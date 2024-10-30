<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Interfaces;

defined('CHATBROENGINE') || die();

interface IcbroSetting
{
  function id();
  function get();
  function set($value);
  function set_sanitized($value);
  function get_params();
  function sanitize($value);
  function del();
}