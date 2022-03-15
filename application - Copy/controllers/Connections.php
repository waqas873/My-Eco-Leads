<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

//use Twilio\Rest\Client;

class Connections extends CI_Controller 
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
		$this->selected_tab = 'messages';
		$this->layout = 'user_dashboard';
		$this->load->model('users_model', 'users');
		$this->load->model('connections_model', 'connections');
		$this->load->model('messages_model', 'messages');
		if(!$this->session->userdata('user')){
	        redirect('sign-in');
	    }
	    $this->user_id = $this->session->userdata('user_id');
	}

	public function index()
	{
		//require FCPATH.'vendor/twilio/sdk/src/Twilio/autoload.php';
  //       $account_sid = 'ACdcb321fad468676cf6db19fb4acb10dd';
		// $auth_token = 'fdd8a0e7baa6164101881764ce501eba';
		// In production, these should be environment variables. E.g.:
		// $auth_token = $_ENV["TWILIO_ACCOUNT_SID"]
		// A Twilio number you own with SMS capabilities
		//$twilio_number = "+19097570028";
		//$client = new Client($account_sid, $auth_token);
		$sms = 'Helo new World';
		$to = '+923008988873';
		// $message = $client->messages->create(
		//     // Where to send a text message (your cell phone?)
		//     $to,
		//     array(
		//         'from' => $twilio_number,
		//         'body' => $sms
		//     )
		// );

		//$msg_sid = send_twilio_sms("",$to,"+19097570028", $sms);

		if(!empty($message)){
		    $save = array('msg_content'=>$sms);
		    $this->messages->save($save);
		}
	}

	public function add()
	{
		$data = [];
		$where = "user_id = '".$this->user_id."'";
		$result = $this->connections->get_where('*', $where, true, '' , '', '');
		if(!empty($result)){
			$this->session->set_flashdata('error_message', 'Sorry! you have already added a number.');
		    redirect('dashboard');
		}
		$params = array("voiceEnabled" => "true", "mmsEnabled" => "true", "smsEnabled" => "true");
		$numbers = get_phone_numbers('GB',$params);
		//debug($numbers,true);
		$dropdown = '<select name="twilio_number" class="form-control select2"><option value=" ">Select Number</option>';
		$data['numbers'] = '';
		if(!empty($numbers)){
			foreach($numbers as $number){
				$dropdown .= '<option value="'.$number->phoneNumber.'">'.$number->phoneNumber.'</option>';
			}
			$dropdown .= '</select>';
			$data['numbers'] = $dropdown;
		}
		$this->load->view('connections/add', $data);
	}

	public function fetch_numbers()
	{
	    $data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$this->form_validation->set_rules('country_code','country code','required|trim|numeric|max_length[5]');
		if($this->form_validation->run()===TRUE){
			$country_code = $this->input->post('country_code');
			$country_code = strtoupper($country_code);

			// $params = array("mmsEnabled" =>true, "smsEnabled" => true);
			// $numbers = get_phone_numbers('GB',$params);
			$params = array("voiceEnabled" => "true", "mmsEnabled" => "true", "smsEnabled" => "true");
			$numbers = get_phone_numbers('GB',$params);
			//debug($numbers,true);
			$dropdown = '<select name="twilio_number" class="form-control select2"><option value=" ">Select Number</option>';
			$data['numbers'] = '';
			if(!empty($numbers)){
				foreach($numbers as $number){
					$dropdown .= '<option value="'.$number->phoneNumber.'">'.$number->phoneNumber.'</option>';
				}
				$dropdown .= '</select>';
				$data['numbers'] = $dropdown;
			}
			$data['response'] = true;
		}
		else{
            $data['country_code_error'] = form_error('country_code');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function purchase_num()
	{
	    $data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$data['duplicate'] = false;
		$this->form_validation->set_rules('twilio_number','country code','required');
		if($this->form_validation->run()===TRUE){
			$twilio_number = $this->input->post('twilio_number');
			$where = "user_id = '".$this->user_id."'";
			$result = $this->connections->get_where('*', $where, true, '' , '', '');
			if(empty($result)){
				$where = "user_id = '".$this->user_id."'";
			    $user = $this->users->get_where('*', $where, true, '' , '', '');
			    $user = $user[0];
			    $fullname = $user['first_name'].' '.$user['last_name'];
				$tr = connectNumberFromTwilio($fullname,$twilio_number,'');
				$save = [];
				$save['user_id'] = $this->user_id;
				$save['conn_address'] = $twilio_number;
				$save['conn_msg_service_sid'] = 'ACdcb321fad468676cf6db19fb4acb10dd';
				$save['conn_sid'] = $tr['s_sid'];
				$this->connections->save($save);
			}
			else{
                $data['duplicate'] = true;
			}
			$data['response'] = true;
		}
		else{
            $data['twilio_number_error'] = form_error('twilio_number');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function searchNumbers()
    {
        $this->isAjax();
        $this->isLoggedIn();
        $account_data = $this->session->userdata('account_data');

        $area_code = ($this->uri->segment(3)) ? $this->uri->segment(3) : 0;
        $region = ($this->uri->segment(4)) ? $this->uri->segment(4) : 0;

        $params = array("voiceEnabled" => "true", "mmsEnabled" => "true", "smsEnabled" => "true");
        switch ($region) {
        case 'AU':
            $params['contains'] = '%'.$area_code.'%';
            break;

        case 'CA': case 'US' :
            $region = (in_array($area_code, self::$canadian_area_codes) ? 'CA' : 'US');
            $params['areaCode'] = $area_code;
            break;
        case 'PR':
            $params['areaCode'] = $area_code;
            break;
        case 'NZ':
            $params['contains'] = ltrim($area_code, '0').'*******';
            break;

        case 'GB':
        default:
            $params['contains'] = '%'.ltrim($area_code, '0').'%';
            break;
        }
        $data['output'] = get_phone_numbers($region, $params);
        $this->load->view('json_view', $data);
    }
	
}