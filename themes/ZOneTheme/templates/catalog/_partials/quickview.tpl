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
<div id="quickview-modal-{$product.id}-{$product.id_product_attribute}" class="modal fade quickview" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
  <div class="modal-content">
    <div class="modal-header">
      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
        <span aria-hidden="true">&times;</span>
      </button>
    </div>
    <div class="modal-body">
      <div class="main-product-details" id="quickviewProduct">
        <div class="row">
          <div class="product-left col-12 col-md-5">          
            <section class="product-left-content">
              {block name='product_cover_thumbnails'}
                {include file='catalog/_partials/product-cover-thumbnails.tpl' quickview=true}
              {/block}
              
              {block name='product_flags'}
                <div class="product-flags">
                  {foreach from=$product.flags item=flag}
                    <span class="product-flag {$flag.type}"><span>{$flag.label}</span></span>
                  {/foreach}
                </div>
              {/block}
            </section>
          </div>
          <div class="product-right col-12 col-md-7">
            <section class="product-right-content">
              <h2 class="page-heading" itemprop="name">{$product.name}</h2>

              <div class="product-attributes mb-2 js-product-attributes-destination"></div>

              <div class="product-availability-top mb-3 js-product-availability-destination"></div>

              {block name='product_out_of_stock'}
                <div class="product-out-of-stock">
                  {hook h='actionProductOutOfStock' product=$product}
                </div>
              {/block}

              {block name='product_description_short'}
                <div id="product-description-short-{$product.id}" class="product-description-short sm-bottom" itemprop="description">
                  {$product.description_short nofilter}
                </div>
              {/block}

              <div class="product-information light-box-bg sm-bottom">
                {block name='product_prices'}
                  {include file='catalog/_partials/product-prices.tpl'}
                {/block}

                {if $product.is_customizable && count($product.customizations.fields)}
                  {block name='product_customization'}
                    {include file="catalog/_partials/product-customization.tpl" customizations=$product.customizations}
                  {/block}
                {/if}

                <div class="product-actions">
                  {block name='product_buy'}
                    <form action="{$urls.pages.cart}" method="post" id="add-to-cart-or-refresh">
                      <input type="hidden" name="token" value="{$static_token}">
                      <input type="hidden" name="id_product" value="{$product.id}" id="product_page_product_id">
                      <input type="hidden" name="id_customization" value="{$product.id_customization}" id="product_customization_id">

                      {block name='product_variants'}
                        {include file='catalog/_partials/product-variants.tpl'}
                      {/block}

                      {block name='product_pack'}
                        {if $packItems}
                          <section class="product-pack md-bottom">
                            <label>{l s='This pack contains' d='Shop.Theme.Catalog'}</label>
                            <div class="pack-product-items">
                            {foreach from=$packItems item="product_pack"}
                              {block name='product_miniature'}
                                {include file='catalog/_partials/miniatures/pack-product.tpl' product=$product_pack}
                              {/block}
                            {/foreach}
                            </div>
                          </section>
                        {/if}
                      {/block}

                      {block name='product_discounts'}
                        {include file='catalog/_partials/product-discounts.tpl'}
                      {/block}

                      {block name='product_add_to_cart'}
                        {include file='catalog/_partials/product-add-to-cart.tpl'}
                      {/block}

                      {block name='product_refresh'}
                        <input class="product-refresh" data-url-update="false" name="refresh" type="hidden" value="{l s='Refresh' d='Shop.Theme.Actions'}" hidden>
                      {/block}

                    </form>
                  {/block}
                </div>
              </div><!-- /product-information -->

              <div class="reassurance-hook">
                {hook h='displayReassurance'}
              </div>
            </section>
          </div>
        </div>
        <div class="js-pending-query js-product-refresh-pending-query page-loading-overlay main-product-details-loading">
          <div class="page-loading-backdrop d-flex align-items-center justify-content-center">
            <div class="uil-spin-css"><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div></div>
          </div>
        </div>
      </div>

      <div class="hidden-xs-up">
        {include file='catalog/_partials/product-details.tpl'}
      </div>
    </div>
    <div class="modal-footer">
      <div class="row">
        <div class="col-12 col-md-8">
          {widget name='ps_sharebuttons' product=$product}
        </div>
        <div class="col-12 col-md-4">
          <a 
            class="btn btn-secondary view-details" 
            href="{$product.url}"
          ><span>{l s='View details' d='Shop.Zonetheme'}</span> &nbsp;<i class="caret-right"></i></a>
        </div>
      </div>
    </div>
  </div>
  </div>
</div>
