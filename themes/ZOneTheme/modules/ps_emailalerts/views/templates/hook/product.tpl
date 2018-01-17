{*
* 2007-2015 PrestaShop
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
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
* International Registered Trademark & Property of PrestaShop SA
*}

<div class="mailalert-product-page js-mailalert sm-bottom" data-url="{url entity='module' name='ps_emailalerts' controller='actions' params=['process' => 'add']}">
  <input type="hidden" value="{$id_product}" />
  <input type="hidden" value="{$id_product_attribute}" />
  <div class="mailalert-form js-mailalert-form row">
    {if isset($email) AND $email}
      <div class="col-12 col-lg-6">
        <input class="form-control" type="email" placeholder="{l s='your@email.com' d='Modules.Mailalerts.Shop'}"/>
      </div>
    {/if}
    <div class="col-12 col-lg-6">
      <a class="btn btn-teriary" href="#" rel="nofollow" onclick="return addNotification();">{l s='Notify me when available' d='Modules.Mailalerts.Shop'}</a>
    </div>
  </div>
  <div class="alert mailalert-msg" style="display: none;"></div>
</div>
