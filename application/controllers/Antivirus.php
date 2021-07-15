<?php

require_once("Home.php"); // loading home controller

class Antivirus extends Home
{

    public $user_id;    
    public $scan_places;    
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');       
 
        $this->user_id=$this->session->userdata('user_id');
        set_time_limit(0);

        $this->important_feature();
        $this->member_validity();
        if($this->session->userdata('user_type') != 'Admin' && !in_array(10,$this->module_access))
        redirect('home/login_page', 'location'); 


        $this->scan_places=array (
          0 => 'CLEAN MX',
          1 => 'DNS8',
          2 => 'OpenPhish',
          3 => 'VX Vault',
          4 => 'ZDB Zeus',
          5 => 'ZCloudsec',
          6 => 'PhishLabs',
          7 => 'Zerofox',
          8 => 'K7AntiVirus',
          9 => 'FraudSense',
          10 => 'Virusdie External Site Scan',
          11 => 'Quttera',
          12 => 'AegisLab WebGuard',
          13 => 'MalwareDomainList',
          14 => 'ZeusTracker',
          15 => 'zvelo',
          16 => 'Google Safebrowsing',
          17 => 'Kaspersky',
          18 => 'BitDefender',
          19 => 'Opera',
          20 => 'Certly',
          21 => 'G-Data',
          22 => 'C-SIRT',
          23 => 'CyberCrime',
          24 => 'SecureBrain',
          25 => 'Malware Domain Blocklist',
          26 => 'MalwarePatrol',
          27 => 'Webutation',
          28 => 'Trustwave',
          29 => 'Web Security Guard',
          30 => 'CyRadar',
          31 => 'desenmascara.me',
          32 => 'ADMINUSLabs',
          33 => 'Malwarebytes hpHosts',
          34 => 'Dr.Web',
          35 => 'AlienVault',
          36 => 'Emsisoft',
          37 => 'Rising',
          38 => 'Malc0de Database',
          39 => 'malwares.com URL checker',
          40 => 'Phishtank',
          41 => 'Malwared',
          42 => 'Avira',
          43 => 'NotMining',
          44 => 'StopBadware',
          45 => 'Antiy-AVL',
          46 => 'Forcepoint ThreatSeeker',
          47 => 'SCUMWARE.org',
          48 => 'Comodo Site Inspector',
          49 => 'Malekal',
          50 => 'ESET',
          51 => 'Sophos',
          52 => 'Yandex Safebrowsing',
          53 => 'Spam404',
          54 => 'Nucleon',
          55 => 'Sucuri SiteCheck',
          56 => 'Blueliv',
          57 => 'Netcraft',
          58 => 'AutoShun',
          59 => 'ThreatHive',
          60 => 'FraudScore',
          61 => 'Tencent',
          62 => 'URLQuery',
          63 => 'Fortinet',
          64 => 'ZeroCERT',
          65 => 'Baidu-International',
          66 => 'securolytics',
        );

    }

    public function index()
    {
        $this->scan();
    }

    public function scan()
    {
        $data['body'] = 'security_tools/antivirus/antivirus_scan';
        $data['page_title'] = $this->lang->line('Malware Scan');
        $this->_viewcontroller($data);
    }

    public function malware()
    {
        $data['body'] = 'security_tools/antivirus/antivirus_new';
        $data['page_title'] = $this->lang->line('Malware Scan');
        $this->_viewcontroller($data);
    }
    

    public function scan_data()
    {
        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','domain_name','google_status','norton_status','macafee_status','scanned_at');
        $search_columns = array('domain_name','scanned_at');

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
                $where_simple["Date_Format(scanned_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(scanned_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") $where_simple['antivirus_scan_info.domain_name like'] = "%".$searching."%";

        $where_simple['antivirus_scan_info.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "antivirus_scan_info";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');


        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }

    public function scan_action()
    {

        $this->load->library('web_common_report');
        $urls=strip_tags($this->input->post('domain_name', true));
        $is_google=$this->input->post('is_google', true);
        $is_norton=$this->input->post('is_norton', true);
        $is_mcafee=$this->input->post('is_mcafee', true);
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);

        //************************************************//
        $status=$this->_check_usage($module_id=10,$request=count($url_array));
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
        
      
        $this->session->set_userdata('antivirus_scan_bulk_total_search',count($url_array));
        $this->session->set_userdata('antivirus_scan_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/antivirus/antivirus_{$this->user_id}_{$download_id}.csv", "w");
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF)); // unicode compatible csv
        $total_count=0;
        
        /**Write header in csv file***/
        $write_data[]="Domain";
        if($is_google==1) $write_data[]="Google Status";
        if($is_norton==1) $write_data[]="Norton Status";
        if($is_mcafee==1) $write_data[]="Mcafee Status";
        $write_data[]="Scanned at";       
        
        fputcsv($download_path, $write_data);
        
        $antivirus_scan_complete=0;

        $api="";
        $use_admin_app = $this->config->item('use_admin_app');
        if($use_admin_app == '' || $use_admin_app == 'no')
          $config_data = $this->basic->get_data('config',['where'=>['user_id'=>$this->user_id]]);
        else
          $config_data = $this->basic->get_data('config',['where'=>['access'=>'all_users']],'','',1,0);

        if(count($config_data)>0) $api=$config_data[0]["google_safety_api"];

        $str = "<div class='card'>
                   <div class='card-header'>
                     <h4><i class='fab fa-typo3'></i> ".$this->lang->line("Malware Scan Results")."</h4>
                     <div class='card-header-action'>
                       <div class='badges'>
                         <a  class='btn btn-primary float-right' href='".base_url()."/download/antivirus/antivirus_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
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
           
           $google_status="";
           $norton_status="";
           $mcafee_status="";
           
           if($is_google==1) $google_status=$this->web_common_report->google_safety_check($api,$domain);
           if($is_norton==1) $norton_status=$this->web_common_report->norton_safety_check($domain,$proxy="");   
           if($is_mcafee==1) $mcafee_status=$this->web_common_report->macafee_safety_analysis($domain,$proxy="");   


           $scanned_at= date("Y-m-d H:i:s");
                 
           $write_data=array();
           $write_data[]=$domain;
           if($is_google==1) $write_data[]=$google_status;
           if($is_norton==1) $write_data[]=$norton_status;
           if($is_mcafee==1) $write_data[]=$mcafee_status;
           $write_data[]=$scanned_at;
           
           fputcsv($download_path, $write_data);
           
           /** Insert into database ***/
           
           $insert_data=array
           (
               'user_id'           => $this->user_id,
               'domain_name'       => $domain,
               'scanned_at'        => $scanned_at
           );
           if($is_google==1) $insert_data["google_status"]=$google_status;
           if($is_norton==1) $insert_data["norton_status"]=$norton_status;
           if($is_mcafee==1) $insert_data["macafee_status"]=$mcafee_status;

           if ($tab == 1) {
               $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                       <ul class='list-group'>";

               if($is_google==1) 
                   $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Google Status')."<span class='badge badge-primary badge-pill'>".$google_status."</span></li>";
               if($is_norton==1) 
                   $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Norton Status')."<span class='badge badge-primary badge-pill'>".$norton_status."</span></li>";
               if($is_mcafee==1) 
                   $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Mcafee Status')."<span class='badge badge-primary badge-pill'>".$mcafee_status."</span></li>";

               $str.= "</ul></div>";
           }
           else{
               $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                       <ul class='list-group'>";

                       if($is_google==1) 
                           $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Google Status')."<span class='badge badge-primary badge-pill'>".$google_status."</span></li>";
                       if($is_norton==1) 
                           $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Norton Status')."<span class='badge badge-primary badge-pill'>".$norton_status."</span></li>";
                       if($is_mcafee==1) 
                           $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Mcafee Status')."<span class='badge badge-primary badge-pill'>".$mcafee_status."</span></li>";
               
               $str.= "</ul></div>";
           }

           $this->basic->insert_data('antivirus_scan_info', $insert_data);
       }
       $str.="</div>
               </div>";

       $this->_insert_usage_log($module_id=10,$request=count($url_array)); 
       echo $str.="</div></div></div>";

    }  

    public function scan_download()
    {
        $all=$this->input->post("ids");
        $table = 'antivirus_scan_info';
        $where=array();
        if($all !=0)
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
        $fp = fopen("download/antivirus/antivirus_{$this->user_id}_{$download_id}.csv", "w");
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF)); // unicode compatible csv
        $head=array("Doamin","Google Status","Norton Status","Mcafee Status","Scanned at");
                    
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) 
        {
            $write_info['domain_name'] = $value['domain_name'];
            $write_info['google_status'] = $value['google_status'];
            $write_info['norton_status'] = $value['norton_status'];
            $write_info['mcafee_status'] = $value['macafee_status'];
            $write_info['scanned_at'] = $value['scanned_at'];
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/antivirus/antivirus_{$this->user_id}_{$download_id}.csv";
        
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }    

    public function scan_delete()
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
        $this->db->delete('antivirus_scan_info');
    }

    public function bulk_scan_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('antivirus_scan_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('antivirus_scan_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }


    public function virus_total()
    {
        $data['body'] = 'security_tools/virus_total/virus_total';
        $data['page_title'] = $this->lang->line('VirusTotal Scan');
        $this->_viewcontroller($data);
    }

    public function new_scan()
    {
        $data['body'] = 'security_tools/virus_total/new_scan';
        $data['page_title'] = $this->lang->line('VirusTotal Scan');
        $this->_viewcontroller($data);
    }
    

    public function virus_total_scan_data()
    {   
        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','domain_name','response_code','positives','total','scanned_at','actions');
        $search_columns = array('domain_name','scanned_at');

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
                $where_simple["Date_Format(scanned_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(scanned_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") $where_simple['virustotal.domain_name like'] = "%".$searching."%";

        $where_simple['virustotal.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "virustotal";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');


        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];

        for($i=0;$i<count($info);$i++)
        {   
          
         
            $report ="<a class='btn btn-circle btn-outline-primary view_report' data-id = ".$info[$i]['id']." title='".$this->lang->line('View Report')."'><i class='fas fa-eye'></i></a>";



            $info[$i]['actions'] = $report;
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function virus_total_scan_action()
    {  
        $this->load->library('web_common_report');
        $domain=strip_tags($this->input->post('domain_name', true));
       
        //************************************************//
        $status=$this->_check_usage($module_id=10,$request=1);
     
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
        
      
        $this->session->set_userdata('antivirustotal_scan_bulk_total_search',1);
        $this->session->set_userdata('antivirustotal_scan_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/antivirus/antivirus_{$this->user_id}_{$download_id}.csv", "w");
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF)); // unicode compatible csv
        $total_count=0;
        
        /**Write header in csv file***/
        $write_data[]="Domain";
        $write_data[]="Positives";
        $write_data[]="Total Scan";
        $write_data[]="Scanned at"; 
        foreach ($this->scan_places as $key => $value) 
        {
            $write_data[]=$value;
        }     
        
        fputcsv($download_path, $write_data);
        
        $antivirus_scan_complete=0;
        $api="";
        $use_admin_app = $this->config->item('use_admin_app');
        if($use_admin_app == '' || $use_admin_app == 'no')
          $config_data = $this->basic->get_data('config',['where'=>['user_id'=>$this->user_id]]);
        else
          $config_data = $this->basic->get_data('config',['where'=>['access'=>'all_users']],'','',1,0);
        
        if(count($config_data)>0) $api=$config_data[0]["virus_total_api"];
       
        if($api=="") 
        {   echo "<div class='card-body'>
                    <div class='alert alert-danger alert-has-icon'>
                     <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                     <div class='alert-body'>
                        <div class='alert-title'>".$this->lang->line('Danger')."</div>
                        <a target='_BLANK' href='".base_url("social_apps/connectivity_settings")."'>".$this->lang->line("VirusTotal API key not found. Please setup API key first.")."</a>
                     </div>
                    </div>
                </div>";

            exit();
        }

        $count=0;
         
               
        /***Remove all www. http:// and https:// ****/            
        $domain=str_replace("www.","",$domain);
        $domain=str_replace("http://","",$domain);
        $domain=str_replace("https://","",$domain);
         
        $scan_report=$this->web_common_report->virus_total_scan($api,$domain);
       
        $scanned_at= date("Y-m-d H:i:s");
              
        $write_data=array();
        $write_data[]=$domain;
        $positives=isset($scan_report['positives']) ? $scan_report['positives'] : 0;
        $write_data[]=$positives;
        $total_scan=isset($scan_report['total']) ? $scan_report['total'] : 0;
        $write_data[]=$total_scan;
        $write_data[]=$scanned_at;

        $str="<div class='card'>
                  <div class='card-header'>
                    <h4><i class='fas fa-shield-alt'></i> ".$this->lang->line("Virutotal Results")."</h4>
                    <div class='card-header-action'>
                      <div class='badges'>
                        <a  class='btn btn-primary float-right' href='".base_url()."/download/antivirus/antivirus_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
                      </div>                    
                    </div>
                  </div>";
        // $str.= "<h3 class='text-center'>Domain : ".$domain."</h3>";
        // $str.= "<h3 class='text-center'>Positives : ".$positives."/".$total_scan."</h3>";
        // $str.= "<h5 class='text-center'>Scanned at : ".$scanned_at."</h5><br></br>";

        $str.="<div class='card-body '>
                <h4 class='text-center'>".$this->lang->line("Total Scan: ").$total_scan. " <small class='text-muted'> ".$this->lang->line('Positives: ').$positives. " </small></h4>
                    <ul class='list-group list_scroll'>";   

        foreach ($this->scan_places as $key => $value) 
        {
            $temp="";
            $temp2="";
            $count++;
            if(isset($scan_report['scans'][$value]))
            {
                if(isset($scan_report['scans'][$value]['result'])) $temp.=$scan_report['scans'][$value]['result']; 
                // if(isset($scan_report['scans'][$value]['detected']) && $scan_report['scans'][$value]['detected']!="") $temp.=" | ".$scan_report['scans'][$value]['detected'];

                if(isset($scan_report['scans'][$value]['result'])) $temp2=$scan_report['scans'][$value]['result']; 
                if(trim($temp2)=="clean site") $temp2="<span class='badge badge-primary badge-pill'> ".ucwords($temp2)." </span>";
                else $temp2="<span class='badge badge-danger badge-pill'> ".ucwords($temp2)."</span> ";
            }

            $write_data[]=$temp;
            $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$value."  ".$temp2." </li>";
        }  
        $str.="</ul></div></div>";
    
        fputcsv($download_path, $write_data);
        
        /** Insert into database ***/

        $insert_data=array();            
        $insert_data['user_id']           = $this->user_id;
        $insert_data['domain_name']       = $domain;
        $insert_data['scanned_at']        = $scanned_at;
        $insert_data['response_code']     = isset($scan_report['response_code']) ? $scan_report['response_code'] : "";
        $insert_data['permalink']         = isset($scan_report['permalink']) ? $scan_report['permalink'] : "";
        $insert_data['verbose_msg']       = isset($scan_report['verbose_msg']) ? $scan_report['verbose_msg'] : "";
        $insert_data['positives']         = isset($scan_report['positives']) ? $scan_report['positives'] : 0;
        $insert_data['total']             = isset($scan_report['total']) ? $scan_report['total'] : 0;
        $insert_data['scans']             = isset($scan_report['scans']) ? json_encode($scan_report['scans']) : json_encode(array());
      
       
        $this->basic->insert_data('virustotal', $insert_data);    
     
        

        //******************************//
        // insert data to useges log table
        $this->_insert_usage_log($module_id=10,$request=1);   
        //******************************//

        echo $str;

    }  

    public function virus_total_report()
    {  
        if ($_SERVER['REQUEST_METHOD'] === 'GET')
        redirect('home/access_forbidden', 'location');

        $id=$this->input->post("id");

        $count=0;

        $scanreport=$this->basic->get_data("virustotal",array("where"=>array("id"=>$id,"user_id"=>$this->user_id)));
        $scanreport=isset($scanreport[0]) ? $scanreport[0] : array();
        if(empty($scanreport)) exit();

        $domain=$scanreport["domain_name"];
        $positives=$scanreport["positives"];
        $total_scan=$scanreport["total"];
        $scanned_at=$scanreport["scanned_at"];

        $scan_report=json_decode($scanreport['scans'],true);

        $str="<div class='card'>";
        $str.="<div class='card-body '>
                  <p class='text-center'>".$this->lang->line("Domain:") .$domain. "</p>
                      <ul class='list-group list_scroll'>";  

        // $str.= "<h3 class='text-center'>Domain : ".$domain."</h3>";
        // $str.= "<h3 class='text-center'>Positives : ".$positives."/".$total_scan."</h3>";
        // $str.= "<h5 class='text-center'>Scanned at : ".$scanned_at."</h5><br></br>";

        // $str.="<ol class='list-group' style='max-width:500px;display:block;margin:0 auto;'>";   

        foreach ($this->scan_places as $key => $value) 
        {
            $temp2="";
            $count++;
            if(isset($scan_report[$value]))
            {
                if(isset($scan_report[$value]['result'])) $temp2=$scan_report[$value]['result']; 
                if(trim($temp2)=="clean site") 
                    $temp2="<span class='badge badge-primary badge-pill'> ".ucwords($temp2)." </span>";
                else 
                    $temp2="<span class='badge badge-danger badge-pill'> ".ucwords($temp2)."</span> ";
            }
            $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$value."  ".$temp2." </li>";
        }  
        $str.="</ul></div></div>";
        $str.=" <style>
                    .list_scroll{
                        position: relative;
                        height: 400px;
                    }
                </style>
                <script>
                new PerfectScrollbar('.list_scroll',{
                              wheelSpeed: 2,
                              wheelPropagation: true,
                              minScrollbarLength: 20
                            });
               </script>";  

        echo $str;

    }  


    public function virus_total_scan_download()
    {   
        $all=$this->input->post("ids");
        $table = 'virustotal';
        $where=array();
        if($all !=0)
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
        $fp = fopen("download/antivirus/antivirus_{$this->user_id}_{$download_id}.csv", "w");
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF)); // unicode compatible csv

        $write_data=array();
        $write_data[]="Domain";
        $write_data[]="Positives";
        $write_data[]="Total Scan";
        $write_data[]="Scanned at"; 
        foreach ($this->scan_places as $key => $value) 
        {
            $write_data[]=$value;
        }           
        fputcsv($fp, $write_data);


        foreach ($info as  $value) 
        {
            
            $write_info = array();    
            $scan_report=json_decode($value['scans'],true);
          
            $write_info[]=$value["domain_name"];
            $positives=isset($value['positives']) ? $value['positives'] : 0;
            $write_info[]=$positives;
            $total_scan=isset($value['total']) ? $value['total'] : 0;
            $write_info[]=$total_scan;
            $write_info[]=isset($value['scanned_at']) ? $value['scanned_at'] : '';

            foreach ($this->scan_places as $key => $value2) 
            {
                $temp="";              
                if(isset($scan_report[$value2]['result'])) $temp=$scan_report[$value2]['result']; 
                // if(isset($scan_report['scans'][$value]['detected']) && $scan_report['scans'][$value]['detected']!="") $temp.=" | ".$scan_report['scans'][$value]['detected']; 
                $write_info[]=$temp;
            }              
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/antivirus/antivirus_{$this->user_id}_{$download_id}.csv";
       
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }    

    public function virus_total_scan_delete()
    {
      
        $all=$this->input->post("ids");

        if($all !=0)
        {
            $id_array = array();
            foreach ($all as  $value) 
            {
                $id_array[] = $value;
            }     
            $this->db->where_in('id', $id_array);
        }
        $this->db->where('user_id', $this->user_id);
        $this->db->delete('virustotal');
    }
        
    public function virus_total_bulk_scan_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('antivirustotal_scan_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('antivirustotal_scan_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }

    public function read_text_csv_file_antivirus()
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
            $filename="antivirus_".$this->user_id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;

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
