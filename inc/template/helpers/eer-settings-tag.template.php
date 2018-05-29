<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Settings_Tag
{

	public function print_content($tags)
	{
		$tags_list = '';

		if ($tags) {
			$tags_list .= '<table class="eer-tags-table">';
			foreach ($tags as $tag) {
				if (isset($tag['type']) && ($tag['type'] === 'double')) {
					$tags_list .= '<tr><td>[' . $tag['tag'] . '][/' . $tag['tag'] . ']</td><td>' . $tag['description'] . '</td></tr>';
				} else {
					$tags_list .= '<tr><td>[' . $tag['tag'] . ']</td><td>' . $tag['description'] . '</td></tr>';
				}
			}
			$tags_list .= '</table>';
		}

		return $tags_list;
	}


	public static function eer_tag_replace_string($tag, $body, $replacement)
	{
		return str_replace("[" . $tag['tag'] . "]", $replacement, $body);
	}


	public static function eer_tag_replace_registration_ticket_list($tag, $body, $order_id)
	{
		$replacement = "";
		$tag_code = str_replace('list_', '', $tag['tag']);

		if ($order_id) {
			$replacement .= "<ul>";
			foreach (EER()->sold_ticket->eer_get_sold_tickets_by_order($order_id) as $ticket_id => $sold_ticket) {
				$ticket_data = EER()->ticket->get_ticket_data($sold_ticket->ticket_id);
				$levels = isset($ticket_data->levels) ? $ticket_data->levels : [];
				$replacement .= "<li>" . $ticket_data->title . "<br>
					<ul style='margin-left: 20px'>
						<li>" . __('Level', 'easy-event-registration') . ": " . (($levels) ? $levels[$sold_ticket->level_id]['name'] : '') . "</li>
						<li>" . __('Role', 'easy-event-registration') . ": " . (EER()->dancing_as->eer_get_title($sold_ticket->dancing_as)) . "</li>
						<li>" . __('Partner', 'easy-event-registration') . ": " . (($sold_ticket->dancing_with_name) ? $sold_ticket->dancing_with_name : '') . ' ' . (($sold_ticket->dancing_with) ? $sold_ticket->dancing_with : '') . "</li>
					</ul>
				</li>";
			}
			$replacement .= "</ul>";

			$body = str_replace('[' . $tag_code . '_exists]', '', $body);
			$body = str_replace('[/' . $tag_code . '_exists]', '', $body);
		} else {
			$body = preg_replace('/\[' . $tag_code . '_exists\].*\[\/' . $tag_code . '_exists\]/is', '', $body);
		}

		return str_replace("[" . $tag['tag'] . "]", $replacement, $body);
	}


	public static function eer_tag_replace_order_data($tag, $body, $order)
	{
		$replacement = "";

		if ($order->{$tag['id']}) {
			$replacement = $order->{$tag['id']};
		}

		return str_replace("[" . $tag['tag'] . "]", $replacement, $body);
	}


	public static function eer_tag_replace_order_info($tag, $body, $order_info)
	{
		$replacement = "";

		if ($order_info->{$tag['id']}) {
			$replacement = $order_info->{$tag['id']};
		}

		return str_replace("[" . $tag['tag'] . "]", $replacement, $body);
	}

	public static function eer_tag_replace_tickets_list($tag, $body, $tickets)
	{
		$replacement = "";

		if ($tickets) {
			$replacement .= "<ul>";
			foreach ($tickets as $id => $ticket) {
				$replacement .= "<li>" . $ticket->title . "</li>";
			}
			$replacement .= "</ul>";
		}

		return str_replace("[" . $tag['tag'] . "]", $replacement, $body);
	}

	public static function eer_tag_replace_price($tag, $body, $data)
	{
		return str_replace("[" . $tag['tag'] . "]", EER()->currency->eer_prepare_price($data['event']->id, $data['price'], $data['event']), $body);
	}
}