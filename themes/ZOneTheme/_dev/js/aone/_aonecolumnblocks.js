function setColumnBlockSlider(obj, scroll, rtl) {
	$(obj).slick({
	    slidesToShow: 1,
		slidesToScroll: 1,
		adaptiveHeight: true,
		infinite: true,
		speed: 1000,
		autoplay: scroll,
		dots: true,
		arrows: false,
		rtl: rtl,
  	});
}

$(document).ready(function() {
	var rtl = false;
  	if (prestashop.language.is_rtl == '1') {
    	rtl = true;
  	}
	$('.js-column-block-slider').each(function() {
	  	var	scroll = $(this).data('autoscroll');
	  	if ('ontouchstart' in document.documentElement)
			scroll = false;

		setColumnBlockSlider($(this), scroll, rtl);
	});
});