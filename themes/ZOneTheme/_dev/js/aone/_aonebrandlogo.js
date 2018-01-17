function setBrandLogoSlider(obj, scroll, rtl) {
	$(obj).slick({
	    slidesToShow: 6,
		slidesToScroll: 1,
		adaptiveHeight: false,
		infinite: true,
		speed: 700,
		autoplay: scroll,
		dots: false,
		arrows: true,
		draggable: false,
		rtl: rtl,
		responsive: [
			{
			  breakpoint: 1220,
			  settings: {
				slidesToShow: 5,
			  }
			},
			{
			  breakpoint: 992,
			  settings: {
				slidesToShow: 4,
			  }
			},
			{
			  breakpoint: 768,
			  settings: {
				slidesToShow: 3,
			  }
			},
			{
			  breakpoint: 576,
			  settings: {
				slidesToShow: 2,
			  }
			}
		],
  	});
}

$(document).ready(function() {
	var rtl = false;
  	if (prestashop.language.is_rtl == '1') {
    	rtl = true;
  	}
  	$('.js-brand-logo-slider').each(function() {
	  	var	scroll = $(this).data('autoscroll');
		setBrandLogoSlider($(this), scroll, rtl);
	});
});