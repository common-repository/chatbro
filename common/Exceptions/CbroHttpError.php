<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Exceptions;

defined('CHATBROENGINE') || die();

use Exception;

class CbroHttpError extends Exception
{
	public function __construct($message)
	{
		parent::__construct($message);
	}
}
