<?php

require_once("Home.php"); // loading home controller

class Backlink extends Home
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
        $this->backlink_gererator();
    }

    public function backlink_gererator()
    {
        $data['body'] = 'backlink_ping/backlink/backlink_generator';
        $data['page_title'] = $this->lang->line('Backlink Generator');
        $this->_viewcontroller($data);
    }

    public function generator_backlink()
    {
        $data['body'] = 'backlink_ping/backlink/backlink_generator_new';
        $data['page_title'] = $this->lang->line('Backlink Generator');
        $this->_viewcontroller($data);
    }
    

    public function backlink_gererator_data()
    {

        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','url','domain_name','response_code','status','generated_at');
        $search_columns = array('domain_name','generated_at');

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
                $where_simple["Date_Format(generated_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(generated_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") $where_simple['backlink_generator.domain_name like'] = "%".$searching."%";

        $where_simple['backlink_generator.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "backlink_generator";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        for($i=0;$i<count($info);$i++)
        {   
           $info[$i]['generated_at'] = date("jS M y",strtotime($info[$i]["generated_at"]));
          if ($info[$i]['status'] == 'successful')
             $status ="<span><i class='fas fa-check-circle text-success'></i> ".$this->lang->line("Successful")."</span>";
          else
            $status ="<span><i class='fas fa-times text-danger'></i> ".$this->lang->line("Faild")."</span>";
          
         
         $info[$i]['status'] = $status;
        }


        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function backlink_gererator_action()
    {
        $this->load->library('web_common_report');
        $urls=$this->input->post('domain_name', true);
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);
        
        $back_link_url=$this->web_common_report->back_link_url; 
        $total_url=count($back_link_url); // no of backlink url
        $out_of=ceil($total_url*count($url_array));

        $this->session->set_userdata('backlink_gererator_bulk_total_search',$out_of);
        $this->session->set_userdata('backlink_gererator_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/backlink/backlink_gererator_{$this->user_id}_{$download_id}.csv", "w");
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF)); // unicode compatible csv
        $total_count=0;
        
        /**Write header in csv file***/
        $write_data[]="Backlink URL";            
        $write_data[]="Domain";            
        $write_data[]="Response Code";            
        $write_data[]="Status";               
        $write_data[]="Created At";               
        
        fputcsv($download_path, $write_data);
        
        $backlink_gererator_complete=0;

       
      

        $str="";
        foreach ($url_array as $domain) 
        {        
            /***Remove all www. http:// and https:// ****/            
            $domain=str_replace("www.","",$domain);
            $domain=str_replace("http://","",$domain);
            $domain=str_replace("https://","",$domain);

            $backlink_gererator_data=array();
            $backlink_gererator_data=$this->web_common_report->make_backlink($domain);

             $count=0;
            $str.="<div class='card'>
                          <div class='card-header'>
                            <h4><i class='fas fa-paperclip'></i> ".$this->lang->line("Backlink Generator")."</h4>
                            <div class='card-header-action'>
                              <div class='badges'>
                                <a  class='btn btn-primary float-right' href='".base_url()."/download/backlink/backlink_gererator_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
                              </div>                    
                            </div>
                          </div>";
            $str.="<div class='card-body'>
                        <h6 class='text-center'>".$this->lang->line("Domain:").$domain."</h6>
                        <ul class='list-group'>";   

            foreach($back_link_url as $link)
            {
                $link=str_replace("[url]",$domain,$link);
                $response=$this->web_common_report->make_backlink($link);
                $generated_at= date("Y-m-d H:i:s");
                  
                if($response=="200") 
                    $status="Successful";
                else 
                    $status= "Failed";

                $write_data=array();
                $write_data[]=$link;
                $write_data[]=$domain;
                $write_data[]=$response;               
                $write_data[]=$status;
                $write_data[]=$generated_at;

            
                fputcsv($download_path, $write_data);
                
                /** Insert into database ***/
       
                $insert_data=array
                (
                    'user_id'           => $this->user_id,
                    'domain_name'       => $domain,
                    'url'               => $link,
                    'response_code'     => $response,
                    'status'            => $status,
                    'generated_at'      => $generated_at
                );

                $count++;

                if ($status == 'Successful')
                    $tmp2 = "<span class='badge badge-success badge-pill'>".$this->lang->line("Successful")."</span>";
                else
                    $tmp2 = "<span class='badge badge-danger badge-pill'>".$this->lang->line("Failed")."</span>";

                $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$link."  ".$tmp2." </li>";
                
                $this->basic->insert_data('backlink_generator', $insert_data);    
                $backlink_gererator_complete++;
                $this->session->set_userdata("backlink_gererator_complete_search",$backlink_gererator_complete);    
            }  
            $str.="</ul></div></div>";


        }

        echo $str;      

    }

  

    public function backlink_gererator_download()
    {
        $all=$this->input->post("ids");
        $table = 'backlink_generator';
        $where=array();
        if($all!=0)
        {

            $id_array = array();
            foreach ($json_decode as  $value) 
            {
                $id_array[] = $value;
            }
            $where['where_in'] = array('id' => $id_array);
        }

        $where['where'] = array('user_id'=>$this->user_id);

        $info = $this->basic->get_data($table, $where, $select ='', $join='', $limit='', $start=null, $order_by='id asc');

        $download_id=$this->session->userdata('download_id');
        $fp = fopen("download/backlink/backlink_gererator_{$this->user_id}_{$download_id}.csv", "w");
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF)); // unicode compatible csv
        $head=array("Backlink URL","Doamin","Rresponse_code","status","Generated at");
                    
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) 
        {
         
            $write_info['url'] = $value['url'];
            $write_info['domain_name'] = $value['domain_name'];
            $write_info['response_code'] = $value['response_code'];
            $write_info['status'] = $value['status'];
            $write_info['generated_at'] = $value['generated_at'];
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/backlink/backlink_gererator_{$this->user_id}_{$download_id}.csv";

        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }


    

    public function backlink_gererator_delete()
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
        $this->db->delete('backlink_generator');
    }


    
    public function bulk_backlink_gererator_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('backlink_gererator_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('backlink_gererator_complete_search'); 
        
        $response['search_complete']=$bulk_complete_search;
        $response['search_total']=$bulk_tracking_total_search;
        
        echo json_encode($response);
        
    }








    public function backlink_search()
    {
        $data['body'] = 'backlink_ping/backlink/backlink_search';
        $data['page_title'] = $this->lang->line('Google Backlink Search');
        $this->_viewcontroller($data);
    }
    

    public function backlink_search_data()
    {

        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','domain_name','backlink_count','searched_at');
        $search_columns = array('domain_name','searched_at');

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

        if($searching !="") $where_simple['backlink_search.domain_name like'] = "%".$searching."%";

        $where_simple['backlink_search.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "backlink_search";

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

    public function backlink_new()
    {
        $data['body'] = 'backlink_ping/backlink/backlink_new';
        $data['page_title'] = $this->lang->line('Google Backlink Search');
        $this->_viewcontroller($data);
    }
    public function backlink_search_action()
    {
        $this->load->library('web_common_report');
        $urls=$this->input->post('domain_name', true);
      
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);

        //************************************************//
        $status=$this->_check_usage($module_id=9,$request=count($url_array));
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
        
      
        $this->session->set_userdata('backlink_search_bulk_total_search',count($url_array));
        $this->session->set_userdata('backlink_search_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/backlink/backlink_search_{$this->user_id}_{$download_id}.csv", "w");
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF)); // unicode compatible csv
        $total_count=0;
        
        /**Write header in csv file***/
        $write_data[]="Domain";            
        $write_data[]="Backlink Count";                
        $write_data[]="Searched At";                
        
        fputcsv($download_path, $write_data);
        
        $backlink_search_complete=0;

        $count=0;

        $str="<div class='card'>
                      <div class='card-header'>
                        <h4><i class='fas fa-vector-square'></i> ".$this->lang->line("Google Backlink Search Results")."</h4>
                        <div class='card-header-action'>
                          <div class='badges'>
                            <a  class='btn btn-primary float-right' href='".base_url()."/download/backlink/backlink_search_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
                          </div>                    
                        </div>
                      </div>";
        $str.="<div class='card-body'>
                    <ul class='list-group list_scroll'>";   
        foreach ($url_array as $domain) 
        {        
            /***Remove all www. http:// and https:// ****/            
            $domain=str_replace("www.","",$domain);
            $domain=str_replace("http://","",$domain);
            $domain=str_replace("https://","",$domain);

            $backlink_count="";
            $backlink_count=$this->web_common_report->GoogleBL($domain);  
            $searched_at= date("Y-m-d H:i:s");
                  
            $write_data=array();
            $write_data[]=$domain;
            $write_data[]=$backlink_count;
            $write_data[]=$searched_at;
        
            fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
   
            $insert_data=array
            (
                'user_id'           => $this->user_id,
                'domain_name'       => $domain,
                'backlink_count'    => $backlink_count,
                'searched_at'       => $searched_at
            );

            $count++;

            $str.= "<li class='list-group-item d-flex justify-content-between align-items-center'>".$domain."  <span class='badge badge-primary badge-pill'> ".$backlink_count."</span> </li>";

            $this->basic->insert_data('backlink_search', $insert_data);    
            $backlink_search_complete++;
            $this->session->set_userdata("backlink_search_complete_search",$backlink_search_complete);     
			sleep(8);
        }

        $str.="</ul></div></div>";
        $str.=" <style>
                    .list_scroll{
                        position: relative;
                        height: 450px;
                        width:450px;
                    }
                </style>
                <script>
                 new PerfectScrollbar('.list_scroll',{
                              wheelSpeed: 2,
                              wheelPropagation: true,
                              minScrollbarLength: 20
                            });
               </script>";
        //******************************//
        // insert data to useges log table
        $this->_insert_usage_log($module_id=9,$request=count($url_array));   
        //******************************//

        echo $str;

    }

  

    public function backlink_search_download()
    {
        $all=$this->input->post("ids");

        $table = 'backlink_search';
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
        $fp = fopen("download/backlink/backlink_search_{$this->user_id}_{$download_id}.csv", "w");
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF)); // unicode compatible csv
        $head=array("Doamin","Backlink Count","Searched at");
                    
        fputcsv($fp, $head);
        $write_info = array();

        foreach ($info as  $value) 
        {
         
            $write_info['domain_name'] = $value['domain_name'];
            $write_info['backlink_count'] = $value['backlink_count'];
            $write_info['searched_at'] = $value['searched_at'];
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/backlink/backlink_search_{$this->user_id}_{$download_id}.csv";
       
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }


    

    public function backlink_search_delete()
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
        $this->db->delete('backlink_search');
    }


    
    public function bulk_backlink_search_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('backlink_search_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('backlink_search_complete_search'); 
        
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
