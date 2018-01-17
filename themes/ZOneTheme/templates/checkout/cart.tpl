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
{extends file='checkout/checkout-layout.tpl'}

{block name='cart_grid_body'}
  <div class="cart-container">
    {block name='cart_overview'}
      {include file='checkout/_partials/cart-detailed.tpl' cart=$cart}
    {/block}
  </div>

  {block name='continue_shopping'}
  <div class="cart-continue-shopping hidden-md-down">
    <a class="btn btn-secondary" href="{$urls.pages.index}">
      <i class="material-icons">&#xE5CB;</i> {l s='Continue shopping' d='Shop.Theme.Actions'}
    </a>
  </div>
  {/block}
{/block}

{block name='cart_grid_right'}
  {block name='cart_summary'}
    <div class="light-box-bg cart-summary mb-4">

      {block name='hook_shopping_cart'}
        {hook h='displayShoppingCart'}
      {/block}

      {block name='cart_totals'}
        {include file='checkout/_partials/cart-detailed-totals.tpl' cart=$cart}
      {/block}

      {block name='cart_actions'}
        {include file='checkout/_partials/cart-detailed-actions.tpl' cart=$cart}
      {/block}

    </div>
  {/block}
{/block}

{block name='hook_shopping_cart_footer'}
  {hook h='displayShoppingCartFooter'}
{/block}
