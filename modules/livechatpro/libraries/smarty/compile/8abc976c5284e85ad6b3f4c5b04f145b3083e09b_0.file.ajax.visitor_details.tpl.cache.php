<?php
/* Smarty version 3.1.28, created on 2018-01-17 04:15:28
  from "/var/www/ayawaddyonline/modules/livechatpro/views/templates/admin/ajax.visitor_details.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_5a5ecde04c4647_41343797',
  'file_dependency' => 
  array (
    '8abc976c5284e85ad6b3f4c5b04f145b3083e09b' => 
    array (
      0 => '/var/www/ayawaddyonline/modules/livechatpro/views/templates/admin/ajax.visitor_details.tpl',
      1 => 1510628788,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_5a5ecde04c4647_41343797 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '10121577105a5ecde04a65e6_23910865';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);?>


<!-- <input type="hidden" name="visitor_details_id_visitor" id="visitor_details_id_visitor" value=""> -->
<div style="height: 300px; overflow-y: scroll; overflow-x: hidden;">

	<table class="table table-striped table-hover" width="<?php if (isset($_smarty_tpl->tpl_vars['full_width']->value) && $_smarty_tpl->tpl_vars['full_width']->value == 'Y') {?>100%<?php } else { ?>50%<?php }?>">
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Name:'),$_smarty_tpl);?>
</td>
			<td id="details_name" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['name']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Email:'),$_smarty_tpl);?>
</td>
			<td id="details_email" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['email']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Phone:'),$_smarty_tpl);?>
</td>
			<td id="details_phone" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['phone']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Company:'),$_smarty_tpl);?>
</td>
			<td id="details_company" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['company']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Country:'),$_smarty_tpl);?>
</td>
			<td id="details_country" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['country']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'City:'),$_smarty_tpl);?>
</td>
			<td id="details_city" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['city']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Province:'),$_smarty_tpl);?>
</td>
			<td id="details_province" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['province']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'In Chat:'),$_smarty_tpl);?>
</td>
			<td id="details_in_chat" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['in_chat']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Language:'),$_smarty_tpl);?>
</td>
			<td id="details_language" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['language']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Visits:'),$_smarty_tpl);?>
</td>
			<td id="details_visits" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['visits']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Current page:'),$_smarty_tpl);?>
</td>
			<td id="details_current_page" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['current_page']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Host:'),$_smarty_tpl);?>
</td>
			<td id="details_host" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['host']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'IP:'),$_smarty_tpl);?>
</td>
			<td id="details_ip" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['ip']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Browser:'),$_smarty_tpl);?>
</td>
			<td id="details_browser" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['browser']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Timezone:'),$_smarty_tpl);?>
</td>
			<td id="details_timezone" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['timezone']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Resolution:'),$_smarty_tpl);?>
</td>
			<td id="details_resolution" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['resolution']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Online Time:'),$_smarty_tpl);?>
</td>
			<td id="details_online_time" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['online_time']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Referrer:'),$_smarty_tpl);?>
</td>
			<td id="details_referrer" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['referrer']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Page count:'),$_smarty_tpl);?>
</td>
			<td id="details_page_count" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['page_count']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Operating System:'),$_smarty_tpl);?>
</td>
			<td id="details_operating_system" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['os']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
		<tr>
			<td align="left"><?php echo smartyFunctionTranslate(array('s'=>'Last visit:'),$_smarty_tpl);?>
</td>
			<td id="details_last_visit" align="left"><?php echo (($tmp = @preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['visitor_details']->value['last_visit']))===null||$tmp==='' ? '' : $tmp);?>
</td>
		</tr>
	</table>
</div><?php }
}
