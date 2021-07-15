<?php  
include("Facebook/autoload.php");

	class Fb_login
	{				
        public $app_id="";
		public $app_secret="";

	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->helper('my_helper');
		$this->CI->load->model('basic');

		$facebook_config=$this->CI->basic->get_data("facebook_rx_config",array("where"=>array("status"=>"1")));
		if(isset($facebook_config[0]))
		{			
			$this->app_id=$facebook_config[0]["app_id"];
			$this->app_secret=$facebook_config[0]["app_secret"];
		}
		
	}
	
	
	
	function login_for_user_access_token($redirect_url=""){
	
		if (session_status() == PHP_SESSION_NONE) {
		    session_start();
		}
		
		if($this->app_id=="" || $this->app_secret=="") return "";

		$fb = new Facebook\Facebook([
		  'app_id' => $this->app_id, // Replace {app-id} with your app id
		  'app_secret' => $this->app_secret,
		  'default_graph_version' => 'v2.2',
		]);
		
		$helper = $fb->getRedirectLoginHelper();

		$permissions = ['email']; // Optional permissions
		$loginUrl = $helper->getLoginUrl($redirect_url, $permissions);
	
		return '<a class="btn btn-block btn-social btn-facebook" href="'.htmlspecialchars($loginUrl).'"><span class="fab fa-facebook"></span> ThisIsTheLoginButtonForFacebook</a>';	
	}
	


	
	public function login_callback($redirect_url=""){
			$redirect_url=rtrim($redirect_url,'/');
			if (session_status() == PHP_SESSION_NONE) {
			    session_start();
			}
			
			$fb = new Facebook\Facebook([
			  'app_id' => $this->app_id, // Replace {app-id} with your app id
			  'app_secret' => $this->app_secret,
			  'default_graph_version' => 'v2.2',
			]);
			
			$user=array();
				
			$helper = $fb->getRedirectLoginHelper();
				try {
				  $accessToken = $helper->getAccessToken($redirect_url);
				   $response = $fb->get('/me?fields=id,name,email', $accessToken);
  					$user = $response->getGraphUser()->asArray();
				} catch(Facebook\Exceptions\FacebookResponseException $e) {
				  
				 return $user;
				  
				} catch(Facebook\Exceptions\FacebookSDKException $e) {
					$user['status']="0";
				    $user['message']= $e->getMessage();
					return $user;
				}				
		 
		 return $user;		
	}
			

		
	
}
	
	
