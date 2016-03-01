<?php
/**
 * Plugin Name: Screets Chat X
 * Plugin URI: http://apps.screets.org/chat-x-night-bird/
 * Description: <strong>Night Bird.</strong> Allows you to chat directly with your visitors on your website
 * Version: 2.1.1
 * Author: Screets Team
 * Author URI: http://www.screets.com
 * Requires at least: 4.0
 * Tested up to: 4.4.1
 *
 *
 * Text Domain: chatx
 * Domain Path: /languages/
 *
 * @package Chat X
 * @author Screets
 *
 * COPYRIGHT © 2016 Screets d.o.o. All rights reserved.
 * This  is  commercial  software,  only  users  who have purchased a valid
 * license  and  accept  to the terms of the  License Agreement can install
 * and use this program.
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $wpdb;

// Some useful constants
define( 'SCX_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );
define( 'SCX_URL', plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) );
define( 'SCX_EDITION', 'Night Bird' );
define( 'SCX_PX', $wpdb->prefix . 'scx_' ); // DB Table prefix
define( 'SCX_SLUG', 'screets-cx' ); // Using for updating as slug and in options as prefix

define( 'SCX_FIREBASE_VERSION', '2.3.2' );

require_once( SCX_PATH . '/core/library/titan-framework/titan-framework-embedder.php' );

class ChatX 
{

	/**
	 * @var Object
	 */
	var $opts;

	/**
	 * @var Object
	 */
	var $__opts;

	/**
	 * @var object 	Plugin meta data
	 */
	var $meta;

	/**
	 * Constructor
	 *
	 * @access public
	 * @return void
	 */
	public function __construct() {

		// Check that function get_plugin_data exists
		if ( !function_exists( 'get_plugin_data' ) )
			require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

		// Get plugin meta
		$this->meta = get_plugin_data( __FILE__, false );

		// Define app meta constants
		define( 'SCX_NAME', $this->meta['Name'] );
		define( 'SCX_NAME_SHORT', str_replace( 'Screets ', '', SCX_NAME ) );
		define( 'SCX_VERSION', $this->meta['Version'] );
		define( 'SCX_PLUGIN_URL', $this->meta['PluginURI'] );

		// Install plugin
		register_activation_hook( __FILE__, array( $this, 'activate' ) );

		// Include required files
		$this->includes();

		// Get options
		add_action( 'tf_done', array( &$this, 'get_opts' ) );

		// Actions
		add_action( 'init', array( &$this, 'init' ), 10 );
		add_action( 'plugins_loaded', array( &$this, 'load_plugin_textdomain' ) );
		
		// Loaded action
		do_action( 'scx_loaded' );

	}


	/**
	 * Get options
	 *
	 * @access public
	 * @return void
	 */
	function get_opts() {
		
		// Get options framework
		$this->opts = TitanFramework::getInstance( SCX_SLUG );

		if( function_exists( 'pll_register_string' ) ) {
			foreach( $this->__opts as $name => $opt ) {
				$string = $this->opts->getOption( $opt['id'] );
				pll_register_string( $name, $string, 'Screets Chat X', $opt['multiline'] );
			}
		}
		
	}


	/**
	 * Init the plugin when WordPress initializes
	 *
	 * @access public
	 * @return void
	 */
	function init() {

		// Before init action
		do_action( 'before_scx_init' );

		if( current_user_can( 'scx_answer_visitor' ) )
			define( 'SCX_OP', true ); // User is operator?
		else
			define( 'SCX_VISITOR', true ); // User is visitor?

		// WPML support (updates opts)
		// if( function_exists( 'icl_register_string' ) )
			// $this->WPML( $this->opts );

		// Initialization action
		do_action( 'scx_init' );

	}

	/**
	 * Include required core files
	 *
	 * @access public
	 * @return void
	 */
	function includes() {

		// Include core files
		require SCX_PATH . '/core/fn.shortcodes.php';
		require SCX_PATH . '/core/fn.firebase.php';
		require SCX_PATH . '/core/fn.post-types.php';
		require SCX_PATH . '/core/fn.common.php';
		require SCX_PATH . '/core/fn.users.php';
		require SCX_PATH . '/core/fn.security.php';
		require SCX_PATH . '/core/fn.options.php';

		// Spyc library
		if ( !class_exists( 'Spyc' ) )
			require SCX_PATH . '/core/library/Spyc.php';

		// Back-end includes
		if( is_admin() ) {
			require SCX_PATH . '/back-end.php';

		// Front-end includes
		} else {
			require SCX_PATH . '/core/fn.skin.php';
			require SCX_PATH . '/front-end.php';
		}

		// AJAX includes
		if ( defined( 'DOING_AJAX' ) ) {
			$this->ajax_includes();
		}

	}

	/**
	 * Include AJAX files
	 *
	 * @access public
	 * @return void
	 */
	function ajax_includes() {

		// Include back-end files
		require_once SCX_PATH . '/core/library/Pushover.php';
		require SCX_PATH . '/core/fn.ajax.php';

	}


	/**
	 * Get Ajax URL
	 *
	 * @access public
	 * @return string
	 */
	function ajax_url() {

		return str_replace( array('https:', 'http:'), '', admin_url( 'admin-ajax.php' ) );

	}


	/**
	 * Localization
	 *
	 * @access public
	 * @return void
	 */
	function load_plugin_textdomain() {

		load_plugin_textdomain( 'chatx', false, SCX_PATH . '/languages/' );

	}

	/**
	 * Get token
	 *
	 * @access public
	 * @return string token
	 */
	public function get_token() {

		// Load required libraries
		if( !class_exists( 'Services_FirebaseTokenGenerator' ) )
			require_once SCX_PATH . '/core/library/firebase/FirebaseToken.php';

		// Create token generator
		$generator = new Services_FirebaseTokenGenerator( trim( $this->opts->getOption('app-secret') ) );

		// Admin user
		$is_admin = ( current_user_can( 'manage_options' ) ) ? true : false;

		// Debugging?
		$debug = $this->opts->getOption('debug' );
		
		// Create token
		$token = $generator->createToken(

			array(
				'uid' => fn_scx_get_user_id(),
				// Is current user an chat operator?
				'is_op' => ( $is_admin || current_user_can( 'scx_answer_visitor' ) ) ? true : false
			),

			array(

				// Admin role disables all security rules for this client
				// This will provide the client with read and write access to your entire Firebase
				'admin' => ( $is_admin ) ? true : false,

				// Debugging
				'debug' => ( !empty( $debug ) ) ? true : false

			)

		);

		return $token;

	}

	/**
	 * Activate plugin
	 *
	 * @access public
	 * @return void
	 */
	public function activate() {
		
		// Register operator role
		add_role(
			'cx_op',
			'Chat X Operator',
			array(
				'read' => true, // True allows that capability
				'edit_posts' => true,
				'delete_posts' => false // Use false to explicitly deny
			)
		);

		// Get roles
		$admin_role = get_role( 'administrator' );
		$op_role = get_role( 'cx_op' );

		// Add chat capability to admin and operators
		$admin_role->add_cap( 'scx_answer_visitor' );
		$admin_role->add_cap( 'scx_see_logs' );
		$admin_role->add_cap( 'scx_manage_chat_options' );

		$op_role->add_cap( 'scx_answer_visitor' );
		$op_role->add_cap( 'scx_see_logs' );

	}

	/**
	 * Add WPML support to the plguin
	 *
	 * @return void
	 */
	function WPML( $opts_set ) {

		/*foreach( $opts_set as $opt ) {

			if( !empty( $opt['translate'] ) ) {

				// Register strings to WPML
				icl_register_string( 'Screets Popup Art', $opt['name'], $opt['id'] );

				// Update translations in options
				$this->opts[ $opt['id'] ] = icl_t( 'Screets Popup Art', $opt['name'], $this->opts[ $opt['id'] ] );

			}
		}*/

	}

	/**
	 * Load common scripts
	 *
	 * @access public
	 * @return void
	 */
	function common_scripts() {

		// Twemoji JS
		wp_register_script(
			'twemoji',
			'//twemoji.maxcdn.com/twemoji.min.js',
			null,
			SCX_VERSION,
			true
		);
		wp_enqueue_script( 'twemoji' );

		// Polyfill JS
		wp_register_script(
			'scx-polyfill',
			SCX_URL . '/assets/js/scx.polyfill.js',
			null,
			SCX_VERSION,
			true
		);
		wp_enqueue_script( 'scx-polyfill' );

		// Firebase JS
		wp_register_script(
			'scx-firebase',
			SCX_URL . '/assets/js/scx.firebase.js',
			array( 'firebase' ),
			SCX_VERSION,
			true
		);
		wp_enqueue_script( 'scx-firebase' );

		// Firebase JS
		wp_register_script(
			'firebase',
			SCX_URL . '/assets/js/firebase.min.js',
			null,
			SCX_FIREBASE_VERSION,
			true
		);
		wp_enqueue_script( 'firebase' );

	}

	/**
	 * Update the plugin and check for new updates from Screets Server
	 *
	 * @access public
	 * @return void
	 */
	function update() {

		require SCX_PATH . '/core/library/update-checker/plugin-update-checker.php';

		$checker = PucFactory::buildUpdateChecker(
			'http://screets.org/apps/api/v1/wp-updates/?action=get_metadata&slug=' . SCX_SLUG,
			__FILE__,
			SCX_SLUG
		);

		$checker->addQueryArgFilter( array( &$this, '_filter_updates' ) );
	}

	/**
	 * Filter updates with API key
	 *
	 * @access public
	 * @return array Query arguments
	 */
	function _filter_updates( $query_args ) {

		$api_key = $this->opts->getOption( 'api-key' );		

		// Include API key
		if ( !empty( $api_key ) ) {
			$query_args['key'] = $api_key;
			$query_args['domain'] = fn_scx_current_domain();
		}

		return $query_args;

	}

}

// Init the plugin class
$GLOBALS['ChatX'] = new ChatX();