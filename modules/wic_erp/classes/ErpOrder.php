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

class ErpOrder extends ObjectModel
{
    /**
     * @var int Supplier
     */
    public $id_supplier;

    /**
     * @var string Supplier Name
     */
    public $supplier_name;

    /**
     * @var int Current state of the order
     */
    public $id_erp_order_state;

    /**
     * @var int Currency used for the order
     */
    public $id_currency;

    /**
     * @var string Reference of the order
     */
    public $reference;

    /**
     * @var string Date when added
     */
    public $date_add;

    /**
     * @var string Date when updated
     */
    public $date_upd;

    /**
     * @var string Date when updated
     */
    public $date_delivery_expected;

    /**
     * @var float Total price without tax
     */
    public $total_te = 0;

    /**
     * @var float Total price after discount, without tax
     */
    public $total_with_discount_te = 0;

    /**
     * @var float Total price with tax
     */
    public $total_ti = 0;

    /**
     * @var float Total tax value
     */
    public $total_tax = 0;

    /**
     * @var float Supplier discount rate (for the whole order)
     */
    public $discount_rate = 0;

    /**
     * @var float Supplier discount value without tax (for the whole order)
     */
    public $discount_value_te = 0;

    /**
     * @var float Supplier shipping value without tax (for the whole order)
     */
    public $shipping_cost = 0;

    /**
     * @var float Supplier shipping tax rate (for the whole order)
     */
    public $shipping_tax_rate = 0;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'erp_order',
        'primary' => 'id_erp_order',
        'fields' => array(
            'id_supplier'                => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'supplier_name'            => array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName', 'required' => false),
            'id_erp_order_state'        => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'id_currency'                => array('type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true),
            'reference'                => array('type' => self::TYPE_STRING, 'validate' => 'isGenericName', 'required' => true),
            'date_add'                    => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_upd'                    => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'date_delivery_expected'    => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
            'total_te'                    => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_with_discount_te'    => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_ti'                    => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'total_tax'                => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'discount_rate'            => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => false),
            'discount_value_te'            => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'shipping_cost'                => array('type' => self::TYPE_FLOAT, 'validate' => 'isPrice'),
            'shipping_tax_rate'        => array('type' => self::TYPE_FLOAT, 'validate' => 'isFloat', 'required' => false),
        ),
    );

    /**
     * @see ObjectModel::update()
     */
    public function update($null_values = false)
    {
        $this->calculatePrices();

        $res = parent::update($null_values);

        if ($res) {
            $this->addHistory();
        }

        return $res;
    }

    /**
     * @see ObjectModel::add()
     */
    public function add($autodate = true, $null_values = false)
    {
        $this->calculatePrices();

        $res = parent::add($autodate, $null_values);

        if ($res) {
            $this->addHistory();
        }

        return $res;
    }

    /**
     * Checks all products in this order and calculate prices
     * Applies the global discount if necessary
     */
    protected function calculatePrices()
    {
        $this->total_te = 0;
        $this->total_with_discount_te = 0;
        $this->total_tax = 0;
        $this->total_ti = 0;
        $is_discount = false;

        if (is_numeric($this->discount_rate) && (float)$this->discount_rate >= 0) {
            $is_discount = true;
        }

        // gets all product entries in this order
        $entries = $this->getEntriesCollection();

        foreach ($entries as $entry) {
            // applys global discount rate on each product if possible
            if ($is_discount) {
                $entry->applyGlobalDiscount((float)$this->discount_rate);
            }

            // adds new prices to the total
            $this->total_te += $entry->price_with_discount_te;
            $this->total_with_discount_te += $entry->price_with_order_discount_te;
            $this->total_tax += $entry->tax_value_with_order_discount;
            $this->total_ti = $this->total_tax + $this->total_with_discount_te;
        }
        $shipping_tax =  ($this->shipping_cost * (1 + ($this->shipping_tax_rate / 100))) - $this->shipping_cost;
        $this->total_tax += round($shipping_tax, 2);
        $this->total_ti += round($this->shipping_cost + $shipping_tax, 2);
        // applies global discount rate if possible
        if ($is_discount) {
            $this->discount_value_te = $this->total_te - $this->total_with_discount_te;
        }
    }

    /**
     * Retrieves the product entries for the current order
     *
     * @param int $id_lang Optional Id Lang - Uses Context::language::id by default
     * @return array
     */
    public function getEntries($id_lang = null)
    {
        if ($id_lang == null) {
            $id_lang = Context::getContext()->language->id;
        }

        // build query
        $query = new DbQuery();

        $query->select('
			s.*,
			IFNULL(CONCAT(pl.name, \' : \', GROUP_CONCAT(agl.name, \' - \', al.name SEPARATOR \', \')), pl.name) as name_displayed');

        $query->from('erp_order_detail', 's');

        $query->innerjoin('product_lang', 'pl', 'pl.id_product = s.id_product AND pl.id_lang = '.(int)$id_lang);

        $query->leftjoin('product', 'p', 'p.id_product = s.id_product');
        $query->leftjoin('product_attribute_combination', 'pac', 'pac.id_product_attribute = s.id_product_attribute');
        $query->leftjoin('attribute', 'atr', 'atr.id_attribute = pac.id_attribute');
        $query->leftjoin('attribute_lang', 'al', 'al.id_attribute = atr.id_attribute AND al.id_lang = '.(int)$id_lang);
        $query->leftjoin('attribute_group_lang', 'agl', 'agl.id_attribute_group = atr.id_attribute_group AND agl.id_lang = '.(int)$id_lang);

        $query->where('s.id_erp_order = '.(int)$this->id);
        $query->orderBy('s.reference DESC');
        $query->groupBy('s.id_erp_order_detail');

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
    }

    /**
     * Retrieves the details entries (i.e. products) collection for the current order
     *
     * @return Collection of SupplyOrderDetail
     */
    public function getEntriesCollection()
    {
        $details = new Collection('ErpOrderDetail');
        $details->where('id_erp_order', '=', (int)$this->id);
        return $details;
    }

    /**
     * Retrieves the details entries (i.e. products) collection for the current order
     *
     * @return Collection of SupplyOrderDetail
     */
    public function getEntriesCollectionMin()
    {
        $details = new Collection('ErpOrderDetail');
        $details->where('id_erp_order', '=', (int)$this->id);
        $details->groupBy('id_product');

        return $details;
    }

    /**
     * Check if the order has entries
     *
     * @return bool Has/Has not
     */
    public function hasEntries()
    {
        $query = new DbQuery();
        $query->select('COUNT(*)');
        $query->from('erp_order_detail', 's');
        $query->where('s.id_erp_order = '.(int)$this->id);

        return (Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query) > 0);
    }

    /**
     * Check if the current state allows to edit the current order
     *
     * @return bool
     */
    public function isEditable()
    {
        $query = new DbQuery();
        $query->select('s.editable');
        $query->from('erp_order_state', 's');
        $query->where('s.id_erp_order_state = '.(int)$this->id_erp_order_state);

        return (Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query) == 1);
    }

    /**
     * Checks if the current state allows to generate a delivery note for this order
     *
     * @return bool
     */
    public function isDeliveryNoteAvailable()
    {
        $query = new DbQuery();
        $query->select('s.delivery_note');
        $query->from('erp_order_state', 's');
        $query->where('s.id_erp_order_state = '.(int)$this->id_erp_order_state);

        return (Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query) == 1);
    }

    /**
     * Checks if the current state allows to add products in stock
     *
     * @return bool
     */
    public function isInReceiptState()
    {
        $query = new DbQuery();
        $query->select('s.receipt_state');
        $query->from('erp_order_state', 's');
        $query->where('s.id_erp_order_state = '.(int)$this->id_erp_order_state);

        return (Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query) == 1);
    }

    /**
     * Historizes the order : its id, its state, and the employee responsible for the current action
     */
    protected function addHistory()
    {
        if (!$this->getLastOrderState($this->id_erp_order_state)) {
            $context = Context::getContext();
            $history = new ErpOrderHistory();
            $history->id_erp_order = $this->id;
            $history->id_state = $this->id_erp_order_state;
            $history->id_employee = (int)$context->employee->id;
            $history->employee_firstname = pSQL($context->employee->firstname);
            $history->employee_lastname = pSQL($context->employee->lastname);

            $history->save();
        }
    }

    /**
     * Removes all products from the order
     */
    public function resetProducts()
    {
        $products = $this->getEntriesCollection();

        foreach ($products as $p) {
            $p->delete();
        }
    }


    /**
     * For a given $id_supplier, tells if it has pending supply orders
     *
     * @param int $id_supplier Id Supplier
     * @return bool
     */
    public static function supplierHasPendingOrders($id_supplier)
    {
        if (!$id_supplier) {
            return false;
        }

        $query = new DbQuery();
        $query->select('COUNT(so.id_erp_order) as erp_orders');
        $query->from('erp_order', 'so');
        $query->leftJoin('erp_order_state', 'sos', 'so.id_erp_order_state = sos.id_erp_order_state');
        $query->where('sos.enclosed != 1');
        $query->where('so.id_supplier = '.(int)$id_supplier);

        $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
        return ($res > 0);
    }

    public static function supplierHasCreateOrders()
    {
        $query = new DbQuery();
        $query->select('so.id_erp_order');
        $query->from('erp_order', 'so');
        $query->leftJoin('erp_order_state', 'sos', 'so.id_erp_order_state = sos.id_erp_order_state');
        $query->where('sos.editable = 1');

        $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        return ($res);
    }
    /**
     * For a given id or reference, tells if the erp order exists
     *
     * @param int|string $match Either the reference of the order, or the Id of the order
     * @return int ErpOrder Id
     */
    public static function exists($match)
    {
        if (!$match) {
            return false;
        }

        $query = new DbQuery();
        $query->select('id_erp_order');
        $query->from('erp_order', 'so');
        $query->where('so.id_erp_order = '.(int)$match.' OR so.reference = "'.pSQL($match).'"');

        $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
        return ((int)$res);
    }

    /**
     * For a given reference, returns the corresponding supply order
     *
     * @param string $reference Reference of the order
     * @return bool|SupplyOrder
     */
    public static function getSupplyOrderByReference($reference)
    {
        if (!$reference) {
            return false;
        }

        $query = new DbQuery();
        $query->select('id_erp_order');
        $query->from('erp_order', 'so');
        $query->where('so.reference = "'.pSQL($reference).'"');
        $id = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);

        if ($id == false) {
            return false;
        }

        return (new ErpOrder((int)$id));
    }

    /**
     * Gets the reference of a given order
     *
     * @param int $id_supply_order
     * @return bool|string
     */
    public static function getReferenceById($id_erp_order)
    {
        if (!$id_erp_order) {
            return false;
        }

        $query = new DbQuery();
        $query->select('so.reference');
        $query->from('erp_order', 'so');
        $query->where('so.id_erp_order = '.(int)$id_erp_order);
        $ref = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);

        return (pSQL($ref));
    }

    public function getLastOrderState($id_state)
    {
        return Db::getInstance()->getValue('SELECT `id_erp_order`
											FROM `'._DB_PREFIX_.'erp_order_history`
											WHERE `id_state` = '.(int)$id_state.'
											AND `id_erp_order` = '.(int)$this->id.'
											ORDER BY `date_add` DESC;');
    }

    /**
     * @see ObjectModel::hydrate()
     */
    public function hydrate(array $data, $id_lang = null)
    {
        $this->id_lang = $id_lang;
        if (isset($data[$this->def['primary']])) {
            $this->id = $data[$this->def['primary']];
        }
        foreach ($data as $key => $value) {
            if (array_key_exists($key, $this)) {
                // formats prices and floats
                if ($this->def['fields'][$key]['validate'] == 'isFloat' ||
                    $this->def['fields'][$key]['validate'] == 'isPrice') {
                    $value = Tools::ps_round($value, 6);
                }
                $this->$key = $value;
            }
        }
    }
}
