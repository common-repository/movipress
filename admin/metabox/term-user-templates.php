<?php
/**
 * Movipress term-user-templates.php
 *
 * Link templates to categories, tags and users.
 *
 * @author 		Movipress Ltd.
 * @version     1.0
 */
	
function mpio_paint_list_templates_select($template){
	$templates = get_option('mpio_list_templates');
	$a='<select id="mpio_template" name="mpio_template">';
	$a.='<option value="default"'.selected($template,'default',false).'>default</option>';    	
	foreach ($templates as $key => $value){
		$a.='<option value="'.$key.'"'.selected($template,$key,false).'>'.$key.'</option>';
	}
	$a.= '</select>';	
	$a.= '<p class="description">'.__('Select a custom template or leave it as default.', 'movipress').'</p>';
	echo $a;
}

// Select template for categories and tags
function mpio_add_term_custom_template($term){
	$term_template = get_term_meta($term->term_id, '_mpio_template', true);
?> 
    <tr class="form-field">
        <th scope="row" valign="top"><label for="mpio_template"><?php _e('Movipress Template', 'movipress'); ?></label></th>
        <td>
			<?php mpio_paint_list_templates_select($term_template);	?>
        </td>
    </tr>
<?php
}
add_action ( 'category_edit_form_fields', 'mpio_add_term_custom_template');
add_action ( 'post_tag_edit_form_fields', 'mpio_add_term_custom_template');


// Save template for a term
function mpio_save_term_template($term_id){    
    if(isset($_POST['mpio_template'])){
		update_term_meta($term_id, '_mpio_template', $_POST['mpio_template']);
    }
}
add_action ( 'edited_category', 'mpio_save_term_template');
add_action ( 'edited_post_tag', 'mpio_save_term_template');


// Select template for an user
function mpio_add_user_custom_template($user){ 
	if (current_user_can('edit_users')){
		$user_template = get_the_author_meta('mpio_template', $user->ID);
?>
		<h2>Movipress</h2>
		<table class="form-table">
			<tbody>
				<tr>
					<th><label for="mpio_template"><?php _e('Movipress Template', 'movipress'); ?></label></th>
					<td>
						<?php mpio_paint_list_templates_select($user_template);	?>
					</td>
				</tr>
			</tbody>
		</table>
<?php 
	}
}
add_action('show_user_profile', 'mpio_add_user_custom_template');
add_action('edit_user_profile', 'mpio_add_user_custom_template');


// Save template for an user
function mpio_save_user_template($user_id){
	if (current_user_can('edit_users')){
		if(isset($_POST['mpio_template'])){
			update_user_meta( $user_id, 'mpio_template', $_POST['mpio_template'] );	
	    }
    }
}
add_action('personal_options_update', 'mpio_save_user_template');
add_action('edit_user_profile_update', 'mpio_save_user_template');
