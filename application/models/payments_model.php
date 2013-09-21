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
		$db = $this->db->where('date', date("Y-m-d"))->where('loanid', $id)->get('paymentss');

		if($db->num_rows() > 0 ) { 
			return $db->result();
		}
		else {
			return false;
		}
	}
}