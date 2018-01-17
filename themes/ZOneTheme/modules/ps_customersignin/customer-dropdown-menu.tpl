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
<ul class="dropdown-menu">
  <li>
    <a href="{$urls.pages.identity}" class="dropdown-item">
      <i class="material-icons">&#xE853;</i>
      <span>{l s='Information' d='Shop.Theme.Customeraccount'}</span>
    </a>
  </li>
  <li>
    <a href="{$urls.pages.addresses}" class="dropdown-item">
      <i class="material-icons">&#xE56A;</i>
      <span>{l s='Addresses' d='Shop.Theme.Customeraccount'}</span>
    </a>
  </li>
  {if !$configuration.is_catalog}
  <li>
    <a href="{$urls.pages.history}" class="dropdown-item">
      <i class="material-icons">&#xE916;</i>
      <span>{l s='Orders' d='Shop.Zonetheme'}</span>
    </a>
  </li>
  {/if}
  {if !$configuration.is_catalog}
  <li>
    <a href="{$urls.pages.order_slip}" class="dropdown-item">
      <i class="material-icons">&#xE8B0;</i>
      <span>{l s='Credit slips' d='Shop.Theme.Customeraccount'}</span>
    </a>
  </li>
  {/if}
  {if $configuration.voucher_enabled && !$configuration.is_catalog}
  <li>
    <a href="{$urls.pages.discount}" class="dropdown-item">
      <i class="material-icons">&#xE54E;</i>
      <span>{l s='Vouchers' d='Shop.Theme.Customeraccount'}</span>
    </a>
  </li>
  {/if}
  {if $configuration.return_enabled && !$configuration.is_catalog}
  <li>
    <a href="{$urls.pages.order_follow}" class="dropdown-item">
      <i class="material-icons">&#xE860;</i>
      <span>{l s='Returns' d='Shop.Zonetheme'}</span>
    </a>
  </li>
  {/if}
  <li class="logout">
    <a href="{$urls.actions.logout}">
      <i class="fa fa-sign-out" aria-hidden="true"></i>
      <span>{l s='Sign out' d='Shop.Theme.Actions'}</span>
    </a>
  </li>
</ul>