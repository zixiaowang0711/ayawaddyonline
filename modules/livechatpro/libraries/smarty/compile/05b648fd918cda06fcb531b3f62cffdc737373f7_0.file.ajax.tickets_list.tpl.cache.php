<?php
/* Smarty version 3.1.28, created on 2017-01-19 11:57:11
  from "C:\webserver\htdocs\www\prestashop16\modules\livechatpro\views\templates\front\ajax.tickets_list.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_58809b87976a35_06748534',
  'file_dependency' => 
  array (
    '05b648fd918cda06fcb531b3f62cffdc737373f7' => 
    array (
      0 => 'C:\\webserver\\htdocs\\www\\prestashop16\\modules\\livechatpro\\views\\templates\\front\\ajax.tickets_list.tpl',
      1 => 1484823180,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58809b87976a35_06748534 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '1828458809b87910873_40991141';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>
<table id="tickets_grid" class="lcp display compact responsive nowrap radius5" cellspacing="0" width="100%" style="border: 1px solid #dddddd;">
<thead>
	<tr>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Department'),$_smarty_tpl);?>
</th>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Subject'),$_smarty_tpl);?>
</th>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Staff'),$_smarty_tpl);?>
</th>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Client'),$_smarty_tpl);?>
</th>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Last reply'),$_smarty_tpl);?>
</th>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Priority'),$_smarty_tpl);?>
</th>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Status'),$_smarty_tpl);?>
</th>
	</thead>
	<tfoot>
	<tr>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Date add'),$_smarty_tpl);?>
</th>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Department'),$_smarty_tpl);?>
</th>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Subject'),$_smarty_tpl);?>
</th>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Staff'),$_smarty_tpl);?>
</th>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Client'),$_smarty_tpl);?>
</th>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Last reply'),$_smarty_tpl);?>
</th>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Priority'),$_smarty_tpl);?>
</th>
		<th><?php echo smartyFunctionTranslate(array('s'=>'Status'),$_smarty_tpl);?>
</th>
	</tr>
	</tfoot>
</table>

<div id="dialog-form-ticket-details" title="<?php echo smartyFunctionTranslate(array('s'=>'View ticket details'),$_smarty_tpl);?>
" style="display:none">
				<div style="width: 1000px; height:400px; overflow-y: scroll;" id="ajax_ticket_details_div">
					
				</div>
			</div>

<?php echo '<script'; ?>
 type="text/javascript">

$( "#dialog-form-ticket-details" ).dialog({
					autoOpen: false,
					/*height: 300,*/
					width: 1015,
					modal: false,
					close : function() {
						/*allFields.val( "" ).removeClass( "ui-state-error" );*/
					}
				});

<?php echo '</script'; ?>
>				<?php }
}
