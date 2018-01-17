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
{if (!empty($online_internal_users)) && ($count_online_internal_users gt 1)}
<select name="online_internal_users_select" id="online_internal_users_select" class="form-control">
	{foreach from=$online_internal_users key=key item=value}
		{if $id_staffprofile != $value.id_staffprofile}
			<option value="{$value.id_staffprofile|escape:'quotes':'UTF-8'}">{$value.firstname|escape:'quotes':'UTF-8'} {$value.lastname|escape:'quotes':'UTF-8'}</option>
		{/if}
	{/foreach}
</select>
{else}
	{lang s='There are no online staff members.'}
{/if}