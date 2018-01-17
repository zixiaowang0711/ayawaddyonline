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
<div id="js-active-search-filters">
{if $activeFilters|count}
<section class="active-filters md-bottom box-bg">
  <div class="active-search-wrapper">
    {block name='active_filters_title'}
      <h6 class="active-filter-title">{l s='Active filters' d='Shop.Theme.Global'}</h6>
    {/block}

    <ul class="active-filter-list">
      {foreach from=$activeFilters item="filter"}
        {block name='active_filters_item'}
          <li class="filter-block">
            <span>{l s='%1$s: ' d='Shop.Theme.Catalog' sprintf=[$filter.facetLabel]} <strong>{$filter.label}</strong></span>
            <a class="js-search-link" href="{$filter.nextEncodedFacetsURL}"><i class="material-icons">&#xE5CD;</i></a>
          </li>
        {/block}
      {/foreach}
    </ul>
  </div>
</section>
{/if}
</div>
