function addBodyTouchClass() {
    if ('ontouchstart' in document.documentElement) {
        $('body').addClass('touch-screen');
    }
}

function stickyHeader() {
    if ($('.js-sticky-menu').length) {
        $('.js-sticky-menu').sticky({
            wrapperClassName: 'desktop-sticky-wrapper'
        });

        $('.js-sticky-menu').on('sticky-start', function() {
            $('.js-sticky-icon-cart').html($('.js-sticky-cart-source').html());
        });
    }
    if ($('.js-mobile-sticky').length) {
        $('.js-mobile-sticky').sticky({
            wrapperClassName: 'mobile-sticky-wrapper'
        });
    }
}

function scrollToTopButton() {
    var $sttb = $('#js_scroll_to_top');
    $(window).scroll(function() {
        if ($(this).scrollTop() > $(window).height()) {
            $sttb.fadeIn();
        } else {
            $sttb.fadeOut();
        }
    });

    $sttb.find('a').smoothScroll({
        speed: 500,
    });
}

function loadPageWithProcessBar() {
    if ($('.js-page-progress-bar').length) {
        Pace.start();
        Pace.on('done', function() {
            $('#page').removeClass('js-page-progress-bar');
        });
    }
}

function sliderHeaderMessages() {
    $('.header-messages').slick({
        arrows: false,
        infinite: false,
        speed: 500,
        fade: true,
        cssEase: 'linear',
        autoplay: true,
    });
}

function addTooltip() {
    $('body:not(.touch-screen) [data-toggle="tooltip"]').tooltip({
        position: { my: "center bottom-8", at: "center top" },
        hide: false,
        show: false,
    });
}

function loadSidebarNavigation() {
    var resetMenu = function() {
            $('html').removeClass('st-menu-open st-effect-left st-effect-right');
        };

    $('#st_overlay').on('click', function(e){
        resetMenu();
    });
    $('.st-menu-close').on('click', function(e){
        resetMenu();
    });

    //$('#js-search-sidebar').removeClass('js-hidden').html($('.js-search-source').html());
    $('#js-header-phone-sidebar').removeClass('js-hidden').html($('.js-header-phone-source').html());
    //$('#js-account-sidebar').removeClass('js-hidden').html($('.js-account-source').html());
    $('#js-language-sidebar').removeClass('js-hidden').html($('.js-language-source').html());
    if (!$('.js-sidebar-cart-enabled').length) {
        $('#js-left-currency-sidebar').removeClass('js-hidden').html($('.js-currency-source').html());
    }

    $('#js-left-nav-trigger').on('click', function(e){
        $('html').addClass('st-effect-left st-menu-open');
        return false;
    });
}

function loadSidebarCart() {
    if ($('.js-sidebar-cart-enabled').length) {
        $('#js-cart-sidebar').removeClass('js-hidden').html($('.js-cart-source').html());
        $('#js-currency-sidebar').removeClass('js-hidden').html($('.js-currency-source').html());

        $('.js-cart-source').addClass('hidden-xs-up');

        $('.js-sidebar-cart-trigger').on('click', function(e){
            $('html').addClass('st-effect-right st-menu-open');
            return false;
        });
    }
}

function sidebarSubCategoriesTrigger() {
    var $subcats = $('.js-sidebar-categories');
    if ($subcats.length) {
        $subcats.find('.js-collapse-trigger').click(function(e) {
            $(this).next('.js-sub-categories').stop().slideToggle();
            $(this).find('.add').toggle();
            $(this).find('.remove').toggle();
        });
    }
}

function mobileMenuControl() {
  $('#mobile-menu-icon').on('click', function() {
    $('#dropdown-mobile-menu').stop().slideToggle();
  });
}

$(document).ready(function() {
    addBodyTouchClass();

    loadPageWithProcessBar();
    stickyHeader();
    scrollToTopButton();

    loadSidebarNavigation();
    loadSidebarCart();

    sidebarSubCategoriesTrigger();
    mobileMenuControl();
});
$(window).load(function() {
    addTooltip();
});