<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {
	function __construct() {
		parent::__construct();
	}

	function index() {
		$data['title'] = "Users";
		$this->load->view('templates/header_view');
		$this->load->view('templates/sidepanel_view',$data);
		$this->load->view('templates/footer_view');
	}
}