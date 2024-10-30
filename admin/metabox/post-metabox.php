<?php 
/**
 * Movipress post-metabox.php
 *
 * Add Movipress Metabox for posts.
 *
 * @author 		Movipress Ltd.
 * @version     1.0.2
 */	
function mpio_add_meta_boxes( $post ){
	add_meta_box( 'mpio_meta_box', 'Movipress', 'mpio_build_meta_box', 'post', 'normal', 'default' );
}
add_action( 'add_meta_boxes_post', 'mpio_add_meta_boxes' );


/**
 * Build meta box
 */
function mpio_build_meta_box( $post ){
	global $wp_version;

	wp_enqueue_script('mpio-metabox-js');	
	if(metadata_exists( 'post', $post->ID, '_mpio_post_meta')){
		$values = json_decode(get_post_meta( $post->ID, '_mpio_post_meta',true ),true);	
		$snnp = isset($values['local']['snnp'] ) ? $values['local']['snnp'] : 0;
		$snup = isset($values['local']['snup'] ) ? $values['local']['snup'] : 0;
	}else{
		$values['remote']['show']=1;
		$notif_default = get_option('mpio_notifications_options');	
		$snnp = isset($notif_default['send_notification_new_post']) ? 1 : 0;
		$snup = isset($notif_default['send_notification_updated_post']) ? 1 : 0;
	}
	$list_template = isset($values['remote']['templates']['item'] ) ? $values['remote']['templates']['item'] : 'default';
	$single_template = isset($values['remote']['templates']['single'] ) ? $values['remote']['templates']['single'] : 'default';
	$content =  isset($values['remote']['content']) ? $values['remote']['content'] : '';
	

	$a='<p><input 	
			type="checkbox" 
			id="mpio-show" 
			name="mpio_show" 
			value="1" '.checked(1, $values['remote']['show'], false).'/>
	<label for="mpio_show">'. __('Show this article in the app', 'movipress').'</label></p>'; 
	echo $a;
	?>	
	

   <div id="mpio-metabox-tabs" class="mpio-metabox-tabs" style="background:#fff;border: 0px;">
        <ul class="category-tabs" style="background:#fff;border: 0px;border-bottom: 1px solid #ddd;">
            <li class="tabs mpio-tabs"><a href="#mpio-metabox-notifications-tab"><?php _e('Notifications', 'movipress'); ?></a></li>
            <li class="tabs mpio-tabs"><a href="#mpio-metabox-templates-tab"><?php _e('Templates', 'movipress'); ?></a></li>
			<li class="tabs mpio-tabs"><a href="#mpio-metabox-editor-tab"><?php _e('Content', 'movipress'); ?></a></li>            
        </ul>
        
		<div id="mpio-metabox-notifications-tab" class="tabs-panel">
			<ul>
		    	<li><input type="checkbox" 
						id="mpio_send_notification_new_post" 
						name="mpio_send_notification_new_post" 
						value="1" <?php checked(1, $snnp, true); ?>/>
					<label for="mpio_send_notification_new_post"><?php _e('Send when publishing', 'movipress'); ?></label>
				</li>
				<li><input type="checkbox" 
						id="mpio_send_notification_updated_post" 
						name="mpio_send_notification_updated_post" 
						value="1" <?php checked(1, $snup, true); ?>/>
						<label for="mpio_send_notification_updated_post"><?php _e('Send when updating', 'movipress'); ?></label>
				</li>
			</ul>
		</div>
		
		<div id="mpio-metabox-templates-tab" class="tabs-panel">
			<table>
				<tbody>
					<tr>
						<td><label for="mpio_single_template"><?php _e('Single view', 'movipress'); ?></label></td>
						<td>
							<select id="mpio_single_template" name="mpio_single_template">
						<?php	
							$selects = mpio_form_selects("single_template_values");
							$a='';
							foreach($selects as $select){
								$a.='<option value="'.$select['value'].'"'.selected($single_template, $select['value'], false).'>'.$select['name'].'</option>';
			    			}
			    			echo $a;
		    			?>
							</select>
						</td>
					</tr>
					<tr>
						<td><label for="mpio_list_template"><?php _e('List view', 'movipress'); ?></label></td>
						<td>
							<select id="mpio_list_template" name="mpio_list_template">
						<?php
							$selects = mpio_form_selects("list_template_values");
							$a='';
							foreach($selects as $select){
								$a.='<option value="'.$select['value'].'"'.selected($list_template, $select['value'], false).'>'.$select['name'].'</option>';
			    			}
			    			echo $a;
		    			?>			    			
							</select>
						</td> 
					</tr>
				</tbody>
			</table>
		</div>

		<div id="mpio-metabox-editor-tab" class="tabs-panel hidden">
			<p><input type="checkbox" 
				id="mpio-alt-content" 
				name="mpio_alt_content" 
				value="1" <?php checked(1, $content['alternative'], true); ?>/>
				<label for="mpio-alt-content"><?php  _e('Show alternative content in the app version', 'movipress'); ?></label>
			</p>
			<div id="mpio-wp-editor">
				<?php 
					$settings = array(
						            'wpautop' => true,
						            'media_buttons' => true,
						            'editor_height' => 425,
						            'textarea_rows' => '20', 
						            'tabindex' => '',
						            'editor_css' => '', 
						            'editor_class' => '',
						            'teeny' => true,
						            'dfw' => true,
						            'tinymce' => true,
						            'quicktags' => true 
						        );
					wp_editor($content['rendered'], 'mpio_editor', $settings); ?>
			</div>
		</div>
	 </div>
<?php      
}



function mpio_save_meta_box($post_id){
    if( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
     
    
     
    if( !current_user_can( 'edit_post', $post_id ) ) return;
    
    $show = isset($_POST['mpio_show']) ? 1 : 0;
    if($show){
	    
	    $snnp = isset($_POST['mpio_send_notification_new_post']) ? 1 : 0;
	    $snup = isset($_POST['mpio_send_notification_updated_post']) ? 1 : 0;
	    $local = array(
		    'snnp' 		=>	$snnp,
		    'snup'	 	=>	$snup,
	    );
	    
	    $alt_content = isset($_POST['mpio_alt_content']) ? 1 : 0;
	    
	    if(isset($_POST['mpio_editor'])){
			$rendered = html_entity_decode(sanitize_text_field(htmlentities(wp_kses_post($_POST['mpio_editor']))));
	    }else{
		    $rendered = 'disabled';
	    }
	    $content = array(
		    'alternative'	=>	$alt_content,
		    'rendered'	 	=>	$rendered,
	    );

	    $templates = array(
			"item"		=> $_POST['mpio_list_template'], 
			"single"	=> $_POST['mpio_single_template']
		);

	    $remote = array(
		    'show' 		=>	$show,
		    'content'	=>	$content,
		    'templates' =>	$templates,
	    );
	    
	    $mpio_post_meta = array(
		    'local' 	=>	$local,
		    'remote' 	=>	$remote,
	    );
	    	
	}else{
		$mpio_post_meta['remote']['show'] = 0;

	}
	update_post_meta($post_id, '_mpio_post_meta', json_encode($mpio_post_meta));    

}
add_action( 'save_post', 'mpio_save_meta_box' );
