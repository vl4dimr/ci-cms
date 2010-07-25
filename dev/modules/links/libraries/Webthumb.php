<?php
if (!defined('BASEPATH')) exit('No direct script access allowed');

class Webthumb {
	var $_apikey;

	function Webthumb($apikey = "") {
		$this->_apikey = $apikey;
		$this->obj =& get_instance();
		$this->obj->load->helper('xml');
	}

	function setApi( $apikey ) {
		$this->_apikey = $apikey;
	}

	function requestThumbnail($url) {
		if(!$url) {
			$this->setErrMessage("Url not valid");
			Return false;
		}
		
		$_request = "
		<webthumb>
			<apikey>{$this->_apikey}</apikey>
			<request>
				<url>{$url}</url>
			</request>
		</webthumb>
			";

		if($_response = $this->_sendRequest($_request)) {
			$xmltoarray = xmlize($_response);
			
			if(isset($xmltoarray['webthumb']['#']['jobs']['0']['#']['job']['0']['#'])) {
				return $xmltoarray['webthumb']['#']['jobs']['0']['#']['job']['0']['#'];
			}
			else
			{
				return false;
			}
		}
		
	}

	function requestStatus($job) {
		$_request = "
		<webthumb>
			<apikey>{$this->_apikey}</apikey>
			<status>
				<job>{$job}</job>
			</status>
		</webthumb>
		";

		if($_response = $this->_sendRequest($_request)) {
			$xmltoarray = xmlize($_response);
			
			if( isset($xmltoarray['webthumb']['#']['jobStatus']['0']['#']['status']['0']['#']) ) {
				return $xmltoarray['webthumb']['#']['jobStatus']['0']['#']['status']['0']['#'];
			}
		}

	}

	function getThumbnail($job_id, $size = "medium")
	{
		$_request = "<webthumb>
							 <apikey>{$this->_apikey}</apikey>
							 <fetch>
								<job>{$job_id}</job>
								<size>{$size}</size>
							 </fetch>
						 </webthumb>";	

		$_response = $this->_sendRequest($_request);
		return $_response;
	}


	function _sendRequest($req) {


        $http_request  = "POST /api.php HTTP/1.0\r\n";
        $http_request .= "Host: webthumb.bluga.net\r\n";
        $http_request .= "Content-Type: application/x-www-form-urlencoded;\r\n";
        $http_request .= "Content-Length: " . strlen($req) . "\r\n";
        $http_request .= "User-Agent: ci-cms/PHP\r\n";
        $http_request .= "\r\n";
        $http_request .= $req;

        $response = '';
        if( false == ( $fs = @fsockopen('webthumb.bluga.net', '80', $errno, $errstr, 10) ) ) {
                die ('Could not open socket');
        }

        fwrite($fs, $http_request);

        while ( !feof($fs) )
                $response .= fgets($fs, 1160); // One TCP-IP packet
        fclose($fs);
        $response = explode("\r\n\r\n", $response, 2);

        return $response['1'];

	}
	
}

?>