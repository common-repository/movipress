<?php
/**
 * Movipress list-templates.php
 *
 * Manage the templates/style for last posts, searches,
 * categories, tags and authors and pages.
 *
 * @author 		Movipress Ltd.
 * @version     1.0
 */
wp_enqueue_script('mpio-list-js');	
$templates = get_option('mpio_list_templates');

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
					update_option('mpio_list_view_options', $templates[$template]);
				}
				
				// Delete
				if($action==1){			
					unset($templates[$template]);
					update_option('mpio_list_templates', $templates);
					$current_template = get_option('mpio_list_view_options');
					if($current_template['list_template_name']==$template){
						delete_option('mpio_list_view_options');
					}
				}
			}  
	    }
	}
}


$last_template= get_option('mpio_list_view_options') ? get_option('mpio_list_view_options'):"";
$template =isset( $_GET[ 'template' ] ) ? $_GET[ 'template' ] : $last_template['list_template_name'];
$fields=mpio_get_settings_fields();

query_posts( 
		array(
			'posts_per_page' => 5,
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
		<?php settings_fields('mpio_list_view');?>	
		<div id="mpio-phone-accordion" class="mpio-accordion">
			<h3 class="mpio-accodion-general"><?php _e('General', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accodion-general-area">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Template name', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['list_template_name']);?></td> 
						</tr>
						<tr>
							<td class="mpio-right"><?php _e('Show Statusbar', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['show_statusbar']);?></td> 
						</tr>
						<tr class="statusbar_color">
							<td class="mpio-right"><?php _e('Colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['list_statusbar_color']);?></td> 
						</tr>
						<tr>
							<td class="mpio-right"><?php _e('Border', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['list_border']);?></td> 
						</tr>
						<tr class="bg_color">
							<td class="mpio-right"><?php _e('Background', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['bg_color']);?></td> 
						</tr>
					</table>
				</div>
			</div>
			<h3 class="mpio-accordion-toolbar"><?php _e('Toolbar', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-toolbar-area">
				<div class="mpio-field-group">
					<table>						
						<tr class="this-content-title" data-content="<?php wp_title(); ?>">
							<td class="mpio-right"><?php _e('Title type', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['toolbar_title_type']);?></td> 
						</tr>
						<tr class="toolbar_custom_title">
							<td class="mpio-right"><?php _e('Custom title', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['toolbar_title']);?></td> 
						</tr>
						<tr>
							<td class="mpio-right"><?php _e('Font family', 'movipress'); ?></td>
							<td>
								<div class="select-editable">
									<?php mpio_do_settings_field($fields['toolbar_text_font']);mpio_print_font_families();?>
								</div>
							</td> 
						</tr>
						<tr>
							<td class="mpio-right"><?php _e('Text align', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['toolbar_text_align']);?></td> 
						</tr>
						<tr>
							<td class="mpio-right"><?php _e('Text size', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['toolbar_text_size']);?></td> 
						</tr><tr>
							<td class="mpio-right"><?php _e('Background', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['toolbar_color']);?></td> 
						</tr>
						<tr>
							<td class="mpio-right"><?php _e('Icons/text colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['toolbar_icons_color']);?></td> 
						</tr>
					</table>
				</div>
			</div>
			<h3 class="mpio-accordion-image"><?php _e('Article Item', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-image-area">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Background', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_background_color']);?></td> 
						</tr>
						<tr>
							<td class="mpio-right"><?php _e('Allow mixed styles', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_allow_mixed']);?></td> 
						</tr>
						<tr class="custom_border">
							<td class="mpio-right"><?php _e('Custom border', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_custom_border']);?></td> 
						</tr>
						<tr class="item_border">
							<td class="mpio-right"><?php _e('Border', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_border']);?></td> 
						</tr>
					</table>
				</div>
			</div>			
			<h3 class="mpio-accordion-image"><?php _e('Featured image', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-image-area">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Position', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_template']);?></td> 

						</tr>
						<tr class="item_img_height">
							<td class="mpio-right"><?php _e('Height', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_img_height']);?></td> 
						</tr>
						<tr class="item_img_border">
							<td class="mpio-right"><?php _e('Border', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_img_border']);?></td> 
						</tr>
						<tr class="item_vertical_align">
							<td class="mpio-right"><?php _e('Vertical align', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_data_vertical_align']);?></td> 
						</tr>
					</table>
				</div>
			</div>
			<h3 class="mpio-accordion-title"><?php _e('Title', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-title-area">
				<div class="mpio-field-group">
					<table>
						
						<tr>
							<td class="mpio-right"><?php _e('Show', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_show_title']);?></td> 

						</tr>
						<tr class="item_title_text_font">
							<div class="select-editable">
								<td class="mpio-right"><?php _e('Font family', 'movipress'); ?></td>
								<td><?php mpio_do_settings_field($fields['item_title_text_font']);?></td> 
							</div>
						</tr>
						<tr class="item_title_text_align">
							<td class="mpio-right"><?php _e('Text align', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_title_text_align']);?></td> 
						</tr>
						<tr class="item_title_text_size">
							<td class="mpio-right"><?php _e('Text size', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_title_text_size']);?></td> 
						</tr>
						<tr class="item_title_text_color">
							<td class="mpio-right"><?php _e('Text colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_title_text_color']);?></td> 
						</tr>
					</table>
				</div>
			</div>
			<h3 class="mpio-accordion-date"><?php _e('Author and date', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-toolbar-date">
				<div class="mpio-field-group">
					<table>						
						<tr>
							<td class="mpio-right"><?php _e('Show author', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_show_author']);?></td> 
						</tr>
						<tr>
							<div class="select-editable">
								<td class="mpio-right"><?php _e('Show date', 'movipress'); ?></td>
								<td><?php mpio_do_settings_field($fields['item_show_date']);?></td> 
							</div>
						</tr>
						<tr class="item_date_text_font">
							<div class="select-editable">
								<td class="mpio-right"><?php _e('Font family', 'movipress'); ?></td>
								<td><?php mpio_do_settings_field($fields['item_date_text_font']);?></td> 
							</div>
						</tr>
						<tr class="item_date_text_align">
							<td class="mpio-right"><?php _e('Text align', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_date_text_align']);?></td> 
						</tr>
						<tr class="item_date_text_size">
							<td class="mpio-right"><?php _e('Text size', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_date_text_size']);?></td> 
						</tr>
						<tr class="item_date_text_color">
							<td class="mpio-right"><?php _e('Text colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_date_text_color']);?></td> 
						</tr>
					</table>
				</div>
			</div>
			<h3 class="mpio-accordion-content"><?php _e('Content', 'movipress'); ?></h3>
			<div class="mpio-options-group mpio-accordion-toolbar-content">
				<div class="mpio-field-group">
					<table>
						<tr>
							<td class="mpio-right"><?php _e('Show', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_show_excerpt']);?></td> 
						</tr>
						<tr class="item_excerpt_text_font">
							<div class="select-editable">
								<td class="mpio-right"><?php _e('Font family', 'movipress'); ?></td>
								<td><?php mpio_do_settings_field($fields['item_excerpt_text_font']);?></td> 
							</div>
						</tr>
						<tr class="item_excerpt_text_align">
							<td class="mpio-right"><?php _e('Text align', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_excerpt_text_align']);?></td> 
						</tr>
						<tr class="item_excerpt_text_size">
							<td class="mpio-right"><?php _e('Text size', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_excerpt_text_size']);?></td> 
						</tr>
						<tr class="item_excerpt_text_color">
							<td class="mpio-right"><?php _e('Text colour', 'movipress'); ?></td>
							<td><?php mpio_do_settings_field($fields['item_excerpt_text_color']);?></td> 
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
							<path d="M15.5 14h-.79l-.28-.27C15.41 12.59 16 11.11 16 9.5 16 5.91 13.09 3 9.5 3S3 5.91 3 9.5 5.91 16 9.5 16c1.61 0 3.09-.59 4.23-1.57l.27.28v.79l5 4.99L20.49 19l-4.99-5zm-6 0C7.01 14 5 11.99 5 9.5S7.01 5 9.5 5 14 7.01 14 9.5 11.99 14 9.5 14z"/>
							<path d="M0 0h24v24H0z" fill="none"/>
						</svg>
				 	</div>
				  <!-- END toolbar -->
				  	<div class="mpio-container">  
<?php while(have_posts()) : the_post(); ?>
		<div class="mpio-card mpio-card-main">
			<div class="mpio-card-group">
		        <span class="mpio-card-title"><?php the_title(); ?></span>
		        <div class="mpio-card-author-date">
			        <span class="mpio-span-author">By <?php the_author();?></span>
			        <span class="mpio-span-separator"> | </span>
			        <span class="mpio-span-date"><?php the_date(); ?></span>
		        </div>
		        
		        <div class="mpio-card-excerpt">
			        <?php echo wp_trim_words( get_the_content(), 30, '...' ); ?>
		        </div>
	    	</div>
	    	<div class="img-url" data-img="<?php the_post_thumbnail_url(); ?>"></div>
<?php 		if ( has_post_thumbnail() ) :
				the_post_thumbnail('post-thumbnail', array('class' => 'img-responsive responsive--full mpio-card-media', 'title' => ''));
			endif; 
?>			
		</div>						
<?php endwhile; ?>
				  	</div><!-- END mpio-container -->
				</div><!-- END mpio-phone -->
			</div><!-- END mpio-phone-box -->
		</div><!-- END mpio-phone-wrapper -->
	</div><!-- END mpio-column -->
</div><!-- END mpio-row -->