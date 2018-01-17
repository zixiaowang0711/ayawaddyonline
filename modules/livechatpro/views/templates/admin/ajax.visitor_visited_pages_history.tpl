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

<div style="height: 299px; overflow-y: scroll; overflow-x: hidden;">
	<table class="table table-striped table-hover" width="100%">
		<tr>
			<td align="center"><b>{lang s='ID'}</b></td>
			<td align="center"><b>{lang s='Time'}</b></td>
			<td align="center"><b>{lang s='Duration'}</b></td>
			<td><b>{lang s='URL'} </b></td>
			<td><b>{lang s='Referrer'}</b></td>
		</tr>
		{if !empty($visitor_details.visited_pages)}
		{foreach from=$visitor_details.visited_pages key=key2 item=value2}
		<tr>
			<td align="center">{$value2.id_visitedpage|escape:'quotes':'UTF-8'}</td>
			<td align="center">{$value2.date_add|escape:'quotes':'UTF-8'}</td>
			<td align="center">{$value2.duration|escape:'quotes':'UTF-8'}</td>
			<td>{$value2.url|escape:'quotes':'UTF-8'}</td>
			<td>{$value2.referrer|escape:'quotes':'UTF-8'}</td>
		</tr>
		{/foreach}
		{/if}
	</table>
</div>