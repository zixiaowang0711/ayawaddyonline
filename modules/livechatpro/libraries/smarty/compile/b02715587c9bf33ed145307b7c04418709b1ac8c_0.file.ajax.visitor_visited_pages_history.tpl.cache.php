<?php
/* Smarty version 3.1.28, created on 2017-03-30 09:42:41
  from "C:\localhost\htdocs\www\prestashop1615\modules\livechatpro\views\templates\admin\ajax.visitor_visited_pages_history.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_58dcb6f1a9beb1_34015400',
  'file_dependency' => 
  array (
    'b02715587c9bf33ed145307b7c04418709b1ac8c' => 
    array (
      0 => 'C:\\localhost\\htdocs\\www\\prestashop1615\\modules\\livechatpro\\views\\templates\\admin\\ajax.visitor_visited_pages_history.tpl',
      1 => 1490763043,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58dcb6f1a9beb1_34015400 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '1336158dcb6f1a619c2_32903438';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>

<div style="height: 299px; overflow-y: scroll; overflow-x: hidden;">
	<table class="table table-striped table-hover" width="100%">
		<tr>
			<td align="center"><b><?php echo smartyFunctionTranslate(array('s'=>'ID'),$_smarty_tpl);?>
</b></td>
			<td align="center"><b><?php echo smartyFunctionTranslate(array('s'=>'Time'),$_smarty_tpl);?>
</b></td>
			<td align="center"><b><?php echo smartyFunctionTranslate(array('s'=>'Duration'),$_smarty_tpl);?>
</b></td>
			<td><b><?php echo smartyFunctionTranslate(array('s'=>'URL'),$_smarty_tpl);?>
 </b></td>
			<td><b><?php echo smartyFunctionTranslate(array('s'=>'Referrer'),$_smarty_tpl);?>
</b></td>
		</tr>
		<?php if (!empty($_smarty_tpl->tpl_vars['visitor_details']->value['visited_pages'])) {?>
		<?php
$_from = $_smarty_tpl->tpl_vars['visitor_details']->value['visited_pages'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_value2_0_saved_item = isset($_smarty_tpl->tpl_vars['value2']) ? $_smarty_tpl->tpl_vars['value2'] : false;
$__foreach_value2_0_saved_key = isset($_smarty_tpl->tpl_vars['key2']) ? $_smarty_tpl->tpl_vars['key2'] : false;
$_smarty_tpl->tpl_vars['value2'] = new Smarty_Variable();
$__foreach_value2_0_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
if ($__foreach_value2_0_total) {
$_smarty_tpl->tpl_vars['key2'] = new Smarty_Variable();
foreach ($_from as $_smarty_tpl->tpl_vars['key2']->value => $_smarty_tpl->tpl_vars['value2']->value) {
$__foreach_value2_0_saved_local_item = $_smarty_tpl->tpl_vars['value2'];
?>
		<tr>
			<td align="center"><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value2']->value['id_visitedpage']);?>
</td>
			<td align="center"><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value2']->value['date_add']);?>
</td>
			<td align="center"><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value2']->value['duration']);?>
</td>
			<td><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value2']->value['url']);?>
</td>
			<td><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value2']->value['referrer']);?>
</td>
		</tr>
		<?php
$_smarty_tpl->tpl_vars['value2'] = $__foreach_value2_0_saved_local_item;
}
}
if ($__foreach_value2_0_saved_item) {
$_smarty_tpl->tpl_vars['value2'] = $__foreach_value2_0_saved_item;
}
if ($__foreach_value2_0_saved_key) {
$_smarty_tpl->tpl_vars['key2'] = $__foreach_value2_0_saved_key;
}
?>
		<?php }?>
	</table>
</div><?php }
}
