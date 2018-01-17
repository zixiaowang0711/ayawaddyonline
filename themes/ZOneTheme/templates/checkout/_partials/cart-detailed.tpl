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
{block name='cart_detailed_product'}
  <div class="cart-overview js-cart" data-refresh-url="{url entity='cart' params=['ajax' => true, 'action' => 'refresh']}">
    {if $cart.products}
      <div class="cart-container-wrapper light-box-bg">
        <ul class="cart-items">
          {foreach from=$cart.products item=product}
            <li class="cart-item">
              {block name='cart_detailed_product_line'}
                {include file='checkout/_partials/cart-detailed-product-line.tpl' product=$product}</li>
              {/block}
          {/foreach}
        </ul>
      </div>

      <div class="js-cart-update-quantity page-loading-overlay cart-overview-loading">
        <div class="page-loading-backdrop d-flex align-items-center justify-content-center">
          <div class="uil-spin-css"><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div></div>
        </div>
      </div>
    {else}
      <div class="no-items alert alert-warning">
        {l s='There are no more items in your cart' d='Shop.Theme.Checkout'}
      </div>
    {/if}
  </div>
{/block}
