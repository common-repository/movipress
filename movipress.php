<?php
/*
 * Plugin Name: Movipress
 * Plugin URI: https://movipress.com
 * Description: Movipress is a service which enables you to convert your site into a <strong>native Android app</strong>. Create the app via our site and manage it from the plugin.
 * Author: Movipress Ltd.
 * Version: 1.2.1
 * Author URI: https://movipress.com
 * License: GPL2+
 * Text Domain: movipress
 * Domain Path: /languages
 */
 

/* !1. DEFINE */
defined('ABSPATH') OR exit;
define('MOVIPRESS__MINIMUM_WP_VERSION', '4.7');
define('MOVIPRESS__PLUGIN_DIR', plugin_dir_path( __FILE__ ));
define('MOVIPRESS__PLUGIN_URL', plugin_dir_url( __FILE__ ));
define('MOVIPRESS__PLUGIN_FILE', __FILE__);


/* !2. REQUIRE */
require_once(MOVIPRESS__PLUGIN_DIR.'admin/options.php');
require_once(MOVIPRESS__PLUGIN_DIR.'admin/menu/menu.php');
require_once(MOVIPRESS__PLUGIN_DIR.'admin/menu/dashboard/app-menu.php');
require_once(MOVIPRESS__PLUGIN_DIR.'admin/metabox/post-metabox.php');
require_once(MOVIPRESS__PLUGIN_DIR.'admin/metabox/term-user-templates.php');
require_once(MOVIPRESS__PLUGIN_DIR.'admin/notifications.php');			
require_once(MOVIPRESS__PLUGIN_DIR.'admin/rest-api/json-settings.php');				
require_once(MOVIPRESS__PLUGIN_DIR.'admin/rest-api/versions.php');


/* !3. SCRIPTS & STYLES */
function mpio_admin_scripts(){

	$dep1=array('jquery', 'jquery-ui-tabs');
	$dep2=array('jquery', 'jquery-ui-accordion', 'jquery-ui-tabs', 'jquery-ui-sortable');
	$dep3=array('jquery', 'jquery-ui-accordion', 'wp-color-picker', 'jquery-ui-spinner');

	wp_register_script('mpio-metabox-js', MOVIPRESS__PLUGIN_URL.'assets/js/metabox.js', $dep1, '', true);
	wp_register_script('mpio-menu-js', MOVIPRESS__PLUGIN_URL.'assets/js/app-menu.js', $dep2, '', true);
	wp_register_script('mpio-list-js', MOVIPRESS__PLUGIN_URL.'assets/js/activity-list.js', $dep3, '', true);
	wp_register_script('mpio-single-js', MOVIPRESS__PLUGIN_URL.'assets/js/activity-single.js', $dep3, '', true);

	wp_register_style('mpio-jquery-ui', MOVIPRESS__PLUGIN_URL.'assets/css/jquery-ui.css');
	wp_enqueue_style('mpio-phone-css', MOVIPRESS__PLUGIN_URL.'assets/css/phone.css');
	wp_enqueue_style('mpio-admin-css', MOVIPRESS__PLUGIN_URL.'assets/css/admin.css', array('mpio-jquery-ui'));
}
add_action( 'admin_enqueue_scripts', 'mpio_admin_scripts');



// Checks the WP version and register the action for deactivate the plugin.
function mpio_init(){
	global $wp_version;
	if($wp_version < MOVIPRESS__MINIMUM_WP_VERSION){
		
		function mpio_deactivate() {
			deactivate_plugins( plugin_basename( __FILE__ ) );
       	}
	   	add_action( 'admin_init', 'mpio_deactivate' );
	   	
		function mpio_admin_notice() {
           	$mpio_plugin = __( 'Movipress', 'movipress' );
			$mpio_wp_version = __( 'Wordpress 4.7', 'movipress' );
			echo '<div class="error"><p>' . sprintf( __( '%1$s requires %2$s to function correctly. Please update to %2$s or superior before activating %1$s. For now, the plugin has been deactivated.', 'movipress' ), '<strong>' . esc_html( $mpio_plugin ) . '</strong>', '<strong>' . esc_html( $mpio_wp_version ) . '</strong>' ). '</p></div>';
			
			if(isset($_GET['activate'])) unset($_GET['activate']);
		}
		add_action( 'admin_notices', 'mpio_admin_notice' );
    }
}
add_action('plugins_loaded','mpio_init');


// Initialize settings
function mpio_perform_activation(){
	require_once(MOVIPRESS__PLUGIN_DIR.'admin/init-data.php');
	mpio_init_data();
}

// Check if it is a multisite network
function mpio_on_activation($network_wide){
    if(current_user_can('activate_plugins')){
	    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
	    check_admin_referer("activate-plugin_{$plugin}");
	
		if ( is_multisite() && $network_wide ) {
	
			$sites=get_sites();
			foreach($sites as $site){
				switch_to_blog($site->blog_id);
				mpio_perform_activation();
				restore_current_blog();
			}		
		}else{
			mpio_perform_activation();
		}
	}
}
register_activation_hook(MOVIPRESS__PLUGIN_FILE, 'mpio_on_activation' );



// Deactivate plugin
function mpio_on_deactivation(){
	if(current_user_can('activate_plugins')){

	    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
	    check_admin_referer("deactivate-plugin_{$plugin}");
	}
}
register_deactivation_hook(MOVIPRESS__PLUGIN_FILE, 'mpio_on_deactivation' );
