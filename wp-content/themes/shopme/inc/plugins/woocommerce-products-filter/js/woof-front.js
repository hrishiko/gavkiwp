
(function ($) {

	$(function () {

		$('body').on('submit', '.widget_price_filter form', function () {
			var min_price = $(this).find('.price_slider_amount #min_price').val();
			var max_price = $(this).find('.price_slider_amount #max_price').val();
			shopme_woof_current_values.min_price = min_price;
			shopme_woof_current_values.max_price = max_price;
			shopme_woof_ajax_page_num = 1;
			shopme_woof_submit_link(shopme_woof_get_submit_link());
			return false;
		});

		$('body').on('price_slider_change', function (event, min, max) {
			var min_price = $(this).find('.price_slider_amount #min_price').val();
			var max_price = $(this).find('.price_slider_amount #max_price').val();
			shopme_woof_current_values.min_price = min_price;
			shopme_woof_current_values.max_price = max_price;
		});

		shopme_woof_remove_empty_elements();
		shopme_woof_init_search_form();
		shopme_woof_init_pagination();
		shopme_woof_init_orderby();
		shopme_woof_init_reset_button();
		shopme_woof_shortcode_observer();

	});

})(jQuery);

function shopme_woof_init_reset_button() {

	jQuery('body').on('click', '.woof_reset_search_form', function () {

		shopme_woof_ajax_page_num = 1;

		if (shopme_woof_is_permalink) {
			shopme_woof_current_values = {};
			shopme_woof_submit_link(shopme_woof_get_submit_link().split("page/")[0]);
		} else {

			var link = shopme_woof_shop_page;

			if (shopme_woof_current_values.hasOwnProperty('page_id')) {
				link = location.protocol + '//' + location.host + "/?page_id=" + shopme_woof_current_values.page_id;
				shopme_woof_current_values = { 'page_id': shopme_woof_current_values.page_id };
				shopme_woof_get_submit_link();
			}

			shopme_woof_submit_link(link);

			history.pushState({}, "", link);
			if (shopme_woof_current_values.hasOwnProperty('page_id')) {
				shopme_woof_current_values = {'page_id': shopme_woof_current_values.page_id};
			} else {
				shopme_woof_current_values = {};
			}
		}
		return false;

	});

}

function shopme_woof_init_pagination() {

	jQuery('body').on('click', '.woocommerce-pagination ul.page-numbers a.page-numbers', function () {

		var l = jQuery(this).attr('href');

		if (shopme_woof_ajax_first_done) {
			var res = l.split("paged=");
			if (res[1] !== undefined) {
				shopme_woof_ajax_page_num = parseInt(res[1]);
			} else {
				shopme_woof_ajax_page_num = 1;
			}
		} else {
			var res = l.split("page/");

			if (res[1] !== undefined) {
				shopme_woof_ajax_page_num = parseInt(res[1]);
			} else {
				shopme_woof_ajax_page_num = 1;
			}
		}

		shopme_woof_submit_link(shopme_woof_get_submit_link());

		return false;
	});

}

function shopme_woof_init_orderby() {

	jQuery('.sort-param-order').on('click', 'a', function () {

		var $this = jQuery(this);
			$this.parent('li').siblings().children('a').removeClass("selected").end().end().end().addClass('selected');

		shopme_woof_current_values.orderby = $this.data('order');
		shopme_woof_ajax_page_num = 1;
		shopme_woof_submit_link(shopme_woof_get_submit_link());
		return false;
	});

	jQuery('.order-param-button').on('click', 'a', function () {

		var $this = jQuery(this),
			sort = $this.data('sort');

		if (sort == 'asc') {
			$this.removeClass('order-param-asc order-param-desc').addClass('order-param-desc');
			$this.data('sort', 'desc');
			shopme_woof_ajax_order = 'desc';
			shopme_woof_current_values.product_sort = 'desc';
		} else {
			$this.removeClass('order-param-asc order-param-desc').addClass('order-param-asc');
			$this.data('sort', 'asc');
			shopme_woof_ajax_order = 'asc';
			shopme_woof_current_values.product_sort = 'asc';
		}

		shopme_woof_ajax_page_num = 1;
		shopme_woof_submit_link(shopme_woof_get_submit_link());
		return false;
	});

	jQuery('.sort-param-count').on('click', 'a', function () {

		var $this = jQuery(this),
			count = $this.data('count');
		$this.parent('li').siblings().children('a').removeClass("selected").end().end().end().addClass('selected');

		shopme_woof_current_values.product_count = count;
		shopme_woof_ajax_per_page = count;

		shopme_woof_ajax_page_num = 1;
		shopme_woof_submit_link(shopme_woof_get_submit_link());
		return false;
	});

}

function shopme_woof_init_search_form() {

	shopme_woof_init_checkboxes();
	shopme_woof_init_colors();
	shopme_woof_init_labels();
	shopme_woof_init_selects();
	shopme_woof_init_sku();
	shopme_woof_init_text();

	var containers = jQuery('.woof_container');

	if (containers.length) {

		jQuery.each(containers, function (index, value) {

			var remove = false;

			if (jQuery(value).find('ul.woof_list_checkbox').size() === 1) {
				remove = true;
			}

			if (remove) {
				if (jQuery(value).find('ul.woof_list li').size() === 0) {
					jQuery(value).remove();
				}
			}

		});

	}

	jQuery('.woof_submit_search_form').click(function () {
		shopme_woof_submit_link(shopme_woof_get_submit_link());
		return false;
	});


	jQuery('ul.woof_childs_list').parent('li').addClass('woof_childs_list_li');

	shopme_woof_checkboxes_slide();

}

function shopme_woof_submit_link(link) {

	shopme_woof_ajax_first_done = true;

	var data = {
		action: "woof_draw_products",
		link: link,
		page: shopme_woof_ajax_page_num,
		per_page: shopme_woof_ajax_per_page,
		order: shopme_woof_ajax_order,
		shortcode: jQuery('#woof_results_by_ajax').data('shortcode'),
		woof_shortcode: jQuery('div.shopme-woof').data('shortcode')
	};

	jQuery.ajax({
		type: 'POST',
		dataType: 'json',
		url: shopme_woof_ajaxurl,
		data: data,
		beforeSend: function () {
			jQuery('.woof_shortcode_output').html('').addClass('shopme-woof-loading');
		},
		success: function (content) {

			jQuery('.woof_shortcode_output').html(jQuery(content.products));
			jQuery('div.woof_redraw_zone').replaceWith(jQuery(content.form).find('.woof_redraw_zone'));

			shopme_woof_remove_empty_elements();
			shopme_woof_init_search_form();

			jQuery.shopme_woocommerce_mod.raty();

			jQuery.each(jQuery('#woof_results_by_ajax'), function (index, item) {
				if (index == 0) { return; }

				jQuery(item).removeAttr('id');
			});

		},
		complete: function () {
			jQuery('.woof_shortcode_output').removeClass('shopme-woof-loading');
		}
	});

}

function shopme_woof_remove_empty_elements() {

	jQuery.each(jQuery('.woof_container select'), function (index, select) {
		var size = jQuery(select).find('option').size();
		if (size === 0) {
			jQuery(select).parents('.woof_container').remove();
		}
	});

	jQuery.each(jQuery('ul.woof_list_checkbox, ul.woof_list_color, ul.woof_list_label'), function (index, ch) {
		var size = jQuery(ch).find('li').size();
		if (size === 0) {
			jQuery(ch).parents('.woof_container').remove();
		}
	});

}



function shopme_woof_get_submit_link() {

	shopme_woof_current_values.page = shopme_woof_ajax_page_num;

	if (Object.keys(shopme_woof_current_values).length > 0) {
		jQuery.each(shopme_woof_current_values, function (index, value) {
			if (index == shopme_swoof_search_slug) {
				delete shopme_woof_current_values[index];
			}
			if (index == 's') {
				delete shopme_woof_current_values[index];
			}
			if (index == 'product') {
				//for single product page (when no permalinks)
				delete shopme_woof_current_values[index];
			}
			if (index == 'really_curr_tax') {
				delete shopme_woof_current_values[index];
			}
		});
	}

	if (Object.keys(shopme_woof_current_values).length === 2) {
		if (('min_price' in shopme_woof_current_values) && ('max_price' in shopme_woof_current_values)) {
			var l = shopme_woof_current_page_link + '?min_price=' + shopme_woof_current_values.min_price + '&max_price=' + shopme_woof_current_values.max_price;
			history.pushState({}, "", l);
			return l;
		}
	}

	if (Object.keys(shopme_woof_current_values).length === 0) {
		history.pushState({}, "", shopme_woof_current_page_link);
		return shopme_woof_current_page_link;
	}

	if (Object.keys(shopme_woof_really_curr_tax).length > 0) {
		shopme_woof_current_page_link['really_curr_tax'] = shopme_woof_really_curr_tax.term_id + '-' + shopme_woof_really_curr_tax.taxonomy;
	}

	var link = shopme_woof_current_page_link + "?" + shopme_swoof_search_slug + "=1";

	if (!shopme_woof_is_permalink) {

		link = location.protocol + '//' + location.host + "?" + shopme_swoof_search_slug + "=1";

		if (shopme_woof_current_values.hasOwnProperty('page_id')) {
			link = location.protocol + '//' + location.host + "?" + shopme_swoof_search_slug + "=1";
		}
	}

	var shopme_woof_exclude_accept_array = ['path'];

	if (Object.keys(shopme_woof_current_values).length > 0) {
		jQuery.each(shopme_woof_current_values, function (index, value) {
			if (index == 'page') {
				index = 'paged';
			}
			if (typeof value !== 'undefined') {
				if ((typeof value && value.length > 0) || typeof value == 'number') {
					if (jQuery.inArray(index, shopme_woof_exclude_accept_array) == -1) {
						link = link + "&" + index + "=" + value;
					}
				}
			}

		});
	}

	link = link.replace(new RegExp(/page\/(\d+)\//), "");
	history.pushState({}, "", link);

	return link;
}

function shopme_woof_shortcode_observer() {

	if (jQuery('.woof_shortcode_output').length) {
		shopme_woof_current_page_link = location.protocol + '//' + location.host + location.pathname;
	}
}

function shopme_woof_checkboxes_slide() {

	var childs = jQuery('ul.woof_childs_list');

		if (childs.length) {

			jQuery.each(childs, function (index, ul) {
				var span_class = 'woof_is_closed';
				if (jQuery(ul).find('input[type=checkbox]').is(':checked')) {
					jQuery(ul).slideDown(400);
					span_class = 'woof_is_opened';
				}

				jQuery(ul).before('<a href="javascript:void(0);" class="woof_childs_list_opener"><span class="' + span_class + '"></span></a>');
			});

			jQuery.each(jQuery('a.woof_childs_list_opener'), function (index, a) {
				jQuery(a).click(function () {
					var span = jQuery(this).find('span');
					if (span.hasClass('woof_is_closed')) {
						jQuery(this).parent().find('ul.woof_childs_list').first().stop(true, true).slideDown(400);
						span.removeClass('woof_is_closed');
						span.addClass('woof_is_opened');
					} else {
						jQuery(this).parent().find('ul.woof_childs_list').first().stop(true, true).slideUp(400);
						span.removeClass('woof_is_opened');
						span.addClass('woof_is_closed');
					}
					return false;
				});
			});

		}

}

/* Checkboxes Modification
/* --------------------------------------------- */

function shopme_woof_init_checkboxes() {

	jQuery('.woof_checkbox_term').on('change', function (event) {
		if (jQuery(this).is(':checked')) {
			jQuery(this).attr("checked", true);
			shopme_woof_checkbox_process_data(this, true);
		} else {
			jQuery(this).attr("checked", false);
			shopme_woof_checkbox_process_data(this, false);
		}
	});

}

function shopme_woof_checkbox_process_data(_this, is_checked) {
	var tax = jQuery(_this).data('tax'),
		name = jQuery(_this).attr('name');
	shopme_woof_checkbox_direct_search(name, tax, is_checked);
}

function shopme_woof_checkbox_direct_search(name, tax, is_checked) {

	var values = '';

	if (is_checked) {
		if (tax in shopme_woof_current_values) {
			shopme_woof_current_values[tax] = woof_current_values[tax] + ',' + name;
		} else {
			shopme_woof_current_values[tax] = name;
		}
		jQuery('.woof_checkbox_term[name=' + name + ']').attr('checked', true);
	} else {
		values = shopme_woof_current_values[tax];
		values = values.split(',');
		var tmp = [];
		jQuery.each(values, function (index, value) {
			if (value != name) {
				tmp.push(value);
			}
		});
		values = tmp;

		if (values.length) {
			shopme_woof_current_values[tax] = values.join(',');
		} else {
			delete shopme_woof_current_values[tax];
		}
		jQuery('.woof_checkbox_term[name=' + name + ']').attr('checked', false);
	}

	shopme_woof_ajax_page_num = 1;
	shopme_woof_submit_link(shopme_woof_get_submit_link());
}

/* Label Modification
/* --------------------------------------------- */


function shopme_woof_init_labels() {

	jQuery('.woof_label_term').each(function () {

		var span = jQuery('<span class="' + jQuery(this).attr('type') + ' ' + jQuery(this).attr('class') + '">' + jQuery(this).data('name') +'</span>').click(shopme_woof_label_do_check);

		if (jQuery(this).is(':checked')) {
			span.addClass('checked');
		}
		jQuery(this).wrap(span).hide();

	});

	function shopme_woof_label_do_check() {

		var is_checked = false;
		if (jQuery(this).hasClass('checked')) {
			jQuery(this).removeClass('checked');
			jQuery(this).children().prop("checked", false);
		} else {
			jQuery(this).addClass('checked');
			jQuery(this).children().prop("checked", true);
			is_checked = true;
		}

		shopme_woof_label_process_data(this, is_checked);

	}

}

function shopme_woof_label_process_data(_this, is_checked) {
	var tax = jQuery(_this).find('input[type=checkbox]').data('tax'),
		name = jQuery(_this).find('input[type=checkbox]').attr('name');
	shopme_woof_label_direct_search(name, tax, is_checked);
}

function shopme_woof_label_direct_search(name, tax, is_checked) {

	var values = '';

	if (is_checked) {
		if (tax in shopme_woof_current_values) {
			shopme_woof_current_values[tax] = shopme_woof_current_values[tax] + ',' + name;
		} else {
			shopme_woof_current_values[tax] = name;
		}
		jQuery('.woof_label_term[name=' + name + ']').attr('checked', true);
	} else {
		values = shopme_woof_current_values[tax];
		values = values.split(',');
		var tmp = [];
		jQuery.each(values, function (index, value) {
			if (value != name) {
				tmp.push(value);
			}
		});
		values = tmp;
		if (values.length) {
			shopme_woof_current_values[tax] = values.join(',');
		} else {
			delete shopme_woof_current_values[tax];
		}
		jQuery('.woof_label_term[name=' + name + ']').attr('checked', false);
	}

	shopme_woof_ajax_page_num = 1;
	shopme_woof_submit_link(shopme_woof_get_submit_link());
}

/* Colors Modification
/* --------------------------------------------- */

function shopme_woof_init_colors() {

	jQuery('.woof_color_term').each(function () {
		var span = jQuery('<span style="background-color:' + jQuery(this).data('color') + '" class="woof-color ' + jQuery(this).attr('type') + ' ' + jQuery(this).attr('class') + '"></span>').click(shopme_woof_color_do_check);

		jQuery(this).after(jQuery(this).data('name'));

		if (jQuery(this).is(':checked')) {
			span.addClass('checked');
		}
		jQuery(this).wrap(span).hide();
	});

	function shopme_woof_color_do_check() {
		var is_checked = false;
		if (jQuery(this).hasClass('checked')) {
			jQuery(this).removeClass('checked');
			jQuery(this).children().prop("checked", false);
		} else {
			jQuery(this).addClass('checked');
			jQuery(this).children().prop("checked", true);
			is_checked = true;
		}

		shopme_woof_color_process_data(this, is_checked);
	}

}

function shopme_woof_color_process_data(_this, is_checked) {
	var tax = jQuery(_this).find('input[type=checkbox]').data('tax');
	var name = jQuery(_this).find('input[type=checkbox]').attr('name');
	shopme_woof_color_direct_search(name, tax, is_checked);
}

function shopme_woof_color_direct_search(name, tax, is_checked) {

	var values = '';

	if (is_checked) {
		if (tax in shopme_woof_current_values) {
			shopme_woof_current_values[tax] = shopme_woof_current_values[tax] + ',' + name;
		} else {
			shopme_woof_current_values[tax] = name;
		}
		jQuery('.woof_color_term[name=' + name + ']').attr('checked', true);
	} else {
		values = shopme_woof_current_values[tax];
		values = values.split(',');
		var tmp = [];
		jQuery.each(values, function (index, value) {
			if (value != name) {
				tmp.push(value);
			}
		});
		values = tmp;
		if (values.length) {
			shopme_woof_current_values[tax] = values.join(',');
		} else {
			delete shopme_woof_current_values[tax];
		}
		jQuery('.woof_color_term[name=' + name + ']').attr('checked', false);
	}

	shopme_woof_ajax_page_num = 1;
	shopme_woof_submit_link(shopme_woof_get_submit_link());
}

/* Selects Modification
/* --------------------------------------------- */

function shopme_woof_init_selects() {

	if (jQuery('select.shopme_woof_select, select.woof_price_filter_dropdown').length) {
		jQuery("select.shopme_woof_select, select.woof_price_filter_dropdown")
			.chosen({disable_search_threshold: 10})
			.change(function () {
				var slug = jQuery(this).val();
				var name = jQuery(this).attr('name');
				shopme_woof_select_direct_search(name, slug);
			});
	}

}

function shopme_woof_select_direct_search(name, slug) {

	jQuery.each(shopme_woof_current_values, function (index, value) {
		if (index == name) {
			delete shopme_woof_current_values[name];
			return;
		}
	});

	if (slug != 0) {
		shopme_woof_current_values[name] = slug;
	}

	shopme_woof_ajax_page_num = 1;
	shopme_woof_submit_link(shopme_woof_get_submit_link());
}

function shopme_woof_init_sku() {

	jQuery('.shopme_woof_show_sku_search').on('keyup', function (e) {
		var val = jQuery(this).val();

		if (val.length > 3) {
			if (e.keyCode == 13) {
				shopme_woof_direct_search('shopme_woof_sku', val);
				return true;
			}
		}
	});

}

function shopme_woof_init_text() {

	jQuery('.shopme_woof_show_text_search').on('keyup', function (e) {
		var val = jQuery(this).val();

		if (val.length > 3) {
			if (e.keyCode == 13) {
				shopme_woof_direct_search('shopme_woof_text', val);
				return true;
			}
		}
	});

}

function shopme_woof_direct_search(name, slug) {

	jQuery.each(shopme_woof_current_values, function (index, value) {
		if (index == name) {
			delete shopme_woof_current_values[name];
			return;
		}
	});

	if (slug != 0) {
		shopme_woof_current_values[name] = slug;
	}

	shopme_woof_ajax_page_num = 1;
	shopme_woof_submit_link(shopme_woof_get_submit_link());
}



