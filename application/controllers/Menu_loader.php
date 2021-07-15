<?php

require_once("Home.php"); // loading home controller

/**
* @category controller
* class Admin
*/


class Menu_loader extends Home
{

	public function __construct()
	{
	    parent::__construct();

	    if ($this->session->userdata('logged_in') != 1) {
	    	redirect('home/login_page', 'location');
	    }

	    // if ($this->session->userdata('user_type') != 'Admin') {
	    // 	redirect('home/login_page', 'location');
	    // }

	    $this->important_feature();
	    $this->periodic_check();

	}


	public function analysis_tools()
	{
		$data = array();
		$data['body'] = "menu_blocks/analysis_tools";
		$data['page_title'] = $this->lang->line("Analysis Tools");
		$this->_viewcontroller($data);
	}

	public function utlities()
	{
		$data = array();
		$data['body'] = "menu_blocks/utlities";
		$data['page_title'] = $this->lang->line("Utilities");
		$this->_viewcontroller($data);
	}

	public function url_shortner()
	{
		$data = array();
		$data['body'] = "menu_blocks/url_shortner";
		$data['page_title'] = $this->lang->line("URL Shortner");
		$this->_viewcontroller($data);
	}

	public function keyword_position_tracking()
	{
		$data = array();
		$data['body'] = "menu_blocks/keyword_position_tracking";
		$data['page_title'] = $this->lang->line("Keyword Tracking");
		$this->_viewcontroller($data);
	}
	
	public function security_tools()
	{
		$data = array();
		$data['body'] = "menu_blocks/security_tools";
		$data['page_title'] = $this->lang->line("Security Tools");
		$this->_viewcontroller($data);
	}
	
	public function google_adwars_scrapper()
	{
		$data = array();
		$data['body'] = "menu_blocks/google_adwars_scrapper";
		$data['page_title'] = $this->lang->line("Google Adward Scrapper");
		$this->_viewcontroller($data);
	}
	
	public function code_minifier()
	{
		$data = array();
		$data['body'] = "menu_blocks/code_minifier";
		$data['page_title'] = $this->lang->line("Code Minifier");
		$this->_viewcontroller($data);
	}

	public function backlink_ping()
	{
		$data = array();
		$data['body'] = "menu_blocks/backlink_ping";
		$data['page_title'] = $this->lang->line("BackLink & Ping");
		$this->_viewcontroller($data);
	}
	
	public function native_widget()
	{
		$data = array();
		$data['body'] = "menu_blocks/native_widget";
		$data['page_title'] = $this->lang->line("Native Widget");
		$this->_viewcontroller($data);
	}

}