import $ from 'jquery';
import prestashop from 'prestashop';

prestashop.responsive = prestashop.responsive || {};
prestashop.responsive.min_width = 768;
prestashop.responsive.tablet_min_width = 992;
prestashop.responsive.scrollbar_width = scrollCompensate();
prestashop.responsive.current_width = window.innerWidth;

$(document).ready(function() {
    prestashop.responsive.mobile = prestashop.responsive.current_width < prestashop.responsive.min_width;
    prestashop.responsive.tablet = prestashop.responsive.current_width < prestashop.responsive.tablet_min_width;

    if (prestashop.responsive.mobile) {
        toggleMobileStyles(prestashop.responsive.mobile);
        toggleBlockOnMobile(prestashop.responsive.mobile);
    }
    if (prestashop.responsive.tablet) {
        toggleTabletStyles(prestashop.responsive.tablet);
        toggleLinkListOnMobile(prestashop.responsive.tablet);
    }
});

function scrollCompensate()
{
    var inner = document.createElement('p');
    inner.style.width = "100%";
    inner.style.height = "200px";

    var outer = document.createElement('div');
    outer.style.position = "absolute";
    outer.style.top = "0px";
    outer.style.left = "0px";
    outer.style.visibility = "hidden";
    outer.style.width = "200px";
    outer.style.height = "150px";
    outer.style.overflow = "hidden";
    outer.appendChild(inner);

    document.body.appendChild(outer);
    var w1 = inner.offsetWidth;
    outer.style.overflow = 'scroll';
    var w2 = inner.offsetWidth;
    if (w1 == w2) w2 = outer.clientWidth;

    document.body.removeChild(outer);

    return (w1 - w2);
}

function swapChildren(obj1, obj2)
{
    var temp = obj2.children().detach();
    obj2.empty().append(obj1.children().detach());
    obj1.append(temp);
}

function toggleMobileStyles(is_mobile)
{
    if (is_mobile) {
        $("*[id^='_desktop_']").each(function(idx, el) {
            var target = $('#' + el.id.replace('_desktop_', '_mobile_'));
            if (target.length) {
                swapChildren($(el), target);
            }
        });
    } else {
        $("*[id^='_mobile_']").each(function(idx, el) {
            var target = $('#' + el.id.replace('_mobile_', '_desktop_'));
            if (target.length) {
                swapChildren($(el), target);
            }
        });
    }
    prestashop.emit('responsive update', {
        mobile: is_mobile
    });
}

function toggleTabletStyles(is_tablet)
{
    if (is_tablet) {
        $("*[id^='_desktop_']").each(function(idx, el) {
            var target = $('#' + el.id.replace('_desktop_', '_tablet_'));
            if (target.length) {
                swapChildren($(el), target);
            }
        });
    } else {
        $("*[id^='_tablet_']").each(function(idx, el) {
            var target = $('#' + el.id.replace('_tablet_', '_desktop_'));
            if (target.length) {
                swapChildren($(el), target);
            }
        });
    }
}

function toggleLinkListOnMobile(is_tablet) {
    var $objs = $('.js-toggle-linklist-mobile'),
        $title = $objs.find('h3, h4');

    if (is_tablet) {
        $title.each(function(idx, el) {
            var $content = $(el).next();
            if ($content.length) {
                $(el).addClass('toggle-linklist-title');
                $content.hide();
                $(el).click(function() {
                    $content.stop().slideToggle();
                });
            }
        });
    } else {
        $title.each(function(idx, el) {
            var $content = $(el).next();
            if ($content.length) {
                $(el).unbind('click');
                $(el).removeClass('toggle-linklist-title');
                $content.show();
            }
        });
    }
}

function toggleBlockOnMobile(is_mobile) {
    var $objs = $('.js-toggle-block-mobile');

    if (is_mobile) {
        $objs.each(function(idx, el) {
            var $content = $(el).next();
            if ($content.length) {
                $(el).addClass('toggle-block-title is-hide');
                $content.hide();
                $(el).click(function() {
                    $content.stop().slideToggle();
                    $(el).toggleClass('is-hide');
                });
            }
        });
    } else {
        $objs.each(function(idx, el) {
            var $content = $(el).next();
            if ($content.length) {
                $(el).unbind('click');
                $(el).removeClass('toggle-block-title is-hide');
                $content.show();
            }
        });
    }
}


$(window).on('resize', function() {
    var _cw = prestashop.responsive.current_width;
    var _mw = prestashop.responsive.min_width;
    var _tmw = prestashop.responsive.tablet_min_width;
    var _w = window.innerWidth;

    var _toggle = (_cw >= _mw && _w < _mw) || (_cw < _mw && _w >= _mw);
    var _tablet_toggle = (_cw >= _tmw && _w < _tmw) || (_cw < _tmw && _w >= _tmw);

    prestashop.responsive.current_width = _w;

    prestashop.responsive.mobile = prestashop.responsive.current_width < prestashop.responsive.min_width;
    prestashop.responsive.tablet = prestashop.responsive.current_width < prestashop.responsive.tablet_min_width;

    if (_toggle) {
        toggleMobileStyles(prestashop.responsive.mobile);
        toggleBlockOnMobile(prestashop.responsive.mobile);
    }
    if (_tablet_toggle) {
        toggleTabletStyles(prestashop.responsive.tablet);
        toggleLinkListOnMobile(prestashop.responsive.tablet);
    }
});

