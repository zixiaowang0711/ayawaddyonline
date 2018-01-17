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
<section class="product-customization mb-4">
  {if !$configuration.is_catalog}
    <div class="">
      <h5>{l s='Product customization' d='Shop.Theme.Catalog'}</h5>
      <p><i>{l s='Don\'t forget to save your customization to be able to add to cart' d='Shop.Forms.Help'}</i></p>

      {block name='product_customization_form'}
        <form method="post" action="{$product.url}" enctype="multipart/form-data">
          <div class="mb-3 clearfix">
            {foreach from=$customizations.fields item="field"}
              <div class="product-customization-item">
                <label>{$field.label}</label>
                {if $field.type == 'text'}
                  <small class="float-right">{l s='250 char. max' d='Shop.Forms.Help'}</small>
                  <textarea placeholder="{l s='Your message here' d='Shop.Forms.Help'}" class="form-control product-message" maxlength="250" {if $field.required} required {/if} name="{$field.input_name}">{$field.text}</textarea>
                  
                  {if $field.text !== ''}
                    <h6 class="customization-message mt-2">{l s='Your customization:' d='Shop.Theme.Catalog'}
                      <span class="font-weight-normal">{$field.text}</span>
                    </h6>
                  {/if}
                {elseif $field.type == 'image'}
                  {if $field.is_customized}
                    <div class="field-image-uploaded mb-2">
                      <img src="{$field.image.small.url}" class="img-thumbnail" alt="{$field.label}">
                      <a class="remove-image" href="{$field.remove_image_url}" rel="nofollow">
                        <i class="material-icons">&#xE872;</i>{l s='Remove' d='Shop.Theme.Actions'}
                      </a>
                    </div>
                  {/if}
                  <div class="custom-file form-control">
                    <span class="js-file-name">{l s='No selected file' d='Shop.Forms.Help'}</span>
                    <input class="file-input js-file-input" {if $field.required} required {/if} type="file" name="{$field.input_name}">
                    <button class="btn">{l s='Choose file' d='Shop.Theme.Actions'}</button>
                  </div>
                  <small class="float-right">{l s='.png .jpg .gif' d='Shop.Forms.Help'}</small>
                {/if}
              </div>
            {/foreach}
          </div>
          <div class="clearfix">
            <button class="btn btn-info float-right" type="submit" name="submitCustomizedData">{l s='Save Customization' d='Shop.Theme.Actions'}</button>
          </div>
        </form>
      {/block}
    </div>
  {/if}
</section>
