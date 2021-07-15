<?php

require_once("Home.php"); // including home controller

/**
* class config
* @category controller
*/
class Dashboard extends Home
{
    public $user_id;
    /**
    * load constructor method
    * @access public
    * @return void
    */
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

    /**
    * load index method. redirect to config
    * @access public
    * @return void
    */

    public function index()
    {
        $country_list = $this->get_country_names();

        if($this->session->userdata("user_type")=="Member")
        {
            $package_info=$this->session->userdata("package_info");              
            $package_name="No Package";
            if(isset($package_info["package_name"]))  $package_name=$package_info["package_name"];
            $validity="0";
            if(isset($package_info["validity"]))  $validity=$package_info["validity"];
            $price="0";
            if(isset($package_info["price"]))  $price=$package_info["price"];
            $data['package_name']=$package_name;
            $data['validity']=$validity;
            $data['price']=$price;


        }
        
        $data['payment_config']=$this->basic->get_data('payment_config');
        $where['where'] = array('user_id'=>$this->user_id);

        $visitor_type_where['where'] = array("user_id"=>$this->user_id,"dashboard"=>'1');
        $visitor_type_data = $this->basic->get_data('visitor_analysis_domain_list',$visitor_type_where,$select='',$join='',$limit=3,$start=NULL,$order_by='');
        // $visitor_type_data = array();
        /**
         * Doughnut Chart 
         * 
         */
        $l=0;
        foreach($visitor_type_data as $value){
            $l++;
            $select=array("count(id) as user_number","country");
            $country_report_where=array();

            $today = date("Y-m-d");
            $from_date = $today." 00:00:00";
            $to_date = $today." 23:59:59";

            $country_report_where['where'] = array(
                "date_time >=" => $from_date,
                "date_time <=" => $to_date,
                "is_new"=>"1",
                "domain_list_id" => $value['id']
                );

            // $country_report_where['where'] = array("is_new"=>"1","date_format(date_time,'%Y-%m-%d')"=>date("Y-m-d"));
            $country_report_data = $this->basic->get_data('visitor_analysis_domain_list_data',$country_report_where,$select,$join="",$limit=5,$start='',$order_by='user_number desc',$group_by='country');

            $temp_data = array();
            $m=0;
            $color_array = array("#F9E559","#218C8D","#6CCECB","#EF7126","#8EDC9D","#473E3F");           

            // $str = '<ul class="chart-legend clearfix" id="visitor_type_color_codes">';
            $str="";

            foreach($country_report_data as $value1){
             
                $temp_data[$value1['country']] = $value1['user_number'];

                $br='';
                if(($m+1)%3==0) $br='<br>';
                $str .= '<i class="fa fa-circle-o" style="color: '.$color_array[$m].';"></i> '.array_search($value1['country'],$country_list).' : '.$value1['user_number'].' '.$br;
                $m++;
            }

            // $str .= '</ul>';

            $data_number = "country_chart_data_".$l;
            $country_name_data = "country_name_list_".$l;
            $data[$data_number] = $temp_data;
            $data[$country_name_data] = $str;
        }

        /**
         * End Doughnut Chart 
         * 
         */

        /**
         * Pie Chart 
         * 
         */
        $k=0;
        foreach($visitor_type_data as $value1){

            $k++;
            $select=array("GROUP_CONCAT(is_new SEPARATOR ',') as new_vs_returning");
            $new_or_returning_where=array();

            $today = date("Y-m-d");
            $from_date = $today." 00:00:00";
            $to_date = $today." 23:59:59";

            $new_or_returning_where['where'] = array(
                "date_time >=" => $from_date,
                "date_time <=" => $to_date,
                "domain_list_id" => $value1['id']
                );
           
            $total_new_returning = $this->basic->get_data('visitor_analysis_domain_list_data',$new_or_returning_where,$select,$join="",$limit='',$start='',$order_by='',$group_by='cookie_value,session_value');
           

            $new_or_returning = array();
            $new_user = 0;
            $returning_user = 0;
            foreach($total_new_returning as $value){
                $new_or_returning = explode(',', $value['new_vs_returning']);
                if(in_array(1, $new_or_returning)) $new_user++;
                else $returning_user++;
            }

            $data_number = "pie_chart_data_".$k;
            $website_name = "website_name_".$k; 
            $website_id = "website_id_".$k; 
            $temp_data = array(
                'value' => array(
                    'new_user' => $new_user,
                    'returning_user' => $returning_user,
                )
            );
            $data[$data_number] = $temp_data;
            $data[$website_name] = $value1['domain_name'];
            $data[$website_id] = $value1['id'];
        }
        //echo "<pre>";print_r($data);exit;
        /**
         * End Pie Chart 
         * 
         */

        /**
         * Line Chart
         */
        $curdate=date("Y-m-d");
        $from_date=date('Y-m-d', strtotime($curdate. " - 7 days"));
        $from_date = $from_date." 00:00:00";
        $to_date = $curdate." 23:59:59";
        $k=0;
        $line_char_data = array();
        foreach($visitor_type_data as $value)
        {
            $where = array();
            $where['where'] = array(
                "date_time >=" => $from_date,
                "date_time <=" => $to_date,
                "domain_list_id" => $value['id']
                );
            $select = array(
                "date_format(date_time,'%Y-%m-%d') as date",
                "count(id) as number_of_user",
                "visit_url"
                );
            $day_wise_visitor = $this->basic->get_data('visitor_analysis_domain_list_data',$where,$select,$join='',$limit='',$start='',$order_by='',$group_by="date");

           // echo $this->db->last_query();
            $day_count = date('Y-m-d', strtotime($from_date. " + 1 days"));

            $day_wise_info=array();
            foreach ($day_wise_visitor as $value2){
                $day_wise_info[$value2['date']] = $value2['number_of_user'];
            }

            $dDiff = strtotime($to_date) - strtotime($from_date);
            $no_of_days = floor($dDiff/(60*60*24));
            
            for($i=0;$i<=$no_of_days;$i++){
                $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
                if(isset($day_wise_info[$day_count])){
                    $line_char_data[$k][$i]['user'] = $day_wise_info[$day_count];
                } else {
                    $line_char_data[$k][$i]['user'] = 0;
                }
                $line_char_data[$k][$i]['date'] = date('Y-m-d', strtotime($from_date. " + $i days"));
                $line_char_data[$k][$i]['domain'] = $value['domain_name'];
            }
            $k++;
        }
       
        $data['day_wise_click_report'] = $line_char_data; 



        /**
         * Line Chart Compare
         * 
         */
        $k=0;
        $line_char_data_compare=array();
        foreach ($line_char_data as $key => $value)
        {
          foreach ($value as $key2 => $value2) 
          {
             $line_char_data_compare[$value2['date']]['date']=$value2['date'];
             $domainName=str_ireplace(array('https://','http://','/'), '', $value2['domain']);
             $domainName=trim($domainName,'/');
             $line_char_data_compare[$value2['date']][$domainName]=$value2['user'];
          }
          $k++;
        }


        /**
         * New added requirement
         * 
         */

        $where = array();
        $where['where'] = array('user_id'=>$this->user_id);

        $table = "visitor_analysis_domain_list_data";

        $total_page_view = $this->basic->get_data($table,$where,$select='');

        $total_unique_visitor = $this->basic->get_data($table,$where,$select='',$join='',$limit='',$start='',$order_by='',$group_by='cookie_value');


        $select = array("count(id) as session_number","last_scroll_time","last_engagement_time");
        $total_unique_session = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start='',$order_by='',$group_by='session_value');

        $bounce = 0;
        $no_bounce = 0;
        foreach($total_unique_session as $value){
            if($value['session_number'] > 1)
                $no_bounce++;
            if($value['session_number'] == 1){
                if($value['last_scroll_time']=="0000-00-00 00:00:00" && $value['last_engagement_time']=="0000-00-00 00:00:00")
                    $bounce++;
                else
                    $no_bounce++;
            }
        }
        $bounce_no_bounce = $bounce+$no_bounce;
        if($bounce_no_bounce == 0)
            $bounce_rate = 0;
        else
            $bounce_rate = number_format($bounce*100/$bounce_no_bounce, 2);

        $select = array(
            "date_time as stay_from",
            "last_engagement_time",
            "last_scroll_time"
            );
        $stay_time_info = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start='',$order_by='',$group_by='');


        $total_stay_time = 0;
        if(!empty($stay_time_info)) {
            foreach($stay_time_info as $value){
                $total_stay_time_individual = 0;
                if($value['last_scroll_time']=='0000-00-00 00:00:00' && $value['last_engagement_time']=='0000-00-00 00:00:00')
                    $total_stay_time = $total_stay_time + $total_stay_time_individual;
                else if ($value['last_scroll_time']=='0000-00-00 00:00:00' && $value['last_engagement_time']!='0000-00-00 00:00:00'){
                    $total_stay_time_individual = strtotime($value['last_engagement_time']) - strtotime($value['stay_from']);
                    $total_stay_time = $total_stay_time + $total_stay_time_individual;
                }
                else if ($value['last_scroll_time']!='0000-00-00 00:00:00' && $value['last_engagement_time']=='0000-00-00 00:00:00'){
                   $total_stay_time_individual = strtotime($value['last_scroll_time']) - strtotime($value['stay_from']);
                   $total_stay_time = $total_stay_time + $total_stay_time_individual;
                }
                else {
                    if($value['last_scroll_time']>$value['last_engagement_time']){
                       $total_stay_time_individual = strtotime($value['last_scroll_time']) - strtotime($value['stay_from']);
                       $total_stay_time = $total_stay_time + $total_stay_time_individual;
                    }
                    else{
                       $total_stay_time_individual = strtotime($value['last_engagement_time']) - strtotime($value['stay_from']);  
                       $total_stay_time = $total_stay_time + $total_stay_time_individual;
                    }
                }
            }
        }


        $average_stay_time = 0;
        if($total_stay_time != 0)
            $average_stay_time = $total_stay_time/count($total_unique_session);

        $hours = 0;
        $minutes = 0;
        $seconds = 0;

        $hours = floor($average_stay_time / 3600);
        $minutes = floor(($average_stay_time / 60) % 60);
        $seconds = $average_stay_time % 60;        

        // end of average stay time
    

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-7 days"));
        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";
        $table = "visitor_analysis_domain_list_data";


        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "user_id" => $this->user_id
            );

        $select=array("date_format(date_time,'%Y-%m-%d') as date_test","session_value","GROUP_CONCAT(referrer SEPARATOR ',') as referrer","GROUP_CONCAT(visit_url SEPARATOR ',') as visit_url_str");

        $traffic_source_info = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start='',$order_by='',$group_by='session_value');

        // echo $this->db->last_query(); exit();

        $daily_traffic_source_info = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start='',$order_by='',$group_by='session_value,date_test');

        
        $search_engine_array = array('Baidu','Bing','DuckDuckGo','Ecosia','Exalead','Gigablast','Google','Munax','Qwant','Sogou','Soso.com','Yahoo','Yandex','Youdao','FAROO','YaCy','DeeperWeb','Dogpile','Excite','HotBot','Info.com','Mamma','Metacrawler','Mobissimo','Otalo','Skyscanner','WebCrawler','Accoona','Ansearch','Biglobe','Daum','Egerin','Leit.is','Maktoob','Miner.hu','Najdi.si','Naver','Onkosh','Rambler','Rediff','SAPO','Search.ch','Sesam','Seznam','Walla!','Yandex.ru','ZipLocal');
        $social_network_array = array('Twitter','Facebook','Xing','Renren','plus.Google','Disqus','Linkedin Pulse','Snapchat','Tumblr','Pintarest','Twoo','MyMFB','Instagram','Vine','WhatsApp','vk.com','Meetup','Secret','Medium','Youtube','Reddit');


        $search_link_count = 0;
        $social_link_count = 0;
        $referrer_link_count = 0;
        $direct_link_count = 0;

        $k = 0;
        $referrer_info = array();
        $search_engine_info = array();
        $social_network_info = array();
        $referrer_name = array();

        foreach($traffic_source_info as $value){
            $referrer_array = array();
            if($value['referrer'] != ''){
                $referrer_array = explode(',', $value['referrer']);
                $visit_url = explode(',', $value['visit_url_str']);           
            }

            if(empty($referrer_array)){
                $direct_link_count++;

                if(isset($referrer_info['direct_link']))
                    $referrer_info['direct_link']++;
                else
                   $referrer_info['direct_link'] = 1;
            }
            else{
                $first_part_of_domain_array = array();
                $first_index_of_referrer = get_domain_only($referrer_array[0]);
                $first_index_of_url = get_domain_only($visit_url[0]);
                /** creating referrer info array with count **/
                for($i=0;$i<count($referrer_array);$i++){                    
                    
                    if($first_index_of_referrer != $first_index_of_url && $referrer_array[0] != ''){
                        if(isset($referrer_info[$referrer_array[$i]]))
                            $referrer_info[$referrer_array[$i]]++;
                        else 
                            $referrer_info[$referrer_array[$i]] = 1;
                    }
                    $only_domain_name = get_domain_only($referrer_array[$i]);
                    $first_part_of_domain_array[] = $only_domain_name; 
                    
                } // end of for loop

                if($first_index_of_referrer == $first_index_of_url){
                    $direct_link_count++;
                    if(isset($referrer_info['direct_link']))
                        $referrer_info['direct_link']++;
                    else
                       $referrer_info['direct_link'] = 1;
                }
                if($referrer_array[0] == ''){
                    $direct_link_count++;
                    if(isset($referrer_info['direct_link']))
                        $referrer_info['direct_link']++;
                    else
                       $referrer_info['direct_link'] = 1;
                } 


                $count_search_engine = array();
                $count_social_network = array();
                /** for social network and search engine array creation and counter **/
                for($i=0;$i<count($first_part_of_domain_array);$i++){

                    for($j=0;$j<count($search_engine_array);$j++){
                        $occurance_search_engine = stripos($first_part_of_domain_array[$i], $search_engine_array[$j]);
                        if($occurance_search_engine !== FALSE){
                            if(isset($search_engine_info[$search_engine_array[$j]])){
                                $search_engine_info[$search_engine_array[$j]]++;
                                $count_search_engine[] = $search_engine_array[$j];
                            }
                            else{
                                $search_engine_info[$search_engine_array[$j]] = 1;
                                $count_search_engine[] = $search_engine_array[$j];
                            }
                        }
                    } // end of for loop
                    
                    for($k=0;$k<count($social_network_array);$k++){
                        $occurance_social_network = stripos($first_part_of_domain_array[$i], $social_network_array[$k]);
                        if($occurance_social_network !== FALSE){
                            if(isset($social_network_info[$social_network_array[$k]])){
                                $social_network_info[$social_network_array[$k]]++;
                                $count_social_network[] = $social_network_array[$k];
                            }
                            else{
                                $social_network_info[$social_network_array[$k]] = 1;
                                $count_social_network[] = $social_network_array[$k];
                            }
                        }
                    } // end of for loop

                } // end of for loop

                if(!empty($count_search_engine)){
                    $search_link_count = $search_link_count + count($count_search_engine);
                }
                if(!empty($count_social_network)){
                    $social_link_count = $social_link_count + count($count_social_network);
                }
                if(empty($count_search_engine) && empty($count_social_network)){
                    if($first_index_of_referrer != $first_index_of_url && $first_index_of_referrer != '')
                        $referrer_link_count = $referrer_link_count + count($first_part_of_domain_array);
                }

            }

        }

        

        $day_wise_search_link_count = 0;
        $day_wise_social_link_count = 0;
        $day_wise_referrer_link_count = 0;
        $day_wise_direct_link_count = 0;

        //for daily report section
        $visit_url = array();
        foreach($daily_traffic_source_info as $value){
            $referrer_array = array();
            if(isset($value['referrer'])){
                $referrer_array = explode(',', $value['referrer']);
                $empty_referrer_array = array_filter($referrer_array);
                $empty_referrer_array = array_values($empty_referrer_array);

                $visit_url = explode(',', $value['visit_url_str']);
            }

            if(empty($empty_referrer_array)){

                $day_wise_direct_link_count++;
                if(isset($daily_report[$value['date_test']]['direct_link_count']))
                    $daily_report[$value['date_test']]['direct_link_count'] = $daily_report[$value['date_test']]['direct_link_count'] + $day_wise_direct_link_count;
                else
                    $daily_report[$value['date_test']]['direct_link_count'] = $day_wise_direct_link_count;
                $day_wise_direct_link_count = 0;

            }
            else{
                $first_part_of_domain_array = array();
                for($i=0;$i<count($referrer_array);$i++){
                    $only_domain_name = get_domain_only($referrer_array[$i]);
                    $first_part_of_domain_array[] = $only_domain_name;  
                }

                $first_index_of_referrer = get_domain_only($referrer_array[0]);
                $first_index_of_url = get_domain_only($visit_url[0]);
                if($first_index_of_referrer == $first_index_of_url){
                    $day_wise_direct_link_count++;
                    if(isset($daily_report[$value['date_test']]['direct_link_count']))
                        $daily_report[$value['date_test']]['direct_link_count'] = $daily_report[$value['date_test']]['direct_link_count'] + $day_wise_direct_link_count;
                    else
                       $daily_report[$value['date_test']]['direct_link_count'] = $day_wise_direct_link_count;
                   $day_wise_direct_link_count = 0;
                }
                if($referrer_array[0] == ''){
                    $day_wise_direct_link_count++;
                    if(isset($daily_report[$value['date_test']]['direct_link_count']))
                        $daily_report[$value['date_test']]['direct_link_count'] = $daily_report[$value['date_test']]['direct_link_count'] + $day_wise_direct_link_count;
                    else
                       $daily_report[$value['date_test']]['direct_link_count'] = $day_wise_direct_link_count;
                   $day_wise_direct_link_count = 0;
                }

                $count_search_engine = array();
                $count_social_network = array();

                for($i=0;$i<count($first_part_of_domain_array);$i++){

                    for($j=0;$j<count($search_engine_array);$j++){
                        $occurance_search_engine = stripos($first_part_of_domain_array[$i], $search_engine_array[$j]);
                        if($occurance_search_engine !== FALSE){
                            $count_search_engine[] = $search_engine_array[$j];
                        }
                    }
                    
                    for($k=0;$k<count($social_network_array);$k++){
                        $occurance_social_network = stripos($first_part_of_domain_array[$i], $social_network_array[$k]);
                        if($occurance_social_network !== FALSE){
                            $count_social_network[] = $social_network_array[$k];
                        }
                    }

                }                

                if(!empty($count_search_engine)){
                    $day_wise_search_link_count = $day_wise_search_link_count + count($count_search_engine);
                    if(isset($daily_report[$value['date_test']]['search_link_count']))
                        $daily_report[$value['date_test']]['search_link_count'] = $daily_report[$value['date_test']]['search_link_count'] + $day_wise_search_link_count;
                    else
                        $daily_report[$value['date_test']]['search_link_count'] = $day_wise_search_link_count;
                    $day_wise_search_link_count = 0;
                }
                if(!empty($count_social_network)){
                    $day_wise_social_link_count = $day_wise_social_link_count + count($count_social_network);
                    if(isset($daily_report[$value['date_test']]['social_link_count']))
                        $daily_report[$value['date_test']]['social_link_count'] = $daily_report[$value['date_test']]['social_link_count'] + $day_wise_social_link_count;
                    else
                        $daily_report[$value['date_test']]['social_link_count'] = $day_wise_social_link_count;
                    $day_wise_social_link_count = 0;
                }
                if(empty($count_search_engine) && empty($count_social_network)) {
                    if($first_index_of_referrer != $first_index_of_url && $first_index_of_referrer != ''){

                        $day_wise_referrer_link_count = $day_wise_referrer_link_count + count($first_part_of_domain_array);
                        if(isset($daily_report[$value['date_test']]['referrer_link_count']))
                            $daily_report[$value['date_test']]['referrer_link_count'] = $daily_report[$value['date_test']]['referrer_link_count'] + $day_wise_referrer_link_count;
                        else
                            $daily_report[$value['date_test']]['referrer_link_count'] = $day_wise_referrer_link_count;
                        $day_wise_referrer_link_count = 0;
                    }
                }

            }
        }

        $dDiff = strtotime($to_date) - strtotime($from_date);
        $no_of_days = floor($dDiff/(60*60*24));
        $line_char_data_search = array();
        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($daily_report[$day_count])){
                if(isset($daily_report[$day_count]['direct_link_count']))
                    $line_char_data_search[$i]['direct_link'] = $daily_report[$day_count]['direct_link_count'];
                else
                    $line_char_data_search[$i]['direct_link'] = 0;

                if(isset($daily_report[$day_count]['search_link_count']))
                    $line_char_data_search[$i]['search_link'] = $daily_report[$day_count]['search_link_count'];
                else
                    $line_char_data_search[$i]['search_link'] = 0;

                if(isset($daily_report[$day_count]['social_link_count']))
                    $line_char_data_search[$i]['social_link'] = $daily_report[$day_count]['social_link_count'];
                else
                    $line_char_data_search[$i]['social_link'] = 0;

                if(isset($daily_report[$day_count]['referrer_link_count']))
                    $line_char_data_search[$i]['referrer_link'] = $daily_report[$day_count]['referrer_link_count'];
                else
                    $line_char_data_search[$i]['referrer_link'] = 0;
            } else {
                $line_char_data_search[$i]['direct_link'] = 0;
                $line_char_data_search[$i]['search_link'] = 0;
                $line_char_data_search[$i]['social_link'] = 0;
                $line_char_data_search[$i]['referrer_link'] = 0;
            }
            $line_char_data_search[$i]['date'] = date('Y-m-d', strtotime($from_date. " + $i days"));
        }
        
        //End Of 7 days Visitor From Search Engine And Direct 
        

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));


        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";
        $table = "visitor_analysis_domain_list_data";


        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "user_id" => $this->user_id
            );

        $select=array("GROUP_CONCAT(is_new SEPARATOR ',') as new_vs_returning");
        $total_new_returning = $this->basic->get_data($table,$where,$select,$join="",$limit='',$start='',$order_by='',$group_by='cookie_value,session_value');


        $new_or_returning = array();
        $new_user = 0;
        $returning_user = 0;
        foreach($total_new_returning as $value){
            $new_or_returning = explode(',', $value['new_vs_returning']);
            if(in_array(1, $new_or_returning)) $new_user++;
            else $returning_user++;
        }

        $info['total_new_returning_labels'] = array($this->lang->line('New Users'),$this->lang->line('Returning Users'));
        $info['total_new_returning_values'] = array($new_user,$returning_user);

        $select=array("date_format(date_time,'%Y-%m-%d') as date","GROUP_CONCAT(is_new SEPARATOR ',') as new_vs_returning");
        $daily_total_new_returning = $this->basic->get_data($table,$where,$select,$join="",$limit='',$start='',$order_by='',$group_by='cookie_value,session_value,date');


        $daily_report = array();
        $new_or_returning = array();
        $new_user = 0;
        $returning_user = 0;
        $i = 0;
        foreach($daily_total_new_returning as $value){
            $daily_report[$value['date']]['date'] = $value['date'];

            $new_or_returning = explode(',', $value['new_vs_returning']);                
            if(in_array(1, $new_or_returning)){
                if(isset($daily_report[$value['date']]['new_user'])){
                    $daily_report[$value['date']]['new_user']=$daily_report[$value['date']]['new_user']+1;
                }
                else{
                   $daily_report[$value['date']]['new_user'] = 1; 
                }
            } 
            else {
                if(isset($daily_report[$value['date']]['returning_user']))
                    $daily_report[$value['date']]['returning_user']=$daily_report[$value['date']]['returning_user']+1;
                else{
                   $daily_report[$value['date']]['returning_user'] = 1;
                }
            }
        }

        $dDiff = strtotime($to_date) - strtotime($from_date);
        $no_of_days = floor($dDiff/(60*60*24));
        $line_char_data_new_vs_returning = array();

        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($daily_report[$day_count])){
                if(isset($daily_report[$day_count]['new_user']))
                    $line_char_data_new_vs_returning[$i]['new_user'] = $daily_report[$day_count]['new_user'];
                else
                    $line_char_data_new_vs_returning[$i]['new_user'] = 0;

                if(isset($daily_report[$day_count]['returning_user']))
                    $line_char_data_new_vs_returning[$i]['returning_user'] = $daily_report[$day_count]['returning_user'];
                else
                    $line_char_data_new_vs_returning[$i]['returning_user'] = 0;

            } else {
                $line_char_data_new_vs_returning[$i]['new_user'] = 0;
                $line_char_data_new_vs_returning[$i]['returning_user'] = 0;                
            }
            $line_char_data_new_vs_returning[$i]['date'] = date('Y-m-d', strtotime($from_date. " + $i days"));
        }
       // End Of 30 Days New Vs Returning Users
       
        $table = "visitor_analysis_domain_list_data";

        $where = array();
        $where['where'] = array(
            "user_id" => $this->user_id
            );

        $select = array("GROUP_CONCAT(session_value SEPARATOR ',') as sessions","browser_name","GROUP_CONCAT(device SEPARATOR ',') as device");
        $this->db->group_by(array('browser_name'));
        $browser_report = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='');

        $top_bowser_array = array();

        $i = 0;
        foreach($browser_report as $value){
            $device_type = array();
            $sessions = array();
            
            $device_type = explode(',', $value['device']);
            $device_type = array_filter($device_type);
            $device_type = array_values($device_type);
            $device_type = array_count_values($device_type);
            $desktop = isset($device_type['Desktop']) ? $device_type['Desktop'] : 0;
            $mobile = isset($device_type['Mobile']) ? $device_type['Mobile'] : 0;
            $percentage_info = $this->get_percentage($desktop,$mobile);

            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);

            $top_bowser_array[$i]['sessions_count'] = $sessions;
            $top_bowser_array[$i]['browser_name'] = $value['browser_name'];
            $top_bowser_array[$i]['desktop'] = $desktop;
            $top_bowser_array[$i]['desktop_percentage'] = $percentage_info[0];
            $top_bowser_array[$i]['mobile'] = $mobile;
            $top_bowser_array[$i]['mobile_percentage'] = $percentage_info[1];

            $i++;

        }

        function callback($a, $b)
        {
          if ( $a['sessions_count'] == $b['sessions_count'] )
            return 0;

          return ( $a['sessions_count'] > $b['sessions_count'] ) ? -1 : 1;
        }

        usort($top_bowser_array, 'callback');
        $top5_browser = array_slice($top_bowser_array, 0, 5);
        // End Top 5 Browser


        $table = "visitor_analysis_domain_list_data";


        $where = array();
        $where['where'] = array(
            "user_id" => $this->user_id
            );
        $select = array("GROUP_CONCAT(session_value SEPARATOR ',') as sessions","GROUP_CONCAT(is_new SEPARATOR ',') as new_user","country");
        $country_name = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='country');

        $i = 0;
        $country_list = $this->get_country_names();  
        $top5_country = array();

        foreach($country_name as $value){
            $sessions = array();
           
            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);

            $top5_country[$i]['session_count'] = $sessions;
            

            $country_flag = array_search(trim($value["country"]), $country_list); 

            if($value['country'] == '' || !isset($value['country'])){
                $image_link = '';     
                $value['country'] = "Unknown";
            }

            $image_link = base_url()."assets/images/flags/".$country_flag.".png";
            $top5_country[$i]['country_flag'] = $image_link;
            $top5_country[$i]['country_name']  = $value['country'];
            $i++;
          
        }
        
        function callbackbrowser($a, $b)
        {
          if ( $a['session_count'] == $b['session_count'] )
            return 0;

          return ( $a['session_count'] > $b['session_count'] ) ? -1 : 1;
        }
        usort($top5_country, 'callbackbrowser');
        $top5_country_final = array_slice($top5_country, 0, 5);


        //End Top Browser
        
        $table = "visitor_analysis_domain_list_data";
        $where = array();
        $where['where'] = array(
            "user_id" => $this->user_id
            );

        $select = array("GROUP_CONCAT(session_value SEPARATOR ',') as sessions","GROUP_CONCAT(is_new SEPARATOR ',') as new_user","os");
        $os_report = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='os');

        $top5_os = array();
        $i = 0;
        foreach($os_report as $value){
            $sessions = array();
            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);
            $top5_os[$i]['session_count'] = $sessions;
            if ($value['os'] == '-') {
                $value['os'] = 'Unknown';
            }
            $top5_os[$i]['os_name'] = $value['os'];
            $i++;

        }

        function callbackos($a, $b)
        {
          if ( $a['session_count'] == $b['session_count'] )
            return 0;

          return ( $a['session_count'] > $b['session_count'] ) ? -1 : 1;
        }
        
        usort($top5_os, 'callbackos');
        $top5_os_final = array_slice($top5_os, 0, 6);

        $data['top5_os']  = $top5_os_final;

        $data['top5_country'] = $top5_country_final;

        $data['top5_browser'] = $top5_browser;



        $data['new_vs_returning_dates'] = array_column($line_char_data_new_vs_returning, 'date');
        $data['thirty_days_new_user'] = array_column($line_char_data_new_vs_returning, 'new_user');
        $data['thirty_days_returning_user'] = array_column($line_char_data_new_vs_returning, 'returning_user');



        $data['traffic_line_chart_dates'] = array_column($line_char_data_search, 'date');
        $data['traffic_direct_link'] = array_column($line_char_data_search, 'direct_link');
        $data['traffic_search_link'] = array_column($line_char_data_search, 'search_link');

        // end of daily report section

        $data['seven_days_direct'] = $direct_link_count;
        $data['seven_days_search_engine'] = $search_link_count;

        $data['total_page_view'] = number_format(count($total_page_view));
        $data['total_unique_visitor'] = number_format(count($total_unique_visitor));
        $data['total_stay_time'] = $hours.":".$minutes.":".$seconds;
        $data['bounce_rate'] = $bounce_rate;
        $data['line_char_data_compare'] = array_values($line_char_data_compare);
        $data['body'] = 'dashboard/dashboard';
        $data['page_title'] = $this->lang->line('Dashboard');
        $this->_viewcontroller($data);
    }

    public function get_percentage($first_number, $second_number) {
        if($first_number == 0 && $second_number == 0)
            return [(float) 0, (float) 0];

        $total = (int) $first_number + (int) $second_number;
        
        $first_percent = ($first_number / $total) * 100;
        $second_percent = ($second_number / $total) * 100;
        
        return [(float) $first_percent, (float) $second_percent];
    }


 
}