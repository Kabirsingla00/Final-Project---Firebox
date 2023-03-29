/* global jQuery:false, elementor:false */

jQuery(document).ready(function() {
	"use strict";

	// Reload preview after any page setting is changed
	if (window.elementor !== undefined) {
		var timer = null;
		jQuery('#elementor-panel')
			.on('input change', '[data-setting*="freightco_options_"]', function (e) {
				var tab = jQuery('.elementor-panel-navigation-tab.active'),
					tab_name = tab.length > 0 ? tab.data('tab') : '',
					section = jQuery(this).parents('.elementor-control').prevAll('.elementor-control-type-section'),
					section_classes = section.length > 0 ? section.attr('class').split(' ') : [],
					section_name = '';
				for (var i=0; i < section_classes.length; i++) {
					if (section_classes[i].indexOf('elementor-control-section_') >= 0) {
						section_name = section_classes[i].replace('elementor-control-', '');
						break;
					}
				}
				if (tab.length > 0 && section_name != '') {
					if (timer !== null) clearTimeout(timer);
					timer = setTimeout(function() {
						elementor.reloadPreview();
						elementor.once( 'preview:loaded', function() {
							// Restore panel with the 'Page settings'
							var panel = jQuery( '#elementor-panel-footer-settings' );
								if (panel.length > 0) {
									panel.trigger( 'click' );
							}
							// Trigger 'click' on the last opened tab (if not first)
							tab = jQuery('.elementor-panel-navigation-tab[data-tab="'+tab_name+'"]');
							if (tab.length > 0 && tab.parent().find('.elementor-panel-navigation-tab').eq(0).data('tab') != tab_name) {
								tab.find('a').trigger('click');
							}

							// Trigger 'click' on the last opened section (if not first)
							section = jQuery('.elementor-control-'+section_name);
							if (section.length > 0 && !section.parent().find('.elementor-control').eq(0).hasClass('elementor-control-'+section_name)) {
								section.trigger('click');
							}
						} );
					}, 3500);	// Reload page after the AJAX-call 'Save page options' appear (Elementor call save options after 3000ms)
				}
			});
	}
});