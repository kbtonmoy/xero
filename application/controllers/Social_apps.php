<?php

require_once("Home.php"); // loading home controller

/**
* @category controller
* class Admin
*/

class Social_apps extends Home
{
    
    public function __construct()
    {
        parent::__construct();

        if ($this->session->userdata('logged_in')!= 1) {
            redirect('home/login', 'location');
        }     
        
        $this->load->helper('form');
        $this->load->library('upload');
        
        $this->upload_path = realpath(APPPATH . '../upload');
        set_time_limit(0);

        $this->important_feature();
        $this->periodic_check();
    }


    public function index()
    {
        $this->settings();
    }


    public function settings()
    {

        $data['page_title'] = $this->lang->line('Social Apps & APIs');

        $data['body'] = 'admin/social_apps/settings';
        $data['title'] = $this->lang->line('Social Apps');

        $this->_viewcontroller($data);  
    }


    public function google_settings()
    {
        if ($this->session->userdata('user_type') != 'Admin')
            redirect('home/login_page', 'location');

        $google_settings = $this->basic->get_data('login_config');

        if (!isset($google_settings[0])) $google_settings = array();
        else $google_settings = $google_settings[0];

        if($this->is_demo == '1')
        {
            $google_settings['api_key'] = 'XXXXXXXXXXX';
            $google_settings['google_client_secret'] = 'XXXXXXXXXXX';
        }

        $data['google_settings'] = $google_settings;
        $data['page_title'] = $this->lang->line('Google App Settings');
        $data['title'] = $this->lang->line('Google App Settings');
        $data['body'] = 'admin/social_apps/google_settings';

        $this->_viewcontroller($data);
    }

    public function google_settings_action()
    {
        if($this->is_demo == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

        if ($this->session->userdata('user_type') != 'Admin')
            redirect('home/login_page', 'location');

        if (!isset($_POST)) exit;

        $this->form_validation->set_rules('api_key', $this->lang->line("API Key"), 'trim');
        $this->form_validation->set_rules('google_client_id', $this->lang->line("Client ID"), 'trim|required');
        $this->form_validation->set_rules('google_client_secret', $this->lang->line("Client Secret"), 'trim|required');

        if ($this->form_validation->run() == FALSE) 
            $this->google_settings();
        else {

            $insert_data['app_name'] = trim(strip_tags($this->input->post('app_name',true)));
            $insert_data['api_key'] = trim(strip_tags($this->input->post('api_key',true)));
            $insert_data['google_client_id'] = trim(strip_tags($this->input->post('google_client_id',true)));
            $insert_data['google_client_secret'] = trim(strip_tags($this->input->post('google_client_secret',true)));
            
            $status = $this->input->post('status');
            if($status=='') $status='0';
            $insert_data['status'] = $status;

            $google_settings = $this->basic->get_data('login_config');

            if (count($google_settings) > 0 ) {

                $id = $google_settings[0]['id'];
                $this->basic->update_data('login_config', array('id' => $id), $insert_data);
            }
            else {
                $this->basic->insert_data('login_config', $insert_data);
            }


            // update facebook_app_id, facebook_app_secret into config table
            // $google_api_settings = $this->basic->get_data("config", array("where"=>array("user_id"=>$this->user_id)));

            // if(count($google_api_settings) > 0 )  {

            //     $google_api_settings_id = $google_api_settings[0]['id'];
            //     $this->basic->update_data("config",array("id"=>$google_api_settings_id),array("google_safety_api"=>$insert_data['api_key'],"mobile_ready_api_key"=>$insert_data['api_key']));
            // }
            // else { 

            //     $this->basic->insert_data("config",array("user_id"=>$this->user_id,"google_safety_api"=>$insert_data['api_key'],"mobile_ready_api_key"=>$insert_data['api_key']));
            // }


            $this->session->set_flashdata('success_message', '1');
            redirect(base_url('social_apps/google_settings'),'location');
        }

    }


    protected function facebookTokenValidityCheck($input_token)
    {

        if($input_token=="") 
        return "<span class='badge badge-status text-danger'><i class='fas fa-times-circle red'></i> ".$this->lang->line('Invalid')."</span>";
        $this->load->library("fb_rx_login"); 
        
        if($this->config->item('developer_access') == '1')
        {
            $valid_or_invalid = $this->fb_rx_login->access_token_validity_check_for_user($input_token);
            
            if($valid_or_invalid)
                return "<span class='badge badge-status text-success'><i class='fa fa-check-circle green'></i> ".$this->lang->line('Valid')."</span>";
            else
                return "<span class='badge badge-status text-danger'><i class='fa fa-clock-o red'></i> ".$this->lang->line('Expired')."</span>";
        }
        else
        {
            $url="https://graph.facebook.com/debug_token?input_token={$input_token}&access_token={$input_token}";
            $result= $this->fb_rx_login->run_curl_for_fb($url);
            $result = json_decode($result,true);
             
            if(isset($result["data"]["is_valid"]) && $result["data"]["is_valid"])
                return "<span class='badge badge-status text-success'><i class='fa fa-check-circle green'></i> ".$this->lang->line('Valid')."</span>";
            else
                return "<span class='badge badge-status text-danger'><i class='fa fa-clock-o red'></i> ".$this->lang->line('Expired')."</span>"; 
        }

    }


    public function add_facebook_settings()
    {
        if ($this->session->userdata('user_type') != 'Admin')
            redirect('home/login_page', 'location');

        $data['facebook_settings'] = array();
        $data['page_title'] = $this->lang->line('Facebook App Settings');
        $data['title'] = $this->lang->line('Facebook App Settings');
        $data['body'] = 'admin/social_apps/facebook_settings';

        $appsData = $this->basic->get_data("facebook_rx_config");
        if($this->is_demo == '1')
        {
            $appsData[0]['app_id'] = 'XXXXXXXXXXX';
            $appsData[0]['app_secret'] = 'XXXXXXXXXXX';
        }
        $data['app_data'] = isset($appsData[0]) ? $appsData[0] : "";
        $this->_viewcontroller($data);
    }

    public function facebook_settings_update_action()
    {
        if($this->is_demo == '1')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

        if (!isset($_POST)) exit;

        $this->form_validation->set_rules('api_id', $this->lang->line("App ID"), 'trim|required');
        $this->form_validation->set_rules('api_secret', $this->lang->line("App Secret"), 'trim|required');
        // $table_id = $this->input->post('table_id',true);

        if ($this->form_validation->run() == FALSE) 
        {
            // if($table_id == 0) {
            //     $this->add_facebook_settings();
            // }
            // else { 
            //     $this->edit_facebook_settings($table_id);
            // }

            return $this->add_facebook_settings();
        }
        else {

            $insert_data['app_name'] = trim(strip_tags($this->input->post('app_name',true)));
            $insert_data['app_id'] = trim(strip_tags($this->input->post('api_id',true)));
            $insert_data['app_secret'] = trim(strip_tags($this->input->post('api_secret',true)));
            $insert_data['user_id'] = $this->user_id;
            
            $status = $this->input->post('status');
            if($status=='') $status='0';
            $insert_data['status'] = $status;

            $facebook_settings = $this->basic->get_data('facebook_rx_config');

            if (count($facebook_settings) > 0 ) {

                $id = $facebook_settings[0]['id'];
                $this->basic->update_data('facebook_rx_config', array("id"=>$id), $insert_data);
            }
            else {
                $this->basic->insert_data('facebook_rx_config', $insert_data);
            }

            $this->session->set_flashdata('success_message', '1');
            redirect(base_url('social_apps/add_facebook_settings'),'location');
            
        }
    }



    /* Connectivity settings section */
    public function connectivity_settings()
    {
        $data['body'] = "admin/social_apps/connectivity_settings";
        $data['config_data'] = $this->basic->get_data("config",array("where"=>array("user_id"=>$this->user_id)));

        $data['page_title'] = $this->lang->line('Connectivity Settings');
        $this->_viewcontroller($data);
    }

    public function connectivity_settings_action()
    {
        if($this->is_demo == '1' && $this->session->userdata('user_type')=='Admin')
        {
            echo "<h2 style='text-align:center;color:red;border:1px solid red; padding: 10px'>This feature is disabled in this demo.</h2>"; 
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'GET') {
            redirect('home/access_forbidden', 'location');
        }

        if ($_POST) 
        {
            // validation
            $this->form_validation->set_rules('google_api_key',          '<b>Google API Key</b>',                  'trim');
            $this->form_validation->set_rules('moz_access_id',          '<b>MOZ Access ID</b>',                  'trim');
            $this->form_validation->set_rules('moz_secret_key',         '<b>MOZ Secret Key</b>',                 'trim');
            $this->form_validation->set_rules('virus_total_api',         '<b>VirusTotal Key</b>',                 'trim');
            $this->form_validation->set_rules('bitly_access_token',         '<b>Bitly Access Token</b>',           'trim');
            $this->form_validation->set_rules('rebrandly_api_key',         '<b>Rebrandly API Key</b>',           'trim');
            // go to config form page if validation wrong
            if ($this->form_validation->run() == false) 
            {
                return $this->connectivity_settings();
            } 

            else 
            {
                // assign
                $google_api_key=trim(strip_tags($this->input->post('google_api_key', true)));
                $moz_access_id=trim(strip_tags($this->input->post('moz_access_id', true)));
                $moz_secret_key=trim(strip_tags($this->input->post('moz_secret_key', true)));
                $virus_total_api=trim(strip_tags($this->input->post('virus_total_api', true)));
                $bitly_access_token=trim(strip_tags($this->input->post('bitly_access_token', true)));
                $rebrandly_api_key=trim(strip_tags($this->input->post('rebrandly_api_key', true)));

                $update_data = array(
                    "google_safety_api"=>$google_api_key,
                    "mobile_ready_api_key"=>$google_api_key,
                    "moz_access_id"=>$moz_access_id,
                    "moz_secret_key"=>$moz_secret_key,
                    "virus_total_api"=>$virus_total_api,
                    "bitly_access_token"=>$bitly_access_token,
                    "rebrandly_api_key"=>$rebrandly_api_key,
                );

                $insert_data=array(
                    "google_safety_api"=>$google_api_key,
                    "mobile_ready_api_key"=>$google_api_key,
                    "moz_access_id"=>$moz_access_id,
                    "moz_secret_key"=>$moz_secret_key,
                    "virus_total_api"=>$virus_total_api,
                    "bitly_access_token"=>$bitly_access_token,
                    "rebrandly_api_key"=>$rebrandly_api_key,
                    "user_id"=>$this->user_id
                );

                if($this->session->userdata('user_type') == 'Admin' && $this->config->item('use_admin_app') == 'yes')
                {
                    $update_data['access'] = 'all_users';
                    $insert_data['access'] = 'all_users';
                }

                $connectivity_settings_data = $this->basic->get_data("config", array("where"=>array("user_id"=>$this->user_id)));

                if(count($connectivity_settings_data) > 0 )  {
                    $this->basic->update_data("config",array("user_id"=>$this->user_id),$update_data);
                }
                else { 
                    $this->basic->insert_data("config",$insert_data);
                }
                  
                $this->session->set_flashdata('success_message', 1);
                redirect('social_apps/connectivity_settings', 'location');
            }
        }
    }
    /* end of connectivity settings */

    /* proxy settings section */
    public function proxy_settings()
    {
        $data['body'] = "admin/social_apps/proxy_settings";
        $data['page_title'] = $this->lang->line('Proxy Settings');
        $this->_viewcontroller($data);
    }

    public function proxy_settings_data()
    {
        $this->ajax_check();

        $proxy_keyword  = trim($this->input->post("proxy_keyword",true));

        $display_columns = array("#",'id','proxy','port','admin_permission','username','password','actions');
        $search_columns = ["proxy","port","username","password"];

        $page = isset($_POST['page']) ? intval($_POST['page']) : 1;
        $start = isset($_POST['start']) ? intval($_POST['start']) : 0;
        $limit = isset($_POST['length']) ? intval($_POST['length']) : 10;
        $sort_index = isset($_POST['order'][0]['column']) ? strval($_POST['order'][0]['column']) : 1;
        $sort = isset($display_columns[$sort_index]) ? $display_columns[$sort_index] : 'id';
        $order = isset($_POST['order'][0]['dir']) ? strval($_POST['order'][0]['dir']) : 'desc';
        $order_by=$sort." ".$order;

        $where_custom="user_id = ".$this->user_id;

        if ($proxy_keyword != '') {

            foreach ($search_columns as $key => $value) 
                $temp[] = $value." LIKE "."'%$proxy_keyword%'";

            $imp = implode(" OR ", $temp);
            $where_custom .=" AND (".$imp.") ";
        }

        $table = "config_proxy";
        $this->db->where($where_custom);
        $info = $this->basic->get_data($table,$where='',$select='',$join='',$limit,$start,$order_by,$group_by='');

        $this->db->where($where_custom);
        $total_rows_array = $this->basic->count_row($table,$where='',$count="id",$join,$group_by='');
        $total_result = $total_rows_array[0]['total_rows'];

        for ($i=0; $i < count($info) ; $i++) { 

            if($this->session->userdata("user_type") == "Admin") {
                if($info[$i]['admin_permission'] == 'everyone') {

                    $info[$i]['admin_permission'] = "<div class='badge badge-primary pointer text-center'>".ucwords($info[$i]['admin_permission'])."</div>";

                } else {

                    $info[$i]['admin_permission'] = "<div class='badge badge-warning pointer text-center'>".ucwords($info[$i]['admin_permission'])."</div>";

                }

            } else {

                unset($display_columns[4]);
            }

            $info[$i]['actions'] = "<div><a class='btn btn-outline-warning btn-circle edit_proxy' href='#' table_id='".$info[$i]['id']."'><i class='fas fa-edit'></i></a>&nbsp;&nbsp;<a class='btn btn-outline-danger btn-circle delete_proxy' href='#' table_id='".$info[$i]['id']."'><i class='fas fa-trash-alt'></i></a></div>";
        }

        $data['draw'] = (int)$_POST['draw'] + 1;
        $data['recordsTotal'] = $total_result;
        $data['recordsFiltered'] = $total_result;
        $data['data'] = convertDataTableResult($info, $display_columns ,$start,$primary_key="id");

        echo json_encode($data);
    }

    public function insert_proxy()
    {
        if(!$_POST) exit();
        $this->ajax_check();

        $result = [];
        $insert_proxy_data = [];
        $user_type = $this->session->userdata("user_type");

        $insert_proxy_data['proxy'] = trim(strip_tags($this->input->post("proxy",true)));
        $insert_proxy_data['port'] = trim(strip_tags($this->input->post("proxy_port",true)));
        $insert_proxy_data['username'] = trim(strip_tags($this->input->post("proxy_username",true)));
        $insert_proxy_data['password'] = trim(strip_tags($this->input->post("proxy_password",true)));
        $insert_proxy_data['admin_permission'] = $this->input->post("permission",true);
        $insert_proxy_data['user_id'] = $this->user_id;

        if($user_type == "Member") {
            $insert_proxy_data ['admin_permission'] = "only me";
        }

        $table = "config_proxy";
        if($this->basic->insert_data($table,$insert_proxy_data)) {

            $result['status'] = "1";
            $result['message'] = $this->lang->line("Proxy information have been added successfully.");

        } else {

            $result['status'] = "0";
            $result['message'] = $this->lang->line("something went wrong, please try once again.");
        }

        echo json_encode($result); 
    }

    public function ajax_update_proxy_info()
    {
        $this->ajax_check();

        $table_id = $this->input->post("table_id",true);
        if($table_id == "" || $table_id == "0") exit;

        $get_proxy_data = $this->basic->get_data("config_proxy",array("where"=>array("id"=>$table_id,"user_id"=>$this->user_id)));

        $form_html = '
            <div class="row">
                <div class="col-12">
                    <form action="#" method="POST" id="update_proxy_form">
                        <input type="hidden" name="table_id" value="'.$get_proxy_data[0]['id'].'">
                        <div class="row">
                            <div class="col-12">
                                <div class="form-group">
                                    <label>'.$this->lang->line('Proxy').'</label>
                                    <input type="text" class="form-control" id="updated_proxy" name="proxy" value="'.$get_proxy_data[0]['proxy'].'">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>'.$this->lang->line('Proxy Port').'</label>
                                    <input type="text" class="form-control" id="updated_proxy_port" name="proxy_port" value="'.$get_proxy_data[0]['port'].'">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>'.$this->lang->line('Proxy Username').'</label>
                                    <input type="text" class="form-control" id="updated_proxy_username" name="proxy_username" value="'.$get_proxy_data[0]['username'].'">
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="form-group">
                                    <label>'.$this->lang->line('Proxy Password').'</label>
                                    <input type="text" class="form-control" id="updated_proxy_password" name="proxy_password" value="'.$get_proxy_data[0]['password'].'">
                                </div>
                            </div>';

                            if($this->session->userdata("user_type") == "Admin") {
                                $permission = $get_proxy_data[0]['admin_permission'];

                                if($permission == "everyone") $everyone = "checked";
                                else $everyone = "";

                                if($permission == "only me") $onlyme = "checked";
                                else $onlyme = "";

                                $form_html .='
                                    <div class="col-12">
                                        <div class="form-group">
                                            <label>'.$this->lang->line('Proxy Permission').'</label>
                                            <div class="custom-switches-stacked mt-2">
                                                <div class="row">   
                                                    <div class="col-6">
                                                        <label class="custom-switch">
                                                            <input type="radio" name="permission" value="everyone" class="permission custom-switch-input" '.$everyone.'>
                                                            <span class="custom-switch-indicator"></span>
                                                            <span class="custom-switch-description">'.$this->lang->line('Everyone').'</span>
                                                        </label>
                                                    </div>                        
                                                    <div class="col-6">
                                                        <label class="custom-switch">
                                                            <input type="radio" name="permission" value="only me" class="permission custom-switch-input" '.$onlyme.'>
                                                            <span class="custom-switch-indicator"></span>
                                                            <span class="custom-switch-description">'.$this->lang->line('Only me').'</span>
                                                        </label>
                                                    </div>
                                                </div>                                  
                                            </div>
                                        </div> 
                                    </div>';
                            }

                            $form_html .= '
                                        </div>
                                    </form>
                                </div>
                            </div>';
        echo $form_html;
    }

    public function update_proxy_settings()
    {
        if(!$_POST) exit();
        $this->ajax_check();

        $table_id = trim($this->input->post("table_id",true));

        $result = [];
        $update_proxy_data = [];
        $user_type = $this->session->userdata("user_type");

        $update_proxy_data['proxy'] = trim(strip_tags($this->input->post("proxy",true)));
        $update_proxy_data['port'] = trim(strip_tags($this->input->post("proxy_port",true)));
        $update_proxy_data['username'] = trim(strip_tags($this->input->post("proxy_username",true)));
        $update_proxy_data['password'] = trim(strip_tags($this->input->post("proxy_password",true)));
        $update_proxy_data['admin_permission'] = $this->input->post("permission",true);
        $update_proxy_data['user_id'] = $this->user_id;

        if($user_type == "Member") {
            $update_proxy_data ['admin_permission'] = "only me";
        }

        $table = "config_proxy";
        if($this->basic->update_data($table,["id"=>$table_id,"user_id"=>$this->user_id],$update_proxy_data)) {

            $result['status'] = "1";
            $result['message'] = $this->lang->line("Proxy information have been updated successfully.");

        } else {

            $result['status'] = "0";
            $result['message'] = $this->lang->line("something went wrong, please try once again.");
        }

        echo json_encode($result); 
    }

    public function delete_proxy()
    {
        $this->ajax_check();

        $table_id = $this->input->post("table_id",true);
        if($table_id == "" || $table_id == "0") exit;

        if($this->basic->delete_data("config_proxy",array("id"=>$table_id,"user_id"=>$this->user_id))) {
            echo "1";
        } else {
            echo "0";
        }
    }
    /* end of proxy settings */
}

