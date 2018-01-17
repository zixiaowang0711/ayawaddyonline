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
<div class="product-line-grid row">
  <div class="product-line-grid-left col-md-2 col-3">
    <span class="product-image media-middle">
     {if $product.cover} <img src="{$product.cover.bySize.cart_default.url}" alt="{$product.name}" class="img-fluid">{/if}
    </span>
  </div>

  <div class="product-line-grid-body col-md-4 col-9">
    <h5 class="product-name"><a href="{$product.url}" data-id_customization="{$product.id_customization|intval}">{$product.name}</a></h5>

    <div class="product-prices">
      <span class="price">{$product.price}</span>
      {if $product.unit_price_full}
        <span class="unit-price-cart">{$product.unit_price_full}</span>
      {/if}
    </div>

    {foreach from=$product.attributes key="attribute" item="value"}
      <div class="product-line-info">
        <span><i>{$attribute}: {$value}</i></span>
      </div>
    {/foreach}

    {if $product.customizations|count}
      {block name='cart_detailed_product_line_customization'}
        {foreach from=$product.customizations item="customization"}
          <a href="#" data-toggle="modal" data-target="#product-customizations-modal-{$customization.id_customization}">{l s='Product customization' d='Shop.Theme.Catalog'}</a>
          <div class="modal fade customization-modal" id="product-customizations-modal-{$customization.id_customization}" tabindex="-1" role="dialog" aria-hidden="true">
            <div class="modal-dialog" role="document">
              <div class="modal-content">
                <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                  </button>
                  <h4 class="modal-title">{l s='Product customization' d='Shop.Theme.Catalog'}</h4>
                </div>
                <div class="modal-body">
                  {foreach from=$customization.fields item="field"}
                    <div class="product-customization-line row">
                      <div class="col-sm-3 col-4 label">
                        {$field.label}
                      </div>
                      <div class="col-sm-9 col-8 value">
                        {if $field.type == 'text'}
                          {if (int)$field.id_module}
                            {$field.text nofilter}
                          {else}
                            {$field.text}
                          {/if}
                        {elseif $field.type == 'image'}
                          <img src="{$field.image.small.url}">
                        {/if}
                      </div>
                    </div>
                  {/foreach}
                </div>
              </div>
            </div>
          </div>
        {/foreach}
      {/block}
    {/if}
  </div>

  <div class="product-line-grid-right product-line-actions col-md-6 col-12">
    <div class="row">
      <div class="col-md-10 col-10">
        <div class="row">
          <div class="col-md-6 col-6 qty-col">
            {if isset($product.is_gift) && $product.is_gift}
              <span class="gift-quantity">{$product.quantity}</span>
            {else}
              <input
                class="js-cart-line-product-quantity"
                data-down-url="{$product.down_quantity_url}"
                data-up-url="{$product.up_quantity_url}"
                data-update-url="{$product.update_quantity_url}"
                data-product-id="{$product.id_product}"
                type="text"
                value="{$product.quantity}"
                name="product-quantity-spin"
                min="{$product.minimal_quantity}"
              />
            {/if}
          </div>
          <div class="col-md-6 col-6 price-col text-center text-sm-left">
            <span class="price product-price">
              {if isset($product.is_gift) && $product.is_gift}
                <span class="gift">{l s='Gift' d='Shop.Theme.Checkout'}</span>
              {else}
                {$product.total}
              {/if}
            </span>
          </div>
        </div>
      </div>
      <div class="col-md-2 col-2 text-right">
        <div class="cart-line-product-actions actions-col">
          <a
            class                       = "remove-from-cart"
            rel                         = "nofollow"
            href                        = "{$product.remove_from_cart_url}"
            data-link-action            = "delete-from-cart"
            data-id-product             = "{$product.id_product|escape:'javascript'}"
            data-id-product-attribute   = "{$product.id_product_attribute|escape:'javascript'}"
            data-id-customization       = "{$product.id_customization|escape:'javascript'}"
            title                       = "{l s='Delete' d='Shop.Theme.Actions'}"
          >
            {if !isset($product.is_gift) || !$product.is_gift}
              <i class="material-icons">&#xE872;</i>
            {/if}
          </a>

          {block name='hook_cart_extra_product_actions'}
            {hook h='displayCartExtraProductActions' product=$product}
          {/block}
        </div>
      </div>
    </div>
  </div>
</div>
