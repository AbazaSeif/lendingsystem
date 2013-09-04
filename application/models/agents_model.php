<?php
class Agents_model extends CI_Model {

	function get_agents() {
		$query = $this->db->get('agents');

		if($query->num_rows() > 0 ) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function delete_agent($id) {
		$this->db->where('id', $id)->delete('agents');
		$this->db->where('agentid', $id)->delete('borrowers');
	}

	function add_agent($db) {
		$query = $this->db->insert('agents', $db);
	}

	function get_agent($id) {
		$query = $this->db
		->where('id', $id)
		->limit(1)
		->get('agents');

		if($query->num_rows() > 0 ) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function search_agents($search_by, $search_key) {
		$query = $this->db
			->where($search_by, $search_key)
			->get('agents');

		if($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function check_number($number) {
		$query = $this->db->where('contact', $number)->get('agents');

		if($query->num_rows() > 0 ) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

}