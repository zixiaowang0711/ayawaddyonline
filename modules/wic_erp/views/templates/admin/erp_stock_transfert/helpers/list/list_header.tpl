{*
* Module Inventory | Web in Color / AdminInventoryController - Last modified: 20 jan. 2015
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module to newer
* versions in the future. If you wish to customize our module for your
* needs please refer to http://www.webincolor.fr for more information.
*
*  @author    Web in Color <addons@webincolor.fr>
*  @copyright Copyright &copy; 2015, Web In Color
*  @license   http://www.webincolor.fr
*}

{extends file="helpers/list/list_header.tpl"}

{block name=override_header}
<div id="wic_wrapper">
        {html_entity_decode($stockTransfertWarehouseForm|escape:'htmlall':'UTF-8')}
        <div id="wic_info-area" class="alert alert-info" style="display: none;"></div>
        {html_entity_decode($stockTransfertProductForm|escape:'htmlall':'UTF-8')}
        <div class="wic_inventory-action">
            <p class="btn-group" role="group">
                <a class="btn btn-default btn-lg" href="{$ajaxUrl|escape:'html':'UTF-8'}&exportListCSV"><i class='icon-download'></i> {l s='Export to CSV' mod='wic_erp'}</a>
                <a class="btn btn-default btn-lg" href="{$ajaxUrl|escape:'html':'UTF-8'}&exportPDF"><i class='icon-download'></i> {l s='Export to PDF' mod='wic_erp'}</a>
            </p>
        </div>
</div>
    {if isset($ajaxUrl)}
        <script type="text/javascript">
            (function(){
                'use strict';
                var ajaxUrl = "{$ajaxUrl|escape:'html':'UTF-8'}",
                    translations = {
                        waitMsg: "{l s='Please wait' mod='wic_erp'}",
                        upToDate: "{l s='Quantities are up to date' mod='wic_erp'}",
                        needUpdate: "{l s='Quantities changed. You need to' mod='wic_erp'}",
                        update: "{l s='update' mod='wic_erp'}",
                        updateAlert: "{l s='Stock will be updated. Continue ?' mod='wic_erp'}",
                        autofillAlert: "{l s='All quantities equal to zero will be updated to theorical value. Continue ?' mod='wic_erp'}",
                        NaNAlert: "{l s='Please enter a numeric quantity' mod='wic_erp'}"
                    }
                {literal}
                    $(document).ready(function(){
                        WIC_ERP.init({ajaxUrl: ajaxUrl, translations: translations});
                        /******************
                        * Button handlers *
                        ******************/
                       $('#configuration_form').submit(WIC_ERP.formUpdate);
                    });
                })();
                {/literal}
        </script>
    {/if}
{/block}