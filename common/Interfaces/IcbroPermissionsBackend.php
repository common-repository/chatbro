<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Interfaces;

defined('CHATBROENGINE') || die();

interface IcbroPermissionsBackend
{
  function can($capability, $display_to_guests);
  function can_manage_settings();
}
