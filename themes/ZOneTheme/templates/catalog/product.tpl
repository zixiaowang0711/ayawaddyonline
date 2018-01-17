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

{block name='head_seo' prepend}
  <link rel="canonical" href="{$product.canonical_url}">
{/block}

{block name='head' append}
  <meta property="og:type" content="product">
  <meta property="og:url" content="{$urls.current_url}">
  <meta property="og:title" content="{$page.meta.title}">
  <meta property="og:site_name" content="{$shop.name}">
  <meta property="og:description" content="{$page.meta.description}">
  <meta property="og:image" content="{$product.cover.large.url}">
  <meta property="product:pretax_price:amount" content="{$product.price_tax_exc}">
  <meta property="product:pretax_price:currency" content="{$currency.iso_code}">
  <meta property="product:price:amount" content="{$product.price_amount}">
  <meta property="product:price:currency" content="{$currency.iso_code}">
  {if isset($product.weight) && ($product.weight != 0)}
  <meta property="product:weight:value" content="{$product.weight}">
  <meta property="product:weight:units" content="{$product.weight_unit}">
  {/if}
{/block}

{block name='content'}
<section itemscope itemtype="https://schema.org/Product">
  <meta itemprop="url" content="{$product.link}">

  {block name='main_product_details'}
    <div class="main-product-details shadow-box md-bottom" id="mainProduct">
      <div class="row">
        {block name='product_left'}
          <div class="product-left col-12 col-md-5">
            <section class="product-left-content">
              {block name='page_content'}
                {block name='product_cover_thumbnails'}
                  {include file='catalog/_partials/product-cover-thumbnails.tpl'}
                {/block}

                {block name='product_flags'}
                  <div class="product-flags">
                    {foreach from=$product.flags item=flag}
                      <span class="product-flag {$flag.type}"><span>{$flag.label}</span></span>
                    {/foreach}
                  </div>
                {/block}
              {/block}
            </section>
          </div>
        {/block}

        {block name='product_right'}
          <div class="product-right col-12 col-md-7">
            <section class="product-right-content">
              {block name='page_header_container'}
                {block name='page_header'}
                  <h1 class="page-heading" itemprop="name">{block name='page_title'}{$product.name}{/block}</h1>
                {/block}
              {/block}

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

              <div class="product-information light-box-bg mb-3">
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
                        <input class="product-refresh ps-hidden-by-js" name="refresh" type="submit" value="{l s='Refresh' d='Shop.Theme.Actions'}">
                      {/block}

                    </form>
                  {/block}
                </div>
              </div><!-- /product-information -->

              {block name='product_additional_info'}
                {include file='catalog/_partials/product-additional-info.tpl'}
              {/block}

              {block name='hook_display_reassurance'}
                <div class="reassurance-hook">
                  {hook h='displayReassurance'}
                </div>
              {/block}

            </section><!-- /product-right-content -->
          </div><!-- /product-right -->
        {/block}
      </div><!-- /row -->
      <div class="js-pending-query js-product-refresh-pending-query page-loading-overlay main-product-details-loading">
        <div class="page-loading-backdrop d-flex align-items-center justify-content-center">
          <div class="uil-spin-css"><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div></div>
        </div>
      </div>
    </div><!-- /main-product-details -->
  {/block}

  {block name='main_product_bottom'}
    <div class="main-product-bottom md-bottom">
      {if isset($product_infoLayout) && $product_infoLayout == 'accordions'}
        {include file='catalog/_partials/product-bottom-accordions.tpl'}
      {elseif isset($product_infoLayout) && $product_infoLayout == 'tabs'}
        {include file='catalog/_partials/product-bottom-tabs.tpl'}
      {else}
        {include file='catalog/_partials/product-bottom-normal.tpl'}
      {/if}
    </div>
  {/block}

  {block name='product_accessories'}
    {if $accessories}
      <section class="product-accessories mb-2 clearfix">
        <h3 class="title-block">
          <span>{l s='You might also like' d='Shop.Theme.Catalog'}</span>
        </h3>

        <div class="product-list">
          <div class="product-list-wrapper clearfix grid columns-slick js-accessories-slider">
            {foreach from=$accessories item="product_accessory"}
              {block name='product_miniature'}
                {include file='catalog/_partials/miniatures/product-simple.tpl' product=$product_accessory}
              {/block}
            {/foreach}
          </div>
        </div>
      </section>
    {/if}
  {/block}

  {block name='product_footer'}
    {hook h='displayFooterProduct' product=$product category=$category}
  {/block}

  {block name='product_images_modal'}
    {include file='catalog/_partials/product-images-modal.tpl'}
  {/block}

  {block name='add_to_cart_message_modal'}
    <div class="modal fade" id="modalAddToCartMessage">
      <div class="modal-dialog" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-label="{l s='Close' d='Shop.Theme.Global'}">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">
            <div class="js-modal-content alert alert-danger mb-0"></div>
          </div>
        </div>
      </div>
    </div>
  {/block}
</section>
{/block}
