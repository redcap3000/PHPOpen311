<?php

/**
 * 
 * Copyright 2010 Mark J. Headd
 * http://www.voiceingov.org
 * 
 * PHPOpen311: A set of PHP classes for working with the Open311 API (http://open311.org/)
 *
 * This file is part of PHPOpen311
 * 
 * PHPOpen311 is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PHPOpen311 is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with PHPOpen311.  If not, see <http://www.gnu.org/licenses/>.
 *  
 */


/**
 * The base class for Open311 API calls.
 *
 */
class APIBaseClass {
 
	// API base URL.
	protected $base_url;
	 
	// cURL handle used to make API request.
	protected $ch;
	 
	// API output and info.
	protected $output;
	protected $info;
	 
	/**
	 * Class constructor.
	 * @param string $base_url
	 * @return null
	 */
	protected function __construct($base_url) {
		$this->base_url = $base_url;
		$this->ch = curl_init();
	}
	
	/**
	 * Get the output of a cURL request.
	 * @return string
	 * 
	 */
	public function getOutput() {
		return $this->output;
	}
	
	/**
	 * Get information about the last cURL request made.
	 * @return string
	 */
	public function getInfo() {
		return $this->info;
	}
	 
	/**
	 * Method to log API request outcome.
	 * Can be overriden in derived classes for custom logging.
	 * @param string $logFile
	 * @param string $message
	 * @return null
	 */
	protected function logResults($logFile, $message) {
		$loghandle = fopen($logFile, 'a');
		fwrite($loghandle, date("F j, Y, g:i a").": API response from ".$this->base_url." = ".$message."\n");
		fclose($loghandle);
	}
	 
	/**
	 * Class destructor.
	 *
	 */
	protected function __destruct() {
		curl_close($this->ch);
	}
}

/**
 * The main Open311 class.
 *
 */
class Open311 extends APIBaseClass {

	// Private class members.
	
	/**
	 * Open311 Class Constructor.
	 * @param $base_url
	 * @param $api_key
	 * @param $city_id
	 * @return null
	 */
	public function __construct($base_url, $api_key, $city_id) {
	// this need to be dynamic as well specifically the 'city_id' param
		$this->api_key = $api_key;
		$this->city_id = $city_id;
		parent::__construct($base_url);
	}
	
	/**
	 * Get a list of 311 Service Types.
	 * @return string
	 */
	public function selectService(){
		
		// Set URL for API method.
		$this->method_name = 'service_list';
		
		// Make the HTTP request to the API.
		$this->makeAPIRequest();
		
	}
	
	/**
	 * Create a new 311 Service Request.
	 * @param string $service_code
	 * @param string $lat
	 * @param string $lon
	 * @param string $address_string 
	 * @param string $customer_email
	 * @param string $device_id 
	 * @param string $account_id 
	 * @param string $first_name
	 * @param string $last_name
	 * @param string $phone_number
	 * @param string $description 
	 * @param string $media_url
	 * @return string
	 */
	 
	 
//	public function createRequest($service_code, $lat=NULL, $lon=NULL, $address_string=NULL, $customer_email=NULL, $device_id=NULL, 
//				      			  $account_id=NULL, $first_name=NULL, $last_name=NULL, $phone_number=NULL, $description=NULL, $media_url=NULL) 
	 
	 
	 private static $special_settings = array(
	 	'address_string' => 'url',
	 	'description' => 'url'
	 );
	 // keeps track of any specific processing if needed, the key refers to the setting, while the value refers to the class of setting
	 // to be handled in a specific way when encountered
	public function createRequest($connection_settings) {
		
		
		// this can be replaced with the code ...
		
		
		if((empty($connection_settings['lat']) && empty($connection_settings['lat']) && strlen(empty($connection_settings['lat']))) == 0) {
			throw new create_requestException('Must submit lat/lon or address string with create service request.');
		}
		
		foreach($connection_settings as $name=>$setting){
			// ideally use another array to define the special settings themselves
			if(array_key_exists($name,self::$special_settings) && self::$special_settings[$name] == 'url')
			// process special setting, for now only 'url'
				$this->$name = urlencode($setting);
			else
				$this->$name = $setting;
		
		}
		
		
		// Set URL for API method.
		$this->method_name = 'create_request';
		
		// Make the HTTP request to the API.
		$this->makeAPIRequest();
	}
	
	/**
	 * Get the status of a specific 311 Service Request.
	 * @param int $service_request_id
	 * @return string
	 */
	public function statusUpdate($service_request_id) {
		
		$this->service_request_id = $service_request_id;
		
		// Set URL for API method.
		$this->method_name = 'status_update';
		
		// Make the HTTP request to the API.
		$this->makeAPIRequest();
	}
	
	/**
	 * Helper method to make the cURL request to the Open 311 API.
	 */
	private function makeAPIRequest() {
		// this function will need to be abstracted for use with other apis. Will probably be able to use the api_request_config file to 
		// populate these requests, and create an easy to understand language to use to define the api requests
		$request_url = $this->base_url.$this->method_name.'?api_key='.$this->api_key.'&city_id='.$this->city_id;
		
		switch($this->method_name) {
			
			case 'service_list':				
				break;

			case 'create_request':
				$request_url .= "&service_code=$this->service_code&lat=$this->lat&lon=$this->lon";
				$request_url .= "&address_string=$this->address_string&customer_email=$this->customer_email";
				$request_url .= "&device_id=$this->device_id&account_id=$this->account_id&first_name=$this->first_name";
				$request_url .= "&last_name=$this->last_name&phone_number=$this->phone_number&description=$this->description";
				$request_url .= "&media_url=$this->media_url";
				break;
				
			case 'status_update':
				$request_url .= "&service_request_id=$this->service_request_id";
				break;
		}
		
		// Set options for all cURL requests
		curl_setopt($this->ch, CURLOPT_URL, $request_url);
		curl_setopt($this->ch, CURLOPT_RETURNTRANSFER, true);	
		
		// Make cURL request and get response.
		$this->output = curl_exec($this->ch);
		$this->info = curl_getinfo($this->ch);
		
		if($this->info['http_code'] != '200') {
			$className = $this->method_name.'Exception';
			throw new $className("HTTP Code returned was: ".$this->info['http_code']);
		}
	}

	/**
	 * Open311 Class Destructor.
	 */
	public function __destruct() {
		parent::__destruct();
	}
}

/**
 * A class for 311 Service Types.
 *
 */
class ServiceType {
	
	private $service_code;
	private $service_name;
	private $service_description;
	
	/**
	 * Classd constructor
	 *
	 * @param int $service_code
	 * @param string $service_name
	 * @param string $service_description
	 */
	public function __construct($service_code, $service_name, $service_description) {
		$this->service_code = $service_code;
		$this->service_name = $service_name;
		$this->service_description = $service_description;
	}
}

/**
 * A class for Service List Exceptions.
 *
 */
class service_listException extends Exception {}

/**
 * A class for Service Creation Exceptions.
 *
 */
class create_requestException extends Exception {}

/**
 * A class for Status Update Exceptions.
 *
 */
class status_updateException extends Exception {}

?>
