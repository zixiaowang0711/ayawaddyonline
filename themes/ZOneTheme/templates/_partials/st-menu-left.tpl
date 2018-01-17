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

<div class="st-menu st-effect-left js-sidebar-navigation-enabled">
  <div class="st-menu-close d-flex"><i class="material-icons">&#xE5CD;</i></div>
  <div class="st-menu-title">
    <h4>{l s='Menu' d='Shop.Zonetheme'}</h4>
  </div>
  <div id="js-search-sidebar" class="sidebar-search js-hidden"></div>
  <div id="js-menu-sidebar" class="sidebar-menu">
    {hook h='displaySidebarNavigation'}
  </div>
  <div id="js-header-phone-sidebar" class="sidebar-header-phone js-hidden"></div>
  <div id="js-account-sidebar" class="sidebar-account js-hidden"></div>
  <div id="js-language-sidebar" class="sidebar-language js-hidden"></div>
  <div id="js-left-currency-sidebar" class="sidebar-currency js-hidden"></div>
</div>
