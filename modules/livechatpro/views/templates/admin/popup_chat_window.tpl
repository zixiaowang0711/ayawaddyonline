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

<span id="dialog_chat_window_ajax_load_span"></span>

<div id='root' class="bootstrap">

<div class="row">
	<div class="col-sm-4 col-md-3 col-lg-2">
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

	</div>

	<div class="col-sm-8 col-md-9 col-lg-10">
		<div class="row lcp panel">

			<span id="ajax_chats_load_span"></span>
			<div id="ajax_chats_div">
				{* include file="$module_templates_back_dir/ajax.chats.tpl" *}
			</div>
			
			<div id="ajax_chats_textarea_div" style="{if empty($active_pending_archives)}display:none;{/if} background-color: white;">
					
					<div class="pull-left" style="padding-right: 5px;"><input type="text" name="msg_admin" id="msg_admin" value="" class="form-control fixed-width-xxl" placeholder="{lang s='press enter key to chat'}"></div>
					<div class="pull-left" style="padding-right: 5px;"><a href="javascript:{}" name="send_msg_admin_a" id="send_msg_admin_a" class="lcp button"><span class="icon-paper-plane-o fa fa-paper-plane-o"></span></a></div>
					<div class="pull-left" style="padding-right: 5px;"><a href="javascript:{}" name="show_hide_emoticons_admin" id="show_hide_emoticons_admin" class="lcp button"><span class="icon-smile-o fa fa-smile-o"></span></a></div>
					<div class="pull-left" style="padding-right: 5px;"><a href="javascript:{}" name="predefined_messages" id="predefined_messages" class="lcp button" title="{lang s='Predefined messages'}"><span class="icon-keyboard-o fa fa-keyboard-o"></span> {lang s='Predefined messages'}</a></div>
					<div class="pull-left" style="padding-right: 5px;"><a href="javascript:{}" name="transfer_visitor" id="transfer_visitor" class="lcp button" title="{lang s='Transfer visitor'}"><span class="icon-exchange fa fa-exchange"></span> {lang s='Transfer visitor'}</a></div>
					<div class="pull-left" style="padding-right: 5px;"><input type="file" class="" id="send_file_upload" /></div>

			</div>

			<div id="tabs-visitor-details" style="display: none; padding-top: 5px;">
				{assign var="full_width" value='N'}
				{include file="$module_templates_back_dir/ajax.visitor_details.tpl"}
			</div>
			<div id="tabs-visitor-visitedpageshistory" style="display: none; padding-top: 5px;">
				{include file="$module_templates_back_dir/ajax.visitor_visited_pages_history.tpl"}
			</div>
			<div id="tabs-visitor-geotracking" style="display: none; padding-top: 5px;">
				{include file="$module_templates_back_dir/ajax.visitor_geotracking.tpl"}
			</div>
			<div id="tabs-visitor-archive" style="display: none; padding-top: 5px;">
				{include file="$module_templates_back_dir/ajax.visitor_archive.tpl"}
			</div>
			<div id="tabs-visitor-messages" style="display: none; padding-top: 5px;">
				{include file="$module_templates_back_dir/ajax.visitor_messages.tpl"}
			</div>
			<div id="tabs-visitor-ratings" style="display: none; padding-top: 5px;">
				{include file="$module_templates_back_dir/ajax.visitor_ratings.tpl"}
			</div>
			<div id="tabs-visitor-logs" style="display: none; padding-top: 5px;">
				{include file="$module_templates_back_dir/ajax.visitor_logs.tpl"}
			</div>
		</div>
	</div>
</div>


<div class="row">
	<div class="lcp panel" id='statistics'>
		<div id="ajax_statistics_div">
			<div class="row lcp panel-head border-bottom">
				{lang s='Statistics'} <span id="statistics_ajax_load_span" style="padding-left: 5px;"></span>
			</div>
			<div class="row">
					<div id="tabs-statistics">
							<ul>
								<li><a id="tabs-visitors-a" href="#tabs-visitors"><span style="cursor: hand; cursor: pointer;">{lang s='Visitors online'} <!--(<span id="count_online_visitors_ajax_load_span">{$count_online_visitors|escape:'quotes':'UTF-8'}</span>) --></span></a><span id="online_visitors_ajax_load_span"></span></li>
								<li><a id="tabs-archive-a" href="#tabs-archive"><span style="cursor: hand; cursor: pointer;">{lang s='Archive'}</span></a><span id="archive_ajax_load_span"></span></li>
								<li><a id="tabs-messages-a" href="#tabs-messages"><span style="cursor: hand; cursor: pointer;">{lang s='Messages'}</span></a><span id="messages_ajax_load_span"></span></li>
								<li><a id="tabs-tickets-a" href="#tabs-tickets"><span style="cursor: hand; cursor: pointer;">{lang s='Tickets'}</span></a><span id="tickets_ajax_load_span"></span></li>
								<li><a id="tabs-ratings-a" href="#tabs-ratings"><span style="cursor: hand; cursor: pointer;">{lang s='Ratings'}</span></a><span id="ratings_ajax_load_span"></span></li>
								<li><a id="tabs-logs-a" href="#tabs-logs"><span style="cursor: hand; cursor: pointer;">{lang s='Logs'}</span></a><span id="logs_ajax_load_span"></span></li>
							</ul>
							<div id="tabs-visitors">
								<table id="onlinevisitors_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
									<thead>
										<tr>
											<th>{lang s='ID'}</th>
											<th>{lang s='Country'}</th>
											<th>{lang s='City'}</th>
											<th>{lang s='Language'}</th>
											<th>{lang s='Visits'}</th>
											<th>{lang s='Current page'}</th>
											<th>{lang s='Host'}</th>
											<th>{lang s='IP'}</th>
											<th>{lang s='Browser'}</th>
											<th>{lang s='Timezone'}</th>
											<th>{lang s='Resolution'}</th>
											<th>{lang s='Referrer'}</th>
											<th>{lang s='Page count'}</th>
											<th>{lang s='OS'}</th>
											<th>{lang s='Last visit'}</th>
										</tr>
									</thead>
									<tfoot>
									<tr>
										<th>{lang s='ID'}</th>
										<th>{lang s='Country'}</th>
										<th>{lang s='City'}</th>
										<th>{lang s='Language'}</th>
										<th>{lang s='Visits'}</th>
										<th>{lang s='Current page'}</th>
										<th>{lang s='Host'}</th>
										<th>{lang s='IP'}</th>
										<th>{lang s='Browser'}</th>
										<th>{lang s='Timezone'}</th>
										<th>{lang s='Resolution'}</th>
										<th>{lang s='Referrer'}</th>
										<th>{lang s='Page count'}</th>
										<th>{lang s='OS'}</th>
										<th>{lang s='Last visit'}</th>
									</tr>
									</tfoot>
								</table>
							</div>
							<div id="tabs-archive">
								<table id="archive_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
									<thead>
										<tr>
											<th>{lang s='Date add'}</th>
											<th>{lang s='ID Chat'}</th>
											<th>{lang s='Name'}</th>
											<th>{lang s='Internal'}</th>
											<th>{lang s='Department'}</th>
											<th>{lang s='Email'}</th>
											<th>{lang s='Phone'}</th>
											<th>{lang s='Company'}</th>
											<th>{lang s='Language'}</th>
											<th>{lang s='Country'}</th>
											<th>{lang s='IP'}</th>
											<th>{lang s='Host'}</th>
											<th>{lang s='Duration'}</th>
											<th>{lang s='Log entries'}</th>
										</tr>
									</thead>
									<tfoot>
									<tr>
										<th>{lang s='Date add'}</th>
										<th>{lang s='ID Chat'}</th>
										<th>{lang s='Name'}</th>
										<th>{lang s='Internal'}</th>
										<th>{lang s='Department'}</th>
										<th>{lang s='Email'}</th>
										<th>{lang s='Phone'}</th>
										<th>{lang s='Company'}</th>
										<th>{lang s='Language'}</th>
										<th>{lang s='Country'}</th>
										<th>{lang s='IP'}</th>
										<th>{lang s='Host'}</th>
										<th>{lang s='Duration'}</th>
										<th>{lang s='Log entries'}</th>
									</tr>
									</tfoot>
								</table>
							</div>
							<div id="tabs-messages">
								<table id="messages_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
									<thead>
										<tr>
											<th>{lang s='Date add'}</th>
											<th>{lang s='Name'}</th>
											<th>{lang s='Email'}</th>
											<th>{lang s='Phone'}</th>
											<th>{lang s='Department'}</th>
											<th>{lang s='Question'}</th>
											<th>{lang s='IP'}</th>
											<th>{lang s='Status'}</th>
										</thead>
										<tfoot>
										<tr>
											<th>{lang s='Date add'}</th>
											<th>{lang s='Name'}</th>
											<th>{lang s='Email'}</th>
											<th>{lang s='Phone'}</th>
											<th>{lang s='Department'}</th>
											<th>{lang s='Question'}</th>
											<th>{lang s='IP'}</th>
											<th>{lang s='Status'}</th>
										</tr>
										</tfoot>
									</table>
								</div>
								<div id="tabs-tickets">
								<table id="tickets_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
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
								</div>
								<div id="tabs-ratings">
									<table id="ratings_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
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
									<div id="tabs-logs">
										<table id="logs_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
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
						</div>
			
			</div>



	</div>
</div>
</div>


<div style="line-height: 30px; text-align: center;">
	<a href="{$module_link|escape:'quotes':'UTF-8'}">{lang s='Go to module configuration page'}</a>
</div>






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


<div id="dialog-form-archive" title="{lang s='View archive details'}" style="display:none">
	<div style="width: 700px; height:300px; overflow-y: scroll; background: white;" class="bootstrap">
		<table border="0" width="100%" height="100%">
			<tr>
				<td valign="top"><div id="archive_message">&nbsp;</div></td>
			</tr>
		</table>
	</div>
</div>

<div id="dialog-form-messages" title="{lang s='View Message'}" style="display:none" class="bootstrap">
	
	
	<div class="row">
		<div class="col-xs-8 col-sm-8 col-md-8 col-lg-8 lcp panel">
			<div class="lcp panel-head border-bottom">{lang s='Date'}</div>
			<div id="messages_date">&nbsp;</div>
		</div>

		<div class="col-xs-4 col-sm-4 col-md-4 col-lg-4 lcp panel">
			<div class="lcp panel-head border-bottom">{lang s='Department'}</div>
			<div id="messages_department">&nbsp;</div>
		</div>
	</div>
	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 lcp panel">
			<div class="lcp panel-head border-bottom">{lang s='Sender'}</div>
			<div>{lang s='Name:'} <b><span id="messages_sender_name"></span></b></div>
			<div>{lang s='Email:'} <b><span id="messages_sender_email"></span></b></div>
			<div>{lang s='Phone:'} <b><span id="messages_sender_phone"></span></b></div>
			<div>{lang s='Company:'} <b><span id="messages_sender_company"></span></b></div>
			<div>{lang s='Message:'} <b><span id="messages_message"></span></b></div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 lcp panel" id="messages_reply_tr">
			<div class="lcp panel-head border-bottom">{lang s='Reply'}</div>
			<textarea id="messages_reply" name="messages_reply" rows="2" class="form-control" style="width: 100%;"></textarea><br>
			<a href="javascript:{}" name="messages_reply_send_a" id="messages_reply_send_a" class="lcp button" title="{lang s='Send'}"><span class="fa fa-paper-plane"></span> {lang s='Send'}</a>
		</div>
	</div>

</div>

<div id="dialog-form-ticket-details" title="{lang s='View ticket details'}" style="display:none">
	<div style="width: 1000px; height:500px; overflow-y: scroll;" id="ajax_ticket_details_div" class="bootstrap">
		{include file="$module_templates_back_dir/ajax.ticket_details.tpl"}
	</div>
</div>

<div id="dialog-form-ratings" title="{lang s='View Rating'}" style="display:none" class="bootstrap">
	
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 lcp panel">
			<div class="lcp panel-head">{lang s='Politness'}</div>
			<div id="ratings_politness">&nbsp;</div>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 lcp panel">
			<div class="lcp panel-head">{lang s='Qualification'}</div>
			<div id="ratings_qualification">&nbsp;</div>
		</div>
	</div>
	
	<div class="row">
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 lcp panel">
			<div class="lcp panel-head border-bottom">{lang s='Date'}</div>
			<div id="ratings_date">&nbsp;</div>
		</div>
		<div class="col-xs-6 col-sm-6 col-md-6 col-lg-6 lcp panel">
			<div class="lcp panel-head border-bottom">{lang s='Internal'}</div>
			<div id="ratings_internal">&nbsp;</div>
		</div>
	</div>

	<div class="row">
		<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 lcp panel">
			<div class="lcp panel-head border-bottom">{lang s='Sender'}</div>
			<div>{lang s='Name:'} <b><span id="ratings_sender_name"></span></b></div>
			<div>{lang s='Email:'} <b><span id="ratings_sender_email"></span></b></div>
			<div>{lang s='Phone:'} <b><span id="ratings_sender_phone"></span></b></div>
			<div>{lang s='Company:'} <b><span id="ratings_sender_company"></span></b></div>
			<div>{lang s='Comment:'} <b><span id="ratings_comment"></span></b></div>
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

</div> <!-- END root -->

<script type="text/javascript">
$( "#dialog-form-archive" ).dialog({
	autoOpen: false,
	/*height: 300,*/
	width: 715,
	modal: false,
	close: function() {
		/*allFields.val( "" ).removeClass( "ui-state-error" );*/
	}
});


$( "#dialog-form-messages" ).dialog({
	autoOpen: false,
	/*height: 300,*/
	width: 500,
	modal: false,
	close : function() {
		/*allFields.val( "" ).removeClass( "ui-state-error" );*/
	}
});

$( "#dialog-form-ticket-details" ).dialog({
	autoOpen: false,
	/*height: 300,*/
	width: 1015,
	modal: false,
	close : function() {
		/*allFields.val( "" ).removeClass( "ui-state-error" );*/
	}
});

$( "#dialog-form-ratings" ).dialog({
	autoOpen: false,
	/*height: 300,*/
	width: 500,
	modal: false,
	close: function() {
		/*allFields.val( "" ).removeClass( "ui-state-error" );*/
	}
});

$('input[id^="send_file_upload"]').uploadifive({
	'multi'    : false,
	"formData" : {
		"location" : "uploads",
	},
	'buttonText' : lcp.l('Send file'),
	"uploadScript"     : lcp_path+"libraries/uploadify/uploadifive.php",
	"onUploadComplete" : function(file, data)
	{
	//alert(data);
		if (data == "error1") alert(lcp.l("File exists, choose different filename."));
		else if (data == "error2") alert(lcp.l("Invalid file type."));
		else
		{
			var e = $.Event("keydown");
					e.which = 13;
					e.keyCode = 13;
			$("#msg_admin").val(lcp_url+"uploads/"+data).trigger(e);
		}
	},
	height     : 26,
	//width      : 100,
});
		
</script>




