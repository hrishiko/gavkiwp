<?php

$shopme_post_id = shopme_post_id();
$shopme_before_content = rwmb_meta('shopme_before_content', '', $shopme_post_id); ?>

<?php if ($shopme_before_content && $shopme_before_content > 0): ?>

	<?php
		$shopme_page = get_pages(array(
			'include' => $shopme_before_content
		));
	?>

	<?php if (!empty($shopme_page)): ?>
		<?php echo do_shortcode($shopme_page[0]->post_content); ?>
	<?php endif; ?>

<?php endif; ?>
