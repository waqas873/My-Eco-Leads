<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller 
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
		$this->selected_tab = 'home';
		$this->layout = 'publicsite';
		$this->load->model('admin_model', 'admin');
		$this->load->model('users_model', 'users');
		$this->load->model('leads_model', 'leads');
		$this->load->model('orders_model', 'orders');
		$this->load->model('lead_order_model', 'lead_order');
		$this->load->model('contact_us_model', 'contact_us');
		$this->load->model('interests_model', 'interests');
		$this->load->model('users_interests_model', 'users_interests');
		if($this->session->userdata('user')){
	        redirect('dashboard/');
	    }
	}
	
	public function index()
	{
		$data = [];
		$where = "is_removed='0'";
		$data['interests'] = $this->interests->get_where('*', $where, true, '' , '', '');
		$this->load->view('home/index', $data);
	}

	public function deliver_leads()
	{
		$this->layout = '';
		$this->db->trans_start();
		$available_leads = $this->leads->count_rows('status=0');
        $where = "status='0'";
		$client_orders = $this->orders->get_where('order_id,user_id,total_leads,remaining_leads', $where, true, 'remaining_leads DESC' , '', '');
		$result = $this->orders->get_where('SUM(remaining_leads) as remaining_leads', $where, true, '' , '', '');
		$client_orders_sum = $result[0]['remaining_leads'];
		//echo $client_orders_sum; exit;
		$remaining_leads = $available_leads;
		if($available_leads>$client_orders_sum || $available_leads==$client_orders_sum){
            foreach($client_orders as $row){
                $deliver_leads = $row['remaining_leads'];
                $remaining_leads = $remaining_leads-$deliver_leads;
                $this->update_orders($row['order_id'],$deliver_leads);
            }
		}
		else{
			$total_clients = count($client_orders);
			$average = ($available_leads>$total_clients)?true:false;
			if($average){
				$orders_average = $available_leads/$client_orders_sum;
			}
			$again = false;
			while($remaining_leads>0){
				$exploded_leads = 0;
				foreach ($client_orders as $row) {
					if(isset($orders_average) && $again === false){
					    $delivered_leads = $orders_average*$row['remaining_leads'];
	                    $explode = explode(".",$delivered_leads);
	                    $delivered_leads = $explode[0];
	                    if(isset($explode[1])){
                            $float = '0.'.$explode[1];
                            $exploded_leads=$exploded_leads+$float;
	                    }
					}
					else{
						$delivered_leads = ($remaining_leads>0)?1:0;
						$remaining_leads = $remaining_leads-1;
					}
					$this->update_orders($row['order_id'],$delivered_leads);
				}
				$remaining_leads = $exploded_leads;
				$again = true;
			}
		}
		$this->db->trans_complete();
	}

	public function update_orders($order_id,$deliver_leads)
	{
		$this->layout = '';
		if(is_numeric($order_id) && $deliver_leads>0){
			$where = "order_id = '".$order_id."'";
		    $order = $this->orders->get_where('*', $where, true, '' , '', '');
		    $order = $order[0];
		    
	    	$where = "status = '0'";
	    	$leads = $this->leads->get_where('lead_id', $where, true, 'lead_id ASC' , $deliver_leads, '');
	    	$where = "status = '0' order by lead_id ASC limit ".$deliver_leads."";
	    	$update_leads = [];
	    	$update_leads['user_id'] = $order['user_id'];
	    	$update_leads['status'] = 1;
            if($this->leads->update_by_where($update_leads,$where))
            {
            	$all_leads = [];
                foreach($leads as $lead){
                	$single_lead = [];
                	$single_lead['lead_id'] = $lead['lead_id'];
                	$single_lead['order_id'] = $order_id;
                	$single_lead['user_id'] = $order['user_id'];
                	$all_leads[] = $single_lead;
                }
                if(!empty($all_leads)){
                	$this->lead_order->save_batch($all_leads);
                    $update_order = [];
				    $update_order['remaining_leads'] = $order['remaining_leads']-count($all_leads);
				    ($update_order['remaining_leads']==0)?$update_order['status']=1:'';
				    $this->orders->update_by('order_id',$order_id,$update_order);
                }
            }
		}
		return true;
	}

	public function about_us()
	{
		$data = [];
		$this->load->view('home/about_us', $data);
	}

	public function contact_us()
	{
		$data = [];
		$this->load->view('home/contact_us', $data);
	}

	public function process_contact_us()
	{
		$data = array();
		$this->layout = " ";
		if(!$this->input->is_ajax_request()){
		   exit('No direct script access allowed');
		}
		$data['response'] = false;
		$this->form_validation->set_rules('name','name','required|trim');
		$this->form_validation->set_rules('email','email','required|valid_email|trim');
		$this->form_validation->set_rules('phone_no','contact no','required|min_length[7]|max_length[15]|numeric');
		$this->form_validation->set_rules('subject','subject','required|trim');
		$this->form_validation->set_rules('message','message','required|trim');
		if($this->form_validation->run()===TRUE){
			$contact_us = $this->input->post();
			$this->contact_us->save($contact_us);
            $message = $contact_us['message'];
			send_email_to("Dear Admin",$contact_us['email'],$contact_us['message'],ucwords($contact_us['subject']));
			$data['response'] = true;
		}
		else{
			$data['name_error'] = form_error('name');
            $data['email_error'] = form_error('email');
            $data['phone_no_error'] = form_error('phone_no');
			$data['subject_error'] = form_error('subject');
            $data['message_error'] = form_error('message');
		}
		$data['regenerate_token'] = $this->security->get_csrf_hash();
		echo json_encode($data);
	}

	public function support()
	{
		$data = [];
		$this->load->view('home/support', $data);
	}

	public function faqs()
	{
		$data = [];
		$this->load->view('home/faqs', $data);
	}


	
}