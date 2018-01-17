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
{extends file='customer/page.tpl'}

{block name='page_title'}
  {l s='Return details' d='Shop.Theme.Customeraccount'}
{/block}

{block name='page_content'}
  {block name='order_return_infos'}
    <div id="order-return-infos" class="md-bottom">
      <div class="sm-bottom light-box-bg">
        <p><strong>
          {l
            s='%number% on %date%'
            d='Shop.Theme.Customeraccount'
            sprintf=['%number%' => $return.return_number, '%date%' => $return.return_date]
          }
        </strong></p>
        <p>{l s='We have logged your return request.' d='Shop.Theme.Customeraccount'}</p>
        <p>
          {l
            s='Your package must be returned to us within %number% days of receiving your order.'
            d='Shop.Theme.Customeraccount'
            sprintf=['%number%' => $configuration.number_of_days_for_return]
          }
        </p>
        <p>
          {l
            s='The current status of your merchandise return is: [1] %status% [/1]'
            d='Shop.Theme.Customeraccount'
            sprintf=[
              '[1]' => '<strong>',
              '[/1]' => '</strong>',
              '%status%' => $return.state_name
            ]
          }
        </p>
      </div>

      <h5>{l s='List of items to be returned:' d='Shop.Theme.Customeraccount'}</h5>
      <table class="table table-bordered">
        <thead class="thead-default">
          <tr>
            <th>{l s='Product' d='Shop.Theme.Catalog'}</th>
            <th>{l s='Quantity' d='Shop.Theme.Catalog'}</th>
          </tr>
        </thead>
        <tbody>
        {foreach from=$products item=product}
          <tr>
            <td>
              <strong>{$product.product_name}</strong>
              {if $product.product_reference}
                <br />
                {l s='Reference' d='Shop.Theme.Catalog'}: {$product.product_reference}
              {/if}
              {if $product.customizations}
                {foreach from=$product.customizations item="customization"}
                  <div class="customization">
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
            </td>
            <td>
              {if $product.customizations}
                {foreach $product.customizations as $customization}
                  {$customization.quantity}
                {/foreach}
              {else}
                {$product.product_quantity}
              {/if}
            </td>
          </tr>
        {/foreach}
        </tbody>
      </table>
    </div>
  {/block}

  {if $return.state == 2}
    <section class="order-return-reminder">
      <div class="light-box-bg">
        <h5>{l s='Reminder' d='Shop.Theme.Customeraccount'}</h5>
        <p>
          {l
            s='All merchandise must be returned in its original packaging and in its original state.'
            d='Shop.Theme.Customeraccount'
          }
          <br>
          {l
            s='Please print out the [1]returns form[/1] and include it with your package.'
            d='Shop.Theme.Customeraccount'
            sprintf=[
              '[1]' => '<a href="'|cat:$return.print_url|cat:'">',
              '[/1]' => '</a>'
            ]
          }
          <br>
          {l
            s='Please check the [1]returns form[/1] for the correct address.'
            d='Shop.Theme.Customeraccount'
            sprintf=[
              '[1]' => '<a href="'|cat:$return.print_url|cat:'">',
              '[/1]' => '</a>'
            ]
          }
        </p>
        <p>
          {l
            s='When we receive your package, we will notify you by email. We will then begin processing order reimbursement.'
            d='Shop.Theme.Customeraccount'
          }
          <br>
          <a href="{$urls.pages.contact}">
            {l
              s='Please let us know if you have any questions.'
              d='Shop.Theme.Customeraccount'
            }
          </a>
          <br>
          {l
            s='If the conditions of return listed above are not respected, we reserve the right to refuse your package and/or reimbursement.'
            d='Shop.Theme.Customeraccount'
          }
        </p>
      </div>
    </section>
  {/if}
{/block}
