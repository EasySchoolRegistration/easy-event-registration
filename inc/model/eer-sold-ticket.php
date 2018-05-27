<?php

if (!defined('ABSPATH')) {
	exit;
}

if (!class_exists('EER_Sold_Ticket')) {

	class EER_Sold_Ticket {

		public function generate_unique_key($event_id) {
			global $wpdb;

			do {
				$token  = mt_rand(10000000, 99999999);
				$result = $wpdb->get_var($wpdb->prepare("SELECT 1 FROM {$wpdb->prefix}eer_sold_tickets AS st JOIN {$wpdb->prefix}eer_events_orders AS eo ON st.order_id = eo.id WHERE eo.event_id = %d AND st.unique_key = %s", [$event_id, $token]));
			} while (intval($result) > 0);

			return $token;
		}

		public function eer_get_sold_tickets_data($sold_ticket_id) {
			global $wpdb;

			return $wpdb->get_row($wpdb->prepare("SELECT st.* FROM {$wpdb->prefix}eer_sold_tickets AS st WHERE st.id = %d", [$sold_ticket_id]), OBJECT);
		}

		public function eer_get_sold_tickets_by_event($event_id) {
			global $wpdb;

			return $wpdb->get_results($wpdb->prepare("SELECT st.* FROM {$wpdb->prefix}eer_sold_tickets AS st JOIN {$wpdb->prefix}eer_events_orders AS eo ON st.order_id = eo.id WHERE eo.event_id = %d", [$event_id]), OBJECT_K);
		}


		public function eer_get_sold_tickets_by_order($order_id) {
			global $wpdb;

			return $wpdb->get_results($wpdb->prepare("SELECT st.* FROM {$wpdb->prefix}eer_sold_tickets AS st WHERE st.order_id = %d", [$order_id]), OBJECT_K);
		}


		public function eer_get_confirmed_sold_tickets_by_order($order_id) {
			global $wpdb;

			return $wpdb->get_results($wpdb->prepare("SELECT st.*, t.* FROM {$wpdb->prefix}eer_sold_tickets AS st JOIN {$wpdb->prefix}eer_tickets AS t ON st.ticket_id = t.id WHERE st.order_id = %d AND st.status = %d", [$order_id, EER_Enum_Sold_Ticket_Status::CONFIRMED]), OBJECT_K);
		}


		public function eer_are_all_ordered_tickets_deleted($order_id) {
			global $wpdb;

			return filter_var($wpdb->get_var($wpdb->prepare("SELECT 1 FROM {$wpdb->prefix}eer_sold_tickets AS st WHERE st.order_id = %d AND st.status != %d", [$order_id, EER_Enum_Sold_Ticket_Status::DELETED])), FILTER_VALIDATE_BOOLEAN);
		}


		public function eer_count_sold_tickets($order_id) {
			global $wpdb;

			return $wpdb->get_var($wpdb->prepare("SELECT COUNT(*) FROM {$wpdb->prefix}eer_sold_tickets AS st WHERE st.order_id = %d", [$order_id]));
		}


		public function eer_ticket_order_exists($ticket_id, $user_id) {
			global $wpdb;

			return intval($wpdb->get_var($wpdb->prepare("SELECT 1 FROM {$wpdb->prefix}eer_sold_tickets AS st JOIN {$wpdb->prefix}eer_events_orders AS eo ON st.order_id = eo.id WHERE st.ticket_id = %d AND eo.user_id = %d", [$ticket_id, $user_id]))) > 0;
		}
	}
}