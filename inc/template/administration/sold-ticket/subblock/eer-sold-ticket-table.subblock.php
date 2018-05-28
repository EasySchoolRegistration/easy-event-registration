<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Subblock_Sold_Ticket_Table
{

	public function print_block($event_id)
	{
		$sold_tickets = EER()->sold_ticket->eer_get_sold_tickets_by_event($event_id);
		$tickets = EER()->ticket->get_tickets_by_event($event_id);

		$orders = EER()->order->eer_get_orders_by_event($event_id);
		$event_data = EER()->event->get_event_data($event_id);

		$partner_name_enabled = intval(EER()->event->eer_get_event_option($event_data, 'partner_name_enabled', -1)) === 1;
		?>
		<table id="datatable" class="table table-default table-bordered eer-datatable eer-sold-tickets">
			<colgroup>
				<col width="170">
				<col width="100">
				<col width="50">
			</colgroup>
			<thead>
			<tr>
				<th class="filter-disabled"><?php _e('Order Time', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort"
				    data-key="eer_actions"><?php _e('Actions', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Status', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Ticket', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Name', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Email', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort"><?php _e('Code', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Level', 'easy-event-registration'); ?></th>
				<th class="no-sort"><?php _e('Dancing As', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort"><?php _e('Dancing With', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort"><?php _e('Partner', 'easy-event-registration'); ?></th>
				<th class="filter-disabled"><?php _e('C.P. position', 'easy-event-registration'); ?></th>
			</tr>
			</thead>
			<tbody class="list">
			<?php
			$users_data = get_users(['fields' => ['ID', 'display_name', 'user_email']]);
			$users = [];
			foreach ($users_data as $u) {
				$users[$u->ID] = $u;
			}
			foreach ($sold_tickets as $sold_ticket) {
				$order = isset($orders[$sold_ticket->order_id]) ? $orders[$sold_ticket->order_id] : null;

				$ticket_id = $sold_ticket->ticket_id;
				$levels = isset($tickets[$ticket_id]->levels) ? $tickets[$ticket_id]->levels : [];

				?>
				<tr class="eer-row eer-status-<?php echo $sold_ticket->status; ?>"
				    data-id="<?php echo $sold_ticket->id; ?>"
				    data-dancing_as="<?php echo $sold_ticket->dancing_as; ?>"
				    data-dancing_with="<?php echo $sold_ticket->dancing_with; ?>"
				    data-dancing_with_name="<?php echo $sold_ticket->dancing_with_name; ?>">
					<td><?php echo $sold_ticket->inserted_datetime; ?></td>
					<td class="actions eer-sold-tickets">
						<div class="eer-relative">
							<button class="page-title-action" type="button" data-toggle="dropdown"
							        aria-expanded="false">Actions
							</button>
							<?php $this->print_action_box($sold_ticket->id); ?>
						</div>
					</td>
					<td><?php echo EER()->sold_ticket_status->get_title($sold_ticket->status); ?></td>
					<td><?php echo isset($tickets[$ticket_id]) ? $tickets[$ticket_id]->title : ''; ?></td>
					<td><?php echo isset($users[$order->user_id]) ? $users[$order->user_id]->display_name : ''; ?></td>
					<td><?php echo isset($users[$order->user_id]) ? $users[$order->user_id]->user_email : ''; ?></td>
					<td><?php echo $sold_ticket->unique_key; ?></td>
					<td><?php echo ($levels && $sold_ticket->level_id !== null) ? $levels[$sold_ticket->level_id]['name'] : ''; ?></td>
					<td><?php echo EER()->dancing_as->eer_get_title($sold_ticket->dancing_as); ?></td>
					<td><?php
						echo $sold_ticket->dancing_with;
						if ($partner_name_enabled && $sold_ticket->dancing_with_name) {
							echo ' (' . $sold_ticket->dancing_with_name . ')';
						}
						?></td>
					<td><?php if ($sold_ticket->partner_id) {
							echo $users[$sold_ticket->partner_id]->display_name;
						}; ?></td>
					<td><?php echo(isset($sold_ticket->cp_position) ? $sold_ticket->cp_position : ''); ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php
	}


	private function print_action_box($id)
	{
		?>
		<ul class="eer-actions-box dropdown-menu" data-id="<?php echo $id; ?>">
			<li class="eer-action edit">
				<a href="javascript:;">
					<i class="fa fa-edit"></i>
					<span><?php _e('Edit', 'easy-event-registration'); ?></span>
				</a>
			</li>
			<li class="eer-action confirm">
				<a href="javascript:;">
					<i class="fa fa-check"></i>
					<span><?php _e('Confirm', 'easy-event-registration'); ?></span>
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
		</ul>
		<?php
	}
}
