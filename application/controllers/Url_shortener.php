<?php 
require_once("Home.php"); // loading home controller

class Url_shortener extends Home
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
        if($this->session->userdata('user_type') != 'Admin' && !in_array(18,$this->module_access))
        redirect('home/login_page', 'location'); 
    }


    public function index()
    {
        $data['body'] = "bitly/bitly_url_shortener_grid";
        $data['page_title'] = $this->lang->line("Bitly Url Shortener");
        $this->_viewcontroller($data);
    }


    public function url_shortener_data()
    {
        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','long_url','short_url','short_url_id','analytics','created_at');
        $search_columns = array('long_url','created_at');

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
                $where_simple["Date_Format(created_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(created_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") 
            $where_simple['bitly_url_shortener.long_url like'] = "%".$searching."%";

        $where_simple['bitly_url_shortener.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "bitly_url_shortener";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        for($i=0;$i<count($info);$i++)
        {  
         $info[$i]['created_at'] = date("jS M y",strtotime($info[$i]["created_at"]));
         $url_analytics = base_url('url_shortener/url_analytics/').$info[$i]['id'];
         $info[$i]['analytics'] = "<a target='_BLANK' href='".$url_analytics."' title='".$this->lang->line("URL Analytics")."' class='btn btn-circle btn-outline-primary'><i class='far fa-chart-bar'></i></a>&nbsp;&nbsp;";
        
        }

        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }

    public function shortener()
    {
        $data['body'] = "bitly/bitly_url_shortener";
        $data['page_title'] = $this->lang->line("New URL Shortener");
        $this->_viewcontroller($data);
    }

    public function rebrandly_shortener()
    {
        $data['body'] = "rebrandly/rebrandly_url_shortener_grid";
        $data['page_title'] = $this->lang->line("Rebrandly Url Shortener");
        $this->_viewcontroller($data);
    }

    public function rebrandly_shortener_data()
    {
        $this->ajax_check();

        $searching       = trim($this->input->post("searching",true));
        $post_date_range = $this->input->post("post_date_range",true);
        $display_columns = array("#",'CHECKBOX','id','long_url','short_url','short_url_id','analytics','created_at');
        $search_columns = array('long_url','created_at');

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
                $where_simple["Date_Format(created_at,'%Y-%m-%d') >="] = $from_date;
                $where_simple["Date_Format(created_at,'%Y-%m-%d') <="] = $to_date;
            }
        }

        if($searching !="") 
            $where_simple['rebrandly_url_shortener.long_url like'] = "%".$searching."%";

        $where_simple['rebrandly_url_shortener.user_id'] = $this->user_id;

        $where  = array('where'=>$where_simple);


        $table = "rebrandly_url_shortener";

        $info = $this->basic->get_data($table,$where,$select='',$join='',$limit,$start,$order_by,$group_by='');
        for($i=0;$i<count($info);$i++)
        {  
         $info[$i]['created_at'] = date("jS M y",strtotime($info[$i]["created_at"]));
         $url_analytics = base_url('url_shortener/rebrandly_url_analytics/').$info[$i]['id'];
         $info[$i]['analytics'] = "<a target='_BLANK' href='".$url_analytics."' title='".$this->lang->line("URL Analytics")."' class='btn btn-circle btn-outline-primary'><i class='far fa-chart-bar'></i></a>&nbsp;&nbsp;";
        
        }

        $total_rows_array=$this->basic->count_row($table,$where,$count=$table.".id",$join,$group_by='');
        $total_result=$total_rows_array[0]['total_rows'];
        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }
    public function rebrandly_url_analytics($id = 0)
    {
       if($id==0) exit();
       $where['where'] = array('id'=>$id,'user_id'=>$this->user_id);
       $select = array('short_url_id','short_url');
       $get_data = $this->basic->get_data("rebrandly_url_shortener",$where,$select);
       if (!empty($get_data)) {
         $data['rebrandly_shortener'] = $get_data[0]['short_url'];
         $total_click_data = $this->rebrandly_total_clicks($get_data[0]['short_url_id']);
         $data['total_click_data'] = $total_click_data;
         $data['body'] = "rebrandly/rebrandly_analytics";
         $data['page_title'] = $this->lang->line("Rebrandly URL Analytics");
         $this->_viewcontroller($data);
       }
       else
       {
        show_404();
       }




    }
    public function rebrandly_total_clicks($short_url_id)
    {
        $use_admin_app = $this->config->item('use_admin_app');
        if($use_admin_app == '' || $use_admin_app == 'no')
          $config_data = $this->basic->get_data('config',['where'=>['user_id'=>$this->user_id]]);
        else
          $config_data = $this->basic->get_data('config',['where'=>['access'=>'all_users']],'','',1,0);

        $rebrandly_api_key="";
        if(count($config_data)>0)
        {
            $rebrandly_api_key=$config_data[0]["rebrandly_api_key"];
        }

        $url = "https://api.rebrandly.com/v1/links/{$short_url_id}";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_URL,$url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey:{$rebrandly_api_key}",
            "Content-Type:application/json"
        ]);
        $content = curl_exec($ch);
        $result = json_decode($content,true);
        if (isset($result['errors'])) {
           
            $rebrandly_error['error_message'] = $result['errors'][0]['message'];
            return $rebrandly_error;
        }
        else if (isset($result['code'])){
            $rebrandly_error['error_message'] = $result['message'];
            return $rebrandly_error;
        }
        else{
           return  $result;
        }
    }
    public function rebrandly()
    {
        $data['body'] = "rebrandly/rebrandly_url_shortener";
        $data['page_title'] = $this->lang->line("New Shortener");
        $this->_viewcontroller($data);
    }

    public function rebrandly_shortener_action()
    {
        $urls=strip_tags($this->input->post('long_url', true));
        $title=strip_tags($this->input->post('title', true));
        
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/url_shortener/rebrandly_short_url_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
          
        /**Write header in csv file***/

        $write_data=array();            
        $write_data[]="Long URL";          
        $write_data[]="Short URL";               
        $write_data[]="Created at";            
                                        
        
        fputcsv($download_path, $write_data);
        
        $short_url_complete=0;

        $count=0;
        $str = "<div class='card'>
        <div class='card-header'>
          <h4><i class='fas fa-cut'></i> ".$this->lang->line("Rebrandly URL Shortener")."</h4>
            <div class='card-header-action'>
              <div class='badges'>
                <a  class='btn btn-primary float-right' href='".base_url()."/download/url_shortener/rebrandly_short_url_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
              </div>                    
            </div>
        </div>
        <div class='card-body'>
          <div class='table-responsive'>
            <table class='table table-bordered table-hover'>
              <tbody>
                <tr>";
        $str.="<th>".$this->lang->line("Long URL")."</th>"; 
        $str.="<th>".$this->lang->line("Short URL")."</th>";  
        $str.="</tr>";        
        $domain_data=array();
        $domain_data=$this->rebrandly_shortener_creator($urls,$title);
        if (isset($domain_data['error_message'])) {
            
            echo "<div class='card-body'>
                    <div class='alert alert-warning alert-has-icon'>
                     <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                     <div class='alert-body'>
                        <div class='alert-title'>".$this->lang->line('warning')."</div>
                            ".$domain_data['error_message']."
                       <br>
                    
                     </div>
                    </div>
                </div>";
            exit();
        }  
        $created_at= date("Y-m-d:H:i:s");
        
        $original_url = isset($domain_data['destination']) ? $domain_data['destination']: "";
        $short_url = isset($domain_data['shortUrl']) ? $domain_data['shortUrl']: ""; 
        $short_url_id = isset($domain_data['id']) ? $domain_data['id'] : "";
        $title = isset($domain_data['title']) ? $domain_data['title'] : "";
        $domainId = isset($domain_data['domainId']) ? $domain_data['domainId'] : "";
        $slashtag = isset($domain_data['slashtag']) ? $domain_data['slashtag'] : "";

        $write_data=array();
    
        $write_data[]=$original_url;
        $write_data[]=$short_url;
        $write_data[]=$short_url_id;
        $write_data[]=$created_at;
        fputcsv($download_path, $write_data);
        
        /** Insert into database ***/
    
        $insert_data=array
        (
            'user_id'        => $this->user_id,
            'long_url'       => $original_url,
            'short_url'      => $short_url,
            'short_url_id'   => $short_url_id,
            'title'   => $title,
            'domainId'   => $domainId,
            'slashtag'   => $slashtag,
            'created_at'     => $created_at
        );

  

        $str.=
        "<tr>
        <td>".$original_url."</td>
        <td>".$short_url."</td>
        </tr>";
        
        $this->basic->insert_data('rebrandly_url_shortener', $insert_data);        


        //******************************//
        // insert data to useges log table
        $this->_insert_usage_log($module_id=18,$request=1);   
        //******************************//

        echo $str.="</tbody></table></div></div></div>";
    }

    public function rebrandly_shortener_creator($urls,$title="")
    {
        $use_admin_app = $this->config->item('use_admin_app');
        if($use_admin_app == '' || $use_admin_app == 'no')
          $config_data = $this->basic->get_data('config',['where'=>['user_id'=>$this->user_id]]);
        else
          $config_data = $this->basic->get_data('config',['where'=>['access'=>'all_users']],'','',1,0);

        $rebrandly_api_key="";
        if(count($config_data)>0)
        {
            $rebrandly_api_key=$config_data[0]["rebrandly_api_key"];
        }

        $data = array(
        "title"=> "{$title}",
        "destination" => "{$urls}"
        );
            
        $url = 'https://api.rebrandly.com/v1/links';
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_URL,$url); 
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "apikey:{$rebrandly_api_key}",
            "Content-Type:application/json"
        ]);
        $content = curl_exec($ch);
        $result = json_decode($content,true);
        // $response = curl_getinfo($ch);
        // echo "<pre>";
        // var_dump($response); exit;

        if (isset($result['errors'])) {
           
            $rebrandly_error['error_message'] = $result['errors'][0]['message'];
            return $rebrandly_error;
        }
        else{
           return  $result;
        }
        
    }
    public function url_shortener_action()
    {
        $urls=strip_tags($this->input->post('domain_name', true));
       
        $urls=str_replace("\n", ",", $urls);
        $url_array=explode(",", $urls);
        $url_array=array_filter($url_array);
        $url_array=array_unique($url_array);

        //************************************************//
        $status=$this->_check_usage($module_id=18,$request=count($url_array));
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
        
      
        $this->session->set_userdata('url_shortener_bulk_total_search',count($url_array));
        $this->session->set_userdata('url_shortener_complete_search',0);
        $download_id= $this->session->userdata('download_id');
        
        $download_path=fopen("download/url_shortener/short_url_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($download_path, chr(0xEF).chr(0xBB).chr(0xBF));
          
        /**Write header in csv file***/

        $write_data=array();            
        $write_data[]="Original URL";          
        $write_data[]="Short URL";               
        $write_data[]="Created at";            
                                        
        
        fputcsv($download_path, $write_data);
        
        $short_url_complete=0;

        $count=0;
        $str = "<div class='card'>
        <div class='card-header'>
          <h4><i class='fas fa-cut'></i> ".$this->lang->line("Bitly URL Shortener")."</h4>
            <div class='card-header-action'>
              <div class='badges'>
                <a  class='btn btn-primary float-right' href='".base_url()."/download/url_shortener/short_url_{$this->user_id}_{$download_id}.csv'> <i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")." </a>
              </div>                    
            </div>
        </div>
        <div class='card-body'>
          <div class='table-responsive'>
            <table class='table table-bordered table-hover'>
              <tbody>
                <tr>";
        $str.="<th>".$this->lang->line("SL")."</th>"; 
        $str.="<th>".$this->lang->line("Long URL")."</th>"; 
        $str.="<th>".$this->lang->line("Short URL")."</th>";  
        $str.="</tr>";  
        foreach ($url_array as $domain) 
        {        
            $domain_data=array();
            $domain_data=$this->bitly_short_url_creator($domain);
            if (isset($domain_data['error_message'])) {
                
                echo "<div class='card-body'>
                        <div class='alert alert-warning alert-has-icon'>
                         <div class='alert-icon'><i class='far fa-lightbulb'></i></div>
                         <div class='alert-body'>
                            <div class='alert-title'>".$this->lang->line('warning')."</div>
                           ".$this->lang->line("Sorry, bitly generic access token is missing or invalid long url.").'<br>'.$domain_data['error_message']."
                           <br>
                            <a target='_BLANK' href='".base_url("social_apps/connectivity_settings")."'>".$this->lang->line("click here to insert bitly generic access token.")."</a>
                         </div>
                        </div>
                    </div>";
                exit();
            }  
            $created_at= date("Y-m-d:H:i:s");
            
            $original_url = isset($domain_data['long_url']) ? $domain_data['long_url']: "";
            $short_url = isset($domain_data['link']) ? $domain_data['link']: ""; 
            $short_url_id = isset($domain_data['id']) ? $domain_data['id'] : "";

            $write_data=array();
     
            $write_data[]=$original_url;
            $write_data[]=$short_url;
            $write_data[]=$short_url_id;

            $write_data[]=$created_at;
            fputcsv($download_path, $write_data);
            
            /** Insert into database ***/
   
            $insert_data=array
            (
                'user_id'        => $this->user_id,
                'long_url'       => $original_url,
                'short_url'      => $short_url,
                'short_url_id'   => $short_url_id,
                'created_at'     => $created_at
            );

            $count++;

            $str.=
            "<tr>
            <td>".$count."</td>
            <td>".$original_url."</td>
            <td>".$short_url."</td>
            </tr>";
            
            $this->basic->insert_data('bitly_url_shortener', $insert_data);    
            $short_url_complete++;
            $this->session->set_userdata("url_shortener_complete_search",$short_url_complete);        
        }

        //******************************//
        // insert data to useges log table
        $this->_insert_usage_log($module_id=18,$request=count($url_array));   
        //******************************//

        echo $str.="</tbody></table></div></div></div>";

    }


    public function bitly_short_url_creator($long_url)
    {
        $use_admin_app = $this->config->item('use_admin_app');
        if($use_admin_app == '' || $use_admin_app == 'no')
          $config_data = $this->basic->get_data('config',['where'=>['user_id'=>$this->user_id]]);
        else
          $config_data = $this->basic->get_data('config',['where'=>['access'=>'all_users']],'','',1,0);

        $bitly_generic_access_token="";
        if(count($config_data)>0)
        {
            $bitly_generic_access_token=$config_data[0]["bitly_access_token"];
        }

        $apiv4 = 'https://api-ssl.bitly.com/v4/bitlinks';

        $data = array(
            'long_url' => $long_url
        );
        $payload = json_encode($data);

        $header = array(
            'Authorization: Bearer ' . $bitly_generic_access_token,
            'Content-Type: application/json'
        );

        $ch = curl_init($apiv4);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch);
        curl_close($ch);
        $result = json_decode($content,true);

        if (isset($result['message'])) {
           
           $bitly_data['error_message'] = $result['message'];
           return $bitly_data;
        }
        else{
            return $result;
        }


    }


    public function short_url_download()
    {
        $all=$this->input->post("ids");
        $table = 'bitly_url_shortener';
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
        $fp = fopen("download/url_shortener/short_url_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        

        $write_data=array();            
        $write_data[]="Long URL";          
        $write_data[]="Short URL";           
        $write_data[]="Short URL ID";           
        $write_data[]="Created at";   
                    
        fputcsv($fp, $write_data);
        $write_info = array();

        foreach ($info as  $value) 
        {
                $write_data=array();            
                $write_data[]=$value["long_url"];      
                $write_data[]=$value["short_url"];
                $write_data[]=$value["short_url_id"];
                $write_data[]=$value["created_at"];   
            
                fputcsv($fp, $write_data);
        }

        fclose($fp);
        $file_name = "download/url_shortener/short_url_{$this->user_id}_{$download_id}.csv";
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }

    public function rebrandly_short_url_download()
    {
        $all=$this->input->post("ids");
        $table = 'rebrandly_url_shortener';
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
        $fp = fopen("download/url_shortener/rebrandly_short_url_{$this->user_id}_{$download_id}.csv", "w");
        // make output csv file unicode compatible
        fprintf($fp, chr(0xEF).chr(0xBB).chr(0xBF));
        

        $write_data=array();            
        $write_data[]="Long URL";          
        $write_data[]="Short URL";           
        $write_data[]="Short URL ID";           
        $write_data[]="Created at";   
                    
        fputcsv($fp, $write_data);
        $write_info = array();

        foreach ($info as  $value) 
        {
                $write_data=array();            
                $write_data[]=$value["long_url"];      
                $write_data[]=$value["short_url"];
                $write_data[]=$value["short_url_id"];
                $write_data[]=$value["created_at"];   
            
                fputcsv($fp, $write_data);
        }

        fclose($fp);
        $file_name = "download/url_shortener/rebrandly_short_url_{$this->user_id}_{$download_id}.csv";
        echo "<p>".$this->lang->line("Your file is ready to download")."</p> <a href=".base_url().$file_name." target='_BLANK' class='btn btn-lg btn-primary'><i class='fa fa-cloud-download'></i> ".$this->lang->line("Download")."</a>";
    }
    public function short_url_delete()
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
        $this->db->delete('bitly_url_shortener');
    }

    public function rebrandly_short_url_delete()
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
        $this->db->delete('rebrandly_url_shortener');
    }



    public function url_analytics($id=0)
    {
        if($id==0) exit();
        $where['where'] = array('id'=>$id,'user_id'=>$this->user_id);
        $select = array('short_url_id','short_url');
        $get_data = $this->basic->get_data("bitly_url_shortener",$where,$select);
        $data['bitly_shortener'] = $get_data[0]['short_url'];
        
        $monthly_click_data = $this->bitly_monthly_clicks_report($get_data[0]['short_url_id']);
        $monthly_bitly_montly_referring_domains_data = $this->bitly_montly_referring_domains($get_data[0]['short_url_id']);
        $monthly_bitly_monthly_countries_data = $this->bitly_monthly_countries($get_data[0]['short_url_id']);
        $data['monthly_click_data'] = $monthly_click_data;
        $data['monthly_bitly_montly_referring_domains_data'] = $monthly_bitly_montly_referring_domains_data;
        $data['monthly_bitly_monthly_countries_data'] = $monthly_bitly_monthly_countries_data;
        $data['body'] = "bitly/bitly_analytics";
        $data['page_title'] = $this->lang->line("Bitly URL Analytics");
        $this->_viewcontroller($data);

    }

    public function bitly_monthly_clicks_report($short_url_id)
    {
        $use_admin_app = $this->config->item('use_admin_app');
        if($use_admin_app == '' || $use_admin_app == 'no')
          $config_data = $this->basic->get_data('config',['where'=>['user_id'=>$this->user_id]]);
        else
          $config_data = $this->basic->get_data('config',['where'=>['access'=>'all_users']],'','',1,0);

        $bitly_generic_access_token="";
        if(count($config_data)>0)
        {
            $bitly_generic_access_token=$config_data[0]["bitly_access_token"];
        }

        $apiv4 = "https://api-ssl.bitly.com/v4/bitlinks/{$short_url_id}/clicks?units=28";

        $header = array(
            'Authorization: Bearer ' .$bitly_generic_access_token,
            'Content-Type: application/json'
        );

        $ch = curl_init($apiv4);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch);

        $result = json_decode($content,true);
        if (isset($result['message'])) {
           
           $bitly_data['error_message'] = $result['message'];
           return $bitly_data;
        }
        else{
            return $result;
        }
       

    }

    public function bitly_montly_referring_domains($short_url_id)
    {
        $use_admin_app = $this->config->item('use_admin_app');
        if($use_admin_app == '' || $use_admin_app == 'no')
          $config_data = $this->basic->get_data('config',['where'=>['user_id'=>$this->user_id]]);
        else
          $config_data = $this->basic->get_data('config',['where'=>['access'=>'all_users']],'','',1,0);

        $bitly_generic_access_token="";
        if(count($config_data)>0)
        {
            $bitly_generic_access_token=$config_data[0]["bitly_access_token"];
        }

        $apiv4 = "https://api-ssl.bitly.com/v4/bitlinks/{$short_url_id}/referring_domains?units=28";

        $header = array(
            'Authorization: Bearer ' .$bitly_generic_access_token,
            'Content-Type: application/json'
        );

        $ch = curl_init($apiv4);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch);

        $result = json_decode($content,true);
        if (isset($result['message'])) {
          
          $bitly_data['error_message'] = $result['message'];
          return $bitly_data;
        }
       else{
           return $result;
        }

    }

    public function bitly_monthly_countries($short_url_id)
    {
        $use_admin_app = $this->config->item('use_admin_app');
        if($use_admin_app == '' || $use_admin_app == 'no')
          $config_data = $this->basic->get_data('config',['where'=>['user_id'=>$this->user_id]]);
        else
          $config_data = $this->basic->get_data('config',['where'=>['access'=>'all_users']],'','',1,0);

        $bitly_generic_access_token="";
        if(count($config_data)>0)
        {
            $bitly_generic_access_token=$config_data[0]["bitly_access_token"];
        }

        $apiv4 = "https://api-ssl.bitly.com/v4/bitlinks/{$short_url_id}/countries?units=28";

        $header = array(
            'Authorization: Bearer ' .$bitly_generic_access_token,
            'Content-Type: application/json'
        );

        $ch = curl_init($apiv4);

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch);

        $result = json_decode($content,true);
        if (isset($result['message'])) {
           
           $bitly_data['error_message'] = $result['message'];
           return $bitly_data;
        }
        else{
            return $result;
        }
    }


    public function bulk_url_short_progress_count()
    {
        $bulk_tracking_total_search=$this->session->userdata('url_shortener_bulk_total_search'); 
        $bulk_complete_search=$this->session->userdata('url_shortener_complete_search'); 
        
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
              $filename="alexa_rank_analysis_".$this->user_id."_".time().substr(uniqid(mt_rand(), true), 0, 6).".".$ext;

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