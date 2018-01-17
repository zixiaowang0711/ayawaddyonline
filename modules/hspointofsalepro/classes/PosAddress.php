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
class PosAddress extends Address
{

    /**
     * Override: change firstname validate from isName to isGenericName.
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
    }

    /**
     * Create  dummy address for customer.
     *
     * @return bool
     */

    /**
     *
     * @param PosCustomer $customer
     * @param int $id_shop
     * @param string $phone
     * @return boolean
     */
    public static function createDummyAddress(PosCustomer $customer, $id_shop = null, $phone = null)
    {
        if (!Validate::isLoadedObject($customer)) {
            return false;
        }
        $address = new self();
        $address->id_customer = (int) $customer->id;
        $id_country = (int) Configuration::get('PS_SHOP_COUNTRY_ID', null, null, $id_shop) ? Configuration::get('PS_SHOP_COUNTRY_ID', null, null, $id_shop) : Configuration::get('PS_SHOP_COUNTRY_ID');
        $country = new Country($id_country);
        if (!$country->active) {
            $id_country = Configuration::get('PS_COUNTRY_DEFAULT');
        }
        $address->id_country = $id_country;
        if (Country::containsStates($id_country)) {
            $address->id_state = Configuration::get('PS_SHOP_STATE_ID', null, null, $id_shop) ? Configuration::get('PS_SHOP_STATE_ID', null, null, $id_shop) : Configuration::get('PS_SHOP_STATE_ID');
        }
        $address->company = Configuration::get('PS_SHOP_NAME', null, null, $id_shop) ? Configuration::get('PS_SHOP_NAME', null, null, $id_shop) : Configuration::get('PS_SHOP_NAME');
        $address->address1 = Configuration::get('PS_SHOP_ADDR1', null, null, $id_shop) ? Configuration::get('PS_SHOP_ADDR1', null, null, $id_shop) : Configuration::get('PS_SHOP_ADDR1');
        $address->address2 = Configuration::get('PS_SHOP_ADDR2', null, null, $id_shop) ? Configuration::get('PS_SHOP_ADDR2', null, null, $id_shop) : Configuration::get('PS_SHOP_ADDR2');
        $address->city = Configuration::get('PS_SHOP_CITY', null, null, $id_shop) ? Configuration::get('PS_SHOP_CITY', null, null, $id_shop) : Configuration::get('PS_SHOP_CITY');
        
        $address->postcode = Configuration::get('PS_SHOP_CODE', null, null, $id_shop) ? Configuration::get('PS_SHOP_CODE', null, null, $id_shop) : Configuration::get('PS_SHOP_CODE');
        if (empty($phone)) {
            $phone = Configuration::get('PS_SHOP_PHONE', null, null, $id_shop) ? Configuration::get('PS_SHOP_PHONE', null, null, $id_shop) : Configuration::get('PS_SHOP_PHONE');
        }
        $address->phone = $phone;
        $address->firstname = $customer->firstname;
        $address->lastname = $customer->lastname;
        $address->alias = Configuration::get('POS_ADDRESS_ALIAS');
        return $address->add();
    }

    /**
     * @param bool $autodate
     * @param bool $null_values
     *
     * @return bool
     */
    public function add($autodate = true, $null_values = false)
    {
        $this->assignDefaultValues();

        return parent::add($autodate, $null_values);
    }

    /**
     * @param bool $null_values
     *
     * @return bool
     */
    public function update($null_values = false)
    {
        $this->assignDefaultValues();

        return parent::update($null_values);
    }

    protected function assignDefaultValues()
    {
        if (empty($this->alias)) {
            $controller = Context::getContext()->controller;
            $module = isset($controller->module) ? $controller->module : null;
            $alias_prefix = $module ? $module->displayName : $controller->php_self;
            $this->alias = $alias_prefix . '_' . Tools::passwdGen();
        }

        $default_instance = self::generateDefaultInstance($this->id_country);
        foreach (get_object_vars($default_instance) as $field => $value) {
            if (empty($this->$field)) {
                $this->$field = $value;
            }
        }
    }

    /**
     * @param int $id_country
     *
     * @return \stdClass
     */
    public static function generateDefaultInstance($id_country = null)
    {
        $address = new stdClass();
        $address->id_country = !empty($id_country) ? $id_country : Configuration::get('PS_COUNTRY_DEFAULT');
        $address_fields_required = PosAddressFormat::getFieldsRequired();
        foreach ($address_fields_required as $required_field) {
            if (!isset(self::$definition['fields'][$required_field])) {
                continue;
            }
            switch (self::$definition['fields'][$required_field]['validate']) {
                case 'isPhoneNumber':
                    $address->$required_field = PosConstants::DEFAULT_PHONE_NUMBER;
                    break;
                case 'isPostCode':
                    $address->$required_field = PosCountry::generateDefaultZipCode($address->id_country);
                    break;
                default:
                    $address->$required_field = PosConstants::NOT_AVAILABLE;
                    break;
            }
        }

        return $address;
    }

    /**
     *
     * @return Address
     */
    public static function getSampleAddress()
    {
        $query = new DbQuery();
        $query->select('MAX(`id_address`) AS id_address');
        $query->from('address');
        $query->where('`active` = 1');
        $id_address = Db::getInstance()->getValue($query);
        $address = new self($id_address);
        return $address;
    }
}
