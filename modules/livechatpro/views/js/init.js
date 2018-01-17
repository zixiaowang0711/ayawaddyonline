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
var livechatpro_config;
(function() {
  var livechatpro_loader = function(src) {
    var script;
    script = document.createElement("script");
    script.setAttribute("type", "text/javascript");
    script.setAttribute("src", src);
    document.getElementsByTagName("head")[0].appendChild(script);
  	return true;
  };

  if (livechatpro_config === undefined )
  {
		livechatpro_config = {
			'api_key': '', 
			'data': ''
		};
		livechatpro_loader(lcp_url+'views/js/loader.js')
  }
}).call(this);

