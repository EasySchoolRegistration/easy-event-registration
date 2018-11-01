<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Subblock_Order_Table {

	public function print_block($event_id) {
		$orders     = EER()->order->eer_get_orders_by_event($event_id);
		$event_data = EER()->event->get_event_data($event_id);

		$users_data = [];

		$hosting_enabled       = intval(EER()->event->eer_get_event_option($event_data, 'hosting_enabled', -1)) === 1;
		$tshirts_enabled       = intval(EER()->event->eer_get_event_option($event_data, 'tshirts_enabled', -1)) === 1;
		$food_enabled          = intval(EER()->event->eer_get_event_option($event_data, 'food_enabled', -1)) === 1;
		$offer_hosting_enabled = intval(EER()->event->eer_get_event_option($event_data, 'offer_hosting_enabled', -1)) === 1;

		?>
		<table id="datatable" class="table table-default table-bordered eer-datatable eer-orders">
			<colgroup>
				<col width="150">
				<col width="100">
				<col width="50">
			</colgroup>
			<thead>
			<tr>
				<th class="filter-disabled"><?php _e('Order Time', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_actions"><?php _e('Actions', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort"><?php _e('Note', 'easy-event-registration'); ?></th>
				<th class="filter-disabled"><?php _e('Code', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Name', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Surname', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Email', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Phone', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Country', 'easy-event-registration'); ?></th>
				<?php if ($hosting_enabled) {
					?>
					<th class="no-sort"><?php _e('Hosting', 'easy-event-registration'); ?></th><?php
				} ?>
				<?php if ($tshirts_enabled) {
					?>
					<th class="no-sort"><?php _e('T-shirt', 'easy-event-registration'); ?></th><?php
				} ?>
				<?php if ($food_enabled) {
					?>
					<th class="no-sort"><?php _e('Food', 'easy-event-registration'); ?></th><?php
				} ?>
				<?php if ($offer_hosting_enabled) {
					?>
					<th class="no-sort"><?php _e('Offer hosting', 'easy-event-registration'); ?></th><?php
				} ?>
			</tr>
			</thead>
			<tbody class="list">
			<?php foreach ($orders as $order) {
				if (!isset($users_data[$order->user_id])) {
					$users_data[$order->user_id] = get_userdata($order->user_id);
				}

				$user_data_exists = isset($users_data[$order->user_id]) && $users_data[$order->user_id];
				$order_info       = json_decode($order->order_info);

				?>
				<tr class="<?php echo apply_filters('eer_get_order_row_classes', $order); ?>"
				    data-id="<?php echo $order->id; ?>"
				    data-phone="<?php echo(isset($order_info->phone) ? $order_info->phone : ''); ?>"
				    data-country="<?php echo(isset($order_info->country) ? $order_info->country : ''); ?>"
				    data-hosting="<?php echo(isset($order_info->hosting) ? $order_info->hosting : ''); ?>"
				    data-tshirt="<?php echo(isset($order_info->tshirt) ? $order_info->tshirt : ''); ?>"
				    data-food="<?php echo(isset($order_info->food) ? $order_info->food : ''); ?>"
				    data-offer_hosting="<?php echo(isset($order_info->offer_hosting) ? $order_info->offer_hosting : ''); ?>">
					<td><?php echo $order->inserted_datetime; ?></td>
					<td class="actions eer-orders">
						<div class="eer-relative">
							<button class="page-title-action">Actions</button>
							<?php $this->print_action_box($order->id); ?>
						</div>
					</td>
					<td><?php if (($order_info->note !== null) && ($order_info->note !== "")) { ?><i
							class="fa fa-commenting" title="<?php echo $order_info->note; ?>"></i><?php } ?></td>
					<td><?php echo $order->unique_key; ?></td>
					<td><?php echo($user_data_exists ? $users_data[$order->user_id]->first_name : ''); ?></td>
					<td><?php echo($user_data_exists ? $users_data[$order->user_id]->last_name : ''); ?></td>
					<td><?php echo($user_data_exists ? $users_data[$order->user_id]->user_email : ''); ?></td>
					<td><?php echo (isset($order_info->phone)) ? $order_info->phone : '' ?></td>
					<td><?php echo (isset($order_info->country)) ? $order_info->country : '' ?></td>
					<?php if ($hosting_enabled) {
						?>
						<td><?php echo $order_info->hosting ? __('Yes', 'easy-event-registration') : __('No', 'easy-event-registration'); ?></td><?php
					} ?>
					<?php if ($tshirts_enabled) {
						?>
						<td><?php echo ($order_info->tshirt === '') || !isset($event_data->tshirt_options[$order_info->tshirt]) ? __('No', 'easy-event-registration') : $event_data->tshirt_options[$order_info->tshirt]['name']; ?></td><?php
					} ?>
					<?php if ($food_enabled) {
						?>
						<td><?php echo ($order_info->food === '') || !isset($event_data->food_options[$order_info->food]) ? __('No', 'easy-event-registration') : $event_data->food_options[$order_info->food]['option']; ?></td><?php
					} ?>
					<?php if ($offer_hosting_enabled) {
						?>
						<td><?php echo (isset($order_info->offer_hosting) && $order_info->offer_hosting) ? __('Yes', 'easy-event-registration') : __('No', 'easy-event-registration'); ?></td><?php
					} ?>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php
	}


	private function print_action_box($id) {
		?>
		<ul class="eer-actions-box dropdown-menu" data-id="<?php echo $id; ?>">
			<li class="eer-action edit">
				<a href="javascript:;">
					<i class="fa fa-edit"></i>
					<span><?php _e('Edit', 'easy-event-registration'); ?></span>
				</a>
			</li>
			<li class="eer-action remove">
				<a href="javascript:;">
					<i class="fa fa-close"></i>
					<span><?php _e('Remove', 'easy-event-registration'); ?></span>
				</a>
			</li>
			<li class="eer-action remove-forever">
				<a href="javascript:;">
					<i class="fa fa-close"></i>
					<span><?php _e('Remove forever', 'easy-event-registration'); ?></span>
				</a>
			</li>
			<li class="eer-action send-tickets">
				<a href="javascript:;">
					<i class="fa fa-ticket"></i>
					<span><?php _e('Send tickets', 'easy-event-registration'); ?></span>
				</a>
			</li>
		</ul>
		<?php
	}


	public static function eer_get_row_classes($order) {
		$classes = [
			'eer-row',
			'eer-order',
			'eer-status-' . $order->status
		];

		return implode(' ', $classes);
	}
}

add_filter('eer_get_order_row_classes', ['EER_Subblock_Order_Table', 'eer_get_row_classes']);
