{*
* 2007-2016 PrestaShop
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
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

<div class="collapse-icons js-collapse-trigger">
  <i class="material-icons add">&#xE145;</i>
  <i class="material-icons remove">&#xE15B;</i>
</div>
<div class="category-sub-menu js-sub-categories">
<ul>
  {foreach from=$submenus item=submenu}
  <li class="sb-m{$submenu.id}">
    <a href="{$submenu.link}" title="{$submenu.name}">{$submenu.name}</a>
    {if isset($submenu.children) && $submenu.children}
      {include file="module:zonethememanager/views/templates/hook/sub_category_tree.tpl" submenus=$submenu.children}
    {/if}
  </li>
  {/foreach}
</ul>
</div>


