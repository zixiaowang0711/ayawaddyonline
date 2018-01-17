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

{block name='category_miniature_item'}
  <section class="subcategory-miniature {if !$subcategory.image}no-image{/if} col-6 col-sm-4 col-md-4 col-lg-3">
    {if $subcategory.image}
    	<div class="subcategory-image">
        <a href="{$subcategory.url}">
          <img class="img-fluid img-thumbnail" src="{$subcategory.image.bySize.category_medium.url}" alt="{$subcategory.image.legend|default: $subcategory.name}">
        </a>
      </div>
    {/if}

    <h5 class="subcategory-name">
      <a href="{$subcategory.url}" class="li-a">{$subcategory.name}</a>
    </h5>

    <div class="subcategory-description">{$subcategory.description nofilter}</div>
  </section>
{/block}
