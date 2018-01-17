<?php
/**
 * RockPOS PosLoyaltyModule
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 *
 */
if (!class_exists('LoyaltyModule')) {
    require_once _PS_MODULE_DIR_ . '/loyalty/LoyaltyModule.php';
    require_once _PS_MODULE_DIR_ . '/loyalty/LoyaltyStateModule.php';
}

class PosLoyaltyModule extends PosLoyaltyAbstract
{

    public function __construct()
    {
        $this->voucher_code = PosConstants::LOYALTY_VOUCHER_CODE_PREFIX . '-' . rand(1000, 100000);
        $this->module_name = 'loyalty';
    }

    /**
     *
     * @param float $points
     * @return float
     */
    public function convertPoints($points)
    {
        return (float) LoyaltyModule::getVoucherValue($points);
    }

    /**
     *
     * @param PosCustomer $customer
     * @return float
     */
    public function getLoyaltyPoints($customer)
    {
        return (float) LoyaltyModule::getPointsByCustomer($customer->id);
    }

    /**
     *
     * @param PosCartRule $cart_rule
     * @return boolean
     */
    public function registerDiscount($cart_rule)
    {
        return LoyaltyModule::registerDiscount($cart_rule);
    }

    /**
     * Return the date when the last loyalty is recorded, in timestamp
     * @param int $id_customer
     * @return int
     */
    public function getLatestLoyaltyDate($id_customer)
    {
        $date_add = '';
        if ($this->isValidModule()) {
            $query = new DbQuery();
            $query->select('UNIX_TIMESTAMP(`date_add`)');
            $query->from('loyalty');
            $query->where('`id_cart_rule` = 0');
            $query->where('`id_customer` = ' . (int) $id_customer);
            $query->orderBy('`date_add` DESC');
            $date_add = Db::getInstance()->getValue($query);
        }
        return $date_add;
    }

    /**
     * @param string $name
     * @return array
     * <pre>
     * array(
     *  int => string,//id_lang => cart rule's name
     *  int => string,
     *  ...
     * )
     */
    public function generateName($name = null)
    {
        if (is_null($name)) {
            $name = 'PS_LOYALTY_VOUCHER_DETAILS';
        }
        return parent::generateName($name);
    }

    /**
     *
     * @return array
     */
    public function getRestrictedIdCategories()
    {
        $id_categories = array();
        $categories = Configuration::get('PS_LOYALTY_VOUCHER_CATEGORY');
        if ($categories) {
            $id_categories = explode(',', $categories);
        }
        return $id_categories;
    }
    /**
     *
     * @return int
     */
    public function getLoyaltyTax()
    {
        return Configuration::get('PS_LOYALTY_TAX');
    }

    /**
     *
     * @return int
     */
    public function getMinimumAmount()
    {
        return Configuration::get('PS_LOYALTY_MINIMAL');
    }
}
