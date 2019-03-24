/**
 * Custom scripts needed for the colorpicker, image button selectors,
 * and navigation tabs.
 */

jQuery(document).ready(function($) {

	// Loads the color pickers
	$('.of-color').wpColorPicker();

	// Image Options
	$('.of-radio-img-img').click(function(){
		$(this).parent().parent().find('.of-radio-img-img').removeClass('of-radio-img-selected');
		$(this).addClass('of-radio-img-selected');
	});

	$('.of-radio-img-label').hide();
	$('.of-radio-img-img').show();
	$('.of-radio-img-radio').hide();

	jQuery('#mail_smtpconfig').click(function() {
		jQuery('#section-mail_smtpsecure').fadeToggle(400);
		jQuery('#section-mail_host').fadeToggle(400);
		jQuery('#section-mail_port').fadeToggle(400);
		jQuery('#section-mail_name').fadeToggle(400);
		jQuery('#section-mail_username').fadeToggle(400);
		jQuery('#section-mail_passwd').fadeToggle(400);
	});

	if (jQuery('#mail_smtpconfig:checked').val() !== undefined) {
		jQuery('#section-mail_smtpsecure').show();
		jQuery('#section-mail_host').show();
		jQuery('#section-mail_port').show();
		jQuery('#section-mail_name').show();
		jQuery('#section-mail_username').show();
		jQuery('#section-mail_passwd').show();
	}

	jQuery('#social_config').click(function() {
		jQuery('#section-social_weibo').fadeToggle(400);
		jQuery('#section-social_qzone').fadeToggle(400);
		jQuery('#section-social_twitter').fadeToggle(400);
		jQuery('#section-social_facebook').fadeToggle(400);
		jQuery('#section-social_steam').fadeToggle(400);
		jQuery('#section-social_github').fadeToggle(400);
	});

	if (jQuery('#social_config:checked').val() !== undefined) {
		jQuery('#section-social_weibo').show();
		jQuery('#section-social_qzone').show();
		jQuery('#section-social_twitter').show();
		jQuery('#section-social_facebook').show();
		jQuery('#section-social_steam').show();
		jQuery('#section-social_github').show();
	}

	jQuery('#ad_top').click(function() {
		jQuery('#section-ad_top_img').fadeToggle(400);
		jQuery('#section-ad_top_url').fadeToggle(400);
	});

	if (jQuery('#ad_top:checked').val() !== undefined) {
		jQuery('#section-ad_top_img').show();
		jQuery('#section-ad_top_url').show();
	}

	jQuery('#ad_bottom').click(function() {
		jQuery('#section-ad_bottom_img').fadeToggle(400);
		jQuery('#section-ad_bottom_url').fadeToggle(400);
	});

	if (jQuery('#ad_bottom:checked').val() !== undefined) {
		jQuery('#section-ad_bottom_img').show();
		jQuery('#section-ad_bottom_url').show();
	}

	jQuery('#post_donate').click(function() {
		jQuery('#section-post_donate_links').fadeToggle(400);
	});

	if (jQuery('#post_donate:checked').val() !== undefined) {
		jQuery('#section-post_donate_links').show();
	}

	jQuery('#global_guestbook').click(function() {
		jQuery('#section-global_guestbook_url').fadeToggle(400);
	});

	if (jQuery('#global_guestbook:checked').val() !== undefined) {
		jQuery('#section-global_guestbook_url').show();
	}

	jQuery('#global_weichat').click(function() {
		jQuery('#section-global_weichat_img').fadeToggle(400);
	});

	if (jQuery('#global_weichat:checked').val() !== undefined) {
		jQuery('#section-global_weichat_img').show();
	}

	// Loads tabbed sections if they exist
	if ( $('.nav-tab-wrapper').length > 0 ) {
		options_framework_tabs();
	}

	function options_framework_tabs() {

		var $group = $('.group'),
			$navtabs = $('.nav-tab-wrapper a'),
			active_tab = '';

		// Hides all the .group sections to start
		$group.hide();

		// Find if a selected tab is saved in localStorage
		if ( typeof(localStorage) != 'undefined' ) {
			active_tab = localStorage.getItem('active_tab');
		}

		// If active tab is saved and exists, load it's .group
		if ( active_tab != '' && $(active_tab).length ) {
			$(active_tab).fadeIn();
			$(active_tab + '-tab').addClass('nav-tab-active');
		} else {
			$('.group:first').fadeIn();
			$('.nav-tab-wrapper a:first').addClass('nav-tab-active');
		}

		// Bind tabs clicks
		$navtabs.click(function(e) {

			e.preventDefault();

			// Remove active class from all tabs
			$navtabs.removeClass('nav-tab-active');

			$(this).addClass('nav-tab-active').blur();

			if (typeof(localStorage) != 'undefined' ) {
				localStorage.setItem('active_tab', $(this).attr('href') );
			}

			var selected = $(this).attr('href');

			$group.hide();
			$(selected).fadeIn();

		});
	}

});