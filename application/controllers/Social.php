<?php

require_once("Home.php"); // loading home controller



class Social extends Home
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

        if($this->session->userdata('user_type') != 'Admin' && !in_array(3,$this->module_access))
        redirect('home/login_page', 'location'); 
    }


    public function index()
    {
        $this->social_list();
    }

    public function social_list()
    {
        $data['body'] = 'social_analysis/social_network_analysis_list';
        $data['page_title'] = $this->lang->line("Social Network Analysis");
        $this->_viewcontroller($data);
    }
    
    public function analysis_new()
    {
        $data['body'] = 'social_analysis/social_network_analysis_list_new';
        $data['page_title'] = $this->lang->line("Social Network Analysis");
        $this->_viewcontroller($data);
    }

    public function social_list_data()
    {

        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','domain_name','fb_share','fb_like','fb_comment','xing_share_count','pinterest_pin','reddit_score','reddit_up','reddit_dowon','buffer_share','search_at');
        $search_columns = array('ip','search_at');

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
                $where_simple["Date_Format(search_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(search_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") 
            $where_simple['social_info.domain_name like'] = "%".$searching."%";

        $where_simple['social_info.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "social_info";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        for($i=0;$i<count($info);$i++)
        {  
         $info[$i]['search_at'] = date("jS M y",strtotime($info[$i]["search_at"]));
        }
        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }


    public function social_action()
    {
        $this->load->library('web_common_report');
        $urls=strip_tags($this->input->post('domain_name', true));

      /* if(is_facebook==0 && is_linkedin==0 && is_googleplus==0 && is_xing==0 && is_reddit==0 && is_pinterest==0 && is_buffer==0 && is_stumbleupon==0);*/

        $is_facebook=$this->input->post('is_facebook', true);
        $is_xing=$this->input->post('is_xing', true);

        $is_reddit=$this->input->post('is_reddit', true);
        $is_pinterest=$this->input->post('is_pinterest', true);
        $is_buffer=$this->input->post('is_buffer', true);
      
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);

        //************************************************//
        $status=$this->_check_usage($module_id=3,$request=count($url_array));
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
        
      
        $this->session->set_userdata('social_analysis_bulk_total_search',count($url_array));
        $this->session->set_userdata('social_analysis_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/social/social_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
        $total_count=0;
        
        /**Write header in csv file***/
        $write_data[]="Domain";
        if($is_facebook==1) {
            // $write_data[]="Facebook Like";
            $write_data[]="Facebook Share";
            $write_data[]="Facebook Reaction";
            $write_data[]="Facebook Comment";
            //$write_data[]="Facebook Comment Plugin";
        }
        if($is_xing==1) $write_data[]="Xing Share"; 

        if($is_reddit==1) {
            $write_data[]="Reddit Score";
            $write_data[]="Reddit Up";
            $write_data[]="Reddit Down";
        }

        if($is_pinterest==1) $write_data[]="Pinterest Pin";
        if($is_buffer==1) $write_data[]="Buffer Share";


        $write_data[]="Search at";            
        
        fputcsv($download_path, $write_data);
        
        $social_analysis_complete=0;
        $api="";
        $config_data=$this->basic->get_data("config",array("where"=>array("user_id"=>$this->user_id)));
        if(count($config_data)>0) $api=$config_data[0]["google_safety_api"];

 
        $str = "<div class='card'>
                    <div class='card-header'>
                      <h4><i class='fas fa-share-alt'></i> ".$this->lang->line("Analysis Result")."</h4>
                        <div class='card-header-action'>
                          <div class='badges'>
                            <a  class='btn btn-primary float-right' href='".base_url()."/download/social/social_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
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

            $domain_org=$domain;
            if($is_facebook==1) $facebook_report=$this->web_common_report->fb_like_comment_share(addHttp($domain_org));
            if($is_xing==1) $xing_report=$this->web_common_report->xing_share_count($domain); 

            if($is_reddit==1) $reddit_report=$this->web_common_report->reddit_count($domain);   
            if($is_pinterest==1) $pinterest_report=$this->web_common_report->pinterest_pin($domain);   
            if($is_buffer==1) $buffer_report=$this->web_common_report->buffer_share($domain);     
            
            $searched_at= date("Y-m-d H:i:s");
                  
            $write_data=array();


            $write_data[]=$domain;

            if($is_facebook==1)
            {
                
                $write_data[] = $facebook_report["total_share"];  
                $write_data[] = $facebook_report["total_reaction"];  
                $write_data[] = $facebook_report["total_comment"];  
                //$write_data[] = $facebook_report["total_comment_plugin"];  

            }

            if($is_xing==1){               
                   $write_data[] = $xing_report;               
            }

             if($is_reddit==1){
                foreach ($reddit_report as $value) {
                   $write_data[] = $value;
                }
            }

            if($is_pinterest==1){
               
                   $write_data[] = $pinterest_report;
             
            }

            if($is_buffer==1){
              
                   $write_data[] = $buffer_report;
              
            }


            $write_data[]=$searched_at;
            
            fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
            
            $insert_data=array
            (
                'user_id'           => $this->user_id,
                'domain_name'       => $domain,
                'search_at'        => $searched_at
            );

            
            if($is_facebook ==1){
                $insert_data['fb_share'] = $facebook_report['total_share'];
                $insert_data['fb_like'] = $facebook_report['total_reaction'];
                $insert_data['fb_comment'] = $facebook_report['total_comment'];
                //$insert_data['fb_comment_plugin'] = $facebook_report['total_comment_plugin'];
            }
            
            if($is_xing ==1){
                 $insert_data['xing_share_count'] = $xing_report;
            }

            
             if($is_reddit==1){
                 $insert_data['reddit_score'] = $reddit_report['score'];
                 $insert_data['reddit_up'] = $reddit_report['ups'];
                 $insert_data['reddit_dowon'] = $reddit_report['downs'];
            }

            if($is_pinterest ==1){
                 $insert_data['pinterest_pin'] = $pinterest_report;
            }

            if($is_buffer ==1){
                 $insert_data['buffer_share'] = $buffer_report;
            }
            if ($tab == 1) {
                $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

                if($is_facebook==1) 
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Facebook Share')."<span class='badge badge-primary badge-pill'>{$facebook_report['total_share']}</span></li>";
                if($is_facebook==1) 
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Facebook Reaction')."<span class='badge badge-primary badge-pill'>{$facebook_report['total_reaction']}</span></li>";
                if($is_facebook==1) 
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Facebook Comment')."<span class='badge badge-primary badge-pill'>{$facebook_report['total_comment']}</span></li>";
                if($is_xing==1) 
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Xing Share')."<span class='badge badge-primary badge-pill'>{$xing_report}</span></li>";
                if($is_reddit==1) 
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Reddit Score')."<span class='badge badge-primary badge-pill'>{$reddit_report['score']}</li><li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Reddit Up')." <span class='badge badge-primary badge-pill'>{$reddit_report['downs']}</span></li><li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Reddit Down')." <span class='badge badge-primary badge-pill'>{$reddit_report['ups']}</span></li>";
                if($is_pinterest==1)
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Pinterest Pin')."<span class='badge badge-primary badge-pill'>{$pinterest_report}</span></li>";
                if($is_buffer==1) 
                    $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Buffer Share')."<span class='badge badge-primary badge-pill'>{$buffer_report}</span></li>";


                $str.= "</ul></div>";
            }
            else{
                $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

                        if($is_facebook==1) 
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Facebook Share')."<span class='badge badge-primary badge-pill'>{$facebook_report['total_share']}</span></li>";
                        if($is_facebook==1) 
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Facebook Reaction')."<span class='badge badge-primary badge-pill'>{$facebook_report['total_reaction']}</span></li>";
                        if($is_facebook==1) 
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Facebook Comment')."<span class='badge badge-primary badge-pill'>{$facebook_report['total_comment']}</span></li>";
                        if($is_xing==1) 
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Xing Share')."<span class='badge badge-primary badge-pill'>{$xing_report}</span></li>";
                        if($is_reddit==1) 
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Reddit Score')."<span class='badge badge-primary badge-pill'>{$reddit_report['score']}</li><li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Reddit Up')." <span class='badge badge-primary badge-pill'>{$reddit_report['downs']}</span></li><li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Reddit Down')." <span class='badge badge-primary badge-pill'>{$reddit_report['ups']}</span></li>";
                        if($is_pinterest==1)
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Pinterest Pin')."<span class='badge badge-primary badge-pill'>{$pinterest_report}</span></li>";
                        if($is_buffer==1) 
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Buffer Share')."<span class='badge badge-primary badge-pill'>{$buffer_report}</span></li>";
                
                $str.= "</ul></div>";
            }

            $this->basic->insert_data('social_info', $insert_data);
        }
        $str.="</div>
                </div>";

        $this->_insert_usage_log($module_id=3,$request=count($url_array)); 
        echo $str.="</div></div></div>";

        


    }

  

    public function social_download()
    {
        $all=$this->input->post("ids");
        $table = 'social_info';
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
        $fp = fopen("download/social/social_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
 
        $head=array("Domain Name","Facebook Share","Facebook Reaction","Facebook Comment","Facebook Comment Plugin","Xing Share","Pinterest Pin", "Reddit Score" ,"Reddit Up", "Reddit Down" ,"Buffer Share" , "Search At");
                    
        fputcsv($fp, $head);
        $write_info = array();

       /* domain_name     user_id     reddit_score    reddit_up   reddit_dowon    linked_in_share     pinterest_pin   buffer_share    fb_like     fb_share    fb_comment  google_plus_count   stumbleupon_view    stumbleupon_like    stumbleupon_comment     stumbleupon_list    xing_share_count    search_at*/

        foreach ($info as  $value) 
        {
            $write_info['domain_name'] = $value['domain_name'];
            $write_info['facebook_share'] = $value['fb_share'];
            $write_info['facebook_reaction'] = $value['fb_like'];
            $write_info['facebook_comment'] = $value['fb_comment'];
            $write_info['facebook_comment_plugin'] = $value['fb_comment_plugin'];
            $write_info['xing_share_count'] = $value['xing_share_count'];
            $write_info['pinterest_pin'] = $value['pinterest_pin'];
            $write_info['reddit_score'] = $value['reddit_score'];
            $write_info['reddit_up'] = $value['reddit_up'];
            $write_info['reddit_dowon'] = $value['reddit_dowon'];
            $write_info['buffer_share'] = $value['buffer_share'];
            $write_info['search_at'] = $value['search_at'];
          
            
            fputcsv($fp, $write_info);
        }

        fclose($fp);
        $file_name = "download/social/social_{$this->user_id}_{$download_id}.csv";
       
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }

    

    public function social_delete()
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
        $this->db->delete('social_info');
    }


    
    public function bulk_scan_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('social_analysis_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('social_analysis_complete_search'); 
        
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
