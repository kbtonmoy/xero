<?php
require_once("Home.php");

class Cron_job extends Home
{
    public $user_id;
    
    public function __construct()
    {
        parent::__construct();   
        $this->user_id=$this->session->userdata("user_id");    
        $this->load->library('Web_common_report');

        // $this->important_feature();
    }


    public function api_member_validity($user_id='')
    {
        if($user_id!='') {
            $where['where'] = array('id'=>$user_id);
            $user_expire_date = $this->basic->get_data('users',$where,$select=array('expired_date'));
            $expire_date = strtotime($user_expire_date[0]['expired_date']);
            $current_date = strtotime(date("Y-m-d"));
            $package_data=$this->basic->get_data("users",$where=array("where"=>array("users.id"=>$user_id)),$select="package.price as price, users.user_type",$join=array('package'=>"users.package_id=package.id,left"));

            if(is_array($package_data) && array_key_exists(0, $package_data) && $package_data[0]['user_type'] == 'Admin' )
                return true;

            $price = '';
            if(is_array($package_data) && array_key_exists(0, $package_data))
            $price=$package_data[0]["price"];
            if($price=="Trial") $price=1;

            
            if ($expire_date < $current_date && ($price>0 && $price!=""))
            return false;
            else return true;
            

        }
    }


    public function index()
    {
       $this->get_api();
    }

    public function _api_key_generator()
    {
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');

        if($this->session->userdata('user_type') != 'Admin' && !in_array(15,$this->module_access))
        redirect('home/login_page', 'location');

        $this->member_validity();
        $val=$this->session->userdata("user_id")."-".substr(str_shuffle('abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789') , 0 , 7 ).time()
        .substr(str_shuffle('abcdefghijkmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ23456789') , 0 , 7 );
        return $val;
    }

    public function get_api()
    {
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login_page', 'location');

        if($this->session->userdata('user_type') != 'Admin' && !in_array(15,$this->module_access))
        redirect('home/login_page', 'location');

        $this->member_validity();

        $data['body'] = "admin/cron_job/command";
        $data['page_title'] = 'Cron Job';
        $api_data=$this->basic->get_data("native_api",array("where"=>array("user_id"=>$this->session->userdata("user_id"))));
        $data["api_key"]="";
        if(count($api_data)>0) $data["api_key"]=$api_data[0]["api_key"];
        $this->_viewcontroller($data);
    }

    public function get_api_action()
    { 
        if($this->is_demo == '1' && $this->session->userdata('user_type') == 'Admin')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }
        if ($this->session->userdata('logged_in') != 1)
        redirect('home/login', 'location');

        if($this->session->userdata('user_type') != 'Admin' && !in_array(15,$this->module_access))
        redirect('home/login_page', 'location');

        $api_key=$this->_api_key_generator(); 
        if($this->basic->is_exist("native_api",array("api_key"=>$api_key)))
        $this->get_api_action();

        $user_id=$this->session->userdata("user_id");        
        if($this->basic->is_exist("native_api",array("user_id"=>$user_id)))
        $this->basic->update_data("native_api",array("user_id"=>$user_id),array("api_key"=>$api_key));
        else $this->basic->insert_data("native_api",array("api_key"=>$api_key,"user_id"=>$user_id));
            
        redirect('cron_job/get_api', 'location');
    }

    public function send_notification($api_key="")
    {
        if ($api_key=="") exit();
        $user_id=substr($api_key, 0, 1);

        if(!$this->basic->is_exist("native_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
            echo "API Key does not match with any user.";
            exit();
        }   

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0","user_type"=>"Admin")))
        {
            echo "Invalid user.";
            exit();
        }     

        $current_date = date("Y-m-d");
        $tenth_day_before_expire = date("Y-m-d", strtotime("$current_date + 10 days"));
        $one_day_before_expire = date("Y-m-d", strtotime("$current_date + 1 days"));
        $one_day_after_expire = date("Y-m-d", strtotime("$current_date - 1 days"));

        // echo $tenth_day_before_expire."<br/>".$one_day_before_expire."<br/>".$one_day_after_expire;

        //send notification to members before 10 days of expire date
        $where = array();
        $where['where'] = array(
            'user_type !=' => 'Admin',
            'expired_date' => $tenth_day_before_expire
            );
        $info = array();
        $value = array();
        $info = $this->basic->get_data('users',$where,$select='');
        $from = "";
        $mask = $this->config->item('product_name');
        $subject = "Payment Notification";
        foreach ($info as $value) 
        {
            $message = "Dear {$value['first_name']} {$value['last_name']},<br/> your account will expire after 10 days, Please pay your fees.<br/><br/>Thank you,<br/><a href='".base_url()."'>{$mask}</a> team";
            $to = $value['email'];
            $this->_mail_sender($from, $to, $subject, $message, $mask, $html=0);
        }

        //send notificatio to members before 1 day of expire date
        $where = array();
        $where['where'] = array(
            'user_type !=' => 'Admin',
            'expired_date' => $one_day_before_expire
            );
        $info = array();
        $value = array();
        $info = $this->basic->get_data('users',$where,$select='');
        $from = $this->config->item('institute_email');
        $mask = $this->config->item('product_name');
        $subject = "Payment Notification";
        foreach ($info as $value) {
            $message = "Dear {$value['first_name']} {$value['last_name']},<br/> your account will expire tomorrow, Please pay your fees.<br/><br/>Thank you,<br/><a href='".base_url()."'>{$mask}</a> team";
            $to = $value['email'];
            $this->_mail_sender($from, $to, $subject, $message, $mask, $html=0);
        }

        //send notificatio to members after 1 day of expire date
        $where = array();
        $where['where'] = array(
            'user_type !=' => 'Admin',
            'expired_date' => $one_day_after_expire
            );
        $info = array();
        $value = array();
        $info = $this->basic->get_data('users',$where,$select='');
        $from = $this->config->item('institute_email');
        $mask = $this->config->item('product_name');
        $subject = "Payment Notification";
        foreach ($info as $value) {
            $message = "Dear {$value['name']},<br/> your account has been expired, Please pay your fees for continuity.<br/><br/>Thank you,<br/><a href='".base_url()."'>{$mask}</a> team";
            $to = $value['email'];
            $this->_mail_sender($from, $to, $subject, $message, $mask, $html=0);
        }

    }

    public function auction_domain($api_key)
    {
        if ($api_key=="") exit();
        $user_id=substr($api_key, 0, 1);

        if(!$this->basic->is_exist("native_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
            echo "API Key does not match with any user.";
            exit();
        }   

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0","user_type"=>"Admin")))
        {
            echo "Invalid user.";
            exit();
        } 
        
        $this->_grab_auction_list_data();
    }

    public function get_keyword_position_data($api_key="")
    {
        $user_id="";
        if($api_key!="")
        {
            $explde_api_key=explode('-',$api_key);
            $user_id="";
            if(array_key_exists(0, $explde_api_key))
            $user_id=$explde_api_key[0];
        }

        if($api_key=="")
        {        
            echo "API Key is required.";    
            exit();
        }

        if(!$this->basic->is_exist("native_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
           echo "API Key does not match with any user.";
           exit();
        }

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0","user_type"=>"Admin")))
        {
            echo "API Key does not match with any authentic user.";
            exit();
        }

        $this->load->library('web_common_report');
        $keywords = $this->basic->get_data("keyword_position_set",['where'=>['last_scan_date !='=>date('Y-m-d')]],'','',50);
        foreach($keywords as $value)
            $this->basic->update_data('keyword_position_set',['id'=>$value['id']],['last_scan_date'=>date('Y-m-d')]);
        

        foreach($keywords as $value){

            $keyword = $value['keyword'];
            $country = $value['country'];
            $language = $value['language'];
            $domain = $value['website'];

            $keyword_position_google_data=$this->web_common_report->keyword_position_google($keyword, $page_number=0, $proxy="",$country,$language,$domain);

            $keyword_position_bing_data=$this->web_common_report->keyword_position_bing($keyword, $page_number=0, $proxy="",$country,$language,$domain);

            $keyword_position_yahoo_data=$this->web_common_report->keyword_position_yahoo($keyword, $page_number=0, $proxy="",$country,$language,$domain);

            $data = array(
                "keyword_id" => $value['id'],
                "user_id" => $value['user_id'],
                "google_position" => $keyword_position_google_data["status"],
                "bing_position" => $keyword_position_bing_data["status"],
                "yahoo_position" => $keyword_position_yahoo_data["status"],
                "date" => date("Y-m-d")
                );
            $this->basic->insert_data("keyword_position_report",$data);

        }
    }

    public function delete_junk_files($api_key="")
    {
        if ($api_key=="") exit();
        $user_id=substr($api_key, 0, 1);

        if(!$this->basic->is_exist("native_api",array("api_key"=>$api_key,"user_id"=>$user_id)))
        {
            echo "API Key does not match with any user.";
            exit();
        }   

        if(!$this->basic->is_exist("users",array("id"=>$user_id,"status"=>"1","deleted"=>"0","user_type"=>"Admin")))
        {
            echo "Invalid user.";
            exit();
        }

        /****Clean Cache Directory , keep all files of last 24 hours******/
        $all_cache_file=$this->delete_cache('application/cache');
        $all_cache_file=$this->delete_cache('download');

        $delete_junk_data_after_how_many_days = $this->config->item('delete_junk_data_after_how_many_days');
        if($delete_junk_data_after_how_many_days == '') $delete_junk_data_after_how_many_days = 30;
        $to_date = date("Y-m-d H:i:s");
        $from_date = date("Y-m-d H:i:s",strtotime("$to_date-$delete_junk_data_after_how_many_days days"));
        $this->db->where('date_time <',$from_date);
        $this->db->delete('visitor_analysis_domain_list_data');

    }

    protected function delete_cache($myDir) //delete_junk_data
    {

        $cur_time=date('Y-m-d H:i:s');
        $yesterday=date("Y-m-d H:i:s",strtotime($cur_time." -1 day"));
        $yesterday=strtotime($yesterday);


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

        $path_explode = explode(".",$file_path);
        $extension= array_pop($path_explode);

        if($file_path!='.htaccess' && $file_path!='index.html'){

             $full_file_path=$myDir."/".$file_path;

             $file_creation_time=filemtime($full_file_path);
             $file_creation_time=date('Y-m-d H:i:s',$file_creation_time); //convert unix time to system time zone 
             $file_creation_time=strtotime($file_creation_time);


             if($file_creation_time<$yesterday){
                $dirTree[] = trim($file_path,"/");
                unlink($full_file_path);

             }
                
        }

        
        }
        
        return $dirTree;
            
    }


}
