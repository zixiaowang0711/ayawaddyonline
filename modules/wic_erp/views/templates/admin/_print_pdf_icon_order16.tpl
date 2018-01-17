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

<span style="width:20px; margin-right:5px;">
{if ($order_state->invoice || $order->invoice_number)}
	<a target="_blank" href="{$link->getAdminLink('AdminPdf')|escape:'htmlall':'UTF-8'}&submitAction=generateInvoicePDF&id_order={$order->id|intval}"><i class="icon-file-text"></i></a>
{else}
	-
{/if}
</span>

{* Generate HTML code for printing Delivery Icon with link *}
<span style="width:20px;">
{if ($order_state->delivery || $order->delivery_number)}
	<a target="_blank" href="{$link->getAdminLink('AdminPdf')|escape:'htmlall':'UTF-8'}&submitAction=generateDeliverySlipPDF&id_order={$order->id|intval}"><i class="icon-truck"></i></a>
{else}
	-
{/if}
</span>
