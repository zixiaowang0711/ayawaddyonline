function nivoCaptionIn(property, effect, speed) {
	if (property == 'top')
		jQuery('.nivo-caption').animate({top: '0'}, speed, effect, fadeInProducts);
	else if (property == 'bottom')
		jQuery('.nivo-caption').animate({bottom: '0'}, speed, effect, fadeInProducts);
	else if (property == 'left')
		jQuery('.nivo-caption').animate({left: '0'}, speed, effect, fadeInProducts);
	else if (property == 'right')
		jQuery('.nivo-caption').animate({right: '0'}, speed, effect, fadeInProducts);
	else if (property == 'opacity')
		jQuery('.nivo-caption').animate({opacity: '1'}, speed, 'swing', fadeInProducts);
}
function nivoCaptionOut(property, effect, speed) {
	if (property == 'top')
		jQuery('.nivo-caption').animate({top: '-100%'}, speed, effect);
	else if (property == 'bottom')
		jQuery('.nivo-caption').animate({bottom: '-100%'}, speed, effect);
	else if (property == 'left')
		jQuery('.nivo-caption').animate({left: '-100%'}, speed, effect);
	else if (property == 'right')
		jQuery('.nivo-caption').animate({right: '-100%'}, speed, effect);
	else if (property == 'opacity')
		jQuery('.nivo-caption').animate({opacity: '0'}, speed, 'swing');
}
function fadeInProducts() {
	jQuery('.nivo-caption .js-slide-products-related').fadeIn();
}

$(window).load(function() {
	var sliderObject = $('#aoneSlider'),
		settings = sliderObject.data('settings');

	if (sliderObject.length) {
		sliderObject.nivoSlider({
			effect: settings.effect,
			slices: Number(settings.slices),
			boxCols: Number(settings.boxCols),
			boxRows: Number(settings.boxRows),
			animSpeed: Number(settings.animSpeed),
			pauseTime: Number(settings.pauseTime),
			startSlide: Number(settings.startSlide),
			directionNav: settings.directionNav,
			controlNav: settings.controlNav,
			controlNavThumbs: settings.controlNavThumbs,
			pauseOnHover: settings.pauseOnHover,
			manualAdvance: settings.manualAdvance,
			randomStart: settings.randomStart,
			afterLoad: function(){
				nivoCaptionIn(settings.caption_effect, 'easeInBack', settings.animSpeed);
			},
			beforeChange: function(){
				nivoCaptionOut(settings.caption_effect, 'easeInBack', settings.animSpeed);
			},
			afterChange: function(){
				nivoCaptionIn(settings.caption_effect, 'easeInBack', settings.animSpeed);
			}
		});
	}
});