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

{if $variables}
<div class="aone-colors-live-preview hidden-sm-down" id="js-aoneColorsLivePreview">
  <div class="live-preview-toggle js-previewToggle"><i class="fa fa-cog"></i></div>
	<div class="live-preview-container">
    <div class="live-preview-title">{l s='Preview Colors' d='Shop.Zonetheme'}</div>

    <div class="live-preview-boxed-wide js-boxedWide">
      <a href="#" class="js-previewBoxed">{l s='Boxed' d='Shop.Zonetheme'}</a>
      <a href="#" class="js-previewWide active">{l s='Wide' d='Shop.Zonetheme'}</a>
      <span class="style">
        <span class="boxed_bg_css js-boxedBackgroundCSS">{$boxed_bg_css}</span>
        <span class="preview"></span>
      </span>
    </div>

    <div class="live-preview-special-style js-specialStyle">
      <div class="custom-checkbox-wrapper">
        <label>Disable Border Radius</label>
        <span class="custom-radio">
          <input type="checkbox" name="disable_border_radius">
          <span class="ps-shown-by-js"><i class="material-icons check-icon">&#xE5CA;</i></span>
        </span>
      </div>
      <div class="custom-checkbox-wrapper">
        <label>Disable Box Shadow</label>
        <span class="custom-radio">
          <input type="checkbox" name="disable_box_shadow">
          <span class="ps-shown-by-js"><i class="material-icons check-icon">&#xE5CA;</i></span>
        </span>
      </div>
      <div class="custom-checkbox-wrapper">
        <label>Background for Block Title</label>
        <span class="custom-radio">
          <input type="checkbox" name="background_block_title">
          <span class="ps-shown-by-js"><i class="material-icons check-icon">&#xE5CA;</i></span>
        </span>
      </div>
    </div>

		<div class="live-preview-wrapper">
      <p class="hint">{l s='Pick a color, then click OK' d='Shop.Zonetheme'}</p>

      {foreach from=$variables key=k item=v}
        <div class="acolor js-color {$k}">
          <label title="{$v.desc}" data-toggle="tooltip" data-placement="top">{$v.label}</label>
          <div class="color-pick js-colorPicker {$k}" style="background-color: {$v.default};" data-color="{$v.default}"></div>
          {foreach from=$v.styles item=style}
            <span class="style">
              <span class="selector">{$style.selector}</span>
              <span class="property">{$style.property}: {if isset($style.boxShadow)}{$style.boxShadow} {/if}</span>
              <span class="preview"></span>
            </span>
          {/foreach}
        </div>
      {/foreach}
    </div>

    <div class="live-preview-reset"><a href="#" class="js-previewReset">{l s='RESET' d='Shop.Zonetheme'}</a></div>
	</div>
</div>
{/if}