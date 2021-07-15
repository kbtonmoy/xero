<?php
require_once("Home.php"); // loading home controller

class Link_analysis extends Home
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

        if($this->session->userdata('user_type') != 'Admin' && !in_array(7,$this->module_access))
        redirect('home/login_page', 'location'); 
    }

    public function index()
    {
        $this->link_analysis();
    }

    public function link_analysis()
    {
        $data['body'] = 'link_analysis/link_analysis';
        $data['page_title'] = $this->lang->line("Link Analyzer");
        $this->_viewcontroller($data);
    }

    public function analysis_new()
    {
       $data['body'] = 'link_analysis/link_analysis_new';
       $data['page_title'] = $this->lang->line("New Link Analyzer");
       $this->_viewcontroller($data); 
    }
    

    public function link_analysis_data()
    {

        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','url','external_link_count','internal_link_count','nofollow_count','do_follow_count','searched_at');
        $search_columns = array('url','searched_at');

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
                $where_simple["Date_Format(searched_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(searched_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") 
            $where_simple['link_analysis.url like'] = "%".$searching."%";

        $where_simple['link_analysis.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "link_analysis";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        for($i=0;$i<count($info);$i++)
        {  
         $info[$i]['searched_at'] = date("jS M y",strtotime($info[$i]["searched_at"]));
        }
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);

    }


    public function link_analysis_action()
    {

        //************************************************//
        $status=$this->_check_usage($module_id=7,$request=1);
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

        $this->load->library('web_common_report');
        $url=strip_tags($this->input->post('keyword', true));
         
      
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/link/link_analysis_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        
        /**Write header in csv file***/      
        
        $link_analysis_complete=0;
  

        $link_analysis_data=array();
        $link_analysis_data=$this->web_common_report->link_statistics($url);  
        $searched_at= date("Y-m-d H:i:s");

        $total_external_link=$link_analysis_data["external_link_count"];
        $total_internal_link=$link_analysis_data["internal_link_count"];
        $total_nofollow=$link_analysis_data["nofollow_count"];
        $total_dofollow=$link_analysis_data["do_follow_count"];
        $total_link=$total_external_link+$total_internal_link;

        $this->session->set_userdata('link_analysis_complete_search',0);
        $this->session->set_userdata('link_analysis_bulk_total_search',$total_link);

        $write_data=array();            
        $write_data[]="URL";            
        $write_data[]=$url;            
        $write_data[]="Total Link";            
        $write_data[]=$total_link;            
        $write_data[]="External Link Count";            
        $write_data[]=$total_external_link;            
        $write_data[]="Internal Link Count";            
        $write_data[]=$total_internal_link;            
        $write_data[]="DoFollow Count";                
        $write_data[]=$total_dofollow;            
        $write_data[]="NoFollow Count";                
        $write_data[]=$total_nofollow;   

        fputcsv($download_path, $write_data);




        $str="<div class='card'>
                      <div class='card-header'>
                        <h4><i class='fas fa-anchor'></i> ".$this->lang->line("Link Analyzer")."</h4>
                        <div class='card-header-action'>
                          <div class='badges'>
                            <a  class='btn btn-primary float-right' href='".base_url()."/download/link/link_analysis_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
                          </div>                    
                        </div>
                      </div>";
        $str.="<div class='card-body'>
                   
                    <ul class='list-group'>";  

        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Total Links")." <span class='badge badge-primary badge-pill'>".$total_link."</span></li>";

        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("External Links")." <span class='badge badge-primary badge-pill'>".$total_external_link."</span></li>";

        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("Internal Links")." <span class='badge badge-primary badge-pill'>".$total_internal_link."</span></li>";

        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("DoFollow Links")." <span class='badge badge-primary badge-pill'>".$total_dofollow."</span></li>";

        $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line("NoFollow Links")." <span class='badge badge-primary badge-pill'>".$total_nofollow."</span></li>";

        $str.="</ul></div></div>";

        $write_data=array();            
        $write_data[]="Links"; 
        $write_data[]="Type"; 
        $write_data[]="DoFollow/NoFollow"; 
        fputcsv($download_path, $write_data);


        $str.="<div class='card'>
                      <div class='card-header'>
                        <h4><i class='fas fa-anchor'></i> ".$this->lang->line("Internal Links")."</h4>
                        <div class='card-header-action'>
                          <div class='badges'>
                            <a  class='btn btn-primary float-right' href='".base_url()."/download/link/link_analysis_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
                          </div>                    
                        </div>
                      </div>";
        if(count($link_analysis_data["internal_link"])==0)  
        $str.="<h6 class='text-center'>".$this->lang->line("No Data Found:")."</h6>";

        $str.="<div class='card-body'>
                <h6 class='text-center'>".$this->lang->line("Internal Links")."</h6>
                    <ul class='list-group'>"; 



        $count=0;
        foreach ($link_analysis_data["internal_link"] as $key => $value) 
        {
            $count++;
            $write_data=array();            
            $write_data[]=$value["link"]; 
            $write_data[]="Internal"; 
            $write_data[]=$value["type"]; 
            fputcsv($download_path, $write_data); 

            if($value["type"]=="dofollow")
                $dofollow_nofollow="<span class='badge badge-success badge-pill'>".$value['type']."</span>";
            else 
                $dofollow_nofollow="<span class='badge badge-danger badge-pill'>".$value['type']."</span>";


            $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$value["link"]." ".$dofollow_nofollow."</li>";

            $link_analysis_complete++;
            $this->session->set_userdata("link_analysis_complete_search",$link_analysis_complete);   
        }
        $str.="</ul></div></div>";
        

        $str.="<div class='card'>
                      <div class='card-header'>
                        <h4><i class='fas fa-anchor'></i> ".$this->lang->line("External Links")."</h4>
                        <div class='card-header-action'>
                          <div class='badges'>
                            <a  class='btn btn-primary float-right' href='".base_url()."/download/link/link_analysis_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
                          </div>                    
                        </div>
                      </div>";
        if(count($link_analysis_data["external_link"])==0)  
            $str.="<h6 class='text-center'>".$this->lang->line("No Data Found:")."</h6>";

        $str.="<div class='card-body'>
                <h6 class='text-center'>".$this->lang->line("External Links")."</h6>
                    <ul class='list-group'>"; 


        $count=0;
        foreach ($link_analysis_data["external_link"] as $key => $value) 
        {
            $count++;
            $write_data=array();            
            $write_data[]=$value["link"]; 
            $write_data[]="External"; 
            $write_data[]=$value["type"]; 
            fputcsv($download_path, $write_data); 

            if($value["type"]=="dofollow")
                $dofollow_nofollow="<span class='badge badge-success badge-pill'>".$value['type']."</span>";
            else 
                $dofollow_nofollow="<span class='badge badge-danger badge-pill'>".$value['type']."</span>";


            $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$value["link"]." ".$dofollow_nofollow."</li>";

            $link_analysis_complete++;
            $this->session->set_userdata("link_analysis_complete_search",$link_analysis_complete);  
        }
        $str.="</ul></div></div>";


        /** Insert into database ***/

        $insert_data=array
        (
            'user_id'                           => $this->user_id,
            'searched_at'                       => $searched_at,
            'url'                               => $url,
            'external_link_count'               => $total_external_link,
            'internal_link_count'               => $total_internal_link,
            'nofollow_count'                    => $total_nofollow,
            'do_follow_count'                   => $total_dofollow,
            'external_link'                     => json_encode($link_analysis_data["external_link"]),
            'internal_link'                     => json_encode($link_analysis_data["internal_link"]),
            'searched_at'                       => $searched_at
        );

               
        if($this->basic->insert_data('link_analysis', $insert_data)){
            //******************************//
            // insert data to useges log table
            $this->_insert_usage_log($module_id=7,$request=1);   
            //******************************//
            
            echo $str;     
        }
       

    }

  

    public function link_analysis_download()
    {
        $all=$this->input->post("ids");
        $table = 'link_analysis';
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
        $fp = fopen("download/link/link_analysis_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        
        $write_data=array();            
        $write_data[]="URL"; 
        $write_data[]="Links"; 
        $write_data[]="Type"; 
        $write_data[]="DoFollow/NoFollow"; 
        fputcsv($fp, $write_data);


        foreach ($info as $row) 
        {
            
            $internal_json_data=json_decode($row["internal_link"],true);
            foreach ($internal_json_data as $key => $value) 
            {              
                $write_data=array();            
                $write_data[]=$row["url"]; 
                $write_data[]=$value["link"]; 
                $write_data[]="Internal"; 
                $write_data[]=$value["type"]; 
                fputcsv($fp, $write_data);  
            }

            $external_json_data=json_decode($row["external_link"],true);
            foreach ($external_json_data as $key => $value) 
            {                
                $write_data=array();            
                $write_data[]=$row["url"]; 
                $write_data[]=$value["link"]; 
                $write_data[]="External"; 
                $write_data[]=$value["type"]; 
                fputcsv($fp, $write_data);                
            }       
                
        }

        fclose($fp);
        $file_name = "download/link/link_analysis_{$this->user_id}_{$download_id}.csv";
       
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }


    

    public function link_analysis_delete()
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
        $this->db->delete('link_analysis');
    }

   
    public function bulk_link_analysis_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('link_analysis_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('link_analysis_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }

 

   

}
