<?php

require_once("Home.php"); // loading home controller



class Who_is extends Home
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

        if($this->session->userdata('user_type') != 'Admin' && !in_array(5,$this->module_access))
        redirect('home/login_page', 'location'); 
    }


    public function index()
    {
        $this->who_is_list();
    }

    public function who_is_list(){
        $data['body'] = 'domain_analysis/who_is_list';
        $data['page_title'] = $this->lang->line('Whois Search');
        $this->_viewcontroller($data);
    }
    public function who_is()
    {
        $data['body'] = 'domain_analysis/who_is_new';
        $data['page_title'] = $this->lang->line('Whois Search');
        $this->_viewcontroller($data);
    }
    public function who_is_list_data()
    {

        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','domain_name','admin_name','admin_email','admin_country','admin_phone','admin_street','admin_city','admin_postal_code','tech_email','registrant_email','registrant_name','registrant_organization','registrant_street','registrant_city','registrant_state','registrant_postal_code','registrant_country','registrant_phone','registrar_url','is_registered','namve_servers','created_at','changed_at','expire_at','scraped_time');
        $search_columns = array('domain_name','scraped_time');

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
                $where_simple["Date_Format(scraped_time,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(scraped_time,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") 
            $where_simple['whois_search.domain_name like'] = "%".$searching."%";

        $where_simple['whois_search.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "whois_search";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        for($i=0;$i<count($info);$i++)
        {  
         $info[$i]['scraped_time'] = date("jS M y",strtotime($info[$i]["scraped_time"]));
        }
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function who_is_action()
    {
        $this->load->library('web_common_report');
        $urls=strip_tags($this->input->post('domain_name', true));        
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);
        $bulk_tracking_code=time();


        //************************************************//
        $status=$this->_check_usage($module_id=5,$request=count($url_array));
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
        
      
        $this->session->set_userdata('who_is_search_bulk_total_search',count($url_array));
        $this->session->set_userdata('who_is_search_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/who_is/who_is_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        $total_count=0;
        
        /**Write header in csv file***/
        $write_domain[]="Domain";
        $write_domain[]="Is Registered";
        $write_domain[]="Registrant Email"; 
        $write_domain[]="Tech Email";
        $write_domain[]="Admin Email";
    
        $write_domain[]="Name Servers";
        $write_domain[]="Created At";
        $write_domain[]="Changed At";
        $write_domain[]="Expires At";
        
        
        $write_domain[]="Registrat URL";
        
        $write_domain[]="Registrant Name";
        $write_domain[]="Registrant Organization";
        $write_domain[]="Registrant Street";
        $write_domain[]="Registrant City";
        $write_domain[]="Registrant State";
        $write_domain[]="Registrant Postal Code";
        $write_domain[]="Registrant Country";
        $write_domain[]="Registrant Phone";
        
        $write_domain[]="Admin Name";
        $write_domain[]="Admin Street";
        $write_domain[]="Admin City";
        $write_domain[]="Admin Postal Code";
        $write_domain[]="Admin Country";
        $write_domain[]="Admin Phone";            
        
        fputcsv($download_path, $write_domain);
        
        $str = "<div class='card'>
                    <div class='card-header'>
                      <h4><i class='fas fa-server'></i> ".$this->lang->line("Whois Search")."</h4>
                        <div class='card-header-action'>
                          <div class='badges'>
                            <a  class='btn btn-primary float-right' href='".base_url()."/download/who_is/who_is_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
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
            
            /* $who_is_report =$this->web_common_report->whois_email($domain);*/
             $domain_info=@$this->web_common_report->whois_email($domain);


             $write_domain=array();
             $write_domain[]=$domain;
             $write_domain[]=$domain_info['is_registered'];
             $write_domain[]=$domain_info['registrant_email'];
             $write_domain[]=$domain_info['tech_email'];
             $write_domain[]=$domain_info['admin_email'];
             $write_domain[]=$domain_info['name_servers'];
             $write_domain[]=$domain_info['created_at'];
             $write_domain[]=$domain_info['changed_at'];
             $write_domain[]=$domain_info['expire_at'];
             
             $write_domain[]=$domain_info['registrar_url'];
             
             $write_domain[]=$domain_info['registrant_name'];
             $write_domain[]=$domain_info['registrant_organization'];
             $write_domain[]=$domain_info['registrant_street'];
             $write_domain[]=$domain_info['registrant_city'];
             $write_domain[]=$domain_info['registrant_state'];
             $write_domain[]=$domain_info['registrant_postal_code'];
             $write_domain[]=$domain_info['registrant_country'];
             $write_domain[]=$domain_info['registrant_phone'];
             
             $write_domain[]=$domain_info['admin_name'];
             $write_domain[]=$domain_info['admin_street'];
             $write_domain[]=$domain_info['admin_city'];
             $write_domain[]=$domain_info['admin_postal_code'];
             $write_domain[]=$domain_info['admin_country'];
             $write_domain[]=$domain_info['admin_phone'];
             // $write_domain[]=$domain_info[''];
            
            
             fputcsv($download_path, $write_domain);
             
             /** Insert into database ***/
             
             $time=date("Y-m-d H:i:s");
             $insert_data=array(
                                 'user_id'           => $this->user_id,
                                 'domain_name'       => $domain,
                                 'tech_email'        => $domain_info['tech_email'],
                                 'admin_email'       => $domain_info['admin_email'],
                                 'is_registered'     =>$domain_info['is_registered'],
                                 'namve_servers'     =>$domain_info['name_servers'],
                                 'created_at'        =>$domain_info['created_at'],
                                 'changed_at'        =>$domain_info['changed_at'],
                                 'expire_at'         =>$domain_info['expire_at'],
                                 'scraped_time'      =>$time,
                                 'registrant_email'  =>$domain_info['registrant_email'],
                                 'registrant_name'   => $domain_info['registrant_name'],
                                 'registrant_organization'=>$domain_info['registrant_organization'],
                                 'registrant_street' =>$domain_info['registrant_street'],
                                 'registrant_city'   =>$domain_info['registrant_city'],
                                 'registrant_state'  =>$domain_info['registrant_state'],
                                 'registrant_postal_code'=> $domain_info['registrant_postal_code'],
                                 'registrant_country'=>$domain_info['registrant_country'],
                                 'registrant_phone'  =>$domain_info['registrant_phone'],
                                 'registrar_url'     =>$domain_info['registrar_url'],
                                 'admin_name'        =>$domain_info['admin_name'],
                                 'admin_street'      =>$domain_info['admin_street'],
                                 'admin_city'        =>$domain_info['admin_city'],
                                 'admin_postal_code'=> $domain_info['admin_postal_code'],
                                 'admin_country'     =>$domain_info['admin_country'],
                                 'admin_phone'       =>$domain_info['admin_phone']
                                 );
             

            if ($tab == 1) {
                $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

 
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Is Registered')."<span class='badge badge-primary badge-pill'>".$domain_info['is_registered']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant Email')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_email']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Tech Email')."<span class='badge badge-primary badge-pill'>".$domain_info['tech_email']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Admin Email')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_email']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Name Servers')."<span class='badge badge-primary badge-pill'>".$domain_info['name_servers']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Created At')."<span class='badge badge-primary badge-pill'>".$domain_info['created_at']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Changed At')."<span class='badge badge-primary badge-pill'>".$domain_info['changed_at']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Expires At')."<span class='badge badge-primary badge-pill'>".$domain_info['expire_at']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrat URL')."<span class='badge badge-primary badge-pill'>".$domain_info['registrar_url']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant Name')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_name']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant Organization')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_organization']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant Street')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_street']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant City')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_city']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant State')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_state']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant Postal Code')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_postal_code']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant Country')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_country']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant Phone')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_phone']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Admin Name')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_name']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Admin Street')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_street']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Admin City')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_city']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Admin Postal Code')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_postal_code']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Admin Country')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_country']."</span></li>";
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Admin Phone')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_phone']."</span></li>";



                $str.= "</ul></div>";
            }
            else{
                $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Is Registered')."<span class='badge badge-primary badge-pill'>".$domain_info['is_registered']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant Email')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_email']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Tech Email')."<span class='badge badge-primary badge-pill'>".$domain_info['tech_email']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Admin Email')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_email']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Name Servers')."<span class='badge badge-primary badge-pill'>".$domain_info['name_servers']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Created At')."<span class='badge badge-primary badge-pill'>".$domain_info['created_at']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Changed At')."<span class='badge badge-primary badge-pill'>".$domain_info['changed_at']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Expires At')."<span class='badge badge-primary badge-pill'>".$domain_info['expire_at']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrat URL')."<span class='badge badge-primary badge-pill'>".$domain_info['registrar_url']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant Name')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_name']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant Organization')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_organization']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant Street')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_street']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant City')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_city']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant State')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_state']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant Postal Code')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_postal_code']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant Country')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_country']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Registrant Phone')."<span class='badge badge-primary badge-pill'>".$domain_info['registrant_phone']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Admin Name')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_name']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Admin Street')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_street']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Admin City')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_city']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Admin Postal Code')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_postal_code']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Admin Country')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_country']."</span></li>";
                        $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Admin Phone')."<span class='badge badge-primary badge-pill'>".$domain_info['admin_phone']."</span></li>";
                
                $str.= "</ul></div>";
            }

            $this->basic->insert_data('whois_search', $insert_data);
        }
        $str.="</div>
                </div>";

        $this->_insert_usage_log($module_id=5,$request=count($url_array)); 
        echo $str.="</div></div></div>";

    }

  

    public function who_is_download()
    {
        $all=$this->input->post("ids");
        $table = 'whois_search';
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
        $fp = fopen("download/who_is/who_is_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

        $write_domain[]="Domain";
        $write_domain[]="Is Registered";
        $write_domain[]="Registrant Email"; 
        $write_domain[]="Tech Email";
        $write_domain[]="Admin Email";
    
        $write_domain[]="Name Servers";
        $write_domain[]="Created At";
        $write_domain[]="Changed At";
        // $write_domain[]="Sponsor";
        $write_domain[]="Expires At";
        
        
        $write_domain[]="Registrat URL";
        
        $write_domain[]="Registrant Name";
        $write_domain[]="Registrant Organization";
        $write_domain[]="Registrant Street";
        $write_domain[]="Registrant City";
        $write_domain[]="Registrant State";
        $write_domain[]="Registrant Postal Code";
        $write_domain[]="Registrant Country";
        $write_domain[]="Registrant Phone";
        
        $write_domain[]="Admin Name";
        $write_domain[]="Admin Street";
        $write_domain[]="Admin City";
        $write_domain[]="Admin State";
        $write_domain[]="Admin Postal Code";
        $write_domain[]="Admin Country";
        $write_domain[]="Admin Phone";
                    
        fputcsv($fp, $write_domain);

        $write_info = array();

        foreach ($info as  $domain_info) 
        {
			$write_info = array();
            $write_info[]=$domain_info['domain_name'];
            $write_info[]=$domain_info['is_registered'];
            $write_info[]=$domain_info['registrant_email'];
            
            $write_info[]=$domain_info['tech_email'];
            $write_info[]=$domain_info['admin_email'];
            $write_info[]=$domain_info['namve_servers'];
            $write_info[]=$domain_info['created_at'];
            $write_info[]=$domain_info['changed_at'];
            // $write_info[]=$domain_info['sponsor'];
            $write_info[]=$domain_info['expire_at'];
            
            $write_info[]=$domain_info['registrar_url'];
            
            $write_info[]=$domain_info['registrant_name'];
            $write_info[]=$domain_info['registrant_organization'];
            $write_info[]=$domain_info['registrant_street'];
            $write_info[]=$domain_info['registrant_city'];
            $write_info[]=$domain_info['registrant_state'];
            $write_info[]=$domain_info['registrant_postal_code'];
            $write_info[]=$domain_info['registrant_country'];
            $write_info[]=$domain_info['registrant_phone'];
            
            $write_info[]=$domain_info['admin_name'];
            $write_info[]=$domain_info['admin_street'];
            $write_info[]=$domain_info['admin_city'];
            // $write_info[]=$domain_info['admin_state'];
            $write_info[]=$domain_info['admin_postal_code'];
            $write_info[]=$domain_info['admin_country'];
            $write_info[]=$domain_info['admin_phone'];
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/who_is/who_is_{$this->user_id}_{$download_id}.csv";
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }


    

    public function who_is_delete()
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
        $this->db->delete('whois_search');
    }


    
    public function bulk_scan_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('who_is_search_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('who_is_search_complete_search'); 
        
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

  

}
