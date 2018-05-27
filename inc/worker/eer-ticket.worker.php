<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Worker_Ticket {

	public function __construct() {
		add_action('eer_ticket_add', [get_called_class(), 'add_ticket_action']);
		add_action('eer_ticket_update', [get_called_class(), 'update_ticket_action'], 10, 2);
	}


	public function process_ticket($data) {
		if (isset($data['ticket_id']) && !empty($data['ticket_id'])) {
			do_action('eer_ticket_update', $data['ticket_id'], $this->prepare_data($data, isset($data['ticket_id'])));
		} else {
			do_action('eer_ticket_add', $this->prepare_data($data));
		}
	}


	public static function add_ticket_action($insert_data) {
		global $wpdb;
		$result = $wpdb->insert($wpdb->prefix . 'eer_tickets', $insert_data);

		if ($result !== false) {
			$ticket_id = $wpdb->insert_id;

			$ticket_data = EER()->ticket->get_ticket_data($ticket_id);

			if ($ticket_data->levels_enabled < 1) {
				$wpdb->insert($wpdb->prefix . 'eer_ticket_summary', [
					'ticket_id'     => $ticket_id,
					'max_leaders'   => $ticket_data->max_leaders,
					'max_followers' => $ticket_data->max_followers,
					'max_tickets'   => $ticket_data->max_tickets,
					'level_id' => NULL
				]);
			} else {
				foreach ($ticket_data->levels as $key => $level) {
					$wpdb->insert($wpdb->prefix . 'eer_ticket_summary', [
						'ticket_id'     => $ticket_id,
						'level_id'      => (int) $level['key'],
						'max_leaders'   => $level['leaders'] !== '' ? (int) $level['leaders'] : 0,
						'max_followers' => $level['followers'] !== '' ? (int) $level['followers'] : 0,
						'max_tickets'   => $level['tickets'] !== '' ? (int) $level['tickets'] : 0,
					]);
				}
			}

			do_action('eer_module_ticket_add', $ticket_id, $insert_data);
		}
	}


	public static function update_ticket_action($ticket_id, $update_data) {
		global $wpdb;
		$ticket_settings = json_decode($update_data['ticket_settings']);

		$wpdb->update($wpdb->prefix . 'eer_tickets', $update_data, [
			'id' => $ticket_id,
		]);



		if (intval($ticket_settings->levels_enabled) < 1) {
			$wpdb->update($wpdb->prefix . 'eer_ticket_summary', [
				'max_leaders'   => $update_data['max_leaders'],
				'max_followers' => $update_data['max_followers'],
				'max_tickets'   => $update_data['max_tickets'],
				'level_id' => NULL
			], ['ticket_id' => $ticket_id]);
		} else {
			$old_summery = EER()->ticket_summary->eer_get_ticket_summaries($ticket_id);
			foreach ($old_summery as $summary_key => $summary) {
				if (isset($ticket_settings->levels->{$summary->level_id})) {
					$level = $ticket_settings->levels->{$summary->level_id};
					$wpdb->update($wpdb->prefix . 'eer_ticket_summary', [
						'max_leaders'   => (int) $level->leaders,
						'max_followers' => (int) $level->followers,
						'max_tickets'   => (int) $level->tickets,
						'level_id'      => (int) $level->key
					], ['id' => $summary->id]);
					unset($ticket_settings->levels->{$summary->level_id});
				} else {
					$wpdb->delete($wpdb->prefix . 'eer_ticket_summary', [
						'id' => $summary->id,
					]);
				}
			}

			foreach ($ticket_settings->levels as $level_key => $level) {
				$wpdb->insert($wpdb->prefix . 'eer_ticket_summary', [
					'max_leaders'   => (int) $level->leaders,
					'max_followers' => (int) $level->followers,
					'max_tickets'   => (int) $level->tickets,
					'level_id'      => (int) $level->key,
					'ticket_id'     => $ticket_id
				]);
			}
		}

		do_action('eer_module_ticket_update', $ticket_id, $update_data);
	}


	private function prepare_data($data, $is_update = false) {
		$return_data = [];
		if (!isset($data['is_solo'])) {
			$data['max_per_order'] = 1;
		}

		foreach (EER()->ticket->get_fields() as $key => $field) {
			if ($field['type'] === 'json') {
				if (isset($data[$key]['levels'])) {
					$levels = [];
					foreach ($data[$key]['levels'] as $level_key => $level) {
						$level['key']       = $level_key;
						$levels[$level_key] = (object) $level;
					}
					$data[$key]['levels'] = (object) $levels;
				}
				if (isset($data[$key]['levels_enabled']) && (intval($data[$key]['levels_enabled']) === 1)) {
					$return_data['has_levels'] = true;
				}

				$return_data[$key] = json_encode(EER()->fields->eer_sanitize_ticket_settings($data[$key]), JSON_FORCE_OBJECT);
			} else if (($field['type'] === 'boolean') && (!isset($data[$key]) || (isset($data[$key]) && !is_bool($data[$key])))) {
				$return_data[$key] = isset($data[$key]) && ($data[$key] === '1');
			} else if (($field['required'] && !$is_update) || isset($data[$key])) {
				$return_data[$key] = EER()->fields->sanitize($field['type'], $data[$key]);
			}
		}
		return $return_data;
	}
}