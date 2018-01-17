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

class ErpSuppliers extends ObjectModel
{
    /** @var integer supplier ID */
    public $id_erp_suppliers;

    /** @var integer employee ID */
    public $id_employee;

    /** @var integer supplier ID */
    public $id_supplier;
        
        /** @var integer supplier ID */
    public $id_lang;

    /** @var string e-mail */
    public $email;

    /** @var integer delivery day */
    public $delivery;

    /** @var integer delivery variation day */
    public $delivery_change;

    /** @var boolean manual configuration */
    public $manual_configuration;
    
    /** @var boolean vat exemption */
    public $vat_exemption = 0;

    /** @var float minimum order price */
    public $minimum_price = 0;

    /** @var float minimum order price to free shipping */
    public $minimum_price_free_shipping = 0;

    /** @var float minimum order price to free shipping */
    public $shipping_price = 0;

    /** @var string Object creation date */
    public $date_add;

    /** @var string Object last modification date */
    public $date_upd;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'erp_suppliers',
        'primary' => 'id_erp_suppliers',
        'multilang' => false,
        'fields' => array(
            'id_supplier'                => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_lang'                    => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'id_employee'                    => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'email'                            => array('type' => self::TYPE_STRING, 'size' => 128),
            'delivery'                        => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'delivery_change'                => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId'),
            'manual_configuration'            => array('type' => self::TYPE_BOOL),
            'vat_exemption'                     => array('type' => self::TYPE_BOOL),
            'minimum_price'                    => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'minimum_price_free_shipping'    => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'shipping_price'                => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'date_add'                        => array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => true),
            'date_upd'                        => array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => true),
        ),
    );

    public static function suppliersExist()
    {
        $query = new DbQuery();
        $query->select('es.`id_erp_suppliers`');
        $query->from('erp_suppliers', 'es');

        $suppliers = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        if (!$suppliers) {
            return false;
        } else {
            return true;
        }
    }

    public static function getSupplierById($id_supplier)
    {
        $query = new DbQuery();
        $query->select('es.`id_erp_suppliers`');
        $query->from('erp_suppliers', 'es');
        $query->where('es.`id_supplier` = '.(int)$id_supplier);
        $id_erp_suppliers = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
        if (!$id_erp_suppliers) {
            return false;
        } else {
            return $id_erp_suppliers;
        }
    }
}
