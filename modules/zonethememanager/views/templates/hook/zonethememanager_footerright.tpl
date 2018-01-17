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

<div class="footer-menu js-toggle-linklist-mobile">
{if $cmsLinks || $staticLinks}
<div class="row">
  {if $cmsLinks || $pageLinks }
    <div class="col-12 col-lg-4 linklist">
      <h4>{l s='Information' d='Shop.Zonetheme'}</h4>
      <ul>
      {foreach from=$cmsLinks item=page}
        <li><a href="{$page.link}" title="{$page.title}">{$page.title}</a></li>
      {/foreach}
      {foreach from=$pageLinks item=page}
        <li>
        {if $page.id == 'stores'}
          <a href="{$page.link}" title="{l s='Our stores' d='Shop.Theme.Global'}">{l s='Our stores' d='Shop.Theme.Global'}</a>
        {elseif $page.id == 'prices-drop'}
          <a href="{$page.link}" title="{l s='Price drop' d='Shop.Theme.Catalog'}">{l s='Price drop' d='Shop.Theme.Catalog'}</a>
        {elseif $page.id == 'new-products'}
          <a href="{$page.link}" title="{l s='New products' d='Shop.Theme.Catalog'}">{l s='New products' d='Shop.Theme.Catalog'}</a>
        {elseif $page.id == 'best-sales'}
          <a href="{$page.link}" title="{l s='Best sellers' d='Shop.Theme.Catalog'}">{l s='Best sellers' d='Shop.Theme.Catalog'}</a>
        {elseif $page.id == 'contact'}
          <a href="{$page.link}" title="{l s='Contact us' d='Shop.Theme.Global'}">{l s='Contact us' d='Shop.Theme.Global'}</a>
        {elseif $page.id == 'sitemap'}
          <a href="{$page.link}" title="{l s='Sitemap' d='Shop.Theme.Global'}">{l s='Sitemap' d='Shop.Theme.Global'}</a>
        {/if}
        </li>
      {/foreach}
      </ul>
    </div>
  {/if}
  {if $staticLinks}
    <div class="col-12 col-lg-8 linklist">
      {$staticLinks nofilter}
    </div>
  {/if}
</div>
{/if}
</div>
