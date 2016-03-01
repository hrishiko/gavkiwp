<?php
/**
 * Checkout coupon form
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.2
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! WC()->cart->coupons_enabled() ) {
	return;
}

$info_message = apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'woocommerce' ) . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'woocommerce' ) . '</a>' );
wc_print_notice( $info_message, 'notice' );
?>

<div class="section_offset">

	<form class="checkout_coupon" method="post" style=" display: none; ">

		<div class="theme_box">

			<p class="form_caption"><?php esc_html_e('Enter your coupon code if you have one.', 'shopme') ?></p>

			<ul>
				<li class="row">
					<div class="col-xs-12">
						<p class="form-row form-row-first">
							<input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_html_e( 'Coupon code', 'woocommerce' ); ?>" id="coupon_code" value="" />
						</p>
					</div>
				</li>
			</ul>

		</div><!--/ .theme_box-->

		<footer class="bottom_box">
			<input type="submit" class="button button_grey middle_btn" name="apply_coupon" value="<?php esc_html_e( 'Apply Coupon', 'woocommerce' ); ?>" />
		</footer><!--/ .bottom_box-->

	</form>

</div><!--/ .section_offset-->

