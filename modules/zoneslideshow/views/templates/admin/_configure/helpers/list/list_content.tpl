{*
* 2007-2017 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{extends file="helpers/list/list_content.tpl"}

{block name="open_td"}
	<td
		{if isset($params.type) && $params.type == 'zposition'}
			id="td_{if !empty($position_group_identifier)}{$position_group_identifier|escape:'html':'UTF-8'}{else}0{/if}_{$tr.$identifier|escape:'html':'UTF-8'}{if $smarty.capture.tr_count > 1}_{($smarty.capture.tr_count - 1)|intval}{/if}"
		{/if}
		class="{strip}{if !$no_link}pointer{/if}
		{if isset($params.type) && $params.type == 'zposition'} dragHandle{/if}
		{if isset($params.class)} {$params.class|escape:'html':'UTF-8'}{/if}
		{if isset($params.align)} {$params.align|escape:'html':'UTF-8'}{/if}{/strip}"
		{if (!isset($params.position) && !$no_link && !isset($params.remove_onclick))}
			onclick="document.location = '{$current_index|escape:'html':'UTF-8'}&amp;{$identifier|escape:'html':'UTF-8'}={$tr.$identifier|escape:'html':'UTF-8'}{if $view}&amp;view{else}&amp;update{/if}{$table|escape:'html':'UTF-8'}&amp;token={$token|escape:'html':'UTF-8'}'">
		{else}
	>
		{/if}
{/block}

{block name="td_content"}
	{if isset($params.type) && $params.type == 'zposition'}
		<div class="dragGroup">
			<div class="positions">
				{$tr.$key.position|intval}
			</div>
		</div>
	{elseif isset($params.type) && $params.type == 'zimage'}	
		<img src="{$image_baseurl|escape:'html':'UTF-8'}{$tr.image|escape:'html':'UTF-8'}" alt="{$tr.title|escape:'html':'UTF-8'}" class="img-thumbnail" />
	{elseif isset($params.type) && $params.type == 'zdetails'}	
		<p>{$tr.title|escape:'html':'UTF-8'}</p>
		{if $tr.link}<p>{$tr.link|escape:'html':'UTF-8'}</p>{/if}
		{if $tr.transition}<p>{$tr.transition|escape:'html':'UTF-8'}</p>{/if}
	{else}
		{$smarty.block.parent}
	{/if}
{/block}
