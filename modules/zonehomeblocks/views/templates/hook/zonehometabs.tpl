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
{if $homeTabs}
<div class="aone-tabs">
  <ul class="nav nav-tabs" role="tablist">
    {foreach from=$homeTabs item=block name=homeTabs}
      <li class="nav-item">
        <a 
          class="nav-link {if $smarty.foreach.homeTabs.first}active{/if}"
          data-toggle="tab"
          href="#aHomeTab{$block.id}"
          data-slickID="aTabSlick{$block.id}"
          role="tab"
        >
          <span>{$block.title}</span>
        </a>
      </li>
    {/foreach}
  </ul>

  <div class="tab-content">
    {foreach from=$homeTabs item=block name=homeTabs}
      <div id="aHomeTab{$block.id}" class="tab-pane fade {if $smarty.foreach.homeTabs.first}show active{/if}" role="tabpanel">
      {if $block.block_type == $blocktype_product}
        {if $block.products}
          {if $block.enable_slider}
            {include file="module:zonehomeblocks/views/templates/hook/product-list-slider.tpl" aproducts=$block.products slidesToShow=$block.number_column autoplay=$block.auto_scroll slickID="aTabSlick{$block.id}"}
          {else}
            {include file="module:zonehomeblocks/views/templates/hook/product-list.tpl" aproducts=$block.products grid=$block.number_column}
          {/if}
        {/if}

        {if $block.category}
          <div class="view-all-link at-bottom">
            <a class="btn btn-secondary" href="{$block.category.url}">{l s='Show More' d='Shop.Zonetheme'} &nbsp;<i class="material-icons">&#xE8E4;</i></a>
          </div>
        {/if}
      {elseif $block.block_type == $blocktype_html}
        <div class="static-html typo">
          {$block.static_html nofilter}
        </div>
        <div style="height: 1px;"></div>
      {/if}
      </div>
    {/foreach}
  </div>
</div>
{/if}
