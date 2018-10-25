<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Event_Sale_Tickets
{

	public function print_content($event_id, $for_sale = true, $always_enabled = false)
	{
		$tickets = EER()->ticket->get_tickets_by_event($event_id);

		if ($tickets) {
			$ticket_width = 100 / count($tickets) - 2;
			?>
			<div class="eer-tickets eer-clearfix">
				<?php
				foreach ($tickets as $ticket_id => $ticket) {
					$ticket_buy_enabled = $always_enabled ? true : EER()->ticket->is_ticket_buy_enabled($ticket->id, $ticket);

					$classes = [
						'eer-ticket'
					];
					$levels = NULL;

					if (!$ticket_buy_enabled) {
						$classes[] = 'eer-sold';
					}

					if ($ticket->has_levels) {
						$classes[] = 'eer-has-levels';
						$levels = EER()->ticket_summary->eer_get_ticket_availability_by_levels($ticket->id);
						foreach ($levels as $level_id => $level) {
							$level->name = $ticket->levels[$level_id]['name'];
						}
					}
					?>
					<div class="<?php echo implode(' ', $classes); ?>" style="width: <?php echo $ticket_width ?>%"
					     data-id="<?php echo $ticket->id; ?>"
					     data-title="<?php echo $ticket->title; ?>"
					     data-price="<?php echo $ticket->price; ?>"
					     data-solo="<?php echo $ticket->is_solo; ?>"
					     data-max="<?php echo $ticket->max_per_order; ?>"
					     data-sold_separately="<?php echo $ticket->sold_separately; ?>"
					     data-levels="<?php echo htmlspecialchars(json_encode($levels)); ?>"
						<?php if (!$ticket->is_solo) { ?>
							data-leader-enabled="<?php echo EER()->dancing_as->eer_is_leader_registration_enabled($ticket->id); ?>"
							data-follower-enabled="<?php echo EER()->dancing_as->eer_is_followers_registration_enabled($ticket->id); ?>"
						<?php } ?>>
						<div class="eer-ticket-body-wraper">
							<div class="eer-ticket-body">
								<h3 class="eer-ticket-title"><?php echo $ticket->title; ?></h3>
								<div class="eer-ticket-price"><?php echo EER()->currency->eer_prepare_price($event_id, $ticket->price); ?></div>
								<div class="eer-ticket-content"><?php echo nl2br(stripslashes($ticket->content)); ?></div>
								<?php if ($for_sale) {
									if ($ticket_buy_enabled) { ?>
										<button class="eer-ticket-add"><i class="ti-plus"></i></button>
									<?php } else { ?>
										<div class="eer-ticket-sold"><?php _e('Sold Out', 'easy-event-registration'); ?></div>
									<?php }
								} ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>
			<?php
		}
	}
}

