{**
* RockPOS - Point of Sale for PrestaShop
*
* @author    Hamsa Technologies
* @copyright Hamsa Technologies
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
<table>
    <tr>
        <td style="text-align: center; font-size: 8pt;">
            {if !empty($shop_address)}
                {$shop_address|escape:'html':'UTF-8'}<br />
            {/if}
            {if !empty($shop_phone) OR !empty($shop_fax)}
                {if !empty($shop_phone)}
                    {$hs_pos_i18n.phone|escape:'html':'UTF-8'}: {$shop_phone|escape:'html':'UTF-8'}
                {/if}
                {if !empty($shop_fax)}
                    {$hs_pos_i18n.fax|escape:'html':'UTF-8'}: {$shop_fax|escape:'html':'UTF-8'}
                {/if}
                <br />
            {/if}
            {if isset($shop_details)}
                {$shop_details|escape:'html':'UTF-8'}<br />
            {/if}
        </td>
    </tr>
</table>