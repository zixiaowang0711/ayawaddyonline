<?php
/* Smarty version 3.1.28, created on 2017-05-19 08:33:25
  from "C:\localhost\htdocs\www\prestashop17\modules\livechatpro\views\templates\admin\ajax.visitor_ratings.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_591e91b5c90918_93098340',
  'file_dependency' => 
  array (
    '85de5ed3e5a34bdc0c4110cfbe2d47d3642d93b6' => 
    array (
      0 => 'C:\\localhost\\htdocs\\www\\prestashop17\\modules\\livechatpro\\views\\templates\\admin\\ajax.visitor_ratings.tpl',
      1 => 1490763043,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_591e91b5c90918_93098340 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '24077591e91b5c6f5f7_44024515';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>

<div style="height: 300px; overflow-y: scroll; overflow-x: hidden;">
	<table id="visitor_ratings_grid" class="lcp display compact nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
		<thead>
			<tr>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Politness'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Qualification'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Internal'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Name'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Email'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Company'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Comment'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Status'),$_smarty_tpl);?>
</th>
			</thead>
			<tfoot>
			<tr>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Politness'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Qualification'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Internal'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Name'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Email'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Company'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Comment'),$_smarty_tpl);?>
</th>
				<th><?php echo smartyFunctionTranslate(array('s'=>'Status'),$_smarty_tpl);?>
</th>
			</tr>
			</tfoot>
		</table>
	</div><?php }
}
