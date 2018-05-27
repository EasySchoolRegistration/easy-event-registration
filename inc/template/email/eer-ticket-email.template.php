<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Ticket_Email {

	private $worker_email;

	public function __construct() {
		$this->worker_email = new EER_Worker_Email();
	}


	public function send_email($order_id) {
		$order        = EER()->order->eer_get_order($order_id);
		$sold_tickets = EER()->sold_ticket->eer_get_confirmed_sold_tickets_by_order($order_id);

		$event_data = EER()->event->get_event_data($order->event_id);

		$user = get_user_by('ID', $order->user_id);

		$subject = stripcslashes(EER()->event->eer_get_event_option($event_data, 'tickets_email_subject', ''));
		$body    = stripcslashes(EER()->event->eer_get_event_option($event_data, 'tickets_email_body', null));

		if (!empty($body)) {
			$tags = EER()->tags->get_tags('email_tickets');

			foreach ($tags as $key => $tag) {
				$parameter = null;
				if (isset($tag['parameter'])) {
					switch ($tag['parameter']) {
						case 'event_title':
							{
								$parameter = $event_data->title;
								break;
							}
					}

					$body = call_user_func(['EER_Template_Settings_Tag', $tag['function']], $tag, $body, $parameter);
				} else {
					$body = call_user_func(['EER_Template_Settings_Tag', $tag['function']], $tag, $body);
				}
			}

			$attachments = [];
			foreach ($sold_tickets as $key => $sold_ticket) {
				$attachments[] = EER()->pdf_ticket->generate_pdf($sold_ticket->ticket_id, $sold_ticket->unique_key);
			}

			$status = $this->worker_email->send_ticket_email($user->user_email, $subject, $body, $event_data, $attachments);

			foreach ($attachments as $file_key => $file_path) {
				unlink($file_path);
			}

			return $status;
		}

		return -1;
	}

}
