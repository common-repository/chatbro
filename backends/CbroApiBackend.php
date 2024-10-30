<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Backends;

defined('CHATBROENGINE') || die();
defined('ABSPATH') or die('No script kiddies please!');

use Chatbroapp\Common\Common\CbroCommon;

class CbroApiBackend
{
	const platform_id = 1000;
	const platform_name = 'wordpress';
	const api_url = 'https://www.chatbro.com/api/v1/';

	public function __construct()
	{
	}

	function is_error($response)
	{
		return CbroCommon::is_error($response);
	}

	function remote_post($uri, $args)
	{
		return CbroCommon::remote_post($uri, $args);
	}

	function remote_retrieve_response_code($response)
	{
		return CbroCommon::remote_retrieve_response_code($response);
	}

	function remote_retrieve_body($response)
	{
		return CbroCommon::remote_retrieve_body($response);
	}

	function delete_access_token()
	{
		return CbroCommon::delete_option('chatbro_access_token');
	}

	function delete_refresh_token()
	{
		return CbroCommon::delete_option('chatbro_refresh_token');
	}

	function set_access_token($access_token)
	{
		return CbroCommon::set_access_token($access_token);
	}

	function set_refresh_token($refresh_token)
	{
		return CbroCommon::set_refresh_token($refresh_token);
	}

	function get_access_token()
	{
		return CbroCommon::get_option('chatbro_access_token');
	}

	function get_refresh_token()
	{
		return CbroCommon::get_option('chatbro_refresh_token');
	}
}