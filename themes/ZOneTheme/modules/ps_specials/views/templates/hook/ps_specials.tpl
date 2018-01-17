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

<section class="specials-products">
  <div class="block clearfix">

    <div class="title-block d-flex flex-wrap">
      <span>{l s='On sale' d='Modules.Specials.Shop'}</span>
      <span class="view-all-link">
        <a href="{$allSpecialProductsLink}">{l s='All sale products' d='Modules.Specials.Shop'} <i class="material-icons">&#xE8E4;</i></a>
      </span>
    </div>
    
    <div class="product-list shown-index">
      <div class="product-list-wrapper clearfix grid columns-5">
        {foreach from=$products item="product"}
          {include file='catalog/_partials/miniatures/product.tpl' product=$product}
        {/foreach}
      </div>
    </div>
    
    <div class="product-list hidden-index">
      <div class="product-list-wrapper clearfix grid columns-5">
        {foreach from=$products item="product"}
          {include file='catalog/_partials/miniatures/product-simple.tpl' product=$product}
        {/foreach}
      </div>
    </div>

  </div>
</section>