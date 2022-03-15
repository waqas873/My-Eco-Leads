<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Lists extends CI_Controller 
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
		$this->selected_tab = 'dashboard';
		$this->layout = 'template';
		$this->load->model('users_model', 'users');
        $this->load->model('lists_model', 'lists');
        $this->load->model('interests_model', 'interests');
		if(!$this->session->userdata('admin')) {
        $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
        redirect('admin/auth/');
        }
	}
	
	public function index()
	{
		$data = [];
		$this->load->view('admin/lists/index', $data);
	}

    public function add()
    {
        $data = [];
        $where = "is_removed='0'";
        $data['interests'] = $this->interests->get_where('*', $where, true, '' , '', '');
        $this->load->view('admin/lists/add', $data);
    }

    public function process_add()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $this->form_validation->set_rules('list_name','first name','required|trim');
        $this->form_validation->set_rules('crm_id','crm','required|numeric');
        $this->form_validation->set_rules('list_category','category','required');
        if($this->form_validation->run()===TRUE){
            $list = $this->input->post();
            if($list_id = $this->lists->save($list)) {
                $data['response'] = true;
            }
        }
        else{
            $data['list_name_error'] = form_error('list_name');
            $data['crm_id_error'] = form_error('crm_id');
            $data['list_category_error'] = form_error('list_category');
        }
        $data['regenerate_token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

    public function update($list_id=0)
    {
        $this->layout = " ";
        $data = [];
        if(!$this->input->is_ajax_request()){
            exit;
        }
        $data['response'] = false;
        $where = "list_id='".$list_id."'";
        $list = $this->lists->get_where('*', $where, true, '' , '', '');
        if(!empty($list)){
            $data['response'] = true;
            $list = $list[0];
            $where = "is_removed='0'";
            $interests = $this->interests->get_where('*', $where, true, '' , '', '');
            $data['list'] = $list;
            $status_active = ($list['status']==1)?"selected":'';
            $status_inactive = ($list['status']==0)?"selected":'';
            $status_dropdown = '<option '.$status_active.' value="1">Active</option><option '.$status_inactive.' value="0">Inactive</option>';
            $data['status'] = $status_dropdown;
            $category_dropdown = '<option value="">Choose</option>';
            foreach ($interests as $row) {
                $selected = ($row['interest_id']==$list['list_category'])?"selected":'';
                $category_dropdown .= '<option '.$selected.' value="'.$row['interest_id'].'">'.$row['name'].'</option>';
            }
            $data['list_category'] = $category_dropdown;
        }
        echo json_encode($data);
    }

    public function process_update()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $list_id = $this->input->post('list_id');
        $this->form_validation->set_rules('list_name','first name','required|trim');
        $this->form_validation->set_rules('crm_id','crm','required|numeric');
        $this->form_validation->set_rules('list_category','category','required');
        if($this->form_validation->run()===TRUE){
            $list = $this->input->post();
            unset($list['user_id']);
            $this->lists->update_by('list_id',$list_id,$list);
            $data['response'] = true;
        }
        else{
            $data['list_name_error'] = form_error('list_name');
            $data['crm_id_error'] = form_error('crm_id');
            $data['list_category_error'] = form_error('list_category');
        }
        echo json_encode($data);
    }

	public function get_lists()
    {
        $this->layout = '';
        $like = array();
        $lists_array = [];

        $orderByColumnIndex = $_POST['order'][0]['column'];
        $orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
        $orderType = $_POST['order'][0]['dir'];
        $offset = $this->input->post('start');
        $limit = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $_POST['search']['value'];
        $status_filter = $this->input->post('status_filter');
        $lists_count = $this->lists->count_rows();

        $where = "lists.list_id > 0";

        if (isset($status_filter) && $status_filter != '') {
            $where .= " AND lists.status ='" . $status_filter . "'";
        }

        if (isset($search) && $search != '') {
            $where .= " AND (lists.list_name  LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

        $joins = array(
            '0' => array('table_name' => 'interests interests',
                'join_on' => 'interests.interest_id = lists.list_category',
                'join_type' => 'left'
            )
        );
        $from_table = "lists lists";
        $select_from_table = 'lists.*,interests.name as interest_name';
        $lists_data = $this->lists->get_by_join($select_from_table, $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', $limit, $offset);
        //debug($lists_data,true);
        $lists_count_rows = $this->lists->get_by_join_total_rows('*', $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', '', '');

        if (isset($lists_count_rows) && isset($search) && !empty($search) || (isset($status_filter) && !empty($status_filter))) {
            $recordsFiltered = $lists_count_rows;
        } else {
            $recordsFiltered = $lists_count;
        }

        if (isset($lists_data)) {
        	foreach ($lists_data as $item) {
                // $users_field['admin_id'] = '<input type="checkbox" class="checkboxes sub_checks" name="users[]" id="sub_check_' . $item['admin_id'] . '" value="' . $item['admin_id'] . '"/>';
                $users_field['list_name'] = $item['list_name'];
                $users_field['list_category'] = $item['interest_name'];
                $users_field['status'] = ($item['status']==1)?'Active':'Inactive';
                $users_field['leads'] = "<a href='".base_url('admin/leads/index/'.createBase64($item['list_id']))."'>Leads</a>";
                $users_field['created_at'] = $item['created_at'];
                $users_field['action'] = "<a href='javascript::' rel='".$item['list_id']."' class='edit_list'><i class='ace-icon fa fa-pencil bigger-130'></i></a> &nbsp; <a href='".base_url('admin/lists/delete/'.$item['list_id'])."' onclick='delete_record_dt(this); return false;'><i class='ace-icon fa fa-trash-o bigger-130'></i></a>";
                $lists_array[] = $users_field;
            }
            $data['draw'] = $draw;
            $data['recordsTotal'] = $lists_count;
            $data['recordsFiltered'] = $recordsFiltered;
            $data['data'] = $lists_array;
        } else {
            $data['draw'] = $draw;
            $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = '';
        }
        echo json_encode($data);
    }

    public function delete($list_id)
    {
        if (isset($list_id) && !empty($list_id)) {

            $this->lists->delete_by('list_id', $list_id);
            $this->session->set_flashdata('success_message', 'List has been deleted successfully');
        } else {
            $this->session->set_flashdata('error_message', 'Invalid request to delete list.');
        }
        redirect('admin/lists/');
    }
}