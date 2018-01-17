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
<div class="images-container {if isset($product_imageZoom) && $product_imageZoom}js-enable-zoom-image{else}js-cover-image{/if}">
  {if $product.cover}
    {block name='product_cover'}
      <div class="product-cover sm-bottom">
        <img
          class="img-fluid js-qv-product-cover js-main-zoom"
          src="{$product.cover.bySize.medium_default.url}"
          alt="{$product.cover.legend|default: $product.name}"
          data-zoom-image="{$product.cover.bySize.large_default.url}"
          data-id-image="{$product.cover.id_image}"
          itemprop="image"
        >
        <div class="layer d-flex align-items-center justify-content-center">
          <span class="zoom-in js-mfp-button"><i class="material-icons">&#xE56B;</i></span>
        </div>
      </div>
    {/block}

    {block name='product_thumbnails'}
      <div class="thumbs-list">
        {block name='product_images'}
          <div class="js-qv-mask mask">
            <ul class="product-images js-qv-product-images" id="js-zoom-gallery">
              {foreach from=$product.images item=image}
                <li class="thumb-container js-thumb-container">
                  <a
                    class="thumb js-thumb {if $image.id_image == $product.cover.id_image}selected{/if}"
                    data-image="{$image.bySize.medium_default.url}"
                    data-zoom-image="{$image.bySize.large_default.url}"
                    data-id-image="{$image.id_image}"
                  >
                    <img src="{$image.bySize.small_default.url}" alt="{$image.legend|default: $product.name}" class="img-fluid">
                  </a>
                </li>
              {/foreach}
            </ul>
          </div>
        {/block}

        <div class="scroll-box-arrows">
          <i class="material-icons left">&#xE314;</i>
          <i class="material-icons right">&#xE315;</i>
        </div>
      </div>
    {/block}
  {/if}
</div>

{hook h='displayAfterProductThumbs'}
