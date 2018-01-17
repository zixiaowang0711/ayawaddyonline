function updateDropdownPosition()
{
    if ($('#amegamenu').hasClass('amegamenu_rtl'))
        updateDropdownPositionRTL();
    else
        updateDropdownPositionLTR();
        
}
function updateDropdownPositionLTR()
{
	$('#amegamenu .adropdown').each(function(index, element) {
		var i = 0 - ($(this).outerWidth() - $(this).parent().outerWidth())/2;
        var this_offset_right = $(this).offset().left + $(this).outerWidth();
        var this_parent_offset_right = $(this).parent().offset().left + $(this).parent().outerWidth();
        var amegamenu_offset_right = $('#amegamenu').offset().left + $('#amegamenu').outerWidth();
	
		if ( this_offset_right - parseInt($(this).css('margin-left')) + i > amegamenu_offset_right) {
			var max = $('#amegamenu').offset().left - $(this).parent().offset().left + $('#amegamenu').outerWidth() - $(this).outerWidth();
			$(this).css('margin-left', max + 'px');
		} else if ($(this).offset().left - parseInt($(this).css('margin-left')) + i < $('#amegamenu').offset().left) {
			var min = $('#amegamenu').offset().left - $(this).parent().offset().left;
			$(this).css('margin-left', min + 'px');
		} else {
			$(this).css('margin-left', i + 'px');
		}
	});
}
function updateDropdownPositionRTL()
{
	$('#amegamenu .adropdown').each(function(index, element) {
		var i = 0 - ($(this).outerWidth() - $(this).parent().outerWidth())/2;
        var this_offset_right = $(this).offset().left + $(this).outerWidth();
        var this_parent_offset_right = $(this).parent().offset().left + $(this).parent().outerWidth();
        var amegamenu_offset_right = $('#amegamenu').offset().left + $('#amegamenu').outerWidth();
	
		if ( $(this).offset().left + parseInt($(this).css('margin-right')) - i < $('#amegamenu').offset().left) {
			var max = 0 - ($(this).outerWidth() - this_parent_offset_right + $('#amegamenu').offset().left);
			$(this).css('margin-right', max + 'px');
		} else if (this_offset_right + parseInt($(this).css('margin-right')) - i > amegamenu_offset_right) {
			var min = 0 - (amegamenu_offset_right - this_parent_offset_right);
			$(this).css('margin-right', min + 'px');
		} else {
			$(this).css('margin-right', i + 'px');
		}
	});
}
function mobileToggleEvent()
{
	$('#mobile-amegamenu .amenu-item.plex .amenu-link').on('click', function() {
		$(this).next('.adropdown').stop().slideToggle();
		$(this).parent('.amenu-item').toggleClass('active');

		return false;
	});
}
function enableHoverMenuOnTablet()
{
	$('html').on('touchstart', function(e) {
		$('#amegamenu .anav-top > li').removeClass('hover');
	});
	$('#amegamenu').on('touchstart', function (e) {
		e.stopPropagation();
	});
	$('#amegamenu .anav-top > li.plex > .amenu-link').on('touchstart', function (e) {
		'use strict'; //satisfy code inspectors		
		var li = $(this).parent('li');
		if (li.hasClass('hover')) {
			return true;
		} else {
			$('#amegamenu .anav-top > li').removeClass('hover');
			li.addClass('hover');
			e.preventDefault();
			return false;
		}
	});
}

$(document).ready(function() {
	setTimeout(function(){
		updateDropdownPosition();
	}, 500);
	$(window).resize(updateDropdownPosition);
	
	mobileToggleEvent();
	enableHoverMenuOnTablet();
});