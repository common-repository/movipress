<?php
/**
 * Movipress versions.php
 *
 * Manage versions values for keep synchronization with the app
 *
 * @author 		Movipress Ltd.
 * @version     1.0
 */
	

// Increase the version of... whatever
function mpio_increase_version($option_name){
	$versions = get_option('mpio_versions');
	if ($versions !== false){
		$versions[$option_name] = $versions[$option_name] == 999 ? 1 : $versions[$option_name]+1;
    	update_option('mpio_versions', $versions);
    	wp_cache_delete('mpio_versions');
	}else{
    	mpio_init_versions();
	}
}

	
// Increase the permalinks version number
function mpio_increase_permalinks_version(){
	mpio_increase_version('permalinks');
}
add_action('update_option_permalink_structure', 'mpio_increase_permalinks_version');
add_action('update_option_category_base', 'mpio_increase_permalinks_version');
add_action('update_option_tag_base', 'mpio_increase_permalinks_version');


// Increase the category version number
function mpio_increase_category_version(){
	mpio_increase_version('categories');
}
add_action('create_category', 'mpio_increase_category_version');
add_action('edit_category', 'mpio_increase_category_version');
add_action('delete_category', 'mpio_increase_category_version');


// Increase the tags version number
function mpio_increase_tags_version(){
	mpio_increase_version('tags');
}
add_action('create_post_tag', 'mpio_increase_tags_version');
add_action('edit_post_tag', 'mpio_increase_tags_version');
add_action('delete_post_tag', 'mpio_increase_tags_version');


// Increase the users version number
function mpio_increase_users_version(){
	mpio_increase_version('users');
}
add_action('user_register', 'mpio_increase_users_version');
add_action('personal_options_update', 'mpio_increase_users_version');
add_action('edit_user_profile_update', 'mpio_increase_users_version');
add_action('delete_user', 'mpio_increase_users_version');
