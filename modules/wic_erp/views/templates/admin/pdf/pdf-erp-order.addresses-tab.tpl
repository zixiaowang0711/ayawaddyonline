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
<table id="addresses-tab" cellspacing="0" cellpadding="0">
	<tr>
		<td width="40%"><span class="bold"> </span><br/><br/>
			{$shop_name|escape:'htmlall':'UTF-8'}<br/>
			{$shop_address|escape:'html':'UTF-8'}
		</td>
		<td width="20%">&nbsp;</td>
		<td width="40%"><span class="bold"> </span><br/><br/>
			{$supplier_name|escape:'htmlall':'UTF-8'}<br/>
			{$address_supplier->address1|escape:'htmlall':'UTF-8'}<br/>
			{if !empty($address_supplier->address2)|escape:'htmlall':'UTF-8'}{$address_supplier->address2|escape:'htmlall':'UTF-8'}<br/>{/if}
			{$address_supplier->postcode|escape:'htmlall':'UTF-8'} {$address_supplier->city|escape:'htmlall':'UTF-8'}<br/>
			{$address_supplier->country|escape:'htmlall':'UTF-8'}
		</td>
	</tr>
</table>
