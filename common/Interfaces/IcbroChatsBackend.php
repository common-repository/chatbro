<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Interfaces;

defined('CHATBROENGINE') || die();

interface IcbroChatsBackend
{
	function get();
	function set($value);
	function del();
	function postpone_write();
	function flush();
}
