<?php

class Loans_model extends CI_Model {

	function add_loan($db) {
		$this->db->insert('loans',$db);
		return $this->db->insert_id();
	}
	
	function get_loans($id) {
		$select = array(
			'loans.*',
			'borrowers.lastname AS blastname',
			'borrowers.firstname AS bfirstname',
			'borrowers.middlename AS bmiddlename'
			);
		$query = $this->db->select($select)->where('loans.borrowerid', $id)->join('borrowers', 'borrowers.id = loans.borrowerid','left')->get('loans');

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

	function get_total_loan() {
		$query = $this->db->select_sum('amount')->get('loans');
		return $query->result();
	}

	function get_all_loans() {
		$select = array(
			'loans.*',
			'borrowers.lastname AS blastname',
			'borrowers.firstname AS bfirstname',
			'borrowers.middlename AS bmiddlename'
			);
		$query = $this->db->select($select)->join('borrowers', 'borrowers.id = loans.borrowerid','left')->get('loans');

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


	function update_total($bag,$id) {
		$data = array(
			'bag' => $bag
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