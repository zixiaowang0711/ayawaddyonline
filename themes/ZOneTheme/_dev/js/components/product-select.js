import $ from 'jquery';
import 'velocity-animate';

export default class ProductSelect {
  init() {
    $('body').on('click','.js-modal-thumb', (event) => {
      $('.js-modal-thumb.selected').removeClass('selected');
      $(event.currentTarget).addClass('selected');

      $('.js-modal-product-cover').attr('src', $(event.target).data('image-large-src'));
    });

    let $arrows =   $('.js-modal-arrows');
    let $thumbnails = $('.js-modal-product-images');

    var $imgModal = $('.js-modal-product-cover'),
        c_w = $('.js-modal-product-cover').offsetWidth,
        c_h = $('.js-modal-product-cover').offsetHeight,
        t_w = $('.js-modal-thumb:first').offsetWidth,
        t_h = $('.js-modal-thumb:first').offsetHeight,
        w_p = 40,
        thumb_padding = 10;

    var max_thumb = Math.floor((c_h - w_p) / (t_h + 2 + thumb_padding)),
        thumb_padding = thumb_padding + Math.floor(((c_h - w_p) % (t_h + 2 + thumb_padding)) / max_thumb);
    
    $('.js-modal-mask').css('max-height', (max_thumb * (t_h + 2 + thumb_padding)) + 'px');
    $('.js-thumb-container').css('padding-top', (thumb_padding / 2) + 'px').css('padding-bottom', (thumb_padding / 2) + 'px');

    if ($('.js-modal-product-images li').length > max_thumb) {
      $arrows.removeClass('hidden-xs-up');
      $('.js-modal-wrapper').removeClass('nomargin');

      $arrows.on('click', (event) => {
        if ($(event.target).hasClass('arrow-up') && $thumbnails.position().top < 0) {
          this.move('up');
          $('.js-modal-arrow-down').css('opacity','1');
        } else if ($(event.target).hasClass('arrow-down') && $thumbnails.position().top + $thumbnails.height() >  $('.js-modal-mask').height()) {
          this.move('down');
          $('.js-modal-arrow-up').css('opacity','1');
        }
      });
    } else {
      $arrows.addClass('hidden-xs-up');
      $('.js-modal-wrapper').addClass('nomargin');
    }
  }

  move(direction) {
    var $thumbnails = $('.js-modal-product-images');
    var thumbHeight = $('.js-modal-product-images li').outerHeight();
    var currentPosition = $thumbnails.position().top;
    $thumbnails.velocity({
      translateY: (direction === 'up') ? currentPosition + thumbHeight : currentPosition - thumbHeight
    },function(){
      if ($thumbnails.position().top >= 0) {
        $('.js-modal-arrow-up').css('opacity','.2');
      } else if ($thumbnails.position().top + $thumbnails.height() <=  $('.js-modal-mask').height()) {
        $('.js-modal-arrow-down').css('opacity','.2');
      }
    });
  }
}
