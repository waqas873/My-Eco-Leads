<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Dashboard extends CI_Controller 
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
	public $user_id = '';

	public function __construct()
	{
		parent::__construct();
		$this->selected_tab = 'dashboard';
		$this->layout = 'user_dashboard';
		$this->load->model('users_model', 'users');
		if(!$this->session->userdata('user')) {
	    $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
	    redirect('sign-in');
	    }
	    $this->user_id = $this->session->userdata('user_id');
	}

	public function index()
	{
		$data = [];
		$where = "user_id = '".$this->user_id."' AND login='0'";
		$user = $this->users->get_where('*', $where, true, '' , '', '');
		if(!empty($user)){
			$user = $user[0];
			$update = ['login'=>1];
			$this->users->update_by('user_id',$this->user_id,$update);
			redirect('auth/change_password/company_registration');
		}
		//$this->load->view('home/dashboard', $data);
		redirect('leads/');
	}

	public function change_password_success($token='')
	{
		if($token=='cps_no' || $token=='company_registration'){
	        $this->session->set_flashdata('success_message', "Your password has been changed successfully.");
		}
		($token=='cps_no')?redirect('dashboard/'):redirect('companies/');
	}
	
}