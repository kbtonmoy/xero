<?php

require_once("Home.php"); // loading home controller

class Search_engine_index extends Home
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

        if($this->session->userdata('user_type') != 'Admin' && !in_array(4,$this->module_access))
        redirect('home/login_page', 'location'); 
    }

    public function index()
    {
        $this->search_engine_index();
    }

    public function search_engine_index()
    {
        $data['body'] = 'search_engine_analysis/search_engine_list';
        $data['page_title'] = $this->lang->line("Search Engine Index");
        $this->_viewcontroller($data);
    }

    public function search_engine()
    {
        $data['body'] = 'search_engine_analysis/search_engine';
        $data['page_title'] = $this->lang->line("Search Engine Index");
        $this->_viewcontroller($data);
    }
    

    public function search_engine_index_data()
    {

        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','domain_name','google_index','bing_index','yahoo_index','checked_at');
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
            $where_simple['search_engine_index.domain_name like'] = "%".$searching."%";

        $where_simple['search_engine_index.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "search_engine_index";

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


    public function search_engine_index_action()
    {
        $this->load->library('web_common_report');
        $urls=$this->input->post('domain_name', true);
        $is_google=$this->input->post('is_google', true);
        $is_bing=$this->input->post('is_bing', true);
        $is_yahoo=$this->input->post('is_yahoo', true);
        
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
        
      
        $this->session->set_userdata('search_engine_index_bulk_total_search',count($url_array));
        $this->session->set_userdata('search_engine_index_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/search_engine_index/search_engine_index_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        $total_count=0;
        
        /**Write header in csv file***/
        $write_data[]="Domain";
        if($is_google==1) $write_data[]="Google Index";
        if($is_bing==1) $write_data[]="Bing Index";
        if($is_yahoo==1) $write_data[]="Yahoo Index";
        $write_data[]="Checked at";            
        
        fputcsv($download_path, $write_data);

        $str = "<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fas fa-trophy'></i> ".$this->lang->line("Search Engine Index")."</h4>
                      <div class='card-header-action'>
                        <div class='badges'>
                          <a  class='btn btn-primary float-right' href='".base_url()."/download/search_engine_index/search_engine_index_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
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

            $google_index="";
            $bing_index="";
            $yahoo_index="";
            
            if($is_google==1)   
              $google_index = $this->web_common_report->GoogleIP($domain,$proxy="");
            if($is_bing==1) 
              $bing_index = $this->web_common_report->bing_index($domain,$proxy="");
            if($is_yahoo==1)    
              $yahoo_index = $this->web_common_report->yahoo_index($domain,$proxy="");
            
            $checked_at= date("Y-m-d H:i:s");
                  
            $write_data=array();
            $write_data[]=$domain;
            if($is_google==1)   $write_data[]=$google_index;
            if($is_bing==1)     $write_data[]=$bing_index;
            if($is_yahoo==1)    $write_data[]=$yahoo_index;
            $write_data[]=$checked_at;
            
            fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
            
            $insert_data=array
            (
                'user_id'           => $this->user_id,
                'domain_name'       => $domain,
                'checked_at'        => $checked_at
            );
            if($is_google==1)   
              $insert_data["google_index"]=$google_index;
            if($is_bing==1)     
              $insert_data["bing_index"]=$bing_index;
            if($is_yahoo==1)    
              $insert_data["yahoo_index"]=$yahoo_index;

            if ($tab == 1) {
                $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

                if($is_google==1) 
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Google Index')."<span class='badge badge-primary badge-pill'>{$google_index}</span></li>";
                if($is_bing==1) 
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Bing Index')."<span class='badge badge-primary badge-pill'>{$bing_index}</span></li>";
                if($is_yahoo==1) 
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Yahoo Index')."<span class='badge badge-primary badge-pill'>{$yahoo_index}</span></li>";

                $str.= "</ul></div>";
            }
            else{
                $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";
                        
                        if($is_google==1) 
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Google Index')."<span class='badge badge-primary badge-pill'>{$google_index}</span></li>";
                        if($is_bing==1) 
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Bing Index')."<span class='badge badge-primary badge-pill'>{$bing_index}</span></li>";
                        if($is_yahoo==1) 
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Yahoo Index')."<span class='badge badge-primary badge-pill'>{$yahoo_index}</span></li>";
                $str.= "</ul></div>";
            }

            $this->basic->insert_data('search_engine_index', $insert_data);
        }
        $str.="</div>
                </div>";

        $this->_insert_usage_log($module_id=4,$request=count($url_array)); 
        echo $str.="</div></div></div>";

    }

  

    public function search_engine_index_download()
    {
        $all=$this->input->post("ids");
        $table = 'search_engine_index';
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
        $fp = fopen("download/search_engine_index/search_engine_index_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        $head=array("Doamin","Google Index","Bing Index","Yahoo Index","Scanned at");
                    
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) 
        {
            $write_info['domain_name']  = $value['domain_name'];
            $write_info['google_index'] = $value['google_index'];
            $write_info['bing_index']   = $value['bing_index'];
            $write_info['yahoo_index']  = $value['yahoo_index'];
            $write_info['checked_at']   = $value['checked_at'];
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/search_engine_index/search_engine_index_{$this->user_id}_{$download_id}.csv";
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }


    

    public function search_engine_index_delete()
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
        $this->db->delete('search_engine_index');
    }


    
    public function bulk_search_engine_index_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('search_engine_index_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('search_engine_index_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
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

    

   

}
