<?php
/**
 * This file represents an example of the code that themes would use to register
 */

/**
 * Include the TGM_Plugin_Activation class.
 */

require_once dirname( __FILE__ ) . '/class-tgm-plugin-activation.php';

add_action( 'tgmpa_register', 'shopme_register_required_plugins' );

if (!function_exists('shopme_added_admin_action')) {

	function shopme_added_admin_action() {
		add_action( 'admin_enqueue_scripts', 'shopme_added_plugin_style' );
	}

	function shopme_added_plugin_style() {
		wp_enqueue_style( SHOPME_PREFIX . 'admin_plugins', SHOPME_BASE_URI . 'css/admin-plugin.css', array() );
	}

	add_action( 'load-plugins.php', 'shopme_added_admin_action', 1 );

}
/**
 * Register the required plugins for this theme.
 *
 * In this example, we register two plugins - one included with the TGMPA library
 * and one from the .org repo.
 *
 * The variable passed to tgmpa_register_plugins() should be an array of plugin
 * arrays.
 *
 * This function is hooked into tgmpa_init, which is fired within the
 * TGM_Plugin_Activation class constructor.
 */
function shopme_register_required_plugins() {

    /**
     * Array of plugin arrays. Required keys are name and slug.
     * If the source is NOT from the .org repo, then source is also required.
     */
	$shopme_plugins = array(
/*
        // This is an example of how to include a plugin pre-packaged with a theme.
        array(
            'name'               => 'TGM Example Plugin', // The plugin name.
            'slug'               => 'tgm-example-plugin', // The plugin slug (typically the folder name).
            'source'             => get_stylesheet_directory() . '/lib/plugins/tgm-example-plugin.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'version'            => '', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
            'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
            'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
            'external_url'       => '', // If set, overrides default API URL and points to an external URL.
        ),
*/
        // This is an example of how to include a plugin from a private repo in your theme.
		/*
        array(
            'name'               => 'TGM New Media Plugin', // The plugin name.
            'slug'               => 'tgm-new-media-plugin', // The plugin slug (typically the folder name).
            'source'             => 'https://s3.amazonaws.com/tgm/tgm-new-media-plugin.zip', // The plugin source.
            'required'           => true, // If false, the plugin is only 'recommended' instead of required.
            'external_url'       => 'https://github.com/thomasgriffin/New-Media-Image-Uploader', // If set, overrides default API URL and points to an external URL.
        ),
		*/

        array(
            'name'      => 'Social Login',
            'slug'      => 'oa-social-login',
			'required'           => false,
			'version'            => '',
			'force_activation'   => false,
			'force_deactivation' => false
        ),

        array(
            'name'     => 'Woocommerce',
            'slug'     => 'woocommerce',
            'required' => false
        ),

		array(
			'name'     => 'Yith Woocommerce Ajax Search',
			'slug'     => 'yith-woocommerce-ajax-search',
			'required' => false
		),

        array(
            'name'     => 'YITH WooCommerce Compare',
            'slug'     => 'yith-woocommerce-compare',
            'required' => false
        ),

		array(
			'name'     => 'Yith WooCommerce Wishlist',
			'slug'     => 'yith-woocommerce-wishlist',
			'required' => false
		),

		array(
			'name'     => 'Contact Form 7',
			'slug'     => 'contact-form-7',
			'required' => false
		),

		array(
			'name'      => 'WC Vendors',
			'slug'      => 'wc-vendors',
			'required'  => false,
			'force_activation'   => false,
			'force_deactivation' => false
		),

		// This is an example of how to include a plugin from the WordPress Plugin Repository.

		array(
            'name'               => 'Envato Wordpress Toolkit Master',
            'slug'               => 'envato-wordpress-toolkit-master',
            'source'             => 'http://velikorodnov.com/wordpress/sample-data/shopme/plugins/envato-wordpress-toolkit-master.zip',
            'required'           => false,
            'version'            => '1.7.3',
            'force_activation'   => false,
            'force_deactivation' => false
      	),

		array(
			'name'               => 'Rich Snippets Wordpress Plugin',
			'slug'               => 'rich-snippets-wordpress-plugin',
			'source'             => 'http://velikorodnov.com/wordpress/sample-data/shopme/plugins/rich-snippets-wordpress-plugin.zip',
			'required'           => false,
			'version'            => '1.6.1',
			'force_activation'   => false,
			'force_deactivation' => false
		),

        array(
            'name'               => 'WooCommerce Prices By User Role',
            'slug'               => 'woocommerce-prices-by-user-role',
            'source'             => 'http://velikorodnov.com/wordpress/sample-data/shopme/plugins/woocommerce-prices-by-user-role.zip',
            'required'           => false,
            'version'            => '2.20.3',
            'force_activation'   => false,
            'force_deactivation' => false
        ),

		array(
			'name'               => 'Shopme Content Types',
			'slug'               => 'shopme-content-types',
			'source'             => 'http://velikorodnov.com/wordpress/sample-data/shopme/plugins/shopme-content-types.zip',
			'required'           => false,
			'version'            => '1.0.1',
			'force_activation'   => false,
			'force_deactivation' => false
		),

        array(
            'name'               => 'Indeed Smart PopUp',
            'slug'               => 'indeed-smart-popup',
            'source'             => 'http://velikorodnov.com/wordpress/sample-data/shopme/plugins/indeed-smart-popup.zip',
            'required'           => false,
            'version'            => '4.6',
            'force_activation'   => false,
            'force_deactivation' => false
        ),

        array(
            'name'               => 'Woo Sale Revolution:Flash Sale + Dynamic Discounts',
            'slug'               => 'woo-sale-revolution-flashsale',
            'source'             => 'http://velikorodnov.com/wordpress/sample-data/shopme/plugins/woo-sale-revolution-flashsale.zip',
            'required'           => false,
            'version'            => '2.7',
            'force_activation'   => false,
            'force_deactivation' => false
        ),

		array(
			'name'               => 'Mega Main Menu',
			'slug'               => 'mega-main-menu',
			'source'             => 'http://velikorodnov.com/wordpress/sample-data/shopme/plugins/mega_main_menu.zip',
			'required'           => false,
			'version'            => '2.1.1',
			'force_activation'   => false,
			'force_deactivation' => false
		),

		array(
			'name'               => 'LayerSlider WP',
			'slug'               => 'LayerSlider',
			'source'             => 'http://velikorodnov.com/wordpress/sample-data/shopme/plugins/LayerSlider.zip',
			'required'           => false,
			'version'            => '5.6.2',
			'force_activation'   => false,
			'force_deactivation' => false
		),

		array(
			'name'               => 'Slider Revolution',
			'slug'               => 'revslider',
			'source'             => 'http://velikorodnov.com/wordpress/sample-data/shopme/plugins/revslider.zip',
			'required'           => false,
			'version'            => '5.1.6',
			'force_activation'   => false,
			'force_deactivation' => false
		),

		array(
            'name'               => 'WPBakery Visual Composer',
            'slug'               => 'js_composer',
            'source'             => 'http://velikorodnov.com/wordpress/sample-data/shopme/plugins/js_composer.zip',
            'required'           => false,
            'version'            => '4.10',
            'force_activation'   => false,
            'force_deactivation' => false
		),

		array(
			'name'               => 'Screets Chat X',
			'slug'               => 'screets-cx',
			'source'             => 'http://velikorodnov.com/wordpress/sample-data/shopme/plugins/screets-cx.zip',
			'required'           => false,
			'version'            => '2.1.1',
			'force_activation'   => false,
			'force_deactivation' => false
		),

		array(
			'name'               => 'Ultimate WooCommerce Brands Plugin',
			'slug'               => 'mgwoocommercebrands',
			'source'             => 'http://velikorodnov.com/wordpress/sample-data/shopme/plugins/mgwoocommercebrands.zip',
			'required'           => false,
			'version'            => '1.5',
			'force_activation'   => false,
			'force_deactivation' => false
		)

// 		array(
//			'name'               => 'WPML Multilingual CMS', // The plugin name.
//			'slug'               => 'sitepress-multilingual-cms', // The plugin slug (typically the folder name).
//			'source'             => 'http://velikorodnov.com/wordpress/sample-data/shopme/plugins/sitepress-multilingual-cms.zip', // The plugin source.
//			'required'           => false, // If false, the plugin is only 'recommended' instead of required.
//			'version'            => '3.3.6', // E.g. 1.0.0. If set, the active plugin must be this version or higher.
//			'force_activation'   => false, // If true, plugin is activated upon theme activation and cannot be deactivated until theme switch.
//			'force_deactivation' => false, // If true, plugin is deactivated upon theme switch, useful for theme-specific plugins.
//			'external_url'       => '', // If set, overrides default API URL and points to an external URL.
//		)

    );

    /**
     * Array of configuration settings. Amend each line as needed.
     * If you want the default strings to be available under your own theme domain,
     * leave the strings uncommented.
     * Some of the strings are added into a sprintf, so see the comments at the
     * end of each line for what each argument will be.
     */
    $shopme_config = array(
        'default_path' => '',  // Default absolute path to pre-packaged plugins.
        'menu'         => 'tgmpa-install-plugins', // Menu slug.
        'has_notices'  => true,                    // Show admin notices or not.
        'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
        'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
        'is_automatic' => true,                   // Automatically activate plugins after installation or not.
        'message'      => '',                      // Message to output right before the plugins table.
        'strings'      => array(
            'page_title'                      => __( 'Install Required Plugins', 'tgmpa' ),
            'menu_title'                      => __( 'Install Plugins', 'tgmpa' ),
            'installing'                      => __( 'Installing Plugin: %s', 'tgmpa' ), // %s = plugin name.
            'oops'                            => __( 'Something went wrong with the plugin API.', 'tgmpa' ),
            'notice_can_install_required'     => _n_noop( 'This theme requires the following plugin: %1$s.', 'This theme requires the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_install_recommended'  => _n_noop( 'This theme recommends the following plugin: %1$s.', 'This theme recommends the following plugins: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_install'           => _n_noop( 'Sorry, but you do not have the correct permissions to install the %s plugin. Contact the administrator of this site for help on getting the plugin installed.', 'Sorry, but you do not have the correct permissions to install the %s plugins. Contact the administrator of this site for help on getting the plugins installed.' ), // %1$s = plugin name(s).
            'notice_can_activate_required'    => _n_noop( 'The following required plugin is currently inactive: %1$s.', 'The following required plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_can_activate_recommended' => _n_noop( 'The following recommended plugin is currently inactive: %1$s.', 'The following recommended plugins are currently inactive: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_activate'          => _n_noop( 'Sorry, but you do not have the correct permissions to activate the %s plugin. Contact the administrator of this site for help on getting the plugin activated.', 'Sorry, but you do not have the correct permissions to activate the %s plugins. Contact the administrator of this site for help on getting the plugins activated.' ), // %1$s = plugin name(s).
            'notice_ask_to_update'            => _n_noop( 'The following plugin needs to be updated to its latest version to ensure maximum compatibility with this theme: %1$s.', 'The following plugins need to be updated to their latest version to ensure maximum compatibility with this theme: %1$s.' ), // %1$s = plugin name(s).
            'notice_cannot_update'            => _n_noop( 'Sorry, but you do not have the correct permissions to update the %s plugin. Contact the administrator of this site for help on getting the plugin updated.', 'Sorry, but you do not have the correct permissions to update the %s plugins. Contact the administrator of this site for help on getting the plugins updated.' ), // %1$s = plugin name(s).
            'install_link'                    => _n_noop( 'Begin installing plugin', 'Begin installing plugins' ),
            'activate_link'                   => _n_noop( 'Begin activating plugin', 'Begin activating plugins' ),
            'return'                          => __( 'Return to Required Plugins Installer', 'tgmpa' ),
            'plugin_activated'                => __( 'Plugin activated successfully.', 'tgmpa' ),
            'complete'                        => __( 'All plugins installed and activated successfully. %s', 'tgmpa' ), // %s = dashboard link.
            'nag_type'                        => 'updated' // Determines admin notice type - can only be 'updated', 'update-nag' or 'error'.
        )
    );

    tgmpa( $shopme_plugins, $shopme_config );

}