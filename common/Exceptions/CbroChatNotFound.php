<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

 namespace Chatbroapp\Common\Exceptions;

defined('CHATBROENGINE') || die();

use Exception;

class CbroChatNotFound extends Exception
{
  public function __construct($id = null)
  {
    $msg = 'Chat not found';

    if ($id)
      $msg .= ": {$id}";
    parent::__construct($msg);
  }
}