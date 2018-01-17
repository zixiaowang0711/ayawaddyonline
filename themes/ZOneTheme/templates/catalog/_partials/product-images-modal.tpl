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

{if $product.cover}
<ul
  id="js_mfp_gallery"
  class="hidden-xs-up"
  data-text-close="{l s='Close (Esc)' d='Shop.Zonetheme'}"
  data-text-prev="{l s='Previous (Left arrow key)' d='Shop.Zonetheme'}"
  data-text-next="{l s='Next (Right arrow key)' d='Shop.Zonetheme'}"
>
  {foreach from=$product.images item=image name=productImages}
    <li
      class="js_mfp_gallery_item"
      data-id-image="{$image.id_image}"
      data-mfp-src="{$image.bySize.large_default.url}"
    ></li>
  {/foreach}
</ul>
{/if}
