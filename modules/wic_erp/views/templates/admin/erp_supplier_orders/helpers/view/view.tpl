{*
* 2014 Web In Color
*
*  @author Web In Color <support@webincolor.fr>
*  @copyright  2014 Web In Color
*  @license    Web In Color
*  International Registered Trademark & Property of Web In Color
*}

{extends file="helpers/view/view.tpl"}
{block name="override_tpl"}

<div class="row">
	<div class="col-lg-6">
		<div class="panel">
			<h3>
				<i class="icon-info"></i>{l s='General information' mod='wic_erp'}
			</h3>
			<table style="width: 400px;" classe="table">
				<tr>
					<td>{l s='Creation date:' mod='wic_erp'}</td>
					<td>{$erp_order_creation_date|escape:'htmlall':'UTF-8'}</td>
				</tr>
				<tr>
					<td>{l s='Supplier:' mod='wic_erp'}</td>
					<td>{$erp_order_supplier_name|escape:'htmlall':'UTF-8'}</td>
				</tr>
				<tr>
					<td>{l s='Last update:' mod='wic_erp'}</td>
					<td>{$erp_order_last_update|escape:'htmlall':'UTF-8'}</td>
				</tr>
				<tr>
					<td>{l s='Delivery expected:' mod='wic_erp'}</td>
					<td>{$erp_order_expected|escape:'htmlall':'UTF-8'}</td>
				</tr>
				<tr>
					<td>{l s='Currency:' mod='wic_erp'}</td>
					<td>{$erp_order_currency->name|escape:'htmlall':'UTF-8'}</td>
				</tr>
				<tr>
					<td>{l s='Global discount rate:' mod='wic_erp'}</td>
					<td>{$erp_order_discount_rate|escape:'htmlall':'UTF-8'} %</td>
				</tr>
				<tr>
					<td>{l s='Shipping tax rate:' mod='wic_erp'}</td>
					<td>{$erp_order_shipping_tax_rate|escape:'htmlall':'UTF-8'} %</td>
				</tr>
                                {if $vat_exemption}
                                    <tr>
                                            <td colspan="2"><span class="label label-warning">{l s='VAT exemption' mod='wic_erp'}</span></td>
                                    </tr>
                                {/if}
			</table>
		</div>
	</div>
	<div class="col-lg-6">
		<div class="panel">
			<h3>
				<i class="icon-th-list"></i>{l s='Summary' mod='wic_erp'}
			</h3>
			<table style="width: 400px;" classe="table">
				<tr>
					<th>{l s='Designation' mod='wic_erp'}</th>
					<th width="100px">{l s='Value' mod='wic_erp'}</th>
				</tr>
				<tr>
					<td bgcolor="#000000"></td>
					<td bgcolor="#000000"></td>
				</tr>
				<tr>
					<td>{l s='Total (tax excl.)' mod='wic_erp'}</td>
					<td align="right">{$erp_order_total_te|escape:'htmlall':'UTF-8'}</td>
				</tr>
				<tr>
					<td>{l s='Discount' mod='wic_erp'}</td>
					<td align="right">{$erp_order_discount_value_te|escape:'htmlall':'UTF-8'}</td>
				</tr>
				<tr>
					<td>{l s='Total with discount (tax excl.)' mod='wic_erp'}</td>
					<td align="right">{$erp_order_total_with_discount_te|escape:'htmlall':'UTF-8'}</td>
				</tr>
				<tr>
					<td bgcolor="#000000"></td>
					<td bgcolor="#000000"></td>
				</tr>
				<tr>
					<td>{l s='Shipping Cost (tax excl.)' mod='wic_erp'}</td>
					<td align="right">{$erp_order_shipping_cost|escape:'htmlall':'UTF-8'}</td>
				</tr>
				<tr>
					<td>{l s='Shipping tax' mod='wic_erp'}</td>
					<td align="right">{$erp_order_shipping_tax|escape:'htmlall':'UTF-8'}</td>
				</tr>
				<tr>
					<td bgcolor="#000000"></td>
					<td bgcolor="#000000"></td>
				</tr>
				<tr>
					<td>{l s='Total Tax' mod='wic_erp'}</td>
					<td align="right">{$erp_order_total_tax|escape:'htmlall':'UTF-8'}</td>
				</tr>
				<tr>
					<td>{l s='Total (tax incl.)' mod='wic_erp'}</td>
					<td align="right">{$erp_order_total_ti|escape:'htmlall':'UTF-8'}</td>
				</tr>
				<tr>
					<td bgcolor="#000000"></td>
					<td bgcolor="#000000"></td>
				</tr>
				<tr>
					<td>{l s='TOTAL TO PAY' mod='wic_erp'}</td>
					<td align="right">{$erp_order_total_ti|escape:'htmlall':'UTF-8'}</td>
				</tr>
			</table>
		</div>
	</div>
</div>
{html_entity_decode($erp_order_detail_content|escape:'htmlall':'UTF-8')|replace:'&#039;':'\''}

{/block}
