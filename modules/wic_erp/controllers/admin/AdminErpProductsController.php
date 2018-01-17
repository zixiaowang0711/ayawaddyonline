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

class AdminErpProductsController extends ModuleAdminController
{
    public $toolbar_title;

    public function __construct()
    {
        //$this->context = Context::getContext();
        $this->table = 'erp_products';
        $this->list_id = 'erp_products';
        $this->className = 'ErpProducts';
        $this->identifier = 'id_erp_products';
        $this->lang = false;
        $this->multishop_context = Shop::CONTEXT_ALL;
        $this->bootstrap = true;
        $this->toolbar_title = $this->l('My Easy ERP: Setting Erp Products');

        $this->initShopContext($this->context);

        $this->list_no_link = true;

        $this->bulk_actions = array(
            'update' => array('text' => $this->l('Update products'), 'confirm' => $this->l('Update all products?'))
        );

        if (Tools::isSubmit('submitReseterp_products')) {
            $this->processResetFilters();
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
                $this->_join .= ' JOIN `'._DB_PREFIX_.'product_shop` sa ON (a.`id_product` = sa.`id_product` AND sa.`id_shop`'.$query_shop.')
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

        $id_suppliers = Tools::getValue($this->table.'Filter_Supplier');

        if (!is_array($id_suppliers) && isset($this->context->cookie->Filter_Suppliers) && $this->context->cookie->Filter_Suppliers) {
            $id_suppliers = explode(',', $this->context->cookie->Filter_Suppliers);
        }
                
        if (is_array($id_suppliers)) {
            $this->_where .= ' AND p.`id_supplier` IN ('.implode(',', $id_suppliers).')';
        }

        $this->_join .= ($join_category ? 'INNER JOIN `'._DB_PREFIX_.'category_product` cp ON (cp.`id_product` = a.`id_product` AND cp.`id_category` = '.(int)$this->_category->id.')' : '').'';
        $this->_join .= 'LEFT JOIN `'._DB_PREFIX_.'supplier` sp ON (p.`id_supplier` = sp.`id_supplier`)';
        $this->_select .= 'cl.name `name_category`, '.$alias_image.'.`id_image`, pl.`name`, p.`reference`, a.`id_product` AS id_prod, a.`id_product` as `image`, sp.`name` as `supplier_name`';
        $this->_group = 'GROUP BY a.id_product';

        $this->fields_list = array();
        $this->fields_list['id_product'] = array(
            'title' => $this->l('ID'),
            'align' => 'center',
            'width' => 20,
            'filter_key' => 'a!id_product',
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
        $this->fields_list['name'] = array(
            'title' => $this->l('Name'),
            'filter_key' => 'pl!name'
                        );

        $this->fields_list['supplier_name'] = array(
                'title' => $this->l('Supplier'),
                'align' => 'left',
                'width' => 100,
                'filter_key' => 'sp!name'
                        );

        $this->fields_list['reference'] = array(
            'title' => $this->l('Reference'),
            'align' => 'left',
            'width' => 80
                        );

        $this->fields_list['name_category'] = array(
                'title' => $this->l('Category'),
                'width' => 230,
                'filter_key' => 'cl!name',
                                );

        $this->fields_list['id_prod'] = array(
            'title' => $this->l('List'),
            'width' => 35,
            'align' => 'center',
            'callback' => 'viewProduct',
            'orderby' => false,
            'search' => false,
            'remove_onclick' => true
        );

        parent::__construct();
    }

    public function initShopContext()
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
    }

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

    public function initBreadcrumbs($tab_id = null, $tabs = null)
    {
        parent::initBreadcrumbs();
    }

    public function getList($id_lang, $orderBy = null, $orderWay = null, $start = 0, $limit = null, $id_lang_shop = false)
    {
        if (Tools::getValue($this->list_id.'Orderby')) {
            $orderBy = Tools::getValue($this->list_id.'Orderby');
        }
        
        if (Tools::getValue($this->list_id.'Orderway')) {
            $orderWay = Tools::getValue($this->list_id.'Orderway');
        }
            
        parent::getList($id_lang, $orderBy, $orderWay, $start, $limit, $this->context->shop->id);
    }

    public function renderList()
    {
        if (!Tools::isSubmit('submitReseterp_products')) {
            parent::processFilter();
        }

        return parent::renderList();
    }

    public function fetchTemplate($path, $name, $extension = false)
    {
        return $this->context->smarty->fetch(_PS_MODULE_DIR_.'wic_erp'.$path.$name.'.'.($extension ? $extension : 'tpl'));
    }

    public function viewProduct($id_product)
    {
        $erp_products = new ErpProducts();
        $products = $erp_products->getAllProductAttributes((int)$id_product);

        if ($products) {
            //Foreach products we retrieve attrubutes name
            foreach ($products as $key => $value) {
                $product_obj = new Product((int)$value['id_product'], false, (int)$this->context->language->id);
                $attributes = $product_obj->getAttributeCombinationsById((int)$value['id_product_attribute'], (int)$this->context->language->id);
                if ($attributes) {
                    $name = $product_obj->name;
                    foreach ($attributes as $attribute) {
                        $name .= ' '.$attribute['group_name'].' : '.$attribute['attribute_name'];
                    }
                    
                    $products[$key]['name'] = $name;
                    unset($name);
                } elseif ($value['id_product_attribute'] && !$attributes) {
                    //This combination deleted in product Obj
                    //We update erpProducts
                    Db::getInstance()->Execute('DELETE FROM `'._DB_PREFIX_.'erp_products` WHERE id_product='.(int)$product_obj->id.' AND id_product_attribute = '.(int)$value['id_product_attribute']);
                    unset($products[$key]);
                }

                if (isset($products[$key])) {
                    $id_stock = StockAvailable::getStockAvailableIdByProductId((int)$value['id_product'], (int)$value['id_product_attribute']);
                    $stock = new StockAvailable($id_stock);

                    if (Validate::isLoadedObject($stock)) {
                        $products[$key]['stock'] = $stock->quantity;
                    } else {
                        $products[$key]['stock'] = 0;
                    }

                    unset($id_stock, $stock);
                }
            }
        }
                
        $this->context->smarty->assign(array(
            'products' => $products,
            'id_product' => $id_product,
        ));

        return $this->fetchTemplate('/views/templates/admin/', '_list_attributes');
    }

    public function postProcess()
    {
        if (Tools::isSubmit('submitBulkupdateerp_products')) {
            $quantities = Tools::getValue('min_quantity');
            $safety_stock = Tools::getValue('safety_stock');
            $manual_configuration = Tools::getValue('manual_configuration');
            $unit_order = Tools::getValue('unit_order');
            $bundling = Tools::getValue('bundling');
            
            foreach ($quantities as $key => $value) {
                $ids = explode('_', $key);
                $id_product = $ids[0];
                $id_product_attribute = $ids[1];
                $erp_product = ErpProducts::getProductById((int)$id_product, (int)$id_product_attribute);
                $erp_product = new ErpProducts($erp_product);
                                                
                if (Validate::isLoadedObject($erp_product)) {
                    $erp_product->min_quantity = $quantities[$key];
                    $erp_product->safety_stock = $safety_stock[$key];
                    $erp_product->manual_configuration = (isset($manual_configuration[$key]) ? $manual_configuration[$key] : 0);
                    $erp_product->unit_order = $unit_order[$key];
                    $erp_product->bundling = $bundling[$key];
                    $erp_product->save();
                }
            }
        }
    }

    public function initToolbar()
    {
        return false;
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
                $content .= '<img src="'.Context::getContext()->link->getImageLink($product->link_rewrite[(int)Configuration::get('PS_LANG_DEFAULT')], $image_obj['id_image'], (version_compare(_PS_VERSION_, '1.7', '<') ? ImageType::getFormatedName('small') : ImageType::getFormattedName('small'))).'" alt="" class="imgm img-thumbnail" width="35" />';
            }
        }

        return $content;
    }
    
    protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
    	if (version_compare(_PS_VERSION_, '1.7', '<')) {
            return parent::l($string, $class, $addslashes, $htmlentities);
    	} else {
            return Translate::getModuleTranslation('wic_erp', $string, 'AdminErpProductsController');
    	}
    }
}
