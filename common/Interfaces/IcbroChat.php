<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Interfaces;

defined('CHATBROENGINE') || die();

interface IcbroChat
{
	function get_id();
	function get_title();
	function get_is_main_chat();
	function get_guid();
	function get_display();
	function get_selected_pages();
	function get_display_to_guests();
}
