{*
* Module My Easy ERP Web In Color 
* 
*  @author    Web In Color - addons@webincolor.fr
*  @version 2.6
*  @uses Prestashop modules
*  @since 1.0 - mai 2014
*  @package Wic ERP
*  @copyright Copyright &copy; 2014, Web In Color
*  @license   http://www.webincolor.fr
*}

{extends file="helpers/form/form.tpl"}

{block name="other_input"}

{if isset($erp_order) && $erp_order->id > 0 && isset($erp_order_states) && !isset($printed)}
{assign var="printed" value=1}
<script>
$(document).ready(function() {
	$('#id_erp_order_state option').each(function () {
		
		if ($(this).attr('disabled') == false)
			$(this).attr('selected', true);
		
		return ($(this).attr('disabled') == true);
		
	});
});
</script>
{assign var=order_state value=$erp_order_state->name[$employee->id_lang]|regex_replace:"/[^A-Za-z_ \t]/":""}
<div class="alert alert-warning"><strong>{l s='Current order state: %s'|sprintf:$order_state mod='wic_erp'}</strong></div>
<div class="alert alert-info">{l s='Choose the new status for your order' mod='wic_erp'}</div>
<div class="form-horizontal">
	<input type="hidden" name="id_erp_order" id="id_erp_order" value="{$erp_order->id|intval}">
	<div class="form-group">
		<label class="control-label col-lg-3">{l s='Status of the order:' mod='wic_erp'}</label>						
		<div class="col-lg-9">
			<select name="id_erp_order_state" id="id_erp_order_state">
			{foreach $erp_order_states as $state}
				<option value="{$state['id_erp_order_state']|intval}" {if $state['allowed'] == 0} disabled="disabled" {/if}>{$state['name']|escape:'html':'UTF-8'}</option>
			{/foreach}
			</select>
		</div>
	</div>
</div>
<div class="margin-form">
<input type="submit" id="{$table|escape:'htmlall':'UTF-8'}_form_submit_btn" value="{l s='Save' mod='wic_erp'}" name="submitChangestate" class="button" style="display: none;">
</div>
{/if}
{/block}
{block name="other_fieldsets"}							
{if isset($erp_order_state) && isset($erp_order) && $erp_order_state->id > 1}
<div class="panel" id='update_status'>
	<h3><i class="icon-download-alt"></i> {l s='Print the supply order form' mod='wic_erp'}</h3>
	<a href="{$link->getAdminLink('AdminErpSupplierOrders16')|escape:'htmlall':'UTF-8'}&generateErpOrderFormPDF&id_erp_order={$erp_order->id|intval}" onclick="return !window.open(this.href);" title="Export as PDF" class="btn btn-default"><i class="icon-download-alt"></i> {l s='Click here to download the supply order form.' mod='wic_erp'}.</a>
</div>
{/if}

{/block}
