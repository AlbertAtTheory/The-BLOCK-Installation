(function ($) {
Drupal.behaviors.myTheme = {
attach: function(context, settings) {
	// Replace broken user images with default images.
	$('.avatar.image-style-30x30').error(function(){
        $(this).attr('src', '/sites/all/themes/theblock/images/icon-helmet.png').height(23).width(21).css('margin', '4px');
	});

	$('.image-style-driver-photo').error(function(){
        $(this).attr('src', '/sites/all/themes/theblock/images/default-profilePoster.jpg').height(336).width(600);
	});

	$('.avatar.image-style-48x48').error(function(){
        $(this).remove();
	});

	// Keep Facebook Login in same window
	$('.facebook-action-connect').attr('target', '_self');
	$('#modalContent #user-login div a').attr('target', '_self');

	$(window).scroll(function(){
		var window_top = $(window).scrollTop();
		var div_top = $('#content-column').offset().top - 30;
		
		if (window_top > div_top)
			$('.region-sidebar-first').addClass('stick')
		else
			$('.region-sidebar-first').removeClass('stick');
	});
	if (!$.cookie('welcometotheblock')
	&& !navigator.userAgent.match(/Android/i)
	&& !navigator.userAgent.match(/webOS/i)
	&& !navigator.userAgent.match(/iPhone/i)
	&& !navigator.userAgent.match(/iPad/i)
	&& !navigator.userAgent.match(/iPod/i)
	&& !navigator.userAgent.match(/BlackBerry/i)) {
		$("#welcometotheblock").slideDown();
		$("#messages-help-wrapper").fadeIn();
	}
	
	$("#mn-button").click(function(){
		$("#mn-menu").fadeToggle();
		$("#block-search-form").fadeToggle();
	});
	
	$("#pagedimmer").click(function(){
		$("#pagedimmer").fadeOut();
		resetALL();
	});
	
	$("#block-generate-qr").click(function() {
		$("#block-generate-qr .qrcode").animate({width:200,height:200,right:-355,top:60},800);
		$("#block-qr-panel").fadeIn();
		$("#pagedimmer").fadeIn();
	});
	
	$("#block-views-races-entered-block .expand").click(function() {
		$("#block-views-races-entered-block .view-content").fadeIn();
		$("#block-views-races-entered-block .view-content").addClass('iamfloating');
		$("#pagedimmer").fadeIn();
	});
	
	function resetALL() {
		$("#block-generate-qr .qrcode").animate({width:25,height:25,right:0,top:3},800);
		$("#block-qr-panel").fadeOut();
		$(".iamfloating").fadeOut();
	}
	
	var sections = {},
		regions = {},
		racing = {},
        _height  = $(window).height(),
        i        = 0;
    
    // Grab positions of our sections 
    $('section.block-views').each(function(){
        sections[this.id] = $(this).offset().top;
    });
    
    $('div.region').each(function(){
        regions[this.className] = $(this).offset().top;
    });
    
    $('h1').each(function(){
        racing[this.id] = $(this).offset().top;
    });
    
    console.log('social buttons:');
$('#twitter').sharrre({
  share: {
    twitter: true
  },
  template: '<a class="box" href="#"><div class="count" href="#">{total}</div><div class="share"><span></span></div></a>',
  enableHover: false,
  enableTracking: true,
  buttons: { twitter: {via: 'chevroletperf'}},
  click: function(api, options){
    api.simulateClick();
    api.openPopup('twitter');
  }
});
$('#facebook').sharrre({
  share: {
    facebook: true
  },
  template: '<a class="box" href="#"><div class="count" href="#">{total}</div><div class="share"><span></span></div></a>',
  enableHover: false,
  enableTracking: true,
  click: function(api, options){
    api.simulateClick();
    api.openPopup('facebook');
  }
});
$('#googleplus').sharrre({
  share: {
    googlePlus: true
  },
  template: '<a class="box" href="#"><div class="count" href="#">{total}</div><div class="share">+1</div></a>',
  enableHover: false,
  enableTracking: true,
  click: function(api, options){
    api.simulateClick();
    api.openPopup('googlePlus');
  }
});
console.log('done.');
}
};
})(jQuery);

function highlightMe(divid) {
	jQuery(document).scroll(function(){
		var vp = jQuery('#' + divid),
		vp_offset = vp.offset(),  // has x and y properties
		vp_height = vp.height();
		
		if (jQuery('#nav_' + divid).length) {
			var vp2 = jQuery('#nav_' + divid),
			vp2_offset = vp2.offset();
		} else {
			var vp2 = jQuery('.header'),
			vp2_offset = vp2.offset();
		}
		
		if (vp2_offset.top>=vp_offset.top) {
			jQuery('a').removeClass('active');
			jQuery('#nav_' + divid).addClass('active');
			
			//alert(divid + ': ' + vp_offset.top);
		}
	});
}

function animateScrollTo(ast) {
	jQuery('html,body').animate({scrollTop: jQuery(ast).offset().top});
}
