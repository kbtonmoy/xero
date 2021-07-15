<?php

require_once("Home.php"); // loading home controller

/**
* @category controller
* class Admin
*/

class Website_analysis extends Home
{

    public $user_id;    

    /**
    * load constructor
    * @access public
    * @return void
    */
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
            redirect('home/login_page', 'location');   
        
        $this->load->helper(array('form'));
        $this->load->library('upload');
        $this->load->library('Web_common_report');
        $this->upload_path = realpath(APPPATH . '../upload');
        $this->user_id=$this->session->userdata('user_id');
        set_time_limit(0);

        $this->important_feature();
        $this->periodic_check();

        $this->member_validity();

        // if($this->session->userdata('user_type') != 'Admin' && !in_array(1,$this->module_access))
        if($this->session->userdata('user_type') != 'Admin' && !in_array(2,$this->module_access))
            redirect('home/login_page', 'location'); 
         
        $query = 'SET SESSION group_concat_max_len = 9999999999999999999';
        $this->db->query($query);

        if(function_exists('ini_set')){
            ini_set('memory_limit', '-1');
        }
    }

    public function index()
    {
        $has_google_api = false;
        $use_admin_app = $this->config->item('use_admin_app');
        if($use_admin_app == '' || $use_admin_app == 'no')
          $config_data = $this->basic->get_data('config',['where'=>['user_id'=>$this->user_id]]);
        else
          $config_data = $this->basic->get_data('config',['where'=>['access'=>'all_users']],'','',1,0);

        if(count($config_data) > 0) {
            if($config_data[0]['google_safety_api'] != "") {
                $has_google_api = true;
            }
        }

        $data['has_google_api'] = $has_google_api;
        $data['body'] = "website_analysis/analysis_lists";
        $data['page_title'] = $this->lang->line("Website Analysis");
        $this->_viewcontroller($data);
    }

    public function website_analysis_lists_data()
    {
        $this->ajax_check();

        $domain_name = trim($this->input->post("domain_name",true));
        $post_date_range = $this->input->post("post_date_range",true);

        $display_columns = array("#",'CHECKBOX','id','domain_name','search_from','search_at','actions');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 2;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;

        $where_simple = array();

        if ($domain_name != "") {
            $where_simple['domain_name like'] = "%".$domain_name."%";
        }

        if($post_date_range !="")
        {
            $exp = explode('|', $post_date_range);
            $from_date = isset($exp[0])?$exp[0]:"";
            $to_date   = isset($exp[1])?$exp[1]:"";

            if($from_date!="Invalid date" && $to_date!="Invalid date")
            {
                $from_date = date('Y-m-d', strtotime($from_date));
                $to_date   = date('Y-m-d', strtotime($to_date));
                $where_simple["Date_Format(search_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(search_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($this->session->userdata('user_type') == 'Admin') {

            $where_in['user_id'] = array($this->user_id,'0');   
            $where = array('where' => $where_simple,'where_in'=>$where_in);
            
        } else {

            $where_simple['user_id'] = $this->user_id;
            $where = array('where'=>$where_simple);
            
        }

        $select = array("id","user_id","domain_name","search_at");

        $table = "website_analysis_info";
        $info = $this->basic->get_data($table,$where,$select,$join='',$limit,$start,$order_by,$group_by='');

        $total_rows_array = $this->basic->count_row($table,$where,$count="id",$join="",$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];

        for ($i=0; $i < count($info) ; $i++) {

            if($info[$i]['search_at'] != "0000-00-00 00:00:00") {

                $info[$i]['search_at'] = "<div style='min-width:140px !important;' class='text-center'>".date("M j, Y h:i A",strtotime($info[$i]['search_at']))."</div>";
            } else {

                $info[$i]['search_at'] = "<div class='text-muted text-center'><i class='fas fa-times'></i></div>";
            }

            if($info[$i]['user_id'] == '0') {
                $info[$i]['search_from'] = '<div class="badge badge-warning">'.$this->lang->line('Frontend').'</div>';
            } else {
                $info[$i]['search_from'] = '<div class="badge badge-primary">'.$this->lang->line('Application').'</div>';

            }

            $view_url = base_url('website_analysis/analysis_report/').$info[$i]['id'];
            $download_url = base_url('website_analysis/download_analysis_report/').$info[$i]['id'];

            $info[$i]['actions'] = "<div><a href='".$view_url."' title='".$this->lang->line("View Report")."' class='btn btn-outline-primary btn-circle'><i class='fa fa-eye'></i></a>&nbsp;&nbsp;";

            $info[$i]['actions'] .= "<a target='_BLANK' href='$download_url' title='".$this->lang->line("Download Report")."' class='btn btn-outline-success btn-circle'><i class='fa fa-cloud-download'></i></a>&nbsp;&nbsp;";

            $info[$i]['actions'] .= "<a href='#' title='".$this->lang->line("Delete Report")."' class='btn btn-outline-danger delete_domain btn-circle' table_id='".$info[$i]['id']."'><i class='fa fa-trash-alt'></i></a></div>
                <script>$('[data-toggle=\"tooltip\"]').tooltip();</script>";
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
        
    }


    public function ajax_domain_analysis_action()
    {
        $status=$this->_check_usage($module_id=2,$request=1);
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


        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }


        $completed_function_str = '';
        $domain_name = strip_tags(strtolower($this->input->post('domain_name', true)));
        $user_id = $this->user_id; 

        $common_result['user_id'] = $user_id;
        $common_result['domain_name'] = $domain_name;
        $common_result['search_at'] = date("Y-m-d G:i:s");

        $use_admin_app = $this->config->item('use_admin_app');
        if($use_admin_app == '' || $use_admin_app == 'no')
          $config_data = $this->basic->get_data('config',['where'=>['user_id'=>$this->user_id]]);
        else
          $config_data = $this->basic->get_data('config',['where'=>['access'=>'all_users']],'','',1,0);

        $moz_access_id="";
        $moz_secret_key="";
        $mobile_ready_api_key="";
        $api = "";
        if(count($config_data)>0)
        {
            $moz_access_id=$config_data[0]["moz_access_id"];
            $moz_secret_key=$config_data[0]["moz_secret_key"];
            $mobile_ready_api_key=$config_data[0]["mobile_ready_api_key"];
            $api=$config_data[0]["google_safety_api"];
        }


        // Getting screenshot from page speed insight
        $domain=addHttp($domain_name);
        $desktop_result=$this->web_common_report->google_page_speed_insight($api,$domain,"desktop");
            
        if (isset($desktop_result['error'])) {
            $common_result['screenshot_error'] = $desktop_result['error']['message'];
        }
        else{

           $common_result['screenshot'] = isset($desktop_result['lighthouseResult']['audits']['final-screenshot']['details']['data']) ? $desktop_result['lighthouseResult']['audits']['final-screenshot']['details']['data'] : "";
        }            
        
        $ready_data = $this->web_common_report->mobile_ready($domain_name,$mobile_ready_api_key);            
        
            
        if (isset($ready_data['error'])) {
            $common_result['google_api_error'] = $ready_data['error']['message'];
        }
        else {
            //$common_result["mobile_ready_data"] =  json_encode($ready_data);
            if (isset($ready_data['loadingExperience'])) {
                $common_result["loadingexperience_metrics"] =  isset($ready_data['loadingExperience']) ? json_encode($ready_data['loadingExperience']) : "";
            }
            if (isset($ready_data['originLoadingExperience'])) {
                $common_result["originloadingexperience_metrics"] =  isset($ready_data['originLoadingExperience']) ? json_encode($ready_data['originLoadingExperience']) : "";
            }
            if (isset($ready_data['lighthouseResult']['configSettings'])) {
               $common_result["lighthouseresult_configsettings"] =  isset($ready_data['lighthouseResult']['configSettings']) ? json_encode($ready_data['lighthouseResult']['configSettings']) : "";
            }

            if (isset($ready_data['lighthouseResult']['audits'])) {
                if(isset($ready_data['lighthouseResult']['audits']['resource-summary']))
                    unset($ready_data['lighthouseResult']['audits']['resource-summary']['details']);

                if (isset($ready_data['lighthouseResult']['audits']['efficient-animated-content']))
                    unset($ready_data['lighthouseResult']['audits']['efficient-animated-content']['details']);

                if (isset($ready_data['lighthouseResult']['audits']['metrics']))
                    unset($ready_data['lighthouseResult']['audits']['metrics']);   

                if (isset($ready_data['lighthouseResult']['audits']['network-server-latency']))
                    unset($ready_data['lighthouseResult']['audits']['network-server-latency']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['offscreen-images']))
                    unset($ready_data['lighthouseResult']['audits']['offscreen-images']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['uses-responsive-images']))
                    unset($ready_data['lighthouseResult']['audits']['uses-responsive-images']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['unused-css-rules']))
                    unset($ready_data['lighthouseResult']['audits']['unused-css-rules']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['total-byte-weight']))
                    unset($ready_data['lighthouseResult']['audits']['total-byte-weight']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['mainthread-work-breakdown']))
                    unset($ready_data['lighthouseResult']['audits']['mainthread-work-breakdown']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['uses-webp-images']))
                    unset($ready_data['lighthouseResult']['audits']['uses-webp-images']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['critical-request-chains']))
                    unset($ready_data['lighthouseResult']['audits']['critical-request-chains']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['dom-size']))
                    unset($ready_data['lighthouseResult']['audits']['dom-size']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['unminified-javascript']))
                    unset($ready_data['lighthouseResult']['audits']['unminified-javascript']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['redirects']))
                    unset($ready_data['lighthouseResult']['audits']['redirects']['details']);   

                if (isset($ready_data['lighthouseResult']['audits']['time-to-first-byte']))
                    unset($ready_data['lighthouseResult']['audits']['time-to-first-byte']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['render-blocking-resources']))
                    unset($ready_data['lighthouseResult']['audits']['render-blocking-resources']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['font-display']))
                    unset($ready_data['lighthouseResult']['audits']['font-display']['details']);

                if (isset($ready_data['lighthouseResult']['audits']['estimated-input-latency']))
                    unset($ready_data['lighthouseResult']['audits']['estimated-input-latency']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['uses-rel-preconnect']))
                    unset($ready_data['lighthouseResult']['audits']['uses-rel-preconnect']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['unminified-css']))
                    unset($ready_data['lighthouseResult']['audits']['unminified-css']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['bootup-time']))
                    unset($ready_data['lighthouseResult']['audits']['bootup-time']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['uses-rel-preload']))
                    unset($ready_data['lighthouseResult']['audits']['uses-rel-preload']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['user-timings']))
                    unset($ready_data['lighthouseResult']['audits']['user-timings']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['uses-text-compression']))
                    unset($ready_data['lighthouseResult']['audits']['uses-text-compression']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['uses-optimized-images']))
                    unset($ready_data['lighthouseResult']['audits']['uses-optimized-images']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['uses-long-cache-ttl']))
                    unset($ready_data['lighthouseResult']['audits']['uses-long-cache-ttl']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['third-party-summary']))
                    unset($ready_data['lighthouseResult']['audits']['third-party-summary']['details']);                
                if (isset($ready_data['lighthouseResult']['audits']['network-rtt']))
                    unset($ready_data['lighthouseResult']['audits']['network-rtt']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['diagnostics']))
                    unset($ready_data['lighthouseResult']['audits']['diagnostics']);                

                if (isset($ready_data['lighthouseResult']['audits']['network-requests']))
                    unset($ready_data['lighthouseResult']['audits']['network-requests']['details']);                

                if (isset($ready_data['lighthouseResult']['audits']['screenshot-thumbnails']))
                    unset($ready_data['lighthouseResult']['audits']['screenshot-thumbnails']);                

                if (isset($ready_data['lighthouseResult']['audits']['main-thread-tasks']))
                    unset($ready_data['lighthouseResult']['audits']['main-thread-tasks']);

                if (isset($ready_data['lighthouseResult']['categories']['performance']))
                    unset($ready_data['lighthouseResult']['categories']['performance']['auditRefs']);                
                
                $common_result['lighthouseresult_audits'] = isset($ready_data['lighthouseResult']['audits']) ? json_encode($ready_data['lighthouseResult']['audits']) : "";                   

            }

            if (isset($ready_data['lighthouseResult']['categories'])) {
                $common_result['lighthouseresult_categories'] = isset($ready_data['lighthouseResult']['categories']) ? json_encode($ready_data['lighthouseResult']['categories']) : "";
            }
                
        }
        //for dynamic progress bar data
        $add_complete = 0;
        $common_result['completed_step_count'] = $add_complete;
        $common_result['completed_step_string'] = '';
        $search_existing_info = $this->basic->get_data('website_analysis_info',['where'=>['user_id'=>$user_id,'domain_name'=>$domain_name]],['id']);



        if(empty($search_existing_info))
        {
            $this->basic->insert_data('website_analysis_info',$common_result);
            $web_common_info_id = $this->db->insert_id();    
        }
        else
        {
            $this->basic->update_data('website_analysis_info',['id'=>$search_existing_info[0]['id']],$common_result);
            $web_common_info_id = $search_existing_info[0]['id'];            
        }
        //***************************************//
        // insert data to useges log table
        $this->_insert_usage_log($module_id=2,$request=1);
        //***************************************//        
        $this->session->set_userdata('insert_table_id_website_analysis', $web_common_info_id);
        $this->session->set_userdata('website_analysis_bulk_total_search', 21);
        session_write_close(); 

        // get moz info


        
        $get_moz_info = $this->web_common_report->get_moz_info($domain_name,$moz_access_id, $moz_secret_key);
        $common_result['moz_subdomain_normalized'] = $get_moz_info['mozrank_subdomain_normalized'];
        $common_result['moz_subdomain_raw'] = $get_moz_info['mozrank_subdomain_raw'];
        $common_result['moz_url_normalized'] = $get_moz_info['mozrank_url_normalized'];
        $common_result['moz_url_raw'] = $get_moz_info['mozrank_url_raw'];
        $common_result['moz_http_status_code'] = $get_moz_info['http_status_code'];
        $common_result['moz_domain_authority'] = $get_moz_info['domain_authority'];
        $common_result['moz_page_authority'] = $get_moz_info['page_authority'];
        $common_result['moz_external_equity_links'] = $get_moz_info['external_equity_links'];
        $common_result['moz_links'] = $get_moz_info['links'];
        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". MOZ ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);
        // end of get moz info


        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Mobile Friendly ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);
        // end of get mobile ready

        
        $backlink_count=$common_result['moz_external_equity_links'];
        if($backlink_count=="")
            $backlink_count=0;
            
        $common_result['google_back_link_count'] = number_format($backlink_count);
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Backlink ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);
        
        $common_result['yahoo_back_link_count'] = 0;
        $common_result['bing_back_link_count'] = 0;
        
                

        /*************** alexa info data ****************/
        $alexa_data_full = $this->web_common_report->alexa_raw_data($domain_name);

        $alexa_rank = isset($alexa_data_full["alexa_rank"]) ? strip_tags($alexa_data_full["alexa_rank"]): "";
        $alexa_rank_spend_time =isset($alexa_data_full["alexa_rank_spend_time"]) ? $alexa_data_full["alexa_rank_spend_time"] : "";
        $site_search_traffic =isset($alexa_data_full["site_search_traffic"]) ? $alexa_data_full["site_search_traffic"] : "";
        $bounce_rate = isset($alexa_data_full["bounce_rate"]) ? $alexa_data_full["bounce_rate"] : "";
        $total_sites_linking_in = isset($alexa_data_full["total_sites_linking_in"]) ? $alexa_data_full["total_sites_linking_in"] : "";
        $total_keyword_opportunities_breakdown = isset($alexa_data_full["total_keyword_opportunities_breakdown"]) ? $alexa_data_full["total_keyword_opportunities_breakdown"] : "";
        $keyword_opportunitites_values = isset($alexa_data_full["keyword_opportunitites_values"]) ? $alexa_data_full["keyword_opportunitites_values"] : "";
        $similar_site = isset($alexa_data_full["similar_site"]) ? $alexa_data_full["similar_site"] : array();
        $similar_site_overlap = isset($alexa_data_full["similar_site_overlap"]) ? $alexa_data_full["similar_site_overlap"] : array();
        $keyword_top = isset($alexa_data_full["keyword_top"]) ? $alexa_data_full["keyword_top"]: array();
        $search_traffic = isset($alexa_data_full["search_traffic"]) ? $alexa_data_full["search_traffic"] :array();
        $share_voice = isset($alexa_data_full["share_voice"]) ? $alexa_data_full["share_voice"] : array();
        $keyword_gaps = isset($alexa_data_full["keyword_gaps"]) ? $alexa_data_full["keyword_gaps"] : array();
        $keyword_gaps_trafic_competitor = isset($alexa_data_full["keyword_gaps_trafic_competitor"]) ? $alexa_data_full["keyword_gaps_trafic_competitor"] : array();
        $keyword_gaps_search_popularity = isset($alexa_data_full["keyword_gaps_search_popularity"]) ? $alexa_data_full["keyword_gaps_search_popularity"] : array();
        $easyto_rank_keyword = isset($alexa_data_full["easyto_rank_keyword"]) ? $alexa_data_full["easyto_rank_keyword"]: array();
        $easyto_rank_relevence = isset($alexa_data_full["easyto_rank_relevence"]) ? $alexa_data_full["easyto_rank_relevence"] : array();
        $easyto_rank_search_popularity = isset($alexa_data_full["easyto_rank_search_popularity"]) ? $alexa_data_full["easyto_rank_search_popularity"] : array();
        $buyer_keyword = isset($alexa_data_full["buyer_keyword"]) ? $alexa_data_full["buyer_keyword"]: array();
        $buyer_keyword_traffic_to_competitor = isset($alexa_data_full["buyer_keyword_traffic_to_competitor"]) ? $alexa_data_full["buyer_keyword_traffic_to_competitor"] : array();
        $buyer_keyword_organic_competitor = isset($alexa_data_full["buyer_keyword_organic_competitor"]) ? $alexa_data_full["buyer_keyword_organic_competitor"]: array();
        $optimization_opportunities = isset($alexa_data_full["optimization_opportunities"]) ? $alexa_data_full["optimization_opportunities"] : array();
        $optimization_opportunities_search_popularity = isset($alexa_data_full["optimization_opportunities_search_popularity"]) ? $alexa_data_full["optimization_opportunities_search_popularity"]: array();
        $optimization_opportunities_organic_share_of_voice = isset($alexa_data_full["optimization_opportunities_organic_share_of_voice"]) ? $alexa_data_full["optimization_opportunities_organic_share_of_voice"]: array();
        $refferal_sites = isset($alexa_data_full["refferal_sites"]) ? $alexa_data_full["refferal_sites"]:array();
        $refferal_sites_links = isset($alexa_data_full["refferal_sites_links"]) ? $alexa_data_full["refferal_sites_links"]:array();
        $top_keywords = isset($alexa_data_full["top_keywords"]) ? $alexa_data_full["top_keywords"]:array();
        $top_keywords_search_traficc = isset($alexa_data_full["top_keywords_search_traficc"]) ? $alexa_data_full["top_keywords_search_traficc"] : array();
        $top_keywords_share_of_voice = isset($alexa_data_full["top_keywords_share_of_voice"]) ? $alexa_data_full["top_keywords_share_of_voice"] :array();
        $site_overlap_score = isset($alexa_data_full["site_overlap_score"]) ? $alexa_data_full["site_overlap_score"]:array();
        $similar_to_this_sites = isset($alexa_data_full["similar_to_this_sites"]) ? $alexa_data_full["similar_to_this_sites"]:array();
        $similar_to_this_sites_alexa_rank = isset($alexa_data_full["similar_to_this_sites_alexa_rank"]) ? $alexa_data_full["similar_to_this_sites_alexa_rank"] :array();
        $card_geography_country = isset($alexa_data_full["card_geography_country"]) ? $alexa_data_full["card_geography_country"]:array();
        $card_geography_countryPercent = isset($alexa_data_full["card_geography_countryPercent"]) ? $alexa_data_full["card_geography_countryPercent"]:array();
        $site_metrics = isset($alexa_data_full["site_metrics"]) ? $alexa_data_full["site_metrics"] : array();
        $site_metrics_domains = isset($alexa_data_full["site_metrics_domains"]) ? $alexa_data_full["site_metrics_domains"] :array();

        $status = $alexa_data_full["status"];

        $common_result['alexa_rank'] = $alexa_rank;
        $common_result['site_search_traffic'] = $site_search_traffic;
        $common_result['alexa_rank_spend_time'] = $alexa_rank_spend_time;
        $common_result['bounce_rate'] = $bounce_rate;
        $common_result['total_sites_linking_in'] = $total_sites_linking_in;
        $common_result['total_keyword_opportunities_breakdown'] = $total_keyword_opportunities_breakdown;
        $common_result['keyword_opportunitites_values'] = json_encode($keyword_opportunitites_values);
        $common_result['similar_sites'] = json_encode($similar_site);
        $common_result['similar_site_overlap'] = json_encode($similar_site_overlap);
        $common_result['keyword_top'] = json_encode($keyword_top);
        $common_result['search_traffic'] = json_encode($search_traffic);
        $common_result['share_voice'] = json_encode($share_voice);
        $common_result['keyword_gaps'] = json_encode($keyword_gaps);
        $common_result['keyword_gaps_trafic_competitor'] = json_encode($keyword_gaps_trafic_competitor);
        $common_result['keyword_gaps_search_popularity'] = json_encode($keyword_gaps_search_popularity);
        $common_result['easyto_rank_keyword'] = json_encode($easyto_rank_keyword);
        $common_result['easyto_rank_relevence'] = json_encode($easyto_rank_relevence);
        $common_result['easyto_rank_search_popularity'] = json_encode($easyto_rank_search_popularity);
        $common_result['buyer_keyword'] = json_encode($buyer_keyword);
        $common_result['buyer_keyword_traffic_to_competitor'] = json_encode($buyer_keyword_traffic_to_competitor);
        $common_result['buyer_keyword_organic_competitor'] = json_encode($buyer_keyword_organic_competitor);
        $common_result['optimization_opportunities'] = json_encode($optimization_opportunities);
        $common_result['optimization_opportunities_search_popularity'] = json_encode($optimization_opportunities_search_popularity);
        $common_result['optimization_opportunities_organic_share_of_voice'] = json_encode($optimization_opportunities_organic_share_of_voice);
        $common_result['refferal_sites'] = json_encode($refferal_sites);
        $common_result['refferal_sites_links'] = json_encode($refferal_sites_links);
        $common_result['top_keywords'] = json_encode($top_keywords);
        $common_result['top_keywords_search_traficc'] = json_encode($top_keywords_search_traficc);
        $common_result['top_keywords_share_of_voice'] = json_encode($top_keywords_share_of_voice);
        $common_result['site_overlap_score'] = json_encode($site_overlap_score);
        $common_result['similar_to_this_sites'] = json_encode($similar_to_this_sites);
        $common_result['similar_to_this_sites_alexa_rank'] = json_encode($similar_to_this_sites_alexa_rank);
        $common_result['card_geography_country'] = json_encode($card_geography_country);
        $common_result['card_geography_countryPercent'] = json_encode($card_geography_countryPercent);
        $common_result['site_metrics'] = json_encode($site_metrics);
        $common_result['site_metrics_domains'] = json_encode($site_metrics_domains);
        $common_result['status'] = $status;

        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Alexa ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);


        $fb_like_comment_share = $this->web_common_report->fb_like_comment_share(addHttp($domain_name));
        // $common_result['domain_id'] = $domain_info[0]['id'];

        if(isset($fb_like_comment_share['total_share']))
            $common_result['fb_total_share'] = $fb_like_comment_share['total_share'];
        else 
            $common_result['fb_total_share'] = 0;

        if(isset($fb_like_comment_share['total_reaction']))
            $common_result['fb_total_like'] = $fb_like_comment_share['total_reaction'];
        else
            $common_result['fb_total_like'] = 0;

        if(isset($fb_like_comment_share['total_comment']))
            $common_result['fb_total_comment'] = $fb_like_comment_share['total_comment'];
        else
            $common_result['fb_total_comment'] = 0;


        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Facebook ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);


        $pinterest_info = $this->web_common_report->pinterest_pin($domain_name);
        $common_result['pinterest_pin'] = $pinterest_info;
        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Pinterest ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);

        $stumbleupon_info = $this->web_common_report->stumbleupon_info($domain_name);
        $common_result['stumbleupon_total_view'] = $stumbleupon_info['total_view'];
        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Stumbleupon ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);


        $buffer_info = $this->web_common_report->buffer_share($domain_name);
        $common_result['buffer_share_count'] = $buffer_info;
        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Buffer ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);


        $GoogleIP = $this->web_common_report->GoogleIP($domain_name);
        $common_result['google_index_count'] = $GoogleIP;
        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Google Index ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);
        

        $reddit_count = $this->web_common_report->reddit_count($domain_name);
        $common_result['reddit_score'] = $reddit_count['score'];
        $common_result['reddit_ups'] = $reddit_count['ups'];
        $common_result['reddit_downs'] = $reddit_count['downs'];
        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Reddit ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);



        $xing_share_count = $this->web_common_report->xing_share_count($domain_name);
        $common_result['xing_share_count'] = empty($xing_share_count) ? 0 : $xing_share_count;
        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Xing ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);
        


        $bing_index = $this->web_common_report->bing_index($domain_name);
        $common_result['bing_index_count'] = $bing_index;
        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Bing ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);



        $yahoo_index = $this->web_common_report->yahoo_index($domain_name);
        $common_result['yahoo_index_count'] = $yahoo_index;
        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Yahoo ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);



        $meta_tag_info = $this->web_common_report->content_analysis($domain_name);
        $common_result['h1'] = json_encode($meta_tag_info['h1']);
        $common_result['h2'] = json_encode($meta_tag_info['h2']);
        $common_result['h3'] = json_encode($meta_tag_info['h3']);
        $common_result['h4'] = json_encode($meta_tag_info['h4']);
        $common_result['h5'] = json_encode($meta_tag_info['h5']);
        $common_result['h6'] = json_encode($meta_tag_info['h6']);
        $common_result['blocked_by_robot_txt'] = $meta_tag_info['blocked_by_robot_txt'];
        $common_result['meta_tag_information'] = json_encode($meta_tag_info['meta_tag_information']);
        $common_result['blocked_by_meta_robot'] = $meta_tag_info['blocked_by_meta_robot'];
        $common_result['nofollowed_by_meta_robot'] = $meta_tag_info['nofollowed_by_meta_robot'];
        $common_result['one_phrase'] = json_encode($meta_tag_info['one_phrase']);
        $common_result['two_phrase'] = json_encode($meta_tag_info['two_phrase']);
        $common_result['three_phrase'] = json_encode($meta_tag_info['three_phrase']);
        $common_result['four_phrase'] = json_encode($meta_tag_info['four_phrase']);
        $common_result['total_words'] = $meta_tag_info['total_words'];
        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Metatag ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);


        $whois_info = @$this->web_common_report->whois_email($domain_name);

        $common_result['whois_is_registered'] = $whois_info['is_registered'];
        $common_result['whois_tech_email'] = $whois_info['tech_email'];
        $common_result['whois_admin_email'] = $whois_info['admin_email'];
        $common_result['whois_name_servers'] = $whois_info['name_servers'];
        $common_result['whois_created_at'] = $whois_info['created_at'];
        $common_result['whois_changed_at'] = $whois_info['changed_at'];
        $common_result['whois_expire_at'] = $whois_info['expire_at'];
        $common_result['whois_registrar_url'] = $whois_info['registrar_url'];
        $common_result['whois_registrant_name'] = $whois_info['registrant_name'];
        $common_result['whois_registrant_organization'] = $whois_info['registrant_organization'];
        $common_result['whois_registrant_street'] = $whois_info['registrant_street'];
        $common_result['whois_registrant_city'] = $whois_info['registrant_city'];
        $common_result['whois_registrant_state'] = $whois_info['registrant_state'];
        $common_result['whois_registrant_postal_code'] = $whois_info['registrant_postal_code'];
        $common_result['whois_registrant_email'] = $whois_info['registrant_email'];
        $common_result['whois_registrant_country'] = $whois_info['registrant_country'];
        $common_result['whois_registrant_phone'] = $whois_info['registrant_phone'];
        $common_result['whois_admin_name'] = $whois_info['admin_name'];
        $common_result['whois_admin_street'] = $whois_info['admin_street'];
        $common_result['whois_admin_city'] = $whois_info['admin_city'];
        $common_result['whois_admin_postal_code'] = $whois_info['admin_postal_code'];
        $common_result['whois_admin_country'] = $whois_info['admin_country'];
        $common_result['whois_admin_phone'] = $whois_info['admin_phone'];
        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Whois ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);



        $get_ip_country = $this->web_common_report->get_ip_country($domain_name);
        $common_result['ipinfo_isp'] = $get_ip_country['isp'];
        $common_result['ipinfo_ip'] = $get_ip_country['ip'];
        $common_result['ipinfo_city'] = $get_ip_country['city'];
        $common_result['ipinfo_region'] = $get_ip_country['region'];
        $common_result['ipinfo_country'] = $get_ip_country['country'];
        $common_result['ipinfo_time_zone'] = $get_ip_country['time_zone'];
        $common_result['ipinfo_longitude'] = $get_ip_country['longitude'];
        $common_result['ipinfo_latitude'] = $get_ip_country['latitude'];
        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". IP ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);


        $this->web_common_report->get_site_in_same_ip($common_result['ipinfo_ip'],$page=1,$proxy=""); 
        $sites_in_same_ip=$this->web_common_report->same_site_in_ip; 
        $common_result['sites_in_same_ip']=json_encode($sites_in_same_ip);
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Site's in same IP - ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);


        $norton_safety_check = $this->web_common_report->norton_safety_check($domain_name,$proxy="");
        $common_result['norton_status'] = $norton_safety_check;
        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Norton ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);


        $google_safety_check = $this->web_common_report->google_safety_check($api,$domain_name);
        $common_result['google_safety_status'] = $google_safety_check;
        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Google Safety ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);

        $mcafee_status = $this->web_common_report->macafee_safety_analysis($domain_name,$proxy="");
        $common_result['macafee_status'] = $mcafee_status;

        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Mcafee Safety ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);

        $similar_site_from_google = $this->web_common_report->similar_site_from_google($domain_name);

        $common_result['similar_site'] = implode(',', $similar_site_from_google);

        //for dynamic progress bar data
        $add_complete++;
        $completed_function_str .= "<a href='#' class='list-group-item list-group-item-action text-primary'>".$add_complete.". Similar Site ".$this->lang->line("step completed")."<span class='text-primary float-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$completed_function_str]);


        unset($common_result['completed_step_count']);
        unset($common_result['completed_step_string']);
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],$common_result);

        $link = site_url()."website_analysis/analysis_report/".$web_common_info_id;
        echo '<a href="'.$link.'" class="btn btn-primary"><i class="fa fa-binoculars"></i> '.$this->lang->line("click here for detailed report").'</a><br/><br/>';

    }


    public function bulk_scan_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('website_analysis_bulk_total_search'); 

        $insert_table_id=$this->session->userdata('insert_table_id_website_analysis');
        $website_info = $this->basic->get_data('website_analysis_info',['where'=>['id'=>$insert_table_id]],['completed_step_string','completed_step_count']);
        $bulk_complete_search=(int)$website_info[0]['completed_step_count'];
        $completed_function_str=$website_info[0]['completed_step_string'];

        $response['view_details_button'] = 'not_set';
        if($insert_table_id != "")
        {

            $link = site_url()."website_analysis/analysis_report/".$insert_table_id;
            $view_button = '<a href="'.$link.'" class="btn btn-primary"><i class="fa fa-eye"></i> '.$this->lang->line("detailed report").'</a><br/><br/>';
            $response['view_details_button'] = $view_button;
        }
        
        $response['search_complete'] = $bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        $response['completed_function_str'] = $completed_function_str;
        
        echo json_encode($response);
        
    }

    public function analysis_report($id=0)
    {
        $data['id'] = $id;

        $where = array();
        $where['where'] = array('id'=>$id);
        $domain_info = $this->basic->get_data('website_analysis_info',$where,$select='');

        $data['country_list'] = $this->get_country_names();

        $data['body'] = 'website_analysis/analysis_report';
        $data['page_title'] = $this->lang->line("Website Analysis Report");
        $data['domain_info'] = $domain_info;
        $this->_viewcontroller($data);
    }


    public function update_common_info($domain_id=0)
    {
        if($this->update_common_info_action($domain_id))
            return $this->domain_list_for_domain_details();
        else
            return $this->domain_list_for_domain_details();
            
    }

    public function ajax_get_general_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);

        $where = array();
        $where['where'] = array('id'=>$domain_id);
        $domain_info = $this->basic->get_data('website_analysis_info',$where,$select='');
        $data['country_list'] = $this->get_country_names();
        $data['domain_info'] = $domain_info;
       
        $domain_details = $this->load->view('website_analysis/general',$data);
        
        echo $domain_details;

    }

    public function ajax_get_alexa_info_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $data["alexa_data"] = $this->basic->get_data("website_analysis_info",array("where"=>array("id"=>$domain_id)));
       
        $alexa_details = $this->load->view('admin/ranking/alexa_details',$data);

        echo $alexa_details;
    }

    public function ajax_get_social_network_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);

        $where = array();
        $where['where'] = array('id'=>$domain_id);
        $infos = $this->basic->get_data('website_analysis_info',$where,$select='');

        $domain_info = array();  
        $domain_info['domain_name'] = $infos[0]['domain_name'];

        $domain_info['fb_total_share'] = is_numeric($infos[0]['fb_total_share']) ? number_format($infos[0]['fb_total_share']) : $infos[0]['fb_total_share'];

        $domain_info['fb_total_reaction'] = is_numeric($infos[0]['fb_total_like']) ? number_format($infos[0]['fb_total_like']) : $infos[0]['fb_total_like'];

        $domain_info['fb_total_comment'] = is_numeric($infos[0]['fb_total_comment']) ? number_format($infos[0]['fb_total_comment']) : $infos[0]['fb_total_comment'];


        $domain_info['stumbleupon_total_view'] = is_numeric($infos[0]['stumbleupon_total_view']) ? number_format($infos[0]['stumbleupon_total_view']) : $infos[0]['stumbleupon_total_view'];
        if($domain_info['stumbleupon_total_view']=="")$domain_info['stumbleupon_total_view'] =0;

        $domain_info['reddit_score'] = is_numeric($infos[0]['reddit_score']) ? number_format($infos[0]['reddit_score']) : $infos[0]['reddit_score'];
        $domain_info['reddit_ups'] = is_numeric($infos[0]['reddit_ups']) ? number_format($infos[0]['reddit_ups']) : $infos[0]['reddit_ups'];
        $domain_info['reddit_downs'] = is_numeric($infos[0]['reddit_downs']) ? number_format($infos[0]['reddit_downs']) : $infos[0]['reddit_downs'];

        $domain_info['pinterest_pin'] = is_numeric($infos[0]['pinterest_pin']) ? number_format($infos[0]['pinterest_pin']) : $infos[0]['pinterest_pin'];
        $domain_info['buffer_share'] = is_numeric($infos[0]['buffer_share_count']) ? number_format($infos[0]['buffer_share_count']) : $infos[0]['buffer_share_count'];
        $domain_info['xing_share'] = is_numeric($infos[0]['xing_share_count']) ? number_format($infos[0]['xing_share_count']) : $infos[0]['xing_share_count'];


        $social_network_info = array(
            $this->lang->line('Facebook Total Share') => $infos[0]['fb_total_share'],        
            $this->lang->line('Facebook Total Like') => $infos[0]['fb_total_like'],        
            $this->lang->line('Facebook Total Comment') => $infos[0]['fb_total_comment'],        
            $this->lang->line('Pinterest') => $infos[0]['pinterest_pin'],        
            $this->lang->line('Reddit Score') => $infos[0]['reddit_score'],        
            $this->lang->line('Buffer Share Count') => $infos[0]['buffer_share_count'],        
            $this->lang->line('Xing Share Count') => $infos[0]['xing_share_count'],        
        );
        
        $domain_info['social_network_info'] = $social_network_info;

        $domain_info['color_codes'] = "

            <li class='media mb-1 pb-0'>
                <div class='social_shared_icon mt-1' style='background-color: #003f5c !important;'></div>
                <div class='media-body ml-3'>
                    <h4 class='media-title'>".$this->lang->line('Facebook Total Share')."</h4>
                </div>
            </li>
            <li class='media mb-0 pb-0'>
                <div class='social_shared_icon mt-2' style='background-color: #4571ef !important;'></div>
                <div class='media-body ml-3'>
                    <div class='media-title'>".$this->lang->line('Facebook Total Like')."</div>
                </div>
            </li>
            <li class='media mb-0 pb-0'>
                <div class='social_shared_icon mt-2' style='background-color: #ce6f45 !important;'></div>
                <div class='media-body ml-3'>
                    <div class='media-title'>".$this->lang->line('Facebook Total Comment')."</div>
                </div>
            </li>
            <li class='media mb-0 pb-0'>
                <div class='social_shared_icon mt-2' style='background-color: #58508d !important;'></div>
                <div class='media-body ml-3'>
                    <div class='media-title'>".$this->lang->line('Pinterest')."</div>
                </div>
            </li>
            <li class='media mb-0 pb-0'>
                <div class='social_shared_icon mt-2' style='background-color: #bc5090 !important;'></div>
                <div class='media-body ml-3'>
                    <div class='media-title'>".$this->lang->line('Reddit Score')."</div>
                </div>
            </li>
            <li class='media mb-0 pb-0'>
                <div class='social_shared_icon mt-2' style='background-color: #ff6361 !important;'></div>
                <div class='media-body ml-3'>
                    <div class='media-title'>".$this->lang->line('Buffer Share Count')."</div>
                </div>
            </li>
            <li class='media mb-0 pb-0'>
                <div class='social_shared_icon mt-2' style='background-color: #ffa600 !important;'></div>
                <div class='media-body ml-3'>
                    <div class='media-title'>".$this->lang->line('Xing Share Count')."</div>
                </div>
            </li>
        ";

        echo json_encode($domain_info);
    }

    public function ajax_get_meta_tag_info_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $data["meta_tag_info"]=$this->basic->get_data("website_analysis_info",array("where"=>array("id"=>$domain_id)));

        $meta_tag_info = $this->load->view('website_analysis/meta_tag_details',$data);

        echo $meta_tag_info;
    }


    public function delete_website_analysis_domain()
    {
        $this->db->trans_start();
        $id = $this->input->post('table_id',true);

        $this->basic->delete_data('website_analysis_info',$where=array('id'=>$id));

        $this->db->trans_complete();
        if($this->db->trans_status() === false) {
            echo "0";
        } else {
            echo "1";
        }
    }

    public function ajax_delete_all_selected_domain()
    {
        $this->ajax_check();

        $selected_domain_data = $this->input->post('info', true);

        if(!is_array($selected_domain_data)) {
            $selected_domain_data = array();
        }

        $implode_ids = implode(",",$selected_domain_data);

        $table = "website_analysis_info";

        if(!empty($selected_domain_data)) {

            $final_sql = "DELETE FROM website_analysis_info WHERE id IN({$implode_ids})";

            $this->db->query($final_sql);

            if($this->db->affected_rows() > 0) {
                echo "1";
            } else {
                echo "0";
            }
        }
    }

    public function download_analysis_report($id=0)
    {
        // $id = $this->input->post('table_id',true);
        $id = $id;
        $data["user_data"] = $this->basic->get_data("users",array("where"=>array("id"=>$this->session->userdata("user_id"))));
        
        $where = array();        
        $where['where'] = array('id'=>$id);        
        $domain_info = $this->basic->get_data('website_analysis_info',$where,$select='');
        $data['country_list'] = $this->get_country_names();
        $data['domain_info'] = $domain_info;
        $data["similar_web"] = $domain_info;
        $data["alexa_data"] = $domain_info;

        $info['fb_total_share'] = is_numeric($domain_info[0]['fb_total_share']) ? number_format($domain_info[0]['fb_total_share']) : 0;

        $info['fb_total_like'] = is_numeric($domain_info[0]['fb_total_like']) ? number_format($domain_info[0]['fb_total_like']) : 0;

        $info['fb_total_comment'] = is_numeric($domain_info[0]['fb_total_comment']) ? number_format($domain_info[0]['fb_total_comment']) : $domain_info[0]['fb_total_comment'];


        $info['stumbleupon_total_view'] = is_numeric($domain_info[0]['stumbleupon_total_view']) ? number_format($domain_info[0]['stumbleupon_total_view']) : 0;

        $info['reddit_score'] = is_numeric($domain_info[0]['reddit_score']) ? number_format($domain_info[0]['reddit_score']) : 0;
        $info['reddit_ups'] = is_numeric($domain_info[0]['reddit_ups']) ? number_format($domain_info[0]['reddit_ups']) : 0;
        $info['reddit_downs'] = is_numeric($domain_info[0]['reddit_downs']) ? number_format($domain_info[0]['reddit_downs']) : 0;

        $info['pinterest_pin'] = is_numeric($domain_info[0]['pinterest_pin']) ? number_format($domain_info[0]['pinterest_pin']) : 0;
        $info['buffer_share'] = is_numeric($domain_info[0]['buffer_share_count']) ? number_format($domain_info[0]['buffer_share_count']) : 0;
        $info['xing_share'] = is_numeric($domain_info[0]['xing_share_count']) ? number_format($domain_info[0]['xing_share_count']) : 0;

        $data['info'] = $info;

        ob_start();
        include(APPPATH ."vendor/autoload.php");
        $this->load->view("website_analysis/report/report",$data); 
        ob_get_contents();
        $html=ob_get_clean();  
        include(APPPATH ."vendor/mpdf/mpdf/src/Mpdf.php");
        $mpdf2=new \Mpdf\Mpdf();
        $mpdf2->addPage();
        $mpdf2->SetDisplayMode('fullpage');
        $mpdf2->writeHTML($html);       
        $domain = time();
        $download_id = $this->_random_number_generator(10);
        $file_name = "website_analysis_".$domain."_".$download_id.".pdf";
        $mpdf2->output($file_name,'I');
    }

}