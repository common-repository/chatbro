<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Settings;

defined('CHATBROENGINE') || die();

use Chatbroapp\Common\Interfaces\IcbroSetting;
use Chatbroapp\Common\Exceptions\CbroSettingNotFound;
use Chatbroapp\Common\Utils\CbroUtils;
use Exception;

class CbroSetting implements IcbroSetting
{
	private $backend;
	private $params;
	private $id;

	public function __construct($backend, $params)
	{
		$this->params = $params;
		$this->backend = $backend;

		if (!array_key_exists('id', $this->params)) {
			throw new Exception('Invalid setting params (no id)');
		}

		$this->id = $params['id'];

		$value = null;
		try {
			$value = $this->backend->get($this->id);
		} catch (CbroSettingNotFound $e) {
			if (array_key_exists('generator', $this->params)) {
				$value = call_user_func_array($this->params['generator'], []);
			} elseif (array_key_exists('default', $this->params)) {
				$value = $this->params['default'];
			} elseif (!$this->params['required']) {
				return;
			} else {
				throw $e;
			}

			$this->backend->set($this->id, $value);
		}
	}

	public function get()
	{
		$value = null;
		try {
			$value = $this->backend->get($this->id);
		} catch (CbroSettingNotFound $e) {
			if ($this->is_required()) {
				throw $e;
			}
		}

		return $value;
	}

	public function set_sanitized($value)
	{
		if ($this->params['active']) {
			$this->backend->set($this->id, $value);
		}
	}
	public function set($value)
	{
		if ($this->params['active']) {
			$this->set_sanitized($this->sanitize($value));
		}
	}

	public function id()
	{
		return $this->id;
	}

	public function get_params()
	{
		return $this->params;
	}

	public function sanitize($value)
	{
		if (
			array_key_exists('sanitizer', $this->params) &&
			$this->params['active']
		) {
			$value = call_user_func_array($this->params['sanitizer'], [$value]);
		}

		return $value;
	}

	public function is_required()
	{
		return $this->params['required'] === true;
	}

	public function del()
	{
		try {
			$this->backend->del($this->id);
		} catch (CbroSettingNotFound $e) {
		}
	}
}
