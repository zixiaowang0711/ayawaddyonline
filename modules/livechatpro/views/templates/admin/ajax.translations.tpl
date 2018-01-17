{*
* ProQuality (c) All rights reserved.
*
* DISCLAIMER
*
* Do not edit, modify or copy this file.
* If you wish to customize it, contact us at addons4prestashop@gmail.com.
*
* @author    Andrei Cimpean (ProQuality) <addons4prestashop@gmail.com>
* @copyright 2015-2016 ProQuality
* @license   Do not edit, modify or copy this file
*}
{assign var=template value="`$smarty.current_dir`\\`$smarty.template`"}

{if !empty($sections)}
	<form name="lang_form" id="lang_form" action="#" method="POST">
	<table border="0" width="100%" class="table-striped table-hover">

	{foreach from=$sections key=key item=value}
                      
	      {if $value.section_expressions != '0'}
	      <tr style="font-weight: bold; height: 30px;">
	        <td>{literal}{{/literal}{$value.section_file_name|escape:'quotes':'UTF-8'}{literal}}{/literal} - ({$value.section_expressions|escape:'quotes':'UTF-8'}) {lang s='expressions'}</td>
	      </tr>
	      {foreach from=$value key=key2 item=value2}
	      {if is_numeric($key2)}
	      <tr style="height: 30px;">
	        <td>
	          <input type="hidden" value='{$value.section_file_name|escape:'quotes':'UTF-8'}' name="section_file_name[]" id="section_file_name_{$key2|escape:'quotes':'UTF-8'}">
	          <input type="hidden" value='{$value2.text_from_files|escape:'quotes':'UTF-8'}' name="section_text_from_files[]" id="section_text_from_files_{$key2|escape:'quotes':'UTF-8'}">
	          <table border="0" width="100%">
	            <tr>
	              <td width="50%">{$value2.text_from_files|escape:'quotes':'UTF-8'}</td>
	              <td width="5">&nbsp;=&nbsp;</td>
	              <td width="50%"><textarea name="language_variables[]" id="language_variables_{$key2|escape:'quotes':'UTF-8'}" class="form-control" style="width: 100%; height: 30px; {if empty($value2.text_from_lang_file)}background-color: #f2dede !important;{/if} resize:vertical;">{$value2.text_from_lang_file|escape:'quotes':'UTF-8'}{* if empty($value2.text_from_lang_file)}{$value2.text_from_files|escape:'quotes':'UTF-8'}{else}{$value2.text_from_lang_file|escape:'quotes':'UTF-8'}{/if *}</textarea></td>
	            </tr>
	          </table>
	        </td>
	      </tr>
	      {/if}                                                
	      {/foreach}     
	      
	      {/if}
	      
	{/foreach}

	</table>
</form>
	{/if}