<?php
<?php

// Import PHPMailer classes into the global namespace
// These must be at the top of your script, not inside a function
use PHPMailer\src\PHPMailer;
use PHPMailer\src\Exception;



// Load Composer's autoloader
//require 'vendor/autoload.php';

// Instantiation and passing `true` enables exceptions
$mail = new PHPMailer(true);
echo "adsfddfs"; exit;

//From email address and name
$mail->From = "support@consumer-care.co.uk";
$mail->FromName = "Debt Monster";

//To address and name
$mail->addAddress("muhammadw873@gmail.com", "Waqas Sajjad");

//Address to which recipient will reply
$mail->addReplyTo("support@consumer-care.co.uk", "Reply");

//CC and BCC
// $mail->addCC("cc@example.com");
// $mail->addBCC("bcc@example.com");

//Send HTML or Plain Text email
$mail->isHTML(true);

$mail->Subject = "Lead Delivered";
$mail->Body = "<i>Mail body in HTML</i>";
$mail->AltBody = "This is the plain text version of the email content";

if(!$mail->send()) 
{
    echo "Mailer Error: " . $mail->ErrorInfo;
} 
else 
{
    echo "Message has been sent successfully";
}

exit;

//send_email('waqas sajjad','muhammadw873@gmail.com');

function send_email($name,$to)
{
	$link = 'https://consumer-care-uk.typeform.com/to/jztunQ';
	$message = '<html><body>
    <div>
        <h1>We can’t contact you.</h1>
        <h3>Hi, '.ucwords($name).'</h3>
        <p>Thanks for your recent enquiry regarding some Free debt advice unfortunately we have tried your contact number and it seems to be Invalid.</p>
        <p>So that we can get you the best advice please can you update your details so that we can help<br />'.$link.'<br /></p>
        <p><strong>WE ARE HELPING PEOPLE WRITE OFF DEBT EVERY DAY. THERE ARE SOLUTIONS AVAILABLE TO YOU.</strong></p>
        <img src="http://www.debtmonster.co.uk/assets/images/cropped-consumer-care-4-300x134.png" width="400" height="200" alt="consumer logo">
    </div>
</body></html>';

	$subject = 'PHONE VALIDATION';
	$headers  = 'MIME-Version: 1.0' . "\r\n";
    $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: support@consumer-care.co.uk' . "\r\n" .
	    'X-Mailer: PHP/' . phpversion();
	mail($to, $subject, $message, $headers);
	return false;
}
exit;



$curlPost = curl_init();

/* Prepare the data array start */
$post = array();
$post['number'] = '00(44)7445257665';
$post['output_format'] = 'NATIONAL';
$post['cache_value_days'] = 7;
/* Prepare the data array End */
$token = '16bdaac2-119f-44c0-98e2-aba77d954574';


curl_setopt_array($curlPost, array(
    CURLOPT_URL => "https://api.experianaperture.io/phone/validation/v2",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_USERAGENT => "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:62.0) Gecko/20100101 Firefox/62.0",
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => json_encode($post),
    CURLOPT_HTTPHEADER => array(
        "Content-Type: application/json",
        "Accept: application/json",
        "Auth-Token:  $token",
        "Add-Metadata: true"
    )  // Set the Header
));
$responsePost = curl_exec($curlPost);

/* Get Http Code */
$get_http_code = curl_getinfo($curlPost);
echo "Http Code = ". $get_http_code['http_code']."<br>";

// Print Json Response
print_r($responsePost);


// Decode response data
$decode_result = json_decode($responsePost);
echo  "<pre>";
print_r($decode_result);
?>

