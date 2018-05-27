<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Worker_Order {

	public function update_order($order_data) {
		if ($order_data && isset($order_data['order_id'])) {
			$order = EER()->order->eer_get_order($order_data['order_id']);
			if ($order) {
				global $wpdb;

				$order_info = json_decode($order->order_info);

				$order_info->phone = $order_data['phone'];
				$order_info->country = $order_data['country'];
				$order_info->tshirt = $order_data['tshirt'];
				$order_info->food = $order_data['food'];
				$order_info->hosting = $order_data['hosting'] === 'on';
				$order_info->offer_hosting = $order_data['offer_hosting'] === 'on';

				$wpdb->update($wpdb->prefix . 'eer_events_orders', [
					'order_info' => json_encode($order_info)
				], [
					'id' => $order_data['order_id']
				]);

				apply_filters('eer_update_user_payment', $order_data['order_id']);
			}
		}
	}

}