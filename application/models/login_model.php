<?php
class Login_model extends CI_Model {

	function check_login($username, $password) {
		$query = $this->db
			->where('username', $username)
			->where('password', sha1($password))
			->limit(1)
			->get('users');

		if($query->num_rows() > 0) {
			return $query->row();
		}
		else {
			return FALSE;
		}
	}
}