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
			{if $statuses}
			<fieldset>
				<legend>{l s='Mass update orders status' mod='wic_erp'}</legend>
				<p>
                                    {if $statuses|count > 1}
                                        <select id="select_submitBulk" name="select_submitBulk">
                                                <option value="0">{l s='-- Select new status --' mod='wic_erp'}</option>
                                                {foreach $statuses as $status}
                                                        <option value="{$status.id_order_state|escape:'htmlall':'UTF-8'}">{$status.name|escape:'html':'UTF-8'}</option>
                                                {/foreach}
                                        </select>
                                        <input type="submit" class="button" name="submitBulk" id="submitBulk" value="{l s='Update order(s)' mod='wic_erp'}" />
                                    {/if}
				</p>
				</fieldset>
			{/if}
		</td>
	</tr>
</table>
<input type="hidden" name="token" value="{$token|escape:'html':'UTF-8'}" />
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
	});
</script>

