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

{extends file="helpers/list/list_header.tpl"}

{block name=override_header}
<script type="text/javascript">
	$(document).ready(function(){
		$('#filter-by-supplier').click(function() {
			if ($(this).is(':checked')) {
				$('#block_supplier_tree').show();
			} else {
				$('#block_supplier_tree').hide();
				location.href = 'index.php?controller=AdminErpProducts16&token={$token|escape:'html':'UTF-8'}&reset_filter_supplier=1';
			}
		});
	});
</script>
<form method="post" action="{$action|escape:'htmlall':'UTF-8'}" class="form-horizontal clearfix">
<div class="col-lg-12" style=" ">		
	<div class="well">
		<div class="tree-panel-heading-controls clearfix">
			<input type="checkbox" id="filter-by-supplier" name="filter-by-supplier" {if $is_supplier_filter}checked="checked"{/if}>
			<i class="icon-tag"></i>
			{l s='Filter by supplier' mod='wic_erp'}
		</div>
		<div id="block_supplier_tree" {if !$is_supplier_filter}style="display:none;"{/if}>
			<table class="table">
				<thead>
					<tr>
						<th class="fixed-width-xs"><span class="title_box">{l s='Selected' mod='wic_erp'}</span></th>
						<th><span class="title_box">{l s='Supplier Name' mod='wic_erp'}</span></th>								
					</tr>
				</thead>
				<tbody>
					{foreach from=$suppliers item=supplier}
						<tr>
							<td><input type="checkbox" class="supplierCheckBox" name="erp_productsFilter_Supplier[]" {if $supplier['is_selected'] == true}checked="checked"{/if} value="{$supplier['id_supplier']|intval}" /></td>
							<td>{$supplier['name']|escape:'htmlall':'UTF-8'}</td>								
						</tr>
					{/foreach}
				</tbody>
			</table>
			{if $has_actions || (isset($show_filters) && $show_filters) || $suppliers|count > 0}
				<div class="row">
					<div class="col-lg-4" style=" ">
						{if (isset($show_filters) && $show_filters) || $suppliers|count > 0}
						<span class="pull-left">
							{*Search must be before reset for default form submit*}
							<button type="submit" id="submitFilterButton{$list_id|escape:'htmlall':'UTF-8'}" name="submitFilter" class="btn btn-default" data-list-id="{$list_id|escape:'htmlall':'UTF-8'}">
								<i class="icon-search"></i> {l s='Search' mod='wic_erp'}
							</button>
							{if $is_supplier_filter}
							<button type="submit" name="submitReset{$list_id|escape:'htmlall':'UTF-8'}" class="btn btn-warning">
								<i class="icon-eraser"></i> {l s='Reset' mod='wic_erp'}
							</button>
							{/if}
						</span>
						{/if}
					</div>
				</div>
			{/if}
		</div>
	</div>	
</div>
</form>
{/block}