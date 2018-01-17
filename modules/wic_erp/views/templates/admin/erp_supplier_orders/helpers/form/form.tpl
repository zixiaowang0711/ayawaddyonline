{*
* 2014 Web In Color
*
*  @author Web In Color <support@webincolor.fr>
*  @copyright  2014 Web In Color
*  @license    Web In Color
*  International Registered Trademark & Property of Web In Color
*}

{extends file="helpers/form/form.tpl"}

{block name="other_fieldsets"}
    <div class="panel col-lg-3" style="position:absolute;{if version_compare($smarty.const._PS_VERSION_,'1.6','<')}top:25%;{else}top:10%;{/if}left:70%;">
        <div class="panel-heading">
            {l s='Supplier infomation' mod='wic_erp'}
        </div>
        <ul id="display_ajax_info"></ul>
    </div>
	{if isset($show_product_management_form)}
	<p>&nbsp;</p>

	<input type="hidden" id="product_ids" name="product_ids" value="{$product_ids|escape:'html':'UTF-8'}" />
	<input type="hidden" id="product_ids_to_delete" name="product_ids_to_delete" value="{$product_ids_to_delete|escape:'html':'UTF-8'}" />
	<input type="hidden" name="updateerp_order" value="1" />

	<div class="panel">
		<h3><i class="icon-cogs"></i>{l s='Manage the products you want to order from your supplier' mod='wic_erp'}</h3>
		<div class="alert alert-info">{l s='To add a product to the order, type the first letters of the product name, then select it from the drop-down list:' mod='wic_erp'}</div>
		<div class="row">
			<div class="col-lg-8">
				<input type="text" size="60" id="cur_product_name" />
			</div>
			<div class="col-lg-2">
				<button type="button" class="btn btn-default" onclick="addProduct();"><i class="icon-plus-sign"></i> {l s='Add a product to the supply order' mod='wic_erp'}</button>
			</div>
		</div>
		<table id="products_in_erp_order" class="table">
						<thead>
							<tr class="nodrag nodrop">
								<th style="width: 50px">{l s='Photo' mod='wic_erp'}</th>
								<th style="width: 100px">{l s='Reference' mod='wic_erp'}</th>
								<th style="width: 50px">{l s='EAN13' mod='wic_erp'}</th>
								<th style="width: 50px">{l s='UPC' mod='wic_erp'}</th>
								<th style="width: 100px">{l s='Supplier Reference' mod='wic_erp'}</th>
								<th style="width: 50px">{l s='In Stock' mod='wic_erp'}</th>
								<th>{l s='Name' mod='wic_erp'}</th>
								<th style="width: 100px">{l s='Unit Price (tax excl.)' mod='wic_erp'}</th>
								<th style="width: 100px">{l s='Quantity' mod='wic_erp'}</th>
								<th style="width: 100px">{l s='Discount rate' mod='wic_erp'}</th>
								<th style="width: 100px">{l s='Tax rate' mod='wic_erp'}</th>
								<th style="width: 40px">{l s='Delete' mod='wic_erp'}</th>
							</tr>
						</thead>
						<tbody>
							{foreach $products_list AS $product}
								<tr style="height:50px;">
									<td>
										{if isset($product.image_tag)}
                                                                                    {html_entity_decode($product.image_tag|escape:'htmlall':'UTF-8')}
										{/if}
									</td>
									<td>
										{$product.reference|escape:'htmlall':'UTF-8'}
										<input type="hidden" name="input_check_{$product.id_product|intval}_{$product.id_product_attribute|intval}" value="{$product.checksum|escape:'htmlall':'UTF-8'}" />
										<input type="hidden" name="input_reference_{$product.id_product|intval}_{$product.id_product_attribute|intval}" value="{$product.reference|escape:'htmlall':'UTF-8'}" />
										<input type="hidden" name="input_id_{$product.id_product|intval}_{$product.id_product_attribute|intval}" value="{if isset($product.id_erp_order_detail)}{$product.id_erp_order_detail|intval}{/if}" />
									</td>
									<td>
                                                                                <img src="{$smarty.const.__PS_BASE_URI__}modules/wic_erp/views/img/barcode/barcode_{$product.ean13|escape:'htmlall':'UTF-8'}.png">
										{l s='ean' mod='wic_erp'} : {$product.ean13|escape:'htmlall':'UTF-8'}
										<input type="hidden" name="input_ean13_{$product.id_product|intval}_{$product.id_product_attribute|intval}" value="{$product.ean13|escape:'htmlall':'UTF-8'}" />
									</td>
									<td>
										{$product.upc|escape:'htmlall':'UTF-8'}
										<input type="hidden" name="input_upc_{$product.id_product|intval}_{$product.id_product_attribute|intval}" value="{$product.upc|escape:'htmlall':'UTF-8'}" />
									</td>
									<td>
										{$product.supplier_reference|escape:'htmlall':'UTF-8'}
										<input type="hidden" name="input_supplier_reference_{$product.id_product|intval}_{$product.id_product_attribute|intval}" value="{$product.supplier_reference|escape:'htmlall':'UTF-8'}" />
									</td>
									<td>
										{if $product.in_stock <= 0}
											<span class="badge badge-danger">
										{else}
											<span class="badge badge-success">
										{/if}
										{$product.in_stock|intval}
											</span>
									</td>
									<td>
										{$product.name|escape:'htmlall':'UTF-8'}
										<input type="hidden" name="input_name_{$product.id_product|intval}_{$product.id_product_attribute|intval}" value="{$product.name|escape:'htmlall':'UTF-8'}" />
									</td>
									<td>
										<div class="input-group fixed-width-md">
											{if isset($currency->prefix) && trim($currency->prefix) != ''}<span class="input-group-addon">{$currency->prefix|escape:'htmlall':'UTF-8'}</span>{/if}<input type="text" name="input_unit_price_te_{$product.id_product|intval}_{$product.id_product_attribute|intval}" value="{$product.unit_price_te|htmlentities}" />{if isset($currency->suffix) && trim($currency->suffix) != ''}<span class="input-group-addon">{$currency->suffix|escape:'htmlall':'UTF-8'}</span>{/if}
										</div>
									</td>
									<td>
										<input type="text" name="input_quantity_ordered_{$product.id_product|intval}_{$product.id_product_attribute|intval}" value="{$product.quantity_ordered|htmlentities}" class="fixed-width-xs" />
									</td>
									<td>
										<div class="input-group fixed-width-md">
											<input type="text" name="input_discount_rate_{$product.id_product|intval}_{$product.id_product_attribute|intval}" value="{round($product.discount_rate, 4)|escape:'htmlall':'UTF-8'}" /><span class="input-group-addon">%</span>
										</div>
									</td>
									<td>
										<div class="input-group fixed-width-md">
											<input type="text" name="input_tax_rate_{$product.id_product|intval}_{$product.id_product_attribute|intval}" value="{round($product.tax_rate, 4)|escape:'htmlall':'UTF-8'}" /><span class="input-group-addon">%</span>
										</div>
									</td>
									<td>
                                                                            <div style="margin:5px;"><a href="#" id="deletelink|{$product.id_product|intval}_{$product.id_product_attribute|intval}" class="btn btn-default removeProductFromErpOrderLink"><i class="icon-trash"></i> {l s='Remove' mod='wic_erp'}
                                                                                </a></div>
                                                                            <div style="margin:5px;"><a href="#" id="deletelinkProduct|{$product.id_product|intval}_{$product.id_product_attribute|intval}" class="btn btn-default removeProductFromErpOrderLinkProduct"><i class="icon-eraser"></i> {l s='Remove all combination' mod='wic_erp'}
                                                                                </a></div>
                                                                            <div style="margin:5px;"><button type="button" data-attribute="{$product.id_product_attribute|intval}" data-product="{$product.id_product|intval}" class="btn btn-default expandProduct"><i class="icon-cloud-download"></i> {l s='Expand to all combination' mod='wic_erp'}</button></div>
                                                                        </td>
								</tr>
                                                                {if $product.location|count || (isset($product.quantity_expected_by_warehouse) && $product.quantity_expected_by_warehouse|count)}
                                                                <tr>
                                                                    <td colspan="2"><b>{l s='Warehouse location:' mod='wic_erp'}</b></td>
                                                                    <td colspan="5">
                                                                        {if $product.location|count}
                                                                            <ul class="list-group">
                                                                            {foreach $product.location AS $location}
                                                                                <li class="list-group-item">
                                                                                    <span class="badge">{$location.wh_location|escape:'htmlall':'UTF-8'}</span>
                                                                                    {$location.wh_name|escape:'htmlall':'UTF-8'}
                                                                                </li>
                                                                            {/foreach}
                                                                            </ul>
                                                                        {else}
                                                                            {l s='No warehouse location' mod='wic_erp'}
                                                                        {/if}
                                                                    </td>
                                                                    <td colspan="1"><b>{l s='Quantity expected by warehouse:' mod='wic_erp'}</b></td>
                                                                    <td colspan="4">
                                                                        {if isset($product.quantity_expected_by_warehouse) && $product.quantity_expected_by_warehouse|count}
                                                                            <ul class="list-group">
                                                                            {foreach $product.quantity_expected_by_warehouse AS $quantity}
                                                                                <li class="list-group-item">
                                                                                    <div class="row">
                                                                                        <div class="col-lg-4"><label>{$quantity.wh_name|escape:'htmlall':'UTF-8'}</label></div>
                                                                                        <div class="col-lg-8"><input type="text" name="qtyWarehouse_{$quantity.wh_id|intval}_{$product.id_product|intval}_{$product.id_product_attribute|intval}" value="{$quantity.wh_quantity_expected|escape:'htmlall':'UTF-8'}" /></div>
                                                                                    </div>
                                                                                </li>
                                                                            {/foreach}
                                                                            </ul>
                                                                        {/if}
                                                                    </td>
                                                                </tr>
                                                                {/if}
							{/foreach}
						</tbody>
					</table>
			<div class="panel-footer">
			<button type="submit" value="1" id="erp_order_form_submit_btn" name="submitAdderp_order" class="btn btn-default pull-right">
				<i class="process-icon-save"></i> {l s='Save order' mod='wic_erp'}
			</button>
			<a class="btn btn-default" onclick="window.history.back()">
				<i class="process-icon-cancel"></i> {l s='Cancel' mod='wic_erp'}
			</a>
			<button type="submit" class="btn btn-default btn btn-default pull-right" name="submitAdderp_orderAndStay"><i class="process-icon-save"></i> {l s='Save order and stay' mod='wic_erp'}</button>
		</div>
	</div>

	<script type="text/javascript">
		product_infos = null;
		debug = null;
		
		if ($('#product_ids').val() == '')
			product_ids = [];
		else
			product_ids = $('#product_ids').val().split('|');

		if ($('#product_ids_to_delete').val() == '')
			product_ids_to_delete = [];
		else
			product_ids_to_delete = $('#product_ids_to_delete').val().split('|');


		function addProduct()
		{
			// check if it's possible to add the product
			if (product_infos == null || $('#cur_product_name').val() == '')
			{
				jAlert('{l s='Please select at least one product.' mod='wic_erp'}');
				return false;
			}

			spanElement = '<span class="badge badge-danger">';
			
			if (!product_infos.unit_price_te)
				product_infos.unit_price_te = 0;
			
			if (product_infos.in_stock > 0)
				spanElement = '<span class="badge badge-success">';
				
			// add a new line in the products table
			$('#products_in_erp_order > tbody').prepend(
				'<tr>'+
				'<td>'+product_infos.image_tag+'</td>'+
				'<td>'+product_infos.reference+'<input type="hidden" name="input_check_'+product_infos.id+'" value="'+product_infos.checksum+'" /><input type="hidden" name="input_reference_'+product_infos.id+'" value="'+product_infos.reference+'" /></td>'+
				'<td>'+product_infos.ean13+'<input type="hidden" name="input_ean13_'+product_infos.id+'" value="'+product_infos.ean13+'" /></td>'+
				'<td>'+product_infos.upc+'<input type="hidden" name="input_upc_'+product_infos.id+'" value="'+product_infos.upc+'" /></td>'+
				'<td>'+product_infos.supplier_reference+'<input type="hidden" name="input_supplier_reference_'+product_infos.id+'" value="'+product_infos.supplier_reference+'" /></td>'+
				'<td>'+spanElement+product_infos.in_stock+'</span></td>'+
				'<td>'+product_infos.name+'<input type="hidden" name="input_name_displayed_'+product_infos.id+'" value="'+product_infos.name+'" /></td>'+
				'<td><div class="input-group fixed-width-md">{if isset($currency->prefix) && trim($currency->prefix) != ''}<span class="input-group-addon">{$currency->prefix|escape:'htmlall':'UTF-8'}</span>{/if}<input type="text" name="input_unit_price_te_'+product_infos.id+'" value="'+product_infos.unit_price_te+'" />{if isset($currency->suffix) && trim($currency->suffix) != ''}<span class="input-group-addon">{$currency->suffix|escape:'htmlall':'UTF-8'}</span>{/if}</div></td>'+
				'<td><input type="text" name="input_quantity_ordered_'+product_infos.id+'" value="0" class="fixed-width-xs" /></td>'+
				'<td><div class="input-group fixed-width-md"><input type="text" name="input_discount_rate_'+product_infos.id+'" value="0" /><span class="input-group-addon">%</span></div></td>'+
				'<td><div class="input-group fixed-width-md"><input type="text" name="input_tax_rate_'+product_infos.id+'" value="'+product_infos.tax_rate+'" /><span class="input-group-addon">%</span></div></td>'+
				'<td><div style="margin:5px;"><a href="#" id="deletelink|'+product_infos.id+'" class="btn btn-default removeProductFromErpOrderLink"><i class="icon-trash"></i> {l s='Remove' mod='wic_erp'}</a></div>'+
				'<div style="margin:5px;"><a href="#" id="deletelinkProduct|'+product_infos.id+'" class="btn btn-default removeProductFromErpOrderLinkProduct"><i class="icon-eraser"></i> {l s='Remove all combination' mod='wic_erp'}</a></div>'+
                                '<div style="margin:5px;"><button type="button" data-attribute="'+product_infos.id.split('_')[1]+'" data-product="'+product_infos.id.split('_')[0]+'" class="btn btn-default expandProduct"><i class="icon-cloud-download"></i> {l s='Expand to all combination' mod='wic_erp'}</button></div>'+
                                '</td></tr>'
			);

			// add the current product id to the product_id array - used for not show another time the product in the list
			product_ids.push(product_infos.id);

			// update the product_ids hidden field
			$('#product_ids').val(product_ids.join('|'));

			// clear the cur_product_name field
			$('#cur_product_name').val("");

			// clear the product_infos var
			product_infos = null;
		}

		/* function autocomplete */
		$(function() {
			// add click event on just created delete item link
			$('a.removeProductFromErpOrderLink').live('click', function() {

				var id = $(this).attr('id');
				var product_id = id.split('|')[1];


				//find the position of the product id in product_id array
				var position = jQuery.inArray(product_id, product_ids);
				if (position != -1)
				{
					//remove the id from the array
					product_ids.splice(position, 1);

					var input_id = $('input[name~="input_id_'+product_id+'"]');
					if (input_id != 'undefined')
						if (input_id.length > 0)
							product_ids_to_delete.push(product_id);

					// update the product_ids hidden field
					$('#product_ids').val(product_ids.join('|'));
					$('#product_ids_to_delete').val(product_ids_to_delete.join('|'));

					//remove the table row
					$(this).parents('tr:eq(0)').remove();
				}

				return false;
			});
                        
                        $('a.removeProductFromErpOrderLinkProduct').live('click', function() {

				var id = $(this).attr('id');
				var product_id = id.split('|')[1];


				//find the position of the product id in product_id array
				var position = jQuery.inArray(product_id, product_ids);
				if (position != -1)
				{
                                    //remove the id from the array
                                    product_ids.splice(position, 1);

                                    var $inputs = $('input[name^="input_id_'+product_id.split('_')[0]+'"]');
                                    $inputs.each(function(){
                                        //product_ids_to_delete.push($(this));
                                        $(this).parents('tr:eq(0)').remove();
                                    });
				}

				return false;
			});
                        
                        $('#products_in_erp_order').on('click', '.expandProduct', function(){
                            var idProduct = $(this).data('product'),
                            idProductAttribute = $(this).data('attribute'),
                            value,
                            inputArray = ['unit_price_te',
                            'discount_rate',
                            'tax_rate'];
                        inputArray.forEach(function (selector) {
                            value = $('[name="input_'+ selector +'_'+ idProduct +'_'+ idProductAttribute +'"').val();
                  
                            $('[name^="input_'+ selector +'_'+ idProduct +'"').each(function() {
                                $(this).val(value);
                    
                            });
                        });
                            
                            
                        });
                        
			btn_save = $('span[class~="process-icon-save"]').parent();

			btn_save.click(function() {
				$('#erp_order_form').submit();
			});

			// bind enter key event on search field
			$('#cur_product_name').bind('keypress', function(e) {
				var code = (e.keyCode ? e.keyCode : e.which);
				if(code == 13) { //Enter keycode
					e.stopPropagation();//Stop event propagation
					return false;
				}
			});

			// set autocomplete on search field
			$('#cur_product_name').autocomplete("ajax-tab.php", {
				delay: 100,
				minChars: 3,
				autoFill: true,
				max:100,
				matchContains: true,
				mustMatch:false,
				scroll:false,
				cacheLength:0,
	            dataType: 'json',
	            extraParams: {
	                id_supplier: '{$supplier_id|intval}',
	                id_currency: '{$currency->id|intval}',
					ajax : '1',
					controller : 'AdminErpSupplierOrders',
					token : '{$token|escape:"htmlall":"UTF-8"}',
					action : 'searchProduct'
	            },
	            parse: function(data) {
		            if (data == null || data == 'undefined')
			        	return [];
	            	var res = $.map(data, function(row) {
		            	// filter the data to chaeck if the product is already added to the order
	            		if (jQuery.inArray(row.id, product_ids) == -1)
		    				return {
		    					data: row,
		    					result: row.supplier_reference + ' - ' + row.name,
		    					value: row.id
		    				}
	    			});
	    			return res;
	            },
	    		formatItem: function(item) {
	    			return item.supplier_reference + ' - ' + item.name;
	    		}
	        }).result(function(event, item){
				product_infos = item;
	            if (typeof(ajax_running_timeout) !== 'undefined')
	            	clearTimeout(ajax_running_timeout);
			});
		});
	</script>
	{/if}
	<script type="text/javascript">
            function getHttpRequest()
            {
                var objet_ajax = null;
                if(window.XMLHttpRequest) // Firefox 
                    objet_ajax = new XMLHttpRequest(); 
                else if(window.ActiveXObject) // Internet Explorer 
                    objet_ajax = new ActiveXObject("Microsoft.XMLHTTP"); 
                else
                    alert("Your browser doesn't support AJAX. Please activate your javascript plugin !"); 

                return objet_ajax;
            }
            
            $(document).ready(function() {
                var ajax_url = "../modules/wic_erp/tools/ajax_supplier_order.php";

                    if ($(".datepicker").length > 0)
                            $(".datepicker").datepicker({
                                    prevText: '',
                                    nextText: '',
                                    dateFormat: 'yy-mm-dd'
                            });

                    $ajax_info = $('#display_ajax_info');
                    
                    var data = $(this).serializeArray();
                    data.push({literal}{{/literal}name: 'id_supplier', value: $("#id_supplier").val(){literal}}{/literal});
                    data.push({literal}{{/literal}name: 'id_currency', value: $("#id_currency").val(){literal}}{/literal});
                    data.push({literal}{{/literal}name: 'token', value: '{$token|escape:"htmlall":"UTF-8"}'{literal}}{/literal});
                            
                    $.getJSON(ajax_url, data, successHandler);
                        
                    $("#id_supplier").change(function () {
                        var data = $(this).serializeArray();
                        data.push({literal}{{/literal}name: 'id_supplier', value: $("#id_supplier").val(){literal}}{/literal});
                        data.push({literal}{{/literal}name: 'id_currency', value: $("#id_currency").val(){literal}}{/literal});
                        data.push({literal}{{/literal}name: 'token', value: '{$token|escape:"htmlall":"UTF-8"}'{literal}}{/literal});
                            
                        $.getJSON(ajax_url, data, successHandler);
                    });
                    
                    function successHandler(data) {
                        if (data.success) {
                            if(data.supplier_minimum_free_shipping > data.global_price)
                                $("#shipping_cost").val(data.shipping_price);
                            $ajax_info.fadeOut(450,function(){
                                $(this).empty();
                                $ajax_info.append($('<li/>').html("{l s='Supplier minimum amount (tax excl.): ' mod='wic_erp'} <b>"+data.supplier_minimum_price+"</b>"));
                                $ajax_info.append($('<li/>').html("{l s='Amount for free shipping (tax excl.): ' mod='wic_erp'} <b>"+data.supplier_minimum_free_shipping+"</b>"));
                                $ajax_info.append($('<li/>').html("{l s='Default shipping price (tax excl.): ' mod='wic_erp'} <b>"+data.default_shipping_price+"</b>"));
                                $ajax_info.append($('<li/>').html("{l s='Total order (tax excl.): ' mod='wic_erp'} <b>"+data.global_price+"</b>"));
                                $ajax_info.append($('<li/>').html("{l s='Quantity to purchase: ' mod='wic_erp'} <span class=\"badge badge-info\">"+data.global_quantity+"</span>"));
                                if (data.vat_exemption) {
                                    $ajax_info.append($('<li/>').html("<span class=\"label label-warning\">{l s='VAT exemption' mod='wic_erp'}</span>"));
                                }
                                $ajax_info.fadeIn();
                                });
                            
                        } else {
                            console.log(msg);
                        }
                    }
            });
	</script>
{/block}
