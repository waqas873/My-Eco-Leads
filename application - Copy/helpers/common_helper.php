<?php

function msg_notifications()
{
    $CI = get_instance();
    $CI->load->model('messages_model' , 'messages');
    $CI->load->model('conversations_model' , 'conversations');
    $data = [];
    $user_id = $CI->session->userdata('user_id');
    $where = "conversations.user_id = '".$user_id."'";
    $joins = array(
        '0' => array('table_name' => 'leads leads',
            'join_on' => 'leads.lead_id = conversations.lead_id',
            'join_type' => 'left'
        )
    );
    $from_table = "conversations conversations";
    $select_from_table = 'conversations.conv_id,conversations.lead_id,leads.first_name,leads.last_name';
    $conversations = $CI->conversations->get_by_join($select_from_table, $from_table, $joins, $where, '', '', '', '', '', '', '', '');
    if(!empty($conversations)){
        foreach($conversations as $conv){
            $where = "conv_id = '".$conv['conv_id']."' AND user_id = '".$user_id."' AND msg_sent = '0' AND msg_read = '0'";
            $result = $CI->messages->count_rows($where);
            if($result>0){
                $single = [];
                $single['conv_id'] = $conv['conv_id'];
                $single['lead_id'] = $conv['lead_id'];
                $single['msgs'] = $result;
                $single['name'] = $conv['first_name'].' '.$conv['last_name'];
                $data[] = $single;
            }
        }
    }
    //debug($data,true);
    return $data;
}

function get_connection($user_id = '')
{
    if(empty($user_id)){
        return false;
    }
    $CI = get_instance();
    $CI->load->model('connections_model' , 'connections');
    $data = [];
    $where = "user_id = '".$user_id."' AND is_removed = '0'";
    $result = $CI->connections->get_where('*', $where, true, '' , '', '');
    if(!empty($result)){
        return $result[0];
    }
    return $data;
}

function company($user_id='0')
{
    $CI = get_instance();
    $CI->load->model('companies_model' , 'companies');
    $company = '';
    $where = "user_id = '".$user_id."'";
	$result = $CI->companies->get_where('*', $where, true, '' , '', '');
    if(!empty($result)){
    	$company = $result[0];
    }
    return $company;
}

function lead_price()
{
    $CI = get_instance();
    $CI->load->model('Settings_model', 'settings');
    $price = 0;
    $where = "name = 'lead_price'";
    $result = $CI->settings->get_where('*', $where, true, '' , '', '');
    if(!empty($result)){
        $price = $result[0]['value'];
    }
    return $price;
}

function user_api($user_id=0,$api_id=0)
{
    $CI = get_instance();
    $CI->load->model('user_api_model' , 'user_api');
    $api = '';
    $where = "api_id = '".$api_id."' AND user_id = '".$user_id."'";
    $result = $CI->user_api->get_where('*', $where, true, '' , '', '');
    if(!empty($result)){
        $api = $result[0];
    }
    return $api;
}

function update_leads($user_id=0,$lead_id=0)
{
    $CI = get_instance();
    $CI->load->model('leads_model', 'leads');
    $CI->load->model('lead_order_model', 'lead_order');
    $CI->load->model('orders_model', 'orders');
    $where="orders.user_id = '".$user_id."' AND status = 0";
    $result = $CI->orders->get_where('*',$where, true, '','', '');
    if(!empty($result)){
        $order = $result[0];
        $ordered_leads = $order['total_leads'];
        $update = [];
        $update['remaining_leads'] = $order['remaining_leads']-1;
        if($update['remaining_leads']==0){
            $update['status'] = 1;
        }
        $CI->orders->update_by('order_id',$order['order_id'],$update);
        $update = [];
        $update['user_id'] = $user_id;
        $CI->leads->update_by('lead_id',$lead_id,$update);
        $save = ['lead_id'=>$lead_id,'order_id'=>$order['order_id'],'user_id'=>$user_id];
        $CI->lead_order->save($save);
    }
    return false;
}

function lead_phone_number($num='')
{
    $data = array();
    $CI = get_instance();
    $CI->load->model('phone_numbers_model', 'phone_numbers');
    $data['is_valid'] = false;
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
    if($decode_result->result->confidence=="Verified" || $decode_result->result->confidence=="Teleservice not provisioned" || $decode_result->result->confidence=="No coverage" || $decode_result->result->phone_type=="Landline"){
        $update = [];
        $update['is_valid'] = 1;
        $CI->phone_numbers->update_by('phone_number',$phone_number,$update);
        $data['is_valid'] = true;
    }
    return $data;
}

function hub($user_id=0,$lead_id='',$post_hub='')
{
    $return = false;
    $CI = get_instance();
    $CI->load->model('user_api_model', 'user_api');
    $CI->load->model('api_leads_model', 'api_leads');
    $where="user_id = '".$user_id."' AND status = 1";
    $result = $CI->user_api->get_where('*',$where, true, '','', '');
    if(!empty($result)){
        $result = $result[0];
        $post_hub = $post_hub;
        $hupl = json_decode($result['api_settings']);
        $post_hub['HUBSOLV-API-KEY']=$hupl->hubsolv_api_key;
        $post_hub['lead_source']="Debt Monster";
        $username_pass = $hupl->username.':'.$hupl->password;
        $base64 =  base64_encode($username_pass);
        $curlPost = curl_init();
        curl_setopt_array($curlPost, array(
            CURLOPT_URL => $hupl->api_url,
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
        $save = [];
        $save['lead_id'] = $lead_id;
        $save['user_id'] = $user_id;
        $save['api_id'] = $result['api_id'];
        $save['post_data'] = json_encode($post_hub);
        $save['api_response'] = $responsePost;
        $save['status'] = $status;
        $save['failed_attempts'] = $failed_attempts;
        if($CI->api_leads->save($save)){
            $return = true;
        }
    }
    return $return;
}

?>