<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_User_Registration_Email {

	private $worker_email;

	public function __construct() {
		$this->worker_email = new EER_Worker_Email();
	}


	public function send_email($login, $email, $password) {

		return false;
	}

}
