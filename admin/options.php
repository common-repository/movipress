<?php 	
/**
 * Movipress options.php
 *
 * Definitions of settings pages, sections, fields....
 *
 * @author 		Movipress Ltd.
 * @version     1.1
 */
 
function mpio_initialize_options() {
	$settings=mpio_get_settings_sections();
	$fields=mpio_get_settings_fields();			
	array_walk($settings, 'mpio_add_option_callback');	
    array_walk($settings, 'mpio_add_settings_section_callback');
    array_walk($fields, 'mpio_add_settings_field_callback');
    array_walk($settings, 'mpio_register_settings_callback');       
}add_action('admin_init', 'mpio_initialize_options');


function mpio_default_templates_section_description_callback() {
    echo '<p>'. __('Below you can link a template to each content type. The templates you\'ve created will appear in the dropdown menu.', 'movipress').'</p>';
}



function mpio_notifications_section_instructions_callback() {
	_e('<h2>Intructions:</h2><ol>
 	<li>Go to firebase.google.com.</li>
 	<li>Once in Firebase click on <strong>GO TO CONSOLE</strong>.</li>
 	<li>In console click on the desired project you\'ve created and select the <strong>project settings</strong> icon on the top left of the screen.</li>
 	<li>Select the <strong>cloud messaging</strong> tab and copy the <strong>server key</strong>.</li>
 	<li>Once copied, go back into the <strong>notification</strong> tab in the Movipress plugin <strong>Dashboard.</strong></li>
 	<li>Paste the <strong>server key</strong> you\'ve copied before and paste into the <strong>Firebase API</strong> text box.</li>
 	<li>The following checkboxes allow you to setup whether you want to activate/deactivate by default push notifications when publishing a new article or updating an already published one.</li>
 	<li>To apply the changes you\'ve made click on the <strong>save changes</strong> button.</li>
</ol>','movipress');
}
 
function mpio_empty_section_description_callback() {
}

function mpio_add_option_callback($args){
	if(false==get_option($args['section'])){  
        add_option($args['section'],'');
    }
} 
 
 
function mpio_add_settings_section_callback($args){
    add_settings_section(
        $args['section'],
        $args['title'],
        $args['description'],
        $args['page']
    );
}
 
 
function mpio_add_settings_field_callback($args) {
	add_settings_field( 
        $args['option'],          		
        $args['label'],		 	        
        'mpio_do_settings_field',   	
        $args['page'],    				
        $args['section'],    			
        array(                         	
            'type' 			=> $args['type'],
            'values_id'		=> isset($args['values_id']) ? $args['values_id'] : '',
            'section' 		=> $args['section'],
            'description' 	=> $args['description'],
            'section' 		=> $args['section'],
            'option' 		=> $args['option']
        )
    );
}


function mpio_register_settings_callback($args){
	register_setting(
        $args['page'],
        $args['section']
    );
}


//	Form selects
function mpio_form_selects($select){
	switch($select){
		case 'list_template_values':
			$templates = get_option('mpio_list_templates');
			$selects = array(array('name' => 'default',	'value' => 'default'));
			if($templates!=false){
				foreach ($templates as $key => $value){
					$s=array(array('name' => $key,	'value' => $key));
					$selects = array_merge($selects,$s);
				}
			}
			
			break;
		case 'single_template_values':
			$templates = get_option('mpio_single_templates');
			$selects = array(array('name' => 'default',	'value' => 'default'));
			if($templates!=false){
				foreach ($templates as $key => $value){
					$s=array(array('name' => $key,	'value' => $key));
					$selects = array_merge($selects,$s);
				}
			}
			
			break;
			
		case 'ad_interstitial_values':
			$selects = array(
				array('name' => __('Deactivated', 'movipress'),	'value' => '0'),
				array('name' => __('Activated', 'movipress'),		'value'	=> '1')
			);			
			break;			
		case 'ad_type_values':
			$selects = array(
				array('name' => __('Do not show', 'movipress'),	'value' => '0'),
				array('name' => 'AdMob Standard Banner',		'value'	=> '1'),
				array('name' => 'AdMob Large Banner',			'value'	=> '2'),
				array('name' => 'AdMob Medium Rectangle',		'value'	=> '3'),
				array('name' => 'AdMob Smart Banner',			'value'	=> '6')
			);			
			break;		
		case 'ad_type_fixed_values':
			$selects = array(
				array('name' => __('Do not show', 'movipress'),	'value' => '0'),
				array('name' => 'AdMob Standard Banner',		'value'	=> '1'),
				array('name' => 'AdMob Large Banner',			'value'	=> '2'),
				array('name' => 'AdMob Smart Banner',			'value'	=> '6')
			);			
			break;
		case 'item_layout_values':
			$selects = array(
				array('name' => __('Top', 'movipress'),			'value' => 'item_img_top'),
				array('name' => __('Middle', 'movipress'),		'value'	=> 'item_img_middle'),
				array('name' => __('Bottom', 'movipress'),		'value'	=> 'item_img_bottom'),
				array('name' => __('Left', 'movipress'),		'value'	=> 'item_img_left'),
				array('name' => __('Top-left', 'movipress'),	'value'	=> 'item_img_left_top'),
				array('name' => __('Right', 'movipress'),		'value'	=> 'item_img_right'),
				array('name' => __('Top-right', 'movipress'),	'value'	=> 'item_img_right_top'),
				array('name' => __('Background', 'movipress'),	'value'	=> 'item_img_background'),
				array('name' => __('No image', 'movipress'),	'value'	=> 'item_no_img')								
			);					
			break;
		case 'related_type_values':
			$selects = array(
				array('name' => __('Same tag', 'movipress'),		'value'	=> '2'),
				array('name' => __('Same category', 'movipress'),	'value'	=> '1'),
				array('name' => __('Last articles', 'movipress'),	'value' => '0'),				
				array('name' => __('Same author', 'movipress'),		'value'	=> '3')
			);			
			break;				
			
		case 'taxonomy_style_values':
			$selects = array(
				array('name' => __('In-line', 'movipress'),		'value' => '0'),
				array('name' => __('Tag cloud', 'movipress'),	'value' => '1')
			);		
			break;			
		case 'text_align_values':
			$selects = array(
				array('name' => __('left', 'movipress'),	'value' => 'left'),
				array('name' => __('center', 'movipress'),	'value' => 'center'),
				array('name' => __('right', 'movipress'),	'value'	=> 'right')
			);		
			break;
		case 'text_align_values_content':
			$selects = array(
				array('name' => __('left', 'movipress'),	'value' => 'left'),
				array('name' => __('center', 'movipress'),	'value' => 'center'),
				array('name' => __('right', 'movipress'),	'value'	=> 'right'),
				array('name' => __('justify', 'movipress'),	'value'	=> 'justify')				
			);		
			break;
		case 'toolbar_title_type_values':
			$selects = array(
				array('name' => __('Content title', 'movipress'),	'value' => 0),
				array('name' => __('Custom text', 'movipress'),		'value'	=> 1),				
			);		
			break;			
		case 'vertical_align_values':
			$selects = array(
				array('name' => __('top', 'movipress'),		'value' => 'top'),
				array('name' => __('middle', 'movipress'),	'value'	=> 'middle'),
				array('name' => __('bottom', 'movipress'),	'value'	=> 'bottom')
			);					
			break;	
	}	
	return $selects;
} 



function mpio_do_settings_field($args) {
	
   	$options = get_option($args['section']);    
   	$options[$args['option']]=isset($options[$args['option']])?$options[$args['option']]:'';
    $a='';
    
    switch($args['type']){
		case 'checkbox':
			if (!is_array($options))$options[$args['option']]=false;
			$a.='<input type="checkbox" 
				id="'.$args['option'].'" 
				name="'.$args['section'].'['.$args['option'].']" 
				value="1" '.checked(1, $options[$args['option']], false).'/>'; 
			break;
			
		case 'text':
			$a.='<input type="text"
				id="'.$args['option'].'"
				title="'.$args['description'].'"
				name="'.$args['section'].'['.$args['option'].']"
				size="25" 
				value="'.$options[$args['option']].'" />';
			break;
			
		case 'color_picker':
			$a.='<input type="text" class="mpio-color-picker"
				id="'.$args['option'].'"
				
				name="'.$args['section'].'['.$args['option'].']" 
				value="'.$options[$args['option']].'" />';
			break;
			
		case 'textarea':
	    	$a.='<textarea 
				id="'.$args['option'].'"
				name="'.$args['section'].'['.$args['option'].']" rows="3" cols="60">'
				.$options[$args['option']].'</textarea>';

			break;

		case 'textarea-narrow':
	    	$a.='<textarea 
				id="'.$args['option'].'"
				name="'.$args['section'].'['.$args['option'].']" rows="5" cols="20">'
				.$options[$args['option']].'</textarea>';

			break;
			
		case 'select':
			if (!is_array($options))$options[$args['option']]=0;
	    	$a.='<select
				id="'.$args['option'].'" 
				name="'.$args['section'].'['.$args['option'].']">';
	    	$selects=mpio_form_selects($args['values_id']);
	    	foreach($selects as $select){
				$a.='<option value="'.$select['value'].'"'.selected($options[$args['option']],$select['value'],false).'>'.$select['name'].'</option>';
	    	}
			$a.= '</select>';
			break;
		
		case 'spinner':
	    	$a.='<input 
				id="'.$args['option'].'"
				name="'.$args['section'].'['.$args['option'].']" 
				min="0" max="500" step="1" 
				value="'.$options[$args['option']].'" size="3" maxlength="3">';
			break;
			
		case 'font-select':			
			$a.='<input type="text"
				list="fonts"
				id="'.$args['option'].'"
				title="'.$args['description'].'"
				name="'.$args['section'].'['.$args['option'].']"
				size="18" 
				value="'.$options[$args['option']].'" />';
			break;		
			
		case 'page-select':		
				$argsp = array(
					    'echo'                  => false,
					    'name'                  => $args['section'].'['.$args['option'].']',
					    'id'                    => $args['option'],
					    'selected'              => $options[$args['option']]
					);
				$a.=wp_dropdown_pages($argsp);
			break;				
	}
	
	if(!empty($args['description'])){
		switch($args['type']){
			case 'checkbox':
					$a .= '<label for="'.$args['option'].'"> '.$args['description'] .'</label>'; 
				break;
			case 'color_picker':
			case 'text':
			case 'textarea':
			case 'select':
			case 'page-select':
			    	$a.='<p class="description" id="'.$args['option'].'-description">'.$args['description'].'</p>';    	
				break;
		}						
	}
    echo $a;
}
     	
	
function mpio_get_settings_sections(){
	$settings=array(
				/**
				*	GENERAL SETTINGS OPTIONS TAB
 				*/
    			'general' => array(
	    			'section' 		=> 'mpio_general_options',
	    			'title' 		=> '',
	    			'description' 	=> 'mpio_empty_section_description_callback',
	    			'page' 			=> 'mpio_general'
				),
				/**
				*	MENU SETUP TAB
 				*/
				'app_navigation' 	=> array(
	    			'section' 		=> 'mpio_navigation_menu_options',
	    			'title' 		=> '',
	    			'description' 	=> 'mpio_empty_section_description_callback',
	    			'page' 			=> 'mpio_navigation_menu'
				),
				/**
				*	NOTIFICATIONS SETTINGS TAB
 				*/
				'notifications' => array(
	    			'section' 		=> 'mpio_notifications_options',
	    			'title' 		=> '',
	    			'description' 	=> 'mpio_empty_section_description_callback',		    			
	    			'page' 			=> 'mpio_notifications'
				),
				/**
				*	ADS SETTINGS OPTION PAGE
 				*/
 				'ads' => array(
	    			'section' 		=> 'mpio_ads_options',
	    			'title' 		=> '',
	    			'description' 	=> 'mpio_empty_section_description_callback',
	    			'page' 			=> 'mpio_ads'
				),
				/**
				*	APPLINKS SETTINGS OPTION TAB
 				*/
				'app_links' 	=> array(
	    			'section' 		=> 'mpio_app_links_options',
	    			'title' 		=> '',
	    			'description' 	=> 'mpio_empty_section_description_callback',
	    			'page' 			=> 'mpio_app_links'
				),
				/**
				*	APPEARANCE GENERAL TAB
 				*/
				'general_templates' => array(
	    			'section' 		=> 'mpio_general_templates_options',
	    			'title' 		=> '',
	    			'description' 	=> 'mpio_default_templates_section_description_callback',
	    			'page' 			=> 'mpio_general_templates'
				),
				/**
				*	APPEARANCE LIST TEMPLATES TAB
 				*/
				'list_view' 	=> array(
	    			'section' 		=> 'mpio_list_view_options',
	    			'title' 		=> '',
	    			'description' 	=> 'mpio_empty_section_description_callback',
	    			'page' 			=> 'mpio_list_view'
				),
				/**
				*	APPEARANCE SINGLE TEMPLATES TAB
 				*/
				'article_view' 	=> array(
	    			'section' 		=> 'mpio_article_view_options',
	    			'title' 		=> '',
	    			'description' 	=> 'mpio_empty_section_description_callback',
	    			'page' 			=> 'mpio_article_view'
				)
			);
	return $settings;
}
	
	
	
function mpio_get_settings_fields(){
	$settings=mpio_get_settings_sections();
	$fields = array( 
	    		/**
				* General settings fields
 				*/
				'is_https' => array(
	    			'page' 			=> $settings['general']['page'],
					'section' 		=> $settings['general']['section'],
					'option' 		=> 'is_https',
					'type' 			=> 'checkbox',
					'label' 		=> __('Secure connection', 'movipress'),
					'description' 	=> __('Check this option if your site has a secure connection (https)', 'movipress')
				),
				'use_rtl_reading' => array(
	    			'page' 			=> $settings['general']['page'],
					'section' 		=> $settings['general']['section'],
					'option' 		=> 'use_rtl_reading',
					'type' 			=> 'checkbox',
					'label' 		=> __('Right to left reading', 'movipress'),
					'description' 	=> __('Check this option if your site\'s language is right-to-left reading (RTL)', 'movipress')
				),
				'gdpr' => array(
	    			'page' 			=> $settings['general']['page'],
					'section' 		=> $settings['general']['section'],
					'option' 		=> 'gdpr',
					'type' 			=> 'page-select',
					'label' 		=> __('Privacy policy', 'movipress'),
					'description' 	=> __('Select a page with your privacy policy (GPDR) -Non subscribed apps will show Movipress privacy policy-', 'movipress')
				),
				/**
				* Navigation Menu - appearance field
 				*/
    			'navigation_menu' => array(
	    			'page' 			=> $settings['app_navigation']['page'],
					'section' 		=> $settings['app_navigation']['section'],
					'option' 		=> 'navigation_menu',
					'type' 			=> 'text',
					'label' 		=> '',
					'description' 	=> ''
				),
	    		/**
				* Notifications fields
 				*/
 				'fcm_api_key' => array(
	    			'page' 			=> $settings['notifications']['page'],
					'section' 		=> $settings['notifications']['section'],
					'option' 		=> 'fcm_api_key',
					'type' 			=> 'text',
					'label' 		=> 'Firebase Server key',
					'description' 	=> __('In order to send notifications introduce your own server key', 'movipress')
				),
				'send_notification_new_post' => array(
	    			'page' 			=> $settings['notifications']['page'],
					'section' 		=> $settings['notifications']['section'],
					'option' 		=> 'send_notification_new_post',
					'type' 			=> 'checkbox',
					'label' 		=> __('When publishing', 'movipress'),
					'description' 	=> __('Tick this box to mark by default the post metabox option every time you <b><i>publish</i></b> a new post (you can always uncheck for each post)', 'movipress')
				),
				'send_notification_updated_post' => array(
	    			'page' 			=> $settings['notifications']['page'],
					'section' 		=> $settings['notifications']['section'],
					'option' 		=> 'send_notification_updated_post',
					'type' 			=> 'checkbox',
					'label' 		=> __('When updating', 'movipress'),
					'description' 	=> __('Tick this box to mark by default the post metabox option every time you <b><i>update</i></b> a published post (you can always uncheck for each post)', 'movipress')
				),
				/**
				* General settings - App Links 
 				*/
 				'use_deep_links' => array(
	    			'page' 			=> $settings['app_links']['page'],
					'section' 		=> $settings['app_links']['section'],
					'option' 		=> 'use_deep_links',
					'type' 			=> 'checkbox',
					'label' 		=> __('Activate Deep Links', 'movipress'),
					'description' 	=> __('Check this option to print the android alternate metatag in the source code of your pages', 'movipress')
				),
    			'app_package_name' => array(
	    			'page' 			=> $settings['app_links']['page'],
					'section' 		=> $settings['app_links']['section'],
					'option' 		=> 'app_package_name',
					'type' 			=> 'text',
					'label' 		=> __('App package name', 'movipress'),
					'description' 	=> __('Here type your unique Android package name')
				),
    			'in_home' => array(
	    			'page' 			=> $settings['app_links']['page'],
					'section' 		=> $settings['app_links']['section'],
					'option' 		=> 'in_home',
					'type' 			=> 'checkbox',
					'label' 		=> __('Home', 'movipress'),
					'description' 	=> 'Link desktop homepage to app homepage'
				),
    			'in_posts' => array(
	    			'page' 			=> $settings['app_links']['page'],
					'section' 		=> $settings['app_links']['section'],
					'option' 		=> 'in_posts',
					'type' 			=> 'checkbox',
					'label' 		=> __('Posts', 'movipress'),
					'description' 	=> 'Link desktop post pages to each post page in app'
				),
    			'in_pages' => array(
	    			'page' 			=> $settings['app_links']['page'],
					'section' 		=> $settings['app_links']['section'],
					'option' 		=> 'in_pages',
					'type' 			=> 'checkbox',
					'label' 		=> __('Pages', 'movipress'),
					'description' 	=> 'Link desktop pages to each page in app'
				),
    			'in_categories' => array(
	    			'page' 			=> $settings['app_links']['page'],
					'section' 		=> $settings['app_links']['section'],
					'option' 		=> 'in_categories',
					'type' 			=> 'checkbox',
					'label' 		=> __('Categories', 'movipress'),
					'description' 	=> 'Link desktop category pages to each category page in app'
				),
    			'in_tags' => array(
	    			'page' 			=> $settings['app_links']['page'],
					'section' 		=> $settings['app_links']['section'],
					'option' 		=> 'in_tags',
					'type' 			=> 'checkbox',
					'label' 		=> __('Tags', 'movipress'),
					'description' 	=> 'Link desktop tag pages to each tag page in app'
				),
    			'in_authors' => array(
	    			'page' 			=> $settings['app_links']['page'],
					'section' 		=> $settings['app_links']['section'],
					'option' 		=> 'in_authors',
					'type' 			=> 'checkbox',
					'label' 		=> __('Authors', 'movipress'),
					'description' 	=> 'Link desktop author pages to each author page in app'
				),
				/**
				*  APPEARANCE - GENERAL FIELDS
 				*/	
				'def_main_template' => array(
	    			'page' 			=> $settings['general_templates']['page'],
					'section' 		=> $settings['general_templates']['section'],
					'option' 		=> 'def_main_template',
					'type' 			=> 'select',
					'values_id' 	=> 'list_template_values',
					'label' 		=> __('Main template', 'movipress'),
					'description' 	=> ''
				),	
				'def_saved_template' => array(
	    			'page' 			=> $settings['general_templates']['page'],
					'section' 		=> $settings['general_templates']['section'],
					'option' 		=> 'def_saved_template',
					'type' 			=> 'select',
					'values_id' 	=> 'list_template_values',
					'label' 		=> __('Saved template', 'movipress'),
					'description' 	=> ''
				),	
				'def_cat_template' => array(
	    			'page' 			=> $settings['general_templates']['page'],
					'section' 		=> $settings['general_templates']['section'],
					'option' 		=> 'def_cat_template',
					'type' 			=> 'select',
					'values_id' 	=> 'list_template_values',
					'label' 		=> __('Categories template', 'movipress'),
					'description' 	=> ''
				),	
				'def_tag_template' => array(
	    			'page' 			=> $settings['general_templates']['page'],
					'section' 		=> $settings['general_templates']['section'],
					'option' 		=> 'def_tag_template',
					'type' 			=> 'select',
					'values_id' 	=> 'list_template_values',
					'label' 		=> __('Tags template', 'movipress'),
					'description' 	=> ''
				),	
				'def_author_template' => array(
	    			'page' 			=> $settings['general_templates']['page'],
					'section' 		=> $settings['general_templates']['section'],
					'option' 		=> 'def_author_template',
					'type' 			=> 'select',
					'values_id' 	=> 'list_template_values',
					'label' 		=> __('Author template', 'movipress'),
					'description' 	=> ''
				),	
				'def_search_template' => array(
	    			'page' 			=> $settings['general_templates']['page'],
					'section' 		=> $settings['general_templates']['section'],
					'option' 		=> 'def_search_template',
					'type' 			=> 'select',
					'values_id' 	=> 'list_template_values',
					'label' 		=> __('Search template', 'movipress'),
					'description' 	=> ''
				),	
				'def_single_template' => array(
	    			'page' 			=> $settings['general_templates']['page'],
					'section' 		=> $settings['general_templates']['section'],
					'option' 		=> 'def_single_template',
					'type' 			=> 'select',
					'values_id' 	=> 'single_template_values',
					'label' 		=> __('Articles template', 'movipress'),
					'description' 	=> ''
				),	
				'def_page_template' => array(
	    			'page' 			=> $settings['general_templates']['page'],
					'section' 		=> $settings['general_templates']['section'],
					'option' 		=> 'def_page_template',
					'type' 			=> 'select',
					'values_id' 	=> 'single_template_values',
					'label' 		=> __('Pages template', 'movipress'),
					'description' 	=> ''
				),
				/************************************************************
				*
				*	
				*  LISTVIEW - APPEARANCE FIELDS
				*
				*
 				*************************************************************/
 				'list_template_name' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'list_template_name',
					'type' 			=> 'text',
					'label' 		=> '',
					'description' 	=> ''
				),
				'show_statusbar' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'show_statusbar',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'list_statusbar_color' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'list_statusbar_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),    			
				'list_border' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'list_border',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'bg_color' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'bg_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				// Toolbar
    			'toolbar_color' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'toolbar_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				'toolbar_icons_color' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'toolbar_icons_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				'toolbar_title_type' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'toolbar_title_type',
					'type' 			=> 'select',
					'values_id' 	=> 'toolbar_title_type_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'toolbar_title' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'toolbar_title',
					'type' 			=> 'text',
					'label' 		=> '',
					'description' 	=> ''
				),
				'toolbar_text_font' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'toolbar_text_font',
					'type' 			=> 'font-select',
					'values_id' 	=> 'font_family_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'toolbar_text_align' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'toolbar_text_align',
					'type' 			=> 'select',
					'values_id' 	=> 'text_align_values',
					'label' 		=> '',
					'description' 	=> ''
				),
    			'toolbar_text_size' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'toolbar_text_size',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				// Item
				'item_allow_mixed' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_allow_mixed',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_custom_border' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_custom_border',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_border' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_border',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_background_color' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_background_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				// Featured image
				'item_template' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_template',
					'type' 			=> 'select',
					'values_id' 	=> 'item_layout_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_img_height' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_img_height',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_img_border' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_img_border',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_data_vertical_align' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_data_vertical_align',
					'type' 			=> 'select',
					'values_id' 	=> 'vertical_align_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				// Title
				'item_show_title' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_show_title',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_title_text_font' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_title_text_font',
					'type' 			=> 'font-select',
					'values_id' 	=> 'font_family_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_title_text_align' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_title_text_align',
					'type' 			=> 'select',
					'values_id' 	=> 'text_align_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_title_text_size' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_title_text_size',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_title_text_color' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_title_text_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				
				// Date and Author
				'item_show_author' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_show_author',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_show_date' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_show_date',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_date_text_font' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_date_text_font',
					'type' 			=> 'font-select',
					'values_id' 	=> 'font_family_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_date_text_align' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_date_text_align',
					'type' 			=> 'select',
					'values_id' 	=> 'text_align_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_date_text_size' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_date_text_size',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_date_text_color' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_date_text_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_show_excerpt' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_show_excerpt',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_excerpt_text_font' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_excerpt_text_font',
					'type' 			=> 'font-select',
					'values_id' 	=> 'font_family_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_excerpt_text_align' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_excerpt_text_align',
					'type' 			=> 'select',
					'values_id' 	=> 'text_align_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_excerpt_text_size' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_excerpt_text_size',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'item_excerpt_text_color' => array(
	    			'page' 			=> $settings['list_view']['page'],
					'section' 		=> $settings['list_view']['section'],
					'option' 		=> 'item_excerpt_text_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				/************************************************************
				*
				*	
				* Article view - appearance fields
				*
				*
 				*************************************************************/
 				// General
 				'single_template_name' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_template_name',
					'type' 			=> 'text',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_show_statusbar' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_show_statusbar',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_statusbar_color' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_statusbar_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),    			
				'single_border' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_border',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_bg_color' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_bg_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_item_color' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_item_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				// Toolbar
				'single_show_toolbar' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_show_toolbar',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_toolbar_title_type' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_toolbar_title_type',
					'type' 			=> 'select',
					'values_id' 	=> 'toolbar_title_type_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_toolbar_title' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_toolbar_title',
					'type' 			=> 'text',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_toolbar_text_font' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_toolbar_text_font',
					'type' 			=> 'font-select',
					'values_id' 	=> 'font_family_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_toolbar_text_align' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_toolbar_text_align',
					'type' 			=> 'select',
					'values_id' 	=> 'text_align_values',
					'label' 		=> '',
					'description' 	=> ''
				),
    			'single_toolbar_text_size' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_toolbar_text_size',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
    			'single_toolbar_color' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_toolbar_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_toolbar_icons_color' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_toolbar_icons_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				// Featured image
    			'single_show_featured_img' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_show_featured_img',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_img_height' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_img_height',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_img_border' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_img_border',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				// Title
				'single_show_title' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_show_title',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_title_text_font' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_title_text_font',
					'type' 			=> 'font-select',
					'values_id' 	=> 'font_family_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_title_text_align' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_title_text_align',
					'type' 			=> 'select',
					'values_id' 	=> 'text_align_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_title_text_size' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_title_text_size',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_title_text_color' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_title_text_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				// Date and Author
				'single_show_author' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_show_author',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_show_date' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_show_date',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_date_text_font' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_date_text_font',
					'type' 			=> 'font-select',
					'values_id' 	=> 'font_family_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_date_text_align' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_date_text_align',
					'type' 			=> 'select',
					'values_id' 	=> 'text_align_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_date_text_size' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_date_text_size',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_date_text_color' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_date_text_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),

				// Excerpt
				'single_show_excerpt' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_show_excerpt',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_excerpt_text_font' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_excerpt_text_font',
					'type' 			=> 'font-select',
					'values_id' 	=> 'font_family_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_excerpt_text_align' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_excerpt_text_align',
					'type' 			=> 'select',
					'values_id' 	=> 'text_align_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_excerpt_text_size' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_excerpt_text_size',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_excerpt_text_color' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_excerpt_text_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),

				// Content
				'single_show_content' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_show_content',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_content_text_font' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_content_text_font',
					'type' 			=> 'font-select',
					'values_id' 	=> 'font_family_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_content_text_align' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_content_text_align',
					'type' 			=> 'select',
					'values_id' 	=> 'text_align_values_content',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_content_text_size' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_content_text_size',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_content_text_color' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_content_text_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_content_header' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_content_header',
					'type' 			=> 'textarea-narrow',
					'label' 		=> '',
					'description' 	=> ''
				),
				// Categories
				'single_show_categories' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_show_categories',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_include_tags' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_include_tags',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_categories_title' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_categories_title',
					'type' 			=> 'text',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_categories_separator' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_categories_separator',
					'type' 			=> 'text',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_categories_ending' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_categories_ending',
					'type' 			=> 'text',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_categories_style' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_categories_style',
					'type' 			=> 'select',
					'values_id' 	=> 'taxonomy_style_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_categories_text_font' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_categories_text_font',
					'type' 			=> 'font-select',
					'values_id' 	=> 'font_family_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_categories_text_align' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_categories_text_align',
					'type' 			=> 'select',
					'values_id' 	=> 'text_align_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_categories_text_size' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_categories_text_size',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_categories_text_color' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_categories_text_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_categories_random_colors' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_categories_random_colors',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_categories_color' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_categories_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				// Tags
				'single_show_tags' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_show_tags',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_tags_title' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_tags_title',
					'type' 			=> 'text',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_tags_separator' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_tags_separator',
					'type' 			=> 'text',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_tags_ending' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_tags_ending',
					'type' 			=> 'text',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_tags_style' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_tags_style',
					'type' 			=> 'select',
					'values_id' 	=> 'taxonomy_style_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_tags_text_font' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_tags_text_font',
					'type' 			=> 'font-select',
					'values_id' 	=> 'font_family_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_tags_text_align' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_tags_text_align',
					'type' 			=> 'select',
					'values_id' 	=> 'text_align_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_tags_text_size' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_tags_text_size',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_tags_text_color' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_tags_text_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_tags_random_colors' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_tags_random_colors',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_tags_color' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_tags_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				// Author card
				'single_show_authors' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_show_authors',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_authors_text_font' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_authors_text_font',
					'type' 			=> 'font-select',
					'values_id' 	=> 'font_family_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_authors_text_align' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_authors_text_align',
					'type' 			=> 'select',
					'values_id' 	=> 'text_align_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_authors_text_size' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_authors_text_size',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_authors_text_color' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_authors_text_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_authors_background' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_authors_background',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				// Comments button
				'single_show_comments' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_show_comments',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_comments_text' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_comments_text',
					'type' 			=> 'text',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_to_comment_text' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_to_comment_text',
					'type' 			=> 'text',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_comments_text_font' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_comments_text_font',
					'type' 			=> 'font-select',
					'values_id' 	=> 'font_family_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_comments_text_size' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_comments_text_size',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_comments_text_color' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_comments_text_color',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_comments_background' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_comments_background',
					'type' 			=> 'color_picker',
					'label' 		=> '',
					'description' 	=> ''
				),
				// Related articles
				'single_show_related' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_show_related',
					'type' 			=> 'checkbox',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_related_type' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_related_type',
					'type' 			=> 'select',
					'values_id' 	=> 'related_type_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_related_number' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_related_number',
					'type' 			=> 'spinner',
					'label' 		=> '',
					'description' 	=> ''
				),
				'single_related_style' => array(
	    			'page' 			=> $settings['article_view']['page'],
					'section' 		=> $settings['article_view']['section'],
					'option' 		=> 'single_related_style',
					'type' 			=> 'select',
					'values_id' 	=> 'list_template_values',
					'label' 		=> '',
					'description' 	=> ''
				),
				/**
				* Advertisement fields
 				*/
    			'to_show_ads' => array(
	    			'page' 			=> $settings['ads']['page'],
					'section' 		=> $settings['ads']['section'],
					'option' 		=> 'to_show_ads',
					'type' 			=> 'checkbox',
					'label' 		=> __('Show Ads', 'movipress'),
					'description' 	=> 'Check/uncheck this option depending on whether you want to display ads'
				),				
				'admob_pub_id' => array(
	    			'page' 			=> $settings['ads']['page'],
					'section' 		=> $settings['ads']['section'],
					'option' 		=> 'admob_pub_id',
					'type' 			=> 'text',
					'label' 		=> 'AdMob pub ID',
					'description' 	=> __('Insert your AdMob™ pub ID', 'movipress')
				),
				'main_interstitial_type' => array(
	    			'page' 			=> $settings['ads']['page'],
					'section' 		=> $settings['ads']['section'],
					'option' 		=> 'main_interstitial_type',
					'type' 			=> 'select',
					'values_id' 	=> 'ad_interstitial_values',
					'label' 		=> __('Interstitial ad', 'movipress'),
					'description' 	=> ''
				),
				'main_interstitial_code' => array(
	    			'page' 			=> $settings['ads']['page'],
					'section' 		=> $settings['ads']['section'],
					'option' 		=> 'main_interstitial_code',
					'type' 			=> 'textarea',
					'label' 		=> '',
					'description' 	=> __('Code for the interstitial banner', 'movipress')
				),
				'single_top_banner_type' => array(
	    			'page' 			=> $settings['ads']['page'],
					'section' 		=> $settings['ads']['section'],
					'option' 		=> 'single_top_banner_type',
					'type' 			=> 'select',
					'values_id' 	=> 'ad_type_values',
					'label' 		=> __('Article view top banner', 'movipress'),
					'description' 	=> ''
				),
				'single_top_banner_code' => array(
	    			'page' 			=> $settings['ads']['page'],
					'section' 		=> $settings['ads']['section'],
					'option' 		=> 'single_top_banner_code',
					'type' 			=> 'textarea',
					'label' 		=> '',
					'description' 	=> __('Code for the ad unit displayed at the top of the page', 'movipress')
				),
				'single_bottom_banner_type' => array(
	    			'page' 			=> $settings['ads']['page'],
					'section' 		=> $settings['ads']['section'],
					'option' 		=> 'single_bottom_banner_type',
					'type' 			=> 'select',
					'values_id' 	=> 'ad_type_values',
					'label' 		=> __('Article view bottom banner', 'movipress'),
					'description' 	=> ''
				),
				'single_bottom_banner_code' => array(
	    			'page' 			=> $settings['ads']['page'],
					'section' 		=> $settings['ads']['section'],
					'option' 		=> 'single_bottom_banner_code',
					'type' 			=> 'textarea',
					'label' 		=> '',
					'description' 	=> __('Code for the ad unit displayed at the bottom of the page', 'movipress')
				),
				'single_fixed_banner_type' => array(
	    			'page' 			=> $settings['ads']['page'],
					'section' 		=> $settings['ads']['section'],
					'option' 		=> 'single_fixed_banner_type',
					'type' 			=> 'select',
					'values_id' 	=> 'ad_type_fixed_values',
					'label' 		=> __('Article view fixed banner', 'movipress'),
					'description' 	=> ''
				),
				'single_fixed_banner_code' => array(
	    			'page' 			=> $settings['ads']['page'],
					'section' 		=> $settings['ads']['section'],
					'option' 		=> 'single_fixed_banner_code',
					'type' 			=> 'textarea',
					'label' 		=> '',
					'description' 	=> __('Code for the ad unit displayed fixed at the bottom of the screen', 'movipress')
				),
			);
	return $fields;		
}
	
	
// Datalist of Google fonts
function mpio_print_font_families(){
	$a="<datalist id='fonts'>
	<option>ABeeZee</option>
	<option>Abel</option>
	<option>Abhaya Libre</option>
	<option>Abril Fatface</option>
	<option>Aclonica</option>
	<option>Acme</option>
	<option>Actor</option>
	<option>Adamina</option>
	<option>Advent Pro</option>
	<option>Aguafina Script</option>
	<option>Akronim</option>
	<option>Aladin</option>
	<option>Aldrich</option>
	<option>Alef</option>
	<option>Alegreya</option>
	<option>Alegreya SC</option>
	<option>Alegreya Sans</option>
	<option>Alegreya Sans SC</option>
	<option>Alex Brush</option>
	<option>Alfa Slab One</option>
	<option>Alice</option>
	<option>Alike</option>
	<option>Alike Angular</option>
	<option>Allan</option>
	<option>Allerta</option>
	<option>Allerta Stencil</option>
	<option>Allura</option>
	<option>Almendra</option>
	<option>Almendra Display</option>
	<option>Almendra SC</option>
	<option>Amarante</option>
	<option>Amaranth</option>
	<option>Amatic SC</option>
	<option>Amatica SC</option>
	<option>Amethysta</option>
	<option>Amiko</option>
	<option>Amiri</option>
	<option>Amita</option>
	<option>Anaheim</option>
	<option>Andada</option>
	<option>Andika</option>
	<option>Angkor</option>
	<option>Annie Use Your Telescope</option>
	<option>Anonymous Pro</option>
	<option>Antic</option>
	<option>Antic Didone</option>
	<option>Antic Slab</option>
	<option>Anton</option>
	<option>Arapey</option>
	<option>Arbutus</option>
	<option>Arbutus Slab</option>
	<option>Architects Daughter</option>
	<option>Archivo</option>
	<option>Archivo Black</option>
	<option>Archivo Narrow</option>
	<option>Aref Ruqaa</option>
	<option>Arima Madurai</option>
	<option>Arimo</option>
	<option>Arizonia</option>
	<option>Armata</option>
	<option>Arsenal</option>
	<option>Artifika</option>
	<option>Arvo</option>
	<option>Arya</option>
	<option>Asap</option>
	<option>Asap Condensed</option>
	<option>Asar</option>
	<option>Asset</option>
	<option>Assistant</option>
	<option>Astloch</option>
	<option>Asul</option>
	<option>Athiti</option>
	<option>Atma</option>
	<option>Atomic Age</option>
	<option>Aubrey</option>
	<option>Audiowide</option>
	<option>Autour One</option>
	<option>Average</option>
	<option>Average Sans</option>
	<option>Averia Gruesa Libre</option>
	<option>Averia Libre</option>
	<option>Averia Sans Libre</option>
	<option>Averia Serif Libre</option>
	<option>Bad Script</option>
	<option>Bahiana</option>
	<option>Baloo</option>
	<option>Baloo Bhai</option>
	<option>Baloo Bhaijaan</option>
	<option>Baloo Bhaina</option>
	<option>Baloo Chettan</option>
	<option>Baloo Da</option>
	<option>Baloo Paaji</option>
	<option>Baloo Tamma</option>
	<option>Baloo Tammudu</option>
	<option>Baloo Thambi</option>
	<option>Balthazar</option>
	<option>Bangers</option>
	<option>Barrio</option>
	<option>Basic</option>
	<option>Battambang</option>
	<option>Baumans</option>
	<option>Bayon</option>
	<option>Belgrano</option>
	<option>Bellefair</option>
	<option>Belleza</option>
	<option>BenchNine</option>
	<option>Bentham</option>
	<option>Berkshire Swash</option>
	<option>Bevan</option>
	<option>Bigelow Rules</option>
	<option>Bigshot One</option>
	<option>Bilbo</option>
	<option>Bilbo Swash Caps</option>
	<option>BioRhyme</option>
	<option>BioRhyme Expanded</option>
	<option>Biryani</option>
	<option>Bitter</option>
	<option>Black Ops One</option>
	<option>Bokor</option>
	<option>Bonbon</option>
	<option>Boogaloo</option>
	<option>Bowlby One</option>
	<option>Bowlby One SC</option>
	<option>Brawler</option>
	<option>Bree Serif</option>
	<option>Bubblegum Sans</option>
	<option>Bubbler One</option>
	<option>Buda</option>
	<option>Buenard</option>
	<option>Bungee</option>
	<option>Bungee Hairline</option>
	<option>Bungee Inline</option>
	<option>Bungee Outline</option>
	<option>Bungee Shade</option>
	<option>Butcherman</option>
	<option>Butterfly Kids</option>
	<option>Cabin</option>
	<option>Cabin Condensed</option>
	<option>Cabin Sketch</option>
	<option>Caesar Dressing</option>
	<option>Cagliostro</option>
	<option>Cairo</option>
	<option>Calligraffitti</option>
	<option>Cambay</option>
	<option>Cambo</option>
	<option>Candal</option>
	<option>Cantarell</option>
	<option>Cantata One</option>
	<option>Cantora One</option>
	<option>Capriola</option>
	<option>Cardo</option>
	<option>Carme</option>
	<option>Carrois Gothic</option>
	<option>Carrois Gothic SC</option>
	<option>Carter One</option>
	<option>Catamaran</option>
	<option>Caudex</option>
	<option>Caveat</option>
	<option>Caveat Brush</option>
	<option>Cedarville Cursive</option>
	<option>Ceviche One</option>
	<option>Changa</option>
	<option>Changa One</option>
	<option>Chango</option>
	<option>Chathura</option>
	<option>Chau Philomene One</option>
	<option>Chela One</option>
	<option>Chelsea Market</option>
	<option>Chenla</option>
	<option>Cherry Cream Soda</option>
	<option>Cherry Swash</option>
	<option>Chewy</option>
	<option>Chicle</option>
	<option>Chivo</option>
	<option>Chonburi</option>
	<option>Cinzel</option>
	<option>Cinzel Decorative</option>
	<option>Clicker Script</option>
	<option>Coda</option>
	<option>Coda Caption</option>
	<option>Codystar</option>
	<option>Coiny</option>
	<option>Combo</option>
	<option>Comfortaa</option>
	<option>Coming Soon</option>
	<option>Concert One</option>
	<option>Condiment</option>
	<option>Content</option>
	<option>Contrail One</option>
	<option>Convergence</option>
	<option>Cookie</option>
	<option>Copse</option>
	<option>Corben</option>
	<option>Cormorant</option>
	<option>Cormorant Garamond</option>
	<option>Cormorant Infant</option>
	<option>Cormorant SC</option>
	<option>Cormorant Unicase</option>
	<option>Cormorant Upright</option>
	<option>Courgette</option>
	<option>Cousine</option>
	<option>Coustard</option>
	<option>Covered By Your Grace</option>
	<option>Crafty Girls</option>
	<option>Creepster</option>
	<option>Crete Round</option>
	<option>Crimson Text</option>
	<option>Croissant One</option>
	<option>Crushed</option>
	<option>Cuprum</option>
	<option>Cutive</option>
	<option>Cutive Mono</option>
	<option>Damion</option>
	<option>Dancing Script</option>
	<option>Dangrek</option>
	<option>David Libre</option>
	<option>Dawning of a New Day</option>
	<option>Days One</option>
	<option>Dekko</option>
	<option>Delius</option>
	<option>Delius Swash Caps</option>
	<option>Delius Unicase</option>
	<option>Della Respira</option>
	<option>Denk One</option>
	<option>Devonshire</option>
	<option>Dhurjati</option>
	<option>Didact Gothic</option>
	<option>Diplomata</option>
	<option>Diplomata SC</option>
	<option>Domine</option>
	<option>Donegal One</option>
	<option>Doppio One</option>
	<option>Dorsa</option>
	<option>Dosis</option>
	<option>Dr Sugiyama</option>
	<option>Droid Sans</option>
	<option>Droid Sans Mono</option>
	<option>Droid Serif</option>
	<option>Duru Sans</option>
	<option>Dynalight</option>
	<option>EB Garamond</option>
	<option>Eagle Lake</option>
	<option>Eater</option>
	<option>Economica</option>
	<option>Eczar</option>
	<option>El Messiri</option>
	<option>Electrolize</option>
	<option>Elsie</option>
	<option>Elsie Swash Caps</option>
	<option>Emblema One</option>
	<option>Emilys Candy</option>
	<option>Encode Sans</option>
	<option>Encode Sans Condensed</option>
	<option>Encode Sans Expanded</option>
	<option>Encode Sans Semi Condensed</option>
	<option>Encode Sans Semi Expanded</option>
	<option>Engagement</option>
	<option>Englebert</option>
	<option>Enriqueta</option>
	<option>Erica One</option>
	<option>Esteban</option>
	<option>Euphoria Script</option>
	<option>Ewert</option>
	<option>Exo</option>
	<option>Exo 2</option>
	<option>Expletus Sans</option>
	<option>Fanwood Text</option>
	<option>Farsan</option>
	<option>Fascinate</option>
	<option>Fascinate Inline</option>
	<option>Faster One</option>
	<option>Fasthand</option>
	<option>Fauna One</option>
	<option>Faustina</option>
	<option>Federant</option>
	<option>Federo</option>
	<option>Felipa</option>
	<option>Fenix</option>
	<option>Finger Paint</option>
	<option>Fira Mono</option>
	<option>Fira Sans</option>
	<option>Fira Sans Condensed</option>
	<option>Fira Sans Extra Condensed</option>
	<option>Fjalla One</option>
	<option>Fjord One</option>
	<option>Flamenco</option>
	<option>Flavors</option>
	<option>Fondamento</option>
	<option>Fontdiner Swanky</option>
	<option>Forum</option>
	<option>Francois One</option>
	<option>Frank Ruhl Libre</option>
	<option>Freckle Face</option>
	<option>Fredericka the Great</option>
	<option>Fredoka One</option>
	<option>Freehand</option>
	<option>Fresca</option>
	<option>Frijole</option>
	<option>Fruktur</option>
	<option>Fugaz One</option>
	<option>GFS Didot</option>
	<option>GFS Neohellenic</option>
	<option>Gabriela</option>
	<option>Gafata</option>
	<option>Galada</option>
	<option>Galdeano</option>
	<option>Galindo</option>
	<option>Gentium Basic</option>
	<option>Gentium Book Basic</option>
	<option>Geo</option>
	<option>Geostar</option>
	<option>Geostar Fill</option>
	<option>Germania One</option>
	<option>Gidugu</option>
	<option>Gilda Display</option>
	<option>Give You Glory</option>
	<option>Glass Antiqua</option>
	<option>Glegoo</option>
	<option>Gloria Hallelujah</option>
	<option>Goblin One</option>
	<option>Gochi Hand</option>
	<option>Gorditas</option>
	<option>Goudy Bookletter 1911</option>
	<option>Graduate</option>
	<option>Grand Hotel</option>
	<option>Gravitas One</option>
	<option>Great Vibes</option>
	<option>Griffy</option>
	<option>Gruppo</option>
	<option>Gudea</option>
	<option>Gurajada</option>
	<option>Habibi</option>
	<option>Halant</option>
	<option>Hammersmith One</option>
	<option>Hanalei</option>
	<option>Hanalei Fill</option>
	<option>Handlee</option>
	<option>Hanuman</option>
	<option>Happy Monkey</option>
	<option>Harmattan</option>
	<option>Headland One</option>
	<option>Heebo</option>
	<option>Henny Penny</option>
	<option>Herr Von Muellerhoff</option>
	<option>Hind</option>
	<option>Hind Guntur</option>
	<option>Hind Madurai</option>
	<option>Hind Siliguri</option>
	<option>Hind Vadodara</option>
	<option>Holtwood One SC</option>
	<option>Homemade Apple</option>
	<option>Homenaje</option>
	<option>IM Fell DW Pica</option>
	<option>IM Fell DW Pica SC</option>
	<option>IM Fell Double Pica</option>
	<option>IM Fell Double Pica SC</option>
	<option>IM Fell English</option>
	<option>IM Fell English SC</option>
	<option>IM Fell French Canon</option>
	<option>IM Fell French Canon SC</option>
	<option>IM Fell Great Primer</option>
	<option>IM Fell Great Primer SC</option>
	<option>Iceberg</option>
	<option>Iceland</option>
	<option>Imprima</option>
	<option>Inconsolata</option>
	<option>Inder</option>
	<option>Indie Flower</option>
	<option>Inika</option>
	<option>Inknut Antiqua</option>
	<option>Irish Grover</option>
	<option>Istok Web</option>
	<option>Italiana</option>
	<option>Italianno</option>
	<option>Itim</option>
	<option>Jacques Francois</option>
	<option>Jacques Francois Shadow</option>
	<option>Jaldi</option>
	<option>Jim Nightshade</option>
	<option>Jockey One</option>
	<option>Jolly Lodger</option>
	<option>Jomhuria</option>
	<option>Josefin Sans</option>
	<option>Josefin Slab</option>
	<option>Joti One</option>
	<option>Judson</option>
	<option>Julee</option>
	<option>Julius Sans One</option>
	<option>Junge</option>
	<option>Jura</option>
	<option>Just Another Hand</option>
	<option>Just Me Again Down Here</option>
	<option>Kadwa</option>
	<option>Kalam</option>
	<option>Kameron</option>
	<option>Kanit</option>
	<option>Kantumruy</option>
	<option>Karla</option>
	<option>Karma</option>
	<option>Katibeh</option>
	<option>Kaushan Script</option>
	<option>Kavivanar</option>
	<option>Kavoon</option>
	<option>Kdam Thmor</option>
	<option>Keania One</option>
	<option>Kelly Slab</option>
	<option>Kenia</option>
	<option>Khand</option>
	<option>Khmer</option>
	<option>Khula</option>
	<option>Kite One</option>
	<option>Knewave</option>
	<option>Kotta One</option>
	<option>Koulen</option>
	<option>Kranky</option>
	<option>Kreon</option>
	<option>Kristi</option>
	<option>Krona One</option>
	<option>Kumar One</option>
	<option>Kumar One Outline</option>
	<option>Kurale</option>
	<option>La Belle Aurore</option>
	<option>Laila</option>
	<option>Lakki Reddy</option>
	<option>Lalezar</option>
	<option>Lancelot</option>
	<option>Lateef</option>
	<option>Lato</option>
	<option>League Script</option>
	<option>Leckerli One</option>
	<option>Ledger</option>
	<option>Lekton</option>
	<option>Lemon</option>
	<option>Lemonada</option>
	<option>Libre Barcode 128</option>
	<option>Libre Barcode 128 Text</option>
	<option>Libre Barcode 39</option>
	<option>Libre Barcode 39 Extended</option>
	<option>Libre Barcode 39 Extended Text</option>
	<option>Libre Barcode 39 Text</option>
	<option>Libre Baskerville</option>
	<option>Libre Franklin</option>
	<option>Life Savers</option>
	<option>Lilita One</option>
	<option>Lily Script One</option>
	<option>Limelight</option>
	<option>Linden Hill</option>
	<option>Lobster</option>
	<option>Lobster Two</option>
	<option>Londrina Outline</option>
	<option>Londrina Shadow</option>
	<option>Londrina Sketch</option>
	<option>Londrina Solid</option>
	<option>Lora</option>
	<option>Love Ya Like A Sister</option>
	<option>Loved by the King</option>
	<option>Lovers Quarrel</option>
	<option>Luckiest Guy</option>
	<option>Lusitana</option>
	<option>Lustria</option>
	<option>Macondo</option>
	<option>Macondo Swash Caps</option>
	<option>Mada</option>
	<option>Magra</option>
	<option>Maiden Orange</option>
	<option>Maitree</option>
	<option>Mako</option>
	<option>Mallanna</option>
	<option>Mandali</option>
	<option>Manuale</option>
	<option>Marcellus</option>
	<option>Marcellus SC</option>
	<option>Marck Script</option>
	<option>Margarine</option>
	<option>Marko One</option>
	<option>Marmelad</option>
	<option>Martel</option>
	<option>Martel Sans</option>
	<option>Marvel</option>
	<option>Mate</option>
	<option>Mate SC</option>
	<option>Maven Pro</option>
	<option>McLaren</option>
	<option>Meddon</option>
	<option>MedievalSharp</option>
	<option>Medula One</option>
	<option>Meera Inimai</option>
	<option>Megrim</option>
	<option>Meie Script</option>
	<option>Merienda</option>
	<option>Merienda One</option>
	<option>Merriweather</option>
	<option>Merriweather Sans</option>
	<option>Metal</option>
	<option>Metal Mania</option>
	<option>Metamorphous</option>
	<option>Metrophobic</option>
	<option>Michroma</option>
	<option>Milonga</option>
	<option>Miltonian</option>
	<option>Miltonian Tattoo</option>
	<option>Miniver</option>
	<option>Miriam Libre</option>
	<option>Mirza</option>
	<option>Miss Fajardose</option>
	<option>Mitr</option>
	<option>Modak</option>
	<option>Modern Antiqua</option>
	<option>Mogra</option>
	<option>Molengo</option>
	<option>Molle</option>
	<option>Monda</option>
	<option>Monofett</option>
	<option>Monoton</option>
	<option>Monsieur La Doulaise</option>
	<option>Montaga</option>
	<option>Montez</option>
	<option>Montserrat</option>
	<option>Montserrat Alternates</option>
	<option>Montserrat Subrayada</option>
	<option>Moul</option>
	<option>Moulpali</option>
	<option>Mountains of Christmas</option>
	<option>Mouse Memoirs</option>
	<option>Mr Bedfort</option>
	<option>Mr Dafoe</option>
	<option>Mr De Haviland</option>
	<option>Mrs Saint Delafield</option>
	<option>Mrs Sheppards</option>
	<option>Mukta</option>
	<option>Mukta Mahee</option>
	<option>Mukta Malar</option>
	<option>Mukta Vaani</option>
	<option>Muli</option>
	<option>Mystery Quest</option>
	<option>NTR</option>
	<option>Neucha</option>
	<option>Neuton</option>
	<option>New Rocker</option>
	<option>News Cycle</option>
	<option>Niconne</option>
	<option>Nixie One</option>
	<option>Nobile</option>
	<option>Nokora</option>
	<option>Norican</option>
	<option>Nosifer</option>
	<option>Nothing You Could Do</option>
	<option>Noticia Text</option>
	<option>Noto Sans</option>
	<option>Noto Serif</option>
	<option>Nova Cut</option>
	<option>Nova Flat</option>
	<option>Nova Mono</option>
	<option>Nova Oval</option>
	<option>Nova Round</option>
	<option>Nova Script</option>
	<option>Nova Slim</option>
	<option>Nova Square</option>
	<option>Numans</option>
	<option>Nunito</option>
	<option>Nunito Sans</option>
	<option>Odor Mean Chey</option>
	<option>Offside</option>
	<option>Old Standard TT</option>
	<option>Oldenburg</option>
	<option>Oleo Script</option>
	<option>Oleo Script Swash Caps</option>
	<option>Open Sans</option>
	<option>Open Sans Condensed</option>
	<option>Oranienbaum</option>
	<option>Orbitron</option>
	<option>Oregano</option>
	<option>Orienta</option>
	<option>Original Surfer</option>
	<option>Oswald</option>
	<option>Over the Rainbow</option>
	<option>Overlock</option>
	<option>Overlock SC</option>
	<option>Overpass</option>
	<option>Overpass Mono</option>
	<option>Ovo</option>
	<option>Oxygen</option>
	<option>Oxygen Mono</option>
	<option>PT Mono</option>
	<option>PT Sans</option>
	<option>PT Sans Caption</option>
	<option>PT Sans Narrow</option>
	<option>PT Serif</option>
	<option>PT Serif Caption</option>
	<option>Pacifico</option>
	<option>Padauk</option>
	<option>Palanquin</option>
	<option>Palanquin Dark</option>
	<option>Pangolin</option>
	<option>Paprika</option>
	<option>Parisienne</option>
	<option>Passero One</option>
	<option>Passion One</option>
	<option>Pathway Gothic One</option>
	<option>Patrick Hand</option>
	<option>Patrick Hand SC</option>
	<option>Pattaya</option>
	<option>Patua One</option>
	<option>Pavanam</option>
	<option>Paytone One</option>
	<option>Peddana</option>
	<option>Peralta</option>
	<option>Permanent Marker</option>
	<option>Petit Formal Script</option>
	<option>Petrona</option>
	<option>Philosopher</option>
	<option>Piedra</option>
	<option>Pinyon Script</option>
	<option>Pirata One</option>
	<option>Plaster</option>
	<option>Play</option>
	<option>Playball</option>
	<option>Playfair Display</option>
	<option>Playfair Display SC</option>
	<option>Podkova</option>
	<option>Poiret One</option>
	<option>Poller One</option>
	<option>Poly</option>
	<option>Pompiere</option>
	<option>Pontano Sans</option>
	<option>Poppins</option>
	<option>Port Lligat Sans</option>
	<option>Port Lligat Slab</option>
	<option>Pragati Narrow</option>
	<option>Prata</option>
	<option>Preahvihear</option>
	<option>Press Start 2P</option>
	<option>Pridi</option>
	<option>Princess Sofia</option>
	<option>Prociono</option>
	<option>Prompt</option>
	<option>Prosto One</option>
	<option>Proza Libre</option>
	<option>Puritan</option>
	<option>Purple Purse</option>
	<option>Quando</option>
	<option>Quantico</option>
	<option>Quattrocento</option>
	<option>Quattrocento Sans</option>
	<option>Questrial</option>
	<option>Quicksand</option>
	<option>Quintessential</option>
	<option>Qwigley</option>
	<option>Racing Sans One</option>
	<option>Radley</option>
	<option>Rajdhani</option>
	<option>Rakkas</option>
	<option>Raleway</option>
	<option>Raleway Dots</option>
	<option>Ramabhadra</option>
	<option>Ramaraja</option>
	<option>Rambla</option>
	<option>Rammetto One</option>
	<option>Ranchers</option>
	<option>Rancho</option>
	<option>Ranga</option>
	<option>Rasa</option>
	<option>Rationale</option>
	<option>Ravi Prakash</option>
	<option>Redressed</option>
	<option>Reem Kufi</option>
	<option>Reenie Beanie</option>
	<option>Revalia</option>
	<option>Rhodium Libre</option>
	<option>Ribeye</option>
	<option>Ribeye Marrow</option>
	<option>Righteous</option>
	<option>Risque</option>
	<option>Roboto</option>
	<option>Roboto Condensed</option>
	<option>Roboto Mono</option>
	<option>Roboto Slab</option>
	<option>Rochester</option>
	<option>Rock Salt</option>
	<option>Rokkitt</option>
	<option>Romanesco</option>
	<option>Ropa Sans</option>
	<option>Rosario</option>
	<option>Rosarivo</option>
	<option>Rouge Script</option>
	<option>Rozha One</option>
	<option>Rubik</option>
	<option>Rubik Mono One</option>
	<option>Ruda</option>
	<option>Rufina</option>
	<option>Ruge Boogie</option>
	<option>Ruluko</option>
	<option>Rum Raisin</option>
	<option>Ruslan Display</option>
	<option>Russo One</option>
	<option>Ruthie</option>
	<option>Rye</option>
	<option>Sacramento</option>
	<option>Sahitya</option>
	<option>Sail</option>
	<option>Saira</option>
	<option>Saira Condensed</option>
	<option>Saira Extra Condensed</option>
	<option>Saira Semi Condensed</option>
	<option>Salsa</option>
	<option>Sanchez</option>
	<option>Sancreek</option>
	<option>Sansita</option>
	<option>Sarala</option>
	<option>Sarina</option>
	<option>Sarpanch</option>
	<option>Satisfy</option>
	<option>Scada</option>
	<option>Scheherazade</option>
	<option>Schoolbell</option>
	<option>Scope One</option>
	<option>Seaweed Script</option>
	<option>Secular One</option>
	<option>Sedgwick Ave</option>
	<option>Sedgwick Ave Display</option>
	<option>Sevillana</option>
	<option>Seymour One</option>
	<option>Shadows Into Light</option>
	<option>Shadows Into Light Two</option>
	<option>Shanti</option>
	<option>Share</option>
	<option>Share Tech</option>
	<option>Share Tech Mono</option>
	<option>Shojumaru</option>
	<option>Short Stack</option>
	<option>Shrikhand</option>
	<option>Siemreap</option>
	<option>Sigmar One</option>
	<option>Signika</option>
	<option>Signika Negative</option>
	<option>Simonetta</option>
	<option>Sintony</option>
	<option>Sirin Stencil</option>
	<option>Six Caps</option>
	<option>Skranji</option>
	<option>Slabo 13px</option>
	<option>Slabo 27px</option>
	<option>Slackey</option>
	<option>Smokum</option>
	<option>Smythe</option>
	<option>Sniglet</option>
	<option>Snippet</option>
	<option>Snowburst One</option>
	<option>Sofadi One</option>
	<option>Sofia</option>
	<option>Sonsie One</option>
	<option>Sorts Mill Goudy</option>
	<option>Source Code Pro</option>
	<option>Source Sans Pro</option>
	<option>Source Serif Pro</option>
	<option>Space Mono</option>
	<option>Special Elite</option>
	<option>Spectral</option>
	<option>Spicy Rice</option>
	<option>Spinnaker</option>
	<option>Spirax</option>
	<option>Squada One</option>
	<option>Sree Krushnadevaraya</option>
	<option>Sriracha</option>
	<option>Stalemate</option>
	<option>Stalinist One</option>
	<option>Stardos Stencil</option>
	<option>Stint Ultra Condensed</option>
	<option>Stint Ultra Expanded</option>
	<option>Stoke</option>
	<option>Strait</option>
	<option>Sue Ellen Francisco</option>
	<option>Suez One</option>
	<option>Sumana</option>
	<option>Sunshiney</option>
	<option>Supermercado One</option>
	<option>Sura</option>
	<option>Suranna</option>
	<option>Suravaram</option>
	<option>Suwannaphum</option>
	<option>Swanky and Moo Moo</option>
	<option>Syncopate</option>
	<option>Tangerine</option>
	<option>Taprom</option>
	<option>Tauri</option>
	<option>Taviraj</option>
	<option>Teko</option>
	<option>Telex</option>
	<option>Tenali Ramakrishna</option>
	<option>Tenor Sans</option>
	<option>Text Me One</option>
	<option>The Girl Next Door</option>
	<option>Tienne</option>
	<option>Tillana</option>
	<option>Timmana</option>
	<option>Tinos</option>
	<option>Titan One</option>
	<option>Titillium Web</option>
	<option>Trade Winds</option>
	<option>Trirong</option>
	<option>Trocchi</option>
	<option>Trochut</option>
	<option>Trykker</option>
	<option>Tulpen One</option>
	<option>Ubuntu</option>
	<option>Ubuntu Condensed</option>
	<option>Ubuntu Mono</option>
	<option>Ultra</option>
	<option>Uncial Antiqua</option>
	<option>Underdog</option>
	<option>Unica One</option>
	<option>UnifrakturCook</option>
	<option>UnifrakturMaguntia</option>
	<option>Unkempt</option>
	<option>Unlock</option>
	<option>Unna</option>
	<option>VT323</option>
	<option>Vampiro One</option>
	<option>Varela</option>
	<option>Varela Round</option>
	<option>Vast Shadow</option>
	<option>Vesper Libre</option>
	<option>Vibur</option>
	<option>Vidaloka</option>
	<option>Viga</option>
	<option>Voces</option>
	<option>Volkhov</option>
	<option>Vollkorn</option>
	<option>Voltaire</option>
	<option>Waiting for the Sunrise</option>
	<option>Wallpoet</option>
	<option>Walter Turncoat</option>
	<option>Warnes</option>
	<option>Wellfleet</option>
	<option>Wendy One</option>
	<option>Wire One</option>
	<option>Work Sans</option>
	<option>Yanone Kaffeesatz</option>
	<option>Yantramanav</option>
	<option>Yatra One</option>
	<option>Yellowtail</option>
	<option>Yeseva One</option>
	<option>Yesteryear</option>
	<option>Yrsa</option>
	<option>Zeyada</option>
	<option>Zilla Slab</option>
	<option>Zilla Slab Highlight</option>
	</datalist>";
	echo $a;
}
