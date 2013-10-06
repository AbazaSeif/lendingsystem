<?php

class Forgotpassword_model extends CI_model {
	function save_hash($data) {
		$this->db->insert('forgotpassword', $data);
	}

	function get_hash($hash) {
		$query = $this->db->where('hash' $hash)->get('forgotpassword');
		if($query->num_rows() > 0) {
			return $query->row();
		}
		else {
			return false;
		}
	}
}