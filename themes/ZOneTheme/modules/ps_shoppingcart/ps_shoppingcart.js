/* global $, prestashop */

/**
 * This module exposes an extension point in the form of the `showModal` function.
 *
 * If you want to override the way the modal window is displayed, simply define:
 *
 * prestashop.blockcart = prestashop.blockcart || {};
 * prestashop.blockcart.showModal = function myOwnShowModal (modalHTML) {
 *   // your own code
 *   // please not that it is your responsibility to handle closing the modal too
 * };
 *
 * Attention: your "override" JS needs to be included **before** this file.
 * The safest way to do so is to place your "override" inside the theme's main JS file.
 *
 */

$(document).ready(function () {
  prestashop.blockcart = prestashop.blockcart || {};

  var showModal = prestashop.blockcart.showModal || function (modal) {
    var $body = $('body');
    $body.append(modal);
    $body.one('click', '#blockcart-modal', function (event) {
      if (event.target.id === 'blockcart-modal') {
        $(event.target).remove();
      }
    });
  };

  $(document).ready(function () {
    prestashop.on(
      'updateCart',
      function (event) {
        var refreshURL = $('.blockcart').data('refresh-url');
        var requestData = {};

        if (event && event.resp && event.resp.hasError) {
          $('#modalAddToCartMessage').find('.js-modal-content').html(event.resp.errors[0]);
          $('#modalAddToCartMessage').modal('show');
        } else if (event && event.reason && event.reason.idProduct) {
          requestData = {
            id_product_attribute: event.reason.idProductAttribute,
            id_product: event.reason.idProduct,
            action: event.reason.linkAction
          };
        }

        $.post(refreshURL, requestData).then(function (resp) {
          $('.blockcart .cart-header').replaceWith($(resp.preview).find('.blockcart .cart-header'));
          $('.blockcart .cart-dropdown-wrapper').replaceWith($(resp.preview).find('.blockcart .cart-dropdown-wrapper'));
          //$('.blockcart').removeClass('inactive').addClass('active');
          $('.js-sticky-icon-cart').html($(resp.preview).find('.blockcart .js-sticky-cart-source').html());
          
          if ($('.js-sidebar-cart-enabled').length) {
            $('#js-cart-sidebar .cart-dropdown-wrapper').replaceWith($(resp.preview).find('.blockcart .cart-dropdown-wrapper'));
            if (prestashop.page.page_name != 'cart' && prestashop.page.page_name != 'checkout') {
              $('html').addClass('st-effect-right st-menu-open');
            }
          } else {
            if (resp.modal) {
              showModal(resp.modal);
            }
          }
        }).fail(function (resp) {
          prestashop.emit('handleError', {eventType: 'updateShoppingCart', resp: resp});
        });
      }
    );
  });
});
