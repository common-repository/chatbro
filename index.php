<?php
/**
 * @package ChatBro
 * @version 4.1.0
 */
/*
Plugin Name: ChatBro
Plugin URI: http://chatbro.com
Description: Live group chat for your community with social networks integration. Chat conversation is being syncronized with popular messengers. Love ChatBro? Spread the word! <a href="https://wordpress.org/support/view/plugin-reviews/chatbro">Click here to review the plugin!</a>.
Version: 4.1.0
Author: ChatBro
Author URI: http://chatbro.com
License: GPL3
Text Domain: chatbro
Domain Path: /common/languages/
*/

defined('ABSPATH') or die('No script kiddies please!');

if (!defined('CHATBROENGINE')) {
    define('CHATBROENGINE', 1);
}

require_once ('init.php');
CBroInit::init();