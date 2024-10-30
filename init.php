<?php

defined('ABSPATH') or die('No script kiddies please!');

require_once (ABSPATH . '/wp-admin/includes/user.php');
require_once __DIR__ . '/vendor/autoload.php';

use Chatbroapp\Common\Shortcodes\CbroShortCode;
use Chatbroapp\Common\Common\CbroCommon;
use Chatbroapp\Common\Users\CbroUser;
use Chatbroapp\Common\Admin\CbroAdmin;
use Chatbroapp\Common\Utils\CbroUtils;
use Chatbroapp\Common\Settings\CbroSettings;
use Chatbroapp\Common\Chats\CbroChats;
use Chatbroapp\Common\Api\CbroApi;
use Chatbroapp\Common\Chats\CbroDisplayChat;
use Chatbroapp\Common\Permissions\CbroPermissions;
use Chatbroapp\Backends\CbroUtilsBackend;
use Chatbroapp\Backends\CbroAdminBackend;
use Chatbroapp\Backends\CbroSettingsBackend;
use Chatbroapp\Backends\CbroChatsBackend;
use Chatbroapp\Backends\CbroApiBackend;
use Chatbroapp\Backends\CbroPermissionsBackend;
use Chatbroapp\Backends\CbroUserBackend;

require_once ('widget.php');


class CBroInit
{
  const caps_initialized = 'chatbro_caps_initialized';

  public static function load_textdomain()
  {
    // Локализации
    load_plugin_textdomain(
      'chatbro',
      false,
      dirname(plugin_basename(__FILE__)) . '/common/languages'
    );
  }

  public static function my_plugin_load_my_own_textdomain( $mofile, $domain ) {
    if ( 'chatbro' === $domain && false !== strpos( $mofile, WP_LANG_DIR . '/plugins/' ) ) {
      $locale = apply_filters( 'plugin_locale', determine_locale(), $domain );
      $mofile = WP_PLUGIN_DIR . '/' . dirname( plugin_basename( __FILE__ ) ) . '/common/languages/' . $domain . '-' . $locale . '.mo';
    }
    return $mofile;
  }

  public static function do_init()
  {
    self::init_permissions();
    self::init_backends();
    self::init_chat_list();
  }

  private static function init_backends()
  {
    CbroUtils::init(new CbroUtilsBackend());
    CbroCommon::init(null);
    CbroAdmin::init(new CbroAdminBackend());
    CbroSettings::init(new CbroSettingsBackend());
    CbroChats::init(new CbroChatsBackend());
    CbroApi::init(new CbroApiBackend());
    CbroUser::init(new CbroUserBackend());
    CbroPermissions::init(new CbroPermissionsBackend());
    CbroShortCode::get_instance();
  }

  private static function init_chat_list()
  {
    CbroChats::init_chats();
  }

  private static function init_permissions()
  {
    if (!CbroCommon::get_option(self::caps_initialized)) {
      // Initializing capabilities with default values
      $adm = get_role('administrator');
      $adm->add_cap(CbroPermissions::cap_delete);
      $adm->add_cap(CbroPermissions::cap_ban);

      foreach (get_editable_roles() as $name => $info) {
        $role = get_role($name);
        $role->add_cap(CbroPermissions::cap_view);
      }

      CbroCommon::add_option(self::caps_initialized, true);
    }
  }

  public static function add_menu_option()
  {
    add_menu_page(
      "ChatBro",
      "ChatBro",
      "manage_options",
      "chatbro_settings",
      array(CbroAdmin::class, 'display'),
      plugins_url('favicon_small.png', __FILE__)
    );
  }

  public static function chat()
  {
    // Идем по всем чатам и рисуем их, если нужно
    foreach (CbroChats::iterator() as $name => $chat) {
      echo ((new CbroDisplayChat($chat))->get_sitewide_popup_chat_code());
    }
  }

  public static function init()
  {
    add_filter('load_textdomain_mofile', array('CbroInit', 'my_plugin_load_my_own_textdomain'), 10, 2);

    add_action('init', array('CbroInit', 'do_init'));
    add_action('admin_menu', array('CbroInit', 'add_menu_option'));
    add_action('wp_footer', array('CbroInit', 'chat'));

    add_action('wp_ajax_chatbro_save_settings', array(CbroAdmin::class, 'save_settings'));
    add_action('wp_ajax_chatbro_create_chat', array(CbroAdmin::class, 'create_chat'));
    add_action('wp_ajax_chatbro_update_chat', array(CbroAdmin::class, 'update_chat'));
    add_action('wp_ajax_chatbro_delete_chat', array(CbroAdmin::class, 'delete_chat'));
    add_action('wp_ajax_chatbro_get_chats', array(CbroAdmin::class, 'get_chats'));

    add_action('widgets_init', array('CBroWidget', 'register'));
  }
}