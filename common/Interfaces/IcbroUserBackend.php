<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Interfaces;

defined('CHATBROENGINE') || die();

interface IcbroUserBackend
{
  function is_logged_in();
  function is_admin();
  function avatar_url();
  function profile_url();
  function id();
  function display_name();
  function full_name();
}
