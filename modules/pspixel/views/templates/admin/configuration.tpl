{*
* 2007-2017 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author	PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2017 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*}

{literal}
<script>
	multishop = '{/literal}{$multishop|intval}{literal}';
	debug_mode = '{/literal}{$debug_mode|intval}{literal}';
{/literal}
</script>

	<!-- Module content -->
	<div id="modulecontent" class="clearfix" role="{$is_submit|intval}">

		<!-- Nav tabs -->
		<div class="col-lg-2">
			<div class="list-group">
				<a href="#documentation" class="list-group-item {if !isset($is_submit)}active{/if}" data-toggle="tab"><i class="icon-book"></i> {l s='Get Started' mod='pspixel'}</a>
				<a href="#conf" class="list-group-item {if isset($is_submit) && !empty($is_submit)}active{/if}" data-toggle="tab"><i class="icon-cogs"></i> {l s='Configuration' mod='pspixel'}</a>
				{if !empty($apifaq)}
				<a href="#help" class="list-group-item" data-toggle="tab"><i class="icon-question-circle"></i> {l s='FAQ' mod='pspixel'}</a>
				{/if}
				<a href="#contacts" class="contactus list-group-item" data-toggle="tab"><i class="icon-envelope"></i> {l s='Contact' mod='pspixel'}</a>
			</div>
			<div class="list-group">
				<a class="list-group-item"><i class="icon-info"></i> {l s='Version' mod='pspixel'}
					{$module_version|escape:'htmlall':'UTF-8'}&nbsp;&nbsp;|&nbsp;&nbsp;
					<i class="icon-info"></i> {l s='PrestaShop' mod='pspixel'} {$version|escape:'htmlall':'UTF-8'}
				</a>
			</div>
		</div>
		<!-- Tab panes -->
		<div class="tab-content col-lg-10">
			<!-- Info configutation -->
			<div class="tab-pane {if !isset($is_submit)}active{/if} panel" id="documentation">
				{include file="./tabs/documentation.tpl"}
			</div>
			<div class="tab-pane {if isset($is_submit) && !empty($is_submit)}active{/if} panel" id="conf">
				{include file="./tabs/conf.tpl"}
			</div>
			{if !empty($apifaq)}
			{include file="./tabs/help.tpl"}
			{/if}
			{include file="./tabs/contact.tpl"}
		</div>
	</div>
