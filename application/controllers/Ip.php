<?php
require_once("Home.php"); // loading home controller

class Ip extends Home
{

    public $user_id;    
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');       
 
        $this->user_id=$this->session->userdata('user_id');
        set_time_limit(0);

        $this->important_feature();

        $this->member_validity();

        if($this->session->userdata('user_type') != 'Admin' && !in_array(6,$this->module_access))
        redirect('home/login_page', 'location'); 
    }
	
	
	public function index()
    {
        $this->my_ip_address();
    }

    public function my_ip_address()
    {
        $this->load->library('Web_common_report');
        $data['body'] = 'ip_analysis/my_ip_info';
        $data['page_title'] = $this->lang->line("My Ip Information");
        $data["my_ip"]=$this->real_ip();
        $data["ip_info"]=$this->web_common_report->ip_info($data["my_ip"]);
        $this->_viewcontroller($data);
    }





    public function domain_info()
    {
        $data['body'] = 'ip_analysis/domain_info';
        $data['page_title'] = $this->lang->line("Domain Ip Information");
        $this->_viewcontroller($data);
    }

    public function analysis_new()
    {
       $data['body'] = 'ip_analysis/domain_info_new';
       $data['page_title'] = $this->lang->line("Domain Ip Information");
       $this->_viewcontroller($data); 
    }
    

    public function domain_info_data()
    {

        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','domain_name','isp','ip','organization','country','city','time_zone','latitude','longitude','searched_at');
        $search_columns = array('domain_name','searched_at');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 2;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;

        $where_simple=array();

        if($post_date_range!="")
        {
            $exp = explode('|', $post_date_range);
            $from_date = isset($exp[0])?$exp[0]:"";
            $to_date   = isset($exp[1])?$exp[1]:"";

            if($from_date!="Invalid date" && $to_date!="Invalid date")
            {
                $from_date = date('Y-m-d', strtotime($from_date));
                $to_date   = date('Y-m-d', strtotime($to_date));
                $where_simple["Date_Format(searched_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(searched_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") 
            $where_simple['ip_domain_info.domain_name like'] = "%".$searching."%";

        $where_simple['ip_domain_info.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "ip_domain_info";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        for($i=0;$i<count($info);$i++)
        {  
         $info[$i]['searched_at'] = date("jS M y",strtotime($info[$i]["searched_at"]));
        }
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function domain_info_action()
    {
        $this->load->library('web_common_report');
        $urls=strip_tags($this->input->post('domain_name', true));
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);

        //************************************************//
        $status=$this->_check_usage($module_id=6,$request=count($url_array));
        if($status=="2") 
        {
            echo "<div class='card-body'>
                    <div class='alert alert-warning alert-has-icon'>
                     <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                     <div class='alert-body'>
                        <div class='alert-title'>".$this->lang->line('warning')."</div>
                       ".$this->lang->line("Sorry, your bulk limit is exceeded for this module.")."
                        <a target='_BLANK' href='".base_url("payment/usage_history")."'>".$this->lang->line("click here to see usage log.")."</a>
                     </div>
                    </div>
                </div>";
            exit();
        }
        else if($status=="3") 
        {
            echo "<div class='card-body'>
                    <div class='alert alert-warning alert-has-icon'>
                     <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                     <div class='alert-body'>
                        <div class='alert-title'>".$this->lang->line('warning')."</div>
                        ".$this->lang->line("sorry, your monthly limit is exceeded for this module.")."
                        <a target='_BLANK' href='".base_url("payment/usage_history")."'>".$this->lang->line("click here to see usage log.")."</a>
                     </div>
                    </div>
                </div>";

            exit();
        }
        //************************************************//
        
      
        $this->session->set_userdata('domain_info_bulk_total_search',count($url_array));
        $this->session->set_userdata('domain_info_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/ip/domain_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
          
        /**Write header in csv file***/

        $write_data=array();            
        $write_data[]="Domain";            
        $write_data[]="ISP";            
        $write_data[]="IP";   
        $write_data[]="Organization";         
        $write_data[]="Country";            
        $write_data[]="Region";            
        $write_data[]="City";            
        $write_data[]="Time Zone";            
        $write_data[]="Latitude";            
        $write_data[]="Longitude";            
                                        
        
        fputcsv($download_path, $write_data);

        $str = "<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fas fa-map-marker-alt'></i> ".$this->lang->line("Domain IP Information")."</h4>
                    <div class='card-header-action'>
                      <div class='badges'>
                        <a  class='btn btn-primary float-right' href='".base_url()."/download/ip/domain_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
                      </div>                    
                    </div>
                  </div>
                    <div class='card-body'>";

        $str .="<div class='row'>";
        $str .="<div class='col-12 col-sm-12 col-md-4'>
                  <ul class='nav nav-pills flex-column' id='myTab4' role='tablist'>";
        $tab = 0;
        foreach ($url_array as $key => $value) {
             $tab++;
             if ($tab == 1) {
                $str.="<li class='nav-item'>
                              <a class='nav-link active p-3' id='home-tab".$tab."' data-toggle='tab' href='#home".$tab."' role='tab' aria-controls='home' aria-selected='true'>".$value."</a>
                            </li>";
             }
             else{
                $str.="<li class='nav-item'>
                             <a class='nav-link p-3' id='home-tab".$tab."' data-toggle='tab' href='#home".$tab."' role='tab' aria-controls='home' aria-selected='true'>".$value."</a>
                           </li>";
             }

        }

        $str.="</ul>
                </div>";
        //col end
        $str.="<div class='col-12 col-sm-12 col-md-8'>
                  <div class='tab-content no-padding' id='myTab2Content'>";
        $tab = 0;
        foreach ($url_array as $domain) {
            $tab++;
            /***Remove all www. http:// and https:// ****/            
            $domain=str_replace("www.","",$domain);
            $domain=str_replace("http://","",$domain);
            $domain=str_replace("https://","",$domain);

            $domain_data=array();
            $domain_data=$this->web_common_report->get_ip_country($domain,$proxy="");  
            $searched_at= date("Y-m-d H:i:s");
                  
            $write_data=array();
            
            $write_data[]=$domain;
            $write_data[]=$domain_data["isp"];
            $write_data[]=$domain_data["ip"];
            $write_data[]=$domain_data["organization"];
            $write_data[]=$domain_data["country"];
            $write_data[]=$domain_data["region"];
            $write_data[]=$domain_data["city"];
            $write_data[]=$domain_data["time_zone"];
            $write_data[]=$domain_data["latitude"];
            $write_data[]=$domain_data["longitude"];
            fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
            
            $insert_data=array
            (
                'user_id'           => $this->user_id,
                'domain_name'       => $domain,
                'ip'                => $domain_data["ip"],
                'isp'               => $domain_data["isp"],
                'organization'      => $domain_data["organization"],
                'country'           => $domain_data["country"],
                'region'            => $domain_data["region"],
                'city'              => $domain_data["city"],
                'time_zone'         => $domain_data["time_zone"],
                'latitude'          => $domain_data["latitude"],
                'longitude'         => $domain_data["longitude"],
                'searched_at'       => $searched_at,
            );
            if ($tab == 1) {
                $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("ISP")." <span class='badge badge-primary badge-pill'>".$domain_data["isp"]."</span></li>";  
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("IP")." <span class='badge badge-primary badge-pill'>".$domain_data["ip"]."</span></li>";  
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Organization")." <span class='badge badge-primary badge-pill'>".$domain_data["organization"]."</span></li>";    
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Country")." <span class='badge badge-primary badge-pill'>".$domain_data["country"]."</span></li>";  
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Region")." <span class='badge badge-primary badge-pill'>".$domain_data["region"]."</span></li>";  
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("City")." <span class='badge badge-primary badge-pill'>".$domain_data["city"]."</span></li>";  
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Time Zone")." <span class='badge badge-primary badge-pill'>".$domain_data["time_zone"]."</span></li>";  
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Latitude")." <span class='badge badge-primary badge-pill'>".$domain_data["latitude"]."</span></li>";  
                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Longitude")." <span class='badge badge-primary badge-pill'>".$domain_data["longitude"]."</span></li>";  

                $str.= "</ul></div>";
            }
            else{
                $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("ISP")." <span class='badge badge-primary badge-pill'>".$domain_data["isp"]."</span></li>";  
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("IP")." <span class='badge badge-primary badge-pill'>".$domain_data["ip"]."</span></li>";  
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Organization")." <span class='badge badge-primary badge-pill'>".$domain_data["organization"]."</span></li>";    
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Country")." <span class='badge badge-primary badge-pill'>".$domain_data["country"]."</span></li>";  
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Region")." <span class='badge badge-primary badge-pill'>".$domain_data["region"]."</span></li>";  
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("City")." <span class='badge badge-primary badge-pill'>".$domain_data["city"]."</span></li>";  
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Time Zone")." <span class='badge badge-primary badge-pill'>".$domain_data["time_zone"]."</span></li>";  
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Latitude")." <span class='badge badge-primary badge-pill'>".$domain_data["latitude"]."</span></li>";  
                         $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Longitude")." <span class='badge badge-primary badge-pill'>".$domain_data["longitude"]."</span></li>";                  
                $str.= "</ul></div>";
            }

            $this->basic->insert_data('ip_domain_info', $insert_data);
        }
        $str.="</div>
                </div>";

        $this->_insert_usage_log($module_id=6,$request=count($url_array)); 
        echo $str.="</div></div></div>";

    }

    public function read_text_csv_file_backlink()
    {
          if ($_SERVER['REQUEST_METHOD'] === 'GET') exit();

          $ret=array();
          $output_dir = FCPATH."upload/tmp";
          if (isset($_FILES["myfile"])) {
              $error =$_FILES["myfile"]["error"];
              $post_fileName =$_FILES["myfile"]["name"];
              $post_fileName_array=explode(".", $post_fileName);
              $ext=array_pop($post_fileName_array);
              $filename=implode('.', $post_fileName_array);
              $filename="domain_ip_analysis_".$this->user_id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;

              $allow=".csv,.txt";
              $allow=str_replace('.', '', $allow);
              $allow=explode(',', $allow);

              if(!in_array(strtolower($ext), $allow)) 
              {
                  echo json_encode(array("are_u_kidding_me" => "yarki"));
                  exit();
              }

              
              move_uploaded_file($_FILES["myfile"]["tmp_name"], $output_dir.'/'.$filename);

              $path = realpath(FCPATH."upload/tmp/".$filename);
              $read_handle=fopen($path, "r");
              $context_array =array('file_name'=>$filename);
              $context ="";
              while (!feof($read_handle)) 
              {
                  $information = fgetcsv($read_handle);
                  if (!empty($information)) 
                  {
                      foreach ($information as $info) 
                      {
                          if (!is_numeric($info)) 
                          $context.=$info."\n";                       
                      }
                  }
              }

              $context_array['content'] = trim($context, "\n");
              echo json_encode($context_array);
              
          }
    }

    public function read_after_delete_csv_txt() // deletes the uploaded video to upload another one
    {
          if(!$_POST) exit();
         
          $output_dir = FCPATH."upload/tmp/";
          if(isset($_POST["op"]) && $_POST["op"] == "delete" && isset($_POST['name']))
          {
               $fileName =$_POST['name'];
               $fileName=str_replace("..",".",$fileName); //required. if somebody is trying parent folder files
               $filePath = $output_dir. $fileName;
               if (file_exists($filePath))
               {
                  unlink($filePath);
               }
          }
    }

    public function domain_info_download()
    {
        $all=$this->input->post("ids");
        $table = 'ip_domain_info';
        $where=array();
        if($all!=0)
        {
            $id_array = array();
            foreach ($all as  $value) 
            {
                $id_array[] = $value;
            }
            $where['where_in'] = array('id' => $id_array);
        }

        $where['where'] = array('user_id'=>$this->user_id);

        $info = $this->basic->get_data($table, $where, $select ='', $join='', $limit='', $start=null, $order_by='id asc');
        $download_id=$this->session->userdata('download_id');
        $fp = fopen("download/ip/domain_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        

        $write_data=array();            
        $write_data[]="Domain";            
        $write_data[]="ISP";            
        $write_data[]="IP";            
        $write_data[]="Country";            
        $write_data[]="Region";            
        $write_data[]="City";            
        $write_data[]="Time Zone";            
        $write_data[]="Latitude";            
        $write_data[]="Longitude";  
        $write_data[]="Searched at";  
                    
        fputcsv($fp, $write_data);
        $write_info = array();

        foreach ($info as  $value) 
        {
         
                $write_data=array();            
                $write_data[]=$value["domain_name"];            
                $write_data[]=$value["isp"];            
                $write_data[]=$value["ip"];            
                $write_data[]=$value["country"];   
                $write_data[]=$value["region"];   
                $write_data[]=$value["city"];     
                $write_data[]=$value["time_zone"];            
                $write_data[]=$value["latitude"];          
                $write_data[]=$value["longitude"];   
                $write_data[]=$value["searched_at"];   
            
                fputcsv($fp, $write_data);
        }

        fclose($fp);
        $file_name = "download/ip/domain_{$this->user_id}_{$download_id}.csv";
       
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }


    

    public function domain_info_delete()
    {
        $all=$this->input->post("ids");

        if($all!=0)
        {

            $id_array = array();
            foreach ($all as  $value) 
            {
                $id_array[] = $value;
            }     
            $this->db->where_in('id', $id_array);
        }
        $this->db->where('user_id', $this->user_id);
        $this->db->delete('ip_domain_info');
    }
   
    public function bulk_domain_info_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('domain_info_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('domain_info_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }




    public function site_this_ip()
    {
        $data['body'] = 'ip_analysis/site_this_ip';
        $data['page_title'] = $this->lang->line("Sites in same ip");
        $this->_viewcontroller($data);
    }
    public function sites_same_ip()
    {
        $data['body'] = 'ip_analysis/site_this_ip_new';
        $data['page_title'] = $this->lang->line("Sites in same ip");
        $this->_viewcontroller($data);
    }

    public function site_this_ip_data()
    {

        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','ip','searched_at');
        $search_columns = array('ip','searched_at');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 2;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;

        $where_simple=array();

        if($post_date_range!="")
        {
            $exp = explode('|', $post_date_range);
            $from_date = isset($exp[0])?$exp[0]:"";
            $to_date   = isset($exp[1])?$exp[1]:"";

            if($from_date!="Invalid date" && $to_date!="Invalid date")
            {
                $from_date = date('Y-m-d', strtotime($from_date));
                $to_date   = date('Y-m-d', strtotime($to_date));
                $where_simple["Date_Format(searched_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(searched_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") 
            $where_simple['ip_same_site.ip like'] = "%".$searching."%";

        $where_simple['ip_same_site.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "ip_same_site";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        for($i=0;$i<count($info);$i++)
        {  
         $info[$i]['searched_at'] = date("jS M y",strtotime($info[$i]["searched_at"]));
        }
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function site_this_ip_action()
    {
        //************************************************//
        $status=$this->_check_usage($module_id=6,$request=1);
        if($status=="2") 
        {
            echo "<div class='card-body'>
                    <div class='alert alert-warning alert-has-icon'>
                     <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                     <div class='alert-body'>
                        <div class='alert-title'>".$this->lang->line('warning')."</div>
                       ".$this->lang->line("Sorry, your bulk limit is exceeded for this module.")."
                        <a target='_BLANK' href='".base_url("payment/usage_history")."'>".$this->lang->line("click here to see usage log.")."</a>
                     </div>
                    </div>
                </div>";
            exit();
        }
        else if($status=="3") 
        {

            echo "<div class='card-body'>
                    <div class='alert alert-warning alert-has-icon'>
                     <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                     <div class='alert-body'>
                        <div class='alert-title'>".$this->lang->line('warning')."</div>
                       ".$this->lang->line("Sorry, your monthly limit is exceeded for this module.")."
                        <a target='_BLANK' href='".base_url("payment/usage_history")."'>".$this->lang->line("click here to see usage log.")."</a>
                     </div>
                    </div>
                </div>";
            exit();
        }
        //************************************************//

        $this->load->library('web_common_report');
        $ip=strip_tags($this->input->post('domain_name', true));
       
       
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/ip/same_site_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
     
        
        /**Write header in csv file***/

        $write_data=array();              
        $write_data[]="IP";            
        $write_data[]="Website";            
                                        
        
        fputcsv($download_path, $write_data);
        
        $site_this_ip_complete=0;

        $count=0;
        $str= "<div class='card card-hero'>
                  <div class='card-header' style='border-radius:0!important;'>
                    <div class='card-description'>".$this->lang->line("Sites In Same IP : ").$ip."</div>
                    <div class='card-header-action'>
                      <div class='badges'>
                        <a  class='btn btn-primary float-right' href='".base_url()."/download/ip/same_site_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
                      </div>                    
                    </div>
                  </div>
                  <div class='card-body'>
                    <div class='tickets-list'>";
  
        $same_site_data=array();
        $this->web_common_report->get_site_in_same_ip($ip,$page=1,$proxy="");  
        $same_site_data=$this->web_common_report->same_site_in_ip;  
        $this->session->set_userdata('site_this_ip_complete_search',0);
        $this->session->set_userdata('site_this_ip_bulk_total_search',count($same_site_data));
        $searched_at= date("Y-m-d H:i:s");
               
       
       foreach ($same_site_data as $key => $value) 
       {
            $count++;
            //$site_linkable="<a target='_BLANL' title='Visit Now' href='".addHttp($value)."'>".$value."</a>";
            $str.="<a href='".addHttp($value)."' class='ticket-item' target='_BLANK'>
                    <div class='ticket-title'>
                      <h4>".$value."</h4>
                    </div>
                  </a>";

            $write_data=array(); 
            $write_data[]=$ip;
            $write_data[]=$value;
            fputcsv($download_path, $write_data);

            $site_this_ip_complete++;
            $this->session->set_userdata("site_this_ip_complete_search",$site_this_ip_complete);  
       }

       if(count($same_site_data)==0) $str.="<h4 class='text-center mt-5 mb-5'>".$this->lang->line("No data found!")."</h4>";
        
         /** Insert into database ***/
        $insert_data=array
        (
            'user_id'           => $this->user_id,
            'ip'                => $ip,
            'website'           => json_encode($same_site_data),
            'searched_at'       => $searched_at
        );
       
       //******************************//
        // insert data to useges log table
        $this->_insert_usage_log($module_id=6,$request=1);   
        //******************************//
        
       if($this->basic->insert_data('ip_same_site', $insert_data))
       echo $str.="</div></div></div>";

    }

  

    public function site_this_ip_download()
    {
        $all=$this->input->post("ids");
        $table = 'ip_same_site';
        $where=array();
        if($all!=0)
        {
            $id_array = array();
            foreach ($all as  $value) 
            {
                $id_array[] = $value;
            }
            $where['where_in'] = array('id' => $id_array);
        }

        $where['where'] = array('user_id'=>$this->user_id);

        $info = $this->basic->get_data($table, $where, $select ='', $join='', $limit='', $start=null, $order_by='id asc');
        $download_id=$this->session->userdata('download_id');
        $fp = fopen("download/ip/same_site_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        
        $write_data=array();            
        $write_data[]="IP";            
        $write_data[]="Website"; 
        $write_data[]="Searched at";  
                    
        fputcsv($fp, $write_data);
        $write_info = array();

        foreach ($info as  $value) 
        {
            $website_array=json_decode($value["website"],true);
            foreach ($website_array as $row) 
            {
                $write_data=array();            
                $write_data[]=$value["ip"];    
                $write_data[]=$row;    
                $write_data[]=$value["searched_at"];    
            
                fputcsv($fp, $write_data);
            }                
        }
        fclose($fp);
        $file_name = "download/ip/same_site_{$this->user_id}_{$download_id}.csv";
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }

    

    public function site_this_ip_delete()
    {
        $all=$this->input->post("ids");

        if($all!=0)
        {

            $id_array = array();
            foreach ($all as  $value) 
            {
                $id_array[] = $value;
            }     
            $this->db->where_in('id', $id_array);
        }
        $this->db->where('user_id', $this->user_id);
        $this->db->delete('ip_same_site');
    }

   
    public function bulk_site_this_ip_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('site_this_ip_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('site_this_ip_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }


    public function ipv6_check()
    {
        $data['body'] = 'ip_analysis/ipv6_check';
        $data['page_title'] = $this->lang->line("IPv6 Compability Check");
        $this->_viewcontroller($data);
    }
    public function check_ipv6()
    {

       $data['body'] = 'ip_analysis/ipv6_check_new';
       $data['page_title'] = $this->lang->line("IPv6 Compability Check");
       $this->_viewcontroller($data); 
    }
    

    public function ipv6_check_data()
    {

        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','domain_name','ip','ipv6','searched_at');
        $search_columns = array('ip','searched_at');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 2;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;

        $where_simple=array();

        if($post_date_range!="")
        {
            $exp = explode('|', $post_date_range);
            $from_date = isset($exp[0])?$exp[0]:"";
            $to_date   = isset($exp[1])?$exp[1]:"";

            if($from_date!="Invalid date" && $to_date!="Invalid date")
            {
                $from_date = date('Y-m-d', strtotime($from_date));
                $to_date   = date('Y-m-d', strtotime($to_date));
                $where_simple["Date_Format(searched_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(searched_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") 
            $where_simple['ip_v6_check.domain_name like'] = "%".$searching."%";

        $where_simple['ip_v6_check.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "ip_v6_check";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        for($i=0;$i<count($info);$i++)
        {  
         if ($info[$i]['ipv6'] == 'Not Compatible') 
               $info[$i]['ipv6'] = "<span><i class='fas fa-times-circle text-danger'></i> ".$info[$i]['ipv6']."</span>";

         $info[$i]['searched_at'] = date("jS M y",strtotime($info[$i]["searched_at"]));
        }
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function ipv6_check_action()
    {
        $this->load->library('web_common_report');
        $urls=strip_tags($this->input->post('domain_name', true));
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);

        //************************************************//
        $status=$this->_check_usage($module_id=6,$request=count($url_array));
        if($status=="2") 
        {
            echo "<div class='card-body'>
                    <div class='alert alert-warning alert-has-icon'>
                     <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                     <div class='alert-body'>
                        <div class='alert-title'>".$this->lang->line('warning')."</div>
                       ".$this->lang->line("Sorry, your bulk limit is exceeded for this module.")."
                        <a target='_BLANK' href='".base_url("payment/usage_history")."'>".$this->lang->line("click here to see usage log.")."</a>
                     </div>
                    </div>
                </div>";
            exit();
        }
        else if($status=="3") 
        {
            echo "<div class='card-body'>
                    <div class='alert alert-warning alert-has-icon'>
                     <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                     <div class='alert-body'>
                        <div class='alert-title'>".$this->lang->line('warning')."</div>
                       ".$this->lang->line("Sorry, your monthly limit is exceeded for this module.")."
                        <a target='_BLANK' href='".base_url("payment/usage_history")."'>".$this->lang->line("click here to see usage log.")."</a>
                     </div>
                    </div>
                </div>";
            exit();
        }
        //************************************************//
        
      
        $this->session->set_userdata('ipv6_check_bulk_total_search',count($url_array));
        $this->session->set_userdata('ipv6_check_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/ip/ipv6_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
          
        /**Write header in csv file***/

        $write_data=array();            
        $write_data[]="Domain";          
        $write_data[]="IP";            
        $write_data[]="IPv6";            
        $write_data[]="Searched at";            
                                        
        fputcsv($download_path, $write_data);

        $str = "<div class='card'>
                   <div class='card-header'>
                     <h4><i class='fas fa-map-marker-alt'></i> ".$this->lang->line("IPv6 Compability Check")."</h4>
                      <div class='card-header-action'>
                        <div class='badges'>
                          <a  class='btn btn-primary float-right' href='".base_url()."/download/ip/ipv6_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
                        </div>                    
                      </div>
                   </div>
                  <div class='card-body'>";

        $str .="<div class='row'>";
        $str .="<div class='col-12 col-sm-12 col-md-4'>
                  <ul class='nav nav-pills flex-column' id='myTab4' role='tablist'>";
        $tab = 0;
        foreach ($url_array as $key => $value) {
             $tab++;
             if ($tab == 1) {
                $str.="<li class='nav-item'>
                              <a class='nav-link active p-3' id='home-tab".$tab."' data-toggle='tab' href='#home".$tab."' role='tab' aria-controls='home' aria-selected='true'>".$value."</a>
                            </li>";
             }
             else{
                $str.="<li class='nav-item'>
                             <a class='nav-link p-3' id='home-tab".$tab."' data-toggle='tab' href='#home".$tab."' role='tab' aria-controls='home' aria-selected='true'>".$value."</a>
                           </li>";
             }

        }

        $str.="</ul>
                </div>";
        //col end
        $str.="<div class='col-12 col-sm-12 col-md-8'>
                  <div class='tab-content no-padding' id='myTab2Content'>";
        $tab = 0;
        foreach ($url_array as $domain) {
            $tab++;
            $domain_data=array();
            $domain_data=$this->web_common_report->ipv6_check($domain);  
            $searched_at= date("Y-m-d H:i:s");
                  
            $write_data=array();
            
            $write_data[]=$domain;
            $write_data[]=$domain_data["ip"];

            if($domain_data["is_ipv6_support"]=="1")
                $ipv6=$domain_data["ipv6"];
            else 
                $ipv6="Not Compatible";
            $write_data[]=$ipv6;

            $write_data[]=$searched_at;
            fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
            
            $insert_data=array
            (
                'user_id'           => $this->user_id,
                'domain_name'       => $domain,
                'ip'                => $domain_data["ip"],
                'ipv6'              => $ipv6,
                'is_ipv6_support'   => $domain_data["is_ipv6_support"],
                'searched_at'       => $searched_at
            );

            if ($tab == 1) {
                $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('IP')."<span class='badge badge-primary badge-pill'>".$domain_data["ip"]."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('IPv6')."<span class='badge badge-primary badge-pill'>".$ipv6."</span></li>";

                $str.= "</ul></div>";
            }
            else{
                $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('IP')."<span class='badge badge-primary badge-pill'>".$domain_data["ip"]."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('IPv6')."<span class='badge badge-primary badge-pill'>".$ipv6."</span></li>";
                
                $str.= "</ul></div>";
            }

            $this->basic->insert_data('ip_v6_check', $insert_data);
        }
        $str.="</div>
                </div>";

        $this->_insert_usage_log($module_id=6,$request=count($url_array)); 
        echo $str.="</div></div></div>";

    }

  

    public function ipv6_check_download()
    {
        $all=$this->input->post("ids");
        $table = 'ip_v6_check';
        $where=array();
        if($all!=0)
        {
            $id_array = array();
            foreach ($all as  $value) 
            {
                $id_array[] = $value;
            }
            $where['where_in'] = array('id' => $id_array);
        }

        $where['where'] = array('user_id'=>$this->user_id);

        $info = $this->basic->get_data($table, $where, $select ='', $join='', $limit='', $start=null, $order_by='id asc');
        $download_id=$this->session->userdata('download_id');
        $fp = fopen("download/ip/ipv6_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        

        $write_data=array();            
        $write_data[]="Domain";          
        $write_data[]="IP";            
        $write_data[]="IPv6";            
        $write_data[]="Searched at";   
                    
        fputcsv($fp, $write_data);
        $write_info = array();

        foreach ($info as  $value) 
        {
         
                if($value["is_ipv6_support"]=="1")
                $ipv6=$value["ipv6"];
                else $ipv6="Not Compatible";

                $write_data=array();            
                $write_data[]=$value["domain_name"];      
                $write_data[]=$value["ip"];            
                $write_data[]=$ipv6;    
                $write_data[]=$value["searched_at"];   
            
                fputcsv($fp, $write_data);
        }

        fclose($fp);
        $file_name = "download/ip/ipv6_{$this->user_id}_{$download_id}.csv";
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }


    

    public function ipv6_check_delete()
    {
        $all=$this->input->post("ids");

        if($all!=0)
        {

            $id_array = array();
            foreach ($all as  $value) 
            {
                $id_array[] = $value;
            }     
            $this->db->where_in('id', $id_array);
        }
        $this->db->where('user_id', $this->user_id);
        $this->db->delete('ip_v6_check');
    }
   
    public function bulk_ipv6_check_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('ipv6_check_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('ipv6_check_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }



    public function ip_canonical_check()
    {
        $this->load->library('Web_common_report');
        $data['body'] = 'ip_analysis/ip_canonical_check';
        $data['page_title'] = $this->lang->line("IP Canonical Check");

        $data['table_data'] = NULL;
        if($_POST) :
            $this->load->library('form_validation');
            //$this->form_validation->set_rules('domain', 'Domain', array('required', array('valid_url', array($this->basic, 'valid_url'))), array('valid_url' => 'Invalid url'));
            $this->form_validation->set_rules('domain', 'Domain', 'required');

           if($this->form_validation->run() == TRUE) :
                $this->load->library('web_common_report');
                $table_data = $this->web_common_report->ip_canonical_check($this->input->post('domain')); 
                $data['table_data'] = $table_data;
                $data['table_data']['domain'] = $this->input->post('domain');           
           endif; 
        endif;    

        $this->_viewcontroller($data);

    }

    public function ip_canonical_action()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') :
            redirect('home/access_forbidden', 'location');     
        endif;       

        $this->load->library('web_common_report');
        $domain_lists = array();
        $domain_values = explode(',',strip_tags($this->input->post('domain_name',true)));
   
        $str = "<div class='card'>
                    <div class='card-header'>
                      <h4><i class='fas fa-map-marker-alt'></i> ".$this->lang->line("IP Canonical Check")."</h4>
                    </div>
                    <div class='card-body'>";

        $str .="<div class='row'>";
        $str .="<div class='col-12 col-sm-12 col-md-4'>
                  <ul class='nav nav-pills flex-column' id='myTab4' role='tablist'>";
        if (count($domain_values) <= 50) {
            $tab = 0;
            foreach ($domain_values as $key => $value) {
                 $tab++;

                 if ($tab == 1) {
                    $str.="<li class='nav-item'>
                                  <a class='nav-link active p-3' id='home-tab".$tab."' data-toggle='tab' href='#home".$tab."' role='tab' aria-controls='home' aria-selected='true'>".$value."</a>
                                </li>";
                 }
                 else{
                    $str.="<li class='nav-item'>
                                 <a class='nav-link p-3' id='home-tab".$tab."' data-toggle='tab' href='#home".$tab."' role='tab' aria-controls='home' aria-selected='true'>".$value."</a>
                               </li>";
                 }

            }
        }
        $str.="</ul>
                </div>";
        //col end
        $str.="<div class='col-12 col-sm-12 col-md-8'>
                  <div class='tab-content no-padding' id='myTab2Content'>";
        if (count($domain_values) <= 50) {

           $tab = 0;
           foreach ($domain_values as $domain_value) {
               $tab++;
               $domain_value = trim($domain_value);
               if (is_valid_domain_name($domain_value) === TRUE) {
                   $check_data = $this->web_common_report->ip_canonical_check($domain_value);
                   if ($check_data['ip_canonical'] =='1')
                       $check_data['ip_canonical'] = "Yes";
                    else
                       $check_data['ip_canonical'] = "No";
                        
               }
               if ($tab == 1) {
                   $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                           <ul class='list-group'>";

                       $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('IP')."<span class='badge badge-primary badge-pill'>".$check_data['ip']."</span></li>";
                       $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Canonical')."<span class='badge badge-primary badge-pill'>".$check_data['ip_canonical']."</span></li>";

                   $str.= "</ul></div>";
               }
               else{
                   $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                           <ul class='list-group'>";
                           
                       $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('IP')."<span class='badge badge-primary badge-pill'>".$check_data['ip']."</span></li>";
                       $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Canonical')."<span class='badge badge-primary badge-pill'>".$check_data['ip_canonical']."</span></li>";
                   
                   $str.= "</ul></div>";
               }

           }

        }
        $str.="</div>
                </div>";

        echo $str.="</div></div></div>";


    }


    public function balcklist_check($ip="")
    {
        $data['body'] = "admin/ip/blacklist_check";
        $data['page_title'] = $this->lang->line("Blacklist Checker");
        $data['check_ip']=$ip;
        $this->_viewcontroller($data);
    }


    public function blacklist_check_data()
    {
        $this->load->library('Web_common_report');
        $ip_address = $this->input->post('ip_address');
        $blacklist_details = $this->web_common_report->check_black_list($ip_address);

        $str = '<table class="table table-bordered table-hover">
                    <tr><th>Site</th><th>Status</th></tr>';
        foreach($blacklist_details as $value)
        {
            $str .= '<tr><td>'.$value['domain'].'</td><td><img src="'.$value['status_image_link'].'" /></td></tr>';
        }

        $str .= '</table>';

        $data["ip_info"]=$this->web_common_report->ip_info($ip_address);
        $data["my_ip"]=$ip_address;
        $data["google_api"]=$this->basic->get_data("config",array("where"=>array("user_id"=>$this->session->userdata("user_id"))));
        $str.=$this->load->view("admin/ip/ip_info",$data);

        echo $str;


    }


    public function ip_traceout($ip="")
    {
        
        $data['body'] = "ip_analysis/ip_traceout";
        $data['page_title'] = $this->lang->line("IP Traceroute");
        $data['check_ip']=$ip;
        $this->_viewcontroller($data);
    }


    public function traceout_check_data()
    {
        $this->load->library('Web_common_report');
        $ip_address = $this->input->post('domain_name');
        $blacklist_details = $this->web_common_report->ip_traceout($ip_address);

        $str = "<div class='card'>
        <div class='card-header'>
          <h4><i class='fas fa-map-marker-alt'></i> ".$this->lang->line("IP Traceroute")."</h4>

        </div>
        <div class='card-body p-0'>
          <div class='table-responsive'>
            <table class='table table-hover table-bordered'>
              <tbody>
                <tr>
                  <th>".$this->lang->line("Hop")."</th>
                  <th>".$this->lang->line("Time")."</th>
                  <th>".$this->lang->line("Host")."</th>
                  <th>".$this->lang->line("IP")."</th>
                  <th>".$this->lang->line("Location")."</th>
                </tr>";

        if(is_array($blacklist_details) && isset($blacklist_details[0]))unset($blacklist_details[0]);
        if(is_array($blacklist_details))
        foreach($blacklist_details as $value)
        {

            $str .="<tr>
            <td>".$value['hop']."</td>
            <td>".$value['time']."</td>
            <td>".$value['host']."</td>
            <td>".$value['ip']."</td>
            <td>".$value['location']."</td>
            </tr>";

        }

        $str .= '</tbody></table></div></div></div>';

        
        $data["ip_info"]=$this->web_common_report->ip_info($ip_address);
        $data["my_ip"]=$ip_address;

        $data["google_api"]=$this->basic->get_data("config",array("where"=>array("user_id"=>$this->session->userdata("user_id"))));
        $str.=$this->load->view("ip_analysis/ip_info",$data);

        echo $str;

    }


	
	
}
?>