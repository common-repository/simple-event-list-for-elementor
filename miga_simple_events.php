<?php
 /**
  * Plugin Name
  *
  * @package           PluginPackage
  * @author            Michael Gangolf
  * @copyright         2023 Michael Gangolf
  * @license           GPL-2.0-or-later
  *
  * @wordpress-plugin
  * Plugin Name:       Simple event list for Elementor
  * Description:       A very simple event list with an Elementor widget.
  * Version:           1.3.0
  * Requires at least: 5.2
  * Requires PHP:      7.2
  * Author:            Michael Gangolf
  * Author URI:        https://www.migaweb.de/
  * License:           GPL v2 or later
  * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
  * Text Domain:       simple-event-list-for-elementor
  * Domain Path:       /languages
  */

 // If this file is called directly, abort.
 if (! defined('WPINC')) {
     die;
 }

 if (!class_exists("WP_List_Table")) {
     require_once ABSPATH . "wp-admin/includes/class-wp-list-table.php";
 }

 global $wpdb;
 defined("TABLE_NAME_MIGA_SIMPLE_EVENTS") or
   define("TABLE_NAME_MIGA_SIMPLE_EVENTS", $wpdb->prefix . "miga_simple_events");

 function miga_simple_events()
 {
     global $wpdb;
     $table_name = TABLE_NAME_MIGA_SIMPLE_EVENTS;
     if ($wpdb->get_var("show tables like '$table_name'") != $table_name) {
         $charset_collate = $wpdb->get_charset_collate();
         $sql = "CREATE TABLE $table_name (
           id INT NOT NULL AUTO_INCREMENT,
           dateFrom DATE NULL DEFAULT NULL,
           timeFrom TIME NULL DEFAULT NULL,
           dateTo DATE NULL DEFAULT NULL,
           timeTo TIME NULL DEFAULT NULL,
           text VARCHAR(255) NULL DEFAULT NULL,
           link INT NULL DEFAULT NULL,
           visible BOOLEAN NOT NULL DEFAULT FALSE,
           PRIMARY KEY (`id`)
         ) $charset_collate;";
         require_once ABSPATH . "wp-admin/includes/upgrade.php";
         dbDelta($sql);
     }
 }

 register_activation_hook(__FILE__, "miga_simple_events");

 function miga_simple_events_db_update()
 {
     global $wpdb;
     $table_name = TABLE_NAME_MIGA_SIMPLE_EVENTS;
     $oldVersion = get_option('miga_simple_events_db_version', '1.0');
     $newVersion = '1.1';

     if (!(version_compare($oldVersion, $newVersion) < 0)) {
         return false;
     }
     $charset_collate = $wpdb->get_charset_collate();
     $sql = "CREATE TABLE $table_name (
       id INT NOT NULL AUTO_INCREMENT,
       dateFrom DATE NULL DEFAULT NULL,
       timeFrom TIME NULL DEFAULT NULL,
       dateTo DATE NULL DEFAULT NULL,
       timeTo TIME NULL DEFAULT NULL,
       text VARCHAR(255) NULL DEFAULT NULL,
       link INT NULL DEFAULT NULL,
       visible BOOLEAN NOT NULL DEFAULT FALSE,
       PRIMARY KEY (`id`)
     ) $charset_collate;";
     require_once ABSPATH . "wp-admin/includes/upgrade.php";
     dbDelta($sql);
     update_option('miga_simple_events_db_version', $newVersion);
 }


 function miga_simple_events_enqueue()
 {
     wp_register_style("miga_simple_events_styles2", plugins_url("styles/main.css", __FILE__));
     wp_enqueue_style("miga_simple_events_styles2");
     wp_register_style("miga_simple_events_styles", plugins_url("styles/editor.css", __FILE__));
     wp_enqueue_style("miga_simple_events_styles");
     wp_register_script("miga_simple_events_editor_script", plugins_url("scripts/editor.js", __FILE__), ["wp-i18n"], "", true);
     wp_enqueue_script("miga_simple_events_editor_script");
     wp_localize_script("miga_simple_events_editor_script", "miga_simple_events", [
        "miga_nonce" => wp_create_nonce("miga_nonce"),
        "wp_url" => admin_url("admin-ajax.php"),
     ]);
 }

 function mytheme_enqueue_style()
 {
     wp_register_style("miga_simple_events_styles1", plugins_url("styles/main.css", __FILE__));
     wp_enqueue_style('miga_simple_events_styles1', get_stylesheet_uri());
     wp_register_style("miga_simple_events_styles2", plugins_url("styles/theme1.css", __FILE__));
     wp_enqueue_style('miga_simple_events_styles2', get_stylesheet_uri());
     wp_register_style("miga_simple_events_styles3", plugins_url("styles/theme2.css", __FILE__));
     wp_enqueue_style('miga_simple_events_styles3', get_stylesheet_uri());
     wp_register_style("miga_simple_events_styles4", plugins_url("styles/editor.css", __FILE__));
     wp_enqueue_style('miga_simple_events_styles4', get_stylesheet_uri());
 }

 add_action('wp_enqueue_scripts', 'mytheme_enqueue_style');

 function miga_simple_events_add_page()
 {
     add_submenu_page(
         "options-general.php",
         __("Simple Events", "miga_simple_events"),
         __("Simple Events", "miga_simple_events"),
         "manage_options",
         "miga_simple_events-page",
         "miga_simple_events_page",
         100
     );
 }

 function miga_simple_events_page()
 {
     $default_tab = null;
     $tab = isset($_GET['tab']) ? $_GET['tab'] : $default_tab;
     ?>

     <div class="wrap">
         <!-- Print the page title -->
        <h1>Simple events <a class="button button-primary" href="options-general.php?page=miga_simple_events-page"><?=__('new item', 'simple-event-list-for-elementor');?></a></h1>
         <!-- Here are our tabs -->
         <nav class="nav-tab-wrapper">
           <a href="?page=miga_simple_events-page" class="nav-tab <?php if($tab===null):?>nav-tab-active<?php endif; ?>">Events</a>
           <a href="?page=miga_simple_events-page&tab=help" class="nav-tab <?php if($tab==='help'):?>nav-tab-active<?php endif; ?>"><?=__('Help', 'simple-event-list-for-elementor');?></a>
         </nav>

         <div class="tab-content">
         <?php switch($tab) :
             case 'help':
                 require "widget/includes/backend_page_help.php";
                 break;
             default:
                 require "widget/includes/backend_page.php";
                 break;
         endswitch; ?>
         </div>
       </div>
	<?php

 }

 require("widget/includes/frontend_output.php");

 add_action("admin_enqueue_scripts", "miga_simple_events_enqueue");
 add_action("admin_menu", "miga_simple_events_add_page", 999);
 add_shortcode('miga_simple_events', 'miga_simple_events_shortcode');

 use Elementor\Plugin;

 add_action('init', static function () {
     if (! did_action('elementor/loaded')) {
         return false;
     }
     require_once(__DIR__ . '/widget/simple_events.php');
     \Elementor\Plugin::instance()->widgets_manager->register_widget_type(new \MIGA_SimpleEventWidget());
     load_plugin_textdomain( 'simple-event-list-for-elementor', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' );
 });

 add_action('admin_init', static function () {
     miga_simple_events_db_update();
 });
