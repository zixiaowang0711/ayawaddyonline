<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Custom Customer for Point of Sale
 * - searchByName(): Allow searching for guest customers (beside standard custommers).
 */
class PosCustomer extends Customer
{

    /**
     * Override
     * 1. return null for all fields which have value = N/A.
     * 2. firstname is genericName
     *
     * @param int $id
     */
    public function __construct($id = null)
    {
        self::$definition['fields']['firstname'] = array(
            'type' => self::TYPE_STRING,
            'validate' => 'isGenericName',
            'required' => true,
            'size' => 32
        );
        parent::__construct($id);
        foreach (self::$definition['fields'] as $field => $data) {
            if ($this->$field == PosConstants::NOT_AVAILABLE) {
                $this->$field = null;
            }
        }
    }

    /**
     * Light back office search for customers (and guests).
     *
     * @param string $keyword
     * @param int    $limit   How many customers to be returned
     * @param array $id_employees
     *
     * @return array
     *               <pre>
     *               array(
     *               int => array(
     *               'id_customer' => int,
     *               'firstname' => string,
     *               'lastname' => string,
     *               'email' => string
     *               )
     *               ...
     */
    public static function search($keyword, $limit = null, array $id_employees = array())
    {
        $db_query = new DbQuery();
        $db_query->select('c.`id_customer`');
        $db_query->select('NULLIF(c.`firstname`,"' . pSQL(PosConstants::NOT_AVAILABLE) . '") AS `firstname`');
        $db_query->select('NULLIF(c.`lastname`,"' . pSQL(PosConstants::NOT_AVAILABLE) . '") AS `lastname`');
        $db_query->select('NULLIF(c.`email`,"' . pSQL(PosConstants::NOT_AVAILABLE) . '") AS `email`');
        $db_query->select('a.`phone`');
        $db_query->select('a.`phone_mobile`');
        $db_query->select('c.`company`');
        $db_query->from('customer', 'c');
        $db_query->leftJoin('address', 'a', 'a.`id_customer` = c.`id_customer`');
        $db_query->groupBy('c.`id_customer`')->orderBy('c.`firstname` ASC');
        if ($limit) {
            $db_query->limit($limit);
        }
        $db_query->where('c.`active` = 1');
        $db_query->where('a.`active` = 1 OR a.`active` IS NULL');
        $db_query->where(Configuration::get('POS_ALLOW_GUEST_SEARCH') ? null : 'c.`is_guest` = 0');
        $db_query->where('1 ' . Shop::addSqlRestriction(Shop::SHARE_CUSTOMER));
        $db_query->where('c.`id_customer` != ' . (int) self::getDefaultCustomer()->id);
        if (!empty($id_employees)) {
            $db_query->where('c.`id_employee` IN(' . implode(',', $id_employees) . ')');
        }
        $sanitized_keyword = pSQL($keyword);
        $where = array();
        foreach (Customer::$definition['fields'] as $field => $field_definition) {
            $where[] = self::isSearchField($field_definition) ? "c.`$field` LIKE '%$sanitized_keyword%'" : '';
        }
        foreach (Address::$definition['fields'] as $field => $field_definition) {
            $where[] = self::isSearchField($field_definition) ? "a.`$field` LIKE '%$sanitized_keyword%'" : '';
        }
        $db_query->where(implode(' OR ', array_diff($where, array(''))));
        return Db::getInstance()->executeS($db_query);
    }

    /**
     * Check if a field is available for search.
     *
     * @param array $field_definition Definition of a field of an ObjectModel class
     *                                <pre>
     *                                array(
     *                                'type' => int,
     *                                'copy_post' => string (optional)
     *                                'validate' => string (optional)
     *                                ...
     *                                )
     *
     * @return bool
     */
    protected static function isSearchField(array $field_definition)
    {
        return (
                $field_definition['type'] == self::TYPE_STRING &&
                (!isset($field_definition['copy_post']) || (isset($field_definition['copy_post']) && $field_definition['copy_post'])) &&
                (!isset($field_definition['validate']) || (isset($field_definition['validate']) && !in_array($field_definition['validate'], array('isMd5', 'isPasswd'))))
                );
    }

    /**
     * Create  dummy customer for guest checkout.
     *
     * @param int $id_shop
     *
     * @return bool
     */
    public static function createDummyCustomer($id_employee = null, $id_shop = null, $id_shop_group = null)
    {
        $success = array();
        $id_customer = self::getDefaultCustomerIds($id_employee, $id_shop);
        $customer = $id_customer ? new self($id_customer) : new self();
        if (!Validate::isLoadedObject($customer)) {
            $customer->email = Configuration::get('PS_SHOP_EMAIL', null, null, $id_shop);
            $customer->firstname = 'POS';
            $customer->lastname = 'Guest';
            $customer->passwd = Tools::encrypt(Tools::passwdGen());
            $customer->id_gender = 1;
            $customer->active = 1;
            $customer->id_shop = $id_shop;
            $customer->id_shop_group = $id_shop_group;

            if ($customer->add()) {
                $id_customers = self::getDefaultCustomerIds();
                $id_customers[-1] = (int) $customer->id;
                if (self::setDefaultCustomerIds($id_customers, $id_shop)) {
                    $success[] = PosAddress::createDummyAddress($customer, $id_shop);
                    $success[] = PosGroup::addPosCustomerGroup((int) $customer->id);
                }
            }
        }
        return array_sum($success) >= count($success);
    }

    /**
     * @param int $id_employee
     * @param int $id_shop
     * @return bool
     */
    public function isDefaultCustomer($id_employee = null, $id_shop = null)
    {
        return ((int) $this->id === (int) self::getDefaultCustomerIds($id_employee, $id_shop));
    }

    /**
     * Get default customer.
     * @param int $id_shop
     * @return Customer
     */
    public static function getDefaultCustomer($id_employee = null, $id_shop = null)
    {
        if (is_null($id_shop)) {
            $context = Context::getContext();
            $id_shop = $context->shop->id;
        }
        $id_customer = self::getDefaultCustomerIds($id_employee, $id_shop);
        if ($id_customer) {
            $customer = new self($id_customer);
        } else {
            $customer = new self();
        }

        return $customer;
    }

    /**
     * @param Customer $customer
     *
     * @return string
     */
    public static function getPhoneNumber(Customer $customer)
    {
        $addresses = $customer->getAddresses(Context::getContext()->language->id);
        $phone = '';

        if (!empty($addresses)) {
            foreach ($addresses as $address) {
                if (!empty($address['phone']) && $address['phone'] != PosConstants::DEFAULT_PHONE_NUMBER) {
                    $phone = $address['phone'];
                } elseif (!empty($address['phone_mobile']) && $address['phone_mobile'] != PosConstants::DEFAULT_PHONE_NUMBER) {
                    $phone = $address['phone_mobile'];
                }
            }
        }

        return $phone;
    }

    /**
     * @return bool
     */
    public function addPosCustomerGroup()
    {
        $flag = true;
        $pos_customer_id_group = (int) Configuration::get('POS_CUSTOMER_ID_GROUP');
        $id_groups = $this->getGroups();
        if ($pos_customer_id_group && !in_array($pos_customer_id_group, $id_groups)) {
            $flag = (bool) $this->addGroups(array($pos_customer_id_group));
        }

        return $flag;
    }

    /**
     * @param bool $autodate
     * @param bool $null_values
     *
     * @return bool
     */
    public function add($autodate = true, $null_values = true)
    {
        $default_instance = self::generateDefaultInstance();
        foreach (get_object_vars($default_instance) as $field => $value) {
            if (empty($this->$field)) {
                $this->$field = $value;
            }
        }

        return parent::add($autodate, $null_values);
    }

    /**
     * @return \stdClass
     */
    public static function generateDefaultInstance()
    {
        $customer = new stdClass();
        foreach (self::$definition['fields'] as $field => $definition) {
            if (!isset($definition['required']) || (isset($definition['required']) && !$definition['required'])) {
                continue;
            }
            switch ($definition['validate']) {
                case 'isEmail':
                    $customer->$field = Configuration::get('PS_SHOP_EMAIL');
                    break;
                case 'isPasswd':
                    $customer->$field = Tools::encrypt(Tools::passwdGen());
                    break;
                default:
                    $customer->$field = PosConstants::NOT_AVAILABLE;
                    break;
            }
        }

        return $customer;
    }

    /**
     * This is combined from 2 functions: ObjectModel::validateFields() and ObjectModel::validateFieldsLang().
     *
     * @return array
     *               <pre>
     *               array(
     *               string,
     *               string,
     *               ...
     *               )
     */
    public function validate()
    {
        $error_messages = array();
        foreach ($this->def['fields'] as $field => $data) {
            if (empty($data['lang'])) {
                // see ObjectModel::validateFields()
                if (is_array($this->update_fields) && empty($this->update_fields[$field]) && isset($this->def['fields'][$field]['shop']) && $this->def['fields'][$field]['shop']) {
                    continue;
                }
                $error_messages[] = $this->validateField($field, $this->$field);
            } else {
                // see ObjectModel::validateFieldsLang()
                $this->field = is_array($this->field) ? $this->field : array($this->id_lang => $this->field);
                if (!isset($this->field[Configuration::get('PS_LANG_DEFAULT')])) {
                    $this->field[Configuration::get('PS_LANG_DEFAULT')] = '';
                }
                foreach ($this->field as $id_lang => $value) {
                    if (is_array($this->update_fields) && empty($this->update_fields[$field][$id_lang])) {
                        continue;
                    }
                    $error_messages[] = $this->validateField($field, $value, $id_lang);
                }
            }
        }

        return array_diff($error_messages, array(true)); // "true" means "no error"
    }

    /**
     * Override: remove some unnecessary properties.
     *
     * @param int $id_lang
     *
     * @return array
     *               <pre>
     *               array(
     *               array(
     *               'id_address' => int,
     *               'id_country' => int,
     *               'id_state' => int,
     *               'alias' => string,
     *               'company' => string,
     *               'lastname' => string,
     *               'firstname' => string,
     *               'address1' => string,
     *               'address2' => string,
     *               'postcode' => string,
     *               'city' => string,
     *               'phone' => string,
     *               'phone_mobile' => string,
     *               'vat_number' => string,
     *               'dni' => string,
     *               )
     *               )
     *               </pre>
     */
    public function getAddresses($id_lang = null)
    {
        $customer_addresses_query = new DbQuery();
        $customer_addresses_query->select('a.`id_address`');
        $customer_addresses_query->select('a.`id_country`');
        $customer_addresses_query->select('a.`id_state`');
        $customer_addresses_query->select('a.`alias`');
        $customer_addresses_query->select('a.`company`');
        $customer_addresses_query->select('NULLIF(a.`lastname`,"' . pSQL(PosConstants::NOT_AVAILABLE) . '") AS `lastname`');
        $customer_addresses_query->select('NULLIF(a.`firstname`,"' . pSQL(PosConstants::NOT_AVAILABLE) . '") AS `firstname`');
        $customer_addresses_query->select('NULLIF(a.`address1`,"' . pSQL(PosConstants::NOT_AVAILABLE) . '") AS `address1`');
        $customer_addresses_query->select('a.`address2`');
        $customer_addresses_query->select('a.`postcode`');
        $customer_addresses_query->select('NULLIF(a.`city`,"' . pSQL(PosConstants::NOT_AVAILABLE) . '") AS `city`');
        $customer_addresses_query->select('a.`phone`');
        $customer_addresses_query->select('a.`phone_mobile`');
        $customer_addresses_query->select('a.`vat_number`');
        $customer_addresses_query->select('a.`dni`');
        $customer_addresses_query->select('a.`other`');
        $customer_addresses_query->from('address', 'a');
        $customer_addresses_query->where('a.`id_customer` = ' . (int) $this->id);
        $customer_addresses_query->where('a.`deleted` = 0');

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($customer_addresses_query);
    }

    /**
     * @param string $default
     *
     * @return string
     */
    public function getDisplayName($default = '')
    {
        $names = array();
        if (!empty($this->firstname) && $this->firstname != PosConstants::NOT_AVAILABLE) {
            $names[] = $this->firstname;
        }
        if (!empty($this->lastname) && $this->lastname != PosConstants::NOT_AVAILABLE) {
            $names[] = $this->lastname;
        }

        return $names ? implode(' ', $names) : ($default ? $default : $this->email);
    }

    /**
     * The price display method (w/o tax which is set under customer group).
     *
     * @return int //PS_TAX_EXC or PS_TAX_INC
     */
    public function getPriceDisplayMethod()
    {
        return Product::getTaxCalculationMethod($this->id);
    }

    /**
     *
     * @return array
     * <pre>
     * array(
     *  id => int,
     *  firstname => string,
     *  lastname => string,
     *  email => string,
     *  edit_link => string,
     * )
     */
    public static function getGuestCustomer()
    {
        $guest_customer = array();
        $context = Context::getContext();
        $default_customer_id = self::getDefaultCustomerIds($context->cookie->id_employee, $context->shop->id);
        $customer = new self((int) $default_customer_id);
        $guest_customer['id'] = $customer->id;
        $guest_customer['firstname'] = $customer->firstname;
        $guest_customer['lastname'] = $customer->lastname;
        $guest_customer['email'] = $customer->email;
        $guest_customer['edit_link'] = $context->link->getAdminLink('AdminCustomers') . '&updatecustomer&id_customer=' . $customer->id;
        return $guest_customer;
    }

    /**
     *
     * @return PosCustomer
     */
    public static function getSampleCustomer()
    {
        $query = new DbQuery();
        $query->select('MAX(`id_customer`) AS id_customer');
        $query->from('customer');
        $query->where('`active` = 1');
        $id_customer = Db::getInstance()->getValue($query);
        $customer = new self($id_customer);
        return $customer;
    }

    /**
     *
     * @param int $id_employee
     * @param int $id_shop
     * @return array || int
     * <pre>
     * array(
     *  int => int,
     *  ...
     * )
     */
    public static function getDefaultCustomerIds($id_employee = null, $id_shop = null)
    {
        $default_id_customers = Tools::jsonDecode(Configuration::get('POS_DEFAULT_ID_CUSTOMERS', null, null, $id_shop), true);
        if (is_null($id_employee)) {
            return $default_id_customers;
        } else if ($id_employee && isset($default_id_customers[$id_employee])) {
            return $default_id_customers[$id_employee];
        } else if (isset($default_id_customers[-1])) {
            return $default_id_customers[-1];
        } else {
            return 0;
        }
    }

    /**
     *
     * @param array $default_customer_ids
     * @param int $id_shop
     * @return boolean
     */
    public static function setDefaultCustomerIds(array $default_customer_ids = array(), $id_shop = null)
    {
        return Configuration::updateValue('POS_DEFAULT_ID_CUSTOMERS', Tools::jsonEncode($default_customer_ids), false, null, $id_shop);
    }

    /**
     *
     * @param int $id_employee
     * @return boolean
     */
    public function updateEmployee($id_employee)
    {
        $sql = 'UPDATE `' . _DB_PREFIX_ . 'customer` SET `id_employee` = ' . (int) $id_employee . ' WHERE `id_customer` = ' . (int) $this->id;
        return Db::getInstance()->execute($sql);
    }
}
