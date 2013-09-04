<?php

class Loans_model extends CI_Model {

	function add_loan($db) {
		$this->db->insert('loans',$db);
	}
	
	function get_loans($id) {
		$query = $this->db->where('borrowerid', $id)->get('loans');

		if($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function get_finished_loans($id) {
		$query = $this->db->where('borrowerid', $id)->where('status', 2)->get('loans');

		if($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function get_active_loan($id) {
		$query = $this->db->where('borrowerid',$id)->where('status',1)->limit(1)->get('loans');

		if($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function get_active_loans() {
		$query = $this->db->where('status',1)->get('loans');

		if($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function get_all_loans() {
		$query = $this->db->get('loans');

		if($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function get_loan($id) {
		$query = $this->db->where('id', $id)->get('loans');

		if($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}


	function update_total($total,$id) {
		$data = array(
			'total' => $total
			);
		$this->db->where('id',$id)->update('loans',$data);
	}

	function update_status($stat,$id) {
		$data = array(
			'status' => $stat
			);

		$this->db->where('id',$id)->update('loans',$data);
	}

	function get_borrower_active($id) {
		$query = $this->db->where('status', 1)->where('borrowerid', $id)->limit(1)->get('loans');

		if($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	} 
}