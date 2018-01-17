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
    <p><button id="wic_erp_start_scan" class="btn btn-lg btn-default"><i class="icon-barcode"></i> {l s='Start scan' mod='wic_erp'}</button></p>
    <script language="javascript" type="text/javascript">
    var hideBtnHtml = "<i class='icon icon-eye-close' /> {l s='Hide complete product receipt' mod='wic_erp'}",
        showBtnHtml = "<i class='icon icon-eye-open' /> {l s='Show all product purchased' mod='wic_erp'}",
        resetAlertMsg = "{l s='All products received quantities will be set to 0. Continue ?' mod='wic_erp'}";
    var product_dlc_counters = new Array();
    var currentText = '{l s='Now' mod='wic_erp'}';
    var closeText = '{l s='Done' mod='wic_erp'}';
    var timeOnlyTitle = '{l s='Choose Time' mod='wic_erp'}';
    var timeText = '{l s='Time' mod='wic_erp'}';
    var hourText = '{l s='Hour' mod='wic_erp'}';
    var minuteText = '{l s='Minute' mod='wic_erp'}';
    {literal}
        $(document).ready(function() {
            $('#wic_erp_start_scan').click(function(){
                if(confirm(resetAlertMsg)) {
                    $(this).fadeOut(500, function(){
                        $('#erp_scan_product_form').slideDown();
                    });
                    $('.quantity_received_today').val(0);
                }
            });
            
            $('#erp_scan_product_form').submit(function(e){
                e.preventDefault();
                var reference = new RegExp($('#WIC_ERP_PRODUCT_REFERENCE').val()),
                    quantity = $('#WIC_ERP_PRODUCT_QUANTITY').val() || 1,
                    $infoArea = $('#wic_info-area'),
                    $infoAreaSuccess = $('#wic_info-area-success'),
                    validRef = false,
                    $tr,
                    $input;

                $('.erp_order_detail tbody tr').each(function(){
                    if (reference.test($(this).html())) {
                        $tr = $(this);
                        $input = $tr.find('.quantity_received_today');
                        $quantity_expected = $tr.next().find('.quantity_expected_'+$('#selectIdWarehouse').val());
                        if ($quantity_expected.length) {
                            if (parseInt($quantity_expected.text()) == 0) {
                                quantity = 0;
                            }
                        }
                        var oldQuantity = parseInt($input.val());
                        $input.val(oldQuantity + parseInt(quantity));
                        $quantityField = $tr.next().find('.quantity_'+$('#selectIdWarehouse').val()); 
                        if ($quantityField.length) {
                            $quantityField.text($input.val());
                        }
                        
                        $('html, body').animate({
                            scrollTop:$quantityField.offset().top
                        }, 'slow');
                        
                        validRef = true;
                    } 
                });
                $(this).trigger('reset');
                if (!validRef) 
                    $infoArea.slideDown().delay(2000).slideUp();
                else
                    $infoAreaSuccess.slideDown().delay(2000).slideUp();
            });

            var btnAttr = {
                'data-action': 'hide',
                id: 'hideCompleteBtn',
                class:'btn btn-sm btn-default',
                html: hideBtnHtml,
                type: 'button',
                click: function() {
                    var action = $(this).data('action');
                    $('#erp_order_detail > div > div.table-responsive.clearfix > table > tbody tr').each(function(){

                        if ($(this).find('.complete').length) {
                            if (action === 'hide')
                                $(this).fadeOut();
                        else 
                            $(this).fadeIn();
                    }
                    });
                    $(this).data('action',(action === 'hide') ? 'show': 'hide');
                    $(this).html((action === 'hide') ? showBtnHtml : hideBtnHtml);
                }
            };
            $('#erp_order_detail > div > div.panel-heading').append($('<button />', btnAttr));
            $('input.quantity_received_today').live('click', function() {
                    /* checks checkbox when the input is clicked */
                    $(this).parents('tr:eq(0)').find('input[type=checkbox]').attr('checked', true);
            });
            
            var date = new Date();
            var hours = date.getHours();
            if (hours < 10)
                    hours = "0" + hours;
            var mins = date.getMinutes();
            if (mins < 10)
                    mins = "0" + mins;
            var secs = date.getSeconds();
            if (secs < 10)
                    secs = "0" + secs;
                
            $(window).scroll(function(){
                if ($(this).scrollTop() > 135) {
                    $('#erp_scan_product_form').addClass('fixed');
                } else {
                    $('#erp_scan_product_form').removeClass('fixed');
                }
            });
        });
        
        function display_action_dlc(row_id, controller, token, action, params)
            {
                    var id = 'dlc_'+row_id;
                    var current_element = $('#details_'+id);
                    if (!current_element.data('dataMaped')) {
                            var ajax_params = {
                                    'id': row_id,
                                    'controller': controller,
                                    'token': token,
                                    'action': action,
                                    'ajax': true
                            };

                            $.each(params, function(k, v) {
                                    ajax_params[k] = v;
                            });

                            $.ajax({
                                    url: 'index.php',
                                    data: ajax_params,
                                    dataType: 'json',
                                    cache: false,
                                    context: current_element,
                                    async: false,
                                    success: function(data) {
                                            if (typeof(data.use_parent_structure) == 'undefined' || (data.use_parent_structure == true))
                                            {
                                                    if (current_element.parent().parent().hasClass('alt_row'))
                                                            var alt_row = true;
                                                    else
                                                            var alt_row = false;
                                                    current_element.parent().parent().after($('<tr class="details_'+id+' small '+(alt_row ? 'alt_row' : '')+'"></tr>')
                                                            .append($('<td style="border:none!important;" class="empty"></td>')
                                                            .attr('colspan', current_element.parent().parent().find('td').length)));
                                                    $.each(data.data, function(it, row)
                                                    {
                                                            var bg_color = ''; // Color
                                                            if (row.color)
                                                                    bg_color = 'style="background:' + row.color +';"';

                                                            var content = $('<tr class="action_details details_'+id+' '+(alt_row ? 'alt_row' : '')+'"></tr>');
                                                            content.append($('<td class="empty"></td>'));
                                                            var first = true;
                                                            var count = 0; // Number of non-empty collum
                                                            $.each(row, function(it)
                                                            {
                                                                    if(typeof(data.fields_display[it]) != 'undefined')
                                                                            count++;
                                                            });
                                                            $.each(data.fields_display, function(it, line)
                                                            {
                                                                    if (typeof(row[it]) == 'undefined')
                                                                    {
                                                                            if (first || count == 0)
                                                                                    content.append($('<td class="'+current_element.align+' empty"' + bg_color + '></td>'));
                                                                            else
                                                                                    content.append($('<td class="'+current_element.align+'"' + bg_color + '></td>'));
                                                                    }
                                                                    else
                                                                    {
                                                                            count--;
                                                                            if (first)
                                                                            {
                                                                                    first = false;
                                                                                    content.append($('<td class="'+current_element.align+' first"' + bg_color + '>'+row[it]+'</td>'));
                                                                            }
                                                                            else if (count == 0)
                                                                                    content.append($('<td class="'+current_element.align+' last"' + bg_color + '>'+row[it]+'</td>'));
                                                                            else
                                                                                    content.append($('<td class="'+current_element.align+' '+count+'"' + bg_color + '>'+row[it]+'</td>'));
                                                                    }
                                                            });
                                                            content.append($('<td class="empty"></td>'));
                                                            current_element.parent().parent().after(content.show('slow'));
                                                    });
                                            }
                                            else
                                            {
                                                    if (current_element.parent().parent().hasClass('alt_row'))
                                                            var content = $('<tr class="details_'+id+' alt_row"></tr>');
                                                    else
                                                            var content = $('<tr class="details_'+id+'"></tr>');
                                                    content.append($('<td style="border:none!important;">'+data.data+'</td>').attr('colspan', current_element.parent().parent().find('td').length));
                                                    current_element.parent().parent().after(content);
                                                    current_element.parent().parent().parent().find('.details_'+id).hide();
                                            }
                                            current_element.data('dataMaped', true);
                                            current_element.data('opened', false);

                                            if (typeof(initTableDnD) != 'undefined')
                                                    initTableDnD('.details_'+id+' table.tableDnD');
                                    }
                            });
                    }

                    if (current_element.data('opened'))
                    {
                            current_element.find('i.icon-collapse-top').attr('class', 'icon-collapse');
                            current_element.parent().parent().parent().find('.details_'+id).hide('fast');
                            current_element.data('opened', false);
                    }
                    else
                    {
                            current_element.find('i.icon-collapse').attr('class', 'icon-collapse-top');
                            current_element.parent().parent().parent().find('.details_'+id).show('fast');
                            current_element.data('opened', true);
                    }
            }
            
            function addProductDlcRow(product_dlc_group_id, token)
            {   
                if (typeof product_dlc_counters[product_dlc_group_id] == 'undefined') {
                    product_dlc_counters[product_dlc_group_id] = 0;
                }
                
                product_dlc_counters[product_dlc_group_id] += 1;
                $.get(
                    'ajax-tab.php',
                        {controller:'AdminErpSupplierOrders16',token:token,newProductDlcRow:1,product_dlc_group_id:product_dlc_group_id,product_dlc_id:product_dlc_counters[product_dlc_group_id]},
                        function(content) {
                            if (content != "")
                                $('#product_dlc_table_' + product_dlc_group_id + ' tbody').append(content);
                        }
                );
                $('#numberDlcRow_'+product_dlc_group_id).val(product_dlc_counters[product_dlc_group_id]);
            }
            
            function removeProductDlc(product_dlc_group_id, product_dlc_id)
            {
                $('#product_dlc_' + product_dlc_group_id + '_' + product_dlc_id + '_tr').remove();
            }
            
            function checkSumProduct(product_dlc_group_id)
            {
                var sum = 0;

                $("[name^='quantity_"+product_dlc_group_id+"']").each(function(){
                    if ($.isNumeric($(this).val()) === true) {
                        sum += parseInt($(this).val());
                    }
                });
                
                if ($('[name="adminstrativeReceipt_'+product_dlc_group_id+'"]:checked').length == 0) { 
                    $("[name='quantity_received_today_"+product_dlc_group_id+"']").val(parseInt(sum));
                }
            }
            
            function disabledChecked(product_dlc_group_id, $product_dlc_id)
            {
                $("[name^='current_stock_"+product_dlc_group_id+"']").each(function(){
                    if ($(this).attr('name') != 'current_stock_'+product_dlc_group_id+'_'+$product_dlc_id) {
                        $(this).prop('checked', false);
                    }
                });
            }
            
            function activeLines(product_dlc_group_id) {
                $('#details_dlc_'+product_dlc_group_id).parents('tr:eq(0)').find('input[type=checkbox]').prop('checked', true);
                if (typeof product_dlc_counters[product_dlc_group_id] == 'undefined' || product_dlc_counters[product_dlc_group_id] == 0) {
                    $("[name='quantity_received_today_"+product_dlc_group_id+"']").val(0);
                }
            }
    {/literal}
</script>
<div id="wic_info-area" class="alert alert-danger" style="display: none;">{l s='No product found for this reference in this order' mod='wic_erp'}</div>
<div id="wic_info-area-success" class="alert alert-success" style="display: none;">{l s='Product added successfully' mod='wic_erp'}</div>   
{/block}