<?php
include_once("config/config.php");
include_once("helpers/twilio_helper.php");

//create_rcp_send();

function create_rcp_send()
{
    global $conn;
    $sql = "select messages.*,conversations.lead_id from messages left JOIN conversations ON messages.conv_id = conversations.conv_id where messages.msg_sent = '0' AND messages.msg_send_time >= date_sub(now(), interval 12 hour)";
    $messages = mysqli_query($conn, $sql);
    while($message = mysqli_fetch_array($messages)){
        echo $message['msg_content']."<br/>".$message['lead_id'];
        $receipt = ['msg_id'=>$message['msg_id'],'lead_id'=>$message['lead_id'],'msg_uniqid'=>$message['msg_uniqid']];
        $rcp_id = add_receipt($receipt);
        if(!empty($rcp_id)){
            send_rcp($rcp_id);
        }
    }
}

function add_receipt($arr=[])
{
    global $conn;
    if(empty($arr)){
        return false;
    }
    $sql = "INSERT INTO receipts (msg_id,lead_id,msg_uniqid,rcp_bill,rcp_status) VALUES ('".$arr['msg_id']."','".$arr['lead_id']."','".$arr['msg_uniqid']."','1','pend')";
    if($conn->query($sql) === TRUE){
        return mysqli_insert_id($conn);
    }
    return false;
}

function send_rcp($rcp_id='')
{
    global $conn;
    if(empty($rcp_id)){
        return false;
    }
    $result = mark_receipt($rcp_id,"prog","Sending");
    send_twilio_sms("+923008988873", "+19097570028", "Helo new World");
    return $result;
}

function mark_receipt($rcp_id,$status,$notes)
{
    global $conn;
    if(empty($rcp_id) || empty($status) || empty($notes)){
        return false;
    }
    $sql = "UPDATE receipts SET rcp_status = '".$status."',rcp_notes = '".$notes."' WHERE rcp_id='".$rcp_id."'";
    if($conn->query($sql) === TRUE){
        return true;
    }
    return false;
}


?>