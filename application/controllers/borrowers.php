<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Borrowers extends CI_Controller {
	function __construct() {
		parent::__construct();

		if($this->session->userdata('usertype')!== 32) {
            redirect('login');
        }
	}

	function index() {

		$this->load->model('borrowers_model');
		$this->load->model('agents_model');
		$this->load->model('loans_model');
		
		$data['loans'] = $this->loans_model->get_all_loans();

		$lid = $this->loans_model->get_active_loans();
		if($lid) {
			foreach($lid as $entry) {
				$data['loanid'][$entry->id] = $entry->id;
			}
		}
		else {
			$data['loanid'] = FALSE;
		}

		$gid = $this->agents_model->get_agents();
		if($gid) {
			foreach($gid as $entry) {
				 $data['aid'][$entry->id] = $entry->id.' - '.$entry->lastname;
			}
		}
		else {
			$data['aid'] = FALSE;
		}

		$bid = $this->borrowers_model->get_able_borrowers();
		if($bid) {
			foreach($bid as $entry) {
				$data['bid'][$entry->id] = $entry->id.' - '.$entry->lastname;
			}
		}
		else {
			$data['bid'] = FALSE;
		}
		$data['borrowers'] = $this->borrowers_model->get_borrowers();
		$data['title'] = "Borrowers";
		$this->load->view('templates/header_view');
		$this->load->view('templates/sidepanel_view',$data);
		$this->load->view('borrowers/home',$data);
		$this->load->view('templates/footer_view');
	}

	function payment() {
		$this->load->model('transactions_model');
		$this->load->model('loans_model');
		$this->load->model('borrowers_model');
		$this->load->model('settings_model');

		$this->form_validation->set_rules('amount','Amount','required|numeric');

		$lid = $this->loans_model->get_active_loans();
		if($lid) {
			foreach($lid as $entry) {
				$data['loanid'][$entry->id] = $entry->id;
			}
		}
		else {
			$data['loanid'] = FALSE;
		}


		if($this->form_validation->run() !== FALSE) {
			$db = array(
				'loanid' => $this->input->post('loanid'),
				'amount' => $this->input->post('amount'),
				'date' => date('Y-m-d')
				);

			$this->transactions_model->add_transaction($db);
			$loan = $this->loans_model->get_loan($this->input->post('loanid'));
			$total = $loan[0]->total;
			$amount = $loan[0]->amountdue;
			$newtotal = $total + $this->input->post('amount');
			$this->loans_model->update_total($newtotal,$this->input->post('loanid'));

			if($newtotal >= $amount) {
				$this->loans_model->update_status(2,$this->input->post('loanid'));
				$status = array(
					'status' => 0
					);
				$this->borrowers_model->update_status($loan[0]->borrowerid,$status);
			}
			redirect('borrowers/payment');

		}

		$settings = $this->settings_model->get_all();
		$data['settings'] = $settings;
		$data['loans'] = $this->loans_model->get_all_loans();
		$data['title'] = "Add Payment";
		$this->load->view('templates/header_view');
		$this->load->view('templates/sidepanel_view',$data);
		$this->load->view('borrowers/payment',$data);
		$this->load->view('templates/footer_view');
	}

	function add() {
		$this->load->model('borrowers_model');
		$this->load->model('agents_model');
		$this->load->model('sms_model');
		$this->load->model('settings_model');
		$this->form_validation->set_rules('lastname','Last Name','required');
		$this->form_validation->set_rules('firstname','First Name','required');
		$this->form_validation->set_rules('middlename','Middle Name','required');
		$this->form_validation->set_rules('contact','Contact Number', 'required|numeric|exact_length[10]|is_unique[borrowers.contact]|is_unique[agents.contact]');
		$this->form_validation->set_rules('address','Address','required');
		$this->form_validation->set_message('is_unique', "Number already taken.");


		if($this->form_validation->run() !== FALSE) {
			$db = array(
				'lastname' => $this->input->post('lastname'),
				'firstname' => $this->input->post('firstname'),
				'middlename' => $this->input->post('middlename'),
				'address' => $this->input->post('address'),
				'gender' => $this->input->post('gender'),
				'agentid' => $this->input->post('aid'),
				'contact' => $this->input->post('contact')
				);

			$this->borrowers_model->add_borrower($db);

			$settings = $this->settings_model->get_all();
			$message = $settings->message1;
			$this->sms_model->send($message, $this->input->post('contact'));
			redirect('borrowers');

		}
		$data['aid'] = "";
		$gid = $this->agents_model->get_agents();
			if($gid) {
			foreach($gid as $entry) {
				 $data['aid'][$entry->id] = $entry->id.' - '.$entry->lastname;
			}
		}

		$data['borrowers'] = $this->borrowers_model->get_borrowers();
		$data['title'] = "Add Borrower";
		$this->load->view('templates/header_view');
		$this->load->view('templates/sidepanel_view',$data);
		$this->load->view('borrowers/add',$data);
		$this->load->view('templates/footer_view');
	}

	function loan() {

		$this->load->model('borrowers_model');
		$this->load->model('loans_model');
		$this->load->model('sms_model');
		$this->load->model('settings_model');
		$inte = $this->settings_model->get_all();
		$interest = $inte->interest;
		$this->form_validation->set_rules('amount','Amount','required');

		if($this->form_validation->run() !== FALSE) {
			$db = array(
				'amount' => $this->input->post('amount'),
				'borrowerid' => $this->input->post('borrower'),
				'date' => date('Y-m-d'),
				'duedate' => date("Y-m-d",strtotime("+30 day")),
				'status' => 1,
				'amountdue' => $this->input->post('amount') + ($this->input->post('amount') * ($interest/100))
				);

			$this->loans_model->add_loan($db);

			$status = array(
				'status' => 1);
			$this->borrowers_model->update_status( $this->input->post('borrower') ,$status);

			$borrower = $this->borrowers_model->get_borrower( $this->input->post('borrower') );
			$mess = $this->settings_model->get_all();
			$message = $mess->message10;
			$number = $borrower[0]->contact;
			$this->sms_model->send($message,$number);
			redirect('borrowers');

		}

		$bid = $this->borrowers_model->get_able_borrowers();
		if($bid) {
			foreach($bid as $entry) {
				$data['bid'][$entry->id] = $entry->id.' - '.$entry->lastname;
			}
		}
		else {
			$data['bid'] = FALSE;
		}
		$data['borrowers'] = $this->borrowers_model->get_borrowers();
		$data['title'] = "Borrowers";
		$this->load->view('templates/header_view');
		$this->load->view('templates/sidepanel_view',$data);
		$this->load->view('borrowers/loan',$data);
		$this->load->view('templates/footer_view');

	}
	function search($search_by = "lastname", $search_key = "") {
		$this->load->model('borrowers_model');
		$this->form_validation->set_rules('search', 'Last Name', 'required');
		$this->form_validation->set_rules('search_by', 'Search By', 'alpha');
		$this->form_validation->set_message('alpha', 'Please choose search description.');
		if($this->form_validation->run() !== false) {
			redirect('borrowers/search/'.$this->input->post('search_by').'/'.$this->input->post('search'));
		}

		$data['borrowers'] = $this->borrowers_model->search_borrowers($search_by, $search_key);
		$data['title'] = "Search";
		$this->load->view('templates/header_view');
		$this->load->view('templates/sidepanel_view',$data);
		$this->load->view('borrowers/search',$data);
		$this->load->view('templates/footer_view');		
	}

	function view($id) {
		$this->load->model('borrowers_model');
		$this->load->model('loans_model');

		$data['loans'] = $this->loans_model->get_loans($id);
		$activeloan = $this->loans_model->get_active_loan($id);
		if($activeloan) {
			$data['activeloan'] = $activeloan[0];
		}
		else {
			$data['activeloan'] = false;
		}
		$data['borrower'] = $this->borrowers_model->get_borrower($id);
		$data['title'] = "View";
		$this->load->view('templates/header_view');
		$this->load->view('templates/sidepanel_view',$data);
		$this->load->view('borrowers/view',$data);
		$this->load->view('templates/footer_view');		
	}

	function delete($id) {
		try {
			$this->load->model('borrowers_model');
			$this->borrowers_model->delete_borrower($id);
			redirect('borrowers');
		} catch (Exception $e) {
			redirect('borrowers');
		}
	}

    function gen_uuid() {
        return sprintf('%04x',
            // 32 bits for "time_low"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ),
                
            // 16 bits for "time_mid"
            mt_rand( 0, 0xffff ),
                
            // 16 bits for "time_hi_and_version",
            // four most significant bits holds version number 4
            mt_rand( 0, 0x0fff ) | 0x4000,
                    
            // 16 bits, 8 bits for "clk_seq_hi_res",
            // 8 bits for "clk_seq_low",
            // two most significant bits holds zero and one for variant DCE1.1
            mt_rand( 0, 0x3fff ) | 0x8000,
                    
            // 48 bits for "node"
            mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff ), mt_rand( 0, 0xffff )
            );
    } 
}