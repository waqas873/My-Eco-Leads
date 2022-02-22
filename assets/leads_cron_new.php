<?php
echo "<h1>Processing........</h1>";
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define("LIST_ID", 186);

include_once("helpers/twilio_helper.php");

$servername = "localhost";
$username = "myecoleads_myeco-leads";
$password = "nK%4TsPoH.Lm";
$dbname = "myecoleads_myeco-leads";

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

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
// Load Composer's autoloader
require 'vendor/autoload.php';

function RemoveSpecialChar($value){
    $result  = preg_replace('/[^a-zA-Z0-9_ -]/s','',$value);
    return $result;
}

function formate_number($number,$postion,$n){
    return true;
    //function for cell numberformate it takes three para first cell number second for postion of digit that want to remove and third one is for number that you want to remove
    $num=array();
    $num = str_split($number);
    if($num[$postion]==$n){

        $index=array_search($n,$num);
        if ($postion==$index) {
            unset($num[$postion]);
            $number= implode("",$num);
            return $number;
        }
    }
    else
        if ($num[0]=='0') {
            # code...
            unset($num[0]);
            array_unshift($num , '+44');
            $number= implode("",$num);
            return $number;
        }
        else{
            return $number;
        }
}

function debug($arr, $exit = true)
{
    print "<pre>";
        print_r($arr);
    print "</pre>";
    if($exit)
        exit;
}

############# If api is active get all contact details ################
//if ($response->success == 1) {
############# Get Leads Data #############

$leads_data = [];
$all_leads_info = [];

$sql = "select * from leads_new where status = 0";
$leads = mysqli_query($conn, $sql);
while($row = mysqli_fetch_array($leads)){
    $leads_data[] = $row;
}
//debug($leads_data);


if(!empty($leads_data)){
    $available_leads = count($leads_data);
    $sql = "select orders.order_id,orders.user_id,orders.total_leads,orders.remaining_leads from orders LEFT JOIN users ON orders.user_id = users.user_id where orders.status = 0 AND users.is_paused = 0 order by orders.remaining_leads DESC";
    $client_orders = mysqli_query($conn, $sql);
    $sql = "select SUM(remaining_leads) as remaining_leads from orders LEFT JOIN users ON orders.user_id = users.user_id where orders.status = 0 AND users.is_paused = 0";
    $result = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($result);
    $client_orders_sum = $result['remaining_leads'];
    $remaining_leads = $available_leads;
    //echo $available_leads.'----------'.$client_orders_sum; exit;
    if($available_leads>$client_orders_sum || $available_leads==$client_orders_sum){
        while($row = mysqli_fetch_array($client_orders)){
            $deliver_leads = $row['remaining_leads'];
            $remaining_leads = $remaining_leads-$deliver_leads;
            update_orders($row['order_id'],$deliver_leads);
        }
    }
    else{
        $total_clients = mysqli_num_rows($client_orders);
        $average = ($available_leads>$total_clients)?true:false;
        if($average){
            $orders_average = $available_leads/$client_orders_sum;
        }
        $again = false;
        while($remaining_leads>0){
            $exploded_leads = 0;
            while($row = mysqli_fetch_array($client_orders)){
                if(isset($orders_average) && $again === false){
                    $delivered_leads = $orders_average*$row['remaining_leads'];
                    $explode = explode(".",$delivered_leads);
                    $delivered_leads = $explode[0];
                    if(isset($explode[1])){
                        $float = '0.'.$explode[1];
                        $exploded_leads=$exploded_leads+$float;
                    }
                }
                else{
                    $delivered_leads = ($remaining_leads>0)?1:0;
                    $remaining_leads = $remaining_leads-1;
                }
                update_orders($row['order_id'],$delivered_leads);
            }
            $remaining_leads = $exploded_leads;
            $again = true;
        }
    }
}

function update_orders($order_id,$deliver_leads){
    global $conn;
    global $leads_data;
    
    $sql = "select * from orders where order_id = '".$order_id."'";
    $order = mysqli_query($conn, $sql);
    $order = mysqli_fetch_array($order);
    $index = 1;
    $all_leads = 0;
    $user_email = '';

    $sql2 = "select users.email,users.first_name,users.last_name,users.is_email_notification,users.secondary_email from users where user_id = '".$order['user_id']."' AND is_email_notification = 1";
    $result = mysqli_query($conn, $sql2);
    $result = mysqli_fetch_array($result);
    $email_notification = false;
    if(!empty($result)){
        $user_email = (!empty($result['secondary_email']))?$result['secondary_email']:$result['email'];
        ($result['is_email_notification']==1)?$email_notification=true:'';
    }

    //$user_email = 'muhammadw873@gmail.com';

    foreach($leads_data as $key => $value){

        if($index <= $deliver_leads){
            $first_name = (empty($value['name']))?'Unknown':RemoveSpecialChar($value['name']);
            $last_name = (empty($value['last_name']))?'Unknown':RemoveSpecialChar($value['last_name']);
            $email = (empty($value['email']))?'Unknown@gmail.com':$value['email'];
            $contact_mobile = (empty($value['phone']))?'9999999999':str_replace('+44',0,$value['phone']);
            $list_id = LIST_ID;
            $email_msg = '';
            $num_response = phone_number($contact_mobile);
            $leads_user_id = (!empty($num_response['is_valid']) && $num_response['is_valid'] == true)?$order['user_id']:0;

            $contact_mobile = ltrim($contact_mobile,0);
            $contact_mobile = '0'.$contact_mobile;

            $sql = "INSERT INTO leads (list_id,leads_new_id,user_id,first_name,last_name,email,contact_mobile,status) VALUES ('".$list_id."','".$value['id']."','".$leads_user_id."','".$first_name."','".$last_name."','".$email."','".$contact_mobile."','1')";
            mysqli_query($conn, $sql);
            $lead_id = mysqli_insert_id($conn);

            if(!empty($num_response['is_valid']) && $num_response['is_valid'] == true){
                 $sql = "INSERT INTO lead_order (lead_id,order_id,user_id) VALUES ('".$lead_id."','".$order_id."','".$order['user_id']."')";
                mysqli_query($conn, $sql);

                if(!empty($user_email)){
                    $user_full_name = $result['first_name'].' '.$result['last_name'];
                    $lead_msg = 'You have recieved a lead.Lead info is given below<br/>
                        <strong>Name: </strong>'.$first_name.'<br/><strong>Email: </strong>'.$email.'<br/><strong>Contact No: </strong>'.$contact_mobile.'<br/>';
                    $lead_msg .= '<strong>Postal Code: </strong>'.$value['postal_code'].'<br/>';
                    $lead_msg .= '<strong>House Number: </strong>'.$value['house_no'].'<br/>';
                    $lead_msg .= '<strong>Property Type: </strong>'.$value['property_type'].'<br/>';
                    $lead_msg .= '<strong>Household Benefits: </strong>'.$value['household_benefits'].'<br/>';
                    $lead_msg .= '<strong>Residential Status: </strong>'.$value['residential_status'].'<br/>';
                    $lead_msg .= '<strong>Floor Type: </strong>'.$value['floor_type'].'<br/>';
                    $lead_msg .= '<strong>Fuel Type: </strong>'.$value['fuel_type'].'<br/>';
                    $lead_msg .= '<strong>Heatign System Age: </strong>'.$value['heating_system'].'<br/>';
                    //send_email($user_full_name,$user_email,$lead_msg,'LEAD DELIVERED',$email_notification);
                    send_email($user_full_name,$user_email,$lead_msg,'LEAD DELIVERED',true);
                }
                $all_leads++;
            }
            else{
                $full_name = $first_name.' '.$last_name;
            }
            
            $sql5 = "UPDATE leads_new SET status = '1' WHERE id='".$value['id']."'";
            mysqli_query($conn, $sql5);

            unset($leads_data[$key]);
        }
     $index++;    
    }
    if($all_leads>0){
        $update_remaining_leads = $order['remaining_leads']-$all_leads;
        $status = ($update_remaining_leads==0)?1:0;
        $sql = "UPDATE orders SET remaining_leads = '".$update_remaining_leads."',status = '".$status."' WHERE order_id='".$order_id."'";
        mysqli_query($conn, $sql);
    }
    return true;
}

function phone_number($num='')
{
    global $conn;
    $data = array();
    $data['already_exist'] = false;
    $data['is_valid'] = true;
    return $data;

    $sql = "select * from phone_numbers where phone_number = '".$num."'";
    $result = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($result);
    if(!empty($result)){
        $data['already_exist'] = true;
        if($result['is_valid']==1){
            $data['is_valid'] = true;
        }
        return $data;
    }
    
    //$regex = ($num[1]==0)?"/^00/":"/^0/";
    $num = preg_replace("/^0/", "00(44)", $num);
    //echo "here is the num ".$num; exit;
    $curlPost = curl_init();
    /* Prepare the data array start */
    $post = array();
    $post['number'] = $num;
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
    // echo "Http Code = ". $get_http_code['http_code']."<br>";
    // // Print Json Response
    // print_r($responsePost);
    // // Decode response data
    $decode_result = json_decode($responsePost);
    // echo  "<pre>";
    // print_r($decode_result);
    $phone_number = str_replace("00(44)","0",$num);
    $is_valid = 0;
    if($decode_result->result->confidence=="Verified" || $decode_result->result->confidence=="Teleservice not provisioned" || $decode_result->result->confidence=="No coverage" || $decode_result->result->phone_type=="Landline"){
        $is_valid = 1;
        $data['is_valid'] = true;
    }
     $sql = "INSERT INTO phone_numbers (phone_number,post_data,is_valid,api_response) VALUES ('".$phone_number."','".json_encode($post)."','".$is_valid."','".$responsePost."')";
    mysqli_query($conn, $sql);
    return $data;
}

function send_email($name,$to,$message='',$title,$email_notification=false)
{
    $page = '<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html lang="en">
<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8">
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
        <tr><td align="left" style="font-size:18px;font-weight:bold;padding-bottom:12px;color:#a964a7;padding-left:5px;padding-right:5px"><img src="https://dev.myecoleads.com/assets/public/images/logo-mel.png" width="189" height="120" alt=""/>
              <div style="text-align:Center;">Welcome To My Eco Leads</div>
          </td>
        </tr>
        <tr>
          <td align="left" style="position:relative;padding-left:24px;padding-right:24px;padding-top:24px;padding-bottom:24px;border:3px solid #f7d5ed;background-color:#ffffff;border-radius:14px;-moz-border-radius:14px;-webkit-border-radius:14px;"> 
          <div class="mob" style=" position: absolute; right: -45px; top: -45px;"></div>
          <div style="font-size:25px;font-weight:700;color:#e9ac1e"> Hi,</div>
           <div style="font-size:25px;font-weight:700; padding-bottom: 35px; color:#a964a7">'.ucwords($name).'! </div>
            <div style="font-size:14px;line-height:20px;text-align:left;color:#333333"><br><br>
              <div style="font-size:18px;font-weight:700;color:#5e3368; padding-bottom:10px;"> '.$title.' </div>
              <p>'.$message.'</p>
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
    $mail->Host = "mail.myecoleads.com";
    $mail->Port = 587; // or 465
    $mail->IsHTML(true);
    $mail->Username = "no-reply@myecoleads.com";
    $mail->Password = "vo0Bu&]X]hf6";
    $mail->SetFrom("no-reply@myecoleads.com");
    $mail->From = "no-reply@myecoleads.com";
    $mail->FromName = "My Eco Leads";
    
    //Address to which recipient will reply
    $mail->addReplyTo("no-reply@myecoleads.com", "Reply");

    //CC and BCC
    // $mail->addCC("cc@example.com");
    // $mail->addBCC("bcc@example.com");

    //Send HTML or Plain Text email

    $mail->Subject = $title;
    $mail->Body = $page;
    $mail->AltBody = "Not Available";

    if($email_notification==false){
        $to = "newleads@webleadscompany.com";
        $mail->addAddress($to);
    }
    if($email_notification==true){
        $all_emails = explode(',', $to);
        $mail->addAddress($all_emails[0]);
        $mail->AddCC('newleads@webleadscompany.com', 'Dear Admin');
        foreach($all_emails as $key => $se){
            if($key > 0){
                $mail->AddCC($se, $name);
            }
        }
    }
   // $mail->AddCC('mfarhan7333@gmail.com', 'Muhammad Farhan');

    // $mail->addAddress('mfarhan7333@gmail.com');
    // $mail->AddCC('muhammadw873@gmail.com', 'dear');

    $mail->send();

    // if(!$mail->send()){
    //     echo "Mailer Error: " . $mail->ErrorInfo;
    // } 
    // else{
    //     echo "Message has been sent successfully";
    // }
    return false;
}

echo "<h1>Done</h1>";
echo "<hr>";

?>