/**
 * Module My Easy ERP Web In Color 
 * 
 *  @author    Web In Color - addons@webincolor.fr
 *  @version 2.6
 *  @uses Prestashop modules
 *  @since 1.0 - mai 2014
 *  @package Wic ERP
 *  @copyright Copyright &copy; 2014, Web In Color
 *  @license   http://www.webincolor.fr
 */
(function () {
	var WIC_ERP, ConfigStep, modelNb;

	// Turn a string like 'foo' to 'Foo'
	String.prototype.capitalize = function () {
		return this.charAt(0).toUpperCase() + this.slice(1).toLowerCase();
	};

	// Turn a string like 'foo_bar' to 'fooBar'
	String.prototype.toCamelCase = function () {
		var words;
		words = this.toLowerCase().split(/[-_]/);
		return words[0] + words.slice(1).map(function (text) {
			return text.capitalize();
		}).join('');
	};

	// Turn a string like 'fooBar' to 'foo_bar'
	String.prototype.toUnderScore = function () {
		return this.replace(/[A-Z]/g, function (letter) {
			return '_' + letter.toLowerCase();
		});
	};

	WIC_ERP = (function () {
		'use strict';
		var stepNb,			
			errorsNb,
			$modal,
			$progressBar,
			$stepsUl,
			configSteps = [],
			firstConfigStepOk = false;

		return {
			init: function () {
				$('input[rel="txtTooltip"]').tooltip();
                                
                                $('.displayNone').parents('div.form-group').css('display','none');
                                $('.displayNone').parents('div.margin-form').css('display','none');
                                $('.displayNone').parents('div.margin-form').prev('label').css('display','none');
                                
                                $('.displayNoneFieldset').parents('div[id^="fieldset"]').css('display','none');
                                $('.displayNoneFieldset').parents('fieldset').css('display','none');
                                $('.displayNoneFieldset').parents('fieldset').prev('label').css('display','none');
                                
				// Setup values
				$modal = $('#wic_erp-modal');
				$progressBar = $('#wic_erp-pb');
				$stepsUl = $('#wic_erp-stepsUl');

				$("#module_form").submit(function (e) {
					e.preventDefault();
					var $form = $(this),
						postData = $(this).serializeArray(),
						formURL = $(this).attr("action");

					// Initialization
					errorsNb = 0;
					$stepsUl.empty();
					$progressBar.removeClass('progress-bar-success progress-bar-danger').css('width', '0%').attr('aria-valuenow', 0);

					configSteps = [
						new ConfigStep('updateConfig', postData),
						new ConfigStep('updateSuppliers', postData),
						new ConfigStep('analyzeOrders', {offset: 0}, true),
						new ConfigStep('calculateStock', {
							'WIC_ERP_STOCK_MANAGEMENT': $('[name="WIC_ERP_STOCK_MANAGEMENT"]').val(),
							'updateProducts': $('[name="updateProducts"]').val(),
							'offset': 0
						}, true)
					];

					var i, len, name, step, translation;

					for (name in stepsTranslations) {
						translation = stepsTranslations[name];
						for (i = 0, len = configSteps.length; i < len; i++) {
							step = configSteps[i];
							if (step.name === name) {
								step.translation = translation;
							}
						}
					}

					stepNb = configSteps.length;

					$modal.modal();
					WIC_ERP.updateConfiguration();
				});
			},
			updateProgressBar: function () {
				var percent = ((stepNb - configSteps.length) / stepNb) * 100 + '%';
				$progressBar.animate({width: percent}, 400).text(percent).attr('aria-valuenow', parseInt(percent, 10));

				if (!firstConfigStepOk)
					$progressBar.addClass('progress-bar-danger');

				// If update is complete
				if (configSteps.length)
					return true;

				$progressBar.addClass(errorsNb ? 'progress-bar-danger' : 'progress-bar-success');
				$modal.find('.pleasewait').fadeOut('slow');
			},
			updateConfiguration: function () {
				var $label,
					$req,
					ajaxOptions,
					currentStep;

				if (!configSteps.length)
					return;

				// Set variables
				currentStep = configSteps.shift();

				ajaxOptions = {
					dataType: "json",
					url: document.location.href + '&action=' + currentStep.action,
					type: currentStep.data ? 'POST' : 'GET',
					data: currentStep.data || undefined
				};

				$label = $('<span />', {
					'class': 'label label-info',
					text: currentStep.translation,
					title: $('.pleasewait').first().text()
				});
				// Add the label to the list
				$stepsUl.append($('<li />').append($label));
				
				if (currentStep.isMultiple)
					return currentStep.update($label);

				$req = $.ajax(ajaxOptions);

				// Request status_code == 200
				$req.done(function (data) {
					if (!data.hasOwnProperty('message'))
						return console.error(data);

					if (configSteps.length === (stepNb - 1)) // If it's the first mandatory step
					{
						if (!data.hasOwnProperty('success') || !data.hasOwnProperty('modelNb'))
							return alert('Error in configuration step');
						firstConfigStepOk = data.success;
						modelNb = data.modelNb;
					}

					$label.attr('title', data.message).removeClass('label-info')
						.addClass(data.success ? 'label-success' : 'label-danger')
						.tooltip();

					if (!data.success)
						errorsNb++;
				});

				// If Ajax request fail (http_code != 200)
				$req.fail(function (jqXHR, textStatus) {
					WIC_ERP.handleError(jqXHR, textStatus, $label);
				});

				// Whatever request result, do
				$req.always(function () {
					WIC_ERP.updateProgressBar();
					if (configSteps.length && firstConfigStepOk)
						WIC_ERP.updateConfiguration(configSteps);
				});
			},
			handleError: function (jqXHR, textStatus, $label) {
				errorsNb++;
				console.error(jqXHR.status + ': ' + textStatus);
				$('#wic_erp-modal').animate({marginTop: 0}, 400).find('.modal-body').append($('<hr/>')).append($('<pre/>').html(jqXHR.responseText));
				$label.attr('title', textStatus).removeClass('label-info').addClass('label-danger').tooltip();
			}
		};
	}());

	ConfigStep = (function () {
		function ConfigStep(name, data1, isMultiple) {
			this.name = name;
			this.data = data1;
			this.isMultiple = isMultiple != null ? isMultiple : false;
			this.action = this.name.toUnderScore();
		}

		ConfigStep.prototype.update = function ($label) {
			var $req, currentModelNb;
			currentModelNb = modelNb[this.name];
			
			// Ajax request
			$req = $.ajax({
				dataType: "json",
				url: document.location.href + '&action=' + this.action,
				type: 'POST',
				data: this.data,
				success: (function (_this) {
					return function (data) {
						var labelText, progress, stepName;
						_this.data.offset = data.offset;
						if (_this.data.offset > currentModelNb) {
							_this.data.offset = currentModelNb;
						}
						progress = (_this.data.offset / currentModelNb) * 100;
						stepName = $label.text().split(':')[0];
						labelText = isNaN(progress) ? stepName : stepName + ": " + (progress.toFixed(2)) + "%";
						$label.text(labelText);
						
						if ((_this.data.offset < currentModelNb) && data.needUpdate) {
							return _this.update($label);
						} else {
							$label.attr('title', data.message).removeClass('label-info').addClass('label-success').tooltip();
							WIC_ERP.updateProgressBar();
							return WIC_ERP.updateConfiguration();
						}
					};
				})(this),
				error: function (jqXHR, textStatus) {
					return WIC_ERP.handleError(jqXHR, textStatus, $label);
				}
			});
			return $req;
		};

		return ConfigStep;

	})();

	$(document).ready(WIC_ERP.init);
}).call(this);