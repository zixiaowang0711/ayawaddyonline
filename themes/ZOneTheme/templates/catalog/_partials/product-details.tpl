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
{block name='product_details'}
  <div class="product-details" id="product-details" data-product="{$product.embedded_attributes|json_encode}">
    
    <div class="js-product-attributes-source hidden-xs-up">
      {block name='product_manufacturer'}
        {if isset($product_manufacturer->id)}
          <div class="attribute-item product-manufacturer">
            <label>{l s='Brand' d='Shop.Theme.Catalog'} </label>
            <span><a href="{$product_brand_url}" class="li-a">{$product_manufacturer->name}</a></span>

            {if isset($manufacturer_image_url)}
              <div class="brand-logo">
                <a href="{$product_brand_url}">
                  <img src="{$manufacturer_image_url}" class="img-fluid" alt="{$product_manufacturer->name}" />
                </a>
              </div>
            {/if}
          </div>
        {/if}
      {/block}

      {block name='product_reference'}
        {if isset($product.reference_to_display)}
          <div class="attribute-item product-reference">
            <label>{l s='Reference' d='Shop.Theme.Catalog'} </label>
            <span itemprop="sku">{$product.reference_to_display}</span>
          </div>
        {/if}
      {/block}
      {block name='product_condition'}
        {if $product.condition}
          <div class="attribute-item product-condition">
            <label>{l s='Condition' d='Shop.Theme.Catalog'} </label>
            <link itemprop="itemCondition" href="{$product.condition.schema_url}"/>
            <span>{$product.condition.label}</span>
          </div>
        {/if}
      {/block}
      {block name='product_quantities'}
        {if $product.show_quantities}
          <div class="attribute-item product-quantities">
            <label>{l s='In stock' d='Shop.Theme.Catalog'}</label>
            <span data-stock="{$product.quantity}" data-allow-oosp="{$product.allow_oosp}">{$product.quantity} {$product.quantity_label}</span>
          </div>
        {/if}
      {/block}
      {block name='product_availability_date'}
        {if $product.availability_date}
          <div class="attribute-item product-availability-date">
            <label>{l s='Availability date:' d='Shop.Theme.Catalog'} </label>
            <span>{$product.availability_date}</span>
          </div>
        {/if}
      {/block}
      {block name='product_specific_references'}
        {if isset($product.specific_references)}
          {foreach from=$product.specific_references item=reference key=key}
            <div class="attribute-item product-specific-references {$key}">
              <label>{$key} </label>
              <span>{$reference}</span>
            </div>
          {/foreach}
        {/if}
      {/block}
    </div>

    {block name='product_features'}
      {if $product.features}
        <section class="product-features">
          <h5>{l s='Data sheet' d='Shop.Theme.Catalog'}</h5>
          <dl class="data-sheet">
            {foreach from=$product.features item=feature}
              <dt class="name">{$feature.name}</dt>
              <dd class="value">{$feature.value}</dd>
            {/foreach}
          </dl>
        </section>
      {/if}
    {/block}
  </div>
{/block}
