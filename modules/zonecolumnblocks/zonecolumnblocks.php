<?php
/**
 * 2007-2017 PrestaShop.
 *
 * PHP version 5
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 *  @author    PrestaShop SA <contact@prestashop.com>
 *  @copyright 2007-2017 PrestaShop SA
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 *  International Registered Trademark & Property of PrestaShop SA
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

use PrestaShop\PrestaShop\Adapter\Category\CategoryProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\PricesDrop\PricesDropProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\NewProducts\NewProductsProductSearchProvider;
use PrestaShop\PrestaShop\Adapter\BestSales\BestSalesProductSearchProvider;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchContext;
use PrestaShop\PrestaShop\Core\Product\Search\ProductSearchQuery;
use PrestaShop\PrestaShop\Core\Product\Search\SortOrder;

include_once dirname(__FILE__).'/classes/ZColumnBlock.php';

class ZOneColumnBlocks extends Module
{
    protected $html = '';
    protected $currentIndex;
    protected $btproduct = 'blocktype_product';
    protected $bthtml = 'blocktype_html';
    protected $ptfeatures = 'products_featured';
    protected $ptnew = 'products_new';
    protected $ptspecial = 'products_special';
    protected $ptseller = 'products_seller';
    protected $ptselected = 'products_selected';
    protected $ptcategory = 'products_category';
    protected $order_by_values = array(
        0 => 'name',
        1 => 'price',
        2 => 'date_add',
        3 => 'date_upd',
        4 => 'position',
        5 => 'manufacturer_name',
        6 => 'quantity',
        7 => 'reference'
    );
    protected $order_way_values = array(
        0 => 'asc',
        1 => 'desc'
    );

    public function __construct()
    {
        $this->name = 'zonecolumnblocks';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Mr.ZOne';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->getTranslator()->trans(
            'Z.One - Column Blocks',
            array(),
            'Modules.ZoneColumnblocks.Admin'
        );
        $this->description = $this->getTranslator()->trans(
            'Flexible Products and Banners on the Columns',
            array(),
            'Modules.ZoneColumnblocks.Admin'
        );

        $this->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
    }

    public function install()
    {
        if (!file_exists(dirname(__FILE__).'/sql/install.sql')) {
            return false;
        } elseif (!$sql = Tools::file_get_contents(dirname(__FILE__).'/sql/install.sql')) {
            return false;
        }
        $sql = str_replace(array('PREFIX_', 'ENGINE_TYPE'), array(_DB_PREFIX_, _MYSQL_ENGINE_), $sql);
        $sql = preg_split("/;\s*[\r\n]+/", trim($sql));

        foreach ($sql as $query) {
            if (!Db::getInstance()->execute(trim($query))) {
                return false;
            }
        }

        return parent::install()
            && $this->registerHook('addproduct')
            && $this->registerHook('updateproduct')
            && $this->registerHook('deleteproduct')
            && $this->registerHook('actionCategoryAdd')
            && $this->registerHook('actionCategoryUpdate')
            && $this->registerHook('actionCategoryDelete')
            && $this->registerHook('updateOrderStatus')
            && $this->registerHook('displayLeftColumn')
        ;
    }

    public function uninstall()
    {
        $sql = 'DROP TABLE IF EXISTS
            `'._DB_PREFIX_.'zcolumnblock`,
            `'._DB_PREFIX_.'zcolumnblock_lang`';

        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $this->_clearCache('*');

        return parent::uninstall();
    }

    protected function about()
    {
        $this->smarty->assign(array(
            'doc_url' => $this->_path.'documentation.pdf',
        ));

        return $this->display(__FILE__, 'views/templates/admin/about.tpl');
    }

    public function backOfficeHeader()
    {
        Media::addJsDef(array(
            'blocktype_product' => $this->btproduct,
            'blocktype_html' => $this->bthtml,
            'products_selected' => $this->ptselected,
            'products_category' => $this->ptcategory,
        ));

        $this->context->controller->addJqueryPlugin('tablednd');
        $this->context->controller->addJS($this->_path.'views/js/position.js');
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }

    public function getContent()
    {
        $this->backOfficeHeader();

        $about = $this->about();

        if (Tools::isSubmit('savezonecolumnblock')) {
            if ($this->processSaveColumnBlock()) {
                return $this->html.$this->renderColumnBlockList();
            } else {
                return $this->html.$this->renderColumnBlockForm();
            }
        } elseif (Tools::isSubmit('addzonecolumnblock') || Tools::isSubmit('updatezonecolumnblock')) {
            return $this->renderColumnBlockForm();
        } elseif (Tools::isSubmit('deletezonecolumnblock')) {
            $zcolumnblock = new ZColumnBlock((int) Tools::getValue('id_zcolumnblock'));
            $zcolumnblock->delete();
            $this->_clearCache('*');
            Tools::redirectAdmin($this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'));
        } elseif (Tools::isSubmit('statuszonecolumnblock')) {
            $this->ajaxStatusColumnBlock();
        } elseif (Tools::getValue('updatePositions') == 'zonecolumnblock') {
            $this->ajaxPositionsColumnBlock();
        } elseif (Tools::isSubmit('ajaxProductsList')) {
            $this->ajaxProductsList();
        } else {
            return $this->renderColumnBlockList().$about;
        }
    }

    protected function ajaxProductsList()
    {
        $query = Tools::getValue('q', false);
        if (!$query || $query == '' || Tools::strlen($query) < 1) {
            die();
        }
        if ($pos = strpos($query, ' (ref:')) {
            $query = Tools::substr($query, 0, $pos);
        }

        $sql = 'SELECT p.`id_product`, pl.`link_rewrite`, p.`reference`, pl.`name`
            FROM `'._DB_PREFIX_.'product` p
            LEFT JOIN `'._DB_PREFIX_.'product_lang` pl
                ON (pl.id_product = p.id_product
                AND pl.id_lang = '.(int) Context::getContext()->language->id.Shop::addSqlRestrictionOnLang('pl').')
            WHERE (pl.name LIKE \'%'.pSQL($query).'%\'
                OR p.reference LIKE \'%'.pSQL($query).'%\')
            GROUP BY p.`id_product`';

        $items = Db::getInstance()->executeS($sql);

        if ($items) {
            foreach ($items as $item) {
                $item['name'] = str_replace('|', '-', $item['name']);
                echo trim($item['name']).(!empty($item['reference']) ? ' (ref: '.$item['reference'].')' : '').'|'.(int) $item['id_product']."\n";
            }
        } else {
            Tools::jsonEncode(new stdClass());
        }
    }

    protected function ajaxStatusColumnBlock()
    {
        $id_zcolumnblock = (int)Tools::getValue('id_zcolumnblock');
        if (!$id_zcolumnblock) {
            die(Tools::jsonEncode(array(
                'success' => false,
                'error' => true,
                'text' => $this->getTranslator()->trans(
                    'Failed to update the status',
                    array(),
                    'Admin.Notifications.Error'
                )
            )));
        } else {
            $zcolumnblock = new ZColumnBlock($id_zcolumnblock);
            $zcolumnblock->active = !(int)$zcolumnblock->active;
            if ($zcolumnblock->save()) {
                $this->_clearCache('*');
                die(Tools::jsonEncode(array(
                    'success' => true,
                    'text' => $this->getTranslator()->trans(
                        'The status has been updated successfully',
                        array(),
                        'Admin.Notifications.Success'
                    )
                )));
            } else {
                die(Tools::jsonEncode(array(
                    'success' => false,
                    'error' => true,
                    'text' => $this->getTranslator()->trans(
                        'Failed to update the status',
                        array(),
                        'Admin.Notifications.Error'
                    )
                )));
            }
        }
    }

    protected function ajaxPositionsColumnBlock()
    {
        $positions = Tools::getValue('zcolumnblock');

        if (empty($positions)) {
            return;
        }

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2])) {
                ZColumnBlock::updatePosition($pos[2], $position + 1);
            }
        }

        $this->_clearCache('*');
    }

    protected function processSaveColumnBlock()
    {
        $zcolumnblock = new ZColumnBlock();
        $id_zcolumnblock = (int) Tools::getValue('id_zcolumnblock');
        if ($id_zcolumnblock) {
            $zcolumnblock = new ZColumnBlock($id_zcolumnblock);
        }

        $zcolumnblock->position = (int) Tools::getValue('position');
        $zcolumnblock->active = (int) Tools::getValue('active');
        $zcolumnblock->block_type = Tools::getValue('block_type');
        $zcolumnblock->custom_class = Tools::getValue('custom_class');
        $zcolumnblock->product_filter = Tools::getValue('product_filter');

        $product_options = array();
        $product_options['limit'] = Tools::getValue('limit');
        $product_options['enable_slider'] = Tools::getValue('enable_slider');
        $product_options['auto_scroll'] = Tools::getValue('auto_scroll');
        $product_options['product_thumb'] = Tools::getValue('product_thumb');
        $product_options['selected_products'] = Tools::getValue('selected_products');
        $product_options['selected_category'] = Tools::getValue('selected_category');
        $zcolumnblock->product_options = $product_options;

        $languages = Language::getLanguages(false);
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $title = array();
        $static_html = array();
        foreach ($languages as $lang) {
            $title[$lang['id_lang']] = Tools::getValue('title_'.$lang['id_lang']);
            $static_html[$lang['id_lang']] = Tools::getValue('static_html_'.$lang['id_lang']);
            if (!$static_html[$lang['id_lang']]) {
                $static_html[$lang['id_lang']] = Tools::getValue('static_html_'.$id_lang_default);
            }
        }
        $zcolumnblock->title = $title;
        $zcolumnblock->static_html = $static_html;

        $result = $zcolumnblock->validateFields(false) && $zcolumnblock->validateFieldsLang(false);

        if ($result) {
            $zcolumnblock->save();

            if ($id_zcolumnblock) {
                $this->html .= $this->displayConfirmation($this->getTranslator()->trans(
                    'Block Content has been updated.',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                ));
            } else {
                $this->html .= $this->displayConfirmation($this->getTranslator()->trans(
                    'Block Content has been created successfully.',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                ));
            }

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->getTranslator()->trans(
                'An error occurred while attempting to save Block Content.',
                array(),
                'Modules.ZoneColumnblocks.Admin'
            ));
        }

        return $result;
    }

    protected function renderColumnBlockList()
    {
        $blocks = ZColumnBlock::getList((int) $this->context->language->id, false);

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->toolbar_btn['new'] = array(
            'href' => $this->currentIndex.'&addzonecolumnblock&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->getTranslator()->trans(
                'Add New',
                array(),
                'Admin.Actions'
            ),
        );
        $helper->simple_header = false;
        $helper->listTotal = count($blocks);
        $helper->identifier = 'id_zcolumnblock';
        $helper->table = 'zonecolumnblock';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->no_link = true;
        $helper->module = $this;
        $helper->title = $this->getTranslator()->trans(
            'Column Blocks',
            array(),
            'Modules.ZoneColumnblocks.Admin'
        );
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->currentIndex;
        $helper->position_identifier = 'zcolumnblock';
        $helper->position_group_identifier = 0;

        $helper->tpl_vars = array('block_type' => array(
            $this->btproduct => array(
                $this->ptfeatures => $this->getTranslator()->trans(
                    'Featured Products',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                ),
                $this->ptnew => $this->getTranslator()->trans(
                    'New Products',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                ),
                $this->ptspecial => $this->getTranslator()->trans(
                    'Special Products',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                ),
                $this->ptseller => $this->getTranslator()->trans(
                    'Best Seller Products',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                ),
                $this->ptselected => $this->getTranslator()->trans(
                    'Selected Products',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                ),
                $this->ptcategory => $this->getTranslator()->trans(
                    'Products from Category',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                )
            ),
            $this->bthtml => $this->getTranslator()->trans(
                'Static HTML',
                array(),
                'Modules.ZoneColumnblocks.Admin'
            ),
        ));

        return $helper->generateList($blocks, $this->getColumnBlockList());
    }

    protected function getColumnBlockList()
    {
        $fields_list = array(
            'id_zcolumnblock' => array(
                'title' => $this->getTranslator()->trans(
                    'Block ID',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                ),
                'align' => 'center',
                'class' => 'fixed-width-xs',
                'orderby' => false,
                'search' => false,
                'type' => 'zid_columnblock',
            ),
            'title' => array(
                'title' => $this->getTranslator()->trans(
                    'Title',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                ),
                'orderby' => false,
                'search' => false,
            ),
            'block_type' => array(
                'title' => $this->getTranslator()->trans(
                    'Content Type',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                ),
                'orderby' => false,
                'search' => false,
                'type' => 'zblocktype',
            ),
            'position' => array(
                'title' => $this->getTranslator()->trans(
                    'Position',
                    array(),
                    'Admin.Global'
                ),
                'align' => 'center',
                'orderby' => false,
                'search' => false,
                'class' => 'fixed-width-md',
                'position' => true,
                'type' => 'zposition',
            ),
            'active' => array(
                'title' => $this->getTranslator()->trans(
                    'Displayed',
                    array(),
                    'Admin.Global'
                ),
                'active' => 'status',
                'type' => 'bool',
                'class' => 'fixed-width-xs',
                'align' => 'center',
                'ajax' => true,
                'orderby' => false,
                'search' => false,
            ),
        );

        return $fields_list;
    }

    protected function renderColumnBlockForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));
        
        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'savezonecolumnblock';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getColumnBlockFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
            'module_dir' => $this->_path,
        );

        $form = $helper->generateForm(array($this->getColumnBlockForm()));

        Context::getContext()->smarty->assign('token', Tools::getAdminTokenLite('AdminModules'));
        
        return $form;
    }

    protected function getColumnBlockForm()
    {
        $id_zcolumnblock = (int) Tools::getValue('id_zcolumnblock');
        $zcolumnblock = new ZColumnBlock($id_zcolumnblock, (int) $this->context->language->id);
        $default_category = isset($zcolumnblock->product_options['selected_category']) ? (int) $zcolumnblock->product_options['selected_category'] : 0;
        $selected_category = array((int) Tools::getValue('selected_category', $default_category));
        $root = Category::getRootCategory();

        $legent_title = $this->getTranslator()->trans(
            'Add New Block',
            array(),
            'Modules.ZoneColumnblocks.Admin'
        );
        if ($id_zcolumnblock) {
            $legent_title = $this->getTranslator()->trans(
                'Edit Block',
                array(),
                'Modules.ZoneColumnblocks.Admin'
            );
        }

        $block_type_options = array(
            'query' => array(
                array('id' => $this->btproduct, 'name' => $this->getTranslator()->trans(
                    'Product Block',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                )),
                array('id' => $this->bthtml, 'name' => $this->getTranslator()->trans(
                    'Static HTML',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                )),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $product_filter_options = array(
            'query' => array(
                array('id' => $this->ptfeatures, 'name' => $this->getTranslator()->trans(
                    'Featured Products',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                )),
                array('id' => $this->ptnew, 'name' => $this->getTranslator()->trans(
                    'New Products',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                )),
                array('id' => $this->ptspecial, 'name' => $this->getTranslator()->trans(
                    'Special Products',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                )),
                array('id' => $this->ptseller, 'name' => $this->getTranslator()->trans(
                    'Best Seller Products',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                )),
                array('id' => $this->ptselected, 'name' => $this->getTranslator()->trans(
                    'Selected Products',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                )),
                array('id' => $this->ptcategory, 'name' => $this->getTranslator()->trans(
                    'Products from Category',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                )),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $product_thumb_options = array(
            'query' => array(
                array('id' => 'top', 'name' => $this->getTranslator()->trans(
                    'Top',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                )),
                array('id' => 'left', 'name' => $this->getTranslator()->trans(
                    'Left',
                    array(),
                    'Modules.ZoneColumnblocks.Admin'
                )),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $legent_title,
                    'icon' => 'icon-book',
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'id_zcolumnblock',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans(
                            'Title',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ),
                        'name' => 'title',
                        'lang' => true,
                        'required' => true,
                        'col' => 5,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Displayed',
                            array(),
                            'Admin.Global'
                        ),
                        'name' => 'active',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'active_on',
                                'value' => true,
                                'label' => $this->getTranslator()->trans(
                                    'Enabled',
                                    array(),
                                    'Admin.Global'
                                ),
                            ),
                            array(
                                'id' => 'active_off',
                                'value' => false,
                                'label' => $this->getTranslator()->trans(
                                    'Disabled',
                                    array(),
                                    'Admin.Global'
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'position',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans(
                            'Custom CSS Class',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ),
                        'name' => 'custom_class',
                        'hint' => $this->getTranslator()->trans(
                            'Using it for special stylesheet.',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ),
                        'col' => 3,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->getTranslator()->trans(
                            'Block Type',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ),
                        'name' => 'block_type',
                        'id' => 'block_type_selectbox',
                        'options' => $block_type_options,
                    ),
                    array(
                        'type' => 'html',
                        'name' => 'product_option_title',
                        'html_content' => '<h4>'.$this->getTranslator()->trans(
                            'Product Block',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ).'</h4>',
                        'form_group_class' => 'block_type_product',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans(
                            'Number of Products',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ),
                        'name' => 'limit',
                        'form_group_class' => 'block_type_product',
                        'hint' => $this->getTranslator()->trans(
                            'The number of products to be displayed.',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ),
                        'col' => 1,
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Enable Slider',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ),
                        'name' => 'enable_slider',
                        'form_group_class' => 'block_type_product',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'slider_on',
                                'value' => true,
                                'label' => $this->getTranslator()->trans(
                                    'Yes',
                                    array(),
                                    'Admin.Global'
                                ),
                            ),
                            array(
                                'id' => 'slider_off',
                                'value' => false,
                                'label' => $this->getTranslator()->trans(
                                    'No',
                                    array(),
                                    'Admin.Global'
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Slider Autoplay',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ),
                        'name' => 'auto_scroll',
                        'form_group_class' => 'block_type_product',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'scroll_on',
                                'value' => true,
                                'label' => $this->getTranslator()->trans(
                                    'Yes',
                                    array(),
                                    'Admin.Global'
                                ),
                            ),
                            array(
                                'id' => 'scroll_off',
                                'value' => false,
                                'label' => $this->getTranslator()->trans(
                                    'No',
                                    array(),
                                    'Admin.Global'
                                ),
                            ),
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->getTranslator()->trans(
                            'Product Thumbnail Position',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ),
                        'name' => 'product_thumb',
                        'form_group_class' => 'block_type_product',
                        'options' => $product_thumb_options,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->getTranslator()->trans(
                            'Get Products From',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ),
                        'name' => 'product_filter',
                        'id' => 'product_filter_selectbox',
                        'form_group_class' => 'block_type_product',
                        'options' => $product_filter_options,
                    ),
                    array(
                        'type' => 'product_autocomplete',
                        'label' => $this->getTranslator()->trans(
                            'Select the Products',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ),
                        'name' => 'selected_products',
                        'form_group_class' => 'block_type_product filter_selected_products',
                        'ajax_path' => $this->currentIndex.'&ajax=1&ajaxProductsList&token='.Tools::getAdminTokenLite('AdminModules'),
                        'hint' => $this->getTranslator()->trans(
                            'Begin typing the First Letters of the Product Name, then select the Product from the Drop-down List.',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ),
                    ),
                    array(
                        'type' => 'categories',
                        'label' => $this->getTranslator()->trans(
                            'Select a Category',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ),
                        'name' => 'selected_category',
                        'form_group_class' => 'block_type_product filter_select_category',
                        'tree' => array(
                            'use_search' => false,
                            'id' => 'categoryBox',
                            'root_category' => $root->id,
                            'selected_categories' => $selected_category,
                        ),
                    ),
                    array(
                        'type' => 'html',
                        'name' => 'static_html_title',
                        'html_content' => '<h4>'.$this->getTranslator()->trans(
                            'Static HTML',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ).'</h4>',
                        'form_group_class' => 'block_type_static_html',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->getTranslator()->trans(
                            'Content',
                            array(),
                            'Modules.ZoneColumnblocks.Admin'
                        ),
                        'name' => 'static_html',
                        'form_group_class' => 'block_type_static_html',
                        'autoload_rte' => true,
                        'lang' => true,
                        'rows' => 10,
                        'cols' => 100,
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans(
                        'Save',
                        array(),
                        'Admin.Actions'
                    ),
                ),
                'buttons' => array(
                    array(
                        'href' => $this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'),
                        'title' => $this->getTranslator()->trans(
                            'Cancel',
                            array(),
                            'Admin.Actions'
                        ),
                        'icon' => 'process-icon-cancel',
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getColumnBlockFormValues()
    {
        $fields_value = array();

        $id_zcolumnblock = (int) Tools::getValue('id_zcolumnblock');
        $zcolumnblock = new ZColumnBlock($id_zcolumnblock);

        $fields_value['id_zcolumnblock'] = $id_zcolumnblock;
        $fields_value['active'] = Tools::getValue('active', $zcolumnblock->active);
        $fields_value['position'] = Tools::getValue('position', $zcolumnblock->position);
        $fields_value['block_type'] = Tools::getValue('block_type', $zcolumnblock->block_type);
        $fields_value['custom_class'] = Tools::getValue('custom_class', $zcolumnblock->custom_class);
        $fields_value['product_filter'] = Tools::getValue('product_filter', $zcolumnblock->product_filter);

        $fields_value['selected_products'] = $zcolumnblock->getProductsAutocompleteInfo($this->context->language->id);
        $fields_value['limit'] = Tools::getValue('limit', $zcolumnblock->product_options['limit']);
        $fields_value['enable_slider'] = Tools::getValue('enable_slider', $zcolumnblock->product_options['enable_slider']);
        $fields_value['auto_scroll'] = Tools::getValue('auto_scroll', $zcolumnblock->product_options['auto_scroll']);
        $fields_value['product_thumb'] = Tools::getValue('product_thumb', $zcolumnblock->product_options['product_thumb']);

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $default_title = isset($zcolumnblock->title[$lang['id_lang']]) ? $zcolumnblock->title[$lang['id_lang']] : '';
            $fields_value['title'][$lang['id_lang']] = Tools::getValue('title_'.(int) $lang['id_lang'], $default_title);
            $default_static_html = isset($zcolumnblock->static_html[$lang['id_lang']]) ? $zcolumnblock->static_html[$lang['id_lang']] : '';
            $fields_value['static_html'][$lang['id_lang']] = Tools::getValue('static_html_'.(int) $lang['id_lang'], $default_static_html);
        }

        return $fields_value;
    }

    public function hookAddProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookUpdateProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookDeleteProduct($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionCategoryAdd($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionCategoryUpdate($params)
    {
        $this->_clearCache('*');
    }

    public function hookActionCategoryDelete($params)
    {
        $this->_clearCache('*');
    }

    public function hookUpdateOrderStatus($params)
    {
        $this->_clearCache('*');
    }

    protected function getProducts($product_filter, $product_options)
    {
        $nProducts = $product_options['limit'];
        if ($nProducts < 0) {
            $nProducts = 10;
        }

        $searchContext = new ProductSearchContext($this->context);

        $query = new ProductSearchQuery();

        $query
            ->setResultsPerPage($nProducts)
            ->setPage(1)
        ;

        $query->setSortOrder(new SortOrder('product', 'position', 'asc'));

        $searchProvider = false;
        $products = false;

        if ($product_filter == $this->ptfeatures) {
            $category = new Category((int) $this->context->shop->getCategory());

            if (Validate::isLoadedObject($category)) {
                $searchProvider = new CategoryProductSearchProvider(
                    $this->context->getTranslator(),
                    $category
                );
            }
        } elseif ($product_filter == $this->ptnew) {
            $query
                ->setQueryType('new-products')
                ->setSortOrder(new SortOrder('product', 'date_add', 'desc'))
            ;

            $searchProvider = new NewProductsProductSearchProvider(
                $this->context->getTranslator()
            );
        } elseif ($product_filter == $this->ptspecial) {
            $query->setQueryType('prices-drop');

            $searchProvider = new PricesDropProductSearchProvider(
                $this->context->getTranslator()
            );
        } elseif ($product_filter == $this->ptseller) {
            $query
                ->setQueryType('best-sales')
                ->setSortOrder(new SortOrder('product', 'sale_nbr', 'desc'))
            ;

            $searchProvider = new BestSalesProductSearchProvider(
                $this->context->getTranslator()
            );
        } elseif ($product_filter == $this->ptselected && isset($product_options['selected_products'])) {
            $products = ZColumnBlock::getProductsByArrayId($product_options['selected_products']);
        } elseif ($product_filter == $this->ptcategory && isset($product_options['selected_category'])) {
            $category = new Category((int) $product_options['selected_category']);

            if (Validate::isLoadedObject($category)) {
                $searchProvider = new CategoryProductSearchProvider(
                    $this->context->getTranslator(),
                    $category
                );
            }
        }

        if ($searchProvider) {
            $result = $searchProvider->runQuery(
                $searchContext,
                $query
            );

            $products = $result->getProducts();
        }

        $present_products = array();

        if ($products) {
            $assembler = new ProductAssembler($this->context);

            $presenterFactory = new ProductPresenterFactory($this->context);
            $presentationSettings = $presenterFactory->getPresentationSettings();
            $presenter = new ProductListingPresenter(
                new ImageRetriever($this->context->link),
                $this->context->link,
                new PriceFormatter(),
                new ProductColorsRetriever(),
                $this->context->getTranslator()
            );

            foreach ($products as $rawProduct) {
                $present_products[] = $presenter->present(
                    $presentationSettings,
                    $assembler->assembleProduct($rawProduct),
                    $this->context->language
                );
            }
        }

        return $present_products;
    }

    protected function preProcess()
    {
        $id_lang = (int) $this->context->language->id;
        $column_blocks = array();

        $zcolumnblocks = ZColumnBlock::getList($id_lang);
        if (!empty($zcolumnblocks)) {
            foreach ($zcolumnblocks as $zcolumnblock) {
                if ($zcolumnblock['block_type'] == $this->btproduct) {
                    $zcolumnblock['product_options'] = ZColumnBlock::initProductOptions($zcolumnblock['product_options']);

                    $products = $this->getProducts(
                        $zcolumnblock['product_filter'],
                        $zcolumnblock['product_options']
                    );

                    $column_blocks[] = array(
                        'id' => $zcolumnblock['id_zcolumnblock'],
                        'title' => $zcolumnblock['title'],
                        'block_type' => $zcolumnblock['block_type'],
                        'custom_class' => $zcolumnblock['custom_class'],
                        'enable_slider' => $zcolumnblock['product_options']['enable_slider'],
                        'auto_scroll' => $zcolumnblock['product_options']['auto_scroll'] ? 'true' : 'false',
                        'product_thumb' => $zcolumnblock['product_options']['product_thumb'],
                        'products' => $products,
                    );
                } elseif ($zcolumnblock['block_type'] == $this->bthtml) {
                    $static_html = $zcolumnblock['static_html'];
                    $column_blocks[] = array(
                        'id' => $zcolumnblock['id_zcolumnblock'],
                        'title' => $zcolumnblock['title'],
                        'block_type' => $zcolumnblock['block_type'],
                        'custom_class' => $zcolumnblock['custom_class'],
                        'static_html' => $static_html,
                    );
                }
            }
        }

        $this->smarty->assign(array(
            'columnBlocks' => $column_blocks,
            'blocktype_product' => $this->btproduct,
            'blocktype_html' => $this->bthtml,
        ));
    }

    public function hookDisplayLeftColumn()
    {
        if (!$this->isCached('zonecolumnblocks.tpl', $this->getCacheId())) {
            $this->preProcess();
        }

        return $this->display(__FILE__, 'zonecolumnblocks.tpl', $this->getCacheId());
    }

    public function hookDisplayRightColumn()
    {
        return $this->hookDisplayLeftColumn();
    }
}
