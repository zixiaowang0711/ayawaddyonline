/*
* ProQuality (c) All rights reserved.
*
* DISCLAIMER
*
* Do not edit, modify or copy this file.
* If you wish to customize it, contact us at addons4prestashop@gmail.com.
*
* @author    Andrei Cimpean (ProQuality) <addons4prestashop@gmail.com>
* @copyright 2015-2016 ProQuality
* @license   Do not edit, modify or copy this file
*/
(function() {

    // Load the script
    if(!window.jQuery)
	{
	    var script = document.createElement("SCRIPT");
	    script.src = lcp_url+'libraries/jquery/jquery-1.10.2.js';
	    script.type = 'text/javascript';
	    document.getElementsByTagName("head")[0].appendChild(script);
	}

    // Poll for jQuery to come into existance
    var checkReady = function(callback) {
        if (window.jQuery) {
            callback(jQuery);
        }
        else {
            window.setTimeout(function() { checkReady(callback); }, 100);
        }
    };

// Start polling...
checkReady(function($) {

	$.loadJSArray = function(arr, path) {
	    var _arr = $.map(arr, function(scr) {
	        return $.getScript( (path||"") + scr );
	    });

	    _arr.push($.Deferred(function( deferred ){
	        $( deferred.resolve );
	    }));

	    return $.when.apply($, _arr);
	}

	$.loadCSSArray = function(arr, path) {
	    var _arr = $.map(arr, function(scr) {
	         return $('head').append('<link rel="stylesheet" type="text/css" href="' + (path||"") + scr + '">');
	         //return $.getScript( (path||"") + scr );
	    });

	    _arr.push($.Deferred(function( deferred ){
	        $( deferred.resolve );
	    }));

	    return $.when.apply($, _arr);
	}


	////////////////////////////////////////////////////////////////////////////////////////////////////

	$(document).ready(function()
	{
		// css
		css_arr = [];
		css_arr.push(lcp_url+'views/css/lcp.css');
		$.loadCSSArray(css_arr);

		// js
		js_arr = [];
		js_arr.push(lcp_url+'views/js/lcp.js');

		$.loadJSArray(js_arr).done(function() 
		{
			console.log('loaded');
		});

	}); // end document ready

	////////////////////////////////////////////////////////////////////////////////////////////////////

}); // end check readyu use jquery inside

})();

