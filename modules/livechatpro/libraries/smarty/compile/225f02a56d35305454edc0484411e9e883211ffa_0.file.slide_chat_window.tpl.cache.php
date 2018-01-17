<?php
/* Smarty version 3.1.28, created on 2016-12-08 15:04:13
  from "C:\webserver\htdocs\www\prestashop16\modules\livechatpro\views\templates\admin\slide_chat_window.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_5849685d963b84_32849938',
  'file_dependency' => 
  array (
    '225f02a56d35305454edc0484411e9e883211ffa' => 
    array (
      0 => 'C:\\webserver\\htdocs\\www\\prestashop16\\modules\\livechatpro\\views\\templates\\admin\\slide_chat_window.tpl',
      1 => 1479756723,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5849685d963b84_32849938 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '26595849685d6ff946_46598057';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>

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
				<div class="col-lg-2 lcp panel-head"><?php echo smartyFunctionTranslate(array('s'=>'Status:'),$_smarty_tpl);?>
</div>
				<div class="col-lg-9">
					<select name="status_select" id="status_select" class="form-control">
						<option value="Offline" style="color: #606060; background-color: white; font-weight: bolder; padding: 10px;" <?php if ($_smarty_tpl->tpl_vars['staff_chat_status']->value == 'offline') {?>selected<?php }?>><?php echo smartyFunctionTranslate(array('s'=>'Offline'),$_smarty_tpl);?>
</option>
						<option value="Online" style="color: #3fc813; background-color: white; font-weight: bolder;  padding: 10px;" <?php if ($_smarty_tpl->tpl_vars['staff_chat_status']->value == 'online') {?>selected<?php }?>><?php echo smartyFunctionTranslate(array('s'=>'Online'),$_smarty_tpl);?>
</option>
					</select>
				</div>
				<div class="col-lg-1" style="line-height: 25px; vertical-align: middle;"><span id="status_ajax_load_span"><img id="status_img" border="0" src="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['module_path']->value);?>
views/img/<?php if ($_smarty_tpl->tpl_vars['staff_chat_status']->value == 'online') {?>online<?php } else { ?>offline<?php }?>-ico.png"></span></div>
			</div>

			<div class="row lcp panel" style="margin: 10px 0px !important;">
			<div id='online_users'>
				<span id="ajax_onlineusers_load_span"></span>
				<div id="ajax_onlineusers_div">
					
				</div>
			</div>
			</div>
			
			<div class="row lcp panel" style="margin: 10px 0px !important;">
				<div class="lcp panel-head border-bottom">
					<?php echo smartyFunctionTranslate(array('s'=>'Statistics'),$_smarty_tpl);?>
 <span id="statistics_ajax_load_span" style="padding-left: 5px;">
				</div>
			
				<div id="tabs-statistics">

					<div id="tabs-visitors">
						<table id="onlinevisitors_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
							<thead>
								<tr>
									<th style="display: none;"><?php echo smartyFunctionTranslate(array('s'=>'ID'),$_smarty_tpl);?>
</th>
									<th><?php echo smartyFunctionTranslate(array('s'=>'Country'),$_smarty_tpl);?>
</th>
									<th style="display: none;"><?php echo smartyFunctionTranslate(array('s'=>'City'),$_smarty_tpl);?>
</th>
									<th style="display: none;"><?php echo smartyFunctionTranslate(array('s'=>'Language'),$_smarty_tpl);?>
</th>
									<th style="display: none;"><?php echo smartyFunctionTranslate(array('s'=>'Visits'),$_smarty_tpl);?>
</th>
									<th style="display: none;"><?php echo smartyFunctionTranslate(array('s'=>'Current page'),$_smarty_tpl);?>
</th>
									<th style="display: none;"><?php echo smartyFunctionTranslate(array('s'=>'Host'),$_smarty_tpl);?>
</th>
									<th><?php echo smartyFunctionTranslate(array('s'=>'IP'),$_smarty_tpl);?>
</th>
									<th style="display: none;"><?php echo smartyFunctionTranslate(array('s'=>'Browser'),$_smarty_tpl);?>
</th>
									<th style="display: none;"><?php echo smartyFunctionTranslate(array('s'=>'Timezone'),$_smarty_tpl);?>
</th>
									<th style="display: none;"><?php echo smartyFunctionTranslate(array('s'=>'Resolution'),$_smarty_tpl);?>
</th>
									<th style="display: none;"><?php echo smartyFunctionTranslate(array('s'=>'Referrer'),$_smarty_tpl);?>
</th>
									<th style="display: none;"><?php echo smartyFunctionTranslate(array('s'=>'Page count'),$_smarty_tpl);?>
</th>
									<th style="display: none;"><?php echo smartyFunctionTranslate(array('s'=>'OS'),$_smarty_tpl);?>
</th>
									<th style="display: none;"><?php echo smartyFunctionTranslate(array('s'=>'Last visit'),$_smarty_tpl);?>
</th>
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
	
	


<div id="dialog-form-visitordetails" title="<?php echo smartyFunctionTranslate(array('s'=>'View visitor details'),$_smarty_tpl);?>
" style="display:none" class="bootstrap">
	<div id="tabs-visitordetails">
		<ul>
			<li>
				<a href="#tabs-visitordetails-details"><?php echo smartyFunctionTranslate(array('s'=>'Details:'),$_smarty_tpl);?>
</a> 
			</li>
			<li>
				<a href="#tabs-visitordetails-visitedpageshistory"><?php echo smartyFunctionTranslate(array('s'=>'Visited pages history'),$_smarty_tpl);?>
</a>
			</li>
			<li>
				<a href="#tabs-visitordetails-geotracking" id="tabs-visitordetails-geotracking-a"><?php echo smartyFunctionTranslate(array('s'=>'GeoTracking'),$_smarty_tpl);?>
</a>
			</li>
		</ul>
		<div id="tabs-visitordetails-details">
			<?php $_smarty_tpl->tpl_vars["full_width"] = new Smarty_Variable('Y', null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, "full_width", 0);?>
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.visitor_details.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

		</div>
		<div id="tabs-visitordetails-visitedpageshistory">
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.visitor_visited_pages_history.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

		</div>
		<div id="tabs-visitordetails-geotracking">
			<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.visitor_geotracking.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

		</div>
	</div>
</div>


<div id="dialog-online-internal-users" title="<?php echo smartyFunctionTranslate(array('s'=>'Online Staff'),$_smarty_tpl);?>
" style="display:none">
	<div id="ajax_online_internal_users_div">
		<?php $_smarty_tpl->smarty->ext->_subtemplate->render($_smarty_tpl, ((string)$_smarty_tpl->tpl_vars['module_templates_back_dir']->value)."/ajax.online_internal_users.tpl", $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, $_smarty_tpl->cache_lifetime, array(), 0, true);
?>

	</div>
</div>

<div id="dialog-predefined-messages" title="<?php echo smartyFunctionTranslate(array('s'=>'Predefined Messages'),$_smarty_tpl);?>
" style="display:none">
	<?php if ((!empty($_smarty_tpl->tpl_vars['predefined_messages']->value))) {?>
	<select name="predefined_messages_select" id="predefined_messages_select" class="lcp formfield form-control">
		<?php
$_from = $_smarty_tpl->tpl_vars['predefined_messages']->value;
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
			<option value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['id_predefinedmessage']);?>
" style="color: #606060; background-color: white;"><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['title']);?>
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
	
	<?php } else { ?>
		<?php echo smartyFunctionTranslate(array('s'=>'There are no predefined messages.'),$_smarty_tpl);?>

	<?php }?>
</div>

<div id="menu-emoticons" style="position:absolute; z-index: 9999; display:none; float:left; clear:both; background: white;" class="lcp panel">
	
	<input type="hidden" name="active_emoticon_menu" id="active_emoticon_menu" value="0">
	<table border="0" width="100%" cellspacing="0" cellpadding="0" class="lcp emoticon-table">
	<tr>
	<?php
$__section_id_0_saved = isset($_smarty_tpl->tpl_vars['__smarty_section_id']) ? $_smarty_tpl->tpl_vars['__section_id'] : false;
$__section_id_0_loop = (is_array(@$_loop=$_smarty_tpl->tpl_vars['emoticons']->value) ? count($_loop) : max(0, (int) $_loop));
$__section_id_0_total = $__section_id_0_loop;
$_smarty_tpl->tpl_vars['__smarty_section_id'] = new Smarty_Variable(array());
if ($__section_id_0_total != 0) {
for ($_smarty_tpl->tpl_vars['__smarty_section_id']->value['iteration'] = 1, $_smarty_tpl->tpl_vars['__smarty_section_id']->value['index'] = 0; $_smarty_tpl->tpl_vars['__smarty_section_id']->value['iteration'] <= $__section_id_0_total; $_smarty_tpl->tpl_vars['__smarty_section_id']->value['iteration']++, $_smarty_tpl->tpl_vars['__smarty_section_id']->value['index']++){
?>
	   <td align="center" style="text-align: center; width: 40px;">
	   	<input type="hidden" name="emoticon_code" id="emoticon_code_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['emoticons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_id']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_id']->value['index'] : null)]['id_emoticon']);?>
" value='<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['emoticons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_id']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_id']->value['index'] : null)]['code']);?>
'>
	   	<img title='<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['emoticons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_id']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_id']->value['index'] : null)]['code']);?>
' border="0" src="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['module_path']->value);?>
views/img/emoticons/<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['emoticons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_id']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_id']->value['index'] : null)]['filename']);?>
" id="emoticon_img_admin_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['emoticons']->value[(isset($_smarty_tpl->tpl_vars['__smarty_section_id']->value['index']) ? $_smarty_tpl->tpl_vars['__smarty_section_id']->value['index'] : null)]['id_emoticon']);?>
" class="lcp emoticon-img"></td>
	   <?php if ((isset($_smarty_tpl->tpl_vars['__smarty_section_id']->value['iteration']) ? $_smarty_tpl->tpl_vars['__smarty_section_id']->value['iteration'] : null)%5 == 0) {?>
	       </tr><tr>
	   <?php }?>
	<?php
}
}
if ($__section_id_0_saved) {
$_smarty_tpl->tpl_vars['__smarty_section_id'] = $__section_id_0_saved;
}
?>
	</tr>
	</table> 	
	
</div>

<?php echo '<script'; ?>
 type="text/javascript">
$('#tabs-visitors').trigger('click');
<?php echo '</script'; ?>
>


<?php }
}
