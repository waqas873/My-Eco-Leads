<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Typeform_leads extends CI_Controller 
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
		$this->load->model('leads_new_model', 'leads_new');
	}
	
	public function storeLead()
	{
		$this->createLog("<====================================>");
		$input = file_get_contents('php://input');
		$input = str_replace("'", '', $input);
		$input = json_decode($input , true);
		if(!empty($input['form_response']['answers'])){
			$answers = $input['form_response']['answers'];
			$save = [];
			$save['postal_code'] = $answers[0]['text'];
			$save['house_no'] = $answers[1]['text'];
			$save['name'] = $answers[2]['text'];
			$save['email'] = $answers[3]['email'];
			$save['property_type'] = $answers[4]['choice']['label'];
			$save['household_benefits'] = $answers[5]['choice']['label'];
			$save['residential_status'] = $answers[6]['choice']['label'];
			$save['floor_type'] = $answers[7]['choice']['label'];
			$save['fuel_type'] = $answers[8]['choice']['label'];
			$save['heating_system'] = $answers[9]['choice']['label'];
			$save['phone'] = $answers[10]['phone_number'];
			if($id = $this->leads_new->save($save)){

			}
			else{
				$this->createLog($this->db->last_query());
			}
		}
	}

	public function storeLeadElectric()
	{
		// $input = '{"event_id":"01FXT1PMBNP8GS8AR34P69KSDN","event_type":"form_response","form_response":{"form_id":"HtJh3p5v","token":"2y9zs58y6lf4ow0hsnx2y9zslz2ndacz","landed_at":"2022-03-10T13:50:17Z","submitted_at":"2022-03-10T13:53:41Z","definition":{"id":"HtJh3p5v","title":"Electric Only","fields":[{"id":"tbEwmLEEdRLu","title":"*What is your postcode?*","type":"long_text","ref":"fdaca680-38a1-4a03-99bb-b0e38f7a3151","properties":{}},{"id":"HiOnmXSPIWt9","title":"*What is your house number?*","type":"short_text","ref":"b452c896-8582-42be-827a-5bc92c6ec053","properties":{}},{"id":"xYOl2l25aks6","title":"*What is your full name?*","type":"long_text","ref":"52290f9f-8b7e-4e22-ae98-ef5f675a78ea","properties":{}},{"id":"psDb0hjzLwn6","title":"*Whats your best Email address?  *","type":"email","ref":"808c0935-f268-4e50-b251-af26e2df98dc","properties":{}},{"id":"JQ8lxQhymXAI","title":"*Important - *What is your properties current fuel type?","type":"multiple_choice","ref":"c7788a9a-9386-461c-b7ac-1577f8897f8c","properties":{},"choices":[{"id":"rKDqu7u7zSvT","label":"Electric Storage Heaters"},{"id":"jOZJuM4rYgCa","label":"Electric Plug In Heaters"},{"id":"4ub7hv91oOGh","label":"Oil"},{"id":"uDtV3lWY4I2B","label":"LPG and Radiators Bottled"},{"id":"rIMuFwfMIOX6","label":"LPG and Radiators Tank"},{"id":"HvGBtZAGsKpk","label":"Gas Fire (Without Boiler)"},{"id":"2SY9gYtE2Tc7","label":"Solid Fuel ( Coal Fire/ Log Burners)"},{"id":"PtOmJ3zokLxm","label":"Electric Panel Heaters"},{"id":"sx6Gq3UvUmzV","label":"No Heating System Installed"},{"id":"JvytEwMIDREl","label":"Oil"},{"id":"3PtZBCeloCac","label":"Warm Air (Electric Blowers)"},{"id":"KGppnv5xwQYb","label":"Warm Air (Gas)"},{"id":"oBYzrBnEmV5n","label":"Not Sure"},{"id":"7wa5JTZAVf5k","label":"Gas with boiler system/radiators"}]},{"id":"EtIPMbMo5Hra","title":"*Great!* We are nearly finished. What best describes your Property Type?","type":"multiple_choice","ref":"cc5ae59c-16dd-401e-bb7f-3a237294b573","properties":{},"choices":[{"id":"wIaYTWWh8kLx","label":"Mid Terrace"},{"id":"isI5TWTe42Y7","label":"End Terrace"},{"id":"rUUfZgZZVCYK","label":"Semi Detached"},{"id":"NTwbjtOUfQYh","label":"Detached"},{"id":"qa2ZKG3XwEze","label":"Flat"},{"id":"SyXwq2gZo9DO","label":"Apartment"},{"id":"FCdqhYJrTdth","label":"Bungalow"}]},{"id":"rgl1DSsbANSF","title":"*Does anybody in your household currently receive any Benefits or Credits?*","type":"multiple_choice","ref":"273fdeb4-fbc4-4eb7-bc36-bfd203e622ea","properties":{},"choices":[{"id":"l9Sdqq1e0oG7","label":"Carers Allowance"},{"id":"ErapI0mpZNez","label":"Child Benefit"},{"id":"juyUGTsC8j9U","label":"Child Tax Credits"},{"id":"1mh94XPNxuum","label":"Income Support"},{"id":"M1ORJFque5wL","label":"Universal Credit"},{"id":"Q4qCbG7ToBCy","label":"Working Tax Credit"},{"id":"X4mMg9bgaEtG","label":"Other"},{"id":"Djo8xPN6IIxq","label":"None"}]},{"id":"UFEbHI81v93K","title":"Residential status? \n","type":"multiple_choice","ref":"648d40ba-d902-4603-8292-6c05261c0c1e","properties":{},"choices":[{"id":"K0soaguOZ088","label":"Homeowner"},{"id":"zqEjKXXQS2tx","label":"Living with homeowner"},{"id":"pZ6P97Ywk0ge","label":"Tennant"}]},{"id":"QHd8IDPDkCz5","title":"*Please enter your Mobile Phone Number* ","type":"phone_number","ref":"3dbb22de-94be-4a3f-b883-b44913b297ba","properties":{}}]},"answers":[{"type":"text","text":"post1234","field":{"id":"tbEwmLEEdRLu","type":"long_text","ref":"fdaca680-38a1-4a03-99bb-b0e38f7a3151"}},{"type":"text","text":"house no 12345","field":{"id":"HiOnmXSPIWt9","type":"short_text","ref":"b452c896-8582-42be-827a-5bc92c6ec053"}},{"type":"text","text":"full name waqas","field":{"id":"xYOl2l25aks6","type":"long_text","ref":"52290f9f-8b7e-4e22-ae98-ef5f675a78ea"}},{"type":"email","email":"muhammadw8737@gmail.com","field":{"id":"psDb0hjzLwn6","type":"email","ref":"808c0935-f268-4e50-b251-af26e2df98dc"}},{"type":"choice","choice":{"label":"Electric Storage Heaters"},"field":{"id":"JQ8lxQhymXAI","type":"multiple_choice","ref":"c7788a9a-9386-461c-b7ac-1577f8897f8c"}},{"type":"choice","choice":{"label":"Mid Terrace"},"field":{"id":"EtIPMbMo5Hra","type":"multiple_choice","ref":"cc5ae59c-16dd-401e-bb7f-3a237294b573"}},{"type":"choice","choice":{"label":"Child Benefit"},"field":{"id":"rgl1DSsbANSF","type":"multiple_choice","ref":"273fdeb4-fbc4-4eb7-bc36-bfd203e622ea"}},{"type":"choice","choice":{"label":"Homeowner"},"field":{"id":"UFEbHI81v93K","type":"multiple_choice","ref":"648d40ba-d902-4603-8292-6c05261c0c1e"}},{"type":"phone_number","phone_number":"+447458038791","field":{"id":"QHd8IDPDkCz5","type":"phone_number","ref":"3dbb22de-94be-4a3f-b883-b44913b297ba"}}]}}';
		// debug(json_decode($input , true) , true);

		$this->createLog("<===This is electric lead===>");
		$input = file_get_contents('php://input');
		$input = str_replace("'", '', $input);
		$this->createLog($input);
		$input = json_decode($input , true);
		if(!empty($input['form_response']['answers'])){
			$answers = $input['form_response']['answers'];
			$save = [];
			$save['postal_code'] = $answers[0]['text'];
			$save['house_no'] = $answers[1]['text'];
			$save['name'] = $answers[2]['text'];
			$save['email'] = $answers[3]['email'];
			$save['fuel_type'] = $answers[4]['choice']['label'];
			$save['property_type'] = $answers[5]['choice']['label'];
			$save['household_benefits'] = $answers[6]['choice']['label'];
			$save['residential_status'] = $answers[7]['choice']['label'];
			$save['phone'] = $answers[8]['phone_number'];
			if($id = $this->leads_new->save($save)){

			}
			else{
				$this->createLog($this->db->last_query());
			}
		}
	}

	public function saveLead()
	{
		$data = [];
		$data['response'] = false;
		$data['msg'] = '';
		if(empty($this->input->post())){
			$data['response'] = "error";
		    $data['msg'] = 'Invalid request';
		    echo json_encode($data); exit;
		}
		$formData = $this->input->post();
		//$formData = ['property_type'=>'hello','floor_type'=>'test'];
		$save = [];
		$save['postal_code'] = (!empty($formData['postal_code']))?$formData['postal_code']:'N/A';
		$save['house_no'] = (!empty($formData['house_no']))?$formData['house_no']:'N/A';
		$save['name'] = (!empty($formData['name']))?$formData['name']:'N/A';
		$save['email'] = (!empty($formData['email']))?$formData['email']:'N/A';
		$save['property_type'] = (!empty($formData['property_type']))?$formData['property_type']:'N/A';
		$save['household_benefits'] = (!empty($formData['household_benefits']))?$formData['household_benefits']:'N/A';
		$save['residential_status'] = (!empty($formData['residential_status']))?$formData['residential_status']:'N/A';
		$save['floor_type'] = (!empty($formData['floor_type']))?$formData['floor_type']:'N/A';
		$save['fuel_type'] = (!empty($formData['fuel_type']))?$formData['fuel_type']:'N/A';
		$save['heating_system'] = (!empty($formData['heating_system']))?$formData['heating_system']:'N/A';
		$save['phone'] = (!empty($formData['phone']))?$formData['phone']:'N/A';
		if($id = $this->leads_new->save($save)){
            $data['response'] = "SUCCESS";
		    $data['msg'] = 'Lead saved successfully';
		    echo json_encode($data); exit;
		}
		$this->createLog($this->db->last_query());
		$data['response'] = "error";
	    $data['msg'] = 'Error occured while saving lead';
	    echo json_encode($data); exit;
	}

	public function createLog($data = '')
	{
	    if(empty($data)){
	        return false;
	    }
	    $log_filename = "logsss";
	    if (!file_exists($log_filename)){
	        mkdir($log_filename, 0777, true);
	    }
	    $log_file_data = $log_filename.'/log_' . date('d-M-Y') . '.log';
	    file_put_contents($log_file_data, $data."\n\n", FILE_APPEND);
	    return false;
	}
	
}