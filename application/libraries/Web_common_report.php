<?php  
	require_once('phpwhois-4.2.2/whois.main.php'); // including 
	require_once('Simple_html_dom.php');
	require_once( 'IXR_Library.php' );
	require_once ('Whois.php');


	class Web_common_report
	{

		public  $googlehost='toolbarqueries.google.com';
		public 	$googleua='Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.0.6) Gecko/20060728 Firefox/1.5';
		public $same_site_in_ip=array();

		public $country_list = array (
								  'AF' => 'AFGHANISTAN',
								  'AX' => 'ÅLAND ISLANDS',
								  'AL' => 'ALBANIA',
								  'BLANK' => 'ZANZIBAR',
								  'DZ' => 'ALGERIA (El Djazaïr)',
								  'AS' => 'AMERICAN SAMOA',
								  'AD' => 'ANDORRA',
								  'AO' => 'ANGOLA',
								  'AI' => 'ANGUILLA',
								  'AQ' => 'ANTARCTICA',
								  'AG' => 'ANTIGUA AND BARBUDA',
								  'AR' => 'ARGENTINA',
								  'AM' => 'ARMENIA',
								  'AW' => 'ARUBA',
								  'blank' => 'YUGOSLAVIA (Internet code still used)',
								  'AU' => 'AUSTRALIA',
								  'AT' => 'AUSTRIA',
								  'AZ' => 'AZERBAIJAN',
								  'BS' => 'BAHAMAS',
								  'BH' => 'BAHRAIN',
								  'BD' => 'BANGLADESH',
								  'BB' => 'BARBADOS',
								  'BY' => 'BELARUS',
								  'BE' => 'BELGIUM',
								  'BZ' => 'BELIZE',
								  'BJ' => 'BENIN',
								  'BM' => 'BERMUDA',
								  'BT' => 'BHUTAN',
								  'BO' => 'BOLIVIA',
								  'BQ' => 'BONAIRE, ST. EUSTATIUS, AND SABA',
								  'BA' => 'BOSNIA AND HERZEGOVINA',
								  'BW' => 'BOTSWANA',
								  'BV' => 'BOUVET ISLAND',
								  'BR' => 'BRAZIL',
								  'IO' => 'BRITISH INDIAN OCEAN TERRITORY',
								  'BN' => 'BRUNEI DARUSSALAM',
								  'BG' => 'BULGARIA',
								  'BF' => 'BURKINA FASO',
								  'BI' => 'BURUNDI',
								  'KH' => 'CAMBODIA',
								  'CM' => 'CAMEROON',
								  'CA' => 'CANADA',
								  'CV' => 'CAPE VERDE',
								  'KY' => 'CAYMAN ISLANDS',
								  'CF' => 'CENTRAL AFRICAN REPUBLIC',
								  'CD' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE (formerly Zaire)',
								  'CL' => 'CHILE',
								  'CN' => 'CHINA',
								  'CX' => 'CHRISTMAS ISLAND',
								  'CC' => 'COCOS (KEELING) ISLANDS',
								  'CO' => 'COLOMBIA',
								  'KM' => 'COMOROS',
								  'CG' => 'CONGO, REPUBLIC OF',
								  'CK' => 'COOK ISLANDS',
								  'CR' => 'COSTA RICA',
								  'CI' => 'CÔTE D\'IVOIRE (Ivory Coast)',
								  'HR' => 'CROATIA (Hrvatska)',
								  'CU' => 'CUBA',
								  'CW' => 'CURAÇAO',
								  'CY' => 'CYPRUS',
								  'CZ' => 'ZECH REPUBLIC',
								  'DK' => 'DENMARK',
								  'DJ' => 'DJIBOUTI',
								  'DM' => 'DOMINICA',
								  'DO'=>'Dominican Republic',
								  'DC' => 'DOMINICAN REPUBLIC',
								  'EC' => 'ECUADOR',
								  'EG' => 'EGYPT',
								  'SV' => 'EL SALVADOR',
								  'GQ' => 'EQUATORIAL GUINEA',
								  'ER' => 'ERITREA',
								  'EE' => 'ESTONIA',
								  'ET' => 'ETHIOPIA',
								  'FO' => 'FAEROE ISLANDS',
								  'FK' => 'FALKLAND ISLANDS (MALVINAS)',
								  'FJ' => 'FIJI',
								  'FI' => 'FINLAND',
								  'FR' => 'FRANCE',
								  'GF' => 'FRENCH GUIANA',
								  'PF' => 'FRENCH POLYNESIA',
								  'TF' => 'FRENCH SOUTHERN TERRITORIES',
								  'GA' => 'GABON',
								  'GM' => 'GAMBIA, THE',
								  'GE' => 'GEORGIA',
								  'DE' => 'GERMANY (Deutschland)',
								  'GH' => 'GHANA',
								  'GI' => 'GIBRALTAR',
								  'GB' => 'UNITED KINGDOM',
								  'GR' => 'GREECE',
								  'GL' => 'GREENLAND',
								  'GD' => 'GRENADA',
								  'GP' => 'GUADELOUPE',
								  'GU' => 'GUAM',
								  'GT' => 'GUATEMALA',
								  'GG' => 'GUERNSEY',
								  'GN' => 'GUINEA',
								  'GW' => 'GUINEA-BISSAU',
								  'GY' => 'GUYANA',
								  'HT' => 'HAITI',
								  'HM' => 'HEARD ISLAND AND MCDONALD ISLANDS',
								  'HN' => 'HONDURAS',
								  'HK' => 'HONG KONG (Special Administrative Region of China)',
								  'HU' => 'HUNGARY',
								  'IS' => 'ICELAND',
								  'IN' => 'INDIA',
								  'ID' => 'INDONESIA',
								  'IR' => 'IRAN (Islamic Republic of Iran)',
								  'IQ' => 'IRAQ',
								  'IE' => 'IRELAND',
								  'IM' => 'ISLE OF MAN',
								  'IL' => 'ISRAEL',
								  'IT' => 'ITALY',
								  'JM' => 'JAMAICA',
								  'JP' => 'JAPAN',
								  'JE' => 'JERSEY',
								  'JO' => 'JORDAN (Hashemite Kingdom of Jordan)',
								  'KZ' => 'KAZAKHSTAN',
								  'KE' => 'KENYA',
								  'KI' => 'KIRIBATI',
								  'KP' => 'KOREA (Democratic Peoples Republic of [North] Korea)',
								  'KR' => 'KOREA (Republic of [South] Korea)',
								  'KW' => 'KUWAIT',
								  'KG' => 'KYRGYZSTAN',
								  'LA' => 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC',
								  'LV' => 'LATVIA',
								  'LB' => 'LEBANON',
								  'LS' => 'LESOTHO',
								  'LR' => 'LIBERIA',
								  'LY' => 'LIBYA (Libyan Arab Jamahirya)',
								  'LI' => 'LIECHTENSTEIN (Fürstentum Liechtenstein)',
								  'LT' => 'LITHUANIA',
								  'LU' => 'LUXEMBOURG',
								  'MO' => 'MACAO (Special Administrative Region of China)',
								  'MK' => 'MACEDONIA (Former Yugoslav Republic of Macedonia)',
								  'MG' => 'MADAGASCAR',
								  'MW' => 'MALAWI',
								  'MY' => 'MALAYSIA',
								  'MV' => 'MALDIVES',
								  'ML' => 'MALI',
								  'MT' => 'MALTA',
								  'MH' => 'MARSHALL ISLANDS',
								  'MQ' => 'MARTINIQUE',
								  'MR' => 'MAURITANIA',
								  'MU' => 'MAURITIUS',
								  'YT' => 'MAYOTTE',
								  'MX' => 'MEXICO',
								  'FM' => 'MICRONESIA (Federated States of Micronesia)',
								  'MD' => 'MOLDOVA',
								  'MC' => 'MONACO',
								  'MN' => 'MONGOLIA',
								  'ME' => 'MONTENEGRO',
								  'MS' => 'MONTSERRAT',
								  'MA' => 'MOROCCO',
								  'MZ' => 'MOZAMBIQUE (Moçambique)',
								  'MM' => 'MYANMAR (formerly Burma)',
								  'NA' => 'NAMIBIA',
								  'NR' => 'NAURU',
								  'NP' => 'NEPAL',
								  'NL' => 'NETHERLANDS',
								  'AN' => 'NETHERLANDS ANTILLES (obsolete)',
								  'NC' => 'NEW CALEDONIA',
								  'NZ' => 'NEW ZEALAND',
								  'NI' => 'NICARAGUA',
								  'NE' => 'NIGER',
								  'NG' => 'NIGERIA',
								  'NU' => 'NIUE',
								  'NF' => 'NORFOLK ISLAND',
								  'MP' => 'NORTHERN MARIANA ISLANDS',
								  'ND' => 'NORWAY',
								  'NO' => 'NORWAY',
								  'OM' => 'OMAN',
								  'PK' => 'PAKISTAN',
								  'PW' => 'PALAU',
								  'PS' => 'PALESTINIAN TERRITORIES',
								  'PA' => 'PANAMA',
								  'PG' => 'PAPUA NEW GUINEA',
								  'PY' => 'PARAGUAY',
								  'PE' => 'PERU',
								  'PH' => 'PHILIPPINES',
								  'PN' => 'PITCAIRN',
								  'PL' => 'POLAND',
								  'PT' => 'PORTUGAL',
								  'PR' => 'PUERTO RICO',
								  'QA' => 'QATAR',
								  'RE' => 'RÉUNION',
								  'RO' => 'ROMANIA',
								  'RU' => 'RUSSIAN FEDERATION',
								  'RW' => 'RWANDA',
								  'BL' => 'SAINT BARTHÉLEMY',
								  'SH' => 'SAINT HELENA',
								  'KN' => 'SAINT KITTS AND NEVIS',
								  'LC' => 'SAINT LUCIA',
								  'MF' => 'SAINT MARTIN (French portion)',
								  'PM' => 'SAINT PIERRE AND MIQUELON',
								  'VC' => 'SAINT VINCENT AND THE GRENADINES',
								  'WS' => 'SAMOA (formerly Western Samoa)',
								  'SM' => 'SAN MARINO (Republic of)',
								  'ST' => 'SAO TOME AND PRINCIPE',
								  'SA' => 'SAUDI ARABIA (Kingdom of Saudi Arabia)',
								  'SN' => 'SENEGAL',
								  'RS' => 'SERBIA (Republic of Serbia)',
								  'SC' => 'SEYCHELLES',
								  'SL' => 'SIERRA LEONE',
								  'SG' => 'SINGAPORE',
								  'SX' => 'SINT MAARTEN',
								  'SK' => 'SLOVAKIA (Slovak Republic)',
								  'SI' => 'SLOVENIA',
								  'SB' => 'SOLOMON ISLANDS',
								  'SO' => 'SOMALIA',
								  'ZA' => 'ZAMBIA (formerly Northern Rhodesia)',
								  'GS' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
								  'SS' => 'SOUTH SUDAN',
								  'ES' => 'SPAIN (España)',
								  'LK' => 'SRI LANKA (formerly Ceylon)',
								  'SD' => 'SUDAN',
								  'SR' => 'SURINAME',
								  'SJ' => 'SVALBARD AND JAN MAYE',
								  'SZ' => 'SWAZILAND',
								  'SE' => 'SWEDEN',
								  'CH' => 'SWITZERLAND (Confederation of Helvetia)',
								  'SY' => 'SYRIAN ARAB REPUBLIC',
								  'TW' => 'TAIWAN ("Chinese Taipei" for IOC)',
								  'TJ' => 'TAJIKISTAN',
								  'TZ' => 'TANZANIA',
								  'TH' => 'THAILAND',
								  'TL' => 'TIMOR-LESTE (formerly East Timor)',
								  'TG' => 'TOGO',
								  'TK' => 'TOKELAU',
								  'TO' => 'TONGA',
								  'TT' => 'TRINIDAD AND TOBAGO',
								  'TN' => 'TUNISIA',
								  'TR' => 'TURKEY',
								  'TM' => 'TURKMENISTAN',
								  'TC' => 'TURKS AND CAICOS ISLANDS',
								  'TV' => 'TUVALU',
								  'UG' => 'UGANDA',
								  'UA' => 'UKRAINE',
								  'AE' => 'UNITED ARAB EMIRATES',
								  'US' => 'UNITED STATES',
								  'UM' => 'UNITED STATES MINOR OUTLYING ISLANDS',
								  'UY' => 'URUGUAY',
								  'UZ' => 'UZBEKISTAN',
								  'VU' => 'VANUATU',
								  'VA' => 'VATICAN CITY (Holy See)',
								  'VN' => 'VIET NAM',
								  'VG' => 'VIRGIN ISLANDS, BRITISH',
								  'VI' => 'VIRGIN ISLANDS, U.S.',
								  'WF' => 'WALLIS AND FUTUNA',
								  'EH' => 'WESTERN SAHARA (formerly Spanish Sahara)',
								  'YE' => 'YEMEN (Yemen Arab Republic)',
								  'ZW' => 'ZIMBABWE',
								);

		public $ping_link = array(
			'http://www.blogpeople.net/servlet/weblogUpdates',
			'http://blogpeople.net/ping',
			);
			
			
		public $back_link_url=Array(
		
		 		"http://similarsites.com/site/[url]",
			    "http://alexa.com/siteinfo/[url]",
			    "http://builtwith.com/[url]",
			    "http://siteadvisor.cn/sites/[url]/summary/",
			    "http://whois.domaintools.com/[url]",
				"http://whoisx.co.uk/[url]",
			    "http://aboutdomain.org/info/[url]/",
			    //"http://aboutus.org/[url]",
			    "http://validator.w3.org/check?uri=[url]",
			    //"http://sitepricechecker.com/[url]",
			    "http://script3.prothemes.biz/[url]",
			    "http://websitevaluebot.com/[url]",
			    // "http://listenarabic.com/search?q=[url]&sa=Search",
			    "http://keywordspy.com/research/search.aspx?q=[url]&tab=domain-overview",
			    "http://aboutdomain.org/backlinks/[url]/",
			    "http://who.is/whois/[url]/",
			    //"http://protect-x.com/info/[url]",
			    "https://siteanalytics.compete.com/[url]/",
			    "http://sitedossier.com/site/[url]",
			    "http://wholinkstome.com/url/[url]",
			    "http://serpanalytics.com/#competitor/[url]/summary/1",
			    //"http://hosts-file.net/default.asp?s=[url]",
			    "http://robtex.com/dns/[url].html",
			    //"https://quantcast.com/[url]",
			    "http://toolbar.netcraft.com/site_report?url=[url]",
			    //"http://aboutthedomain.com/[url]",
			    "http://websiteshadow.com/[url]",
			    "http://surcentro.com/en/info/[url]",
			    "http://onlinewebcheck.com/check.php?url=[url]",
			    //"http://socialwebwatch.com/stats.php?url=[url]",
			    "http://statscrop.com/www/[url]",
			    "http://statmyweb.com/site/[url]",
			    //"http://tools.quicksprout.com/analyze/[url]",
			    "http://whois.net/whois/[url]",
			    "http://iwebchk.com/reports/view/[url]",
			    "http://siteadvisor.com/sites/[url]",
			    "http://google.com/safebrowsing/diagnostic?site=[url]",
			    "https://safeweb.norton.com/report/show?url=[url]",
			    "https://mywot.com/en/scorecard/[url]",
			    "http://sitecheck.sucuri.net/results/[url]",
			    "http://sitejabber.com/search/[url]",
			    "http://avgthreatlabs.com/website-safety-reports/domain/[url]",
			    "http://siteprice.org/AnalyzeSite.aspx?url=[url]",
			    //"http://similarweb.com/website/[url]",
			    "http://dnscheck.pingdom.com/?domain=[url]",
				"http://www.myip.net/[url]",
				"http://hqindex.org/[url]",
				"http://hqindex.org/[url]",
				"http://statsie.com/[url]",
				"http://toolbar.netcraft.com/site_report?url=[url]#last_reboot",
				"http://estibot.com/appraise.php?a=appraise&data=[url]",
				"http://onthesamehost.com/?q=[url]",
				
			);
				
		public $user_id; 
		public $proxy_ip;
		public $proxy_auth_pass;
		public $session_id;
        public $app_id="";
		public $app_secret="";



	function __construct()
	{
		$this->CI =& get_instance();
		$this->CI->load->database();
		$this->CI->load->helper('my_helper');
		$this->CI->load->library('session');
		$this->CI->load->model('basic');

		if($this->CI->session->userdata("user_id") != '')
			$this->user_id=$this->CI->session->userdata("user_id");
		else
		{
			$q="select id from users where user_type='Admin' and deleted='0' and status='1' ORDER BY rand() LIMIT 1";
			$query=$this->CI->db->query($q);
			$results=$query->result_array();
			if(!empty($results))
				$this->user_id = $results[0]['id'];
		}
		
		$this->session_id=$this->CI->session->userdata("session_id");
		
		
		$q="select * from config_proxy where deleted='0' and (user_id='{$this->user_id}' or  admin_permission='everyone') ORDER BY rand() LIMIT 1";
		$query=$this->CI->db->query($q);
		$results=$query->result_array();
		if(count($results)==0) {
			$this->proxy_ip="";
			$this->proxy_auth_pass="";
		}
		else{
			foreach($results as $info){	
				$this->proxy_ip=$info['proxy'].":".$info['port'];
				if($info['username']=='' || $info['username']=='NULL'){
					$this->proxy_auth_pass="";
				}
				else{
					$this->proxy_auth_pass=$info['username'].":".$info['password'];
				}
				
			}
		}


	}



	function get_alexa_rank($domain)
	{
		$url="https://mostofa.club/development/alexa/index.php?domain={$domain}";
		$ch = curl_init(); // initialize curl handle
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
		curl_setopt($ch, CURLOPT_AUTOREFERER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
		curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
		curl_setopt($ch, CURLOPT_POST, 0); // set POST method
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");
		$content = curl_exec($ch); // run the whole process
		$curl_info= curl_getinfo($ch);
		curl_close($ch);
		return json_decode($content,true);
	}

	/*** Get DMOZ listed or not ***/
	function dmoz_check($domain)
	{

		$url="http://www.dmoz.org/search?q={$domain}";
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT,$_SERVER['HTTP_USER_AGENT']);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt ($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		
		/**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
		
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
		
		$content = curl_exec($ch);



		$cat_string_exist   = strpos($content,"Categories");
		$site_string_exist  = strpos($content,"Sites");

		if($cat_string_exist!==FALSE && $site_string_exist!==FALSE){
			return "yes";
		}		
		else{
			return "no";
		}

	}


	function get_content($url)
	{

		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");   
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");  
		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt"); 	
		$content = curl_exec($ch);
		$content=json_decode($content,TRUE);

		return $content;

	}

	/*** 		Social Network      ***/

	/*********Get Facebook Like share Comment Count*********/
	function fb_like_comment_share($url)
	{

		$facebook_config=$this->CI->basic->get_data("facebook_rx_config");
		if(isset($facebook_config[0]))
		{			
			$this->app_id=$facebook_config[0]["app_id"];
			$this->app_secret=$facebook_config[0]["app_secret"];
		
		}
		
		$access_token=$this->app_id.'|'.$this->app_secret;
		$url="https://graph.facebook.com/v3.3/?id={$url}&fields=engagement&access_token={$access_token}";

		$response=$this->get_content($url);


		if (isset($response['engagement']['share_count'])) 
			$get_total_share['total_share'] = $response['engagement']['share_count'];
		else
			$get_total_share['total_share'] = 0;

		if (isset($response['engagement']['reaction_count']))
			$get_total_share['total_reaction'] = $response['engagement']['reaction_count'];
		else
			$get_total_share['total_reaction'] = 0;

		if (isset($response['engagement']['comment_count']))
			$get_total_share['total_comment'] = $response['engagement']['comment_count'];
		else
			$get_total_share['total_comment'] = 0;

		if (isset($response['engagement']['comment_plugin_count']))
			$get_total_share['total_comment_plugin'] = $response['engagement']['comment_plugin_count'];
		else
			$get_total_share['total_comment_plugin'] = 0;

		return $get_total_share;

	}


	/*****Retrun google plus Share (plus one )*******/	
	function get_plusones($url) 
	{

		$url=addHttp($url);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "https://clients6.google.com/rpc");
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POSTFIELDS, '{"method":"pos.plusones.get","id":"p","params":{"nolog":true,"id":"' . $url . '","source":"widget","userId":"@viewer","groupId":"@self"},"jsonrpc":"2.0","key":"p","apiVersion":"v1"}');
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-type: application/json'));
		$curl_results = curl_exec ($ch);
		curl_close ($ch);

		$curl_results = json_decode($curl_results, true);
		return isset($curl_results['result']['metadata']['globalCounts']['count']) ? $curl_results['result']['metadata']['globalCounts']['count']:0;
	}	


	/****  Return Total Pin Count ******/
	function pinterest_pin($url)
	{

		/**
		 *  If there is no slash in the domain end, result is not coming.
		 */
		

		$url=addHttp($url);

		$url=rtrim($url,"/").'/';


		//$url=urlencode($url);

		//$pin_url="https://api.pinterest.com/v1/urls/count.json?url={$url}";
		$pin_url="https://api.pinterest.com/v1/urls/count.json?callback=receiveCount&url={$url}";
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $pin_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		$content = curl_exec($ch);
		$content=str_replace("receiveCount","",$content);
		$content = str_replace(array('(', ')'), '', $content);
		$result=json_decode($content,TRUE);

		 if(isset($result['count'])) return $result['count'];

		 else return 0;	

	}


	/****** Get stumbleupon.com total views, like, comment, list ******/


	function stumbleupon_info($url)
	{

		$url=addHttp($url);
		$url=urlencode($url);
		$stumble_url="http://www.stumbleupon.com/services/1.01/badge.getinfo?url={$url}";
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $stumble_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		$content = curl_exec($ch);
		$content=json_decode($content,TRUE);

		$response=array();
		if(isset($content['result']['views'])){
			$response['total_view']= $content['result']['views'];
			$publicid= $content['result']['publicid'];
		} else $response['total_view'] = 0;

		return $response;

	}


	/**Get LinkdIn Share ***/
	function linkdin_share($url)
	{
		$url=addHttp($url);
		$url=urlencode($url);
		$linkdin_url="http://www.linkedin.com/countserv/count/share?url={$url}&format=json";
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $linkdin_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		$content = curl_exec($ch);
		$content=json_decode($content,TRUE);

		$total_share=isset($content['count'])? $content['count']: 0;
		return $total_share;
	}



	/***Return Buffer share Count**/
	function buffer_share($url)
	{
		$url=addHttp($url);
		$url=urlencode($url);
		$buffer_url="https://api.bufferapp.com/1/links/shares.json?url={$url}";
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $buffer_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		$content = curl_exec($ch);
		$content=json_decode($content,TRUE);

		$total_share=isset($content['shares'])? $content['shares']: 0;
		return $total_share;
	}

	function reddit_count($url)
	{

		$url=addHttp($url);
		$url=urlencode($url);
		$reddit_url="https://www.reddit.com/api/info.json?url={$url}";
		$ch=curl_init();
		curl_setopt($ch, CURLOPT_URL, $reddit_url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_USERAGENT,'Mozilla/5.0 (compatible; Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 30);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		$content = curl_exec($ch);
		$content=json_decode($content,TRUE);

		/***Take Scroe, Up, Downs from first subreddit*****/
		$response=array();
		$response['score']=  isset($content['data']['children'][0]['data']['score'])? $content['data']['children'][0]['data']['score']:0;
		$response['downs']=  isset($content['data']['children'][0]['data']['downs'])?$content['data']['children'][0]['data']['downs']:0;
		$response['ups']=  isset($content['data']['children'][0]['data']['ups'])?$content['data']['children'][0]['data']['ups']:0;

		return $response;

	}

	function xing_share_count($url)
	{
		$url=addHttp($url);
		$url=urlencode($url);

		$xing_url="http://www.xing-share.com/app/share?op=get_share_button;counter=top;url={$url}";
		$xing_content=$this->get_general_content($xing_url);
		$html = new simple_html_dom();
		$html->load($xing_content);
		
		
		/** Get the statistics of mark tag, it is in order (Likes, Comments, Lists) **/
		$share_info = $html->find('span.xing-count');
		foreach($share_info as $info) {
			$statistics=$info->plaintext;
		}
		if(isset($statistics))
			return $statistics;
		else 
			return 0;
	}


	/********************************End of Social Network Information************************************************/

	
	function GoogleBL_old($url,$proxy=""){
	
		$google_url= "www.google.com/search?q=link:{$url}";
		
		$ch = curl_init(); // initialize curl handle
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
		curl_setopt($ch, CURLOPT_AUTOREFERER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,7);
		curl_setopt($ch, CURLOPT_REFERER, 'http://'.$google_url);
		curl_setopt($ch, CURLOPT_URL,$google_url); // set url to post to
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
		curl_setopt($ch, CURLOPT_POST, 0); // set POST method
	
		/***** Proxy set for google . if lot of request gone, google will stop reponding. That's why it's should use some proxy *****/
		/**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!=''){
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
		}
			
			
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");  
		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");	
		$content = curl_exec($ch); // run the whole process
		
		preg_match('#<font size="-1">About.*?([\d,]+).*?</font>#si', $content, $google_index); 
		
		if(!isset($google_index[1])){
			 preg_match('#<font size="-1">([\d,]+).*?</font>#si', $content, $google_index); 
		}
		
		
		return isset($google_index[1]) ? $google_index[1] : 0;
		
	}
	
	function GoogleBL($url,$proxy=""){
			
		$use_admin_app = $this->CI->config->item('use_admin_app');
		if($use_admin_app == '' || $use_admin_app == 'no')
		  $config_data = $this->CI->basic->get_data('config',['where'=>['user_id'=>$this->user_id]]);
		else
		  $config_data = $this->CI->basic->get_data('config',['where'=>['access'=>'all_users']],'','',1,0);

		$access_id="";
		$secret_key="";
		if(count($config_data)>0)
		{
		    $access_id=$config_data[0]["moz_access_id"];
		    $secret_key=$config_data[0]["moz_secret_key"];
		}

		$moz_info= $this->get_moz_info($url,$access_id, $secret_key); 
		
		$backlink_count=$moz_info['external_equity_links'];
		if($backlink_count=="")
			$backlink_count=0;
		return number_format($backlink_count);
		
	}
	
	
	
	function GoogleIP($url,$proxy=""){
	
		$google_url= "www.google.com/search?q=site:{$url}&hl=en";
		$ch = curl_init(); // initialize curl handle
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		// curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
 		//curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
 		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:64.0) Gecko/20100101 Firefox/64.0');
		curl_setopt($ch, CURLOPT_AUTOREFERER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,7);
		curl_setopt($ch, CURLOPT_REFERER, 'http://'.$google_url);
		curl_setopt($ch, CURLOPT_URL,$google_url); // set url to post to
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
		curl_setopt($ch, CURLOPT_POST, 0); // set POST method
	
		/***** Proxy set for google . if lot of request gone, google will stop reponding. That's why it's should use some proxy *****/
		/**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
			
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");  
		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");	

		$content = curl_exec($ch); // run the whole process
		// preg_match('#<font size="-1">About.*?([\d,]+).*?</font>#si', $content, $google_index); 

		preg_match('#<div.*?id="result-stats".*?>.*?([\d,]+).*?results#si', $content, $google_index);    

		if(!isset($google_index[1])){  
		    preg_match('#<div.*?id="result-stats".*?>(.*?)results#si', $content, $google_index); 
		  }
		
		return isset($google_index[1]) ? $google_index[1] : 0;
		
	}
	
	
	
	


	/**Get Bing Index Count ***/
	function bing_index($url,$proxy="")
	{

		$bing_url= "http://www.bing.com/search?q=site:{$url}";
		$ch = curl_init(); // initialize curl handle
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		 curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:64.0) Gecko/20100101 Firefox/64.0');
		curl_setopt($ch, CURLOPT_AUTOREFERER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,7);
		curl_setopt($ch, CURLOPT_REFERER, 'http://'.$bing_url);
		curl_setopt($ch, CURLOPT_URL,$bing_url); // set url to post to
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
		curl_setopt($ch, CURLOPT_POST, 0); // set POST method

		/***** Proxy set for google . if lot of request gone, google will stop reponding. That's why it's should use some proxy *****/
		/**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
			
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);

		$cookie_file=FCPATH."cookie/{$this->session_id}.txt";	
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
	    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
		
			 
		$content = curl_exec($ch); // run the whole process
		preg_match('#<span.*?class="sb_count"[^>]*>(.*?)<\/span[^>]*>#i', $content, $bing_index);
		return isset($bing_index[1]) ? str_replace("results","",$bing_index[1]) : 0;

	}


	/*Get Yahoo Index Count*/
	function yahoo_index($url,$proxy="")
	{

		$yahoo_url= "http://search.yahoo.com/bin/search?p=site:{$url}";
		$ch = curl_init(); // initialize curl handle
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
		curl_setopt($ch, CURLOPT_AUTOREFERER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,7);
		curl_setopt($ch, CURLOPT_REFERER, 'http://'.$yahoo_url);
		curl_setopt($ch, CURLOPT_URL,$yahoo_url); // set url to post to
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
		curl_setopt($ch, CURLOPT_POST, 0); // set POST method

		/***** Proxy set for google . if lot of request gone, google will stop reponding. That's why it's should use some proxy *****/
		/**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
			
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);

		$cookie_file=FCPATH."cookie/{$this->session_id}.txt";	
	    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
	    curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file);
	    curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_file);
			 
		$content = curl_exec($ch); // run the whole process

		preg_match('#<span[^>]*>[^<]*results<\/span[^>]*>#i', $content, $yahoo_index);
		return isset($yahoo_index[0]) ? strip_tags(str_replace("results","",$yahoo_index[0])) : 0;
	}
	
	
	
	
	public function yahoo_backlink($url,$proxy=""){
		
		$url=$this->clean_domain_name($url);
		
		/*** Http for Bing **/
		$url=addHttp($url);
		
		$yahoo_url= "http://search.yahoo.com/bin/search?p=link:{$url}";
		$ch = curl_init(); // initialize curl handle
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
		curl_setopt($ch, CURLOPT_AUTOREFERER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,7);
		curl_setopt($ch, CURLOPT_REFERER, 'http://'.$yahoo_url);
		curl_setopt($ch, CURLOPT_URL,$yahoo_url); // set url to post to
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
		curl_setopt($ch, CURLOPT_POST, 0); // set POST method
	
		/***** Proxy set for google . if lot of request gone, google will stop reponding. That's why it's should use some proxy *****/
		/**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
			
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies1.txt");  
		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies1.txt");	 
		$content = curl_exec($ch); // run the whole process
	
		preg_match('#<span[^>]*>[^<]*results<\/span[^>]*>#i', $content, $yahoo_index);
		return isset($yahoo_index[0]) ? strip_tags(str_replace("results","",$yahoo_index[0])) : 0;
	
	
	}
	
	
	function bing_backlink($url,$proxy="")
	{
		
		$url=$this->clean_domain_name($url);
		/**** No Http for bing *****/
		
		
		$bing_url= "http://www.bing.com/search?q=link:{$url}";
		$ch = curl_init(); // initialize curl handle
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
		curl_setopt($ch, CURLOPT_AUTOREFERER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,7);
		curl_setopt($ch, CURLOPT_REFERER, 'http://'.$bing_url);
		curl_setopt($ch, CURLOPT_URL,$bing_url); // set url to post to
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
		curl_setopt($ch, CURLOPT_POST, 0); // set POST method
	
		/***** Proxy set for google . if lot of request gone, google will stop reponding. That's why it's should use some proxy *****/
		/**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
			
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
	
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies1.txt");  
		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies1.txt");	 
		$content = curl_exec($ch); // run the whole process
		preg_match('#<span.*?class="sb_count"[^>]*>(.*?)<\/span[^>]*>#i', $content, $bing_index);
		return isset($bing_index[1]) ? str_replace("results","",$bing_index[1]) : 0;

	}


	


	/****Get Google Page Rank ******/

	function get_google_page_rank($url) 
	{

		$googlehost= $this->googlehost;
		$googleua= $this->googleua;

		$ch = $this->getch($url);
		$fp = fsockopen($googlehost, 80, $errno, $errstr, 30);
		if ($fp) {
			$out = "GET /tbr?client=navclient-auto&ch=$ch&features=Rank&q=info:$url HTTP/1.1\r\n";
			$out .= "User-Agent: $googleua\r\n";
			$out .= "Host: $googlehost\r\n";
			$out .= "Connection: Close\r\n\r\n";

			fwrite($fp, $out);

			//$pagerank = substr(fgets($fp, 128), 4); //debug only
			while (!feof($fp)) {
				$data = fgets($fp, 128);
				$pos = strpos($data, "Rank_");
				if($pos === false){} else{
					$pr=substr($data, $pos + 9);
					$pr=trim($pr);
					$pr=str_replace("\n",'',$pr);
					return $pr;
				}
			}
			fclose($fp);
		}
	}


	function StrToNum($Str, $Check, $Magic) 
	{
		$Int32Unit = 4294967296;  // 2^32
		$length = strlen($Str);
		for ($i = 0; $i < $length; $i++) {
			$Check *= $Magic;   
		//If the float is beyond the boundaries of integer (usually +/- 2.15e+9 = 2^31), 
		//  the result of converting to integer is undefined
		//  refer to http://www.php.net/manual/en/language.types.integer.php
			if ($Check >= $Int32Unit) {
				$Check = ($Check - $Int32Unit * (int) ($Check / $Int32Unit));
		//if the check less than -2^31
				$Check = ($Check < -2147483648) ? ($Check + $Int32Unit) : $Check;
			}
			$Check += ord($Str[$i]); 
		}
		return $Check;
	}

	//genearate a hash for a url
	function HashURL($String) 
	{
		$Check1 = $this->StrToNum($String, 0x1505, 0x21);
		$Check2 = $this->StrToNum($String, 0, 0x1003F);

		$Check1 >>= 2;    
		$Check1 = (($Check1 >> 4) & 0x3FFFFC0 ) | ($Check1 & 0x3F);
		$Check1 = (($Check1 >> 4) & 0x3FFC00 ) | ($Check1 & 0x3FF);
		$Check1 = (($Check1 >> 4) & 0x3C000 ) | ($Check1 & 0x3FFF);   

		$T1 = (((($Check1 & 0x3C0) << 4) | ($Check1 & 0x3C)) <<2 ) | ($Check2 & 0xF0F );
		$T2 = (((($Check1 & 0xFFFFC000) << 4) | ($Check1 & 0x3C00)) << 0xA) | ($Check2 & 0xF0F0000 );

		return ($T1 | $T2);
	}



	//genearate a checksum for the hash string
	function CheckHash($Hashnum) 
	{
		$CheckByte = 0;
		$Flag = 0;

		$HashStr = sprintf('%u', $Hashnum) ;
		$length = strlen($HashStr);

		for ($i = $length - 1;  $i >= 0;  $i --) {
			$Re = $HashStr[$i];
			if (1 === ($Flag % 2)) {              
				$Re += $Re;     
				$Re = (int)($Re / 10) + ($Re % 10);
			}
			$CheckByte += $Re;
			$Flag ++;   
		}

		$CheckByte %= 10;
		if (0 !== $CheckByte) {
			$CheckByte = 10 - $CheckByte;
			if (1 === ($Flag % 2) ) {
				if (1 === ($CheckByte % 2)) {
					$CheckByte += 9;
				}
				$CheckByte >>= 1;
			}
		}

		return '7'.$CheckByte.$HashStr;
	}



	function getch($url) 
	{ 
		return $this->CheckHash($this->HashURL($url)); 
	}


	/**Get Meta Tags ****/
	function extract_meta_tags($domain_name)
	{
		$tags = get_meta_tags($domain_name);
		return $tags;
	}


	function get_meta_tag($html)
	{
		$doc = new DOMDocument();
		@$doc->loadHTML('<meta http-equiv="content-type" content="text/html; charset=utf-8">'.$html);
		$nodes = $doc->getElementsByTagName('title');
		
		if(isset($nodes->item(0)->nodeValue))
			$title = $nodes->item(0)->nodeValue;
		else
			$title="";
		
		$response=array();
		$response['title']=$title;

		$metas = $doc->getElementsByTagName('meta');

		for ($i = 0; $i < $metas->length; $i++)
		{
			$meta = $metas->item($i);
			if($meta->getAttribute('name')!='')
				$response[$meta->getAttribute('name')] = $meta->getAttribute('content');
		}

		return $response;
	}


	public function whois_info($domain='')
    {
        $tech_email="";
        $admin_email="";
        $name_server_str="";
        $created_at="";
        $sponsor="";
        $expire_at="";
        $changed_at="";
		
		$registrant_name="";
		$registrant_organization="";
		$registrant_street="";
		$registrant_city="";
		$registrant_state="";
		$registrant_postal_code="";
		$registrant_country="";
		$registrant_phone="";
		$registrar_url="";
		$registrant_email="";
		
		
		$admin_name="";
		$admin_street="";
		$admin_city="";
		$admin_state="";
		$admin_postal_code="";
		$admin_country="";
		$admin_phone="";

        $domain=trim($domain);
		$domain=str_replace("www.","",$domain);
		$domain=str_replace("http://","",$domain);
		$domain=str_replace("https://","",$domain);
		$domain=str_replace("/","",$domain);
		$domain=strtolower($domain);
		
		
  
        $whois = new Whois();
        $query = $domain;
        $whois->deep_whois=true;
        $result = $whois->Lookup($query, false);
        $rawdata = $result['rawdata'];
        $regrinfo = $result['regrinfo'];
        $is_registered=trim($regrinfo['registered']);

        
        if ($is_registered=='yes' || isset($regrinfo['domain']['nserver'])) {
		
			$is_registered="yes";
			
            $name_servers=$regrinfo['domain']['nserver'];
            foreach ($name_servers as $n_server=>$ip) {
                $name_server_str.=$n_server.", ";
            }
        
            $name_server_str=trim($name_server_str);
            $name_server_str=trim($name_server_str, ",");
        
            if (isset($regrinfo['domain']['created'])) {
                $created_at=$regrinfo['domain']['created'];
            }
        
            if (isset($regrinfo['domain']['sponsor'])) {
                $sponsor= $regrinfo['domain']['sponsor'];
            }
        
            if (isset($regrinfo['domain']['changed'])) {
                $changed_at= $regrinfo['domain']['changed'];
            }
            
            if (isset($regrinfo['domain']['expires'])) {
                $expire_at=$regrinfo['domain']['expires'];
            }
        
        
                
            foreach ($rawdata as $info) {
                /**Get technical email**/
            $pos=strpos($info, "Tech Email: ");
                if ($pos!==false) {
                    $tech_email= trim(str_replace("Tech Email: ", "", $info));
                }
            
            /**get admin email**/
            $pos=strpos($info, "Admin Email: ");
                if ($pos!==false) {
                    $admin_email=trim(str_replace("Admin Email: ", "", $info));
                }
				
			
			
			
			$pos=strpos($info, "Registrar URL: ");
                if ($pos!==false) {
                    $registrar_url=trim(str_replace("Registrar URL: ", "", $info));
                }
			
			
			/***  Get registrant Address	***/
			
			$pos=strpos($info, "Registrant Name: ");
                if ($pos!==false) {
                    $registrant_name=trim(str_replace("Registrant Name: ", "", $info));
                }
			
			$pos=strpos($info, "Registrant Organization: ");
                if ($pos!==false) {
                    $registrant_organization=trim(str_replace("Registrant Organization: ", "", $info));
                }
				
			$pos=strpos($info, "Registrant Street: ");
                if ($pos!==false) {
                    $registrant_street=trim(str_replace("Registrant Street: ", "", $info));
                }
				
			
			$pos=strpos($info, "Registrant City: ");
                if ($pos!==false) {
                    $registrant_city=trim(str_replace("Registrant City: ", "", $info));
                }
				
				
			$pos=strpos($info, "Registrant State/Province: ");
                if ($pos!==false) {
                    $registrant_state=trim(str_replace("Registrant State/Province: ", "", $info));
                }
				
				
			$pos=strpos($info, "Registrant Postal Code: ");
                if ($pos!==false) {
                    $registrant_postal_code=trim(str_replace("Registrant Postal Code: ", "", $info));
                }
				
			$pos=strpos($info, "Registrant Country: ");
                if ($pos!==false) {
                    $registrant_country=trim(str_replace("Registrant Country: ", "", $info));
                }
				
			$pos=strpos($info, "Registrant Phone: ");
                if ($pos!==false) {
                    $registrant_phone=trim(str_replace("Registrant Phone: ", "", $info));
                }
			
			$pos=strpos($info, "Registrant Email: ");
                if ($pos!==false) {
                    $registrant_email=trim(str_replace("Registrant Email: ", "", $info));
                }
					
			
				
				/***	Get Admin Address	***/
			
			$pos=strpos($info, "Admin Name: ");
                if ($pos!==false) {
                    $admin_name=trim(str_replace("Admin Name: ", "", $info));
                }
			
			
				
			$pos=strpos($info, "Admin Street: ");
                if ($pos!==false) {
                    $admin_street=trim(str_replace("Admin Street: ", "", $info));
                }
				
			
			$pos=strpos($info, "Admin City: ");
                if ($pos!==false) {
                    $admin_city=trim(str_replace("Admin City: ", "", $info));
                }
				
				
			$pos=strpos($info, "Admin State/Province: ");
                if ($pos!==false) {
                    $admin_state=trim(str_replace("Admin State/Province: ", "", $info));
                }
				
				
			$pos=strpos($info, "Admin Postal Code: ");
                if ($pos!==false) {
                    $admin_postal_code=trim(str_replace("Admin Postal Code: ", "", $info));
                }
				
			$pos=strpos($info, "Admin Country: ");
                if ($pos!==false) {
                    $admin_country=trim(str_replace("Admin Country: ", "", $info));
                }
				
			$pos=strpos($info, "Admin Phone: ");
                if ($pos!==false) {
                    $admin_phone=trim(str_replace("Admin Phone: ", "", $info));
                }
				
				
            
                if ($tech_email!='' && $admin_email!='') {
                    break;
                }	
            }
			
        }
    
        $response['is_registered']=$is_registered;
        $response['tech_email']=$tech_email;
        $response['admin_email']=$admin_email;
        
        $response['name_servers']=$name_server_str;
        $response['created_at']=$created_at;
		
		if(is_array($sponsor)){
			$sponsor=implode(",",$sponsor);
		}
		
        $response['sponsor']=$sponsor;
        $response['changed_at']=$changed_at;
        $response['expire_at']=$expire_at;
		$response['rawdata'] = json_encode($rawdata);
		$response['registrar_url'] = $registrar_url;
		
		$response['registrant_name'] = $registrant_name;
		$response['registrant_organization'] = $registrant_organization;
		$response['registrant_street'] = $registrant_street;
		$response['registrant_city'] = $registrant_city;
		$response['registrant_state'] = $registrant_state;
		$response['registrant_postal_code'] = $registrant_postal_code;
		$response['registrant_email'] = $registrant_email;
		$response['registrant_country'] = $registrant_country;
		$response['registrant_phone'] = $registrant_phone;
		$response['registrant_email'] = $registrant_email;
		
		
		$response['admin_name'] = $admin_name;
		$response['admin_street'] = $admin_street;
		$response['admin_city'] = $admin_city;
		$response['admin_state'] = $admin_state;
		$response['admin_postal_code'] = $admin_postal_code;
		$response['admin_country'] = $admin_country;
		$response['admin_phone'] = $admin_phone;
		
		
        return $response;
    }
      


	function get_moz_info($url,$access_id="", $secret_key="")
	{
		if($access_id=="" || $secret_key=="")
		{
			$response=array();
			$response['mozrank_subdomain_normalized'] = "";
			$response['mozrank_subdomain_raw'] = "";
			$response['mozrank_url_normalized'] = "";
			$response['mozrank_url_raw'] = "";
			$response['http_status_code'] = "";
			$response['domain_authority'] = "";
			$response['page_authority'] = "";
			$response['external_equity_links'] = "";
			$response['links'] = "";

			return $response;
		} 		

		$accessID = $access_id;
		$secretKey = $secret_key;
		$expires = time() + 300;
		$stringToSign = $accessID."\n".$expires;
		$binarySignature = hash_hmac('sha1', $stringToSign, $secretKey, true);
		$urlSafeSignature = urlencode(base64_encode($binarySignature));
		$objectURL = $url;
		$cols = "103616137248";

		$requestUrl = "http://lsapi.seomoz.com/linkscape/url-metrics/".urlencode($objectURL)."?Cols=".$cols."&AccessID=".$accessID."&Expires=".$expires."&Signature=".$urlSafeSignature;

		$options = array(
			CURLOPT_RETURNTRANSFER => true
			);
		$ch = curl_init($requestUrl);
		curl_setopt_array($ch, $options);
		$content = curl_exec($ch);
		curl_close($ch);

		$content=json_decode($content,TRUE);

		$response=array();
		$response['mozrank_subdomain_normalized'] = isset($content['fmrp'])?$content['fmrp']: "0";
		$response['mozrank_subdomain_raw'] = isset($content['fmrr'])?$content['fmrr']: "0";
		$response['mozrank_url_normalized'] = isset($content['umrp'])?$content['umrp']: "0";
		$response['mozrank_url_raw'] = isset($content['umrr'])?$content['umrr']: "0";
		$response['http_status_code'] = isset($content['us'])?$content['us']: "0";
		$response['domain_authority'] = isset($content['pda'])?$content['pda']: "0";
		$response['page_authority'] = isset($content['upa'])?$content['upa']: "0";
		$response['external_equity_links'] = isset($content['ueid'])?$content['ueid']: "0";
		$response['links'] = isset($content['uid'])?$content['uid']: "0";
		return $response;
	}



	/************************************				Malware Checking 	*******************************************/

	function google_safety_check($api="",$url){

		if($api=="") return "undefined API";

		$url=urlencode($url);

		  $data = array(
	  		    "threatInfo" => array(
	  		      "threatTypes" => ["MALWARE"],
	  			  "platformTypes" => ["WINDOWS"],
	  		      "threatEntryTypes" => ["URL"],
	  		      "threatEntries" => array(
	  		        "url"=> $url
	  		      )
	  		    )
		  );

		$url2 ="https://safebrowsing.googleapis.com/v4/threatMatches:find?key={$api}";
		$ch = curl_init();
		$options = array(
		    CURLOPT_HTTPHEADER     => array("Content-Type:application/json"),
		    CURLOPT_POST           => 1,
		    CURLOPT_POSTFIELDS     => json_encode($data),
		    CURLOPT_URL            => $url2,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_HEADER         => true,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_SSL_VERIFYHOST => false
		);
		curl_setopt_array( $ch, $options );
		$result = curl_exec($ch);
		$info=curl_getinfo($ch);
        $httpcode = $info['http_code'];
        if ($httpcode == '200')
        	return 'Safe';
        else if($httpcode == '204')
        	return 'Malware';
        else
        	return 'Failed';
     
	}

	function google_page_speed_insight($api="",$domain="",$strategy="desktop")
	{

		//$url="https://www.googleapis.com/pagespeedonline/v3beta1/runPagespeed?key=".$api."&url=".$domain."&strategy=".$strategy."&screenshot=true";
		$url="https://www.googleapis.com/pagespeedonline/v5/runPagespeed?key=".$api."&url=".$domain."&strategy=".$strategy."&screenshot=true";

		$ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch); // run the whole process
		$content= json_decode($content,TRUE);
		curl_close($ch);

		return $content;
	}


	function norton_safety_check($url,$proxy="")
	{

		$norton_link = "https://safeweb.norton.com/report/show?url={$url}";
		$ch = curl_init(); // initialize curl handle

		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		curl_setopt($ch, CURLOPT_URL,$norton_link); // set url to post to
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
		curl_setopt($ch, CURLOPT_POST, 0); // set POST method

		/***** Proxy set for google . if lot of request gone, google will stop reponding. That's why it's should use some proxy *****/
		/**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
		
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies1.txt");  
		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies1.txt");	 
		$content = curl_exec($ch); // run the whole process

		$diagnose = array(
			'icoSafe' => 'safe',
			'icoUntested' => 'untested',
			'icoWarning' => 'warning',
			'icoCaution' => 'caution',
			'icoNSecured' => 'safe',
			);

		if(!$content)
			return "untested";

		preg_match('#<img(?:[^>]*)class="big_clip (.*?)"(?:[^>]*)>#is', $content, $matches);
		$d = isset($matches[1]) ? trim($matches[1]) : 'untested';
	
		return isset($diagnose[$d]) ? $diagnose[$d] : 'untested';


	}

	function macafee_safety_analysis($url,$proxy="")
	{

		$macafee_link = "http://www.siteadvisor.com/sites/{$url}";
		$ch = curl_init(); // initialize curl handle
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		curl_setopt($ch, CURLOPT_URL,$macafee_link); // set url to post to
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
		curl_setopt($ch, CURLOPT_POST, 0); // set POST method

		/***** Proxy set for google . if lot of request gone, google will stop reponding. That's why it's should use some proxy *****/
		/**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
		
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies1.txt");  
		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies1.txt");	 
		$content = curl_exec($ch); // run the whole process
		$html = new simple_html_dom();
		$html->load($content);

		
		$status = isset($html->find('span.rating',0)->plaintext) ? $html->find('span.rating',0)->plaintext : 'failed';
		return $status;

	
	}


	function avg_safety_check($url,$proxy='')
	{

		$avg_link = "http://www.avgthreatlabs.com/ww-en/website-safety-reports/domain/{$url}";
		$ch = curl_init(); // initialize curl handle
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		curl_setopt($ch, CURLOPT_URL,$avg_link); // set url to post to
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); // times out after 50s
		curl_setopt($ch, CURLOPT_POST, 0); // set POST method

		/***** Proxy set for google . if lot of request gone, google will stop reponding. That's why it's should use some proxy *****/
		/**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
		
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies1.txt");  
		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies1.txt");	 
		$content = curl_exec($ch); // run the whole process

		if(!$content){
			return "untested";
		}

		$diagnose = array(
			'green' => 'safe',
			'yellow' => 'warning',
			'orange' => 'warning',
			'red' => 'caution',
			'gray' => 'untested',
			);

		//preg_match('#<span id="linkscanner_icon" class="linkscanner (.+?)" (.+?)></span>#is', $content, $matches);
		preg_match('#<div class="rating(.*?)">.*?</div>#is', $content, $matches);
		$d = isset($matches[1]) ? trim($matches[1]) : 'untested';
		return isset($diagnose[$d]) ? $diagnose[$d] : 'untested';
	}





	/***** get content from searchengine ******/

	public function getContentFromSearchEngine($url, $proxy='',$is_cookie_on=1)
	{

		$ch = curl_init(); // initialize curl handle
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:64.0) Gecko/20100101 Firefox/64.0');
		curl_setopt($ch, CURLOPT_AUTOREFERER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
		curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
		curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 30); // times out after 50s
		curl_setopt($ch, CURLOPT_POST, 0); // set POST method

		/***** Proxy set for google . if lot of request gone, google will stop reponding. That's why it's should use some proxy *****/
		/**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
		
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
			

		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		if($is_cookie_on==1){
			curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies1.txt");
			curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies1.txt"); 
		}


		$content = curl_exec($ch); // run the whole process	


		/****If it returns http code without 200 means caught by google, or redirect to captcha page*****/

		$get_info = curl_getinfo($ch);
		$httpcode=$get_info['http_code'];

		if($httpcode!='200'){
			return 0;
		}

		curl_close($ch);

		/** Remove html tag line <br> <b> in the email **/
		$content=str_replace("<b>", "", $content);
		$content=str_replace("</b>", "", $content);
		$content=str_replace("</br>", "", $content);
		$content=str_replace("<br>", "", $content);
		$content=str_replace("<br/>", "", $content);

		/*** These are specially for the bing search engine ***/
		$content=str_replace("<strong>", "", $content);
		$content=str_replace("</strong>", "", $content);
		$content=str_replace(",", "", $content);


		return $content;

	}



	/******  	Get domain IP, Country, Location Information 	*******/


	function get_ip_country($domain,$proxy='')
	{

		$domain=str_replace("www.","",$domain);
		$domain=str_replace("http://","",$domain);
		$domain=str_replace("https://","",$domain);
		$domain=str_replace("/","",$domain);
		$domain=strtolower($domain);
		$domain_ip	= gethostbyname($domain);
		$response=$this->ip_information($domain_ip);

		return $response;

	}


	/****Return website address in same ip , Get access with the $this->same_site_in_ip variable, means from controller after execute the function call, then use $this->web_common_report->same_site_in_ip variable *****/

	public function get_site_in_same_ip($ip,$page=1,$proxy="")
	{

		$q="ip:{$ip}";
		$q=urlencode($q);

		if($page>1){
			$page_number=$page-1;
			$start=$page_number*10+1;
			$page_str="&first={$start}";
		} else {
			$page_str="";
		}


		$url="http://www.bing.com/search?q={$q}{$page_str}";

		$content = $this->getContentFromSearchEngine($url,$proxy,$is_cookie_on=0);

		preg_match_all('#<cite>(.*?)</cite>#', $content, $matches);

		foreach($matches[1] as $info){
			$this->same_site_in_ip[]=get_domain_only($info);
		}

		preg_match_all('~<a.*?href="/search.*?">(\d*?)</a>~',$content,$matches);
		$next_page=$page+1; 

		$this->same_site_in_ip=array_unique($this->same_site_in_ip);
		$this->same_site_in_ip=array_values($this->same_site_in_ip);

		if(in_array($next_page,$matches[1]) && $next_page<10){
			$this->get_site_in_same_ip($ip,$next_page,$proxy="");
		}

		else 
			return $this->same_site_in_ip;		
	}


		/*********	Ip information , call ip_information() function where api is called with proper logic   ****************/

	function ip_info($ip)
	{
		$url="ipinfo.io/{$ip}/json";
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");  
		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($ch, CURLOPT_TIMEOUT, 20); // times out after 20s
		$st=curl_exec($ch);  
		$result=json_decode($st,TRUE);
		$get_info=curl_getinfo($ch) ;
		$httpcode=$get_info['http_code'];

		$response=array();

		if($httpcode=='200' && isset($result['country'])){
		$response['status']="success";
		
		
		$response['city']= isset($result['city'])?$result['city']:"";
		
		$country_code =isset($result['country'])?strtoupper($result['country']):"";
		if($country_code)
			$response['country']=$this->country_list[$country_code];
		else
			$response['country']="";
		
		$response['postal']=isset($result['postal'])?$result['postal']:"";
		$response['org']=isset($result['org'])?$result['org']:"";
		$response['hostname']=isset($result['hostname'])?$result['hostname']:"";
		$response['region']=isset($result['region'])?$result['region']:"";

		$response['organization']=isset($result['org'])?$result['org']:"";
		$response['time_zone']=isset($result['timezone'])?$result['timezone']:"";
		$response['isp']=isset($result['org'])?$result['org']:"";
		$response['ip']=isset($result['ip'])?$result['ip']:"";



		$location=isset($result['loc'])?$result['loc']:"";
		$location=explode(",",$location);
		$response['latitude']=isset($location[0]) ? $location[0]:"";
		$response['longitude']=isset($location[1]) ? $location[1]:"";

		}	

		else{
			$response['status']="error";
		} 

		return $response; 
	}



	// This site is deprecated & no more work. 

	function free_geo_ip($ip)
	{

		$url="freegeoip.net/json/{$ip}";
		$ch = curl_init();  
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);  
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);  
		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");  
		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
		curl_setopt($ch, CURLOPT_TIMEOUT, 20); // times out after 20s
		$st=curl_exec($ch);  
		$result=json_decode($st,TRUE);

		$get_info=curl_getinfo($ch) ;
		$httpcode=$get_info['http_code'];

		$response=array();

		if($httpcode=='200' && isset($result['country_code']))
		{
			$response['status']="success";
			$response['city']=$result['city'];
			$county_code = strtoupper($result['country_code']);
			$response['country']=$this->country_list[$county_code];
			$response['postal']=$result['zip_code'];
			$response['latitude']=$result['latitude'];
			$response['longitude']=$result['longitude'];
		}	

		else{
			$response['status']="error";
		} 

		return $response; 

	}

	function get_ip_from_location_finder($ip,$proxy='')
	{

		$ip_link = "http://www.iplocationfinder.com/{$ip}";
		$ch = curl_init(); // initialize curl handle
		curl_setopt($ch, CURLOPT_URL,$ip_link); // set url to post to
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, false );

		/**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
		
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
			
		
		$content = curl_exec($ch); // run the whole process


		$get_info=curl_getinfo($ch) ;
		$httpcode=$get_info['http_code'];

		$response=array();

		curl_close($ch);

		$response=array();		

		preg_match('#<th>ISP:</th><td>(.*?)</td>#', $content, $matches);
		$response['isp']=isset($matches[1])?$matches[1] : '';

		preg_match('#<th>IP:</th><td>(.*?)</td>#', $content, $matches);
		$response['ip']=isset($matches[1])?$matches[1] : '';

		preg_match('#<th>Organization:</th><td>(.*?)</td>#', $content, $matches);
		$response['organization']=isset($matches[1])?$matches[1] : '';		

		preg_match('#<th>City:</th><td>(.*?)</td>#', $content, $matches);
		$response['city']=isset($matches[1])?$matches[1] : '';

		preg_match('#<th>Region:</th><td>(.*?)</td>#', $content, $matches);
		$response['region']=isset($matches[1])?$matches[1] : '';

		preg_match('#<th>Country:</th><td>(.*?)</td>#', $content, $matches);
		$country=isset($matches[1])?$matches[1] : '';
		$response['country'] = preg_replace("#<img.*?>#", "", $country); 

		preg_match('#<th>Timezone:</th><td>(.*?)</td>#', $content, $matches);
		$response['time_zone']=isset($matches[1])?$matches[1] : '';

		preg_match('#<th>Longitude:</th><td>(.*?)</td>#', $content, $matches);
		$response['longitude']=isset($matches[1])?$matches[1] : '';

		preg_match('#<th>Latitude:</th><td>(.*?)</td>#', $content, $matches);
		$response['latitude']=isset($matches[1])?$matches[1] : '';


		if($httpcode=='200' && isset($result['country'])){
			$response['status']="success";
		}
		else{
			$response['status']="error";
		}



		return $response;

	}






	function ip_information($ip)
	{
		$ip_information=$this->ip_info($ip);

		if($ip_information['status']=='error'){
			$ip_information=$this->get_ip_from_location_finder($ip);
		}
		
		return $ip_information;	
	}



	public function similar_site_from_google($domain)
	{
	
			$domain=$this->clean_domain_name($domain);
			
			$url="www.google.com/search?q=related:{$domain}&num=20";
			$content=$this->getContentFromSearchEngine($url);	
			$similar_site=array();
			
			if($content){
				preg_match_all('~<a.*?href="/url\?q=(.*?)">~', $content, $matches);	
				foreach($matches[1] as $info){	
					$similar_site[] = trim(get_domain_only($info));
			  	}
			}
			
			return $similar_site;	
	}





	public function keyword_position_google($keyword, $page_number=0, $proxy='',$country="",$language="",$site="")
	{

		$keyword=$keyword;

		if ($page_number) {
			$page_str="&start={$page_number}";
		} else {
			$page_str="";
		}

		$localization_string="";

		if($country!=""){
			$localization_string.="&cr=country{$country}";
		}

		if($language!=""){
			$localization_string.="&lr=lang_{$language}";
		}

		$url="www.google.com/search?q={$keyword}&num=100{$page_str}{$localization_string}";	
		$content=$this->getContentFromSearchEngine($url, $proxy);			

		$response=array();

		if($content){

			//preg_match_all('~<a.*?href="/url\?q=(.*?)">~', $content, $matches);	

			preg_match_all('#<cite.*?>(.*?)<span.*?</cite>#', $content, $matches);

			$check_domain=get_domain_only($site);

			$search_link_domain=array();
			$search_link=array();
			foreach($matches[1] as $info){	
				$search_link_domain[] = get_domain_only($info);
				$search_link[]= $info;
			}

			$position=array_search($check_domain,$search_link_domain);

			if($position!==FALSE){
				$position=$position+1;
			}

			else
				$position="Not Found";

			$response['status']=$position;
			$response['top_site']['domain']=$search_link_domain;
			$response['top_site']['link']=$search_link;
			return $response;
		}

		else{
			$response['status']="caught_0_dolphin";
			$response['top_site']['domain']=array();
			$response['top_site']['link']=array();
			return $response;
		}

	}





	public function keyword_position_bing($keyword, $page_number=0, $proxy='',$country="",$language="",$site="")
	{

		$keyword=urlencode($keyword);

		if ($page_number) {
			$start=$page_number*10+1;
			$page_str="&first={$start}";
		} else {
			$page_str="";
		}

		$localization_string="";

		if($country!=""){
			$country=strtolower($country);
			$localization_string.="&cc={$country}";
		}

		$url="https://www.bing.com/search?q={$keyword}&count=100&ie=utf-8&oe=utf-8{$page_str}{$localization_string}";
		$content=$this->getContentFromSearchEngine($url, $proxy);

		$response=array();

		if($content){

			preg_match_all('#<cite>(.*?)</cite>#', $content, $matches);

			$check_domain=get_domain_only($site);

			$search_link_domain=array();
			$search_link=array();
			foreach($matches[1] as $info){	
				$search_link_domain[] = get_domain_only($info);
				$search_link[]= $info;
			}

			$position=array_search($check_domain,$search_link_domain);

			if($position!==FALSE){
				$position=$position+1;
			}

			else
				$position="Not Found";

			$response['status']=$position;
			$response['top_site']['domain']=$search_link_domain;
			$response['top_site']['link']=$search_link;

			return $response;
		}

		else{
			$response['status']="caught_0_dolphin";			
			$response['top_site']['domain']=array();
			$response['top_site']['link']=array();
			return $response;
		}

	}


	public function keyword_position_yahoo($keyword, $page_number=0, $proxy='',$country="",$language="",$site="")
	{

		$keyword=urlencode($keyword);

		if ($page_number) {
			$start=$page_number*10+1;
			$page_str="&first={$start}";
		} else {
			$page_str="";
		}

		$localization_string="";

		if($country!=""){
			$country=strtolower($country);
			$localization_string.="&vc={$country}";
		}

		$url="http://search.yahoo.com/bin/search?p={$keyword}&n=100{$localization_string}";
		$content=$this->getContentFromSearchEngine($url, $proxy);


		if($content){

			$check_domain=get_domain_only($site);
			//reg_match_all('#<div class="compTitle.*?">.*?<h3 class="title">.*?<a.*?href="http://ri.search.yahoo.com.*?RU=(.*?)/RK#', $content, $matches);	
			preg_match_all('#<div class="compTitle.*?">.*?<h3 class="title">.*?<a.*?href="https://r.search.yahoo.com.*?RU=(.*?)/RK#', $content, $matches);
			

			$search_link_domain=array();
			$search_link=array();
			foreach($matches[1] as $info){	
				$search_link_domain[] = get_domain_only(urldecode($info));
				$search_link[]= urldecode($info);
			}


			$position=array_search($check_domain,$search_link_domain);

			if($position!==FALSE){
				$position=$position+1;
			}

			else
				$position="Not Found";

			$response['status']=$position;
			$response['top_site']['domain']=$search_link_domain;
			$response['top_site']['link']=$search_link;

			return $response;
		}

		else{
			$response['status']="caught_0_dolphin";
			$response['top_site']['domain']=array();
			$response['top_site']['link']=array();
			return $response;
		}

	}






	function ping_url($blog_name,$blog_url,$updated_url_ping,$blog_rss_feed_url,$link_to_ping)
	{

		$response=array();

		$client = new IXR_Client( $link_to_ping );
		$client->timeout = 3;
		$client->useragent .= ' -- PingTool/1.0.0';
		$client->debug = false;

		if( $client->query( 'weblogUpdates.extendedPing', $blog_name, $blog_url, $updated_url_ping, $blog_rss_feed_url) )
		{
			return $client->getResponse();
		}

		if( $client->query( 'weblogUpdates.ping', $blog_name, $blog_url ) )
		{
			return $client->getResponse();
		}

		return FALSE;

	}
	
	
	public function make_backlink($link){
	
			$ch = curl_init(); // initialize curl handle
            curl_setopt($ch, CURLOPT_URL, $link); // set url to post to
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, 10); // times out after 50s      
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
			curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.3) Gecko/20070309 Firefox/2.0.0.3");  
            curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
            curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt"); 
            curl_exec($ch); // run the whole process	
			$info= curl_getinfo($ch);
			curl_close($ch);
			return $info['http_code'];
				
	}
	
	/******Get Alexa Raw Data from their site*******/
	
	function alexa_raw_data($domain,$proxy=""){

	  	$alexa_url= "http://www.alexa.com/siteinfo/{$domain}";
		$ch = curl_init(); // initialize curl handle
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/44.0 (compatible;)");
		curl_setopt($ch, CURLOPT_AUTOREFERER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,7);
		curl_setopt($ch, CURLOPT_REFERER, 'http://'.$alexa_url);
		curl_setopt($ch, CURLOPT_URL,$alexa_url); // set url to post to
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
		curl_setopt($ch, CURLOPT_POST, 0); // set POST method
		
		/***** Proxy set for google . if lot of request gone, google will stop reponding. That's why it's should use some proxy *****/
		/**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
		
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
		
			
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies1.txt");  
		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies1.txt");	 
	    $content = curl_exec($ch); // run the whole process
		
		$connect_info	= curl_getinfo($ch);
		$http_code=$connect_info['http_code'];
		curl_close($ch);

		$response=array();

		$html = new simple_html_dom();
		$html->load($content);


	
		
		if($http_code=='200'){
			
			$response['status']="success";
			/*
				=====================Over View ==================
			 */
			 /**
				KEYWORD OPPORTUNITIES BREAKDOWN
			 */

			
			$total_keyword_opportunities_breakdown = isset($html->find('div#card_mini_kwopps div#kototalnum',0)->plaintext) ? $html->find('div#card_mini_kwopps div#kototalnum',0)->plaintext : $this->CI->lang->line("No data");
			 $response['total_keyword_opportunities_breakdown'] = $total_keyword_opportunities_breakdown;


			$keyword_opportunitites_values = array();
			$keyword_opportunitites_html = $html->find('div#card_mini_kwopps div#koContainer section.table div.Body div.Row div.value span.truncation');
			$i= 0;
			foreach ($keyword_opportunitites_html as $key => $value) {
				$keyword_opportunitites_values[$i] = trim($value->plaintext);
				$i++;
			}
			$response['keyword_opportunitites_values'] = $keyword_opportunitites_values;
			



			 /**
			  * COMPARISON METRICS
			  * Search Traffic & Bounce Rate
			  */

			$site_search_traffic = isset($html->find('div#card_mini_competitors section.group div.flex div.ThirdFull span.purple', 0)->plaintext) ? $html->find('div#card_mini_competitors section.group div.flex div.ThirdFull span.purple', 0)->plaintext : $this->CI->lang->line("No data");
			$response['site_search_traffic'] = $site_search_traffic;
			$site_bounce_rate = isset($html->find('div#card_mini_competitors section.group div.flex div.ThirdFull span.purple', 1)->plaintext) ? $html->find('div#card_mini_competitors section.group div.flex div.ThirdFull span.purple', 1)->plaintext : $this->CI->lang->line("No data");
			$response["bounce_rate"]=$site_bounce_rate;
			$total_sites_linking_in = isset($html->find('div#card_metrics section.linksin div.enun span.big',0)->plaintext) ? $html->find('div#card_metrics section.linksin div.enun span.big',0)->plaintext : $this->CI->lang->line("No data");
			$response["total_sites_linking_in"]=$total_sites_linking_in;

			 /**
			  * SIMILAR SITES BY AUDIENCE OVERLAP
			  * 
			  */
			 $similar_site = array();
			 $get_similar_html = $html->find('div#card_mini_audience section section.table div.Body div.Row div.site');
			 $i = 0;
			 foreach ($get_similar_html as $key => $value) {
			 	
			 	$similar_site[$i] = trim($value->plaintext);
			 	$i++;
			 	
			 }
			$response['similar_site'] = $similar_site;

			$similar_site_overlap = array();
			$get_similar_overlap_html = $html->find('div#card_mini_audience section section.table div.Body div.Row div.overlap span.truncation');
			$i = 0;
			foreach ($get_similar_overlap_html as $key => $value) {
				$similar_site_overlap[$i] = trim($value->innertext);
				$i++;
			 }
			 $response['similar_site_overlap'] = $similar_site_overlap;
			 


			/**
			 * TOP KEYWORDS BY TRAFFIC
			 */
			$keyword_top = array();
			$keyword_traffic = $html->find('div#card_mini_topkw section.table div.Body div.Row div.keyword span.truncation');
			$i = 0;
			foreach ($keyword_traffic as $key => $value) {
				$keyword_top[$i] = trim($value->plaintext);
				$i++;
				
			}
			$response['keyword_top'] = $keyword_top;
			

			$search_traffic = array();
			$search_metric = $html->find('div#card_mini_topkw section.table div.Body div.Row div.metric_one span.truncation');
			$i = 0;
			foreach ($search_metric as $key => $value) {
				$search_traffic[$i] = trim($value->plaintext);
				$i++;
			}
			$response['search_traffic'] = $search_traffic;

			$share_voice = array();
			$search_voice = $html->find('div#card_mini_topkw section.table div.Body div.Row div.metric_two span.truncation');
			$i = 0;
			foreach ($search_voice as $key => $value) {
				$share_voice[$i] = trim($value->plaintext);
				$i++;
			}
			$response['share_voice'] = $share_voice;

			/**
			 * ALEXA RANK 90 DAY TREND
			 */

			$alexa_rank = isset($html->find('div#card_mini_trafficMetrics div.rankmini-container div.rankmini-global div.rankmini-rank',0)->innertext) ? $html->find('div#card_mini_trafficMetrics div.rankmini-container div.rankmini-global div.rankmini-rank',0)->innertext: $this->CI->lang->line("No data"); 
			$alexa_rank_spend_time = isset($html->find('div#card_mini_trafficMetrics div.rankmini-container div.rankmini-daily div.rankmini-rank',0)->innertext) ? $html->find('div#card_mini_trafficMetrics div.rankmini-container div.rankmini-daily div.rankmini-rank',0)->innertext: $this->CI->lang->line("No data");
			$response['alexa_rank'] = trim($alexa_rank);
			$response['alexa_rank_spend_time'] = trim($alexa_rank_spend_time);


			// =========================KEYWORD OPPORTUNITIES =================
			/**
			 * Keyword Gaps 
			 */
			 $keyword_gaps = array();
			 $keyword_gaps_html = $html->find('div#card_gaps section.fancymobile div.Body div.Row div.keyword');
			 $i = 0;
			 foreach ($keyword_gaps_html as $key => $value) {
			 	$keyword_gaps[$i] = trim($value->plaintext);
			    $i++;
			 }

			 $response['keyword_gaps'] = $keyword_gaps;


			$keyword_gaps_trafic_competitor = array();
			$keyword_gaps_trafic_competitor_html = $html->find('div#card_gaps section.fancymobile div.Body div.Row div.metric_one span.truncation');
			$i = 0;
			foreach ($keyword_gaps_trafic_competitor_html as $key => $value) {
				$keyword_gaps_trafic_competitor[$i] = preg_replace('/[^0-9]/','',trim($value->outertext));
				 $i++;
			}
			$response['keyword_gaps_trafic_competitor'] = $keyword_gaps_trafic_competitor;

			$keyword_gaps_search_popularity = array();
			$keyword_gaps_trafic_competitor_html = $html->find('div#card_gaps section.fancymobile div.Body div.Row div.metric_two span.truncation');
			$i = 0;
			foreach ($keyword_gaps_trafic_competitor_html as $key => $value) {
				
				$keyword_gaps_search_popularity[$i] = preg_replace('/[^0-9]/','',trim($value->outertext));
				$i++;
			}
			$response['keyword_gaps_search_popularity'] = $keyword_gaps_search_popularity;
			/**
			 * Easy-to-Rank Keywords
			 */
			 $easyto_rank_keyword = array();
			 $easyto_rank_keyword_html = $html->find('div#card_kwdiff section.fancymobile div.Body div.Row div.keyword span.truncation');
			 $i = 0;
			 foreach ($easyto_rank_keyword_html as $key => $value) {
			 	$easyto_rank_keyword[$i] = trim($value->plaintext);
			 	$i++;
			 }
			 $response['easyto_rank_keyword'] = $easyto_rank_keyword;

			 $easyto_rank_relevence = array();
			 $easyto_rank_relevence_html = $html->find('div#card_kwdiff section.fancymobile div.Body div.Row div.metric_one span.truncation');
			 $i = 0;
			 foreach ($easyto_rank_relevence_html as $key => $value) {
			 	$easyto_rank_relevence[$i] = preg_replace('/[^0-9]/','',trim($value->outertext));
			 	$i++;
			 }
			 $response['easyto_rank_relevence'] = $easyto_rank_relevence;

			 $easyto_rank_search_popularity = array();
			 $easyto_rank_search_popularity_html = $html->find('div#card_kwdiff section.fancymobile div.Body div.Row div.metric_two span.truncation');
			 $i = 0;
			 foreach ($easyto_rank_search_popularity_html as $key => $value) {
			 	$easyto_rank_search_popularity[$i] = preg_replace('/[^0-9]/','',trim($value->outertext));
			 	$i++;
			 }
			 $response['easyto_rank_search_popularity'] = $easyto_rank_search_popularity;

			/**
			 * Buyer Keywords
			 */
			 $buyer_keyword = array();
			 $buyer_keyword_html = $html->find('div#card_buyer section.fancymobile div.Body div.Row div.keyword span.truncation');
			 $i = 0;
			 foreach ($buyer_keyword_html as $key => $value) {
			 	$buyer_keyword[$i] = $value->innertext;
			 	$i++;
			 }
			 $response['buyer_keyword'] = $buyer_keyword;

			 $buyer_keyword_traffic_to_competitor = array();
			 $traffic_to_competitor_html = $html->find('div#card_buyer section.fancymobile div.Body div.Row div.metric_one span.truncation');
			 $i = 0;
			 foreach ($traffic_to_competitor_html as $key => $value) {
			 	$buyer_keyword_traffic_to_competitor[$i] = preg_replace('/[^0-9]/','',trim($value->outertext));
			 	$i++;
			 }
			 $response['buyer_keyword_traffic_to_competitor'] = $buyer_keyword_traffic_to_competitor;

			 $buyer_keyword_organic_competitor = array();
			 $buyer_keyword_organic_competitor_html = $html->find('div#card_buyer section.fancymobile div.Body div.Row div.metric_two span.truncation');
			 $i = 0;
			 foreach ($buyer_keyword_organic_competitor_html as $key => $value) {
			 	$buyer_keyword_organic_competitor[$i] = preg_replace('/[^0-9]/','',trim($value->outertext));
			 	$i++;
			 }
			 $response['buyer_keyword_organic_competitor'] = $buyer_keyword_organic_competitor;
			 /**
			  * Optimization Opportunities
			  */

			 $optimization_opportunities = array();
			 $optimization_opportunities_html = $html->find('div#card_sitekw section.fancymobile div.Body div.Row div.keyword span.truncation');
			 $i = 0;
			 foreach ($optimization_opportunities_html as $key => $value) {
			 	$optimization_opportunities[$i] = trim($value->innertext);
			 	$i++;
			 }
			 $response['optimization_opportunities'] = $optimization_opportunities;

			 $optimization_opportunities_search_popularity = array();
			 $optimization_opportunities_search_popularity_html = $html->find('div#card_sitekw section.fancymobile div.Body div.Row div.metric_one span.truncation');
			 $i = 0;
			 foreach ($optimization_opportunities_search_popularity_html as $key => $value) {
			 	$optimization_opportunities_search_popularity[$i] = preg_replace('/[^0-9]/','',trim($value->outertext));
			 	$i++;
			 }
			 $response['optimization_opportunities_search_popularity'] = $optimization_opportunities_search_popularity;

			 $optimization_opportunities_organic_share_of_voice = array();
			 $optimization_opportunities_organic_share_of_voice_html = $html->find('div#card_sitekw section.fancymobile div.Body div.Row div.metric_two span.truncation');
			 $i = 0;
			 foreach ($optimization_opportunities_organic_share_of_voice_html as $key => $value) {
			 	$optimization_opportunities_organic_share_of_voice[$i] = preg_replace('/[^0-9-%-.]/','',trim($value->outertext));
			 	$i++;
			 }

			 $response['optimization_opportunities_organic_share_of_voice'] = $optimization_opportunities_organic_share_of_voice;

			 // ======================= COMPETITIVE ANALYSIS ===============
			 
			 /**
			  * Referral Sites
			  */
			 $refferal_sites = array();
			 $refferal_sites_html = $html->find('div#card_referralsites section.table div.Body div.Row div.site');
			 $i = 0;
			 foreach ($refferal_sites_html as $key => $value) {
			 	$refferal_sites[$i] = trim($value->plaintext);
			    $i++;
			 }
			 $response['refferal_sites'] = $refferal_sites;

			 $refferal_sites_links = array();
			 $refferal_sites_links_html = $html->find('div#card_referralsites section.table div.Body div.Row div.metric_one');
			 $i = 0;
			 foreach ($refferal_sites_links_html as $key => $value) {
			 	$refferal_sites_links[$i] = trim($value->plaintext);
			    $i++;
			 }
			 $response['refferal_sites_links'] = $refferal_sites_links;
			 /**
			  * Top Keywords
			  */
			 $top_keywords = array();
			 $top_keywords_html = $html->find('div#card_topkeywords section.table div.Body div.Row div.keyword span.truncation');
			 $i = 0;
			 foreach ($top_keywords_html as $key => $value) {
			 	$top_keywords[$i] = trim($value->plaintext);
			 	$i++;
			 }
			 $response['top_keywords'] = $top_keywords;

			 $top_keywords_search_traficc = array();
			 $top_keywords_search_traficc_html = $html->find('div#card_topkeywords section.table div.Body div.Row div.metric_one span.truncation');
			 $i = 0;
			 foreach ($top_keywords_search_traficc_html as $key => $value) {
			 	$top_keywords_search_traficc[$i] = trim($value->innertext);
			 	$i++;
			 }
			 $response['top_keywords_search_traficc'] = $top_keywords_search_traficc;

			 $top_keywords_share_of_voice = array();
			 $top_keywords_share_of_voice_html = $html->find('div#card_topkeywords section.table div.Body div.Row div.metric_two span.truncation');
			 $i = 0;
			 foreach ($top_keywords_share_of_voice_html as $key => $value) {
			 	$top_keywords_share_of_voice[$i] = trim($value->innertext);
			 	$i++;
			 }
			 $response['top_keywords_share_of_voice'] = $top_keywords_share_of_voice;


			//=====================AUDIENCE ANALYSIS======================


			/**
			 * Audience Overlap
			 */
			 $site_overlap_score = array();
			 $site_overlap_score_html = $html->find('div#card_overlap div.ACard div.padding section.overlap div.ThirdFull div.Body div.Row div.overlap span.truncation');
			 $i = 0;
			 foreach ($site_overlap_score_html as $key => $value) {
			 	$site_overlap_score[$i] = trim($value->innertext);
			 	$i++;
			 }
			 $response['site_overlap_score'] = $site_overlap_score;


			 $similar_to_this_sites = array();
			 $similar_to_this_sites_html = $html->find('div#card_overlap div.ACard div.padding section.overlap div.ThirdFull div.Body div.Row div.site a.truncation');
			 $i = 0;
			 foreach ($similar_to_this_sites_html as $key => $value) {
			 	$similar_to_this_sites[$i] = trim($value->plaintext);
			 	$i++;
			 }
			 $response['similar_to_this_sites'] = $similar_to_this_sites;


			 $similar_to_this_sites_alexa_rank = array();
			 $similar_to_this_sites_alexa_rank_html = $html->find('div#card_overlap div.ACard div.padding section.overlap div.ThirdFull div.Body div.Row div.metric_two span.truncation');
			 $i = 0;
			 foreach ($similar_to_this_sites_alexa_rank_html as $key => $value) {
			 	$similar_to_this_sites_alexa_rank[$i] = trim($value->plaintext);
			 	$i++;
			 }
			 $response['similar_to_this_sites_alexa_rank'] = $similar_to_this_sites_alexa_rank;


			//==================TRAFFIC STATISTICS =====================
			

			/**
			 * Audience Geography
			 */
			$card_geography_country = array();
			$card_geography_html = $html->find("div#card_geography section section.country div.visitorList ul li div#countryName");
			$i = 0;
			foreach ($card_geography_html as $key => $value) {
				$card_geography_country[$i] = $value->plaintext;
				$i++;
			}
			$response['card_geography_country'] = $card_geography_country;

			$card_geography_countryPercent = array();
			$card_geography_countryPercent_html = $html->find("div#card_geography section section.country div.visitorList ul li div#countryPercent");
			$i = 0;
			foreach ($card_geography_countryPercent_html as $key => $value) {
				$card_geography_countryPercent[$i] = $value->plaintext;
				$i++;
			}
			$response['card_geography_countryPercent'] = $card_geography_countryPercent;

			/**
			 * Site Flow
			 */

			$site_metrics = array();
			$site_metrics_html = $html->find('div#card_metrics section.stream div.flex div.Half p.truncation span.delta');
			$i = 0;
			foreach ($site_metrics_html as $key => $value) {
				$site_metrics[$i] = $value->innertext;
				$i++;
			}
			$response['site_metrics'] = $site_metrics;

			$site_metrics_domains = array();
			$site_metrics_domains_html = $html->find('div#card_metrics section.stream div.flex div.Half p.truncation');
			$i = 0;
			foreach ($site_metrics_domains_html as $key => $value) {
				$site_metrics_domains[$i] = preg_replace('/[0-9-%-.]+/', '', $value->innertext);
				$i++;
			}	
			$response['site_metrics_domains'] = $site_metrics_domains;


		}
		else{
			$response['status']="error";
		}
		

		
		return $response;
		
	}



	/***	Get Similar Web data from their site 	***/
	
	function similar_web_raw_data($domain,$proxy="")
	{

	   	$alexa_url= "https://www.similarweb.com/website/{$domain}";
		$ch = curl_init(); // initialize curl handle
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_VERBOSE, 0);
	    curl_setopt($ch,CURLOPT_USERAGENT,'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.13) Gecko/20080311 Firefox/2.0.0.13');
		curl_setopt($ch, CURLOPT_AUTOREFERER, false);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT,7);
		curl_setopt($ch, CURLOPT_REFERER, 'http://'.$alexa_url);
		curl_setopt($ch, CURLOPT_URL,$alexa_url); // set url to post to
		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1); // return into a variable
		curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
		curl_setopt($ch, CURLOPT_POST, 0); // set POST method
		
		/***** Proxy set for google . if lot of request gone, google will stop reponding. That's why it's should use some proxy *****/
		/**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
		
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
			
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies1.txt");  
		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies1.txt");	 
	    $content = curl_exec($ch); // run the whole process
		
		$connect_info	= curl_getinfo($ch);
		$http_code=$connect_info['http_code'];
		
		curl_close($ch);
		
		
		
	 
		/*********	Get Global Rank **********/ 
		
		
		preg_match('#<h3.*?class="websiteRanks-titleText">Global Rank</h3>.*?<div class="websiteRanks-valueContainer js-websiteRanksValue">(.*?)</div>#si', $content, $matches);
			
		$global_rank=isset($matches[1])? trim(strip_tags($matches[1])) : 'No Data';
			
		/******	Get Country Rank *******/
		
		preg_match('#<h3.*?class="websiteRanks-titleText">Country Rank</h3>.*?<div class="websiteRanks-valueContainer js-websiteRanksValue">(.*?)</div>#si', $content, $matches);
		
		$country_rank=isset($matches[1])? trim(strip_tags($matches[1])) : 'No Data';
		
		
		/**	Get Country ***/
		
		preg_match('#<h3.*?class="websiteRanks-titleText">Country Rank</h3>.*?<div.*?class="websiteRanks-name">.*?<a.*?class="websiteRanks-nameText".*?>(.*?)</a>#si', $content, $matches);
		
		$country=isset($matches[1])? trim(strip_tags($matches[1])) : 'No Data';

		
		/******Get Category Rank********/	
			
		preg_match('#<h3.*?class="websiteRanks-titleText">Category Rank</h3>.*?<div class="websiteRanks-valueContainer js-websiteRanksValue">(.*?)</div>#si', $content, $matches);
		
		$category_rank	=	isset($matches[1])? trim(strip_tags($matches[1])) : 'No Data';
		
		/**************	Get Category *********/
		
		preg_match('#<h3.*?class="websiteRanks-titleText">Category Rank</h3>.*?<div.*?class="websiteRanks-name">.*?<a.*?class="websiteRanks-nameText".*?>(.*?)</a>#si', $content, $matches);
		
		$category=	isset($matches[1])? trim(strip_tags($matches[1])) : 'No Data';
			
		
		/*******************		Get Engagement History		********************/
		/****		Get Total Visit		******/
		preg_match('#<div class="engagementInfo-line">.*?<div.*?data-type="visits">.*?<span class="engagementInfo-value.*?">(.*?)</span>#si', $content, $matches);
		
		 $total_visit=  isset($matches[1])? trim(strip_tags($matches[1])) : 'No Data';
		
		/******		Get Time on Site		*******/
		preg_match('#<div class="engagementInfo-line">.*?<div.*?data-type="time">.*?<span class="engagementInfo-value.*?">(.*?)</span>#si', $content, $matches);
		$time_on_site= isset($matches[1])? trim(strip_tags($matches[1])) : 'No Data';
		
		/******	Get Page Views *****/
		preg_match('#<div class="engagementInfo-line">.*?<div.*?data-type="ppv">.*?<span class="engagementInfo-value.*?">(.*?)</span>#si', $content, $matches);
		$page_views= isset($matches[1])? trim(strip_tags($matches[1])) : 'No Data';
		
		/********	Get Bounce Rate *******/
		preg_match('#<div class="engagementInfo-line">.*?<div.*?data-type="bounce">.*?<span class="engagementInfo-value.*?">(.*?)</span>#si', $content, $matches);
		 $bounce = isset($matches[1])? trim(strip_tags($matches[1])) : 'No Data';
		
		
		/***********   Traffic by countries   On desktop, in the last 3 months *************/
		$html = new simple_html_dom();
		$html->load($content);
	
	 	$traffic_by_countries_div = $html->find('div.countries-list span.accordion-toggle');	
		$i=0;
		$traffic_country=array();
		$traffic_country_percentage =array();
		
		foreach($traffic_by_countries_div as $span)
		{
			if(isset($span->find('a',0)->plaintext))	
			{
				$traffic_country[$i] = $span->find('a',0)->plaintext;				
			}
			else
			{

				if(isset($span->find('span.country-name',0)->plaintext))	
				$traffic_country[$i]= $span->find('span.country-name',0)->plaintext;
				
			}

			if(isset($span->find('span.traffic-share-value',0)->plaintext))	
			{
				$traffic_country_percentage[$i]	= $span->find('span.traffic-share-value',0)->plaintext;
			}
			$i++;
		}
		
		
		/***********Get Traffic Sources  On desktop, in the last 3 months***********/
		
		/***** Get Direct Traffic *****/
		preg_match('#<div class="trafficSourcesChart">.*?<li.*?data-key="Direct">.*?<div class="trafficSourcesChart-value">(.*?)</div>#si', $content, $matches);
		
		$direct_traffic=isset($matches[1])? $matches[1] : 'No Data';
		
		/******		Get Referrals Traffic		********/
		preg_match('#<div class="trafficSourcesChart">.*?<li.*?data-key="Referrals">.*?<div class="trafficSourcesChart-value">(.*?)</div>#si', $content, $matches);
		
	    $referral_traffic=isset($matches[1])? $matches[1] : 'No Data';
		
		
		/***Get Search Traffic ****/
		preg_match('#<div class="trafficSourcesChart">.*?<li.*?data-key="Search">.*?<div class="trafficSourcesChart-value">(.*?)</div>#si', $content, $matches);
		$search_traffic=isset($matches[1])? $matches[1] : 'No Data';
		
		/****Get Social Traffic ***/
		preg_match('#<div class="trafficSourcesChart">.*?<li.*?data-key="Social">.*?<div class="trafficSourcesChart-value">(.*?)</div>#si', $content, $matches);
		$social_traffic=isset($matches[1])? $matches[1] : 'No Data';
		
		/*****	Get Mail Traffic ****/
		preg_match('#<div class="trafficSourcesChart">.*?<li.*?data-key="Mail">.*?<div class="trafficSourcesChart-value">(.*?)</div>#si', $content, $matches);
		$mail_traffic=isset($matches[1])? $matches[1] : 'No Data';
		
		
		/****	Get traffic from display advertisement *******/
		
		preg_match('#<div class="trafficSourcesChart">.*?<li.*?data-key="Display">.*?<div class="trafficSourcesChart-value">(.*?)</div>#si', $content, $matches);
		$display_traffic=isset($matches[1])? $matches[1] : 'No Data';
		
		
		
		/*******  Get refferal site address *******/
		$referral_site_ul = $html->find('div.referring ul.websitePage-list li');
		
		$i=0;
		$top_referral_site=array();
		
		foreach($referral_site_ul as $li)
		{
			if(isset($li->find('a',0)->plaintext))
			{
				$top_referral_site[$i] = $li->find('a',0)->plaintext;
			}
			$i++;
		}
		
		
		/******  Get Destination Site address **********/	
		$destination_site_ul = $html->find('div.destination ul.websitePage-list li');
		
		$i=0;
		$top_destination_site=array();
		
		foreach($destination_site_ul as $li)
		{
			if(isset($li->find('a',0)->plaintext))
			{
				$top_destination_site[$i] = $li->find('a',0)->plaintext;
			}
			$i++;
		}
		
		
		/*******		Get Search category percentage 	*******/
		
		/***** Get Organic Search Percentage ****/
		
		preg_match('#<div class="searchPie-text searchPie-text--left.*?">.*?<span class="searchPie-number">(.*?)</span>#si', $content, $matches);
		$organic_search_percentage =isset($matches[1])? $matches[1] : 'No Data';
	 
		/****Get Paid Search Percentage***/
		 
		preg_match('#<div class="searchPie-text searchPie-text--right.*?">.*?<span class="searchPie-number">(.*?)</span>#si', $content, $matches);
		$paid_search_percentage =isset($matches[1])? $matches[1] : 'No Data';
	 
	 	/*****	Get Organic Keyword ************/
	  	$organic_keyword_ul = $html->find('div.searchKeywords-text--left ul.searchKeywords-list li');
		
		$i=0;
		$top_organic_keyword=array();
		
		foreach($organic_keyword_ul as $li)
		{
			if(isset($li->find('span.searchKeywords-words',0)->plaintext))
			{
				$top_organic_keyword[$i] = $li->find('span.searchKeywords-words',0)->plaintext;
			}
			$i++;
		}
		
		
		/*******	 Get Paid Search Keyword	 *******/
		
		
	   $paid_keyword_ul = $html->find('div.searchKeywords-text--right ul.searchKeywords-list li');
		
		$i=0;
		$top_paid_keyword=array();
		
		foreach($paid_keyword_ul as $li)
		{
			if(isset($li->find('span.searchKeywords-words',0)->plaintext))
			{
				$top_paid_keyword[$i] = $li->find('span.searchKeywords-words',0)->plaintext;
			}
			$i++;
		}
	

		$social_info_ul = $html->find('ul.socialList li');
		
		$i=0;
		$social_site_name=array();
		$social_site_percentage=array();
		
		foreach($social_info_ul as $li){
			if(isset($li->find('a',0)->plaintext))
			{
				$social_site_name[$i] = $li->find('a',0)->plaintext;
			}
			if(isset($li->find('.socialItem-value',0)->plaintext))
			{
				$social_site_percentage[$i]=$li->find('.socialItem-value',0)->plaintext;
			}
			$i++;
		}
		
		$response=array();
		
		if($http_code=='200')
		{
			$response['status']="success";
		}
		else
		{
			$response['status']="error";
		}
		
		$response['global_rank']=$global_rank;
		$response['country_rank']=$country_rank;
		$response['country']=$country;
		$response['category_rank']=$category_rank;
		$response['category']=$category;
		$response['total_visit']=$total_visit;
		$response['time_on_site']=$time_on_site;
		$response['page_views']=$page_views;
		$response['bounce']=$bounce;
		$response['traffic_country']=$traffic_country;
		$response['traffic_country_percentage']=$traffic_country_percentage;
		$response['direct_traffic']=$direct_traffic;
		$response['referral_traffic']=$referral_traffic;
		$response['search_traffic']=$search_traffic;
		$response['social_traffic']=$social_traffic;
		$response['mail_traffic']=$mail_traffic;
		$response['display_traffic']=$display_traffic;
		$response['top_referral_site']=$top_referral_site;
		$response['top_destination_site']=$top_destination_site;
		$response['organic_search_percentage']=$organic_search_percentage;
		$response['paid_search_percentage']=$paid_search_percentage;
		$response['top_organic_keyword']=$top_organic_keyword;
		$response['top_paid_keyword']=$top_paid_keyword;
		$response['social_site_name']=$social_site_name;
		$response['social_site_percentage']=$social_site_percentage;
		
		return $response;
		
	}



	public function page_status_check($url)
    {
        $useragent = $_SERVER['HTTP_USER_AGENT'];
        $options = array(
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_HEADER         => false,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_USERAGENT      => $useragent,
                CURLOPT_AUTOREFERER    => true,
                CURLOPT_CONNECTTIMEOUT => 30,
                CURLOPT_TIMEOUT        => 30,
                CURLOPT_MAXREDIRS      => 10,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => false,
        );
        $ch = curl_init($url);
        curl_setopt_array($ch, $options);
        curl_exec($ch);
        $get_info = curl_getinfo($ch);
        $httpcode=$get_info['http_code'];
        curl_close($ch);
        return $get_info;
    }

    public function fixArrayKey(&$arr)
    {
        $arr = array_combine(
            array_map(
                function ($str) {
                    return str_replace(" ", "_", $str);
                },
                array_keys($arr)
            ),
            array_values($arr)
        );

        foreach ($arr as $key => $val) {
            if (is_array($val)) {
                fixArrayKey($arr[$key]);
            }
        }
    }

    // original function :D
	public function whois_email($domain='')
    {	

    	$response = array(); 
    	$domain=$this->clean_domain_name($domain);
    	$whois = new cWhois;
    	$whois->setDomain($domain);
    	$data = $whois->whois();

    	$new_data = preg_split("/\r\n|\n|\r/", $data);

    	$empty_array = array();
    	foreach ($new_data as $key => $value) {
    		
    		preg_match('@([a-zA-Z\s]+):\s(.*)@mui', $value, $matches);
    		if (isset($matches[1]) && $matches[2]) {
    			$empty_array[$matches[1]]  =  $matches[2];
    		}

    	}

    	$keys = str_replace( ' ', '_', array_keys( $empty_array ) );
    	$results = array_combine( $keys, array_values( $empty_array ) );


    	if(empty($results))
    	{
    		$url="https://mostofa.club/development/whois_info/index.php?domain={$domain}";
    		$ch = curl_init(); // initialize curl handle
    		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
    		curl_setopt($ch, CURLOPT_AUTOREFERER, false);
    		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
    		curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
    		curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
    		curl_setopt($ch, CURLOPT_FAILONERROR, 1);
    		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
    		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
    		curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
    		curl_setopt($ch, CURLOPT_POST, 0); // set POST method
    		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    		curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
    		curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");
    		$content = curl_exec($ch); // run the whole process
    		$curl_info= curl_getinfo($ch);
    		curl_close($ch);
    		$data = json_decode($content,true);

    		$new_data = preg_split("/\r\n|\n|\r/", $data);

    		$empty_array = array();
    		foreach ($new_data as $key => $value) {
    			
    			preg_match('@([a-zA-Z\s]+):\s(.*)@mui', $value, $matches);
    			if (isset($matches[1]) && $matches[2]) {
    				$empty_array[$matches[1]]  =  $matches[2];
    			}

    		}

    		$keys = str_replace( ' ', '_', array_keys( $empty_array ) );
    		$results = array_combine( $keys, array_values( $empty_array ) );
    	}


    	if (isset($results['Registrar_URL'])) {
    		$response['is_registered'] = "Yes";
    	}
    	else
    	{
    		$response['is_registered'] = "No";
    	}

    	$response['tech_email'] = isset($results['Tech_Email']) ? $results['Tech_Email'] : "";
    	$response['admin_email'] = isset($results['Admin_Email']) ? $results['Admin_Email'] : "";
    	$response['name_servers'] = isset($results['Name_Server']) ? $results['Name_Server'] : "";
    	$response['created_at'] = isset($results['Creation_Date']) ? date('Y:m:d:H:i:s',strtotime($results['Creation_Date'])) : "";
    	$response['changed_at'] = isset($results['Updated_Date']) ? date('Y:m:d:H:i:s',strtotime($results['Updated_Date'])) : "";
    	$response['expire_at'] = isset($results['Registrar_Registration_Expiration_Date']) ? date('Y:m:d:H:i:s',strtotime($results['Registrar_Registration_Expiration_Date'])) : "";
    	$response['registrar_url'] = isset($results['Registrar_URL']) ? $results['Registrar_URL'] : "";
    	$response['registrar'] = isset($results['Registrar']) ? $results['Registrar'] : "";
    	$response['registrant_name'] = isset($results['Registrant_Name']) ? $results['Registrant_Name'] : "";
    	$response['registrant_organization'] = isset($results['Registrant_Organization']) ? $results['Registrant_Organization'] : "";
    	$response['registrant_street'] = isset($results['Registrant_Street']) ? $results['Registrant_Street'] : "";
    	$response['registrant_city'] = isset($results['Registrant_City']) ? $results['Registrant_City'] : "";
    	$response['registrant_state'] = isset($results['Province']) ? $results['Province'] : "";
    	$response['registrant_postal_code'] = isset($results['Registrant_Postal_Code']) ? $results['Registrant_Postal_Code'] : "";
    	$response['registrant_email'] = isset($results['Registrant_Email']) ? $results['Registrant_Email'] : "";
    	$response['registrant_country'] = isset($results['Registrant_Country']) ? $results['Registrant_Country'] : "";
    	$response['registrant_phone'] = isset($results['Registrant_Phone']) ? $results['Registrant_Phone'] : "";
    	$response['admin_name'] = isset($results['Admin_Name']) ? $results['Admin_Name'] : "";
    	$response['admin_street'] = isset($results['Admin_Street']) ? $results['Admin_Street'] : "";
    	$response['admin_city'] = isset($results['Admin_City']) ? $results['Admin_City'] : "";
    	$response['admin_country'] = isset($results['Admin_Country']) ? $results['Admin_Country'] : "";
    	$response['admin_postal_code'] = isset($results['Admin_Postal_Code']) ? $results['Admin_Postal_Code'] : "";
    	$response['admin_phone'] = isset($results['Admin_Phone']) ? $results['Admin_Phone'] : "";

		
        return $response;
    } 

     public function get_email($content)
    {
        preg_match_all('/([\w+\.]*\w+@[\w+\.]*\w+[\w+\-\w+]*\.\w+)/is', $content, $results);
        return $results[1];
    }    
	
	
	public function google_adwords_ad($keyword, $page_number=0, $proxy='',$country="",$language="")
	{

		$keyword=urlencode($keyword);
		$title=array();
		$description=array();
		$link=array();


		if ($page_number) {
			$page_str="&start={$page_number}";
		} else {
			$page_str="";
		}

		$localization_string="";

		if($country!=""){
			$localization_string.="&cr=country{$country}";
		}

		if($language!=""){
			$localization_string.="&lr=lang_{$language}";
		}

		$url="www.google.com/search?q={$keyword}{$page_str}{$localization_string}";	
		$content=$this->getContentFromSearchEngine($url, $proxy);	
		
		$content=str_replace('<font size="-1">Ads</font>',"",$content);  /**Remove the forst font tag where "Ads" word is displayed***/
		 	   
		$html = new simple_html_dom();
		
		$html->load($content);
		
		$google_adwords_table=array();
		$google_adwords_table = $html->find('table[bgcolor="#fff8e7"] tr');
		$i=0;
		
		foreach($google_adwords_table as $tr){
			$td = $tr->find('td');
			foreach($td as $t){
			 	for($k=0;$k<=6;$k++){
					if(isset($t->find('a',$k)->plaintext)){
					
						$title[$i]			= $t->find('a',$k)->plaintext;
						$description_font_number=$k*3;  /***3 font tag in one section **/
						$description[$i]	= $t->find('font[size="-1"]',$description_font_number)->plaintext;
						$link[$i]			=$t->find('font[color="green"]',$k)->plaintext;
						$title[$i]=str_replace("&nbsp;","", $title[$i]);
						$description[$i]=str_replace("&nbsp;","", $description[$i]);
						$link[$i]=str_replace("&nbsp;","", $link[$i]);
						$i++;
					}
				}
			}		
		}
		
		$response=array("title"=>array(),"description"=>array(),"link"=>array());
		$response['title']=$title;
		$response['description']=$description;
		$response['link']=$link;
		return $response;
		
	}

	public function email_validate($email)
    {
        $email=trim($email);
        $is_valid=0;
        $is_exists=0;
        
        /***Validation check***/
        $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';

        if (preg_match($pattern, $email) === 1) {
            $is_valid=1;
        }
        
         if($is_valid){
            /*** MX record check ***/
            @list($name, $domain)=explode('@', $email);
			
            if (!checkdnsrr($domain, 'MX')) {
                $is_exists=0;
            } else {
                $is_exists=1;
            } 	
		}
                    
        $result['is_valid']=$is_valid;
        $result['is_exists']=$is_exists;
        return $result;
    }



	function get_general_content($url,$proxy="") {

		$ch = curl_init(); // initialize curl handle
       /* curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_VERBOSE, 0);*/
        //curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
        curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 120); // times out after 50s
        curl_setopt($ch, CURLOPT_POST, 0); // set POST method

           /**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
		
		if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
		 
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
            curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");
            
            $content = curl_exec($ch); // run the whole process
			
            curl_close($ch);
			
			return $content;
			
	}
	
	
	
	function link_statistics($url){
		
		$internal_link_count=0;
		$external_link_count=0;
		$nofollow_link_count=0;
		$dofollow_link_count=0;
		
		$internal_link=array();
		$external_link=array();
		
		$analyzed_url_domain=get_domain_only($url);
		
		
		$content=$this->get_general_content($url);
		preg_match_all("#<a(.*?)>#si",$content,$links);
	
		
		$i=0;
		
		foreach($links[1] as $link_info){
			 
			preg_match('#href=[",\'](.*?)[",\']#',$link_info,$matches);
			$link=isset($matches[1])?$matches[1]:"";
			$link=trim($link, "'");
			
			/********/
			if($link=="" || substr($link, 0, 1) == '#' || stripos($link,"javascript:")!==FALSE || stripos($link,"tel:")!==FALSE){
				continue;
			}
			
			$link_domain=get_domain_only($link);
			
			/**** If domain is get as web page, then simply it is internal link ***/
			
			if(is_web_page($link_domain) || $link_domain==$analyzed_url_domain || $link_domain==""){
			
				$internal_link_count++;
				$internal_link[$i]['link']=$link;
				if(stripos($link_info,"nofollow")!==FALSE){
					$nofollow_link_count++;
					$internal_link[$i]['type']="nofollow";
				}
				else{
					$dofollow_link_count++;
					$internal_link[$i]['type']="dofollow";
				}	
			}
			
			else{
			
				$external_link_count++;
				$external_link[$i]['link']=$link;
				
				if(stripos($link_info,"nofollow")!==FALSE){
					$nofollow_link_count++;
					$external_link[$i]['type']="nofollow";
				}
				else{
					$dofollow_link_count++;
					$external_link[$i]['type']="dofollow";
				}	
				
			}
			
			$i++;
			
		}
		
		
		$response=array();
		
		$response['external_link_count']=$external_link_count;
		$response['internal_link_count']=$internal_link_count;
		$response['nofollow_count']=$nofollow_link_count;
		$response['do_follow_count']=$dofollow_link_count;
		$response['external_link']=$external_link;
		$response['internal_link']=$internal_link;
		
		return $response;
		
		
	}

	
	function google_correlated_trending_keyword($keyword,$country_code="us",$proxy=""){
	
		$keyword=urlencode($keyword);
		$url="http://www.google.com/trends/correlate/search?e={$keyword}&t=weekly&p={$country_code}";
		$content=$this->getContentFromSearchEngine($url, $proxy);	
		
		$html = new simple_html_dom();
		$html->load($content);
		
		$google_trends_li = $html->find('div#results li.result');
		$i=0;
		$correlation=array();
		$cor_keyword=array();
		
		foreach($google_trends_li as $li){
			
			if(isset($li->find('small',0)->plaintext)) 
				 $correlation[$i]= $li->find('small',0)->plaintext; 
				 
			if(isset($li->find('a',0)->plaintext))
				 $cor_keyword[$i]= $li->find('a',0)->plaintext; 
			else{
				$cor_keyword[$i]= $li->find('span',0)->plaintext; 
			}	
			
			$i++;
		}
		
		$response=array();
		
		$response['correlation']=$correlation;
		$response['cor_keyword']=$cor_keyword;
		$response['download_link']="http://www.google.com/trends/correlate/csv?e={$keyword}&t=weekly&p={$country_code}";
		
		return $response;
	
		
	}
	
	
	public function google_auto_sugesstion_keyword($keyword){
		$keyword=urlencode($keyword);
		$url="http://suggestqueries.google.com/complete/search?output=firefox&client=firefox&hl=en-US&q={$keyword}";
		$content=$this->get_general_content($url);
		$result=json_decode($content,true);
		return $result;
		
	}


	public function bing_auto_sugesstion_keyword($keyword){
		$keyword=urlencode($keyword);
		$url="http://api.bing.com/osjson.aspx?query={$keyword}";
		$content=$this->get_general_content($url);
		$result=json_decode($content,true);
		return $result;
		
	}

	public function yahoo_auto_sugesstion_keyword($keyword){
		$keyword=urlencode($keyword);
		$url="http://ff.search.yahoo.com/gossip?output=fxjson&command={$keyword}";
		$content=$this->get_general_content($url);
		$result=json_decode($content,true);
		return $result;
	}
	
	public function wiki_auto_sugesstion_keyword($keyword){
		$keyword=urlencode($keyword);
		$url="http://en.wikipedia.org/w/api.php?action=opensearch&search={$keyword}";
		$content=$this->get_general_content($url);
		$result=json_decode($content,true);
		return $result;
	}
	
	public function amazon_auto_sugesstion_keyword($keyword){
		$keyword=urlencode($keyword);
		$url="http://completion.amazon.com/search/complete?search-alias=aps&client=amazon-search-ui&mkt=1&q={$keyword}";
		$content=$this->get_general_content($url);
		$result=json_decode($content,true);
		return $result;
	}
		

	
	public function content_analysis($url){
		/***Remove last backslash from url**/
		
		$response=array();
		
		
		$url=trim($url,"/");
		
		$blocked_by_robots_txt="";
		
		$content=$this->get_general_content($url);
		$html = new simple_html_dom();
		$html->load($content);
		
		/*****Check Robot File *******/
		$robot_text=$this->get_general_content($url."/robots.txt");
		if($robot_text!=''){
			preg_match("#Disallow: /\s#si", $robot_text, $match);	
			if(empty($match)) 
				$blocked_by_robots_txt = "No";
			else 
				$blocked_by_robots_txt = "Yes";
		}
		
		else 
			$blocked_by_robots_txt="No";
			
			
		/****Get all meta tags *****/	
		
		$meta_tag_information=$this->get_meta_tag($content);
		
		/***Check meta robot****/
		
		if(isset($meta_tag_information['robots'])){
			if(stripos($meta_tag_information['robots'], "index") !== false){
				$blocked_by_meta_robot="No";
			}
				
			else if(stripos($meta_tag_information['robots'], "noindex") !== false)
				$blocked_by_meta_robot="Yes";
				
			else
				$blocked_by_meta_robot="No";
				
		}
		
		else{
			$blocked_by_meta_robot="No";
		}
		
		
		if(isset($meta_tag_information['robots'])){
			if(stripos($meta_tag_information['robots'], "follow") !== false)
				$nofollowed_by_meta_robot="No";
			else if(stripos($meta_tag_information['robots'], "nofollow") !== false)
				$nofollowed_by_meta_robot="Yes";
			else
				$nofollowed_by_meta_robot="No";
		}
		
		else{
			$nofollowed_by_meta_robot="No";
		}
		
		
		/*****Extract all headings *******/
		
		
		for($i=1;$i<=6;$i++){
		
			 $header_name="h{$i}";
			$header_name_result=array();
			
			$headers= $html->find($header_name);
			
			if(isset($headers)){
				foreach($headers as $header){
				 $header_name_result[] = $header->plaintext;
			 }
		  }
		 
		  $response[$header_name]=$header_name_result;
		  	
		}
		

		

		if(function_exists('iconv'))
		{
			$page_encoding =  mb_detect_encoding($content);
			if(isset($page_encoding))
			{
				$utf8_text = @iconv( $page_encoding, "utf-8//IGNORE", $content );
				$raw_text = $utf8_text;
			}
			else $raw_text = $content;
		} 
		else $raw_text = $content;


		$raw_text=$this->strip_html_tags($raw_text);
		$raw_text=str_replace("&nbsp;"," ",$raw_text);	

		$raw_text=str_replace("  "," ",$raw_text);		
		
		$total_number_of_words = str_word_count($raw_text);	
		
		$raw_text = preg_replace('~\h*\[(?:[^][]+|(?R))*+]\h*~', ' ', $raw_text); // replacing chars between brackets
		
		$raw_text = html_entity_decode( $raw_text, ENT_QUOTES, "UTF-8" ); /* Decode HTML entities */
		// keeping raw text into a different variable $raw_text_for_2_words for phrase keyword extract
		$raw_text_for_2_words = $raw_text;

		$punc_marks = array('!','@','#','$','%','^','&','*','-','+','/','"',':','|',',','.',';','(',')','{','}','[',']');	

			$raw_text = str_replace($punc_marks, "", $raw_text);

			$raw_text = preg_replace( "/\r|\n/", " ", $raw_text );

		// $raw_text = preg_replace('/[^A-Za-z0-9\-]/', " ", $raw_text); // deleting all special chars 
		$raw_text =  trim($raw_text); // trimming text

		$array_preposition = array(
		"a's",'accordingly','again','allows','also','amongst','anybody','anyways','appropriate','aside',
		'available','because','before','below','between','by', "can't",'certain','com','consider',
		'corresponding','definitely','different',"don't",'each','else','et','everybody','exactly',
		'fifth','follows','four','gets','goes','greetings','has','he', 'her','herein','him','how',"i'm",
		'immediate','indicate','instead','it','itself','know','later','lest','likely','ltd', 'me','more','must',
		'nd','needs','next','none','nothing','of','okay','ones','others','ourselves','own','placed','probably',
		'rather','regarding','right','saying','seeing','seen','serious','she','so','something','soon',
		'still',"t's",'th','that','theirs','there','therein',"they'd",'third','though','thus','toward',
		'try','under','unto','used','value','vs','way',"we've","weren't",'whence','whereas','whether',"who's",
		'why','within',"wouldn't","you'll",'yourself','able','across','against','almost','although',
		'an','anyhow','anywhere', 'are','ask','away','become','beforehand','beside','beyond',
		"c'mon",'cannot','certainly','come','considering','could','described','do','done',
		'edu','elsewhere','etc','everyone','example','first','for','from','getting','going','had',"hasn't",
		"he's",'here','hereupon','himself','howbeit',"i've",'in','indicated','into',"it'd",'just','known',
		'latter','let','little','mainly','mean','moreover','my','near','neither','nine','noone','novel','off',
		'old','only','otherwise','out','particular','please','provides','rd','regardless','said','says','seem',
		'self','seriously', 'should','some','sometime','sorry','sub','take','than',"that's",'them',
		"there's",'theres',"they'll",'this','three','to','towards','trying','unfortunately','up',
		'useful','various','want','we','welcome','what','whenever','whereby','which','whoever',
		'will','without','yes',"you're",'yourselves','about','actually',"ain't",'alone','always', 'and','anyone',
		'apart',"aren't",'asking','awfully','becomes','behind','besides','both',"c's",'cant','changes','comes',
		'contain',"couldn't",'despite','does','down','eg','enough','even','everything','except', 'five',
		'former','further','given','gone',"hadn't",'have','hello',"here's",'hers','his','however',
		'ie','inasmuch','indicates','inward',"it'll",'keep','knows','latterly',"let's",'look','many','meanwhile',
		'most','myself','nearly','never','no','nor','now','often','on', 'onto','ought','outside','particularly',
		'plus','que','re','regards','same','second','seemed','selves', 'seven',"shouldn't",'somebody',
		'sometimes','specified','such','taken', 'thank','thats','themselves','thereafter','thereupon',"they're",
		'thorough','through','together','tried','twice','unless','upon','uses','very','wants',"we'd",
		'well',"what's",'where','wherein','while','whole','willing',"won't",'yet',"you've",'zero',
		'above','after','all','along','am','another','anything','appear','around','associated','be','becoming',
		'being','best','brief','came','cause','clearly','concerning','containing','course','did',"doesn't",
		'downwards','eight','entirely','ever','everywhere','far','followed','formerly','furthermore','gives',
		'got','happens', "haven't",'help','hereafter','herself','hither',"i'd",'if','inc','inner', 'is',"it's",
		'keeps','last','least','like','looking','may', 'merely','mostly','name','necessary','nevertheless',
		'nobody','normally','nowhere','oh','once','or','our','over','per','possible','quite', 'really',
		'relatively','saw','secondly', 'seeming','sensible','several','since','somehow','somewhat',
		'specify','sup','tell','thanks','the','then','thereby','these',"they've",'thoroughly','throughout','too',
		'tries','two','unlikely','us','using','via','was',"we'll",'went','whatever',"where's",'whereupon',
		'whither','whom','wish','wonder','you','your','according','afterwards','allow','already','among','any',
		'anyway','appreciate','as','at','became','been','believe','better','but','can', 'causes','co',
		'consequently','contains', 'currently',"didn't",'doing','during', 'either','especially','every','ex',
		'few','following','forth','get','go','gotten','hardly','having','hence', 'hereby','hi','hopefully',
		"i'll",'ignored','indeed','insofar',"isn't",'its','kept','lately', 'less','liked','looks','maybe',
		'might','much','namely','need','new','non','not','obviously','ok','one','other', 'ours','overall',
		'perhaps','presumably', 'qv','reasonably','respectively','say','see', 'seems','sent','shall','six',
		'someone','somewhere','specifying','sure','tends','thanx','their','thence','therefore',
		'they','think','those','thru','took','truly','un', 'until','use','usually','viz',"wasn't","we're",
		'were','when','whereafter','wherever','who','whose','with','would',"you'd",'yours','a','b','c','d','e',
		'f','g','h','i','j','k','l','m','n', 'o','p','q','r','s','t','u','v','w','x','y','z','A','B','D','E',
		'F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','provide','1','2','3','4','5','6','7','8','9','0','1st',
		'2nd','3rd','000','10',0,'11'
		);

		/****** Get one word Keyword *****/
		// uppercasing $array_preposition values for delete from final array
		
		$one_keyword=array();
		
		$array_uppercase = array();
		foreach ($array_preposition as $value) $array_uppercase[] = ucfirst($value);	
		
		$sample_array = explode(" ", $raw_text);  // exploding raw text into array

		$sample_array = array_map('trim', $sample_array);
		
		$sample_array = array_filter($sample_array); // deleting blank values
		
		$sample_array = array_slice($sample_array, 0);  // recreating index for no gap in array


		// deleting stop words and prepositions from array
		$final_array_first_diff = array_udiff($sample_array, $array_preposition,'strcasecmp');

		$one_keyword_filter=array();
		foreach ($final_array_first_diff as $w) {
			
				preg_match("#\d*#", $w,$matches);
				if(empty($matches[0]))
					$one_keyword_filter[]=$w;

		}

		// creating an array of keywords as key and its occurence as value
		$one_keyword = array_count_values($one_keyword_filter);
		
		arsort($one_keyword); // sorting from top to bottom 
		
		$one_keyword = array_slice($one_keyword, 0,20); // reduece array to 20 elements 

		$two_keyword=array();
		
		$number_of_words =$this->mb_count_words($raw_text); // find the number of total words in raw text

		$word = explode(" ",$raw_text); 	// exploding raw text to an array of words

		$word = array_map('trim', $word);

		$sample_array_2_words = $word; 

		$sample_array_2_words = array_filter($sample_array_2_words); 	// filter array
		
		$sample_array_2_words = array_slice($sample_array_2_words, 0);	// slicing array	
		
		$half = 2; // length of phrase
	    
		for($i = 0; $i < $number_of_words - 1 ; $i++) // first for loop for total number of words
		{	
			$ingram=""; // a blank string		
			
			for($j=$i; $j < $half+$i; $j++) // 2nd for loop for creating all the phrases
			{
				if(isset($sample_array_2_words[$j]))
					$ingram = $ingram." ".$sample_array_2_words[$j];			
			}		

			if($ingram!="")	
				$two_keyword[]=$ingram;		// saving phrases to an array
		}

		$two_keyword = array_count_values($two_keyword);
		arsort($two_keyword);
		$two_keyword = array_slice($two_keyword, 0,20);  // reduce array to first 20 elements

		/****** Three Words ********/

		// $half=(int) count($word)/2; 
		
		$three_keyword=array();
		
			$half = 3;

			for($i = 0; $i < $number_of_words - 1 ; $i++)
			{	
				$ingram="";
				
				for($j=$i; $j < $half+$i; $j++)
				{
					if(isset($sample_array_2_words[$j]))
						$ingram = $ingram." ".$sample_array_2_words[$j];			
				}
				if($ingram!="")	
					$three_keyword[]=$ingram;		
			}
		
			
		 $three_keyword = array_count_values($three_keyword);
		 arsort($three_keyword);
		 $three_keyword = array_slice($three_keyword, 0,20);

		/***** Get 4 phrase keyword ***********/

		// $half=(int) count($word)/2; 
		$four_keyword=array();
		$half = 4;
		for($i = 0; $i < $number_of_words - 1 ; $i++)
		{	
			$ingram="";
			for($j=$i; $j < $half+$i; $j++)
			{
				if(isset($sample_array_2_words[$j]))
					$ingram = $ingram." ".$sample_array_2_words[$j];			
			}
			if($ingram!="")		
				$four_keyword[]=$ingram;		
		}

		$four_keyword = array_count_values($four_keyword);
		arsort($four_keyword);
		$split_word = array_slice($four_keyword, 0,20);

		
		$response['blocked_by_robot_txt']=$blocked_by_robots_txt;
		$response['meta_tag_information']=$meta_tag_information;
		$response['blocked_by_meta_robot']=$blocked_by_meta_robot;
		$response['nofollowed_by_meta_robot']=$nofollowed_by_meta_robot;
		
		$response['one_phrase']=$one_keyword;
		$response['two_phrase']=$two_keyword;
		$response['three_phrase']=$three_keyword;
		$response['four_phrase']=$split_word;
		
		$response['total_words']=$total_number_of_words;

		return $response;	 
		
	}
	
	
	
	function strip_html_tags( $text )
	{
		// PHP's strip_tags() function will remove tags, but it
		// doesn't remove scripts, styles, and other unwanted
		// invisible text between tags.  Also, as a prelude to
		// tokenizing the text, we need to insure that when
		// block-level tags (such as <p> or <div>) are removed,
		// neighboring words aren't joined.
		$text = preg_replace(
			array(
				// Remove invisible content
				'@<head[^>]*?>.*?</head>@siu',
				'@<style[^>]*?>.*?</style>@siu',
				'@<script[^>]*?.*?</script>@siu',
				'@<object[^>]*?.*?</object>@siu',
				'@<embed[^>]*?.*?</embed>@siu',
				'@<applet[^>]*?.*?</applet>@siu',
				'@<noframes[^>]*?.*?</noframes>@siu',
				'@<noscript[^>]*?.*?</noscript>@siu',
				'@<noembed[^>]*?.*?</noembed>@siu',

				// Add line breaks before & after blocks
				'@<((br)|(hr))@iu',
				'@</?((address)|(blockquote)|(center)|(del))@iu',
				'@</?((div)|(h[1-9])|(ins)|(isindex)|(p)|(pre))@iu',
				'@</?((dir)|(dl)|(dt)|(dd)|(li)|(menu)|(ol)|(ul))@iu',
				'@</?((table)|(th)|(td)|(caption))@iu',
				'@</?((form)|(button)|(fieldset)|(legend)|(input))@iu',
				'@</?((label)|(select)|(optgroup)|(option)|(textarea))@iu',
				'@</?((frameset)|(frame)|(iframe))@iu',
			),
			array(
				' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ', ' ',
				"\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0", "\n\$0",
				"\n\$0", "\n\$0",
			),
			$text );

		// Remove all remaining tags and comments and return.
		return strip_tags( $text );
	}

	/****This is for counting utf-8 words***/
	function mb_count_words($string) {
	    preg_match_all('/[\pL\pN\pPd]+/u', $string, $matches);
	    return count($matches[0]);
	}


	function clean_domain_name($domain){

	 		$domain=trim($domain);
			$domain=strtolower($domain);
			
			$domain=str_replace("www.","",$domain);
			$domain=str_replace("http://","",$domain);
			$domain=str_replace("https://","",$domain);
			$domain=str_replace("/","",$domain);
			
			return $domain; 
	}

	function mobile_ready($domain="",$key="")
	{	

		$domain=$this->clean_domain_name($domain);
		$domain=addHttp($domain);
		$url="https://www.googleapis.com/pagespeedonline/v5/runPagespeed?key=".$key."&url=".$domain."&strategy=mobile&category=performance";
		$ch = curl_init();
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch); // run the whole process
		$content= json_decode($content,TRUE);
		curl_close($ch);

		return $content;


	}
	
	
	
	function ipv6_check($url){
	
		$url=addHttp($url);
		
		$url_parse= @parse_url($url);
		$url_hostname=$url_parse['host'];
		$dns_result = @dns_get_record($url_hostname,DNS_A + DNS_AAAA);
		
		$ipv6_support=0;
		$ipv6="";
		$site_ip="";
		
		if(!is_array($dns_result))
			$dns_result=array();
		
		foreach($dns_result as $dns_rec){
			if($dns_rec['type']=='AAAA'){
				$ipv6_support=1;
				$ipv6=$dns_rec['ipv6'];
			}
			
			if($dns_rec['type']=='A'){
				$site_ip=$dns_rec['ip'];
			}
		}
		
		$response['is_ipv6_support']=$ipv6_support;
		$response['ipv6'] =$ipv6;
		$response['ip']= $site_ip;
		
		return $response;
		
	}


	public function html_text_ratio($general_content){
	
		$html_text_length=mb_strlen($general_content,'UTF-8');
		$text_conent=$this->strip_html_tags($general_content);
		$text_content_lenght=mb_strlen($text_conent,'UTF-8');
		$text_ratio= @($text_content_lenght/$html_text_length)*100;
		return $text_ratio;
		
	}
	
	
	
	public function get_all_info_content_curl($url){
	
			$ch = curl_init(); // initialize curl handle
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
            curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
            curl_setopt($ch, CURLOPT_POST, 0); // set POST method
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
            curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");
			
            $content = curl_exec($ch); // run the whole process
			$curl_info= curl_getinfo($ch);
			
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			
			$header = substr($content, 0, $header_size);
			
			$body = substr($content, $header_size);
			
			$response['curl_info']=$curl_info;
			$response['header']=$header;
			$response['content']=$content;
			
			return	$response;
	}
	
	
	
	public function ip_canonical_check($url){
		
		$url=addHttp($url);
		$url_parse= @parse_url($url);
  		$url_hostname=$url_parse['host'];
		$dns_result = @dns_get_record($url_hostname,DNS_A);	
		foreach($dns_result as $dns_rec){
			if($dns_rec['type']=='A'){
				$site_ip=$dns_rec['ip'];
			}
		}
				
		/*****For Canonicalization test of IP******/
		$ip_response=$this->get_all_info_content_curl($site_ip);
		$ip_url=$this->clean_domain_name($ip_response['curl_info']['url']);
		
		$ip_canonical=0;
		
		if($site_ip==$ip_url)
			$ip_canonical=0;
		else
			$ip_canonical=1;	
		
		$response['ip']=$site_ip;
		$response['ip_canonical']=$ip_canonical;
		
		return $response;
		
	}
	
	
	public function url_canonical_check($url){
	
		$general_content_information = $this->get_all_info_content_curl($url);
		
		$url_without_http=$this->clean_domain_name($url);
		
		$www_pos=strpos($url,'www.');
		
		if($www_pos!==FALSE)
			$canonicalization_test_url=$url_without_http;
		else
			$canonicalization_test_url="www.".$url_without_http;
	
		$canonicalization_test_url;		
		$canonicalization_content= $this->get_all_info_content_curl($canonicalization_test_url);
			
		if($general_content_information['curl_info']['url']== $canonicalization_content['curl_info']['url'])
			$url_canonicalization=1;
		else
			$url_canonicalization=0;
			
		return $url_canonicalization;
			
	}
	
	
	function get_gzip_response($url){
	
			$ch = curl_init(); // initialize curl handle
            curl_setopt($ch, CURLOPT_HEADER, TRUE);
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible;)");
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
            curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
            curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
            curl_setopt($ch, CURLOPT_POST, 0); // set POST method
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
            curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");
			curl_setopt($ch, CURLOPT_ENCODING , "gzip");
            $content = curl_exec($ch); // run the whole process
			$curl_info= curl_getinfo($ch);
			$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
			$header = substr($content, 0, $header_size);
			$body = substr($content, $header_size);
			$response['curl_info']=$curl_info;
			$response['header']=$header;
			return	$response;
			
		}	
		
		
		
		
	public function gzip_compression_check($url)
	{
		
		$gzip_response	= $this->get_gzip_response($url);
	
		$gzip_curl_info = $gzip_response['curl_info'];
		$gzip_header	= $gzip_response['header'];
		$gzip_header_array = explode("\r\n", $gzip_header);
		foreach ($gzip_header_array as $row){
			    $cutRow = explode(":", $row, 2);
			    $gzip_headers[$cutRow[0]] = isset($cutRow[1])? trim($cutRow[1]):"";
			}

				
		/****************************** Total Page Size in Normal Mode *****************************************/	
		$general_content_information=$this->get_all_info_content_curl($url);
		
		$total_page_size=$general_content_information['curl_info']['size_download']/1024; 
		
		$gzip_page_size=$gzip_curl_info['size_download']/1024; 
		
		/***If Gzip Support is enabled or not**/
		
		 $gzip_enable=0;
		 $gzip_headers['Content-Encoding']=isset($gzip_headers['Content-Encoding'])?$gzip_headers['Content-Encoding']:"";
		 
		 $gzip_pos=stripos($gzip_headers['Content-Encoding'], "gzip");
         if ($gzip_pos!==false) {
            $gzip_enable=1;
         }
		 
		 
		 $response=array();
		 
		 $response['is_gzip_enable']=$gzip_enable;
		 $response['gzip_page_size']=$gzip_page_size;
		 $response['normal_page_size']=$total_page_size;
		 
		 return $response;
		 
	}
		
	
	public function get_header_response($url)	{
		
		$response=$this->get_all_info_content_curl($url);
		
		return $response['header'];
		
		
	}
	
	/***	International domain name encoder 	***/
	
	public function puny_encoder($url){
		
		$url=addHttp($url);
		$parts = parse_url($url);
	    if (!isset($parts['host']))
	      return $url; 
   
	    if (mb_detect_encoding($parts['host']) != 'ASCII'  && function_exists("idn_to_ascii") ){
	      $parts['host'] = idn_to_ascii($parts['host']);
	      return $parts['scheme']."://".$parts['host'];
	    }
		
	    return $url;
	
	}
	
	
	
	public function punny_decoder($url){
		
	    if (function_exists("idn_to_utf8")){
			$url	= idn_to_utf8($url);
	    }
		
		return $url;
	}
		
		
	public function base_64_encode($string){
		return base64_encode($string);
	}
		
	public function base_64_decode($string){
		return base64_decode($string);
	}
	
	
	public function dns_information($url){
		$url=addHttp($url);
		$url_parse= @parse_url($url);
  		$url_hostname=$url_parse['host'];
		$dns_result = @dns_get_record($url_hostname,DNS_A + DNS_CNAME + DNS_MX + DNS_NS + DNS_AAAA + DNS_A6);	
		return $dns_result;
		
	}
	
	
	
	/****Get Youtube video data******/
	
	
	function youtube_video_curl($url){
		$ch = curl_init(); 
        curl_setopt($ch, CURLOPT_URL, $url); // set url to post to
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);// allow redirects
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_TIMEOUT, 50); // times out after 50s
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $content = curl_exec($ch); // run the whole process
		$results=json_decode($content,TRUE);
		return $results;
	}
	
	
	function get_youtube_video($keyword,$api_key=""){
		
		$all_video_result=array();
		
		$keyword=urlencode($keyword);
		
		
		$url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults=50";
		$results=$this->youtube_video_curl($url);
		
		$i=0;
		foreach($results['items'] as $r){
		
			if(isset($r['id']['videoId'])){
			
				$all_video_result[$i]['video_id']=$r['id']['videoId'];
				$all_video_result[$i]['published_at']=$r['snippet']['publishedAt'];
				$all_video_result[$i]['channel_id']=$r['snippet']['channelId'];
				$all_video_result[$i]['title']=$r['snippet']['title'];
				$all_video_result[$i]['description']=$r['snippet']['description'];
				$i++;		
			}	
		}
		
		
		for($page=0;$page<=2;$page++){
		
				$next_token_1=isset($results['nextPageToken'])? $results['nextPageToken']: "";
				
				if($next_token_1){
					$url="https://www.googleapis.com/youtube/v3/search?key={$api_key}&part=snippet,id&q={$keyword}&maxResults=50&pageToken={$next_token_1}";		
					$results=$this->youtube_video_curl($url);
					foreach($results['items'] as $r){
				
					if(isset($r['id']['videoId'])){
						$all_video_result[$i]['video_id']=$r['id']['videoId'];
						$all_video_result[$i]['published_at']=$r['snippet']['publishedAt'];
						$all_video_result[$i]['channel_id']=$r['snippet']['channelId'];
						$all_video_result[$i]['title']=$r['snippet']['title'];
						$all_video_result[$i]['description']=$r['snippet']['description'];
						$i++;		
					}	
				 }
					
			}
			
			else
				return $all_video_result;
		}
		
	return $all_video_result;
		
	}
	
	
	
	public function get_video_position($keyword,$video_id,$api_key="AIzaSyAnYjnemdEvwXbQhBJwk9muz7kDrDl2skw"){
	
		$all_video_info=$this->get_youtube_video($keyword,$api_key);
		
		$position=0;
		
		foreach($all_video_info as $index=>$value){
			if($value['video_id']=$video_id){
				$position=$index+1;
				break;
			}
				
		}
		
		 $response['position']=$position;
		 $response['all_video']=$all_video_info;
		 
		 return$response;
		
	}
	
	
	
	public function get_video_by_id($ids,$api_key){
		
		$url="https://www.googleapis.com/youtube/v3/videos?key={$api_key}&part=id,snippet,contentDetails,player,statistics,status&id={$ids}";
		$results=$this->youtube_video_curl($url);
		
		return $results;
		
	}
	
	
	public function get_vimeo_video_search($keyword,$access_token=""){
		$keyword=urlencode($keyword);
		
		$final_result=array();
		
		$url="https://api.vimeo.com/videos?query={$keyword}&access_token=f15c4e5b1862c1c00b259b7d8058f284&per_page=50&fields=uri,name,link,duration,embed,created_time,modified_time,privacy,modified_time,release_time,stats,metadata,user";
		
		$results=$this->youtube_video_curl($url);
		
		$final_result[]=$results['data'];
		
		for($i=2;$i<=5;$i++){
			
			if($results['paging']['next']){
				 $url="https://api.vimeo.com/videos?query={$keyword}&access_token=f15c4e5b1862c1c00b259b7d8058f284&per_page=50&fields=uri,name,link,duration,embed,created_time,modified_time,privacy,modified_time,release_time,stats,metadata,user&page={$i}";
				$results=$this->youtube_video_curl($url);
				$final_result[]=$results['data'];
			}
			else
				break;
			
		}
		
		
		return $final_result;
		
		
	}
	
	
	
	public function get_dailymotion_video_search($keyword){
	
		$keyword=urlencode($keyword);
		
		$final_result=array();
		
		$url="https://api.dailymotion.com/videos?search={$keyword}&fields=id,title,description,thumbnail_120_url,duration,views_total,created_time,likes_total,comments_total&sort=relevance&limit=50";
		
		$results=$this->youtube_video_curl($url);
		
		$final_result[]=$results['list'];
		
		for($i=2;$i<=5;$i++){
			
			if($results['has_more']){
				 $url="https://api.dailymotion.com/videos?search={$keyword}&fields=id,title,description,thumbnail_120_url,duration,views_total,created_time,likes_total,comments_total,embed_html,url&sort=relevance&limit=50&page={$i}";
				$results=$this->youtube_video_curl($url);
				$final_result[]=$results['list'];
			}
			else
				break;
			
		}
		
		
		
		return $final_result;
		
		
		
	}


	function virus_total_scan($api="",$domain="")
	{
	 	$url="https://www.virustotal.com/vtapi/v2/url/report?apikey=".$api."&resource=".$domain;
	 	$result=$this->get_content($url);
	 	return $result;
	}


	function get_blacklist_content($ip){
			
			$url="https://whatismyipaddress.com/blacklist-check";
			$post_value="LOOKUPADDRESS=".$ip;
			
			$ch = curl_init(); 
            curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (FM Scene 4.6.1)");
            curl_setopt($ch, CURLOPT_AUTOREFERER, false);
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
            curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_FAILONERROR, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
            curl_setopt($ch, CURLOPT_TIMEOUT, 120); 
            curl_setopt($ch, CURLOPT_POST, 1); 
            curl_setopt($ch, CURLOPT_POSTFIELDS, $post_value); 

           /**** Using proxy of public and private proxy both ****/
			if($this->proxy_ip!='')
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
		
			if($this->proxy_auth_pass!='')	
			curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
		 
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
            curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");
            
            $content = curl_exec($ch); // run the whole process
			
            curl_close($ch);
			
			return $content;
			
	}


	public function check_black_list($ip){
		
		$content = $this->get_blacklist_content($ip);
		
		if($content=="")
			return "Error";


		$blacklist_array=array();
		
		$html = new simple_html_dom();
		$html->load($content);
		$table_tr  = $html->find('table tr');
		$blacklist=array();
		$i=0;

			foreach($table_tr as $tr){
			
				$status_link= isset($tr->find('td img',0)->src)?$tr->find('td img',0)->src : "";
				
				if(isset($tr->find('td a',0)->href))
					 $domain_details_url="https://whatismyipaddress.com".$tr->find('td a',0)->href;
				else
					$domain_details_url="";
				
				$status_link="https://whatismyipaddress.com{$status_link}";
				
				$url_parts=@parse_url($status_link);
				@parse_str($url_parts['query'],$query);
				$domain_name= isset($query['bl'])?$query['bl']:"";
				
				if($domain_name){
					$blacklist_array[$i]['status_image_link']=$status_link;
					$blacklist_array[$i]['domain']=$domain_name;
					$blacklist_array[$i]['domain_details_url']=$domain_details_url;
					$i++;
				}
				
				
				
				$status_link=isset($tr->find('td img',1)->src) ? $tr->find('td img',1)->src : "";
				
				if(isset($tr->find('td a',1)->href))
					 $domain_details_url="https://whatismyipaddress.com".$tr->find('td a',1)->href;
				else
					$domain_details_url="";
				
				
				$status_link="https://whatismyipaddress.com{$status_link}";
				$url_parts=@parse_url($status_link);
				@parse_str($url_parts['query'],$query);
				$domain_name= isset($query['bl'])?$query['bl']:"";
				if($domain_name){
					$blacklist_array[$i]['status_image_link']=$status_link;
					$blacklist_array[$i]['domain']=$domain_name;
					$blacklist_array[$i]['domain_details_url']=$domain_details_url;
					$i++;
				}
				
			}

		return $blacklist_array;
		
	}

	function ip_traceout_content($ip){
			
		$url="https://whatismyipaddress.com/traceroute-tool";
		$post_value="LOOKUPADDRESS=".$ip;
		
		$ch = curl_init(); 
        curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows; U; Windows NT 5.1; de; rv:1.9.2.3) Gecko/20100401 Firefox/3.6.3 (FM Scene 4.6.1)");
        curl_setopt($ch, CURLOPT_AUTOREFERER, false);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 7);
        curl_setopt($ch, CURLOPT_REFERER, 'http://'.$url);
        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_FAILONERROR, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 120); 
        curl_setopt($ch, CURLOPT_POST, 1); 
        curl_setopt($ch, CURLOPT_POSTFIELDS, $post_value); 

       /**** Using proxy of public and private proxy both ****/
		if($this->proxy_ip!='')
		curl_setopt($ch, CURLOPT_PROXY, $this->proxy_ip);
	
		if($this->proxy_auth_pass!='')	
		curl_setopt($ch, CURLOPT_PROXYUSERPWD, $this->proxy_auth_pass);
	 
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_COOKIEJAR, "my_cookies.txt");
        curl_setopt($ch, CURLOPT_COOKIEFILE, "my_cookies.txt");
        
        $content = curl_exec($ch); // run the whole process
		
        curl_close($ch);
		
		return $content;
			
	}
	
	public function ip_traceout($ip){
	
		$content = $this->ip_traceout_content($ip);
		
		if($content=="")
			return "Error";
						
			
			$html = new simple_html_dom();
			$html->load($content);
			$table_tr  = $html->find('table[border="1"] tr');
			
			
			$trace_result=array();
			
			$i=0;
			
			foreach($table_tr as $tr){
			
				$trace_result[$i]['hop']=$tr->find('td',0)->plaintext;
				$trace_result[$i]['time']=$tr->find('td',1)->plaintext;
				$trace_result[$i]['host']=$tr->find('td',2)->plaintext;
				$trace_result[$i]['ip']=$tr->find('td',3)->plaintext;
				$trace_result[$i]['location']=$tr->find('td',4)->plaintext;
				$i++;
				
			}	
			
		return $trace_result;
		
			
		
	}
	
	
	

}


?>