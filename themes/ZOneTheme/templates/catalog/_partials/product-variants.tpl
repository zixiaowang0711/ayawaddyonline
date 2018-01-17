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
{if $groups}
<div class="product-variants sm-bottom">
  {foreach from=$groups key=id_attribute_group item=group}
    <div class="product-variants-item row">
      <label class="form-control-label col-3 col-xl-2">{$group.name}</label>

      <div class="attribute-list col-9 col-xl-10">
        {if $group.group_type == 'select'}
          <select
            id="group_{$id_attribute_group}"
            data-product-attribute="{$id_attribute_group}"
            name="group[{$id_attribute_group}]"
            class="custom-select"
          >
            {foreach from=$group.attributes key=id_attribute item=group_attribute}
              <option value="{$id_attribute}" title="{$group_attribute.name}"{if $group_attribute.selected} selected="selected"{/if}>{$group_attribute.name}</option>
            {/foreach}
          </select>
        {elseif $group.group_type == 'color'}
          <ul id="group_{$id_attribute_group}" class="color-group d-flex flex-wrap align-items-center">
            {foreach from=$group.attributes key=id_attribute item=group_attribute}
              <li>
                <span class="custom-checkbox custom-color">
                  <input class="input-color" type="radio" data-product-attribute="{$id_attribute_group}" name="group[{$id_attribute_group}]" value="{$id_attribute}"{if $group_attribute.selected} checked="checked"{/if}>
                  <span
                    {if $group_attribute.html_color_code}class="color" style="background-color: {$group_attribute.html_color_code}" {/if}
                    {if $group_attribute.texture}class="color texture" style="background-image: url({$group_attribute.texture})" {/if}
                  ><span class="check-circle"></span><span class="sr-only">{$group_attribute.name}</span></span>
                </span>
              </li>
            {/foreach}
          </ul>
        {elseif $group.group_type == 'radio'}
          <ul id="group_{$id_attribute_group}">
            {foreach from=$group.attributes key=id_attribute item=group_attribute}
              <li class="custom-checkbox-wrapper">
                <span class="custom-radio">
                  <input class="input-radio" type="radio" data-product-attribute="{$id_attribute_group}" name="group[{$id_attribute_group}]" value="{$id_attribute}"{if $group_attribute.selected} checked="checked"{/if}>
                  <span class="ps-shown-by-js"><i class="material-icons check-icon"></i></span>
                </span>

                <span class="radio-label">{$group_attribute.name}</span>
              </li>
            {/foreach}
          </ul>
        {/if}
      </div>
    </div>
  {/foreach}
</div>
{/if}
