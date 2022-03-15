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
                $single_field['epc'] = '<a href="javascript::" class="btn btn-info btn-sm view_addresses" rel="'.$leads_new_id.'">EPC Checker</a>';
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
        // debug($leads_new_id, true);
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
                
                if(!empty($epc_response)){
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

                        $val1 = (!empty($value['low-energy-fixed-light-count']))?$value['low-energy-fixed-light-count']:'N/A';
                        $val2 = (!empty($value['uprn-source']))?$value['uprn-source']:'N/A';
                        $val3 = (!empty($value['floor-height']))?$value['floor-height']:'N/A';
                        $val4 = (!empty($value['heating-cost-potential']))?$value['heating-cost-potential']:'N/A';
                        $val5 = (!empty($value['unheated-corridor-length']))?$value['unheated-corridor-length']:'N/A';
                        $val6 = (!empty($value['hot-water-cost-potential']))?$value['hot-water-cost-potential']:'N/A';
                        $val7 = (!empty($value['construction-age-band']))?$value['construction-age-band']:'N/A';
                        $val8 = (!empty($value['potential-energy-rating']))?$value['potential-energy-rating']:'N/A';
                        $val9 = (!empty($value['mainheat-energy-eff']))?$value['mainheat-energy-eff']:'N/A';
                        $val10 = (!empty($value['windows-env-eff']))?$value['windows-env-eff']:'N/A';

                        $val11 = (!empty($value['extension-count']))?$value['extension-count']:'N/A';
                        $val12 = (!empty($value['mainheatc-env-eff']))?$value['mainheatc-env-eff']:'N/A';
                        $val13 = (!empty($value['address3']))?$value['address3']:'N/A';
                        $val14 = (!empty($value['wind-turbine-count']))?$value['wind-turbine-count']:'N/A';
                        $val15 = (!empty($value['tenure']))?$value['tenure']:'N/A';
                        $val16 = (!empty($value['floor-level']))?$value['floor-level']:'N/A';
                        $val17 = (!empty($value['potential-energy-efficiency']))?$value['potential-energy-efficiency']:'N/A';
                        $val18 = (!empty($value['hot-water-energy-eff']))?$value['hot-water-energy-eff']:'N/A';
                        $val19 = (!empty($value['low-energy-lighting']))?$value['low-energy-lighting']:'N/A';
                        $val20 = (!empty($value['hotwater-description']))?$value['hotwater-description']:'N/A';

                        $address_table .= '<tr>
                            <th colspan="2">Certificate Details</th>
                            </tr>
                            <tr>
                                <td>low energy fixed light count</td><td>'.$val1.'</td>
                            </tr>
                            <tr>
                                <td>uprn source</td><td>'.$val2.'</td>
                            </tr>
                            <tr>
                                <td>floor height</td><td>'.$val3.'</td>
                            </tr>
                            <tr>
                                <td>heating cost potential</td><td>'.$val4.'</td>
                            </tr>
                            <tr>
                                <td>unheated corridor length</td><td>'.$val5.'</td>
                            </tr>
                            <tr>
                                <td>hot water cost potential</td><td>'.$val6.'</td>
                            </tr>
                            <tr>
                                <td>construction age band</td><td>'.$val7.'</td>
                            </tr>
                            <tr>
                                <td>potential energy rating</td><td>'.$val8.'</td>
                            </tr>
                            <tr>
                                <td>mainheat energy eff</td><td>'.$val9.'</td>
                            </tr>
                            <tr>
                                <td>windows env eff</td><td>'.$val10.'</td>
                            </tr>
                            <tr>
                              <th colspan="2">Location</th>
                            </tr>
                            <tr>
                                <td>extension count</td><td>'.$val11.'</td>
                            </tr>
                            <tr>
                                <td>mainheatc env eff</td><td>'.$val12.'</td>
                            </tr>
                            <tr>
                                <td>address3</td><td>'.$val13.'</td>
                            </tr>
                            <tr>
                                <td>wind turbine count</td><td>'.$val14.'</td>
                            </tr>
                            <tr>
                                <td>tenure</td><td>'.$val15.'</td>
                            </tr>
                            <tr>
                                <td>floor level</td><td>'.$val16.'</td>
                            </tr>
                            <tr>
                                <td>potential energy efficiency</td><td>'.$val17.'</td>
                            </tr>
                            <tr>
                                <td>hot water energy eff</td><td>'.$val18.'</td>
                            </tr>
                            <tr>
                                <td>low energy lighting</td><td>'.$val19.'</td>
                            </tr>
                            <tr>
                                <td>hotwater description</td><td>'.$val20.'</td>
                            </tr>';
                        $address_table .= '</tbody>';
    
                    }
                    $data['address_table'] = $address_table;
                    $data['address_info'] = $address_data;
                    $data['response'] = true;
                }
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