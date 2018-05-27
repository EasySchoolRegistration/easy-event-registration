<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Event_Sale_Tickets_Vienna_Honey_Swing
{

	public function print_content($event_id, $for_sale = true)
	{
		$tickets = EER()->ticket->get_tickets_by_event($event_id);

		if ($tickets) {
			$ticket_width = 100 / count($tickets) - 2;
			?>
			<div class="eer-tickets eer-theme-dark-blue-green eer-clearfix">
				<?php
				foreach ($tickets as $tisket_id => $ticket) {
					$ticket_buy_enabled = EER()->ticket->is_ticket_buy_enabled($tisket_id, $ticket);
					$classes = [
						'eer-ticket'
					];

					if (!$ticket_buy_enabled) {
						$classes[] = 'eer-sold';
					}
					?>
					<div class="<?php echo implode(' ', $classes); ?>" style="width: <?php echo $ticket_width ?>%"
					     data-id="<?php echo $ticket->id; ?>"
					     data-title="<?php echo $ticket->title; ?>"
					     data-price="<?php echo $ticket->price; ?>"
					     data-max="<?php echo $ticket->max_per_order; ?>">
						<div class="eer-ticket-body-wraper">
							<div class="eer-ticket-body">
								<h3 class="eer-ticket-title"><?php echo $ticket->title; ?></h3>
								<div class="eer-ticket-price"><?php echo EER()->currency->eer_prepare_price($event_id, $ticket->price); ?></div>
								<div class="eer-ticket-content"><?php echo nl2br($ticket->content); ?></div>
								<?php if ($for_sale) {
									if ($ticket_buy_enabled) { ?>
										<button class="eer-ticket-add"><i class="ti-plus"></i></button>
									<?php } else { ?>
										<div class="eer-ticket-sold"><?php _e('Sold', 'easy-event-registration'); ?></div>
									<?php }
								} ?>
							</div>
						</div>
					</div>
					<?php
				} ?>
			</div>
			<?php
		}
	}
}

