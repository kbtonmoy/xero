<?php
require_once("Home.php"); // loading home controller

/**
* @category controller
* class Admin
*/

class Js_controller extends Home
{
	

	function get_ip()
	{
		$ip[0]=$this->real_ip();
		echo $_GET['callback']."(".json_encode($ip).")";
	}

	public function server_info()
	{
		header('Access-Control-Allow-Origin: *');
		$time=date("Y-m-d H:i:s");
	   
		$ip=$this->real_ip();
		$website_code=$_POST['website_code'];
		$browser_name=$_POST['browser_name'];
		$browser_version=$_POST['browser_version'];
		$device=$_POST['device'];
		$mobile_desktop=$_POST['mobile_desktop'];
		$referrer=$_POST['referrer'];
		$current_url=$_POST['current_url'];
		$only_domain = get_domain_only($current_url);
		$cookie_value=$_POST['cookie_value'];
		$is_new=$_POST['is_new'];
		$session_value=$_POST['session_value'];
		$browser_rawdata=$_POST['browser_rawdata'];
		
		$this->load->library('Web_common_report');
		

		$where['where'] = array('domain_code'=>$website_code);
		$domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select=array('id','domain_name','user_id'));
		$domain_list_id = $domain_info[0]['id'];
		$domain_name = $domain_info[0]['domain_name'];
		$user_id = $domain_info[0]['user_id'];

		
		/**Get Country code and country name***/
		
		if($ip){
			
			/*** Check ip is already in table or not, if in table then don't call for api ****/
			
			$where['where']=array('ip'=>$ip,'country !='=>'','domain_list_id'=>$domain_list_id);
			$select=array('country','city','org','latitude','longitude','postal','cookie_value','session_value');
			$existing_ip_info= $this->basic->get_data('visitor_analysis_domain_list_data',$where,$select,'', $limit = '1', $start = '0');
			
			if(isset($existing_ip_info[0]['country']) && $existing_ip_info[0]['country']!=''){
			
			 	$user_country=isset($existing_ip_info[0]['country']) ? $existing_ip_info[0]['country']: "";
				$user_city=isset($existing_ip_info[0]['city'])? $existing_ip_info[0]['city']: "";
			 	$user_org=isset($existing_ip_info[0]['org']) ? $existing_ip_info[0]['org']:"";
			 	$user_latitude=isset($existing_ip_info[0]['latitude']) ? $existing_ip_info[0]['latitude'] :"";
				$user_longitude=isset($existing_ip_info[0]['longitude']) ? $existing_ip_info[0]['longitude'] : "";
			 	$user_postal=isset($existing_ip_info[0]['postal']) ? $existing_ip_info[0]['postal'] : "";
			}
			
			else{
					$ip_info= $this->web_common_report->ip_information($ip);
					
					$user_country=isset($ip_info['country']) ? $ip_info['country']: "";
					$user_city=isset($ip_info['city'])? $ip_info['city']: "";
					$user_org=isset($ip_info['org'])?$ip_info['org']:"";
					$user_latitude=isset($ip_info['latitude'])?$ip_info['latitude']:"";
					$user_longitude=isset($ip_info['longitude'])?$ip_info['longitude']:"";
					$user_postal=isset($ip_info['postal'])?$ip_info['postal']:"";
			}
			
		 }
		 
		if(!isset($user_country))
		 	$user_country="";
		
		if(!isset($country_code))
			$country_code="";		
				
		// $browser_rawdata=result_encode($browser_rawdata);

		$where['where']=array('cookie_value'=>$cookie_value,'domain_list_id'=>$domain_list_id);
		$select=array('cookie_value','session_value');
		$existing_cookie_info= $this->basic->get_data('visitor_analysis_domain_list_data',$where,$select,'', $limit = '1', $start = '0');

		if(isset($existing_cookie_info[0]['cookie_value'])){
			$is_new = 0;
		}
		else
			$is_new = 1;

		if(strtolower($only_domain) == strtolower($domain_name)) {
			$this->basic->insert_data('visitor_analysis_domain_list_data', [
				'domain_list_id' => $domain_list_id,
				'user_id' => $user_id,
				'domain_code' => $website_code,
				'ip' => $ip,
				'country' => $user_country,
				'city' => $user_city,
				'org' => $user_org,
				'latitude' => $user_latitude,
				'longitude' => $user_longitude,
				'postal' => $user_postal,
				'os' => $device,
				'device' => $mobile_desktop,
				'browser_name' => $browser_name,
				'browser_version' => $browser_version,
				'date_time' => $time,
				'referrer' => $referrer,
				'visit_url' => $current_url,
				'cookie_value' => $cookie_value,
				'is_new' => $is_new,
				'session_value' => $session_value,
				'browser_rawdata' => $browser_rawdata
			]);
		}
	}

	public function scroll_info()
	{
		header('Access-Control-Allow-Origin: *');
		$time=date("Y-m-d H:i:s");	   
		$ip=$this->real_ip();
		$website_code=$_POST['website_code'];
		$current_url=$_POST['current_url'];
		$only_domain = get_domain_only($current_url);
		$cookie_value=$_POST['cookie_value'];
		$session_value=$_POST['session_value'];

		$where['where'] = array('domain_code'=>$website_code);
		$domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select=array('id','domain_name'));
		$domain_list_id = $domain_info[0]['id'];
		$domain_name = $domain_info[0]['domain_name'];
		
		$q="Update `visitor_analysis_domain_list_data` set  last_scroll_time='$time' WHERE domain_list_id='$domain_list_id' and visit_url='$current_url' and cookie_value='$cookie_value' and session_value='$session_value' order by id desc limit 1";
		if(strtolower($only_domain) == strtolower($domain_name))
			$this->basic->execute_complex_query($q);
	}

	public function click_info()
	{
		header('Access-Control-Allow-Origin: *');
		$time=date("Y-m-d H:i:s");
	   
		$ip=$this->real_ip();
		$website_code=$_POST['website_code'];
		$current_url=$_POST['current_url'];
		$only_domain = get_domain_only($current_url);
		$cookie_value=$_POST['cookie_value'];
		$session_value=$_POST['session_value'];

		$where['where'] = array('domain_code'=>$website_code);
		$domain_info = $this->basic->get_data('visitor_analysis_domain_list',$where,$select=array('id','domain_name'));
		$domain_list_id = $domain_info[0]['id'];
		$domain_name = $domain_info[0]['domain_name'];
		
		$q="Update `visitor_analysis_domain_list_data` set  last_engagement_time='$time' WHERE domain_list_id='$domain_list_id' and visit_url='$current_url' and cookie_value='$cookie_value' and session_value='$session_value' order by id desc limit 1";
		if(strtolower($only_domain) == strtolower($domain_name))
			$this->basic->execute_complex_query($q);
	}



	public function client()
	{
		header('Access-Control-Allow-Origin: *');
		header('Content-Type: application/javascript');
		$content = "/*
			Title: SiteSpy Client v2.0
			Author: XerOne IT
			Copyright: XerOne IT
			URL: https://xeroneit.net
			*/

			var ip_link='".base_url('js_controller/get_ip')."';
			var server_link='".base_url('js_controller/server_info')."';
			var scroll_server_link='".base_url('js_controller/scroll_info')."';
			var click_server_link='".base_url('js_controller/click_info')."';
			var browser_js_link='".base_url('js/useragent.js')."';


			function document_height(){
				var body = document.body,
			    html = document.documentElement;
				var height = Math.max( body.scrollHeight, body.offsetHeight, 
			                       html.clientHeight, html.scrollHeight, html.offsetHeight );
				return height;
			}

			function getScrollTop(){
			    if(typeof pageYOffset!= 'undefined'){
			        //most browsers except IE before #9
			        return pageYOffset;
			    }
			    else{
			        var B= document.body; //IE 'quirks'
			        var D= document.documentElement; //IE with doctype
			        D= (D.clientHeight)? D: B;
			        return D.scrollTop;
			    }
			}


			function ajax_dolphin(link,data){
				  xhr = new XMLHttpRequest();
				  xhr.open('POST',link);
				  xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
				  xhr.send(data);
			}




			function get_browser_info(){
					    var ua=navigator.userAgent,tem,M=ua.match(/(opera|chrome|safari|firefox|msie|trident(?=\/))\/?\s*(\d+)/i) || []; 
					    if(/trident/i.test(M[1])){
					        tem=/\brv[ :]+(\d+)/g.exec(ua) || []; 
					        return {name:'IE',version:(tem[1]||'')};
					        }   
					    if(M[1]==='Chrome'){
					        tem=ua.match(/\bOPR\/(\d+)/)
					        if(tem!=null)   {return {name:'Opera', version:tem[1]};}
					        }   
					    M=M[2]? [M[1], M[2]]: [navigator.appName, navigator.appVersion, '-?'];
					    if((tem=ua.match(/version\/(\d+)/i))!=null) {M.splice(1,1,tem[1]);}
					    return {
					      name: M[0],
					      version: M[1]
					    };
			 }
			 
			 /*** Creating Cookie function ***/
			 function createCookie(name,value,days) {
			    if (days) {
			        var date = new Date();
			        date.setTime(date.getTime()+(days*24*60*60*1000));
			        var expires = '; expires='+date.toGMTString();
			    }
			    else var expires = '';
			    document.cookie = name+'='+value+expires+'; path=/';
			}

			/***Read Cookie function**/
			function readCookie(name) {
			    var nameEQ = name + '=';
			    var ca = document.cookie.split(';');
			    for(var i=0;i < ca.length;i++) {
			        var c = ca[i];
			        while (c.charAt(0)==' ') c = c.substring(1,c.length);
			        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
			    }
			    return null;
			}

			/*** Delete Cookie Function ***/
			function eraseCookie(name) {
			    createCookie(name,'',-1);
			}


			function time_difference(from_time,to_time){
				var differenceTravel = to_time.getTime() - from_time.getTime();
				var seconds = Math.floor((differenceTravel) / (1000));
				return seconds;
				
			}
			 
			function ajax_call(){
					
					/**Load browser plugin***/
					var y = document.createElement('script');
					y.src = browser_js_link;
					document.getElementsByTagName('head')[0].appendChild(y);
					
					/**after browser plugin loaded**/
					y.onload=function(){
					
							var ip;
							var device;
							var mobile_desktop;
							
							device=jscd.os;
							if(jscd.mobile){
								mobile_desktop='Mobile';
							}
							else{
								mobile_desktop='Desktop';
							}
							
							var browser_info=get_browser_info();
							var browser_name=browser_info.name;
							var browser_version=browser_info.version;
							
							var browser_rawdata = JSON.stringify(navigator.userAgent);
							// var website_code = document.getElementById('xero-domain-name').getAttribute('xero-data-name');
							var website_code = document.querySelector('script#xero-domain-name').getAttribute('xero-data-name');
							
							/**Get referer Address**/
							var referrer = document.referrer;
							
							/** Get Current url **/
							var current_url = window.location.href;
							
							/*** Get cookie value , if it is already set or not **/
							
							var cookie_value=readCookie('xerone_dolphin');
							var extra_value= new Date().getTime();
							
							/**if new visitor set the cookie value a random number***/
							if(cookie_value=='' || cookie_value==null || cookie_value === undefined){
								var is_new=1;
								var random_cookie_value=Math.floor(Math.random()*999999);
								random_cookie_value=random_cookie_value+extra_value.toString();
								createCookie('xerone_dolphin',random_cookie_value,1);
								cookie_value=random_cookie_value;
							}
							
							else{
								createCookie('xerone_dolphin',cookie_value,1);
								var is_new=0;
							}
							
							
							var session_value=sessionStorage.xerone_dolphin_session;
							
							if(session_value=='' || session_value==null || session_value === undefined){
								var random_session_value=Math.floor(Math.random()*999999);
								random_session_value=random_session_value+extra_value.toString();
								sessionStorage.xerone_dolphin_session=random_session_value;
								session_value=random_session_value;
							}
							
							/**if it is a new session then create session***/
							
							
							var data='website_code='+website_code+'&browser_name='+browser_name+'&browser_version='+browser_version+'&device='+device+'&mobile_desktop='+mobile_desktop+'&referrer='+referrer+'&current_url='+current_url+'&cookie_value='+cookie_value+'&is_new='+is_new+'&session_value='+session_value+'&browser_rawdata='+browser_rawdata;
							
								ajax_dolphin(server_link,data);
												
						
						/** Scrolling detection, if it is scrolling more than 50%  and after 5 seceond of last scroll then enter the time ****/
						
							var last_scroll_time;
							var scroll_track=0;
							var time_dif=0;
							
							window.onscroll	=	function(){
								
								 var  wintop = getScrollTop();
								 var  docheight = document_height();
								 var  winheight = window.innerHeight;
								 
								 var  scrolltrigger = 0.50;
								 
								 if  ((wintop/(docheight-winheight)) > scrolltrigger) {
								 
								 	scroll_track++;
									var to_time=new Date();
									
									if(scroll_track>1){
										time_dif=time_difference(last_scroll_time,to_time);
									}
									
									
									if(scroll_track==1 || time_dif>5){
										last_scroll_time=new Date();
										
										var data='website_code='+website_code+'&current_url='+current_url+'&cookie_value='+cookie_value+'&session_value='+session_value;
										ajax_dolphin(scroll_server_link,data);
										
										
									}
						   	 	}
						};		
						
						
						/*** track each engagement record. Enagagment is calculated by click function****/
						
							
							var last_click_time;
							var click_track=0;
							var click_time_dif=0;
							
							document.onclick	=  function(){
									click_track++;
									var to_time=new Date();
									
									if(click_track>1){
										click_time_dif=time_difference(last_click_time,to_time);
									}
									
									if(click_track==1 || click_time_dif>5){
										last_click_time=new Date();
										
										var data='website_code='+website_code+'&current_url='+current_url+'&cookie_value='+cookie_value+'&session_value='+session_value;
										ajax_dolphin(click_server_link,data);
										
									}	
							};	
					}
				}

			function init(){
				ajax_call();
			}

			init();
		";

		echo $content;
	}


}