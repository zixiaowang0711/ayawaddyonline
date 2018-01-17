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

<a onclick="display_action_details('{$id|intval}', '{$controller|escape:'htmlall':'UTF-8'}', '{$token|escape:'htmlall':'UTF-8'}', '{$params.action|escape:'htmlall':'UTF-8'}', {$json_params|escape}); return false;" id="details_{$params.action|escape:'htmlall':'UTF-8'}_{$id|intval}" title="{$action|escape:'htmlall':'UTF-8'}" class="pointer">
	<i class="icon-eye-open"></i> {html_entity_decode($action|escape:'htmlall':'UTF-8')}
</a>
{if isset($smarty.get.dlc) && $smarty.get.dlc}
    <a onclick="display_action_dlc('{$id|intval}', '{$controller|escape:'htmlall':'UTF-8'}', '{$token|escape:'htmlall':'UTF-8'}', '{$params.action|escape:'htmlall':'UTF-8'}', {ldelim}'display_product_dlc':1,'action':'dlc'{rdelim}); activeLines({$id|intval}); return false;" id="details_dlc_{$id|intval}" title="{l s='Add DLC / BBD' mod='wic_erp'}" class="pointer btn btn-default btn-dlc">
        <i class="icon-dashboard"></i> {l s='DLC/BBD' mod='wic_erp'}
    </a>
{/if}