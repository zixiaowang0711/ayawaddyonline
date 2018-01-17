function addLivePreviewColorPicker() {
	var live_preview = $('#js-aoneColorsLivePreview'),
		color_picker = $('.js-color .js-colorPicker'),
		toggle_button = $('.js-previewToggle'),
        reset_button = $('.js-previewReset'),
        preview_boxed = $('.js-previewBoxed'),
		preview_wide = $('.js-previewWide'),
        special_style = $('.js-specialStyle');

    color_picker.each(function() {
        $(this).colpick({
            layout: 'hex',
            color: $(this).data('color'),
            onSubmit: function(hsb,hex,rgb,el){
                $(el).css('background-color', '#' + hex);
                
                var styles = $(el).parent('.js-color').children('.style');
                $.each(styles, function() {
                    var selector = $(this).children('.selector');
                    var property = $(this).children('.property');
                    var preview = $(this).children('.preview');
                    preview.html('<style>' + selector.text() + '{' + property.text() + '#' + hex + '}</style>');
                });
            }
        });
    });

    toggle_button.click(function() {
        live_preview.toggleClass('open');
    });

    reset_button.click(function(e) {
        e.preventDefault();
        live_preview.find('.js-color .preview').html('');
        color_picker.each(function() {
            $(this).css('background-color', $(this).data('color'));
        });
        return false;
    });
    
    preview_boxed.click(function(e) {
        e.preventDefault();
        $('#page').addClass('boxed-layout');
        preview_wide.removeClass('active');
        $(this).addClass('active');
        $('.js-boxedWide .style .preview').html('<style>' + $('.js-boxedBackgroundCSS').text() + '</style>');

        return false;
    });
    preview_wide.click(function(e) {
        e.preventDefault();
        $('#page').removeClass('boxed-layout');
        preview_boxed.removeClass('active');
        $(this).addClass('active');
        $('.js-boxedWide .style .preview').html('');

        return false;
    });

    if ($('body').hasClass('remove-border-radius')) {
        special_style.find('input[name="disable_border_radius"]').attr('checked', 'checked');
    }
    if ($('body').hasClass('remove-box-shadow')) {
        special_style.find('input[name="disable_box_shadow"]').attr('checked', 'checked');
    }
    if ($('#wrapper').hasClass('background-for-title')) {
        special_style.find('input[name="background_block_title"]').attr('checked', 'checked');
    }
    special_style.find('input[name="disable_border_radius"]').change(function() {
        $('body').toggleClass('remove-border-radius');
    });
    special_style.find('input[name="disable_box_shadow"]').change(function() {
        $('body').toggleClass('remove-box-shadow');
    });
    special_style.find('input[name="background_block_title"]').change(function() {
        $('#wrapper').toggleClass('background-for-title background-for-tab-title background-for-column-title');
    });
}

$(document).ready(function() {
	addLivePreviewColorPicker();
});
