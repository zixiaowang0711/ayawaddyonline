<?php
/**
 * RockPOS - Point of Sale for PrestaShop
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

require_once dirname(__FILE__) . '/AdminRockPosCommon.php';
/**
 * Controller of admin page - Point Of Sale
 */
class AdminRockPosSalesController extends AdminRockPosCommon
{

    /**
     *
     * @return array
     * <pre>
     * array(
     *   string => string,// uri => media_type. Fx: 'path/to/css/file' => 'all'
     *   string => string,
     * ...
     * )
     */
    protected $module_media_css = array(
        'admin_sales.css' => 'all',
    );

    /**
     *  @see parent::__construct()
     */
    public function __construct()
    {
        parent::__construct();
        $pos_employee = new PosEmployee($this->context->employee->id);// To make sure we're playing with PosEmployee, instead of Employee object
        $this->context->shop = new Shop($this->context->shop->id); // To make sure "virtual_uri" is set in default shop
        $this->module->setToken($pos_employee->getToken());
        Tools::redirect($this->context->link->getModuleLink($this->module->name, 'sales', array('token' => $pos_employee->getToken())));
    }
}
