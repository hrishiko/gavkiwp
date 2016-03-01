<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>

<div data-css-class="woof_text_search_container" class="woof_text_search_container woof_container">

    <div class="woof_container_inner">

		<?php
		$shopme_woof_text = '';
		$request = $this->get_request_data();

		if (isset($request['shopme_woof_text'])) {
			$shopme_woof_text = $request['shopme_woof_text'];
		}

		$p = __('enter a product title here ...', 'shopme');
		$unique_id = uniqid('shopme_woof_text_search_');
		?>

		<div class="woof_text_table">
			<input type="search" class="shopme_woof_show_text_search <?php echo $unique_id ?>" data-uid="<?php echo $unique_id ?>" placeholder="<?php echo esc_attr($p) ?>" name="shopme_woof_text" value="<?php echo $shopme_woof_text ?>" />
		</div>

    </div>
</div>