<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Common\Interfaces;

defined('CHATBROENGINE') || die();

interface IcbroSettingsBackend
{
  function get($id);
  function set($id, $value);
  function del($id);
  function postpone_write();
  function flush();
  function add_extra_settings($settings);
  function get_cbro_utils_class();
}