<?php
/* Smarty version 3.1.28, created on 2016-10-13 13:33:08
  from "C:\webserver\htdocs\www\prestashop16\modules\livechatpro\views\templates\admin\ajax.chats_slide.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_57ff70f4e201f7_29560454',
  'file_dependency' => 
  array (
    '4623dd04e6d40de644ac388c663277eab197b915' => 
    array (
      0 => 'C:\\webserver\\htdocs\\www\\prestashop16\\modules\\livechatpro\\views\\templates\\admin\\ajax.chats_slide.tpl',
      1 => 1476355612,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_57ff70f4e201f7_29560454 ($_smarty_tpl) {
if (!is_callable('smarty_function_counter')) require_once 'C:\\webserver\\htdocs\\www\\prestashop16\\modules\\livechatpro\\libraries\\smarty\\plugins\\function.counter.php';
$_smarty_tpl->compiled->nocache_hash = '1233257ff70f4d07d50_95871779';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>

			<tr>
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
			
				<td style="width: 260px;" valign="bottom" id="id_chat_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
">
						
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
		
	
		<div class="" style="">
			
			<div class="row lcp panel2">
				<div class="row lcp border-bottom" style="background: #cccccc; height: 22px;">
					<span <?php if (($_smarty_tpl->tpl_vars['value']->value['in_chat'] == 'P' || $_smarty_tpl->tpl_vars['value']->value['in_chat'] == 'Y') && $_smarty_tpl->tpl_vars['value']->value['awaiting_response_from_staff'] == 'Y') {?>class="lcp blink-container"<?php }?> style="float: left;"><b> <?php if (!empty($_smarty_tpl->tpl_vars['value']->value['name'])) {
echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['name']);
} else { ?>Visitor ID: <?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['id_visitor']);
}?></b></span>
					<span style="float: right; cursor: hand; cursor: pointer;" class="lcp ui-icon ui-icon-close" role="presentation" id="remove_tab_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
"><?php echo smartyFunctionTranslate(array('s'=>'Remove Tab'),$_smarty_tpl);?>
</span>
					<span style="float: right; cursor: hand; cursor: pointer;" class="lcp fa fa-chevron-down" role="presentation" id="minimize_tab_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
"></span>
				</div>
				<div class="row">
						<div id="content-<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" style=" padding: 5px 10px; text-align: left; overflow-y: scroll; height:150px;">
							<div id="content_wrapper_div_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" class="col-sm-12">	
	
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
											<span id="chatactionbuttons_ajax_load_span_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" style="padding-left: 5px;"></span>
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
				
				
				</td>
				
<?php echo '<script'; ?>
 type="text/javascript">if ($.localStorage.get('admin_chat_toggled_'+<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
) == 'down') { $('#minimize_tab_'+<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
).trigger('click');}<?php echo '</script'; ?>
>	

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
</tr>



<?php echo '<script'; ?>
 type="text/javascript">
	

try {
	/*scroll down*/
	$('div[id^="content"]').each(function( i ) {
		$(this).scrollTop($(this).prop('scrollHeight'));
	});
} catch(e) {}



	$('input[id^="send_file_upload"]').uploadify({
		'multi'    : false,
		'method'   : 'post',
		'formData' : { 'path' : lcp_path },
		'buttonText' : lcp.l('Send file'),
		'swf'      : lcp_path+'libraries/uploadify/uploadify.swf',
		'uploader' : lcp_path+'libraries/uploadify/uploadify_files.php',
		'onUploadSuccess' : function(file, data, response)
		{
			if (data == 'error1')
				alert(lcp.l('File exists, choose different filename.'));
			else if (data == 'error2')
				alert(lcp.l('Invalid file type.'));
			else
			{
				//aici inserez path-ul cu linkul de la fisierul incarcat la user sa il poata downloada
				var e = $.Event("keydown");
						e.which = 13;
						e.keyCode = 13;
				$('#msg_admin').val(lcp_url+'uploads/'+data).trigger(e);
			}
			},
		height     : 26,
		width      : 72,
		});


<?php echo '</script'; ?>
>	
	<?php }
}
