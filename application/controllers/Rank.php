<?php

require_once("Home.php"); // loading home controller

class Rank extends Home
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

    }

    public function index()
    {
        $this->alexa_rank();
    }

    public function alexa_rank()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(4,$this->module_access))
        redirect('home/login_page', 'location'); 

        $data['body'] = 'alexa_rank/alexa_rank_list';
        $data['page_title'] = $this->lang->line("Alexa Rank");
        $this->_viewcontroller($data);
    }

    public function rank_alexa()
    {
        $data['body'] = 'alexa_rank/alexa_rank';
        $data['page_title'] = $this->lang->line("Alexa Rank");
        $this->_viewcontroller($data);
    }
    

    public function alexa_rank_data()
    {

        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','domain_name','reach_rank','country','country_rank','traffic_rank','checked_at');
        $search_columns = array('domain_name','checked_at');

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
                $where_simple["Date_Format(checked_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(checked_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") 
            $where_simple['alexa_info.domain_name like'] = "%".$searching."%";

        $where_simple['alexa_info.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "alexa_info";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        for($i=0;$i<count($info);$i++)
        {  
         $info[$i]['checked_at'] = date("jS M y",strtotime($info[$i]["checked_at"]));
    
        }
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function alexa_rank_action()
    {
               
        $this->load->library('web_common_report');
        $urls=strip_tags($this->input->post('domain_name', true));
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);


        //************************************************//
        $status=$this->_check_usage($module_id=4,$request=count($url_array));
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
        
      
        $this->session->set_userdata('alexa_rank_bulk_total_search',count($url_array));
        $this->session->set_userdata('alexa_rank_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/rank/alexa_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        $total_count=0;
        
        /**Write header in csv file***/
        $write_data[]="Domain";            
        $write_data[]="Reach Rank";            
        $write_data[]="Country";            
        $write_data[]="Country Rank";            
        $write_data[]="Traffic Rank";                
        
        fputcsv($download_path, $write_data);

        $str = "<div class='card'>
                    <div class='card-header'>
                      <h4><i class='fas fa-trophy'></i> ".$this->lang->line("Alexa Rank")."</h4>
                        <div class='card-header-action'>
                          <div class='badges'>
                            <a  class='btn btn-primary float-right' href='".base_url()."/download/rank/alexa_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
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
            $domain=str_replace("www.","",$domain);
            $domain=str_replace("http://","",$domain);
            $domain=str_replace("https://","",$domain);

            $alexa_data=array();
            $alexa_data=$this->web_common_report->get_alexa_rank($domain);  
            $checked_at= date("Y-m-d H:i:s");
                  
            $write_data=array();
            $write_data[]=$domain;
            $write_data[]=$alexa_data["reach_rank"];
            $write_data[]=$alexa_data["country"];
            $write_data[]=$alexa_data["country_rank"];
            $write_data[]=$alexa_data["traffic_rank"];

            $reach_rank=$alexa_data["reach_rank"];
            $country=$alexa_data["country"];
            $country_rank=$alexa_data["country_rank"];
            $traffic_rank=$alexa_data["traffic_rank"];
            
            fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
            
            $insert_data=array
            (
                'user_id'           => $this->user_id,
                'domain_name'       => $domain,
                'reach_rank'        => $reach_rank,
                'country'           => $country,
                'country_rank'      => $country_rank,
                'traffic_rank'      => $traffic_rank,
                'checked_at'        => $checked_at,
            );
            if ($tab == 1) {
                $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";
                         $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Reach Rank')."<span class='badge badge-primary badge-pill'>{$reach_rank}</span></li>";
                         $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Top Country')."<span class='badge badge-primary badge-pill'>{$country}</span></li>";
                         
                         $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Top Country Rank')."<span class='badge badge-primary badge-pill'>{$country_rank}</span></li>";
                         
                         $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Traffic Rank')."<span class='badge badge-primary badge-pill'>{$traffic_rank}</span></li>";
                $str.= "</ul></div>";
            }
            else{
                $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";
                         $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Reach Rank')."<span class='badge badge-primary badge-pill'>{$reach_rank}</span></li>";
                         $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Top Country')."<span class='badge badge-primary badge-pill'>{$country}</span></li>";
                        
                         $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Top Country Rank')."<span class='badge badge-primary badge-pill'>{$country_rank}</span></li>";
                        
                         $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Traffic Rank')."<span class='badge badge-primary badge-pill'>{$traffic_rank}</span></li>";                
                $str.= "</ul></div>";
            }

            $this->basic->insert_data('alexa_info', $insert_data);
        }
        $str.="</div>
                </div>";

        $this->_insert_usage_log($module_id=4,$request=count($url_array)); 
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
              $filename="alexa_rank_analysis_".$this->user_id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;

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
  

    public function alexa_rank_download()
    {
        $all=$this->input->post("ids");
        $table = 'alexa_info';
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
        $fp = fopen("download/rank/alexa_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        $head=array("Doamin","Reach Rank","Country","Country Rank","Traffic Rank","Checked at");
                    
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) 
        {
         
            $write_info['domain_name'] = $value['domain_name'];
            $write_info['google_status'] = $value['reach_rank'];
            $write_info['macafee_status'] = $value['country'];
            $write_info['avg_status'] = $value['country_rank'];
            $write_info['norton_status'] = $value['traffic_rank'];
            $write_info['checked_at'] = $value['checked_at'];
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/rank/alexa_{$this->user_id}_{$download_id}.csv";
       
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }


    

    public function alexa_rank_delete()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(4,$this->module_access))
        exit(); 

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
        $this->db->delete('alexa_info');
    }
   
    public function bulk_alexa_rank_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('alexa_rank_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('alexa_rank_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }


    public function alexa_rank_full()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(4,$this->module_access))
        redirect('home/login_page', 'location'); 

        $data['body'] = 'alexa_rank/alexa_rank_full_list';
        $data['page_title'] = $this->lang->line("Alexa Data");
        $this->_viewcontroller($data);
    }

    public function alexa_data()
    {
        $data['body'] = 'alexa_rank/alexa_full';
        $data['page_title'] = $this->lang->line("Alexa Data");
        $this->_viewcontroller($data);
    }
    

    public function alexa_rank_full_data()
    {

        $this->ajax_check();
        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','domain_name','alexa_rank','bounce_rate','alexa_rank_spend_time','site_search_traffic','status','searched_at','actions');
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
            $where_simple['alexa_info_full.domain_name like'] = "%".$searching."%";

        $where_simple['alexa_info_full.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "alexa_info_full";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        for($i=0;$i<count($info);$i++)
        {  
         $info[$i]['searched_at'] = date("jS M y",strtotime($info[$i]["searched_at"]));

         $view_url = base_url('rank/alexa_details/').$info[$i]['id'];
         $info[$i]['actions'] = "<div><a href='".$view_url."' title='".$this->lang->line("View Report")."' class='btn btn-outline-primary'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";
         $info[$i]['status'] = "<div class='pricing-item-icon'><i class='fas fa-check-circle green'></i> ".$info[$i]['status']."</div>";
        
        }
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function alexa_rank_full_action()
    {
        $this->load->library('web_common_report');
        $urls=strip_tags($this->input->post('domain_name', true));
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);


        //************************************************//
        $status=$this->_check_usage($module_id=4,$request=count($url_array));
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
        
      
        $this->session->set_userdata('alexa_rank_full_bulk_total_search',count($url_array));
        $this->session->set_userdata('alexa_rank_full_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/rank/alexa_full_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        $total_count=0;
        
        /**Write header in csv file***/
        $write_data[]="Domain";                 
        $write_data[]="Alexa Rank";                 
        $write_data[]="Alexa Rank Spend Time";                 
        $write_data[]="Site Search Traffic";                 
        $write_data[]="Bounce Rate";                 
        $write_data[]="Total Linking In Site";                                
        $write_data[]="Top 5 Similar Sites By Audience Overlap";                 
        $write_data[]="Top 5 Keywords By Traffic";                 
        $write_data[]="Top 4 Keyword Gaps";                 
        $write_data[]="Top 4 Easy-to-Rank Keywords";                 
        $write_data[]="Top 4 Buyer Keywords";                 
        $write_data[]="Top 4 Optimization Opportunities";                 
        $write_data[]="Top 5 Referral Sites";                 
        $write_data[]="Top 5 Audience Overlap";                 
        $write_data[]="Top 3 Country";                 
        $write_data[]="Site FLow";                 
                          
        
        fputcsv($download_path, $write_data);
        
        $alexa_rank_full_complete=0;

        $count=0;
        $str = "<div class='card'>
        <div class='card-header'>
          <h4><i class='fas fa-trophy'></i> ".$this->lang->line("Alexa Data")."</h4>
            <div class='card-header-action'>
              <div class='badges'>
                <a  class='btn btn-primary float-right' href='".base_url()."/download/rank/alexa_full_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
              </div>                    
            </div>
        </div>
        <div class='card-body p-0'>
          <div class='table-responsive'>
            <table class='table table-bordered table-hover'>
              <tbody>
                <tr>
                 ";
        $str.="<th>".$this->lang->line("Domain")."</th>";      
        $str.="<th>".$this->lang->line("Status")."</th>";      
        $str.="<th>".$this->lang->line("Details")."</th>";        
        
        $str.="</tr>"; 
        foreach ($url_array as $domain) 
        {        
            /***Remove all www. http:// and https:// ****/            
            $domain=str_replace("www.","",$domain);
            $domain=str_replace("http://","",$domain);
            $domain=str_replace("https://","",$domain);

            $alexa_data_full=array();
            $alexa_data_full=$this->web_common_report->alexa_raw_data($domain); 
         
            $alexa_rank =strip_tags($alexa_data_full["alexa_rank"]);
            $alexa_rank_spend_time =$alexa_data_full["alexa_rank_spend_time"];
            $site_search_traffic =$alexa_data_full["site_search_traffic"];
            $bounce_rate = $alexa_data_full["bounce_rate"];
            $total_sites_linking_in = $alexa_data_full["total_sites_linking_in"];
            $total_keyword_opportunities_breakdown = $alexa_data_full["total_keyword_opportunities_breakdown"];
            $keyword_opportunitites_values = $alexa_data_full["keyword_opportunitites_values"];
            $similar_site = $alexa_data_full["similar_site"];
            $similar_site_overlap = $alexa_data_full["similar_site_overlap"];
            $keyword_top = $alexa_data_full["keyword_top"];
            $search_traffic = $alexa_data_full["search_traffic"];
            $share_voice = $alexa_data_full["share_voice"];
            $keyword_gaps = $alexa_data_full["keyword_gaps"];
            $keyword_gaps_trafic_competitor = $alexa_data_full["keyword_gaps_trafic_competitor"];
            $keyword_gaps_search_popularity = $alexa_data_full["keyword_gaps_search_popularity"];
            $easyto_rank_keyword = $alexa_data_full["easyto_rank_keyword"];
            $easyto_rank_relevence = $alexa_data_full["easyto_rank_relevence"];
            $easyto_rank_search_popularity = $alexa_data_full["easyto_rank_search_popularity"];
            $buyer_keyword = $alexa_data_full["buyer_keyword"];
            $buyer_keyword_traffic_to_competitor = $alexa_data_full["buyer_keyword_traffic_to_competitor"];
            $buyer_keyword_organic_competitor = $alexa_data_full["buyer_keyword_organic_competitor"];
            $optimization_opportunities = $alexa_data_full["optimization_opportunities"];
            $optimization_opportunities_search_popularity = $alexa_data_full["optimization_opportunities_search_popularity"];
            $optimization_opportunities_organic_share_of_voice = $alexa_data_full["optimization_opportunities_organic_share_of_voice"];
            $refferal_sites = $alexa_data_full["refferal_sites"];
            $refferal_sites_links = $alexa_data_full["refferal_sites_links"];
            $top_keywords = $alexa_data_full["top_keywords"];
            $top_keywords_search_traficc = $alexa_data_full["top_keywords_search_traficc"];
            $top_keywords_share_of_voice = $alexa_data_full["top_keywords_share_of_voice"];
            $site_overlap_score = $alexa_data_full["site_overlap_score"];
            $similar_to_this_sites = $alexa_data_full["similar_to_this_sites"];

            $similar_to_this_sites_alexa_rank = $alexa_data_full["similar_to_this_sites_alexa_rank"];
            $card_geography_country = $alexa_data_full["card_geography_country"];
            $card_geography_countryPercent = $alexa_data_full["card_geography_countryPercent"];
            $site_metrics = $alexa_data_full["site_metrics"];
            $site_metrics_domains = $alexa_data_full["site_metrics_domains"];

            $site_metrics_domains_list = array();
            foreach ($site_metrics_domains as $key => $value) {
                    
                     $site_metrics_domains_list[] = strip_tags($value);
            }


            $status = $alexa_data_full["status"]; 
            $searched_at= date("Y-m-d H:i:s");

         	$alexa_rank = $alexa_rank;
         	$site_search_traffic = $site_search_traffic;
         	$alexa_rank_spend_time = $alexa_rank_spend_time;
         	$bounce_rate = $bounce_rate;
         	$total_sites_linking_in = $total_sites_linking_in;
         	$total_keyword_opportunities_breakdown = $total_keyword_opportunities_breakdown;
         	$keyword_opportunitites_values = $keyword_opportunitites_values;
         	$similar_sites = $similar_site;
         	$similar_site_overlap = $similar_site_overlap;

         	$similar_site_overlap_data = array();
         	                    
         	if(is_array($similar_sites) && is_array($similar_site_overlap))
         	{
         	    foreach($similar_sites as $key=>$val)
         	    {                  
         	        if(array_key_exists($key, $similar_sites) && array_key_exists($key, $similar_site_overlap))
         	        $similar_site_overlap_data[$key]="Similar Sites: ".$similar_sites[$key]." Overlap Score : ".$similar_site_overlap[$key];
         	    }
         	}
         	$similar_site_overlap_str=implode(',', $similar_site_overlap_data); 
         	

         	$keyword_top = $keyword_top;
         	$search_traffic = $search_traffic;
         	$share_voice = $share_voice;

         	$top_5_keywords_by_traffic_data = array();
         	if(is_array($keyword_top) && is_array($search_traffic) && is_array($share_voice))
         	{
         		foreach ($keyword_top as $key => $val) 
         		{
         			if(array_key_exists($key, $keyword_top) && array_key_exists($key, $search_traffic) && array_key_exists($key, $share_voice))
         				$top_5_keywords_by_traffic_data[$key] = "(Keywords : " . $keyword_top[$key] .",". "Search Traffic: ". $search_traffic[$key] .",". "Share Of Voice : " . $share_voice[$key] .")";
         		}
         	}
         	$top_5_keywords_by_traffic_str = implode(',', $top_5_keywords_by_traffic_data);
      
         	$keyword_gaps = $keyword_gaps;
         	$keyword_gaps_trafic_competitor = $keyword_gaps_trafic_competitor;
         	$keyword_gaps_search_popularity = $keyword_gaps_search_popularity;
 
         	$top_4_keyword_gaps_data = array();
         	if(is_array($keyword_gaps) && is_array($keyword_gaps_trafic_competitor) && is_array($keyword_gaps_search_popularity))
         	{
         		foreach ($keyword_gaps as $key => $val) 
         		{
         			if(array_key_exists($key, $keyword_gaps) && array_key_exists($key, $keyword_gaps_trafic_competitor) && array_key_exists($key, $keyword_gaps_search_popularity))
         				$top_4_keyword_gaps_data[$key] = "(Keywords driving traffic to competitors : " . $keyword_gaps[$key] .",". "Avg. Traffic to Competitors: ". $keyword_gaps_trafic_competitor[$key] .",". "Search Popularity : " . $keyword_gaps_search_popularity[$key] .")";
         		}
         	}

         	$top_4_keyword_gaps_str = implode(',', $top_4_keyword_gaps_data);


         	$easyto_rank_keyword = $easyto_rank_keyword;
         	$easyto_rank_relevence = $easyto_rank_relevence;
         	$easyto_rank_search_popularity = $easyto_rank_search_popularity;

         	$top4_easy_to_rank_keyword_data = array();
         	if(is_array($easyto_rank_keyword) && is_array($easyto_rank_relevence) && is_array($easyto_rank_search_popularity))
         	{
         		foreach ($easyto_rank_keyword as $key => $val) 
         		{
         			 if(array_key_exists($key, $easyto_rank_keyword) && array_key_exists($key, $easyto_rank_relevence) && array_key_exists($key, $easyto_rank_search_popularity))
         				$top4_easy_to_rank_keyword_data[$key] = "(Popular Keywords Within This Site's Competitive Power : " . $easyto_rank_keyword[$key] .",". "Relevance To This Site: ". $easyto_rank_relevence[$key] .",". "Search Popularity : " . $easyto_rank_search_popularity[$key] .")";
         		}
         	}

         	$top4_easy_to_rank_keyword_str = implode(',', $top4_easy_to_rank_keyword_data);
          
         	$buyer_keyword = $buyer_keyword;
         	$buyer_keyword_traffic_to_competitor = $buyer_keyword_traffic_to_competitor;
         	$buyer_keyword_organic_competitor = $buyer_keyword_organic_competitor;

         	$buyer_keyword_data = array();

         	if(is_array($buyer_keyword) && is_array($buyer_keyword_traffic_to_competitor) && is_array($buyer_keyword_organic_competitor))
         	{
         		foreach ($buyer_keyword as $key => $val) 
         		{
         			if(array_key_exists($key, $buyer_keyword) && array_key_exists($key, $buyer_keyword_traffic_to_competitor) && array_key_exists($key, $buyer_keyword_organic_competitor))
         				$buyer_keyword_data[$key] = "(Keywords That Show A High Purchase Intent : " . $buyer_keyword[$key] .",". "Avg. Traffic To Competitors: ". $buyer_keyword_traffic_to_competitor[$key] .",". "Organic Competition : " . $buyer_keyword_organic_competitor[$key] .")";
         		}
         	}

         	$buyer_keyword_str = implode(',', $buyer_keyword_data);

         	$optimization_opportunities = $optimization_opportunities;
         	$optimization_opportunities_search_popularity = $optimization_opportunities_search_popularity;
         	$optimization_opportunities_organic_share_of_voice = $optimization_opportunities_organic_share_of_voice;

         	$optimization_opportunities_data = array();
         	if(is_array($optimization_opportunities) && is_array($optimization_opportunities_search_popularity) && is_array($optimization_opportunities_organic_share_of_voice))
         	{
         		foreach ($optimization_opportunities as $key => $val) 
         		{
         			if(array_key_exists($key, $optimization_opportunities) && array_key_exists($key, $optimization_opportunities_search_popularity) && array_key_exists($key, $optimization_opportunities_organic_share_of_voice))
         				$optimization_opportunities_data[$key] = "(Very Popular Keywords Already Driving Some Traffic To This Site : " . $optimization_opportunities[$key] .",". "Search Popularity : ". $optimization_opportunities_search_popularity[$key] .",". "Organic Share Of Voice : " . $optimization_opportunities_organic_share_of_voice[$key] .")";
         		}
         	}
         	$optimization_opportunities_str = implode(',', $optimization_opportunities_data);

         	$refferal_sites = $refferal_sites;
         	$refferal_sites_links = $refferal_sites_links;
         	$refferal_sites_data = array();
         	if(is_array($refferal_sites) && is_array($refferal_sites_links) )
         	{
         		foreach ($refferal_sites as $key => $val) 
         		{
         			if(array_key_exists($key, $refferal_sites) && array_key_exists($key, $refferal_sites_links))
         				$refferal_sites_data[$key] = "(Sites By How Many Other Sites Drive Traffic To Them : " . $refferal_sites[$key] .",". "Referral Sites : ". $refferal_sites_links[$key] .")";
         		}
         	}

         	$refferal_sites_str = implode(',', $refferal_sites_data);


         	$top_keywords = $top_keywords;
         	$top_keywords_search_traficc = $top_keywords_search_traficc;
         	$top_keywords_share_of_voice = $top_keywords_share_of_voice;

         	$site_overlap_score = $site_overlap_score;
         	$similar_to_this_sites = $similar_to_this_sites;
         	$similar_to_this_sites_alexa_rank = $similar_to_this_sites_alexa_rank;

         	$site_overlap_score_data = array();
         	if(is_array($site_overlap_score) && is_array($similar_to_this_sites)  && is_array($similar_to_this_sites_alexa_rank))
         	{
         		foreach ($site_overlap_score as $key => $val) 
         		{
         			if(array_key_exists($key, $site_overlap_score) && array_key_exists($key, $similar_to_this_sites) && array_key_exists($key, $similar_to_this_sites_alexa_rank))
         				$site_overlap_score_data[$key] = "(Site’s Overlap Score : " . $site_overlap_score[$key] .",". "Similar Sites to This Site : ". $similar_to_this_sites[$key] ."," ."Alexa Rank :" .$similar_to_this_sites_alexa_rank[$key] .")";
         		}
         	}

         	$site_overlap_score_str = implode(',', $site_overlap_score_data);


         	$card_geography_country = $card_geography_country;
         	$card_geography_countryPercent = $card_geography_countryPercent;
         	$card_geography_country_data = array();
         	if(is_array($card_geography_country) && is_array($card_geography_countryPercent))
         	{
         		foreach ($card_geography_country as $key => $val) 
         		{
         			if(array_key_exists($key, $card_geography_country) && array_key_exists($key, $card_geography_countryPercent) )
         				$card_geography_country_data[$key] = "(Visitors by Country : " . $card_geography_country[$key] .",". "Visitors by Country Percentage : ". $card_geography_countryPercent[$key] .")";
         		}
         	}
         	$card_geography_country_str = implode(',', $card_geography_country_data);

         	$site_metrics = $site_metrics;
         	$site_metrics_domain = $site_metrics_domains_list;
         	$site_metrics_data = array();
          
         	if(is_array($site_metrics_domain) && is_array($site_metrics))
         	{
         		foreach ($site_metrics_domain as $key => $val) 
         		{
         			if(array_key_exists($key, $site_metrics_domain) && array_key_exists($key, $site_metrics) )
         				$site_metrics_data[$key] = "(Visited just before & right after : " . $site_metrics_domain[$key] .",". "Percentage : ". $site_metrics[$key] .")";
         		}
         	}
           
         	$site_metrics_data_str = implode(',', $site_metrics_data);
         	$status = $status;

                   
            $write_data=array();
            $write_data[]=$domain;
            $write_data[]=$alexa_rank;
            $write_data[]=$alexa_rank_spend_time;
            $write_data[]=$site_search_traffic;
            $write_data[]=$bounce_rate;
            $write_data[]=$total_sites_linking_in;
            $write_data[]=$similar_site_overlap_str;
            $write_data[]=$top_5_keywords_by_traffic_str;
            $write_data[]=$top_4_keyword_gaps_str;
            $write_data[]=$top4_easy_to_rank_keyword_str;
            $write_data[]=$buyer_keyword_str;
            $write_data[]=$optimization_opportunities_str;
            $write_data[]=$refferal_sites_str;
            $write_data[]=$site_overlap_score_str;
            $write_data[]=$card_geography_country_str;
            $write_data[]=$site_metrics_data_str;
           
            if($status=="success") fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
   
            $insert_data=array
            (
                 'user_id'                          => $this->user_id,
                 'searched_at'                      => $searched_at,
                 'domain_name'                      => $domain,
                 'alexa_rank'                       => $alexa_rank,     
                 'site_search_traffic'              => $site_search_traffic,    
                 'alexa_rank_spend_time'            => $alexa_rank_spend_time,     
                 'bounce_rate'                      => $bounce_rate,  
                 'total_sites_linking_in'           => $total_sites_linking_in,    
                 'total_keyword_opportunities_breakdown' => $total_keyword_opportunities_breakdown,    
                 'keyword_opportunitites_values' => json_encode($keyword_opportunitites_values),    
                 'similar_sites' => json_encode($similar_sites),    
                 'similar_site_overlap' => json_encode($similar_site_overlap),    
                 'keyword_top' => json_encode($keyword_top),    
                 'search_traffic' => json_encode($search_traffic),    
                 'share_voice' => json_encode($share_voice),    
                 'keyword_gaps' => json_encode($keyword_gaps),    
                 'keyword_gaps_trafic_competitor' => json_encode($keyword_gaps_trafic_competitor),    
                 'keyword_gaps_search_popularity' => json_encode($keyword_gaps_search_popularity),    
                 'easyto_rank_keyword' => json_encode($easyto_rank_keyword),    
                 'easyto_rank_relevence' => json_encode($easyto_rank_relevence),    
                 'easyto_rank_search_popularity' => json_encode($easyto_rank_search_popularity),    
                 'buyer_keyword' => json_encode($buyer_keyword),    
                 'buyer_keyword_traffic_to_competitor' => json_encode($buyer_keyword_traffic_to_competitor),    
                 'buyer_keyword_organic_competitor' => json_encode($buyer_keyword_organic_competitor),    
                 'optimization_opportunities' => json_encode($optimization_opportunities),    
                 'optimization_opportunities_search_popularity' => json_encode($optimization_opportunities_search_popularity),    
                 'optimization_opportunities_organic_share_of_voice' => json_encode($optimization_opportunities_organic_share_of_voice),    
                 'refferal_sites' => json_encode($refferal_sites),    
                 'refferal_sites_links' => json_encode($refferal_sites_links),    
                 'top_keywords' => json_encode($top_keywords),    
                 'top_keywords_search_traficc' => json_encode($top_keywords_search_traficc),    
                 'top_keywords_share_of_voice' => json_encode($top_keywords_share_of_voice),    
                 'site_overlap_score' => json_encode($site_overlap_score),    
                 'similar_to_this_sites' => json_encode($similar_to_this_sites),    
                 'similar_to_this_sites_alexa_rank' => json_encode($similar_to_this_sites_alexa_rank),    
                 'card_geography_country' => json_encode($card_geography_country),    
                 'card_geography_countryPercent' => json_encode($card_geography_countryPercent),    
                 'site_metrics' => json_encode($site_metrics),    
                 'site_metrics_domains' => json_encode($site_metrics_domains_list),    
                 'status'               => $status 


            );

            
            $details_url="";
            if($status=="success")
            {

                $this->basic->insert_data('alexa_info_full', $insert_data);    
                $insert_id=$this->db->insert_id();                
                $details_url="<a target='_BLANK' class='btn btn-primary' href='".site_url().'rank/alexa_details/'.$insert_id."'><i class='fas fa-eye'></i> " .$this->lang->line('See Full Report')."</a>";
            }

            $count++;

            $str.=
            "<tr>
            
            <td>".$domain."</td>
            <td><div class='pricing-item-icon'><i class='fas fa-check-circle green'></i> " .$status." </div></td>
            <td>".$details_url."</td>
            </tr>";
            
            $alexa_rank_full_complete++;
            $this->session->set_userdata("alexa_rank_full_complete_search",$alexa_rank_full_complete);        
        }

        //******************************//
        // insert data to useges log table
        $this->_insert_usage_log($module_id=4,$request=count($url_array));   
        //******************************//
        echo $str.="</tbody></table></div></div></div>";

    }

    public function alexa_rank_full_download()
    {
        $all=$this->input->post("ids");
        $table = 'alexa_info_full';
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
        $fp = fopen("download/rank/alexa_full_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

        $wite_data=array();  
                    
        $write_data[]="Domain";                 
        $write_data[]="Alexa Rank";                 
        $write_data[]="Alexa Rank Spend Time";                 
        $write_data[]="Site Search Traffic";                 
        $write_data[]="Bounce Rate";                 
        $write_data[]="Total Linking In Site";                                
        $write_data[]="Top 5 Similar Sites By Audience Overlap";                 
        $write_data[]="Top 5 Keywords By Traffic";                 
        $write_data[]="Top 4 Keyword Gaps";                 
        $write_data[]="Top 4 Easy-to-Rank Keywords";                 
        $write_data[]="Top 4 Buyer Keywords";                 
        $write_data[]="Top 4 Optimization Opportunities";                 
        $write_data[]="Top 5 Referral Sites";                 
        $write_data[]="Top 5 Audience Overlap";                 
        $write_data[]="Top 3 Country";                 
        $write_data[]="Site FLow";     
        $write_data[]="Searched At";     

        fputcsv($fp, $write_data);
        $write_data = array();

        foreach ($info as  $alexa_data_full) 
        {
            
            $domain =$alexa_data_full["domain_name"];
            $global_rank =$alexa_data_full["alexa_rank"];
            $alexa_rank_spend_time =$alexa_data_full["alexa_rank_spend_time"];
            $site_search_traffic =$alexa_data_full["site_search_traffic"];
            $bounce_rate =$alexa_data_full["bounce_rate"];
            $total_sites_linking_in =$alexa_data_full["total_sites_linking_in"];
           
            $similar_sites = json_decode($alexa_data_full['similar_sites'],true);
            $similar_site_overlap = json_decode($alexa_data_full['similar_site_overlap'],true);

            $similar_site_overlap_data = array();
                                
            if(is_array($similar_sites) && is_array($similar_site_overlap))
            {
                foreach($similar_sites as $key=>$val)
                {                  
                    if(array_key_exists($key, $similar_sites) && array_key_exists($key, $similar_site_overlap))
                    $similar_site_overlap_data[$key]="Similar Sites: ".$similar_sites[$key]." Overlap Score : ".$similar_site_overlap[$key];
                }
            }
            $similar_site_overlap_str=implode(',', $similar_site_overlap_data); 
            
            $keyword_top = json_decode($alexa_data_full['keyword_top'],true);
            $search_traffic = json_decode($alexa_data_full['search_traffic'],true);
            $share_voice = json_decode($alexa_data_full['share_voice'],true);

            $top_5_keywords_by_traffic_data = array();
            if(is_array($keyword_top) && is_array($search_traffic) && is_array($share_voice))
            {
                foreach ($keyword_top as $key => $val) 
                {
                    if(array_key_exists($key, $keyword_top) && array_key_exists($key, $search_traffic) && array_key_exists($key, $share_voice))
                        $top_5_keywords_by_traffic_data[$key] = "(Keywords : " . $keyword_top[$key] .",". "Search Traffic: ". $search_traffic[$key] .",". "Share Of Voice : " . $share_voice[$key] .")";
                }
            }
            $top_5_keywords_by_traffic_str = implode(',', $top_5_keywords_by_traffic_data);

            $keyword_gaps = json_decode($alexa_data_full['keyword_gaps'],true);
            $keyword_gaps_trafic_competitor = json_decode($alexa_data_full['keyword_gaps_trafic_competitor'],true);
            $keyword_gaps_search_popularity = json_decode($alexa_data_full['keyword_gaps_search_popularity'],true);
            
            $top_4_keyword_gaps_data = array();
            if(is_array($keyword_gaps) && is_array($keyword_gaps_trafic_competitor) && is_array($keyword_gaps_search_popularity))
            {
                foreach ($keyword_gaps as $key => $val) 
                {
                    if(array_key_exists($key, $keyword_gaps) && array_key_exists($key, $keyword_gaps_trafic_competitor) && array_key_exists($key, $keyword_gaps_search_popularity))
                        $top_4_keyword_gaps_data[$key] = "(Keywords driving traffic to competitors : " . $keyword_gaps[$key] .",". "Avg. Traffic to Competitors: ". $keyword_gaps_trafic_competitor[$key] .",". "Search Popularity : " . $keyword_gaps_search_popularity[$key] .")";
                }
            }

            $top_4_keyword_gaps_str = implode(',', $top_4_keyword_gaps_data);


           $easyto_rank_keyword = json_decode($alexa_data_full['easyto_rank_keyword'],true);
           $easyto_rank_relevence = json_decode($alexa_data_full['easyto_rank_relevence'],true);
           $easyto_rank_search_popularity = json_decode($alexa_data_full['easyto_rank_search_popularity'],true);

           $top4_easy_to_rank_keyword_data = array();
           if(is_array($easyto_rank_keyword) && is_array($easyto_rank_relevence) && is_array($easyto_rank_search_popularity))
           {
            foreach ($easyto_rank_keyword as $key => $val) 
            {
                 if(array_key_exists($key, $easyto_rank_keyword) && array_key_exists($key, $easyto_rank_relevence) && array_key_exists($key, $easyto_rank_search_popularity))
                    $top4_easy_to_rank_keyword_data[$key] = "(Popular Keywords Within This Site's Competitive Power : " . $easyto_rank_keyword[$key] .",". "Relevance To This Site: ". $easyto_rank_relevence[$key] .",". "Search Popularity : " . $easyto_rank_search_popularity[$key] .")";
            }
           }

           $top4_easy_to_rank_keyword_str = implode(',', $top4_easy_to_rank_keyword_data);

           $buyer_keyword = json_decode($alexa_data_full['buyer_keyword'],true);
           $buyer_keyword_traffic_to_competitor = json_decode($alexa_data_full['buyer_keyword_traffic_to_competitor'],true);
           $buyer_keyword_organic_competitor = json_decode($alexa_data_full['buyer_keyword_organic_competitor'],true);

           $buyer_keyword_data = array();

           if(is_array($buyer_keyword) && is_array($buyer_keyword_traffic_to_competitor) && is_array($buyer_keyword_organic_competitor))
           {
            foreach ($buyer_keyword as $key => $val) 
            {
                if(array_key_exists($key, $buyer_keyword) && array_key_exists($key, $buyer_keyword_traffic_to_competitor) && array_key_exists($key, $buyer_keyword_organic_competitor))
                    $buyer_keyword_data[$key] = "(Keywords That Show A High Purchase Intent : " . $buyer_keyword[$key] .",". "Avg. Traffic To Competitors: ". $buyer_keyword_traffic_to_competitor[$key] .",". "Organic Competition : " . $buyer_keyword_organic_competitor[$key] .")";
            }
           }

           $buyer_keyword_str = implode(',', $buyer_keyword_data);

           $optimization_opportunities = json_decode($alexa_data_full['optimization_opportunities'],true);
           $optimization_opportunities_search_popularity = json_decode($alexa_data_full['optimization_opportunities_search_popularity'],true);
           $optimization_opportunities_organic_share_of_voice = json_decode($alexa_data_full['optimization_opportunities_organic_share_of_voice'],true);

           $optimization_opportunities_data = array();
           if(is_array($optimization_opportunities) && is_array($optimization_opportunities_search_popularity) && is_array($optimization_opportunities_organic_share_of_voice))
           {
            foreach ($optimization_opportunities as $key => $val) 
            {
                if(array_key_exists($key, $optimization_opportunities) && array_key_exists($key, $optimization_opportunities_search_popularity) && array_key_exists($key, $optimization_opportunities_organic_share_of_voice))
                    $optimization_opportunities_data[$key] = "(Very Popular Keywords Already Driving Some Traffic To This Site : " . $optimization_opportunities[$key] .",". "Search Popularity : ". $optimization_opportunities_search_popularity[$key] .",". "Organic Share Of Voice : " . $optimization_opportunities_organic_share_of_voice[$key] .")";
            }
           }
           $optimization_opportunities_str = implode(',', $optimization_opportunities_data);

           $refferal_sites = json_decode($alexa_data_full['refferal_sites'],true);
           $refferal_sites_links = json_decode($alexa_data_full['refferal_sites_links'],true);
           $refferal_sites_data = array();
           if(is_array($refferal_sites) && is_array($refferal_sites_links) )
           {
            foreach ($refferal_sites as $key => $val) 
            {
                if(array_key_exists($key, $refferal_sites) && array_key_exists($key, $refferal_sites_links))
                    $refferal_sites_data[$key] = "(Sites By How Many Other Sites Drive Traffic To Them : " . $refferal_sites[$key] .",". "Referral Sites : ". $refferal_sites_links[$key] .")";
            }
           }

           $refferal_sites_str = implode(',', $refferal_sites_data);

           $site_overlap_score = json_decode($alexa_data_full['site_overlap_score'],true);
           $similar_to_this_sites = json_decode($alexa_data_full['similar_to_this_sites'],true);
           $similar_to_this_sites_alexa_rank = json_decode($alexa_data_full['similar_to_this_sites_alexa_rank'],true);

           $site_overlap_score_data = array();
           if(is_array($site_overlap_score) && is_array($similar_to_this_sites)  && is_array($similar_to_this_sites_alexa_rank))
           {
            foreach ($site_overlap_score as $key => $val) 
            {
                if(array_key_exists($key, $site_overlap_score) && array_key_exists($key, $similar_to_this_sites) && array_key_exists($key, $similar_to_this_sites_alexa_rank))
                    $site_overlap_score_data[$key] = "(Site’s Overlap Score : " . $site_overlap_score[$key] .",". "Similar Sites to This Site : ". $similar_to_this_sites[$key] ."," ."Alexa Rank :" .$similar_to_this_sites_alexa_rank[$key] .")";
            }
           }

           $site_overlap_score_str = implode(',', $site_overlap_score_data);

           $card_geography_country = json_decode($alexa_data_full['card_geography_country'],true);
           $card_geography_countryPercent = json_decode($alexa_data_full['card_geography_countryPercent'],true);
           $card_geography_country_data = array();
           if(is_array($card_geography_country) && is_array($card_geography_countryPercent))
           {
            foreach ($card_geography_country as $key => $val) 
            {
                if(array_key_exists($key, $card_geography_country) && array_key_exists($key, $card_geography_countryPercent) )
                    $card_geography_country_data[$key] = "(Visitors by Country : " . $card_geography_country[$key] .",". "Visitors by Country Percentage : ". $card_geography_countryPercent[$key] .")";
            }
           }
           $card_geography_country_str = implode(',', $card_geography_country_data);

           $site_metrics = json_decode($alexa_data_full['site_metrics'],true);
           $site_metrics_domain = json_decode($alexa_data_full['site_metrics_domains'],true);
           $site_metrics_data = array();
           
           if(is_array($site_metrics_domain) && is_array($site_metrics))
           {
            foreach ($site_metrics_domain as $key => $val) 
            {
                if(array_key_exists($key, $site_metrics_domain) && array_key_exists($key, $site_metrics) )
                    $site_metrics_data[$key] = "(Visited just before & right after : " . $site_metrics_domain[$key] .",". "Percentage : ". $site_metrics[$key] .")";
            }
           }
            
           $site_metrics_data_str = implode(',', $site_metrics_data);

           $searched_at=$alexa_data_full["searched_at"];


            
            $write_data[]=$domain;
            $write_data[]=$global_rank;
            $write_data[]=$alexa_rank_spend_time;
            $write_data[]=$site_search_traffic;
            $write_data[]=$bounce_rate;
            $write_data[]=$total_sites_linking_in;
            $write_data[]=$similar_site_overlap_str;
            $write_data[]=$top_5_keywords_by_traffic_str;
            $write_data[]=$top_4_keyword_gaps_str;
            $write_data[]=$top4_easy_to_rank_keyword_str;
            $write_data[]=$buyer_keyword_str;
            $write_data[]=$optimization_opportunities_str;
            $write_data[]=$refferal_sites_str;
            $write_data[]=$site_overlap_score_str;
            $write_data[]=$card_geography_country_str;
            $write_data[]=$site_metrics_data_str;
            $write_data[]=$searched_at;
            
            fputcsv($fp, $write_data);
        }

        fclose($fp);
        $file_name = "download/rank/alexa_full_{$this->user_id}_{$download_id}.csv";
       
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }


    public function alexa_rank_full_delete()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(4,$this->module_access))
        exit();

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
        $this->db->delete('alexa_info_full');
    }


    public function bulk_alexa_rank_full_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('alexa_rank_full_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('alexa_rank_full_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }


    public function alexa_details($id=0)
    {
        if($this->session->userdata('user_type') != 'Admin' && (!in_array(4,$this->module_access) && !in_array(2,$this->module_access)))
        redirect('home/login_page', 'location');

        if(!$this->basic->is_exist("alexa_info_full",array("user_id"=>$this->user_id,"id"=>$id)))
        redirect('home/access_forbidden', 'location');   

        $data["alexa_data"]=$this->basic->get_data("alexa_info_full",array("where"=>array("user_id"=>$this->user_id,"id"=>$id)));

        $data['body'] = 'alexa_rank/alexa_details';
        $data['page_title'] = $this->lang->line("Alexa Data");
        $this->_viewcontroller($data);
    }







    public function dmoz_rank()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(5,$this->module_access))
        redirect('home/login_page', 'location');

        $data['body'] = 'admin/ranking/dmoz';
        $data['page_title'] = $this->lang->line("dmoz check");
        $this->_viewcontroller($data);
    }
    

    public function dmoz_rank_data()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        redirect('home/access_forbidden', 'location');
        

        $page = isset($_POST['page']) ? intval($_POST['page']) : 15;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';

        $domain_name = trim($this->input->post("domain_name", true));

        $from_date = trim($this->input->post('from_date', true));        
        if($from_date) $from_date = date('Y-m-d', strtotime($from_date));

        $to_date = trim($this->input->post('to_date', true));
        if($to_date) $to_date = date('Y-m-d', strtotime($to_date));


        // setting a new properties for $is_searched to set session if search occured
        $is_searched = $this->input->post('is_searched', true);


        if ($is_searched) 
        {
            // if search occured, saving user input data to session. name of method is important before field
            $this->session->set_userdata('dmoz_rank_domain_name', $domain_name);
            $this->session->set_userdata('dmoz_rank_from_date', $from_date);
            $this->session->set_userdata('dmoz_rank_to_date', $to_date);
        }

        // saving session data to different search parameter variables
        $search_domain_name   = $this->session->userdata('dmoz_rank_domain_name');
        $search_from_date  = $this->session->userdata('dmoz_rank_from_date');
        $search_to_date = $this->session->userdata('dmoz_rank_to_date');

        // creating a blank where_simple array
        $where_simple=array();
        
        if ($search_domain_name)    $where_simple['domain_name like ']    = "%".$search_domain_name."%";
        if ($search_from_date) 
        {
            if ($search_from_date != '1970-01-01'){
                $search_from_date = $search_from_date." 00:00:00";
                $where_simple["checked_at >="]= $search_from_date;
            }
            
        }
        if ($search_to_date) 
        {
            if ($search_to_date != '1970-01-01'){
                $search_to_date = $search_to_date." 23:59:59";
                $where_simple["checked_at <="]=$search_to_date;
            }
            
        }

        $where_simple['user_id'] = $this->user_id;
        $where  = array('where'=>$where_simple);
        $order_by_str=$sort." ".$order;
        $offset = ($page-1)*$rows;
        $result = array();
        $table = "dmoz_info";
        $info = $this->basic->get_data($table, $where, $select='', $join='', $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='');
        $total_rows_array = $this->basic->count_row($table, $where, $count="id", $join='');
        $total_result = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }


    public function dmoz_rank_action()
    {
        $this->load->library('web_common_report');
        $urls=strip_tags($this->input->post('urls', true));
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);


        //************************************************//
        $status=$this->_check_usage($module_id=5,$request=count($url_array));
        if($status=="2") 
        {
            echo $this->lang->line("sorry, your bulk limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a>";
            exit();
        }
        else if($status=="3") 
        {
            echo $this->lang->line("sorry, your monthly limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a>";
            exit();
        }
        //************************************************//
        
      
        $this->session->set_userdata('dmoz_rank_bulk_total_search',count($url_array));
        $this->session->set_userdata('dmoz_rank_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/rank/dmoz_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        $total_count=0;
        
        /**Write header in csv file***/
        $write_data[]="Domain";            
        $write_data[]="Is Listed";                
        
        fputcsv($download_path, $write_data);
        
        $dmoz_rank_complete=0;

        $count=0;
        $str=
            "<table class='table table-bordered table-hover table-striped'>
            <tr>
            <td>SL</td>
            <td>Domain</td>
            <td>Is Listed</td>
            </tr>";
        foreach ($url_array as $domain) 
        {        
            /***Remove all www. http:// and https:// ****/            
            $domain=str_replace("www.","",$domain);
            $domain=str_replace("http://","",$domain);
            $domain=str_replace("https://","",$domain);

            $dmoz_data="";
            $dmoz_data=$this->web_common_report->dmoz_check($domain);  
            $checked_at= date("Y-m-d H:i:s");
                  
            $write_data=array();
            $write_data[]=$domain;
            $write_data[]=$dmoz_data;

        
            fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
   
            $insert_data=array
            (
                'user_id'           => $this->user_id,
                'domain_name'       => $domain,
                'listed_or_not'     => $dmoz_data,
                'checked_at'        => $checked_at
            );

            $count++;

            $str.=
            "<tr>
            <td>".$count."</td>
            <td>".$domain."</td>
            <td>".$dmoz_data."</td>
            </tr>";
            
            $this->basic->insert_data('dmoz_info', $insert_data);
            $dmoz_rank_complete++;
            $this->session->set_userdata("dmoz_rank_complete_search",$dmoz_rank_complete);        
        }

        //******************************//
        // insert data to useges log table
        $this->_insert_usage_log($module_id=5,$request=count($url_array));   
        //******************************// 
        echo $str.="</table>";

    }

  

    public function dmoz_rank_download()
    {
        $all=$this->input->post("all");
        $table = 'dmoz_info';
        $where=array();
        if($all==0)
        {
            $selected_grid_data = $this->input->post('info', true);
            $json_decode = json_decode($selected_grid_data, true);
            $id_array = array();
            foreach ($json_decode as  $value) 
            {
                $id_array[] = $value['id'];
            }
            $where['where_in'] = array('id' => $id_array);
        }

        $where['where'] = array('user_id'=>$this->user_id);

        $info = $this->basic->get_data($table, $where, $select ='', $join='', $limit='', $start=null, $order_by='id asc');
        $download_id=$this->session->userdata('download_id');
        $fp = fopen("download/rank/dmoz_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        $head=array("Doamin","Is Listed","Checked at");
                    
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) 
        {
         
            $write_info['domain_name']   = $value['domain_name'];
            $write_info['listed_or_not'] = $value['listed_or_not'];
            $write_info['checked_at']    = $value['checked_at'];
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/rank/dmoz_{$this->user_id}_{$download_id}.csv";
        echo $file_name;
    }


    

    public function dmoz_rank_delete()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(5,$this->module_access))
        exit();

        
        $all=$this->input->post("all");

        if($all==0)
        {
            $selected_grid_data = $this->input->post('info', true);
            $json_decode = json_decode($selected_grid_data, true);
            $id_array = array();
            foreach ($json_decode as  $value) 
            {
                $id_array[] = $value['id'];
            }     
            $this->db->where_in('id', $id_array);
        }
        $this->db->where('user_id', $this->user_id);
        $this->db->delete('dmoz_info');
    }


    
    public function bulk_dmoz_rank_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('dmoz_rank_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('dmoz_rank_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }





    public function moz_rank()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(4,$this->module_access))
        redirect('home/login_page', 'location');

        $data['body'] = 'moz_rank/moz_rank_list';
        $data['page_title'] = $this->lang->line("Moz Rank");
        $this->_viewcontroller($data);
    }
    
    public function moz_rank_analysis()
    {
        $data['body'] = 'moz_rank/moz_rank';
        $data['page_title'] = $this->lang->line("Moz Rank");
        $this->_viewcontroller($data);
    }

    public function moz_rank_data()
    {

        $this->ajax_check();
        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','mozrank_subdomain_normalized','mozrank_subdomain_raw','mozrank_url_normalized','mozrank_url_raw','http_status_code','domain_authority','page_authority','external_equity_links','links','url','checked_at');
        $search_columns = array('url','checked_at');

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
                $where_simple["Date_Format(checked_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(checked_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") 
            $where_simple['moz_info.url like'] = "%".$searching."%";

        $where_simple['moz_info.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "moz_info";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        for($i=0;$i<count($info);$i++)
        {  
            $info[$i]['checked_at'] = date("jS M y",strtotime($info[$i]["checked_at"]));        
        }
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function moz_rank_action()
    {
        $this->load->library('web_common_report');
        $urls=strip_tags($this->input->post('domain_name', true));
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);

        //************************************************//
        $status=$this->_check_usage($module_id=4,$request=count($url_array));
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
        
      
        $this->session->set_userdata('moz_rank_bulk_total_search',count($url_array));
        $this->session->set_userdata('moz_rank_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/rank/moz_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        $total_count=0;
        
        /**Write header in csv file***/
                                             
        
        $write_data[]="URL";            
        $write_data[]="Subdomain Normalized";                
        $write_data[]="Subdomain Raw";                
        $write_data[]="URL Normalized";                
        $write_data[]="URL Raw";                
        $write_data[]="HTTP Status Code";                
        $write_data[]="Domain Authority";                
        $write_data[]="Page Authority";                
        $write_data[]="External Equity Links";                
        $write_data[]="Links";           
        
        fputcsv($download_path, $write_data);

        $str = "<div class='card'>
                 <div class='card-header'>
                   <h4><i class='fas fa-trophy'></i> ".$this->lang->line("Moz Rank")."</h4>
                     <div class='card-header-action'>
                       <div class='badges'>
                         <a  class='btn btn-primary float-right' href='".base_url()."/download/rank/moz_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
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
            $moz_data=array();

            $use_admin_app = $this->config->item('use_admin_app');
            if($use_admin_app == '' || $use_admin_app == 'no')
              $config_data = $this->basic->get_data('config',['where'=>['user_id'=>$this->user_id]]);
            else
              $config_data = $this->basic->get_data('config',['where'=>['access'=>'all_users']],'','',1,0);

            $moz_access_id="";
            $moz_secret_key="";
            if(count($config_data)>0)
            {
                $moz_access_id=$config_data[0]["moz_access_id"];
                $moz_secret_key=$config_data[0]["moz_secret_key"];
            }

            $moz_data=$this->web_common_report->get_moz_info($domain,$moz_access_id, $moz_secret_key);  
            $checked_at= date("Y-m-d H:i:s");
                  
            $mozrank_subdomain_normalized=$moz_data["mozrank_subdomain_normalized"];    
            $mozrank_subdomain_raw=$moz_data["mozrank_subdomain_raw"];  
            $mozrank_url_normalized=$moz_data["mozrank_url_normalized"];    
            $mozrank_url_raw=$moz_data["mozrank_url_raw"];  
            $http_status_code=$moz_data["http_status_code"];    
            $domain_authority=$moz_data["domain_authority"];    
            $page_authority=$moz_data["page_authority"];    
            $external_equity_links=$moz_data["external_equity_links"];  
            $links=$moz_data["links"];             


            $write_data=array();
            $write_data[]=$domain;
            $write_data[]=$mozrank_subdomain_normalized;
            $write_data[]=$mozrank_subdomain_raw;
            $write_data[]=$mozrank_url_normalized;
            $write_data[]=$mozrank_url_raw;
            $write_data[]=$http_status_code;
            $write_data[]=$domain_authority;
            $write_data[]=$page_authority;
            $write_data[]=$external_equity_links;
            $write_data[]=$links;

            
            fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
            
            $insert_data=array
            (
                'user_id'                           => $this->user_id,
                'url'                               => $domain,
                'mozrank_subdomain_normalized'      => $mozrank_subdomain_normalized,
                'mozrank_subdomain_raw'             => $mozrank_subdomain_raw,
                'mozrank_url_normalized'            => $mozrank_url_normalized,
                'mozrank_url_raw'                   => $mozrank_url_raw,
                'http_status_code'                  => $http_status_code,
                'domain_authority'                  => $domain_authority,
                'page_authority'                    => $page_authority,
                'external_equity_links'             => $external_equity_links,
                'links'                             => $links,
                'checked_at'                        => $checked_at
            );  
            if ($tab == 1) {
                $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Domain')."<span class='badge badge-primary badge-pill'>{$domain}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Subdomain Normalized')."<span class='badge badge-primary badge-pill'>{$mozrank_subdomain_normalized}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Subdomain Raw')."<span class='badge badge-primary badge-pill'>{$mozrank_subdomain_raw}</span></li>";
                        
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('URL Normalized')."<span class='badge badge-primary badge-pill'>{$mozrank_url_normalized}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('URL Raw')."<span class='badge badge-primary badge-pill'>{$mozrank_url_raw}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('HTTP Status Code')."<span class='badge badge-primary badge-pill'>{$http_status_code}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Domain Authority')."<span class='badge badge-primary badge-pill'>{$domain_authority}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Page Authority')."<span class='badge badge-primary badge-pill'>{$page_authority}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('External Equity Links')."<span class='badge badge-primary badge-pill'>{$external_equity_links}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Links')."<span class='badge badge-primary badge-pill'>{$links}</span></li>";
                $str.= "</ul></div>";
            }
            else{
                $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Domain')."<span class='badge badge-primary badge-pill'>{$domain}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Subdomain Normalized')."<span class='badge badge-primary badge-pill'>{$mozrank_subdomain_normalized}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Subdomain Raw')."<span class='badge badge-primary badge-pill'>{$mozrank_subdomain_raw}</span></li>";
                        
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('URL Normalized')."<span class='badge badge-primary badge-pill'>{$mozrank_url_normalized}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('URL Raw')."<span class='badge badge-primary badge-pill'>{$mozrank_url_raw}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('HTTP Status Code')."<span class='badge badge-primary badge-pill'>{$http_status_code}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Domain Authority')."<span class='badge badge-primary badge-pill'>{$domain_authority}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Page Authority')."<span class='badge badge-primary badge-pill'>{$page_authority}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('External Equity Links')."<span class='badge badge-primary badge-pill'>{$external_equity_links}</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Links')."<span class='badge badge-primary badge-pill'>{$links}</span></li>";                
                $str.= "</ul></div>";
            }

            $this->basic->insert_data('moz_info', $insert_data);
            sleep(10);
        }
        $str.="</div>
                </div>";

        $this->_insert_usage_log($module_id=4,$request=count($url_array)); 
        echo $str.="</div></div></div>";

    }

  

    public function moz_rank_download()
    {
        $all=$this->input->post("ids");
        $table = 'moz_info';
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
        $fp = fopen("download/rank/moz_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));


        $head=array("URL","Subdomain Normalized","Subdomain Raw","URL Normalized","URL Raw","HTTP Status Code","Domain Authority","Page Authority","External Equity Links","Links","Checked at");
                    
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) 
        {
         
            $write_info['url'] = $value['url'];
            $write_info['mozrank_subdomain_normalized'] = $value['mozrank_subdomain_normalized'];
            $write_info['mozrank_subdomain_raw'] = $value['mozrank_subdomain_raw'];
            $write_info['mozrank_url_normalized'] = $value['mozrank_url_normalized'];
            $write_info['mozrank_url_raw'] = $value['mozrank_url_raw'];
            $write_info['http_status_code'] = $value['http_status_code'];
            $write_info['domain_authority'] = $value['domain_authority'];
            $write_info['page_authority'] = $value['page_authority'];
            $write_info['external_equity_links'] = $value['external_equity_links'];
            $write_info['links'] = $value['links'];
            $write_info['checked_at'] = $value['checked_at'];
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/rank/moz_{$this->user_id}_{$download_id}.csv";
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }


    

    public function moz_rank_delete()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(4,$this->module_access))
        exit();

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
        $this->db->delete('moz_info');
    }


    
    public function bulk_moz_rank_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('moz_rank_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('moz_rank_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }




    public function google_page_rank()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(4,$this->module_access))
        redirect('home/login_page', 'location');

        $data['body'] = 'admin/ranking/google_page';
        $data['page_title'] = $this->lang->line("google page rank");
        $this->_viewcontroller($data);
    }
    

    public function google_page_rank_data()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        redirect('home/access_forbidden', 'location');
        

        $page = isset($_POST['page']) ? intval($_POST['page']) : 15;
        $rows = isset($_POST['rows']) ? intval($_POST['rows']) : 5;
        $sort = isset($_POST['sort']) ? strval($_POST['sort']) : 'id';
        $order = isset($_POST['order']) ? strval($_POST['order']) : 'DESC';

        $domain_name = trim($this->input->post("domain_name", true));

        $from_date = trim($this->input->post('from_date', true));        
        if($from_date) $from_date = date('Y-m-d', strtotime($from_date));

        $to_date = trim($this->input->post('to_date', true));
        if($to_date) $to_date = date('Y-m-d', strtotime($to_date));


        // setting a new properties for $is_searched to set session if search occured
        $is_searched = $this->input->post('is_searched', true);


        if ($is_searched) 
        {
            // if search occured, saving user input data to session. name of method is important before field
            $this->session->set_userdata('google_page_rank_domain_name', $domain_name);
            $this->session->set_userdata('google_page_rank_from_date', $from_date);
            $this->session->set_userdata('google_page_rank_to_date', $to_date);
        }

        // saving session data to different search parameter variables
        $search_domain_name   = $this->session->userdata('google_page_rank_domain_name');
        $search_from_date  = $this->session->userdata('google_page_rank_from_date');
        $search_to_date = $this->session->userdata('google_page_rank_to_date');

        // creating a blank where_simple array
        $where_simple=array();
        
        if ($search_domain_name)    $where_simple['domain_name like ']    = "%".$search_domain_name."%";
        if ($search_from_date) 
        {
            if ($search_from_date != '1970-01-01') 
            $where_simple["Date_Format(checked_at,'%Y-%m-%d') >="]= $search_from_date;
            
        }
        if ($search_to_date) 
        {
            if ($search_to_date != '1970-01-01') 
            $where_simple["Date_Format(checked_at,'%Y-%m-%d') <="]=$search_to_date;
            
        }

        $where_simple['user_id'] = $this->user_id;
        $where  = array('where'=>$where_simple);
        $order_by_str=$sort." ".$order;
        $offset = ($page-1)*$rows;
        $result = array();
        $table = "search_engine_page_rank";
        $info = $this->basic->get_data($table, $where, $select='', $join='', $limit=$rows, $start=$offset, $order_by=$order_by_str, $group_by='');
        $total_rows_array = $this->basic->count_row($table, $where, $count="id", $join='');
        $total_result = $total_rows_array[0]['total_rows'];

        echo convert_to_grid_data($info, $total_result);
    }


    public function google_page_rank_action()
    {
        $this->load->library('web_common_report');
        $urls=strip_tags($this->input->post('urls', true));
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);


        //************************************************//
        $status=$this->_check_usage($module_id=4,$request=count($url_array));
        if($status=="2") 
        {
            echo $this->lang->line("sorry, your bulk limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a>";
            exit();
        }
        else if($status=="3") 
        {
            echo $this->lang->line("sorry, your monthly limit is exceeded for this module.")."<a href='".site_url('payment/usage_history')."'>".$this->lang->line("click here to see usage log")."</a>";
            exit();
        }
        //************************************************//
        
      
        $this->session->set_userdata('google_page_rank_bulk_total_search',count($url_array));
        $this->session->set_userdata('google_page_rank_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/rank/google_page_rank_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        $total_count=0;
        
        /**Write header in csv file***/
        $write_data[]="Domain";            
        $write_data[]="Google Page Rank";                
        
        fputcsv($download_path, $write_data);
        
        $google_page_rank_complete=0;

        $count=0;
        $str=
            "<table class='table table-bordered table-hover table-striped'>
            <tr>
            <td>SL</td>
            <td>Domain</td>
            <td>Google Page Rank</td>
            </tr>";
        foreach ($url_array as $domain) 
        {        
            /***Remove all www. http:// and https:// ****/            
            $domain=str_replace("www.","",$domain);
            $domain=str_replace("http://","",$domain);
            $domain=str_replace("https://","",$domain);

            $google_page_rank="";
            $google_page_rank=$this->web_common_report->get_google_page_rank($domain);  
            if($google_page_rank=="") $google_page_rank="0";
            $checked_at= date("Y-m-d H:i:s");
                  
            $write_data=array();
            $write_data[]=$domain;
            $write_data[]=$google_page_rank;

        
            fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
   
            $insert_data=array
            (
                'user_id'           => $this->user_id,
                'domain_name'       => $domain,
                'google_page_rank'  => $google_page_rank,
                'checked_at'        => $checked_at
            );

            $count++;

            $str.=
            "<tr>
            <td>".$count."</td>
            <td>".$domain."</td>
            <td>".$google_page_rank."</td>
            </tr>";
            
            $this->basic->insert_data('search_engine_page_rank', $insert_data);    
            $google_page_rank_complete++;
            $this->session->set_userdata("google_page_rank_complete_search",$google_page_rank_complete);        
        }

        //******************************//
        // insert data to useges log table
        $this->_insert_usage_log($module_id=4,$request=count($url_array));   
        //******************************//

        echo $str.="</table>";

    }

  

    public function google_page_rank_download()
    {
        $all=$this->input->post("all");
        $table = 'search_engine_page_rank';
        $where=array();
        if($all==0)
        {
            $selected_grid_data = $this->input->post('info', true);
            $json_decode = json_decode($selected_grid_data, true);
            $id_array = array();
            foreach ($json_decode as  $value) 
            {
                $id_array[] = $value['id'];
            }
            $where['where_in'] = array('id' => $id_array);
        }

        $where['where'] = array('user_id'=>$this->user_id);

        $info = $this->basic->get_data($table, $where, $select ='', $join='', $limit='', $start=null, $order_by='id asc');
        $download_id=$this->session->userdata('download_id');
        $fp = fopen("download/rank/google_page_rank_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        $head=array("Doamin","Google Page Rank","Checked at");
                    
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) 
        {
         
            $write_info['domain_name']      = $value['domain_name'];
            $write_info['google_page_rank'] = $value['google_page_rank'];
            $write_info['checked_at']       = $value['checked_at'];
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/rank/google_page_rank_{$this->user_id}_{$download_id}.csv";
        echo $file_name;
    }


    

    public function google_page_rank_delete()
    {
        if($this->session->userdata('user_type') != 'Admin' && !in_array(4,$this->module_access))
        exit();

        $all=$this->input->post("all");

        if($all==0)
        {
            $selected_grid_data = $this->input->post('info', true);
            $json_decode = json_decode($selected_grid_data, true);
            $id_array = array();
            foreach ($json_decode as  $value) 
            {
                $id_array[] = $value['id'];
            }     
            $this->db->where_in('id', $id_array);
        }
        $this->db->where('user_id', $this->user_id);
        $this->db->delete('search_engine_page_rank');
    }


    
    public function bulk_google_page_rank_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('google_page_rank_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('google_page_rank_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }



    

   

}
