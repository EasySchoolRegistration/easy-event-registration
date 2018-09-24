<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Add_Over_Limit
{

	const MENU_SLUG = 'eer_admin_sub_page_add_over_limit';


	public static function print_content()
	{
		$data = $_POST;
		$template_all_events = new EER_Template_All_Events_Select();

		$selected_event = $template_all_events->get_selected_event();

		?>
		<div class="wrap">
		<?php
		$template_all_events->print_content($selected_event);
		?>

		</div><?php
	}
}
