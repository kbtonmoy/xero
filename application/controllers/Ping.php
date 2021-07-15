<?php

require_once("Home.php"); // loading home controller

class Ping extends Home
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

        if($this->session->userdata('user_type') != 'Admin' && !in_array(9,$this->module_access))
        redirect('home/login_page', 'location'); 
    }

    public function index()
    {
        $this->ping_website();
    }

    public function ping_website()
    {
        $data['body'] = 'backlink_ping/ping/website_ping';
        $data['page_title'] = $this->lang->line('Website Ping');
        $this->_viewcontroller($data);
    }

    public function website_ping()
    {
        $data['body'] = 'backlink_ping/ping/ping_new';
        $data['page_title'] = $this->lang->line('Website Ping');
        $this->_viewcontroller($data);
    }
    

    public function ping_website_data()
    {

        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','blog_name','ping_url','blog_url','blog_url_to_ping','blog_rss_feed_url','response','ping_at');
        $search_columns = array('blog_name','ping_at');

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
                $where_simple["Date_Format(ping_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(ping_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") $where_simple['website_ping.blog_name like'] = "%".$searching."%";

        $where_simple['website_ping.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "website_ping";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');


        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function ping_website_action()
    {
        $this->load->library('web_common_report');

        $blog_name=$this->input->post('blog_name', true);
        $blog_url=$this->input->post('blog_url', true);
        $blog_url_to_ping=$this->input->post('blog_url_to_ping', true);
        $blog_rss_feed_url=$this->input->post('blog_rss_feed_url', true);
                 
        $all_pink_link=$this->web_common_report->ping_link; 
        $total_url=count($all_pink_link); // no of backlink url       

        $this->session->set_userdata('ping_website_bulk_total_search',$total_url);
        $this->session->set_userdata('ping_website_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/ping/ping_website_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        $total_count=0;
        
        /**Write header in csv file***/
        $write_data=array();            
        $write_data[]="Blog Name";            
        $write_data[]="Blog URL";            
        $write_data[]="Blog URL to Ping";            
        $write_data[]="Blog RSS Feed URL";               
        $write_data[]="Ping URL";               
        $write_data[]="Response";               
        $write_data[]="Ping at";               
        
        fputcsv($download_path, $write_data);
        
        $ping_website_complete=0;

        $str = "<div class='card'>
        <div class='card-header'>
          <h4><i class='fas fa-anchor'></i> ".$this->lang->line("Website Ping")."</h4>
            <div class='card-header-action'>
              <div class='badges'>
                <a  class='btn btn-primary float-right' href='".base_url()."/download/ping/ping_website_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
              </div>                    
            </div>
        </div>
        <div class='card-body'>
          <div class='table-responsive'>
            <table class='table table-bordered table-hover'>
              <tbody>
                <tr>";
        $str.="<th>".$this->lang->line("SL")."</th>"; 
        $str.="<th>".$this->lang->line("Blog Name")."</th>"; 
        $str.="<th>".$this->lang->line("Blog URL")."</th>"; 
        $str.="<th>".$this->lang->line("Blog URL to Ping")."</th>"; 
        $str.="<th>".$this->lang->line("Blog RSS Feed URL")."</th>"; 
        $str.="<th>".$this->lang->line("Ping URL")."</th>"; 
        $str.="<th>".$this->lang->line("Response")."</th>";
        $str.="</tr>";  

        $count=0;

        foreach($all_pink_link as $link)
        {
            $response_array=$this->web_common_report->ping_url($blog_name,$blog_url,$blog_url_to_ping,$blog_rss_feed_url,$link);
            $ping_at= date("Y-m-d H:i:s");
              
            $response="";
            if(is_array($response_array) && array_key_exists('message',$response_array)) 
            $response=$response_array["message"];


            $write_data=array();
            $write_data[]=$blog_name;
            $write_data[]=$blog_url;
            $write_data[]=$blog_url_to_ping;               
            $write_data[]=$blog_rss_feed_url;
            $write_data[]=$response;
            $write_data[]=$link;
            $write_data[]=$ping_at;

        
            fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
   
            $insert_data=array
            (
                'user_id'           => $this->user_id,
                'ping_at'           => $ping_at,
                'blog_name'         => $blog_name,
                'blog_url'          => $blog_url,
                'blog_url_to_ping'  => $blog_url_to_ping,
                'blog_rss_feed_url' => $blog_rss_feed_url,
                'ping_url'          => $link,
                'response'          => $response
            );

            $count++;

            $str.=
            "<tr>
            <td>".$count."</td>
            <td>".$blog_name."</td>
            <td>".$blog_url."</td>
            <td>".$blog_url_to_ping."</td>
            <td>".$blog_rss_feed_url."</td>
            <td>".$link."</td>
            <td>".$response."</td>
            </tr>";
            
            $this->basic->insert_data('website_ping', $insert_data);    
            $ping_website_complete++;
            $this->session->set_userdata("ping_website_complete_search",$ping_website_complete);    
        }  
        $str.="</tbody></table></div></div></div>";  


        echo $str;      

    }

  

    public function ping_website_download()
    {
        $all=$this->input->post("ids");
        $table = 'website_ping';
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
        $fp = fopen("download/ping/ping_website_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));


        $head=array("Blog Name","Blog URL","Blog URL to Ping","Blog RSS Feed URL","Ping URL","Response","Ping at");
                    
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) 
        {
         
            $write_info['blog_name'] = $value['blog_name'];
            $write_info['blog_url'] = $value['blog_url'];
            $write_info['blog_url_to_ping'] = $value['blog_url_to_ping'];
            $write_info['blog_rss_feed_url'] = $value['blog_rss_feed_url'];
            $write_info['ping_url'] = $value['ping_url'];
            $write_info['response'] = $value['response'];
            $write_info['ping_at'] = $value['ping_at'];
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/ping/ping_website_{$this->user_id}_{$download_id}.csv";
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }


    

    public function ping_website_delete()
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
        $this->db->delete('website_ping');
    }


    
    public function bulk_ping_website_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('ping_website_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('ping_website_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }

  

}
