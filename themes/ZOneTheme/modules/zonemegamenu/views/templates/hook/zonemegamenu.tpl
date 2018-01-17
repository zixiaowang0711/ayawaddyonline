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

{if $zmenus}
<div class="amega-menu hidden-sm-down">
<div id="amegamenu" class="{if $is_rtl}amegamenu_rtl{/if}">
  <ul class="anav-top">
  {foreach from=$zmenus item=menu}
  <li class="amenu-item mm-{$menu.id_zmenu} {if $menu.dropdowns}plex{/if}">
    {if $menu.link}<a href="{$menu.link}" class="amenu-link">{else}<span class="amenu-link">{/if}
      {$menu.name}
      {if $menu.label}<sup {if $menu.label_color}style="background-color: {$menu.label_color}; color: {$menu.label_color};"{/if}><span>{$menu.label}</span></sup>{/if}
    {if $menu.link}</a>{else}</span>{/if}

    {if $menu.dropdowns && $menu.drop_column}
    <span class="mobile-toggle-plus"><i class="caret-down-icon"></i></span>
    <div class="adropdown adropdown-{$menu.drop_column}" {if $menu.drop_bgcolor}style="background-color: {$menu.drop_bgcolor};"{/if}>
      <div class="dropdown-bgimage" {if $menu.drop_bgimage}style="background-image: url('{$bg_image_url}{$menu.drop_bgimage}'); background-position: {$menu.bgimage_position}; {$menu.position}"{/if}></div>
      
      {foreach from=$menu.dropdowns item=dropdown}
      {if $dropdown.content_type != 'none' && $dropdown.column}
      <div class="dropdown-content acontent-{$dropdown.column} dd-{$dropdown.id_zdropdown} {$dropdown.custom_class}">
        {if $dropdown.content_type == 'category'}
          {if $dropdown.categories}
          <div class="categories-grid">
          {foreach from=$dropdown.categories item=category name=categories}
            <div class="category-item">
              {if $category.id_category}<h5 class="category-title"><a href="{$category.url}" title="">{$category.name}</a></h5>{/if}
              {if $category.subcategories}
              <ul>
                {foreach from=$category.subcategories item=subcategory}
                <li><a href="{$subcategory.url}" title="">{$subcategory.name}</a></li>
                {/foreach}
              </ul>
              {/if}
            </div>
          {/foreach}
          </div>
          {/if}

        {elseif $dropdown.content_type == 'product'}
          {if $dropdown.products}
          <div class="products-grid">
          {foreach from=$dropdown.products item=product name=products}
            <div class="product-item">
              <div class="product-thumbnail"><a href="{$product.url}" title="{$product.name}"><img class="img-fluid" src="{$product.cover.medium.url}" alt="{$product.cover.legend}" /></a></div>
              <h5 class="product-name"><a href="{$product.url}" title="{$product.name}">{$product.name}</a></h5>
              {if $product.show_price}
                <div class="product-price-and-shipping d-flex flex-wrap align-items-center justify-content-center">
                  <span class="price product-price">{$product.price}</span>
                  {if $product.has_discount}<span class="regular-price">{$product.regular_price}</span>{/if}
                </div>
              {/if}
            </div>
          {/foreach}
          </div>
          {/if}

        {elseif $dropdown.content_type == 'manufacturer'}
          {if $dropdown.manufacturers}
          <div class="manufacturers-grid">
          {foreach from=$dropdown.manufacturers item=manufacturer name=manufacturers}
            <div class="manufacturer-item brand-base">
              <div class="left-side"><div class="logo"><a href="{$manufacturer.url}" title=""><img class="img-fluid" src="{$manufacturer.image}" alt="" /></a></div></div>
              <div class="middle-side"><a class="product-name" href="{$manufacturer.url}" title="">{$manufacturer.name}</a></div>
            </div>
          {/foreach}
          </div>
          {/if}

        {elseif $dropdown.content_type == 'html'}
          {if $dropdown.static_content}
          <div class="html-item typo">
            {$dropdown.static_content nofilter}
          </div>
          {/if}
        {/if}
      </div>
      {/if}
      {/foreach}
    </div>
    {/if}
  </li>
  {/foreach}
  </ul>
</div>
</div>
{/if}
