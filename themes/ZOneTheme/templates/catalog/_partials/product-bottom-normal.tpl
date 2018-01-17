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

<div class="product-normal-layout">
  <div class="block md-bottom product-description-block {if !$product.description}hidden-xs-up{/if}">
    <h3 class="title-block">{l s='Description' d='Shop.Theme.Catalog'}</h3>
    <div class="block-content">
      {include file='catalog/_partials/product-description.tpl'}
    </div>
  </div>

  <div class="block md-bottom product-features-block {if !$product.features && !isset($product.specific_references)}hidden-xs-up{/if}">
    <h3 class="title-block">{l s='Data sheet' d='Shop.Theme.Catalog'}</h3>
    <div class="block-content">
      {include file='catalog/_partials/product-details.tpl'}
    </div>
  </div>


  {if $product.attachments}
  <div class="block product-attachments-block sm-bottom">
    <h3 class="title-block">{l s='Attachments' d='Shop.Theme.Catalog'}</h3>
    <div class="block-content">
      {include file='catalog/_partials/product_attachments.tpl'}
    </div>
  </div>
  {/if}

  {if $product.extraContent}
    {foreach from=$product.extraContent item=extra key=extraKey}
      <div class="block md-bottom product-extra-block">
        <h3 class="title-block">{$extra.title}</h3>
        <div class="block-content {foreach $extra.attr as $key => $val}{if $val}{$key}="{$val}" {/if}{/foreach}">
          <div class="extra-content typo">
            {$extra.content nofilter}
          </div>
        </div>
      </div>
    {/foreach}
  {/if}
</div>
<!-- /normal -->
