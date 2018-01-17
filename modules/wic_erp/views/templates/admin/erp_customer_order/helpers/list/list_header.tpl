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
<div class="col-lg-12">
<ul class="nav nav-tabs" role="tablist">
  <li {if $tab_selected == 'complete'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=complete">{l s='Complete orders' mod='wic_erp'} <span class="badge">{$count_complete|intval}</span></a></li>
  <li {if $tab_selected == 'pending'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=pending">{l s='Pending orders' mod='wic_erp'} <span class="badge">{$count_pending|intval}</span></a></li>
  <li {if $tab_selected == 'ignored'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=ignored">{l s='ignored orders' mod='wic_erp'} <span class="badge">{$count_ignored|intval}</span></a></li>
  <li {if $tab_selected == 'preparation'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=preparation">{l s='Preparation in progress' mod='wic_erp'} <span class="badge">{$count_preparation|intval}</span></a></li>
</ul>
</div>
  <div class="clearfix"></div>
{/block}