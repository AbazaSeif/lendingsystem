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
			$this->inbox();
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
		$data['borrowers'] = $this->borrowers_model->get_some($id);
		if($data['borrowers']) {
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
				'uName' => 'so9mcfhyp',
				'uPin' => '21737147',
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
			                        	$result2 = $client->call('sendSMS', array(
											'uName' => '4xw4dtnjk',
											'uPin' => '21737147',
											'MSISDN' => '0'.substr($outgoing->number,-10),
											'messageString' => $outgoing->message,
											'Display' => '1', // 1 for normal message
											'udh' => '',
											'mwi' => '',
											'coding' => '0' ),
											"http://ESCPlatform/xsd");

			                        	if($result2 !== "201") {
			                        		$result3 = $client->call('sendSMS', array(
											'uName' => 'qm273wsfi',
											'uPin' => '21737254',
											'MSISDN' => '0'.substr($outgoing->number,-10),
											'messageString' => $outgoing->message,
											'Display' => '1', // 1 for normal message
											'udh' => '',
											'mwi' => '',
											'coding' => '0' ),
											"http://ESCPlatform/xsd");
			                        	}

			                                $error_message = "Server responded with a $result message";
			                        }
			                }
			        }
			}// end if
			$this->sms_model->delete($outgoing->id);
		}
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
		$this->load->model('payments_model');
		$getAll = $this->settings_model->get_all();
		$messages = $this->sms_model->get_inbox();
		if($messages) {
			foreach($messages as $msg) {
				$kw = explode(" ", $msg->message);
				$keyword = strtoupper($kw[0]);
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
					else if($keyword == "COMM") {
						$borrowers = $this->borrowers_model->get_some($id);
						$data['total'] = 0;
						foreach($borrowers as $borrower) {
							$finishedloans[$borrower->id] = $this->loans_model->get_finished_loans($borrower->id);
							if($finishedloans[$borrower->id]) {
							foreach($finishedloans[$borrower->id] as $loan) {
								$data['total'] = $data['total'] + (($loan->amount * $interest/100) * ($commision/100));
							}
							}
						}

						$message = "Total commision: P".$data['total'];
						$this->sms_model->send($message, $msg->number);
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
							$message = $settings->message11;
						}

						$this->sms_model->send($message, $msg->number);
						$this->sms_model->delete($msg->id);

					}
					else if($keyword == "HELP") {
						$settings = $this->settings_model->get_all();
						$message = $settings->message13;

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
								'contact' => substr($contact, -10),
								'address' => $address,
								'gender' => $gender,
								'agentid' => $agent[0]->id
								);

							$this->borrowers_model->add_borrower($db);
							$settings = $this->settings_model->get_all();
							$message = $settings->message7;
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
							
							
							$interest = $getAll->interest;
							$db = array(
								'amount' => $amount,
								'borrowerid' => $borrowersid,
								'date' => date('Y-m-d'),
								'duedate' => date("Y-m-d",strtotime("+30 day")),
								'status' => 1,
								'amountdue' => $amount + ($amount * ($interest/100))
								);

							$loanid = $this->loans_model->add_loan($db);

							$days = $this->generate_days(date('Y-m-d'));
							foreach($days as $day) {
								$db = array(
									'loanid' => $loanid,
									'date' => $day,
									'amount' => ( $amount + ( $amount * ($interest/100) ) ) /30 ,
									'status' => 0
									);
								$this->payments_model->save_day($db);
							}

							$status = array(
							'status' => 1);
							$this->borrowers_model->update_status($borrowersid,$status);

							$settings = $this->settings_model->get_all();
							$message = $settings->message8;
							$this->sms_model->send($message, $msg->number);
							$borrower = $this->borrowers_model->get_borrower($borrowersid);
							$message = $getAll->message10;
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

							$this->payments_model->add_payment($db);
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
							$message = $settings->message12;
							$number = $borrower->contact;
							$this->sms_model->send($message, $number);

						}
						
						$message = $settings->message9;
						$number = $msg->number;
						$this->sms_model->send($message,$number);
						$this->sms_model->delete($msg->id);
						}
						else {
						$settings = $this->settings_model->get_all();
						$message = $settings->message5;
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
						$message = $settings->message6;
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
								$loantotal[$loan->id] = $this->payments_model->get_sum($loan->id);
								$message = "You have an active loan. \n Loan Amount: P".$loan->amount."\nAmount Due: P".$loan->amountdue."\nDue Date: ".$loan->duedate."\nPer Day: P".($loan->amountdue/30)."\nCurrent Balance: P".($loan->amountdue - $loantotal[$loan->id]);
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
						$message = $settings->message4;
						$this->sms_model->send($message, $msg->number);
						$this->sms_model->delete($msg->id);
					}
				}
			// Invalid Number or Confirmation code
				else {
					$settings = $this->settings_model->get_all();
					$message = $settings->message3;
					$this->sms_model->send($message, $msg->number);
					$this->sms_model->delete($msg->id);
				}
			}
		}
		$this->gateway();
	}

	function generate_days($today) {
		for($i = 0; $i < 30; $i++) {
			$days[$i] = date('Y-m-d', strtotime('+'.($i + 1).' day', strtotime($today)));	
		}
		return $days;
	}

}