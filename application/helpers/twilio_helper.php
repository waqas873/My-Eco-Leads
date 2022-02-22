<?php

/*
 * Twilio Messaging Helpers
 *
 */
require FCPATH.'vendor/twilio/sdk/src/Twilio/autoload.php';


function getICCList() {
	return array('AU'=>61, 'CA'=>'1', 'GB'=>'44', 'NZ'=>64, 'US'=>'1');
}

/* twilizePhoneNumber assumes a normalized phone number */
function twilizePhoneNumber($phone, $country='US') {
	$intl_codes = getICCList();

	$icc = isset($intl_codes[$country]) ? $intl_codes[$country] : '1';
	$phone = !empty($phone) ? '+'.$icc.$phone : '';

	return $phone;
}
function get_phone_carrier_and_type($phone) {
	$CI = get_instance();
	$CI->load->helper('twilio_loader');

	$service = get_twilio_service();

	try {
		$code = $service->phone_numbers->get($phone);
		$number = $service->phone_numbers->get($phone, array("CountryCode" => $code->country_code, "Type" => "carrier"));
		$mobile_carrier = ($number->carrier->name ? $number->carrier->name : 'unknown');
		$mobile_type = $number->carrier->type;
	}
	catch (Exception $e) {
		$mobile_carrier = 'unknown';
		$mobile_type = 'invalid';
		error_log('get phone carrier and type twilio error: Code: '.$e->getCode().' Message:'.$e->getMessage());
	}

	return array($mobile_carrier, $mobile_type);
}

function send_twilio_sms($sa_sid='', $to_phone, $from_phone, $message, $media=null, $client=null)
{
	$CI = get_instance();
	$client = $client ? $client : get_twilio_service();
	if($sa_sid){
	  $twilioAccount = $client->api
	    ->accounts($sa_sid)
	    ->fetch();
	  $sa_token = $twilioAccount->authToken;
	  $personTwilioService = new Twilio\Rest\Client($sa_sid, $sa_token);
	}
	else{
		$personTwilioService = $client;
	}
	$send_vars = array(
		'from' => $from_phone,
		'body' => $message,
	);
	if ($media) $send_vars['mediaUrl'] = $media;
	try {
  		$message = $personTwilioService->messages->create($to_phone, $send_vars);
		if ($message->sid) return $message->sid;
	}
	catch(Exception $e){
		if($e->getCode() == 21610){
			// $CI->load->model('contacts_model');
			// $CI->contacts_model->opt_out_phone($to_phone);
		}
		error_log('messaging_helper: send_sms: twilio error: '.$e->getMessage());
		last_error('twilio: send: '.$e->getMessage());
		return false;
	}
}

function send_twilio_sms_service($to_phone, $from_sid, $message, $media=null, $client=null)
{
	$CI = get_instance();
	$client = $client ? $client : get_twilio_service();

	$send_vars = array(
		'messagingServiceSid' => $from_sid,
		'body' => $message,
		);
	if ($media) $send_vars['mediaUrl'] = $media;
	try {
		$return = $client->messages->create($to_phone, $send_vars);
		return $return->sid;
	}
	catch(Exception $e){
		if($e->getCode() == 21610){
			$CI->load->model('contacts_model');
			$CI->contacts_model->opt_out_phone($to_phone);
		}
		error_log('messaging_helper: send_sms: twilio error: '.$e->getMessage());
		last_error('twilio: send: '.$e->getMessage());
		return false;
	}
}

  function count_twilio_sms($sa_sid, $renewal_date, $end_date=false)
  {
		try{
			$CI = get_instance();
	    $CI->load->helper('twilio_loader');
			$client = get_twilio_service();

      $twilioAccount = $client->api
        ->accounts($sa_sid)
        ->fetch();
		} catch (Exception $e) {
			error_log('fetch twilio sms account error: Code: '.$e->getCode().' Message:'.$e->getMessage());
      return false;
		}

		try{
      $sa_token = $twilioAccount->authToken;
      $sa_client = new Twilio\Rest\Client($sa_sid, $sa_token);

			$filters = array(
					"category" => "sms-outbound",
					"startDate" => $renewal_date
			);

			if($end_date) $filters['endDate'] = $end_date;

			$total = 0;
      $records = $sa_client->usage->records->read($filters);

      foreach ($records as $record) {
          $total = $total+$record->usage;
      }

			$filters['category'] ="mms-outbound";

      $records2 = $sa_client->usage->records->read($filters);
      foreach ($records2 as $record) {
        $mmsUpper = $record->usage*2;
        $total = $total+$mmsUpper;
      }
			$CI->session->set_userdata('twilio_count', $total);
			return $total;
		} catch (Exception $e) {
			//error_log('count twilio sms error: Code: '.$e->getCode().' Message:'.$e->getMessage());
      return false;
		}
  }

function get_total_sms_log_count($para,$type="inbound")
{
    $CI = get_instance();
    if (!$para['sa_sid']) {
        return false;
    }
    else
    {
        $sa_sid = $para['sa_sid'];
        $CI->load->helper('twilio_loader');
        $client = get_twilio_service();
        try{
            $paramss                                = array();
            $paramss['category']                    = "sms-$type";
            if($para['from_date']) $paramss['from'] = $para['from_date'];
            $total = 0;
            foreach ($client->usage->records->read($paramss) as $record)
            {
                $total  =  $record->count;
            }

            $paramss['category'] = "mms-$type";


            foreach ($client->usage->records->read($paramss) as $record)
            {
                $total  =  $total+$record->count;
            }

            return  $total;

        } catch (Exception $e){
            error_log('get twilio sms history error: Code: '.$e->getCode().' Message:'.$e->getMessage());
            return false;
        }
    }
}


function get_sms_paginate_inbound_log($para){
    $CI = get_instance();

    $get_data = $CI->input->get();
    $sa_sid = $para['sa_sid'];
    if (!$sa_sid) {
        return false;
    } else {
        $CI->load->helper('twilio_loader');
        $client = get_twilio_service();

        try{
            $paramss                                = array();
            if($para['from_date']) $paramss['dateSent>='] = $para['from_date'];

            $paramss['to']       = trim($para['from']);//$para['from'];  //+15169862378

            $pagination_session = $CI->session->userdata('twilio_pagination');

            $response           = array();
            $set_session        = array();
            if(!isset($pagination_session) || $pagination_session=="" )
            {
                $result =  $client->messages->stream($paramss,200,200);
                $set_session['next_page_uri']     = $result->page->getNextPageUrl();
                $set_session['previous_page_uri'] = $result->page->getPreviousPageUrl();
                $set_session['total_records']     = get_total_sms_log_count($para,"inbound");
                $set_session['start']             = $get_data['start'];
                $response['sms_log']              = $result;
                //$response['active_page']          = 1;
                $response['total_records']        = $set_session['total_records'];
            }
            else
            {
                if($pagination_session['start'] <  $get_data['start'])
                {
                    $result    = $client->messages->getPage($pagination_session['next_page_uri']);
                }
                else
                {
                    $result   = $client->messages->getPage($pagination_session['previous_page_uri']);
                }
                $set_session['next_page_uri']     = $result->getNextPageUrl();
                $set_session['previous_page_uri'] = $result->getPreviousPageUrl();
                $set_session['total_records']     =  $pagination_session['total_records'];
                $set_session['start']             = $get_data['start'];
                $response['sms_log']              = $result;
                //$response['active_page']          = getCurrentTwilioPageNum($result->getNextPageUrl());
                $response['total_records']        = $pagination_session['total_records'];
            }
            $CI->session->set_userdata('twilio_pagination',$set_session);
            return $response;
        } catch (Exception $e) {

            error_log('get twilio sms history error: Code: '.$e->getCode().' Message:'.$e->getMessage());
            return false;
        }
    }
    return false;
}

function get_sms_paginate_outbound_log($para){
    $CI = get_instance();

    $get_data = $CI->input->get();
    $sa_sid = $para['sa_sid'];
    if (!$sa_sid) {
        return false;
    } else {
        $CI->load->helper('twilio_loader');
        $client = get_twilio_service();

        try{
            $paramss                                = array();
            if($para['from_date']) $paramss['dateSent>='] = $para['from_date'];

            $paramss['from']       = trim($para['from']);//$para['from'];  //+15169862378

            $pagination_session = $CI->session->userdata('twilio_outbound_pagination');

            $response           = array();
            $set_session        = array();
            if(!isset($pagination_session) || $pagination_session=="" )
            {
                $result =  $client->messages->stream($paramss,200,200);
                $set_session['next_page_uri']     = $result->page->getNextPageUrl();
                $set_session['previous_page_uri'] = $result->page->getPreviousPageUrl();
                $set_session['total_records']     = get_total_sms_log_count($para,"outbound");
                $set_session['start']             = $get_data['start'];
                $response['sms_log']              = $result;
                //$response['active_page']          = 1;
                $response['total_records']        = $set_session['total_records'];
            }
            else
            {
                if($pagination_session['start'] <  $get_data['start'])
                {
                    $result    = $client->messages->getPage($pagination_session['next_page_uri']);
                }
                else
                {
                    $result   = $client->messages->getPage($pagination_session['previous_page_uri']);
                }
                $set_session['next_page_uri']     = $result->getNextPageUrl();
                $set_session['previous_page_uri'] = $result->getPreviousPageUrl();
                $set_session['total_records']     =  $pagination_session['total_records'];
                $set_session['start']             = $get_data['start'];
                $response['sms_log']              = $result;
                //$response['active_page']          = getCurrentTwilioPageNum($result->getNextPageUrl());
                $response['total_records']        = $pagination_session['total_records'];
            }
            $CI->session->set_userdata('twilio_outbound_pagination',$set_session);
            return $response;
        } catch (Exception $e) {

            error_log('get twilio sms history error: Code: '.$e->getCode().' Message:'.$e->getMessage());
            return false;
        }
    }
    return false;
}

function smart_wordwrap($string, $width = 75, $break = "\n") {
    // split on problem words over the line length
    $pattern = sprintf('/([^ ]{%d,})/', $width);
    $output = '';
    $words = preg_split($pattern, $string, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);

    foreach ($words as $word) {
        if (false !== strpos($word, ' ')) {
            // normal behaviour, rebuild the string
            $output .= $word;
        } else {
            // work out how many characters would be on the current line
            $wrapped = explode($break, wordwrap($output, $width, $break));
            $count = $width - (strlen(end($wrapped)) % $width);

            // fill the current line and add a break
            $output .= substr($word, 0, $count) . $break;

            // wrap any remaining characters from the problem word
            $output .= wordwrap(substr($word, $count), $width, $break, true);
        }
    }

    // wrap the final output
    return wordwrap($output, $width, $break);
}

function getCurrentTwilioPageNum($url)
{
    $UrlPara = parse_url($url);
    $QueryString = explode('&',$UrlPara['query']);
    foreach ($QueryString as $val)
    {
        $checkIndex = explode('=',$val);
        if($checkIndex[0] == 'Page')
            $CurrentPage = trim($checkIndex[1]);
    }
    $CurrentPage=$CurrentPage - 1;
    return $CurrentPage;
}
/*
 * Twilio Accounts Helpers
 *
 */

  function get_update_twilio_accounts($sa_sid){
    $CI = get_instance();

    if (!$sa_sid) {
      return false;
    } else {
      $CI->load->helper('twilio_loader');
      $client = get_twilio_service();
      try{
        $tw_account = $client->api
          ->accounts($sa_sid)
          ->fetch();
      } catch (Exception $e) {
				error_log('get twilio account error: Code: '.$e->getCode().' Message:'.$e->getMessage());
	      return false;
      }
      try{
        $sid2 = $tw_account->sid;
        $token2 = $tw_account->authToken;
        $client2 = new Twilio\Rest\Client($sid2, $token2);
        $counter=0;
        // Loop over the list of numbers and echo a property for each one
        foreach ($client2->account->incoming_phone_numbers as $number) {
          $counter++;
          $number->update(array(
              "VoiceUrl" => "https://textinchurch.com/listener/phParser",
              "SmsUrl" => "https://textinchurch.com/listener/moParser"
          ));
        }
        return $counter;
      } catch (Exception $e) {
				error_log('update twilio account error: Code: '.$e->getCode().' Message:'.$e->getMessage());
	      return false;
      }
    }
    return false;
  }

  function update_twilio_phone_numbers($sa_sid, $sms_url=null, $voice_url=null){
      $CI = get_instance();
      if (!$sa_sid) {
          return false;
      } else {
          $CI->load->helper('twilio_loader');
          $client = get_twilio_service();
          try{
              $tw_account = $client->api
                  ->accounts($sa_sid)
                  ->fetch();
          } catch (Exception $e) {
              error_log('get twilio account error: Code: '.$e->getCode().' Message:'.$e->getMessage());
              return false;
          }
          try{
              $sid2 = $tw_account->sid;
              $token2 = $tw_account->authToken;
              $client2 = new Twilio\Rest\Client($sid2, $token2);
              $counter = 0;
              $incomingPhoneNumbers = $client2->incomingPhoneNumbers->read();
              // Loop over the list of numbers and echo a property for each one
              foreach ($incomingPhoneNumbers as $number) {
                  $counter++;
                  $data = array();
                  if ($sms_url) $data['smsUrl'] = $sms_url;
                  if ($voice_url) $data['voiceUrl'] = $voice_url;
                  $number->update($data);
              }
              return $counter;
          } catch (Exception $e) {
              error_log('update twilio account error: Code: '.$e->getCode().' Message:'.$e->getMessage());
              return false;
          }
      }
      return false;
  }

  function update_twilio_phone_number($psid , $update)
  {
      $CI = get_instance();
      $CI->load->helper('twilio_loader');
      $client = get_twilio_service();
      try{
          $number = $client->incomingPhoneNumbers($psid)->update($update);
      } catch (Exception $e) {
          error_log('update twilio account error: Code: '.$e->getCode().' Message:'.$e->getMessage());
          return false;
      }
  }


  /*
   * Twilio Phone Number Helpers
   *
   */

  function get_phone_numbers($region, $params)
  {
		try{
			$client = get_twilio_service();
			// $numbers = $client->availablePhoneNumbers($region)->local->read(
			//     $params
			// );
      $numbers = $client->availablePhoneNumbers($region)->mobile->read(
          [],20
      );

			$data = array();
			// foreach ($numbers as $number) {
			// 	$data[] = formatPhoneNumber($number->phoneNumber, $region);
			// }
      $data = array();
			return $numbers;
		} catch (Exception $e) {
			error_log('get numbers from twilio error: Code: '.$e->getCode().' Message:'.$e->getMessage());
			return false;
		}
  }

  function connectNumberFromTwilio($name='', $phone_number, $account_phone="")
  {
    $CI = get_instance();
    $CI->load->helper('twilio_loader');
	  $client = get_twilio_service();
    try{
      $base_url = base_url();
      $urlBase = parse_url($base_url, PHP_URL_SCHEME).'://'.parse_url($base_url, PHP_URL_HOST).'/';

      $service = $client->messaging->v1->services
         ->create($name,
          array(
              "inboundRequestUrl" => base_url().'listener/callback_url',
							  "fallbackUrl" => base_url().'listener/callback_url',
							  "areaCodeGeomatch" => true,
							  "fallbackToLongCode" => true,
							  "mmsConverter" => true,
							  "smartEncoding" => true,
							  "stickySender" => true,
							  "statusCallback" => base_url().'listener/set_status'
              ));
                      
    //debug($service);     
   //  if($service->sid){
		 //  return array('pn_sid'=>NULL, 's_sid'=>$service->sid);
	  // } else{
		 //  return false;
	  // }
                                          
                                        
        // test this one in preminum account
        //https://www.twilio.com/blog/incomingphonenumbers-api-change-documentation
        //This api end point is changed and needed bundled sid to work
      $incoming_phone_number = $client->incomingPhoneNumbers->create( array(
          "friendlyName" => $name,
          "phoneNumber" => $phone_number,
          "bundleSid" => BUNDLE_SID,
		      //"voiceUrl" => "http://twimlets.com/forward?PhoneNumber=+16468937192"
      ));


	  if($incoming_phone_number->sid && $service->sid){
		  $phone_number = $client->messaging->v1->services($service->sid)
		  ->phoneNumbers->create($incoming_phone_number->sid);
		  // echo "<pre>";
		  // print_r($phone_number);
		  // print_r($service);
		  // exit;
		  $response = array('pn_sid'=>$phone_number->sid, 's_sid'=>$service->sid);
      debug($response,true);
      return $response;
	  } else{
		  return false;
	  }
      return false;
    }
    catch (Exception $e) {
     error_log('connect number from twilio error: Code: '.$e->getCode().' Message:'.$e->getMessage());
     return false;
   }
  }

  function get_bundle()
  {
    $CI = get_instance();
    $CI->load->helper('twilio_loader');
    $client = get_twilio_service();
    //debug($client,true);

    $bundle = $client->numbers->v2->regulatoryCompliance
      ->bundles
      ->create("wlc, Local Bundle", // friendlyName
               "andy@webleadscompany.com", // email
               [
                   "endUserType" => "Individual",
                   "isoCountry" => "GB",
                   "numberType" => "local",
                   "statusCallback" => base_url('listener/callback_url')
               ]
      );
      print($bundle->sid);
      debug($bundle);
  }

function release_twilio_number($sid, $serviceSID){
	$CI = get_instance();
	$CI->load->helper('twilio_loader');
	$client = get_twilio_service();

	try{
		$client->incomingPhoneNumbers($sid)->delete();
		$client->messaging->v1->services($serviceSID)->delete();
		return true;
	} catch (Exception $e) {
		error_log('release number from twilio error: Code: '.$e->getCode().' Message:'.$e->getMessage());
		return false;
	}
}

?>
