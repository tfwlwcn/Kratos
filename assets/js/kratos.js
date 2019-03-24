(function () {

	'use strict';

	var mainMenu = function () {

		$('#kratos-primary-menu').superfish({
			delay: 0,
			animation: {
				opacity: 'show'
			},
			speed: 'fast',
			cssArrows: true,
			disableHI: true
		});

	};

	var offcanvas = function () {
		var $clone = $('#kratos-menu-wrap').clone();
		$clone.attr({
			'id': 'offcanvas-menu'
		});
		$clone.find('> ul').attr({
			'class': '',
			'id': ''
		});
		$('.kratos-page').prepend($clone);
		$('.js-kratos-nav-toggle').on('click', function () {
			if ($('body').hasClass('kratos-offcanvas')) {
				$('body').removeClass('kratos-offcanvas');
			} else {
				$('body').addClass('kratos-offcanvas');
			}
		});
		// $('#offcanvas-menu').css('height', $(window).height());
		$(window).resize(function () {
			var w = $(window);
			$('#offcanvas-menu').css('height', w.height());
			if (w.width() > 769) {
				if ($('body').hasClass('kratos-offcanvas')) {
					$('body').removeClass('kratos-offcanvas');
				}
			}
		});
	};

	var mobileMenuOutsideClick = function () {
		$(document).click(function (e) {
			var container = $("#offcanvas-menu, .js-kratos-nav-toggle");
			if (!container.is(e.target) && container.has(e.target).length === 0) {
				if ($('body').hasClass('kratos-offcanvas')) {
					$('body').removeClass('kratos-offcanvas');
				}
			}
		});
	};

	var contentWayPoint = function () {
		var i = 0;
		$('.animate-box').waypoint(function (direction) {
			if (direction === 'down' && !$(this.element).hasClass('animated')) {
				i++;
				$(this.element).addClass('item-animate');
				setTimeout(function () {
					$('body .animate-box.item-animate').each(function (k) {
						var el = $(this);
						setTimeout(function () {
							el.addClass('fadeInUp animated');
							el.removeClass('item-animate');
						}, k * 200, 'easeInOutExpo');
					});
				}, 100);
			}
		}, {
			offset: '85%'
		});
	};

	var shareMenu = function () {
		$(".Share").click(function () {
			$(".share-wrap").fadeToggle("slow");
		});

		$('.qrcode').each(function (index, el) {
			var url = $(this).data('url');
			if ($.fn.qrcode) {
				$(this).qrcode({
					text: url,
					width: 150,
					height: 150,
				});
			}
		});
	};

	var showlove = function () {
		$.fn.postLike = function () {
			if ($(this).hasClass('done')) {
				layer.msg('您已经支持过了', function () {});
				return false;
			} else {
				$(this).addClass('done');
				layer.msg('感谢您的支持');
				var id = $(this).data("id"),
					action = $(this).data('action');
				var ajax_data = {
					action: "love",
					um_id: id,
					um_action: action
				};
				$.post(kratos.site + "/wp-admin/admin-ajax.php", ajax_data, function (data) {
					$(rateHolder).html(data);
				});
				return false;
			}
		};
		$(document).on("click", ".Love", function () {
			$(this).postLike();
		});
	};

	var gotop = function () {
		var offset = 300,
			offset_opacity = 1200,
			scroll_top_duration = 700,
			$back_to_top = $('.cd-top'),
			$cd_gb = $('.cd-gb'),
			$cd_weixin = $('.cd-weixin');
		$(window).scroll(function(){
			( $(this).scrollTop() > offset ) ? $back_to_top.addClass('cd-is-visible') : $back_to_top.removeClass('cd-is-visible cd-fade-out');
			if( $(this).scrollTop() > offset_opacity ) { 
				$back_to_top.addClass('cd-fade-out');
				$cd_gb.addClass('cd-fade-out');
				$cd_weixin.addClass('cd-fade-out');
			}
		});
		$back_to_top.on('click', function(event){
			event.preventDefault();
			$('body,html').animate({
				scrollTop: 0 ,
			 	}, scroll_top_duration
			);
		});
	};

	var weixinpic = function () {
		$("#weixin-img").mouseout(function () {
			$("#weixin-pic")[0].style.display = 'none';
		})
		$("#weixin-img").mouseover(function () {
			$("#weixin-pic")[0].style.display = 'block';
		})
	};

	var copyright = function () {
		console.log("项目托管：https://github.com/Vtrois/Kratos");
	};

	$(function () {
		mainMenu();
		shareMenu();
		offcanvas();
		showlove();
		gotop();
		weixinpic();
		mobileMenuOutsideClick();
		contentWayPoint();
		copyright();
	});
}());