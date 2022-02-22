<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Auth extends CI_Controller 
{
	
	/**
	* @var stirng
	* @access Public
	*/
	public $selected_tab = '';
	
	/** 
	* Controller constructor
	* 
	* @access public 
	*/
	public function __construct()
	{
		parent::__construct();
		$this->selected_tab = 'auth';
		$this->layout = 'login';
		$this->load->model('admin_model', 'admin');
		$this->load->model('users_model', 'users');
	}
	
	public function index()
	{
		if($this->session->userdata('admin')){
	        redirect('admin/dashboard/');
	    }

		$data = [];
		$this->load->view('admin/auth/login', $data);
	}
	
	public function process_login()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$data['notifiy_error'] = false;
		$data['email_error'] = '';
		$data['password_error'] = '';
		$this->form_validation->set_rules('email','email','required|trim|valid_email');
		$this->form_validation->set_rules('password','Password','required|trim');
		if($this->form_validation->run()===TRUE){
			$data = $this->input->post();
			$password = md5($data['password']);
			$where = "email = '".$data['email']."' AND password = '".$password."' AND user_role = '1'";
		    $user = $this->users->get_where('*', $where, true, '' , '', '');
		    if(empty($user)){
                $data['notifiy_error'] = true;
                $data['notifiy_msg'] = "Invalid email or password.";
		    }
		    else{
		    	$user = $user[0];
		    	if($user['status']==2){
		    		$data['notifiy_error'] = true;
                    $data['notifiy_msg'] = "your account is blocked.You cannot login.";
		    	}
		    	else{
		    		$data['response'] = true;
		    		$this->session->set_userdata(array('admin_id'=>$user['user_id'],'admin_email'=>$user['email'],'admin'=>$user['user_id']));
		    	}
		    }	
		}
		else{
			$data['email_error'] = form_error('email');
			$data['password_error'] = form_error('password');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}
		
	public function logout()
	{
		$this->session->sess_destroy();
		redirect(base_url('admin/auth/'));
	}
	
}