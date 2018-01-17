{**
* RockPOS - Point of Sale for PrestaShop
*
* @author    Hamsa Technologies
* @copyright Hamsa Technologies
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}

<style>
   table{        
        width: 370px;
        font-size: 8pt;
        color: #444;
   }
    table .report-header{
        text-align: center;
    }
    table .report-generate{
        text-align: right;
        font-size: 8pt;
    }
    table td.head-time {
        font-size: 7pt;
    }
    table td.price{
        text-align: right;
   }
   table td.header-tilte{
       font-size: 12pt; 
   }
       
  .sales-summary-content {
      background-color: #fff;
      border: 1px solid #d3d8db;
  }
  .sales-summary-content td {
      background-color: #fff;
      border: 1px solid #d3d8db;
      height: 20px; 
      line-height:7px;
  }
  
</style>
<br />
<div class='report-summary'>
    <table>
        <tr class="report-header">
            <td colspan="3" class="header-tilte">
                {$hs_pos_i18n.sales_summary|escape:'quotes':'UTF-8'}
            </td>                                 
        </tr>
        <tr class="report-header">
            <td class="head-time" colspan="3">
                <i>
                {if $pos_sales_summary->date_from != $pos_sales_summary->date_to}
                    ({$pos_sales_summary->date_from|date_format|escape:'htmlall':'UTF-8'} - {$pos_sales_summary->date_to|date_format|escape:'htmlall':'UTF-8'})  
                {else}
                    ({$pos_sales_summary->date_from|date_format|escape:'htmlall':'UTF-8'})
                {/if}
                </i>
            </td>                                 
        </tr>
        <tr class="space"><td></td><td></td><td></td></tr>
        <tr class="space-header"><td></td><td></td><td></td></tr>
        <tr class="report-generate">
            <td colspan="3">
                {assign value=$employee->firstname|cat:' '|cat:$employee->lastname|cat: '<br />' var=employee_name}
                {assign value='<span class="generate-time">'|cat:$generate_time|cat:'</span>' var=generate_time}
                <i>{$hs_pos_i18n.generated_by_at|escape:'htmlall':'UTF-8'|sprintf:$employee_name:$generate_time|replace:'[breakline]':''}</i> <br />
            </td>            
        </tr>
        <tr class="space"><td></td><td></td><td></td></tr>
    </table>
    {assign value=$pos_sales_summary->getSalesSummary() var=sales_summary}
    {assign value=$pos_sales_summary->getPaymentSummary() var=payment_summary}
    {assign value=$pos_sales_summary->getShippingCostSummary() var=shipping_cost_summary}
    {assign value=$pos_sales_summary->getCurrencySummary() var=currency_summary}
    {assign value=$pos_sales_summary->getTaxSummary() var=tax_summary}
    {assign value=$pos_sales_summary->getCategorySummary($employee->id_lang) var=category_summary}
    <table class="sales-summary-content">             
            <tr>
                <td>{$hs_pos_i18n.sales|escape:'htmlall':'UTF-8'}</td>
                <td>{$hs_pos_i18n.total_products|escape:'htmlall':'UTF-8'}</td>
                <td class="price">{convertPrice price=$sales_summary.total_products}</td> 
            </tr>
            <tr>
                <td></td>
                <td>{$hs_pos_i18n.shipping_cost|escape:'htmlall':'UTF-8'}</td>
                <td class="price">{convertPrice price=$shipping_cost_summary}</td> 
            </tr> 
            <tr>
                <td></td>
                <td>{$hs_pos_i18n.total_without_tax|escape:'htmlall':'UTF-8'}</td>
                <td class="price">{convertPrice price=$sales_summary.total_tax_excl}</td> 
            </tr>
            <tr>
                <td></td>
                <td>{$hs_pos_i18n.total_tax|escape:'htmlall':'UTF-8'}</td>
                <td class="price">{convertPrice price=($sales_summary.total_tax_incl - $sales_summary.total_tax_excl)}</td> 
            </tr>
            {if $tax_enabled}
                <tr>
                    <td></td>
                    <td>{$hs_pos_i18n.total_with_tax|escape:'htmlall':'UTF-8'}</td>
                    <td class="price">{convertPrice price=$sales_summary.total_tax_incl}</td> 
                </tr>
            {/if}
            <tr>
                <td></td>
                <td>{$hs_pos_i18n.total_real_paid|escape:'htmlall':'UTF-8'}</td>
                <td class="price">{convertPrice price=$sales_summary.total_real_paid}</td> 
            </tr>
            <tr>
                <td></td>
                <td>{$hs_pos_i18n.transactions|escape:'htmlall':'UTF-8'}</td>
                <td class="price">{$sales_summary.total_transaction|escape:'htmlall':'UTF-8'}</td> 
            </tr>
            <tr>
                <td></td>
                <td>{$hs_pos_i18n.sold_items|escape:'htmlall':'UTF-8'}</td>
                <td class="price">{$sales_summary.sold_items|escape:'htmlall':'UTF-8'}</td> 
            </tr>
            <tr>
                <td></td>
                <td>{$hs_pos_i18n.average_ticket_size|escape:'htmlall':'UTF-8'}</td>
                <td class="price">{if !empty($sales_summary.total_tax_incl) && !empty($sales_summary.total_transaction)}{convertPrice price=($sales_summary.total_tax_incl / $sales_summary.total_transaction)}{else} {convertPrice price=0} {/if}</td> 
            </tr>
            <tr class="space"><td></td><td></td><td></td></tr>
            {if !empty($category_summary)}
                {foreach from=$category_summary item=category name=category} 
                    <tr>
                        {if $smarty.foreach.category.first}  
                            <td>{$hs_pos_i18n.categories|escape:'htmlall':'UTF-8'}</td>
                        {else}
                            <td></td>                        
                        {/if} 
                        <td>{$category.name|escape:'htmlall':'UTF-8'} ({(($category.total_price / $sales_summary.total_products) * 100)|string_format:"%.2f"}%)</td>
                        <td class="price">{convertPrice price=$category.total_price}</td>  
                    </tr>
                {/foreach}
            {else}
                <tr>
                    <td>{$hs_pos_i18n.categories|escape:'htmlall':'UTF-8'}</td>
                    <td></td>
                    <td class="price">{convertPrice price=0}</td>  
                </tr>
            {/if}
            <tr class="space"><td></td><td></td><td></td></tr>
            {if !empty($payment_summary )}    
               {foreach from=$payment_summary item=payment name=payment} 
                <tr>
                    {if $smarty.foreach.payment.first}  
                        <td>
                            {$hs_pos_i18n.payment_methods|escape:'htmlall':'UTF-8'}
                        </td>
                    {else}
                        <td></td>
                    {/if}         
                    <td>{$payment.name|escape:'htmlall':'UTF-8'}</td>
                    <td class="price">{convertPrice price=$payment.amount}</td>  
                </tr>
                {/foreach}
            {else}
                <tr>
                    <td>{$hs_pos_i18n.payment_methods|escape:'htmlall':'UTF-8'}</td>
                    <td></td>
                    <td class="price">{convertPrice price=0}</td>  
                </tr>
            {/if}
            <tr class="space"><td></td><td></td><td></td></tr>
            {if !empty($currency_summary)}
                {foreach from=$currency_summary item=currency name=currency} 
                    <tr>
                        {if $smarty.foreach.currency.first}  
                            <td>{$hs_pos_i18n.currencies|escape:'htmlall':'UTF-8'}</td>
                        {else}
                            <td></td>                        
                        {/if} 
                        <td>{$currency.iso_code|escape:'htmlall':'UTF-8'} ({$currency.sign|escape:'htmlall':'UTF-8'})</td>
                        <td class="price">{$currency.total|escape:'htmlall':'UTF-8'}</td>  
                    </tr>
                {/foreach}
            {else}
                <tr>
                    <td>{$hs_pos_i18n.currencies|escape:'htmlall':'UTF-8'}</td>
                    <td></td>
                    <td class="price">{convertPrice price=0}</td>  
                </tr>
            {/if}
            {if $tax_enabled}
                {if !empty($tax_summary)}
                    <tr class="space"><td></td><td></td><td></td></tr>
                    {foreach from=$tax_summary item=tax name=tax} 
                        <tr>
                            {if $smarty.foreach.tax.first}  
                                <td>{$hs_pos_i18n.taxes|escape:'htmlall':'UTF-8'}</td>
                            {else}
                                <td></td>                        
                            {/if} 
                            <td>{$tax.rate|string_format:"%.2f"|escape:'htmlall':'UTF-8'}%</td>
                            <td class="price">{convertPrice price=$tax.total}</td>  
                        </tr>
                    {/foreach}     
                {/if}  
            {/if}   
    </table>
</div>
