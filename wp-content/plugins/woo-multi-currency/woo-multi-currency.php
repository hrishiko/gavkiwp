<?php

/*
Plugin Name: Woo Multi Currency
Plugin URI: http://villatheme.com
Description: Creat a price switcher or approximately price with unlimit currency. Working base on WooCommerce plugin.
Version: 1.2.4
Author: Cuong Nguyen and Andy Ha (villatheme.com)
Author URI: http://villatheme.com
Copyright 2016 VillaTheme.com. All rights reserved.
*/
define( 'WOO_MULTI_CURRENCY_VERSION', '1.2.4' );
require_once plugin_dir_path( __FILE__ ) . 'admin/settings.php';
require_once plugin_dir_path( __FILE__ ) . 'front-end/mini-cart.php';
require_once plugin_dir_path( __FILE__ ) . 'front-end/filter-price.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/ads.php';
require_once plugin_dir_path( __FILE__ ) . 'includes/functions.php';


class Woo_Multi_Currency {
	public $main_currency = "GBP";
	public $current_currency = "GBP";
	public $current_position = "";
	public $selected_currencies = array();
	public $currencies_list = array();
	public $currencies_symbol = array();


	public function __construct() {
		if ( get_option( 'woocommerce_currency' ) != '' ) {
			$this->main_currency = get_option( 'woocommerce_currency' );
		}
		$this->currencies_list   = $this->wmc_get_currencies_list();
		$this->currencies_symbol = $this->wmc_get_currencies_symbol();
		add_action( 'init', array( $this, 'wmc_load_js_css' ) );
		if ( get_option( 'wmc_auto_update_rates' ) == 'yes' ) {
			add_action( 'init', array( $this, 'wmc_auto_update_rates' ) );
		}
		if ( is_admin() && isset( $_POST['wmc_currency'] ) ) {
			$this->wmc_save_selected_currencies();
		}
		if ( get_option( 'wmc_allow_multi' ) == "" ) {
			update_option( 'wmc_allow_multi', 'yes' );
		}
		$this->selected_currencies = get_option( 'wmc_selected_currencies' );
		if ( empty( $this->selected_currencies ) || ( ! empty( $this->selected_currencies ) && ! array_key_exists( $this->main_currency, $this->selected_currencies ) ) ) {
			$this->selected_currencies[$this->main_currency]['rate'] = 1;
			if ( get_option( 'woocommerce_currency_pos' ) != '' ) {
				$this->selected_currencies[$this->main_currency]['pos'] = get_option( 'woocommerce_currency_pos' );
			} else {
				$this->selected_currencies[$this->main_currency]['pos'] = 'left';
			}
			$this->selected_currencies[$this->main_currency]['symbol']     = $this->currencies_symbol[$this->main_currency];
			$this->selected_currencies[$this->main_currency]['is_main']    = 1;
			$this->selected_currencies[$this->main_currency]['num_of_dec'] = 2;
			update_option( 'wmc_selected_currencies', $this->selected_currencies );
		}
		if ( ! empty( $this->selected_currencies ) && array_key_exists( $this->main_currency, $this->selected_currencies ) ) {
			if ( $this->selected_currencies[$this->main_currency]['is_main'] != 1 ) {
				foreach ( $this->selected_currencies as $code => $details ) {
					$this->selected_currencies[$code]['is_main'] = 0;
				}
				$this->selected_currencies[$this->main_currency]['is_main'] = 1;
				$this->selected_currencies[$this->main_currency]['rate']    = 1;
				update_option( 'wmc_selected_currencies', $this->selected_currencies );
			}
		}
		add_action( 'widgets_init', array( $this, 'widgets_init' ) );
		add_shortcode( 'woo_multi_currency', array( $this, 'wmc_widget_shortcode' ) );
		add_shortcode( 'woo_multi_currency_converter', array( $this, 'wmc_widget_converter_shortcode' ) );
		add_action( 'wp_ajax_wmc_get_rate', array( $this, 'wmc_get_rate' ) );
		add_filter( 'woocommerce_get_order_currency', array( $this, 'wmc_get_order_currency' ), 10, 2 );

		if ( is_admin() ) {
			add_filter( 'woocommerce_settings_tabs_array', array( $this, 'wmc_add_tab' ), 30 );
			add_action( 'woocommerce_settings_tabs_wmc', array( $this, 'wmc_add_setting_fields' ) );
			add_action( 'woocommerce_update_options_wmc', array( $this, 'wmc_update_settings_fields' ) );
			add_filter(
				'plugin_action_links_woo-multi-currency/woo-multi-currency.php', array(
					$this,
					'wmc_add_settings_link'
				)
			);

		} else {
			@session_start();
			if ( isset( $_GET['wmc_current_currency'] ) && array_key_exists( $_GET['wmc_current_currency'], $this->selected_currencies ) ) {
				$this->current_currency = $_GET['wmc_current_currency'];
				setcookie( 'wmc_current_currency', $this->current_currency, time() + 60 * 60 * 24, '/' );
			} elseif ( isset( $_COOKIE['wmc_current_currency'] ) && array_key_exists( $_COOKIE['wmc_current_currency'], $this->selected_currencies ) ) {
				$this->current_currency = $_COOKIE['wmc_current_currency'];
			} else {
				$this->current_currency = $this->main_currency;
			}

			if ( get_option( 'wmc_allow_multi' ) == 'no' ) {
				if ( isset( $_GET['wc-ajax'] ) AND $_GET['wc-ajax'] == 'update_order_review' ) {
					$this->current_currency = $this->main_currency;
					setcookie( 'wmc_current_currency', $this->current_currency, - ( time() + 60 * 60 * 24 ), '/' );
				}
			}

			if ( get_option( 'wmc_allow_multi' ) == 'yes' ) {
				add_filter( 'woocommerce_get_regular_price', array( $this, 'wmc_woocommerce_get_price' ), 10, 2 );
				add_filter( 'woocommerce_get_sale_price', array( $this, 'wmc_woocommerce_get_price' ), 10, 2 );
				add_filter( 'woocommerce_get_price', array( $this, 'wmc_woocommerce_get_price' ), 10, 2 );
				add_filter( 'woocommerce_currency', array( $this, 'wmc_get_current_currency' ) );
				add_filter( 'woocommerce_package_rates', array( $this, 'wmc_package_rates' ) );

				add_filter(
					'woocommerce_get_variation_regular_price', array(
					$this,
					'wmc_woocommerce_get_price'
				), 9999
				);
				add_filter( 'woocommerce_get_variation_sale_price', array( $this, 'wmc_woocommerce_get_price' ), 9999 );
				add_filter( 'woocommerce_variation_prices', array( $this, 'get_woocommerce_variation_prices' ) );
				add_filter(
					'woocommerce_variation_prices_price', array(
					$this,
					'get_woocommerce_variation_prices'
				), 9999, 1
				);
				add_filter(
					'woocommerce_variation_prices_regular_price', array(
					$this,
					'get_woocommerce_variation_prices'
				), 9999, 1
				);
				add_filter(
					'woocommerce_variation_prices_sale_price', array(
					$this,
					'get_woocommerce_variation_prices'
				), 9999, 1
				);
				add_filter(
					'woocommerce_get_variation_prices_hash', array(
					$this,
					'wmc_get_woocommerce_get_variation_prices_hash'
				), 9999, 3
				);
			} else {
				add_filter( 'raw_woocommerce_price', array( $this, 'wmc_woocommerce_get_price' ) );
			}
			add_filter( 'woocommerce_get_price_html', array( $this, 'add_approximately_price' ), 10, 2 );

		}
		add_filter( 'woocommerce_price_format', array( $this, 'wmc_get_price_format' ) );
		add_filter( 'woocommerce_currency_symbol', array( $this, 'wmc_get_currency_symbol' ) );
		add_action( 'init', array( $this, 'wmc_load_text_domain' ) );

		add_filter( 'woocommerce_price_filter_widget_min_amount', array( $this, 'wmc_woocommerce_get_max_min_price' ) );
		add_filter( 'woocommerce_price_filter_widget_max_amount', array( $this, 'wmc_woocommerce_get_max_min_price' ) );
		add_filter( 'wc_price_args', array( $this, 'wc_price_args' ), 10, 1 );

		/*Add Currency Option Title*/
		add_filter( 'woo_multi_currency_tab_options', array( $this, 'currency_option_title' ) );
	}

	public function wc_price_args( $price_arg ) {
		$price_arg['decimals'] = $this->get_price_decimals();

		return $price_arg;
	}

	/**
	 * Coverted min price
	 *
	 * @param $raw_price
	 *
	 * @return mixed
	 */
	public function wmc_woocommerce_get_max_min_price( $raw_price ) {

		$demicial = $this->get_price_decimals();

		$raw_price = $raw_price * $this->selected_currencies[$this->current_currency]['rate'];

		return $raw_price;
	}

	/**
	 * Change currency when view orders for each order  to the ordered currency
	 *
	 * @param $order_currency
	 * @param $WC_Order
	 *
	 * @return mixed
	 */
	public function wmc_get_order_currency( $order_currency, $WC_Order ) {
		$this->current_currency                             = $order_currency;
		$this->selected_currencies[$order_currency]['rate'] = 1;
		if ( ! array_key_exists( 'pos', $this->selected_currencies[$order_currency] ) ) {
			$this->selected_currencies[$order_currency]['pos']        = $this->selected_currencies[$this->main_currency]['pos'];
			$this->selected_currencies[$order_currency]['num_of_dec'] = $this->selected_currencies[$this->main_currency]['num_of_dec'];
		}

		return apply_filters( 'wmc_get_order_currency', $order_currency, $WC_Order );
	}

	/**
	 * Return nothing to overwrite hash of price to get
	 */
	public function wmc_get_woocommerce_get_variation_prices_hash() {
		//Do nothing to remove prices hash to alway get live price.
	}

	/**
	 * Convert price of vaiation product to current currency
	 *
	 * @param $price_arr
	 *
	 * @return array
	 */
	public function get_woocommerce_variation_prices( $price_arr ) {
		if ( is_array( $price_arr ) && ! empty( $price_arr ) ) {
			foreach ( $price_arr as $price_type => $values ) {
				foreach ( $values as $key => $value ) {
					$price_arr[$price_type][$key] = $value * $this->selected_currencies[$this->current_currency]['rate'];
				}
			}
		}

		return $price_arr;
	}

	/**
	 * Auto get update exchange after setted time in setting
	 */
	public function wmc_auto_update_rates() {
		$last_update_time = get_option( 'last_update_rates_time' );
		$update_time      = get_option( 'wmc_auto_update_rates_time' );
		$time_type        = get_option( 'wmc_auto_update_time_type' );
		switch ( $time_type ) {
			case    "min":
				$diff = floor( ( time() - $last_update_time ) / 60 );
				break;
			case    "hour":
				$diff = floor( ( time() - $last_update_time ) / 60 / 60 );
				break;
			case    "day":
				$diff = floor( ( time() - $last_update_time ) / 60 / 60 / 24 );
				break;

		}
		if ( $diff >= $update_time ) {
			update_option( 'last_update_rates_time', time() );
			foreach ( $this->selected_currencies as $code => $values ) {
				if ( $code == $this->main_currency ) {
					$this->selected_currencies[$code]['rate'] = 1;
				} else {
					$response                                 = file_get_contents( 'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20csv%20where%20url%3D%22http%3A%2F%2Ffinance.yahoo.com%2Fd%2Fquotes.csv%3Fe%3D.csv%26f%3Dc4l1%26s%3D' . $this->main_currency . $code . '%3DX%22%3B&format=json' );
					$resArr                                   = json_decode( $response );
					$this->selected_currencies[$code]['rate'] = $resArr->query->results->row->col1;
				}
			}
			update_option( 'wmc_selected_currencies', $this->selected_currencies );
		}
	}

	/**
	 * Change currency position as setting
	 *
	 * @param $post
	 *
	 * @return mixed
	 */
	public function view_order( $post ) {
		if ( is_object( $post ) AND $post->post_type == 'shop_order' ) {
			$currency = get_post_meta( $post->ID, '_order_currency', true );
			if ( ! empty( $currency ) ) {
				$this->current_currency = $currency;
				add_filter( 'woocommerce_price_format', array( $this, 'wmc_get_price_format' ) );
			}
		}

		return $post;
	}

	/**
	 * Get ip of client
	 * @return mixed ip of client
	 */
	public function getIP() {
		if ( isset( $_SERVER["HTTP_CLIENT_IP"] ) ) {
			return $_SERVER["HTTP_CLIENT_IP"];
		} elseif ( isset( $_SERVER["HTTP_X_FORWARDED_FOR"] ) ) {
			return $_SERVER["HTTP_X_FORWARDED_FOR"];
		} elseif ( isset( $_SERVER["HTTP_X_FORWARDED"] ) ) {
			return $_SERVER["HTTP_X_FORWARDED"];
		} elseif ( isset( $_SERVER["HTTP_FORWARDED_FOR"] ) ) {
			return $_SERVER["HTTP_FORWARDED_FOR"];
		} elseif ( isset( $_SERVER["HTTP_FORWARDED"] ) ) {
			return $_SERVER["HTTP_FORWARDED"];
		} else {
			return $_SERVER["REMOTE_ADDR"];
		}
	}

	/**
	 * Get informations about client as current country and currency code, current rate
	 * @return array|mixed|string
	 */
	public function detect_ip_currency() {
		$ip_addr = $this->getIP();
		//$ip_addr   = '14.189.176.98';
		@$geoplugin = wp_remote_get( 'http://www.geoplugin.net/php.gp?ip=' . $ip_addr );
		if ( $geoplugin ) {
			$geoplugin = unserialize( $geoplugin['body'] );
		}
		if ( is_array( $geoplugin ) and isset( $geoplugin['geoplugin_currencySymbol'] ) ) {
			@$response = wp_remote_get( 'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20csv%20where%20url%3D%22http%3A%2F%2Ffinance.yahoo.com%2Fd%2Fquotes.csv%3Fe%3D.csv%26f%3Dc4l1%26s%3D' . $this->main_currency . $geoplugin['geoplugin_currencyCode'] . '%3DX%22%3B&format=json' );
			if ( $response ) {
				$resArr                         = json_decode( $response['body'], true );
				$rate                           = $resArr['query']['results']['row']['col1'];
				$geoplugin['rate']              = $rate;
				$_SESSION['detect_ip_currency'] = $geoplugin;
			}

			return $geoplugin;
		}
	}

	/**
	 * @param $html_price    default price
	 * @param $a
	 *
	 * @return string
	 */
	public function add_approximately_price( $html_price, $product ) {
		$enable_appoxi = get_option( 'wmc_enable_approxi' );
		if ( $enable_appoxi == 'yes' ) {
			$decimal_separator  = $this->get_price_decimal_separator();
			$thousand_separator = $this->get_price_thousand_separator();
			$decimals           = $this->get_price_decimals();
			if ( isset( $_SESSION['detect_ip_currency'] ) && ! empty( $_SESSION['detect_ip_currency'] ) ) {
				$detect_ip_currency = $_SESSION['detect_ip_currency'];
			} else {
				$detect_ip_currency = $this->detect_ip_currency();
			}
			if ( is_array( $detect_ip_currency ) && $detect_ip_currency['geoplugin_currencyCode'] != $this->current_currency ) {
				if ( $product->sale_price > 0 ) {
					$html_price = $html_price . "<br> " . esc_html__( 'Approximately Price', 'woo-multi-currency' ) . ": " . number_format( $product->sale_price * $detect_ip_currency['rate'], $decimals, $decimal_separator, $thousand_separator ) . ' ' . $detect_ip_currency['geoplugin_currencySymbol'];
				} elseif ( $product->regular_price > 0 ) {
					$html_price = $html_price . "<br> " . esc_html__( 'Approximately Price', 'woo-multi-currency' ) . ": " . number_format( $product->regular_price * $detect_ip_currency['rate'], $decimals, $decimal_separator, $thousand_separator ) . ' ' . $detect_ip_currency['geoplugin_currencySymbol'];
				} else {
					$html_price = $html_price . "<br> " . esc_html__( 'Approximately Price', 'woo-multi-currency' ) . ": " . number_format( $product->price * $detect_ip_currency['rate'], $decimals, $decimal_separator, $thousand_separator ) . ' ' . $detect_ip_currency['geoplugin_currencySymbol'];
				}
			}
		}

		return $html_price;
	}

	/**
	 * @param $rates  standar taxs or shipping rates of  main currency
	 *
	 * @return mixed    taxs or shipping rates after convert to current currency
	 */
	public function wmc_package_rates( $rates ) {
		$demicial = $this->get_price_decimals();
		foreach ( $rates as $rate ) {
			$value      = $rate->cost * $this->selected_currencies[$this->current_currency]['rate'];
			$rate->cost = number_format( floatval( $value ), $demicial, $this->get_price_decimal_separator(), '' );
		}

		return $rates;
	}

	/**
	 * @return string    Curren currency
	 */
	public function wmc_get_current_currency() {
		return $this->current_currency;
	}

	/**
	 * @param $raw_price    standar price
	 *
	 * @return mixed    price after convert to current currency
	 */
	public function wmc_woocommerce_get_price( $raw_price ) {

		$demicial = $this->get_price_decimals();
		if ( $raw_price !== '' ) {
			$raw_price = $raw_price * $this->selected_currencies[$this->current_currency]['rate'];
		}

		//$raw_price = number_format( $raw_price, $demicial, $this->get_price_decimal_separator(), '' );

		return $raw_price;
	}

	/**
	 * @return mixed    current currency symbol
	 */
	public function wmc_get_currency_symbol() {
		if ( get_post_type() == 'product' && is_admin() ) {
			$this->current_currency = get_option( 'woocommerce_currency' );
		}
		if ( empty( $this->current_currency ) ) {
			$this->current_currency = $this->main_currency;
		}

		return $this->currencies_symbol[$this->current_currency];
	}

	/**
	 * @return string    price format of current currency
	 */
	public function wmc_get_price_format() {
		if ( array_key_exists( $this->current_currency, $this->selected_currencies ) ) {
			$current_pos = $this->selected_currencies[$this->current_currency]['pos'];
		} else {
			$current_pos = get_option( 'woocommerce_currency_pos' );
		}

		switch ( $current_pos ) {
			case 'left' :
				$format = '%1$s%2$s';
				break;
			case 'right' :
				$format = '%2$s%1$s';
				break;
			case 'left_space' :
				$format = '%1$s&nbsp;%2$s';
				break;
			case 'right_space' :
				$format = '%2$s&nbsp;%1$s';
				break;
		}

		return $format;
	}

	/**
	 * Get Exchange rate function
	 */
	public function wmc_get_rate() {
		$main_currency = $_REQUEST['main_currency'];
		if ( is_array( $_REQUEST['second_currency'] ) ) {
			$second_currency = $_REQUEST['second_currency'];
		} else {
			$second_currency[] = $_REQUEST['second_currency'];
		}
		for ( $i = 0; $i < count( $second_currency ); $i ++ ) {
			@$rate = wp_remote_get( 'https://query.yahooapis.com/v1/public/yql?q=select%20*%20from%20csv%20where%20url%3D%22http%3A%2F%2Ffinance.yahoo.com%2Fd%2Fquotes.csv%3Fe%3D.csv%26f%3Dc4l1%26s%3D' . $main_currency . $second_currency[$i] . '%3DX%22%3B&format=json' );
			if ( $rate ) {
				$resArr = json_decode( $rate['body'], true );
				if ( is_array( $resArr ) ) {
					$response[] = $resArr['query']['results']['row']['col1'];
				}
			}
		}
		echo( json_encode( $response ) );
		exit;
	}

	/**
	 * Load text domain function
	 */
	public function wmc_load_text_domain() {
		$plugin_dir = basename( dirname( __FILE__ ) );
		load_plugin_textdomain( 'woo-multi-currency', false, $plugin_dir . '\languages' );
	}

	/**
	 * Add Setting link under plugin name
	 *
	 * @param $links
	 *
	 * @return mixed
	 */

	public function wmc_add_settings_link( $links ) {
		$settings_link = '<a href="admin.php?page=wc-settings&tab=wmc" title="' . esc_attr__( 'Woo Multi Currency', 'woo-multi-currency' ) . '>">' . esc_html__( 'Settings', 'woo-multi-currency' ) . '</a>';
		array_unshift( $links, $settings_link );

		return $links;
	}

	/**
	 * Create short code
	 *
	 * @param $args
	 */
	public function wmc_widget_shortcode( $args ) {
		if ( empty( $args ) ) {
			$args = array();
		}

		$args = wp_parse_args(
			$args, apply_filters(
				'wmc_widget_arg', array(
					'selected_currencies' => $this->selected_currencies,
					'currencies_list'     => $this->currencies_list,
					'current_currency'    => $this->current_currency,
				)
			)
		);

		$this->wmc_get_template( 'woo-multi-currency_widget.php', $args );

	}
	/**
	 * Create short code
	 *
	 * @param $args
	 */
	public function wmc_widget_converter_shortcode( $args ) {
		if ( empty( $args ) ) {
			$args = array();
		}

		$args = wp_parse_args(
			$args, apply_filters(
				'wmc_widget_arg', array(
					'selected_currencies' => $this->selected_currencies,
					'currencies_list'     => $this->currencies_list,
					'current_currency'    => $this->current_currency,
				)
			)
		);

		$this->wmc_get_template( 'woo-multi-currency_converter_widget.php', $args );

	}

	/**
	 * Init widget
	 */
	public function widgets_init() {
		include plugin_dir_path( __FILE__ ) . 'widgets/wmc_widget.php';
		register_widget( 'WMC_Widget' );
		include plugin_dir_path( __FILE__ ) . 'widgets/wmc_widget_converter.php';
		register_widget( 'WMC_Widget_Converter' );
	}

	/**
	 * Save currency in backend
	 */
	public function wmc_save_selected_currencies() {
		foreach ( $_POST['wmc_currency'] as $key => $code ) {
			if ( ! empty( $code ) ) {
				$result[$code] = array(
					'is_main'    => $_POST['wmc_hidden_is_main'][$key],
					'pos'        => $_POST['wmc_pos'][$key],
					'rate'       => $_POST['wmc_rate'][$key],
					'symbol'     => $this->currencies_symbol[$code],
					'num_of_dec' => $_POST['num_of_dec'][$key],
				);
				if ( $_POST['wmc_hidden_is_main'][$key] == 1 ) {
					$this->main_currency = $code;
					$main_currency_pos   = $_POST['wmc_pos'][$key];
				}

			}
		}
		update_option( 'wmc_selected_currencies', $result );
		update_option( 'woocommerce_currency', $this->main_currency );
		update_option( 'woocommerce_currency_pos', $main_currency_pos );
		update_option( 'wmc_auto_update_rates_time', $_POST['wmc_auto_update_rates_time'] );
		update_option( 'wmc_auto_update_time_type', $_POST['wmc_auto_update_time_type'] );

	}

	/**
	 * Init JS and CSS
	 */
	public function wmc_load_js_css() {
		wp_enqueue_script( 'jquery' );
		wp_enqueue_script( 'jquery-ui-core' );
		wp_enqueue_script( 'jquery-ui-sortable' );
		wp_enqueue_script( 'select2', plugins_url() . '/woo-multi-currency/js/select2.min.js', array(), WOO_MULTI_CURRENCY_VERSION );
		wp_enqueue_script( 'wmcjs', plugins_url() . '/woo-multi-currency/js/woo-multi-currency.js', array(), WOO_MULTI_CURRENCY_VERSION );
		wp_enqueue_style( 'select2css', plugins_url() . '/woo-multi-currency/css/select2.min.css', array(), WOO_MULTI_CURRENCY_VERSION );
		wp_enqueue_style( 'wmccss', plugins_url() . '/woo-multi-currency/css/wmc.css', array(), WOO_MULTI_CURRENCY_VERSION );
	}

	/**
	 * Add Multi Woo Currency tab to WooCommerce setting tab
	 *
	 * @param $tabs
	 *
	 * @return mixed
	 */
	public function wmc_add_tab( $tabs ) {
		$tabs['wmc'] = esc_html__( 'Woo Multi Currency', 'woo-multi-currency' );

		return $tabs;
	}

	/**
	 * Add field
	 */
	public function wmc_add_setting_fields() {
		$sections        = array(
			'wmc_currency' => 'Currencies',
			//'woocurrency_options' => 'Options',
		);
		$array_keys      = array_keys( $sections );
		$current_section = isset( $_GET['section'] ) ? sanitize_text_field( $_GET['section'] ) : 'wmc_currency';
		$args            = array();
		$args            = wp_parse_args(
			$args, apply_filters(
				'wmc_admin_setting_arg', array(
					'currencies_list'            => $this->wmc_get_currencies_list(),
					'currencies_symbol'          => $this->wmc_get_currencies_symbol(),
					'selected_currencies'        => $this->selected_currencies,
					'setting_fields'             => $this->wmc_get_settings_fields(),
					'wmc_auto_update_rates_time' => get_option( 'wmc_auto_update_rates_time' ),
					'wmc_auto_update_time_type'  => get_option( 'wmc_auto_update_time_type' ),
				)
			)
		);

		$this->wmc_get_template_admin( 'woo-multi-currency_admin_setting.php', $args );

	}

	/**
	 * Update setting fields
	 */
	public function wmc_update_settings_fields() {
		$fields = $this->wmc_get_settings_fields();
		woocommerce_update_options( $fields );
	}

	/*
	 * Get setting fields
	 */
	public function wmc_get_settings_fields() {
		$setting_fields = array(

			array(
				'title' => esc_html__( 'GENERAL OPTIONS', 'woo-multi-currency' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'woo-multi-currency_ganeral'
			),
			array(
				'title'    => esc_html__( 'Approximately Price', 'woo-multi-currency' ),
				'id'       => 'wmc_enable_approxi',
				'default'  => 'no',
				'desc'     => esc_html__( 'Enable Approximately Price on your shop page. It will auto detect customer\'s country .', 'woo-multi-currency' ),
				'type'     => 'checkbox',
				'desc_tip' => true,

			),
			array(
				'title'    => esc_html__( 'Allow multi currency payment', 'woo-multi-currency' ),
				'id'       => 'wmc_allow_multi',
				'default'  => 'no',
				'desc'     => esc_html__( 'Enable multi currency payment on your shop page.', 'woo-multi-currency' ),
				'type'     => 'checkbox',
				'desc_tip' => true,
			),
			array(
				'title'    => esc_html__( 'Auto update exchange rate', 'woo-multi-currency' ),
				'id'       => 'wmc_auto_update_rates',
				'default'  => 'no',
				'desc'     => esc_html__( 'Check to enable auto update exchange rate.', 'woo-multi-currency' ),
				'type'     => 'checkbox',
				'desc_tip' => true,
			),
			array(
				'type' => 'sectionend',
				'id'   => 'woo-multi-currency_ganeral'
			)
		);

		return apply_filters( 'woo_multi_currency_tab_options', $setting_fields );
	}

	public function currency_option_title( $data ) {
		$new_data = array(
			array(
				'title' => esc_html__( 'CURRENCY OPTIONS', 'woo-multi-currency' ),
				'type'  => 'title',
				'desc'  => '',
				'id'    => 'woo-multi-currency_select_currency'
			),
			array(
				'type' => 'sectionend',
				'id'   => 'woo-multi-currency_select_currency'
			)
		);

		return array_merge( $data, $new_data );
	}

	/*
	 * Get all supported currency symbol
	 */
	public function wmc_get_currencies_symbol() {

		$symbols = array(
			'AED' => 'د.إ',
			'ARS' => '&#36;',
			'AUD' => '&#36;',
			'BDT' => '&#2547;&nbsp;',
			'BGN' => '&#1083;&#1074;.',
			'BRL' => '&#82;&#36;',
			'CAD' => '&#36;',
			'CHF' => '&#67;&#72;&#70;',
			'CLP' => '&#36;',
			'CNY' => '&yen;',
			'COP' => '&#36;',
			'CZK' => '&#75;&#269;',
			'DKK' => 'DKK',
			'DOP' => 'RD&#36;',
			'EGP' => 'EGP',
			'EUR' => '&euro;',
			'GBP' => '&pound;',
			'HKD' => '&#36;',
			'HRK' => 'Kn',
			'HUF' => '&#70;&#116;',
			'IDR' => 'Rp',
			'ILS' => '&#8362;',
			'INR' => '&#8377;',
			'ISK' => 'Kr.',
			'JPY' => '&yen;',
			'KES' => 'KSh',
			'KRW' => '&#8361;',
			'LAK' => '&#8365;',
			'MXN' => '&#36;',
			'MYR' => '&#82;&#77;',
			'NGN' => '&#8358;',
			'NOK' => '&#107;&#114;',
			'NPR' => '&#8360;',
			'NZD' => '&#36;',
			'PHP' => '&#8369;',
			'PKR' => '&#8360;',
			'PLN' => '&#122;&#322;',
			'PYG' => '&#8370;',
			'RON' => 'lei',
			'RUB' => 'руб',
			'SAR' => '&#x631;.&#x633;',
			'SEK' => '&#107;&#114;',
			'SGD' => '&#36;',
			'THB' => '&#3647;',
			'TRY' => '&#8378;',
			'TWD' => '&#78;&#84;&#36;',
			'UAH' => '&#8372;',
			'USD' => '&#36;',
			'VND' => '&#8363;',
			'ZAR' => '&#82;',
		);

		return $symbols;
	}

	/*
	 * Get all supported currency
	 */
	public function wmc_get_currencies_list() {
		return array_unique(
			apply_filters(
				'woocommerce_currencies',
				array(
					'AED' => esc_html__( 'United Arab Emirates Dirham', 'woo-multi-currency' ),
					'ARS' => esc_html__( 'Argentine Peso', 'woo-multi-currency' ),
					'AUD' => esc_html__( 'Australian Dollars', 'woo-multi-currency' ),
					'BDT' => esc_html__( 'Bangladeshi Taka', 'woo-multi-currency' ),
					'BGN' => esc_html__( 'Bulgarian Lev', 'woo-multi-currency' ),
					'BRL' => esc_html__( 'Brazilian Real', 'woo-multi-currency' ),
					'CAD' => esc_html__( 'Canadian Dollars', 'woo-multi-currency' ),
					'CHF' => esc_html__( 'Swiss Franc', 'woo-multi-currency' ),
					'CLP' => esc_html__( 'Chilean Peso', 'woo-multi-currency' ),
					'CNY' => esc_html__( 'Chinese Yuan', 'woo-multi-currency' ),
					'COP' => esc_html__( 'Colombian Peso', 'woo-multi-currency' ),
					'CZK' => esc_html__( 'Czech Koruna', 'woo-multi-currency' ),
					'DKK' => esc_html__( 'Danish Krone', 'woo-multi-currency' ),
					'DOP' => esc_html__( 'Dominican Peso', 'woo-multi-currency' ),
					'EGP' => esc_html__( 'Egyptian Pound', 'woo-multi-currency' ),
					'EUR' => esc_html__( 'Euros', 'woo-multi-currency' ),
					'GBP' => esc_html__( 'Pounds Sterling', 'woo-multi-currency' ),
					'HKD' => esc_html__( 'Hong Kong Dollar', 'woo-multi-currency' ),
					'HRK' => esc_html__( 'Croatia kuna', 'woo-multi-currency' ),
					'HUF' => esc_html__( 'Hungarian Forint', 'woo-multi-currency' ),
					'IDR' => esc_html__( 'Indonesia Rupiah', 'woo-multi-currency' ),
					'ILS' => esc_html__( 'Israeli Shekel', 'woo-multi-currency' ),
					'INR' => esc_html__( 'Indian Rupee', 'woo-multi-currency' ),
					'ISK' => esc_html__( 'Icelandic krona', 'woo-multi-currency' ),
					'JPY' => esc_html__( 'Japanese Yen', 'woo-multi-currency' ),
					'KES' => esc_html__( 'Kenyan shilling', 'woo-multi-currency' ),
					'KRW' => esc_html__( 'South Korean Won', 'woo-multi-currency' ),
					'LAK' => esc_html__( 'Lao Kip', 'woo-multi-currency' ),
					'MXN' => esc_html__( 'Mexican Peso', 'woo-multi-currency' ),
					'MYR' => esc_html__( 'Malaysian Ringgits', 'woo-multi-currency' ),
					'NGN' => esc_html__( 'Nigerian Naira', 'woo-multi-currency' ),
					'NOK' => esc_html__( 'Norwegian Krone', 'woo-multi-currency' ),
					'NPR' => esc_html__( 'Nepali Rupee', 'woo-multi-currency' ),
					'NZD' => esc_html__( 'New Zealand Dollar', 'woo-multi-currency' ),
					'PHP' => esc_html__( 'Philippine Pesos', 'woo-multi-currency' ),
					'PKR' => esc_html__( 'Pakistani Rupee', 'woo-multi-currency' ),
					'PLN' => esc_html__( 'Polish Zloty', 'woo-multi-currency' ),
					'PYG' => esc_html__( 'Paraguayan Guaraní', 'woo-multi-currency' ),
					'RON' => esc_html__( 'Romanian Leu', 'woo-multi-currency' ),
					'RUB' => esc_html__( 'Russian Ruble', 'woo-multi-currency' ),
					'SAR' => esc_html__( 'Saudi Riyal', 'woo-multi-currency' ),
					'SEK' => esc_html__( 'Swedish Krona', 'woo-multi-currency' ),
					'SGD' => esc_html__( 'Singapore Dollar', 'woo-multi-currency' ),
					'THB' => esc_html__( 'Thai Baht', 'woo-multi-currency' ),
					'TRY' => esc_html__( 'Turkish Lira', 'woo-multi-currency' ),
					'TWD' => esc_html__( 'Taiwan New Dollars', 'woo-multi-currency' ),
					'UAH' => esc_html__( 'Ukrainian Hryvnia', 'woo-multi-currency' ),
					'USD' => esc_html__( 'US Dollars', 'woo-multi-currency' ),
					'VND' => esc_html__( 'Vietnamese Dong', 'woo-multi-currency' ),
					'ZAR' => esc_html__( 'South African rand', 'woo-multi-currency' ),
				)
			)
		);
	}

	/**
	 * Get path of template
	 *
	 * @param        $template_name
	 * @param string $template_path
	 * @param string $default_path
	 *
	 * @return mixed
	 */
	public function wmc_locate_template( $template_name, $template_path = '', $default_path = '' ) {
		if ( ! $template_path ) {
			$template_path = '/woo-multi-currency/';
		}
		if ( ! $default_path ) {
			$default_path = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/';
		}
		// Look within passed path within the theme - this is priority.
		$template = locate_template( array( trailingslashit( $template_path ) . $template_name, $template_name ) );
		// Get default template/
		if ( ! $template ) {
			$template = $default_path . $template_name;
		}

		// Return what we found.
		return apply_filters( 'wmc_locate_template', $template, $template_name, $template_path );
	}

	/**
	 * Get template
	 *
	 * @param        $template_name
	 * @param array  $args
	 * @param string $template_path
	 * @param string $default_path
	 */
	public function wmc_get_template( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		if ( $args && is_array( $args ) ) {
			extract( $args );
		}
		$located = $this->wmc_locate_template( $template_name, $template_path, $default_path );
		if ( ! file_exists( $located ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );

			return;
		}
		// Allow 3rd party plugin filter template file from their plugin.
		$located = apply_filters( 'wmc_get_template', $located, $template_name, $args, $template_path, $default_path );
		do_action( 'wmc_before_template_part', $template_name, $template_path, $located, $args );
		include( $located );
		do_action( 'wmc_template_part', $template_name, $template_path, $located, $args );
	}

	/**
	 * Get template for admin. Do not allow overwrite
	 *
	 * @param        $template_name
	 * @param array  $args
	 * @param string $template_path
	 * @param string $default_path
	 */
	public function wmc_get_template_admin( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		if ( $args && is_array( $args ) ) {
			extract( $args );
		}
		$default_path = untrailingslashit( plugin_dir_path( __FILE__ ) ) . '/templates/';
		$located      = $default_path . $template_name;;
		if ( ! file_exists( $located ) ) {
			_doing_it_wrong( __FUNCTION__, sprintf( '<code>%s</code> does not exist.', $located ), '2.1' );

			return;
		}
		do_action( 'wmc_before_template_part', $template_name, $template_path, $located, $args );
		include( $located );
		do_action( 'wmc_template_part', $template_name, $template_path, $located, $args );
	}

	/**
	 * Get template html
	 *
	 * @param        $template_name
	 * @param array  $args
	 * @param string $template_path
	 * @param string $default_path
	 *
	 * @return string
	 */
	public function wmc_get_template_html( $template_name, $args = array(), $template_path = '', $default_path = '' ) {
		ob_start();
		$this->wmc_get_template( $template_name, $args, $template_path, $default_path );

		return ob_get_clean();
	}

	public function get_price_thousand_separator() {
		$separator = stripslashes( get_option( 'woocommerce_price_thousand_sep' ) );

		return $separator;
	}

	public function get_price_decimal_separator() {
		$separator = stripslashes( get_option( 'woocommerce_price_decimal_sep' ) );

		return $separator ? $separator : '.';
	}

	public function get_price_decimals() {
		try {
			return absint( $this->selected_currencies[$this->current_currency]['num_of_dec'] );
		}
		catch ( Exception $e ) {
			return absint( $this->selected_currencies[$this->main_currency]['num_of_dec'] );
		}
	}

}

include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
if ( is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
	$WMC = new Woo_Multi_Currency();
}
?>
