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

require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpOrder.php';
require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpOrderDetail.php';
require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpOrderHistory.php';
require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpOrderAttachement.php';
require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpOrderState.php';
require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpSuppliers.php';
require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpOrderReceiptHistory.php';
require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpOrderReceiptDlc.php';
require_once _PS_MODULE_DIR_.'wic_erp/classes/HTMLTemplateErpOrderForm.php';
require_once _PS_MODULE_DIR_.'wic_erp/classes/HTMLTemplateErpOrderFormMin.php';
require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpEan13.php';

class AdminErpSupplierOrdersController extends ModuleAdminController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->table = 'erp_order';
        $this->list_id = 'erp_order';
        $this->className = 'ErpOrder';
        $this->identifier = 'id_erp_order';
        $this->lang = false;
        $this->dlcDluoActive = false;
        $this->is_template_list = false;
        $this->multishop_context = Shop::CONTEXT_ALL;
        $this->addRowAction('updatereceipt');
        $this->addRowAction('changestate');
        $this->addRowAction('edit');
        $this->addRowAction('view');
        $this->addRowAction('details');
        $this->addRowAction('delete');
        $this->list_no_link = true;
        $this->bootstrap = true;
        
        $this->bulk_actions = array(
            'delete' => array('text' => $this->l('Delete Supply order'), 'confirm' => $this->l('Delete selected supply orders?'))
        );

        if (Tools::isSubmit('submitReseterp_order')) {
            $this->processResetFilters();
        }

        $this->fields_list = array(
            'reference' => array(
                'title' => $this->l('Reference'),
                'width' => 150,
                'havingFilter' => true
            ),
            'supplier_name' => array(
                'title' => $this->l('Supplier'),
                'width' => 130,
                'filter_key' => 's!name'
            ),
            'nb_products' => array(
                'title' => $this->l('Number of products'),
                'width' => 50,
                'align' => 'center',
                'filter_key' => 'eod!nb_products',
                'callback' => 'getNumberOfProduct',
            ),
            'state' => array(
                'title' => $this->l('Status'),
                'width' => 200,
                'filter_key' => 'stl!name',
                'color' => 'color',
            ),
            /*'date_add' => array(
                'title' => $this->l('Creation'),
                'width' => 150,
                'align' => 'left',
                'type' => 'date',
                'havingFilter' => true,
                'filter_key' => 'a!date_add'
            ),
            'date_upd' => array(
                'title' => $this->l('Last modification'),
                'width' => 150,
                'align' => 'left',
                'type' => 'date',
                'havingFilter' => true,
                'filter_key' => 'a!date_upd'
            ),*/
            'date_delivery_expected' => array(
                'title' => $this->l('Delivery (expected)'),
                'width' => 150,
                'align' => 'left',
                'type' => 'date',
                'havingFilter' => true,
                'filter_key' => 'a!date_delivery_expected'
            ),
            'id_export' => array(
                'title' => $this->l('Export'),
                'width' => 50,
                'callback' => 'printExportIcons',
                'orderby' => false,
                'search' => false
            ),
        );
        
        //We verify if DLC/DLUO addons exists
        if (file_exists(_PS_MODULE_DIR_.'productsdlcdluo/ProductsDlcDluoClass.php')
                && file_exists(_PS_MODULE_DIR_.'productsdlcdluo/OrdersDlcDluo.php')) {
            $this->dlcDluoActive = true;
        }
        
        parent::__construct();
    }

    /**
     * AdminController::init() override
     * @see AdminController::init()
     */
    public function init()
    {
        parent::init();

        if (Tools::isSubmit('adderp_order') ||
                Tools::isSubmit('submitAdderp_order') ||
                (Tools::isSubmit('updateerp_order') && Tools::isSubmit('id_erp_order'))) {
            // override table, lang, className and identifier for the current controller
            $this->table = 'erp_order';
            $this->className = 'ErpOrder';
            $this->identifier = 'id_erp_order';
            $this->lang = false;

            $this->action = 'new';
            $this->display = 'add';

            if (Tools::isSubmit('updateerp_order')) {
                /*if ($this->tabAccess['edit'] === '1') {*/
                    $this->display = 'edit';
                /*} else {
                    $this->errors[] = Tools::displayError('You do not have permission to edit this.');
                }*/
            }
        }

        if (Tools::isSubmit('update_receipt') && Tools::isSubmit('id_erp_order')) {
            // change the display type in order to add specific actions to
            $this->display = 'update_receipt';

            // display correct toolBar
            $this->initToolbar();
        }
    }
    /**
     * AdminController::initContent() override
     * @see AdminController::initContent()
     */
    public function initContent()
    {
        // Manage the add stock form
        if (Tools::isSubmit('changestate')) {
            $this->initChangeStateContent();
        } elseif (Tools::isSubmit('generateErpOrderFormPDF') && Tools::isSubmit('id_erp_order')) {
            $this->processGenerateErpOrderFormPDF();
        } elseif (Tools::isSubmit('generateErpOrderFormPDFMin') && Tools::isSubmit('id_erp_order')) {
            $this->processGenerateErpOrderFormPDF();
        } elseif (Tools::isSubmit('update_receipt') && Tools::isSubmit('id_erp_order')) {
            $this->initUpdateReceiptContent();
        } elseif (Tools::isSubmit('viewsupply_order') && Tools::isSubmit('id_erp_order')) {
            $this->action = 'view';
            $this->display = 'view';
            parent::initContent();
        } elseif (Tools::isSubmit('updateerp_order')) {
            $this->initUpdateErpOrderContent();
        } else {
            parent::initContent();
        }
    }

    /**
     * Assigns default actions in toolbar_btn smarty var, if they are not set.
     * uses override to specifically add, modify or remove items
     * @see AdminSupplier::initToolbar()
     */
    public function initToolbar()
    {
        switch ($this->display) {
            case 'update_receipt':
                // Default cancel button - like old back link
                if (!isset($this->no_back) || $this->no_back == false) {
                    $back = Tools::safeOutput(Tools::getValue('back', ''));
                    if (empty($back)) {
                        $back = self::$currentIndex.'&token='.$this->token;
                    }

                    $this->toolbar_btn['cancel'] = array(
                        'href' => $back,
                        'desc' => $this->l('Cancel')
                    );
                }
                break;
            case 'update_order_state':
                // Default cancel button - like old back link
                if (!isset($this->no_back) || $this->no_back == false) {
                    $back = Tools::safeOutput(Tools::getValue('back', ''));
                    if (empty($back)) {
                        $back = self::$currentIndex.'&token='.$this->token;
                    }

                    $this->toolbar_btn['cancel'] = array(
                            'href' => $back,
                            'desc' => $this->l('Cancel')
                    );
                }
                // no break
            case 'add':
            case 'edit':
                $this->toolbar_btn['save-and-stay'] = array(
                    'href' => '#',
                    'desc' => $this->l('Save and stay')
                );
            default:
                parent::initToolbar();
                break;
        }
    }

    public function initPageHeaderToolbar()
    {
        if ($this->display == 'details') {
            $this->page_header_toolbar_btn['back'] = array(
                'href' => Context::getContext()->link->getAdminLink('AdminErpSupplierOrders'),
                'desc' => $this->l('Back to list', null, null, false),
                'icon' => 'process-icon-back'
            );
        } elseif (empty($this->display)) {
            $this->page_header_toolbar_btn['new_erp_order'] = array(
                'href' => self::$currentIndex.'&adderp_order&token='.$this->token,
                'desc' => $this->l('Add new supply order', null, null, false),
                'icon' => 'process-icon-new'
            );
        }

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            parent::initPageHeaderToolbar();
        }
    }
    
    public function getSupplierList()
    {
        $this->identifier = 'id_erp_suppliers';
        $this->table = 'erp_suppliers';
        $this->list_id = 'erp_suppliers';
        $this->lang = false;
        $this->toolbar_title = $this->l('Supplier list with number products to purchase');
        
        /* gets current lang id */
        $lang_id = (int)$this->context->language->id;


        /* loads history of the given order */
        unset($this->_select,$this->_join, $this->_where, $this->_orderBy, $this->_orderWay, $this->_group, $this->_filterHaving, $this->_filter);
        $this->_select = '
                        a.id_erp_suppliers,
			s.name AS supplier,
                        1 as nb_products,
                        1 as qte_products,
                        1 as order_button
                        ';
        
        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'supplier` s ON a.id_supplier = s.id_supplier';

        /* gets list and forces no limit clause in the request */
        $this->getList($lang_id, 'supplier', 'ASC', 0, 1000, false);
        
        /* creates new fields_list */
        $this->fields_list = array(
            'id_erp_suppliers' => array(
                'title' => $this->l('ID'),
                'width' => 150,
                'align' => 'left',
                'orderby' => false,
                'filter' => false,
                'search' => false,
            ),
            'supplier' => array(
                'title' => $this->l('Name (legend)'),
                'align' => 'center',
                'orderby' => false,
                'filter' => false,
                'search' => false
            ),
            'nb_products' => array(
                'title' => $this->l('Number of products to purchase'),
                'align' => 'center',
                'callback' => 'getSupplierQuantityToPurchase',
                'orderby' => false,
                'filter' => false,
                'search' => false,
            ),
            'qte_products' => array(
                'title' => $this->l('Qte'),
                'align' => 'center',
                'callback' => 'getSupplierTotalQuantityToPurchase',
                'orderby' => false,
                'filter' => false,
                'search' => false,
            ),
            'order_button' => array(
                'title' => $this->l('Create supply Order'),
                'align' => 'center',
                'callback' => 'getSupplierQuantityToPurchaseButton',
                'orderby' => false,
                'filter' => false,
                'search' => false,
            ),
        );
        
        unset($this->toolbar_btn['export-csv-orders']);
        unset($this->toolbar_btn['export-csv-details']);
        unset($this->toolbar_btn['new']);
        unset($this->bulk_actions['delete']);
        
        /* renders list */
        $helper = new HelperList();
        $this->setHelperDisplay($helper);
        $helper->no_link = true;
        $helper->show_toolbar = false;
        $helper->toolbar_scroll = false;
        $helper->pagination = false;
        $helper->shopLinkType = '';
        $helper->identifier = $this->identifier;
        $helper->token = $this->token;
        $helper->table = $this->table;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminErpSupplierOrders', false).'&id_erp_order='.(int)Tools::getValue('id_erp_order').'&viewerp_order';
        
        return $helper->generateList($this->_list, $this->fields_list);
    }
    /**
     * AdminController::renderList() override
     * @see AdminController::renderList()
     */
    public function renderList()
    {
        if (!Tools::isSubmit('submitReseterp_order')) {
            parent::processFilter();
        }
        
        $this->displayInformation($this->l('This interface allows you to manage supply orders.').'<br />');
        $this->displayInformation($this->l('Also, you can create templates that you can use later to generate actual orders.').'<br />');

        $this->tpl_list_vars['filter_status'] = $this->getFilterStatus();
        
        if (Tools::getValue('order_status') || isset($this->context->cookie->erp_supplier_order_state)) {
            if (Tools::getValue('order_status')) {
                $this->tpl_list_vars['tab_selected'] = Tools::getValue('order_status');
                $this->context->cookie->erp_supplier_order_state = Tools::getValue('order_status');
            } else {
                $this->tpl_list_vars['tab_selected'] = $this->context->cookie->erp_supplier_order_state;
            }
        } else {
            $this->context->cookie->erp_supplier_order_state = 'all';
            $this->tpl_list_vars['tab_selected'] = 'all';
        }
        
        // overrides query
        $this->_select = '
			s.name AS supplier,
			stl.name AS state,
			st.delivery_note,
			st.editable,
			st.enclosed,
			st.receipt_state,
			st.pending_receipt,
			st.color AS color,
			a.id_erp_order as id_export,
                        1 as nb_products';

        $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'erp_order_state_lang` stl ON
                        (
                            a.id_erp_order_state = stl.id_erp_order_state
                            AND stl.id_lang = '.(int)$this->context->language->id.'
                        )
                        LEFT JOIN `'._DB_PREFIX_.'erp_order_state` st ON a.id_erp_order_state = st.id_erp_order_state
                        LEFT JOIN `'._DB_PREFIX_.'supplier` s ON a.id_supplier = s.id_supplier
                        '; 
        
        if ($this->context->cookie->erp_supplier_order_state == 'complete') {
            $this->_where .= ' AND stl.`id_erp_order_state` = 5';
        } elseif ($this->context->cookie->erp_supplier_order_state == 'canceled') {
            $this->_where .= ' AND stl.`id_erp_order_state` = 6';
        } elseif ($this->context->cookie->erp_supplier_order_state == 'pending') {
            $this->_where .= ' AND stl.`id_erp_order_state` = 3';
        } elseif ($this->context->cookie->erp_supplier_order_state == 'received_in_part') {
            $this->_where .= ' AND stl.`id_erp_order_state` = 4';
        } elseif ($this->context->cookie->erp_supplier_order_state == 'progress') {
            $this->_where .= ' AND stl.`id_erp_order_state` = 1';
        } elseif ($this->context->cookie->erp_supplier_order_state == 'validated') {
            $this->_where .= ' AND stl.`id_erp_order_state` = 2';
        }

        $this->tpl_list_vars['count_all'] = $this->getAllOrder();
        $this->tpl_list_vars['list_id'] = 'erp_order';
        $this->tpl_list_vars['count_progress'] = $this->getProgressOrder();
        $this->tpl_list_vars['count_validated'] = $this->getValidatedOrder();
        $this->tpl_list_vars['count_pending'] = $this->getPendingOrder();
        $this->tpl_list_vars['count_received_in_part'] = $this->getReceivedInPartOrder();
        $this->tpl_list_vars['count_complete'] = $this->getCompleteOrder();
        $this->tpl_list_vars['count_cancelled'] = $this->getCancelledOrder();

        if ($this->getFilterStatus() != 0) {
            $this->_where .= ' AND st.enclosed != 1';
            self::$currentIndex .= '&filter_status=on';
        }
        $first_list = parent::renderList();

        if (Tools::isSubmit('csv_orders') || Tools::isSubmit('csv_orders_details') || Tools::isSubmit('csv_order_details') || Tools::isSubmit('csv_order_details_min')) {
            if (count($this->_list) > 0) {
                $this->renderCSV();
                die;
            } else {
                $this->displayWarning($this->l('There is nothing to export as a CSV.'));
            }
        }
        
        $first_list .= $this->getSupplierList();

        return $first_list;
    }

    /**
     * @see AdminController::renderView()
     */
    public function renderView()
    {
        $content = $this->getFileList();
        $this->show_toolbar = true;
        $this->toolbar_scroll = false;
        $this->table = 'erp_order_detail';
        $this->identifier = 'id_erp_order_detail';
        $this->className = 'ErpOrderDetail';
        $this->colorOnBackground = false;
        $this->lang = false;
        $this->list_simple_header = true;
        $this->list_no_link = true;

        /* gets the id supplier to view */
        $id_erp_order = (int)Tools::getValue('id_erp_order');

        /* gets global order information */
        $erp_order = new ErpOrder((int)$id_erp_order);

        if (Validate::isLoadedObject($erp_order)) {
            $this->displayInformation($this->l('This interface allows you to display detailed information on your order.').'<br />');

            /* just in case.. */
            unset($this->_select, $this->_join, $this->_where, $this->_orderBy, $this->_orderWay, $this->_group, $this->_filterHaving, $this->_filter);

            /* gets all information on the products ordered */
            $this->_where = 'AND a.`id_erp_order` = '.(int)$id_erp_order;

            /* gets the list ordered by price desc, without limit */
            $this->getList($this->context->language->id, 'reference', 'ASC', 0, false, false);

            /* gets the currency used in this order */
            $currency = new Currency($erp_order->id_currency);

            $this->toolbar_title = sprintf($this->l('Details on Erp order #%s'), $erp_order->reference);

            /* re-defines fields_list */
            $this->fields_list = array(
                'image_tag' => array(
                    'title' => $this->l('Photo'),
                    'align' => 'center',
                    'width' => 70,
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                    'callback' => 'productImage',
                ),
                'supplier_reference' => array(
                    'title' => $this->l('Supplier Reference'),
                    'align' => 'center',
                    'width' => 100,
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                ),
                'reference' => array(
                    'title' => $this->l('Reference'),
                    'align' => 'center',
                    'width' => 120,
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                ),
                'ean13' => array(
                    'title' => $this->l('EAN13'),
                    'align' => 'center',
                    'width' => 100,
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                    'callback' => 'productEANCode',
                ),
                'upc' => array(
                    'title' => $this->l('UPC'),
                    'align' => 'center',
                    'width' => 100,
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                ),
                'in_stock' => array(
                        'title' => $this->l('In Stock'),
                        'align' => 'center',
                        'width' => 50,
                        'orderby' => false,
                        'filter' => false,
                        'search' => false,
                        'callback' => 'productInStock',
                ),
                'name' => array(
                    'title' => $this->l('Name'),
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                ),
                'quantity_ordered' => array(
                    'title' => $this->l('Quantity purchased'),
                    'align' => 'center',
                    'width' => 80,
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                ),
                'quantity_received' => array(
                    'title' => $this->l('Quantity received'),
                    'align' => 'center',
                    'width' => 80,
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                ),
                'unit_price_te' => array(
                    'title' => $this->l('Unit price (tax excl.)'),
                    'align' => 'right',
                    'width' => 80,
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                    'type' => 'price',
                    'currency' => true,
                ),
                'price_te' => array(
                    'title' => $this->l('Price (tax excl.)'),
                    'align' => 'right',
                    'width' => 80,
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                    'type' => 'price',
                    'currency' => true,
                ),
                'discount_rate' => array(
                    'title' => $this->l('Discount rate'),
                    'align' => 'right',
                    'width' => 80,
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                    'suffix' => '%',
                ),
                'discount_value_te' => array(
                    'title' => $this->l('Discount value (tax excl.)'),
                    'align' => 'right',
                    'width' => 80,
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                    'type' => 'price',
                    'currency' => true,
                ),
                'price_with_discount_te' => array(
                    'title' => $this->l('Price with product discount (tax excl.)'),
                    'align' => 'center',
                    'width' => 80,
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                    'type' => 'price',
                    'currency' => true,
                ),
                'tax_rate' => array(
                    'title' => $this->l('Tax rate'),
                    'align' => 'right',
                    'width' => 80,
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                    'suffix' => '%',
                ),
                'tax_value' => array(
                    'title' => $this->l('Tax value'),
                    'align' => 'right',
                    'width' => 80,
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                    'type' => 'price',
                    'currency' => true,
                ),
                'price_ti' => array(
                    'title' => $this->l('Price (tax incl.)'),
                    'align' => 'right',
                    'width' => 80,
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                    'type' => 'price',
                    'currency' => true,
                ),
                'details' => array(
                    'title' => $this->l('Actions'), 
                    'align' => 'right',
                    'orderby' => false,
                    'filter' => false,
                    'search' => false,
                    'callback' => 'detailsButton',
                )
            );
            
            /* some staff before render list */
            foreach ($this->_list as &$item) {
                $item['discount_rate'] = Tools::ps_round($item['discount_rate'], 4);
                $item['tax_rate'] = Tools::ps_round($item['tax_rate'], 4);
                $item['id_currency'] = $currency->id;
                
                $this->setProductImageInformations($item);
                if ($item['image'] != null) {
                    $name = 'product_mini_'.(int)$item['id_product'].(isset($item['id_product_attribute']) ? '_'.(int)$item['id_product_attribute'] : '').'.jpg';
                    // generate image cache, only for back office
                    $item['image_tag'] = ImageManager::thumbnail(_PS_IMG_DIR_.'p/'.$item['image']->getExistingImgPath().'.jpg', $name, 45, 'jpg');
                    if (file_exists(_PS_TMP_IMG_DIR_.$name)) {
                        $item['image_size'] = getimagesize(_PS_TMP_IMG_DIR_.$name);
                    } else {
                        $item['image_size'] = false;
                    }
                }

                $item['in_stock'] = StockAvailable::getQuantityAvailableByProduct((int)$item['id_product'], (int)$item['id_product_attribute'], (int)Context::getContext()->shop->id);
            
                $item['details'] = $item['id_erp_order_detail'];
            }

            /* unsets some buttons */
            unset($this->toolbar_btn['export-csv-orders']);
            unset($this->toolbar_btn['export-csv-details']);
            unset($this->toolbar_btn['new']);
            
            /* renders list */
            $helper = new HelperList();
            $this->setHelperDisplay($helper);
            $helper->actions = array('');
            $helper->show_toolbar = false;
            $helper->toolbar_btn = $this->toolbar_btn;

            $content .= $this->getAddFileForm();
            $content .= $helper->generateList($this->_list, $this->fields_list);

            if (version_compare(_PS_VERSION_, '1.5.5', '<')) {
                $date_add = Tools::displayDate($erp_order->date_add, $this->context->language->id);
                $date_upd = Tools::displayDate($erp_order->date_upd, $this->context->language->id);
                $date_exp = Tools::displayDate($erp_order->date_delivery_expected, $this->context->language->id);
            } else {
                $date_add = Tools::displayDate($erp_order->date_add);
                $date_upd = Tools::displayDate($erp_order->date_upd);
                $date_exp = Tools::displayDate($erp_order->date_delivery_expected);
            }
            
            //We retreive Erp supplier information
            $erpSupplier = new ErpSuppliers((int)$erp_order->id_supplier);
            
            /* display these global order informations */
            $this->tpl_view_vars = array(
                'erp_order_detail_content' => $content,
                'erp_order_reference' => $erp_order->reference,
                'erp_order_supplier_name' => $erp_order->supplier_name,
                'erp_order_creation_date' => $date_add,
                'erp_order_last_update' => $date_upd,
                'erp_order_expected' => $date_exp,
                'erp_order_discount_rate' => Tools::ps_round($erp_order->discount_rate, 2),
                'erp_order_total_te' => Tools::displayPrice($erp_order->total_te, $currency),
                'erp_order_discount_value_te' => Tools::displayPrice($erp_order->discount_value_te, $currency),
                'erp_order_total_with_discount_te' => Tools::displayPrice($erp_order->total_with_discount_te, $currency),
                'erp_order_shipping_cost' => Tools::displayPrice($erp_order->shipping_cost, $currency),
                'erp_order_shipping_tax' => Tools::displayPrice(($erp_order->shipping_cost * (1 + ($erp_order->shipping_tax_rate / 100))) - $erp_order->shipping_cost, $currency),
                'erp_order_shipping_tax_rate' => $erp_order->shipping_tax_rate,
                'erp_order_total_tax' => Tools::displayPrice($erp_order->total_tax, $currency),
                'erp_order_total_ti' => Tools::displayPrice($erp_order->total_ti, $currency),
                'erp_order_currency' => $currency,
                'vat_exemption' => $erpSupplier->vat_exemption,
            );
        }

        return parent::renderView();
    }
    
    public function getFileList()
    {
        $this->identifier = 'id_erp_order_attachement';
        $this->table = 'erp_order_attachement';
        $this->lang = false;
        $this->toolbar_title = $this->l('File on Erp order');
        
        /* gets current lang id */
        $lang_id = (int)$this->context->language->id;
        /* gets supply order id */
        $id_erp_order = (int)Tools::getValue('id_erp_order');

        /* loads history of the given order */
        unset($this->_select, $this->_join, $this->_where, $this->_orderBy, $this->_orderWay, $this->_group, $this->_filterHaving, $this->_filter);
        $this->_where = 'AND a.`id_erp_order` = '.(int)$id_erp_order;
        $this->_orderBy = 'a.`date_add`';
        $this->_orderWay = 'DESC';

        /* gets list and forces no limit clause in the request */
        $this->getList($lang_id, 'date_add', 'DESC', 0, false, false);
        
        /* creates new fields_list */
        $this->fields_list = array(
            'id_erp_order_attachement' => array(
                'title' => $this->l('ID'),
                'width' => 50,
                'align' => 'left',
                'orderby' => false,
                'filter' => false,
                'search' => false,
            ),
            'name' => array(
                'title' => $this->l('Name (legend)'),
                'align' => 'left',
                'orderby' => false,
                'filter' => false,
                'search' => false,
            ),
            'file_name' => array(
                'title' => $this->l('File name'),
                'align' => 'left',
                'callback' => 'displayFileName',
                'orderby' => false,
                'filter' => false,
                'search' => false,
            ),
            'date_add' => array(
                'title' => $this->l('Date add'),
                'width' => 150,
                'align' => 'left',
                'type' => 'datetime',
                'orderby' => false,
                'filter' => false,
                'search' => false,
            ),
        );
        
        unset($this->toolbar_btn['export-csv-orders']);
        unset($this->toolbar_btn['export-csv-details']);
        unset($this->toolbar_btn['new']);
        $back_button = $this->toolbar_btn['back'];
        unset($this->toolbar_btn['back']);
        
        /* renders list */
        $helper = new HelperList();
        $this->setHelperDisplay($helper);
        $helper->actions = array('delete','');
        $helper->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'icon' => 'icon-trash',
                'confirm' => $this->l('Delete selected object?')
            )
        );
        $helper->no_link = true;
        $helper->show_toolbar = false;
        $helper->toolbar_scroll = false;
        $helper->shopLinkType = '';
        $helper->identifier = $this->identifier;
        $helper->token = $this->token;
        $helper->table = $this->table;
        $helper->currentIndex = $this->context->link->getAdminLink('AdminErpSupplierOrders', false).'&id_erp_order='.(int)Tools::getValue('id_erp_order').'&viewerp_order';
        $this->toolbar_btn['back'] = $back_button;
        return $helper->generateList($this->_list, $this->fields_list);
    }

    public function getAddFileForm()
    {
        /* Get default language */
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        
        $helper = new HelperForm();
        $form_fields = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Add file for this order'),
                    'icon' => 'icon-AdminStock',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'name' => 'WIC_ERP_FILE_NAME',
                        'class' => 'form-control input-lg',
                        'label' => $this->l('Name of file'),
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'id_erp_order',
                    ),
                    array(
                        'type' => 'file',
                        'name' => 'file',
                        'class' => 'form-control input-lg',
                        'label' => $this->l('Upload file'),
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default',
                )
            )
        );

        $default_field_value = array(
            'file' => null,
            'WIC_ERP_FILE_NAME' => '',
            'id_erp_order' => (int)Tools::getValue('id_erp_order'),
        );

        $helper->show_toolbar = false;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->table = 'erp_file_order';
        $helper->identifier = 'erpfileform';
        $helper->submit_action = 'submitWic_erpfile';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminErpSupplierOrders', false).'&id_erp_order='.(int)Tools::getValue('id_erp_order').'&viewerp_order';
        $helper->token = $this->token;

        $helper->tpl_vars = array(
            'fields_value' => $default_field_value, /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'allowEmployeeFormLang' => $default_lang,
        );

        return $helper->generateForm(array($form_fields));
    }
    
    /**
     * AdminController::getList() override
     * @see AdminController::getList()
     */
    public function getList($id_lang, $order_by = null, $order_way = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        if (Tools::isSubmit('csv_orders') || Tools::isSubmit('csv_orders_details') || Tools::isSubmit('csv_order_details') || Tools::isSubmit('csv_order_details_min')) {
            $limit = false;
        }

            // adds export csv buttons
            $this->toolbar_btn['export-csv-orders'] = array(
                'short' => 'Export Orders',
                'href' => $this->context->link->getAdminLink('AdminErpSupplierOrders').'&csv_orders',
                'desc' => $this->l('Export Orders (CSV)'),
                'class' => 'process-icon-export-csv-orders process-icon-export',
            );

            $this->toolbar_btn['export-csv-details'] = array(
                'short' => 'Export Orders Details',
                'href' => $this->context->link->getAdminLink('AdminErpSupplierOrders').'&csv_orders_details',
                'desc' => $this->l('Export Orders Details (CSV)'),
                'class' => 'process-icon-export-csv-orders process-icon-export',
            );

        unset($this->toolbar_btn['new']);
        if (isset($this->tabAccess['add']) && $this->tabAccess['add'] === '1') {
            $this->toolbar_btn['new'] = array(
                    'href' => self::$currentIndex.'&add'.$this->table.'&token='.$this->token,
                    'desc' => $this->l('Add new')
                );
        }

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

        // adds colors depending on the receipt state
        if ($order_by == 'a.reference') {
            $nb_items = count($this->_list);
            for ($i = 0; $i < $nb_items; ++$i) {
                $item = &$this->_list[$i];
                if ($item['quantity_received'] == $item['quantity_ordered']) {
                    $item['color'] = '#00bb35';
                } elseif ($item['quantity_received'] > $item['quantity_ordered']) {
                    $item['color'] = '#fb0008';
                }

                $item['in_stock'] = StockAvailable::getQuantityAvailableByProduct((int)$item['id_product'], $item['id_product_attribute'], Context::getContext()->shop->id);
            }
        }

        // actions filters on supply orders list
        if ($this->table == 'erp_order') {
            $nb_items = count($this->_list);

            for ($i = 0; $i < $nb_items; $i++) {
                // if the current state doesn't allow order edit, skip the edit action
                if ($this->_list[$i]['editable'] == 0) {
                    $this->addRowActionSkipList('edit', $this->_list[$i]['id_erp_order']);
                }
                if ($this->_list[$i]['enclosed'] == 1 && $this->_list[$i]['receipt_state'] == 0) {
                    $this->addRowActionSkipList('changestate', $this->_list[$i]['id_erp_order']);
                }
                if (1 != $this->_list[$i]['pending_receipt']) {
                    $this->addRowActionSkipList('updatereceipt', $this->_list[$i]['id_erp_order']);
                }
            }
        }
    }

    /**
     * Exports CSV
     */
    protected function renderCSV()
    {
        // exports orders
        if (Tools::isSubmit('csv_orders')) {
            $ids = array();
            foreach ($this->_list as $entry) {
                $ids[] = $entry['id_erp_order'];
            }

            if (count($ids) <= 0) {
                return;
            }

            $id_lang = Context::getContext()->language->id;
            $orders = new Collection('ErpOrder', $id_lang);
            $orders->where('id_erp_order', 'in', $ids);
            $orders->getAll();
            $csv = new CSV($orders, $this->l('erp_orders'));
            $csv->export();
        } elseif (Tools::isSubmit('csv_orders_details')) {
            // exports details for all orders
            // header
            header('Content-type: text/csv');
            header('Content-Type: application/force-download; charset=UTF-8');
            header('Cache-Control: no-store, no-cache');
            header('Content-disposition: attachment; filename="'.$this->l('erp_orders_details').'.csv"');

            // echoes details
            $ids = array();
            foreach ($this->_list as $entry) {
                $ids[] = $entry['id_erp_order'];
            }

            if (count($ids) <= 0) {
                return;
            }

            // for each supply order
            $keys = array('id_product', 'id_product_attribute', 'reference', 'supplier_reference', 'ean13', 'upc', 'name',
                            'unit_price_te', 'quantity_ordered', 'quantity_received', 'price_te', 'discount_rate', 'discount_value_te',
                            'price_with_discount_te', 'tax_rate', 'tax_value', 'price_ti', 'tax_value_with_order_discount',
                            'price_with_order_discount_te', 'id_supply_order');
            echo sprintf("%s\n", implode(';', array_map(array('CSVCore', 'wrap'), $keys)));

            // overrides keys (in order to add FORMAT calls)
            $keys = array('sod.id_product', 'sod.id_product_attribute', 'sod.reference', 'sod.supplier_reference', 'sod.ean13',
                            'sod.upc', 'sod.name',
                            'FORMAT(sod.unit_price_te, 2)', 'sod.quantity_ordered', 'sod.quantity_received', 'FORMAT(sod.price_te, 2)',
                            'FORMAT(sod.discount_rate, 2)', 'FORMAT(sod.discount_value_te, 2)',
                            'FORMAT(sod.price_with_discount_te, 2)', 'FORMAT(sod.tax_rate, 2)', 'FORMAT(sod.tax_value, 2)',
                            'FORMAT(sod.price_ti, 2)', 'FORMAT(sod.tax_value_with_order_discount, 2)',
                            'FORMAT(sod.price_with_order_discount_te, 2)', 'sod.id_supply_order');

            foreach ($ids as $id) {
                $query = new DbQuery();
                $query->select(implode(', ', $keys));
                $query->from('erp_order_detail', 'sod');
                $query->leftJoin('erp_order', 'so', 'so.id_erp_order = sod.id_erp_order');
                $query->where('sod.id_erp_order = '.(int)$id);
                $query->orderBy('sod.reference DESC');
                $resource = Db::getInstance()->query($query);
                // gets details
                while ($row = Db::getInstance()->nextRow($resource)) {
                    echo sprintf("%s\n", implode(';', array_map(array('CSVCore', 'wrap'), $row)));
                }
            }
        } elseif (Tools::isSubmit('csv_order_details') && Tools::getValue('id_erp_order')) {
            // exports details for the given order
            $erp_order = new ErpOrder((int)Tools::getValue('id_erp_order'));
            if (Validate::isLoadedObject($erp_order)) {
                $details = $erp_order->getEntriesCollection();
                $details->getAll();
                $csv = new CSV($details, $this->l('erp_order').'_'.$erp_order->reference.'_details');
                $csv->export();
            }
        } elseif (Tools::isSubmit('csv_order_details_min') && Tools::getValue('id_erp_order')) {
            // exports details for the given order
            $erp_order = new ErpOrder((int)Tools::getValue('id_erp_order'));
            if (Validate::isLoadedObject($erp_order)) {
                $erp_order_details = $erp_order->getEntriesCollectionMin();
                $erp_order_details->getAll();
                
                foreach ($erp_order_details as $erp_order_detail) {
                    $product = new Product((int)$erp_order_detail->id_product, false, (int)$this->context->language->id);

                    if (Validate::isLoadedObject($product)) {
                        $name = $product->name;
                    } else {
                        $name = '';
                    }

                    $sum = $erp_order_detail->price_with_order_discount_te;
                    $qty = $erp_order_detail->quantity_ordered;
                    //We select all combinations
                    $sql = 'SELECT
										`id_product_attribute`,
										`price_with_order_discount_te`,
										`quantity_ordered`
									FROM 
										`'._DB_PREFIX_.'erp_order_detail`
									WHERE
										`id_product` = '.(int)$erp_order_detail->id_product.'
									AND
										`id_product_attribute` != 0
									AND
										`id_product_attribute` != '.(int)$erp_order_detail->id_product_attribute.'
									AND
										`id_erp_order` = '.(int)$erp_order_detail->id_erp_order.'
									';

                    $attributes = Db::getInstance()->executeS($sql);

                    if ($attributes && count($attributes)) {
                        foreach ($attributes as $attribute) {
                            $combination = new Combination($attribute['id_product_attribute']);

                            if (Validate::isLoadedObject($combination)) {
                                $combination_name = '';
                                foreach ($combination->getAttributesName((int)$this->context->language->id) as $attribute_name) {
                                    $combination_name .= $attribute_name['name'].'|';
                                }

                                $name .= ' '.Tools::substr($combination_name, 0, -1).',';
                            }

                            $sum += $attribute['price_with_order_discount_te'];
                            $qty += $attribute['quantity_ordered'];
                        }
                    }

                    $erp_order_detail->reference = $product->reference;
                    $erp_order_detail->name = Tools::substr($name, 0, -1);
                    $erp_order_detail->price_with_order_discount_te = $sum;
                    $erp_order_detail->quantity_ordered = $qty;

                    unset($erp_order_detail->id_product,
                        $erp_order_detail->id_product_attribute,
                        $erp_order_detail->ean13,
                        $erp_order_detail->upc,
                        $erp_order_detail->id_erp_order,
                        $erp_order_detail->id_currency,
                        $erp_order_detail->exchange_rate,
                        $erp_order_detail->unit_price_te,
                        $erp_order_detail->quantity_received,
                        $erp_order_detail->price_te,
                        $erp_order_detail->discount_rate,
                        $erp_order_detail->discount_value_te,
                        $erp_order_detail->price_with_discount_te,
                        $erp_order_detail->tax_rate,
                        $erp_order_detail->tax_value,
                        $erp_order_detail->price_ti,
                        $erp_order_detail->tax_value_with_order_discount,
                        $erp_order_detail->id,
                        $erp_order_detail->id_shop_list,
                        $erp_order_detail->force_id
                        );
                }

                $csv = new CSV($erp_order_details, $this->l('erp_order').'_'.$erp_order->reference.'_details');
                $csv->export();
            }
        }
    }

    /**
     * Inits the content of 'update_receipt' action
     * Called in initContent()
     * @see AdminSuppliersOrders::initContent()
     */
    public function initUpdateReceiptContent()
    {
        $content = '';
        $id_erp_order = (int)Tools::getValue('id_erp_order', null);

        // if there is no order to fetch
        if (null == $id_erp_order) {
            return parent::initContent();
        }

        $erp_order = new ErpOrder($id_erp_order);

        // if it's not a valid order
        if (!Validate::isLoadedObject($erp_order)) {
            return parent::initContent();
        }

        $this->initPageHeaderToolbar();
        
        $this->displayInformation($this->l('This interface allows you to update the quantities of this ongoing order.').'<br />');
        $this->displayInformation($this->l('Be careful : once you update, you cannot go back unless you add new negative stock movements.').'<br />');
        $this->displayInformation($this->l('Please not that a green line means that you received what you expected, and a red line means that you received more than expected.').'<br />');

        
        // re-defines fields_list
        $this->fields_list = array(
            'supplier_reference' => array(
                'title' => $this->l('Supplier Reference'),
                'orderby' => false,
                'filter' => false,
                'search' => false,
            ),
            'reference' => array(
                'title' => $this->l('Reference'),
                'orderby' => false,
                'filter' => false,
                'search' => false,
            ),
            'ean13' => array(
                'title' => $this->l('EAN13'),
                'orderby' => false,
                'filter' => false,
                'search' => false,
            ),
            'upc' => array(
                'title' => $this->l('UPC'),
                'orderby' => false,
                'filter' => false,
                'search' => false,
            ),
            'name' => array(
                'title' => $this->l('Name'),
                'orderby' => false,
                'filter' => false,
                'search' => false,
            ),
            'in_stock' => array(
                'title' => $this->l('In Stock'),
                'align' => 'center',
                'width' => 50,
                'orderby' => false,
                'filter' => false,
                'search' => false,
                'callback' => 'productInStock',
            ),
            'quantity_received_today' => array(
                'title' => $this->l('Quantity received today?'),
                'type' => 'editable',
                'orderby' => false,
                'filter' => false,
                'search' => false,
                'hint' => $this->l('Enter here the quantity you received today'),
            ),
            'quantity_received' => array(
                'title' => $this->l('Quantity received'),
                'orderby' => false,
                'filter' => false,
                'search' => false,
                'badge_danger' => true,
                'badge_success' => true,
                'hint' => 'Note that you can see details on the receptions - per products',
            ),
            'quantity_ordered' => array(
                'title' => $this->l('Quantity expected'),
                'orderby' => false,
                'filter' => false,
                'search' => false,
            ),
            'quantity_left' => array(
                'title' => $this->l('Quantity left'),
                'orderby' => false,
                'filter' => false,
                'search' => false,
                'hint' => $this->l('This is the quantity left to receive'),
                'callback' => 'productLeftQuantity'
            ),
            'location' => array(
                'title' => $this->l('Warehouse location'),
                'orderby' => false,
                'filter' => false,
                'search' => false,
                'hint' => $this->l('Warehouse Location'),
            )
        );

        // attributes override
        unset($this->_select, $this->_join, $this->_where, $this->_orderBy, $this->_orderWay, $this->_group, $this->_filterHaving, $this->_filter);
        $this->table = 'erp_order_detail';
        $this->identifier = 'id_erp_order_detail';
        $this->className = 'ErpOrderDetail';
        $this->list_simple_header = false;
        $this->list_no_link = true;
        $this->colorOnBackground = true;
        $this->row_hover = false;
        $this->bulk_actions = array('Update' => array('text' => $this->l('Update selected'), 'confirm' => $this->l('Update selected items?')));
        $this->addRowAction('details');

        // sets toolbar title with order reference
        $this->toolbar_title = sprintf($this->l('Receipt of products for Erp order #%s'), $erp_order->reference);
        $this->lang = false;
        $lang_id = (int)$this->context->language->id; //employee lang

        // gets values corresponding to fields_list
        $this->_select = '
			a.id_erp_order_detail as id,
			a.quantity_received as quantity_received,
			a.quantity_ordered as quantity_ordered,
			IF (a.quantity_ordered < a.quantity_received, 0, a.quantity_ordered - a.quantity_received) as quantity_left,
			IF (a.quantity_ordered < a.quantity_received, 0, a.quantity_ordered - a.quantity_received) as quantity_received_today,
			IF (a.quantity_ordered = a.quantity_received, 1, 0) badge_success,
			IF (a.quantity_ordered > a.quantity_received, 1, 0) badge_danger';

        $this->_where = 'AND a.`id_erp_order` = '.(int)$id_erp_order;

        $this->_group = 'GROUP BY a.id_erp_order_detail';

        // gets the list ordered by price desc, without limit
        $this->getList($lang_id, 'a.reference', 'DESC', 0, false, false);

        // defines action for POST
        $action = '&id_erp_order='.(int)$id_erp_order.'&update_receipt=1';

        // unsets some buttons
        unset($this->toolbar_btn['export-csv-orders']);
        unset($this->toolbar_btn['export-csv-details']);
        unset($this->toolbar_btn['new']);

        $this->toolbar_btn['back'] = array(
            'desc' => $this->l('Back'),
            'href' => $this->context->link->getAdminLink('AdminErpSupplierOrders')
        );
        
        // renders list
        $helper = new HelperList();
        $this->setHelperDisplay($helper);
        $helper->actions = array('details');
        $helper->force_show_bulk_actions = true;
        $helper->override_folder = 'erp_orders_receipt_history/';
        $helper->toolbar_btn = $this->toolbar_btn;
        $helper->list_id = 'erp_order_detail';
        $helper->ajax_params = array(
            'display_product_history' => 1
        );
        $helper->currentIndex = self::$currentIndex.$action;
        // display these global order informations

        /* We retrieve Warehouse */
        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
            $warehouses = Warehouse::getWarehouses();
            $helper->tpl_vars = array(
                        'warehouses' => $warehouses,
                        'dlcDluoActive' => $this->dlcDluoActive,
                    );
        }
        
        foreach ($this->_list as &$item) {
            $warehouses = Warehouse::getProductWarehouseList((int)$item['id_product'], (int)$item['id_product_attribute'], (int)Context::getContext()->shop->id);

            if (count($warehouses)) {
                foreach ($warehouses as $warehouse) {
                    $item['location'][] = array('wh_name' => $warehouse['name'], 'wh_location' => WarehouseProductLocation::getProductLocation((int)$item['id_product'], (int)$item['id_product_attribute'], (int)$warehouse['id_warehouse']));
                    $item['quantity_expected_by_warehouse'][] = array('wh_id' => (int)$warehouse['id_warehouse'], 'wh_name' => $warehouse['name'], 'wh_quantity_expected' => Db::getInstance()->getValue('SELECT `quantity` FROM '._DB_PREFIX_.'erp_order_detail_by_warehouse WHERE `id_erp_order_detail` = '.$item['id_erp_order_detail'].' AND `id_warehouse` = '.(int)$warehouse['id_warehouse']));
                }
            }
        }
        /* generates content form */
        $content .= $this->getSupplyProductForm();
        
        /* generate content list */
        $content .= $helper->generateList($this->_list, $this->fields_list);
                
        // assigns var
        $this->context->smarty->assign(array(
            'content' => $content,
            'show_page_header_toolbar' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? $this->show_page_header_toolbar : '',
            'page_header_toolbar_title' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? $this->page_header_toolbar_title : '',
            'page_header_toolbar_btn' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? $this->page_header_toolbar_btn : '',
        ));
    }

    public function postProcessUploadFile()
    {
        if (Tools::getValue('WIC_ERP_FILE_NAME') && isset($_FILES['file'])) {
            if (isset($_FILES['file']) && is_uploaded_file($_FILES['file']['tmp_name'])) {
                if ($_FILES['file']['size'] > (Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024 * 1024)) {
                    $this->errors[] = sprintf(
                        $this->l('The file is too large. Maximum size allowed is: %1$d kB. The file you are trying to upload is %2$d kB.'),
                        (Configuration::get('PS_ATTACHMENT_MAXIMUM_SIZE') * 1024),
                        number_format(($_FILES['file']['size'] / 1024), 2, '.', '')
                    );
                } else {
                    if (file_exists(_PS_MODULE_DIR_.'wic_erp/upload/'.$_FILES['file']['name'])) {
                        $this->errors[] = $this->l('This file already exists.');
                    }
                    if (!move_uploaded_file($_FILES['file']['tmp_name'], _PS_MODULE_DIR_.'wic_erp/upload/'.$_FILES['file']['name'])) {
                        $this->errors[] = $this->l('Failed to copy the file.');
                    }
                    @unlink($_FILES['file']['tmp_name']);
                }
            } elseif (array_key_exists('file', $_FILES) && (int)$_FILES['file']['error'] === 1) {
                $max_upload = (int)ini_get('upload_max_filesize');
                $max_post = (int)ini_get('post_max_size');
                $upload_mb = min($max_upload, $max_post);
                $this->errors[] = sprintf(
                    $this->l('The file %1$s exceeds the size allowed by the server. The limit is set to %2$d MB.'),
                    '<b>'.$_FILES['file']['name'].'</b> ',
                    '<b>'.$upload_mb.'</b>'
                );
            }
        } else {
            $this->errors[] = Tools::displayError($this->l('All fields are required.'));
        }

        /* We add file in database wic_erp_order_attachement */
        if (!count($this->errors)) {
            $ids = array();
            $ids[] = array('id_erp_order' => (int)Tools::getValue('id_erp_order'), 'file_name' => pSQL($_FILES['file']['name']), 'name' => pSQL(Tools::getValue('WIC_ERP_FILE_NAME')), 'date_add' => date('Y-m-d H:i:s'));

            if (!empty($ids)) {
                $result = Db::getInstance()->insert('erp_order_attachement', $ids);
            }

            return true;
        }

        return false;
    }

    public function getSupplyProductForm()
    {
        /* Get default language */
        $default_lang = (int)Configuration::get('PS_LANG_DEFAULT');
        
        $helper = new HelperForm();
        $manual_quantity_update = Configuration::get('WIC_ERP_ALLOW_MQU');
        $form_fields = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Add product'),
                    'icon' => 'icon-AdminStock',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'name' => 'WIC_ERP_PRODUCT_REFERENCE',
                        'class' => 'form-control input-lg',
                        'label' => $this->l('Reference / EAN / UPC'),
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'class' => 'btn btn-default',
                )
            )
        );

        // Manual quantity update only if supervisor or if allowed in confi
        if ($manual_quantity_update) {
            $form_fields['form']['input'][] = array(
                'type' => 'text',
                'name' => 'WIC_ERP_PRODUCT_QUANTITY',
                'class' => 'form-control input-lg',
                'label' => $this->l('Quantity')
            );
        }
        
        $default_field_value = array(
            'WIC_ERP_PRODUCT_REFERENCE' => null,
            'WIC_ERP_PRODUCT_QUANTITY' => 1,
        );

        $helper->show_toolbar = false;
        $helper->default_form_language = $default_lang;
        $helper->allow_employee_form_lang = $default_lang;
        $helper->table = 'erp_scan_product';
        $helper->identifier = 'erpproductform';
        $helper->submit_action = 'submitWic_erpproduct';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminErpSupplierOrders', false);
        $helper->token = Tools::getAdminTokenLite('AdminErpSupplierOrders');

        $helper->tpl_vars = array(
            'fields_value' => $default_field_value, /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($form_fields));
    }
    
    /**
     * Init the content of change state action
     */
    public function initChangeStateContent()
    {
        $id_erp_order = (int)Tools::getValue('id_erp_order', 0);

        if ($id_erp_order <= 0) {
            $this->errors[] = Tools::displayError($this->l('The specified Erp order is not valid'));
            return parent::initContent();
        }

        $erp_order = new ErpOrder($id_erp_order);
        $erp_order_state = new ErpOrderState($erp_order->id_erp_order_state);

        if (!Validate::isLoadedObject($erp_order) || !Validate::isLoadedObject($erp_order_state)) {
            $this->errors[] = Tools::displayError($this->l('The specified supply order is not valid'));
            return parent::initContent();
        }

        // change the display type in order to add specific actions to
        $this->display = 'update_order_state';
        // overrides parent::initContent();
        $this->initToolbar();
        $this->initPageHeaderToolbar();

        // given the current state, loads available states
        $states = ErpOrderState::getErpOrderStates($erp_order->id_erp_order_state);
        
        // gets the state that are not allowed
        $allowed_states = array();
        foreach ($states as &$state) {
            $allowed_states[] = $state['id_erp_order_state'];
            $state['allowed'] = 1;
        }
        $not_allowed_states = ErpOrderState::getStates($allowed_states);

        // generates the final list of states
        $index = count($allowed_states);
        foreach ($not_allowed_states as &$not_allowed_state) {
            $not_allowed_state['allowed'] = 0;
            $states[$index] = $not_allowed_state;
            ++$index;
        }

        // loads languages
        $this->getlanguages();

        // defines the fields of the form to display
        $this->fields_form[0]['form'] = array(
            'legend' => array(
                'title' => $this->l('Erp Order Status'),
                'icon' => 'icon-pencil'
            ),
            'input' => array(),
            'submit' => array(
                'title' => $this->l('Save'),
                'class' => 'btn btn-default'
            )
        );

        $this->displayInformation($this->l('Be careful when changing status. Some of them cannot be changed afterwards (Canceled, for instance).'));

        // sets up the helper
        $helper = new HelperForm();
        $helper->submit_action = 'submitChangestate';
        $helper->currentIndex = self::$currentIndex;
        $helper->toolbar_btn = $this->toolbar_btn;
        $helper->toolbar_scroll = false;
        $helper->token = $this->token;
        $helper->id = null; // no display standard hidden field in the form
        $helper->languages = $this->_languages;
        $helper->default_form_language = $this->default_form_language;
        $helper->allow_employee_form_lang = $this->allow_employee_form_lang;
        $helper->title = sprintf($this->l('Stock: Change Erp order status #%s'), $erp_order->reference);

        $helper->override_folder = 'erp_orders_change_state/';

        // assigns our content
        $helper->tpl_vars['show_change_state_form'] = true;
        $helper->tpl_vars['erp_order_state'] = $erp_order_state;
        $helper->tpl_vars['erp_order'] = $erp_order;
        $helper->tpl_vars['erp_order_states'] = $states;

        // generates the form to display
        $content = $helper->generateForm($this->fields_form);

        $this->context->smarty->assign(array(
            'content' => $content,
            'url_post' => self::$currentIndex.'&token='.$this->token,
            'show_page_header_toolbar' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? $this->show_page_header_toolbar : '',
            'page_header_toolbar_title' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? $this->page_header_toolbar_title : '',
            'page_header_toolbar_btn' => (version_compare(_PS_VERSION_, '1.6', '>=')) ? $this->page_header_toolbar_btn : ''
        ));
    }

    /**
     * Display receipt action link
     * @param string $token the token to add to the link
     * @param int $id the identifier to add to the link
     * @return string
     */
    public function displayChangestateLink($token = null, $id)
    {
        if (!array_key_exists('State', self::$cache_lang)) {
            self::$cache_lang['State'] = $this->l('Change state');
        }

        $this->context->smarty->assign(array(
            'href' => self::$currentIndex.
                '&'.$this->identifier.'='.$id.
                '&changestate&token='.($token != null ? $token : $this->token),
            'action' => self::$cache_lang['State'],
        ));

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            return $this->fetchTemplate('/views/templates/admin/erp/helpers/list/', 'list_action_erp_order_change_state16');
        } else {
            return $this->fetchTemplate('/views/templates/admin/erp/helpers/list/', 'list_action_erp_order_change_state');
        }
    }
    
    public function displayEditLink($token = null, $id)
    {
        if (!array_key_exists('State2', self::$cache_lang)) {
            self::$cache_lang['State2'] = $this->l('Edit');
        }

        $this->context->smarty->assign(array(
                'href' => self::$currentIndex.
                '&'.$this->identifier.'='.$id.
                '&updateerp_order&token='.($token != null ? $token : $this->token),
                'action' => self::$cache_lang['State2'],
        ));

        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            return $this->fetchTemplate('/views/templates/admin/erp/helpers/list/', 'list_action_edit');
        } else {
            return $this->fetchTemplate('/views/templates/admin/erp/helpers/list/', 'list_action_edit16');
        }
    }

    public function displayViewLink($token = null, $id )
    {
        if (!array_key_exists('State3', self::$cache_lang)) {
            self::$cache_lang['State3'] = $this->l('View');
        }

        $this->context->smarty->assign(array(
                'href' => self::$currentIndex.
                '&'.$this->identifier.'='.$id.
                '&viewerp_order&token='.($token != null ? $token : $this->token),
                'action' => self::$cache_lang['State3'],
        ));

        if (version_compare(_PS_VERSION_, '1.6', '<')) {
            return $this->fetchTemplate('/views/templates/admin/erp/helpers/list/', 'list_action_view');
        } else {
            return $this->fetchTemplate('/views/templates/admin/erp/helpers/list/', 'list_action_view16');
        }
    }

    /**
     * AdminController::renderForm() override
     * @see AdminController::renderForm()
     */
    public function renderForm()
    {
        if (Tools::isSubmit('adderp_order') ||
            Tools::isSubmit('updateerp_order') ||
            Tools::isSubmit('submitAdderp_order') ||
            Tools::isSubmit('submitUpdateerp_order')) {
            
            if (Tools::isSubmit('adderp_order') ||    Tools::isSubmit('submitAdderp_order')) {
                $this->toolbar_title = $this->l('Stock: Create new supply order');
            }

            if (Tools::isSubmit('updateerp_order') || Tools::isSubmit('submitUpdateerp_order')) {
                $this->toolbar_title = $this->l('Stock: Manage supply order');
            }

            //get currencies list
            $currencies = Currency::getCurrencies();
            $id_default_currency = Configuration::get('PS_CURRENCY_DEFAULT');
            $default_currency = Currency::getCurrency($id_default_currency);
            if ($default_currency) {
                $currencies = array_merge(array($default_currency, '-'), $currencies);
            }

            //get languages list
            $languages = Language::getLanguages(true);
            $id_default_lang = Configuration::get('PS_LANG_DEFAULT');
            $default_lang = Language::getLanguage($id_default_lang);
            if ($default_lang) {
                $languages = array_merge(array($default_lang, '-'), $languages);
            }

            //get suppliers list
            $suppliers = Supplier::getSuppliers();
            if (!count($this->errors)) {
                $this->fields_form = array(
                    'legend' => array(
                        'title' => $this->l('Order information'),
                        'image' => '../img/admin/edit.gif'
                    ),
                    'input' => array(
                        array(
                            'type' => 'text',
                            'label' => $this->l('Reference:'),
                            'name' => 'reference',
                            'size' => 50,
                            'col' => '3',
                            'required' => true,
                            'desc' => $this->l('This is the reference for your order.'),
                        ),
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
                            'desc' => $this->l('Select the supplier from whom you are buying products.'),
                            'hint' => $this->l('Be careful! When changing this field, all products already added to the order will be removed.')
                        ),
                        array(
                            'type' => 'select',
                            'label' => $this->l('Currency:'),
                            'name' => 'id_currency',
                            'required' => true,
                            'options' => array(
                                'query' => $currencies,
                                'id' => 'id_currency',
                                'name' => 'name'
                            ),
                            'desc' => $this->l('The currency of the order.'),
                            'hint' => $this->l('Be careful! When changing this field, all products already added to the order will be removed.')
                        ),
                        array(
                            'type' => 'text',
                            'label' => $this->l('Global discount rate (%):'),
                            'name' => 'discount_rate',
                            'size' => 50,
                            'col' => 3,
                            'required' => true,
                            'desc' => $this->l('This is the global discount rate in percent for the order.'),
                        ),
                        array(
                            'type' => 'date',
                            'label' => $this->l('Expected delivery date:'),
                            'name' => 'date_delivery_expected',
                            'required' => true,
                            'desc' => $this->l('This is the expected delivery date for this order.'),
                        ),
                        array(
                                'type' => 'text',
                                'prefix' => '<i class="icon-euro"></i>',
                                'label' => $this->l('Shipping cost:'),
                                'name' => 'shipping_cost',
                                'col' => '3',
                                'desc' => $this->l('This is the shipping cost without tax.'),
                        ),
                        array(
                                'type' => 'text',
                                'prefix' => '%',
                                'label' => $this->l('Shipping tax rate:'),
                                'name' => 'shipping_tax_rate',
                                'required' => false,
                                'col' => '3',
                                'desc' => $this->l('Enter percent integer. For example 20 if you set 20%.')
                        ),
                    ),
                    'submit' => array(
                        'title' => $this->l('Save order'),
                    ),
                    'buttons' => array(
                        'save-and-stay' => array(
                            'title' => $this->l('Save order and stay'),
                            'name' => 'submitAdderp_orderAndStay',
                            'type' => 'submit',
                            'class' => 'btn btn-default pull-right',
                            'icon' => 'process-icon-save'
                        ),
                        'save-and-import' => array(
                            'title' => $this->l('Save empty order and import'),
                            'name' => 'submitAdderp_orderAndImport',
                            'type' => 'submit',
                            'id' => 'btn-import',
                            'class' => 'btn btn-default pull-left',
                            'icon' => 'process-icon-save'
                        ),
                        'save-and-empty' => array(
                            'title' => $this->l('Save empty order'),
                            'name' => 'submitAdderp_orderEmpty',
                            'type' => 'submit',
                            'id' => 'btn-empty',
                            'class' => 'btn btn-default pull-left',
                            'icon' => 'process-icon-save'
                        ),
                    )
                );

                if (!Tools::getValue('id_erp_order')) {
                    $this->fields_value = array('reference' => date('Ymd-His'));
                }
                
                if (Tools::getValue('my_supplier')) {
                    //We retrieve supplier to add date
                    $erpSupplier = new ErpSuppliers((int)Tools::getValue('my_supplier'));
                    
                    if (Validate::isLoadedObject($erpSupplier)) {
                        $this->fields_value = array('id_supplier' =>Tools::getValue('my_supplier'),'reference' => date('Ymd-His'), 'date_delivery_expected' => date('Y-m-d', strtotime('+'.$erpSupplier->delivery.' day')));
                    } else {
                        $this->fields_value = array('id_supplier' =>Tools::getValue('my_supplier'),'reference' => date('Ymd-His'));
                    }
                }

                //specific discount display
                if (isset($this->object->discount_rate)) {
                    $this->object->discount_rate = Tools::ps_round($this->object->discount_rate, 4);
                }

                //specific date display
                if (isset($this->object->date_delivery_expected)) {
                    $date = explode(' ', $this->object->date_delivery_expected);
                    if ($date) {
                        $this->object->date_delivery_expected = $date[0];
                    }
                }

                $this->displayInformation(
                    $this->l('Please note that if you wish to order products, they have to be available for the specified Supplier.')
                    .' '.
                    $this->l('See Catalog/Products/Your Product/Suppliers')
                    .'<br />'.
                    $this->l('Also, changing the currency or the supplier will reset the order.')
                    .'<br /><br />'.
                    $this->l('Finally, please note that you can only order from one supplier at a time.')
                );

                $this->addJqueryUI('ui.datepicker');
            }

            $this->tpl_form_vars['firstCall'] = false;

            return parent::renderForm();
        }
    }

    /**
     * Ths method manage associated products to the order when updating it
     */
    public function manageOrderProducts()
    {
        // load erp order
        $id_erp_order = (int)Tools::getValue('id_erp_order', null);
        $products_already_in_order = array();

        if ($id_erp_order != null) {
            $erp_order = new ErpOrder($id_erp_order);

            if (Validate::isLoadedObject($erp_order)) {
                // tests if the supplier or currency have changed in the supply order
                $new_supplier_id = (int)Tools::getValue('id_supplier');
                $new_currency_id = (int)Tools::getValue('id_currency');

                if (($new_supplier_id != $erp_order->id_supplier) ||
                    ($new_currency_id != $erp_order->id_currency)) {
                    // resets all products in this order
                    $erp_order->id_currency = $new_currency_id;
                    $erp_order->resetProducts();
                    $this->loadProducts();
                } else {
                    $products_already_in_order = $erp_order->getEntries();
                    $currency = new Currency($erp_order->id_currency);

                    // gets all product ids to manage
                    $product_ids_str = Tools::getValue('product_ids', null);
                    $product_ids = explode('|', $product_ids_str);
                    $product_ids_to_delete_str = Tools::getValue('product_ids_to_delete', null);
                    $product_ids_to_delete = array_unique(explode('|', $product_ids_to_delete_str));

                    //delete products that are not managed anymore
                    foreach ($products_already_in_order as $paio) {
                        $product_ok = false;

                        foreach ($product_ids_to_delete as $id) {
                            $id_check = $paio['id_product'].'_'.$paio['id_product_attribute'];
                            if ($id_check == $id) {
                                $product_ok = true;
                            }
                        }

                        if ($product_ok === true) {
                            $entry = new ErpOrderDetail($paio['id_erp_order_detail']);
                            $entry->delete();
                        }
                    }

                    // manage each product
                    foreach ($product_ids as $id) {
                        $errors = array();

                        // check if a checksum is available for this product and test it
                        $check = Tools::getValue('input_check_'.$id, '');
                        $check_valid = md5(_COOKIE_KEY_.$id);

                        if ($check_valid != $check) {
                            continue;
                        }

                        $pos = strpos($id, '_');
                        if ($pos === false) {
                            continue;
                        }

                        // Load / Create supply order detail
                        $entry = new ErpOrderDetail();
                        $id_erp_order_detail = (int)Tools::getValue('input_id_'.$id, 0);
                        if ($id_erp_order_detail > 0) {
                            $existing_entry = new ErpOrderDetail($id_erp_order_detail);
                            if (Validate::isLoadedObject($erp_order)) {
                                $entry = &$existing_entry;
                            }
                        }
                        
                        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && $id_erp_order_detail) {
                            $warehouses = Warehouse::getWarehouses(true);
                            $quantityPurchased = 0;
                            foreach ($warehouses as $warehouse) {
                                $quantityPurchased += Tools::getValue('qtyWarehouse_'.$warehouse['id_warehouse'].'_'.$id,0);
                                $checkWarehouseOrder = Db::getInstance()->getValue('SELECT `id_erp_order_detail` FROM `'._DB_PREFIX_.'erp_order_detail_by_warehouse` WHERE `id_erp_order_detail` = '.$id_erp_order_detail.' AND `id_warehouse` = '.(int)$warehouse['id_warehouse']);
                 				
                                if (count($warehouses) == 1) {
                                    $quantityPurchased = Tools::getValue('input_quantity_ordered_'.$id, 0);
                                    $quantityByWarehouse = Tools::getValue('input_quantity_ordered_'.$id, 0);
                            	} else {
                                    $quantityByWarehouse = Tools::getValue('qtyWarehouse_'.$warehouse['id_warehouse'].'_'.$id, 0);
                            	}
                                
                                if($quantityByWarehouse){
                                    if ($checkWarehouseOrder) {
                                        Db::getInstance()->update('erp_order_detail_by_warehouse', array('quantity' => $quantityByWarehouse), '`id_erp_order_detail` = '.$id_erp_order_detail.' AND `id_warehouse` = '.(int)$warehouse['id_warehouse']);
                                    } else {
                                         Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'erp_order_detail_by_warehouse` (`id_erp_order_detail`,`id_warehouse`, `quantity`) VALUES ('.$id_erp_order_detail.','.(int)$warehouse['id_warehouse'].','.$quantityByWarehouse.')');
                                    }
                                }
                            }
                        }
                        
                        // get product informations
                        $entry->id_product = Tools::substr($id, 0, $pos);
                        $entry->id_product_attribute = Tools::substr($id, $pos + 1);
                        $entry->unit_price_te = (float)str_replace(array(' ', ','), array('', '.'), Tools::getValue('input_unit_price_te_'.$id, 0));
                        $entry->quantity_ordered = (int)str_replace(array(' ', ','), array('', '.'), ((isset($quantityPurchased) && $quantityPurchased) ? $quantityPurchased : Tools::getValue('input_quantity_ordered_'.$id, 0)));
                        $entry->discount_rate = (float)str_replace(array(' ', ','), array('', '.'), Tools::getValue('input_discount_rate_'.$id, 0));
                        $entry->tax_rate = (float)str_replace(array(' ', ','), array('', '.'), Tools::getValue('input_tax_rate_'.$id, 0));
                        $entry->reference = Tools::getValue('input_reference_'.$id, '');
                        $entry->supplier_reference = Tools::getValue('input_supplier_reference_'.$id, '');
                        $entry->ean13 = Tools::getValue('input_ean13_'.$id, '');
                        $entry->upc = Tools::getValue('input_upc_'.$id, '');

                        //get the product name in the order language
                        $entry->name = Product::getProductName($entry->id_product, $entry->id_product_attribute, $this->context->language->id);

                        if (empty($entry->name)) {
                            $entry->name = '';
                        }

                        if ($entry->supplier_reference == null) {
                            $entry->supplier_reference = '';
                        }

                        $entry->exchange_rate = $currency->conversion_rate;
                        $entry->id_currency = $currency->id;

                        $entry->id_erp_order = $erp_order->id;

                        $errors = $entry->validateController();

                        // if there is a problem, handle error for the current product
                        if (count($errors) > 0) {
                            // add the product to error array => display again product line
                            $this->order_products_errors[] = array(
                                'id_product' =>    $entry->id_product,
                                'id_product_attribute' => $entry->id_product_attribute,
                                'unit_price_te' =>    $entry->unit_price_te,
                                'quantity_ordered' => $entry->quantity_ordered,
                                'name' => $entry->name,
                                'discount_rate' =>    $entry->discount_rate,
                                'tax_rate' => $entry->tax_rate,
                                'reference' => $entry->reference,
                                'supplier_reference' => $entry->supplier_reference,
                                'ean13' => $entry->ean13,
                                'upc' => $entry->upc,
                            );

                            $error_str = '<ul>';
                            foreach ($errors as $e) {
                                $error_str .= '<li>'.$this->l('field').$e.'</li>';
                            }
                            $error_str .= '</ul>';

                            $this->errors[] = Tools::displayError($this->l('Please verify the product information:').$entry->name.' '.$error_str);
                        } else {
                            $entry->save();
                        }
                    }
                }
            }
        }
    }

    /**
     * AdminController::postProcess() override
     * @see AdminController::postProcess()
     */
    public function postProcess()
    {
        $this->is_editing_order = false;

        // Checks access
        /*if (Tools::isSubmit('submitAdderp_order') && !($this->tabAccess['add'] === '1')) {
            $this->errors[] = Tools::displayError($this->l('You do not have the required permission to add a supply order.'));
        }
        if (Tools::isSubmit('submitBulkUpdateerp_order_detail') && !($this->tabAccess['edit'] === '1')) {
            $this->errors[] = Tools::displayError($this->l('You do not have the required permission to edit an order.'));
        }*/

        // checks if supply order reference is unique
        if (Tools::isSubmit('reference')) {
            // gets the reference
            $ref = pSQL(Tools::getValue('reference'));

            if (Tools::getValue('id_erp_order') != 0 && ErpOrder::getReferenceById((int)Tools::getValue('id_erp_order')) != $ref) {
                if ((int)ErpOrder::exists($ref) != 0) {
                    $this->errors[] = Tools::displayError($this->l('The reference has to be unique.'));
                }
            } elseif (Tools::getValue('id_erp_order') == 0 && (int)ErpOrder::exists($ref) != 0) {
                $this->errors[] = Tools::displayError($this->l('The reference has to be unique.'));
            }
        }

        if ($this->errors) {
            return;
        }

        // Global checks when add / update a supply order
        if (Tools::isSubmit('submitAdderp_order') || Tools::isSubmit('submitAdderp_orderAndStay') || Tools::isSubmit('submitAdderp_orderAndImport') || Tools::isSubmit('submitAdderp_orderEmpty')) {
            $this->action = 'save';
            $this->is_editing_order = true;
            
            // get supplier ID
            $id_supplier = (int)Tools::getValue('id_supplier', 0);
            if ($id_supplier <= 0 || !Supplier::supplierExists($id_supplier)) {
                $this->errors[] = Tools::displayError($this->l('The selected supplier is not valid.'));
            }
            
            //Retrieve language to supplier to send email and PDF
            if ($id_supplier) {
                $erpSupplier = new ErpSuppliers((int)ErpSuppliers::getSupplierById($id_supplier));
                
                if (Validate::isLoadedObject($erpSupplier)) {
                    $_POST['id_supplier'] = $erpSupplier->id_supplier;
                    //$_POST['id_supplier'] = $erpSupplier->id_supplier; //Pour ceux qui ont les supplier/erp_supplier inversé, utiliser cette ligne
                    $_POST['id_lang'] = $erpSupplier->id_lang;
                }
            }
            
            // get delivery date
            if (!Tools::getValue('date_delivery_expected') && !Tools::getValue('generate')) {
                $this->errors[] = Tools::displayError($this->l('Delivery date is required.'));
            }

            if (!Tools::getValue('generate')) {
                $delivery_expected = new DateTime(pSQL(Tools::getValue('date_delivery_expected')));
            } else {
                $delivery_expected = date('Y-m-d H:i:s', strtotime('+'.$erpSupplier->delivery.' day'));
                $_POST['date_delivery_expected'] = $delivery_expected;
                $delivery_expected = new DateTime();
            }
            
            if (Tools::getValue('generate')) { 
                $_POST['id_currency'] = Configuration::get('PS_CURRENCY_DEFAULT');
                $_POST['reference'] = pSQL(date('Ymd-His'));
            }
            
            // converts date to timestamp
            if ($delivery_expected <= (new DateTime('yesterday'))) {
                $this->errors[] = Tools::displayError($this->l('The date you specified cannot be in the past.'));
            }

            // gets threshold
            $quantity_threshold = null;
            if (Tools::getValue('load_products') && Validate::isInt(Tools::getValue('load_products'))) {
                $quantity_threshold = (int)Tools::getValue('load_products');
            } else {
                $quantity_threshold = 0;
            }

            if (!count($this->errors)) {
                // specify initial state
                $_POST['id_erp_order_state'] = 1; //defaut creation state

                // specify supplier name
                $_POST['supplier_name'] = Supplier::getNameById($id_supplier);
            }
            
            if (!Tools::isSubmit('submitAdderp_orderAndImport') && !Tools::isSubmit('submitAdderp_orderEmpty')) {
                // manage each associated product
                $this->manageOrderProducts();

                // if the threshold is defined and we are saving the order
                if (Tools::isSubmit('submitAdderp_order') && !Tools::getValue('product_ids') && !Tools::isSubmit('submitAdderp_orderAndImport') && !Tools::isSubmit('submitAdderp_orderEmpty')) {
                    $this->loadProducts($quantity_threshold);
                }
            } else {
                if (!count($this->errors) && !Tools::isSubmit('submitAdderp_orderEmpty')) {
                    $this->redirect_after = $this->context->link->getAdminLink('AdminErpImport').'&wic_order_import=true';
                }
            }
        }

        // Manage state change
        if (Tools::isSubmit('submitChangestate')
            && Tools::isSubmit('id_erp_order')
            && Tools::isSubmit('id_erp_order_state')) {
            /*if ($this->tabAccess['edit'] != '1') {
                $this->errors[] = Tools::displayError($this->l('You do not have permission to change the order status.'));
            }*/

            // get state ID
            $id_state = (int)Tools::getValue('id_erp_order_state', 0);
            if ($id_state <= 0) {
                $this->errors[] = Tools::displayError($this->l('The selected just oin time order status is not valid.'));
            }

            // get supply order ID
            $id_erp_order = (int)Tools::getValue('id_erp_order', 0);
            if ($id_erp_order <= 0) {
                $this->errors[] = Tools::displayError($this->l('The Erp order id is not valid.'));
            }

            if (!count($this->errors)) {
                // try to load supply order
                $erp_order = new ErpOrder($id_erp_order);

                if (Validate::isLoadedObject($erp_order)) {
                    // get valid available possible states for this order
                    $states = ErpOrderState::getErpOrderStates($erp_order->id_erp_order_state);

                    foreach ($states as $state) {
                        // if state is valid, change it in the order
                        if ($id_state == $state['id_erp_order_state']) {
                            $new_state = new ErpOrderState($id_state);
                            $old_state = new ErpOrderState($erp_order->id_erp_order_state);

                            // special case of validate state - check if there are products in the order and the required state is not an enclosed state
                            if ($erp_order->isEditable() && !$erp_order->hasEntries() && !$new_state->enclosed) {
                                $this->errors[] = Tools::displayError(
                                    $this->l('It is not possible to change the status of this order because you did not order any products')
                                );
                            }

                            if (!count($this->errors)) {
                                //Send email to supplier if configuration is active
                                if ($id_state == 2 && Configuration::get('WIC_ERP_SEND_EMAIL_SUPPLIER')) {
                                    $this->sendSupplierOrder($erp_order);
                                }

                                $erp_order->id_erp_order_state = $state['id_erp_order_state'];
                                if ($erp_order->save()) {
                                    // if pending_receipt,
                                    // or if the order is being canceled,
                                    // synchronizes StockAvailable
                                    if (($new_state->pending_receipt && !$new_state->receipt_state) ||
                                        ($old_state->receipt_state && $new_state->enclosed && !$new_state->receipt_state)) {
                                        $erp_order_details = $erp_order->getEntries();
                                        $products_done = array();
                                        foreach ($erp_order_details as $erp_order_detail) {
                                            if (!in_array($erp_order_detail['id_product'], $products_done)) {
                                                //StockAvailable::synchronize($erp_order_detail['id_product']);
                                                $products_done[] = $erp_order_detail['id_product'];
                                            }
                                        }
                                    }

                                    $token = Tools::getValue('token') ? Tools::getValue('token') : $this->token;
                                    $redirect = self::$currentIndex.'&token='.$token;
                                    $this->redirect_after = $redirect.'&conf=5';
                                }
                            }
                        }
                    }
                } else {
                    $this->errors[] = Tools::displayError($this->l('The selected supplier is not valid.'));
                }
            }
        }

        // updates receipt
        if (Tools::isSubmit('submitBulkUpdateerp_order_detail') && Tools::isSubmit('id_erp_order')) {
            $this->postProcessUpdateReceipt();
        }

        if (Tools::isSubmit('deleteerp_order_attachement')) {
            if (Tools::getValue('id_erp_order_attachement')) {
                $erp_order_attachement = new ErpOrderAttachement((int)Tools::getValue('id_erp_order_attachement'));

                if (Validate::isLoadedObject($erp_order_attachement)) {
                    @unlink(_PS_MODULE_DIR_.'wic_erp/upload/'.$erp_order_attachement->file_name);
                    $erp_order_attachement->delete();
                    unset($erp_order_attachement);
                    $this->confirmations[] = $this->l('Your file has been deleted successfully.');
                } else {
                    $this->errors[] = Tools::displayError($this->l('Error deleting file'));
                }
            } else {
                $this->errors[] = Tools::displayError($this->l('Error deleting file'));
            }
        }

        if (Tools::isSubmit('submitBulkdeleteerp_order_attachement')) {
            if ($this->postProcessDeleteFile()) {
                $this->confirmations[] = $this->l('Your file has been deleted successfully.');
            } else {
                $this->errors[] = Tools::displayError($this->l('Error deleting file'));
            }
        }
            
        if (Tools::isSubmit('submitWic_erpfile')) {
            if ($this->postProcessUploadFile()) {
                $this->confirmations[] = $this->l('Your file has been uploaded successfully.');
            }
        }

        if ((!count($this->errors) && $this->is_editing_order) || !$this->is_editing_order) {
            parent::postProcess();
        }
    }

    /**
     * Helper function for AdminErpSupplierOrdersController::postProcess()
     *
     * @see AdminErpSupplierOrdersController::postProcess()
     */
    protected function postProcessUpdateReceipt()
    {
        // gets all box selected
        $rows = Tools::getValue('erp_order_detailBox');
        if (!$rows) {
            $this->errors[] = Tools::displayError($this->l('You did not select any product to update'));
            return;
        }

        // final array with id_erp_order_detail and value to update
        $to_update = array();
        // gets quantity for each id_order_detail
        foreach ($rows as $row) {
            if (Tools::getValue('quantity_received_today_'.$row) || Tools::getValue('adminstrativeReceipt_'.$row)) {
                $to_update[$row] = (int)Tools::getValue('quantity_received_today_'.$row);
            }
        }

        // checks if there is something to update
        if (!count($to_update)) {
            $this->errors[] = Tools::displayError($this->l('You did not select any product to update'));
            return;
        }
        
        if (Tools::getValue('id_warehouse') && Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
            // Adds to stock
            $warehouse = new Warehouse((int)Tools::getValue('id_warehouse'));
            if (!Validate::isLoadedObject($warehouse)) {
                $this->errors[] = Tools::displayError('The warehouse could not be loaded.');
                return;
            }
        }

        $totaly_received = true;

        foreach ($to_update as $id_erp_order_detail => $quantity) {
            $erp_order_detail = new ErpOrderDetail($id_erp_order_detail);
            $erp_order = new ErpOrder((int)Tools::getValue('id_erp_order'));

            if (Validate::isLoadedObject($erp_order_detail) && Validate::isLoadedObject($erp_order)) {
                // checks if quantity is valid
                // It's possible to receive more quantity than expected in case of a shipping error from the supplier
                if (!Validate::isInt($quantity) || $quantity < 0) {
                    $this->errors[] = sprintf(Tools::displayError($this->l('Quantity (%d) for product #%d is not valid')), (int)$quantity, (int)$id_erp_order_detail);
                } elseif ($quantity == 0 && $this->dlcDluoActive && Tools::getValue('adminstrativeReceipt_'.$id_erp_order_detail)) {
                    $totaly_received = false;
                    //We Add DLC /DLUO if module exists
                    //We verify if there is DLC/BBD Lines for this product
                    if (Tools::getValue('numberDlcRow_'.$id_erp_order_detail)) {
                        // creates the history
                        $erp_receipt_history = new ErpOrderReceiptHistory();
                        $erp_receipt_history->id_erp_order_detail = (int)$id_erp_order_detail;
                        $erp_receipt_history->id_employee = (int)$this->context->employee->id;
                        $erp_receipt_history->employee_firstname = pSQL($this->context->employee->firstname);
                        $erp_receipt_history->employee_lastname = pSQL($this->context->employee->lastname);
                        $erp_receipt_history->id_erp_order_state = (int)$erp_order->id_erp_order_state;
                        $erp_receipt_history->quantity = (int)$quantity;
                        $erp_receipt_history->add();
                        
                        $numberDlcRow = Tools::getValue('numberDlcRow_'.$id_erp_order_detail);
                        for ($i = 1; $i <= $numberDlcRow; $i++) {
                            //Get value for each DLC / DLUO
                            $dlc = new ErpOrderReceiptDlc();
                            $dlc->id_erp_order_receipt_history = $erp_receipt_history->id;
                            $dlc->batch_number = Tools::getValue('batch_number_'.$id_erp_order_detail.'_'.$i);
                            $dlc->dlc = Tools::getValue('dlc_'.$id_erp_order_detail.'_'.$i);
                            $dlc->bbd = Tools::getValue('bbd_'.$id_erp_order_detail.'_'.$i);
                            $dlc->quantity = Tools::getValue('quantity_'.$id_erp_order_detail.'_'.$i);
                            $dlc->current_stock = (Tools::getValue('current_stock_'.$id_erp_order_detail.'_'.$i) ? 1 : 0);
                            $dlc->add();

                            //Get current stock for this product and combination
                            $position = Db::getInstance()->getValue('SELECT MAX(is_current_stock) FROM `'._DB_PREFIX_.'products_dlc_dluo` WHERE id_product = '.(int)$erp_order_detail->id_product.' AND id_combinaison = '.(int)$erp_order_detail->id_product_attribute);

                            if (!$position && !$dlc->current_stock) {
                                $position = 2;
                            }
                            elseif ($dlc->current_stock) {
                                $position = 1;
                            } else {
                                $position++;
                            }

                            //Insert in module DLC/BBD
                            $dlcdluo = new DlcDluoClass();
                            $dlcdluo->id_product = (int)$erp_order_detail->id_product;
                            $dlcdluo->id_combinaison = (int)$erp_order_detail->id_product_attribute;
                            $dlcdluo->is_current_stock = $position;
                            $dlcdluo->numero_lot = $dlc->batch_number;
                            $dlcdluo->dlc = $dlc->dlc;
                            $dlcdluo->dluo = $dlc->bbd;
                            $dlcdluo->stock = $dlc->quantity;
                            $dlcdluo->entry_date = $dlc->date_add;
                            $dlcdluo->alert1 = '';
                            $dlcdluo->alert2 = '';
                            $dlcdluo->id_warehouse = Tools::getValue('id_warehouse');
                            $dlcdluo->add();
                        }  
                    }
                }
                else {
                    // everything is valid :  updates

                    if (((int)$quantity + (int)$erp_order_detail->quantity_received) < (int)$erp_order_detail->quantity_ordered) {
                        $totaly_received = false;
                    }

                    // creates the history
                    $erp_receipt_history = new ErpOrderReceiptHistory();
                    $erp_receipt_history->id_erp_order_detail = (int)$id_erp_order_detail;
                    $erp_receipt_history->id_employee = (int)$this->context->employee->id;
                    $erp_receipt_history->employee_firstname = pSQL($this->context->employee->firstname);
                    $erp_receipt_history->employee_lastname = pSQL($this->context->employee->lastname);
                    $erp_receipt_history->id_erp_order_state = (int)$erp_order->id_erp_order_state;
                    $erp_receipt_history->quantity = (int)$quantity;

                    // updates quantity received
                    $erp_order_detail->quantity_received += (int)$quantity;

                    // if current state is "Pending receipt", then we sets it to "Order received in part"
                    if (3 == $erp_order->id_erp_order_state) {
                        $erp_order->id_erp_order_state = 4;
                    }

                    if ($erp_order_detail->id_product_attribute) {
                        $res = StockAvailable::updateQuantity((int)$erp_order_detail->id_product, (int)$erp_order_detail->id_product_attribute, $quantity, (int)Context::getContext()->shop->id);
                    } else {
                        $res = StockAvailable::updateQuantity((int)$erp_order_detail->id_product, 0, $quantity, (int)Context::getContext()->shop->id);
                    }
                    
                    
                    if (isset($warehouse)) {
                        /* Supply order */
                        $id_stock_mvt_reason = 8;
    
                        $manager = StockManagerFactory::getManager();
                        $result = $manager->addProduct((int)$erp_order_detail->id_product, (int)$erp_order_detail->id_product_attribute, $warehouse, (int)$quantity, $id_stock_mvt_reason, (float)$erp_order_detail->unit_price_te, true, null);

                        $location = Warehouse::getProductLocation((int)$erp_order_detail->id_product, (int)$erp_order_detail->id_product_attribute, $warehouse->id);

                        $result = Warehouse::setProductlocation((int)$erp_order_detail->id_product, (int)$erp_order_detail->id_product_attribute, $warehouse->id, $location ? $location : '');

                        if (!$result) {
                            $this->errors[] = Tools::displayError('Something went wrong when setting warehouse on product record');
                        }
                    }
                    
                    if ($res) {
                        // if product has been added

                        $erp_receipt_history->add();
                        
                        
                        //We Add DLC /DLUO if module exists
                        if ($this->dlcDluoActive) {
                            //We verify if there is DLC/BBD Lines for this product
                            if (Tools::getValue('numberDlcRow_'.$id_erp_order_detail)) {
                                $numberDlcRow = Tools::getValue('numberDlcRow_'.$id_erp_order_detail);
                                for ($i = 1; $i <= $numberDlcRow; $i++) {
                                    //Get value for each DLC / DLUO
                                    $dlc = new ErpOrderReceiptDlc();
                                    $dlc->id_erp_order_receipt_history = $erp_receipt_history->id;
                                    $dlc->batch_number = Tools::getValue('batch_number_'.$id_erp_order_detail.'_'.$i);
                                    $dlc->dlc = Tools::getValue('dlc_'.$id_erp_order_detail.'_'.$i);
                                    $dlc->bbd = Tools::getValue('bbd_'.$id_erp_order_detail.'_'.$i);
                                    $dlc->quantity = Tools::getValue('quantity_'.$id_erp_order_detail.'_'.$i);
                                    $dlc->current_stock = (Tools::getValue('current_stock_'.$id_erp_order_detail.'_'.$i) ? 1 : 0);
                                    $dlc->add();
                                    
                                    //Get current stock for this product and combination
                                    $position = Db::getInstance()->getValue('SELECT MAX(is_current_stock) FROM `'._DB_PREFIX_.'products_dlc_dluo` WHERE id_product = '.(int)$erp_order_detail->id_product.' AND id_combinaison = '.(int)$erp_order_detail->id_product_attribute);
                                    
                                    if (!$position && !$dlc->current_stock) {
                                        $position = 2;
                                    }
                                    elseif ($dlc->current_stock) {
                                        $position = 1;
                                    } else {
                                        $position++;
                                    }
                                        
                                    //Insert in module DLC/BBD
                                    $dlcdluo = new DlcDluoClass();
                                    $dlcdluo->id_product = (int)$erp_order_detail->id_product;
                                    $dlcdluo->id_combinaison = (int)$erp_order_detail->id_product_attribute;
                                    $dlcdluo->is_current_stock = $position;
                                    $dlcdluo->numero_lot = $dlc->batch_number;
                                    $dlcdluo->dlc = $dlc->dlc;
                                    $dlcdluo->dluo = $dlc->bbd;
                                    $dlcdluo->stock = $dlc->quantity;
                                    $dlcdluo->entry_date = $dlc->date_add;
                                    $dlcdluo->alert1 = '';
                                    $dlcdluo->alert2 = '';
                                    $dlcdluo->id_warehouse = Tools::getValue('id_warehouse');
                                    $dlcdluo->add();
                                }  
                            }   
                        }
                        
                        $erp_order_detail->save();
                        $erp_order->save();
                        StockAvailable::synchronize((int)$erp_order_detail->id_product);
                    } else {
                        $this->errors[] = Tools::displayError($this->l('Something went wrong when adding products to the warehouse'));
                    }
                }
            }
        }

        //We verify Receipt quantity and purchased quantity
        $query = new DbQuery();
        $query->select('SUM(od.`quantity_ordered`) as purchased, SUM(od.`quantity_received`) as receipt');
        $query->from('erp_order_detail', 'od');
        $query->where('od.id_erp_order = '.(int)$erp_order->id);

        $res = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($query);

        if ($res) {
            foreach ($res as $result) {
                if ((int)$result['purchased'] > (int)$result['receipt']) {
                    $totaly_received = false;
                }
            }
        }

        if ($totaly_received) {
            $erp_order->id_erp_order_state = 5;
            $erp_order->save();
        }

        if (!count($this->errors)) {
            // display confirm message
            $token = Tools::getValue('token') ? Tools::getValue('token') : $this->token;
            $redirect = self::$currentIndex.'&token='.$token;
            $this->redirect_after = $redirect.'&conf=4';
        }
    }

    protected function postProcessDeleteFile()
    {
        // gets all box selected
        $rows = Tools::getValue('erp_orderBox');
        if (!$rows) {
            return false;
        }
        
        foreach ($rows as $row) {
            $erp_order_attachement = new ErpOrderAttachement((int)$row);
            
            if (Validate::isLoadedObject($erp_order_attachement)) {
                @unlink(_PS_MODULE_DIR_.'wic_erp/upload/'.$erp_order_attachement->file_name);
                $erp_order_attachement->delete();
                unset($erp_order_attachement);
            } else {
                return false;
            }
        }
        
        return true;
    }

    /**
     * Overrides AdminController::afterAdd()
     * @see AdminController::afterAdd()
     * @param ObjectModel $object
     * @return bool
     */
    protected function afterAdd($object)
    {
        if (!Tools::isSubmit('submitAdderp_orderAndImport') && !Tools::isSubmit('submitAdderp_orderEmpty')) {
            if (Tools::getValue('load_products') && Validate::isInt(Tools::getValue('load_products'))) {
                $this->loadProducts((int)Tools::getValue('load_products'));
            } else {
                $this->loadProducts(0);
            }
        }
        
        $this->object = $object;
        return true;
    }

    /**
     * Loads products which quantity (physical quantity) is equal or less than $threshold
     * @param int $threshold
     */
    protected function loadProducts()
    {
        // if there is already an order
        if (Tools::getValue('id_erp_order')) {
            $erp_order = new ErpOrder((int)Tools::getValue('id_erp_order'));
        } else { // else, we just created a new order
            $erp_order = $this->object;
        }
        
        // if order is not valid, return;
        if (!Validate::isLoadedObject($erp_order)) {
            return;
        }

        // resets products if needed
        if (Tools::getValue('id_erp_order')) {
            $erp_order->resetProducts();
        }
        
        $id_erp_supplier = ErpSuppliers::getSupplierById((int)Tools::getValue('id_supplier'));
        $erp_supplier = new ErpSuppliers((int)$id_erp_supplier);
        
        $show_quantities = true;
        $shop_context = Shop::getContext();
        $shop_group = new ShopGroup((int)Shop::getContextShopGroupID());

        // if we are in all shops context, we retrieve all quantities
        if (Shop::isFeatureActive() && $shop_context == Shop::CONTEXT_ALL) {
            $show_quantities = false;
        }
        // if we are in group shop context
        elseif (Shop::isFeatureActive() && $shop_context == Shop::CONTEXT_GROUP) {
            // if quantities are not shared between shops of the group, it's not possible to manage them at group level
            if (!$shop_group->share_stock) {
                $show_quantities = true;
            }
        }
        // if we are in shop context
        elseif (Shop::isFeatureActive()) {
            // if quantities are shared between shops of the group, it's not possible to manage them for a given shop
            if ($shop_group->share_stock) {
                $show_quantities = false;
            }
        }
        
        // gets products
        $query = new DbQuery();
        $query->select('ep.id_product,
						ep.id_product_attribute,
						ps.product_supplier_reference as supplier_reference,
						p.id_tax_rules_group,
						IF(ps.product_supplier_price_te = 0 OR ps.product_supplier_price_te = \'\' OR ps.product_supplier_price_te IS NULL, IF(pa.wholesale_price = 0 OR pa.wholesale_price = \'\' OR pa.wholesale_price IS NULL, IF(psh.wholesale_price = 0 OR psh.wholesale_price = \'\' OR psh.wholesale_price IS NULL, p.wholesale_price, psh.wholesale_price), pa.wholesale_price), ps.product_supplier_price_te) as unit_price_te,
						ps.id_currency,
						IF(pa.reference = \'\' OR pa.reference IS NULL, IF(p.reference = \'\' , \'\', p.reference), pa.reference) as reference,
						IF(pa.ean13 = \'\' OR pa.ean13 IS NULL, IF(p.ean13 = \'\', \'\', p.ean13), pa.ean13) as ean13,
						IF(pa.upc = \'\' OR pa.upc IS NULL, IF(p.upc = \'\', \'\', p.upc), pa.upc) as upc'
                        );
        if (Configuration::get('WIC_ERP_STOCK_MANAGEMENT') == 'normal') {				
            $query->select('(ep.`min_quantity`+ep.`safety_stock`- SUM(sa.`quantity`))   as `stock_to_order`');
            $query->select('(SUM(sa.`quantity`)-ep.`min_quantity`) as `stock_orderable`');               
        } else {
            $query->select('SUM(od.`product_quantity`-od.`product_quantity_refunded`)-SUM(od.`product_quantity_in_stock`) as `stock_to_order`');
        }

        $query->from('erp_products', 'ep');
        $query->leftJoin('stock_available', 'sa', '
                        sa.id_product = ep.id_product
                        AND sa.id_product_attribute = ep.id_product_attribute');					

        $query->leftJoin('product', 'p', 'p.id_product = ep.id_product');
        $query->innerJoin('product_supplier', 'ps', 'ps.id_product = ep.id_product AND ps.id_product_attribute = ep.id_product_attribute AND ps.id_supplier = '.(int)$erp_supplier->id_supplier);
        if (Configuration::get('WIC_ERP_STOCK_MANAGEMENT') != 'normal') {
            $query->leftJoin('order_detail', 'od', 'od.product_id = ep.id_product AND od.product_attribute_id = ep.id_product_attribute AND od.id_order IN (SELECT o.`id_order` FROM `'._DB_PREFIX_.'orders` o WHERE o.`current_state` IN ('.pSQL(Configuration::get('WIC_ERP_NOT_COMPLETE')).') )');
        }

        $query->leftJoin('product_attribute', 'pa', '
                        pa.id_product_attribute = ep.id_product_attribute
                        AND pa.id_product = ep.id_product
                ');
        $query->leftJoin('product_shop', 'psh', '
                        psh.`id_product` = ep.id_product
                        AND psh.id_shop = '.(int)Context::getContext()->shop->id.'
                ');
        if (!configuration::get('WIC_ERP_DISABLED_PRODUCT')) {
            $query->where('p.`active` = 1');
        }
        
        $query->groupBy('ep.id_product,
                        ep.id_product_attribute');
        $query->having('stock_to_order >= 0');

        /* gets items */
        $items = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        // loads order currency
        if (Tools::getValue('id_currency')) {
            $order_currency = new Currency((int)Tools::getValue('id_currency'));
        } else {
            $order_currency = new Currency((int)$erp_order->id_currency);
        }

        if (!Validate::isLoadedObject($order_currency)) {
            return;
        }

        //We retrieve erp order in progress
        $query = new DbQuery();
        $query->select('eos.`id_erp_order_state`');
        $query->from('erp_order_state', 'eos');
        $query->where('eos.`enclosed` != 1 AND eos.`editable` != 1');

        $id_status = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        if ($id_status) {
            $status = array();

            foreach ($id_status as $id_state) {
                $status[] = $id_state['id_erp_order_state'];
            }

            $query = new DbQuery();
            $query->select('eo.`id_erp_order`');
            $query->from('erp_order', 'eo');
            $query->where('id_erp_order_state IN('.pSQL(implode(',', $status)).')');
            
            $orders = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

            if ($orders) {
                $order_progress = array();
                foreach ($orders as $order) {
                    $order_progress[] = $order['id_erp_order'];
                }
            }
        }

        foreach ($items as &$item) {
            $valid_item = true;
            $qty_to_order = (int)$item['stock_to_order'];
            $qty_expected = 0;
            if (isset($order_progress) && count($order_progress) > 0) {
                $query = new DbQuery();
                $query->select('SUM(eod.`quantity_ordered`) as ordered, SUM(eod.`quantity_received`) as received');
                $query->from('erp_order_detail', 'eod');
                $query->where('eod.`id_erp_order` IN ('.pSQL(implode(',', $order_progress)).') AND eod.`id_product` = '.(int)$item['id_product'].' AND eod.`id_product_attribute` = '.(int)$item['id_product_attribute']);
                
                $result_quantity = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow($query);
            }
            
            if (isset($result_quantity) && count($result_quantity)) {
                if ($result_quantity['ordered'] > $result_quantity['received']) {
                    $qty_expected = $result_quantity['ordered'] - $result_quantity['received'];
                } else {
                    $qty_expected = 0;
                }
            } else {
                $qty_expected = 0;
            }
                
            if ($qty_expected < 0) {
                $qty_expected = 0;
            }

            $qty_to_order = $qty_to_order - $qty_expected;
            
            $erp_product = ErpProducts::getProductById((int)$item['id_product'], (int)$item['id_product_attribute']);
            
            //We verify if the loaded Product have combination
            if (!$item['id_product_attribute']) {
                if (ErpProducts::verifyCombination((int)$item['id_product'])) {
                    $product = new ErpProducts((int)$erp_product);
                    
                    if (Validate::isLoadedObject($product)) {
                        $product->delete();
                        $valid_item = false;
                    }
                }
            }
            
            $erp_product = new ErpProducts((int)$erp_product);

            if ($qty_to_order && $qty_to_order > 0) {
                if ($erp_product->unit_order) {
                    if (($qty_to_order % ($erp_product->unit_order))) {
                        $qty_to_order += $erp_product->unit_order - ($qty_to_order % ($erp_product->unit_order));
                    }
                }
            }

            if ($qty_to_order > 0 && $valid_item) {
                // sets erp_order_detail
                $erp_order_detail = new ErpOrderDetail();
                $erp_order_detail->id_erp_order = (int)$erp_order->id;
                $erp_order_detail->id_currency = (int)$order_currency->id;
                $erp_order_detail->id_product = (int)$item['id_product'];
                $erp_order_detail->id_product_attribute = (int)$item['id_product_attribute'];
                $erp_order_detail->reference = $item['reference'];
                $erp_order_detail->supplier_reference = $item['supplier_reference'];
                $erp_order_detail->name = Product::getProductName($item['id_product'], $item['id_product_attribute'], $this->context->cookie->id_lang);
                $erp_order_detail->ean13 = $item['ean13'];
                $erp_order_detail->upc = $item['upc'];
                $erp_order_detail->quantity_ordered = $qty_to_order;
                $erp_order_detail->exchange_rate = $order_currency->conversion_rate;
        
                if ($tax_rate = $this->getTaxRate($item['id_tax_rules_group'])) {
                    if ($erp_supplier->vat_exemption) {
                        $erp_order_detail->tax_rate = 0;
                    } else {
                        $erp_order_detail->tax_rate = $tax_rate;
                    }
                }
  
                $product_currency = new Currency((int)$item['id_currency']);
                
                if (Validate::isLoadedObject($product_currency)) {
                    $erp_order_detail->unit_price_te = Tools::convertPriceFull($item['unit_price_te'], $product_currency, $order_currency);
                } else {
                    $erp_order_detail->unit_price_te = 0;
                }

                $erp_order_detail->save();
                
                //We check stocks needs by warehouse
                if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
                    $warehouses = Warehouse::getWarehouses(true);
                    $quantityToOrder = 0;
                    $manager = StockManagerFactory::getManager();
                    foreach ($warehouses as $warehouse) {
                        $stockValue = Db::getInstance()->getRow('SELECT `min_quantity_by_warehouse`, `safety_stock_by_warehouse` FROM `'._DB_PREFIX_.'erp_products_by_warehouse` WHERE `id_erp_products` = '.(int)$erp_product->id_erp_products.' AND `id_warehouse` = '.(int)$warehouse['id_warehouse']);
                        if ($stockValue) {
                            $realQuantity = $manager->getProductRealQuantities($item['id_product'], $item['id_product_attribute'], array($warehouse['id_warehouse']));
                            $quantityToOrderByWarehouse = $stockValue['min_quantity_by_warehouse'] + $stockValue['safety_stock_by_warehouse'] - $realQuantity;
                            $checkWarehouseOrder = Db::getInstance()->getValue('SELECT `id_erp_order_detail` FROM `'._DB_PREFIX_.'erp_order_detail_by_warehouse` WHERE `id_erp_order_detail` = '.$erp_order_detail->id.' AND `id_warehouse` = '.(int)$warehouse['id_warehouse']);
                            
                            if($quantityToOrderByWarehouse < 0) {
                                $quantityToOrderByWarehouse = 0;
                            }
                            
                            $quantityToOrder+= $quantityToOrderByWarehouse;
                                                                
                            if ($checkWarehouseOrder) {
                                Db::getInstance()->update('erp_order_detail_by_warehouse', array('quantity' => $quantityToOrderByWarehouse), '`id_erp_order_detail` = '.$erp_order_detail->id.' AND `id_warehouse` = '.(int)$warehouse['id_warehouse']);
                            } else {
                                 Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'erp_order_detail_by_warehouse` (`id_erp_order_detail`,`id_warehouse`, `quantity`) VALUES ('.$erp_order_detail->id.','.(int)$warehouse['id_warehouse'].','.$quantityToOrderByWarehouse.')');
                            }
                        }
                    }
                }
            }
            unset($product_currency);
        }
        // updates supply order
        $erp_order->update();
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

    /**
     * Callback used to display custom content for a given field
     * @param int $id_supply_order
     * @param string $tr
     * @return string $content
     */
    public function printExportIcons($id_erp_order)
    {
        $erp_order = new ErpOrder((int)$id_erp_order);

        if (!Validate::isLoadedObject($erp_order)) {
            return;
        }

        $erp_order_state = new ErpOrderState($erp_order->id_erp_order_state);
        if (!Validate::isLoadedObject($erp_order_state)) {
            return;
        }

        $content = '<span style="width:20px; margin-right:5px;">';
        if ($erp_order_state->editable == false) {
            $content .= '<a href="'.$this->context->link->getAdminLink('AdminErpSupplierOrders').'&generateErpOrderFormPDF&id_erp_order='.(int)$erp_order->id.'" title="'.$this->l('Export as PDF').'"><i class="icon-print"></i></a>';
        } else {
            $content .= '-';
        }
        $content .= '</span>';
        
        $content .= '<span style="width:20px; margin-right:5px;">';
        if ($erp_order_state->editable == false) {
            $content .= '<a href="'.$this->context->link->getAdminLink('AdminErpSupplierOrders').'&amp;id_erp_order='.(int)$erp_order->id.'
						 &csv_order_details" title='.$this->l('Export as CSV').'><i class="icon-table"></i></a>';
        } else {
            $content .= '-';
        }
        $content .= '</span>';
        
        return $content;
    }

    /**
     * Display state action link
     * @param string $token the token to add to the link
     * @param int $id the identifier to add to the link
     * @return string
     */
    public function displayUpdateReceiptLink($token = null, $id)
    {
        if (!array_key_exists('Receipt', self::$cache_lang)) {
            self::$cache_lang['Receipt'] = $this->l('Update ongoing receipt of products');
        }

        $this->context->smarty->assign(array(
            'href' => self::$currentIndex.
                '&'.$this->identifier.'='.$id.
                '&update_receipt'.($this->dlcDluoActive ? '&dlc='.$this->dlcDluoActive : '').'&token='.($token != null ? $token : $this->token),
            'action' => self::$cache_lang['Receipt'],
        ));

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            return $this->fetchTemplate('/views/templates/admin/erp/helpers/list/', 'list_action_erp_order_receipt16');
        } else {
            return $this->fetchTemplate('/views/templates/admin/erp/helpers/list/', 'list_action_erp_order_receipt');
        }
    }

    /**
     * method call when ajax request is made with the details row action
     * @see AdminController::postProcess()
     */
    public function ajaxProcessDetails()
    {
        // tests if an id is submit
        if (Tools::isSubmit('id') && !Tools::isSubmit('display_product_history') && !Tools::isSubmit('display_product_dlc')) {
            // overrides attributes
            $this->identifier = 'id_erp_order_history';
            $this->table = 'erp_order_history';

            //$this->display = 'list';
            $this->lang = false;
            // gets current lang id
            $lang_id = (int)$this->context->language->id;
            // gets supply order id
            $id_erp_order = (int)Tools::getValue('id');

            // creates new fields_list
            $this->fields_list = array(
                'history_date' => array(
                    'title' => $this->l('Last update'),
                    'width' => 50,
                    'align' => 'left',
                    'type' => 'datetime',
                    'havingFilter' => true
                ),
                'history_employee' => array(
                    'title' => $this->l('Employee'),
                    'width' => 100,
                    'align' => 'left',
                    'havingFilter' => true
                ),
                'history_state_name' => array(
                    'title' => $this->l('Status'),
                    'width' => 100,
                    'align' => 'left',
                    'color' => 'color',
                    'havingFilter' => true
                ),
            );
            // loads history of the given order
            unset($this->_select, $this->_join, $this->_where, $this->_orderBy, $this->_orderWay, $this->_group, $this->_filterHaving, $this->_filter);
            $this->_select = '
			a.`date_add` as history_date,
			CONCAT(a.`employee_lastname`, \' \', a.`employee_firstname`) as history_employee,
			sosl.`name` as history_state_name,
			sos.`color` as color';

            $this->_join = '
			LEFT JOIN `'._DB_PREFIX_.'erp_order_state` sos ON (a.`id_state` = sos.`id_erp_order_state`)
			LEFT JOIN `'._DB_PREFIX_.'erp_order_state_lang` sosl ON
			(
				a.`id_state` = sosl.`id_erp_order_state`
				AND sosl.`id_lang` = '.(int)$lang_id.'
			)';

            $this->_where = 'AND a.`id_erp_order` = '.(int)$id_erp_order;
            $this->_orderBy = 'a.`date_add`';
            $this->_orderWay = 'DESC';

            // gets list and forces no limit clause in the request
            $this->getList($lang_id, 'date_add', 'DESC', 0, false, false);

            // renders list
            $helper = new HelperList();
            $helper->no_link = true;
            $helper->show_toolbar = false;
            $helper->toolbar_scroll = false;
            $helper->shopLinkType = '';
            $helper->identifier = $this->identifier;
            //$helper->colorOnBackground = true;
            $helper->simple_header = true;
            $helper->token = $this->token;
            $helper->table = $this->table;
            $content = $helper->generateList($this->_list, $this->fields_list);

            echo Tools::jsonEncode(array('use_parent_structure' => false, 'data' => $content));
        } elseif (Tools::isSubmit('id') && Tools::isSubmit('display_product_history') && !Tools::isSubmit('display_product_dlc')) {
            $this->identifier = 'id_erp_order_receipt_history';
            $this->table = 'erp_order_receipt_history';
            $this->display = 'list';
            $this->lang = false;
            $lang_id = (int)$this->context->language->id;
            $id_erp_order_detail = (int)Tools::getValue('id');

            unset($this->fields_list);
            $this->fields_list = array(
                'date_add' => array(
                    'title' => $this->l('Last update'),
                    'width' => 50,
                    'align' => 'left',
                    'type' => 'datetime',
                    'havingFilter' => true
                ),
                'employee' => array(
                    'title' => $this->l('Employee'),
                    'width' => 100,
                    'align' => 'left',
                    'havingFilter' => true
                ),
                'quantity' => array(
                    'title' => $this->l('Quantity received'),
                    'width' => 100,
                    'align' => 'left',
                    'havingFilter' => true
                ),
            );

            // loads history of the given order
            unset($this->_select, $this->_join, $this->_where, $this->_orderBy, $this->_orderWay, $this->_group, $this->_filterHaving, $this->_filter);
            $this->_select = 'CONCAT(a.`employee_lastname`, \' \', a.`employee_firstname`) as employee';
            $this->_where = 'AND a.`id_erp_order_detail` = '.(int)$id_erp_order_detail;

            // gets list and forces no limit clause in the request
            $this->getList($lang_id, 'date_add', 'DESC', 0, false, false);

            // renders list
            $helper = new HelperList();
            $helper->no_link = true;
            $helper->show_toolbar = false;
            $helper->toolbar_scroll = false;
            $helper->shopLinkType = '';
            $helper->identifier = $this->identifier;
            $helper->colorOnBackground = true;
            $helper->simple_header = true;
            $helper->token = $this->token;
            $helper->table = $this->table;
            $content = $helper->generateList($this->_list, $this->fields_list);
            
            if ($this->dlcDluoActive) {
                //We retrieve DLC / DLUO
                $this->identifier = 'id_erp_order_receipt_dlc_bdd';
                $this->table = 'erp_order_receipt_dlc_bbd';
                $this->display = 'list';
                $this->lang = false;
                $lang_id = (int)$this->context->language->id;
                $id_erp_order_detail = (int)Tools::getValue('id');

                unset($this->fields_list);
                $this->fields_list = array(
                    'date_add' => array(
                        'title' => $this->l('Last update'),
                        'width' => 50,
                        'align' => 'left',
                        'type' => 'datetime',
                        'havingFilter' => true
                    ),
                    'employee' => array(
                        'title' => $this->l('Employee'),
                        'width' => 100,
                        'align' => 'left',
                        'havingFilter' => true
                    ),
                    'batch_number' => array(
                        'title' => $this->l('Batch number'),
                        'width' => 100,
                        'align' => 'left',
                        'havingFilter' => true
                    ),
                    'dlc' => array(
                        'title' => $this->l('DLC'),
                        'width' => 100,
                        'align' => 'left',
                        'havingFilter' => true
                    ),
                    'bbd' => array(
                        'title' => $this->l('BBD'),
                        'width' => 100,
                        'align' => 'left',
                        'havingFilter' => true
                    ),
                    'quantity' => array(
                        'title' => $this->l('Quantity received'),
                        'width' => 100,
                        'align' => 'left',
                        'havingFilter' => true
                    ),
                );

                // loads history of the given order
                unset($this->_select, $this->_join, $this->_where, $this->_orderBy, $this->_orderWay, $this->_group, $this->_filterHaving, $this->_filter);
                $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'erp_order_receipt_history` orh ON ('.(int)$id_erp_order_detail.' = orh.`id_erp_order_detail`)';
                $this->_select = 'CONCAT(orh.`employee_lastname`, \' \', orh.`employee_firstname`) as employee';
                $this->_where = 'AND a.`id_erp_order_receipt_history` = orh.`id_erp_order_receipt_history`';

                // gets list and forces no limit clause in the request
                $this->getList($lang_id, 'date_add', 'DESC', 0, false, false);

                // renders list
                $helper = new HelperList();
                $helper->no_link = true;
                $helper->show_toolbar = false;
                $helper->toolbar_scroll = false;
                $helper->shopLinkType = '';
                $helper->identifier = $this->identifier;
                $helper->colorOnBackground = true;
                $helper->simple_header = true;
                $helper->token = $this->token;
                $helper->table = $this->table;

                $content .= $helper->generateList($this->_list, $this->fields_list);
            }

            echo Tools::jsonEncode(array('use_parent_structure' => false, 'data' => $content));
        }
        die;
    }

    public function ajaxProcessDlc()
    {
        if (Tools::isSubmit('id') && Tools::isSubmit('display_product_dlc') && !Tools::isSubmit('display_product_history')) {
            $this->context->smarty->assign(array('id_detail_row' => Tools::getValue('id'), 'token' => $this->token));
            echo Tools::jsonEncode(array('use_parent_structure' => false, 'data' => $this->fetchTemplate('/views/templates/admin/erp_orders_receipt_histor/helpers/list/', 'list_dlc_action'))); 
        }
        die;
    }
    
    public function ajaxProcess()
    {
        if (Tools::isSubmit('newProductDlcRow')) {
            die($this->getProductDlcDisplay(Tools::getValue('product_dlc_group_id'), Tools::getValue('product_dlc_id')));
        }
    }
    /**
     * method call when ajax request is made for search product to add to the order
    */
    public function ajaxProcessSearchProduct()
    {
        // Get the search pattern
        $pattern = pSQL(Tools::getValue('q', false));

        if (!$pattern || $pattern == '' || Tools::strlen($pattern) < 1) {
            die();
        }

        // get supplier id
        $id_supplier = (int)Tools::getValue('id_supplier', false);

        //Retreive supplier information
        $erpSupplier = new ErpSuppliers((int)$id_supplier);
            
        // get lang from context
        $id_lang = (int)Context::getContext()->language->id;

        $query = new DbQuery();
        $query->select('
			CONCAT(p.id_product, \'_\', IFNULL(pa.id_product_attribute, \'0\')) as id,
			pa.id_product_attribute,
			p.id_product,
			p.id_tax_rules_group,
			ps.product_supplier_reference as supplier_reference,
			IF(ps.product_supplier_price_te = 0 OR ps.product_supplier_price_te = \'\' OR ps.product_supplier_price_te IS NULL, IF(pa.wholesale_price = 0 OR pa.wholesale_price = \'\' OR pa.wholesale_price IS NULL, IF(psh.wholesale_price = 0 OR psh.wholesale_price = \'\' OR psh.wholesale_price IS NULL, p.wholesale_price, psh.wholesale_price), pa.wholesale_price), ps.product_supplier_price_te) as unit_price_te,			
			IF(pa.reference = \'\' OR pa.reference IS NULL, IF(p.reference = \'\' , \'\', p.reference), pa.reference) as reference,
			IF(pa.ean13 = \'\' OR pa.ean13 IS NULL, IF(p.ean13 = \'\', \'\', p.ean13), pa.ean13) as ean13,
			IF(pa.upc = \'\' OR pa.upc IS NULL, IF(p.upc = \'\', \'\', p.upc), pa.upc) as upc,
			md5(CONCAT(\''._COOKIE_KEY_.'\', p.id_product, \'_\', IFNULL(pa.id_product_attribute, \'0\'))) as checksum,
			IFNULL(CONCAT(pl.name, \' : \', GROUP_CONCAT(DISTINCT agl.name, \' - \', al.name SEPARATOR \', \')), pl.name) as name
		');

        $query->from('product', 'p');

        $query->innerJoin('product_lang', 'pl', 'pl.id_product = p.id_product AND pl.id_lang = '.(int)$id_lang);
        $query->leftJoin('product_attribute', 'pa', 'pa.id_product = p.id_product');
        $query->leftJoin('product_attribute_combination', 'pac', 'pac.id_product_attribute = pa.id_product_attribute');
        $query->leftJoin('attribute', 'atr', 'atr.id_attribute = pac.id_attribute');
        $query->leftJoin('attribute_lang', 'al', 'al.id_attribute = atr.id_attribute AND al.id_lang = '.(int)$id_lang);
        $query->leftJoin('attribute_group_lang', 'agl', 'agl.id_attribute_group = atr.id_attribute_group AND agl.id_lang = '.(int)$id_lang);
        $query->leftJoin('product_supplier', 'ps', 'ps.id_product = p.id_product AND ps.id_product_attribute = IFNULL(pa.id_product_attribute, 0)');
        $query->leftJoin('product_shop', 'psh', '
			psh.`id_product` = p.id_product
			AND psh.id_shop = '.(int)Context::getContext()->shop->id.'
		');
        
        $query->where('(pl.name LIKE \'%'.pSQL($pattern).'%\' OR p.reference LIKE \'%'.pSQL($pattern).'%\' OR ps.product_supplier_reference LIKE \'%'.pSQL($pattern).'%\')');
        $query->where('p.id_product NOT IN (SELECT pd.id_product FROM `'._DB_PREFIX_.'product_download` pd WHERE (pd.id_product = p.id_product))');
        $query->where('p.is_virtual = 0 AND p.cache_is_pack = 0');

        if ($id_supplier) {
            $query->where('ps.id_supplier = '.(int)$id_supplier.' OR p.id_supplier = '.(int)$id_supplier);
        }

        $query->groupBy('p.id_product, pa.id_product_attribute');

        $items = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        foreach ($items as &$item) {
            $ids = explode('_', $item['id']);
            $prices = ProductSupplier::getProductSupplierPrice($ids[0], $ids[1], $id_supplier, true);
            $this->setProductImageInformations($item);

            if ($item['image'] != null) {
                $name = 'product_mini_'.(int)$item['id_product'].(isset($item['id_product_attribute']) ? '_'.(int)$item['id_product_attribute'] : '').'.jpg';
                // generate image cache, only for back office
                $item['image_tag'] = ImageManager::thumbnail(_PS_IMG_DIR_.'p/'.$item['image']->getExistingImgPath().'.jpg', $name, 45, 'jpg');
                if (file_exists(_PS_TMP_IMG_DIR_.$name)) {
                    $item['image_size'] = getimagesize(_PS_TMP_IMG_DIR_.$name);
                } else {
                    $item['image_size'] = false;
                }
            }
            
            if (Validate::isLoadedObject($erpSupplier)) {
                if ($erpSupplier->vat_exemption) {
                    $item['tax_rate'] = 0;
                } else {
                    $item['tax_rate'] = $this->getTaxRate($item['id_tax_rules_group']);
                }
            } else {
                $item['tax_rate'] = $this->getTaxRate($item['id_tax_rules_group']);
            }
            $item['in_stock'] = StockAvailable::getQuantityAvailableByProduct((int)$item['id_product'], (int)$item['id_product_attribute'], Context::getContext()->shop->id);

            //$currency = new Currency((int)$prices['id_currency']);
        }

        if ($items) {
            die(Tools::jsonEncode($items));
        }

        die(1);
    }
    
    /**
     * Init the content of change state action
     */
    public function initUpdateErpOrderContent()
    {
        $this->addJqueryPlugin('autocomplete');

        // load supply order
        $id_erp_order = (int)Tools::getValue('id_erp_order', null);
        $this->display = 'edit';
        
        $this->initToolbar();

        if ($id_erp_order != null) {
            $erp_order = new ErpOrder($id_erp_order);
            $currency = new Currency((int)$erp_order->id_currency);

            if (Validate::isLoadedObject($erp_order)) {
                // load products of this order
                $products = $erp_order->getEntries();
                $product_ids = array();

                if (isset($this->order_products_errors) && is_array($this->order_products_errors)) {
                    //for each product in error array, check if it is in products array, and remove it to conserve last user values
                    foreach ($this->order_products_errors as $pe) {
                        foreach ($products as $index_p => $p) {
                            if (($p['id_product'] == $pe['id_product']) && ($p['id_product_attribute'] == $pe['id_product_attribute'])) {
                                unset($products[$index_p]);
                            }
                        }
                    }

                    // then merge arrays
                    $products = array_merge($this->order_products_errors, $products);
                }

                foreach ($products as $key => &$item) {

                    //skip product if quantity ordered is 0
                    if(!$item['id_erp_order_detail']){
                        unset($products[$key]);
                        continue;
                    }

                    // calculate md5 checksum on each product for use in tpl
                    $item['checksum'] = md5(_COOKIE_KEY_.$item['id_product'].'_'.$item['id_product_attribute']);
                    $item['unit_price_te'] = Tools::ps_round($item['unit_price_te'], 2);
                    $item['in_stock'] = StockAvailable::getQuantityAvailableByProduct((int)$item['id_product'], (int)$item['id_product_attribute'], Context::getContext()->shop->id);
                    $this->setProductImageInformations($item);
                    if ($item['image'] != null) {
                        $name = 'product_mini_'.(int)$item['id_product'].(isset($item['id_product_attribute']) ? '_'.(int)$item['id_product_attribute'] : '').'.jpg';
                        // generate image cache, only for back office
                        $item['image_tag'] = ImageManager::thumbnail(_PS_IMG_DIR_.'p/'.$item['image']->getExistingImgPath().'.jpg', $name, 45, 'jpg');
                        if (file_exists(_PS_TMP_IMG_DIR_.$name)) {
                            $item['image_size'] = getimagesize(_PS_TMP_IMG_DIR_.$name);
                        } else {
                            $item['image_size'] = false;
                        }
                    }
                    
                    $barCode = new ErpEan13($item['ean13'], 1, 'barcode_'.$item['ean13'].'.png', 135, 70, false);
                    
                    // add id to ids list
                    $product_ids[] = $item['id_product'].'_'.$item['id_product_attribute'];
                                        
                    $item['location'] = array();
                                
                    $warehouses = Warehouse::getProductWarehouseList((int)$item['id_product'], (int)$item['id_product_attribute'], (int)Context::getContext()->shop->id);

                    if (count($warehouses)) {
                        foreach ($warehouses as $warehouse) {
                            $item['location'][] = array('wh_name' => $warehouse['name'], 'wh_location' => WarehouseProductLocation::getProductLocation((int)$item['id_product'], (int)$item['id_product_attribute'], (int)$warehouse['id_warehouse']));
                            $item['quantity_expected_by_warehouse'][] = array('wh_id' => (int)$warehouse['id_warehouse'], 'wh_name' => $warehouse['name'], 'wh_quantity_expected' => Db::getInstance()->getValue('SELECT `quantity` FROM '._DB_PREFIX_.'erp_order_detail_by_warehouse WHERE `id_erp_order_detail` = '.$item['id_erp_order_detail'].' AND `id_warehouse` = '.(int)$warehouse['id_warehouse']));
                        }
                    }
                }

                $this->tpl_form_vars['products_list'] = $products;
                $this->tpl_form_vars['product_ids'] = implode($product_ids, '|');
                $this->tpl_form_vars['product_ids_to_delete'] = '';
                $this->tpl_form_vars['supplier_id'] = $erp_order->id_supplier;
                $this->tpl_form_vars['currency'] = $currency;
            }
        }
        
        $this->tpl_form_vars['content'] = $this->content;
        $this->tpl_form_vars['token'] = $this->token;
        $this->tpl_form_vars['show_product_management_form'] = true;

        // call parent initcontent to render standard form content
        parent::initContent();
    }

    protected function setProductImageInformations(&$item)
    {
        if (isset($item['id_product_attribute']) && $item['id_product_attribute']) {
            $id_image = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
                SELECT image_shop.id_image
                FROM '._DB_PREFIX_.'product_attribute_image pai'.
                Shop::addSqlAssociation('image', 'pai', true).'
                WHERE id_product_attribute = '.(int)$item['id_product_attribute']
            );
        }

        if (!isset($id_image) || !$id_image) {
            $id_image = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
                SELECT image_shop.id_image
                FROM '._DB_PREFIX_.'image i'.
                Shop::addSqlAssociation('image', 'i', true, 'image_shop.cover=1').'
                WHERE i.id_product = '.(int)$item['id_product']
            );
        }

        $item['image'] = null;
        $item['image_size'] = null;

        if ($id_image) {
            $item['image'] = new Image($id_image);
        }
    }

    public function fetchTemplate($path, $name, $extension = false)
    {
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'wic_erp'.$path.$name.'.'.($extension ? $extension : 'tpl'));
    }

    public function processGenerateErpOrderFormPDF()
    {
        if (!Tools::isSubmit('id_erp_order')) {
            die(Tools::displayError('Missing supply order ID'));
        }

        $id_erp_order = (int)Tools::getValue('id_erp_order');
        $erp_order = new ErpOrder($id_erp_order);
        
        if (!Validate::isLoadedObject($erp_order)) {
            die(Tools::displayError('Cannot find this Erp order in the database'));
        }
        
        if (Tools::isSubmit('generateErpOrderFormPDF')) {
            $this->generatePDF($erp_order, 'ErpOrderForm');
        } elseif (Tools::isSubmit('generateErpOrderFormPDFMin')) {
            $this->generatePDF($erp_order, 'ErpOrderFormMin');
        }
    }

    public function generatePDF($object, $template)
    {
        $pdf = new PDF($object, $template, Context::getContext()->smarty);
        $pdf->render();
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
        unset($this->context->cookie->erp_supplier_order_state);
    }

    public function productImage($content)
    {
        return $content;
    }
    
    public function productEANCode($content)
    {
        $ean13 = $content;
        $content = '';
        if ($ean13) {
            $barCode = new ErpEan13($ean13, 1, 'barcode_'.$ean13.'.png', 135, 70, true);
            $content = '<img src="'.__PS_BASE_URI__.'modules/wic_erp/views/img/barcode/barcode_'.$ean13.'.png">';
        }
        //$content .= $ean13;
        return $content;
    }

    public function productInStock($content)
    {
        $badge = '<span class="badge badge-success">';
        if ($content <= 0) {
            $badge = '<span class="badge badge-danger">';
        }
        return $badge.$content.'</span>';
    }
    
    public function productLeftQuantity($content)
    {
        $badge = '<span class="badge badge-success complete">';
        if ($content > 0) {
            $badge = '<span class="badge badge-danger">';
        }
        return $badge.$content.'</span>';
    }

    public function getAllOrder()
    {
        $query = 'SELECT
						COUNT(eo.id_erp_order)
					FROM
						`'._DB_PREFIX_.'erp_order` eo
					LEFT JOIN `'._DB_PREFIX_.'erp_order_state_lang` stl ON
						(
							eo.id_erp_order_state = stl.id_erp_order_state
							AND stl.id_lang = '.(int)$this->context->language->id.'
						)
					LEFT JOIN `'._DB_PREFIX_.'erp_order_state` st ON eo.id_erp_order_state = st.id_erp_order_state
					LEFT JOIN `'._DB_PREFIX_.'supplier` s ON eo.id_supplier = s.id_supplier';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }

    public function getProgressOrder()
    {
        $query = 'SELECT
						COUNT(eo.id_erp_order)
					FROM
						`'._DB_PREFIX_.'erp_order` eo
					LEFT JOIN `'._DB_PREFIX_.'erp_order_state_lang` stl ON
						(
							eo.id_erp_order_state = stl.id_erp_order_state
							AND stl.id_lang = '.(int)$this->context->language->id.'
						)
					LEFT JOIN `'._DB_PREFIX_.'erp_order_state` st ON eo.id_erp_order_state = st.id_erp_order_state
					LEFT JOIN `'._DB_PREFIX_.'supplier` s ON eo.id_supplier = s.id_supplier
					WHERE stl.`id_erp_order_state` = 1';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }

    public function getValidatedOrder()
    {
        $query = 'SELECT
						COUNT(eo.id_erp_order)
					FROM
						`'._DB_PREFIX_.'erp_order` eo
					LEFT JOIN `'._DB_PREFIX_.'erp_order_state_lang` stl ON
						(
							eo.id_erp_order_state = stl.id_erp_order_state
							AND stl.id_lang = '.(int)$this->context->language->id.'
						)
					LEFT JOIN `'._DB_PREFIX_.'erp_order_state` st ON eo.id_erp_order_state = st.id_erp_order_state
					LEFT JOIN `'._DB_PREFIX_.'supplier` s ON eo.id_supplier = s.id_supplier
					WHERE stl.`id_erp_order_state` = 2';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }

    public function getPendingOrder()
    {
        $query = 'SELECT
						COUNT(eo.id_erp_order)
					FROM
						`'._DB_PREFIX_.'erp_order` eo
					LEFT JOIN `'._DB_PREFIX_.'erp_order_state_lang` stl ON
						(
							eo.id_erp_order_state = stl.id_erp_order_state
							AND stl.id_lang = '.(int)$this->context->language->id.'
						)
					LEFT JOIN `'._DB_PREFIX_.'erp_order_state` st ON eo.id_erp_order_state = st.id_erp_order_state
					LEFT JOIN `'._DB_PREFIX_.'supplier` s ON eo.id_supplier = s.id_supplier
					WHERE stl.`id_erp_order_state` = 3';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }

    public function getReceivedInPartOrder()
    {
        $query = 'SELECT
						COUNT(eo.id_erp_order)
					FROM
						`'._DB_PREFIX_.'erp_order` eo
					LEFT JOIN `'._DB_PREFIX_.'erp_order_state_lang` stl ON
						(
							eo.id_erp_order_state = stl.id_erp_order_state
							AND stl.id_lang = '.(int)$this->context->language->id.'
						)
					LEFT JOIN `'._DB_PREFIX_.'erp_order_state` st ON eo.id_erp_order_state = st.id_erp_order_state
					LEFT JOIN `'._DB_PREFIX_.'supplier` s ON eo.id_supplier = s.id_supplier
					WHERE stl.`id_erp_order_state` = 4';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }

    public function getCompleteOrder()
    {
        $query = 'SELECT
						COUNT(eo.id_erp_order)
					FROM
						`'._DB_PREFIX_.'erp_order` eo
					LEFT JOIN `'._DB_PREFIX_.'erp_order_state_lang` stl ON
						(
							eo.id_erp_order_state = stl.id_erp_order_state
							AND stl.id_lang = '.(int)$this->context->language->id.'
						)
					LEFT JOIN `'._DB_PREFIX_.'erp_order_state` st ON eo.id_erp_order_state = st.id_erp_order_state
					LEFT JOIN `'._DB_PREFIX_.'supplier` s ON eo.id_supplier = s.id_supplier
					WHERE stl.`id_erp_order_state` = 5';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }

    public function getCancelledOrder()
    {
        $query = 'SELECT
						COUNT(eo.id_erp_order)
					FROM
						`'._DB_PREFIX_.'erp_order` eo
					LEFT JOIN `'._DB_PREFIX_.'erp_order_state_lang` stl ON
						(
							eo.id_erp_order_state = stl.id_erp_order_state
							AND stl.id_lang = '.(int)$this->context->language->id.'
						)
					LEFT JOIN `'._DB_PREFIX_.'erp_order_state` st ON eo.id_erp_order_state = st.id_erp_order_state
					LEFT JOIN `'._DB_PREFIX_.'supplier` s ON eo.id_supplier = s.id_supplier
					WHERE stl.`id_erp_order_state` = 6';

        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }

    public function getTaxRate($id_tax_rules_group)
    {
        $id_tax = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT DISTINCT(`id_tax`) FROM `'._DB_PREFIX_.'tax_rule` WHERE `id_tax_rules_group` = '.(int)$id_tax_rules_group);

        if ($id_tax) {
            $tax = new Tax($id_tax);
            if (Validate::isLoadedObject($tax)) {
                return $tax->rate;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    public function sendSupplierOrder($erp_order)
    {
        if (Validate::isLoadedObject($erp_order)) {
            $id_erp_supplier = ErpSuppliers::getSupplierById((int)$erp_order->id_supplier);
            $supplier = new ErpSuppliers((int)$id_erp_supplier);

            if (Configuration::get('WIC_ERP_SEND_EMAIL_SUPPLIER') && Validate::isLoadedObject($supplier) && $supplier->email) {
                $iso = Language::getIsoById($supplier->id_lang);
                $template = 'new_supplier_order';
                $txt_file = dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.txt';
                $html_file = dirname(__FILE__).'/../../mails/'.$iso.'/'.$template.'.html';
                $subject = array('en'=>'New supply order', 'fr'=>'Nouvelle commande fournisseur');
                $subject = isset($subject[$iso]) ? $subject[$iso] : $subject['en'];

                $template_vars = array(
                                '{supplier_name}' => $erp_order->supplier_name,
                                '{shop_name}' => Configuration::get('PS_SHOP_NAME'),
                                '{supplier_order}' => $erp_order->reference
                );

                $details = $erp_order->getEntriesCollection();
                $details->getAll();

                $file_attachement = array();

                if (!Configuration::get('WIC_ERP_SEND_ONLY_PDF')) {
                    $csv = new CSV($details, $this->l('erp_order').'_'.$erp_order->reference.'_details');

                    $header_line = false;
                    //Generate CSV

                    ob_start();
                    foreach ($csv->collection as $object) {
                        $vars = get_object_vars($object);
                        if (!$header_line) {
                            $wraped_data = array_map(array('CSVCore', 'wrap'), array_keys($vars));
                            echo sprintf("%s\n", implode($csv->delimiter, $wraped_data));
                            $header_line = true;
                        }

                        $wraped_data = array_map(array('CSVCore', 'wrap'), $vars);
                        echo sprintf("%s\n", implode($csv->delimiter, $wraped_data));

                        unset($vars);
                    }

                    $csv_file = ob_get_contents();
                    ob_end_clean();
                    //End generate CSV

                    $file_attachement[] = array(
                                    'content' => $csv_file,
                                    'name' => 'CSV_'.$erp_order->reference.'.csv',
                                    'mime' =>  'text/csv',
                    );
                }

                $pdf = new PDF($erp_order, 'ErpOrderForm', Context::getContext()->smarty);

                $file_attachement[] = array(
                                'content' => $pdf->render(false),
                                'name' => 'PDF_'.$erp_order->reference.'.pdf',
                                'mime' =>  'application/pdf',
                );

                if (file_exists($txt_file) && file_exists($html_file)) {
                    $emails = explode(',', $supplier->email);
                    foreach ($emails as $email) {
                        Mail::Send(Context::getContext()->language->id, $template, $subject, $template_vars, trim($email), null, Configuration::get('PS_SHOP_EMAIL'), Configuration::get('PS_SHOP_NAME'), $file_attachement, null, dirname(__FILE__).'/../../mails/');
                    }
                }

                return true;
            }
        }
    }
    
    public function displayFileName($name)
    {
        return '<a href="../modules/wic_erp/upload/'.$name.'" target="_blank"><i class="icon-file-text"></i> '.$name.'</a>';
    }
    
    public function getProductDlcDisplay($product_dlc_group_id, $product_dlc_id)
    {
        $this->context->smarty->assign(array('product_dlc_group_id' => $product_dlc_group_id, 'product_dlc_id' => $product_dlc_id));
        return $this->fetchTemplate('/views/templates/admin/erp_orders_receipt_history/helpers/list/', 'dlc_row');
    }
    
    public function detailsButton($id_erp_order_detail) {
        $content = '<a onclick="display_action_details('.(int)$id_erp_order_detail.', \'AdminErpSupplierOrders\', \''.$this->token.'\', \'details\', {\'display_product_history\':1,\'action\':\'details\'}); return false;" id="details_details_'.(int)$id_erp_order_detail.'" title="Détails" class="pointer btn btn-default">';
	$content .= '<i class="icon-eye-open"></i> '.$this->l('Details').'</a>';
        
        return $content;
    }
    
    public function getNumberOfProduct($numberOfProducts, $value)
    {
        $query = 'SELECT
                        COUNT(eod.id_erp_order_detail)
                FROM
                        `'._DB_PREFIX_.'erp_order_detail` eod
                WHERE eod.`id_erp_order` = '.$value['id_erp_order'];

        $number = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
        
        $content = '<span class="badge badge-info">';
        $content .= $number;
        $content .= '</span>';
        return $content;
    }
    
    public function getSupplierQuantityToPurchase($id_erp_supplier, $value) {
    
        $erp_supplier = new ErpSuppliers((int)$value['id_erp_suppliers']);

        if (Validate::isLoadedObject($erp_supplier)) {
            
            /* We retrieve product to order */
            /* gets products */
           $query = new DbQuery();
            $query->select('ep.id_product,
                            ep.id_product_attribute'
                            );
            if (Configuration::get('WIC_ERP_STOCK_MANAGEMENT') == 'normal') {
                $query->select('(ep.`min_quantity`+ep.`safety_stock`-sa.`quantity`) as `stock_to_order`');
            } else {
                $query->select('SUM(od.`product_quantity`-od.`product_quantity_refunded`)-SUM(od.`product_quantity_in_stock`) as `stock_to_order`');
            }

            $query->from('erp_products', 'ep');
            $query->leftJoin('stock_available', 'sa', '
                            sa.id_product = ep.id_product
                            AND sa.id_product_attribute = ep.id_product_attribute');

            $query->leftJoin('product', 'p', 'p.id_product = ep.id_product');
            $query->innerJoin('product_supplier', 'ps', 'ps.id_product = ep.id_product AND ps.id_product_attribute = ep.id_product_attribute AND ps.id_supplier = '.(int)$erp_supplier->id_supplier);
            if (Configuration::get('WIC_ERP_STOCK_MANAGEMENT') != 'normal') {
                $query->leftJoin('order_detail', 'od', 'od.product_id = ep.id_product AND od.product_attribute_id = ep.id_product_attribute AND od.id_order IN (SELECT o.`id_order` FROM `'._DB_PREFIX_.'orders` o WHERE o.`current_state` IN ('.pSQL(Configuration::get('WIC_ERP_NOT_COMPLETE')).') )');
            }

            $query->leftJoin('product_attribute', 'pa', '
                            pa.id_product_attribute = ep.id_product_attribute
                            AND pa.id_product = ep.id_product
                    ');
            $query->leftJoin('product_shop', 'psh', '
                            psh.`id_product` = ep.id_product
                            AND psh.id_shop = '.(int)Context::getContext()->shop->id.'
                    ');
            if (!configuration::get('WIC_ERP_DISABLED_PRODUCT')) {
                $query->where('p.`active` = 1');
            }
            
            $query->groupBy('ep.id_product,
                            ep.id_product_attribute');
            $query->having('stock_to_order > 0');

            /* gets items */
            $items = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
            
            /* We retrieve erp order in progress */
            $query = new DbQuery();
            $query->select('eos.`id_erp_order_state`');
            $query->from('erp_order_state', 'eos');
            $query->where('eos.`delivery_note` = 1 OR ( eos.`pending_receipt` = 1 AND receipt_state != 1)');

            $id_status = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
       
            if ($id_status) {
                $status = array();

                foreach ($id_status as $id_state) {
                    $status[] = $id_state['id_erp_order_state'];
                }

                $query = new DbQuery();
                $query->select('eo.`id_erp_order`');
                $query->from('erp_order', 'eo');
                $query->where('id_erp_order_state IN('.pSQL(implode(',', $status)).')');

                $orders = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
                
                if ($orders) {
                    $order_progress = array();
                    foreach ($orders as $order) {
                        $order_progress[] = $order['id_erp_order'];
                    }
                }
            }

            $global_quantity = 0;
            $global_price = 0;
            
            $product_to_order = count($items);
            
            $qty_expected = 0;
            if (isset($order_progress) && count($order_progress) > 0) {
                if ($items) {
                    foreach ($items as $item) {
                        $query = new DbQuery();
                        $query->select('COUNT(eod.id_erp_order_detail)');
                        $query->from('erp_order_detail', 'eod');
                        $query->where('eod.`id_erp_order` IN ('.pSQL(implode(',', $order_progress)).')');
                        $query->where('eod.`id_product` = '.$item['id_product']);
                        $query->where('eod.`id_product_attribute` = '.$item['id_product_attribute']);
                        $qty_expected += Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
                    }
                }
            }
            
            $product_to_order = $product_to_order - $qty_expected;
            $content = '<span class="badge badge-info product_to_order" style="padding: 5px 10px;border-radius: 0;width: 50px;text-align: center;">';
            $content .= $product_to_order;
            $content .= '</span>';
            return $content;
        }
    }
    
    public function getSupplierTotalQuantityToPurchase($id_erp_supplier, $value)
    {
        $erp_supplier = new ErpSuppliers((int)$value['id_erp_suppliers']);

        if (Validate::isLoadedObject($erp_supplier)) {
            /* We retrieve product to order */
            /* gets products */
            $query = new DbQuery();
            $query->select('ep.id_product,
                            ep.id_product_attribute'
                            );
            if (Configuration::get('WIC_ERP_STOCK_MANAGEMENT') == 'normal') {				
                $query->select('(ep.`min_quantity`+ep.`safety_stock`- SUM(sa.`quantity`))   as `stock_to_order`');
                $query->select('(SUM(sa.`quantity`)-ep.`min_quantity`) as `stock_orderable`');               
            } else {
                $query->select('SUM(od.`product_quantity`-od.`product_quantity_refunded`)-SUM(od.`product_quantity_in_stock`) as `stock_to_order`');
            }
            $query->from('erp_products', 'ep');
            $query->leftJoin('stock_available', 'sa', '
                            sa.id_product = ep.id_product
                            AND sa.id_product_attribute = ep.id_product_attribute');					
							
            $query->leftJoin('product', 'p', 'p.id_product = ep.id_product');
            $query->innerJoin('product_supplier', 'ps', 'ps.id_product = ep.id_product AND ps.id_product_attribute = ep.id_product_attribute AND ps.id_supplier = '.(int)$erp_supplier->id_supplier);
            if (Configuration::get('WIC_ERP_STOCK_MANAGEMENT') != 'normal') {
                $query->leftJoin('order_detail', 'od', 'od.product_id = ep.id_product AND od.product_attribute_id = ep.id_product_attribute AND od.id_order IN (SELECT o.`id_order` FROM `'._DB_PREFIX_.'orders` o WHERE o.`current_state` IN ('.pSQL(Configuration::get('WIC_ERP_NOT_COMPLETE')).') )');
            }

            $query->leftJoin('product_attribute', 'pa', '
                            pa.id_product_attribute = ep.id_product_attribute
                            AND pa.id_product = ep.id_product
                    ');
            $query->leftJoin('product_shop', 'psh', '
                            psh.`id_product` = ep.id_product
                            AND psh.id_shop = '.(int)Context::getContext()->shop->id.'
                    ');
            if (!configuration::get('WIC_ERP_DISABLED_PRODUCT')) {
                $query->where('p.`active` = 1');
            }
            
            $query->groupBy('ep.id_product,ep.id_product_attribute');         
            $query->having('stock_to_order > 0');
            
            /* gets items */
            $items = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
            
            $stock_orderable = 0;
            $stock_to_order = 0;
            $stock_to_order_summed = 0;
            $stock_to_order_nap = 0;
            $stock_to_order_bout = 0;
            if ($items) {
                foreach ($items as $item) {
                    $stock_to_order_summed += $item["stock_to_order"];		
                }
            }
            $total=$stock_to_order_nap+$stock_to_order_bout;
            $content = '<span class="badge badge-info stock_to_order_summed" style="background: #10dd00;padding: 5px 10px;border-radius: 0;width: 50px;text-align: center;">';
            $content .= $stock_to_order_summed;			
            $content .= '</span>';
            return $content;			
        }		
    }
    
    public function getSupplierQuantityToPurchaseButton($id_erp_supplier, $value)
    {
        $erp_supplier = new ErpSuppliers((int)$value['id_erp_suppliers']);
        if (Validate::isLoadedObject($erp_supplier)) {
            $content = '<span class="badge-info order">';
            $content .= '<a href="'.$this->context->link->getAdminLink('AdminErpSupplierOrders').'&submitAdderp_order&id_supplier='. (int)$erp_supplier->id_supplier .'&generate=1" class="btn btn-default"><i class="icon-shopping-cart"></i> '.$this->l('Create Order').'</a>';
            $content .= '</span>';
            
            return $content;
        }
    }
        
    protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
    	if (version_compare(_PS_VERSION_, '1.7', '<')) {
            return parent::l($string, $class, $addslashes, $htmlentities);
    	} else {
            return Translate::getModuleTranslation('wic_erp', $string, 'AdminErpSupplierOrdersController');
    	}
    }
}