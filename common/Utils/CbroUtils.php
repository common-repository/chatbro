<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Utils;

defined('CHATBROENGINE') || die();

use Chatbroapp\Common\Core\CbroBackendable;
use Chatbroapp\Common\Exceptions\CbroSanitizeError;

use Exception;

class CbroUtils extends CbroBackendable
{

	const a = 'dddd';
	public static function gen_guid()
	{
		$instance = self::get_instance();
		$guid = $instance->_gen_uuid();
		return $guid;
	}

	public static function gen_secret()
	{
		$randomBytes = random_bytes(78);
		return base64_encode(
			rtrim(strtr(base64_encode($randomBytes), '+/', '-_'), '=')
		);
	}

	public static function check_path($display, $selected_pages)
	{
		return self::get_backend()->check_path($display, $selected_pages);
	}

	static function match_path($path, $patterns)
	{
		return self::get_backend()->match_path($path, $patterns);
	}

	private function _gen_uuid()
	{
		return strtolower(
			sprintf(
				'%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
				// 32 bits for "time_low"
				mt_rand(0, 0xffff),
				mt_rand(0, 0xffff),

				// 16 bits for "time_mid"
				mt_rand(0, 0xffff),

				// 16 bits for "time_hi_and_version",
				// four most significant bits holds version number 4
				mt_rand(0, 0x0fff) | 0x4000,

				// 16 bits, 8 bits for "clk_seq_hi_res",
				// 8 bits for "clk_seq_low",
				// two most significant bits holds zero and one for variant DCE1.1
				mt_rand(0, 0x3fff) | 0x8000,

				// 48 bits for "node"
				mt_rand(0, 0xffff),
				mt_rand(0, 0xffff),
				mt_rand(0, 0xffff)
			)
		);
	}

	public static function sanitize_guid($guid)
	{
		$guid = trim(strtolower($guid));

		if (
			!preg_match(
				'/^[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$/',
				$guid
			)
		) {
			throw new CbroSanitizeError(
				__('Invalid chat Account ID', 'chatbro'),
				CbroSanitizeError::Error
			);
		}

		return $guid;
	}

	public static function sanitize_secret($secret)
	{
		$secret = trim($secret);

		try {
			base64_decode($secret);
		} catch (Exception $e) {
			throw new CbroSanitizeError(
				__('Invalid account secret', 'chatbro'),
				CbroSanitizeError::Error
			);
		}

		return $secret;
	}

	public static function merge_attributes($attributes, $defaults)
	{
		$filtered_attributes = array_intersect_key($attributes, $defaults);
		return array_merge($defaults, $filtered_attributes);
	}

	public static function get_site_url()
	{
		return self::get_backend()->get_site_url();
	}

	public static function get_site_domain()
	{
		return self::get_backend()->get_site_domain();
	}

	public static function get_platform()
	{
		return self::get_backend()->get_platform();
	}

	public static function is_front_page()
	{
		return self::get_backend()->is_front_page();
	}

	public static function translate($text)
	{
		return self::get_backend()->translate($text);
	}

	public static function enque_script($file)
	{
		return self::get_backend()->enque_script($file);
	}

	public static function enque_style($file)
	{
		return self::get_backend()->enque_style($file);
	}

	public static function get_locale()
	{
		return self::get_backend()->get_locale();
	}

	public static function get_request_var($var_name)
	{
		return self::get_backend()->get_request_var($var_name);
	}

	public static function sanitize_checkbox($val)
	{
		return (int) ($val == 'on');
	}

	public static function get_home_url()
	{
		return self::get_backend()->get_home_url();
	}

	public static function get_default_profile_url()
	{
		return self::get_backend()->get_default_profile_url();
	}

	public static function get_support_chat_data_offset_top()
	{
		return self::get_backend()->get_support_chat_data_offset_top();
	}

	public static function generated_scripts()
	{
		return self::get_backend()->generated_scripts();
	}
}
