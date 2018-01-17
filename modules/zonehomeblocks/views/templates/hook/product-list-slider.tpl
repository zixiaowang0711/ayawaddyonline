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
{if !isset($slidesToShow)}
{assign var='slidesToShow' value=5}
{/if}
{if !isset($autoplay)}
{assign var='autoplay' value='false'}
{/if}

<div class="product-list">
  <div
  	class="product-list-wrapper clearfix {if $slidesToShow > 2}grid{else}list{/if} columns-slick js-home-block-slider"
  	id="{$slickID}"
  	data-autoscroll="{$autoplay}"
    data-slidestoshow="{$slidesToShow}"
  	data-mdslidestoshow="{if $slidesToShow > 2}{$slidesToShow}{else}1{/if}"
  >
    {foreach from=$aproducts item="product"}
      {include file='catalog/_partials/miniatures/product.tpl' product=$product}
    {/foreach}
  </div>
</div>