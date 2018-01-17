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


<!-- <input type="hidden" name="visitor_details_id_visitor" id="visitor_details_id_visitor" value=""> -->
<div style="height: 300px; overflow-y: scroll; overflow-x: hidden;">

	<table class="table table-striped table-hover" width="{if isset($full_width) && $full_width == 'Y'}100%{else}50%{/if}">
		<tr>
			<td align="left">{lang s='Name:'}</td>
			<td id="details_name" align="left">{$visitor_details.name|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Email:'}</td>
			<td id="details_email" align="left">{$visitor_details.email|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Phone:'}</td>
			<td id="details_phone" align="left">{$visitor_details.phone|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Company:'}</td>
			<td id="details_company" align="left">{$visitor_details.company|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Country:'}</td>
			<td id="details_country" align="left">{$visitor_details.country|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='City:'}</td>
			<td id="details_city" align="left">{$visitor_details.city|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Province:'}</td>
			<td id="details_province" align="left">{$visitor_details.province|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='In Chat:'}</td>
			<td id="details_in_chat" align="left">{$visitor_details.in_chat|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Language:'}</td>
			<td id="details_language" align="left">{$visitor_details.language|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Visits:'}</td>
			<td id="details_visits" align="left">{$visitor_details.visits|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Current page:'}</td>
			<td id="details_current_page" align="left">{$visitor_details.current_page|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Host:'}</td>
			<td id="details_host" align="left">{$visitor_details.host|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='IP:'}</td>
			<td id="details_ip" align="left">{$visitor_details.ip|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Browser:'}</td>
			<td id="details_browser" align="left">{$visitor_details.browser|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Timezone:'}</td>
			<td id="details_timezone" align="left">{$visitor_details.timezone|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Resolution:'}</td>
			<td id="details_resolution" align="left">{$visitor_details.resolution|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Online Time:'}</td>
			<td id="details_online_time" align="left">{$visitor_details.online_time|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Referrer:'}</td>
			<td id="details_referrer" align="left">{$visitor_details.referrer|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Page count:'}</td>
			<td id="details_page_count" align="left">{$visitor_details.page_count|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Operating System:'}</td>
			<td id="details_operating_system" align="left">{$visitor_details.os|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
		<tr>
			<td align="left">{lang s='Last visit:'}</td>
			<td id="details_last_visit" align="left">{$visitor_details.last_visit|escape:'quotes':'UTF-8'|default:''}</td>
		</tr>
	</table>
</div>