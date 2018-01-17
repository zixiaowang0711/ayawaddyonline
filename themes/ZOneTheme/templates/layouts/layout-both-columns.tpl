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
<!doctype html>
<html lang="{$language.iso_code}">
  <head>
    {block name='head'}
      {include file='_partials/head.tpl'}
    {/block}
  </head>

  <body id="{$page.page_name}" class="{$page.body_classes|classnames} st-wrapper {if $configuration.is_catalog}catalog-mode{/if} {if isset($zoneDisableBorderRadius) && $zoneDisableBorderRadius}remove-border-radius{/if} {if isset($zoneDisableBoxShadow) && $zoneDisableBoxShadow}remove-box-shadow{/if} {if isset($productBuyNewLine) && $productBuyNewLine}small-style{/if} {if isset($customer.is_logged) && $customer.is_logged}customer-logged{/if}">

    {block name='hook_after_body_opening_tag'}
      {hook h='displayAfterBodyOpeningTag'}
    {/block}

    {include file="_partials/st-menu-left.tpl"}

    <main id="page" class="st-pusher {if isset($zoneLayout) && $zoneLayout == 'boxed'}boxed-layout{/if} {if isset($zoneProgressBar) && $zoneProgressBar}js-page-progress-bar{/if}">

      {block name='product_activation'}
        {include file='catalog/_partials/product-activation.tpl'}
      {/block}

      <header id="header">
        {block name='header'}
          {include file='_partials/header.tpl'}
        {/block}
      </header>

      <section id="wrapper" class="{if isset($zoneBackgroundTitle) && $zoneBackgroundTitle}background-for-title background-for-tab-title background-for-column-title{/if}">

        {block name='notifications'}
          {include file='_partials/notifications.tpl'}
        {/block}

        {hook h="displayWrapperTop"}

        {block name='top_content'}{/block}

        {block name='main_content'}
          <div class="main-content">
            <div class="container">
              <div class="row">

                {block name='left_column'}
                  <div id="left-column" class="column col-12 col-md-4 col-lg-3">
                    <div class="column-wrapper">
                      {hook h="displayLeftColumn"}
                    </div>
                  </div>
                {/block}

                {block name='content_wrapper'}
                  <div id="center-column" class="center col-12 col-md-8 col-lg-9">
                    <div class="center-wrapper">
                      {hook h="displayContentWrapperTop"}
                      
                      {block name='content'}
                        <p>Hello world! This is HTML5 Boilerplate.</p>
                      {/block}

                      {hook h="displayContentWrapperBottom"}
                    </div>
                  </div>
                {/block}

                {block name='right_column'}
                  <div id="right-column" class="column col-12 col-md-4 col-lg-3">
                    <div class="column-wrapper">
                      {hook h="displayRightColumn"}
                    </div>
                  </div>
                {/block}
                  
              </div><!-- /row -->
            </div><!-- /container -->
          </div><!-- /main-content -->
        {/block}

        {block name='bottom-content'}{/block}

        {hook h="displayWrapperBottom"}

      </section><!-- /wrapper -->

      <footer id="footer">
        {block name="footer"}
          {include file="_partials/footer.tpl"}
        {/block}
      </footer>

    </main>

    {block name='hook_outside_main_page'}
      {hook h='displayOutsideMainPage'}
    {/block}

    {include file="_partials/st-menu-right.tpl"}

    <div class="st-overlay" id="st_overlay"></div>

    {block name='external_html'}{/block}

    {block name='javascript_bottom'}
      {include file="_partials/javascript.tpl" javascript=$javascript.bottom}
    {/block}

    {block name='hook_before_body_closing_tag'}
      {hook h='displayBeforeBodyClosingTag'}
    {/block}

  </body>
</html>
