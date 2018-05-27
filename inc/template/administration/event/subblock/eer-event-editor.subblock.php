<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Subblock_Event_Editor {

	public function __construct() {
		add_action('eer_event_edit_form_input', [get_called_class(), 'input_title']);
		add_action('eer_event_edit_form_input', [get_called_class(), 'input_sale_start']);
		add_action('eer_event_edit_form_input', [get_called_class(), 'input_sale_end']);
		add_action('eer_event_edit_form_submit', [get_called_class(), 'input_submit']);
	}


	public function print_block() {
		$settings_tabs = EER()->event->eer_get_event_settings_tabs();
		$settings_tabs = empty($settings_tabs) ? [] : $settings_tabs;
		$active_tab    = isset($_GET['tab']) ? sanitize_text_field($_GET['tab']) : 'event_general';
		$active_tab    = array_key_exists($active_tab, $settings_tabs) ? $active_tab : 'event_general';
		$sections      = EER()->event->eer_get_event_settings_sections();
		?>
		<div class="eer-edit-box">
			<span class="close"><i class="fa fa-close"></i></span>

			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" class="tab-content">
				<h3><?php _e('Main info', 'easy-event-registration'); ?></h3>
				<table>
					<?php
					do_action('eer_event_edit_form_input');
					?>
				</table>
				<h3><?php _e('Settings', 'easy-event-registration'); ?></h3>
				<ul class="nav nav-tabs">
					<?php
					$number = 0;
					foreach (EER()->event->eer_get_event_settings_tabs() as $tab_id => $tab_name) {
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
				$section_number             = 0;
				foreach ($sections as $section_id => $subsections) {
					$number_of_sections = count($subsections);
					$number             = 0;
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
										$this->eer_print_events_settings_tab($section_id, $sub_section_id);
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
				do_action('eer_event_edit_form_submit');
				?>
			</form>
		</div>
		<?php
	}


	public static function input_title() {
		?>
		<tr>
			<th><?php _e('Title', 'easy-event-registration'); ?></th>
			<td><input id="title" required type="text" name="title" class="eer-input"></td>
		</tr>
		<?php
	}


	public static function input_sale_start() {
		?>
		<tr>
			<th><?php _e('Start date and time of sale', 'easy-event-registration'); ?></th>
			<td>
				<input id="sale_start" name="sale_start" type="datetime-local" class="eer-input">
			</td>
		</tr>
		<?php
	}


	public static function input_sale_end() {
		?>
		<tr>
			<th><?php _e('End date and time of sale', 'easy-event-registration'); ?></th>
			<td>
				<input id="sale_end" name="sale_end" type="datetime-local" class="eer-input">
			</td>
		</tr>
		<?php
	}


	public static function input_submit() {
		?>
		<tr>
			<th></th>
			<td>
				<input type="hidden" name="event_id">
				<input type="submit" name="eer_event_submit" value="<?php _e('Save', 'easy-event-registration'); ?>">
			</td>
		</tr>
		<?php
	}

	public static function eer_print_events_settings_tab($section_id, $sub_section_id) {
		$model_settings = new EER_Models_Settings_Helper_Templater();
		$model_settings->eer_print_settings_tab('event', EER()->event->eer_get_event_settings_fields_to_print($section_id, $sub_section_id));
	}
}