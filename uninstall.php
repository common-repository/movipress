<?php
/**
 * Movipress Uninstall
 *
 * Uninstalling Movipress deletes "mpio_%" options and meta_keys.
 *
 * @author 		Movipress Ltd.
 * @version     1.0
 */	

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN') || !WP_UNINSTALL_PLUGIN ||
	dirname( WP_UNINSTALL_PLUGIN ) != dirname( plugin_basename( __FILE__ ))){
	status_header( 404 );
	exit;
}

// Perform deletion for multisite
if(!is_multisite()){
	mpio_delete_options();
}else{
    $sites=get_sites();
    foreach ($sites as $site){
	    if(!wp_is_large_network()){
	        switch_to_blog($site->blog_id);
	        mpio_delete_options();
	        restore_current_blog();
	    }
	}
}

// Deletes Movipress options and meta_keys from different tables
function mpio_delete_options(){
	global $wpdb;

	// Delete Movipress options from wp_options table
	$wpdb->query("DELETE FROM $wpdb->options WHERE option_name LIKE 'mpio_%'");
	
	// Delete Movipress post_meta from wp_postmeta table	
	$wpdb->query("DELETE FROM $wpdb->postmeta WHERE meta_key LIKE '_mpio_%'");

	// Delete Movipress term_meta_meta from wp_termmeta table
	$wpdb->query("DELETE FROM $wpdb->termmeta WHERE meta_key LIKE '_mpio_%'");

	// Delete Movipress author_meta from wp_usermeta table
	$wpdb->query("DELETE FROM $wpdb->usermeta WHERE meta_key LIKE 'mpio_%'");
}
