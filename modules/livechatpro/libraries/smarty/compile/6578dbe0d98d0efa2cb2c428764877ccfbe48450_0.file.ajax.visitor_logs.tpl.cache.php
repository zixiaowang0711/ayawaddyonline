<?php
/* Smarty version 3.1.28, created on 2017-03-30 09:42:41
  from "C:\localhost\htdocs\www\prestashop1615\modules\livechatpro\views\templates\admin\ajax.visitor_logs.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_58dcb6f1dcc588_52037593',
  'file_dependency' => 
  array (
    '6578dbe0d98d0efa2cb2c428764877ccfbe48450' => 
    array (
      0 => 'C:\\localhost\\htdocs\\www\\prestashop1615\\modules\\livechatpro\\views\\templates\\admin\\ajax.visitor_logs.tpl',
      1 => 1490763043,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58dcb6f1dcc588_52037593 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '2013058dcb6f1dbf5e2_54991497';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>

<div style="height: 300px; overflow-y: scroll; overflow-x: hidden;">
	<table id="visitor_logs_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
		<thead>
			<tr>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
				<th align="left"><?php echo smartyFunctionTranslate(array('s'=>'Message'),$_smarty_tpl);?>
</th>
			</thead>
			<tfoot>
			<tr>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
				<th align="left"><?php echo smartyFunctionTranslate(array('s'=>'Message'),$_smarty_tpl);?>
</th>
			</tr>
			</tfoot>
		</table>
	</div><?php }
}
