<?php
/* Smarty version 3.1.28, created on 2018-01-17 04:15:28
  from "/var/www/ayawaddyonline/modules/livechatpro/views/templates/admin/ajax.visitor_archive.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_5a5ecde0480df0_85234858',
  'file_dependency' => 
  array (
    '898e38b34f8a4b1216d6e5a7fc46753d7c1a499f' => 
    array (
      0 => '/var/www/ayawaddyonline/modules/livechatpro/views/templates/admin/ajax.visitor_archive.tpl',
      1 => 1510628788,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a5ecde0480df0_85234858 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '21202189135a5ecde0477437_88876208';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>

<!-- <input type="hidden" name="visitor_archive_id_visitor" id="visitor_archive_id_visitor" value=""> -->


<div style="height: 300px; overflow-y: scroll; overflow-x: hidden;">
	<table id="visitor_archive_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
		<thead>
			<tr>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'ID Chat'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Name'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Internal'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Department'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Email'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Phone'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Company'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Language'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Country'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'IP'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Host'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Duration'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Log entries'),$_smarty_tpl);?>
</th>
			</tr>
		</thead>
		<tfoot>
		<tr>
			<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
			<th><?php echo smartyFunctionTranslate(array('s'=>'ID Chat'),$_smarty_tpl);?>
</th>
			<th><?php echo smartyFunctionTranslate(array('s'=>'Name'),$_smarty_tpl);?>
</th>
			<th><?php echo smartyFunctionTranslate(array('s'=>'Internal'),$_smarty_tpl);?>
</th>
			<th><?php echo smartyFunctionTranslate(array('s'=>'Department'),$_smarty_tpl);?>
</th>
			<th><?php echo smartyFunctionTranslate(array('s'=>'Email'),$_smarty_tpl);?>
</th>
			<th><?php echo smartyFunctionTranslate(array('s'=>'Phone'),$_smarty_tpl);?>
</th>
			<th><?php echo smartyFunctionTranslate(array('s'=>'Company'),$_smarty_tpl);?>
</th>
			<th><?php echo smartyFunctionTranslate(array('s'=>'Language'),$_smarty_tpl);?>
</th>
			<th><?php echo smartyFunctionTranslate(array('s'=>'Country'),$_smarty_tpl);?>
</th>
			<th><?php echo smartyFunctionTranslate(array('s'=>'IP'),$_smarty_tpl);?>
</th>
			<th><?php echo smartyFunctionTranslate(array('s'=>'Host'),$_smarty_tpl);?>
</th>
			<th><?php echo smartyFunctionTranslate(array('s'=>'Duration'),$_smarty_tpl);?>
</th>
			<th><?php echo smartyFunctionTranslate(array('s'=>'Log entries'),$_smarty_tpl);?>
</th>
		</tr>
		</tfoot>
	</table>
</div><?php }
}
