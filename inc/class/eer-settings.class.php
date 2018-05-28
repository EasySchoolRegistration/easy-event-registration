<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Settings
{

	/**
	 * Retrieve the array of plugin settings
	 *
	 * @since 3
	 * @return array
	 */
	public function eer_get_registered_settings()
	{
		$eer_settings = [
			/** General Settings */
			'general' => apply_filters('eer_settings_general', [
				'main' => [
					'license_key' => [
						'id' => 'license_key',
						'name' => __('License key', 'easy-event-registration'),
						'type' => 'text',
						'options' => 'small',
					],
					'eer_license_activate' => [
						'id' => 'eer_license_activate',
						'name' => __('Activate License', 'easy-event-registration'),
						'type' => 'submit',
					],
				],
			]),
			'licenses' => apply_filters('eer_settings_licenses', []),
			'extensions' => apply_filters('eer_settings_extensions', []),
		];

		return apply_filters('eer_registered_settings', $eer_settings);
	}


	public function eer_get_registered_settings_sections()
	{

		static $sections = false;

		if (false !== $sections) {
			return $sections;
		}

		$sections = [
			'general' => apply_filters('eer_settings_sections_general', [
				'main' => __('General', 'easy-event-registration'),
			]),
			'licenses' => apply_filters('eer_settings_sections_licenses', []),
			'extensions' => apply_filters('eer_settings_sections_extensions', []),
		];

		$sections = apply_filters('eer_settings_sections', $sections);

		return $sections;
	}


	public function eer_get_settings_tabs()
	{

		$settings = $this->eer_get_registered_settings();

		$tabs = [];
		$tabs['general'] = __('General', 'easy-event-registration');

		if (!empty($settings['extensions'])) {
			$tabs['extensions'] = __('Extensions', 'easy-event-registration');
		}
		if (!empty($settings['licenses'])) {
			$tabs['licenses'] = __('Licenses', 'easy-event-registration');
		}

		return apply_filters('eer_settings_tabs', $tabs);
	}


	public function eer_get_settings_tab_sections($tab = false)
	{

		$tabs = false;
		$sections = $this->eer_get_registered_settings_sections();

		if ($tab && !empty($sections[$tab])) {
			$tabs = $sections[$tab];
		} else if ($tab) {
			$tabs = false;
		}

		return $tabs;
	}


	public static function eer_register_settings()
	{
		if (false == get_option('eer_settings')) {
			add_option('eer_settings');
		}

		foreach (EER()->settings->eer_get_registered_settings() as $tab => $sections) {
			foreach ($sections as $section => $settings) {

				// Check for backwards compatibility
				$section_tabs = EER()->settings->eer_get_settings_tab_sections($tab);
				if (!is_array($section_tabs) || !array_key_exists($section, $section_tabs)) {
					$section = 'main';
					$settings = $sections;
				}

				add_settings_section('eer_settings_' . $tab . '_' . $section, null, '__return_false', 'eer_settings_' . $tab . '_' . $section);

				foreach ($settings as $option) {
					// For backwards compatibility
					if (empty($option['id'])) {
						continue;
					}

					$args = wp_parse_args($option, [
						'section' => $section,
						'id' => null,
						'desc' => '',
						'name' => '',
						'size' => null,
						'options' => '',
						'std' => '',
						'min' => null,
						'max' => null,
						'step' => null,
						'chosen' => null,
						'multiple' => null,
						'placeholder' => null,
						'allow_blank' => true,
						'readonly' => false,
						'faux' => false,
						'tooltip_title' => false,
						'tooltip_desc' => false,
						'field_class' => '',
						'prefix' => 'eer_',
						'template' => 'EER_Template_Settings_Helper'
					]);

					$callback = $args['prefix'] . $args['type'] . '_callback';
					add_settings_field('eer_settings[' . $args['id'] . ']', $args['name'], method_exists($args['template'], $callback) ? [$args['template'], $callback] : '', 'eer_settings_' . $tab . '_' . $section, 'eer_settings_' . $tab . '_' . $section, $args);
				}
			}
		}

		// Creates our settings in the options table
		register_setting('eer_settings', 'eer_settings', ['EER_Settings', 'eer_settings_sanitize']);
	}


	public function eer_get_option($key = '', $default = false)
	{
		global $eer_settings;
		$value = !empty($eer_settings[$key]) ? $eer_settings[$key] : $default;
		$value = apply_filters('eer_get_option', $value, $key, $default);

		return apply_filters('eer_get_option_' . $key, $value, $key, $default);
	}


	public function eer_get_settings()
	{

		$settings = get_option('eer_settings');

		if (empty($settings)) {
			update_option('eer_settings', []);
		}

		return apply_filters('eer_get_settings', $settings);
	}


	public static function eer_settings_sanitize($input = [])
	{
		global $eer_settings;

		$input = $input ? $input : [];

		// Merge our new settings with the existing
		$output = array_merge($eer_settings, $input);

		return $output;
	}
}

add_action('admin_init', ['EER_Settings', 'eer_register_settings']);