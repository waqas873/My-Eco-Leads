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
	public $perPage = 10;

	public function __construct()
	{
		parent::__construct();
		$this->selected_tab = 'dashboard';
		$this->layout = 'template';
		$this->load->model('users_model', 'users');
		if(!$this->session->userdata('admin')) {
	    $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
	    redirect('admin/auth/');
	    }
	}
	
	public function index()
	{
		$data = [];
		$this->load->view('admin/dashboard/index', $data);
	}
}