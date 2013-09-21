<?php
class Payments_model extends CI_Model {

	function add_payment($db) {
		$this->db->insert('payments',$db);
	}	

	function get_payments($id) {
		$db = $this->db->where('loanid', $id)->order_by('id', 'desc')->get('payments');

		if($db->num_rows() > 0 ) {
			return $db->result();
		}
		else {
			return false;
		}
	}

	function get_todays_payment($id) {
		$db = $this->db->where('date', date("Y-m-d"))->where('loanid', $id)->get('payments');

		if($db->num_rows() > 0 ) { 
			return $db->result();
		}
		else {
			return false;
		}
	}

	function get_sum($id) {
		$db = $this->db->select_sum('amount')->where('loanid', $id)->get('payments');

		if($db->num_rows() > 0) { 
			$test = $db->row();
			if($test->amount == 0) {
				return 0;
			}
			else {
				return $test->amount;
			}
		}
		else {
			return 0;
		}

	}
}