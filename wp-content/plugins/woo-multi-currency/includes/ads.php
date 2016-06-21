<?php

/**
 * Add Ads in product
 * Class VI_WOO_CB_Admin_Ads
 */
if ( ! class_exists( 'Villatheme_Ads' ) ) {
	class Villatheme_Ads {
		public function __construct() {
			add_action( 'villatheme_ads', array( $this, 'add_ads' ) );

			//Init Script
			add_action( 'admin_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
		}

		/**
		 * Add Script
		 */
		function wp_enqueue_scripts() {
			wp_enqueue_style( 'villatheme-ads', plugins_url() . '/woo-multi-currency/css/villatheme-items.css' );
		}

		/**
		 * Add ADS
		 */
		public function add_ads() {

			$ads = $this->get_xml();

			if ( ! $ads || ! is_array( $ads ) ) {
				return false;
			}
			$ads = array_filter( $ads );
			if ( ! count( $ads ) ) {
				return false;
			}


			?>
			<div class="villatheme-ads-wrapper">
				<h3><?php echo esc_html( 'MAY BE YOU LIKE' ) ?></h3>
				<ul class="villatheme-list-ads">
					<?php

					if ( count( $ads ) > 5 ) {
						$ads = $this->array_random_assoc( $ads, 5 );
					}
					foreach ( $ads as $ad ) { ?>
						<li>
							<a href="<?php echo esc_url( $ad->link ) ?>">
								<?php if ( $ad->thumb ) { ?>
									<img src="<?php echo esc_url( $ad->thumb ) ?>" />
								<?php } ?>
								<?php if ( $ad->image ) { ?>
									<span>
								<img src="<?php echo esc_url( $ad->image ) ?>" />
							</span>
								<?php } ?>
								<?php echo esc_html( $ad->title ) ?>
							</a>
						</li>
					<?php } ?>
				</ul>
			</div>
		<?php }

		/**
		 * Get data from server
		 * @return array
		 */
		protected function get_xml() {
			$ads = array();
			$doc = new DOMDocument();
			@$doc->load( 'http://villatheme.com/ads.xml' );
			@$sites = $doc->getElementsByTagName( "item" );
			if ( $sites->length ) {
				$theme_select = null;
				foreach ( $sites as $site ) {
					$item        = new stdClass();
					$titles      = $site->getElementsByTagName( "title" );
					$item->title = $titles->item( 0 )->nodeValue;

					$link       = $site->getElementsByTagName( "link" );
					$item->link = $link->item( 0 )->nodeValue;

					$thumb       = $site->getElementsByTagName( "thumb" );
					$item->thumb = $thumb->item( 0 )->nodeValue;

					$image       = $site->getElementsByTagName( "image" );
					$item->image = $image->item( 0 )->nodeValue;

					$desc       = $site->getElementsByTagName( "desc" );
					$item->desc = @$desc->item( 0 )->nodeValue;
					$ads[]      = $item;
				}
			} else {
				return false;
			}

			return $ads;
		}

		/**
		 * Random multi array
		 *
		 * @param     $arr
		 * @param int $num
		 *
		 * @return array
		 */
		protected function array_random_assoc( $arr, $num = 1 ) {
			$keys = array_keys( $arr );
			shuffle( $keys );

			$r = array();
			for ( $i = 0; $i < $num; $i ++ ) {
				$r[$keys[$i]] = $arr[$keys[$i]];
			}

			return $r;
		}
	}

	new Villatheme_Ads();
}