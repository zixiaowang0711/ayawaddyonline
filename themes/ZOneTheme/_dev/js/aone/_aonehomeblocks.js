function updateSlickInTabs() {
  $('.aone-tabs a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
    var slick = $(e.target).attr('data-slickID');
    $('#'+slick).slick('setPosition');
  });
}

function setHomeBlockSlider(obj, sts, mdsts, scroll, rtl) {
  $(obj).slick({
    slidesToShow: sts,
    slidesToScroll: sts,
    adaptiveHeight: true,
    infinite: true,
    speed: 1000,
    autoplay: scroll,
    dots: false,
    arrows: true,
    rtl: rtl,
    responsive: [
      {
        breakpoint: 1220,
        settings: {
          slidesToShow: Math.min(4, sts),
          slidesToScroll: Math.min(4, sts),
        }
      },
      {
        breakpoint: 992,
        settings: {
          slidesToShow: Math.min(3, mdsts),
          slidesToScroll: Math.min(3, mdsts),
        }
      },
      {
        breakpoint: 768,
        settings: {
          slidesToShow: Math.min(2, mdsts),
          slidesToScroll: Math.min(2, mdsts),
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
  $('.js-home-block-slider').each(function() {
    var scroll = $(this).data('autoscroll'),
        sts = $(this).data('slidestoshow'),
        mdsts = $(this).data('mdslidestoshow');

    if ('ontouchstart' in document.documentElement)
      scroll = false;

    setHomeBlockSlider($(this), sts, mdsts, scroll, rtl);
  });

  updateSlickInTabs();
});