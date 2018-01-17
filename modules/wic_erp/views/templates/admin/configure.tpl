{*
* 2007-2014 PrestaShop
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
*  @author    PrestaShop SA <contact@prestashop.com>
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*}

{html_entity_decode($update_msg|escape:'htmlall':'UTF-8')}

<!-- Integrate specific JS vars -->
<script>
    {literal}
    function DisplayField(field, showField) {
        var valField = $('[name="'+field+'"]').val();
        if (valField == "normal" && showField == "stockManagement") {
            $("#"+showField).parents('div.form-group').show("normal");
            $("#"+showField).parents('div.margin-form').show("normal");
            $("#"+showField).parents('div.margin-form').prev('label').show("normal");
        }
        else if (valField != "normal" && showField == "stockManagement") {
            $("#"+showField).parents('div.form-group').hide("normal");
            $("#"+showField).parents('div.margin-form').hide("normal");
            $("#"+showField).parents('div.margin-form').prev('label').hide("normal");
        }
        else if (valField == "normal" && showField == "stockMethod") {
            $("#"+showField).show("normal");
            $("#"+showField).parents('div.form-group').show("normal");
            $("#"+showField).parents('div.margin-form').show("normal");
            $("#"+showField).parents('div.margin-form').prev('label').show("normal");
        }
        else if (valField == "normal" && showField == "productManagement") {
            $("#"+showField).parents('div[id^="fieldset"]').show("normal");
            $("#"+showField).parents('fieldset').show("normal");
            $("#"+showField).parents('fieldset').prev('label').show("normal");
        }
        else if (valField != "normal" && showField == "productManagement") {
            $("#"+showField).parents('div[id^="fieldset"]').hide("normal");
            $("#"+showField).parents('fieldset').hide("normal");
            $("#"+showField).parents('fieldset').prev('label').hide("normal");
        }
        else if (valField == "normal" && showField == "supplierManagement") {
            $("#"+showField).parents('div.form-group').show("normal");
            $("#"+showField).parents('div.margin-form').show("normal");
            $("#"+showField).parents('div.margin-form').prev('label').show("normal");
            $('[name="WIC_ERP_RECALCULATE_BY_SUPPLIER"]').parents('div.form-group').show("normal");
            $('[name="WIC_ERP_RECALCULATE_BY_SUPPLIER"]').parents('div.margin-form').show("normal");
            $('[name="WIC_ERP_RECALCULATE_BY_SUPPLIER"]').parents('div.margin-form').prev('label').show("normal");
        }
        else if (valField != "normal" && showField == "supplierManagement") {
            $("#"+showField).parents('div.form-group').hide("normal");
            $("#"+showField).parents('div.margin-form').hide("normal");
            $("#"+showField).parents('div.margin-form').prev('label').hide("normal");
            $('[name="WIC_ERP_RECALCULATE_BY_SUPPLIER"]').parents('div.form-group').hide("normal");
            $('[name="WIC_ERP_RECALCULATE_BY_SUPPLIER"]').parents('div.margin-form').hide("normal");
            $('[name="WIC_ERP_RECALCULATE_BY_SUPPLIER"]').parents('div.margin-form').prev('label').hide("normal");
        }
        else if (valField == "normal" && showField == "updateManagement") {
            if ($('[name="updateSuppliers"]').attr('type') != 'hidden') { 
                $('[name="updateSuppliers"]').parents('div[id^="fieldset"]').show("normal");
                $("#"+showField).parents('fieldset').show("normal");
                $("#"+showField).parents('fieldset').prev('label').show("normal");
            }
        }
        else if (valField != "normal" && showField == "updateManagement") {
            $('[name="updateSuppliers"]').parents('div[id^="fieldset"]').hide("normal");
            $("#"+showField).parents('fieldset').hide("normal");
            $("#"+showField).parents('fieldset').prev('label').hide("normal");
        }
        else {
            $("#"+showField).parents('div.form-group').hide("normal");
            $("#"+showField).parents('div.margin-form').hide("normal");
            $("#"+showField).parents('div.margin-form').prev('label').hide("normal")
        }
    }
    {/literal}
</script>    
<script>
    var stepsTranslations = {html_entity_decode($steps|escape:'htmlall':'UTF-8')};
</script>
<script src="{$module_dir|escape:'htmlall':'UTF-8'}views/js/wic_erp.admin.js"></script>
<link type="text/css" rel="stylesheet" href="{$module_dir|escape:'htmlall':'UTF-8'}views/css/admin.css" />
<!-- Modal content -->
<div class="modal fade" id="wic_erp-modal" tabindex="-1" role="dialog" aria-labelledby="wic_erp-modalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only">Close</span>
                </button>
                <h2 class="modal-title" id="wic_erp-modalLabel">
                    <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/logo_webincolor_L260.png" height="45"/>&nbsp;{l s='My Easy ERP' mod='wic_erp'} <small>v{$version|escape:'htmlall':'UTF-8'}</small>
                </h2>
            </div>
            <div class="modal-body">
                <h3 class="modal-title">{l s='Setup progress:' mod='wic_erp'}<em class="pleasewait">{l s='Please wait ...' mod='wic_erp'}</em></h3>
                <div class="progress">
                    <div id="wic_erp-pb" class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
                <ul id="wic_erp-stepsUl" class="list-inline"></ul>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">{l s='Close' mod='wic_erp'}</button>
            </div>
        </div>
    </div>
</div>
<!-- Module content -->
<div id="modulecontent" class="clearfix" role="{$is_submit|escape:'htmlall':'UTF-8'}">
    <!-- Nav tabs -->
    <div class="col-lg-2">
        <div class="list-group">
            <a href="#informations" class="list-group-item active" data-toggle="tab">
                <i class="icon-book"></i> {l s='Informations' mod='wic_erp'}
            </a>
            <a href="#configuration" class="list-group-item" data-toggle="tab">
                <i class="icon-briefcase"></i> {l s='Configuration' mod='wic_erp'}
            </a>
            <a href="#support" class="list-group-item" data-toggle="tab"><i class="icon-envelope"></i> {l s='Support' mod='wic_erp'}</a>
            <a href="#" class="list-group-item" data-toggle="tab"><i class="icon-info-sign"></i> {l s='Version' mod='wic_erp'} {$version|escape:'htmlall':'UTF-8'}</a>
        </div>
        <div style="text-align:center; margin-top:-15px; margin-right: 5px;">
            <img src="{$module_dir|escape:'htmlall':'UTF-8'}views/img/logo_webincolor_L260.png" alt="Web in Color" height="35" />
        </div>
    </div>
    <!-- Tab panes -->
    <div class="tab-content col-lg-10">
            <div class="tab-pane active panel" id="informations">
                {include file="./tabs/informations.tpl"}
            </div>
            <div class="tab-pane panel" id="configuration">
                {include file="./tabs/configuration.tpl"}
            </div>
            <div class="tab-pane panel" id="support">
                {include file="./tabs/support.tpl"}
            </div>
    </div>
</div>