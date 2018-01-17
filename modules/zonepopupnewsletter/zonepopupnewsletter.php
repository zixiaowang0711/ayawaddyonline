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

include_once dirname(__FILE__).'/classes/ZPopupNewsletter.php';

class ZOnePopupNewsletter extends Module
{
    protected $html = '';
    protected $bg_img_folder = 'views/img/bgImages/';

    public function __construct()
    {
        $this->name = 'zonepopupnewsletter';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'Mr.ZOne';
        $this->need_instance = 0;
        $this->ps_versions_compliancy = array('min' => '1.7', 'max' => _PS_VERSION_);

        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->getTranslator()->trans(
            'Z.One - Popup Newsletter',
            array(),
            'Modules.ZonePopupnewsletter.Admin'
        );
        $this->description = $this->getTranslator()->trans(
            'Displays a popup newsletter when page load.',
            array(),
            'Modules.ZonePopupnewsletter.Admin'
        );
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
            && $this->registerHook('displayOutsideMainPage');
    }

    public function uninstall()
    {
        $sql = 'DROP TABLE IF EXISTS
            `'._DB_PREFIX_.'zpopupnewsletter`,
            `'._DB_PREFIX_.'zpopupnewsletter_lang`';

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

    protected function blockNewsletter()
    {
        $name = 'ps_emailsubscription';
        $module_instance = Module::getInstanceByName($name);

        $enabled = true;
        if (!Module::isInstalled($name) || !Module::isEnabled($name)) {
            $enabled = false;
        }

        $this->smarty->assign(array(
            'module_enabled' => $enabled,
            'module_name' => $module_instance->displayName,
            'module_link' => $this->context->link->getAdminLink('AdminModules', true).'&configure='.urlencode($module_instance->name),
        ));

        return $this->display(__FILE__, 'views/templates/admin/newsletter-message.tpl');
    }

    public function getContent()
    {
        $this->context->controller->addJS($this->_path.'views/js/back.js');
        $this->context->controller->addCSS($this->_path.'views/css/back.css');

        $about = $this->about();
        $message = $this->blockNewsletter();

        if (Tools::isSubmit('submitSettings')) {
            $this->processSaveSettings();

            return $message.$this->html.$this->renderSettingsForm().$about;
        } elseif (Tools::isSubmit('deleteBackgroundImage')) {
            $zpopupnewsletter = ZPopupNewsletter::getNewsletterByShop();
            if ($zpopupnewsletter->bg_image) {
                $image_path = $this->local_path.$this->bg_img_folder.$zpopupnewsletter->bg_image;

                if (file_exists($image_path)) {
                    unlink($image_path);
                }

                $zpopupnewsletter->bg_image = null;
                $zpopupnewsletter->update(false);
                $this->_clearCache('*');
            }

            Tools::redirectAdmin(AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&conf=7');
        } else {
            return $message.$this->renderSettingsForm().$about;
        }
    }

    protected function processSaveSettings()
    {
        $zpopupnewsletter = ZPopupNewsletter::getNewsletterByShop();

        $zpopupnewsletter->active = (int) Tools::getValue('active');
        $zpopupnewsletter->width = (int) Tools::getValue('width');
        $zpopupnewsletter->height = (int) Tools::getValue('height');
        $zpopupnewsletter->bg_color = Tools::getValue('bg_color');
        $zpopupnewsletter->cookie_time = (int) Tools::getValue('cookie_time');
        $zpopupnewsletter->save_time = strtotime('now');

        $languages = Language::getLanguages(false);
        $id_lang_default = (int) Configuration::get('PS_LANG_DEFAULT');
        $content = array();
        foreach ($languages as $lang) {
            $content[$lang['id_lang']] = Tools::getValue('content_'.$lang['id_lang']);
            if (!$content[$lang['id_lang']]) {
                $content[$lang['id_lang']] = Tools::getValue('content_'.$id_lang_default);
            }
        }
        $zpopupnewsletter->content = $content;

        if (isset($_FILES['bg_image']) && isset($_FILES['bg_image']['tmp_name']) && !empty($_FILES['bg_image']['tmp_name'])) {
            if ($error = ImageManager::validateUpload($_FILES['bg_image'], Tools::convertBytes(ini_get('upload_max_filesize')))) {
                $this->html .= $this->displayError($error);
            } else {
                if (move_uploaded_file($_FILES['bg_image']['tmp_name'], $this->local_path.$this->bg_img_folder.$_FILES['bg_image']['name'])) {
                    $zpopupnewsletter->bg_image = $_FILES['bg_image']['name'];
                } else {
                    $this->html .= $this->displayError($this->getTranslator()->trans(
                        'File upload error.',
                        array(),
                        'Modules.ZonePopupnewsletter.Admin'
                    ));
                }
            }
        }

        $result = $zpopupnewsletter->validateFields(false) && $zpopupnewsletter->validateFieldsLang(false);

        if ($result) {
            $zpopupnewsletter->save();

            $this->html .= $this->displayConfirmation($this->getTranslator()->trans(
                'Settings has been updated successfully.',
                array(),
                'Modules.ZonePopupnewsletter.Admin'
            ));

            $this->_clearCache('*');
        } else {
            $this->html .= $this->displayError($this->getTranslator()->trans(
                'An error occurred while attempting to save Settings.',
                array(),
                'Modules.ZonePopupnewsletter.Admin'
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
        $helper->currentIndex = AdminController::$currentIndex.'&configure='.$this->name;
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
        $zpopupnewsletter = ZPopupNewsletter::getNewsletterByShop();

        $image_url = false;
        $image_size = false;
        if ($zpopupnewsletter && file_exists($this->local_path.$this->bg_img_folder.$zpopupnewsletter->bg_image)) {
            if ($zpopupnewsletter->bg_image) {
                $image_url = $this->_path.$this->bg_img_folder.$zpopupnewsletter->bg_image;
                $image_size = filesize($this->local_path.$this->bg_img_folder.$zpopupnewsletter->bg_image) / 1000;
            }
        }

        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->getTranslator()->trans(
                        'Popup Newsletter',
                        array(),
                        'Modules.ZonePopupnewsletter.Admin'
                    ),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
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
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans(
                            'Popup Width',
                            array(),
                            'Modules.ZonePopupnewsletter.Admin'
                        ),
                        'name' => 'width',
                        'col' => 2,
                        'suffix' => 'px',
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans(
                            'Popup Height',
                            array(),
                            'Modules.ZonePopupnewsletter.Admin'
                        ),
                        'name' => 'height',
                        'col' => 2,
                        'suffix' => 'px',
                    ),
                    array(
                        'type' => 'color',
                        'label' => $this->getTranslator()->trans(
                            'Background Color',
                            array(),
                            'Modules.ZonePopupnewsletter.Admin'
                        ),
                        'name' => 'bg_color',
                        'hint' => $this->getTranslator()->trans(
                            'Background color of Popup Newsletter',
                            array(),
                            'Modules.ZonePopupnewsletter.Admin'
                        ),
                    ),
                    array(
                        'type' => 'file',
                        'label' => $this->getTranslator()->trans(
                            'Background Image',
                            array(),
                            'Modules.ZonePopupnewsletter.Admin'
                        ),
                        'name' => 'bg_image',
                        'hint' => $this->getTranslator()->trans(
                            'Upload a new background image for Popup Newsletter',
                            array(),
                            'Modules.ZonePopupnewsletter.Admin'
                        ),
                        'display_image' => true,
                        'image' => $image_url ? '<img src="'.$image_url.'" alt="" class="img-thumbnail" style="max-width: 430px;" />' : false,
                        'size' => $image_size,
                        'delete_url' => AdminController::$currentIndex.'&configure='.$this->name.'&token='.Tools::getAdminTokenLite('AdminModules').'&deleteBackgroundImage',
                        'desc' => $this->getTranslator()->trans(
                            'Recommended dimensions are 670x500 pixels',
                            array(),
                            'Modules.ZonePopupnewsletter.Admin'
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->getTranslator()->trans(
                            'Cookie Time',
                            array(),
                            'Modules.ZonePopupnewsletter.Admin'
                        ),
                        'name' => 'cookie_time',
                        'col' => 2,
                        'suffix' => $this->getTranslator()->trans(
                            'days',
                            array(),
                            'Modules.ZonePopupnewsletter.Admin'
                        ),
                        'hint' => $this->getTranslator()->trans(
                            'How long should be cookie stored?',
                            array(),
                            'Modules.ZonePopupnewsletter.Admin'
                        ),
                        'desc' => $this->getTranslator()->trans(
                            '0 = when browser closes',
                            array(),
                            'Modules.ZonePopupnewsletter.Admin'
                        ),
                    ),
                    array(
                        'type' => 'textarea',
                        'label' => $this->getTranslator()->trans(
                            'Content',
                            array(),
                            'Modules.ZonePopupnewsletter.Admin'
                        ),
                        'name' => 'content',
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
            ),
        );

        return $fields_form;
    }

    protected function getSettingsFieldsValues()
    {
        $zpopupnewsletter = ZPopupNewsletter::getNewsletterByShop();

        $fields_value = array(
            'active' => Tools::getValue('active', $zpopupnewsletter->active),
            'width' => Tools::getValue('width', $zpopupnewsletter->width),
            'height' => Tools::getValue('height', $zpopupnewsletter->height),
            'bg_color' => Tools::getValue('bg_color', $zpopupnewsletter->bg_color),
            'cookie_time' => Tools::getValue('cookie_time', $zpopupnewsletter->cookie_time),
        );

        $languages = Language::getLanguages(false);
        foreach ($languages as $lang) {
            $default_content = isset($zpopupnewsletter->content[$lang['id_lang']]) ? $zpopupnewsletter->content[$lang['id_lang']] : '';
            $fields_value['content'][$lang['id_lang']] = Tools::getValue('content_'.(int) $lang['id_lang'], $default_content);
        }

        return $fields_value;
    }

    protected function preProcess()
    {
        $id_lang = (int) $this->context->language->id;
        $zpopupnewsletter = ZPopupNewsletter::getNewsletterByShop($id_lang);

        $name = 'ps_emailsubscription';
        $module_instance = Module::getInstanceByName($name);
        $subscribe_form = false;

        if (Module::isInstalled($name) && Module::isEnabled($name)) {
            $subscribe_form = $module_instance->renderWidget();
        }

        $this->smarty->assign(array(
            'id_zpopupnewsletter' => $zpopupnewsletter->id_zpopupnewsletter,
            'width' => $zpopupnewsletter->width,
            'height' => $zpopupnewsletter->height,
            'bg_color' => $zpopupnewsletter->bg_color,
            'bg_image' => $zpopupnewsletter->bg_image,
            'content' => $zpopupnewsletter->content,
            'active' => $zpopupnewsletter->active,
            'cookie_time' => $zpopupnewsletter->cookie_time,
            'ajax_subscribe_url' => $this->_path.'ajax_subscribe.php',
            'bg_image_url' => $this->_path.$this->bg_img_folder,
            'save_time' => $zpopupnewsletter->save_time,
            'subscribe_form' => $subscribe_form,
        ));
    }

    public function hookDisplayBeforeBodyClosingTag()
    {
        return $this->hookDisplayOutsideMainPage();
    }

    public function hookDisplayOutsideMainPage()
    {
        if (!$this->isCached('zonepopupnewsletter.tpl', $this->getCacheId())) {
            $this->preProcess();
        }

        return $this->display(__FILE__, 'zonepopupnewsletter.tpl', $this->getCacheId());
    }
}
