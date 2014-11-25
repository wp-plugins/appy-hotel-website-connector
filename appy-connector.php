<?php 
/**
 * @package AppyConnector
 */
/*
Plugin Name: AppyConnector
Plugin URI: http://appyhotel.com
Description:  <p>Thanks for installing <b>Appy Hotel Website Connector</b>.</p><p> Next steps: 1) Install your <a href="http://www.appyhotel.com/appy-hotel-website-connector" target="_blank">hotel theme</a> next. 2) Head on to the <a href='admin.php?page=appy_connector_account-settings'>settings page</a> and enter your hotel ID and API key.
Version: 1.0
Author: AppyHotel
Author URI: http://appyhotel.com
License: GPL2
*/

/*
This program is free software; you can redistribute it and/or
modify it under the terms of the GNU General Public License
as published by the Free Software Foundation; either version 2
of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received aadd copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * Make sure we don't expose any info if called directly
 *
 */
 if ( !function_exists( 'add_action' ) ) {
  echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
  exit;
}


/**
 * Appy configuration.
 *
 * Don't change those unless you know what you are doing. 
 * It might break functionnalities and make the plugin unusable.
 */

/**
 * Current version of the plugin.
 * @var string
 *
 * @since appy_connector (1.0.0)
 */
define('APPY_CONNECTOR_VERSION', '1.0.0');

/**
 * The url of AppyHotel API (including the version).
 * @var string
 *
 * @since appy_connector (1.0.0)
 */
define('APPY_API_URL', 'http://api.appyhotel.com/v4/web');

/**
 * The tenant used to query the API. Only 'hotels' available for now.
 * @var string
 *
 * @since appy_connector (1.0.0)
 */
define('APPY_API_TENANT', 'apps');

/**
 * The name of the table used to store the hotel's data.
 * @var string
 *
 * @since appy_connector (1.0.0)
 */
define('APPY_TABLE_NAME', 'appy_apps');


if(!defined('APPY_ASSET_DIR')) define('APPY_ASSET_DIR', plugins_url( '/assets/' , __FILE__ ));

/**
 * Loads the plugin api file containing the methods used to 
 * access hotel's data.
 *
 * @since appy_connector (1.0.0)
 */
require_once(dirname(__FILE__) . '/appy-translate.php');
require_once(dirname(__FILE__) . '/appy-plugin-api.php');
require_once(dirname(__FILE__) . '/appy-wp-content.php');
require_once(dirname(__FILE__) . '/appy-wp-shortcodes.php');

/**
 * Appy Connector settings registration.
 */

/**
 * Register settings 'appy_options' containing the following fields :
 * @var string appy_id
 * @var string appy_api_key
 *
 * @since appy_connector (1.0.0)
 */
function register_appy_settings() {
  register_setting( 'appy_settings_group', 'appy_options' );
} 
add_action( 'admin_init', 'register_appy_settings' );

/**
 * Install/Uninstall hooks.
 */

/**
 * Appy Connector install.
 * 
 * Create a new table in order to store the hotel's data. 
 * Run when the plugin is activated. If the table is already existing, don't
 * do anything.
 *
 * @since appy_connector (1.0.0)
 */
function appy_install() {
  global $wpdb;

  $table_name = $wpdb->prefix . APPY_TABLE_NAME;

  $sql = "CREATE TABLE $table_name (
    appy_id char(10) NOT NULL,
    time datetime DEFAULT '0000-00-00 00:00:00' NOT NULL,    
    content LONGTEXT NOT NULL,
    binary_content LONGBLOB NOT NULL,
    UNIQUE KEY (appy_id)
  );";

  require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
  dbDelta( $sql ); 

  appy_create_categories();
}
register_activation_hook( __FILE__, 'appy_install' );

/**
 * Appy Connector uninstall.
 * 
 * Delete the table used to store hotel's data. 
 * Unregister appy settings.
 *
 * @since appy_connector (1.0.0)
 */
function appy_uninstall() {
  unregister_setting( 'appy_settings_group', 'appy_options' );

  global $wpdb;
  $table_name = $wpdb->prefix . APPY_TABLE_NAME;

  $sql = "DROP TABLE IF EXISTS $table_name;";
  if(!$wpdb->query($sql)) {
    die("An unexpected error occured.".$wpdb->last_error);
  }

  $sql_options = 'delete from wp_options where option_name like "appy_options";';
  if(!$wpdb->query($sql_options)) {
    die("An unexpected error occured.".$wpdb->last_error);
  }

}
register_deactivation_hook( __FILE__, 'appy_uninstall' );

/**
 * Appy Connector settings getters.
 */

function appy_config_set() {
  return appy_get_key() && appy_id();
}

/**
 * Appy Settings fields : appy_pride_enabled.
 *
 * Check whether or not appy pride section options is enabled
 *
 * @since appy_connector (1.0.0)
 *
 * @return boolean True if the appy pride section option is enabled, False otherwise
 */
function appy_get_pride_enabled() {
  $opts=get_option('appy_options') ;
  return $opts['appy_pride_enabled'];
}

function appy_get_blog_enabled() {
  $opts=get_option('appy_options') ;
  return $opts['appy_blog_enabled'];
}

function appy_get_facebook_widget_enabled() {
  $opts=get_option('appy_options') ;
  return $opts['appy_facebook_widget_enabled'];
}

function appy_get_twitter_widget_enabled() {
  $opts=get_option('appy_options') ;
  return $opts['appy_twitter_widget_enabled'];
}

function appy_get_api_url() {
  //return get_option('appy_options')['appy_api_url'];
  return APPY_API_URL;
}

/**
 * Appy Settings fields : appy_api_key.
 *
 * Retrieve the api key defined by the user in Appy Connector settings page.
 * This value is used to query AppyHotel's API.
 *
 * @since appy_connector (1.0.0)
 *
 * @return string The actual value if existing, null else
 */
function appy_get_key() {
  $opts=get_option('appy_options') ;
  return $opts['appy_api_key'];
}

/**
 * Appy Settings fields : appy_id.
 *
 * Retrieve the hotel id defined by the user in Appy Connector settings page.
 * This value is used to get the hotel's data.
 *
 * @since appy_connector (1.0.0)
 *
 * @return string The actual value if existing, null else
 */
function appy_id() {
  $opts=get_option('appy_options') ;
  $appy_id = ( is_null($opts['appy_id']) ) ? $_POST['appy_options[appy_id]'] : $opts['appy_id'] ;
  return $appy_id ;
}

/**
 * Appy Settings fields : appy_booking_enabled.
 *
 * Check whether or not appy booking options is enabled
 *
 * @since appy_connector (1.0.0)
 *
 * @return boolean True if the appy booking option is enabled, False otherwise
 */
function appy_booking_enabled() {
  //return get_option('appy_options')['appy_booking_enabled'] == "appy_booking_enabled";
  return false;
}

/**
 * Pulling logic methods.
 */

/**
 * Appy API Connector.
 * 
 * Instantiate a new AppyApiConnector object and use it to download the last
 * hotel's data.
 *
 * @since appy_connector (1.0.0)
 *
 * @return string The error if something went wrong, null if the data was successfuly updated.
 */

function appy_download_app() {
  require_once(dirname(__FILE__) . '/api-connector.php');
  $c = new AppyApiConnector();
  $res = $c->get_app();

  set_permalink_structure();
  appy_create_menus();
  // appy_create_categories();

  return $res;
}

/**
 * Hook in Wordpress interface.
 */

/**
 * Insert the menu 'Appy Connector' in the settings menu.
 * 
 *
 * @since appy_connector (1.0.0)
 */
function appy_admin_menu() {
  $page = add_menu_page( 'Appy Connector Options', 'Appy Connector', 'manage_options', 'appy-connector-account-settings', 'appy_connector_account_settings', APPY_ASSET_DIR . 'img/AppyIcon-16x16.png') ;
  $sub1 = add_submenu_page('appy-connector-account-settings', 'Appy Connector Options', 'Account Settings', 'manage_options', 'appy-connector-account-settings') ;
  $sub2 = add_submenu_page('appy-connector-account-settings', 'Appy Connector Options', 'Application Settings', 'manage_options', 'appy-connector-application-settings', 'appy_connector_application_settings') ;

  add_action( 'admin_print_styles-' . $page, 'appy_admin_style' );
  add_action( 'admin_print_scripts-' . $page, 'appy_admin_script' );
  add_action( 'admin_print_styles-' . $sub1, 'appy_admin_style' );
  add_action( 'admin_print_scripts-' . $sub1, 'appy_admin_script' );
  add_action( 'admin_print_styles-' . $sub2, 'appy_admin_style' );
  add_action( 'admin_print_scripts-' . $sub2, 'appy_admin_script' );

   wp_register_script( 'appyGuidedScript', APPY_ASSET_DIR.'js/guided.js' );

  // intro.js
  wp_register_style( 'IntroJSstyle', APPY_ASSET_DIR.'css/introjs.min.css' );
  wp_register_style( 'IntroJSACustomStyle', APPY_ASSET_DIR.'css/guidedCustom.css' );
  wp_register_script( 'IntroJSscript', APPY_ASSET_DIR.'js/intro.min.js' );
  wp_enqueue_style('IntroJSstyle') ;
  wp_enqueue_script('IntroJSscript');

  wp_enqueue_script( 'appyGuidedScript' ) ;

}
add_action( 'admin_menu', 'appy_admin_menu' );

function appy_admin_init() {
  wp_register_style( 'appyConnectorStyle', plugins_url('style.css', __FILE__) );
  wp_register_script( 'appyConnectorScript', plugins_url( 'appy-admin.js', __FILE__ ) );
  // grid
  wp_register_style( 'gridStyle', plugins_url('assets/ugrt.css', __FILE__) );
  // fancy checkbox deps
  wp_register_style( 'checkBoxStyle', plugins_url('assets/jquery.switchButton.css', __FILE__) );
  wp_register_script( 'checkBoxScript', plugins_url( 'assets/jquery.switchButton.js', __FILE__ ) );

  // download.js
  wp_register_script( 'downloadJS', APPY_ASSET_DIR.'js/download.min.js' );
  
}
add_action('admin_init', 'appy_admin_init') ;

function bsInit() {
  //Bootstrap
  wp_register_style('bsStyle', APPY_ASSET_DIR.'bs/css/bootstrap.css') ;
  wp_register_style('bsThemeStyle', APPY_ASSET_DIR.'bs/css/bootstrap-theme.css') ;
  wp_register_script('bsScript', APPY_ASSET_DIR.'bs/js/bootstrap.min.js');

  wp_enqueue_style('bsStyle');
  wp_enqueue_style('bsThemeStyle');

  wp_enqueue_script('bsScript');
}
add_action('admin_init', 'bsInit');

function appy_admin_style() {
  wp_enqueue_style('appyConnectorStyle') ;
  wp_enqueue_style('checkBoxStyle') ;
  wp_enqueue_style('gridStyle') ;
  wp_enqueue_style('lightboxStyle') ;
}

function appy_admin_script() {
  wp_enqueue_script('jquery-ui-core');
  wp_enqueue_script('jquery-ui-widget');
  wp_enqueue_script('jquery-effects-core');
  wp_enqueue_script('checkBoxScript') ;
  //wp_enqueue_script('lightboxScript') ;
  wp_enqueue_script('downloadJS');
  wp_enqueue_script('appyConnectorScript') ;
}
/**
 * The settings page of Appy Connector. Allow a user to update the hotel's id and 
 * the API Key. Also offer a refresh button to get the last data from the API.
 *
 * @since appy_connector (1.0.0)
 */
function appy_connector_account_settings() {
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  require(dirname(__FILE__) . '/templates/appy_form_account_settings.php');
}

function appy_connector_application_settings() {
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  require_once(dirname(__FILE__) . '/templates/appy_form_application_settings.php');
}

function appy_connector_first_boot() {
  if ( !current_user_can( 'manage_options' ) )  {
    wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
  }
  require_once(dirname(__FILE__) . '/templates/appy_first_boot.php');
}


function add_query_vars($aVars) {
  $aVars[] = "culture"; // represents the language_code as shown in the URL
  return $aVars;
}
// hook add_query_vars function into query_vars
add_filter('query_vars', 'add_query_vars');

/*
 * push notifications
 */
function appy_refresh_web_client() {
  error_log("appy_refresh_web_client");
  $appy_db = appy_freshness_db();
  appy_freshness_update($appy_db, "0");
}

if(preg_match("/^\/appy_refresh_web_client/", $_SERVER['REQUEST_URI'])) {
  $secret = $_GET["secret"];
  if(sha1($_SERVER['HTTP_HOST']) == $secret) {
    add_action( 'wp_loaded', 'appy_refresh_web_client' );
  }
}

function appy_freshness_check() {
    
    $appy_db = appy_freshness_db();
    $sth = $appy_db->prepare('SELECT fresh FROM appyapps');
    $sth->execute();
    $fresh = $sth->fetchColumn();
    
    if(!$fresh) {
      appy_download_app();
      appy_freshness_update($appy_db, "1");
    }
}
add_action('wp_loaded','appy_freshness_check');

function appy_freshness_db(){
  $dsn = 'sqlite:' . dirname(__FILE__) . '/appy.sqlite' ;
  $db = new PDO($dsn);
  return $db;
}

function appy_freshness_update(&$db, $val) {
      $update = "UPDATE appyapps SET fresh=$val" ;
      $db->exec($update);
      $db=null;
}

function appy_connector_admin_notice() {
  $settings = get_option('appy_options');
  if ( !strlen($settings['appy_id']) || !strlen($settings['appy_api_key']) ) {
    echo appy_load_template("appy_connector_admin_notice") ;
  }
}
add_action( 'admin_notices', 'appy_connector_admin_notice' );