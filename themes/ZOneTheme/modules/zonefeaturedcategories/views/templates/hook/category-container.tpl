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

<div class="category-block">
  <div class="category-container">
    <div class="category-image">
      {if isset($category.image) && $category.image}
        <a href="{$category.url}">
          <img
            class="img-fluid"
            src="{$category.image}"
            alt="{$category.meta_title}"
            {if isset($imageSize)}
              width="{$imageSize.width}"
              height="{$imageSize.height}"
            {/if}
          />
        </a>
      {/if}
    </div>

    <h3 class="category-name"><a href="{$category.url}" class="li-a">{$category.name}</a></h3>

    <div class="category-des">
      {$category.description|strip_tags|truncate:180}
    </div>

    {if $subCategories && $layoutStyle == 'medium'}
      <div class="sub-categories">
        <ul class="linklist d-flex flex-wrap">
          {foreach from=$subCategories item=subcategory name=subCategories}
            <li>
              <a class="subcategory-name" href="{$subcategory.url}">{$subcategory.name}</a>
            </li>
          {/foreach}
        </ul>
      </div>
    {/if}

    <div class="shop-now">
      <a class="btn btn-primary" href="{$category.url}">{l s='Shop Now' d='Shop.Zonetheme'} &nbsp;<i class="caret-right"></i></a>
    </div>
  </div>
</div>
