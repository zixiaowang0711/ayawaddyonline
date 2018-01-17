<?php
/* Smarty version 3.1.28, created on 2017-05-19 08:32:58
  from "C:\localhost\htdocs\www\prestashop17\modules\livechatpro\views\templates\front\chat_window.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_591e919a8d9446_71494507',
  'file_dependency' => 
  array (
    '8e2a808f4af00ffce6c4d62922ccdf92cc37b6bc' => 
    array (
      0 => 'C:\\localhost\\htdocs\\www\\prestashop17\\modules\\livechatpro\\views\\templates\\front\\chat_window.tpl',
      1 => 1490763043,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_591e919a8d9446_71494507 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '24485591e919a824924_70540831';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>


<?php if ($_smarty_tpl->tpl_vars['chat_type']->value == 'Popup') {?>
<!DOCTYPE HTML>
<html lang="en">
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">

		<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['media']->value);?>



		<?php echo '<script'; ?>
 type="text/javascript">
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
		<?php echo '</script'; ?>
>
	
	</head>
	<body topmargin="0" leftmargin="0" rightmargin="0" bottommargin="0" style="margin: 0px; padding: 0px;">
<?php }?>
		
		<audio id="newchat" src=""></audio>
		<audio id="newmessage" src=""></audio>

		<div id="dialog_chat_invitation" title="<?php echo smartyFunctionTranslate(array('s'=>'Invitation to chat'),$_smarty_tpl);?>
" style="display:none; text-align: center; z-index: 9999 !important;">
			
	
				<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
					<?php if (!empty($_smarty_tpl->tpl_vars['visitor_archive_details']->value['avatar'])) {?><img id="invitation_avatar_img" border="0" src="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['module_path']->value);?>
views/img/avatars/<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_archive_details']->value['avatar']);?>
"><?php }?>
				</div>

				<div class="col-xs-10 col-sm-10 col-md-10 col-lg-10">
					<?php echo smartyFunctionTranslate(array('s'=>'You have been invited to chat!'),$_smarty_tpl);?>

				</div>
	
		</div>
		<?php echo '<script'; ?>
 type="text/javascript">
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
		
		<?php echo '</script'; ?>
>
		
		<div id="chat_div">
			
		</div>
		
		
<?php if ($_smarty_tpl->tpl_vars['chat_type']->value == 'Popup') {?>
	</body>
</html>
<?php }
}
}
