<?php
/**
 * Movipress menu.php
 *
 * Manage the plugin menus and pages.
 *
 * @author 		Movipress Ltd.
 * @version     1.0
 */	
function mpio_admin_menu(){	
	// Main menu	
	$top_menu = 'mpio_dashboard';
	add_menu_page( '', 'Movipress', 'manage_options', $top_menu, 'mpio_dashboard_callback','dashicons-smartphone');

	// Menu Dashboard
	add_submenu_page( $top_menu, 'Movipress', __('Dashboard', 'movipress'), 'manage_options', $top_menu, 'mpio_dashboard_callback');
	
	// Menu Notifications
	add_submenu_page( $top_menu, 'Movipress', __('Appearance', 'movipress'),  'manage_options', 'mpio_appearance', 'mpio_appearance_callback');  
	
	// Menu Advertisement
	add_submenu_page( $top_menu, 'Movipress', __('Advertisements', 'movipress'),  'manage_options', 'mpio_ads', 'mpio_ads_callback');		
}
add_action('admin_menu', 'mpio_admin_menu');


/*
 * Submenu Dashboard
 */
function mpio_dashboard_callback(){
    echo '<div class="wrap mpio-logo"><h1>'.__('Dashboard', 'movipress').'</h1>';
	settings_errors();
	
	$tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'general';
	
	echo '<h2 class="nav-tab-wrapper">
    		<a href="?page=mpio_dashboard&tab=general" class="nav-tab ';
    			if($tab=='general')echo 'nav-tab-active'; echo'">'.__('General', 'movipress').'</a>
    		<a href="?page=mpio_dashboard&tab=navigation" class="nav-tab ';
    			if($tab=='navigation')echo 'nav-tab-active'; echo'">'.__('Application Menu', 'movipress').'</a>	
			<a href="?page=mpio_dashboard&tab=notifications" class="nav-tab ';
    			if($tab=='notifications')echo 'nav-tab-active'; echo'">'.__('Notifications', 'movipress').'</a>
    		<a href="?page=mpio_dashboard&tab=app_links" class="nav-tab ';
    			if($tab=='app_links')echo 'nav-tab-active'; echo'">'.__('App Links', 'movipress').'</a></h2>';
	
	switch($tab){
		case 'general':
			echo '<form method="post" action="options.php">';
			settings_fields('mpio_general');
			do_settings_sections('mpio_general');
			submit_button();
			echo '</form></div><div class="mpio-bottom"><p>'.__('If you like', 'movipress').' Movipress, <a href="https://wordpress.org/support/plugin/movipress/reviews/?rate=5#new-post" target="_blank">'.__('please give us a positive review', 'movipress').'</a>.</p><div class="dashicons-before dashicons-smiley"></div></div>';
			break;
		case 'navigation':
			mpio_navigation_callback();
			break;
		case 'notifications':
			echo '<form method="post" action="options.php">';
			settings_fields( 'mpio_notifications' );
			do_settings_sections( 'mpio_notifications' );
			submit_button();
			echo '</form>';
			mpio_notifications_section_instructions_callback();
			echo '</div>';
			break;		
		case 'app_links':
			echo '<form method="post" action="options.php">';		
			settings_fields('mpio_app_links');	  
			do_settings_sections('mpio_app_links');
			submit_button();
			echo '</form></div>';
			break;	
	}    
}

function mpio_update_general_options( $old_value, $new_value ){
	mpio_increase_version('general');
}
add_action( 'update_option_mpio_general_options', 'mpio_update_general_options', 10, 2 );



/*
 * Submenu Appearance
 */
function mpio_appearance_callback(){   
    echo '<div class="wrap mpio-logo"><h1>'.__('Appearance Options', 'movipress').'</h1>';
	settings_errors();
    
	$tab = isset( $_GET[ 'tab' ] ) ? $_GET[ 'tab' ] : 'default';
	
	echo '<h2 class="nav-tab-wrapper">
			<a href="?page=mpio_appearance&tab=default" class="nav-tab ';if($tab=='default')echo 'nav-tab-active'; echo'">'.__('Default templates', 'movipress').'</a>
			<a href="?page=mpio_appearance&tab=list" class="nav-tab ';if($tab=='list')echo 'nav-tab-active'; echo'">'.__('List templates', 'movipress').'</a>
			<a href="?page=mpio_appearance&tab=article" class="nav-tab ';if($tab=='article')echo 'nav-tab-active'; echo'">'.__('Article templates', 'movipress').'</a>
		  </h2>';
		
	switch($tab){
		case 'default':
			echo '<form method="post" action="options.php">';
			settings_fields('mpio_general_templates');	  
			do_settings_sections('mpio_general_templates');
			submit_button();
			echo '</form>';
			break;
		case 'list':
			include_once(MOVIPRESS__PLUGIN_DIR.'admin/menu/appearance/list-templates.php');
			break;
		case 'article':
			include_once(MOVIPRESS__PLUGIN_DIR.'admin/menu/appearance/article-templates.php');  
			break;	
	}	
    echo '</div>';
    
}

// Templates version number increases
function mpio_general_templates_update_options( $old_value, $new_value ){
	mpio_increase_version('templates');
}
add_action( 'update_option_mpio_general_templates_options', 'mpio_general_templates_update_options', 10, 2 );



// Save the template
function mpio_any_view_update_options($new_value, $new_template_name, $this_templates_option, $init_templates){
	if(!empty($new_template_name) && (($new_template_name!='default' && $new_template_name!='') || $init_templates)){
		$templates = get_option($this_templates_option);		
	    if ($templates!==false){
		    $templates[$new_template_name] = $new_value;
	    	update_option($this_templates_option, $templates);
		}else{
			$templates[$new_template_name] = $new_value;
	    	add_option($this_templates_option, $templates);
		}
		mpio_increase_version('templates');
	}
}

// Saving checkboxes
function mpio_list_view_update_options($old_value, $new_value ){
	if(!isset($_POST['template_action'])){
		$new_template_name = $new_value['list_template_name'];
		$this_templates_option = 'mpio_list_templates';
		$new_value['show_statusbar'] = array_key_exists('show_statusbar',$new_value) ? true : false;
		$new_value['item_allow_mixed'] = array_key_exists('item_allow_mixed',$new_value) ? true : false;
		$new_value['item_custom_border'] = array_key_exists('item_custom_border',$new_value) ? true : false;
		$new_value['item_show_title'] = array_key_exists('item_show_title',$new_value) ? true : false;
		$new_value['item_show_author'] = array_key_exists('item_show_author',$new_value) ? true : false;
		$new_value['item_show_date'] = array_key_exists('item_show_date',$new_value) ? true : false;
		$new_value['item_show_excerpt'] = array_key_exists('item_show_excerpt',$new_value) ? true : false;
		mpio_any_view_update_options($new_value, $new_template_name, $this_templates_option, false);
	}
}
add_action( 'update_option_mpio_list_view_options', 'mpio_list_view_update_options', 10, 2 );


// Saving checkboxes
function mpio_single_view_update_options( $old_value, $new_value ){
	if(!isset($_POST['template_action'])){
		$new_template_name = $new_value['single_template_name'];
		$this_templates_option = 'mpio_single_templates';
		$new_value['single_show_statusbar'] = array_key_exists('single_show_statusbar',$new_value) ? true : false;
		$new_value['single_show_toolbar'] = array_key_exists('single_show_toolbar',$new_value) ? true : false;
		$new_value['single_show_featured_img'] = array_key_exists('single_show_featured_img',$new_value) ? true : false;
		$new_value['single_show_title'] = array_key_exists('single_show_title',$new_value) ? true : false;
		$new_value['single_show_date'] = array_key_exists('single_show_date',$new_value) ? true : false;
		$new_value['single_show_author'] = array_key_exists('single_show_author',$new_value) ? true : false;
		$new_value['single_show_excerpt'] = array_key_exists('single_show_excerpt',$new_value) ? true : false;
		$new_value['single_show_content'] = array_key_exists('single_show_content',$new_value) ? true : false;
		$new_value['single_show_categories'] = array_key_exists('single_show_categories',$new_value) ? true : false;
		$new_value['single_include_tags'] = array_key_exists('single_include_tags',$new_value) ? true : false;
		$new_value['single_categories_random_colors'] = array_key_exists('single_categories_random_colors',$new_value) ? true : false;
		$new_value['single_show_tags'] = array_key_exists('single_show_tags',$new_value) ? true : false;
		$new_value['single_tags_random_colors'] = array_key_exists('single_tags_random_colors',$new_value) ? true : false;
		$new_value['single_show_authors'] = array_key_exists('single_show_authors',$new_value) ? true : false;
		$new_value['single_show_comments'] = array_key_exists('single_show_comments',$new_value) ? true : false;
		$new_value['single_show_related'] = array_key_exists('single_show_related',$new_value) ? true : false;	
		mpio_any_view_update_options($new_value, $new_template_name, $this_templates_option, false);
	}
}
add_action( 'update_option_mpio_article_view_options', 'mpio_single_view_update_options', 10, 2 );


/*
 * Submenu Advertisement
 */
function mpio_ads_callback(){ 
    echo '<div class="wrap mpio-logo"><h1>Advertisement Options</h1>';
	settings_errors();
    echo '<form method="post" action="options.php">';
	settings_fields( 'mpio_ads' );
	do_settings_sections( 'mpio_ads' );
	submit_button();
    echo '</form></div>';
}

function mpio_update_ads_options( $old_value, $new_value ){
	mpio_increase_version('ads');
}
add_action( 'update_option_mpio_ads_options', 'mpio_update_ads_options', 10, 2 );


// Deep links/App Links alternate Metatag
function mpio_deep_link_metatag(){
	$options=get_option('mpio_app_links_options');
	if($options['use_deep_links']){
		if((is_home()&&$options['in_home']) || (is_single()&&$options['in_posts']) || (is_page()&&$options['in_pages']) ||
			(is_category()&&$options['in_categories']) || (is_tag()&&$options['in_tags']) || (is_author()&&$options['in_authors'])){
				global $wp;
				$current_url = home_url(add_query_arg(array(),$wp->request));
				$general = get_option('mpio_general_options');
				if($general['is_https']){
					$current_url = str_replace("s:/", "", $current_url);	
				}else{
					$current_url = str_replace(":/", "", $current_url);				
				}
				$package=$options['app_package_name'];
				echo "<link rel='alternate' href='android-app://$package/$current_url/' />";
		}
	}
}
add_action('wp_head', 'mpio_deep_link_metatag');