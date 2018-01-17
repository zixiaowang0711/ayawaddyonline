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
<div class="panel col-lg-12">
    <div style="margin:10px;">
        <a href="javascript:addProductDlcRow({$id_detail_row|intval}, '{$token|escape:'htmlall':'UTF-8'}');" class="btn btn-default ">
            <i class="icon-plus-sign"></i> {l s='Add dlc row' mod='wic_erp'}
        </a>
    </div>
    <div style="margin:10px;" class="alert alert-danger">
        <input type="checkbox" name="adminstrativeReceipt_{$id_detail_row|intval}" value="1" id="adminstrativeReceipt_{$id_detail_row|intval}" /> <label>{l s='Administrative receipt' mod='wic_erp'}</label>
    </div>
    <div class="table-responsive-row clearfix">
        <table class="table" id="product_dlc_table_{$id_detail_row|intval}">
            <thead>
                <tr class="nodrag nodrop">
                    <th class=" left"><span class="title_box">{l s='Batch number' mod='wic_erp'}</span></th>
                    <th class=" left"><span class="title_box">{l s='DLC' mod='wic_erp'}</span></th>
                    <th class=" left"><span class="title_box">{l s='BBD' mod='wic_erp'}</span></th>
                    <th class=" left"><span class="title_box">{l s='Quantity' mod='wic_erp'}</span></th>
                    <th class=" left"><span class="title_box">{l s='is current stock ?' mod='wic_erp'}</span></th>
                    <th class=" left"><span class="title_box">{l s='Action' mod='wic_erp'}</span></th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div style="margin:10px;">
        <a href="javascript:addProductDlcRow({$id_detail_row|intval}, '{$token|escape:'htmlall':'UTF-8'}');" class="btn btn-default ">
            <i class="icon-plus-sign"></i> {l s='Add DLC row' mod='wic_erp'}
        </a>
    </div>
</div>
<input type="hidden" name="numberDlcRow_{$id_detail_row|intval}" value="" id="numberDlcRow_{$id_detail_row|intval}" />
