<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 *
 */
class PosOrder extends Order
{
    /**
     * @see parent::getByReference()
     * Override:
     * - Return a PrestaShopCollection of PosOrder, instead of Order
     */
    public static function getByReference($reference, $valid = null)
    {
        $orders = new PrestaShopCollection('PosOrder');
        $orders->where('reference', '=', $reference);
        if (!is_null($valid)) {
            $orders->where('valid', '=', $valid ? 1 : 0);
        }
        return $orders;
    }

    protected function mergeSiblingOrders()
    {
        $fields = array_keys($this->def['fields']);
        $total_fields = array();
        foreach ($fields as $field) {
            if (strpos($field, 'total_') === 0) {
                // Let's sum all "total_xxx" fields from these orders
                $total_fields[] = $field;
            }
        }
        $query = new DbQuery();
        $query->from('orders', 'o');
        $query->where('o.`reference` = "' . pSQL($this->reference) . '"');
        $query->groupBy('reference');
        foreach ($total_fields as $total_field) {
            $query->select('SUM(`' . $total_field . '`) AS `' . $total_field . '`');
        }
        $totals = Db::getInstance()->getRow($query);
        if ($totals) {
            $this->hydrate($totals, $this->id_lang);
        }
    }

    /**
     * @see parent::getInvoicesCollection()
     * Override:<br/>
     * - Return PrestaShopCollection of PosOrderInvoice (instead of OrderInvoice)
     */
    public function getInvoicesCollection()
    {
        $order_invoices = new PrestaShopCollection('PosOrderInvoice');
        $order_invoices->where('id_order', '=', $this->id);

        return $order_invoices;
    }

    /**
     * @see parent::getProducts()
     * Overrides:<br/>
     * - Add extra info for each product<br/>
     *      + name<br/>
     *      + combination<br/>
     */
    public function getProducts($products = false, $selected_products = false, $selected_qty = false)
    {
        if (!$products) {
            $products = parent::getProducts($products, $selected_products, $selected_qty);
        }
        foreach ($products as &$product) {
            // Most likely, both "product.id_product" and "order_detail.product_id" are returned from self::getProducts()
            // But sometimes, people make customization and might get rid of "product.id_product".
            // This fix is to make sure, we always can be able to get product id
            $id_product = isset($product['id_product']) ? $product['id_product'] : $product['product_id'];
            $product['name'] = $this->getProductName($product);
            if ($product['product_price'] > $product['original_product_price']) {
                $product['original_product_price'] = $product['product_price'];
                $product['product_original_price'] = $product['product_price'];
            }
            $product['combination'] = $this->getProductCombination($product);
            $product['manufacturer_name'] = Manufacturer::getNameById($product['id_manufacturer']);
            $product['product_price_tax_excl'] = $product['product_price']; // @see OrderDetail::setDetailProductPrice()
            $product['product_price_tax_incl'] = PosProduct::getPriceStatic($id_product, true, $product['product_attribute_id'], 2, null, false, false, 1, false, null, $this->id_cart);
            $product['price_without_specific_price'] = $product['product_price_tax_incl']; //@todo To remove this
        }

        return $products;
    }

    /**
     * Get product name only, without combination.
     *
     * @param array $order_detail an element of parent::getProducts()
     *
     * @return string
     */
    protected function getProductName(array $order_detail)
    {
        $product_name = explode('-', $order_detail['product_name']);
        return trim($product_name[0]);
    }

    /**
     * @param array $order_detail an element of parent::getProducts()
     *
     * @return string
     */
    protected function getProductCombination(array $order_detail)
    {
        $product_name = explode('-', $order_detail['product_name']);
        $combination = '';
        if (isset($product_name[1])) {
            $combination = $product_name[1];
        }
        if (isset($product_name[2])) {
            $combination .= ',' . $product_name[2];
        }
        return trim($combination);
    }

    /**
     *
     * @param int $id_customer
     * @param int $page
     * @param int $limit
     * @return array
     * <pre>
     * array(
     *  int => array(
     *      reference => string,
     *      date_upd => datetime,
     *      total_paid => float,
     *      payment => string,
     *      url => string,
     *  )
     * )
     */
    public static function getOrdersByIdCustomer($id_customer, $page = 1, $limit = null)
    {
        $page = max((int) $page, 1);
        $limit = !empty($limit) ? $limit : Configuration::get('POS_ORDERHISTORY_ITEMS_PER_PAGE');
        $sql = new DbQuery();
        $sql->select('o.`id_order`');
        $sql->select('o.`reference`');
        $sql->select('o.`date_upd`');
        $sql->select('o.`total_paid`');
        $sql->select('o.`payment`');
        $sql->select('osl.`name` AS `status`');
        $sql->from('orders', 'o');
        $sql->leftjoin('order_state_lang', 'osl', 'o.`id_lang`= osl.`id_lang` AND osl.`id_order_state` = o.`current_state`');
        $sql->where('`id_customer` =  ' . (int) $id_customer);
        $sql->limit($limit, ($page - 1) * $limit);
        $results = Db::getInstance()->executeS($sql);
        foreach ($results as &$record) {
            $record['url'] = Context::getContext()->link->getAdminLink('AdminOrders', false) . '&vieworder&id_order=' . $record['id_order'];
            unset($record['id_order']);
        }
        return $results;
    }

    /**
     *
     * @param string $reference
     * @param int $page
     * @param int $limit
     * @return array
     * <pre>
     * array(
     *  array(
     *      'id_cart' => int,
     *      'id_order' => int,
     *      'id_currency' => int,
     *      'reference' => string,
     *      'date_add' => datetime,
     *      'total_paid' => float,
     *      'payment' => string,
     *      'status' => string,
     *      'firstname' => string,
     *      'lastname' => string,
     *      'return_product' => int,
     *      'order_returned' => int,
     *
     *  )
     * )
     */
    public static function searchByReference($reference, $page = 1, $limit = null)
    {
        if (empty($reference)) {
            return array();
        }
        $query = new DbQuery();
        $query->select('o.`id_cart`');
        $query->select('o.`id_order`');
        $query->select('o.`id_currency`');
        $query->select('o.`reference`');
        $query->select('o.`date_add`');
        $query->select('o.`total_paid`');
        $query->select('o.`payment`');
        $query->select('osl.`name` AS `status`');
        $query->select('IF(os.`shipped` = 1 OR os.`delivery` = 1, 1, 0  ) AS `return_product`');
        $query->select('NULLIF(c.`firstname`,"' . pSQL(PosConstants::NOT_AVAILABLE) . '") AS `firstname`');
        $query->select('NULLIF(c.`lastname`,"' . pSQL(PosConstants::NOT_AVAILABLE) . '") AS `lastname`');
        $query->select('IF(ISNULL(pe.`id_order_old`), 0, 1) AS `order_returned`');
        $query->from('orders', 'o');
        $query->leftjoin('order_state', 'os', 'o.`current_state`= os.`id_order_state`');
        $query->leftjoin('order_state_lang', 'osl', 'o.`id_lang`= osl.`id_lang` AND osl.`id_order_state` = o.`current_state`');
        $query->leftjoin('customer', 'c', 'o.`id_customer`= c.`id_customer`');
        $query->leftjoin('pos_exchange', 'pe', 'pe.`id_order_old`= o.`id_order`');
        $query->where('o.`reference` LIKE "%' . pSQL($reference) . '%"');
        $query->limit((int) $limit, (max((int) $page, 1) - 1) * (int) $limit);
        return Db::getInstance()->executeS($query);
    }

    /**
     *
     * @param int $id_customer
     * @param int $page
     * @param int $limit
     * @return array
     * <pre>
     * array(
     *  array(
     *      'id_cart' => int,
     *      'id_order' => int,
     *      'id_currency' => int,
     *      'reference' => string,
     *      'date_add' => datetime,
     *      'total_paid' => float,
     *      'payment' => string,
     *      'status' => string,
     *      'firstname' => string,
     *      'lastname' => string,
     *      'return_product' => int,
     *      'order_returned' => int,
     *  )
     * )
     */
    public static function searchByIdCustomer($id_customer = 0, $page = 1, $limit = null)
    {
        if (empty($id_customer)) {
            return array();
        }
        $query = new DbQuery();
        $query->select('o.`id_cart`');
        $query->select('o.`id_order`');
        $query->select('o.`id_currency`');
        $query->select('o.`reference`');
        $query->select('o.`date_add`');
        $query->select('o.`total_paid`');
        $query->select('o.`payment`');
        $query->select('osl.`name` AS `status`');
        $query->select('IF(os.`shipped` = 1 OR os.`delivery` = 1, 1, 0  ) AS `return_product`');
        $query->select('NULLIF(c.`firstname`,"' . pSQL(PosConstants::NOT_AVAILABLE) . '") AS `firstname`');
        $query->select('NULLIF(c.`lastname`,"' . pSQL(PosConstants::NOT_AVAILABLE) . '") AS `lastname`');
        $query->select('IF(ISNULL(pe.`id_order_old`), 0, 1) AS `order_returned`');
        $query->from('orders', 'o');
        $query->leftjoin('order_state', 'os', 'o.`current_state`= os.`id_order_state`');
        $query->leftjoin('order_state_lang', 'osl', 'o.`id_lang`= osl.`id_lang` AND osl.`id_order_state` = o.`current_state`');
        $query->leftjoin('customer', 'c', 'o.`id_customer`= c.`id_customer`');
        $query->leftjoin('pos_exchange', 'pe', 'pe.`id_order_old`= o.`id_order`');
        $query->where('o.`id_customer` =  ' . (int) $id_customer);
        $query->limit((int) $limit, (max((int) $page, 1) - 1) * (int) $limit);
        return Db::getInstance()->executeS($query);
    }

    /**
     *
     * @param int $id_order
     * @param int $page
     * @param int $limit
     * @return array
     * <pre>
     * array(
     *      'id_cart' => int,
     *      'id_order' => int,
     *      'id_currency' => int,
     *      'reference' => string,
     *      'date_add' => datetime,
     *      'total_paid' => float,
     *      'payment' => string,
     *      'status' => string,
     *      'firstname' => string,
     *      'lastname' => string,
     *      'return_product' => int,
     *      'order_returned' => int,
     * )
     */
    public static function searchById($id_order, $page = 1, $limit = null)
    {
        if (empty($id_order)) {
            return array();
        }
        $query = new DbQuery();
        $query->select('o.`id_cart`');
        $query->select('o.`id_order`');
        $query->select('o.`id_currency`');
        $query->select('o.`reference`');
        $query->select('o.`date_add`');
        $query->select('o.`total_paid`');
        $query->select('o.`payment`');
        $query->select('osl.`name` AS `status`');
        $query->select('IF(os.`shipped` = 1 OR os.`delivery` = 1, 1, 0  ) AS `return_product`');
        $query->select('NULLIF(c.`firstname`,"' . pSQL(PosConstants::NOT_AVAILABLE) . '") AS `firstname`');
        $query->select('NULLIF(c.`lastname`,"' . pSQL(PosConstants::NOT_AVAILABLE) . '") AS `lastname`');
        $query->select('IF(ISNULL(pe.`id_order_old`), 0, 1) AS `order_returned`');
        $query->from('orders', 'o');
        $query->leftjoin('order_state', 'os', 'o.`current_state`= os.`id_order_state`');
        $query->leftjoin('order_state_lang', 'osl', 'o.`id_lang`= osl.`id_lang` AND osl.`id_order_state` = o.`current_state`');
        $query->leftjoin('customer', 'c', 'o.`id_customer`= c.`id_customer`');
        $query->leftjoin('pos_exchange', 'pe', 'pe.`id_order_old`= o.`id_order`');
        $query->where('o.`id_order` =  ' . (int) $id_order);
        $query->limit((int) $limit, (max((int) $page, 1) - 1) * (int) $limit);
        return Db::getInstance()->getRow($query);
    }

    /**
     *
     * @param array $product_returns
     * @param PosOrder $order
     * @return type
     */
    public static function cloneOrder($product_returns, PosOrder $order)
    {
        $context = Context::getContext();
        $new_order = clone($order);
        $order_details = self::reCalculateOrderDetails($product_returns, $order->getOrderDetails());
        $total_price_excl = self::getTotalProducts($order_details);
        $total_price_incl = self::getTotalProducts($order_details, true);
        if ($total_price_excl > 0 && $total_price_incl > 0) {
            $default_payment = PosPayment::getDefaultPayment();
            $number_payment = (int) $order->countOrderPayment();
            $order->cancelOrderPayments();
            unset($new_order->id);
            $new_order->total_products = $total_price_excl;
            $new_order->total_products_wt = $total_price_incl;
            $new_order->total_paid_real = 0;
            $new_order->total_paid = $new_order->total_paid_tax_incl = $total_price_incl + $order->total_shipping_tax_incl - $order->total_discounts_tax_incl;
            $new_order->total_paid_tax_excl = $total_price_excl + $order->total_shipping_tax_excl - $order->total_discounts_tax_excl;
            $new_order->payment = ($number_payment === 1) ? $order->payment : $default_payment->payment_name;
            $new_order->invoice_number = 0;
            $new_order->delivery_number = 0;
            $new_order->date_add = $order->date_add;
            $new_order->date_upd = $order->date_upd;
            if ($new_order->add(false)) {
                // create new cart
                $cart = clone(new PosCart($new_order->id_cart));
                $cart->id = 0;
                $cart->save();
                // add order detail
                foreach ($order_details as $order_detail) {
                    if (Validate::isLoadedObject($order_detail)) {
                        $shop = new Shop($order_detail->id_shop);
                        $cart->updateQtyPos($order_detail->product_quantity, $order_detail->product_id, $shop, $order_detail->product_attribute_id);
                        $id_tax = $order_detail->getIdTax();
                        unset($order_detail->id);
                        $order_detail->id_order = $new_order->id;
                        if ($order_detail->add() && $id_tax) {
                            $unit_amount = $order_detail->unit_price_tax_incl - $order_detail->unit_price_tax_excl;
                            $total_amount = $order_detail->product_quantity * ($order_detail->unit_price_tax_incl - $order_detail->unit_price_tax_excl - $order_detail->ecotax * $order_detail->ecotax_tax_rate / 100);
                            $order_detail->addOrderDetailTax($id_tax, $unit_amount, $total_amount);
                        }
                        // clone customization if exist
                        $customization_collection = PosCustomization::getCustomizationCollection($order->id_cart);
                        foreach ($customization_collection as &$customization) {
                            if (Validate::isLoadedObject($customization)) {
                                $customization_datas = PosCustomizationData::getCustomizationDataById($customization->id);
                                $customization->id = 0;
                                $customization->id_cart = $cart->id;
                                if ($customization->add()) {
                                    foreach ($customization_datas as $customization_data) {
                                        PosCustomizationData::add($customization_data['index'], $customization_data['type'], $customization_data['value'], $customization->id);
                                    }
                                }
                            }
                        }
                    }
                }
                // add order_history
                $history = new OrderHistory();
                $history->id_order = (int) $new_order->id;
                $history->id_order_state = (int) $new_order->current_state;
                $history->id_employee = (int) $context->employee->id;
                $history->add();
                // add order_carrier
                $order_carrier = new OrderCarrier();
                $order_carrier->id_order = (int) $new_order->id;
                $order_carrier->id_carrier = (int) $new_order->id_carrier;
                $order_carrier->weight = (float) $new_order->getTotalWeight();
                $order_carrier->shipping_cost_tax_excl = (float) $new_order->total_shipping_tax_excl;
                $order_carrier->shipping_cost_tax_incl = (float) $new_order->total_shipping_tax_incl;
                $order_carrier->add();
                // add order_cart_rule
                $order_cart_rule_collection = $order->getCartRuleCollection();
                foreach ($order_cart_rule_collection as $order_cart_rule) {
                    if (Validate::isLoadedObject($order_cart_rule)) {
                        unset($order_cart_rule->id);
                        $order_cart_rule->id_order = $new_order->id;
                        $order_cart_rule->id_order_invoice = 0;
                        $order_cart_rule->add();
                    }
                }
                // add order_payment
                $currency = new Currency($new_order->id_currency);
                $new_order->addOrderPayment($new_order->total_paid, $new_order->payment, null, $currency);
                // add order_invoice
                if ($order->invoice_number) {
                    $new_order->setInvoice(true);
                }
                if ($order->delivery_number) {
                    $new_order->setDelivery();
                    $new_order->setDeliverySlip();
                }
                // update new id_cart
                $new_order->id_cart = $cart->id;
                $new_order->update();
                self::execHookUpdateOrderState($new_order->current_state, $new_order);
            }
        }
        $default_return_order_state = (int)Configuration::get('POS_DEFAULT_RETURN_ORDER_STATE');
        self::execHookUpdateOrderState($default_return_order_state, $order);
        
        // add order history
        $history = new OrderHistory();
        $history->id_order = (int) $order->id;
        $history->id_order_state = $default_return_order_state;
        $history->id_employee = (int) $context->employee->id;
        $history->add();
        
        // cancel current order
        $order->current_state = $default_return_order_state;
        $order->valid = 0;
        $order->update();

        return $new_order;
    }

    /**
     *
     * @param int $id_order_state
     * @param PosOrder $order
     */
    protected static function execHookUpdateOrderState($id_order_state, $order)
    {
        $new_os = new OrderState($id_order_state, $order->id_lang);
        Hook::exec('actionOrderStatusUpdate', array('newOrderStatus' => $new_os, 'id_order' => (int) $order->id), null, false, true, false, $order->id_shop);
    }

    /**
     *
     * @param array $products
     * <pre>
     * array(
     *  int => array(
     *      quantity => int,
     *      id_product => int,
     *      id_product_attribute => int,
     *      id_order_detail => int
     *  )
     * )
     * @param array $order_details
     * <pre>
     * array(
     *  int => PosOrderDetail,
     * ...
     * )
     * @return array
     * * <pre>
     * array (
     *  int => PosOrderDetail
     * ...
     *
     * )
     */
    public static function reCalculateOrderDetails(array $products, array $order_details)
    {
        if (!empty($products) && !empty($order_details)) {
            foreach ($order_details as $index => &$order_detail) {
                if (in_array($order_detail->id, array_keys($products))) {
                    if ((int) $order_detail->product_quantity === (int) $products[$order_detail->id]['quantity']) {
                        unset($order_details[$index]);
                    } else {
                        $order_detail->product_quantity -= $products[$order_detail->id]['quantity'];
                        $order_detail->product_quantity_in_stock -= $products[$order_detail->id]['quantity'];
                        $order_detail->total_price_tax_incl -= $order_detail->unit_price_tax_incl * $products[$order_detail->id]['quantity'];
                        $order_detail->total_price_tax_excl -= $order_detail->unit_price_tax_excl * $products[$order_detail->id]['quantity'];
                        $order_detail->id_order_invoice = 0;
                    }
                }
            }
        }
        return $order_details;
    }

    /**
     *
     * @param array $order_details
     * <pre>
     * array(
     *  int => PosOrderDetail,
     * ...
     * )
     * @param boolean $use_tax
     * @return float
     */
    public static function getTotalProducts(array $order_details, $use_tax = false)
    {
        $total_product = 0;
        if (!empty($order_details)) {
            foreach ($order_details as $order_detail) {
                $total_product += $use_tax ? $order_detail->total_price_tax_incl : $order_detail->total_price_tax_excl;
            }
        }
        return $total_product;
    }

    /**
     *
     * @return int
     */
    public function countOrderPayment()
    {
        $sql = new DbQuery();
        $sql->select('COUNT(DISTINCT `payment_method`)');
        $sql->from('order_payment');
        $sql->where('`order_reference` = "' . pSQL($this->reference) . '"');
        return Db::getInstance()->getValue($sql);
    }

    /**
     *
     * @return boolean
     */
    public function cancelOrderPayments()
    {
        $success = array();
        $cancel_order_payments = $this->getOrderPaymentCollection();
        foreach ($cancel_order_payments as $order_payment) {
            $order_payment->order_reference = PosConstants::POS_CANCEL_ORDER;
            $success[] = $order_payment->update();
        }
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @return PrestaShopCollection of OrderCartRule
     */
    public function getCartRuleCollection()
    {
        $order_cart_rules = new PrestaShopCollection('OrderCartRule');
        $order_cart_rules->where('id_order', '=', (int) $this->id);
        return $order_cart_rules;
    }

    /**
     *
     * @param array $products_returns
     * <pre>
     * array(
     *  int => array(
     *      quantity => int,
     *      id_product => int,
     *      id_product_attribute => int,
     *      id_order_detail => int
     *  )
     * )
     * @return array
     * <pre>
     * array (
     *  int => array (
     *       id_order_detail => int
     *       id_order => int
     *       id_order_invoice => int
     *       id_warehouse => int
     *       id_shop => int
     *       product_id => int
     *       product_attribute_id => int
     *       product_name => string
     *       product_quantity => int
     *       product_quantity_in_stock => int
     *       product_quantity_refunded => int
     *       product_quantity_return => int
     *       product_quantity_reinjected => int
     *       product_price => float
     *       reduction_percent =>float
     *       reduction_amount =>float
     *       reduction_amount_tax_incl => float
     *       reduction_amount_tax_excl => float
     *       group_reduction => float
     *       product_quantity_discount => float
     *       product_ean13 => string
     *       product_upc => string
     *       product_reference => string
     *       product_supplier_reference => string
     *       product_weight => float
     *       id_tax_rules_group => int
     *       tax_computation_method => int
     *       tax_name => string
     *       tax_rate => float
     *       ecotax => float
     *       ecotax_tax_rate => float
     *       discount_quantity_applied => int
     *       download_hash => int
     *       download_nb => int
     *       download_deadline => date
     *       total_price_tax_incl => float
     *       total_price_tax_excl => float
     *       unit_price_tax_incl => float
     *       unit_price_tax_excl => float
     *       total_shipping_price_tax_incl => float
     *       total_shipping_price_tax_excl => float
     *       purchase_supplier_price => float
     *       original_product_price => float
     *       original_wholesale_price => float
     *       condition => string
     *       show_price => int
     *       product_price_wt => float
     *       product_price_wt_but_ecotax => float
     *       total_wt =>float
     *       total_price => float
     *       id_address_delivery => int
     *       name => string
     *       combination =>  string
     *       manufacturer_name => string
     *       product_price_tax_excl => float
     *       product_price_tax_incl => float
     *       price_without_specific_price => float
     *   )
     * )
     */
    public function getProductsReturn(array $products_returns)
    {
        $products = $this->getProducts();
        foreach ($products as $index => &$product) {
            if (in_array($product['id_order_detail'], array_keys($products_returns))) {
                $product['product_quantity'] = $products_returns[$product['id_order_detail']]['quantity'];
                $product['total_price_tax_incl'] = $product['unit_price_tax_incl'] * $products_returns[$product['id_order_detail']]['quantity'];
                $product['total_price_tax_excl'] = $product['unit_price_tax_excl'] * $products_returns[$product['id_order_detail']]['quantity'];
            } else {
                unset($products[$index]);
            }
        }
        return $products;
    }

    /**
     *
     * @return array
     * <pre />
     * array (
     *  int => PosOrderDetail
     * ...
     *
     * )
     */
    public function getOrderDetails()
    {
        $sql = new DbQuery();
        $sql->select('od.*');
        $sql->from('order_detail', 'od');
        $sql->innerJoin('orders', 'o', 'o.`id_order` = od.`id_order`');
        $sql->where('od.`id_order` = ' . (int) $this->id);
        $sql->where('o.`valid` = 1');
        $results = Db::getInstance()->executeS($sql);
        return self::hydrateCollection('PosOrderDetail', $results, (int) $this->id_lang);
    }

    /**
     * override remove setting PS_ORDER_RETURN
     * Does NOT delete a product but "cancel" it (which means return/refund/delete it depending of the case)
     *
     * @param $order
     * @param OrderDetail $order_detail
     * @param int $quantity
     * @return bool
     * @throws PrestaShopException
     */
    public function deleteProduct($order, $order_detail, $quantity)
    {
        if (!(int) $this->getCurrentState() || !validate::isLoadedObject($order_detail)) {
            return false;
        }
        if ($this->hasBeenDelivered()) {
            $order_detail->product_quantity_return += (int) $quantity;
            return $order_detail->update();
        } elseif ($this->hasBeenPaid()) {
            $order_detail->product_quantity_refunded += (int) $quantity;
            return $order_detail->update();
        }
        return $this->_deleteProduct($order_detail, (int) $quantity);
    }

    /**
     * @param string $order_reference
     *
     * @return array
     *               <pre/>
     *               Array(
     *               [0] => PosOrderInvoice,
     *               [1] => PosOrderInvoice
     *               )
     */
    public static function getInvoicesCollectionByReference($order_reference)
    {
        $order_invoices = Db::getInstance()->executeS('
            SELECT oi.*
            FROM `' . _DB_PREFIX_ . 'order_invoice` oi
            LEFT JOIN `' . _DB_PREFIX_ . 'orders` o ON (o.`id_order` = oi.`id_order`)
            WHERE o.`reference` = \'' . pSQL($order_reference) . '\'
            AND oi.`number` > 0
            ORDER BY oi.`date_add` ASC');

        return self::hydrateCollection('PosOrderInvoice', $order_invoices);
    }

    /**
     * @param string $order_reference
     *
     * @return float
     */
    public static function getAmountDueByReference($order_reference)
    {
        $sql = new DbQuery();
        $sql->select('SUM((o.`total_paid_tax_incl` - o.`total_paid_real`)) AS `amount_due`');
        $sql->from('orders', 'o');
        $sql->where('o.`reference` = "' . pSQL($order_reference) . '"');
        $sql->groupBy('reference');

        return (float) Db::getInstance()->getValue($sql);
    }

    /**
     * @param array $products
     *
     * @return array
     *               array(<pre>
     *               'products' => array(),@see PosOrder::getProductsByReference
     *               'gift_products' => array(),@see PosOrder::getProductsByReference
     *               'gift_total_order_tax_excl' => float,
     *               'gift_total_order_tax_incl' => float,
     *               )</pre>
     */
    public function formatProducts(array $products)
    {
        $gift_products = array();
        $gift_total_order_tax_excl = 0;
        $gift_total_order_tax_incl = 0;
        $cart_rules = $this->getCartRules();
        if (!empty($cart_rules)) {
            foreach ($cart_rules as $cart_rule) {
                if ($cart_rule['gift_product']) {
                    foreach ($products as $key => &$product) {
                        if (empty($product['gift']) && $cart_rule['gift_product'] == $product['product_id'] && $cart_rule['gift_product_attribute'] == $product['product_attribute_id']) {
                            $gift_total_order_tax_excl += $product['unit_price_tax_excl'];
                            $gift_total_order_tax_incl += $product['unit_price_tax_incl'];
                            $product['total_price_tax_incl'] = $product['total_price_tax_incl'] - $product['unit_price_tax_incl'];
                            $product['total_price_tax_excl'] = $product['total_price_tax_excl'] - $product['unit_price_tax_excl'];
                            --$product['product_quantity'];
                            if (!$product['product_quantity']) {
                                unset($products[$key]);
                            }
                            $gift_product = $product;
                            $gift_product['product_quantity'] = 1;
                            $gift_product['gift'] = true;
                            $gift_products[] = $gift_product;
                            break;
                        }
                    }
                }
            }
        }

        return array(
            'products' => $products,
            'gift_products' => $gift_products,
            'gift_total_order_tax_excl' => $gift_total_order_tax_excl,
            'gift_total_order_tax_incl' => $gift_total_order_tax_incl,
        );
    }

    /**
     * Check if there is any disccount applied to this order or not.
     *
     * @return bool
     */
    public function isDiscount()
    {
        $cache_id = __CLASS__ . __FUNCTION__ . $this->id;
        if (!Cache::isStored($cache_id)) {
            $is_discount = false;
            $products = $this->getProductsDetail();
            foreach ($products as $product) {
                if ($product['reduction_amount'] > 0 || $product['reduction_percent'] > 0) {
                    $is_discount = true;
                    break;
                }
            }
            Cache::store($cache_id, $is_discount);
        }

        return Cache::retrieve($cache_id);
    }

    /**
     *
     * override
     *  1. case useOneAfterAnotherTaxComputationMethod, t.`name` does not exit.
     *  2. fix wrong field `tax_amount`
     *
     * @since 1.5.0.1
     * @return array
     *  <pre>
     *  array(
     *      string => array(
     *          'total_amount' => float,
     *          'name' => string,
     *          'total_price_tax_excl' => float
     *      )
     *  ...
     * )
     */
    public function getProductTaxesBreakdown()
    {
        $tmp_tax_infos = array();
        if ($this->useOneAfterAnotherTaxComputationMethod()) {
            // sum by taxes
            $taxes_by_tax = Db::getInstance()->executeS('
                SELECT odt.`id_order_detail`, t.`rate`, SUM(`total_amount`) AS `total_amount`
                FROM `' . _DB_PREFIX_ . 'order_detail_tax` odt
                LEFT JOIN `' . _DB_PREFIX_ . 'tax` t ON (t.`id_tax` = odt.`id_tax`)
                LEFT JOIN `' . _DB_PREFIX_ . 'order_detail` od ON (od.`id_order_detail` = odt.`id_order_detail`)
                WHERE od.`id_order` = ' . (int) $this->id . '
                GROUP BY odt.`id_tax`
			');
            // format response
            foreach ($taxes_by_tax as $tax_infos) {
                $tmp_tax_infos[$tax_infos['rate']]['total_amount'] = $tax_infos['total_amount'];
                $tmp_tax_infos[$tax_infos['rate']]['name'] = $tax_infos['rate'];
                $tmp_tax_infos[$tax_infos['rate']]['total_price_tax_excl'] = 0;
            }
        } else {
            // sum by order details in order to retrieve real taxes rate
            $taxes_infos = Db::getInstance()->executeS('
                SELECT odt.`id_order_detail`, t.`rate` AS `name`, SUM(od.`total_price_tax_excl`) AS total_price_tax_excl, SUM(t.`rate`) AS rate, SUM(`total_amount`) AS `total_amount`
                FROM `' . _DB_PREFIX_ . 'order_detail_tax` odt
                LEFT JOIN `' . _DB_PREFIX_ . 'tax` t ON (t.`id_tax` = odt.`id_tax`)
                LEFT JOIN `' . _DB_PREFIX_ . 'order_detail` od ON (od.`id_order_detail` = odt.`id_order_detail`)
                WHERE od.`id_order` = ' . (int) $this->id . '
                GROUP BY odt.`id_order_detail`
			');

            // sum by taxes
            foreach ($taxes_infos as $tax_infos) {
                if (!isset($tmp_tax_infos[$tax_infos['rate']])) {
                    $tmp_tax_infos[$tax_infos['rate']] = array('total_amount' => 0,
                        'name' => 0,
                        'total_price_tax_excl' => 0);
                }

                $tmp_tax_infos[$tax_infos['rate']]['total_amount'] += $tax_infos['total_amount'];
                $tmp_tax_infos[$tax_infos['rate']]['name'] = $tax_infos['name'];
                $tmp_tax_infos[$tax_infos['rate']]['total_price_tax_excl'] += $tax_infos['total_price_tax_excl'];
            }
        }
        return $tmp_tax_infos;
    }

    /**
     * Override: calculate ecotax_tax_excl + ecotax_tax_incl
     * Returns the ecotax taxes breakdown
     *
     * @since 1.5.0.1
     * @return array
     *  <pre>
     *  array(
     *      string => array(
     *          'ecotax_tax_excl' => float,
     *          'ecotax_tax_incl' => float
     *      )
     *  ...
     * )
     */
    public function getEcoTaxTaxesBreakdown()
    {
        $sql = new DbQuery();
        $sql->select('`ecotax_tax_rate` as `rate`');
        $sql->select('SUM(`ecotax` * `product_quantity`) as `ecotax_tax_excl`');
        $sql->select('SUM(`ecotax` * `product_quantity`) as `ecotax_tax_incl`');
        $sql->from('order_detail');
        $sql->where('`id_order` = "' . (int) $this->id . '"');
        $sql->groupBy('`ecotax_tax_rate`');
        $result = Db::getInstance()->executeS($sql);
        $taxes = array();
        foreach ($result as $row) {
            if ($row['ecotax_tax_excl'] > 0) {
                $row['ecotax_tax_incl'] = Tools::ps_round($row['ecotax_tax_excl'] + ($row['ecotax_tax_excl'] * $row['rate'] / 100), _PS_PRICE_DISPLAY_PRECISION_);
                $row['ecotax_tax_excl'] = Tools::ps_round($row['ecotax_tax_excl'], _PS_PRICE_DISPLAY_PRECISION_);
                $taxes[] = $row;
            }
        }
        return $taxes;
    }

    /**
     * @param PosPaymentModule $module
     * @param string $date_from
     * @param string $date_to
     *
     * @return array
     * <pre>
     * array (
     *  int => string
     * ...
     * )
     */
    public static function getReferencesByModule(PosPaymentModule $module, $date_from, $date_to)
    {
        $references = array();
        $cache_key = 'PosOrder::getReferencesByModule_' . $module->name . '-' . $date_from . '-' . $date_to;
        if (!Cache::isStored($cache_key)) {
            if ($module instanceof PosPaymentModule) {
                $sql = new DbQuery();
                $sql->select('DISTINCT reference');
                $sql->from('orders', 'o');
                $sql->where('o.`module` = "' . pSQL($module->name) . '"');
                $sql->where('o.`valid` = 1' . Shop::addSqlRestriction(false, 'o'));
                $sql->where('o.`date_add` BETWEEN "' . pSQL($date_from) . ' 00:00:00" AND "' . pSQL($date_to) . ' 23:59:59"');
                $results = Db::getInstance()->executeS($sql);
                if (!empty($results)) {
                    foreach ($results as $result) {
                        $references[] = $result['reference'];
                    }
                    Cache::store($cache_key, $references);
                }
            }
        } else {
            $references = Cache::retrieve($cache_key);
        }
        return $references;
    }

    /**
     *
     * @param PosPaymentModule $module
     * @param string $date_from
     * @param string $date_to
     * @param string $granularity
     * @return array
     * <pre />
     * array(
     *  int => float// strtotime => float
     * ...
     * )
     */
    public static function getSales(PosPaymentModule $module, $date_from, $date_to, $granularity)
    {
        $sales = array();
        $references = self::getReferencesByModule($module, $date_from, $date_to);
        if (!empty($references)) {
            $sql = new DbQuery();
            $sql->select('LEFT(o.`date_add`, 10) AS `date`');
            $sql->select('SUM(total_paid_tax_excl / o.conversion_rate) as sales');
            $sql->from('orders', 'o');
            $sql->where('o.`reference` IN ("' . implode('","', $references) . '")');
            switch ($granularity) {
                case 'day':
                    $sql->groupBy('LEFT(o.`date_add`, 10)');
                    break;
                case 'week':
                    $sql->groupBy('WEEK(o.`date_add`, 1)');
                    break;
                default:
                    $sql->groupBy('MONTH(o.`date_add`)');
                    break;
            }
            $results = Db::getInstance()->executeS($sql);
            foreach ($results as $result) {
                switch ($granularity) {
                    case 'day':
                        $sales[strtotime($result['date'])] = (float) $result['sales'];
                        break;
                    case 'week':
                        $date = strtotime(date('Y-m-d', strtotime('monday this week', strtotime($result['date']))));
                        if (!isset($sales[$date])) {
                            $sales[$date] = 0;
                        }
                        $sales[$date] += (float) $result['sales'];
                        break;
                    default:
                        $date = strtotime(date('Y-m', strtotime($result['date'])));
                        if (!isset($sales[$date])) {
                            $sales[$date] = 0;
                        }
                        $sales[$date] = (float) $result['sales'];
                        break;
                }
            }
        }
        return $sales;
    }

    /**
     *
     * @param PosPaymentModule $module
     * @param string $date_from
     * @param string $date_to
     * @param string $granularity
     * @return array
     * <pre />
     * array(
     *  int => float// strtotime => float
     * ...
     * )
     */
    public static function getOrders(PosPaymentModule $module, $date_from, $date_to, $granularity)
    {
        $orders = array();
        $references = self::getReferencesByModule($module, $date_from, $date_to);
        if (!empty($references)) {
            $sql = new DbQuery();
            $sql->select('LEFT(o.`date_add`, 10) AS `date`');
            $sql->select('COUNT(DISTINCT o.`reference`) AS `orders`');
            $sql->from('orders', 'o');
            $sql->where('o.`reference` IN ("' . implode('","', $references) . '")');
            switch ($granularity) {
                case 'day':
                    $sql->groupBy('LEFT(o.`date_add`, 10)');
                    break;
                case 'week':
                    $sql->groupBy('WEEK(o.`date_add`, 1)');
                    break;
                default:
                    $sql->groupBy('MONTH(o.`date_add`)');
                    break;
            }
            $results = Db::getInstance()->executeS($sql);
            foreach ($results as $result) {
                switch ($granularity) {
                    case 'day':
                        $orders[strtotime($result['date'])] = (float) $result['orders'];
                        break;
                    case 'week':
                        $date = strtotime(date('Y-m-d', strtotime('monday this week', strtotime($result['date']))));
                        if (!isset($orders[$date])) {
                            $orders[$date] = 0;
                        }
                        $orders[$date] += (float) $result['orders'];
                        break;
                    default:
                        $date = strtotime(date('Y-m', strtotime($result['date'])));
                        if (!isset($orders[$date])) {
                            $orders[$date] = 0;
                        }
                        $orders[$date] = (float) $result['orders'];
                        break;
                }
            }
        }
        return $orders;
    }

    /**
     *
     * @param PosPaymentModule $module
     * @param string $date_from
     * @param string $date_to
     * @param string $granularity
     * @return array
     * <pre />
     * array(
     *  int => float// strtotime => float
     * ...
     * )
     */
    public static function getPurchases(PosPaymentModule $module, $date_from, $date_to, $granularity)
    {
        $purchases = array();
        $references = self::getReferencesByModule($module, $date_from, $date_to);
        if (!empty($references)) {
            $sql = new DbQuery();
            $sql->select('LEFT(o.`date_add`, 10) AS `date`');
            $sql->select('SUM(od.`product_quantity` * IF(
                            od.`purchase_supplier_price` > 0,
                            od.`purchase_supplier_price` / `conversion_rate`,
                            od.`original_product_price` * ' . (int) Configuration::get('CONF_AVERAGE_PRODUCT_MARGIN') . ' / 100
                        )) as total_purchase_price');
            $sql->from('orders', 'o');
            $sql->leftJoin('order_detail', 'od', 'o.id_order = od.id_order');
            $sql->leftJoin('order_state', 'os', 'o.current_state = os.id_order_state');
            $sql->where('o.`reference` IN ("' . implode('","', $references) . '")');
            switch ($granularity) {
                case 'day':
                    $sql->groupBy('LEFT(o.`date_add`, 10)');
                    break;
                case 'week':
                    $sql->groupBy('WEEK(o.`date_add`, 1)');
                    break;
                default:
                    $sql->groupBy('MONTH(o.`date_add`)');
                    break;
            }
            $results = Db::getInstance()->executeS($sql);
            foreach ($results as $result) {
                switch ($granularity) {
                    case 'day':
                        $purchases[strtotime($result['date'])] = (float) $result['total_purchase_price'];
                        break;
                    case 'week':
                        $date = strtotime(date('Y-m-d', strtotime('monday this week', strtotime($result['date']))));
                        if (!isset($purchases[$date])) {
                            $purchases[$date] = 0;
                        }
                        $purchases[$date] += (float) $result['total_purchase_price'];
                        break;
                    default:
                        $date = strtotime(date('Y-m', strtotime($result['date'])));
                        if (!isset($purchases[$date])) {
                            $purchases[$date] = 0;
                        }
                        $purchases[$date] = (float) $result['total_purchase_price'];
                        break;
                }
            }
        }
        return $purchases;
    }

    /**
     *
     * @param PosPaymentModule $module
     * @param string $date_from
     * @param string $date_to
     * @param string $granularity
     * @return array
     * <pre />
     * array(
     *  int => float// strtotime => float
     * ...
     * )
     */
    public static function getExpenses(PosPaymentModule $module, $date_from, $date_to, $granularity)
    {
        $expenses = array();
        $references = self::getReferencesByModule($module, $date_from, $date_to);
        if (!empty($references)) {
            $sql = new DbQuery();
            $sql->select('LEFT(o.`date_add`, 10) AS `date`');
            $sql->select('SUM(o.`total_paid_tax_incl` / o.`conversion_rate`) AS `total_paid_tax_incl`');
            $sql->select('SUM(o.`total_shipping_tax_excl` / o.`conversion_rate`) AS `total_shipping_tax_excl`');
            $sql->select('o.`module`');
            $sql->select('a.`id_country`');
            $sql->select('o.`id_currency`');
            $sql->select('a.`id_country`');
            $sql->select('c.`id_reference` AS `carrier_reference`');
            $sql->from('orders', 'o');
            $sql->leftJoin('address', 'a', 'o.`id_address_delivery` = a.`id_address`');
            $sql->leftJoin('carrier', 'c', 'o.`id_carrier` = c.`id_carrier`');
            $sql->where('o.`reference` IN ("' . implode('","', $references) . '")');
            $sql->where('o.`valid` = 1');
            switch ($granularity) {
                case 'day':
                    $sql->groupBy('LEFT(o.`date_add`, 10)');
                    break;
                case 'week':
                    $sql->groupBy('WEEK(o.`date_add`, 1)');
                    break;
                default:
                    $sql->groupBy('MONTH(o.`date_add`)');
                    break;
            }
            $results = Db::getInstance()->executeS($sql);
            foreach ($results as $result) {
                // Add flat fees for this order
                $flat_fees = Configuration::get('CONF_ORDER_FIXED') + (
                        $result['id_currency'] == Configuration::get('PS_CURRENCY_DEFAULT') ? Configuration::get('CONF_' . Tools::strtoupper($result['module']) . '_FIXED') : Configuration::get('CONF_' . Tools::strtoupper($result['module']) . '_FIXED_FOREIGN')
                        );

                // Add variable fees for this order
                $var_fees = $result['total_paid_tax_incl'] * (
                        $result['id_currency'] == Configuration::get('PS_CURRENCY_DEFAULT') ? Configuration::get('CONF_' . Tools::strtoupper($result['module']) . '_VAR') : Configuration::get('CONF_' . Tools::strtoupper($result['module']) . '_VAR_FOREIGN')
                        ) / 100;

                // Add shipping fees for this order
                $shipping_fees = $result['total_shipping_tax_excl'] * (
                        $result['id_country'] == Configuration::get('PS_COUNTRY_DEFAULT') ? Configuration::get('CONF_' . Tools::strtoupper($result['carrier_reference']) . '_SHIP') : Configuration::get('CONF_' . Tools::strtoupper($result['carrier_reference']) . '_SHIP_OVERSEAS')
                        ) / 100;

                switch ($granularity) {
                    case 'day':
                        $date = strtotime($result['date']);
                        if (!isset($expenses[$date])) {
                            $expenses[$date] = 0;
                        }
                        $expenses[strtotime($result['date'])] += $flat_fees + $var_fees + $shipping_fees;
                        break;
                    case 'week':
                        $date = strtotime(date('Y-m-d', strtotime('monday this week', strtotime($result['date']))));
                        if (!isset($expenses[$date])) {
                            $expenses[$date] = 0;
                        }
                        $expenses[$date] += $flat_fees + $var_fees + $shipping_fees;
                        break;
                    default:
                        $date = strtotime(date('Y-m', strtotime($result['date'])));
                        if (!isset($expenses[$date])) {
                            $expenses[$date] = 0;
                        }
                        $expenses[$date] += $flat_fees + $var_fees + $shipping_fees;
                        break;
                }
            }
        }
        return $expenses;
    }

    /**
     *
     * @param PosPaymentModule $module
     * @param string $date_from
     * @param string $date_to
     * @return array
     * <pre />
     * array(
     *  sales => array(
     *      int => float// strtotime => float
     *  ...
     *  )
     *  orders => array(
     *      int => float// strtotime => float
     *  ...
     *  )
     *  average_cart => array(
     *      int => float// strtotime => float
     *  ...
     *  )
     *  net_profits => array(
     *      int => float// strtotime => float
     *  ...
     *  )
     * )
     */
    public static function getSummaryDetails(PosPaymentModule $module, $date_from, $date_to, $granularity)
    {
        $summary_details = array(
            'sales' => array(),
            'orders' => array(),
            'average_cart' => array(),
            'net_profits' => array()
        );
        $sales = self::getSales($module, $date_from, $date_to, $granularity);
        $orders = self::getOrders($module, $date_from, $date_to, $granularity);
        $purchases = self::getPurchases($module, $date_from, $date_to, $granularity);
        $expenses = self::getExpenses($module, $date_from, $date_to, $granularity);
        $from = strtotime($date_from . ' 00:00:00');
        $to = min(time(), strtotime($date_to . ' 23:59:59'));
        switch ($granularity) {
            case 'day':
                for ($date = $from; $date <= $to; $date = strtotime('+1 day', $date)) {
                    $summary_details['sales'][$date] = isset($sales[$date]) ? $sales[$date] : 0;
                    $summary_details['orders'][$date] = isset($orders[$date]) ? $orders[$date] : 0;
                    $summary_details['average_cart'][$date] = isset($orders[$date]) ? $sales[$date] / $orders[$date] : 0;
                    $summary_details['net_profits'][$date] = 0;
                    $summary_details['net_profits'][$date] += isset($sales[$date]) ? $sales[$date] : 0;
                    $summary_details['net_profits'][$date] -= isset($purchases[$date]) ? $purchases[$date] : 0;
                    $summary_details['net_profits'][$date] -= isset($expenses[$date]) ? $expenses[$date] : 0;
                }
                break;
            case 'week':
                for ($date = $from; $date <= $to; $date = strtotime('+1 week', $date)) {
                    $summary_details['sales'][$date] = isset($sales[$date]) ? $sales[$date] : 0;
                    $summary_details['orders'][$date] = isset($orders[$date]) ? $orders[$date] : 0;
                    $summary_details['average_cart'][$date] = isset($orders[$date]) ? $sales[$date] / $orders[$date] : 0;
                    $summary_details['net_profits'][$date] = 0;
                    $summary_details['net_profits'][$date] += isset($sales[$date]) ? $sales[$date] : 0;
                    $summary_details['net_profits'][$date] -= isset($purchases[$date]) ? $purchases[$date] : 0;
                    $summary_details['net_profits'][$date] -= isset($expenses[$date]) ? $expenses[$date] : 0;
                }
                break;
            default:
                for ($date = $from; $date <= $to; $date = strtotime('+1 month', $date)) {
                    $summary_details['sales'][$date] = isset($sales[$date]) ? $sales[$date] : 0;
                    $summary_details['orders'][$date] = isset($orders[$date]) ? $orders[$date] : 0;
                    $summary_details['average_cart'][$date] = isset($orders[$date]) ? $sales[$date] / $orders[$date] : 0;
                    $summary_details['net_profits'][$date] = 0;
                    $summary_details['net_profits'][$date] += isset($sales[$date]) ? $sales[$date] : 0;
                    $summary_details['net_profits'][$date] -= isset($purchases[$date]) ? $purchases[$date] : 0;
                    $summary_details['net_profits'][$date] -= isset($expenses[$date]) ? $expenses[$date] : 0;
                }
                break;
        }
        return $summary_details;
    }

    /**
     *
     * @param PosPaymentModule $module
     * @param string $summary_start_date
     * @param string $date_from
     * @param string $date_to
     * @return array
     * <pre />
     * array(
     *  int => array(
     *      product_id => int
     *      product_attribute_id => int
     *      name => string
     *      combination => string
     *      quantity => int
     *      sales => float
     *      discounts => float
     *      date => string
     *  )
     * ...
     * )
     */
    public static function getBestSellingProducts(PosPaymentModule $module, $summary_start_date, $date_from, $date_to, $granularity)
    {
        $best_seller_products = array();
        $references = self::getReferencesByModule($module, $date_from, $date_to);
        $sql = new DbQuery();
        $sql->select('od.`product_id`');
        $sql->select('od.`product_attribute_id`');
        $sql->select('od.`product_name`');
        $sql->select('SUM(od.`product_quantity`) as quantity');
        $sql->select('SUM(od.`product_price` - od.`unit_price_tax_excl`) as discounts');
        $sql->select('SUM(od.`total_price_tax_excl` / o.`conversion_rate`) as sales');
        $sql->select('LEFT(o.`date_add`, 10) AS `date`');
        $sql->from('orders', 'o');
        $sql->leftJoin('order_detail', 'od', 'o.`id_order` = od.`id_order`');
        $sql->where('o.`valid` = 1');
        $sql->where('o.`date_add` BETWEEN "' . pSQL($summary_start_date) . ' 00:00:00" AND "' . pSQL($date_to) . ' 23:59:59"');
        $sql->groupBy('od.`product_id`, od.`product_attribute_id`');
        $sql->limit(10);
        $best_seller_products = Db::getInstance()->executeS($sql);
        if (!empty($best_seller_products)) {
            foreach ($best_seller_products as &$product) {
                $combination = array();
                $product_name = array();
                if ((int) $product['product_attribute_id'] > 0) {
                    $names = explode('-', $product['product_name']);
                    if (count($names) >= 2) {
                        $combination[] = str_replace(" : ", ": ", array_pop($names));
                    }
                    foreach ($names as $key => $name) {
                        if (strpos($name, ':')) {
                            $combination[] = str_replace(" : ", ": ", $name);
                        } else {
                            $product_name[] = trim($name);
                        }
                    }
                    $product['name'] = implode('-', $product_name);
                    $product['combination'] = implode(' - ', $combination);
                } else {
                    $product['name'] = $product['product_name'];
                    $product['combination'] = $combination;
                }
                $product['trends'] = self::getProductTrends($product['product_id'], $product['product_attribute_id'], $references, $date_from, $date_to, $granularity);
                unset($product['product_name']);
            }
        }
        return $best_seller_products;
    }

    /**
     *
     * @param int $id_product
     * @param int $id_product_attribute
     * @param array $order_references
     * @param string $date_from
     * @param string $date_to
     * @param string $granularity
     * @return array
     * <pre />
     * array(
     *      string => float // strtotime => float
     * ...
     * )
     */
    protected static function getProductTrends($id_product, $id_product_attribute, $order_references, $date_from, $date_to, $granularity)
    {
        $product_trends = array();
        $sql = new DbQuery();
        $sql->select('SUM(od.`total_price_tax_excl` / o.`conversion_rate`) as sales');
        $sql->select('LEFT(o.`date_add`, 10) AS `date`');
        $sql->from('orders', 'o');
        $sql->leftJoin('order_detail', 'od', 'o.`id_order` = od.`id_order`');
        $sql->where('o.`reference` IN ("' . implode('","', $order_references) . '")');
        $sql->where('od.`product_id`=' . (int) $id_product);
        $sql->where('od.`product_attribute_id`=' . (int) $id_product_attribute);
        switch ($granularity) {
            case 'day':
                $sql->groupBy('LEFT(o.`date_add`, 10)');
                break;
            case 'week':
                $sql->groupBy('WEEK(o.`date_add`)');
                break;
            default:
                $sql->groupBy('MONTH(o.`date_add`)');
                break;
        }
        $results = Db::getInstance()->executeS($sql);
        if (!empty($results)) {
            foreach ($results as $result) {
                switch ($granularity) {
                    case 'day':
                        $date = strtotime($result['date']);
                        if (!isset($product_trends[$date])) {
                            $product_trends[$date] = 0;
                        }
                        $product_trends[$date] = (float) $result['sales'];
                        break;
                    case 'week':
                        $date = strtotime(date('Y-m-d', strtotime('monday this week', strtotime($result['date']))));
                        if (!isset($product_trends[$date])) {
                            $product_trends[$date] = 0;
                        }
                        $product_trends[$date] += (float) $result['sales'];
                        break;
                    default:
                        $date = strtotime(date('Y-m', strtotime($result['date'])));
                        if (!isset($product_trends[$date])) {
                            $product_trends[$date] = 0;
                        }
                        $product_trends[$date] = (float) $result['sales'];
                        break;
                }
            }
            $from = strtotime($date_from . ' 00:00:00');
            $to = min(time(), strtotime($date_to . ' 23:59:59'));
            switch ($granularity) {
                case 'day':
                    for ($date = $from; $date <= $to; $date = strtotime('+1 day', $date)) {
                        if (!isset($product_trends[$date])) {
                            $product_trends[$date] = 0;
                        }
                        $product_trends[$date] = isset($product_trends[$date]) ? $product_trends[$date] : 0;
                    }
                    break;
                case 'week':
                    for ($date = $from; $date <= $to; $date = strtotime('+1 week', $date)) {
                        if (!isset($product_trends[$date])) {
                            $product_trends[$date] = 0;
                        }
                        $product_trends[$date] = isset($product_trends[$date]) ? $product_trends[$date] : 0;
                    }
                    break;
                default:
                    for ($date = $from; $date <= $to; $date = strtotime('+1 month', $date)) {
                        if (!isset($product_trends[$date])) {
                            $product_trends[$date] = 0;
                        }
                        $product_trends[$date] = isset($product_trends[$date]) ? $product_trends[$date] : 0;
                    }
                    break;
            }
        }
        return $product_trends;
    }

    /**
     *
     * @param string $date_from
     * @param string $date_to
     * @return array
     * <pre >
     * array(
     *  int => array(
     *      employee_name => string
     *      sales => float
     *      orders => int
     *      quantity => int
     *      average_cart => float
     *  )
     * ...
     * )
     */
    public static function getTopCashiers(PosPaymentModule $module, $date_from, $date_to)
    {
        $cashiers = array();
        $references = self::getReferencesByModule($module, $date_from, $date_to);
        if (!empty($references)) {
            $sql = new DbQuery();
            $sql->select('COUNT( DISTINCT(o.`reference`)) as orders');
            $sql->select('CONCAT(e.`firstname`, " " , e.`lastname`) as `employee_name`');
            $sql->select('SUM(od.`total_price_tax_excl` / o.`conversion_rate`) as sales');
            $sql->select('SUM(od.`product_quantity`) as quantity');
            $sql->from('orders', 'o');
            $sql->innerJoin('pos_sales_commission', 'sc', 'sc.`order_reference` = o.`reference`');
            $sql->leftJoin('order_detail', 'od', 'o.`id_order` = od.`id_order`');
            $sql->leftJoin('employee', 'e', 'sc.`id_employee` = e.`id_employee`');
            $sql->where('sc.`order_reference` IN ("' . implode('","', $references) . '")');
            $sql->where('o.`valid` = 1');
            $sql->groupBy('e.`id_employee`');
            $cashiers = Db::getInstance()->executeS($sql);
            if (!empty($cashiers)) {
                foreach ($cashiers as &$cashier) {
                    $cashier['average_cart'] = $cashier['sales'] / $cashier['orders'];
                }
            }
        }
        return (array) $cashiers;
    }

    /**
     *
     * @param string $module_name
     * @param string $date_from
     * @param string $date_to
     * @return array
     * <pre>
     * array(
     *  int => array(
     *      id_order => int
     *      reference => string
     *      total => string
     *      total_for_sort => float
     *      paid => string
     *      paid_for_sort => float
     *      unpaid => string
     *      unpaid_for_sort => float
     *      customer => string
     *      shop_name => string
     *      status => string
     *      date => date time

     *  )
     * ...
     * )
     */
    public static function getList($module_name, $date_from, $date_to)
    {
        $orders = array_merge(self::getOrdersbyModule($module_name, $date_from, $date_to), self::getOrdersbyModule($module_name, $date_from, $date_to, true));
        if (!empty($orders)) {
            foreach ($orders as &$order) {
                $currency = PosCurrency::getCurrencyInstance($order['id_currency']);
                $unpaid = 0;
                $collection_orders = PosOrder::getByReference($order['reference']);
                $is_return_exchange = PosExchange::isReturnExchangeOrder($collection_orders);
                foreach ($collection_orders as $collection_order) {
                    $unpaid += $collection_order->total_paid - $collection_order->total_paid_real;
                }
                $unpaid = $is_return_exchange ? 0 : $unpaid;
                $order['total_for_sort'] = $order['total'];
                $order['paid'] = $order['total_paid_real'];
                $order['paid_for_sort'] = $order['total_paid_real'];
                $order['unpaid'] = $unpaid;
                $order['unpaid_for_sort'] = $unpaid;
                $order['currency'] = $currency;
                unset($order['id_currency']);
            }
        }
        return $orders;
    }
    
    /**
     *
     * @param string $module_name
     * @param string $date_from
     * @param string $date_to
     * @param boolean $return_order
     * @return array
     * <pre>
     * array(
     *  int => array(
     *      id_order => int
     *      reference => string
     *      total => string
     *      total_for_sort => float
     *      paid => string
     *      paid_for_sort => float
     *      unpaid => string
     *      unpaid_for_sort => float
     *      customer => string
     *      shop_name => string
     *      status => string
     *      date => date time

     *  )
     * ...
     * )
     */
    protected static function getOrdersbyModule($module_name, $date_from, $date_to, $return_order = false)
    {
        $sql = new DbQuery();
        $sql->select('o.`id_order`');
        $sql->select('o.`id_currency`');
        $sql->select('o.`current_state`');
        $sql->select('o.`reference`');
        $sql->select('SUM(o.`total_paid_tax_incl`) AS `total`');
        $sql->select('SUM(o.`total_products_wt`) AS `total_products_wt`');
        $sql->select('SUM(o.`total_shipping`) AS `total_shipping`');
        $sql->select('SUM(o.`total_paid_real`) AS `total_paid_real`');
        $sql->select('pc.`note`');
        $sql->select('IF(ocr.`free_shipping`, 0, SUM(ocr.`value`)) AS `total_discount`');
        $sql->select('CONCAT(c.`firstname`,\' \', c.`lastname`) AS `customer`');
        $sql->select('s.`name` AS `shop_name`');
        $sql->select('otl.`name` AS `status`');
        $sql->select('o.`date_add` AS `date`');
        $sql->from('orders', 'o');
        $sql->leftJoin('order_cart_rule', 'ocr', 'ocr.`id_order` = o.`id_order`');
        $sql->leftJoin('pos_cart', 'pc', 'pc.`id_cart` = o.`id_cart`');
        $sql->innerJoin('customer', 'c', 'c.`id_customer` = o.`id_customer`');
        $sql->innerJoin('shop', 's', 's.`id_shop` = o.`id_shop`');
        $sql->innerJoin('order_state_lang', 'otl', 'otl.`id_order_state` = o.`current_state` AND otl.`id_lang` = o.`id_lang`');
        $sql->where('o.`module` = \'' . pSQL($module_name) . '\'');
        $sql->where('o.id_shop IN('.implode(',', Shop::getContextListShopID()).')');
        if ($return_order) {
            $sql->where('o.`current_state` = ' . (int) Configuration::get('POS_DEFAULT_RETURN_ORDER_STATE'));
            $sql->groupBy('o.`id_order`');
        } else {
            $sql->where('o.`current_state` != ' . (int) Configuration::get('POS_DEFAULT_RETURN_ORDER_STATE'));
            $sql->groupBy('o.`reference`');
        }
        $sql->where('o.`date_add` >= "' . pSQL($date_from) . ' 00:00:00"');
        $sql->where('o.`date_add` <= "' . pSQL($date_to) . ' 23:59:59"');
        $sql->orderBy('o.`date_add` DESC');
        return Db::getInstance()->executeS($sql);
    }

    /**
     * Override add note for invoice
     * This method allows to fulfill the object order_invoice with sales figures
     * @param PosInvoice $order_invoice
     */
    protected function setInvoiceDetails($order_invoice)
    {
        if (!$order_invoice || !is_object($order_invoice)) {
            return;
        }
        $note = PosActiveCart::getNote($this->id_cart, $this->id_shop);
        $address = new Address((int) $this->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
        $carrier = new Carrier((int) $this->id_carrier);
        $tax_calculator = $carrier->getTaxCalculator($address);
        $order_invoice->total_discount_tax_excl = $this->total_discounts_tax_excl;
        $order_invoice->total_discount_tax_incl = $this->total_discounts_tax_incl;
        $order_invoice->total_paid_tax_excl = $this->total_paid_tax_excl;
        $order_invoice->total_paid_tax_incl = $this->total_paid_tax_incl;
        $order_invoice->total_products = $this->total_products;
        $order_invoice->total_products_wt = $this->total_products_wt;
        $order_invoice->total_shipping_tax_excl = $this->total_shipping_tax_excl;
        $order_invoice->total_shipping_tax_incl = $this->total_shipping_tax_incl;
        $order_invoice->shipping_tax_computation_method = $tax_calculator->computation_method;
        $order_invoice->total_wrapping_tax_excl = $this->total_wrapping_tax_excl;
        $order_invoice->note = $note['public'] ? $note['note'] : null;
        $order_invoice->save();

        if (Configuration::get('PS_ATCP_SHIPWRAP')) {
            $wrapping_tax_calculator = Adapter_ServiceLocator::get('AverageTaxOfProductsTaxCalculator')->setIdOrder($this->id);
        } else {
            $wrapping_tax_manager = TaxManagerFactory::getManager($address, (int) Configuration::get('PS_GIFT_WRAPPING_TAX_RULES_GROUP'));
            $wrapping_tax_calculator = $wrapping_tax_manager->getTaxCalculator();
        }

        $order_invoice->saveCarrierTaxCalculator($tax_calculator->getTaxesAmount($order_invoice->total_shipping_tax_excl, $order_invoice->total_shipping_tax_incl, _PS_PRICE_COMPUTE_PRECISION_, $this->round_mode));
        $order_invoice->saveWrappingTaxCalculator($wrapping_tax_calculator->getTaxesAmount($order_invoice->total_wrapping_tax_excl, $order_invoice->total_wrapping_tax_incl, _PS_PRICE_COMPUTE_PRECISION_, $this->round_mode));
    }

    /**
     * @param Module $module
     * @param int $expiring_days
     * @param string $version
     * @return int
     */
    public static function getNbFreeOrders(Module $module, $expiring_days, $version)
    {
        $query = new DbQuery();
        $query->select('COUNT(*) AS `total`');
        $query->from('orders');
        $query->where('module = "' . pSQL($module->name) . '"');
        if ($version == 'lite' || $expiring_days < -30) {
            $query->where('`date_add` >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)');
        } else if ($expiring_days < 0) {
            $query->where('`date_add` >= DATE_SUB(CURDATE(), INTERVAL ' . (abs($expiring_days) - 1) . ' DAY)');
        }
        return Db::getInstance()->getValue($query);
    }

    /**
     *
     * @return array
     * <pre>
     * array(
     *  int => array(
     *      id_tax_rules_group => int,
     *      total_amount => float,
     *      rate => float
     *
     *  )
     * )
     */
    public function getTaxes()
    {
        $sql = new DbQuery();
        $sql->select('od.`id_tax_rules_group` AS id_tax');
        $sql->select('SUM(odt.`total_amount`) AS total_amount');
        $sql->select('t.`rate` AS tax_rate');
        $sql->from('orders', 'o');
        $sql->innerJoin('order_detail', 'od', 'od.`id_order` = o.`id_order`');
        $sql->innerJoin('order_detail_tax', 'odt', 'odt.`id_order_detail` = od.`id_order_detail`');
        $sql->innerJoin('tax', 't', 't.`id_tax` = odt.`id_tax`');
        $sql->where('o.`reference` = \'' . pSQL($this->reference) . '\'');
        $sql->where('o.`valid` = 1');
        $sql->groupBy('od.`id_tax_rules_group`');
        return Db::getInstance()->executeS($sql);
    }
    
    /**
     * @param string $module_name
     * @return int
     */
    public static function getNbOrdersByModule($module_name)
    {
        $date_end = date('Y-m-d', strtotime('today'));
        $date_start = date('Y-m-d', strtotime('today - 30 days'));
        $sql = new DbQuery();
        $sql->select('count(*)');
        $sql->from('orders', 'o');
        $sql->where('o.`date_add` BETWEEN "' . pSQL($date_start) . ' 00:00:00" AND "' . pSQL($date_end) . ' 23:59:59"');
        $sql->where('o.`module` = \''.pSQL($module_name).'\'');
        return Db::getInstance()->getValue($sql);
    }
}
