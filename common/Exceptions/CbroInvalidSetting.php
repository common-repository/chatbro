<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Exceptions;

defined('CHATBROENGINE') || die();

use Exception;

class CbroInvalidSetting extends Exception
{
	public function __construct()
	{
		parent::__construct('Invalid setting');
	}
}
