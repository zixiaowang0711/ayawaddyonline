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
<style>
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
	
	.btn {
		display: inline-block;
		margin-bottom: 0;
		font-weight: normal;
		text-align: center;
		vertical-align: middle;
		cursor: pointer;
		background-image: none;
		border: 1px solid transparent;
		white-space: nowrap;
		padding: 4px 8px;
		font-size: 12px;
		line-height: 1.42857;
		border-radius: 3px;
	}
	
	.btn-default {
		color: #555;
		background-color: #fff;
		border-color: #ccc;
	}
	
	.pull-right {
		float: right !important;
	}
	
	.btn.btn-default {
		box-shadow: #e6e6e6 0 -2px 0 inset;
		margin-top:10px;
	}
	
	.btn.pull-right:not(:first-child) {
		margin-right: 5px;
		
	}
	
	.btn.btn-default:hover {
		color: #fff;
		background-color: #00aff0;
		border-color: #008abd;
		-webkit-box-shadow: none;
		box-shadow: none;
	}
	
</style>
<div id="product-tab-content-ModuleWic_erp" class="product-tab-content">
	<div id="product-modulewic_erp" class="panel product-tab">
		<h3>{l s='My Easy Erp product' mod='wic_erp'}</h3>
		{if $combinations|@sizeof > 0}
			<div id="product_accordion-settings" style="margin-top:10px; display:block;" class="ui-accordion ui-widget ui-helper-reset" role="tablist">
				<h4 style="margin-bottom:0;border-bottom:1px solid #000; color:#00aff0;" class="ui-accordion-header ui-helper-reset ui-state-default ui-accordion-header-active ui-state-active ui-corner-top ui-accordion-icons" role="tab" id="ui-accordion-product_accordion-header-settings" aria-controls="ui-accordion-product_accordion-panel-settings" aria-selected="true" tabindex="0">{l s='Settings Erp Product' mod='wic_erp'}</h4>
		   		<div style="display: block;" class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active" id="ui-accordion-product_accordion-panel-settings" aria-labelledby="ui-accordion-product_accordion-header-settings" role="tabpanel" aria-expanded="true" aria-hidden="false">
      			<div class="panel-body">
                            <div class="form-group">
                                   <label class="control-label col-lg-4">{l s='Bundling (Specific option to import)' mod='wic_erp'}</label>
                                   <div class="col-lg-4"><input type="text" name="bundling" value="{$bundling|intval}" /></div>
                                   <div class="col-lg-6 col-lg-offset-4">
                                       <div class="help-block">{l s='Leave a blank or 0 if you don\'t use this option. This option multiply quantity purchased of your product when you import file.' mod='wic_erp'}</div>
                                   </div>
                                </div>
                                   
					<table id="product_details_settings" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;">
							<thead>
								<tr class="nodrag nodrop">
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
								{foreach $combinations AS $combination}
								<!-- Foreach product Attribute //-->
									{if !isset($combination.id_product_attribute)}
										{assign var='id_product_attribute' value=0}
									{else}
										{assign var='id_product_attribute' value=$combination.id_product_attribute}
									{/if}
									<tr>
										<td>{$combination.attribute_designation|escape:'htmlall':'UTF-8'}</td>
										<td class="center"><input type="text" name="min_quantity[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}]" value="{$combination.min_quantity|intval}" size='4' /></td>
										<td class="center"><input type="text" name="safety_stock[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}]" value="{$combination.safety_stock|intval}" /></td>
										<td class="center"><span class="badge badge-{if $combination.in_stock <= 0}danger{else}success{/if}">{$combination.in_stock|intval}</span></td>
										<td class="center"><input type="text" name="unit_order[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}]" value="{$combination.unit_order|intval}" /></td>
                                                                                <td class="center"><input type="text" name="bundling[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}]" value="{$combination.bundling|intval}" /></td>
										<td class="center"><input type="checkbox" name="manual_configuration[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}]" value="1" {if $combination.manual_configuration}checked="checked"{/if} /></td>
									</tr>
    							{/foreach}
    						</tbody>
    					</table>
    				</div>
    			</div>
 			</div>
			<div class="panel-group" id="product_accordion">
				{foreach $combinations AS $combination}
					<!-- Foreach product Attribute //-->
					<div id="product_accordion-{if isset($combination.id_product_attribute)}{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}{else}{$combination.id_product|escape:'htmlall':'UTF-8'}{/if}" style="margin-top:10px; display:block;" class="ui-accordion ui-widget ui-helper-reset" role="tablist">
				    	<h4 style="margin-bottom:0;border-bottom:1px solid #000; color:#00aff0;" class="ui-accordion-header ui-helper-reset ui-state-default ui-accordion-header-active ui-state-active ui-corner-top ui-accordion-icons" role="tab" id="ui-accordion-product_accordion-header-{if isset($combination.id_product_attribute)}{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}{else}{$combination.id_product|escape:'htmlall':'UTF-8'}{/if}" aria-controls="ui-accordion-product_accordion-panel-{if isset($combination.id_product_attribute)}{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}{else}{$combination.id_product|escape:'htmlall':'UTF-8'}{/if}" aria-selected="true" tabindex="0">{html_entity_decode($combination.attribute_designation|escape:'htmlall':'UTF-8')}</h4>
		   				<div style="display: block;" class="ui-accordion-content ui-helper-reset ui-widget-content ui-corner-bottom ui-accordion-content-active" id="ui-accordion-product_accordion-panel-{if isset($combination.id_product_attribute)}{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}{else}{$combination.id_product|escape:'htmlall':'UTF-8'}{/if}" aria-labelledby="ui-accordion-product_accordion-header-{if isset($combination.id_product_attribute)}{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}{else}{$combination.id_product|escape:'htmlall':'UTF-8'}{/if}" role="tabpanel" aria-expanded="true" aria-hidden="false">
      						<div class="panel-body">
      							<!-- Customer order //-->
        						<div class="tab-content panel">
        							<h3>{l s='Customer Order pending' mod='wic_erp'}</h3>
        							{if isset($combination.id_product_attribute) && isset($customer_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute]) && $customer_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute]|@sizeof > 0}
        								{foreach $customer_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute] AS $order}
        									<div class="tab-pane active">
												<div class="table-responsive">
													<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;">
														<thead>
															<tr class="nodrag nodrop">
																<th style="width:10%;">{l s='ID' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Reference' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Customer' mod='wic_erp'}</th>
																<th class="center" style="width:15%;">{l s='Total' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Status' mod='wic_erp'}</th>
																<th class="center" style="width:15%;">{l s='Date' mod='wic_erp'}</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>{$order.order->id|intval}</td>
																<td>{$order.order->reference|escape:'htmlall':'UTF-8'}</td>
																<td class="center">{$order.order->customer->firstname|escape:'htmlall':'UTF-8'} {$order.order->customer->lastname|escape:'htmlall':'UTF-8'}</td>
																<td class="center"><b>{displayPrice price=$order.order->total_paid_tax_incl currency=$order.order->id_currency}</b></td>
																<td class="center"><span class="badge" style="background-color:{$order.order->state->color|escape:'htmlall':'UTF-8'};">{$order.order->state->name|escape:'htmlall':'UTF-8'}</span></td>
																<td class="center"><i class="icon-calendar-o"></i> {dateFormat date=$order.order->date_add}</td>
															</tr>
															<tr>
																<td colspan="6" style="border:none!important; border-bottom:5px solid #a0d0eb!important; padding-left:6%;">
																	<div class="tab-pane active" id="products_purchased">
																		<div class="table-responsive">
																			<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;margin:0;">
																				<thead>
																					<tr class="nodrag nodrop" style="background-color: #AAADAF;color: white;">
																						<th style="width:25%;padding:0;">{l s='Name' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Quantity ordered' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Stock reserved' mod='wic_erp'}</th>
																						<th class="center" style="width:15%;padding:0;">{l s='Quantity refunded' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Quantity to be ordered' mod='wic_erp'}</th>
																					</tr>
																				</thead>
																				<tbody>
																					{foreach $order.order_detail AS $detail}
																						<tr {if $detail.product_quantity == $detail.product_quantity_refunded || $detail.product_quantity_refunded > $detail.product_quantity}style="text-decoration:line-through;"{/if}>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} >{$detail.product_name|escape:'htmlall':'UTF-8'}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$detail.product_quantity|intval}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $detail.product_quantity == $detail.product_quantity_refunded}0{elseif ($detail.product_quantity - $detail.product_quantity_refunded < 0)}{$detail.product_quantity|intval}{else}{math equation="x - y" x=$detail.product_quantity_in_stock y=$detail.product_quantity_refunded}{/if}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$detail.product_quantity_refunded|intval}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $detail.product_quantity == $detail.product_quantity_refunded || ($detail.product_quantity-$detail.product_quantity_refunded-$detail.product_quantity_in_stock-$detail.product_quantity_refunded) < 0}0{else}{math equation="x - y" x=($detail.product_quantity-$detail.product_quantity_refunded) y=$detail.product_quantity_in_stock-$detail.product_quantity_refunded}{/if}</td>
																						</tr>
																					{/foreach}
																				</tbody>
																			</table>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
        								{/foreach}
        							{elseif isset($customer_order[$combination.id_product]) && $customer_order[$combination.id_product]|@sizeof > 0}
        								{foreach $customer_order[$combination.id_product] AS $order}
        									<div class="tab-pane active">
												<div class="table-responsive">
													<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;">
														<thead>
															<tr class="nodrag nodrop">
																<th style="width:10%;">{l s='ID' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Reference' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Customer' mod='wic_erp'}</th>
																<th class="center" style="width:15%;">{l s='Total' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Status' mod='wic_erp'}</th>
																<th class="center" style="width:15%;">{l s='Date' mod='wic_erp'}</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>{$order.order->id|intval}</td>
																<td>{$order.order->reference|escape:'htmlall':'UTF-8'}</td>
																<td class="center">{$order.order->customer->firstname|escape:'htmlall':'UTF-8'} {$order.order->customer->lastname|escape:'htmlall':'UTF-8'}</td>
																<td class="center"><b>{displayPrice price=$order.order->total_paid_tax_incl currency=$order.order->id_currency}</b></td>
																<td class="center"><span class="badge" style="background-color:{$order.order->state->color|escape:'htmlall':'UTF-8'};">{$order.order->state->name|escape:'htmlall':'UTF-8'}</span></td>
																<td class="center"><i class="icon-calendar-o"></i> {dateFormat date=$order.order->date_add}</td>
															</tr>
															<tr>
																<td colspan="6" style="border:none!important; border-bottom:5px solid #a0d0eb!important; padding-left:6%;">
																	<div class="tab-pane active" id="products_purchased">
																		<div class="table-responsive">
																			<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;margin:0;">
																				<thead>
																					<tr class="nodrag nodrop" style="background-color: #AAADAF;color: white;">
																						<th style="width:25%;padding:0;">{l s='Name' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Quantity ordered' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Stock reserved' mod='wic_erp'}</th>
																						<th class="center" style="width:15%;padding:0;">{l s='Quantity refunded' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Quantity to be ordered' mod='wic_erp'}</th>
																					</tr>
																				</thead>
																				<tbody>
																					{foreach $order.order_detail AS $detail}
																						<tr {if $detail.product_quantity == $detail.product_quantity_refunded}style="text-decoration:line-through;"{/if}>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} >{$detail.product_name|escape:'htmlall':'UTF-8'}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$detail.product_quantity|escape:'htmlall':'UTF-8'}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $detail.product_quantity == $detail.product_quantity_refunded}0{else}{math equation="x - y" x=$detail.product_quantity_in_stock y=$detail.product_quantity_refunded}{/if}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$detail.product_quantity_refunded|intval}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $detail.product_quantity == $detail.product_quantity_refunded}0{else}{math equation="x - y" x=($detail.product_quantity-$detail.product_quantity_refunded) y=$detail.product_quantity_in_stock-$detail.product_quantity_refunded}{/if}</td>
																						</tr>
																					{/foreach}
																				</tbody>
																			</table>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
        								{/foreach}
									{else}
										<div class="list-empty">
											<div class="list-empty-msg">
												<i class="icon-warning-sign list-empty-icon"></i>
												{l s='No customer order pending' mod='wic_erp'}
											</div>
										</div>
									{/if}
								</div>
								<br/><br/>
								<!--End customer order //-->
								<!-- Supply order //-->
								<div class="tab-content panel">
        							<h3>{l s='Supply Order pending' mod='wic_erp'}</h3>
        							{if isset($combination.id_product_attribute) && isset($supply_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute]) && $supply_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute]|@sizeof > 0}
        								<div class="tab-pane active">
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
														{foreach $supply_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute] AS $detail}
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
										</div>
									{elseif isset($supply_order[$combination.id_product]) && $supply_order[$combination.id_product]|@sizeof > 0}
        								<div class="tab-pane active">
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
														{foreach $supply_order[$combination.id_product] AS $detail}
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
										</div>
									{else}
										<div class="list-empty">
											<div class="list-empty-msg">
												<i class="icon-warning-sign list-empty-icon"></i>
												{l s='No supply order pending' mod='wic_erp'}
											</div>
										</div>
									{/if}
								</div>
								<!-- End Supply Order //-->
        					</div>
    					</div>
  					</div>
  				{/foreach}
  				<!-- End foreach attribute combination //-->
  			</div>
  		{else}
  		
  		{/if}
	</div>
</div>
{elseif version_compare($smarty.const._PS_VERSION_,'1.7','<')}
{*
1.6 VERSION
*}
    {if $warehouses}
        {assign var="highest_width" value="width:10%"}
        {assign var="min_width" value="width:5%"}
    {else}
        {assign var="highest_width" value="width:12%"}
        {assign var="min_width" value="width:10%"}
    {/if}
<div id="product-tab-content-ModuleWic_erp" class="product-tab-content">
	<div id="product-modulewic_erp" class="panel product-tab">
		<h3>{l s='My Easy Erp product' mod='wic_erp'}</h3>
		{if $combinations|@sizeof > 0}
			<div class="panel-group" id="product_erp_settings">
				<div class="panel">
  					<div class="panel-heading">
        				<a data-toggle="collapse" data-parent="#product_erp_settings" href="#collapse_product_erp_settings">{l s='Settings Erp Product' mod='wic_erp'}</a>
      				</div>
      				<div id="collapse_product_erp_settings" class="collapse in">
      					<table id="product_details_settings" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;">
							<thead>
								<tr class="nodrag nodrop">
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
                                                            {assign var="bg" value="#eee"}
                                                            
								{foreach $combinations AS $combination}
                                                                    {if $bg eq "#eee"}
                                                                        {assign var="bg" value="#fff"}
                                                                    {else}
                                                                        {assign var="bg" value="#eee"}
                                                                    {/if}
								<!-- Foreach product Attribute //-->
									{if !isset($combination.id_product_attribute)}
										{assign var='id_product_attribute' value=0}
									{else}
										{assign var='id_product_attribute' value=$combination.id_product_attribute}
									{/if}
									<tr>
                                                                            <td style="background:{$bg|escape:'htmlall':'UTF-8'};"><b>{$combination.attribute_designation|escape:'htmlall':'UTF-8'}</b></td>
										<td class="center" style="background:{$bg|escape:'htmlall':'UTF-8'};"><input type="text" name="min_quantity[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}]" value="{$combination.min_quantity|intval}" size='4' /></td>
										<td class="center" style="background:{$bg|escape:'htmlall':'UTF-8'};"><input type="text" name="safety_stock[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}]" value="{$combination.safety_stock|intval}" /></td>
										<td class="center" style="background:{$bg|escape:'htmlall':'UTF-8'};"><span class="badge badge-{if $combination.in_stock <= 0}danger{else}success{/if}">{$combination.in_stock|intval}</span></td>
										<td class="center" style="background:{$bg|escape:'htmlall':'UTF-8'};"><input type="text" name="unit_order[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}]" value="{$combination.unit_order|intval}" /></td>
										<td class="center" style="background:{$bg|escape:'htmlall':'UTF-8'};"><input type="text" name="bundling[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}]" value="{$combination.bundling|intval}" /></td>
                                                                                <td class="center" style="background:{$bg|escape:'htmlall':'UTF-8'};"><input type="checkbox" name="manual_configuration[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}]" value="1" {if $combination.manual_configuration}checked="checked"{/if} /></td>
									</tr>
                                                                       {if $warehouses}
                                                                           <tr>
                                                                               <td style="background:{$bg|escape:'htmlall':'UTF-8'}; border-bottom:3px solid #a0d0eb;"><i>{l s='Warehouse' mod='wic_erp'}</i></td>
                                                                                <td colspan="3" style="background:{$bg|escape:'htmlall':'UTF-8'}; border-bottom:3px solid #a0d0eb;">
                                                                                    <table>
                                                                                        <thead>
                                                                                            <tr class="nodrag nodrop">
                                                                                                <th colspan="2" style="color:red;"><b>{l s='Min Stock by Warehouse' mod='wic_erp'}</b></th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            {foreach $warehouses AS $warehouse}
                                                                                                <tr>
                                                                                                    <td style="background:{$bg|escape:'htmlall':'UTF-8'};"><label>{$warehouse.name|escape:'htmlall':'UTF-8'}</label></td>
                                                                                                    <td style="background:{$bg|escape:'htmlall':'UTF-8'};"><input type="text" name="min_quantity_by_warehouse[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}][{$warehouse.id_warehouse|escape:'htmlall':'UTF-8'}]" value="{$combination.min_quantity_by_warehouse[$warehouse.id_warehouse]|intval}" /></td>
                                                                                                </tr>
                                                                                            {/foreach}
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                                <td colspan="3" style="background:{$bg|escape:'htmlall':'UTF-8'}; border-bottom:3px solid #a0d0eb;">
                                                                                    <table>
                                                                                        <thead>
                                                                                            <tr class="nodrag nodrop">
                                                                                                <th colspan="2" style="color:red;"><b>{l s='Safety Stock by Warehouse' mod='wic_erp'}</b></th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            {foreach $warehouses AS $warehouse}
                                                                                                <tr>
                                                                                                    <td style="background:{$bg|escape:'htmlall':'UTF-8'};"><label>{$warehouse.name|escape:'htmlall':'UTF-8'}</label></td>
                                                                                                    <td style="background:{$bg|escape:'htmlall':'UTF-8'};"><input type="text" name="safety_stock_by_warehouse[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}][{$warehouse.id_warehouse|escape:'htmlall':'UTF-8'}]" value="{$combination.safety_stock_by_warehouse[$warehouse.id_warehouse]|intval}" /></td>
                                                                                                </tr>
                                                                                            {/foreach}
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                           </tr>
                                                                        {/if} 
                                                                    
    							{/foreach}
    						</tbody>
    					</table>
    					<div class="panel-footer">
    						<button type="submit" name="submitAddproduct" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save' mod='wic_erp'}</button>
    						<button type="submit" name="submitAddproductAndStay" class="btn btn-default pull-right"><i class="process-icon-save"></i> {l s='Save and Stay' mod='wic_erp'}</button>
    					</div>
    				</div>
    			</div>
    		</div>	
    				
			<div class="panel-group" id="product_accordion">
				{foreach $combinations AS $combination}
					<!-- Foreach product Attribute //-->
  					<div class="panel">
  						<div class="panel-heading">
        					<a data-toggle="collapse" data-parent="#product_accordion" href="#collapse{if isset($combination.id_product_attribute)}{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}{else}{$combination.id_product|escape:'htmlall':'UTF-8'}{/if}">{$combination.attribute_designation|escape:'htmlall':'UTF-8'}</a>
      					</div>
    					<div id="collapse{if isset($combination.id_product_attribute)}{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}{else}{$combination.id_product|escape:'htmlall':'UTF-8'}{/if}" class="collapse in">
      						<div class="panel-body">
      							<!-- Customer order //-->
        						<div class="tab-content panel">
        							<h3>{l s='Customer Order pending' mod='wic_erp'}</h3>
        							{if isset($combination.id_product_attribute) && isset($customer_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute]) && $customer_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute]|@sizeof > 0}
        								{foreach $customer_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute] AS $order}
        									<div class="tab-pane active">
												<div class="table-responsive">
													<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;">
														<thead>
															<tr class="nodrag nodrop">
																<th style="width:10%;">{l s='ID' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Reference' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Customer' mod='wic_erp'}</th>
																<th class="center" style="width:15%;">{l s='Total' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Status' mod='wic_erp'}</th>
																<th class="center" style="width:15%;">{l s='Date' mod='wic_erp'}</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>{$order.order->id|intval}</td>
																<td>{$order.order->reference|escape:'htmlall':'UTF-8'}</td>
																<td class="center">{$order.order->customer->firstname|escape:'htmlall':'UTF-8'} {$order.order->customer->lastname|escape:'htmlall':'UTF-8'}</td>
																<td class="center"><b>{displayPrice price=$order.order->total_paid_tax_incl currency=$order.order->id_currency}</b></td>
																<td class="center"><span class="badge" style="background-color:{$order.order->state->color|escape:'htmlall':'UTF-8'};">{$order.order->state->name|escape:'htmlall':'UTF-8'}</span></td>
																<td class="center"><i class="icon-calendar-o"></i> {dateFormat date=$order.order->date_add}</td>
															</tr>
															<tr>
																<td colspan="6" style="border:none!important; border-bottom:5px solid #a0d0eb!important; padding-left:6%;">
																	<div class="tab-pane active" id="products_purchased">
																		<div class="table-responsive">
																			<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;margin:0;">
																				<thead>
																					<tr class="nodrag nodrop" style="background-color: #AAADAF;color: white;">
																						<th style="width:25%;padding:0;">{l s='Name' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Quantity ordered' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Stock reserved' mod='wic_erp'}</th>
																						<th class="center" style="width:15%;padding:0;">{l s='Quantity refunded' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Quantity to be ordered' mod='wic_erp'}</th>
																					</tr>
																				</thead>
																				<tbody>
																					{foreach $order.order_detail AS $detail}
																						<tr {if $detail.product_quantity == $detail.product_quantity_refunded || $detail.product_quantity_refunded > $detail.product_quantity}style="text-decoration:line-through;"{/if}>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} >{$detail.product_name|escape:'htmlall':'UTF-8'}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$detail.product_quantity|intval}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $detail.product_quantity == $detail.product_quantity_refunded || ($detail.product_quantity - $detail.product_quantity_refunded < 0)}0{elseif ($detail.product_quantity - $detail.product_quantity_in_stock) < 0}{$detail.product_quantity|intval}{else}{math equation="x - y" x=$detail.product_quantity_in_stock y=$detail.product_quantity_refunded}{/if}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$detail.product_quantity_refunded|intval}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $detail.product_quantity == $detail.product_quantity_refunded || ($detail.product_quantity-$detail.product_quantity_refunded-$detail.product_quantity_in_stock-$detail.product_quantity_refunded) < 0}0{else}{math equation="x - y" x=($detail.product_quantity-$detail.product_quantity_refunded) y=$detail.product_quantity_in_stock-$detail.product_quantity_refunded}{/if}</td>
																						</tr>
																					{/foreach}
																				</tbody>
																			</table>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
        								{/foreach}
        							{elseif isset($customer_order[$combination.id_product]) && $customer_order[$combination.id_product]|@sizeof > 0}
        								{foreach $customer_order[$combination.id_product] AS $order}
        									<div class="tab-pane active">
												<div class="table-responsive">
													<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;">
														<thead>
															<tr class="nodrag nodrop">
																<th style="width:10%;">{l s='ID' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Reference' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Customer' mod='wic_erp'}</th>
																<th class="center" style="width:15%;">{l s='Total' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Status' mod='wic_erp'}</th>
																<th class="center" style="width:15%;">{l s='Date' mod='wic_erp'}</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>{$order.order->id|intval}</td>
																<td>{$order.order->reference|escape:'htmlall':'UTF-8'}</td>
																<td class="center">{$order.order->customer->firstname|escape:'htmlall':'UTF-8'} {$order.order->customer->lastname|escape:'htmlall':'UTF-8'}</td>
																<td class="center"><b>{displayPrice price=$order.order->total_paid_tax_incl currency=$order.order->id_currency}</b></td>
																<td class="center"><span class="badge" style="background-color:{$order.order->state->color|escape:'htmlall':'UTF-8'};">{$order.order->state->name|escape:'htmlall':'UTF-8'}</span></td>
																<td class="center"><i class="icon-calendar-o"></i> {dateFormat date=$order.order->date_add}</td>
															</tr>
															<tr>
																<td colspan="6" style="border:none!important; border-bottom:5px solid #a0d0eb!important; padding-left:6%;">
																	<div class="tab-pane active" id="products_purchased">
																		<div class="table-responsive">
																			<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;margin:0;">
																				<thead>
																					<tr class="nodrag nodrop" style="background-color: #AAADAF;color: white;">
																						<th style="width:25%;padding:0;">{l s='Name' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Quantity ordered' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Stock reserved' mod='wic_erp'}</th>
																						<th class="center" style="width:15%;padding:0;">{l s='Quantity refunded' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Quantity to be ordered' mod='wic_erp'}</th>
																					</tr>
																				</thead>
																				<tbody>
																					{foreach $order.order_detail AS $detail}
																						<tr {if $detail.product_quantity == $detail.product_quantity_refunded}style="text-decoration:line-through;"{/if}>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} >{$detail.product_name|escape:'htmlall':'UTF-8'}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$detail.product_quantity|intval}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $detail.product_quantity == $detail.product_quantity_refunded}0{else}{math equation="x - y" x=$detail.product_quantity_in_stock y=$detail.product_quantity_refunded}{/if}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$detail.product_quantity_refunded|intval}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $detail.product_quantity == $detail.product_quantity_refunded}0{else}{math equation="x - y" x=($detail.product_quantity-$detail.product_quantity_refunded) y=$detail.product_quantity_in_stock-$detail.product_quantity_refunded}{/if}</td>
																						</tr>
																					{/foreach}
																				</tbody>
																			</table>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
        								{/foreach}
									{else}
										<div class="list-empty">
											<div class="list-empty-msg">
												<i class="icon-warning-sign list-empty-icon"></i>
												{l s='No customer order pending' mod='wic_erp'}
											</div>
										</div>
									{/if}
								</div>
								<!--End customer order //-->
								<!-- Supply order //-->
								<div class="tab-content panel">
        							<h3>{l s='Supply Order pending' mod='wic_erp'}</h3>
        							{if isset($combination.id_product_attribute) && isset($supply_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute]) && $supply_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute]|@sizeof > 0}
        								<div class="tab-pane active">
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
														{foreach $supply_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute] AS $detail}
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
										</div>
									{elseif isset($supply_order[$combination.id_product]) && $supply_order[$combination.id_product]|@sizeof > 0}
        								<div class="tab-pane active">
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
														{foreach $supply_order[$combination.id_product] AS $detail}
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
										</div>
									{else}
										<div class="list-empty">
											<div class="list-empty-msg">
												<i class="icon-warning-sign list-empty-icon"></i>
												{l s='No supply order pending' mod='wic_erp'}
											</div>
										</div>
									{/if}
								</div>
								<!-- End Supply Order //-->
        					</div>
    					</div>
  					</div>
  				{/foreach}
  				<!-- End foreach attribute combination //-->
  			</div>
  		{else}
  		
  		{/if}
	</div>
</div>
{else}
{*
1.7 VERSION
*}
    {if $warehouses}
        {assign var="highest_width" value="width:10%"}
        {assign var="min_width" value="width:5%"}
    {else}
        {assign var="highest_width" value="width:12%"}
        {assign var="min_width" value="width:10%"}
    {/if}
<div id="product-tab-content-ModuleWic_erp" class="product-tab-content">
	<div id="product-modulewic_erp" class="panel product-tab">
		<h3>{l s='My Easy Erp product' mod='wic_erp'}</h3>
		{if $combinations|@sizeof > 0}
			<div class="panel-group" id="product_erp_settings">
				<div class="panel">
  					<div class="panel-heading">
        				<a data-toggle="collapse" data-parent="#product_erp_settings" href="#collapse_product_erp_settings">{l s='Settings Erp Product' mod='wic_erp'}</a>
      				</div>
      				<div id="collapse_product_erp_settings" class="collapse in panel-body">
      					<table id="product_details_settings" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;">
							<thead>
								<tr class="nodrag nodrop">
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
                                                            {assign var="bg" value="#eee"}
                                                            
								{foreach $combinations AS $combination}
                                                                    {if $bg eq "#eee"}
                                                                        {assign var="bg" value="#fff"}
                                                                    {else}
                                                                        {assign var="bg" value="#eee"}
                                                                    {/if}
								<!-- Foreach product Attribute //-->
									{if !isset($combination.id_product_attribute)}
										{assign var='id_product_attribute' value=0}
									{else}
										{assign var='id_product_attribute' value=$combination.id_product_attribute}
									{/if}
									<tr>
                                                                            <td style="background:{$bg|escape:'htmlall':'UTF-8'};"><b>{$combination.attribute_designation|escape:'htmlall':'UTF-8'}</b></td>
										<td class="center" style="background:{$bg|escape:'htmlall':'UTF-8'};"><input type="text" name="min_quantity[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}]" value="{$combination.min_quantity|intval}" size='4' /></td>
										<td class="center" style="background:{$bg|escape:'htmlall':'UTF-8'};"><input type="text" name="safety_stock[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}]" value="{$combination.safety_stock|intval}" /></td>
										<td class="center" style="background:{$bg|escape:'htmlall':'UTF-8'};"><span class="badge badge-{if $combination.in_stock <= 0}danger{else}success{/if}">{$combination.in_stock|intval}</span></td>
										<td class="center" style="background:{$bg|escape:'htmlall':'UTF-8'};"><input type="text" name="unit_order[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}]" value="{$combination.unit_order|intval}" /></td>
										<td class="center" style="background:{$bg|escape:'htmlall':'UTF-8'};"><input type="text" name="bundling[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}]" value="{$combination.bundling|intval}" /></td>
                                                                                <td class="center" style="background:{$bg|escape:'htmlall':'UTF-8'};"><input type="checkbox" name="manual_configuration[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}]" value="1" {if $combination.manual_configuration}checked="checked"{/if} /></td>
									</tr>
                                                                       {if $warehouses}
                                                                           <tr>
                                                                               <td style="background:{$bg|escape:'htmlall':'UTF-8'}; border-bottom:3px solid #a0d0eb;"><i>{l s='Warehouse' mod='wic_erp'}</i></td>
                                                                                <td colspan="3" style="background:{$bg|escape:'htmlall':'UTF-8'}; border-bottom:3px solid #a0d0eb;">
                                                                                    <table>
                                                                                        <thead>
                                                                                            <tr class="nodrag nodrop">
                                                                                                <th colspan="2" style="color:red;"><b>{l s='Min Stock by Warehouse' mod='wic_erp'}</b></th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            {foreach $warehouses AS $warehouse}
                                                                                                <tr>
                                                                                                    <td style="background:{$bg|escape:'htmlall':'UTF-8'};"><label>{$warehouse.name|escape:'htmlall':'UTF-8'}</label></td>
                                                                                                    <td style="background:{$bg|escape:'htmlall':'UTF-8'};"><input type="text" name="min_quantity_by_warehouse[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}][{$warehouse.id_warehouse|escape:'htmlall':'UTF-8'}]" value="{$combination.min_quantity_by_warehouse[$warehouse.id_warehouse]|intval}" /></td>
                                                                                                </tr>
                                                                                            {/foreach}
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                                <td colspan="3" style="background:{$bg|escape:'htmlall':'UTF-8'}; border-bottom:3px solid #a0d0eb;">
                                                                                    <table>
                                                                                        <thead>
                                                                                            <tr class="nodrag nodrop">
                                                                                                <th colspan="2" style="color:red;"><b>{l s='Safety Stock by Warehouse' mod='wic_erp'}</b></th>
                                                                                            </tr>
                                                                                        </thead>
                                                                                        <tbody>
                                                                                            {foreach $warehouses AS $warehouse}
                                                                                                <tr>
                                                                                                    <td style="background:{$bg|escape:'htmlall':'UTF-8'};"><label>{$warehouse.name|escape:'htmlall':'UTF-8'}</label></td>
                                                                                                    <td style="background:{$bg|escape:'htmlall':'UTF-8'};"><input type="text" name="safety_stock_by_warehouse[{$combination.id_product|escape:'htmlall':'UTF-8'}_{$id_product_attribute|escape:'htmlall':'UTF-8'}][{$warehouse.id_warehouse|escape:'htmlall':'UTF-8'}]" value="{$combination.safety_stock_by_warehouse[$warehouse.id_warehouse]|intval}" /></td>
                                                                                                </tr>
                                                                                            {/foreach}
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                           </tr>
                                                                        {/if} 
                                                                    
    							{/foreach}
    						</tbody>
    					</table>
    				</div>
                    <div class="panel-footer" style="text-align:center;">
    						<button type="submit" name="submitAddproduct" class="btn btn-default"><i class="process-icon-save"></i> {l s='Save' mod='wic_erp'}</button>
    						<button type="submit" name="submitAddproductAndStay" class="btn btn-default"><i class="process-icon-save"></i> {l s='Save and Stay' mod='wic_erp'}</button>
    					</div>
    			</div>
    		</div>	
    				
			<div class="panel-group" id="product_accordion">
				{foreach $combinations AS $combination}
					<!-- Foreach product Attribute //-->
  					<div class="panel">
  						<div class="panel-heading">
        					<a data-toggle="collapse" data-parent="#product_accordion" href="#collapse{if isset($combination.id_product_attribute)}{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}{else}{$combination.id_product|escape:'htmlall':'UTF-8'}{/if}">{$combination.attribute_designation|escape:'htmlall':'UTF-8'}</a>
      					</div>
    					<div id="collapse{if isset($combination.id_product_attribute)}{$combination.id_product_attribute|escape:'htmlall':'UTF-8'}{else}{$combination.id_product|escape:'htmlall':'UTF-8'}{/if}" class="collapse in">
      						<div class="panel-body">
      							<!-- Customer order //-->
                                <div class="panel">
        						<div class="panel-heading">
        							<h3>{l s='Customer Order pending' mod='wic_erp'}</h3></div>
        							{if isset($combination.id_product_attribute) && isset($customer_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute]) && $customer_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute]|@sizeof > 0}
        								{foreach $customer_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute] AS $order}
        									<div class="panel-body">
												<div class="table-responsive">
													<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;">
														<thead>
															<tr class="nodrag nodrop">
																<th style="width:10%;">{l s='ID' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Reference' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Customer' mod='wic_erp'}</th>
																<th class="center" style="width:15%;">{l s='Total' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Status' mod='wic_erp'}</th>
																<th class="center" style="width:15%;">{l s='Date' mod='wic_erp'}</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>{$order.order->id|intval}</td>
																<td>{$order.order->reference|escape:'htmlall':'UTF-8'}</td>
																<td class="center">{$order.order->customer->firstname|escape:'htmlall':'UTF-8'} {$order.order->customer->lastname|escape:'htmlall':'UTF-8'}</td>
																<td class="center"><b>{displayPrice price=$order.order->total_paid_tax_incl currency=$order.order->id_currency}</b></td>
																<td class="center"><span class="badge" style="background-color:{$order.order->state->color|escape:'htmlall':'UTF-8'};">{$order.order->state->name|escape:'htmlall':'UTF-8'}</span></td>
																<td class="center"><i class="icon-calendar-o"></i> {dateFormat date=$order.order->date_add}</td>
															</tr>
															<tr>
																<td colspan="6" style="border:none!important; border-bottom:5px solid #a0d0eb!important; padding-left:6%;">
																	<div class="tab-pane active" id="products_purchased">
																		<div class="table-responsive">
																			<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;margin:0;">
																				<thead>
																					<tr class="nodrag nodrop" style="background-color: #AAADAF;color: white;">
																						<th style="width:25%;padding:0;">{l s='Name' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Quantity ordered' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Stock reserved' mod='wic_erp'}</th>
																						<th class="center" style="width:15%;padding:0;">{l s='Quantity refunded' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Quantity to be ordered' mod='wic_erp'}</th>
																					</tr>
																				</thead>
																				<tbody>
																					{foreach $order.order_detail AS $detail}
																						<tr {if $detail.product_quantity == $detail.product_quantity_refunded || $detail.product_quantity_refunded > $detail.product_quantity}style="text-decoration:line-through;"{/if}>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} >{$detail.product_name|escape:'htmlall':'UTF-8'}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$detail.product_quantity|intval}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $detail.product_quantity == $detail.product_quantity_refunded || ($detail.product_quantity - $detail.product_quantity_refunded < 0)}0{elseif ($detail.product_quantity - $detail.product_quantity_in_stock) < 0}{$detail.product_quantity|intval}{else}{math equation="x - y" x=$detail.product_quantity_in_stock y=$detail.product_quantity_refunded}{/if}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$detail.product_quantity_refunded|intval}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $detail.product_quantity == $detail.product_quantity_refunded || ($detail.product_quantity-$detail.product_quantity_refunded-$detail.product_quantity_in_stock-$detail.product_quantity_refunded) < 0}0{else}{math equation="x - y" x=($detail.product_quantity-$detail.product_quantity_refunded) y=$detail.product_quantity_in_stock-$detail.product_quantity_refunded}{/if}</td>
																						</tr>
																					{/foreach}
																				</tbody>
																			</table>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
        								{/foreach}
        							{elseif isset($customer_order[$combination.id_product]) && $customer_order[$combination.id_product]|@sizeof > 0}
        								{foreach $customer_order[$combination.id_product] AS $order}
        									<div class="panel-body">
												<div class="table-responsive">
													<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;">
														<thead>
															<tr class="nodrag nodrop">
																<th style="width:10%;">{l s='ID' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Reference' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Customer' mod='wic_erp'}</th>
																<th class="center" style="width:15%;">{l s='Total' mod='wic_erp'}</th>
																<th class="center" style="width:20%;">{l s='Status' mod='wic_erp'}</th>
																<th class="center" style="width:15%;">{l s='Date' mod='wic_erp'}</th>
															</tr>
														</thead>
														<tbody>
															<tr>
																<td>{$order.order->id|intval}</td>
																<td>{$order.order->reference|escape:'htmlall':'UTF-8'}</td>
																<td class="center">{$order.order->customer->firstname|escape:'htmlall':'UTF-8'} {$order.order->customer->lastname|escape:'htmlall':'UTF-8'}</td>
																<td class="center"><b>{displayPrice price=$order.order->total_paid_tax_incl currency=$order.order->id_currency}</b></td>
																<td class="center"><span class="badge" style="background-color:{$order.order->state->color|escape:'htmlall':'UTF-8'};">{$order.order->state->name|escape:'htmlall':'UTF-8'}</span></td>
																<td class="center"><i class="icon-calendar-o"></i> {dateFormat date=$order.order->date_add}</td>
															</tr>
															<tr>
																<td colspan="6" style="border:none!important; border-bottom:5px solid #a0d0eb!important; padding-left:6%;">
																	<div class="tab-pane active" id="products_purchased">
																		<div class="table-responsive">
																			<table id="product_details" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;margin:0;">
																				<thead>
																					<tr class="nodrag nodrop" style="background-color: #AAADAF;color: white;">
																						<th style="width:25%;padding:0;">{l s='Name' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Quantity ordered' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Stock reserved' mod='wic_erp'}</th>
																						<th class="center" style="width:15%;padding:0;">{l s='Quantity refunded' mod='wic_erp'}</th>
																						<th class="center" style="width:20%;padding:0;">{l s='Quantity to be ordered' mod='wic_erp'}</th>
																					</tr>
																				</thead>
																				<tbody>
																					{foreach $order.order_detail AS $detail}
																						<tr {if $detail.product_quantity == $detail.product_quantity_refunded}style="text-decoration:line-through;"{/if}>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} >{$detail.product_name|escape:'htmlall':'UTF-8'}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$detail.product_quantity|intval}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $detail.product_quantity == $detail.product_quantity_refunded}0{else}{math equation="x - y" x=$detail.product_quantity_in_stock y=$detail.product_quantity_refunded}{/if}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{$detail.product_quantity_refunded|intval}</td>
																							<td style="line-height: 1!important;{if $detail.product_quantity_in_stock < ($detail.product_quantity-$detail.product_quantity_refunded)}background-color: #fab4bf;"{elseif $detail.product_quantity == $detail.product_quantity_refunded}background-color: #F2D7F3;"{else}background-color: #b4fac5;"{/if} class="center">{if $detail.product_quantity == $detail.product_quantity_refunded}0{else}{math equation="x - y" x=($detail.product_quantity-$detail.product_quantity_refunded) y=$detail.product_quantity_in_stock-$detail.product_quantity_refunded}{/if}</td>
																						</tr>
																					{/foreach}
																				</tbody>
																			</table>
																		</div>
																	</div>
																</td>
															</tr>
														</tbody>
													</table>
												</div>
											</div>
        								{/foreach}
									{else}
										<div class="panel-body">
											<div class="list-empty-msg">
												<i class="icon-warning-sign list-empty-icon"></i>
												{l s='No customer order pending' mod='wic_erp'}
											</div>
										</div>
									{/if}
								</div>
								<!--End customer order //-->
								<!-- Supply order //-->
								<div class="panel">
        						<div class="panel-heading">
        							<h3>{l s='Supply Order pending' mod='wic_erp'}</h3></div>
        							{if isset($combination.id_product_attribute) && isset($supply_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute]) && $supply_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute]|@sizeof > 0}
        								<div class="panel-body">
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
														{foreach $supply_order[$combination.id_product|cat:"_"|cat:$combination.id_product_attribute] AS $detail}
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
										</div>
									{elseif isset($supply_order[$combination.id_product]) && $supply_order[$combination.id_product]|@sizeof > 0}
        								<div class="panel-body">
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
														{foreach $supply_order[$combination.id_product] AS $detail}
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
										</div>
									{else}
										<div class="panel-body">
											<div class="list-empty-msg">
												<i class="icon-warning-sign list-empty-icon"></i>
												{l s='No supply order pending' mod='wic_erp'}
											</div>
										</div>
									{/if}
								</div>
								<!-- End Supply Order //-->
        					</div>
    					</div>
  					</div>
  				{/foreach}
  				<!-- End foreach attribute combination //-->
  			</div>
  		{else}
  		
  		{/if}
	</div>
</div>
{/if}