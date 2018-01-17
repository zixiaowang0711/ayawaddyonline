<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * An installer of the module.
 */
class PosInstaller extends PosSetup
{

    /**
     * @see parent::$tabs_to_install
     */
    protected $tabs_to_install = array(
        'AdminPos',
        'AdminRockPosSales',
        'AdminRockPosManage'
    );

    /**
     * @see parent::$hooks_to_register
     */
    protected $hooks_to_register = array(
        'displayBackOfficeHeader',
        'actionProductSave',
        'actionProductDelete',
        'actionCarrierUpdate',
        'actionAdminOrdersListingFieldsModifier',
        'moduleRoutes',
    );

    /**
     * @var array
     * <pre>
     * array(
     *  string => string,
     *  string => string
     * ...
     * )
     */
    protected $configuration_keys_to_update_value = array(
        'POS_DEFAULT_ORDER_STATE' => 'POS_DEFAULT_STANDARD_ORDER_STATE',
        'POS_SELECTED_ORDER_STATES' => 'POS_SELECTED_STANDARD_ORDER_STATES',
        'POS_SEND_EMAIL_TO_CUSTOMER' => 'POS_EMAILING_ACCOUNT_CREATION',
        'POS_ORDER_DISABLED_PRODUCTS' => 'POS_PRODUCT_INACTIVE',
        'POS_ORDER_OUT_OF_STOCK' => 'POS_PRODUCT_OUT_OF_STOCK',
        'POS_FILTER_PRODUCT_DEFAULT_CATEGORY' => 'POS_DEFAULT_CATEGORIES',
    );

    /**
     * @see parent::__construct
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        parent::__construct($module);
        $this->configurations_to_install = PosConfiguration::getDefaultSettings();
    }

    /**
     * @return boolean
     */
    protected function updateConfigs()
    {
        $success = array();
        if (!empty($this->configuration_keys_to_update_value)) {
            foreach ($this->configuration_keys_to_update_value as $old_key => $new_key) {
                $old_value = Configuration::get($old_key);
                if ($old_value !== false) {
                    $success[] = Configuration::updateValue($new_key, $old_value);
                    $success[] = Configuration::deleteByName($old_key);
                }
            }
        }
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @return boolean
     */
    protected function installTables()
    {
        $install_queries = array();
        $install_queries[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pos_payment` (
					`id_pos_payment` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`id_module` int(10) unsigned NULL,
					`reference` int(2) DEFAULT 0,
					`rule` varchar(255) NULL,
                                        `is_default` tinyint(1) DEFAULT 0,
					`active` tinyint(1) NOT NULL,
                    `position` int(10) NULL,
				     PRIMARY KEY (`id_pos_payment`)
				     )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

        // create table pos_payment_lang
        $install_queries[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pos_payment_lang` (
				      `id_pos_payment` int(10) unsigned NOT NULL,
				      `id_lang` int(10) unsigned NOT NULL,
				      `payment_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
				      `label` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NULL,
				      PRIMARY KEY (`id_pos_payment`,`id_lang`)
				    )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

        $install_queries[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pos_payment_shop` (
					`id_pos_payment` int(10) unsigned NOT NULL,
					`id_shop` int(10) unsigned NOT NULL,
					PRIMARY KEY (`id_pos_payment`,`id_shop`)
				    )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

        // create table cart_postpayment
        $install_queries[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pos_cart_payment` (
                                        `id_pos_cart_payment` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                        `id_cart` int(10) unsigned NOT NULL,
                                        `id_pos_payment` int(10) unsigned NOT NULL,
                                        `amount` decimal(20,6) NOT NULL,
                                        `reference` varchar(255) NULL,
                                        `message` text NULL,
                                        `given_money` decimal(20,6) DEFAULT 0,
                                        `change` decimal(20,6) DEFAULT 0,
                                        PRIMARY KEY (`id_pos_cart_payment`)
                                        )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

        $install_queries[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pos_orders` (
					`id_pos_order` int(11) unsigned NOT NULL,
					`status` tinyint(1) NOT NULL DEFAULT 0,
					`id_employee` int(11) NULL,
					`note` text,
					`show_note` tinyint(1) DEFAULT 0,
					PRIMARY KEY (`id_pos_order`)
				    )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

        $install_queries[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pos_search_word` (
                                        `id_word` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`id_shop` int(10) NOT NULL DEFAULT 1,
					`id_lang` int(10) NULL,
					`word` varchar(15) NOT NULL,
					PRIMARY KEY (`id_word`),
                                        UNIQUE KEY `id_lang` (`id_lang`,`id_shop`,`word`)
				    )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

        $install_queries[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pos_search_index` (
                                        `id_product` int(10) UNSIGNED NOT NULL,
                                        `id_word` int(10) UNSIGNED NOT NULL,
                                        `weight` smallint(4) UNSIGNED NOT NULL DEFAULT 1,
					PRIMARY KEY (`id_product`, `id_word`),
                                        KEY `id_product` (`id_product`)
				    )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

        $install_queries[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pos_exchange` (
					`id_pos_exchange` int(10) unsigned NOT NULL AUTO_INCREMENT,
					`id_order_old` int(10) unsigned NULL,
					`id_order_new` int(10) unsigned NULL,
					`id_cart_new` int(10) unsigned NULL,
                                        PRIMARY KEY (`id_pos_exchange`)
				    )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';


        $install_queries[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pos_cart` (
                                        `id_cart` int(10) unsigned null,
                                        `id_employee` int(10) unsigned null,
                                        `id_shop` int(10) unsigned null,
                                        `shipping` decimal(17,2) DEFAULT 0,
                                        `order_reference` varchar(10) NOT NULL,
                                        `public` tinyint(1) DEFAULT 0,
                                        `note` text NOT NULL,
                                        `date_upd` datetime NOT NULL,
                                         PRIMARY KEY (`id_cart`,`id_employee`,`id_shop`)
                                         )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';


        $install_queries[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pos_sales_commission` (
                                        `id_cart` int(10) unsigned NULL,
                                        `order_reference` varchar(10) NULL,
                                        `id_employee` int(10) unsigned NULL,
                                        `id_customer` int(10) unsigned NULL,
                                        `commission_rate` decimal(20,6) NOT NULL DEFAULT 0,
                                        `commission_amount` decimal(20,6) DEFAULT 0,
                                        `commission_percent` decimal(20,6) DEFAULT 0,
                                        `date_add` datetime NULL,
                                        `date_upd` datetime NULL,
                                        PRIMARY KEY (`id_cart`)
                                        )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';

        $install_queries[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'pos_custom_product` (
                                        `id_pos_custom_product` int(10) unsigned NOT NULL AUTO_INCREMENT,
                                        `id_cart` int(10) unsigned null,
                                        `id_product` int(10) unsigned null,
                                        `date_add` datetime NOT NULL,
                                    PRIMARY KEY (`id_pos_custom_product`)
                                    )ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8';


        $success = array();
        foreach ($install_queries as $install_query) {
            $success[] = Db::getInstance()->execute($install_query);
        }
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @return boolean
     */
    protected function uninstallTabs()
    {
        $success = array();
        $tabs = Tab::getCollectionFromModule($this->module->name);
        if (!empty($tabs)) {
            foreach ($tabs as $tab) {
                $success[] = $tab->delete();
            }
        }
        return array_sum($success) >= count($success);
    }

    /**
     * @return boolean
     */
    protected function installOthers()
    {
        $success = array();
        $success[] = $this->installOtherConfigs();
        $success[] = PosPayment::installDefaultPayments();
        $success[] = $this->insertPreOrderState();
        $success[] = $this->insertSelectedOrderStates();
        $success[] = $this->activateCartRule();
        $success[] = $this->createDummyCustomer();
        $success[] = $this->updateConfigs();
        $success[] = $this->addPosPaymentIsDefault();
        $success[] = $this->addPosCartNote();
        $success[] = $this->insertDataPosCart();
        $success[] = $this->installCarrier();
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @return boolean
     */
    protected function installOtherConfigs()
    {
        $success = array();
        $success[] = Configuration::updateValue('POS_ROCKPOS_NAME', $this->module->name);
        return array_sum($success) >= count($success);
    }

    /**
     * Insert a new order status pre-order.
     *
     * @return boolean
     */
    protected function insertPreOrderState()
    {
        $success = array();
        $order_state = new OrderState((int) Configuration::get('POS_DEFAULT_PRE_ORDER_STATE'));
        if (!Validate::isLoadedObject($order_state)) {
            $languages = Language::getLanguages(false);
            $order_state = new OrderState();
            foreach ($languages as $language) {
                $order_state->name[$language['id_lang']] = 'Pre-order';
                $order_state->template[$language['id_lang']] = 'payment';
            }
            $order_state->send_email = 1;
            $order_state->module_name = $this->module->name;
            $order_state->color = '#FF8C00';
            $order_state->unremovable = 1;
            $order_state->logable = 1;
            $success[] = $order_state->add();
            if (array_sum($success) >= count($success)) {
                $success[] = PosConfiguration::updateValue('POS_DEFAULT_PRE_ORDER_STATE', (int) $order_state->id);
                $success[] = $this->copyLogo($order_state);
            }
        }
        return array_sum($success) >= count($success);
    }

    /**
     * Copy logo of element Pre order state
     * @param $order_state
     * @return boolean
     */
    protected function copyLogo($order_state)
    {
        return Tools::copy(_PS_ROOT_DIR_ . '/modules/' . $this->module->name . '/views/img/pre-order.gif', _PS_ORDER_STATE_IMG_DIR_ . '/' . (int) $order_state->id . '.gif');
    }

    /**
     * Insert default selected order states when upgrade module version 2.3.2 or install module.
     *
     * @return boolean
     */
    protected function insertSelectedOrderStates()
    {
        $context = Context::getContext();
        $order_states = OrderState::getOrderStates((int) $context->language->id);
        $id_order_states = array();
        foreach ($order_states as $order_state) {
            $id_order_states[] = $order_state['id_order_state'];
        }
        $success = array();
        $success[] = Configuration::updateValue('POS_SELECTED_STANDARD_ORDER_STATES', implode(',', $id_order_states));
        $success[] = Configuration::updateValue('POS_SELECTED_PRE_ORDER_STATES', implode(',', $id_order_states));
        return array_sum($success) >= count($success);
    }

    /**
     * Activate cart rule for current cart.
     *
     * @return boolean
     */
    protected function activateCartRule()
    {
        $success = array();
        if (!CartRule::isFeatureActive()) {
            $success[] = Configuration::updateValue('PS_CART_RULE_FEATURE_ACTIVE', 1);
        }
        return array_sum($success) >= count($success);
    }

    /**
     * Activate cart rule for current cart.
     *
     * @return boolean
     */
    protected function createDummyCustomer()
    {
        $success = array();
        $shops = Shop::getShops();
        $context = Context::getContext();
        foreach ($shops as $shop) {
            $success[] = PosCustomer::createDummyCustomer($context->employee->id, (int) $shop['id_shop'], (int) $shop['id_shop_group']);
        }
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @return boolean
     */
    public function uninstall()
    {
        $success = array();
        $success[] = $this->uninstallTabs();
        $success[] = $this->removeSettings();
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @return boolean
     */
    protected function removeSettings()
    {
        $success = array();
        if (PosConstants::POS_REMOVE_TABLES_AND_SETTINGS) {
            $success[] = PosConfiguration::removeSettings();
            $success[] = $this->uninstallTables();
        }
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @return boolean
     */
    protected function uninstallTables()
    {
        $success = $uninstall_queries = array();
        $uninstall_queries[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pos_payment`';
        $uninstall_queries[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pos_payment_lang`';
        $uninstall_queries[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pos_payment_shop`';
        $uninstall_queries[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pos_orders`';
        $uninstall_queries[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pos_cart_payment`';
        $uninstall_queries[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pos_search_word`';
        $uninstall_queries[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pos_search_index`';
        $uninstall_queries[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pos_exchange`';
        $uninstall_queries[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pos_cart`';
        $uninstall_queries[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pos_sales_commission`';
        $uninstall_queries[] = 'DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pos_custom_product`';
        foreach ($uninstall_queries as $uninstall_query) {
            $success[] = Db::getInstance()->execute($uninstall_query);
        }
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @return boolean
     */
    protected function addPosPaymentIsDefault()
    {
        $success = array();
        $sql = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = \'' . _DB_NAME_ . '\' AND TABLE_NAME = \'' . _DB_PREFIX_ . 'pos_payment\' AND COLUMN_NAME = \'is_default\'';
        if (!Db::getInstance()->getValue($sql)) {
            $success[] = Db::getInstance()->execute('ALTER TABLE `' . _DB_PREFIX_ . 'pos_payment` ADD `is_default` TINYINT DEFAULT 0 AFTER `active`');
            if (array_sum($success) >= count($success)) {
                $success[] = Db::getInstance()->execute(
                    'UPDATE `' . _DB_PREFIX_ . 'pos_payment` AS p
                    JOIN (SELECT MIN(`id_pos_payment`) AS `max_id` FROM `' . _DB_PREFIX_ . 'pos_payment`) AS payment
                    ON payment.`max_id` = p.`id_pos_payment`
                    SET p.`is_default` = 1'
                );
            }
        }
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @return boolean
     */
    protected function addPosCartNote()
    {
        $success = array();
        $sql = 'SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = \'' . _DB_NAME_ . '\' AND TABLE_NAME = \'' . _DB_PREFIX_ . 'pos_cart\' AND COLUMN_NAME = \'note\'';
        if (!Db::getInstance()->getValue($sql)) {
            $success[] = Db::getInstance()->execute(
                'ALTER TABLE `' . _DB_PREFIX_ . 'pos_cart`
                ADD `order_reference` varchar(10) NOT NULL AFTER `shipping`,
                ADD `public` tinyint(1) DEFAULT 0 AFTER `order_reference`,
                ADD `note` text NOT NULL AFTER `public`'
            );
        }
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @return boolean
     */
    protected function insertDataPosCart()
    {
        $success = array();
        $sql = 'SELECT DISTINCT TABLE_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = \'' . _DB_NAME_ . '\' AND TABLE_NAME = \'' . _DB_PREFIX_ . 'pos_active_cart\'';
        if (Db::getInstance()->getValue($sql)) {
            $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'pos_cart`(`id_cart`, `id_employee`, `id_shop`, `date_upd`)
                    SELECT `id_cart`, `id_employee`, `id_shop`, `date_upd`  FROM `' . _DB_PREFIX_ . 'pos_cart`';
            $success[] = Db::getInstance()->execute($sql);
            $success[] = Db::getInstance()->execute('DROP TABLE IF EXISTS `' . _DB_PREFIX_ . 'pos_active_cart`');
        }
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @return boolean
     */
    public function installCarrier()
    {
        $success = array();
        $carrier = new PosCarrier((int) Configuration::get('POS_ID_CARRIER'));
        if (Validate::isLoadedObject($carrier)) {
            if ($carrier->deleted) {
                $carrier->deleted = 0;
                $carrier->update();
            }
            return true;
        }
        $languages = Language::getLanguages(false);
        $carrier->name = $this->module->i18n['pos'];
        $carrier->is_module = 1;
        $carrier->active = 1;
        $carrier->deleted = 0;
        $carrier->shipping_handling = 0;
        $carrier->range_behavior = 0;
        $carrier->shipping_external = 1;
        $carrier->external_module_name = $this->module->name;
        $carrier->need_range = 1;
        $carrier->id_tax_rules_group = 0;
        foreach ($languages as $language) {
            $carrier->delay[(int) $language['id_lang']] = $this->module->i18n['pick_up_in_store'];
        }

        if ($carrier->add()) {
            $success[] = Configuration::updateValue('POS_ID_CARRIER', $carrier->id);
            $success[] = array_sum($success) >= count($success) && $this->insertCarrierGroup($carrier);
            $success[] = array_sum($success) >= count($success) && $this->addToZones($carrier);
            $success[] = array_sum($success) >= count($success) && $this->addPriceRange($carrier);
            $success[] = array_sum($success) >= count($success) && $this->addWeightRange($carrier);
        } else {
            $success = 0;
        }
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @param PosCarrier $carrier
     * @return boolean
     */
    protected function insertCarrierGroup($carrier)
    {
        $success = array();
        $groups = PosGroup::getGroups(true);
        foreach ($groups as $group) {
            $carrier_group_data = array(
                'id_carrier' => (int) $carrier->id,
                'id_group' => (int) $group['id_group']
            );
            $success[] = Db::getInstance()->insert('carrier_group', $carrier_group_data);
        }
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @param PosCarrier $carrier
     * @return boolean
     */
    protected function addToZones($carrier)
    {
        $success = array();
        $zones = Zone::getZones();
        $range_weight = new RangeWeight();
        $range_price = new RangePrice();
        foreach ($zones as $zone) {
            $carrier_zone_data = array(
                'id_carrier' => (int) $carrier->id,
                'id_zone' => (int) $zone['id_zone']
            );
            $success[] = Db::getInstance()->insert('carrier_zone', $carrier_zone_data);

            $delivery_price_range = array(
                'id_carrier' => (int) $carrier->id,
                'id_range_price' => (int) $range_price->id,
                'id_range_weight' => null,
                'id_zone' => (int) $zone['id_zone'],
                'price' => '0'
            );
            $success[] = Db::getInstance()->insert('delivery', $delivery_price_range, true);

            $delivery_weight_range = array(
                'id_carrier' => (int) $carrier->id,
                'id_range_price' => null,
                'id_range_weight' => (int) $range_weight->id,
                'id_zone' => (int) $zone['id_zone'],
                'price' => '0'
            );
            $success[] = Db::getInstance()->insert('delivery', $delivery_weight_range, true);
        }
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @param PosCarrier $carrier
     * @return boolean
     */
    protected function addPriceRange($carrier)
    {
        $range_price = new RangePrice();
        $range_price->id_carrier = $carrier->id;
        $range_price->delimiter1 = '0';
        $range_price->delimiter2 = '10000';
        return $range_price->add();
    }

    /**
     *
     * @param PosCarrier $carrier
     * @return boolean
     */
    protected function addWeightRange($carrier)
    {
        $range_weight = new RangeWeight();
        $range_weight->id_carrier = $carrier->id;
        $range_weight->delimiter1 = '0';
        $range_weight->delimiter2 = '10000';
        return $range_weight->add();
    }
}
