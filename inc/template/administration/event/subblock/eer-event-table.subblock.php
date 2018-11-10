<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Subblock_Event_Table {

	public function print_block() {
		$events = EER()->event->load_events();
		?>
		<h1 class="wp-heading-inline"><?php _e('Events', 'easy-event-registration'); ?></h1>
		<a href="<?php echo esc_url(add_query_arg('event_id', -1)) ?>" class="eer-add-new page-title-action"><?php _e('Add new event', 'easy-event-registration'); ?></a>
		<table id="datatable" class="table table-default table-bordered eer-datatable" data-eer-columns="<?php do_action('eer_get_event_columns'); ?>">
			<colgroup>
				<col width="10">
				<col width="100">
				<col width="180">
				<col width="500">
			</colgroup>
			<thead>
			<tr>
				<th class="filter-disabled no-sort" data-key="eer_actions"><?php _e('ID', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_actions"><?php _e('Actions', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e('Title', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e('Start of sale', 'easy-event-registration'); ?></th>
				<th class="filter-disabled no-sort" data-key="eer_title"><?php _e('Sale ends', 'easy-event-registration'); ?></th>
			</tr>
			</thead>
			<tbody class="list">
			<?php foreach ($events as $event) {

				?>
				<tr class="<?php echo apply_filters('eer_get_event_row_classes', $event); ?>"
					<?php apply_filters('eer_print_event_data', $event); ?>>
					<td><?php echo $event->id; ?></td>
					<td class="actions eer-events">
						<div class="eer-relative">
							<button class="page-title-action">Actions</button>
							<?php $this->print_action_box($event->id); ?>
						</div>
					</td>
					<td><?php echo $event->title; ?></td>
					<td><?php echo $event->sale_start; ?></td>
					<td><?php echo $event->sale_end; ?></td>
				</tr>
			<?php } ?>
			</tbody>
		</table>
		<?php
	}


	private function print_action_box($id) {
		?>
		<ul class="eer-actions-box dropdown-menu" data-id="<?php echo $id; ?>">
			<li class="eer-action edit">
				<a href="<?php echo esc_url(add_query_arg('event_id', $id)) ?>">
					<i class="fa fa-edit"></i>
					<span><?php _e('Edit', 'easy-event-registration'); ?></span>
				</a>
			</li>
		</ul>
		<?php
	}


	public static function get_columns() {
		echo implode(';', array_keys((array) EER()->event->get_fields()));
	}


	public static function print_event_data($data) {
		$fields = EER()->event->get_fields();
		foreach ($data as $name => $value) {
			if ((is_array($fields) && isset($fields[$name]) && ($fields[$name]['type'] == 'timestamp')) || (is_object($fields) && isset($fields->$name) && ($fields->$name['type'] == 'timestamp'))) {
				echo ' data-' . $name . '="' . str_replace(' ', 'T', strftime('%Y-%m-%dT%H:%M:%S', strtotime($value))) . '"';
			} elseif (is_array($value)) {
				echo ' data-' . $name . '="' . htmlspecialchars(json_encode($value)) . '"';
			} else {
				echo ' data-' . $name . '="' . htmlspecialchars($value) . '"';
			}
		}
	}


	public static function get_row_classes($event) {
		$classes = [
			'eer-row',
			'eer-event'
		];

		if ($event->is_passed) {
			$classes[] = 'passed';
		}

		return implode(' ', $classes);
	}

}

add_action('eer_get_event_columns', ['EER_Subblock_Event_Table', 'get_columns']);
add_filter('eer_get_event_row_classes', ['EER_Subblock_Event_Table', 'get_row_classes']);
add_filter('eer_print_event_data', ['EER_Subblock_Event_Table', 'print_event_data']);