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

<meta name="viewport" content="width=device-width, initial-scale=1.0">
<audio id="newchat" src=""></audio>
<audio id="newmessage" src=""></audio>

<table border="0" cellpadding="5" cellspacing="5" style="right: 0px; bottom: 0px; z-index: 9999; position: fixed !important;">
	<tr>
		
		<td id="" valign="bottom">
			<table border="0">
				<tr id="ajax_chats_div"></tr>
				<tr id="ajax_chats_textarea_div"></tr>
			</table>
		</td>
		
	
		<td style="width: 260px; background-color: white !important; padding: 5px 5px 0px 5px; box-shadow: 0px 0px 5px 0px rgba(66,66,66,0.75);" valign="bottom">
	
		<div class="" style="">
			
			<div class="row lcp panel">
				<div class="col-lg-2 lcp panel-head">{lang s='Status:'}</div>
				<div class="col-lg-9">
					<select name="status_select" id="status_select" class="form-control">
						<option value="Offline" style="color: #606060; background-color: white; font-weight: bolder; padding: 10px;" {if $staff_chat_status == 'offline'}selected{/if}>{lang s='Offline'}</option>
						<option value="Online" style="color: #3fc813; background-color: white; font-weight: bolder;  padding: 10px;" {if $staff_chat_status == 'online'}selected{/if}>{lang s='Online'}</option>
					</select>
				</div>
				<div class="col-lg-1" style="line-height: 25px; vertical-align: middle;"><span id="status_ajax_load_span"><img id="status_img" border="0" src="{$module_path|escape:'quotes':'UTF-8'}views/img/{if $staff_chat_status == 'online'}online{else}offline{/if}-ico.png"></span></div>
			</div>

			<div class="row lcp panel" style="margin: 10px 0px !important;">
			<div id='online_users'>
				<span id="ajax_onlineusers_load_span"></span>
				<div id="ajax_onlineusers_div">
					{* include file="$module_templates_back_dir/ajax.onlineusers.tpl" *}
				</div>
			</div>
			</div>
			
			<div class="row lcp panel" style="margin: 10px 0px !important;">
				<div class="lcp panel-head border-bottom">
					{lang s='Statistics'} <span id="statistics_ajax_load_span" style="padding-left: 5px;">
				</div>
			
				<div id="tabs-statistics">

					<div id="tabs-visitors">
						<table id="onlinevisitors_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
							<thead>
								<tr>
									<th style="display: none;">{lang s='ID'}</th>
									<th>{lang s='Country'}</th>
									<th style="display: none;">{lang s='City'}</th>
									<th style="display: none;">{lang s='Language'}</th>
									<th style="display: none;">{lang s='Visits'}</th>
									<th style="display: none;">{lang s='Current page'}</th>
									<th style="display: none;">{lang s='Host'}</th>
									<th>{lang s='IP'}</th>
									<th style="display: none;">{lang s='Browser'}</th>
									<th style="display: none;">{lang s='Timezone'}</th>
									<th style="display: none;">{lang s='Resolution'}</th>
									<th style="display: none;">{lang s='Referrer'}</th>
									<th style="display: none;">{lang s='Page count'}</th>
									<th style="display: none;">{lang s='OS'}</th>
									<th style="display: none;">{lang s='Last visit'}</th>
								</tr>
							</thead>
						</table>
					</div>
				</div>
			</div>
		

		
	</div>

	</td>

	</tr>
</table>
	
	


<div id="dialog-form-visitordetails" title="{lang s='View visitor details'}" style="display:none" class="bootstrap">
	<div id="tabs-visitordetails">
		<ul>
			<li>
				<a href="#tabs-visitordetails-details">{lang s='Details:'}</a> 
			</li>
			<li>
				<a href="#tabs-visitordetails-visitedpageshistory">{lang s='Visited pages history'}</a>
			</li>
			<li>
				<a href="#tabs-visitordetails-geotracking" id="tabs-visitordetails-geotracking-a">{lang s='GeoTracking'}</a>
			</li>
		</ul>
		<div id="tabs-visitordetails-details">
			{assign var="full_width" value='Y'}
			{include file="$module_templates_back_dir/ajax.visitor_details.tpl"}
		</div>
		<div id="tabs-visitordetails-visitedpageshistory">
			{include file="$module_templates_back_dir/ajax.visitor_visited_pages_history.tpl"}
		</div>
		<div id="tabs-visitordetails-geotracking">
			{include file="$module_templates_back_dir/ajax.visitor_geotracking.tpl"}
		</div>
	</div>
</div>


<div id="dialog-online-internal-users" title="{lang s='Online Staff'}" style="display:none">
	<div id="ajax_online_internal_users_div">
		{include file="$module_templates_back_dir/ajax.online_internal_users.tpl"}
	</div>
</div>

<div id="dialog-predefined-messages" title="{lang s='Predefined Messages'}" style="display:none">
	{if (!empty($predefined_messages))}
	<select name="predefined_messages_select" id="predefined_messages_select" class="lcp formfield form-control">
		{foreach from=$predefined_messages key=key item=value}
			<option value="{$value.id_predefinedmessage|escape:'quotes':'UTF-8'}" style="color: #606060; background-color: white;">{$value.title|escape:'quotes':'UTF-8'}</option>
		{/foreach}
	</select>
	{*<br><br>
	<a href="javascript:{}" name="insert_predefined_message" id="insert_predefined_message" class="lcp button2" title="{lang s='Insert message'}"><span class="fa fa-exchange"></span> {lang s='Insert message'}</a>
	<span id="predefinedmessages_ajax_load_span" style="padding-left: 5px;"></span>*}
	{else}
		{lang s='There are no predefined messages.'}
	{/if}
</div>

<div id="menu-emoticons" style="position:absolute; z-index: 9999; display:none; float:left; clear:both; background: white;" class="lcp panel">
	
	<input type="hidden" name="active_emoticon_menu" id="active_emoticon_menu" value="0">
	<table border="0" width="100%" cellspacing="0" cellpadding="0" class="lcp emoticon-table">
	<tr>
	{section loop=$emoticons name=id}
	   <td align="center" style="text-align: center; width: 40px;">
	   	<input type="hidden" name="emoticon_code" id="emoticon_code_{$emoticons[id].id_emoticon|escape:'quotes':'UTF-8'}" value='{$emoticons[id].code|escape:'quotes':'UTF-8'}'>
	   	<img title='{$emoticons[id].code|escape:'quotes':'UTF-8'}' border="0" src="{$module_path|escape:'quotes':'UTF-8'}views/img/emoticons/{$emoticons[id].filename|escape:'quotes':'UTF-8'}" id="emoticon_img_admin_{$emoticons[id].id_emoticon|escape:'quotes':'UTF-8'}" class="lcp emoticon-img"></td>
	   {if $smarty.section.id.iteration % 5 == 0}
	       </tr><tr>
	   {/if}
	{/section}
	</tr>
	</table> 	
	
</div>

<script type="text/javascript">
$('#tabs-visitors').trigger('click');
</script>


