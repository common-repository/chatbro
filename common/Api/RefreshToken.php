<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Api;

defined('CHATBROENGINE') || die();

use Chatbroapp\Common\Api\Token;
use JsonSerializable;

class RefreshToken extends Token implements JsonSerializable
{
}