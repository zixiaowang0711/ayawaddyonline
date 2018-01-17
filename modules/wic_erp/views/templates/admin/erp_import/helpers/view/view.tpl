{*
* 2007-2015 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
{extends file="helpers/view/view.tpl"}
{block name="override_tpl"}
	<script type="text/javascript">
		var errorEmpty = '{l s='Please name your data matching configuration in order to save it.' mod='wic_erp' js=1}';
		var current = 0;
		function showTable(nb) {
			$('#btn_left').disabled = null;
			$('#btn_right').disabled = null;
			if (nb <= 0) {
				nb = 0;
				$('#btn_left').disabled = 'true';
			}
			if (nb >= {$nb_table} - 1) {
				nb = {$nb_table|escape:'htmlall':'UTF-8'} - 1;
				$('#btn_right').disabled = 'true';
			}
			$('#table' + current).hide();
			current = nb;
			$('#table' + current).show();
		}
		$(document).ready(function() {
			var btn_save_import = $('span[class~="process-icon-save-import"]').parent();
			var btn_submit_import = $('#import');
			if (btn_save_import.length > 0 && btn_submit_import.length > 0) {
				btn_submit_import.closest('.form-group').hide();
				btn_save_import.find('span').removeClass('process-icon-save-import');
				btn_save_import.find('span').addClass('process-icon-save');
				btn_save_import.click(function(){
					btn_submit_import.before('<input type="hidden" name="' + btn_submit_import.attr("name") + '" value="1" />');
					$('#import_form').submit();
				});
			}
			showTable(current);
		});
	</script>
	<div id="container-customer" class="panel">
		<h3><i class="icon-list-alt"></i> {l s='View your data' mod='wic_erp'}</h3>
		<div class="alert alert-info">
			<p>{l s='Please match each column of your source CSV file to one of the destination columns.' mod='wic_erp'}</p>
		</div>
		<div class="form-horizontal">
			<div class="form-group" {if !$import_matchs}style="display:none"{/if}>
				<label class="control-label col-lg-3">{l s='Load a data matching configuration' mod='wic_erp'}</label>
				<div id="selectDivImportMatchs" class="col-lg-7">
					<select id="valueImportMatchs">
						{foreach $import_matchs as $match}
							<option id="{$match.id_import_match|escape:'htmlall':'UTF-8'}" value="{$match.match|escape:'htmlall':'UTF-8'}">{$match.name|escape:'htmlall':'UTF-8'}</option>
						{/foreach}
					</select>
				</div>
				<div class="col-lg-2">
					<a id="loadImportMatchs" href="#" class="btn btn-default"><i class="icon-cogs"></i> {l s='Load' mod='wic_erp'}</a>
					<a id="deleteImportMatchs" href="#" class="btn btn-default"><i class="icon-remove"></i> {l s='Delete' mod='wic_erp'}</a>
				</div>
			</div>
			<div class="form-group">
				<label class="control-label col-lg-3" for="newImportMatchs">{l s='Save your data matching configuration' mod='wic_erp'}</label>
				<div class="col-lg-7">
					<input type="text" name="newImportMatchs" id="newImportMatchs" />		
				</div>
				<div class="col-lg-2">
					<a id="saveImportMatchs" class="btn btn-default" href="#"><i class="icon-save"></i> {l s='Save' mod='wic_erp'}</a>
				</div>
			</div>
		</div>
		<div id="error_duplicate_type" class="alert alert-warning" style="display:none;">
			{l s='Two columns cannot have the same type of values' mod='wic_erp'}
		</div>
		<div id="required_column" class="alert alert-warning" style="display:none;">
			{l s='This column must be set:' mod='wic_erp'} <span id="missing_column">&nbsp;</span>
		</div>
		<form action="{$current|escape:'html':'UTF-8'}&amp;token={$token|escape:'html':'UTF-8'}" method="post" id="import_form" name="import_form" class="form-horizontal">
			<input type="hidden" name="csv" value="{$fields_value.csv|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="convert" value="{$fields_value.convert|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="regenerate" value="{$fields_value.regenerate|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="entity" value="{$fields_value.entity|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="iso_lang" value="{$fields_value.iso_lang|escape:'htmlall':'UTF-8'}" />
                        <input type="hidden" name="id_erp_order" value="{$fields_value.id_erp_order|escape:'htmlall':'UTF-8'}" />
			{if $fields_value.truncate}
				<input type="hidden" name="truncate" value="1" />
			{/if}
			{if $fields_value.forceIDs}
				<input type="hidden" name="forceIDs" value="1" />
			{/if}
			{if $fields_value.match_ref}
				<input type="hidden" name="match_ref" value="1" />
			{/if}
			<input type="hidden" name="separator" value="{$fields_value.separator|escape:'htmlall':'UTF-8'}" />
			<input type="hidden" name="multiple_value_separator" value="{$fields_value.multiple_value_separator|escape:'htmlall':'UTF-8'}" />
			<div class="form-group">
				<label class="control-label col-lg-3" for="skip">{l s='Lines to skip' mod='wic_erp'}</label>
				<div class="col-lg-9">
					<input class="fixed-width-sm" type="text" name="skip" id="skip" value="1" />
					<p class="help-block">{l s='This number indicates how many of the first lines of your CSV file should be skipped when importing the data. For instance set it to 1 if the first row of your file contains headers.' mod='wic_erp'}</p>
				</div>
			</div>
			<div class="form-group">
				<div class="col-lg-12">
					{section name=nb_i start=0 loop=$nb_table step=1}
						{assign var=i value=$smarty.section.nb_i.index}
						{html_entity_decode($data.$i|escape:'htmlall':'UTF-8')}
					{/section}
					<button id="btn_left" type="button" class="btn btn-default pull-left" onclick="showTable(current - 1);">
						<i class="icon-chevron-sign-left"></i>
					</button>
					<button id="btn_right" type="button" class="btn btn-default pull-right" onclick="showTable(current + 1);">
						<i class="icon-chevron-sign-right"></i>
					</button>
				</div>
			</div>
			<div class="panel-footer">
				<button type="button" class="btn btn-default" onclick="window.history.back();">
					<i class="process-icon-cancel text-danger"></i>
					{l s='Cancel' mod='wic_erp'}
				</button>
				<button id="import" name="import" type="submit" onclick="return (validateImportation(new Array({$res|escape:'htmlall':'UTF-8'})));"  class="btn btn-default pull-right">
					<i class="process-icon-ok text-success"></i>
					{l s='Import .CSV data' mod='wic_erp'}
				</button>
			</div>
		</form>
	</div>
{/block}
