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
<div id="_desktop_cart">
  <div class="blockcart cart-preview js-sidebar-cart-trigger {if $cart.products_count > 0}active{else}inactive{/if}" data-refresh-url="{$refresh_url}">
    <ul class="cart-header"><li class="js-sticky-cart-source">
      <a rel="nofollow" href="{$cart_url}" class="cart-link btn-primary">
        <i class="material-icons">&#xE8CC;</i>
        <span class="cart-total-value">{$cart.totals.total.value}</span>
        <span class="cart-products-count">{$cart.products_count}</span>
      </a>
    </li></ul>
    <div class="cart-dropdown js-cart-source">
      <div class="cart-dropdown-wrapper">
        <div class="cart-title">
          <h4 class="text-center">{l s='Shopping Cart' d='Shop.Theme.Checkout'}</h4>
        </div>
        {if $cart.products}
          <ul class="cart-items">
            {foreach from=$cart.products item=product}
              <li class="cart-product-line">{include 'module:ps_shoppingcart/ps_shoppingcart-product-line.tpl' product=$product}</li>
            {/foreach}
          </ul>
          <div class="cart-bottom">
            <div class="cart-subtotals">
              {foreach from=$cart.subtotals item="subtotal"}
                {if $subtotal && $subtotal.type !== 'tax'}
                <div class="total-line {$subtotal.type}">
                  <span class="label">{$subtotal.label}</span>
                  <span class="value price">{$subtotal.value}</span>
                </div>
                {/if}
              {/foreach}
            </div>
            <div class="cart-total total-line">
              <span class="label">{$cart.totals.total.label}</span><span class="tax-short">{$cart.labels.tax_short}</span>
              <span class="value price price-total">{$cart.totals.total.value}</span>
            </div>
            <div class="cart-tax total-line">
              <small class="label">{$cart.subtotals.tax.label}</small>
              <small class="value price">{$cart.subtotals.tax.value}</small>
            </div>
            <div class="cart-action">
              <div class="text-center">
                <a href="{$cart_url}" class="btn btn-primary">{l s='Checkout' d='Shop.Theme.Actions'} &nbsp;<i class="caret-right"></i></a>
              </div>
            </div>
          </div>
        {else}
          <div class="no-items">
            {l s='There are no more items in your cart' d='Shop.Theme.Checkout'}
          </div>
        {/if}

        <div class="js-cart-update-quantity page-loading-overlay cart-overview-loading">
          <div class="page-loading-backdrop d-flex align-items-center justify-content-center">
            <div class="uil-spin-css"><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
