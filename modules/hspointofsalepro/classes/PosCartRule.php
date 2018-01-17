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
class PosCartRule extends CartRule
{

    /**
     * Restricted categories
     * @var array
     * <pre>
     * array(
     *  int,
     *  int
     * )
     */
    public $id_categories = array();

    /**
     *
     * @return string
     */
    protected function generateCode()
    {
        $voucher_code = null;
        do {
            $voucher_code = 'FID-POS' . rand(1000, 100000);
        } while (CartRule::cartRuleExists($voucher_code));
        return $voucher_code;
    }

    /**
     * Generate "From" date for a newly created cart rule of a customer
     * @param int $id_customer
     * @return int
     */
    protected function generateFromDate()
    {
        $date_from = PosLoyalty::getLatestLoyaltyDate($this->id_customer);
        if (empty($date_from)) {
            $date_from = time();
        }
        return $date_from;
    }

    /**
     * @see CartRule::add()
     */
    public function add($autodate = true, $null_values = false)
    {
        $this->code = !empty($this->code) ? $this->code : $this->generateCode();
        $this->name = !empty($this->name) ? $this->name : PosLoyalty::generateName();
        $this->date_from = date('Y-m-d H:i:s', $this->generateFromDate());
        $this->date_to = date('Y-m-d H:i:s', strtotime($this->date_from . ' +1 year'));
        $this->quantity = 1;
        $this->highlight = 1;
        $this->quantity_per_user = 1;
        $this->active = 1;
        return parent::add($autodate, $null_values);
    }

    /**
     * Generate a cart rule of a customer
     * @param float $amount
     * @param string $voucher_code
     * @param int $id_customer
     * @param int $id_currency
     * @return PosCartRule
     */
    public static function generateCartRule($amount, $voucher_code, $id_customer, $id_currency)
    {
        // Voucher creation and affectation to the customer
        $cart_rule = new self();
        $cart_rule->id_customer = (int) $id_customer;
        $cart_rule->code = $voucher_code;
        $cart_rule->reduction_currency = (int) $id_currency;
        $cart_rule->reduction_amount = $amount;
        $cart_rule->minimum_amount_currency = (int) $id_currency;
        $cart_rule->reduction_tax = PosLoyalty::getLoyaltyTax();
        $cart_rule->minimum_amount = PosLoyalty::getMinimumAmount();
        $categories = PosLoyalty::getRestrictedIdCategories();
        $cart_rule->product_restriction = empty($categories) ? 0 : 1;
        if ($cart_rule->add()) {
            if ($cart_rule->product_restriction) {
                self::attachRestrictedCategories($cart_rule->id, $categories);
            }
            PosLoyalty::registerDiscount($cart_rule);
        }
        return $cart_rule;
    }
    /**
     * @param int $id_cart_rule
     * @param array $categories
     * @return boolean
     */
    protected static function attachRestrictedCategories($id_cart_rule, $categories)
    {
        if (empty($categories)) {
            return true;
        }
        try {
            //Creating rule group
            $sql = 'INSERT INTO ' . _DB_PREFIX_ . 'cart_rule_product_rule_group (id_cart_rule, quantity) VALUES ('. (int) $id_cart_rule.', 1)';
            Db::getInstance()->execute($sql);
            $id_group = (int) Db::getInstance()->Insert_ID();

            //Creating product rule
            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'cart_rule_product_rule` (`id_product_rule_group`, `type`) VALUES ('. (int)$id_group.', \'categories\')';
            Db::getInstance()->execute($sql);
            $id_product_rule = (int) Db::getInstance()->Insert_ID();

            //Creating restrictions
            $values = array();
            foreach ($categories as $id_category) {
                $id_category = (int) $id_category;
                $values[] = "('$id_product_rule', '$id_category')";
            }
            if (!empty($values)) {
                $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'cart_rule_product_rule_value` (`id_product_rule`, `id_item`) VALUES '. implode(',', $values);
                Db::getInstance()->execute($sql);
            }
        } catch (Exception $ex) {
            return false;
        }
        return true;
    }
    
    /**
     * @param Context $context
     * @param string  $type
     * @param float   $value
     * @param array   $names
     * <pre>
     * array(
     *      int => string,// id_lang => name by lang
     *      int => string,
     *      ...
     * )
     * @param string  $description
     *
     * @return PosCartRule
     */
    public static function addOrderDiscount(Context $context, $type, $value, array $names, $description = null)
    {
        $cart_average_vat_rate = $context->cart->getAverageProductsTaxRate();
        $tax_included = PS_TAX_INC == $context->customer->getPriceDisplayMethod();
        $reduction_amount = $tax_included ? $value : $value + $value * $cart_average_vat_rate;
        $cart_rule = new self();
        $cart_rule->name = $names;
        $cart_rule->reduction_amount = ($type === PosConstants::DISCOUNT_TYPE_AMOUNT) ? (float) $reduction_amount : 0;
        $cart_rule->reduction_percent = ($type === PosConstants::DISCOUNT_TYPE_PERCENTAGE) ? (float) $value : 0;
        $cart_rule->description = $description;
        $cart_rule->date_from = date('Y-m-d H:i:s', time());
        $cart_rule->date_to = date('Y-m-d H:i:s', time() + PosConstants::DISCOUNT_DURATION_ORDER);
        $cart_rule->code = Tools::passwdGen(8, 'NO_NUMERIC');
        $cart_rule->quantity = 1;
        $cart_rule->quantity_per_user = 1;
        $cart_rule->active = 1;
        $cart_rule->id_customer = (int) $context->cart->id_customer;
        $cart_rule->minimum_amount_currency = (int) $context->cart->id_currency;
        $cart_rule->reduction_currency = (int) $context->cart->id_currency;
        $cart_rule->reduction_tax = 1;
        $cart_rule->add();

        return $cart_rule;
    }

    /**
    * @param int $id_customer
    * @param array $cart_rules
    * @return boolean
    */
    public static function updateCartRulesByIdCustomer($id_customer, array $cart_rules = array())
    {
        $result = true;
        if (!empty($cart_rules)) {
            foreach ($cart_rules as $cart_rule) {
                $sql = 'UPDATE `'._DB_PREFIX_.'cart_rule` SET `id_customer` = '.(int) $id_customer.'
                WHERE `id_cart_rule` = '.(int) $cart_rule['id_cart_rule'];
                $result = $result && Db::getInstance()->execute($sql);
            }
        }
        return $result;
    }
}
