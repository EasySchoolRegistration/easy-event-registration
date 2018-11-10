<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Subblock_Template_Payment_Table {

	public function print_table($selected_event) {
		$enum_payment  = new EER_Enum_Payment();
		$user_can_edit = current_user_can('eer_payment_edit');
		$event_data    = EER()->event->get_event_data($selected_event);

		$payments = EER()->payment->eer_get_payments_by_event($selected_event);
		$ticket_unique = EER()->sold_ticket->eer_get_sold_ticket_unique_by_event($selected_event);

		?>
		<table id="datatable" class="eer-datatable table table-default table-bordered eer-payments-table">
			<colgroup>
				<col width="10">
				<col width="100">
			</colgroup>
			<thead>
			<tr>
				<th class="no-sort"><?php _e('Status', 'easy-event-registration') ?></th>
				<?php if ($user_can_edit) { ?>
					<th class="filter-disabled no-sort eer-hide-print"><?php _e('Actions', 'easy-event-registration') ?></th>
				<?php } ?>
				<th class="filter-disabled no-sort eer-header-note"><?php _e('Note', 'easy-event-registration'); ?>
					<i class="far fa-comment-alt eer-show-all-notes"></i>
					<i class="fas fa-comment-alt eer-hide-all-notes"></i></th>
				<th class="filter-disabled no-sort"><?php _e('Name', 'easy-event-registration') ?></th>
				<th class="filter-disabled no-sort"><?php _e('Email', 'easy-event-registration') ?></th>
				<th class="filter-disabled no-sort"><?php _e('Order code', 'easy-event-registration') ?></th>
				<th class="filter-disabled no-sort"><?php _e('Ticket codes', 'easy-event-registration') ?></th>
				<th class="no-sort"><?php _e('To pay', 'easy-event-registration') ?></th>
				<th class="no-sort"><?php _e('Paid', 'easy-event-registration') ?></th>
			</tr>
			</thead>
			<tbody class="list">
			<?php
			foreach ($payments as $order_id => $payment) {
			$user_data   = get_userdata($payment->user_id);
			$user_email  = $user_data ? $user_data->user_email : '';
			$user_name   = $user_data ? $user_data->last_name . ' ' . $user_data->first_name : '';
			$paid_status = $enum_payment->get_status($payment);

			?>
			<tr class="eer-row <?php echo 'paid-status-' . $paid_status; ?>"
				<?php if ($user_can_edit) { ?>
					data-id="<?php echo $payment->id; ?>"
					data-order_id="<?php echo $payment->order_id; ?>"
					data-email="<?php echo $user_email; ?>"
					data-to_pay="<?php echo $payment->to_pay; ?>"
					data-payment="<?php echo $payment->payment; ?>"
					data-event_id="<?php echo $payment->event_id ?>"
				<?php } ?>
			>
				<td class="status"><?php echo $enum_payment->get_title($paid_status); ?></td>
				<?php if ($user_can_edit) { ?>
					<td class="actions eer-payment">
						<div class="eer-relative">
							<button class="page-title-action">Actions</button>
							<?php $this->print_action_box($payment->order_id); ?>
						</div>
					</td>
				<?php } ?>

				<td class="eer-note">
					<?php if (($payment->note !== null) && ($payment->note !== "")) { ?>
						<i class="far fa-comment-alt eer-show-note" title="<?php echo $payment->note; ?>"></i>
						<i class="fas fa-comment-alt eer-hide-note"></i>
						<span class="eer-note-message"><?php echo $payment->note; ?></span>
					<?php } ?>
				</td>
				<td class="student-surname"><?php echo $user_name; ?></td>
				<td class="student-email"><?php echo $user_email; ?></td>
				<td class="variable-symbol"><?php echo $payment->unique_key; ?></td>
				<td class="ticket-variable-symbol"><?php
					if (isset($ticket_unique[$payment->order_id])) {
						foreach ($ticket_unique[$payment->order_id] as $key => $ticket_key) {
							echo $ticket_key . '<br>';
						}
					}
					?></td>
				<td><?php echo EER()->currency->eer_prepare_price($selected_event, $payment->to_pay, $event_data); ?></td>
				<td class="student-paid"><?php echo(($payment && isset($payment->payment) && (!in_array($paid_status, [EER_Enum_Payment::NOT_PAYING, EER_Enum_Payment::VOUCHER]))) ? EER()->currency->eer_prepare_price($selected_event, $payment->payment, $event_data) : ''); ?></td>
				<?php } ?>
			</tbody>
		</table>
		<?php
	}


	private function print_action_box($id) {
		?>
		<ul class="eer-actions-box dropdown-menu" data-id="<?php echo $id; ?>">
			<li class="eer-action confirm-payment">
				<a href="javascript:;">
					<i class="fa fa-edit"></i>
					<span><?php _e('Confirm payment', 'easy-event-registration'); ?></span>
				</a>
			</li>
		</ul>
		<?php
	}

}
