<?php
/**
 * Single Product Meta
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     1.6.4
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $product;

$cat_count = sizeof( get_the_terms( $post->ID, 'product_cat' ) );
$tag_count = sizeof( get_the_terms( $post->ID, 'product_tag' ) );
?>
<div class="product_meta">
	
	<div class="col-xs-12 nopadding shipping-container" style=" padding-left: 0;" >
							<div class="col-xs-12 col-sm-6 col-md-5 padleft0" style=" padding-left: 0; font-size:17px;">
								<b>Shipping Cost :</b>
								<ul class="ship-cost" style="list-style-type: none;">
										<li>
											<i class="fa fa-rupee"></i> 100 shipping in India
										</li>
									
								</ul>
							</div>
							<div style=" padding-left: 0; font-size:17px;">
								<b>Estimated Delivery Time :</b>
								<div class="pt10"> 7-21 business days</div>
							</div>
	</div>

	<?php //do_action( 'woocommerce_product_meta_start' ); ?>

	<?php if ( wc_product_sku_enabled() && ( $product->get_sku() || $product->is_type( 'variable' ) ) ) : ?>

		<span class="sku_wrapper"><?php esc_html_e( 'SKU:', 'woocommerce' ); ?> <span class="sku" itemprop="sku"><?php echo ( $sku = $product->get_sku() ) ? $sku : __( 'N/A', 'woocommerce' ); ?></span>.</span>

	<?php endif; ?>

	<?php echo $product->get_categories( ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', $cat_count, 'woocommerce' ) . ' ', '.</span>' ); ?>

	<?php echo $product->get_tags( ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', $tag_count, 'woocommerce' ) . ' ', '.</span>' ); ?>

	<?php do_action( 'woocommerce_product_meta_end' ); ?>

</div>