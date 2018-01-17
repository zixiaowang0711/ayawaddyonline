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

<div style="height: 300px; overflow-y: scroll; overflow-x: hidden;">
	<table id="visitor_logs_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
		<thead>
			<tr>
				<th>{lang s='Date add'}</th>
				<th align="left">{lang s='Message'}</th>
			</thead>
			<tfoot>
			<tr>
				<th>{lang s='Date add'}</th>
				<th align="left">{lang s='Message'}</th>
			</tr>
			</tfoot>
		</table>
	</div>