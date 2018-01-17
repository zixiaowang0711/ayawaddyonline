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

{if version_compare($smarty.const._PS_VERSION_,'1.6','<')}
<br/><br/>
<style>
.ui-accordion .ui-accordion-content {
padding: 1em 2.2em;
border-top: 0;
overflow: auto;
}
.ui-accordion .ui-accordion-header {
display: block;
cursor: pointer;
position: relative;
margin-top: 2px;
padding: .5em .5em .5em .7em;
min-height: 0;
/* support: IE7; */
}

.badge {
	background-color: #fff;
	color: #fff;
	font-weight:bold;
	padding: 1px 5px;
	border-radius: 10px;
}

.badge.badge-danger {
background-color: #d9534f;
}

.badge.badge-success {
background-color: #79bd3c;
}

</style>
<fieldset>
		<legend>
			<img src="../modules/wic_erp/logo.gif" /> {l s='My Easy ERP' mod='wic_erp'}
		</legend>
		<div id="product_accordion" style="margin-top:10px; display:block;" class="ui-accordion ui-widget ui-helper-reset" role="tablist">
		<h4 style="margin-bottom:0;border-bottom:1px solid #000; color:#00aff0;" class="ui-accordion-header ui-helper-reset ui-state-default ui-accordion-header-active ui-state-active ui-corner-top ui-accordion-icons" role="tab" id="ui-accordion-product_accordion-header-0" aria-controls="ui-accordion-product_accordion-panel-0" aria-selected="true" tabindex="0">{l s='Product purchased' mod='wic_erp'} <span class="badge">{$products|@sizeof|escape:'htmlall':'UTF-8'}</span></h4>
		<div style="display: block;" class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active" id="ui-accordion-product_accordion-panel-0" aria-labelledby="ui-accordion-product_accordion-header-0" role="tabpanel" aria-expanded="true" aria-hidden="false">
				
					<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;">
						<thead>
							<tr class="nodrag nodrop">
								<th style="width:25%;">{l s='Name' mod='wic_erp'}</th>
								<th class="center" style="width:15%;">{l s='In stock' mod='wic_erp'}</th>
								<th class="center" style="width:15%;">{l s='Quantity ordered' mod='wic_erp'}</th>
								<th class="center" style="width:15%;">{l s='Stock reserved' mod='wic_erp'}</th>
								<th class="center" style="width:15%;">{l s='Quantity refunded' mod='wic_erp'}</th>
								<th class="center" style="width:15%;">{l s='Quantity to be ordered' mod='wic_erp'}</th>
							</tr>
						</thead>
						<tbody>
						{foreach $products AS $product}
								<tr {if $product.product_quantity == $product.product_quantity_refunded || $product.product_quantity_refunded > $product.product_quantity}style="text-decoration:line-through;"{/if}>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} >{$product.product_name|escape:'htmlall':'UTF-8'}</td>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center"><span class="badge badge-{if $product.in_stock <= 0}danger{else}success{/if}">{$product.in_stock|intval}</span></td>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$product.product_quantity|intval}</td>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $product.product_quantity == $product.product_quantity_refunded || !$order_supplier_allow || ($product.product_quantity - $product.product_quantity_refunded < 0)}0{elseif ($product.product_quantity - $product.product_quantity_in_stock) < 0}{$product.product_quantity|intval}{else}{math equation="x - y" x=$product.product_quantity_in_stock y=$product.product_quantity_refunded}{/if}</td>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$product.product_quantity_refunded|intval}</td>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $product.product_quantity == $product.product_quantity_refunded || !$order_supplier_allow || ($product.product_quantity-$product.product_quantity_refunded-$product.product_quantity_in_stock-$product.product_quantity_refunded) < 0}0{else}{math equation="x - y" x=($product.product_quantity-$product.product_quantity_refunded) y=$product.product_quantity_in_stock-$product.product_quantity_refunded}{/if}</td>
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
				
			</div>
		</div>	
		<div id="supplier_accordion" style="margin-top:10px; display:block;" class="ui-accordion ui-widget ui-helper-reset" role="tablist">
		<h4 style="margin-bottom:0;border-bottom:1px solid #000; color:#00aff0;" class="ui-accordion-header ui-helper-reset ui-state-default ui-accordion-header-active ui-state-active ui-corner-top ui-accordion-icons" role="tab" id="ui-accordion-cupplier_accordion-header-0" aria-controls="ui-accordion-supplier_accordion-panel-0" aria-selected="true" tabindex="0">{l s='Supply Orders' mod='wic_erp'} <span class="badge">{($supply_order|@sizeof|escape:'htmlall':'UTF-8')}</span></h4>
		<div style="display: block;" class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active" id="ui-accordion-supplier_accordion-panel-0" aria-labelledby="ui-accordion-supplier_accordion-header-0" role="tabpanel" aria-expanded="true" aria-hidden="false">
				<div class="table-responsive">
					{if $supply_order|@sizeof == 0}
					<div class="list-empty">
						<div class="list-empty-msg">
							<i class="icon-warning-sign list-empty-icon"></i>
							{l s='No supply order' mod='wic_erp'}
						</div>
					</div>
					{else}
						
							<table id="supply_order_detail" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;">
								<thead>
									<tr class="nodrag nodrop">
										<th style="width:25%;">{l s='Product name' mod='wic_erp'}</th>
										<th class="center" style="width:20%;">{l s='Reference' mod='wic_erp'}</th>
										<th class="center" style="width:15%;">{l s='Status' mod='wic_erp'}</th>
										<th class="center" style="width:15%;">{l s='Delivery (expected)' mod='wic_erp'}</th>
										<th class="center" style="width:15%;">{l s='Quantity expected' mod='wic_erp'}</th>
										<th class="center" style="width:15%;">{l s='Quantity delivred' mod='wic_erp'}</th>
									</tr>
								</thead>
								<tbody>
									{foreach $supply_order AS $detail}
									<tr>
										<td>{$detail.erp_order_detail->name|escape:'htmlall':'UTF-8'}</td>
										<td class="center">{$detail.erp_order->reference|escape:'htmlall':'UTF-8'}</td>
										<td class="center"><span class="badge" style="background-color:{$detail.erp_order_state->color|escape:'htmlall':'UTF-8'};">{$detail.erp_order_state->name|escape:'htmlall':'UTF-8'}</span></td>
										<td class="center"><i class="icon-calendar-o"></i> {dateFormat date=$detail.erp_order->date_delivery_expected}</td>
										<td class="center"><span class="badge badge-success">{$detail.erp_order_detail->quantity_ordered|intval}</span></td>
										<td class="center"><span class="{if $detail.erp_order_detail->quantity_ordered > $detail.erp_order_detail->quantity_received}badge badge-danger{else}badge badge-success{/if}">{$detail.erp_order_detail->quantity_received|intval}</span></td>
									</tr>
									{/foreach}
								</tbody>
							</table>
						
					{/if}
				</div>
				</div>
</fieldset>
{else}
{*
1.6 VERSION
*}
<div class="col-lg-12">
	<div class="panel">
		<h3>
			<i class="icon-shopping-cart"></i> {l s='My Easy ERP' mod='wic_erp'}
		</h3>
		<ul class="nav nav-tabs" id="tabErp">
			<li class="active">
				<a href="#products_purchased">
					<i class="icon-shopping-cart"></i>
					{l s='Product purchased' mod='wic_erp'} <span class="badge">{$products|@sizeof|escape:'htmlall':'UTF-8'}</span>
				</a>
			</li>
			<li>
				<a href="#supply_order">
					<i class="icon-truck "></i>
					{l s='Supply Orders' mod='wic_erp'} <span class="badge">{($supply_order|@sizeof|escape:'htmlall':'UTF-8')}</span>
				</a>
			</li>
		</ul>
		<div class="tab-content panel">
			<div class="tab-pane active" id="products_purchased">
				<div class="table-responsive">
					<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;">
						<thead>
							<tr class="nodrag nodrop">
								<th style="width:25%;">{l s='Name' mod='wic_erp'}</th>
								<th class="center" style="width:15%;">{l s='In stock' mod='wic_erp'}</th>
								<th class="center" style="width:15%;">{l s='Quantity ordered' mod='wic_erp'}</th>
								<th class="center" style="width:15%;">{l s='Stock reserved' mod='wic_erp'}</th>
								<th class="center" style="width:15%;">{l s='Quantity refunded' mod='wic_erp'}</th>
								<th class="center" style="width:15%;">{l s='Quantity to be ordered' mod='wic_erp'}</th>
							</tr>
						</thead>
						<tbody>
						{foreach $products AS $product}
								<tr {if $product.product_quantity == $product.product_quantity_refunded || $product.product_quantity_refunded > $product.product_quantity}style="text-decoration:line-through;"{/if}>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} >{$product.product_name|escape:'htmlall':'UTF-8'}</td>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center"><span class="badge badge-{if $product.in_stock <= 0}danger{else}success{/if}">{$product.in_stock|intval}</span></td>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$product.product_quantity|intval}</td>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $product.product_quantity == $product.product_quantity_refunded || !$order_supplier_allow || ($product.product_quantity - $product.product_quantity_refunded < 0)}0{elseif ($product.product_quantity - $product.product_quantity_in_stock) < 0}{$product.product_quantity|intval}{else}{math equation="x - y" x=$product.product_quantity_in_stock y=$product.product_quantity_refunded}{/if}</td>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$product.product_quantity_refunded|intval}</td>
									<td style="line-height: 1!important;{if $product.product_quantity_in_stock < ($product.product_quantity-$product.product_quantity_refunded)}background-color: #fab4bf;"{elseif $product.product_quantity == $product.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $product.product_quantity == $product.product_quantity_refunded || !$order_supplier_allow || ($product.product_quantity-$product.product_quantity_refunded-$product.product_quantity_in_stock-$product.product_quantity_refunded) < 0}0{else}{math equation="x - y" x=($product.product_quantity-$product.product_quantity_refunded) y=$product.product_quantity_in_stock-$product.product_quantity_refunded}{/if}</td>
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
				</div>
			</div>
			
			<div class="tab-pane" id="supply_order">
				<div class="table-responsive">
					{if $supply_order|@sizeof == 0}
					<div class="list-empty">
						<div class="list-empty-msg">
							<i class="icon-warning-sign list-empty-icon"></i>
							{l s='No supply order' mod='wic_erp'}
						</div>
					</div>
					{else}
						<div class="table-responsive">
							<table id="supply_order_detail" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;">
								<thead>
									<tr class="nodrag nodrop">
										<th style="width:25%;">{l s='Product name' mod='wic_erp'}</th>
										<th class="center" style="width:20%;">{l s='Reference' mod='wic_erp'}</th>
										<th class="center" style="width:15%;">{l s='Status' mod='wic_erp'}</th>
										<th class="center" style="width:15%;">{l s='Delivery (expected)' mod='wic_erp'}</th>
										<th class="center" style="width:15%;">{l s='Quantity expected' mod='wic_erp'}</th>
										<th class="center" style="width:15%;">{l s='Quantity delivred' mod='wic_erp'}</th>
									</tr>
								</thead>
								<tbody>
									{foreach $supply_order AS $detail}
									<tr>
										<td>{$detail.erp_order_detail->name|escape:'htmlall':'UTF-8'}</td>
										<td class="center">{$detail.erp_order->reference|escape:'htmlall':'UTF-8'}</td>
										<td class="center"><span class="badge" style="background-color:{$detail.erp_order_state->color|escape:'htmlall':'UTF-8'};">{$detail.erp_order_state->name|escape:'htmlall':'UTF-8'}</span></td>
										<td class="center"><i class="icon-calendar-o"></i> {dateFormat date=$detail.erp_order->date_delivery_expected}</td>
										<td class="center"><span class="badge badge-success">{$detail.erp_order_detail->quantity_ordered|intval}</span></td>
										<td class="center"><span class="{if $detail.erp_order_detail->quantity_ordered > $detail.erp_order_detail->quantity_received}badge badge-danger{else}badge badge-success{/if}">{$detail.erp_order_detail->quantity_received|intval}</span></td>
									</tr>
									{/foreach}
								</tbody>
							</table>
						</div>
					{/if}
				</div>
			</div>
			
		</div>
	</div>
</div>
<script>
	$('#tabErp a').click(function (e) {
		e.preventDefault()
		$(this).tab('show');
	});
</script>
{/if}
