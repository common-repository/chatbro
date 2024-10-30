<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Backends;

defined('CHATBROENGINE') || die();
defined('ABSPATH') or die('No script kiddies please!');


use Chatbroapp\Common\Interfaces\IcbroSettingsBackend;
use Chatbroapp\Common\Settings\CbroNonExistentOption;
use Chatbroapp\Common\Common\CbroCommon;
use Chatbroapp\Common\Settings\CbroSetting;
use Chatbroapp\Common\Settings\CbroInputType;
use Chatbroapp\Common\Exceptions\CbroSettingNotFound;

class CbroSettingsBackend implements IcbroSettingsBackend
{
	const display_to_guests = "chatbro_chat_display_to_guests";

	public $non_existent_option;

	public function __construct()
	{
		$this->non_existent_option = new CbroNonExistentOption();
	}

	function postpone_write()
	{
	}

	function flush()
	{
	}

	function add_extra_settings($settings)
	{
		$settings->add_setting(
			new CbroSetting(
				$this,
				array(
					'id' => self::display_to_guests,
					'type' => CbroInputType::checkbox,
					'label' => 'Display chat to guests',
					'sanitizer' => [
						$this->get_cbro_utils_class(),
						'sanitize_checkbox'
					],
					'default' => true,
					'active' => false
				)
			)
		);
	}

	public function reg_store()
	{
		CbroCommon::reg_store();
	}

	function update_option($name, $value)
	{
		return CbroCommon::update_option($name, $value);
	}

	function get($id)
	{
		$val = CbroCommon::get_option($id, $this->non_existent_option);

		if ($val === $this->non_existent_option)
			throw new CbroSettingNotFound($id);

		return $val;
	}

	function set($id, $value)
	{
		if (!CbroCommon::add_option($id, $value))
			self::update_option($id, $value);
	}

	function del($name)
	{
		CbroCommon::delete_option($name);
	}

	function get_cbro_utils_class()
	{
		return "\Chatbroapp\Common\Utils\CbroUtils";
	}
}