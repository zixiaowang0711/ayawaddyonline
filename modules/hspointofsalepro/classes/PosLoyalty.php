<?php
/**
 * RockPOS Loyalty
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 *
 */
class PosLoyalty
{
    /**
     *
     * @var instance of PosRewardModule or PosLoyaltyModule
     */
    protected static $instance;

    /**
     *
     * @return instance of PosRewardModule or PosLoyaltyModule
     */
    public static function getInstance()
    {
        switch (Configuration::get('POS_LOYALTY')) {
            case 'allinone_rewards':
                if (Module::isEnabled('allinone_rewards') && Module::getInstanceByName('allinone_rewards')) {
                    self::$instance = new PosRewardModule();
                }
                break;
            case 'totloyaltyadvanced':
                if (Module::isEnabled('totloyaltyadvanced') && Module::getInstanceByName('totloyaltyadvanced')) {
                    self::$instance = new PosLoyaltyAdvancedModule();
                }
                break;

            default:
                if (Module::isEnabled('loyalty') && Module::getInstanceByName('loyalty')) {
                    self::$instance = new PosLoyaltyModule();
                }
                break;
        }
        return self::$instance;
    }

    /**
     * @return boolean
     */
    public static function loadLoyalty()
    {
        if (!self::$instance) {
            self::getInstance();
        }
        return (
                self::$instance instanceof PosRewardModule ||
                self::$instance instanceof PosLoyaltyModule ||
                self::$instance instanceof PosLoyaltyAdvancedModule
                );
    }

    /**
     *
     * @param PosCustomer $customer
     * @param Context $context
     * @return array
     * <pre>
     * array(
     *  points => int,
     *  value => float
     * )
     */
    public static function getLoyalty(PosCustomer $customer, $context = null)
    {
        if (is_null($context)) {
            $context = Context::getContext();
        }
        $loyalty = array(
            'points' => 0,
            'value' => 0
        );
        if (!Validate::isLoadedObject($customer)) {
            return $loyalty;
        }
        if ($customer->isDefaultCustomer($context->cookie->pos_id_employee, $context->shop->id)) {
            return $loyalty;
        }
        if (!self::loadLoyalty()) {
            return $loyalty;
        }

        $points = (int) self::$instance->getLoyaltyPoints($customer);
        $loyalty['points'] = $points;
        $loyalty['value'] = self::$instance->convertPoints($points);
        return $loyalty;
    }

    /**
     *
     * @return array
     * <pre>
     * array(
     *  int => string,//id_lang => cart rule's name
     *  int => string,
     *  ...
     * )
     */
    public static function generateName()
    {
        if (!self::$instance) {
            self::getInstance();
        }
        return self::loadLoyalty() ? self::$instance->generateName() : array(Configuration::get('PS_LANG_DEFAULT') => PosConfiguration::LOYALTY_REWARD);
    }

    /**
     *
     * @param type $id_customer
     * @return string
     */
    public static function getLatestLoyaltyDate($id_customer)
    {
        return self::loadLoyalty() ? self::$instance->getLatestLoyaltyDate($id_customer) : '';
    }

    /**
     *
     * @param PosCartRule $cart_rule
     * @return boolean
     */
    public static function registerDiscount($cart_rule)
    {
        return self::loadLoyalty() ? self::$instance->registerDiscount($cart_rule) : false;
    }

    /**
     *
     * @return array
     */
    public static function getRestrictedIdCategories()
    {
        return self::loadLoyalty() ? self::$instance->getRestrictedIdCategories() : array();
    }
    /**
     *
     * @return int
     */
    public static function getLoyaltyTax()
    {
        return self::loadLoyalty() ? self::$instance->getLoyaltyTax() : 0;
    }
    /**
     *
     * @return int
     */
    public static function getMinimumAmount()
    {
        return self::loadLoyalty() ? self::$instance->getMinimumAmount() : 0;
    }
}
