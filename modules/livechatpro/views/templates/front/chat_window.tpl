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


{if $chat_type == 'Popup'}
<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		{$media|escape:'quotes':'UTF-8'}


		<script type="text/javascript">
			//$(window).on('onunload', function(){
			$(window).on('beforeunload', function(){
				
				return $('#close_chat').trigger('click');
				/*return 'Are you sure you want to leave?';*/
			});
			/*
			$(window).on('unload', function(){
				
				//return $('#close_chat').trigger('click');
				//return 'Are you sure you want to leave?';
			});
			*/
		</script>
	
	</head>
	<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" style="margin: 0px; padding: 0px;">
{/if}
		
		<audio id="newchat" src=""></audio>
		<audio id="newmessage" src=""></audio>

		<div id="dialog_chat_invitation" title="{lang s='Invitation to chat'}" style="display:none; text-align: center; z-index: 9999 !important;">
			
	
				<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
					{if !empty($visitor_archive_details.avatar)}<img id="invitation_avatar_img" border="0" src="{$module_path|escape:'quotes':'UTF-8'}views/img/avatars/{$visitor_archive_details.avatar|escape:'quotes':'UTF-8'}">{/if}
				</div>

				<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
					{lang s='You have been invited to chat!'}
				</div>
	
		</div>
		<script type="text/javascript">
		// init dialog chat invitation
		$("#dialog_chat_invitation").dialog(
			{
				autoOpen: false,
				closeOnEscape: false,
				dialogClass: "noclose",
				width: 300,
				/*height: 200,*/
				position: ['top', 320],
				modal: true,
				buttons: [
					{
						text: lcp.l("Accept"),
						click: function()
						{
							if ($('#chat_inner_table').is(":hidden")) 
								$("#minimize_chat").trigger('click');
							
							$("#error_tr").hide();
							$("#before_chat_div").hide();
							$('#before_chat_div_with_image').hide();
							$("#start_chat_div").show();
							$("#chat_msg_textarea_div").show();
							$("#be_patient").show();
							var data2 = {
								'id_visitor': lcp_id_visitor,
							};
							var params2 = {
								'load': 'chatAcceptedFromClient',
								'divs': null,
								'params':
								{
									'data': data2,
								},
							};
							lcp.ajaxController(params2, function(result) {});
							$(this).dialog("close");
						},
						class: "",
						style: "color: black"
					},
					{
						text: lcp.l("Deny"),
						click: function()
						{
							var data2 = {
								'id_visitor': lcp_id_visitor,
							};
							var params2 = {
								'load': 'chatDeniedFromClient',
								'divs': null,
								'params':
								{
									'data': data2,
								},
							};
							lcp.ajaxController(params2, function(result) {});
							$(this).dialog("close");
						},
						class: "",
						style: "color: red"
					},
					//{ text: lcp.l("Cancel"), click: function () { $( this ).dialog( "close" ); }, class:"", style:"color: black" }
				],

			});
		
		</script>
		
		<div id="chat_div">
			{* include file="$module_templates_front_dir/ajax.chat.tpl" *}
		</div>
		
		
{if $chat_type == 'Popup'}
	</body>
</html>
{/if}