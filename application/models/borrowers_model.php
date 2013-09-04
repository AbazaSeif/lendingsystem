<?php
class Borrowers_model extends CI_Model {

	function get_borrowers() {
		$query = $this->db->get('borrowers');

		if($query->num_rows() > 0 ) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function add_borrower($db) {
		$this->db->insert('borrowers',$db);
	}

	function delete_borrower($id) {
		$this->db->where('id', $id)->delete('borrowers');
	}

	function get_some($id) {
		$query = $this->db->where('agentid', $id)->get('borrowers');

		if($query->num_rows() > 0 ) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function get_borrower($id) {
		$query = $this->db->where('id', $id)->limit(1)->get('borrowers');

		if($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function update_status($id, $data) {
		$this->db->where('id', $id)->update('borrowers',$data);
	}

	function get_able_borrowers() {
		$query = $this->db->where('status', 0)->get('borrowers');

		if($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function check_number($number) {
		$query = $this->db->where('contact', $number)->get('borrowers');

		if($query->num_rows() > 0 ) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function search_borrowers($search_by, $search_key) {
		$query = $this->db
			->where($search_by, $search_key)
			->get('borrowers');

		if($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}
		
}