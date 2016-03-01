<?php
/**
 * SCREETS © 2016
 *
 * Plugin post type(s)
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
 * Register post types
 *
 * @since Chat X (2.0)
 * @return void
 */
function fn_scx_post_types() {

	// Register Offline Messages post type
	$labels = array(
		'name'                => _x( 'Offline Messages', 'Post Type General Name', 'chatx' ),
		'singular_name'       => _x( 'Offline Message', 'Post Type Singular Name', 'chatx' ),
		'menu_name'           => __( 'Offline Message', 'chatx' ),
		'parent_item_colon'   => __( 'Parent Offline Message:', 'chatx' ),
		'all_items'           => __( 'All Offline Messages', 'chatx' ),
		'view_item'           => __( 'View Offline Message', 'chatx' ),
		'add_new_item'        => __( 'Add New Offline Message', 'chatx' ),
		'add_new'             => __( 'New Offline Message', 'chatx' ),
		'edit_item'           => __( 'Edit Offline Message', 'chatx' ),
		'update_item'         => __( 'Update Offline Message', 'chatx' ),
		'search_items'        => __( 'Search offline message', 'chatx' ),
		'not_found'           => __( 'No offline message found', 'chatx' ),
		'not_found_in_trash'  => __( 'No offline message found in Trash', 'chatx' ),
	);

	$args = array(
		'labels'              => $labels,
		'supports'            => array( 'title' ),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => 'scx_console',
		'show_in_nav_menus'   => false,
		'show_in_admin_bar'   => false,
		'menu_position'       => 60,
		'menu_icon'           => '',
		'can_export'          => true,
		'has_archive'         => false,
		'exclude_from_search' => true,
		'publicly_queryable'  => false,
		'rewrite' 			  => false,
		'capability_type'     => 'page',
		'capabilities' 		  => array(
			// 'create_posts' => false
		)
	);
	register_post_type( 'scx_offline_msg', $args );

	/*$labels = array(
		'name'                => _x( 'Support Topics', 'Post Type General Name', 'chatx' ),
		'singular_name'       => _x( 'Support Topic', 'Post Type Singular Name', 'chatx' ),
		'menu_name'           => __( 'Support Topics', 'chatx' ),
		'name_admin_bar'      => __( 'Support Topics', 'chatx' ),
		'parent_item_colon'   => __( 'Parent Item:', 'chatx' ),
		'all_items'           => __( 'All Items', 'chatx' ),
		'add_new_item'        => __( 'New Support Topic', 'chatx' ),
		'add_new'             => __( 'Add New', 'chatx' ),
		'new_item'            => __( 'New Item', 'chatx' ),
		'edit_item'           => __( 'Edit Item', 'chatx' ),
		'update_item'         => __( 'Update Item', 'chatx' ),
		'view_item'           => __( 'View Item', 'chatx' ),
		'search_items'        => __( 'Search Item', 'chatx' ),
		'not_found'           => __( 'Not found', 'chatx' ),
		'not_found_in_trash'  => __( 'Not found in Trash', 'chatx' ),
	);
	register_post_type( 'scx_topic', array(
		'label'               => __( 'scx_topic', 'chatx' ),
		'description'         => __( 'Support Topics', 'chatx' ),
		'labels'              => $labels,
		'supports'            => array( 'title', 'editor' ),
		'taxonomies'          => array( 'scx_support_cat' ),
		'hierarchical'        => false,
		'public'              => false,
		'show_ui'             => true,
		'show_in_menu'        => 'scx_console', // Setup top level menu
		'menu_position'       => 5,
		'show_in_admin_bar'   => true,
		'show_in_nav_menus'   => true,
		'can_export'          => true,
		'has_archive'         => true,		
		'exclude_from_search' => false,
		'publicly_queryable'  => true,
		'capability_type'     => 'post',
	) );

	//
	// Support category taxonomy
	//
	$labels = array(
		'name'                       => _x( 'Support Categories', 'Taxonomy General Name', 'chatx' ),
		'singular_name'              => _x( 'Support Category', 'Taxonomy Singular Name', 'chatx' ),
		'menu_name'                  => __( 'Support Categories', 'chatx' ),
		'all_items'                  => __( 'Support Categories', 'chatx' ),
		'parent_item'                => __( 'Parent item', 'chatx' ),
		'parent_item_colon'          => __( 'Parent item', 'chatx' ),
		'new_item_name'              => __( 'New Support Category', 'chatx' ),
		'add_new_item'               => __( 'New Support Category', 'chatx' ),
		'edit_item'                  => __( 'Edit Support Category', 'chatx' ),
		'update_item'                => __( 'Update', 'chatx' ),
		'view_item'                  => __( 'View', 'chatx' ),
		'separate_items_with_commas' => __( 'Separate items with commas', 'chatx' ),
		'add_or_remove_items'        => __( 'Add or remove', 'chatx' ),
		'choose_from_most_used'      => __( 'Choose from the most used', 'chatx' ),
		'popular_items'              => __( 'Popular Support Categories', 'chatx' ),
		'search_items'               => __( 'Search', 'chatx' ),
		'not_found'                  => __( 'Not Found', 'chatx' ),
	);
	register_taxonomy( 'scx_support_cat', 'scx_topic', array(
		'labels'					=> $labels,
		'hierarchical'				=> true,
		'public'					=> false,
		'show_ui'					=> true,
		'show_admin_column'			=> true,
		'show_in_nav_menus'			=> true,
		'show_tagcloud'				=> false
	));*/

}

add_action( 'init', 'fn_scx_post_types', 0 );



