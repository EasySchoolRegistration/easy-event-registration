<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Ticket
{

	const MENU_SLUG = 'eer_admin_ticket';

	public static function print_content()
	{
		if (isset($_POST['eer_ticket_submit'])) {
			$worker_ticket = new EER_Worker_Ticket();
			$worker_ticket->process_ticket($_POST);
		}

		$subblock_tickets_edit_form = new EER_Subblock_Ticket_Editor();
		$subblock_tickets_table = new EER_Subblock_Ticket_Table();

		ob_start();
		?>
		<div class="wrap tabbable boxed parentTabs">
			<h1 class="wp-heading-inline"><?php _e('Tickets', 'easy-event-registration'); ?></h1>
			<a href="#" class="eer-add-new page-title-action"><?php _e('Add new ticket', 'easy-event-registration'); ?></a>
			<?php
			$subblock_tickets_edit_form->print_block();
			$subblock_tickets_table->print_block();
			?>
		</div><!-- #tab_container-->
		<?php
		echo ob_get_clean();
	}

}
