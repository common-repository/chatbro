<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Backends;

defined('CHATBROENGINE') || die();
defined('ABSPATH') or die('No script kiddies please!');

use Chatbroapp\Common\Interfaces\IcbroUserBackend;
use Chatbroapp\Common\Settings\CbroSettings;

class CbroUserBackend implements IcbroUserBackend
{
  private $user;

  public function __construct()
  {
    $this->user = wp_get_current_user();
  }

  function is_logged_in()
  {
    return is_user_logged_in();
  }

  function is_admin()
  {
    return current_user_can('administrator');
  }

  function avatar_url()
  {
    $user_id = $this->user->ID;
    $site_user_avatar_url = "";

    preg_match("/src=['\"]([^'\"]+)['\"]/i", get_avatar($user_id), $avatar_path);

    if (count($avatar_path) != 0)
      $site_user_avatar_url = $avatar_path[1];

    if ($site_user_avatar_url == "")
      $site_user_avatar_url = get_avatar_url($user_id);

    return $site_user_avatar_url;
  }

  function profile_url()
  {
    $profile_path = CbroSettings::get(CbroSettings::user_profile_path);
    $profile_url = '';

    if ($profile_path) {
      $profile_url = get_home_url() . ($profile_path[0] == '/' ? '' : '/') . $profile_path;
      $profile_url = str_ireplace('{$username}', $this->user->user_login, $profile_url);
      $profile_url = str_ireplace('{$userid}', $this->user->ID, $profile_url);
    }

    return $profile_url;
  }

  function id()
  {
    return $this->user->ID;
  }

  function display_name()
  {
    return $this->user->display_name;
  }

  function full_name()
  {
    return $this->user->display_name;
  }
}