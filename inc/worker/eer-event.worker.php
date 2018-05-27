<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Worker_Event {

	public function __construct() {
		add_action('eer_event_add', [get_called_class(), 'add_event_action']);
		add_action('eer_event_update', [get_called_class(), 'update_event_action'], 10, 2);
	}


	public function process_event($data) {
		if (isset($data['event_id']) && !empty($data['event_id'])) {
			do_action('eer_event_update', $data['event_id'], $this->prepare_data($data, isset($data['event_id'])));
		} else {
			do_action('eer_event_add', $this->prepare_data($data));
		}
	}


	public static function add_event_action($data) {
		global $wpdb;
		$result = $wpdb->insert($wpdb->prefix . 'eer_events', $data);

		if ($result !== false) {
			$data['event_id'] = $wpdb->insert_id;

			do_action('eer_module_event_add', $data);
		}
	}


	public static function update_event_action($event_id, $data) {
		global $wpdb;

		$wpdb->update($wpdb->prefix . 'eer_events', $data, [
			'id' => $event_id,
		]);

		do_action('eer_module_event_update', $event_id, $data);
	}


	private function prepare_data($data, $is_update = false) {
		$return_data = [];

		foreach (EER()->event->get_fields() as $key => $field) {
			if ($field['type'] === 'json') {
				if (isset($data[$key]['tshirt_options'])) {
					$data[$key]['tshirt_options'] = $this->array_to_objects($data[$key]['tshirt_options']);
				}
				if (isset($data[$key]['food_options'])) {
					$data[$key]['food_options'] = $this->array_to_objects($data[$key]['food_options']);
				}

				$return_data[$key] = json_encode(EER()->fields->eer_sanitize_event_settings($data[$key]));
			} else if (($field['required'] && !$is_update) || isset($data[$key])) {
				$return_data[$key] = EER()->fields->sanitize($field['type'], $data[$key]);
			}
		}

		return $return_data;
	}


	private function array_to_objects($data) {
		$options = [];
		foreach ($data as $option_key => $option) {
			$option['key']        = $option_key;
			$options[$option_key] = (object) $option;
		}

		return (object) $options;
	}
}