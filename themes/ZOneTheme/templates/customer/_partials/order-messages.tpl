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
{block name='order_messages_table'}
  {if $order.messages}
  <div class="sm-bottom">
    <h4>{l s='Messages' d='Shop.Theme.Customeraccount'}</h4>
    <table class="table table-bordered">
      <thead class="thead-default">
        <tr>
          <th>{l s='From' d='Shop.Forms.Labels'}</th>
          <th>{l s='Message' d='Shop.Forms.Labels'}</th>
        </tr>
      </thead>
      <tbody>
      {foreach from=$order.messages item=message}
        <tr>
          <td>
            {$message.name}<br>
            {$message.message_date}
          </td>
          <td>{$message.message nofilter}</td>
        </tr>
      {/foreach}
      </tbody>
    </table>
  </div>
  {/if}
{/block}

{block name='order_message_form'}
  <section class="order-message-form box">
    <form action="{$urls.pages.order_detail}" method="post">

      <header>
        <h4>{l s='Add a message' d='Shop.Theme.Customeraccount'}</h4>
        <p>{l s='If you would like to add a comment about your order, please write it in the field below.' d='Shop.Theme.Customeraccount'}</p>
      </header>

      <section class="form-fields">

        <div class="form-group row">
          <label class="col-md-3 form-control-label">{l s='Product' d='Shop.Forms.Labels'}</label>
          <div class="col-md-5">
            <select name="id_product" class="form-control form-control-select">
              <option value="0">{l s='-- please choose --' d='Shop.Forms.Labels'}</option>
              {foreach from=$order.products item=product}
                <option value="{$product.id_product}">{$product.name}</option>
              {/foreach}
            </select>
          </div>
        </div>

        <div class="form-group row">
          <label class="col-md-3 form-control-label"></label>
          <div class="col-md-9">
            <textarea rows="3" name="msgText" class="form-control"></textarea>
          </div>
        </div>

      </section>

      <div class="form-footer row">
        <input type="hidden" name="id_order" value="{$order.details.id}">
        <div class="col-md-9 offset-md-3">
          <button type="submit" name="submitMessage" class="btn btn-primary form-control-submit">
            {l s='Send' d='Shop.Theme.Actions'}
          </button>
        </div>
      </div>

    </form>
  </section>
{/block}
