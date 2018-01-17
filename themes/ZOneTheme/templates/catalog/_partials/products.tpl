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
{if !isset($productGridColumns)}
  {assign var='productGridColumns' value=3}
{/if}

<div id="js-product-list" data-grid-columns="columns-{$productGridColumns}">
  <div class="product-list">
    <div class="products product-list-wrapper clearfix grid columns-{$productGridColumns} js-product-list-view">
      {foreach from=$listing.products item="product"}
        {block name='product_miniature'}
          {include file='catalog/_partials/miniatures/product.tpl' product=$product image_type='home_default'}
        {/block}
      {/foreach}
    </div>
  </div>

  {block name='pagination'}
    {include file='_partials/pagination.tpl' pagination=$listing.pagination}
  {/block}
</div>
