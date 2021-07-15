<?php

require_once("Home.php"); // loading home controller

class Server_info extends Home
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
    	$this->server_info();
    }

    public function server_info()
    {
        $data['body'] = 'domain_analysis/server_info';
        $data['page_title'] = $this->lang->line("Server Information");
        $this->_viewcontroller($data);
    }   

    public function server_info_action()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') :
            redirect('home/access_forbidden', 'location');     
        endif;       

        $this->load->library('web_common_report');
        $url_lists = array();
        $url_values = explode(',',strip_tags($this->input->post('domain_name',true)));

        $str = "<div class='card'>
                    <div class='card-header'>
                      <h4><i class='fas fa-server'></i> ".$this->lang->line("Server Information")."</h4>
                    </div>
                    <div class='card-body'>";

        $str .="<div class='row'>";
        $str .="<div class='col-12 col-sm-12 col-md-4'>
                  <ul class='nav nav-pills flex-column' id='myTab4' role='tablist'>";
        if (count($url_values) <= 50) {
           $tab = 0;
           foreach ($url_values as $key => $value) {
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
        }

        $str.="</ul>
                </div>";
        //col end
        $str.="<div class='col-12 col-sm-12 col-md-8'>
                  <div class='tab-content no-padding' id='myTab2Content'>";
        if (count($url_values) <= 50) {

            $tab = 0;
            foreach ($url_values as $url_value) {
                $tab++;
                $url_value = trim($url_value);
                if (is_valid_url($url_value) === TRUE || is_valid_domain_name($url_value) === TRUE) {
                    $server = '';
                    $connection = '';
                    $response = $this->web_common_report->get_header_response($url_value);

                    $response = explode(PHP_EOL, $response);
                   
                    foreach ($response as $single_response) {
                        $semicolon_position = strpos($single_response, ':');
                        if ($semicolon_position !== FALSE) {
                            $title = substr($single_response, 0, $semicolon_position);
                            $value = str_replace($title . ': ','',$single_response);
                            if($title == 'Server')
                                $server = $value;
                            if($title == 'Connection')
                                $connection = $value;
                        }
                    }
                }
                if ($tab == 1) {
                    $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                            <ul class='list-group'>";

                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Server')."<span class='badge badge-primary badge-pill'>".$server."</span></li>";
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Connection')."<span class='badge badge-primary badge-pill'>".$connection."</span></li>";

                    $str.= "</ul></div>";
                }
                else{
                    $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                            <ul class='list-group'>";

                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Server')."<span class='badge badge-primary badge-pill'>".$server."</span></li>";
                            $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Connection')."<span class='badge badge-primary badge-pill'>".$connection."</span></li>";
                    
                    $str.= "</ul></div>";
                }


            }
        }

        $str.="</div>
                </div>";
        echo $str.="</div></div></div>";

            
    } 

}    