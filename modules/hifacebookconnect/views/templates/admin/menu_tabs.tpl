{**
* 2013 - 2017 HiPresta
*
* MODULE Facebook Connect
*
* @version   1.1.0
* @author    HiPresta <suren.mikaelyan@gmail.com>
* @link      http://www.hipresta.com
* @copyright HiPresta 2017
* @license   PrestaShop Addons license limitation
*
*}

{if $psv >= 1.6}
	<div class="col-lg-2">
		<div class="list-group">
			{foreach from=$tabs key=action item=name}
				<a {if $action == 'version'} style="margin-top:30px;" {/if} class="list-group-item {if {$url_key|escape:'htmlall':'UTF-8'} == "{$action}" || ($url_key == '' && $action == 'generel_sett')} active{/if}"
				{if $action != 'version'} href="{$module_url|escape:'htmlall':'UTF-8'}&hiscfacebook={$action|escape:'htmlall':'UTF-8'}"{/if}>
					{if $action != 'version'}
						{$name|escape:'htmlall':'UTF-8'}
					{else}
						{$name|escape:'htmlall':'UTF-8'} - {$module_version|escape:'html':'UTF-8'}
					{/if}
				</a>
			{/foreach}
		</div>
	</div>
{else}
	<div class="productTabs">
		<ul class="tab">
			{foreach from=$tabs key=action item=name}
				<li class="tab-row">
					<a {if $action == 'version'} style="margin-top:30px;" {/if} class="tab-page 
						{if {$url_key|escape:'htmlall':'UTF-8'} == "{$action}" || ($url_key == '' && $action == 'generel_sett')} selected{/if}"
						{if $action != 'version'} href="{$module_url|escape:'htmlall':'UTF-8'}&hiscfacebook={$action|escape:'htmlall':'UTF-8'}"{/if}>
						{if $action != 'version'}
							{$name|escape:'htmlall':'UTF-8'}
						{else}
							{$name|escape:'htmlall':'UTF-8'} - {$module_version|escape:'html':'UTF-8'}
						{/if}
					</a>
				</li>
			{/foreach}
		</ul>
	</div>
{/if}