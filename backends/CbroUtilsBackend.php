<?php
/**
 * @copyright   Copyright (C) Chatbro. All rights reserved.
 * @license     GNU General Public License version 2 or later.
 */

namespace Chatbroapp\Backends;

defined('CHATBROENGINE') || die();
defined('ABSPATH') or die('No script kiddies please!');

require_once (__DIR__ . '/../common/Core/version.php');
require_once (__DIR__ . '/../backends/version.php');

use Chatbroapp\Common\Interfaces\IcbroUtilsBackend;
use Chatbroapp\Common\Api\CbroApi;
use Chatbroapp\Common\Common\CbroCommon;

class CbroUtilsBackend implements IcbroUtilsBackend
{
	private static $scripts_count = 0;
	private static $styles_count = 0;
	private $cms;

	public function __construct()
	{
	}

	function get_site_url()
	{
		return CbroCommon::get_option('siteurl');
	}

	function generated_scripts()
	{
		?>
		<script>
			var cBroGlobals = {
				saveSettingsUrl: ajaxurl,
				createChatUrl: ajaxurl,
				deleteChatUrl: ajaxurl,
				updateChatUrl: ajaxurl,
				getFaqUrl: ajaxurl,
				getChatsUrl: ajaxurl,
				cms: "<?php echo CbroApi::get_platform_name(); ?>",
				shortcodeTemplateType: '',
				chatShortcodeTemplate: '[chatbro id="%s" static="true"]',
				childShortcodeTemplate: '[chatbro id="%s" child="true" title="Child chat %s"]',
				dropDownConflict: false
			};
		</script>
		<?php
	}

	function get_site_domain()
	{
		$url = self::get_site_url();
		if (!preg_match('/^.+:\/\/([^\/\:]+)/', $url, $m)) {
			return '';
		}

		return $m[1];
	}

	function get_platform()
	{
		return 'wordpress-plugin-' . chatbro_common_version . '.' . chatbro_wp_minor_version;
	}

	function is_front_page()
	{
		return is_front_page();
	}

	function match_path($path, $patterns)
	{
		$to_replace = array(
			'/(\r\n?|\n)/',
			'/\\\\\*/',
		);
		$replacements = array(
			'|',
			'.*',
		);
		$patterns_quoted = preg_quote($patterns, '/');
		$regexps = '/^(' . preg_replace($to_replace, $replacements, $patterns_quoted) . ')$/';
		return (bool) preg_match($regexps, $path);
	}

	function check_path($display, $selected_pages)
	{
		global $_SERVER;

		$page_match = false;
		$selected_pages = trim($selected_pages);

		if ($selected_pages != '') {

			if (function_exists('mb_strtolower')) {
				$pages = mb_strtolower($selected_pages);
				$path = mb_strtolower($_SERVER['REQUEST_URI']);
			} else {
				$pages = strtolower($selected_pages);
				$path = strtolower($_SERVER['REQUEST_URI']);
			}

			$page_match = self::match_path($path, $pages);

			if ($display == 'except_listed')
				$page_match = !$page_match;
		}

		return $page_match;
	}

	function enque_script($file)
	{
		wp_enqueue_script('chatbro' . ++self::$scripts_count, plugins_url('chatbro/js/' . $file));
	}

	function enque_style($file)
	{
		wp_enqueue_style('chatbro' . ++self::$styles_count, plugins_url('chatbro/css/' . $file));
	}

	function translate($text)
	{
		return __($text, 'chatbro');
	}

	function get_locale()
	{
		$t = explode('_', get_locale());
		return $t[0];
	}

	function get_request_var($var_name)
	{
		global $_POST;

		if (in_array($var_name, array_keys($_POST)))
			return $_POST[$var_name];

		return null;
	}

	function get_home_url()
	{
		return get_home_url();
	}

	function get_default_profile_url()
	{
		return get_home_url() . '/';
	}

	function get_support_chat_data_offset_top()
	{
		return 53;
	}
}