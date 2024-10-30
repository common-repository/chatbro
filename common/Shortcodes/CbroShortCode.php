<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Shortcodes;

defined('CHATBROENGINE') || die();

use Chatbroapp\Common\Utils\CbroUtils;
use Chatbroapp\Common\Settings\CbroSettings;
use Chatbroapp\Common\Chats\CbroChats;
use Chatbroapp\Common\Chats\CbroDisplayChat;
use Chatbroapp\Common\Users\CbroUser;
use Chatbroapp\Common\Exceptions\CbroChatNotFound;

class CbroShortCode
{
	private static $instance = null;

	private function __construct()
	{
		add_shortcode('chatbro', array(&$this, 'render'));
	}

	public static function get_instance()
	{
		if (!self::$instance) {
			self::$instance = new CbroShortCode();
		}
		return self::$instance;
	}

	public static function render($atts, $content = null)
	{
		$a = CbroUtils::merge_attributes($atts, [
			'static' => true,
			'registered_only' => false,
			'title' => null,
			'child' => false,
			'ext_id' => null,
			'id' => null,
		]);

		if (!CbroSettings::get(CbroSettings::enabled)) {
			return '';
		}

		if (!CbroSettings::get(CbroSettings::enable_shortcodes)) {
			return '';
		}

		$chat_id = $a['id'];
		$chat = null;

		if ($chat_id) {
			try {
				$chat = CbroChats::get($chat_id);
			} catch (CbroChatNotFound $ex) {
				return;
			}
		} else {
			$chat = CbroChats::get_default_chat();
		}

		if (!isset($chat)) {
			return;
		}

		$registered_only =
			$atts && array_key_exists('registered_only', $atts)
			? strtolower($a['registered_only']) == 'true' ||
			$a['registered_only'] == '1'
			: !$chat->get_display_to_guests();

		if (
			!CbroUser::can_view($chat->get_display_to_guests()) ||
			($registered_only && !CbroUser::is_logged_in())
		) {
			return '';
		}

		$static = strtolower($a['static']) == 'true' || $a['static'] == '1';
		$child = strtolower($a['child']) == 'true' || $a['child'] == '1';

		if ($child && $a['title']) {
			$code = (new CbroDisplayChat($chat))->get_child_chat_code(
				$static,
				$a['title'],
				$a['ext_id']
			);
		} else {
			$code = $static
				? (new CbroDisplayChat($chat))->get_static_chat_code()
				: (new CbroDisplayChat($chat))->get_chat_code();
		}

		return $code;
	}
}
