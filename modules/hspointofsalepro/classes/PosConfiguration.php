<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * @since 2.3.7
 */
class PosConfiguration extends Configuration
{

    /**
     * @return array
     * <pre>
     * array(
     *   string => string // key => value
     * ...
     * )
     */
    public static function getSettings()
    {
        $general_settings = self::getGeneralSettings();
        $product_settings = self::getProductSettings();
        $customer_settings = self::getCustomerSettings();
        $invoice_settings = self::getInvoiceSettings();
        $receipt_settings = self::getReceiptSettings();
        $order_settings = self::getOrderSettings();
        $addon_settings = self::getAddonSettings();

        return array_merge($general_settings, $product_settings, $customer_settings, $invoice_settings, $receipt_settings, $order_settings, $addon_settings);
    }
    
    /**
     * @return array
     * <pre>
     * array(
     *   int => array(
     *      string // key => value
     *   )
     * ...
     * )
     */
    public static function getTrackingSettings()
    {
        $settings = self::getSettings();
        $ignore_keys = array(
            'POS_RECEIPT_TEMPLATE',
            'POS_MULTIPLE_LANGGUAGES',
            'POS_LICENSE_INFO'
        );
        $tracking_settings = array();
        foreach ($settings as $key => $value) {
            if (in_array($key, $ignore_keys)) {
                unset($settings[$key]);
            } else {
                $tracking_settings[] = array($key => $value);
            }
        }
        return $tracking_settings;
    }

    /**
     * @return array
     * <pre>
     * array(
     *   string => string // key => value
     * ...
     * )
     */
    public static function getGeneralSettings()
    {
        return array(
            'POS_COLLECTING_PAYMENT' => 1,
            'POS_DEFAULT_PAYMENT_ID' => 0,
            'POS_DEF_PRODUCT_DISCOUNT_TYPE' => PosConstants::DISCOUNT_TYPE_PERCENTAGE,
            'POS_DEF_ORDER_DISCOUNT_TYPE' => PosConstants::DISCOUNT_TYPE_PERCENTAGE,
            'POS_FREE_SHIPPING' => 1,
            'POS_REBUILD_SEARCH_INDEX' => 1,
            'POS_SHARE_YOUR_DATA' => 0,
            'POS_TIME_RUN_MIX_PANEL' => 0
        );
    }

    /**
     * @return array
     * <pre>
     * array(
     *   string => string // key => value
     * ...
     * )
     */
    public static function getProductSettings()
    {
        $product_settings = array(
            'POS_PRODUCT_OUT_OF_STOCK' => 0,
            'POS_VISIBILITY_EVERYWHERE' => 1,
            'POS_VISIBILITY_CATALOG_ONLY' => 0,
            'POS_VISIBILITY_SEARCH_ONLY' => 1,
            'POS_VISIBILITY_NOWHERE' => 0,
            'POS_AUTO_INDEXING' => 1,
            'POS_IMAGES_IN_SHOPPING_CART' => 0,
            'POS_PRODUCT_SEARCH_BY' => PosConstants::SEARCH_GENERAL,
            'POS_PRODUCT_ACTIVE' => 1,
            'POS_PRODUCT_INSTOCK' => 1
        );

        return array_merge($product_settings, self::getOutputProductSearchSettings(), self::getActiveProductSetting());
    }

    /**
     * @return array
     * <pre>
     * array(
     *   string => string // key => value
     * ...
     * )
     */
    public static function getOutputProductSearchSettings()
    {
        return array(
            'POS_SHOW_ID' => 1,
            'POS_SHOW_REFERENCE' => 1,
            'POS_SHOW_STOCK' => 1,
            'POS_SHOW_NAME' => 1,
        );
    }

    /**
     * @return array
     * <pre>
     * array(
     *   string => string // key => value
     * ...
     * )
     */
    public static function getCustomerSettings()
    {
        return array(
            'POS_ALLOW_GUEST_SEARCH' => 0,
            'POS_GUEST_CHECKOUT' => 1,
            'POS_SHOW_CUS_INFO_ON_RECEIPT' => 1,
            'POS_SHOW_WARNING_GUEST_CHECKOUT' => 0,
            'POS_EMAILING_ACCOUNT_CREATION' => 1,
            'POS_EMAILING_ORDER_COMPLETION_STANDARD' => 1,
            'POS_EMAILING_ORDER_COMPLETION_GUEST_CHECKOUT' => 0,
            'POS_ADDRESS_ALIAS' => PosConstants::ADDRESS_ALIAS
        );
    }

    /**
     * @return array
     * <pre>
     * array(
     *   string => string // key => value
     * ...
     * )
     */
    public static function getInvoiceSettings()
    {
        return array(
            'POS_PRESTASHOP_INVOICE' => 0,
            'POS_INVOICE_AUTO_PRINT' => 0,
            'POS_INVOICE_PAGE_SIZE' => PosConstants::PAGE_SIZE_A4,
            'POS_INVOICE_ORIENTATION' => '', // Empty means "Automatic"
            'POS_INVOICE_LOGO' => '',
            'POS_INVOICE_SHOW_SHOP_NAME' => 1,
            'POS_INVOICE_SHOW_EMPLOYEE_NAME' => 0,
            'POS_INVOICE_SHOW_SALESMAN_NAME' => 1,
            'POS_INVOICE_SHOW_EAN_JAN' => 0,
            'POS_INVOICE_SHOW_SIGNATURE' => 0,
            'POS_SEND_EMAIL_TO_CUSTOMER' => 1, // @todo:Move to outside of Invoice tab
            'POS_INVOICE_FOOTER_TEXT' => '',
        );
    }

    /**
     * @return array
     * <pre>
     * array(
     *   string => string // key => value
     * ...
     * )
     */
    public static function getReceiptSettings()
    {
        return array(
            'POS_RECEIPT_LOGO' => '',
            'POS_RECEIPT_FOOTER_TEXT' => '',
            'POS_RECEIPT_HEADER_TEXT' => '',
            'POS_RECEIPT_BILL_PRINTER_1' => '',
            'POS_RECEIPT_BILL_PRINTER_2' => '',
            'POS_PRINT_IN_PDF' => 0,
            'POS_RECEIPT_LOGO_MAX_WIDTH' => PosConstants::DEFAULT_RECEIPT_LOGO_MAX_WIDTH,
            'POS_RECEIPT_PAGE_SIZE' => PosConstants::DEFAULT_RECEIPT_PAGE_SIZE,
            'POS_RECEIPT_PRINTER_DPI' => PosConstants::DEFAULT_PRINTER_DPI,
            'POS_RECEIPT_TEMPLATE' => Tools::jsonEncode(self::getDefaultReceiptTemplate()),
        );
    }

    /**
     * @return array
     * <pre>
     * array(
     *   string => string // key => value
     * ...
     * )
     */
    public static function getOrderSettings()
    {
        return array(
            'POS_DEFAULT_STANDARD_ORDER_STATE' => PosOrderState::getFistOrderStateId(),
            'POS_SHOW_ORDERS_UNDER_PS_ORDERS' => 1,
            'POS_DEFAULT_RETURN_ORDER_STATE' => Configuration::get('PS_OS_REFUND')
        );
    }

    /**
     * @return array
     * <pre>
     * array(
     *   string => string // key => value
     * ...
     * )
     */
    public static function getAddonSettings()
    {
        return array(
            'POS_CUSTOM_PRODUCT_TIME_TO_LIVE' => PosConstants::CUSTOM_PRODUCT_TIME_TO_LIVE,
            'POS_PRODUCT_NAME_LENGTH' => PosConstants::PRODUCT_NAME_LENGTH,
            'POS_DELAY_TIME' => PosConstants::DELAY_TIME,
            'POS_ORDERHISTORY_ITEMS_PER_PAGE' => PosConstants::POS_ORDERHISTORY_ITEMS_PER_PAGE,
            'POS_COMMISSION_BASE' => PosConstants::GROSS_SALES,
            'POS_COMMISSION_STRUCTURE' => PosConstants::STRAIGHT,
            'POS_STRAIGHT_VALUE' => 0,
        );
    }

    /**
     * @return array
     * <pre>
     * array(
     *   string => string // key => value
     * ...
     * )
     */
    public static function getDefaultSettings()
    {
        $settings = self::getSettings();
        $old_setting = self::getMultiple(array_keys($settings));

        return array_merge($settings, array_diff($old_setting, array('')));
    }

    /**
     * Remove all configuration keys of module.
     *
     * @return bool
     */
    public static function removeSettings()
    {
        $keys_setting = array_keys(self::getSettings());
        $flag = true;
        foreach ($keys_setting as $key) {
            $flag &= self::deleteByName($key);
        }

        return $flag;
    }

    /**
     * @return string
     */
    public static function getModuleName()
    {
        return Configuration::get('POS_ROCKPOS_NAME') ? Configuration::get('POS_ROCKPOS_NAME') : 'hspointofsalepro';
    }

    /**
     * @param string $old_key
     * @param string $new_key
     *
     * @return bool
     */
    public static function rename($old_key, $new_key)
    {
        $success = array();
        $sql = 'UPDATE `' . _DB_PREFIX_ . 'configuration` SET `name` = \''.pSQL($new_key).'\' WHERE `name` = \''.pSQL($old_key).'\'';
        $success[] = Db::getInstance()->execute($sql);

        return array_sum($success) >= count($success);
    }

    /**
     * @param array $old_keys
     * <pre>
     * array(
     *   string => string // key => value
     * ...
     * )
     * @param array $new_keys
     * <pre>
     * array(
     *   string => string // key => value
     * ...
     * )
     * @return bool
     */
    public static function renameMultiple(array $old_keys, array $new_keys)
    {
        $final_result = false;
        if (count($old_keys) == count($new_keys)) {
            $success = array();
            foreach ($old_keys as $index => $key) {
                $success[] = self::rename($key, $new_keys[$index]);
            }
            $final_result = array_sum($success) >= count($success);
        }

        return $final_result;
    }

    /**
     * @return array
     * <pre>
     * array(
     *   string => string // key => value
     * ...
     * )
     */
    public static function getProductVisibilities()
    {
        return array(
            'POS_VISIBILITY_EVERYWHERE' => 'both',
            'POS_VISIBILITY_SEARCH_ONLY' => 'search',
            'POS_VISIBILITY_CATALOG_ONLY' => 'catalog',
            'POS_VISIBILITY_NOWHERE' => 'none',
        );
    }

    /**
     * @return array
     * <pre>
     * array(
     *   int => string
     * ...
     * )
     */
    public static function getImageFields()
    {
        return array(
            'POS_RECEIPT_LOGO',
        );
    }

    /**
     * @return array
     * <pre>
     * array(
     *   string => string // key => value
     * ...
     * )
     */
    public static function getActiveProductSetting()
    {
        return array(
            'POS_PRODUCT_INACTIVE' => 0,
        );
    }

    public static function getTextFields()
    {
        return array(
            'POS_RECEIPT_HEADER_TEXT',
            'POS_RECEIPT_FOOTER_TEXT',
        );
    }

    protected static function getDefaultReceiptTemplate()
    {
        return
                array(
                    'headerReceipt' => array(
                        'logo',
                        'shopName',
                        'shopAddress',
                        'websiteUrl',
                        'phone',
                        'orderReference',
                        'lineBreak_2',
                        'divider_3',
                        'lineBreak_4',
                    ),
                    'productReceipt' => array(
                        'unitPrice',
                        'name',
                        'reference',
                        'upc',
                        'combination',
                    ),
                    'summaryReceipt' => array(
                        'subtotal',
                        'tax',
                        'shipping',
                        'totalProductDiscount',
                        'totalOrderDiscount',
                        'total',
                        'totalItems',
                        'payment',
                    ),
                    'footerReceipt' => array(
                        'note',
                        'signature',
                        'referenceBarcode',
                    )
        );
    }
}
