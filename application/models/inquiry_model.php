<?php 

class Inquiry_model extends CI_Model {
	
	function add_inquiry($data) {
		$this->db->insert('inquiry', $data);
	}
}