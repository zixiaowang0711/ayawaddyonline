{*
* 2007-2015 PrestaShop
**
* NOTICE OF LICENSE
**
* This source file is subject to the Academic Free License (AFL 3.0)
* that is bundled with this package in the file LICENSE.txt.
* It is also available through the world-wide-web at this URL:
* http://opensource.org/licenses/afl-3.0.php
* If you did not receive a copy of the license and are unable to
* obtain it through the world-wide-web, please send an email
* to license@prestashop.com so we can send you a copy immediately.
**
* DISCLAIMER
**
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
**
*  @author PrestaShop SA <contact@prestashop.com>
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}
<div class="leadin">{block name="leadin"}{/block}</div>
{if $module_confirmation}
<div class="alert alert-success clearfix">
	{l s='Your .CSV file has been successfully imported into your shop.' mod='wic_erp'}
</div>
{/if}
<div class="row">
	<div class="col-lg-8">
		{* Import fieldset *}
		<div class="panel">
			<h3>
				<i class="icon-upload"></i>
				{l s='Import' mod='wic_erp'}
			</h3>
			<div class="alert alert-info">
				<ul class="list-unstyled">
					<li>{l s='Read more about the CSV format at:' mod='wic_erp'}
						<a href="http://en.wikipedia.org/wiki/Comma-separated_values" class="_blank">http://en.wikipedia.org/wiki/Comma-separated_values</a>
					</li>
				</ul>
			</div>
                        <br/>
                        <h3><i class="icon-info-sign"></i> {l s='Genral' mod='wic_erp'}</h3>
			<form id="preview_import" action="{$current|escape:'html':'UTF-8'}&amp;token={$token|escape:'html':'UTF-8'}" method="post" enctype="multipart/form-data" class="form-horizontal">
                <div class="alert alert-warning">
                    <div class="form-group" style="display:block;">
					<label for="entity" class="control-label col-lg-4">{l s='What kind of entity would you like to import?' mod='wic_erp'} </label>
					<div class="col-lg-8">
						<select name="entity" id="entity" class="fixed-width-xxl form-control">
							{foreach $entities AS $entity => $i }
							<option value="{$i|escape:'htmlall':'UTF-8'}"{if $entity_selected == $i} selected="selected"{/if}>
								{$entity|escape:'htmlall':'UTF-8'}
							</option>
							{/foreach}
						</select>
					</div>
				</div>
                </div>
                <br/>
                <div id="import_order_suuplier">
                <h3><i class="icon-info-sign"></i> {l s='Informations' mod='wic_erp'}</h3>
				<div class="alert alert-warning import_products_categories">
                                    <p>{l s='Please verify you haven\'t product with the same reference.' mod='wic_erp'}</p>
				</div>
				<div class="alert alert-warning import_supply_orders_details">
                                    <p>{l s='Importing Supply Order Details will reset your history of ordered products, if there are any.' mod='wic_erp'}</p>
				</div>
                                <br/>
                                <h3><i class="icon-list-alt"></i> {l s='Select supplier Order' mod='wic_erp'}</h3>
                                <div class="form-group">
                                <table id="supply_order_detail" class="table tableDnD attribute" cellpadding="0" cellspacing="0" style="width: 100%;">
                                        <thead>
                                                <tr class="nodrag nodrop">
                                                    <th class="center" style="width:5%;"></th>
                                                    <th class="center" style="width:30%;">{l s='Reference' mod='wic_erp'}</th>
                                                    <th class="center" style="width:20%;">{l s='Status' mod='wic_erp'}</th>
                                                    <th class="center" style="width:15%;">{l s='Supplier' mod='wic_erp'}</th>
                                                    <th class="center" style="width:15%;">{l s='Delivery (expected)' mod='wic_erp'}</th>
                                                    <th class="center" style="width:15%;">{l s='Date Add' mod='wic_erp'}</th>
                                                </tr>
                                        </thead>
                                        <tbody>
                                            {if $erp_list_orders|count > 0}
                                                {foreach $erp_list_orders AS $erp_order}
                                                <tr>
                                                    <td class="center"><input type="radio" name="id_erp_order" value="{$erp_order->id|escape:'htmlall':'UTF-8'}" {if $wic_order_import}checked="checked"{/if} /></td>
                                                    <td class="center">{$erp_order->reference|escape:'htmlall':'UTF-8'}</td>
                                                    <td class="center"><span class="badge" style="background-color:{$erp_order->state_color|escape:'htmlall':'UTF-8'};">{$erp_order->state_name|escape:'htmlall':'UTF-8'}</span></td>
                                                    <td class="center">{$erp_order->supplier_name|escape:'htmlall':'UTF-8'}</td>
                                                    <td class="center"><i class="icon-calendar-o"></i> {dateFormat date=$erp_order->date_delivery_expected}</td>
                                                    <td class="center"><i class="icon-calendar"></i> {dateFormat date=$erp_order->date_add}</td>
                                                </tr>
                                                {/foreach}
                                            {else}
                                                <tr>
                                                    <td colspan="6">{l s='There is no supplier order' mod='wic_erp'}</td>
                                                </tr>
                                            {/if}
                                        </tbody>
                                </table>
                                </div>
                                <br/>
                            </div>
                                <h3><i class="icon-save"></i> {l s='Import your file' mod='wic_erp'}</h3>
				<div class="form-group" id="csv_file_uploader">
					<label for="file" class="control-label col-lg-4">{l s='Select a CSV file to import' mod='wic_erp'}</label>
					<div class="col-lg-8">
						<input id="file" type="file" name="file" data-url="{$current|escape:'html':'UTF-8'}&amp;token={$token|escape:'html':'UTF-8'}&amp;ajax=1&amp;action=uploadCsv" class="hide" />
						<button class="ladda-button btn btn-default" data-style="expand-right" data-size="s" type="button" id="file-add-button">
							<i class="icon-folder-open"></i>
							{l s='Upload a file' mod='wic_erp'}
						</button>
						{l s='or' mod='wic_erp'}
						<button class="btn btn-default csv-history-btn" type="button">
							<span class="csv-history-nb badge">{$files_to_import|count}</span>
							{l s='Choose from history / FTP' mod='wic_erp'}
						</button>
						<p class="help-block">
							{l s='Only UTF-8 and ISO 8859-1 encodings are allowed' mod='wic_erp'}.<br/>
							{l s='You can also upload your file via FTP to the following directory /modules/wic_erp/import/' mod='wic_erp'}
						</p>
					</div>
					<div class="alert alert-danger" id="file-errors" style="display:none"></div>
				</div>
				<div class="form-group" id="csv_files_history" style="display:none;" >
					<div class="panel">
						<div class="panel-heading">
							{l s='History of uploaded .CSV' mod='wic_erp'}
							<span class="csv-history-nb badge">{$files_to_import|count}</span>
							<button type="button" class="btn btn-link pull-right csv-history-btn">
								<i class="icon-remove"></i>
							</button>
						</div>
						<table id="csv_uploaded_history" class="table">
							<tr class="hide">
								<td></td>
								<td>
									<div class="btn-group pull-right">
										<button type="button" data-filename="" class="csv-use-btn btn btn-default">
											<i class="icon-ok"></i>
											{l s='Use' mod='wic_erp'}
										</button>
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
											<i class="icon-chevron-down"></i>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li>
												<a class="csv-download-link _blank" href="#">
													<i class="icon-download"></i>
													{l s='Download' mod='wic_erp'}
												</a>
											</li>
											<li class="divider"></li>
											<li>
												<a class="csv-delete-link" href="#">
													<i class="icon-trash"></i>
													{l s='Delete' mod='wic_erp'}
												</a>
											</li>
										</ul>
									</div>
								</td>
							</tr>
							{foreach $files_to_import AS $filename}
							<tr >
								<td>
									{html_entity_decode($filename|escape:'htmlall':'UTF-8')}
								</td>
								<td>
									<div class="btn-group pull-right">
										<button type="button" data-filename="{$filename|escape:'html':'UTF-8'}" class="csv-use-btn btn btn-default">
											<i class="icon-ok"></i>
											{l s='Use' mod='wic_erp'}
										</button>
										<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
											<i class="icon-chevron-down"></i>
										</button>
										<ul class="dropdown-menu" role="menu">
											<li>
												<a href="{$current|escape:'html':'UTF-8'}&amp;token={$token|escape:'html':'UTF-8'}&amp;csvfilename={$filename|@urlencode}" class="_blank">
													<i class="icon-download"></i>
													{l s='Download' mod='wic_erp'}
												</a>
											</li>
											<li class="divider"></li>
											<li>
												<a href="{$current|escape:'html':'UTF-8'}&amp;token={$token|escape:'html':'UTF-8'}&amp;csvfilename={$filename|@urlencode}&amp;delete=1">
													<i class="icon-trash"></i>
													{l s='Delete' mod='wic_erp'}
												</a>
											</li>
										</ul>
									</div>
								</td>
							</tr>
							{/foreach}
						</table>
					</div>
				</div>
				<div class="form-group" id="csv_file_selected" style="display: none;">
					<div class="alert alert-success clearfix">
						<input type="hidden" value="{$csv_selected|escape:'htmlall':'UTF-8'}" name="csv" id="csv_selected_value" />
						<div class="col-lg-8">
							<span id="csv_selected_filename">{$csv_selected|escape:'html':'UTF-8'}</span>
						</div>
						<div class="col-lg-4">
							<div class="btn-group pull-right">
								<button id="file-remove-button" type="button" class="btn btn-default">
									<i class="icon-refresh"></i>
									{l s='Change' mod='wic_erp'}
								</button>
							</div>
						</div>
					</div>
				</div>
				<hr />
				<div class="form-group">
					<label for="iso_lang" class="control-label col-lg-4">
						<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='The locale must be installed' mod='wic_erp'}">
							{l s='Language of the file' mod='wic_erp'}
						</span>
					</label>
					<div class="col-lg-8">
						<select id="iso_lang" name="iso_lang" class="fixed-width-xl form-control">
							{foreach $languages AS $lang}
								<option value="{$lang.iso_code|escape:'htmlall':'UTF-8'}" {if $lang.id_lang == $id_language} selected="selected"{/if}>{$lang.name|escape:'htmlall':'UTF-8'}</option>
							{/foreach}
						</select>
					</div>
				</div>
				<div class="form-group">
					<label for="convert" class="control-label col-lg-4">{l s='ISO 8859-1 encoded file?' mod='wic_erp'}</label>
					<div class="col-lg-8">
						<label class="switch-light prestashop-switch fixed-width-lg">
							<input name="convert" id="convert" type="checkbox" />
							<span>
								<span>{l s='Yes' mod='wic_erp'}</span>
								<span>{l s='No' mod='wic_erp'}</span>
							</span>
							<a class="slide-button btn"></a>
						</label>
					</div>
				</div>
				<div class="form-group">
					<label for="separator" class="control-label col-lg-4">{l s='Field separator' mod='wic_erp'}</label>
					<div class="col-lg-8">
						<input id="separator" name="separator" class="fixed-width-xs form-control" type="text" value="{if isset($separator_selected)}{$separator_selected|escape:'html':'UTF-8'}{else};{/if}" />
						<div class="help-block">{l s='e.g. ' mod='wic_erp'} 1; Blouse; 129.90; 5</div>
					</div>
				</div>
				<div class="form-group" style="display:none;">
					<label for="multiple_value_separator" class="control-label col-lg-4">{l s='Multiple value separator' mod='wic_erp'}</label>
					<div class="col-lg-8">
						<input id="multiple_value_separator" name="multiple_value_separator" class="fixed-width-xs form-control" type="text" value="{if isset($multiple_value_separator_selected)}{$multiple_value_separator_selected|escape:'html':'UTF-8'}{else},{/if}" />
						<div class="help-block">{l s='e.g. ' mod='wic_erp'} Blouse; red.jpg, blue.jpg, green.jpg; 129.90</div>
					</div>
				</div>
<!--
				{*if empty($files_to_import)*}
				<div class="alert alert-info">{l s='You must upload a file in order to proceed to the next step' mod='wic_erp'}</div>
				{*if !count($files_to_import)*}
				<p>{l s='There is no CSV file available. Please upload one using the \'Upload\' button above.' mod='wic_erp'}</p>
-->
				<div class="panel-footer">
					<button type="submit" name="submitImportFile" id="submitImportFile" class="btn btn-default pull-right" >
						<i class="process-icon-next"></i> <span>{l s='Next step' mod='wic_erp'}</span>
					</button>
				</div>
			</form>
		</div>
	</div>
	<div class="col-lg-4">
		{* Available and required fields *}
		<div class="panel">
			<h3>
				<i class="icon-list-alt"></i>
				{l s='Available fields' mod='wic_erp'}
			</h3>
			<div id="availableFields" class="alert alert-info">
				{html_entity_decode($available_fields|escape:'htmlall':'UTF-8')}
			</div>
			<p>{l s='* Required field' mod='wic_erp'}</p>
		</div>
		<div class="panel">
			<div class="panel-heading">
				<i class="icon-download"></i>
				{l s='Download sample csv files' mod='wic_erp'}
			</div>

			<div class="list-group">
				<a class="list-group-item _blank" href="../modules/wic_erp/docs/csv_import/supply_orders_details_import.csv">
					{l s='Sample Supply Order Details file' mod='wic_erp'}
				</a>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">

	function humanizeSize(bytes) {
		if (typeof bytes !== 'number')
			return '';
		if (bytes >= 1000000000)
			return (bytes / 1000000000).toFixed(2) + ' GB';
		if (bytes >= 1000000)
			return (bytes / 1000000).toFixed(2) + ' MB';
		return (bytes / 1000).toFixed(2) + ' KB';
	}
	// when user select a .csv
	function csv_select(filename) {
		$('#csv_selected_value').val(filename);
		$('#csv_selected_filename').html(filename);
		$('#csv_file_selected').show();
		$('#csv_file_uploader').hide();
		$('#csv_files_history').hide();
	}
	// when user unselect the .csv
	function csv_unselect() {
		$('#csv_file_selected').hide();
		$('#csv_file_uploader').show();
	}

	// add a disabled state when empty history
	function enableHistory(){
		if($('.csv-history-nb').text() == 0){
			$('button.csv-history-btn').attr('disabled','disabled');
		} else {
			$('button.csv-history-btn').attr('disabled',false);
		}
	}

	$(document).ready(function() {

		var file_add_button = Ladda.create(document.querySelector('#file-add-button'));
		var file_total_files = 0;

		$('#file').fileupload({
			dataType: 'json',
			autoUpload: true,
			acceptFileTypes: /(\.|\/)(csv)$/i,
			singleFileUploads: true,
			{if isset ($post_max_size)}maxFileSize: {$post_max_size|escape:'htmlall':'UTF-8'},{/if}
			start: function (e) {
				file_add_button.start();
			},
			fail: function (e, data) {
				$('#file-errors').html(data.errorThrown.message).show();
			},
			done: function (e, data) {
				if (data.result) {
					if (typeof data.result.file !== 'undefined') {
						if (typeof data.result.file.error !== 'undefined' && data.result.file.error != '')
							$('#file-errors').html('<strong>'+data.result.file.name+'</strong> : '+data.result.file.error).show();
						else {
							$(data.context).find('button').remove();

							var filename = encodeURIComponent(data.result.file.filename);
							var row = $('#csv_uploaded_history tr:first').clone();

							$('#csv_uploaded_history').append(row);
							row.removeClass('hide');
							row.find('td:first').html(data.result.file.filename);
							row.find('button.csv-use-btn').data('filename', data.result.file.filename);
							row.find('a.csv-download-link').attr('href','{$current|escape:'html':'UTF-8'}&token={$token|escape:'html':'UTF-8'}&csvfilename='+filename);
							row.find('a.csv-delete-link').attr('href','{$current|escape:'html':'UTF-8'}&token={$token|escape:'html':'UTF-8'}&csvfilename='+filename+'&delete=1');
							csv_select(data.result.file.filename);
							var items = $('#csv_uploaded_history tr').length -1;
							$('.csv-history-nb').html(items);
							enableHistory();
						}
					}
				}
			},
		}).on('fileuploadalways', function (e, data) {
			file_add_button.stop();
		}).on('fileuploadprocessalways', function (e, data) {
			var index = data.index,	file = data.files[index];

			if (file.error) {
				$('#file-errors').append('<strong>'+file.name+'</strong> ('+humanizeSize(file.size)+') : '+file.error).show();
				$(data.context).find('button').trigger('click');
			}
		});

		$('#csv_uploaded_history').on('click', 'button.csv-use-btn', function(e){
			e.preventDefault();
			var filename = $(this).data('filename');
			csv_select(filename);
		});
		$('#file-add-button').on('click', function(e) {
			e.preventDefault();
			$('#file-success').hide();
			$('#file-errors').html('').hide();
			$('#file').trigger('click');
		});
		$('#file-remove-button').on('click', function(e) {
			e.preventDefault();
			csv_unselect();
		});

		$('.csv-history-btn').on('click',function(e){
			e.preventDefault();
			$('#csv_files_history').toggle();
			$('#csv_file_uploader').toggle();
		})
		//show selected csv if exists
		var selected = '{$csv_selected|escape:'htmlall':'UTF-8'}';
		if(selected){
			$('#csv_file_selected').show();
			$('#csv_file_uploader').hide();
		}

		var truncateAuthorized = {$truncateAuthorized|intval};

		enableHistory();

		$('#file-selectbutton').click(function(e){
			$('#file').trigger('click');
		});
		$('#filename').click(function(e){
			$('#file').trigger('click');
		});
		$('#file').change(function(e){
			var val = $(this).val();
			var file = val.split(/[\\/]/);
			$('#filename').val(file[file.length-1]);
		});
        
        $('#entity').change(function() {
            $( "select option:selected" ).each(function() {
                if ($(this).val() == 1)
                    $('#import_order_suuplier').hide();
                else if ($(this).val() == 0)
                    $('#import_order_suuplier').show();
            });
            
            $.ajax({
				url: '{$current|escape:'html':'UTF-8'}',
				data: {
					getAvailableFields:1,
                    token:'{$token|escape:'html':'UTF-8'}',
                    ajax:true,
					entity: $("#entity").val(),
                    action: 'displayEntity',
				},
				dataType: 'json',
				success: function(j){
					var fields = "";
					$("#availableFields").empty();
					
					for (var i = 0; i < j.length; i++)
						fields += j[i].field;
	
					$("#availableFields").html(fields);
					$('.help-tooltip').tooltip();
				},
				error: function(j){}			
			});
        });
	});
</script>