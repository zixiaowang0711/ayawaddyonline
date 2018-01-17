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

class ErpOrderReceiptHistory extends ObjectModel
{
    /**
     * @var int Detail of the just in time order (i.e. One particular product)
     */
    public $id_erp_order_detail;

    /**
     * @var int Employee
     */
    public $id_employee;

    /**
     * @var string The first name of the employee responsible of the movement
     */
    public $employee_firstname;

    /**
     * @var string The last name of the employee responsible of the movement
     */
    public $employee_lastname;

    /**
     * @var int State
     */
    public $id_erp_order_state;

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
        'table' => 'erp_order_receipt_history',
        'primary' => 'id_erp_order_receipt_history',
        'fields' => array(
            'id_erp_order_detail'    => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_erp_order_state'    => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_employee'            => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'employee_firstname'    => array('type' => self::TYPE_STRING, 'validate' => 'isName'),
            'employee_lastname'    => array('type' => self::TYPE_STRING, 'validate' => 'isName'),
            'quantity'                => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedInt', 'required' => true),
            'date_add'                => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        ),
    );
}
