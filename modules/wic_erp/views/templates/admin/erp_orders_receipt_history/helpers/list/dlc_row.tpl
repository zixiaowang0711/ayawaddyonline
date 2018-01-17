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
<tr id="product_dlc_{$product_dlc_group_id|intval}_{$product_dlc_id|intval}_tr">
    <td>
        <div class="input-group">
            <input type="text" name="batch_number_{$product_dlc_group_id|intval}_{$product_dlc_id|intval}" placeholder="{l s='Batch number' mod='wic_erp'}" />
        </div>
    </td>
    <td>
        <div class="input-group">
            <input type="text" class="datepicker" name="dlc_{$product_dlc_group_id|intval}_{$product_dlc_id|intval}" id="dlc_{$product_dlc_group_id|intval}_{$product_dlc_id|intval}" placeholder="{l s='DLC' mod='wic_erp'}"/>
            <span class="input-group-addon">
                <i class="icon-calendar-empty"></i>
            </span>
        </div>
    </td>
    <td>
        <div class="input-group">
            <input type="text" class="datepicker" name="bbd_{$product_dlc_group_id|intval}_{$product_dlc_id|intval}" id="bbd_{$product_dlc_group_id|intval}_{$product_dlc_id|intval}" placeholder="{l s='BBD' mod='wic_erp'}"/>
            <span class="input-group-addon">
                <i class="icon-calendar-empty"></i>
            </span>
        </div>
    </td>
    <td>
        <div class="input-group">
            <input type="text" name="quantity_{$product_dlc_group_id|intval}_{$product_dlc_id|intval}" placeholder="{l s='Quantity' mod='wic_erp'}" onkeyup="checkSumProduct({$product_dlc_group_id|intval});" />
        </div>
    </td>
    <td>
        <div class="input-group">
            <label>{l s='Current stock' mod='wic_erp'}</label>&nbsp;
            <input type="checkbox" name="current_stock_{$product_dlc_group_id|intval}_{$product_dlc_id|intval}" onclick="disabledChecked({$product_dlc_group_id|intval}, {$product_dlc_id|intval});" />
        </div>
    </td>
    <td>
        <a class="btn btn-default" href="javascript:removeProductDlc({$product_dlc_group_id|intval},{$product_dlc_id|intval});">
            <i class="icon-remove text-danger"></i> {l s='Remove' mod='wic_erp'}
        </a>
    </td>
</tr>

<script type="text/javascript">
    $('.datepicker').datetimepicker({
                    beforeShow: function (input, inst) {
                    setTimeout(function () {
                        inst.dpDiv.css({
                            'z-index': 1031
                        });
                    }, 0);
                },
                    prevText: '',
                    nextText: '',
                    dateFormat: 'yy-mm-dd',
                    // Define a custom regional settings in order to use PrestaShop translation tools
                    currentText: currentText,
                    closeText:closeText,
                    ampm: false,
                    amNames: ['AM', 'A'],
                    pmNames: ['PM', 'P'],
                    timeFormat: 'hh:mm:ss tt',
                    timeSuffix: '',
                    timeOnlyTitle: timeOnlyTitle,
                    timeText: timeText,
                    hourText: hourText,
                    minuteText: minuteText,
            });
            $('.datepicker').mask('9999-99-99',{ldelim}placeholder:"YYYY-MM-DD"{rdelim});
</script>