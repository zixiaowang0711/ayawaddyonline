<?php
/**
 * 2007-2017 PrestaShop.
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

include_once dirname(__FILE__).'/classes/ZManager.php';

class ZOneThemeManager extends Module
{
    protected $html = '';
    protected $action;
    protected $currentIndex;
    protected $image_folder = 'views/img/front/';
    protected $static_pages;
    protected $social_keys;

    public function __construct()
    {
        $this->name = 'zonethememanager';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Mr.ZOne';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->getTranslator()->trans(
            'Z.One - Theme Manager',
            array(),
            'Modules.ZoneThememanager.Admin'
        );
        $this->description = $this->getTranslator()->trans(
            'Configure the main elements of ZOne theme.',
            array(),
            'Modules.ZoneThememanager.Admin'
        );

        $this->static_pages = array(
            'stores' => $this->getTranslator()->trans(
                'Our Stores',
                array(),
                'Modules.ZoneThememanager.Admin'
            ),
            'prices-drop' => $this->getTranslator()->trans(
                'Price Drop',
                array(),
                'Modules.ZoneThememanager.Admin'
            ),
            'new-products' => $this->getTranslator()->trans(
                'New Products',
                array(),
                'Modules.ZoneThememanager.Admin'
            ),
            'best-sales' => $this->getTranslator()->trans(
                'Best Sales',
                array(),
                'Modules.ZoneThememanager.Admin'
            ),
            'contact' => $this->getTranslator()->trans(
                'Contact us',
                array(),
                'Modules.ZoneThememanager.Admin'
            ),
            'sitemap' => $this->getTranslator()->trans(
                'Sitemap',
                array(),
                'Modules.ZoneThememanager.Admin'
            ),
        );

        $this->action = Tools::getValue('action', 'general');
        $this->currentIndex = AdminController::$currentIndex.'&configure='.$this->name.'&action='.$this->action;
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
            && $this->registerHook('header')
            && $this->registerHook('displayFooterLeft')
            && $this->registerHook('displayFooterRight')
            && $this->registerHook('displayFooterAfter')
            && $this->registerHook('displayBanner')
            && $this->registerHook('displayNav1')
            && $this->registerHook('displaySidebarNavigation')
        ;
    }

    public function uninstall()
    {
        $sql = 'DROP TABLE IF EXISTS
            `'._DB_PREFIX_.'zthememanager`,
            `'._DB_PREFIX_.'zthememanager_lang`';

        if (!Db::getInstance()->execute($sql)) {
            return false;
        }

        $this->_clearCache('*');

        return parent::uninstall();
    }

    public function getContent()
    {
        $this->context->controller->addCSS($this->_path.'views/css/back.css');

        if (Tools::isSubmit('submitGeneralSettings')) {
            $this->processSaveGeneralSettings();
        } elseif (Tools::isSubmit('deleteBoxedBackgroundImage')) {
            $settings = ZManager::getSettingsByShop();
            $general_settings = $settings->general_settings;
            if ($general_settings['boxed_bg_img']) {
                $image_path = $this->local_path.$this->image_folder.$general_settings['boxed_bg_img'];

                if (file_exists($image_path)) {
                    unlink($image_path);
                }

                $general_settings['boxed_bg_img'] = false;
                $settings->general_settings = $general_settings;
                $settings->save();
                $this->_clearCache('*');
            }

            Tools::redirectAdmin($this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&conf=7');
        } elseif (Tools::isSubmit('submitHeaderSettings')) {
            $this->processSaveHeaderSettings();
        } elseif (Tools::isSubmit('submitFooterSettings')) {
            $this->processSaveFooterSettings();
        } elseif (Tools::isSubmit('submitCategorySettings')) {
            $this->processSaveCategorySettings();
        } elseif (Tools::isSubmit('submitProductSettings')) {
            $this->processSaveProductSettings();
        } elseif (Tools::isSubmit('submitConfigureZOne')) {
            if (Tools::getValue('overwrite_zone_settings', 0)) {
                $this->processImportZOneTables();
            }
        }

        $this->smarty->assign(array(
            'alert' => $this->html,
            'action' => Tools::getValue('action', 'general'),
            'panel_href' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules'),
            'doc_url' => $this->_path.'documentation.pdf',
            'settings_form' => $this->renderSettingsForm(),
        ));

        return $this->display(__FILE__, 'views/templates/admin/settings_form.tpl');
    }

    protected function processSaveGeneralSettings()
    {
        $settings = ZManager::getSettingsByShop();
        $general_settings = $settings->general_settings;

        $new_settings = array(
            'layout' => Tools::getValue('layout'),
            'boxed_bg_color' => Tools::getValue('boxed_bg_color', '#bdbdbd'),
            'boxed_bg_img' => $general_settings['boxed_bg_img'],
            'boxed_bg_img_style' => Tools::getValue('boxed_bg_img_style'),
            'sticky_menu' => (int) Tools::getValue('sticky_menu'),
            'sticky_mobile' => (int) Tools::getValue('sticky_mobile'),
            'scroll_top' => (int) Tools::getValue('scroll_top'),
            'progress_bar' => (int) Tools::getValue('progress_bar'),
            'sidebar_cart' => (int) Tools::getValue('sidebar_cart'),
            'navigation' => Tools::getValue('navigation'),
        );

        if (isset($_FILES['boxed_bg_img']) && isset($_FILES['boxed_bg_img']['tmp_name']) && !empty($_FILES['boxed_bg_img']['tmp_name'])) {
            if ($error = ImageManager::validateUpload($_FILES['boxed_bg_img'], Tools::convertBytes(ini_get('upload_max_filesize')))) {
                $this->html .= $this->displayError($error);
            } else {
                if (move_uploaded_file($_FILES['boxed_bg_img']['tmp_name'], $this->local_path.$this->image_folder.$_FILES['boxed_bg_img']['name'])) {
                    $new_settings['boxed_bg_img'] = $_FILES['boxed_bg_img']['name'];
                } else {
                    $this->html .= $this->displayError($this->getTranslator()->trans(
                        'File upload error.',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ));
                }
            }
        }

        $settings->general_settings = $new_settings;
        $settings->svg_logo = trim(Tools::getValue('svg_logo'));

        $result = $settings->validateFields(false) && $settings->validateFieldsLang(false);

        if ($result) {
            $settings->save();

            $this->html .= $this->displayConfirmation($this->getTranslator()->trans(
                'General Settings has been updated successfully.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->getTranslator()->trans(
                'An error occurred while attempting to save Settings.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));
        }

        return $result;
    }

    protected function processSaveHeaderSettings()
    {
        $settings = ZManager::getSettingsByShop();

        $settings->header_top_bg_color = Tools::getValue('header_top_bg_color');

        $languages = Language::getLanguages(false);
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $header_top = array();
        $header_phone = array();
        foreach ($languages as $lang) {
            $header_top[$lang['id_lang']] = Tools::getValue('header_top_'.$lang['id_lang']);
            if (!$header_top[$lang['id_lang']]) {
                $header_top[$lang['id_lang']] = Tools::getValue('header_top_'.$id_lang_default);
            }
            $header_phone[$lang['id_lang']] = Tools::getValue('header_phone_'.$lang['id_lang']);
            if (!$header_phone[$lang['id_lang']]) {
                $header_phone[$lang['id_lang']] = Tools::getValue('header_phone_'.$id_lang_default);
            }
        }
        $settings->header_top = $header_top;
        $settings->header_phone = $header_phone;
        $settings->header_save_date = strtotime('now');

        $result = $settings->validateFields(false) && $settings->validateFieldsLang(false);

        if ($result) {
            $settings->save();

            $this->html .= $this->displayConfirmation($this->getTranslator()->trans(
                'Header Settings has been updated successfully.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->getTranslator()->trans(
                'An error occurred while attempting to save Settings.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));
        }

        return $result;
    }

    protected function processSaveFooterSettings()
    {
        $settings = ZManager::getSettingsByShop();

        $settings->footer_cms_links = Tools::getValue('footer_cms_links', array());

        $languages = Language::getLanguages(false);
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $footer_about_us = array();
        $footer_static_links = array();
        $footer_bottom = array();
        foreach ($languages as $lang) {
            $footer_about_us[$lang['id_lang']] = Tools::getValue('footer_about_us_'.$lang['id_lang']);
            if (!$footer_about_us[$lang['id_lang']]) {
                $footer_about_us[$lang['id_lang']] = Tools::getValue('footer_about_us_'.$id_lang_default);
            }
            $footer_static_links[$lang['id_lang']] = Tools::getValue('footer_static_links_'.$lang['id_lang']);
            if (!$footer_static_links[$lang['id_lang']]) {
                $footer_static_links[$lang['id_lang']] = Tools::getValue('footer_static_links_'.$id_lang_default);
            }
            $footer_bottom[$lang['id_lang']] = Tools::getValue('footer_bottom_'.$lang['id_lang']);
            if (!$footer_bottom[$lang['id_lang']]) {
                $footer_bottom[$lang['id_lang']] = Tools::getValue('footer_bottom_'.$id_lang_default);
            }
        }
        $settings->footer_about_us = $footer_about_us;
        $settings->footer_static_links = $footer_static_links;
        $settings->footer_bottom = $footer_bottom;

        $result = $settings->validateFields(false) && $settings->validateFieldsLang(false);
        if ($result) {
            $settings->save();

            $this->html .= $this->displayConfirmation($this->getTranslator()->trans(
                'Footer Settings has been updated successfully.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->getTranslator()->trans(
                'An error occurred while attempting to save Settings.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));
        }

        return $result;
    }

    protected function processSaveCategorySettings()
    {
        $settings = ZManager::getSettingsByShop();

        $new_settings = array(
            'show_image' => (int) Tools::getValue('show_image'),
            'show_description' => (int) Tools::getValue('show_description'),
            'show_subcategories' => (int) Tools::getValue('show_subcategories'),
            'product_grid_columns' => (int) Tools::getValue('product_grid_columns'),
            'buy_in_new_line' => (int) Tools::getValue('buy_in_new_line'),
            'default_product_view' => Tools::getValue('default_product_view'),
        );

        $settings->category_settings = $new_settings;

        $result = $settings->validateFields(false) && $settings->validateFieldsLang(false);

        if ($result) {
            $settings->save();

            $this->html .= $this->displayConfirmation($this->getTranslator()->trans(
                'Category Page Settings has been updated successfully.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->getTranslator()->trans(
                'An error occurred while attempting to save Settings.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));
        }

        return $result;
    }

    protected function processSaveProductSettings()
    {
        $settings = ZManager::getSettingsByShop();

        $new_settings = array(
            'product_info_layout' => Tools::getValue('product_info_layout'),
            'product_image_zoom' => (int) Tools::getValue('product_image_zoom'),
        );

        $settings->product_settings = $new_settings;

        $result = $settings->validateFields(false) && $settings->validateFieldsLang(false);

        if ($result) {
            $settings->save();

            $this->html .= $this->displayConfirmation($this->getTranslator()->trans(
                'Product Page Settings has been updated successfully.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->getTranslator()->trans(
                'An error occurred while attempting to save Settings.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));
        }

        return $result;
    }

    protected function processImportZOneTables()
    {
        $sql_file = $this->local_path.'sql/zone_modules.sql';
        $result = true;
        if (!file_exists($sql_file)) {
            $result = false;
        } elseif (!$sql = Tools::file_get_contents($sql_file)) {
            $result = false;
        }

        $sql = str_replace(array('PREFIX_', 'BASE_URL'), array(_DB_PREFIX_, $this->context->shop->getBaseURL(true)), $sql);
        $sql = preg_split("/;\s*[\r\n]+/", trim($sql));
        foreach ($sql as $query) {
            $result &= Db::getInstance()->execute($query);
        }

        $img_folder = $this->local_path.'views/img/cms/';
        $cms_folder = _PS_IMG_DIR_.'cms/';
        $cms_imgs = glob($img_folder.'*.{jpg,png}', GLOB_BRACE);
        foreach($cms_imgs as $img) {
            $file_to_go = str_replace($img_folder, $cms_folder, $img);
            Tools::copy($img, $file_to_go);
        }
        
        $tables_with_id_shop = array(
            'zcolorsfonts',
            'zcolumnblock',
            'zhomeblock',
            'zthememanager',
            'zmenu',
            'zpopupnewsletter',
            'zslideshow',
            'zproduct_extra_field',
        );
        $sql = 'UPDATE ';
        foreach ($tables_with_id_shop as &$t) {
            $t = _DB_PREFIX_.$t;
        }
        $sql .= implode(', ', $tables_with_id_shop).' SET ';
        foreach ($tables_with_id_shop as &$t) {
            $t = $t.'.`id_shop` = '.(int) $this->context->shop->id;
        }
        $sql .= implode(', ', $tables_with_id_shop);
        $result &= Db::getInstance()->execute($sql);

        $sql = 'UPDATE '._DB_PREFIX_.'zmenu_lang SET link = \'#\' WHERE 1';
        $result &= Db::getInstance()->execute($sql);

        $sql = 'UPDATE '._DB_PREFIX_.'zthememanager SET svg_logo = \'\' WHERE 1';
        $result &= Db::getInstance()->execute($sql);

        $sql_cats = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS(
            'SELECT *
            FROM `'._DB_PREFIX_.'category` c
            '.Shop::addSqlAssociation('category', 'c').'
            WHERE c.`id_parent` != 0 AND c.`id_parent` != 1
            AND `active` = 1
            ORDER BY c.`id_parent` ASC
            LIMIT 3'
        );
        if ($sql_cats) {
            $array_cats = array();
            foreach ($sql_cats as $row) {
                $array_cats[] = $row['id_category'];
            }
            $afc_settings = Tools::unSerialize(Configuration::get('ZONEFEATUREDCATEGORIES_SETTINGS'));
            $afc_settings['featuredCategories'] = $array_cats;
            Configuration::updateValue('ZONEFEATUREDCATEGORIES_SETTINGS', serialize($afc_settings));
        }

        $tables_with_id_lang = array(
            'zcolumnblock_lang',
            'zdropdown_lang',
            'zhomeblock_lang',
            'zhometab_lang',
            'zmenu_lang',
            'zpopupnewsletter_lang',
            'zproduct_extra_field_lang',
            'zslideshow_lang',
            'zthememanager_lang',
        );
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');

        $langTables = array();
        foreach ($tables_with_id_lang as $t) {
            $langTables[] = _DB_PREFIX_.$t;
        }

        $sql = 'UPDATE ';
        $sql .= implode(', ', $langTables).' SET ';
        $set_sql = array();
        foreach ($langTables as $table) {
            $set_sql[] = '`'.$table.'`.`id_lang` = '.(int) $id_lang_default;
        }
        $sql .= implode(', ', $set_sql);
        $result &= Db::getInstance()->execute($sql);
        
        foreach ($langTables as $name) {
            preg_match('#^'.preg_quote(_DB_PREFIX_).'(.+)_lang$#i', $name, $m);
            $identifier = 'id_'.$m[1];

            $fields = '';
            $primary_key_exists = false;
            $columns = Db::getInstance()->executeS('SHOW COLUMNS FROM `'.$name.'`');
            foreach ($columns as $column) {
                $fields .= '`'.$column['Field'].'`, ';
                if ($column['Field'] == $identifier) {
                    $primary_key_exists = true;
                }
            }
            $fields = rtrim($fields, ', ');

            if (!$primary_key_exists) {
                continue;
            }

            $sql = 'INSERT IGNORE INTO `'.$name.'` ('.$fields.') (SELECT ';

            reset($columns);
            foreach ($columns as $column) {
                if ($identifier != $column['Field'] && $column['Field'] != 'id_lang') {
                    $sql .= '(
                        SELECT `'.bqSQL($column['Field']).'`
                        FROM `'.bqSQL($name).'` tl
                        WHERE tl.`id_lang` = '.(int) $id_lang_default.'
                        AND tl.`'.bqSQL($identifier).'` = `'.bqSQL(str_replace('_lang', '', $name)).'`.`'.bqSQL($identifier).'`
                    ),';
                } else {
                    $sql .= '`'.bqSQL($column['Field']).'`,';
                }
            }

            $sql = rtrim($sql, ', ');
            $sql .= ' FROM `'._DB_PREFIX_.'lang` CROSS JOIN `'.bqSQL(str_replace('_lang', '', $name)).'` ';
            $sql .= ' WHERE `'.bqSQL($identifier).'` IN (SELECT `'.bqSQL($identifier).'` FROM `'.bqSQL($name).'`) )';

            $result &= Db::getInstance()->execute($sql);
        }

        if ($result) {
            $this->html .= $this->displayConfirmation($this->getTranslator()->trans(
                'Import sample data successfully. Please go to "Advanced Parameters > Performance" menu and click on "Clear cache" button.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));
        } else {
            $this->html .= $this->displayError($this->getTranslator()->trans(
                'A error occurred during import data.',
                array(),
                'Modules.ZoneThememanager.Admin'
            ));
        }
    }

    protected function renderSettingsForm()
    {
        $action = Tools::getValue('action');
        if ($action == 'header') {
            $result = $this->renderHeaderForm();
        } elseif ($action == 'footer') {
            $result = $this->renderFooterForm();
        } elseif ($action == 'category') {
            $result = $this->renderCategoryForm();
        } elseif ($action == 'product') {
            $result = $this->renderProductForm();
        } elseif ($action == 'configure_zone') {
            $result = $this->renderConfigureZOneForm();
        } else {
            $result = $this->renderGeneralForm();
        }

        return $result;
    }

    // General
    protected function renderGeneralForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitGeneralSettings';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getGeneralFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getGeneralForm()));
    }

    protected function getGeneralForm()
    {
        $layout_options = array(
            'query' => array(
                array('id' => 'wide', 'name' => 'Wide'),
                array('id' => 'boxed', 'name' => 'Boxed'),
            ),
            'id' => 'id',
            'name' => 'name',
        );
        $navigation_options = array(
            'query' => array(
                array('id' => 'sidebar', 'name' => 'Sidebar Navigation'),
                array('id' => 'top', 'name' => 'Top Navigation'),
                array('id' => 'sidebar_top', 'name' => 'Sidebar and Top Navigation'),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $boxed_bg_img_style_options = array(
            'query' => array(
                array('id' => 'repeat', 'name' => 'Repeat'),
                array('id' => 'stretch', 'name' => 'Stretch'),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $settings = ZManager::getSettingsByShop();
        $general_settings = $settings->general_settings;
        $bg_image_url = false;
        $bg_image_size = false;
        if ($general_settings['boxed_bg_img']) {
            $bg_image_url = $this->_path.$this->image_folder.$general_settings['boxed_bg_img'];
            $bg_image_size = filesize($this->local_path.$this->image_folder.$general_settings['boxed_bg_img']) / 1000;
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->getTranslator()->trans(
                        'General',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->getTranslator()->trans(
                            'Page Layout',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'layout',
                        'options' => $layout_options,
                        'desc' => 'Set wide or boxed layout to your site.',
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->getTranslator()->trans(
                            'Boxed Background Color',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'boxed_bg_color',
                        'form_group_class' => 'odd',
                        'desc' => $this->getTranslator()->trans(
                            'Set the background color to boxed layout.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->getTranslator()->trans(
                            'Boxed Background Image',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'boxed_bg_img',
                        'display_image' => true,
                        'image' => $bg_image_url ? '<img src="'.$bg_image_url.'" alt="" class="img-thumbnail" style="max-width: 100px;" />' : false,
                        'size' => $bg_image_size,
                        'delete_url' => $this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules').'&deleteBoxedBackgroundImage',
                        'form_group_class' => 'odd',
                        'desc' => $this->getTranslator()->trans(
                            'Set the background image to boxed layout.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->getTranslator()->trans(
                            'Background Image Style',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'boxed_bg_img_style',
                        'options' => $boxed_bg_img_style_options,
                        'form_group_class' => 'odd',
                        'desc' => $this->getTranslator()->trans(
                            'How a background image will be displayed.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->getTranslator()->trans(
                            'SVG Logo',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'svg_logo',
                        'autoload_rte' => false,
                        'lang' => false,
                        'desc' => $this->getTranslator()->trans(
                            'Using SVG code instead of image logo for your site',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Sticky Menu',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'sticky_menu',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'sticky_menu_on', 'label' => $this->getTranslator()->trans(
                                'Yes',
                                array(),
                                'Admin.Global'
                            )),
                            array('value' => false, 'id' => 'sticky_menu_off', 'label' => $this->getTranslator()->trans(
                                'No',
                                array(),
                                'Admin.Global'
                            )),
                        ),
                        'desc' => $this->getTranslator()->trans(
                            'Make the menu "sticky" as soon as it hits the top of the page when you scroll down.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Sticky Menu on Mobile',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'sticky_mobile',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'sticky_mobile_on', 'label' => $this->getTranslator()->trans(
                                'Yes',
                                array(),
                                'Admin.Global'
                            )),
                            array('value' => false, 'id' => 'sticky_mobile_off', 'label' => $this->getTranslator()->trans(
                                'No',
                                array(),
                                'Admin.Global'
                            )),
                        ),
                        'desc' => $this->getTranslator()->trans(
                            'Enable the sticky menu on mobile device.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Scroll to Top Button',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'scroll_top',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'scroll_top_on', 'label' => $this->getTranslator()->trans(
                                'Yes',
                                array(),
                                'Admin.Global'
                            )),
                            array('value' => false, 'id' => 'scroll_top_off', 'label' => $this->getTranslator()->trans(
                                'No',
                                array(),
                                'Admin.Global'
                            )),
                        ),
                        'desc' => $this->getTranslator()->trans(
                            'Allow your visitors to easily scroll back to the top of your page.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Progress Bar',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'progress_bar',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'progress_bar_on', 'label' => $this->getTranslator()->trans(
                                'Yes',
                                array(),
                                'Admin.Global'
                            )),
                            array('value' => false, 'id' => 'progress_bar_off', 'label' => $this->getTranslator()->trans(
                                'No',
                                array(),
                                'Admin.Global'
                            )),
                        ),
                        'desc' => $this->getTranslator()->trans(
                            'Page load progress bar.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Sidebar Mini Cart',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'sidebar_cart',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'sidebar_cart_on', 'label' => $this->getTranslator()->trans(
                                'Yes',
                                array(),
                                'Admin.Global'
                            )),
                            array('value' => false, 'id' => 'sidebar_cart_off', 'label' => $this->getTranslator()->trans(
                                'No',
                                array(),
                                'Admin.Global'
                            )),
                        ),
                        'desc' => $this->getTranslator()->trans(
                            'Enable the Sidebar Mini Cart instead of the Dropdown Cart on the header.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->getTranslator()->trans(
                            'Navigation',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'navigation',
                        'options' => $navigation_options,
                        'desc' => 'Choose the Header Navigation.',
                        'form_group_class' => 'odd',
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans(
                        'Save General Settings',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getGeneralFieldsValues()
    {
        $settings = ZManager::getSettingsByShop();
        $general_settings = $settings->general_settings;

        $fields_value = array(
            'layout' => Tools::getValue('layout', $general_settings['layout']),
            'boxed_bg_color' => Tools::getValue('boxed_bg_color', $general_settings['boxed_bg_color']),
            'boxed_bg_img_style' => Tools::getValue('boxed_bg_img_style', $general_settings['boxed_bg_img_style']),
            'svg_logo' => Tools::getValue('svg_logo', $settings->svg_logo),
            'sticky_menu' => Tools::getValue('sticky_menu', $general_settings['sticky_menu']),
            'sticky_mobile' => Tools::getValue('sticky_mobile', $general_settings['sticky_mobile']),
            'scroll_top' => Tools::getValue('scroll_top', $general_settings['scroll_top']),
            'progress_bar' => Tools::getValue('progress_bar', $general_settings['progress_bar']),
            'sidebar_cart' => Tools::getValue('sidebar_cart', $general_settings['sidebar_cart']),
            'navigation' => Tools::getValue('navigation', $general_settings['navigation']),
        );

        return $fields_value;
    }

    // Header
    protected function renderHeaderForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitHeaderSettings';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getHeaderFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getHeaderForm()));
    }

    protected function getHeaderForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->getTranslator()->trans(
                        'Header',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'color',
                        'label' => $this->getTranslator()->trans(
                            'Header Top Background Color',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'header_top_bg_color',
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->getTranslator()->trans(
                            'Header Top',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'header_top',
                        'autoload_rte' => true,
                        'lang' => true,
                        'desc' => $this->getTranslator()->trans(
                            'Displays a event at the top of page',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->getTranslator()->trans(
                            'Header Links',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'header_phone',
                        'autoload_rte' => true,
                        'lang' => true,
                        'desc' => $this->getTranslator()->trans(
                            'Displays some custom links on Header.',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans(
                        'Save Header Settings',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getHeaderFieldsValues()
    {
        $settings = ZManager::getSettingsByShop();

        $fields_value = array(
            'header_top_bg_color' => Tools::getValue('header_top_bg_color', $settings->header_top_bg_color),
        );

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $default_header_top = isset($settings->header_top[$lang['id_lang']]) ?
            $settings->header_top[$lang['id_lang']] : '';
            $fields_value['header_top'][$lang['id_lang']] = Tools::getValue('header_top_'.(int) $lang['id_lang'], $default_header_top);

            $default_header_phone = isset($settings->header_phone[$lang['id_lang']]) ?
            $settings->header_phone[$lang['id_lang']] : '';
            $fields_value['header_phone'][$lang['id_lang']] = Tools::getValue('header_phone_'.(int) $lang['id_lang'], $default_header_phone);
        }

        return $fields_value;
    }

    // Footer
    protected function renderFooterForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitFooterSettings';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getFooterFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getFooterForm()));
    }

    protected function getFooterForm()
    {
        $footer_cms_links_values = array();
        $cms_pages = CMS::listCms();
        if ($cms_pages) {
            foreach ($cms_pages as $cms) {
                $footer_cms_links_values[] = array(
                    'id' => $cms['id_cms'],
                    'name' => $this->getTranslator()->trans(
                        'CMS Page: ',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ).$cms['meta_title'],
                    'val' => $cms['id_cms'],
                );
            }
        }
        foreach ($this->static_pages as $controller => $title) {
            $footer_cms_links_values[] = array(
                'id' => $controller,
                'name' => $title,
                'val' => $controller,
            );
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->getTranslator()->trans(
                        'Footer',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'textarea',
                        'label' => $this->getTranslator()->trans(
                            'Footer About Us',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'footer_about_us',
                        'autoload_rte' => true,
                        'lang' => true,
                        'desc' => $this->getTranslator()->trans(
                            'About your store and contact information',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'checkbox_array',
                        'label' => $this->getTranslator()->trans(
                            'Footer CMS Links',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'footer_cms_links',
                        'values' => $footer_cms_links_values,
                        'desc' => $this->getTranslator()->trans(
                            'CMS Pages and some useful for your Store',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->getTranslator()->trans(
                            'Footer Static Links',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'footer_static_links',
                        'autoload_rte' => true,
                        'lang' => true,
                        'desc' => $this->getTranslator()->trans(
                            'Use the List format (ul & li HTML tag) for this field',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->getTranslator()->trans(
                            'Footer Bottom',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'footer_bottom',
                        'autoload_rte' => true,
                        'lang' => true,
                        'desc' => $this->getTranslator()->trans(
                            'CopyRight, Payment,...',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'form_group_class' => 'odd',
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans(
                        'Save Footer Settings',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getFooterFieldsValues()
    {
        $settings = ZManager::getSettingsByShop();

        $fields_value = array(
            'footer_cms_links' => Tools::getValue('footer_cms_links', $settings->footer_cms_links),
        );

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $default_footer_about_us = '';
            if (isset($settings->footer_about_us[$lang['id_lang']])) {
                $default_footer_about_us = $settings->footer_about_us[$lang['id_lang']];
            }
            $fields_value['footer_about_us'][$lang['id_lang']] = Tools::getValue('footer_about_us_'.(int) $lang['id_lang'], $default_footer_about_us);

            $default_footer_static_links = '';
            if (isset($settings->footer_static_links[$lang['id_lang']])) {
                $default_footer_static_links = $settings->footer_static_links[$lang['id_lang']];
            }
            $fields_value['footer_static_links'][$lang['id_lang']] = Tools::getValue('footer_static_links_'.(int) $lang['id_lang'], $default_footer_static_links);

            $default_footer_bottom = '';
            if (isset($settings->footer_bottom[$lang['id_lang']])) {
                $default_footer_bottom = $settings->footer_bottom[$lang['id_lang']];
            }
            $fields_value['footer_bottom'][$lang['id_lang']] = Tools::getValue('footer_bottom_'.(int) $lang['id_lang'], $default_footer_bottom);
        }

        return $fields_value;
    }

    // Category
    protected function renderCategoryForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitCategorySettings';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getCategoryFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getCategoryForm()));
    }

    protected function getCategoryForm()
    {
        $product_grid_columns_values = array(
            array('id' => '2', 'value' => '2', 'label' => $this->getTranslator()->trans(
                '2 cols',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => '3', 'value' => '3', 'label' => $this->getTranslator()->trans(
                '3 cols',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => '4', 'value' => '4', 'label' => $this->getTranslator()->trans(
                '4 cols',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => '5', 'value' => '5', 'label' => $this->getTranslator()->trans(
                '5 cols',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
        );

        $default_product_view_values = array(
            array('id' => 'grid', 'value' => 'grid', 'label' => $this->getTranslator()->trans(
                'Grid View',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => 'list', 'value' => 'list', 'label' => $this->getTranslator()->trans(
                'List View',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => 'table', 'value' => 'table', 'label' => $this->getTranslator()->trans(
                'Table View',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->getTranslator()->trans(
                        'Category Page',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Show Category Image',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'show_image',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'show_image_on', 'label' => $this->getTranslator()->trans(
                                'Yes',
                                array(),
                                'Admin.Global'
                            )),
                            array('value' => false, 'id' => 'show_image_off', 'label' => $this->getTranslator()->trans(
                                'No',
                                array(),
                                'Admin.Global'
                            )),
                        ),
                        'desc' => ' ',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Show Category Description',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'show_description',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'show_description_on', 'label' => $this->getTranslator()->trans(
                                'Yes',
                                array(),
                                'Admin.Global'
                            )),
                            array('value' => false, 'id' => 'show_description_off', 'label' => $this->getTranslator()->trans(
                                'No',
                                array(),
                                'Admin.Global'
                            )),
                        ),
                        'desc' => ' ',
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Show Subcategories',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'show_subcategories',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'show_subcategories_on', 'label' => $this->getTranslator()->trans(
                                'Yes',
                                array(),
                                'Admin.Global'
                            )),
                            array('value' => false, 'id' => 'show_subcategories_off', 'label' => $this->getTranslator()->trans(
                                'No',
                                array(),
                                'Admin.Global'
                            )),
                        ),
                        'desc' => ' ',
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->getTranslator()->trans(
                            'Product Grid Columns',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'product_grid_columns',
                        'is_bool' => true,
                        'values' => $product_grid_columns_values,
                        'desc' => 'Number of columns in Product Grid view.',
                        'form_group_class' => 'odd',
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'BUY button in new line',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'buy_in_new_line',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'buy_in_new_line_on', 'label' => $this->getTranslator()->trans(
                                'Yes',
                                array(),
                                'Admin.Global'
                            )),
                            array('value' => false, 'id' => 'buy_in_new_line_off', 'label' => $this->getTranslator()->trans(
                                'No',
                                array(),
                                'Admin.Global'
                            )),
                        ),
                        'desc' => 'In Product Grid view, Show the "BUY" button in a new line.',
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->getTranslator()->trans(
                            'Default Product View',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'default_product_view',
                        'is_bool' => true,
                        'values' => $default_product_view_values,
                        'desc' => 'Default product list view in the category page.',
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans(
                        'Save Category Settings',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getCategoryFieldsValues()
    {
        $settings = ZManager::getSettingsByShop();
        $category_settings = $settings->category_settings;

        $fields_value = array(
            'show_image' => Tools::getValue('show_image', $category_settings['show_image']),
            'show_description' => Tools::getValue('show_description', $category_settings['show_description']),
            'show_subcategories' => Tools::getValue('show_subcategories', $category_settings['show_subcategories']),
            'product_grid_columns' => Tools::getValue('product_grid_columns', $category_settings['product_grid_columns']),
            'buy_in_new_line' => Tools::getValue('buy_in_new_line', $category_settings['buy_in_new_line']),
            'default_product_view' => Tools::getValue('default_product_view', $category_settings['default_product_view']),
        );

        return $fields_value;
    }

    // Product
    protected function renderProductForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitProductSettings';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getProductFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getProductForm()));
    }

    protected function getProductForm()
    {
        $product_info_layout_values = array(
            array('id' => 'normal', 'value' => 'normal', 'label' => $this->getTranslator()->trans(
                'Normal',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => 'tabs', 'value' => 'tabs', 'label' => $this->getTranslator()->trans(
                'Tabs',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
            array('id' => 'accordions', 'value' => 'accordions', 'label' => $this->getTranslator()->trans(
                'Accordion',
                array(),
                'Modules.ZoneThememanager.Admin'
            )),
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->getTranslator()->trans(
                        'Product Page',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Enable Product Image Zoom',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'product_image_zoom',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'product_image_zoom_on', 'label' => $this->getTranslator()->trans(
                                'Yes',
                                array(),
                                'Admin.Global'
                            )),
                            array('value' => false, 'id' => 'product_image_zoom_off', 'label' => $this->getTranslator()->trans(
                                'No',
                                array(),
                                'Admin.Global'
                            )),
                        ),
                        'desc' => 'Show a bigger size product image on mouseover',
                    ),
                    array(
                        'type' => 'radio',
                        'label' => $this->getTranslator()->trans(
                            'Product Info Layout',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => 'product_info_layout',
                        'is_bool' => true,
                        'values' => $product_info_layout_values,
                        'desc' => 'Select a product informations layout',
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans(
                        'Save Product Settings',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getProductFieldsValues()
    {
        $settings = ZManager::getSettingsByShop();
        $product_settings = $settings->product_settings;

        $fields_value = array(
            'product_info_layout' => Tools::getValue('product_info_layout', $product_settings['product_info_layout']),
            'product_image_zoom' => Tools::getValue('product_image_zoom', $product_settings['product_image_zoom']),
        );

        return $fields_value;
    }
    
    // Configure ZOne Module
    protected function renderConfigureZOneForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitConfigureZOne';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigureZOneValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getConfigureZOneForm()));
    }

    protected function getConfigureZOneForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->getTranslator()->trans(
                        'Import Module Settings',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'html',
                        'label' => $this->getTranslator()->trans(
                            'Please note:',
                            array(),
                            'Modules.ZoneThememanager.Admin'
                        ),
                        'name' => '<p style="padding-top: 8px;">This action will remove all the settings of the Z.One modules and add the new settings like the demo page.
                            <br/>Please make sure you have a database backup.</p>',
                    ),
                    array(
                        'type' => 'checkbox',
                        'name' => 'overwrite',
                        'values' => array(
                            'query' => array(
                                array(
                                    'id' => 'zone_settings',
                                    'name' => $this->getTranslator()->trans(
                                        'Overwrite the Z.One module settings',
                                        array(),
                                        'Modules.ZoneThememanager.Admin'
                                    ),
                                    'val' => 1
                                ),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans(
                        'Overwrite',
                        array(),
                        'Modules.ZoneThememanager.Admin'
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getConfigureZOneValues()
    {
        $fields_value = array();

        return $fields_value;
    }

    // Hook process
    public function getBoxedBackgroundCSS($settings = null, $force = false)
    {
        if (!$settings) {
            $settings = ZManager::getSettingsByShop();
        }
        
        $general_settings = $settings->general_settings;
        $boxed_bg_css = false;
        if ($general_settings['layout'] == 'boxed' || $force) {
            $boxed_bg_css = 'body { background-color: '.$general_settings['boxed_bg_color'].';';
            if ($general_settings['boxed_bg_img']) {
                $boxed_bg_css .= 'background-image: url('.$this->_path.$this->image_folder.$general_settings['boxed_bg_img'].');';
                if ($general_settings['boxed_bg_img_style'] == 'repeat') {
                    $boxed_bg_css .= 'background-repeat: repeat;';
                } elseif ($general_settings['boxed_bg_img_style'] == 'stretch') {
                    $boxed_bg_css .= 'background-repeat: no-repeat;background-attachment: fixed;background-position: center;background-size: cover;';
                }
            }
            $boxed_bg_css .= '}';
        }

        return $boxed_bg_css;
    }

    public function hookDisplayHeader()
    {
        $settings = ZManager::getSettingsByShop();
        $general_settings = $settings->general_settings;
        $category_settings = $settings->category_settings;
        $product_settings = $settings->product_settings;

        $this->context->smarty->assign(array(
            'zoneLayout' => $general_settings['layout'],
            'zoneProgressBar' => $general_settings['progress_bar'],
            'zoneStickyMenu' => $general_settings['sticky_menu'],
            'zoneStickyMobile' => $general_settings['sticky_mobile'],
            'zoneSidebarCart' => $general_settings['sidebar_cart'],
            'zoneNavigation' => $general_settings['navigation'],
            'svgLogo' => $settings->svg_logo,
            'productGridColumns' => $category_settings['product_grid_columns'],
            'productBuyNewLine' => $category_settings['buy_in_new_line'],
            'cat_showImage' => $category_settings['show_image'],
            'cat_showDescription' => $category_settings['show_description'],
            'cat_showSubcategories' => $category_settings['show_subcategories'],
            'cat_productView' => $category_settings['default_product_view'],
            'product_infoLayout' => $product_settings['product_info_layout'],
            'product_imageZoom' => $product_settings['product_image_zoom'],
        ));

        if (!$this->isCached('zonethememanager_header.tpl', $this->getCacheId())) {
            $boxed_bg_css = $this->getBoxedBackgroundCSS($settings, false);
            $this->smarty->assign(array(
                'boxed_bg_css' => $boxed_bg_css,
            ));
        }

        return $this->display(__FILE__, 'zonethememanager_header.tpl', $this->getCacheId());
    }

    public function hookDisplayNav1()
    {
        if (!$this->isCached('zonethememanager_nav1.tpl', $this->getCacheId())) {
            $id_lang = (int) $this->context->language->id;
            $settings = ZManager::getSettingsByShop($id_lang);

            $this->smarty->assign(array(
                'header_phone' => $settings->header_phone,
            ));
        }
        return $this->display(__FILE__, 'zonethememanager_nav1.tpl', $this->getCacheId());
    }

    public function hookDisplayBanner()
    {
        $id_lang = (int) $this->context->language->id;
        $settings = ZManager::getSettingsByShop($id_lang);

        if (!$this->isCached('zonethememanager_banner.tpl', $this->getCacheId())) {
            $this->smarty->assign(array(
                'header_top' => $settings->header_top,
                'header_top_bg_color' => $settings->header_top_bg_color,
            ));
        }

        return $this->display(__FILE__, 'zonethememanager_banner.tpl', $this->getCacheId());
    }

    public function hookDisplayFooterLeft()
    {
        if (!$this->isCached('zonethememanager_footerleft.tpl', $this->getCacheId())) {
            $id_lang = (int) $this->context->language->id;
            $settings = ZManager::getSettingsByShop($id_lang);

            $this->smarty->assign(array(
                'aboutUs' => $settings->footer_about_us,
            ));
        }

        return $this->display(__FILE__, 'zonethememanager_footerleft.tpl', $this->getCacheId());
    }

    public function hookDisplayFooterRight()
    {
        if (!$this->isCached('zonethememanager_footerright.tpl', $this->getCacheId())) {
            $id_lang = (int) $this->context->language->id;
            $id_shop = $this->context->shop->id;
            $settings = ZManager::getSettingsByShop($id_lang);
            $cms_links = array();
            $page_links = array();

            $cms_pages = CMS::getCMSPages($id_lang, null, true, $id_shop);
            if ($cms_pages) {
                foreach ($cms_pages as $cms) {
                    if (in_array($cms['id_cms'], $settings->footer_cms_links)) {
                        $cms_links[] = array(
                            'link' => $this->context->link->getCMSLink($cms['id_cms'], $cms['link_rewrite']),
                            'title' => $cms['meta_title'],
                        );
                    }
                }
            }

            foreach ($this->static_pages as $controller => $title) {
                if (in_array($controller, $settings->footer_cms_links)) {
                    $page_links[] = array(
                        'link' => $this->context->link->getPageLink($controller),
                        'id' => $controller,
                    );
                }
            }

            $this->smarty->assign(array(
                'cmsLinks' => $cms_links,
                'pageLinks' => $page_links,
                'staticLinks' => $settings->footer_static_links,
            ));
        }

        return $this->display(__FILE__, 'zonethememanager_footerright.tpl', $this->getCacheId());
    }

    public function hookDisplayFooterAfter()
    {
        if (!$this->isCached('zonethememanager_footerafter.tpl', $this->getCacheId())) {
            $id_lang = (int) $this->context->language->id;
            $settings = ZManager::getSettingsByShop($id_lang);
            $general_settings = $settings->general_settings;

            $this->smarty->assign(array(
                'footerBottom' => $settings->footer_bottom,
                'enableScrollTop' => $general_settings['scroll_top'],
            ));
        }

        return $this->display(__FILE__, 'zonethememanager_footerafter.tpl', $this->getCacheId());
    }

    public function hookDisplaySidebarNavigation()
    {
        if (!$this->isCached('zonethememanager_sidebar_navigation.tpl', $this->getCacheId())) {
            $id_lang = (int) $this->context->language->id;
            $sidebar_menus = false;
            $categories_tree = Category::getRootCategory()->recurseLiteCategTree(5, 0, $id_lang);
            if ($categories_tree['children']) {
                $sidebar_menus = $categories_tree['children'];
            }

            $this->smarty->assign(array(
                'sidebarMenus' => $sidebar_menus,
            ));
        }
        return $this->display(__FILE__, 'zonethememanager_sidebar_navigation.tpl', $this->getCacheId());
    }
}
