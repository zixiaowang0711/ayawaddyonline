<?php
/* Smarty version 3.1.28, created on 2017-03-30 09:42:42
  from "C:\localhost\htdocs\www\prestashop1615\modules\livechatpro\views\templates\admin\ajax.ticket_details.tpl" */

if ($_smarty_tpl->smarty->ext->_validateCompiled->decodeProperties($_smarty_tpl, array (
  'has_nocache_code' => false,
  'version' => '3.1.28',
  'unifunc' => 'content_58dcb6f2358374_18606802',
  'file_dependency' => 
  array (
    '340dbeb154751d0570870d25e4bfd528f1758164' => 
    array (
      0 => 'C:\\localhost\\htdocs\\www\\prestashop1615\\modules\\livechatpro\\views\\templates\\admin\\ajax.ticket_details.tpl',
      1 => 1490763043,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_58dcb6f2358374_18606802 ($_smarty_tpl) {
$_smarty_tpl->compiled->nocache_hash = '1918458dcb6f22d3b20_97521748';
?>

<?php $_smarty_tpl->tpl_vars['template'] = new Smarty_Variable(((string)dirname($_smarty_tpl->source->filepath))."\\".((string)basename($_smarty_tpl->source->filepath)), null);
$_smarty_tpl->ext->_updateScope->updateScope($_smarty_tpl, 'template', 0);
if (!empty($_smarty_tpl->tpl_vars['ticket_details']->value)) {?>

<?php echo '<script'; ?>
 type="text/javascript">
var lcp_id_ticket = "<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['ticket_details']->value['id_ticket']);?>
";
var lcp_ticket_id_staffprofile = "<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['ticket_details']->value['id_staffprofile']);?>
";
var lcp_ticket_id_customer = "<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['ticket_details']->value['id_customer']);?>
";
<?php echo '</script'; ?>
>

<!-- <input type="hidden" name="lcp_id_ticket" id="lcp_id_ticket" value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['ticket_details']->value['id_ticket']);?>
" autocomplete="off">
<input type="hidden" name="lcp_ticket_id_staffprofile" id="lcp_ticket_id_staffprofile" value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['ticket_details']->value['id_staffprofile']);?>
" autocomplete="off">
<input type="hidden" name="lcp_ticket_id_customer" id="lcp_ticket_id_customer" value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['ticket_details']->value['id_customer']);?>
" autocomplete="off"> -->

<table class="table lcp panel">
	<tr>
		<td width="25%">
			<?php echo smartyFunctionTranslate(array('s'=>'Department:'),$_smarty_tpl);?>

		</td>
		<td width="25%">
			<select class="form-control" name="lcp_ticket_department" id="lcp_ticket_department">
				<?php
$_from = $_smarty_tpl->tpl_vars['departments']->value;
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
				<option value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['id_department']);?>
" <?php if ($_smarty_tpl->tpl_vars['ticket_details']->value['id_department'] == $_smarty_tpl->tpl_vars['value']->value['id_department']) {?>selected<?php }?>><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['name']);?>
</option>
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
			</select>
		</td>
		<td width="25%">
			<?php echo smartyFunctionTranslate(array('s'=>'Priority:'),$_smarty_tpl);?>

		</td>
		<td width="25%">
			<select class="form-control" name="lcp_ticket_priority" id="lcp_ticket_priority">
				<option value="Low" <?php if ($_smarty_tpl->tpl_vars['ticket_details']->value['priority'] == 'Low') {?>selected<?php }?>><?php echo smartyFunctionTranslate(array('s'=>'Low'),$_smarty_tpl);?>
</option>
				<option value="Medium" <?php if ($_smarty_tpl->tpl_vars['ticket_details']->value['priority'] == 'Medium') {?>selected<?php }?>><?php echo smartyFunctionTranslate(array('s'=>'Medium'),$_smarty_tpl);?>
</option>
				<option value="High" <?php if ($_smarty_tpl->tpl_vars['ticket_details']->value['priority'] == 'High') {?>selected<?php }?>><?php echo smartyFunctionTranslate(array('s'=>'High'),$_smarty_tpl);?>
</option>
			</select>
		</td>
	</tr>
	<tr>
		<td width="25%">
			<?php echo smartyFunctionTranslate(array('s'=>'Subject:'),$_smarty_tpl);?>

		</td>
		<td width="25%">
			<input type="text" name="lcp_ticket_subject" id="lcp_ticket_subject" class="form-control" value="<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['ticket_details']->value['subject']);?>
">
		</td>
		<td width="25%">
			<?php echo smartyFunctionTranslate(array('s'=>'Client:'),$_smarty_tpl);?>

		</td>
		<td width="25%">
			<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['ticket_details']->value['customer_name']);?>

		</td>
	</tr>
	<tr>
		<td width="25%">
			<?php echo smartyFunctionTranslate(array('s'=>'Status:'),$_smarty_tpl);?>

		</td>
		<td width="25%">
			<select class="form-control" name="lcp_ticket_status" id="lcp_ticket_status">
				<option value="Open" <?php if ($_smarty_tpl->tpl_vars['ticket_details']->value['status'] == 'Open') {?>selected<?php }?>><?php echo smartyFunctionTranslate(array('s'=>'Open'),$_smarty_tpl);?>
</option>
				<option value="Answered" <?php if ($_smarty_tpl->tpl_vars['ticket_details']->value['status'] == 'Answered') {?>selected<?php }?>><?php echo smartyFunctionTranslate(array('s'=>'Answered'),$_smarty_tpl);?>
</option>
				<option value="Customer-Reply" <?php if ($_smarty_tpl->tpl_vars['ticket_details']->value['status'] == 'Customer-Reply') {?>selected<?php }?>><?php echo smartyFunctionTranslate(array('s'=>'Customer-Reply'),$_smarty_tpl);?>
</option>
				<option value="In-Progress" <?php if ($_smarty_tpl->tpl_vars['ticket_details']->value['status'] == 'In-Progress') {?>selected<?php }?>><?php echo smartyFunctionTranslate(array('s'=>'In-Progress'),$_smarty_tpl);?>
</option>
				<option value="Closed" <?php if ($_smarty_tpl->tpl_vars['ticket_details']->value['status'] == 'Closed') {?>selected<?php }?>><?php echo smartyFunctionTranslate(array('s'=>'Closed'),$_smarty_tpl);?>
</option>
			</select>
		</td>
		<td width="25%">
			<?php echo smartyFunctionTranslate(array('s'=>'Staff:'),$_smarty_tpl);?>

		</td>
		<td width="25%">
			<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['ticket_details']->value['staff_name']);?>

		</td>
	</tr>
	<tr>
		<td colspan="4" align="center">
			<a href="javascript:{}" name="lcp_ticket_save_a" id="lcp_ticket_save_a" class="lcp button" title="<?php echo smartyFunctionTranslate(array('s'=>'Save'),$_smarty_tpl);?>
"><span class="fa fa-save"></span> <?php echo smartyFunctionTranslate(array('s'=>'Save'),$_smarty_tpl);?>
</a>
		</td>
	</tr>
</table>


<br>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 col-centered lcp panel">
	<div><textarea id="lcp_ticket_reply_textarea" name="lcp_ticket_reply_textarea" rows="2"></textarea></div>
	<div style="text-align: center; padding-top: 5px;"><a href="javascript:{}" name="lcp_ticket_add_reply_a" id="lcp_ticket_add_reply_a" class="lcp button" title="<?php echo smartyFunctionTranslate(array('s'=>'Add reply'),$_smarty_tpl);?>
"><span class="fa fa-plus"></span> <?php echo smartyFunctionTranslate(array('s'=>'Add reply'),$_smarty_tpl);?>
</a></div>
</div>

<div class="clearfix"></div>
<br>

<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 lcp panel">
	<?php if (!empty($_smarty_tpl->tpl_vars['ticket_details']->value['replyes'])) {?>
		<?php
$_from = $_smarty_tpl->tpl_vars['ticket_details']->value['replyes'];
if (!is_array($_from) && !is_object($_from)) {
settype($_from, 'array');
}
$__foreach_value_1_saved_item = isset($_smarty_tpl->tpl_vars['value']) ? $_smarty_tpl->tpl_vars['value'] : false;
$__foreach_value_1_saved_key = isset($_smarty_tpl->tpl_vars['key']) ? $_smarty_tpl->tpl_vars['key'] : false;
$_smarty_tpl->tpl_vars['value'] = new Smarty_Variable();
$__foreach_value_1_total = $_smarty_tpl->smarty->ext->_foreach->count($_from);
if ($__foreach_value_1_total) {
$_smarty_tpl->tpl_vars['key'] = new Smarty_Variable();
foreach ($_from as $_smarty_tpl->tpl_vars['key']->value => $_smarty_tpl->tpl_vars['value']->value) {
$__foreach_value_1_saved_local_item = $_smarty_tpl->tpl_vars['value'];
?>
			<?php if ($_smarty_tpl->tpl_vars['value']->value['reply_from'] == 'Staff') {?>
			<table class="table">
					<tr>
						<td width="25%" style="color: red;">
							<b>Staff:</b><br><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['ticket_details']->value['staff_name']);?>
<br><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['ticket_details']->value['staff_email']);?>

						</td>
						<td>
							<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['message']);?>

						</td>
					</tr>
				</table>
				
			<?php } else { ?>
				<table class="table">
					<tr>
						<td width="25%">
							<b>Client:</b><br><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['ticket_details']->value['customer_name']);?>
<br><?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['ticket_details']->value['customer_email']);?>

						</td>
						<td>
							<?php echo preg_replace("%(?<!\\\\)'%", "\'",$_smarty_tpl->tpl_vars['value']->value['message']);?>

						</td>
					</tr>
				</table>
			<?php }?>
		<?php
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_1_saved_local_item;
}
}
if ($__foreach_value_1_saved_item) {
$_smarty_tpl->tpl_vars['value'] = $__foreach_value_1_saved_item;
}
if ($__foreach_value_1_saved_key) {
$_smarty_tpl->tpl_vars['key'] = $__foreach_value_1_saved_key;
}
?>
	<?php } else { ?>
		<?php echo smartyFunctionTranslate(array('s'=>'There are no replyes for this ticket!'),$_smarty_tpl);?>

	<?php }?>
</div>

<?php }?>

<?php }
}
