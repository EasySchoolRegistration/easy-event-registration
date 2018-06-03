<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Order_Confirmation_Email
{

	private $worker_email;

	public function __construct()
	{
		$this->worker_email = new EER_Worker_Email();
	}


	public function send_email($event_id, $tickets)
	{
		$event_data = EER()->event->get_event_data($event_id);

		$subject = stripcslashes(EER()->event->eer_get_event_option($event_data, 'order_confirmation_email_subject', ''));

		foreach ($tickets as $ticket_id => $sold_tickets) {
			foreach ($sold_tickets as $key => $sold_ticket_id) {
				$body = stripcslashes(EER()->event->eer_get_event_option($event_data, 'order_confirmation_email_body', null));

				$sold_ticket = EER()->sold_ticket->eer_get_sold_tickets_data($sold_ticket_id);
				$order = EER()->order->eer_get_order($sold_ticket->order_id);

				$ticket = EER()->ticket->get_ticket_data($ticket_id);
				$user = get_user_by('ID', $order->user_id);

				if (!empty($body)) {
					$tags = EER()->tags->get_tags('order_confirmation_email');

					foreach ($tags as $tag_key => $tag) {
						$parameter = null;
						if (isset($tag['parameter'])) {
							switch ($tag['parameter']) {
								case 'event_title':
									{
										$parameter = $event_data->title;
										break;
									}
								case 'ticket_title' :
									{
										$parameter = $ticket->title;
										break;
									}
								case 'order_code' :
									{
										$parameter = $order->unique_key;
										break;
									}
								case 'price' :
									{
										$parameter = ['price' => $ticket->price, 'event' => $event_data];
										break;
									}
							}

							$body = call_user_func(['EER_Template_Settings_Tag', $tag['function']], $tag, $body, $parameter);
						}
					}

					$this->worker_email->send_email($user->user_email, $subject, $body, $event_data);
				}
			}
		}


		return false;
	}

	public function send_confirmation_email($event_id, $sold_ticket_id)
	{
		$event_data = EER()->event->get_event_data($event_id);

		$subject = stripcslashes(EER()->event->eer_get_event_option($event_data, 'order_confirmation_email_subject', ''));


		$body = stripcslashes(EER()->event->eer_get_event_option($event_data, 'order_confirmation_email_body', null));

		$sold_ticket = EER()->sold_ticket->eer_get_sold_tickets_data($sold_ticket_id);
		$order = EER()->order->eer_get_order($sold_ticket->order_id);

		$ticket = EER()->ticket->get_ticket_data($sold_ticket->ticket_id);
		$user = get_user_by('ID', $order->user_id);

		if (!empty($body)) {
			$tags = EER()->tags->get_tags('order_confirmation_email');

			foreach ($tags as $tag_key => $tag) {
				$parameter = null;
				if (isset($tag['parameter'])) {
					switch ($tag['parameter']) {
						case 'event_title':
							{
								$parameter = $event_data->title;
								break;
							}
						case 'ticket_title' :
							{
								$parameter = $ticket->title;
								break;
							}
						case 'order_code' :
							{
								$parameter = $order->unique_key;
								break;
							}
						case 'price' :
							{
								$parameter = ['price' => $ticket->price, 'event' => $event_data];
								break;
							}
					}

					$body = call_user_func(['EER_Template_Settings_Tag', $tag['function']], $tag, $body, $parameter);
				}
			}

			$this->worker_email->send_email($user->user_email, $subject, $body, $event_data);
		}


		return false;
	}

}
