<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Payment_Email
{

	private $worker_email;

	private $worker_payment_email;

	public function __construct()
	{
		$this->worker_email = new EER_Worker_Email();
		$this->worker_payment_email = new EER_Worker_Payment_Email();
	}


	public function send_email($payment_id, $event_data)
	{
		$payment = EER()->payment->eer_get_payment($payment_id);
		$order = EER()->order->eer_get_order($payment->order_id);
		$order_info = json_decode($order->order_info);

		$user = get_user_by('ID', $order->user_id);

		$subject = stripcslashes(EER()->event->eer_get_event_option($event_data, 'payment_reminder_email_subject', ''));
		$body = stripcslashes(EER()->event->eer_get_event_option($event_data, 'payment_reminder_email_body', null));

		if (!empty($body)) {
			$tags = EER()->tags->get_tags('payment_reminder_email');

			foreach ($tags as $key => $tag) {
				$parameter = null;
				if (isset($tag['parameter'])) {
					switch ($tag['parameter']) {
						case 'event_title':
							{
								$parameter = $event_data->title;
								break;
							}
						case 'tickets_list' :
							{
								$parameter = EER()->sold_ticket->eer_get_confirmed_sold_tickets_by_order($order->id);
								break;
							}
						case 'to_pay' :
							{
								$parameter = ['event' => $event_data, 'price' => $payment->to_pay];
								break;
							}
						case 'order_code' :
							{
								$parameter = $order->unique_key;
								break;
							}
					}

					$body = call_user_func(['EER_Template_Settings_Tag', $tag['function']], $tag, $body, $parameter);
				}
			}

			$status = $this->worker_email->send_email($user->user_email, $subject, $body, $event_data);

			if ($status) {
				$this->worker_payment_email->eer_update_payment_email_timestamp($payment_id);
			}

			return $status;
		}

		return false;
	}

}
