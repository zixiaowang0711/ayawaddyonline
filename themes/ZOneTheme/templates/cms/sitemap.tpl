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
{extends file='page.tpl'}

{block name='page_title'}
  {l s='Sitemap' d='Shop.Theme.Global'}
{/block}

{block name='page_content_container'}
  <div id="sitemap-tree" class="sitemap">
    <div class="tree-top row md-bottom">
      <div class="col-12 col-lg-6 col-xl-4">
        <div class="box-bg">
          <a href="{$urls.base_url}" title="{$shop.name}"><i class="fa fa-home" aria-hidden="true"></i> {$shop.name}</a>
        </div>
      </div>
    </div>

    <div class="tree-wrapper row">
      <div class="col-12 col-md-6 col-xl-4">
        <h5>{$our_offers}</h5>
        <div class="tree-content light-box-bg sm-bottom typo">
          {include file='cms/_partials/sitemap-nested-list.tpl' links=$links.offers}
        </div>
      </div>

      <div class="col-12 col-md-6 col-xl-4">
        <h5>{$pages}</h5>
        <div class="tree-content light-box-bg sm-bottom typo">
          {include file='cms/_partials/sitemap-nested-list.tpl' links=$links.pages}
        </div>
      </div>

      <div class="col-12 col-md-6 col-xl-4">
        <h5>{$your_account}</h5>
        <div class="tree-content light-box-bg sm-bottom typo">
          {include file='cms/_partials/sitemap-nested-list.tpl' links=$links.user_account}
        </div>
      </div>

      <div class="col-12 category-sitemap">
        <h5>{$categories}</h5>
        <div class="tree-content light-box-bg sm-bottom typo">
          {include file='cms/_partials/sitemap-nested-list.tpl' links=$links.categories}
        </div>
      </div>
    </div>
  </div>
{/block}
