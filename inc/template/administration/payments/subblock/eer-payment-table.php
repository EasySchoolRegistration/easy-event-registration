<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Subblock_Template_Payment_Table
{

	public function print_table($selected_event)
	{
		$enum_payment = new EER_Enum_Payment();
		$user_can_edit = current_user_can('eer_payment_edit');
		$event_data = EER()->event->get_event_data($selected_event);

		$payments = EER()->payment->eer_get_payments_by_event($selected_event);

		?>
		<table id="datatable" class="eer-datatable table table-default table-bordered eer-payments-table">
			<colgroup>
				<col width="10">
				<col width="100">
			</colgroup>
			<thead>
			<tr>
				<th class="no-sort"><?php _e('Status', 'easy-school-registration') ?></th>
				<?php if ($user_can_edit) { ?>
					<th class="filter-disabled no-sort eer-hide-print"><?php _e('Actions', 'easy-school-registration') ?></th>
				<?php } ?>
				<th class="filter-disabled no-sort"><?php _e('Note', 'easy-school-registration') ?></th>
				<th class="filter-disabled no-sort"><?php _e('Name', 'easy-school-registration') ?></th>
				<th class="filter-disabled no-sort"><?php _e('Email', 'easy-school-registration') ?></th>
				<th class="filter-disabled no-sort"><?php _e('Order code', 'easy-school-registration') ?></th>
				<th class="no-sort"><?php _e('To pay', 'easy-school-registration') ?></th>
				<th class="no-sort"><?php _e('Paid', 'easy-school-registration') ?></th>
			</tr>
			</thead>
			<tbody class="list">
			<?php
			foreach ($payments

			as $order_id => $payment) {
			$user_data = get_userdata($payment->user_id);
			$user_email = $user_data ? $user_data->user_email : '';
			$user_name = $user_data ? $user_data->last_name . ' ' . $user_data->first_name : '';
			$paid_status = $enum_payment->get_status($payment);

			?>
			<tr class="eer-row <?php echo 'paid-status-' . $paid_status; ?>"
				<?php if ($user_can_edit) { ?>
					data-id="<?php echo $payment->id; ?>"
					data-order_id="<?php echo $payment->order_id; ?>"
					data-email="<?php echo $user_email; ?>"
					data-to_pay="<?php echo $payment->to_pay; ?>"
					data-payment="<?php echo $payment->payment; ?>"
					data-event_id="<?php echo $payment->event_id?>"
				<?php } ?>
			>
				<td class="status"><?php echo $enum_payment->get_title($paid_status); ?></td>
				<?php if ($user_can_edit) { ?>
					<td class="actions eer-payment">
						<div class="eer-relative">
							<button class="page-title-action" type="button" data-toggle="dropdown" aria-expanded="false">Actions</button>
							<?php $this->print_action_box($payment->order_id); ?>
						</div>
					</td>
				<?php } ?>
				<td>

				</td>
				<td class="student-surname"><?php echo $user_name; ?></td>
				<td class="student-email"><?php echo $user_email; ?></td>
				<td class="variable-symbol"><?php echo $payment->unique_key; ?></td>
				<td><?php echo EER()->currency->eer_prepare_price($selected_event, $payment->to_pay, $event_data); ?></td>
				<td class="student-paid"><?php echo(($payment && isset($payment->payment) && (!in_array($paid_status, [EER_Enum_Payment::NOT_PAYING, EER_Enum_Payment::VOUCHER]))) ? EER()->currency->eer_prepare_price($selected_event, $payment->payment, $event_data) : ''); ?></td>
				<?php } ?>
			</tbody>
		</table>
		<?php
	}


	private function print_action_box($id)
	{
		?>
		<ul class="eer-actions-box dropdown-menu" data-id="<?php echo $id; ?>">
			<li class="eer-action confirm-payment">
				<a href="javascript:;">
					<i class="fa fa-edit"></i>
					<span><?php _e('Confirm payment', 'easy-school-registration'); ?></span>
				</a>
			</li>
		</ul>
		<?php
	}

}