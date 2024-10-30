<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Settings;

defined('CHATBROENGINE') || die();

use Chatbroapp\Common\Settings\CbroSettings;
use Iterator;

class CbroSettingsIterator implements Iterator
{
	private $keys;
	private $pos;

	public function __construct($keys)
	{
		$this->keys = $keys;
		$this->pos = 0;
	}

	public function rewind(): void
	{
		$this->pos = 0;
	}

	public function current(): CbroSetting
	{
		return CbroSettings::get_setting($this->keys[$this->pos]);
	}

	public function key(): string
	{
		return $this->keys[$this->pos];
	}

	public function next(): void
	{
		++$this->pos;
	}

	public function valid(): bool
	{
		return isset($this->keys[$this->pos]);
	}
}
