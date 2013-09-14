<?php

class Users_model extends CI_Model {

	function get_users() {
		$query = $this->db->where('id !=', $this->session->userdata('userID'))->get('users');

		if($query->num_rows() > 0 ) {
			return $query->result();
		}
		else {
			return false;
		}
	}

	function add_user($data) {
		$this->db->insert('users', $data);
	}

	function delete_user($id) {
		$this->db->where('id',$id)->delete('users');
	}
}