<?php
require_once("Home.php"); // loading home controller

class Keyword_position_tracking extends Home
{

    public $user_id;    
    public $download_id;    
    
    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');       
        
        $this->load->library('Web_common_report');
        $this->user_id=$this->session->userdata('user_id');
        $this->download_id=$this->session->userdata('download_id');
        set_time_limit(0);

        $this->important_feature();

        $this->member_validity();

        if($this->session->userdata('user_type') != 'Admin' && !in_array(16,$this->module_access))
        redirect('home/login_page', 'location'); 
    }
	
	
	public function index()
    {
        $this->keyword_list();
    }

    public function keyword_tracking_settings_action()
    {   
        $responses = array();

        //************************************************//
        $status=$this->_check_usage($module_id=16,$request=1);
        if($status=="2") 
        {
            $responses['status'] = "2";
            $responses['msg'] = $this->lang->line("sorry, your bulk limit is exceeded for this module.");

            echo json_encode($responses);
            exit;
        }
        else if($status=="3") 
        {
            $responses['status'] = "3";
            $responses['msg'] = $this->lang->line("sorry, your monthly limit is exceeded for this module.");

            echo json_encode($responses);
            exit;
        }
        //************************************************//

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        $post = $_POST;
        foreach ($post as $key => $value) 
        {
            $$key = trim(strip_tags($this->input->post($key,TRUE)));
        }
       

        $data = array(
            'keyword' => $keyword,
            'website' => $website,
            'language' => $language,
            'country' => $country,
            'user_id' => $this->user_id,
            'add_date' => date("Y-m-d H:i:s"),
            'deleted' => '0'
        );

        if($this->basic->insert_data("keyword_position_set",$data))
        {
            //******************************//
            // insert data to useges log table
            $this->_insert_usage_log($module_id=16,$request=1);   
            //******************************//

            $responses['status'] = "1";
            $responses['msg'] = $this->lang->line("Keyword has been successfully added.");
            echo json_encode($responses);

        } else 
        {
            $responses['status'] = "0";
            $responses['msg'] = $this->lang->line("Something went wrong, please try once again.");                 
            echo json_encode($responses);                  
        }
    }

    public function keyword_list()
    {
        $data['body'] = "keyword_position_tracking/keyword_list";
        $data['page_title'] = $this->lang->line("Keyword Tracking Lists");
        $data['country_name'] = $this->get_country_names();
        $data['language_name'] = $this->get_language_names();

        $where['where'] = array('user_id'=>$this->user_id);
        $number_of_keyword = $this->basic->get_data("keyword_position_set",$where);
        $data['number_of_keyword'] = count($number_of_keyword);
        $this->_viewcontroller($data);
    }

    public function keyword_list_data()
    {
        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','keyword','website','country','language','add_date','actions');
        $search_columns = array('keyword','add_date');

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
                $where_simple["Date_Format(add_date,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(add_date,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") {
            $where_simple['keyword like'] = "%".$searching."%";
        }

        $where_simple['user_id'] = $this->user_id;
        $where  = array('where'=>$where_simple);

        $table = "keyword_position_set";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        for($i=0;$i<count($info);$i++)
        {  
            $info[$i]['add_date'] = date("M j, y h:i A",strtotime($info[$i]["add_date"]));
            $info[$i]['actions'] = "<a href='#' title='".$this->lang->line("Delete Keyword")."' class='btn btn-circle btn-outline-danger delete_keyword' table_id=".$info[$i]["id"]."><i class='fa fa-trash-alt'></i></a>";
        
        }
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }

    public function delete_keyword_action()
    {
        $this->ajax_check();

        $table_id = $this->input->post("table_id",true);

        $where = array("id"=>$table_id,"user_id"=>$this->user_id);

        if($this->basic->delete_data("keyword_position_set",$where)) {
            $this->basic->delete_data("keyword_position_report",array("keyword_id"=>$table_id));
            echo "1";
        } else {
            echo "0";
        }
    }

    public function delete_selected_keyword_action()
    {
        $this->ajax_check();

        $selected_keyword_data = $this->input->post('info', true);

        if(!is_array($selected_keyword_data)) {
            $selected_keyword_data = array();
        }

        $implode_ids = implode(",",$selected_keyword_data);

        if(!empty($selected_keyword_data)) {

            $final_sql = "DELETE FROM keyword_position_set WHERE user_id={$this->user_id} AND id IN({$implode_ids})";

            foreach ($selected_keyword_data as $value) {
                $this->basic->delete_data("keyword_position_report", array("keyword_id" => $value));
            }

            $this->db->query($final_sql);

            if($this->db->affected_rows() > 0) {
                echo "1";
            } else {
                echo "0";
            }

        }
    }

    public function keyword_position_report()
    {
        $data['body'] = "keyword_position_tracking/keyword_position_report";
        $data['page_title'] = $this->lang->line("Keyword Position Report");
        $where['where'] = array('user_id' => $this->user_id);
        $keywords = $this->basic->get_data("keyword_position_set",$where);
        $keywords_array = array();
        foreach($keywords as $value){
            $keywords_array[$value['id']] = $value['keyword']." | ".$value['website'];
        }

        $data['keywords'] = $keywords_array;
        $this->_viewcontroller($data);
    }

    public function keyword_position_report_data()
    {
        $keyword = $this->input->post("keyword");
        $from_date = $this->input->post("from_date");
        $to_date = $this->input->post("to_date");

        $where['where'] = array(
            "keyword_id" => $keyword,
            "date >=" => date("Y-m-d",strtotime($from_date)),
            "date <=" => date("Y-m-d",strtotime($to_date))
            );
        $join = array("keyword_position_set" => "keyword_position_report.keyword_id=keyword_position_set.id,left");

        $keyword_position = $this->basic->get_data("keyword_position_report",$where,$select="",$join);

        $str = '<div class="table-responsive">
                    <table class="table table-hover table-bordered text-left">
                        <thead>
                            <tr>
                                <th>'.$this->lang->line('Keyword').'</th>
                                <th>'.$this->lang->line('Website').'</th>
                                <th>'.$this->lang->line('Google Position').'</th>
                                <th>'.$this->lang->line('Bing Position').'</th>
                                <th>'.$this->lang->line('Yahoo Position').'</th>
                                <th>'.$this->lang->line('Date').'</th>
                            </tr>
                        </thead><tbody>';
        if(count($keyword_position) > 0) {
            foreach($keyword_position as $value){
                $str .= '<tr>
                            <td>'.$value['keyword'].'</td>
                            <td>'.$value['website'].'</td>
                            <td>'.$value['google_position'].'</td>
                            <td>'.$value['bing_position'].'</td>
                            <td>'.$value['yahoo_position'].'</td>
                            <td>'.date("M j, Y", strtotime($value['date'])).'</td>
                        </tr>';
            }
        } else {
            $str .= '<tr><td class="text-center" colspan="6">'.$this->lang->line('No Data found').'</td></tr>';
        }

        $str .= '</tbody></table></div>';

        echo $str;
    }
	
}
?>