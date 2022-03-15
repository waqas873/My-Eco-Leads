<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sms_orders extends CI_Controller 
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
		$this->selected_tab = 'sms_orders';
		$this->layout = 'user_dashboard';
        $this->load->model('sms_orders_model', 'sms_orders');
        $this->load->model('sms_invoices_model', 'sms_invoices');
        $this->load->model('connections_model', 'connections');
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
        $result = $this->sms_orders->get_where('SUM(total_sms) as total_orders', $where, true, '' , '', '');
        $result = $result[0];
        $data['total_orders'] = ($result['total_orders']>0)?$result['total_orders']:0;
        $result = $this->sms_invoices->get_where('SUM(amount) as orders_amount', $where, true, '' , '', '');
        $result = $result[0];
        $data['orders_amount'] = ($result['orders_amount']>0)?$result['orders_amount']:0;
        $this->load->view('sms_orders/index', $data);
    }
	
	public function add()
	{
        $data = [];
        $this->load->view('sms_orders/add', $data);
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
        $data['total_sms_error'] = '';
        $this->form_validation->set_rules('total_sms','no fo sms','required|trim|numeric');
        if($this->form_validation->run()===TRUE){
            $total_sms = $this->input->post('total_sms');
            if($total_sms>0){
                $data['total_sms'] = $total_sms;
                $data['response'] = true;
            }
            else{
                $data['total_sms_error']='<p>Invalid request of sms</p>';
            }           
        }
        else{
            $data['total_sms_error'] = form_error('total_sms');
        }
        $data['regenerate_token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }

    public function payment($total_sms='')
    {
        if(!is_numeric($total_sms) || $total_sms=='0') {
            $this->session->set_flashdata('error_message', 'Invalid request of sms order.');
            redirect('sms_orders/add/');
        }

        $data = [];
        $user_id = $this->session->userdata('user_id');

        $data['total_sms'] = $total_sms;
        $data['total_amount'] = $total_sms*SMS_PRICE;

        $where = "user_id = '".$this->user_id."'";
        $connection = $this->connections->get_where('*', $where, true, '' , '', '');
        if(empty($connection)){
            $data['total_amount'] = $data['total_amount']+CONNECTION_PRICE;
            //debug($data['total_amount'],true);
            $data['connection'] = '<br/><strong>Note: </strong>These price includes your first time connection price of &pound'.CONNECTION_PRICE.'.';
        }
        $this->load->view('sms_orders/payment', $data);
    }

    public function process_payment()
    {
        if(!$this->input->post('stripeToken')){
           $this->session->set_flashdata('error_message', 'Invalid request of leads order.');
            redirect('sms_orders/add/');
        }
        $token = $this->input->post('stripeToken');
        $amount = $this->input->post('amount');
        // stripe uses amount in cents so convert dollars into cents.
        $amount = $amount*100;
        $email = $this->input->post('stripeEmail');
        $total_sms = $this->input->post('total_sms');

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

            $where = "user_id = '".$this->user_id."'";
            $connection = $this->connections->get_where('*', $where, true, '' , '', '');
            if(empty($connection)){
                $save = ['user_id'=>$this->user_id];
                $this->connections->save($save);
            }

            // stripe uses amount in cents so convert cents into dollars.
            $charge_amount = ($charge->amount)/100;
            $no_of_sms = intval($charge_amount/SMS_PRICE);
            $order = [];
            $order['user_id'] = $this->session->userdata('user_id');
            $order['total_sms'] = $no_of_sms;
            $order['remaining_sms'] = $no_of_sms;
            if($so_id = $this->sms_orders->save($order)){
                $invoice = [];
                $invoice['so_id'] = $so_id;
                $invoice['user_id'] = $this->session->userdata('user_id');
                $invoice['stripe_id'] = $charge->id;
                $invoice['amount'] = $charge_amount;
                $invoice['currency'] = $charge->currency;
                $this->sms_invoices->save($invoice);
                $this->session->set_flashdata('success_message', "Your order of ".$no_of_sms." sms has been placed successfully.");
                redirect('sms_orders/');
            }
        }
        else{
            $this->session->set_flashdata('error_message', "Invalid request for order sms.");
        }
        redirect('sms_orders/add/');
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
        // $status_filter = $this->input->post('status_filter');
        // $from_date = $this->input->post('from_date');
        // $to_date = $this->input->post('to_date');
        
        $where = "sms_orders.user_id = '".$this->user_id."'";
        $orders_count = $this->sms_orders->count_rows($where);

        // if( (isset($from_date) && $from_date != '') && (isset($to_date) && $to_date != '') ){
        //     $from_date = $from_date.' 00:00:00';
        //     $to_date = $to_date.' 23:00:00';
        //     $where = "orders.created_at BETWEEN '".$from_date."' AND '".$to_date."' AND orders.user_id = '".$this->user_id."'";
        // }

        // if( ($from_date != '' && $to_date == '') || ($to_date != '' && $from_date == '') ) {
        //    $date = ($from_date!='')?$from_date:$to_date;
        //    $where .= " AND (orders.created_at  LIKE CONCAT('%','".$date."' ,'%') )";
        // }

        // if(isset($status_filter) && $status_filter != ''){
        //     $where .= " AND orders.status ='".$status_filter."'";
        // }

        if (isset($search) && $search != '') {
            $where .= " AND (orders.total_sms  LIKE CONCAT('%','" . $search . "' ,'%') OR orders.remaining_sms LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

        $joins = array(
            '0' => array('table_name' => 'sms_invoices sms_invoices',
                'join_on' => 'sms_invoices.so_id = sms_orders.so_id',
                'join_type' => 'left'
            )
        );
        $from_table = "sms_orders sms_orders";
        $select_from_table = 'sms_orders.*,sms_invoices.amount as order_amount';
        $orders_data = $this->sms_orders->get_by_join($select_from_table, $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', $limit, $offset);
        //debug($orders_data,true);
        $orders_count_rows = $this->sms_orders->get_by_join_total_rows('*', $from_table, $joins, $where, $orderByColumn, $orderType, '', '', '', '', '', '');

        if (isset($orders_data)) {
            foreach ($orders_data as $item) {
                $single_field['total_sms'] = $item['total_sms'];
                $single_field['remaining_sms'] = $item['remaining_sms'];
                $single_field['order_amount'] = '<span>&#163;</span>'.$item['order_amount'];
                $single_field['status'] = ($item['status']==1)?'Completed':'Remaining';
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