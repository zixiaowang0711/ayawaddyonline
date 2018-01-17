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
{l s='Taxes:' pdf='true' mod='wic_erp'}<br/>

<table id="tax-tab" width="100%">
	<thead>
		<tr>
			<th class="header-right small">{l s='Base TE' pdf='true' mod='wic_erp'}</th>
			<th class="header-right small">{l s='Tax Rate' pdf='true' mod='wic_erp'}</th>
			<th class="header-right small">{l s='Tax Value' pdf='true' mod='wic_erp'}</th>
		</tr>
	</thead>
	<tbody>
		{assign var=has_line value=false}

		{foreach $tax_order_summary as $entry}
			{assign var=has_line value=true}
			<tr>
				<td class="right white">{$currency->prefix|escape:'htmlall':'UTF-8'} {$entry['base_te']|escape:'htmlall':'UTF-8'} {$currency->suffix|escape:'htmlall':'UTF-8'}</td>
				<td class="right white">{$entry['tax_rate']|escape:'htmlall':'UTF-8'}</td>
				<td class="right white">{$currency->prefix|escape:'htmlall':'UTF-8'} {$entry['total_tax_value']|escape:'htmlall':'UTF-8'} {$currency->suffix|escape:'htmlall':'UTF-8'}</td>
			</tr>
		{/foreach}

		{if !$has_line}
		<tr>
			<td class="white center" colspan="3">
				{l s='No taxes' pdf='true' mod='wic_erp'}
			</td>
		</tr>
		{/if}

	</tbody>
</table>
