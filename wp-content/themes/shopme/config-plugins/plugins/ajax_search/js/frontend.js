/**
 * frontend.js
 *
 * @author Your Inspiration Themes
 * @package YITH WooCommerce Ajax Search
 * @version 1.1.1
 */

jQuery(document).ready(function ($) {

	"use strict";

    var el = $('.yith-s'),
		def_loader = ( typeof woocommerce_params != 'undefined' && typeof woocommerce_params.ajax_loader_url != 'undefined' ) ? woocommerce_params.ajax_loader_url : yith_wcas_params.loading,
		loader_icon = el.data('loader-icon') == '' ? def_loader : el.data('loader-icon'),
        search_button = $('#yith-searchsubmit'),
        min_chars = el.data('min-chars');

    search_button.on('click', function () {
        var form = $(this).closest('form');
        if( form.find('.yith-s').val()==''){
            return false;
        }
        return true;
    });

    if( el.length == 0 ) el = $('#yith-s');

    el.each(function () {
        var $t = $(this),
            append_to = ( typeof  $t.data('append-to') == 'undefined') ? $t.closest('.yith-ajaxsearchform-container') : $t.data('append-to');

		var prefix = '?';
        if (typeof icl_vars != 'undefined') { prefix = '&'; }

        $t.autocomplete({
            minChars: min_chars,
            appendTo: append_to,
            serviceUrl: yith_wcas_params.ajax_url + prefix + 'action=yith_ajax_search_products',
            onSearchStart: function () {
                $t.css('background-image', 'url(' + loader_icon + ')');
            },
            onSearchComplete: function () {
				$t.css('background-image', 'none');
			},
            onSelect: function (suggestion) {
                if( suggestion.id != -1 ) {
                    window.location.href = suggestion.url;
                }
            },
            zIndex: 250,
            maxHeight: 'auto'
        });

    });

});


