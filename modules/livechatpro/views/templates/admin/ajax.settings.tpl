{*
* ProQuality (c) All rights reserved.
*
* DISCLAIMER
*
* Do not edit, modify or copy this file.
* If you wish to customize it, contact us at addons4prestashop@gmail.com.
*
* @author    Andrei Cimpean (ProQuality) <addons4prestashop@gmail.com>
* @copyright 2015-2016 ProQuality
* @license   Do not edit, modify or copy this file
*}
{assign var=template value="`$smarty.current_dir`\\`$smarty.template`"}
<select name="settings" id="settings" autocomplete="off" class="form-control fixed-width-xl">
		{foreach from=$settings key=key item=value}
			<option value="{$value.id_setting|escape:'quotes':'UTF-8'}" {if $value.is_primary == '1'}selected{/if}>{$value.name|escape:'quotes':'UTF-8'}</option>
		{/foreach}
</select>