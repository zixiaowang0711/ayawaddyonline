{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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
 * @copyright 2007-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
{foreach from=$homeBlocks item=block name=homeBlocks}
  <div class="block clearfix {$block.custom_class}">
    {if $block.block_type == $blocktype_product}    
      <div class="title-block d-flex flex-wrap">
        <span>{$block.title}</span>
        {if $block.category}
          <span class="view-all-link">
            <a href="{$block.category.url}">{l s='Show More' d='Shop.Zonetheme'} &nbsp;<i class="material-icons">&#xE8E4;</i></a>
          </span>
        {/if}
      </div>
      
      {if $block.products}
        {if $block.enable_slider}
          {include file="module:zonehomeblocks/views/templates/hook/product-list-slider.tpl" aproducts=$block.products slidesToShow=$block.number_column autoplay=$block.auto_scroll slickID="aSlick{$block.id}"}
        {else}
          {include file="module:zonehomeblocks/views/templates/hook/product-list.tpl" aproducts=$block.products grid=$block.number_column}
        {/if}
      {/if}

    {elseif $block.block_type == $blocktype_html}
      <div class="static-html typo">
        {$block.static_html nofilter}
      </div>
      <div style="height: 1px;"></div>

    {elseif $block.block_type == $blocktype_tabs}
      {include file="module:zonehomeblocks/views/templates/hook/zonehometabs.tpl" homeTabs=$block.home_tabs}
    {/if}
  </div>
{/foreach}