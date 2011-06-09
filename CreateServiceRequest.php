<?php

/**
 * 
 * Copyright 2010 Mark J. Headd
 * http://www.voiceingov.org
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
 * GNU Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public License
 * along with PHPOpen311.  If not, see <http://www.gnu.org/licenses/>.
 *  
 */

/**
 * Useage example: Create a new 311 service request.
 */

// Include the Open 311 classes.
include('classes/PHPOpen311.php');

define("BASE_URL", "");
define("API_KEY", "");
define("CITY_ID", "");

// Service request information.

try {
	
	// Create a new instance of the Open 311 class.
	$open311 = new Open311(BASE_URL, API_KEY, CITY_ID);
	
	// Create a new 311 Service request.
	// load the api_connection config class to make this call cleaner and more expandable
	// let the createRequest deal with variable assignment from an array
	if(!class_exists('api_connection_settings')) include('classes/api_request_config.php');
	
	$open311->createRequest(api_connection_settings::$_);	
							
	$createRequestXML = new SimpleXMLElement($open311->getOutput());
	
	// Check to see if an error code and message were returned.
	if(strlen($createRequestXML->Open311Error->errorCode) > 0) {
		throw new create_requestException("API Error message returned: ".$createRequestXML->Open311Error->errorDescription);
	}
	
	// Display the ID of the service request.
	echo "Service Request ID: ".$createRequestXML->Open311Create->service_request_id;
}

catch (create_requestException $ex) {
	die("ERROR: ".$ex->getMessage());
}

catch (Exception $ex) {
	die("Sorry, a problem occured: ".$ex->getMessage());
}

?>