<?php
/**
* 2007-2017 PrestaShop
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

use PrestaShop\PrestaShop\Core\Module\WidgetInterface;
use PrestaShop\PrestaShop\Adapter\ObjectPresenter;

class ZOneFeaturedCategories extends Module implements WidgetInterface
{
    public function __construct()
    {
        $this->name = 'zonefeaturedcategories';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Mr.ZOne';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->getTranslator()->trans(
            'Z.One - Featured Categories',
            array(),
            'Modules.ZoneFeaturedcategories.Admin'
        );
        $this->description = $this->getTranslator()->trans(
            'Displays featured Categories on the Homepage.',
            array(),
            'Modules.ZoneFeaturedcategories.Admin'
        );
    }

    public function install()
    {
        $settings = array(
            'enableSlider' => true,
            'categoriesPerRow' => 3,
            'imageName' => 'category_home',
            'featuredCategories' => array(),
            'layoutStyle' => 'medium',
            'subCategoriesLimit' => 10,
        );

        Configuration::updateValue('ZONEFEATUREDCATEGORIES_SETTINGS', serialize($settings));

        return parent::install()
            && $this->registerHook('categoryAddition')
            && $this->registerHook('categoryUpdate')
            && $this->registerHook('categoryDeletion')
            && $this->registerHook('displayBottomColumn')
        ;
    }

    public function uninstall()
    {
        Configuration::deleteByName('ZONEFEATUREDCATEGORIES_SETTINGS');

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
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }

    public function getContent()
    {
        $this->backOfficeHeader();

        $about = $this->about();

        if (Tools::isSubmit('submitSettings')) {
            $this->processSaveSettings();

            return $this->displayConfirmation($this->getTranslator()->trans(
                'Settings updated',
                array(),
                'Modules.ZoneFeaturedcategories.Admin'
            )).$this->renderSettingsForm().$about;
        } else {
            return $this->renderSettingsForm().$about;
        }
    }

    protected function processSaveSettings()
    {
        $settings = array(
            'enableSlider' => Tools::getValue('enableSlider'),
            'categoriesPerRow' => (int)Tools::getValue('categoriesPerRow'),
            'imageName' => Tools::getValue('imageName', ImageType::getFormattedName('category')),
            'featuredCategories' => Tools::getValue('featuredCategories', array()),
            'layoutStyle' => Tools::getValue('layoutStyle'),
            'subCategoriesLimit' => (int)Tools::getValue('subCategoriesLimit'),
        );

        Configuration::updateValue('ZONEFEATUREDCATEGORIES_SETTINGS', serialize($settings));

        $this->_clearCache('*');
    }

    protected function renderSettingsForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'submitSettings';
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getSettingsFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        $form = $helper->generateForm(array($this->getSettingsForm()));

        Context::getContext()->smarty->assign('token', Tools::getAdminTokenLite('AdminModules'));
        
        return $form;
    }

    protected function getSettingsForm()
    {
        $categories_per_row_options = array(
            'query' => array(
                array('id' => 2, 'name' => 2),
                array('id' => 3, 'name' => 3),
                array('id' => 4, 'name' => 4),
            ),
            'id' => 'id',
            'name' => 'name'
        );
        $layout_style_options = array(
            'query' => array(
                array('id' => 'small', 'name' => $this->getTranslator()->trans(
                    'Category and Thumbnail',
                    array(),
                    'Modules.ZoneFeaturedcategories.Admin'
                )),
                array('id' => 'medium', 'name' => $this->getTranslator()->trans(
                    'Category, Thumbnail and Subcategories',
                    array(),
                    'Modules.ZoneFeaturedcategories.Admin'
                )),
            ),
            'id' => 'id',
            'name' => 'name'
        );

        $selected_categories = array();
        $settings = Tools::unSerialize(Configuration::get('ZONEFEATUREDCATEGORIES_SETTINGS'));
        if (isset($settings['featuredCategories'])) {
            $selected_categories = $settings['featuredCategories'];
        }

        $cats_images_types = ImageType::getImagesTypes('categories');
        if ($cats_images_types) {
            foreach ($cats_images_types as &$cit) {
                $cit['label'] = $cit['name'].' ('.$cit['width'].'x'.$cit['height'].'px)';
            }
        } else {
            $cats_images_types = array(array('name' => '', 'label' => $this->getTranslator()->trans(
                'Not found!',
                array(),
                'Modules.ZoneFeaturedcategories.Admin'
            )));
        }

        $image_name_options = array(
            'query' => $cats_images_types,
            'id' => 'name',
            'name' => 'label'
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->getTranslator()->trans(
                        'Settings',
                        array(),
                        'Modules.ZoneFeaturedcategories.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Enable Slider',
                            array(),
                            'Modules.ZoneFeaturedcategories.Admin'
                        ),
                        'name' => 'enableSlider',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'enableSlider_on',
                                'value' => true,
                                'label' => $this->getTranslator()->trans(
                                    'Enabled',
                                    array(),
                                    'Admin.Global'
                                )
                            ),
                            array(
                                'id' => 'enableSlider_off',
                                'value' => false,
                                'label' => $this->getTranslator()->trans(
                                    'Disabled',
                                    array(),
                                    'Admin.Global'
                                )
                            )
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->getTranslator()->trans(
                            'Categories per Row',
                            array(),
                            'Modules.ZoneFeaturedcategories.Admin'
                        ),
                        'name' => 'categoriesPerRow',
                        'options' => $categories_per_row_options,
                        'hint' => $this->getTranslator()->trans(
                            'The number of category block per row.',
                            array(),
                            'Modules.ZoneFeaturedcategories.Admin'
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->getTranslator()->trans(
                            'Category Image Type',
                            array(),
                            'Modules.ZoneFeaturedcategories.Admin'
                        ),
                        'name' => 'imageName',
                        'options' => $image_name_options,
                    ),
                    array(
                        'type' => 'categories',
                        'label' => $this->getTranslator()->trans(
                            'Category Selection',
                            array(),
                            'Modules.ZoneFeaturedcategories.Admin'
                        ),
                        'name' => 'featuredCategories',
                        'tree' => array(
                            'use_search' => false,
                            'id' => 'featuredCategories',
                            'use_checkbox' => true,
                            'selected_categories' => $selected_categories,
                        ),
                        'col' => 6,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->getTranslator()->trans(
                            'Layout Style',
                            array(),
                            'Modules.ZoneFeaturedcategories.Admin'
                        ),
                        'name' => 'layoutStyle',
                        'options' => $layout_style_options,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans(
                            'Number of Subcategories',
                            array(),
                            'Modules.ZoneFeaturedcategories.Admin'
                        ),
                        'name' => 'subCategoriesLimit',
                        'hint' => $this->getTranslator()->trans(
                            'Limit number of subcategories of a category. If set to zero, no limit is imposed.',
                            array(),
                            'Modules.ZoneFeaturedcategories.Admin'
                        ),
                        'col' => 1,
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans(
                        'Save',
                        array(),
                        'Admin.Actions'
                    ),
                )
            ),
        );

        return $fields_form;
    }

    protected function getSettingsFieldsValues()
    {
        $settings = Tools::unSerialize(Configuration::get('ZONEFEATUREDCATEGORIES_SETTINGS'));

        $fields_value = array(
            'enableSlider' => Tools::getValue('enableSlider', $settings['enableSlider']),
            'categoriesPerRow' => Tools::getValue('categoriesPerRow', $settings['categoriesPerRow']),
            'imageName' => Tools::getValue('imageName', $settings['imageName']),
            'layoutStyle' => Tools::getValue('layoutStyle', $settings['layoutStyle']),
            'subCategoriesLimit' => Tools::getValue('subCategoriesLimit', $settings['subCategoriesLimit']),
        );

        return $fields_value;
    }

    public function hookCategoryAddition()
    {
        $this->_clearCache('*');
    }

    public function hookCategoryUpdate()
    {
        $this->_clearCache('*');
    }

    public function hookCategoryDeletion()
    {
        $this->_clearCache('*');
    }

    protected function getCacheId($hookName = null)
    {
        return parent::getCacheId().'|'.$hookName;
    }

    public function getWidgetVariables($hookName = null, array $configuration = array())
    {
        $id_lang = (int)$this->context->language->id;
        $settings = Tools::unSerialize(Configuration::get('ZONEFEATUREDCATEGORIES_SETTINGS'));
        $featured_categories = false;

        if (!empty($settings['featuredCategories']) && is_array($settings['featuredCategories'])) {
            $objectPresenter = new ObjectPresenter();
            $featured_categories = array();

            foreach ($settings['featuredCategories'] as $id_category) {
                $object_category = new Category($id_category, $id_lang);

                if (Validate::isLoadedObject($object_category)) {
                    $category = $objectPresenter->present($object_category);

                    $category['image'] = false;
                    if ($object_category->id_image) {
                        $category['image'] = $this->context->link->getCatImageLink(
                            $object_category->link_rewrite,
                            $object_category->id_image,
                            $settings['imageName']
                        );
                    }

                    $category['url'] = $this->context->link->getCategoryLink(
                        $object_category->id_category,
                        $object_category->link_rewrite
                    );

                    $subCategories = $object_category->getSubCategories($id_lang);
                    if ((int)$settings['subCategoriesLimit']) {
                        $subCategories = array_slice($subCategories, 0, (int)$settings['subCategoriesLimit']);
                    }

                    if ($subCategories) {
                        foreach ($subCategories as &$sub) {
                            $sub['url'] = $this->context->link->getCategoryLink(
                                $sub['id_category'],
                                $sub['link_rewrite']
                            );
                        }
                    }

                    $featured_categories[] = array(
                        'category' => $category,
                        'subCategories' => $subCategories,
                    );
                }
            }
        }

        $image_size = ImageType::getByNameNType($settings['imageName'], 'categories');

        $this->smarty->assign(array(
            'enableSlider' => $settings['enableSlider'],
            'categoriesPerRow' => $settings['categoriesPerRow'],
            'layoutStyle' => $settings['layoutStyle'],
            'featuredCategories' => $featured_categories,
            'hookName' => $hookName,
            'imageSize' => $image_size,
        ));
    }

    public function renderWidget($hookName = null, array $configuration = array())
    {
        if (!$this->isCached('zonefeaturedcategories.tpl', $this->getCacheId())) {
            $this->getWidgetVariables($hookName, $configuration);
        }

        return $this->fetch('module:zonefeaturedcategories/views/templates/hook/zonefeaturedcategories.tpl', $this->getCacheId($hookName));
    }
}
