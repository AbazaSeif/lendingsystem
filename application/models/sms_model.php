<?php
class Sms_model extends CI_Model {

	function send($message,$number) {
		$data = array(
			'message' => $message,
			'number' => $number,
			'type' => 1
			);
		$this->db->insert('sms',$data);
	}

	function get_all() {
		$query = $this->db->get('sms');

		if($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function get_inbox() {
		$query = $this->db->where('type', 2)->get('sms');

		if($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function delete($id) {
		$this->db->where('id',$id)->delete('sms');
	}

	function get_send() {
		$query = $this->db->where('type', 1)->get('sms');

		if($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return false;
		}
	}

	function add_inbox($db) {
		$this->db->insert('sms', $db);
	}

}