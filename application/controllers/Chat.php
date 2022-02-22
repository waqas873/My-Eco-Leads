<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//use Twilio\Rest\Client;

class Chat extends CI_Controller 
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
		$this->selected_tab = 'chat';
		$this->layout = 'chat';
		$this->load->model('users_model', 'users');
		$this->load->model('leads_model', 'leads');
 		$this->load->model('connections_model', 'connections');
		$this->load->model('conversations_model', 'conversations');
 		$this->load->model('messages_model', 'messages');
 		$this->load->model('sms_orders_model', 'sms_orders');
 		$this->load->model('receipts_model', 'receipts');
		if(!$this->session->userdata('user')){
	        $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
		    redirect('sign-in');
	    }
	    $this->user_id = $this->session->userdata('user_id');
	}

	public function index($lead_id='')
	{
		$data = [];
		$where = "user_id = '".$this->user_id."'";
        $leads = $this->leads->get_where('lead_id,first_name,last_name', $where, true, '' , '', '');
        $data['leads'] = $leads;
        if(!empty($leads)){
        	$lead = $leads[0];
        	if(!empty($lead_id)){
        		$lead_id = decodeBase64($lead_id);
        		$where = "lead_id = '".$lead_id."'";
        		$result = $this->leads->count_rows($where);
        		if($result==0){
        			redirect('leads');
        		}
        	}
        	else{
        		$lead_id = $lead['lead_id'];
        	}
	        $data['chat'] = $this->get_chat($lead_id);
	        $data['lead_id'] = $lead_id;
        }
		$this->load->view('chat/index',$data);
	}

	public function send_sms()
	{
		$this->layout = 'user_dashboard';
		$data = [];
		$this->load->view('messages/send',$data);
	}

	public function fetch_chat()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$lead_id = $this->input->post('lead_id');
		if(empty($lead_id)){
           exit;
		}
		$chat = $this->get_chat($lead_id);
		$messages = '';
		if(!empty($chat)){
			foreach($chat as $key => $value){
				if($value['msg_sent']==0 && $value['msg_incoming']==1){
					$messages .= '
					<div class="kt-chat__message">
						<div class="kt-chat__user">
							<span class="kt-media kt-media--circle kt-media--sm">
								<img src="assets/chat/media/user.png" alt="image">
							</span>
							<a href="javascript::" class="kt-chat__username">
								'.$value['first_name'].' '.$value['last_name'].'
							</a>
							<span class="kt-chat__datetime">'.$value['msg_stamp'].'</span>
						</div>
						<div class="kt-chat__text kt-bg-light-success">
							'.$value['msg_content'].'
						</div>
					</div>';
				}
				if($value['msg_sent']==1 && $value['msg_incoming']==0){
					$messages .= '
					<div class="kt-chat__message kt-chat__message--right">
						<div class="kt-chat__user">
							<span class="kt-chat__datetime">'.$value['msg_stamp'].'</span>
							<a href="javascript::" class="kt-chat__username">You</a>
							<span class="kt-media kt-media--circle kt-media--sm">
								<img src="assets/chat/media/user.png" alt="image">
							</span>
						</div>
						<div class="kt-chat__text kt-bg-light-brand">
							'.$value['msg_content'].'
						</div>
					</div>';
				}
			}
			$data['chat'] = $messages;
			$data['response'] = true;
		}
		echo json_encode($data);
	}

	public function fetch_leads()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$search = $this->input->post('search');
		$where = "user_id = '".$this->user_id."'";
		if(!empty($search)){
			$explode = explode(' ', $search);
			if(isset($explode[1])){
				$where .= " AND (leads.first_name  LIKE CONCAT('%','" . $explode[0] . "' ,'%') AND leads.last_name LIKE CONCAT('%','" . $explode[1] . "' ,'%')  )";
			}
			else{
				$where .= " AND (leads.first_name  LIKE CONCAT('%','" . $search . "' ,'%') OR leads.last_name LIKE CONCAT('%','" . $search . "' ,'%') OR leads.email LIKE CONCAT('%','" . $search . "' ,'%') OR leads.contact_mobile LIKE CONCAT('%','" . $search . "' ,'%') )";
			}
        }   
        $leads = $this->leads->get_where('*', $where, true, '' , '', '');
        if(!empty($leads)){
        	$leads_list = '';
        	foreach($leads as $key => $value){
        		$leads_list .= '
        		<div class="kt-widget__item">
					<span class="kt-media kt-media--circle">
						<img src="assets/chat/media/user.png" alt="image">
					</span>
					<div class="kt-widget__info">
						<div class="kt-widget__section">
							<a href="javascript::" rel="'.$value["lead_id"].'" class="kt-widget__username lead_user">
							'.$value['first_name'].' '.$value['last_name'].'
								 </a>
							<span class="kt-badge kt-badge--success kt-badge--dot"></span>
						</div>
					</div>
				</div>';
        	}
        	$data['leads_list'] = $leads_list;
        	$data['response'] = true;
        }
		echo json_encode($data);
	}

	public function process_send_sms()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$data['stop'] = false;
		$data['sms_unavailable'] = false;
		$this->form_validation->set_rules('msg_content','','required|trim');
		$this->form_validation->set_rules('lead_id','','required');
		if($this->form_validation->run()===TRUE){
			$msg_content = $this->input->post('msg_content');
			$lead_id = $this->input->post('lead_id');

			$where = "user_id = '".$this->user_id."' AND status = '0'";
			$sms = $this->sms_orders->get_where('*', $where, true, 'so_id ASC' , '', '');
			if(!empty($sms)){
				$where = "leads.lead_id = '".$lead_id."'";
				$cos = $this->leads->get_where('*', $where, true, '' , '', '');
				$cos = $cos[0]['contact_optout_sms'];
				if($cos==0){
					$where = "conversations.lead_id = '".$lead_id."' AND conversations.user_id = '".$this->user_id."'";
				    $result = $this->conversations->get_where('*', $where, true, '' , '', '');
				    $conv_id = 0;
				    if(empty($result)){
				    	$save = [];
				    	$save['user_id'] = $this->user_id;
				    	$save['lead_id'] = $lead_id;
				    	$conv_id = $this->conversations->save($save);
				    }
				    else{
				    	$conv_id = $result[0]['conv_id'];
				    }
				    $message = [];
				    $message['conv_id'] = $conv_id;
				    $message['user_id'] = $this->user_id;
				    $message['msg_type'] = 'sms';
				    $message['msg_content'] = $msg_content;
				    $message['msg_uniqid'] = time();
				    $message['msg_sent'] = 1;
				    $message['msg_read'] = 1;
				    $message['msg_send_time'] = date("Y-m-d H:i:s");
				    $message_id = $this->messages->save($message);

		            $receipt = ['msg_id'=>$message_id,'lead_id'=>$lead_id,'msg_uniqid'=>$message['msg_uniqid'],'rcp_bill'=>'1','rcp_status'=>'pend'];
		            $rcp_id = $this->add_receipt($receipt);
		            if(!empty($rcp_id)){
			            $this->send_rcp($rcp_id);
			        }
			        if(!empty($message_id)){
			        	$data['message'] = '
			        	<div class="kt-chat__message kt-chat__message--right">
							<div class="kt-chat__user">
								<span class="kt-chat__datetime">'.$message['msg_send_time'].'</span>
								<a href="javascript::" class="kt-chat__username">You</a>
								<span class="kt-media kt-media--circle kt-media--sm">
									<img src="assets/chat/media/user.png" alt="image">
								</span>
							</div>
							<div class="kt-chat__text kt-bg-light-brand">
								'.$msg_content.'
							</div>
						</div>';
			        	$data['response'] = true;

			        	$sms = $sms[0];
			        	$remaining_sms = $sms['remaining_sms']-1;
			        	$update = [];
			        	$update['remaining_sms'] = $remaining_sms;
			        	if($remaining_sms==0){
			        		$update['status'] = 1;
			        	}
			        	$this->sms_orders->update_by('so_id',$sms['so_id'],$update);
			        }
				}
				else{
					$data['stop'] = true;
				}
			}
			else{
				$data['sms_unavailable'] = true;
			}
		}
		else{
            $data['msg_content_error'] = form_error('msg_content');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function user_replies()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$data['messages'] = '';
		$lead_id = $this->input->post('lead_id');
		if(empty($lead_id)){
			exit;
		}
		$where = " AND messages.msg_read = '0'";
		$chat = $this->get_chat($lead_id,$where);
		$messages = '';
		if(!empty($chat)){
			foreach($chat as $key => $value){
				if($value['msg_sent']==0 && $value['msg_incoming']==1){
					$messages .= '
					<div class="kt-chat__message">
						<div class="kt-chat__user">
							<span class="kt-media kt-media--circle kt-media--sm">
								<img src="assets/chat/media/user.png" alt="image">
							</span>
							<a href="javascript::" class="kt-chat__username">
								'.$value['first_name'].' '.$value['last_name'].'
							</a>
							<span class="kt-chat__datetime">'.$value['msg_stamp'].'</span>
						</div>
						<div class="kt-chat__text kt-bg-light-success">
							'.$value['msg_content'].'
						</div>
					</div>';
				}
				if($value['msg_sent']==1 && $value['msg_incoming']==0){
					$messages .= '
					<div class="kt-chat__message kt-chat__message--right">
						<div class="kt-chat__user">
							<span class="kt-chat__datetime">'.$value['msg_stamp'].'</span>
							<a href="javascript::" class="kt-chat__username">You</a>
							<span class="kt-media kt-media--circle kt-media--sm">
								<img src="assets/chat/media/user.png" alt="image">
							</span>
						</div>
						<div class="kt-chat__text kt-bg-light-brand">
							'.$value['msg_content'].'
						</div>
					</div>';
				}
			}
			$data['messages'] = $messages;
			$data['response'] = true;
		}
		echo json_encode($data);
	}

	public function get_chat($lead_id='',$user_replies = '')
	{
		if(empty($lead_id)){
			return false;
		}
    	$where = "conversations.user_id = '".$this->user_id."' AND conversations.lead_id = '".$lead_id."'";
    	if(!empty($user_replies)){
    		$where .= $user_replies;
    	}
	    $joins = array(
            '0' => array('table_name' => 'messages messages',
                'join_on' => 'messages.conv_id = conversations.conv_id',
                'join_type' => 'right'
            ),
            '1' => array('table_name' => 'leads leads',
                'join_on' => 'leads.lead_id = conversations.lead_id',
                'join_type' => 'right'
            )
        );
        $from_table = "conversations conversations";
        $select_from_table = 'messages.msg_id,messages.msg_content,messages.msg_incoming,messages.msg_sent,messages.msg_stamp,leads.first_name,leads.last_name';
        $chat = $this->conversations->get_by_join($select_from_table, $from_table, $joins, $where, "", "", '', '', '', '', "", "");
        if(!empty($chat)){
			foreach($chat as $key => $value){
				$update = ['msg_read'=>1];
				$this->messages->update_by('msg_id',$value['msg_id'],$update);
			}
		}
        return $chat;
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
			$msg_sid = send_twilio_sms("",$to,TWILIO_NUMBER, $rcp['msg_content']);
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
	
}