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
{block name='footer_before'}
  <div class="footer-top clearfix">
    {block name='hook_footer_before'}
      <div class="container">
        {hook h='displayFooterBefore'}
      </div>
    {/block}
  </div>
{/block}

{block name='footer_main'}
  <div class="footer-main clearfix">
    <div class="container">
      <div class="row">
        {block name='hook_footer_left'}
          <div class="footer-left col-sm-12 col-md-6 col-lg-4">
            {hook h='displayFooterLeft'}
          </div>
        {/block}
        {block name='hook_footer_right'}
          <div class="footer-right col-sm-12 col-md-6 col-lg-8">
            {hook h='displayFooterRight'}
          </div>
        {/block}
      </div>

      {block name='hook_footer'}
        <div class="row hook-display-footer">
          {hook h='displayFooter'}
        </div>
      {/block}
    </div>
  </div>
{/block}

{block name='footer_after'}
  <div class="footer-bottom clearfix">
    {block name='hook_footer_after'}
      <div class="container">
        {hook h='displayFooterAfter'}
      </div>
    {/block}
  </div>
{/block}
