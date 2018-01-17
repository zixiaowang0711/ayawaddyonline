<?php
/* Smarty version 3.1.28, created on 2018-01-17 04:15:28
  from "/var/www/ayawaddyonline/modules/livechatpro/views/templates/admin/ajax.visitor_geotracking.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_5a5ecde0475611_94420308',
  'file_dependency' => 
  array (
    '5841ca2af7a18b4a9b7e45cc6d3aaec46ec55f86' => 
    array (
      0 => '/var/www/ayawaddyonline/modules/livechatpro/views/templates/admin/ajax.visitor_geotracking.tpl',
      1 => 1510628788,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a5ecde0475611_94420308 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '9183035325a5ecde04526c7_34887050';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>
<!-- <?php echo '<script'; ?>
 type="text/javascript" src="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['http_or_https']->value);?>
maps.google.com/maps/api/js?sensor=false"><?php echo '</script'; ?>
> -->
<?php if (!empty($_smarty_tpl->tpl_vars['visitor_details']->value)) {?>

<?php echo '<script'; ?>
 type="text/javascript">
var lcp_visitor_geotracking_id_visitor = "<?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['id_visitor']))===null||$tmp==='' ? '' : $tmp);?>
";
var lcp_visitor_geotracking_latitude = "<?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['latitude']))===null||$tmp==='' ? '0.00' : $tmp);?>
";
var lcp_visitor_geotracking_longitude = "<?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['longitude']))===null||$tmp==='' ? '0.00' : $tmp);?>
";
var lcp_visitor_geotracking_city = "<?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['city']))===null||$tmp==='' ? 'unknown' : $tmp);?>
";
var lcp_visitor_geotracking_country = "<?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['country']))===null||$tmp==='' ? 'unknown' : $tmp);?>
";
<?php echo '</script'; ?>
>

<!-- <input type="hidden" name="visitor_geotracking_id_visitor" id="visitor_geotracking_id_visitor" value="<?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['id_visitor']))===null||$tmp==='' ? '' : $tmp);?>
">
<input type="hidden" name="visitor_geotracking_latitude" id="visitor_geotracking_latitude" value="<?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['latitude']))===null||$tmp==='' ? '0.00' : $tmp);?>
">
<input type="hidden" name="visitor_geotracking_longitude" id="visitor_geotracking_longitude" value="<?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['longitude']))===null||$tmp==='' ? '0.00' : $tmp);?>
">
<input type="hidden" name="visitor_geotracking_city" id="visitor_geotracking_city" value="<?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['city']))===null||$tmp==='' ? 'unknown' : $tmp);?>
">
<input type="hidden" name="visitor_geotracking_country" id="visitor_geotracking_country" value="<?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['country']))===null||$tmp==='' ? 'unknown' : $tmp);?>
"> -->
<div style="height: 300px; overflow-y: scroll; overflow-x: hidden;">

	<?php echo smartyFunctionTranslate(array('s'=>'This user is located in:'),$_smarty_tpl);?>
 <b><?php echo smartyFunctionTranslate(array('s'=>'City:'),$_smarty_tpl);?>
 <?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['city']))===null||$tmp==='' ? 'unknown' : $tmp);?>
, <?php echo smartyFunctionTranslate(array('s'=>'Country:'),$_smarty_tpl);?>
 <?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['country']))===null||$tmp==='' ? 'unknown' : $tmp);?>
</b>

	<div id="geotracking_map_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
">
		<div style="overflow:hidden;height:255px;width:100%;">
			<div id="gmap_canvas_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" style="height:255px;width:100%;"></div>
			<style>#gmap_canvas_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
 img{max-width:none !important;background:none !important}</style>
		</div>
	</div>

</div>

<?php echo '<script'; ?>
 type="text/javascript">

try
{
	if (typeof google !== 'object') 
	{
		$.getScript("<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['http_or_https']->value);?>
maps.google.com/maps/api/js?sensor=false");
	}

	function init_map_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
() {
				
		console.log("map loaded: <?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
");
		//console.log("key: <?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
");
		
		var myOptions = {
			zoom: 14,
			center: new google.maps.LatLng(<?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['latitude']))===null||$tmp==='' ? '0.00' : $tmp);?>
, <?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['longitude']))===null||$tmp==='' ? '0.00' : $tmp);?>
),
			mapTypeId: google.maps.MapTypeId.ROADMAP
			};
			map_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
 = new google.maps.Map(document.getElementById("gmap_canvas_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
"), myOptions);
			marker = new google.maps.Marker({
			map: map_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
,
			position: new google.maps.LatLng(<?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['latitude']))===null||$tmp==='' ? '0.00' : $tmp);?>
, <?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['longitude']))===null||$tmp==='' ? '0.00' : $tmp);?>
)
			});
			infowindow = new google.maps.InfoWindow({
			content: "<?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['city']))===null||$tmp==='' ? 'unknown' : $tmp);?>
, <?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['tracking_info']['country']))===null||$tmp==='' ? 'unknown' : $tmp);?>
"
			});
			google.maps.event.addListener(marker, "click", function() {
			infowindow.open(map_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
, marker);
			});
			//google.maps.event.addDomListener(document.getElementById("geotracking_span_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
"), 'click', reload_map_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
);
			//google.maps.event.addDomListener(document.getElementById('tabs-visitordetails-geotracking-a'), 'click', reload_map_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
);
				google.maps.event.addDomListener(document.getElementById('gmap_canvas_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
'), 'mouseover', reload_map_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
);
			//google.maps.event.addDomListener(window, 'load', init_map);
	
	}
	
	function reload_map_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
() {
		try {
			setTimeout(function(){ google.maps.event.trigger(map_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
, 'resize'); }, 500);
			console.log('map reloaded: <?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
');
		} catch(e){}
	}

	init_map_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
();

} catch(e){}


<?php echo '</script'; ?>
>
<?php }
}
}
