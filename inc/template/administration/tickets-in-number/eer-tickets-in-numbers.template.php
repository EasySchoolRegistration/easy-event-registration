<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Tickets_In_Numbers
{

	const MENU_SLUG = 'eer_admin_sub_page_tickets_in_numbers';


	public static function print_content()
	{
		$template_all_events = new EER_Template_All_Events_Select();

		$selected_event = $template_all_events->get_selected_event();


		if (isset($_POST['eer_recount_event']) && isset($_POST['eer_recount_event_id'])) {
			$worker_cin = new EER_Worker_Tickets_In_Numbers();
			$worker_cin->eer_recount_event_statistics((int)$_POST['eer_recount_event_id']);
		}

		?>
		<div class="wrap eer-settings">
			<?php $template_all_events->print_content($selected_event); ?>
			<h1 class="wp-heading-inline"><?php _e('Tickets in numbers', 'easy-school-registration'); ?></h1>

			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="eer-recount-button">
				<input type="hidden" name="eer_recount_event_id" value="<?php echo $selected_event; ?>">
				<input type="submit" name="eer_recount_event" class="page-title-action" value="Recount statistics">
			</form>
			<table id="datatable" class="eer-datatable table table-default table-bordered eer-tickets-in-numbers-table">
				<thead>
				<tr>
					<th class="no-sort"><?php _e('Ticket', 'easy-school-registration') ?></th>
					<th class="filter-disabled no-sort"><?php _e('Level', 'easy-school-registration') ?></th>
					<th class="filter-disabled no-sort"><?php _e('Leaders', 'easy-school-registration') ?></th>
					<th class="filter-disabled no-sort"><?php _e('Followers', 'easy-school-registration') ?></th>
					<th class="filter-disabled no-sort"><?php _e('Tickets', 'easy-school-registration') ?></th>
				</tr>
				</thead>
				<tbody>
				<?php
				foreach (EER()->ticket_summary->eer_get_ticket_by_event($selected_event) as $id => $ticket_summary) {
					?>
					<tr>
						<td><?php echo $ticket_summary->title; ?></td>
						<td><?php if ($ticket_summary->has_levels) {
								echo json_decode($ticket_summary->ticket_settings)->levels->{$ticket_summary->level_id}->name;
							} ?></td>
						<td><?php echo $ticket_summary->registered_leaders . '/' . $ticket_summary->max_leaders . ' (' . $ticket_summary->waiting_leaders . ')'; ?></td>
						<td><?php echo $ticket_summary->registered_followers . '/' . $ticket_summary->max_followers . ' (' . $ticket_summary->waiting_followers . ')'; ?></td>
						<td><?php echo $ticket_summary->registered_tickets . '/' . $ticket_summary->max_tickets . ' (' . $ticket_summary->waiting_tickets . ')'; ?></td>
					</tr>
					<?php
				}
				?>
				</tbody>
			</table>
		</div>

		<?php
	}

}
