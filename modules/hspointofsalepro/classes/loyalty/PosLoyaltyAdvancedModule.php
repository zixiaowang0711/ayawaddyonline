<?php
/**
 * RockPOS PosRewardModule
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 *
 */
if (!class_exists('LoyaltyModuleAdvanced')) {
    require_once _PS_MODULE_DIR_ . '/totloyaltyadvanced/LoyaltyModuleAdvanced.php';
    require_once _PS_MODULE_DIR_ . '/totloyaltyadvanced/LoyaltyStateModuleAdvanced.php';
}
class PosLoyaltyAdvancedModule extends PosLoyaltyAbstract
{

    public function __construct()
    {
        $this->voucher_code = PosConstants::LOYALTY_VOUCHER_CODE_PREFIX . '-' . rand(1000, 100000);
        $this->module_name = 'totloyaltyadvanced';
    }

    /**
     *
     * @param int $points
     * @return int
     */
    public function convertPoints($points)
    {
         return (float) LoyaltyModuleAdvanced::getVoucherValue($points);
    }

    /**
     *
     * @param PosCustomer $customer
     * @return float
     */
    public function getLoyaltyPoints($customer)
    {
        return (float) LoyaltyModuleAdvanced::getPointsByCustomer($customer->id);
    }

    /**
     *
     * @param PosCartRule $cart_rule
     * @return boolean
     */
    public function registerDiscount($cart_rule)
    {
        return LoyaltyModuleAdvanced::registerDiscount($cart_rule);
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
            $query->from('totloyalty');
            $query->where('`id_cart_rule` = 0');
            $query->where('`id_customer` = ' . (int) $id_customer);
            $query->orderBy('`date_add` DESC');
            $date_add = Db::getInstance()->getValue($query);
        }
        return $date_add;
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
