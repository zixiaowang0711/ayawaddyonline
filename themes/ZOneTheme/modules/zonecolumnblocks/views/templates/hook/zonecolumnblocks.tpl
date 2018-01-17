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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if $columnBlocks}
<div class="aone-column">
	{foreach from=$columnBlocks item=block name=columnBlocks}
	  <div class="column-block md-bottom clearfix {$block.custom_class}">
		{if $block.block_type == $blocktype_product }
		  <h4 class="column-title">{$block.title}</h4>	  
		  
		  {if $block.products}
			{if $block.enable_slider}
			  {include file="module:zonecolumnblocks/views/templates/hook/product-list-slider.tpl" aproducts=$block.products thumb=$block.product_thumb autoplay=$block.auto_scroll slickID="aSlickColumn{$block.id}"}
			{else}
			  {include file="module:zonecolumnblocks/views/templates/hook/product-list.tpl" aproducts=$block.products thumb=$block.product_thumb}
			{/if}
		  {/if}
		{elseif $block.block_type == $blocktype_html}
		  <div class="static-html typo">
			{$block.static_html nofilter}
		  </div>
		{/if}
	  </div>
	{/foreach}
</div>
{/if}
