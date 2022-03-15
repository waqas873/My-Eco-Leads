<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Leads extends CI_Controller 
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
		$this->selected_tab = 'leads';
		$this->layout = 'user_dashboard';
		$this->load->model('users_model', 'users');
		$this->load->model('leads_model', 'leads');
		$this->load->model('leads_new_model', 'leads_new');
        $this->load->model('orders_model', 'orders');
        $this->load->model('lead_order_model', 'lead_order');
        $this->load->model('notes_model', 'notes');
		if(!$this->session->userdata('user')){
		    $this->session->set_flashdata('error_message', 'Sorry! Please login first.');
		    redirect('sign-in');
	    }
	    $this->user_id = $this->session->userdata('user_id');
	}
	
	public function index()
	{

		$data = [];
		$where = "user_id = '".$this->user_id."'";
		$result = $this->orders->get_where('SUM(total_leads) as ordered_leads,SUM(remaining_leads) as remaining_leads', $where, true, '' , '', '');
        $result = $result[0];
        $data['ordered_leads'] = $result['ordered_leads'];
        $data['remaining_leads'] = $result['remaining_leads'];
        $data['delivered_leads'] = $result['ordered_leads']-$result['remaining_leads'];

        $from_date = date('Y-m-d').' 00:00:00';
        $to_date = date('Y-m-d').' 23:59:59';
        $where = "lead_order.created_at BETWEEN '".$from_date."' AND '".$to_date."' AND lead_order.user_id = '".$this->user_id."'";
        $data['today_delivered_leads'] = $this->lead_order->count_rows($where);
        $arr = ['called','call_back','not_interested','no_answer','pack_out'];
        foreach($arr as $ar){
            $where = "user_id = '".$this->user_id."' AND lead_action = '".$ar."'";
            $data[$ar] = $this->leads->count_rows($where);
        }
		$this->load->view('leads/index', $data);
	}

	public function get_leads()
    {
        $this->layout = '';
        $like = array();
        $leads_array = [];
        
        $orderByColumnIndex = $_POST['order'][0]['column'];   
        $orderByColumn = $_POST['columns'][$orderByColumnIndex]['data'];
        $orderType = $_POST['order'][0]['dir'];
        if($orderByColumn=='sr'){
            $orderByColumn = 'lead_id';
            $orderType = 'desc';
        }
        $offset = $this->input->post('start');
        $limit = $this->input->post('length');
        $draw = $this->input->post('draw');
        $search = $_POST['search']['value'];
        $status_filter = $this->input->post('status_filter');
        $action_filter = $this->input->post('action_filter');
        $from_date = $this->input->post('from_date');
        $to_date = $this->input->post('to_date');
        
        $where = "leads.user_id = '".$this->user_id."'";
        $leads_count = $this->leads->count_rows($where);

        if( (isset($from_date) && $from_date != '') && (isset($to_date) && $to_date != '') ){
            $from_date = $from_date.' 00:00:00';
            $to_date = $to_date.' 23:00:00';
            $where = "leads.created_at BETWEEN '".$from_date."' AND '".$to_date."' AND leads.user_id = '".$this->user_id."'";
        }

        if( ($from_date != '' && $to_date == '') || ($to_date != '' && $from_date == '') ) {
           $date = ($from_date!='')?$from_date:$to_date;
           $where .= " AND (leads.created_at  LIKE CONCAT('%','".$date."' ,'%') )";
        }

        if(isset($status_filter) && $status_filter != ''){
            $where .= " AND leads.status ='".$status_filter."'";
        }

        if(isset($action_filter) && $action_filter != ''){
            $where .= " AND leads.lead_action ='".$action_filter."'";
        }

        if(isset($search) && $search != ''){
            $where .= " AND (leads.first_name  LIKE CONCAT('%','" . $search . "' ,'%') OR leads.last_name LIKE CONCAT('%','" . $search . "' ,'%') OR leads.email LIKE CONCAT('%','" . $search . "' ,'%') OR leads.contact_mobile LIKE CONCAT('%','" . $search . "' ,'%') OR leads.lead_info LIKE CONCAT('%','" . $search . "' ,'%'))";
        }

        $joins = array(
            '0' => array('table_name' => 'leads_new leads_new',
                'join_on' => 'leads_new.id = leads.leads_new_id',
                'join_type' => 'left'
            )
        );
        $from_table = "leads leads";
        $select_from_table = 'leads.*, leads_new.household_benefits, leads_new.house_no, leads_new.id';
        $leads_data = $this->leads->get_by_join($select_from_table, $from_table, $joins, $where, "leads.".$orderByColumn, $orderType, '', '', '', '', $limit, $offset);
        // debug($leads_data,true);
        $leads_count_rows = $this->leads->get_by_join_total_rows('*', $from_table, $joins, $where, "leads.".$orderByColumn, $orderType, '', '', '', '', '', '');
        $index = $offset+1;
        if(isset($leads_data)){
        	foreach($leads_data as $item){
                $single_field['sr'] = $index;
                $leads_new_id = $item['id'];
                $single_field['house_no'] = $item['house_no'];
                $single_field['household_benefits'] = $item['household_benefits'];
                $single_field['first_name'] = $item['first_name'];
                $single_field['last_name'] = $item['last_name'];
                $single_field['email'] = $item['email'];
                $single_field['contact_mobile'] = $item['contact_mobile'];
                // $single_field['confirm_mobile_number'] = (empty($item['confirm_mobile_number']))?"---":$item['confirm_mobile_number'];
                // $single_field['best_time_to_call'] = (empty($item['best_time_to_call']))?"---":$item['best_time_to_call'];
                $single_field['status'] = ($item['status']==1)?'Success':'Failed';
                $called = ($item['lead_action']=="called")?"selected":" ";
                $call_back = ($item['lead_action']=="call_back")?"selected":"";
                $not_interested = ($item['lead_action']=="not_interested")?"selected":"";
                $no_answer = ($item['lead_action']=="no_answer")?"selected":"";
                // $pack_out = ($item['lead_action']=="pack_out")?"selected":"";
                $id = $item['lead_id'];
                $arr = ['called','call_back','not_interested','no_answer'];
                $color_class = '';
                foreach($arr as $ar){
                    ($item['lead_action']==$ar)?$color_class=$ar:'';
                }
                $single_field['action'] = '<select class="form-control select2 action '.$color_class.'" name="'.$id.'"><option value="" class="action_select">Select</option><option value="called" '.$called.' class="called">Called</option><option value="call_back" '.$call_back.' class="call_back">Call Back</option><option value="not_interested" '.$not_interested.' class="not_interested">Not Interested</option><option value="no_answer" '.$no_answer.' class="no_answer">No Answer</option></select>';
                //$single_field['conversation'] = '<a href="javascript::" class="send_sms" rel="'.$id.'">Send Message</a><br/><a href="'.base_url('chat/index/'.createBase64($id)).'" target="_blank">View Chat</a>';
                $single_field['addresses'] = '<a href="javascript::" class="btn btn-info btn-sm view_addresses" rel="'.$leads_new_id.'">View Addresses</a>';
                $single_field['notes'] = '<a href="javascript::" class="add_note btn btn-info btn-sm" rel="'.$id.'">Add Note</a><a href="javascript::" class="view_notes btn btn-info btn-sm" rel="'.$id.'">View Notes</a>';
                $single_field['lead_info'] = (!empty($item['lead_info']))?'<a href="javascript::" class="btn btn-info btn-sm lead_info" rel="'.$id.'">Info</a>':'---';
                $appeal = '<a href="'.base_url('leads/lead_appeal/'.createBase64($id)).'" class="btn btn-info btn-sm" onclick="delete_record_dt(this); return false;">Appeal</a>';
                ($item['lead_appeal']==3)?$appeal="Appealed":'';
                ($item['lead_appeal']==2)?$appeal="Appeal Rejected":'';
                ($item['lead_appeal']==1)?$appeal="Appeal Approved":'';
                $single_field['lead_appeal'] = $appeal;
                $single_field['created_at'] = $item['created_at'];
                $leads_array[] = $single_field;
                $index++;
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

    public function lead_appeal($lead_id='')
    {
        $lead_id = decodeBase64($lead_id);
        $where = "lead_id = '".$lead_id."' AND lead_appeal = 0";
        $result = $this->leads->get_where('*', $where, true, '' , '', '');
        if(empty($result)){
            $this->session->set_flashdata('error_message', 'Invalid request for appeal.');
            redirect('leads/');
        }
        $update = ['lead_appeal'=>3];
        if($this->leads->update_by('lead_id',$lead_id,$update)){
            $this->session->set_flashdata('success_message', 'Your appeal has been submitted successfully.');
            redirect('leads/');
        }
        redirect('leads/');  
    }

    public function change_action()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $action = $this->input->post('action');
        $lead_id = $this->input->post('lead_id');
        $where = "lead_id = '".$lead_id."'";
        $result = $this->leads->get_where('*', $where, true, '' , '', '');
        $result = $result[0];
        $data['remove_class'] = (!empty($result['lead_action']))?$result['lead_action']:'';
        $data['add_class'] = $action;
        $update = ['lead_action'=>$action];
        if($this->leads->update_by('lead_id',$lead_id,$update)){
            $where = "user_id = '".$this->user_id."' AND lead_action = '".$action."'";
            $data['new_action_count'] = $this->leads->count_rows($where);
            $data['new_action'] = $action;
            $data['old_action_count'] = 0;
            if(!empty($result['lead_action'])){
                $where = "user_id = '".$this->user_id."' AND lead_action = '".$result['lead_action']."'";
                $data['old_action_count'] = $this->leads->count_rows($where);
                $data['old_action'] = $result['lead_action'];
            }
            $data['response'] = true;
        }   
        echo json_encode($data);
    }

    public function lead_info()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $data['lead_info'] = '';
        $lead_id = $this->input->post('lead_id');
        $where = "lead_id = '".$lead_id."'";
        $result = $this->leads->get_where('*', $where, true, '' , '', '');
        $leads_new_id = $result[0]['leads_new_id'];
        if(!empty($leads_new_id)){
            $lead_data = '';
            $where1 ="leads_new.id = '".$leads_new_id."'";
            $result1 = $this->leads_new->get_where('*', $where1, true, '' , '', '');
            if(!empty($result1)){
                $lead_info = $result1[0];
                if(!empty($lead_info['name'])){
                    $lead_data .= '<span class="question">What is your name?</span><br/>';
                    $lead_data .= '<span class="answer">'.$lead_info['name'].'</span><br/>';
                }
                if(!empty($lead_info['email'])){
                    $lead_data .= '<span class="question">What is your email?</span><br/>';
                    $lead_data .= '<span class="answer">'.$lead_info['email'].'</span><br/>';
                }
                if(!empty($lead_info['phone'])){
                    $lead_data .= '<span class="question">What is your phone number?</span><br/>';
                    $lead_data .= '<span class="answer">'.$lead_info['phone'].'</span><br/>';
                }
                if(!empty($lead_info['postal_code'])){
                    $lead_data .= '<span class="question">What is your postal code?</span><br/>';
                    $lead_data .= '<span class="answer">'.$lead_info['postal_code'].'</span><br/>';
                }
                if(!empty($lead_info['house_no'])){
                    $lead_data .= '<span class="question">What is your house number?</span><br/>';
                    $lead_data .= '<span class="answer">'.$lead_info['house_no'].'</span><br/>';
                }
                if(!empty($lead_info['property_type'])){
                    $lead_data .= '<span class="question">What is your property type?</span><br/>';
                    $lead_data .= '<span class="answer">'.$lead_info['property_type'].'</span><br/>';
                }
                if(!empty($lead_info['household_benefits'])){
                    $lead_data .= '<span class="question">What are your household benefits?</span><br/>';
                    $lead_data .= '<span class="answer">'.$lead_info['household_benefits'].'</span><br/>';
                }
                if(!empty($lead_info['residential_status'])){
                    $lead_data .= '<span class="question">What is your residential status?</span><br/>';
                    $lead_data .= '<span class="answer">'.$lead_info['residential_status'].'</span><br/>';
                }
                if(!empty($lead_info['floor_type'])){
                    $lead_data .= '<span class="question">What is your floor type?</span><br/>';
                    $lead_data .= '<span class="answer">'.$lead_info['floor_type'].'</span><br/>';
                }
                if(!empty($lead_info['fuel_type'])){
                    $lead_data .= '<span class="question">What is your fuel type?</span><br/>';
                    $lead_data .= '<span class="answer">'.$lead_info['fuel_type'].'</span><br/>';
                }
                if(!empty($lead_info['heating_system'])){
                    $lead_data .= '<span class="question">What is your heating system age?</span><br/>';
                    $lead_data .= '<span class="answer">'.$lead_info['heating_system'].'</span><br/>';
                }
                $data['lead_info'] = $lead_data;
                $data['response'] = true;
            }
        }else {
            $lead_data = "Info. doesn't exist against this lead!";
            $data['lead_info'] = $lead_data;
            $data['response'] = true;
        }  
        echo json_encode($data);
    }
    public function addresses_info(){

        $this->layout = " ";
        $data = array();
        $data['response'] = false;
        $data['address_info'] = '';
        $data['address_table'] = '';

        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        
        $lead_id = $this->input->post('lead_id');
        $where = "id = '".$lead_id."'";
        $lead_result = $this->leads_new->get_where('*', $where, true, '' , '', '');
        if(!empty($lead_result)){
            foreach($lead_result as $lead){
                $epc_response = $lead['epc_response'];
                $decoded_epc = json_decode($epc_response, true);
                $epc_rows = $decoded_epc['rows'];
                // debug($epc_rows, true);
                $address_data = 'lead_address';
                $address_data = '<select class="addresses" name="lead_address" id="lead_address">';
                $address_data .= '<option value="">All Addresses</option>';

                foreach($epc_rows as $key => $value){
                $key = $key+1;
                   $address_data .= '<option value="row_'.$key.'">'.$value['address'].'</option>';     
                }
                $address_data .= '</select>';
                
                $address_table = '';
                foreach($epc_rows as $key => $value){
                    $key2 = $key+1;
                    $address_table .= '<tbody style="display:none;" class="row-data" id="row_'.$key2.'">';

                    $address_table .= '<tr>';
                    $address_table .= '<th>LOW ENERGY FIXED LIGHT COUNT</th>';
                    $address_table .= '<th>UPRN SOURCE</th>';
                    $address_table .= '<th>FLOOR HEIGHT</th>';
                    $address_table .= '<th>HEATING COST POTENTIAL</th>';
                    $address_table .= '<th>UNHEATED CORRIDOR LENGTH</th>';
                    $address_table .= '<th>HOT WATER COST POTENTIAL</th>';
                    $address_table .= '<th>CONSTRUCTION AGE BAND</th>';
                    $address_table .= '<th>POTENTIAL ENERGY RATING</th>';
                    $address_table .= '<th>MAINHEAT ENERGY EFF</th>';
                    $address_table .= '<th>WINDOWS ENV EFF</th>';
                    $address_table .= '</tr>';
                    
                    $address_table .= '<tr>';
                    $address_table .= ($value['low-energy-fixed-light-count']!="")?'<td>'.$value['low-energy-fixed-light-count'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['uprn-source']!="")?'<td>'.$value['uprn-source'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['floor-height']!="")?'<td>'.$value['floor-height'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['heating-cost-potential']!="")?'<td>'.$value['heating-cost-potential'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['unheated-corridor-length']!="")?'<td>'.$value['unheated-corridor-length'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['hot-water-cost-potential']!="")?'<td>'.$value['hot-water-cost-potential'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['construction-age-band']!="")?'<td>'.$value['construction-age-band'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['potential-energy-rating']!="")?'<td>'.$value['potential-energy-rating'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['mainheat-energy-eff']!="")?'<td>'.$value['mainheat-energy-eff'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['windows-env-eff']!="")?'<td>'.$value['windows-env-eff'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= '</tr>';

                    // $address_table .= '<tr>';
                    // $address_table .= '<th>lighting energy eff</th>';
                    // $address_table .= '<th>environment impact potential</th>';
                    // $address_table .= '<th>glazed type</th>';
                    // $address_table .= '<th>heating cost current</th>';
                    // $address_table .= '<th>walls description</th>';
                    // $address_table .= '<th>mainheatcont description</th>';
                    // $address_table .= '<th>sheating energy eff</th>';
                    // $address_table .= '<th>property type</th>';
                    // $address_table .= '<th>local authority label</th>';
                    // $address_table .= '<th>fixed lighting outlets count</th>';
                    // $address_table .= '</tr>';

                    // $address_table .= '<tr>';
                    // $address_table .= '<td>'.$value['lighting-energy-eff'].'</td>';
                    // $address_table .= '<td>'.$value['environment-impact-potential'].'</td>';
                    // $address_table .= '<td>'.$value['glazed-type'].'</td>';
                    // $address_table .= '<td>'.$value['heating-cost-current'].'</td>';
                    // $address_table .= '<td>'.$value['walls-description'].'</td>';
                    // $address_table .= '<td>'.$value['mainheatcont-description'].'</td>';
                    // $address_table .= '<td>'.$value['sheating-energy-eff'].'</td>';
                    // $address_table .= '<td>'.$value['property-type'].'</td>';
                    // $address_table .= '<td>'.$value['local-authority-label'].'</td>';
                    // $address_table .= '<td>'.$value['fixed-lighting-outlets-count'].'</td>';
                    // $address_table .= '</tr>';

                    // $address_table .= '<tr>';
                    // $address_table .= '<th>energy tariff</th>';
                    // $address_table .= '<th>mechanical ventilation</th>';
                    // $address_table .= '<th>hot water cost current</th>';
                    // $address_table .= '<th>county</th>';
                    // $address_table .= '<th>postcode</th>';
                    // $address_table .= '<th>solar water heating flag</th>';
                    // $address_table .= '<th>constituency</th>';
                    // $address_table .= '<th>co2 emissions potential</th>';
                    // $address_table .= '<th>number heated rooms</th>';
                    // $address_table .= '<th>floor description</th>';
                    // $address_table .= '</tr>';

                    // $address_table .= '<tr>';
                    // $address_table .= '<td>'.$value['energy-tariff'].'</td>';
                    // $address_table .= '<td>'.$value['mechanical-ventilation'].'</td>';
                    // $address_table .= '<td>'.$value['hot-water-cost-current'].'</td>';
                    // $address_table .= '<td>'.$value['county'].'</td>';
                    // $address_table .= '<td>'.$value['postcode'].'</td>';
                    // $address_table .= '<td>'.$value['solar-water-heating-flag'].'</td>';
                    // $address_table .= '<td>'.$value['constituency'].'</td>';
                    // $address_table .= '<td>'.$value['co2-emissions-potential'].'</td>';
                    // $address_table .= '<td>'.$value['number-heated-rooms'].'</td>';
                    // $address_table .= '<td>'.$value['floor-description'].'</td>';
                    // $address_table .= '</tr>';

                    // $address_table .= '<tr>';
                    // $address_table .= '<th>energy consumption potential</th>';
                    // $address_table .= '<th>local authority</th>';
                    // $address_table .= '<th>built form</th>';
                    // $address_table .= '<th>number open fireplaces</th>';
                    // $address_table .= '<th>windows description</th>';
                    // $address_table .= '<th>glazed area</th>';
                    // $address_table .= '<th>inspection date</th>';
                    // $address_table .= '<th>mains gas flag</th>';
                    // $address_table .= '<th>co2 emiss curr per floor area</th>';
                    // $address_table .= '<th>address1</th>';
                    // $address_table .= '</tr>';

                    // $address_table .= '<tr>';
                    // $address_table .= '<td>'.$value['energy-consumption-potential'].'</td>';
                    // $address_table .= '<td>'.$value['local-authority'].'</td>';
                    // $address_table .= '<td>'.$value['built-form'].'</td>';
                    // $address_table .= '<td>'.$value['number-open-fireplaces'].'</td>';
                    // $address_table .= '<td>'.$value['windows-description'].'</td>';
                    // $address_table .= '<td>'.$value['glazed-area'].'</td>';
                    // $address_table .= '<td>'.$value['inspection-date'].'</td>';
                    // $address_table .= '<td>'.$value['mains-gas-flag'].'</td>';
                    // $address_table .= '<td>'.$value['co2-emiss-curr-per-floor-area'].'</td>';
                    // $address_table .= '<td>'.$value['address1'].'</td>';
                    // $address_table .= '</tr>';

                    // $address_table .= '<tr>';
                    // $address_table .= '<th>heat loss corridor</th>';
                    // $address_table .= '<th>flat storey count</th>';
                    // $address_table .= '<th>constituency label</th>';
                    // $address_table .= '<th>roof energy eff</th>';
                    // $address_table .= '<th>total floor area</th>';
                    // $address_table .= '<th>building reference number</th>';
                    // $address_table .= '<th>environment impact current</th>';
                    // $address_table .= '<th>co2 emissions current</th>';
                    // $address_table .= '<th>roof description</th>';
                    // $address_table .= '<th>floor energy eff</th>';
                    // $address_table .= '</tr>';

                    // $address_table .= '<tr>';
                    // $address_table .= '<td>'.$value['heat-loss-corridor'].'</td>';
                    // $address_table .= '<td>'.$value['flat-storey-count'].'</td>';
                    // $address_table .= '<td>'.$value['constituency-label'].'</td>';
                    // $address_table .= '<td>'.$value['roof-energy-eff'].'</td>';
                    // $address_table .= '<td>'.$value['total-floor-area'].'</td>';
                    // $address_table .= '<td>'.$value['building-reference-number'].'</td>';
                    // $address_table .= '<td>'.$value['environment-impact-current'].'</td>';
                    // $address_table .= '<td>'.$value['co2-emissions-current'].'</td>';
                    // $address_table .= '<td>'.$value['roof-description'].'</td>';
                    // $address_table .= '<td>'.$value['floor-energy-eff'].'</td>';
                    // $address_table .= '</tr>';
                    
                    // $address_table .= '<tr>';
                    // $address_table .= '<th>number habitable rooms</th>';
                    // $address_table .= '<th>address2</th>';
                    // $address_table .= '<th>hot water env eff</th>';
                    // $address_table .= '<th>posttown</th>';
                    // $address_table .= '<th>mainheatc energy eff</th>';
                    // $address_table .= '<th>main fuel</th>';
                    // $address_table .= '<th>lighting env eff</th>';
                    // $address_table .= '<th>windows energy eff</th>';
                    // $address_table .= '<th>floor env eff</th>';
                    // $address_table .= '<th>sheating env eff</th>';
                    // $address_table .= '</tr>';
                    
                    // $address_table .= '<tr>';
                    // $address_table .= '<td>'.$value['number-habitable-rooms'].'</td>';
                    // $address_table .= '<td>'.$value['address2'].'</td>';
                    // $address_table .= '<td>'.$value['hot-water-env-eff'].'</td>';
                    // $address_table .= '<td>'.$value['posttown'].'</td>';
                    // $address_table .= '<td>'.$value['mainheatc-energy-eff'].'</td>';
                    // $address_table .= '<td>'.$value['main-fuel'].'</td>';
                    // $address_table .= '<td>'.$value['lighting-env-eff'].'</td>';
                    // $address_table .= '<td>'.$value['windows-energy-eff'].'</td>';
                    // $address_table .= '<td>'.$value['floor-env-eff'].'</td>';
                    // $address_table .= '<td>'.$value['sheating-env-eff'].'</td>';
                    // $address_table .= '</tr>';
                    
                    // $address_table .= '<tr>';
                    // $address_table .= '<th>lighting description</th>';
                    // $address_table .= '<th>roof env eff</th>';
                    // $address_table .= '<th>walls energy eff</th>';
                    // $address_table .= '<th>photo supply</th>';
                    // $address_table .= '<th>lighting cost potential</th>';
                    // $address_table .= '<th>mainheat env eff</th>';
                    // $address_table .= '<th>multi glaze proportion</th>';
                    // $address_table .= '<th>main heating controls</th>';
                    // $address_table .= '<th>lodgement datetime</th>';
                    // $address_table .= '<th>flat top storey</th>';
                    // $address_table .= '</tr>';
                    
                    // $address_table .= '<tr>';
                    // $address_table .= '<td>'.$value['lighting-description'].'</td>';
                    // $address_table .= '<td>'.$value['roof-env-eff'].'</td>';
                    // $address_table .= '<td>'.$value['walls-energy-eff'].'</td>';
                    // $address_table .= '<td>'.$value['photo-supply'].'</td>';
                    // $address_table .= '<td>'.$value['lighting-cost-potential'].'</td>';
                    // $address_table .= '<td>'.$value['mainheat-env-eff'].'</td>';
                    // $address_table .= '<td>'.$value['multi-glaze-proportion'].'</td>';
                    // $address_table .= '<td>'.$value['main-heating-controls'].'</td>';
                    // $address_table .= '<td>'.$value['lodgement-datetime'].'</td>';
                    // $address_table .= '<td>'.$value['flat-top-storey'].'</td>';
                    // $address_table .= '</tr>';
                    
                    // $address_table .= '<tr>';
                    // $address_table .= '<th>current energy rating</th>';
                    // $address_table .= '<th>secondheat description</th>';
                    // $address_table .= '<th>walls env eff</th>';
                    // $address_table .= '<th>transaction type</th>';
                    // $address_table .= '<th>uprn</th>';
                    // $address_table .= '<th>current energy efficiency</th>';
                    // $address_table .= '<th>energy consumption current</th>';
                    // $address_table .= '<th>mainheat description</th>';
                    // $address_table .= '<th>lighting cost current</th>';
                    // $address_table .= '<th>lodgement date</th>';
                    // $address_table .= '</tr>';
                    
                    // $address_table .= '<tr>';
                    // $address_table .= '<td>'.$value['current-energy-rating'].'</td>';
                    // $address_table .= '<td>'.$value['secondheat-description'].'</td>';
                    // $address_table .= '<td>'.$value['walls-env-eff'].'</td>';
                    // $address_table .= '<td>'.$value['transaction-type'].'</td>';
                    // $address_table .= '<td>'.$value['uprn'].'</td>';
                    // $address_table .= '<td>'.$value['current-energy-efficiency'].'</td>';
                    // $address_table .= '<td>'.$value['energy-consumption-current'].'</td>';
                    // $address_table .= '<td>'.$value['mainheat-description'].'</td>';
                    // $address_table .= '<td>'.$value['lighting-cost-current'].'</td>';
                    // $address_table .= '<td>'.$value['lodgement-date'].'</td>';
                    // $address_table .= '</tr>';
                    
                    $address_table .= '<tr>';
                    $address_table .= '<th>EXTENSION COUNT</th>';
                    $address_table .= '<th>MAINHEATC ENV EFF</th>';
                    $address_table .= '<th>ADDRESS3</th>';
                    $address_table .= '<th>WIND TURBINE COUNT</th>';
                    $address_table .= '<th>TENURE</th>';
                    $address_table .= '<th>FLOOR LEVEL</th>';
                    $address_table .= '<th>POTENTIAL ENERGY EFFICIENCY</th>';
                    $address_table .= '<th>HOT WATER ENERGY EFF</th>';
                    $address_table .= '<th>LOW ENERGY LIGHTING</th>';
                    $address_table .= '<th>HOTWATER DESCRIPTION</th>';
                    $address_table .= '</tr>';
                    
                    $address_table .= '<tr>';
                    $address_table .= ($value['extension-count']!="")?'<td>'.$value['extension-count'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['mainheatc-env-eff']!="")?'<td>'.$value['mainheatc-env-eff'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['address3']!="")?'<td>'.$value['address3'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['wind-turbine-count']!="")?'<td>'.$value['wind-turbine-count'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['tenure']!="")?'<td>'.$value['tenure'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['floor-level']!="")?'<td>'.$value['floor-level'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['potential-energy-efficiency']!="")?'<td>'.$value['potential-energy-efficiency'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['hot-water-energy-eff']!="")?'<td>'.$value['hot-water-energy-eff'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['low-energy-lighting']!="")?'<td>'.$value['low-energy-lighting'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= ($value['hotwater-description']!="")?'<td>'.$value['hotwater-description'].'</td>':'<td>(NO VALUE AVAILABLE)</td>';
                    $address_table .= '</tr>';

                    $address_table .= '</tbody>';

                }
                $data['address_table'] = $address_table;
                $data['address_info'] = $address_data;
                $data['response'] = true;
            }
        }
        // debug($data);
        echo json_encode($data);
    }

    public function view_notes()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $data['view_notes'] = '';
        $lead_id = $this->input->post('lead_id');
        $where = "lead_id = '".$lead_id."'";
        $result = $this->notes->get_where('*', $where, true, '' , '', '');
        if(!empty($result)){
            $note_data = '';
            foreach($result as $value){
                $note_data .= '<div id="note'.$value['note_id'].'"><span class="answer note_des">'.$value['description'].'.</span> <br/><span class="ndate">'.$value['created_at'].'.</span> | <a href="javascript::" rel="'.$value['note_id'].'" class="remove_note">Remove</a><br/><hr></div>';
            }
            //debug($lead_info,true);
            $data['view_notes'] = $note_data;
            $data['response'] = true;
        }   
        echo json_encode($data);
    }

    public function process_add_note()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        $this->form_validation->set_rules('description','','required|trim');
        $this->form_validation->set_rules('lead_id','','required');
        if($this->form_validation->run()===TRUE){
            $save = $this->input->post();
            if($note_id = $this->notes->save($save)){
                $data['response'] = true;
            }
        }
        else{
            $data['description_error'] = form_error('description');
        }
        $data['regenerate_token'] = $this->security->get_csrf_hash();
        echo json_encode($data);
    }
	
    public function delete_note()
    {
        $data = array();
        $this->layout = " ";
        if(!$this->input->is_ajax_request()){
           exit('No direct script access allowed');
        }
        $data['response'] = false;
        if($this->input->post()){
            $note_id = $this->input->post('note_id');
            $where = "note_id='".$note_id."'";
            $result = $this->notes->count_rows($where);
            if($result>0){
                $this->notes->delete_by('note_id',$note_id);
                $data['response'] = true;
            }
        }
        echo json_encode($data);
    }
}