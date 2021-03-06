<?php
/**
 * SCREETS © 2016
 *
 * Ajax functions
 *
 * COPYRIGHT © 2016 Screets d.o.o. All rights reserved.
 * This  is  commercial  software,  only  users  who have purchased a valid
 * license  and  accept  to the terms of the  License Agreement can install
 * and use this program.
 *
 * @package Chat X
 * @author Screets
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// Ajax requests
add_action( 'wp_ajax_chatx_ajax_cb', 'fn_scx_ajax_cb' );
add_action( 'wp_ajax_nopriv_chatx_ajax_cb', 'fn_scx_ajax_cb' );

/**
 * Ajax Callback
 *
 * @since Chat X (2.0)
 * @return void
 */
function fn_scx_ajax_cb() {

	// Response var
	$r = array();

	try {

		// Handling the supported actions:
		switch( $_GET['mode'] ) {

			// Front-end requests
			case 'offline': $r = fn_scx_ajax_send_email( $_POST ); break; // Offline foms
			case 'email_chat': $r = fn_scx_ajax_email_chat( $_POST ); break; 

			default:
				throw new Exception( 'Wrong AJAX action: ' . @$_REQUEST['mode'] );

		}

	} catch ( Exception $e ) {

		$r['err_code'] = $e->getCode();
		$r['error'] = $e->getMessage();

	}

	// Response output
	header( "Content-Type: application/json" );
	echo json_encode( $r );

	exit;

}

/**
 * Offline form
 *
 * @since Chat X (2.0)
 * @param array $fd Form data
 * @return array $r Response
 */
function fn_scx_ajax_send_email( $fd ) {

	global $ChatX;

	require_once SCX_PATH . '/core/fn.offline.php';

	// Get user agent data
	$agent = fn_scx_get_agent();

	// Get email subject
	$subject = ( !empty( $fd['subject'] ) ) ? $fd['subject'] : __( 'New message', 'chatx' );
	
	// Email parameters
	$to = $ChatX->opts->getOption( 'site-email' );
	$subject = apply_filters( 'scx_offline_email_subject', $subject );
	$headers = array( 'Content-Type: text/html; charset=UTF-8' );
	$site_name = $ChatX->opts->getOption( 'site-name' );
	$from_name = ( !empty( $fd['name' ] ) ) ? $fd['name'] : $site_name;

	// Sending by visitor if email provided in offline form
	if( !empty( $fd['email'] ) ) {

		$headers[] = 'From: "' . $from_name . '" <' . $fd['email'] . '>';
		$headers[] = 'Reply-To: "' . $from_name . '" <' . $fd['email'] . '>';

	// No email provided in offline form..
	} else {
		$headers[] = 'From: "' . $from_name . '" <' . $site_name . '>';
	}

	// 
	// Prepare body
	// 
	$body = '';

	$custom_fields = array();

	// Get email body
	foreach( $fd as $k => $v ) {

		// Get custom fields if exists
		if( substr($k, 0, 5) === 'xtra-' ) {
			$custom_fields[substr($k,5)] = $v;

		// Offline form data
		} else {

			$title = scx__( $ChatX->opts->getOption( 'offline-f-' . $k ) );

			$body .= '<strong>'. $title . ':</strong> ';
			$body .= '' . esc_html( stripslashes( $v ) ) . '<br />';

		}
	}

	// Render user-agent data
	$body .= '<br/><small>' . __( 'Visitor information', 'chatx' ) . ':</small><br/>';
	$body .= '<small><strong>' . __( 'Platform', 'chatx' ) . ':</strong> ' . $agent['browser'] . ' ' . $agent['browser_version'] . ' (' . $agent['os'] . ')</small><br/>';

	// Render other custom fields
	if( !empty( $custom_fields ) ) {
		$body .= '<small><strong>' . __( 'Location', 'chatx' ) . ':</strong> ' . $custom_fields['city'] . ', ' . $custom_fields['country'] . ', ' . $custom_fields['ip'] . '</small><br/>';
		$body .= '<small><strong>' . __( 'Current page', 'chatx' ) . ':</strong> <a href="' . $custom_fields['current-page'] .'">' . $custom_fields['current-page'] . '</a></small><br/>';
	}

	// Message meta data
	$meta = array_merge( $fd, $custom_fields );

	// Insert new offline message into database
	fn_scx_create_offline_msg( $fd['question'], $meta );

	// Send email
	if( fn_scx_send_email( $to, $subject, $body, $headers ) ) {
		return array( 'msg' => __( 'Successfully sent! Thank you', 'chatx' ) );
	}

}

/**
 * Email chat history
 *
 * @since Chat X (2.0)
 * @param array $fd Form data
 * @return array $r Response
 */
function fn_scx_ajax_email_chat( $fd ) {

	global $ChatX;

	require_once SCX_PATH . '/core/fn.offline.php';

	if( empty( $fd['content'] ) ) {
		throw new Exception( __( 'No messages found', 'chatx' ) );
	}

	if( !is_email( @$fd['email'] ) ) {
		throw new Exception( __( 'Email is invalid.', 'chatx' ) );
	}

	// Email parameters
	$to = $fd['email'];
	$site_name = $ChatX->opts->getOption( 'site-name' );
	$subject = apply_filters( 'scx_email_chat_subject', __( 'Chat History', 'chatx' ) . ' - ' . current_time( 'Y-m-d' ) );
	$headers = array( 'Content-Type: text/html; charset=UTF-8' );
	$site_reply_to = $ChatX->opts->getOption( 'site-reply-to' );

	$headers[] = 'From: "' . $site_name . '" <' . $site_reply_to . '>';
	$headers[] = 'Reply-To: "' . $site_name . '" <' . $site_reply_to . '>';

	// Sanitize message
	$body  = '<strong>' . __( 'Chat History', 'chatx' ) . '</strong><br><br>';
	$body .= wp_kses( $fd['content'], array( 'strong' => array(), 'small' => array(), 'div' => array(), 'p' => array(), 'span' => array(), 'a' => array('href' => array()) ) );
	$body .= '<br><small>Chat ID: ' . $fd['chat_id'] . '</small>';

	if( fn_scx_send_email( $to, $subject, $body, $headers ) ) {
		return array( 'msg' => __( 'Successfully sent! Thank you', 'chatx' ) );
	}

}

/**
 * Save chat transcript to server database
 *
 * @since Chat X (2.0)
 * @param int $data Messages data
 * @return array $r Response
 */
function fn_scx_ajax_save_transcript( $raw ) {

	global $wpdb;

	$db_cnv = SCX_PX . 'conversations';
	$db_msgs = SCX_PX . 'chat_messages';
	$i = 0;
	$cnv_id = null;

	foreach( $raw as $id => $json_data ) {

		// Decode the value
		$v = json_decode( stripslashes( $json_data ) );

		// 
		// Create conversation
		//
		if( $i == 0 ) {

			$wpdb->query( $wpdb->prepare(
				"INSERT INTO {$db_cnv} (`name`,`created_at`, `type`) VALUES( %s, %d, %s )",
				$v->name, 
				$v->created_at, 
				$v->type
			));

			$cnv_id = $wpdb->insert_id;
		
		// 
		// Insert chat messages
		//
		} else {
			
			$wpdb->query( $wpdb->prepare(
				"INSERT INTO {$db_msgs} ( `cnv_id`, `user_id`, `name`, `msg`, `time`) VALUES( %d, %s, %s, %s, %d )",
				$cnv_id, 
				$v->user_id, 
				$v->name, 
				$v->msg, 
				$v->time
			));

		}

		$i++;
	}

	// Create user or get exists one
	/*$wpdb->replace( SCX_PX . 'users', array(
		'user_id' => $user->id,
		'name' => $user->name,
		'username' => @$user->username,
		'email' => @$user->email,
		'phone' => @$user->phone,
		'ip' => ip2long( fn_scx_ip_addr() )
	), array( '%s', '%s', '%s', '%s', '%s', '%d' ) );

	$user_id = $wpdb->insert_id;*/
	
	

	// Update page history

	return array( 'ok' => 1 );

}