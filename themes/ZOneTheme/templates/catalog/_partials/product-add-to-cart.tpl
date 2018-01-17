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
<div class="product-add-to-cart">
  {if !$configuration.is_catalog}
    
    {block name='product_quantity'}
      <div class="product-quantity row sm-bottom">
        <label class="form-control-label col-3 col-xl-2">{l s='Quantity' d='Shop.Theme.Catalog'}</label>
        <div class="col-9 col-xl-10">
          <div class="qty">
            <input
              type="number"
              name="qty"
              id="quantity_wanted"
              value="{$product.quantity_wanted}"
              class="form-control"
              min="{$product.minimal_quantity}"
              aria-label="{l s='Quantity' d='Shop.Theme.Actions'}"
            />
          </div>
        </div>
      </div>
    {/block}

    {block name='product_minimal_quantity'}
      <div class="product-minimal-quantity">
        {if $product.minimal_quantity > 1}
          <p class="product-minimal-quantity-text">
            <i>{l
              s='The minimum purchase order quantity for the product is %quantity%.'
              d='Shop.Theme.Checkout'
              sprintf=['%quantity%' => $product.minimal_quantity]
            }</i>
          </p>
        {/if}
      </div>
    {/block}

    {block name='product_add_to_cart_button'}
      <div class="product-add-to-cart-button mb-2 row">
        <div class="add col-12 col-md-9 col-xl-10">
          <button
            class="btn add-to-cart"
            data-button-action="add-to-cart"
            type="submit"
            {if !$product.add_to_cart_url}disabled{/if}
          >
            <i class="material-icons shopping-cart">&#xE547;</i><span>{l s='Add to cart' d='Shop.Theme.Actions'}</span>
          </button>
          
          {block name='product_availability'}
          {if $product.show_availability && $product.availability_message}
          <div class="js-product-availability-source hidden-xs-up">
            <span id="product-availability">
              {if $product.availability == 'available' && $product.quantity > 0}
                <span class="product-availability product-available alert alert-success">
                  <i class="material-icons">&#xE5CA;</i>
                  {$product.availability_message}
                </span>
              {elseif $product.availability == 'last_remaining_items'}
                <span class="product-availability product-last-items alert alert-info">
                  <i class="material-icons">&#xE002;</i>
                  {$product.availability_message}
                </span>
              {else}
                <span class="product-availability product-unavailable alert alert-warning">
                  <i class="material-icons">&#xE14B;</i>
                  {$product.availability_message}
                </span>
              {/if}
            </span>
          </div>
          {/if}
          {/block}
        </div>
      </div>
    {/block}

  {/if}
</div>
