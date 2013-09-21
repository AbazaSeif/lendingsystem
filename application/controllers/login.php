<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Login extends CI_Controller {
	function __construct() {
		parent::__construct();

		if($this->session->userdata('usertype') == 32) {
            redirect('home');
        }
	}

	public function index()
	{
		
		$this->form_validation->set_rules('username','Username','required');
		$this->form_validation->set_rules('password', 'Password', 'required');
		$data['error'] = '';
		$this->load->model('login_model');
		if($this->form_validation->run() !== false) {
			$verify = $this->login_model->check_login(
				$this->input->post('username'),
				$this->input->post('password')
				);

			if($verify !== FALSE) {
				$sessiondata = array(
                	'username' => $this->input->post('username'),
                	'usertype' => 32,
                	'userID' => $verify->id,
                	'borrowers' => $verify->borrowers,
                	'agents' => $verify->agents,
                	'settings' => $verify->settings,
                	'users' => $verify->users
				);
				$this->session->set_userdata($sessiondata);
				redirect('home');
			}
			else {
				redirect('login');
				$data['error'] = "Wrong Credentials";
			}
		}
		$data['title'] = "Login";
		$this->load->vieW('templates/header_view', $data);
		$this->load->view('login/login_view',$data);
		$this->load->view('templates/footer_view', $data);
		
	}

	function loanHanlder() {
		$this->load->model('payments_model');
		$this->load->model('loans_model');
		$this->load->model('penalty_model');
		$this->load->model('settings_model');
		$this->load->model('borrowers_model');

		// Get all active loans
		$loans = $this->loans_model->get_active_loans();
		if($loans) {
			foreach($loans as $loan) {
				$payents = $this->payments_model->get_todays_payment($loan->id);
				$todayspay = 0;
				foreach($payments as $payment) {
					$todayspay = $todayspay + $payment->amount;
				}
				if($todayspay < ($loan->amountdue/30)) {

					if($loan->bag >= ($loan->amountdue/30)) {
						// get from bag

						// pay todays loan
						$this->payments_model->add_payment(array(
							'loanid' => $loan->id,
							'amount' => $loan->amountdue/30,
							'date' => date('Y-m-d')
							));

						// update bag value
						$newbag = $loan->bag - ($loan->amountdue/30);
						$this->loans_model->update_total($newbag, $loan->id);

						$this->borrowers_model->update_payday($loan->borrowerid, 0);

					}
					else {
						// penalty
						$settings = $this->settings_model->get_all();
						$penaltyamount = $settings->penalty;

						$penalty = array(
							'userid' => $loan->borrowerid,
							'amount' => $penaltyamount,
							'date' => date('Y-m-d')
							);
						$this->penalty_model->add_penalty($penalty);

						// add no pay days
						$borrower = $this->borrowers_model->get_borrower($loan->borrowesid);

						$nopay = $borrower[0]->nopay;
						$nopay++;

						$this->borrowers_model->update_payday($loan->borrowerid, $nopay);

						if($nopay >= 5) {
							// send sms to notify the borrower
						}

					}
				}
				else {
					// do nothing
				}
			}
		}

		//
		echo 'Loans handler.';
	}

	function messagehandler() {
		$this->load->model('sms_model');
		/* change behavior depending on the type of request. If we receive a POST request, then parse the XML document embedded within and save to the database. We can add additional validation logic later */
		if ($_SERVER['REQUEST_METHOD'] == 'POST') {
			$xml = simplexml_load_file('php://input');
			# Parse the XML for parameters
			$sms = array();
			$nodes = $xml->xpath('/message/param');
			foreach($nodes as $node) {
			   $param = (array) $node;
			   $sms[$param['name']] = $param['value'];
			}

			if($sms['messageType'] == 'SMS-NOTIFICATION') {

			   # Delivery status notifications
			   # See http://codecri.me/r/5E for more details

			} elseif($sms['messageType'] == 'SMS') {
			   # Process the SMS here; like forwarding the SMS to your email to see that it worked
				$db = array(
					'number' => substr($sms['source'], -10),
					'message' => $sms['msg'],
					'type' => 2
					);
				$this->sms_model->add_inbox($db);
			   $this->inbox();
			   $this->gateway();
			} elseif($sms['messageType'] == 'MMS') {

			} else {
			   // Unsupported Message Type
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
								'contact' => subsrt($contact, -10),
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

							$this->loans_model->add_loan($db);

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
