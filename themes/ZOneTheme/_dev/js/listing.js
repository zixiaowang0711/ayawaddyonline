import $ from 'jquery';
import prestashop from 'prestashop';
import 'velocity-animate';

/* Ajax Add to cart */
function ajaxAddToCart() {
  if (prestashop.configuration.is_catalog === false) {
    $('body').on('click', '.js-ajax-add-to-cart', function (event) {
      event.preventDefault();

      var $btn = $(this);
      $btn.removeClass('added').addClass('disabled');

      var query = 'id_product=' + $btn.data('id-product') + '&add=1&action=update&token=' + prestashop.static_token;
      var actionURL = prestashop.urls.pages.cart;

      $.post(actionURL, query, null, 'json').then(function (resp) {
        prestashop.emit('updateCart', {
          reason: {
            idProduct: resp.id_product,
            idProductAttribute: resp.id_product_attribute,
            linkAction: 'add-to-cart'
          }
        });
        $btn.removeClass('disabled').addClass('added');
      }).fail(function (resp) {
        prestashop.emit('handleError', { eventType: 'addProductToCart', resp: resp });
      });

      return false;
    });
  }
}


/* Quickview */
function quickviewFunction() {
  prestashop.on('clickQuickView', function (elm) {
    let data = {
      'action': 'quickview',
      'id_product': elm.dataset.idProduct,
      'id_product_attribute': elm.dataset.idProductAttribute,
    };
    $.post(prestashop.urls.pages.product, data, null, 'json').then(function (resp) {
      $('body').append(resp.quickview_html);
      let productModal = $('#quickview-modal-'+resp.product.id+'-'+resp.product.id_product_attribute);
      productModal.modal('show');
      productConfig(productModal);
      productModal.on('hidden.bs.modal', function () {
        productModal.remove();
      });
    }).fail((resp) => {
      prestashop.emit('handleError', {eventType: 'clickQuickView', resp: resp});
    });
  });

  var productConfig = (qv) => {
    var $mask = qv.find('.js-qv-mask'),
        $arrows = qv.find('.scroll-box-arrows'),
        $productImages = qv.find('.js-qv-product-images'),
        $thumbnails = qv.find('.js-qv-product-images .js-thumb'),
        $cover = qv.find('.js-qv-product-cover'),
        $src = qv.find('.js-product-attributes-source'),
        $dest = qv.find('.js-product-attributes-destination'),
        $src2 = qv.find('.js-product-availability-source'),
        $dest2 = qv.find('.js-product-availability-destination');

    if ($src.length) {
      $dest.html($src.html());
      $src.remove();
    } else {
      $dest.empty();
    }
    if ($src2.length) {
      $dest2.html($src2.html());
      $src2.remove();
    } else {
      $dest2.empty();
    }

    $thumbnails.on('click', (event) => {
      $thumbnails.removeClass('selected');
      $(event.currentTarget).addClass('selected');
      $cover.attr('src', $(event.currentTarget).data('image'));
    });

    $mask.addClass('scroll');
    $mask.scrollbox({
      direction: 'h',
      distance: 'auto',
      autoPlay: false,
      infiniteLoop: false,
      onMouseOverPause: false,
    });
    $arrows.find('.left').click(function () {
      $mask.trigger('backward');
    });
    $arrows.find('.right').click(function () {
      $mask.trigger('forward');
    });

    setTimeout(function(){ 
      var thumbsWidth = 0;
      $productImages.find('.js-thumb-container').each(function() {
        thumbsWidth = thumbsWidth + $(this).outerWidth();
      });
      if (($productImages.width() + 5) < thumbsWidth) {
        $arrows.addClass('scroll');
      } else {
        $arrows.removeClass('scroll');
      }
    }, 500);

    qv.find('#quantity_wanted').TouchSpin({
      verticalbuttons: true,
      verticalupclass: 'material-icons touchspin-up',
      verticaldownclass: 'material-icons touchspin-down',
      buttondown_class: 'btn btn-touchspin js-touchspin',
      buttonup_class: 'btn btn-touchspin js-touchspin',
      min: 1,
      max: 1000000
    });
  };
}

/* Search filters - Facets */
function searchFiterFacets() {
  var dataGrid = $('#js-product-list').data('grid-columns'),
      storage =  window.localStorage || window.sessionStorage;

  const parseSearchUrl = function (event) {
    if (event.target.dataset.searchUrl !== undefined) {
      return event.target.dataset.searchUrl;
    }

    if ($(event.target).parent()[0].dataset.searchUrl === undefined) {
      throw new Error('Can not parse search URL');
    }

    return $(event.target).parent()[0].dataset.searchUrl;
  };

  $('body').on('change', '#search_filters input[data-search-url]', function (event) {
    enablePaceBounce();
    prestashop.emit('updateFacets', parseSearchUrl(event));
  });

  $('body').on('click', '.js-search-filters-clear-all', function (event) {
    enablePaceBounce();
    prestashop.emit('updateFacets', parseSearchUrl(event));
  });

  $('body').on('click', '.js-search-link', function (event) {
    event.preventDefault();
    enablePaceBounce();
    prestashop.emit('updateFacets', $(event.target).closest('a').get(0).href);
  });

  $('body').on('change', '#search_filters select', function (event) {
    const form = $(event.target).closest('form');
    enablePaceBounce();
    prestashop.emit('updateFacets', '?' + form.serialize());
  });

  prestashop.on('updateProductList', (data) => {
    updateProductListDOM(data);

    $('#js-product-list').attr('data-grid-columns', dataGrid);
    $('#js-product-list').find('.js-product-list-view').removeClass('columns-2 columns-3 columns-4 columns-5').addClass(dataGrid);

    if (storage && storage.productListView) {
      $('#product_display_control a[data-view="' + storage.productListView + '"]').trigger('click');
    }

    setTimeout(function() {
      disablePaceBounce();

      if ($('#js-product-list-top').length) {
        $.smoothScroll({
          scrollTarget: '#js-product-list-top',
          offset: -50,
        });
      }
    }, 500);
  });
}
function enablePaceBounce() {
  $('.js-pending-query').fadeIn();
}
function disablePaceBounce() {
  $('.js-pending-query').fadeOut();
}

function updateProductListDOM (data) {
  $('#search_filters').replaceWith(data.rendered_facets);
  $('#js-active-search-filters').replaceWith(data.rendered_active_filters);
  $('#js-product-list-top').replaceWith(data.rendered_products_top);
  $('#js-product-list').replaceWith(data.rendered_products);
  $('#js-product-list-bottom').replaceWith(data.rendered_products_bottom);
}

/* Grid - List - Table */
function productDisplayControl() {
  var displayControl = '#product_display_control a';
  var storage =  window.localStorage || window.sessionStorage;

  $('body').on('click', displayControl, function (event) {
    event.preventDefault();
    var view = $(this).data('view');
    $(displayControl).removeClass('selected');
    $(this).addClass('selected');
    $('.js-product-list-view').removeClass('grid list table-view').addClass(view);
    
    try {
      storage.setItem('productListView', view);
    }
    catch (error) {
      console.log('Can not cache the product list view');
    }
  });

  if (storage.productListView) {
    $(displayControl + '[data-view="' + storage.productListView + '"]').trigger('click');
  } else {
    $(displayControl + '.selected').trigger('click');
  }
}

/* Mobile search filter toggle */
function mobileSearchFilterToggle() {
  $('body').on('click', '#search_filter_toggler', function () {
    $('#_mobile_search_filters').stop().slideToggle();
  });
  $('#search_filter_controls .ok').on('click', function () {
    $('#_mobile_search_filters').stop().slideUp();
  });
}

$(document).ready(() => {
  quickviewFunction();
  searchFiterFacets();
  productDisplayControl();
  mobileSearchFilterToggle();
  ajaxAddToCart();
});


