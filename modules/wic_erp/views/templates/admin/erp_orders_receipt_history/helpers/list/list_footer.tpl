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

{extends file="helpers/list/list_footer.tpl"}
{block name="footer"}
<div class="panel-footer">
	{foreach from=$toolbar_btn item=btn key=k}
		{if $k == 'back'}
			{assign 'back_button' $btn}
			{break}
		{/if}
	{/foreach}
	{if isset($back_button)}
		<a id="desc-{$table|escape:'htmlall':'UTF-8'}-{if isset($back_button.imgclass)}{$back_button.imgclass|escape:'htmlall':'UTF-8'}{else}{$k|escape:'htmlall':'UTF-8'}{/if}" class="btn btn-default" {if isset($back_button.href)}href="{$back_button.href|escape:'htmlall':'UTF-8'}"{/if} {if isset($back_button.target) && $back_button.target}target="_blank"{/if}{if isset($back_button.js) && $back_button.js}onclick="{$back_button.js|escape:'html':'UTF-8'}"{/if}>
			<i class="process-icon-back {if isset($back_button.class)}{$back_button.class|escape:'html'}{/if}" ></i> <span {if isset($back_button.force_desc) && $back_button.force_desc == true } class="locked" {/if}>{html_entity_decode($back_button.desc|escape:'html':'UTF-8')}</span>
		</a>
	{/if}
	<a href="" class="btn btn-default pull-right" onclick="$(this).attr('disabled', 'disabled'); if (confirm('Update selected items?'))sendBulkAction($(this).closest('form').get(0), 'submitBulkUpdateerp_order_detail'); return false;">
		<i class="process-icon-refresh" ></i> <span>{l s='Update selected items' mod='wic_erp'}</span>
	</a>
</div>
{/block}