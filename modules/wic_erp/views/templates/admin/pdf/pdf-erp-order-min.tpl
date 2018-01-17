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

<div style="font-size: 9pt; color: #444">
	
	<!-- SUPPLIER ADDRESS -->
	<div style="text-align: right;">
		<table style="width: 70%">
			<tr>
				<td style="font-size: 13pt; font-weight: bold">{$supplier_name|escape:'html':'UTF-8'}</td>
			</tr>
			<tr>
				<td style="font-size: 13pt; font-weight: bold">{$address_supplier->address1|escape:'html':'UTF-8'}</td>
			</tr>
			{* if the address has two parts *}
			{if !empty($address_supplier->address2)}
			<tr>
				<td style="font-size: 13pt; font-weight: bold">{$address_supplier->address2|escape:'html':'UTF-8'}</td>
			</tr>
			{/if}
			<tr>
				<td style="font-size: 13pt; font-weight: bold">{$address_supplier->postcode|escape:'html':'UTF-8'} {$address_supplier->city|escape:'html':'UTF-8'}</td>
			</tr>
			<tr>
				<td style="font-size: 13pt; font-weight: bold">{$address_supplier->country|escape:'html':'UTF-8'}</td>
			</tr>
		</table>
	</div>
	<!-- / SUPPLIER ADDRESS -->

	<table>
		<tr><td style="line-height: 8px">&nbsp;</td></tr>
	</table>

	{l s='Products ordered:' pdf='true' mod='wic_erp'}
	<!-- PRODUCTS -->
	<div style="font-size: 7pt;">
		<table style="width: 100%;">
			<tr style="line-height:6px; border: none">
				<td style="width: 10%; text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 2px; font-weight: bold;">{l s='Supplier reference' pdf='true' mod='wic_erp'}</td>
				<td style="width: 55%; text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 2px; font-weight: bold;">{l s='Designation' pdf='true' mod='wic_erp'}</td>
				<td style="width: 15%; text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 2px; font-weight: bold;">{l s='Reference' pdf='true' mod='wic_erp'}</td>
				<td style="width: 10%; text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 2px; font-weight: bold;">{l s='Qty' pdf='true' mod='wic_erp'}</td>
				<td style="width: 10%; text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 2px; font-weight: bold;">{l s='Total TI' pdf='true' mod='wic_erp'}</td>
			</tr>
			{* for each product ordered *}
			{foreach $erp_order_details as $erp_order_detail}
			<tr>
				<td style="text-align: left; padding-left: 1px;">{$erp_order_detail->supplier_reference|escape:'html':'UTF-8'}</td>
				<td style="text-align: left; padding-left: 1px;">{$erp_order_detail->name|escape:'html':'UTF-8'}</td>
				<td style="text-align: left; padding-left: 1px;">{$erp_order_detail->reference|escape:'html':'UTF-8'}</td>
				<td style="text-align: center; padding-right: 1px;">{$erp_order_detail->quantity_ordered|round:"2"|escape:'htmlall':'UTF-8'}</td>
				<td style="text-align: right; padding-right: 1px;">{$currency->prefix|escape:'html':'UTF-8'} {$erp_order_detail->price_ti|round:"2"|escape:'htmlall':'UTF-8'} {$currency->suffix|escape:'html':'UTF-8'}</td>
			
			</tr>
			
			
			{/foreach}
		</table>
	</div>
	<!-- / PRODUCTS -->
	<table>
		<tr><td style="line-height: 8px">&nbsp;</td></tr>
	</table>

	{l s='Taxes:' pdf='true' mod='wic_erp'}
	<!-- PRODUCTS TAXES -->
	<div style="font-size: 6pt;">
		<table style="width: 30%;">
				<tr style="line-height:6px; border: none">
					<td style="text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 2px; font-weight: bold;">{l s='Base TE' pdf='true' mod='wic_erp'}</td>
					<td style="text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 2px; font-weight: bold;">{l s='Tax Rate' pdf='true' mod='wic_erp'}</td>
					<td style="text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 2px; font-weight: bold;">{l s='Tax Value' pdf='true' mod='wic_erp'}</td>
				</tr>
				{foreach $tax_order_summary as $entry}
				<tr style="line-height:6px; border: none">
					<td style="text-align: right; padding-right: 1px;">{$currency->prefix|escape:'html':'UTF-8'} {$entry['base_te']|round:"2"|escape:'htmlall':'UTF-8'} {$currency->suffix|escape:'html':'UTF-8'}</td>
					<td style="text-align: right; padding-right: 1px;">{$entry['tax_rate']|escape:'html':'UTF-8'}</td>
					<td style="text-align: right; padding-right: 1px;">{$currency->prefix|escape:'html':'UTF-8'} {$entry['total_tax_value']|round:"2"|escape:'htmlall':'UTF-8'} {$currency->suffix|escape:'html':'UTF-8'}</td>
				</tr>
				{/foreach}
		</table>
	</div>
	<!-- / PRODUCTS TAXES -->
	
	<table>
		<tr><td style="line-height: 8px">&nbsp;</td></tr>
	</table>
	
	{l s='Summary:' pdf='true' mod='wic_erp'}
	<!-- TOTAL -->
	<div style="font-size: 6pt;">
		<table style="width: 30%;">
				<tr style="line-height:6px; border: none">
					<td style="text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 2px; font-weight: bold;">{l s='Total TE' pdf='true' mod='wic_erp'} <br /> {l s='(Discount excluded)' pdf='true' mod='wic_erp'}</td>
					<td width="43px" style="text-align: right;">{$currency->prefix|escape:'html':'UTF-8'} {$erp_order->total_te|round:"2"|escape:'htmlall':'UTF-8'} {$currency->suffix|escape:'html':'UTF-8'}</td>
				</tr>
				<tr style="line-height:6px; border: none">
					<td style="text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 2px; font-weight: bold;">{l s='Order Discount' pdf='true' mod='wic_erp'}</td>
					<td width="43px" style="text-align: right;">{$currency->prefix|escape:'html':'UTF-8'} {$erp_order->discount_value_te|round:"2"|escape:'htmlall':'UTF-8'} {$currency->suffix|escape:'html':'UTF-8'}</td>
				</tr>
				<tr style="line-height:6px; border: none">
					<td style="text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 2px; font-weight: bold;">{l s='Total TE' pdf='true' mod='wic_erp'} <br /> {l s='(Discount included)' pdf='true' mod='wic_erp'}</td>
					<td width="43px" style="text-align: right;">{$currency->prefix|escape:'html':'UTF-8'} {$erp_order->total_with_discount_te|round:"2"|escape:'htmlall':'UTF-8'} {$currency->suffix|escape:'html':'UTF-8'}</td>
				</tr>
				<tr style="line-height:6px; border: none">
					<td style="text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 2px; font-weight: bold;">{l s='Tax value' pdf='true' mod='wic_erp'}</td>
					<td width="43px" style="text-align: right;">{$currency->prefix|escape:'html':'UTF-8'} {$erp_order->total_tax|round:"2"|escape:'htmlall':'UTF-8'} {$currency->suffix|escape:'html':'UTF-8'}</td>
				</tr>
				<tr style="line-height:6px; border: none">
					<td style="text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 2px; font-weight: bold;">{l s='Total TI' pdf='true' mod='wic_erp'}</td>
					<td width="43px" style="text-align: right;">{$currency->prefix|escape:'html':'UTF-8'} {$erp_order->total_ti|round:"2"|escape:'htmlall':'UTF-8'} {$currency->suffix|escape:'html':'UTF-8'}</td>
				</tr>
				<tr style="line-height:6px; border: none">
					<td style="text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 2px; font-weight: bold;">{l s='TOTAL TO PAY' pdf='true' mod='wic_erp'}</td>
					<td width="43px" style="text-align: right;">{$currency->prefix|escape:'html':'UTF-8'} {$erp_order->total_ti|round:"2"|escape:'htmlall':'UTF-8'} {$currency->suffix|escape:'html':'UTF-8'}</td>
				</tr>
		</table>
	</div>
	<!-- / TOTAL -->
</div>
