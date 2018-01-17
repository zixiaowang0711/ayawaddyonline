/*
* Public JS for criteo
*
*  @author    Thomas BERLOT - Web In Color <contact@webincolor.fr>
*  @copyright 2012-2017 Web In Color
*  @license   http://www.apache.org/licenses/  Apache License
*  International Registered Trademark & Property of Web In Color
*/

// Main Function
var Main = function () {
    // function to custom select
    var runCustomElement = function () {
        // check submit
        var is_submit = $("#modulecontent").attr('role');

        if (is_submit == 1) {
            $(".list-group-item").each(function() {
                if ($(this).hasClass('active')) {
                    $(this).removeClass("active");
                }
                else if ($(this).attr('href') == "#config") {
                    $(this).addClass("active");
                }
            });
            $('#config').addClass("active");
            $('#informations').removeClass("active");
        }
        if (is_submit == 2) {
            $(".list-group-item").each(function() {
                if ($(this).hasClass('active')) {
                    $(this).removeClass("active");
                }
                else if ($(this).attr('href') == "#options") {
                    $(this).addClass("active");
                }
            });
            $('#support').addClass("active");
            $('#informations').removeClass("active");
        }

        $('.module_confirmation').delay(10000).hide(100);    // Hides 'Configuration Updated' alert after 10 seconds

            // toggle panel
        $(".list-group-item").on('click', function() {
                var $el = $(this).parent().closest(".list-group").children(".active");
                if ($el.hasClass("active")) {
                    $el.removeClass("active");
                    $(this).addClass("active");
                }
        });
    };
    return {
        //main function to initiate template pages
        init: function () {
            runCustomElement();
        }
    };
}();

$(function() {
    // Load functions
    Main.init();
});