<?php

class Settings_model extends CI_Model {

	function get_all() {
		$query = $this->db
			->where('id', 1)
			->limit(1)
			->get('settings');

		return $query->row();
	}

	function update($db) {
		$this->db->where('id', 1)->update('settings', $db);
	}

	function change_pass($id, $newpassword) {
		$data = array('password' => sha1($newpassword));
		$query = $this->db->where('id', $id)->update('users', $data);
	}
}