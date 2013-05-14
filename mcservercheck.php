<?php
/**
 * @package MC_Server_Check
 * @version alpha
 */
/*
Plugin Name: MC Server Check
Plugin URI: http://wordpress.org/extend/plugins/mc-server-check/
Description: A plugin to display Minecraft server stats with shortcodes and widgets.
Author: Robert Lane
Version: alpha
Author URI: http://profiles.wordpress.org/robertlane
*/

$MCServerCheck_pluginURL = plugin_dir_url(__FILE__);

add_option( 'mcserver_cache' , array("duck" => "foo",) , '', 'yes');

include_once('includes/server_check_engine.php');
include_once('includes/widget.php');
include_once('includes/shortcode.php');


add_shortcode( 'mcservercheck', 'mcserver_shortcode');


?>
