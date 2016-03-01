<?php
/**
 * SCREETS © 2016
 *
 * Plugin custom post types metaboxes
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
 * Get Metabox form options
 *
 * @since Chat X (2.0)
 * @return array $opts
 */
function fn_scx_get_metaboxes( $type = 'post') {

	switch( $type ) {
		
		// Post and custom post types
		case 'post':

			$opts = array(
				
			);

		break;

		// Topics
		case 'topic':
			$opts = array(
				array(
					'id' => 'topic-click-action',
					'name' => __( 'Click action', 'chatx' ),
					'desc' => __( 'This action will occur when visitor clicks the topic in the list', 'chatx' ),
					'options' => array(
						'show' => __( 'Show topic content', 'chatx' ),
						'connect_op' => __( 'Connect to operator', 'chatx' )
					),
					'default' => 'show',
					'type' => 'select'
				)
			);

		break;

	}

	return $opts;

}