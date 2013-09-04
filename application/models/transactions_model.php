<?php


class Transactions_model extends CI_Model {

	function add_transaction($db) {
		$this->db->insert('transactions', $db);
	}
}