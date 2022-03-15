<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

include_once('./vendor/autoload.php');

class Api_auth extends CI_Controller {
  public $selected_tab = '';

  public function __construct(){
    parent::__construct();
    $this->selected_tab = 'api_auth';
		$this->layout = 'publicsite';
		$this->load->model('leads_model', 'leads');
		$this->load->model('mobile_otp_model', 'mobile_otp');
  }

  public function is_email_exists($str){
		if(!empty($str)){
      $where = "leads.email='".$str."'";
			$user = $this->leads->get_where('*', $where, true, '', '1', '');
			if (empty($user)){
				return TRUE;
			}else {
				$this->form_validation->set_message('is_email_exists', 'This %s is already registered. Enter a new one.');
				return FALSE;
			}
	  }
	}

  public function send_otp(){
    $this->layout = " ";
    $data = [];
    $data['response'] = false;

    $this->form_validation->set_rules('country_code', 'Country Code', 'required|trim', array('required' => '%s is required.'));
    $this->form_validation->set_rules('mobile_no', 'Mobile No.', 'required|trim', array('required' => '%s is required.'));

    if($this->form_validation->run()===TRUE){
      $country_code = $this->input->post('country_code');
      $phone = $this->input->post('mobile_no');
      $mobile_no = $country_code.$phone;
      $otp = rand(1231,7879);
      $otp_data = array(
        'mobile_no' => $mobile_no,
        'otp' => $otp
      );
      if(!empty($otp_data)){
        try {
          $sid = "ACaa153995c93e94077c4e1d5b92c4075c";
          $token = "e8be76091dbcabdca8bc6b290d2e41ad";
          $client = new Twilio\Rest\Client($sid, $token);
          $message = $client->messages->create(
            $mobile_no,
            [
              'from' => '+16628508863',
              'body' => "Your OTP: ".$otp
            ]
          ); 
          $otp_id = $this->mobile_otp->save($otp_data);
          if(!empty($otp_id)){
            $data['response'] = true;
            $data['otp'] = $otp;
            $data['success'] = "OTP sent successfully!";
          }
        } catch (Twilio\Exceptions\RestException $e) {
          $data['failure'] = 'Please enter a correct mobile number with correct country code!';
        }
      }
    }else {
      $data['errors'] = $this->form_validation->error_array();
    }
    echo json_encode($data);
    exit;
  }

  public function validate_otp(){
    $this->layout = " ";
    $data = [];
    $data['response'] = false;

    $this->form_validation->set_rules('otp', 'OTP', 'required|trim|numeric', array('required' => '%s is required.'));
    $this->form_validation->set_rules('mobile_no', 'Mobile No.', 'required|trim', array('required' => '%s is required.'));

    if($this->form_validation->run()===TRUE){
      $otp = $this->input->post('otp');
      $mobile_no = $this->input->post('mobile_no');
      if(!empty($otp) AND !empty($mobile_no)){
        $where = "mobile_otp.otp='".$otp."' AND mobile_otp.mobile_no='".$mobile_no."'";
        $result = $this->mobile_otp->get_where('*', $where, true, '', '1', '');
        if(!empty($result)){
          $data['response'] = true;
          $data['success'] = "OTP authenticated successfully!";
        }else {
          $data['failure'] = 'OTP authentication failed! Try Again with correct mobile number and OTP.';
        }
      }
    }else {
      $data['errors'] = $this->form_validation->error_array();
    }
    echo json_encode($data);
    exit;
  }

  public function register(){
    $this->layout = " ";
    $data = [];
    $data['response'] = false;
    
    $this->form_validation->set_rules('first_name', 'First Name', 'required|trim', array('required' => '%s is required.'));
    $this->form_validation->set_rules('last_name', 'Last Name', 'required|trim', array('required' => '%s is required.'));
    $this->form_validation->set_rules('email', 'Email address', 'required|trim|valid_email|callback_is_email_exists', array('required' => '%s is required.', 'valid_email' => 'This is not a valid %s.'));
    $this->form_validation->set_rules('password', 'Password', 'required|trim', array('required' => '%s is required.'));
    $this->form_validation->set_rules('mobile_no', 'Mobile No.', 'required|trim', array('required' => '%s is required.'));
    
    if($this->form_validation->run()===TRUE){
      $first_name = $this->input->post('first_name');
      $last_name = $this->input->post('last_name');
      $email = $this->input->post('email');
      $password = $this->input->post('password');
      $mobile_no = $this->input->post('mobile_no');
      
      $register_data = array(
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'password' => md5($password),
        'contact_mobile' => $mobile_no,
        'is_app_user' => 1
      );

      if(!empty($register_data)){
        $user_id = $this->leads->save($register_data);
        if(!empty($user_id)){
          $data['response'] = true;
          $data['success'] = "Data saved successfully!";
          $data['user_id'] = $user_id;
        }
      }
    }else {
      $data['errors'] = $this->form_validation->error_array();
    }
    echo json_encode($data);
    exit;
  }

  public function login(){
    $this->layout = " ";
    $data = [];
    $data['response'] = false;

    $this->form_validation->set_rules('email', 'Email address', 'required|trim|valid_email', array('required' => '%s is required.'));
    $this->form_validation->set_rules('password', 'Password', 'required|trim', array('required' => '%s is required.'));

    if($this->form_validation->run()===TRUE){
      $email = $this->input->post('email');
      $password = md5($this->input->post('password'));
      if(!empty($email) AND !empty($password)){
        $where = "leads.email='".$email."' AND leads.password='".$password."'";
        $result = $this->leads->get_where('*', $where, true, '', '1', '');
        if(!empty($result)){
          $data['response'] = true;
          $data['user_data'] = $result[0];
        }else {
          $data['failure'] = 'Incorrect email or password!';
        }
      }
    }else {
      $data['errors'] = $this->form_validation->error_array();
    }
    echo json_encode($data);
    exit;
  }
  
}

?>