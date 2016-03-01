<?php

if (!class_exists('Shopme_Woo_Config')) {

	class Shopme_Woo_Config extends Shopme_Custom_Content_Types_and_Taxonomies {

		function __construct() {

			add_action('shopme_pre_import_hook', array($this, 'woocommerce_import_start'));

		}

		function woocommerce_import_start() {
			global $wpdb;

			$file = self::$view_path['XML_PATH'] . 'default.xml';

			$parser      = new WXR_Parser();
			$import_data = $parser->parse( $file );

			if ( isset( $import_data['posts'] ) ) {
				$posts = $import_data['posts'];

				if ( $posts && sizeof( $posts ) > 0 ) foreach ( $posts as $post ) {

					if ( $post['post_type'] == 'product' ) {

						if ( $post['terms'] && sizeof( $post['terms'] ) > 0 ) {

							foreach ( $post['terms'] as $term ) {

								$domain = $term['domain'];

								if ( strstr( $domain, 'pa_' ) ) {

									// Make sure it exists!
									if ( ! taxonomy_exists( $domain ) ) {

										$nicename = strtolower( sanitize_title( str_replace( 'pa_', '', $domain ) ) );
										$nicelabel = ucfirst( sanitize_title( str_replace( 'pa_', '', $domain ) ) );

										$exists_in_db = $wpdb->get_var( $wpdb->prepare( "SELECT attribute_id FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies WHERE attribute_name = %s;", $nicename ) );

										// Create the taxonomy
										if ( ! $exists_in_db )
											$wpdb->insert( $wpdb->prefix . "woocommerce_attribute_taxonomies", array(
												'attribute_name' => $nicename,
												'attribute_label' => $nicelabel,
												'attribute_type' => 'select',
												'attribute_orderby' => 'menu_order' ), array( '%s', '%s', '%s', '%s'  ) );

										// Register the taxonomy now so that the import works!
										register_taxonomy( $domain,
											apply_filters( 'woocommerce_taxonomy_objects_' . $domain, array('product') ),
											apply_filters( 'woocommerce_taxonomy_args_' . $domain, array(
												'hierarchical' => true,
												'show_ui' => false,
												'query_var' => true,
												'rewrite' => false,
											) )
										);


									}
								}
							}

							$transient_name = 'wc_attribute_taxonomies';
							$attribute_taxonomies = $wpdb->get_results( "SELECT * FROM " . $wpdb->prefix . "woocommerce_attribute_taxonomies" );
							set_transient( $transient_name, $attribute_taxonomies );

						}
					}
				}
			}

		}

	}

}

