<?php
/**
 * Movipress notifications.php
 *
 * Send notifications
 *
 * @author 		Movipress Ltd.
 * @version     1.0
 */
 	
// Checks if the notifications should be sent
function mpio_check_if_send_sms($new_status, $old_status, $post){
	if('post' == get_post_type($post) && $new_status === 'publish' && isset($_POST['mpio_show'])){
		if($old_status !== 'publish' &&  isset($_POST['mpio_send_notification_new_post']) ||
			($old_status === 'publish' && isset($_POST['mpio_send_notification_updated_post']))){
				mpio_send_notification($post);
		}
	}	
}
add_action('transition_post_status', 'mpio_check_if_send_sms',2,3);


// Send notification for scheduled posts
function mpio_check_if_send_future_sms($post_id){
	$values = json_decode(get_post_meta( $post_id, '_mpio_post_meta',true ),true);
	if(isset($values['local']['snnp'])){
		$post = get_post( $post_id );	
		mpio_send_notification($post);
	}
}
add_action('publish_future_post', 'mpio_check_if_send_future_sms');


/**
 * JSON for send notifications to every subscribed device
 *
 * See: https://firebase.google.com/docs/cloud-messaging/send-message
 */
function mpio_send_notification($post) {
	$time = 2419200;
	$priority = 0;
	$options = get_option('mpio_notifications_options');
    $fcm_api_key = $options['fcm_api_key'];
    $url ='https://fcm.googleapis.com/fcm/send';
    
	$headers = array(
		'Authorization'	=> 'key='.$fcm_api_key,
		'Content-Type' 	=> 'application/json'
	);
	
	$data = array(
		'mpio_id'		=> intval($post->ID),						
		'mpio_title'	=> $post->post_title,
		'mpio_subtitle'	=> $post->post_excerpt
	);
	
	$fields = array(
		'delay_while_idle' 	=> true,
		'time_to_live' 		=> intval($time),
		'to'				=> '/topics/ALL',
		'priority' 			=> intval($priority),
		'data' 				=> $data			
	);
	
	$result = wp_remote_post($url, array(
		'method' 	=> 'POST',
		'headers' 	=> $headers,
		'body' 		=> json_encode($fields)
	));
}
