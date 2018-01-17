{**
 * 2007-2017 PrestaShop
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
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
 * @copyright 2007-2017 PrestaShop SA
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * International Registered Trademark & Property of PrestaShop SA
 *}
<div class="d-flex flex-wrap">
  <label class="form-control-label hidden-sm-down sort-label">{l s='Sort by:' d='Shop.Theme.Global'}</label>
  <div class="sort-select dropdown js-dropdown">
    <a
      class="custom-select select-title"
      rel="nofollow"
      data-toggle="dropdown"
      aria-haspopup="true"
      aria-expanded="false"
    >
      {if isset($listing.sort_selected)}{$listing.sort_selected}{else}{l s='Select' d='Shop.Theme.Actions'}{/if}
    </a>
    <div class="dropdown-menu">
      {foreach from=$listing.sort_orders item=sort_order}
        <a
          rel="nofollow"
          href="{$sort_order.url}"
          class="dropdown-item {['current' => $sort_order.current, 'js-search-link' => true]|classnames}"
        >
          {$sort_order.label}
        </a>
      {/foreach}
    </div>
  </div>

  {if !isset($cat_productView)}
    {assign var='cat_productView' value='grid'}
  {/if}

  <div class="product-display hidden-sm-down">
    <div class="d-flex">
      <label class="form-control-label display-label">{l s='View' d='Shop.Zonetheme'}</label>
      <ul class="display-select" id="product_display_control">
        <li class="d-flex">
          <a data-view="grid" {if $cat_productView == 'grid'}class="selected"{/if} rel="nofollow" href="#grid" title="{l s='Grid' d='Shop.Zonetheme'}" data-toggle="tooltip" data-placement="top">
            <i class="material-icons">&#xE42A;</i>
          </a>
          <a data-view="list" {if $cat_productView == 'list'}class="selected"{/if} rel="nofollow" href="#list" title="{l s='List' d='Shop.Zonetheme'}" data-toggle="tooltip" data-placement="top">
            <i class="material-icons">&#xE8EF;</i>
          </a>
          <a data-view="table-view" {if $cat_productView == 'table'}class="selected"{/if} rel="nofollow" href="#table" title="{l s='Table' d='Shop.Zonetheme'}" data-toggle="tooltip" data-placement="top">
            <i class="material-icons">&#xE8EE;</i>
          </a>
        </li>
      </ul>
    </div>
  </div>
</div>
