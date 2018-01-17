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
{block name='header_banner'}
  <div class="header-banner clearfix">
    {hook h='displayBanner'}
  </div>
{/block}

{block name='header_nav'}
  <div class="header-nav clearfix {if isset($zoneNavigation) && ($zoneNavigation == 'top' || $zoneNavigation == 'sidebar_top')}hidden-sm-down{else}hidden-xs-up{/if}">
    <div class="container">
      <div class="header-nav-wrapper d-flex align-items-center">
        {hook h='displayNav1'}
      </div>
    </div>
  </div>
{/block}

{block name='main_header'}
  <div class="main-header clearfix hidden-sm-down">
    <div class="container">
      <div class="header-wrapper d-md-flex align-items-md-center">

        {block name='header_logo'}
          <div class="header-logo" id="_desktop_header_logo">
            <a class="logo" href="{$urls.base_url}" title="{$shop.name}">
              {if isset($svgLogo) && $svgLogo}
                {$svgLogo nofilter}
              {else}
                <img src="{$shop.logo}" alt="{$shop.name}">
              {/if}
            </a>
          </div>
        {/block}

        {block name='header_right'}
          <div class="header-right hidden-sm-down">
            <div class="display-top align-items-center d-flex flex-lg-nowrap flex-md-wrap justify-content-end justify-content-lg-center">
              {hook h='displayTop'}
            </div>
          </div>
        {/block}

      </div>
    </div>
  </div>
{/block}

{block name='main_mobile_header'}
  <div class="main-header main-mobile-header hidden-md-up">
    {block name='header_mobile_top'}
      <div class="header-mobile-top">
        <div class="container">
          <div class="header-mobile-top-wrapper d-flex align-items-center">
            <div id="_mobile_language_selector"></div>
            <div id="_mobile_currency_selector"></div>
            <div id="_mobile_user_info" class="mobile-user-info"></div>
          </div>
        </div>
      </div>
    {/block}

    {block name='header_logo'}
      <div class="header-mobile-logo">
        <div class="container">
          <div class="header-logo" id="_mobile_header_logo"></div>
        </div>
      </div>
    {/block}

    {block name="header_mobile_bottom"}
      <div class="header-mobile-bottom {if isset($zoneStickyMobile) && $zoneStickyMobile}js-mobile-sticky{/if}" id="header-mobile-bottom">
        <div class="container">
          <div class="header-mobile-bottom-wrapper d-flex align-items-center">
            <div id="_mobile_left_nav_trigger" class="mobile-left-nav-trigger"></div>
            <div class="mobile-menu hidden-xs-up">
              <div id="mobile-menu-icon" class="mobile-menu-icon d-flex align-items-center justify-content-center">
                <i class="material-icons">&#xE871;</i>
              </div>
              <div id="dropdown-mobile-menu" class="mobile-menu-content">
                {hook h='displayMobileMenu'}
              </div>
            </div>
            <div id="_mobile_search_widget" class="mobile-search-widget"></div>
            <div id="_mobile_cart" class="mobile-cart"></div>
          </div>
        </div>
      </div>
    {/block}
  </div>
{/block}

{block name='header-bottom'}
  <div class="header-bottom clearfix">
    <div class="header-main-menu {if isset($zoneStickyMenu) && $zoneStickyMenu}js-sticky-menu{/if}" id="header-main-menu">
      <div class="container">
        <div class="header-main-menu-wrapper">
          <div id="_desktop_left_nav_trigger" class="{if isset($zoneNavigation) && ($zoneNavigation == 'sidebar' || $zoneNavigation == 'sidebar_top')}hidden-sm-down{else}hidden-xs-up{/if}">
            <div id="js-left-nav-trigger" class="left-nav-trigger">
              <div class="left-nav-icon d-flex align-items-center justify-content-center">
                <i class="material-icons">&#xE8EE;</i>
              </div>
            </div>
          </div>
          {hook h='displayNavFullWidth'}
          <div class="sticky-icon-cart js-sticky-icon-cart js-sidebar-cart-trigger"></div>
        </div>
      </div>
    </div>

    {block name='breadcrumb'}
      {include file='_partials/breadcrumb.tpl'}
    {/block}
  </div>
{/block}
