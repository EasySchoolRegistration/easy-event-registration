<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Easy_Event
{

	public static function print_page()
	{
		?>
		<div class="wrap eer-settings">
			<h1 class="wp-heading-inline"><?php _e('Easy Event Registration', 'easy-school-registration'); ?></h1>
		</div>
		<?php

	}
}
