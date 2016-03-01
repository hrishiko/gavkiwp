<?php
/**
 * YITH WooCommerce Ajax Search template
 *
 * @author Yithemes
 * @package YITH WooCommerce Ajax Search
 * @version 1.1.1
 */

if ( !defined( 'YITH_WCAS' ) ) { exit; } // Exit if accessed directly
wp_enqueue_script('yith_wcas_frontend' );

$yith_wcas_search_input_label = get_option('yith_wcas_search_input_label');
if (empty($yith_wcas_search_input_label)) {
	$yith_wcas_search_input_label = esc_html__('Search for products', 'shopme');
}
?>

<div class="yith-ajaxsearchform-container">

    <form class="clearfix search_form" method="get" id="yith-ajaxsearchform" action="<?php echo esc_url( home_url( '/'  ) ) ?>">

		<input type="search"
			   value="<?php echo get_search_query() ?>"
			   name="s" id="yith-s"
			   class="yith-s alignleft"
			   placeholder="<?php echo esc_attr($yith_wcas_search_input_label); ?>"
			   data-loader-icon="<?php echo str_replace( '"', '', apply_filters('yith_wcas_ajax_search_icon', '') ) ?>"
			   data-min-chars="<?php echo get_option('yith_wcas_min_chars'); ?>" />

		<div class="search_category alignleft">

			<?php
				$args = array(
					'show_option_all' => esc_html__( 'All Categories', 'shopme' ),
					'hierarchical' => 1,
					'class' => 'cat',
					'echo' => 0,
					'value_field' => 'slug',
					'selected' => 1
				);

				$args['taxonomy'] = 'product_cat';
				$args['name'] = 'product_cat';

				$html =	wp_dropdown_categories($args);
				echo str_replace( '&nbsp;', '', $html );
			?>

		</div><!--/ .search_category-->

		<button type="submit" class="button_blue def_icon_btn alignleft"></button>
		<input type="hidden" name="post_type" value="product" />

		<?php if ( defined( 'ICL_LANGUAGE_CODE' ) ): ?>
			<input type="hidden" name="lang" value="<?php echo( ICL_LANGUAGE_CODE ); ?>" />
		<?php endif ?>

    </form>

</div>