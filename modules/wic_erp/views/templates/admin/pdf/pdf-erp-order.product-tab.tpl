{*
* 2007-2015 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @license   http://opensource.org/licenses/afl-3.0.php Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}
{l s='Products ordered:' pdf='true' mod='wic_erp'}<br/>

<table class="product small" width="100%" cellpadding="4" cellspacing="0">

	<thead>
	<tr>
		<th class="product header small" width="10%">{l s='Reference' pdf='true' mod='wic_erp'}</th>
        <th class="product header small" width="10%">{l s='Supplier Ref' pdf='true' mod='wic_erp'}</th>
        <th class="product header small" width="15%">{l s='Ean13' pdf='true' mod='wic_erp'}</th>
		<th class="product header small" width="26%">{l s='Designation' pdf='true' mod='wic_erp'}</th>
		<th class="product header small" width="5%">{l s='Qty' pdf='true' mod='wic_erp'}</th>
		<th class="product header small" width="9%">{l s='Unit Price TE' pdf='true' mod='wic_erp'}</th>
		<th class="product header small" width="8%">{l s='Discount Rate' pdf='true' mod='wic_erp'}</th>
		<th class="product header small" width="8%">{l s='Tax rate' pdf='true' mod='wic_erp'}</th>
		<th class="product header small" width="9%">{l s='Total TI' pdf='true' mod='wic_erp'}</th>
	</tr>
	</thead>

	<tbody>

	{foreach $erp_order_details as $erp_order_detail}
		{cycle values=["color_line_even", "color_line_odd"] assign=bgcolor_class}
		<tr class="product {$bgcolor_class|escape:'htmlall':'UTF-8'}">
            <td class="product left">
				{$erp_order_detail->reference|escape:'htmlall':'UTF-8'}
			</td>	
            <td class="product left">
				{$erp_order_detail->supplier_reference|escape:'htmlall':'UTF-8'}
			</td>
                        <td>
                            {if file_exists("`$imgUrl`barcode_`$erp_order_detail->ean13`.png")}
                                <img src="{$imgUrl}barcode_{$erp_order_detail->ean13|escape:'htmlall':'UTF-8'}.png">
                            {else}
                                {$erp_order_detail->ean13|escape:'htmlall':'UTF-8'}
                            {/if}
                        </td>
			<td class="product left">
				{$erp_order_detail->name|escape:'htmlall':'UTF-8'}
			</td>
			<td  class="product right">
				{$erp_order_detail->quantity_ordered|escape:'htmlall':'UTF-8'}
			</td>
			<td  class="product right">
				{$currency->prefix|escape:'htmlall':'UTF-8'} {$erp_order_detail->unit_price_te|escape:'htmlall':'UTF-8'} {$currency->suffix|escape:'htmlall':'UTF-8'}
                        </td>
			<td  class="product right">
				{$erp_order_detail->discount_rate|escape:'htmlall':'UTF-8'}
                        </td>
			<td  class="product right">
				{$erp_order_detail->tax_rate|escape:'htmlall':'UTF-8'}
			</td>
			<td  class="product right">
				{$currency->prefix|escape:'htmlall':'UTF-8'} {$erp_order_detail->price_ti|escape:'htmlall':'UTF-8'} {$currency->suffix|escape:'htmlall':'UTF-8'}
			</td>
		</tr>
	{/foreach}

	</tbody>

</table>
