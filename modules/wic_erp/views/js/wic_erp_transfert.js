/* 
 * Module My Easy ERP| Web in Color
 * 
 * wic_erp
 * 
 * DISCLAIMER
 * 
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize our module for your
 * needs please refer to http://www.webincolor.fr for more information.
 * 
 *  @author    Web in Color <addons@webincolor.fr>
 *  @copyright Copyright &copy; 2015, Web In Color
 *  @license   http://www.webincolor.fr
 */

var WIC_ERP = (function () {
	'use strict';
	var $infoArea,
		ajaxUrl,
		translations = {},
		successClass = 'module_confirmation conf confirm',
		errorClass = 'module_error alert',
		waitClass = 'alert alert-info',
		infoAreaOffset,
		$updateLabel,
		upToDate = true;

	return {
		init: function (options) {
			if (typeof options === 'object')
				if (options.hasOwnProperty('translations'))
					$.extend(translations, options.translations);
				else
					console.error('No translations defined');
				
				if(options.hasOwnProperty('ajaxUrl') && options.ajaxUrl !== '')
					ajaxUrl = options.ajaxUrl + '&ajax&action=';
			else
				console.error('Must be used with an object');

			$infoArea = $('#wic_info-area');
			$updateLabel = $('#wic_update-label');

			if (parseFloat(_PS_VERSION_) > 1.5) {
				infoAreaOffset = $infoArea.offset().top + 30;
				successClass += ' alert alert-success';
				errorClass += ' alert-danger';
			} else {
				infoAreaOffset = $infoArea.offset().top;
				errorClass += ' error';
			}
		},
		ajaxReq: function (action, data, callback) {
			$infoArea.stop(true).removeClass(successClass + ' ' + errorClass).addClass(waitClass).text(translations.waitMsg).fadeIn();
			$.ajax({
				dataType: "json",
				type: "POST",
				url: ajaxUrl + action,
				data: data,
				success: function (result) {
					$infoArea.removeClass(waitClass).addClass(result.success ? successClass : errorClass).text(result.message).delay(3000).fadeOut();
					$('html, body').animate({scrollTop: infoAreaOffset}, 'slow');

					if (typeof callback === 'function')
						callback(result);
				},
				error: function (jqXHR, textStatus, errorThrown) {
					var errorMsg = textStatus + ': ' + errorThrown;
					$infoArea.removeClass(waitClass).addClass(errorClass).text(errorMsg).delay(5000).fadeOut();
					$('html, body').animate({scrollTop: infoAreaOffset}, 'slow');
				}
			});

		},
		formUpdate: function (e) {
			e.preventDefault();
			var data = $(this).serializeArray(),
				$refInput = $('[name="ean"]', this),
				$qtyInput = $('[name="quantity"]', this);


			// Check if user can specify quantity
			if ($qtyInput.length)
			{
				var quantity = parseInt($qtyInput.val(), 10);
                                 
				// And if quantity is set and valid
				if (isNaN(quantity) || quantity <= 0)
				{
					// If it doesn't, set the focus on it
					$qtyInput.focus();

					// If value isn't numeric
					if ($qtyInput.val() !== '')
						alert(translations.NaNAlert);

					return false; // And prevent form submit
				}
			}

			// Reset inputs values
			$(this).trigger('reset');
			$refInput.focus();			
			$qtyInput.val(1);
			WIC_ERP.ajaxReq('add_transfert_stock_product', data, function (result) {
				if (!result.success)
					return false;
                                
                                $('#realStockFrom_'+result.id_erp_products).text(result.realStockFrom);
                                $('#realStockTo_'+result.id_erp_products).text(result.realStockTo);
                                $('#stockTransfert_'+result.id_erp_products).text(result.stockTransfer);
                                $('#stockTransferable_'+result.id_erp_products).text(result.stockTransferable);
                                
                                var $tr = $('#stockTransferable_'+result.id_erp_products).parent().parent();
                                
                                if (result.stockTransfer == 0) {
                                    $tr.hide('slow', function(){ $tr.remove(); });
                                }
                                
                                if (result.stockTransferable == 0) {
                                    $tr.hide('slow', function(){ $tr.remove(); });
                                }
			});
		},
		updateOrderQuantity: function (result) {
			if (!result.hasOwnProperty('transfertStockProducts') || !result.inventoryProducts.length)
				return false;

			result.inventoryProducts.forEach(function (inventoryProduct) {
				var $tr = $('[name=real_quantity_' + inventoryProduct.id + ']').parent().parent();
				$.each(inventoryProduct, function (key, val) {
					if (key !== 'id')
						$tr.find('.' + key).text(val);
				});
				$tr.find('.real_quantity').trigger('change');
			});
		},
		updateRealQuantity: function (result) {
			if (!result.success)
				return;

			$updateLabel.text(translations.upToDate).removeClass('label-danger').addClass('label-success');
			upToDate = true;
		},
		autoFill: function () {
			if (!confirm(translations.autofillAlert))
				return;
			$('.wic_transfert_stock_product > tbody tr').each(function () {
				var $input = $(this).find('.real_quantity'),
					theoricalQuantity = parseInt($(this).find('.theorical_quantity').text(), 10);
				if (parseInt($input.val(), 10) === 0)
					$input.val(theoricalQuantity).trigger('change');
			});
		},
		checkUpdate: function (e) {
			if (!upToDate)
				$('.wic_update-inv').trigger('click');

			if (parseInt($('[name="done"]:checked').val(), 10))
				return confirm(translations.updateAlert);
		}
	};
}());