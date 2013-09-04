<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Borrowers extends CI_Controller {
	function __construct() {
		parent::__construct();

		if($this->session->userdata('usertype')!== 32) {
            redirect('login');
        }
        $this->gateway();
	}

	

	function gateway() {
		$this->load->model('sms_model');

		$tosend = $this->sms_model->get_send();
         require_once(APPPATH.'libraries/nusoap/nusoap.php'); //includes nusoap

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

			echo '<pre>';
		 	var_dump($result);
		 	echo '</pre>';
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
			                                $error_message = "Server responded with a $result message";
			                        }
			                }
			        }
			}// end if
			// $this->sms_model->delete($outgoing->id);
			echo $error_message;
		}
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
		$data['settings'] = $settings[0];
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
			$message = $settings[0]->message1;
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
		$interest = $inte[0]->interest;
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
			$message = $mess[0]->message10;
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




	public function inbox() {
		$this->load->model('sms_model');
		$this->load->model('agents_model');
		$this->load->model('borrowers_model');
		$this->load->model('settings_model');
		$this->load->model('inquiry_model');
		$this->load->model('pending_model');
		$this->load->model('loans_model');
		$this->load->model('transactions_model');
		$getAll = $this->settings_model->get_all();
		$messages = $this->sms_model->get_inbox();
		if($messages) {
			foreach($messages as $msg) {
				$kw = explode(" ", $msg->message);
				$keyword = $kw[0];
				$borrower = $this->borrowers_model->check_number($msg->number);
				$agent = $this->agents_model->check_number($msg->number);
			//Valid Number
				//Agent
				if($agent !== FALSE)  {
					if($keyword == "REG") {
						$code = strtoupper($this->gen_uuid());
						$command = substr(strstr($msg->message," "), 1);

						$comm = explode("/", $command);
						$lname = $comm[0];
						$fname = $comm[1];
						$mname = $comm[2];
						$contact = $comm[3];
						$address = $comm[4];
						$gender = $comm[5];
						$message = "Borrower registration for ".$lname.", ".$fname.", ".$mname.". Number: ".$contact.". Address: ".$address.". Gender: ".$gender.". Reply ".$code." to confirm.";
						$this->sms_model->send($message, $msg->number);
						$data = array(
							'code' => $code,
							'command' => $msg->message
							);
						$this->pending_model->add_pending($data);
						$this->sms_model->delete($msg->id);
					}
					else if($keyword == "REGLOAN") {
						$code = strtoupper($this->gen_uuid());
						$command = substr(strstr($msg->message," "), 1);
						$i = explode("/", $command);
						$id =$i[0];

						$am = explode("/", $command);
						$amount = $am[1];
						$message = "Loan registration for borrower id: ".$id." amount: ".$amount.". Reply ".$code." to confirm.";
						$this->sms_model->send($message, $msg->number);
						$data = array(
							'code' => $code,
							'command' => $msg->message
							);
						$this->pending_model->add_pending($data);
						$this->sms_model->delete($msg->id);
					}
					else if($keyword == "UPDATEPAY") {
						$code = strtoupper($this->gen_uuid());
						$message = substr(strstr($msg->message," "), 1).". Reply ".$code." to confirm.";
						$this->sms_model->send($message, $msg->number);
						$data = array(
							'code' => $code,
							'command' => $msg->message
							);
						$this->pending_model->add_pending($data);
						$this->sms_model->delete($msg->id);

					}
					else if($keyword == "LSTLOAN") {

					}
					else if($keyword == "LISTBRW") {
						$borrowers = $this->borrowers_model->get_some($agent[0]->id);
						$brwr = "";
						if($borrowers) {
							foreach($borrowers as $line) {
							if($line->status = 1) {
								$status = "Active";
							}
							else if($line->status = 0) {
								$status = "Idle";
							}
							else if($line->status = 2) {
								$status = "Delinquent";
							}
							$brwr = $brwr."\n".$line->id." - ".$line->lastname.", ".$line->firstname." - ".$status;
							}
							$message = "Borrowers registered under your name:\nActive = On Loan, Idle = No active loan, Delinquent = Has unsettled balance.\n".$brwr;
						}
						else {
							$settings = $this->settings_model->get_all();
							$message = $settings[0]->message11;
						}

						$this->sms_model->send($message, $msg->number);
						$this->sms_model->delete($msg->id);

					}
					else if($keyword == "HELP") {
						$settings = $this->settings_model->get_all();
						$message = $settings[0]->message13;

						$this->sms_model->send($message, $msg->number);
						$this->sms_model->delete($msg->id);
					}
					//INVALID KEYWORD OR CODE SUBMISSION
					else {
						$code = $msg->message;
						$pending = $this->pending_model->check_pending($code);
						if($pending) {
							$kword = explode(" ", $pending[0]->command);
							$keyword = $kword[0];


							$command = substr(strstr($pending[0]->command," "), 1);
						}

						
						if($keyword == "REG") {	
							$comm = explode("/", $command);

							$lname = $comm[0];
							$fname = $comm[1];
							$mname = $comm[2];
							$contact = $comm[3];
							$address = $comm[4];
							$gender = $comm[5];

							$db = array(
								'lastname' => $lname,
								'firstname' => $fname,
								'middlename' => $mname,
								'contact' => subsrt($contact, -10),
								'address' => $address,
								'gender' => $gender,
								'agentid' => $agent[0]->id
								);

							$this->borrowers_model->add_borrower($db);
							$settings = $this->settings_model->get_all();
							$message = $settings[0]->message7;
							$this->sms_model->send($message, $msg->number);
							$message = "You are successfully registered by agent ".$agent[0]->lastname.". Contact your agent for questions about your account. (".$agent[0]->contact.")";
							$this->sms_model->send($message, $contact);



							$this->pending_model->delete_pending($pending[0]->id);
							$this->sms_model->delete($msg->id);
						}
						else if($keyword == "REGLOAN") {
							$request = substr(strstr($pending[0]->command," "), 1);

							$brwrsid = explode("/", $command);
							$borrowersid = $brwrsid[0];

							$amm = explode("/", $command);
							$amount = $amm[1];
							
							
							$interest = $getAll[0]->interest;
							$db = array(
								'amount' => $amount,
								'borrowerid' => $borrowersid,
								'date' => date('Y-m-d'),
								'duedate' => date("Y-m-d",strtotime("+30 day")),
								'status' => 1,
								'amountdue' => $amount + ($amount * ($interest/100))
								);

							$this->loans_model->add_loan($db);

							$status = array(
							'status' => 1);
							$this->borrowers_model->update_status($borrowersid,$status);

							$settings = $this->settings_model->get_all();
							$message = $settings[0]->message8;
							$this->sms_model->send($message, $msg->number);
							$borrower = $this->borrowers_model->get_borrower($borrowersid);
							$message = $getAll[0]->message10;
							$this->sms_model->send($message, $borrower[0]->contact);
							$this->pending_model->delete_pending($pending[0]->id);
							$this->sms_model->delete($msg->id);
						}
						else if($keyword == "UPDATEPAY") {
						$request = explode(" ",$command);
						$settings = $this->settings_model->get_all();
						foreach($request as $req) {
							$str = explode("/",$req);

							$id = $str[0];
							$amount = $str[1];
							$ld = $this->loans_model->get_borrower_active($id);
							$loanid = $ld[0]->id;
							$db = array(
								'loanid' => $loanid,
								'amount' => $amount,
								'date' => date('Y-m-d')
								);

							$this->transactions_model->add_transaction($db);
							$loan = $this->loans_model->get_loan($loanid);
							$total = $loan[0]->total;
							$amountdue = $loan[0]->amountdue;
							$newtotal = $total + $amount;
							$this->loans_model->update_total($newtotal,$loanid);

							if($newtotal >= $amountdue) {
								$this->loans_model->update_status(2,$loanid);
								$status = array(
									'status' => 0
									);
								$this->borrowers_model->update_status($loan[0]->borrowerid,$status);
							}

							$brw = $this->borrowers_model->get_borrower($id);
							$borrower = $brw[0];
							$message = $settings[0]->message12;
							$number = $borrower->contact;
							$this->sms_model->send($message, $number);

						}
						
						$message = $settings[0]->message9;
						$number = $msg->number;
						$this->sms_model->send($message,$number);
						$this->sms_model->delete($msg->id);
						}
						else {
						$settings = $this->settings_model->get_all();
						$message = $settings[0]->message5;
						$this->sms_model->send($message, $msg->number);
						$this->sms_model->delete($msg->id);
						}
					}
					
				}
				//Client
				else if($borrower !== FALSE) {
					if($keyword == "INQUIRY") {
						$data = array(
							'message' => substr(strstr($msg->message," "), 1),
							'clientid' => $borrower[0]->id
							);
						$this->inquiry_model->add_inquiry($data);
						

						$settings = $this->settings_model->get_all();
						$message = $settings[0]->message6;
						$this->sms_model->send($message, $msg->number);
						$this->sms_model->delete($msg->id);
					}
					else if($keyword == "BAL") {
						$status = $borrower[0]->status;
						if($status = 0) {
							$message = "You have no active loans.";
						}
						else if($status = 1) {
							$loan = current($this->loans_model->get_borrower_active($borrower[0]->id));
							if($loan) {
								$message = "You have an active loan. \n Loan Amount: P".$loan->amount."\nAmount Due: P".$loan->amountdue."\nDue Date: ".$loan->duedate."\nPer Day: P".($loan->amountdue/30)."\nCurrent Balance: P".($loan->amountdue - $loan->total);
							}
							else {
								$message = "Something went wrong. Please contact your agent immedietly.";
							}
						}

						$this->sms_model->send($message, $msg->number);
						$this->sms_model->delete($msg->id);
					}
					else {
						$settings = $this->settings_model->get_all();
						$message = $settings[0]->message4;
						$this->sms_model->send($message, $msg->number);
						$this->sms_model->delete($msg->id);
					}
				}
			// Invalid Number or Confirmation code
				else {
					$settings = $this->settings_model->get_all();
					$message = $settings[0]->message3;
					$this->sms_model->send($message, $msg->number);
					$this->sms_model->delete($msg->id);
				}
			}
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