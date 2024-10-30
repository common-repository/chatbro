<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Interfaces;

defined('CHATBROENGINE') || die();

interface IcbroAdminBackend
{
	function has_permission_editor();
	function has_shortcodes();
	function get_login_url();
	function additional_fields($type);
	function render_permissions();
	function check_token($type);
	function get_chat_help_tips();
	function get_settings_help_tips();
	function save_permissions();
}
