<?php
/**
 * Show widget
 *
 * This template can be overridden by copying it to yourtheme/woo-currency/woo-currency_widget.php.
 *
 * @author        Cuong Nguyen
 * @package       Woo-currency/Templates
 * @version       1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<script>
	jQuery(document).ready(function () {
		jQuery('#wmc_widget').select2();
		jQuery('#wmc_widget').select2().on("change", function (e) {
			var link = window.location.href;
			link = link.split('?');
			link = link[0];
			window.location = link + '?wmc_current_currency=' + e.val;
		});
	});
</script>
<div class="woo-multi-currency-wrapper">
	<input name="widget_hidden_is_main_currency" id="widget_hidden_is_main_currency" type="hidden" value="0">
	<select name="wmc_widget" id="wmc_widget" class="wc-enhanced-select" style="width:180px;" data-placeholder="Select currency">
		<?php foreach ( $selected_currencies as $code => $values ) : ?>
			<option value="<?php echo $code; ?>" <?php selected( $code, $current_currency ) ?>
					style="width:100px;"><?php echo "(" . $values['symbol'] . ") " . $currencies_list[$code]; ?></option>
		<?php endforeach; ?>
	</select>
</div>