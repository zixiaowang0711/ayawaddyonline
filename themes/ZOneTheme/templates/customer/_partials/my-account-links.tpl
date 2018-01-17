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
{block name='my_account_links'}
  <ul class="footer-account-links box-bg">
    <li class="back-to-account-link">
      <a href="{$urls.pages.my_account}">
        <i class="material-icons">&#xE7FD;</i>
        <span>{l s='Back to your account' d='Shop.Theme.Customeraccount'}</span>
      </a>
    </li>
    <li class="logout-link">
      <a href="{$urls.actions.logout}">
        <i class="fa fa-sign-out" aria-hidden="true"></i>
        <span>{l s='Sign out' d='Shop.Theme.Actions'}</span>
      </a>
    </li>
  </ul>
{/block}
