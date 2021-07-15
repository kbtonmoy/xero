<?php

require_once("Home.php"); // loading home controller

class Expired_domain extends Home
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

        if($this->session->userdata('user_type') != 'Admin' && !in_array(5,$this->module_access))
        redirect('home/login_page', 'location'); 
    }
	
	
	public function index()
    {
        $this->expired_domain();
    }

    public function expired_domain()
    {
        $data['body'] = 'domain_analysis/auction';
        $data['page_title'] = $this->lang->line("Auction Domain List");
        $this->_viewcontroller($data);
    }
    

    public function expired_domain_data()
    {

        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','domain_name','auction_type','auction_end_date','sync_at');
        $search_columns = array('domain_name','sync_at');

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
                $where_simple["Date_Format(sync_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(sync_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") 
            $where_simple['expired_domain_list.domain_name like'] = "%".$searching."%";

        //$where_simple['expired_domain_list.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "expired_domain_list";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        // for($i=0;$i<count($info);$i++)
        // {  
        //  $info[$i]['scraped_time'] = date("jS M y",strtotime($info[$i]["scraped_time"]));
        //  $info[$i]['owner_email'] = "<div style='min-width:100px !important;' class='text-muted text-center'>".$info[$i]['owner_email']."</div>";;
        // }
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }



    public function expired_domain_download()
    {
        $all=$this->input->post("ids");
        $table = 'expired_domain_list';
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

        $info = $this->basic->get_data($table, $where, $select ='', $join='', $limit='', $start=null, $order_by='id asc');
        $download_id=$this->session->userdata('download_id');
        $fp = fopen("download/expired_domain/expired_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));

        $head=array("Domain", "Auction Type", "Auction End Date", "Sync At");
                    
        fputcsv($fp, $head);

        foreach ($info as  $value) 
        {
        	$write_info = array();
            $write_info[] = $value['domain_name'];
            $write_info[] = $value['auction_type'];
            $write_info[] = $value['auction_end_date'];
            $write_info[] = $value['sync_at'];
            // $write_info[] = $value['page_rank'];
            // $write_info[] = $value['google_index'];
            // $write_info[] = $value['yahoo_index'];
            // $write_info[] = $value['bing_index'];
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/expired_domain/expired_{$this->user_id}_{$download_id}.csv";
       
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }


    

  	


	
}
