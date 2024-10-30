<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

 namespace Chatbroapp\Common\Chats;

defined('CHATBROENGINE') || die();

use Chatbroapp\Common\Core\CbroBackendable;
use Chatbroapp\Common\Settings\CbroSettings;
use Chatbroapp\Common\Exceptions\CbroChatNotFound;
use Chatbroapp\Common\Exceptions\CbroChatsNotFound;
use Chatbroapp\Common\Chats\CbroChat;
use Chatbroapp\Common\Chats\CbroChatsIterator;

use DateTimeImmutable;

class CbroChats extends CbroBackendable
{
	private $chats;
	static $main_chat_id = 1;
	static $main_chat_title = 'Trollbox';

	static $default_display_to_guests = true;
	static $default_display = 'everywhere';
	static $default_selected_pages = false;

	protected function __construct($backend)
	{
		parent::__construct($backend);
		$this->chats = [];
	}

	public static function init_chats()
	{
		try {
			$list = json_decode(self::get_backend()->get(), true);

			// Sort by created_at
			usort($list, function ($chat1, $chat2) {
				return (int) ($chat1[CbroChat::created_at] <
					$chat2[CbroChat::created_at]);
			});

			foreach ($list as $key => $chat) {
				if ($chat[CbroChat::is_main_chat] && $chat[CbroChat::id]) {
					self::$main_chat_id = $chat[CbroChat::id];
				}

				self::init_chat(
					new CbroChat(
						$chat[CbroChat::id],
						$chat[CbroChat::title],
						$chat[CbroChat::is_main_chat],
						$chat[CbroChat::guid],
						$chat[CbroChat::created_at],
						$chat[CbroChat::display],
						$chat[CbroChat::selected_pages],
						$chat[CbroChat::display_to_guests]
					)
				);
			}
		} catch (CbroChatsNotFound $ex) {
			$chat = self::create_default_chat();
			self::init_chat($chat);
			self::save();
		}
	}

	public static function create_default_chat()
	{
		$date = new DateTimeImmutable();
		$milli = (int) $date->format('Uv');

		return new CbroChat(
			self::$main_chat_id,
			self::$main_chat_title,
			true,
			CbroSettings::get(CbroSettings::guid),
			$milli,
			self::$default_display,
			self::$default_selected_pages,
			self::$default_display_to_guests
		);
	}

	public static function init_chat($chat)
	{
		self::get_instance()->chats[$chat->get_id()] = $chat;
	}

	public static function get($id)
	{
		if (is_null($id)) {
			return null;
		}

		if (!array_key_exists($id, self::get_instance()->chats)) {
			throw new CbroChatNotFound($id);
		}

		return self::get_instance()->chats[$id];
	}

	public static function get_default_chat()
	{
		try {
			return self::get(self::$main_chat_id);
		} catch (CbroChatNotFound $ex) {
			// $chat = self::create_default_chat();
			// CbroChats::init_chat($chat);
			// CbroChats::save();
			// return $chat;
		}
	}

	public static function create_chat($id, $guid, $save = true)
	{
		$date = new DateTimeImmutable();
		$milli = (int) $date->format('Uv');

		$chat = new CbroChat($id, $id, false, $guid, $milli);

		self::init_chat($chat);

		if ($save) {
			self::save();
		}

		return $chat;
	}

	public static function update($chat)
	{
		self::get($chat->get_id());
		self::get_instance()->chats[$chat->get_id()] = $chat;
	}

	public static function change_chat_id($chat, $new_id)
	{
		self::delete($chat->get_id());
		$chat->set_id($new_id);
		self::get_instance()->chats[$chat->get_id()] = $chat;
	}

	public static function delete($id)
	{
		self::get($id);
		unset(self::get_instance()->chats[$id]);
	}

	public static function deleteAll()
	{
		foreach (self::iterator() as $name => $chat) {
			unset(self::get_instance()->chats[$chat->get_id()]);
		}

		self::save();
	}

	public static function save()
	{
		self::get_backend()->set(self::prepare());
	}

	public static function prepare()
	{
		$res = [];

		foreach (self::iterator() as $name => $chat) {
			array_push($res, $chat);
		}

		return json_encode($res);
	}

	public static function iterator()
	{
		return new CbroChatsIterator(array_keys(self::get_instance()->chats));
	}

	public static function isEmpty()
	{
		return empty(self::get_instance()->chats);
	}
}