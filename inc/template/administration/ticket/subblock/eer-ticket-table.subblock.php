<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Subblock_Ticket_Table
{

	public function print_block()
	{
		$tickets = EER()->ticket->load_tickets();
		?>
		<table id="datatable" class="table table-default table-bordered eer-datatable" data-eer-columns="<?php do_action('eer_get_ticket_columns'); ?>">
			<colgroup>
				<col width="100">
				<col width="180">
				<col width="500">
			</colgroup>
			<thead>
			<tr>
				<th class="filter-disabled no-sort" data-key="eer_actions"><?php _e('Actions', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e('Title', 'easy-event-registration'); ?></th>
				<th class="no-sort" data-key="eer_title"><?php _e('Event', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e('Price', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e('Number of tickets', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e('Max per order', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e('Sold separately', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e('Position', 'easy-event-registration'); ?></th>
			</tr>
			</thead>
			<tbody class="list">
			<?php foreach ($tickets as $ticket) {

				?>
				<tr class="<?php echo apply_filters('eer_get_ticket_row_classes', $ticket); ?>"
					<?php apply_filters('eer_print_ticket_data', $ticket); ?>>
					<td class="actions eer-tickets">
						<div class="eer-relative">
							<button class="page-title-action">Actions</button>
							<?php $this->print_action_box($ticket->id); ?>
						</div>
					</td>
					<td><?php echo $ticket->title; ?></td>
					<td><?php echo EER()->event->get_event_title($ticket->event_id); ?></td>
					<td><?php echo EER()->currency->eer_prepare_price($ticket->event_id, $ticket->price); ?></td>
					<td><?php
						if ($ticket->has_levels) {
							foreach ($ticket->levels as $level_key => $level) {
								echo '<strong>' . $level['name'] . '</strong></br>';
								if ($ticket->is_solo) {
									echo $level['tickets'] . '</br>';
								} else {
									echo __('Leaders', 'easy-event-registration') . ': ' . $level['leaders'] . '</br>';
									echo __('Followers', 'easy-event-registration') . ': ' . $level['followers'] . '</br>';
								}
							}
						} elseif ($ticket->is_solo) {
							echo $ticket->max_tickets;
						} else {
							echo __('Leaders', 'easy-event-registration') . ': ' . $ticket->max_leaders; ?><br/><?php echo __('Followers', 'easy-event-registration') . ': ' . $ticket->max_followers;
						}
						?></td>
					<td><?php echo $ticket->max_per_order; ?></td>
					<td><?php echo $ticket->sold_separately; ?></td>
					<td><?php echo $ticket->position; ?></td>
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
			<li class="eer-action remove">
				<a href="javascript:;">
					<i class="fa fa-remove"></i>
					<span><?php _e('Remove', 'easy-event-registration'); ?></span>
				</a>
			</li>
			<li class="eer-action remove-forever">
				<a href="javascript:;">
					<i class="fa fa-remove"></i>
					<span><?php _e('Remove Forever', 'easy-event-registration'); ?></span>
				</a>
			</li>
		</ul>
		<?php
	}


	public static function get_columns()
	{
		echo implode(';', array_keys((array)EER()->event->get_fields()));
	}


	public static function print_ticket_data($data)
	{
		$fields = EER()->ticket->get_fields();
		foreach ($data as $name => $value) {
			if (isset($fields->$name) && ($fields->$name['type'] === 'timestamp')) {
				echo ' data-' . $name . '="' . strftime('%Y-%m-%dT%H:%M', strtotime($value)) . '"';
			} elseif (is_array($value)) {
				echo ' data-' . $name . '="' . htmlspecialchars(json_encode($value)) . '"';
			} else {
				echo ' data-' . $name . '="' . htmlspecialchars($value) . '"';
			}
		}
	}


	public static function get_row_classes($ticket)
	{
		$classes = [
			'eer-row',
			'eer-ticket'
		];

		if (intval($ticket->to_remove) === 1) {
			$classes[] = 'eer-to-remove';
		}

		return implode(' ', $classes);
	}

}

add_action('eer_get_ticket_columns', ['EER_Subblock_Ticket_Table', 'get_columns']);
add_filter('eer_get_ticket_row_classes', ['EER_Subblock_Ticket_Table', 'get_row_classes']);
add_filter('eer_print_ticket_data', ['EER_Subblock_Ticket_Table', 'print_ticket_data']);