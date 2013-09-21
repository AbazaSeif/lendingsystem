<?php
class Borrowers_model extends CI_Model {

	function get_borrowers() {
		$select = array(
			'borrowers.*',
			'agents.lastname AS alastname',
			'agents.firstname AS afirstname',
			'agents.middlename AS amiddlename'
			);
		$query = $this->db->select($select)->join('agents', 'borrowers.agentid = agents.id','left')->get('borrowers');

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
		$select = array(
			'borrowers.*',
			'agents.lastname AS alastname',
			'agents.firstname AS afirstname',
			'agents.middlename AS amiddlename'
			);
		$query = $this->db->select($select)->where('borrowers.id', $id)->limit(1)->join('agents', 'borrowers.agentid = agents.id','left')->get('borrowers');

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
		$select = array(
			'borrowers.*',
			'agents.lastname AS alastname',
			'agents.firstname AS afirstname',
			'agents.middlename AS amiddlename'
			);
		$query = $this->db->select($select)->where('borrowers.status', 0)->join('agents', 'borrowers.agentid = agents.id','left')->get('borrowers');

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
		$select = array(
			'borrowers.*',
			'agents.lastname AS alastname',
			'agents.firstname AS afirstname',
			'agents.middlename AS amiddlename'
			);
		$query = $this->db
			->select($select)
			->where('borrowers.'.$search_by, $search_key)
			->join('agents', 'borrowers.agentid = agents.id','left')
			->get('borrowers');

		if($query->num_rows() > 0) {
			return $query->result();
		}
		else {
			return FALSE;
		}
	}

	function update_payday($id, $value) {
		$data = array(
			'nopay' => $value
			);
		$this->db->where('id', $id)->update('borrowers', $data);
	}
		
}