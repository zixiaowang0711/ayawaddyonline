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
<div id="_desktop_language_selector">
  <div class="language-selector dropdown js-dropdown">
    <div class="desktop-dropdown">
      <a class="dropdown-current expand-more" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" aria-label="{l s='Language dropdown' d='Shop.Theme.Global'}">
        <span><img src="{$urls.img_lang_url}{$current_language.id_lang}.jpg" alt="{$current_language.name_simple}" width="16" height="11"></span>
        <span>&nbsp;&nbsp;{$current_language.name_simple}</span>
        <span class="dropdown-icon"><span class="expand-icon"></span></span>
      </a>
      <div class="dropdown-menu js-language-source" aria-labelledby="language-selector-label">
        <ul class="language-list">
          {foreach from=$languages item=language}
            <li {if $language.id_lang == $current_language.id_lang} class="current" {/if}>
              <a href="{url entity='language' id=$language.id_lang}" title="{$language.name}" class="dropdown-item">
                <span class="l-name"><img src="{$urls.img_lang_url}{$language.id_lang}.jpg" alt="{$language.name_simple}" width="16" height="11">
                &nbsp;{$language.name_simple}</span>
                <span class="l-code">{$language.iso_code}</span>
              </a>
            </li>
          {/foreach}
        </ul>
      </div>
    </div>
  </div>
</div>
