<?php

if (!function_exists('shopme_filter_constructor')) {

	function shopme_filter_constructor() {

		global $woocommerce;
		if ( ! isset( $woocommerce ) ) { return; }

		define('SHOPME_WOOF_PATH', trailingslashit(dirname(__FILE__)));
		define('SHOPME_WOOF_LINK', trailingslashit(SHOPME_BASE_URI . 'inc/plugins/woocommerce-products-filter'));

		include SHOPME_WOOF_PATH . 'helper.php';
		include SHOPME_WOOF_PATH . 'classes/storage.php';
		include SHOPME_WOOF_PATH . 'classes/counter.php';

		include SHOPME_WOOF_PATH . 'class.woof.php';
		include SHOPME_WOOF_PATH . 'widgets/class.woocommerce-filter.php';

		// Let's start the game!
		global $SHOPME_WOOF;
		$SHOPME_WOOF = new SHOPME_WOOF();

		add_action( 'init', array( $SHOPME_WOOF, 'init' ), 1 );

	}

	shopme_filter_constructor();

}


