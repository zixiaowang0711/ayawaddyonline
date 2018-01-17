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

{$alert nofilter}

<div class="row">
  <div class="left-panel col-lg-2 col-md-3">
    <div class="list-group">
      {foreach from=$groups item=group}
        <a class="list-group-item {if $action == $group}active{/if}" href="{$panel_href}&action={$group}">{$group}</a>
      {/foreach}
      <a class="list-group-item dark {if $action == 'custom_css'}active{/if}" href="{$panel_href}&action=custom_css">
        {l s='Custom CSS' d='Modules.ZoneColorsfonts.Admin'}
      </a>
    </div>
  </div>
  <div class="main-settings col-lg-10 col-md-9">
    {$settings_form nofilter}
  </div>
</div>

<div class="panel" style="clear: both; margin-top: 50px; max-width: 500px;">
  <h3><i class="icon icon-circle"></i> Documentation</h3>
  <p>Please read file <a href="{$doc_url}" target="_blank">documentation.pdf</a> to configure this module.</p>
</div>
