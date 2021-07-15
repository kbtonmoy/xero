<?php

require_once("Home.php"); // loading home controller

class Dns_info extends Home
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
    	$this->dns_info();
    }

    public function dns_info()
    {
        $data['body'] = 'domain_analysis/info';
        $data['page_title'] = $this->lang->line("DNS Information");
        $this->_viewcontroller($data);
    }   

    public function dns_info_action()
    {
        if($_SERVER['REQUEST_METHOD'] === 'GET') :
            redirect('home/access_forbidden', 'location');     
        endif;       

        $this->load->library('web_common_report');

        $url_lists = array();
        $url_values = explode(',',strip_tags($this->input->post('domain_name',true)));
        $str = "<div class='card'>
                    <div class='card-header'>
                      <h4><i class='fas fa-globe'></i> ".$this->lang->line("DNS Information")."</h4>
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
              $check_data = $this->web_common_report->dns_information($url_value);
              
              $first_element = $check_data[0];
              if ($tab == 1) {

               $str.="<div class='tab-pane fade active show' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                       <ul class='list-group'>";

               $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Host')."<span class='badge badge-primary badge-pill'>".$first_element['host']."</span></li>";
               $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Type')."<span class='badge badge-primary badge-pill'>".$first_element['type']."</span></li>";
               $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('IP')."<span class='badge badge-primary badge-pill'>".$first_element['ip']."</span></li>";
               $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Class')."<span class='badge badge-primary badge-pill'>".$first_element['class']."</span></li>";
               $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('TTL')."<span class='badge badge-primary badge-pill'>".$first_element['ttl']."</span></li>";
               $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('TTL')."<a href='#' class='btn btn-icon icon-left btn-danger details float-right' data-details=".json_encode($check_data)."><i class='fas fa-info-circle'></i> ".$this->lang->line("Details")."</a></li>";

               $str.= "</ul></div>";
              }
              else{
                $str.="<div class='tab-pane fade' id='home".$tab."' role='tabpanel' aria-labelledby='home-tab".$tab."'>
                        <ul class='list-group'>";

                      $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Host')."<span class='badge badge-primary badge-pill'>".$first_element['host']."</span></li>";
                      $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Type')."<span class='badge badge-primary badge-pill'>".$first_element['type']."</span></li>";
                      $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('IP')."<span class='badge badge-primary badge-pill'>".$first_element['ip']."</span></li>";
                      $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('Class')."<span class='badge badge-primary badge-pill'>".$first_element['class']."</span></li>";
                      $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('TTL')."<span class='badge badge-primary badge-pill'>".$first_element['ttl']."</span></li>";
                      $str.="<li class='list-group-item d-flex justify-content-between align-items-center'>".$this->lang->line('TTL')."<a href='#' class='btn btn-icon icon-left btn-danger details float-right' data-details=".json_encode($check_data)."><i class='fas fa-info-circle'></i> ".$this->lang->line("Details")."</a></li>";
                
                $str.= "</ul></div>";
              }

           }
         }
        }
        $str.="</div>
                </div>";

       
       $str.="</div></div></div>";  

         echo json_encode(array('url_lists' => $str));       
    } 

}    