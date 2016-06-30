<?php

/**
 * Class WMC_Admin_Settings
 */
class WMC_Admin_Settings {
	function __construct() {
		add_filter( 'woocommerce_general_settings', array( $this, 'woocommerce_general_settings' ) );
		add_action( 'admin_notices', array( $this, 'global_note' ) );
		add_action( 'admin_init', array( $this, 'admin_init' ) );
		add_action( 'admin_bar_menu', array( &$this, 'admin_bar_menu' ) );
	}

	function admin_bar_menu() {
		if ( wmc_check_vpro() ) {
			return;
		}
		global $wp_admin_bar;
		/* Add the main siteadmin menu item */
		$wp_admin_bar->add_menu(
			array(
				'id'     => 'upgrade-woo-multi-currency',
				'parent' => 'top-secondary',
				'title'  => '<a href="http://bit.ly/woo-multi-currency-pro">' . esc_html__( 'Upgrade Woo Multi Currency Pro' ) . '</a>',
				'meta'   => array( 'class' => 'wmc-notice' ),
			)
		);
	}

	/**
	 * Garenal tab setting
	 *
	 * @param $datas
	 *
	 * @return mixed
	 */
	function woocommerce_general_settings( $datas ) {
		foreach ( $datas as $k => $data ) {
			if ( isset( $data['id'] ) ) {
				if ( $data['id'] == 'woocommerce_currency' || $data['id'] == 'woocommerce_price_num_decimals' || $data['id'] == 'woocommerce_currency_pos' ) {
					unset( $datas[$k] );
				}
				if ( $data['id'] == 'pricing_options' ) {
					$datas[$k]['desc'] = esc_html__( 'The following options affect how prices are displayed on the frontend. Woo Multi Currency is working. Please go to ', 'woo-multi-currency' ) . '<a href="admin.php?page=wc-settings&tab=wmc">' . esc_html__( 'Woo Multi Currency setting page', 'woo-multi-currency' ) . '</a>' . esc_html__( ' to set default currency.', 'woo-multi-currency' );
				}
			}
		}

		return $datas;
	}

	/**
	 * Update hidden note
	 */
	public function admin_init() {
		$current_time = current_time( 'timestamp' );
		$hide         = filter_input( INPUT_GET, 'wmc_hide', FILTER_SANITIZE_NUMBER_INT );
		if ( $hide ) {
			update_option( 'wmc_note', 0 );
			update_option( 'wmc_note_time', $current_time );
		}

		$time_off = get_option( 'wmc_note_time' );
		if ( ! $time_off ) {
			update_option( 'wmc_note', 1 );
		} else {
			$time_next = $time_off + 30 * 24 * 60 * 60;
			if ( $time_next < $current_time ) {
				update_option( 'wmc_note', 1 );
			}
		}

	}

	/**
	 * Show Note
	 */
	public function global_note() {
		if ( ! wmc_check_vpro() ) {
			$hide = get_option( 'wmc_note', 1 );
			if ( $hide ) {
				?>
				<div id="message" class="updated">
					<p><?php _e( 'You can get <strong> Woo Multi Currency Premium version</strong> at <a target="_blank" href="http://villatheme.com/extensions/woo-multi-currency/">here</a> then please active.', 'woo-multi-currency' ); ?></p>
					<p class="submit">
						<a class="button-primary"
						   href="http://villatheme.com/extensions/woo-multi-currency/"><?php echo esc_html__( 'Use Now', 'woo-multi-currency' ) ?></a>
						<a class="button-secondary skip"
						   href="<?php echo add_query_arg( array( 'wmc_hide' => 1 ), admin_url() ) ?>"><?php echo esc_html__( 'Hidden', 'woo-multi-currency' ) ?></a>
					</p>
				</div>
			<?php }
		}
	}
}

new WMC_Admin_Settings();