<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Tags
{

	private $tags;

	public function __construct()
	{
		$this->tags = $this->set_tags();
	}


	public function get_tags($category = NULL)
	{
		if (($category != NULL) && (isset($this->tags[$category]))) {
			return $this->tags[$category];
		} else {
			return $category;
		}
	}

	private function set_tags()
	{
		return [
			'email_tickets' => [
				'event_title' => [
					'id' => 'title',
					'tag' => 'event_title',
					'description' => __('Event title.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_string',
					'parameter' => 'event_title'
				],
			],
			'thank_you_page' => [
				'registered_exists' => [
					'id' => 'registered_exists',
					'tag' => 'registered_exists',
					'type' => 'double',
					'description' => __('List of registered tickets.', 'easy-event-registration'),
				],
				'list_registered' => [
					'id' => 'list_registered',
					'tag' => 'list_registered',
					'description' => __('List of registered tickets.', 'easy-event-registration'),
				],
				'full_exists' => [
					'id' => 'full_exists',
					'tag' => 'full_exists',
					'type' => 'double',
					'description' => __('List of full tickets.', 'easy-event-registration'),
				],
				'list_full' => [
					'id' => 'list_full',
					'tag' => 'list_full',
					'description' => __('List of full tickets.', 'easy-event-registration'),
				],
			],
			'order_email' => [
				'event_title' => [
					'id' => 'title',
					'tag' => 'event_title',
					'description' => __('Event title.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_string',
					'parameter' => 'event_title'
				],
				'registered_exists' => [
					'id' => 'registered_exists',
					'tag' => 'registered_exists',
					'type' => 'double',
					'description' => __('List of registered tickets.', 'easy-event-registration'),
				],
				'list_registered' => [
					'id' => 'list_registered',
					'tag' => 'list_registered',
					'description' => __('List of registered tickets.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_registration_ticket_list',
					'parameter' => 'list_registered'
				],
				'name' => [
					'id' => 'name',
					'tag' => 'name',
					'description' => __('Name of user from registration.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_order_info',
					'parameter' => 'order_info'
				],
				'surname' => [
					'id' => 'surname',
					'tag' => 'surname',
					'description' => __('Surname of user from registration.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_order_info',
					'parameter' => 'order_info'
				],
				'email' => [
					'id' => 'email',
					'tag' => 'email',
					'description' => __('Email of user from registration.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_order_info',
					'parameter' => 'order_info'
				],
				'phone' => [
					'id' => 'phone',
					'tag' => 'phone',
					'description' => __('Phone of user from registration.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_order_info',
					'parameter' => 'order_info'
				],
				'country' => [
					'id' => 'country',
					'tag' => 'country',
					'description' => __('Country of user from registration.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_order_info',
					'parameter' => 'order_info'
				],
				'note' => [
					'id' => 'note',
					'tag' => 'note',
					'description' => __('Note from registration.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_order_info',
					'parameter' => 'order_info'
				],
				'hosting_option' => [
					'id' => 'hosting',
					'tag' => 'hosting_option',
					'description' => __('Hosting option.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_string',
					'parameter' => 'hosting_option'
				],
				'tshirt_option' => [
					'id' => 'tshirt',
					'tag' => 'tshirt_option',
					'description' => __('T-shirt option.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_string',
					'parameter' => 'tshirt_option'
				],
				'food_option' => [
					'id' => 'food',
					'tag' => 'food_option',
					'description' => __('Food option.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_string',
					'parameter' => 'food_option'
				],
			],
			'order_confirmation_email' => [
				'event_title' => [
					'id' => 'title',
					'tag' => 'event_title',
					'description' => __('Event title.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_string',
					'parameter' => 'event_title'
				],
				'ticket_title' => [
					'id' => 'title',
					'tag' => 'ticket_title',
					'description' => __('Ticket title.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_string',
					'parameter' => 'ticket_title'
				],
			],
			'payment_reminder_email' => [
				'event_title' => [
					'id' => 'title',
					'tag' => 'event_title',
					'description' => __('Event title.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_string',
					'parameter' => 'event_title'
				],
				'tickets_list' => [
					'id' => 'tickets_list',
					'tag' => 'tickets_list',
					'description' => __('List of tickets the student is confirmed to.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_tickets_list',
					'parameter' => 'tickets_list'
				],
				'total_price' => [
					'id' => 'total_price',
					'tag' => 'total_price',
					'description' => __('Total price of all tickets.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_price',
					'parameter' => 'to_pay'
				],
				'order_code' => [
					'id' => 'order_code',
					'tag' => 'order_code',
					'description' => __('Order code to identify which order is user paying.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_string',
					'parameter' => 'order_code'
				],
			],
			'payment_confirmation_email' => [
				'event_title' => [
					'id' => 'title',
					'tag' => 'event_title',
					'description' => __('Event title.', 'easy-event-registration'),
					'function' => 'eer_tag_replace_string',
					'parameter' => 'event_title'
				],
			],
		];
	}

}