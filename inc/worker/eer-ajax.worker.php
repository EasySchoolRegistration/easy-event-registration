<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Worker_Ajax {

	public function remove_order($order_id) {
		if ($order_id) {
			global $wpdb;

			$sold_tickets = EER()->sold_ticket->eer_get_sold_tickets_by_order($order_id);

			$wpdb->delete($wpdb->prefix . 'eer_events_payments', ['order_id' => $order_id]);
			$wpdb->update($wpdb->prefix . 'eer_sold_tickets', ['status' => EER_Enum_Sold_Ticket_Status::DELETED], ['order_id' => $order_id]);
			$wpdb->update($wpdb->prefix . 'eer_events_orders', ['status' => EER_Enum_Order_Status::DELETED], ['id' => $order_id]);

			foreach ($sold_tickets AS $key => $sold_ticket) {
				$update  = [];
				$summary = EER()->ticket_summary->eer_get_ticket_summary($sold_ticket->ticket_id, $sold_ticket->level_id);
				if ($sold_ticket->status == EER_Enum_Sold_Ticket_Status::CONFIRMED) {
					if (EER()->ticket->eer_is_solo($sold_ticket->ticket_id)) {
						$update['registered_tickets'] = $summary->registered_tickets - 1;
					} else if (EER()->dancing_as->eer_is_follower($sold_ticket->dancing_as)) {

						$update['registered_followers'] = $summary->registered_followers - 1;
					} else if (EER()->dancing_as->eer_is_leader($sold_ticket->dancing_as)) {
						$update['registered_leaders'] = $summary->registered_leaders - 1;
					}
				} else if ($sold_ticket->status == EER_Enum_Sold_Ticket_Status::WAITING) {
					if (EER()->dancing_as->eer_is_follower($sold_ticket->dancing_as)) {
						$update['waiting_followers'] = $summary->waiting_followers - 1;
					} else if (EER()->dancing_as->eer_is_leader($sold_ticket->dancing_as)) {
						$update['waiting_leaders'] = $summary->waiting_leaders - 1;
					}
				}

				$wpdb->update($wpdb->prefix . 'eer_ticket_summary', $update, [
					'ticket_id' => $sold_ticket->ticket_id,
					'level_id'  => $sold_ticket->level_id,
				]);
			}

			return true;
		}

		return false;
	}


	public function remove_sold_ticket($sold_ticket_id) {
		if ($sold_ticket_id) {
			global $wpdb;
			$worker_payment = new EER_Worker_Payment();

			$sold_ticket_data = EER()->sold_ticket->eer_get_sold_tickets_data($sold_ticket_id);

			$wpdb->update($wpdb->prefix . 'eer_sold_tickets', ['status' => EER_Enum_Sold_Ticket_Status::DELETED], ['id' => $sold_ticket_id]);
			$worker_payment->eer_update_user_payment($sold_ticket_data->order_id);

			if (!EER()->sold_ticket->eer_are_all_ordered_tickets_deleted($sold_ticket_data->order_id)) {
				$wpdb->update($wpdb->prefix . 'eer_events_orders', ['status' => EER_Enum_Order_Status::DELETED], ['id' => $sold_ticket_data->order_id]);
			}

			$update  = [];
			$summary = EER()->ticket_summary->eer_get_ticket_summary($sold_ticket_data->ticket_id, $sold_ticket_data->level_id);
			if ($sold_ticket_data->status == EER_Enum_Sold_Ticket_Status::CONFIRMED) {
				if (EER()->ticket->eer_is_solo($sold_ticket_data->ticket_id)) {
					$update['registered_tickets'] = $summary->registered_tickets - 1;
				} else if (EER()->dancing_as->eer_is_follower($sold_ticket_data->dancing_as)) {
					$update['registered_followers'] = $summary->registered_followers - 1;
				} else if (EER()->dancing_as->eer_is_leader($sold_ticket_data->dancing_as)) {
					$update['registered_leaders'] = $summary->registered_leaders - 1;
				}
			} else if ($sold_ticket_data->status == EER_Enum_Sold_Ticket_Status::WAITING) {
				if (EER()->dancing_as->eer_is_follower($sold_ticket_data->dancing_as)) {
					$update['waiting_followers'] = $summary->waiting_followers - 1;
				} else if (EER()->dancing_as->eer_is_leader($sold_ticket_data->dancing_as)) {
					$update['waiting_leaders'] = $summary->waiting_leaders - 1;
				}
			}

			$wpdb->update($wpdb->prefix . 'eer_ticket_summary', $update, [
				'ticket_id' => $sold_ticket_data->ticket_id,
				'level_id'  => $sold_ticket_data->level_id,
			]);

			return true;
		}

		return false;
	}


	public function remove_sold_ticket_forever_callback($sold_ticket) {
		global $wpdb;

		if ($sold_ticket) {
			$wpdb->delete($wpdb->prefix . 'eer_sold_tickets', [
				'id' => $sold_ticket->id,
				'status' => EER_Enum_Sold_Ticket_Status::DELETED,
			]);

			if (EER()->sold_ticket->eer_count_sold_tickets($sold_ticket->order_id) == 0) {
				$wpdb->delete($wpdb->prefix . 'eer_events_orders', [
					'id' => $sold_ticket->order_id,
				]);
			}

			return 1;
		}
		return -1;
	}


	public function confirm_sold_ticket($sold_ticket_id) {
		if ($sold_ticket_id) {
			global $wpdb;
			$worker_payment = new EER_Worker_Payment();

			$sold_ticket_data = EER()->sold_ticket->eer_get_sold_tickets_data($sold_ticket_id);

			$wpdb->update($wpdb->prefix . 'eer_sold_tickets', [
				'status' => EER_Enum_Sold_Ticket_Status::CONFIRMED
			], ['id' => $sold_ticket_id]);

			$worker_payment->eer_update_user_payment($sold_ticket_data->order_id);

			$update  = [];
			$summary = EER()->ticket_summary->eer_get_ticket_summary($sold_ticket_data->ticket_id, $sold_ticket_data->level_id);
			$is_solo = EER()->ticket->eer_is_solo($sold_ticket_data->ticket_id);
			if ($sold_ticket_data->status == EER_Enum_Sold_Ticket_Status::DELETED) {
				if ($is_solo) {
					$update['registered_tickets'] = $summary->registered_tickets + 1;
				} else if (EER()->dancing_as->eer_is_follower($sold_ticket_data->dancing_as)) {
					$update['registered_followers'] = $summary->registered_followers + 1;
				} else if (EER()->dancing_as->eer_is_leader($sold_ticket_data->dancing_as)) {
					$update['registered_leaders'] = $summary->registered_leaders + 1;
				}
			} else if ($sold_ticket_data->status == EER_Enum_Sold_Ticket_Status::WAITING) {
				if ($is_solo) {
					$update['registered_tickets'] = $summary->registered_tickets + 1;
					$update['waiting_tickets'] = $summary->waiting_tickets - 1;
				} else if (EER()->dancing_as->eer_is_follower($sold_ticket_data->dancing_as)) {
					$update['registered_followers'] = $summary->registered_followers + 1;
					$update['waiting_followers'] = $summary->waiting_followers - 1;
				} else if (EER()->dancing_as->eer_is_leader($sold_ticket_data->dancing_as)) {
					$update['registered_leaders'] = $summary->registered_leaders + 1;
					$update['waiting_leaders'] = $summary->waiting_leaders - 1;
				}
			}

			$wpdb->update($wpdb->prefix . 'eer_ticket_summary', $update, [
				'ticket_id' => $sold_ticket_data->ticket_id,
				'level_id'  => $sold_ticket_data->level_id,
			]);


			$order = EER()->order->eer_get_order($sold_ticket_data->order_id);

			EER()->email->eer_send_ticket_confirmation_email($order->event_id, $sold_ticket_data->id);

			return 1;
		}

		return -1;
	}


	public function save_payment($data) {
		if (isset($data['payment_type']) && isset($data['order_id'])) {
			global $wpdb;
			$price = null;

			if (($data['payment_type'] === 'paid') && isset($data['payment'])) {
				$price = (int) $data['payment'];
			}

			if (($price !== null) || in_array($data['payment_type'], ['not_paying', 'voucher'])) {
				$wpdb->update($wpdb->prefix . 'eer_events_payments', [
					'payment'    => $price,
					'is_paying'  => ($data['payment_type'] !== 'not_paying'),
					'is_voucher' => ($data['payment_type'] === 'voucher'),
					'note'       => isset($data['note']) ? $data['note'] : null,
					'status'     => EER_Enum_Payment::PAID
				], [
					'order_id' => (int) $data['order_id'],
				]);

				if (isset($data['eer_payment_email_confirmation']) && filter_var($data['eer_payment_email_confirmation'], FILTER_VALIDATE_BOOLEAN)) {
					EER()->email->eer_send_payment_confirmation_email((int) $data['order_id']);
				}
			}

			return is_int($data['payment']) ? $price : $data['payment'];
		}

		return false;
	}

}