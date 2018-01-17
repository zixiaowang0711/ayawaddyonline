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

{if version_compare($smarty.const._PS_VERSION_,'1.6','<')}
{if !$simple_header}

	<script type="text/javascript">
		$(document).ready(function() {
			$('table.{$list_id|escape:'htmlall':'UTF-8'} .filter').keypress(function(event){
				formSubmit(event, 'submitFilterButton{$list_id|escape:'htmlall':'UTF-8'}')
			})
		});
	</script>
	{* Display column names and arrows for ordering (ASC, DESC) *}
	{if $is_order_position}
		<script type="text/javascript" src="../js/jquery/plugins/jquery.tablednd.js"></script>
		<script type="text/javascript">
			var token = "{$token|escape:'htmlall':'UTF-8'}";
			var come_from = "{$list_id|escape:'htmlall':'UTF-8'}"";
			var alternate = {if $order_way == 'DESC'}'1'{else}'0'{/if};
		</script>
		<script type="text/javascript" src="../js/admin-dnd.js"></script>
	{/if}

	<script type="text/javascript">
		$(function() {
			if ($("table.{$list_id|escape:'htmlall':'UTF-8'} .datepicker").length > 0)
				$("table.{$list_id|escape:'htmlall':'UTF-8'} .datepicker").datepicker({
					prevText: '',
					nextText: '',
					dateFormat: 'yy-mm-dd'
				});
		});
	</script>


{/if}{* End if simple_header *}

{if $show_toolbar}
	{include file="toolbar.tpl" toolbar_btn=$toolbar_btn toolbar_scroll=$toolbar_scroll title=$title}
{/if}

{if !$simple_header}
	<div class="leadin">{block name="leadin"}{/block}</div>
{/if}
{block name="override_header"}
    
    {if !isset($smarty.get.id_erp_order) && $identifier != 'id_erp_suppliers'} 
	<div class="col-lg-12">
		<ul class="nav nav-tabs" role="tablist">
			<li {if $tab_selected == 'all' || !isset($tab_selected)}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=all">{l s='All orders' mod='wic_erp'} <span class="badge">{$count_all|intval}</span></a></li>
  			<li {if $tab_selected == 'progress'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=progress">{l s='Creation in progress' mod='wic_erp'} <span class="badge">{$count_progress|intval}</span></a></li>
  			<li {if $tab_selected == 'validated'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=validated">{l s='Order validated' mod='wic_erp'} <span class="badge">{$count_validated|intval}</span></a></li>
  			<li {if $tab_selected == 'pending'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=pending">{l s='Pending receipt' mod='wic_erp'} <span class="badge">{$count_pending|intval}</span></a></li>
			<li {if $tab_selected == 'received_in_part'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=received_in_part">{l s='Order received in part' mod='wic_erp'} <span class="badge">{$count_received_in_part|intval}</span></a></li>
			<li {if $tab_selected == 'complete'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=complete">{l s='Order received completely' mod='wic_erp'} <span class="badge">{$count_complete|intval}</span></a></li>
			<li {if $tab_selected == 'canceled'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=canceled">{l s='Order canceled' mod='wic_erp'} <span class="badge">{$count_cancelled|intval}</span></a></li>
		</ul>
	</div>
    {/if}
{/block}
<div class="clearfix"></div>

{hook h='displayAdminListBefore'}
{if isset($name_controller)}
	{capture name=hookName assign=hookName}display{$name_controller|ucfirst|escape:'htmlall':'UTF-8'}ListBefore{/capture}
	{hook h=$hookName}
{elseif isset($smarty.get.controller)}
	{capture name=hookName assign=hookName}display{$smarty.get.controller|ucfirst|htmlentities|escape:'htmlall':'UTF-8'}ListBefore{/capture}
	{hook h=$hookName}
{/if}


{if !$simple_header}
<form method="post" action="{$action|escape:'html':'UTF-8'}" class="form">

	{block name="override_form_extra"}{/block}

	<input type="hidden" id="submitFilter{$list_id|escape:'htmlall':'UTF-8'}" name="submitFilter{$list_id|escape:'htmlall':'UTF-8'}" value="0"/>
{/if}
	<table class="table_grid" name="list_table">
		{if !$simple_header}
			<tr>
				<td style="vertical-align: bottom;">
					<span style="float: left;">
						{if $page > 1}
							<input type="image" src="../img/admin/list-prev2.gif" onclick="getE('submitFilter{$list_id|escape:'htmlall':'UTF-8'}').value=1"/>&nbsp;
							<input type="image" src="../img/admin/list-prev.gif" onclick="getE('submitFilter{$list_id|escape:'htmlall':'UTF-8'}').value={$page - 1|intval}"/>
						{/if}
						{l s='Page' mod='wic_erp'} <b>{$page|intval}</b> / {$total_pages|intval}
						{if $page < $total_pages}
							<input type="image" src="../img/admin/list-next.gif" onclick="getE('submitFilter{$list_id|escape:'htmlall':'UTF-8'}').value={$page + 1|intval}"/>&nbsp;
							<input type="image" src="../img/admin/list-next2.gif" onclick="getE('submitFilter{$list_id|escape:'htmlall':'UTF-8'}').value={$total_pages|intval}"/>
						{/if}
						| {l s='Display' mod='wic_erp'}
						<select name="{$list_id|escape:'htmlall':'UTF-8'}_pagination" onchange="submit()">
							{* Choose number of results per page *}
							{foreach $pagination AS $value}
								<option value="{$value|intval}"{if $selected_pagination == $value && $selected_pagination != NULL} selected="selected"{elseif $selected_pagination == NULL && $value == $pagination[1]} selected="selected2"{/if}>{$value|intval}</option>
							{/foreach}
						</select>
						/ {$list_total|intval} {l s='result(s)' mod='wic_erp'}
					</span>
					<span style="float: right;">
						<input type="submit" id="submitFilterButton{$list_id|escape:'htmlall':'UTF-8'}" name="submitFilter" value="{l s='Filter' mod='wic_erp'}" class="button" />					
						<input type="submit" name="submitReset{$list_id|escape:'htmlall':'UTF-8'}" value="{l s='Reset' mod='wic_erp'}" class="button" />
					</span>
					<span class="clear"></span>
				</td>
			</tr>
		{/if}
		<tr>
			<td{if $simple_header} style="border:none;"{/if}>
				<table
				{if $table_id} id="{$table_id|escape:'htmlall':'UTF-8'}"{/if}
				class="table {if $table_dnd}tableDnD{/if} {$list_id|escape:'htmlall':'UTF-8'}"
				cellpadding="0" cellspacing="0"
				style="width: 100%; margin-bottom:10px;">
					<col width="10px" />
					{foreach $fields_display AS $key => $params}
						<col {if isset($params.width) && $params.width != 'auto'}width="{$params.width|escape:'htmlall':'UTF-8'}px"{/if}/>
					{/foreach}
					{if $shop_link_type}
						<col width="80px" />
					{/if}
					{if $has_actions}
						<col width="52px" />
					{/if}
					<thead>
						<tr class="nodrag nodrop" style="height: 40px">
							<th class="center">
								{if $has_bulk_actions}
									<input type="checkbox" name="checkme" class="noborder" onclick="checkDelBoxes(this.form, '{$list_id|escape:'html':'UTF-8'}Box[]', this.checked)" />
								{/if}
							</th>
							{foreach $fields_display AS $key => $params}
								<th {if isset($params.align)} class="{$params.align|escape:'htmlall':'UTF-8'}"{/if}>
									{if isset($params.hint)}<span class="hint" name="help_box">{$params.hint|escape:'htmlall':'UTF-8'}<span class="hint-pointer">&nbsp;</span></span>{/if}
									<span class="title_box">
										{html_entity_decode($params.title|escape:'htmlall':'UTF-8')}
									</span>
									{if (!isset($params.orderby) || $params.orderby) && !$simple_header}
										<br />
										<a href="{$currentIndex|escape:'htmlall':'UTF-8'}&{$list_id|escape:'htmlall':'UTF-8'}Orderby={$key|urlencode|escape:'htmlall':'UTF-8'}&{$list_id|escape:'htmlall':'UTF-8'}Orderway=desc&token={$token|escape:'htmlall':'UTF-8'}{if isset($smarty.get.$identifier)}&{$identifier|escape:'htmlall':'UTF-8'}={$smarty.get.$identifier|intval}{/if}">
										<img border="0" src="../img/admin/down{if isset($order_by) && ($key == $order_by) && ($order_way == 'DESC')}_d{/if}.gif" /></a>
										<a href="{$currentIndex|escape:'htmlall':'UTF-8'}&{$list_id|escape:'htmlall':'UTF-8'}Orderby={$key|urlencode|escape:'htmlall':'UTF-8'}&{$list_id|escape:'htmlall':'UTF-8'}Orderway=asc&token={$token|escape:'htmlall':'UTF-8'}{if isset($smarty.get.$identifier)}&{$identifier|escape:'htmlall':'UTF-8'}={$smarty.get.$identifier|intval}{/if}">
										<img border="0" src="../img/admin/up{if isset($order_by) && ($key == $order_by) && ($order_way == 'ASC')}_d{/if}.gif" /></a>
									{elseif !$simple_header}
										<br />&nbsp;
									{/if}
								</th>
							{/foreach}
							{if $shop_link_type}
								<th>
									{if $shop_link_type == 'shop'}
										{l s='Shop' mod='wic_erp'}
									{else}
										{l s='Group shop' mod='wic_erp'}
									{/if}
									<br />&nbsp;
								</th>
							{/if}
							{if $has_actions}
								<th class="center">{l s='Actions' mod='wic_erp'}{if !$simple_header}<br />&nbsp;{/if}</th>
							{/if}
							<th>&nbsp;</th>
						</tr>
 						{if !$simple_header}
						<tr class="nodrag nodrop filter {if $row_hover}row_hover{/if}" style="height: 35px;">
							<td class="center">
								{if $has_bulk_actions}
									--
								{/if}
							</td>

							{* Filters (input, select, date or bool) *}
							{foreach $fields_display AS $key => $params}
								<td {if isset($params.align)} class="{$params.align|escape:'htmlall':'UTF-8'}" {/if}>
									{if isset($params.search) && !$params.search}
										--
									{else}
										{if $params.type == 'bool'}
											<select onchange="$('#submitFilterButton{$list_id|escape:'htmlall':'UTF-8'}').focus();$('#submitFilterButton{$list_id|escape:'htmlall':'UTF-8'}').click();" name="{$list_id|escape:'htmlall':'UTF-8'}Filter_{$key|escape:'htmlall':'UTF-8'}">
												<option value="">-</option>
												<option value="1"{if $params.value == 1} selected="selected"{/if}>{l s='Yes' mod='wic_erp'}</option>
												<option value="0"{if $params.value == 0 && $params.value != ''} selected="selected"{/if}>{l s='No' mod='wic_erp'}</option>
											</select>
										{elseif $params.type == 'date' || $params.type == 'datetime'}
											{l s='From' mod='wic_erp'} <input type="text" class="filter datepicker" id="{$params.id_date|escape:'htmlall':'UTF-8'}_0" name="{$params.name_date|escape:'htmlall':'UTF-8'}[0]" value="{if isset($params.value.0)}{$params.value.0|escape:'htmlall':'UTF-8'}{/if}"{if isset($params.width)} style="width:70px"{/if}/><br />
											{l s='To' mod='wic_erp'} <input type="text" class="filter datepicker" id="{$params.id_date|escape:'htmlall':'UTF-8'}_1" name="{$params.name_date|escape:'htmlall':'UTF-8'}[1]" value="{if isset($params.value.1)}{$params.value.1|escape:'htmlall':'UTF-8'}{/if}"{if isset($params.width)} style="width:70px"{/if}/>
										{elseif $params.type == 'select'}
											{if isset($params.filter_key)}
												<select onchange="$('#submitFilterButton{$list_id|escape:'htmlall':'UTF-8'}').focus();$('#submitFilterButton{$list_id|escape:'htmlall':'UTF-8'}').click();" name="{$list_id|escape:'htmlall':'UTF-8'}Filter_{$params.filter_key|escape:'htmlall':'UTF-8'}" {if isset($params.width)} style="width:{$params.width|escape:'htmlall':'UTF-8'}px"{/if}>
													<option value=""{if $params.value == ''} selected="selected"{/if}>-</option>
													{if isset($params.list) && is_array($params.list)}
														{foreach $params.list AS $option_value => $option_display}
															<option value="{$option_value|escape:'htmlall':'UTF-8'}" {if $params.value != '' && ($option_display == $params.value ||  $option_value == $params.value)} selected="selected"{/if}>{$option_display|escape:'htmlall':'UTF-8'}</option>
														{/foreach}
													{/if}
												</select>
											{/if}
										{else}
											<input type="text" class="filter" name="{$list_id|escape:'htmlall':'UTF-8'}Filter_{if isset($params.filter_key)}{$params.filter_key|escape:'htmlall':'UTF-8'}{else}{$key|escape:'htmlall':'UTF-8'}{/if}" value="{$params.value|escape:'htmlall':'UTF-8'}" {if isset($params.width) && $params.width != 'auto'} style="width:{$params.width|escape:'htmlall':'UTF-8'}px"{else}style="width:95%"{/if} />
										{/if}
									{/if}
								</td>
							{/foreach}

							{if $shop_link_type}
								<td>--</td>
							{/if}
							{if $has_actions}
								<td class="center">--</td>
							{/if}
							<td>--</td>
							</tr>
						{/if}
						</thead>
{else}
{if $ajax}
	<script type="text/javascript">
		$(function () {
			$(".ajax_table_link").click(function () {
				var link = $(this);
				$.post($(this).attr('href'), function (data) {
					if (data.success == 1) {
						showSuccessMessage(data.text);
						if (link.hasClass('action-disabled')){
							link.removeClass('action-disabled').addClass('action-enabled');
						} else {
							link.removeClass('action-enabled').addClass('action-disabled');
						}
						link.children().each(function () {
							if ($(this).hasClass('hidden')) {
								$(this).removeClass('hidden');
							} else {
								$(this).addClass('hidden');
							}
						});
					} else {
						showErrorMessage(data.text);
					}
				}, 'json');
				return false;
			});
		});
	</script>
{/if}
{if !$simple_header}
	{* Display column names and arrows for ordering (ASC, DESC) *}
	{if $is_order_position}
		<script type="text/javascript" src="../js/jquery/plugins/jquery.tablednd.js"></script>
		<script type="text/javascript">
			var come_from = '{$list_id|addslashes|escape:'htmlall':'UTF-8'}';
			var alternate = {if $order_way == 'DESC'}'1'{else}'0'{/if};
		</script>
		<script type="text/javascript" src="../js/admin-dnd.js"></script>
	{/if}
	<script type="text/javascript">
		$(function() {
			$("table.{$list_id|escape:'htmlall':'UTF-8'} .filter").keypress(function(e){
				var key = (e.keyCode ? e.keyCode : e.which);
				if (key == 13)
				{
					e.preventDefault();
					formSubmit(event, 'submitFilterButton{$list_id|escape:'htmlall':'UTF-8'}');
				}
			})
			$("#submitFilterButton{$list_id|escape:'htmlall':'UTF-8'}").click(function() {
				$("#submitFilter{$list_id|escape:'htmlall':'UTF-8'}").val(1);
			});
			if ($("table.{$list_id|escape:'htmlall':'UTF-8'} .datepicker").length > 0) {
				$("table.{$list_id|escape:'htmlall':'UTF-8'} .datepicker").datepicker({
					prevText: '',
					nextText: '',
					altFormat: 'yy-mm-dd'
				});
			}
		});
	</script>
{/if}

{if !$simple_header}
	<div class="leadin">
		{block name="leadin"}{/block}
	</div>
{/if}

{block name="override_header"}
    {if !isset($smarty.get.id_erp_order) && $identifier != 'id_erp_suppliers'} 
	<div class="col-lg-12">
		<ul class="nav nav-tabs" role="tablist">
			<li {if $tab_selected == 'all' || !isset($tab_selected)}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=all">{l s='All orders' mod='wic_erp'} <span class="badge">{$count_all|intval}</span></a></li>
  			<li {if $tab_selected == 'progress'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=progress">{l s='Creation in progress' mod='wic_erp'} <span class="badge">{$count_progress|intval}</span></a></li>
  			<li {if $tab_selected == 'validated'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=validated">{l s='Order validated' mod='wic_erp'} <span class="badge">{$count_validated|intval}</span></a></li>
  			<li {if $tab_selected == 'pending'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=pending">{l s='Pending receipt' mod='wic_erp'} <span class="badge">{$count_pending|intval}</span></a></li>
			<li {if $tab_selected == 'received_in_part'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=received_in_part">{l s='Order received in part' mod='wic_erp'} <span class="badge">{$count_received_in_part|intval}</span></a></li>
			<li {if $tab_selected == 'complete'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=complete">{l s='Order received completely' mod='wic_erp'} <span class="badge">{$count_complete|intval}</span></a></li>
			<li {if $tab_selected == 'canceled'}class="active"{/if}><a href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;token={$token|escape:'htmlall':'UTF-8'}&amp;order_status=canceled">{l s='Order canceled' mod='wic_erp'} <span class="badge">{$count_cancelled|intval}</span></a></li>
		</ul>
	</div>
    {/if}
{/block}
<div class="clearfix"></div>
{hook h='displayAdminListBefore'}

{if isset($name_controller)}
	{capture name=hookName assign=hookName}display{$name_controller|ucfirst|escape:'htmlall':'UTF-8'}ListBefore{/capture}
	{hook h=$hookName}
{elseif isset($smarty.get.controller)}
	{capture name=hookName assign=hookName}display{$smarty.get.controller|ucfirst|htmlentities|escape:'htmlall':'UTF-8'}ListBefore{/capture}
	{hook h=$hookName}
{/if}

<div class="alert alert-warning" id="{$list_id|escape:'htmlall':'UTF-8'}-empty-filters-alert" style="display:none;">{l s='Please fill at least one field to perform a search in this list.' mod='wic_erp'}</div>

{block name="startForm"}
	<form method="post" action="{$action|escape:'htmlall':'UTF-8'}" class="form-horizontal clearfix" id="{$list_id|escape:'htmlall':'UTF-8'}">
{/block}

{if !$simple_header}
	<input type="hidden" id="submitFilter{$list_id|escape:'htmlall':'UTF-8'}" name="submitFilter{$list_id|escape:'htmlall':'UTF-8'}" value="0"/>
	{block name="override_form_extra"}{/block}
	<div class="panel col-lg-12">
		<div class="panel-heading">
			{if isset($icon)}<i class="{$icon|escape:'htmlall':'UTF-8'}"></i> {/if}{if is_array($title)}{html_entity_decode($title|end|escape:'htmlall':'UTF-8')}{else}{html_entity_decode($title|escape:'htmlall':'UTF-8')}{/if}
			{if isset($toolbar_btn) && count($toolbar_btn) >0}
				<span class="badge">{$list_total|intval}</span>
				<span class="panel-heading-action">
				{foreach from=$toolbar_btn item=btn key=k}
					{if $k != 'modules-list' && $k != 'back'}
						<a id="desc-{$table|escape:'htmlall':'UTF-8'}-{if isset($btn.imgclass)}{$btn.imgclass|escape:'htmlall':'UTF-8'}{else}{$k|escape:'htmlall':'UTF-8'}{/if}" class="list-toolbar-btn" {if isset($btn.href)}href="{$btn.href|escape:'htmlall':'UTF-8'}"{/if} {if isset($btn.target) && $btn.target}target="_blank"{/if}{if isset($btn.js) && $btn.js}onclick="{$btn.js|escape:'htmlall':'UTF-8'}"{/if}>
							<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{$btn.desc|escape:'html':'UTF-8'}" data-html="true" data-placement="left">
								<i class="process-icon-{if isset($btn.imgclass)}{$btn.imgclass|escape:'htmlall':'UTF-8'}{else}{$k|escape:'htmlall':'UTF-8'}{/if} {if isset($btn.class)}{$btn.class|escape:'htmlall':'UTF-8'}{/if}" ></i>
							</span>
						</a>
					{/if}
				{/foreach}
					<a id="desc-{$table|escape:'htmlall':'UTF-8'}-refresh" class="list-toolbar-btn" href="javascript:location.reload();">
						<span title="" data-toggle="tooltip" class="label-tooltip" data-original-title="{l s='Refresh list' mod='wic_erp'}" data-html="true" data-placement="left">
							<i class="process-icon-refresh" ></i>
						</span>
					</a>
				</span>
			{/if}
		</div>
		{if $show_toolbar}
			<script language="javascript" type="text/javascript">
				//<![CDATA[
				var submited = false;
				$(function() {
					//get reference on save link
					btn_save = $('i[class~="process-icon-save"]').parent();
					//get reference on form submit button
					btn_submit = $("#{$table|escape:'htmlall':'UTF-8'}_form_submit_btn");
					if (btn_save.length > 0 && btn_submit.length > 0) {
						//get reference on save and stay link
						btn_save_and_stay = $('i[class~="process-icon-save-and-stay"]').parent();
						//get reference on current save link label
						lbl_save = $("#desc-{$table|escape:'htmlall':'UTF-8'}-save div");
						//override save link label with submit button value
						if (btn_submit.val().length > 0) {
							lbl_save.html(btn_submit.attr("value"));
						}
						if (btn_save_and_stay.length > 0) {
							//get reference on current save link label
							lbl_save_and_stay = $("#desc-{$table|escape:'htmlall':'UTF-8'}-save-and-stay div");
							//override save and stay link label with submit button value
							if (btn_submit.val().length > 0 && lbl_save_and_stay && !lbl_save_and_stay.hasClass('locked')) {
								lbl_save_and_stay.html(btn_submit.val() + " {l s='and stay' mod='wic_erp'} ");
							}
						}
						//hide standard submit button
						btn_submit.hide();
						//bind enter key press to validate form
						$("#{$table|escape:'htmlall':'UTF-8'}_form").keypress(function (e) {
							if (e.which == 13 && e.target.localName != 'textarea') {
								$("#desc-{$table|escape:'htmlall':'UTF-8'}-save").click();
							}
						});
						//submit the form
						{block name=formSubmit}
							btn_save.click(function() {
								// Avoid double click
								if (submited) {
									return false;
								}
								submited = true;
								//add hidden input to emulate submit button click when posting the form -> field name posted
								btn_submit.before('<input type="hidden" name="'+btn_submit.attr("name")+'" value="1" />');
								$("#{$table|escape:'htmlall':'UTF-8'}_form").submit();
								return false;
							});
							if (btn_save_and_stay) {
								btn_save_and_stay.click(function() {
									//add hidden input to emulate submit button click when posting the form -> field name posted
									btn_submit.before('<input type="hidden" name="'+btn_submit.attr("name")+'AndStay" value="1" />');
									$("#{$table|escape:'htmlall':'UTF-8'}_form").submit();
									return false;
								});
							}
						{/block}
					}
				});
				//]]>
			</script>
		{/if}
{elseif $simple_header}
	<div class="panel col-lg-12">
		{if isset($title)}<h3>{if isset($icon)}<i class="{$icon|escape:'htmlall':'UTF-8'}"></i> {/if}{if is_array($title)}{html_entity_decode($title|end|escape:'htmlall':'UTF-8')}{else}{html_entity_decode($title|escape:'htmlall':'UTF-8')}{/if}</h3>{/if}
{/if}
	{block name="preTable"}{/block}
	<div class="table-responsive clearfix{if isset($use_overflow) && $use_overflow} overflow-y{/if}">
		<table {if $table_id} id="{$table_id|escape:'htmlall':'UTF-8'}"{/if} class="table {if $table_dnd}tableDnD{/if} {$table|escape:'htmlall':'UTF-8'}" >
			<thead>
				<tr class="nodrag nodrop">
					{if $bulk_actions && $has_bulk_actions}
						<th class="center fixed-width-xs"></th>
					{/if}
					{foreach $fields_display AS $key => $params}
					<th class="{if isset($params.class)}{$params.class|escape:'htmlall':'UTF-8'}{/if}{if isset($params.align)} {$params.align|escape:'htmlall':'UTF-8'}{/if}">
						<span class="title_box {if isset($order_by) && ($key == $order_by)} active{/if}">
							{if isset($params.hint)}
								<span class="label-tooltip" data-toggle="tooltip"
									title="
										{if is_array($params.hint)}
											{foreach $params.hint as $hint}
												{if is_array($hint)}
													{html_entity_decode($hint.text|escape:'htmlall':'UTF-8')}
												{else}
													{html_entity_decode($hint|escape:'htmlall':'UTF-8')}
												{/if}
											{/foreach}
										{else}
											{html_entity_decode($params.hint|escape:'htmlall':'UTF-8')}
										{/if}
									">
									{html_entity_decode($params.title|escape:'htmlall':'UTF-8')}
								</span>
							{else}
								{html_entity_decode($params.title|escape:'htmlall':'UTF-8')}
							{/if}

							{if (!isset($params.orderby) || $params.orderby) && !$simple_header && $show_filters}
								<a {if isset($order_by) && ($key == $order_by) && ($order_way == 'DESC')}class="active"{/if}  href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;{$list_id|escape:'htmlall':'UTF-8'}Orderby={$key|urlencode|escape:'htmlall':'UTF-8'}&amp;{$list_id|escape:'htmlall':'UTF-8'}Orderway=desc&amp;token={$token|escape:'htmlall':'UTF-8'}{if isset($smarty.get.$identifier)}&{$identifier|escape:'htmlall':'UTF-8'}={$smarty.get.$identifier|intval}{/if}">
									<i class="icon-caret-down"></i>
								</a>
								<a {if isset($order_by) && ($key == $order_by) && ($order_way == 'ASC')}class="active"{/if} href="{$currentIndex|escape:'htmlall':'UTF-8'}&amp;{$list_id|escape:'htmlall':'UTF-8'}Orderby={$key|urlencode|escape:'htmlall':'UTF-8'}&amp;{$list_id|escape:'htmlall':'UTF-8'}Orderway=asc&amp;token={$token|escape:'htmlall':'UTF-8'}{if isset($smarty.get.$identifier)}&{$identifier|escape:'htmlall':'UTF-8'}={$smarty.get.$identifier|intval}{/if}">
									<i class="icon-caret-up"></i>
								</a>
							{/if}
						</span>
					</th>
					{/foreach}
					{if $shop_link_type}
						<th>
							<span class="title_box">
							{if $shop_link_type == 'shop'}
								{l s='Shop' mod='wic_erp'}
							{else}
								{l s='Group shop' mod='wic_erp'}
							{/if}
							</span>
						</th>
					{/if}
					{if $has_actions || $show_filters}
						<th>{if !$simple_header}{/if}</th>
					{/if}
					<th>&nbsp;</th>
				</tr>
			{if !$simple_header && $show_filters}
				<tr class="nodrag nodrop filter {if $row_hover}row_hover{/if}">
					{if $has_bulk_actions}
						<th class="text-center">
							--
						</th>
					{/if}
					{* Filters (input, select, date or bool) *}
					{foreach $fields_display AS $key => $params}
						<th {if isset($params.align)} class="{$params.align|escape:'htmlall':'UTF-8'}" {/if}>
							{if isset($params.search) && !$params.search}
								--
							{else}
								{if $params.type == 'bool'}
									<select class="filter fixed-width-sm" name="{$list_id|escape:'htmlall':'UTF-8'}Filter_{$key|intval}">
										<option value="">-</option>
										<option value="1" {if $params.value == 1} selected="selected" {/if}>{l s='Yes' mod='wic_erp'}</option>
										<option value="0" {if $params.value == 0 && $params.value != ''} selected="selected" {/if}>{l s='No' mod='wic_erp'}</option>
									</select>
								{elseif $params.type == 'date' || $params.type == 'datetime'}
									<div class="date_range row">
 										<div class="input-group fixed-width-md">
											<input type="text" class="filter datepicker date-input form-control" id="local_{$params.id_date|escape:'htmlall':'UTF-8'}_0" name="local_{$params.name_date|escape:'htmlall':'UTF-8'}[0]"  placeholder="{l s='From' mod='wic_erp'}" />
											<input type="hidden" id="{$params.id_date|escape:'htmlall':'UTF-8'}_0" name="{$params.name_date|escape:'htmlall':'UTF-8'}[0]" value="{if isset($params.value.0)}{$params.value.0|escape:'htmlall':'UTF-8'}{/if}">
											<span class="input-group-addon">
												<i class="icon-calendar"></i>
											</span>
										</div>
 										<div class="input-group fixed-width-md">
											<input type="text" class="filter datepicker date-input form-control" id="local_{$params.id_date|escape:'htmlall':'UTF-8'}_1" name="local_{$params.name_date|escape:'htmlall':'UTF-8'}[1]"  placeholder="{l s='To' mod='wic_erp'}" />
											<input type="hidden" id="{$params.id_date|escape:'htmlall':'UTF-8'}_1" name="{$params.name_date|escape:'htmlall':'UTF-8'}[1]" value="{if isset($params.value.1)}{$params.value.1|escape:'htmlall':'UTF-8'}{/if}">
											<span class="input-group-addon">
												<i class="icon-calendar"></i>
											</span>
										</div>
										<script>
											function parseDate(date){
												return $.datepicker.parseDate("yy-mm-dd", date);
											}
											$(function() {
												var dateStart = parseDate($("#{$params.id_date|escape:'htmlall':'UTF-8'}_0").val());
												var dateEnd = parseDate($("#{$params.id_date|escape:'htmlall':'UTF-8'}_1").val());
												$("#local_{$params.id_date|escape:'htmlall':'UTF-8'}_0").datepicker("option", "altField", "#{$params.id_date|escape:'htmlall':'UTF-8'}_0");
												$("#local_{$params.id_date|escape:'htmlall':'UTF-8'}_1").datepicker("option", "altField", "#{$params.id_date|escape:'htmlall':'UTF-8'}_1");
												if (dateStart !== null){
													$("#local_{$params.id_date|escape:'htmlall':'UTF-8'}_0").datepicker("setDate", dateStart);
												}
												if (dateEnd !== null){
													$("#local_{$params.id_date|escape:'htmlall':'UTF-8'}_1").datepicker("setDate", dateEnd);
												}
											});
										</script>
									</div>
								{elseif $params.type == 'select'}
									{if isset($params.filter_key)}
										<select class="filter" onchange="$('#submitFilterButton{$list_id|escape:'htmlall':'UTF-8'}').focus();$('#submitFilterButton{$list_id|escape:'htmlall':'UTF-8'}').click();" name="{$list_id|escape:'htmlall':'UTF-8'}Filter_{$params.filter_key|intval}" {if isset($params.width)} style="width:{$params.width|intval}px"{/if}>
											<option value="" {if $params.value == ''} selected="selected" {/if}>-</option>
											{if isset($params.list) && is_array($params.list)}
												{foreach $params.list AS $option_value => $option_display}
													<option value="{$option_value|intval}" {if (string)$option_display === (string)$params.value ||  (string)$option_value === (string)$params.value} selected="selected"{/if}>{$option_display|default}</option>
												{/foreach}
											{/if}
										</select>
									{/if}
								{else}
									<input type="text" class="filter" name="{$list_id|escape:'htmlall':'UTF-8'}Filter_{if isset($params.filter_key)}{$params.filter_key|escape:'htmlall':'UTF-8'}{else}{$key|escape:'htmlall':'UTF-8'}{/if}" value="{$params.value|escape:'htmlall':'UTF-8'}" {if isset($params.width) && $params.width != 'auto'} style="width:{$params.width|intval}px"{/if} />
								{/if}
							{/if}
						</th>
					{/foreach}

					{if $shop_link_type}
						<th>--</th>
					{/if}
					{if $has_actions || $show_filters}
						<th class="actions">
							{if $show_filters}
							<span class="pull-right">
								{*Search must be before reset for default form submit*}
								<button type="submit" id="submitFilterButton{$list_id|escape:'htmlall':'UTF-8'}" name="submitFilter" class="btn btn-default" data-list-id="{$list_id|escape:'htmlall':'UTF-8'}">
									<i class="icon-search"></i> {l s='Search' mod='wic_erp'}
								</button>
								{if $filters_has_value}
									<button type="submit" name="submitReset{$list_id|escape:'htmlall':'UTF-8'}" class="btn btn-warning">
										<i class="icon-eraser"></i> {l s='Reset' mod='wic_erp'}
									</button>
								{/if}
							</span>
							{/if}
						</th>
					{/if}
					<th>--</th>
				</tr>
			{/if}
</thead>
{/if}