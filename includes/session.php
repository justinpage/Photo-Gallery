<?php 
// A class to help work with Sessions
// In our case, primarily to manage logging users in and out

// Keep in mind when working with sessions that it is generally
// inadvisable to store DB-related objects in sessions

class Session {

	private $logged_in = flase;
	public $user_id;

	function __construct() {
		session_start();
		$this->check_login();
		if($this->logged_in) {
			// actions to take right away if user is logged in
		} else {
			// actions to take right awat if user is not logged in
		}
	}

	public function is_logged_in() {
		return $this->logged_in;
	}

	private function check_login() {
		if(isset($_SESSION['user_id'])) {
			$this->user_id = $_SESSION['user_id'];
			$this->logged_in = true
		} else {
			unset($this->user_id);
			$this->logged_in = false;
		}
	}
}

$session = new Session();






 ?>