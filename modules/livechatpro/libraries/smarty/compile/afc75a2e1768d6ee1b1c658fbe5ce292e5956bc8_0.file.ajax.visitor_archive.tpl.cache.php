<?php
/* Smarty version 3.1.28, created on 2017-03-30 09:42:41
  from "C:\localhost\htdocs\www\prestashop1615\modules\livechatpro\views\templates\admin\ajax.visitor_archive.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_58dcb6f1bf1d38_26275517',
  'file_dependency' => 
  array (
    'afc75a2e1768d6ee1b1c658fbe5ce292e5956bc8' => 
    array (
      0 => 'C:\\localhost\\htdocs\\www\\prestashop1615\\modules\\livechatpro\\views\\templates\\admin\\ajax.visitor_archive.tpl',
      1 => 1490763043,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58dcb6f1bf1d38_26275517 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '3087158dcb6f1bc9a40_54328458';
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
