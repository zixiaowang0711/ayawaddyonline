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
<div id="order-items" class="col-12 col-md-8">
  {block name='order_items_table_head'}
    <h4>{l s='Order items' d='Shop.Theme.Checkout'}</h4>
  {/block}

  <div class="order-confirmation-table">
    {block name='order_confirmation_table'}
      {foreach from=$products item=product}
        <div class="order-line">
          <div class="row align-items-center">
            <div class="col-md-2 col-3 order-line-left">
              <span class="image">
                <img class="img-fluid" src="{$product.cover.medium.url}" />
              </span>
            </div>
            <div class="col-md-10 col-9 order-line-right">
              <div class="row">
                <div class="col-lg-6 col-12 details">
                  {if $add_product_link}<a href="{$product.url}" target="_blank">{/if}
                    <span class="product-name">{$product.name}</span>
                  {if $add_product_link}</a>{/if}
                  {if $product.customizations|count}
                    {foreach from=$product.customizations item="customization"}
                      <div class="customizations">
                        <a href="#" data-toggle="modal" data-target="#product-customizations-modal-{$customization.id_customization}">{l s='Product customization' d='Shop.Theme.Catalog'}</a>
                      </div>
                      <div class="modal fade customization-modal" id="product-customizations-modal-{$customization.id_customization}" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                          <div class="modal-content">
                            <div class="modal-header">
                              <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                              </button>
                              <h4 class="modal-title">{l s='Product customization' d='Shop.Theme.Catalog'}</h4>
                            </div>
                            <div class="modal-body">
                              {foreach from=$customization.fields item="field"}
                                <div class="product-customization-line row">
                                  <div class="col-sm-3 col-4 label">
                                    {$field.label}
                                  </div>
                                  <div class="col-sm-9 col-8 value">
                                    {if $field.type == 'text'}
                                      {if (int)$field.id_module}
                                        {$field.text nofilter}
                                      {else}
                                        {$field.text}
                                      {/if}
                                    {elseif $field.type == 'image'}
                                      <img src="{$field.image.small.url}">
                                    {/if}
                                  </div>
                                </div>
                              {/foreach}
                            </div>
                          </div>
                        </div>
                      </div>
                    {/foreach}
                  {/if}
                  {hook h='displayProductPriceBlock' product=$product type="unit_price"}
                </div>
                <div class="col-lg-6 col-12 qty">
                  <div class="row">
                    <div class="col-5 text-lg-right text-left">{$product.price}</div>
                    <div class="col-2 text-right">{$product.quantity}</div>
                    <div class="col-5 text-right font-weight-bold">{$product.total}</div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      {/foreach}

      <div class="order-confirmation-total">
        <div class="row">
          {foreach $subtotals as $subtotal}
            {if $subtotal.type !== 'tax' && isset($subtotal.value)}
              <div class="col-6"><label>{$subtotal.label}</label></div>
              <div class="col-6 text-right">{$subtotal.value}</div>
            {/if}
          {/foreach}

          {if $subtotals.tax.label !== null}
            <div class="col-6"><label>{$subtotals.tax.label}</label></div>
            <div class="col-6 text-right">{$subtotals.tax.value}</div>
          {/if}

          <div class="col-6"><label class="text-uppercase">{$totals.total.label}</label> {$labels.tax_short}</div>
          <div class="col-6 text-right"><span class="price price-total">{$totals.total.value}</span></div>
        </div>
      </div>
    {/block}
  </div>
</div>
