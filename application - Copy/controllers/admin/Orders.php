<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Orders extends CI_Controller 
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
		$this->selected_tab = 'orders';
		$this->layout = 'template';
        $this->load->model('orders_model', 'orders');
        $this->load->model('invoices_model', 'invoices');
        $this->load->model('users_model', 'users');
		if(!$this->session->userdata('admin')){
            $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
            redirect('admin/auth/');
        }
	}

    public function index($user_id='')
    {
        $user_id = decodeBase64($user_id);
        if(!is_numeric($user_id)){
            $this->session->set_flashdata('error_message', 'Invalid request to see orders.');
            redirect('admin/users/');
        }
        $data = [];
        $data['user_id'] = $user_id;
        $this->load->view('admin/orders/index', $data);
    }

    public function manual_orders()
    {
        $this->selected_tab = 'manual_orders';
        $data = [];
        $this->load->view('admin/orders/manual_orders', $data);
    }

    public function manual_add()
    {
        $this->selected_tab = 'manual_orders';
        $data = [];
        $where = "user_role = '0'";
        $data['users'] = $this->users->get_where('*', $where, true, '' , '', '');
        $this->load->view('admin/orders/manual_add', $data);
    }

    public function manual_update($order_id='')
    {
        if(empty($order_id)){
            $this->session->set_flashdata('error_message','Invalid request to update order.');
            redirect('admin/orders/manual_orders/');
        }

        $this->selected_tab = 'manual_orders';
        $data = [];
        $where = "user_role = '0'";
        $data['users'] = $this->users->get_where('*', $where, true, '' , '', '');
        
        $where = "orders.order_id = '".$order_id."'";
        $joins = array(
            '0' => array('table_name' => 'invoices invoices',
                'join_on' => 'invoices.order_id = orders.order_id',
                'join_type' => 'left'
            )
        );
        $from_table = "orders orders";
        $select_from_table = 'orders.*,invoices.amount as amount';
        $result = $this->orders->get_by_join($select_from_table, $from_table, $joins, $where, '', '', '', '', '', '', '', '');
        if(empty($result)){
            $this->session->set_flashdata('error_message','Invalid request to update order.');
            redirect('admin/orders/manual_orders/');
        }
        $data['order'] = $result[0];

        $this->load->view('admin/orders/manual_update', $data);
    }

    public function process_manual_add()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $data['total_leads_error'] = '';
        $data['user_id_error'] = '';
        $data['email_error'] = '';
        $data['amount_error'] = '';
        $this->form_validation->set_rules('total_leads','total leads','required|trim|numeric');
        $this->form_validation->set_rules('user_id','user name','required');
        $this->form_validation->set_rules('email','user email','required|trim');
        $this->form_validation->set_rules('amount','total amount','required|trim|numeric');
        if($this->form_validation->run()===TRUE){
            if($this->input->post('total_leads')>0){
                $order = ['user_id'=>$this->input->post('email'),'interest_id'=>1,'total_leads'=>$this->input->post('total_leads'),'remaining_leads'=>$this->input->post('total_leads'),'is_manual'=>1];
                if($order_id = $this->orders->save($order)){
                    $invoice = ['order_id'=>$order_id,'user_id'=>$this->input->post('email'),'amount'=>$this->input->post('amount'),'currency'=>'gbp','is_manual'=>1];
                    $this->invoices->save($invoice);
                    $data['response'] = true;
                    $this->session->set_userdata('order_added','yes');
                    $data['url'] = base_url('admin/orders/manual_orders/');
                }
            }
            else{
                $data['total_leads_error'] = "Invalid order request";
            }
        }
        else{
            $data['total_leads_error'] = form_error('total_leads');
            $data['user_id_error'] = form_error('user_id');
            $data['email_error'] = form_error('email');
            $data['amount_error'] = form_error('amount');
        }
        $data['regenerate_token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

    public function process_manual_update()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $data['total_leads_error'] = '';
        $data['user_id_error'] = '';
        $data['email_error'] = '';
        $data['amount_error'] = '';
        $this->form_validation->set_rules('order_id','','required|trim|numeric');
        $this->form_validation->set_rules('total_leads','total leads','required|trim|numeric');
        $this->form_validation->set_rules('user_id','user name','required');
        $this->form_validation->set_rules('email','user email','required|trim');
        $this->form_validation->set_rules('amount','total amount','required|trim|numeric');
        if($this->form_validation->run()===TRUE){
            if($this->input->post('total_leads')>0){
                $order_id = $this->input->post('order_id');
                $order = ['user_id'=>$this->input->post('email'),'interest_id'=>1,'total_leads'=>$this->input->post('total_leads'),'remaining_leads'=>$this->input->post('total_leads'),'is_manual'=>1];
                $this->orders->update_by('order_id',$order_id,$order);
                $invoice = ['user_id'=>$this->input->post('email'),'amount'=>$this->input->post('amount')];
                $this->invoices->update_by('order_id',$order_id,$invoice);
                $data['response'] = true;
                $this->session->set_userdata('order_updated','yes');
                $data['url'] = base_url('admin/orders/manual_orders/');
            }
            else{
                $data['total_leads_error'] = "Invalid order request";
            }
        }
        else{
            $data['total_leads_error'] = form_error('total_leads');
            $data['user_id_error'] = form_error('user_id');
            $data['email_error'] = form_error('email');
            $data['amount_error'] = form_error('amount');
        }
        $data['regenerate_token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

    public function leads_amount()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $this->form_validation->set_rules('total_leads','total leads','required|trim|numeric');
        if($this->form_validation->run()===TRUE){
            $total_leads = $this->input->post('total_leads');
            if($total_leads>0){
                $lead_price = lead_price();
                $data['amount'] = $lead_price*$total_leads;
                $data['response'] = true;
            }
            else{
                $data['msg'] = "Invalid request";
            }
        }
        else{
            $data['msg'] = form_error('total_leads');
        }
        $data['regenerate_token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

    public function get_orders()
    {
        $this->layout = '';
        $like = array();
        $orders_array = [];

        $orderByColumnIndex = $_POST['order'][0]['column'];
        $orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
        $orderType = $_POST['order'][0]['dir'];
        $offset = $this->input->post('start');
        $limit = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $_POST['search']['value'];
        $user_id = $this->input->post('user_id');
        $status_filter = $this->input->post('status_filter');
        
        $where = "orders.user_id = '".$user_id."'";
        $orders_count = $this->orders->count_rows($where);

        if(isset($status_filter) && $status_filter != ''){
            $where .= " AND orders.status ='".$status_filter."'";
        }

        if (isset($search) && $search != '') {
            $where .= " AND (orders.total_leads  LIKE CONCAT('%','" . $search . "' ,'%') OR orders.remaining_leads LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

        $joins = array(
            '0' => array('table_name' => 'invoices invoices',
                'join_on' => 'invoices.order_id = orders.order_id',
                'join_type' => 'left'
            )
        );
        $from_table = "orders orders";
        $select_from_table = 'orders.*,invoices.amount as order_amount';
        $orders_data = $this->orders->get_by_join($select_from_table, $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', $limit, $offset);
        //debug($orders_data,true);
        $orders_count_rows = $this->orders->get_by_join_total_rows('*', $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', '', '');

        if (isset($orders_data)) {
            foreach ($orders_data as $item) {
                $single_field['total_leads'] = $item['total_leads'];
                $single_field['delivered_leads'] = $item['total_leads']-$item['remaining_leads'];
                $single_field['remaining_leads'] = $item['remaining_leads'];
                $single_field['order_amount'] = "<span>&#163;</span>".$item['order_amount'];
                $single_field['status'] = ($item['status']==1)?'Completed':'Pending';
                $single_field['created_at'] = $item['created_at'];
                $orders_array[] = $single_field;
            }
            $data['draw'] = $draw;
            $data['recordsTotal'] = $orders_count;
            $data['recordsFiltered'] = $orders_count_rows;
            $data['data'] = $orders_array;
        } else {
            $data['draw'] = $draw;
            $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = '';
        }
        echo json_encode($data);
    }

    public function get_manual_orders()
    {
        $this->layout = '';
        $like = array();
        $orders_array = [];

        $orderByColumnIndex = $_POST['order'][0]['column'];
        $orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
        $orderType = $_POST['order'][0]['dir'];
        $offset = $this->input->post('start');
        $limit = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $_POST['search']['value'];
        $status_filter = $this->input->post('status_filter');
        
        $where = "orders.is_manual = '1'";
        $orders_count = $this->orders->count_rows($where);

        if(isset($status_filter) && $status_filter != ''){
            $where .= " AND orders.status ='".$status_filter."'";
        }

        if (isset($search) && $search != '') {
            $where .= " AND (orders.total_leads  LIKE CONCAT('%','" . $search . "' ,'%') OR orders.remaining_leads LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

        $joins = array(
            '0' => array('table_name' => 'invoices invoices',
                'join_on' => 'invoices.order_id = orders.order_id',
                'join_type' => 'left'
            ),
            '1' => array('table_name' => 'users users',
                'join_on' => 'users.user_id = orders.user_id',
                'join_type' => 'left'
            )
        );
        $from_table = "orders orders";
        $select_from_table = 'orders.*,invoices.amount as order_amount,users.first_name,users.last_name,users.email';
        $orders_data = $this->orders->get_by_join($select_from_table, $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', $limit, $offset);
        //debug($orders_data,true);
        $orders_count_rows = $this->orders->get_by_join_total_rows('*', $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', '', '');

        if (isset($orders_data)) {
            foreach ($orders_data as $item) {
                $single_field['total_leads'] = $item['total_leads'];
                $single_field['user'] = $item['first_name'].' '.$item['last_name'].'<span class="small_email"> &nbsp;('.$item['email'].')</span>';
                $single_field['delivered_leads'] = $item['total_leads']-$item['remaining_leads'];
                $single_field['remaining_leads'] = $item['remaining_leads'];
                $single_field['order_amount'] = "<span>&#163;</span>".$item['order_amount'];
                $single_field['status'] = ($item['status']==1)?'Completed':'Pending';
                $single_field['edit'] = '<a href="'.base_url('admin/orders/manual_update/'.$item['order_id']).'">Edit</a>';
                $single_field['created_at'] = $item['created_at'];
                $orders_array[] = $single_field;
            }
            $data['draw'] = $draw;
            $data['recordsTotal'] = $orders_count;
            $data['recordsFiltered'] = $orders_count_rows;
            $data['data'] = $orders_array;
        } else {
            $data['draw'] = $draw;
            $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = '';
        }
        echo json_encode($data);
    }

}