<?php
/* Smarty version 3.1.28, created on 2016-10-10 13:44:18
  from "C:\webserver\htdocs\www\prestashop16\modules\livechatpro\views\templates\admin\ajax.chats.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_57fb7f12c2c198_02496851',
  'file_dependency' => 
  array (
    'd996d4ca0ec8020aa4c60657a7cfc903874e1d73' => 
    array (
      0 => 'C:\\webserver\\htdocs\\www\\prestashop16\\modules\\livechatpro\\views\\templates\\admin\\ajax.chats.tpl',
      1 => 1475156752,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_57fb7f12c2c198_02496851 ($_smarty_tpl) {
if (!is_callable('smarty_function_counter')) require_once 'C:\\webserver\\htdocs\\www\\prestashop16\\modules\\livechatpro\\libraries\\smarty\\plugins\\function.counter.php';
$_smarty_tpl->compiled->nocache_hash = '2668057fb7f12a021b8_31508269';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>

<?php echo '<script'; ?>
 type="text/javascript">
var lcp_count_active_pending_archives = "<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['count_active_pending_archives']->value);?>
";
<?php echo '</script'; ?>
>

<!-- <input type="hidden" name="count_active_pending_archives" id="count_active_pending_archives" value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['count_active_pending_archives']->value);?>
"> -->

<div class="row lcp panel-head border-bottom" id="ajax_chats_table">
	<?php echo smartyFunctionTranslate(array('s'=>'Chats'),$_smarty_tpl);?>
 
</div>
<div class="row">

			<div class="row">
				<div id="no_chats" style="text-align: center; <?php if (!empty($_smarty_tpl->tpl_vars['active_pending_archives']->value)) {?>display:none;<?php }?>">
					<?php echo smartyFunctionTranslate(array('s'=>'There are not active chats!'),$_smarty_tpl);?>

				</div>
			</div>

			<div class="row">
					<div id="tabs-chat">
						<ul>
							<?php echo smarty_function_counter(array('start'=>1,'skip'=>1,'print'=>false,'assign'=>"count"),$_smarty_tpl);?>

							<?php
$_from = $_smarty_tpl->tpl_vars['active_pending_archives']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_value_0_saved_item = isset($_smarty_tpl->tpl_vars['value']) ? $_smarty_tpl->tpl_vars['value'] : false;
$__foreach_value_0_saved_key = isset($_smarty_tpl->tpl_vars['key']) ? $_smarty_tpl->tpl_vars['key'] : false;
$_smarty_tpl->tpl_vars['value'] = new Smarty_Variable();
$__foreach_value_0_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
if ($__foreach_value_0_total) {
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable();
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$__foreach_value_0_saved_local_item = $_smarty_tpl->tpl_vars['value'];
?>
							<li>
								<a id="tabs-chat-a-<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" href="#tabs-chat-<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" class="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['id_visitor']);?>
" style="cursor: hand; cursor: pointer;"><span <?php if (($_smarty_tpl->tpl_vars['value']->value['in_chat'] == 'P' || $_smarty_tpl->tpl_vars['value']->value['in_chat'] == 'Y') && $_smarty_tpl->tpl_vars['value']->value['awaiting_response_from_staff'] == 'Y') {?>class="lcp blink-container"<?php }?>><b><?php if (!empty($_smarty_tpl->tpl_vars['value']->value['name'])) {
echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['name']);
} else { ?>Visitor ID: <?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['id_visitor']);
}?></b></span></a> <span style="float: right; cursor: hand; cursor: pointer;" class="lcp ui-icon ui-icon-close" role="presentation" id="remove_tab_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
"><?php echo smartyFunctionTranslate(array('s'=>'Remove Tab'),$_smarty_tpl);?>
</span>
							</li>
							<?php echo smarty_function_counter(array(),$_smarty_tpl);?>

							<?php
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_0_saved_local_item;
}
}
if ($__foreach_value_0_saved_item) {
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_0_saved_item;
}
if ($__foreach_value_0_saved_key) {
$_smarty_tpl->tpl_vars['key'] = $__foreach_value_0_saved_key;
}
?>
						</ul>
						<?php echo smarty_function_counter(array('start'=>1,'skip'=>1,'print'=>false,'assign'=>"count"),$_smarty_tpl);?>

						<?php
$_from = $_smarty_tpl->tpl_vars['active_pending_archives']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_value_1_saved_item = isset($_smarty_tpl->tpl_vars['value']) ? $_smarty_tpl->tpl_vars['value'] : false;
$__foreach_value_1_saved_key = isset($_smarty_tpl->tpl_vars['key']) ? $_smarty_tpl->tpl_vars['key'] : false;
$_smarty_tpl->tpl_vars['value'] = new Smarty_Variable();
$__foreach_value_1_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
if ($__foreach_value_1_total) {
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable();
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$__foreach_value_1_saved_local_item = $_smarty_tpl->tpl_vars['value'];
?>
						<input type="hidden" name="id_visitor_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" id="id_visitor_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['id_visitor']);?>
">
						<input type="hidden" name="chat_request_from_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" id="chat_request_from_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" value="client">
						<input type="hidden" name="in_chat_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" id="in_chat_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['in_chat']);?>
">
						
						<div id="tabs-chat-<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
">

							<span id="userchat_span_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" class="lcp tab-selected">&nbsp;<a href="javascript:{}"><?php echo smartyFunctionTranslate(array('s'=>'Chat'),$_smarty_tpl);?>
</a>&nbsp;</span>
								<?php if (!strstr($_smarty_tpl->tpl_vars['value']->value['id_visitor'],"i")) {?>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<span id="details_span_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" class="">&nbsp;<a href="javascript:{}"><?php echo smartyFunctionTranslate(array('s'=>'Details'),$_smarty_tpl);?>
</a>&nbsp;</span>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<span id="visitedpageshistory_span_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" class="">&nbsp;<a href="javascript:{}"><?php echo smartyFunctionTranslate(array('s'=>'Visited pages history'),$_smarty_tpl);?>
</a>&nbsp;</span>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<span id="geotracking_span_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" class="">&nbsp;<a href="javascript:{}"><?php echo smartyFunctionTranslate(array('s'=>'GeoTracking'),$_smarty_tpl);?>
</a>&nbsp;</span>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<span id="archive_span_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" class="">&nbsp;<a href="javascript:{}"><?php echo smartyFunctionTranslate(array('s'=>'Archive'),$_smarty_tpl);?>
</a>&nbsp;</span>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<span id="messages_span_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" class="">&nbsp;<a href="javascript:{}"><?php echo smartyFunctionTranslate(array('s'=>'Messages'),$_smarty_tpl);?>
</a>&nbsp;</span>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<span id="ratings_span_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" class="">&nbsp;<a href="javascript:{}"><?php echo smartyFunctionTranslate(array('s'=>'Ratings'),$_smarty_tpl);?>
</a>&nbsp;</span>
								&nbsp;&nbsp;|&nbsp;&nbsp;
								<span id="logs_span_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" class="">&nbsp;<a href="javascript:{}"><?php echo smartyFunctionTranslate(array('s'=>'Logs'),$_smarty_tpl);?>
</a>&nbsp;</span>
								<?php }?>
								

							<div id="tabs-visitor-userchat-<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" style="padding:5px; background-color: white;">

								<div class="row">
										<div id="content-<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" style=" padding: 5px 10px; text-align: left; overflow-y: scroll; <?php if ($_smarty_tpl->tpl_vars['value']->value['in_chat'] == 'Y') {?>height:271px;<?php } else { ?>height:299px;<?php }?>">
											<div id="content_wrapper_div_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" class="col-md-12 col-lg-7">	

												<?php if ($_smarty_tpl->tpl_vars['value']->value['in_chat'] == 'P' && $_smarty_tpl->tpl_vars['value']->value['chat_request_from'] == 'Client') {?>
													
													<div class="row alert alert-info">
														<div><?php echo smartyFunctionTranslate(array('s'=>'Chat request for:'),$_smarty_tpl);?>
 <b><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['department_name']);?>
</b></div>
														<hr>
														<div><?php echo smartyFunctionTranslate(array('s'=>'Name:'),$_smarty_tpl);?>
 <b><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['name']);?>
</b></div>
														<div><?php echo smartyFunctionTranslate(array('s'=>'Email:'),$_smarty_tpl);?>
 <b><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['email']);?>
</b></div>
														<div><?php echo smartyFunctionTranslate(array('s'=>'Phone:'),$_smarty_tpl);?>
 <b><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['phone']);?>
</b></div>
														<div><?php echo smartyFunctionTranslate(array('s'=>'Message:'),$_smarty_tpl);?>
 <b><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['last_message']);?>
</b></div>
													</div>
												<?php }?>
												<?php if ($_smarty_tpl->tpl_vars['value']->value['in_chat'] == 'P' || $_smarty_tpl->tpl_vars['value']->value['in_chat'] == 'D') {?>
													<div class="row">
														<div id="chat_buttons_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
">
															<?php if ($_smarty_tpl->tpl_vars['value']->value['in_chat'] == 'P') {?>
																<?php if ($_smarty_tpl->tpl_vars['value']->value['chat_request_from'] == 'Staff') {?>
																	<input type="button" disabled value="<?php echo smartyFunctionTranslate(array('s'=>'Invitation sent, please wait...'),$_smarty_tpl);?>
" id="invite_to_chat_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" name="invite_to_chat_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
">
																<?php } else { ?>
																	<input type="button" class="btn btn-primary" value="<?php echo smartyFunctionTranslate(array('s'=>'Accept chat!'),$_smarty_tpl);?>
" id="accept_chat_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" name="accept_chat_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
">
																	<input type="button" class="btn btn-danger" value="<?php echo smartyFunctionTranslate(array('s'=>'Deny chat!'),$_smarty_tpl);?>
" id="deny_chat_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" name="deny_chat_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" <?php if ($_smarty_tpl->tpl_vars['value']->value['chat_request_from'] == 'Staff') {?>style="display:none"<?php }?>>
																<?php }?>
															<?php } else { ?>
																<input type="button" class="btn btn-primary" value="<?php echo smartyFunctionTranslate(array('s'=>'Invite to chat!'),$_smarty_tpl);?>
" id="invite_to_chat_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" name="invite_to_chat_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
">
															<?php }?>
															<span id="chatactionbuttons_ajax_load_span" style="padding-left: 5px;"></span>
														</div>
													</div>
												<?php }?>
												<div class="row">
													<div id="chat_messages_tr_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" <?php if ($_smarty_tpl->tpl_vars['value']->value['in_chat'] != 'Y') {?>style="display: none;"<?php }?>>
														<div id="chat_messages_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
">
																<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['messages']);?>

														</div>
													</div>
												</div>

											</div>
										</div>
										
								
								</div>

							</div>
							
						</div>
						<?php echo smarty_function_counter(array(),$_smarty_tpl);?>

						<?php
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_1_saved_local_item;
}
}
if ($__foreach_value_1_saved_item) {
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_1_saved_item;
}
if ($__foreach_value_1_saved_key) {
$_smarty_tpl->tpl_vars['key'] = $__foreach_value_1_saved_key;
}
?>
					</div>
			
			</div>


</div>











<?php if (empty($_smarty_tpl->tpl_vars['active_pending_archives']->value)) {
echo '<script'; ?>
 type="text/javascript">
	
	try {
		$("#ajax_chats_textarea_div").hide();
		$('#tabs-visitor-details').hide();
		$('#tabs-visitor-visitedpageshistory').hide();
		$('#tabs-visitor-geotracking').hide();
		$('#tabs-visitor-archive').hide();
		$('#tabs-visitor-messages').hide();
		$('#tabs-visitor-ratings').hide();
		$('#tabs-visitor-logs').hide();
		
	} catch(e){}
	

<?php echo '</script'; ?>
>
<?php }
echo '<script'; ?>
 type="text/javascript">
	

try {
	/*scroll down*/
	$('div[id^="content"]').each(function( i ) {
		$(this).scrollTop($(this).prop('scrollHeight'));
	});
} catch(e) {}

$( "#tabs-chat" ).tabs({ active: lcp_active_chat_tab }); // init active tab
var id_visitor = $('#id_visitor_'+lcp_active_chat_tab).val(); //console.log(id_visitor); var id_visitor = $('#id_visitor_'+$("#tabs-chat").tabs('option', 'active')).val(); //console.log(id_visitor);
$("#tabs-chat > ul > li > a."+id_visitor).trigger('click'); // click the active tab

//$('#msg_admin').focus().val( $('#msg_admin').val() );
//$('#msg_admin').focus(); //ca sa mearga in IE sa nu sara (<input type="text" name="msg_admin" id="msg_admin" size="20" style="width: 100%;" class="lcp formfield3" onfocus="var val=this.value; this.value=''; this.value= val;">)




<?php echo '</script'; ?>
><?php }
}
