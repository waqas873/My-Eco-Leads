<?php

$servername = "localhost";
$username = "debtmonsterco_wlc";
$password = "nK%4TsPoH.Lm";
$dbname = "debtmonsterco_wlc";

// $servername = "localhost";
// $username = "root";
// $password = "";
// $dbname = "wlc";

############# Create connection #############
$conn = new mysqli($servername, $username, $password, $dbname);

############# Check connection #############
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}


// Include the bundled autoload from the Twilio PHP Helper Library
require __DIR__ . '/src/Twilio/autoload.php';
use Twilio\Rest\Client;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Your Account SID and Auth Token from twilio.com/console
$account_sid = 'ACdcb321fad468676cf6db19fb4acb10dd';
$auth_token = 'fdd8a0e7baa6164101881764ce501eba';
// In production, these should be environment variables. E.g.:
// $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]

// A Twilio number you own with SMS capabilities
$twilio_number = "+19097570028";
$client = new Client($account_sid, $auth_token);
$sms = 'Helo World';
$to = '+16468937192';
$message = $client->messages->create(
    // Where to send a text message (your cell phone?)
    $to,
    array(
        'from' => $twilio_number,
        'body' => $sms
    )
);

print_r($message);

$type = "sent";
if(!empty($message)){
    $sql = "INSERT INTO sms (sms_to,sms_from,message,type) VALUES ('".$to."','".$twilio_number."','".$sms."','".$type."')";
    if($conn->query($sql) === TRUE){
        print_r($message);
    }
}

