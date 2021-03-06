<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Event_Sale_Solo_Worker
{

	public function process_registration($order_id, $ticket_id, $ticket_data, $number_of_registrations, $order_data, $event_id)
	{
		global $wpdb;
		$return_tickets = [];

		$level_id = (isset($order_data->level_id) && ($order_data->level_id !== '')) ? intval($order_data->level_id) : null;
		$event_data = EER()->event->get_event_data($event_id);

		if (intval(EER()->event->eer_get_event_option($event_data, 'floating_price_enabled', -1)) !== -1) {
			$payment = apply_filters('eer_get_order_payment', $order_id)->to_pay;

			if ($payment) {
				$return_tickets['paired'][$ticket_id]['user']['previous_price'] = $payment;
			}
		}

		for ($i = 0; $i < $number_of_registrations; $i++) {
			$status = $wpdb->query($wpdb->prepare("INSERT INTO {$wpdb->prefix}eer_sold_tickets(order_id, ticket_id, unique_key, dancing_as, dancing_with, status, position)
							SELECT %d, %d, %d, %d, %s, %d, COALESCE(COUNT(event_id) + 1, 0)  FROM  {$wpdb->prefix}eer_sold_tickets AS st JOIN {$wpdb->prefix}eer_events_orders AS eo ON eo.id = st.order_id WHERE eo.event_id = %d AND st.ticket_id = %d", [
				$order_id, $ticket_id, EER()->sold_ticket->generate_unique_key($event_id), EER_Enum_Dancing_As::SOLO, null, EER()->pairing_mode->get_solo_ticket_default_status($ticket_data->pairing_mode), $event_id, $ticket_id]));

			if ($status !== false) {
				$summary = EER()->ticket_summary->eer_get_ticket_summary($ticket_id, $level_id);
				if (EER()->pairing_mode->get_solo_ticket_default_status($ticket_data->pairing_mode) === EER_Enum_Sold_Ticket_Status::CONFIRMED) {
					$registration_id = $wpdb->insert_id;

					EER()->ticket_summary->eer_update_ticket_summary($ticket_id, $level_id, [
						'registered_tickets' => intval($summary->registered_tickets) + 1,
					]);

					$worker_payment = new EER_Worker_Payment();
					$worker_payment->eer_update_user_payment($order_id);

					$return_tickets['paired'][$ticket_id]['user']['sold_ticket_id'] = $registration_id;
				} else {
					EER()->ticket_summary->eer_update_ticket_summary($ticket_id, $level_id, [
						'waiting_tickets' => intval($summary->waiting_tickets) + 1,
					]);
				}
			}
		}

		if (intval(EER()->event->eer_get_event_option($event_data, 'floating_price_enabled', -1)) !== -1) {
			$payment = apply_filters('eer_get_order_payment', $order_id)->to_pay;

			if ($payment) {
				$return_tickets['paired'][$ticket_id]['user']['actual_price'] = $payment;
			}
		}

		return $return_tickets;
	}
}