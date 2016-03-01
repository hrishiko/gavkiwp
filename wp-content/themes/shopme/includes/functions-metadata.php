<?php

require_once( SHOPME_INCLUDES_PATH . 'metadata/meta_values.php' );
require_once( SHOPME_INCLUDES_PATH . 'metadata/functions-types.php' );
require_once( SHOPME_INCLUDES_PATH . 'metadata/product.php' );

if (!function_exists('shopme_get_meta_value')) {

	function shopme_get_meta_value($meta_key) {

		$value = '';

		if (shopme_is_product_category()) {
			$term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) );

			if ($term) {
				$value = get_metadata($term->taxonomy, $term->term_id, $meta_key, true);
			}
		}

		return $value;
	}

}
