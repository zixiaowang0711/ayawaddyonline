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

require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpProducts.php';

class AdminErpSuppliersController extends ModuleAdminController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->table = 'erp_suppliers';
        $this->className = 'ErpSuppliers';
        $this->identifier = 'id_erp_suppliers';
        $this->lang = false;
        $this->is_template_list = false;
        $this->multishop_context = Shop::CONTEXT_ALL;
        $this->bootstrap = true;
        $this->list_no_link = true;
        $this->addRowAction('delete');
        $this->addRowAction('edit');
        if (Tools::isSubmit('submitReseterp_suppliers'))
            $this->processResetFilters();
                
                $this->fieldImageSettings = array(
                    array(
                        'name' => 'id_lang',
                        'dir' => 'l'
                    ),
                    array(
                        'name' => 'no_picture',
                        'dir' => 'p'
                    )
                );
                
        $this->bulk_actions = array(
            'update' => array('text' => $this->l('Update suppliers'), 'confirm' => $this->l('Update all suppliers?'))
        );

        $this->fields_list = array(
            'id_erp_suppliers' => array(
                'title' => $this->l('ID in ERP'),
                'width' => 25,
                'havingFilter' => true
            ),
            'supplier' => array(
                'title' => $this->l('Supplier'),
                'width' => 150,
                'filter_key' => 's!name'
            ),
            'delivery' => array(
                'title' => $this->l('Delivery (Days)'),
                'width' => 30,
                'filter_key' => 'a!delivery',
                'callback' => 'editSuppliers'
            ),
                        'id_lang' => array(
                                'title' => $this->l('Flag'),
                                'align' => 'center',
                                'image' => 'l',
                                'orderby' => false,
                                'search' => false,
                                'class' => 'fixed-width-xs',
                                'image_id' => 'id_lang',
                        ),
            'email' => array(
                'title' => $this->l('Email'),
                'width' => 150,
                'filter_key' => 'a!email',
                'callback' => 'editSuppliersEmailConfiguration'
            ),
            'minimum_price' => array(
                'title' => $this->l('Min. amount order'),
                'width' => 50,
                'filter_key' => 'a!minimum_price',
                'callback' => 'editSuppliersMinimumPriceConfiguration'
            ),
            'minimum_price_free_shipping' => array(
                'title' => $this->l('Min. amount free shipping'),
                'width' => 50,
                'filter_key' => 'a!minimum_price_free_shipping',
                'callback' => 'editSuppliersMinimumPriceShippingConfiguration'
            ),
            'shipping_price' => array(
                'title' => $this->l('Default shipping price'),
                'width' => 50,
                'filter_key' => 'a!shipping_price',
                'callback' => 'editSuppliersShippingPriceConfiguration'
            ),
            'vat_exemption' => array(
                'title' => $this->l('VAT Exemption'),
                'width' => 70,
                'align' => 'center',
                'callback' => 'editSuppliersVatConfiguration'
            ),
            'manual_configuration' => array(
                'title' => $this->l('Manual configuration'),
                'width' => 70,
                'align' => 'center',
                'callback' => 'editSuppliersConfiguration'
            ),
        );
        parent::__construct();
    }

        /**
     * AdminController::renderList() override
     * @see AdminController::renderList()
     */
    public function renderList()
    {
        if (Tools::isSubmit('submitFilter')) {
            parent::processFilter();
        }

        $this->tpl_list_vars['filter_status'] = $this->getFilterStatus();

        // overrides query
        $this->_select = '
			s.name AS supplier';

        $this->_join = '
						LEFT JOIN `'._DB_PREFIX_.'supplier` s ON a.id_supplier = s.id_supplier
						';

        if ($this->getFilterStatus() != 0) {
            $this->_where .= ' AND st.enclosed != 1';
            self::$currentIndex .= '&filter_status=on';
        }

        $first_list = parent::renderList();

        return $first_list;
    }

    /**
     * AdminController::getList() override
     * @see AdminController::getList()
     */
    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        if (Tools::getValue($this->list_id.'Orderby')) {
            $order_by = Tools::getValue($this->list_id.'Orderby');
        }
        
        if (Tools::getValue($this->list_id.'Orderway')) {
            $order_way = Tools::getValue($this->list_id.'Orderway');
        }
        
        if (Tools::getValue($this->list_id.'Orderby') == 'name') {
            $order_by = 'supplier_name';
        }

        parent::getList($id_lang, $order_by, $order_way, $start, $limit, $id_lang_shop);
    }

    /**
     * Gets the current filter used
     *
     * @return int status
     */
    protected function getFilterStatus()
    {
        static $status = 0;

        $status = 0;
        if (Tools::getValue('filter_status') === 'on') {
            $status = 1;
        }

        return $status;
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitBulkupdateerp_suppliers') || Tools::isSubmit('submitBulkOneSupplier')) {
            $suppliers = Tools::getValue('erp_suppliersBox');
            $update_delivery = Tools::getValue('updateDelivery');
            $manual_configuration = Tools::getValue('updateManualConfiguration');
            $vat_exemption = Tools::getValue('updateVatExemption');
            $email_configuration = Tools::getValue('updateEmailConfiguration');
            $minimum_price_configuration = Tools::getValue('updateMinimumPriceConfiguration');
            $minimum_price_free_shipping_configuration = Tools::getValue('updateMinimumPriceShippingConfiguration');
            $shipping_price = Tools::getValue('updateShippingPriceConfiguration');

            if ($suppliers) {
                $suppliers_update = array();
                
                foreach ($suppliers as $key => $value) {
                    $erp_suppliers = new ErpSuppliers((int)$value);
                    $suppliers_update[] = (int)$value;

                    if (Validate::isLoadedObject($erp_suppliers)) {
                        $erp_suppliers->delivery = (int)$update_delivery[$value];
                        if (isset($manual_configuration[$value])) {
                            $erp_suppliers->manual_configuration = (int)$manual_configuration[$value];
                        } else {
                            $erp_suppliers->manual_configuration = 0;
                        }
                        
                        if (isset($vat_exemption[$value])) {
                            $erp_suppliers->vat_exemption = (int)$vat_exemption[$value];
                        } else {
                            $erp_suppliers->vat_exemption = 0;
                        }

                        if (isset($email_configuration[$value])) {
                            $erp_suppliers->email = $email_configuration[$value];
                        } else {
                            $erp_suppliers->email = '';
                        }

                        if (isset($minimum_price_configuration[$value])) {
                            $erp_suppliers->minimum_price = $minimum_price_configuration[$value];
                        } else {
                            $erp_suppliers->minimum_price = '';
                        }

                        if (isset($minimum_price_free_shipping_configuration[$value])) {
                            $erp_suppliers->minimum_price_free_shipping = $minimum_price_free_shipping_configuration[$value];
                        } else {
                            $erp_suppliers->minimum_price_free_shipping = '';
                        }

                        if (isset($shipping_price[$value])) {
                            $erp_suppliers->shipping_price = $shipping_price[$value];
                        } else {
                            $erp_suppliers->shipping_price = '';
                        }

                        $erp_suppliers->save();
                    }

                    unset($erp_suppliers);
                }
                                
                if (Configuration::get('WIC_ERP_RECALCULATE_BY_SUPPLIER')) {
                    ErpProducts::updateErpProducts($suppliers_update);
                }
            }
        }

        // Global checks when add / update a supply order
        if (Tools::isSubmit('submitAdderp_suppliers') || Tools::isSubmit('submitAdderp_suppliersAndStay')) {
            $this->action = 'save';
            // get supplier ID
            $id_supplier = (int)Tools::getValue('id_supplier', 0);
            if ($id_supplier <= 0 || !Supplier::supplierExists((int)$id_supplier)) {
                $this->errors[] = Tools::displayError($this->l('The selected supplier is not valid.'));
            }

            if (Tools::getValue('delivery') < 0) {
                $this->errors[] = Tools::displayError($this->l('Delivery can not be negative.'));
            }

            if (!count($this->errors)) {
                if (!ErpSuppliers::getSupplierById((int)$id_supplier)) {
                    $erp_suppliers = new ErpSuppliers();
                    $erp_suppliers->id_supplier = (int)$id_supplier;
                    $erp_suppliers->id_lang = (int)Tools::getValue('id_lang');
                    $erp_suppliers->id_employee = (int)$this->context->employee->id;
                    $erp_suppliers->delivery = (int)Tools::getValue('delivery');
                    $erp_suppliers->email = Tools::getValue('email');
                    $erp_suppliers->minimum_price = Tools::getValue('minimum_price');
                    $erp_suppliers->shipping_price = Tools::getValue('shipping_price');
                    $erp_suppliers->minimum_price_free_shipping = Tools::getValue('minimum_price_free_shipping');
                    $erp_suppliers->manual_configuration = (int)Tools::getValue('manual_configuration');
                    $erp_suppliers->vat_exemption = (int)Tools::getValue('vat_exemption');
                    $erp_suppliers->save();
                } else {
                    if (Tools::getValue('id_erp_suppliers')) {
                        $erp_suppliers = new ErpSuppliers((int)Tools::getValue('id_erp_suppliers'));
                        $erp_suppliers->id_employee = (int)$this->context->employee->id;
                        $erp_suppliers->id_lang = (int)Tools::getValue('id_lang');
                        $erp_suppliers->delivery = (int)Tools::getValue('delivery');
                        $erp_suppliers->email = Tools::getValue('email');
                        $erp_suppliers->minimum_price = Tools::getValue('minimum_price');
                        $erp_suppliers->shipping_price = Tools::getValue('shipping_price');
                        $erp_suppliers->minimum_price_free_shipping = Tools::getValue('minimum_price_free_shipping');
                        $erp_suppliers->manual_configuration = (int)Tools::getValue('manual_configuration');
                        $erp_suppliers->vat_exemption = (int)Tools::getValue('vat_exemption');
                        $erp_suppliers->save();
                    } else {
                        $this->errors[] = Tools::displayError($this->l('This supplier already configured in Erp.'));
                    }
                }
                                
                               
                if (Configuration::get('WIC_ERP_RECALCULATE_BY_SUPPLIER') && $id_supplier) {
                    $suppliers_update = array($id_supplier);
                    ErpProducts::updateErpProducts($suppliers_update);
                }
            }
        }

        if (Tools::isSubmit('deleteerp_suppliers')) {
            $this->processDelete();
        }
    }

    public function processDelete()
    {
        if (Validate::isLoadedObject($object = $this->loadObject())) {
            if (!$object->delete()) {
                $this->errors[] = Tools::displayError('An error occurred during deletion.');
            }
        } else {
            $this->errors[] = Tools::displayError('An error occurred during deletion.');
        }
    }
    /**
     * AdminController::renderForm() override
     * @see AdminController::renderForm()
     */
    public function renderForm()
    {
        /* get suppliers list */
        $suppliers = Supplier::getSuppliers();

        $this->fields_form = array(
                'legend' => array(
                    'title' => $this->l('Suppliers information'),
                    'image' => '../img/admin/edit.gif'
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Supplier:'),
                        'name' => 'id_supplier',
                        'required' => true,
                        'options' => array(
                            'query' => $suppliers,
                            'id' => 'id_supplier',
                            'name' => 'name'
                        ),
                    ),
                                        array(
                                                'type' => 'select',
                                                'label' => $this->l('Language'),
                                                'name' => 'id_lang',
                                                'required' => true,
                                                'col' => 4,
                                                'default_value' => (int)$this->context->language->id,
                                                'options' => array(
                                                    'query' => Language::getLanguages(),
                                                    'id' => 'id_lang',
                                                    'name' => 'name',
                                                ),
                                        ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Delay of delivery'),
                        'name' => 'delivery',
                        'size' => 10,
                        'required' => true,
                        'hint' => $this->l('Enter a number of days'),
                        'desc' => $this->l('Thank you to enter the number of days that your provider shall deliver you.'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Email of supplier'),
                        'name' => 'email',
                        'size' => 10,
                        'hint' => $this->l('Enter an email of suppier'),
                        'desc' => $this->l('Leave blank if you don\'t send an email. You can add multiple email separated by comma \',\''),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Minimum amount to purchase'),
                        'name' => 'minimum_price',
                        'size' => 10,
                        'hint' => $this->l('Enter amount without tax'),
                        'desc' => $this->l('Leave blank if you don\'t define amount'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Minimum amount order to free shipping'),
                        'name' => 'minimum_price_free_shipping',
                        'size' => 10,
                        'hint' => $this->l('Enter amount without tax'),
                        'desc' => $this->l('Leave blank if you don\'t define amount'),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Default shipping price'),
                        'name' => 'shipping_price',
                        'size' => 10,
                        'hint' => $this->l('Enter amount without tax'),
                        'desc' => $this->l('Default shipping price if your order have not free shipping. Leave blank if you don\'t define amount'),
                    ),
                    array(
                        'type' => (version_compare(_PS_VERSION_, '1.6', '<')) ? 'radio' : 'switch',
                        'label' => $this->l('VAT Exemption:'),
                        'name' => 'vat_exemption',
                        'required' => false,
                        'class' => 't',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        )
                    ),
                    array(
                        'type' => (version_compare(_PS_VERSION_, '1.6', '<')) ? 'radio' : 'switch',
                        'label' => $this->l('Manual configuration:'),
                        'name' => 'manual_configuration',
                        'required' => false,
                        'class' => 't',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save suppliers'),
                )
            );

        return parent::renderForm();
    }

    public function editSuppliers($delivery, $tr)
    {
        return '<input type="text" size="3" name="updateDelivery['.$tr['id_erp_suppliers'].']" value="'.$delivery.'"/>';
    }

    public function editSuppliersConfiguration($manual_configuration, $tr)
    {
        return '<input type="checkbox" name="updateManualConfiguration['.$tr['id_erp_suppliers'].']" value="1" '.($manual_configuration ? 'checked="checked"' : '').'/>';
    }
    
    public function editSuppliersVatConfiguration($vat_exemption, $tr)
    {
        return '<input type="checkbox" name="updateVatExemption['.$tr['id_erp_suppliers'].']" value="1" '.($vat_exemption ? 'checked="checked"' : '').'/>';
    }

    public function editSuppliersEmailConfiguration($email, $tr)
    {
        return '<input  type="text" size="20" name="updateEmailConfiguration['.$tr['id_erp_suppliers'].']" value="'.$email.'" />';
    }

    public function editSuppliersMinimumPriceConfiguration($minimum_price, $tr)
    {
        return '<div class="input-group col-lg-12"><span class="input-group-addon"> €</span><input  type="text" size="5" name="updateMinimumPriceConfiguration['.$tr['id_erp_suppliers'].']" value="'.$minimum_price.'" /></div>';
    }

    public function editSuppliersMinimumPriceShippingConfiguration($minimum_price_free_shipping, $tr)
    {
        return '<div class="input-group col-lg-12"><span class="input-group-addon"> €</span><input  type="text" size="5" name="updateMinimumPriceShippingConfiguration['.$tr['id_erp_suppliers'].']" value="'.$minimum_price_free_shipping.'" /></div>';
    }

    public function editSuppliersShippingPriceConfiguration($shipping_price, $tr)
    {
        return '<div class="input-group col-lg-12"><span class="input-group-addon"> €</span><input  type="text" size="5" name="updateShippingPriceConfiguration['.$tr['id_erp_suppliers'].']" value="'.$shipping_price.'" /></div>';
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
        unset($this->context->cookie->erp_suppliers);
    }
    
    protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
    	if (version_compare(_PS_VERSION_, '1.7', '<')) {
            return parent::l($string, $class, $addslashes, $htmlentities);
    	} else {
            return Translate::getModuleTranslation('wic_erp', $string, 'AdminErpSuppliersController');
    	}
    }
}
