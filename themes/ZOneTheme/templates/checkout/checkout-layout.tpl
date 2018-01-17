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
{extends file=$layout}

{block name='content'}
  <section id="main">
    {block name='page_heading'}
      <h1 class="page-heading">{l s='Shopping Cart' d='Shop.Theme.Checkout'}</h1>
    {/block}

    <div class="cart-grid mb-3 row">
      <div class="cart-grid-body col-12 col-lg-8 mb-4">
      	{block name='cart_grid_body'}{/block}
      </div>

      <div class="cart-grid-right col-12 col-lg-4 mb-4">
        {block name='cart_grid_right'}{/block}

        {block name='display_reassurance'}
          {hook h='displayReassurance'}
        {/block}
      </div>
    </div>

    {block name='hook_shopping_cart_footer'}{/block}
  </section>
{/block}
