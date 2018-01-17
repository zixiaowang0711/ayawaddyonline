{*
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
*}
{assign var=template value="`$smarty.current_dir`\\`$smarty.template`"}
<!-- <script type="text/javascript" src="{$http_or_https|escape:'quotes':'UTF-8'}maps.google.com/maps/api/js?sensor=false"></script> -->
{if !empty($visitor_details)}

<script type="text/javascript">
var lcp_visitor_geotracking_id_visitor = "{$visitor_details.id_visitor|escape:'quotes':'UTF-8'|default:''}";
var lcp_visitor_geotracking_latitude = "{$visitor_details.tracking_info.latitude|escape:'quotes':'UTF-8'|default:'0.00'}";
var lcp_visitor_geotracking_longitude = "{$visitor_details.tracking_info.longitude|escape:'quotes':'UTF-8'|default:'0.00'}";
var lcp_visitor_geotracking_city = "{$visitor_details.tracking_info.city|escape:'quotes':'UTF-8'|default:'unknown'}";
var lcp_visitor_geotracking_country = "{$visitor_details.tracking_info.country|escape:'quotes':'UTF-8'|default:'unknown'}";
</script>

<!-- <input type="hidden" name="visitor_geotracking_id_visitor" id="visitor_geotracking_id_visitor" value="{$visitor_details.id_visitor|escape:'quotes':'UTF-8'|default:''}">
<input type="hidden" name="visitor_geotracking_latitude" id="visitor_geotracking_latitude" value="{$visitor_details.tracking_info.latitude|escape:'quotes':'UTF-8'|default:'0.00'}">
<input type="hidden" name="visitor_geotracking_longitude" id="visitor_geotracking_longitude" value="{$visitor_details.tracking_info.longitude|escape:'quotes':'UTF-8'|default:'0.00'}">
<input type="hidden" name="visitor_geotracking_city" id="visitor_geotracking_city" value="{$visitor_details.tracking_info.city|escape:'quotes':'UTF-8'|default:'unknown'}">
<input type="hidden" name="visitor_geotracking_country" id="visitor_geotracking_country" value="{$visitor_details.tracking_info.country|escape:'quotes':'UTF-8'|default:'unknown'}"> -->
<div style="height: 300px; overflow-y: scroll; overflow-x: hidden;">

	{lang s='This user is located in:'} <b>{lang s='City:'} {$visitor_details.tracking_info.city|escape:'quotes':'UTF-8'|default:'unknown'}, {lang s='Country:'} {$visitor_details.tracking_info.country|escape:'quotes':'UTF-8'|default:'unknown'}</b>

	<div id="geotracking_map_{$key|escape:'quotes':'UTF-8'}">
		<div style="overflow:hidden;height:255px;width:100%;">
			<div id="gmap_canvas_{$key|escape:'quotes':'UTF-8'}" style="height:255px;width:100%;"></div>
			<style>#gmap_canvas_{$key|escape:'quotes':'UTF-8'} {literal}img{max-width:none !important;background:none !important}{/literal}</style>
		</div>
	</div>

</div>

<script type="text/javascript">
{literal}
try
{
	if (typeof google !== 'object') 
	{
		$.getScript("{/literal}{$http_or_https|escape:'quotes':'UTF-8'}{literal}maps.google.com/maps/api/js?sensor=false");
	}

	function init_map_{/literal}{$key|escape:'quotes':'UTF-8'}{literal}() {
				
		console.log("map loaded: {/literal}{$key|escape:'quotes':'UTF-8'}{literal}");
		//console.log("key: {/literal}{$key|escape:'quotes':'UTF-8'}{literal}");
		
		var myOptions = {
			zoom: 14,
			center: new google.maps.LatLng({/literal}{$visitor_details.tracking_info.latitude|escape:'quotes':'UTF-8'|default:'0.00'}{literal}, {/literal}{$visitor_details.tracking_info.longitude|escape:'quotes':'UTF-8'|default:'0.00'}{literal}),
			mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map_{/literal}{$key|escape:'quotes':'UTF-8'}{literal} = new google.maps.Map(document.getElementById("gmap_canvas_{/literal}{$key|escape:'quotes':'UTF-8'}{literal}"), myOptions);
			marker = new google.maps.Marker({
			map: map_{/literal}{$key|escape:'quotes':'UTF-8'}{literal},
			position: new google.maps.LatLng({/literal}{$visitor_details.tracking_info.latitude|escape:'quotes':'UTF-8'|default:'0.00'}{literal}, {/literal}{$visitor_details.tracking_info.longitude|escape:'quotes':'UTF-8'|default:'0.00'}{literal})
			});
			infowindow = new google.maps.InfoWindow({
			content: "{/literal}{$visitor_details.tracking_info.city|escape:'quotes':'UTF-8'|default:'unknown'}{literal}, {/literal}{$visitor_details.tracking_info.country|escape:'quotes':'UTF-8'|default:'unknown'}{literal}"
			});
			google.maps.event.addListener(marker, "click", function() {
			infowindow.open(map_{/literal}{$key|escape:'quotes':'UTF-8'}{literal}, marker);
			});
			//google.maps.event.addDomListener(document.getElementById("geotracking_span_{/literal}{$key|escape:'quotes':'UTF-8'}{literal}"), 'click', reload_map_{/literal}{$key|escape:'quotes':'UTF-8'}{literal});
			//google.maps.event.addDomListener(document.getElementById('tabs-visitordetails-geotracking-a'), 'click', reload_map_{/literal}{$key|escape:'quotes':'UTF-8'}{literal});
				google.maps.event.addDomListener(document.getElementById('gmap_canvas_{/literal}{$key|escape:'quotes':'UTF-8'}{literal}'), 'mouseover', reload_map_{/literal}{$key|escape:'quotes':'UTF-8'}{literal});
			//google.maps.event.addDomListener(window, 'load', init_map);
	
	}
	
	function reload_map_{/literal}{$key|escape:'quotes':'UTF-8'}{literal}() {
		try {
			setTimeout(function(){ google.maps.event.trigger(map_{/literal}{$key|escape:'quotes':'UTF-8'}{literal}, 'resize'); }, 500);
			console.log('map reloaded: {/literal}{$key|escape:'quotes':'UTF-8'}{literal}');
		} catch(e){}
	}

	init_map_{/literal}{$key|escape:'quotes':'UTF-8'}{literal}();

} catch(e){}

{/literal}
</script>
{/if}