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

class ErpOrderAttachement extends ObjectModel
{
    /**
     * @var int id erp order attachement
     */
    public $id_erp_order_attachement;

    /**
     * @var int erp order id
     */
    public $id_erp_order;

    /**
     * @var string the name of file
     */
    public $file_name;

    /**
     * @var string name of file
     */
    public $name;

    /**
     * @var string Date
     */
    public $date_add;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'erp_order_attachement',
        'primary' => 'id_erp_order_attachement',
        'fields' => array(
            'id_erp_order_attachement'    => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_erp_order'                => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'file_name'                => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
            'name'                        => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml'),
            'date_add'                    => array('type' => self::TYPE_DATE, 'validate' => 'isDate', 'required' => true),
        ),
    );
}
