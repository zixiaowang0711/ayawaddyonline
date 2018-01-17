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
	<table id="visitor_ratings_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
		<thead>
			<tr>
				<th>{lang s='Politness'}</th>
				<th>{lang s='Qualification'}</th>
				<th>{lang s='Date add'}</th>
				<th>{lang s='Internal'}</th>
				<th>{lang s='Name'}</th>
				<th>{lang s='Email'}</th>
				<th>{lang s='Company'}</th>
				<th>{lang s='Comment'}</th>
				<th>{lang s='Status'}</th>
			</thead>
			<tfoot>
			<tr>
				<th>{lang s='Politness'}</th>
				<th>{lang s='Qualification'}</th>
				<th>{lang s='Date add'}</th>
				<th>{lang s='Internal'}</th>
				<th>{lang s='Name'}</th>
				<th>{lang s='Email'}</th>
				<th>{lang s='Company'}</th>
				<th>{lang s='Comment'}</th>
				<th>{lang s='Status'}</th>
			</tr>
			</tfoot>
		</table>
	</div>