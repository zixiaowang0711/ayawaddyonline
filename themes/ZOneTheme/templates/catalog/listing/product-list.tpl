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
{extends file=$layout}

{block name='content'}
  <section id="main">

    {block name='product_list_header'}
      <h1 class="page-heading">{$listing.label}</h1>
    {/block}

    <section id="products">
      {if $listing.products|count}

        {block name='product_list_top'}
          {include file='catalog/_partials/products-top.tpl' listing=$listing}
        {/block}

        {block name='mobile_search_filter'}
          <div id="_mobile_search_filters" class="mobile-search-fillter light-box-bg hidden-md-up md-bottom"></div>
        {/block}
        
        {block name='product_list_active_filters'}
          {$listing.rendered_active_filters nofilter}
        {/block}

        {block name='product_list'}
	        {include file='catalog/_partials/products.tpl' listing=$listing}
	      {/block}

        {block name='product_list_bottom'}
          {include file='catalog/_partials/products-bottom.tpl' listing=$listing}
        {/block}

      {else}

        {include file='errors/not-found.tpl'}

      {/if}
    </section>

  </section>
{/block}

{block name='external_html'}
  <div class="js-pending-query page-loading-overlay">
    <div class="page-loading-backdrop d-flex align-items-center justify-content-center">
      <div class="uil-spin-css"><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div><div><div></div></div></div>
    </div>
  </div>
{/block}
