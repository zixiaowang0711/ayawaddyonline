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

class AdminErpProductOrderController extends ModuleAdminController
{
    public $toolbar_title;

    public function __construct()
    {
        $this->context = Context::getContext();
        $this->lang = false;
        $this->explicitSelect = false;
        $this->list_no_link = true;
        $this->bootstrap = true;
        $this->deleted = false;
        $this->multishop_context = Shop::CONTEXT_ALL;
        //$this->initShopContext($this->context);
        $this->toolbar_title = $this->l('My Easy ERP: Products order');
        $this->list_id = 'erp_products';

        if (Configuration::get('WIC_ERP_STOCK_MANAGEMENT') == 'normal') {
            $this->table = 'erp_products';
            $this->className = 'ErpProducts';
            $this->list_id = 'erp_products';

            if (Tools::isSubmit('submitReseterp_products')) {
                $this->processResetFilters();
            }

            $this->_select .= '
			a.`min_quantity` as `min_quantity`,
			a.`safety_stock` as `safety_stock`,
			sa.`quantity` as real_stock,
			(a.`min_quantity`+a.`safety_stock`) as `order_point`,
			(a.`min_quantity`+a.`safety_stock`-sa.`quantity`)-IFNULL(SUM(CAST(eod.`quantity_ordered` as SIGNED)-CAST(eod.`quantity_received` as SIGNED)),0) as `stock_to_order`,
			IF(a.`id_product_attribute` != 0, CONCAT(a.`id_product`, \'_\', a.`id_product_attribute`), a.`id_product`) AS `id_product_ref`,
			IF(a.`id_product_attribute` != 0, CONCAT(a.`id_product`, \'_\', a.`id_product_attribute`), a.`id_product`) AS `id_product_icon`,
			IF(p.`reference` != \'\',p.`reference` ,\'--\') AS `ref`,
			IF(pa.`reference` != \'\',pa.`reference` ,p.`reference`) AS `refAttr`,
			CAST(IFNULL(SUM(CAST(eod.`quantity_ordered` as SIGNED)-CAST(eod.`quantity_received` as SIGNED)),0) as SIGNED) as `stock_expected`,
			a.`id_product` as `image`,
			sp.`name` as `supplier_name`,
			pl.`name` as name
			';

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
            
            if ($show_quantities) {
                //Define shop context
                $cookie = Context::getContext()->cookie->getFamily('shopContext');
                if (substr($cookie['shopContext'], 0, 1) == 's') {
                     $id_shop = (int)substr($cookie['shopContext'], 2, strlen($cookie['shopContext']));
                     $query_shop = ' = '.$id_shop;
                } elseif (substr($cookie['shopContext'], 0, 1) == 'g') {
                    $id_shop_group = (int)substr($cookie['shopContext'], 2, strlen($cookie['shopContext']));
                    //we retrieve all shop in this group
                    $shops = ShopGroup::getShopsFromGroup($id_shop_group);
                    $array_shop = array();
                    foreach ($shops as $shop) {
                        $array_shop[] = (int)$shop['id_shop'];
                    }
                    $query_shop = ' IN ('.pSQL(implode(',', $array_shop)).')';
                } else {
                    $shops = Shop::getShops();
                    $array_shop = array();
                    foreach ($shops as $shop) {
                        $array_shop[] = (int)$shop['id_shop'];
                    }
                    $query_shop = ' IN ('.pSQL(implode(',', $array_shop)).', 0)';
                }
            } else {
                if (isset($this->context->shop->id) && $this->context->shop->id) {
                    $query_shop = ' = '.(int)$this->context->shop->id;
                } elseif (isset($this->context->shop->id_shop_group)) {
                    $id_shops = ShopGroup::getShopsFromGroup($this->context->shop->id_shop_group);

                    $array_shop = array();
                    foreach ($id_shops as $key => $value) {
                        $array_shop[] = (int)$value['id_shop'];
                    }

                    $query_shop = ' IN ('.pSQL(implode(',', $array_shop)).')';
                } else {
                    $query_shop = ' = '.(int)Configuration::get('PS_SHOP_DEFAULT');
                }
            }
            
            $this->_join = ' JOIN `'._DB_PREFIX_.'product_shop` ps ON (a.`id_product` = ps.`id_product` AND ps.`id_shop`'.$query_shop.')';
            $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'stock_available` sa ON (sa.`id_product` = a.`id_product` AND sa.`id_product_attribute` = a.`id_product_attribute`)
                                                        LEFT JOIN `'._DB_PREFIX_.'shop` shop ON (shop.id_shop'.$query_shop.') 
                                                        LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.`id_product` = a.`id_product`)
                                                        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (pl.`id_product` = a.`id_product` AND pl.`id_lang` = '.(int)$this->context->language->id.')
                                                        LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pa.`id_product` = a.`id_product` AND pa.`id_product_attribute` = a.`id_product_attribute`)
                                                        LEFT JOIN `'._DB_PREFIX_.'erp_order_detail` eod ON (eod.`id_product` = a.`id_product` AND eod.`id_product_attribute` = a.`id_product_attribute` AND eod.`id_erp_order` IN (SELECT eo.`id_erp_order` FROM `'._DB_PREFIX_.'erp_order` eo WHERE eo.`id_erp_order_state` IN (SELECT eos.`id_erp_order_state` FROM `'._DB_PREFIX_.'erp_order_state` eos WHERE eos.`enclosed` = 0 AND eos.`editable` = 0)))';
            
            $this->_group = 'GROUP BY a.`id_product`,a.`id_product_attribute`';
            $this->_having = '`stock_to_order` > 0';
            $this->identifier = 'id_erp_products';

            if (!configuration::get('WIC_ERP_DISABLED_PRODUCT')) {
                $this->_where .= ' AND p.`active` = 1';
            }

            $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` '.(!Shop::isFeatureActive() ? ' AND i.cover=1' : '').')';
            $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop ON (image_shop.`id_image` = i.`id_image` AND image_shop.`cover` = 1 AND image_shop.`id_shop`'.$query_shop.')';
            $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'supplier` sp ON (p.`id_supplier` = sp.`id_supplier`)';
            $this->_where .= ' AND (i.id_image IS NULL OR image_shop.`id_shop`'.$query_shop.')';
        } else {
            $this->table = 'order_detail';
            $this->className = 'OrderDetail';

            if (Tools::isSubmit('submitReseterp_products')) {
                $this->processResetFilters();
            }

            $this->_join = 'LEFT JOIN `'._DB_PREFIX_.'orders` o ON (o.`id_order` = a.`id_order`)
							LEFT JOIN `'._DB_PREFIX_.'product` p ON (p.`id_product` = a.`product_id`)
							LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (pa.`id_product` = a.`product_id` AND pa.`id_product_attribute` = a.`product_attribute_id`)
							LEFT JOIN `'._DB_PREFIX_.'erp_order_detail` eod ON (eod.`id_product` = a.`product_id` AND eod.`id_product_attribute` = a.`product_attribute_id` AND eod.`id_erp_order` IN (SELECT eo.`id_erp_order` FROM `'._DB_PREFIX_.'erp_order` eo WHERE eo.`id_erp_order_state` IN (SELECT eos.`id_erp_order_state` FROM `'._DB_PREFIX_.'erp_order_state` eos WHERE eos.`enclosed` = 0 AND eos.`editable` = 0)))
							';

            if (Configuration::get('WIC_ERP_NOT_COMPLETE')) {
                $this->_where .= ' AND o.`current_state` IN ('.pSQL(Configuration::get('WIC_ERP_NOT_COMPLETE')).')';
            }
            if (!configuration::get('WIC_ERP_DISABLED_PRODUCT')) {
                $this->_where .= ' AND p.`active` = 1';
            }
            $this->_group = 'GROUP BY a.`product_id`,a.`product_attribute_id`';

            $this->_select = '
			SUM(a.`product_quantity`-a.`product_quantity_refunded`) as `total_ordered`,
			SUM(a.`product_quantity_in_stock`) as `stock_reserved`,
			SUM(a.`product_quantity`-a.`product_quantity_refunded`)-SUM(a.`product_quantity_in_stock`)-IFNULL(SUM(eod.`quantity_ordered`-eod.`quantity_received`),0) as `stock_to_order`,
			IF(a.`product_attribute_id` != 0, CONCAT(a.`product_id`, \'_\', a.`product_attribute_id`), a.`product_id`) AS `id_product_ref`,
			IFNULL(SUM(eod.`quantity_ordered`-eod.`quantity_received`),0) as `stock_expected`,
			IF(p.`reference` != \'\',p.`reference` ,\'--\') AS `ref`,
			IF(pa.`reference` != \'\',pa.`reference` ,\'--\') AS `refAttr`,
			a.`product_id` as `image`,
			sp.`name` as `supplier_name`
			';
            $this->_orderBy = 'p.reference';
            $this->identifier = 'id_product_ref';
            $this->_having = '`stock_to_order` > 0';

            $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = p.`id_product` '.(!Shop::isFeatureActive() ? ' AND i.cover=1' : '').')';
            $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop ON (image_shop.`id_image` = i.`id_image` AND image_shop.`cover` = 1 AND image_shop.id_shop='.(int)$this->context->shop->id.')';
            $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'supplier` sp ON (p.`id_supplier` = sp.`id_supplier`)';
            $this->_where .= ' AND (i.id_image IS NULL OR image_shop.id_shop='.(int)$this->context->shop->id.')';
        }

        $id_suppliers = Tools::getValue('erp_productsFilter_Supplier');

        if (!is_array($id_suppliers) && isset($this->context->cookie->Filter_Suppliers) && $this->context->cookie->Filter_Suppliers) {
            $id_suppliers = explode(',', $this->context->cookie->Filter_Suppliers);
        }
                
        if (is_array($id_suppliers)) {
            $this->_where .= ' AND p.`id_supplier` IN ('.pSQL(implode(',', $id_suppliers)).')';
        }

        $this->fields_list = array();

        if (Configuration::get('WIC_ERP_STOCK_MANAGEMENT') == 'normal') {
            $this->fields_list['id_erp_products'] = array(
                'title' => $this->l('ID'),
                'align' => 'left',
                'width' => 15
            );

            $this->fields_list['image'] = array(
                'title' => $this->l('Photo'),
                'align' => 'center',
                'width' => 70,
                'orderby' => false,
                'filter' => false,
                'search' => false,
                'callback' => 'productImage',
            );

            $this->fields_list['refAttr'] = array(
                'title' => $this->l('Reference features'),
                'align' => 'left',
                'width' => 65,
                'filter_key' => 'pa!reference'
            );

            $this->fields_list['id_product_ref'] = array(
                'title' => $this->l('Name'),
                'align' => 'left',
                'callback' => 'productName',
                'width' => 125,
                'filter_key' => 'pl!name'
            );

            $this->fields_list['supplier_name'] = array(
                'title' => $this->l('Supplier'),
                'align' => 'left',
                'width' => 100,
                'filter_key' => 'sp!name'
            );

            $this->fields_list['min_quantity'] = array(
                'title' => $this->l('Min quantity'),
                'align' => 'center',
                'width' => 15,
                'hint' => $this->l('(1)'),
            );

            $this->fields_list['safety_stock'] = array(
                'title' => $this->l('Safety stock'),
                'align' => 'center',
                'width' => 15,
                'hint' => $this->l('(2)'),
            );

            $this->fields_list['order_point'] = array(
                'title' => $this->l('Order point'),
                'align' => 'center',
                'width' => 15,
                'hint' => $this->l('(1)+(2)=(3)'),
            );

            $this->fields_list['real_stock'] = array(
                'title' => $this->l('Real stock'),
                'align' => 'center',
                'width' => 15,
                'callback' => 'realStock',
                'hint' => $this->l('(4)'),
                'filter_key' => 'sa!quantity'
            );
        } else {
            $this->fields_list['id_product_ref'] = array(
                'title' => $this->l('ID'),
                'align' => 'left',
                'width' => 15
            );

            $this->fields_list['image'] = array(
                'title' => $this->l('Photo'),
                'align' => 'center',
                'width' => 70,
                'orderby' => false,
                'filter' => false,
                'search' => false,
                'callback' => 'productImage',
            );

            $this->fields_list['refAttr'] = array(
                'title' => $this->l('Ref. features'),
                'align' => 'left',
                'width' => 65,
                'filter_key' => 'pa!reference'
            );
            $this->fields_list['product_name'] = array(
                'title' => $this->l('Name'),
                'align' => 'left',
                'width' => 125,
                'filter_key' => 'a!name'
            );

            $this->fields_list['supplier_name'] = array(
                    'title' => $this->l('Supplier'),
                    'align' => 'left',
                    'width' => 100,
                    'filter_key' => 'sp!name'
            );

            $this->fields_list['total_ordered'] = array(
                'title' => $this->l('Product purchased'),
                'align' => 'center',
                'width' => 65
            );
            $this->fields_list['stock_reserved'] = array(
                'title' => $this->l('Stock reserved'),
                'align' => 'center',
                'width' => 65
            );
        }

        if (Configuration::get('WIC_ERP_STOCK_MANAGEMENT') == 'normal') {
            $this->fields_list['stock_expected'] = array(
                'title' => $this->l('Stock expected'),
                'align' => 'center',
                'width' => 35,
                'hint' => $this->l('(5)'),
            );

            $this->fields_list['stock_to_order'] = array(
                'title' => $this->l('Stock to order'),
                'align' => 'center',
                'width' => 35,
                'callback' => 'stockPurchased',
                'hint' => $this->l('(3)-(4)-(5)'),
            );

            $this->fields_list['id_product_icon'] = array(
                'title' => $this->l('Info product'),
                'width' => 80,
                'callback' => 'printProductIcons',
                'orderby' => false,
                'search' => false
            );
        } else {
            $this->fields_list['stock_expected'] = array(
                'title' => $this->l('Stock expected'),
                'align' => 'center',
                'width' => 35
            );

            $this->fields_list['stock_to_order'] = array(
                'title' => $this->l('Stock to order'),
                'align' => 'center',
                'width' => 35,
                'callback' => 'stockPurchased',
            );
        }
        parent::__construct();
    }

    /*public function initShopContext()
    {
        $this->context = Context::getContext();

        if (Tools::getValue('setShopContext') !== false || (isset($this->context->cookie->shopContext) && $this->context->cookie->shopContext)) {
            if (Tools::getValue('setShopContext')) {
                $this->context->cookie->shopContext = Tools::getValue('setShopContext');
            }

            $shop_id = '';

            if ($this->context->cookie->shopContext) {
                $split = explode('-', $this->context->cookie->shopContext);
                if (count($split) == 2) {
                    if ($split[0] == 'g') {
                        if ($this->context->employee->hasAuthOnShopGroup($split[1])) {
                            Shop::setContext(Shop::CONTEXT_GROUP, $split[1]);
                        } else {
                            $shop_id = $this->context->employee->getDefaultShopID();
                            Shop::setContext(Shop::CONTEXT_SHOP, $shop_id);
                        }
                    } elseif (Shop::getShop($split[1]) && $this->context->employee->hasAuthOnShop($split[1])) {
                        $shop_id = $split[1];
                        Shop::setContext(Shop::CONTEXT_SHOP, $shop_id);
                    } else {
                        $shop_id = $this->context->employee->getDefaultShopID();
                        Shop::setContext(Shop::CONTEXT_SHOP, $shop_id);
                    }
                }
            }

            // Check multishop context and set right context if need
            if (!($this->multishop_context & Shop::getContext())) {
                if (Shop::getContext() == Shop::CONTEXT_SHOP && !($this->multishop_context & Shop::CONTEXT_SHOP)) {
                    Shop::setContext(Shop::CONTEXT_GROUP, Shop::getContextShopGroupID());
                }
                if (Shop::getContext() == Shop::CONTEXT_GROUP && !($this->multishop_context & Shop::CONTEXT_GROUP)) {
                    Shop::setContext(Shop::CONTEXT_ALL);
                }
            }

            // Replace existing shop if necessary
            if (!$shop_id) {
                $this->context->shop->id = '';
            } elseif ($this->context->shop->id != $shop_id) {
                $this->context->shop = new Shop($shop_id);
            }
        }
    }*/

    public function initContent()
    {
        // ==============================
        // suppliers
        // ==============================
        $id_supplier = Tools::getValue($this->list_id.'Filter_Supplier');
        
        $array_tmp = array();
        
        if (!$id_supplier && isset($this->context->cookie->Filter_Suppliers) && $this->context->cookie->Filter_Suppliers) {
            $id_supplier = explode(',', $this->context->cookie->Filter_Suppliers);
        }
        
        $suppliers = Supplier::getSuppliers();
        foreach ($suppliers as &$s) {
            $s['is_selected'] = 0;
            if (is_array($id_supplier) && in_array($s['id_supplier'], $id_supplier)) {
                $s['is_selected'] = 1;
                $array_tmp[] = $s['id_supplier'];
            }
        }
        $this->context->cookie->Filter_Suppliers = implode(',', $array_tmp);
        $this->context->cookie->Filter_id_Suppliers = (is_array($id_supplier) ? 1 : 0);
        
        $this->tpl_list_vars['suppliers'] = $suppliers;

        $this->tpl_list_vars['is_supplier_filter'] = $this->context->cookie->Filter_id_Suppliers;

        parent::initContent();
    }

    public function getList($id_lang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        
        if (Tools::getValue('action') == 'details') {
            $orderBy = 'id_order';
        }

        parent::getList($id_lang, $orderBy, $orderWay, $start, $limit, $this->context->shop->id);

        if (Configuration::get('WIC_ERP_STOCK_MANAGEMENT') == 'normal' && !Tools::getValue('id')) {
            $nb = count($this->_list);
            if ($this->_list) {
                /* update product final price */
                for ($i = 0; $i < $nb; $i++) {
                    // convert price with the currency from context
                    if ($this->_list[$i]['stock_to_order'] <= 0) {
                        unset($this->_list[$i]);
                    }
                }
            }
        }
    }
    /**
     * AdminController::renderList() override
     * @see AdminController::renderList()
     */
    public function renderList()
    {
        $this->addRowAction('details');

        if (Tools::isSubmit('submitFilter')) {
            parent::processFilter();
        }

        return parent::renderList();
    }

    /**
     * method call when ajax request is made with the details row action
     * @see AdminController::postProcess()
     */
    public function ajaxProcessDetails()
    {
        if (($id = Tools::getValue('id'))) {
            if (Configuration::get('WIC_ERP_STOCK_MANAGEMENT') != 'normal') {
                if (preg_match('/_/i', $id)) {
                    $id = explode('_', $id);
                    $id_product = $id[0];
                    $id_attribute = $id[1];
                } else {
                    $id_product = $id;
                    $id_attribute = 0;
                }
            } else {
                $erp_products = new ErpProducts((int)$id);
                $id_product = $erp_products->id_product;
                $id_attribute = $erp_products->id_product_attribute;
            }

            $this->table = 'order';
            $this->className = 'Order';

            $this->context = Context::getContext();

            $this->_select = '
			a.id_currency,
			a.id_order AS id_pdf,
			CONCAT(LEFT(c.`firstname`, 1), \'. \', c.`lastname`) AS `customer`,
			osl.`name` AS `osname`,
			os.`color`,
			ca.name as carrier_name';
            $this->_join = '
			LEFT JOIN `'._DB_PREFIX_.'customer` c ON (c.`id_customer` = a.`id_customer`)
			LEFT JOIN `'._DB_PREFIX_.'order_state` os ON (os.`id_order_state` = a.`current_state`)
			LEFT JOIN `'._DB_PREFIX_.'carrier` ca ON (ca.`id_carrier` = a.`id_carrier`)
			LEFT JOIN `'._DB_PREFIX_.'order_state_lang` osl ON (os.`id_order_state` = osl.`id_order_state` AND osl.`id_lang` = '.(int)$this->context->language->id.')';
            $this->_where = 'AND a.`id_order` IN (
													SELECT od.id_order
													FROM `'._DB_PREFIX_.'order_detail` od 
													WHERE od.`product_id` = '.(int)$id_product.'
													AND od.`product_attribute_id` = '.(int)$id_attribute.'
													AND od.`product_quantity` != od.`product_quantity_in_stock`
												)';
            if (Configuration::get('WIC_ERP_NOT_COMPLETE')) {
                $this->_where .= ' AND a.`current_state` IN ('.pSQL(Configuration::get('WIC_ERP_NOT_COMPLETE')).')';
            }
            $this->_group = ' GROUP BY a.`id_order`';
            $this->_orderBy = 'id_order';
            $this->_orderWay = 'DESC';

            unset($this->_having);

            $statuses_array = array();
            $statuses = OrderState::getOrderStates((int)$this->context->language->id);

            foreach ($statuses as $status) {
                $statuses_array[$status['id_order_state']] = $status['name'];
            }

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
                'havingFilter' => true,
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

            'payment' => array(
                'title' => $this->l('Payment'),
                'width' => 100
            ),

            'osname' => array(
                'title' => $this->l('Status'),
                'color' => 'color',
                'width' => 200,
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
            ),

            'carrier_name' => array(
            'title' => $this->l('Carrier'),
            'width' => 150,
            'callback' => 'carrier'
            ),

            'id_pdf' => array(
                'title' => $this->l('PDF'),
                'width' => 35,
                'align' => 'center',
                'callback' => 'printPDFIconsOrder',
                'orderby' => false,
                'search' => false,
                'remove_onclick' => true
            )
            );

            // get list and force no limit clause in the request
            $this->getList($this->context->language->id, null, null, 0, false);

            // Render list
            $helper = new HelperList();
            $helper->no_link = true;
            $helper->shopLinkType = '';
            $helper->identifier = 'id_order';
            $helper->toolbar_scroll = false;
            $helper->currentIndex = self::$currentIndex;
            $helper->token = $this->token;
            $helper->table = $this->table;
            $helper->simple_header = true;
            $helper->show_toolbar = false;
            $helper->position_identifier = 'id_order';
            $content = $helper->generateList($this->_list, $this->fields_list);

            die(Tools::jsonEncode(array('use_parent_structure' => false, 'data' => $content)));
        }
    }

    public function fetchTemplate($path, $name, $extension = false)
    {
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'wic_erp'.$path.$name.'.'.($extension ? $extension : 'tpl'));
    }

    public function carrier($carrier_name)
    {
        return $carrier_name;
    }

    public function printPDFIconsOrder($id_order)
    {
        $order = new Order((int)$id_order);
        $this->context->smarty->assign(array(
            'order' => $order,
            'order_state' => $order->getCurrentOrderState(),
            'link' => new Link()

        ));

        return $this->fetchTemplate('/views/templates/admin/', '_print_pdf_icon_order16');
    }

    public function initToolbar()
    {
        return false;
    }

    public function productName($id_product_ref)
    {
        $ids = explode('_', $id_product_ref);
        $id_product = $ids[0];

        $product_obj = new Product($id_product, false, $this->context->language->id);
        if (isset($ids[1])) {
            $attributes = $product_obj->getAttributeCombinationsById($ids[1], $this->context->language->id);
            if ($attributes) {
                $name = $product_obj->name;
                foreach ($attributes as $attribute) {
                    $name .= ' '.$attribute['group_name'].' : '.$attribute['attribute_name'];
                }
                return $name;
            } else {
                return $product_obj->name;
            }
        } else {
            return $product_obj->name;
        }
    }

    public function realStock($stock)
    {
        if ($stock < 0) {
            return '<span class="badge badge-danger">'.$stock.'</span>';
        } elseif ($stock == 0) {
            return '<span class="badge badge-warning">'.$stock.'</span>';
        } else {
            return '<span class="badge badge-success">'.$stock.'</span>';
        }
    }

    public function stockPurchased($stock)
    {
        if ($stock > 0) {
            return '<span class="badge badge-danger">'.$stock.'</span>';
        } else {
            return '<span class="badge badge-success">'.$stock.'</span>';
        }
    }

    /**
     * Callback used to display custom content for a given field
     * @param int $id_supply_order
     * @param string $tr
     * @return string $content
     */
    public function printProductIcons($id_product_ref)
    {
        $ids = explode('_', $id_product_ref);
        $id_product = $ids[0];

        $product_obj = new Product($id_product, false, $this->context->language->id);

        $content = '<span style="width:20px; margin-right:5px;">';
        $content .= '<a class="pointer btn btn-default" href="'.$this->context->link->getAdminLink('AdminProducts').'&id_product='.(int)$product_obj->id.'&updateproduct" title="'.$this->l('View product').'"><i class="icon-eye-open"></i></a>';
        $content .= '</span>';
        $content .= '<a class="pointer btn btn-default" href="'.$this->context->link->getAdminLink('AdminStats').'&module=statsproduct&id_product='.(int)$product_obj->id.'" title="'.$this->l('Statisics product').'"><i class="icon-signal"></i></a>';

        return $content;
    }

    /**
     * Callback used to display custom content for a given field
     * @param int $id_supply_order
     * @param string $tr
     * @return string $content
     */
    public function productImage($id_product)
    {
        $product = new Product((int)$id_product);

        $content = '';

        if (Validate::isLoadedObject($product)) {
            $image_obj = Product::getCover((int)$id_product);
            if (version_compare(_PS_VERSION_, '1.7', '<')) {
                $url = $this->context->link->getImageLink($product->link_rewrite[(int)Configuration::get('PS_LANG_DEFAULT')], $image_obj['id_image'], ImageType::getFormatedName('small'));
            } else {
                $url = $this->context->link->getImageLink($product->link_rewrite[(int)Configuration::get('PS_LANG_DEFAULT')], $image_obj['id_image'], ImageType::getFormattedName('small'));
            }
            $content .= '<img src="'.$url.'" alt="" class="imgm img-thumbnail"/>';
        }

        return $content;
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

        unset($this->context->cookie->Filter_Suppliers);
        unset($this->context->cookie->Filter_id_Suppliers);

        unset($_POST);
        $this->_filter = false;
        unset($this->_filterHaving);
        unset($this->_having);
    }
    
    protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
    	if (version_compare(_PS_VERSION_, '1.7', '<')) {
            return parent::l($string, $class, $addslashes, $htmlentities);
    	} else {
            return Translate::getModuleTranslation('wic_erp', $string, 'AdminErpProductOrderController');
    	}
    }
}
