<?php
/* Smarty version 3.1.28, created on 2016-10-13 13:33:08
  from "C:\webserver\htdocs\www\prestashop16\modules\livechatpro\views\templates\admin\ajax.onlineusers.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_57ff70f4ee7ff3_98106551',
  'file_dependency' => 
  array (
    'f62e9249b8aa330105d0de7ac2bb4d0289cf5a37' => 
    array (
      0 => 'C:\\webserver\\htdocs\\www\\prestashop16\\modules\\livechatpro\\views\\templates\\admin\\ajax.onlineusers.tpl',
      1 => 1475659780,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_57ff70f4ee7ff3_98106551 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '690157ff70f4e5ea85_01835915';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>

<div class="lcp panel-head border-bottom">
	<?php echo smartyFunctionTranslate(array('s'=>'Online Users'),$_smarty_tpl);?>
 (<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['count_total_online_users']->value);?>
) <span id="users_ajax_load_span" style="padding-left: 5px;"></span>
</div>


<div id="tabs-users">
	<ul>
		<li><a href="#tabs-users-external"><span class="badge"><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['count_online_external_users']->value);?>
</span> <?php echo smartyFunctionTranslate(array('s'=>'External'),$_smarty_tpl);?>
</a></li>
		<li><a href="#tabs-users-internal"><span class="badge"><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['count_online_internal_users']->value);?>
</span> <?php echo smartyFunctionTranslate(array('s'=>'Internal'),$_smarty_tpl);?>
</a></li>
	</ul>
	<div style="width: 100%; <?php if ($_smarty_tpl->tpl_vars['primary_settings']->value['chat_type_admin'] == 'Popup') {?>height: 275px;<?php }?> overflow-y: scroll;" id="div_scroll_users">
		<div id="tabs-users-external">
			<table border="0" width="100%" cellspacing="0" cellpadding="0" class="table table-striped table-hover">
				<tbody>
				<?php
$_from = $_smarty_tpl->tpl_vars['online_external_users']->value;
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
				<input type="hidden" name="id_external_user_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" id="id_external_user_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['id_user']);?>
">
				<input type="hidden" name="id_external_visitor_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" id="id_external_visitor_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['id_visitor']);?>
">
				
				<tr>
					<td class="lcp" id="online_external_users_td_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" style="text-align: left;"><?php if (!empty($_smarty_tpl->tpl_vars['value']->value['name'])) {
echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['name']);
} else { ?>Visitor ID: <?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['id_visitor']);
}?></td>
					<td class="lcp online_users_hover_td" style="cursor: hand; cursor: pointer; padding-left: 5px; padding-right: 5px;" align="right"></td>
				</tr>
				
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
				</tbody>
			</table>
		</div>
		<div id="tabs-users-internal">
			<table border="0" width="100%" cellspacing="0" cellpadding="0" class="table table-striped table-hover">
				<tbody>
				<!-- <tr><td colspan="2"><b>Support</b></td></tr> -->
				<?php
$_from = $_smarty_tpl->tpl_vars['online_internal_users']->value;
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
				<input type="hidden" name="id_internal_user_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" id="id_internal_user_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['id_user']);?>
">
				<input type="hidden" name="id_internal_staffprofile_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" id="id_internal_staffprofile_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['id_staffprofile']);?>
">
				<tr>
					<!-- <td>&nbsp;</td> -->
					<td class="lcp <?php if (!empty($_smarty_tpl->tpl_vars['value']->value['count_pending_archives'])) {?>blink<?php }?>" id="online_internal_users_td_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key']->value);?>
" style="text-align: left;"><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['firstname']);?>
 <?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['lastname']);?>
 (<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['count_online_archives']);?>
)<?php if ($_smarty_tpl->tpl_vars['id_staffprofile']->value == $_smarty_tpl->tpl_vars['value']->value['id_staffprofile']) {?><span style="color: #999999 !important;"> (Me)</span><?php }
if (!empty($_smarty_tpl->tpl_vars['value']->value['count_pending_archives'])) {?><span style="font-size: xx-small" class="lcp blink"> ringing...</span><?php }?></td>
					<td class="lcp online_users_hover_td" style="cursor: hand; cursor: pointer; padding-left: 5px; padding-right: 5px; <?php if ($_smarty_tpl->tpl_vars['id_staffprofile']->value == $_smarty_tpl->tpl_vars['value']->value['id_staffprofile']) {?>color: #cccccc !important;<?php }?>" align="right"></td>
				</tr>
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
				</tbody>
			</table>
		</div>
	</div>
</div>






<?php echo '<script'; ?>
 type="text/javascript">


if (parseInt($(window).width()) <= 768)
{
	$('div[id^="div_scroll_users"]').css(
	{
		'height': '50px',
	});
}

$( "#tabs-users" ).tabs({ active: lcp_active_users_tab });
			


<?php echo '</script'; ?>
><?php }
}
