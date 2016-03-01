<?php

$shopme_include_plugins = array(
	'post-ratings'
);

if (shopme_custom_get_option('products_filter')) {
	$shopme_include_plugins[] = 'woocommerce-products-filter';
}

foreach ($shopme_include_plugins as $inc) {
	require_once ( SHOPME_INC_PLUGINS_PATH . trailingslashit($inc) . 'init' . '.php' );
}