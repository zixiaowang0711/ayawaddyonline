<?php
/* Smarty version 3.1.28, created on 2016-10-31 13:24:02
  from "C:\webserver\htdocs\www\prestashop16\modules\livechatpro\views\templates\front\ajax.chat.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_581737e2927bf1_74194164',
  'file_dependency' => 
  array (
    '95fa46e7ab69d705ce01ad90860d35856434aefc' => 
    array (
      0 => 'C:\\webserver\\htdocs\\www\\prestashop16\\modules\\livechatpro\\views\\templates\\front\\ajax.chat.tpl',
      1 => 1475144995,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_581737e2927bf1_74194164 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '19737581737e276afb7_04383862';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>

<?php echo '<script'; ?>
 type="text/javascript">
var lcp_rating_click = 0;
var lcp_chat_status = "<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['status']->value);?>
";
<?php echo '</script'; ?>
>

<div id="before_chat_div_with_image" style="<?php if ($_smarty_tpl->tpl_vars['chat_type']->value == 'Slide' && $_smarty_tpl->tpl_vars['primary_settings']->value['slide_with_image'] == 'Y' && $_smarty_tpl->tpl_vars['status']->value == 'online') {
} else { ?>display: none;<?php }?>">
	<img src="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['module_path']->value);?>
views/img/iconsets/<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['iconset_img']->value);?>
" style="cursor: hand; cursor: pointer;" id="">
</div>

<div style="
<?php if ($_smarty_tpl->tpl_vars['primary_settings']->value['hide_when_offline'] == 'Y' && $_smarty_tpl->tpl_vars['status']->value == 'offline') {?>
	display:none; 
<?php }?>

<?php if ($_smarty_tpl->tpl_vars['chat_type']->value != 'Popup') {?>
	border: <?php if ($_smarty_tpl->tpl_vars['theme']->value['chat_box_border'] == 'Y') {?>1<?php } else {
}?>px solid #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['chat_box_border_color']);?>
 !important;
<?php }?>
width: <?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['width']);?>
px;
color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['chat_box_foreground']);?>
 !important;
background-color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['chat_box_background']);?>
 !important;
-webkit-border-top-left-radius: <?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['corners_radius']);?>
px;
-moz-border-top-left-radius:<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['corners_radius']);?>
px;
border-top-left-radius:<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['corners_radius']);?>
px;
-webkit-border-top-right-radius:<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['corners_radius']);?>
px;
-moz-border-top-right-radius:<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['corners_radius']);?>
px;
border-top-right-radius:<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['corners_radius']);?>
px;
" class="row" id="chat_table">

	
		<div style="
		<?php if ($_smarty_tpl->tpl_vars['chat_type']->value == 'Popup') {?>
			display: none;
		<?php } else { ?>
			<?php if ($_smarty_tpl->tpl_vars['primary_settings']->value['slide_with_image'] == 'Y') {?>
				<?php if ($_smarty_tpl->tpl_vars['status']->value == 'offline') {?>
					display: table;
				<?php } else { ?>
					display: none;
				<?php }?>
			<?php } else { ?>
				display: table;
			<?php }?>
		<?php }?>
		<?php if ($_smarty_tpl->tpl_vars['status']->value == 'offline') {?>
			color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['header_offline_foreground']);?>
 !important;
			background-color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['header_offline_background']);?>
 !important;
		<?php } else { ?>
			color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['header_online_foreground']);?>
 !important;
			background-color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['header_online_background']);?>
 !important;
		<?php }?>
		-webkit-border-top-left-radius:<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['corners_radius']);?>
px;
		-moz-border-top-left-radius:<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['corners_radius']);?>
px;
		border-top-left-radius:<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['corners_radius']);?>
px;
		-webkit-border-top-right-radius:<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['corners_radius']);?>
px;
		-moz-border-top-right-radius:<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['corners_radius']);?>
px;
		border-top-right-radius:<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['corners_radius']);?>
px;
		height: 30px; 
		vertical-align: middle; 
		margin: 0px; 
		padding: 0px 5px;
		width: 100% !important;
		" id="chat_header_table" class="row lcp blink-container">

			<div style="line-height: 30px; width: 16px; display: table-cell;" class="fa fa-<?php if ($_smarty_tpl->tpl_vars['status']->value == 'offline') {?>envelope<?php } else { ?>comment<?php }?>"></div>
			<div style="line-height: 30px; cursor: hand; cursor: pointer; width: auto; display: table-cell;" id="header_message_td" class=""><?php if ($_smarty_tpl->tpl_vars['status']->value == 'offline') {
echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['primary_settings']->value['offline_header_message']);
} else {
echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['primary_settings']->value['online_header_message']);
}?></div>
			<div style="line-height: 30px; cursor: hand; cursor: pointer; width: 16px; display: table-cell;" title="Minimize" class="fa fa-chevron-<?php if ($_smarty_tpl->tpl_vars['primary_settings']->value['start_minimized'] == 'Y' || empty($_smarty_tpl->tpl_vars['chat_toggled']->value) || $_smarty_tpl->tpl_vars['chat_toggled']->value == 'down') {?>up<?php } else { ?>down<?php }?>" id="minimize_chat"></div>
			<div style="line-height: 30px; cursor: hand; cursor: pointer; width: 16px; display: table-cell;" title="Close" class="fa fa-times-circle" id="close_chat"></div>
		</div>
	<div class="clearboth"></div>
	<div id="chat_inner_table" style="
		<?php if ($_smarty_tpl->tpl_vars['primary_settings']->value['start_minimized'] == 'Y' || empty($_smarty_tpl->tpl_vars['chat_toggled']->value) || $_smarty_tpl->tpl_vars['chat_toggled']->value == 'down') {?>
			display:none;
		<?php }?>">

			<!-- BEGIN before_chat_div -->
			<div id="before_chat_div" style="
			<?php if ($_smarty_tpl->tpl_vars['visitor_chat_status']->value != 'Y' && $_smarty_tpl->tpl_vars['visitor_chat_status']->value != 'P') {?>
				<?php if ($_smarty_tpl->tpl_vars['primary_settings']->value['slide_with_image'] == 'Y') {?>
					<?php if ($_smarty_tpl->tpl_vars['status']->value == 'offline') {?>
						display: block;
					<?php } else { ?>
						display: none;
					<?php }?>
				<?php }?>
			<?php } else { ?>
				display:none;
			<?php }?>
			padding: 0px 10px;">
				
				<div id="welcome_message_tr" style="margin: 5px 5px;">
					<span id="welcome_message_span"><?php if ($_smarty_tpl->tpl_vars['status']->value == 'offline') {
echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['primary_settings']->value['offline_welcome_message']);
} else {
echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['primary_settings']->value['online_welcome_message']);
}?></span>
				</div>

				<div class="lcp validateTips" id="error_tr" style="margin: 5px 5px; display:none;">
					<?php echo smartyFunctionTranslate(array('s'=>'All form fields are required.'),$_smarty_tpl);?>

				</div>

				<div class="" id="name_field_tbody" style="margin: 5px 0px; <?php if ($_smarty_tpl->tpl_vars['status']->value == 'online') {
if ($_smarty_tpl->tpl_vars['primary_settings']->value['name_field_online'] == 'N') {?>display:none;<?php }
} else {
if ($_smarty_tpl->tpl_vars['primary_settings']->value['name_field_offline'] == 'N') {?>display:none;<?php }
}?>">
					<input class="lcp form-control formfield" type="text" name="lcp_name" id="lcp_name" placeholder="<?php if (!empty($_smarty_tpl->tpl_vars['customer_details']->value)) {
echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['customer_details']->value['firstname']);?>
 <?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['customer_details']->value['lastname']);
} else {
echo smartyFunctionTranslate(array('s'=>'Your name'),$_smarty_tpl);
}?>">
				</div>

				<div class="" id="email_field_tbody" style="margin: 5px 0px; <?php if ($_smarty_tpl->tpl_vars['status']->value == 'online') {
if ($_smarty_tpl->tpl_vars['primary_settings']->value['email_field_online'] == 'N') {?>display:none;<?php }
} else {
if ($_smarty_tpl->tpl_vars['primary_settings']->value['email_field_offline'] == 'N') {?>display:none;<?php }
}?>">
					<input class="lcp form-control formfield" type="text" name="lcp_email" id="lcp_email" placeholder="<?php if (!empty($_smarty_tpl->tpl_vars['customer_details']->value)) {
echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['customer_details']->value['email']);
} else {
echo smartyFunctionTranslate(array('s'=>'E-mail'),$_smarty_tpl);
}?>">
				</div>

				<div class="" id="phone_field_tbody" style="margin: 5px 0px; <?php if ($_smarty_tpl->tpl_vars['status']->value == 'online') {
if ($_smarty_tpl->tpl_vars['primary_settings']->value['phone_field_online'] == 'N') {?>display:none;<?php }
} else {
if ($_smarty_tpl->tpl_vars['primary_settings']->value['phone_field_offline'] == 'N') {?>display:none;<?php }
}?>">
					<input class="lcp form-control formfield" type="text" name="lcp_phone" id="lcp_phone" placeholder="<?php if (!empty($_smarty_tpl->tpl_vars['customer_details']->value)) {
echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['customer_details']->value['phone']);
} else {
echo smartyFunctionTranslate(array('s'=>'Phone'),$_smarty_tpl);
}?>">
				</div>

				<div class="" id="department_field_tbody" style="margin: 5px 0px; <?php if ($_smarty_tpl->tpl_vars['status']->value == 'online') {
if ($_smarty_tpl->tpl_vars['primary_settings']->value['department_field_online'] == 'N') {?>display:none;<?php }
} else {
if ($_smarty_tpl->tpl_vars['primary_settings']->value['department_field_offline'] == 'N') {?>display:none;<?php }
}?>">
					<select class="lcp formfield " name="departments" id="departments" style="width: 100% !important;">
						<?php
$_from = $_smarty_tpl->tpl_vars['departments']->value;
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
						<option value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['id_department']);?>
"><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['name']);?>
</option>
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
					</select>
				</div>

				<div class="" id="question_field_tbody" style="margin: 5px 0px; <?php if ($_smarty_tpl->tpl_vars['status']->value == 'online') {
if ($_smarty_tpl->tpl_vars['primary_settings']->value['question_field_online'] == 'N') {?>display:none;<?php }
} else {
if ($_smarty_tpl->tpl_vars['primary_settings']->value['question_field_offline'] == 'N') {?>display:none;<?php }
}?>">
					<textarea class="lcp form-control formfield" rows="3" name="lcp_question" id="lcp_question" placeholder="<?php echo smartyFunctionTranslate(array('s'=>'Question'),$_smarty_tpl);?>
"></textarea>
				</div>

				<div class="row-centered" style="margin: 5px 0px;">
					<?php if ($_smarty_tpl->tpl_vars['status']->value == 'offline') {?>
						<a href="javascript:{}" name="leave_message" id="leave_message" class="lcp chat-button" style="color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['submit_button_foreground']);?>
 !important; background-color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['submit_button_background']);?>
 !important;"><span class="fa fa-envelope-o"></span> <?php echo smartyFunctionTranslate(array('s'=>'Leave message!'),$_smarty_tpl);?>
</a>
					<?php } else { ?>
						<a href="javascript:{}" name="start_chat" id="start_chat" class="lcp chat-button" style="color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['submit_button_foreground']);?>
 !important; background-color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['submit_button_background']);?>
; !important"><span class="fa fa-comment-o"></span> <?php echo smartyFunctionTranslate(array('s'=>'Start chat!'),$_smarty_tpl);?>
</a>
					<?php }?>
				</div>

			</div> 
			<!-- END before_chat_div -->


			<!-- BEGIN start_chat_div -->
			<div id="start_chat_div" style="<?php if ($_smarty_tpl->tpl_vars['visitor_chat_status']->value != 'Y' && $_smarty_tpl->tpl_vars['visitor_chat_status']->value != 'P') {?>display:none;<?php } else {
}?> padding-left: 10px;">
				<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_front_dir']->value)."/ajax.start_chat.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

			</div>
			
			<div id="chat_msg_textarea_div" style="<?php if ($_smarty_tpl->tpl_vars['visitor_chat_status']->value != 'Y') {?>display:none;<?php } else {
}?> padding-left: 10px;">
				
				<div class="pull-left" style="margin-right: 5px;"><input class="lcp form-control formfield" type="text" name="msg" id="msg" placeholder="<?php echo smartyFunctionTranslate(array('s'=>'press enter key to chat'),$_smarty_tpl);?>
"></div>
				<div class="pull-left" style="margin-right: 5px;"><a href="javascript:{}" name="send_msg_a" id="send_msg_a" class="lcp chat-button" style="color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['submit_button_foreground']);?>
 !important; background-color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['submit_button_background']);?>
 !important;"><span class="icon-paper-plane-o fa fa-paper-plane-o"></span></a></div>
				<div class="pull-left"><a href="javascript:{}" name="show_hide_emoticons" id="show_hide_emoticons" class="lcp chat-button" style="color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['submit_button_foreground']);?>
 !important; background-color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['submit_button_background']);?>
 !important;"><span class="icon-smile-o fa fa-smile-o"></span></a></div>
				<div style="<?php if ($_smarty_tpl->tpl_vars['primary_settings']->value['visitors_can_upload_files'] == 'N') {?>display: none;<?php }?>"><input type="file"  style="width: 80px;" id="send_file_upload"></div>

			</div>
			<!-- END start_chat_div -->

			<div id="leave_message_div" style="display:none;">
				<?php echo smartyFunctionTranslate(array('s'=>'Your message has been sent! We will get back to you as soon as possible. Thank you!'),$_smarty_tpl);?>

			</div>
			
			<!-- BEGIN after_chat_div -->
			<div id="after_chat_div" class="row-centered" style="display: none; margin: 0px 10px;">
				<!-- <input type="hidden" name="rating_click" id="rating_click" value="0"> -->
				<br>
				<a href="javascript:{}" name="back_to_start_chat_a" id="back_to_start_chat_a" class="lcp chat-button" style="color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['submit_button_foreground']);?>
 !important; background-color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['submit_button_background']);?>
 !important;"><span class="fa fa-caret-left"></span> <?php echo smartyFunctionTranslate(array('s'=>'Back to chat again'),$_smarty_tpl);?>
</a>
				<br>
				<div id="signature_td">
						&nbsp;
				</div>
				<br>
				<?php if ($_smarty_tpl->tpl_vars['primary_settings']->value['staff_qualification'] == 'Y') {?>
				
					<div><?php echo smartyFunctionTranslate(array('s'=>'Please rate this staff member below'),$_smarty_tpl);?>
</div>
					<div id="rating_td" class="col-lg-6 col-lg-offset-3" style="text-align: center;"></div>
					<div id="rate_ajax_load_span"></div>

					<div class="pull-left">
						<input type="text" name="rating_comment" id="rating_comment" class="lcp form-control formfield" placeholder="<?php echo smartyFunctionTranslate(array('s'=>'Comment...'),$_smarty_tpl);?>
">
					</div>
					<div class="pull-right">
						<a href="javascript:{}" name="rate" id="rate" class="lcp chat-button" style="color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['submit_button_foreground']);?>
 !important; background-color: #<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['theme']->value['submit_button_background']);?>
 !important;"><span class="fa fa-star-half-o"></span> <?php echo smartyFunctionTranslate(array('s'=>'Rate!'),$_smarty_tpl);?>
</a>
					</div>
					<div class="clearfix"></div>
					<br>
				<?php }?>
			</div>
			<!-- END after_chat_div -->



	</div>

</div>

<?php echo '<script'; ?>
 type="text/javascript">
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
					$('#msg').val(lcp_url+'uploads/'+data).trigger(e);
				}
			},
			height     : 26,
			width      : 100,
		});
<?php echo '</script'; ?>
><?php }
}
