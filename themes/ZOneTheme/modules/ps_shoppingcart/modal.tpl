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
<div id="blockcart-modal" class="modal fade" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
        <h3 class="h5 modal-title text-center"><i class="material-icons">&#xE876;</i>{l s='Product successfully added to your shopping cart' d='Shop.Theme.Checkout'}</h3>
      </div>
      <div class="modal-body">
        <div class="cart-modal-wrapper row">
          <div class="col-12 col-md-6 divide-right">
            <div class="cart-product sm-bottom row">
              <div class="product-image col-4">
                {if $product.cover}<img class="img-thumbnail" src="{$product.cover.bySize.small_default.url}" alt="{$product.cover.legend}" title="{$product.cover.legend}">{/if}
              </div>
              <div class="product-infos col-8">
                <h6 class="h6 product-name">{$product.name}</h6>
                <div class="price product-price xs-bottom">{$product.price}</div>
                {hook h='displayProductPriceBlock' product=$product type="unit_price"}
                <div class="product-attributes">
                {foreach from=$product.attributes item="property_value" key="property"}
                  <p><strong>{$property}</strong>: <span>{$property_value}</span></p>
                {/foreach}
                <p><strong>{l s='Quantity:' d='Shop.Theme.Checkout'}</strong> <span>{$product.cart_quantity}</span></p>
                </div>
              </div>
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="cart-content">
              {if $cart.products_count > 1}
                <p class="cart-products-count">{l s='There are %products_count% items in your cart.' sprintf=['%products_count%' => $cart.products_count] d='Shop.Theme.Checkout'}</p>
              {else}
                <p class="cart-products-count">{l s='There is %product_count% item in your cart.' sprintf=['%product_count%' =>$cart.products_count] d='Shop.Theme.Checkout'}</p>
              {/if}
              <div class="cart-prices sm-bottom">
                <div class="cart-subtotals">
                  <p>
                    <label>{l s='Total products:' d='Shop.Theme.Checkout'}</label>
                    <span class="price">{$cart.subtotals.products.value}</span>
                  </p>
                  <p>
                    <label>{l s='Total shipping:' d='Shop.Theme.Checkout'}</label>
                    <span class="price">{$cart.subtotals.shipping.value} {hook h='displayCheckoutSubtotalDetails' subtotal=$cart.subtotals.shipping}</span>
                  </p>
                  {if $cart.subtotals.tax}
                    <p>
                      <label>{$cart.subtotals.tax.label}</label>
                      <span class="price">{$cart.subtotals.tax.value}</span>
                    </p>
                  {/if}
                </div>
                <p class="cart-total">
                  <label>{l s='Total:' d='Shop.Theme.Checkout'}</label>
                  <span class="price price-total">{$cart.totals.total.value}</span> {$cart.labels.tax_short}
                </p>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="cart-buttons">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">{l s='Continue shopping' d='Shop.Theme.Actions'}</button>
          <a href="{$cart_url}" class="btn btn-primary"><i class="material-icons">&#xE876;</i> {l s='Proceed to checkout' d='Shop.Theme.Actions'}</a>
        </div>
      </div>
    </div>
  </div>
</div>
