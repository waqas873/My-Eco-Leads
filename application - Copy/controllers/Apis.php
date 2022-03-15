<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Apis extends CI_Controller 
{
	
	/**
	* @var stirng
	* @access Public
	*/
	public $selected_tab = '';

	public $user_id = '';
	
	/** 
	* Controller constructor
	* 
	* @access public 
	*/
	public function __construct()
	{
		parent::__construct();
		$this->selected_tab = 'user_api';
		$this->layout = 'user_dashboard';
		$this->load->model('apis_model', 'api');
		$this->load->model('user_api_model', 'user_api');
		if(!$this->session->userdata('user')){
		    $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
		    redirect('sign-in');
	    }
	    $this->user_id = $this->session->userdata('user_id');
	}
	
	public function index()
	{
		$data = [];
		$where = "status = 1";
        $apis = $this->api->get_where('api_id,api_name', $where, true, '' , '', '');
        $arr = [];
        if(!empty($apis)){
        	foreach($apis as $api){
        		$single = array();
        		$single = $api;
        		$single['is_checked'] = '';
        		$where = "api_id = '".$api['api_id']."' AND user_id = '".$this->user_id."'";
                $result = $this->user_api->get_where('', $where, true, '' , '', '');
                if(!empty($result)){
                	$single['is_checked'] = true; 
                	$result = $result[0];
                	$hupl = json_decode($result['api_settings']);
                	if($api['api_id']==3){
                		$single['zeavo_api_key'] = $hupl->api_key;
	                	$single['zeavo_lead_group_id'] = $hupl->lead_group_id;
		        		$single['zeavo_api_url'] = $hupl->api_url;
		        		$single['zeavo_status'] = $result['status'];
                	}
                	if($api['api_id']==4){
                		$single['abbotts_api_token'] = $hupl->api_token;
	                	$single['abbotts_lead_group_id'] = $hupl->lead_group_id;
	                	$single['abbotts_team_id'] = $hupl->team_id;
		        		$single['abbotts_api_url'] = $hupl->api_url;
		        		$single['abbotts_status'] = $result['status'];
                	}
                	if($api['api_id']==2){
                		$single['hubsolv_api_key'] = $hupl->hubsolv_api_key;
	                	$single['hubsolv_username'] = $hupl->username;
		        		$single['hubsolv_password'] = $hupl->password;
		        		$single['hubsolv_api_url'] = $hupl->api_url;
		        		$single['hubsolv_status'] = $result['status'];
		        		$single['hubsolv_campaignid'] = (isset($hupl->campaignid))?$hupl->campaignid:'';
		        		$single['hubsolv_lead_source'] = (isset($hupl->lead_source))?$hupl->lead_source:'';
                	}
                    if($api['api_id']==5){
                        $single['byte_campaign_id'] = $hupl->campaign_id;
                        $single['byte_sid'] = $hupl->sid;
                        $single['byte_status'] = $result['status'];
                    }
	        		
                }
                $arr[] = $single;
        	}
        }

        $data['apis'] = $arr;
		$this->load->view('user_api/index', $data);
	}

	public function process_api()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$this->form_validation->set_rules('hubsolv_api_key','hubsolv api key','required|trim');
		$this->form_validation->set_rules('username','username','required|trim');
		$this->form_validation->set_rules('password','password','required|trim');
		$this->form_validation->set_rules('api_url','api_url','required|trim');
		if($this->form_validation->run()===TRUE){
			$update = false;
			$api_id = $this->input->post('api_id');
			$user_api = user_api($this->user_id,$api_id);
			if(!empty($user_api)){
                $user_api_id = $user_api['user_api_id'];
                $update = true;
			}
			$record = $this->input->post();
			$status = $this->input->post('status');
			unset($record['api_id']);
            unset($record['status']);

            $url = $record['api_url'];
			$parse = parse_url($url);
			if(isset($parse['host'])){
				$record['api_url'] = $parse['host'];
			}

			$api_settings = json_encode($record);
			$record = ['api_id'=>$api_id,'user_id'=>$this->user_id,'api_settings'=>$api_settings,'status'=>$status];

			if($update){
                $this->user_api->update_by('user_api_id',$user_api_id,$record);
                $data['msg']="API credentials has been updated successfully.";
			}
			else{
				$this->user_api->save($record);
				$data['msg']="API credentials has been saved successfully.";
			}
			$data['response'] = true;
		}
		else{
			$data['hubsolv_api_key_error'] = form_error('hubsolv_api_key');
			$data['username_error'] = form_error('username');
            $data['password_error'] = form_error('password');
            $data['api_url_error'] = form_error('api_url');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function hubsolv_api_test()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$data['api_response'] = false;
		$this->form_validation->set_rules('firstname','firstname','required|trim');
		$this->form_validation->set_rules('lastname','lastname','required|trim');
		$this->form_validation->set_rules('email','email','required|trim|valid_email');
		$this->form_validation->set_rules('phone_mobile','mobile number','required|trim|numeric|min_length[10]|max_length[11]');
		if($this->form_validation->run()===TRUE){
			$formData = $this->input->post();
			$where = "user_id = '".$this->user_id."' AND api_id = '2' ";
            $result = $this->user_api->get_where('*', $where, true, '' , '', '');
            if(!empty($result)){
            	$result = $result[0];
            	$hupl = json_decode($result['api_settings']);
            	$hub_url =  $hub_url = $this->clean_url($hupl->api_url);
//		        if(filter_var($hupl->api_url, FILTER_VALIDATE_URL)){
//		            $hub_url = $hupl->api_url;
//		        }
//		        else{
//		            $hub_url = 'https://'.$hupl->api_url.'/api/client/format/json';
//		        }
                $hub_url = 'https://'.$hub_url.'/api/client/format/json';
		        $post_hub = [];
		        $post_hub = $formData;
		        $post_hub['HUBSOLV-API-KEY']=$hupl->hubsolv_api_key;
		        $post_hub['lead_source']=(!empty($hupl->lead_source)?$hupl->lead_source:"Debt Monster");
		        if(!empty($hupl->campaignid)) {
		            $post_hub['campaignid'] = $hupl->campaignid; 
		        }
		        $username_pass = $hupl->username.':'.$hupl->password;
                $base64 = base64_encode($username_pass);
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
		        if($decode_result->status=='success_post'){
		        	$data['api_response'] = true;
		        }
		        //debug($decode_result,true);
                $data['response'] = true;
            }
		}
		else{
			$data['firstname_error'] = form_error('firstname');
			$data['lastname_error'] = form_error('lastname');
            $data['email_error'] = form_error('email');
            $data['phone_mobile_error'] = form_error('phone_mobile');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}
    public function blueVision_api_test()
    {

        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
            exit('No direct script access allowed');
        }
        $data['response'] = false;
        $data['api_response'] = false;
        $this->form_validation->set_rules('firstname','firstname','required|trim');
        $this->form_validation->set_rules('lastname','lastname','required|trim');
        $this->form_validation->set_rules('email','email','required|trim|valid_email');
        $this->form_validation->set_rules('phone_mobile','mobile number','required|trim|numeric|min_length[10]|max_length[11]');
        if($this->form_validation->run()===TRUE){
            $where = "user_id = '".$this->user_id."' AND api_id = '4' ";
            $result = $this->user_api->get_where('*', $where, true, '' , '', '');
            if(!empty($result)){
                $result = $result[0];
                $hupl = json_decode($result['api_settings']);

                $hub_url = $this->clean_url($hupl->api_url);
//                if(filter_var($hupl->api_url, FILTER_VALIDATE_URL)){
//                    $hub_url = $hupl->api_url;
//                }
//                else{
//                    $hub_url = 'https://'.$hupl->api_url.'/webforms/create';
//                }
                $hub_url = 'https://'.$hub_url.'/webforms/create';
                $post_data = array(
                    'token' => $hupl->api_token,
                    'first_name' => $this->input->post('firstname'),
                    'last_name' => $this->input->post('lastname'),
                    'mobile' =>$this->input->post('phone_mobile'),
                    'email' => $this->input->post('email'),
                    'country'=>"UK",
                    'consent_to_contact'=>1,
                    'consent_mode' => '1,2,3,4,5,6,7'
                );
                $ch = curl_init();
                curl_setopt( $ch, CURLOPT_URL, $hub_url );
                curl_setopt( $ch, CURLOPT_CUSTOMREQUEST, 'POST' );
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch,CURLOPT_SSL_VERIFYPEER, false);
                curl_setopt( $ch, CURLOPT_POSTFIELDS,  $post_data);
                curl_setopt( $ch, CURLOPT_HTTPHEADER ,  array("Content-Type:multipart/form-data"));
                $response = curl_exec( $ch );
                curl_close( $ch );
                $resp=json_decode($response);
                if(!empty($resp->case_number) && $resp->webform_status=='Save Successful'){
                    $data['api_response'] = true;
                    $data['response']=true;
                }
                else{
                    $data['api_response'] = false;
                }
            }
        }
        else{
            $data['firstname_error'] = form_error('firstname');
            $data['lastname_error'] = form_error('lastname');
            $data['email_error'] = form_error('email');
            $data['phone_mobile_error'] = form_error('phone_mobile');
        }
        $data['regenerate_token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
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
	public function process_zeavo_api()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$this->form_validation->set_rules('api_key','api key','required|trim');
		$this->form_validation->set_rules('lead_group_id','lead group id','required|trim');
		$this->form_validation->set_rules('api_url','api url','required|trim');
		if($this->form_validation->run()===TRUE){
			$update = false;
			$api_id = $this->input->post('api_id');
			$user_api = user_api($this->user_id,$api_id);
			if(!empty($user_api)){
                $user_api_id = $user_api['user_api_id'];
                $update = true;
			}
			$record = $this->input->post();
			$status = $this->input->post('status');
			unset($record['api_id']);
            unset($record['status']);
			$api_settings = json_encode($record);
			$record = ['api_id'=>$api_id,'user_id'=>$this->user_id,'api_settings'=>$api_settings,'status'=>$status];
			if($update){
                $this->user_api->update_by('user_api_id',$user_api_id,$record);
                $data['msg']="API credentials has been updated successfully.";
			}
			else{
				$this->user_api->save($record);
				$data['msg']="API credentials has been saved successfully.";
			}
			$data['response'] = true;
		}
		else{
			$data['api_key_error'] = form_error('api_key');
			$data['lead_group_id_error'] = form_error('lead_group_id');
            $data['api_url_error'] = form_error('api_url');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function process_abbotts_api()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$this->form_validation->set_rules('api_token','api token','required|trim');
		$this->form_validation->set_rules('api_url','api url','required|trim');
		if($this->form_validation->run()===TRUE){
			$update = false;
			$api_id = $this->input->post('api_id');
			$user_api = user_api($this->user_id,$api_id);
			if(!empty($user_api)){
                $user_api_id = $user_api['user_api_id'];
                $update = true;
			}
			$record = $this->input->post();
			$status = $this->input->post('status');
			unset($record['api_id']);
            unset($record['status']);
			$api_settings = json_encode($record);
			$record = ['api_id'=>$api_id,'user_id'=>$this->user_id,'api_settings'=>$api_settings,'status'=>$status];
			if($update){
                $this->user_api->update_by('user_api_id',$user_api_id,$record);
                $data['msg']="API credentials has been updated successfully.";
			}
			else{
				$this->user_api->save($record);
				$data['msg']="API credentials has been saved successfully.";
			}
			$data['response'] = true;
		}
		else{
			$data['api_token_error'] = form_error('api_token');
            $data['api_url_error'] = form_error('api_url');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}
    public function byte_lead_api()
    {

        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
            exit('No direct script access allowed');
        }
        $data['response'] = false;
        $this->form_validation->set_rules('campaign_id','campaign Id','required|trim');
        $this->form_validation->set_rules('sid','SID','required|trim');

        if($this->form_validation->run()===TRUE){
            $update = false;
            $api_id = $this->input->post('api_id');
            $user_api = user_api($this->user_id,$api_id);
            if(!empty($user_api)){
                $user_api_id = $user_api['user_api_id'];
                 $update = true;
            }
            $record = $this->input->post();
            $status = $this->input->post('status');
            unset($record['api_id']);
            unset($record['status']);
            $api_settings = json_encode($record);
            $record = ['api_id'=>$api_id,'user_id'=>$this->user_id,'api_settings'=>$api_settings,'status'=>$status];
            if($update){
                $this->user_api->update_by('user_api_id',$user_api_id,$record);
                $data['msg']="API credentials has been updated successfully.";
            }
            else{
                $this->user_api->save($record);
                $data['msg']="API credentials has been saved successfully.";
            }
            $data['response'] = true;
        }
        else{
            $data['campaign_id'] = form_error('campaign_id');
            $data['sid'] = form_error('sid');
        }
        $data['regenerate_token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }
	public function check_api()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$api_id = $this->input->post('api_id');
		$user_api = user_api($this->user_id,$api_id);
		if(!empty($user_api)){
            $data['response'] = true;
		}
		echo json_encode($data);
	}
	
}