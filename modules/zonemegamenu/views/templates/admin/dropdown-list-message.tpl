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

<div class="panel col-lg-12">
  <div class="panel-heading">{l s='Dropdown Contents' d='Modules.ZoneMegamenu.Admin'}</div>
  <div class="table-responsive-row clearfix">
    {if $msg_type == 'enable_column'}
      {l s='You have to ENABLE the "Dropdown Menu Columns" option.' d='Modules.ZoneMegamenu.Admin'}
    {elseif $msg_type == 'save_menu'}
      {l s='You have to SAVE this menu before adding a dropdown content.' d='Modules.ZoneMegamenu.Admin'}
    {/if}
  </div>
</div>
