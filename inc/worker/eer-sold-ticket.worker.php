<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Worker_Sold_Ticket {

	public function update_sold_ticket($sold_ticket_data) {
		if ($sold_ticket_data && isset($sold_ticket_data['sold_ticket_id'])) {
			$sold_ticket = EER()->sold_ticket->eer_get_sold_tickets_data($sold_ticket_data['sold_ticket_id']);
			if ($sold_ticket) {
				global $wpdb;

				$new_dancing_as = intval($sold_ticket_data['dancing_as']);
				$wpdb->update($wpdb->prefix . 'eer_sold_tickets', [
					'dancing_as' => intval($sold_ticket_data['dancing_as']),
					'dancing_with' => $sold_ticket_data['dancing_with'],
					'dancing_with_name' => $sold_ticket_data['dancing_with_name'],
				], [
					'id' => $sold_ticket_data['sold_ticket_id']
				]);

				apply_filters('eer_update_user_payment', $sold_ticket->order_id);

				if ($sold_ticket->dancing_as != $new_dancing_as) {
					$summary = EER()->ticket_summary->eer_get_ticket_summary($sold_ticket->ticket_id, $sold_ticket->level_id);
					$update  = [];
					if ($sold_ticket->status == EER_Enum_Sold_Ticket_Status::CONFIRMED) {
						if (EER()->dancing_as->eer_is_follower($sold_ticket->dancing_as)) {
							$update['registered_followers'] = $summary->registered_followers - 1;
							$update['registered_leaders']   = $summary->registered_leaders + 1;
						} else if (EER()->dancing_as->eer_is_leader($sold_ticket->dancing_as)) {
							$update['registered_leaders']   = $summary->registered_leaders - 1;
							$update['registered_followers'] = $summary->registered_followers + 1;
						}
					} else if ($sold_ticket->status == EER_Enum_Sold_Ticket_Status::WAITING) {
						if (EER()->dancing_as->eer_is_follower($sold_ticket->dancing_as)) {
							$update['registered_followers'] = $summary->registered_followers - 1;
							$update['registered_leaders']   = $summary->registered_leaders + 1;
						} else if (EER()->dancing_as->eer_is_leader($sold_ticket->dancing_as)) {
							$update['registered_leaders']   = $summary->registered_leaders - 1;
							$update['registered_followers'] = $summary->registered_followers + 1;
						}
					}

					if (!empty($update)) {
						$wpdb->update($wpdb->prefix . 'eer_ticket_summary', $update, [
							'id' => $summary->id,
						]);
					}
				}
			}
		}
	}

}