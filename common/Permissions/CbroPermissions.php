<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Permissions;

defined('CHATBROENGINE') || die();

use Chatbroapp\Common\Core\CbroBackendable;

class CbroPermissions extends CbroBackendable
{
  const cap_delete = "chatbro_delete_message";
  const cap_ban = "chatbro_ban_user";
  const cap_view = "chatbro_view_chat";

  public static function can($capability, $display_to_guests = true)
  {
    return self::get_backend()->can($capability, $display_to_guests);
  }

  public static function can_manage_settings()
  {
    return self::get_backend()->can_manage_settings();
  }
}
