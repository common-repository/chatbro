<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Backends;

defined('CHATBROENGINE') || die();

use Chatbroapp\Common\Interfaces\IcbroPermissionsBackend;
use Chatbroapp\Common\Permissions\CbroPermissions;

class CbroPermissionsBackend implements IcbroPermissionsBackend
{
	function can($capability, $display_to_guests) {
    
		switch($capability) {
		  case CbroPermissions::cap_ban:
			return current_user_can(CbroPermissions::cap_ban);
	
		  case CbroPermissions::cap_delete:
			return current_user_can(CbroPermissions::cap_delete);
	
		  case CbroPermissions::cap_view:
			$logged_in = is_user_logged_in();
			
			$can_view = $logged_in ? current_user_can(CbroPermissions::cap_view) : false;
	
			if ((!$display_to_guests && !$logged_in) || ($logged_in && !$can_view))
			  return false;
	
			if (!$display_to_guests && !$logged_in)
			  // Don't show the chat to unregistered users
			  return false;
	
			return true;
		}
	  }

	function check_permission($permissions)
	{
		// return current_user_can(CbroPermissions::cap_view);
		return true;
	}

	function can_manage_settings()
	{
		return current_user_can('manage_options');
	}
}