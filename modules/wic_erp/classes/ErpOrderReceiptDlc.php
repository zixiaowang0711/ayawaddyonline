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

class ErpOrderReceiptDlc extends ObjectModel
{
    /**
     * @var int Detail of the just in time order (i.e. One particular product)
     */
    public $id_erp_order_receipt_dlc_bbd;
    /**
     * @var int Detail of the just in time order (i.e. One particular product)
     */
    public $id_erp_order_receipt_history;

    /**
     * @var string Batch Number
     */
    public $batch_number;

    /**
     * @var date DLC
     */
    public $dlc;
    
    /**
     * @var Date BBD Best before date
     */
    public $bbd;

    /**
     * @var int Quantity delivered
     */
    public $quantity;

    /**
     * @var string Date of delivery
     */
    public $date_add;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'erp_order_receipt_dlc_bbd',
        'primary' => 'id_erp_order_receipt_dlc_bbd',
        'fields' => array(
            'id_erp_order_receipt_history'  => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'batch_number'                  => array('type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true),
            'dlc'                           => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'bbd'                           => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'quantity'                      => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'current_stock'                 => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'date_add'                      => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        ),
    );
}
