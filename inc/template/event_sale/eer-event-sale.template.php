<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Event_Sale
{

	public function print_content($event_id)
	{

		ob_start();

		// check event sale started
		if (EER()->event->is_event_sale_active($event_id)) {
			$templater_tickets = new EER_Template_Event_Sale_Tickets();
			$templater_user_form = new EER_Template_Event_Sale_User_Form();

			echo '<div class="eer-tickets-sale-wrapper">';
			$templater_tickets->print_content($event_id);
			$templater_user_form->print_content($event_id);
			echo '</div>';
		} else {
			$templater_not_opened = new EER_Template_Event_Sale_Not_Opened();
			$templater_not_opened->print_content($event_id);
		}

		echo ob_get_clean();
	}
}

