<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Event
{

	private $fields;


	public function __construct()
	{
		$this->fields = new EER_Fields();

		$this->fields->add_field('title', 'string', true);
		$this->fields->add_field('sale_start', 'timestamp', false);
		$this->fields->add_field('sale_end', 'timestamp', false);
		$this->fields->add_field('event_settings', 'json', false);
	}


	/**
	 * Retrieve the array of plugin settings
	 *
	 * @return array
	 */
	public function eer_get_event_settings_fields()
	{
		$template_settings_tag = new EER_Template_Settings_Tag();

		$eer_event_settings = [
			/** General Settings */
			'event_general' => apply_filters('eer_event_settings_general', [
				'general_currency' => [
					'currency' => [
						'id' => 'currency',
						'name' => __('Currency', 'easy-event-registration'),
						'desc' => __('Choose your currency.', 'easy-event-registration'),
						'type' => 'select',
						'options' => EER()->currency->eer_get_currencies(),
						'chosen' => true,
						'std' => 'USD',
					],
					'currency_position' => [
						'id' => 'currency_position',
						'name' => __('Currency Position', 'easy-event-registration'),
						'desc' => __('Choose the location of the currency sign.', 'easy-event-registration'),
						'type' => 'select',
						'options' => [
							'before' => __('Before - $10', 'easy-event-registration'),
							'before_with_space' => __('Before with space - $ 10', 'easy-event-registration'),
							'after' => __('After - 10$', 'easy-event-registration'),
							'after_with_space' => __('After with space - 10 $', 'easy-event-registration'),
						],
						'std' => 'after_with_space',
					]
				],
				'general_sale_not_opened' => [
					'show_tickets' => [
						'id' => 'show_tickets',
						'name' => __('Show tickets', 'easy-event-registration'),
						'desc' => '',
						'type' => 'checkbox',
						'std' => true,
						'field_class' => 'eer-input'
					],
					'sale_not_opened' => [
						'id' => 'sale_not_opened',
						'name' => __('Sale Not Opened Yet', 'easy-event-registration'),
						'desc'      => __('Available template tags:', 'easy-school-registration'),
						'desc_tags' => $template_settings_tag->print_content(EER()->tags->get_tags('sale_not_opened')),
						'type' => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
				'general_sale_closed' => [
					'sale_closed' => [
						'id' => 'sale_closed',
						'name' => __('Sale Closed', 'easy-event-registration'),
						'type' => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
			]),
			'registration_form' => apply_filters('eer_event_settings_emails', [
				'rfmain' => [
					'partner_name_enabled' => [
						'id' => 'partner_name_enabled',
						'name' => __('Enable partner name', 'easy-event-registration'),
						'type' => 'checkbox',
					],
					'partner_name_required' => [
						'id' => 'partner_name_required',
						'name' => __('Partner name is required', 'easy-event-registration'),
						'type' => 'checkbox',
					],
					'phone_enabled' => [
						'id' => 'phone_enabled',
						'name' => __('Enable phone number', 'easy-event-registration'),
						'type' => 'checkbox',
					],
					'phone_required' => [
						'id' => 'phone_required',
						'name' => __('Phone number is required', 'easy-event-registration'),
						'type' => 'checkbox',
					],
					'country_enabled' => [
						'id' => 'country_enabled',
						'name' => __('Enable country selector', 'easy-event-registration'),
						'type' => 'checkbox',
					],
					'country_required' => [
						'id' => 'country_required',
						'name' => __('Country is required', 'easy-event-registration'),
						'type' => 'checkbox',
					],
				],
				'terms_conditions' => [
					'terms_conditions_enabled' => [
						'id' => 'terms_conditions_enabled',
						'name' => __('Enable terms & conditions confirmation', 'easy-event-registration'),
						'desc' => __('By enabling you will add terms & conditions confirmation checkbox to users form at registration page.', 'easy-event-registration'),
						'type' => 'checkbox',
					],
					'terms_conditions_required' => [
						'id' => 'terms_conditions_required',
						'name' => __('Require terms & conditions', 'easy-event-registration'),
						'desc' => __('By enabling you will set terms & conditions as required.', 'easy-event-registration'),
						'type' => 'checkbox',
					],
					'terms_conditions_text' => [
						'id' => 'terms_conditions_text',
						'name' => __('Confirmation text', 'easy-event-registration'),
						'type' => 'full_editor',
					],
				],
				'thank_you_text' => [
					'thank_you' => [
						'id' => 'thank_you',
						'name' => __('Thank you text', 'easy-event-registration'),
						'desc'      => __('Available template tags:', 'easy-school-registration'),
						'desc_tags' => $template_settings_tag->print_content(EER()->tags->get_tags('thank_you_page')),
						'type' => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
				'hosting' => [
					'hosting_enabled' => [
						'id' => 'hosting_enabled',
						'name' => __('Enable hosting confirmation', 'easy-event-registration'),
						'desc' => __('By enabling you will add hosting confirmation checkbox to users form at registration page.', 'easy-event-registration'),
						'type' => 'checkbox',
					],
					'hosting_text' => [
						'id' => 'hosting_text',
						'name' => __('Hosting text', 'easy-event-registration'),
						'type' => 'full_editor',
					],
					'offer_hosting_enabled' => [
						'id' => 'offer_hosting_enabled',
						'name' => __('Enable offer hosting confirmation', 'easy-event-registration'),
						'desc' => __('By enabling you will add offer hosting confirmation checkbox to users form at registration page.', 'easy-event-registration'),
						'type' => 'checkbox',
					],
					'offer_hosting_text' => [
						'id' => 'offer_hosting_text',
						'name' => __('Offer hosting text', 'easy-event-registration'),
						'type' => 'full_editor',
					],
				],
				'rf_tshirts' => [
					'tshirts_enabled' => [
						'id' => 'tshirts_enabled',
						'name' => __('Enable T-shirts selection', 'easy-event-registration'),
						'desc' => __('By enabling you will add T-shirts selection to users form at registration page.', 'easy-event-registration'),
						'type' => 'checkbox',
					],
					'tshirt_description' => [
						'id' => 'tshirt_description',
						'name' => __('Description', 'easy-event-registration'),
						'type' => 'full_editor',
						'field_class' => 'eer-input'
					],
					'tshirt_options' => [
						'id' => 'tshirt_options',
						'name' => __('T-shirt options', 'easy-event-registration'),
						'type' => 'add_list_tshirts',
						'singular' => __('T-shirt', 'easy-event-registration'),
					],
				],
				'food' => [
					'food_enabled' => [
						'id' => 'food_enabled',
						'name' => __('Enable food confirmation', 'easy-event-registration'),
						'desc' => __('By enabling you will add food confirmation checkbox to users form at registration page.', 'easy-event-registration'),
						'type' => 'checkbox',
					],
					'food_description' => [
						'id' => 'food_description',
						'name' => __('Description', 'easy-event-registration'),
						'type' => 'full_editor',
						'field_class' => 'eer-input'
					],
					'food_options' => [
						'id' => 'food_options',
						'name' => __('Food options', 'easy-event-registration'),
						'type' => 'add_list_food',
						'singular' => __('Food', 'easy-event-registration'),
					],
				],
			]),
			/** Emails Settings */
			'emails' => apply_filters('eer_event_settings_emails', [
				'emain' => [
					'from_name' => [
						'id' => 'from_name',
						'name' => __('From Name', 'easy-event-registration'),
						'desc' => __('The name emails are said to come from. This should probably be your site name.', 'easy-event-registration'),
						'type' => 'text',
						'std' => get_bloginfo('name'),
						'allow_blank' => false,
					],
					'from_email' => [
						'id' => 'from_email',
						'name' => __('From Email', 'easy-event-registration'),
						'desc' => __('Email to send emails from. This will act as the "from" and "reply-to" address.', 'easy-event-registration'),
						'type' => 'email',
						'std' => get_bloginfo('admin_email'),
						'allow_blank' => false,
					],
				],
				'order_email' => [
					'order_email_enabled' => [
						'id' => 'order_email_enabled',
						'name' => __('Enable emails', 'easy-event-registration'),
						'desc' => '',
						'type' => 'checkbox',
						'std' => true,
						'field_class' => 'eer-input'
					],
					'order_email_subject' => [
						'id' => 'order_email_subject',
						'name' => __('Email Subject', 'easy-event-registration'),
						'type' => 'text',
					],
					'order_email_body' => [
						'id' => 'order_email_body',
						'name' => __('Email Body', 'easy-event-registration'),
						'desc' => __('Available template tags:', 'easy-event-registration'),
						'desc_tags' => $template_settings_tag->print_content(EER()->tags->get_tags('order_email')),
						'type' => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
				'order_confirmation_email' => [
					'order_confirmation_email_enabled' => [
						'id' => 'order_confirmation_email_enabled',
						'name' => __('Enable emails', 'easy-event-registration'),
						'desc' => '',
						'type' => 'checkbox',
						'std' => true,
						'field_class' => 'eer-input'
					],
					'order_confirmation_email_subject' => [
						'id' => 'order_confirmation_email_subject',
						'name' => __('Email Subject', 'easy-event-registration'),
						'type' => 'text',
					],
					'order_confirmation_email_body' => [
						'id' => 'order_confirmation_email_body',
						'name' => __('Email Body', 'easy-event-registration'),
						'desc' => __('Available template tags:', 'easy-event-registration'),
						'desc_tags' => $template_settings_tag->print_content(EER()->tags->get_tags('order_confirmation_email')),
						'type' => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
				'payment_reminder_email' => [
					'payment_reminder_email_enabled' => [
						'id' => 'payment_reminder_email_enabled',
						'name' => __('Enable emails', 'easy-event-registration'),
						'desc' => '',
						'type' => 'checkbox',
						'std' => true,
						'field_class' => 'eer-input'
					],
					'payment_reminder_email_subject' => [
						'id' => 'payment_reminder_email_subject',
						'name' => __('Email Subject', 'easy-event-registration'),
						'type' => 'text',
					],
					'payment_reminder_email_body' => [
						'id' => 'payment_reminder_email_body',
						'name' => __('Email Body', 'easy-event-registration'),
						'desc' => __('Available template tags:', 'easy-event-registration'),
						'desc_tags' => $template_settings_tag->print_content(EER()->tags->get_tags('payment_reminder_email')),
						'type' => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
				'payment_confirmation_email' => [
					'payment_confirmation_email_enabled' => [
						'id' => 'payment_confirmation_email_enabled',
						'name' => __('Enable emails', 'easy-event-registration'),
						'desc' => '',
						'type' => 'checkbox',
						'std' => true,
						'field_class' => 'eer-input'
					],
					'payment_confirmation_send_tickets' => [
						'id' => 'payment_confirmation_send_tickets',
						'name' => __('Enable sending tickets with confirmation', 'easy-event-registration'),
						'desc' => '',
						'type' => 'checkbox',
						'std' => true,
						'field_class' => 'eer-input'
					],
					'payment_confirmation_email_subject' => [
						'id' => 'payment_confirmation_email_subject',
						'name' => __('Email Subject', 'easy-event-registration'),
						'type' => 'text',
					],
					'payment_confirmation_email_body' => [
						'id' => 'payment_confirmation_email_body',
						'name' => __('Email Body', 'easy-event-registration'),
						'desc' => __('Available template tags:', 'easy-event-registration'),
						'desc_tags' => $template_settings_tag->print_content(EER()->tags->get_tags('payment_confirmation_email')),
						'type' => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
				'tickets_email' => [
					'tickets_email_enabled' => [
						'id' => 'tickets_email_enabled',
						'name' => __('Enable emails', 'easy-event-registration'),
						'desc' => '',
						'type' => 'checkbox',
						'std' => true,
						'field_class' => 'eer-input'
					],
					'tickets_email_subject' => [
						'id' => 'tickets_email_subject',
						'name' => __('Email Subject', 'easy-event-registration'),
						'type' => 'text',
					],
					'tickets_email_body' => [
						'id' => 'tickets_email_body',
						'name' => __('Email Body', 'easy-event-registration'),
						'desc' => __('Available template tags:', 'easy-event-registration'),
						'desc_tags' => $template_settings_tag->print_content(EER()->tags->get_tags('email_tickets')),
						'type' => 'full_editor',
						'field_class' => 'eer-input'
					],
				],
			]),
		];

		return apply_filters('eer_registered_events_settings', $eer_event_settings);
	}


	public function eer_get_event_settings_fields_to_print($section_id, $sub_section_id)
	{
		$sections = $this->eer_get_event_settings_fields();

		if (isset($sections[$section_id][$sub_section_id])) {
			return $sections[$section_id][$sub_section_id];
		}

		return [];
	}


	public function eer_get_event_settings_tabs()
	{
		$tabs = [];
		$tabs['event_general'] = __('General', 'easy-event-registration');
		$tabs['registration_form'] = __('Registration form', 'easy-event-registration');
		$tabs['emails'] = __('Emails', 'easy-event-registration');

		return apply_filters('eer_event_settings_tabs', $tabs);
	}


	public function eer_get_event_settings_tab($tab)
	{
		$tabs = $this->eer_get_event_settings_tabs();

		return isset($tabs[$tab]) ? $tabs[$tab] : $tab;
	}


	public function eer_get_event_settings_tab_sections($tab = false)
	{

		$tabs = false;
		$sections = $this->eer_get_event_settings_sections();

		if ($tab && !empty($sections[$tab])) {
			$tabs = $sections[$tab];
		} else if ($tab) {
			$tabs = false;
		}

		return $tabs;
	}


	public function eer_get_event_settings_sections()
	{

		static $sections = false;

		if (false !== $sections) {
			return $sections;
		}

		$sections = [
			'event_general' => apply_filters('eer_event_settings_sections_general', [
				'general_currency' => __('Currency', 'easy-event-registration'),
				'general_sale_not_opened' => __('Sale not opened', 'easy-event-registration'),
				'general_sale_closed' => __('Sale closed', 'easy-event-registration'),
			]),
			'registration_form' => apply_filters('eer_event_settings_sections_registration_form', [
				'rfmain' => __('Main', 'easy-event-registration'),
				'thank_you_text' => __('Thank you text', 'easy-event-registration'),
				'terms_conditions' => __('Terms & Conditions', 'easy-event-registration'),
				'hosting' => __('Hosting', 'easy-event-registration'),
				'rf_tshirts' => __('T-shirts', 'easy-event-registration'),
				'food' => __('Food', 'easy-event-registration'),
			]),
			'emails' => apply_filters('eer_event_settings_sections_emails', [
				'emain' => __('General', 'easy-event-registration'),
				'order_email' => __('Registration Email', 'easy-event-registration'),
				'order_confirmation_email' => __('Confirmation Email', 'easy-event-registration'),
				'payment_reminder_email' => __('Payment Reminder Email', 'easy-event-registration'),
				'payment_confirmation_email' => __('Payment Confirmation Email', 'easy-event-registration'),
				'tickets_email' => __('Tickets Email', 'easy-event-registration'),
			]),
		];

		$sections = apply_filters('eer_event_settings_sections', $sections);

		return $sections;
	}


	/**
	 * Loads all events
	 * @return array|null|object
	 */
	public function load_events()
	{
		global $wpdb;
		$return = [];

		$events = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eer_events ORDER BY id DESC", OBJECT_K);

		foreach ($events as $id => $event) {
			$settings = $event->event_settings;
			unset($event->event_settings);
			$event = (object)array_merge((array)$event, (array)json_decode($settings, true));
			$return[$event->id] = $event;
		}

		return $return;
	}


	/**
	 * Loads all events
	 * @return array|null|object
	 */
	public function load_events_without_data()
	{
		global $wpdb;
		return $wpdb->get_results("SELECT * FROM {$wpdb->prefix}eer_events ORDER BY id DESC", OBJECT_K);
	}


	public function get_event_data($event_id)
	{
		global $wpdb;
		$event = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}eer_events WHERE id = %d", [$event_id]), OBJECT);
		$settings = "";
		if ($event) {
			$settings = $event->event_settings;
			unset($event->event_settings);
		}
		return (object)array_merge((array)$event, (array)json_decode($settings, true));
	}


	public function get_event_setting($event_id, $setting_key, $default = NULL)
	{
		$event_data = $this->get_event_data($event_id);
		return isset($event_data->$setting_key) ? $event_data->$setting_key : $default;
	}


	public function get_event_title($event_id)
	{
		return $this->get_event_data($event_id)->title;
	}


	/**
	 * @return object
	 */
	public function get_fields()
	{
		return $this->fields->get_fields();
	}

	public function is_event_sale_active($event_id)
	{
		$data = $this->get_event_data($event_id);
		$current_time = current_time('Y-m-d H:i:s');

		return !$data->is_passed && ($data->sale_start <= $current_time) && ($data->sale_end >= $current_time);
	}


	/**
	 * @param int $event_id
	 *
	 * @return bool
	 */
	public function is_event_sale_closed($event_id)
	{
		$data = $this->get_event_data($event_id);
		$current_time = current_time('Y-m-d H:i:s');

		return $data->is_passed || ($data->sale_end < $current_time);
	}


	/**
	 * @param int $event_id
	 *
	 * @return bool
	 */
	public function is_event_sale_not_opened_yet($event_id)
	{
		$data = $this->get_event_data($event_id);
		$current_time = current_time('Y-m-d H:i:s');

		return !$data->is_passed && ($data->sale_start > $current_time);
	}

	public function eer_get_event_option($event_data, $key = '', $default = false)
	{
		$value = !empty($event_data->$key) ? $event_data->$key : $default;

		return apply_filters('eer_get_event_option_' . $key, $value, $key, $default);
	}
}