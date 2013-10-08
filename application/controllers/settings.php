<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Settings extends CI_Controller {
	function __construct() {
		parent::__construct();

		if($this->session->userdata('usertype')!== 32) {
            redirect('login');
        }
	}

	function index() {
		$this->load->model('settings_model');

		$this->form_validation->set_rules('message1','Message 1','required');
		$this->form_validation->set_rules('message2','Message 2','required');
		$this->form_validation->set_rules('message3','Message 3','required');
		$this->form_validation->set_rules('message4','Message 4','required');
		$this->form_validation->set_rules('message5','Message 5','required');
		$this->form_validation->set_rules('message6','Message 6','required');
		$this->form_validation->set_rules('message7','Message 7','required');
		$this->form_validation->set_rules('message8','Message 8','required');
		$this->form_validation->set_rules('message9','Message 9','required');		
		$this->form_validation->set_rules('message10','Message 10','required');
		$data['info'] = FALSE;
		if($this->form_validation->run() !== FALSE) {
			$db = array(
				'message1' => $this->input->post('message1'),
				'message2' => $this->input->post('message2'),
				'message3' => $this->input->post('message3'),
				'message4' => $this->input->post('message4'),
				'message5' => $this->input->post('message5'),
				'message6' => $this->input->post('message6'),
				'message7' => $this->input->post('message7'),
				'message8' => $this->input->post('message8'),
				'message9' => $this->input->post('message9'),
				'message10' => $this->input->post('message10'),
				'message11' => $this->input->post('message11'),
				'message12' => $this->input->post('message12'),
				'message13' => $this->input->post('message13'),
				'penalty' => $this->input->post('penalty'),
				'interest' => $this->input->post('interest'),
				'commision' => $this->input->post('commision')
				);
			$this->settings_model->update($db);
			$data['info'] = "Settings Updated!";
		}



		
		$data['settings'] = $this->settings_model->get_all();
		$data['title'] = "Settings";
		$this->load->view('templates/header_view');
		$this->load->view('templates/sidepanel_view',$data);
		$this->load->view('settings/home.php',$data);
		$this->load->view('templates/footer_view');
	}

	function profile() {
		$this->load->model('login_model');
		$this->load->model('settings_model');

		$this->form_validation->set_error_delimiters('<div class="alert alert-error">', '</div>');
		$this->form_validation->set_rules('newpassword','Password','required');
		$this->form_validation->set_rules('repeat','Password','required|matches[newpassword]');

		if($this->form_validation->run() !== FALSE) {
			$this->settings_model->change_pass($this->session->userdata('userID'), $this->input->post('repeat'));
			$this->session->set_flashdata(array('changepass' => true));
			redirect('settings/profile');
		}



		$data['title'] = "Profile";
		$this->load->view('templates/header_view');
		$this->load->view('templates/sidepanel_view', $data);
		$this->load->view('settings/changepass',$data);
		$this->load->view('templates/footer_view');
	}
}