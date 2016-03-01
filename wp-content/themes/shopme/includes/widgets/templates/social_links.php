
<div class="social_icons_holder">

	<ul class="social_btns">

		<?php if ($facebook_links != '') : ?>
			<li class="facebook">
				<a target="_blank" href="<?php echo esc_url($facebook_links); ?>" class="icon_btn middle_btn social_facebook tooltip_container">
					<i class="icon-facebook-1"></i><span class="tooltip top">Facebook</span>
				</a>
			</li>
		<?php endif; ?>

		<?php if ($twitter_links != '') : ?>
			<li class="twitter">
				<a target="_blank" href="<?php echo esc_url($twitter_links); ?>" class="icon_btn middle_btn  social_twitter tooltip_container">
					<i class="icon-twitter"></i><span class="tooltip top">Twitter</span>
				</a>
			</li>
		<?php endif; ?>

		<?php if ($gplus_links != '') : ?>
			<li class="google_plus">
				<a target="_blank" href="<?php echo esc_url($gplus_links); ?>" class="icon_btn middle_btn social_googleplus tooltip_container">
					<i class="icon-gplus-2"></i><span class="tooltip top">GooglePlus</span>
				</a>
			</li>
		<?php endif; ?>

		<?php if ($pinterest_links != '') : ?>
			<li class="pinterest">
				<a target="_blank" href="<?php echo esc_url($pinterest_links); ?>" class="icon_btn middle_btn social_pinterest tooltip_container">
					<i class="icon-pinterest-3"></i><span class="tooltip top">Pinterest</span>
				</a>
			</li>
		<?php endif; ?>

		<?php if ($flickr_links != '') : ?>
			<li class="flickr">
				<a target="_blank" href="<?php echo esc_url($flickr_links); ?>" class="icon_btn middle_btn social_flickr tooltip_container">
					<i class="icon-flickr-1"></i><span class="tooltip top">Flickr</span>
				</a>
			</li>
		<?php endif; ?>

		<?php if ($youtube_links != '') : ?>
			<li class="youtube">
				<a target="_blank" href="<?php echo esc_url($youtube_links); ?>" class="icon_btn middle_btn social_youtube tooltip_container">
					<i class="icon-youtube"></i><span class="tooltip top">Youtube</span>
				</a>
			</li>
		<?php endif; ?>

		<?php if ($vimeo_links != '') : ?>
			<li class="vimeo">
				<a target="_blank" href="<?php echo esc_url($vimeo_links); ?>" class="icon_btn middle_btn social_vimeo tooltip_container">
					<i class="icon-vimeo-2"></i><span class="tooltip top">Vimeo</span>
				</a>
			</li>
		<?php endif; ?>

		<?php if ($instagram_links != '') : ?>
			<li class="instagram">
				<a target="_blank" href="<?php echo esc_url($instagram_links); ?>" class="icon_btn middle_btn social_instagram tooltip_container">
					<i class="icon-instagram-4"></i><span class="tooltip top">Instagram</span>
				</a>
			</li>
		<?php endif; ?>

		<?php if ($linkedin_links != '') : ?>
			<li class="linkedin">
				<a target="_blank" href="<?php echo esc_url($linkedin_links); ?>" class="icon_btn middle_btn social_linkedin tooltip_container">
					<i class="icon-linkedin-5"></i><span class="tooltip top">LinkedIn</span>
				</a>
			</li>
		<?php endif; ?>

		<?php if ($vk_links != '') : ?>
			<li class="vk">
				<a target="_blank" href="<?php echo esc_url($vk_links); ?>" class="icon_btn middle_btn social_vk tooltip_container">
					<i class="icon-vk"></i><span class="tooltip top">Vkontakte</span>
				</a>
			</li>
		<?php endif; ?>

		<?php if ($contact_us != '') : ?>
			<li class="envelope">
				<a target="_blank" href="mailto:<?php echo esc_url($contact_us); ?>" class="icon_btn middle_btn social_contact tooltip_container">
					<i class="icon-contacts"></i><span class="tooltip top">Contact Us</span>
				</a>
			</li>
		<?php endif; ?>

	</ul><!--/ .social-icons-->

</div><!--/ .social_icons_holder-->