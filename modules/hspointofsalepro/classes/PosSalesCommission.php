<?php
/**
 * RockPOS Sales commission
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 *
 */
class PosSalesCommission extends ObjectModel
{

    /**
     * Enables to define an ID before adding object.
     * @var boolean
     */
    public $force_id = true;

    /**
     * @var int
     */
    public $id_cart;

    /**
     * @var string
     */
    public $order_reference;

    /**
     * Id of sales commission employee
     * @var int
     */
    public $id_employee;

    /**
     * @var int
     */
    public $id_customer;

    /**
     * @var float
     */
    public $commission_rate;

    /**
     * @var float
     */
    public $commission_amount;

    /**
     * @var float
     */
    public $commission_percent;

    /**
     * @var datetime
     */
    public $date_add;

    /**
     * @var datetime
     */
    public $date_upd;

    /**
     * define all fields of table sales commission.
     * @var array
     */
    public static $definition = array(
        'table' => 'pos_sales_commission',
        'primary' => 'id_cart',
        'fields' => array(
            'order_reference' => array('type' => self::TYPE_STRING),
            'id_employee' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'id_customer' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'commission_rate' => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat'),
            'commission_amount' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'commission_percent' => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
        ),
    );

    /**
     *
     * @param int $id
     * @param int $id_lang
     * @param int $id_shop
     */
    public function __construct($id = null, $id_lang = null, $id_shop = null)
    {
        $id = $id ? $id : self::getIdCart();
        parent::__construct($id, $id_lang, $id_shop);
        $this->id_cart = $this->id_cart ? $this->id_cart : self::getIdCart();
    }

    /**
     * @see parent::save()
     * @param boolean $null_values
     * @param boolean $auto_date
     * @return boolean
     */
    public function save($null_values = false, $auto_date = true)
    {
        $sales_commission = new self($this->id);
        return Validate::isLoadedObject($sales_commission) ? $this->update($null_values) : $this->add($auto_date, $null_values);
    }

    /**
     * @see parent::formatFields()
     * Override: assign id_cart
     */
    protected function formatFields($type, $id_lang = null)
    {
        $fields = parent::formatFields($type, $id_lang);
        $fields['id_cart'] = empty($fields['id_cart']) ? $this->id_cart : $fields['id_cart'];
        if ($fields['id_cart'] == 0) {
            return array();
        }
        if (empty($fields['id_employee'])) {
            $fields['id_employee'] = $this->getIdEmployee();
        }
        return $fields;
    }

    /**
     *
     * @param string $order_reference
     * @return Employee
     */
    public static function getEmployee($order_reference)
    {
        $sql = new DbQuery();
        $sql->select('id_employee')->from(self::$definition['table'])->where('`order_reference` = "' . $order_reference . '"');
        $id_employee = Db::getInstance()->getValue($sql);
        $employee = new Employee((int) $id_employee);
        return $employee;
    }

    /**
     *
     * @return int
     */
    public function getIdEmployee()
    {
        $context = Context::getContext();
        return $this->id_employee ? (int) $this->id_employee : isset($context->employee) && Validate::isLoadedObject($context->employee) ? $context->employee->id : 0;
    }

    /**
     *
     * @param string $date_from
     * @param string $date_to
     * @return array
     * <pre/>
     *  array(
     *   array(
     *          employee => string,
     *          email => string,
     *          total_incl_tax => float,
     *          total_excl_tax => float,
     *          total_commission => float
     *    ),
     *    ...
     *
     * )
     */
    public static function getSalesCommission($date_from, $date_to)
    {
        $query = new DbQuery();
        $query->select('CONCAT(e.`firstname`, " " , e.`lastname`) as `employee`');
        $query->select('e.`email`');
        $query->select('IFNULL(SUM(o.`total_paid_tax_incl` / o.`conversion_rate`), 0) AS `total_incl_tax`');
        $query->select('IFNULL(SUM(o.`total_paid_tax_excl` / o.`conversion_rate`), 0) AS `total_excl_tax`');
        $query->select('IFNULL(SUM((o.`total_paid_tax_excl` / o.`conversion_rate`) * psc.`commission_rate`), 0) AS `total_commission`');
        $query->from('employee', 'e');
        $query->leftJoin('pos_sales_commission', 'psc', 'e.`id_employee` = psc.`id_employee`');
        $query->leftJoin('orders', 'o', 'o.`reference` = psc.`order_reference`');
        $query->where('o.`valid` = 1');
        $query->where('o.`date_add` BETWEEN \'' . pSQL($date_from) . ' 00:00:00\' AND \'' . pSQL($date_to) . ' 23:59:59\'');
        $query->groupBy('e.`id_employee`');
        return Db::getInstance()->executeS($query);
    }

    /**
     * @return int
     */
    protected static function getIdCart()
    {
        $id_cart = 0;
        if (!empty(Context::getContext()->cookie->pos_id_cart)) {
            $id_cart = Context::getContext()->cookie->pos_id_cart;
        } elseif (!empty(Context::getContext()->cart) && Context::getContext()->cart->id) {
            $id_cart = Context::getContext()->cart->id;
        }
        return (int) $id_cart;
    }

    /**
     *
     * @return array
     * <pre>
     * array(
     *  int => array(
     *      id_employee: int,
     *      fullname: string,
     *      selected: boolean,
     *  )
     * ...
     * )
     */
    public static function getSalesMen()
    {
        $employees = array();
        $pos_id_profiles = Configuration::get('POS_ID_PROFILES');
        if (!empty($pos_id_profiles)) {
            $sales_commission = new self();
            $current_employee = $sales_commission->getIdEmployee();
            $profile_ids = array_filter(explode(',', Configuration::get('POS_ID_PROFILES')));
            if (!empty($profile_ids)) {
                $employees = PosEmployee::getEmployeesByIdProfiles($profile_ids);
                foreach ($employees as &$employee) {
                    $employee['selected'] = $employee['id_employee'] == $current_employee;
                }
            }
        }
        return $employees;
    }
}
