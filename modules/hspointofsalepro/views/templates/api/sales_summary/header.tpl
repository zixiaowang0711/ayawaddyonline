{**
* RockPOS - Point of Sale for PrestaShop
*
* @author    Hamsa Technologies
* @copyright Hamsa Technologies
* @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*}
<style>
    .header-report{
        font-size: 9pt; 
        color: #444;
        width: 100%;
        text-align: left;
    }
</style>
<table style="width: 100%">
    <tr>
        <td style="width: 50%; text-align: left;">
            <table style="width: 100%;" class="header-report">
                {if !empty($shop_name)}
                    <tr>
                        <td>{$shop_name|escape:'html':'UTF-8'}</td>
                    </tr>
                {/if}
                {if !empty($shop_details)}
                    <tr>
                        <td>{$shop_details|escape:'html':'UTF-8'}</td>
                    </tr>
                {/if}
                {if !empty($shop_address)}
                    <tr>
                        <td>{$shop_address|escape:'html':'UTF-8'}</td>
                    </tr>
                {/if}
            </table>
        </td>

    </tr>
</table>

