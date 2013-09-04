<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Reports extends CI_Controller {
	function __construct() {
		parent::__construct();

		if($this->session->userdata('usertype')!== 32) {
            redirect('login');
        }
	}

	function index() {
		$this->load->view('home/home_view');
	}
}