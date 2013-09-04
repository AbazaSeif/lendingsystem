<?php

class Pending_model extends CI_Model {
	function add_pending($data) {
		$this->db->insert('pending', $data);
	}

	function check_pending($code) {
		$query = $this->db->where('code', $code)->limit(1)->get('pending');

		if($query->num_rows() > 0 ) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function delete_pending($id) {
		$this->db->where('id', $id)->delete('pending');
	}
}