<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Api;

defined('CHATBROENGINE') || die();

use JsonSerializable;

class Token implements JsonSerializable
{
    private $value;
    private $expires_in;


    public function __construct($value, $expires_in)
    {
        $this->value = $value;
        $this->expires_in = $expires_in;
    }

    public function get_value()
    {
        return $this->value;
    }

    public function get_expires_in()
    {
        return $this->expires_in;
    }

    public function is_expired()
    {
        return time() > $this->get_expires_in();
    }

    public function jsonSerialize(): array
    {
        return [
            'value' => $this->get_value(),
            'expiresIn' => $this->get_expires_in(),
        ];
    }
}