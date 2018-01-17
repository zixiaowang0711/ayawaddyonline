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
class StockManager extends StockManagerCore
{
    /*
    * module: wic_erp
    * date: 2017-11-06 19:44:10
    * version: 2.9.9.1
    */
    public function getProductRealQuantities($id_product, $id_product_attribute, $ids_warehouse = null, $usable = false)
    {
        if (!is_null($ids_warehouse))
        {
            if (!is_array($ids_warehouse))
                $ids_warehouse = array($ids_warehouse);
            $ids_warehouse = array_map('intval', $ids_warehouse);
        }
        
        Cache::clean('StockAvailable::getQuantityAvailableByProduct_'.(int) $id_product.'*');
        
        $excludedStatus = '';
        if (Configuration::get('WIC_ERP_NOT_UPD_STK')) {
                $excludedStatus .= Configuration::get('WIC_ERP_NOT_UPD_STK');
        }
        if (Configuration::get('WIC_ERP_NOT_DISPLAY')) {
                if ($excludedStatus) {
                        $excludedStatus .= ',';
                }
                $excludedStatus .= Configuration::get('WIC_ERP_NOT_DISPLAY');
        }
        
        /*if (Configuration::get('WIC_ERP_NOT_UPD_STATE')) {
                if ($excludedStatus) {
                        $excludedStatus .= ',';
                }
                $excludedStatus .= Configuration::get('WIC_ERP_NOT_UPD_STATE');
        }*/
        if ($excludedStatus) {
                $excludedStatus .= ',';
        }
        
        $excludedStatus .= Configuration::get('PS_OS_CANCELED').','.Configuration::get('PS_OS_ERROR');
        /*********************
        * FOR ALL WAREHOUSE
        ********************/
        $client_orders_qty = 0;
        if (version_compare(_PS_VERSION_, '1.6.0.11', '<=')) {
            $in_pack = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
               'SELECT id_product_pack, quantity FROM '._DB_PREFIX_.'pack
                       WHERE id_product_item = '.(int)$id_product);
        } else {
            $in_pack = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
               'SELECT id_product_pack, quantity FROM '._DB_PREFIX_.'pack
                       WHERE id_product_item = '.(int)$id_product.'
                       AND id_product_attribute_item = '.($id_product_attribute ? (int)$id_product_attribute : '0'));
        }
                 
        if (!Pack::isPack($id_product) && $in_pack)
        {
            foreach ($in_pack as $value)
            {
                if (Validate::isLoadedObject($product = new Product((int)$value['id_product_pack'])) &&
                    ($product->pack_stock_type == 1 || $product->pack_stock_type == 2 || ($product->pack_stock_type == 3 && Configuration::get('PS_PACK_STOCK_TYPE') > 0)))
                {
                    $query = new DbQuery();
                    $query->select('od.product_quantity, od.product_quantity_refunded, od.id_order');
                    $query->from('order_detail', 'od');
                    $query->leftjoin('orders', 'o', 'o.id_order = od.id_order');
                    $query->where('od.product_id = '.(int)$value['id_product_pack']);
                    $query->leftJoin('order_history', 'oh', 'oh.id_order = o.id_order AND oh.id_order_state = o.current_state');
                    $query->leftJoin('order_state', 'os', 'os.id_order_state = oh.id_order_state');
                    $query->where('os.shipped != 1');
                    $query->where('(o.valid = 1 AND os.id_order_state != '.Configuration::get('PS_OS_ERROR').' AND os.id_order_state != '.Configuration::get('PS_OS_CANCELED').') OR os.id_order_state NOT IN ('.$excludedStatus.')');
                    $query->groupBy('od.id_order_detail');
                    if (count($ids_warehouse)) {
                        $query->where('od.id_warehouse IN('.implode(', ', $ids_warehouse).')');
                    }
                    
                    $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
                    if (count($res)) {
                        foreach ($res as $row) {
                            $client_orders_qty += ($row['product_quantity'] - $row['product_quantity_refunded']);
                        }
                    }
                }
            }
        }
        if (!Pack::isPack($id_product) || (Pack::isPack($id_product) && Validate::isLoadedObject($product = new Product((int)$id_product))
                && $product->pack_stock_type == 0 || $product->pack_stock_type == 2 ||
                ($product->pack_stock_type == 3 && (Configuration::get('PS_PACK_STOCK_TYPE') == 0 || Configuration::get('PS_PACK_STOCK_TYPE') == 2))))
        {
            $query = new DbQuery();
            $query->select('od.product_quantity, od.product_quantity_refunded');
            $query->from('order_detail', 'od');
            $query->leftjoin('orders', 'o', 'o.id_order = od.id_order');
            $query->where('od.product_id = '.(int)$id_product);
            if (0 != $id_product_attribute) {
                $query->where('od.product_attribute_id = '.(int)$id_product_attribute);
            }
            $query->leftJoin('order_history', 'oh', 'oh.id_order = o.id_order AND oh.id_order_state = o.current_state');
            $query->leftJoin('order_state', 'os', 'os.id_order_state = oh.id_order_state');
            $query->where('os.shipped != 1');
            $query->where('(o.valid = 1 AND os.id_order_state != '.Configuration::get('PS_OS_ERROR').' AND os.id_order_state != '.Configuration::get('PS_OS_CANCELED').') OR os.id_order_state NOT IN ('.$excludedStatus.')');
            $query->groupBy('od.id_order_detail');
            if (count($ids_warehouse)) {
                $query->where('od.id_warehouse IN('.implode(', ', $ids_warehouse).')');
            }
            $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
            if (count($res)) {
                foreach ($res as $row) {
                    $client_orders_qty += ($row['product_quantity'] - $row['product_quantity_refunded']);
                }
            }
        }
        
        /***************************
         * BY Warehouse
         */
        
        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && is_null($ids_warehouse))
        {
            $warehouses = Warehouse::getWarehouses();
            
            if ($warehouses) {
                if (count($warehouses) == 1) {
                    $client_orders_qty_by_warehouse = $client_orders_qty;
                }
                
                $ids_warehouses = array();
                $realQuantityProduct = 0;
                foreach ($warehouses as $warehouse) {
                    
                    if (Configuration::get('WIC_ERP_DEFAULT_WAREHOUSE') == $warehouse['id_warehouse']) {
                        $warehouseSelect = array(0, $warehouse['id_warehouse']);
                    } else {
                        $warehouseSelect = array($warehouse['id_warehouse']);
                    }
                    
                    $ids_warehouses[] = $warehouse['id_warehouse'];
                        
                    if (count($warehouses) > 1) {
                        $client_orders_qty_by_warehouse = 0;
                        
                        if (!Pack::isPack($id_product) && $in_pack)
                        {
                            foreach ($in_pack as $value)
                            {
                                if (Validate::isLoadedObject($product = new Product((int)$value['id_product_pack'])) &&
                                    ($product->pack_stock_type == 1 || $product->pack_stock_type == 2 || ($product->pack_stock_type == 3 && Configuration::get('PS_PACK_STOCK_TYPE') > 0)))
                                {
                                    $query = new DbQuery();
                                    $query->select('od.product_quantity, od.product_quantity_refunded');
                                    $query->from('order_detail', 'od');
                                    $query->leftjoin('orders', 'o', 'o.id_order = od.id_order');
                                    $query->where('od.product_id = '.(int)$value['id_product_pack']);
                                    $query->leftJoin('order_history', 'oh', 'oh.id_order = o.id_order AND oh.id_order_state = o.current_state');
                                    $query->leftJoin('order_state', 'os', 'os.id_order_state = oh.id_order_state');
                                    $query->where('os.shipped != 1');
                                    $query->where('(o.valid = 1 AND os.id_order_state != '.Configuration::get('PS_OS_ERROR').' AND os.id_order_state != '.Configuration::get('PS_OS_CANCELED').') OR os.id_order_state NOT IN ('.$excludedStatus.')');
                                    $query->groupBy('od.id_order_detail');
                                    
                                    if (count($warehouses)) {
                                        $query->where('od.id_warehouse IN('.implode(', ', $warehouseSelect).')');
                                    }
                                    
                                    $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
                                    if (count($res)) {
                                        foreach ($res as $row) {
                                            $client_orders_qty_by_warehouse += ($row['product_quantity'] - $row['product_quantity_refunded']);
                                        }
                                    }
                                }
                            }
                        }
                        if (!Pack::isPack($id_product) || (Pack::isPack($id_product) && Validate::isLoadedObject($product = new Product((int)$id_product))
                                && $product->pack_stock_type == 0 || $product->pack_stock_type == 2 ||
                                ($product->pack_stock_type == 3 && (Configuration::get('PS_PACK_STOCK_TYPE') == 0 || Configuration::get('PS_PACK_STOCK_TYPE') == 2))))
                        {
                            $query = new DbQuery();
                            $query->select('od.product_quantity, od.product_quantity_refunded');
                            $query->from('order_detail', 'od');
                            $query->leftjoin('orders', 'o', 'o.id_order = od.id_order');
                            $query->where('od.product_id = '.(int)$id_product);
                            if (0 != $id_product_attribute) {
                                $query->where('od.product_attribute_id = '.(int)$id_product_attribute);
                            }
                            $query->leftJoin('order_history', 'oh', 'oh.id_order = o.id_order AND oh.id_order_state = o.current_state');
                            $query->leftJoin('order_state', 'os', 'os.id_order_state = oh.id_order_state');
                            $query->where('os.shipped != 1');
                            $query->where('(o.valid = 1 AND os.id_order_state != '.Configuration::get('PS_OS_ERROR').' AND os.id_order_state != '.Configuration::get('PS_OS_CANCELED').') OR os.id_order_state NOT IN ('.$excludedStatus.')');
                            $query->groupBy('od.id_order_detail');
                            if (count($warehouses)) {
                                $query->where('od.id_warehouse IN('.implode(', ', $warehouseSelect).')');
                            }
                            $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
                            if (count($res)) {
                                foreach ($res as $row) {
                                    $client_orders_qty_by_warehouse += ($row['product_quantity'] - $row['product_quantity_refunded']);
                                }
                            }
                        }
                    }
                    
                    $qtyPhysical = $this->getPhysicalQuantity($id_product, $id_product_attribute, $warehouseSelect);
                    $stockId = Db::getInstance()->getValue('SELECT `id_stock` FROM '._DB_PREFIX_.'stock
                        WHERE `id_warehouse` = '.(int)$warehouse['id_warehouse'].' AND `id_product` = '.(int)$id_product.((int)$id_product_attribute ? ' AND `id_product_attribute` = '.$id_product_attribute : '')); 
                    
                    $realQuantity = ($qtyPhysical - $client_orders_qty_by_warehouse);
                    $realQuantityProduct += $realQuantity;
                    
                    if ($stockId) {
                        $stockObject = new Stock((int)$stockId);
                        if (Validate::isLoadedObject($stockObject)) {
                            if ($stockObject->usable_quantity != $realQuantity) {
                                if ($realQuantity < 0) {
                                    $realQuantity = 0;
                                }
                                $stockObject->usable_quantity = $realQuantity;
                                $stockObject->update();
                            }
                        }
                    }
                }
            }
            
            $this->synchronizeStockAvailable($realQuantityProduct, $id_product, $id_product_attribute);
            Cache::clean('StockAvailable::getQuantityAvailableByProduct_'.(int) $id_product.'*');
            return $realQuantityProduct;
        } else {
            $qty = $this->getPhysicalQuantity($id_product, $id_product_attribute, $ids_warehouse);
            $this->synchronizeStockAvailable(($qty - $client_orders_qty), $id_product, $id_product_attribute);
            Cache::clean('StockAvailable::getQuantityAvailableByProduct_'.(int) $id_product.'*');
            return ($qty - $client_orders_qty);
        }
    }
    
    /*
    * module: wic_erp
    * date: 2017-11-06 19:44:10
    * version: 2.9.9.1
    */
    public function getPhysicalQuantity($id_product, $id_product_attribute, $ids_warehouse = null)
    {
        if (!is_null($ids_warehouse)) {
            if (!is_array($ids_warehouse)) {
                $ids_warehouse = array($ids_warehouse);
            }
            $ids_warehouse = array_map('intval', $ids_warehouse);
            if (!count($ids_warehouse)) {
                return 0;
            }
        } else {
            $ids_warehouse = array();
        }
        $query = new DbQuery();
        $query->select('SUM(s.physical_quantity)');
        $query->from('stock', 's');
        $query->where('s.id_product = '.(int)$id_product);
        if (0 != $id_product_attribute) {
            $query->where('s.id_product_attribute = '.(int)$id_product_attribute);
        }
        if (count($ids_warehouse)) {
            $query->where('s.id_warehouse IN('.implode(', ', $ids_warehouse).')');
        }
        return (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }
    
    /*
    * module: wic_erp
    * date: 2017-11-06 19:44:10
    * version: 2.9.9.1
    */
    public function addProduct(
        $id_product,
        $id_product_attribute = 0,
        Warehouse $warehouse,
        $quantity,
        $id_stock_mvt_reason,
        $price_te,
        $is_usable = true,
        $id_supply_order = null,
        $employee = null
    ) {
        
        $mvt = parent::addProduct($id_product,
            $id_product_attribute,
            $warehouse,
            $quantity,
            $id_stock_mvt_reason,
            $price_te,
            $is_usable,
            $id_supply_order,
            $employee);
        
        StockAvailable::synchronize($id_product);
        Cache::clean('StockAvailable::getQuantityAvailableByProduct_'.(int) $id_product.'*');
        
        return $mvt;
    }
    
    /*
    * module: wic_erp
    * date: 2017-11-06 19:44:10
    * version: 2.9.9.1
    */
    public function removeProduct($id_product,
                                  $id_product_attribute = null,
                                  Warehouse $warehouse,
                                  $quantity,
                                  $id_stock_mvt_reason,
                                  $is_usable = true,
                                  $id_order = null,
                                  $ignore_pack = 0,
                                  $employee = null,
                                  Stock $stock = null)
    {
        $return = array();
        if (!Validate::isLoadedObject($warehouse) || !$quantity || !$id_product) {
            return $return;
        }
        if (!StockMvtReason::exists($id_stock_mvt_reason)) {
            $id_stock_mvt_reason = Configuration::get('PS_STOCK_MVT_DEC_REASON_DEFAULT');
        }
        $context = Context::getContext();
        if (Pack::isPack((int)$id_product) && !$ignore_pack) {
            if (Validate::isLoadedObject($product = new Product((int)$id_product))) {
                if ($product->pack_stock_type == 1 || $product->pack_stock_type == 2 || ($product->pack_stock_type == 3 && Configuration::get('PS_PACK_STOCK_TYPE') > 0)) {
                    $products_pack = Pack::getItems((int)$id_product, (int)Configuration::get('PS_LANG_DEFAULT'));
                    foreach ($products_pack as $product_pack) {
                        if ($product_pack->advanced_stock_management == 1) {
                            $product_warehouses = Warehouse::getProductWarehouseList($product_pack->id, $product_pack->id_pack_product_attribute);
                            $warehouse_stock_found = false;
                            foreach ($product_warehouses as $product_warehouse) {
                                if (!$warehouse_stock_found) {
                                    if (Warehouse::exists($product_warehouse['id_warehouse'])) {
                                        $current_warehouse = new Warehouse($product_warehouse['id_warehouse']);
                                        $return[] = $this->removeProduct($product_pack->id, $product_pack->id_pack_product_attribute, $current_warehouse, $product_pack->pack_quantity * $quantity, $id_stock_mvt_reason, $is_usable, $id_order);
                                        $warehouse_stock_found = !empty($return[count($return) - 1]);
                                    }
                                }
                            }
                        }
                    }
                }
                if ($product->pack_stock_type == 0 || $product->pack_stock_type == 2 ||
                    ($product->pack_stock_type == 3 && (Configuration::get('PS_PACK_STOCK_TYPE') == 0 || Configuration::get('PS_PACK_STOCK_TYPE') == 2))) {
                    $return = array_merge($return, $this->removeProduct($id_product, $id_product_attribute, $warehouse, $quantity, $id_stock_mvt_reason, $is_usable, $id_order, 1));
                }
            } else {
                return false;
            }
        } else {
            $physical_quantity_in_stock = (int)$this->getPhysicalQuantity($id_product, $id_product_attribute, array($warehouse->id));
            $usable_quantity_in_stock = (int)$this->getProductPhysicalQuantities($id_product, $id_product_attribute, array($warehouse->id), true);
            if (!$is_usable) {
                $quantity_in_stock = $physical_quantity_in_stock - $usable_quantity_in_stock;
            } else {
                $quantity_in_stock = $physical_quantity_in_stock;
            }
            if ($quantity_in_stock < $quantity) {
                return $return;
            }
            $stock_collection = $this->getStockCollection($id_product, $id_product_attribute, $warehouse->id);
            $stock_collection->getAll();
            if (count($stock_collection) <= 0) {
                return $return;
            }
            $stock_history_qty_available = array();
            $mvt_params = array();
            $stock_params = array();
            $quantity_to_decrement_by_stock = array();
            $global_quantity_to_decrement = $quantity;
            switch ($warehouse->management_type) {
                case 'WA':
                    
                    $stock = $stock_collection->current();
                    $mvt_params = array(
                        'id_stock' => $stock->id,
                        'physical_quantity' => $quantity,
                        'id_stock_mvt_reason' => $id_stock_mvt_reason,
                        'id_order' => $id_order,
                        'price_te' => $stock->price_te,
                        'last_wa' => $stock->price_te,
                        'current_wa' => $stock->price_te,
                        'id_employee' => (int)$context->employee->id ? (int)$context->employee->id : $employee->id,
                        'employee_firstname' => $context->employee->firstname ? $context->employee->firstname : $employee->firstname,
                        'employee_lastname' => $context->employee->lastname ? $context->employee->lastname : $employee->lastname,
                        'sign' => -1
                    );
                    $stock_params = array(
                        'physical_quantity' => ($stock->physical_quantity - $quantity),
                        'usable_quantity' => ($is_usable ? ($stock->usable_quantity - $quantity) : $stock->usable_quantity)
                    );
                    $stock->hydrate($stock_params);
                    $stock->update();
                    $stock_mvt = new StockMvt();
                    $stock_mvt->hydrate($mvt_params);
                    $stock_mvt->save();
                    $return[$stock->id]['quantity'] = $quantity;
                    $return[$stock->id]['price_te'] = $stock->price_te;
                break;
                case 'LIFO':
                case 'FIFO':
                    foreach ($stock_collection as $stock) {
                        
                        $left_quantity_to_check = $stock->physical_quantity;
                        if ($left_quantity_to_check <= 0) {
                            continue;
                        }
                        $resource = Db::getInstance(_PS_USE_SQL_SLAVE_)->query('
							SELECT sm.`id_stock_mvt`, sm.`date_add`, sm.`physical_quantity`,
								IF ((sm2.`physical_quantity` is null), sm.`physical_quantity`, (sm.`physical_quantity` - SUM(sm2.`physical_quantity`))) as qty
							FROM `'._DB_PREFIX_.'stock_mvt` sm
							LEFT JOIN `'._DB_PREFIX_.'stock_mvt` sm2 ON sm2.`referer` = sm.`id_stock_mvt`
							WHERE sm.`sign` = 1
							AND sm.`id_stock` = '.(int)$stock->id.'
							GROUP BY sm.`id_stock_mvt`
							ORDER BY sm.`date_add` DESC'
                        );
                        while ($row = Db::getInstance()->nextRow($resource)) {
                            if ($warehouse->management_type == 'FIFO') {
                                if ($row['qty'] == 0) {
                                    break;
                                }
                            }
                            $date = new DateTime($row['date_add']);
                            $timestamp = $date->format('U');
                            $stock_history_qty_available[$timestamp] = array(
                                'id_stock' => $stock->id,
                                'id_stock_mvt' => (int)$row['id_stock_mvt'],
                                'qty' => (int)$row['qty']
                            );
                            if ($warehouse->management_type == 'LIFO') {
                                $left_quantity_to_check -= (int)$row['qty'];
                                if ($left_quantity_to_check <= 0) {
                                    break;
                                }
                            }
                        }
                    }
                    if ($warehouse->management_type == 'LIFO') {
                        krsort($stock_history_qty_available);
                    } else {
                        ksort($stock_history_qty_available);
                    }
                    foreach ($stock_history_qty_available as $entry) {
                        if ($entry['qty'] >= $global_quantity_to_decrement) {
                            $quantity_to_decrement_by_stock[$entry['id_stock']][$entry['id_stock_mvt']] = $global_quantity_to_decrement;
                            $global_quantity_to_decrement = 0;
                        } else {
                            $quantity_to_decrement_by_stock[$entry['id_stock']][$entry['id_stock_mvt']] = $entry['qty'];
                            $global_quantity_to_decrement -= $entry['qty'];
                        }
                        if ($global_quantity_to_decrement <= 0) {
                            break;
                        }
                    }
                    foreach ($stock_collection as $stock) {
                        if (array_key_exists($stock->id, $quantity_to_decrement_by_stock) && is_array($quantity_to_decrement_by_stock[$stock->id])) {
                            $total_quantity_for_current_stock = 0;
                            foreach ($quantity_to_decrement_by_stock[$stock->id] as $id_mvt_referrer => $qte) {
                                $mvt_params = array(
                                    'id_stock' => $stock->id,
                                    'physical_quantity' => $qte,
                                    'id_stock_mvt_reason' => $id_stock_mvt_reason,
                                    'id_order' => $id_order,
                                    'price_te' => $stock->price_te,
                                    'sign' => -1,
                                    'referer' => $id_mvt_referrer,
                                    'id_employee' => (int)$context->employee->id ? (int)$context->employee->id : $employee->id,
                                );
                                $stock_mvt = new StockMvt();
                                $stock_mvt->hydrate($mvt_params);
                                $stock_mvt->save();
                                $total_quantity_for_current_stock += $qte;
                            }
                            $stock_params = array(
                                'physical_quantity' => ($stock->physical_quantity - $total_quantity_for_current_stock),
                                'usable_quantity' => ($is_usable ? ($stock->usable_quantity - $total_quantity_for_current_stock) : $stock->usable_quantity)
                            );
                            $return[$stock->id]['quantity'] = $total_quantity_for_current_stock;
                            $return[$stock->id]['price_te'] = $stock->price_te;
                            $stock->hydrate($stock_params);
                            $stock->update();
                        }
                    }
                break;
            }
            if (Pack::isPacked($id_product, $id_product_attribute)) {
                $packs = Pack::getPacksContainingItem($id_product, $id_product_attribute, (int)Configuration::get('PS_LANG_DEFAULT'));
                foreach($packs as $pack) {
                    if (!((int)$pack->pack_stock_type == 2) &&
                        !((int)$pack->pack_stock_type == 3 && (int)Configuration::get('PS_PACK_STOCK_TYPE') == 2)
                        ) {
                        continue;
                    }
                    
                    $quantity_by_pack = $pack->pack_item_quantity;
                    $stock_available_quantity = $quantity_in_stock - $quantity;
                    $max_pack_quantity = max(array(0, floor($stock_available_quantity / $quantity_by_pack)));
                    $quantity_delta = Pack::getQuantity($pack->id) - $max_pack_quantity;
                    if ($pack->advanced_stock_management == 1 && $quantity_delta > 0) {
                        $product_warehouses = Warehouse::getPackWarehouses($pack->id);
                        $warehouse_stock_found = false;
                        foreach ($product_warehouses as $product_warehouse) {
                            if (!$warehouse_stock_found) {
                                if (Warehouse::exists($product_warehouse)) {
                                    $current_warehouse = new Warehouse($product_warehouse);
                                    $return[] = $this->removeProduct($pack->id, null, $current_warehouse, $quantity_delta, $id_stock_mvt_reason, $is_usable, $id_order, 1);
                                    $warehouse_stock_found = !empty($return[count($return) - 1]);
                                }
                            }
                        }
                    }
                }
            }   
        }
        
        if ($id_stock_mvt_reason == Configuration::get('PS_STOCK_CUSTOMER_ORDER_REASON') && $id_order) {
            if ($is_usable) {
                Hook::exec('actionProductCoverage',
                    array(
                        'id_product' => $id_product,
                        'id_product_attribute' => $id_product_attribute,
                        'warehouse' => $warehouse
                    )
                );
            }
            return $return;
        }
        
        StockAvailable::synchronize($id_product);
        Cache::clean('StockAvailable::getQuantityAvailableByProduct_'.(int) $id_product.'*');
        
        if ($is_usable) {
            Hook::exec('actionProductCoverage',
                    array(
                        'id_product' => $id_product,
                        'id_product_attribute' => $id_product_attribute,
                        'warehouse' => $warehouse
                    )
            );
        }
        return $return;
    }
    
    /*
    * module: wic_erp
    * date: 2017-11-06 19:44:10
    * version: 2.9.9.1
    */
    public function synchronizeStockAvailable($quantity, $id_product, $id_product_attribute)
    {
    	$query = array(
                    'table' => 'stock_available',
                    'data' => array('quantity' => $quantity),
                    'where' => 'id_product = '.(int)$id_product.' AND id_product_attribute = '.(int)$id_product_attribute.
        			StockAvailable::addSqlShopRestriction(null, Context::getContext()->shop->id)
                );
        Db::getInstance()->update($query['table'], $query['data'], $query['where']);
    }
}
