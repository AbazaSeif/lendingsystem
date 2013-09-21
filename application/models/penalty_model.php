<?php

class Penalty_model extends CI_model {
	function add_penalty($data) {
		$this->db->insert('penalty', $data);
	}
}