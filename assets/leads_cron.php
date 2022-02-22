<?php
echo "<h1>Processing........</h1>";
ini_set('max_execution_time', 0);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
define("LIST_ID", 99);

include_once("helpers/twilio_helper.php");

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


// $sql = "select DISTINCT(crm_lead_id) from leads";
// $query = mysqli_query($conn, $sql);
// echo mysqli_num_rows($query); exit;

############# If api is active get all contact details ################
//if ($response->success == 1) {
############# Get Leads Data #############

$leads_data = [];
$all_leads_info = [];
for($i=1; $i <=3 ; $i++){ 
    $url = 'https://seasidemedia.api-us1.com';
    $params = array(
        'api_key' => 'a544e3a9c628cf1c447ffcdef2aabf99d0bac140877a42f4fe51e3a81ad8c26971e857bb',
        'api_action' => 'contact_list',
        'api_output' => 'json',
        'filters[listid]' => LIST_ID,
        'full' => 1,
        'sort'=>'id',
        'sort_direction'=>'DESC',
        'page'=>$i
    );
    // 'page'=>2
    $url = rtrim($url, '/ ');
    $query = http_build_query($params);
    if (!function_exists('curl_init')) {
        die('CURL not supported. (introduced in PHP 4.0.2)');
    }
    if ($params['api_output'] == 'json' && !function_exists('json_decode')) {
        die('JSON not supported. (introduced in PHP 5.2.0)');
    }
    $api = $url . '/admin/api.php?' . $query;
    $request = curl_init($api);
    curl_setopt($request, CURLOPT_HEADER, 0);
    curl_setopt($request, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($request, CURLOPT_FOLLOWLOCATION, true);
    $res = (string)curl_exec($request);
    curl_close($request);
    if (!$res) {
        die('Nothing was returned. Please try again later.');
    }
    $result = json_decode($res, true);
   // echo $result[0]['tags'][2];
   //   $LeadInfo = $row['fields'][10]['val'];
   // echo "<pre>";
   // print_r($result);
   //exit;
    foreach ($result as $row) {
        if(!empty($row['id'])){
            $sql = "select * from leads where crm_lead_id='".$row['id']."'";
            $query = mysqli_query($conn, $sql);
            if(mysqli_num_rows($query) == 0){
                $leads_data[] = $row;
            }
            else{
                $lead_result = mysqli_fetch_array($query);
                $LeadInfo = !empty($row['tags'][2]) ? $row['tags'][2]:"" ;
                if(empty($lead_result['lead_info']) && !empty($LeadInfo))
                {
                    $message = "";
                    $message = 'Lead information is updated of below given lead<br/>
                        <strong>First Name: </strong>'.$lead_result['first_name'].'<br/><strong>Last Name: </strong>'.$lead_result['last_name'].'<br/><strong>Email: </strong>'.$lead_result['email'].'<br/><strong>Contact No: </strong>'.$lead_result['contact_mobile'].'<br/>';
                    $single = ['lead_id'=>$lead_result['lead_id'],'user_id'=>$lead_result['user_id'],'LeadInfo'=>$LeadInfo,'message'=>$message];
                    $all_leads_info[] = $single;
                }
            }
        }
    }
}


if(!empty($leads_data)){
    $available_leads = count($leads_data);
    $sql = "select orders.order_id,orders.user_id,orders.total_leads,orders.remaining_leads from orders LEFT JOIN users ON orders.user_id = users.user_id where orders.status = 0 AND users.is_paused = 0 order by orders.remaining_leads DESC";
    $client_orders = mysqli_query($conn, $sql);
    $sql = "select SUM(remaining_leads) as remaining_leads from orders LEFT JOIN users ON orders.user_id = users.user_id where orders.status = 0 AND users.is_paused = 0";
    $result = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($result);
    $client_orders_sum = $result['remaining_leads'];
    $remaining_leads = $available_leads;
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

    foreach($leads_data as $key => $value){

        if($index <= $deliver_leads){
            $crm_lead_id = $value['id'];
            $first_name = (empty($value['first_name']))?'Unknown':RemoveSpecialChar($value['first_name']);
            $last_name = (empty($value['last_name']))?'Unknown':RemoveSpecialChar($value['last_name']);
            $email = (empty($value['email']))?'Unknown@gmail.com':$value['email'];
            $contact_mobile = (empty($value['phone']))?'9999999999':str_replace('+44',0,$value['phone']);
            $status = 1;
            $list_id = LIST_ID;
            $best_time_to_call = "";
            $confirm_mobile = "";
            $post_hub = array();
            $quest_ans = array();
            $email_msg = '';
            $lead_fields = " ";
            if(!empty($value['fields'][10]['val'])) {
                 $extra_ques = explode('|', $value['fields'][10]['val']);
                 if(!empty($extra_ques[0])) {
                   $confirm_mobile = $extra_ques[0];  
                   $post_hub['confirm_mobile'] = $extra_ques[0];
                 }
                 if(!empty($extra_ques[1])) {
                   $best_time_to_call = $extra_ques[1];  
                   $post_hub['best_time_to_call'] = $extra_ques[1];
                 }
            }
            $num_response = phone_number($contact_mobile);
            if($num_response['is_valid'] === false && $contact_mobile!=$confirm_mobile){
                $num_response = phone_number($confirm_mobile);
            }
            $leads_user_id = (!empty($num_response['is_valid']) && $num_response['is_valid'] == true)?$order['user_id']:0;
            
            if(isset($value['tags'][0]) && !empty($value['tags'][0])){
                $value['tags'][0] = rtrim($value['tags'][0],'|');
                $lead_fields .= $value['tags'][0].'|';
            }
            if(isset($value['tags'][1]) && !empty($value['tags'][1])){
                $lead_fields .= $value['tags'][1]."|";
            }
            if( isset($value['tags'][2]) && !empty($value['tags'][2]) ){
                $lead_fields .= $value['tags'][2].'|';
            }
            
                $lead_fields = rtrim($lead_fields,'|');

            if(!empty($lead_fields)){
                $leadInformation = explode('|', $lead_fields);
                   $byte_leads_answers=array();
                foreach($leadInformation as $answer){
                    $expld = explode("?", $answer);
                    if( !empty($expld[0]) && !empty($expld[1]) )
                    {
                     
                        $quest_ans[$expld[0]] = $expld[1];
                        $email_msg .= '<strong>'.$expld[0].'?</strong><div>'.$expld[1].'</div>';
                        if(strpos($answer, "much unsecured debt") !== false){
                           $post_hub['level_of_debt'] = $expld[1];
                            $byte_leads_answers['debt_level']=$expld[1];
                        }
                        if(strpos($answer, "types of debt") !== false){
                           $post_hub['type_of_debts'] = $expld[1];
                            $byte_leads_answers['type_of_debt']=$expld[1];
                        }
                        if(strpos($answer, "creditors do you") !== false){
                           $post_hub['no_of_creditors'] = $expld[1];
                            $byte_leads_answers['num_creditors']=$expld[1];
                        }
                        if(strpos($answer, "Employment status") !== false){
                           $post_hub['employment_status'] = $expld[1];
                            $byte_leads_answers['employment']=$expld[1];
                        }
                        if(strpos($answer, "rent or Own") !== false){
                           $post_hub['home'] = $expld[1];
                        }
                        if(strpos($answer, "can you afford") !== false){
                           $post_hub['afford_to_pay_debts'] = $expld[1];
                        }
                        if(strpos($answer, "do you live") !== false){
                           $post_hub['live'] = $expld[1];
                            $byte_leads_answers['where_do_you_live']=$expld[1];
                        }
                        if(strpos($answer, "Preffered time to call") !== false){
                           $post_hub['best_time_to_call'] = $expld[1];
                            $byte_leads_answers['time_to_contact']=$expld[1];
                        }
                    }
                }
            }

            $quest_ans = (!empty($quest_ans)) ? json_encode($quest_ans):"";
            $contact_mobile = ltrim($contact_mobile,0);
            $contact_mobile = '0'.$contact_mobile;

             $sql = "select * from leads where crm_lead_id = '".$crm_lead_id."'";
            $lead_res = mysqli_query($conn, $sql);
            if(mysqli_num_rows($lead_res) == 0){
                 $sql = "INSERT INTO leads (crm_lead_id,list_id,user_id,first_name,last_name,email,contact_mobile,status,lead_info,confirm_mobile_number,best_time_to_call) VALUES ('".$crm_lead_id."','".$list_id."','".$leads_user_id."','".$first_name."','".$last_name."','".$email."','".$contact_mobile."','".$status."','".$quest_ans."','".$confirm_mobile."','".$best_time_to_call."')";
                mysqli_query($conn, $sql);
                $lead_id = mysqli_insert_id($conn);
                send_sms($lead_id,$leads_user_id);
         
                if(!empty($num_response['is_valid']) && $num_response['is_valid'] == true){
                   $sql = "INSERT INTO lead_order (lead_id,order_id,user_id) VALUES ('".$lead_id."','".$order_id."','".$order['user_id']."')";
                    mysqli_query($conn, $sql);
                    $tags=(!empty($value['tags']))?implode(',',$value['tags']):'';
                    $post_hub['firstname'] = $first_name;
                    $post_hub['lastname'] = $last_name;
                    $post_hub['email'] = $email;
                    $post_hub['phone_mobile'] = $contact_mobile;
                    $post_hub['lead_generator'] = "debt advice service";
                    $post_hub['tags'] = $tags;

                    $sql = "select api_id from user_api where user_id = '".$order['user_id']."' and status=1";
     
                    $user_apis=mysqli_query($conn, $sql);
                    $post_zeavo = ['first_names'=>$first_name,'surname'=>$last_name,'mobile_number'=>$contact_mobile,'email_address'=>$email];
                    if(mysqli_num_rows($user_apis)>0){
                        while ($row_api=mysqli_fetch_array($user_apis)){
                    
                              if($row_api['api_id']==2){
                                      hub($order['user_id'],$lead_id,$post_hub);
                              } else

                                  if($row_api['api_id']==3){
                                      zeavo($order['user_id'],$lead_id,$post_zeavo,$quest_ans);
                                  }else
                                      if($row_api['api_id']==4){
                                      vision_blue($order['user_id'],$lead_id,$post_zeavo,$quest_ans,$byte_leads_answers);
                              }else
                                  if($row_api['api_id']==5){
                                     // echo $contact_mobile.">>>>>>>>>";
                                      ByteLead($row_api['api_id'],$lead_id,$order['user_id'],$first_name,$last_name,$contact_mobile,$email,$byte_leads_answers);
                                  }
                        }
                    }
                    if(!empty($user_email)){
                        $user_full_name = $result['first_name'].' '.$result['last_name'];
                        $lead_msg = "";
                        $lead_msg = 'You have recieved a lead.Lead info is given below<br/>
                            <strong>First Name: </strong>'.$first_name.'<br/><strong>Last Name: </strong>'.$last_name.'<br/><strong>Email: </strong>'.$email.'<br/><strong>Contact No: </strong>'.$contact_mobile.'<br/>';
                            
                            if(!empty($confirm_mobile)) {
                                $lead_msg .='<strong>Please Confirm your phone number? </strong>'.$confirm_mobile.'<br/>';
                             }
                            if(!empty($best_time_to_call)) {
                                 $lead_msg .='<strong>Time to call? </strong>'.$best_time_to_call.'<br/>';
                            } 
                            
                        $stringQA = $email_msg;
                        if(!empty($stringQA)) {
                            $lead_msg .='<br><strong>Extra Information : </strong><br/>';
                        }
                        $lead_msg .=$stringQA;
                        send_email($user_full_name,$user_email,$lead_msg,'LEAD DELIVERED',$email_notification);
                    }
                    $all_leads++;
                }
                else{
                    $full_name = $first_name.' '.$last_name;
                    if($lead_id>0) {
                        invalid_phone_email($full_name,$email); 
                    }
                }
            }
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
function send_sms($lead_id='',$user_id='')
{
    if(empty($lead_id) || empty($user_id)){
        return false;
    }
    global $conn;

    $sql = "select * from users where user_id = '".$user_id."' AND enable_auto_reply = '1'";
    $result = mysqli_query($conn, $sql);
    $user = mysqli_fetch_array($result);
    if(empty($user)){
        return false;
    }
    if(empty($user['auto_reply_message'])){
        return false;
    }
    $msg_content = $user['auto_reply_message'];
    $lead_id = $lead_id;
    $sql = "select * from conversations where lead_id = '".$lead_id."' AND user_id = '".$user_id."'";
    $result = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($result);
    $conv_id = 0;
    if(empty($result)){
        $sql = "INSERT INTO conversations (user_id,lead_id) VALUES ('".$user_id."','".$lead_id."')";
        if($conn->query($sql) === TRUE){
            $conv_id = mysqli_insert_id($conn);
        }
    }
    else{
        $conv_id = $result['conv_id'];
    }
    $time = time();
     $sql = "INSERT INTO messages (conv_id,user_id,msg_type,msg_content,msg_uniqid,msg_sent,msg_send_time) VALUES ('".$conv_id."','".$user_id."','sms','".$msg_content."','".$time."','1','".date("Y-m-d H:i:s")."')";
    if($conn->query($sql)){
        $message_id = mysqli_insert_id($conn);
        $receipt = ['msg_id'=>$message_id,'lead_id'=>$lead_id,'msg_uniqid'=>$time];

        $rcp_id = add_receipt($receipt);
        if(!empty($rcp_id)){
            send_rcp($rcp_id);
        }
    }
    return false;
}

function add_receipt($arr=[])
{
    global $conn;
    if(empty($arr)){
        return false;
    }
     $sql = "INSERT INTO receipts (msg_id,lead_id,msg_uniqid,rcp_bill,rcp_status) VALUES ('".$arr['msg_id']."','".$arr['lead_id']."','".$arr['msg_uniqid']."','1','pend')";
    if($conn->query($sql)){
        $rcp_id = mysqli_insert_id($conn);
        return $rcp_id;
    }
    return false;
}

function send_rcp($rcp_id='')
{
    global $conn;
    if(empty($rcp_id)){
        return false;
    }
    $update = ['rcp_status'=>'prog','rcp_notes'=>'Sending'];
    $result = mark_receipt($rcp_id,$update);
    
    $sql = "select receipts.rcp_id,leads.contact_mobile,messages.msg_content from receipts 
     JOIN leads ON leads.lead_id = receipts.lead_id 
     JOIN messages ON messages.msg_id = receipts.msg_id 
    where receipts.rcp_id = '".$rcp_id."'";
    $result = mysqli_query($conn, $sql);
    $rcp = mysqli_fetch_array($result);

    if(!empty($rcp)){
        $to = $rcp['contact_mobile'];
        $msg_sid = send_twilio_sms($to,"+19097570028", $rcp['msg_content']);
        if(!empty($msg_sid)){
            $sql = "UPDATE receipts SET rcp_sid = '".$msg_sid."' WHERE rcp_id='".$rcp_id."'";
            if($conn->query($sql) === TRUE){
            }
        }
    }
    return $rcp;
}

function mark_receipt($rcp_id='',$update=[])
{
    global $conn;
    if(empty($rcp_id) || empty($update)){
        return false;
    }
    $sql = "UPDATE receipts SET rcp_status = '".$update['rcp_status']."',rcp_notes = '".$update['rcp_notes']."' WHERE rcp_id='".$rcp_id."'";
    if($conn->query($sql)){
        return true;
    }
    return false;
}

function hub($user_id=0,$lead_id='',$post_hub='')
{
    global $conn;
    
    $return = false;
    $sql = "select ua.user_api_id,ua.api_id,ua.api_settings,api.api_name from user_api as ua inner JOIN api as api ON ua.api_id = api.api_id where ua.user_id = '".$user_id."' AND ua.status = 1 AND api.api_id = '2'";
    $result = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($result);
    if(!empty($result)){
        $post_hub = $post_hub;
        $hupl = json_decode($result['api_settings']);
        $post_hub['HUBSOLV-API-KEY']=$hupl->hubsolv_api_key;
        $post_hub['lead_source']=(!empty($hupl->lead_source) ?$hupl->lead_source:"Debt Monster");
        if(!empty($hupl->campaignid)) {
            $post_hub['campaignid'] = $hupl->campaignid; 
        }

        $username_pass = $hupl->username.':'.$hupl->password;
        $base64 =  base64_encode($username_pass);

        $hub_url = clean_url($hupl->api_url);
//        if(filter_var($hupl->api_url, FILTER_VALIDATE_URL)){
//            $hub_url = $hupl->api_url;
//        }
//        else{
//            $hub_url = 'https://'.$hupl->api_url.'/api/client/format/json';
//        }
        $hub_url = 'https://'.$hub_url.'/api/client/format/json';
        $curlPost = curl_init();
        curl_setopt_array($curlPost, array(
            CURLOPT_URL => $hub_url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_USERAGENT => "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:62.0) Gecko/20100101 Firefox/62.0",
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS => http_build_query($post_hub),
            CURLOPT_HTTPHEADER => array(
                //"Content-Type: application/json",
                "Authorization: Basic $base64"
            )
        ));
        $responsePost = curl_exec($curlPost);
        $decode_result = json_decode($responsePost);

        $status = ($decode_result->status=='success_post')?1:0;
        $failed_attempts = ($status==1)?0:1;
          $sql = "INSERT INTO api_leads (lead_id,user_id,api_id,post_data,api_response,status,failed_attempts) VALUES ('".$lead_id."','".$user_id."','".$result['api_id']."','".json_encode($post_hub)."','".$responsePost."','".$status."','".$failed_attempts."')";
        if($conn->query($sql) === TRUE){
            $return = true;
        }
    }
    return $return;
}
function clean_url($url)
{
    $parsedUrl = parse_url($url);
    if(!isset($parsedUrl['host']))
    {
        $url = explode("/",$parsedUrl['path']);
        return $url[0];
    }
    else{
        return $parsedUrl['host'];
    }
}
function zeavo($user_id=0,$lead_id='',$post_zeavo='',$lead_info='')
{
    if(empty($user_id) || empty($lead_id) || empty($post_zeavo)){
        return false;
    }
    $lead_info = (empty($lead_info))?"unavailable":$lead_info;

    global $conn;
    $sql = "select ua.user_api_id,ua.api_id,ua.api_settings,api.api_name from user_api as ua inner JOIN api as api ON ua.api_id = api.api_id where ua.user_id = '".$user_id."' AND ua.status = 1 AND api.api_id = '3'";
    $result = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($result);
    
    if(!empty($result) && !empty($post_zeavo)){
        $hupl = json_decode($result['api_settings']);
        $post_data = array(
            'key' => $hupl->api_key,
            //'title' => 'Mr',
            'first_names' => $post_zeavo['first_names'],
            'surname' => $post_zeavo['surname'],
            'mobile_number' => $post_zeavo['mobile_number'],
            // 'home_number' => '0161 123 4567',
            'email_address' => $post_zeavo['email_address'],
            //'postal_code' => 'A123BC',
            //'introducer_id' => 0,
            'lead_group_id' => $hupl->lead_group_id,
            //'lead_type_id' => 0,
            // 'debt_level' => 1000,
            // 'custom' => array(
            //     'key1'     => 'value1'
            // )
            'lead_info' => $lead_info
        );

        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $hupl->api_url );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode( $post_data ) );
        $response = curl_exec( $ch );
        curl_close( $ch );

        // echo "<pre>";
        // print_r($response);
        // exit;

        if(!empty($response)){
            $sql = "INSERT INTO api_leads (lead_id,user_id,api_id,post_data,api_response) VALUES ('".$lead_id."','".$user_id."','".$result['api_id']."','".json_encode($post_data)."','".$response."')";
            if($conn->query($sql) === TRUE){
                return true;
            }
        }
    }
    return false;
}

function vision_blue($user_id=0,$lead_id='',$post_abbotts='',$quest_ans='',$byte_leads_answers=array())
{
    if(empty($user_id) || empty($lead_id) || empty($post_abbotts)){
        return false;
    }

    global $conn;
    $sql = "select ua.user_api_id,ua.api_id,ua.api_settings,api.api_name from user_api as ua inner JOIN api as api ON ua.api_id = api.api_id where ua.user_id = '".$user_id."' AND ua.status = 1 AND api.api_id = '4'";
    $result = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($result);
    
    if(!empty($result) && !empty($post_abbotts)){
        $hupl = json_decode($result['api_settings']);
        $post_data = array(
            'token' => $hupl->api_token,
            'first_name' => $post_abbotts['first_names'],
            'last_name' => $post_abbotts['surname'],
            'mobile' => $post_abbotts['mobile_number'],
            'email' => $post_abbotts['email_address'],
            'country'=>"US",
            'consent_to_contact'=>1,
            'consent_mode' => '1,2,3,4,5,6,7'
        );
        if(isset($hupl->lead_group_id) && !empty($hupl->lead_group_id)){
            $post_data['lead_group_id'] = $hupl->lead_group_id;
        }
        if(isset($hupl->team_id) && !empty($hupl->team_id)){
            $post_data['team_id'] = $hupl->team_id;
        }

        if(!empty($byte_leads_answers) && $byte_leads_answers['debt_level']){
            $post_data['debt_amount'] = $byte_leads_answers['debt_level'];
            unset($byte_leads_answers['debt_level']);
        }
        if(!empty($byte_leads_answers) && $byte_leads_answers['employment']){
            $post_data['employment_status'] =$byte_leads_answers['employment'];
            unset($byte_leads_answers['employment']);
        }
        if(!empty($byte_leads_answers) && $byte_leads_answers['where_do_you_live']){
            $post_data['country'] = $byte_leads_answers['where_do_you_live'];
            unset($byte_leads_answers['where_do_you_live']);
        }
        
        if(!empty($byte_leads_answers) && $byte_leads_answers['type_of_debt']){
            $post_data['credit_rating'] = $byte_leads_answers['type_of_debt'];
            unset($byte_leads_answers['type_of_debt']);
        }

        if(!empty($byte_leads_answers) && $byte_leads_answers['num_creditors']){
            $post_data['number_of_debts'] = $byte_leads_answers['num_creditors'];
            unset($byte_leads_answers['num_creditors']);
        }
        
        if(empty($post_data['employment_status'])) {
               $post_data['employment_status'] = "Employed";
        }


        if(!empty($byte_leads_answers)){
            $qa = "";
            foreach($byte_leads_answers as $key => $value){
                $qa .= $key;
                $qa .= $value;
            }
            $post_data['comments'] = $qa;
        }
        $hub_url=clean_url($hupl->api_url);
        $hub_url = 'https://'.$hub_url.'/webforms/create';
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $hub_url );
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt( $ch, CURLOPT_POSTFIELDS,  $post_data);
        curl_setopt( $ch, CURLOPT_HTTPHEADER ,  array("Content-Type:multipart/form-data"));
        $response = curl_exec( $ch );
        curl_close( $ch );

        // echo "<pre>";
        // print_r($response);
        // exit;

        if(!empty($response)){
            $sql = "INSERT INTO api_leads (lead_id,user_id,api_id,post_data,api_response,lead_info) VALUES ('".$lead_id."','".$user_id."','".$result['api_id']."','".json_encode($post_data)."','".$response."','".json_encode($quest_ans)."')";
            if($conn->query($sql) === TRUE){
                return true;
            }
        }
    }
    return false;
}
function ByteLead($api_id , $lead_id , $user_id , $first_name , $last_name, $contact_mobile12 , $email , $byte_leads_answers){
   // $row_api['api_id'],$lead_id,$order['user_id'],$first_name,$last_name,$contact_mobile,$email,$byte_leads_answers

//echo ">>>>>>>>>>".$contact_mobile12;

    global $conn;
    $sql = "select ua.user_api_id,ua.api_id,ua.api_settings,api.api_name from user_api as ua inner JOIN api as api ON ua.api_id = api.api_id where ua.user_id = '".$user_id."' AND ua.status = 1 AND api.api_id = 5";
    $result = mysqli_query($conn, $sql);
    $result = mysqli_fetch_array($result);
   //print_r($byte_leads_answers);
    if(!empty($result)){
        $hupl = json_decode($result['api_settings']);
         $compaignid=$hupl->campaign_id;
         $sid=$hupl->sid;
        $url ="https://moneyresults.leadbyte.co.uk/api/submit.php?campid=$compaignid&sid=$sid&returnjson=yes";
        //  echo $contact_mobile.">>>>>>>>>>>";
        //  echo '<pre>';
        //  print_r($byte_leads_answers);
         
        //  exit;
        
       // print_r($byte_leads_answers);
         $timetoContact=trim($byte_leads_answers['time_to_contact']);
         $timetoContact=str_replace(" ","",$timetoContact);
         
         $debt_level=trim($byte_leads_answers['debt_level']);
         $debt_level=substr_replace($debt_level, ',', 9,0);
         $employment=!empty($byte_leads_answers['employment'])?trim($byte_leads_answers['employment']):'Employed';
         $dept_level=!empty($byte_leads_answers['debt_level'])?$debt_level:'Above £5,000';
         $dept_level=substr_replace($dept_level, '',10, 1);
            //echo $dept_level.">>>>>>>";
        $post = array(
            'email' => $email,
            'title' => 'Mr',
            'firstname'=>$first_name,
            'lastname' =>$last_name,
            'dob' => '22/01/1980', //not in ac list
            'street1' => '12 Red Road', //not in ac list
            'towncity'=>'Manchester', //not in ac list
            'county'=>'England',
            //'cover_amount'=>'10000',
            //'cover_term'=>'5year',
            'postcode'=>'M16DN', //not in ac list
            'phone1'=>$contact_mobile12,
            'ipaddress'=>'86.156.88.22',
            'optinurl'=>'https://debtmonster.co.uk',
            'optindate'=>date("d/m/Y"),
            'debt_level'=>'Above £5,000',
            'opt_in'=>'hello world', //not in ac list
            'privacy_policy'=>'hello world', //not in ac list
            'uk_resident'=>'Yes', //not in ac list
            'type_of_debt'=>!empty($byte_leads_answers['type_of_debt'])?trim($byte_leads_answers['type_of_debt']):'Credit Cards',
            'num_creditors'=>!empty($byte_leads_answers['num_creditors'])?trim($byte_leads_answers['num_creditors']):'2',
            'employment'=>$employment,
            'monthly_income'=>'Yes',   //its accept only yes or no
            'where_do_you_live'=>!empty($byte_leads_answers['where_do_you_live'])?trim($byte_leads_answers['where_do_you_live']):'England',
            'time_to_contact'=>!empty($byte_leads_answers['time_to_contact'])?$timetoContact:'9am-1pm',
        );

        $post_data = http_build_query($post);
        $api = $url;
        $ch = curl_init();
        curl_setopt( $ch, CURLOPT_URL, $api);
        curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt( $ch, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt( $ch, CURLOPT_HTTPHEADER , array("Content-Type:application/x-www-form-urlencoded"));
        $response = curl_exec( $ch );
        if(!empty($response)){
            global $conn;
            $sql = "INSERT INTO api_leads (lead_id,user_id,api_id,post_data,api_response,lead_info) VALUES ('".$lead_id."','".$user_id."','".$api_id."','".json_encode($post)."','".$response."','".json_encode($byte_leads_answers)."')";
            if($conn->query($sql) === TRUE){
              
                return true;
            }
            else{
                
                return false;
            }
        }

    }
}
function phone_number($num='')
{
    global $conn;
    $data = array();
    $data['already_exist'] = false;
    $data['is_valid'] = false;
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


if(!empty($all_leads_info)){
    foreach($all_leads_info as $row){
        $answers = explode('|', $row['LeadInfo']);
        $quest_ans = array();
        $level_of_debt = "";
        $creditors = "";
        $employment_status = "";
        $your_creditors = '';
        $afford_to_pay_debts = '';
        $where_you_live = '';
        //$discuss_options = "";
        if(!empty($answers[0])){
            $anwser_1 = str_replace("u00a3","&#163;",$answers[0]);
            $quest_ans['Whats your level of debt?'] = $anwser_1;
            $level_of_debt = '<br><strong>Whats your level of debt?</strong><div>'.$anwser_1.'</div>';
        }
        if(!empty($answers[1])){
            $quest_ans['How many creditors do you have?'] = $answers[1];
            $creditors = '<strong>How many creditors do you have?</strong><div>'.$answers[1].'</div>';
        }
        if(!empty($answers[2])){
            $quest_ans['Whats your current employment status?'] = $answers[2];
            $employment_status = '<strong>Whats your current employment status?</strong><div>'.$answers[2].'</div>';
        }
        if(!empty($answers[3])){
            $quest_ans['Who are your creditors?'] = $answers[3];
            $your_creditors = '<strong>Who are your creditors?</strong><div>'.$answers[3].'</div>';
        }
        if(!empty($answers[4])){
            $quest_ans['How much can you afford to pay of your debts?'] = $answers[4];
            $afford_to_pay_debts = '<strong>How much can you afford to pay of your debts?</strong><div>'.$answers[4].'</div>';
        }
        if(!empty($answers[5])){
            $quest_ans['Where do you live?'] = $answers[5];
            $where_you_live = '<strong>Where do you live?</strong><div>'.$answers[5].'</div>';
        }
        /*if(!empty($answers[3])){
            $quest_ans['What time and day would be best to call you to discuss your options?'] = $answers[3];
            $discuss_options = '<strong>What time and day would be best to call you to discuss your options?</strong><div>'.$answers[3].'</div>';
        }*/
        $sql = "UPDATE leads SET lead_info = '".json_encode($quest_ans)."' WHERE lead_id='".$row['lead_id']."'";
        mysqli_query($conn, $sql);
        if($row['user_id']>0){
            $sql = "select * from users where user_id = '".$row['user_id']."'";
            $result = mysqli_query($conn, $sql);
            $result = mysqli_fetch_array($result);
            if(!empty($result)){
                $email = (!empty($result['secondary_email']))?$result['secondary_email']:$result['email'];
                $full_name = $result['first_name'].' '.$result['last_name'];
                $message = $row['message'];
                (!empty($level_of_debt))?$message.=$level_of_debt:'';
                (!empty($creditors))?$message.=$creditors:'';
                (!empty($employment_status))?$message.=$employment_status:'';
                (!empty($your_creditors))?$message.=$your_creditors:'';
                (!empty($afford_to_pay_debts))?$message.=$afford_to_pay_debts:'';
                (!empty($where_you_live))?$message.=$where_you_live:'';
               // (!empty($discuss_options))?$message.=$discuss_options:'';
                //send_email($full_name,$email,$message,'LEAD INFO UPDATED');
            }

        }
    }
}

function send_email($name,$to,$message='',$title,$email_notification=false)
{
    $message='You have a Live Lead enquiry in your Debt Monster Dashboard" Please Log in on your <a href="https://debtmonster.co.uk/leads/" target="_blank">Dashboard Here</a>  to help you new customer';
    //echo "Trigger Send Email";
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
    // // else{
    // //     echo "Message has been sent successfully";
    // // }
    return false;
}

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

echo "<h1>Done</h1>";
echo "<hr>";

?>