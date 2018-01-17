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

</tr>
{if $message}
<tr style="display: table-row;">
	<td colspan="12" style="padding-left:6%;">
        <div class="warn">
            <i class="icon-envelope"></i>
            {html_entity_decode($message|escape:'htmlall':'UTF-8')}
        </div>
    </td>
</tr>
{/if}
<tr style="display: table-row;">
	<td colspan="12" style="border:none!important; border-bottom:5px solid #a0d0eb!important; padding-left:6%;">
		<table class="table-grid clearfix" name="list_table" style="width:100%;">
			<tbody>
				<tr>
					<td style="padding:0!important;">
						<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;margin:0;">
							<thead>
								<tr class="nodrag nodrop" style="background-color: #AAADAF;color: white;">
									<th style="width:25%;padding:0;">{l s='Name' mod='wic_erp'}</th>
									<th class="center " style="width:15%;padding:0;">{l s='In stock' mod='wic_erp'}</th>
									<th class="center " style="width:15%;padding:0;">{l s='Quantity ordered' mod='wic_erp'}</th>
									<th class="center " style="width:15%;padding:0;">{l s='Stock reserved' mod='wic_erp'}</th>
									<th class="center " style="width:15%;padding:0;">{l s='Quantity refunded' mod='wic_erp'}</th>
									<th class="center " style="width:15%;padding:0;">{l s='Quantity to be ordered' mod='wic_erp'}</th>
								</tr>
							</thead>
							<tbody>
							{foreach $products as $product}
								<tr {if $product.product_quantity == $product.product_quantity_refunded}style="text-decoration:line-through;"{/if}>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} >{$product.product_name|escape:'html':'UTF-8'}</td>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center "><span class="badge badge-{if $product.in_stock <= 0}danger{else}success{/if}">{$product.in_stock|intval}</span></td>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center ">{$product.product_quantity|intval}</td>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center ">{if $product.product_quantity == $product.product_quantity_refunded}0{else}{math equation="x - y" x=$product.product_quantity_in_stock y=$product.product_quantity_refunded}{/if}</td>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center ">{$product.product_quantity_refunded|intval}</td>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center ">{if $product.product_quantity == $product.product_quantity_refunded || !$order_supplier_allow || ($product.product_quantity-$product.product_quantity_refunded-$product.product_quantity_in_stock-$product.product_quantity_refunded) < 0}0{else}{math equation="x - y" x=($product.product_quantity-$product.product_quantity_refunded) y=$product.product_quantity_in_stock-$product.product_quantity_refunded}{/if}</td>
								</tr>
                                                                {if isset($product.dlc) && $product.dlc}
                                                                    {foreach $product.dlc as $dlc}
                                                                        <tr>
                                                                            <td><b>{l s='Traceability' mod='wic_erp'}</b></td>
                                                                            <td>{l s='Batch number: ' mod='wic_erp'}<b>{$dlc.batchNumber|escape:'htmlall':'UTF-8'}</b></td>
                                                                            <td>{l s='DLC: ' mod='wic_erp'}<b>{$dlc.dlc|escape:'htmlall':'UTF-8'}</b></td>
                                                                            <td>{l s='BBD: ' mod='wic_erp'}<b>{$dlc.bbd|escape:'htmlall':'UTF-8'}</b></td>
                                                                            <td class="center">{l s='Quantity: ' mod='wic_erp'}<b>{$dlc.quantity|escape:'htmlall':'UTF-8'}</b></td>
                                                                            <td>{if $dlc.warehouse}{l s='Warehouse: ' mod='wic_erp'}<b>{$dlc.warehouse|escape:'htmlall':'UTF-8'}{/if}</b></td>
                                                                        </tr>
                                                                    {/foreach}
                                                                {/if}
							{/foreach}
							</tbody>
						</table>
					</td>
				</tr>
			</tbody>
		</table>
	</td>
</tr>