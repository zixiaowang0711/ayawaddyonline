{*
* 2007-2017 PrestaShop
*
* NOTICE OF LICENSE
*
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2017 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="block-newsletter js-emailsubscription">
  <h4>{l s='Newsletter' d='Modules.Emailsubscription.Shop'}</h4>

  <form action="{$urls.pages.index}#footer" method="post" class="js-subscription-form">
    <div class="newsletter-form">
      <div class="input-wrapper">
        <input
          name="email"
          type="text"
          value="{$value}"
          class="form-control"
          placeholder="{l s='Your email address' d='Shop.Forms.Labels'}"
        >
        <span class="input-btn">
          <button type="submit" name="submitNewsletter" class="btn btn-primary hidden-md-down">
            <i class="fa fa-envelope-o"></i> {l s='Subscribe' d='Shop.Theme.Actions'}
          </button>
          <button type="submit" name="submitNewsletter" class="btn btn-primary hidden-lg-up">
            <i class="fa fa-envelope-o"></i> {l s='OK' d='Shop.Theme.Actions'}
          </button>
        </span>
      </div>
      <input type="hidden" name="action" value="0">
    </div>

    <div class="newsletter-message">
      {if $msg}
        <p class="alert {if $nw_error}alert-danger{else}alert-success{/if}">
          {$msg}
        </p>
      {else}
        {if $conditions}
          <p class="conditons">{$conditions}</p>
        {/if}
      {/if}
    </div>
  </form>
</div>
