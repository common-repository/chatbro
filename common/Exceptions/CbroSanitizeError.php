<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Exceptions;

defined('CHATBROENGINE') || die();

use Exception;

class CbroSanitizeError extends Exception
{
	const Error = 'error';
	const Fatal = 'fatal';
	private $type;

	public function __construct($message, $type)
	{
		$this->type = $type;
		parent::__construct($message);
	}

	public function type()
	{
		return $this->type;
	}
}
