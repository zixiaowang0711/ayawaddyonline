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

			</table>
                        {if version_compare($smarty.const._PS_VERSION_,'1.6','>=')}
			<div class="row">
				<div class="col-lg-6">
					{if $bulk_actions && $has_bulk_actions}
					<div class="btn-group bulk-actions">
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							{l s='Bulk actions' mod='wic_erp'} <span class="caret"></span>
						</button>
						<ul class="dropdown-menu">
							<li>
								<a href="#" onclick="javascript:checkDelBoxes($(this).closest('form').get(0), '{$list_id|escape:'htmlall':'UTF-8'}Box[]', true);return false;">
									<i class="icon-check-sign"></i>&nbsp;{l s='Select all' mod='wic_erp'}
								</a>
							</li>
							<li>
								<a href="#" onclick="javascript:checkDelBoxes($(this).closest('form').get(0), '{$list_id|escape:'htmlall':'UTF-8'}Box[]', false);return false;">
									<i class="icon-check-empty"></i>&nbsp;{l s='Unselect all' mod='wic_erp'}
								</a>
							</li>
						</ul>
					</div>
					{/if}
				</div>
				{if !$simple_header && $list_total > $pagination[0]}
				<div class="col-lg-6">
					{* Choose number of results per page *}
					<span class="pagination">
						{l s='Display' mod='wic_erp'}: 
						<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
							{$selected_pagination|escape:'html':'UTF-8'}
							<i class="icon-caret-down"></i>
						</button>
						<ul class="dropdown-menu">
						{foreach $pagination AS $value}
							<li>
								<a href="javascript:void(0);" class="pagination-items-page" data-items="{$value|intval}" data-list-id="{$list_id|default}">{$value|escape:'htmlall':'UTF-8'}</a>
							</li>
						{/foreach}
						</ul>
						/ {$list_total|intval} {l s='result(s)' mod='wic_erp'}
						<input type="hidden" id="{$list_id|escape:'htmlall':'UTF-8'}-pagination-items-page" name="{$list_id|escape:'htmlall':'UTF-8'}_pagination" value="{$selected_pagination|intval}" />
					</span>
					<script type="text/javascript">
						$('.pagination-items-page').on('click',function(e){
							e.preventDefault();
							$('#'+$(this).data("list-id")+'-pagination-items-page').val($(this).data("items")).closest("form").submit();
						});
					</script>
					<ul class="pagination pull-right">
						<li {if $page <= 1}class="disabled"{/if}>
							<a href="javascript:void(0);" class="pagination-link" data-page="1" data-list-id="{$list_id|escape:'htmlall':'UTF-8'}">
								<i class="icon-double-angle-left"></i>
							</a>
						</li>
						<li {if $page <= 1}class="disabled"{/if}>
							<a href="javascript:void(0);" class="pagination-link" data-page="{$page - 1|escape:'htmlall':'UTF-8'}" data-list-id="{$list_id|escape:'htmlall':'UTF-8'}">
								<i class="icon-angle-left"></i>
							</a>
						</li>
						{assign p 0}
						{while $p++ < $total_pages}
							{if $p < $page-2}
								<li class="disabled">
									<a href="javascript:void(0);">&hellip;</a>
								</li>
								{assign p $page-3}
							{else if $p > $page+2}
								<li class="disabled">
									<a href="javascript:void(0);">&hellip;</a>
								</li>
								{assign p $total_pages}
							{else}
								<li {if $p == $page}class="active"{/if}>
									<a href="javascript:void(0);" class="pagination-link" data-page="{$p|escape:'htmlall':'UTF-8'}" data-list-id="{$list_id|escape:'htmlall':'UTF-8'}">{$p|escape:'htmlall':'UTF-8'}</a>
								</li>
							{/if}
						{/while}
						<li {if $page >= $total_pages}class="disabled"{/if}>
							<a href="javascript:void(0);" class="pagination-link" data-page="{$page + 1|escape:'htmlall':'UTF-8'}" data-list-id="{$list_id|escape:'htmlall':'UTF-8'}">
								<i class="icon-angle-right"></i>
							</a>
						</li>
						<li {if $page >= $total_pages}class="disabled"{/if}>
							<a href="javascript:void(0);" class="pagination-link" data-page="{$total_pages|escape:'htmlall':'UTF-8'}" data-list-id="{$list_id|escape:'htmlall':'UTF-8'}">
								<i class="icon-double-angle-right"></i>
							</a>
						</li>
					</ul>
					<script type="text/javascript">
						$('.pagination-link').on('click',function(e){
							e.preventDefault();
			
							if (!$(this).parent().hasClass('disabled'))
								$('#submitFilter'+$(this).data("list-id")).val($(this).data("page")).closest("form").submit();
						});
					</script>
				</div>
				{/if}
			</div>
                        {/if}
			{if $statuses}
			<br/><br/>
			<fieldset>
				<legend>{l s='Mass update orders status' mod='wic_erp'}</legend>
				<p>
					{if $statuses|count > 1}
						<select id="select_submitBulk" name="select_submitBulk" style="width:350px;" class="chosen form-control">
							<option value="0">{l s='-- Select new status --' mod='wic_erp'}</option>
							{foreach $statuses as $status}
								<option value="{$status.id_order_state|escape:'htmlall':'UTF-8'}">{$status.name|escape:'html':'UTF-8'}</option>
							{/foreach}
						</select>
						<br/><br/>
						<input type="submit" class="btn btn-primary pull-left" name="submitBulk" id="submitBulk" value="{l s='Update order(s)' mod='wic_erp'}" />
					{/if}
				</p>
				</fieldset>
			{/if}
			<br/><br/>
			<fieldset>
				<legend>{l s='Add message to orders' mod='wic_erp'}</legend>
				<div id="message" class="form-horizontal" style="width:600px;">
							<div class="form-group">
								<label class="control-label col-lg-3">{l s='Choose a standard message' mod='wic_erp'}</label>
								<div class="col-lg-9">
									<select class="chosen form-control" name="order_message" id="order_message" onchange="orderOverwriteMessage(this, '{l s='Do you want to overwrite your existing message?' mod='wic_erp'}')">
										<option value="0" selected="selected">-</option>
										{foreach from=$orderMessages item=orderMessage}
										<option value="{$orderMessage['message']|escape:'html':'UTF-8'}">{$orderMessage['name']|escape:'html':'UTF-8'}</option>
										{/foreach}
									</select>
									<p class="help-block">
										<a href="{$link->getAdminLink('AdminOrderMessage')|escape:'html':'UTF-8'}">
											{l s='Configure predefined messages' mod='wic_erp'}
											<i class="icon-external-link"></i>
										</a>
									</p>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-lg-3">{l s='Display to customer?' mod='wic_erp'}</label>
								<div class="col-lg-9">
									<span class="switch prestashop-switch fixed-width-lg">
										<input type="radio" name="visibility" id="visibility_on" value="0" />
										<label for="visibility_on">
											{l s='Yes' mod='wic_erp'}
										</label>
										<input type="radio" name="visibility" id="visibility_off" value="1" checked="checked" /> 
										<label for="visibility_off">
											{l s='No' mod='wic_erp'}
										</label>
										<a class="slide-button btn"></a>
									</span>
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-lg-3">{l s='Message' mod='wic_erp'}</label>
								<div class="col-lg-9">
									<textarea id="txt_msg" class="textarea-autosize" name="message" rows="10">{Tools::getValue('message')|escape:'html':'UTF-8'}</textarea>
									<p id="nbchars"></p>
								</div>
							</div>
							<button type="submit" id="submitMessage" class="btn btn-primary pull-left" name="submitMessage">
								{l s='Send message' mod='wic_erp'}
							</button>
						</div>
				</fieldset>
		</td>
	</tr>
</table>
<input type="hidden" name="token" value="{$token|escape:'htmlall':'UTF-8'}" />
<input type="hidden" name="order_status" value="{$tab_selected|escape:'htmlall':'UTF-8'}" />
</form>

<script type="text/javascript">
	var confirmation = new Array();
	{foreach $bulk_actions as $key => $params}
		{if isset($params.confirm)}
			confirmation['update'] = "{$params.confirm|escape:'html':'UTF-8'}";
		{/if}
	{/foreach}

	$(document).ready(function(){
			$('#submitBulk').click(function(){
				if (confirmation['update'])
					return confirm(confirmation['update']);
				else
					return true;
			});
			
			$('#submitMessage').click(function(){
				if (confirmation['update'])
					return confirm(confirmation['update']);
				else
					return true;
			});
	});
</script>

