<?php if (!defined('ABSPATH')) die('No direct access allowed'); ?>

<div data-css-class="woof_sku_search_container" class="woof_sku_search_container woof_container">

    <div class="woof_container_inner">

        <?php

        $shopme_woof_sku = '';
        $request = $this->get_request_data();

        if (isset($request['shopme_woof_sku']))  {
            $shopme_woof_sku = $request['shopme_woof_sku'];
        }

        $p = __('enter a product sku here ...', 'shopme');
        $unique_id = uniqid('shopme_woof_sku_search_');

        ?>

        <div class="woof_sku_table">
            <input type="search" class="shopme_woof_show_sku_search <?php echo $unique_id ?>" data-uid="<?php echo $unique_id ?>" placeholder="<?php echo esc_attr($p) ?>" name="shopme_woof_sku" value="<?php echo $shopme_woof_sku ?>" />
        </div>

    </div>

</div>