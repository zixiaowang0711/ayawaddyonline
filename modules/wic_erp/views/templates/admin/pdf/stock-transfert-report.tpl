{*
* Module Inventory | Web in Color
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade this module
*
*  @author    Web in Color <addons@webincolor.fr>
*  @copyright Copyright &copy; 2015, Web In Color
*  @license   http://www.webincolor.fr
*}

<div style="font-size: 9pt; color: #444">
    <h1>{l s='Stock Transfer:' mod='wic_erp' pdf='true'}</h1>
    <table style="width: 30%; border: 0.75pt solid #4D4D4D;">
        <tbody style="font-size: 12pt; color: #666;">
            <tr>
                <td style="font-weight: bold;">{l s='Date:' mod='wic_erp' pdf='true'}</td>
                <td>{$date|date_format|escape:'htmlall':'UTF-8'}</td>
            </tr>                        
            <tr>
                <td style="font-weight: bold;">{l s='Supervisor:' mod='wic_erp' pdf='true'}</td>
                <td>{$employee|escape:'htmlall':'UTF-8'}</td>
            </tr>            
        </tbody>
    </table>
    <p>&nbsp;</p>
    <h4>{l s='Product List:' mod='wic_erp' pdf='true'}</h4>
    <!-- PRODUCTS -->
    <div style="font-size: 5pt;">
        <table style="width: 100%;">
            <tr style="line-height:6px; border: none; font-size: 10pt;">                
                <td style="width: 40%; text-align: left; background-color: #4D4D4D; color: #FFF; padding-left: 2px; font-weight: bold;">{l s='Designation' mod='wic_erp' pdf='true'}</td>
                <td style="width: 15%; text-align: center; background-color: #4D4D4D; color: #FFF; padding-right: 2px; font-weight: bold;">{l s='BarCode' mod='wic_erp' pdf='true'}</td>
                <td style="width: 15%; text-align: right; background-color: #4D4D4D; color: #FFF; padding-right: 2px; font-weight: bold;">{l s='Reference' mod='wic_erp' pdf='true'}</td>
                <td style="width: 15%; text-align: center; background-color: #4D4D4D; color: #FFF; padding-right: 2px; font-weight: bold;">{l s='Location' mod='wic_erp' pdf='true'}</td>
                <td style="width: 15%; text-align: center; background-color: #4D4D4D; color: #FFF; padding-right: 2px; font-weight: bold;">{l s='Stock transfer' mod='wic_erp' pdf='true'}</td>
            </tr>
            {* for each product ordered *}
            {foreach from=$products key=idErpProduct item=product}
                <tr style="font-size: 10pt;">                    
                    <td style="text-align: left; padding-left: 1px; border-bottom: 0.75pt solid #4D4D4D;">{$product.name|escape:'htmlall':'UTF-8'}</td>
                    <td style="text-align: center; padding-right: 1px; border-bottom: 0.75pt solid #4D4D4D;">{$product.ean13|escape:'htmlall':'UTF-8'}</td>
                    <td style="text-align: right; padding-right: 1px; border-bottom: 0.75pt solid #4D4D4D;">{$product.reference|escape:'htmlall':'UTF-8'}</td>
                    <td style="text-align: center; padding-right: 1px; border-bottom: 0.75pt solid #4D4D4D;">{$product.location|escape:'htmlall':'UTF-8'}</td>
                    <td style="text-align: center; padding-right: 1px; border-bottom: 0.75pt solid #4D4D4D;">{$product.stockTransfert|escape:'htmlall':'UTF-8'}</td>
                </tr>
            {/foreach}
        </table>
    </div>
</div>