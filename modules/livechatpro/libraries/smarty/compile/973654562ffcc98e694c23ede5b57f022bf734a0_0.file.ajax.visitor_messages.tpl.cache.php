<?php
/* Smarty version 3.1.28, created on 2018-01-17 04:15:28
  from "/var/www/ayawaddyonline/modules/livechatpro/views/templates/admin/ajax.visitor_messages.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_5a5ecde048aea8_15325764',
  'file_dependency' => 
  array (
    '973654562ffcc98e694c23ede5b57f022bf734a0' => 
    array (
      0 => '/var/www/ayawaddyonline/modules/livechatpro/views/templates/admin/ajax.visitor_messages.tpl',
      1 => 1510628788,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a5ecde048aea8_15325764 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '3321039605a5ecde0484b84_91887634';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>

<div style="height: 300px; overflow-y: scroll; overflow-x: hidden;">
	<table id="visitor_messages_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
		<thead>
			<tr>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Name'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Email'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Phone'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Department'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Question'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'IP'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Status'),$_smarty_tpl);?>
</th>
			</thead>
			<tfoot>
			<tr>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Name'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Email'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Phone'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Department'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Question'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'IP'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Status'),$_smarty_tpl);?>
</th>
			</tr>
			</tfoot>
		</table>
	</div><?php }
}
