<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_All_Events_Select
{

	public function print_content($selected_event = null)
	{
		$events = EER()->event->load_events_without_data();

		if (!$selected_event) {
			$selected_event = $this->get_selected_event($events);
		}

		?>
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post" class="eer-select-event">
			<span><?php echo __('Select event', 'easy-event-registration') . ': '; ?></span>
			<select name="eer_event">
				<?php
				foreach ($events as $key => $event) {
					?>
					<option
					value="<?php echo $event->id ?>" <?php if ($event->id == $selected_event) { ?>selected="selected"<?php } ?>><?php echo $event->title; ?></option><?php
				}
				?>
			</select>
			<input type="submit" name="eer_choose_event_submit" class="page-title-action" value="<?php _e('Select', 'easy-event-registration'); ?>">
		</form>
		<?php
	}


	/**
	 * @param array $events
	 *
	 * @return int
	 */
	public function get_selected_event($events = [])
	{
		if (isset($_POST['eer_choose_event_submit']) && isset($_POST['eer_event'])) {
			return $_POST['eer_event'];
		} else if ($events) {
			return reset($events);
		} else {
			$events = EER()->event->load_events_without_data();
			$event = reset($events);
			if ($event) {
				return $event->id;
			}
		}

		return null;
	}
}