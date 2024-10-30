<?php
/**
 * Movipress app-menu.php
 *
 * Manage the menu for the app.
 *
 * @author 		Movipress Ltd.
 * @version     1.0
 */

// Creates a checklist with the given values.
function mpio_create_checklist($type,$value,$text){
	return '<li id="'.$type.'-'.$value.'">
				<label class="selectit">
				<input value="'.$value.'" type="checkbox" name="post_category[]" id="in-'.$type.'-'.$value.'"> '.$text.'</label>
			</li>';
}

// Creates a many checklists from an Array.
function mpio_print_p_checklist($type,$ps){
	$a="";
	foreach ($ps as $p) $a.=mpio_create_checklist($type,$p->ID,$p->post_title);
	echo $a;
}

// Creates a checklist with the all the pages.
function mpio_all_pages_checklist(){
	$pages = get_pages(); 
	mpio_print_p_checklist("page",$pages);
}

// Creates a checklist with all the posts.
function mpio_all_posts_checklist(){
	$args = array(
		'numberposts' => -1,
		'post_type' => 'post',
	);
	$posts = get_posts($args); 
	mpio_print_p_checklist("post",$posts);
}

// Creates a checklist with the most recent pages
function mpio_most_recent_pages_checklist(){
	$args = array(
		'post_type' => 'page',
		'orderby' => 'post_date',
		'showposts' => '10'
	);
	$pages = get_posts($args);
	mpio_print_p_checklist("page",$pages);
}

// Creates a checklist with the most recent posts
function mpio_most_recent_posts_checklist(){
	$args = array(
		'numberposts' => -1,
		'post_type' => 'post',
		'orderby' => 'post_date',
		'showposts' => '10'
	);
	$posts = get_posts($args); 
	mpio_print_p_checklist("post",$posts);
}

// Creates a cheacklist with the most used categories
function mpio_most_used_categories_checklist(){
	$categories = get_categories( array(
		'odererby' => 'count',
		'order' => 'DESC',
		'number' => 10,
	));
	$a="";
	foreach ($categories as $category) $a.=mpio_create_checklist("category",$category->cat_ID,$category->name);
	echo $a;
}

// Creates a cheacklist with the most used tags
function mpio_most_used_tags_checklist(){
	$tags = get_tags( array(
		'odererby' => 'count',
		'order' => 'DESC',
		'number' => 10,
	));
	$a="";
	foreach ($tags as $tag) $a.=mpio_create_checklist("tag",$tag->term_id ,$tag->name);
	echo $a;
}

// Returns a menu item
function mpio_get_menu_item($element, $title){
	$a='<li class="ui-state-default" id="'.$element.'"><span class="mpio-menu-item">'.$title.'</span><span class="mpio-menu-delete"><a href="#">'.__('Delete', 'movipress').'</a></span><div style="clear: both;"></div></li>';
	return $a;
}

// Prints the custom main menu of the app
function print_main_menu($elements){
	if(isset($elements)){
	$elements = explode(",", $elements);
	foreach($elements as $element){
		$type=substr($element,0,1);
		switch ($type) {
			case "c":	//Category
				echo mpio_get_menu_item($element, get_cat_name(substr($element,1)));
				break;
			case "t":	//tag
				$tag = get_tag(substr($element,1)); 
				echo mpio_get_menu_item($element, $tag->name);
				break;	
			case "p":	//Page
			case "a":	//Post/Article
				echo mpio_get_menu_item($element, get_the_title(substr($element,1)));
				break;
			default:
				break;        		
		}
	}
	}
}


// Increases the menu version number
function mpio_update_menu_options( $old_value, $new_value ){
	mpio_increase_version('app_menu');
}
add_action( 'update_option_mpio_navigation_menu_options', 'mpio_update_menu_options', 10, 2 );


// Prints app menu WP admin page.
function mpio_navigation_callback(){
	wp_enqueue_script('mpio-menu-js');		
	$options=get_option('mpio_navigation_menu_options');
?> 
<div class="wrap nav-menus-php">
	<div id="nav-menus-frame" class="wp-clearfix">
		<div id="menu-settings-column" class="metabox-holder">
			<div class="clear"></div>
			<div id="accordion" class="mpio-accordion">
				<h3><?php _e('Categories', 'movipress'); ?></h3>
				<div class="mpio-options-group">		
					<div id="cat-tabs" class="mpio-metabox-tabs">
						<ul class="category-tabs mpio-category-tabs">
							<li class="tabs"><a href="#cat-tabs-1"><?php _e('Most Used', 'movipress'); ?></a></li>
							<li class="tabs"><a href="#cat-tabs-2"><?php _e('View All', 'movipress'); ?></a></li>
						</ul>
						<div id="cat-tabs-1" class="panel tabs-panel">
							<?php mpio_most_used_categories_checklist(); ?>
						</div>
						<div id="cat-tabs-2" class="panel tabs-panel">
							<?php wp_category_checklist(); ?>
						</div>
					</div>
					<div>
						<input type="submit" class="button-secondary submit-add-to-menu right" 
							value="<?php _e('Add to Menu', 'movipress'); ?>" name="add-category-menu-item" id="submit-taxonomy-category">
					</div>
				</div>
				<h3><?php _e('Tags', 'movipress'); ?></h3>
				<div class="mpio-options-group">		
					<div id="tags-tabs" class="mpio-metabox-tabs">
						<ul class="category-tabs mpio-category-tabs">
							<li class="tabs"><a href="#tags-tabs-1"><?php _e('Most Used', 'movipress'); ?></a></li>
							<li class="tabs"><a href="#tags-tabs-2"><?php _e('View All', 'movipress'); ?></a></li>
						</ul>
						<div id="tags-tabs-1" class="panel tabs-panel">
							<?php mpio_most_used_tags_checklist(); ?>
						</div>
						<div id="tags-tabs-2" class="panel tabs-panel">
							<?php wp_terms_checklist(0, array('taxonomy'=>'post_tag')); ?>
						</div>
					</div>
					<div>
						<input type="submit" class="button-secondary submit-add-to-menu right" 
							value="<?php _e('Add to Menu', 'movipress'); ?>" name="add-tag-menu-item" id="submit-taxonomy-tag">
					</div>
				</div>
				<h3><?php _e('Posts', 'movipress'); ?></h3>
				<div class="mpio-options-group">
    				<div id="posts-tabs" class="mpio-metabox-tabs">
						<ul class="category-tabs mpio-category-tabs">
							<li class="tabs"><a href="#posts-tabs-1"><?php _e('Most Used', 'movipress'); ?></a></li>
							<li class="tabs"><a href="#posts-tabs-2"><?php _e('View All', 'movipress'); ?></a></li>
						</ul>
						<div id="posts-tabs-1" class="panel tabs-panel">
							<?php mpio_most_recent_posts_checklist(); ?>
						</div>
						<div id="posts-tabs-2" class="panel tabs-panel">
							<?php mpio_all_posts_checklist(); ?>
						</div>
					</div>
					<div>
						<input type="submit" class="button-secondary submit-add-to-menu right" 
						value="<?php _e('Add to Menu', 'movipress'); ?>" name="add-post-menu-item" id="submit-post">
					</div>
				</div>
				<h3><?php _e('Pages', 'movipress'); ?></h3>
				<div class="mpio-options-group">
    				<div id="pages-tabs" class="mpio-metabox-tabs">
						<ul class="category-tabs mpio-category-tabs">
							<li class="tabs"><a href="#pages-tabs-1"><?php _e('Most Used', 'movipress'); ?></a></li>
							<li class="tabs"><a href="#pages-tabs-2"><?php _e('View All', 'movipress'); ?></a></li>
						</ul>
						<div id="pages-tabs-1" class="panel tabs-panel">
							<?php mpio_most_recent_pages_checklist(); ?>
						</div>
						<div id="pages-tabs-2" class="panel tabs-panel">
							<?php mpio_all_pages_checklist(); ?>
						</div>
					</div>
					<div>
						<input type="submit" class="button-secondary submit-add-to-menu right" 
								value="<?php _e('Add to Menu', 'movipress'); ?>" name="add-page-menu-item" id="submit-page">
					</div>
				</div>
				
			</div><!-- #accordion -->
		</div><!-- /#menu-settings-column -->
		<div id="menu-management-liquid">
			<div id="menu-management">
				<form method="post" action="options.php">
					<div class="menu-edit">
						<?php 
							settings_fields('mpio_navigation_menu');
						?>
						<input id="navigation-menu" type="hidden" name="mpio_navigation_menu_options[navigation_menu]" size="50" value="<?php echo $options['navigation_menu']; ?>" />
						<div id="nav-menu-header">
							<div class="major-publishing-actions wp-clearfix">
								<div class="publishing-action">
									<?php submit_button( __('Save menu', 'movipress'), 'primary', 'save_menu_header', false); ?>
								</div><!-- END .publishing-action -->
							</div><!-- END .major-publishing-actions -->
						</div>
					
						<div id="post-body">
							<div id="post-body-content" class="wp-clearfix">
								<h3><?php _e('Menu Structure', 'movipress'); ?></h3>
								<div class="drag-instructions post-body-plain" >	
									<p><?php _e('Drag each item into the order you prefer.', 'movipress'); ?></p>
								</div>
								<div id="menu-instructions" class="post-body-plain menu-instructions-inactive">
									<p><?php _e('Add menu items from the column on the left.', 'movipress'); ?></p>
								</div>
								<ul class="menu" id="menu-to-edit">
									<?php 
										if(isset($options['navigation_menu'])){
											print_main_menu($options['navigation_menu']); 	
										}
									?>
								</ul>						
							</div>
						</div>
						<div id="nav-menu-footer">
							<div class="major-publishing-actions wp-clearfix">
								<span class="delete-action" >
									<a id="delete-all" class="submitdelete deletion menu-delete" href="#"><?php _e('Delete Menu', 'movipress'); ?></a>
								</span><!-- END .delete-action -->
								<div class="publishing-action">
									<?php submit_button( __('Save menu', 'movipress'), 'primary', 'save_menu_footer', false); ?>
								</div><!-- END .publishing-action -->
							</div><!-- END .major-publishing-actions -->
						</div>
					</div><!-- /.menu-edit -->
				</form>
			</div><!-- /#menu-management -->
		</div>
	</div>
</div>
<?php } ?>