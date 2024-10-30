<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Backends;

defined('CHATBROENGINE') || die();
defined('ABSPATH') or die('No script kiddies please!');

use Chatbroapp\Common\Interfaces\IcbroAdminBackend;
use Chatbroapp\Common\Utils\CbroUtils;
use Chatbroapp\Common\Permissions\CbroPermissions;

class CbroAdminBackend implements IcbroAdminBackend
{
  public function __construct()
  {
  }

  public function display($content)
  {
    // Проверка на наличие прав для отображения страницы
    if (!current_user_can('manage_options')) {
      return;
    }

    echo($content);
  }

  public function display_ajax($content)
  {
    // Проверка на наличие прав для отображения страницы
    if (!current_user_can('manage_options')) {
      return;
    }

    wp_send_json($content);
  }

  function has_permission_editor()
  {
    return true;
  }

  function has_shortcodes()
  {
    return true;
  }

  function get_login_url()
  {
    return wp_login_url(get_permalink());
  }

  function additional_fields($type)
  {
    switch ($type) {
      case 'settings':
        wp_nonce_field("chatbro_save_settings", "chb-sec");
        ?>
        <input name="action" type="hidden" value="chatbro_save_settings">
        <?php
        break;
      case 'create-chat':
        wp_nonce_field("chatbro_create_chat", "chb-sec-create-chat");
        ?>
        <input name="action" type="hidden" value="chatbro_create_chat">
        <?php
        break;
      case 'delete-chat':
        wp_nonce_field("chatbro_delete_chat", "chb-sec-delete-chat");
        ?>
        <input name="action" type="hidden" value="delete">
        <?php
        break;
      case 'update-chat':
        wp_nonce_field("chatbro_update_chat", "chb-sec-update-chat");
        ?>
        <input name="action" type="hidden" value="chatbro_update_chat">
        <?php
        break;
      case 'get-chats':
        wp_nonce_field("chatbro_get_chats", "chb-sec-get-chats");
        ?>
        <input name="action" type="hidden" value="chatbro_get_chats">
        <?php
        break;
    }
  }

  function check_token($type)
  {
    switch ($type) {
      case 'settings':
        return wp_verify_nonce($_POST['chb-sec'], "chatbro_save_settings") ? true : false;
      case 'create-chat':
        return wp_verify_nonce($_POST['chb-sec-create-chat'], "chatbro_create_chat") ? true : false;
      case 'update-chat':
        return wp_verify_nonce($_POST['chb-sec-update-chat'], "chatbro_update_chat") ? true : false;
      case 'delete-chat':
        return wp_verify_nonce($_POST['chb-sec-delete-chat'], "chatbro_delete_chat") ? true : false;
      case 'get-chats':
        return wp_verify_nonce($_POST['chb-sec-get-chats'], "chatbro_get_chats") ? true : false;
    }
  }

  function save_permissions()
  {
    global $_POST;

    foreach (get_editable_roles() as $name => $info) {
      $viewCap = $_POST['chatbro_' . $name . '_view'] == 'on' ? true : false;
      $banCap = $_POST['chatbro_' . $name . '_ban'] == 'on' ? true : false;
      $deleteCap = $_POST['chatbro_' . $name . '_delete'] == 'on' ? true : false;

      $role = get_role($name);

      if ($viewCap)
        $role->add_cap(CbroPermissions::cap_view);
      else
        $role->remove_cap(CbroPermissions::cap_view);

      if ($banCap)
        $role->add_cap(CbroPermissions::cap_ban);
      else
        $role->remove_cap(CbroPermissions::cap_ban);

      if ($deleteCap)
        $role->add_cap(CbroPermissions::cap_delete);
      else
        $role->remove_cap(CbroPermissions::cap_delete);
    }
  }

  function render_permissions()
  {
    ?>
    <div id="permissions-group" class="form-group">
      <label class="control-label col-sm-2">
        <?php _e("Permissions", "chatbro"); ?>
      </label>
      <div class="col-sm-10">
        <table id="chatbro-permissions" class="table table-active table-striped">
          <tr>
            <th>
              <?php _e("Role", "chatbro"); ?>
            </th>
            <th>
              <?php _e("View", "chatbro"); ?>
            </th>
            <th>
              <?php _e("Ban", "chatbro"); ?>
            </th>
            <th>
              <?php _e("Delete", "chatbro"); ?>
            </th>
          </tr>
          <?php
          foreach (get_editable_roles() as $name => $info) {
            $ctrlViewId = "chatbro_" . $name . "_view";
            $ctrlBanId = "chatbro_" . $name . "_ban";
            $ctrlDeleteId = "chatbro_" . $name . "_delete";

            $role = get_role($name);

            $chkView = $role->has_cap(CbroPermissions::cap_view) ? "checked" : "";
            $chkBan = $role->has_cap(CbroPermissions::cap_ban) ? "checked" : "";
            $chkDelete = $role->has_cap(CbroPermissions::cap_delete) ? "checked" : "";
            ?>
            <tr>
              <td>
                <?php echo $info["name"] ?>
              </td>
              <td><input id="<?php _e($ctrlViewId); ?>" name="<?php _e($ctrlViewId); ?>" type="checkbox" <?php echo $chkView; ?>></td>
              <td><input id="<?php _e($ctrlBanId); ?>" name="<?php _e($ctrlBanId); ?>" <?php echo $chkBan; ?> type="checkbox">
              </td>
              <td><input id="<?php _e($ctrlDeleteId); ?>" name="<?php _e($ctrlDeleteId); ?>" type="checkbox" <?php echo $chkDelete; ?>></td>
            </tr>
            <?php
          }
          ?>
        </table>
      </div>
    </div>
    <?php
  }

  function get_chat_help_tips()
  {
    ?>
    <div class="bs-callout bs-callout-info">
      <h4 class="bs-callout-info-header">
        <?php echo CbroUtils::translate('Shortcodes'); ?>
      </h4>
      <div class="bs-callout-info-body">
        <p>
          <?php echo CbroUtils::translate("Use shortcodes for flexible chat customization. It allows you to insert the chat on a specific page or even in a specific block on the page. Simply click the 'Copy Shortcode' button and paste the code into the desired block. If the button is not present, make sure that the 'Shortcodes enabled' parameter on the 'Plugin settings' tab is enabled."); ?>
        </p>
        <p>
          <?php echo CbroUtils::translate("Such a chat will ignore the 'Show popup chat' parameter and will appear in any case."); ?>
        </p>
        <h5>
          <?php echo CbroUtils::translate('Shortcode example'); ?>
        </h5>
        <code id="chatbroShortCodeExample"></code>
        <h5>
          <?php echo CbroUtils::translate('Supported shortcode attributes'); ?>
        </h5>
        <ul style="list-style-type:none; margin-left: 0;">
          <li>
            <?php echo CbroUtils::translate(
              '<em><b>id</b></em> &ndash; chat ID. For the main chat, this parameter should be omitted.'
            ); ?>
          </li>
          <li>
            <?php echo CbroUtils::translate('<em><b>static</b></em> &ndash; static not movable chat widget (default <em>true</em>).'); ?>
          </li>
          <li>
            <?php echo CbroUtils::translate("<em><b>registered_only</b></em> &ndash; display chat widget to logged in users only (default <em>false</em>). If this attribute is explicitly set it precedes the global <em>'Display chat to guests'</em> setting value."); ?>
          </li>
        </ul>
      </div>
    </div>
    <div class="bs-callout bs-callout-info">
      <h4 class="bs-callout-info-header">
        <?php echo CbroUtils::translate('Dynamic creation'); ?>
      </h4>
      <div class="bs-callout-info-body">
        <p>
          <?php echo CbroUtils::translate(
            'For example you want different chats for different pages of your site. In case you have video hosting site you can make different chat for each video. Generate these chats on the fly using the child chats feature."'
          ); ?>
          <?php echo CbroUtils::translate(
            'Child chat is a chat that uses the settings of another (parent) chat. This is done in order to automate the process of creating your chats.'
          ); ?>
        </p>

        <h5>
          <?php echo CbroUtils::translate('How to use it?'); ?>
        </h5>

        <p>
          <?php echo CbroUtils::translate(
            "Simply click on the 'Dynamic creation' button, and then paste this shortcode in the right place. When the page loads, the chat will be created automatically."
          ); ?>
        </p>

        <p>
          <?php echo CbroUtils::translate(
            'Child chats are inserted through a special shortcode:'
          ); ?>
        </p>
        <p>
          <code id="chatbroShortCodeChildExample"></code>
        </p>

        <ul style="list-style-type:none; margin-left: 0;">
          <li>
            <?php echo CbroUtils::translate(
              '<em><b>id</b></em> &ndash; ID of parent chat. The chat will copy the settings of this chat.'
            ); ?>
          </li>
          <li>
            <?php echo CbroUtils::translate(
              '<em><b>child</b></em> &ndash; tells the system to create a child chat.'
            ); ?>
          </li>
          <li>
            <?php echo CbroUtils::translate(
              '<em><b>title</b></em> &ndash; unique title that to be used as chat identifier. This means that this chat will be associated with this title. 2 shortcodes with the same title will load 2 identical chats.'
            ); ?>
          </li>
          <li>
            <?php echo CbroUtils::translate(
              '<em><b>ext_id</b></em> &ndash; optional additional indentificator. If you want to have two or more different chats with equal titles add this parameter. Timestamp is perfect for this.'
            ); ?>
          </li>
        </ul>

        <h5>
          <?php echo CbroUtils::translate(
            'What do child chats inherit?'
          ); ?>
        </h5>
        <ul style="list-style-type:none; margin-left: 0;">
          <li>-
            <?php echo CbroUtils::translate(
              'Visual settings;'
            ); ?>
          </li>
          <li>-
            <?php echo CbroUtils::translate(
              'Authorization settings;'
            ); ?>
          </li>
          <li>-
            <?php echo CbroUtils::translate(
              'Privacy settings;'
            ); ?>
          </li>
          <li>-
            <?php echo CbroUtils::translate(
              'Message filter function;'
            ); ?>
          </li>
          <li>-
            <?php echo CbroUtils::translate(
              'Tariff, balance.'
            ); ?>
          </li>
        </ul>
      </div>
    </div>
    <div class="bs-callout bs-callout-info">
      <h4 class="bs-callout-info-header">
        <?php echo CbroUtils::translate('Synchronization with messengers'); ?>
      </h4>
      <div class="bs-callout-info-body">
        <p>
          <?php echo CbroUtils::translate('Chatbro allows you to synchronize messages between the website and popular messengers like Telegram or VK.'); ?>
        </p>

        <h5>
          <?php echo CbroUtils::translate('How to sync up Telegram group/supergroup?'); ?>
        </h5>
        <p>
          <?php echo CbroUtils::translate(
            "Add @ChatbroBot to telegram group or supergroup. The bot will send a link in a private message where you can link the web chat and the telegram group/supergroup. If you don't have contact with our bot until this moment, it will not be able to send you a private message and will send a link to the group/supergroup."
          ); ?>
        </p>

        <h5>
          <?php echo CbroUtils::translate(
            'How to sync up the Telegram channel?'
          ); ?>
        </h5>
        <p>
          <?php echo CbroUtils::translate(
            "Add @ChatbroBot to the telegram channel as an administrator and type '/sync' in the channel. The bot will send a sync link to this channel."
          ); ?>
        </p>

        <h5>
          <?php echo CbroUtils::translate('What if I want to use my bot with a specific name?'); ?>
        </h5>
        <p>
          <?php echo CbroUtils::translate(
            'You can easily add your own bot.'
          ); ?>
          <a href="https://www.chatbro.com/documentation/custom_telegram_bot/" target='_blank'>
            <?php echo CbroUtils::translate(
              'Follow the instructions.'
            ); ?>
          </a>
        </p>

        <h5>
          <?php echo CbroUtils::translate('How to sync VK conversation with the web chat?'); ?>
        </h5>
        <p>
          <a href="https://www.chatbro.com/documentation/custom_vk_bot/" target='_blank'>
            <?php echo CbroUtils::translate(
              'Detail instruction'
            ); ?>
          </a>
          <?php echo CbroUtils::translate('how to add bot and sync it.'); ?>
        </p>

        <h5>
          <?php echo CbroUtils::translate(
            'How to make automatic synchronization?'
          ); ?>
        </h5>
        <p>
          <a href="https://www.chatbro.com/documentation/automatic_synchronization/" target='_blank'>
            <?php echo CbroUtils::translate('Detail instruction') .
              '.'; ?>
          </a>
        </p>
        <p><!-- just height space --></p>
      </div>
    </div>
    <div class="bs-callout bs-callout-info">
      <h4 class="bs-callout-info-header">
        <?php echo CbroUtils::translate('Message filtering'); ?>
      </h4>
      <div class="bs-callout-info-body">
        <p>
          <?php echo CbroUtils::translate(
            'You can set up flexible message filtering on our server side or use a send delay.'
          ); ?>
        </p>
        <h5>
          <?php echo CbroUtils::translate('How to configure?'); ?>
        </h5>
        <p>
          <?php echo CbroUtils::translate(
            "Go to the chat editor, then the 'Restrictions' block, and click 'Edit function'. In the editor that opens, at the bottom right, there will be a general example button."
          ); ?>
        </p>
        <p><!-- just height space --></p>
      </div>
    </div>
    <div class="bs-callout bs-callout-info">
      <h4 class="bs-callout-info-header">
        <?php echo CbroUtils::translate('Spoofing protection'); ?>
      </h4>
      <div class="bs-callout-info-body">
        <p>
          <?php echo CbroUtils::translate(
            'Spoofing is the situation when a person or a program successfully masks under another way of falsifying data and receives illegal benefits. For example, in the chat one person can write under the guise of another. The spoofing protection excludes such situations. Chatbro allows you to make sure that the chat is not displayed on other sites besides yours and exclude sending messages from other resources.'
          ); ?>
        </p>
        <h5>
          <?php echo CbroUtils::translate('How to configure?'); ?>
        </h5>
        <p>
          <?php echo CbroUtils::translate(
            'This feature is enabled and configured in the plugin by default.'
          ); ?>
        </p>
        <p><!-- just height space --></p>
      </div>
    </div>
    <?php
  }

  function get_settings_help_tips()
  {
    ?>
    <div class="bs-callout bs-callout-info">
      <h4 class="bs-callout-info-header">
        <?php echo CbroUtils::translate('Account ID'); ?>
      </h4>
      <div class="bs-callout-info-body">
        <p>
          <?php echo CbroUtils::translate("Your current profile and chats are associated with your account ID. Please keep it in a secure place. In the future, when removing the plugin or transitioning to another CMS, you'll be able to easily restore all your chats and account."); ?>
        </p>
        <div class="alert alert-warning">
          <?php echo CbroUtils::translate("Remember that deleting the plugin will erase information about the account ID. If you want to temporarily pause the plugin, simply uncheck 'Plugin enabled.'"); ?>
        </div>
      </div>
    </div>
    <div class="bs-callout bs-callout-info">
      <h4 class="bs-callout-info-header">
        <?php echo CbroUtils::translate('How to transfer ChatBro account to plugin?'); ?>
      </h4>
      <div class="bs-callout-info-body">
        <p>
          <?php echo CbroUtils::translate('To transfer an existing account from ChatBro to the plugin, you can follow these steps:'); ?>
        </p>
        <p>
          <?php echo CbroUtils::translate(
            '1. Merge Chats from Plugin with ChatBro Profile:'
          ); ?>
        </p>
        <ul style="list-style-type:none; margin-left: 0;">
          <li>
            <?php echo CbroUtils::translate(
              "1. In the plugin, navigate to the 'ChatBro profile' tab."
            ); ?>
          </li>
          <li>
            <?php echo CbroUtils::translate(
              "2. In the 'Linked accounts' section, click the 'Connect' button."
            ); ?>
          </li>
          <li>
            <?php echo CbroUtils::translate(
              '3. Authenticate with the required ChatBro account.'
            ); ?>
          </li>
          <li>
            <?php echo CbroUtils::translate(
              '4. This will merge your accounts, allowing you to administer your chats through the chatbro.com website account.'
            ); ?>
          </li>
        </ul>

        <p>
          <?php echo CbroUtils::translate(
            '2. Integrate Chatbro.com Account into the Plugin:'
          ); ?>
        </p>
        <ul style="list-style-type:none; margin-left: 0;">
          <li>
            <?php echo CbroUtils::translate(
              '1. Log in to the chatbro.com website.'
            ); ?>
          </li>
          <li>
            <?php echo CbroUtils::translate(
              '2. Go to your profile settings.'
            ); ?>
          </li>
          <li>
            <?php echo CbroUtils::translate(
              "3. In the 'API' section, click the 'Enable' button."
            ); ?>
          </li>
          <li>
            <?php echo CbroUtils::translate(
              '4. Copy the generated API keys.'
            ); ?>
          </li>
          <li>
            <?php echo CbroUtils::translate(
              '5. Open the plugin settings and go to the third tab.'
            ); ?>
          </li>
          <li>
            <?php echo CbroUtils::translate(
              '6. Paste the copied API keys into the corresponding fields.'
            ); ?>
          </li>
          <li>
            <?php echo CbroUtils::translate(
              '7. Save the changes.'
            ); ?>
          </li>
        </ul>
        <p>
          <?php echo CbroUtils::translate('By following these steps, you should be able to transfer your existing ChatBro account to the plugin and seamlessly manage your chats.'); ?>
        </p>
      </div>
    </div>
    <?php
  }
}