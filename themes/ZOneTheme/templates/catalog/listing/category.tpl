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
{extends file='catalog/listing/product-list.tpl'}

{block name='product_list_header'}
  {if isset($cat_showImage) && $cat_showImage}
    {if $category.image}
      <div class="category-cover mb-4">
        <img class="img-fluid" src="{$category.image.bySize.category_default.url}" alt="{$category.image.legend|default: $category.name}">
      </div>
    {/if}
  {/if}

  <h1 class="page-heading">{$category.name}</h1>
  
  {if isset($cat_showDescription) && $cat_showDescription}
    {if $category.description}
      <div class="category-description mb-4">{$category.description nofilter}</div>
    {/if}
  {/if}

  {block name='category_subcategories'}
    {if isset($cat_showSubcategories) && $cat_showSubcategories}
      {if $subcategories|count}
        <aside class="subcategories mb-2">
          <h3 class="page-subheading">{l s='Subcategories' d='Shop.Zonetheme'}</h3>
          <div class="subcategories-wrapper row">
            {foreach from=$subcategories item="subcategory"}
              {block name='category_miniature'}
                {include file='catalog/_partials/miniatures/subcategory.tpl' subcategory=$subcategory}
              {/block}
            {/foreach}
          </div>
        </aside>
      {/if}
    {/if}
  {/block}
{/block}
