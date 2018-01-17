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
<article class="product-miniature product-simple product-style" data-id-product="{$product.id_product}" data-id-product-attribute="{$product.id_product_attribute}">
  <div class="product-container">
    <div class="first-block">
      {block name='product_thumbnail'}
      {if $product.cover}
        {if !isset($image_type) || !array_key_exists($image_type, $product.cover.bySize)}
          {assign var='image_type' value='home_default'}
        {/if}
        {assign var=thumbnail value=$product.cover.bySize.$image_type}
        <div class="product-thumbnail">
          <a href="{$product.url}" class="product-cover-link">
            <img
              src     = "{$thumbnail.url}"
              alt     = "{$product.cover.legend|default: $product.name}"
              class   = "img-fluid"
              width   = "{$thumbnail.width}"
              height  = "{$thumbnail.height}"
            >
          </a>
        </div>
      {/if}
      {/block}
    </div><!-- /first-block -->

    <div class="second-block">
      {block name='product_name'}
        <h5 class="product-name"><a href="{$product.url}">{$product.name}</a></h5>
      {/block}

      {block name='product_price_and_shipping'}
        {if $product.show_price}
          <div class="product-price-and-shipping d-flex flex-wrap align-items-center">

            <span class="price product-price">{$product.price}</span>

            {if $product.has_discount}
              <span class="regular-price">{$product.regular_price}</span>
              {if $product.discount_type === 'percentage'}
                <span class="discount-percentage">{$product.discount_percentage}</span>
              {/if}
            {/if}
          </div>
        {/if}
      {/block}
    </div><!-- /second-block -->
  </div><!-- /product-container -->
</article>
