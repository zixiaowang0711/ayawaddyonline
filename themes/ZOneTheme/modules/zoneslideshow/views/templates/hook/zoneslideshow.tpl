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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2017 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{if $aslides}
<div class="aone-slideshow theme-default caption-effect-{$settings.caption_effect} {if isset($settings.layout) && $settings.layout == 'boxed'}container{/if} {if $oneSlide}one-slide{/if}">
  <div id="aoneSlider" class="nivoSlider" data-settings={$settings|json_encode}>
    {foreach from=$aslides item=aslide name=aslides}
      {if $aslide.link}<a href="{$aslide.link}" title="">{/if}
      <img
        src = "{$image_baseurl}{$aslide.image}"
        data-thumb = "{$thumb_baseurl}{$aslide.image}"
        alt = "{$aslide.title}"
        {if $aslide.transition}data-transition="{$aslide.transition}"{/if}
        {if $aslide.caption || $aslide.related_products}title="#aCaption_{$smarty.foreach.aslides.iteration|intval}"{/if}
      />
      {if $aslide.link}</a>{/if}
    {/foreach}
  </div>
  
  {foreach from=$aslides item=aslide name=aslides}
    {if $aslide.caption || $aslide.related_products}
      <div id="aCaption_{$smarty.foreach.aslides.iteration|intval}" class="nivo-html-caption">
        {if $aslide.link}<a class="slide-link" href="{$aslide.link}" title=""></a>{/if}

        {if $aslide.caption}
          <div class="caption-wrapper">
            <div class="caption-content">
              {$aslide.caption nofilter} 
            </div>
          </div>
        {/if}

        {if $aslide.related_products}
          <div class="js-slide-products-related slide-products-related hidden-lg-down">
            <div class="slide-products-related-wrapper">
              {include file="module:zoneslideshow/views/templates/hook/product-list.tpl" aproducts=$aslide.related_products}
            </div>
          </div>
        {/if}
      </div>
    {/if}
  {/foreach}
</div>
{/if}
