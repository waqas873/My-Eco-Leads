<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Listener extends CI_Controller 
{
	
	/**
	* @var stirng
	* @access Public
	*/
	public $selected_tab = '';
	public $user_id = 0;
	/** 
	* Controller constructor
	* 
	* @access public 
	*/

 	public function __construct()
	{
 		parent::__construct();
		$this->selected_tab = 'messages';
		$this->layout = "";
		$this->load->model('users_model', 'users');
		$this->load->model('leads_model', 'leads');
 		$this->load->model('connections_model', 'connections');
		$this->load->model('conversations_model', 'conversations');
 		$this->load->model('messages_model', 'messages');
 		$this->load->model('receipts_model', 'receipts');
 		$this->load->model('groups_model', 'groups');
		if($this->session->userdata('user')){
	        $this->user_id = $this->session->userdata('user_id');
	    }
	}

	public function callback_url()
	{
		$this->layout = '';
	   //send_email_to("waqas","mfarhan7333@gmail.com",$post,'Inbound');
	    //	exit;
	    
		// $to = $this->input->post('To');
  //       $from = $this->input->post('From');
  //       $text_body = $this->input->post('Body');
  //       $MediaUrl = $this->input->post('MediaUrl0');
  //       $MediaContentType = $this->input->post('MediaContentType0');

		$to = "+19097570028";
        $from = "+923008988873";
        $text_body = "info";

        if(!empty($to) && !empty($from)){
        	$where = "contact_mobile = '".$from."'";
			$lead = $this->leads->get_where('*', $where, true, '' , '', '');
			$where = "conn_address = '".$to."'";
			$connection = $this->connections->get_where('*', $where, true, '' , '', '');
			if(!empty($lead) && !empty($connection)){
                $lead = $lead[0];
                $connection = $connection[0];
                $lead_id = $lead['lead_id'];
                $where = "conversations.lead_id = '".$lead_id."' AND conversations.user_id = '".$connection['user_id']."'";
			    $result = $this->conversations->get_where('*', $where, true, '' , '', '');
			    $conv_id = 0;
			    if(empty($result)){
			    	$save = [];
			    	$save['user_id'] = $connection['user_id'];
			    	$save['lead_id'] = $lead_id;
			    	$conv_id = $this->conversations->save($save);
			    }
			    else{
			    	$conv_id = $result[0]['conv_id'];
			    }
			    $message = [];
			    $message['conv_id'] = $conv_id;
			    $message['user_id'] = $connection['user_id'];
			    $message['msg_type'] = 'sms';
			    $message['msg_content'] = $text_body;
			    $message['msg_incoming'] = 1;
			    $message['msg_send_time'] = date("Y-m-d H:i:s");
			    $message_id = $this->messages->save($message);
                
                $where = "user_id = '".$connection['user_id']."' AND group_keyword = '".$text_body."'";
			    $result = $this->groups->get_where('*', $where, true, '' , '', '');
			    //debug($result,true);
			    if(!empty($result)){
			    	$result = $result[0];
			        $this->keyword_reply($lead_id,$connection['user_id'],$result['group_reply_text']);
			    }

			    $text_body = strtoupper($text_body);
			    if($text_body=="START" || $text_body=="STOP"){
			    	$cos = ($text_body=="START")?0:1;
			    	$update = ['contact_optout_sms'=>$cos];
			    	$this->leads->update_by('lead_id',$lead_id,$update);
			    }
			}
        }
        return false;
	}

	public function keyword_reply($lead_id='',$user_id='',$msg_content='')
	{
		if(empty($lead_id) || empty($user_id) || empty($msg_content))
		{
			return false;
		}
		$data = array();
		$this->layout = "";
		$where = "conversations.lead_id = '".$lead_id."' AND conversations.user_id = '".$user_id."'";
	    $result = $this->conversations->get_where('*', $where, true, '' , '', '');
	    $conv_id = 0;
	    if(empty($result)){
	    	$save = [];
	    	$save['user_id'] = $user_id;
	    	$save['lead_id'] = $lead_id;
	    	$conv_id = $this->conversations->save($save);
	    }
	    else{
	    	$conv_id = $result[0]['conv_id'];
	    }
	    $message = [];
	    $message['conv_id'] = $conv_id;
	    $message['user_id'] = $user_id;
	    $message['msg_type'] = 'sms';
	    $message['msg_content'] = $msg_content;
	    $message['msg_uniqid'] = time();
	    $message['msg_sent'] = 1;
	    $message['msg_send_time'] = date("Y-m-d H:i:s");
	    $message_id = $this->messages->save($message);

        $receipt = ['msg_id'=>$message_id,'lead_id'=>$lead_id,'msg_uniqid'=>$message['msg_uniqid'],'rcp_bill'=>'1','rcp_status'=>'pend'];
        $rcp_id = $this->add_receipt($receipt);
        if(!empty($rcp_id)){
            $this->send_rcp($rcp_id);
        }
        if(!empty($message_id)){
        	$data['response'] = true;
        }
		echo json_encode($data);
	}

	public function add_receipt($arr=[])
	{
	    if(empty($arr)){
	        return false;
	    }
	    $rcp_id = $this->receipts->save($arr);
	    return $rcp_id;
	}

	public function send_rcp($rcp_id='')
	{
	    if(empty($rcp_id)){
	        return false;
	    }
	    $update = ['rcp_status'=>'prog','rcp_notes'=>'Sending'];
	    $result = $this->mark_receipt($rcp_id,$update);
        
        $where = "receipts.rcp_id = '".$rcp_id."'";
	    $joins = array(
            '0' => array('table_name' => 'leads leads',
                'join_on' => 'leads.lead_id = receipts.lead_id',
                'join_type' => 'left'
            ),
            '1' => array('table_name' => 'messages messages',
                'join_on' => 'messages.msg_id = receipts.msg_id',
                'join_type' => 'left'
            )
        );
        $from_table = "receipts receipts";
        $select_from_table = 'receipts.rcp_id,leads.contact_mobile,messages.msg_content';
        $rcp = $this->receipts->get_by_join($select_from_table, $from_table, $joins, $where, "", "", '', '', '', '', "", "");
        if(!empty($rcp)){
        	$rcp = $rcp[0];
        	$to = $rcp['contact_mobile'];
			$msg_sid = send_twilio_sms("",$to,"+19097570028", $rcp['msg_content']);
			if(!empty($msg_sid)){
				$update = ['rcp_sid'=>$msg_sid];
				$this->receipts->update_by('rcp_id',$rcp_id,$update);
			}
        }
	    return $result;
	}

	public function mark_receipt($rcp_id='',$update=[])
	{
	    if(empty($rcp_id) || empty($update)){
	        return false;
	    }
	    $this->receipts->update_by('rcp_id',$rcp_id,$update);
	    return true;
	}

	public function set_status()
	{
		$data = json_decode(file_get_contents('php://input'), true);
        $msg_sid = $data['msg_sid'];
		$status = $data['status'];
		$message = $data['message'];
        try{
        	$update = ['rcp_status'=>$status,'rcp_notes'=>$message];
        	$this->receipts->update_by('rcp_sid',$msg_sid,$update);
            $output = array("status"=>"updated");
        } catch (Exception $e) {
            $output = array('status'=>'error', 'message'=>$e->getMessage());
        }
        header('Content-type: application/json');
        echo json_encode($output);
        //exit;
	}
	
}