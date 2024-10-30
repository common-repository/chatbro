<?php

if (!defined('WP_UNINSTALL_PLUGIN'))
  exit;

if (!defined('CHATBROENGINE')) {
  define('CHATBROENGINE', 1);
}

require_once (ABSPATH . '/wp-admin/includes/user.php');
require_once __DIR__ . '/vendor/autoload.php';

use Chatbroapp\Common\Admin\CbroAdmin;
use Chatbroapp\Common\Utils\CbroUtils;
use Chatbroapp\Common\Common\CbroCommon;
use Chatbroapp\Common\Settings\CbroSettings;
use Chatbroapp\Common\Chats\CbroChats;
use Chatbroapp\Common\Api\CbroApi;
use Chatbroapp\Common\Permissions\CbroPermissions;
use Chatbroapp\Backends\CbroUtilsBackend;
use Chatbroapp\Backends\CbroAdminBackend;
use Chatbroapp\Backends\CbroSettingsBackend;
use Chatbroapp\Backends\CbroChatsBackend;
use Chatbroapp\Backends\CbroApiBackend;

require_once ('init.php');

CbroUtils::init(new CbroUtilsBackend());
CbroAdmin::init(new CbroAdminBackend());
CbroSettings::init(new CbroSettingsBackend());
CbroChats::init(new CbroChatsBackend());
CbroApi::init(new CbroApiBackend());

CbroApi::remove_tokens();
CbroCommon::delete_option(CBroInit::caps_initialized);

foreach (CbroSettings::iterator() as $s) {
  $s->del();
}

foreach (get_editable_roles() as $name => $info) {
  $role = get_role($name);
  $role->remove_cap(CbroPermissions::cap_view);
  $role->remove_cap(CbroPermissions::cap_ban);
  $role->remove_cap(CbroPermissions::cap_delete);
}

CbroChats::deleteAll();
CbroCommon::delete_option(CbroChatsBackend::chat_list);

