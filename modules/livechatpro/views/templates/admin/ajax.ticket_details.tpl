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
{if !empty($ticket_details)}

<script type="text/javascript">
var lcp_id_ticket = "{$ticket_details.id_ticket|escape:'quotes':'UTF-8'}";
var lcp_ticket_id_staffprofile = "{$ticket_details.id_staffprofile|escape:'quotes':'UTF-8'}";
var lcp_ticket_id_customer = "{$ticket_details.id_customer|escape:'quotes':'UTF-8'}";
</script>

<!-- <input type="hidden" name="lcp_id_ticket" id="lcp_id_ticket" value="{$ticket_details.id_ticket|escape:'quotes':'UTF-8'}" autocomplete="off">
<input type="hidden" name="lcp_ticket_id_staffprofile" id="lcp_ticket_id_staffprofile" value="{$ticket_details.id_staffprofile|escape:'quotes':'UTF-8'}" autocomplete="off">
<input type="hidden" name="lcp_ticket_id_customer" id="lcp_ticket_id_customer" value="{$ticket_details.id_customer|escape:'quotes':'UTF-8'}" autocomplete="off"> -->

<table class="table lcp panel">
	<tr>
		<td width="25%">
			{lang s='Department:'}
		</td>
		<td width="25%">
			<select class="form-control" name="lcp_ticket_department" id="lcp_ticket_department">
				{foreach from=$departments key=key item=value}
				<option value="{$value.id_department|escape:'quotes':'UTF-8'}" {if $ticket_details.id_department == $value.id_department}selected{/if}>{$value.name|escape:'quotes':'UTF-8'}</option>
				{/foreach}
			</select>
		</td>
		<td width="25%">
			{lang s='Priority:'}
		</td>
		<td width="25%">
			<select class="form-control" name="lcp_ticket_priority" id="lcp_ticket_priority">
				<option value="Low" {if $ticket_details.priority == 'Low'}selected{/if}>{lang s='Low'}</option>
				<option value="Medium" {if $ticket_details.priority == 'Medium'}selected{/if}>{lang s='Medium'}</option>
				<option value="High" {if $ticket_details.priority == 'High'}selected{/if}>{lang s='High'}</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="25%">
			{lang s='Subject:'}
		</td>
		<td width="25%">
			<input type="text" name="lcp_ticket_subject" id="lcp_ticket_subject" class="form-control" value="{$ticket_details.subject|escape:'quotes':'UTF-8'}">
		</td>
		<td width="25%">
			{lang s='Client:'}
		</td>
		<td width="25%">
			{$ticket_details.customer_name|escape:'quotes':'UTF-8'}
		</td>
	</tr>
	<tr>
		<td width="25%">
			{lang s='Status:'}
		</td>
		<td width="25%">
			<select class="form-control" name="lcp_ticket_status" id="lcp_ticket_status">
				<option value="Open" {if $ticket_details.status == 'Open'}selected{/if}>{lang s='Open'}</option>
				<option value="Answered" {if $ticket_details.status == 'Answered'}selected{/if}>{lang s='Answered'}</option>
				<option value="Customer-Reply" {if $ticket_details.status == 'Customer-Reply'}selected{/if}>{lang s='Customer-Reply'}</option>
				<option value="In-Progress" {if $ticket_details.status == 'In-Progress'}selected{/if}>{lang s='In-Progress'}</option>
				<option value="Closed" {if $ticket_details.status == 'Closed'}selected{/if}>{lang s='Closed'}</option>
			</select>
		</td>
		<td width="25%">
			{lang s='Staff:'}
		</td>
		<td width="25%">
			{$ticket_details.staff_name|escape:'quotes':'UTF-8'}
		</td>
	</tr>
	<tr>
		<td colspan="4" align="center">
			<a href="javascript:{}" name="lcp_ticket_save_a" id="lcp_ticket_save_a" class="lcp button" title="{lang s='Save'}"><span class="fa fa-save"></span> {lang s='Save'}</a>
		</td>
	</tr>
</table>


<br>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-centered lcp panel">
	<div><textarea id="lcp_ticket_reply_textarea" name="lcp_ticket_reply_textarea" rows="2"></textarea></div>
	<div style="text-align: center; padding-top: 5px;"><a href="javascript:{}" name="lcp_ticket_add_reply_a" id="lcp_ticket_add_reply_a" class="lcp button" title="{lang s='Add reply'}"><span class="fa fa-plus"></span> {lang s='Add reply'}</a></div>
</div>

<div class="clearfix"></div>
<br>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 lcp panel">
	{if !empty($ticket_details.replyes)}
		{foreach from=$ticket_details.replyes key=key item=value}
			{if $value.reply_from == 'Staff'}
			<table class="table">
					<tr>
						<td width="25%" style="color: red;">
							<b>Staff:</b><br>{$ticket_details.staff_name|escape:'quotes':'UTF-8'}<br>{$ticket_details.staff_email|escape:'quotes':'UTF-8'}
						</td>
						<td>
							{$value.message|escape:'quotes':'UTF-8'}
						</td>
					</tr>
				</table>
				
			{else}
				<table class="table">
					<tr>
						<td width="25%">
							<b>Client:</b><br>{$ticket_details.customer_name|escape:'quotes':'UTF-8'}<br>{$ticket_details.customer_email|escape:'quotes':'UTF-8'}
						</td>
						<td>
							{$value.message|escape:'quotes':'UTF-8'}
						</td>
					</tr>
				</table>
			{/if}
		{/foreach}
	{else}
		{lang s='There are no replyes for this ticket!'}
	{/if}
</div>

{/if}

