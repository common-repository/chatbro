<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Interfaces;

defined('CHATBROENGINE') || die();

interface IcbroUtilsBackend
{
	function get_site_url();
	function get_site_domain();
	function get_platform();
	function is_front_page();
	function enque_script($file);
	function enque_style($file);
	function get_locale();
	function get_request_var($var_name);
	function get_home_url();
	function get_default_profile_url();
	function get_support_chat_data_offset_top();
}
