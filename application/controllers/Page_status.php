<?php

require_once("Home.php"); // loading home controller



class Page_status extends Home
{

    public $user_id;    
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1) {
            redirect('home/login_page', 'location');
        }
        $this->load->library('upload');
        $this->load->library('web_common_report');
        $this->upload_path = realpath(APPPATH . '../upload');
        $this->user_id=$this->session->userdata('user_id');
        set_time_limit(0);

        $this->important_feature();

        $this->member_validity();

        if($this->session->userdata('user_type') != 'Admin' && !in_array(7,$this->module_access))
        redirect('home/login_page', 'location'); 
    }


    public function index()
    {
        $this->page_status_list();
    }

    public function page_status_list()
    {
        $data['body'] = 'page_status/page_status_list';
        $data['page_title'] = $this->lang->line('Page Status Check');
        $this->_viewcontroller($data);
    }

    public function analysis_new()
    {
        $data['body'] = 'page_status/page_status_new';
        $data['page_title'] = $this->lang->line('Page Status Check');
        $this->_viewcontroller($data);
    }

   public function page_status_list_data()
   {

        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','url','http_code','status','total_time','namelookup_time','connect_time','speed_download','check_date');
        $search_columns = array('url','check_date');

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
                $where_simple["Date_Format(check_date,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(check_date,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") 
            $where_simple['page_status.url like'] = "%".$searching."%";

        $where_simple['page_status.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "page_status";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        for($i=0;$i<count($info);$i++)
        {  
         $info[$i]['check_date'] = date("jS M y",strtotime($info[$i]["check_date"]));
        }
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);

   }

   public function page_status_action(){

        $this->load->library('web_common_report');
        $urls=strip_tags($this->input->post('domain_name', true));      
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);


        //************************************************//
        $status=$this->_check_usage($module_id=7,$request=count($url_array));
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
        
      
        $this->session->set_userdata('page_status_bulk_total_search',count($url_array));
        $this->session->set_userdata('page_status_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/page_status/page_status_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        $total_count=0;
        
        /**Write header in csv file***/
        $write_domain[]="URL";
        $write_domain[]="HTTP Code";
        $write_domain[]="Status";
        $write_domain[]="Total Time (sec)";
        $write_domain[]="Name Lookup Time (sec)";
        $write_domain[]="Connect Time (sec)";
        $write_domain[]="Download Speed Time";
        $write_domain[]="Check Status Date";           
        
        fputcsv($download_path, $write_domain);

        $http_codes = array( 100 => 'Continue', 101 => 'Switching Protocols', 102 => 'Processing', 200 => 'OK',
         201 => 'Created', 202 => 'Accepted', 203 => 'Non-Authoritative Information', 204 => 'No Content',
         205 => 'Reset Content', 206 => 'Partial Content', 207 => 'Multi-Status', 300 => 'Multiple Choices',
         301 => 'Moved Permanently', 302 => 'Found', 303 => 'See Other', 304 => 'Not Modified', 305 => 'Use Proxy',
         306 => 'Switch Proxy', 307 => 'Temporary Redirect', 400 => 'Bad Request', 401 => 'Unauthorized',
         402 => 'Payment Required', 403 => 'Forbidden', 404 => 'Not Found', 405 => 'Method Not Allowed',
         406 => 'Not Acceptable', 407 => 'Proxy Authentication Required', 408 => 'Request Timeout', 409 => 'Conflict',410 => 'Gone', 411 => 'Length Required', 412 => 'Precondition Failed', 413 => 'Request Entity Too Large',414 => 'Request-URI Too Long', 415 => 'Unsupported Media Type', 416 => 'Requested Range Not Satisfiable',417 => 'Expectation Failed', 418 => 'I\'m a teapot', 422 => 'Unprocessable Entity', 423 => 'Locked',
         424 => 'Failed Dependency', 425 => 'Unordered Collection', 426 => 'Upgrade Required', 449 => 'Retry With',
         450 => 'Blocked by Windows Parental Controls', 500 => 'Internal Server Error', 501 => 'Not Implemented',
         502 => 'Bad Gateway', 503 => 'Service Unavailable', 504 => 'Gateway Timeout',
         505 => 'HTTP Version Not Supported', 506 => 'Variant Also Negotiates', 507 => 'Insufficient Storage',
         509 => 'Bandwidth Limit Exceeded', 510 => 'Not Extended',
         0 => 'Not Registered' );
        

        $api="";
        $config_data=$this->basic->get_data("config",array("where"=>array("user_id"=>$this->user_id)));
        if(count($config_data)>0) $api=$config_data[0]["google_safety_api"];

        $str = "<div class='card'>
                    <div class='card-header'>
                      <h4><i class='fas fa-anchor'></i> ".$this->lang->line("Page Status Check")."</h4>
                      <div class='card-header-action'>
                        <div class='badges'>
                          <a  class='btn btn-primary float-right' href='".base_url()."/download/page_status/page_status_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
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
            $time=date("Y-m-d H:i:s");
            $domain_info= $this->web_common_report->page_status_check($domain);
            $write_domain=array();
            $write_domain[]=$domain;
            $write_domain[]=$domain_info['http_code'];
            $write_domain[]=$http_codes[$domain_info['http_code']];
            $write_domain[]=$domain_info['total_time'];
            $write_domain[]=$domain_info['namelookup_time'];
            $write_domain[]=$domain_info['connect_time'];
            $write_domain[]=$domain_info['speed_download'];
            $write_domain[]=$time;
            fputcsv($download_path, $write_domain);
            
            $insert_data=array(
                                'url'        => $domain,
                                'user_id'    => $this->user_id,
                                'http_code'    => $domain_info['http_code'],
                                'status'    => $http_codes[$domain_info['http_code']],
                                'total_time'    => $domain_info['total_time'],
                                'namelookup_time'    =>$domain_info['namelookup_time'],
                                'connect_time' =>$domain_info['connect_time'],
                                'speed_download'    =>$domain_info['speed_download'],
                                'check_date'        => $time
                                );

            if ($tab == 1) {
                $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("HTTP Code")." <span class='badge badge-primary badge-pill'>".$domain_info['http_code']."</span></li>";

                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Status")." <span class='badge badge-primary badge-pill'>".$http_codes[$domain_info['http_code']]."</span></li>";    

                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Total Time (sec)")." <span class='badge badge-primary badge-pill'>".$domain_info['total_time']."</span></li>";  

                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Name Lookup Time (sec)")." <span class='badge badge-primary badge-pill'>".$domain_info['namelookup_time']."</span></li>";

                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Connect Time (sec)")." <span class='badge badge-primary badge-pill'>".$domain_info['connect_time']."</span></li>";  

                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Download Speed Time")." <span class='badge badge-primary badge-pill'>".$domain_info['speed_download']."</span></li>";

                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Check Date Time")." <span class='badge badge-primary badge-pill'>".$time."</span></li>";     


                $str.= "</ul></div>";
            }
            else{
                $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("HTTP Code")." <span class='badge badge-primary badge-pill'>".$domain_info['http_code']."</span></li>";

                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Status")." <span class='badge badge-primary badge-pill'>".$http_codes[$domain_info['http_code']]."</span></li>";    

                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Total Time (sec)")." <span class='badge badge-primary badge-pill'>".$domain_info['total_time']."</span></li>";  

                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Name Lookup Time (sec)")." <span class='badge badge-primary badge-pill'>".$domain_info['namelookup_time']."</span></li>";

                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Connect Time (sec)")." <span class='badge badge-primary badge-pill'>".$domain_info['connect_time']."</span></li>";  

                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Download Speed Time")." <span class='badge badge-primary badge-pill'>".$domain_info['speed_download']."</span></li>";

                        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Check Date Time")." <span class='badge badge-primary badge-pill'>".$time."</span></li>";     
                
                $str.= "</ul></div>";
            }

            $this->basic->insert_data('page_status', $insert_data);
        }
        $str.="</div>
                </div>";

        $this->_insert_usage_log($module_id=7,$request=count($url_array)); 
        echo $str.="</div></div></div>";
   }

    public function page_status_download()
    {
        $all=$this->input->post("ids");
        $table = 'page_status';
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
        $fp = fopen("download/page_status/page_status_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        $head=array("Domain Name","HTTP Code","Status","Total Time","Name Lookup Time","Connect Time","Download Speed","Check At");
                    
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) 
        {
            $write_info['url'] = $value['url'];
            $write_info['http_code'] = $value['http_code'];
            $write_info['status'] = $value['status'];
            $write_info['total_time'] = $value['total_time'];
            $write_info['namelookup_time'] = $value['namelookup_time'];
            $write_info['connect_time'] = $value['connect_time'];
            $write_info['speed_download'] = $value['speed_download'];
            $write_info['check_date'] = $value['check_date'];
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/page_status/page_status_{$this->user_id}_{$download_id}.csv";

        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }


    

    public function page_status_delete()
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
        $this->db->delete('page_status');
    }


    
    public function bulk_scan_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('page_status_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('page_status_complete_search'); 
        
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
            $filename="page_check_status_".$this->user_id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;

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
