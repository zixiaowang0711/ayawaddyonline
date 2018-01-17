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
<img border="0" src="{$module_path|escape:'quotes':'UTF-8'}views/img/client-mouse-cursor.gif" width="30" height="30" id="client_coursor_img" style="position : absolute; z-index: 9999;">

<div class="row">
	{lang s='This user is browsing:'}<a href="{$tracking_data.current_page|escape:'quotes':'UTF-8'}" id="url_a" target="_blank">{$tracking_data.current_page|escape:'quotes':'UTF-8'}</a> 
</div>

<div class="row">
	<div id="mousetracking_iframe_{$key|escape:'quotes':'UTF-8'}" style="height: 100%; overflow: auto;">
		<div id="wrapper" style="position: relative; height: 98%;">
			<div id="block" style="position: absolute; top: 0; left: 0; width: 98%; height: 100%;"></div>
			<iframe src="{$tracking_data.current_page|escape:'quotes':'UTF-8'}" frameborder="0" width="100%" height="100%" id="url_iframe_{$key|escape:'quotes':'UTF-8'}"></iframe>
		</div>
	</div>
</div>


<!--
<ul>
	<li><a href="#tabs-mousetracking-1">{lang s='Real Time:'} <b>User1</b></a></li>
	<table border="0"  bgcolor="#F7F7F7" class="lcp radius5">
	<tr>
	<td align="left">&nbsp;</td>
	<td align="left" style="padding-right: 5px">
	<a href="javascript:{}">{lang s='Full Screen'}</a></td>
	</tr>
	</table>
	</span>
	<li>
	<a href="#tabs-mousetracking-2">{lang s='History (All users)'}</a>
	</li>
	<span style="float: right;">
	<table border="0"  bgcolor="#F7F7F7" class="lcp radius5">
	<tr>
	<td align="left"><input type="checkbox" name="C1" value="ON"></td>
	<td align="left" style="border-right: 1px solid #DDDDDD; padding-right: 5px">
	<font color="#CC0000">{lang s='Clicks'}</font></td>
	<td align="left"><input type="checkbox" name="C2" value="ON"></td>
	<td align="left" style="border-right: 1px solid #DDDDDD; padding-right: 5px">
	<font color="#3FC813">{lang s='Mouse motion'}</font></td>
	<td align="left" width="100" style="padding-left: 10px; padding-right: 10px;"><div id="transparency"></div></td>
	<td align="left" style="padding-right: 5px">{lang s='Transparency'}</td>
	</tr>
	</table>
</ul>
<div id="tabs-mousetracking-1">
</div>
<div id="tabs-mousetracking-2">
</div>
-->