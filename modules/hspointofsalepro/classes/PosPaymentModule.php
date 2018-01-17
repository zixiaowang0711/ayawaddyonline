<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Handle payment through POS.
 */
class PosPaymentModule extends PaymentModule
{
    /**
     * List all orders of current cart.
     *
     * @var array
     *            array
     *            (<pre>
     *            [0] => Order,
     *            [1] => Order,
     *            ...
     *            )</pre>
     */
    public $order_list = array();

    /**
     * Assign origin order reference to new order in case exchange
     * @var string
     */
    public $origin_order_reference = null;

    /**
     * Validate an order in database
     * Function called from a payment module.
     *
     * @param int    $id_cart
     * @param int    $id_order_state
     * @param float  $amount_paid
     * @param string $message
     * @param array  $extra_vars
     * @param int    $currency_special
     * @param bool   $dont_touch_amount
     * @param string $secure_key
     * @param Shop   $shop
     *
     * @return bool
     *
     * @throws PrestaShopException
     */
    public function validateOrder($id_cart, $id_order_state, $amount_paid, $payment_method = 'Unknown', $message = null, $extra_vars = array(), $currency_special = null, $dont_touch_amount = false, $secure_key = false, Shop $shop = null)
    {
        $this->context->cart = new PosCart((int) $id_cart);
        $this->context->customer = new PosCustomer((int) $this->context->cart->id_customer);
        $this->context->language = new Language((int) $this->context->cart->id_lang);
        $this->context->shop = ($shop ? $shop : new Shop((int) $this->context->cart->id_shop));

        if (method_exists('ShopUrl', 'resetMainDomainCache')) {
            ShopUrl::resetMainDomainCache();
        }

        $id_currency = $currency_special ? (int) $currency_special : (int) $this->context->cart->id_currency;
        $this->context->currency = new Currency((int) $id_currency, null, (int) $this->context->shop->id);
        if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
            $context_country = $this->context->country;
        }
        $order_state = new OrderState((int) $id_order_state, (int) $this->context->language->id);
        if (!Validate::isLoadedObject($order_state)) {
            throw new PrestaShopException('Can\'t load Order state status');
        }
        if (!$this->active) {
            throw new PrestaShopException(Tools::displayError());
        }
        // Does order already exists ?
        if (Validate::isLoadedObject($this->context->cart) && $this->context->cart->OrderExists() == false) {
            if ($secure_key !== false && $secure_key != $this->context->cart->secure_key) {
                throw new PrestaShopException(Tools::displayError());
            }
            // For each package, generate an order
            $delivery_option_list = $this->context->cart->getDeliveryOptionList();
            $cart_delivery_option = $this->context->cart->getDeliveryOption();
            // If some delivery options are not defined, or not valid, use the first valid option
            foreach ($delivery_option_list as $id_address => $package) {
                if (!isset($cart_delivery_option[$id_address]) || !array_key_exists($cart_delivery_option[$id_address], $package)) {
                    foreach ($package as $key => $val) {
                        $val = $val; // unused, pass validation
                        $cart_delivery_option[$id_address] = $key;
                        break;
                    }
                }
            }

            $order_list = array();
            $order_detail_list = array();
            $reference = is_null($this->origin_order_reference) ? Order::generateReference() : $this->origin_order_reference;
            $this->currentOrderReference = $reference;

            $order_creation_failed = false;
            $cart_total_paid = (float) Tools::ps_round((float) $this->context->cart->getOrderTotal(true, Cart::BOTH), 2);
            $package_list = $this->context->cart->getPackageList(true);
            foreach ($cart_delivery_option as $id_address => $key_carriers) {
                foreach ($delivery_option_list[$id_address][$key_carriers]['carrier_list'] as $id_carrier => $data) {
                    foreach ($data['package_list'] as $id_package) {
                        // Rewrite the id_warehouse
                        if ($id_address) {
                            $package_list[$id_address][$id_package]['id_warehouse'] = (int) $this->context->cart->getPackageIdWarehouse($package_list[$id_address][$id_package], (int) $id_carrier);
                            $package_list[$id_address][$id_package]['id_carrier'] = (int) $id_carrier;
                        }
                    }
                }
            }
            // Make sure CarRule caches are empty
            CartRule::cleanCache();
            $cart_rules = $this->context->cart->getCartRules();
            foreach ($cart_rules as $cart_rule) {
                if (($rule = new CartRule((int) $cart_rule['obj']->id)) && Validate::isLoadedObject($rule)) {
                    if ($error = $rule->checkValidity($this->context, true, true)) {
                        $this->context->cart->removeCartRule((int) $rule->id);
                    }
                }
            }

            foreach ($package_list as $id_address => $package_by_address) {
                foreach ($package_by_address as $id_package => $package) {
                    // Relate to caching problem, our changes doesn't create invoice number for an order then doesn't create
                    // order invoice too. This fixing make sure it should create invoice by setting
                    if (method_exists('ObjectModel', 'disableCache')) {
                        PosOrder::disableCache();
                    }
                    $order = new PosOrder();
                    $order->product_list = $package['product_list'];

                    if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                        $address = new Address($id_address);
                        $this->context->country = new Country((int) $address->id_country, (int) $this->context->cart->id_lang);
                    }

                    $carrier = null;
                    if (!$this->context->cart->isVirtualCart() && isset($package['id_carrier'])) {
                        $carrier = new Carrier((int) $package['id_carrier'], (int) $this->context->cart->id_lang);
                        $order->id_carrier = (int) $carrier->id;
                        $id_carrier = (int) $carrier->id;
                    } else {
                        $order->id_carrier = 0;
                        $id_carrier = 0;
                    }

                    $order->id_customer = (int) $this->context->cart->id_customer;
                    $order->id_address_invoice = (int) $this->context->cart->id_address_invoice;
                    $order->id_address_delivery = (int) $id_address;
                    $order->id_currency = (int) $this->context->currency->id;
                    $order->id_lang = (int) $this->context->cart->id_lang;
                    $order->id_cart = (int) $this->context->cart->id;
                    $order->reference = $reference;
                    $order->id_shop = (int) $this->context->shop->id;
                    $order->id_shop_group = (int) $this->context->shop->id_shop_group;

                    $order->secure_key = ($secure_key ? pSQL($secure_key) : pSQL($this->context->customer->secure_key));
                    // pos get all payment for order
                    $payment = $this->context->cart->getPaymentMethods();
                    $order->payment = !empty($payment) ? $payment : $payment_method;
                    if (isset($this->name)) {
                        $order->module = $this->name;
                    }
                    $order->recyclable = $this->context->cart->recyclable;
                    $order->gift = (int) $this->context->cart->gift;
                    $order->gift_message = $this->context->cart->gift_message;
                    $order->mobile_theme = $this->context->cart->mobile_theme;
                    $order->conversion_rate = $this->context->currency->conversion_rate;
                    $amount_paid = !$dont_touch_amount ? Tools::ps_round((float) $amount_paid, 2) : $amount_paid;
                    $order->total_paid_real = 0;

                    $order->total_products = (float) $this->context->cart->getOrderTotal(false, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);
                    $order->total_products_wt = (float) $this->context->cart->getOrderTotal(true, Cart::ONLY_PRODUCTS, $order->product_list, $id_carrier);

                    $order->total_discounts_tax_excl = (float) abs($this->context->cart->getOrderTotal(false, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts_tax_incl = (float) abs($this->context->cart->getOrderTotal(true, Cart::ONLY_DISCOUNTS, $order->product_list, $id_carrier));
                    $order->total_discounts = $order->total_discounts_tax_incl;

                    $order->total_shipping_tax_excl = (float) $this->context->cart->getPackageShippingCost((int) $id_carrier, false, null, $order->product_list);
                    $order->total_shipping_tax_incl = (float) $this->context->cart->getPackageShippingCost((int) $id_carrier, true, null, $order->product_list);
                    $order->total_shipping = $order->total_shipping_tax_incl;

                    if (!is_null($carrier) && Validate::isLoadedObject($carrier)) {
                        $order->carrier_tax_rate = $carrier->getTaxesRate(new Address($this->context->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}));
                    }

                    $order->total_wrapping_tax_excl = (float) abs($this->context->cart->getOrderTotal(false, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping_tax_incl = (float) abs($this->context->cart->getOrderTotal(true, Cart::ONLY_WRAPPING, $order->product_list, $id_carrier));
                    $order->total_wrapping = $order->total_wrapping_tax_incl;

                    $order->total_paid_tax_excl = (float) Tools::ps_round((float) $this->context->cart->getOrderTotal(false, Cart::BOTH, $order->product_list, $id_carrier), _PS_PRICE_COMPUTE_PRECISION_);
                    $order->total_paid_tax_incl = (float) Tools::ps_round((float) $this->context->cart->getOrderTotal(true, Cart::BOTH, $order->product_list, $id_carrier), _PS_PRICE_COMPUTE_PRECISION_);
                    $order->total_paid = $order->total_paid_tax_incl;
                    $order->round_mode = Configuration::get('PS_PRICE_ROUND_MODE');
                    $order->round_type = Configuration::get('PS_ROUND_TYPE');
                    $order->invoice_date = '0000-00-00 00:00:00';
                    $order->delivery_date = '0000-00-00 00:00:00';

                    // Creating order
                    $result = $order->add();

                    if (!$result) {
                        throw new PrestaShopException('Can\'t save Order');
                    }
                    // Amount paid by customer is not the right one -> Status = payment error
                    // We don't use the following condition to avoid the float precision issues : http://www.php.net/manual/en/language.types.float.php
                    // if ($order->total_paid != $order->total_paid_real)
                    // We use number_format in order to compare two string
                    if ($order_state->logable && number_format($cart_total_paid, 2) != number_format($amount_paid, 2)) {
                        $id_order_state = Configuration::get('PS_OS_ERROR');
                    }

                    $order_list[] = $order;

                    // Insert new Order detail list using cart for the current order
                    $order_detail = new OrderDetail(null, null, $this->context);
                    $order_detail->createList($order, $this->context->cart, $id_order_state, $order->product_list, 0, true, $package_list[$id_address][$id_package]['id_warehouse']);
                    $order_detail_list[] = $order_detail;

                    // Adding an entry in order_carrier table
                    if (!is_null($carrier)) {
                        $order_carrier = new OrderCarrier();
                        $order_carrier->id_order = (int) $order->id;
                        $order_carrier->id_carrier = (int) $id_carrier;
                        $order_carrier->weight = (float) $order->getTotalWeight();
                        $order_carrier->shipping_cost_tax_excl = (float) $order->total_shipping_tax_excl;
                        $order_carrier->shipping_cost_tax_incl = (float) $order->total_shipping_tax_incl;
                        $order_carrier->add();
                    }
                }
            }
            // The country can only change if the address used for the calculation is the delivery address, and if multi-shipping is activated
            if (Configuration::get('PS_TAX_ADDRESS_TYPE') == 'id_address_delivery') {
                $this->context->country = $context_country;
            }
            $transaction_id = isset($extra_vars['transaction_id']) ? $extra_vars['transaction_id'] : null;
            $this->registerPayments($order, $transaction_id);

            // Next !
            $cart_rule_used = array();
            // Make sure CarRule caches are empty
            CartRule::cleanCache();

            foreach ($order_detail_list as $key => $order_detail) {
                $order = $order_list[$key];
                if (!$order_creation_failed && isset($order->id)) {
                    if (!$secure_key) {
                        $message .= '<br />' . Tools::displayError('Warning: the secure key is empty, check your payment account before validation');
                    }

                    // Optional message to attach to this order
                    if (!empty($message)) {
                        $msg = new Message();
                        $message = strip_tags($message, '<br>');
                        if (Validate::isCleanHtml($message)) {
                            $msg->message = $message;
                            $msg->id_cart = (int) $id_cart;
                            $msg->id_customer = (int) ($order->id_customer);
                            $msg->id_order = (int) $order->id;
                            $msg->private = 1;
                            $msg->add();
                        }
                    }

                    // Insert new Order detail list using cart for the current order
                    //$orderDetail = new OrderDetail(null, null, $this->context);
                    //$orderDetail->createList($order, $this->context->cart, $id_order_state);
                    // Construct order detail table for the email
                    $products_list = '';
                    $virtual_product = true;

                    foreach ($order->product_list as $key => $product) {
                        $price = PosProduct::getPriceStatic((int) $product['id_product'], false, ($product['id_product_attribute'] ? (int) $product['id_product_attribute'] : null), 6, null, false, true, $product['cart_quantity'], false, (int) $order->id_customer, (int) $order->id_cart, (int) $order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});
                        $price_wt = PosProduct::getPriceStatic((int) $product['id_product'], true, ($product['id_product_attribute'] ? (int) $product['id_product_attribute'] : null), 2, null, false, true, $product['cart_quantity'], false, (int) $order->id_customer, (int) $order->id_cart, (int) $order->{Configuration::get('PS_TAX_ADDRESS_TYPE')});

                        $customization_quantity = 0;
                        $customized_datas = Product::getAllCustomizedDatas((int) $order->id_cart);
                        if (isset($customized_datas[$product['id_product']][$product['id_product_attribute']])) {
                            $customization_text = '';
                            foreach ($customized_datas[$product['id_product']][$product['id_product_attribute']][$order->id_address_delivery] as $customization) {
                                if (isset($customization['datas'][Product::CUSTOMIZE_TEXTFIELD])) {
                                    foreach ($customization['datas'][Product::CUSTOMIZE_TEXTFIELD] as $text) {
                                        $customization_text .= $text['name'] . ': ' . $text['value'] . '<br />';
                                    }
                                }
                                if (isset($customization['datas'][Product::CUSTOMIZE_FILE])) {
                                    $customization_text .= sprintf(Tools::displayError('%d image(s)'), count($customization['datas'][Product::CUSTOMIZE_FILE])) . '<br />';
                                }
                                $customization_text .= '---<br />';
                            }
                            $customization_text = rtrim($customization_text, '---<br />');

                            $customization_quantity = (int) $product['customization_quantity'];
                            $products_list .= '<tr style="background-color: ' . ($key % 2 ? '#DDE2E6' : '#EBECEE') . ';">
				    <td style="padding: 0.6em 0.4em;width: 15%;">' . $product['reference'] . '</td>
				    <td style="padding: 0.6em 0.4em;width: 30%;"><strong>' . $product['name'] . (isset($product['attributes']) ? ' - ' . $product['attributes'] : '') . ' - ' . Tools::displayError('Customized') . (!empty($customization_text) ? ' - ' . $customization_text : '') . '</strong></td>
				    <td style="padding: 0.6em 0.4em; width: 20%;">' . Tools::displayPrice(Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false) . '</td>
				    <td style="padding: 0.6em 0.4em; width: 15%;">' . $customization_quantity . '</td>
				    <td style="padding: 0.6em 0.4em; width: 20%;">' . Tools::displayPrice($customization_quantity * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false) . '</td>
				</tr>';
                        }

                        if (!$customization_quantity || (int) $product['cart_quantity'] > $customization_quantity) {
                            $products_list .= '<tr style="background-color: ' . ($key % 2 ? '#DDE2E6' : '#EBECEE') . ';">
				    <td style="padding: 0.6em 0.4em;width: 15%;">' . $product['reference'] . '</td>
				    <td style="padding: 0.6em 0.4em;width: 30%;"><strong>' . $product['name'] . (isset($product['attributes']) ? ' - ' . $product['attributes'] : '') . '</strong></td>
				    <td style="padding: 0.6em 0.4em; width: 20%;">' . Tools::displayPrice(Product::getTaxCalculationMethod((int) $this->context->customer->id) == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt, $this->context->currency, false) . '</td>
				    <td style="padding: 0.6em 0.4em; width: 15%;">' . ((int) $product['cart_quantity'] - $customization_quantity) . '</td>
				    <td style="padding: 0.6em 0.4em; width: 20%;">' . Tools::displayPrice(((int) $product['cart_quantity'] - $customization_quantity) * (Product::getTaxCalculationMethod() == PS_TAX_EXC ? Tools::ps_round($price, 2) : $price_wt), $this->context->currency, false) . '</td>
				</tr>';
                        }
                        // Check if is not a virutal product for the displaying of shipping
                        if (!$product['is_virtual']) {
                            $virtual_product &= false;
                        }
                    } // end foreach ($products)

                    $cart_rules_list = '';
                    $total_reduction_value_ti = 0;
                    $total_reduction_value_tex = 0;
                    foreach ($cart_rules as $cart_rule) {
                        $package = array('id_carrier' => (int) $order->id_carrier, 'id_address' => (int) $order->id_address_delivery, 'products' => $order->product_list);
                        $values = array(
                            'tax_incl' => $cart_rule['obj']->getContextualValue(true, $this->context, CartRule::FILTER_ACTION_ALL_NOCAP, $package),
                            'tax_excl' => $cart_rule['obj']->getContextualValue(false, $this->context, CartRule::FILTER_ACTION_ALL_NOCAP, $package),
                        );

                        // If the reduction is not applicable to this order, then continue with the next one
                        if (!$values['tax_excl']) {
                            continue;
                        }
                        /* IF
                         * * - This is not multi-shipping
                         * * - The value of the voucher is greater than the total of the order
                         * * - Partial use is allowed
                         * * - This is an "amount" reduction, not a reduction in % or a gift
                         * * THEN
                         * * The voucher is cloned with a new value corresponding to the remainder
                         */
                        if (count($order_list) == 1 && $values['tax_incl'] > ($order->total_products_wt - $total_reduction_value_ti) && $cart_rule['obj']->partial_use == 1 && $cart_rule['obj']->reduction_amount > 0) {
                            // Create a new voucher from the original
                            $voucher = new CartRule((int) $cart_rule['obj']->id); // We need to instantiate the CartRule without lang parameter to allow saving it
                            unset($voucher->id);

                            // Set a new voucher code
                            $voucher->code = empty($voucher->code) ? Tools::substr(md5($order->id . '-' . $order->id_customer . '-' . $cart_rule['obj']->id), 0, 16) : $voucher->code . '-2';
                            if (preg_match('/\-([0-9]{1,2})\-([0-9]{1,2})$/', $voucher->code, $matches) && $matches[1] == $matches[2]) {
                                $voucher->code = preg_replace('/' . $matches[0] . '$/', '-' . ((int) $matches[1] + 1), $voucher->code);
                            }

                            // Set the new voucher value
                            if ($voucher->reduction_tax) {
                                $voucher->reduction_amount = $values['tax_incl'] - ($order->total_products_wt - $total_reduction_value_ti) - ($voucher->free_shipping == 1 ? $order->total_shipping_tax_incl : 0);
                            } else {
                                $voucher->reduction_amount = $values['tax_excl'] - ($order->total_products - $total_reduction_value_tex) - ($voucher->free_shipping == 1 ? $order->total_shipping_tax_excl : 0);
                            }

                            $voucher->id_customer = (int) $order->id_customer;
                            $voucher->quantity = 1;
                            $voucher->quantity_per_user = 1;
                            $voucher->free_shipping = 0;
                            if ($voucher->add()) {
                                // If the voucher has conditions, they are now copied to the new voucher
                                CartRule::copyConditions((int) $cart_rule['obj']->id, (int) $voucher->id);

                                $params = array(
                                    '{voucher_amount}' => Tools::displayPrice($voucher->reduction_amount, $this->context->currency, false),
                                    '{voucher_num}' => $voucher->code,
                                    '{firstname}' => $this->context->customer->firstname,
                                    '{lastname}' => $this->context->customer->lastname,
                                    '{id_order}' => $order->reference,
                                    '{order_name}' => $order->getUniqReference(),
                                );
                                Mail::Send((int) $order->id_lang, 'voucher', sprintf(Mail::l('New voucher regarding your order %s', (int) $order->id_lang), $order->reference), $params, $this->context->customer->email, $this->context->customer->firstname . ' ' . $this->context->customer->lastname, null, null, null, null, _PS_MAIL_DIR_, false, (int) $order->id_shop);
                            }
                            $values['tax_incl'] -= $values['tax_incl'] - $order->total_products_wt;
                            $values['tax_excl'] -= $values['tax_excl'] - $order->total_products;
                        }
                        $total_reduction_value_ti += $values['tax_incl'];
                        $total_reduction_value_tex += $values['tax_excl'];

                        $order->addCartRule((int) $cart_rule['obj']->id, $cart_rule['obj']->name, $values, 0, $cart_rule['obj']->free_shipping);

                        if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && !in_array($cart_rule['obj']->id, $cart_rule_used)) {
                            $cart_rule_used[] = (int) $cart_rule['obj']->id;

                            // Create a new instance of Cart Rule without id_lang, in order to update its quantity
                            $cart_rule_to_update = new CartRule((int) $cart_rule['obj']->id);
                            $cart_rule_to_update->quantity = max(0, $cart_rule_to_update->quantity - 1);
                            $cart_rule_to_update->update();
                        }

                        $cart_rules_list .= '
						<tr>
							<td colspan="4" style="padding:0.6em 0.4em;text-align:right">' . Tools::displayError('Voucher name:') . ' ' . $cart_rule['obj']->name . '</td>
							<td style="padding:0.6em 0.4em;text-align:right">' . ($values['tax_incl'] != 0.00 ? '-' : '') . Tools::displayPrice($values['tax_incl'], $this->context->currency, false) . '</td>
						</tr>';
                    }

                    // Specify order id for message
                    $old_message = Message::getMessageByCartId((int) $this->context->cart->id);
                    if ($old_message) {
                        $update_message = new Message((int) $old_message['id_message']);
                        $update_message->id_order = (int) $order->id;
                        $update_message->update();

                        // Add this message in the customer thread
                        $customer_thread = new CustomerThread();
                        $customer_thread->id_contact = 0;
                        $customer_thread->id_customer = (int) $order->id_customer;
                        $customer_thread->id_shop = (int) $this->context->shop->id;
                        $customer_thread->id_order = (int) $order->id;
                        $customer_thread->id_lang = (int) $this->context->language->id;
                        $customer_thread->email = $this->context->customer->email;
                        $customer_thread->status = 'open';
                        $customer_thread->token = Tools::passwdGen(12);
                        $customer_thread->add();

                        $customer_message = new CustomerMessage();
                        $customer_message->id_customer_thread = (int) $customer_thread->id;
                        $customer_message->id_employee = 0;
                        $customer_message->message = $update_message->message;
                        $customer_message->private = 0;

                        if (!$customer_message->add()) {
                            $this->errors[] = Tools::displayError('An error occurred while saving message');
                        }
                    }

                    // Hook validate order
                    Hook::exec('actionValidateOrder', array(
                        'cart' => $this->context->cart,
                        'order' => $order,
                        'customer' => $this->context->customer,
                        'currency' => $this->context->currency,
                        'orderStatus' => $order_state,
                    ));

                    foreach ($this->context->cart->getProducts() as $product) {
                        if ($order_state->logable) {
                            ProductSale::addProductSale((int) $product['id_product'], (int) $product['cart_quantity']);
                        }
                    }
                    // Set the order status
                    $new_history = new PosOrderHistory();
                    $new_history->id_order = (int) $order->id;
                    $new_history->changeIdOrderState((int) $id_order_state, $order, true);
                    $new_history->addWithemail(true, $extra_vars);

                    // Switch to back order if needed
                    if (Configuration::get('PS_STOCK_MANAGEMENT') && $order_detail->getStockState() || $order_detail->product_quantity_in_stock <= 0) {
                        $history = new PosOrderHistory();
                        $history->id_order = (int) $order->id;
                        $history->changeIdOrderState(Configuration::get($order->valid ? 'PS_OS_OUTOFSTOCK_PAID' : 'PS_OS_OUTOFSTOCK_UNPAID'), $order, true);
                        $history->addWithemail();
                    }
                    unset($order_detail);

                    // Order is reloaded because the status just changed
                    $order = new PosOrder((int) $order->id);
                    // Send an e-mail to customer (one order = one email)
                    if ($id_order_state != Configuration::get('PS_OS_ERROR') && $id_order_state != Configuration::get('PS_OS_CANCELED') && $this->context->customer->id) {
                        $default_customer = PosCustomer::getDefaultCustomer($order->id_shop);
                        if (($order->id_customer == $default_customer->id && configuration::get('POS_EMAILING_ORDER_COMPLETION_GUEST_CHECKOUT')) || ($order->id_customer != $default_customer->id && configuration::get('POS_EMAILING_ORDER_COMPLETION_STANDARD'))) {
                            $this->sendEmailToCustomer($order, $products_list, $cart_rules_list, $extra_vars, $order_state);
                        }
                    }

                    // updates stock in shops
                    if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                        $product_list = $order->getProducts();
                        foreach ($product_list as $product) {
                            // if the available quantities depends on the physical stock
                            if (StockAvailable::dependsOnStock($product['product_id'])) {
                                // synchronizes
                                StockAvailable::synchronize($product['product_id'], $order->id_shop);
                            }
                        }
                    }
                    $order->updateOrderDetailTax();
                } else {
                    throw new PrestaShopException(Tools::displayError('Order creation failed'));
                }
            } // End foreach $order_detail_list
            // Use the last order as currentOrder
            $this->currentOrder = (int) $order->id;
            $this->order_list = $order_list;
            return true;
        } else {
            throw new PrestaShopException(Tools::displayError('Cart cannot be loaded or an order has already been placed using this cart'));
        }
    }

    /**
     * @param PosOrder   $order
     * @param HTML       $products_list
     * @param HTML       $cart_rules_list
     * @param array      $extra_vars      extra variables to use in email template
     * @param OrderState $order_state
     */
    protected function sendEmailToCustomer(PosOrder $order, $products_list, $cart_rules_list, array $extra_vars, OrderState $order_state)
    {
        $data_order_info = array(
            '{order_name}' => $order->getUniqReference(),
            '{date}' => Tools::displayDate(date('Y-m-d H:i:s'), null, 1),
            '{carrier}' => Tools::displayError($this->getTranslation('no_carrier')), //$virtual_product ? $carrier->name : Tools::displayError($this->getTranslation('no_carrier')),
            '{payment}' => Tools::substr($order->payment, 0, 32),
            '{products}' => $products_list,
            '{discounts}' => $cart_rules_list,
            '{total_paid}' => Tools::displayPrice($order->total_paid, $this->context->currency, false),
            '{total_products}' => Tools::displayPrice($order->total_paid - $order->total_shipping - $order->total_wrapping + $order->total_discounts, $this->context->currency, false),
            '{total_discounts}' => Tools::displayPrice($order->total_discounts, $this->context->currency, false),
            '{total_shipping}' => Tools::displayPrice($order->total_shipping, $this->context->currency, false),
            '{total_wrapping}' => Tools::displayPrice($order->total_wrapping, $this->context->currency, false),
            '{total_tax_paid}' => Tools::displayPrice(($order->total_products_wt - $order->total_products) + ($order->total_shipping_tax_incl - $order->total_shipping_tax_excl), $this->context->currency, false),);
        $customer_info = $this->getCustomerInfo();
        $delivery_info = $this->getDeliveryInfo($order);
        $invoice_info = $this->getInvoiceInfo($order);
        $data = array_merge($data_order_info, $customer_info, $delivery_info, $invoice_info);
        if (is_array($extra_vars)) {
            $data = array_merge($data, $extra_vars);
        }
        // Join PDF invoice
        $file_attachement = $this->getFileAttachement($order, $order_state);

        if (Validate::isEmail($this->context->customer->email)) {
            Mail::Send((int) $order->id_lang, 'order_conf', Mail::l('Order confirmation', (int) $order->id_lang), $data, $this->context->customer->email, $this->context->customer->firstname . ' ' . $this->context->customer->lastname, null, null, $file_attachement, null, _PS_MAIL_DIR_, false, (int) $order->id_shop);
        }
    }

    /**
     * @return array
     *               <pre>
     *               array(
     *               '{firstname}' => string,
     *               '{lastname}' => string,
     *               '{email}' => string,
     *               )
     */
    protected function getCustomerInfo()
    {
        return array(
            '{firstname}' => $this->context->customer->firstname,
            '{lastname}' => $this->context->customer->lastname,
            '{email}' => $this->context->customer->email,
        );
    }

    /**
     * @param Order $order
     *
     * @return array
     *               <pre>
     *               array (
     *               '{invoice_block_txt}' => string,
     *               '{invoice_block_html}' =>string,
     *               '{invoice_company}' => string,
     *               '{invoice_vat_number}' => string,
     *               '{invoice_firstname}' => string,
     *               '{invoice_lastname}' => string,
     *               '{invoice_address2}' => string,
     *               '{invoice_address1}' => string,
     *               '{invoice_city}' => string,
     *               '{invoice_postal_code}' => string,
     *               '{invoice_country}' => string,
     *               '{invoice_state}' => string,
     *               '{invoice_phone}' => string,
     *               '{invoice_other}' => string
     *               )
     */
    protected function getInvoiceInfo(Order $order)
    {
        $invoice_address = new Address((int) $order->id_address_invoice);
        $invoice_state = $invoice_address->id_state ? new State((int) $invoice_address->id_state) : false;

        return array(
            '{invoice_block_txt}' => $this->_getFormatedAddress($invoice_address, "\n"),
            '{invoice_block_html}' => $this->_getFormatedAddress($invoice_address, '<br />', array(
                'firstname' => '<span style="font-weight:bold;">%s</span>',
                'lastname' => '<span style="font-weight:bold;">%s</span>',
            )),
            '{invoice_company}' => $invoice_address->company,
            '{invoice_vat_number}' => $invoice_address->vat_number,
            '{invoice_firstname}' => $invoice_address->firstname,
            '{invoice_lastname}' => $invoice_address->lastname,
            '{invoice_address2}' => $invoice_address->address2,
            '{invoice_address1}' => $invoice_address->address1,
            '{invoice_city}' => $invoice_address->city,
            '{invoice_postal_code}' => $invoice_address->postcode,
            '{invoice_country}' => $invoice_address->country,
            '{invoice_state}' => $invoice_address->id_state ? $invoice_state->name : '',
            '{invoice_phone}' => ($invoice_address->phone) ? $invoice_address->phone : $invoice_address->phone_mobile,
            '{invoice_other}' => $invoice_address->other,
        );
    }

    /**
     * @param Order $order
     *
     * @return array
     *               <pre>
     *               array(
     *               '{delivery_block_txt}' => string,
     *               '{delivery_block_html}' => string,
     *               '{delivery_company}' => string,
     *               '{delivery_firstname}' => string,
     *               '{delivery_lastname}' => string,
     *               '{delivery_address1}' => string,
     *               '{delivery_address2}' => string,
     *               '{delivery_city}' => string,
     *               '{delivery_postal_code}' => string,
     *               '{delivery_country}' => string,
     *               '{delivery_state}' => string,
     *               '{delivery_phone}' => string,
     *               '{delivery_other}' => string
     *               )
     */
    protected function getDeliveryInfo(Order $order)
    {
        $delivery_address = new Address((int) $order->id_address_delivery);
        $delivery_state = $delivery_address->id_state ? new State((int) $delivery_address->id_state) : false;

        return array(
            '{delivery_block_txt}' => $this->_getFormatedAddress($delivery_address, "\n"),
            '{delivery_block_html}' => $this->_getFormatedAddress($delivery_address, '<br />', array(
                'firstname' => '<span style="font-weight:bold;">%s</span>',
                'lastname' => '<span style="font-weight:bold;">%s</span>',
            )),
            '{delivery_company}' => $delivery_address->company,
            '{delivery_firstname}' => $delivery_address->firstname,
            '{delivery_lastname}' => $delivery_address->lastname,
            '{delivery_address1}' => $delivery_address->address1,
            '{delivery_address2}' => $delivery_address->address2,
            '{delivery_city}' => $delivery_address->city,
            '{delivery_postal_code}' => $delivery_address->postcode,
            '{delivery_country}' => $delivery_address->country,
            '{delivery_state}' => $delivery_address->id_state ? $delivery_state->name : '',
            '{delivery_phone}' => ($delivery_address->phone) ? $delivery_address->phone : $delivery_address->phone_mobile,
            '{delivery_other}' => $delivery_address->other,
        );
    }

    /**
     * @param PosOrder   $order
     * @param OrderState $order_state
     *
     * @return array
     *               <pre>
     *               array(
     *               'content' => string,
     *               'name' => string,
     *               'mine' => string
     *               )
     */
    protected function getFileAttachement(PosOrder $order, OrderState $order_state)
    {
        $file_attachement = array();
        if ((int) Configuration::get('PS_INVOICE') && $order_state->invoice && $order->invoice_number) {
            $invoices_collection = $order->getInvoicesCollection();
            $pdf = new PDF($invoices_collection, PDF::TEMPLATE_INVOICE, $this->context->smarty, '');
            $file_attachement['content'] = $pdf->render(false);
            $file_attachement['name'] = Configuration::get('PS_INVOICE_PREFIX', (int) $order->id_lang, null, $order->id_shop) . sprintf('%06d', $order->invoice_number) . '.pdf';
            $file_attachement['mime'] = 'application/pdf';
        }

        return $file_attachement;
    }

    /**
     * @param Order  $order
     * @param string $transaction_id any code related to the current transaction rather than order_id or order_reference
     *
     * @throws PrestaShopException
     */
    protected function registerPayments(Order $order, $transaction_id = null)
    {
        $payments = $this->context->cart->getPayments();
        foreach ($payments as $payment) {
            if (!$order->addOrderPayment($payment['amount'], $payment['payment_name'], $transaction_id)) {
                throw new PrestaShopException('Can\'t save Order Payment');
            }
        }
    }
}
