{*
* Module My Easy ERP Web In Color 
* 
*  @author    Web In Color - addons@webincolor.fr
*  @version 2.6
*  @uses Prestashop modules
*  @since 1.0 - mai 2014
*  @package Wic ERP
*  @copyright Copyright &copy; 2014, Web In Color
*  @license   http://www.webincolor.fr
*}

<tbody>
{if count($list)}
{foreach $list AS $index => $tr}
	<tr
	{if $position_identifier}id="tr_{$position_group_identifier|intval}_{$tr.$identifier|intval}_{if isset($tr.position['position'])}{$tr.position['position']|intval}{else}0{/if}"{/if}
	class="{if isset($tr.class)} {$tr.class|escape:'htmlall':'UTF-8'}{/if} {if $tr@iteration is odd by 1}odd{/if}"
	{if isset($tr.color) && $color_on_bg}style="background-color: {$tr.color|escape:'htmlall':'UTF-8'}"{/if}

	>
		{if version_compare($smarty.const._PS_VERSION_,'1.6','<')}
		<td> </td>
		{/if}
		{foreach $fields_display AS $key => $params}
			{block name="open_td"}
				<td
					{if isset($params.position)}
						id="td_{if !empty($position_group_identifier)}{$position_group_identifier|escape:'htmlall':'UTF-8'}{else}0{/if}_{$tr.$identifier|escape:'htmlall':'UTF-8'}"
					{/if}
					class="{if !$no_link}pointer{/if}
					{if isset($params.position) && $order_by == 'position'  && $order_way != 'DESC'} dragHandle{/if}
					{if isset($params.class)} {$params.class|escape:'htmlall':'UTF-8'}{/if}
					{if isset($params.align)} {$params.align|escape:'htmlall':'UTF-8'}{/if}"
					{if (!isset($params.position) && !$no_link && !isset($params.remove_onclick))}
						onclick="document.location = '{$current_index|intval}&{$identifier|escape:'htmlall':'UTF-8'}={$tr.$identifier|escape:'htmlall':'UTF-8'}{if $view}&view{else}&update{/if}{$table|escape:'htmlall':'UTF-8'}&token={$token|escape:'htmlall':'UTF-8'}'">
					{else}
					>
				{/if}
			{/block}
			{block name="td_content"}
				{if isset($params.prefix)}{html_entity_decode($params.prefix|escape:'htmlall':'UTF-8')}{/if}
				{if isset($params.badge_success) && $params.badge_success && isset($tr.badge_success) && $tr.badge_success == $params.badge_success}<span class="badge badge-success">{/if}
				{if isset($params.badge_warning) && $params.badge_warning && isset($tr.badge_warning) && $tr.badge_warning == $params.badge_warning}<span class="badge badge-warning">{/if}
				{if isset($params.badge_danger) && $params.badge_danger && isset($tr.badge_danger) && $tr.badge_danger == $params.badge_danger}<span class="badge badge-danger">{/if}
				{if isset($params.color) && isset($tr[$params.color])}
					<span class="label color_field" style="background-color:{$tr[$params.color]|escape:'htmlall':'UTF-8'};color:{if Tools::getBrightness($tr[$params.color]) < 128}white{else}#383838{/if}">
				{/if}
				{if isset($tr.$key)}
					{if isset($params.active)}
						{$tr.$key|escape:'html':'UTF-8'}
					{elseif isset($params.activeVisu)}
						{if $tr.$key}
							<i class="icon-check-ok"></i> {l s='Enabled' mod='wic_erp'}
						{else}
							<i class="icon-remove"></i> {l s='Disabled' mod='wic_erp'}
						{/if}

					{elseif isset($params.position)}
						{if $order_by == 'position' && $order_way != 'DESC'}
							<div class="dragGroup">
								<div class="positions">
									{$tr.$key.position|escape:'html':'UTF-8'}
								</div>
								<div class="btn-group">
									<a class="btn btn-default btn-xs" href="{$tr.$key.position_url_down|escape:'htmlall':'UTF-8'}" {if !($tr.$key.position != $positions[count($positions) - 1])}disabled{/if}>
										<i class="icon-chevron-{if $order_way == 'ASC'}down{else}up{/if}"></i>
									</a>
									<a class="btn btn-default btn-xs" href="{$tr.$key.position_url_up|escape:'htmlall':'UTF-8'}" {if !($tr.$key.position != $positions.0)}disabled{/if}>
										<i class="icon-chevron-{if $order_way == 'ASC'}up{else}down{/if}"></i>
									</a>
								</div>
							</div>
						{else}
							{$tr.$key.position + 1|intval}
						{/if}
					{elseif isset($params.image)}
						{$tr.$key|escape:'htmlall':'UTF-8'}
					{elseif isset($params.icon)}
						{if is_array($tr[$key])}
							{if isset($tr[$key]['class'])}
								<i class="{$tr[$key]['class']|escape:'htmlall':'UTF-8'}"></i>
							{else}
								<img src="../img/admin/{$tr[$key]['src']|escape:'htmlall':'UTF-8'}" alt="{$tr[$key]['alt']|escape:'htmlall':'UTF-8'}" title="{$tr[$key]['alt']|escape:'htmlall':'UTF-8'}" />
							{/if}
                        {else}
                            <i class="{$tr[$key]|escape:'html':'UTF-8'}"></i>
						{/if}
					{elseif isset($params.type) && $params.type == 'price'}
						{displayPrice price=$tr.$key}
					{elseif isset($params.float)}
						{$tr.$key|escape:'htmlall':'UTF-8'}
					{elseif isset($params.type) && $params.type == 'date'}
						{dateFormat date=$tr.$key full=0}
					{elseif isset($params.type) && $params.type == 'datetime'}
						{dateFormat date=$tr.$key full=1}
					{elseif isset($params.type) && $params.type == 'decimal'}
						{$tr.$key|string_format:"%.2f"|escape:'htmlall':'UTF-8'}
					{elseif isset($params.type) && $params.type == 'percent'}
						{$tr.$key|escape:'html':'UTF-8'} {l s='%' mod='wic_erp'}
					{* If type is 'editable', an input is created *}
					{elseif isset($params.type) && $params.type == 'editable' && isset($tr.id)}
						<input type="text" name="{$key|escape:'htmlall':'UTF-8'}_{$tr.id|intval}" value="{$tr.$key|escape:'html':'UTF-8'}" class="{$key|escape:'htmlall':'UTF-8'}" />
					{elseif isset($params.callback)}
						{if isset($params.maxlength) && Tools::strlen($tr.$key) > $params.maxlength}
							<span title="{$tr.$key|escape:'htmlall':'UTF-8'}">{$tr.$key|truncate:$params.maxlength:'...'|escape:'htmlall':'UTF-8'}</span>
						{else}
							{html_entity_decode($tr.$key|escape:'htmlall':'UTF-8')}
						{/if}
					{elseif $key == 'color'}
						{if !is_array($tr.$key)}
						<div style="background-color: {$tr.$key|escape:'htmlall':'UTF-8'};" class="attributes-color-container"></div>
						{else} {*TEXTURE*}
						<img src="{$tr.$key.texture|escape:'htmlall':'UTF-8'}" alt="{$tr.name|escape:'htmlall':'UTF-8'}" class="attributes-color-container" />
						{/if}
					{elseif isset($params.maxlength) && Tools::strlen($tr.$key) > $params.maxlength}
						<span title="{$tr.$key|escape:'html':'UTF-8'}">{$tr.$key|truncate:$params.maxlength:'...'|escape:'html':'UTF-8'}</span>
					{else}
						{html_entity_decode($tr.$key|escape:'html':'UTF-8')}
					{/if}
				{else}
					{block name="default_field"}--{/block}
				{/if}
				{if isset($params.suffix)}{html_entity_decode($params.suffix|escape:'htmlall':'UTF-8')}{/if}
				{if isset($params.color) && isset($tr.color)}
					</span>
				{/if}
				{if isset($params.badge_danger) && $params.badge_danger && isset($tr.badge_danger) && $tr.badge_danger == $params.badge_danger}</span>{/if}
				{if isset($params.badge_warning) && $params.badge_warning && isset($tr.badge_warning) && $tr.badge_warning == $params.badge_warning}</span>{/if}
				{if isset($params.badge_success) && $params.badge_success && isset($tr.badge_success) && $tr.badge_success == $params.badge_success}</span>{/if}
			{/block}
			{block name="close_td"}
				</td>
			{/block}
		{/foreach}

	{if $shop_link_type}
		<td title="{$tr.shop_name|escape:'html':'UTF-8'}">
			{if isset($tr.shop_short_name)}
				{$tr.shop_short_name|escape:'htmlall':'UTF-8'}
			{else}
				{$tr.shop_name|escape:'htmlall':'UTF-8'}
			{/if}
		</td>
	{/if}
	{if $has_actions}
		<td class="text-right">
			{assign var='compiled_actions' value=array()}
			{foreach $actions AS $key => $action}
				{if isset($tr.$action)}
					{if $key == 0}
						{assign var='action' value=$action}
					{/if}
					{$compiled_actions[] = $tr.$action}
				{/if}
			{/foreach}
			{if $compiled_actions|count > 0}
				{if $compiled_actions|count > 1}<div class="btn-group-action">{/if}
				
					{html_entity_decode($compiled_actions[0]|regex_replace:'/class\s*=\s*"(\w*)"/':'class="$1 btn btn-default"'|escape:'htmlall':'UTF-8')}
					{if $compiled_actions|count > 1}
					<button class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						<i class="icon-caret-down"></i>&nbsp;
					</button>
						<ul class="dropdown-menu">
						{foreach $compiled_actions AS $key => $action}
							{if $key != 0}
							<li>
								{html_entity_decode($action|escape:'htmlall':'UTF-8')}
							</li>
							{/if}
						{/foreach}
						</ul>
					{/if}
				
				{if $compiled_actions|count > 1}</div>{/if}
			{/if}
		</td>
	{/if}
	</tr>
{/foreach}
{else}
	<tr>
		<td class="list-empty" colspan="{count($fields_display) + 2|intval}">
			<div class="list-empty-msg">
				<i class="icon-warning-sign list-empty-icon"></i>
				{l s='No records found' mod='wic_erp'}
			</div>
		</td>
	</tr>
{/if}
</tbody>
