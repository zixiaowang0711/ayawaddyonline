<?php
/* Smarty version 3.1.28, created on 2017-05-19 08:33:26
  from "C:\localhost\htdocs\www\prestashop17\modules\livechatpro\views\templates\admin\ajax.online_internal_users.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_591e91b62f6285_32051913',
  'file_dependency' => 
  array (
    '98e747dd774ead2c30ecf572d4d478e06a1e380a' => 
    array (
      0 => 'C:\\localhost\\htdocs\\www\\prestashop17\\modules\\livechatpro\\views\\templates\\admin\\ajax.online_internal_users.tpl',
      1 => 1490763043,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_591e91b62f6285_32051913 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '19977591e91b62cce67_08688183';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);
if ((!empty($_smarty_tpl->tpl_vars['online_internal_users']->value)) && ($_smarty_tpl->tpl_vars['count_online_internal_users']->value > 1)) {?>
<select name="online_internal_users_select" id="online_internal_users_select" class="form-control">
	<?php
$_from = $_smarty_tpl->tpl_vars['online_internal_users']->value;
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
		<?php if ($_smarty_tpl->tpl_vars['id_staffprofile']->value != $_smarty_tpl->tpl_vars['value']->value['id_staffprofile']) {?>
			<option value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['id_staffprofile']);?>
"><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['firstname']);?>
 <?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['lastname']);?>
</option>
		<?php }?>
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
	<?php echo smartyFunctionTranslate(array('s'=>'There are no online staff members.'),$_smarty_tpl);?>

<?php }
}
}
