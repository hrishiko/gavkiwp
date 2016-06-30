<?php

/* ---------------------------------------------------------------------- */
/*	Template: Woocommerce
/* ---------------------------------------------------------------------- */

if ( ! function_exists('shopme_wc_get_template') ) {
	function shopme_wc_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		if ( function_exists( 'wc_get_template' ) ) {
			wc_get_template( $template_name, $args, $template_path, $default_path );
		} else {
			woocommerce_get_template( $template_name, $args, $template_path, $default_path );
		}
	}
}

if ( ! function_exists('shopme_woocommerce_product_custom_tab') ) {

	function shopme_woocommerce_product_custom_tab($key) {
		global $post;

		$shopme_title_product_tab = $shopme_content_product_tab = '';
		$custom_tabs_array = get_post_meta($post->ID, 'shopme_custom_tabs', true);
		$custom_tab = $custom_tabs_array[$key];

		extract($custom_tab);

		if ($shopme_title_product_tab != '') {

			preg_match("!\[embed.+?\]|\[video.+?\]!", $shopme_content_product_tab, $match_video);
			preg_match("!\[(?:)?gallery.+?\]!", $shopme_content_product_tab, $match_gallery);
			$zoom_image = shopme_custom_get_option('zoom_image', '');

			if (!empty($match_video)) {

				global $wp_embed;

				$video = $match_video[0];
				$before = "<div class='image-overlay ". esc_attr($zoom_image) ."'>";
				$before .= "<div class='entry-media photoframe'>";
				$before .= do_shortcode($wp_embed->run_shortcode($video));
				$before .= "</div>";
				$before .= "</div>";
				$before = apply_filters('the_content', $before);
				echo $before;

			} elseif (!empty($match_gallery)) {

				$gallery = $match_gallery[0];
				if (strpos($gallery, 'vc_') === false) {
					$gallery = str_replace("gallery", 'shopme_gallery image_size="848*370"', $gallery);
				}
				$before = apply_filters('the_content', $gallery);
				echo do_shortcode($before);

			} else {
				echo do_shortcode($shopme_content_product_tab);
			}

		}

	}
}

if (!function_exists('shopme_woocommerce_show_product_loop_out_of_sale_flash')) {
	function shopme_woocommerce_show_product_loop_out_of_sale_flash() {
		shopme_wc_get_template( 'loop/out-of-stock-flash.php' );
	}
}

if ( ! function_exists('shopme_woocommerce_other_products') ) {
	function shopme_woocommerce_other_products() {
		shopme_wc_get_template( 'single-product/other-products.php' );
	}
}

if (!function_exists('shopme_woocommerce_content_top')) {
	function shopme_woocommerce_content_top() {
		shopme_wc_get_template( 'content-top.php' );
	}
}

if (!function_exists('shopme_woocommerce_single_variation_add_to_cart_button')) {
	function shopme_woocommerce_single_variation_add_to_cart_button() {
		global $product;
		?>
		<div class="variations_button">

			<table class="description-table">
				<tbody>
				<tr>
					<td><?php esc_html_e('Qty:', 'shopme'); ?></td>
					<td></td>
					<td class="product-quantity">
						<?php woocommerce_quantity_input( array( 'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 ) ); ?>
					</td>
					<td></td>
					<td><?php do_action( 'woocommerce_after_add_to_cart_button' ); ?></td>
					<td><button type="submit" class="single_add_to_cart_button button alt"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></button></td>
					<td></td>
					<td><?php echo do_shortcode('[wc_quick_buy  label="Buy Now"]'); ?></td>		
			</tr>
			
				</tbody>
			</table>
			
			<input type="hidden" name="add-to-cart" value="<?php echo absint( $product->id ); ?>" />
			<input type="hidden" name="product_id" value="<?php echo absint( $product->id ); ?>" />
			<input type="hidden" name="variation_id" class="variation_id" value="" />
		</div>
		
		<!--<div class="col-xs-12 nopadding shipping-container" style=" padding-left: 0;" >

			<div class="col-xs-12 col-sm-6 col-md-2 padleft0" style=" padding-left: 0;"><?php //esc_html_e('Qty:', 'shopme'); ?></div>
			<div class="col-xs-12 col-sm-6 col-md-3 padleft0" style=" padding-right: 0;"><?php //woocommerce_quantity_input( array( 'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( $_POST['quantity'] ) : 1 ) ); ?></div>
			<div><?php //do_action( 'woocommerce_after_add_to_cart_button' ); ?></div>
			<div><button type="submit" class="single_add_to_cart_button button alt"><?php //echo esc_html( $product->single_add_to_cart_text() ); ?></button></div>
			<div><?php //echo do_shortcode('[wc_quick_buy  label="Buy Now"]'); ?></div>
		</div>-->
		
		
		<?php
	}
}

if (!function_exists('shopme_overwrite_catalog_ordering')) {

	function shopme_overwrite_catalog_ordering($args) {

		global $shopme_config;

		if (shopme_custom_get_option('products_filter')) return $args;

		$keys = array('product_order', 'product_count', 'product_sort');
		if (empty($shopme_config['woocommerce'])) $shopme_config['woocommerce'] = array();

		foreach ($keys as $key) {
			if (isset($_GET[$key]) ) {
				$_SESSION['shopme_woocommerce'][$key] = esc_attr($_GET[$key]);
			}
			if (isset($_SESSION['shopme_woocommerce'][$key]) ) {
				$shopme_config['woocommerce'][$key] = $_SESSION['shopme_woocommerce'][$key];
			}
		}

		if(isset($_GET['product_order']) && !isset($_GET['product_sort']) && isset($_SESSION['shopme_woocommerce']['product_sort']))
		{
			unset($_SESSION['shopme_woocommerce']['product_sort'], $shopme_config['woocommerce']['product_sort']);
		}

		extract($shopme_config['woocommerce']);

		if (isset($product_order) && !empty($product_order)) {
			switch ( $product_order ) {
				case 'title' : $orderby = 'title'; $order = 'asc'; $meta_key = ''; break;
				case 'price' : $orderby = 'meta_value_num'; $order = 'asc'; $meta_key = '_price'; break;
				case 'date'  : $orderby = 'date'; $order = 'desc'; $meta_key = '';  break;
				case 'popularity' : $orderby = 'meta_value_num'; $order = 'desc'; $meta_key = 'total_sales'; break;
				case 'menu_order':
				default : $orderby = 'menu_order title'; $order = 'asc'; $meta_key = ''; break;
			}
		}

		if(!empty($product_count) && is_numeric($product_count)) {
			$shopme_config['shop_overview_product_count'] = (int) $product_count;
		}

		if (!empty($product_sort)) {
			switch ( $product_sort ) {
				case 'desc' : $order = 'desc'; break;
				case 'asc' : $order = 'asc'; break;
				default : $order = 'asc'; break;
			}
		}

		if (isset($orderby)) $args['orderby'] = $orderby;
		if (isset($order)) 	$args['order'] = $order;

		if (!empty($meta_key)) {
			$args['meta_key'] = $meta_key;
		}

		$shopme_config['woocommerce']['product_sort'] = $args['order'];

		return $args;
	}

	add_action( 'woocommerce_get_catalog_ordering_args', 'shopme_overwrite_catalog_ordering');

}


if (!function_exists('shopme_woocommerce_output_product_content')) {
	function shopme_woocommerce_output_product_content() {
		echo '<section class="section_offset">';
			echo do_shortcode(str_replace(']]>', ']]&gt;', apply_filters('the_content', get_the_content())));
		echo '</section>';
	}
}

if (!function_exists('shopme_woocommerce_output_product_data_tabs')) {
	function shopme_woocommerce_output_product_data_tabs() {
		echo '<div class="clear"></div>';
		woocommerce_output_product_data_tabs();
	}
}

if (!function_exists('shopme_woocommerce_output_related_products')) {
	function shopme_woocommerce_output_related_products() {
		global $shopme_config;

		$shopme_config['shop_single_column'] = ($shopme_config['sidebar_position'] != 'no_sidebar') ? 4 : 5; // columns for related products
		$shopme_config['shop_single_column_items'] = shopme_custom_get_option('shop_single_column_items'); // number of items for related products

		ob_start();

		woocommerce_related_products(
			array(
				'columns' => $shopme_config['shop_single_column'],
				'posts_per_page' => $shopme_config['shop_single_column_items']
			)
		);

		$content = ob_get_clean(); ?>

		<?php if ($content): ?>
			<?php echo $content; ?>
		<?php endif;
	}
}