<?php
/**
* Module My Easy ERP Web In Color.
*
*  @author    Web In Color - addons@webincolor.fr
*  @version 2.9.9.1
*  @uses Prestashop modules
*  @since 1.0 - mai 2014
*  @copyright Copyright &copy; 2014, Web In Color
*  @license   http://www.webincolor.fr
*/

class WIC_Erp extends Module
{
    private $html = '';
    private $post_errors = array();
    private $log = array();

    public function __construct()
    {
        $this->author = 'Web In Color';
        $this->tab = 'shipping_logistics';
        $this->need_instance = 0;
        $this->module_key = 'bdcac571af5738252b4719773625a320';
        $this->name = 'wic_erp';
        $this->version = '2.9.9.1';
        $this->bootstrap = true;
        $this->dlcDluoActive = false;

        parent::__construct();

        $this->displayName = $this->l('My Easy ERP');
        $this->description = $this->l('Module My Easy ERP');
        $this->confirmUninstall = $this->l('Are you sure you want to delete this module ?');

        $config = Configuration::getMultiple(array('WIC_ERP_NOT_COMPLETE', 'WIC_STATE_ERP_COMPLETE'));

        if (!isset($config['WIC_ERP_NOT_COMPLETE'])
                            || !isset($config['WIC_STATE_ERP_COMPLETE'])
                            || empty($config['WIC_ERP_NOT_COMPLETE'])
                            || empty($config['WIC_STATE_ERP_COMPLETE'])) {
            $this->warning = $this->l('This module must be configured to use this module correctly.');
        }
        /* Retrocompatibility */
        $this->initContext();

        /* Run update process */
        $this->updateProcess();

        require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpSuppliers.php';
        require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpProducts.php';
        require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpOrder.php';
        require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpOrderDetail.php';
        require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpOrderState.php';
        
        //We verify if DLC/DLUO addons exists
        if (file_exists(_PS_MODULE_DIR_.'productsdlcdluo/ProductsDlcDluoClass.php')
                && file_exists(_PS_MODULE_DIR_.'productsdlcdluo/OrdersDlcDluo.php')) {
            $this->dlcDluoActive = true;
            require_once(_PS_MODULE_DIR_.'productsdlcdluo/ProductsDlcDluoClass.php');
            require_once(_PS_MODULE_DIR_.'productsdlcdluo/OrdersDlcDluo.php');
        }
        
        $this->css_path = $this->_path.'views/css/';
        $this->js_path = $this->_path.'views/js/';
    }

    /* Retrocompatibility 1.4/1.5 */
    private function initContext()
    {
        $this->context = Context::getContext();
        if (!$this->context->shop->id) {
            $this->context->shop->id = 1;
        }
    }

    /* Upgrade process */
    private function updateProcess()
    {
        $this->installed_version = Configuration::get('WIC_ERP_VERSION');
        if (Module::isInstalled('wic_erp')
            && version_compare($this->installed_version, $this->version, '<')) {
            $this->runUpgrade();
        }
    }

    public function runUpgrade()
    {
        /*  List of upgraded version existing */
        $files_version = array('2.4', '2.6', '2.7', '2.8', '2.9', '2.9.8.2', '2.9.8.5', '2.9.8.6', '2.9.8.7');

        $upgrade_path = dirname(__FILE__).'/upgrade/';

        foreach ($files_version as $version) {
            $file = $upgrade_path.'install-'.$version.'.php';
            if (version_compare($this->installed_version, $version, '<') && file_exists($file)) {
                include_once $file;
                call_user_func('upgrade_module_'.str_replace('.', '_', $version), $this);
            }
        }
    }

    /*****************************************************************************************************************
        * INSTALL METHOD
        *****************************************************************************************************************/
    public function install()
    {
        $id_lang_fr = Language::getIdByIso('fr');

        $sql = '';

        include dirname(__FILE__).'/sql/install.php';
        foreach ($sql as $s) {
            if (!Db::getInstance()->execute($s)) {
                return false;
            }
        }

        $admin_parent_erp = array();
        $admin_erp_customer_order = array();
        $admin_erp_product_order = array();
        $admin_erp_status_order = array();
        $admin_erp_supplier_orders = array();
        $admin_erp_suppliers = array();
        $admin_erp_products = array();
        $admin_erp_import = array();

        foreach (Language::getLanguages() as $language) {
            $admin_parent_erp[$language['id_lang']] = 'My Easy ERP';

            if ($language['id_lang'] == $id_lang_fr) {
                $admin_erp_customer_order[$language['id_lang']] = 'ERP - Commandes clients';
                $admin_erp_product_order[$language['id_lang']] = 'ERP - Achats produits';
                $admin_erp_status_order[$language['id_lang']] = 'ERP - Gestion de statuts';
                $admin_erp_supplier_orders[$language['id_lang']] = 'ERP - Commandes fournisseurs';
                $admin_erp_suppliers[$language['id_lang']] = 'ERP - Configuration fournisseurs';
                $admin_erp_products[$language['id_lang']] = 'ERP - Configuration produits';
                $admin_erp_import[$language['id_lang']] = 'ERP - Import cmdes fournisseurs';
            } else {
                $admin_erp_customer_order[$language['id_lang']] = 'ERP customer order';
                $admin_erp_product_order[$language['id_lang']] = 'ERP product order';
                $admin_erp_status_order[$language['id_lang']] = 'ERP status management';
                $admin_erp_supplier_orders[$language['id_lang']] = 'ERP supplier orders';
                $admin_erp_suppliers[$language['id_lang']] = 'ERP suppliers configuration';
                $admin_erp_products[$language['id_lang']] = 'ERP products configuration';
                $admin_erp_import[$language['id_lang']] = 'ERP Import order suppliers';
            }
        }

        if (!parent::install()
            || !$this->installParentModuleTab('AdminParentErp', $admin_parent_erp, 'logo.gif')
            || !$this->installModuleTab(
                                            'AdminErpCustomerOrder',
                                            $admin_erp_customer_order,
                                            Configuration::get('WIC_ERP_PARENT_TAB'),
                                            'logo.gif')
            || !$this->installModuleTab(
                                            'AdminErpProductOrder',
                                            $admin_erp_product_order,
                                            Configuration::get('WIC_ERP_PARENT_TAB'),
                                            'logo.gif')
            || !$this->installModuleTab(
                                            'AdminErpStatusOrder',
                                            $admin_erp_status_order,
                                            Configuration::get('WIC_ERP_PARENT_TAB'),
                                            'logo.gif')
            || !$this->installModuleTab(
                                            'AdminErpSupplierOrders',
                                            $admin_erp_supplier_orders,
                                            Configuration::get('WIC_ERP_PARENT_TAB'),
                                            'logo.gif')
            || !$this->installModuleTab(
                                            'AdminErpSuppliers',
                                            $admin_erp_suppliers,
                                            Configuration::get('WIC_ERP_PARENT_TAB'),
                                            'logo.gif')
            || !$this->installModuleTab(
                                            'AdminErpProducts',
                                            $admin_erp_products,
                                            Configuration::get('WIC_ERP_PARENT_TAB'),
                                            'logo.gif')
            || !$this->installModuleTab(
                                            'AdminErpImport',
                                            $admin_erp_import,
                                            Configuration::get('WIC_ERP_PARENT_TAB'),
                                            'logo.gif')
            || !$this->registerHook('actionUpdateQuantity')
            || !$this->registerHook('actionProductSave')
            || !$this->registerHook('actionOrderHistoryAddAfter')
            || !$this->registerHook('displayAdminOrder')
            || !$this->registerHook('displayAdminProductsExtra')
            || !$this->registerHook('displayBackOfficeHeader')
            || !$this->registerHook('displayOrderDetail')
            || !$this->installOrderStatus()
            || !$this->installDataInDatabase()) {
            return false;
        }
        
        Configuration::updateValue('WIC_ERP_NOT_UPD_STATE', '1,10,11');
        Configuration::updateValue('WIC_ERP_NOT_DISPLAY', '3,4,5,6,7,8');
        Configuration::updateValue('WIC_ERP_NOT_UPD_STK', '1,10,11');

        if (version_compare(_PS_VERSION_, '1.6.0.11', '>=')) {
            Configuration::updateValue('WIC_ERP_VERIFY_ORDER', '13');
            Configuration::updateValue('WIC_ERP_BACK_ORDER_STATE', '9');
            Configuration::updateValue('WIC_ERP_NOT_COMPLETE', '12,2,9,13');
        } else {
            Configuration::updateValue('WIC_ERP_VERIFY_ORDER', '9');
            Configuration::updateValue('WIC_ERP_NOT_COMPLETE', '12,2,9');
        }

        Configuration::updateValue('WIC_ERP_PREPARATION_STATE', '3');
        Configuration::updateValue('WIC_ERP_DISPLAY_FO_ORDER_DETAIL', 1);
        Configuration::updateValue('WIC_ERP_REFUNDED_STATE', '7');

        return true;
    }

    private function installParentModuleTab($tab_class, $tab_name, $img)
    {
        Tools::copy(_PS_MODULE_DIR_.$this->name.'/'.$img, _PS_IMG_DIR_.'t/'.$tab_class.'.gif');
        $tab = new Tab();
        $tab->name = $tab_name;
        $tab->class_name = $tab_class;
        $tab->module = $this->name;
        $tab->id_parent = 0;
        if (!$tab->save()) {
            return false;
        }

        $tab->updatePosition(0, 3);

        Configuration::updateValue('WIC_ERP_PARENT_TAB', $tab->id);

        return true;
    }

    public function installModuleTab($tab_class, $tab_name, $id_tab_parent, $img)
    {
        Tools::copy(_PS_MODULE_DIR_.$this->name.'/'.$img, _PS_IMG_DIR_.'t/'.$tab_class.'.gif');
        $tab = new Tab();
        $tab->name = $tab_name;
        $tab->class_name = $tab_class;
        $tab->module = $this->name;
        $tab->id_parent = $id_tab_parent;
        if (!$tab->save()) {
            return false;
        }

        return true;
    }

    private function installOrderStatus()
    {
        if (!Configuration::get('WIC_STATE_ERP_COMPLETE')) {
            $order_state = new OrderState();
            $order_state->name = array();
            foreach (Language::getLanguages() as $language) {
                if (Tools::strtolower($language['iso_code']) == 'fr') {
                    $order_state->name[$language['id_lang']] = 'Commande complète';
                } else {
                    $order_state->name[$language['id_lang']] = 'Complete order';
                }
            }
            $order_state->send_email = false;
            $order_state->color = '#f2bfff';
            $order_state->hidden = true;
            $order_state->delivery = false;
            $order_state->logable = true;
            $order_state->invoice = true;
            if ($order_state->add()) {
                Tools::copy(_PS_MODULE_DIR_.$this->name.'/logo.gif', dirname(__FILE__).'/../../img/os/'.(int) $order_state->id.'.gif');
            } else {
                return false;
            }
            Configuration::updateValue('WIC_STATE_ERP_COMPLETE', (int) $order_state->id);

            return true;
        } else {
            $order_state = new OrderState((int) Configuration::get('WIC_STATE_ERP_COMPLETE'));
            if (!Validate::isLoadedObject($order_state)) {
                $order_state = new OrderState();
                $order_state->name = array();
                foreach (Language::getLanguages() as $language) {
                    if (Tools::strtolower($language['iso_code']) == 'fr') {
                        $order_state->name[$language['id_lang']] = 'Commande complète';
                    } else {
                        $order_state->name[$language['id_lang']] = 'Complete order';
                    }
                }
                $order_state->send_email = false;
                $order_state->color = '#f2bfff';
                $order_state->hidden = true;
                $order_state->delivery = false;
                $order_state->logable = true;
                $order_state->invoice = true;
                if ($order_state->add()) {
                    Tools::copy(_PS_MODULE_DIR_.$this->name.'/logo.gif', dirname(__FILE__).'/../../img/os/'.(int) $order_state->id.'.gif');
                } else {
                    return false;
                }
                Configuration::updateValue('WIC_STATE_ERP_COMPLETE', (int) $order_state->id);

                return true;
            }

            return true;
        }
    }

    private function installDataInDatabase()
    {
        /* We verify if we have data in this table */
        $data = Db::getInstance()->ExecuteS('SELECT `id_erp_order_state` FROM `'._DB_PREFIX_.'erp_order_state`;');

        if (!$data) {
            $query = 'INSERT INTO
					`'._DB_PREFIX_.'erp_order_state`
					(`id_erp_order_state`,
						`delivery_note`,
						`editable`,
						`receipt_state`,
						`pending_receipt`,
						`enclosed`,
						`color`)
					VALUES
					(1, 0, 1, 0, 0, 0, \'#faab00\'),
					(2, 1, 0, 0, 0, 0, \'#273cff\'),
					(3, 0, 0, 0, 1, 0, \'#ff37f5\'),
					(4, 0, 0, 1, 1, 0, \'#ff3e33\'),
					(5, 0, 0, 1, 0, 1, \'#00d60c\'),
					(6, 0, 0, 0, 0, 1, \'#666666\');';

            if (!Db::getInstance()->Execute($query)) {
                return false;
            }

            unset($query);

            foreach (Language::getLanguages() as $language) {
                if ($language['id_lang'] != Language::getIdByIso('fr')) {
                    $query = 'INSERT INTO
							`'._DB_PREFIX_.'erp_order_state_lang`
							(`id_erp_order_state`,
							`id_lang`,
							`name`)
							VALUES
							(1, '.(int) $language['id_lang'].', \'1 - Creation in progress\'),
							(2, '.(int) $language['id_lang'].', \'2 - Order validated\'),
							(3, '.(int) $language['id_lang'].', \'3 - Pending receipt\'),
							(4, '.(int) $language['id_lang'].', \'4 - Order received in part\'),
							(5, '.(int) $language['id_lang'].', \'5 - Order received completely\'),
							(6, '.(int) $language['id_lang'].', \'6 - Order canceled\')';
                } else {
                    $query = 'INSERT INTO
							`'._DB_PREFIX_.'erp_order_state_lang`
							(`id_erp_order_state`,
							`id_lang`,
							`name`)
							VALUES
							(1, '.(int) $language['id_lang'].', \'1 - Création en cours\'),
							(2, '.(int) $language['id_lang'].', \'2 - Commande validée\'),
							(3, '.(int) $language['id_lang'].', \'3 - En attente de réception\'),
							(4, '.(int) $language['id_lang'].', \'4 - Commande réceptionnée partiellement\'),
							(5, '.(int) $language['id_lang'].', \'5 - Commande réceptionnée totalement\'),
							(6, '.(int) $language['id_lang'].', \'6 - Commande annulée\')';
                }

                if (!Db::getInstance()->Execute($query)) {
                    return false;
                }

                unset($query);
            }
        }

        return true;
    }

    public function uninstall()
    {
        $this->uninstallModuleTab('AdminErpStockTransfert');
        
        if (!parent::uninstall()
            || (!$this->uninstallModuleTab('AdminErpCustomerOrder') && !$this->uninstallModuleTab('AdminErpCustomerOrder16'))
            || (!$this->uninstallModuleTab('AdminErpProductOrder') && !$this->uninstallModuleTab('AdminErpProductOrder16'))
            || (!$this->uninstallModuleTab('AdminErpStatusOrder') && !$this->uninstallModuleTab('AdminErpStatusOrder16'))
            || (!$this->uninstallModuleTab('AdminErpSupplierOrders') && !$this->uninstallModuleTab('AdminErpSupplierOrders16'))
            || (!$this->uninstallModuleTab('AdminErpProducts') && !$this->uninstallModuleTab('AdminErpProducts16'))
            || (!$this->uninstallModuleTab('AdminErpSuppliers') && !$this->uninstallModuleTab('AdminErpSuppliers16'))
            || !$this->uninstallModuleTab('AdminErpImport')
            || !$this->uninstallModuleTab('AdminParentErp')) {
            return false;
        }

        return true;
    }

    private function uninstallModuleTab($tab_class)
    {
        $id_tab = Tab::getIdFromClassName($tab_class);
        if ($id_tab != 0) {
            $tab = new Tab($id_tab);
            $tab->delete();

            return true;
        }

        return false;
    }

    /*************************************************************************************************
    DISPLAY BACK OFFICE MANAGEMENT
    **************************************************************************************************/
    /**
     * Loads asset resources.
     */
    public function loadAsset()
    {
        $css_compatibility = $js_compatibility = array();

        /* Load CSS */
        $css = array(
                $this->css_path.'bootstrap-select.min.css',
                $this->css_path.'DT_bootstrap.css',
                $this->css_path.'fix.css',
                );

        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            $css_compatibility = array(
                            $this->css_path.'bootstrap.css',
                            $this->css_path.'bootstrap.extend.css',
                            $this->css_path.'bootstrap-responsive.min.css',
                            $this->css_path.'font-awesome.min.css',
                            $this->css_path.'back.1.5.css',
                            );
            $css = array_merge($css_compatibility, $css);
        }
        $this->context->controller->addCSS($css, 'all');

        unset($css, $css_compatibility);

        $this->context->controller->addJquery();

        /* Load JS */
        $js = array(
                        $this->js_path.'bootstrap-select.min.js',
                        $this->js_path.'bootstrap-dialog.js',
                        $this->js_path.'jquery.autosize.min.js',
                        $this->js_path.'jquery.dataTables.js',
                        $this->js_path.'DT_bootstrap.js',
                        $this->js_path.'dynamic_table_init.js',
                        $this->js_path.'jscolor.js',
                        $this->js_path.'module.js',
                        );

        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            $js_compatibility = array(
                            $this->js_path.'bootstrap.min.js',
                            );
            $js = array_merge($js_compatibility, $js);
        }
        $this->context->controller->addJS($js);

        /* Clean memory */
        unset($js, $js_compatibility);
    }

    public function getContent()
    {
        $this->postProcess();
        
        $errors = '';
        if (count($this->post_errors)) {
            foreach ($this->post_errors as $err) {
                $errors .= $this->displayError($err);
            }
        }

        $this->loadAsset();

        $steps_translations = array(
            'updateConfig' => $this->l('Configuration'),
            'updateSuppliers' => $this->l('Suppliers'),
            'analyzeOrders' => $this->l('Orders'),
            'calculateStock' => $this->l('Stock'),
        );
        if (Tools::isSubmit('btnSubmit')) {
            $is_submit = 1;
        } else {
            $is_submit = 0;
        }
        
        /**
         * If values have been submitted in the form, process.
         */
        $smarty = $this->context->smarty;
        $smarty->assign('module_dir', $this->_path);
        $smarty->assign('update_msg', $errors);
        $smarty->assign('wic_config_form', $this->renderForm());
        $smarty->assign('version', $this->version);
        $smarty->assign('steps', Tools::jsonEncode($steps_translations));
        $smarty->assign('is_submit', $is_submit);
        
        return $this->display(__FILE__, 'views/templates/admin/configure.tpl');
        
        //$this->displayForm();
        //return $this->html;
    }
    
    public function renderForm()
    {
        /**
         * 1.5 version doesn't include switch type
         */
        $bool_type = version_compare(_PS_VERSION_, '1.6', '<') ? 'radio' : 'switch';
        
        $order_states = OrderState::getOrderStates($this->context->language->id);
        
        //We declare form var which contains each step form
        $form = array();
        
        $fieldsStockOptions = null;
        
        if (version_compare(_PS_VERSION_, '1.6.0.11', '>=')) {
            $fieldsStockOptions = array(
                'type' => 'select',
                'label' => $this->l('When your order is paid if you haven\'t all products in stock, in which status do you want to put this order ?'),
                'name' => 'WIC_ERP_BACK_ORDER_STATE',
                'required' => true,
                'class' => 'chosen-select',
                'options' => array(
                    'query' => $order_states,
                    'id' => 'id_order_state',
                    'name' => 'name'
                ),
                'desc' => '<div class="alert alert-info">'.$this->l('For example: For orders in status "Payment accepted", if you haven\'t all products ti this order, put your order in status "Waiting for Replenishment (Paid)"').'</div>',
            );
        }
        
        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
            $warehouses = Warehouse::getWarehouses();
            $fieldsWarehouseOptions = array(
               'type' => 'select',
               'label' => $this->l('Please select default warehouse'),
               'name' => 'WIC_ERP_DEFAULT_WAREHOUSE',
               'required' => true,
               'class' => 'chosen-select',
               'options' => array(
                   'query' => $warehouses,
                   'id' => 'id_warehouse',
                   'name' => 'name'
               ),
               'desc' => '<div class="alert alert-danger">'.$this->l('If you have enabled inventory management Advanced there may be subsequent commands for activation are not related to a warehouse. It is mandatory to attach these commands to a default warehouse.').'</div>',
            );
        } else {
            $fieldsWarehouseOptions = null;
        } 
        
        /* Step 1 */
        $fieldsStock = array(
            'form' => array(
                'class' => 'panel',
                'legend' => array(
                    'title' => $this->l('Configure your stock management'),
                ),
                'input' => array(
                     $fieldsWarehouseOptions,
                    array(
                        'type' => 'select',
                        'label' => $this->l('Select the order status that does not decrease the stock when order is placed'),
                        'name' => version_compare(_PS_VERSION_, '1.6.1.2', '<') ? 'WIC_ERP_NOT_UPD_STK[]' : 'WIC_ERP_NOT_UPD_STK',
                        'required' => true,
                        'multiple' => true,
                        'class' => 'chosen-select',
                        'options' => array(
                            'query' => $order_states,
                            'id' => 'id_order_state',
                            'name' => 'name'
                        ),
                        'desc' => '<div class="alert alert-info">'.$this->l('You can select the order status which doesn\'t update stock when order is placed (eg:pending payment status).').'</div>',
                    ),
                    array(
                        'type' => $bool_type,
                        'label' => $this->l('Prioritize orders that can be sent'),
                        'name' => 'WIC_ERP_PRORITIZE_ORDER',
                        'is_bool' => true,
                        'class' => 't',
                        'desc' => '<div class="alert alert-danger">'.$this->l('WARNING : We advise you to put a number of days (below) which will be used to reserve some stock for older orders even if there are not shippable. Shipment would then leave your warehouse as soon as possible.').'</div>',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Number of days after which an order has priority'),
                        'name' => 'WIC_ERP_NUMBER_DAY',
                        'placeholder' => $this->l('In days'),
                        'desc' => $this->l('You can leave it blank if you do not want to make it a priority. Only works if "Prioritize orders that can be sent" is checked.'),
                        'required' => false
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Select the order status to which you want the previous state is verified before updating or stock control'),
                        'name' => 'WIC_ERP_VERIFY_ORDER',
                        'required' => true,
                        'class' => 'chosen-select',
                        'options' => array(
                            'query' => $order_states,
                            'id' => 'id_order_state',
                            'name' => 'name'
                        ),
                        'desc' => '<div class="alert alert-info">'.$this->l('For example: For orders in status "Waiting for Replenishment" we must verify that the order is not pending payment before booking the stock.').'</div>',
                    ),
                    $fieldsStockOptions
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => version_compare(_PS_VERSION_, '1.6', '<') ? 'button btn btn-primary' : 'button btn btn-default pull-right',
                ),
            ),
        );
        
        /*Step 2 */
        $fieldsStatus = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Configure your order status'),
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Complete status'),
                        'name' => 'WIC_STATE_ERP_COMPLETE',
                        'required' => true,
                        'class' => 'chosen-select',
                        'options' => array(
                            'query' => $order_states,
                            'id' => 'id_order_state',
                            'name' => 'name'
                        ),
                        'desc' => '<div class="alert alert-info">'.$this->l('Select Prestashop complete status.').'</div>',
                    ),
                    array(
                        'type' => $bool_type,
                        'label' => $this->l('Complete order status is a prefix for new status'),
                        'name' => 'WIC_ERP_COMPLETE_IS_PREFIX',
                        'is_bool' => true,
                        'class' => 't',
                        'desc' => '<div class="alert alert-danger">'.$this->l('WARNING : If you define as prefix complete order status we create a new status with "Status orders to prepare". For example : Complete order - Payment accepted is a new order status.').'</div>',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Status ignored orders'),
                        'name' => version_compare(_PS_VERSION_, '1.6.1.2', '<') ? 'WIC_ERP_NOT_UPD_STATE[]' : 'WIC_ERP_NOT_UPD_STATE',
                        'required' => true,
                        'multiple' => true,
                        'class' => 'chosen-select',
                        'options' => array(
                            'query' => $order_states,
                            'id' => 'id_order_state',
                            'name' => 'name'
                        ),
                        'desc' => '<div class="alert alert-info">'.$this->l('Select status which have not update the order status automatically and and for which we do not reserve stock.').'</div>',
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Status orders to prepare'),
                        'name' => version_compare(_PS_VERSION_, '1.6.1.2', '<') ? 'WIC_ERP_NOT_COMPLETE[]' : 'WIC_ERP_NOT_COMPLETE',
                        'required' => true,
                        'multiple' => true,
                        'class' => 'chosen-select',
                        'options' => array(
                            'query' => $order_states,
                            'id' => 'id_order_state',
                            'name' => 'name'
                        ),
                        'desc' => '<div class="alert alert-info">'.$this->l('Select status for which orders are not considered complete.').'</div>',
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Status not appear in the order picking'),
                        'name' => version_compare(_PS_VERSION_, '1.6.1.2', '<') ? 'WIC_ERP_NOT_DISPLAY[]' : 'WIC_ERP_NOT_DISPLAY',
                        'required' => true,
                        'multiple' => true,
                        'class' => 'chosen-select',
                        'options' => array(
                            'query' => $order_states,
                            'id' => 'id_order_state',
                            'name' => 'name'
                        ),
                        'desc' => '<div class="alert alert-info">'.$this->l('Select status for which orders don\'t appear in the order picking.').'</div>',
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Orders status preparation in progress'),
                        'name' => version_compare(_PS_VERSION_, '1.6.1.2', '<') ? 'WIC_ERP_PREPARATION_STATE[]' : 'WIC_ERP_PREPARATION_STATE',
                        'required' => true,
                        'multiple' => true,
                        'class' => 'chosen-select',
                        'options' => array(
                            'query' => $order_states,
                            'id' => 'id_order_state',
                            'name' => 'name'
                        ),
                        'desc' => '<div class="alert alert-info">'.$this->l('Select status for which orders in preparation.').'</div>',
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Orders status with customer thread.'),
                        'name' => version_compare(_PS_VERSION_, '1.6.1.2', '<') ? 'WIC_ERP_CUSTOMER_THREAD[]' : 'WIC_ERP_CUSTOMER_THREAD',
                        'required' => true,
                        'multiple' => true,
                        'class' => 'chosen-select',
                        'options' => array(
                            'query' => $order_states,
                            'id' => 'id_order_state',
                            'name' => 'name'
                        ),
                        'desc' => '<div class="alert alert-info">'.$this->l('When status is in customer thread order is not valid but you want add product on supplier order.').'</div>',
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Refund order State'),
                        'name' => 'WIC_ERP_REFUNDED_STATE',
                        'required' => true,
                        'class' => 'chosen-select',
                        'options' => array(
                            'query' => $order_states,
                            'id' => 'id_order_state',
                            'name' => 'name'
                        ),
                        'desc' => '<div class="alert alert-info">'.$this->l('If you refund an order by partial refund and this order is completely refund we update state for you.').'</div>',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => version_compare(_PS_VERSION_, '1.6', '<') ? 'button btn btn-primary' : 'button btn btn-default pull-right',
                ),
            ),
        );
        
        /*Step 3 */
        $fieldsOrderProduct = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Configuration order product'),
                ),
                'input' => array(
                    array(
                        'type' => $bool_type,
                        'label' => $this->l('Allow orders disabled products'),
                        'name' => 'WIC_ERP_DISABLED_PRODUCT',
                        'is_bool' => true,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => version_compare(_PS_VERSION_, '1.6', '<') ? 'button btn btn-primary' : 'button btn btn-default pull-right',
                ),
            ),
        );
        
        /* Step 4 */
        $stockManagement = array(
            array('id' => 'just-in-time', 'name' => $this->l('Just-in-time distribution')),
            array('id' => 'normal', 'name' => $this->l('Management classic stocks')),
            );
        
        $stockMethod = array(
            array('id' => 'expert', 'name' => $this->l('expert mode')),
            array('id' => 'normal', 'name' => $this->l('normal distribution')),
            );
        
        $fieldsStockManagement = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Stock management settings'),
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('How do you manage your stock ?'),
                        'name' => 'WIC_ERP_STOCK_MANAGEMENT',
                        'required' => true,
                        'onchange' => 'DisplayField(\'WIC_ERP_STOCK_MANAGEMENT\',\'stockManagement\');DisplayField(\'WIC_ERP_STOCK_MANAGEMENT\',\'productManagement\');DisplayField(\'WIC_ERP_STOCK_MANAGEMENT\',\'supplierManagement\');DisplayField(\'WIC_ERP_STOCK_MANAGEMENT\',\'updateManagement\');',
                        'class' => 'chosen-select',
                        'options' => array(
                            'query' => $stockManagement,
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'desc' => '<div class="alert alert-info">'.$this->l('The "Management classic stocks" method adopts a system control variable but fixed time quantity to the extent that this is the achievement of a given level of stock (called the control point) that triggers replenishment order.').'</div>',
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Method of stock management'),
                        'name' => 'WIC_ERP_STOCK_METHOD',
                        'id' => 'stockManagement',
                        'required' => true,
                        'onchange' => 'DisplayField(\'WIC_ERP_STOCK_METHOD\',\'stockMethod\');',
                        'class' => Configuration::get('WIC_ERP_STOCK_MANAGEMENT') == 'normal' ? 'chosen-select' : 'chosen-select displayNone',
                        'options' => array(
                            'query' => $stockMethod,
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'desc' => '<div class="alert alert-info">
                                    <ul style="margin-top: 3px">
                                        <li><b>'.$this->l('Expert mode:').'</b>'
                                            .$this->l('the advantage of being simple and being rather traditional. Moreover, with these calculations, we rely on certainties such as maximum demand and time of receipt. There is still a risk, albeit small but neglected here, the delivery time is increased or the maximum demand varies.').
                                        '</li>
                                        <li><b>'.$this->l('Normal distribution:').'</b>'
                                            .$this->l('This method takes into account random variables and thus covers a greater potential risk. This method is based on probability and incorporating the risk factor, and thus closer to reality. We also note the usefulness of this statistical approach because it allows us to reduce safety stock and therefore save space, storage costs and reduce the risk of perishability of our product.').
                                        '</li>
                                    </ul>
                                </div>',
                    ),
                    array(
                        'type' => 'free',
                        'label' => $this->l('Select a level of satisfaction'),
                        'name' => 'WIC_ERP_STOCK_NORMAL_RATE',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => version_compare(_PS_VERSION_, '1.6', '<') ? 'button btn btn-primary' : 'button btn btn-default pull-right',
                ),
            ),
        );
        
        /* Step 5 */
        $fieldsSupplierConfig = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Suppliers default configuration'),
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'label' => $this->l('Default delivery suppliers'),
                        'name' => 'WIC_ERP_DELIVERY_SUPPLIERS',
                        'id' => 'supplierManagement',
                        'placeholder' => $this->l('In days'),
                        'required' => true,
                        'class' => Configuration::get('WIC_ERP_STOCK_MANAGEMENT') == 'normal' ? 'chosen-select' : 'chosen-select displayNone',
                    ),
                    array(
                        'type' => $bool_type,
                        'label' => $this->l('Send email to supplier when you place order'),
                        'name' => 'WIC_ERP_SEND_EMAIL_SUPPLIER',
                        'is_bool' => true,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => $bool_type,
                        'label' => $this->l('Send only PDF when you send an email (NO CSV file)'),
                        'name' => 'WIC_ERP_SEND_ONLY_PDF',
                        'is_bool' => true,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                    array(
                        'type' => $bool_type,
                        'label' => $this->l('Recalculate stocks needs for each product when you update supplier.'),
                        'name' => 'WIC_ERP_RECALCULATE_BY_SUPPLIER',
                        'is_bool' => true,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ),
                        ),
                        'desc' => '<div class="alert alert-danger '.(Configuration::get('WIC_ERP_STOCK_MANAGEMENT') != 'normal' ? 'displayNone' : '').'">'.$this->l('WARNING : This option can use more ressources on your server foreach upadte suppliers. You could take timeout server if you have a lot of product associated with suppliers updated.').'</div>',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => version_compare(_PS_VERSION_, '1.6', '<') ? 'button btn btn-primary' : 'button btn btn-default pull-right',
                ),
            ),
        );

        /* Step 6 */
        $statsDays = array(
            array('id' => '30', 'name' => $this->l('1 month')),
            array('id' => '90', 'name' => $this->l('3 months')),
            array('id' => '180', 'name' => $this->l('6 months')),
            array('id' => '365', 'name' => $this->l('1 year')),
            );
        
        $fieldsProductConfiguration = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Products default configuration'),
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Collect statistics from'),
                        'name' => 'WIC_ERP_STAT_DAYS',
                        'required' => true,
                        'id' => 'productManagement',
                        'class' => Configuration::get('WIC_ERP_STOCK_MANAGEMENT') == 'normal' ? 'chosen-select' : 'chosen-select displayNoneFieldset',
                        'options' => array(
                            'query' => $statsDays,
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'desc' => '<div class="alert alert-info">'.$this->l('Thank you to select the duration of your statistics gathering').'</div>',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Minimum stock product default'),
                        'name' => 'WIC_ERP_STK_MIN',
                        'required' => true,
                        'hint' => $this->l('Enter a minimum stock default product if your product does not fall within the statistical calculation. '
                                        .'(Example: no sale on this product for the last 6 months)'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Safety stock product default'),
                        'name' => 'WIC_ERP_STK_SAFETY',
                        'required' => true,
                        'hint' => $this->l('Enter a safety stock default product if your product does not fall within the statistical calculation. '
                                        .'(Example: no sale on this product for the last 6 months)'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => version_compare(_PS_VERSION_, '1.6', '<') ? 'button btn btn-primary' : 'button btn btn-default pull-right',
                ),
            ),
        );

        /*Step 7 */
        $supplier_exists = ErpSuppliers::suppliersExist();
        $products_exists = ErpProducts::productsExist();
        
        $fieldsUpdate = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Update configuration'),
                ),
                'input' => array(
                    array(
                        'type' => $supplier_exists ? $bool_type : 'hidden',
                        'label' => $this->l('Update ERP suppliers'),
                        'name' => 'updateSuppliers',
                        'is_bool' => true,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ),
                        ),
                        'desc' => '<div class="alert alert-danger '.(Configuration::get('WIC_ERP_STOCK_MANAGEMENT') != 'normal' ? 'displayNoneFieldset' : '').'">'.$this->l('WARNING : You have already configured the module! Do you want to Reset all your deadlines suppliers based on data entered?').'</div>',
                    ),
                    array(
                        'type' => $products_exists ? $bool_type : 'hidden',
                        'label' => $this->l('Update ERP suppliers'),
                        'name' => 'updateProducts',
                        'is_bool' => true,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ),
                        ),
                        'desc' => '<div class="alert alert-danger '.(Configuration::get('WIC_ERP_STOCK_MANAGEMENT') != 'normal' ? 'displayNoneFieldset' : '').'">'.$this->l('WARNING : You have already configured the module! Do you want to Reset all your products data?').'</div>',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'id' => 'updateManagement',
                    'class' => version_compare(_PS_VERSION_, '1.6', '<') ? (Configuration::get('WIC_ERP_STOCK_MANAGEMENT') == 'normal' ? 'button btn btn-primary' : 'button btn btn-primary displayNoneFieldset') : 'button btn btn-default pull-right',
                ),
            ),
        );
        
        /* Step 8 */
        $cronOptions = array(
            array('id' => '30', 'name' => $this->l('1 month')),
            array('id' => '90', 'name' => $this->l('3 months')),
            array('id' => '180', 'name' => $this->l('6 months')),
            array('id' => '365', 'name' => $this->l('1 year')),
            );
        
        $fieldsCronJob = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Cron Job configuration'),
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Recalculate your stock'),
                        'name' => 'WIC_ERP_CRON_DAYS',
                        'required' => true,
                        'class' => 'chosen-select',
                        'options' => array(
                            'query' => $cronOptions,
                            'id' => 'id',
                            'name' => 'name'
                        ),
                        'desc' => '<div class="alert alert-info">'.$this->l('Thank you to select the duration of your cron job execution').'</div>',
                    ),
                    array(
                        'type' => 'free',
                        'name' => 'cronText',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => version_compare(_PS_VERSION_, '1.6', '<') ? 'button btn btn-primary' : 'button btn btn-default pull-right',
                ),
            ),
        );
        
        /*Step 9 */
        $fieldsDisplayFO = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Configuration display front office order detail'),
                ),
                'input' => array(
                    array(
                        'type' => $bool_type,
                        'label' => $this->l('Display order detail to your customer'),
                        'name' => 'WIC_ERP_DISPLAY_FO_ORDER_DETAIL',
                        'is_bool' => true,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ),
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => version_compare(_PS_VERSION_, '1.6', '<') ? 'button btn btn-primary' : 'button btn btn-default pull-right',
                ),
            ),
        );
        
        /*Step 10 */
        $fieldsReceiptOrder = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Receipt order supplier'),
                ),
                'input' => array(
                    array(
                        'type' => $bool_type,
                        'label' => $this->l('Allow manual input quantity'),
                        'name' => 'WIC_ERP_ALLOW_MQU',
                        'is_bool' => true,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ),
                        ),
                        'desc' => '<div class="alert alert-danger">'.$this->l('When you receipt an order supplier, allow manual input quantity ?').'</div>',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => version_compare(_PS_VERSION_, '1.6', '<') ? 'button btn btn-primary' : 'button btn btn-default pull-right',
                ),
            ),
        );
        
        $warehouses = Warehouse::getWarehouses();
        /*Step 11 */
        $fieldsStockTransfert = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Stock Transfert'),
                ),
                'input' => array(
                    array(
                        'type' => $bool_type,
                        'label' => $this->l('Display Stock transfert view'),
                        'name' => 'WIC_ERP_STOCK_TRANSFERT',
                        'is_bool' => true,
                        'class' => 't',
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->l('Yes'),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->l('No'),
                            ),
                        ),
                        'desc' => $this->l('Display Stock transfert view and create menu'),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Please select default warehouse from'),
                        'name' => 'WIC_ERP_WAREHOUSE_FROM_DEFAULT',
                        'required' => true,
                        'class' => 'chosen-select',
                        'options' => array(
                            'query' => $warehouses,
                            'id' => 'id_warehouse',
                            'name' => 'name'
                        ),  
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Please select default warehouse to'),
                        'name' => 'WIC_ERP_WAREHOUSE_TO_DEFAULT',
                        'required' => true,
                        'class' => 'chosen-select',
                        'options' => array(
                            'query' => $warehouses,
                            'id' => 'id_warehouse',
                            'name' => 'name'
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => version_compare(_PS_VERSION_, '1.6', '<') ? 'button btn btn-primary' : 'button btn btn-default pull-right',
                ),
            ),
        );
        
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $lang = new Language((int) Configuration::get('PS_LANG_DEFAULT'));
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') ? Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG') : 0;
        $this->fields_form = array();
        $helper->identifier = $this->identifier;
        $helper->submit_action = 'btnSubmit';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id, 
        );
        
        return $helper->generateForm(array($fieldsStock, $fieldsStatus, $fieldsOrderProduct, $fieldsStockManagement, $fieldsSupplierConfig, $fieldsProductConfiguration, $fieldsUpdate, $fieldsCronJob, $fieldsDisplayFO, $fieldsReceiptOrder, $fieldsStockTransfert));
    }
    
    public function getSpecificField()
    {
        $html = '
            <div class="row'.(Configuration::get('WIC_ERP_STOCK_METHOD') == 'normal' ? '' : ' displayNone').'" id="stockMethod">
                <div class="col-lg-5">
                    <table cellspacing="0" cellpadding="0" class="table" style="width: 30em;">
                        <tbody>
                            <tr>
                                <th>&nbsp;</th>
                                <th>'.$this->l('Service rate %').'</th>
                                <th>'.$this->l('Risk of rupture %').'</th>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" name="WIC_ERP_STOCK_NORMAL_RATE" value="0.01" '.(Configuration::get('WIC_ERP_STOCK_NORMAL_RATE') == 0.01 ? 'checked="checked"' : '').'>
                                </td>
                                <td>'.$this->l('50').'</td>
                                <td>'.$this->l('50').'</td>
                            </tr>
                            <tr class="alt_row">
                                <td>
                                    <input type="radio" name="WIC_ERP_STOCK_NORMAL_RATE" value="1" '.(Configuration::get('WIC_ERP_STOCK_NORMAL_RATE') == 1 ? 'checked="checked"' : '').'>
                                </td>
                                <td>'.$this->l('84').'</td>
                                <td>'.$this->l('16').'</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" name="WIC_ERP_STOCK_NORMAL_RATE" value="1.3" '.(Configuration::get('WIC_ERP_STOCK_NORMAL_RATE') == 1.3 ? 'checked="checked"' : '').'>
                                </td>
                                <td>'.$this->l('90').'</td>
                                <td>'.$this->l('10').'</td>
                            </tr>
                            <tr class="alt_row">
                                <td>
                                    <input type="radio" name="WIC_ERP_STOCK_NORMAL_RATE" value="1.6" '.(Configuration::get('WIC_ERP_STOCK_NORMAL_RATE') == 1.6 ? 'checked="checked"' : '').'>
                                </td>
                                <td>'.$this->l('95').'</td>
                                <td>'.$this->l('5').'</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" name="WIC_ERP_STOCK_NORMAL_RATE" value="2" '.((Configuration::get('WIC_ERP_STOCK_NORMAL_RATE') == 2 || !Configuration::get('WIC_ERP_STOCK_NORMAL_RATE')) ? 'checked="checked"' : '').'>
                                </td>
                                <td>'.$this->l('97.5').'</td>
                                <td>'.$this->l('2.5').'</td>
                            </tr>
                            <tr class="alt_row">
                                <td>
                                    <input type="radio" name="WIC_ERP_STOCK_NORMAL_RATE" value="2.3" '.(Configuration::get('WIC_ERP_STOCK_NORMAL_RATE') == 2.3 ? 'checked="checked"' : '').'>
                                </td>
                                <td>'.$this->l('99').'</td>
                                <td>'.$this->l('1').'</td>
                            </tr>
                            <tr>
                                <td>
                                    <input type="radio" name="WIC_ERP_STOCK_NORMAL_RATE" value="2.6" '.(Configuration::get('WIC_ERP_STOCK_NORMAL_RATE') == 2.6 ? 'checked="checked"' : '').'>
                                </td>
                                <td>'.$this->l('99.5').'</td>
                                <td>'.$this->l('0.5').'</td>
                            </tr>
                            <tr class="alt_row">
                                <td>
                                    <input type="radio" name="WIC_ERP_STOCK_NORMAL_RATE" value="3" '.(Configuration::get('WIC_ERP_STOCK_NORMAL_RATE') == 3 ? 'checked="checked"' : '').'>
                                </td>
                                <td>'.$this->l('100').'</td>
                                <td>'.$this->l('0').'</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>';
        
        return $html;
    }
    
    public function getCronInstruction()
    {
        $html = '
            <div class="col-lg-12">
                <div class="warn alert alert-danger">
                    <ul style="margin-top: 3px">
                        <li>'.$this->l('Configure your cron job every day with this URL').'
                            <a href="'
                                    .Tools::getProtocol()
                                    .$_SERVER['HTTP_HOST']
                                    .__PS_BASE_URI__.'modules/'.$this->name.'/tools/erp_calculation.php?token='
                                    .sha1(_COOKIE_KEY_.'wic_erp').'">'
                                        .Tools::getProtocol()
                                        .$_SERVER['HTTP_HOST'].__PS_BASE_URI__.'modules/'.$this->name.'/tools/erp_calculation.php?token='
                                        .sha1(_COOKIE_KEY_.'wic_erp').
                            '</a>
                        </li>
                    </ul>                           
                </div>
            </div>
            <a class="btn btn-default" href="'
                .Tools::getProtocol()
                .$_SERVER['HTTP_HOST']
                .__PS_BASE_URI__.'modules/'.$this->name.'/tools/erp_calculation.php?manual=1&token='
                .sha1(_COOKIE_KEY_.'wic_erp').'"><i class="icon-AdminTools"></i> '.$this->l('Launch manually this task !').'</a>';
        
        return $html;
    }
    
    public function getConfigFieldsValues()
    {
        return array(
            'WIC_ERP_NOT_UPD_STK[]' => Tools::getValue('WIC_ERP_NOT_UPD_STK', explode(',', Configuration::get('WIC_ERP_NOT_UPD_STK'))),
            'WIC_ERP_NOT_COMPLETE[]' => Tools::getValue('WIC_ERP_NOT_COMPLETE', explode(',', Configuration::get('WIC_ERP_NOT_COMPLETE'))),
            'WIC_ERP_NOT_DISPLAY[]' => Tools::getValue('WIC_ERP_NOT_DISPLAY', explode(',', Configuration::get('WIC_ERP_NOT_DISPLAY'))),
            'WIC_ERP_NOT_UPD_STATE[]' => Tools::getValue('WIC_ERP_NOT_UPD_STATE', explode(',', Configuration::get('WIC_ERP_NOT_UPD_STATE'))),
            'WIC_ERP_PREPARATION_STATE[]' => Tools::getValue('WIC_ERP_PREPARATION_STATE', explode(',', Configuration::get('WIC_ERP_PREPARATION_STATE'))),
            'WIC_ERP_CUSTOMER_THREAD[]' => Tools::getValue('WIC_ERP_CUSTOMER_THREAD', explode(',', Configuration::get('WIC_ERP_CUSTOMER_THREAD'))),
            'WIC_ERP_STOCK_MANAGEMENT' => Tools::getValue('WIC_ERP_STOCK_MANAGEMENT', Configuration::get('WIC_ERP_STOCK_MANAGEMENT')),
            'WIC_ERP_STK_SAFETY' => Tools::getValue('WIC_ERP_STK_SAFETY', Configuration::get('WIC_ERP_STK_SAFETY')),
            'WIC_ERP_STK_MIN' => Tools::getValue('WIC_ERP_STK_MIN', Configuration::get('WIC_ERP_STK_MIN')),
            'WIC_ERP_BACK_ORDER_STATE' => Tools::getValue('WIC_ERP_BACK_ORDER_STATE', Configuration::get('WIC_ERP_BACK_ORDER_STATE')),
            'WIC_STATE_ERP_COMPLETE' => Tools::getValue('WIC_STATE_ERP_COMPLETE', Configuration::get('WIC_STATE_ERP_COMPLETE')),
            'WIC_ERP_STOCK_MANAGEMENT' => Tools::getValue('WIC_ERP_STOCK_MANAGEMENT', Configuration::get('WIC_ERP_STOCK_MANAGEMENT')),
            'WIC_ERP_DISABLED_PRODUCT' => Tools::getValue('WIC_ERP_DISABLED_PRODUCT', Configuration::get('WIC_ERP_DISABLED_PRODUCT')),
            'WIC_ERP_ALLOW_MQU' => Tools::getValue('WIC_ERP_ALLOW_MQU', Configuration::get('WIC_ERP_ALLOW_MQU')),
            'WIC_ERP_PRORITIZE_ORDER' => Tools::getValue('WIC_ERP_PRORITIZE_ORDER', Configuration::get('WIC_ERP_PRORITIZE_ORDER')),
            'WIC_ERP_NUMBER_DAY' => Tools::getValue('WIC_ERP_NUMBER_DAY', Configuration::get('WIC_ERP_NUMBER_DAY')),
            'WIC_ERP_VERIFY_ORDER' => Tools::getValue('WIC_ERP_VERIFY_ORDER', Configuration::get('WIC_ERP_VERIFY_ORDER')),
            'WIC_ERP_SEND_EMAIL_SUPPLIER' => Tools::getValue('WIC_ERP_SEND_EMAIL_SUPPLIER', Configuration::get('WIC_ERP_SEND_EMAIL_SUPPLIER')),
            'WIC_ERP_COMPLETE_IS_PREFIX' => Tools::getValue('WIC_ERP_COMPLETE_IS_PREFIX', Configuration::get('WIC_ERP_COMPLETE_IS_PREFIX')),
            'WIC_ERP_RECALCULATE_BY_SUPPLIER' => Tools::getValue('WIC_ERP_RECALCULATE_BY_SUPPLIER', Configuration::get('WIC_ERP_RECALCULATE_BY_SUPPLIER')),
            'WIC_ERP_SEND_ONLY_PDF' => Tools::getValue('WIC_ERP_SEND_ONLY_PDF', Configuration::get('WIC_ERP_SEND_ONLY_PDF')),
            'WIC_ERP_COMPLETE_LIST_STATUS' => Tools::getValue('WIC_ERP_COMPLETE_LIST_STATUS', Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS')),
            'WIC_ERP_DISPLAY_FO_ORDER_DETAIL' => Tools::getValue('WIC_ERP_DISPLAY_FO_ORDER_DETAIL', Configuration::get('WIC_ERP_DISPLAY_FO_ORDER_DETAIL')),
            'WIC_ERP_STAT_DAYS' => Tools::getValue('WIC_ERP_STAT_DAYS', Configuration::get('WIC_ERP_STAT_DAYS')),   
            'WIC_ERP_STOCK_NORMAL_RATE' => $this->getSpecificField(),
            'WIC_ERP_CRON_DAYS' => Tools::getValue('WIC_ERP_CRON_DAYS', Configuration::get('WIC_ERP_CRON_DAYS')),
            'WIC_ERP_STOCK_METHOD' => Tools::getValue('WIC_ERP_STOCK_METHOD', Configuration::get('WIC_ERP_STOCK_METHOD')),
            'WIC_ERP_DELIVERY_SUPPLIERS' => Tools::getValue('WIC_ERP_DELIVERY_SUPPLIERS', Configuration::get('WIC_ERP_DELIVERY_SUPPLIERS')),   
            'WIC_ERP_DELIVERY_CHANGE' => Tools::getValue('WIC_ERP_DELIVERY_CHANGE', Configuration::get('WIC_ERP_DELIVERY_CHANGE')),
            'WIC_ERP_DEFAULT_WAREHOUSE' => Tools::getValue('WIC_ERP_DEFAULT_WAREHOUSE', Configuration::get('WIC_ERP_DEFAULT_WAREHOUSE')),
            'WIC_ERP_STOCK_TRANSFERT' => Tools::getValue('WIC_ERP_STOCK_TRANSFERT', Configuration::get('WIC_ERP_STOCK_TRANSFERT')),
            'WIC_ERP_WAREHOUSE_FROM_DEFAULT' => Tools::getValue('WIC_ERP_WAREHOUSE_FROM_DEFAULT', Configuration::get('WIC_ERP_WAREHOUSE_FROM_DEFAULT')),
            'WIC_ERP_WAREHOUSE_TO_DEFAULT' => Tools::getValue('WIC_ERP_WAREHOUSE_TO_DEFAULT', Configuration::get('WIC_ERP_WAREHOUSE_TO_DEFAULT')),
            'WIC_ERP_REFUNDED_STATE' => Tools::getValue('WIC_ERP_REFUNDED_STATE', (Configuration::get('WIC_ERP_REFUNDED_STATE') ? Configuration::get('WIC_ERP_REFUNDED_STATE') : 7)),
            'updateSuppliers' => 1,
            'updateProducts' => 1,
            'cronText' => $this->getCronInstruction(),
        );
    }

    /*************************************************************************************************
    UPDATE CONFIGURATION && POST PROCCESS
    **************************************************************************************************/
    private function postProcess()
    {
        if (!Configuration::get('WIC_ERP_REFUNDED_STATE')) {
            Configuration::updateValue('WIC_ERP_REFUNDED_STATE',7);
        }
        
        $action = Tools::getValue('action');
        if (!$action) {
            return false;
        }

        $method_name = 'ajaxProcess'.Tools::toCamelCase($action, true);

        if (method_exists($this, $method_name)) {
            return $this->{$method_name}();
        }
        
        return false;
    }
    
    private function ajaxProcessUpdateConfig()
    {
        // Check settings
        if (!Tools::getValue('WIC_ERP_NOT_COMPLETE') || !Tools::getValue('WIC_STATE_ERP_COMPLETE')) {
            return $this->JSONoutput('All fields are required.', false);
        } elseif (!Tools::getValue('WIC_ERP_NOT_UPD_STATE')) {
            return $this->JSONoutput('All fields are required.', false);
        } elseif (Tools::getValue('WIC_ERP_STOCK_MANAGEMENT') == 'normal' && !Tools::getValue('WIC_ERP_DELIVERY_SUPPLIERS')) {
            return $this->JSONoutput('Default delivery suppliers is required.', false);
        }

        /* Step 1 of postProcess */
        Configuration::updateValue('WIC_ERP_STK_SAFETY', (int) Tools::getValue('WIC_ERP_STK_SAFETY'));
        Configuration::updateValue('WIC_ERP_STK_MIN', (int) Tools::getValue('WIC_ERP_STK_MIN'));
        Configuration::updateValue('WIC_ERP_BACK_ORDER_STATE', Tools::getValue('WIC_ERP_BACK_ORDER_STATE'));
        Configuration::updateValue('WIC_ERP_NOT_UPD_STATE', (Tools::getValue('WIC_ERP_NOT_UPD_STATE') ? implode(Tools::getValue('WIC_ERP_NOT_UPD_STATE'), ','): ''));
        Configuration::updateValue('WIC_ERP_NOT_COMPLETE', (Tools::getValue('WIC_ERP_NOT_COMPLETE') ? implode(Tools::getValue('WIC_ERP_NOT_COMPLETE'), ',') : ''));
        Configuration::updateValue('WIC_ERP_NOT_DISPLAY', (Tools::getValue('WIC_ERP_NOT_DISPLAY') ? implode(Tools::getValue('WIC_ERP_NOT_DISPLAY'), ',') : ''));
        Configuration::updateValue('WIC_ERP_NOT_UPD_STK', (Tools::getValue('WIC_ERP_NOT_UPD_STK') ? implode(Tools::getValue('WIC_ERP_NOT_UPD_STK'), ',') : ''));
        Configuration::updateValue('WIC_ERP_PREPARATION_STATE', (Tools::getValue('WIC_ERP_PREPARATION_STATE') ? implode(Tools::getValue('WIC_ERP_PREPARATION_STATE'), ',') : ''));
        Configuration::updateValue('WIC_STATE_ERP_COMPLETE', Tools::getValue('WIC_STATE_ERP_COMPLETE'));
        Configuration::updateValue('WIC_ERP_STOCK_MANAGEMENT', Tools::getValue('WIC_ERP_STOCK_MANAGEMENT'));
        Configuration::updateValue('WIC_ERP_DISABLED_PRODUCT', Tools::getValue('WIC_ERP_DISABLED_PRODUCT'));
        Configuration::updateValue('WIC_ERP_ALLOW_MQU', Tools::getValue('WIC_ERP_ALLOW_MQU'));
        Configuration::updateValue('WIC_ERP_PRORITIZE_ORDER', Tools::getValue('WIC_ERP_PRORITIZE_ORDER'));
        Configuration::updateValue('WIC_ERP_NUMBER_DAY', Tools::getValue('WIC_ERP_NUMBER_DAY'));
        Configuration::updateValue('WIC_ERP_VERIFY_ORDER', Tools::getValue('WIC_ERP_VERIFY_ORDER'));
        Configuration::updateValue('WIC_ERP_SEND_EMAIL_SUPPLIER', Tools::getValue('WIC_ERP_SEND_EMAIL_SUPPLIER'));
        Configuration::updateValue('WIC_ERP_COMPLETE_IS_PREFIX', Tools::getValue('WIC_ERP_COMPLETE_IS_PREFIX'));
        Configuration::updateValue('WIC_ERP_RECALCULATE_BY_SUPPLIER', Tools::getValue('WIC_ERP_RECALCULATE_BY_SUPPLIER'));
        Configuration::updateValue('WIC_ERP_SEND_ONLY_PDF', Tools::getValue('WIC_ERP_SEND_ONLY_PDF'));
        Configuration::updateValue('WIC_ERP_DEFAULT_WAREHOUSE', Tools::getValue('WIC_ERP_DEFAULT_WAREHOUSE'));
        Configuration::updateValue('WIC_ERP_REFUNDED_STATE', Tools::getValue('WIC_ERP_REFUNDED_STATE'));
        Configuration::updateValue('WIC_ERP_STOCK_TRANSFERT', Tools::getValue('WIC_ERP_STOCK_TRANSFERT'));
        Configuration::updateValue('WIC_ERP_WAREHOUSE_TO_DEFAULT', Tools::getValue('WIC_ERP_WAREHOUSE_TO_DEFAULT'));
        Configuration::updateValue('WIC_ERP_WAREHOUSE_FROM_DEFAULT', Tools::getValue('WIC_ERP_WAREHOUSE_FROM_DEFAULT'));
        
        if (!Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS')) {
            Configuration::updateValue('WIC_ERP_COMPLETE_LIST_STATUS', Tools::getValue('WIC_STATE_ERP_COMPLETE'));
        }

        
        Configuration::updateValue('WIC_ERP_CUSTOMER_THREAD', (Tools::getValue('WIC_ERP_CUSTOMER_THREAD') ? implode(Tools::getValue('WIC_ERP_CUSTOMER_THREAD'), ',') : ''));
        
        Configuration::updateValue('WIC_ERP_DISPLAY_FO_ORDER_DETAIL', Tools::getValue('WIC_ERP_DISPLAY_FO_ORDER_DETAIL'));

        if (!Tools::getValue('WIC_ERP_STOCK_TRANSFERT')) {
            $id_tab = Tab::getIdFromClassName('AdminErpStockTransfert');
            $tab = new Tab((int) $id_tab);

            if (Validate::isLoadedObject($tab)) {
                $tab->active = 0;
                $tab->save();
            }
        } else {
            $id_tab = Tab::getIdFromClassName('AdminErpStockTransfert');
            $tab = new Tab((int) $id_tab);

            if (Validate::isLoadedObject($tab)) {
                $tab->active = 1;
                $tab->save();
            }
        }
        
        if (Tools::getValue('WIC_ERP_STOCK_MANAGEMENT') != 'normal') {
            /* we hidden menu */
            $id_tab = Tab::getIdFromClassName('AdminErpProducts');
            $tab = new Tab((int) $id_tab);

            if (Validate::isLoadedObject($tab)) {
                $tab->active = 0;
                $tab->save();
            }

            if (Tools::getValue('WIC_ERP_SEND_EMAIL_SUPPLIER')) {
                $id_tab = Tab::getIdFromClassName('AdminErpSuppliers');
                $tab = new Tab((int) $id_tab);

                if (Validate::isLoadedObject($tab)) {
                    $tab->active = 1;
                    $tab->save();
                }
            }
        } else {
            /* we hidden menu */
            $id_tab = Tab::getIdFromClassName('AdminErpProducts');
            $tab = new Tab((int) $id_tab);

            if (Validate::isLoadedObject($tab)) {
                $tab->active = 1;
                $tab->save();
            }

            $id_tab = Tab::getIdFromClassName('AdminErpSuppliers');
            $tab = new Tab((int) $id_tab);

            if (Validate::isLoadedObject($tab)) {
                $tab->active = 1;
                $tab->save();
            }
        }

        if (Tools::getValue('WIC_ERP_STOCK_NORMAL_RATE')) {
            Configuration::updateValue('WIC_ERP_STOCK_NORMAL_RATE', Tools::getValue('WIC_ERP_STOCK_NORMAL_RATE'));
        }

        if (Tools::getValue('WIC_ERP_STAT_DAYS')) {
            Configuration::updateValue('WIC_ERP_STAT_DAYS', Tools::getValue('WIC_ERP_STAT_DAYS'));
        }

        if (Tools::getValue('WIC_ERP_CRON_DAYS')) {
            Configuration::updateValue('WIC_ERP_CRON_DAYS', Tools::getValue('WIC_ERP_CRON_DAYS'));
        }

        if (Tools::getValue('WIC_ERP_STOCK_METHOD')) {
            Configuration::updateValue('WIC_ERP_STOCK_METHOD', Tools::getValue('WIC_ERP_STOCK_METHOD'));
        }
        
        $this->JSONoutput(array(
            'message' => $this->l('Configuration updated'),
            'modelNb' => array(
                'analyzeOrders' => $this->getOrderCount(),
                'calculateStock' => ErpProducts::getCount(),
            ),
        ));
    }

    private function ajaxProcessUpdateSuppliers()
    {
        if (Tools::getValue('WIC_ERP_DELIVERY_SUPPLIERS') || Tools::getValue('WIC_ERP_SEND_EMAIL_SUPPLIER')) {
            Configuration::updateValue('WIC_ERP_DELIVERY_SUPPLIERS', Tools::getValue('WIC_ERP_DELIVERY_SUPPLIERS'));
            /* We update suppliers delivery */

            if (Tools::getValue('WIC_ERP_DELIVERY_CHANGE')) {
                Configuration::updateValue('WIC_ERP_DELIVERY_CHANGE', Tools::getValue('WIC_ERP_DELIVERY_CHANGE'));

                if (Tools::getValue('updateSuppliers')) {
                    $this->addOrUpdateSuppliers(Tools::getValue('WIC_ERP_DELIVERY_SUPPLIERS'), Tools::getValue('WIC_ERP_DELIVERY_CHANGE'));
                }
            } else {
                if (Tools::getValue('updateSuppliers')) {
                    $this->addOrUpdateSuppliers(Tools::getValue('WIC_ERP_DELIVERY_SUPPLIERS'), 0);
                }
            }
        }
        $this->JSONoutput($this->l('Suppliers updated'));
    }

    private function ajaxProcessAnalyzeOrders()
    {
        $limit = 50;
        $offset = (int) Tools::getValue('offset', 0);

        if (!$offset) {
            $query = new DbQuery();
            $query->select('`id_order`');
            $query->from('orders');
            $query->where('`current_state` IN('.pSQL(Configuration::get('WIC_ERP_NOT_COMPLETE')).','.pSQL(Configuration::get('WIC_STATE_ERP_COMPLETE')).(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') ? ','.pSQL(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS')) : '').')');

            $orders = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($query);

            if ($orders) {
                $order_synchronise = array();
                foreach ($orders as $order) {
                    $order_synchronise[] = $order['id_order'];
                }

                /* Update order detail to quantity in stock to 0 */
                $this->updateQtyOrdersByProductsDetail(implode(',', $order_synchronise));
            }
        }

        $query = new DbQuery();
        $query->select('`id_order`');
        $query->from('orders');
        $query->where('`current_state` IN('.pSQL(Configuration::get('WIC_ERP_NOT_COMPLETE')).','.pSQL(Configuration::get('WIC_STATE_ERP_COMPLETE')).(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') ? ','.pSQL(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS')) : '').')');
        $query->limit($limit, $offset);

        $orders = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($query);

        if ($orders) {
            $order_synchronise = array();
            foreach ($orders as $order) {
                $order_synchronise[] = $order['id_order'];
            }

            // Update order detail to quantity in stock to 0 
            //$this->updateQtyOrdersByProductsDetail(implode(',', $order_synchronise));
        }

        foreach ($orders as $order) {
            $order_obj = new Order($order['id_order']);
            $products = $order_obj->getProductsDetail();

            $order_complete = true;

            foreach ($products as $product) {
                $order_detail = new OrderDetail($product['id_order_detail']);

                if (Validate::isLoadedObject($order_detail)) {
                    $product = new Product((int) $order_detail->product_id);

                    /*$reservable_quantity = $this->loadStockDataReservable($product, (int) $order_detail->product_attribute_id, Context::getContext());

                    if (($order_detail->product_quantity - $order_detail->product_quantity_refunded) <= $reservable_quantity) {
                        $order_detail->product_quantity_in_stock = ($order_detail->product_quantity - $order_detail->product_quantity_refunded);
                    } else {
                        $order_detail->product_quantity_in_stock = (($reservable_quantity >= 0) ? $reservable_quantity : 0);
                    }

                    $query = '
                                    UPDATE
                                            `'._DB_PREFIX_.'order_detail` od
                                    SET
                                            od.`product_quantity_in_stock` = '
        .((!$order_detail->product_quantity_in_stock) ? 0 : (int) $order_detail->product_quantity_in_stock).'
                                    WHERE
                                            od.`id_order_detail` = '.(int) $order_detail->id;

                    Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query);

                    unset($query);

                    if ($order_detail->product_quantity_in_stock != ($order_detail->product_quantity - $order_detail->product_quantity_refunded)) {
                        $order_complete = false;
                    }*/
                }
                
                $this->updateQuantityProduct((int) $order_detail->product_id);
                unset($order_detail);
            }

            /* If order as complete we update Status */
           /* if ($order_complete) {
                $this->updateOrderStatus((int) $order_obj->id);
            }*/

            unset($order_obj, $products);
        }

        /**************************************************************************************************
        UPDATE ORDER
        We retreieve all order in status ERP NOT UPDATE SATUT
        We reinitialize reserved quantity to 0
        **************************************************************************************************/
        $orders = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('SELECT `id_order` FROM `'
                                                                        ._DB_PREFIX_.'orders` WHERE `current_state` IN('
                                                                        .pSQL(Configuration::get('WIC_ERP_NOT_UPD_STATE')).')');

        foreach ($orders as $order) {
            $order_obj = new Order($order['id_order']);
            $products = $order_obj->getProductsDetail();

            foreach ($products as $product) {
                $order_detail = new OrderDetail($product['id_order_detail']);

                if (Validate::isLoadedObject($order_detail)) {
                    $order_detail->product_quantity_in_stock = 0;
                    $query = '
								UPDATE
									`'._DB_PREFIX_.'order_detail` od
								SET
									od.`product_quantity_in_stock` = '.(int) $order_detail->product_quantity_in_stock.'
								WHERE
									od.`id_order_detail` = '.(int) $order_detail->id;

                    Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query);

                    unset($query);
                }

                unset($order_detail);
            }
            unset($order_obj, $products);
        }
        unset($orders);

        $this->logTxtFile($this->log);
        /************************************************************************************************
        END UPDATE ORDER
        ************************************************************************************************/

        $this->JSONoutput(array(
            'message' => $this->l('Orders analysed'),
            'offset' => $offset + $limit,
            'needUpdate' => true,
        ));
    }

    public function ajaxProcessCalculateStock()
    {
        $step_response = array(
            'needUpdate' => false,
            'message' => $this->l('Products and stock updated'),
        );

        if (Tools::getValue('WIC_ERP_STOCK_MANAGEMENT') == 'normal' && Tools::getValue('updateProducts')) {
            $limit = 100;
            $offset = (int) Tools::getValue('offset', 0);

            ErpProducts::updateErpProducts(array(), $limit, $offset);
            $step_response['needUpdate'] = true;
            $step_response['offset'] = $offset + $limit;
        }

        return $this->JSONoutput($step_response);
    }

    public function JSONoutput($message, $success = true)
    {
        if (is_array($message)) {
            $jsonoutput = $message;
            if (!array_key_exists('success', $jsonoutput)) {
                $jsonoutput['success'] = $success;
            }
        } else {
            $jsonoutput = array(
                'message' => $message,
                'success' => $success,
            );
        }

        header('Content-Type: application/json');
        exit(Tools::jsonEncode($jsonoutput));
    }

    public function addOrUpdateSuppliers($delivery_supplier, $delivery_change_supplier)
    {
        $suppliers = Supplier::getSuppliers();

        foreach ($suppliers as $supplier) {
            $id_erp_suppliers = ErpSuppliers::getSupplierById($supplier['id_supplier']);
            if (!$id_erp_suppliers) {
                $erp_supplier = new ErpSuppliers();
            } else {
                $erp_supplier = new ErpSuppliers($id_erp_suppliers);
            }

            if (!$erp_supplier->manual_configuration) {
                $erp_supplier->id_supplier = $supplier['id_supplier'];
                $erp_supplier->id_lang = $this->context->language->id;
                $erp_supplier->id_employee = $this->context->employee->id;
                $erp_supplier->delivery = (int) $delivery_supplier;
                $erp_supplier->delivery_change = (int) $delivery_change_supplier;
                $erp_supplier->manual_configuration = 0;
                $erp_supplier->vat_exemption = 0;
                $erp_supplier->save();
            }

            unset($erp_supplier);
        }
    }

    /*************************************************************************************************
    HOOK MANAGEMENT
    **************************************************************************************************/

    /************************************************
    * HOOK BACK OFFICE HEADER
    ************************************************/
    public function hookDisplayBackOfficeHeader()
    {
        if ((isset(Context::getContext()->controller->controller_name)
                    && !preg_match('/AdminExpeditor/i', Context::getContext()->controller->controller_name))
                    || (isset(Context::getContext()->controller->controller_name)
                    && !preg_match('/AdminExpeditor/i', Context::getContext()->controller->controller_name))) {
            $this->context->controller->addCSS(($this->css_path).'admin_global.css', 'all');
        }

        if (isset(Context::getContext()->controller->controller_name)
                    && preg_match('/AdminErp/i', Context::getContext()->controller->controller_name)) {
            $this->context->controller->addJquery();
            $this->context->controller->addCSS(($this->_path).'views/css/admin_header.css', 'all');
            $this->context->controller->addJS($this->js_path.'jquery.maskedinput.min.js');
        }

        if (version_compare(_PS_VERSION_, '1.6', '<')
                    && isset(Context::getContext()->controller->controller_name)
                    && preg_match('/AdminErp/i', Context::getContext()->controller->controller_name)) {
            $this->context->controller->addJquery();
            $this->context->controller->addJS($this->js_path.'jquery.ui.widget.min.js');
            $this->context->controller->addJS($this->js_path.'d3.v3.min.js');
            $this->context->controller->addJS($this->js_path.'nv.d3.min.js');
            $this->context->controller->addJS($this->js_path.'jquery.iframe-transport.js');
            $this->context->controller->addJS($this->js_path.'jquery.fileupload.js');
            $this->context->controller->addJS($this->js_path.'jquery.fileupload-process.js');
            $this->context->controller->addJS($this->js_path.'jquery.fileupload-validate.js');
            $this->context->controller->addJS($this->js_path.'spin.js');
            $this->context->controller->addJS($this->js_path.'ladda.js');
            $this->loadAsset();
        }

        if (isset(Context::getContext()->controller->controller_name)
                    && preg_match('/AdminErpImport/i', Context::getContext()->controller->controller_name)) {
            $bo_theme = ((Validate::isLoadedObject($this->context->employee)
                    && $this->context->employee->bo_theme) ? $this->context->employee->bo_theme : 'default');

            if (!file_exists(_PS_BO_ALL_THEMES_DIR_.$bo_theme.DIRECTORY_SEPARATOR
                            .'template')) {
                $bo_theme = 'default';
            }
        }
    }

    /*************************************************
    ORDER STATUS UPDATE AFTER
    **************************************************/
    public function hookActionOrderHistoryAddAfter($params)
    {
        if (!$this->active) {
            return;
        }

        $order_history = $params['order_history'];

        if (Validate::isLoadedObject($order_history)) {
            $order = new Order($order_history->id_order);
            $previous_id_order_state = $this->getPreviousStatusOrder((int) $order->id);

            $count_order_state = $this->getAllOrderState((int) $order->id);

            if (Validate::isLoadedObject($order_history)) {
                $this->log[] = '******************************************************'.PHP_EOL;
                $this->log[] = 'DATE '.date('Y-m-d H:i:s').PHP_EOL;
                $this->log[] = 'update order #'.$order->id.PHP_EOL;
                $this->log[] = 'current status order #'.$order_history->id_order_state.PHP_EOL;
                $this->log[] = '******************************************************'.PHP_EOL.PHP_EOL;

                $authorized_order_state = explode(',', Configuration::get('WIC_ERP_NOT_COMPLETE').','.Configuration::get('WIC_STATE_ERP_COMPLETE').(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') ? ','.Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') : ''));
                $not_available_status = explode(',', Configuration::get('WIC_ERP_NOT_UPD_STK'));
                $customerThread = explode(',', Configuration::get('WIC_ERP_CUSTOMER_THREAD'));

                if (!$previous_id_order_state 
                        && $order_history->id_order_state != Configuration::get('PS_OS_CANCELED')
                        && $order_history->id_order_state != Configuration::get('PS_OS_ERROR')) {
                    // updates stock in shops
                    if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                        $product_list = $order->getProducts();

                        foreach ($product_list as $product) {
                            // if the available quantities depends on the physical stock
                            if (StockAvailable::dependsOnStock($product['product_id'])) {
                                // synchronizes
                                $this->synchronize($product['product_id'], $order->id_shop);
                            }

                            $order_detail_obj = new OrderDetail((int) $product['id_order_detail']);

                            if (Validate::isLoadedObject($order_detail_obj)) {
                                $order_detail_obj->product_quantity_in_stock = 0;
                                $order_detail_obj->update();
                            }
                            unset($order_detail_obj);
                        }
                    }
                }
                /**************************************************************
                WHEN PRESTASHOP CREATE ORDER || UPDATE ORDER IN PENDING STATUS
                ***************************************************************/
                if (in_array($order_history->id_order_state, $not_available_status)
                        && $order_history->id_order_state != Configuration::get('PS_OS_CANCELED')
                        && $order_history->id_order_state != Configuration::get('PS_OS_ERROR')
                        && !$previous_id_order_state
                        && count($not_available_status) > 0
                        && Configuration::get('WIC_ERP_NOT_UPD_STK')) {
                    $this->log[] = 'case 1 --> current status order #'.$order_history->id_order_state.PHP_EOL;
                    $products = $order->getProducts();
                    if ($products) {
                        foreach ($products as $product) {
                            StockAvailable::updateQuantity((int) $product['product_id'],
                                                                                        (int) $product['product_attribute_id'],
                                                                                        ($product['product_quantity'] - $product['product_quantity_refunded']),
                                                                                        (int) $order->id_shop);

                            if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                               // if the available quantities depends on the physical stock
                               if (StockAvailable::dependsOnStock($product['product_id'])) {
                                   // synchronizes
                                   $this->synchronize($product['product_id'], $order->id_shop);
                               }
                            }
                            
                            $order_detail_obj = new OrderDetail((int) $product['id_order_detail']);

                            if (Validate::isLoadedObject($order_detail_obj)) {
                                $order_detail_obj->product_quantity_in_stock = 0;
                                $order_detail_obj->update();
                            }
                        }
                    }
                } elseif ($order_history->id_order_state != Configuration::get('PS_OS_CANCELED')
                                        && $order_history->id_order_state != Configuration::get('PS_OS_ERROR')
                                        && in_array($order_history->id_order_state, $authorized_order_state)
                                        && !in_array($previous_id_order_state, $authorized_order_state)
                                        && in_array($previous_id_order_state, $not_available_status)
                                        && $previous_id_order_state
                                        && $count_order_state == 1
                                        && count($not_available_status) > 0
                                        && Configuration::get('WIC_ERP_NOT_UPD_STK')) {
                    $this->log[] = 'case 2 --> current status order #'.$order_history->id_order_state.PHP_EOL;
                    $products = $order->getProducts();
                    if ($products) {
                        foreach ($products as $product) {
                            StockAvailable::updateQuantity((int) $product['product_id'],
                                                                                (int) $product['product_attribute_id'],
                                                                                -($product['product_quantity'] - $product['product_quantity_refunded']),
                                                                                (int) $order->id_shop);
                        
                            if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                               // if the available quantities depends on the physical stock
                               if (StockAvailable::dependsOnStock($product['product_id'])) {
                                   // synchronizes
                                   $this->synchronize($product['product_id'], $order->id_shop);
                               }
                            }
                        }
                    }
                } elseif ($order_history->id_order_state != Configuration::get('PS_OS_CANCELED')
                                        && $order_history->id_order_state != Configuration::get('PS_OS_ERROR')
                                        && in_array($order_history->id_order_state, $authorized_order_state)
                                        && !Tools::getValue('WIC_ERP_STEP')) {
                    if ($order->current_state == Configuration::get('WIC_ERP_VERIFY_ORDER')
                            && $previous_id_order_state
                            && (in_array($previous_id_order_state, $not_available_status)
                            || in_array($previous_id_order_state, $customerThread))) {
                        /* We update state with previous state */
                        $new_history = new OrderHistory();
                        $new_history->id_order = (int) $order->id;

                        $use_existings_payment = false;
                        if (!$order->hasInvoice() && $previous_id_order_state != Configuration::get('PS_OS_CANCELED') && $previous_id_order_state != Configuration::get('PS_OS_ERROR')) {
                            $use_existings_payment = true;
                        }

                        $new_history->changeIdOrderState((int) $previous_id_order_state, $order->id, $use_existings_payment);
                        $new_history->add();

                        unset($new_history);
                    } elseif ($order->current_state == Configuration::get('WIC_ERP_VERIFY_ORDER')
                            && $previous_id_order_state
                            && ($previous_id_order_state == Configuration::get('PS_OS_CANCELED')
                            || $previous_id_order_state == Configuration::get('PS_OS_ERROR'))) {
                        $order->current_state = $previous_id_order_state;
                        $order->update();
                    }
                    $this->log[] = 'case 3 --> current status order #'.$order_history->id_order_state.PHP_EOL;

                    $products = $order->getProducts();
                    if ($products) {
                        foreach ($products as $product) {
                            $this->updateQuantityProduct((int) $product['product_id']);
                        }
                    }
                } elseif ($order_history->id_order_state == Configuration::get('PS_OS_CANCELED')
                            &&	$previous_id_order_state
                            && in_array($previous_id_order_state, $not_available_status))
                {
                    $products = $order->getProducts();
                    if ($products)
                    {
                        foreach ($products as $product)
                            StockAvailable::updateQuantity((int)$product['product_id'],
                                (int)$product['product_attribute_id'],
                                -($product['product_quantity'] - $product['product_quantity_refunded']),
                                (int)$order->id_shop);
                    }
                }
            }
            /******************************************************************
            END
            ******************************************************************/
            unset($order, $order_history);
        }
    }

    /*************************************************
    UPDATE QUANTITY
    **************************************************/
    public function hookActionUpdateQuantity($params)
    {
        if (!$this->active) {
            return;
        }

        $context = Context::getContext();

        $id_product = $params['id_product'];

        //We verify and update Quantity
        $this->updateQuantityProduct((int) $id_product);
    }
    
    public function updateQuantityProduct($id_product)
    {
        if ($id_product) {
            $product = new Product((int) $id_product);

            $not_available_status = explode(',', Configuration::get('WIC_ERP_NOT_UPD_STK'));
            $authorized_order_state = explode(',', Configuration::get('WIC_ERP_NOT_COMPLETE').','.Configuration::get('WIC_STATE_ERP_COMPLETE').(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') ? ','.Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') : ''));

            if (Validate::isLoadedObject($product)) {
                $this->log[] = '******************************************************'.PHP_EOL;
                $this->log[] = 'DATE '.date('Y-m-d H:i:s').PHP_EOL;
                $this->log[] = 'update stock for product #'.$product->id.PHP_EOL;
                $authorized_order_state = explode(',', Configuration::get('WIC_ERP_NOT_COMPLETE').','.Configuration::get('WIC_STATE_ERP_COMPLETE').(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') ? ','.Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') : ''));

                /* We verify if this product has atttribute */
                if ($product->hasAttributes()) {
                    $attributes = $product->getAttributesResume(Configuration::get('PS_LANG_DEFAULT'));

                    if ($attributes) {
                        $ordersToUpdate = array();
                        foreach ($attributes as $attribute) {
                            $this->log[] = 'Product attribute #'.(int) $attribute['id_product_attribute'].PHP_EOL;
                                        /* We retrieve all order which contain this product and this attribute */
                                        $orders_detail = $this->getOrdersByProductsDetail((int) $product->id, (int) $attribute['id_product_attribute']);

                            $shippable_quantity = $this->loadStockDataShippable($product, (int) $attribute['id_product_attribute'], Context::getContext());
                            $this->log[] = 'Shippable quantity for this product with attribute --> '.$shippable_quantity.' product(s).'.PHP_EOL.PHP_EOL;
                            $this->log[] = '------------------ Impacted Orders ---------------------'.PHP_EOL;
                            if ($orders_detail) {
                                
                                //we verify if process partial refund order
                                $processRefund = 0;
                                if (Tools::getValue('partialRefundProductQuantity')) {
                                    $partialRefund = Tools::getValue('partialRefundProductQuantity');
                                    foreach ($orders_detail as $order_detail) {
                                        if (is_array($partialRefund) && isset($partialRefund[$order_detail['id_order_detail']])) {
                                            $order_detail_obj = new OrderDetail($order_detail['id_order_detail']);

                                            if (Validate::isLoadedObject($order_detail_obj)) {
                                                if (($order_detail_obj->product_quantity_refunded 
                                                            && ($order_detail_obj->product_quantity_refunded - $partialRefund[$order_detail['id_order_detail']] > 0))
                                                    ||
                                                        ($order_detail_obj->product_quantity_refunded - $partialRefund[$order_detail['id_order_detail']] == 0 
                                                                && $order_detail_obj->product_quantity_refunded == $partialRefund[$order_detail['id_order_detail']])
                                                        )
                                                {
                                                    $processRefund += $partialRefund[$order_detail['id_order_detail']];
                                                }
                                            }
                                        }
                                    }
                                }
                                
                                $shippable_quantity = $shippable_quantity + $processRefund;

                                foreach ($orders_detail as $order_detail) {
                                    $order = new Order((int) $order_detail['id_order']);
                                    $this->log[] = 'Order Id #'.$order->id.PHP_EOL;
                                    $previous_id_order_state = $this->getPreviousStatusOrder((int) $order->id);
                                    $current_state = $order->current_state;
                                    if ((in_array($current_state, $authorized_order_state)
                                                                        && $current_state != Configuration::get('WIC_ERP_VERIFY_ORDER'))
                                                                        || ($current_state == Configuration::get('WIC_ERP_VERIFY_ORDER')
                                                                        && !in_array($previous_id_order_state, $not_available_status))) {
                                        $order_detail_obj = new OrderDetail((int) $order_detail['id_order_detail']);

                                        if (Validate::isLoadedObject($order_detail_obj)) {
                                            $qty_to_ship = ($order_detail_obj->product_quantity - $order_detail_obj->product_quantity_refunded);

                                            if (Configuration::get('WIC_ERP_PRORITIZE_ORDER')
                                                                                        && (!Configuration::get('WIC_ERP_NUMBER_DAY')
                                                                                        || strtotime(
                                                                                                date('Y-m-d',
                                                                                                        strtotime($order->date_add)).'+'.Configuration::get('WIC_ERP_NUMBER_DAY').'day') > strtotime(
                                                                                                                        date('Y-m-d')))) {
                                                $reservable_qty_for_this_order_detail = (($qty_to_ship <= $shippable_quantity) ? $qty_to_ship : 0);
                                            } else {
                                                $reservable_qty_for_this_order_detail = (($qty_to_ship <= $shippable_quantity) ?
                                                                                                                                                                $qty_to_ship : $shippable_quantity);
                                            }

                                            $this->log[] = 'Quantity to ship --> '.$qty_to_ship.PHP_EOL;
                                            $this->log[] = 'Reservable quantity --> '.$reservable_qty_for_this_order_detail.PHP_EOL;

                                            if ($reservable_qty_for_this_order_detail != $order_detail_obj->product_quantity_in_stock) {
                                                if ($reservable_qty_for_this_order_detail < 0) {
                                                    $reservable_qty_for_this_order_detail = 0;
                                                }
                                                $order_detail_obj->product_quantity_in_stock = $reservable_qty_for_this_order_detail;
                                                $order_detail_obj->update();
                                            }

                                            $shippable_quantity -= $reservable_qty_for_this_order_detail;
                                            $this->log[] = 'New shippable quantity --> '.$shippable_quantity.PHP_EOL;
                                            $ordersToUpdate[] = $order->id;

                                            unset($order_detail_obj, $order);
                                        }
                                    } else {
                                        $order_detail_obj = new OrderDetail((int) $order_detail['id_order_detail']);

                                        if (Validate::isLoadedObject($order_detail_obj)) {
                                            $order_detail_obj->product_quantity_in_stock = 0;
                                            $order_detail_obj->update();

                                            unset($order_detail_obj, $order);
                                        }
                                    }
                                    $this->log[] = PHP_EOL;
                                }
                                unset($orders_detail);
                                $this->log[] = 'End order process'.PHP_EOL.PHP_EOL;
                            }
                        }

                        //We update all orderStatuts
                        /* We verify if order is complete */
                        $ordersToUpdate = array_unique($ordersToUpdate);

                        if (count($ordersToUpdate)) {
                            foreach ($ordersToUpdate as $idOrderUpdate) {
                                if ($this->orderIsComplete((int) $idOrderUpdate)) {
                                    $this->log[] = 'Order #'.$idOrderUpdate.' is complete'.PHP_EOL;
                                    $this->updateOrderStatus((int) $idOrderUpdate);
                                } else {
                                    $this->log[] = 'Order #'.$idOrderUpdate.' is not complete'.PHP_EOL;
                                    if (in_array($current_state, $authorized_order_state) && $current_state != Configuration::get('WIC_ERP_VERIFY_ORDER')) {
                                        $this->log[] = 'Backward update status order'.PHP_EOL;
                                        $this->updateBackOrderStatus((int) $idOrderUpdate);
                                    }
                                }
                            }
                        }
                    }
                } else {
                    $this->log[] = 'Simple product'.PHP_EOL;
                    /* We retrieve all order which contain this product and this attribute */
                    $orders_detail = $this->getOrdersByProductsDetail((int) $product->id);

                    $shippable_quantity = $this->loadStockDataShippable($product, 0, Context::getContext());
                    $this->log[] = 'Shippable quantity for this simple product --> '.$shippable_quantity.' product(s).'.PHP_EOL.PHP_EOL;
                    $this->log[] = '------------------ Impacted Orders ---------------------'.PHP_EOL;

                    if ($orders_detail) 
                        $ordersToUpdate = array();

                        //we verify if process partial refund order
                        $processRefund = 0;
                        if (Tools::getValue('partialRefundProductQuantity')) {
                            $partialRefund = Tools::getValue('partialRefundProductQuantity');
                            foreach ($orders_detail as $order_detail) {
                                if (is_array($partialRefund) && isset($partialRefund[$order_detail['id_order_detail']])) {
                                    $order_detail_obj = new OrderDetail($order_detail['id_order_detail']);

                                    if (Validate::isLoadedObject($order_detail_obj)) {
                                        if (($order_detail_obj->product_quantity_refunded 
                                                    && ($order_detail_obj->product_quantity_refunded - $partialRefund[$order_detail['id_order_detail']] > 0))
                                            ||
                                                ($order_detail_obj->product_quantity_refunded - $partialRefund[$order_detail['id_order_detail']] == 0 
                                                        && $order_detail_obj->product_quantity_refunded == $partialRefund[$order_detail['id_order_detail']])
                                                )
                                        {
                                            $processRefund += $partialRefund[$order_detail['id_order_detail']];
                                        }
                                    }
                                }
                            }
                        }

                        $shippable_quantity = $shippable_quantity + $processRefund;

                        $this->log[] = 'Shippable quantity for this simple product --> '.$shippable_quantity.' product(s).'.PHP_EOL.PHP_EOL;
                        $this->log[] = '------------------ Impacted Orders ---------------------'.PHP_EOL;

                        foreach ($orders_detail as $order_detail) {
                            $order = new Order((int) $order_detail['id_order']);
                            $this->log[] = 'Order Id #'.$order->id.PHP_EOL;
                            $previous_id_order_state = $this->getPreviousStatusOrder((int) $order->id);
                            $current_state = $order->current_state;

                            if (in_array($current_state, $authorized_order_state)
                                                        || ($current_state == Configuration::get('WIC_ERP_VERIFY_ORDER')
                                                        && !in_array($previous_id_order_state, $not_available_status))) {
                                $order_detail_obj = new OrderDetail($order_detail['id_order_detail']);

                                if (Validate::isLoadedObject($order_detail_obj)) {

                                    $qty_to_ship = ($order_detail_obj->product_quantity - $order_detail_obj->product_quantity_refunded);

                                    if (Configuration::get('WIC_ERP_PRORITIZE_ORDER')
                                                                        && (!Configuration::get('WIC_ERP_NUMBER_DAY')
                                                                        || strtotime(date('Y-m-d',
                                                                                                                strtotime($order->date_add)).'+'.Configuration::get('WIC_ERP_NUMBER_DAY').'day') >
                                                                                                                        strtotime(date('Y-m-d')))) {
                                        $reservable_qty_for_this_order_detail = (($qty_to_ship <= $shippable_quantity) ? $qty_to_ship : 0);
                                    } else {
                                        $reservable_qty_for_this_order_detail = (($qty_to_ship <= $shippable_quantity) ? $qty_to_ship : $shippable_quantity);
                                    }

                                    $this->log[] = 'Quantity to ship --> '.$qty_to_ship.PHP_EOL;
                                    $this->log[] = 'Reservable quantity --> '.$reservable_qty_for_this_order_detail.PHP_EOL;

                                    if ($reservable_qty_for_this_order_detail != $order_detail_obj->product_quantity_in_stock) {
                                        if ($reservable_qty_for_this_order_detail < 0) {
                                            $reservable_qty_for_this_order_detail = 0;
                                        }

                                        $order_detail_obj->product_quantity_in_stock = $reservable_qty_for_this_order_detail;
                                        $order_detail_obj->update();
                                    }

                                    $shippable_quantity -= $reservable_qty_for_this_order_detail;

                                    $this->log[] = 'New shippable quantity --> '.$shippable_quantity.PHP_EOL;

                                    $ordersToUpdate[] = $order->id;
                                }
                            } else {
                                $order_detail_obj = new OrderDetail((int) $order_detail['id_order_detail']);

                                if (Validate::isLoadedObject($order_detail_obj)) {
                                    $order_detail_obj->product_quantity_in_stock = 0;
                                    $order_detail_obj->update();

                                    unset($order_detail_obj, $order);
                                }
                            }
                            $this->log[] = PHP_EOL;
                        }
                        unset($order_detail);
                        $this->log[] = 'End order process'.PHP_EOL.PHP_EOL;

                        $ordersToUpdate = array_unique($ordersToUpdate);
                        /* We verify if order is complete */
                        if (count($ordersToUpdate)) {
                            foreach ($ordersToUpdate as $idOrderUpdate) {
                                if ($this->orderIsComplete((int) $idOrderUpdate)) {
                                    $this->log[] = 'Order #'.$idOrderUpdate.' is complete'.PHP_EOL;
                                    $this->updateOrderStatus((int) $idOrderUpdate);
                                } else {
                                    $this->log[] = 'Order #'.$idOrderUpdate.' is not complete'.PHP_EOL;
                                    if (in_array($current_state, $authorized_order_state) && $current_state != Configuration::get('WIC_ERP_VERIFY_ORDER')) {
                                        $this->log[] = 'Backward update status order'.PHP_EOL;
                                        $this->updateBackOrderStatus((int) $idOrderUpdate);
                                    }
                                }
                            }
                        }
                    }
                $this->log[] = 'update stock for product #'.$product->id.PHP_EOL;
                $this->log[] = '******************************************************'.PHP_EOL.PHP_EOL;
            }
            unset($product);
            $this->logTxtFile($this->log);
        }
    }

    /*************************************************
    PRODUCT SAVE (add default erp configuration)
    **************************************************/
    public function hookActionProductSave($params)
    {
        if (!$this->active || (!Tools::isSubmit('submitAddproduct') && !Tools::isSubmit('submitAddproductAndStay'))) {
            return;
        }
        
        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
            $warehouses = Warehouse::getWarehouses(true);
        }
                
        $product_obj = new Product($params['id_product']);

        /* Update ERP product data */
        $min_quantity = (Tools::getValue('min_quantity') ? Tools::getValue('min_quantity', 0) : Configuration::get('WIC_ERP_STK_MIN'));
        $safety_stock = (Tools::getValue('safety_stock') ? Tools::getValue('safety_stock', 0) : Configuration::get('WIC_ERP_STK_SAFETY'));
        $manual_configuration = Tools::getValue('manual_configuration');
        $unit_order = Tools::getValue('unit_order');
        $bundling = Tools::getValue('bundling');
        $min_quantity_by_warehouse = Tools::getValue('min_quantity_by_warehouse');
        $safety_stock_by_warehouse = Tools::getValue('safety_stock_by_warehouse');
        
        if (Validate::isLoadedObject($product_obj)) {
            if ($product_obj->hasAttributes()) {
                /* we retrieve all attributes */
                $attributes = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS('SELECT pa.`id_product_attribute`
                                                                                            FROM `'._DB_PREFIX_.'product_attribute` pa
                                                                                            WHERE pa.`id_product` ='.(int) $product_obj->id);

                if ($attributes) {
                    foreach ($attributes as $attribute) {
                        $id_erp_products = ErpProducts::getProductById($product_obj->id, $attribute['id_product_attribute']);

                        if (!$id_erp_products) {
                            $erp_products = new ErpProducts();
                        } else {
                            $erp_products = new ErpProducts($id_erp_products);
                        }
                        
                        if (!$id_erp_products) {
                            $erp_products->id_product = $params['id_product'];
                            $erp_products->id_product_attribute = $attribute['id_product_attribute'];
                            $erp_products->id_employee = Context::getContext()->employee->id;
                            $erp_products->min_quantity = ((isset($min_quantity_by_warehouse[$params['id_product'].'_'.$attribute['id_product_attribute']]) && array_sum($min_quantity_by_warehouse[$params['id_product'].'_'.$attribute['id_product_attribute']]) > 0) ? array_sum($min_quantity_by_warehouse[$params['id_product'].'_'.$attribute['id_product_attribute']]) : (int) $min_quantity[$params['id_product'].'_'.$attribute['id_product_attribute']]);
                            $erp_products->safety_stock = ((isset($safety_stock_by_warehouse[$params['id_product'].'_'.$attribute['id_product_attribute']]) && array_sum($safety_stock_by_warehouse[$params['id_product'].'_'.$attribute['id_product_attribute']]) > 0) ? array_sum($safety_stock_by_warehouse[$params['id_product'].'_'.$attribute['id_product_attribute']]) : (int) $safety_stock[$params['id_product'].'_'.$attribute['id_product_attribute']]);
                            $erp_products->unit_order = (int) $unit_order[$params['id_product'].'_'.$attribute['id_product_attribute']];
                            $erp_products->bundling = (int) $bundling[$params['id_product'].'_'.$attribute['id_product_attribute']];
                            $erp_products->manual_configuration = (int) $manual_configuration[$params['id_product'].'_'.$attribute['id_product_attribute']];

                            if (Tools::getValue('min_quantity') || Tools::getValue('safety_stock') || Tools::getValue('manual_configuration') || Tools::getValue('unit_order') || Tools::getValue('bundling')) {
                                $erp_products->save();
                            }
                        } else {
                            $erp_products->id_employee = (isset(Context::getContext()->employee->id) ? Context::getContext()->employee->id : 1);
                            $erp_products->min_quantity = ((isset($min_quantity_by_warehouse[$params['id_product'].'_'.$attribute['id_product_attribute']]) && array_sum($min_quantity_by_warehouse[$params['id_product'].'_'.$attribute['id_product_attribute']]) > 0) ? array_sum($min_quantity_by_warehouse[$params['id_product'].'_'.$attribute['id_product_attribute']]) : (int) $min_quantity[$params['id_product'].'_'.$attribute['id_product_attribute']]);
                            $erp_products->safety_stock = ((isset($safety_stock_by_warehouse[$params['id_product'].'_'.$attribute['id_product_attribute']]) && array_sum($safety_stock_by_warehouse[$params['id_product'].'_'.$attribute['id_product_attribute']]) > 0) ? array_sum($safety_stock_by_warehouse[$params['id_product'].'_'.$attribute['id_product_attribute']]) : (int) $safety_stock[$params['id_product'].'_'.$attribute['id_product_attribute']]);
                            $erp_products->unit_order = (int) $unit_order[$params['id_product'].'_'.$attribute['id_product_attribute']];
                            $erp_products->bundling = (int) $bundling[$params['id_product'].'_'.$attribute['id_product_attribute']];
                            $erp_products->manual_configuration = (int) $manual_configuration[$params['id_product'].'_'.$attribute['id_product_attribute']];
                            
                            if (Tools::getValue('min_quantity') || Tools::getValue('safety_stock') || Tools::getValue('manual_configuration') || Tools::getValue('unit_order') || Tools::getValue('bundling')) {
                                $erp_products->save();
                            }
                        }
                        
                        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                            if ($min_quantity_by_warehouse[$params['id_product'].'_'.$attribute['id_product_attribute']]) {
                                foreach($min_quantity_by_warehouse[$params['id_product'].'_'.$attribute['id_product_attribute']] as $id_warehouse => $min_quantity_by_wh) {
                                    $this->addErpProductToWarehouse($erp_products->id, $id_warehouse, $min_quantity_by_wh, $safety_stock_by_warehouse[$params['id_product'].'_'.$attribute['id_product_attribute']][$id_warehouse]);
                                }
                            }
                        }
                        unset($id_erp_products, $erp_products);
                    }
                    unset($attributes);

                    $id_erp_products_to_delete = ErpProducts::getProductById($product_obj->id, 0);

                    if ($id_erp_products_to_delete) {
                        $erp_products = new ErpProducts((int) $id_erp_products_to_delete);

                        if (Validate::isLoadedObject($erp_products)) {
                            $erp_products->delete();
                        }
                    }
                }
            } else {
                /* We verify if product exists */
                $id_erp_products = ErpProducts::getProductById($product_obj->id);
                
                if (!$id_erp_products) {
                    $erp_products = new ErpProducts();
                } else {
                    $erp_products = new ErpProducts($id_erp_products);
                }

                if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                    $erp_products->min_quantity_by_warehouse = Tools::jsonEncode($min_quantity_by_warehouse[$params['id_product'].'_0']);
                    $erp_products->safety_stock_by_warehouse = Tools::jsonEncode($safety_stock_by_warehouse[$params['id_product'].'_0']);
                }
                        
                if (!$id_erp_products) {
                    $erp_products = new ErpProducts();

                    $erp_products->id_product = $params['id_product'];
                    $erp_products->id_product_attribute = 0;
                    $erp_products->id_employee = Context::getContext()->employee->id;
                    $erp_products->min_quantity = ((isset($min_quantity_by_warehouse[$params['id_product'].'_0']) && array_sum($min_quantity_by_warehouse[$params['id_product'].'_0'])  > 0) ? array_sum($min_quantity_by_warehouse[$params['id_product'].'_0']) : (int) $min_quantity[$params['id_product'].'_0']);
                    $erp_products->unit_order = (int) $unit_order[$params['id_product'].'_0'];
                    $erp_products->bundling = (int) $bundling[$params['id_product'].'_0'];
                    $erp_products->safety_stock = ((isset($safety_stock_by_warehouse[$params['id_product'].'_0']) && array_sum($safety_stock_by_warehouse[$params['id_product'].'_0']) > 0) ? array_sum($safety_stock_by_warehouse[$params['id_product'].'_0']) : (int) $safety_stock[$params['id_product'].'_0']);
                    $erp_products->manual_configuration = 0;

                    if (Tools::getValue('min_quantity') || Tools::getValue('safety_stock') || Tools::getValue('manual_configuration') || Tools::getValue('unit_order') || Tools::getValue('bundling')) {
                        $erp_products->save();
                    }
                } else {
                    $erp_products = new ErpProducts($id_erp_products);
                    $erp_products->id_employee = Context::getContext()->employee->id;
                    $erp_products->min_quantity = ((isset($min_quantity_by_warehouse[$params['id_product'].'_0']) && array_sum($min_quantity_by_warehouse[$params['id_product'].'_0']) > 0) ? array_sum($min_quantity_by_warehouse[$params['id_product'].'_0']) : (int) $min_quantity[$params['id_product'].'_0']);
                    $erp_products->safety_stock = ((isset($safety_stock_by_warehouse[$params['id_product'].'_0']) && array_sum($safety_stock_by_warehouse[$params['id_product'].'_0']) > 0) ? array_sum($safety_stock_by_warehouse[$params['id_product'].'_0']) : (int) $safety_stock[$params['id_product'].'_0']);
                    $erp_products->manual_configuration = (int) $manual_configuration[$params['id_product'].'_0'];
                    $erp_products->unit_order = (int) $unit_order[$params['id_product'].'_0'];
                    $erp_products->bundling = (int) $bundling[$params['id_product'].'_0'];

                    if (Tools::getValue('min_quantity') || Tools::getValue('safety_stock') || Tools::getValue('manual_configuration') || Tools::getValue('unit_order') || Tools::getValue('bundling')) {
                        $erp_products->save();
                    }
                }
                
                if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                    if ($min_quantity_by_warehouse[$params['id_product'].'_0']) {
                        foreach($min_quantity_by_warehouse[$params['id_product'].'_0'] as $id_warehouse => $min_quantity_by_wh) {
                            $this->addErpProductToWarehouse($erp_products->id, $id_warehouse, $min_quantity_by_wh, $safety_stock_by_warehouse[$params['id_product'].'_0'][$id_warehouse]);
                        }
                    }
                }
                unset($erp_products, $id_erp_products);
            }
        }
        unset($product_obj);
    }

    /***********************************************************************
    HOOK DISPLAY ADMIN ORDER
    ***********************************************************************/

    public function hookDisplayAdminOrder($params)
    {
        if (!$this->active) {
            return;
        }

        $order = new Order((int) $params['id_order']);

        if (Validate::isLoadedObject($order)) {
            $products = $order->getProducts();

            $supply_order = array();

            foreach ($products as &$product) {
                if (($product['product_quantity'] - $product['product_quantity_refunded']) != $product['product_quantity_in_stock']) {
                    /*We verify if there is an supply order in process for this product
                    * build query
                    */
                    $query = new DbQuery();

                    $query->select('
									s.id_erp_order, s.quantity_ordered, s.id_erp_order_detail');
                    $query->from('erp_order_detail', 's');
                    $query->leftjoin('erp_order', 'eo', 'eo.id_erp_order = s.id_erp_order');
                    $query->where('s.id_product = '.(int) $product['product_id'].
                                    ' AND s.id_product_attribute = '.(int) $product['product_attribute_id'].
                                    ' AND eo.id_erp_order_state IN(1,2,3,4)');
                    $query->groupBy('s.id_erp_order_detail');

                    $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

                    if ($results) {
                        foreach ($results as $result) {
                            $erp_order = new ErpOrder((int) $result['id_erp_order']);
                            $erp_order_detail = new ErpOrderDetail((int) $result['id_erp_order_detail']);
                            $erp_order_state = new ErpOrderState((int) $erp_order->id_erp_order_state, Context::getContext()->language->id);
                            $supply_order[] = array('erp_order' => $erp_order, 'erp_order_detail' => $erp_order_detail, 'erp_order_state' => $erp_order_state);
                        }
                    }
                }

                $product['in_stock'] = StockAvailable::getQuantityAvailableByProduct((int) $product['product_id'],
                                                                                                    $product['product_attribute_id'],
                                                                                                    Context::getContext()->shop->id);
                
                //We verify if DLC / DLUO is active
                if ($this->dlcDluoActive) {

                    //We retrieve DLC / DLUO entries by product, combination and order
                    $req = 'SELECT id_dlc_dluo, quantity
                            FROM `'._DB_PREFIX_.'products_dlc_dluo_orders` 
                            WHERE `id_order`  = '.(int)$order->id.'
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

            $order_not_upd_stk = explode(',', Configuration::get('WIC_ERP_NOT_UPD_STK'));

            $order_supplier_allow = false;

            if (!in_array($order->current_state, $order_not_upd_stk)) {
                $order_supplier_allow = true;
            }

            $this->context->smarty->assign(
                            array(
                                'products' => $products,
                                'supply_order' => $supply_order,
                                'order_supplier_allow' => $order_supplier_allow,
                            )
            );

            return $this->display(__FILE__, 'admin_order.tpl');
        } else {
            return;
        }
    }

    /***********************************************************************
    HOOK DISPLAY ADMIN PRODUCT
    ************************************************************************/
    public function hookDisplayAdminProductsExtra($params)
    {
        if (!$this->active) {
            return;
        }
        
        if (version_compare(_PS_VERSION_, '1.7', '<')) {
        	$params['id_product'] = Tools::getValue('id_product');
        }
        
        if ($params['id_product']) {
            $product = new Product((int) $params['id_product'], true, $this->context->language->id);

            if (Validate::isLoadedObject($product)) {
                /* Build attributes combinations */
                $combinations = $product->getAttributesResume($this->context->language->id);
                $warehouses = false;
                
                if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                    $warehouses = Warehouse::getWarehouses(true);
                }
                
                $customer_order = array();
                $supply_order = array();

                if (is_array($combinations) && count($combinations) > 0) {
                    foreach ($combinations as $k => &$combination) {
                        $id_erp_products = ErpProducts::getProductById((int) $product->id, (int) $combination['id_product_attribute']);

                        if ($id_erp_products) {
                            $erp_products_obj = new ErpProducts((int) $id_erp_products);

                            if (Validate::isLoadedObject($erp_products_obj)) {
                                $combination['min_quantity'] = (int) $erp_products_obj->min_quantity;
                                $combination['unit_order'] = (int) $erp_products_obj->unit_order;
                                $combination['bundling'] = (int) $erp_products_obj->bundling;
                                $combination['safety_stock'] = (int) $erp_products_obj->safety_stock;
                                $combination['manual_configuration'] = (int) $erp_products_obj->manual_configuration;
                                
                                if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                                    foreach ($warehouses as $warehouse) {
                                        if ($result = $this->getValueToWarehouse($id_erp_products, $warehouse['id_warehouse'])) {
                                            $combination['min_quantity_by_warehouse'][$warehouse['id_warehouse']] = $result['min_quantity_by_warehouse'];
                                            $combination['safety_stock_by_warehouse'][$warehouse['id_warehouse']] = $result['safety_stock_by_warehouse'];
                                        } else {
                                            $combination['min_quantity_by_warehouse'][$warehouse['id_warehouse']] = 0;
                                            $combination['safety_stock_by_warehouse'][$warehouse['id_warehouse']] = 0;
                                        }
                                    }
                                }
                            }
                        } else {
                            $combination['min_quantity'] = (int) Configuration::get('WIC_ERP_STK_MIN');
                            $combination['unit_order'] = 1;
                            $combination['bundling'] = 1;
                            $combination['safety_stock'] = (int) Configuration::get('WIC_ERP_STK_SAFETY');
                            $combination['manual_configuration'] = 0;
                            
                            if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                                foreach ($warehouses as $warehouse) {
                                    $combination['min_quantity_by_warehouse'][$warehouse['id_warehouse']] = 0;
                                    $combination['safety_stock_by_warehouse'][$warehouse['id_warehouse']] = 0;
                                }
                            }
                        }

                        $combination['in_stock'] = StockAvailable::getQuantityAvailableByProduct((int) $product->id,
                                                                                                                $combination['id_product_attribute'],
                                                                                                                Context::getContext()->shop->id);

                        $orders_detail = $this->getOrdersByProductsDetail((int) $product->id, $combination['id_product_attribute']);

                        if ($orders_detail) {
                            foreach ($orders_detail as $order_detail) {
                                $order = new Order((int) $order_detail['id_order']);
                                $customer = new Customer((int) $order->id_customer);
                                $state = new OrderState((int) $order->current_state, $this->context->language->id);
                                $order->customer = $customer;
                                $order->state = $state;
                                $customer_order[$combination['id_product'].'_'.$combination['id_product_attribute']][] = array('order' => $order,
                                                                                                                                                                                                                                                    'order_detail' => $order->getProducts(), );
                            }
                        }

                        $query = new DbQuery();

                        $query->select('s.id_erp_order, s.quantity_ordered, s.id_erp_order_detail');
                        $query->from('erp_order_detail', 's');
                        $query->leftjoin('erp_order', 'eo', 'eo.id_erp_order = s.id_erp_order AND eo.id_erp_order_state IN(\'1,2,3,4\')');
                        $query->where('s.id_product = '.(int) $product->id.' AND s.id_product_attribute = '.(int) $combination['id_product_attribute']);
                        $query->groupBy('s.id_erp_order_detail');

                        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

                        if ($results) {
                            foreach ($results as $result) {
                                $erp_order = new ErpOrder((int) $result['id_erp_order']);
                                $erp_order_detail = new ErpOrderDetail((int) $result['id_erp_order_detail']);
                                $erp_order_state = new ErpOrderState((int) $erp_order->id_erp_order_state, Context::getContext()->language->id);
                                $supply_order[$combination['id_product'].'_'.$combination['id_product_attribute']][] = array(
                                                        'erp_order' => $erp_order,
                                                        'erp_order_detail' => $erp_order_detail,
                                                        'erp_order_state' => $erp_order_state, );
                            }
                        }
                    }
                } else {
                    $combinations = array();
                    $combinations[] = array('id_product' => $product->id, 'attribute_designation' => $product->name);
                                    /* This is a simple product */

                                    $id_erp_products = ErpProducts::getProductById((int) $product->id, 0);

                    if ($id_erp_products) {
                        $erp_products_obj = new ErpProducts((int) $id_erp_products);
                        if (Validate::isLoadedObject($erp_products_obj)) {
                            $combinations[0]['min_quantity'] = (int) $erp_products_obj->min_quantity;
                            $combinations[0]['unit_order'] = (int) $erp_products_obj->unit_order;
                            $combinations[0]['bundling'] = (int) $erp_products_obj->bundling;
                            $combinations[0]['safety_stock'] = (int) $erp_products_obj->safety_stock;
                            $combinations[0]['manual_configuration'] = (int) $erp_products_obj->manual_configuration;
                            
                            if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                                foreach ($warehouses as $warehouse) {
                                    if ($result = $this->getValueToWarehouse($id_erp_products, $warehouse['id_warehouse'])) {
                                        $combinations[0]['min_quantity_by_warehouse'][$warehouse['id_warehouse']] = $result['min_quantity_by_warehouse'];
                                        $combinations[0]['safety_stock_by_warehouse'][$warehouse['id_warehouse']] = $result['safety_stock_by_warehouse'];
                                    } else {
                                        $combinations[0]['min_quantity_by_warehouse'][$warehouse['id_warehouse']] = 0;
                                        $combinations[0]['safety_stock_by_warehouse'][$warehouse['id_warehouse']] = 0;
                                    }
                                }
                            }
                        }
                    } else {
                        $combinations[0]['min_quantity'] = (int) Configuration::get('WIC_ERP_STK_MIN');
                        $combinations[0]['unit_order'] = 1;
                        $combinations[0]['bundling'] = 1;
                        $combinations[0]['safety_stock'] = (int) Configuration::get('WIC_ERP_STK_SAFETY');
                        $combinations[0]['manual_configuration'] = 0;
                        
                        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                            foreach ($warehouses as $warehouse) {
                                $combinations[0]['min_quantity_by_warehouse'][$warehouse['id_warehouse']] = 0;
                                $combinations[0]['safety_stock_by_warehouse'][$warehouse['id_warehouse']] = 0;
                            }
                        }
                    }

                    $combinations[0]['in_stock'] = StockAvailable::getQuantityAvailableByProduct((int) $product->id,
                                                                                                0,
                                                                                                Context::getContext()->shop->id);
                    $orders_detail = $this->getOrdersByProductsDetail((int) $product->id);

                    if ($orders_detail) {
                        foreach ($orders_detail as $order_detail) {
                            $order = new Order((int) $order_detail['id_order']);
                            $customer = new Customer((int) $order->id_customer);
                            $state = new OrderState((int) $order->current_state, $this->context->language->id);
                            $order->customer = $customer;
                            $order->state = $state;
                            $customer_order[$product->id][] = array('order' => $order, 'order_detail' => $order->getProducts());
                        }
                    }

                    $query = new DbQuery();

                    $query->select('
                                                                    s.id_erp_order, s.quantity_ordered, s.id_erp_order_detail');
                    $query->from('erp_order_detail', 's');
                    $query->leftjoin('erp_order', 'eo', 'eo.id_erp_order = s.id_erp_order AND eo.id_erp_order_state IN(\'1,2,3,4\')');
                    $query->where('s.id_product = '.(int) $product->id.' AND s.id_product_attribute = 0');
                    $query->groupBy('s.id_erp_order_detail');

                    $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

                    if ($results) {
                        foreach ($results as $result) {
                            $erp_order = new ErpOrder((int) $result['id_erp_order']);
                            $erp_order_detail = new ErpOrderDetail((int) $result['id_erp_order_detail']);
                            $erp_order_state = new ErpOrderState((int) $erp_order->id_erp_order_state, Context::getContext()->language->id);
                            $supply_order[$product->id][] = array(
                                                    'erp_order' => $erp_order,
                                                    'erp_order_detail' => $erp_order_detail,
                                                    'erp_order_state' => $erp_order_state, );
                        }
                    }
                }
            }

            $this->context->smarty->assign(
                    array(
                        'combinations' => $combinations,
                        'customer_order' => $customer_order,
                        'supply_order' => $supply_order,
                        'warehouses' => $warehouses,
                        )
                    );

            return $this->display(__FILE__, 'admin_product.tpl');
        }
    }

    /****************************************************************
    * HOOK DISPLAY ORDER DETAIL
    ***************************************************************/
    public function hookDisplayOrderDetail($params)
    {
        if (!$this->active || !Configuration::get('WIC_ERP_DISPLAY_FO_ORDER_DETAIL')) {
            return;
        }

        $order = $params['order'];

        if (Validate::isLoadedObject($order)) {
            $products = $order->getProducts();

            foreach ($products as &$product) {
                $product_obj = new Product($product['id_product'], false, (int) Context::getContext()->language->id);
                $product['in_stock'] = StockAvailable::getQuantityAvailableByProduct((int) $product['product_id'],
                                                                                    $product['product_attribute_id'],
                                                                                    Context::getContext()->shop->id);
                $product['unavailable_message'] = $product_obj->available_later;
            }

            $order_not_upd_stk = explode(',', Configuration::get('WIC_ERP_NOT_UPD_STK'));

            $order_supplier_allow = false;

            if (!in_array($order->current_state, $order_not_upd_stk)) {
                $order_supplier_allow = true;
            }

            $this->context->smarty->assign(
                    array(
                        'products' => $products,
                        'order_supplier_allow' => $order_supplier_allow,
                    )
            );
        }

        return $this->display(__FILE__, 'front_order_detail.tpl');
    }

    /****************************************************************
    SPECIFIC METHOD
    ****************************************************************/
    /****************************************************************
        Get stock product
    ****************************************************************/
    public function loadStockDataReservable($product, $id_product_attribute = 0, $context)
    {
        if (!$this->active) {
            return;
        }

        if (Validate::isLoadedObject($product)) {
            
            $query = new DbQuery();
            $query->select('SUM(quantity)');
            $query->from('stock_available');

            // if null, it's a product without attributes
            $query->where('id_product = '.(int)$product->id);

            $query->where('id_product_attribute = '.(int)$id_product_attribute);
            $query = StockAvailable::addSqlShopRestriction($query, (int)$context->shop->id);
            $product->quantity = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
            
            $product->out_of_stock = StockAvailable::outOfStock((int) $product->id);
            $product->depends_on_stock = StockAvailable::dependsOnStock((int) $product->id);
            if ($context->shop->getContext() == Shop::CONTEXT_GROUP && $context->shop_group->share_stock == 1) {
                $product->advanced_stock_management = $product->useAdvancedStockManagement();
            }

            /* We retrieve reserved product and not reserved */
            $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
							SELECT
								SUM(CAST(od.`product_quantity` AS SIGNED) - CAST(od.`product_quantity_refunded` AS SIGNED)) as reservable_qty,
								SUM(od.`product_quantity_in_stock`) as reserved_qty
							FROM `'._DB_PREFIX_.'order_detail` od
							LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.id_product = od.product_id)
							LEFT JOIN `'._DB_PREFIX_.'product_shop` ps ON (ps.id_product = p.id_product && ps.id_shop = od.id_shop)
							LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.id_order = od.id_order && o.id_shop = od.id_shop)
							WHERE od.`product_id` = '.(int) $product->id.'
							AND od.`product_attribute_id` = '.(int) $id_product_attribute.'
							AND o.`current_state` IN('.pSQL(Configuration::get('WIC_ERP_NOT_COMPLETE')).(Configuration::get('WIC_STATE_ERP_COMPLETE') ?
                                                    ','.pSQL(Configuration::get('WIC_STATE_ERP_COMPLETE')).(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') ? ','.pSQL(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS')) : '') : '').')');

            $quantity = $product->quantity;

            if (is_array($res) && isset($res[0])) {
                if (($res[0]['reservable_qty'] - $res[0]['reserved_qty'] + $quantity) > ($res[0]['reservable_qty'] - $res[0]['reserved_qty'])) {
                    $quantity = $res[0]['reservable_qty'] - $res[0]['reserved_qty'];
                } else {
                    $quantity += $res[0]['reservable_qty'] - $res[0]['reserved_qty'];
                }
            }

            return (int) $quantity;
        }
    }

    /****************************************************************
        Get shippable stock product
    ****************************************************************/
    public function loadStockDataShippable($product, $id_product_attribute = 0, $context)
    {
        if (!$this->active) {
            return;
        }

        if (Validate::isLoadedObject($product)) {
     
            $query = new DbQuery();
            $query->select('SUM(quantity)');
            $query->from('stock_available');

            // if null, it's a product without attributes
            $query->where('id_product = '.(int)$product->id);

            $query->where('id_product_attribute = '.(int)$id_product_attribute);
            $query = StockAvailable::addSqlShopRestriction($query, (int)$context->shop->id);
            $product->quantity = (int)Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
            
            $product->out_of_stock = StockAvailable::outOfStock((int) $product->id);
            $product->depends_on_stock = StockAvailable::dependsOnStock((int) $product->id);
            if ($context->shop->getContext() == Shop::CONTEXT_GROUP && $context->shop_group->share_stock == 1) {
                $product->advanced_stock_management = $product->useAdvancedStockManagement();
            }
            
            /* We retrieve reserved product and not reserved */
            $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
							SELECT SUM(CAST(od.`product_quantity` AS SIGNED) - CAST(od.`product_quantity_refunded` AS SIGNED)) as shippable_qty
							FROM `'._DB_PREFIX_.'order_detail` od
							LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.id_product = od.product_id)
							LEFT JOIN `'._DB_PREFIX_.'product_shop` ps ON (ps.id_product = p.id_product && ps.id_shop = od.id_shop)
							LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.id_order = od.id_order && o.id_shop = od.id_shop)
							WHERE od.`product_id` = '.(int) $product->id.'
							AND od.`product_attribute_id` = '.(int) $id_product_attribute.'
							AND o.`current_state` IN('.pSQL(Configuration::get('WIC_ERP_NOT_COMPLETE')).(Configuration::get('WIC_STATE_ERP_COMPLETE') ?
                                                    ','.pSQL(Configuration::get('WIC_STATE_ERP_COMPLETE')).(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') ? ','.pSQL(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS')) : '') : '').')');

            $quantity = $product->quantity;

            if (is_array($res) && isset($res[0])) {
                if (($res[0]['shippable_qty'] + $quantity) > $res[0]['shippable_qty']) {
                    $quantity = $res[0]['shippable_qty'];
                } else {
                    $quantity += $res[0]['shippable_qty'];
                }
            }

            return (int) $quantity;
        }
    }

    /********************************************************************
    GET ORDER PENDING By Products Detail
    *******************************************************************/
    public function getOrdersByProductsDetail($id_product, $id_product_attribute = 0)
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
						SELECT o.`id_order`, `id_order_detail`
						FROM `'._DB_PREFIX_.'order_detail` od
						LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.id_order = od.id_order && o.id_shop = od.id_shop)
						WHERE od.`product_id` = '.(int) $id_product.'
						AND od.`product_attribute_id` = '.(int) $id_product_attribute.'
						AND o.`current_state` IN('.(Configuration::get('WIC_ERP_NOT_UPD_STK') ? Configuration::get('WIC_ERP_NOT_UPD_STK').',': '')
							.Configuration::get('WIC_ERP_NOT_UPD_STATE').','
                            .pSQL(Configuration::get('WIC_ERP_NOT_COMPLETE'))
                            .(Configuration::get('WIC_STATE_ERP_COMPLETE') ? ','.pSQL(Configuration::get('WIC_STATE_ERP_COMPLETE')).(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') ? ','.pSQL(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS')) : '') : '').')
						ORDER BY o.`id_order`');
    }

    /************************************************************************************
    SET QUANTITY IN STOCK TO 0 BY Products Details
    *************************************************************************************/
    public function updateQtyOrdersByProductsDetail($order_synchronise)
    {
        $this->log[] = '------------------------------------------------------'.PHP_EOL;
        $this->log[] = 'Reinitialize order #'.$order_synchronise.PHP_EOL;
        $this->log[] = '------------------------------------------------------'.PHP_EOL.PHP_EOL;

        $query = 'UPDATE
                            `'._DB_PREFIX_.'order_detail` od
                    SET
                            od.`product_quantity_in_stock` = 0
                    WHERE
                            od.`id_order` IN ('.pSQL($order_synchronise).')';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->execute($query);
    }

    /**************************************************************************************
    VERIFY ORDER IS COMPLETE
    ***************************************************************************************/
    public function orderIsComplete($id_order)
    {
        $order = new Order((int) $id_order);

        if (Validate::isLoadedObject($order)) {
            $products = $order->getProductsDetail();

            $order_complete = true;
             
            $productPurchased = 0;
            $productRefunded = 0;
            
            foreach ($products as $product) {
                $order_detail = new OrderDetail((int) $product['id_order_detail']);
                
                $productPurchased += $order_detail->product_quantity;
                $productRefunded += $order_detail->product_quantity_refunded;
                        
                if ($order_detail->product_quantity_in_stock != ($order_detail->product_quantity - $order_detail->product_quantity_refunded)) {
                    $order_complete = false;
                }

                unset($order_detail);
            }
            unset($products);
        }

        if ($productPurchased == $productRefunded) {
            $order_complete = false;
        }
        unset($order);

        return $order_complete;
    }

    /********************************************************************************
    UPATE STATUS TO COMPLETE
    ********************************************************************************/
    public function updateOrderStatus($id_order)
    {
        $order = new Order((int) $id_order);

        if (Validate::isLoadedObject($order)) {
            $last_order_state = $order->current_state;
            $status_not_update = explode(',', Configuration::get('WIC_ERP_NOT_UPD_STATE'));
            $status_complete = explode(',', Configuration::get('WIC_STATE_ERP_COMPLETE').(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') ? ','.Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') : ''));
            if (!in_array($last_order_state, $status_not_update) && !in_array($last_order_state, $status_complete)) {
                $this->log[] = '******************************************************'.PHP_EOL;
                $this->log[] = 'DATE '.date('Y-m-d H:i:s').PHP_EOL;
                $this->log[] = 'update STATE order COMPLETE #'.$order->id.PHP_EOL;

                if (!Configuration::get('WIC_ERP_COMPLETE_IS_PREFIX')) {
                    $order_state = new OrderState((int) Configuration::get('WIC_STATE_ERP_COMPLETE'));
                } else {
                    //We verify and create order state with complete Prefix
                    $new_state = $this->createNewOrderStatus((int) $last_order_state, (int) $id_order);
                    $order_state = new OrderState((int) $new_state);
                }

                $new_history = new OrderHistory();
                $new_history->id_order = (int) $order->id;

                $use_existings_payment = false;
                if (!$order->hasInvoice()) {
                    $use_existings_payment = true;
                }

                try {
                    $new_history->changeIdOrderState((int) $order_state->id, $order->id, $use_existings_payment);
                    $new_history->add();
                } catch (Exception $e) {
                    $this->log[] = $e->getMessage().PHP_EOL;
                }
                $this->log[] = '******************************************************'.PHP_EOL.PHP_EOL;
                unset($order_state, $new_history);
            }
        }
        unset($order);
    }

    /***************************************************************************************
    PUT the previous status if order is modified
    ****************************************************************************************/
    public function updateBackOrderStatus($id_order)
    {
        $order = new Order((int) $id_order);
        //We verify if we update status
        $notDisplay = explode(',', Configuration::get('WIC_ERP_NOT_DISPLAY'));
        
        if (Validate::isLoadedObject($order)) {
            
            $products = $order->getProductsDetail();
             
            $productPurchased = 0;
            $productRefunded = 0;
            
            foreach ($products as $product) {
                $order_detail = new OrderDetail((int) $product['id_order_detail']);
                
                $productPurchased += $order_detail->product_quantity;
                $productRefunded += $order_detail->product_quantity_refunded;

                unset($order_detail);
            }
            unset($products);

            if ($productPurchased == $productRefunded) {
                if (Configuration::get('WIC_ERP_REFUNDED_STATE') && $order->current_state != Configuration::get('WIC_ERP_REFUNDED_STATE')) {
                    $use_existings_payment = false;
                    $new_history = new OrderHistory();
                    $new_history->id_order = (int) $order->id;
                    $new_history->changeIdOrderState((int) Configuration::get('WIC_ERP_REFUNDED_STATE'), $order->id, $use_existings_payment);         
                    $new_history->add();
                    unset($new_history); 
                }
            } else {
                $last_order_state = $order->current_state;
                $authorized_order_state = explode(',', Configuration::get('WIC_ERP_NOT_COMPLETE').','.Configuration::get('WIC_STATE_ERP_COMPLETE').(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') ? ','.Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') : ''));
                $status_not_update = explode(',', Configuration::get('WIC_ERP_NOT_UPD_STATE'));
                $previous_id_order_state = $this->getPreviousStatusOrder((int) $order->id);

                if (version_compare(_PS_VERSION_, '1.6.0.11', '>=')) {
                    $wic_verify_order_state = (int) Configuration::get('WIC_ERP_BACK_ORDER_STATE');
                } else {
                    $wic_verify_order_state = (int) Configuration::get('WIC_ERP_VERIFY_ORDER');
                }

                if (in_array($last_order_state, $authorized_order_state) && !in_array($last_order_state, $status_not_update) && $last_order_state != (int) $wic_verify_order_state && $previous_id_order_state) {
                    $this->log[] = '******************************************************'.PHP_EOL;
                    $this->log[] = 'DATE '.date('Y-m-d H:i:s').PHP_EOL;
                    $this->log[] = 'update backward STATE order #'.$order->id.PHP_EOL;
                    $this->log[] = '******************************************************'.PHP_EOL.PHP_EOL;

                    $new_history = new OrderHistory();
                    $new_history->id_order = (int) $order->id;

                    $use_existings_payment = false;

                    if (!$order->hasInvoice()) {
                        $use_existings_payment = true;
                    }

                    if (version_compare(_PS_VERSION_, '1.6.0.11', '>=')) {
                        $order_state = new OrderState((int) Configuration::get('WIC_ERP_BACK_ORDER_STATE'));
                        if (Validate::isLoadedObject($order_state)) {
                            $order_state->paid = 0;
                            $order_state->update();
                            $new_history->changeIdOrderState((int) Configuration::get('WIC_ERP_BACK_ORDER_STATE'), $order->id, $use_existings_payment);
                            $order_state->paid = 1;
                            $order_state->update();
                        }
                    } else {
                        $order_state = new OrderState((int) Configuration::get('WIC_ERP_VERIFY_ORDER'));
                        if (Validate::isLoadedObject($order_state)) {
                            $order_state->paid = 0;
                            $order_state->update();
                            $new_history->changeIdOrderState((int) Configuration::get('WIC_ERP_VERIFY_ORDER'), $order->id, $use_existings_payment);
                            $order_state->paid = 1;
                            $order_state->update();
                        }
                    }
                    
                    $new_history->add();
                    unset($new_history);
                }
            }
        }
        unset($order);
    }

    /**************************************************************************
    GET PREVIOUS STATUS ORDER
    **************************************************************************/
    public function getPreviousStatusOrder($id_order)
    {
        if ($id_order) {
            $order = new Order((int) $id_order);

            if (Validate::isLoadedObject($order)) {
                if ($order->current_state != Configuration::get('WIC_ERP_VERIFY_ORDER')) {
                    $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
									SELECT
										oh.`id_order_history`
									FROM
										`'._DB_PREFIX_.'order_history` oh
									WHERE oh.`id_order` = '.(int) $id_order.'
									AND oh.`id_order_state` != '.(int) Configuration::get('WIC_ERP_VERIFY_ORDER').'
									ORDER BY oh.`id_order_history` DESC
									LIMIT 1,1');
                } else {
                    $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
									SELECT
										oh.`id_order_history`
									FROM
										`'._DB_PREFIX_.'order_history` oh
									WHERE oh.`id_order` = '.(int) $id_order.'
									AND oh.`id_order_state` != '.(int) Configuration::get('WIC_ERP_VERIFY_ORDER').'
									ORDER BY oh.`id_order_history` DESC
									LIMIT 1');
                }

                if ($res && isset($res[0]['id_order_history'])) {
                    $order_history = new OrderHistory((int) $res[0]['id_order_history']);

                    if (Validate::isLoadedObject($order_history)) {
                        return $order_history->id_order_state;
                    }
                }
            }
        }

        return false;
    }

    public function getAllOrderState($id_order)
    {
        $count_order_state = Db::getInstance()->getValue('
						SELECT COUNT(`id_order_state`)
						FROM `'._DB_PREFIX_.'order_history`
						WHERE `id_order` = '.(int) $id_order.'
						AND	id_order_state NOT IN ('.pSQL(Configuration::get('WIC_ERP_VERIFY_ORDER')).(Configuration::get('WIC_ERP_NOT_UPD_STK') ? ','.pSQL(Configuration::get('WIC_ERP_NOT_UPD_STK')) : '').')');

        // returns false if there is no state
        if (!$count_order_state) {
            return false;
        }

        return $count_order_state;
    }

    /**************************************************************************
    PUT data in log file
    **************************************************************************/
    public function logTxtFile($log_txt)
    {
        return;
        
        $log_txt = $this->log;
        /* Uncomment just for development */
        $file_log_path = _PS_MODULE_DIR_.$this->name.'/log/wic_erp_log_'.date('Y-m-d').'.txt';
        if (is_array($log_txt))
        {
            foreach ($log_txt as $key => $value)
                file_put_contents($file_log_path, $value, FILE_APPEND);
        }
        
        //Reset Log value
        $this->log = array();
    }

    public function getOrderCount()
    {
        $query = new DbQuery();
        $query->select('COUNT(`id_order`)');
        $query->from('orders');
        $query->where('`current_state` IN('.pSQL(Configuration::get('WIC_ERP_NOT_COMPLETE')).','.pSQL(Configuration::get('WIC_STATE_ERP_COMPLETE')).(Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') ? ','.Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS') : '').')');

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }

    public function createNewOrderStatus($id_state, $id_order)
    {
        //We verify if state new state with "complete order prefix exists"
        $complete_status = new OrderState((int) Configuration::get('WIC_STATE_ERP_COMPLETE'));
        $current_state = new OrderState((int) $id_state);
        $customer_thread_state = explode(',', Configuration::get('WIC_ERP_CUSTOMER_THREAD'));

        //We retrieve previous status
        if (Configuration::get('WIC_ERP_BACK_ORDER_STATE') == $id_state) {
            $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS('
                                                                SELECT
                                                                        oh.`id_order_history`
                                                                FROM
                                                                        `'._DB_PREFIX_.'order_history` oh
                                                                WHERE oh.`id_order` = '.(int) $id_order.'
                                                                AND oh.`id_order_state` != '.(int) Configuration::get('WIC_ERP_BACK_ORDER_STATE').'
                                                                ORDER BY oh.`id_order_history` DESC
                                                                LIMIT 1');

            if ($res && isset($res[0]['id_order_history'])) {
                $order_history = new OrderHistory((int) $res[0]['id_order_history']);

                if (Validate::isLoadedObject($order_history)) {
                    $current_state = new OrderState((int) $order_history->id_order_state);
                }
            }
        }

        $completeList = explode(',', Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS'));

        if (in_array((int) $current_state->id, $completeList)) {
            $id_order_state = Db::getInstance()->getValue('SELECT `id_order_state` FROM `'._DB_PREFIX_.'order_state_lang` WHERE `id_lang` = '.(int) Configuration::get('PS_LANG_DEFAULT').' AND `name` = \''.$current_state->name[(int) Configuration::get('PS_LANG_DEFAULT')].'\'');
        } else {
            $id_order_state = Db::getInstance()->getValue('SELECT `id_order_state` FROM `'._DB_PREFIX_.'order_state_lang` WHERE `id_lang` = '.(int) Configuration::get('PS_LANG_DEFAULT').' AND `name` = \''.$complete_status->name[(int) Configuration::get('PS_LANG_DEFAULT')].' - '.$current_state->name[(int) Configuration::get('PS_LANG_DEFAULT')].'\'');
        }

        $order_state = new OrderState((int) $id_order_state);
        if (!Validate::isLoadedObject($order_state)) {
            $order_state = new OrderState();
            $order_state->name = array();
            foreach (Language::getLanguages() as $language) {
                $order_state->name[$language['id_lang']] = $complete_status->name[$language['id_lang']].' - '.$current_state->name[$language['id_lang']];
            }

            $order_state->send_email = false;
            $order_state->color = '#f2bfff';
            $order_state->hidden = true;
            $order_state->delivery = false;
            $order_state->logable = true;
            if (!in_array((int) $current_state->id, $customer_thread_state)) {
                $order_state->invoice = false;
            } else {
                $order_state->invoice = true;
            }

            if ($order_state->add()) {
                Tools::copy(_PS_MODULE_DIR_.$this->name.'/logo.gif', dirname(__FILE__).'/../../img/os/'.(int) $order_state->id.'.gif');
            } else {
                return false;
            }

            $list_complete = explode(',', Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS'));

            if (!in_array((int) $order_state->id, $list_complete)) {
                $list_complete[] = (int) $order_state->id;
            }

            Configuration::updateValue('WIC_ERP_COMPLETE_LIST_STATUS', implode(',', $list_complete));

            return (int) $order_state->id;
        } else {
            $list_complete = explode(',', Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS'));

            if (!in_array((int) $order_state->id, $list_complete)) {
                $list_complete[] = (int) $order_state->id;
                Configuration::updateValue('WIC_ERP_COMPLETE_LIST_STATUS', implode(',', $list_complete));
            }

            return (int) $order_state->id;
        }
    }

    /**
     * For a given id_product, synchronizes StockAvailable::quantity with Stock::usable_quantity.
     *
     * @param int $id_product
     */
    public function synchronize($id_product, $order_id_shop = null)
    {
        if (!Validate::isUnsignedId($id_product)) {
            return false;
        }

        //if product is pack sync recursivly product in pack
        if (Pack::isPack($id_product)) {
            if (Validate::isLoadedObject($product = new Product((int) $id_product))) {
                if ($product->pack_stock_type == 1 || $product->pack_stock_type == 2 || ($product->pack_stock_type == 3 && Configuration::get('PS_PACK_STOCK_TYPE') > 0)) {
                    $products_pack = Pack::getItems($id_product, (int) Configuration::get('PS_LANG_DEFAULT'));
                    foreach ($products_pack as $product_pack) {
                        StockAvailable::synchronize($product_pack->id, $order_id_shop);
                    }
                }
            } else {
                return false;
            }
        }

        // gets warehouse ids grouped by shops
        $ids_warehouse = Warehouse::getWarehousesGroupedByShops();
        if ($order_id_shop !== null) {
            $order_warehouses = array();
            $wh = Warehouse::getWarehouses(false, (int) $order_id_shop);
            foreach ($wh as $warehouse) {
                $order_warehouses[] = $warehouse['id_warehouse'];
            }
        }

        // gets all product attributes ids
        $ids_product_attribute = array();
        foreach (Product::getProductAttributesIds($id_product) as $id_product_attribute) {
            $ids_product_attribute[] = $id_product_attribute['id_product_attribute'];
        }

        // Allow to order the product when out of stock?
        $out_of_stock = StockAvailable::outOfStock($id_product);

        $manager = StockManagerFactory::getManager();
        // loops on $ids_warehouse to synchronize quantities
        foreach ($ids_warehouse as $id_shop => $warehouses) {
            // first, checks if the product depends on stock for the given shop $id_shop
            if (StockAvailable::dependsOnStock($id_product, $id_shop)) {
                // init quantity
                $product_quantity = 0;

                // if it's a simple product
                if (empty($ids_product_attribute)) {
                    $allowed_warehouse_for_product = WareHouse::getProductWarehouseList((int) $id_product, 0, (int) $id_shop);
                    $allowed_warehouse_for_product_clean = array();
                    foreach ($allowed_warehouse_for_product as $warehouse) {
                        $allowed_warehouse_for_product_clean[] = (int) $warehouse['id_warehouse'];
                    }
                    $allowed_warehouse_for_product_clean = array_intersect($allowed_warehouse_for_product_clean, $warehouses);
                    if ($order_id_shop != null && !count(array_intersect($allowed_warehouse_for_product_clean, $order_warehouses))) {
                        continue;
                    }

                    $product_quantity = $manager->getProductRealQuantities($id_product, null, $allowed_warehouse_for_product_clean, true);
                    
                    $query = array(
                                'table' => 'stock_available',
                                'data' => array('quantity' => $product_quantity),
                                'where' => 'id_product = '.(int) $id_product.
                                StockAvailable::addSqlShopRestriction(null, $id_shop),
                            );
                    Db::getInstance()->update($query['table'], $query['data'], $query['where']);
                }
                // else this product has attributes, hence loops on $ids_product_attribute
                else {
                    foreach ($ids_product_attribute as $id_product_attribute) {
                        $allowed_warehouse_for_combination = WareHouse::getProductWarehouseList((int) $id_product, (int) $id_product_attribute, (int) $id_shop);
                        $allowed_warehouse_for_combination_clean = array();
                        foreach ($allowed_warehouse_for_combination as $warehouse) {
                            $allowed_warehouse_for_combination_clean[] = (int) $warehouse['id_warehouse'];
                        }
                        $allowed_warehouse_for_combination_clean = array_intersect($allowed_warehouse_for_combination_clean, $warehouses);
                        if ($order_id_shop != null && !count(array_intersect($allowed_warehouse_for_combination_clean, $order_warehouses))) {
                            continue;
                        }

                        $quantity = $manager->getProductRealQuantities($id_product, $id_product_attribute, $allowed_warehouse_for_combination_clean, true);

                        $query = new DbQuery();
                        $query->select('COUNT(*)');
                        $query->from('stock_available');
                        $query->where('id_product = '.(int) $id_product.' AND id_product_attribute = '.(int) $id_product_attribute.
                            StockAvailable::addSqlShopRestriction(null, $id_shop));

                        if ((int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query)) {
                            $query = array(
                                'table' => 'stock_available',
                                'data' => array('quantity' => $quantity),
                                'where' => 'id_product = '.(int) $id_product.' AND id_product_attribute = '.(int) $id_product_attribute.
                                StockAvailable::addSqlShopRestriction(null, $id_shop),
                            );
                            Db::getInstance()->update($query['table'], $query['data'], $query['where']);
                        } else {
                            $query = array(
                                'table' => 'stock_available',
                                'data' => array(
                                    'quantity' => $quantity,
                                    'depends_on_stock' => 1,
                                    'out_of_stock' => $out_of_stock,
                                    'id_product' => (int) $id_product,
                                    'id_product_attribute' => (int) $id_product_attribute,
                                ),
                            );
                            StockAvailable::addSqlShopParams($query['data'], $id_shop);
                            Db::getInstance()->insert($query['table'], $query['data']);
                        }

                        $product_quantity += $quantity;
                    }
                }
                // updates
                // if $id_product has attributes, it also updates the sum for all attributes
                if (($order_id_shop != null && array_intersect($warehouses, $order_warehouses)) || $order_id_shop == null) {
                    $query = array(
                        'table' => 'stock_available',
                        'data' => array('quantity' => $product_quantity),
                        'where' => 'id_product = '.(int) $id_product.' AND id_product_attribute = 0'.
                        StockAvailable::addSqlShopRestriction(null, $id_shop),
                    );
                    Db::getInstance()->update($query['table'], $query['data'], $query['where']);
                }
            }
        }
        // In case there are no warehouses, removes product from StockAvailable
        if (count($ids_warehouse) == 0 && StockAvailable::dependsOnStock((int) $id_product)) {
            Db::getInstance()->update('stock_available', array('quantity' => 0), 'id_product = '.(int) $id_product);
        }

        Cache::clean('StockAvailable::getQuantityAvailableByProduct_'.(int) $id_product.'*');
    }
    
    public function addErpProductToWarehouse($id_erp_products, $id_warehouse, $min_stock, $safety_stock)
    {
        $result = Db::getInstance()->getValue('SELECT `id_erp_products` FROM `'._DB_PREFIX_.'erp_products_by_warehouse` WHERE `id_erp_products` = '.$id_erp_products.' AND `id_warehouse` = '.$id_warehouse);
        
        if ($result) {
            Db::getInstance()->update('erp_products_by_warehouse', array('min_quantity_by_warehouse' => $min_stock, 'safety_stock_by_warehouse' => $safety_stock), '`id_erp_products` = '.$id_erp_products.' AND `id_warehouse` = '.$id_warehouse);
        } else {
             Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'erp_products_by_warehouse` (`id_erp_products`,`id_warehouse`, `min_quantity_by_warehouse`, `safety_stock_by_warehouse`) VALUES ('.$id_erp_products.','.$id_warehouse.','.$min_stock.', '.$safety_stock.')');
        }
    }
    
    public function getValueToWarehouse($id_erp_products, $id_warehouse)
    {
        return Db::getInstance()->getRow('SELECT `min_quantity_by_warehouse`, `safety_stock_by_warehouse` FROM `'._DB_PREFIX_.'erp_products_by_warehouse` WHERE `id_erp_products` = '.$id_erp_products.' AND `id_warehouse` = '.$id_warehouse);
    }
}
