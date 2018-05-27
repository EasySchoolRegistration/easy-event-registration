<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Sold_Ticket_Edit_Form {

	public function __construct() {
		add_action('eer_sold_ticket_form_input', [get_called_class(), 'input_dancing_as']);
		add_action('eer_sold_ticket_form_input', [get_called_class(), 'input_dancing_with']);
		add_action('eer_sold_ticket_form_input', [get_called_class(), 'input_dancing_with_name']);
		add_action('eer_sold_ticket_form_input', [get_called_class(), 'input_partner_email']);
		add_action('eer_sold_ticket_form_submit', [get_called_class(), 'input_submit']);
	}


	public function print_form() {
		?>
		<div id="eer-edit-box" class="eer-edit-box">
			<span class="close"><i class="fa fa-close"></i></span>
			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
				<table>
					<?php
					do_action('eer_sold_ticket_form_input');
					do_action('eer_sold_ticket_form_submit');
					?>
				</table>
			</form>
		</div>
		<?php
	}


	public static function input_dancing_as() {
		?>
		<tr>
			<th><?php _e('Dancing as', 'easy-event-registration'); ?></th>
			<td>
				<select id="dancing_as" class="eer-form-control eer-input" name="dancing_as">
					<option value=""><?php _e('- select -', 'easy-event-registration'); ?></option>
					<?php
					foreach (EER()->dancing_as->eer_get_items() as $id => $item) {
						?>
						<option value="<?php echo $id; ?>"><?php echo $item['title']; ?></option><?php
					}
					?>
				</select>
			</td>
		</tr>
		<?php
	}


	public static function input_dancing_with() {
		?>
		<tr>
			<th><?php _e('Dancing with', 'easy-event-registration'); ?></th>
			<td><input id="dancing_with" class="eer-form-control eer-input" type="email" name="dancing_with"></td>
		</tr>
		<?php
	}


	public static function input_dancing_with_name() {
		?>
		<tr>
			<th><?php _e('Dancing with name', 'easy-event-registration'); ?></th>
			<td><input id="dancing_with_name" class="eer-form-control eer-input" type="text" name="dancing_with_name"></td>
		</tr>
		<?php
	}


	public static function input_partner_email() {
		?>
		<tr>
			<th><?php _e('Partner email', 'easy-event-registration'); ?></th>
			<td><input id="partner_email" class="eer-form-control eer-input" type="email" name="partner_email"></td>
		</tr>
		<?php
	}


	public static function input_submit() {
		?>
		<tr>
			<th></th>
			<td>
				<input type="hidden" name="sold_ticket_id">
				<input type="submit" name="eer_sold_ticket_edit_submit" value="<?php _e('Save', 'easy-event-registration'); ?>">
			</td>
		</tr>
		<?php
	}
}
