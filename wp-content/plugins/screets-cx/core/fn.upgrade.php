<?php
/**
 * SCREETS © 2016
 *
 * Upgrade functions
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

/**
 * Upgrade the plugin
 *
 * @since Chat X (2.0)
 * @return void
 */
function fn_scx_upgrade( $last_version ) {

	global $wpdb, $ChatX;;

	// Update version
	update_option( 'scx_version', SCX_VERSION );

	// Activate the plugin
	$ChatX->activate();

	// 1.4.3 or older versions
	if( version_compare( $last_version, '1.4.3', '<=' ) ) {

		// Change all "cx_offline_msg" post types with "scx_offline_msg"
		$wpdb->update( $wpdb->posts, array( 'post_type' => 'scx_offline_msg' ), array( 'post_type' => 'cx_offline_msg' ), array( '%s' ), array( '%s' ) );

		// Get old options
		$old_opts = get_option('screetschatx-opts');

		// Transfer old options to new one
		if( !empty( $old_opts ) ) {
			$ChatX->opts->setInternalAdminPageOption( 'site-name', @$old_opts['site_name'] );
			$ChatX->opts->setInternalAdminPageOption( 'site-url', @$old_opts['site_url'] );
			$ChatX->opts->setInternalAdminPageOption( 'site-email', @$old_opts['admin_emails'] );
			$ChatX->opts->setInternalAdminPageOption( 'site-logo', @$old_opts['default_avatar'] );
			
			$ChatX->opts->setInternalAdminPageOption( 'avatar-size', @$old_opts['avatar_size'] );
			$ChatX->opts->setInternalAdminPageOption( 'avatar-radius', @$old_opts['avatar_radius'] );
			
			$ChatX->opts->setInternalAdminPageOption( 'btn-size', @$old_opts['btn_width'] );
			$ChatX->opts->setInternalAdminPageOption( 'btn-title-online', @$old_opts['when_online'] );
			$ChatX->opts->setInternalAdminPageOption( 'btn-title-offline', @$old_opts['when_offline'] );

			$ChatX->opts->setInternalAdminPageOption( 'prechat-greeting', @$old_opts['prechat_msg'] );
			$ChatX->opts->setInternalAdminPageOption( 'offline-greeting', @$old_opts['offline_body'] );
			$ChatX->opts->setInternalAdminPageOption( 'welcome-msg', @$old_opts['welc_msg'] );
			$ChatX->opts->setInternalAdminPageOption( 'str-reply-ph', @$old_opts['popup_reply_ph'] );
			
			$ChatX->opts->setInternalAdminPageOption( 'primary-color', @$old_opts['primary_color'] );
			$ChatX->opts->setInternalAdminPageOption( 'link-color', @$old_opts['link_color'] );
			$ChatX->opts->setInternalAdminPageOption( 'popup-size', @$old_opts['widget_width'] );
			$ChatX->opts->setInternalAdminPageOption( 'widget-pos', @$old_opts['widget_position'] );

			// Save options
			$ChatX->opts->saveInternalAdminPageOptions();
		}

		// Delete old database data
		delete_option( 'cx_version' );
		delete_option( 'cx_error' );
		delete_option( 'cx_security_last_update' );
		delete_option( 'screetschatx-opts' );

	}

}
