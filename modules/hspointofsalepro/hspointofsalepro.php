<?php
/**
 * RockPOS - Point of Sale for PrestaShop
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_ROCKPOS_DIR_')) {
    define('_ROCKPOS_DIR_', realpath(dirname(__FILE__)));
}

if (!defined('_ROCKPOS_VERSION_')) {
    // Don't forget to update the version in _construct() too.
    define('_ROCKPOS_VERSION_', '4.1.5'); // this needs to go ahead of HsAutoload.
}

if (!class_exists('HsAutoload')) {
    require_once dirname(__FILE__) . '/classes/hs/HsAutoload.php';
}

if (!function_exists('p')) {

    function p($data)
    {
        echo '<pre>';
        print_r($data);
    }

}

// https://stackoverflow.com/questions/27422640/alternate-to-array-column
if (!function_exists('array_column')) {

    function array_column(array $array, $columnKey, $indexKey = null)
    {
        $result = array();
        foreach ($array as $subArray) {
            if (!is_array($subArray)) {
                continue;
            } elseif (is_null($indexKey) && array_key_exists($columnKey, $subArray)) {
                $result[] = $subArray[$columnKey];
            } elseif (array_key_exists($indexKey, $subArray)) {
                if (is_null($columnKey)) {
                    $result[$subArray[$indexKey]] = $subArray;
                } elseif (array_key_exists($columnKey, $subArray)) {
                    $result[$subArray[$indexKey]] = $subArray[$columnKey];
                }
            }
        }
        return $result;
    }

}

spl_autoload_register(array(HsAutoload::getSingleton(basename(__FILE__, '.php') . '_' . md5(realpath(__FILE__)), _ROCKPOS_VERSION_, _ROCKPOS_DIR_, array('classes/', 'pdf/', 'components/')), 'load'));

/**
 * Controller general of module Point Of Sale
 */
class HsPointOfSalePro extends PosPaymentModule
{

    /**
     * @var boolean
     */
    public $is_logged = false;

    /**
     * @var string
     */
    public $parent_admin_tab = 'AdminPos';

    /**
     * Constant path js.
     */
    const PATH_JS = 'views/js/';

    /**
     * Constant path css.
     */
    const PATH_CSS = 'views/css/';

    /**
     * Constant path media.
     */
    const PATH_MEDIA = 'assets/sounds/';

    /**
     * Constant path img.
     */
    const PATH_IMG = 'views/img/';

    /**
     * Define all tabs.
     *
     * @var array
     */
    public $pos_tabs = array();

    /**
     * containt all message lang include to js.
     */
    public $i18n = array();

    /**
     * Mark if RockPOS is initialized or not.
     *
     * @var bool
     */
    protected static $initialized = false;

    /**
     * Construct
     */
    public function __construct()
    {
        $this->name = 'hspointofsalepro';
        $this->tab = 'payments_gateways';
        $this->version = '4.1.5';
        $this->displayName = 'RockPOS';
        $this->author = 'Hamsa Technologies';
        $this->module_key = 'da010e9a47dd53cdb405602e5f9a3ad1';
        parent::__construct();
        $this->description = $this->l('POS for PrestaShop, synced with online shops in realtime');
        $this->initTranslations();
        $this->pos_tabs = $this->initAdminTabs();
        if (!defined('_ROCKPOS_PDF_TPL_DIR_')) {
            define('_ROCKPOS_PDF_TPL_DIR_', $this->local_path . 'views/templates/api');
        }
    }

    /**
     *
     * @return array
     * <pre>
     * array(
     *  string => array(// AdminTab class
     *      'position' => int,
     *      'active' => boolean,
     *      'name' => string
     *  )
     * )
     */
    protected function initAdminTabs()
    {
        return array(
            'AdminPos' => array(
                'position' => 1,
                'active' => true,
                'class_name' => 'AdminPos',
                'name' => $this->displayName,
            ),
            'AdminRockPosSales' => array(
                'position' => 2,
                'active' => true,
                'class_name' => 'AdminRockPosSales',
                'name' => $this->i18n['sell'],
            ),
            'AdminRockPosManage' => array(
                'position' => 3,
                'active' => true,
                'class_name' => 'AdminRockPosManage',
                'name' => '▸ ' . $this->i18n['manage'],
            ),
        );
    }

    /**
     * All possible message in this module
     * How to access:
     * - js: st.lang.key
     * - module: $this->lang[key]
     * - module admin controller: $this->lang[key]
     * NOTE: PLEASE CHECK IF THIS KEY ALREADY EXISTS.
     */
    protected function initTranslations()
    {
        $source = basename(__FILE__, '.php');
        $this->i18n = array(
            '1st_bill_printer' => $this->l('1st bill printer', $source),
            '2nd_bill_printer' => $this->l('2nd bill printer', $source),
            'accept_payment_complete_order' => $this->l('Accept payment - Complete order', $source),
            'accept_payment_complete_order' => $this->l('Accept payment - Complete order', $source),
            'access' => $this->l('Access', $source),
            'account_creation' => $this->l('Account creation', $source),
            'actions' => $this->l('Actions', $source),
            'activate' => $this->l('Activate', $source),
            'activate_license' => $this->l('Activate license', $source),
            'active' => $this->l('Active', $source),
            'add' => $this->l('Add', $source),
            'add_address' => $this->l('Add address', $source),
            'add_category' => $this->l('Add category', $source),
            'add_customization_data' => $this->l('Add customization data', $source),
            'add_missing_products_to_the_index' => $this->l('Add missing products to the index', $source),
            'add_new' => $this->l('Add new', $source),
            'add_new_address' => $this->l('Add new address', $source),
            'add_new_order_with_rock_pos' => $this->l('Add new order with RockPOS', $source),
            'add_new_product' => $this->l('Add new product', $source),
            'add_payment' => $this->l('Add payment', $source),
            'add_successfully' => $this->l('Add successfully', $source),
            'add_successfully' => $this->l('Add successfully', $source),
            'add_this' => $this->l('Add this', $source),
            'add_to_cart' => $this->l('Add to cart', $source),
            'add_your_note_here' => $this->l('Add your note here', $source),
            'added_to_cart' => $this->l('Added to cart', $source),
            'address' => $this->l('Address', $source),
            'address_2' => $this->l('Address 2', $source),
            'address_added' => $this->l('Address added', $source),
            'address_alias' => $this->l('Address alias', $source),
            'address_changed' => $this->l('Address changed', $source),
            'address_updated' => $this->l('Address updated', $source),
            'advanced_loyalty_program' => $this->l('Advanced Loyalty Program', $source),
            'all' => $this->l('All', $source),
            'all_in_one_rewards' => $this->l('All in one rewards', $source),
            'all_orders_loaded' => $this->l('All orders loaded', $source),
            'all_set' => $this->l('All set', $source),
            'already_loaded' => $this->l('Already loaded', $source),
            'already_returned' => $this->l('Already returned', $source),
            'amount' => $this->l('Amount', $source),
            'amount_due' => $this->l('Amount due', $source),
            'amount_tendered' => $this->l('Amount tendered', $source),
            'an_error_occurred_during_the_image_move_process' => $this->l('an error occurred during the image move process', $source),
            'an_error_occurred_during_the_image_resize_process' => $this->l('an error occurred during the image resize process', $source),
            'an_error_occurred_during_the_image_upload_process' => $this->l('an error occurred during the image upload process', $source),
            'an_error_occurred_while_attempting_to_change_your_password' => $this->l('An error occurred while attempting to change your password.', $source),
            'an_internal_error_occurred_during_loading_the_previous_orders' => $this->l('an internal error occurred during loading the previous orders', $source),
            'and' => $this->l('And', $source),
            'and_click_here_when_you_are_done' => $this->l('And[a]click here[a] when you are done.', $source),
            'and_see_how_it_looks_like' => $this->l('...and see how it looks like.', $source),
            'ape' => $this->l('APE', $source),
            'apply' => $this->l('Apply', $source),
            'apply' => $this->l('Apply', $source),
            'are_you_sure' => $this->l('Are you sure?', $source),
            'are_you_sure_to_delete' => $this->l('Are you sure to delete', $source),
            'are_you_sure_to_leave' => $this->l('Are you sure to leave?', $source),
            'are_you_sure_to_proceed_with_exchange' => $this->l('Are you sure to proceed with exchange?', $source),
            'are_you_sure_to_proceed_with_return' => $this->l('Are you sure to proceed with return?', $source),
            'are_you_sure_to_undo_this_payment_record' => $this->l('Are you sure to undo this payment record?', $source),
            'are_you_sure_you_will_proceed_without_adding_a_customer' => $this->l('Are you sure you will proceed without adding a customer?', $source),
            'associated_categories' => $this->l('Associated categories', $source),
            'attribute' => $this->l('Attribute', $source),
            'auto_indexing' => $this->l('Auto indexing', $source),
            'autocomplete_search' => $this->l('Autocomplete search', $source),
            'average_ticket_size' => $this->l('Average ticket size', $source),
            'back' => $this->l('Back', $source),
            'back_to_dashboard' => $this->l('Back to dashboard', $source),
            'back_to_homepage' => $this->l('Back to homepage', $source),
            'balance' => $this->l('Balance', $source),
            'barcode' => $this->l('Barcode', $source),
            'base_price' => $this->l('Base price', $source),
            'bestselling' => $this->l('Bestselling', $source),
            'bill_to' => $this->l('Bill to', $source),
            'billing_address' => $this->l('Billing address', $source),
            'can_not_change_to_your_option' => $this->l('Cannot change to your option', $source),
            'cancel' => $this->l('Cancel', $source),
            'cannot_add_payment' => $this->l('Cannot add payment', $source),
            'cannot_add_sales_commission' => $this->l('Cannot add sales commission', $source),
            'cannot_associate_this_customer' => $this->l('Cannot associate this customer', $source),
            'cannot_change_carrier' => $this->l('Cannot change carrier', $source),
            'you_cannot_apply_loyalty_points_on_these_products' => $this->l('You cannot apply loyalty points on these products.', $source),
            'cannot_get_product_id' => $this->l('Cannot get product id', $source),
            'cannot_load_order' => $this->l('Cannot load order.', $source),
            'cannot_load_payment' => $this->l('Cannot load payment.', $source),
            'cannot_remove_product_from_cart' => $this->l('Cannot remove product from cart', $source),
            'cannot_remove_this_payment' => $this->l('Cannot remove this payment', $source),
            'cannot_set_free_shipping' => $this->l('Cannot set free shipping', $source),
            'cannot_update_paymment' => $this->l('Cannot update payment', $source),
            'cannot_update_position' => $this->l('Cannot update position', $source),
            'carrier_changed' => $this->l('Carrier changed', $source),
            'cart_size' => $this->l('Cart size', $source),
            'cashier' => $this->l('Cashier', $source),
            'cashiers' => $this->l('Cashiers', $source),
            'catalog_only' => $this->l('Catalog only', $source),
            'categories' => $this->l('Categories', $source),
            'category' => $this->l('Category', $source),
            'category_filter' => $this->l('Category filter', $source),
            'category_found' => $this->l('Category found', $source),
            'change' => $this->l('Change', $source),
            'change_order_state' => $this->l('Change order state', $source),
            'change_order_state_successfully' => $this->l('Change order state successfully', $source),
            'change_shop' => $this->l('Change shop', $source),
            'change_to_this' => $this->l('Change to this', $source),
            'changed_combinations' => $this->l('Changed combinations', $source),
            'char_max' => $this->l('250 char. max', $source),
            'check_out' => $this->l('Check out', $source),
            'check_your_email_for_confirmation_link' => $this->l('Check your email for confirmation link', $source),
            'choose_an_image' => $this->l('Choose an image', $source),
            'city' => $this->l('City', $source),
            'click_continue_to_proceed_with_setting_up' => $this->l('Click "Continue" to proceed with setting up.', $source),
            'click_here' => $this->l('Click Here', $source),
            'click_here_to_enable_friendly_url_first' => $this->l('Click here to enable "Friendly URL" first.', $source),
            'click_here_to_proceed_with_setting_up' => $this->l('[a]Click here[a] to proceed with setting up', $source),
            'click_here_when_you_are_done' => $this->l('[a]Click here[a] when you are done.', $source),
            'click_to_go_to_sell_screen' => $this->l('Click to go to Sell screen', $source),
            'combination' => $this->l('Combination', $source),
            'combinations_found' => $this->l('Combinations found', $source),
            'coming_soon' => $this->l('Coming soon!', $source),
            'commission' => $this->l('Commission', $source),
            'commission_base' => $this->l('Commission base', $source),
            'commission_structure' => $this->l('Commission structure', $source),
            'commissions' => $this->l('Commissions', $source),
            'company' => $this->l('Company', $source),
            'complete_order' => $this->l('Complete order', $source),
            'complete_orders' => $this->l('Complete orders', $source),
            'connection_to_qz_tray' => $this->l('Connecting to QZ tray.', $source),
            'contact_us' => $this->l('Contact us!', $source),
            'continue' => $this->l('Continue', $source),
            'country' => $this->l('Country', $source),
            'created_at' => $this->l('Created at', $source),
            'credit_card_slip_for_order' => $this->l('Credit card slip for order #%s', $source),
            'crontab' => $this->l('Crontab', $source),
            'currencies' => $this->l('Currencies', $source),
            'currency' => $this->l('Currency', $source),
            'currency_changed' => $this->l('currency changed', $source),
            'custom_dates' => $this->l('Custom dates', $source),
            'custom_sale' => $this->l('Custom sale', $source),
            'custom_url_sell_screen' => $this->l('Custom URL (Sell screen)', $source),
            'customer' => $this->l('Customer', $source),
            'customer_added' => $this->l('Customer added', $source),
            'customer_associated' => $this->l('Customer associated', $source),
            'customer_email' => $this->l('Customer email', $source),
            'customer_name' => $this->l('Customer name', $source),
            'customer_profiles_found' => $this->l('Customer profiles found.', $source),
            'customer_unassociated' => $this->l('Customer unassociated', $source),
            'customers' => $this->l('Customers', $source),
            'customization' => $this->l('Customization', $source),
            'customize' => $this->l('Customize', $source),
            'dashboard' => $this->l('Dashboard', $source),
            'date' => $this->l('Date', $source),
            'date_of_birth' => $this->l('Date of birth', $source),
            'day' => $this->l('Day', $source),
            'dd' => $this->l('DD', $source),
            'default' => $this->l('Default', $source),
            'default_address_alias' => $this->l('Default address alias', $source),
            'default_carrier' => $this->l('Default carrier', $source),
            'default_categories' => $this->l('Default categories', $source),
            'default_order_discount' => $this->l('Default order discount', $source),
            'default_order_state' => $this->l('Default order state', $source),
            'default_search_criteria' => $this->l('Default search criteria', $source),
            'delete' => $this->l('Delete', $source),
            'delete_successfully' => $this->l('Delete successfully', $source),
            'delete_this_item' => $this->l('Delete this item', $source),
            'delivery_address' => $this->l('Delivery address', $source),
            'description' => $this->l('Description', $source),
            'detail' => $this->l('Detail', $source),
            'disc' => $this->l('Disc', $source),
            'discount' => $this->l('Discount', $source),
            'discount_applied' => $this->l('Discount applied', $source),
            'discount_invalid' => $this->l('Discount invalid', $source),
            'discount_removed' => $this->l('Discount removed', $source),
            'discount_should_be_between_0_and_100' => $this->l('Discount should be between 0% and 100%.', $source),
            'display_on_receipt_invoice' => $this->l('Display on receipt / invoice', $source),
            'display_these' => $this->l('Display these:', $source),
            'divider' => $this->l('Divider', $source),
            'do_you_want_to_cancel_this_order' => $this->l('Do you want cancel this order?', $source),
            'do_you_want_to_cancel_this_return_transaction' => $this->l('Do you want to cancel this return transaction?', $source),
            'do_you_want_to_delete_this_item' => $this->l('Do you want to delete this item?', $source),
            'do_you_want_to_leave' => $this->l('Do you want to leave?', $source),
            'done' => $this->l('Done', $source),
            'download_qz_tray' => $this->l('Download [a]QZ tray[a]', $source),
            'drag_a_tag' => $this->l('Drag a tag...', $source),
            'drop_into_the_editor' => $this->l('...drop into the editor...', $source),
            'ean13' => $this->l('EAN-13', $source),
            'ean_13_or_jan_barcode' => $this->l('EAN-13 or JAN barcode', $source),
            'ecotax' => $this->l('Ecotax', $source),
            'edit' => $this->l('Edit', $source),
            'email' => $this->l('Email', $source),
            'email_address' => $this->l('Email address', $source),
            'email_invalid' => $this->l('Email invalid.', $source),
            'email_is_empty' => $this->l('Email is empty.', $source),
            'emailing' => $this->l('Emailing', $source),
            'employee' => $this->l('Employee', $source),
            'enable_for_sale' => $this->l('Enable for sale', $source),
            'enable_payment' => $this->l('Enable payment', $source),
            'enabled' => $this->l('Enabled', $source),
            'enter_product_name_reference_ean13_upc_etc' => $this->l('Enter porduct name, reference, EAN-13 , UPC, etc...', $source),
            'everywhere' => $this->l('Everywhere', $source),
            'exchange' => $this->l('Exchange', $source),
            'exchange_slip' => $this->l('Exchange slip', $source),
            'exempt_of_vat_according_to_section_259b_of_the_general_tax_code' => $this->l('Exempt of VAT according to section 259B of the General Tax Code.', $source),
            'export_to_pdf' => $this->l('Export to PDF', $source),
            'fax' => $this->l('Fax', $source),
            'feature' => $this->l('Feature', $source),
            'filter' => $this->l('Filter', $source),
            'first_name' => $this->l('First name', $source),
            'five_characters_min' => $this->l('Five characters min', $source),
            'footer' => $this->l('Footer', $source),
            'footer_text' => $this->l('Footer text', $source),
            'for_example' => $this->l('For example, %s', $source),
            'free_shipping' => $this->l('Free shipping', $source),
            'full_screen' => $this->l('Full screen', $source),
            'fully_paid' => $this->l('Fully paid', $source),
            'general' => $this->l('General', $source),
            'generate_a_credit_slip' => $this->l('Generate a credit slip', $source),
            'generate_a_voucher' => $this->l('Generate a voucher', $source),
            'generate_discount' => $this->l('Generate discount', $source),
            'generated_by_at' => $this->l('Generated by %1$s [breakline] at %2$s', $source),
            'get_new_password' => $this->l('Get new password', $source),
            'gift' => $this->l('Gift', $source),
            'go_premium_now' => $this->l('go Premium now', $source),
            'go_with_premium_now' => $this->l('Go with Premium now.', $source),
            'gross_margins' => $this->l('Gross margins', $source),
            'gross_sales' => $this->l('Gross sales', $source),
            'guest_account' => $this->l('Guest account', $source),
            'guest_checkout' => $this->l('Guest checkout', $source),
            'guest_checkout_warning' => $this->l('Guest checkout warning', $source),
            'has_company_information' => $this->l('Has company information?', $source),
            'header' => $this->l('Header', $source),
            'header_text' => $this->l('Header text', $source),
            'here_are_steps_to_get_started' => $this->l('Here are steps to get started:', $source),
            'home' => $this->l('Home', $source),
            'home_phone' => $this->l('Home phone', $source),
            'i_already_installed_it' => $this->l('I already installed it.', $source),
            'id' => $this->l('ID', $source),
            'if_no_logo_uploaded_the_shop_logo_will_be_used_instead' => $this->l('If no logo uploaded, the shop logo will be used instead.', $source),
            'if_you_are_a_premium_user_use_the_email_associated_with_your_purchase' => $this->l('If you are a Premium user, use the email associated with your purchase.', $source),
            'image' => $this->l('Image', $source),
            'images_in_shopping_cart' => $this->l('Images in shopping cart', $source),
            'inactive' => $this->l('Inactive', $source),
            'indexing_status' => $this->l('Indexing status', $source),
            'indicates_a_required_field' => $this->l('Indicates a required field', $source),
            'install_it_as_a_typical_program_on_your_pc' => $this->l('Install it as a typical program on your PC', $source),
            'instock' => $this->l('Instock', $source),
            'internal_server_error' => $this->l('Internal server error', $source),
            'invalid_amount' => $this->l('Invalid amount', $source),
            'invalid_email' => $this->l('Invalid email.', $source),
            'invalid_message' => $this->l('Invalid message', $source),
            'invalid_password' => $this->l('Invalid password.', $source),
            'invalid_quantity' => $this->l('Invalid quantity', $source),
            'invoice' => $this->l('Invoice', $source),
            'invoice_address' => $this->l('Invoice Address', $source),
            'invoice_date' => $this->l('Invoice date', $source),
            'invoice_logo' => $this->l('Invoice logo', $source),
            'invoice_number' => $this->l('Invoice number', $source),
            'invoice_size' => $this->l('Invoice size', $source),
            'invoicing' => $this->l('Invoicing', $source),
            'item' => $this->l('item', $source),
            'item_removed' => $this->l('Item removed', $source),
            'items' => $this->l('items', $source),
            'items_found' => $this->l('Items found', $source),
            'just_applied_a_voucher_of' => $this->l('Just applied a voucher of %s.', $source),
            'just_enable_this_option_in_case_you_cant_print_special_characters' => $this->l('Just enable this option in case you can\'t print special character(s)', $source),
            'just_tell_me' => $this->l('Just tell me.', $source),
            'k57' => $this->l('K57 (57x80 mm; 2.25x3.19 in)', $source),
            'k80' => $this->l('K80 (80x114 mm; 3.50x4.49 in)', $source),
            'last_12_months' => $this->l('Last 12 months', $source),
            'last_7_days' => $this->l('Last 7 days', $source),
            'last_month' => $this->l('Last month', $source),
            'last_name' => $this->l('Last name', $source),
            'last_week' => $this->l('Last week', $source),
            'launch_it' => $this->l('Launch it', $source),
            'lets_go' => $this->l('Let\'s go!', $source),
            'line_break' => $this->l('Line break', $source),
            'list_pos_orders_under_bo_orders' => $this->l('List POS orders under BO > Orders', $source),
            'log_in' => $this->l('Log in', $source),
            'log_out' => $this->l('Log out', $source),
            'logged' => $this->l('Logged', $source),
            'logo' => $this->l('Logo', $source),
            'logo_width' => $this->l('Logo width(%)', $source),
            'lost_your_password' => $this->l('Lost your password?', $source),
            'loyalty' => $this->l('Loyalty', $source),
            'loyalty_point' => $this->l('Loyalty point', $source),
            'loyalty_points' => $this->l('Loyalty points', $source),
            'loyalty_system' => $this->l('Loyalty system', $source),
            'manage' => $this->l('Manage', $source),
            'manufacturer' => $this->l('Manufacturer', $source),
            'manufaturer' => $this->l('Manufaturer', $source),
            'maximum' => $this->l('Maximum 100%.', $source),
            'product_name_length' => $this->l('Product name length', $source),
            'mm' => $this->l('MM', $source),
            'mobile_phone' => $this->l('Mobile phone', $source),
            'month' => $this->l('Month', $source),
            'more_commission_structure' => $this->l('More commission structure?', $source),
            'mrp' => $this->l('MRP', $source),
            'multiple_languages' => $this->l('Multiple languages', $source),
            'name' => $this->l('Name', $source),
            'net_profit' => $this->l('Net profit', $source),
            'new' => $this->l('New', $source),
            'new_address' => $this->l('NEW ADDRESS', $source),
            'new_credit_slip_regarding_your_order' => $this->l('New credit slip regarding your order', $source),
            'new_customer' => $this->l('NEW CUSTOMER', $source),
            'new_order_initialized' => $this->l('New order initialized', $source),
            'new_voucher_for_your_order' => $this->l('New voucher for your order #%s', $source),
            'next' => $this->l('Next', $source),
            'no' => $this->l('No', $source),
            'no_associated_category_found' => $this->l('No associated category found', $source),
            'no_associated_customer_found' => $this->l('No associated customer found.', $source),
            'no_available_orders' => $this->l('No available orders.', $source),
            'no_carrier' => $this->l('No carrier', $source),
            'no_order_found' => $this->l('No order found', $source),
            'no_payment' => $this->l('No payment', $source),
            'no_products' => $this->l('No products', $source),
            'no_tax' => $this->l('No tax', $source),
            'none_selected' => $this->l('None selected', $source),
            'not_installed_yet_or_currently_disabled' => $this->l('not installed yet or currently disabled', $source),
            'note' => $this->l('Note', $source),
            'note_added' => $this->l('Note added', $source),
            'nowhere' => $this->l('Nowhere', $source),
            'on_hold' => $this->l('On hold', $source),
            'only_accept_png_jpg_gif' => $this->l('Only accept .png, .jpg, .gif.', $source),
            'oops_at_least_quantities_required' => $this->l('Oops, at least %s quantities required.', $source),
            'oops_no_invoice_associated_with_order' => $this->l('Oops, no invoice associated with order %s!', $source),
            'oops_not_enough_product_in_stock' => $this->l('Oops, not enough product in stock (%s).', $source),
            'oops_something_goes_wrong' => $this->l('Oops! Something goes wrong.', $source),
            'oops_something_goes_wrong_2' => $this->l('Oops, something goes wrong. Please try again!', $source),
            'oops_your_image_size_exceeds_to' => $this->l('Oops, your image size exceeds to', $source),
            'oops_your_lite_free_version_exceeds_to_xx_orders_in_last_30_days' => $this->l('Oops, your LITE (free) version exceeds to [xx] orders in last 30 days.', $source),
            'oops_your_session_just_expired' => $this->l('Oops, your session just expired. Please log in again!', $source),
            'or' => $this->l('or', $source),
            'or_setup_a_cron_job' => $this->l('Or setup a cron job', $source),
            'order' => $this->l('Order', $source),
            'order_canceled' => $this->l('Order canceled', $source),
            'order_completed' => $this->l('Order completed', $source),
            'order_completed_but_some_external_processes_are_not_done_as_expected' => $this->l('Order completed, but some external processes are not done as expected.', $source),
            'order_completion_guest_checkout' => $this->l('Order completion (Guest checkout)', $source),
            'order_completion_standard_checkout' => $this->l('Order completion (Standard checkout)', $source),
            'order_creation' => $this->l('Order creation', $source),
            'order_date' => $this->l('Order date', $source),
            'order_discount' => $this->l('Order discount', $source),
            'order_found' => $this->l('order found', $source),
            'order_history' => $this->l('Order history', $source),
            'order_loaded' => $this->l('Order loaded', $source),
            'order_reference' => $this->l('Order reference'),
            'order_states' => $this->l('Order states', $source),
            'order_status' => $this->l('Order status', $source),
            'orders' => $this->l('Orders', $source),
            'original_order_paid' => $this->l('Original order (paid)', $source),
            'other' => $this->l('Other', $source),
            'others' => $this->l('Others', $source),
            'otherwise_enter_your_most_used_email_to_go_with_lite_free_version' => $this->l('Otherwise, enter your most used email to go with LITE (free) version.', $source),
            'out_of_stock' => $this->l('Out of stock', $source),
            'paid' => $this->l('Paid', $source),
            'paid_in_full_click_standard_to_continue' => $this->l('Paid in full! Click Standard to continue.', $source),
            'paper_size' => $this->l('Paper size', $source),
            'password' => $this->l('Password', $source),
            'pay' => $this->l('Pay', $source),
            'payment' => $this->l('Payment', $source),
            'payment_added' => $this->l('Payment added', $source),
            'payment_complete' => $this->l('Payment complete', $source),
            'payment_completed' => $this->l('Payment completed!', $source),
            'payment_method' => $this->l('Payment method', $source),
            'payment_methods' => $this->l('Payment methods', $source),
            'payment_removed' => $this->l('Payment removed', $source),
            'payment_types' => $this->l('Payment types', $source),
            'payments' => $this->l('Payments', $source),
            'phone' => $this->l('Phone', $source),
            'pick_up_in_store' => $this->l('Pick up in-store', $source),
            'pick_up_one' => $this->l('Pick Up One', $source),
            'please_activate_your_license_by_entering_your_email' => $this->l('Please activate your license by entering your email', $source),
            'please_configure_the_default_printer_setting_in_step_2_first' => $this->l('Please configure the default printer settings in step 2 first.', $source),
            'please_enable' => $this->l('please enable', $source),
            'please_enter_email_or_firstname' => $this->l('Please enter email or firstname.', $source),
            'please_enter_your_email_address_you_will_receive_a_link_to_create_a_new_password_via_email' => $this->l('Please enter your email address. You will receive a link to create a new password via email.', $source),
            'please_fill_the_required_custom_fields_to_complete_the_sale' => $this->l('Please fill the required custom fields to complete the sale', $source),
            'please_install' => $this->l('please install', $source),
            'please_mark_another_payment_as_default_instead' => $this->l('Please mark another payment as default instead.', $source),
            'please_tell_me' => $this->l('Please tell me', $source),
            'please_try_again' => $this->l('Please try again!', $source),
            'please_wait' => $this->l('Please wait...', $source),
            'point' => $this->l('point', $source),
            'point_of_sale' => $this->l('Point of Sale', $source),
            'points' => $this->l('points', $source),
            'pos' => $this->l('POS', $source),
            'pre_order' => $this->l('Pre-order', $source),
            'pre_order_now' => $this->l('Pre-order now', $source),
            'prestashop_loyalty' => $this->l('PrestaShop Loyalty', $source),
            'price' => $this->l('Price', $source),
            'price_tax_excl' => $this->l('Price (tax excl)', $source),
            'print' => $this->l('Print', $source),
            'print_credit_slip' => $this->l('Print credit slip', $source),
            'print_in_pdf' => $this->l('Print in PDF', $source),
            'print_invoice' => $this->l('Print invoice', $source),
            'print_receipt' => $this->l('Print receipt', $source),
            'printer_dpi' => $this->l('Printer DPI', $source),
            'product' => $this->l('Product', $source),
            'product_name' => $this->l('Product name', $source),
            'products' => $this->l('Products', $source),
            'products_are_indexed_automatically_whenever_they_are_added_or_edited' => $this->l('Products are indexed automatically whenever they are added or edited', $source),
            'qty' => $this->l('Qty', $source),
            'quantity' => $this->l('Quantity', $source),
            'qz_tray' => $this->l('QZ tray', $source),
            're_build_the_entire_index' => $this->l('Re-build the entire index', $source),
            're_build_the_entire_index' => $this->l('Re-build the entire index', $source),
            're_stock_products' => $this->l('Re-stock products', $source),
            'receipt' => $this->l('Receipt', $source),
            'receipt_will_be_sent_to_this_printer' => $this->l('Receipt will be sent to this printer.', $source),
            'ref' => $this->l('Ref', $source),
            'reference' => $this->l('Reference', $source),
            'reference_barcode' => $this->l('Reference (barcode)', $source),
            'reference_code' => $this->l('Reference code', $source),
            'reference_text' => $this->l('Reference (text)', $source),
            'refresh_this_page_and_continue' => $this->l('Refresh this_page and continue', $source),
            'register' => $this->l('Register', $source),
            'register_number' => $this->l('Register number', $source),
            'remember_me' => $this->l('Remember me', $source),
            'remember_this_selection' => $this->l('Remember this selection', $source),
            'repay_shipping_costs' => $this->l('Repay shipping costs', $source),
            'reports' => $this->l('Reports', $source),
            'reset' => $this->l('Reset', $source),
            'retail_price' => $this->l('Retail price', $source),
            'return' => $this->l('Return', $source),
            'return_exchange' => $this->l('Return/Exchange', $source),
            'return_exchange_successfully' => $this->l('Return/Exchange successfully.', $source),
            'returned_order' => $this->l('Returned order', $source),
            'return_slip' => $this->l('Return slip', $source),
            'rockpos_utilizes_qz_tray_to_print_your_receipt' => $this->l('RockPOS utilizes QZ tray to print your receipt.', $source),
            'sale_price' => $this->l('Sale price', $source),
            'sales' => $this->l('Sales', $source),
            'sales_report' => $this->l('Sales report', $source),
            'sales_summary' => $this->l('Sales summary', $source),
            'salesman' => $this->l('Salesman', $source),
            'salesman_profiles' => $this->l('Salesman profiles', $source),
            'salesperson' => $this->l('Salesperson', $source),
            'same_as_standard' => $this->l('Same as "Standard"', $source),
            'save' => $this->l('Save', $source),
            'save_customization' => $this->l('Save customization', $source),
            'save_sell' => $this->l('Save & Sell', $source),
            'saved_customization' => $this->l('Saved customization', $source),
            'search_by_id' => $this->l('Search by ID', $source),
            'search_by_reference' => $this->l('Search by reference', $source),
            'search_for_a_customer' => $this->l('Search for a customer...', $source),
            'search_for_customer' => $this->l('Search for  customer', $source),
            'search_for_id' => $this->l('Search for customer', $source),
            'search_for_items' => $this->l('Search for items...', $source),
            'search_for_orders' => $this->l('Search for orders', $source),
            'search_only' => $this->l('Search only', $source),
            'select_an_image' => $this->l('Select an image', $source),
            'selected' => $this->l('Selected', $source),
            'selected_another_state' => $this->l('selected another state.', $source),
            'sell' => $this->l('Sell', $source),
            'seller' => $this->l('Seller', $source),
            'setup' => $this->l('Setup', $source),
            'share_your_data' => $this->l('Share your data', $source),
            'shipping' => $this->l('Shipping', $source),
            'shipping_cost' => $this->l('Shipping cost', $source),
            'shipping_method' => $this->l('Shipping method', $source),
            'shop' => $this->l('Shop', $source),
            'shop_address' => $this->l('Shop address', $source),
            'shop_name' => $this->l('Shop name', $source),
            'shops' => $this->l('Shops', $source),
            'short_description' => $this->l('Short description', $source),
            'sign_up_for_newsletter' => $this->l('Sign up for newsletter', $source),
            'signature' => $this->l('Signature', $source),
            'siret' => $this->l('SIRET', $source),
            'sold_items' => $this->l('Sold items', $source),
            'sort_by_name' => $this->l('Sort by name', $source),
            'sort_by_price' => $this->l('Sort by price', $source),
            'sp' => $this->l('SP', $source),
            'standard' => $this->l('Standard', $source),
            'state' => $this->l('State', $source),
            'status' => $this->l('Status', $source),
            'stock' => $this->l('Stock', $source),
            'stocks' => $this->l('Stocks', $source),
            'straight' => $this->l('Straight', $source),
            'sub_total' => $this->l('Subtotal', $source),
            'summary' => $this->l('Summary', $source),
            'tag' => $this->l('Tag', $source),
            'tax' => $this->l('Tax', $source),
            'tax_detail' => $this->l('Tax Detail', $source),
            'tax_excl' => $this->l('Tax excl.', $source),
            'tax_incl' => $this->l('Tax incl.', $source),
            'tax_included' => $this->l('Tax included', $source),
            'tax_number' => $this->l('Tax number', $source),
            'tax_rate' => $this->l('Tax rate', $source),
            'taxes' => $this->l('Taxes', $source),
            'tel' => $this->l('Tel', $source),
            'template' => $this->l('Template', $source),
            'thank_you_for_shopping' => $this->l('Thank you for shopping!', $source),
            'thank_you_for_shopping_with_us' => $this->l('Thank you for shopping with us.', $source),
            'the_item_is_not_available' => $this->l('The item is not available.', $source),
            'the_order_cannot_be_found_within_your_database' => $this->l('The order cannot be found within your database.', $source),
            'the_order_slip_cannot_be_found_within_your_database' => $this->l('The order slip cannot be found within your database.', $source),
            'the_password_field_is_blank' => $this->l('The password field is blank..', $source),
            'the_voucher_code_is_invalid' => $this->l('The voucher code is invalid.', $source),
            'there' => $this->l('there', $source),
            'there_was_an_error_saving_this_product' => $this->l('There was an error saving this product. Please try again!', $source),
            'this_account_does_not_exist' => $this->l('This account does not exist.', $source),
            'this_employee_does_not_manage_the_shop_anymore' => $this->l('This employee does not manage the shop anymore (Either the shop has been deleted or permissions have been revoked).', $source),
            'this_product_is_saved_successfully' => $this->l('This product is saved successfully!', $source),
            'this_variant_already_exists_in_your_order' => $this->l('This variant already exists in your order.', $source),
            'this_variant_does_not_exist' => $this->l('This variant does not exist.', $source),
            'this_voucher_does_not_exist' => $this->l('This voucher does not exist.', $source),
            'title' => $this->l('Title', $source),
            'to_pre_load_products_under_these_categories' => $this->l('To pre-load products under these categories', $source),
            'to_solve_it_just_simply_go_with_premium_plan_which_costs_only_a_cup_of_coffee_a_day' => $this->l('To solve it, just simply go with Premium plan which costs only a cup of coffee a day.', $source),
            'today' => $this->l('Today', $source),
            'top_cashiers' => $this->l('Top cashiers', $source),
            'total' => $this->l('Total', $source),
            'total_discount' => $this->l('Total discount', $source),
            'total_items' => $this->l('Total items', $source),
            'total_order_discount' => $this->l('Total order discount', $source),
            'total_paid' => $this->l('Total paid', $source),
            'total_product' => $this->l('Total product', $source),
            'total_product_discount' => $this->l('Total product discount', $source),
            'total_products' => $this->l('Total products', $source),
            'total_real_paid' => $this->l('Total (real paid)', $source),
            'total_shipping' => $this->l('Total shipping', $source),
            'total_tax' => $this->l('Total tax', $source),
            'total_tax_excl' => $this->l('Total (tax excl.)', $source),
            'total_tax_incl' => $this->l('Total (tax incl.)', $source),
            'total_vouchers' => $this->l('Total vouchers', $source),
            'total_with_tax' => $this->l('Total (with tax)', $source),
            'total_without_tax' => $this->l('Total (without tax)', $source),
            'transactions' => $this->l('Transactions', $source),
            'transform_to_a_voucher_and_apply_to_this_order' => $this->l('Transform to a voucher and apply to this order', $source),
            'trend' => $this->l('Trend', $source),
            'try_again' => $this->l('Try again', $source),
            'turn_it_on_or_restart_it' => $this->l('Turn it on or restart it.', $source),
            'u_p' => $this->l('U/P', $source),
            'undo' => $this->l('Undo', $source),
            'unit_price' => $this->l('Unit price', $source),
            'unknown' => $this->l('unknown', $source),
            'unpaid' => $this->l('Unpaid', $source),
            'upc' => $this->l('UPC', $source),
            'upc_barcode' => $this->l('UPC barcode', $source),
            'update_address' => $this->l('Update address', $source),
            'update_customization' => $this->l('Update customization', $source),
            'update_successfully' => $this->l('Update successfully', $source),
            'variables' => $this->l('Variables', $source),
            'vat_number' => $this->l('VAT number', $source),
            'view' => $this->l('view', $source),
            'visibility' => $this->l('Visibility', $source),
            'voucher' => $this->l('Voucher', $source),
            'vouchers' => $this->l('Vouchers', $source),
            'warehouse' => $this->l('warehouse', $source),
            'warn_cashiers_if_they_are_about_to_make_guest_checkout' => $this->l('Warn cashiers if they are about to make a guest checkout.', $source),
            'we_cannot_establish_a_connection_to_qz_tray' => $this->l('We cannot establish a connection to QZ tray.', $source),
            'we_dont_want_your_business_affected_by_this_limitation' => $this->l('We dont want your business affected by this limitation.', $source),
            'we_would_like_to_serve_you_better_and_we_are_looking_for_anonymous_data_to_improve_rockpos_day_by_day' => $this->l('We would like to serve you better, and we are looking for anonymous data to improve RockPOS day by day.', $source),
            'website' => $this->l('Website', $source),
            'website_url' => $this->l('Website URL', $source),
            'week' => $this->l('Week', $source),
            'welcome' => $this->l('Welcome!', $source),
            'whoops_it_seems_qz_tray_has_not_launched_properly_yet' => $this->l('Whoops, it seems QZ tray has not launched properly yet.', $source),
            'wrapping' => $this->l('Wrapping', $source),
            'yes' => $this->l('Yes', $source),
            'yesterday' => $this->l('Yesterday', $source),
            'you_are_all_set' => $this->l('You are all set.', $source),
            'you_are_in_pre_order_mode' => $this->l('You are in Pre-order mode.', $source),
            'you_can_not_delete_all_payment_methods' => $this->l('You can not delete all payment methods', $source),
            'you_can_not_disable_all_payment_methods' => $this->l('You can not disable all payment methods', $source),
            'you_can_regenerate_your_password_only_every_minutes' => $this->l('You can regenerate your password only every %s minutes', $source),
            'you_can_set_a_cron_job_that_will_build_your_index_using_the_following_url' => $this->l('You can set a cron job that will build your index using the following URL:', $source),
            'you_cannot_use_this_voucher_with_these_products' => $this->l('You cannot use this voucher with these products', $source),
            'you_cant_access_to_this_shop_at_front_office' => $this->l("you can't access to this shop at front office", $source),
            'you_have_made_of_orders_in_last_30_days' => $this->l('You have made [totalOrder] of [limitOrder] orders in last 30 days.', $source),
            'you_must_enter_a_voucher_code' => $this->l('You must enter a voucher code.', $source),
            'you_will_be_redirected_automatically_in_span_3_span_seconds_or_click_a_here_a' => $this->l('You will be redirected automatically in [span] 3 [/span] seconds, or click [a]here[/a].', $source),
            'your_account_does_not_exist_or_the_password_is_incorrect' => $this->l('Your account does not exist or the password is incorrect.', $source),
            'your_available_voucher' => $this->l('Your available voucher', $source),
            'your_available_vouchers' => $this->l('Your available vouchers', $source),
            'your_customization_is_not_saved' => $this->l('Your customization is not saved.', $source),
            'your_license_has_expired' => $this->l('Your license has expired. Please renew your license. In case you forgot to renew your license, you still can use your RockPOS as Starter version.', $source),
            'your_new_password' => $this->l('Your new password', $source),
            'your_password_has_been_emailed_to_you' => $this->l('Your password has been emailed to you.', $source),
            'yyyy' => $this->l('YYYY', $source),
            'zip_postal_code' => $this->l('Zip/Postal Code', $source),
            'zipcode' => $this->l('Zipcode', $source),
        );
        $this->context->smarty->assign('hs_pos_i18n', $this->i18n);
    }

    /**
     * Install module.
     *
     * @return bool
     */
    public function install()
    {
        $success = array();
        $success[] = parent::install();
        $pos_installer = new PosInstaller($this);
        $success[] = $pos_installer->install();
        return array_sum($success) >= count($success);
    }

    /**
     * Install module.
     *
     * @return bool
     */
    public function uninstall()
    {
        $success = array();
        $success[] = parent::uninstall();
        $pos_installer = new PosInstaller($this);
        $success[] = $pos_installer->uninstall();
        return array_sum($success) >= count($success);
    }

    /**
     * Get relative path to js files of module.
     *
     * @return string
     */
    public function getJsPath()
    {
        return $this->_path . self::PATH_JS;
    }

    /**
     * Get relative path to css files of module.
     *
     * @return string
     */
    public function getCssPath()
    {
        return $this->_path . self::PATH_CSS;
    }

    /**
     * Get relative path to media files of module.
     *
     * @return string
     */
    public function getMediaPath()
    {
        return $this->_path . self::PATH_MEDIA;
    }

    /**
     * Get relative path to images files of module.
     *
     * @return string
     */
    public function getImgPath()
    {
        return $this->_path . self::PATH_IMG;
    }

    /**
     * hook into Back Office header position.
     *
     * @return assign template
     */
    public function hookDisplayBackOfficeHeader()
    {
        if (method_exists($this->context->controller, 'addCSS')) {
            $this->context->controller->addCSS($this->getCssPath() . 'icon_menu_pos.css');
        }
        if ($this->context->controller instanceof AdminOrdersController) {
            $this->context->smarty->assign(array(
                'label' => $this->i18n['pos'],
                'title' => $this->i18n['add_new_order_with_rock_pos'],
                'sales_link' => $this->context->link->getAdminLink('AdminRockPosSales'),
            ));
            return $this->display($this->name . '.php', 'pos_backofficeheader.tpl');
        }
    }

    public function hookModuleRoutes()
    {
        $rule = Configuration::get('POS_CUSTOM_URI') ? Configuration::get('POS_CUSTOM_URI') : 'pos';
        return array(
            'module-' . $this->name . '-sales' => array(
                'controller' => 'sales',
                'rule' => $rule,
                'keywords' => array(),
                'params' => array(
                    'fc' => 'module',
                    'module' => $this->name
                )
            ),
        );
    }

    /**
     * @param array $params
     * <pre>
     * array(
     *      select => string,// optional, passed by reference
     *      join => string,// optional, passed by reference
     *      where => string,// optional, passed by reference
     *      group_by => string,// optional, passed by reference
     *      order_by => string,// optional, passed by reference
     *      order_way => string,// optional, passed by reference
     *      fields => array // @see AdminController::fields_list, passed by reference
     *      cookie => Cookie,
     *      cart => Cart// optional
     * )
     */
    public function hookActionAdminOrdersListingFieldsModifier(array $params)
    {
        if (!PosConfiguration::get('POS_SHOW_ORDERS_UNDER_PS_ORDERS')) {
            if (in_array('where', array_keys($params))) {
                $params['where'] .= " AND a.`module` <> '$this->name'";
            }
        }
    }

    /**
     * @param array $params
     * array(
     *      id_carrier' => int // old carrier id
     *      carrier' => Carrier // new carrier
     * )
     */
    public function hookActionCarrierUpdate($params)
    {
        if ((int) Configuration::get('POS_ID_CARRIER') === (int) $params['id_carrier']) {
            Configuration::updateValue('POS_ID_CARRIER', $params['carrier']->id);
        }
    }

    /**
     * @param array $params
     *                      array(<pre>
     *                      'id_product' => int,
     *                      'product' => Product
     *                      )</pre>
     */
    public function hookActionProductSave(array $params)
    {
        if (PosConfiguration::get('POS_AUTO_INDEXING')) {
            if (Validate::isLoadedObject($params['product'])) {
                $pos_search_index = new PosSearchIndex(false, false, (int) $params['product']->id);
                $pos_search_index->run();
            }
        }
    }

    /**
     * @param array $params
     *                      array(<pre>
     *                      'id_product' => int,
     *                      'product' => Product
     *                      )</pre>
     */
    public function hookActionProductDelete(array $params)
    {
        if (PosConfiguration::get('POS_AUTO_INDEXING')) {
            if (Validate::isLoadedObject($params['product'])) {
                $pos_search_index = new PosSearchIndex(false, true, (int) $params['product']->id);
                $pos_search_index->run();
            }
        }
    }

    /**
     * Get translations of specific keys; return all available translations if no key passed.
     *
     * @param array $keys
     *                    <pre>
     *                    array(
     *                    string,//key of a translation phrase
     *                    string,
     *                    ...
     *                    )
     *                    </pre>
     *
     * @return array
     *               <pre>
     *               array(
     *               string => string, //key => translation
     *               string => string,
     *               ...
     *               )
     *               </pre>
     */
    public function getTranslations(array $keys = array())
    {
        $translations = array();
        if (empty($keys)) {
            $translations = $this->i18n;
        } else {
            foreach ($keys as $key) {
                $translations[$key] = $this->getTranslation($key);
            }
        }
        return $translations;
    }

    /**
     * Get text transtaltion by key.
     *
     * @param string $key
     *
     * @return string
     */
    public function getTranslation($key)
    {
        return !empty($this->i18n[$key]) ? $this->i18n[$key] : '';
    }

    /**
     *
     * @param boolean $force
     */
    public function initContext($force = false)
    {
        if (self::$initialized && !$force) {
            return;
        }

        // Employee
        if (defined('_PS_ADMIN_DIR_')) {
            $employee = new PosEmployee((int) $this->context->cookie->id_employee);
            if (Validate::isLoadedObject($employee)) {
                $this->context->employee = $employee;
                $this->is_logged = true;
            }
        } elseif (!empty($this->context->cookie->pos_id_employee) && (int) $this->context->cookie->pos_id_employee) {
            $employee = new PosEmployee((int) $this->context->cookie->pos_id_employee);
            if (Validate::isLoadedObject($employee)) {
                $this->context->employee = $employee;
                $this->is_logged = true;
            }
        }

        // Shop
        if (isset($this->context->shop) && $this->context->shop->id > 0) {
            $this->context->shop = new PosShop($this->context->shop->id);
        }


        // Currency
        if (!empty($this->context->cookie->id_currency)) {
            $currency = new Currency((int) $this->context->cookie->id_currency);
            if (Validate::isLoadedObject($currency) && !$currency->deleted) {
                $this->context->currency = $currency;
            }
        }
        if (!Validate::isLoadedObject($this->context->currency)) {
            $this->context->currency = new Currency(Configuration::get('PS_CURRENCY_DEFAULT'));
            $this->context->cookie->id_currency = $this->context->currency->id;
        }

        // Cart
        if (!empty($this->context->cookie->pos_id_cart) && (int) $this->context->cookie->pos_id_cart) {
            $cart = new PosCart((int) $this->context->cookie->pos_id_cart);
        }
        if (empty($cart) || !Validate::isLoadedObject($cart)) {
            unset($this->context->cookie->pos_id_cart, $this->context->cookie->checkedTOS);
            $this->context->cookie->check_cgv = false;
            $cart = $this->createEmptyCart();
        }

        $this->context->cart = $cart;

        // Order
        if (isset($this->context->cookie->id_pos_exchange)) {
            $pos_exchange = new PosExchange((int) $this->context->cookie->id_pos_exchange);
            $pos_order = new PosOrder((int) $pos_exchange->id_order_old);
            if (Validate::isLoadedObject($pos_order)) {
                $this->context->order = $pos_order;
            }
        }


        if (Validate::isLoadedObject($cart) && Validate::isLoadedObject($this->context->currency) && $cart->id_currency != $this->context->currency->id) {
            $cart->id_currency = $this->context->currency->id;
            $cart->save();
        }
        // Due to unknown reason, Context::currency might not be fully loaded
        if (empty($cart->id_currency)) {
            $cart->id_currency = (int) Configuration::get('PS_CURRENCY_DEFAULT');
            $cart->save();
        }

        // Customer
        $this->context->customer = new PosCustomer($cart->id_customer);
        if (Validate::isLoadedObject($this->context->customer)) {
            $this->context->customer->logged = true; // copied from config.inc.php
        }

        self::$initialized = true;
    }

    /**
     * Create default Cart object.
     *
     * @return PosCart an empty object for PosCart
     */
    protected function createEmptyCart()
    {
        $cart = new PosCart();
        $cart->id_lang = (int) $this->context->language->id;
        $cart->id_currency = (int) $this->context->currency->id;
        $cart->id_shop_group = (int) $this->context->shop->id_shop_group;
        $cart->id_shop = (int) $this->context->shop->id;
        $default_customer = new Customer(PosCustomer::getDefaultCustomerIds($this->context->cookie->pos_id_employee, $this->context->shop->id));
        $cart->id_customer = configuration::get('POS_GUEST_CHECKOUT') ? (int) $default_customer->id : 0;
        $cart->id_address_delivery = (int) Address::getFirstCustomerAddressId((int) $cart->id_customer);
        $cart->id_address_invoice = (int) $cart->id_address_delivery;
        $cart->id_carrier = 0;
        return $cart;
    }

    /**
     * @param int  $id_product
     * @param int  $id_product_attribute
     * @param Shop $shop
     * @param int  $qty
     *
     * @return bool
     * @throw Exception
     */
    public function addProductToCart($id_product, $id_product_attribute = 0, $shop = null, $qty = 0)
    {
        $success = true;
        if (!$shop) {
            $shop = $this->context->shop;
        }
        if (!Validate::isLoadedObject($this->context->cart)) {
            if ($this->context->cart->save()) {
                PosActiveCart::push($this->context->cart->id, $this->context->employee->id, $this->context->shop->id, 0);
                $sales_commission = new PosSalesCommission((int) $this->context->cart->id);
                $sales_commission->save();
                $this->context->cookie->pos_id_cart = $this->context->cart->id;
                $this->context->cookie->write();
            }
        }
        if (Validate::isLoadedObject($this->context->cart)) {
            if (!PosActiveCart::doesExistPosCart($this->context->cart->id, $this->context->shop->id)) {
                PosActiveCart::push($this->context->cart->id, $this->context->employee->id, $this->context->shop->id, 0);
            }
            $quantity = $qty ? $qty : $this->context->cart->getMinimalQuantityToBeAdded($id_product, $id_product_attribute);
            $success = $this->context->cart->updateQtyPos($quantity, $id_product, $shop, $id_product_attribute);
            if ($this->context->cart->id_carrier == 0) {
                $this->setDefaultCarrier();
            }
        }
        return $success;
    }

    public function setDefaultCarrier()
    {
        $pos_carrier = PosCarrier::getPosCarrier();
        $id_default_carrier = (int) Configuration::get('POS_ID_DEFAULT_CARRIER');
        if ($id_default_carrier) {
            $deliver_option = array(
                $this->context->cart->id_address_delivery => $id_default_carrier . ','
            );
        } else {
            $carriers = $this->context->cart->getCarriers($this->context->language->id);
            $id_carrier = 0;
            foreach ($carriers as $carrier) {
                if ($carrier['id_carrier'] !== $pos_carrier->id && $id_carrier == 0) {
                    $id_carrier = (int) $carrier['id_carrier'];
                }
            }
            $deliver_option = array(
                $this->context->cart->id_address_delivery => $id_carrier . ','
            );
        }
        $this->context->cart->setDeliveryOption($deliver_option);
        $this->context->cart->update();
    }

    /**
     * @return array
     *               <pre>
     *               array(
     *               array(
     *               'value' => string, //amount, percentage, etc...
     *               'name' => string
     *               )
     *               )
     */
    public function getProductDiscountTypes()
    {
        $default = Configuration::get('POS_DEF_PRODUCT_DISCOUNT_TYPE');
        $default = !empty($default) ? trim($default) : PosConstants::DISCOUNT_TYPE_PERCENTAGE;

        return array(
            array(
                'value' => PosConstants::DISCOUNT_TYPE_PERCENTAGE,
                'name' => '%',
                'is_default' => ($default == PosConstants::DISCOUNT_TYPE_PERCENTAGE),
            ),
            array(
                'value' => PosConstants::DISCOUNT_TYPE_AMOUNT,
                'name' => $this->i18n['amount'],
                'is_default' => ($default == PosConstants::DISCOUNT_TYPE_AMOUNT),
            ),
        );
    }

    /**
     * @return array
     *               <pre>
     *               array(
     *               array(
     *               'value' => string, //amount, percentage, etc...
     *               'name' => string
     *               )
     *               )
     */
    public function getOrderDiscountTypes()
    {
        $default = Configuration::get('POS_DEF_ORDER_DISCOUNT_TYPE');
        $default = !empty($default) ? trim($default) : PosConstants::DISCOUNT_TYPE_PERCENTAGE;

        return array(
            array(
                'value' => PosConstants::DISCOUNT_TYPE_PERCENTAGE,
                'name' => '%',
                'is_default' => ($default == PosConstants::DISCOUNT_TYPE_PERCENTAGE),
            ),
            array(
                'value' => PosConstants::DISCOUNT_TYPE_AMOUNT,
                'name' => $this->i18n['amount'],
                'is_default' => ($default == PosConstants::DISCOUNT_TYPE_AMOUNT),
            ),
            array(
                'value' => PosConstants::DISCOUNT_TYPE_VOUCHER,
                'name' => $this->i18n['voucher'],
                'is_default' => ($default == PosConstants::DISCOUNT_TYPE_VOUCHER),
            ),
        );
    }

    /**
     * @return array
     *  <pre>
     *    array(
     *       array(
     *          'value' => string,
     *          'label' => string
     *          'is_default' => bool
     *       )
     * ...
     *    )
     */
    public function getProductSearchBy()
    {
        $default = Configuration::get('POS_PRODUCT_SEARCH_BY');
        $product_search = array(
            array(
                'value' => PosConstants::SEARCH_GENERAL,
                'name' => $this->i18n['general'],
                'is_default' => ($default == PosConstants::SEARCH_GENERAL),
            ),
            array(
                'value' => PosConstants::SEARCH_BY_ID,
                'name' => $this->i18n['id'],
                'is_default' => ($default == PosConstants::SEARCH_BY_ID),
            ),
            array(
                'value' => PosConstants::SEARCH_BY_NAME,
                'name' => $this->i18n['name'],
                'is_default' => ($default == PosConstants::SEARCH_BY_NAME),
            ),
            array(
                'value' => PosConstants::SEARCH_BY_REFERENCE,
                'name' => $this->i18n['reference'],
                'is_default' => ($default == PosConstants::SEARCH_BY_REFERENCE),
            ),
            array(
                'value' => PosConstants::SEARCH_BY_BARCODE,
                'name' => $this->i18n['barcode'],
                'is_default' => ($default == PosConstants::SEARCH_BY_BARCODE),
            ),
            array(
                'value' => PosConstants::SEARCH_BY_SHORT_DESCRIPTION,
                'name' => $this->i18n['short_description'],
                'is_default' => ($default == PosConstants::SEARCH_BY_SHORT_DESCRIPTION),
            ),
            array(
                'value' => PosConstants::SEARCH_BY_DESCRIPTION,
                'name' => $this->i18n['description'],
                'is_default' => ($default == PosConstants::SEARCH_BY_DESCRIPTION),
            ),
            array(
                'value' => PosConstants::SEARCH_BY_TAG,
                'name' => $this->i18n['tag'],
                'is_default' => ($default == PosConstants::SEARCH_BY_TAG),
            ),
            array(
                'value' => PosConstants::SEARCH_BY_ATTRIBUTE,
                'name' => $this->i18n['attribute'],
                'is_default' => ($default == PosConstants::SEARCH_BY_ATTRIBUTE),
            ),
            array(
                'value' => PosConstants::SEARCH_BY_FEATURE,
                'name' => $this->i18n['feature'],
                'is_default' => ($default == PosConstants::SEARCH_BY_FEATURE),
            ),
            array(
                'value' => PosConstants::SEARCH_BY_CATEGORY,
                'name' => $this->i18n['category'],
                'is_default' => ($default == PosConstants::SEARCH_BY_CATEGORY),
            ),
            array(
                'value' => PosConstants::SEARCH_BY_MANUFACTURER,
                'name' => $this->i18n['manufacturer'],
                'is_default' => ($default == PosConstants::SEARCH_BY_MANUFACTURER),
            ),
        );
        return $product_search;
    }

    /**
     *
     * @return int
     */
    public function getDefaultCustomerId($id_shop = null)
    {
        if ($id_shop === null) {
            $id_shop = $this->context->shop->id;
        }
        return (int) PosCustomer::getDefaultCustomer($id_shop)->id;
    }

    /**
     *
     * @return string
     */
    public function getFileVersion()
    {
        return defined('_PS_MODE_DEV_') && _PS_MODE_DEV_ ? time() : $this->version;
    }

    /**
     * @return bool
     */
    public function clearSession()
    {
        unset($this->context->cookie->pos_id_cart);
        unset($this->context->cookie->id_currency);
        unset($this->context->cookie->id_pos_exchange);
        $this->context->currency = new PosCurrency();
        return true;
    }

    /**
     * @return int
     */
    public function getDefaultOrderState()
    {
        $amountDue = $this->context->cart->getAmountDue($this->context->currency->decimals * _PS_PRICE_DISPLAY_PRECISION_);
        $enable_payment = (bool) Configuration::get('POS_COLLECTING_PAYMENT');
        $default_order_state = 0;
        if ($enable_payment && $amountDue > 0) {
            $default_order_state = Configuration::get('POS_DEFAULT_PRE_ORDER_STATE');
        } else {
            $default_order_state = Configuration::get('POS_DEFAULT_STANDARD_ORDER_STATE');
        }

        return (int) $default_order_state;
    }

    /**
     *
     * @return array | null
     * <pre>
     * array(
     *      id_address => int,
     *      id_country => int,
     *      id_state => int,
     *      id_customer => int,
     *      id_manufacturer => int,
     *      id_supplier => int,
     *      id_warehouse => int,
     *      alias => string,
     *      company => string,
     *      lastname => string,
     *      firstname => string,
     *      address1 => string,
     *      address2 => string,
     *      postcode => string,
     *      city => string,
     *      other => string,
     *      phone => string,
     *      phone_mobile => string,
     *      vat_number => string,
     *      dni => string,
     *      date_add => datetime,
     *      date_upd => datetime,
     *      active => int,
     *      deleted => int,
     *      country => string,
     *      state => string,
     *      state_iso => string
     * )
     */
    public function getDefaultAddress()
    {
        $default_address = array();
        $default_customer_id = PosCustomer::getDefaultCustomerIds($this->context->cookie->pos_id_employee, $this->context->shop->id);
        $default_customer = new Customer((int) $default_customer_id);
        if (Validate::isLoadedObject($default_customer)) {
            $addresses = $default_customer->getAddresses($this->context->language->id);
            if (count($addresses)) {
                $default_address = $addresses[0];
            }
        }
        return $default_address;
    }

    /**
     * Dedicated callback to upgrading process.
     *
     * @param type $version
     *
     * @return bool
     */
    public function upgrade($version)
    {
        $success = array();
        switch ($version) {
            default:
                $upgrader_class = 'PosUpgrader' . str_replace('.', '', $version);
                if (class_exists($upgrader_class)) {
                    $upgrader = new $upgrader_class($this, $version);
                    $success[] = $upgrader->run();
                }
                break;
        }

        return array_sum($success) >= count($success);
    }

    /**
     * Init a new session based on a cart ID.
     *
     * @param int $id_cart
     *
     * @return bool
     */
    public function initSession($id_cart, $id_exchange = 0)
    {
        $this->clearSession();
        $cart = new PosCart((int) $id_cart);
        if (!Validate::isLoadedObject($cart)) {
            return false;
        }
        $this->context->cookie->pos_id_cart = $cart->id;
        $this->context->cookie->id_currency = $cart->id_currency;
        if ($id_exchange != 0) {
            $this->context->cookie->id_pos_exchange = (int) $id_exchange;
        }

        return true;
    }

    /**
     *
     * @param string $token
     * @return boolean
     */
    public function setToken($token)
    {
        $cookie = Context::getContext()->cookie;
        $cookie->token = $token;
        return $cookie->write();
    }

    /**
     *
     * @param string $token
     * @return boolean
     */
    public function validateToken($token)
    {
        $is_valid_token = false;
        $cookie_lifetime = (int) Configuration::get('PS_COOKIE_LIFETIME_BO');
        if ($cookie_lifetime > 0) {
            $cookie_lifetime = time() + (max($cookie_lifetime, 1) * 3600);
        }
        $bo_cookie = new Cookie('psAdmin', '', $cookie_lifetime);
        if ($token == $bo_cookie->token) {
            if (isset($bo_cookie->id_employee) && $bo_cookie->id_employee !== 0) {
                unset($bo_cookie->token);
                $bo_cookie->write();
                $this->context->cookie->pos_id_employee = (int) $bo_cookie->id_employee;
            }

            $is_valid_token = true;
        }
        return $is_valid_token;
    }

    /**
     *
     * @return array
     * <pre>
     * array(
     *  id_lang => int,
     *  name => string,
     *  is_default => boolean
     * )
     */
    public function getAvailableLanguages($active = true)
    {
        $language_fields = array(
            'id_lang',
            'iso_code',
            'name',
            'is_default'
        );
        $languages = Language::getLanguages($active);
        foreach ($languages as &$language) {
            $language['is_default'] = (int) ($language['id_lang'] == Configuration::get('PS_LANG_DEFAULT'));
            foreach (array_keys($language) as $key) {
                if (!in_array($key, $language_fields)) {
                    unset($language[$key]);
                }
            }
        }
        return $languages;
    }

    /**
     *
     * @param PosCart $cart
     * @return int
     */
    public function getOrderShippingCostExternal($cart)
    {
        $this->getOrderShippingCost($cart);
    }

    /**
     *
     * @param PosCart $cart
     * @param float $shipping_cost
     * @return false | float
     */
    public function getOrderShippingCost($cart, $shipping_cost)
    {
        $shipping = false;
        if ($cart instanceof PosCart) {
            $shipping = (float) PosActiveCart::getShippingCost($cart->id);
        }
        return $shipping;
    }

    /**
     *
     * @param PosOrder $order
     * @return array
     * <pre>
     * array(
     *  int => array(
     *      payment_method => string
     *      amount => string
     *      date_add => string
     *  )
     *
     * )
     */
    public function getOrderPayments($order)
    {
        $order_payments = array();
        if (!Validate::isLoadedObject($order)) {
            return $order_payments;
        }
        $properties = array(
            'payment_method',
            'amount',
            'date_add',
        );
        $results = $order->getOrderPayments();
        if (!empty($results)) {
            $currency = new PosCurrency($order->id_currency);
            foreach ($results as $index => $order_payment) {
                foreach ($properties as $key) {
                    if ($key == 'payment_method') {
                        $order_payments[$index]['name'] = $order_payment->{$key};
                    } else if ($key == 'amount') {
                        $order_payments[$index][$key] = $order_payment->{$key};
                    } else {
                        $order_payments[$index][$key] = $order_payment->{$key};
                    }
                }
            }
        }
        return $order_payments;
    }

    /**
     *
     * @param PosOrder $order
     * @return array
     * <pre />
     * array(
     *  int => array(
     *     tax_rate => float
     *     tax_amount => string
     *  )
     * )
     */
    public function getTaxDetails(PosOrder $order)
    {
        $tax_details = array();
        $taxes = $order->getTaxes();
        $currency = new PosCurrency($order->id_currency);
        if (!empty($taxes)) {
            foreach ($taxes as $tax) {
                $tax_details[] = array(
                    'tax_rate' => (float) $tax['tax_rate'],
                    'tax_amount' => Tools::displayPrice($tax['total_amount'], $currency, false),
                );
            }
        }
        return $tax_details;
    }

    /**
     *
     * @param PosOrder $order
     * @param boolean $is_return
     * @param array $exchange_products
     * @param array $return_products
     * @return array
     * <pre />
     * array(
     *  int => array(
     *      product_id => int
     *      product_attribute_id => int
     *      product_ean13 => string
     *      product_upc => string
     *      product_price => float
     *      original_product_price => float
     *      name => string
     *      combination => string
     *      manufacturer_name => string
     *      reference => string
     *      product_unit_price => string
     *      product_original_price => string
     *      product_total_price => string
     *      quantity => int
     *  )
     *
     * )
     */
    public function getProducts($products, PosOrder $order, $is_return = false, array $exchange_products = array(), array $return_products = array())
    {
        $use_tax = Configuration::get('PS_TAX') && !Product::getTaxCalculationMethod((int) $order->id_customer);
        $currency = new PosCurrency($order->id_currency);
        $format_products = array();
        $properties = array(
            'product_id',
            'product_attribute_id',
            'product_quantity',
            'product_price',
            'product_price_wt',
            'original_product_price',
            'product_price_tax_incl',
            'total_price',
            'total_wt',
            'name',
            'combination',
            'manufacturer_name',
            'product_ean13',
            'product_upc',
            'product_reference',
            'return',
            'exchange'
        );
        if (!empty($return_products)) {
            $products = $return_products;
        }
        foreach ($products as &$product) {
            $product['exchange'] = false;
            $product['return'] = $is_return;
        }
        if (!empty($exchange_products)) {
            foreach ($exchange_products as &$exchange_product) {
                $exchange_product['exchange'] = true;
                $exchange_product['return'] = false;
            }
            $products = array_merge($products, $exchange_products);
        }
        foreach ($products as &$product) {
            foreach ($product as $key => $value) {
                if (in_array($key, $properties)) {
                    continue;
                }
                unset($product[$key]);
            }
            $unit_price = $use_tax ? $product['product_price_wt'] : $product['product_price'];
            $original_price = $use_tax ? $product['product_price_tax_incl'] : $product['original_product_price'];
            $total_price = $use_tax ? $product['product_price_wt'] * $product['product_quantity'] : $product['product_price'] * $product['product_quantity'];
            $product['product_unit_price'] = Tools::displayPrice($unit_price, $currency, false);
            $product['product_original_price'] = Tools::displayPrice($original_price, $currency, false);
            $product['product_total_price'] = Tools::displayPrice($total_price, $currency, false);
            $product['quantity'] = $product['product_quantity'];
            $product['reference'] = $product['product_reference'];
            $product['tax_amount'] = $product['product_price_wt'] - $product['product_price'];
            $product['name'] = PosProduct::getProductNames($product['product_id'], $this->context->language->id);
            unset($product['product_quantity']);
            unset($product['product_price_wt']);
            unset($product['product_price_tax_incl']);
            unset($product['total_price']);
            unset($product['total_wt']);
            unset($product['product_reference']);
            $format_products[] = $product;
        }
        return $format_products;
    }

    /**
     *
     * @param collections PosOrder
     * @return string
     */
    public function getTotalOrderDiscount($order_collections)
    {
        $total_order_discount = 0;
        foreach ($order_collections as $order) {
            $order_discounts = $order->getCartRules();
            if (!empty($order_discounts)) {
                foreach ($order_discounts as $discount) {
                    $total_order_discount += (float) $discount['value'];
                }
            }
        }
        $order = $order_collections->getFirst();
        $currency = new PosCurrency($order->id_currency);
        return Tools::displayPrice($total_order_discount, $currency, false);
    }

    /**
     *
     * @param array $products // see function getProducts
     * @param PosCurrency $currency
     * @return string
     */
    public function getTotalProductDiscount(array $products, $currency)
    {
        $total_product_discount = 0;
        foreach ($products as $product) {
            $total_product_discount += (float) $product['original_product_price'] - (float) $product['product_price'];
        }
        return Tools::displayPrice($total_product_discount, $currency, false);
    }

    /**
     *
     * @param array $products // see function getProducts
     * @return int
     */
    public function getNbProducts(array $products)
    {
        $quantity = 0;
        foreach ($products as $product) {
            $quantity += (float) $product['quantity'];
        }
        return $quantity;
    }

    /**
     *
     * @return array
     */
    public function getAvailableFields()
    {
        return array(
            'shop' => array(
                'logo' => $this->i18n['logo'],
                'shop_name' => $this->i18n['shop_name'],
                'phone' => $this->i18n['phone'],
                'fax' => $this->i18n['fax'],
                'register_number' => $this->i18n['register_number'],
                'city' => $this->i18n['city'],
                'website_url' => $this->i18n['website_url'],
                'shop_address' => $this->i18n['shop_address'],
                'state' => $this->i18n['state'],
                'zipcode' => $this->i18n['zipcode'],
            ),
            'order' => array(
                'order_reference' => $this->i18n['reference_text'],
                'reference_barcode' => $this->i18n['reference_barcode'],
                'date_add' => $this->i18n['order_creation'],
            ),
            'customer' => array(
                'customer_name' => $this->i18n['customer_name'],
                'delivery_address' => $this->i18n['delivery_address'],
                'loyalty' => $this->i18n['loyalty'],
                'vouchers' => $this->i18n['vouchers']
            ),
            'employee' => array(
                'cashier' => $this->i18n['cashier'],
                'salesperson' => $this->i18n['salesperson'],
            ),
            'product' => array(
                'name' => $this->i18n['name'],
                'reference' => $this->i18n['reference'],
                'upc' => $this->i18n['upc'],
                'ean13' => $this->i18n['ean13'],
                'combination' => $this->i18n['combination'],
                'manufacturer' => $this->i18n['manufacturer'],
            ),
            'summary' => array(
                'subtotal' => $this->i18n['sub_total'],
                'total_product_discount' => $this->i18n['total_product_discount'],
                'total_order_discount' => $this->i18n['total_order_discount'],
                'total_items' => $this->i18n['total_items'],
                'total' => $this->i18n['total'],
                'tax' => $this->i18n['tax'],
                'shipping' => $this->i18n['shipping'],
                'payment' => $this->i18n['payment'],
            ),
            'others' => array(
                'title' => $this->i18n['title'],
                'signature' => $this->i18n['signature'],
                'header_text' => $this->i18n['header_text'],
                'footer_text' => $this->i18n['footer_text'],
                'line_break' => $this->i18n['line_break'],
                'divider' => $this->i18n['divider'],
                'note' => $this->i18n['note'],
            ),
        );
    }

    /**
     * @param Collecttion PosOrder
     * @param boolean $is_return
     * @param array $exchange_products
     * @param array $return_products
     * @return array
     * <pre >
     * array(
     *      logo => string
     *      shop_name => string
     *      phone => string
     *      fax => string
     *      register_number => string
     *      website_url => string
     *      shop_address => string
     *      state => string
     *      zipcode => string
     *      order_reference => string
     *      date_add => string
     *      cashier => string
     *      salesman => array(
     *          int => string
     *      )
     *      title => string
     *      header_text => string
     *      footer_text => string
     *      customer_name => string
     *      loyalty => array(
     *          points => int
     *          value => string
     *      )
     *      delivery_address => array(
     *          int => string
     *      )
     *      shipping => string
     *      subtotal => string
     *      total => string
     *      total_items => string
     *      total_product_discount => string
     *      total_order_discount => string
     *      tax_detail => array(
     *          int => array(
     *              tax_rate => int
     *              amout => float
     *          )
     *      )
     *      payments => array(
     *          int => array(
     *              name => string
     *              amount => string
     *              date_add => string
     *          )
     *      )
     *      product => array(
     *          int => array(
     *              product_id => int
     *              product_attribute_id => int
     *              product_price => int
     *              product_ean13 => int
     *              product_upc => int
     *              original_product_price => int
     *              name => int
     *              combination => int
     *              manufacturer_name => int
     *              product_original_price => int
     *              product_total_price => int
     *              quantity => int
     *              reference => int
     *          )
     *      )
     * )
     */
    public function getReceiptData($order_collections, $is_return = false, array $exchange_products = array(), array $return_products = array())
    {
        $amount_due = 0;
        $total_paid = 0;
        $total_paid_real = 0;
        $total_shipping_tax_incl = 0;
        $total_discounts_tax_excl = 0;
        $total_products_wt = 0;
        $total_products = 0;
        $total_discounts_tax_excl = 0;
        $products = array();
        $taxes = array();
        foreach ($order_collections as $order) {
            $total_products_wt += $order->total_products_wt;
            $total_products += $order->total_products;
            $total_paid += $order->total_paid;
            $total_paid_real += $order->total_paid_real;
            $total_shipping_tax_incl += $order->total_shipping_tax_incl;
            $total_discounts_tax_excl += $order->total_discounts_tax_excl;
            $products = array_merge($products, $order->getProducts());
            $taxes = array_merge($order->getTaxes());
        }

        $order = $order_collections->getFirst();
        $customer = new PosCustomer($order->id_customer);
        $address = new PosAddress($order->id_address_delivery);
        $currency = new PosCurrency($order->id_currency);

        $products = $this->getProducts($products, $order, $is_return, $exchange_products, $return_products);
        $loyalty = PosLoyalty::getLoyalty($customer, $this->context);
        $sales_person = PosReceipt::getSalesmanInfo($order->reference);
        $note = PosActiveCart::getNote($order->id_cart, $order->id_shop, $order->reference);

        $vouchers = $this->getAvailableCartRules();
        $vouchers_used = $this->getCartRules();
        $id_used_vouchers = array();
        foreach ($vouchers_used as $voucher_used) {
            $id_used_vouchers[] = $voucher_used['id_cart_rule'];
        }
        foreach ($vouchers as $key => $voucher) {
            if (in_array($voucher['id_cart_rule'], $id_used_vouchers)) {
                unset($vouchers[$key]);
            }
        }
        $use_tax = Configuration::get('PS_TAX') && !Product::getTaxCalculationMethod((int) $order->id_customer);
        return array(
            'logo' => Tools::getShopDomain() . _PS_IMG_ . PosReceipt::getLogoFileName(),
            'canvas_width'=> PosReceipt::getLogoWidth(),
            'pdf_page_size' => PosReceipt::getPdfPageSize(),
            'shop_name' => Configuration::get('PS_SHOP_NAME') ? Configuration::get('PS_SHOP_NAME') : '',
            'phone' => Configuration::get('PS_SHOP_PHONE', null, null, $this->context->shop->id) ? Configuration::get('PS_SHOP_PHONE', null, null, $this->context->shop->id) : '',
            'fax' => Configuration::get('PS_SHOP_FAX', null, null, $this->context->shop->id) ? Configuration::get('PS_SHOP_FAX', null, null, $this->context->shop->id) : '',
            'city' => Configuration::get('PS_SHOP_CITY', null, null, $this->context->shop->id) ? Configuration::get('PS_SHOP_CITY', null, null, $this->context->shop->id) : '',
            'register_number' => Configuration::get('PS_SHOP_DETAILS', null, null, $this->context->shop->id) ? Configuration::get('PS_SHOP_DETAILS', null, null, $this->context->shop->id) : '',
            'website_url' => Tools::getShopDomain(),
            'shop_address' => Configuration::get('PS_SHOP_ADDR1', null, null, $this->context->shop->id) ? Configuration::get('PS_SHOP_ADDR1', null, null, $this->context->shop->id) : '',
            'state' => State::getNameById(Configuration::get('PS_SHOP_STATE_ID', null, null, $this->context->shop->id)) ? State::getNameById(Configuration::get('PS_SHOP_STATE_ID', null, null, $this->context->shop->id)) : '',
            'zipcode' => Configuration::get('PS_SHOP_CODE', null, null, $this->context->shop->id) ? Configuration::get('PS_SHOP_CODE', null, null, $this->context->shop->id) : '',
            'order_reference' => $order->reference,
            'date_add' => $order->date_add,
            'cashier' => PosReceipt::getCashierInfo($this->context->employee),
            'salesperson' => implode(' ', $sales_person),
            'title' => $this->i18n['receipt'],
            'header_text' => Configuration::get('POS_RECEIPT_HEADER_TEXT', $this->context->language->id) ? Configuration::get('POS_RECEIPT_HEADER_TEXT', $this->context->language->id) : '',
            'footer_text' => Configuration::get('POS_RECEIPT_FOOTER_TEXT', $this->context->language->id) ? Configuration::get('POS_RECEIPT_FOOTER_TEXT', $this->context->language->id) : '',
            'customer_name' => implode(' ', array($customer->firstname, $customer->lastname)),
            'loyalty' => $this->i18n['loyalty'] . ': ' . Tools::displayPrice($loyalty['value'], $currency, false) . ' (' . $loyalty['points'] . ' ' . ($loyalty['points'] > 1 ? $this->i18n['points'] : $this->i18n['point']) . ')',
            'delivery_address' => explode('<br />', PosAddressFormat::generateAddress($address, array(), '<br />', ' ')),
            'shipping' => Tools::displayPrice($total_shipping_tax_incl, $currency),
            'subtotal' => Tools::displayPrice($use_tax ? $total_products_wt : $total_products, $currency),
            'total' => Tools::displayPrice($total_paid, $currency, false),
            'payments' => $this->getOrderPayments($order),
            'product' => $products,
            'total_items' => $this->getNbProducts($products),
            'total_product_discount' => $this->getTotalProductDiscount($products, $currency),
            'total_order_discount' => $this->getTotalOrderDiscount($order_collections),
            'tax_detail' => $this->getTaxDetails($order),
            'note' => $note['public'] ? $note['note'] : null,
            'vouchers' => $vouchers
        );
    }

    /**
     *
     * @param array $products
     * @return array
     * <pre>
     * array(
     *  int => array(
     *      product_id => int
     *      product_attribute_id => int
     *      product_quantity => int
     *      product_price => float
     *      original_product_price => float
     *      total_price_tax_incl => float
     *      total_price_tax_excl => float
     *      name => string
     *      combination => string
     *  )
     * ...
     * )
     */
    public function formatProducts(array $products)
    {
        $properties = array(
            'product_id',
            'product_attribute_id',
            'name',
            'combination',
            'product_quantity',
            'product_price',
            'original_product_price',
            'total_price_tax_incl',
            'total_price_tax_excl',
        );
        $format_products = array();
        if (!empty($products)) {
            foreach ($products as &$product) {
                foreach ($product as $key => $value) {
                    $combination = '';
                    if ($key == 'product_name') {
                        if ((int) $product['product_attribute_id'] > 0) {
                            $product_name = explode('-', $value);
                            if (count($product_name) >= 2) {
                                $combination = array_pop($product_name);
                            }
                            $product['combination'] = $combination;
                        } else {
                            $product['combination'] = $combination;
                        }
                        $product['name'] = PosProduct::getProductNames($product['product_id'], $this->context->language->id);
                    }
                    if (in_array($key, $properties)) {
                        continue;
                    }
                    unset($product[$key]);
                }
                $format_products[] = $product;
            }
        }
        return $format_products;
    }

    /**
     *
     * @return array
     * <pre>
     * array(
     *  array(
     *      'id_cart_rule' => int,
     *      'code' => string,
     *      'name' => string
     *      'reduction_percent' => float,
     *      'reduction_amount' => float,
     *      'reduction_currency' => int,
     *  )
     * )
     */
    public function getAvailableCartRules()
    {
        $fields = array(
            'id_cart_rule',
            'name',
            'code',
            'reduction_percent',
            'reduction_amount',
            'reduction_currency',
            'date_add'
        );
        $available_cart_rules = CartRule::getCustomerCartRules($this->context->language->id, (isset($this->context->customer->id) ? $this->context->customer->id : 0), true, true, true, $this->context->cart, false, false);
        foreach ($available_cart_rules as $key => $available_cart_rule) {
            if ($available_cart_rule['free_shipping']) {
                unset($available_cart_rules[$key]);
            }
            foreach (array_keys($available_cart_rule) as $cart_rule_key) {
                if (!in_array($cart_rule_key, $fields)) {
                    unset($available_cart_rules[$key][$cart_rule_key]);
                }
            }
        }
        return array_values($available_cart_rules);
    }

    /**
     *
     * @return array
     */
    public function getCartRules()
    {
        $cart_rules = array();
        if (Validate::isLoadedObject($this->context->cart)) {
            $cart_rules = $this->context->cart->getCartRules(CartRule::FILTER_ACTION_REDUCTION);
            $properties = array(
                'id_cart_rule',
                'name',
                'code',
                'reduction_percent',
                'reduction_amount',
                'discounted_value',
            );
            foreach ($cart_rules as &$cart_rule) {
                $cart_rule['discounted_value'] = $cart_rule['value_tax_exc'];
                foreach (array_keys($cart_rule) as $key) {
                    if (!in_array($key, $properties)) {
                        unset($cart_rule[$key]);
                    }
                }
            }
        }

        return $cart_rules;
    }

    /**
     *
     * @return array
     * <pre />
     * array(
     *  fullname => string
     * )
     */
    public function getEmployeeInfo()
    {
        return array(
            'fullname' => isset($this->context->employee) ? implode(' ', array($this->context->employee->firstname, $this->context->employee->lastname)) : null,
        );
    }
    
    
    /**
     *
     * @return array
     * <pre />
     * array(
     *  int => array(
     *      name => string,
     *      value => string
     *      checked => boolean
     *  )
     * )
     */
    public function getReceiptPageSizes()
    {
        return array(
            array(
               'name' => $this->i18n['k80'],
               'value' => 'K80',
               'checked' => Configuration::get('POS_RECEIPT_PAGE_SIZE') == 'K80',
            ),
            array(
               'name' => $this->i18n['k57'],
               'value' => 'K57',
               'checked' => Configuration::get('POS_RECEIPT_PAGE_SIZE') == 'K57',
            ),
        );
    }
    
    /**
     *
     * @return boolean
     */
    public function isDeviceComputer()
    {
        return $this->context->getDevice() == Context::DEVICE_COMPUTER;
    }
}
