<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Users;

defined('CHATBROENGINE') || die();

use Chatbroapp\Common\Core\CbroBackendable;
use Chatbroapp\Common\Permissions\CbroPermissions;

class CbroUser extends CbroBackendable
{
	public static function can_view($display_to_guests)
	{
		return CbroPermissions::can(
			CbroPermissions::cap_view,
			$display_to_guests
		);
	}

	public static function can_ban()
	{
		return CbroPermissions::can(CbroPermissions::cap_ban);
	}

	public static function can_delete()
	{
		return CbroPermissions::can(CbroPermissions::cap_delete);
	}

	public static function is_logged_in()
	{
		return self::get_backend()->is_logged_in();
	}

	public static function is_admin()
	{
		return self::get_backend()->is_admin();
	}

	public static function avatar_url()
	{
		return self::get_backend()->avatar_url();
	}

	public static function profile_url()
	{
		return self::get_backend()->profile_url();
	}

	public static function id()
	{
		return self::get_backend()->id();
	}

	public static function display_name()
	{
		return self::get_backend()->display_name();
	}

	public static function full_name()
	{
		return self::get_backend()->full_name();
	}
}
