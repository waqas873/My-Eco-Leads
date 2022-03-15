<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Companies extends CI_Controller 
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
		$this->selected_tab = 'companies';
		$this->layout = 'template';
		$this->load->model('users_model', 'users');
		$this->load->model('companies_model', 'companies');
		if(!$this->session->userdata('admin')){
		    $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
		    redirect('admin/auth/');
	    }
	}
	
	public function index()
	{
		$data = [];
		$this->load->view('admin/companies/index', $data);
	}

    public function url_sent($url_sent='')
    {
        if($url_sent=='eW3hInjR'){
            $this->session->set_flashdata('success_message', 'Url sent successfully.');
        }
        redirect('admin/companies/');
    }

	public function get_companies()
    {
        $this->layout = '';
        $like = array();
        $companies_array = [];

        $orderByColumnIndex = $_POST['order'][0]['column'];
        $orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
        $orderType = $_POST['order'][0]['dir'];
        $offset = $this->input->post('start');
        $limit = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $_POST['search']['value'];
        $status_filter = $this->input->post('status_filter');
        
        $where = "companies.company_id > 0";
        $companies_count = $this->companies->count_rows();

        if(isset($status_filter) && $status_filter != ''){
            $where .= " AND companies.status ='" . $status_filter . "'";
        }

        if (isset($search) && $search != ''){
            $where .= " AND (companies.user_name  LIKE CONCAT('%','" . $search . "' ,'%') OR companies.name LIKE CONCAT('%','" . $search . "' ,'%') OR companies.registration_no LIKE CONCAT('%','" . $search . "' ,'%') OR companies.fca_license_no LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

        $joins = array(
            '0' => array('table_name' => 'users users',
                'join_on' => 'users.user_id = companies.user_id',
                'join_type' => 'left'
            )
        );
        $from_table = "companies companies";
        $select_from_table = 'companies.*,users.first_name as user_first_name,users.last_name as user_last_name';
        $companies_data = $this->companies->get_by_join($select_from_table, $from_table, $joins, $where, "companies.".$orderByColumn, $orderType, '', '', '', '', $limit, $offset);
        //debug($companies_data,true);
        $companies_count_rows = $this->companies->get_by_join_total_rows('*', $from_table, $joins, $where, "companies.".$orderByColumn, $orderType, '', '', '', '', '', '');

        if (isset($companies_data)) {
        	foreach ($companies_data as $item) {
                $single_field['user_name'] = $item['user_name'];
                $single_field['company_name'] = $item['name'];
                $single_field['registration_no'] = $item['registration_no'];
                $single_field['fca_license_no'] = $item['fca_license_no'];
                $single_field['address'] = $item['address'];
                $single_field['contact_no'] = $item['contact_no'];
                $single_field['status'] = ($item['status']==1)?'Approved':'Unapproved';
                $single_field['created_at'] = $item['created_at'];
                $send_url = '<a href="javascript::" class="btn btn-info btn-sm send_url" rel="'.$item['company_id'].'">Send Url</a>';
                ($item['status']==2)?$send_url='<a href="'.base_url('admin/companies/approve/'.$item['company_id']).'" class="btn btn-success btn-sm" onclick="delete_record_dt(this); return false;">Approve</a>':'';
                ($item['status']==1)?$send_url='<a href="'.base_url('admin/companies/unapprove/'.$item['company_id']).'" class="btn btn-danger btn-sm" onclick="delete_record_dt(this); return false;">Unapprove</a>':'';
                $single_field['action'] = $send_url;
                $companies_array[] = $single_field;
            }
            $data['draw'] = $draw;
            $data['recordsTotal'] = $companies_count;
            $data['recordsFiltered'] = $companies_count_rows;
            $data['data'] = $companies_array;
        } else {
            $data['draw'] = $draw;
            $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = '';
        }
        echo json_encode($data);
    }

    public function approve($company_id='')
	{
		if(!is_numeric($company_id)){
			$this->session->set_flashdata('error_message', 'Invalid request to approve company.');
			redirect('admin/companies/');
		}
		$data = ['status'=>1];
		if($this->companies->update_by('company_id',$company_id,$data)){
			$this->email_to_client($company_id,true);
            $this->session->set_flashdata('success_message', 'Company has been approved successfully');
		}
		else{
			$this->session->set_flashdata('error_message', 'Invalid request to approve company.');
		}
		redirect('admin/companies/');
	}

	public function unapprove($company_id='')
	{
		if(!is_numeric($company_id)){
			$this->session->set_flashdata('error_message', 'Invalid request to unapprove company.');
			redirect('admin/companies/');
		}
		$data = ['status'=>0];
		if($this->companies->update_by('company_id',$company_id,$data)){
			$this->email_to_client($company_id,false);
			$this->session->set_flashdata('success_message', 'Company has been unapproved successfully');
		}
		else{
			$this->session->set_flashdata('error_message', 'Invalid request to unapprove company.');
		}
		redirect('admin/companies/');
	}

    public function send_url()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $data['company_url_error'] = '';
        $this->form_validation->set_rules('company_url','url','required|trim');
        if($this->form_validation->run()===TRUE){
            $company_id = $this->input->post('company_id');
            $company_url = $this->input->post('company_url');
            $update = ['company_url'=>$company_url,'status'=>2];
            $this->companies->update_by('company_id',$company_id,$update);
            $where = "companies.company_id = '".$company_id."'";
            $joins = array(
                '0' => array('table_name' => 'users users',
                    'join_on' => 'users.user_id = companies.user_id',
                    'join_type' => 'left'
                )
            );
            $from_table = "companies companies";
            $select_from_table = 'users.first_name,users.last_name,users.email';
            $user = $this->companies->get_by_join($select_from_table, $from_table, $joins, $where, '', '', '', '', '', '', '','');
            $user = $user[0];
            $name = $user['first_name'].' '.$user['last_name'];
            $url = '<a href="'.$company_url.'">'.$company_url.'</a>';
            $message = 'Thank you for your Company Registration details.<br />We are delighted to welcome you on board as a valued Debt Monster client.<br />We have approved your application and you are one step away from your first order.<br />So we make sure our processs is compliant and you are happy to recieve our compliant leads, we need you to view and sign our online document which you can view below.<br />Dont worry, you wont need to print anything or scan anything back to us.<br /> This document can be signed online.<br />
                '.$url.'<br />Once we have received the signed document back, your account will be active and you will be prompted to make your first order of debt leads.<br />Again, welcome on board and we look forward to working with you.<br />Debt Monster Team<br />';
            send_email_to($name,$user['email'],$message,'EMAIL PRE APPROVAL (PADOC)');
            $data['response'] = true;
        }
        else{
            $data['company_url_error'] = form_error('company_url');
        }
        $data['regenerate_token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

	public function email_to_client($company_id=0,$response=true)
	{
		$where = "company_id = '".$company_id."'";
		$joins = array(
            '0' => array('table_name' => 'users users',
                'join_on' => 'users.user_id = companies.user_id',
                'join_type' => 'left'
            )
        );
        $from_table = "companies companies";
        $select_from_table = 'users.*';
        $result = $this->companies->get_by_join($select_from_table, $from_table, $joins, $where, '', '', '', '', '', '', '', '');
        if(!empty($result)){
        	$result = $result[0];
        	$name = "Dear ".ucwords($result['first_name']).' '.ucwords($result['last_name']);
        	$message = ($response)?"Your company has been approved successfully.":"Admin rejected to approve your company.";
        	send_email_to($name,$result['email'],$message,'Company Registration');
        }
        return false;
	}
	
}