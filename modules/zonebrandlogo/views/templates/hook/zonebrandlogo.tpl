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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if $brands}
<div class="aone-brands clearfix" id="aBrandLogo_{$hookName}">
  <div class="block {if $hookName == 'displayBottomColumn' || $hookName == 'displayTopColumn'}container{/if}">
    <div class="title-block">
      <span>{l s='Popular Brands' d='Shop.Zonetheme'}</span>
    </div>
    <div class="block-content">
      {if $enableSlider}
        {include file="module:zonebrandlogo/views/templates/hook/brands-slider.tpl"}
      {else}
        {include file="module:zonebrandlogo/views/templates/hook/brands-list.tpl"}
      {/if}
    </div>      
  </div>
</div>
{/if}
