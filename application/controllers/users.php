<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Users extends CI_Controller {
	function __construct() {
		parent::__construct();
		$this->load->model('users_model');
	}

	function index() {

		$this->form_validation->set_rules('username','Username','required|is_unique[users.username]');
		$this->form_validation->set_rules('dummypassword','Password','required');
		$this->form_validation->set_rules('password','Repeat password','required|matches[dummypassword]');


		if($this->form_validation->run() !== false) {
			$db = array(
				'username' => $this->input->post('username'),
				'password' => sha1($this->input->post('password')),
				'borrowers' => $this->input->post('borrowers'),
				'agents' => $this->input->post('agents'),
				'settings' => $this->input->post('settings'),
				'users' => $this->input->post('users')
				);

			$this->users_model->add_user($db);
			$this->session->set_flashdata(array('useradd' => true));
			redirect('users');
		}

		$data['users'] = $this->users_model->get_users();
		$data['title'] = "Users";
		$this->load->view('templates/header_view');
		$this->load->view('templates/sidepanel_view',$data);
		$this->load->view('users/home',$data);
		$this->load->view('templates/footer_view');
	}

	function delete($id) {
		$this->users_model->delete_user($id);
		$this->session->set_flashdata(array('userdelete' => true));
		redirect('users');
	}
}