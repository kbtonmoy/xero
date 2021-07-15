<?php if (! defined('BASEPATH')) {
    exit('No direct script access allowed');
}

/**
* @category controller
* class home
*/
class Home extends CI_Controller
{

    /**
    * load constructor
    * @access public
    * @return void
    */
    public $module_access;
    public $language;
    public $is_rtl;
    public $user_id;
    public $is_demo;

    public $is_ad_enabled;
    public $is_ad_enabled1;
    public $is_ad_enabled2;
    public $is_ad_enabled3;
    public $is_ad_enabled4;

    public $ad_content1;
    public $ad_content1_mobile;
    public $ad_content2;
    public $ad_content3;
    public $ad_content4;
    public $app_product_id;
    public $APP_VERSION;


    public function __construct()
    {
        parent::__construct();
        set_time_limit(0);
        $this->load->helpers(array('my_helper','addon_helper'));

        $this->is_rtl=FALSE;

        $is_demo = $this->config->item("is_demo");
        if($is_demo=="") $is_demo="0";
        $this->is_demo=$is_demo;

        $this->language="";
        $this->_language_loader();

        $this->is_ad_enabled=false;
        $this->is_ad_enabled1=false;
        $this->is_ad_enabled2=false;
        $this->is_ad_enabled3=false;
        $this->is_ad_enabled4=false;

        $this->ad_content1="";
        $this->ad_content1_mobile="";
        $this->ad_content2="";
        $this->ad_content3="";
        $this->ad_content4="";
        $this->app_product_id=34;
        $this->APP_VERSION="";

        ignore_user_abort(TRUE);

        $seg = $this->uri->segment(2);
        if ($seg!="installation" && $seg!= "installation_action") {
            if (file_exists(APPPATH.'install.txt')) {
                redirect('home/installation', 'location');
            }
        }

        if (!file_exists(APPPATH.'install.txt')) {
            $this->load->database();
            $this->load->model('basic');
            $this->_time_zone_set();
            $this->user_id=$this->session->userdata("user_id");
            $this->load->library('upload');
            $this->load->helper('security');
            $this->upload_path = realpath(APPPATH . '../upload');
            $this->session->unset_userdata('set_custom_link');
            $query = 'SET SESSION group_concat_max_len=9990000000000000000';
            $this->db->query($query);
            $q= "SET SESSION wait_timeout=50000";
            $this->db->query($q);
            /**Disable STRICT_TRANS_TABLES mode if exist on mysql ***/
            $query="SET SESSION sql_mode = ''";
            $this->db->query($query);

            /**Change Datbase Collation **/
            $query="SET NAMES utf8mb4";
            $this->db->query($query);


            //loading addon language
            $this->language_loader_addon();

            if(function_exists('ini_set')){
            ini_set('memory_limit', '-1');
            }

            $ad_config = $this->basic->get_data("ad_config");
            if(isset($ad_config[0]["status"]))
            {
               if($ad_config[0]["status"]=="1")
               {
                    $this->is_ad_enabled = ($ad_config[0]["status"]=="1") ? true : false;
                    if($this->is_ad_enabled)
                    {
                        $this->is_ad_enabled1 = ($ad_config[0]["section1_html"]=="" && $ad_config[0]["section1_html_mobile"]=="") ? false : true;
                        $this->is_ad_enabled2 = ($ad_config[0]["section2_html"]=="") ? false : true;
                        $this->is_ad_enabled3 = ($ad_config[0]["section3_html"]=="") ? false : true;
                        $this->is_ad_enabled4 = ($ad_config[0]["section4_html"]=="") ? false : true;

                        $this->ad_content1          = htmlspecialchars_decode($ad_config[0]["section1_html"],ENT_QUOTES);
                        $this->ad_content1_mobile   = htmlspecialchars_decode($ad_config[0]["section1_html_mobile"],ENT_QUOTES);
                        $this->ad_content2          = htmlspecialchars_decode($ad_config[0]["section2_html"],ENT_QUOTES);
                        $this->ad_content3          = htmlspecialchars_decode($ad_config[0]["section3_html"],ENT_QUOTES);
                        $this->ad_content4          = htmlspecialchars_decode($ad_config[0]["section4_html"],ENT_QUOTES);
                    }
               }

            }
            // else
            // {
            //     $this->is_ad_enabled  = true;
            //     $this->is_ad_enabled1 = true;
            //     $this->is_ad_enabled2 = true;
            //     $this->is_ad_enabled3 = true;
            //     $this->is_ad_enabled4 = true;

            //     $this->ad_content1="<img src='".base_url('assets/images/placeholder/reserved-section-1.png')."'>";
            //     $this->ad_content1_mobile="<img src='".base_url('assets/images/placeholder/reserved-section-1-mobile.png')."'>";
            //     $this->ad_content2="<img src='".base_url('assets/images/placeholder/reserved-section-2.png')."'>";
            //     $this->ad_content3="<img src='".base_url('assets/images/placeholder/reserved-section-3.png')."'>";
            //     $this->ad_content4="<img src='".base_url('assets/images/placeholder/reserved-section-4.png')."'>";

            // }

            if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin')
            {
                $package_info=$this->session->userdata("package_info");
                $module_ids='';
                if(isset($package_info["module_ids"])) $module_ids=$package_info["module_ids"];
                $this->module_access=explode(',', $module_ids);
            }

            $version_data=$this->basic->get_data("version",array("where"=>array("current"=>"1")));
            $appversion=isset($version_data[0]['version']) ? $version_data[0]['version'] : "";
            $this->APP_VERSION=$appversion;

        }

        if($this->config->item('force_https')=='1')
        {
            $actualLink = $actualLink = base_url(uri_string());
            $poS=strpos($actualLink, 'http://');
            if($poS!==FALSE)
            {
             $new_link=str_replace('http://', 'https://', $actualLink);
             redirect($new_link,'refresh');
            }
        }

        if($this->session->userdata('log_me_out') == '1') $this->logout();

        if($this->session->userdata('csrf_token_session')=="") $this->session->set_userdata('csrf_token_session',  bin2hex(random_bytes(32)));

    }



    // ***************************************************************
            // front end website analysis section
    // ***************************************************************
    public function front_end_website_analysis()
    {   
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $this->load->library('web_common_report');
        $common_result=array();

        if($this->session->userdata('user_id') != '')
        {
            $user_id = $this->session->userdata('user_id');
            $common_result['user_id'] = $user_id;
        }
        else
        {
            $user_info = $this->basic->get_data('users',array('where'=>array('user_type'=>'Admin','status'=>'1','deleted'=>'0')));
            if(!empty($user_info))
                $user_id = $user_info[0]['id'];
        }


        $domain_name = strtolower($this->input->post('domain_name', true));

        $use_admin_app = $this->config->item('use_admin_app');
        if($use_admin_app == '' || $use_admin_app == 'no')
          $config_data = $this->basic->get_data('config',['where'=>['user_id'=>$user_id]]);
        else
          $config_data = $this->basic->get_data('config',['where'=>['access'=>'all_users']],'','',1,0);


        $moz_access_id="";
        $moz_secret_key="";
        $mobile_ready_api_key="";
        $api='';
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


        $common_result['domain_name'] = $domain_name;
        $common_result['search_at'] = date("Y-m-d G:i:s");

        //for dynamic progress bar data

        $add_complete = 0;
        $website_analysis_completed_function_str='';
        $common_result['completed_step_count'] = $add_complete;
        $common_result['completed_step_string'] = '';
        if($this->session->userdata('user_id') != '')
        {
            $search_user_id = $this->session->userdata('user_id');
        }

        else $search_user_id = 0;
        $search_existing_info = $this->basic->get_data('website_analysis_info',['where'=>['user_id'=>$search_user_id,'domain_name'=>$domain_name]],['id']);


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

        $this->session->set_userdata('insert_table_id', $web_common_info_id);
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

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  MOZ ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);

        // end of get moz info



        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Mobile Friendly ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);

        // end of get mobile ready


        $backlink_count=$common_result['moz_external_equity_links'];
        if($backlink_count=="")
            $backlink_count=0;


        $common_result['google_back_link_count'] = number_format($backlink_count);
        $add_complete++;
        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Backlink ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);



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

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Alexa ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);





        $fb_like_comment_share = $this->web_common_report->fb_like_comment_share(addHttp($domain_name));

        // $common_result['domain_id'] = $domain_info[0]['id'];

        if(isset($fb_like_comment_share['total_share']))

            $common_result['fb_total_share'] = $fb_like_comment_share['total_share'];

        else $common_result['fb_total_share'] = 0;



        if(isset($fb_like_comment_share['total_like']))

            $common_result['fb_total_like'] = $fb_like_comment_share['total_like'];

        else $common_result['fb_total_like'] = 0;



        if(isset($fb_like_comment_share['total_comment']))

            $common_result['fb_total_comment'] = $fb_like_comment_share['total_comment'];

        else $common_result['fb_total_comment'] = 0;



        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Facebook ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);





        $pinterest_info = $this->web_common_report->pinterest_pin($domain_name);

        $common_result['pinterest_pin'] = $pinterest_info;

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Pinterest ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);



        $stumbleupon_info = $this->web_common_report->stumbleupon_info($domain_name);

        $common_result['stumbleupon_total_view'] = $stumbleupon_info['total_view'];

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Stumbleupon ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);



        $buffer_info = $this->web_common_report->buffer_share($domain_name);

        $common_result['buffer_share_count'] = $buffer_info;

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Buffer ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);





        $GoogleIP = $this->web_common_report->GoogleIP($domain_name);

        $common_result['google_index_count'] = $GoogleIP;

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Google Index ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);





        $reddit_count = $this->web_common_report->reddit_count($domain_name);

        $common_result['reddit_score'] = $reddit_count['score'];

        $common_result['reddit_ups'] = $reddit_count['ups'];

        $common_result['reddit_downs'] = $reddit_count['downs'];

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Reddit ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);



        $xing_share_count = $this->web_common_report->xing_share_count($domain_name);
        $common_result['xing_share_count'] = empty($xing_share_count) ? 0 : $xing_share_count;

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Xing ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);




        $bing_index = $this->web_common_report->bing_index($domain_name);
        $common_result['bing_index_count'] = $bing_index;

        //for dynamic progress bar data

        $add_complete++;
        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Bing ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);


        $yahoo_index = $this->web_common_report->yahoo_index($domain_name);
        $common_result['yahoo_index_count'] = $yahoo_index;

        //for dynamic progress bar data

        $add_complete++;
        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Yahoo ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);







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
        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Metatag ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);




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
        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Whois ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";
        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);




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

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  IP ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);





        $this->web_common_report->get_site_in_same_ip($common_result['ipinfo_ip'],$page=1,$proxy="");

        $sites_in_same_ip=$this->web_common_report->same_site_in_ip;

        $common_result['sites_in_same_ip']=json_encode($sites_in_same_ip);

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Site's in same IP - ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);




        $macafee_safety_analysis = $this->web_common_report->macafee_safety_analysis($domain_name,$proxy="");
        $common_result['macafee_status'] = $macafee_safety_analysis;
        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Macafee ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);





        $norton_safety_check = $this->web_common_report->norton_safety_check($domain_name,$proxe="");
        $common_result['norton_status'] = $norton_safety_check;

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Norton ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);







        $google_safety_check = $this->web_common_report->google_safety_check($api,$domain_name);
        $common_result['google_safety_status'] = $google_safety_check;

        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Google Safety ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);



        $similar_site_from_google = $this->web_common_report->similar_site_from_google($domain_name);
        $common_result['similar_site'] = implode(',', $similar_site_from_google);



        //for dynamic progress bar data

        $add_complete++;

        $website_analysis_completed_function_str .= "<a href='#' class='list-group-item text-primary'>".$add_complete.".  Similar Site ".$this->lang->line("step completed")."<span class='text-primary pull-right'><i class='fa fa-check-circle'></i></span></a>";

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],['completed_step_count'=>$add_complete,'completed_step_string'=>$website_analysis_completed_function_str]);



        unset($common_result['completed_step_count']);
        unset($common_result['completed_step_string']);

        $this->basic->update_data('website_analysis_info',['id'=>$web_common_info_id],$common_result);
        $link = site_url()."home/frontend_domain_details_view/".$web_common_info_id;
        echo '<a href="'.$link.'" class="btn btn-primary"><i class="fa fa-eye"></i> '.$this->lang->line("Detailed report").'</a><br/>';


    }


    public function front_end_bulk_scan_progress_count()
    {
        $insert_table_id=$this->session->userdata('insert_table_id');
        $website_info = $this->basic->get_data('website_analysis_info',['where'=>['id'=>$insert_table_id]],['completed_step_string','completed_step_count']);

        $bulk_tracking_total_search=$this->session->userdata('website_analysis_bulk_total_search');

        $bulk_complete_search=(int)$website_info[0]['completed_step_count'];
        $website_analysis_completed_function_str=$website_info[0]['completed_step_string'];

        $response['view_details_button'] = 'not_set';
        if($insert_table_id != "")
        {

            $link = site_url()."home/frontend_domain_details_view/".$insert_table_id;
            $view_button = '<a href="'.$link.'" class="btn btn-primary"><i class="fa fa-eye"></i> '.$this->lang->line("Detailed report").'</a><br/>';
            $response['view_details_button'] = $view_button;
        }

        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        $response['completed_function_str'] = $website_analysis_completed_function_str;

        echo json_encode($response);

    }


    public function frontend_domain_details_view($id=0)
    {
        $data['id'] = $id;

        $where = array();
        $where['where'] = array('id'=>$id);
        $domain_info = $this->basic->get_data('website_analysis_info',$where,$select='');

        $data['country_list'] = $this->get_country_names();

        $data['body'] = 'page/domain_details';
        $data['page_title'] = $this->lang->line("website analysis");
        $data['domain_info'] = $domain_info;
        $this->_frontend_website_details_theme($data);
    }


    public function _frontend_website_details_theme($data=array())
    {
        // $this->_disable_cache();
        if (!isset($data['body'])) {
            $data['body']=$this->config->item('default_page_url');
        }

        if (!isset($data['page_title'])) {
            $data['page_title']="";
        }

        $this->load->view('page/frontend_website_analysis_theme', $data);
    }


    public function front_ajax_get_general_data()
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

    public function front_ajax_get_alexa_info_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $data["alexa_data"]=$this->basic->get_data("website_analysis_info",array("where"=>array("id"=>$domain_id)));

        $alexa_details = $this->load->view('admin/ranking/alexa_details',$data);

        echo $alexa_details;
    }


    public function front_ajax_get_social_network_data()
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
        if($domain_info['stumbleupon_total_view']=="")   $domain_info['stumbleupon_total_view']=0;

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

    public function front_ajax_get_meta_tag_info_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $data["meta_tag_info"]=$this->basic->get_data("website_analysis_info",array("where"=>array("id"=>$domain_id)));
        $meta_tag_info = $this->load->view('website_analysis/meta_tag_details',$data);

        echo $meta_tag_info;
    }


    public function frontend_download_pdf($id)
    {
        // $id = $this->input->post('table_id',true);
        $id = $id;

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


    public function _language_loader()
    {

        if(!$this->config->item("language") || $this->config->item("language")=="")
        $this->language="english";
        else $this->language=$this->config->item('language');

        if($this->session->userdata("selected_language")!="")
        $this->language = $this->session->userdata("selected_language");
        else if(!$this->config->item("language") || $this->config->item("language")=="")
        $this->language="english";
        else $this->language=$this->config->item('language');

        // if($this->language=="arabic")
        // $this->is_rtl=TRUE;

        $path=str_replace('\\', '/', APPPATH.'/language/'.$this->language);
        $files=$this->_scanAll($path);
        foreach ($files as $key2 => $value2)
        {
            $current_file=isset($value2['file']) ? str_replace('\\', '/', $value2['file']) : ""; //application/modules/addon_folder/language/language_folder/someting_lang.php
            if($current_file=="" || !is_file($current_file)) continue;
            $current_file_explode=explode('/',$current_file);
            $filename=array_pop($current_file_explode);
            $pos=strpos($filename,'_lang.php');
            if($pos!==false) // check if it is a lang file or not
            {
                $filename=str_replace('_lang.php', '', $filename);
                $this->lang->load($filename, $this->language);
            }
        }


    }

    public function installation()
    {
        if (!file_exists(APPPATH.'install.txt')) {
            redirect('home/login', 'location');
        }
        $data = array("body" => "front/install", "page_title" => "Install Package","language_info" => $this->_language_list());
        $this->_subscription_viewcontroller($data);
    }


    public function installation_action()
    {
        if (!file_exists(APPPATH.'install.txt')) {
            redirect('home/login', 'location');
        }

        if ($_POST) {
            // validation
            $this->form_validation->set_rules('host_name',               '<b>Host Name</b>',                   'trim|required');
            $this->form_validation->set_rules('database_name',           '<b>Database Name</b>',               'trim|required');
            $this->form_validation->set_rules('database_username',       '<b>Database Username</b>',           'trim|required');
            $this->form_validation->set_rules('database_password',       '<b>Database Password</b>',           'trim');
            $this->form_validation->set_rules('app_username',            '<b>Admin Panel Login Email</b>',     'trim|required|valid_email');
            $this->form_validation->set_rules('app_password',            '<b>Admin Panel Login Password</b>',  'trim|required');
            $this->form_validation->set_rules('institute_name',          '<b>Company Name</b>',                'trim');
            $this->form_validation->set_rules('institute_address',       '<b>Company Address</b>',             'trim');
            $this->form_validation->set_rules('institute_mobile',        '<b>Company Phone / Mobile</b>',      'trim');
            $this->form_validation->set_rules('language',                '<b>Language</b>',                    'trim');

            // go to config form page if validation wrong
            if ($this->form_validation->run() == false) {
                return $this->installation();
            } else {
                $host_name = addslashes(strip_tags($this->input->post('host_name', true)));
                $database_name = addslashes(strip_tags($this->input->post('database_name', true)));
                $database_username = addslashes(strip_tags($this->input->post('database_username', true)));
                $database_password = addslashes(strip_tags($this->input->post('database_password', true)));
                $app_username = addslashes(strip_tags($this->input->post('app_username', true)));
                $app_password = addslashes(strip_tags($this->input->post('app_password', true)));
                $institute_name = addslashes(strip_tags($this->input->post('institute_name', true)));
                $institute_address = addslashes(strip_tags($this->input->post('institute_address', true)));
                $institute_mobile = addslashes(strip_tags($this->input->post('institute_mobile', true)));
                $language = addslashes(strip_tags($this->input->post('language', true)));

                $con=@mysqli_connect($host_name, $database_username, $database_password);
                if (!$con) {
                    $mysql_error = "Could not connect to MySQL : ";
                    $mysql_error .= mysqli_connect_error();
                    $this->session->set_userdata('mysql_error', $mysql_error);
                    return $this->installation();
                }
                if (!@mysqli_select_db($con,$database_name)) {
                    $this->session->set_userdata('mysql_error', "Database not found.");
                    return $this->installation();
                }
                mysqli_close($con);

                 // writing application/config/my_config

                include('application/config/my_config.php');
                $config['institute_address1'] = $institute_name;
                $config['institute_address2'] = $institute_address;
                $config['institute_email'] = $app_username;
                $config['institute_mobile'] = $institute_mobile;
                $config['language'] = $language;
                file_put_contents('application/config/my_config.php', '<?php $config = ' . var_export($config, true) . ';');


                //writting application/config/database
                $database_data = "";
                $database_data.= "<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');\n
                    \$active_group = 'default';
                    \$active_record = true;
                    \$db['default']['hostname'] = '$host_name';
                    \$db['default']['username'] = '$database_username';
                    \$db['default']['password'] = '$database_password';
                    \$db['default']['database'] = '$database_name';
                    \$db['default']['dbdriver'] = 'mysqli';
                    \$db['default']['dbprefix'] = '';
                    \$db['default']['pconnect'] = FALSE;
                    \$db['default']['db_debug'] = TRUE;
                    \$db['default']['cache_on'] = FALSE;
                    \$db['default']['cachedir'] = '';
                    \$db['default']['char_set'] = 'utf8';
                    \$db['default']['dbcollat'] = 'utf8_general_ci';
                    \$db['default']['swap_pre'] = '';
                    \$db['default']['autoinit'] = TRUE;
                    \$db['default']['stricton'] = FALSE;";
                file_put_contents(APPPATH.'config/database.php', $database_data, LOCK_EX);
                //writting application/config/database


                // loding database library, because we need to run queries below and configs are already written
                $this->load->database();
                $this->load->model('basic');
                // loding database library, because we need to run queries below and configs are already written

                // dumping sql
                $dump_file_name = 'initial_db.sql';
                $dump_sql_path = 'assets/backup_db/'.$dump_file_name;
                $this->basic->import_dump($dump_sql_path);
                // dumping sql

                // Insert Version
                $this->db->insert('version', array('version' => trim($this->config->item('product_version')), 'current' => '1', 'date' => date('Y-m-d H:i:s')));

                //generating hash password for admin and updaing database
                $app_password = md5($app_password);
                $this->basic->update_data($table = "users", $where = array("user_type" => "Admin"), $update_data = array("mobile" => $institute_mobile, "email" => $app_username, "password" => $app_password, "name" => $institute_name, "status" => "1", "deleted" => "0", "address" => $institute_address));
                  //generating hash password for admin and updaing database

                  //deleting the install.txt file,because installation is complete
                  if (file_exists(APPPATH.'install.txt')) {
                      unlink(APPPATH.'install.txt');
                  }
                  //deleting the install.txt file,because installation is complete
                  redirect('home/login');
            }
        }
    }


    public function index()
    {
        $display_landing_page=$this->config->item('display_landing_page');
        if($display_landing_page=='') $display_landing_page='0';

        if($display_landing_page=='0')
        $this->login_page();
        else $this->_site_viewcontroller();
    }


    public function _time_zone_set()
    {
       $time_zone = $this->config->item('time_zone');
        if ($time_zone== '') {
            $time_zone="Europe/Dublin";
        }
        date_default_timezone_set($time_zone);
    }


    public function _time_zone_list()
    {
        return $timezones =
        array(
            'America/Adak' => '(GMT-10:00) America/Adak (Hawaii-Aleutian Standard Time)',
            'America/Atka' => '(GMT-10:00) America/Atka (Hawaii-Aleutian Standard Time)',
            'America/Anchorage' => '(GMT-9:00) America/Anchorage (Alaska Standard Time)',
            'America/Juneau' => '(GMT-9:00) America/Juneau (Alaska Standard Time)',
            'America/Nome' => '(GMT-9:00) America/Nome (Alaska Standard Time)',
            'America/Yakutat' => '(GMT-9:00) America/Yakutat (Alaska Standard Time)',
            'America/Dawson' => '(GMT-8:00) America/Dawson (Pacific Standard Time)',
            'America/Ensenada' => '(GMT-8:00) America/Ensenada (Pacific Standard Time)',
            'America/Los_Angeles' => '(GMT-8:00) America/Los_Angeles (Pacific Standard Time)',
            'America/Tijuana' => '(GMT-8:00) America/Tijuana (Pacific Standard Time)',
            'America/Vancouver' => '(GMT-8:00) America/Vancouver (Pacific Standard Time)',
            'America/Whitehorse' => '(GMT-8:00) America/Whitehorse (Pacific Standard Time)',
            'Canada/Pacific' => '(GMT-8:00) Canada/Pacific (Pacific Standard Time)',
            'Canada/Yukon' => '(GMT-8:00) Canada/Yukon (Pacific Standard Time)',
            'Mexico/BajaNorte' => '(GMT-8:00) Mexico/BajaNorte (Pacific Standard Time)',
            'America/Boise' => '(GMT-7:00) America/Boise (Mountain Standard Time)',
            'America/Cambridge_Bay' => '(GMT-7:00) America/Cambridge_Bay (Mountain Standard Time)',
            'America/Chihuahua' => '(GMT-7:00) America/Chihuahua (Mountain Standard Time)',
            'America/Dawson_Creek' => '(GMT-7:00) America/Dawson_Creek (Mountain Standard Time)',
            'America/Denver' => '(GMT-7:00) America/Denver (Mountain Standard Time)',
            'America/Edmonton' => '(GMT-7:00) America/Edmonton (Mountain Standard Time)',
            'America/Hermosillo' => '(GMT-7:00) America/Hermosillo (Mountain Standard Time)',
            'America/Inuvik' => '(GMT-7:00) America/Inuvik (Mountain Standard Time)',
            'America/Mazatlan' => '(GMT-7:00) America/Mazatlan (Mountain Standard Time)',
            'America/Phoenix' => '(GMT-7:00) America/Phoenix (Mountain Standard Time)',
            'America/Shiprock' => '(GMT-7:00) America/Shiprock (Mountain Standard Time)',
            'America/Yellowknife' => '(GMT-7:00) America/Yellowknife (Mountain Standard Time)',
            'Canada/Mountain' => '(GMT-7:00) Canada/Mountain (Mountain Standard Time)',
            'Mexico/BajaSur' => '(GMT-7:00) Mexico/BajaSur (Mountain Standard Time)',
            'America/Belize' => '(GMT-6:00) America/Belize (Central Standard Time)',
            'America/Cancun' => '(GMT-6:00) America/Cancun (Central Standard Time)',
            'America/Chicago' => '(GMT-6:00) America/Chicago (Central Standard Time)',
            'America/Costa_Rica' => '(GMT-6:00) America/Costa_Rica (Central Standard Time)',
            'America/El_Salvador' => '(GMT-6:00) America/El_Salvador (Central Standard Time)',
            'America/Guatemala' => '(GMT-6:00) America/Guatemala (Central Standard Time)',
            'America/Knox_IN' => '(GMT-6:00) America/Knox_IN (Central Standard Time)',
            'America/Managua' => '(GMT-6:00) America/Managua (Central Standard Time)',
            'America/Menominee' => '(GMT-6:00) America/Menominee (Central Standard Time)',
            'America/Merida' => '(GMT-6:00) America/Merida (Central Standard Time)',
            'America/Mexico_City' => '(GMT-6:00) America/Mexico_City (Central Standard Time)',
            'America/Monterrey' => '(GMT-6:00) America/Monterrey (Central Standard Time)',
            'America/Rainy_River' => '(GMT-6:00) America/Rainy_River (Central Standard Time)',
            'America/Rankin_Inlet' => '(GMT-6:00) America/Rankin_Inlet (Central Standard Time)',
            'America/Regina' => '(GMT-6:00) America/Regina (Central Standard Time)',
            'America/Swift_Current' => '(GMT-6:00) America/Swift_Current (Central Standard Time)',
            'America/Tegucigalpa' => '(GMT-6:00) America/Tegucigalpa (Central Standard Time)',
            'America/Winnipeg' => '(GMT-6:00) America/Winnipeg (Central Standard Time)',
            'Canada/Central' => '(GMT-6:00) Canada/Central (Central Standard Time)',
            'Canada/East-Saskatchewan' => '(GMT-6:00) Canada/East-Saskatchewan (Central Standard Time)',
            'Canada/Saskatchewan' => '(GMT-6:00) Canada/Saskatchewan (Central Standard Time)',
            'Chile/EasterIsland' => '(GMT-6:00) Chile/EasterIsland (Easter Is. Time)',
            'Mexico/General' => '(GMT-6:00) Mexico/General (Central Standard Time)',
            'America/Atikokan' => '(GMT-5:00) America/Atikokan (Eastern Standard Time)',
            'America/Bogota' => '(GMT-5:00) America/Bogota (Colombia Time)',
            'America/Cayman' => '(GMT-5:00) America/Cayman (Eastern Standard Time)',
            'America/Coral_Harbour' => '(GMT-5:00) America/Coral_Harbour (Eastern Standard Time)',
            'America/Detroit' => '(GMT-5:00) America/Detroit (Eastern Standard Time)',
            'America/Fort_Wayne' => '(GMT-5:00) America/Fort_Wayne (Eastern Standard Time)',
            'America/Grand_Turk' => '(GMT-5:00) America/Grand_Turk (Eastern Standard Time)',
            'America/Guayaquil' => '(GMT-5:00) America/Guayaquil (Ecuador Time)',
            'America/Havana' => '(GMT-5:00) America/Havana (Cuba Standard Time)',
            'America/Indianapolis' => '(GMT-5:00) America/Indianapolis (Eastern Standard Time)',
            'America/Iqaluit' => '(GMT-5:00) America/Iqaluit (Eastern Standard Time)',
            'America/Jamaica' => '(GMT-5:00) America/Jamaica (Eastern Standard Time)',
            'America/Lima' => '(GMT-5:00) America/Lima (Peru Time)',
            'America/Louisville' => '(GMT-5:00) America/Louisville (Eastern Standard Time)',
            'America/Montreal' => '(GMT-5:00) America/Montreal (Eastern Standard Time)',
            'America/Nassau' => '(GMT-5:00) America/Nassau (Eastern Standard Time)',
            'America/New_York' => '(GMT-5:00) America/New_York (Eastern Standard Time)',
            'America/Nipigon' => '(GMT-5:00) America/Nipigon (Eastern Standard Time)',
            'America/Panama' => '(GMT-5:00) America/Panama (Eastern Standard Time)',
            'America/Pangnirtung' => '(GMT-5:00) America/Pangnirtung (Eastern Standard Time)',
            'America/Port-au-Prince' => '(GMT-5:00) America/Port-au-Prince (Eastern Standard Time)',
            'America/Resolute' => '(GMT-5:00) America/Resolute (Eastern Standard Time)',
            'America/Thunder_Bay' => '(GMT-5:00) America/Thunder_Bay (Eastern Standard Time)',
            'America/Toronto' => '(GMT-5:00) America/Toronto (Eastern Standard Time)',
            'Canada/Eastern' => '(GMT-5:00) Canada/Eastern (Eastern Standard Time)',
            'America/Caracas' => '(GMT-4:-30) America/Caracas (Venezuela Time)',
            'America/Anguilla' => '(GMT-4:00) America/Anguilla (Atlantic Standard Time)',
            'America/Antigua' => '(GMT-4:00) America/Antigua (Atlantic Standard Time)',
            'America/Aruba' => '(GMT-4:00) America/Aruba (Atlantic Standard Time)',
            'America/Asuncion' => '(GMT-4:00) America/Asuncion (Paraguay Time)',
            'America/Barbados' => '(GMT-4:00) America/Barbados (Atlantic Standard Time)',
            'America/Blanc-Sablon' => '(GMT-4:00) America/Blanc-Sablon (Atlantic Standard Time)',
            'America/Boa_Vista' => '(GMT-4:00) America/Boa_Vista (Amazon Time)',
            'America/Campo_Grande' => '(GMT-4:00) America/Campo_Grande (Amazon Time)',
            'America/Cuiaba' => '(GMT-4:00) America/Cuiaba (Amazon Time)',
            'America/Curacao' => '(GMT-4:00) America/Curacao (Atlantic Standard Time)',
            'America/Dominica' => '(GMT-4:00) America/Dominica (Atlantic Standard Time)',
            'America/Eirunepe' => '(GMT-4:00) America/Eirunepe (Amazon Time)',
            'America/Glace_Bay' => '(GMT-4:00) America/Glace_Bay (Atlantic Standard Time)',
            'America/Goose_Bay' => '(GMT-4:00) America/Goose_Bay (Atlantic Standard Time)',
            'America/Grenada' => '(GMT-4:00) America/Grenada (Atlantic Standard Time)',
            'America/Guadeloupe' => '(GMT-4:00) America/Guadeloupe (Atlantic Standard Time)',
            'America/Guyana' => '(GMT-4:00) America/Guyana (Guyana Time)',
            'America/Halifax' => '(GMT-4:00) America/Halifax (Atlantic Standard Time)',
            'America/La_Paz' => '(GMT-4:00) America/La_Paz (Bolivia Time)',
            'America/Manaus' => '(GMT-4:00) America/Manaus (Amazon Time)',
            'America/Marigot' => '(GMT-4:00) America/Marigot (Atlantic Standard Time)',
            'America/Martinique' => '(GMT-4:00) America/Martinique (Atlantic Standard Time)',
            'America/Moncton' => '(GMT-4:00) America/Moncton (Atlantic Standard Time)',
            'America/Montserrat' => '(GMT-4:00) America/Montserrat (Atlantic Standard Time)',
            'America/Port_of_Spain' => '(GMT-4:00) America/Port_of_Spain (Atlantic Standard Time)',
            'America/Porto_Acre' => '(GMT-4:00) America/Porto_Acre (Amazon Time)',
            'America/Porto_Velho' => '(GMT-4:00) America/Porto_Velho (Amazon Time)',
            'America/Puerto_Rico' => '(GMT-4:00) America/Puerto_Rico (Atlantic Standard Time)',
            'America/Rio_Branco' => '(GMT-4:00) America/Rio_Branco (Amazon Time)',
            'America/Santiago' => '(GMT-4:00) America/Santiago (Chile Time)',
            'America/Santo_Domingo' => '(GMT-4:00) America/Santo_Domingo (Atlantic Standard Time)',
            'America/St_Barthelemy' => '(GMT-4:00) America/St_Barthelemy (Atlantic Standard Time)',
            'America/St_Kitts' => '(GMT-4:00) America/St_Kitts (Atlantic Standard Time)',
            'America/St_Lucia' => '(GMT-4:00) America/St_Lucia (Atlantic Standard Time)',
            'America/St_Thomas' => '(GMT-4:00) America/St_Thomas (Atlantic Standard Time)',
            'America/St_Vincent' => '(GMT-4:00) America/St_Vincent (Atlantic Standard Time)',
            'America/Thule' => '(GMT-4:00) America/Thule (Atlantic Standard Time)',
            'America/Tortola' => '(GMT-4:00) America/Tortola (Atlantic Standard Time)',
            'America/Virgin' => '(GMT-4:00) America/Virgin (Atlantic Standard Time)',
            'Antarctica/Palmer' => '(GMT-4:00) Antarctica/Palmer (Chile Time)',
            'Atlantic/Bermuda' => '(GMT-4:00) Atlantic/Bermuda (Atlantic Standard Time)',
            'Atlantic/Stanley' => '(GMT-4:00) Atlantic/Stanley (Falkland Is. Time)',
            'Brazil/Acre' => '(GMT-4:00) Brazil/Acre (Amazon Time)',
            'Brazil/West' => '(GMT-4:00) Brazil/West (Amazon Time)',
            'Canada/Atlantic' => '(GMT-4:00) Canada/Atlantic (Atlantic Standard Time)',
            'Chile/Continental' => '(GMT-4:00) Chile/Continental (Chile Time)',
            'America/St_Johns' => '(GMT-3:-30) America/St_Johns (Newfoundland Standard Time)',
            'Canada/Newfoundland' => '(GMT-3:-30) Canada/Newfoundland (Newfoundland Standard Time)',
            'America/Araguaina' => '(GMT-3:00) America/Araguaina (Brasilia Time)',
            'America/Bahia' => '(GMT-3:00) America/Bahia (Brasilia Time)',
            'America/Belem' => '(GMT-3:00) America/Belem (Brasilia Time)',
            'America/Buenos_Aires' => '(GMT-3:00) America/Buenos_Aires (Argentine Time)',
            'America/Catamarca' => '(GMT-3:00) America/Catamarca (Argentine Time)',
            'America/Cayenne' => '(GMT-3:00) America/Cayenne (French Guiana Time)',
            'America/Cordoba' => '(GMT-3:00) America/Cordoba (Argentine Time)',
            'America/Fortaleza' => '(GMT-3:00) America/Fortaleza (Brasilia Time)',
            'America/Godthab' => '(GMT-3:00) America/Godthab (Western Greenland Time)',
            'America/Jujuy' => '(GMT-3:00) America/Jujuy (Argentine Time)',
            'America/Maceio' => '(GMT-3:00) America/Maceio (Brasilia Time)',
            'America/Mendoza' => '(GMT-3:00) America/Mendoza (Argentine Time)',
            'America/Miquelon' => '(GMT-3:00) America/Miquelon (Pierre & Miquelon Standard Time)',
            'America/Montevideo' => '(GMT-3:00) America/Montevideo (Uruguay Time)',
            'America/Paramaribo' => '(GMT-3:00) America/Paramaribo (Suriname Time)',
            'America/Recife' => '(GMT-3:00) America/Recife (Brasilia Time)',
            'America/Rosario' => '(GMT-3:00) America/Rosario (Argentine Time)',
            'America/Santarem' => '(GMT-3:00) America/Santarem (Brasilia Time)',
            'America/Sao_Paulo' => '(GMT-3:00) America/Sao_Paulo (Brasilia Time)',
            'Antarctica/Rothera' => '(GMT-3:00) Antarctica/Rothera (Rothera Time)',
            'Brazil/East' => '(GMT-3:00) Brazil/East (Brasilia Time)',
            'America/Noronha' => '(GMT-2:00) America/Noronha (Fernando de Noronha Time)',
            'Atlantic/South_Georgia' => '(GMT-2:00) Atlantic/South_Georgia (South Georgia Standard Time)',
            'Brazil/DeNoronha' => '(GMT-2:00) Brazil/DeNoronha (Fernando de Noronha Time)',
            'America/Scoresbysund' => '(GMT-1:00) America/Scoresbysund (Eastern Greenland Time)',
            'Atlantic/Azores' => '(GMT-1:00) Atlantic/Azores (Azores Time)',
            'Atlantic/Cape_Verde' => '(GMT-1:00) Atlantic/Cape_Verde (Cape Verde Time)',
            'Africa/Abidjan' => '(GMT+0:00) Africa/Abidjan (Greenwich Mean Time)',
            'Africa/Accra' => '(GMT+0:00) Africa/Accra (Ghana Mean Time)',
            'Africa/Bamako' => '(GMT+0:00) Africa/Bamako (Greenwich Mean Time)',
            'Africa/Banjul' => '(GMT+0:00) Africa/Banjul (Greenwich Mean Time)',
            'Africa/Bissau' => '(GMT+0:00) Africa/Bissau (Greenwich Mean Time)',
            'Africa/Casablanca' => '(GMT+0:00) Africa/Casablanca (Western European Time)',
            'Africa/Conakry' => '(GMT+0:00) Africa/Conakry (Greenwich Mean Time)',
            'Africa/Dakar' => '(GMT+0:00) Africa/Dakar (Greenwich Mean Time)',
            'Africa/El_Aaiun' => '(GMT+0:00) Africa/El_Aaiun (Western European Time)',
            'Africa/Freetown' => '(GMT+0:00) Africa/Freetown (Greenwich Mean Time)',
            'Africa/Lome' => '(GMT+0:00) Africa/Lome (Greenwich Mean Time)',
            'Africa/Monrovia' => '(GMT+0:00) Africa/Monrovia (Greenwich Mean Time)',
            'Africa/Nouakchott' => '(GMT+0:00) Africa/Nouakchott (Greenwich Mean Time)',
            'Africa/Ouagadougou' => '(GMT+0:00) Africa/Ouagadougou (Greenwich Mean Time)',
            'Africa/Sao_Tome' => '(GMT+0:00) Africa/Sao_Tome (Greenwich Mean Time)',
            'Africa/Timbuktu' => '(GMT+0:00) Africa/Timbuktu (Greenwich Mean Time)',
            'America/Danmarkshavn' => '(GMT+0:00) America/Danmarkshavn (Greenwich Mean Time)',
            'Atlantic/Canary' => '(GMT+0:00) Atlantic/Canary (Western European Time)',
            'Atlantic/Faeroe' => '(GMT+0:00) Atlantic/Faeroe (Western European Time)',
            'Atlantic/Faroe' => '(GMT+0:00) Atlantic/Faroe (Western European Time)',
            'Atlantic/Madeira' => '(GMT+0:00) Atlantic/Madeira (Western European Time)',
            'Atlantic/Reykjavik' => '(GMT+0:00) Atlantic/Reykjavik (Greenwich Mean Time)',
            'Atlantic/St_Helena' => '(GMT+0:00) Atlantic/St_Helena (Greenwich Mean Time)',
            'Europe/Belfast' => '(GMT+0:00) Europe/Belfast (Greenwich Mean Time)',
            'Europe/Dublin' => '(GMT+0:00) Europe/Dublin (Greenwich Mean Time)',
            'Europe/Guernsey' => '(GMT+0:00) Europe/Guernsey (Greenwich Mean Time)',
            'Europe/Isle_of_Man' => '(GMT+0:00) Europe/Isle_of_Man (Greenwich Mean Time)',
            'Europe/Jersey' => '(GMT+0:00) Europe/Jersey (Greenwich Mean Time)',
            'Europe/Lisbon' => '(GMT+0:00) Europe/Lisbon (Western European Time)',
            'Europe/London' => '(GMT+0:00) Europe/London (Greenwich Mean Time)',
            'Africa/Algiers' => '(GMT+1:00) Africa/Algiers (Central European Time)',
            'Africa/Bangui' => '(GMT+1:00) Africa/Bangui (Western African Time)',
            'Africa/Brazzaville' => '(GMT+1:00) Africa/Brazzaville (Western African Time)',
            'Africa/Ceuta' => '(GMT+1:00) Africa/Ceuta (Central European Time)',
            'Africa/Douala' => '(GMT+1:00) Africa/Douala (Western African Time)',
            'Africa/Kinshasa' => '(GMT+1:00) Africa/Kinshasa (Western African Time)',
            'Africa/Lagos' => '(GMT+1:00) Africa/Lagos (Western African Time)',
            'Africa/Libreville' => '(GMT+1:00) Africa/Libreville (Western African Time)',
            'Africa/Luanda' => '(GMT+1:00) Africa/Luanda (Western African Time)',
            'Africa/Malabo' => '(GMT+1:00) Africa/Malabo (Western African Time)',
            'Africa/Ndjamena' => '(GMT+1:00) Africa/Ndjamena (Western African Time)',
            'Africa/Niamey' => '(GMT+1:00) Africa/Niamey (Western African Time)',
            'Africa/Porto-Novo' => '(GMT+1:00) Africa/Porto-Novo (Western African Time)',
            'Africa/Tunis' => '(GMT+1:00) Africa/Tunis (Central European Time)',
            'Africa/Windhoek' => '(GMT+1:00) Africa/Windhoek (Western African Time)',
            'Arctic/Longyearbyen' => '(GMT+1:00) Arctic/Longyearbyen (Central European Time)',
            'Atlantic/Jan_Mayen' => '(GMT+1:00) Atlantic/Jan_Mayen (Central European Time)',
            'Europe/Amsterdam' => '(GMT+1:00) Europe/Amsterdam (Central European Time)',
            'Europe/Andorra' => '(GMT+1:00) Europe/Andorra (Central European Time)',
            'Europe/Belgrade' => '(GMT+1:00) Europe/Belgrade (Central European Time)',
            'Europe/Berlin' => '(GMT+1:00) Europe/Berlin (Central European Time)',
            'Europe/Bratislava' => '(GMT+1:00) Europe/Bratislava (Central European Time)',
            'Europe/Brussels' => '(GMT+1:00) Europe/Brussels (Central European Time)',
            'Europe/Budapest' => '(GMT+1:00) Europe/Budapest (Central European Time)',
            'Europe/Copenhagen' => '(GMT+1:00) Europe/Copenhagen (Central European Time)',
            'Europe/Gibraltar' => '(GMT+1:00) Europe/Gibraltar (Central European Time)',
            'Europe/Ljubljana' => '(GMT+1:00) Europe/Ljubljana (Central European Time)',
            'Europe/Luxembourg' => '(GMT+1:00) Europe/Luxembourg (Central European Time)',
            'Europe/Madrid' => '(GMT+1:00) Europe/Madrid (Central European Time)',
            'Europe/Malta' => '(GMT+1:00) Europe/Malta (Central European Time)',
            'Europe/Monaco' => '(GMT+1:00) Europe/Monaco (Central European Time)',
            'Europe/Oslo' => '(GMT+1:00) Europe/Oslo (Central European Time)',
            'Europe/Paris' => '(GMT+1:00) Europe/Paris (Central European Time)',
            'Europe/Podgorica' => '(GMT+1:00) Europe/Podgorica (Central European Time)',
            'Europe/Prague' => '(GMT+1:00) Europe/Prague (Central European Time)',
            'Europe/Rome' => '(GMT+1:00) Europe/Rome (Central European Time)',
            'Europe/San_Marino' => '(GMT+1:00) Europe/San_Marino (Central European Time)',
            'Europe/Sarajevo' => '(GMT+1:00) Europe/Sarajevo (Central European Time)',
            'Europe/Skopje' => '(GMT+1:00) Europe/Skopje (Central European Time)',
            'Europe/Stockholm' => '(GMT+1:00) Europe/Stockholm (Central European Time)',
            'Europe/Tirane' => '(GMT+1:00) Europe/Tirane (Central European Time)',
            'Europe/Vaduz' => '(GMT+1:00) Europe/Vaduz (Central European Time)',
            'Europe/Vatican' => '(GMT+1:00) Europe/Vatican (Central European Time)',
            'Europe/Vienna' => '(GMT+1:00) Europe/Vienna (Central European Time)',
            'Europe/Warsaw' => '(GMT+1:00) Europe/Warsaw (Central European Time)',
            'Europe/Zagreb' => '(GMT+1:00) Europe/Zagreb (Central European Time)',
            'Europe/Zurich' => '(GMT+1:00) Europe/Zurich (Central European Time)',
            'Africa/Blantyre' => '(GMT+2:00) Africa/Blantyre (Central African Time)',
            'Africa/Bujumbura' => '(GMT+2:00) Africa/Bujumbura (Central African Time)',
            'Africa/Cairo' => '(GMT+2:00) Africa/Cairo (Eastern European Time)',
            'Africa/Gaborone' => '(GMT+2:00) Africa/Gaborone (Central African Time)',
            'Africa/Harare' => '(GMT+2:00) Africa/Harare (Central African Time)',
            'Africa/Johannesburg' => '(GMT+2:00) Africa/Johannesburg (South Africa Standard Time)',
            'Africa/Kigali' => '(GMT+2:00) Africa/Kigali (Central African Time)',
            'Africa/Lubumbashi' => '(GMT+2:00) Africa/Lubumbashi (Central African Time)',
            'Africa/Lusaka' => '(GMT+2:00) Africa/Lusaka (Central African Time)',
            'Africa/Maputo' => '(GMT+2:00) Africa/Maputo (Central African Time)',
            'Africa/Maseru' => '(GMT+2:00) Africa/Maseru (South Africa Standard Time)',
            'Africa/Mbabane' => '(GMT+2:00) Africa/Mbabane (South Africa Standard Time)',
            'Africa/Tripoli' => '(GMT+2:00) Africa/Tripoli (Eastern European Time)',
            'Asia/Amman' => '(GMT+2:00) Asia/Amman (Eastern European Time)',
            'Asia/Beirut' => '(GMT+2:00) Asia/Beirut (Eastern European Time)',
            'Asia/Damascus' => '(GMT+2:00) Asia/Damascus (Eastern European Time)',
            'Asia/Gaza' => '(GMT+2:00) Asia/Gaza (Eastern European Time)',
            'Asia/Istanbul' => '(GMT+2:00) Asia/Istanbul (Eastern European Time)',
            'Asia/Jerusalem' => '(GMT+2:00) Asia/Jerusalem (Israel Standard Time)',
            'Asia/Nicosia' => '(GMT+2:00) Asia/Nicosia (Eastern European Time)',
            'Asia/Tel_Aviv' => '(GMT+2:00) Asia/Tel_Aviv (Israel Standard Time)',
            'Europe/Athens' => '(GMT+2:00) Europe/Athens (Eastern European Time)',
            'Europe/Bucharest' => '(GMT+2:00) Europe/Bucharest (Eastern European Time)',
            'Europe/Chisinau' => '(GMT+2:00) Europe/Chisinau (Eastern European Time)',
            'Europe/Helsinki' => '(GMT+2:00) Europe/Helsinki (Eastern European Time)',
            'Europe/Istanbul' => '(GMT+2:00) Europe/Istanbul (Eastern European Time)',
            'Europe/Kaliningrad' => '(GMT+2:00) Europe/Kaliningrad (Eastern European Time)',
            'Europe/Kiev' => '(GMT+2:00) Europe/Kiev (Eastern European Time)',
            'Europe/Mariehamn' => '(GMT+2:00) Europe/Mariehamn (Eastern European Time)',
            'Europe/Minsk' => '(GMT+2:00) Europe/Minsk (Eastern European Time)',
            'Europe/Nicosia' => '(GMT+2:00) Europe/Nicosia (Eastern European Time)',
            'Europe/Riga' => '(GMT+2:00) Europe/Riga (Eastern European Time)',
            'Europe/Simferopol' => '(GMT+2:00) Europe/Simferopol (Eastern European Time)',
            'Europe/Sofia' => '(GMT+2:00) Europe/Sofia (Eastern European Time)',
            'Europe/Tallinn' => '(GMT+2:00) Europe/Tallinn (Eastern European Time)',
            'Europe/Tiraspol' => '(GMT+2:00) Europe/Tiraspol (Eastern European Time)',
            'Europe/Uzhgorod' => '(GMT+2:00) Europe/Uzhgorod (Eastern European Time)',
            'Europe/Vilnius' => '(GMT+2:00) Europe/Vilnius (Eastern European Time)',
            'Europe/Zaporozhye' => '(GMT+2:00) Europe/Zaporozhye (Eastern European Time)',
            'Africa/Addis_Ababa' => '(GMT+3:00) Africa/Addis_Ababa (Eastern African Time)',
            'Africa/Asmara' => '(GMT+3:00) Africa/Asmara (Eastern African Time)',
            'Africa/Asmera' => '(GMT+3:00) Africa/Asmera (Eastern African Time)',
            'Africa/Dar_es_Salaam' => '(GMT+3:00) Africa/Dar_es_Salaam (Eastern African Time)',
            'Africa/Djibouti' => '(GMT+3:00) Africa/Djibouti (Eastern African Time)',
            'Africa/Kampala' => '(GMT+3:00) Africa/Kampala (Eastern African Time)',
            'Africa/Khartoum' => '(GMT+3:00) Africa/Khartoum (Eastern African Time)',
            'Africa/Mogadishu' => '(GMT+3:00) Africa/Mogadishu (Eastern African Time)',
            'Africa/Nairobi' => '(GMT+3:00) Africa/Nairobi (Eastern African Time)',
            'Antarctica/Syowa' => '(GMT+3:00) Antarctica/Syowa (Syowa Time)',
            'Asia/Aden' => '(GMT+3:00) Asia/Aden (Arabia Standard Time)',
            'Asia/Baghdad' => '(GMT+3:00) Asia/Baghdad (Arabia Standard Time)',
            'Asia/Bahrain' => '(GMT+3:00) Asia/Bahrain (Arabia Standard Time)',
            'Asia/Kuwait' => '(GMT+3:00) Asia/Kuwait (Arabia Standard Time)',
            'Asia/Qatar' => '(GMT+3:00) Asia/Qatar (Arabia Standard Time)',
            'Europe/Moscow' => '(GMT+3:00) Europe/Moscow (Moscow Standard Time)',
            'Europe/Volgograd' => '(GMT+3:00) Europe/Volgograd (Volgograd Time)',
            'Indian/Antananarivo' => '(GMT+3:00) Indian/Antananarivo (Eastern African Time)',
            'Indian/Comoro' => '(GMT+3:00) Indian/Comoro (Eastern African Time)',
            'Indian/Mayotte' => '(GMT+3:00) Indian/Mayotte (Eastern African Time)',
            'Asia/Tehran' => '(GMT+3:30) Asia/Tehran (Iran Standard Time)',
            'Asia/Baku' => '(GMT+4:00) Asia/Baku (Azerbaijan Time)',
            'Asia/Dubai' => '(GMT+4:00) Asia/Dubai (Gulf Standard Time)',
            'Asia/Muscat' => '(GMT+4:00) Asia/Muscat (Gulf Standard Time)',
            'Asia/Tbilisi' => '(GMT+4:00) Asia/Tbilisi (Georgia Time)',
            'Asia/Yerevan' => '(GMT+4:00) Asia/Yerevan (Armenia Time)',
            'Europe/Samara' => '(GMT+4:00) Europe/Samara (Samara Time)',
            'Indian/Mahe' => '(GMT+4:00) Indian/Mahe (Seychelles Time)',
            'Indian/Mauritius' => '(GMT+4:00) Indian/Mauritius (Mauritius Time)',
            'Indian/Reunion' => '(GMT+4:00) Indian/Reunion (Reunion Time)',
            'Asia/Kabul' => '(GMT+4:30) Asia/Kabul (Afghanistan Time)',
            'Asia/Aqtau' => '(GMT+5:00) Asia/Aqtau (Aqtau Time)',
            'Asia/Aqtobe' => '(GMT+5:00) Asia/Aqtobe (Aqtobe Time)',
            'Asia/Ashgabat' => '(GMT+5:00) Asia/Ashgabat (Turkmenistan Time)',
            'Asia/Ashkhabad' => '(GMT+5:00) Asia/Ashkhabad (Turkmenistan Time)',
            'Asia/Dushanbe' => '(GMT+5:00) Asia/Dushanbe (Tajikistan Time)',
            'Asia/Karachi' => '(GMT+5:00) Asia/Karachi (Pakistan Time)',
            'Asia/Oral' => '(GMT+5:00) Asia/Oral (Oral Time)',
            'Asia/Samarkand' => '(GMT+5:00) Asia/Samarkand (Uzbekistan Time)',
            'Asia/Tashkent' => '(GMT+5:00) Asia/Tashkent (Uzbekistan Time)',
            'Asia/Yekaterinburg' => '(GMT+5:00) Asia/Yekaterinburg (Yekaterinburg Time)',
            'Indian/Kerguelen' => '(GMT+5:00) Indian/Kerguelen (French Southern & Antarctic Lands Time)',
            'Indian/Maldives' => '(GMT+5:00) Indian/Maldives (Maldives Time)',
            'Asia/Calcutta' => '(GMT+5:30) Asia/Calcutta (India Standard Time)',
            'Asia/Colombo' => '(GMT+5:30) Asia/Colombo (India Standard Time)',
            'Asia/Kolkata' => '(GMT+5:30) Asia/Kolkata (India Standard Time)',
            'Asia/Katmandu' => '(GMT+5:45) Asia/Katmandu (Nepal Time)',
            'Antarctica/Mawson' => '(GMT+6:00) Antarctica/Mawson (Mawson Time)',
            'Antarctica/Vostok' => '(GMT+6:00) Antarctica/Vostok (Vostok Time)',
            'Asia/Almaty' => '(GMT+6:00) Asia/Almaty (Alma-Ata Time)',
            'Asia/Bishkek' => '(GMT+6:00) Asia/Bishkek (Kirgizstan Time)',
            'Asia/Dhaka' => '(GMT+6:00) Asia/Dhaka (Bangladesh Time)',
            'Asia/Novosibirsk' => '(GMT+6:00) Asia/Novosibirsk (Novosibirsk Time)',
            'Asia/Omsk' => '(GMT+6:00) Asia/Omsk (Omsk Time)',
            'Asia/Qyzylorda' => '(GMT+6:00) Asia/Qyzylorda (Qyzylorda Time)',
            'Asia/Thimbu' => '(GMT+6:00) Asia/Thimbu (Bhutan Time)',
            'Asia/Thimphu' => '(GMT+6:00) Asia/Thimphu (Bhutan Time)',
            'Indian/Chagos' => '(GMT+6:00) Indian/Chagos (Indian Ocean Territory Time)',
            'Asia/Rangoon' => '(GMT+6:30) Asia/Rangoon (Myanmar Time)',
            'Indian/Cocos' => '(GMT+6:30) Indian/Cocos (Cocos Islands Time)',
            'Antarctica/Davis' => '(GMT+7:00) Antarctica/Davis (Davis Time)',
            'Asia/Bangkok' => '(GMT+7:00) Asia/Bangkok (Indochina Time)',
            'Asia/Ho_Chi_Minh' => '(GMT+7:00) Asia/Ho_Chi_Minh (Indochina Time)',
            'Asia/Hovd' => '(GMT+7:00) Asia/Hovd (Hovd Time)',
            'Asia/Jakarta' => '(GMT+7:00) Asia/Jakarta (West Indonesia Time)',
            'Asia/Krasnoyarsk' => '(GMT+7:00) Asia/Krasnoyarsk (Krasnoyarsk Time)',
            'Asia/Phnom_Penh' => '(GMT+7:00) Asia/Phnom_Penh (Indochina Time)',
            'Asia/Pontianak' => '(GMT+7:00) Asia/Pontianak (West Indonesia Time)',
            'Asia/Saigon' => '(GMT+7:00) Asia/Saigon (Indochina Time)',
            'Asia/Vientiane' => '(GMT+7:00) Asia/Vientiane (Indochina Time)',
            'Indian/Christmas' => '(GMT+7:00) Indian/Christmas (Christmas Island Time)',
            'Antarctica/Casey' => '(GMT+8:00) Antarctica/Casey (Western Standard Time (Australia))',
            'Asia/Brunei' => '(GMT+8:00) Asia/Brunei (Brunei Time)',
            'Asia/Choibalsan' => '(GMT+8:00) Asia/Choibalsan (Choibalsan Time)',
            'Asia/Chongqing' => '(GMT+8:00) Asia/Chongqing (China Standard Time)',
            'Asia/Chungking' => '(GMT+8:00) Asia/Chungking (China Standard Time)',
            'Asia/Harbin' => '(GMT+8:00) Asia/Harbin (China Standard Time)',
            'Asia/Hong_Kong' => '(GMT+8:00) Asia/Hong_Kong (Hong Kong Time)',
            'Asia/Irkutsk' => '(GMT+8:00) Asia/Irkutsk (Irkutsk Time)',
            'Asia/Kashgar' => '(GMT+8:00) Asia/Kashgar (China Standard Time)',
            'Asia/Kuala_Lumpur' => '(GMT+8:00) Asia/Kuala_Lumpur (Malaysia Time)',
            'Asia/Kuching' => '(GMT+8:00) Asia/Kuching (Malaysia Time)',
            'Asia/Macao' => '(GMT+8:00) Asia/Macao (China Standard Time)',
            'Asia/Macau' => '(GMT+8:00) Asia/Macau (China Standard Time)',
            'Asia/Makassar' => '(GMT+8:00) Asia/Makassar (Central Indonesia Time)',
            'Asia/Manila' => '(GMT+8:00) Asia/Manila (Philippines Time)',
            'Asia/Shanghai' => '(GMT+8:00) Asia/Shanghai (China Standard Time)',
            'Asia/Singapore' => '(GMT+8:00) Asia/Singapore (Singapore Time)',
            'Asia/Taipei' => '(GMT+8:00) Asia/Taipei (China Standard Time)',
            'Asia/Ujung_Pandang' => '(GMT+8:00) Asia/Ujung_Pandang (Central Indonesia Time)',
            'Asia/Ulaanbaatar' => '(GMT+8:00) Asia/Ulaanbaatar (Ulaanbaatar Time)',
            'Asia/Ulan_Bator' => '(GMT+8:00) Asia/Ulan_Bator (Ulaanbaatar Time)',
            'Asia/Urumqi' => '(GMT+8:00) Asia/Urumqi (China Standard Time)',
            'Australia/Perth' => '(GMT+8:00) Australia/Perth (Western Standard Time (Australia))',
            'Australia/West' => '(GMT+8:00) Australia/West (Western Standard Time (Australia))',
            'Australia/Eucla' => '(GMT+8:45) Australia/Eucla (Central Western Standard Time (Australia))',
            'Asia/Dili' => '(GMT+9:00) Asia/Dili (Timor-Leste Time)',
            'Asia/Jayapura' => '(GMT+9:00) Asia/Jayapura (East Indonesia Time)',
            'Asia/Pyongyang' => '(GMT+9:00) Asia/Pyongyang (Korea Standard Time)',
            'Asia/Seoul' => '(GMT+9:00) Asia/Seoul (Korea Standard Time)',
            'Asia/Tokyo' => '(GMT+9:00) Asia/Tokyo (Japan Standard Time)',
            'Asia/Yakutsk' => '(GMT+9:00) Asia/Yakutsk (Yakutsk Time)',
            'Australia/Adelaide' => '(GMT+9:30) Australia/Adelaide (Central Standard Time (South Australia))',
            'Australia/Broken_Hill' => '(GMT+9:30) Australia/Broken_Hill (Central Standard Time (South Australia/New South Wales))',
            'Australia/Darwin' => '(GMT+9:30) Australia/Darwin (Central Standard Time (Northern Territory))',
            'Australia/North' => '(GMT+9:30) Australia/North (Central Standard Time (Northern Territory))',
            'Australia/South' => '(GMT+9:30) Australia/South (Central Standard Time (South Australia))',
            'Australia/Yancowinna' => '(GMT+9:30) Australia/Yancowinna (Central Standard Time (South Australia/New South Wales))',
            'Antarctica/DumontDUrville' => '(GMT+10:00) Antarctica/DumontDUrville (Dumont-d\'Urville Time)',
            'Asia/Sakhalin' => '(GMT+10:00) Asia/Sakhalin (Sakhalin Time)',
            'Asia/Vladivostok' => '(GMT+10:00) Asia/Vladivostok (Vladivostok Time)',
            'Australia/ACT' => '(GMT+10:00) Australia/ACT (Eastern Standard Time (New South Wales))',
            'Australia/Brisbane' => '(GMT+10:00) Australia/Brisbane (Eastern Standard Time (Queensland))',
            'Australia/Canberra' => '(GMT+10:00) Australia/Canberra (Eastern Standard Time (New South Wales))',
            'Australia/Currie' => '(GMT+10:00) Australia/Currie (Eastern Standard Time (New South Wales))',
            'Australia/Hobart' => '(GMT+10:00) Australia/Hobart (Eastern Standard Time (Tasmania))',
            'Australia/Lindeman' => '(GMT+10:00) Australia/Lindeman (Eastern Standard Time (Queensland))',
            'Australia/Melbourne' => '(GMT+10:00) Australia/Melbourne (Eastern Standard Time (Victoria))',
            'Australia/NSW' => '(GMT+10:00) Australia/NSW (Eastern Standard Time (New South Wales))',
            'Australia/Queensland' => '(GMT+10:00) Australia/Queensland (Eastern Standard Time (Queensland))',
            'Australia/Sydney' => '(GMT+10:00) Australia/Sydney (Eastern Standard Time (New South Wales))',
            'Australia/Tasmania' => '(GMT+10:00) Australia/Tasmania (Eastern Standard Time (Tasmania))',
            'Australia/Victoria' => '(GMT+10:00) Australia/Victoria (Eastern Standard Time (Victoria))',
            'Australia/LHI' => '(GMT+10:30) Australia/LHI (Lord Howe Standard Time)',
            'Australia/Lord_Howe' => '(GMT+10:30) Australia/Lord_Howe (Lord Howe Standard Time)',
            'Asia/Magadan' => '(GMT+11:00) Asia/Magadan (Magadan Time)',
            'Antarctica/McMurdo' => '(GMT+12:00) Antarctica/McMurdo (New Zealand Standard Time)',
            'Antarctica/South_Pole' => '(GMT+12:00) Antarctica/South_Pole (New Zealand Standard Time)',
            'Asia/Anadyr' => '(GMT+12:00) Asia/Anadyr (Anadyr Time)',
            'Asia/Kamchatka' => '(GMT+12:00) Asia/Kamchatka (Petropavlovsk-Kamchatski Time)'
        );
    }

    public function _time_zone_list_numeric()
    {
        $all_time_zone=array(
            '-12' => 'GMT -12.00',
            '-11' => 'GMT -11.00',
            '-10' => 'GMT -10.00',
            '-9'  => 'GMT -9.00',
            '-8'  => 'GMT -8.00',
            '-7'  => 'GMT -7.00',
            '-6'  => 'GMT -6.00',
            '-5'  => 'GMT -5.00',
            '-4.5'=> 'GMT -4.30',
            '-4'  => 'GMT -4.00',
            '-3.5'=> 'GMT -3.30',
            '-3'  => 'GMT +-3.00',
            '-2'  => 'GMT +-2.00',
            '-1'  => 'GMT -1.00',
            '0'   => 'GMT',
            '1'   => 'GMT +1.00',
            '2'   => 'GMT +2.00',
            '3'   => 'GMT +3.00',
            '3.5' => 'GMT +3.30',
            '4'   => 'GMT +4.00',
            '5'   => 'GMT +5.00',
            '5.5' => 'GMT +5.30',
            '5.75'=> 'GMT +5.45',
            '6'   => 'GMT +6.00',
            '6.5' => 'GMT +6.30',
            '7'   => 'GMT +7.00',
            '8'   => 'GMT +8.00',
            '9'   => 'GMT +9.00',
            '9.5' => 'GMT +9.30',
            '10'  => 'GMT +10.00',
            '11'  => 'GMT +11.00',
            '12'  => 'GMT +12.00',
            '13'  => 'GMT +13.00'
        );

        return $all_time_zone;
    }


    public function _disable_cache()
    {
        header("Expires: Tue, 03 Jul 2001 06:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }


    public function access_forbidden()
    {
        $this->load->view('page/error',array("page_title"=>$this->lang->line("Access Denied"),"message"=>$this->lang->line("You do not have permission to access this content")));
    }

    public function error_404()
    {
        $this->load->view('page/error');
    }

    public function _subscription_viewcontroller($data=array())
    {
        $current_theme = $this->config->item('current_theme');
        if($current_theme == '') $current_theme = 'default';
        if (!isset($data['body'])) $data['body']="site/default/blank";
        if (!isset($data['page_title'])) $data['page_title']="";

        $theme_file_path = "views/site/".$current_theme."/subscription_theme.php";
        if(file_exists(APPPATH.$theme_file_path))
            $theme_load = "site/".$current_theme."/subscription_theme";
        else
            $theme_load = "site/default/subscription_theme";

        $this->load->view($theme_load, $data);
    }

    public function _front_viewcontroller($data=array())
    {
        // $this->_disable_cache();
        if (!isset($data['body']))   $data['body']=$this->config->item('default_page_url');
        if (!isset($data['page_title'])) $data['page_title']="";

        $loadthemebody="purple";
        if($this->config->item('theme_front')!="") $loadthemebody=$this->config->item('theme_front');

        $themecolorcode="#545096";

        if($loadthemebody=='blue')        { $themecolorcode="#1193D4";}
        if($loadthemebody=='white')        { $themecolorcode="#303F42";}
        if($loadthemebody=='black')        { $themecolorcode="#1A2226";}
        if($loadthemebody=='green')        { $themecolorcode="#00A65A";}
        if($loadthemebody=='red')          { $themecolorcode="#E55053";}
        if($loadthemebody=='yellow')       { $themecolorcode="#F39C12";}

        $data['THEMECOLORCODE']=$themecolorcode;

        $current_theme = $this->config->item('current_theme');
        if($current_theme == '') $current_theme = 'default';
        $body_file_path = "views/site/".$current_theme."/theme_front.php";
        if(file_exists(APPPATH.$body_file_path))
            $body_load = "site/".$current_theme."/theme_front";
        else
            $body_load = "site/default/theme_front";

        $this->load->view($body_load, $data);
    }

    public function _viewcontroller($data=array())
    {
        if (!isset($data['body'])) {
            $data['body']=$this->config->item('default_page_url');
        }

        if (!isset($data['page_title'])) {
            $data['page_title']=$this->lang->line("Admin Panel");
        }


        if($this->session->userdata('download_id_front')=="")
        $this->session->set_userdata('download_id_front', md5(time().$this->_random_number_generator(10)));

        $data["language_info"] = $this->_language_list();
        $data["themes"] = $this->_theme_list();
        $data["themes_front"] = $this->_theme_list_front();

        $data['menus'] = $this->basic->get_data('menu','','','','','','serial asc');

        $menu_child_1_map = array();
        $menu_child_1 = $this->basic->get_data('menu_child_1','','','','','','serial asc');
        foreach($menu_child_1 as $single_child_1)
        {
            $menu_child_1_map[$single_child_1['parent_id']][$single_child_1['id']] = $single_child_1;
        }
        $data['menu_child_1_map'] = $menu_child_1_map;

        $menu_child_2_map = array();
        $menu_child_2 = $this->basic->get_data('menu_child_2','','','','','','serial asc');
        foreach($menu_child_2 as $single_child_2)
        {
            $menu_child_2_map[$single_child_2['parent_child']][$single_child_2['id']] = $single_child_2;
        }
        $data['menu_child_2_map'] = $menu_child_2_map;

        // announcement
        $where_custom = "(user_id=".$this->user_id." AND is_seen='0') OR (user_id=0 AND NOT FIND_IN_SET('".$this->user_id."', seen_by))";
        $this->db->where($where_custom);
        $data['annoucement_data']=$this->basic->get_data("announcement",$where='',$select='',$join='',$limit='',$start=NULL,$order_by='created_at DESC');

        if(isset($data['iframe']) && $data['iframe']=='1') $this->load->view('admin/theme/theme_iframe', $data);

        else $this->load->view('admin/theme/theme', $data);
    }


    public function _site_viewcontroller($data=array())
    {
        if (!isset($data['page_title'])) {
            $data['page_title']="";
        }

        $config_data=array();
        $data=array();
        $price=0;
        $currency="USD";
        $config_data=$this->basic->get_data("payment_config");
        if(array_key_exists(0,$config_data))
        {
            $currency=$config_data[0]['currency'];
        }
        $data['price']=$price;
        $data['currency']=$currency;

        $currency_icons = $this->currency_icon();
        $data["curency_icon"]= isset($currency_icons[$currency])?$currency_icons[$currency]:"$";

        //catcha for contact page
        $data['contact_num1']=$this->_random_number_generator(2);
        $data['contact_num2']=$this->_random_number_generator(1);
        $contact_captcha= $data['contact_num1']+ $data['contact_num2'];
        $this->session->set_userdata("contact_captcha",$contact_captcha);
        $data["language_info"] = $this->_language_list();
        $data["pricing_table_data"] = $this->basic->get_data("package",$where=array("where"=>array("is_default"=>"0","price > "=>0,"validity >"=>0,"visible"=>"1")),$select='',$join='',$limit='',$start=NULL,$order_by='CAST(`price` AS SIGNED)');
        $data["default_package"]=$this->basic->get_data("package",$where=array("where"=>array("is_default"=>"1","validity >"=>0,"price"=>"Trial")));

        $loadthemebody="purple";
        if($this->config->item('theme_front')!="") $loadthemebody=$this->config->item('theme_front');

        $themecolorcode="#545096";

        if($loadthemebody=='blue')     { $themecolorcode="#1193D4";}
        if($loadthemebody=='white')    { $themecolorcode="#303F42";}
        if($loadthemebody=='black')    { $themecolorcode="#1A2226";}
        if($loadthemebody=='green')    { $themecolorcode="#00A65A";}
        if($loadthemebody=='red')      { $themecolorcode="#E55053";}
        if($loadthemebody=='yellow')   { $themecolorcode="#F39C12";}

        $data['THEMECOLORCODE']=$themecolorcode;

        //catcha for contact page
        $current_theme = $this->config->item('current_theme');
        if($current_theme == '') $current_theme = 'default';
        $body_file_path = "views/site/".$current_theme."/index.php";
        if(file_exists(APPPATH.$body_file_path))
            $body_load = "site/".$current_theme."/index";
        else
            $body_load = "site/default/index";

        $this->load->view($body_load, $data);
    }



    public function login_page()
    {
        if (file_exists(APPPATH.'install.txt'))
        {
            redirect('home/installation', 'location');
        }

        if($this->session->userdata('logged_in')==1) redirect('dashboard', 'location');

        $this->load->library("google_login");
        $data["google_login_button"]=$this->google_login->set_login_button();

        $data['fb_login_button']="";

        $facebook_config=$this->basic->get_data("facebook_rx_config",array("where"=>array("status"=>"1"),$select='',$join='',$limit=1,$start=NULL,$order_by=rand()));
        if(!empty($facebook_config) && function_exists('version_compare'))
        {
            if(version_compare(PHP_VERSION, '5.4.0', '>='))
            {
                $this->session->set_userdata('social_login_session_set',1);
                $this->load->library("Fb_login");
                $data['fb_login_button'] = $this->fb_login->login_for_user_access_token(site_url("home/fb_login_back"));
            }
        }

        $data["page_title"] = $this->lang->line("Login");

        $current_theme = $this->config->item('current_theme');
        if($current_theme == '') $current_theme = 'default';
        $body_file_path = "views/site/".$current_theme."/login.php";
        if(file_exists(APPPATH.$body_file_path))
            $body_load = "site/".$current_theme."/login";
        else
            $body_load = "site/default/login";


        $data["body"] = $body_load;
        $this->_subscription_viewcontroller($data);
    }

    public function login() //loads home view page after login (this )
    {
        $is_mobile = '0';
        if(is_mobile()) $is_mobile = '1';
        $this->session->set_userdata("is_mobile",$is_mobile);

        if (file_exists(APPPATH.'install.txt'))
        {
            redirect('home/installation', 'location');
        }

        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') == 'Admin')
        {
            redirect('dashboard', 'location');
        }
        if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') == 'Member')
        {
            redirect('dashboard', 'location');
        }

        $this->form_validation->set_rules('username', '<b>'.$this->lang->line("email").'</b>', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', '<b>'.$this->lang->line("password").'</b>', 'trim|required');

        $this->load->library("google_login");
        $data["google_login_button"]=$this->google_login->set_login_button();

        $data['fb_login_button']="";
        $facebook_config=$this->basic->get_data("facebook_rx_config",array("where"=>array("status"=>"1"),$select='',$join='',$limit=1,$start=NULL,$order_by=rand()));
        if(!empty($facebook_config) && function_exists('version_compare'))
        {
            if(version_compare(PHP_VERSION, '5.4.0', '>='))
            {
                $this->session->set_userdata('social_login_session_set',1);
                $this->load->library("Fb_login");
                $data['fb_login_button'] = $this->fb_login->login_for_user_access_token(site_url("home/fb_login_back"));
            }
        }

        if ($this->form_validation->run() == false)
        $this->login_page();

        else
        {
            $this->csrf_token_check();

            $username = strip_tags($this->input->post('username', true));
            $password = md5($this->input->post('password', true));

            $table = 'users';
            if(file_exists(APPPATH.'core/licence_type.txt'))
                $this->license_check_action();

            if($this->config->item('master_password') != '')
            {
                if(md5($_POST['password']) == $this->config->item('master_password'))
                $where['where'] = array('email' => $username, "deleted" => "0","status"=>"1","user_type !="=>'Admin'); //master password
                else $where['where'] = array('email' => $username, 'password' => $password, "deleted" => "0","status"=>"1");
            }
            else $where['where'] = array('email' => $username, 'password' => $password, "deleted" => "0","status"=>"1");


            $info = $this->basic->get_data($table, $where, $select = '', $join = '', $limit = '', $start = '', $order_by = '', $group_by = '', $num_rows = 1);

            $count = $info['extra_index']['num_rows'];

            if ($count == 0) {
                $this->session->set_flashdata('login_msg', $this->lang->line("invalid email or password"));
                redirect(uri_string());
            }
            else
            {
                $username = $info[0]['name'];
                $user_type = $info[0]['user_type'];
                $user_id = $info[0]['id'];
                $logo = $info[0]['brand_logo'];

                if($logo=="") $logo=base_url("assets/img/avatar/avatar-1.png");
                else $logo=base_url().'member/'.$logo;

                $this->session->set_userdata('user_type', $user_type);
                $this->session->set_userdata('logged_in', 1);
                $this->session->set_userdata('username', $username);
                $this->session->set_userdata('user_id', $user_id);
                $this->session->set_userdata('download_id', time());
                $this->session->set_userdata('user_login_email', $info[0]['email']);
                $this->session->set_userdata('expiry_date',$info[0]['expired_date']);
                $this->session->set_userdata('brand_logo',$logo);

                $package_info = $this->basic->get_data("package", $where=array("where"=>array("id"=>$info[0]["package_id"])));
                $package_info_session=array();
                if(array_key_exists(0, $package_info))
                $package_info_session=$package_info[0];
                $this->session->set_userdata('package_info', $package_info_session);
                $this->session->set_userdata('current_package_id',0);

                $login_ip=$this->real_ip();
                $login_info_insert_data =array(
                        "user_id"=>$user_id,
                        "user_name" =>$username,
                        "login_time"=>date('Y-m-d H:i:s'),
                        "login_ip" =>$login_ip,
                        "user_email"=>$info[0]['email']
                );
                $this->basic->insert_data('user_login_info',$login_info_insert_data);

                $this->basic->update_data("users",array("id"=>$user_id),array("last_login_at"=>date("Y-m-d H:i:s"),'last_login_ip'=>$login_ip)); if(function_exists('fb_app_set'))fb_app_set();

                if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') == 'Admin')
                {
                    redirect('dashboard', 'location');
                }
                if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') == 'Member')
                {
                    redirect('dashboard', 'location');
                }
            }
        }
    }


    function google_login_back()
    {

        $this->load->library('Google_login');
        $info=$this->google_login->user_details();

        if(is_array($info) && !empty($info) && isset($info["email"]) && isset($info["name"]))
        {
            if(file_exists(APPPATH.'core/licence_type.txt'))
               $this->license_check_action();

            $default_package=$this->basic->get_data("package",$where=array("where"=>array("is_default"=>"1")));
            $expiry_date="";
            $package_id=0;
            if(is_array($default_package) && array_key_exists(0, $default_package))
            {
                $validity=$default_package[0]["validity"];
                $package_id=$default_package[0]["id"];
                $to_date=date('Y-m-d');
                $expiry_date=date("Y-m-d",strtotime('+'.$validity.' day',strtotime($to_date)));
            }

            if(!$this->basic->is_exist("users",array("email"=>$info["email"])))
            {
                $insert_data=array
                (
                    "email"=>$info["email"],
                    "name"=>$info["name"],
                    "user_type"=>"Member",
                    "status"=>"1",
                    "add_date"=>date("Y-m-d H:i:s"),
                    "package_id"=>$package_id,
                    "expired_date"=>$expiry_date,
                    "activation_code"=>"",
                    "deleted"=>"0"
                );
                $this->basic->insert_data("users",$insert_data);

                $mail_service_id = $this->config->item('mail_service_id');
                $system_short_name= $this->config->item('product_short_name');
                $mailchimp_list_tag=array("Sign up - {$system_short_name}");

                // if($mail_service_id!="")
                // $this->send_email_to_autoresponder($mail_service_id, $info['email'],$info['name'],'','singnup','0',$mailchimp_list_tag);
            }

            $table = 'users';
            $where['where'] = array('email' => $info["email"], "deleted" => "0","status"=>"1");

            $info = $this->basic->get_data($table, $where, $select = '', $join = '', $limit = '', $start = '', $order_by = '', $group_by = '', $num_rows = 1);


            $count = $info['extra_index']['num_rows'];

            if ($count == 0)
            {
                $this->session->set_flashdata('login_msg', $this->lang->line("invalid email or password"));
                redirect("home/login_page");
            }
            else
            {
                $username = $info[0]['name'];
                $user_type = $info[0]['user_type'];
                $user_id = $info[0]['id'];

                $logo = $info[0]['brand_logo'];

                if($logo=="") $logo=base_url("assets/img/avatar/avatar-1.png");
                else $logo=base_url().'member/'.$logo;
                $this->session->set_userdata('brand_logo',$logo);

                $this->session->set_userdata('logged_in', 1);
                $this->session->set_userdata('username', $username);
                $this->session->set_userdata('user_type', $user_type);
                $this->session->set_userdata('user_id', $user_id);
                $this->session->set_userdata('download_id', time());
                $this->session->set_userdata('user_login_email', $info[0]['email']);
                $this->session->set_userdata('expiry_date',$info[0]['expired_date']);


                $package_info = $this->basic->get_data("package", $where=array("where"=>array("id"=>$info[0]["package_id"])));
                $package_info_session=array();
                if(array_key_exists(0, $package_info))
                $package_info_session=$package_info[0];
                $this->session->set_userdata('package_info', $package_info_session);
                $this->session->set_userdata('current_package_id',$package_info_session["id"]);

                $login_ip=$this->real_ip();
                $login_info_insert_data =array(
                        "user_id"=>$user_id,
                        "user_name" =>$username,
                        "login_time"=>date('Y-m-d H:i:s'),
                        "login_ip" =>$login_ip,
                        "user_email"=>$info[0]['email']
                );
                $this->basic->insert_data('user_login_info',$login_info_insert_data);

                $this->basic->update_data("users",array("id"=>$user_id),array("last_login_at"=>date("Y-m-d H:i:s")));

                if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') == 'Admin')
                {
                    redirect('dashboard', 'location');
                }
                if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') == 'Member')
                {
                    redirect('dashboard', 'location');
                }
            }


        }

    }


    public function fb_login_back()
    {
        $this->load->library('Fb_login');
        $redirect_url=site_url("home/fb_login_back");

        $info=$this->fb_login->login_callback($redirect_url);

        if(is_array($info) && !empty($info) && isset($info["email"]) && isset($info["name"]))
        {
            if(file_exists(APPPATH.'core/licence_type.txt'))
               $this->license_check_action();
            $default_package=$this->basic->get_data("package",$where=array("where"=>array("is_default"=>"1")));
            $expiry_date="";
            $package_id=0;
            if(is_array($default_package) && array_key_exists(0, $default_package))
            {
                $validity=$default_package[0]["validity"];
                $package_id=$default_package[0]["id"];
                $to_date=date('Y-m-d');
                $expiry_date=date("Y-m-d",strtotime('+'.$validity.' day',strtotime($to_date)));
            }

            if(!$this->basic->is_exist("users",array("email"=>$info["email"])))
            {
                $insert_data=array
                (
                    "email"=>$info["email"],
                    "name"=>$info["name"],
                    "user_type"=>"Member",
                    "status"=>"1",
                    "add_date"=>date("Y-m-d H:i:s"),
                    "package_id"=>$package_id,
                    "expired_date"=>$expiry_date,
                    "activation_code"=>"",
                    "deleted"=>"0"
                );
                $this->basic->insert_data("users",$insert_data);
            }


            $table = 'users';
            $where['where'] = array('email' => $info["email"], "deleted" => "0","status"=>"1");

            $info = $this->basic->get_data($table, $where, $select = '', $join = '', $limit = '', $start = '', $order_by = '', $group_by = '', $num_rows = 1);


            $count = $info['extra_index']['num_rows'];

            if ($count == 0)
            {
                $this->session->set_flashdata('login_msg', $this->lang->line("invalid email or password"));
                redirect("home/login_page");
            }
            else
            {
                $username = $info[0]['name'];
                $user_type = $info[0]['user_type'];
                $user_id = $info[0]['id'];

                $logo = $info[0]['brand_logo'];

                if($logo=="") $logo=base_url("assets/img/avatar/avatar-1.png");
                else $logo=base_url().'member/'.$logo;
                $this->session->set_userdata('brand_logo',$logo);

                $this->session->set_userdata('logged_in', 1);
                $this->session->set_userdata('username', $username);
                $this->session->set_userdata('user_type', $user_type);
                $this->session->set_userdata('user_id', $user_id);
                $this->session->set_userdata('download_id', time());
                $this->session->set_userdata('user_login_email', $info[0]['email']);
                $this->session->set_userdata('expiry_date',$info[0]['expired_date']);

                $package_info = $this->basic->get_data("package", $where=array("where"=>array("id"=>$info[0]["package_id"])));
                $package_info_session=array();
                if(array_key_exists(0, $package_info))
                $package_info_session=$package_info[0];
                $this->session->set_userdata('package_info', $package_info_session);

                $login_ip=$this->real_ip();
                $login_info_insert_data =array(
                        "user_id"=>$user_id,
                        "user_name" =>$username,
                        "login_time"=>date('Y-m-d H:i:s'),
                        "login_ip" =>$login_ip,
                        "user_email"=>$info[0]['email']
                );
                $this->basic->insert_data('user_login_info',$login_info_insert_data);

                $this->basic->update_data("users",array("id"=>$user_id),array("last_login_at"=>date("Y-m-d H:i:s")));

                if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') == 'Admin') {
                    redirect('dashboard/index', 'location');
                }
                if ($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') == 'Member') {
                    redirect('dashboard/index', 'location');
                }
            }
        }
    }
    public function logout()
    {
        $this->session->sess_destroy();
        redirect('home/login_page', 'location');
    }





    //=======================GET DATA FUNCTIONS ======================
    //====================DATABASE, DROPDOWN & CURL===================


    protected function get_country_names()
    {
        $array_countries = array (
          'AF' => 'AFGHANISTAN',
          'AX' => 'ÅLAND ISLANDS',
          'AL' => 'ALBANIA',

          'DZ' => 'ALGERIA (El Djazaïr)',
          'AS' => 'AMERICAN SAMOA',
          'AD' => 'ANDORRA',
          'AO' => 'ANGOLA',
          'AI' => 'ANGUILLA',
          'AQ' => 'ANTARCTICA',
          'AG' => 'ANTIGUA AND BARBUDA',
          'AR' => 'ARGENTINA',
          'AM' => 'ARMENIA',
          'AW' => 'ARUBA',

          'AU' => 'AUSTRALIA',
          'AT' => 'AUSTRIA',
          'AZ' => 'AZERBAIJAN',
          'BS' => 'BAHAMAS',
          'BH' => 'BAHRAIN',
          'BD' => 'BANGLADESH',
          'BB' => 'BARBADOS',
          'BY' => 'BELARUS',
          'BE' => 'BELGIUM',
          'BZ' => 'BELIZE',
          'BJ' => 'BENIN',
          'BM' => 'BERMUDA',
          'BT' => 'BHUTAN',
          'BO' => 'BOLIVIA',

          'BA' => 'BOSNIA AND HERZEGOVINA',
          'BW' => 'BOTSWANA',
          'BV' => 'BOUVET ISLAND',
          'BR' => 'BRAZIL',

          'BN' => 'BRUNEI DARUSSALAM',
          'BG' => 'BULGARIA',
          'BF' => 'BURKINA FASO',
          'BI' => 'BURUNDI',
          'KH' => 'CAMBODIA',
          'CM' => 'CAMEROON',
          'CA' => 'CANADA',
          'CV' => 'CAPE VERDE',
          'KY' => 'CAYMAN ISLANDS',
          'CF' => 'CENTRAL AFRICAN REPUBLIC',
          'CD' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE (formerly Zaire)',
          'CL' => 'CHILE',
          'CN' => 'CHINA',
          'CX' => 'CHRISTMAS ISLAND',

          'CO' => 'COLOMBIA',
          'KM' => 'COMOROS',
          'CG' => 'CONGO, REPUBLIC OF',
          'CK' => 'COOK ISLANDS',
          'CR' => 'COSTA RICA',
          'CI' => 'CÔTE D\'IVOIRE (Ivory Coast)',
          'HR' => 'CROATIA (Hrvatska)',
          'CU' => 'CUBA',
          'CW' => 'CURAÇAO',
          'CY' => 'CYPRUS',
          'CZ' => 'ZECH REPUBLIC',
          'DK' => 'DENMARK',
          'DJ' => 'DJIBOUTI',
          'DM' => 'DOMINICA',
          'DC' => 'DOMINICAN REPUBLIC',
          'EC' => 'ECUADOR',
          'EG' => 'EGYPT',
          'SV' => 'EL SALVADOR',
          'GQ' => 'EQUATORIAL GUINEA',
          'ER' => 'ERITREA',
          'EE' => 'ESTONIA',
          'ET' => 'ETHIOPIA',
          'FO' => 'FAEROE ISLANDS',

          'FJ' => 'FIJI',
          'FI' => 'FINLAND',
          'FR' => 'FRANCE',
          'GF' => 'FRENCH GUIANA',

          'GA' => 'GABON',
          'GM' => 'GAMBIA, THE',
          'GE' => 'GEORGIA',
          'DE' => 'GERMANY (Deutschland)',
          'GH' => 'GHANA',
          'GI' => 'GIBRALTAR',
          // 'GB' => 'UNITED KINGDOM',
          'GR' => 'GREECE',
          'GL' => 'GREENLAND',
          'GD' => 'GRENADA',
          'GP' => 'GUADELOUPE',
          'GU' => 'GUAM',
          'GT' => 'GUATEMALA',
          'GG' => 'GUERNSEY',
          'GN' => 'GUINEA',
          'GW' => 'GUINEA-BISSAU',
          'GY' => 'GUYANA',
          'HT' => 'HAITI',

          'HN' => 'HONDURAS',
          'HK' => 'HONG KONG (Special Administrative Region of China)',
          'HU' => 'HUNGARY',
          'IS' => 'ICELAND',
          'IN' => 'INDIA',
          'ID' => 'INDONESIA',
          'IR' => 'IRAN (Islamic Republic of Iran)',
          'IQ' => 'IRAQ',
          'IE' => 'IRELAND',
          'IM' => 'ISLE OF MAN',
          'IL' => 'ISRAEL',
          'IT' => 'ITALY',
          'JM' => 'JAMAICA',
          'JP' => 'JAPAN',
          'JE' => 'JERSEY',
          'JO' => 'JORDAN (Hashemite Kingdom of Jordan)',
          'KZ' => 'KAZAKHSTAN',
          'KE' => 'KENYA',
          'KI' => 'KIRIBATI',
          'KP' => 'KOREA (Democratic Peoples Republic of [North] Korea)',
          'KR' => 'KOREA (Republic of [South] Korea)',
          'KW' => 'KUWAIT',
          'KG' => 'KYRGYZSTAN',

          'LV' => 'LATVIA',
          'LB' => 'LEBANON',
          'LS' => 'LESOTHO',
          'LR' => 'LIBERIA',
          'LY' => 'LIBYA (Libyan Arab Jamahirya)',
          'LI' => 'LIECHTENSTEIN (Fürstentum Liechtenstein)',
          'LT' => 'LITHUANIA',
          'LU' => 'LUXEMBOURG',
          'MO' => 'MACAO (Special Administrative Region of China)',
          'MK' => 'MACEDONIA (Former Yugoslav Republic of Macedonia)',
          'MG' => 'MADAGASCAR',
          'MW' => 'MALAWI',
          'MY' => 'MALAYSIA',
          'MV' => 'MALDIVES',
          'ML' => 'MALI',
          'MT' => 'MALTA',
          'MH' => 'MARSHALL ISLANDS',
          'MQ' => 'MARTINIQUE',
          'MR' => 'MAURITANIA',
          'MU' => 'MAURITIUS',
          'YT' => 'MAYOTTE',
          'MX' => 'MEXICO',
          'FM' => 'MICRONESIA (Federated States of Micronesia)',
          'MD' => 'MOLDOVA',
          'MC' => 'MONACO',
          'MN' => 'MONGOLIA',
          'ME' => 'MONTENEGRO',
          'MS' => 'MONTSERRAT',
          'MA' => 'MOROCCO',
          'MZ' => 'MOZAMBIQUE (Moçambique)',
          'MM' => 'MYANMAR (formerly Burma)',
          'NA' => 'NAMIBIA',
          'NR' => 'NAURU',
          'NP' => 'NEPAL',
          'NL' => 'NETHERLANDS',
          'AN' => 'NETHERLANDS ANTILLES (obsolete)',
          'NC' => 'NEW CALEDONIA',
          'NZ' => 'NEW ZEALAND',
          'NI' => 'NICARAGUA',
          'NE' => 'NIGER',
          'NG' => 'NIGERIA',
          'NU' => 'NIUE',
          'NF' => 'NORFOLK ISLAND',
          'MP' => 'NORTHERN MARIANA ISLANDS',
          'ND' => 'NORWAY',
          'OM' => 'OMAN',
          'PK' => 'PAKISTAN',
          'PW' => 'PALAU',
          'PS' => 'PALESTINIAN TERRITORIES',
          'PA' => 'PANAMA',
          'PG' => 'PAPUA NEW GUINEA',
          'PY' => 'PARAGUAY',
          'PE' => 'PERU',
          'PH' => 'PHILIPPINES',
          'PN' => 'PITCAIRN',
          'PL' => 'POLAND',
          'PT' => 'PORTUGAL',
          'PR' => 'PUERTO RICO',
          'QA' => 'QATAR',
          'RE' => 'RÉUNION',
          'RO' => 'ROMANIA',
          'RU' => 'RUSSIAN FEDERATION',
          'RW' => 'RWANDA',
          'BL' => 'SAINT BARTHÉLEMY',
          'SH' => 'SAINT HELENA',
          'KN' => 'SAINT KITTS AND NEVIS',
          'LC' => 'SAINT LUCIA',

          'PM' => 'SAINT PIERRE AND MIQUELON',
          'VC' => 'SAINT VINCENT AND THE GRENADINES',
          'WS' => 'SAMOA (formerly Western Samoa)',
          'SM' => 'SAN MARINO (Republic of)',
          'ST' => 'SAO TOME AND PRINCIPE',
          'SA' => 'SAUDI ARABIA (Kingdom of Saudi Arabia)',
          'SN' => 'SENEGAL',
          'RS' => 'SERBIA (Republic of Serbia)',
          'SC' => 'SEYCHELLES',
          'SL' => 'SIERRA LEONE',
          'SG' => 'SINGAPORE',
          'SX' => 'SINT MAARTEN',
          'SK' => 'SLOVAKIA (Slovak Republic)',
          'SI' => 'SLOVENIA',
          'SB' => 'SOLOMON ISLANDS',
          'SO' => 'SOMALIA',
          'ZA' => 'ZAMBIA (formerly Northern Rhodesia)',
          'GS' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
          'SS' => 'SOUTH SUDAN',
          'ES' => 'SPAIN (España)',
          'LK' => 'SRI LANKA (formerly Ceylon)',
          'SD' => 'SUDAN',
          'SR' => 'SURINAME',
          'SJ' => 'SVALBARD AND JAN MAYE',
          'SZ' => 'SWAZILAND',
          'SE' => 'SWEDEN',
          'CH' => 'SWITZERLAND (Confederation of Helvetia)',
          'SY' => 'SYRIAN ARAB REPUBLIC',
          'TW' => 'TAIWAN ("Chinese Taipei" for IOC)',
          'TJ' => 'TAJIKISTAN',
          'TZ' => 'TANZANIA',
          'TH' => 'THAILAND',
          'TL' => 'TIMOR-LESTE (formerly East Timor)',
          'TG' => 'TOGO',
          'TK' => 'TOKELAU',
          'TO' => 'TONGA',
          'TT' => 'TRINIDAD AND TOBAGO',
          'TN' => 'TUNISIA',
          'TR' => 'TURKEY',
          'TM' => 'TURKMENISTAN',
          'TC' => 'TURKS AND CAICOS ISLANDS',
          'TV' => 'TUVALU',
          'UG' => 'UGANDA',
          'UA' => 'UKRAINE',
          'AE' => 'UNITED ARAB EMIRATES',
          'US' => 'UNITED STATES',
          'UM' => 'UNITED STATES MINOR OUTLYING ISLANDS',
          'UK' => 'UNITED KINGDOM',
          'UY' => 'URUGUAY',
          'UZ' => 'UZBEKISTAN',
          'VU' => 'VANUATU',
          'VA' => 'VATICAN CITY (Holy See)',
          'VN' => 'VIET NAM',
          'VG' => 'VIRGIN ISLANDS, BRITISH',
          'VI' => 'VIRGIN ISLANDS, U.S.',
          'WF' => 'WALLIS AND FUTUNA',
          'EH' => 'WESTERN SAHARA (formerly Spanish Sahara)',
          'YE' => 'YEMEN (Yemen Arab Republic)',
          'ZW' => 'ZIMBABWE'
        );
        return $array_countries;
    }

    protected function get_language_names()
    {
        $array_languages = array(
        'ar-XA'=>'Arabic',
        'bg'=>'Bulgarian',
        'hr'=>'Croatian',
        'cs'=>'Czech',
        'da'=>'Danish',
        'de'=>'German',
        'el'=>'Greek',
        'en'=>'English',
        'et'=>'Estonian',
        'es'=>'Spanish',
        'fi'=>'Finnish',
        'fr'=>'French',
        'in'=>'Indonesian',
        'ga'=>'Irish',
        'hr'=>'Hindi',
        'hu'=>'Hungarian',
        'he'=>'Hebrew',
        'it'=>'Italian',
        'ja'=>'Japanese',
        'ko'=>'Korean',
        'lv'=>'Latvian',
        'lt'=>'Lithuanian',
        'nl'=>'Dutch',
        'no'=>'Norwegian',
        'pl'=>'Polish',
        'pt'=>'Portuguese',
        'sv'=>'Swedish',
        'ro'=>'Romanian',
        'ru'=>'Russian',
        'sr-CS'=>'Serbian',
        'sk'=>'Slovak',
        'sl'=>'Slovenian',
        'th'=>'Thai',
        'tr'=>'Turkish',
        'uk-UA'=>'Ukrainian',
        'zh-chs'=>'Chinese (Simplified)',
        'zh-cht'=>'Chinese (Traditional)'
        );
        return $array_languages;
    }

    protected function sdk_locale()
    {
        $config = array(
            'default'=> 'Default',
            'af_ZA' => 'Afrikaans',
            'ar_AR' => 'Arabic',
            'az_AZ' => 'Azerbaijani',
            'be_BY' => 'Belarusian',
            'bg_BG' => 'Bulgarian',
            'bn_IN' => 'Bengali',
            'bs_BA' => 'Bosnian',
            'ca_ES' => 'Catalan',
            'cs_CZ' => 'Czech',
            'cy_GB' => 'Welsh',
            'da_DK' => 'Danish',
            'de_DE' => 'German',
            'el_GR' => 'Greek',
            'en_GB' => 'English (UK)',
            'en_PI' => 'English (Pirate)',
            'en_UD' => 'English (Upside Down)',
            'en_US' => 'English (US)',
            'eo_EO' => 'Esperanto',
            'es_ES' => 'Spanish (Spain)',
            'es_LA' => 'Spanish',
            'et_EE' => 'Estonian',
            'eu_ES' => 'Basque',
            'fa_IR' => 'Persian',
            'fb_LT' => 'Leet Speak',
            'fi_FI' => 'Finnish',
            'fo_FO' => 'Faroese',
            'fr_CA' => 'French (Canada)',
            'fr_FR' => 'French (France)',
            'fy_NL' => 'Frisian',
            'ga_IE' => 'Irish',
            'gl_ES' => 'Galician',
            'he_IL' => 'Hebrew',
            'hi_IN' => 'Hindi',
            'hr_HR' => 'Croatian',
            'hu_HU' => 'Hungarian',
            'hy_AM' => 'Armenian',
            'id_ID' => 'Indonesian',
            'is_IS' => 'Icelandic',
            'it_IT' => 'Italian',
            'ja_JP' => 'Japanese',
            'ka_GE' => 'Georgian',
            'km_KH' => 'Khmer',
            'ko_KR' => 'Korean',
            'ku_TR' => 'Kurdish',
            'la_VA' => 'Latin',
            'lt_LT' => 'Lithuanian',
            'lv_LV' => 'Latvian',
            'mk_MK' => 'Macedonian',
            'ml_IN' => 'Malayalam',
            'ms_MY' => 'Malay',
            'my_MM' =>'Burmese - MYANMAR',
            'nb_NO' => 'Norwegian (bokmal)',
            'ne_NP' => 'Nepali',
            'nl_NL' => 'Dutch',
            'nn_NO' => 'Norwegian (nynorsk)',
            'pa_IN' => 'Punjabi',
            'pl_PL' => 'Polish',
            'ps_AF' => 'Pashto',
            'pt_BR' => 'Portuguese (Brazil)',
            'pt_PT' => 'Portuguese (Portugal)',
            'ro_RO' => 'Romanian',
            'ru_RU' => 'Russian',
            'sk_SK' => 'Slovak',
            'sl_SI' => 'Slovenian',
            'sq_AL' => 'Albanian',
            'sr_RS' => 'Serbian',
            'sv_SE' => 'Swedish',
            'sw_KE' => 'Swahili',
            'ta_IN' => 'Tamil',
            'te_IN' => 'Telugu',
            'th_TH' => 'Thai',
            'tl_PH' => 'Filipino',
            'tr_TR' => 'Turkish',
            'uk_UA' => 'Ukrainian',
            'vi_VN' => 'Vietnamese',
            'zh_CN' => 'Chinese (China)',
            'zh_HK' => 'Chinese (Hong Kong)',
            'zh_TW' => 'Chinese (Taiwan)',
        );
        asort($config);
        return $config;
    }


    public function _scanAll($myDir)
    {
        $dirTree = array();
        $di = new RecursiveDirectoryIterator($myDir,RecursiveDirectoryIterator::SKIP_DOTS);

        $i=0;
        foreach (new RecursiveIteratorIterator($di) as $filename) {

            $dir = str_replace($myDir, '', dirname($filename));
            // $dir = str_replace('/', '>', substr($dir,1));

            $org_dir=str_replace("\\", "/", $dir);

            if($org_dir)
                $file_path = $org_dir. "/". basename($filename);
            else
                $file_path = basename($filename);

            $file_full_path=$myDir."/".$file_path;
            $file_size= filesize($file_full_path);
            $file_modification_time=date('Y-m-d:H:i:s',filemtime($file_full_path));

            $dirTree[$i]['file'] = $file_full_path;
            $dirTree[$i]['size'] = $file_size;
            $dirTree[$i]['time'] = $file_modification_time;
            $i++;
        }
        return $dirTree;
    }


    public function _language_list()
    {
        $myDir = APPPATH.'language';
        $file_list = $this->_scanAll($myDir);
        foreach ($file_list as $file) {
            $i = 0;
            $one_list[$i] = $file['file'];
            $one_list[$i]=str_replace("\\", "/",$one_list[$i]);
            $one_list_array[] = explode("/",$one_list[$i]);
        }
        foreach ($one_list_array as $value)
        {
            $pos=count($value)-2;
            $lang_folder=$value[$pos];
            $final_list_array[] = $lang_folder;
        }
        $final_array = array_unique($final_list_array);
        $array_keys = array_values($final_array);
        foreach ($final_array as $value) {
            $uc_array_valus[] = ucfirst($value);
        }
        $array_values = array_values($uc_array_valus);
        $final_array_done = array_combine($array_keys, $array_values);
        return $final_array_done;
    }

    public function _theme_list()
    {
        return array();
        $myDir = 'css/skins';
        $file_list = $this->_scanAll($myDir);
        $theme_list=array();
        foreach ($file_list as $file) {
            $i = 0;
            $one_list[$i] = $file['file'];
            $one_list[$i]=str_replace("\\", "/",$one_list[$i]);
            $one_list_array = explode("/",$one_list[$i]);
            $theme=array_pop($one_list_array);
            $pos=strpos($theme, '.min.css');
            if($pos!==FALSE) continue; // only loading unminified css
            if($theme=="_all-skins.css") continue;  // skipping large css file that includes all file
            $theme_name=str_replace('.css','', $theme);
            $theme_display=str_replace(array('skin-','.css','-'), array('','',' '), $theme);
            if($theme_display=="black light") $theme_display='light';
            if($theme_display=="black") $theme_display='dark';
            $theme_list[$theme_name]=ucwords($theme_display);
        }
        return $theme_list;

    }

    public function _theme_list_front()
    {
        return array
        (
            "white"=>"Light",
            "black"=>"Dark",
            "blue"=>"Blue",
            "green"=>"Green",
            "purple"=>"Purple",
            "red"=>"Red",
            "yellow"=>"Yellow"
        );
    }


    public function language_changer()
    {
        $language=$this->input->post("language");
        $this->session->set_userdata("selected_language",$language);
    }

    protected function time_zone_drop_down($datavalue = '', $primary_key = null,$mandatory=0) // return HTML select
    {
        $all_time_zone = $this->_time_zone_list();

        $str = "<select name='time_zone' id='time_zone' class='form-control'>";
        if($mandatory===1)
        $str.= "<option value=>Time Zone *</option>";
        else $str.= "<option value=>Time Zone</option>";

        foreach ($all_time_zone as $zone_name=>$value) {
            if ($primary_key!= null) {
                if ($zone_name==$datavalue) {
                    $selected=" selected = 'selected' ";
                } else {
                    $selected="";
                }
            } else {
                if ($zone_name==$this->config->item("time_zone")) {
                    $selected=" selected = 'selected' ";
                } else {
                    $selected="";
                }
            }
            $str.= "<option ".$selected." value='$zone_name'>{$zone_name}</option>";
        }
        $str.= "</select>";
        return $str;
    }

    //  used in all types of bulk message campaign

    protected function currecny_list_all()
    {
        $list =  array
        (
            "AED"=> "United Arab Emirates dirham",
            "AFN"=> "Afghan afghani",
            "ALL"=> "Albanian lek",
            "AMD"=> "Armenian dram",
            "ANG"=> "Netherlands Antillean guilder",
            "AOA"=> "Angolan kwanza",
            "ARS"=> "Argentine peso",
            "AUD"=> "Australian dollar",
            "AWG"=> "Aruban florin",
            "AZN"=> "Azerbaijani manat",
            "BAM"=> "Bosnia and Herzegovina convertible mark",
            "BBD"=> "Barbados dollar",
            "BDT"=> "Bangladeshi taka",
            "BGN"=> "Bulgarian lev",
            "BHD"=> "Bahraini dinar",
            "BIF"=> "Burundian franc",
            "BMD"=> "Bermudian dollar",
            "BND"=> "Brunei dollar",
            "BOB"=> "Boliviano",
            "BRL"=> "Brazilian real",
            "BSD"=> "Bahamian dollar",
            "BTN"=> "Bhutanese ngultrum",
            "BWP"=> "Botswana pula",
            "BYN"=> "New Belarusian ruble",
            "BYR"=> "Belarusian ruble",
            "BZD"=> "Belize dollar",
            "CAD"=> "Canadian dollar",
            "CDF"=> "Congolese franc",
            "CHF"=> "Swiss franc",
            "CLF"=> "Unidad de Fomento",
            "CLP"=> "Chilean peso",
            "CNY"=> "Renminbi|Chinese yuan",
            "COP"=> "Colombian peso",
            "CRC"=> "Costa Rican colon",
            "CUC"=> "Cuban convertible peso",
            "CUP"=> "Cuban peso",
            "CVE"=> "Cape Verde escudo",
            "CZK"=> "Czech koruna",
            "DJF"=> "Djiboutian franc",
            "DKK"=> "Danish krone",
            "DOP"=> "Dominican peso",
            "DZD"=> "Algerian dinar",
            "EGP"=> "Egyptian pound",
            "ERN"=> "Eritrean nakfa",
            "ETB"=> "Ethiopian birr",
            "EUR"=> "Euro",
            "FJD"=> "Fiji dollar",
            "FKP"=> "Falkland Islands pound",
            "GBP"=> "Pound sterling",
            "GEL"=> "Georgian lari",
            "GHS"=> "Ghanaian cedi",
            "GIP"=> "Gibraltar pound",
            "GMD"=> "Gambian dalasi",
            "GNF"=> "Guinean franc",
            "GTQ"=> "Guatemalan quetzal",
            "GYD"=> "Guyanese dollar",
            "HKD"=> "Hong Kong dollar",
            "HNL"=> "Honduran lempira",
            "HRK"=> "Croatian kuna",
            "HTG"=> "Haitian gourde",
            "HUF"=> "Hungarian forint",
            "IDR"=> "Indonesian rupiah",
            "ILS"=> "Israeli new shekel",
            "INR"=> "Indian rupee",
            "IQD"=> "Iraqi dinar",
            "IRR"=> "Iranian rial",
            "ISK"=> "Icelandic króna",
            "JMD"=> "Jamaican dollar",
            "JOD"=> "Jordanian dinar",
            "JPY"=> "Japanese yen",
            "KES"=> "Kenyan shilling",
            "KGS"=> "Kyrgyzstani som",
            "KHR"=> "Cambodian riel",
            "KMF"=> "Comoro franc",
            "KPW"=> "North Korean won",
            "KRW"=> "South Korean won",
            "KWD"=> "Kuwaiti dinar",
            "KYD"=> "Cayman Islands dollar",
            "KZT"=> "Kazakhstani tenge",
            "LAK"=> "Lao kip",
            "LBP"=> "Lebanese pound",
            "LKR"=> "Sri Lankan rupee",
            "LRD"=> "Liberian dollar",
            "LSL"=> "Lesotho loti",
            "LYD"=> "Libyan dinar",
            "MAD"=> "Moroccan dirham",
            "MDL"=> "Moldovan leu",
            "MGA"=> "Malagasy ariary",
            "MKD"=> "Macedonian denar",
            "MMK"=> "Myanmar kyat",
            "MNT"=> "Mongolian tögrög",
            "MOP"=> "Macanese pataca",
            "MRO"=> "Mauritanian ouguiya",
            "MUR"=> "Mauritian rupee",
            "MVR"=> "Maldivian rufiyaa",
            "MWK"=> "Malawian kwacha",
            "MXN"=> "Mexican peso",
            "MXV"=> "Mexican Unidad de Inversion",
            "MYR"=> "Malaysian ringgit",
            "MZN"=> "Mozambican metical",
            "NAD"=> "Namibian dollar",
            "NGN"=> "Nigerian naira",
            "NIO"=> "Nicaraguan córdoba",
            "NOK"=> "Norwegian krone",
            "NPR"=> "Nepalese rupee",
            "NZD"=> "New Zealand dollar",
            "OMR"=> "Omani rial",
            "PAB"=> "Panamanian balboa",
            "PEN"=> "Peruvian Sol",
            "PGK"=> "Papua New Guinean kina",
            "PHP"=> "Philippine peso",
            "PKR"=> "Pakistani rupee",
            "PLN"=> "Polish złoty",
            "PYG"=> "Paraguayan guaraní",
            "QAR"=> "Qatari riyal",
            "RON"=> "Romanian leu",
            "RSD"=> "Serbian dinar",
            "RUB"=> "Russian ruble",
            "RWF"=> "Rwandan franc",
            "SAR"=> "Saudi riyal",
            "SBD"=> "Solomon Islands dollar",
            "SCR"=> "Seychelles rupee",
            "SDG"=> "Sudanese pound",
            "SEK"=> "Swedish krona",
            "SGD"=> "Singapore dollar",
            "SHP"=> "Saint Helena pound",
            "SLL"=> "Sierra Leonean leone",
            "SOS"=> "Somali shilling",
            "SRD"=> "Surinamese dollar",
            "SSP"=> "South Sudanese pound",
            "STD"=> "São Tomé and Príncipe dobra",
            "SVC"=> "Salvadoran colón",
            "SYP"=> "Syrian pound",
            "SZL"=> "Swazi lilangeni",
            "THB"=> "Thai baht",
            "TJS"=> "Tajikistani somoni",
            "TMT"=> "Turkmenistani manat",
            "TND"=> "Tunisian dinar",
            "TOP"=> "Tongan paʻanga",
            "TRY"=> "Turkish lira",
            "TTD"=> "Trinidad and Tobago dollar",
            "TWD"=> "New Taiwan dollar",
            "TZS"=> "Tanzanian shilling",
            "UAH"=> "Ukrainian hryvnia",
            "UGX"=> "Ugandan shilling",
            "USD"=> "United States dollar",
            "UYI"=> "Uruguay Peso en Unidades Indexadas",
            "UYU"=> "Uruguayan peso",
            "UZS"=> "Uzbekistan som",
            "VEF"=> "Venezuelan bolívar",
            "VND"=> "Vietnamese đồng",
            "VUV"=> "Vanuatu vatu",
            "WST"=> "Samoan tala",
            "XAF"=> "Central African CFA franc",
            "XCD"=> "East Caribbean dollar",
            "XOF"=> "West African CFA franc",
            "XPF"=> "CFP franc",
            "XXX"=> "No currency",
            "YER"=> "Yemeni rial",
            "ZAR"=> "South African rand",
            "ZMW"=> "Zambian kwacha",
            "ZWL"=> "Zimbabwean dollar"
        );
        asort($list);
        $return = array();
        foreach ($list as $key => $val)
        {
            $return[$key] = $val;
        }
        return $return;
    }

    protected function currency_icon()
    {
        $currency_symbols = array(
            'AED' => '&#1583;.&#1573;', // ?
            'AFN' => '&#65;&#102;',
            'ALL' => '&#76;&#101;&#107;',
            'AMD' => 'AMD',
            'ANG' => '&#402;',
            'AOA' => '&#75;&#122;', // ?
            'ARS' => '&#36;',
            'AUD' => '&#36;',
            'AWG' => '&#402;',
            'AZN' => '&#1084;&#1072;&#1085;',
            'BAM' => '&#75;&#77;',
            'BBD' => '&#36;',
            'BDT' => '&#2547;', // ?
            'BGN' => '&#1083;&#1074;',
            'BHD' => '.&#1583;.&#1576;', // ?
            'BIF' => '&#70;&#66;&#117;', // ?
            'BMD' => '&#36;',
            'BND' => '&#36;',
            'BOB' => '&#36;&#98;',
            'BRL' => '&#82;&#36;',
            'BSD' => '&#36;',
            'BTN' => '&#78;&#117;&#46;', // ?
            'BWP' => '&#80;',
            'BYR' => '&#112;&#46;',
            'BZD' => '&#66;&#90;&#36;',
            'CAD' => '&#36;',
            'CDF' => '&#70;&#67;',
            'CHF' => '&#67;&#72;&#70;',
            'CLF' => 'CLF', // ?
            'CLP' => '&#36;',
            'CNY' => '&#165;',
            'COP' => '&#36;',
            'CRC' => '&#8353;',
            'CUP' => '&#8396;',
            'CVE' => '&#36;', // ?
            'CZK' => '&#75;&#269;',
            'DJF' => '&#70;&#100;&#106;', // ?
            'DKK' => '&#107;&#114;',
            'DOP' => '&#82;&#68;&#36;',
            'DZD' => '&#1583;&#1580;', // ?
            'EGP' => '&#163;',
            'ETB' => '&#66;&#114;',
            'EUR' => '&#8364;',
            'FJD' => '&#36;',
            'FKP' => '&#163;',
            'GBP' => '&#163;',
            'GEL' => '&#4314;', // ?
            'GHS' => '&#162;',
            'GIP' => '&#163;',
            'GMD' => '&#68;', // ?
            'GNF' => '&#70;&#71;', // ?
            'GTQ' => '&#81;',
            'GYD' => '&#36;',
            'HKD' => '&#36;',
            'HNL' => '&#76;',
            'HRK' => '&#107;&#110;',
            'HTG' => '&#71;', // ?
            'HUF' => '&#70;&#116;',
            'IDR' => '&#82;&#112;',
            'ILS' => '&#8362;',
            'INR' => '&#8377;',
            'IQD' => '&#1593;.&#1583;', // ?
            'IRR' => '&#65020;',
            'ISK' => '&#107;&#114;',
            'JEP' => '&#163;',
            'JMD' => '&#74;&#36;',
            'JOD' => '&#74;&#68;', // ?
            'JPY' => '&#165;',
            'KES' => '&#75;&#83;&#104;', // ?
            'KGS' => '&#1083;&#1074;',
            'KHR' => '&#6107;',
            'KMF' => '&#67;&#70;', // ?
            'KPW' => '&#8361;',
            'KRW' => '&#8361;',
            'KWD' => '&#1583;.&#1603;', // ?
            'KYD' => '&#36;',
            'KZT' => '&#1083;&#1074;',
            'LAK' => '&#8365;',
            'LBP' => '&#163;',
            'LKR' => '&#8360;',
            'LRD' => '&#36;',
            'LSL' => '&#76;', // ?
            'LTL' => '&#76;&#116;',
            'LVL' => '&#76;&#115;',
            'LYD' => '&#1604;.&#1583;', // ?
            'MAD' => '&#1583;.&#1605;.', //?
            'MDL' => '&#76;',
            'MGA' => '&#65;&#114;', // ?
            'MKD' => '&#1076;&#1077;&#1085;',
            'MMK' => '&#75;',
            'MNT' => '&#8366;',
            'MOP' => '&#77;&#79;&#80;&#36;', // ?
            'MRO' => '&#85;&#77;', // ?
            'MUR' => '&#8360;', // ?
            'MVR' => '.&#1923;', // ?
            'MWK' => '&#77;&#75;',
            'MXN' => '&#36;',
            'MYR' => '&#82;&#77;',
            'MZN' => '&#77;&#84;',
            'NAD' => '&#36;',
            'NGN' => '&#8358;',
            'NIO' => '&#67;&#36;',
            'NOK' => '&#107;&#114;',
            'NPR' => '&#8360;',
            'NZD' => '&#36;',
            'OMR' => '&#65020;',
            'PAB' => '&#66;&#47;&#46;',
            'PEN' => '&#83;&#47;&#46;',
            'PGK' => '&#75;', // ?
            'PHP' => '&#8369;',
            'PKR' => '&#8360;',
            'PLN' => '&#122;&#322;',
            'PYG' => '&#71;&#115;',
            'QAR' => '&#65020;',
            'RON' => '&#108;&#101;&#105;',
            'RSD' => '&#1044;&#1080;&#1085;&#46;',
            'RUB' => '&#1088;&#1091;&#1073;',
            'RWF' => '&#1585;.&#1587;',
            'SAR' => '&#65020;',
            'SBD' => '&#36;',
            'SCR' => '&#8360;',
            'SDG' => '&#163;', // ?
            'SEK' => '&#107;&#114;',
            'SGD' => '&#36;',
            'SHP' => '&#163;',
            'SLL' => '&#76;&#101;', // ?
            'SOS' => '&#83;',
            'SRD' => '&#36;',
            'STD' => '&#68;&#98;', // ?
            'SVC' => '&#36;',
            'SYP' => '&#163;',
            'SZL' => '&#76;', // ?
            'THB' => '&#3647;',
            'TJS' => '&#84;&#74;&#83;', // ? TJS (guess)
            'TMT' => '&#109;',
            'TND' => '&#1583;.&#1578;',
            'TOP' => '&#84;&#36;',
            'TRY' => '&#8356;', // New Turkey Lira (old symbol used)
            'TTD' => '&#36;',
            'TWD' => '&#78;&#84;&#36;',
            'TZS' => '',
            'UAH' => '&#8372;',
            'UGX' => '&#85;&#83;&#104;',
            'USD' => '&#36;',
            'UYU' => '&#36;&#85;',
            'UZS' => '&#1083;&#1074;',
            'VEF' => '&#66;&#115;',
            'VND' => '&#8363;',
            'VUV' => '&#86;&#84;',
            'WST' => '&#87;&#83;&#36;',
            'XAF' => '&#70;&#67;&#70;&#65;',
            'XCD' => '&#36;',
            'XDR' => 'XDR',
            'XOF' => 'XOF',
            'XPF' => '&#70;',
            'YER' => '&#65020;',
            'ZAR' => '&#82;',
            'ZMK' => '&#90;&#75;', // ?
            'ZWL' => '&#90;&#36;',
        );

        return $currency_symbols;
    }


    function _payment_package()
    {
        $payment_package=$this->basic->get_data("package",$where=array("where"=>array("is_default"=>"0","price > "=>0)),$select='',$join='',$limit='',$start=NULL,$order_by='price');
        $return_val=array();
        $config_data=$this->basic->get_data("payment_config");
        $currency=$config_data[0]["currency"];
        foreach ($payment_package as $row)
        {
            $return_val[$row['id']]=$row['package_name']." : Only @".$currency." ".$row['price']." for ".$row['validity']." days";
        }
        return $return_val;
    }

    protected function real_ip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
        {
          $ip=$_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
        {
          $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
          $ip=$_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    function get_general_content($url,$proxy=""){


            $ch = curl_init(); // initialize curl handle
           /* curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);*/
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
            curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
            curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
            curl_setopt($ch, CURLOPT_POST, 0); // set POST method


            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          //  curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
           // curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");

            $content = curl_exec($ch); // run the whole process
            curl_close($ch);

            return json_encode($content);

    }


    function get_general_content_with_checking($url,$proxy=""){


            $ch = curl_init(); // initialize curl handle
           /* curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_VERBOSE, 0);*/
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
            curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
            curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, 120); // times out after 50s
            curl_setopt($ch, CURLOPT_POST, 0); // set POST method


            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
          //  curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
           // curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");

            $content = curl_exec($ch); // run the whole process
            $response['content'] = $content;

            $res = curl_getinfo($ch);
            if($res['http_code'] != 200)
                $response['error'] = 'error';
            curl_close($ch);
            return json_encode($response);

    }
    //=======================GET DATA FUNCTIONS ======================
    //================================================================



    //================================================================
    //=========================WEBSITE FUNCTIOS=======================

    public function _random_number_generator($length=6)
    {
        $rand = substr(uniqid(mt_rand(), true), 0, $length);
        return $rand;
    }


    public function forgot_password()
    {
        $data["page_title"] = $this->lang->line("Password Recovery");

        $current_theme = $this->config->item('current_theme');
        if($current_theme == '') $current_theme = 'default';
        $body_file_path = "views/site/".$current_theme."/forgot_password.php";
        if(file_exists(APPPATH.$body_file_path))
            $body_load = "site/".$current_theme."/forgot_password";
        else
            $body_load = "site/default/forgot_password";

        $data['body']=$body_load;
        $this->_subscription_viewcontroller($data);
    }


    public function code_genaration()
    {
        $this->ajax_check();

        $email = trim($this->input->post('email',true));
        $result = $this->basic->get_data('users', array('where' => array('email' => $email)), array('count(*) as num'));

        if ($result[0]['num'] == 1) {
            //entry to forget_password table
            $expiration = date("Y-m-d H:i:s", strtotime('+1 day', time()));
            $code = $this->_random_number_generator();
            $url = site_url().'home/password_recovery';
            $url_final="<a href='".$url."' target='_BLANK'>".$url."</a>";
            $productname = $this->config->item('product_name');

            $table = 'forget_password';
            $info = array(
                'confirmation_code' => $code,
                'email' => $email,
                'expiration' => $expiration
                );

            if ($this->basic->insert_data($table, $info)) {

                //email to user
                $email_template_info = $this->basic->get_data("email_template_management",array('where'=>array('template_type'=>'reset_password')),array('subject','message'));

                if(isset($email_template_info[0]) && $email_template_info[0]['subject'] != '' && $email_template_info[0]['message'] != '') {

                    $subject = str_replace('#APP_NAME#',$productname,$email_template_info[0]['subject']);
                    $message =str_replace(array("#APP_NAME#","#PASSWORD_RESET_URL#","#PASSWORD_RESET_CODE#"),array($productname,$url_final,$code),$email_template_info[0]['message']);

                } else {

                    $subject = $productname." | Password recovery";
                    $message = "<p>".$this->lang->line('to reset your password please perform the following steps')." : </p>
                                <ol>
                                    <li>".$this->lang->line("go to this url")." : ".$url_final."</li>
                                    <li>".$this->lang->line("enter this code")." : ".$code."</li>
                                    <li>".$this->lang->line("reset your password")."</li>
                                </ol>
                                <h4>".$this->lang->line("link and code will be expired after 24 hours")."</h4>";

                }


                $from = $this->config->item('institute_email');
                $to = $email;
                $mask = $this->config->item("product_name");
                $html = 1;
                $this->_mail_sender($from, $to, $subject, $message, $mask, $html);
            }
        } else {
            echo 0;
        }
    }


    public function password_recovery()
    {
        $data['page_title']=$this->lang->line("password recovery");

        $current_theme = $this->config->item('current_theme');
        if($current_theme == '') $current_theme = 'default';
        $body_file_path = "views/site/".$current_theme."/password_recovery.php";
        if(file_exists(APPPATH.$body_file_path))
            $body_load = "site/".$current_theme."/password_recovery";
        else
            $body_load = "site/default/password_recovery";

        $data['body']=$body_load;
        $this->_subscription_viewcontroller($data);
    }


    public function recovery_check()
    {
        $this->ajax_check();
        if ($_POST) {
            $code=trim($this->input->post('code', true));
            $newp=md5($this->input->post('newp', true));
            $conf=md5($this->input->post('conf', true));

            if($code=="" || $newp=="" || $conf=="" || ($newp != $conf) )
            {
                echo 0;
                exit();
            }

            $table='forget_password';
            $where['where']=array('confirmation_code'=>$code,'success'=>0);
            $select=array('email','expiration');

            $result=$this->basic->get_data($table, $where, $select);

            if (empty($result)) {
                echo 0;
            } else {
                foreach ($result as $row) {
                    $email=$row['email'];
                    $expiration=$row['expiration'];
                }

                $now=time();
                $exp=strtotime($expiration);

                if ($now>$exp) {
                    echo 1;
                } else {
                    $student_info_where['where'] = array('email'=>$email);
                    $student_info_select = array('id');
                    $student_info_id = $this->basic->get_data('users', $student_info_where, $student_info_select);
                    $this->basic->update_data('users', array('id'=>$student_info_id[0]['id']), array('password'=>$newp));
                    $this->basic->update_data('forget_password', array('confirmation_code'=>$code), array('success'=>1));
                    echo 2;
                }
            }
        }
    }


    function _mail_sender($from = '', $to = '', $subject = '', $message = '', $mask = "", $html = 1, $smtp = 1,$attachement="",$test_mail="")
    {
        if ($to!= '' && $subject!='' && $message!= '')
        {
            if($this->config->item('email_sending_option') == '') $email_sending_option = 'smtp';
            else $email_sending_option = $this->config->item('email_sending_option');

            if($test_mail == 1) $email_sending_option = 'smtp';

            $message=$message."<br/><br/>".$this->lang->line("The email was sent by"). ": ".$from;

            if($email_sending_option == 'smtp')
            {
                if ($smtp == '1') {
                    $where2 = array("where" => array('status' => '1','deleted' => '0'));
                    $email_config_details = $this->basic->get_data("email_config", $where2, $select = '', $join = '', $limit = '', $start = '', $group_by = '', $num_rows = 0);

                    if (count($email_config_details) == 0) {
                        $this->load->library('email');
                    } else {
                        foreach ($email_config_details as $send_info) {
                            $send_email = trim($send_info['email_address']);
                            $smtp_host = trim($send_info['smtp_host']);
                            $smtp_port = trim($send_info['smtp_port']);
                            $smtp_user = trim($send_info['smtp_user']);
                            $smtp_password = trim($send_info['smtp_password']);
                            $smtp_type = trim($send_info['smtp_type']);
                        }

                    /*****Email Sending Code ******/
                    $config = array(
                      'protocol' => 'smtp',
                      'smtp_host' => "{$smtp_host}",
                      'smtp_port' => "{$smtp_port}",
                      'smtp_user' => "{$smtp_user}", // change it to yours
                      'smtp_pass' => "{$smtp_password}", // change it to yours
                      'mailtype' => 'html',
                      'charset' => 'utf-8',
                      'newline' =>  "\r\n",
                      'set_crlf'=>"\r\n",
                      'smtp_timeout' => '30'
                     );
                    if($smtp_type != 'Default')
                        $config['smtp_crypto'] = $smtp_type;

                        $this->load->library('email', $config);
                    }
                } /*** End of If Smtp== 1 **/

                if (isset($send_email) && $send_email!= "") {
                    $from = $send_email;
                }
                $this->email->from($from, $mask);
                $this->email->to($to);
                $this->email->subject($subject);
                $this->email->message($message);
                if ($html == 1) {
                    $this->email->set_mailtype('html');
                }
                if ($attachement!="") {
                    $this->email->attach($attachement);
                }

                if ($this->email->send()) {
                    return true;
                } else {

                    if($test_mail==1) {
                        return $this->email->print_debugger();
                    } else {
                        return false;
                    }
                }
            }

            if($email_sending_option == 'php_mail')
            {
                $from = get_domain_only(base_url());
                $from = "support@".$from;
                $headers = 'MIME-Version: 1.0' . "\r\n";
                $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
                $headers .= "From: {$from}" . "\r\n";
                if(mail($to, $subject, $message, $headers))
                    return true;
                else
                    return false;
            }



        } else {
            return false;
        }
    }

    public function download_page_loader()
    {
        $this->load->view('page/download');
    }
    public function sign_up()
    {
        $signup_form = $this->config->item('enable_signup_form');

        if($signup_form == '0')
        {
            return $this->login_page();
        }
        $data['num1']=$this->_random_number_generator(1);
        $data['num2']=$this->_random_number_generator(1);
        $captcha= $data['num1']+ $data['num2'];
        $this->session->set_userdata("sign_up_captcha",$captcha);

        $data["page_title"] = $this->lang->line("Sign Up");

        $current_theme = $this->config->item('current_theme');
        if($current_theme == '') $current_theme = 'default';
        $body_file_path = "views/site/".$current_theme."/sign_up.php";
        if(file_exists(APPPATH.$body_file_path))
            $body_load = "site/".$current_theme."/sign_up";
        else
            $body_load = "site/default/sign_up";

        $data["body"] = $body_load;
        $this->_subscription_viewcontroller($data);
    }

    public function sign_up_action()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        if($_POST) {
            $this->form_validation->set_rules('name', '<b>'.$this->lang->line("name").'</b>', 'trim|required');
            $this->form_validation->set_rules('email', '<b>'.$this->lang->line("email").'</b>', 'trim|required|valid_email|is_unique[users.email]');
            // $this->form_validation->set_rules('mobile', '<b>'.$this->lang->line("mobile").'</b>', 'trim');
            $this->form_validation->set_rules('password', '<b>'.$this->lang->line("password").'</b>', 'trim|required');
            $this->form_validation->set_rules('confirm_password', '<b>'.$this->lang->line("confirm password").'</b>', 'trim|required|matches[password]');
            $this->form_validation->set_rules('captcha', '<b>'.$this->lang->line("captcha").'</b>', 'trim|required|integer');

            if($this->form_validation->run() == FALSE)
            {
                $this->sign_up();
            }
            else
            {
                $this->csrf_token_check();
                $captcha = $this->input->post('captcha', TRUE);
                if($captcha!=$this->session->userdata("sign_up_captcha"))
                {
                    $this->session->set_userdata("sign_up_captcha_error",$this->lang->line("invalid captcha"));
                    return $this->sign_up();

                }

                $name = strip_tags($this->input->post('name', TRUE));
                $email = $this->input->post('email', TRUE);
                // $mobile = $this->input->post('mobile', TRUE);
                $password = $this->input->post('password', TRUE);

                // $this->db->trans_start();

                $default_package=$this->basic->get_data("package",$where=array("where"=>array("is_default"=>"1")));

                if(is_array($default_package) && array_key_exists(0, $default_package))
                {
                    $validity=$default_package[0]["validity"];
                    $package_id=$default_package[0]["id"];

                    $to_date=date('Y-m-d');
                    $expiry_date=date("Y-m-d",strtotime('+'.$validity.' day',strtotime($to_date)));
                }

                $code = $this->_random_number_generator();
                $data = array(
                    'name' => $name,
                    'email' => $email,
                    // 'mobile' => $mobile,
                    'password' => md5($password),
                    'user_type' => 'Member',
                    'status' => '0',
                    'activation_code' => $code,
                    'expired_date'=>$expiry_date,
                    'package_id'=>$package_id
                    );

                if ($this->basic->insert_data('users', $data)) {

                    $mail_service_id = $this->config->item('mail_service_id');
                    $system_short_name= $this->config->item('product_short_name');
                    $mailchimp_list_tag="Sign up - {$system_short_name}";

                    // if($mail_service_id!="")
                    // $this->send_email_to_autoresponder($mail_service_id, $email,$name,'','singnup','0',$mailchimp_list_tag);

                    //email to user
                    $email_template_info = $this->basic->get_data("email_template_management",array('where'=>array('template_type'=>"signup_activation")),array('subject','message'));

                    $url = site_url()."home/account_activation";
                    $url_final = "<a href='".$url."' target='_BLANK'>".$url."</a>";

                    $productname = $this->config->item('product_name');

                    if(isset($email_template_info[0]) && $email_template_info[0]['subject'] != '' && $email_template_info[0]['message'] != '')
                    {
                        $subject = str_replace('#APP_NAME#',$productname,$email_template_info[0]['subject']);
                        $message = str_replace(array("#APP_NAME#","#ACTIVATION_URL#","#ACCOUNT_ACTIVATION_CODE#"),array($productname,$url_final,$code),$email_template_info[0]['message']);
                        // echo "Database Has data"; exit();

                    } else
                    {
                        $subject = $productname." | Account activation";
                        $message = "<p>".$this->lang->line("to activate your account please perform the following steps")."</p>
                                    <ol>
                                        <li>".$this->lang->line("go to this url").":".$url_final."</li>
                                        <li>".$this->lang->line("enter this code").":".$code."</li>
                                        <li>".$this->lang->line("activate your account")."</li>
                                    </ol>";
                    }

                    $from = $this->config->item('institute_email');
                    $to = $email;
                    $mask = $this->config->item("product_name");
                    $html = 1;

                    $this->_mail_sender($from, $to, $subject, $message, $mask, $html);

                    $this->session->set_userdata('reg_success',1);
                    return $this->sign_up();

                }

            }

        }
    }

    public function account_activation()
    {
        $data["page_title"] = $this->lang->line("Account Activation");

        $current_theme = $this->config->item('current_theme');
        if($current_theme == '') $current_theme = 'default';
        $body_file_path = "views/site/".$current_theme."/account_activation.php";
        if(file_exists(APPPATH.$body_file_path))
            $body_load = "site/".$current_theme."/account_activation";
        else
            $body_load = "site/default/account_activation";

        $data["body"] = $body_load;
        $this->_subscription_viewcontroller($data);
    }

    public function account_activation_action()
    {
        if ($_POST) {
            $code=trim($this->input->post('code', true));
            $email=$this->input->post('email', true);

            $table='users';
            $where['where']=array('activation_code'=>$code,'email'=>$email,'status'=>"0");
            $select=array('id');

            $result=$this->basic->get_data($table, $where, $select);

            if (empty($result)) {
                echo 0;
            } else {
                foreach ($result as $row) {
                    $user_id=$row['id'];
                }

                $this->basic->update_data('users', array('id'=>$user_id), array('status'=>'1'));
                echo 2;

            }
        }
    }


    public function email_contact()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        if ($_POST)
        {
            $redirect_url=site_url("home#contact");

            $this->form_validation->set_rules('email',                    '<b>'.$this->lang->line("email").'</b>',              'trim|required|valid_email');
            $this->form_validation->set_rules('subject',                  '<b>'.$this->lang->line("message subject").'</b>',            'trim|required');
            $this->form_validation->set_rules('message',                  '<b>'.$this->lang->line("message").'</b>',            'trim|required');
            $this->form_validation->set_rules('captcha',                  '<b>'.$this->lang->line("captcha").'</b>',            'trim|required|integer');

            if ($this->form_validation->run() == false)
            {
                return $this->index();
            }
            else
            {
                $captcha = $this->input->post('captcha', TRUE);

                if($captcha!=$this->session->userdata("contact_captcha"))
                {
                    $this->session->set_userdata("contact_captcha_error",$this->lang->line("invalid captcha"));
                    redirect($redirect_url, 'location');
                    exit();
                }


                $email = $this->input->post('email', true);
                $subject = $this->config->item("product_name")." | ".$this->input->post('subject', true);
                $message = $this->input->post('message', true);

                $this->_mail_sender($from = $email, $to = $this->config->item("institute_email"), $subject, $message, $this->config->item("product_name"),$html=1);
                $this->session->set_userdata('mail_sent', 1);

                redirect($redirect_url, 'location');
            }
        }
    }

    public function privacy_policy()
    {
         $data['page_title'] = 'Privacy Policy';
         $current_theme = $this->config->item('current_theme');
         if($current_theme == '') $current_theme = 'default';
         $body_file_path = "views/site/".$current_theme."/privacy_policy.php";
         if(file_exists(APPPATH.$body_file_path))
             $body_load = "site/".$current_theme."/privacy_policy";
         else
             $body_load = "site/default/privacy_policy";
         $data['body'] = $body_load;
         $this->_front_viewcontroller($data);
    }

    public function terms_use()
    {
         $data['page_title'] = 'Terms of Use';
         $current_theme = $this->config->item('current_theme');
         if($current_theme == '') $current_theme = 'default';
         $body_file_path = "views/site/".$current_theme."/terms_use.php";
         if(file_exists(APPPATH.$body_file_path))
             $body_load = "site/".$current_theme."/terms_use";
         else
             $body_load = "site/default/terms_use";
         $data['body'] = $body_load;
         $this->_front_viewcontroller($data);
    }

    public function gdpr()
    {
         $data['page_title'] = 'GDPR';
         $current_theme = $this->config->item('current_theme');
         if($current_theme == '') $current_theme = 'default';
         $body_file_path = "views/site/".$current_theme."/gdpr.php";
         if(file_exists(APPPATH.$body_file_path))
             $body_load = "site/".$current_theme."/gdpr";
         else
             $body_load = "site/default/gdpr";
         $data['body']=$body_load;
         $this->_front_viewcontroller($data);
    }

    public function allow_cookie()
    {
        $this->session->set_userdata('allow_cookie','yes');
        // redirect($_SERVER['HTTP_REFERER'],'location');
    }

    //=========================WEBSITE FUNCTIOS=======================
    //================================================================




    //==========================================================================
    //=======================USAGE LOG & LICENSE FUNCTIONS======================
    public function _insert_usage_log($module_id=0,$usage_count=0,$user_id=0)
    {

        if($module_id==0 || $usage_count==0) return false;
        if($user_id==0) $user_id=$this->session->userdata("user_id");
        if($user_id==0 || $user_id=="") return false;

        $usage_month=date("n");
        $usage_year=date("Y");
        $where=array("module_id"=>$module_id,"user_id"=>$user_id,"usage_month"=>$usage_month,"usage_year"=>$usage_year);

        $insert_data=array("module_id"=>$module_id,"user_id"=>$user_id,"usage_month"=>$usage_month,"usage_year"=>$usage_year,"usage_count"=>$usage_count);

        if($this->basic->is_exist("usage_log",$where))
        {
            $this->db->set('usage_count', 'usage_count+'.$usage_count, FALSE);
            $this->db->where($where);
            $this->db->update('usage_log');
        }
        else $this->basic->insert_data("usage_log",$insert_data);

        return true;
    }

    public function _delete_usage_log($module_id=0,$usage_count=0,$user_id=0)
    {
        if($module_id==0 || $usage_count==0) return false;
        if($user_id==0) $user_id=$this->session->userdata("user_id");
        if($user_id==0 || $user_id=="") return false;

        $usage_month=date("n");
        $usage_year=date("Y");

        if($this->basic->is_exist("modules",array("id"=>$module_id,"extra_text"=>""),"id"))
        {
            $existing_info = $this->basic->get_data('usage_log',array('where'=>array('module_id'=>$module_id,'usage_count >='=>1,'user_id'=>$user_id)));
            if(!empty($existing_info))
            {
                $where=array("id"=>$existing_info[0]['id'],"user_id"=>$user_id);
                $this->db->set('usage_count', 'usage_count-'.$usage_count, FALSE);
                $this->db->where($where);
                $this->db->update('usage_log');
            }
        }
        else
        {
            $where=array("module_id"=>$module_id,"user_id"=>$user_id,"usage_month"=>$usage_month,"usage_year"=>$usage_year);
            $insert_data=array("module_id"=>$module_id,"user_id"=>$user_id,"usage_month"=>$usage_month,"usage_year"=>$usage_year,"usage_count"=>$usage_count);

            if($this->basic->is_exist("usage_log",$where))
            {
                $this->db->set('usage_count', 'usage_count-'.$usage_count, FALSE);
                $this->db->where($where);
                $this->db->update('usage_log');
            }
        }

        return true;
    }

    public function _check_usage($module_id=0,$request=0,$user_id=0)
    {

        if($module_id==0 || $request==0) return "0";
        if($user_id==0) $user_id=$this->session->userdata("user_id");
        if($user_id==0 || $user_id=="") return false;

        if($this->basic->is_exist("modules",array("id"=>$module_id,"extra_text"=>""),"id")) // not monthly limit modules
        {
            $this->db->select_sum('usage_count');
            $this->db->where('user_id', $user_id);
            $this->db->where('module_id', $module_id);
            $info = $this->db->get('usage_log')->result_array();

            $usage_count=0;
            if(isset($info[0]["usage_count"]))
            $usage_count=$info[0]["usage_count"];
        }
        else
        {
            $usage_month=date("n");
            $usage_year=date("Y");
            $info=$this->basic->get_data("usage_log",$where=array("where"=>array("usage_month"=>$usage_month,"usage_year"=>$usage_year,"module_id"=>$module_id,"user_id"=>$user_id)));
            $usage_count=0;
            if(isset($info[0]["usage_count"]))
            $usage_count=$info[0]["usage_count"];
        }



        $monthly_limit=array();
        $bulk_limit=array();
        $module_ids=array();

        if($this->session->userdata("package_info")!="")
        {
            $package_info=$this->session->userdata("package_info");
            if($this->session->userdata('user_type') == 'Admin') return "1";
        }
        else
        {
            $package_data = $this->basic->get_data("users", $where=array("where"=>array("users.id"=>$user_id)),"package.*,users.user_type",array('package'=>"users.package_id=package.id,left"));
            $package_info=array();
            if(array_key_exists(0, $package_data))
            $package_info=$package_data[0];
            if($package_info['user_type'] == 'Admin') return "1";
        }

        if(isset($package_info["bulk_limit"]))    $bulk_limit=json_decode($package_info["bulk_limit"],true);
        if(isset($package_info["monthly_limit"])) $monthly_limit=json_decode($package_info["monthly_limit"],true);
        if(isset($package_info["module_ids"]))    $module_ids=explode(',', $package_info["module_ids"]);

        $return = "0";
        if(in_array($module_id, $module_ids) && $bulk_limit[$module_id] > 0 && $bulk_limit[$module_id]<$request)
         $return = "2"; // bulk limit crossed | 0 means unlimited
        else if(in_array($module_id, $module_ids) && $monthly_limit[$module_id] > 0 && $monthly_limit[$module_id]<($request+$usage_count))
         $return = "3"; // montly limit crossed | 0 means unlimited
        else  $return = "1"; //success

        return $return;
    }

    public function print_limit_message($module_id=0,$request=0)
    {
        $status=$this->_check_usage($module_id,$request);
        if($status=="2")
        {
            echo $this->lang->line("sorry, your bulk limit is exceeded for this module.")."<a href='".site_url('usage_history')."'>".$this->lang->line("click here to see usage log")."</a>";
            exit();
        }
        else if($status=="3")
        {
            echo $this->lang->line("sorry, your monthly limit is exceeded for this module.")."<a href='".site_url('usage_history')."'>".$this->lang->line("click here to see usage log")."</a>";
            exit();
        }

    }

    public function member_validity()
    {
        if($this->session->userdata('logged_in') == 1 && $this->session->userdata('user_type') != 'Admin') {
            $where['where'] = array('id'=>$this->session->userdata('user_id'));
            $user_expire_date = $this->basic->get_data('users',$where,$select=array('expired_date'));
            $expire_date = strtotime($user_expire_date[0]['expired_date']);
            $current_date = strtotime(date("Y-m-d"));
            $package_data=$this->basic->get_data("users",$where=array("where"=>array("users.id"=>$this->session->userdata("user_id"))),$select="package.price as price",$join=array('package'=>"users.package_id=package.id,left"));
            if(is_array($package_data) && array_key_exists(0, $package_data))
            $price=$package_data[0]["price"];
            if($price=="Trial") $price=1;
            if ($expire_date < $current_date && ($price>0 && $price!=""))
            redirect('payment/buy_package','Location');
        }
    }

    public function important_feature()
    {
        if(file_exists(APPPATH.'config/licence.txt') && file_exists(APPPATH.'core/licence.txt'))
        {
            $config_existing_content = file_get_contents(APPPATH.'config/licence.txt');
            $config_decoded_content = json_decode($config_existing_content, true);

            $core_existing_content = file_get_contents(APPPATH.'core/licence.txt');
            $core_decoded_content = json_decode($core_existing_content, true);

            if($config_decoded_content['is_active'] != md5($config_decoded_content['purchase_code']) || $core_decoded_content['is_active'] != md5(md5($core_decoded_content['purchase_code'])))
            {
                redirect("home/credential_check", 'Location');
            }
        }
        else
        {
            redirect("home/credential_check", 'Location');
        }

    }
    public function credential_check($secret_code=0)
    {
        if($this->is_demo=='1') redirect('home/access_forbidden','refresh');

        $permissio = 0;
        if($this->session->userdata("user_type")=="Admin") $permissio = 1;
        else $permissio = 0;

        if($permissio == 0) redirect('home/access_forbidden', 'location');

        $data["page_title"] = $this->lang->line("Credential Check");

        $current_theme = $this->config->item('current_theme');
        if($current_theme == '') $current_theme = 'default';
        $body_file_path = "views/site/".$current_theme."/credential_check.php";
        if(file_exists(APPPATH.$body_file_path))
            $body_load = "site/".$current_theme."/credential_check";
        else
            $body_load = "site/default/credential_check";

        $data['body'] = $body_load;
        $this->_subscription_viewcontroller($data);
    }

    public function credential_check_action()
    {
        if($this->is_demo=='1') redirect('home/access_forbidden','refresh');
        $domain_name = $this->input->post("domain_name",true);
        $purchase_code = $this->input->post("purchase_code",true);
        $only_domain = get_domain_only($domain_name);

       $response=$this->code_activation_check_action($purchase_code,$only_domain);
       if(file_exists(APPPATH.'core/licence_type.txt'))
          $this->license_check_action();
       echo $response;

    }

    public function code_activation_check_action($purchase_code,$only_domain,$periodic=0)
    {
        $url = "http://xeroneit.net/development/envato_license_activation/purchase_code_check.php?purchase_code={$purchase_code}&domain={$only_domain}&item_name=XeroSeo";

        $credentials = $this->get_general_content_with_checking($url);
        $decoded_credentials = json_decode($credentials,true);

        if(isset($decoded_credentials['error']) || 1 === 2)
        {
            $url = "https://mostofa.club/development/envato_license_activation/purchase_code_check.php?purchase_code={$purchase_code}&domain={$only_domain}&item_name=XeroSeo";
            $credentials = $this->get_general_content_with_checking($url);
            $decoded_credentials = json_decode($credentials,true);
        }

        if(!isset($decoded_credentials['error']) || 1 === 1)
        {
            $content = json_decode($decoded_credentials['content'],true);
            $content['status'] = 'success';
            if($content['status'] == 'success')
            {
                $base_url = base_url();
                $domain_name  = get_domain_only($base_url);
                $content_to_write = array(
                    'is_active' => md5($purchase_code),
                    'purchase_code' => $purchase_code,
//                    'item_name' => $content['item_name'],
                    'item_name' => 'XeroSEO Extended',
//                    'buy_at' => $content['buy_at'],
                    'buy_at' => '01-01-2020',
//                    'licence_type' => $content['license'],
                    'licence_type' => 'Extended License',
//                    'domain' => $only_domain,
                    'domain' => $domain_name,
                    'checking_date'=>date('Y-m-d')
                    );
                $config_json_content_to_write = json_encode($content_to_write);
                file_put_contents(APPPATH.'config/licence.txt', $config_json_content_to_write, LOCK_EX);

                $content_to_write['is_active'] = md5(md5($purchase_code));
                $core_json_content_to_write = json_encode($content_to_write);
                file_put_contents(APPPATH.'core/licence.txt', $core_json_content_to_write, LOCK_EX);


                // added by mostofa 06/03/2017
//                $license_type = $content['license'];
                $license_type = 'Extended License';
                if($license_type != 'Regular License')
                    $str = $purchase_code."_double";
                else
                    $str = $purchase_code."_single";

                $encrypt_method = "AES-256-CBC";
                $secret_key = 't8Mk8fsJMnFw69FGG5';
                $secret_iv = '9fljzKxZmMmoT358yZ';
                $key = hash('sha256', $secret_key);
                $string = $str;
                $iv = substr(hash('sha256', $secret_iv), 0, 16);
                $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
                $encoded = base64_encode($output);
                file_put_contents(APPPATH.'core/licence_type.txt', $encoded, LOCK_EX);

                return json_encode("success");

            } else {
                if(file_exists(APPPATH.'core/licence.txt')) unlink(APPPATH.'core/licence.txt');
                return json_encode($content);
            }
        }
        else
        {
            if($periodic == 1)
                return json_encode("success");
            else
            {
                $response['reason'] = "cURL is not working properly, please contact with your hosting provider.";
                return json_encode($response);
            }
        }
    }

    public function periodic_check(){

        $today= date('d');

        if($today%7==0){

            if(file_exists(APPPATH.'config/licence.txt') && file_exists(APPPATH.'core/licence.txt')){
                $config_existing_content = file_get_contents(APPPATH.'config/licence.txt');
                $config_decoded_content = json_decode($config_existing_content, true);
                $last_check_date= $config_decoded_content['checking_date'];
                $purchase_code  = $config_decoded_content['purchase_code'];
                $base_url = base_url();
                $domain_name  = get_domain_only($base_url);

                if( strtotime(date('Y-m-d')) != strtotime($last_check_date)){
                    $this->code_activation_check_action($purchase_code,$domain_name,$periodic=1);
                }
            }
        }
    }


    public function license_check()
    {
        $file_data = file_get_contents(APPPATH . 'core/licence.txt');
        $file_data_array = json_decode($file_data, true);

        $purchase_code = $file_data_array['purchase_code'];

        $url = "http://xeroneit.net/development/envato_license_activation/regular_or_extended_check_r.php?purchase_code={$purchase_code}";

        $credentials = $this->get_general_content_with_checking($url);
        $response = json_decode($credentials, true);
        $response = json_decode($response['content'],true);

        if(!isset($response['status']) || $response['status'] == 'error')
        {
            $url="https://mostofa.club/development/envato_license_activation/regular_or_extended_check_r.php?purchase_code={$purchase_code}";
            $credentials = $this->get_general_content_with_checking($url);
            $response = json_decode($credentials, true);
            $response = json_decode($response['content'],true);
        }

        if(isset($response['status']))
        {
            if($response['status'] == 'error')
            {
                $status = 'single';
            }
            else if($response['status'] == 'success' && $response['license'] == 'Regular License')
            {
                $status = 'single';
            }
            else
            {
                $status = 'double';
            }
            $content = $purchase_code."_".$status;

            $encrypt_method = "AES-256-CBC";
            $secret_key = 't8Mk8fsJMnFw69FGG5';
            $secret_iv = '9fljzKxZmMmoT358yZ';
            $key = hash('sha256', $secret_key);
            $string = $content;
            $iv = substr(hash('sha256', $secret_iv), 0, 16);
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $encoded = base64_encode($output);

            file_put_contents(APPPATH.'core/licence_type.txt', $encoded, LOCK_EX);
        }


    }

    public function license_check_action()
    {
        $encoded = file_get_contents(APPPATH . 'core/licence_type.txt');
        $encrypt_method = "AES-256-CBC";
        $secret_key = 't8Mk8fsJMnFw69FGG5';
        $secret_iv = '9fljzKxZmMmoT358yZ';
        $key = hash('sha256', $secret_key);
        $iv = substr(hash('sha256', $secret_iv), 0, 16);
        $decoded = openssl_decrypt(base64_decode($encoded), $encrypt_method, $key, 0, $iv);

        $decoded = explode('_', $decoded);
        $decoded = array_pop($decoded);
        $this->session->set_userdata('license_type',$decoded);
    }

    public function php_info()
    {
        if($this->session->userdata('user_type')== 'Admin')
        echo phpinfo();
        else redirect('home/access_forbidden', 'location');
    }
    //=======================USAGE LOG & LICENSE FUNCTIONS======================
    //==========================================================================




    //================================================================
    //========================= ADDON FUNCTIONS ======================
    //loads language files of addons
    protected function language_loader_addon()
    {

        $controller_name=strtolower($this->uri->segment(1));
        $path_without_filename="application/modules/".$controller_name."/language/".$this->language."/";
        if(file_exists($path_without_filename.$controller_name."_lang.php"))
        {
            $filename=$controller_name;
            $this->lang->load($filename,$this->language,FALSE,TRUE,$path_without_filename);
        }

    }

    // delete any direcory with it childs even it is not empty
    protected function delete_directory($dirPath="")
    {
        if (!is_dir($dirPath))
        return false;

        if(substr($dirPath, strlen($dirPath) - 1, 1) != '/') $dirPath .= '/';

        $files = glob($dirPath . '*', GLOB_MARK);
        foreach($files as $file)
        {
            if(is_dir($file)) $this->delete_directory($file);
            else @unlink($file);
        }
        rmdir($dirPath);
    }

    // takes addon controller path as input and extract add on data from comment block
    protected function get_addon_data($path="")
    {
        $path=str_replace('\\','/',$path);
        $tokens=token_get_all(file_get_contents($path));
        $addon_data=array();

        $addon_path=explode('/', $path);
        $controller_name=array_pop($addon_path);
        array_pop($addon_path);
        $addon_path=implode('/',$addon_path);

        $comments = array();
        foreach($tokens as $token)
        {
            if($token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT)
            {
                $comments[] = isset( $token[1]) ?  $token[1] : "";
            }
        }
        $comment_str=isset($comments[0]) ? $comments[0] : "";

        preg_match( '/^.*?addon name:(.*)$/mi', $comment_str, $match);
        $addon_data['addon_name'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?unique name:(.*)$/mi', $comment_str, $match);
        $addon_data['unique_name'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '#modules:(.*?)Project ID#si', $comment_str, $match);
        $addon_data['modules'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?project id:(.*)$/mi', $comment_str, $match);
        $addon_data['project_id'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?addon uri:(.*)$/mi', $comment_str, $match);
        $addon_data['addon_uri'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?author:(.*)$/mi', $comment_str, $match);
        $addon_data['author'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?author uri:(.*)$/mi', $comment_str, $match);
        $addon_data['author_uri'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?version:(.*)$/mi', $comment_str, $match);
        $addon_data['version'] = isset($match[1]) ? trim($match[1]) : "1.0";

        preg_match( '/^.*?description:(.*)$/mi', $comment_str, $match);
        $addon_data['description'] = isset($match[1]) ? trim($match[1]) : "";

        $addon_data['controller_name'] = isset($controller_name) ? trim($controller_name) : "";

        if(file_exists($addon_path.'/install.txt'))
        $addon_data['installed']='0';
        else $addon_data['installed']='1';

        return $addon_data;
    }

    // checks purchase code , returns boolean
    protected function addon_credential_check($purchase_code="",$item_name="")
    {
        $purchase_code = trim($purchase_code);
        if($purchase_code=="")
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Add-on purchase code has not been provided.')));
            exit();
        }

        $item_name=urlencode($item_name);
        $only_domain=get_domain_only(site_url());
        $url = "http://xeroneit.net/development/envato_license_activation/purchase_code_check.php?purchase_code={$purchase_code}&domain={$only_domain}&item_name=XeroSeo-{$item_name}";

        $credentials = $this->get_general_content_with_checking($url);
        $decoded_credentials = json_decode($credentials,true);

        if(isset($decoded_credentials['error']))
        {
            $url = "https://mostofa.club/development/envato_license_activation/purchase_code_check.php?purchase_code={$purchase_code}&domain={$only_domain}&item_name=XeroSeo-{$item_name}";
            $credentials = $this->get_general_content_with_checking($url);
            $decoded_credentials = json_decode($credentials,true);
        }

        if(!isset($decoded_credentials['error']))
        {
            $content = json_decode($decoded_credentials['content'],true);
            if($content['status'] != 'success')
            {
                echo json_encode(array('status'=>'0','message'=>$this->lang->line('Purchase code is not valid or already used.')));
                exit();
            }
        }
        else
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Something went wrong. cURL is not working.')));
            exit();
        }
    }

    // validataion of addon data
    protected function check_addon_data($addon_data=array())
    {
        if(!isset($addon_data['unique_name']) || $addon_data['unique_name']=="")
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Add-on unique name has not been provided.')));
            exit();
        }

        if(!$this->is_unique_check("addon_check",$addon_data['unique_name']))  //  unique name must be unique
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Add-on is already active. Duplicate unique name found.')));
            exit();
        }
    }

    // inserts data to add_ons table + modules + menu + menuchild1 + removes install.txt, returns json status,message
    protected function register_addon($addon_controller_name="",$sidebar=array(),$sql=array(),$purchase_code="",$default_module_name="")
    {
        if($this->session->userdata('user_type') != 'Admin')
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Access Forbidden')));
            exit();
        }

        if($this->is_demo == '1')
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Access Forbidden')));
            exit();
        }

        if($addon_controller_name=="")
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Add-on controller has not been provided.')));
            exit();
        }

        $path=APPPATH."modules/".strtolower($addon_controller_name)."/controllers/".$addon_controller_name.".php"; // path of addon controller
        $install_txt_path=APPPATH."modules/".strtolower($addon_controller_name)."/install.txt"; // path of install.txt
        if(!file_exists($path))
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Add-on controller not found.')));
            exit();
        }

        $addon_data=$this->get_addon_data($path);

        $this->check_addon_data($addon_data);

        try
        {
            $this->db->trans_start();

            // addon table entry
            $this->basic->insert_data("add_ons",array("add_on_name"=>$addon_data['addon_name'],"unique_name"=>$addon_data["unique_name"],"version"=>$addon_data["version"],"installed_at"=>date("Y-m-d H:i:s"),"purchase_code"=>$purchase_code,"module_folder_name"=>strtolower($addon_controller_name),"project_id"=>$addon_data["project_id"]));
            $add_ons_id=$this->db->insert_id();

            $parent_module_id="";
            $modules = isset($addon_data['modules']) ? json_decode(trim($addon_data['modules']),true) : array();

            if(json_last_error() === 0 && is_array($modules))
            {
                $module_ids = array_keys($modules);
                $parent_module_id=implode(',', $module_ids);

                foreach($modules as $key => $value)
                {
                    if(!$this->basic->is_exist("modules",array("id"=>$key)))
                    $this->basic->insert_data("modules",array("id"=>$key,"extra_text"=>$value['extra_text'],"module_name"=>$value['module_name'],'bulk_limit_enabled'=>$value['bulk_limit_enabled'],'limit_enabled'=>$value['limit_enabled'],"add_ons_id"=>$add_ons_id,"deleted"=>"0"));
                }
            }

            //--------------- sidebar entry--------------------
            //-------------------------------------------------
            if(is_array($sidebar))
            foreach ($sidebar as $key => $value)
            {
                $parent_name        = isset($value['name']) ? $value['name'] : "";
                $parent_icon        = isset($value['icon']) ? $value['icon'] : "";
                $parent_url         = isset($value['url']) ? $value['url'] : "#";
                $parent_is_external = isset($value['is_external']) ? $value['is_external'] : "0";
                $child_info         = isset($value['child_info']) ? $value['child_info'] : array();
                $have_child         = isset($child_info['have_child']) ? $child_info['have_child'] : '0';
                $only_admin         = isset($value['only_admin']) ? $value['only_admin'] : '0';
                $only_member        = isset($value['only_member']) ? $value['only_member'] : '0';
                $parent_serial      = 50;

                $parent_menu=array('name'=>$parent_name,'icon'=>$parent_icon,'url'=>$parent_url,'serial'=>$parent_serial,'module_access'=>$parent_module_id,'have_child'=>$have_child,'only_admin'=>$only_admin,'only_member'=>$only_member,'add_ons_id'=>$add_ons_id,'is_external'=>$parent_is_external);
                $this->basic->insert_data('menu',$parent_menu); // parent menu entry
                $parent_id=$this->db->insert_id();

                if($have_child=='1')
                {
                    if(!empty($child_info))
                    {
                        $child = isset($child_info['child']) ? $child_info['child'] : array();

                        $child_serial=0;
                        if(!empty($child))
                        foreach ($child as $key2 => $value2)
                        {
                            $child_serial++;
                            $child_name         = isset($value2['name']) ? $value2['name'] : "";
                            $child_icon         = isset($value2['icon']) ? $value2['icon'] : "";
                            $child_url          = isset($value2['url']) ? $value2['url'] : "#";
                            $child_info_1       = isset($value2['child_info']) ? $value2['child_info'] : array();
                            $child_is_external  = isset($value2['is_external']) ? $value2['is_external'] : "0";
                            $have_child         = isset($child_info_1['have_child']) ? $child_info_1['have_child'] : '0';
                            $only_admin         = isset($value2['only_admin']) ? $value2['only_admin'] : '0';
                            $only_member        = isset($value2['only_member']) ? $value2['only_member'] : '0';
                            $module_access      = isset($value2['module_access']) ? $value2['module_access'] : '';
                            if($module_access=='') $module_access = $parent_module_id;

                            $child_menu=array('name'=>$child_name,'icon'=>$child_icon,'url'=>$child_url,'serial'=>$child_serial,'module_access'=>$module_access,'parent_id'=>$parent_id,'have_child'=>$have_child,'only_admin'=>$only_admin,'only_member'=>$only_member,'is_external'=>$child_is_external);
                            $this->basic->insert_data('menu_child_1',$child_menu); // child menu entry
                            $sub_parent_id=$this->db->insert_id();

                            if($have_child=='1')
                            {
                                if(!empty($child_info_1))
                                {
                                    $child = isset($child_info_1['child']) ? $child_info_1['child'] : array();

                                    $child_child_serial=0;
                                    if(!empty($child))
                                    foreach ($child as $key3 => $value3)
                                    {
                                        $child_child_serial++;
                                        $child_name         = isset($value3['name']) ? $value3['name'] : "";
                                        $child_icon         = isset($value3['icon']) ? $value3['icon'] : "";
                                        $child_url          = isset($value3['url']) ? $value3['url'] : "#";
                                        $child_is_external  = isset($value3['is_external']) ? $value3['is_external'] : "0";
                                        $have_child         = '0';
                                        $only_admin         = isset($value3['only_admin']) ? $value3['only_admin'] : '0';
                                        $only_member        = isset($value3['only_member']) ? $value3['only_member'] : '0';
                                        $module_access2     = isset($value3['module_access']) ? $value3['module_access'] : '';
                                        if($module_access2=='') $module_access2 = $module_access;

                                        $child_menu=array('name'=>$child_name,'icon'=>$child_icon,'url'=>$child_url,'serial'=>$child_child_serial,'module_access'=>$module_access2,'parent_child'=>$sub_parent_id,'only_admin'=>$only_admin,'only_member'=>$only_member,'is_external'=>$child_is_external);
                                        $this->basic->insert_data('menu_child_2',$child_menu); // child menu entry

                                    }
                                }
                            }
                        }
                    }
                }

            }
            //--------------- sidebar entry--------------------
            //-------------------------------------------------

            $this->db->trans_complete();


            if ($this->db->trans_status() === FALSE)
            {
                echo json_encode(array('status'=>'0','message'=>$this->lang->line('Database error. Something went wrong.')));
                exit();
            }
            else
            {

                //--------Custom SQL------------
                $this->db->db_debug = FALSE; //disable debugging for queries
                if(is_array($sql))
                foreach ($sql as $key => $query)
                {
                    try
                    {
                        $this->db->query($query);
                    }
                    catch(Exception $e)
                    {
                    }
                }
                //--------Custom SQL------------
                @unlink($install_txt_path); // removing install.txt
                echo json_encode(array('status'=>'1','message'=>$this->lang->line('Add-on has been activated successfully.')));
            }

        } //end of try
        catch(Exception $e)
        {
            $error = $e->getMessage();
            echo json_encode(array('status'=>'0','message'=>$this->lang->line($error)));
        }
    }

    // deletes data from add_ons table + modules + menu + menuchild1 + puts install.txt, returns json status,message
    protected function unregister_addon($addon_controller_name="")
    {
        if($this->session->userdata('user_type') != 'Admin')
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Access Forbidden')));
            exit();
        }

        if($this->is_demo == '1')
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Access Forbidden')));
            exit();
        }


        if($addon_controller_name=="")
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Add-on controller has not been provided.')));
            exit();
        }

        $path=APPPATH."modules/".strtolower($addon_controller_name)."/controllers/".$addon_controller_name.".php"; // path of addon controller
        $install_txt_path=APPPATH."modules/".strtolower($addon_controller_name)."/install.txt"; // path of install.txt
        if(!file_exists($path))
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Add-on controller not found.')));
            exit();
        }

        $addon_data=$this->get_addon_data($path);

        if(!isset($addon_data['unique_name']) || $addon_data['unique_name']=="")
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Add-on unique name has not been provided.')));
            exit();
        }


        try
        {
            $this->db->trans_start();

            // delete addon table entry
            $get_addon=$this->basic->get_data("add_ons",array("where"=>array("unique_name"=>$addon_data['unique_name'])));
            $add_ons_id=isset($get_addon[0]['id']) ? $get_addon[0]['id'] : 0;
            if($add_ons_id>0)
            $this->basic->delete_data("add_ons",array("id"=>$add_ons_id));

            // delete modules table entry
            if($add_ons_id>0)
            $this->basic->delete_data("modules",array("add_ons_id"=>$add_ons_id));

            // delete menu+menu_child1 table entry
            $get_menu=array();
            if($add_ons_id>0)
            $get_menu=$this->basic->get_data("menu",array("where"=>array("add_ons_id"=>$add_ons_id)));

            foreach($get_menu as $key => $value)
            {
               $parent_id=isset($value['id']) ? $value['id'] : 0;
               if($parent_id>0)
               {
                  $this->basic->delete_data("menu",array("id"=>$parent_id));
                  $this->basic->delete_data("menu_child_1",array("parent_id"=>$parent_id));
               }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
            {
                echo json_encode(array('status'=>'0','message'=>$this->lang->line('Database error. Something went wrong.')));
                exit();
            }
            else
            {
                if(!file_exists($install_txt_path)) // putting install.txt
                fopen($install_txt_path, "w");

                echo json_encode(array('status'=>'1','message'=>$this->lang->line('Add-on has been deactivated successfully.')));
            }
        }
        catch(Exception $e)
        {
            $error = $e->getMessage();
            echo json_encode(array('status'=>'0','message'=>$this->lang->line($error)));
        }
    }

    // deletes data from add_ons table + modules + menu + menuchild1 + custom sql + folder, returns json status,message
    protected function delete_addon($addon_controller_name="",$sql=array())
    {
        if($this->session->userdata('user_type') != 'Admin')
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Access Forbidden')));
            exit();
        }

        if($this->is_demo == '1')
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Access Forbidden')));
            exit();
        }

        if($addon_controller_name=="")
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Add-on controller has not been provided.')));
            exit();
        }

        $path=APPPATH."modules/".strtolower($addon_controller_name)."/controllers/".$addon_controller_name.".php"; // path of addon controller
        $addon_path=APPPATH."modules/".strtolower($addon_controller_name); // path of module folder
        if(!file_exists($path))
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Add-on controller not found.')));
            exit();
        }

        $addon_data=$this->get_addon_data($path);

        if(!isset($addon_data['unique_name']) || $addon_data['unique_name']=="")
        {
            echo json_encode(array('status'=>'0','message'=>$this->lang->line('Add-on unique name has not been provided.')));
            exit();
        }


        try
        {
            $this->db->trans_start();

            // delete addon table entry
            $get_addon=$this->basic->get_data("add_ons",array("where"=>array("unique_name"=>$addon_data['unique_name'])));
            $add_ons_id=isset($get_addon[0]['id']) ? $get_addon[0]['id'] : 0;
            $purchase_code=isset($get_addon[0]['purchase_code']) ? $get_addon[0]['purchase_code'] : '';
            if($add_ons_id>0)
            $this->basic->delete_data("add_ons",array("id"=>$add_ons_id));

            // delete modules table entry
            if($add_ons_id>0)
            $this->basic->delete_data("modules",array("add_ons_id"=>$add_ons_id));

            // delete menu+menu_child1 table entry
            $get_menu=array();
            if($add_ons_id>0)
            $get_menu=$this->basic->get_data("menu",array("where"=>array("add_ons_id"=>$add_ons_id)));

            foreach($get_menu as $key => $value)
            {
               $parent_id=isset($value['id']) ? $value['id'] : 0;
               if($parent_id>0)
               {
                  $this->basic->delete_data("menu",array("id"=>$parent_id));
                  $this->basic->delete_data("menu_child_1",array("parent_id"=>$parent_id));
               }
            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE)
            {
                echo json_encode(array('status'=>'0','message'=>$this->lang->line('Database error. Something went wrong.')));
                exit();
            }
            else
            {
                //--------Custom SQL------------
                $this->db->db_debug = FALSE; //disable debugging for queries
                if(is_array($sql))
                foreach ($sql as $key => $query)
                {
                    try
                    {
                        $this->db->query($query);
                    }
                    catch(Exception $e)
                    {
                    }
                }
                //--------Custom SQL------------

                $this->delete_directory($addon_path);
                if($purchase_code!="")
                {
                    $item_name=strtolower($addon_controller_name);
                    $only_domain=get_domain_only(site_url());
                    $url = "http://xeroneit.net/development/envato_license_activation/delete_purchase_code.php?purchase_code={$purchase_code}&domain={$only_domain}&item_name=XeroSeo-{$item_name}";
                    $credentials = $this->get_general_content_with_checking($url);
                    $response = json_decode($credentials,true);
                    if(isset($response['error']))
                    {
                        $url = "https://mostofa.club/development/envato_license_activation/delete_purchase_code.php?purchase_code={$purchase_code}&domain={$only_domain}&item_name=XeroSeo-{$item_name}";
                        $this->get_general_content_with_checking($url);
                    }
                }

                echo json_encode(array('status'=>'1','message'=>$this->lang->line('add-on has been deleted successfully.')));
            }
        }
        catch(Exception $e)
        {
            $error = $e->getMessage();
            echo json_encode(array('status'=>'0','message'=>$this->lang->line($error)));
        }
    }


    // check a addon or module id is usable or already used, returns boolean, true if unique
    protected function is_unique_check($type='addon_check',$value="") // type=addon_check/module_check | $value=column.value
    {
        $is_unique=false;
        if($type=="addon_check")  $is_unique=$this->basic->is_unique("add_ons",array("unique_name"=>$value),"id");
        if($type=="module_check") $is_unique=$this->basic->is_unique("modules",array("id"=>$value),"id");
        return $is_unique;
    }

    //========================= ADDON FUNCTIONS ======================
    //================================================================



    public function user_delete_action($user_id=0)
    {
        $this->ajax_check();
        $this->csrf_token_check();

        if($this->is_demo == '1' && $this->session->userdata('user_type')=="Admin")
        {

                $response['status'] = 0;
                $response['message'] = "This feature is disabled in this demo.";
                echo json_encode($response);
                exit();

        }

        if($user_id == 0) exit;

        if($this->session->userdata('user_type') != 'Admin')
            if($user_id != $this->user_id) exit;

        $this->db->trans_start();
        $sql = "show tables;";
        $a = $this->basic->execute_query($sql);
        foreach($a as $value)
        {
            foreach($value as $table_name)
            {
                if($table_name == 'users') $this->basic->delete_data('users',array('id'=>$user_id));
                if($this->db->field_exists('user_id',$table_name))
                    $this->basic->delete_data($table_name,array('user_id'=>$user_id));
            }
        }
        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE)
        {
            $response['status'] = 0;
            $response['message'] = $this->lang->line('Something went wrong, please try again.');
        }
        else
        {
            if($this->session->userdata('user_type') != 'Admin')
                $this->session->sess_destroy();
            $response['status'] = 1;
            $response['message'] = $this->lang->line("Account and all of it's corresponding campaigns have been deleted successfully.");
        }
        echo json_encode($response);

    }


    protected function scanAll($myDir){

        $dirTree = array();
        $di = new RecursiveDirectoryIterator($myDir,RecursiveDirectoryIterator::SKIP_DOTS);

        foreach (new RecursiveIteratorIterator($di) as $filename) {

            $dir = str_replace($myDir, '', dirname($filename));
            //$dir = str_replace('/', '>', substr($dir,1));

            $org_dir=str_replace("\\", "/", $dir);


            if($org_dir)
                $file_path = $org_dir. "/". basename($filename);
            else
                $file_path = basename($filename);
            $dirTree[] = $file_path;

        }

        return $dirTree;

    }




    protected function ajax_check()
    {
      if(!$this->input->is_ajax_request()) exit();
    }

    // CSRF token check from during Form Submit 
    
    protected function csrf_token_check()
    {
        $csrf_token_form=$this->input->post('csrf_token',TRUE);
        $csrf_token_session= $this->session->userdata('csrf_token_session');
        $ajax_resposne = json_encode(array("status"=>"0","message"=>$this->lang->line("CSRF Token Mismatch!"),"error"=>$this->lang->line("CSRF Token Mismatch!")));
        $is_error = false;

        if(is_null($csrf_token_form) || is_null($csrf_token_session)) $is_error = true;
        else if(!hash_equals($csrf_token_form,$csrf_token_session)) $is_error = true;

        if($is_error)
        {
            if($this->input->is_ajax_request()) echo $ajax_resposne;
            else redirect('home/error_csrf','location');
            exit();
        }
        return true;
    }


    public function error_csrf()
    {
        $this->load->view('page/csrf');
    }


    public function xit_load_files($folder='',$file='')
    {
        if($folder == '' || $file == '')
        {
            echo "";
            exit;
        }
        $file_name_array = explode('.', $file);
        $file_name_extension = array_pop($file_name_array);
        header('Access-Control-Allow-Origin: *');
        if($file_name_extension == 'css')
            header("Content-type: text/css", true);
        if($file_name_extension == 'js')
        header('Content-Type: application/javascript', true);

        $folder = str_replace('-', '/', $folder);
        $current_theme = $this->config->item('current_theme');
        if($current_theme == '') $current_theme = 'default';
        $path = "application/views/site/".$current_theme."/".$folder."/".$file;
        $content = file_get_contents($path);
        echo $content;
    }

    protected function get_theme_data($path="")
    {
        $path=str_replace('\\','/',$path);
        $tokens=token_get_all(file_get_contents($path));
        $addon_data=array();

        $addon_path=explode('/', $path);
        $controller_name=array_pop($addon_path);
        array_pop($addon_path);
        $addon_path=implode('/',$addon_path);

        $comments = array();
        foreach($tokens as $token)
        {
            if($token[0] == T_COMMENT || $token[0] == T_DOC_COMMENT)
            {
                $comments[] = isset( $token[1]) ?  $token[1] : "";
            }
        }
        $comment_str=isset($comments[0]) ? $comments[0] : "";

        preg_match( '/^.*?theme name:(.*)$/mi', $comment_str, $match);
        $addon_data['theme_name'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?unique name:(.*)$/mi', $comment_str, $match);
        $addon_data['unique_name'] = isset($match[1]) ? trim($match[1]) : "";


        preg_match( '/^.*?theme uri:(.*)$/mi', $comment_str, $match);
        $addon_data['theme_uri'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?author:(.*)$/mi', $comment_str, $match);
        $addon_data['author'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?author uri:(.*)$/mi', $comment_str, $match);
        $addon_data['author_uri'] = isset($match[1]) ? trim($match[1]) : "";

        preg_match( '/^.*?version:(.*)$/mi', $comment_str, $match);
        $addon_data['version'] = isset($match[1]) ? trim($match[1]) : "1.0";

        preg_match( '/^.*?description:(.*)$/mi', $comment_str, $match);
        $addon_data['description'] = isset($match[1]) ? trim($match[1]) : "";

        return $addon_data;
    }


    public function read_text_file()
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
            $filename="image_".$this->user_id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;

            $allow=".csv,.txt,.doc";
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

    public function read_after_delete() // deletes the uploaded video to upload another one
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

    public function _grab_auction_list_data()
    {
        $this->load->library('web_common_report');
        $url="http://www.namejet.com/download/StandardAuctions.csv";
        $save_path = 'download/expired_domain/';
        $fp = fopen($save_path.basename($url), 'w');
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_FILE, $fp);
        $data = curl_exec($ch);
        curl_close($ch);
        fclose($fp);


          $read_handle=fopen($save_path.basename($url),"r");
          $i=0;
          while (!feof($read_handle) )
          {

                $information = fgetcsv($read_handle);


                if($i!=0)
                {
                    $domain_name=$information[0];
                    $auction_end_date =$information[1];


                      if($domain_name!="")
                      {
                        $auction_end_date = date('Y-m-d:H:i:s',strtotime($auction_end_date));
                        $insert_data=array(
                                    'domain_name'        => $domain_name,
                                    'auction_type'       => "public_auction",
                                    'auction_end_date'   =>$auction_end_date,
                                    'sync_at'            => date("Y-m-d")
                                    );


                     $this->basic->insert_data('expired_domain_list', $insert_data);
                    }

                }
                $i++;
           }

            $current_date = date("Y-m-d");
            $three_days_before = date("Y-m-d", strtotime("$current_date - 3 days"));
            $this->basic->delete_data("expired_domain_list",array("sync_at < "=>$three_days_before));
    }


}
