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
if (!class_exists('RewardsStateModel')) {
    require_once _PS_MODULE_DIR_ . '/allinone_rewards/models/RewardsStateModel.php';
    require_once _PS_MODULE_DIR_ . '/allinone_rewards/models/RewardsTemplateModel.php';
}
class PosRewardModule extends PosLoyaltyAbstract
{

    public function __construct()
    {
        $this->voucher_code = PosConstants::LOYALTY_VOUCHER_CODE_PREFIX . '-' . rand(1000, 100000);
        $this->module_name = 'allinone_rewards';
    }

    /**
     *
     * @param int $points
     * @return int
     */
    public function convertPoints($points)
    {
        return (float) $points;
    }

    /**
     *
     * @param PosCustomer $customer
     * @return float
     */
    public function getLoyaltyPoints($customer)
    {
        $points = 0;
        if ($this->isValidModule()) {
            $query = new DbQuery();
            $query->select('SUM(`credits`)');
            $query->from('rewards');
            $query->where('`id_cart_rule` = 0');
            $query->where('`plugin` = \'loyalty\'');
            $query->where('`id_customer` = ' . (int) $customer->id);
            $query->where('`id_reward_state` = ' . (int) RewardsStateModel::getValidationId());
            $points = Db::getInstance()->getValue($query);
        }
        return $points;
    }

    /**
     *
     * @param PosCartRule $cart_rule
     * @return boolean
     */
    public function registerDiscount($cart_rule)
    {
        if ($this->isValidModule()) {
            return Db::getInstance()->execute(
                'UPDATE `' . _DB_PREFIX_ . 'rewards`
                SET `id_reward_state` = ' . (int) RewardsStateModel::getConvertId() . ',
                    `id_cart_rule` = ' . (int) $cart_rule->id . '
                WHERE
                    `id_customer` = ' . (int) $cart_rule->id_customer . ' AND
                    `id_reward_state` = ' . (int) RewardsStateModel::getValidationId() . ' AND
                    `plugin` = \'loyalty\' AND
                    `id_cart_rule` = 0
                '
            );
        }
        return false;
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
            $name = 'REWARDS_VOUCHER_DETAILS';
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
            $query->from('rewards');
            $query->where('`id_cart_rule` = 0');
            $query->where('`id_customer` = ' . (int) $id_customer);
            $query->where('`id_reward_state` = 2');
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
        $context = Context::getContext();
        $id_template = (int) MyConf::getIdTemplate('core', (int) $context->customer->id);
        $all_categories = (int) MyConf::get('REWARDS_ALL_CATEGORIES', null, $id_template);
        $categories = explode(',', MyConf::get('REWARDS_VOUCHER_CATEGORY', null, $id_template));
        if (!$all_categories && is_array($categories) && count($categories) > 0) {
            $id_categories = $categories;
        }
        return $id_categories;
    }
    /**
     *
     * @return int
     */
    public function getLoyaltyTax()
    {
        $context = Context::getContext();
        $id_template = (int) MyConf::getIdTemplate('core', (int) $context->customer->id);
        return (int)MyConf::get('REWARDS_MINIMAL_TAX', null, $id_template);
    }
    /**
     *
     * @return int
     */
    public function getMinimumAmount()
    {
        $context = Context::getContext();
        $id_template = (int) MyConf::getIdTemplate('core', (int) $context->customer->id);
        return (int)MyConf::get('REWARDS_VOUCHER_MIN_ORDER_'.$context->currency->id, null, $id_template);
    }
}
