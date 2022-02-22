<?php
require_once dirname(__FILE__, 3).'/vendor/twilio/sdk/src/Twilio/autoload.php';
/**
 * Return a twilio services object.
 *
 * Since we don't want to create multiple connection objects we
 * will return the same object during a single page load
 *
 * @return object Services_Twilio
 */
function get_twilio_service()
{
    static $twilio_service;

    if (!($twilio_service instanceof Client)) {
        //$twilio_service = new Twilio\Rest\Client(getenv('TWILIO_SID'), getenv('TWILIO_TOKEN'));
        //$twilio_service = new Twilio\Rest\Client(TWILIO_SID, AUTH_TOKEN);
        $twilio_service = new Twilio\Rest\Client("ACdcb321fad468676cf6db19fb4acb10dd", "fdd8a0e7baa6164101881764ce501eba");
    }
    return $twilio_service;
}

function get_phone_carrier_and_type($phone)
{
    $service = get_twilio_service();
        // check mobile number
        try {
            $number = $service->lookups
                                ->phoneNumbers($phone)
                                ->fetch(
                                        array("type" => "carrier")
                                );
            $mobile_carrier = ($number->carrier["name"] ? $number->carrier["name"] : 'unknown');
            $mobile_type = $number->carrier["type"]?$number->carrier["type"]:'';
        } catch (Exception $e) {
            $mobile_carrier = 'unknown';
            $mobile_type = 'invalid';
            error_log('get phone carrier and type twilio error: Code: '.$e->getCode().' Message:'.$e->getMessage());
        }

    return array($mobile_carrier, $mobile_type);
}

function update_twilio_subaccount($account_sid, $status="suspended")
{
    $client = get_twilio_service();
    try {
        $twilioAccount = $client->api->accounts($account_sid)->update(array(
                 'Status' => $status,
         ));
        return $twilioAccount ? $twilioAccount : false;
    } catch (Exception $e) {
        return false;
    }
}

function send_twilio_sms($to_phone, $from_phone, $message, $media=null, $rcp_id=null)
{
    $personTwilioService = get_twilio_service();
    $send_vars = array(
     'from' => $from_phone,
     'body' => $message,
     );
    if ($media) {
        $send_vars['mediaUrl'] = $media;
    }
    try {
        $message = $personTwilioService->messages->create($to_phone, $send_vars);
        if ($message->sid) {
            return $message->sid;
        }
    } catch (Exception $e) {
        if (strpos($e->getMessage(), 'is not a valid phone number') !== false || strpos($e->getMessage(), 'The message From/To pair violates a blacklist rule') !== false || strpos($e->getMessage(), 'is not a mobile number') !== false) {
        } elseif(strpos($e->getMessage(), 'Unable to create record: The From phone number') !== false || strpos($e->getMessage(), 'not a valid, SMS-capable inbound') !== false){
		} else {
            return false;
        }
    }
}

function send_twilio_sms_service($to_phone, $from_sid, $message, $media=null, $rcp_id=null)
{
	$client = get_twilio_service();

	$send_vars = array(
		'messagingServiceSid' => $from_sid,
		'body' => $message,
		);
	if ($media) $send_vars['mediaUrl'] = $media;
	try {
		$return = $client->messages->create($to_phone, $send_vars);
		return $return->sid;
	}
	catch (Exception $e) {
        if (strpos($e->getMessage(), 'is not a valid phone number') !== false || strpos($e->getMessage(), 'The message From/To pair violates a blacklist rule') !== false || strpos($e->getMessage(), 'is not a mobile number') !== false) {
            require_once dirname(__FILE__, 2) .'/models/People.php';
            opt_out_phone($to_phone);
        } elseif(strpos($e->getMessage(), 'Invalid messaging service sid') !== false || strpos($e->getMessage(), 'not a valid, SMS-capable inbound') !== false){
			require_once dirname(__FILE__, 2) .'/models/Connections.php';
			update_connection($from_sid, array('conn_error'=>1));
			send_mandrill_email(false, 'There is an issue with Messaging SID '.$from_sid.'. This needs to be investigated and possibly restored. The Messaging SID is assigned in Text In Church, but possibly unavailable in Twilio.', 'Error with Account Messaging SID', 'Error Logging', 'no-reply@textinchurch.com', 'support@textinchurch.com');
		} else {
            if ($rcp_id) {
                mark_receipt($rcp_id, 'fail','Message failed to deliver');
            }
            return false;
        }
    }
}

 function count_twilio_sms($sa_sid, $renewal_date, $end_date=false)
 {

     try {
		 $client = get_twilio_service();
         $filters = array(
	         "category" => "sms-outbound",
	         "startDate" => $renewal_date
	     );

         if ($end_date) {
             $filters['endDate'] = $end_date;
         }

         $total = 0;
         $records = $client->usage->records->read($filters);

         foreach ($records as $record) {
             $total = $total+$record->usage;
         }

         $filters['category'] ="mms-outbound";

         $records2 = $client->usage->records->read($filters);
         foreach ($records2 as $record) {
             $mmsUpper = $record->usage*2;
             $total = $total+$mmsUpper;
         }
         return $total;
     } catch (Exception $e) {
         return false;
     }
 }

 function check_twilio_msg_status($msg_sid)
 {
     try {
         $client = get_twilio_service();

         // Get an object from its sid. If you do not have a sid,
         // check out the list resource examples on this page
         $message = $client
                ->messages($msg_sid)
                ->fetch();

         return $message;
     } catch (Exception $e) {
         return false;
     }
 }

 function release_twilio_number($sid){
 	try{
	 	$client = get_twilio_service();
 		$client->incomingPhoneNumbers($sid)->delete();
 		return true;
 	} catch (Exception $e) {
 		error_log('release number from twilio error: Code: '.$e->getCode().' Message:'.$e->getMessage());
 		return false;
 	}
 }
