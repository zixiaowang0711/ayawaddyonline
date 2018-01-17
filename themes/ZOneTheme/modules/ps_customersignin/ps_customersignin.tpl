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
<div id="_desktop_user_info" class="js-account-source hidden-sm-down">
  <div class="user-info">
    <ul>
    {if $logged}
      <li class="customer-logged">
        <a
          class="btn-teriary account-link account"
          href="{$my_account_url}"
          title="{l s='View my customer account' d='Shop.Theme.Customeraccount'}"
          rel="nofollow"
        >
          <i class="material-icons">&#xE7FD;</i>
          <span>{$customerName}</span>
        </a>
        <a href="{$urls.actions.logout}" class="logout-link">
          <i class="fa fa-sign-out" aria-hidden="true"></i>
          <span>{l s='Sign out' d='Shop.Theme.Actions'}</span>
        </a>
        <div class="dropdown-customer-account-links hidden-sm-down">
          {include file="module:ps_customersignin/customer-dropdown-menu.tpl"}
        </div>
      </li>
    {else}
      <li><a
        href="{$my_account_url}"
        title="{l s='Log in to your customer account' d='Shop.Theme.Customeraccount'}"
        rel="nofollow"
        class="btn-teriary account-link"
      >
        <i class="material-icons">&#xE7FD;</i>
        <span>{l s='Sign in' d='Shop.Theme.Actions'}</span>
      </a></li>
    {/if}
    </ul>
  </div>
</div>