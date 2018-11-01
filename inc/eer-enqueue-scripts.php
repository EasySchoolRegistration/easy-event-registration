<?php

if (!defined('ABSPATH')) {
	exit;
}

class EER_Enqueue_Scripts {

	public static function add_web_scripts() {
		self::enqueue_web_style_scripts();
		self::enqueue_web_js_scripts();
	}


	private static function enqueue_web_style_scripts() {
		wp_enqueue_style('eer_web_style', EER_PLUGIN_URL . 'inc/assets/web/css/eer-web.css', [], EER_VERSION);
		wp_enqueue_style('eer_admin_font_awesome_style', EER_PLUGIN_URL . 'libs/font-awesome/css/font-awesome.css', [], EER_VERSION);
	}


	private static function enqueue_web_js_scripts() {
		wp_enqueue_script('eer_web_script', EER_PLUGIN_URL . 'inc/assets/web/js/eer-web.js', ['jquery'], EER_VERSION);
		wp_enqueue_script('eer_spin_js_script', EER_PLUGIN_URL . 'libs/spin/js/spin.min.js', ['jquery'], EER_VERSION);
		wp_localize_script('eer_web_script', 'eer_ajax_object', ['ajaxurl' => admin_url('admin-ajax.php')]);
	}


	public static function add_admin_scripts() {
		if (self::check_page_base(EER_Template_Event::MENU_SLUG) || self::check_page_base(EER_Template_Ticket::MENU_SLUG)) {
			wp_enqueue_script('eer_admin_events_script', EER_PLUGIN_URL . 'inc/assets/admin/js/eer-production.js', ['jquery', 'wp-color-picker']);
			self::eer_include_admin_scripts();
			self::eer_include_datatable_scripts();
			wp_enqueue_script('tinymce');
			wp_enqueue_style('wp-color-picker');
		} else if (self::check_page_base(EER_Template_Order::MENU_SLUG) || self::check_page_base(EER_Template_Sold_Ticket::MENU_SLUG) || self::check_page_base(EER_Template_Payments::MENU_SLUG) || self::check_page_base(EER_Template_Payment_Emails::MENU_SLUG) || self::check_page_base(EER_Template_Tickets_In_Numbers::MENU_SLUG)) {
			wp_enqueue_script('eer_admin_events_script', EER_PLUGIN_URL . 'inc/assets/admin/js/eer-production.js', ['jquery']);
			self::eer_include_admin_scripts();
			self::eer_include_datatable_scripts();
		}

		if (self::check_page_base(EER_Template_Add_Over_Limit::MENU_SLUG)) {
			wp_enqueue_style('eer_web_style', EER_PLUGIN_URL . 'inc/assets/web/css/eer-web.css', [], EER_VERSION);
			wp_enqueue_script('eer_admin_events_script', EER_PLUGIN_URL . 'inc/assets/admin/js/eer-production.js', ['jquery'], EER_VERSION);
			wp_enqueue_script('eer_spin_js_script', EER_PLUGIN_URL . 'libs/spin/js/spin.min.js', ['jquery'], EER_VERSION);
			self::eer_include_admin_scripts();
		}
	}


	private static function eer_include_admin_scripts() {
		wp_localize_script('eer_admin_events_script', 'eer_ajax_object', ['ajaxurl' => admin_url('admin-ajax.php')]);
		wp_enqueue_style('eer_admin_style', EER_PLUGIN_URL . 'inc/assets/admin/css/eer-admin-settings.css', [], EER_VERSION);
	}


	private static function eer_include_datatable_scripts() {
		wp_enqueue_script('eer_dataTables_script', EER_PLUGIN_URL . 'libs/datatable/js/jquery.dataTables.min.js', ['jquery'], EER_VERSION);
		wp_enqueue_script('eer_dataTables_bootstrap_script', EER_PLUGIN_URL . 'libs/datatable/js/dataTables.bootstrap.min.js', ['jquery'], EER_VERSION);
		wp_enqueue_script('eer_dataTables_button_script', EER_PLUGIN_URL . 'libs/datatable/js/dataTables.buttons.min.js', ['jquery'], EER_VERSION);
		wp_enqueue_script('eer_dataTables_print_script', EER_PLUGIN_URL . 'libs/datatable/js/buttons.print.min.js', ['jquery'], EER_VERSION);
		wp_enqueue_script('eer_dataTables_colvis_script', EER_PLUGIN_URL . 'libs/datatable/js/buttons.colVis.min.js', ['jquery'], EER_VERSION);

		wp_enqueue_style('eer_dataTables_bootstrap_style', EER_PLUGIN_URL . 'libs/datatable/css/dataTables.bootstrap.min.css', [], EER_VERSION);
		wp_enqueue_style('eer_dataTables_min_style', EER_PLUGIN_URL . 'libs/datatable/css/jquery.dataTables.min.css', [], EER_VERSION);
		wp_enqueue_style('eer_dataTables_button_style', EER_PLUGIN_URL . 'libs/datatable/css/buttons.dataTables.min.css', [], EER_VERSION);
		wp_enqueue_style('eer_admin_bootstrap_style', EER_PLUGIN_URL . 'libs/bootstrap/css/bootstrap-ofic.css', [], EER_VERSION);
		wp_enqueue_script('eer_bootstrap_script', EER_PLUGIN_URL . 'libs/bootstrap/js/bootstrap.min.js', ['jquery'], EER_VERSION);

		wp_enqueue_style('eer_admin_font_awesome_style', EER_PLUGIN_URL . 'libs/font-awesome/css/font-awesome.css', [], EER_VERSION);
	}


	private static function check_page_base($base_to_check) {
		return strpos(get_current_screen()->base, $base_to_check) !== false;
	}
}

add_action('wp_enqueue_scripts', ['EER_Enqueue_Scripts', 'add_web_scripts']);
add_action('admin_enqueue_scripts', ['EER_Enqueue_Scripts', 'add_admin_scripts']);