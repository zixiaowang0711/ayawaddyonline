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
	<div id="order-detail-erp-content" class="table_block table-responsive">
		<table class="table table-bordered">
			<thead>
				<tr>
					<th style="width:30%;">{l s='Name' mod='wic_erp'}</th>
					<th class="center" style="width:15%;">{l s='Quantity ordered' mod='wic_erp'}</th>
					<th class="center" style="width:15%;">{l s='Stock reserved' mod='wic_erp'}</th>
					<th class="center" style="width:15%;">{l s='Quantity refunded' mod='wic_erp'}</th>
					<th class="center" style="width:10%;">{l s='Status' mod='wic_erp'}</th>
					<th class="center" style="width:15%;">{l s='Availability' mod='wic_erp'}</th>
				</tr>
			</thead>
			<tbody>
				{foreach $products AS $product}
					<tr {if $product.product_quantity == $product.product_quantity_refunded}style="text-decoration:line-through;"{/if}>
						<td>{$product.product_name|escape:'htmlall':'UTF-8'}</td>
						<td class="center">{$product.product_quantity|intval}</td>
						<td>{if $product.product_quantity == $product.product_quantity_refunded || !$order_supplier_allow}0{else}{math equation="x - y" x=$product.product_quantity_in_stock y=$product.product_quantity_refunded}{/if}</td>
						<td>{$product.product_quantity_refunded|intval}</td>
						<td>{if ($product.product_quantity - $product.product_quantity_refunded) == $product.product_quantity_in_stock && $order_supplier_allow}<span class="label label-success">{l s='Complete' mod='wic_erp'}</span>{else}<span class="label label-danger">{l s='Pending' mod='wic_erp'}</span>{/if}</td>
						<td>{if ($product.product_quantity - $product.product_quantity_refunded) == $product.product_quantity_in_stock || !$order_supplier_allow}{l s='--' mod='wic_erp'}{elseif $product.unavailable_message}{html_entity_decode($product.unavailable_message|escape:'htmlall':'UTF-8')}{else}--{/if}</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
{else}
	<div id="order-detail-erp-content" class="table_block">
		<table class="std">
			<thead>
				<tr>
					<th style="width:30%;" class="first_item">{l s='Name' mod='wic_erp'}</th>
					<th class="center item" style="width:15%;">{l s='Quantity ordered' mod='wic_erp'}</th>
					<th class="center item" style="width:15%;">{l s='Stock reserved' mod='wic_erp'}</th>
					<th class="center item" style="width:15%;">{l s='Quantity refunded' mod='wic_erp'}</th>
					<th class="center item" style="width:10%;">{l s='Status' mod='wic_erp'}</th>
					<th class="center last_item" style="width:15%;">{l s='Availability' mod='wic_erp'}</th>
				</tr>
			</thead>
			<tbody>
				{foreach $products AS $product}
					<tr {if $product.product_quantity == $product.product_quantity_refunded}style="text-decoration:line-through;"{/if} class="item">
						<td>{$product.product_name|escape:'htmlall':'UTF-8'}</td>
						<td class="center">{$product.product_quantity|intval}</td>
						<td>{if $product.product_quantity == $product.product_quantity_refunded || !$order_supplier_allow}0{else}{math equation="x - y" x=$product.product_quantity_in_stock y=$product.product_quantity_refunded}{/if}</td>
						<td>{$product.product_quantity_refunded|intval}</td>
						<td>{if ($product.product_quantity - $product.product_quantity_refunded) == $product.product_quantity_in_stock && $order_supplier_allow}<span class="label label-success">{l s='Complete' mod='wic_erp'}</span>{else}<span class="label label-danger">{l s='Pending' mod='wic_erp'}</span>{/if}</td>
						<td>{if ($product.product_quantity - $product.product_quantity_refunded) == $product.product_quantity_in_stock || !$order_supplier_allow}{l s='--' mod='wic_erp'}{elseif $product.unavailable_message}{html_entity_decode($product.unavailable_message|escape:'htmlall':'UTF-8')}{else}--{/if}</td>
					</tr>
				{/foreach}
			</tbody>
		</table>
	</div>
{/if}