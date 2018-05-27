<?php

// Exit if accessed directly.
if (!defined('ABSPATH')) {
	exit;
}

class EER_Template_Settings_Helper
{

	public static function eer_text_callback($args)
	{
		$eer_settings = EER()->settings->eer_get_option($args['id']);

		if ($eer_settings) {
			$value = $eer_settings;
		} elseif (!empty($args['allow_blank']) && empty($eer_settings)) {
			$value = '';
		} else {
			$value = isset($args['std']) ? $args['std'] : '';
		}

		$name = 'name="eer_settings[' . esc_attr($args['id']) . ']"';

		$class = self::eer_sanitize_html_class($args['field_class']);

		$html = '<input type="text" class="' . $class . ' ' . 'regular-text" id="eer_settings[' . self::eer_sanitize_key($args['id']) . ']" ' . $name . ' value="' . esc_attr(stripslashes($value)) . '"/>';
		$html .= '<label for="eer_settings[' . self::eer_sanitize_key($args['id']) . ']"> ' . wp_kses_post($args['desc']) . '</label>';

		echo apply_filters('eer_after_setting_output', $html, $args);
	}


	public static function eer_license_key_callback($args)
	{
		$eer_settings = EER()->settings->eer_get_option($args['id']);

		$messages = [];
		$license = get_option($args['options']['is_valid_license_option']);

		if ($eer_settings) {
			$value = $eer_settings;
		} else {
			$value = isset($args['std']) ? $args['std'] : '';
		}

		if (!empty($license) && is_object($license)) {

			// activate_license 'invalid' on anything other than valid, so if there was an error capture it
			if (false === $license->success) {

				switch ($license->error) {

					case 'expired' :

						$class = 'expired';
						$messages[] = sprintf(__('Your license key expired on %s. Please <a href="%s" target="_blank">contact us</a> about renew your license key.', 'easy-event-registration'), date_i18n(get_option('date_format'), strtotime($license->expires, current_time('timestamp'))), 'https://easyschoolregistration.com/contact/');

						$license_status = 'license-' . $class . '-notice';

						break;

					case 'revoked' :

						$class = 'error';
						$messages[] = sprintf(__('Your license key has been disabled. Please <a href="%s" target="_blank">contact support</a> for more information.', 'easy-event-registration'), 'https://easyschoolregistration.com/contact/');

						$license_status = 'license-' . $class . '-notice';

						break;

					case 'missing' :

						$class = 'error';
						$messages[] = sprintf(__('Invalid license. Please <a href="%s" target="_blank">visit your account page</a> and verify it.', 'easy-event-registration'), 'https://easyschoolregistration.com/your-account');

						$license_status = 'license-' . $class . '-notice';

						break;

					case 'invalid' :
					case 'site_inactive' :

						$class = 'error';
						$messages[] = sprintf(__('Your %s is not active for this URL. Please <a href="%s" target="_blank">visit your account page</a> to manage your license key URLs.', 'easy-event-registration'), $args['name'], 'https://easyschoolregistration.com/your-account/');

						$license_status = 'license-' . $class . '-notice';

						break;

					case 'item_name_mismatch' :

						$class = 'error';
						$messages[] = sprintf(__('This appears to be an invalid license key for %s.', 'easy-event-registration'), $args['name']);

						$license_status = 'license-' . $class . '-notice';

						break;

					case 'no_activations_left':

						$class = 'error';
						$messages[] = sprintf(__('Your license key has reached its activation limit. <a href="%s">View possible upgrades</a> now.', 'easy-event-registration'), 'https://easyschoolregistration.com/your-account/');

						$license_status = 'license-' . $class . '-notice';

						break;

					case 'license_not_activable':

						$class = 'error';
						$messages[] = __('The key you entered belongs to a bundle, please use the product specific license key.', 'easy-event-registration');

						$license_status = 'license-' . $class . '-notice';
						break;

					default :

						$class = 'error';
						$error = !empty($license->error) ? $license->error : __('unknown_error', 'easy-event-registration');
						$messages[] = sprintf(__('There was an error with this license key: %s. Please <a href="%s">contact our support team</a>.', 'easy-event-registration'), $error, 'https://easyschoolregistration.com/contact/');

						$license_status = 'license-' . $class . '-notice';
						break;
				}

			} else {

				switch ($license->license) {

					case 'valid' :
					default:

						$class = 'valid';

						$now = current_time('timestamp');
						$expiration = strtotime($license->expires, current_time('timestamp'));

						if ('lifetime' === $license->expires) {

							$messages[] = __('License key never expires.', 'easy-event-registration');

							$license_status = 'license-lifetime-notice';

						} elseif ($expiration > $now && $expiration - $now < (DAY_IN_SECONDS * 30)) {

							$messages[] = sprintf(__('Your license key expires soon! It expires on %s. Please <a href="%s" target="_blank">contact us</a> about renew your license key.', 'easy-event-registration'), date_i18n(get_option('date_format'), strtotime($license->expires, current_time('timestamp'))), 'https://easyschoolregistration.com/contact/');

							$license_status = 'license-expires-soon-notice';

						} else {

							$messages[] = sprintf(__('Your license key expires on %s.', 'easy-event-registration'), date_i18n(get_option('date_format'), strtotime($license->expires, current_time('timestamp'))));

							$license_status = 'license-expiration-date-notice';

						}

						break;

				}

			}

		} else {
			$class = 'empty';

			$messages[] = sprintf(__('To receive updates, please enter your valid %s license key.', 'easy-event-registration'), $args['name']);

			$license_status = null;
		}

		$class .= ' ' . self::eer_sanitize_html_class($args['field_class']);

		$size = (isset($args['size']) && !is_null($args['size'])) ? $args['size'] : 'regular';
		$html = '<input type="text" class="' . sanitize_html_class($size) . '-text" id="eer_settings[' . self::eer_sanitize_key($args['id']) . ']" name="eer_settings[' . self::eer_sanitize_key($args['id']) . ']" value="' . esc_attr($value) . '"/>';

		if ((is_object($license) && 'valid' == $license->license) || 'valid' == $license) {
			$html .= '<input type="submit" class="button-secondary" name="' . $args['id'] . '_deactivate" value="' . __('Deactivate License', 'easy-event-registration') . '"/>';
		}

		$html .= '<label for="eer_settings[' . self::eer_sanitize_key($args['id']) . ']"> ' . wp_kses_post($args['desc']) . '</label>';

		if (!empty($messages)) {
			foreach ($messages as $message) {

				$html .= '<div class="eer-license-data eer-license-' . $class . ' ' . $license_status . '">';
				$html .= '<p>' . $message . '</p>';
				$html .= '</div>';

			}
		}

		wp_nonce_field(self::eer_sanitize_key($args['id']) . '-nonce', self::eer_sanitize_key($args['id']) . '-nonce');

		echo $html;
	}


	public static function eer_submit_callback($args)
	{
		$html = '';
		$status = get_option('eer_license_status');
		$license_key = EER()->settings->eer_get_option('license_key');

		if ($license_key) {
			if ($status !== false && $status == 'valid') {
				$html = __('Your license is activated.', 'easy-event-registration');
			} else {
				$html = wp_nonce_field('eer_nonce', 'eer_nonce');
				$html .= '<label for="' . self::eer_sanitize_key($args['id']) . '"><input type="submit" class="button-secondary" name="' . self::eer_sanitize_key($args['id']) . '" value="' . $args['name'] . '"/></label>';
			}
		} else {
			$html = __('Please save the license key before activation.', 'easy-event-registration');
		}

		echo apply_filters('eer_after_setting_output', $html, $args);
	}


	public static function eer_sanitize_html_class($class = '')
	{

		if (is_string($class)) {
			$class = sanitize_html_class($class);
		} else if (is_array($class)) {
			$class = array_values(array_map('sanitize_html_class', $class));
			$class = implode(' ', array_unique($class));
		}

		return $class;

	}


	public static function eer_sanitize_key($key)
	{
		$raw_key = $key;
		$key = preg_replace('/[^a-zA-Z0-9_\-\.\:\/]/', '', $key);

		return apply_filters('eer_sanitize_key', $key, $raw_key);
	}

}
