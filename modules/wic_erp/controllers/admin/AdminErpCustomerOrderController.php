<?php
/**
* Module My Easy ERP Web In Color 
* 
*  @author    Web In Color - addons@webincolor.fr
*  @version 2.6
*  @uses Prestashop modules
*  @since 1.0 - mai 2014
*  @package Wic ERP
*  @copyright Copyright &copy; 2014, Web In Color
*  @license   http://www.webincolor.fr
*/

if (!defined('_PS_VERSION_')) {
    exit(1);
}

class AdminErpCustomerOrderController extends ModuleAdminController
{
    public $toolbar_title;

    public function __construct()
    {
        $this->table = 'order';
        $this->className = 'Order';
        $this->lang = false;
        $this->explicitSelect = true;
        $this->bootstrap = true;
        $this->dlcDluoActive = false;
        $this->deleted = false;
        $this->context = Context::getContext();

        if (Tools::isSubmit('submitResetorder')) {
            $this->processResetFilters();
        }

        $statuses_array = array();
        $statuses = OrderState::getOrderStates((int)$this->context->language->id);

        foreach ($statuses as $status) {
            $statuses_array[$status['id_order_state']] = $status['name'];
        }

        $this->list_id = 'order';
        $this->bulk_actions = array(
            'update' => array('text' => $this->l('Update selected'), 'confirm' => $this->l('Update selected orders?'))
        );

        $this->fields_list = array(
        'id_order' => array(
            'title' => $this->l('ID'),
            'align' => 'center',
            'width' => 25
        ),
        'reference' => array(
            'title' => $this->l('Reference'),
            'align' => 'center',
            'width' => 65
        ),
        'customer' => array(
            'title' => $this->l('Customer'),
            'tmpTableFilter' => true
        ),
        'total_paid_tax_incl' => array(
            'title' => $this->l('Total'),
            'width' => 70,
            'align' => 'right',
            'prefix' => '<b>',
            'suffix' => '</b>',
            'type' => 'price',
            'currency' => true
        ),
        'osname' => array(
            'title' => $this->l('Status'),
            'color' => 'color',
            'width' => 230,
            'type' => 'select',
            'list' => $statuses_array,
            'filter_key' => 'os!id_order_state',
            'filter_type' => 'int'
        ),
        'date_add' => array(
            'title' => $this->l('Date'),
            'width' => 130,
            'align' => 'right',
            'type' => 'datetime',
            'filter_key' => 'a!date_add'
        ));

        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('
					SELECT DISTINCT c.id_country, cl.`name`
					FROM `'._DB_PREFIX_.'orders` o
					'.Shop::addSqlAssociation('orders', 'o').'
					INNER JOIN `'._DB_PREFIX_.'address` a ON a.id_address = o.id_address_delivery
					INNER JOIN `'._DB_PREFIX_.'country` c ON a.id_country = c.id_country
					INNER JOIN `'._DB_PREFIX_.'country_lang` cl ON (c.`id_country` = cl.`id_country` AND cl.`id_lang` = '.(int)$this->context->language->id.')
					ORDER BY cl.name ASC');

        $country_array = array();
        foreach ($result as $row) {
            $country_array[$row['id_country']] = $row['name'];
        }

        $part1 = array_slice($this->fields_list, 0, 3);
        $part2 = array_slice($this->fields_list, 3);
        $part1['cname'] = array(
            'title' => $this->l('Delivery'),
            'type' => 'select',
            'list' => $country_array,
            'filter_key' => 'country!id_country',
            'filter_type' => 'int',
            'order_key' => 'cname'
        );
        $this->fields_list = array_merge($part1, $part2);

        $this->fields_list = array_merge($this->fields_list, array(
            'carrier_name' => array(
            'title' => $this->l('Carrier'),
            'width' => 100,
            'callback' => 'carrier',
            'filter_key' => 'ca!name',
        ),
        'id_pdf' => array(
            'title' => $this->l('PDF'),
            'width' => 100,
            'align' => 'center',
            'callback' => 'printPDFIcons',
            'orderby' => false,
            'search' => false,
            'remove_onclick' => true
        ),
        'id_product_detail' => array(
            'title' => $this->l(''),
            'align' => 'center',
            'callback' => 'viewProduct',
            'orderby' => false,
            'search' => false,
            'remove_onclick' => true
        )
        ));

        $this->shopLinkType = 'shop';
        $this->shopShareDatas = Shop::SHARE_ORDER;
        if (Tools::isSubmit('id_order')) {
            // Save context (in order to apply cart rule)
            $order = new Order((int)Tools::getValue('id_order'));
            if (!Validate::isLoadedObject($order)) {
                throw new PrestaShopException('Cannot load Order object');
            }
            $this->context->cart = new Cart($order->id_cart);
            $this->context->customer = new Customer($order->id_customer);
        }

        parent::__construct();
        
        //We verify if DLC/DLUO addons exists
        if (file_exists(_PS_MODULE_DIR_.'productsdlcdluo/ProductsDlcDluoClass.php')
                && file_exists(_PS_MODULE_DIR_.'productsdlcdluo/OrdersDlcDluo.php')) {
            $this->dlcDluoActive = true;
            require_once(_PS_MODULE_DIR_.'productsdlcdluo/ProductsDlcDluoClass.php');
            require_once(_PS_MODULE_DIR_.'productsdlcdluo/OrdersDlcDluo.php');
        }
    }

    public function renderList()
    {
        if (Tools::isSubmit('submitFilter')) {
            parent::processFilter();
        }

        if (Tools::getValue('order_status') || isset($this->context->cookie->erp_customer_order_state)) {
            if (Tools::getValue('order_status')) {
                $this->tpl_list_vars['tab_selected'] = Tools::getValue('order_status');
                $this->context->cookie->erp_customer_order_state = Tools::getValue('order_status');
            } else {
                $this->tpl_list_vars['tab_selected'] = $this->context->cookie->erp_customer_order_state;
            }
        } else {
            $this->context->cookie->erp_customer_order_state = 'complete';
            $this->tpl_list_vars['tab_selected'] = 'complete';
        }
        
        $this->_select = '
		a.id_currency,
		a.id_order AS id_pdf,
		a.id_order AS id_product_detail,		
		CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`,
		osl.`name` AS `osname`,
		os.`color`,
		ca.name as carrier_name,
		country_lang.name as cname,
		IF((SELECT COUNT(so.id_order) FROM `'._DB_PREFIX_.'orders` so WHERE so.id_customer = a.id_customer) > 1, 0, 1) as new';

        $this->_join = '
		LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = a.`id_customer`)
		INNER JOIN `'._DB_PREFIX_.'address` address ON address.id_address = a.id_address_delivery
		INNER JOIN `'._DB_PREFIX_.'country` country ON address.id_country = country.id_country
		INNER JOIN `'._DB_PREFIX_.'country_lang` country_lang ON (country.`id_country` = country_lang.`id_country` AND country_lang.`id_lang` = '.(int)$this->context->language->id.')
		LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = a.`current_state`)
		LEFT JOIN `'._DB_PREFIX_.'carrier` ca ON (ca.`id_carrier` = a.`id_carrier`)
		LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$this->context->language->id.')';
        if ($this->context->cookie->erp_customer_order_state == 'pending') {
            $this->_where = ' AND a.`current_state` IN ('.pSQL(Configuration::get('WIC_ERP_NOT_COMPLETE')).')'.$this->getQueryShopList();
        } elseif ($this->context->cookie->erp_customer_order_state == 'ignored') {
            $this->_where = ' AND a.`current_state` IN ('.pSQL(Configuration::get('WIC_ERP_NOT_UPD_STATE')).')'.$this->getQueryShopList();
        } elseif ($this->context->cookie->erp_customer_order_state == 'preparation') {
            $this->_where = ' AND a.`current_state` IN ('.pSQL(Configuration::get('WIC_ERP_PREPARATION_STATE')).')'.$this->getQueryShopList();
        } else {
            $this->_where = ' AND a.`current_state` IN ('.pSQL(Configuration::get('WIC_STATE_ERP_COMPLETE')).(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') ? ','.pSQL(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS')) : '').')'.$this->getQueryShopList();
        }
        $this->_orderBy = 'id_order';
        $this->_orderWay = 'ASC';

        $this->tpl_list_vars['shop_link_type'] = false;
        $this->tpl_list_vars['list_id'] = 'order';
        $this->tpl_list_vars['count_complete'] = (int)$this->getCompleteOrders();
        $this->tpl_list_vars['count_ignored'] = (int)$this->getIgnoredOrders();
        $this->tpl_list_vars['count_pending'] = (int)$this->getPendingOrders();
        $this->tpl_list_vars['count_preparation'] = (int)$this->getPreparationOrders();

        $this->tpl_list_vars['statuses'] = OrderState::getOrderStates((int)$this->context->language->id);
        $this->tpl_list_vars['orderMessages'] = OrderMessage::getOrderMessages((int)$this->context->language->id);

        return parent::renderList();
    }

    public function renderKpis()
    {
        $time = time();
        $kpis = array();

        /* The data generation is located in AdminStatsControllerCore */

        $helper = new HelperKpi();
        $helper->id = 'box-complete-orders';
        $helper->icon = 'icon-ok-circle';
        $helper->color = 'color4';
        $helper->title = $this->l('Complete orders', null, null, false);
        $helper->subtitle = $this->l('Orders to prepare', null, null, false);
        $helper->source = $this->context->link->getAdminLink('AdminErpCustomerOrder').'&ajax=1&action=getKpi&kpi=complete_orders';
        $kpis[] = $helper->generate();

        $helper = new HelperKpi();
        $helper->id = 'box-pending-orders';
        $helper->icon = 'icon-remove-circle';
        $helper->color = 'color1';
        $helper->title = $this->l('Pending orders', null, null, false);
        $helper->subtitle = $this->l('No reservable quantity', null, null, false);
        $helper->source = $this->context->link->getAdminLink('AdminErpCustomerOrder').'&ajax=1&action=getKpi&kpi=pending_orders';
        $kpis[] = $helper->generate();

        $helper = new HelperKpi();
        $helper->id = 'box-ignored-orders';
        $helper->icon = 'icon-minus-sign';
        $helper->color = 'color2';
        $helper->title = $this->l('Ignored orders', null, null, false);
        $helper->subtitle = $this->l('Pending payment', null, null, false);
        $helper->source = $this->context->link->getAdminLink('AdminErpCustomerOrder').'&ajax=1&action=getKpi&kpi=ignored_orders';
        $kpis[] = $helper->generate();

        $helper = new HelperKpi();
        $helper->id = 'box-processed-orders';
        $helper->icon = 'icon-thumbs-up';
        $helper->color = 'color3';
        $helper->title = $this->l('Processed orders', null, null, false);
        $helper->subtitle = $this->l('Closed orders', null, null, false);
        $helper->source = $this->context->link->getAdminLink('AdminErpCustomerOrder').'&ajax=1&action=getKpi&kpi=processed_orders';
        $kpis[] = $helper->generate();

        $helper = new HelperKpiRow();
        $helper->kpis = $kpis;
        return $helper->generate();
    }

    public function displayAjaxGetKpi()
    {
        switch (Tools::getValue('kpi')) {
            case 'complete_orders':
                $value = (int)$this->getCompleteOrders();
                ConfigurationKPI::updateValue('COMPLETE_ORDERS', $value);
                ConfigurationKPI::updateValue('COMPLETE_ORDERS_EXPIRE', strtotime('+5 min'));
                break;

            case 'pending_orders':
                $value = (int)$this->getPendingOrders();
                ConfigurationKPI::updateValue('PENDING_ORDERS', $value);
                ConfigurationKPI::updateValue('PENDING_ORDERS_EXPIRE', strtotime('+5 min'));
                break;

            case 'ignored_orders':
                $value = (int)$this->getIgnoredOrders();
                ConfigurationKPI::updateValue('IGNORED_ORDERS', $value);
                ConfigurationKPI::updateValue('IGNORED_ORDERS_EXPIRE', strtotime('+5 min'));
                break;

            case 'processed_orders':
                $value = (int)$this->getPreparationOrders();
                ConfigurationKPI::updateValue('PROCESSED_ORDERS', $value);
                ConfigurationKPI::updateValue('PROCESSED_ORDERS_EXPIRE', strtotime('+5 min'));
                break;

            default:
                $value = false;
        }

        if ($value !== false) {
            $array = array('value' => $value);

            die(Tools::jsonEncode($array));
        }
        die(Tools::jsonEncode(array('has_errors' => true)));
    }

    public function fetchTemplate($path, $name, $extension = false)
    {
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'wic_erp'.$path.$name.'.'.($extension ? $extension : 'tpl'));
    }

    public function printPDFIcons($id_order, $tr)
    {
        $order = new Order($id_order);
        $order_state = $order->getCurrentOrderState();
        if (!Validate::isLoadedObject($order_state) || !Validate::isLoadedObject($order)) {
            return '';
        }

        $this->context->smarty->assign(array(
            'order' => $order,
            'order_state' => $order_state,
            'tr' => $tr
        ));

        return $this->fetchTemplate('/views/templates/admin/', '_print_pdf_icon16');
    }

    public function viewProduct($id_order)
    {
        $order = new Order($id_order);
        $products = $order->getProducts();

        foreach ($products as &$product) {
            $product['in_stock'] = StockAvailable::getQuantityAvailableByProduct((int)$product['product_id'], $product['product_attribute_id'], Context::getContext()->shop->id);
            
            //We verify if DLC / DLUO is active
            if ($this->dlcDluoActive) {
                
                //We retrieve DLC / DLUO entries by product, combination and order
                $req = 'SELECT id_dlc_dluo, quantity
                        FROM `'._DB_PREFIX_.'products_dlc_dluo_orders` 
			WHERE `id_order`  = '.(int)$id_order.'
                        AND id_product = '.(int)$product['product_id'].'
                        AND id_combinaison = '.(int)$product['product_attribute_id'];
                $productsDlcDluoOrders = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($req);
                
                if ($productsDlcDluoOrders) {
                    foreach ($productsDlcDluoOrders as $productDlc) {
                        $order_product = new DlcDluoClass($productDlc['id_dlc_dluo']);
                        
                        if (Validate::isLoadedObject($order_product)) {
                            $warehouse = new Warehouse($order_product->id_warehouse);
                            $warehouseName = '';
                            if (Validate::isLoadedObject($warehouse)) {
                                $warehouseName = $warehouse->name;
                            }
                                
                            $product['dlc'][] = array(
                                'batchNumber' => $order_product->numero_lot,
                                'dlc' => $order_product->dlc,
                                'bbd' => $order_product->dluo, //Best before date
                                'quantity' => $productDlc['quantity'],
                                'warehouse' => $warehouseName,
                            );
                        }
                        
                    }
                }
            }
        }

        if (!Validate::isLoadedObject($order)) {
            return '';
        }

        $message = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
			SELECT `message`
			FROM `'._DB_PREFIX_.'message`
			WHERE `id_order` = '.(int)$order->id.'
			AND `private` = 0
			ORDER BY `id_message`
		');
        
        $order_not_upd_stk = explode(',', Configuration::get('WIC_ERP_NOT_UPD_STK'));

        $order_supplier_allow = false;

        if (!in_array($order->current_state, $order_not_upd_stk)) {
            $order_supplier_allow = true;
        }

        $this->context->smarty->assign(array(
            'order' => $order,
            'products' => $products,
            'order_supplier_allow' => $order_supplier_allow,
            'message' => $message
        ));

        return $this->fetchTemplate('/views/templates/admin/', '_product_details_16');
    }

    public function carrier($carrier_name)
    {
        return $carrier_name;
    }

    public function getList($id_lang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        $this->context = Context::getContext();
        
        if (Tools::getValue($this->list_id.'Orderby')) {
            $orderBy = Tools::getValue($this->list_id.'Orderby');
        }
        
        if (Tools::getValue($this->list_id.'Orderway')) {
            $orderWay = Tools::getValue($this->list_id.'Orderway');
        }
            
        parent::getList($id_lang, $orderBy, $orderWay, $start, $limit, $this->context->shop->id);
    }
    
    public function processFilter()
    {
        if (!Tools::isSubmit('submitResetorder')) {
            parent::processFilter();
        }
            
        $this->_filter = str_replace('`carrier_name`', 'ca.name', $this->_filter);
    }

    public function postProcess()
    {
        if (Tools::getValue('id_order')) {
            Tools::redirectAdmin('index.php?controller=adminorders&id_order='.Tools::getValue('id_order').'&vieworder'.'&token='.Tools::getAdminToken('AdminOrders'.Tab::getIdFromClassName('AdminOrders').(int)$this->context->employee->id));
        }

        if (Tools::isSubmit('submitBulk')) {
            if (Tools::getValue('orderBox') && count(Tools::getValue('orderBox')) > 0) {
                if ($id_order_state = Tools::getValue('select_submitBulk')) {
                    $order_to_be_processed = 0;
                    $order_processed_success = 0;

                    foreach (Tools::getValue('orderBox') as $order_id) {
                        $order_to_be_processed ++;
                        if (empty(Context::getContext()->link)) {
                            Context::getContext()->link = new Link();
                        }

                        $order = new Order($order_id);

                        $last_order_state = $order->getCurrentOrderState();

                        if ($last_order_state->id != $id_order_state) {
                            $new_history = new OrderHistory();
                            $new_history->id_order = (int)$order->id;
                            $new_history->id_order_state = (int)$id_order_state;
                            $new_history->id_employee = (int)$this->context->employee->id;
                            $new_history->changeIdOrderState((int)$id_order_state, $order->id);
                            $new_history->addWithemail();
                            $order_processed_success++;
                        }
                    }

                    if ($order_processed_success) {
                        $this->confirmations[] = $order_processed_success.' '.$this->l('of').' '.$order_to_be_processed.' '.$this->l('order(s) updated');
                    } else {
                        $this->errors[] = $order_processed_success.' '.$this->l('of').' '.$order_to_be_processed.' '.$this->l('order(s) updated');
                    }
                } else {
                    $this->errors[] = $this->l('Please select new status');
                }
            } else {
                $this->errors[] = $this->l('Please select orders');
            }
        } elseif (Tools::isSubmit('submitMessage')) {
            if (Tools::getValue('orderBox') && count(Tools::getValue('orderBox')) > 0) {
                if (!Tools::getValue('message')) {
                    $this->errors[] = Tools::displayError('The message cannot be blank.');
                } else {
                    $order_to_be_processed = 0;
                    $order_processed_success = 0;

                    foreach (Tools::getValue('orderBox') as $order_id) {
                        $order_to_be_processed ++;
                        $order = new Order($order_id);
                        $customer = new Customer((int)$order->id_customer);
                        /* Get message rules and and check fields validity */
                        $rules = call_user_func(array('Message', 'getValidationRules'), 'Message');
                        foreach ($rules['required'] as $field) {
                            if (($value = Tools::getValue($field)) == false && (string)$value != '0') {
                                if (!Tools::getValue('id_'.$this->table) || $field != 'passwd') {
                                    $this->errors[] = sprintf(Tools::displayError('field %s is required.'), $field);
                                }
                            }
                        }
                        foreach ($rules['size'] as $field => $max_length) {
                            if (Tools::getValue($field) && Tools::strlen(Tools::getValue($field)) > $max_length) {
                                $this->errors[] = sprintf(Tools::displayError('field %1$s is too long (%2$d chars max).'), $field, $max_length);
                            }
                        }
                        foreach ($rules['validate'] as $field => $function) {
                            if (Tools::getValue($field)) {
                                if (!Validate::$function(htmlentities(Tools::getValue($field), ENT_COMPAT, 'UTF-8'))) {
                                    $this->errors[] = sprintf(Tools::displayError('field %s is invalid.'), $field);
                                }
                            }
                        }

                        if (!count($this->errors)) {
                            //check if a thread already exist
                            $id_customer_thread = CustomerThread::getIdCustomerThreadByEmailAndIdOrder($customer->email, $order->id);
                            if (!$id_customer_thread) {
                                $customer_thread = new CustomerThread();
                                $customer_thread->id_contact = 0;
                                $customer_thread->id_customer = (int)$order->id_customer;
                                $customer_thread->id_shop = (int)$this->context->shop->id;
                                $customer_thread->id_order = (int)$order->id;
                                $customer_thread->id_lang = (int)$this->context->language->id;
                                $customer_thread->email = $customer->email;
                                $customer_thread->status = 'open';
                                $customer_thread->token = Tools::passwdGen(12);
                                $customer_thread->add();
                            } else {
                                $customer_thread = new CustomerThread((int)$id_customer_thread);
                            }

                            $customer_message = new CustomerMessage();
                            $customer_message->id_customer_thread = $customer_thread->id;
                            $customer_message->id_employee = (int)$this->context->employee->id;
                            $customer_message->message = Tools::getValue('message');
                            $customer_message->private = Tools::getValue('visibility');

                            if (!$customer_message->add()) {
                                $this->errors[] = Tools::displayError('An error occurred while saving the message.');
                            } elseif (!$customer_message->private) {
                                $message = $customer_message->message;
                                if (Configuration::get('PS_MAIL_TYPE', null, null, $order->id_shop) != Mail::TYPE_TEXT) {
                                    $message = Tools::nl2br($customer_message->message);
                                }

                                $vars_tpl = array(
                                    '{lastname}' => $customer->lastname,
                                    '{firstname}' => $customer->firstname,
                                    '{id_order}' => $order->id,
                                    '{order_name}' => $order->getUniqReference(),
                                    '{message}' => $message
                                );
                                if (!Mail::Send((int)$order->id_lang, 'order_merchant_comment',
                                    Mail::l('New message regarding your order', (int)$order->id_lang), $vars_tpl, $customer->email,
                                    $customer->firstname.' '.$customer->lastname, null, null, null, null, _PS_MAIL_DIR_, true, (int)$order->id_shop)) {
                                    $this->errors[] = Tools::displayError('An error occurred while sending an email to the customer.');
                                } else {
                                    $order_processed_success++;
                                }
                            }
                        }
                    }
                }
                if ($order_processed_success) {
                    $this->confirmations[] = $order_processed_success.' '.$this->l('of').' '.$order_to_be_processed.' '.$this->l('order(s) updated');
                } else {
                    $this->errors[] = $order_processed_success.' '.$this->l('of').' '.$order_to_be_processed.' '.$this->l('order(s) updated');
                }
            } else {
                $this->errors[] = Tools::displayError('Please select Order.');
            }
        }
    }

    public function initToolbar()
    {
        return false;
    }

    public function getCompleteOrders()
    {
        if (Configuration::get('WIC_STATE_ERP_COMPLETE')) {
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT COUNT(`id_order`) FROM `'._DB_PREFIX_.'orders` WHERE `current_state` IN('.Configuration::get('WIC_STATE_ERP_COMPLETE').(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') ? ','.pSQL(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS')) : '').')'.$this->getQueryShop());
        } else {
            return false;
        }
    }

    public function getPendingOrders()
    {
        if (Configuration::get('WIC_ERP_NOT_COMPLETE')) {
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT COUNT(`id_order`) FROM `'._DB_PREFIX_.'orders` WHERE `current_state` IN('.Configuration::get('WIC_ERP_NOT_COMPLETE').')'.$this->getQueryShop());
        } else {
            return false;
        }
    }

    public function getIgnoredOrders()
    {
        if (Configuration::get('WIC_ERP_NOT_UPD_STATE')) {
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT COUNT(`id_order`) FROM `'._DB_PREFIX_.'orders` WHERE `current_state` IN('.Configuration::get('WIC_ERP_NOT_UPD_STATE').')'.$this->getQueryShop());
        } else {
            return false;
        }
    }

    public function getProcessedOrders()
    {
        if (Configuration::get('WIC_ERP_NOT_DISPLAY')) {
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT COUNT(`id_order`) FROM `'._DB_PREFIX_.'orders` WHERE `current_state` IN('.Configuration::get('WIC_ERP_NOT_DISPLAY').')'.$this->getQueryShop());
        } else {
            return false;
        }
    }

    public function getPreparationOrders()
    {
        if (Configuration::get('WIC_ERP_PREPARATION_STATE')) {
            return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT COUNT(`id_order`) FROM `'._DB_PREFIX_.'orders` WHERE `current_state` IN('.Configuration::get('WIC_ERP_PREPARATION_STATE').')'.$this->getQueryShop());
        } else {
            return false;
        }
    }

    public function getQueryShop()
    {
        $query = '';
        $shop_context = Shop::getContext();

        $context = Context::getContext();

        if (isset($this->context->shop->id) && ($shop_context != Shop::CONTEXT_ALL || ($context->controller->multishop_context_group != false && $shop_context != Shop::CONTEXT_GROUP))) {
            $query = ' AND id_shop = '.(int)$this->context->shop->id;
        } elseif (isset($this->context->shop->id_shop_group)) {
            $id_shops = ShopGroup::getShopsFromGroup($this->context->shop->id_shop_group);

            $array_shop = array();
            foreach ($id_shops as $id_shop) {
                $array_shop[] = (int)$id_shop['id_shop'];
            }

            $query = ' AND id_shop IN ('.pSQL(implode(',', $array_shop)).')';
        }

        return $query;
    }
    
    public function getQueryShopList()
    {
        $query = '';
        $shop_context = Shop::getContext();

        $context = Context::getContext();

        if (isset($this->context->shop->id) && ($shop_context != Shop::CONTEXT_ALL || ($context->controller->multishop_context_group != false && $shop_context != Shop::CONTEXT_GROUP))) {
            $query = ' AND a.id_shop = '.(int)$this->context->shop->id;
        } elseif (isset($this->context->shop->id_shop_group)) {
            $id_shops = ShopGroup::getShopsFromGroup($this->context->shop->id_shop_group);

            $array_shop = array();
            foreach ($id_shops as $id_shop) {
                $array_shop[] = (int)$id_shop['id_shop'];
            }

            $query = ' AND a.id_shop IN ('.pSQL(implode(',', $array_shop)).')';
        }

        return $query;
    }
    
    public function processResetFilters($list_id = null)
    {
        $prefix = str_replace(array('admin', 'controller'), '', Tools::strtolower(get_class($this)));
        $filters = $this->context->cookie->getFamily($prefix.$this->table.'Filter_');
        
        foreach ($filters as $cookie_key => $filter) {
            if (strncmp($cookie_key, $prefix.$this->table.'Filter_', 7 + Tools::strlen($prefix.$this->table)) == 0) {
                $key = Tools::substr($cookie_key, 7 + Tools::strlen($prefix.$this->table));
                /* Table alias could be specified using a ! eg. alias!field */
                $tmp_tab = explode('!', $key);
                $key = (count($tmp_tab) > 1 ? $tmp_tab[1] : $tmp_tab[0]);
                unset($this->context->cookie->$cookie_key);
            }
        }

        if (isset($this->context->cookie->{'submitFilter'.$this->table})) {
            unset($this->context->cookie->{'submitFilter'.$this->table});
        }

        if (isset($this->context->cookie->{$prefix.$this->table.'Orderby'})) {
            unset($this->context->cookie->{$prefix.$this->table.'Orderby'});
        }

        if (isset($this->context->cookie->{$prefix.$this->table.'Orderway'})) {
            unset($this->context->cookie->{$prefix.$this->table.'Orderway'});
        }

        unset($_POST);
        $this->_filter = false;
        unset($this->_filterHaving);
        unset($this->_having);
        unset($this->context->cookie->erp_customer_order_state);
    }
    
    protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
    	if (version_compare(_PS_VERSION_, '1.7', '<')) {
            return parent::l($string, $class, $addslashes, $htmlentities);
    	} else {
            return Translate::getModuleTranslation('wic_erp', $string, 'AdminErpCustomerOrderController');
    	}
    }
}
