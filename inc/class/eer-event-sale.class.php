<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Event_Sale
{

	private $templater_event_sale_thank_you;

	private $worker_event_sale;

	private $templater_event_sale;


	public function __construct()
	{
		$this->templater_event_sale_thank_you = new EER_Template_Event_Sale_Thank_You_Page();
		$this->worker_event_sale = new EER_Worker_Event_Sale();
	}


	/**
	 * @param int $event_id - Event id
	 * @param array $data - Array with post data
	 */
	public function event_registration($event_id, $data)
	{
		$show_registration_form = true;
		$return_data = [];

		if (isset($data['eer-event-registration-submitted'])) {
			//$return_data = $this->worker_event_sale->process_registration($data);

			//$show_registration_form = empty($return_data);
		}
		if ($show_registration_form) {

		} else {
			echo $this->templater_event_sale_thank_you->print_content($event_id, $return_data);
		}
	}

}
