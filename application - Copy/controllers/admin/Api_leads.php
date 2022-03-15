<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Api_leads extends CI_Controller 
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
		$this->selected_tab = 'api_leads';
		$this->layout = 'template';
        $this->load->model('api_leads_model', 'api_leads');
        $this->load->model('users_model', 'users');
		if(!$this->session->userdata('admin')) {
        $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
        redirect('admin/auth/');
        }
	}
	
	public function index()
	{
		$data = [];
        $users = $this->api_leads->get_where('user_id','', true, '','', 'user_id');
        $all_users = [];
        foreach ($users as $user) {
            $where = "users.user_id = '".$user['user_id']."'";
            $su = $this->users->get_where('user_id,first_name,last_name',$where, true, '','', '');
            if(!empty($su)){
                $all_users[] = $su[0];
            }
        }
        $data['users'] = $all_users;
		$this->load->view('admin/api_leads/index', $data);
	}

	public function get_api_leads()
    {
        $this->layout = '';
        $like = array();
        $api_leads_array = [];

        $orderByColumnIndex = $_POST['order'][0]['column'];
        $orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
        $orderType = $_POST['order'][0]['dir'];
        $offset = $this->input->post('start');
        $limit = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $_POST['search']['value'];
        $status_filter = $this->input->post('status_filter');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        $user_id = $this->input->post('user_id');
        
        if( (isset($from_date) && $from_date != '') && (isset($to_date) && $to_date != '') ){
            $from_date = $from_date.' 00:00:00';
            $to_date = $to_date.' 23:00:00';
            $where = "api_leads.created_at BETWEEN '".$from_date."' AND '".$to_date."'";
        }
        else{
            $where = "api_leads.api_lead_id > 0";
        }

        if( ($from_date != '' && $to_date == '') || ($to_date != '' && $from_date == '') ) {
           $date = ($from_date!='')?$from_date:$to_date;
           $where .= " AND (api_leads.created_at  LIKE CONCAT('%','".$date."' ,'%') )";
        }
        $api_leads_count = $this->api_leads->count_rows($where);

        if(isset($status_filter) && $status_filter != ''){
            $where .= " AND api_leads.status ='".$status_filter."'";
        }

        if (isset($search) && $search != '') {
            $where .= " AND (leads.first_name  LIKE CONCAT('%','" . $search . "' ,'%') OR leads.last_name LIKE CONCAT('%','" . $search . "' ,'%') OR leads.contact_mobile LIKE CONCAT('%','" . $search . "' ,'%') OR leads.email LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

        $joins = array(
            '0' => array('table_name' => 'leads leads',
                'join_on' => 'leads.lead_id = api_leads.lead_id',
                'join_type' => 'inner'
            ),
            '1' => array('table_name' => 'users users',
                'join_on' => 'users.user_id = api_leads.user_id',
                'join_type' => 'left'
            )
        );
        $from_table = "api_leads api_leads";
        $select_from_table = 'api_leads.api_lead_id,api_leads.status as status,api_leads.created_at as created_at ,api_leads.failed_attempts,leads.first_name as first_name,leads.last_name as last_name,leads.contact_mobile as contact_mobile,leads.email as email,users.first_name as user_first_name,users.last_name as user_last_name';
        $api_leads_data = $this->api_leads->get_by_join($select_from_table, $from_table, $joins, $where, "leads.".$orderByColumn, "leads.".$orderType, '', '', '', '', $limit, $offset);
        //debug($api_leads_data,true);
        $api_leads_count_rows = $this->api_leads->get_by_join_total_rows('*', $from_table, $joins, $where, "leads.".$orderByColumn, "leads.".$orderType, '', '', '', '', '', '');

        if (isset($api_leads_data)) {
            foreach ($api_leads_data as $item) {
                $single_field['first_name'] = $item['first_name'];
                $single_field['last_name'] = $item['last_name'];
                $single_field['email'] = $item['email'];
                $single_field['contact_mobile'] = $item['contact_mobile'];
                $single_field['status'] = ($item['status']==1)?'Success':'Failed';
                $single_field['to_user'] = $item['user_first_name'].' '.$item['user_last_name'];
                $single_field['failed_attempts'] = ($item['failed_attempts']==1)?"Failed":'Success';
                $single_field['created_at'] = $item['created_at'];
                $api_leads_array[] = $single_field;
            }
            $data['draw'] = $draw;
            $data['recordsTotal'] = $api_leads_count;
            $data['recordsFiltered'] = $api_leads_count_rows;
            $data['data'] = $api_leads_array;
        } else {
            $data['draw'] = $draw;
            $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = '';
        }
        echo json_encode($data);
    }

}