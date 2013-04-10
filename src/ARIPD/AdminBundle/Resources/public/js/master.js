ARIPD = {
	all : function() {
		//ARIPD.quicksand();

		//ARIPD.headerPull();
		//ARIPD.scrollTop();

		//ARIPD.loadScript(url, callback);
		//ARIPD.loadScript('/themes/default/assets/js/SocialShare.js', function() { /*alert('SocialShare loaded');*/ });

		//ARIPD.slider();
		//ARIPD.tagCloud();
		
		//ARIPD.selectAllItems();
		
		//ARIPD.qrcode();

	},
	
	qrcode : function() {
		jQuery('#qrcode').qrcode('deneme');
	},

	/**
	 * Javascript/jQuery Super Simple Select All Checkbox
	 * Ref: http://www.finalconcept.com.au/article/view/javascript-jquery-super-simple-select-all-checkbox
	 * 
	 * Usage:
	 * <input type="checkbox" id="select_all_items" onchange="javascript:selectAllItems()" name="select_all_items" />
	 * <input type="checkbox" class="chkSelectItem" name="selected_indicators" value="2" />
	 */
	selectAllItems : function() {
		var selectAll = $("#select_all_items");

		$(".chkSelectItem").each(function(index, item) {
			if (selectAll.prop("checked") != $(item).prop('checked')) {
				$(item).trigger('click');
			}
		});
	},
	
	tagCloud : function() {
		$.fn.tagcloud.defaults = {
			size: {start: 14, end: 18, unit: 'pt'},
			color: {start: '#cde', end: '#f52'}
		};
		$('#cloud a').tagcloud();
	},
	
	slider : function() {
		$('#cms_latest').nivoSlider();
		
		$('#store_product_banner').nivoSlider();
		
		$('#cms_post_banner').nivoSlider({
			effect : "fade",
			slices : 15,
			boxCols : 8,
			boxRows : 4,
			animSpeed : 200,
			pauseTime : 3000,
			startSlide : 0,
			directionNav : false,
			controlNav : true,
			controlNavThumbs : true,
			pauseOnHover : true,
			manualAdvance : false
		});
	},

	quicksand : function() {
		// get the first collection
		var $thumbnails = $('#thumbnails');

		// clone thumbnails to get a second collection
		var $data = $thumbnails.clone();

		// attempt to call Quicksand on every click
		$('.filter-nav li a').live(
				'click',
				function(e) {

					var thisid = $(this).attr('data-id');

					if (thisid == 'all') {
						var $filteredData = $data.find('li');
					} else {
						var $filteredData = $data.find('li[data-type*='
								+ thisid + ']');
					}

					// finally, call quicksand
					$thumbnails.quicksand($filteredData, {
						duration : 800,
						easing : 'easeInOutQuad'
					}, function() {
						// Shadowbox.setup("a.thumbnail");
					});

					$('.filter-nav li').removeClass('active');
					$(this).parent().addClass('active');

					return false;

				});
	},

	headerPull : function() {
		$(window).scroll(function() {

			var scrollVal = $(this).scrollTop();

			if (scrollVal > 162) {
				$('.middle').css('top', '64px');
				$('.footer').css('top', '64px');

				$('.nav').css('position', 'fixed');

				$('#homepage').hide();
				$('ul.main_nav li.sep:first').hide();
				$('.logo_s').show();

			} else {
				$('.nav').css('position', 'relative');

				$('.logo_s').hide();
				$('#homepage').show();
				$('ul.main_nav li.sep:first').show();

				$('.middle').css('top', '0px');
				$('.footer').css('top', '0px');
			}

		});
	},

	scrollTop : function() {
		$('.scroll_top').click(function() {
			$('html, body').animate({
				scrollTop : $("body").offset().top
			}, 700);
		});
	},

	/**
	 * Usage: loadScript("my_lovely_script.js", function() { alert('script
	 * loaded'); });
	 * 
	 * @param url
	 * @param callback
	 */
	loadScript : function(url, callback) {
		var head = document.getElementsByTagName('head')[0];

		var script = document.createElement('script');
		script.type = 'text/javascript';
		script.src = url;

		script.onreadystatechange = callback;
		script.onload = callback;

		head.appendChild(script);
	}

}

$(function() {
	ARIPD.all();
});