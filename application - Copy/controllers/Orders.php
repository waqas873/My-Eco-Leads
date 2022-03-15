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

    public $user_id = '';

	public function __construct()
	{
		parent::__construct();
		$this->selected_tab = 'orders';
		$this->layout = 'user_dashboard';
        $this->load->model('leads_model', 'leads');
        $this->load->model('orders_model', 'orders');
        $this->load->model('lead_order_model', 'lead_order');
        $this->load->model('invoices_model', 'invoices');
        $this->load->model('companies_model', 'companies');
		if(!$this->session->userdata('user')){
            $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
            redirect('sign-in/');
        }
        $this->user_id = $this->session->userdata('user_id');
	}

    public function index()
    {
        $data = [];
        $where = "user_id = '".$this->user_id."'";
        $result = $this->orders->get_where('SUM(total_leads) as total_orders', $where, true, '' , '', '');
        $result = $result[0];
        $data['total_orders'] = ($result['total_orders']>0)?$result['total_orders']:0;
        $result = $this->invoices->get_where('SUM(amount) as orders_amount', $where, true, '' , '', '');
        $result = $result[0];
        $data['orders_amount'] = ($result['orders_amount']>0)?$result['orders_amount']:0;
        $this->load->view('orders/index', $data);
    }
	
	public function add()
	{
        $where = "user_id = '".$this->user_id."'";
        $company = $this->companies->get_where('*', $where, true, '' , '', '');
        if(empty($company)){
            $this->session->set_flashdata('error_message', 'please register your company first and than you can place an order.');
            redirect('orders/');
        }
        if($company[0]['status']==0){
            $this->session->set_flashdata('error_message', 'You can place order after your company apporvel by admin.');
            redirect('orders/');
        }
        $data = [];
		$this->load->view('orders/add', $data);
	}

    public function leads($order_id='')
    {
        if(empty($order_id)){
            redirect('orders');
        }
        $order_id = decodeBase64($order_id);
        $data = [];
        $where = "order_id = '".$order_id."'";
        $order = $this->orders->get_where('*', $where, true, '' , '', '');
        if(!empty($order)){
            $data['order'] = $order[0];
        }
        $this->load->view('orders/leads', $data);
    }

    public function order()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $user_id = $this->session->userdata('user_id');
        $data['response'] = false;
        $data['total_leads_error'] = '';
        $this->form_validation->set_rules('total_leads','no fo leads','required|trim|numeric');
        if($this->form_validation->run()===TRUE){
            $total_leads = $this->input->post('total_leads');
            if($total_leads>=20){
                $data['total_leads'] = $total_leads;
                $data['response'] = true;
            }
            else{
                $data['total_leads_error']='<p>Minimum leads should be 20</p>';
            }           
        }
        else{
            $data['total_leads_error'] = form_error('total_leads');
        }
        $data['regenerate_token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

    public function payment($total_leads='')
    {
        if(!is_numeric($total_leads) || $total_leads=='0') {
            $this->session->set_flashdata('error_message', 'Invalid request of leads order.');
            redirect('orders/add/');
        }

        $data = [];
        $user_id = $this->session->userdata('user_id');
        $data['total_leads'] = $total_leads;
        $lead_price = lead_price();
        $data['total_amount'] = $total_leads*$lead_price;
        $this->load->view('orders/payment', $data);
    }

    public function process_payment()
    {
        if(!$this->input->post('stripeToken')){
           $this->session->set_flashdata('error_message', 'Invalid request of leads order.');
            redirect('orders/add/');
        }
        $token = $this->input->post('stripeToken');
        $amount = $this->input->post('amount');
        // stripe uses amount in cents so convert dollars into cents.
        $amount = $amount*100;
        $email = $this->input->post('stripeEmail');
        $total_leads = $this->input->post('total_leads');

        require APPPATH.'third_party/stripe/init.php';
        //stripe test key...
        //\Stripe\Stripe::setApiKey(STRIPE_TEST_KEY);
        //stripe live key...
        \Stripe\Stripe::setApiKey(STRIPE_LIVE_KEY);
        $charge = \Stripe\Charge::create([
            'amount' => $amount, 
            'currency' => STRIPE_CURRENCY_CODE, 
            'source' => $token
        ],[
          "idempotency_key" => alphanumeric_random_string(15)
        ]);

        if($charge){
            // stripe uses amount in cents so convert cents into dollars.
            $charge_amount = ($charge->amount)/100;
            $lead_price = lead_price();
            $no_of_leads = intval($charge_amount/$lead_price);
            $order = [];
            $order['user_id'] = $this->session->userdata('user_id');
            $order['total_leads'] = $no_of_leads;
            $order['remaining_leads'] = $no_of_leads;
            $order['interest_id'] = INTEREST_ID;
            if($order_id = $this->orders->save($order)){
                $invoice = [];
                $invoice['order_id'] = $order_id;
                $invoice['user_id'] = $this->session->userdata('user_id');
                $invoice['stripe_id'] = $charge->id;
                $invoice['amount'] = $charge_amount;
                $invoice['currency'] = $charge->currency;
                $this->invoices->save($invoice);
                $this->session->set_flashdata('success_message', "Your order of ".$no_of_leads." leads has been placed successfully.");
                redirect('orders/');
            }
        }
        else{
            $this->session->set_flashdata('error_message', "Invalid request for order leads.");
        }
        redirect('orders/add/');
        //echo json_encode($charge);
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
        $status_filter = $this->input->post('status_filter');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        
        $where = "orders.user_id = '".$this->user_id."'";
        $orders_count = $this->orders->count_rows($where);

        if( (isset($from_date) && $from_date != '') && (isset($to_date) && $to_date != '') ){
            $from_date = $from_date.' 00:00:00';
            $to_date = $to_date.' 23:00:00';
            $where = "orders.created_at BETWEEN '".$from_date."' AND '".$to_date."' AND orders.user_id = '".$this->user_id."'";
        }

        if( ($from_date != '' && $to_date == '') || ($to_date != '' && $from_date == '') ) {
           $date = ($from_date!='')?$from_date:$to_date;
           $where .= " AND (orders.created_at  LIKE CONCAT('%','".$date."' ,'%') )";
        }

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
                $single_field['remaining_leads'] = $item['remaining_leads'];
                $single_field['order_amount'] = '<span>&#163;</span>'.$item['order_amount'];
                $single_field['status'] = ($item['status']==1)?'Completed':'Approve';
                $single_field['leads'] = "<a href='".base_url('order_leads/'.createBase64($item['order_id']))."' class='click_here'>Click here</a>";
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

    public function get_leads()
    {
        $this->layout = '';
        $like = array();
        $leads_array = [];

        $orderByColumnIndex = $_POST['order'][0]['column'];
        $orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
        $orderType = $_POST['order'][0]['dir'];
        $offset = $this->input->post('start');
        $limit = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $_POST['search']['value'];
        $order_id = $this->input->post('order_id');
        //$status_filter = $this->input->post('status_filter');
        // $from_date = $this->input->post('from_date');
        // $to_date = $this->input->post('to_date');
        
        $where = "lead_order.order_id = '".$order_id."'";
        $leads_count = $this->lead_order->count_rows($where);

        // if( (isset($from_date) && $from_date != '') && (isset($to_date) && $to_date != '') ){
        //     $from_date = $from_date.' 00:00:00';
        //     $to_date = $to_date.' 23:00:00';
        //     $where = "leads.created_at BETWEEN '".$from_date."' AND '".$to_date."' AND leads.user_id = '".$this->user_id."'";
        // }
        // if( ($from_date != '' && $to_date == '') || ($to_date != '' && $from_date == '') ) {
        //    $date = ($from_date!='')?$from_date:$to_date;
        //    $where .= " AND (leads.created_at  LIKE CONCAT('%','".$date."' ,'%') )";
        // }

        // if(isset($status_filter) && $status_filter != ''){
        //     $where .= " AND leads.status ='".$status_filter."'";
        // }

        if (isset($search) && $search != '') {
            $where .= " AND (leads.first_name  LIKE CONCAT('%','" . $search . "' ,'%') OR leads.last_name LIKE CONCAT('%','" . $search . "' ,'%') OR leads.email LIKE CONCAT('%','" . $search . "' ,'%') OR leads.contact_mobile LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

       $joins = array(
            '0' => array('table_name' => 'leads leads',
                'join_on' => 'leads.lead_id = lead_order.lead_id',
                'join_type' => 'left'
            )
        );
        $from_table = "lead_order lead_order";
        $select_from_table = 'lead_order.created_at as delivered_date,leads.*';
        $leads_data = $this->lead_order->get_by_join($select_from_table, $from_table, $joins, $where, "leads.".$orderByColumn, $orderType, '', '', '', '', $limit, $offset);
        //debug($leads_data,true);
        $leads_count_rows = $this->lead_order->get_by_join_total_rows('*', $from_table, $joins, $where, "leads.".$orderByColumn, $orderType, '', '', '', '', '', '');

        if (isset($leads_data)) {
            foreach ($leads_data as $item) {
                $single_field['first_name'] = $item['first_name'];
                $single_field['last_name'] = $item['last_name'];
                $single_field['email'] = $item['email'];
                $single_field['contact_mobile'] = $item['contact_mobile'];
                $single_field['status'] = ($item['status']==1)?'Success':'Failed';
                $single_field['delivered_date'] = $item['delivered_date'];
                $leads_array[] = $single_field;
            }
            $data['draw'] = $draw;
            $data['recordsTotal'] = $leads_count;
            $data['recordsFiltered'] = $leads_count_rows;
            $data['data'] = $leads_array;
        } else {
            $data['draw'] = $draw;
            $data['recordsTotal'] = 0;
            $data['recordsFiltered'] = 0;
            $data['data'] = '';
        }
        echo json_encode($data);
    }

}