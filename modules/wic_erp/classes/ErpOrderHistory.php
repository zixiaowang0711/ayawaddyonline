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

class ErpOrderHistory extends ObjectModel
{
    /**
     * @var int Just In Time order Id
     */
    public $id_erp_order;

    /**
     * @var int Employee Id
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
     * @var int State of the supply order
     */
    public $id_state;

    /**
     * @var string Date
     */
    public $date_add;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'erp_order_history',
        'primary' => 'id_erp_order_history',
        'fields' => array(
            'id_erp_order'            => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_employee'            => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'employee_firstname'    => array('type' => self::TYPE_STRING, 'validate' => 'isName'),
            'employee_lastname'    => array('type' => self::TYPE_STRING, 'validate' => 'isName'),
            'id_state'                => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'date_add'                => array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => true),
        ),
    );
}
