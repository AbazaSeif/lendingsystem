<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Agents extends CI_Controller {
	function __construct() {
		parent::__construct();

		if($this->session->userdata('usertype')!== 32) {
            redirect('login');
        }
	}

	function index() {
		$this->load->model('agents_model');
		$this->load->model('borrowers_model');
		$data['agents'] = $this->agents_model->get_agents();

		if($data['agents']) {
			foreach($data['agents'] as $agent) {
				$data['borrowers'][$agent->id] = $this->borrowers_model->get_some($agent->id);
			}
		}
		
		
		$data['title'] = "Agents";
		$this->load->view('templates/header_view');
		$this->load->view('templates/sidepanel_view',$data);
		$this->load->view('agents/home',$data);
		$this->load->view('templates/footer_view');
	}

	function delete($id) {
		try {
			$this->load->model('agents_model');
			$this->agents_model->delete_agent($id);
			redirect('agents');
		} catch (Exception $e) {
			redirect('agents');
		}
		
	}

	function add() {
		$this->load->model('agents_model');
		$this->load->model('sms_model');
		$this->load->model('settings_model');
		$this->form_validation->set_rules('lastname', 'Last Name', 'required');
		$this->form_validation->set_rules('firstname', 'First Name', 'required');
		$this->form_validation->set_rules('middlename', 'Middle Name', 'required');
		$this->form_validation->set_rules('contact', 'Contact Number', 'required|numeric|exact_length[10]|is_unique[borrowers.contact]|is_unique[agents.contact]');
		$this->form_validation->set_rules('address', 'Address', 'required');
		$this->form_validation->set_message('is_unique', "Number already taken.");

		if($this->form_validation->run() !== false) {
			$db = array(
				'lastname' => $this->input->post('lastname'),
				'firstname' => $this->input->post('firstname'),
				'middlename' => $this->input->post('middlename'),
				'address' => $this->input->post('address'),
				'contact' => $this->input->post('contact')
				);
			$this->agents_model->add_agent($db);

			$settings = $this->settings_model->get_all();
			$message = $settings->message2;
			$this->sms_model->send($message, $this->input->post('contact'));
			$this->gateway();
			redirect('agents');
		}
		$data['agents'] = $this->agents_model->get_agents();
		$data['title'] = "Add Agent";
		$this->load->view('templates/header_view');
		$this->load->view('templates/sidepanel_view',$data);
		$this->load->view('agents/add',$data);
		$this->load->view('templates/footer_view');
	}
	function search($search_by = "lastname", $search_key = "") {
		$this->load->model('agents_model');
		$this->form_validation->set_rules('search', 'Last Name', 'required');
		$this->form_validation->set_rules('search_by', 'Search By', 'alpha');
		$this->form_validation->set_message('alpha', 'Please choose search description.');
		if($this->form_validation->run() !== false) {
			redirect('agents/search/'.$this->input->post('search_by').'/'.$this->input->post('search'));
		}

		$data['agents'] = $this->agents_model->search_agents($search_by, $search_key);
		$data['title'] = "Search Agent";
		$this->load->view('templates/header_view');
		$this->load->view('templates/sidepanel_view',$data);
		$this->load->view('agents/search',$data);
		$this->load->view('templates/footer_view');		
	}

	function view($id) {
		$this->load->model('agents_model');
		$this->load->model('borrowers_model');
		$this->load->model('loans_model');
		$this->load->model('settings_model');
		$this->load->model('payments_model');

		$interest = $this->settings_model->get_all()->interest;
		$commision = $this->settings_model->get_all()->commision;
		$data['total'] = 0;
		$data['commision'] = $this->settings_model->get_all()->commision;
		$data['borrowers'] = $this->borrowers_model->get_some($id);
		$data['agent'] = $this->agents_model->get_agent($id);
		foreach($data['borrowers'] as $borrower) {
			$data['loans'][$borrower->id] = $loans =  $this->loans_model->get_loans($borrower->id);

			if($loans) {
				foreach($loans as $loan) {
				$data['percent'][$loan->id] = $this->payments_model->get_sum($loan->id);
				}
			}

			$finishedloans[$borrower->id] = $this->loans_model->get_finished_loans($borrower->id);
			if($finishedloans[$borrower->id]) {
			foreach($finishedloans[$borrower->id] as $loan) {
				$data['total'] = $data['total'] + (($loan->amount * $interest/100) * ($commision/100));
			}
			}
		}
		

		$data['title'] = "View Agent";
		$this->load->view('templates/header_view');
		$this->load->view('templates/sidepanel_view',$data);
		$this->load->view('agents/view',$data);
		$this->load->view('templates/footer_view');
	}

	function gateway() {
		$this->load->model('sms_model');

		$tosend = $this->sms_model->get_send();

         require_once(APPPATH.'libraries/nusoap/nusoap'.EXT); //includes nusoap
         // Same as application/libraries/nusoap/nusoap.php
        if($tosend) {
         foreach($tosend as $outgoing) {
	         $client = new nusoap_client('http://iplaypen.globelabs.com.ph:1881/axis2/services/Platform/');
	         $client->soap_defencoding = 'UTF-8';
	         $err = $client->getError();

			if ($err) {// Display the error
				$error_message = 'Constructor error: ' . $err;
			}

		if (1 == 1) {// Call the SOAP method, note the definition of the xmlnamespace as the third parameter in the call and how the posted message is added to the message string
			$result = $client->call('sendSMS', array(
				'uName' => '4pcf414hq',
				'uPin' => '21738474',
				'MSISDN' => '0'.substr($outgoing->number,-10),
				'messageString' => $outgoing->message,
				'Display' => '1', // 1 for normal message
				'udh' => '',
				'mwi' => '',
				'coding' => '0' ),
				"http://ESCPlatform/xsd");

		 
			// Check for a fault
			        if ($client->fault)
			        {
			                $error_message = "Fault Generated: \n";
			        }
			        else
			        {// Check for errors
			                $err = $client->getError();
			 
			                if ($err)
			                {// Display the error
			                        $error_message = "An unknown error was generated: \n";
			                }
			                else
			                {// Display the result
			                        if ($result == "201")
			                        {
			                                $error_message = "Message was successfully sent!";
			                        }
			                        else
			                        {
			        //                 	$result = $client->call('sendSMS', array(
											// 'uName' => 's85qb1stk',
											// 'uPin' => '21737167',
											// 'MSISDN' => '0'.substr($outgoing->number,-10),
											// 'messageString' => $outgoing->message,
											// 'Display' => '1', // 1 for normal message
											// 'udh' => '',
											// 'mwi' => '',
											// 'coding' => '0' ),
											// "http://ESCPlatform/xsd");

			                                $error_message = "Server responded with a $result message";
			                        }
			                }
			        }
			}// end if
			$this->sms_model->delete($outgoing->id);
		}
	}

	}


}