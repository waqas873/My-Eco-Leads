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

use Twilio\TwiML\MessagingResponse;
// Set the content-type to XML to send back TwiML from the PHP Helper Library
//header("content-type: text/xml");
//$response = new MessagingResponse();
//$response->message(
   // "I'm using the Twilio PHP library to respond to this SMS!"
//);

$sql = "INSERT INTO sms (sms_to,sms_from,message) VALUES ('".$_POST['To']."','".$_POST['From']."','".$_POST['Body']."')";
if($conn->query($sql) === TRUE){
    //print_r($message);
}

echo $response;