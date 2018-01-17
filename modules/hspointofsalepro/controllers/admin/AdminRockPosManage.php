<?php
/**
 * RockPOS - Point of Sale for PrestaShop
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

require_once dirname(__FILE__) . '/AdminRockPosCommon.php';
/**
 * Controller of admin page - Point Of Sale
 */
class AdminRockPosManageController extends AdminRockPosCommon
{

    /**
     * @see parent::setMedia()
     */
    public function setMedia()
    {
        $this->module_media_css['apps/manage.css'] = 'all';
        parent::setMedia();
    }

    /**
     * @input
     * {
     *      lang_keys:[string, string, ...],
     *      action: "getAllInOne",
     *      ajax: "1"
     * }
     * @output
     * <pre>
     * {
     *      success: boolean,
     *      message: string,
     *      data: {
     *          env: {
     *             lang: [
     *                  {
     *                      string: string // key => translation
     *                  }
     *              ],
     *             shop_groups: [
     *                  {
     *                      id: int,
     *                      name: string,
     *                      share_customer: boolean,
     *                      share_stock: boolean,
     *                      shops: [
     *                          {
     *                              id_shop: int,
     *                              id_shop_group: int,
     *                              name: string,
     *                              id_category: int,
     *                              theme_name: string,
     *                              domain: string,
     *                              domain_ssl: string,
     *                              uri: string,
     *                              active: boolean
     *                          }
     *                      ]
     *                  }
     *             ],
     *          },
     *          order: {
     *              standard_order_states: {
     *                  key: POS_SELECTED_STANDARD_ORDER_STATES,
     *                  value: [
     *                      {
     *                          id_order_state: int,
     *                          name: string,
     *                          checked: int,
     *                      }
     *                  ]
     *              }
     *              pre_order_states: {
     *                   key => 'POS_SELECTED_PRE_ORDER_STATES',
     *                   value => [
     *                      {
     *                          id_order_state: int,
     *                          name: string,
     *                          checked: int,
     *                      }
     *                   ]
     *               },
     *              default_standard_order_state: {
     *                   key => POS_DEFAULT_STANDARD_ORDER_STATE,
     *               },
     *               default_pre_order_state: {
     *                   key: POS_DEFAULT_PRE_ORDER_STATE,
     *               },
     *              default_order_discount: {
     *                   key: POS_DEF_ORDER_DISCOUNT_TYPE,
     *                   value: [
     *                      {
     *                          value: string,
     *                          name: string,
     *                          is_default: int,
     *                      }
     *                  ],
     *               },
     *               list_pos_orders_under_bo_orders: {
     *                   key: POS_SHOW_ORDERS_UNDER_PS_ORDERS,
     *                   value: int,
     *               },
     *          }
     *          customer: {
     *               guest_checkout: {
     *                   key: POS_GUEST_CHECKOUT,
     *                   value: int
     *               },
     *               guest_account: {
     *                  id: int,
     *                  firstname: string,
     *                  lastname: string,
     *                  email: string,
     *                  edit_link: string,
     *              }
     *               guest_checkout_warning:{
     *                   key: POS_SHOW_WARNING_GUEST_CHECKOUT,
     *                   value: int
     *               },
     *               'emailing' => [
     *                   {
     *                       key: POS_EMAILING_ACCOUNT_CREATION,
     *                       value: int
     *                   },
     *                   {
     *                       key => POS_EMAILING_ORDER_COMPLETION,
     *                       value: int
     *                   },
     *               ],
     *           },
     *           payment: {
     *              enable_payment: {
     *                  key: POS_COLLECTING_PAYMENT,
     *                  value: int
     *              },
     *              payment_types: [
     *                  {
     *                      id_pos_payment: int,
     *                      position: int,
     *                      active: int,
     *                      payment_name: [
     *                          int: string
     *                      ],
     *                      shop:[
     *                          int => int,
     *                      ]
     *                  }
     *              ],
     *           }
     *
     *      }
     * }
     * </pre>
     */
    public function ajaxProcessGetAllInOne()
    {
        $missing_indexing_url_params = array(
            'token' => Tools::encrypt($this->module->name),
        );
        if (Shop::getContext() == Shop::CONTEXT_SHOP) {
            $missing_indexing_url_params['id_shop'] = $this->context->shop->id;
        }
        $full_indexing_url_params = array_merge($missing_indexing_url_params, array('full' => true));
        $lang_keys = Tools::jsonDecode(Tools::getValue('lang_keys'), true);
        $sample_customer = PosCustomer::getSampleCustomer();
        $sample_summary = PosCart::getSampleSummary();
        $this->ajax_json = array(
            'success' => true,
            'message' => $this->module->i18n['all_set'],
            'data' => array(
                'env' => array(
                    'lang' => $lang_keys ? $this->module->getTranslations($lang_keys) : array(),
                    'shop_groups' => Shop::getTree(),
                    'languages' => $this->module->getAvailableLanguages(false),
                    'home_url' => $this->context->link->getAdminLink('AdminDashboard'),
                    'last_version' => $this->module->version,
                    'order_discount_type' => $this->module->getOrderDiscountTypes(),
                    'product_price' => array('mrp' => $this->module->i18n['mrp'], 'sp' => $this->module->i18n['sp']),
                    'carriers' => PosCarrier::getCarriers($this->context->language->id, true, false, false, null, Carrier::ALL_CARRIERS),
                    'currency' => PosCurrency::getCurrentCurrency($this->context->shop->id),
                    'is_device_computer' => $this->module->isDeviceComputer()
                ),
                'setup' => array(
                    'commissions' => array(
                        'profiles' => PosProfile::getProfiles($this->context->language->id),
                        'selected_profiles' => array(
                            'key' => 'POS_ID_PROFILES',
                            'value' => Configuration::get('POS_ID_PROFILES'),
                        ),
                        'commission_base' => array(
                            'key' => 'POS_COMMISSION_BASE',
                            'value' => Configuration::get('POS_COMMISSION_BASE'),
                        ),
                        'commission_structure' => array(
                            'key' => 'POS_COMMISSION_STRUCTURE',
                            'value' => Configuration::get('POS_COMMISSION_STRUCTURE'),
                        ),
                        'straight_value' => array(
                            'key' => 'POS_STRAIGHT_VALUE',
                            'value' => Configuration::get('POS_STRAIGHT_VALUE')
                        ),
                        'img_variables_coming_soon' => $this->module->getImgPath() . '/sales_commission_variables_coming_soon.png',
                        'link_contact_us' => PosConstants::LINK_CONTACT_US,
                    ),
                    'order' => array(
                        'standard_order_states' => array(
                            'key' => 'POS_SELECTED_STANDARD_ORDER_STATES',
                            'value' => PosOrderState::getOrderStates($this->context->language->id),
                        ),
                        'default_standard_order_state' => array(
                            'key' => 'POS_DEFAULT_STANDARD_ORDER_STATE',
                            'value' => Configuration::get('POS_DEFAULT_STANDARD_ORDER_STATE')
                        ),
                        'default_pre_order_state' => array(
                            'key' => 'POS_DEFAULT_PRE_ORDER_STATE',
                            'value' => Configuration::get('POS_DEFAULT_PRE_ORDER_STATE')
                        ),
                        'default_return_order_state' => array(
                            'key' => 'POS_DEFAULT_RETURN_ORDER_STATE',
                            'value' => Configuration::get('POS_DEFAULT_RETURN_ORDER_STATE')
                        ),
                        'default_order_discount' => array(
                            'key' => 'POS_DEF_ORDER_DISCOUNT_TYPE',
                            'value' => $this->module->getOrderDiscountTypes(),
                        ),
                        'list_pos_orders_under_bo_orders' => array(
                            'key' => 'POS_SHOW_ORDERS_UNDER_PS_ORDERS',
                            'value' => (int) Configuration::get('POS_SHOW_ORDERS_UNDER_PS_ORDERS')
                        ),
                        'default_carrier' => array(
                            'key' => 'POS_ID_DEFAULT_CARRIER',
                            'value' => (int) Configuration::get('POS_ID_DEFAULT_CARRIER')
                        ),
                    ),
                    'customer' => array(
                        'guest_checkout' => array(
                            'key' => 'POS_GUEST_CHECKOUT',
                            'value' => Configuration::get('POS_GUEST_CHECKOUT')
                        ),
                        'guest_account' => array(
                            'key' => 'POS_DEFAULT_ID_CUSTOMERS',
                            'value' => PosCustomer::getGuestCustomer()
                        ),
                        'guest_checkout_warning' => array(
                            'key' => 'POS_SHOW_WARNING_GUEST_CHECKOUT',
                            'value' => (int) Configuration::get('POS_SHOW_WARNING_GUEST_CHECKOUT')
                        ),
                        'default_address_alias' => array(
                            'key' => 'POS_ADDRESS_ALIAS',
                            'value' => Configuration::get('POS_ADDRESS_ALIAS')
                        ),
                        'emailing' => array(
                            array(
                                'key' => 'POS_EMAILING_ACCOUNT_CREATION',
                                'value' => (int) Configuration::get('POS_EMAILING_ACCOUNT_CREATION')
                            ),
                            array(
                                'key' => 'POS_EMAILING_ORDER_COMPLETION_STANDARD',
                                'value' => (int) Configuration::get('POS_EMAILING_ORDER_COMPLETION_STANDARD')
                            ),
                            array(
                                'key' => 'POS_EMAILING_ORDER_COMPLETION_GUEST_CHECKOUT',
                                'value' => (int) Configuration::get('POS_EMAILING_ORDER_COMPLETION_GUEST_CHECKOUT')
                            ),
                        ),
                        'loyalty_system' => array(
                            'key' => 'POS_LOYALTY',
                            'value' => $this->getloyaltyList()
                        ),
                    ),
                    'products' => array(
                        'product' => array(
                            array(
                                'key' => 'POS_PRODUCT_ACTIVE',
                                'value' => (int) Configuration::get('POS_PRODUCT_ACTIVE')
                            ),
                            array(
                                'key' => 'POS_PRODUCT_INACTIVE',
                                'value' => (int) Configuration::get('POS_PRODUCT_INACTIVE')
                            ),
                            array(
                                'key' => 'POS_PRODUCT_INSTOCK',
                                'value' => (int) Configuration::get('POS_PRODUCT_INSTOCK')
                            ),
                            array(
                                'key' => 'POS_PRODUCT_OUT_OF_STOCK',
                                'value' => (int) Configuration::get('POS_PRODUCT_OUT_OF_STOCK')
                            ),
                        ),
                        'visibility' => array(
                            array(
                                'key' => 'POS_VISIBILITY_EVERYWHERE',
                                'value' => (int) Configuration::get('POS_VISIBILITY_EVERYWHERE')
                            ),
                            array(
                                'key' => 'POS_VISIBILITY_SEARCH_ONLY',
                                'value' => (int) Configuration::get('POS_VISIBILITY_SEARCH_ONLY')
                            ),
                            array(
                                'key' => 'POS_VISIBILITY_CATALOG_ONLY',
                                'value' => (int) Configuration::get('POS_VISIBILITY_CATALOG_ONLY')
                            ),
                            array(
                                'key' => 'POS_VISIBILITY_NOWHERE',
                                'value' => (int) Configuration::get('POS_VISIBILITY_NOWHERE')
                            ),
                        ),
                        'multiple_languages' => array(
                            'key' => 'POS_MULTIPLE_LANGGUAGES',
                            'value' => is_null(Tools::jsonDecode(Configuration::get('POS_MULTIPLE_LANGGUAGES'), true)) ? array() : Tools::jsonDecode(Configuration::get('POS_MULTIPLE_LANGGUAGES'), true)
                        ),
                        'autocomplete_search' => array(
                            array(
                                'key' => 'POS_SHOW_ID',
                                'value' => (int) Configuration::get('POS_SHOW_ID')
                            ),
                            array(
                                'key' => 'POS_SHOW_REFERENCE',
                                'value' => (int) Configuration::get('POS_SHOW_REFERENCE')
                            ),
                            array(
                                'key' => 'POS_SHOW_STOCK',
                                'value' => (int) Configuration::get('POS_SHOW_STOCK')
                            ),
                            array(
                                'key' => 'POS_SHOW_NAME',
                                'value' => (int) Configuration::get('POS_SHOW_NAME')
                            ),
                        ),
                        'default_search' => array(
                            'key' => 'POS_PRODUCT_SEARCH_BY',
                            'value' => $this->module->getProductSearchBy()
                        ),
                        'default_categories' => array(
                            'key' => 'POS_DEFAULT_CATEGORIES',
                            'value' => PosCategory::getDefaultCategories($this->context->language->id),
                        ),
                        'product_name_length' => array(
                            'key' => 'POS_PRODUCT_NAME_LENGTH',
                            'value' => Configuration::get('POS_PRODUCT_NAME_LENGTH'),
                        ),
                        'image_in_shopping_cart' => array(
                            'key' => 'POS_IMAGES_IN_SHOPPING_CART',
                            'value' => (int) Configuration::get('POS_IMAGES_IN_SHOPPING_CART')
                        ),
                        'auto_indexing' => array(
                            'key' => 'POS_AUTO_INDEXING',
                            'value' => (int) Configuration::get('POS_AUTO_INDEXING')
                        ),
                        'index_status' => array(
                            'status' => PosSearchIndexStats::getTotalIndexedProducts() . '/' . PosSearchIndexStats::getTotalProducts(),
                            'add_missing_index_product_link' => $this->context->link->getAdminLink('AdminRockPosManage') . '&action=index&sub_tab=setup-product',
                            'add_missing_index_product_cron_link' => $this->context->link->getModuleLink($this->module->name, 'searchCron', $missing_indexing_url_params),
                            'rebuild_index_product_link' => $this->context->link->getAdminLink('AdminRockPosManage') . '&action=index&sub_tab=setup-product&full=1',
                            'rebuild_index_product_cron_link' => $this->context->link->getModuleLink($this->module->name, 'searchCron', $full_indexing_url_params),
                        )
                    ),
                    'payment' => array(
                        'enable_payment' => array(
                            'key' => 'POS_COLLECTING_PAYMENT',
                            'value' => (int) Configuration::get('POS_COLLECTING_PAYMENT'),
                        ),
                        'payment_types' => PosPayment::getPaymentTypes(),
                    ),
                    'receipt' => array(
                        '1st_bill_printer' => array(
                            'key' => 'POS_RECEIPT_BILL_PRINTER_1',
                            'value' => Configuration::get('POS_RECEIPT_BILL_PRINTER_1') ? Configuration::get('POS_RECEIPT_BILL_PRINTER_1') : ''
                        ),
                        '2nd_bill_printer' => array(
                            'key' => 'POS_RECEIPT_BILL_PRINTER_2',
                            'value' => Configuration::get('POS_RECEIPT_BILL_PRINTER_2') ? Configuration::get('POS_RECEIPT_BILL_PRINTER_2') : ''
                        ),
                        'print_in_pdf' => array(
                            'key' => 'POS_PRINT_IN_PDF',
                            'value' => (int)Configuration::get('POS_PRINT_IN_PDF')
                        ),
                        'paper_size' => array(
                            'key' => 'POS_RECEIPT_PAGE_SIZE',
                            'value' => $this->module->getReceiptPageSizes()
                        ),
                        'printer_dpi' => array(
                            'key' => 'POS_RECEIPT_PRINTER_DPI',
                            'value' => Configuration::get('POS_RECEIPT_PRINTER_DPI')
                        ),
                        'logo' => array(
                            'key' => 'POS_RECEIPT_LOGO',
                            'value' => Tools::getShopDomain() . _PS_IMG_ . PosReceipt::getLogoFileName(),
                        ),
                        'logo_width' => array(
                            'key' => 'POS_RECEIPT_LOGO_MAX_WIDTH',
                            'value' => Configuration::get('POS_RECEIPT_LOGO_MAX_WIDTH') ? Configuration::get('POS_RECEIPT_LOGO_MAX_WIDTH') : ''
                        ),
                        'canvas_width' => PosReceipt::getLogoWidth(),
                        'header_text' => array(
                            'key' => 'POS_RECEIPT_HEADER_TEXT',
                            'value' => Configuration::getInt('POS_RECEIPT_HEADER_TEXT') ? Configuration::getInt('POS_RECEIPT_HEADER_TEXT') : array(),
                        ),
                        'footer_text' => array(
                            'key' => 'POS_RECEIPT_FOOTER_TEXT',
                            'value' => Configuration::getInt('POS_RECEIPT_FOOTER_TEXT') ? Configuration::getInt('POS_RECEIPT_FOOTER_TEXT') : array(),
                        ),
                        'field_configs' => array(
                            'header' => array(
                                'groups' => array(
                                    'shop',
                                    'order',
                                    'employee',
                                    'customer',
                                ),
                                'fields' => array(
                                    'title',
                                    'header_text',
                                    'line_break',
                                    'divider',
                                ),
                            ),
                            'product' => array(
                                'groups' => array(
                                    'product'
                                ),
                                'fields' => array(),
                            ),
                            'summary' => array(
                                'groups' => array(
                                    'summary'
                                ),
                                'fields' => array(
                                // 'vouchers' // will enable later if we need this feature
                                ),
                            ),
                            'footer' => array(
                                'groups' => array(
                                    'shop',
                                    'employee',
                                    'customer',
                                ),
                                'fields' => array(
                                    'footer_text',
                                    'signature',
                                    'line_break',
                                    'divider',
                                    'reference_barcode',
                                    'note',
                                ),
                            ),
                        ),
                        'available_fields' => $this->module->getAvailableFields(),
                        'template' => array(
                            'value' => Tools::jsonDecode(Configuration::get('POS_RECEIPT_TEMPLATE')),
                            'key' => 'POS_RECEIPT_TEMPLATE'
                        ),
                        'sample_data' => array(
                            'logo' => Tools::getShopDomain() . _PS_IMG_ . PosReceipt::getLogoFileName(),
                            'canvas_width'=> PosReceipt::getLogoWidth(),
                            'pdf_page_size' => PosReceipt::getPdfPageSize(),
                            'shop_name' => Configuration::get('PS_SHOP_NAME') ? Configuration::get('PS_SHOP_NAME') : '',
                            'phone' => Configuration::get('PS_SHOP_PHONE', null, null, $this->context->shop->id) ? Configuration::get('PS_SHOP_PHONE', null, null, $this->context->shop->id) : '',
                            'fax' => Configuration::get('PS_SHOP_FAX', null, null, $this->context->shop->id) ? Configuration::get('PS_SHOP_FAX', null, null, $this->context->shop->id) : '',
                            'city' => Configuration::get('PS_SHOP_CITY') ? Configuration::get('PS_SHOP_CITY', null, null, $this->context->shop->id) : '',
                            'register_number' => Configuration::get('PS_SHOP_DETAILS', null, null, $this->context->shop->id) ? Configuration::get('PS_SHOP_DETAILS', null, null, $this->context->shop->id) : '',
                            'website_url' => Tools::getShopDomain(),
                            'shop_address' => Configuration::get('PS_SHOP_ADDR1', null, null, $this->context->shop->id) ? Configuration::get('PS_SHOP_ADDR1', null, null, $this->context->shop->id) : '',
                            'state' => State::getNameById(Configuration::get('PS_SHOP_STATE_ID', null, null, $this->context->shop->id)) ? State::getNameById(Configuration::get('PS_SHOP_STATE_ID', null, null, $this->context->shop->id)) : '',
                            'zipcode' => Configuration::get('PS_SHOP_CODE', null, null, $this->context->shop->id) ? Configuration::get('PS_SHOP_CODE', null, null, $this->context->shop->id) : '',
                            'order_reference' => Order::generateReference(),
                            'date_add' => date('Y-m-d H:i:s'),
                            'cashier' => implode(' ', array_filter(array($this->context->employee->firstname, $this->context->employee->lastname))),
                            'salesperson' => implode(' ', array_filter(array($this->context->employee->firstname, $this->context->employee->lastname))),
                            'title' => $this->module->i18n['receipt'],
                            'header_text' => Configuration::get('POS_RECEIPT_HEADER_TEXT', $this->context->language->id) ? Configuration::get('POS_RECEIPT_HEADER_TEXT', $this->context->language->id) : '',
                            'customer_name' => implode(' ', array($sample_customer->firstname, $sample_customer->lastname)),
                            'loyalty' => $this->module->i18n['loyalty'] . ': $0.00 (0 point)',
                            'delivery_address' => explode('<br />', PosAddressFormat::generateAddress(PosAddress::getSampleAddress(), array(), '<br />', ' ')),
                            'product' => PosProduct::getSampleProducts(),
                            'shipping' => Tools::displayPrice(10, $this->context->currency),
                            'tax_detail' => array(array('tax_rate' => 4.00, 'tax_amount' => Tools::displayPrice(10.00, $this->context->currency, false))),
                            'subtotal' => Tools::displayPrice($sample_summary['total_product'], $this->context->currency),
                            'total_product_discount' => $sample_summary['total_product_discount'],
                            'total_order_discount' => $sample_summary['total_order_discount'],
                            'total_items' => $sample_summary['total_items'],
                            'total' => Tools::displayPrice($sample_summary['total_product'] + 10 + 10, $this->context->currency, false),
                            'payments' => array(array('name' => 'Cash', 'amount' => Tools::ps_round(($sample_summary['total_product'] + 10 + 10) / 2, 2), array('name' => 'Credit', 'amount' => Tools::ps_round(($sample_summary['total_product'] + 10 + 10) / 2, 2)))),
                            'footer_text' => Configuration::get('POS_RECEIPT_FOOTER_TEXT', $this->context->language->id) ? Configuration::get('POS_RECEIPT_FOOTER_TEXT', $this->context->language->id) : '',
                            'note' => $this->module->i18n['note'],
                            'vouchers' => $this->module->getAvailableCartRules()
                        ),
                    ),
                    'others' => array(
                        'shop_url' => $this->context->link->getPageLink('index', null, $this->context->employee->id_lang, null, false, $this->context->shop->id),
                        'is_enable_friendly_url' => (int) Configuration::get('PS_REWRITING_SETTINGS'),
                        'friendly_url' => $this->context->link->getAdminLink('AdminMeta'),
                        'custom_uri' => array(
                            'key' => 'POS_CUSTOM_URI',
                            'value' => Configuration::get('POS_CUSTOM_URI') ? Configuration::get('POS_CUSTOM_URI') : 'pos'
                        ),
                        'share_your_data' => array(
                            'key' => 'POS_SHARE_YOUR_DATA',
                            'value' => (int)Configuration::get('POS_SHARE_YOUR_DATA')
                        ),
                    ),
                ),
                'orders' => array(
                    'orders' => array(),
                    'order_details' => array()
                ),
            ),
        );
    }

    /**
     *
     * @return array
     * <pre>
     * array(
     *  array(
     *      name => string,
     *      label => string,
     *      state => string,
     *      active => boolean,
     *      is_default => boolean,
     *  )
     * ...
     * )
     */
    protected function getloyaltyList()
    {
        return array(
            array(
                'name' => 'loyalty',
                'label' => $this->module->i18n['prestashop_loyalty'],
                'active' => Module::isInstalled('loyalty') && Module::isEnabled('loyalty'),
                'state' => $this->getModuleState('loyalty'),
                'checked' => Configuration::get('POS_LOYALTY') === 'loyalty',
            ),
            array(
                'name' => 'allinone_rewards',
                'label' => $this->module->i18n['all_in_one_rewards'],
                'active' => Module::isInstalled('allinone_rewards') && Module::isEnabled('allinone_rewards'),
                'state' => $this->getModuleState('allinone_rewards'),
                'checked' => Configuration::get('POS_LOYALTY') === 'allinone_rewards',
            ),
            array(
                'name' => 'totloyaltyadvanced',
                'label' => $this->module->i18n['advanced_loyalty_program'],
                'active' => Module::isInstalled('totloyaltyadvanced') && Module::isEnabled('totloyaltyadvanced'),
                'state' => $this->getModuleState('totloyaltyadvanced'),
                'checked' => Configuration::get('POS_LOYALTY') === 'totloyaltyadvanced',
            ),
        );
    }

    /**
     *
     * @param string $module_name
     * @return string
     */
    private function getModuleState($module_name)
    {
        $state = '';
        $module = Module::getInstanceByName($module_name);
        if ($module instanceof Module) {
            if (!Module::isInstalled($module_name)) {
                $state = $this->module->i18n['please_install'];
            } elseif (!Module::isEnabled($module_name)) {
                $state = $this->module->i18n['please_enable'];
            }
        } else {
            $state = $this->module->i18n['not_installed_yet_or_currently_disabled'];
        }
        return $state;
    }

    /**
     * @input
     * {
     *      keyword: string,
     *      action: "search",
     *      sub_action:"customer" || "category"
     *      ajax: "1"
     *
     * }
     * @output
     * {
     *      success: boolean,
     *      message: string,
     *      data: {
     *          customer: [// if sub_action = customer
     *              {
     *                  id_customer: int,
     *                  firstname: string,
     *                  lastname: string,
     *                  email: string,
     *                  phone: string,
     *                  phone_mobile: string,
     *                  company: string
     *              }
     *          ],
     *          category: [// if sub_action = category
     *              {
     *                  id_category: int,
     *                  name: string,
     *              }
     *          ]
     *      }
     * }
     */
    public function ajaxProcessSearch()
    {
        $keyword = trim(urldecode(Tools::getValue('keyword')));
        switch ($this->sub_action) {
            case 'customer':
                $this->ajax_json['data']['customers'] = array();
                $keywords = array_unique(explode(' ', $keyword));
                $customers = array();
                foreach ($keywords as $keyword) {
                    if (!empty($keyword)) {
                        $customers = array_merge($customers, PosCustomer::search($keyword));
                    }
                }
                if ($customers) {
                    $this->ajax_json['success'] = true;
                    $this->ajax_json['message'] = $this->module->i18n['customer_profiles_found'];
                    $this->ajax_json['data']['customers'] = $customers;
                } else {
                    $this->ajax_json['message'] = $this->module->i18n['no_associated_customer_found'];
                }
                break;
            case 'category':
                $categories = PosCategory::search($this->context->language->id, $keyword);
                if (!empty($categories)) {
                    $this->ajax_json['data']['categories'] = PosCategory::search($this->context->language->id, $keyword);
                    $this->ajax_json['success'] = true;
                    $this->ajax_json['message'] = $this->module->i18n['category_found'];
                } else {
                    $this->ajax_json['message'] = $this->module->i18n['no_associated_category_found'];
                }
                break;
            default:
                break;
        }
    }

    /**
     * @input
     * {
     *      id_customer: int,
     *      action: "guestAccount",
     *      ajax: "1"
     *
     * }
     * @output
     * {
     *      success: boolean,
     *      message: string,
     *      data: {
     *          customers: {
     *                  id: int,
     *                  firstname: string,
     *                  lastname: string,
     *                  email: string,
     *                  edit_link: string,
     *              }
     *      }
     * }
     */
    public function ajaxProcesGuestAccount()
    {
        $id_customer = (int) Tools::getValue('id_customer', null);
        if ($id_customer) {
            $customer = new PosCustomer($id_customer);
            if (Validate::isLoadedObject($customer)) {
                $customer->addPosCustomerGroup();
                $default_id_customers = PosCustomer::getDefaultCustomerIds(null, $this->context->shop->id);
                $default_id_customers[$this->context->employee->id] = $id_customer;
                if (PosCustomer::setDefaultCustomerIds($default_id_customers, $this->context->shop->id)) {
                    $this->ajax_json['message'] = $this->module->i18n['the_guest_account_has_been_successfully_updated'];
                    $this->ajax_json['data']['customers'] = PosCustomer::getGuestCustomer();
                }
            }
        }
    }

    /**
     * @input
     * {
     *      action: "payment",
     *      sub_action: "change_postion" || "edit" || "add"
     *      id_pos_payment: int,if sub_action = add | edit | delete | change_postion
     *      way: int,  if sub_action = change_position Up (1)  or Down (0)
     *      position: int,   if sub_action = change_position
     *      active: int,  if sub_action = add | edit
     *      is_default: int, if sub_action = add | edit
     *      payment_name: string, if sub_action = add
     *      shop_ids: array, if sub_action = add | edit
     *      ajax: "1"
     *
     * }
     * @output
     * {
     *      success: boolean,
     *      message: string,
     *      data: {
     *          payment_types: [], refer to payment_types getAllInOne
     *      }
     * }
     */
    public function ajaxProcessPayment()
    {
        $success = array();
        $id_pos_payment = (int) Tools::getValue('id_pos_payment', 0);
        $pos_payment = new PosPayment($id_pos_payment);
        switch ($this->sub_action) {
            case 'change_position':
                $way = Tools::getValue('way');
                $position = Tools::getValue('position');
                if (Validate::isLoadedObject($pos_payment)) {
                    $success[] = $pos_payment->updatePosition($way, $position);
                    $this->ajax_json['message'] = $this->module->i18n['update_successfully'];
                } else {
                    $success[] = false;
                    $this->ajax_json['message'] = $this->module->i18n['cannot_update_position'];
                }
                break;
            case 'add':
                $this->copyFromPost($pos_payment, 'pos_payment');
                if (Tools::getValue('is_default')) {
                    PosPayment::unsetDefaultPayment();
                }
                if ($pos_payment->add(false)) {
                    $success[] = true;
                    if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')) {
                        $shop_ids = Tools::getValue('shop_ids', array());
                    } else {
                        $shop_ids = array($this->context->shop->id);
                    }

                    if (!empty($shop_ids)) {
                        $pos_payment->addAssociatedShops($shop_ids);
                    }
                    $this->ajax_json['message'] = $this->module->i18n['add_successfully'];
                } else {
                    $success[] = false;
                    $this->ajax_json['message'] = $this->module->i18n['cannot_add_payment'];
                }
                break;
            case 'edit':
                $active = Tools::getValue('active', null);
                $is_default = Tools::getValue('is_default', null);
                $shop_ids = Tools::getValue('shop_ids', array());
                $payment_name = Tools::getValue('payment_name', array());

                if (Validate::isLoadedObject($pos_payment)) {
                    $update = false;
                    if (!is_null($active)) {
                        $update = true;
                        $pos_payment->active = $active;
                    } elseif (!is_null($is_default)) {
                        $update = true;
                        PosPayment::unsetDefaultPayment();
                        $pos_payment->is_default = 1;
                    } elseif (!empty($shop_ids)) {
                        $update = true;
                        $success[] = $pos_payment->deleteAssociatedShops();
                        $success[] = $pos_payment->addAssociatedShops($shop_ids);
                    } elseif (!empty($payment_name)) {
                        $update = true;
                        $pos_payment->payment_name = $payment_name;
                    }
                    if ($update && $pos_payment->update()) {
                        $success[] = true;
                        $this->ajax_json['message'] = $this->module->i18n['update_successfully'];
                    }
                } else {
                    $success[] = false;
                    $this->ajax_json['message'] = $this->module->i18n['cannot_update_paymment'];
                }
                break;
            case 'delete':
                $this->ajax_json['message'] = $this->module->i18n['cannot_remove_this_payment'];
                if (Validate::isLoadedObject($pos_payment) && $pos_payment->delete()) {
                    $this->ajax_json['message'] = $this->module->i18n['delete_successfully'];
                    $success[] = true;
                } else {
                    $success[] = false;
                    $this->ajax_json['message'] = $this->module->i18n['cannot_remove_this_payment'];
                }
                break;
            default:
                break;
        }
        if (array_sum($success) >= count($success)) {
            $this->ajax_json['success'] = true;
            $this->ajax_json['data']['payment_types'] = PosPayment::getPaymentTypes();
        }
    }

    /**
     * @input
     * {
     *      action: "updateSettings",
     *      ajax: "1"
     * }
     * @output
     * {
     *      success: boolean,
     *      message: string,
     *      data:{
     *          transaction: {
     *              settings: {} // Refer to action "getAllInOne" for further details, if success
     *          }
     *      }
     * }
     */
    public function ajaxProcessUpdateSettings()
    {
        $success = array();
        switch ($this->sub_action) {
            case 'change_language_position':
                $value = Tools::getValue('value');
                $key = Tools::getValue('key');
                $success[] = Configuration::updateValue($key, $value);
                $this->ajax_json['data']['settings'][$key] = Tools::jsonDecode(Configuration::get($key), true);
                break;
            default:
                $settings = Tools::getValue('settings');
                if (isset($_FILES['POS_RECEIPT_LOGO'])) {
                    if ($this->uploadReceiptLogo()) {
                        $success[] = 1;
                        $this->ajax_json['data']['settings']['POS_RECEIPT_LOGO'] = Tools::getShopDomain() . _PS_IMG_ . PosReceipt::getLogoFileName();
                    }
                }
                if (Tools::getValue('delete_logo')) {
                    @unlink(Tools::getShopDomain() . _PS_IMG_ . PosReceipt::getLogoFileName());
                    PosConfiguration::updateValue('POS_RECEIPT_LOGO', null);
                    $this->ajax_json['data']['settings']['POS_RECEIPT_LOGO'] = Tools::getShopDomain() . _PS_IMG_ . PosReceipt::getLogoFileName();
                }
                foreach ($settings as $key => $value) {
                    $text_fields = in_array($key, PosConfiguration::getTextFields());
                    if ($key == 'POS_SHARE_YOUR_DATA') {
                        $success[] = Configuration::updateValue($key, $value);
                        $success[] = Configuration::updateValue('POS_TIME_RUN_MIX_PANEL', (int) $value ? strtotime(" + 30 days") : 0);
                    }
                    if ($key == 'POS_MULTIPLE_LANGGUAGES') {
                        $success[] = Configuration::updateValue($key, $value);
                    }
                    if ($key == 'POS_DEFAULT_ID_CUSTOMERS') {
                        $default_id_customers = PosCustomer::getDefaultCustomerIds(null, $this->context->shop->id);
                        $default_id_customers[-1] = $value;
                        $success[] = PosCustomer::setDefaultCustomerIds($default_id_customers, $this->context->shop->id);
                    } else {
                        $success[] = Configuration::updateValue($key, $value, $text_fields);
                    }
                    if ($key == 'POS_MULTIPLE_LANGGUAGES') {
                        $this->ajax_json['data']['settings'][$key] = Tools::jsonDecode(Configuration::get($key), true);
                    } else {
                        $this->ajax_json['data']['settings'][$key] = $text_fields ? Configuration::getInt($key) : Configuration::get($key);
                    }
                }
                if (array_sum($success) >= count($success)) {
                    if (in_array('POS_SELECTED_STANDARD_ORDER_STATES', array_keys($settings))) {
                        if (!in_array(Configuration::get('POS_DEFAULT_STANDARD_ORDER_STATE'), explode(',', Configuration::get('POS_SELECTED_STANDARD_ORDER_STATES')))) {
                            $success[] = Configuration::updateValue('POS_DEFAULT_STANDARD_ORDER_STATE', 0);
                        }
                        $this->ajax_json['data']['settings']['POS_DEFAULT_STANDARD_ORDER_STATE'] = Configuration::get('POS_DEFAULT_STANDARD_ORDER_STATE');
                        $this->ajax_json['data']['order']['order_states'] = array(
                            'standard_order_states' => PosOrderState::getOrderStates($this->context->language->id),
                        );
                    }

                    if (in_array('POS_SELECTED_PRE_ORDER_STATES', array_keys($settings))) {
                        if (!in_array(Configuration::get('POS_DEFAULT_PRE_ORDER_STATE'), explode(',', Configuration::get('POS_SELECTED_PRE_ORDER_STATES')))) {
                            $success[] = Configuration::updateValue('POS_DEFAULT_PRE_ORDER_STATE', 0);
                        }
                        $this->ajax_json['data']['settings']['POS_DEFAULT_PRE_ORDER_STATE'] = Configuration::get('POS_DEFAULT_PRE_ORDER_STATE');
                        $this->ajax_json['data']['order']['order_states'] = array(
                            'pre_order_states' => PosOrderState::getOrderStates($this->context->language->id, false),
                        );
                    }

                    if (in_array('POS_DEFAULT_ID_CUSTOMERS', array_keys($settings))) {
                        $this->ajax_json['data']['settings']['customer'] = PosCustomer::getGuestCustomer();
                    }
                    if (in_array('POS_DEFAULT_CATEGORIES', array_keys($settings))) {
                        $this->ajax_json['data']['settings']['default_categories'] = PosCategory::getDefaultCategories();
                    }
                    if (in_array('POS_ID_PROFILES', array_keys($settings))) {
                        $this->ajax_json['data']['settings']['profiles'] = PosProfile::getProfiles($this->context->language->id);
                    }
                    if (in_array('POS_RECEIPT_LOGO_MAX_WIDTH', array_keys($settings))) {
                        $this->ajax_json['data']['settings']['canvas_width'] = PosReceipt::getLogoWidth();
                        $this->ajax_json['data']['settings']['pdf_page_size'] = PosReceipt::getPdfPageSize();
                    }
                }
                break;
        }
        if (array_sum($success) >= count($success)) {
            $this->ajax_json['success'] = true;
            $this->ajax_json['message'] = $this->module->i18n['update_successfully'];
        }
    }

    /**
     * @input
     * {
     *      action: "orders",
     *      sub_action: "get_list" | view | add_payment | change_order_state,
     *      date_from: string, if sub action get_list
     *      date_to: string, if sub action get_list
     *      ajax: "1"
     * }
     *  @output
     * {
     *      success: boolean,
     *      message: string,
     *      data:{
     *          orders: [
     *              {
     *                  id_order => int
     *                  reference => string
     *                  total => string
     *                  paid => string
     *                  unpaid => string
     *                  customer => string
     *                  shop_name => string
     *                  status => string
     *                  date => date time
     *              }
     *          ],
     *          order_states: {}  see selecte_order_state getAllInOne //sub_action: "view"
     *          payment_methods: [ // sub_action: "get_list"
     *              {
     *                  id_payment: int,
     *                  is_default: int,
     *                  name: string
     *              }
     *          ],
     *          products: [ //sub_action: "view"
     *              {
     *                  product_id: int
     *                  product_attribute_id: int
     *                  product_quantity: int
     *                  product_price: float
     *                  original_product_price: float
     *                  total_price_tax_incl: float
     *                  total_price_tax_excl: float
     *                  name: string
     *                  combination: string
     *              }
     *          ],
     *          order_payments: [ //sub_action: "view" || add_payment
     *              {
     *                  payment_method: string
     *                  amount: string
     *                  date_add: string
     *              }
     *          ],
     *          taxs: [ //sub_action: "view"
     *              {
     *                  id_order_detail: int
     *                  id_tax: int
     *                  tax_rate: float
     *                  unit_tax_base: float
     *                  total_tax_base: float
     *                  unit_amount: float
     *                  total_amount: float
     *              }
     *          ],
     *          order:{ //sub_action: "view" || add_payment || change_order_state
     *              id_order: int
     *              reference: string
     *              id_state: int
     *              total_paid: float
     *              total_paid_real: float
     *              date_add: string
     *              invoice_url: string,
     *          },
     *          customer: { // sub_action: "get_list"
     *              firstname: string
     *              lastname: string
     *          },
     *          currency: { //sub_action: "view"
     *                      id_currency: int,
     *                      name: string,
     *                      iso_code: string,
     *                      iso_code_num: string,
     *                      sign: string,
     *                      blank: int,
     *                      format: int,
     *                      decimals: int,
     *                      round_mode: int
     *          },
     *          template: string, //sub_action: "view" || add_payment || change_order_state
     *          1st_bill_printer: string,
     *          2nd_bill_printer: string,
     *          receipt: {}, refer to getReceiptData module //sub_action: "view" || add_payment || change_order_state
     *      }
     * }
     */
    public function ajaxProcessOrders()
    {
        switch ($this->sub_action) {
            case 'get_list':
                $date_from = Tools::getValue('date_from');
                $date_to = Tools::getValue('date_to');
                $orders = PosOrder::getList($this->module->name, $date_from, $date_to);
                $message = empty($orders) ? $this->module->i18n['no_available_orders'] : $this->module->i18n['all_orders_loaded'];
                $this->ajax_json = array(
                    'success' => true,
                    'message' => $message,
                    'data' => array(
                        'orders' => $orders,
                    )
                );
                break;
            case 'view':
                $current_state = (int) Tools::getValue('current_state');
                $order_collections = PosOrder::getByReference(Tools::getValue('reference'));
                $total_products = 0;
                $total_paid = 0;
                $total_paid_real = 0;
                $total_shipping_tax_incl = 0;
                $total_discounts_tax_excl = 0;
                $total_discounts_tax_incl = 0;
                $unpaid = 0;
                $products = array();
                $taxes = array();
                $is_return_exchange = PosExchange::isReturnExchangeOrder($order_collections);
                foreach ($order_collections as $index => $order) {
                    $unpaid += $order->total_paid - $order->total_paid_real;
                    if ((int) $order->current_state !== $current_state) {
                        unset($order_collections[$index]);
                    }
                }
                $unpaid = $is_return_exchange ? 0 : $unpaid;
                foreach ($order_collections as $index => $order) {
                    $total_products += $order->total_products_wt;
                    $total_paid += $order->total_paid;
                    $total_paid_real += $order->total_paid_real;

                    $total_shipping_tax_incl += $order->total_shipping_tax_incl;
                    $total_discounts_tax_excl += $order->total_discounts_tax_excl;
                    $total_discounts_tax_incl += $order->total_discounts_tax_incl;
                    $products = array_merge($products, $this->module->formatProducts($order->getProducts()));
                    $taxes = array_merge($order->getTaxes());
                }
                $order = $order_collections->getFirst();
                $this->context->cart = new PosCart($order->id_cart);
                if (Validate::isLoadedObject($order)) {
                    $customer = new PosCustomer($order->id_customer);
                    $this->ajax_json = array(
                        'success' => true,
                        'message' => $this->module->i18n['order_loaded'],
                        'data' => array(
                            'order_states' => PosOrderState::getOrderStates($this->context->language->id),
                            'payment_methods' => PosPayment::getAll($this->context->language->id, $order->id_shop),
                            'products' => $products,
                            'order_payments' => $this->module->getOrderPayments($order),
                            'taxs' => $taxes,
                            'order' => array(
                                'id_order' => $order->id,
                                'reference' => $order->reference,
                                'id_state' => $order->current_state,
                                'total_paid' => $total_paid,
                                'total_paid_real' => $total_paid_real,
                                'total_shipping' => $total_shipping_tax_incl,
                                'total_discount' => $total_discounts_tax_excl,
                                'amount_due' => Tools::ps_round($unpaid, _PS_PRICE_COMPUTE_PRECISION_),
                                'date_add' => $order->date_add,
                                'invoice_url' => $this->getInvoiceUrl($order),
                            ),
                            'customer' => array(
                                'firstname' => $customer->firstname,
                                'lastname' => $customer->lastname
                            ),
                            'currency' => PosCurrency::getCurrencyInstance($order->id_currency),
                            'template' => Configuration::get('POS_RECEIPT_TEMPLATE'),
                            '1st_bill_printer' => Configuration::get('POS_RECEIPT_BILL_PRINTER_1'),
                            '2nd_bill_printer' => Configuration::get('POS_RECEIPT_BILL_PRINTER_2'),
                            'receipt' => $this->module->getReceiptData($order_collections)
                        )
                    );
                } else {
                    $this->ajax_json['message'] = $this->module->i18n['cannot_load_order'];
                }
                break;
            case 'add_payment':
                $success = array();
                $amount = (float) Tools::getValue('amount');
                $order_collections = PosOrder::getByReference(Tools::getValue('reference'), true);
                $payment = new PosPayment((int) Tools::getValue('id_payment'), $this->context->language->id);
                if (!Validate::isLoadedObject($payment)) {
                    $this->ajax_json['message'] = $this->module->i18n['cannot_load_payment'];
                } elseif (!Validate::isNegativePrice($amount)) {
                    $this->ajax_json['message'] = $this->module->i18n['invalid_amount'];
                } else {
                    $amount_due = 0;
                    $unpaid = 0;
                    $total_paid = 0;
                    $total_paid_real = 0;
                    $total_shipping_tax_incl = 0;
                    $total_discounts_tax_excl = 0;
                    $total_discounts_tax_incl = 0;
                    foreach ($order_collections as $order) {
                        $unpaid += $order->total_paid - $order->total_paid_real;
                    }
                    foreach ($order_collections as $order) {
                        if ($amount == 0) {
                            break;
                        }
                        $total_paid += $order->total_paid;
                        $total_discounts_tax_incl += $order->total_discounts_tax_incl;
                        $total_paid_real += $order->total_paid_real;
                        $amount_due = $order->total_paid - $order->total_paid_real;
                        $amount_paid = 0;
                        if ($amount_due == 0) {
                            continue;
                        } elseif ($amount_due > 0 && $amount_due <= $amount) {
                            $total_paid_real += $amount_due;
                            $amount_paid = $amount_due;
                        } elseif ($amount_due > 0 && $amount_due > $amount) {
                            $total_paid_real += $amount;
                            $amount_paid = $amount;
                        }
                        $amount -= $amount_paid;
                        $currency = new PosCurrency((int) $order->id_currency);
                        $order_invoice = $order->hasInvoice() ? $order->getInvoicesCollection()->getFirst() : null;
                        $success[] = $order->addOrderPayment($amount_paid, $payment->payment_name, null, $currency, date('Y-m-d H:i:s'), $order_invoice);
                        if (array_sum($success) >= count($success)) {
                            $unpaid -= $amount_paid;
                            $pos_cart = new PosCart((int) $order->id_cart);
                            $order_state = new PosOrderState($order->current_state);
                            $success[] = $pos_cart->addPayment($payment->id, $amount_paid, $order->reference, null, $amount_paid);
                            $order->payment = $pos_cart->getPaymentMethods();
                            $success[] = $order->update();
                        }
                    }
                    if ($unpaid == 0) {
                        foreach ($order_collections as $order) {
                            $order_state = new PosOrderState($order->current_state);
                            $default_standard_order_state = (int) Configuration::get('POS_DEFAULT_STANDARD_ORDER_STATE');
                            if ($default_standard_order_state !== $order_state->invoice) {
                                $use_existings_payment = !$order->hasInvoice();
                                $history = new PosOrderHistory();
                                $history->id_order = $order->id;
                                $history->id_employee = (int) $this->context->employee->id;
                                $history->changeIdOrderState($default_standard_order_state, $order, $use_existings_payment);
                            }
                        }
                    }
                    $order = $order_collections->getFirst();
                    $this->context->cart = new PosCart($order->id_cart);
                    if (array_sum($success) >= count($success)) {
                        $this->ajax_json = array(
                            'success' => true,
                            'message' => $this->module->i18n['add_successfully'],
                            'data' => array(
                                'order_payments' => $this->module->getOrderPayments($order),
                                'order' => array(
                                    'id_order' => $order->id,
                                    'reference' => $order->reference,
                                    'id_state' => $order->current_state,
                                    'total_paid' => $total_paid,
                                    'total_paid_real' => $total_paid_real,
                                    'amount_due' => Tools::ps_round($unpaid, _PS_PRICE_COMPUTE_PRECISION_),
                                    'date_add' => $order->date_add,
                                    'invoice_url' => $this->getInvoiceUrl($order),
                                ),
                                'template' => Configuration::get('POS_RECEIPT_TEMPLATE'),
                                'receipt' => $this->module->getReceiptData($order_collections),
                            )
                        );
                    } else {
                        $this->ajax_json['message'] = $this->module->i18n['cannot_add_payment'];
                    }
                }
                break;
            case 'change_order_state':
                $current_state = (int) Tools::getValue('current_state');
                $id_order_state = (int) Tools::getValue('id_order_state', 0);
                $order_collections = PosOrder::getByReference(Tools::getValue('reference'));
                $unpaid = 0;
                $total_paid = 0;
                $total_paid_real = 0;
                $total_discounts_tax_incl = 0;
                $products = array();
                $taxes = array();
                foreach ($order_collections as $index => $order) {
                    $unpaid += $order->total_products_wt + $order->total_shipping - $order->total_paid_real - $order->total_discounts;
                    if ((int) $order->current_state !== $current_state) {
                        unset($order_collections[$index]);
                    }
                }
                foreach ($order_collections as $order) {
                    if ((int) $order->current_state !== (int) $id_order_state) {
                        $order_history = new PosOrderHistory();
                        $order_history->id_order = $order->id;
                        $order_history->id_employee = (int)$this->context->employee->id;
                        $use_existings_payment = !$order->hasInvoice();
                        $order_history->changeIdOrderState($id_order_state, $order, $use_existings_payment);
                        $template_vars = array();
                        // Save all changes
                        if ($order_history->addWithemail(true, $template_vars)) {
                            // synchronizes quantities if needed..
                            if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                                foreach ($order->getProducts() as $product) {
                                    if (StockAvailable::dependsOnStock($product['product_id'])) {
                                        StockAvailable::synchronize($product['product_id'], (int)$product['id_shop']);
                                    }
                                }
                            }
                        }
                    }
                    $total_paid += $order->total_paid;
                    $total_paid_real += $order->total_paid_real;
                    $total_discounts_tax_incl += $order->total_discounts_tax_incl;
                }
                $order = $order_collections->getFirst();
                $this->context->cart = new PosCart($order->id_cart);
                if (Validate::isLoadedObject($order)) {
                    $this->ajax_json['success'] = true;
                    $this->ajax_json = array(
                        'success' => true,
                        'message' => $this->module->i18n['update_successfully'],
                        'data' => array(
                            'order' => array(
                                'id_order' => $order->id,
                                'reference' => $order->reference,
                                'id_state' => $order->current_state,
                                'total_paid' => $total_paid,
                                'total_paid_real' => $total_paid_real,
                                'amount_due' => Tools::ps_round($unpaid, _PS_PRICE_COMPUTE_PRECISION_),
                                'date_add' => $order->date_add,
                                'invoice_url' => $this->getInvoiceUrl($order),
                            ),
                            'template' => Configuration::get('POS_RECEIPT_TEMPLATE'),
                            'receipt' => $this->module->getReceiptData($order_collections),
                        ),
                    );
                } else {
                    $this->ajax_json['message'] = $this->module->i18n['cannot_load_order'];
                }
                break;
            default:
                break;
        }
    }

    /**
     *    @input {
     *          action: "dashboard",
     *          date_from: string,
     *          date_to: string,
      ajax: int
     *     }
     *      @output
     *  {

      /**
     * @input
     * {
     *      action: "dashboard",
     *      granularity: string,
     *      ajax: "1"
     * }
     * @output
     * {
     *      success: boolean,
     *      message: string,
     *      data:{
     *          sales: {
     *              int: float // strtotime => float (1488430800 => 102)
     *          },
     *          orders: {
     *              int: float // strtotime => float (1488430800 => 2)
     *          },
     *          average_cart: {
     *              int: float // strtotime => float (1488430800 => 24)
     *          },
     *          net_profits: {
     *              int: float // strtotime => float (1488430800 => 1.5906)
     *          },
     *          best_seller_products: [
     *              {
     *                  product_id: int,
     *                  product_attribute_id: int,
     *                  name: string,
     *                  combination: string,
     *                  quantity: int,
     *                  sales: float,
     *                  discounts: float,
     *                  date: string,
     *                  trends: {
     *                      int: float //strtotime => float (1488430800 => 1.5906)
     *                  }
     *              }
     *          ],
     *          top_cashiers: [
     *              {
     *                  employee_name: string
     *                  sales: float
     *                  orders: int
     *                  quantity: int
     *                  average_cart: float
     *              }
      ]
      }
     *
     *          ],
     *          currency: {{
     *                      id_currency: int,
     *                      name: string,
     *                      iso_code: string,
     *                      iso_code_num: string,
     *                      sign: string,
     *                      blank: int,
     *                      format: int,
     *                      decimals: int,
     *                      round_mode: int
     *                  }}
     *      }
     * }
     */
    public function ajaxProcessDashboard()
    {
        $granularity = Tools::strtolower(Tools::getValue('granularity'));
        $date_from = Tools::getValue('date_from');
        $date_to = Tools::getValue('date_to');
        $summary_start_date = Tools::getValue('summary_start_date');
        $summary_details = PosOrder::getSummaryDetails($this->module, $date_from, $date_to, $granularity);
        $this->ajax_json['success'] = true;
        $this->ajax_json['data'] = array(
            'sales' => $summary_details['sales'],
            'orders' => $summary_details['orders'],
            'average_cart' => $summary_details['average_cart'],
            'net_profits' => $summary_details['net_profits'],
            'best_seller_products' => PosOrder::getBestSellingProducts($this->module, $summary_start_date, $date_from, $date_to, $granularity),
            'top_cashiers' => PosOrder::getTopCashiers($this->module, $summary_start_date, $date_to),
            'currency' => PosCurrency::getCurrencyInstance($this->context->currency->id),
        );
        $this->ajax_json['message'] = $this->module->i18n['all_set'];
    }

    /**
     * @input
     * {
     *      action: "report",
     *      date_from: string,
     *      date_to: string,
     *      ajax: "1"
     * }
     * @output
     * {
     *      success: boolean,
     *      message: string,
     *      data:{
     *          sales: {
     *              total_tax_incl: float
     *              total_tax_excl: float
     *              total_real_paid: float
     *              total_products: float
     *              total_transaction: int
     *              sold_items: int
     *          },
     *          shipping: int,
     *          payments: [
     *              {
     *                  amount: float
     *                  name: string
     *              }
     *          ],
     *          currencies: [
     *              {
     *                  total: string
     *                  sign: string
     *              }
     *          ],
     *          tax: [
     *              {
     *                  total: float
     *                  rate: float
     *              }
     *          ],
     *          categories: [
     *              {
     *                  id_category_default: int
     *                  name: string
     *                  total_price: float
     *              }
     *          ],
     *          pdf_url: string
     *      }
     * }
     */
    public function ajaxProcessReport()
    {
        $date_from = Tools::getValue('date_from');
        $date_to = Tools::getValue('date_to');
        $build_query = array(
            'action' => 'generatesalessummary',
            'date_from' => $date_from,
            'date_to' => $date_to,
        );
        $sales_summary = new PosSalesSummary($this->module->name, $date_from, $date_to);
        $this->ajax_json['success'] = true;
        $this->ajax_json['data'] = array(
            'report_generate_info' => sprintf($this->module->i18n['generated_by_at'], implode(' ', array('firstname' => $this->context->employee->firstname, 'lastname' => $this->context->employee->lastname,)), date('M jS Y h:i')),
            'tax_enabled' => Configuration::get('PS_TAX'),
            'sales' => $sales_summary->getSalesSummary(),
            'shipping' => $sales_summary->getShippingCostSummary(),
            'payments' => $sales_summary->getPaymentSummary(),
            'currencies' => $sales_summary->getCurrencySummary(),
            'tax' => $sales_summary->getTaxSummary(),
            'categories' => $sales_summary->getCategorySummary($this->context->language->id),
            'pdf_url' => $this->context->link->getAdminLink('AdminRockPosManage') . '&' . http_build_query($build_query),
        );
        $this->ajax_json['message'] = $this->module->i18n['all_set'];
    }

    /**
     * @input
     * {
     *      action: "commissions",
     *      date_from: string,
     *      date_to: string,
     *      ajax: "1"
     * }
     * @output
     * {
     *      success: boolean,
     *      message: string,
     *      data:{
     *          sales_commission: [
     *              {
     *                  employee: string
     *                  email: string
     *                  total_incl_tax: float
     *                  total_excl_tax: float
     *                  total_commission: float
     *              }
     *          ],
     *          sum_sales_commission: [
     *            sum_total_excl_tax: float
     *            sum_total_incl_tax: float
     *            sum_total_commission: float
     *          ]
     *      }
     * }
     */
    public function ajaxProcessCommissions()
    {
        $date_from = Tools::getValue('date_from');
        $date_to = Tools::getValue('date_to');
        $sales_commission = PosSalesCommission::getSalesCommission($date_from, $date_to);
        $sum_sales_commission = array(
            'sum_total_excl_tax' => 0,
            'sum_total_incl_tax' => 0,
            'sum_total_tax' => 0,
            'sum_total_commission' => 0,
        );
        if (!empty($sales_commission)) {
            foreach ($sales_commission as $sale_commission) {
                $sum_sales_commission['sum_total_excl_tax'] += $sale_commission['total_excl_tax'];
                $sum_sales_commission['sum_total_incl_tax'] += $sale_commission['total_incl_tax'];
                $sum_sales_commission['sum_total_commission'] += $sale_commission['total_commission'];
            }
            $sum_sales_commission['sum_total_tax'] = $sum_sales_commission['sum_total_incl_tax'] - $sum_sales_commission['sum_total_excl_tax'];
        }
        $this->ajax_json['success'] = true;
        $this->ajax_json['data'] = array(
            'sales_commission' => $sales_commission,
            'sum_sales_commission' => $sum_sales_commission,
        );
        $this->ajax_json['message'] = $this->module->i18n['all_set'];
    }

    public function processIndex()
    {
        $success = array();
        $full = (int) Tools::getValue('full', 0);
        $search_index = new PosSearchIndex($full);
        $success[] = $search_index->run();
        if (array_sum($success) < count($success)) {
            die($this->module->i18n['oops_something_goes_wrong']);
        }
    }

    public function processGenerateSalesSummary()
    {
        $date_from = Tools::getValue('date_from');
        $date_to = Tools::getValue('date_to');
        $pos_sales_summary = new PosSalesSummary($this->module->name, $date_from, $date_to);
        $pdf_sales_summary = new PosPDFSalesSummary($pos_sales_summary, PosConstants::TEMPLATE_SALES_SUMMARY, $this->context->smarty);
        $pdf_sales_summary->render('I');
    }

    /**
     * upload receipt logo.
     */
    public function uploadReceiptLogo()
    {
        $success = array();
        if ($_FILES['POS_RECEIPT_LOGO']['name']) {
            $image_uploader = new PosHelperImageUploader('POS_RECEIPT_LOGO');
            $image_uploader->setAcceptTypes(PosFile::getImageExtensions());
            $image_uploader->setSavePath(_PS_IMG_DIR_);
            $image_uploader->setResizedWidth(PosConstants::RECEIPT_LOGO_MAX_WIDTH);
            $image_uploader->resize = true;
            $extension = PosFile::getFileExtension($_FILES['POS_RECEIPT_LOGO']);
            $logo_name = $this->getLogoName(PosConstants::RECEIPT_LOGO_PREFIX, $extension);
            $files = $image_uploader->process($logo_name);
            if (count($files) && $files[0]['error']) {
                $success[] = 0;
                $this->ajax_json['message'] = $files[0]['error'];
            } elseif ($image_uploader->resize($files[0]['save_path'], _PS_IMG_DIR_ . $logo_name)) {
                $success[] = PosConfiguration::updateValue('POS_RECEIPT_LOGO', $logo_name);
            }
        }
        return array_sum($success) >= count($success);
    }

    /**
     * @param string $prefix
     * @param string $extension
     *
     * @return string
     */
    protected function getLogoName($prefix, $extension)
    {
        $id_shop = (int) Context::getContext()->shop->id;
        if (Context::getContext()->shop->getContext() == Shop::CONTEXT_ALL || $id_shop == 0 || Shop::isFeatureActive() == false) {
            $id_shop = '';
        }
        return Tools::link_rewrite(Context::getContext()->shop->name) . '-' . $prefix . '-' . (int) PosConfiguration::get('PS_IMG_UPDATE_TIME') . $id_shop . '.' . $extension;
    }

    /**
     *
     * @param PosOrder $order
     * @return string
     */
    protected function getInvoiceUrl(PosOrder $order)
    {
        return $order->hasInvoice() ? $this->context->link->getAdminLink('AdminPdf') . '&submitAction=generateInvoicePDF&id_order=' . $order->id : '';
    }
}
