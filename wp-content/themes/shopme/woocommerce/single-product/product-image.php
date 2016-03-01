<?php
/**
 * Single Product Image
 *
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     2.0.14
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

global $post, $woocommerce, $product;

SHOPME_WOOCOMMERCE_CONFIG::enqueue_script('elevate-zoom');

$image_uniqid = uniqid();
?>

<div class="image_preview_container" data-id="<?php echo esc_attr($image_uniqid); ?>" id="qv_preview-<?php echo esc_attr($image_uniqid) ?>">

	<?php
	if ( has_post_thumbnail() ) {

		$image_title = esc_attr( get_the_title( get_post_thumbnail_id() ) );
		$image_link  = wp_get_attachment_url( get_post_thumbnail_id() );

		$atts_image_single = array(
			'title' => $image_title,
			'data-zoom-image' => $image_link,
			'class' => 'img_zoom',
			'srcset' => ' '
		);

		if (shopme_custom_get_option('zoom_on_product_image')) {
			$atts_image_single['id'] = 'img_zoom';
		}

		$image = get_the_post_thumbnail( $post->ID, 'shop_single', $atts_image_single );

		if (!$image) {
			if ( wc_placeholder_img_src() ) {
				$image = wc_placeholder_img( 'shop_single' );
			}
		}

		if (shopme_custom_get_option('lightbox_on_product_image')) {
			$string = sprintf( '%s <a data-group="images" class="button_grey_2 icon_btn middle_btn open_qv" href="%s"></a>', $image, $image_link );
		} else {
			$string = sprintf( '%s', $image );
		}

		echo apply_filters( 'woocommerce_single_product_image_html', $string, $post->ID );
	} else {
		echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '%s', wc_placeholder_img( 'shop_single' ) ), $post->ID );
	}
	?>

</div><!--/ .image_preview_container-->

<?php

$featuredID[] = get_post_thumbnail_id();
$gallery_ids = $product->get_gallery_attachment_ids();

$attachment_ids = array_merge($featuredID, $gallery_ids);

if ( $attachment_ids && count($attachment_ids) > 1 ) { ?>

	<div class="product_preview" data-output="#qv_preview-<?php echo esc_attr($image_uniqid); ?>">

		<div class="thumbs_carousel" id="thumbnails_<?php echo esc_attr($image_uniqid); ?>">

			<?php

			$loop = 0;
			$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

			foreach ( $attachment_ids as $attachment_id ) {

				$classes = array( 'elzoom' );

				if ( $loop == 0 || $loop % $columns == 0 )
					$classes[] = 'first';

				if ( ( $loop + 1 ) % $columns == 0 )
					$classes[] = 'last';

				$image_src = wp_get_attachment_image_src( $attachment_id, 'shop_single');
				$image_link = wp_get_attachment_url( $attachment_id );

				if ( ! $image_link )
					continue;

				$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_small_thumbnail_size', 'shop_thumbnail' ), false, array( 'srcset' => ' ' ) );
				$image_class = esc_attr( implode( ' ', $classes ) );
				$image_title = esc_attr( get_the_title( $attachment_id ) );

				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<a href="#" data-image="%s" data-zoom-image="%s" class="%s" title="%s">%s</a>', $image_src[0], $image_link, $image_class, $image_title, $image ), $attachment_id, $post->ID, $image_class );

				$loop++;
			}

			?>

		</div><!--/ .thumbs_carousel-->

	</div><!--/ .product_preview-->

<?php
}
