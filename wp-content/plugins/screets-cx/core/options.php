<?php
/**
 * SCREETS © 2016
 *
 * Plugin default options
 *
 * COPYRIGHT © 2016 Screets d.o.o. All rights reserved.
 * This  is  commercial  software,  only  users  who have purchased a valid
 * license  and  accept  to the terms of the  License Agreement can install
 * and use this program.
 *
 * @package Chat X
 * @author Screets
 *
 */

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Get default plugin options
 *
 * @since Chat X (2.0)
 * @return array $opts
 */
function fn_scx_get_opts() {

	$opts = array();

	$editor_settings = array( 'teeny' => true, 'textarea_rows' => 4 );

	//
	// General options
	//
	$opts['general'] = array(

		array(
			'id' 			=> 'enable',
			'name' 			=> __( 'Enable', 'chatx' ),
			'enabled' 		=> __( 'Show', 'chatx' ),
			'disabled' 		=> __( 'Hidden', 'chatx' ),
			'desc' 			=> __( 'You can hide chat widget on all website except the specific pages and categories you select below', 'chatx' ),
			'default' 		=> true,
			'type' 			=> 'enable'
		),

		array(
			'id' 		=> 'display',
			'name' 		=> __( 'Display', 'chatx' ) . fn_scx_admin_desc( sprintf( __( "<a href='%s' target='_blank'>wp_footer()</a> <span class='scx-ico-new-win'></span> function has to be located in your theme", 'chatx' ), 'http://codex.wordpress.org/Function_Reference/wp_footer' ), true ),
			'options' 	=> array(
				'hide_home' => __( 'Hide on homepage', 'chatx' ),
				'hide_offline' => __( 'Hide when all operators offline', 'chatx' ),
				'hide_mobile' => '<span class="dashicons dashicons-smartphone"></span> ' . __( 'Disable on mobile devices', 'chatx' ),
				'hide_ssl' => __( 'Hide from pages that uses SSL', 'chatx' )
			),
			'default'	=> array( 'show' ),
			'type' 		=> 'multicheck'
		),

		array(
			'desc'		=> '<a href="#" id="scx-btn-specific-pages">' . __( 'Specific pages & categories', 'chatx' ) . ' &raquo;</a>',
			'type' 		=> 'note'
		),

		array(
			'id' 		=> 'specific-pages',
			'desc'		=> __( 'Always show on those pages:', 'chatx' ),
			'hidden'	=> true,
			'type' 		=> 'multicheck-pages'
		),

		array(
			'id' 		=> 'specific-cats',
			'desc'		=> __( 'Always show on those categories:', 'chatx' ),
			'hidden'	=> true,
			'type' 		=> 'multicheck-categories'
		),

		array(
			'id' 		=> 'visibility',
			'name' 		=> '<span class="dashicons dashicons-visibility"></span> ' . __( 'Who should see chat box?', 'chatx' ),
			'options' 	=> array(
				'public' => __( 'Public', 'chatx' ) . ' <small class="description">(' . __( 'Anyone who visits your website', 'chatx' ) . ')</small>',
				'wp-user' => __( 'Registered users', 'chatx' ) . ' <small class="description">(' . __( 'All users who logged in WordPress', 'chatx' ) . ')</small>',
				'custom-wp-user' => __( 'Specific user roles', 'chatx' ) . ' <small class="description">(' . __( 'Users who assigned to some specific roles', 'chatx' ) . ')</small>',
				'admins' => __( 'Only admins & operators', 'chatx' )
			),
			'default'	=> 'public',
			'type' 		=> 'radio'
		),

		array(
			'id' 		=> 'show-user-roles',
			'name' 		=> '',
			'desc' 		=> __( 'Make visible for only those user roles', 'chatx' ) . ':',
			'options' 	=> fn_scx_get_role_names(),
			'type' 		=> 'multicheck'
		),

		array(
			'id' => 'api-key',
			'name' => '<span class="dashicons dashicons-post-status"></span> ' . __( 'Screets API Key', 'chatx' ) . ' <span class="scx-red">*</span>',
			'desc' => '<strong>' . sprintf( __( '<a href="%s" target="_blank">Get your API key</a>', 'chatx' ), 'http://screets.org/apps/api/v1/keys/?domain=' . fn_scx_current_domain() ) . '</strong> <span class="scx-ico-new-win"></span><br>' . __( 'It is required to activate the plugin and get <strong>free updates</strong>.', 'chatx' ) . '<br><small>* ' . __( 'Note that you might need to re-login WordPress after updating your API key.', 'chatx' ) . '</small>',
			'type' => 'text'
		)

	);

	//
	// Site info
	//
	$opts['site-info'] = array(

		array(
			'id' => 'site-name',
			'name' => __( 'Site name', 'chatx' ) . '  <span class="scx-red">*</span>',
			'placeholder' => __( 'Site name', 'chatx' ),
			'desc' => __( 'When needed, we will show this name as your site/company name', 'chatx' ),
			'type' => 'text'
		),

		array(
			'id' => 'site-url',
			'name' => __( 'Site url', 'chatx' ) . '  <span class="scx-red">*</span>',
			'placeholder' => 'http://example.com',
			'desc' => __( 'We will redirect your visitors to this URL from emails or other platforms', 'chatx' ),
			'default' => 'http://' . fn_scx_current_domain(),
			'type' => 'text'
		),

		array(
			'id' => 'site-email',
			'name' => __( 'Site email address(es)', 'chatx' ) . '  <span class="scx-red">*</span>' . fn_scx_admin_desc( __( 'The plugin notifications will be sent to this email address', 'chatx' ) . '.<br/><br/>' . sprintf( __( 'If you need SMTP configuration, you will want to use %s plugin or other good one', 'chatx' ), '<a href="https://wordpress.org/plugins/easy-wp-smtp/" target="_blank">Easy WP SMTP</a> <span class="scx-ico-new-win"></span>' ), true ),
			'placeholder' => __( 'Site email address(es)', 'chatx' ),
			'desc' => __( "Separate email addresses with comma ','", 'chatx' ),
			'type' => 'text'
		),

		array(
			'id' => 'site-reply-to',
			'name' => __( 'Site reply-to address', 'chatx' ) . '  <span class="scx-red">*</span>' . fn_scx_admin_desc( __( 'Your visitors will reply to this email', 'chatx' ), true ),
			'placeholder' => __( 'Site reply-to address', 'chatx' ),
			'desc' => __( 'One email address only', 'chatx' ),
			'type' => 'text'
		),

		array(
			'id' => 'site-logo',
			'name' => __( 'Site logo', 'chatx' ),
			'desc' => __( 'Recommended size', 'chatx' ) . ': 200 x 200px<br>' . __( 'Logo will be used in emails (sent to visitors) and other useful places as well', 'chatx' ),
			'default' => SCX_URL . '/assets/img/screets-logo-160px.png',
			'type' => 'upload'
		),

		array(
			'id' => 'site-logo-force',
			'desc' => __( 'Force to use as default operator avatar', 'chatx' ),
			'type' => 'checkbox'
		),

		array(
			'id' => 'social-twitter',
			'name' => __( 'Social links', 'chatx' ) . fn_scx_admin_desc( __( 'Enter full URL (i.e. https://twitter.com/screetscom)', 'chatx' ), true ),
			'placeholder' => 'Twitter',
			'unit' => '<i class="scx-ico-twitter"></i>',
			'type' => 'text'
		),

		array(
			'id' => 'social-facebook',
			'placeholder' => 'Facebook',
			'unit' => '<i class="scx-ico-facebook"></i>',
			'type' => 'text'
		),

		array(
			'id' => 'social-github',
			'placeholder' => 'GitHub',
			'unit' => '<i class="scx-ico-github"></i>',
			'type' => 'text'
		),

		array(
			'id' => 'social-linkedin',
			'placeholder' => 'LinkedIn',
			'unit' => '<i class="scx-ico-linkedin"></i>',
			'type' => 'text'
		),

		array(
			'id' => 'social-skype',
			'placeholder' => 'Skype',
			'unit' => '<i class="scx-ico-skype"></i>',
			'type' => 'text'
		),

		array(
			'id' => 'social-youtube',
			'placeholder' => 'Youtube',
			'unit' => '<i class="scx-ico-youtube"></i>',
			'type' => 'text'
		),

		array(
			'id' => 'social-vimeo',
			'placeholder' => 'Vimeo',
			'unit' => '<i class="scx-ico-vimeo"></i>',
			'type' => 'text'
		),

		array(
			'id' => 'social-slack',
			'placeholder' => 'Slack',
			'unit' => '<i class="scx-ico-slack"></i>',
			'type' => 'text'
		),

		array(
			'id' => 'social-slideshare',
			'placeholder' => 'SlideShare',
			'unit' => '<i class="scx-ico-slideshare"></i>',
			'type' => 'text'
		),

		array(
			'id' => 'social-medium',
			'placeholder' => 'Medium',
			'unit' => '<i class="scx-ico-medium"></i>',
			'type' => 'text'
		)
		
	);

	//
	// Design
	//
	$opts['design'] = array(
		array(
			'id' => 'primary-color',
			'name' => __( 'Primary color', 'chatx' ),
			'default' => '#e54045',
			'type' => 'color'
		),

		array(
			'id' => 'primary-fg-color',
			'name' => __( 'Foreground color', 'chatx' ),
			'default' => '#ffffff',
			'type' => 'color'
		),

		array(
			'id' => 'link-color',
			'name' => __( 'Link color', 'chatx' ),
			'default' => '#42d18c',
			'type' => 'color'
		),

		array(
			'id' => 'bg-color',
			'name' => __( 'Popup background color', 'chatx' ),
			'default' => '#ffffff',
			'type' => 'color'
		),

		array(
			'id' => 'border-color',
			'name' => __( 'Border color', 'chatx' ),
			'default' => '#dddddd',
			'type' => 'color',
			'css' => "
				div#scx-widget .scx-chat-btn,
				div#scx-widget .scx-popup .scx-header {
					color: \$primary-fg-color;
					background-color: \$primary-color;
				}
				div#scx-widget .scx-chat-btn:hover,
				div#scx-widget .scx-popup .scx-header:hover {
					background-color: lighten(\$primary-color, 10%);
				}
				div#scx-widget .scx-popup {
					background-color: \$bg-color;
				}
				div#scx-widget .scx-popup .scx-content {
					border-color: lighten( \$border-color, 7% );
				}
				div#scx-widget .scx-popup a {
					color: \$link-color;
				}
				div#scx-widget .scx-popup a:hover {
					color: lighten( \$link-color, 10%);
				}"
		),

		array(
			'id' => 'popup-size',
			'name' => __( 'Popup size', 'chatx' ),
			'default' => 280,
			'unit' => 'px',
			'placeholder' => __( 'Popup size', 'chatx' ),
			'min' => 100,
			'max' => 400,
			'type' => 'number',
			'css' => 'div#scx-widget .scx-popup { width: (value+px); }'
			
		),

		array(
			'id' => 'widget-pos-y',
			'name' => __( 'Position', 'chatx' ),
			'desc' => __( 'Which side do you want to display chat box', 'chatx' ),
			'default' => 'bottom',
			'options' => array(
				'top' => __( 'Top', 'chatx' ),
				'bottom' => __( 'Bottom', 'chatx' ),
			),
			'type' => 'radio'
		),

		array(
			'id' => 'widget-pos-x',
			'default' => 'right',
			'options' => array(
				'left' => __( 'Left', 'chatx' ),
				'right' => __( 'Right', 'chatx' ),
			),
			'type' => 'radio'
		),

		array(
			'id' 		=> 'offset-x',
			'name'		=> __( 'Horizontal offset', 'chatx' ) . fn_scx_admin_desc( __( 'Sets the horizontal distance between the page bottom and the chat widget', 'chatx' ), true ),
			'unit' 		=> 'px',
			'default' 	=> 20,
			'max' 		=> 100,
			'type' 		=> 'number'
		),

		array(
			'id' 		=> 'offset-y',
			'name'		=> __( 'Vertical offset', 'chatx' ) . fn_scx_admin_desc( __( 'Sets the vertical distance between the edge of page and the chat widget', 'chatx' ), true ),
			'unit' 		=> 'px',
			'default' 	=> 20,
			'max' 		=> 100,
			'type' 		=> 'number',
			'css' => "
				\$t: if( \$widget-pos-y == top, (\$offset-x+px), inherit);
				\$b: if( \$widget-pos-y == bottom, (\$offset-x+px), inherit); 
				\$l: if( \$widget-pos-x == left, (\$offset-y+px), inherit); 
				\$r: if( \$widget-pos-x == right, (\$offset-y+px), inherit);
				
				div#scx-widget .scx-chat-btn, div#scx-widget .scx-popup { top: \$t; bottom: \$b; left: \$l; right: \$r;}"
		),

		array(
			'id' 		=> 'radius',
			'name' 		=> __( 'Radius', 'chatx' ),
			'placeholder'=> __( 'Radius', 'chatx' ),
			'unit' 		=> 'px',
			'default' 	=> '5',
			'max' 		=> 20,
			'type' 		=> 'number',
			'css' => "
				\$rad_tl: if( ( \$widget-pos-y == top and \$offset-x == 0 ) or ( \$widget-pos-x == left and \$offset-y == 0 ), 0, (\$radius+px) );
				\$rad_tr: if( ( \$widget-pos-y == top and \$offset-x == 0 ) or ( \$widget-pos-x == right and \$offset-y == 0 ), 0, (\$radius+px) );
				\$rad_br: if( ( \$widget-pos-y == bottom and \$offset-x == 0 ) or ( \$widget-pos-x == right and \$offset-y == 0 ), 0, (\$radius+px) );
				\$rad_bl: if( ( \$widget-pos-y == bottom and \$offset-x == 0 ) or ( \$widget-pos-x == left and \$offset-y == 0 ), 0, (\$radius+px) );

				div#scx-widget .scx-chat-btn {
					border-radius: \$rad_tl \$rad_tr \$rad_br \$rad_bl;
				}
				div#scx-widget img.alignleft,
				div#scx-widget img.alignright,
				div#scx-widget .scx-popup-online .scx-current-op-avatar,
				div#scx-widget .scx-popup-online textarea.scx-reply,
				div#scx-widget .scx-cnv .scx-msg .scx-msg-img,
				div#scx-widget .scx-cnv .scx-msg .scx-msg-img img:not(.emoji),
				div#scx-widget .scx-cnv .scx-msg-wrap,
				div#scx-widget .scx-cnv .scx-user-avatar > img,
				div#scx-widget .scx-cnv .scx-type-auto-ntf {
					border-radius: \$radius+px;
				}
				"
		),

		array(
			'id' 		=> 'padding',
			'name' 		=> 'Padding',
			'desc' 		=> sprintf( __( 'The %s area is the space between the content of the chat box and its border', 'chatx' ) , '<em>padding</em>' ),
			'placeholder'=> 'Padding',
			'unit' 		=> 'px',
			'default' 	=> 20,
			'min' 		=> 10,
			'max' 		=> 30,
			'type' 		=> 'number',
			'css' => "
				div#scx-widget .scx-cnv,
				div#scx-widget .scx-popup .scx-body .scx-wrap,
				div#scx-widget .scx-popup-online .scx-ntf .scx-wrap {
					padding: (\$padding/2)+px \$padding+px;
				}

				div#scx-widget .scx-button {
					padding: 0 \$padding+px;
				}
				div#scx-widget .scx-button.scx-small {
					padding: 0 (\$padding/2)+px;
				}
				div#scx-widget .scx-popup-online .scx-current-op .scx-meta {
					padding-right: \$padding+px;
				}
				div#scx-widget .scx-popup-online .scx-current-op-avatar {
					left: \$padding+px;
				}
			"
		),

		/*array(
			'id' 		=> 'delay',
			'name' 		=> __( 'Delay', 'chatx' ),
			'placeholder'=> 'Padding',
			'unit' 		=> 'seconds',
			'default' 	=> 2,
			'min' 		=> 0,
			'max' 		=> 30,
			'type' 		=> 'number'
		),*/

		/*array(
			'id' 		=> 'anim',
			'name' 		=> __( 'Animation', 'chatx' ),
			'options' 	=> array(
				'na' 		=> __( 'None', 'chatx' ),
				'slide' 	=> 'Slide in',
				'fade' 		=> 'Fade In',
				'bounce' 	=> 'Bounce In',
				'jelly' 	=> 'Jelly'
			),
			'desc' 		=> '',
			'default'	=> 'init',
			'type' 		=> 'select'
		),*/

		array(
			'id' => 'avatar-size',
			'name' => __( 'Avatar size', 'chatx' ),
			'default' => 30,
			'unit' => 'px',
			'min' => 10,
			'max' => 70,
			'type' => 'number'
		),

		array(
			'id' => 'avatar-radius',
			'name' => __( 'Avatar radius', 'chatx' ),
			'desc' => __( 'If avatar size and radius values are the same, the avatar will be circle', 'chatx' ),
			'default' => 30,
			'unit' => 'px',
			'min' => 10,
			'max' => 70,
			'type' => 'number'
		),

		array(
			'id' 		=> 'shadow',
			'name' 		=> __( 'Shadow', 'chatx' ),
			'type' 		=> 'radio',
			'options' 	=> array(
				'none' 	=> __( 'No shadow', 'chatx' ),
				'0 3px 8px 0 ' => __( 'Key light', 'chatx' ),
				'0 0 18px 0' => __( 'Ambient light', 'chatx' ),
				'5px 4px 18px 0' => __( 'Area light', 'chatx' )
			),
			'default' 	=> 'none'
		),

		array(
			'id' => 'shadow-opacity',
			'desc' => __( 'Shadow opacity', 'chatx' ),
			'default' => 15,
			'unit' => '%',
			'min' => 0,
			'max' => 30,
			'type' => 'number',
			'css' => "
				div#scx-widget .scx-chat-btn, 
				div#scx-widget .scx-popup {
					-webkit-box-shadow: \$shadow rgba( 0, 0, 0, \$shadow-opacity/100 );
					box-shadow: \$shadow rgba( 0, 0, 0, \$shadow-opacity/100 );
				}
			"
		),


		array(
			'id' => 'font',
			'name' => __( 'Typography', 'chatx' ),
			'desc' => __( 'Select your default font style', 'chatx' ),
			'show_font_family' => true,
			'show_font_size' => true,
			'show_font_weight' => true,
			'show_line_height' => true,
			'show_color' => true,
			'show_font_style' => false,
			'show_letter_spacing' => false,
			'show_text_transform' => false,
			'show_font_variant' => false,
			'show_text_shadow' => false,
			'show_preview' => false,
			'default' => array(
				'color' => '#232323',
				'font-family' => 'inherit',
				'line-height' => '1.3em',
				'font-size' => '16px',
				'font-weight' => 'normal'
			),
			'type' => 'font',
			'css' => "
				div#scx-widget .scx-chat-btn, 
				div#scx-widget .scx-popup {
						font-size: (\$font-font-size);
						font-family: \$font-font-family;
						font-weight: \$font-font-weight;
				}
				div#scx-widget .scx-popup {
					color: \$font-color;
					line-height: \$font-line-height;
					border-radius: \$rad_tl \$rad_tr \$rad_br \$rad_bl;
				}
				div#scx-widget .scx-popup .scx-content {
					padding: \$padding+px;
					border-radius: \$rad_tl \$rad_tr \$rad_br \$rad_bl;
				}

				div#scx-widget input[type=\"email\"],
				div#scx-widget input[type=\"number\"],
				div#scx-widget input[type=\"search\"],
				div#scx-widget input[type=\"text\"],
				div#scx-widget input[type=\"tel\"],
				div#scx-widget input[type=\"url\"],
				div#scx-widget input[type=\"password\"],
				div#scx-widget textarea,
				div#scx-widget select {
					font-size: \$font-size;
					font-family: \$font-font-family;
					border-radius: \$radius+px;
					border-color: \$border-color;
				}

				div#scx-widget input[type=\"email\"]:focus,
				div#scx-widget input[type=\"number\"]:focus,
				div#scx-widget input[type=\"search\"]:focus,
				div#scx-widget input[type=\"text\"]:focus,
				div#scx-widget input[type=\"tel\"]:focus,
				div#scx-widget input[type=\"url\"]:focus,
				div#scx-widget input[type=\"password\"]:focus,
				div#scx-widget textarea:focus,
				div#scx-widget select:focus {
					border-color: darken( \$border-color, 10% );
				}

				div#scx-widget input[type=\"email\"]:disabled,
				div#scx-widget input[type=\"number\"]:disabled,
				div#scx-widget input[type=\"search\"]:disabled,
				div#scx-widget input[type=\"text\"]:disabled,
				div#scx-widget input[type=\"tel\"]:disabled,
				div#scx-widget input[type=\"url\"]:disabled,
				div#scx-widget input[type=\"password\"]:disabled,
				div#scx-widget textarea:disabled,
				div#scx-widget select:disabled {
					border-color: lighten( \$border-color, 7% );
				}

				div#scx-widget p { line-height: \$font-line-height; }

				div#scx-widget .scx-button {
					color: \$font-color;
					background-color: \$bg-color;
					border-radius: \$radius+px;
					border: 1px solid \$border-color;
					font-size: \$font-font-size;
					font-weight: \$font-font-weight;
				}

				div#scx-widget .scx-button:hover {
					color: lighten( \$font-color, 10% );
					border-color: darken( \$border-color, 10% );
				}

				div#scx-widget .scx-button.scx-small {
					font-size: \$font-font-size-2;
				}

				div#scx-widget .scx-button.scx-primary {
					color: \$primary-fg-color;
					background-color: \$link-color;
					border-color: \$link-color;
				}
				div#scx-widget .scx-button.scx-primary:hover {
					color: \$link-color;
					background-color: \$bg-color;
					border-color: \$link-color;
				}

				div#scx-widget .scx-button.scx-disabled,
				div#scx-widget .scx-button.scx-disabled:hover {
					color: lighten( \$font-color, 10% );
					border-color: \$border-color;
					background-color: \$bg-color;
				}

				div#scx-widget .scx-popup-online .scx-reply-box {
					border-color: lighten( \$border-color, 7% );
					border-radius: 0 0 \$radius+px \$radius+px;
				}

				div#scx-widget .scx-popup-online textarea.scx-reply {
					border-color: lighten( \$border-color, 7% );
				}

				div#scx-widget ul.scx-social li a,
				div#scx-widget a.scx-logo { 
					color: lighten( \$font-color, 50% ); 
				}
				div#scx-widget ul.scx-social li a:hover,
				div#scx-widget a.scx-logo:hover { 
					color: \$font-color; 
				}
				"
		),

		array(
			'id' 			=> 'whitelabel',
			'name' 			=> __( 'White label', 'chatx' ),
			'desc' 			=> __( 'Show Screets logo', 'chatx' ) . fn_scx_admin_desc( __( 'It will help us to increase the plugin popularity and create better community. A good community brings lots of benefits like extensions, new ideas and improvements.', 'chatx' ), true ),
			'default' 		=> true,
			'type' 			=> 'checkbox'
		),

		/* Chat button */
		array(
			'name' => __( 'Chat button', 'chatx' ),
			'type' => 'heading'
		),
		array(
			'desc' => 'Shortcode: <code>[cx-button]Live chat[/cx-button]</code><br>Custom HTML: <code>' . htmlspecialchars( '<a href="javascript:void(0);" class="scx-shortcode-chat-btn">Live Chat</a>' ) . '</code>',
			'type' => 'note'
		),

		array(
			'id' 			=> 'btn-active',
			'name' 			=> __( 'Activate', 'chatx' ),
			'default' 		=> true,
			'enabled' 		=> __( 'Yes', 'chatx' ),
			'disabled' 		=> __( 'No', 'chatx' ),
			'type' 			=> 'enable'
		),

		array(
			'id' => 'btn-title-online',
			'name' => __( 'Button title', 'chatx' ) . ' (' . __( 'Online', 'chatx' ) . ')',
			'placeholder' => __( 'Button title', 'chatx' ) . ' (' . __( 'Online', 'chatx' ) . ')',
			'default' => "We're online!",
			'translate' => true,
			'type' => 'text'
		),

		array(
			'id' => 'btn-title-offline',
			'name' => __( 'Button title', 'chatx' ) . ' (' . __( 'Offline', 'chatx' ) . ')',
			'placeholder' => __( 'Button title', 'chatx' ) . ' (' . __( 'Offline', 'chatx' ) . ')',
			'default' => 'Send a message',
			'translate' => true,
			'type' => 'text'
		),

		array(
			'id' => 'btn-size',
			'name' => __( 'Button size', 'chatx' ),
			'desc' => __( '0 (zero): No fixed size', 'chatx' ),
			'default' => 0,
			'unit' => 'px',
			'min' => 0,
			'max' => 300,
			'type' => 'number',
			'css' => ".scx-chat-btn { \$w: if( \$btn-size > 0, \$btn-size+px, inherit ); width: \$w; }"
		),

		/*array(
			'id' => 'grabber',
			'name' => '<span class="dashicons dashicons-money"></span> ' . __( 'Attention grabber', 'chatx' ),
			'desc' => __( "Add an image to your chat box to draw the visitor's eye to your live chat service - using your own logo or a photo", 'chatx' ),
			'type' => 'upload'
		),

		array(
			'id' => 'grabber-2x',
			'desc' => __( 'Retina version', 'chatx' ),
			'type' => 'upload'
		),

		array(
			'id' 		=> 'grabber-offset-x',
			'desc'		=> __( 'Horizontal offset', 'chatx' ),
			'unit' 		=> 'px',
			'default' 	=> 10,
			'max' 		=> -100,
			'max' 		=> 100,
			'type' 		=> 'number'
		),

		array(
			'id' 		=> 'grabber-offset-y',
			'desc'		=> __( 'Vertical offset', 'chatx' ),
			'unit' 		=> 'px',
			'default' 	=> 0,
			'max' 		=> -100,
			'max' 		=> 100,
			'type' 		=> 'number'
		),

		array(
			'id' 		=> 'grabber-hide',
			'desc'		=> __( 'Hide when all operators offline', 'chatx' ),
			'type' 		=> 'checkbox'
		),*/
		
		);

	//
	// Popups
	//
	$opts['popups'] = array(

		/* Pre-chat popup */
		array(
			'name' => __( 'Pre-chat popup', 'chatx' ),
			'type' => 'heading'
		),

		array(
			'id' 			=> 'prechat-active',
			'name' 			=> __( 'Activate', 'chatx' ),
			'default' 		=> true,
			'enabled' 		=> __( 'Yes', 'chatx' ),
			'disabled' 		=> __( 'No', 'chatx' ),
			'type' 			=> 'enable'
		),

		array(
			'id' 			=> 'prechat-title',
			'name' 			=> __( 'Title', 'chatx' ),
			'placeholder' 	=> __( 'Title', 'chatx' ),
			'default' 		=> 'Login now',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'prechat-greeting',
			'name' 			=> __( 'Greeting', 'chatx' ),
			'placeholder' 	=> __( 'Greeting', 'chatx' ),
			'default' 		=> "<strong>Need more help?</strong> Save time by starting your support request online.",
			'translate' 	=> true,
			'editor_settings' => $editor_settings,
			'type' 			=> 'editor'
		),

		array(
			'id' 			=> 'prechat-fields',
			'name' 			=> __( 'Form fields', 'chatx' ),
			'options' 		=>  array(
				'name' => 'Your name',
				'email' => 'Email',
				'phone' => 'Phone',
				'question' => 'Describe your issue'
			),
			'default' 		=>  array( 'name', 'email', 'question' ),
			'visible_button' => true,
			'type' 			=> 'sortable'
		),

		array(
			'id' 			=> 'prechat-req-fields',
			'name' 			=> __( 'Required fields', 'chatx' ),
			'desc' 			=> __( 'Choose required fields', 'chatx' ),
			'options' 		=>  array(
				'name' => 'Your name',
				'email' => 'Email',
				'phone' => 'Phone',
				'question' => 'Describe your issue'
			),
			'multiple' => true,
			'default' 		=>  array( 'email', 'question' ),
			'type' 			=> 'multicheck'
		),

		array(
			'id' 			=> 'prechat-f-name',
			'name' 			=> __( 'Translate fields', 'chatx' ),
			'placeholder'	=>  'Your name',
			'default' 		=>  'Your name',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'prechat-f-email',
			'placeholder'	=>  'Email',
			'default' 		=>  'Email',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'prechat-f-phone',
			'placeholder'	=>  'Phone',
			'default' 		=>  'Phone',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'prechat-f-question',
			'placeholder'	=>  'Describe your issue',
			'default' 		=>  'Describe your issue',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'prechat-btn',
			'name' 			=> 'Button text',
			'placeholder' 	=> 'Button text',
			'translate' 	=> true,
			'default' 		=> 'Login now',
			'translate' 	=> true,
			'type' 			=> 'text'
		),


		array(
			'id' 			=> 'prechat-footer',
			'name' 			=> __( 'Footer note', 'chatx' ),
			'default' 		=> "We'll connect you to an expert.",
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		/* Online popup */
		array(
			'name' => __( 'Online popup', 'chatx' ),
			'type' => 'heading'
		),

		array(
			'id' 			=> 'online-title',
			'name' 			=> __( 'Title', 'chatx' ),
			'placeholder' 	=> __( 'Title', 'chatx' ),
			'default' 		=> "We're online!",
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'welcome-msg',
			'name' 			=> __( 'Welcome message', 'chatx' ),
			'placeholder' 	=> __( 'Welcome message', 'chatx' ),
			'default' 		=> "Questions, issues or concerns? I'd love to help you!",
			'editor_settings' => $editor_settings,
			'translate' 	=> true,
			'type' 			=> 'editor'
		),

		array(
			'id' 			=> 'first-auto-reply',
			'name' 			=> __( 'First automatic reply', 'chatx' ),
			'placeholder' 	=> __( 'First automatic reply', 'chatx' ),
			'default' 		=> 'Please wait, an operator will be with you shortly.',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' => 'str-reply-ph',
			'name' => __( 'Reply box placeholder', 'chatx' ),
			'placeholder' => __( 'Reply box placeholder', 'chatx' ),
			'default' => 'Your message',
			'translate' 	=> true,
			'type' => 'text'
		),

		array(
			'id' => 'str-reply-send',
			'name' => __( 'Send button', 'chatx' ),
			'placeholder' => __( 'Send', 'chatx' ),
			'default' => 'Send',
			'translate' 	=> true,
			'type' => 'text'
		),

		/* Offline popup */
		array(
			'name' => __( 'Offline popup', 'chatx' ),
			'type' => 'heading'
		),

		array(
			'id' 			=> 'offline-title',
			'name' 			=> __( 'Title', 'chatx' ),
			'placeholder' 	=> __( 'Title', 'chatx' ),
			'default' 		=> 'Send a message',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'offline-greeting',
			'name' 			=> __( 'Greeting', 'chatx' ),
			'placeholder' 	=> __( 'Greeting', 'chatx' ),
			'default' 		=> "Sorry, we aren't online at the moment. Leave a message.",
			'editor_settings' => $editor_settings,
			'translate' 	=> true,
			'type' 			=> 'editor'
		),

		array(
			'id' 			=> 'offline-fields',
			'name' 			=> __( 'Form fields', 'chatx' ),
			'options' 		=>  array(
				'name' => 'Your name',
				'email' => 'Email',
				'phone' => 'Phone',
				'subject' => 'Subject',
				'question' => 'Describe your issue'
			),
			'default' 		=>  array( 'name', 'email', 'question' ),
			'visible_button' => true,
			'type' 			=> 'sortable'
		),

		array(
			'id' 			=> 'offline-req-fields',
			'name' 			=> __( 'Required fields', 'chatx' ),
			'desc' 			=> __( 'Choose required fields', 'chatx' ),
			'options' 		=>  array(
				'name' => 'Your name',
				'email' => 'Email',
				'phone' => 'Phone',
				'subject' => 'Subject',
				'question' => 'Describe your issue'
			),
			'multiple' => true,
			'default' 		=>  array( 'email', 'question' ),
			'type' 			=> 'multicheck'
		),

		array(
			'id' 			=> 'offline-f-name',
			'name' 			=> __( 'Translate fields', 'chatx' ),
			'placeholder'	=>  'Your name',
			'default' 		=>  'Your name',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'offline-f-email',
			'placeholder'	=>  'Email',
			'default' 		=>  'Email',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'offline-f-phone',
			'placeholder'	=>  'Phone',
			'default' 		=>  'Phone',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'offline-f-subject',
			'placeholder'	=>  'Subject',
			'default' 		=>  'Subject',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'offline-f-question',
			'placeholder'	=>  'Describe your issue',
			'default' 		=>  'Describe your issue',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'offline-btn',
			'name' 			=> __( 'Button text', 'chatx' ),
			'placeholder' 	=> __( 'Button text', 'chatx' ),
			'default' 		=> 'Send message',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'offline-footer',
			'name' 			=> __( 'Footer note', 'chatx' ),
			'default' 		=> "We'll get back to you as soon as possible.",
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'offline-msg-sent',
			'name' 			=> __( 'Success message', 'chatx' ),
			'default' 		=> "Successfully sent! We will get back to you soon",
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'offline-social-links',
			'name' 			=> __( 'Social links', 'chatx' ),
			'default' 		=> true,
			'enabled' 		=> __( 'Show', 'chatx' ),
			'disabled' 		=> __( 'Hide', 'chatx' ),
			'type' 			=> 'enable'
		),

		/* Post-chat popup */
		array(
			'name' => __( 'Post-chat popup', 'chatx' ),
			'type' => 'heading'
		),

		array(
			'id' 			=> 'postchat-active',
			'name' 			=> __( 'Activate', 'chatx' ),
			'default' 		=> true,
			'enabled' 		=> __( 'Yes', 'chatx' ),
			'disabled' 		=> __( 'No', 'chatx' ),
			'type' 			=> 'enable'
		),

		array(
			'id' 			=> 'postchat-title',
			'name' 			=> __( 'Title', 'chatx' ),
			'placeholder' 	=> __( 'Title', 'chatx' ),
			'default' 		=> 'Feedback',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'postchat-greeting',
			'name' 			=> __( 'Greeting', 'chatx' ),
			'placeholder' 	=> __( 'Greeting', 'chatx' ),
			'default' 		=> "Help us help you better! Feel free to leave us any additional feedback.",
			'editor_settings' => $editor_settings,
			'translate' 	=> true,
			'type' 			=> 'editor'
		),

		array(
			'id' 			=> 'poschat-feedback-title',
			'name' 			=> __( 'Feedback', 'chatx' ),
			'placeholder' 	=> __( 'Rating title', 'chatx' ),
			'desc' 			=> __( 'Rating title', 'chatx' ),
			'default' 		=> 'How do you rate our support?',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'poschat-feedback-like',
			'placeholder' 	=> __( 'Like button', 'chatx' ),
			'desc' 			=> __( 'Like button', 'chatx' ),
			'default' 		=> 'Solved',
			'translate' 	=> true,
			'unit' 			=> '<i class="scx-ico-like"></i>',
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'poschat-feedback-dislike',
			'placeholder' 	=> __( 'Dislike button', 'chatx' ),
			'desc' 			=> __( 'Dislike button', 'chatx' ),
			'default' 		=> 'Not solved',
			'translate' 	=> true,
			'unit' 			=> '<i class="scx-ico-dislike"></i>',
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'poschat-feedback-saved',
			'placeholder' 	=> __( 'After voting message', 'chatx' ),
			'desc' 			=> __( 'After voting message', 'chatx' ),
			'default' 		=> 'Saved. Thank you!',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'poschat-f-email',
			'placeholder' 	=> __( 'Email field', 'chatx' ),
			'desc' 			=> __( 'Email field', 'chatx' ),
			'default' 		=> 'Your email',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'postchat-btn-email',
			'name' 			=> __( 'Buttons', 'chatx' ),
			'placeholder' 	=> 'Email chat history',
			'default' 		=> 'Email chat history',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'postchat-btn-send',
			'placeholder' 	=> 'Send',
			'default' 		=> 'Send',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'postchat-btn-done',
			'placeholder' 	=> 'Done',
			'default' 		=> 'Done',
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'postchat-social-links',
			'name' 			=> __( 'Social links', 'chatx' ),
			'default' 		=> true,
			'enabled' 		=> __( 'Show', 'chatx' ),
			'disabled' 		=> __( 'Hide', 'chatx' ),
			'type' 			=> 'enable'
		),
		

		/* Support categories popup */
		/*array(
			'name' => __( 'Support Categories', 'chatx' ),
			'type' => 'heading'
		),

		array(
			'id' 			=> 'cats-active',
			'name' 			=> __( 'Activate', 'chatx' ),
			'default' 		=> true,
			'enabled' 		=> __( 'Yes', 'chatx' ),
			'disabled' 		=> __( 'No', 'chatx' ),
			'type' 			=> 'enable'
		),

		array(
			'id' 			=> 'cats-title',
			'name' 			=> __( 'Title', 'chatx' ),
			'placeholder' 	=> __( 'Title', 'chatx' ),
			'default' 		=> __( "Get Help", 'chatx' ),
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'cats-greeting',
			'name' 			=> __( 'Greeting', 'chatx' ),
			'placeholder' 	=> __( 'Greeting', 'chatx' ),
			'default' 		=> __( "Choose a product and we’ll connect you to an expert by chat, email, and more.", 'chatx' ),
			'desc' 			=> __( 'HTML tags are allowed', 'chatx' ),
			'translate' 	=> true,
			'type' 			=> 'textarea'
		),

		array(
			'id' 			=> 'topics-title',
			'name' 			=> __( 'Title', 'chatx' ) . ' (' . __( 'Topics', 'chatx' ) . ')',
			'placeholder' 	=> __( 'Title', 'chatx' ) . ' (' . __( 'Topics', 'chatx' ) . ')',
			'default' 		=> __( "Choose a topic", 'chatx' ),
			'translate' 	=> true,
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'topics-greeting',
			'name' 			=> __( 'Greeting', 'chatx' ) . ' (' . __( 'Topics', 'chatx' ) . ')',
			'placeholder' 	=> __( 'Greeting', 'chatx' ) . ' (' . __( 'Topics', 'chatx' ) . ')',
			'default' 		=> __( "Choose a topic and we’ll find you the best option.", 'chatx' ),
			'desc' 			=> __( 'HTML tags are allowed', 'chatx' ),
			'translate' 	=> true,
			'type' 			=> 'text'
		)*/



	);

	//
	// Templates
	//
	$opts['templates'] = array(

		array(
			'id' 			=> 'prechat-fields',
			'name' 			=> __( 'Pre-chat form fields', 'chatx' ),
			'lang' 			=> 'json',
			'type' 			=> 'code',
			'default' 		=> "[
	{
		\"name\": \"name\",
		\"type\": \"text\",
		\"label\": \"Your name\",
		\"placeholder\": \"Your name\"
	},
	{
		\"name\": \"email\",
		\"type\": \"email\",
		\"label\": \"Email\",
		\"placeholder\": \"Email\",
		\"required\": true
	}
]", 
		),

		array(
			'id' 			=> 'offline-fields',
			'name' 			=> __( 'Offline form fields', 'chatx' ),
			'lang' 			=> 'json',
			'type' 			=> 'code',
			'default' 		=> "[
	{
		\"name\": \"name\",
		\"type\": \"text\",
		\"label\": \"Your name\",
		\"placeholder\": \"Your name\"
	},
	{
		\"name\": \"email\",
		\"type\": \"email\",
		\"label\": \"Email\",
		\"placeholder\": \"Email\",
		\"required\": true
	},
	{
		\"name\": \"question\",
		\"type\": \"textarea\",
		\"label\": \"Your question?\",
		\"placeholder\": \"Describe your issue\",
		\"required\": true
	},
	{
		\"name\": \"file\",
		\"type\": \"file\",
		\"label\": \"Send a file\"
	}
]"
		)

	);

	//
	// Users options
	//
	$opts['users'] = array(

		array(
			'id' 			=> 'guest-prefix',
			'name' 			=> __( 'Guest prefix', 'chatx' ) . '  <span class="scx-red">*</span>',
			'placeholder'	=> __( 'Guest prefix', 'chatx' ),
			'desc'			=> __( 'Visitors who didn\'t provide name or email will take guest name. For example, "Guest-1234"', 'chatx' ),
			'default'		=> 'Guest-',
			'suffix'		=> '-ID',
			'translate'		=> true,
			'type' 			=> 'text'
		),

		array(
			'name' => __( 'Operators', 'chatx' ),
			'type' => 'heading'
		),

		array(
			'desc' => '<a class="button" href="' . admin_url( 'users.php?role=cx_op' ) . '"><strong>' . __( 'List Operators', 'chatx' ) . '</strong></a> <a class="button" href="' . admin_url( 'user-new.php?role=cx_op' ) . '">' . __( 'Add New Operator', 'chatx' ) . '</a> ',
			'type' => 'note'
		),

		array(
			'id' 		=> 'op-caps',
			'name' 		=> '<span class="dashicons dashicons-admin-network"></span> ' . __( 'Operator capabilities', 'chatx' ) . fn_scx_admin_desc( sprintf( __( 'To add more capabilities to CX Operators, consider to use %s plugin or other good one', 'chatx' ), '<a href="https://wordpress.org/plugins/wpfront-user-role-editor/" target="_blank">WPFront User Role Editor</a> <span class="scx-ico-new-win"></span>' ), true ),
			'options' 	=> array(
				'answer_visitors' => __( 'Use chat console and answer visitors', 'chatx' ) . ' &nbsp; <small class="description">(scx_answer_visitor)</small>',
				'see_chat_logs' => __( 'See chat logs', 'chatx' ) . ' &nbsp; <small class="description">(scx_see_logs)</small>',
				'manage_chat_options' => __( 'Manage chat options', 'chatx' ) . ' &nbsp; <small class="description">(scx_manage_chat_options)</small>'
			),
			'default'	=> array( 'answer_visitors', 'see_chat_logs' ),
			'type' 		=> 'multicheck'
		)

	);

	//
	// Integrations options
	//
	$opts['integrations'] = array(

		array(
			'name' 			=> '<img src="' . SCX_URL . '/assets/img/logos/firebase.png" height="30" alt="" class="scx-logo"> Firebase',
			'type' 			=> 'heading'
		),

		array(
			'desc' 			=> '<div class="scx-firebase-ntf update-nag"><p>' . sprintf( __( 'Please create <strong class="scx-red">new Firebase application</strong> for %s. You can use your Firebase account (if you have already), but DO NOT use <em>your old application</em> even you created for %s 1.x', 'chatx' ), '<strong>Chat X ' . SCX_EDITION .'</strong>', 'Chat X' ) . '.',
			'type' 			=> 'note'
		),

		array(
			'id' 			=> 'app-id',
			'name' 			=> 'App ID  <span class="scx-red">*</span>',
			'desc'			=> __( 'Your application name', 'chatx' ) . '. <a href="https://www.firebase.com/signup/" target="_blank">' . sprintf( __( 'Create free %s account', 'chatx' ), 'Firebase' ) . '</a> <span class="scx-ico-new-win"></span>',
			'placeholder' 	=> __( 'App ID', 'chatx' ),
			'unit'			=> '.firebaseIO.com',
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'app-secret',
			'name' 			=> 'Secret  <span class="scx-red">*</span>',
			'placeholder'	=> 'Secret',
			'desc'			=> sprintf( __( '%s secret key can be found under "%s" menu in your %s dashboard', 'chatx' ), 'Firebase', 'SECRETS', 'Firebase' ),
			'type' 			=> 'text'
		),

		array(
			'name' 			=> 'Anonymous Login  <span class="scx-red">*</span>',
			'custom'		=> '<div class="scx-anonymous-auth">' . __( 'Checking', 'chatx' ) . '...</div>',
			'type' 			=> 'custom'
		),

		array(
			'id' 			=> 'debug',
			'name' 			=> __( 'Debug', 'chatx' ) . fn_scx_admin_desc( __( 'Activate debugging when there is an issue on the plugin', 'chatx' ), true ),
			'desc' 			=> __( 'Activate browser console debug', 'chatx' ),
			'enabled' 		=> __( 'Yes', 'chatx' ),
			'disabled' 		=> __( 'No', 'chatx' ),
			'type' 			=> 'enable'
		),

		/*array(
			'name' 			=> '<img src="' . SCX_URL . '/assets/img/logos/google-maps.png" height="30" alt="" class="scx-logo"> Google Maps',
			'type' 			=> 'heading'
		),

		array(
			'id' 			=> 'gm-api',
			'name' 			=> 'Google Maps API Key' . fn_scx_admin_desc( __( 'Helps you to see user locations on map in chat console', 'chatx' ), true ),
			'placeholder'	=> 'API Key',
			'desc'			=> '<a href="https://developers.google.com/maps/documentation/javascript/tutorial#api_key" target="_blank">' . __( 'Get your API key', 'chatx' ) . '</a> <span class="scx-ico-new-win"></span>',
			'type' 			=> 'text'
		),*/

		array(
			'name' 			=> '<img src="' . SCX_URL . '/assets/img/logos/pushover.png" height="30" alt="" class="scx-logo"> Pushover',
			'type' 			=> 'heading'
		),

		array(
			'id' 			=> 'pushover-active',
			'name' 			=> __( 'Enable', 'chatx' ) . fn_scx_admin_desc( __( 'Install Pushover to your mobile device or desktop (Mac or PC) and receive notifications from the plugin', 'chatx' ) . '<br><a href="http://pushover.net" target="_blank">http://pushover.net</a> <span class="scx-ico-new-win"></span>', true ),
			'desc' 			=> __( 'Enable this application', 'chatx' ),
			'enabled' 		=> __( 'Yes', 'chatx' ),
			'disabled' 		=> __( 'No', 'chatx' ),
			'type' 			=> 'enable'
		),

		array(
			'desc' 			=> __( 'Use that image below as "App Icon" when creating your Pushover application:<br><a href="' . SCX_URL . '/assets/img/logos/chatx-logo-72px.png" target="_blank"><img src="' . SCX_URL . '/assets/img/logos/chatx-logo-72px.png" height="30" alt=""></a>', 'chatx' ),
			'type' 			=> 'note'
		),

		array(
			'id' 			=> 'pushover-user-key',
			'name' 			=> __( 'User key', 'chatx' ),
			'placeholder' 	=> __( 'User key', 'chatx' ),
			'desc' 			=> '<a href="https://pushover.net/apps/build" target="_blank">' . __( 'Create an application and get your keys', 'chatx' ) . '</a> <span class="scx-ico-new-win"></span><br>User key is viewable when logged into <a href="https://pushover.net/dashboard" target="_blank">Pushover dashboard</a> <span class="scx-ico-new-win"></span>',
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'pushover-api-token',
			'name' 			=> 'API Token/Key',
			'desc' 			=> __( 'After creating application, you will see the key', 'chatx' ),
			'placeholder' 	=> 'API token/key',
			'type' 			=> 'text'
		)

		/*array(
			'id' 			=> 'pushover-notify',
			'name' 			=> __( 'Send notification', 'chatx' ),
			'options' 		=> array(
				'new-msg' => __( 'When new message received', 'chatx' ),
				'new-user' => __( 'When new user is online', 'chatx' ),
				'new-offline' => __( 'When new offline message received', 'chatx' )
			),
			'default' 		=> array( 'new-msg', 'new-user' ),
			'type' 			=> 'multicheck'
		)*/


	);

	//
	// Advanced options
	//
	$opts['advanced'] = array(

		array(
			'id' 			=> 'proxy-ips',
			'name' 			=> __( 'Reverse proxy IPs', 'chatx' ),
			'placeholder'	=> __( 'Reverse proxy IPs', 'chatx' ),
			'desc'			=> __( "If your server is behind a reverse proxy, you must whitelist the proxy IP addresses from which WordPress should trust the HTTP_X_FORWARDED_FOR header in order to properly identify the visitor's IP address. Comma-delimited, e.g. '10.0.1.200,10.0.1.201'", 'chatx' ),
			'type' 			=> 'text'
		),

		array(
			'id' 			=> 'custom-css',
			'name' 			=> __( 'Custom CSS', 'chatx' ),
			'placeholder'	=> __( 'Custom CSS', 'chatx' ),
			'lang' 			=> 'css',
			'type' 			=> 'code'
		)

	);

	//
	// Support options
	//
	$opts['support'] = array();

	return apply_filters( 'scx_admin_opts', $opts );

}