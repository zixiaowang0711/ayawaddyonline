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

{if $module_enabled}
<div class="alert alert-info">
  <p>This module use the "Subscribe Form" of the "{$module_name}" module (by PrestaShop)</p>
  <p><a href="{$module_link}" target="_blank"><i class="icon-hand-o-right"></i>
  <strong>You can find the registered emails here.</strong></a></p>
</div>
{else}
<div class="alert alert-warning">
  <p>To use this module, please install and enable "{$module_name}" module (by PrestaShop)</p>
  <p>Go to the List of module page, find the "{$module_name}" module and install it.</p>
</div>
{/if}
