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
<select name="themes" id="themes" class="form-control fixed-width-xl">
		{foreach from=$themes key=key item=value}
			<option value="{$value.id_theme|escape:'quotes':'UTF-8'}">{$value.name|escape:'quotes':'UTF-8'}</option>
		{/foreach}					
</select>