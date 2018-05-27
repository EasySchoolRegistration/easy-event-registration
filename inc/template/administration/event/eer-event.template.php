<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Event {

	const MENU_SLUG = 'eer_admin_event';


	public static function print_content() {
		if (isset($_POST['eer_event_submit'])) {
			$worker_event = new EER_Worker_Event();
			$worker_event->process_event($_POST);
		}

		$subblock_events_edit_form = new EER_Subblock_Event_Editor();
		$subblock_events_table = new EER_Subblock_Event_Table();

		ob_start();
		?>
		<div class="wrap tabbable boxed parentTabs">
			<h1 class="wp-heading-inline"><?php _e('Events', 'easy-event-registration'); ?></h1>
			<a href="#" class="eer-add-new page-title-action"><?php _e('Add new event', 'easy-event-registration'); ?></a>
			<?php
				$subblock_events_edit_form->print_block();
				$subblock_events_table->print_block();
			?>
		</div><!-- #tab_container-->
		<?php
		echo ob_get_clean();
	}
}

