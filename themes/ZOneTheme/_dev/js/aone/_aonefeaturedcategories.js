function setFeaturedCategoriesSlider(obj, sts, rtl) {
	$(obj).slick({
	    slidesToShow: sts,
		slidesToScroll: sts,
		adaptiveHeight: true,
		infinite: true,
		speed: 1000,
		autoplay: false,
		dots: false,
		arrows: true,
		rtl: rtl,
		responsive: [
			{
			  breakpoint: 992,
			  settings: {
				slidesToShow: Math.min(2, sts),
				slidesToScroll: Math.min(2, sts),
			  }
			},
			{
			  breakpoint: 768,
			  settings: {
				slidesToShow: Math.min(1, sts),
				slidesToScroll: Math.min(1, sts),
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
  	$('.js-featured-categories-slider').each(function() {
	  	var	sts = $(this).data('slidestoshow');

		setFeaturedCategoriesSlider($(this), sts, rtl);
	});
});