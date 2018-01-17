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


<div class="{if $psv >= 1.6}form-horizontal col-lg-10 {else}form_content{/if}">
	{foreach $errors as $error}
		<div class="{if $psv >= 1.6}alert alert-danger{else}error{/if}">
			{$error|escape:'htmlall':'UTF-8'}
		</div>
	{/foreach}
	{foreach $success as $succes}
		<div class="{if $psv >= 1.6}alert alert-success{else}conf{/if}">
			{$succes|escape:'htmlall':'UTF-8'}
		</div>
	{/foreach}
	{$form nofilter}

	<a href='#sc_user_popup' class="hisc_fb_delete_popup"></a>
	<div id='sc_user_popup' class="hide">
		<button class="delete_table" data-id ='' data-delete-type ='delete_table'>{l s='Delete from Social Connect module' mod='hifacebookconnect'}</button>
		<button class="delete_full" data-id ='' data-delete-type ='delete_full'>{l s='Delete from customers table' mod='hifacebookconnect'}</button>
		<img src="{$hisc_fb_module_url|escape:'htmlall':'UTF-8'}/views/img/loaders/spinner.gif" class="hide hisc_fb_loader">
	</div>
</div>
<div class="clearfix"></div>
