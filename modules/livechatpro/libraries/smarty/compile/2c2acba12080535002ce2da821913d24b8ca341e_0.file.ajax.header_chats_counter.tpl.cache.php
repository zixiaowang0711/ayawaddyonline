<?php
/* Smarty version 3.1.28, created on 2016-10-13 13:33:09
  from "C:\webserver\htdocs\www\prestashop16\modules\livechatpro\views\templates\admin\ajax.header_chats_counter.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_57ff70f503f9b8_09061439',
  'file_dependency' => 
  array (
    '2c2acba12080535002ce2da821913d24b8ca341e' => 
    array (
      0 => 'C:\\webserver\\htdocs\\www\\prestashop16\\modules\\livechatpro\\views\\templates\\admin\\ajax.header_chats_counter.tpl',
      1 => 1467121637,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_57ff70f503f9b8_09061439 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '2061857ff70f4f3cd41_17433479';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>

<?php echo '<script'; ?>
 type="text/javascript">
	var lcp = new Livechatpro();
<?php echo '</script'; ?>
>


<?php if (!empty($_smarty_tpl->tpl_vars['online_chats']->value)) {?>

	<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['online_chats']->value);?>


	<?php if (!empty($_smarty_tpl->tpl_vars['new_chats_and_messages']->value)) {?>

		
			<?php echo '<script'; ?>
 type="text/javascript">
				/*console.log(blink_interval_id);*/
				if (typeof(blink_interval_id) == "undefined") {
				    blink_interval_id = setInterval(function(){ $(".blink-container").toggleClass("blink"); }, 600);
				    /*console.log(blink_interval_id+" start");*/
				}
			<?php echo '</script'; ?>
>
			

	<?php } else { ?>		

		
			<?php echo '<script'; ?>
 type="text/javascript">	
				try {
					clearInterval(blink_interval_id);
					$(".blink-container").removeClass("blink");
					delete blink_interval_id;
				} catch(e){}	
			<?php echo '</script'; ?>
>
		
	<?php }?>	

<?php } else { ?>
0
	
	<?php echo '<script'; ?>
 type="text/javascript">	
		try {
			clearInterval(blink_interval_id);
			$(".blink-container").removeClass("blink");
			delete blink_interval_id;
		} catch(e){}	
	<?php echo '</script'; ?>
>
	
<?php }?>




<?php if (!empty($_smarty_tpl->tpl_vars['new_chats']->value) && $_smarty_tpl->tpl_vars['primary_settings']->value['popup_alert_on_income_chats'] == 'Y') {?>
	
		<?php echo '<script'; ?>
 type="text/javascript">
			if (typeof(new_chat_window) == "undefined") {
				lcp.popupWindow2(lcp_url+"ajax.php?type=newChatAlert", "New Chat", "250", "100");
				new_chat_window = true;
			}
		<?php echo '</script'; ?>
>
	

<?php } else { ?>

	
		<?php echo '<script'; ?>
 type="text/javascript">
			delete new_chat_window;
		<?php echo '</script'; ?>
>
	
<?php }?>


<?php if (!empty($_smarty_tpl->tpl_vars['new_chats']->value) && $_smarty_tpl->tpl_vars['primary_settings']->value['new_chat_sound'] != 'none') {?>

	
		<?php echo '<script'; ?>
 type="text/javascript">
			lcp.playSound("newchat", true); 
		<?php echo '</script'; ?>
>
	

<?php } else { ?>	

	
		<?php echo '<script'; ?>
 type="text/javascript">
			lcp.stopSound("newchat"); 
		<?php echo '</script'; ?>
>	
	


	<?php if (!empty($_smarty_tpl->tpl_vars['new_messages']->value) && $_smarty_tpl->tpl_vars['primary_settings']->value['new_message_sound'] != 'none') {?>

		
			<?php echo '<script'; ?>
 type="text/javascript">
			if (typeof(new_message) == "undefined") {
				lcp.playSound("newmessage");
				new_message = true;
			}
			<?php echo '</script'; ?>
>
			

	<?php } else { ?>

		
			<?php echo '<script'; ?>
 type="text/javascript">
				delete new_message;
			<?php echo '</script'; ?>
>
		

	<?php }?>

	
<?php }
}
}
