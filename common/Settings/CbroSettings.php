<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Settings;

defined('CHATBROENGINE') || die();

use Chatbroapp\Common\Core\CbroBackendable;
use Chatbroapp\Common\Admin\CbroAdmin;
use Chatbroapp\Common\Utils\CbroUtils;
use Chatbroapp\Common\Settings\CbroSetting;
use Chatbroapp\Common\Settings\CbroInputType;
use Chatbroapp\Common\Settings\CbroSettingsIterator;
use Chatbroapp\Common\Exceptions\CbroSettingNotFound;

class CbroSettings extends CbroBackendable
{
	const guid = 'chatbro_chat_guid';
	const secret = 'chatbro_chat_secret';
	const display = 'chatbro_chat_display';
	const selected_pages = 'chatbro_chat_selected_pages';
	const user_profile_path = 'chatbro_chat_user_profile_url';
	const enabled = 'chatbro_enabled';
	const enable_shortcodes = 'chatbro_enable_shortcodes';
	const plugin_version = 'chatbro_plugin_version';

	public $settings;

	protected function __construct($backend)
	{
		parent::__construct($backend);

		$this->settings = [];
		$backend->reg_store();

		$this->add_setting(
			new CbroSetting($backend, [
				'id' => self::enabled,
				'type' => CbroInputType::checkbox,
				'label' => 'Plugin enabled',
				'sanitizer' => [
					$backend->get_cbro_utils_class(),
					'sanitize_checkbox',
				],
				'default' => true,
				'active' => true,
			])
		);

		$this->add_setting(
			new CbroSetting($backend, [
				'id' => self::guid,
				'type' => CbroInputType::text,
				'label' => 'Account ID',
				'sanitizer' => [
					$backend->get_cbro_utils_class(),
					'sanitize_guid',
				],
				'generator' => [
					$backend->get_cbro_utils_class(),
					'gen_guid'
				],
				'required' => true,
				'pattern' =>
					"[\da-f]{8}-[\da-f]{4}-[\da-f]{4}-[\da-f]{4}-[\da-f]{12}$",
				'pattern_error' => 'Invalid chat key',
				'active' => true,
			])
		);

		$this->add_setting(
			new CbroSetting($backend, [
				'id' => self::secret,
				'type' => CbroInputType::text,
				'label' => 'Account secret',
				'sanitizer' => [
					$backend->get_cbro_utils_class(),
					'sanitize_secret',
				],
				'generator' => [
					$backend->get_cbro_utils_class(),
					'gen_secret'
				],
				'required' => true,
				'active' => true,
			])
		);

		$this->add_setting(
			new CbroSetting($backend, [
				'id' => self::user_profile_path,
				'type' => CbroInputType::text,
				'label' => 'User profile path',
				'default' => 'user/{$username}',
				'addon' => CbroUtils::get_home_url(),
				'required' => false,
				'active' => true,
			])
		);

		$this->add_setting(
			new CBroSetting($backend, [
				'id' => self::display,
				'type' => CBroInputType::select,
				'label' => 'Show popup chat',
				'sanitizer' => [
					$backend->get_cbro_utils_class(),
					'sanitize_display'
				],
				'options' => array(
					'everywhere' => 'Everywhere',
					'frontpage_only' => 'Front page only',
					'except_listed' => 'Everywhere except those listed',
					'only_listed' => 'Only the listed pages',
					'disable' => 'Disable'
				),
				'default' => 'everywhere',
				'required' => true,
				'active' => false
			])
		);

		$this->add_setting(
			new CBroSetting($backend, [
				'id' => self::selected_pages,
				'type' => CBroInputType::textarea,
				'label' => "Pages",
				'help_block' => "Specify pages by using their paths. Enter one path per line. The '*' character is a wildcard. Example paths are /2012/10/my-post for a single post and /2012/* for a group of posts. The path should always start with a forward slash(/).",
				'default' => false,
				'required' => false,
				'active' => false
			])
		);

		if (CbroAdmin::has_shortcodes()) {
			$this->add_setting(
				new CbroSetting($backend, [
					'id' => self::enable_shortcodes,
					'type' => CbroInputType::checkbox,
					'label' => 'Shortcodes enabled',
					'sanitizer' => [
						$backend->get_cbro_utils_class(),
						'sanitize_checkbox',
					],
					'default' => true,
					'active' => true,
				])
			);
		}

		$backend->add_extra_settings($this);
	}

	public function add_setting($setting)
	{
		$this->settings[$setting->id()] = $setting;
	}

	public static function get_setting($id)
	{
		if (!array_key_exists($id, self::get_instance()->settings)) {
			throw new CbroSettingNotFound($id);
		}

		return self::get_instance()->settings[$id];
	}

	public static function get($id)
	{
		return self::get_setting($id)->get();
	}

	public static function set($id, $value)
	{
		self::get_setting($id)->set($value);
	}

	public static function set_sanitized($id, $value)
	{
		self::get_setting($id)->set_sanitized($value);
	}

	public static function iterator()
	{
		return new CbroSettingsIterator(
			array_keys(self::get_instance()->settings)
		);
	}

	public static function del($id)
	{
		try {
			self::get_setting($id)->del();
		} catch (CbroSettingNotFound $ex) {
		}
	}
}
