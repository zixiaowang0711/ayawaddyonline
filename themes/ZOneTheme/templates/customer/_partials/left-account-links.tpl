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
{block name='left_account_links'}
  <div class="custommer-account-links column-block md-bottom hidden-sm-down">
    <h4 class="column-title">
      <a href="{$urls.pages.my_account}" rel="nofollow">
        {l s='Your account' d='Shop.Theme.Customeraccount'}
      </a>
    </h4>
    <div class="account-list">
      <ul class="linklist">
        <li>
          <a href="{$urls.pages.identity}">{l s='Information' d='Shop.Theme.Customeraccount'}</a>
        </li>
        <li>
          <a href="{$urls.pages.addresses}">{l s='Addresses' d='Shop.Theme.Customeraccount'}</a>
        </li>
        {if !$configuration.is_catalog}
          <li>
            <a href="{$urls.pages.history}">{l s='Orders' d='Shop.Theme.Customeraccount'}</a>
          </li>
        {/if}
        {if !$configuration.is_catalog}
          <li>
            <a href="{$urls.pages.order_slip}">{l s='Credit slips' d='Shop.Theme.Customeraccount'}</a>
          </li>
        {/if}
        {if $configuration.voucher_enabled && !$configuration.is_catalog}
          <li>
            <a href="{$urls.pages.discount}">{l s='Vouchers' d='Shop.Theme.Customeraccount'}</a>
          </li>
        {/if}
        {if $configuration.return_enabled && !$configuration.is_catalog}
          <li>
            <a href="{$urls.pages.order_follow}">{l s='Returns' d='Shop.Theme.Customeraccount'}</a>
          </li>
        {/if}

        {hook h='displayMyAccountBlock'}
        
        <li>
          <a class="logout" href="{$urls.actions.logout}" rel="nofollow">
            <i class="fa fa-sign-out" aria-hidden="true"></i>
            <span>{l s='Sign out' d='Shop.Theme.Actions'}</span>
          </a>
        </li>
      </ul>
    </div>
  </div>
{/block}