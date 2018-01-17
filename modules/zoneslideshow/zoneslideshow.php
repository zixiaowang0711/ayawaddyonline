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

use PrestaShop\PrestaShop\Core\Product\ProductListingPresenter;
use PrestaShop\PrestaShop\Adapter\Image\ImageRetriever;
use PrestaShop\PrestaShop\Adapter\Product\PriceFormatter;
use PrestaShop\PrestaShop\Adapter\Product\ProductColorsRetriever;

include_once dirname(__FILE__).'/classes/ZSlideshow.php';

class ZOneSlideshow extends Module
{
    protected $slide_img_folder = 'views/img/slideImages/';
    protected $slide_thumb_folder = 'views/img/slideImages/thumbs/';
    protected $thumb_width = 120;
    protected $thumb_height = 50;
    protected $html = '';
    protected $currentIndex;
    protected $nivo_effects = array(
        'query' => array(
            array('id' => 'random', 'name' => 'random'),
            array('id' => 'sliceDown', 'name' => 'sliceDown', 'val' => 1),
            array('id' => 'sliceDownLeft', 'name' => 'sliceDownLeft', 'val' => 1),
            array('id' => 'sliceUp', 'name' => 'sliceUp', 'val' => 1),
            array('id' => 'sliceUpLeft', 'name' => 'sliceUpLeft', 'val' => 1),
            array('id' => 'sliceUpDown', 'name' => 'sliceUpDown', 'val' => 1),
            array('id' => 'sliceUpDownLeft', 'name' => 'sliceUpDownLeft', 'val' => 1),
            array('id' => 'fold', 'name' => 'fold', 'val' => 1),
            array('id' => 'fade', 'name' => 'fade', 'val' => 1),
            array('id' => 'slideInRight', 'name' => 'slideInRight', 'val' => 1),
            array('id' => 'slideInLeft', 'name' => 'slideInLeft', 'val' => 1),
            array('id' => 'boxRandom', 'name' => 'boxRandom', 'val' => 1),
            array('id' => 'boxRain', 'name' => 'boxRain', 'val' => 1),
            array('id' => 'boxRainReverse', 'name' => 'boxRainReverse', 'val' => 1),
            array('id' => 'boxRainGrow', 'name' => 'boxRainGrow', 'val' => 1),
            array('id' => 'boxRainGrowReverse', 'name' => 'boxRainGrowReverse', 'val' => 1),
        ),
        'id' => 'id',
        'name' => 'name',
    );

    public function __construct()
    {
        $this->name = 'zoneslideshow';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Mr.ZOne';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->getTranslator()->trans(
            'Z.One - Nivo Slideshow',
            array(),
            'Modules.ZoneSlideshow.Admin'
        );
        $this->description = $this->getTranslator()->trans(
            'Add a jQuery Nivo slideshow on the homepage.',
            array(),
            'Modules.ZoneSlideshow.Admin'
        );

        $this->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
    }

    public function install()
    {
        $settings = array(
            'layout' => 'wide',
            'caption_effect' => 'opacity',
            'slices' => 15,
            'boxCols' => 8,
            'boxRows' => 4,
            'animSpeed' => 500,
            'pauseTime' => 5000,
            'startSlide' => 0,
            'directionNav' => true,
            'controlNav' => true,
            'controlNavThumbs' => false,
            'pauseOnHover' => true,
            'manualAdvance' => false,
            'randomStart' => false,
        );

        foreach ($this->nivo_effects['query'] as $effect) {
            $effect_id = 'effect_'.$effect['id'];
            $settings[$effect_id] = 0;
        }

        Configuration::updateValue('ZONESLIDESHOW_SETTINGS', serialize($settings));

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
            && $this->registerHook('actionFrontControllerSetMedia')
            && $this->registerHook('displayTopColumn')
        ;
    }

    public function uninstall()
    {
        Configuration::deleteByName('ZONESLIDESHOW_SETTINGS');

        $sql = 'DROP TABLE IF EXISTS
            `'._DB_PREFIX_.'zslideshow`,
            `'._DB_PREFIX_.'zslideshow_lang`';

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
        $this->context->controller->addJqueryPlugin('tablednd');
        $this->context->controller->addJS($this->_path.'views/js/position.js');
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');
    }

    public function getContent()
    {
        $this->backOfficeHeader();

        $about = $this->about();

        if (Tools::isSubmit('submitSettings')) {
            $this->processSaveSettings();

            return $this->html.$this->renderSlideshowList().$this->renderSettingsForm().$about;
        } elseif (Tools::isSubmit('savezoneslideshow')) {
            if ($this->processSaveSlideshow()) {
                return $this->html.$this->renderSlideshowList().$this->renderSettingsForm().$about;
            } else {
                return $this->html.$this->renderSlideshowForm();
            }
        } elseif (Tools::isSubmit('addzoneslideshow') || Tools::isSubmit('updatezoneslideshow')) {
            return $this->renderSlideshowForm();
        } elseif (Tools::isSubmit('deletezoneslideshow')) {
            $zslideshow = new ZSlideshow((int) Tools::getValue('id_zslideshow'));
            $zslideshow->delete();
            $this->_clearCache('*');
            Tools::redirectAdmin($this->currentIndex.'&token='.Tools::getAdminTokenLite('AdminModules'));
        } elseif (Tools::isSubmit('statuszoneslideshow')) {
            $this->ajaxStatus();
        } elseif (Tools::getValue('updatePositions') == 'zoneslideshow') {
            $this->ajaxPositions();
        } elseif (Tools::isSubmit('ajaxProductsList')) {
            $this->ajaxProductsList();
        } else {
            return $this->renderSlideshowList().$this->renderSettingsForm().$about;
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

    protected function ajaxPositions()
    {
        $positions = Tools::getValue('zslideshow');

        if (empty($positions)) {
            return;
        }

        foreach ($positions as $position => $value) {
            $pos = explode('_', $value);

            if (isset($pos[2])) {
                ZSlideshow::updatePosition($pos[2], $position + 1);
            }
        }

        $this->_clearCache('*');
    }

    protected function ajaxStatus()
    {
        $id_zslideshow = (int)Tools::getValue('id_zslideshow');
        if (!$id_zslideshow) {
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
            $zslideshow = new ZSlideshow($id_zslideshow);
            $zslideshow->active = !(int)$zslideshow->active;
            if ($zslideshow->save()) {
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

    protected function processSaveSettings()
    {
        $settings = array();
        $settings['layout'] = Tools::getValue('layout');
        $settings['caption_effect'] = Tools::getValue('caption_effect');
        $settings['slices'] = Tools::getValue('slices');
        $settings['boxCols'] = Tools::getValue('boxCols');
        $settings['boxRows'] = Tools::getValue('boxRows');
        $settings['animSpeed'] = Tools::getValue('animSpeed');
        $settings['pauseTime'] = Tools::getValue('pauseTime');
        $settings['startSlide'] = Tools::getValue('startSlide');
        $settings['directionNav'] = Tools::getValue('directionNav');
        $settings['controlNav'] = Tools::getValue('controlNav');
        $settings['controlNavThumbs'] = Tools::getValue('controlNavThumbs');
        $settings['pauseOnHover'] = Tools::getValue('pauseOnHover');
        $settings['manualAdvance'] = Tools::getValue('manualAdvance');
        $settings['randomStart'] = Tools::getValue('randomStart');

        foreach ($this->nivo_effects['query'] as $effect) {
            $effect_id = 'effect_'.$effect['id'];
            $settings[$effect_id] = Tools::getValue($effect_id);
        }

        Configuration::updateValue('ZONESLIDESHOW_SETTINGS', serialize($settings));

        $this->_clearCache('*');

        $this->html .= $this->displayConfirmation($this->getTranslator()->trans(
            'Settings updated',
            array(),
            'Modules.ZoneSlideshow.Admin'
        ));
    }

    protected function processSaveSlideshow()
    {
        $zslideshow = new ZSlideshow();
        $id_zslideshow = (int) Tools::getValue('id_zslideshow');
        if ($id_zslideshow) {
            $zslideshow = new ZSlideshow($id_zslideshow);
        }

        $zslideshow->position = (int) Tools::getValue('position');
        $zslideshow->active = (int) Tools::getValue('active');
        $zslideshow->transition = Tools::getValue('transition');

        $zslideshow->related_products = null;
        if (Tools::getValue('related_products')) {
            $zslideshow->related_products = Tools::getValue('related_products');
        }

        $languages = Language::getLanguages(false);
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $title = array();
        $link = array();
        $caption = array();
        foreach ($languages as $lang) {
            $title[$lang['id_lang']] = Tools::getValue('title_'.$lang['id_lang']);
            if (!$title[$lang['id_lang']]) {
                $title[$lang['id_lang']] = Tools::getValue('title_'.$id_lang_default);
            }
            $link[$lang['id_lang']] = Tools::getValue('link_'.$lang['id_lang']);
            if (!$link[$lang['id_lang']]) {
                $link[$lang['id_lang']] = Tools::getValue('link_'.$id_lang_default);
            }
            $caption[$lang['id_lang']] = Tools::getValue('caption_'.$lang['id_lang']);
            if (!$caption[$lang['id_lang']]) {
                $caption[$lang['id_lang']] = Tools::getValue('caption_'.$id_lang_default);
            }
        }
        $zslideshow->title = $title;
        $zslideshow->link = $link;
        $zslideshow->caption = $caption;

        if (isset($_FILES['image']) && isset($_FILES['image']['tmp_name']) && !empty($_FILES['image']['tmp_name'])) {
            if ($error = ImageManager::validateUpload($_FILES['image'], Tools::convertBytes(ini_get('upload_max_filesize')))) {
                $this->html .= $this->displayError($error);
            } else {
                if (move_uploaded_file($_FILES['image']['tmp_name'], $this->local_path.$this->slide_img_folder.$_FILES['image']['name'])) {
                    $zslideshow->image = $_FILES['image']['name'];

                    ImageManager::resize(
                        $this->local_path.$this->slide_img_folder.$zslideshow->image,
                        $this->local_path.$this->slide_thumb_folder.$zslideshow->image,
                        (int) $this->thumb_width,
                        (int) $this->thumb_height
                    );
                } else {
                    $this->html .= $this->displayError($this->getTranslator()->trans(
                        'File upload error.',
                        array(),
                        'Modules.ZoneSlideshow.Admin'
                    ));
                }
            }
        }

        $result = $zslideshow->validateFields(false) && $zslideshow->validateFieldsLang(false);

        if ($result) {
            $zslideshow->save();

            if ($id_zslideshow) {
                $this->html .= $this->displayConfirmation($this->getTranslator()->trans(
                    'Slide has been updated.',
                    array(),
                    'Modules.ZoneSlideshow.Admin'
                ));
            } else {
                $this->html .= $this->displayConfirmation($this->getTranslator()->trans(
                    'Slide has been created successfully.',
                    array(),
                    'Modules.ZoneSlideshow.Admin'
                ));
            }

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->getTranslator()->trans(
                'An error occurred while attempting to save Slide.',
                array(),
                'Modules.ZoneSlideshow.Admin'
            ));
        }

        return $result;
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
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getSettingsFieldsValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getSettingsForm()));
    }

    protected function getSettingsForm()
    {
        $layout_values = array(
            array(
                'id' => 'layout_wide',
                'value' => 'wide',
                'label' => $this->getTranslator()->trans(
                    'Wide',
                    array(),
                    'Modules.ZoneSlideshow.Admin'
                )
            ),
            array(
                'id' => 'layout_boxed',
                'value' => 'boxed',
                'label' => $this->getTranslator()->trans(
                    'Boxed',
                    array(),
                    'Modules.ZoneSlideshow.Admin'
                )
            ),
        );

        $caption_effect_options = array(
            'query' => array(
                array('id' => 'opacity', 'name' => 'Fade'),
                array('id' => 'top', 'name' => 'Slide form Top'),
                array('id' => 'bottom', 'name' => 'Slide form Bottom'),
                array('id' => 'left', 'name' => 'Slide form Left'),
                array('id' => 'right', 'name' => 'Slide form Right'),
            ),
            'id' => 'id',
            'name' => 'name',
        );

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->getTranslator()->trans(
                        'Nivo Slider Options',
                        array(),
                        'Modules.ZoneSlideshow.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'radio',
                        'label' => $this->getTranslator()->trans(
                            'Slideshow Layout',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'layout',
                        'values' => $layout_values,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->getTranslator()->trans(
                            'Caption Effect',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'caption_effect',
                        'options' => $caption_effect_options,
                    ),
                    array(
                        'type' => 'checkbox',
                        'label' => $this->getTranslator()->trans(
                            'Slide Effect',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'effect',
                        'values' => $this->nivo_effects,
                        'col' => 7,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans(
                            'Slices',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'slices',
                        'col' => 1,
                        'hint' => $this->getTranslator()->trans(
                            'For slice animations',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans(
                            'Box Columns',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'boxCols',
                        'col' => 1,
                        'hint' => $this->getTranslator()->trans(
                            'For box animations',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans(
                            'Box Rows',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'boxRows',
                        'col' => 1,
                        'hint' => $this->getTranslator()->trans(
                            'For box animations',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans(
                            'Animation Speed',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'animSpeed',
                        'col' => 1,
                        'hint' => $this->getTranslator()->trans(
                            'Slide transition speed',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans(
                            'Pause Time',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'pauseTime',
                        'col' => 1,
                        'hint' => $this->getTranslator()->trans(
                            'How long each slide will show',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans(
                            'Start Slide',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'startSlide',
                        'col' => 1,
                        'hint' => $this->getTranslator()->trans(
                            'Set starting Slide (0 index)',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Direction Navigation',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'directionNav',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'active_on', 'label' => $this->getTranslator()->trans(
                                'True',
                                array(),
                                'Modules.ZoneSlideshow.Admin'
                            )),
                            array('value' => false, 'id' => 'active_off', 'label' => $this->getTranslator()->trans(
                                'False',
                                array(),
                                'Modules.ZoneSlideshow.Admin'
                            )),
                        ),
                        'hint' => $this->getTranslator()->trans(
                            'Next & Prev navigation',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Control Navigation',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'controlNav',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'active_on', 'label' => $this->getTranslator()->trans(
                                'True',
                                array(),
                                'Modules.ZoneSlideshow.Admin'
                            )),
                            array('value' => false, 'id' => 'active_off', 'label' => $this->getTranslator()->trans(
                                'False',
                                array(),
                                'Modules.ZoneSlideshow.Admin'
                            )),
                        ),
                        'hint' => $this->getTranslator()->trans(
                            '1,2,3... navigation',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Control Navigation Thumbs',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'controlNavThumbs',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'active_on', 'label' => $this->getTranslator()->trans(
                                'True',
                                array(),
                                'Modules.ZoneSlideshow.Admin'
                            )),
                            array('value' => false, 'id' => 'active_off', 'label' => $this->getTranslator()->trans(
                                'False',
                                array(),
                                'Modules.ZoneSlideshow.Admin'
                            )),
                        ),
                        'hint' => $this->getTranslator()->trans(
                            'Use thumbnails for Control Nav',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Pause on Hover',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'pauseOnHover',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'active_on', 'label' => $this->getTranslator()->trans(
                                'True',
                                array(),
                                'Modules.ZoneSlideshow.Admin'
                            )),
                            array('value' => false, 'id' => 'active_off', 'label' => $this->getTranslator()->trans(
                                'False',
                                array(),
                                'Modules.ZoneSlideshow.Admin'
                            )),
                        ),
                        'hint' => $this->getTranslator()->trans(
                            'Stop animation while hovering',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Manual Advance',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'manualAdvance',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'active_on', 'label' => $this->getTranslator()->trans(
                                'True',
                                array(),
                                'Modules.ZoneSlideshow.Admin'
                            )),
                            array('value' => false, 'id' => 'active_off', 'label' => $this->getTranslator()->trans(
                                'False',
                                array(),
                                'Modules.ZoneSlideshow.Admin'
                            )),
                        ),
                        'hint' => $this->getTranslator()->trans(
                            'Force manual transitions',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'switch',
                        'label' => $this->getTranslator()->trans(
                            'Random Start',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'randomStart',
                        'is_bool' => true,
                        'values' => array(
                            array('value' => true, 'id' => 'active_on', 'label' => $this->getTranslator()->trans(
                                'True',
                                array(),
                                'Modules.ZoneSlideshow.Admin'
                            )),
                            array('value' => false, 'id' => 'active_off', 'label' => $this->getTranslator()->trans(
                                'False',
                                array(),
                                'Modules.ZoneSlideshow.Admin'
                            )),
                        ),
                        'hint' => $this->getTranslator()->trans(
                            'Start on a random slide',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->getTranslator()->trans(
                        'Save',
                        array(),
                        'Admin.Actions'
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getSettingsFieldsValues()
    {
        $settings = Tools::unSerialize(Configuration::get('ZONESLIDESHOW_SETTINGS'));

        $fields_value = array(
            'layout' => Tools::getValue('layout', $settings['layout']),
            'caption_effect' => Tools::getValue('caption_effect', $settings['caption_effect']),
            'slices' => Tools::getValue('slices', $settings['slices']),
            'boxCols' => Tools::getValue('boxCols', $settings['boxCols']),
            'boxRows' => Tools::getValue('boxRows', $settings['boxRows']),
            'animSpeed' => Tools::getValue('animSpeed', $settings['animSpeed']),
            'pauseTime' => Tools::getValue('pauseTime', $settings['pauseTime']),
            'startSlide' => Tools::getValue('startSlide', $settings['startSlide']),
            'directionNav' => Tools::getValue('directionNav', $settings['directionNav']),
            'controlNav' => Tools::getValue('controlNav', $settings['controlNav']),
            'controlNavThumbs' => Tools::getValue('controlNavThumbs', $settings['controlNavThumbs']),
            'pauseOnHover' => Tools::getValue('pauseOnHover', $settings['pauseOnHover']),
            'manualAdvance' => Tools::getValue('manualAdvance', $settings['manualAdvance']),
            'randomStart' => Tools::getValue('randomStart', $settings['randomStart']),
        );

        foreach ($this->nivo_effects['query'] as $effect) {
            $effect_id = 'effect_'.$effect['id'];
            $fields_value[$effect_id] = Tools::getValue($effect_id, $settings[$effect_id]);
        }

        return $fields_value;
    }

    protected function renderSlideshowList()
    {
        $slides = ZSlideshow::getList((int) $this->context->language->id, false);

        $helper = new HelperList();
        $helper->shopLinkType = '';
        $helper->toolbar_btn['new'] = array(
            'href' => $this->currentIndex.'&addzoneslideshow&token='.Tools::getAdminTokenLite('AdminModules'),
            'desc' => $this->getTranslator()->trans(
                'Add New',
                array(),
                'Admin.Actions'
            ),
        );
        $helper->simple_header = false;
        $helper->listTotal = count($slides);
        $helper->identifier = 'id_zslideshow';
        $helper->table = 'zoneslideshow';
        $helper->actions = array('edit', 'delete');
        $helper->show_toolbar = true;
        $helper->no_link = true;
        $helper->module = $this;
        $helper->title = $this->getTranslator()->trans(
            'Slides List',
            array(),
            'Modules.ZoneSlideshow.Admin'
        );
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->currentIndex;
        $helper->position_identifier = 'zslideshow';
        $helper->position_group_identifier = 0;

        $helper->tpl_vars = array(
            'image_baseurl' => $this->_path.$this->slide_thumb_folder,
        );

        return $helper->generateList($slides, $this->getSlideshowList());
    }

    protected function getSlideshowList()
    {
        $fields_list = array(
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
            'image' => array(
                'title' => $this->getTranslator()->trans(
                    'Image',
                    array(),
                    'Modules.ZoneSlideshow.Admin'
                ),
                'align' => 'center',
                'orderby' => false,
                'search' => false,
                'type' => 'zimage',
            ),
            'details' => array(
                'title' => $this->getTranslator()->trans(
                    'Details',
                    array(),
                    'Modules.ZoneSlideshow.Admin'
                ),
                'orderby' => false,
                'search' => false,
                'type' => 'zdetails',
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

    protected function renderSlideshowForm()
    {
        $lang = new Language((int)Configuration::get('PS_LANG_DEFAULT'));

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->module = $this;
        $helper->default_form_language = $lang->id;
        $helper->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'savezoneslideshow';
        $helper->currentIndex = $this->currentIndex;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => $this->getSlideshowFormValues(),
            'languages' => $this->context->controller->getLanguages(),
            'id_language' => $this->context->language->id,
        );

        return $helper->generateForm(array($this->getSlideshowForm()));
    }

    protected function getSlideshowForm()
    {
        $id_zslideshow = (int) Tools::getValue('id_zslideshow');

        $legent_title = $this->getTranslator()->trans(
            'Add New Slide',
            array(),
            'Modules.ZoneSlideshow.Admin'
        );
        if ($id_zslideshow) {
            $legent_title = $this->getTranslator()->trans(
                'Edit Slide',
                array(),
                'Modules.ZoneSlideshow.Admin'
            );
        }

        $image_url = false;
        $image_size = false;
        if ($id_zslideshow) {
            $zslideshow = new ZSlideshow($id_zslideshow);
            if ($zslideshow->image) {
                $image_url = $this->_path.$this->slide_img_folder.$zslideshow->image;
                $image_size = filesize($this->local_path.$this->slide_img_folder.$zslideshow->image) / 1000;
            }
        }

        $transition_options = $this->nivo_effects;
        array_shift($transition_options['query']);
        array_unshift($transition_options['query'], array('id' => '0', 'name' => 'Default'));

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $legent_title,
                    'icon' => 'icon-book',
                ),
                'input' => array(
                    array(
                        'type' => 'hidden',
                        'name' => 'id_zslideshow',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans(
                            'Title',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'title',
                        'lang' => true,
                        'required' => true,
                        'col' => 5,
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans(
                            'Link',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'link',
                        'lang' => true,
                        'col' => 5,
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->getTranslator()->trans(
                            'Image',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'image',
                        'desc' => $this->getTranslator()->trans(
                            'Upload a image from your computer.',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'required' => true,
                        'display_image' => true,
                        'image' => $image_url ? '<img src="'.$image_url.'" alt="" class="img-thumbnail" style="max-width:410px;" />' : false,
                        'size' => $image_size,
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->getTranslator()->trans(
                            'Caption',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'caption',
                        'autoload_rte' => true,
                        'lang' => true,
                        'rows' => 10,
                        'cols' => 100,
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->getTranslator()->trans(
                            'Transition',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'name' => 'transition',
                        'options' => $transition_options,
                        'desc' => $this->getTranslator()->trans(
                            'Specify image effect.',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
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
                        'type' => 'product_autocomplete',
                        'label' => $this->getTranslator()->trans(
                            'Related Products',
                            array(),
                            'Admin.Global'
                        ),
                        'name' => 'related_products',
                        'ajax_path' => AdminController::$currentIndex.'&configure='.$this->name.'&ajax=1&ajaxProductsList&token='.Tools::getAdminTokenLite('AdminModules'),
                        'desc' => $this->getTranslator()->trans(
                            'You can add up to 2 related products for each slide.',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'position',
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
                            'Back to Slides List',
                            array(),
                            'Modules.ZoneSlideshow.Admin'
                        ),
                        'icon' => 'process-icon-back',
                    ),
                ),
            ),
        );

        return $fields_form;
    }

    protected function getSlideshowFormValues()
    {
        $fields_value = array();

        $id_zslideshow = (int) Tools::getValue('id_zslideshow');
        $zslideshow = new ZSlideshow($id_zslideshow);

        $fields_value['id_zslideshow'] = $id_zslideshow;
        $fields_value['transition'] = Tools::getValue('transition', $zslideshow->transition);
        $fields_value['active'] = Tools::getValue('active', $zslideshow->active);
        $fields_value['position'] = Tools::getValue('position', $zslideshow->position);
        $fields_value['image'] = Tools::getValue('image', $zslideshow->image);
        $fields_value['related_products'] = $zslideshow->getProductsAutocompleteInfo($this->context->language->id);

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $default_title = isset($zslideshow->title[$lang['id_lang']]) ? $zslideshow->title[$lang['id_lang']] : '';
            $fields_value['title'][$lang['id_lang']] = Tools::getValue('title_'.(int) $lang['id_lang'], $default_title);
            $default_link = isset($zslideshow->link[$lang['id_lang']]) ? $zslideshow->link[$lang['id_lang']] : '';
            $fields_value['link'][$lang['id_lang']] = Tools::getValue('link_'.(int) $lang['id_lang'], $default_link);
            $default_caption = isset($zslideshow->caption[$lang['id_lang']]) ? $zslideshow->caption[$lang['id_lang']] : '';
            $fields_value['caption'][$lang['id_lang']] = Tools::getValue('caption_'.(int) $lang['id_lang'], $default_caption);
        }

        return $fields_value;
    }

    public function hookActionObjectProductUpdateAfter()
    {
        $this->_clearCache('*');
    }

    public function hookActionFrontControllerSetMedia()
    {
        $this->context->controller->addjqueryPlugin('easing');
    }

    protected function getRelatedProducts($array_product_id, $id_lang)
    {
        $products = ZSlideshow::getProductsByArrayId($array_product_id, $id_lang);
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
        $one_slide = false;

        $aslides = ZSlideshow::getList($id_lang);

        if ($aslides) {
            foreach ($aslides as &$slide) {
                $slide['related_products'] = $this->getRelatedProducts($slide['related_products'], $id_lang);

                if ($slide['image']) {
                    $imgInfo = getimagesize($this->local_path.$this->slide_img_folder.$slide['image']);
                    $slide['width'] = $imgInfo[0];
                    $slide['height'] = $imgInfo[1];
                }
            }
        }

        $settings = Tools::unSerialize(Configuration::get('ZONESLIDESHOW_SETTINGS'));
        $effects = array();
        foreach ($this->nivo_effects['query'] as $effect) {
            $effect_id = 'effect_'.$effect['id'];
            if ($settings[$effect_id]) {
                $effects[] = $effect['id'];
            }
        }
        if (empty($effects)) {
            $effects[] = 'random';
        }
        $settings['effect'] = implode(',', $effects);
        if (count($aslides) < 2) {
            $settings['manualAdvance'] = 1;
            $one_slide = true;
        }

        $this->smarty->assign(array(
            'aslides' => $aslides,
            'oneSlide' => $one_slide,
            'settings' => $settings,
            'image_baseurl' => $this->_path.$this->slide_img_folder,
            'thumb_baseurl' => $this->_path.$this->slide_thumb_folder,
        ));
    }

    public function hookDisplayHome()
    {
        if (!$this->isCached('zoneslideshow.tpl', $this->getCacheId())) {
            $this->preProcess();
        }

        return $this->display(__FILE__, 'zoneslideshow.tpl', $this->getCacheId());
    }

    public function hookDisplayTopColumn()
    {
        if (!isset($this->context->controller->php_self) || $this->context->controller->php_self != 'index') {
            return;
        }

        return $this->hookDisplayHome();
    }
}
