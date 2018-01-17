<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * PosConstants for Point of Sale.
 */
class PosConstants
{
    const APPEND_ADMIN_TAB  = 'AdminParentOrders';
    const POS_FILTER_PRODUCT_LIMIT = 100;
    const PRODUCT_NAME_LENGTH = 10;
    const NOT_AVAILABLE = 'N/A';
    const DEFAULT_POSTCODE = '00000';
    const DEFAULT_PHONE_NUMBER = '0000000000';
    const DISCOUNT_TYPE_PERCENTAGE = 'percentage';
    const DISCOUNT_TYPE_AMOUNT = 'amount';
    const DISCOUNT_TYPE_VOUCHER = 'voucher';
    const DEFAULT_ATTRIBUTE_IMAGE_TYPE = 'small';
    const NOT_ENOUGH_PRODUCT = 1;
    const MIN_QUANTITY = -1;
    const DISCOUNT_DURATION_PRODUCT = 864000; //10 days x 24 hours x 60 minutes x 60 seconds
    const DISCOUNT_DURATION_ORDER = 864000; //10 days x 24 hours x 60 minutes x 60 seconds
    const PRODUCT_PRICE_TYPE = 'price';
    const ADDRESS_ALIAS = 'RockPOS';
    const IMAGE_SUFFIXE = '_small';
    const SEARCH_GENERAL = 'general';
    const SEARCH_BY_ID = 'id';
    const SEARCH_BY_NAME = 'name';
    const SEARCH_BY_REFERENCE = 'reference';
    const SEARCH_BY_BARCODE = 'barcode';
    const SEARCH_BY_SHORT_DESCRIPTION = 'short_description';
    const SEARCH_BY_DESCRIPTION = 'description';
    const SEARCH_BY_CATEGORY = 'category';
    const SEARCH_BY_ATTRIBUTE = 'attribute';
    const SEARCH_BY_FEATURE = 'feature';
    const SEARCH_BY_MANUFACTURER = 'manufacturer';
    const SEARCH_BY_TAG = 'tag';
    const CART_RULE_QTY = 1000000;
    const CUSTOM_PRODUCT_TIME_TO_LIVE = 10;
    const PAGE_SIZE_A4 = 'A4';
    const INVOICE_LOGO_PREFIX = 'pos_invoice_logo';
    const INVOICE_LOGO_MAX_HEIGHT = 70; // In pixel
    const PAGE_SIZE_A5 = 'A5';
    const PAGE_SIZE_LETTER = 'LETTER';
    const PAGE_SIZE_K80 = 'K80';
    const BEEP_FILE = 'beep.mp3';
    const DELAY_TIME = 24;
    const POS_ORDERHISTORY_ITEMS_PER_PAGE = 24;
    const GROSS_SALES = 'gross_sales';
    const STRAIGHT = 'straight';
    const POS_REMOVE_TABLES_AND_SETTINGS = 0;
    const POS_CANCEL_ORDER = 'POSCANCEL';
    const RECEIPT_LOGO_PREFIX = 'pos_receipt_logo';
    const RECEIPT_LOGO_MAX_WIDTH = 320; // In pixel
    const DEFAULT_RECEIPT_LOGO_MAX_WIDTH = 80;
    const DEFAULT_RECEIPT_PAGE_SIZE = 'K80';
    const DEFAULT_PRINTER_DPI = 203;
    const RECEIPT_PREFIX = 'SR_';
    const TEMPLATE_SALES_SUMMARY = 'SalesSummary';
    const ORIENTATION_PORTRAIT = 'P';
    const LINK_CONTACT_US = 'https://rockpos.com/contact-us?utm_source=addon';
    const LOYALTY_REWARD = 'loyalty reward';
    const LOYALTY_VOUCHER_CODE_PREFIX = 'FID-LAT';
    const REWARD_VOUCHER_CODE_PREFIX = 'FID-AIORW';
    const DEFAULT_RETURN_ORDER_STATE = 7;
}
