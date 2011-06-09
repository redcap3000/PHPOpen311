<?php

// this class defines connection settings for API requests, For now the default data exists
// the idea is to allow developers to edit one or two config files to shape the library
// into using multiple api's, also another implementation (will do very soon) to allow a web
// based interface that will write these config files for the developer/end user
class api_connection_settings{

public static $_ = array(
	 'api_url' => '',
	 'method_name' => '',
	 'api_key' => '',
	 'city_id' => '',
	 'service_code' => '021',
	 'lat' =>'37.76524078',
	 'lon' => '-122.4212043',
	 'address_string' => '123 Some Street, San Francisco, CA 94114',
	 'customer_email' => 'john_q_public@gmail.com',
	 'device_id'=> 'se4H173nxaQsddl',
	 'account_id'=> '1234567890',
	 'first_name'=> 'John',
	 'last_name'=> 'Public',
	 'phone_number'=> '4151234567',
	 'description'=> 'There is a major pothole at this location.',
	 'media_url'=> 'http://sf.streetsblog.org/wp-content/uploads/2009/06_18/bike.route.pothole.jpg',
	 'service_request_id'=> ''
			);

}
