<?php 	
/**
 * Movipress article-templates.php
 *
 * Manage the templates/style for articles and pages.
 *
 * @author 		Movipress Ltd.
 * @version     1.1
 */
wp_enqueue_script('mpio-single-js');
$templates = get_option('mpio_single_templates');

if(isset($_POST['template'])&&isset($_POST['template_action'])){
	
	if (!current_user_can('manage_options')) {
        wp_die('Unauthorized user');
    }

	if(isset($_POST['template_nonce'])){
	    if (wp_verify_nonce($_POST['template_nonce'],'template_nonce_action')) {
		    $template=$_POST['template'];
			$action=$_POST['template_action'];
			if (array_key_exists($template, $templates)) {
				
				// Edit
				if($action==0){
					update_option('mpio_article_view_options', $templates[$template]);
				}
				
				// Delete
				if($action==1){			
					unset($templates[$template]);
					update_option('mpio_single_templates', $templates);
					$current_template = get_option('mpio_article_view_options');
					if($current_template['single_template_name']==$template){
						delete_option('mpio_article_view_options');
					}
				}
			}  
	    }
	}
}


$last_template=get_option('mpio_article_view_options');
$template =isset( $_GET[ 'template' ] ) ? $_GET[ 'template' ] : $last_template['single_template_name'];
$fields=mpio_get_settings_fields();

query_posts( 
		array(
			'posts_per_page' => 1,
			'post_type'   => 'post',
			'post_status' => 'publish',
		)); 

?>
<div class="mpio-row">
	<div class="mpio-column-options">
		<div class="manage-menus mpio-manage-menus">
			<form method="post">				    
			<?php
				wp_nonce_field('template_nonce_action', 'template_nonce' );
				$b='<select id="template" name="template">';
					    	
				foreach ($templates as $key => $value){
					$b.='<option value="'.$key.'"'.selected($template,$key,false).'>'.$key.'</option>';
				}
				$b.= '</select>';	
				echo $b;
			?>
			<select id="template_action" name="template_action">
				<option value="0"><?php _e('Edit', 'movipress'); ?></option>
				<option value="1"><?php _e('Delete', 'movipress'); ?></option>	    	
			</select>
			<input type="submit" value="<?php _e('Submit', 'movipress'); ?>" class="button button-primary button-large">
			</form>		
		</div>
		<form method="post" action="options.php">
		<?php settings_fields('mpio_article_view');?>	
		<div id="mpio-phone-accordion" class="mpio-accordion">
			<h3 class="mpio-accodion-general"><?php _e('General', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accodion-general-area">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Template name', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_template_name']);?></td> 
						</tr>
						<tr>
							<td class="mpio-right"><?php _e('Show Statusbar', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_show_statusbar']);?></td> 
						</tr>
						<tr class="statusbar_color">
							<td class="mpio-right"><?php _e('Colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_statusbar_color']);?></td> 
						</tr>
						<tr>
							<td class="mpio-right"><?php _e('Border', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_border']);?></td> 
						</tr>
						<tr class="single_bg_color">
							<td class="mpio-right"><?php _e('Body background', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_bg_color']);?></td> 
						</tr>
						<tr>
							<td class="mpio-right"><?php _e('Article background', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_item_color']);?></td> 
						</tr>
					</table>
				</div>
			</div>
			<h3 class="mpio-accordion-toolbar"><?php _e('Toolbar', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-toolbar-area">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Show', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_show_toolbar']);?></td> 
						</tr>											
						<tr class="single_toolbar_input this-content-title" data-content="<?php wp_title(); ?>">
							<td class="mpio-right"><?php _e('Title type', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_toolbar_title_type']);?></td> 
						</tr>
						<tr class="single_toolbar_input single_toolbar_custom_title">
							<td class="mpio-right"><?php _e('Title', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_toolbar_title']);?></td> 
						</tr>
						<tr class="single_toolbar_input">
							<td class="mpio-right"><?php _e('Font family', 'movipress'); ?></td>
							<td>
								<div class="select-editable">
									<?php mpio_do_settings_field($fields['single_toolbar_text_font']);mpio_print_font_families();?>
								</div>
							</td> 
						</tr>
						<tr class="single_toolbar_input">
							<td class="mpio-right"><?php _e('Text align', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_toolbar_text_align']);?></td> 
						</tr>
						<tr class="single_toolbar_input">
							<td class="mpio-right"><?php _e('Text size', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_toolbar_text_size']);?></td> 
						</tr>					
						<tr class="single_toolbar_input">
							<td class="mpio-right"><?php _e('Background', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_toolbar_color']);?></td> 
						</tr>
						<tr class="single_toolbar_input">
							<td class="mpio-right"><?php _e('Icons/text colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_toolbar_icons_color']);?></td> 
						</tr>
					</table>
				</div>
			</div>
			<h3 class="mpio-accordion-featured-img"><?php _e('Featured image', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-featured-img-area">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Show', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_show_featured_img']);?></td> 
						</tr>
						<tr class="single_img_input">
							<td class="mpio-right"><?php _e('Height', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_img_height']);?></td> 
						</tr>
						<tr class="single_img_input">
							<td class="mpio-right"><?php _e('Border', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_img_border']);?></td> 
						</tr>
					</table>
				</div>
			</div>
			<h3 class="mpio-accordion-title"><?php _e('Title', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-featured-img-area">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Show', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_show_title']);?></td> 
						</tr>
						<tr class="single_title_input">
							<div class="select-editable">
								<td class="mpio-right"><?php _e('Font family', 'movipress'); ?></td>
								<td><?php mpio_do_settings_field($fields['single_title_text_font']);?></td> 
							</div>
						</tr>
						<tr class="single_title_input">
							<td class="mpio-right"><?php _e('Text align', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_title_text_align']);?></td> 
						</tr>
						<tr class="single_title_input">
							<td class="mpio-right"><?php _e('Text size', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_title_text_size']);?></td> 
						</tr>
						<tr class="single_title_input">
							<td class="mpio-right"><?php _e('Text colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_title_text_color']);?></td> 
						</tr>
					</table>
				</div>
			</div>
			<h3 class="mpio-accordion-excerpt"><?php _e('Excerpt', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-excerpt-area">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Show', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_show_excerpt']);?></td> 
						</tr>						
						<tr class="single_excerpt_input">
							<div class="select-editable">
								<td class="mpio-right"><?php _e('Font family', 'movipress'); ?></td>
								<td><?php mpio_do_settings_field($fields['single_excerpt_text_font']);?></td> 
							</div>
						</tr>
						<tr class="single_excerpt_input">
							<td class="mpio-right"><?php _e('Text align', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_excerpt_text_align']);?></td> 
						</tr>
						<tr class="single_excerpt_input">
							<td class="mpio-right"><?php _e('Text size', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_excerpt_text_size']);?></td> 
						</tr>
						<tr class="single_excerpt_input">
							<td class="mpio-right"><?php _e('Text colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_excerpt_text_color']);?></td> 
						</tr>
					</table>
				</div>
			</div>	
			<h3 class="mpio-accordion-date"><?php _e('Author & date', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-date-area">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Show author', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_show_author']);?></td> 
						</tr>
						<tr>
							<div class="select-editable">
								<td class="mpio-right"><?php _e('Show date', 'movipress'); ?></td>
								<td><?php mpio_do_settings_field($fields['single_show_date']);?></td> 
							</div>
						</tr>
						<tr class="single_date_input">
							<div class="select-editable">
								<td class="mpio-right"><?php _e('Font family', 'movipress'); ?></td>
								<td><?php mpio_do_settings_field($fields['single_date_text_font']);?></td> 
							</div>
						</tr>
						<tr class="single_date_input">
							<td class="mpio-right"><?php _e('Text align', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_date_text_align']);?></td> 
						</tr>
						<tr class="single_date_input">
							<td class="mpio-right"><?php _e('Text size', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_date_text_size']);?></td> 
						</tr>
						<tr class="single_date_input">
							<td class="mpio-right"><?php _e('Text colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_date_text_color']);?></td> 
						</tr>

					</table>
				</div>
			</div>
			<h3 class="mpio-accordion-content"><?php _e('Content', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-content-area">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Show', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_show_content']);?></td> 
						</tr>						
						<tr class="single_content_input">
							<div class="select-editable">
								<td class="mpio-right"><?php _e('Font family', 'movipress'); ?></td>
								<td><?php mpio_do_settings_field($fields['single_content_text_font']);?></td> 
							</div>
						</tr>
						<tr class="single_content_input">
							<td class="mpio-right"><?php _e('Text align', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_content_text_align']);?></td> 
						</tr>
						<tr class="single_content_input">
							<td class="mpio-right"><?php _e('Text size', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_content_text_size']);?></td> 
						</tr>
						<tr class="single_content_input">
							<td class="mpio-right"><?php _e('Text colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_content_text_color']);?></td> 
						</tr>
						<tr class="single_content_input">
							<td class="mpio-right"><?php _e('Header', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_content_header']);?></td> 
						</tr>						
					</table>
				</div>
			</div>	
			<h3 class="mpio-accordion-comments"><?php _e('Comments button', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-comments-area">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Show', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_show_comments']);?></td> 
						</tr>
						<tr class="single_comments_input">
							<td class="mpio-right"><?php _e('Comments text', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_comments_text']);?></td> 
						</tr>
						<tr class="single_comments_input">
							<td class="mpio-right"><?php _e('Add comment text', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_to_comment_text']);?></td> 
						</tr>						
						<tr class="single_comments_input">
							<div class="select-editable">
								<td class="mpio-right"><?php _e('Font family', 'movipress'); ?></td>
								<td><?php mpio_do_settings_field($fields['single_comments_text_font']);?></td> 
							</div>
						</tr>
						<tr class="single_comments_input">
							<td class="mpio-right"><?php _e('Text size', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_comments_text_size']);?></td> 
						</tr>
						<tr class="single_comments_input">
							<td class="mpio-right"><?php _e('Text colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_comments_text_color']);?></td> 
						</tr>
						<tr class="single_comments_input">
							<td class="mpio-right"><?php _e('Button colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_comments_background']);?></td> 
						</tr>
					</table>
				</div>
			</div> 
			<h3 class="mpio-accordion-categories"><?php _e('Categories', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-categories-area">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Show', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_show_categories']);?></td> 
						</tr>
						<tr class="single_categories_input">
							<td class="mpio-right"><?php _e('Include Tags', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_include_tags']);?></td> 
						</tr>
						<tr class="single_categories_input">
							<td class="mpio-right"><?php _e('Title', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_categories_title']);?></td> 
						</tr>
						<tr class="single_categories_input">
							<td class="mpio-right"><?php _e('Separator', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_categories_separator']);?></td> 
						</tr>
						<tr class="single_categories_input">
							<td class="mpio-right"><?php _e('Ending character', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_categories_ending']);?></td> 
						</tr>
						<tr class="single_categories_input">
							<td class="mpio-right"><?php _e('Style', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_categories_style']);?></td> 
						</tr>
						<tr class="single_categories_input">
							<div class="select-editable">
								<td class="mpio-right"><?php _e('Font family', 'movipress'); ?></td>
								<td><?php mpio_do_settings_field($fields['single_categories_text_font']);?></td> 
							</div>
						</tr>
						<tr class="single_categories_input">
							<td class="mpio-right"><?php _e('Text align', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_categories_text_align']);?></td> 
						</tr>
						<tr class="single_categories_input">
							<td class="mpio-right"><?php _e('Text size', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_categories_text_size']);?></td> 
						</tr>
						<tr class="single_categories_input">
							<td class="mpio-right"><?php _e('Text colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_categories_text_color']);?></td> 
						</tr>
						<tr class="single_categories_input">
							<td class="mpio-right"><?php _e('Random colours', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_categories_random_colors']);?></td> 
						</tr>
						<tr class="single_categories_input">
							<td class="mpio-right"><?php _e('Links colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_categories_color']);?></td> 
						</tr>
					</table>
				</div>
			</div>
			<h3 class="mpio-accordion-tags"><?php _e('Tags', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-tags-area">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Show', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_show_tags']);?></td> 
						</tr>
						<tr class="single_tags_input">
							<td class="mpio-right"><?php _e('Title', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_tags_title']);?></td> 
						</tr>
						<tr class="single_tags_input">
							<td class="mpio-right"><?php _e('Separator', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_tags_separator']);?></td> 
						</tr>
						<tr class="single_tags_input">
							<td class="mpio-right"><?php _e('Ending character', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_tags_ending']);?></td> 
						</tr>
						<tr class="single_tags_input">
							<td class="mpio-right"><?php _e('Style', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_tags_style']);?></td> 
						</tr>
						<tr class="single_tags_input">
							<div class="select-editable">
								<td class="mpio-right"><?php _e('Font family', 'movipress'); ?></td>
								<td><?php mpio_do_settings_field($fields['single_tags_text_font']);?></td> 
							</div>
						</tr>
						<tr class="single_tags_input">
							<td class="mpio-right"><?php _e('Text align', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_tags_text_align']);?></td> 
						</tr>
						<tr class="single_tags_input">
							<td class="mpio-right"><?php _e('Text size', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_tags_text_size']);?></td> 
						</tr>
						<tr class="single_tags_input">
							<td class="mpio-right"><?php _e('Text colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_tags_text_color']);?></td> 
						</tr>
						<tr class="single_tags_input">
							<td class="mpio-right"><?php _e('Random colours', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_tags_random_colors']);?></td> 
						</tr>
						<tr class="single_tags_input">
							<td class="mpio-right"><?php _e('Links colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_tags_color']);?></td> 
						</tr>
					</table>
				</div>
			</div>
			<h3 class="mpio-accordion-author-details"><?php _e('Author details', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-author-details-area">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Show', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_show_authors']);?></td> 
						</tr>
						<tr class="single_authors_input">
							<div class="select-editable">
								<td class="mpio-right"><?php _e('Font family', 'movipress'); ?></td>
								<td><?php mpio_do_settings_field($fields['single_authors_text_font']);?></td> 
							</div>
						</tr>
						<tr class="single_authors_input">
							<td class="mpio-right"><?php _e('Text align', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_authors_text_align']);?></td> 
						</tr>
						<tr class="single_authors_input">
							<td class="mpio-right"><?php _e('Text size', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_authors_text_size']);?></td> 
						</tr>
						<tr class="single_authors_input">
							<td class="mpio-right"><?php _e('Text colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_authors_text_color']);?></td> 
						</tr>
						<tr class="single_authors_input">
							<td class="mpio-right"><?php _e('Background', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_authors_background']);?></td> 
						</tr>
					</table>
				</div>
			</div>
			<h3 class="mpio-accordion-related"><?php _e('Related content', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-related-area">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Show', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_show_related']);?></td> 
						</tr>
						<tr class="single_related_input">
							<td class="mpio-right"><?php _e('Related to', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_related_type']);?></td> 
						</tr>
						<tr class="single_related_input">
							<td class="mpio-right"><?php _e('Number', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_related_number']);?></td> 
						</tr>
						<tr class="single_related_input">
							<td class="mpio-right"><?php _e('Template', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['single_related_style']);?></td> 
						</tr>
					</table>
				</div>
			</div>								
		</div>
		<?php submit_button();?>	
		</form>
	</div>
	<div class="mpio-column-phone">
		<div class="mpio-phone-wrapper">
			<div class="mpio-phone-box">
				<div class="mpio-phone">
					<div class="mpio-toolbar mpio-clearfix">
				    	<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="mpio-toolbar-left-menu-button svg-colorize">
				        	<path d="M0 0h24v24H0z" fill="none"/>
							<path d="M3 18h18v-2H3v2zm0-5h18v-2H3v2zm0-7v2h18V6H3z"/>
						</svg>
						<span class="mpio-toolbar-title"><?php wp_title(); ?></span>

						<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="mpio-toolbar-right-menu-button svg-colorize">
							<path d="M0 0h24v24H0z" fill="none"/>
							<path d="M12 8c1.1 0 2-.9 2-2s-.9-2-2-2-2 .9-2 2 .9 2 2 2zm0 2c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2zm0 6c-1.1 0-2 .9-2 2s.9 2 2 2 2-.9 2-2-.9-2-2-2z"/>
						</svg>
						<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="mpio-toolbar-right-menu-button svg-colorize">
							<path d="M0 0h24v24H0z" fill="none"/>
							<path d="M18 16.08c-.76 0-1.44.3-1.96.77L8.91 12.7c.05-.23.09-.46.09-.7s-.04-.47-.09-.7l7.05-4.11c.54.5 1.25.81 2.04.81 1.66 0 3-1.34 3-3s-1.34-3-3-3-3 1.34-3 3c0 .24.04.47.09.7L8.04 9.81C7.5 9.31 6.79 9 6 9c-1.66 0-3 1.34-3 3s1.34 3 3 3c.79 0 1.5-.31 2.04-.81l7.12 4.16c-.05.21-.08.43-.08.65 0 1.61 1.31 2.92 2.92 2.92 1.61 0 2.92-1.31 2.92-2.92s-1.31-2.92-2.92-2.92z"/>
						</svg>
						<svg fill="#000000" height="24" viewBox="0 0 24 24" width="24" xmlns="http://www.w3.org/2000/svg" class="mpio-toolbar-right-menu-button svg-colorize">
						    <path d="M0 0h24v24H0z" fill="none"/>
						    <path d="M17 3H7c-1.1 0-1.99.9-1.99 2L5 21l7-3 7 3V5c0-1.1-.9-2-2-2zm0 15l-5-2.18L7 18V5h10v13z"/>
						</svg>
				 	</div>
				  <!-- END toolbar -->
				  	<div class="mpio-container">
					    <?php while(have_posts()) : the_post(); ?>
						<div class="mpio-card mpio-card-main">
							<?php if ( has_post_thumbnail() ) : ?>
									<?php the_post_thumbnail('post-thumbnail', array('class' => 'img-responsive responsive--full mpio-card-media', 'title' => '')); ?>
							<?php endif; ?>
							<div class="mpio-card-group">
						        <span class="mpio-card-title"><?php the_title(); ?></span>
						        <div class="mpio-card-excerpt">
									<?php echo get_the_excerpt(); ?>
						        </div>
						        <div class="mpio-card-author-date">
							        <span class="mpio-span-author">By <?php the_author();?></span>
							        <span class="mpio-span-separator"> | </span>
							        <span class="mpio-span-date"><?php the_date(); ?></span>
						        </div>
						        <div class="mpio-card-content">
							        <?php echo get_the_content(); ?>
						        </div>
						        <div class="mpio-center">
							        <div class="mpio-card-comments">
								        Comments
							        </div>
						        </div>
						        <div class="mpio-card-categories">
							        Categories: <?php echo the_category( ' - ' ); ?>
						        </div>
						        <div class="mpio-card-tags">
							        <?php echo the_tags( 'Tags: ', ' - ', '<br />' ); ?>

						        </div>
						        <div class="mpio-card-authors">
							        - Author Details -
						        </div>
						        <div class="mpio-card-related">
							        - Related content -
						        </div>						        
					    	</div>
					    	
						</div>
						<?php endwhile; ?>

				  	</div><!-- END mpio-container -->
				</div><!-- END mpio-phone -->
			</div><!-- END mpio-phone-box -->
		</div><!-- END mpio-phone-wrapper -->
	</div><!-- END mpio-column -->
</div><!-- END mpio-row -->