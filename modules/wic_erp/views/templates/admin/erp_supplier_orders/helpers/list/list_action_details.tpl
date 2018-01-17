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

{if $table != 'erp_suppliers'}
    <a onclick="display_action_details('{$id|intval}', '{$controller|escape:'htmlall':'UTF-8'}', '{$token|escape:'htmlall':'UTF-8'}', '{$params.action|escape:'htmlall':'UTF-8'}', {$json_params|escape:'htmlall':'UTF-8'}); return false;" id="details_{$params.action|escape:'htmlall':'UTF-8'}_{$id|intval}" title="{$action|escape:'htmlall':'UTF-8'}" class="pointer btn btn-default">
        <i class="icon-eye-open"></i> {html_entity_decode($action|escape:'htmlall':'UTF-8')}
    </a>
{/if}