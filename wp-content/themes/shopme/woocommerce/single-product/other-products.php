<?php
/**
 * Other Products From This Seller
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $product, $woocommerce_loop;

if ( empty( $product ) || ! $product->exists() ) {
	return;
}

$id = get_query_var('author');
$name = get_the_author_meta('display_name', $id);

if ( sizeof( $name ) == 0 ) return;

$args = apply_filters( 'shopme_woocommerce_othe_products_args', array(
	'post_type'            => 'product',
	'ignore_sticky_posts'  => 1,
	'no_found_rows'        => 1,
	'posts_per_page'       => 10,
	'author'               => $name,
	'post__not_in'         => array( $product->id )
) );

global $shopme_config;
$shopme_config['shop_single_other_products_column'] = ($shopme_config['sidebar_position'] != 'no_sidebar') ? 3 : 4; // columns for other products

$products = new WP_Query( $args );

if ( $products->have_posts() ) : ?>

	<section class="section_offset">

		<h3 class="offset_title"><?php esc_html_e( 'Other Products From This Seller', 'shopme' ); ?></h3>

		<div data-sidebar="<?php echo esc_attr($shopme_config['sidebar_position']); ?>" data-columns="<?php echo $shopme_config['shop_single_other_products_column']; ?>" class="owl_carousel view-grid type_1 other_products <?php echo 'shop-columns-' . $shopme_config['shop_single_other_products_column'] ?>">

			<?php woocommerce_product_loop_start(); ?>

			<?php while ( $products->have_posts() ) : $products->the_post(); ?>

				<?php wc_get_template_part( 'content', 'product' ); ?>

			<?php endwhile; // end of the loop. ?>

			<?php woocommerce_product_loop_end(); ?>

		</div><!--/ .other_products-->

	</section><!--/ .section_offset-->

<?php endif;

wp_reset_postdata();
