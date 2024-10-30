<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Common;

defined('CHATBROENGINE') || die();
defined('ABSPATH') or die('No script kiddies please!');

use Chatbroapp\Common\Core\CbroBackendable;
use Chatbroapp\Common\Utils\CbroUtils;
use Chatbroapp\Common\Exceptions\CbroStoreNotFound;

class CbroCommon extends CbroBackendable
{
	private static $tableName = 'chatbro';

	protected function __construct($backend)
	{
		parent::__construct($backend);
	}

	public static function get_store_id()
	{
	}

	public static function remote_post($uri, $args)
	{
		$curl_options = [];

		if (isset($args['headers'])) {
			$newHeaders = array_map(
				function ($key, $value) {
					return $key . ': ' . $value;
				},
				array_keys($args['headers']),
				$args['headers']
			);

			// Обновление исходного массива
			$args['headers'] = $newHeaders;

			$curl_options[CURLOPT_HTTPHEADER] = $args['headers'];
		}

		if (isset($args['body'])) {
			$curl_options[CURLOPT_POST] = true;
			$curl_options[CURLOPT_POSTFIELDS] = $args['body'];
		}

		$curl_options[CURLOPT_URL] = $uri;
		$curl_options[CURLOPT_RETURNTRANSFER] = true;
		$curl_options[CURLOPT_SSL_VERIFYHOST] = false;
		$curl_options[CURLOPT_SSL_VERIFYPEER] = false;

		$ch = curl_init();
		curl_setopt_array($ch, $curl_options);
		$result = curl_exec($ch);
		$info = curl_getinfo($ch);
		curl_close($ch);

		return [
			'result' => $result,
			'info' => $info,
		];
	}

	public static function remote_retrieve_response_code($response)
	{
		return isset($response['info']['http_code'])
			? (int) $response['info']['http_code']
			: 500;
	}

	public static function remote_retrieve_body($response)
	{
		return isset($response['result']) ? $response['result'] : null;
	}

	public static function is_error($response)
	{
		return (int) $response['info']['http_code'] >= 404;
	}

	public static function delete_access_token()
	{
		self::delete_option('chatbro_access_token');
	}

	public static function delete_refresh_token()
	{
		self::delete_option('chatbro_refresh_token');
	}

	public static function set_access_token($access_token)
	{
		return self::update_option('chatbro_access_token', $access_token);
	}

	public static function set_refresh_token($refresh_token)
	{
		return self::update_option('chatbro_refresh_token', $refresh_token);
	}

	public static function get_access_token()
	{
		return self::get_option('chatbro_access_token');
	}

	public static function get_refresh_token()
	{
		return self::get_option('chatbro_refresh_token');
	}

	private static function getDb()
	{
	}

	public static function get_store($store_id)
	{
	}

	public static function reg_store()
	{
	}

	public static function add_option($name, $value = '', $v2 = '', $v3 = 'yes')
	{
		if (is_multisite() && is_plugin_active_for_network(plugin_basename(__FILE__))) {
			return add_site_option($name, $value, $v2, $v3);
		} else {
			return add_option($name, $value, $v2, $v3);
		}
	}

	public static function update_option($name, $value)
	{
		if (is_multisite() && is_plugin_active_for_network(plugin_basename(__FILE__))) {
			return update_site_option($name, $value);
		} else {
			return update_option($name, $value);
		}
	}

	public static function get_option($name, $defaultValue = null)
	{
		if (is_multisite() && is_plugin_active_for_network(plugin_basename(__FILE__))) {
			return get_site_option($name, $defaultValue);
		} else {
			return get_option($name, $defaultValue);
		}
	}

	public static function delete_option($name)
	{
		if (is_multisite() && is_plugin_active_for_network(plugin_basename(__FILE__))) {
			return delete_site_option($name);
		} else {
			return delete_option($name);
		}
	}

	public static function delete_store()
	{
	}

	public static function generated_scripts()
	{
	}
}
