<?php
/**
 * Class SampleTest
 *
 * @package ESR
 */

/**
 * Sample test case.
 */
class EER_Base_Test {

	public function __construct() {
	}


	public function setUp() {
		global $wpdb;

		$u = wp_get_current_user();
		$u->add_role('administrator');
	}


	public function delete_all_data() {
		global $wpdb;
		global $esr_settings;

		$esr_settings = [];
		delete_option('esr_settings');

		$wpdb->query("DELETE FROM {$wpdb->prefix}eer_ticket_summary");
		$wpdb->query("DELETE FROM {$wpdb->prefix}eer_sold_tickets");
		$wpdb->query("DELETE FROM {$wpdb->prefix}eer_events_payments");
		$wpdb->query("DELETE FROM {$wpdb->prefix}eer_tickets");
		$wpdb->query("DELETE FROM {$wpdb->prefix}eer_events");

		$wpdb->query("DELETE FROM {$wpdb->usermeta}");
		$wpdb->query("DELETE FROM {$wpdb->users}");

		wp_cache_flush();
	}
}
