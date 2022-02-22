<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// Load Composer's autoloader
require '../vendor/autoload.php';

$lead_msg = 'You have recieved a lead.Lead info is given below<br/>
            <strong>First Name: </strong>unknown<br/><strong>Last Name: </strong>unknown<br/><strong>Email: </strong>abcd@gmail.com<br/><strong>Contact No: </strong>12343212343<br/><br/>';
$lead_msg .= '<strong>Whats your level of debt?</strong><div>76789</div>';
$lead_msg .= '<strong>How many creditors do you have?</strong><div>12</div>';
$lead_msg .= '<strong>Whats your current employment status?</strong><div>more than 2</div>';
$lead_msg .= '<strong>What time and day would be best to call you to discuss your options?</strong><div>3</div>';

send_email('Waqas sajjad','muhammadw873@gmail.com',$lead_msg);

//invalid_phone_email('Waqas sajjad','muhammadw873@gmail.com');

function invalid_phone_email($name,$to)
{
    $link = 'https://consumer-care-uk.typeform.com/to/jztunQ';
    $message = '<div>
        <h1>We can’t contact you.</h1>
        <h3>Hi, '.ucwords($name).'</h3>
        <p>Thanks for your recent enquiry regarding some Free debt advice unfortunately we have tried your contact number and it seems to be Invalid.</p>
        <p>So that we can get you the best advice please can you update your details so that we can help<br />'.$link.'<br /></p>
        <p><strong>WE ARE HELPING PEOPLE WRITE OFF DEBT EVERY DAY. THERE ARE SOLUTIONS AVAILABLE TO YOU.</strong></p>
        <img src="http://www.debtmonster.co.uk/assets/images/cropped-consumer-care-4-300x134.png" width="400" height="200" alt="consumer logo">
    </div>';

    $mail = new PHPMailer(true);
    $mail->IsHTML(true);
    $mail->From = "support@consumer-care.co.uk";
    //Address to which recipient will reply
    $mail->addReplyTo("support@consumer-care.co.uk", "Reply");
    $mail->Subject = 'INVALID PHONE NO';
    $mail->Body = $message;
    $mail->AltBody = "Not Available";
    $mail->addAddress($to);
    $mail->send();

    return false;
}

function send_email($name,$to,$lead_msg='')
{
    $page = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- So that mobile will display zoomed in -->
  <meta http-equiv="X-UA-Compatible" content="IE=edge"> <!-- enable media queries for windows phone 8 -->
  <meta name="format-detection" content="telephone=no"> <!-- disable auto telephone linking in iOS -->
  <title></title>
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet">
<style type="text/css">
body {margin: 0;padding: 0;-ms-text-size-adjust: 100%;-webkit-text-size-adjust: 100%;}
@media screen and (max-width:640px) {
.mob{right: 0!important;}
}   
@media screen and (max-width: 480px) {
  .container {width: auto!important;margin-left:10px;margin-right:10px;}
.mob{position: relative!important;text-align: center;top: 10px !important;right: 0!important;}
.mob img{width: 240px;height:auto;}
}
</style>
</head>
<body style="margin:0; padding:0;"  leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<table border="0" width="100%" height="100%" cellpadding="0" cellspacing="0" bgcolor="#eff6e4" style="font-family: "Open Sans", sans-serif;">
  <tr>
    <td align="center" valign="top" ><br>      
      
      <table border="0" width="600" cellpadding="0" cellspacing="0" class="container" style="width:600px;max-width:600px">
        <tr><td align="left" style="font-size:18px;font-weight:bold;padding-bottom:12px;color:#a964a7;padding-left:5px;padding-right:5px"><img src="http://www.debtmonster.co.uk/assets/images/logo-dm.png" width="189" height="120" alt=""/>
              <div style="text-align:Center;">Welcome To Debt Monster!</div>
          </td>
        </tr>
        <tr>
          <td align="left" style="position:relative;padding-left:24px;padding-right:24px;padding-top:24px;padding-bottom:24px;border:3px solid #f7d5ed;background-color:#ffffff;border-radius:14px;-moz-border-radius:14px;-webkit-border-radius:14px;"> 
          <div class="mob" style=" position: absolute; right: -45px; top: -45px;"><img src="http://www.debtmonster.co.uk/assets/images/bg-image.png"  alt=""/></div>
          <div style="font-size:25px;font-weight:700;color:#e9ac1e"> Hi,</div>
           <div style="font-size:25px;font-weight:700; padding-bottom: 35px; color:#a964a7">'.ucwords($name).'! </div>
            <div style="font-size:14px;line-height:20px;text-align:left;color:#333333"><br><br>
              <div style="font-size:18px;font-weight:700;color:#5e3368; padding-bottom:10px;"> LEAD DELIVERED </div>
              <p>'.$lead_msg.'</p>
              <br><br>
          </td>
        </tr>
        </table></td></tr></table>
    </body></html>';

    // Instantiation and passing `true` enables exceptions
    $mail = new PHPMailer(true);
    $mail->IsSMTP(); // enable SMTP
    $mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
    $mail->SMTPAuth = true; // authentication enabled
    //$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
    $mail->Host = "mail.debtmonster.co.uk";
    $mail->Port = 587; // or 465
    $mail->IsHTML(true);
    $mail->Username = "no-reply@debtmonster.co.uk";
    $mail->Password = "no-reply@callmonster";
    $mail->SetFrom("no-reply@debtmonster.co.uk");
    $mail->From = "no-reply@debtmonster.co.uk";
    $mail->FromName = "Debt Monster";
    
    //Address to which recipient will reply
    $mail->addReplyTo("no-reply@debtmonster.co.uk", "Reply");

    //CC and BCC
    // $mail->addCC("cc@example.com");
    // $mail->addBCC("bcc@example.com");

    //Send HTML or Plain Text email

    $mail->Subject = 'LEAD DELIVERED';
    $mail->Body = $page;
    $mail->AltBody = "Not Available";
    $mail->addAddress($to);
    $mail->AddCC('andy@webleadscompany.com', 'Dear Admin');
    $mail->AddCC('mfarhan7333@gmail.com', 'Farhan Sahib');
    
    $mail->send();

    // if(!$mail->send()){
    //     echo "Mailer Error: " . $mail->ErrorInfo;
    // } 
    // else{
    //     echo "Message has been sent successfully";
    // }

    return false;
}

?> 