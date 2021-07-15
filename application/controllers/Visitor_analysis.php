<?php

require_once("Home.php"); // loading home controller

/**
* @category controller
* class Admin
*/

class Visitor_analysis extends Home
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
        $this->upload_path = realpath(APPPATH . '../upload');
        $this->user_id=$this->session->userdata('user_id');
        set_time_limit(0);

        $this->important_feature();
        $this->periodic_check();

        $this->member_validity();

        if($this->session->userdata('user_type') != 'Admin' && !in_array(1,$this->module_access))
        redirect('home/login_page', 'location'); 
         
        $query = 'SET SESSION group_concat_max_len = 9999999999999999999';
        $this->db->query($query);

        if(function_exists('ini_set')){
            ini_set('memory_limit', '-1');
        }
    }


    public function index(){
        $this->domain_list();      
    }
    

    public function domain_list()
    {
        $data['body'] = 'visitor_analysis/domain_list';
        $data['page_title'] = $this->lang->line("visitor analysis");
        $this->_viewcontroller($data);
    }

    public function domain_list_visitor_data()
    {
        $this->ajax_check();

        $domain_name   = trim($this->input->post("domain_name",true));
        $display_columns = array('#','id','domain_name','domain_code','js_code','actions');

        $database_columns = array('#','id','domain_name','domain_code','js_code','actions');

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 1;
        $sort = isset($database_columns[$sort_index]) ? $database_columns[$sort_index] : 'id';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;

        $where_simple=array();
        if($domain_name !="") $where_simple['domain_name like'] = "%".$domain_name."%";
        $where_simple['user_id'] = $this->user_id;
        $where = array('where'=>$where_simple);
        $table = "visitor_analysis_domain_list";
        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by);
        $total_rows_array = $this->basic->count_row($table, $where, $count="id", $join='');
        $total_result = $total_rows_array[0]['total_rows'];
        $new_info = array();

        for($i=0;$i<count($info);$i++) 
        {
            $new_info[$i]['id'] = $info[$i]['id'];
            $new_info[$i]['user_id'] = $info[$i]['user_id'];
            $new_info[$i]['domain_name'] = $info[$i]['domain_name'];
            $new_info[$i]['domain_code'] = $info[$i]['domain_code'];
            $new_info[$i]['js_code'] = "not shown in the grid";
            
            $action_width = (5*47)+20;
            $new_info[$i]['actions'] = '<div class="dropdown d-inline dropright">
            <button class="btn btn-outline-primary dropdown-toggle no_caret" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-briefcase"></i></button>
            <div class="dropdown-menu mini_dropdown text-center" style="width:'.$action_width.'px !important">';
            $new_info[$i]["actions"] .= "<a href='#' class='btn btn-circle btn-outline-info get_js_code' data-toggle='tooltip' data-original-title='".$this->lang->line('Get JS Code')."' table_id='".$info[$i]['id']."'><i class='fas fa-code'></i></a>&nbsp;<a class='btn btn-circle btn-outline-success' data-toggle='tooltip' data-original-title='".$this->lang->line('View Details')."' href='".base_url('visitor_analysis/domain_details/').$info[$i]['id']."'><i class='fas fa-eye'></i></a>&nbsp;<a href='#' class='btn btn-circle btn-outline-danger delete_template' data-toggle='tooltip' data-original-title='".$this->lang->line('Delete Domain')."' table_id='".$info[$i]['id']."'><i class='fa fa-trash'></i></a>";
            if ($info[$i]['dashboard'] == '1') {
            
                    $new_info[$i]["actions"] .= "&nbsp;<a href='#' table_id='".$info[$i]['id']."' data-toggle='tooltip' data-original-title='".$this->lang->line('Remove from dashboard')."' data-placement='top' dashboard='0'  class='btn btn-circle btn-primary show_in_dashboard'><i class='fas fa-check'></i></a>";
            }
            else{
                    $new_info[$i]["actions"] .= "&nbsp;<a href='#' table_id='".$info[$i]['id']."' data-toggle='tooltip' data-original-title='".$this->lang->line('Show on your dashboard')."' data-placement='top' dashboard='1' class='btn btn-circle btn-outline-primary show_in_dashboard'><i class='fas fa-check'></i></a>";
            }
            $new_info[$i]['actions'] .= "&nbsp;<a href='#' class='btn btn-circle btn-outline-danger delete_30_days_data' data-toggle='tooltip' data-original-title='".$this->lang->line('Delete data except last 30 days')."' table_id='".$info[$i]['id']."'><i class='fas fa-eraser'></i></a>";
            $new_info[$i]['actions'] .= "</div></div><script>$('[data-toggle=\"tooltip\"]').tooltip();</script>";
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($new_info, $display_columns, $start, $primary_key="id");

        echo json_encode($data);

    }


    public function add_domain_action()
    {
        $this->ajax_check();
        $given_domain_name = strip_tags(strtolower($this->input->post('domain_name', true)));
        $domain_name = get_domain_only($given_domain_name);
        $where = array();
        $where['where'] = array("user_id" => $this->user_id, 'domain_name' => $domain_name);
        $domain_exist = $this->basic->get_data('visitor_analysis_domain_list',$where,'id');
        if(!empty($domain_exist))
        {
            echo '<div class="alert alert-danger text-center"><i class="fas fa-times-circle"></i> '.$this->lang->line('You have already added this domain for tracking.').'</div>';
            exit;
        }

        //************************************************//
        $status=$this->_check_usage($module_id=1,$request=1);
        if($status=="2") 
        {
            echo '<div class="alert alert-danger text-center"><i class="fas fa-times-circle"></i> '.$this->lang->line("Module limit is over.").'</div>';
            exit();
        }
        else if($status=="3") 
        {
            echo '<div class="alert alert-danger text-center"><i class="fas fa-times-circle"></i> '.$this->lang->line("Module limit is over.").'</div>';
            exit();
        }
        //************************************************//

        $this->db->trans_start(); 

        $random_num = $this->_random_number_generator().time()."-".$this->user_id;
        $js_code = '<script id="xero-domain-name" xero-data-name="'.$random_num.'" type="text/javascript" src="'.site_url().'js_controller/client"></script>';
        $js_code=htmlspecialchars($js_code);

        $data = array(
            'user_id' => $this->user_id,
            'domain_name' => $domain_name,
            'domain_code' => $random_num,
            'js_code' => $js_code,
            'add_date' => date("Y-m-d")
            );        

        $this->basic->insert_data('visitor_analysis_domain_list',$data);

        //******************************//
        // insert data to useges log table
        $this->_insert_usage_log($module_id=1,$request=1);   
        //******************************//
        $this->db->trans_complete();

        if($this->db->trans_status() === false){
            echo '<div class="alert alert-danger text-center"><i class="fas fa-times-circle"></i> '.$this->lang->line("Something went wrong, please try again.").'</div>';
        } else {
            $str = '
            <div class="card">
                <div class="card-header">
                  <h4><i class="fas fa-copy"></i> '.$this->lang->line('Confirmation').'</h4>
                </div>
                <div class="card-body">
                  <p>'.$this->lang->line('Domain has been added successfully for visitor analysis. Please copy the below code and paste it to your website that you want to track.').'</p>
                  <pre class="language-javascript">
                    <code class="dlanguage-javascript copy_code">
'.$js_code.'</code>
                  </pre>
                </div>
            </div>
            ';
            $str .='
                <script>
                    $(document).ready(function() {
                        Prism.highlightAll();
                        $(".toolbar-item").find("a").addClass("copy");

                        $(document).on("click", ".copy", function(event) {
                            event.preventDefault();

                            $(this).html("'.$this->lang->line('Copied!').'");
                            var that = $(this);
                            
                            var text = $(this).prev("code").text();
                            var temp = $("<input>");
                            $("body").append(temp);
                            temp.val(text).select();
                            document.execCommand("copy");
                            temp.remove();

                            setTimeout(function(){
                              $(that).html("'.$this->lang->line('Copy').'");
                            }, 2000); 

                        });
                    });
                </script>
                ';
            echo $str;
        }
    }


    public function domain_details($id=0)
    {
        $data['id'] = $id;
        $info = $this->basic->get_data('visitor_analysis_domain_list',['where'=>['id'=>$id,'user_id'=>$this->user_id]]);
        if(!empty($info))
            $data['body'] = 'visitor_analysis/domain_details';
        else
            $data['body'] = 'visitor_analysis/no_data';
        $data['page_title'] = $this->lang->line("visitor analysis report");;
        $this->_viewcontroller($data);
    }


    public function ajax_get_overview_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $date_range = $this->input->post('date_range', TRUE);
        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $where = array();
        $where['where'] = array('id'=>$domain_id, 'user_id'=>$this->user_id);
        $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select="");
        $table = "visitor_analysis_domain_list_data";
        // this domain name will be placed for all the pages of visitor analysis tab
        $info['domain_name'] = $domain_info[0]['domain_name'];

        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "domain_list_id" => $domain_id
            );
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

        // code for average stay time
        //"if(status='1',count(book_info.id),0) as available_book"
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

        // code for line chart
        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "is_new" => 1,
            "domain_list_id" => $domain_id
            );
        $select = array(
            "date_format(date_time,'%Y-%m-%d') as date",
            "count(id) as number_of_user"
            );
        $day_wise_visitor = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start='',$order_by='',$group_by="date");

        $day_count = date('Y-m-d', strtotime($from_date. " + 1 days"));


        foreach ($day_wise_visitor as $value){
            $day_wise_info[$value['date']] = $value['number_of_user'];
        }

        $dDiff = strtotime($to_date) - strtotime($from_date);
        $no_of_days = floor($dDiff/(60*60*24));
        $line_char_data = array();
        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($day_wise_info[$day_count])){
                $line_char_data[$i]['user'] = $day_wise_info[$day_count];
            } else {
                $line_char_data[$i]['user'] = 0;
            }
            $line_char_data[$i]['date'] = date('d M Y', strtotime($from_date. " + $i days"));
        }
        // end of code for line chart

        $info['line_chart_dates'] = array_column($line_char_data, 'date');
        $info['line_chart_values'] = array_column($line_char_data, 'user');

        $max1 = (!empty($info['line_chart_values'])) ? max($info['line_chart_values']) : 0;
        $steps = round($max1/7);
        if($steps==0) $steps = 1;
        $info['step_count'] = $steps;

        $info['total_page_view'] = number_format(count($total_page_view));
        $info['total_unique_visitor'] = number_format(count($total_unique_visitor));
        $info['total_unique_session'] = number_format(count($total_unique_session));
        if(count($total_unique_visitor) == 0)
            $info['average_visit'] = number_format(count($total_page_view));
        else
            $info['average_visit'] = number_format(count($total_page_view)/count($total_unique_visitor), 2);

        $info['average_stay_time'] = $hours.":".$minutes.":".$seconds;
        $info['bounce_rate'] = $bounce_rate." %";
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);
    }

    public function ajax_get_traffic_source_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $date_range = $this->input->post('date_range', TRUE);
        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $where = array();
        $where['where'] = array('id' => $domain_id);
        $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select="");
        $table = "visitor_analysis_domain_list_data";
        $info['domain_name'] = $domain_info[0]['domain_name'];

        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "domain_list_id" => $domain_id
            );

        $select=array("date_format(date_time,'%Y-%m-%d') as date_test","session_value","GROUP_CONCAT(referrer SEPARATOR ',') as referrer","GROUP_CONCAT(visit_url SEPARATOR ',') as visit_url_str");

        $traffic_source_info = $this->basic->get_data($table,array('where'=>array('domain_list_id'=>$domain_id)),$select,$join='',$limit='',$start='',$order_by='',$group_by='session_value');

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

        // for top five referrer section
        $total_referrers = $direct_link_count+$search_link_count+$social_link_count+$referrer_link_count;
        $top_referrer = asort($referrer_info);
        $top_referrer = array_reverse($referrer_info);
        $top_referrer_keys = array_keys($top_referrer);
        $top_referrer_values = array_values($top_referrer);
        $no_of_top_referrer = 0;
        if(count($top_referrer)>5) $no_of_top_referrer = 5;
        else $no_of_top_referrer = count($top_referrer);

        $color_array = array("#44B3C2", "#F1A94E", "#E45641", "#5D4C46", "#7B8D8E");
        $top_five_referrer = array();
        for($i=0;$i<$no_of_top_referrer;$i++){
            $top_five_referrer[$i]['value'] = number_format($top_referrer_values[$i]*100/$total_referrers,2);
            $top_five_referrer[$i]['color'] = $color_array[$i];
            $top_five_referrer[$i]['highlight'] = $color_array[$i];
            if($top_referrer_keys[$i] == 'direct_link')
                $link_name = "Direct Link";
            else $link_name = $top_referrer_keys[$i];
            $top_five_referrer[$i]['label'] = $link_name;
        }
        $info['top_referrer_present_value'] = array_column($top_five_referrer, 'value');
        $info['top_referrer_present_label'] = array_column($top_five_referrer, 'label');
        // $info['top_referrer_data'] = $top_five_referrer;
        //end of top five referrer section

        //section for search engine info
        $search_engine_info_keys = array_keys($search_engine_info);
        $search_engine_info_values = array_values($search_engine_info);
        $search_engine_color = array("#44B3C2","#F1A94E","#E45641","#5D4C46","#7B8D8E","#F2EDD8","#BCCF3D","#BCCF3D","#82683B","#B6A754","#D79C8C");
        $j = 0;
        $search_engine_result = array();
        $search_engine_names = array();
        for($i=0;$i<count($search_engine_info);$i++){
            $search_engine_result[$i]['value'] = $search_engine_info_values[$i];
            $search_engine_result[$i]['color'] = $search_engine_color[$j];
            $search_engine_result[$i]['highlight'] = $search_engine_color[$j];
            $search_engine_result[$i]['label'] = $search_engine_info_keys[$i];

            // $search_engine_names[$i]['name'] = $search_engine_info_keys[$i];
            array_push($search_engine_names, $search_engine_info_keys[$i]);
            $j++;
            if($j == 10) $j=0;
        }

        $info['search_engine_colors'] = array_column($search_engine_result, 'color');
        $info['search_engine_labels'] = array_column($search_engine_result, 'label');
        $info['search_engine_values'] = array_column($search_engine_result, 'value');
        // end of search engine info

        
        //social network info
        $social_network_info_keys = array_keys($social_network_info);
        $social_network_info_values = array_values($social_network_info);
        $social_network_color = array_reverse(array("#000066","#FFFFCC","#CCCCFF","#990066","#003399","#CCFFCC","#0099CC","#FF0080","#800080","#D79C8C"));
        $j = 0;
        $social_network_result = array();
        $social_network_names = array();
        for($i=0;$i<count($social_network_info);$i++){
            $social_network_result[$i]['value'] = $social_network_info_values[$i];
            $social_network_result[$i]['color'] = $social_network_color[$j];
            $social_network_result[$i]['highlight'] = $social_network_color[$j];
            $social_network_result[$i]['label'] = $social_network_info_keys[$i];

            $social_network_names[$i]['name'] = $social_network_info_keys[$i];
            $social_network_names[$i]['color'] = $social_network_color[$j];
            $j++;
            if($j == 10) $j=0;
        }

        $info['social_network_colors'] = array_column($social_network_result, 'color');
        $info['social_network_labels'] = array_column($social_network_result, 'label');
        $info['social_network_values'] = array_column($social_network_result, 'value');

        // end of social network info

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
        $line_char_data = array();
        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($daily_report[$day_count])){
                if(isset($daily_report[$day_count]['direct_link_count']))
                    $line_char_data[$i]['direct_link'] = $daily_report[$day_count]['direct_link_count'];
                else
                    $line_char_data[$i]['direct_link'] = 0;

                if(isset($daily_report[$day_count]['search_link_count']))
                    $line_char_data[$i]['search_link'] = $daily_report[$day_count]['search_link_count'];
                else
                    $line_char_data[$i]['search_link'] = 0;

                if(isset($daily_report[$day_count]['social_link_count']))
                    $line_char_data[$i]['social_link'] = $daily_report[$day_count]['social_link_count'];
                else
                    $line_char_data[$i]['social_link'] = 0;

                if(isset($daily_report[$day_count]['referrer_link_count']))
                    $line_char_data[$i]['referrer_link'] = $daily_report[$day_count]['referrer_link_count'];
                else
                    $line_char_data[$i]['referrer_link'] = 0;
            } else {
                $line_char_data[$i]['direct_link'] = 0;
                $line_char_data[$i]['search_link'] = 0;
                $line_char_data[$i]['social_link'] = 0;
                $line_char_data[$i]['referrer_link'] = 0;
            }
            $line_char_data[$i]['date'] = date('d M Y', strtotime($from_date. " + $i days"));
        }

        // $info['line_chart_data'] = $line_char_data;
        $info['traffic_line_chart_dates'] = array_column($line_char_data, 'date');
        $info['traffic_direct_link'] = array_column($line_char_data, 'direct_link');
        $info['traffic_search_link'] = array_column($line_char_data, 'search_link');
        $info['traffic_social_link'] = array_column($line_char_data, 'social_link');
        $info['traffic_referrer_link'] = array_column($line_char_data, 'referrer_link');
        $max1 = (!empty($info['traffic_direct_link'])) ? max($info['traffic_direct_link']) : 0;
        $max2 = (!empty($info['traffic_search_link'])) ? max($info['traffic_search_link']) : 0;
        $max3 = (!empty($info['traffic_social_link'])) ? max($info['traffic_social_link']) : 0;
        $max4 = (!empty($info['traffic_referrer_link'])) ? max($info['traffic_referrer_link']) : 0;
        $steps = round(max(array($max1,$max2,$max3,$max4))/7);
        if($steps==0) $steps = 1;
        $info['traffic_daily_line_step_count'] = $steps;
        // end of daily report section

        $info['traffic_bar_direct_link_count'] = $direct_link_count;
        $info['traffic_bar_search_link_count'] = $search_link_count;
        $info['traffic_bar_social_link_count'] = $social_link_count;
        $info['traffic_bar_referrer_link_count'] = $referrer_link_count;
        $total_traffic_different_source = array($direct_link_count,$search_link_count,$social_link_count,$referrer_link_count);
        $max1 = (!empty($total_traffic_different_source)) ? max($total_traffic_different_source) : 0;
        $steps = round($max1/7);
        if($steps==0) $steps = 1;
        $info['traffic_bar_step_count'] = $steps;

        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);
    }

    public function ajax_get_visitor_type_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $date_range = $this->input->post('date_range', TRUE);
        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $where = array();
        $where['where'] = array('id' => $domain_id);
        $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select="");
        $table = "visitor_analysis_domain_list_data";
        $info['domain_name'] = $domain_info[0]['domain_name'];

        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "domain_list_id" => $domain_id
            );

        $select=array("GROUP_CONCAT(is_new SEPARATOR ',') as new_vs_returning");
        $total_new_returning = $this->basic->get_data($table,array('where'=>array("domain_list_id" => $domain_id)),$select,$join="",$limit='',$start='',$order_by='',$group_by='cookie_value,session_value');


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
        $line_char_data = array();

        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($daily_report[$day_count])){
                if(isset($daily_report[$day_count]['new_user']))
                    $line_char_data[$i]['new_user'] = $daily_report[$day_count]['new_user'];
                else
                    $line_char_data[$i]['new_user'] = 0;

                if(isset($daily_report[$day_count]['returning_user']))
                    $line_char_data[$i]['returning_user'] = $daily_report[$day_count]['returning_user'];
                else
                    $line_char_data[$i]['returning_user'] = 0;

            } else {
                $line_char_data[$i]['new_user'] = 0;
                $line_char_data[$i]['returning_user'] = 0;                
            }
            $line_char_data[$i]['date'] = date('d M Y', strtotime($from_date. " + $i days"));
        }

        $info['new_vs_returning_dates'] = array_column($line_char_data, 'date');
        $info['new_vs_returning_new_user'] = array_column($line_char_data, 'new_user');
        $info['new_vs_returning_returning_user'] = array_column($line_char_data, 'returning_user');
        $max1 = (!empty($info['new_vs_returning_new_user'])) ? max($info['new_vs_returning_new_user']) : 0;
        $max2 = (!empty($info['new_vs_returning_returning_user'])) ? max($info['new_vs_returning_returning_user']) : 0;
        $steps = round(max(array($max1,$max2))/7);
        if($steps==0) $steps = 1;
        $info['new_vs_returning_step_count'] = $steps;


        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "domain_list_id" => $domain_id
            );

        $select = array("count(id) as total_view","visit_url");
        $content_overview_data = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='total_view desc',$group_by='visit_url');

        $total_view = 0;
        foreach($content_overview_data as $value){
            $total_view = $total_view+$value['total_view'];
        }

        $top_url = '';
        $i = 0;
        foreach($content_overview_data as $value){
            $percentage = number_format($value['total_view']*100/$total_view, 2);
            $i++;
            $top_url .= $i.". ".$value['visit_url']." <span class='float-right'><b>".$percentage." %</b></span>";
            $top_url .= 
            '<div class="progress">                                         
              <div class="progress-bar progress-bar-striped " role="progressbar" aria-valuenow="'.$percentage.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$percentage.'%">
              </div>
            </div>';
            if($i==10) break;
        }

        $info['progress_bar_data'] = $top_url;
        

        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);
    }

    public function ajax_get_content_overview_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $date_range = $this->input->post('date_range', TRUE);
        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $where = array();
        $where['where'] = array('id' => $domain_id);
        $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select="");
        $table = "visitor_analysis_domain_list_data";
        $info['domain_name'] = $domain_info[0]['domain_name'];

        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "domain_list_id" => $domain_id
            );

        $select = array("count(id) as total_view","visit_url");
        $content_overview_data = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='total_view desc',$group_by='visit_url');

        $total_view = 0;
        foreach($content_overview_data as $value){
            $total_view = $total_view+$value['total_view'];
        }

        $top_url = '';
        $i = 0;
        foreach($content_overview_data as $value){
            $percentage = number_format($value['total_view']*100/$total_view, 2);
            $i++;
            $top_url .= $i.". ".$value['visit_url']." <span class='float-right'><b>".$percentage." %</b></span>";
            $top_url .= 
            '<div class="progress">                                         
              <div class="progress-bar progress-bar-striped " role="progressbar" aria-valuenow="'.$percentage.'" aria-valuemin="0" aria-valuemax="100" style="width:'.$percentage.'%">
              </div>
            </div>';
            if($i==10) break;
        }

        $info['progress_bar_data'] = $top_url;
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));
        echo json_encode($info);
    }

    public function ajax_get_country_wise_report_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $date_range = $this->input->post('date_range', TRUE);
        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $where = array();
        $where['where'] = array('id' => $domain_id);
        $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select="");
        $table = "visitor_analysis_domain_list_data";
        $info['domain_name'] = $domain_info[0]['domain_name'];

        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "domain_list_id" => $domain_id
            );
        $select = array('country',"GROUP_CONCAT(is_new SEPARATOR ',') as new_user");
        $country_name = $this->basic->get_data($table,array('where'=>array('domain_list_id'=>$domain_id)),$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='country');

        $i = 0;
        $country_report = array();
        $a = array('Country','New Visitor');
        $country_report[$i] = $a;
        foreach($country_name as $value){
            $new_users = array();
            $i++;
            $new_users = explode(',', $value['new_user']);
            $new_users = array_filter($new_users);
            $new_users = array_values($new_users);
            $new_users = count($new_users);
            $temp = array();
            $temp[] = $value['country'];
            $temp[] = $new_users;
            $country_report[$i] = $temp;
        }

        $info['country_graph_data'] = $country_report;
        
        $select = array("GROUP_CONCAT(session_value SEPARATOR ',') as sessions","GROUP_CONCAT(is_new SEPARATOR ',') as new_user","country");
        $browser_report = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='country');

        $country_report_str = "<table class='table table-sm'>
                                    <thead>
                                        <tr>
                                            <th>".$this->lang->line('Country Name')."</th>
                                            <th>".$this->lang->line('Sessions')."</th>
                                            <th>".$this->lang->line('New Users')."</th>
                                            <th>".$this->lang->line('Action')."</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                ";
        $country_list = $this->get_country_names();       
        $i = 0;
        foreach($browser_report as $value){
            $new_users = array();
            $sessions = array();
            $i++;
            $new_users = explode(',', $value['new_user']);
            $new_users = array_filter($new_users);
            $new_users = array_values($new_users);
            $new_users = count($new_users);

            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);


            $s_country = array_search(trim($value["country"]), $country_list); 
            $image_link = base_url()."assets/images/flags/".$s_country.".png";

            $image = '<img style="height: 15px; width: 20px; margin-top: -3px;" src="'.$image_link.'" alt=" "> &nbsp;';
            if($value['country'] == '' || !isset($value['country'])){
                $image = '';
                $value['country'] = "Unknown";
            }

            $country_report_str .= "<tr><td>".$image.$value['country']."</td><td>".$sessions."</td><td>".$new_users."</td><td><button class='country_wise_name btn btn-outline-info btn-circle' title='".$this->lang->line('Session Details')."' data='".$value['country']."'><i class='fas fa-binoculars'></i></button></td></tr>";

        }
        $country_report_str .= "</tbody></table>";
        $info['country_wise_table_data'] = $country_report_str;

        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);
    }

    public function ajax_get_individual_country_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $date_range = $this->input->post('date_range', TRUE);
        $country_name = $this->input->post('country_name', TRUE);

        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $table = "visitor_analysis_domain_list_data";

        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "country" => $country_name,
            "domain_list_id" => $domain_id
            );
        $select = array("GROUP_CONCAT(session_value SEPARATOR ',') as sessions","date_format(date_time,'%Y-%m-%d') as date");
        $country_daily_session = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='city');

        foreach($country_daily_session as $value){
            $sessions = array();
            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);
            $report[$value['date']]['sessions'] = $sessions;
        }

        $dDiff = strtotime($to_date) - strtotime($from_date);
        $no_of_days = floor($dDiff/(60*60*24));
        $line_char_data = array();

        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($report[$day_count])){
                $line_char_data[$i]['session'] = $report[$day_count]['sessions'];
            } else {
                $line_char_data[$i]['session'] = 0;               
            }
            $line_char_data[$i]['date'] = date('d M Y', strtotime($from_date. " + $i days"));
        }

        $info['country_daily_session_dates'] = array_column($line_char_data,'date');
        $info['country_daily_session_values'] = array_column($line_char_data,'session');
        $max1 = (!empty($info['country_daily_session_values'])) ? max($info['country_daily_session_values']) : 0;
        $steps = round($max1/7);
        if($steps==0) $steps = 1;
        $info['country_daily_session_steps'] = $steps;
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));



        $where_version = array();
        $where_version['where'] = array(
            'country' => $country_name,
            "domain_list_id" => $domain_id
            );
        $select = array("GROUP_CONCAT(session_value SEPARATOR ',') as sessions","GROUP_CONCAT(is_new SEPARATOR ',') as new_user","country","city");
        $country_city = array();
        $country_city = $this->basic->get_data($table,$where_version,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='city');

        $country_city_str = "<table class='table table-sm'>
                                    <thead>
                                        <tr>
                                            <th>City Name</th>
                                            <th>Sessions</th>
                                            <th>New Users</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                ";
        // $country_list_individual = $this->get_country_names();       
        $i = 0;
        foreach($country_city as $value){
            $new_users = array();
            $sessions = array();
            $i++;
            $new_users = explode(',', $value['new_user']);
            $new_users = array_filter($new_users);
            $new_users = array_values($new_users);
            $new_users = count($new_users);

            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);

            // $s_country = array_search(trim(strtoupper($value["country"])), $country_list_individual); 
            // $image_link = base_url()."assets/images/flags/".$s_country.".png";

            $country_city_str .= "<tr><td>".$value['city']."</td><td>".$sessions."</td><td>".$new_users."</td></tr>";

        }
        $country_city_str .= "</tbody></table>";

        $info['country_city_str'] = $country_city_str;

        echo json_encode($info);
    }

    public function ajax_get_browser_report_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $date_range = $this->input->post('date_range', TRUE);

        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $where = array();
        $where['where'] = array('id' => $domain_id);
        $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select="");
        $table = "visitor_analysis_domain_list_data";
        $info['domain_name'] = $domain_info[0]['domain_name'];

        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "domain_list_id" => $domain_id
            );

        $select = array("GROUP_CONCAT(session_value SEPARATOR ',') as sessions","GROUP_CONCAT(is_new SEPARATOR ',') as new_user","browser_name");
        $browser_report = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='browser_name');

        $browser_report_str = "<table class='table table-sm'>
                                    <thead>
                                        <tr>
                                            <th>SL</th>
                                            <th>".$this->lang->line('Browser Name')."</th>
                                            <th>".$this->lang->line('Sessions')."</th>
                                            <th>".$this->lang->line('New Users')."</th>
                                            <th>".$this->lang->line('Action')."</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                ";        
        $i = 0;

        $browser_list = [
            'chrome' => 'assets/img/browser/chrome.png',
            'firefox' => 'assets/img/browser/firefox.png',
            'safari' => 'assets/img/browser/safari.png',
            'opera' => 'assets/img/browser/opera.png',
            'ie' => 'assets/img/browser/ie.png',
            'edge' => 'assets/img/browser/edge.png',
        ]; 

        foreach($browser_report as $value){
            $new_users = array();
            $sessions = array();
            $i++;
            $new_users = explode(',', $value['new_user']);
            $new_users = array_filter($new_users);
            $new_users = array_values($new_users);
            $new_users = count($new_users);

            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);

            $browser_name = strtolower($value['browser_name']);
            $browser_img_path = isset($browser_list[$browser_name]) ? $browser_list[$browser_name] : "assets/img/browser/other.png";

            $image = '<img style="height: 15px; width: 20px; margin-top: -3px;" src="'.base_url($browser_img_path).'" alt=" "> &nbsp;';

            $browser_report_str .= "<tr><td>".$i."</td><td>".$image.$value['browser_name']."</td><td>".$sessions."</td><td>".$new_users."</td><td><button class='browser_name btn btn-outline-info btn-circle' title='".$this->lang->line('Session Details')."' data='".$value['browser_name']."'><i class='fas fa-binoculars'></i></button></td></tr>";

        }
        $browser_report_str .= "</tbody></table>";

        $info['browser_report_name'] = $browser_report_str;
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));


        echo json_encode($info);
    }

    public function ajax_get_individual_browser_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $date_range = $this->input->post('date_range', TRUE);
        $browser_name = $this->input->post('browser_name', TRUE);

        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $table = "visitor_analysis_domain_list_data";

        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "browser_name" => $browser_name,
            "domain_list_id" => $domain_id
            );
        $select = array("GROUP_CONCAT(session_value SEPARATOR ',') as sessions","date_format(date_time,'%Y-%m-%d') as date");
        $browser_daily_session = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='date');
        foreach($browser_daily_session as $value){
            $sessions = array();
            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);
            $report[$value['date']]['sessions'] = $sessions;
        }

        $dDiff = strtotime($to_date) - strtotime($from_date);
        $no_of_days = floor($dDiff/(60*60*24));
        $line_char_data = array();

        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($report[$day_count])){
                $line_char_data[$i]['session'] = $report[$day_count]['sessions'];
            } else {
                $line_char_data[$i]['session'] = 0;               
            }
            $line_char_data[$i]['date'] = date('d M Y', strtotime($from_date. " + $i days"));
        }

        $info['browser_daily_session_dates'] = array_column($line_char_data, 'date');
        $info['browser_daily_session_values'] = array_column($line_char_data, 'session');
        $max1 = (!empty($info['browser_daily_session_values'])) ? max($info['browser_daily_session_values']) : 0;
        $steps = round($max1/7);
        if($steps==0) $steps = 1;
        $info['browser_daily_session_steps'] = $steps;
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));



        $where_version = array();
        $where_version['where'] = array(
            'browser_name' => $browser_name,
            "domain_list_id" => $domain_id
            );
        $select = array("GROUP_CONCAT(session_value SEPARATOR ',') as sessions","GROUP_CONCAT(is_new SEPARATOR ',') as new_user","browser_version","browser_name");
        $browser_versions = $this->basic->get_data($table,$where_version,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='browser_version');

        $browser_version_str = "<table class='table table-sm'>
                                <thead>
                                    <tr style='background:#0073B7;color:white'>
                                        <th>SL</th>
                                        <th>".$this->lang->line('Browser Name')."</th>
                                        <th>".$this->lang->line('Sessions')."</th>
                                        <th>".$this->lang->line('New Users')."</th>
                                    </tr>
                                </thead>
                                <tbody>
                                ";        
        $i = 0;
        foreach($browser_versions as $value){
            $new_users = array();
            $sessions = array();
            $i++;
            $new_users = explode(',', $value['new_user']);
            $new_users = array_filter($new_users);
            $new_users = array_values($new_users);
            $new_users = count($new_users);

            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);

            $browser_version_str .= "<tr><td>".$i."</td><td>".$value['browser_name']."</td><td>".$sessions."</td><td>".$new_users."</td></tr>";

        }
        $browser_version_str .= "</tbody></table>";

        $info['browser_version'] = $browser_version_str;

        echo json_encode($info);
    }



    public function ajax_get_os_report_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $date_range = $this->input->post('date_range', TRUE);

        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $where = array();
        $where['where'] = array('id' => $domain_id);
        $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select="");
        $table = "visitor_analysis_domain_list_data";
        $info['domain_name'] = $domain_info[0]['domain_name'];

        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "domain_list_id" => $domain_id
            );

        $select = array("GROUP_CONCAT(session_value SEPARATOR ',') as sessions","GROUP_CONCAT(is_new SEPARATOR ',') as new_user","os");
        $os_report = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='os');

        $os_report_str = "<table class='table table-sm'>
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>".$this->lang->line('OS Name')."</th>
                                        <th>".$this->lang->line('Sessions')."</th>
                                        <th>".$this->lang->line('New Users')."</th>
                                        <th>".$this->lang->line('Action')."</th>
                                    </tr>
                                </thead>
                                <tbody>
                                ";    
        $os_list = [
            'android' => 'assets/img/os/android.png',
            'ipad' => 'assets/img/os/ipad.png',
            'iphone' => 'assets/img/os/iphone.png',
            'linux' => 'assets/img/os/linux.png',
            'mac os x' => 'assets/img/os/mac.png',
            'search bot' => 'assets/img/os/search-bot.png',
            'windows' => 'assets/img/os/windows.png',
        ]; 

        $i = 0;
        foreach($os_report as $value){
            $new_users = array();
            $sessions = array();
            $i++;
            $new_users = explode(',', $value['new_user']);
            $new_users = array_filter($new_users);
            $new_users = array_values($new_users);
            $new_users = count($new_users);

            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);

            $os_name = strtolower($value['os']);
            $os_img_path = isset($os_list[$os_name]) ? $os_list[$os_name] : "assets/img/browser/other.png";
            $image = '<img style="height: 15px; width: 20px; margin-top: -3px;" src="'.base_url($os_img_path).'" alt=" "> &nbsp;';

            $os_report_str .= "<tr><td>".$i."</td><td>".$image.$value['os']."</td><td>".$sessions."</td><td>".$new_users."</td><td><button class='os_name btn btn-outline-info btn-circle' title='".$this->lang->line('Session Details')."' data='".$value['os']."'><i class='fas fa-binoculars'></i></button></td></tr>";

        }
        $os_report_str .= "</tbody></table>";
        $info['os_report_name'] = $os_report_str;
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);

    }


    public function ajax_get_individual_os_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $date_range = $this->input->post('date_range', TRUE);
        $os_name = $this->input->post('os_name', TRUE);

        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $table = "visitor_analysis_domain_list_data";

        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "os" => $os_name,
            "domain_list_id" => $domain_id
            );
        $select = array("GROUP_CONCAT(session_value SEPARATOR ',') as sessions","date_format(date_time,'%Y-%m-%d') as date");
        $browser_daily_session = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='date');
        foreach($browser_daily_session as $value){
            $sessions = array();
            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);
            $report[$value['date']]['sessions'] = $sessions;
        }

        $dDiff = strtotime($to_date) - strtotime($from_date);
        $no_of_days = floor($dDiff/(60*60*24));
        $line_char_data = array();

        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($report[$day_count])){
                $line_char_data[$i]['session'] = $report[$day_count]['sessions'];
            } else {
                $line_char_data[$i]['session'] = 0;               
            }
            $line_char_data[$i]['date'] = date('d M Y', strtotime($from_date. " + $i days"));
        }

        $info['os_daily_session_dates'] = array_column($line_char_data, 'date');
        $info['os_daily_session_values'] = array_column($line_char_data, 'session');
        $max1 = (!empty($info['os_daily_session_values'])) ? max($info['os_daily_session_values']) : 0;
        $steps = round($max1/7);
        if($steps==0) $steps = 1;
        $info['os_daily_session_steps'] = $steps;
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);
    }



    public function ajax_get_device_report_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $date_range = $this->input->post('date_range', TRUE);

        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $where = array();
        $where['where'] = array('id' => $domain_id);
        $domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select="");
        $table = "visitor_analysis_domain_list_data";
        $info['domain_name'] = $domain_info[0]['domain_name'];

        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "domain_list_id" => $domain_id
            );

        $select = array("GROUP_CONCAT(session_value SEPARATOR ',') as sessions","GROUP_CONCAT(is_new SEPARATOR ',') as new_user","device");
        $device_report = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='device');

        $device_report_str = "<table class='table table-sm'>
                                <thead>
                                    <tr>
                                        <th>SL</th>
                                        <th>".$this->lang->line('Device Name')."</th>
                                        <th>".$this->lang->line('Sessions')."</th>
                                        <th>".$this->lang->line('New Users')."</th>
                                        <th>".$this->lang->line('Action')."</th>
                                    </tr>
                                </thead>
                                <tbody>
                                ";  
        $device_list = [
            'mobile' => 'assets/img/os/iphone.png',
            'desktop' => 'assets/img/os/windows.png',
        ]; 

        $i = 0;
        foreach($device_report as $value){
            $new_users = array();
            $sessions = array();
            $i++;
            $new_users = explode(',', $value['new_user']);
            $new_users = array_filter($new_users);
            $new_users = array_values($new_users);
            $new_users = count($new_users);

            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);

            $device_name = strtolower($value['device']);
            $devise_img_path = isset($device_list[$device_name]) ? $device_list[$device_name] : "assets/img/browser/other.png";
            $image = '<img style="height: 15px; width: 20px; margin-top: -3px;" src="'.base_url($devise_img_path).'" alt=" "> &nbsp;';

            $device_report_str .= "<tr><td>".$i."</td><td>".$image.$value['device']."</td><td>".$sessions."</td><td>".$new_users."</td><td><button class='device_name btn btn-outline-info btn-circle' title='".$this->lang->line('Session Details')."' data='".$value['device']."'><i class='fas fa-binoculars'></i></button></td></tr>";

        }
        $device_report_str .= "</tbody></table>";

        $info['device_report_name'] = $device_report_str;
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);
    }


    public function ajax_get_individual_device_data()
    {
        $domain_id = $this->input->post('domain_id', TRUE);
        $date_range = $this->input->post('date_range', TRUE);
        $device_name = $this->input->post('device_name', TRUE);

        $from_and_to_date = array();
        if ($date_range != '') {
            $from_and_to_date = explode(" - ", $date_range);
        }

        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));

        if (!empty($from_and_to_date)) {
            $from_date = date("Y-m-d",strtotime($from_and_to_date[0]));
            $to_date = date("Y-m-d",strtotime($from_and_to_date[1]));
        }

        $to_date = $to_date." 23:59:59";
        $from_date = $from_date." 00:00:00";

        $table = "visitor_analysis_domain_list_data";

        $where = array();
        $where['where'] = array(
            "date_time >=" => $from_date,
            "date_time <=" => $to_date,
            "device" => $device_name,
            "domain_list_id" => $domain_id
            );
        $select = array("GROUP_CONCAT(session_value SEPARATOR ',') as sessions","date_format(date_time,'%Y-%m-%d') as date");
        $browser_daily_session = $this->basic->get_data($table,$where,$select,$join='',$limit='',$start=NULL,$order_by='',$group_by='date');
        foreach($browser_daily_session as $value){
            $sessions = array();
            $sessions = explode(',', $value['sessions']);
            $sessions = array_filter($sessions);
            $sessions = array_values($sessions);
            $sessions = array_unique($sessions);
            $sessions = count($sessions);
            $report[$value['date']]['sessions'] = $sessions;
        }

        $dDiff = strtotime($to_date) - strtotime($from_date);
        $no_of_days = floor($dDiff/(60*60*24));
        $line_char_data = array();

        for($i=0;$i<=$no_of_days+1;$i++){
            $day_count = date('Y-m-d', strtotime($from_date. " + $i days"));
            if(isset($report[$day_count])){
                $line_char_data[$i]['session'] = $report[$day_count]['sessions'];
            } else {
                $line_char_data[$i]['session'] = 0;               
            }
            $line_char_data[$i]['date'] = date('d M Y', strtotime($from_date. " + $i days"));
        }

        $info['device_daily_session_dates'] = array_column($line_char_data, 'date');
        $info['device_daily_session_values'] = array_column($line_char_data, 'session');
        $max1 = (!empty($info['device_daily_session_values'])) ? max($info['device_daily_session_values']) : 0;
        $steps = round($max1/7);
        if($steps==0) $steps = 1;
        $info['device_daily_session_steps'] = $steps;
        $info['from_date'] = date("d-M-y",strtotime($from_date));
        $info['to_date'] = date("d-M-y",strtotime($to_date));

        echo json_encode($info);
    }


    public function ajax_delete_domain()
    {
        if($this->is_demo == '1')
        {
            echo "error"; 
            exit();
        }

        $this->ajax_check();
        $id = $this->input->post('table_id',true);
        $info = $this->basic->get_data('visitor_analysis_domain_list',array('where'=>array('id'=>$id,'user_id'=>$this->user_id)));
        if(empty($info))
        {
            echo 'no_match';
            exit;
        }

        $this->db->trans_start();

        $this->basic->delete_data('visitor_analysis_domain_list',$where=array('id'=>$id));
        $this->basic->delete_data('visitor_analysis_domain_list_data',$where=array('domain_list_id'=>$id));
        $this->_delete_usage_log($module_id=1,$request=1);

        $this->db->trans_complete();
        if($this->db->trans_status() === false) {
            echo 'error';
        } else {
            echo 'success';
        }
    }

    public function ajax_delete_last_30_days_data()
    {
        $this->ajax_check();
        $id = $this->input->post('table_id',true);
        $info = $this->basic->get_data('visitor_analysis_domain_list',array('where'=>array('id'=>$id,'user_id'=>$this->user_id)));
        if(empty($info))
        {
            echo 'no_match';
            exit;
        }

        $this->db->trans_start();
        $to_date = date("Y-m-d H:i:s");
        $from_date = date("Y-m-d H:i:s",strtotime("$to_date-30 days"));
        $this->db->where('domain_list_id',$id);
        $this->db->where('date_time <',$from_date);
        // $this->db->or_where('last_scroll_time <',$from_date);
        // $this->db->or_where('last_engagement_time <',$from_date);
        $this->db->delete('visitor_analysis_domain_list_data');

        echo $this->db->last_query();
        exit;

        $this->db->trans_complete();
        if($this->db->trans_status() === false) {
            echo 'error';
        } else {
            echo 'success';
        }
    }

    public function get_js_code()
    {
        $this->ajax_check();
        $id = $this->input->post('table_id',true);
        $info = $this->basic->get_data('visitor_analysis_domain_list',array('where'=>array('id'=>$id,'user_id'=>$this->user_id)));
        if(empty($info))
        {
            $error_message = '
                        <div class="card" id="nodata">
                          <div class="card-body">
                            <div class="empty-state">
                              <img class="img-fluid" style="height: 200px" src="'.base_url('assets/img/drawkit/drawkit-nature-man-colour.svg').'" alt="image">
                              <h2 class="mt-0">'.$this->lang->line("We could not find any data.").'</h2>
                            </div>
                          </div>
                        </div>';
            echo $error_message;
        }
        else
        {
            $js_code = $info[0]['js_code'];
            $content='<div class="row">
                    <div class="col-12">';
            $content .= '
                        <div class="card">
                          <div class="card-header">
                            <h4><i class="fas fa-copy"></i> '.$this->lang->line("Copy the below code for further use.").'</h4>
                          </div>
                          <div class="card-body">
                            <pre class="language-javascript">
                                <code class="dlanguage-javascript copy_code">
'.$js_code.'
                                </code>
                            </pre>
                          </div>
                        </div>';
            $content .='</div>
                </div>
                <script>
                    $(document).ready(function() {
                        Prism.highlightAll();
                        $(".toolbar-item").find("a").addClass("copy");

                        $(document).on("click", ".copy", function(event) {
                            event.preventDefault();

                            $(this).html("'.$this->lang->line('Copied!').'");
                            var that = $(this);
                            
                            var text = $(this).prev("code").text();
                            var temp = $("<input>");
                            $("body").append(temp);
                            temp.val(text).select();
                            document.execCommand("copy");
                            temp.remove();

                            setTimeout(function(){
                              $(that).html("'.$this->lang->line('Copy').'");
                            }, 2000); 

                        });
                    });
                </script>
                ';
            echo $content;
        }
    }


    public function delete_previous_data($id=0)
    {
        $where_delete_table['where'] = array('id'=>$id);
        $table_name = $this->basic->get_data('domain',$where_delete_table,$select='');
        $table_name = $table_name[0]['table_name'];
        $to_date = date("Y-m-d");
        $from_date = date("Y-m-d",strtotime("$to_date-30 days"));
        $from_date = $from_date." 23:59:59";
        $where = array(
            'date_time <' => $from_date
            );
        if($this->basic->delete_data($table_name,$where)){
            $this->session->set_userdata('delete_success',1);
            redirect('domain_details_visitor/domain_list_visitor','Location');
        } else {
            $this->session->set_userdata('delete_error',1);
            redirect('domain_details_visitor/domain_list_visitor','Location');
        }
    }

    public function js_code_generator()
    {
        $random_num = $this->_random_number_generator();
        $str = '<script id="domain" data-name="'.$random_num.'" type="text/javascript" src="analytics_js/client.js"></script>';
        echo $str;
    }

    public function display_in_dashboard()
    {
        if($this->is_demo == '1')
        {
            $response['status'] = 'exist';
            $response['message'] = $this->lang->line("This feature is disabled in this demo.");
            echo json_encode($response);
            exit;
        }

        $this->ajax_check();
        $id = $_POST['table_id'];
        $response = array();
        $get_data = $this->basic->get_data('visitor_analysis_domain_list',array('where'=>array('user_id'=>$this->user_id,'id'=>$id)));

        if ($get_data[0]['dashboard'] == '1') {
           $this->basic->update_data("visitor_analysis_domain_list",array("user_id"=>$this->user_id,"id"=>$id),array("dashboard"=>'0'));
           $response['status'] = 'remove';
           $response['message'] = $this->lang->line("This domain has successfully been removed from your dashboard.");
           echo json_encode($response);
           exit;
        }
        else {
            $count_row=$this->basic->count_row("visitor_analysis_domain_list",array("where"=>array("user_id"=>$this->user_id,"dashboard"=>'1')));
            $count=isset($count_row[0]['total_rows']) ? $count_row[0]['total_rows'] : 0;

            if($count>=3) {   
                $response['status'] = 'exist';
                $response['message'] = $this->lang->line("You can not add more domain as you have already 3 domains on dashboard.");
                echo json_encode($response);
                exit;
            }
            else {   
                $response['status'] = 'not_exist';
                $response['message'] = $this->lang->line("This domain has successfully been added to your dashboard.");
                $this->basic->update_data("visitor_analysis_domain_list",array("user_id"=>$this->user_id,"id"=>$id),array("dashboard"=>'1'));
                echo json_encode($response);
                exit;
            }

        }



    }


}