<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Backends;

defined('CHATBROENGINE') || die();
defined('ABSPATH') or die('No script kiddies please!');

use Chatbroapp\Common\Common\CbroCommon;
use Chatbroapp\Common\Chats\CbroNonExistentChats;
use Chatbroapp\Common\Exceptions\CbroChatsNotFound;
use Chatbroapp\Common\Interfaces\IcbroChatsBackend;

class CbroChatsBackend implements IcbroChatsBackend
{
	public $non_existent_chats;
	const chat_list = 'chatbro_chat_list';

	public function __construct()
	{
		$this->non_existent_chats = new CbroNonExistentChats();
	}

	function update_option($name, $value)
	{
		CbroCommon::update_option($name, $value);
	}

	function get()
	{
		$val = CbroCommon::get_option(
			self::chat_list,
			$this->non_existent_chats
		);

		if ($val === $this->non_existent_chats) {
			throw new CbroChatsNotFound(self::chat_list);
		}

		return $val;
	}

	function set($value)
	{
		self::update_option(self::chat_list, $value);
	}

	function postpone_write()
	{
	}

	function flush()
	{
	}

	function del()
	{
		CbroCommon::delete_option(self::chat_list);
	}
}
