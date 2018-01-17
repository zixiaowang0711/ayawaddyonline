<?php
/* Smarty version 3.1.28, created on 2018-01-17 04:15:28
  from "/var/www/ayawaddyonline/modules/livechatpro/views/templates/admin/ajax.visitor_logs.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_5a5ecde049a050_23975727',
  'file_dependency' => 
  array (
    'ba980fd9709e0a3fc6c3d576c7c6344ade4276b6' => 
    array (
      0 => '/var/www/ayawaddyonline/modules/livechatpro/views/templates/admin/ajax.visitor_logs.tpl',
      1 => 1510628788,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a5ecde049a050_23975727 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '17283731405a5ecde04973c9_42063977';
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
