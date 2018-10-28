<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Subblock_Ticket_Editor
{

	public function __construct()
	{
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_title']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_event']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_price']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_is_solo']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_max_leaders']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_max_followers']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_max_tickets']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_sold_separately']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_once_per_user']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_max_per_order']);
		add_action('eer_ticket_edit_form_input', [get_called_class(), 'input_position']);
		add_action('eer_ticket_edit_form_submit', [get_called_class(), 'input_submit']);
	}


	public function print_block()
	{
		$settings_tabs = EER()->ticket->eer_get_ticket_settings_tabs();
		$settings_tabs = empty($settings_tabs) ? [] : $settings_tabs;
		$active_tab = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'currency';
		$active_tab = array_key_exists($active_tab, $settings_tabs) ? $active_tab : 'currency';
		$sections = EER()->ticket->eer_get_ticket_settings_sections();
		?>
		<div class="eer-edit-box">
			<span class="close"><i class="fa fa-close"></i></span>

			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="tab-content">
				<h3><?php _e('Main info', 'easy-event-registration'); ?></h3>
				<table>
					<?php
					do_action('eer_ticket_edit_form_input');
					?>
				</table>
				<h3><?php _e('Settings', 'easy-event-registration'); ?></h3>
				<ul class="nav nav-tabs">
					<?php
					$number = 0;
					foreach (EER()->ticket->eer_get_ticket_settings_tabs() as $tab_id => $tab_name) {
						$active = $active_tab == $tab_id ? ' nav-tab-active' : '';
						echo '<li class="' . ($number === 0 ? 'active' : '') . '">';
						echo '<a href="#' . $tab_id . '" class="nav-tab' . $active . '" data-toggle="tab">';
						echo esc_html($tab_name);
						echo '</a>';
						echo '</li>';
						$number++;
					}
					?>
				</ul>
				<?php
				$section_number = 0;
				foreach ($sections as $section_id => $subsections) {
					$number_of_sections = count($subsections);
					$number = 0;
					if ($number_of_sections > 0) {
						?>
					<div id="<?php echo $section_id; ?>" class="tab-pane<?php echo($section_number === 0 ? ' active' : '') ?>">
						<div class="tabbable">
							<ul class="nav nav-tabs subsubsub eer-sub-tabs">
								<?php
								foreach ($subsections as $sub_section_id => $section_name) {
									echo '<li>';
									echo '<a class="nav-tab" href="#' . $sub_section_id . '" data-toggle="tab">' . $section_name . '</a>';
									$number++;
									if ($number != $number_of_sections) {
										echo ' | ';
									}
									echo '</li>';
								}
								?></ul><?php
							?>
							<div class="tab-content"><?php
								$number = 0;
								foreach ($subsections as $sub_section_id => $section_name) {
									?>
									<table id="<?php echo $sub_section_id; ?>" class="tab-pane form-table<?php echo($number === 0 ? ' active' : '') ?>">
										<?php
										$this->eer_print_tickets_settings_tab($section_id, $sub_section_id);
										?>
									</table>
									<?php
									$number++;
								}
								?></div>
						</div>
						</div><?php
					}
					$section_number++;
				}
				?>
				<?php
				do_action('eer_ticket_edit_form_submit');
				?>
			</form>
		</div>
		<?php
	}


	public static function input_title()
	{
		?>
		<tr>
			<th><?php _e('Title', 'easy-event-registration'); ?></th>
			<td><input id="title" required type="text" name="title" class="eer-input"></td>
		</tr>
		<?php
	}


	public static function input_event()
	{
		?>
		<tr>
			<th><?php _e('Event', 'easy-event-registration'); ?></th>
			<td>
				<select id="event_id" name="event_id" class="eer-input">
					<option value=""><?php _e('- select -', 'easy-event-registration'); ?></option>
					<?php foreach (EER()->event->load_events_without_data() as $key => $event) { ?>
						<option value="<?php echo $event->id; ?>"><?php echo $event->title; ?></option>
						<?php
					}
					?>
				</select>
			</td>
		</tr>
		<?php
	}


	public static function input_is_solo()
	{
		?>
		<tr>
			<th><?php _e('Is solo', 'easy-event-registration'); ?></th>
			<td><input id="is_solo" type="checkbox" name="is_solo" class="eer-input" data-show=".max_tickets" data-hide=".max_leaders, .max_followers" value="1"></td>
		</tr>
		<?php
	}


	private static function add_number($name, $key, $class = '', $hidden = false)
	{
		?>
		<tr class="<?php echo $class; ?>" <?php echo($hidden ? 'style="display:none;"' : ''); ?>>
			<th><?php _e($name, 'easy-event-registration'); ?></th>
			<td><input id="<?php echo $key; ?>" type="number" name="<?php echo $key; ?>" value="0" class="eer-input"></td>
		</tr>
		<?php
	}


	public static function input_max_leaders()
	{
		self::add_number('Max leaders', 'max_leaders', 'max_leaders');
	}


	public static function input_max_followers()
	{
		self::add_number('Max followers', 'max_followers', 'max_followers');
	}


	public static function input_max_tickets()
	{
		self::add_number('Max tickets', 'max_tickets', 'max_tickets', true);
	}


	public static function input_price()
	{
		?>
		<tr>
			<th><?php _e('Price', 'easy-event-registration'); ?></th>
			<td><input id="price" required type="number" name="price" class="eer-input"></td>
		</tr>
		<?php
	}


	public static function input_max_per_order()
	{
		?>
		<tr class="max_per_order">
			<th><?php _e('Max per order', 'easy-event-registration'); ?></th>
			<td><input id="max_per_order" type="number" name="max_per_order" class="eer-input"></td>
		</tr>
		<?php
	}


	public static function input_sold_separately()
	{
		?>
		<tr>
			<th><?php _e('Sold separately', 'easy-event-registration'); ?></th>
			<td><input id="sold_separately" type="checkbox" name="sold_separately" class="eer-input" value="1"></td>
		</tr>
		<?php
	}


	public static function input_once_per_user()
	{
		?>
		<tr>
			<th><?php _e('Once per user', 'easy-event-registration'); ?></th>
			<td><input id="once_per_user" type="checkbox" name="once_per_user" class="eer-input" value="1"></td>
		</tr>
		<?php
	}


	public static function input_position()
	{
		?>
		<tr>
			<th><?php _e('Position', 'easy-event-registration'); ?></th>
			<td><input id="position" type="number" name="position" class="eer-input"></td>
		</tr>
		<?php
	}


	public static function input_submit()
	{
		?>
		<tr>
			<th></th>
			<td>
				<input type="hidden" name="ticket_id">
				<input type="submit" name="eer_ticket_submit" value="<?php _e('Save', 'easy-event-registration'); ?>">
			</td>
		</tr>
		<?php
	}

	public static function eer_print_tickets_settings_tab($section_id, $sub_section_id)
	{
		$model_settings = new EER_Models_Settings_Helper_Templater();
		$model_settings->eer_print_settings_tab('ticket', EER()->ticket->eer_get_ticket_settings_fields_to_print($section_id, $sub_section_id));
	}
}