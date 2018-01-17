<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * @todo: This class should not exist. Instead, let's move to a meaningful model.
 */
class PosReceipt extends Configuration
{
    /**
     * Get address : address 1, city name.
     *
     * @param int $id_shop
     * @param bool $break_line
     *
     * @return type
     */
    public static function getAddress($id_shop, $break_line = false)
    {
        $address1 = self::get('POS_RECEIPT_SHOW_ADDRESS') ? self::get('PS_SHOP_ADDR1', null, null, $id_shop) : '';
        $city = self::get('POS_RECEIPT_SHOW_CITY') ? self::get('PS_SHOP_CITY', null, null, $id_shop) : '';
        $address = array();
        if (!empty($address1)) {
            $address[] = $address1;
        }
        if (!empty($city)) {
            $address[] = $city;
        }
        $state = self::get('POS_RECEIPT_SHOW_STATE') ? State::getNameById(self::get('PS_SHOP_STATE_ID', null, null, $id_shop)) : '';
        $zipcode = self::get('POS_RECEIPT_SHOW_ZIPCODE') ? self::get('PS_SHOP_CODE', null, null, $id_shop) : '';
        if (!empty($state)) {
            $address[] = $state;
        }
        if (!empty($zipcode)) {
            $address[] = $zipcode;
        }

        return implode($break_line ? '<br/>' : ', ', $address);
    }

    /**
     * @param int $id_shop
     *
     * @return string
     */
    public static function getPhoneNumber($id_shop)
    {
        return self::get('POS_RECEIPT_SHOW_PHONE') && self::get('PS_SHOP_PHONE', null, null, $id_shop) ? self::get('PS_SHOP_PHONE', null, null, $id_shop) : '';
    }

    /**
     * @param int $id_shop
     *
     * @return string
     */
    public static function getFaxNumber($id_shop)
    {
        return self::get('POS_RECEIPT_SHOW_FAX') && self::get('PS_SHOP_FAX', null, null, $id_shop) ? self::get('PS_SHOP_FAX', null, null, $id_shop) : '';
    }

    /**
     * @param int $id_shop
     *
     * @return string
     */
    public static function getTaxCodeNumber($id_shop)
    {
        return self::get('POS_RECEIPT_SHOW_REG_NUMBER') && self::get('PS_SHOP_DETAILS', null, null, $id_shop) ? self::get('PS_SHOP_DETAILS', null, null, $id_shop) : '';
    }

    /**
     * @param int $id_shop
     *
     * @return bool
     */
    public static function getShopName($id_shop)
    {
        return (bool) self::get('PS_SHOP_NAME', null, null, $id_shop);
    }

    /**
     * @param int $id_shop
     *
     * @return string
     */
    public static function getShopUrl($id_shop)
    {
        $shop_url = '';
        if (self::get('POS_RECEIPT_SHOW_WEBSITE_URL')) {
            $shop = new PosShop((int) $id_shop);
            $shop_url = Validate::isLoadedObject($shop) ? $shop->domain : '';
        }

        return $shop_url;
    }

    /**
     * @return string
     */
    public static function showLogo()
    {
        return self::get('POS_RECEIPT_SHOW_LOGO');
    }

    /**
     * @return string
     */
    public static function getLogoFileName()
    {
        $logo_file_name = '';
        if (Configuration::get('POS_RECEIPT_LOGO') && file_exists(_PS_IMG_DIR_ . Configuration::get('POS_RECEIPT_LOGO'))) {
            $logo_file_name = Configuration::get('POS_RECEIPT_LOGO');
        } elseif (Configuration::get('PS_LOGO') && file_exists(_PS_IMG_DIR_ . Configuration::get('PS_LOGO'))) {
            $logo_file_name = Configuration::get('PS_LOGO');
        }

        return $logo_file_name;
    }

    /**
     *
     * @param array $product
     * @return array
     * <pre>
     * array(
     *  string,
     *  string,
     *  ...
     * )
     */
    public static function getProductMetaData(array $product)
    {
        $meta_data = array();
        $meta_data[] = self::truncate($product['name']);
        if (Configuration::get('POS_RECEIPT_SHOW_COMBINATION') && !empty($product['combination'])) {
            $meta_data[] = self::truncate($product['combination']);
        }
        if ((int) Configuration::get('POS_RECEIPT_SHOW_PRODUCT_REF') && !empty($product['product_reference'])) {
            $meta_data[] = self::truncate($product['product_reference']);
        }
        if ((int) Configuration::get('POS_RECEIPT_SHOW_EAN_JAN') && !empty($product['product_ean13'])) {
            $meta_data[] = self::truncate($product['product_ean13']); // product_ean13 => combination's, ean13 => product's
        }
        if ((int) Configuration::get('POS_RECEIPT_SHOW_UPC') && !empty($product['product_upc'])) {
            $meta_data[] = self::truncate($product['product_upc']);
        }
        if ((int) Configuration::get('POS_RECEIPT_SHOW_MNAME') && !empty($product['manufacturer_name'])) {
            $meta_data[] = self::truncate($product['manufacturer_name']);
        }
        return $meta_data;
    }

    /**
     *
     * @param string $string
     * @return string
     */
    public static function truncate($string)
    {
        $trim_string = trim($string);
        return Tools::strlen($trim_string) <= Configuration::get('POS_RECEIPT_PRODUCT_LENGTH') + 3 ? $trim_string : (Tools::substr($trim_string, 0, Configuration::get('POS_RECEIPT_PRODUCT_LENGTH')) . '...');
    }

    /**
     *
     * @param array $product
     * @return array
     * <pre>
     * array(
     *  string,
     *  string,
     *  ...
     * )
     */
    public static function getProductPricesToShow(array $product, $id_currency, $use_tax = false)
    {
        $prices = array();
        $with_currency = (bool) Configuration::get('POS_RECEIPT_PRODUCT_CURRENCY');
        if (Configuration::get('POS_RECEIPT_SHOW_UNIT_PRICE')) {
            if ($use_tax) {
                $prices[] = $with_currency ? Tools::displayPrice(Tools::displayPrice($product['product_price_tax_incl'], (int) $id_currency)) : sprintf('%.2f', $product['product_price_tax_incl']);
            } else {
                $prices[] = $with_currency ? Tools::displayPrice(Tools::displayPrice($product['product_price_tax_excl'], (int) $id_currency)) : sprintf('%.2f', $product['product_price_tax_excl']);
            }
        }
        if (Configuration::get('POS_RECEIPT_SHOW_PROD_DISCOUNT')) {
            if (!empty($product['reduction_amount']) && $product['reduction_amount'] > 0) {
                $prices[] = $with_currency ? Tools::displayPrice($product['reduction_amount']) : sprintf('%.2f', $product['reduction_amount']);
            } elseif (!empty($product['reduction_percent']) && $product['reduction_percent'] > 0) {
                $prices[] = (float) $product['reduction_percent'] . '%';
            }
        }
        return $prices;
    }

    /**
     *
     * @return string
     */
    public static function getCashierInfo($employee)
    {
        $cashier_info = array();
        try {
            $configuration = array_filter(Configuration::getMultiple(array(
                        'POS_RECEIPT_SHOW_EMPLOYEE_ID',
                        'POS_RECEIPT_SHOW_EMPLOYEE_NAME'
            )));
            if (empty($configuration)) {
                throw new Exception();
            }
            if (isset($configuration['POS_RECEIPT_SHOW_EMPLOYEE_ID'])) {
                $cashier_info[] = $employee->id;
            }
            if (isset($configuration['POS_RECEIPT_SHOW_EMPLOYEE_NAME'])) {
                $cashier_info[] = implode(' ', array_filter(array($employee->firstname, $employee->lastname)));
            }
        } catch (Exception $exception) {
            // do nothing!
        }
        return implode('/', $cashier_info);
    }

    /**
     *
     * @param string $order_reference
     * @return array()
     * <pre>
     * array(
     *  int => string,
     *  int => string
     * )
     */
    public static function getSalesmanInfo($order_reference)
    {
        $salesman_info = array();
        if (PosConfiguration::get('POS_INVOICE_SHOW_SALESMAN_NAME')) {
            $salesman = PosSalesCommission::getEmployee($order_reference);
            if (Validate::isLoadedObject($salesman)) {
                $salesman_info[] = $salesman->firstname;
                $salesman_info[] = $salesman->lastname;
            }
        }
        return $salesman_info;
    }
    
    /**
     *
     * @return int
     */
    public static function getLogoWidth()
    {
        return Tools::ps_round(((int) str_replace('K', '', Configuration::get('POS_RECEIPT_PAGE_SIZE')) * Configuration::get('POS_RECEIPT_PRINTER_DPI')) / 25.4 * Configuration::get('POS_RECEIPT_LOGO_MAX_WIDTH') / 100, 0);
    }

    /**
     *
     * @return int
     */
    public static function getPdfPageSize()
    {
        return str_replace('K', '', Configuration::get('POS_RECEIPT_PAGE_SIZE')) * 72 / 25.4;
    }
}
