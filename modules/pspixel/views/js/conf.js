/*
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author PrestaShop SA <contact@prestashop.com>
 *  @copyright  2007-2017 PrestaShop SA
 *  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

// Main Function
var Main = function () {

  /**
   ** Click Event
   */
  var runEvent = function () {

    // Click on Panel
    $('#modulecontent .tab-content h3 a').live('click', function (e) {
      e.preventDefault();
      var collapse = $(this).attr('data-toggle');
      if (typeof(collapse) !== "undefined" && collapse === 'collapse') {
        var id = $(this).attr('href');
        var is_collapse = false;

        $(this.attributes).each(function() {
          if (this.nodeName === 'class') {
            if(this.nodeValue === '') {
              is_collapse = true;
            }
          }
        });

        if ($(this).attr('class') === undefined) {
          is_collapse = true;
        }
      }
    });

    // Tab panel active
    $(".list-group-item").on('click', function() {
      var $el = $(this).parent().closest(".list-group").children(".active");
      if ($el.hasClass("active")) {
        $el.removeClass("active");
        $(this).addClass("active");
      }
    });

    // Switch tab when click on "contact us"
    $(".contactus").on('click', function() {
			$href = $.trim($(this).attr('href'));
			$(".list-group a.active").each(function() {
				$(this).removeClass("active");
			});

			$(".contactus").addClass("active");
		});

    // Active Tab config
    var is_submit = $("#modulecontent").attr('role');
    if (is_submit >= 1) {
      $(".list-group-item").each(function() {
        if ($(this).hasClass('active')) {
          $(this).removeClass("active");
        }
        else if ($(this).attr('href') == "#conf") {
          $(this).addClass("active");
        }
      });
      $('#conf').addClass("active");
      $('#documentation').removeClass("active");

      $('#collapsein'+is_submit).trigger("click");
    }
  };

  return {
    init: function () {
      runEvent();
    }
  };
}();

// Load functions
$(window).load(function() {
  Main.init();
});
