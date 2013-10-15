<?php
class Payments_model extends CI_Model {

	function save_day($data) {
		$this->db->insert('payments',$data);
	}

	function add_payment($db) {
		$this->db->where('date', $db['date'])->where('loanid', $db['loanid'])->update('payments',array('status' => 1));
	}	

	function skip_payment($db) {
		$this->db->where('date', $db['date'])->where('loanid', $db['loanid'])->update('payments',array('status' => 3));
	}

	function get_payments($id) {
		$db = $this->db->where('loanid', $id)->order_by('id', 'inc')->get('payments');

		if($db->num_rows() > 0 ) {
			return $db->result();
		}
		else {
			return false;
		}
	}

	function get_total_payment() {
		$query = $this->db->where('status', 1)->or_where('status', 3)->select_sum('amount');
		return $query->result();
	}

	function get_past_unpaid($id) {
		$db = $this->db->where('loanid',$id)->where('date <=', date('Y-m-d'))->where('status', 0)->get('payments');
		if($db->num_rows() > 0) {
			return $db->result();
		}
		else {
			return false;
		}
	}

	function update_next_amount($data) {
		$query = $this->db->where('date', date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d')))))->where('loanid', $data['loanid'])->get('payments');

		if($query->num_rows() > 0) {
			$next = $query->row();
			$amount = $next->amount + $data['amount'];
		}
		

		$this->db->where('date', date('Y-m-d', strtotime('+1 day', strtotime(date('Y-m-d')))))->where('loanid', $data['loanid'])->update('payments',array('amount' => $amount));
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
		$db = $this->db->select_sum('amount')->where('loanid', $id)->where('status',1)->get('payments');

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