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
<table id="tickets_grid" class="lcp display compact responsive nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
<thead>
	<tr>
		<th>{lang s='Date add'}</th>
		<th>{lang s='Department'}</th>
		<th>{lang s='Subject'}</th>
		<th>{lang s='Staff'}</th>
		<th>{lang s='Client'}</th>
		<th>{lang s='Last reply'}</th>
		<th>{lang s='Priority'}</th>
		<th>{lang s='Status'}</th>
	</thead>
	<tfoot>
	<tr>
		<th>{lang s='Date add'}</th>
		<th>{lang s='Department'}</th>
		<th>{lang s='Subject'}</th>
		<th>{lang s='Staff'}</th>
		<th>{lang s='Client'}</th>
		<th>{lang s='Last reply'}</th>
		<th>{lang s='Priority'}</th>
		<th>{lang s='Status'}</th>
	</tr>
	</tfoot>
</table>

<div id="dialog-form-ticket-details" title="{lang s='View ticket details'}" style="display:none">
				<div style="width: 1000px; height:400px; overflow-y: scroll;" id="ajax_ticket_details_div">
					{*include file="$module_templates_front_dir/ajax.ticket_details.tpl"*}
				</div>
			</div>

<script type="text/javascript">
{literal}
$( "#dialog-form-ticket-details" ).dialog({
					autoOpen: false,
					/*height: 300,*/
					width: 1015,
					modal: false,
					close : function() {
						/*allFields.val( "" ).removeClass( "ui-state-error" );*/
					}
				});
{/literal}
</script>				