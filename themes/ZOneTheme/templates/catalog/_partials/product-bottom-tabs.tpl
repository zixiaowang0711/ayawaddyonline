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

<div class="product-tabs">
  <ul class="nav nav-tabs flex-md-wrap flex-lg-nowrap" role="tablist">
    <li class="nav-item">
      <a class="nav-link active" data-toggle="tab" href="#collapseDescription" role="tab">
        <span>{l s='Description' d='Shop.Theme.Catalog'}</span>
      </a>
    </li>
    <li class="nav-item {if !$product.features}hidden-xs-up{/if}">
      <a class="nav-link" data-toggle="tab" href="#collapseDetails" role="tab">
        <span>{l s='Data sheet' d='Shop.Theme.Catalog'}</span>
      </a>
    </li>
    {if $product.attachments}
    <li class="nav-item">
      <a class="nav-link" data-toggle="tab" href="#collapseAttachments" role="tab">
        <span>{l s='Attachments' d='Shop.Theme.Catalog'}</span>
      </a>
    </li>
    {/if}
    {if $product.extraContent}
    {foreach from=$product.extraContent item=extra key=extraKey}
      <li class="nav-item">
        <a class="nav-link" data-toggle="tab" href="#collapseExtra{$extraKey}" role="tab">
          <span>{$extra.title}</span>
        </a>
      </li>
    {/foreach}
    {/if}
  </ul>
  <div class="tab-content">
    <div id="collapseDescription" class="product-description-block tab-pane fade show active" role="tabpanel">
      <div class="panel-content">
        {include file='catalog/_partials/product-description.tpl'}
      </div>
    </div>
    <div id="collapseDetails" class="product-features-block tab-pane fade" role="tabpanel">
      <div class="panel-content">
        {include file='catalog/_partials/product-details.tpl'}
      </div>
    </div>
    {if $product.attachments}
    <div id="collapseAttachments" class="product-attachments-block tab-pane fade" role="tabpanel">
      <div class="panel-content">
        {include file='catalog/_partials/product_attachments.tpl'}
      </div>
    </div>
    {/if}
    {if $product.extraContent}
    {foreach from=$product.extraContent item=extra key=extraKey}
      <div id="collapseExtra{$extraKey}" class="product-extra-block tab-pane fade" role="tabpanel">
        <div class="panel-content" {foreach $extra.attr as $key => $val}{if $val}{$key}="{$val}" {/if}{/foreach}>
          <div class="extra-content typo">
            {$extra.content nofilter}
          </div>
        </div>
      </div>
    {/foreach}
    {/if}
  </div>
</div><!-- /tabs -->
