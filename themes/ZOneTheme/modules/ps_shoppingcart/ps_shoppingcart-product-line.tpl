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
<span class="product-image">
  {if $product.cover}<img src="{$product.cover.bySize.cart_default.url}" alt="{$product.name}" class="img-fluid">{/if}
</span>
<div class="product-infos">
  <a class="product-name" href="{$product.url}">{$product.name}</a>
  <span class="product-attributes">
  {foreach from=$product.attributes item=value name=attributes}{if !$smarty.foreach.attributes.first}, {/if}{$value}{/foreach}
  </span>
  <span class="product-price-quantity">
    <span class="product-price">{$product.price}</span><span class="x-character">x</span><span class="product-quantity">{$product.quantity}</span>
  </span>
</div>
<a
  class                       = "remove-from-cart"
  rel                         = "nofollow"
  href                        = "{$product.remove_from_cart_url}"
  data-link-action            = "delete-from-cart"
  data-id-product             = "{$product.id_product}"
  data-id-product-attribute   = "{$product.id_product_attribute}"
  data-id-customization       = "{$product.id_customization}"
  title                       = "{l s='Delete' d='Shop.Theme.Actions'}"
>
  {if !isset($product.is_gift) || !$product.is_gift}
    <i class="material-icons">&#xE872;</i>
  {/if}
</a>
{if $product.customizations|count}{/if}
