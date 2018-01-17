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

class ErpProducts extends ObjectModel
{
    /** @var integer erp products ID */
    public $id_erp_products;

    /** @var integer product ID */
    public $id_product;

    /** @var integer product attribute ID */
    public $id_product_attribute;

    /** @var integer employee ID */
    public $id_employee;

    /** @var integer stock minimum quantity */
    public $min_quantity;

    /** @var integer unit order quantity */
    public $unit_order;

    /** @var integer bundling quantity */
    public $bundling;

    /** @var integer safety stock quantity */
    public $safety_stock;

    /** @var boolean manual configuration */
    public $manual_configuration;
    
    /** @var string Object creation date */
    public $date_add;

    /** @var string Object last modification date */
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'erp_products',
        'primary' => 'id_erp_products',
        'multilang' => false,
        'fields' => array(
            'id_product'                =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_product_attribute'      =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_employee'               =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'min_quantity'              =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'unit_order'                =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'bundling'                  =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'safety_stock'              =>    array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'manual_configuration'      =>    array('type' => self::TYPE_BOOL),
            'date_add'                  =>    array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => true),
            'date_upd'                  =>    array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => true),
        ),
    );

    public static function getNoSalesProducts()
    {
        $sql = 'SELECT
					p.`id_product`
				FROM
					`'._DB_PREFIX_.'product` p
				WHERE
					p.`id_product` NOT IN
							(SELECT
								od.`product_id`
							FROM
								`'._DB_PREFIX_.'order_detail` od
							LEFT JOIN
								`'._DB_PREFIX_.'orders` o ON (o.`id_order` = od.`id_order`)
							WHERE
								o.`date_add` BETWEEN
											DATE_SUB(\''.date('Y-m-d H:i:s').'\',
											INTERVAL '.(int)Configuration::get('WIC_ERP_STAT_DAYS').' DAY)
							AND \''.date('Y-m-d H:i:s').'\'
							)
				AND
					p.`active` = 1';

        $results = Db::getInstance()->ExecuteS($sql);

        if ($results) {
            return $results;
        }

        return false;
    }

    public static function getAllProducts(array $suppliers_update = array(), $limit = 0, $offset = 0)
    {
        $query = new DbQuery();
        $query->select('p.`id_product`');
        $query->from('product', 'p');
        $query->where('p.`active` = 1');

        if (!empty($suppliers_update)) {
            $query->where('`id_manufacturer` IN ('.pSQL(implode(',', $suppliers_update)).')');
        }

        if ($limit || $offset) {
            $query->limit($limit, $offset);
        }

        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($query);

        if ($results) {
            return $results;
        }

        return false;
    }

    public static function getCount()
    {
        $query = new DbQuery();
        $query->select('COUNT(p.`id_product`)');
        $query->from('product', 'p');
        $query->where('p.`active` = 1');
        return Db::getInstance()->getValue($query);
    }

    public static function updateErpProducts(array $suppliers_update = array(), $limit = 0, $offset = 0)
    {
        $products = ErpProducts::getAllProducts($suppliers_update, $limit, $offset);

        /*** NO PRODUCT SALE ***/
        //We verify if product as attribute
        foreach ($products as $product) {
            $product_obj = new Product($product['id_product']);

            if (Validate::isLoadedObject($product_obj)) {
                if ($product_obj->hasAttributes()) {
                    //we retrieve all attributes
                    $attributes = Db::getInstance()->ExecuteS('SELECT pa.`id_product_attribute`
                                                            FROM `'._DB_PREFIX_.'product_attribute` pa
                                                            WHERE pa.`id_product` ='.(int)$product_obj->id);

                    if ($attributes) {
                        foreach ($attributes as $attribute) {
                            $id_erp_products = ErpProducts::getProductById($product_obj->id, $attribute['id_product_attribute']);

                            if ($id_erp_products) {
                                $erp_products = new ErpProducts($id_erp_products);
                            } else {
                                $erp_products = new ErpProducts();
                            }

                            if (!$erp_products->manual_configuration) {
                                if (!isset(Context::getContext()->employee->id) || !Context::getContext()->employee->id) {
                                    $id_employee = 1;
                                } else {
                                    $id_employee = Context::getContext()->employee->id;
                                }

                                $erp_products->id_product = $product['id_product'];
                                $erp_products->id_product_attribute = $attribute['id_product_attribute'];
                                $erp_products->id_employee = (int)$id_employee;
                                $erp_products->min_quantity = Configuration::get('WIC_ERP_STK_MIN');
                                if (!$id_erp_products) {
                                    $erp_products->unit_order = 1;
                                }
                                $erp_products->safety_stock = Configuration::get('WIC_ERP_STK_SAFETY');
                                $erp_products->manual_configuration = 0;
                                $erp_products->save();
                            }
                            unset($erp_products, $id_erp_products);
                        }
                        unset($attributes);
                    }
                } else {
                    //We verify if product exists
                    $id_erp_products = ErpProducts::getProductById($product_obj->id);

                    if ($id_erp_products) {
                        $erp_products = new ErpProducts($id_erp_products);
                    } else {
                        $erp_products = new ErpProducts();
                    }

                    if (!$erp_products->manual_configuration) {
                        if (!isset(Context::getContext()->employee->id) || !Context::getContext()->employee->id) {
                            $id_employee = 1;
                        } else {
                            $id_employee = Context::getContext()->employee->id;
                        }

                        $erp_products->id_product = $product['id_product'];
                        $erp_products->id_product_attribute = 0;
                        $erp_products->id_employee = (int)$id_employee;
                        $erp_products->min_quantity = Configuration::get('WIC_ERP_STK_MIN');
                        $erp_products->safety_stock = Configuration::get('WIC_ERP_STK_SAFETY');
                        if (!$id_erp_products) {
                            $erp_products->unit_order = 1;
                        }
                        $erp_products->manual_configuration = 0;
                        $erp_products->save();
                    }
                    unset($erp_products, $id_erp_products);
                }
            }
            unset($product_obj);
        }
        unset($products);
        /*** END NO PRODUCT SALE ***/

        /*** PRODUCT SALE ***/
        $products = ErpProducts::getProductsSold();

        $products_by_date = ErpProducts::getProductsSoldByDate();

        /******** BY NORMAL DISTRIBUTION *********/
        if (Configuration::get('WIC_ERP_STOCK_METHOD') == 'normal') {
            $average_sold = array();
            foreach ($products as $product) {
                $average_sold[$product['id_product'].'_'.$product['product_attribute_id']]['averageQuantity'] = $product['averageQuantitySold'];
            }

            unset($products);

            foreach ($products_by_date as $product_by_date) {
                if (isset($average_sold[$product_by_date['id_product'].'_'.$product_by_date['product_attribute_id']]['averageQuantity'])) {
                    if (!isset($average_sold[$product_by_date['id_product'].'_'.$product_by_date['product_attribute_id']]['dataCount'])) {
                        $average_sold[$product_by_date['id_product'].'_'.$product_by_date['product_attribute_id']]['dataCount'] = 0;
                    }
                    if (!isset($average_sold[$product_by_date['id_product'].'_'.$product_by_date['product_attribute_id']]['variance'])) {
                        $average_sold[$product_by_date['id_product'].'_'.$product_by_date['product_attribute_id']]['variance'] = 0;
                    }
                    $average_sold[$product_by_date['id_product'].'_'.$product_by_date['product_attribute_id']]['variance'] += pow(($average_sold[$product_by_date['id_product'].'_'.$product_by_date['product_attribute_id']]['averageQuantity'] - ($product_by_date['totalQuantitySold'] / 7)), 2);
                    $average_sold[$product_by_date['id_product'].'_'.$product_by_date['product_attribute_id']]['dataCount'] += 1;
                }
            }

            unset($products_by_date);

            foreach ($average_sold as $key => $value) {
                $id = explode('_', $key);

                if ($id[0]) {
                    $id_product = $id[0];
                }

                if ($id[1]) {
                    $id_product_attribute = $id[1];
                } else {
                    $id_product_attribute = 0;
                }

                $id_erp_products = ErpProducts::getProductById($id_product, $id_product_attribute);

                if ($id_erp_products) {
                    $erp_products = new ErpProducts($id_erp_products);
                } else {
                    $erp_products = new ErpProducts();
                }

                if (!$erp_products->manual_configuration) {
                    $product_obj = new Product($id_product);
                    $id_supplier = $product_obj->id_supplier;

                    if ($id_supplier) {
                        $id_erp_supplier = ErpSuppliers::getSupplierById($id_supplier);
                        $erp_suppliers_obj = new ErpSuppliers($id_erp_supplier);
                        $duration = $erp_suppliers_obj->delivery;
                    } else {
                        $duration = Configuration::get('WIC_ERP_DELIVERY_SUPPLIERS');
                    }

                    $erp_products->id_product = $id_product;
                    $erp_products->id_product_attribute = $id_product_attribute;
                    $erp_products->id_employee = Context::getContext()->employee->id;
                    $erp_products->min_quantity = (int)ceil($value['averageQuantity'] * $duration);
                    $erp_products->safety_stock = (int)ceil(sqrt(($value['variance'] / Configuration::get('WIC_ERP_STAT_DAYS'))) * Configuration::get('WIC_ERP_STOCK_NORMAL_RATE') * $duration);
                    if (!$id_erp_products) {
                        $erp_products->unit_order = 1;
                    }
                    $erp_products->manual_configuration = 0;
                    $erp_products->save();

                    unset($product_obj, $id_supplier, $id_product, $id_product_attribute);
                }
                unset($erp_products);
            }
            unset($average_sold);
        } else {
            /************** BY EXPERT MODE ************/
            $average_sold = array();
            foreach ($products as $product) {
                $average_sold[$product['id_product'].'_'.$product['product_attribute_id']]['averageQuantity'] = $product['averageQuantitySold'];
            }

            unset($products);

            foreach ($products_by_date as $product_by_date) {
                if (!isset($average_sold[$product_by_date['id_product'].'_'.$product_by_date['product_attribute_id']]['maxValue'])) {
                    $average_sold[$product_by_date['id_product'].'_'.$product_by_date['product_attribute_id']]['maxValue'] = 0;
                }

                if ($average_sold[$product_by_date['id_product'].'_'.$product_by_date['product_attribute_id']]['maxValue'] < ($product_by_date['totalQuantitySold'] / 7)) {
                    $average_sold[$product_by_date['id_product'].'_'.$product_by_date['product_attribute_id']]['maxValue'] = ($product_by_date['totalQuantitySold'] / 7);
                }
            }
            unset($products_by_date);

            foreach ($average_sold as $key => $value) {
                $id = explode('_', $key);

                if ($id[0]) {
                    $id_product = $id[0];
                }

                if ($id[1]) {
                    $id_product_attribute = $id[1];
                } else {
                    $id_product_attribute = 0;
                }

                $id_erp_products = ErpProducts::getProductById($id_product, $id_product_attribute);

                if ($id_erp_products) {
                    $erp_products = new ErpProducts($id_erp_products);
                } else {
                    $erp_products = new ErpProducts();
                }

                if (!$erp_products->manual_configuration) {
                    $product_obj = new Product($id_product);
                    $id_supplier = $product_obj->id_supplier;

                    if ($id_supplier) {
                        $id_erp_supplier = ErpSuppliers::getSupplierById($id_supplier);
                        $erp_suppliers_obj = new ErpSuppliers($id_erp_supplier);
                        $duration = $erp_suppliers_obj->delivery;
                    } else {
                        $duration = Configuration::get('WIC_ERP_DELIVERY_SUPPLIERS');
                    }
                    //$coefAverage = $value['dataCount']/Configuration::get('WIC_ERP_STAT_DAYS');
                    $erp_products->id_product = $id_product;
                    $erp_products->id_product_attribute = $id_product_attribute;
                    $erp_products->id_employee = Context::getContext()->employee->id;
                    $erp_products->min_quantity = (int)ceil($value['averageQuantity'] * $duration);
                    $erp_products->safety_stock = (int)ceil((($value['maxValue']) - ($value['averageQuantity'])) / Configuration::get('WIC_ERP_STAT_DAYS') * $duration);
                    if (!$id_erp_products) {
                        $erp_products->unit_order = 1;
                    }
                    $erp_products->manual_configuration = 0;
                    $erp_products->save();

                    unset($product_obj, $id_supplier, $id_product, $id_product_attribute);
                }
                unset($erp_products);
            }
        }
        unset($average_sold);
        /*** PRODUCT SALE ***/
    }

    public static function getProductById($id_product, $id_product_attribute = 0)
    {
        $query = new DbQuery();
        $query->select('ep.`id_erp_products`');
        $query->from('erp_products', 'ep');
        $query->where('ep.`id_product` = '.(int)$id_product.' AND ep.`id_product_attribute` = '.(int)$id_product_attribute);

        $id_erp_products = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);

        if (!$id_erp_products) {
            return false;
        } else {
            return $id_erp_products;
        }
    }

    public static function getProductsSold()
    {
        $query = 'SELECT SQL_CALC_FOUND_ROWS p.id_product, od.product_attribute_id,
                        IFNULL(SUM(od.product_quantity), 0) AS totalQuantitySold,
                        ROUND(
                                IFNULL(
                                        IFNULL(
                                                SUM(od.product_quantity), 0) / (TO_DAYS(\''.date('Y-m-d 00:00:00').'\')
                                                        - GREATEST(
                                                        TO_DAYS(
                                                                DATE_SUB(\''.date('Y-m-d 00:00:00').'\',INTERVAL '.(int)Configuration::get('WIC_ERP_STAT_DAYS').' DAY))
                                                                        , TO_DAYS(product_shop.date_add))), 0), 2) as averageQuantitySold
                                FROM '._DB_PREFIX_.'product p
                                '.Shop::addSqlAssociation('product', 'p').'
                        LEFT JOIN '._DB_PREFIX_.'order_detail od ON od.product_id = p.id_product
                        LEFT JOIN '._DB_PREFIX_.'orders o ON od.id_order = o.id_order
                        WHERE o.valid = 1
                        AND o.invoice_date BETWEEN
                                DATE_SUB(\''.date('Y-m-d 00:00:00').'\',
                                INTERVAL '.(int)Configuration::get('WIC_ERP_STAT_DAYS').' DAY) AND \''.date('Y-m-d 00:00:00').'\'
                        GROUP BY p.id_product, od.product_attribute_id';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
    }

    public static function getProductsSoldByDate()
    {
        $query = 'SELECT SQL_CALC_FOUND_ROWS p.id_product, od.product_attribute_id,
                        IFNULL(SUM(od.product_quantity), 0) AS totalQuantitySold,
                        WEEK(o.`invoice_date`) AS week_name
                                FROM '._DB_PREFIX_.'product p
                                '.Shop::addSqlAssociation('product', 'p').'
                        LEFT JOIN '._DB_PREFIX_.'order_detail od ON od.product_id = p.id_product
                        LEFT JOIN '._DB_PREFIX_.'orders o ON od.id_order = o.id_order
                        WHERE o.`valid`= 1
                        AND o.`invoice_date` BETWEEN
                                DATE_SUB(\''.date('Y-m-d 00:00:00').'\',
                                INTERVAL '.(int)Configuration::get('WIC_ERP_STAT_DAYS').' DAY) AND \''.date('Y-m-d 00:00:00').'\'
                        GROUP BY week_name, p.id_product, od.product_attribute_id';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
    }

    public static function productsExist()
    {
        $query = new DbQuery();
        $query->select('ep.`id_erp_products`');
        $query->from('erp_products', 'ep');

        $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        if (!$products) {
            return false;
        } else {
            return true;
        }
    }

    public function getAllProductAttributes($id_product)
    {
        $query = 'SELECT
                        *
                FROM '._DB_PREFIX_.'erp_products ep
                WHERE ep.`id_product` = '.(int)$id_product.'
                ';

        $products = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        if (!$products) {
            return false;
        } else {
            return $products;
        }
    }
    
    public static function verifyCombination($id_product)
    {
        $query = new DbQuery();
        $query->select('COUNT(ep.`id_erp_products`)');
        $query->from('erp_products', 'ep');
        $query->where('ep.`id_product` = '.(int)$id_product.' AND ep.`id_product_attribute` != 0');

        $count_value = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);

        if (!$count_value) {
            return false;
        } else {
            return $count_value;
        }
    }
}
