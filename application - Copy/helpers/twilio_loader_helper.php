<?php

if (!class_exists('Client')) {
	/**
	 * The main Twilio.php file contains an autoload method for its dependent
	 * classes, we only need to include the one file manually.
	 */
	include_once(APPPATH.'../vendor/autoload.php');
}

/**
 * Return a twilio services object.
 *
 * Since we don't want to create multiple connection objects we
 * will return the same object during a single page load
 *
 * @return object Services_Twilio
 */
function get_twilio_service() {
	static $twilio_service;

	if (!($twilio_service instanceof Client)) {
		$twilio_service = new Twilio\Rest\Client(TWILIO_SID, AUTH_TOKEN);
		//$twilio_service = new Twilio\Rest\Client('ACdcb321fad468676cf6db19fb4acb10dd', 'fdd8a0e7baa6164101881764ce501eba');
	}

	return $twilio_service;
}

function get_flex_twilio_service() {
	static $twilio_service;

	if (!($twilio_service instanceof Client)) {
		$twilio_service = new Twilio\Rest\Client(getenv('FLEXSID'), getenv('FLEXTOKEN'));
	}

	return $twilio_service;
}

?>
