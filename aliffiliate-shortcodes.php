<?php
/*
   Plugin Name: Aliffiliate Shortcodes
   Plugin URI: http://wordpress.org/extend/plugins/aliffiliate-shortcodes/
   Version: 0.1
   Author: GI Team
   Description: 
   Text Domain: aliffiliate-shortcodes   
*/
if (!defined('ABSPATH')) exit;

//can DEFINE needed variables
define('ALIFFILIATE_SHORTCODES_PLUGIN_DIR', plugin_dir_path( __FILE__ ));
$api_url = 'http://aliffiliate.com/api/index.php';
$plugin_slug = basename(dirname(__FILE__));
require_once(ALIFFILIATE_SHORTCODES_PLUGIN_DIR . 'class.aliffiliate-admin.php');
add_action('init', array('Aliffiliate_Shortcode', 'init'));
if (is_admin()) {          
    
}
else
{
    add_shortcode( 'aliffiliate_short', array('Aliffiliate_Shortcode','add_ali_shortcode'));
}


?>