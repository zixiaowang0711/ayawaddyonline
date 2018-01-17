<?php
/* Smarty version 3.1.28, created on 2017-11-20 07:09:16
  from "/var/www/ayawaddyonline/modules/livechatpro/views/templates/admin/ajax.translations.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_5a127f9c8c4b53_61382883',
  'file_dependency' => 
  array (
    '7dcb4df8b59e557b42512f22d248bb9fbf610070' => 
    array (
      0 => '/var/www/ayawaddyonline/modules/livechatpro/views/templates/admin/ajax.translations.tpl',
      1 => 1510628788,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a127f9c8c4b53_61382883 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '5002550925a127f9c8b5323_83862777';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>

<?php if (!empty($_smarty_tpl->tpl_vars['sections']->value)) {?>
	<form name="lang_form" id="lang_form" action="#" method="POST">
	<table border="0" width="100%" class="table-striped table-hover">

	<?php
$_from = $_smarty_tpl->tpl_vars['sections']->value;
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
                      
	      <?php if ($_smarty_tpl->tpl_vars['value']->value['section_expressions'] != '0') {?>
	      <tr style="font-weight: bold; height: 30px;">
	        <td>{<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['section_file_name']);?>
} - (<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['section_expressions']);?>
) <?php echo smartyFunctionTranslate(array('s'=>'expressions'),$_smarty_tpl);?>
</td>
	      </tr>
	      <?php
$_from = $_smarty_tpl->tpl_vars['value']->value;
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_value2_1_saved_item = isset($_smarty_tpl->tpl_vars['value2']) ? $_smarty_tpl->tpl_vars['value2'] : false;
$__foreach_value2_1_saved_key = isset($_smarty_tpl->tpl_vars['key2']) ? $_smarty_tpl->tpl_vars['key2'] : false;
$_smarty_tpl->tpl_vars['value2'] = new Smarty_Variable();
$__foreach_value2_1_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
if ($__foreach_value2_1_total) {
$_smarty_tpl->tpl_vars['key2'] = new Smarty_Variable();
foreach ($_from as $_smarty_tpl->tpl_vars['key2']->value => $_smarty_tpl->tpl_vars['value2']->value) {
$__foreach_value2_1_saved_local_item = $_smarty_tpl->tpl_vars['value2'];
?>
	      <?php if (is_numeric($_smarty_tpl->tpl_vars['key2']->value)) {?>
	      <tr style="height: 30px;">
	        <td>
	          <input type="hidden" value='<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['section_file_name']);?>
' name="section_file_name[]" id="section_file_name_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key2']->value);?>
">
	          <input type="hidden" value='<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value2']->value['text_from_files']);?>
' name="section_text_from_files[]" id="section_text_from_files_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key2']->value);?>
">
	          <table border="0" width="100%">
	            <tr>
	              <td width="50%"><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value2']->value['text_from_files']);?>
</td>
	              <td width="5">&nbsp;=&nbsp;</td>
	              <td width="50%"><textarea name="language_variables[]" id="language_variables_<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['key2']->value);?>
" class="form-control" style="width: 100%; height: 30px; <?php if (empty($_smarty_tpl->tpl_vars['value2']->value['text_from_lang_file'])) {?>background-color: #f2dede !important;<?php }?> resize:vertical;"><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value2']->value['text_from_lang_file']);?>
</textarea></td>
	            </tr>
	          </table>
	        </td>
	      </tr>
	      <?php }?>                                                
	      <?php
$_smarty_tpl->tpl_vars['value2'] = $__foreach_value2_1_saved_local_item;
}
}
if ($__foreach_value2_1_saved_item) {
$_smarty_tpl->tpl_vars['value2'] = $__foreach_value2_1_saved_item;
}
if ($__foreach_value2_1_saved_key) {
$_smarty_tpl->tpl_vars['key2'] = $__foreach_value2_1_saved_key;
}
?>     
	      
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

	</table>
</form>
	<?php }
}
}
