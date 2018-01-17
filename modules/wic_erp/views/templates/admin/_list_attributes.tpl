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
{if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}
    <td align="center">&nbsp;</td>
{/if}
</tr>
<tr style="display: table-row;">
	{if version_compare($smarty.const._PS_VERSION_,'1.6','<')}
		<td colspan="8" style="border:none!important;">
	{else}
		<td colspan="9" style="border:none!important;">
	{/if}
		<table class="table-grid" name="list_table" style="width:100%;">
			<tbody>
				<tr>
					<td>
						<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%; margin-bottom:10px;">
							<thead>
								<tr class="nodrag nodrop" style="height: 40px">
									<th style="width:10%;">{l s='Id Attribute' mod='wic_erp'}</th>
									<th style="width:30%;">{l s='Name' mod='wic_erp'}</th>
									<th class="center" style="width:12%;">{l s='Min Stock' mod='wic_erp'}</th>
									<th class="center" style="width:12%;">{l s='Safety Stock' mod='wic_erp'}</th>
									<th class="center" style="width:12%;">{l s='Real Stock' mod='wic_erp'}</th>
									<th class="center" style="width:12%;">{l s='Unit order' mod='wic_erp'}</th>
                                                                        <th class="center" style="width:12%;">{l s='Bundling' mod='wic_erp'}</th>
									<th class="center" style="width:10%;">{l s='Manual configuration' mod='wic_erp'}</th>
								</tr>
							</thead>
							<tbody>
							{foreach $products as $product}
								<tr {if ($product.min_quantity+$product.safety_stock) > $product.stock}style="background-color: #fab4bf"{else}style="background-color: #b4fac5"{/if}>
									<td style="background-color:inherit;">{if $product.id_product_attribute}{$product.id_product_attribute|intval}{else}--{/if}</td>
									<td style="background-color:inherit;">{if $product.id_product_attribute}{$product.name|escape:'htmlall':'UTF-8'}{else}{l s='This product is a simple product and it has no features' mod='wic_erp'}{/if}</td>
									<td class="center" style="background-color:inherit;"><input type="text" name="min_quantity[{$product.id_product|intval}_{$product.id_product_attribute|intval}]" value="{$product.min_quantity|intval}" size="4"></td>
									<td class="center" style="background-color:inherit;"><input type="text" name="safety_stock[{$product.id_product|intval}_{$product.id_product_attribute|intval}]" value="{$product.safety_stock|intval}" size="4"></td>
									<td class="center" style="background-color:inherit;">{$product.stock|intval}</td>
									<td class="center" style="background-color:inherit;"><input type="text" name="unit_order[{$product.id_product|intval}_{$product.id_product_attribute|intval}]" value="{$product.unit_order|intval}" size="4"></td>
									<td class="center" style="background-color:inherit;"><input type="text" name="bundling[{$product.id_product|intval}_{$product.id_product_attribute|intval}]" value="{$product.bundling|intval}" size="4"></td>
                                                                        <td class="center" style="background-color:inherit;"><input type="checkbox" name="manual_configuration[{$product.id_product|intval}_{$product.id_product_attribute|intval}]" value="1" {if $product.manual_configuration}checked="checked"{/if}></td>
								</tr>
							{/foreach}
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</td>
</tr>