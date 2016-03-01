/*!
 * Screets Chat X - Chat options scripts
 * Author: @screetscom
 *
 * COPYRIGHT © 2016 Screets d.o.o. All rights reserved.
 * This  is  commercial  software,  only  users  who have purchased a valid
 * license  and  accept  to the terms of the  License Agreement can install
 * and use this program.
 */

(function ($) {
	$(document).ready(function () {

		// Add "https://" prefix to app id
		$( 'input#screets-cx_app-id' ).before( 'https:// ' );

		// Move notification to top
		$( '.updated.top' ).prependTo( '#wpbody .wrap' );
		$( '.updated.top' ).delay(3000).fadeOut();

		//
		// UserVoice JavaScript SDK (only needed once on a page)
		//
		var uv=document.createElement('script');
		uv.type='text/javascript';
		uv.async=true;
		uv.src='//widget.uservoice.com/qpWOCBts6rHFkFjDiqkI8Q.js';
		var s=document.getElementsByTagName('script')[0];
		s.parentNode.insertBefore(uv,s);

		// UserVoice options
		UserVoice = window.UserVoice || [];
		UserVoice.push(['showTab', 'classic_widget', {
			mode: 'feedback',
			primary_color: '#999',
			link_color: '#3cc382',
			forum_id: 219170,
			feedback_tab_name: 'Chat X Ideas',
			tab_label: 'Ideas',
			tab_color: '#e54045',
			tab_position: 'middle-right',
			tab_inverted: false
		}]);


		// 
		// Validate options
		// 
		if( scx_admin_opts ) {
			var opt_v = '',
				tab_index = '',
				err = null;


			for( var opt_id in scx_admin_opts ) {
				tab_index = '';
				opt_v = scx_admin_opts[opt_id];
				err = null;

				if( opt_v.length == 0 ) {
					err = true;
				}

				switch( opt_id ){

					// General tab
					case 'api-key':

						// tab_index = 1;
						break;

					// Integrations tab
					case 'app-id':

						if( opt_v.indexOf('https://') > -1 || opt_v.indexOf('http://') > -1 ) {
							err = true;
							$( '#screets-cx_' + opt_id ).parent().append( '<p class="scx-red"><span class="dashicons dashicons-warning"></span>  Don\'t include <strong>https://</strong> part in App ID</p>' );
						}

						tab_index = 6;

						break;
						
					case 'app-secret':
						tab_index = 6;
						break;

					// Site info tab
					case 'site-name':
					case 'site-url':
					case 'site-email':
					case 'site-reply-to':

						tab_index = 2;

						break;

					// Users tab
					case 'guest-prefix':

						tab_index = 5;

						break;
				}

				if( err ) {
					$( '#screets-cx_' + opt_id ).parent().parent().addClass( 'scx-error' );
					
					// Highlight tab button
					if( tab_index ) {
						$('.nav-tab-wrapper a:nth-child(' + tab_index + ')').addClass('scx-error');
					}
				}

			}

			var $firebase_ntf = $('.scx-firebase-ntf').parent().parent();
			$firebase_ntf.hide();

			if( scx_admin_opts['app-id'] ) {

				var ref = new Firebase( 'https://' + scx_admin_opts['app-id'] + '.firebaseIO.com/' );;

				ref.authAnonymously( function( err, auth ) {
					// "Anonymous" login isn't activated
					if( err ) {
						$('.scx-anonymous-auth').html( '<span class="scx-red"><span class="dashicons dashicons-warning"></span> Inactive! Please go to <strong>"Login & Auth"</strong> menu and enable <strong>"Anonymous"</strong> tab in your Firebase dashboard.</span>' );

						// Show Firebase notification
						$firebase_ntf.show();
					} else {
						$('.scx-anonymous-auth').html( '<span class="scx-green"><span class="dashicons dashicons-yes"></span> Active</span>' );
					}
				});

			} else {
				$('.scx-anonymous-auth').html( '<span class="scx-grey">Waiting for you to create your Firebase application.</span>' );
				
				// Show Firebase notification
				$firebase_ntf.show();
			}
			
		}

		// 
		// Show specific pages & categories
		// 
		$('#scx-btn-specific-pages').click( function(e) {
			e.preventDefault();

			var status = ( $(this).data('status') == 'open' ) ? '' : 'open';

			var $pages = $(this).parent().parent().parent().next();
			var $cats = $pages.next();

			$pages.addClass('scx-specific-pages');
			$cats.addClass('scx-specific-cats');

			if( status ) {
				$pages.fadeIn(400);
				$cats.fadeIn(400);
				
			} else {
				$pages.fadeOut(400);
				$cats.fadeOut(400);
			}
			
			$(this).data( 'status', status );

		});

		//
		// Update options
		//
		function cx_update_opts() {

			var opt_specific_role = $('#screets-cx_visibilitycustom-wp-user'),
				list_specific_roles = opt_specific_role.closest('tr').next(),
				form_fields = $('select[name^=screets-cx_field]').closest('tr');

			// Show/hide specific user roles list
			if( opt_specific_role.prop('checked') )
				list_specific_roles.fadeIn(500);
			else
				list_specific_roles.hide();

			// Make some option rows as extra option
			list_specific_roles.addClass('cx-xtra-opt');
			form_fields.next().addClass('cx-xtra-opt').next().addClass('cx-xtra-opt').next().addClass('cx-xtra-opt');

		}

		// Listen changes
		$('.options-container input, .options-container select, .options-container textarea' ).on('change keyup blur', function() {
			cx_update_opts();
		});

		cx_update_opts();



	});
} (window.jQuery || window.Zepto));