<?php
class cWhois {
	var $domainname;
	var $whoisContent;
	var $detailWhois = true;
	var $error = array();
	var $debug = false;
	var $whoisServer = '';
	var $defaultTimeZone;
	
	function __construct($domain = ''){
		return $this->cWhois($domain);
	}
	
	function cWhois($domain){
		$this->error_reporting_status = error_reporting();
		error_reporting($this->error_reporting_status & ~E_WARNING);
		if(!empty($domain)){
			$this->setDomain($domain);
			return $this->whois();
		}
	}
	
	function setDomain($domain){
		$domain = $this->cleanDomain($domain);
		if(!empty($domain)){
			$this->domainname = $domain;
			return true;
		}
		else{
			return false;
		}
	}
	
	function setError($errorMsg){
		$this->error[] = $errorMsg;
		return false;
	}
	
	function getError(){
		return $this->error;
	}
	
	function cleanDomain($domain){
		$domain = str_replace(array('https://', 'http://', 'www.', '//', ' '), '', $domain);
		$domain = trim($domain);
		return $domain;
	}
	
	function getTld(){
		$domain = explode('.', $this->domainname);
		unset($domain[0]);
		$tld = implode('.', $domain);
		return $tld;	
	}

	function whois(){
		$domain = $this->domainname;
		
		if(empty($domain)){
			return $this->setError('Domain tanimlanmamis.');
		}
		
		$this->whoisServer = $this->getWhoisServer($this->getTld($domain));

		if($this->whoisServer == 'whois.iana.org' or $this->whoisServer == ''){
			$response = $this->getSocketResult($domain, $this->whoisServer);

			if (preg_match('/whois:[\s]{1,}(.*?)\n/sim', $response, $regs)) {
				$this->whoisServer = $regs[1];
			}
			else {
				return $response;
			}
		}

		$response = $this->getSocketResult($domain, $this->whoisServer);
		
		$lastWhoisServer = $this->parseWhoisServer($this->parseWhois(strtolower($response)));
		if($this->whoisServer != $lastWhoisServer and $lastWhoisServer != ''){
			$this->whoisServer = $lastWhoisServer;
			$response = $this->getSocketResult($domain, $lastWhoisServer);
		}

		if($this->detailWhois){
			$parseWhois = $this->parseWhois($response);
			if(isset($parseWhois['Whois Server'])){
				$this->whoisServer = $parseWhois['Whois Server'];
				$response = $this->getSocketResult($domain, $this->whoisServer);
			}
		}
		
		if($response != false){
			$this->whoisContent = $response;
		}

		return $response;
	}

	function getSocketResult($domain, $server){
		$reConCount = 2;	//-> ReConnection count.
		while($reConCount > 0){
			if(!$con = fsockopen($server, 43, $errno, $errstr, 3)){
				fclose($con);
				$this->setError("$server not connected!");
				if($reConCount == 1){
					return "$server not connected!";
				}
			}
			else{
				break;
			}
			$reConCount--;
			sleep(15);
		}
		fputs($con, $domain."\n");

		$response = "";
		while(!feof($con)){
			$response .= fgets($con, 128);
		}
		$response = preg_replace("/%.*\n/", "", $response);
		fclose($con);
		
		return $response;
	}

	function parseWhoisServer($data){
		$whoisServer = '';
		if(is_array($data)){
			foreach ($data as $k=>$v){
				if(strstr($k, 'whois server')){
					$whoisServer = $v;
					break;
				}
			}
		}
		else if(is_string($data)){
			preg_match_all('/whois server: (.*?)\n/sim', $data, $result, PREG_PATTERN_ORDER);
			$whoisServer = $result[1][0];
		}
		return $whoisServer;
	}

	function parseWhois($whoisText = ''){
		if(empty($whoisText)){
			$whoisText = $this->whoisContent;
		}
	
		if(is_string($whoisText)){
			$whoisText = str_replace(array("\r", "<<<", ">>>"), "", $whoisText);
			$rows = explode("\n", $whoisText);
			$parsed = array();
			foreach($rows as $row){
				if((strstr($row, '://') and substr_count($row, ":") > 1) or (!strstr($row, '://') and substr_count($row, ":") > 0)){
					if (strlen($row) <= 100 and preg_match('/[\s]{0,}(.*?)[\s]{0,}:(.*?)$/sim', $row, $result)) {
						$key = trim($result[1]);
						$val = trim($result[2]);
						
						if(!isset($parsed[$key])){
							$parsed[$key] = $val;
						}
						else{
							if(!is_array($parsed[$key])){
								$oldValue = $parsed[$key];
								$parsed[$key] = array();
								$parsed[$key][] = $oldValue;
								unset($oldValue);
								$parsed[$key][] = $val;
							}
							else{
								if($val != ''){
									$parsed[$key][] = $val;					
								}
							}
						}
					}
				}
			}
			
			return $parsed;
		}
		else{
			return false;
		}
	}
	
	function __destruct(){
		if($this->debug){
			if(count($this->error) > 0){
				echo "ERROR:\n<pre>" . var_export($this->error, true);
			}
		}
		error_reporting($this->error_reporting_status);
	}
	
	function getWhoisServer($tld){
		$whoisServers = array(
			'com.tr' => 'whois.metu.edu.tr',
			'com.tr' => 'tr.whois-server.net',
			'com'  => 'whois.crsnic.net',
			'net'  => 'whois.crsnic.net',
			'org'  => 'whois.publicinterestregistry.net',
			'info' => 'whois.afilias.net',
			'name' => 'whois.nic.name',
			'eu'   => 'whois.nic.biz',
			'lt'   => 'whois.domreg.lt',
			'eu'   => 'whois.eu'
		);

		if(isset($whoisServers[$tld])){
			return $whoisServers[$tld];
		}
		else{
			return 'whois.iana.org';
		}
	}
	
	function getRegistrar(){
		if(!empty($this->whoisContent)){
			preg_match_all('/Registrar:\s(.*?)\n/sx', $this->whoisContent, $result, PREG_PATTERN_ORDER);
			return trim($result[1][0]);
		}
		else{
			return false;
		}
	}
	
	function getDate($dateType = 'expire'){
		$arrayWhois = $this->parseWhois();
		$result = false;
		if($dateType == 'expire' and isset($arrayWhois['Registrar Registration Expiration Date'])){
			$result = $arrayWhois['Registrar Registration Expiration Date'];
		}
		else if($dateType == 'create' and isset($arrayWhois['Creation Date'])){
			$result = $arrayWhois['Creation Date'];
		}
		else if($dateType == 'update' and isset($arrayWhois['Updated Date'])){
			$result = $arrayWhois['Updated Date'];
		}
		return $result;
	}
	
	function getUnixTime($dateType = 'expire'){
		$this->defaultTimeZone = date_default_timezone_get();
		date_default_timezone_set('Europe/Istanbul');
		
		$date = $this->getDate($dateType);
		if($date != false){
			$result = strtotime(trim(str_replace(array('T', 'Z'), ' ', $date)));
		}
		else{
			$result = false;
		}
		date_default_timezone_set($this->defaultTimeZone);
		return $result;
	}
	
	function getWhoisProp($properties){
		$arrayWhois = $this->parseWhois();
		$result = false;
		if(isset($arrayWhois[$properties])){
			$result = $arrayWhois[$properties];
		}
		return $result;
	}
}