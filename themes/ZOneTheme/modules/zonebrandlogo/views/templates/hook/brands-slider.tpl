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

<div class="brand-list-wrapper">
  <ul class="brand-list js-brand-logo-slider" data-autoscroll="{$autoplay}">
    {foreach from=$brands item=brand name=brands}
      <li class="brand-base">
        <div class="brand-container">
          <div class="left-side">
            <div class="logo">
              <a href="{$brand.url}" title="{$brand.name}">
                <img
                  class="img-fluid"
                  src="{$brand.image}"
                  alt="{$brand.name}"
                  {if isset($imageSize)}
                    width="{$imageSize.width}"
                    height="{$imageSize.height}"
                  {/if}
                />
              </a>          
            </div>
          </div>
          {if $showName}
            <div class="middle-side">
              <a class="product-name" href="{$brand.url}">{$brand.name}</a>
            </div>
          {/if}
        </div>
      </li>
    {/foreach}
  </ul>
</div>