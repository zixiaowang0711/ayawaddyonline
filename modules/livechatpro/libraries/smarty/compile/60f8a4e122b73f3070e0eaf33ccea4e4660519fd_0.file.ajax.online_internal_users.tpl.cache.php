<?php
/* Smarty version 3.1.28, created on 2017-01-21 08:40:04
  from "C:\webserver\htdocs\www\prestashop16\modules\livechatpro\views\templates\admin\ajax.online_internal_users.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_58831054a6cbb5_52766175',
  'file_dependency' => 
  array (
    '60f8a4e122b73f3070e0eaf33ccea4e4660519fd' => 
    array (
      0 => 'C:\\webserver\\htdocs\\www\\prestashop16\\modules\\livechatpro\\views\\templates\\admin\\ajax.online_internal_users.tpl',
      1 => 1467130548,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58831054a6cbb5_52766175 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '2137558831054a4c405_40589060';
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
