<?php
class AccessLog {
	
	public $accessID = '';
	public $ipAddress = '';
	public $ipv4 = 0;
	public $ipv6 = 0;
	public $rfc1413 = '';
	public $httpAuthUser = '';
	public $timestamp = 0;
	public $method = '';
	public $resource = '';
	public $protocol = '';
	public $httpStatus = 0;
	public $size = 0;
	public $referer = '';
	public $userAgent = '';
	
	public $error = false;
	public $errorMsg = '';
	
	// ----- Add Entry -----
	public function addEntry($matches){
		// Log parsing: https://httpd.apache.org/docs/2.4/logs.html

		// IP Address
		$ipv6 = 0;
		$ipv4 = 0;
		$ipAddress = $matches[1];
		if(strlen($ipAddress) > 15){
			$ipv6 = 1;
		}else{
			$ipv4 = 1;
		}
		
		// RFC 1413 Identity
		$rfc1413 = $matches[2];
		
		// HTTP Auth User
		$httpAuthUser = $matches[3];
		
		// Date and Time
		$dateExploded = explode('/', $matches[4]);
		$timestamp  = strtotime($dateExploded[1] . ' ' . $dateExploded[0] . ', ' . $dateExploded[2] . ' ' . $matches[5] . ' ' . $matches[6]);
		
		// Method
		$method = $matches[7];
		
		// Requested Resource
		$resource = $matches[8];
		
		// Protocol
		$protocol = $matches[9];
		
		// Status Code
		$httpStatus = $matches[10];
		
		// Response Size
		$size = $matches[11];
		
		// Referrer
		$referer = $matches[12];
		
		// User Agent
		$userAgent = $matches[13];
		
		// ----- Save to Database -----
		// Get UUID
		$uuid = new uuid();
		$id = $uuid->generate('access_log');
		
		// Connect to Database
		$db = new Database();
		
		// Prep for database
		$dbID = $db->escape($id);
		$dbIPAddress = $db->escape($ipAddress);
		$dbIPV4 = $db->escape($ipv4);
		$dbIPV6 = $db->escape($ipv6);
		$dbrfc1413 = $db->escape($rfc1413);
		$dbHttpAuthUser = $db->escape($httpAuthUser);
		$dbTimestamp = $db->escape($timestamp);
		$dbMethod = $db->escape($method);
		$dbResource = $db->escape($resource);
		$dbProtocol = $db->escape($protocol);
		$dbHttpStatus = $db->escape($httpStatus);
		$dbSize = $db->escape($size);
		$dbReferer = $db->escape($referer);
		$dbUserAgent = $db->escape($userAgent);
		
		// Insert Data
		$db->query("INSERT INTO access_log (id, ipAddress, ipv4, ipv6, rfc1413, httpAuthUser, timestamp, method, resource, protocol, httpStatus, size, referer, userAgent) VALUES ('$dbID', '$dbIPAddress', '$dbIPV4', '$dbIPV6', '$dbrfc1413', '$dbHttpAuthUser', '$dbTimestamp', '$dbMethod', '$dbResource', '$dbProtocol', '$dbHttpStatus', '$dbSize', '$dbReferer', '$dbUserAgent')");
	}
	
	// ----- IP Address Logs -----
	public function getAccessIDs($ipaddress){
		// Return
		$accessIDs = array();
		
		if(!empty($ipaddress)){
			// Prep for Database
			$db = new Database();
			$dbIPAddress = $db->escape($ipaddress);
			
			// Query
			$db->query("SELECT id FROM access_log WHERE ipAddress='$dbIPAddress' ORDER BY timestamp DESC");
			if(!$db->error){
				if($db->result->num_rows > 0){
					while($array = $db->resultArray()){
						$accessIDs[] = $array['id'];
					}
				}
			}
		}
		
		// Return
		return $accessIDs;
	}
	
	// ----- Validate -----
	public function validate($accessID, $saveToClass){
		// Return
		$valid = false;
		
		if(!empty($accessID)){
			// Prep for Database
			$db = new Database();
			$dbAccessID = $db->escape($accessID);
			
			// Query
			$query = "SELECT ipAddress, ipv4, ipv6, rfc1413, httpAuthUser, timestamp, method, resource, protocol, httpStatus, size, referer, userAgent FROM access_log WHERE id='$dbAccessID'";
			$db->query($query);
			if(!$db->error){
				if($db->result->num_rows == 1){
					// Valid ID
					$valid = true;
					
					if($saveToClass){
						// Parse Array
						$array = $db->resultArray();
						$this->ipAddress = $array['ipAddress'];
						$this->ipv4 = $array['ipv4'];
						$this->ipv6 = $array['ipv6'];
						$this->rfc1413 = $array['rfc1413'];
						$this->httpAuthUser = $array['httpAuthUser'];
						$this->timestamp = $array['timestamp'];
						$this->method = $array['method'];
						$this->resource = $array['resource'];
						$this->protocol = $array['protocol'];
						$this->httpStatus = $array['httpStatus'];
						$this->size = $array['size'];
						$this->referer = $array['referer'];
						$this->userAgent = $array['userAgent'];
					}
				}
			}
		}
		
		// Return
		return $valid;
	}
	
	// ----- HTTP Code Definitions -----
	public function httpCode($code){
		// Return
		$definition = '';
		
		if(!empty($code)){
			// Define Codes
			$httpCodes = array(
				100 => 'Continue',
				101 => 'Switching Protocols',
				102 => 'Processing',
				200 => 'OK',
				201 => 'Created',
				202 => 'Accepted',
				203 => 'Non-Authoritative Information',
				204 => 'No Content',
				205 => 'Reset Content',
				206 => 'Partial Content',
				207 => 'Multi-Status',
				300 => 'Multiple Choices',
				301 => 'Moved Permanently',
				302 => 'Found',
				303 => 'See Other',
				304 => 'Not Modified',
				305 => 'Use Proxy',
				306 => 'Switch Proxy',
				307 => 'Temporary Redirect',
				400 => 'Bad Request',
				401 => 'Unauthorized',
				402 => 'Payment Required',
				403 => 'Forbidden',
				404 => 'Not Found',
				405 => 'Method Not Allowed',
				406 => 'Not Acceptable',
				407 => 'Proxy Authentication Required',
				408 => 'Request Timeout',
				409 => 'Conflict',
				410 => 'Gone',
				411 => 'Length Required',
				412 => 'Precondition Failed',
				413 => 'Request Entity Too Large',
				414 => 'Request-URI Too Long',
				415 => 'Unsupported Media Type',
				416 => 'Requested Range Not Satisfiable',
				417 => 'Expectation Failed',
				418 => 'I\'m a teapot',
				422 => 'Unprocessable Entity',
				423 => 'Locked',
				424 => 'Failed Dependency',
				425 => 'Unordered Collection',
				426 => 'Upgrade Required',
				449 => 'Retry With',
				450 => 'Blocked by Windows Parental Controls',
				500 => 'Internal Server Error',
				501 => 'Not Implemented',
				502 => 'Bad Gateway',
				503 => 'Service Unavailable',
				504 => 'Gateway Timeout',
				505 => 'HTTP Version Not Supported',
				506 => 'Variant Also Negotiates',
				507 => 'Insufficient Storage',
				509 => 'Bandwidth Limit Exceeded',
				510 => 'Not Extended'
			);

			if(array_key_exists($code, $httpCodes)){
				$definition = $httpCodes[$code];
			}else{
				$definition = 'Unknown Code';
			}
		}
		
		// Return
		return $definition;
	}
}
?>