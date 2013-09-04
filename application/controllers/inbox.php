<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Inbox extends CI_controller {
	function __construct() {
		parent::__construct();

		if($this->session->userdata('usertype')!== 32) {
            redirect('login');
        }
	}

	function index() {

		$this->load->model('sms_model');
		$data['sms'] = $this->sms_model->get_all();

		$data['title'] = "SMS";
		$this->load->view('templates/header_view.php');
		$this->load->view('templates/sidepanel_view.php');
		$this->load->view('inbox/home.php',$data);
		$this->load->view('templates/footer_view.php');

	}
}