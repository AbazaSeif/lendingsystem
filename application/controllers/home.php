<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Home extends CI_Controller {
	function __construct() {
		parent::__construct();

		if($this->session->userdata('usertype')!== 32) {
            redirect('login');
        }
	}

	function ajaxPosition() {
		$top = $this->input->post('top');
		$left = $this->input->post('left');

		$coords = array('top' => $top, 'left' => $left);

		$this->session->set_userdata($coords);
	}

	function index() {
		$data['title'] = "Lending System";
		$this->load->view('templates/header_view',$data);
		$this->load->view('templates/sidepanel_view',$data);
		$this->load->view('home/home_view');
		$this->load->view('templates/footer_view');
	}

	function logout() {
		$this->session->sess_destroy();
		redirect('login');
	}
}