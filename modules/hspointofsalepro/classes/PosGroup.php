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
class PosGroup extends Group
{

    /**
     *
     * @param int $id_customer
     * @return boolean
     */
    public static function addPosCustomerGroup($id_customer)
    {
        $success = array();
        $pos_customer_group = new PosGroup((int) Configuration::get('POS_CUSTOMER_ID_GROUP'));
        if (!Validate::isLoadedObject($pos_customer_group)) {
            $languages = Language::getLanguages(false);
            foreach ($languages as $language) {
                $pos_customer_group->name[$language['id_lang']] = 'POS Customer';
            }
            $pos_customer_group->price_display_method = PS_TAX_EXC;
            if ($pos_customer_group->add()) {
                $success[] = Configuration::updateValue('POS_CUSTOMER_ID_GROUP', (int) $pos_customer_group->id);
            }
            $customer = new PosCustomer($id_customer);
            if (Validate::isLoadedObject($customer)) {
                $customer->id_default_group = (int) $pos_customer_group->id;
                $success[] = $pos_customer_group->updateModulesRestriction();
            }
        }

        return array_sum($success) >= count($success);
    }

    /**
     * Update (or create) restrictions for modules by group.
     *
     * @return bool
     */
    public function updateModulesRestriction()
    {
        $default_group = new self((int) Configuration::get('PS_CUSTOMER_GROUP'));
        $id_modules = $default_group->getAuthorizedModules();
        $shops = Shop::getShops(true, null, true);
        self::truncateModulesRestrictions($this->id);

        return self::addModulesRestrictions($this->id, $id_modules, $shops);
    }

    /**
     * @return array
     *               array(<pre>
     *               int,
     *               int,
     *               ....
     *               )</pre>
     */
    protected function getAuthorizedModules()
    {
        $modules = Module::getAuthorizedModules($this->id);
        $id_modules = array();
        if (!empty($modules)) {
            foreach ($modules as $module) {
                $id_modules[] = $module['id_module'];
            }
        }

        return array_unique($id_modules);
    }

    /**
     * A method to display price in POS.
     *
     * @return int
     */
    public static function getPosPriceDisplayMethod()
    {
        return parent::getPriceDisplayMethod((int) Configuration::get('POS_CUSTOMER_ID_GROUP', 0));
    }
}
