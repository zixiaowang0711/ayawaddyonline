<?php
/**
 * Module ERP | Web in Color / AdminErpStpckTransfertController
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module to newer
 * versions in the future. If you wish to customize our module for your
 * needs please refer to http://www.webincolor.fr for more information.
 *
 *  @author    Web in Color <addons@webincolor.fr>
 *  @copyright Copyright &copy; 2015, Web In Color
 *  @license   http://www.webincolor.fr
 */

require_once _PS_MODULE_DIR_.'wic_erp/classes/HTMLTemplateStockTransfertReport.php';

class AdminErpStockTransfertController extends ModuleAdminController
{
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->table = 'erp_products';
        $this->list_id = 'erp_products';
        $this->className = 'ErpProducts';
        $this->identifier = 'id_erp_products';
        $this->lang = false;
        $this->delete = false;
        $this->list_no_link = true;
        $this->_orderBy = null;
        $this->context = Context::getContext();
        
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->bootstrap = true;
        }
        
        /* Join categories table */
        if ($id_category = (int)Tools::getValue('productFilter_cl!name')) {
            $this->_category = new Category((int)$id_category);
            $_POST['productFilter_cl!name'] = $this->_category->name[$this->context->language->id];
        } elseif ($id_category = Tools::getvalue('id_category')) {
            $this->_category = new Category((int)$id_category);
        } else {
            $this->_category = new Category();
        }

        $join_category = false;
        if (Validate::isLoadedObject($this->_category) && empty($this->_filter)) {
            $join_category = true;
        }

        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'image` i ON (i.`id_product` = a.`id_product` '.(!Shop::isFeatureActive() ? ' AND i.cover=1' : '').')';
        $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'product_attribute` pa ON (a.`id_product_attribute` = pa.`id_product_attribute`)';
        $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'warehouse_product_location` wpl ON (wpl.`id_product` = a.`id_product` AND wpl.`id_product_attribute` = a.`id_product_attribute`)';
        if (isset($this->context->shop->id) && $this->context->shop->id) {
            $query_shop = ' = '.(int)$this->context->shop->id;
        } elseif (isset($this->context->shop->id_shop_group)) {
            $id_shops = ShopGroup::getShopsFromGroup($this->context->shop->id_shop_group);

            $array_shop = array();
            foreach ($id_shops as $key => $value) {
                $array_shop[] = (int)$value['id_shop'];
            }

            $query_shop = ' IN ('.implode(',', $array_shop).')';
        } else {
            $query_shop = ' = '.(int)Configuration::get('PS_SHOP_DEFAULT');
        }

        if (Shop::isFeatureActive()) {
            $alias = 'sa';
            $alias_image = 'image_shop';
            if (Shop::getContext() == Shop::CONTEXT_SHOP) {
                $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'product_shop` sa ON (a.`id_product` = sa.`id_product` AND sa.`id_shop`'.$query_shop.')
                        LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON ('.$alias.'.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.(int)$this->context->language->id.' AND cl.`id_shop`'.$query_shop.')
                        LEFT JOIN `'._DB_PREFIX_.'shop` shop ON (shop.id_shop = '.(int)$this->context->shop->id.') 
                        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (a.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$this->context->language->id.')
                        LEFT JOIN `'._DB_PREFIX_.'product` p ON (a.`id_product` = p.`id_product`)
                        LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop ON (image_shop.`id_image` = i.`id_image` AND image_shop.`cover` = 1 AND image_shop.`id_shop`'.$query_shop.')';
                $this->_where .= 'AND (i.id_image IS NULL OR image_shop.`id_shop`'.$query_shop.')';
            } else {
                $this->_join .= ' LEFT JOIN `'._DB_PREFIX_.'product_shop` sa ON (a.`id_product` = sa.`id_product` AND sa.`id_shop`'.$query_shop.')
                        LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON ('.$alias.'.`id_category_default` = cl.`id_category` AND cl.`id_lang` = '.(int)$this->context->language->id.' AND cl.`id_shop`'.$query_shop.')
                        LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (a.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$this->context->language->id.')
                        LEFT JOIN `'._DB_PREFIX_.'product` p ON (a.`id_product` = p.`id_product`)
                        LEFT JOIN `'._DB_PREFIX_.'shop` shop ON (shop.`id_shop`'.$query_shop.') 
                        LEFT JOIN `'._DB_PREFIX_.'image_shop` image_shop ON (image_shop.`id_image` = i.`id_image` AND image_shop.`cover` = 1 AND image_shop.`id_shop`'.$query_shop.')';
                $this->_where .= 'AND (i.id_image IS NULL OR image_shop.id_shop=p.id_shop_default)';
            }
            $this->_select .= 'shop.name as shopname, ';
        } else {
            $alias = 'p';
            $alias_image = 'i';
            $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'product` p ON (a.`id_product` = p.`id_product`)
                            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl ON (a.`id_product` = pl.`id_product` AND pl.`id_lang` = '.(int)$this->context->language->id.')
                            LEFT JOIN `'._DB_PREFIX_.'category_lang` cl ON ('.$alias.'.`id_category_default` = cl.`id_category` AND pl.`id_lang` = cl.`id_lang` AND cl.`id_shop`'.$query_shop.')
                            ';
        }

        if (!configuration::get('WIC_ERP_DISABLED_PRODUCT')) {
            $this->_where .= ' AND p.`active` = 1';
        }

        $this->_join .= ($join_category ? 'INNER JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_product` = a.`id_product` AND cp.`id_category` = '.(int)$this->_category->id.')' : '').'';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'supplier` sp ON (p.`id_supplier` = sp.`id_supplier`)';
        $this->_select .= 'cl.name `name_category`, '.$alias_image.'.`id_image`, pl.`name`, a.`id_product` AS id_prod, a.`id_product` as `image`, sp.`name` as `supplier_name`, pa.`id_product_attribute`, 1 as stock_available, ';
        $this->_select .= 'IF(pa.`ean13` != \'\',pa.`ean13` ,p.`ean13`) AS `ean13`,';
        $this->_select .= 'IF(pa.`reference` != \'\',pa.`reference` ,p.`reference`) AS `reference`';
        $this->_group = 'GROUP BY a.id_erp_products';

        //For product which need Transfert
        $this->_select .= ', s.`usable_quantity` as real_stock, epbw.`min_quantity_by_warehouse` as minQuantity, wpl.`location`';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'stock` s ON (a.`id_product` = s.`id_product` AND a.`id_product_attribute` = s.`id_product_attribute` AND s.`id_warehouse` = '.Tools::getValue('warehouse_to', Configuration::get('WIC_ERP_WAREHOUSE_TO_DEFAULT')).')';
      
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'erp_products_by_warehouse` epbw ON (a.`id_erp_products` = epbw.`id_erp_products` AND epbw.`id_warehouse` = '.Tools::getValue('warehouse_to', Configuration::get('WIC_ERP_WAREHOUSE_TO_DEFAULT')).')';
        //$this->_where .= ' AND (s.`usable_quantity` < epbw.`min_quantity_by_warehouse` OR s.`usable_quantity` < epbw.`min_quantity_by_warehouse`)';
		$this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'stock_available` sar ON (a.`id_product` = sar.`id_product` AND a.`id_product_attribute` = sar.`id_product_attribute` AND sar.`id_shop` = '.Tools::getValue('warehouse_to', Configuration::get('WIC_ERP_WAREHOUSE_TO_DEFAULT')).')';
		$this->_where .= ' AND (s.`usable_quantity` < epbw.`min_quantity_by_warehouse` OR (sar.quantity < 0 AND sar.quantity <  epbw.`min_quantity_by_warehouse`))';      


		$this->_orderBy = 'wpl.location';
        
        $this->fields_list = array();
        
        $this->fields_list['image'] = array(
            'title' => $this->l('Photo'),
            'align' => 'center',
            'width' => 70,
            'orderby' => false,
            'filter' => false,
            'search' => false,
            'callback' => 'productImage',
            );
        
        $this->fields_list['name'] = array(
            'title' => $this->l('Name'),
            'width' => 150,
            'orderby' => false,
            'filter' => false,
            'search' => false,
            );

        $this->fields_list['reference'] = array(
            'title' => $this->l('Reference'),
            'align' => 'left',
            'orderby' => false,
            'filter' => false,
            'search' => false,
            );
        
        $this->fields_list['ean13'] = array(
            'title' => $this->l('BarCode'),
            'align' => 'left',
            'orderby' => false,
            'filter' => false,
            'search' => false,
            );
        
        $this->fields_list['stock_available'] = array(
            'title' => $this->l('Warehouse Informations'),
            'callback' => 'getStockAvailable',
            'width' => 100,
            'align' => 'left',
            'orderby' => false,
            'filter' => false,
            'search' => false,
            );
        $this->fields_list['real_stock'] = array(
            'title' => $this->l('Stock transfer'),
            'align' => 'center',
            'orderby' => false,
            'filter' => false,
            'search' => false,
            'callback' => 'getStockTransfer',
            );
        $this->fields_list['transferableStock'] = array(
            'title' => $this->l('Stock transferable'),
            'align' => 'center',
            'orderby' => false,
            'filter' => false,
            'search' => false,
            'callback' => 'getStockTransferable',
            );
            
        return parent::__construct();
    }

    public function setMedia()
    {
        // We need to set parent media first, so that jQuery is loaded before the dependant plugins
        parent::setMedia();

        $this->addJs(_PS_MODULE_DIR_.'wic_erp/views/js/wic_erp_transfert.js');
    }
    
    public function initContent()
    {
        $this->tpl_list_vars['stockTransfertProductForm'] = $this->getStockTransfertProductForm();
        $this->tpl_list_vars['stockTransfertWarehouseForm'] = $this->getStockTransfertWarehouseForm();
        $this->tpl_list_vars['ajaxUrl'] = $this->context->link->getAdminLink('AdminErpStockTransfert');

        parent::initContent();
    }
    
    public function getList($id_lang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        $this->context = Context::getContext();
        //Define shop context
        $cookie = Context::getContext()->cookie->getFamily('shopContext');
        $id_shop = (int)substr($cookie['shopContext'], 2, count($cookie['shopContext']));
                
        parent::getList($id_lang, $orderBy, $orderWay, $start, $limit, $id_shop);

        $nb = count($this->_list);
        if ($nb) {
            // update product final price
            for ($i = 0; $i < $nb; $i++) {
                $manager = StockManagerFactory::getManager();		
				
                $min_quantity = Db::getInstance()->getValue('SELECT `min_quantity_by_warehouse` FROM `'._DB_PREFIX_.'erp_products_by_warehouse` WHERE `id_erp_products` = '.$this->_list[$i]['id_erp_products'].' AND `id_warehouse` = '.Tools::getValue('warehouse_to', Configuration::get('WIC_ERP_WAREHOUSE_TO_DEFAULT')));
				
                $min_quantity_from = Db::getInstance()->getValue('SELECT `min_quantity_by_warehouse` FROM `'._DB_PREFIX_.'erp_products_by_warehouse` WHERE `id_erp_products` = '.$this->_list[$i]['id_erp_products'].' AND `id_warehouse` = '.Tools::getValue('warehouse_from', Configuration::get('WIC_ERP_WAREHOUSE_FROM_DEFAULT')));
                $min_quantity_to = Db::getInstance()->getValue('SELECT `min_quantity_by_warehouse` FROM `'._DB_PREFIX_.'erp_products_by_warehouse` WHERE `id_erp_products` = '.$this->_list[$i]['id_erp_products'].' AND `id_warehouse` = '.Tools::getValue('warehouse_to', Configuration::get('WIC_ERP_WAREHOUSE_TO_DEFAULT')));                
                //$real_quantity_to = $manager->getProductRealQuantities($this->_list[$i]['id_product'], $this->_list[$i]['id_product_attribute'], Tools::getValue('warehouse_to', Configuration::get('WIC_ERP_WAREHOUSE_FROM_DEFAULT')));
                $real_quantity_to = $manager->getProductRealQuantities($this->_list[$i]['id_product'], $this->_list[$i]['id_product_attribute'], Tools::getValue('warehouse_to', Configuration::get('WIC_ERP_WAREHOUSE_TO_DEFAULT')));
                $real_quantity_from = $manager->getProductRealQuantities($this->_list[$i]['id_product'], $this->_list[$i]['id_product_attribute'], Tools::getValue('warehouse_from', Configuration::get('WIC_ERP_WAREHOUSE_FROM_DEFAULT')));
				
                if ($real_quantity_to < $min_quantity_to) {
                    $real_quantity = $manager->getProductRealQuantities($this->_list[$i]['id_product'], $this->_list[$i]['id_product_attribute'], Tools::getValue('warehouse_from', Configuration::get('WIC_ERP_WAREHOUSE_FROM_DEFAULT')));
                    if ($real_quantity > 0) {
                        $manager = StockManagerFactory::getManager();
                        $real_quantity_to_transfer = $manager->getProductRealQuantities($this->_list[$i]['id_product'], $this->_list[$i]['id_product_attribute'], Tools::getValue('warehouse_to', Configuration::get('WIC_ERP_WAREHOUSE_TO_DEFAULT')));
                        $name = $this->_list[$i]['name'];
                        $product_obj = new Product($this->_list[$i]['id_product']);

                        if (Validate::isLoadedObject($product_obj)) {
                            $attributes = $product_obj->getAttributeCombinationsById((int)$this->_list[$i]['id_product_attribute'], (int)$this->context->language->id);
                            if ($attributes) {
                                $name .= ' --';
                                foreach ($attributes as $attribute) {
                                    $name .= ' ';
                                    $name .= $attribute['group_name'].' : '.$attribute['attribute_name'];
                                }
                            }
                        }
                        $this->_list[$i]['name'] = $name;
                        $this->_list[$i]['real_stock'] = $real_quantity_to_transfer;
                        $this->_list[$i]['transferableStock'] = $real_quantity;
                        $this->_list[$i]['stockTransfert'] = $min_quantity - $real_quantity_to_transfer;
                    } else {
                        unset($this->_list[$i]);
                    }
                } else {
                    unset($this->_list[$i]);
                }
            }
        }
    }
    
    public function getStockTransfertProductForm()
    {
        $helper = new HelperForm();
        $helper->id = 'wic_form_stock_transfert_product_scan';
        $form_fields = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Transfert product quantity'),
                    'icon' => 'icon-AdminStock',
                ),
                'input' => array(
                    array(
                        'type' => 'text',
                        'name' => 'ean',
                        'class' => 'form-control input-lg',
                        'label' => $this->l('Reference / EAN / UPC'),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'id' => 'wic_addTransfertProduct',
                ),
            ),
        );

        $default_field_value = array(
            'ean' => null,
            'quantity' => 1,
        );

        $helper->show_toolbar = false;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = 'transfertstockproductform';
        $helper->submit_action = 'submitWic_transfertstockproduct';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminErpStockTransfert', false);
        $helper->token = Tools::getAdminTokenLite('AdminErpStockTransfert');

        $helper->tpl_vars = array(
            'fields_value' => $default_field_value, /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($form_fields));
    }
    
    public function getStockTransfertWarehouseForm()
    {
        
        $warehouses = Warehouse::getWarehouses();
            
        $helper = new HelperForm();
        $helper->id = 'wic_form_stock_transfert_warehouse';
        $form_fields = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Transfert product quantity'),
                    'icon' => 'icon-AdminStock',
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Please select warehouse from'),
                        'name' => 'warehouse_from',
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
                        'label' => $this->l('Please select warehouse to'),
                        'name' => 'warehouse_to',
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
                    'id' => 'wic_warehouse',
                ),
            ),
        );

        $default_field_value = array(
            'warehouse_from' => Tools::getValue('warehouse_from', Configuration::get('WIC_ERP_WAREHOUSE_FROM_DEFAULT')),
            'warehouse_to' => Tools::getValue('warehouse_to', Configuration::get('WIC_ERP_WAREHOUSE_TO_DEFAULT')),
        );

        $helper->show_toolbar = false;
        $helper->default_form_language = $this->context->language->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = 'transfertstockwarehouseform';
        $helper->submit_action = 'submitWic_transfertstockwarehouse';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminErpStockTransfert', false);
        $helper->token = Tools::getAdminTokenLite('AdminErpStockTransfert');

        $helper->tpl_vars = array(
            'fields_value' => $default_field_value, /* Add values for your inputs */
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($form_fields));
    }

    public function postProcess()
    {
        parent::postProcess();
        if (Tools::isSubmit('exportPDF')) {
            $id_lang = $this->context->language->id;
            $this->getList($id_lang);
            $pdf = new PDF((object)$this->_list, 'StockTransfertReport', Context::getContext()->smarty);
            $pdf->render();
        }

        if (Tools::isSubmit('exportListCSV')) {
            $id_lang = $this->context->language->id;
            $collection = [];
            $this->getList($id_lang);
            $products = $this->_list;
       
            foreach ($products as $product) {
                
                $item = array(
                    'product_name' => $product['name'],
                    'reference' => $product['reference'],
                    'ean13' => $product['ean13'],
                    'stock_transferable' => $product['transferableStock'],
                );
                $collection[] = (object) $item;
            }

            $csv = new CSV($collection, $this->l('stock_transfert_').date('Y-m-d'));
            die($csv->export());
        }
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
            
            if ($image_obj) {
                $content .= '<img src="'.Context::getContext()->link->getImageLink($product->link_rewrite[(int)Configuration::get('PS_LANG_DEFAULT')], $image_obj['id_image'], ImageType::getFormatedName('small')).'" alt="" class="imgm img-thumbnail" width="35" />';
            }
        }

        return $content;
    }
    
    public function getStockAvailable($id_erp_products, $value) 
    {
        $content = '<table width="100%">
                        <thead>
                            <tr class="nodrag nodrop">
                                <th>'.$this->l('Warehouse').'</th>
                                <th>'.$this->l('Stock available').'</th>
                                <th>'.$this->l('Min stock').'</th>
                                <th>'.$this->l('Safety stock').'</th>
                            </tr>
                    </thead>
                    <tbody>';
        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT')) {
            $warehouses = Warehouse::getWarehouses(true);
            
            $manager = StockManagerFactory::getManager();
            foreach ($warehouses as $warehouse) {
                $content .= '<tr>';
                $warehouseObj = new Warehouse($warehouse['id_warehouse']);
                if (Validate::isLoadedObject($warehouseObj)) {
                    $physicalQuantity = $manager->getProductRealQuantities($value['id_product'], $value['id_product_attribute'], array($warehouse['id_warehouse']));
                    $content .= '<td><b>'.$warehouseObj->name.': </b></td>';
                    $content .= '<td>';
                    
                    if ($warehouseObj->id == Tools::getValue('warehouse_from', Configuration::get('WIC_ERP_WAREHOUSE_FROM_DEFAULT'))) {
                        $idSpan = "realStockFrom";
                    } else {
                        $idSpan = "realStockTo";
                    }
                    if ($physicalQuantity == 0) {
                        $content .= '<span class="badge badge-warning" id="'.$idSpan.'_'.$value['id_erp_products'].'">';
                    } elseif ($physicalQuantity > 0) {
                        $content .= '<span class="badge badge-success" id="'.$idSpan.'_'.$value['id_erp_products'].'">'; 
                    } else {
                        $content .= '<span class="badge badge-danger" id="'.$idSpan.'_'.$value['id_erp_products'].'">'; 
                    }   
                    $content .= $physicalQuantity.'</span>';
                    $content .= '</td>';
                    
                    // Get Minimal Quantity
                    $quantity =  Db::getInstance()->getValue('SELECT `min_quantity_by_warehouse` FROM `'._DB_PREFIX_.'erp_products_by_warehouse` WHERE `id_erp_products` = '.$value['id_erp_products'].' AND `id_warehouse` = '.$warehouse['id_warehouse']);
                    $content .= '<td>';
                    $content .= '<span class="badge badge-info">';  
                    $content .= (!$quantity ? 0 : $quantity).'</span>';
                    $content .= '</td>';
                    
                    //Get Safety Quantity
                    $quantity = Db::getInstance()->getValue('SELECT `safety_stock_by_warehouse` FROM `'._DB_PREFIX_.'erp_products_by_warehouse` WHERE `id_erp_products` = '.$value['id_erp_products'].' AND `id_warehouse` = '.$warehouse['id_warehouse']);
                    $content .= '<td>';
                    $content .= '<span class="badge badge-info">';  
                    $content .= (!$quantity ? 0 : $quantity).'</span><br/>';
                    $content .= '</td>';
                }
                $content .= '</tr>';
            }
        }
        
        $content .= '</tbody></table>';
        
        return $content;
    }
    
    public function getStockTransfer($real_quantity, $value)
    {
        $content = '<span class="badge badge-danger" id="stockTransfert_'.$value['id_erp_products'].'">';
        $content .= $value['minQuantity'] - $real_quantity;
        $content .= '</span>';

        return $content;
    }
    
    public function getStockTransferable($transferableStock, $value)
    {
        $content = '<span class="badge badge-success" id="stockTransferable_'.$value['id_erp_products'].'">';
        $content .= $transferableStock;
        $content .= '</span>';
        return $content;
    }
    
    public function ajaxProcessAddTransfertStockProduct()
    {
        $response = array(
            'success' => false,
            'message' => $this->l('Unable to load this product'),
        );
            if ($ean = Tools::getValue('ean')) {
                $where = array();

                foreach (array('`reference`', '`ean13`', '`upc`') as $field) {
                    $where[] = '(p.'.pSQL($field).' = "'.pSQL($ean).'" OR pa.'.pSQL($field).' = "'.pSQL($ean).'")';
                }

                $query = new DbQuery();
                $query->select('p.`id_product`, pa.`id_product_attribute`');
                $query->from('product', 'p');
                $query->leftJoin('product_attribute', 'pa', 'p.`id_product` = pa.`id_product`');
                $query->where(implode(' OR ', $where));

                if ($results = Db::getInstance()->ExecuteS($query)) {
                    if (count($results) == 1) {
                        $row = array_shift($results);
                    
                        $stock_manager = StockManagerFactory::getManager();

                        $is_transfer = $stock_manager->transferBetweenWarehouses(
                            $row['id_product'],
                            $row['id_product_attribute'],
                            1,
                            Tools::getValue('warehouse_from', Configuration::get('WIC_ERP_WAREHOUSE_FROM_DEFAULT')),
                            Tools::getValue('warehouse_to', Configuration::get('WIC_ERP_WAREHOUSE_TO_DEFAULT')),
                            1,
                            1
                        );
                        StockAvailable::synchronize($row['id_product']);
                        
                        if ($is_transfer) {
                            $update_msg = $this->l('Product %s updated');
                            $product_name = Product::getProductName($row['id_product'], $row['id_product_attribute'], $this->context->language->id);

                            $response['success'] = true;
                            $response['message'] = sprintf($update_msg, $product_name);
                            
                            //We retreive ERP Products
                            $id_erp_products = ErpProducts::getProductById($row['id_product'], $row['id_product_attribute']);
                            
                            $min_quantity = Db::getInstance()->getValue('SELECT `min_quantity_by_warehouse` FROM `'._DB_PREFIX_.'erp_products_by_warehouse` WHERE `id_erp_products` = '.$id_erp_products.' AND `id_warehouse` = '.Tools::getValue('warehouse_to', Configuration::get('WIC_ERP_WAREHOUSE_TO_DEFAULT')));
                            
                            $manager = StockManagerFactory::getManager();
                            $real_quantity = $manager->getProductRealQuantities($row['id_product'], $row['id_product_attribute'], Tools::getValue('warehouse_from', Configuration::get('WIC_ERP_WAREHOUSE_FROM_DEFAULT')));
                            $real_quantity_to_transfer = $manager->getProductRealQuantities($row['id_product'], $row['id_product_attribute'], Tools::getValue('warehouse_to', Configuration::get('WIC_ERP_WAREHOUSE_TO_DEFAULT')));
                            $response['stockTransfer'] = $min_quantity - $real_quantity_to_transfer;
                            $response['stockTransferable'] = $real_quantity;
                            $response['realStockFrom'] = $real_quantity;
                            $response['realStockTo'] = $real_quantity_to_transfer;
                            $response['id_erp_products'] = $id_erp_products;
                        } else {
                            $response['message'] = $this->l('Unable to save modifcations');
                        }
                    } else {
                        $response['message'] = $this->l('More than one result');
                    }
                } else {
                    $response['message'] = $this->l('Unknown Reference / EAN / UPC');
                }
            } else {
                $response['message'] = $this->l('Invalid Reference / EAN / UPC');
            }
        self::renderJSON($response);
    }
    
    public static function renderJSON(array $data)
    {
        header('Content-Type: application/json');
        die(Tools::jsonEncode($data));
    }
}
