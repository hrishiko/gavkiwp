<?php

if (!class_exists('shopme_woof_widget')) {

	class shopme_woof_widget extends WP_Widget {

		function __construct() {
			$settings  = array( 'classname' => 'shopme-widget-woof-filter woocommerce', 'description' => esc_html__( 'WooCommerce Products Filter', 'shopme' ) );

			parent::__construct('shopme-widget-woof-filter', SHOPME_THEMENAME .' '. esc_html__('WooCommerce Products Filter', 'shopme'), $settings);
		}

		function widget($args, $instance) {

			global $_attributes_array;

			extract($args);

			if ( ! is_post_type_archive( 'product' ) && ! is_tax( array_merge( (array) $_attributes_array, array( 'product_cat', 'product_tag' ) ) ) ) {
				return;
			}

			$title = apply_filters( 'widget_title', ( isset( $instance['title'] ) ? $instance['title'] : ''), $instance, $this->id_base );

			$args['instance'] = $instance;
			$args['sidebar_id'] = $args['id'];
			$args['sidebar_name'] = $args['name'];

			ob_start();
			echo $before_widget . $before_title . $title . $after_title;
			echo do_shortcode('[shopme_woof]');
			echo $after_widget;
			echo ob_get_clean();
		}

		function update($new_instance, $old_instance) {
			$instance = $old_instance;
			$instance['title'] = $new_instance['title'];
			return $instance;
		}

		function form($instance) {
			$defaults = array(
				'title' => esc_html__('Products Filter', 'shopme')
			);
			$instance = wp_parse_args((array) $instance, $defaults);
			?>
			<p>
				<label for="<?php echo $this->get_field_id('title'); ?>"><?php esc_html_e('Title', 'shopme') ?>:</label>
				<input class="widefat" type="text" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" value="<?php echo $instance['title']; ?>" />
			</p>
			<p><?php esc_html_e('There are options for this widget in Woocommerce -> Settings -> Products Filter', 'shopme') ?></p>
		<?php
		}

	}

}
