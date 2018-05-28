<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Payment_Emails
{

	const MENU_SLUG = 'eer_admin_sub_page_payment_emails';


	public static function print_content()
	{
		$data = $_POST;
		$template_all_events = new EER_Template_All_Events_Select();

		$selected_event = $template_all_events->get_selected_event();

		if (isset($data['eer_send_payment_email_submit'])) {
			$event_data = EER()->event->get_event_data($selected_event);
			foreach ($data['eer_choosed_payments'] as $payment_id) {
				EER()->email->eer_send_payment_email($payment_id, $event_data);
			}
		}

		?>
		<div class="wrap">

		<?php
		$template_all_events->print_content($selected_event);
		?>

		<h2>Not Paid</h2>
		<input type="checkbox" name="eer-select-all"/><label><?php _e('select all', 'easy-school-registration'); ?></label>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
			<table>
				<tr>
					<th></th>
					<th><?php _e('Name', 'easy-school-registration'); ?></th>
					<th><?php _e('Email', 'easy-school-registration'); ?></th>
				</tr>
				<?php
				foreach (EER()->payment->eer_get_not_payed_payments_by_event($selected_event) as $payment) {
					?>
					<tr>
						<td><input type="checkbox" name="eer_choosed_payments[]" value="<?php echo $payment->payment_id; ?>">
						</td>
						<td><?php echo $payment->display_name; ?></td>
						<td><?php echo $payment->user_email; ?></td>
					</tr>
					<?php
				}

				?>
			</table>
			<input type="hidden" name="eer_event" value="<?php echo $selected_event; ?>">
			<input type="submit" name="eer_send_payment_email_submit">
		</form>
		</div><?php
	}
}
