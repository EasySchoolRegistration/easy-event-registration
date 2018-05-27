<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Email {

	private $template_order_email;

	private $template_order_confirmation_email;

	private $template_payment_email;

	private $template_payment_confirmation_email;

	private $template_ticket_email;

	private $template_user_registration_email;


	public function __construct() {
		$this->template_order_confirmation_email   = new EER_Template_Order_Confirmation_Email();
		$this->template_order_email                = new EER_Template_Order_Email();
		$this->template_payment_confirmation_email = new EER_Template_Payment_Confirmation_Email();
		$this->template_payment_email              = new EER_Template_Payment_Email();
		$this->template_ticket_email               = new EER_Template_Ticket_Email();
		$this->template_user_registration_email    = new EER_Template_User_Registration_Email();
	}


	/**
	 * @param int $order_id
	 * @param array $registration_data
	 */
	public function eer_send_order_email($order_id, $registration_data) {
		if ($order_id) {
			$this->template_order_email->send_email($order_id, $registration_data);
		}
	}


	/**
	 * @param array $tickets
	 */
	public function eer_send_order_confirmation_email($event_id, $tickets) {
		if ($tickets) {
			$this->template_order_confirmation_email->send_email($event_id, $tickets);
		}
	}


	/**
	 * @param array $tickets
	 */
	public function eer_send_ticket_confirmation_email($event_id, $sold_ticket) {
		$this->template_order_confirmation_email->send_confirmation_email($event_id, $sold_ticket);
	}


	public function eer_send_payment_email($payment_id, $event_data) {
		return $this->template_payment_email->send_email($payment_id, $event_data);
	}


	public function eer_send_payment_confirmation_email($order_id) {
		return $this->template_payment_confirmation_email->send_email($order_id);
	}


	public function eer_send_user_registration_email($login, $email, $password) {
		return $this->template_user_registration_email->send_email($login, $email, $password);
	}


	public function eer_send_tickets_email($order_id) {
		if ($order_id) {
			return $this->template_ticket_email->send_email($order_id);
		}

		return -1;
	}


}
