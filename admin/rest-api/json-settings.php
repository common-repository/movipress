<?php
/**
 * Movipress json-settings.php
 *
 * Creates de REST API endpoint for the settings and adds
 * some movipress data fields to post, terms and authors.
 *
 * @author 		Movipress Ltd.
 * @version     1.0
 */

// Adds movipress custom fields to the WordPress REST API
function mpio_rest_api_post_custom_fields( $data, $post, $request ) {
	
	$_data = $data->data;
	$mpio_post_meta = get_post_meta($post->ID, '_mpio_post_meta', true);
	
	if(isset($mpio_post_meta) && $mpio_post_meta!=null){	
		$mpio_post_meta = json_decode($mpio_post_meta,true);
		$_data['movipress'] = $mpio_post_meta['remote'];	
	}else{
		
		$content = array(
		    'alternative'	=>	0
	    );

	    $templates = array(
			'item'		=> 'default', 
			'single'	=> 'default'
     	
		);
	    
	    $mpio_post_meta = array(
		    'show' 		=>	1,
		    'content'	=>	$content,
		    'templates' =>	$templates,
	    );

	    $_data['movipress'] = $mpio_post_meta;			
	}
	
	$data->data = $_data;
	return $data;
}
add_filter( 'rest_prepare_post', 'mpio_rest_api_post_custom_fields', 10, 3 );



// Adds linked template of a term to the WordPress REST API
function mpio_rest_api_add_list_template($data, $template){
	$_data = $data->data;
	if(empty($template) || $template=='default'){
		$_data['mpio_template'] = 'default';
	}else{
		$templates = get_option('mpio_list_templates');
		$_data['mpio_template'] = array_key_exists($template, $templates) ?  $template : 'default';
	}
	$data->data = $_data;
	return $data;	
}
function mpio_rest_api_term_custom_fields($data, $term, $request) {
	$template = get_term_meta($term->term_id, '_mpio_template', true);
	mpio_rest_api_add_list_template($data, $template);
	return $data;
}
add_filter('rest_prepare_category', 'mpio_rest_api_term_custom_fields', 10, 3);
add_filter('rest_prepare_post_tag', 'mpio_rest_api_term_custom_fields', 10, 3);



// Adds linked template of an user to the WordPress REST API
function mpio_rest_api_user_custom_fields($data, $user, $request) {
	$template = get_the_author_meta('mpio_template', $user->ID);
	mpio_rest_api_add_list_template($data, $template);
	return $data;
}
add_filter('rest_prepare_user', 'mpio_rest_api_user_custom_fields', 10, 3);


function mpio_filter_rest_allow_anonymous_comments() {
    return true;
}
add_filter('rest_allow_anonymous_comments','mpio_filter_rest_allow_anonymous_comments');



/*
 * Endpoint for the movipress settings
 */
class Mpio_Rest_Server extends WP_REST_Controller {
 
	//The namespace and version for the REST SERVER
	var $namespace = 'mpio/v';
	var $version   = '1';
	
	public function register_routes() {
		$namespace = $this->namespace . $this->version;
		$base      = 'settings';
		register_rest_route( $namespace, '/' . $base, 
			array(
				'methods'	=> WP_REST_Server::READABLE,
				'callback'	=> array( $this, 'get_settings' )
			)
		);
	}
 
	// Register REST Server
	public function hook_rest_server(){
		add_action( 'rest_api_init', array( $this, 'register_routes' ) );
	}
 

	 // Gets the custom menu
	private function get_navigation_menu() {
		$options=get_option('mpio_navigation_menu_options');
		if(isset($options['navigation_menu'])){
			$menu=$options['navigation_menu'];
			$items = explode(",", $menu);
			foreach ($items as $key => $item) {
				$type=substr($item,0,1);
				$id=substr($item,1);
				switch ($type) {
				    case "c":
				    	$type=1;
				        $name=get_the_category_by_ID($id);
						break;
					case "t":
			    		$type=2;
			    		$tag = get_tag($id); 
						$name=$tag->name;
						break;
					case "a":
						$type=4;
						$name=get_the_title($id);
						break;
				    case "p":
				    	$type=5;
				        $name=get_the_title($id);
				        break;
				}
				$out[$key]=array("id"=>$id,"type"=>$type,"title"=>$name);	
			}
			return $out;	
		}
		return "";
	}
	
	// Gets the permalinks settings
	private function get_custom_permalinks() {
		global $wp_rewrite;
		if (!is_a($wp_rewrite, 'WP_Rewrite')) {
	       	$wp_rewrite = new WP_Rewrite();
		}
		if($wp_rewrite->using_permalinks()){
			$permalinks = array(
					"permalinks"=>true,
					"post"		=>$wp_rewrite->permalink_structure,
					"page"		=>$wp_rewrite->get_page_permastruct(),
					"author"	=>$wp_rewrite->get_author_permastruct(),
					"category"	=>$wp_rewrite->get_category_permastruct(), 
					"tag"		=>$wp_rewrite->get_tag_permastruct(),        						       						
	    			);
	    }else{
	    	$permalinks = array("permalinks"=>false);
		}
		return $permalinks;
	}
	
	// Gets all the categories.
	private function get_json_categories(){
		$request = new WP_REST_Request( 'GET', '/wp/v2/categories' );
		$num_categories=$this->get_number_of_categories();
		return $this->get_json_request($request, $num_categories);
	}
	
	// Gets the number of categories.
	private function get_number_of_categories(){
		$args=array(
				'get' => 'all',
				'hide_empty' => 1
		);
		$categories=get_categories($args);
		return count($categories); 
	}
	
	
	// Gets all the tags
	private function get_json_tags(){
		$request = new WP_REST_Request( 'GET', '/wp/v2/tags' );
		$num_tags=$this->get_number_of_tags();
		return $this->get_json_request($request, $num_tags);
	}
	
	// Gets the number of tags
	private function get_number_of_tags(){
		$args=array(
				'get' => 'all',
				'hide_empty' => 1
		);
		$tags=get_tags($args);
		return count($tags); 
	}
	
	/*
	 * Gets data from users with published posts or pages
	 * Cannot filter like this with the REST API
	*/
	private function get_json_authors(){	
		$user_args = array('has_published_posts' => array('post','page'));
	    $authors_query = new WP_User_Query($user_args);	
		$authors_array=array();
		foreach($authors_query->get_results() as $user ){    
			$mpio_template = get_user_meta($user->ID, 'mpio_template', true);
			$mpio_template = !empty($mpio_template)? $mpio_template: 'default';
		    $pub_author= array(
			  			'id' 			=> intval($user->ID),
			  			'name'			=> $user->display_name, 
			  			'url'			=> $user->user_url,
			  			'description'	=> get_user_meta($user->ID, 'description', true),
			  			'link'			=> get_author_posts_url($user->ID),
			  			'slug'			=> $user->user_nicename, 
			  			'avatar_urls'	=> array('96' => get_avatar_url($user->ID, 96)),
						'mpio_template' => 	$mpio_template	  			
		    );
		    array_push($authors_array, $pub_author);
		}
		return $authors_array;    
	}

	
	// Gets JSON request for the sync
	private function get_json_request($request, $num_items){
		$per_page=100;
		if($num_items>$per_page){
			$data=array();
			$j=0;
			for($i=1;$j<$num_items;$i++){
				$j=$i*$per_page;
				$request->set_param('per_page',$per_page);
				$request->set_param('page',$i);
				$response = rest_do_request( $request );
				$data=array_merge($data, $response->data);	
			}
		}else{
			$request->set_param('per_page',$num_items);
			$response = rest_do_request( $request );
			$data = $response->data;
		}
		return $data;
	}
 
 
 
	// Gets the JSON with the settings to sync with the app
  	public function get_settings( WP_REST_Request $request ){
	    $v = $request->get_param( 'v' );
        
	    if (filter_var($v, FILTER_VALIDATE_REGEXP, array( "options" => array("regexp"=>"/\A\d{24}\z/")))) { 
			$versions_keys=array('general','menu','permalinks','categories','tags','users','templates','ads');
			$remote_versions=str_split($v,3);
			$remote_values=array_combine($versions_keys,$remote_versions);
			
			$uptodate=true;
			$versions = get_option('mpio_versions');
			
			if($versions==false){
				mpio_init_versions();
				$versions = get_option('mpio_versions');
			}
					
			// General
			if($versions['general']!=$remote_values['general']){
				$uptodate=false;
				$general_options = get_option('mpio_general_options');
				$data['https']=isset($general_options['is_https'])?true:false;
				$data['rtl']=isset($general_options['use_rtl_reading'])?true:false;
				if(isset($general_options['gdpr'])){
					$gdpr_page = get_post($general_options['gdpr']); 
					$data['gdpr']= apply_filters('the_content', $gdpr_page->post_content); 
					
				}
				
				$general_json=array(
							'uptodate' 	=> false,
							'version' 	=> $versions['general'],
							'data' 		=> $data
							);
			}else{
				$general_json=array('uptodate' => true);
			}
			
			// App Menu
			if($versions['app_menu']!=$remote_values['menu']){
				$uptodate=false;
				$menu_json=array(
							'uptodate' 	=> false,
							'version' 	=> $versions['app_menu'],
							'data' 		=> $this->get_navigation_menu()
							);
			}else{
				$menu_json=array('uptodate' => true);
			}
			
			// Permalinks
			if($versions['permalinks']!=$remote_values['permalinks']){
				$uptodate=false;
				$permalinks_json=array(	
							'uptodate' 	=> false,
							'version' 	=> $versions['permalinks'],
							'data' 		=> $this->get_custom_permalinks()
							);
			}else{
				$permalinks_json=array('uptodate' => true);
			}		
			
			//Categories
			if($versions['categories']!=$remote_values['categories']){
				$uptodate=false;
				$categories_json=array(	
							'uptodate'	=> false,
							'version'	=> $versions['categories'],
							'data'  	=> $this->get_json_categories()
							);
			}else{
				$categories_json=array('uptodate' => true);
			}
			
			// Tags
			if($versions['tags']!=$remote_values['tags']){
				$uptodate=false;
				$tags_json=array(	
							'uptodate'	=> false,
							'version'	=> $versions['tags'],
							'data'  	=> $this->get_json_tags()
							);
			}else{
				$tags_json=array('uptodate' => true);
			}
			
			
			// Authors
			if($versions['users']!=$remote_values['users']){
				$uptodate=false;
				$users_json=array(	
							'uptodate'	=> false,
							'version'	=> $versions['users'],
							'data'  	=> $this->get_json_authors()
							);
			}else{
				$users_json=array('uptodate' => true);
			}
	
			// Templates
			if($versions['templates']!=$remote_values['templates']){
				$uptodate=false;
				
				$l_templates = get_option('mpio_list_templates');
				$s_templates = get_option('mpio_single_templates');
				$def_templates = get_option('mpio_general_templates_options');
				
				$main=array_key_exists($def_templates['def_main_template'],$l_templates)?$def_templates['def_main_template']:'default';
				$cat=array_key_exists($def_templates['def_cat_template'],$l_templates)?$def_templates['def_cat_template']:'default';
				$tag=array_key_exists($def_templates['def_tag_template'],$l_templates)?$def_templates['def_tag_template']:'default';
				$author=array_key_exists($def_templates['def_author_template'],$l_templates)?$def_templates['def_author_template']:'default';
				$saved=array_key_exists($def_templates['def_saved_template'],$l_templates)?$def_templates['def_saved_template']:'default';
				$search=array_key_exists($def_templates['def_search_template'],$l_templates)?$def_templates['def_search_template']:'default';
				$single=array_key_exists($def_templates['def_single_template'],$s_templates)?$def_templates['def_single_template']:'default';
				$page=array_key_exists($def_templates['def_page_template'],$s_templates)?$def_templates['def_page_template']:'default';				
				
				$default=array(
							'main'		=> $main,
							'cat'		=> $cat,
							'tag'		=> $tag,
							'author'	=> $author,
							'saved'		=> $saved,
							'search'	=> $search,														
							'single'	=> $single,
							'page'  	=> $page
							);
				
				$data=array(
							'default'	=> $default,
							'list'		=> get_option('mpio_list_templates'),
							'single'	=> get_option('mpio_single_templates')
							);
							
				$templates_json=array(	
							'uptodate'	=> false,
							'version'	=> $versions['templates'],
							'data'  	=> $data
							);
			}else{
				$templates_json=array('uptodate' => true);
			}
			
			// Advertisements
			if($versions['ads']!=$remote_values['ads']){
				$uptodate=false;
				$ads_json=array(	
							'uptodate'	=> false,
							'version'	=> $versions['ads'],
							'data'  	=> get_option('mpio_ads_options')
							);
			}else{
				$ads_json=array('uptodate' => true);
			}
			
			// If there are changes in any setting, notify the app
			if($uptodate!=true){
				
				return array(
						'uptodate' 		=> false,
						'general' 		=> $general_json,
						'menu' 			=> $menu_json,
						'permastruct' 	=> $permalinks_json,
						'categories' 	=> $categories_json,
						'tags'	 		=> $tags_json,
						'users'	 		=> $users_json,
						'templates'		=> $templates_json,
						'ads'	 		=> $ads_json							
				);
			}else{	
				return array('uptodate' => true);
			}
		}else{
			return null;
		}		
	}
}
$mpio_rest_server = new Mpio_Rest_Server();
$mpio_rest_server->hook_rest_server();
