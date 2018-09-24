<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Role
{

	/**
	 * @codeCoverageIgnore
	 */
	public static function init()
	{
		//add capabilities for admin
		$admin = get_role('administrator');
		$capabilities = [
			'eer_add_over_limit_edit' => true,
			'eer_add_over_limit_view' => true,
			'eer_event_edit' => true,
			'eer_event_view' => true,
			'eer_ticket_edit' => true,
			'eer_ticket_view' => true,
			'eer_order_edit' => true,
			'eer_order_view' => true,
			'eer_payment_edit' => true,
			'eer_payment_view' => true,
			'eer_payment_emails_view' => true,
			'eer_sold_ticket_edit' => true,
			'eer_sold_ticket_view' => true,
			'eer_tickets_in_numbers_view' => true,
			'eer_school' => true,
			'eer_settings' => true,
		];

		foreach ($capabilities as $key => $cap) {
			$admin->add_cap($key, $cap);
		}
	}
}

add_action('init', ['EER_Role', 'init']);