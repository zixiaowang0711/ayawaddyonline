<?php
/**
* Module My Easy ERP Web In Color 
* 
*  @author    Web In Color - addons@webincolor.fr
*  @version 2.6
*  @uses Prestashop modules
*  @since 1.0 - mai 2014
*  @package Wic ERP
*  @copyright Copyright &copy; 2014, Web In Color
*  @license   http://www.webincolor.fr
*/

include(str_replace('modules/wic_erp/tools/ajax_supplier_order.php', 'config/config.inc.php', $_SERVER["SCRIPT_FILENAME"]));
require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpSuppliers.php';
require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpProducts.php';

if (!Tools::getValue('token')) {
    die('Token is requied');
}

if (Tools::getValue('id_supplier')) {
    $id_erp_supplier = ErpSuppliers::getSupplierById((int)Tools::getValue('id_supplier'));
    $erp_supplier = new ErpSuppliers((int)$id_erp_supplier);

    if (Validate::isLoadedObject($erp_supplier)) {
        /* We retrieve product to order */
        /* gets products */
        $query = new DbQuery();
        $query->select('ep.id_product,
						ep.id_product_attribute,
						ps.product_supplier_reference as supplier_reference,
						p.id_tax_rules_group,
						IF(ps.product_supplier_price_te = 0 OR ps.product_supplier_price_te = \'\' OR ps.product_supplier_price_te IS NULL, IF(pa.wholesale_price = 0 OR pa.wholesale_price = \'\' OR pa.wholesale_price IS NULL, IF(psh.wholesale_price = 0 OR psh.wholesale_price = \'\' OR psh.wholesale_price IS NULL, p.wholesale_price, psh.wholesale_price), pa.wholesale_price), ps.product_supplier_price_te) as unit_price_te,
						ps.id_currency,
						IF(pa.reference = \'\' OR pa.reference IS NULL, IF(p.reference = \'\' , \'\', p.reference), pa.reference) as reference,
						IF(pa.ean13 = \'\' OR pa.ean13 IS NULL, IF(p.ean13 = \'\', \'\', p.ean13), pa.ean13) as ean13,
						IF(pa.upc = \'\' OR pa.upc IS NULL, IF(p.upc = \'\', \'\', p.upc), pa.upc) as upc'
                        );
        if (Configuration::get('WIC_ERP_STOCK_MANAGEMENT') == 'normal') {				
            $query->select('(ep.`min_quantity`+ep.`safety_stock`- SUM(sa.`quantity`))   as `stock_to_order`');
            $query->select('(SUM(sa.`quantity`)-ep.`min_quantity`) as `stock_orderable`');               
        } else {
            $query->select('SUM(od.`product_quantity`-od.`product_quantity_refunded`)-SUM(od.`product_quantity_in_stock`) as `stock_to_order`');
        }

        $query->from('erp_products', 'ep');
        $query->leftJoin('stock_available', 'sa', '
                        sa.id_product = ep.id_product
                        AND sa.id_product_attribute = ep.id_product_attribute');					

        $query->leftJoin('product', 'p', 'p.id_product = ep.id_product');
        $query->innerJoin('product_supplier', 'ps', 'ps.id_product = ep.id_product AND ps.id_product_attribute = ep.id_product_attribute AND ps.id_supplier = '.(int)$erp_supplier->id_supplier);
        if (Configuration::get('WIC_ERP_STOCK_MANAGEMENT') != 'normal') {
            $query->leftJoin('order_detail', 'od', 'od.product_id = ep.id_product AND od.product_attribute_id = ep.id_product_attribute AND od.id_order IN (SELECT o.`id_order` FROM `'._DB_PREFIX_.'orders` o WHERE o.`current_state` IN ('.pSQL(Configuration::get('WIC_ERP_NOT_COMPLETE')).') )');
        }

        $query->leftJoin('product_attribute', 'pa', '
                        pa.id_product_attribute = ep.id_product_attribute
                        AND pa.id_product = ep.id_product
                ');
        $query->leftJoin('product_shop', 'psh', '
                        psh.`id_product` = ep.id_product
                        AND psh.id_shop = '.(int)Context::getContext()->shop->id.'
                ');
        if (!configuration::get('WIC_ERP_DISABLED_PRODUCT')) {
            $query->where('p.`active` = 1');
        }
        
        $query->groupBy('ep.id_product,
                        ep.id_product_attribute');
        $query->having('stock_to_order >= 0');

        /* gets items */
        $items = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        
        /* loads order currency */
        if (Tools::getValue('id_currency')) {
            $order_currency = new Currency((int)Tools::getValue('id_currency'));
        }

        if (!Validate::isLoadedObject($order_currency)) {
            return;
        }

        /* We retrieve erp order in progress */
        $query = new DbQuery();
        $query->select('eos.`id_erp_order_state`');
        $query->from('erp_order_state', 'eos');
        $query->where('eos.`enclosed` != 1 AND eos.`editable` != 1');

        $id_status = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        if ($id_status) {
            $status = array();

            foreach ($id_status as $id_state) {
                $status[] = $id_state['id_erp_order_state'];
            }

            $query = new DbQuery();
            $query->select('eo.`id_erp_order`');
            $query->from('erp_order', 'eo');
            $query->where('id_erp_order_state IN('.pSQL(implode(',', $status)).')');

            $orders = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

            if ($orders) {
                $order_progress = array();
                foreach ($orders as $order) {
                    $order_progress[] = $order['id_erp_order'];
                }
            }
        }

        $global_quantity = 0;
        $global_price = 0;

        foreach ($items as $item) {
            $qty_to_order = (int)$item['stock_to_order'];
            $qty_expected = 0;
            if (isset($order_progress) && count($order_progress) > 0) {
                $query = new DbQuery();
                $query->select('IF(SUM(eod.`quantity_ordered`) > SUM(eod.`quantity_received`), CAST(eod.`quantity_ordered` AS SIGNED) - CAST(eod.`quantity_received` AS SIGNED), 0) as qty_order');
                $query->from('erp_order_detail', 'eod');
                $query->where('eod.`id_erp_order` IN ('.pSQL(implode(',', $order_progress)).') AND eod.`id_product` = '.(int)$item['id_product'].' AND eod.`id_product_attribute` = '.(int)$item['id_product_attribute']);
                $qty_expected = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
            }

            $qty_to_order = $qty_to_order - $qty_expected;

            $erp_product = ErpProducts::getProductById((int)$item['id_product'], (int)$item['id_product_attribute']);
            $erp_product = new ErpProducts($erp_product);

            if ($erp_product->unit_order && ($qty_to_order % ($erp_product->unit_order))) {
                $qty_to_order += $erp_product->unit_order - ($qty_to_order % ($erp_product->unit_order));
            }


            if ($qty_to_order > 0) {
                if (Tools::getValue('id_currency')) {
                    $product_currency = new Currency((int)Tools::getValue('id_currency'));
                } elseif ($item['id_currency']) {
                    $product_currency = new Currency((int)$item['id_currency']);
                } else {
                    $product_currency = new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'));
                }

                if (Validate::isLoadedObject($product_currency)) {
                    $unit_price_te = Tools::convertPriceFull($item['unit_price_te'], $product_currency, $order_currency);
                } else {
                    $unit_price_te = 0;
                }

                /* We set global vairables */
                $global_quantity += $qty_to_order;
                $global_price += $unit_price_te * $qty_to_order;
            }
        }

        if (!isset($product_currency)) {
            $product_currency = new Currency((int)Configuration::get('PS_CURRENCY_DEFAULT'));
        }

        /* We format return value */
        $jsonoutput = array(
                        'global_quantity' => $global_quantity,
                        'global_price' => Tools::displayPrice($global_price, $product_currency->id),
                        'supplier_minimum_price' => Tools::displayPrice($erp_supplier->minimum_price, $product_currency->id),
                        'supplier_minimum_free_shipping' => Tools::displayPrice($erp_supplier->minimum_price_free_shipping, $product_currency->id),
                        'default_shipping_price' => Tools::displayPrice($erp_supplier->shipping_price, $product_currency->id),
                        'shipping_price' => $erp_supplier->shipping_price,
                        'vat_exemption' => $erp_supplier->vat_exemption,
                        'success' => true,
        );

        /* We send return value to erp_supplier_orders16/helpers/form/form.tpl */
        header('Content-Type: application/json');
        exit(Tools::jsonEncode($jsonoutput));
    }
}
