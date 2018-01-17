<?php
/**
* 2013 - 2017 HiPresta
*
* MODULE Facebook Connect
*
* @version   1.1.0
* @author    HiPresta <suren.mikaelyan@gmail.com>
* @link      http://www.hipresta.com
* @copyright HiPresta 2017
* @license   PrestaShop Addons license limitation
*
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

include_once(dirname(__FILE__).'/classes/HiPrestaModule.php');

class HiFacebookConnect extends HiPrestaFBCModule
{
    public $errors = array();
    public $success = array();
    public $psv;
    public $clean_db;
    public $sdk_on;
    protected $module_hooks = array(
        'displayTop',
        'displayLeftColumn',
        'displayRightColumn',
    );
    /* Globals */
    public $hi_sc_fb_login_page;
    public $hi_sc_fb_on;
    public $hi_sc_fb_id;
    public $hi_sc_fb_position_top;
    public $hi_sc_fb_position_custom;
    public $hi_sc_fb_position_right;
    public $hi_sc_fb_position_left;

    public function __construct()
    {
        $this->name = 'hifacebookconnect';
        $this->tab = 'front_office_features';
        $this->version = '1.1.0';
        $this->author = 'HiPresta';
        $this->need_instance = 0;
        $psv = (float)tools::substr(_PS_VERSION_, 0, 3);
        if ($psv >= 1.6) {
            $this->bootstrap = true;
        }
        $this->module_key = '95dca7a0e45ab2bba717e3c7a0c82e82';
        parent::__construct();
        $this->globalVars();
        $this->secure_key = Tools::encrypt($this->name);
        $this->displayName = $this->l('Facebook Connect');
        $this->description = $this->l('Allow your customers to sign in with Facebook');
        $this->confirmUninstall = $this->l('Are you sure you want to uninstall?');
    }

    public function install()
    {
        if (!parent::install()
            || !$this->createTabs('AdminHiScFacebook', 'AdminHiScFacebook', 'CONTROLLER_TABS_HI_SC_FB', 0)
            || !$this->registerHook('displayNav')
            || !$this->registerHook('displayNav2')
            || !$this->registerHook('header')
            || !$this->registerHook('hiFacebookConnect')
            || !$this->createFbTable()
        ) {
            return false;
        }
        $this->proceedDb();
        return true;
    }

    public function uninstall()
    {
        if (!parent::uninstall()) {
            return false;
        }
        if (Configuration::get('HI_SC_FB_CLEAN_DB')) {
            $this->proceedDb(true);
        }
        return true;
    }

    private function createFbTable()
    {
        $sql = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'hifacebookusers` (
           `id`            INT NOT NULL AUTO_INCREMENT,
           `id_user`       VARCHAR (100) NOT NULL,
           `id_shop_group` INT (11) NOT NULL,
           `id_shop`       INT (11) NOT NULL,
           `first_name`    VARCHAR (100) NOT NULL,
           `last_name`     VARCHAR (100) NOT NULL,
           `email`         VARCHAR (100) NOT NULL,
           `gender`        VARCHAR (100) NOT NULL,
           `date_add`      DATE NOT NULL,
           `date_upd`      DATE NOT NULL,
           PRIMARY KEY     ( `id` )
        ) ENGINE = '._MYSQL_ENGINE_.' DEFAULT CHARSET=UTF8;';
        return Db::getInstance()->Execute(trim($sql));
    }

    public function proceedDb($drop = false)
    {
        if ($drop) {
            $db_drop = array('hifacebookusers');
            foreach ($db_drop as $value) {
                DB::getInstance()->Execute('DROP TABLE IF EXISTS '._DB_PREFIX_.$value);
            }
        }

        if (!$drop) {
            Configuration::updateValue('HI_SC_FB_CLEAN_DB', false);
            Configuration::updateValue('HI_SC_FB_SDK', true);
            Configuration::updateValue('HI_SC_FB_LOGIN_PAGE', 'no_redirect');
            Configuration::updateValue('HI_SC_FB_ON', true);
            Configuration::updateValue('HI_SC_FB_ID', '');
            Configuration::updateValue('HI_SC_FB_POSITION_TOP', true);
            Configuration::updateValue('HI_SC_FB_POSITION_RIGHT', false);
            Configuration::updateValue('HI_SC_FB_POSITION_LEFT', false);
            Configuration::updateValue('HI_SC_FB_POSITION_CUSTOM', false);
            Configuration::updateValue('HI_SC_FB_BUTTON_SIZE_TOP', 'small');
            Configuration::updateValue('HI_SC_FB_BUTTON_SIZE_RIGHT', 'big');
            Configuration::updateValue('HI_SC_FB_BUTTON_SIZE_LEFT', 'big');
        } else {
            Configuration::deleteByName('HI_SC_FB_CLEAN_DB');
            Configuration::deleteByName('HI_SC_FB_SDK');
            Configuration::deleteByName('HI_SC_FB_LOGIN_PAGE');
            Configuration::deleteByName('HI_SC_FB_ON');
            Configuration::deleteByName('HI_SC_FB_ID');
            Configuration::deleteByName('HI_SC_FB_POSITION_TOP');
            Configuration::deleteByName('HI_SC_FB_POSITION_RIGHT');
            Configuration::deleteByName('HI_SC_FB_POSITION_LEFT');
            Configuration::deleteByName('HI_SC_FB_POSITION_CUSTOM');
            Configuration::deleteByName('HI_SC_FB_BUTTON_SIZE_TOP');
            Configuration::deleteByName('HI_SC_FB_BUTTON_SIZE_RIGHT');
            Configuration::deleteByName('HI_SC_FB_BUTTON_SIZE_LEFT');
        }

    }

    private function globalVars()
    {
        $this->psv = (float)Tools::substr(_PS_VERSION_, 0, 3);
        $this->clean_db = (bool)Configuration::get('HI_SC_FB_CLEAN_DB');
        $this->sdk_on = (bool)Configuration::get('HI_SC_FB_SDK');
        $this->hi_sc_fb_login_page = Configuration::get('HI_SC_FB_LOGIN_PAGE');
        $this->hi_sc_fb_on = (bool)Configuration::get('HI_SC_FB_ON');
        $this->hi_sc_fb_id = Configuration::get('HI_SC_FB_ID');
        $this->hi_sc_fb_position_top = Configuration::get('HI_SC_FB_POSITION_TOP');
        $this->hi_sc_fb_position_right = Configuration::get('HI_SC_FB_POSITION_RIGHT');
        $this->hi_sc_fb_position_left = Configuration::get('HI_SC_FB_POSITION_LEFT');
        $this->hi_sc_fb_position_custom = (bool)Configuration::get('HI_SC_FB_POSITION_CUSTOM');
        $this->hi_sc_fb_button_size_top = Configuration::get('HI_SC_FB_BUTTON_SIZE_TOP');
        $this->hi_sc_fb_button_size_left = Configuration::get('HI_SC_FB_BUTTON_SIZE_RIGHT');
        $this->hi_sc_fb_button_size_right = Configuration::get('HI_SC_FB_BUTTON_SIZE_LEFT');
    }

    private function isSelectedShopGroup()
    {
        if (Shop::getContext() == Shop::CONTEXT_GROUP || Shop::getContext() == Shop::CONTEXT_ALL) {
            return true;
        } else {
            return false;
        }
    }

    public function renderShopGroupError()
    {
        $this->context->smarty->assign(
            array('psv' => $this->psv,)
        );
        return $this->display(__FILE__, 'views/templates/admin/shop_group_error.tpl');
    }

    public function renderMenuTabs()
    {
        $tabs = array(
            'generel_sett' => $this->l('General Settings'),
            'connect_sett' => $this->l('Connect Settings'),
            'users' => $this->l('Registered Users'),
            'version' => $this->l('Version'),
            'documentation' => $this->l('Documentation'),
            'news' => $this->l('News'),
        );
        $this->context->smarty->assign(
            array(
                'psv' => $this->psv,
                'tabs' => $tabs,
                'module_version' => $this->version,
                'module_url' => $this->getModuleUrl(),
                'protocol' => Tools::getProtocol(),
                'url_key' => Tools::getValue('hiscfacebook'),
            )
        );
        return $this->display(__FILE__, 'views/templates/admin/menu_tabs.tpl');
    }

    public function renderAdminStructure($form)
    {
        $this->context->smarty->assign(
            array(
                'psv' => $this->psv,
                'hisc_fb_module_url' => Tools::getHttpHost(true)._MODULE_DIR_.$this->name,
                'errors' => $this->errors,
                'success' => $this->success,
                'action' => Tools::getValue('hiscfacebook'),
                'form' => $form
            )
        );
        return $this->display(__FILE__, 'views/templates/admin/display_form.tpl');
    }

    public function renderDocumentationForm()
    {
        $this->context->smarty->assign(array('psv' => $this->psv,));
        return $this->display(__FILE__, 'views/templates/admin/documentation.tpl');
    }

    public function renderModuleAdminVariables()
    {

        $this->context->smarty->assign(
            array(
                'psv' => $this->psv,
                'id_lang' => $this->context->language->id,
                'hi_sc_fb_admin_controller_dir' => $this->context->link->getAdminLink('AdminHiScFacebook'),
            )
        );
        return $this->display(__FILE__, 'views/templates/admin/variables.tpl');
    }
    public function renderModuleAdvertisingForm()
    {
        $this->context->smarty->assign(
            array(
                'psv' => $this->psv,
            )
        );
        return $this->display(__FILE__, 'views/templates/admin/moduleadvertising.tpl');
    }

    public function renderSettingsForm()
    {
        $fields_form = array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('General Settings'),
                    'icon' => 'icon-cogs'
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'label' => $this->l('Redirect after login'),
                        'name' => 'login_page',
                        'options' => array(
                            'query' => array(
                                array('id' => 'no_redirect', 'name' => $this->l('No redirect')),
                                array('id' => 'authentication_page', 'name' => $this->l('Authentication page')),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        ),
                    ),
                    array(
                        'type' => $this->psv >= 1.6 ? 'switch':'radio',
                        'label' => $this->l('Load Facebook SDK JS'),
                        'name' => 'sdk_on',
                        'class' => 't',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'sdk_on_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'sdk_on_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                        'desc' => $this->l('Disable this if Facebook JS already loads in your theme')
                    ),
                    array(
                        'type' => $this->psv >= 1.6 ? 'switch':'radio',
                        'label' => $this->l('Clean Database when module uninstalled'),
                        'name' => 'clean_db',
                        'class' => 't',
                        'is_bool' => true,
                        'desc' => $this->l('Not recommended, use this only when you’re not going to use the module'),
                        'values' => array(
                            array(
                                'id' => 'clean_db_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'clean_db_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'hidden',
                        'name' => 'psv',
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'settings_submit',
                    'class' => $this->psv >= 1.6 ? 'btn btn-default pull-right':'button',
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $this->fields_form = array();
        $helper->submit_action = 'submitBlockSettings';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&hifacebook=generel_sett';
         $helper->module = $this;
        $helper->tpl_vars = array(
            'fields_value' => $this->getConfigFieldsValues()
        );
        $this->context->smarty->assign(
            array(
                'psv' => $this->psv,
            )
        );
        $helper->override_folder = '/';
        return $helper->generateForm(array($fields_form));
    }

    public function getConfigFieldsValues()
    {
        return array(
            'psv' => $this->psv,
            'login_page' => $this->hi_sc_fb_login_page,
            'clean_db' => $this->clean_db,
            'sdk_on' => $this->sdk_on,
        );
    }

    public function renderFacebookConnectForm()
    {
         $fields_form = array(
            'form' => array(
                'input' => array(
                    array(
                        'type' => $this->psv >= 1.6 ? 'switch':'radio',
                        'label' => $this->l('Enable Button'),
                        'name' => 'hi_sc_fb_on',
                        'class' => 't',
                        'is_bool' => true,
                        'values' => array(
                            array(
                                'id' => 'hi_sc_fb_on_on',
                                'value' => 1,
                                'label' => $this->l('Enabled')
                            ),
                            array(
                                'id' => 'hi_sc_fb_on_off',
                                'value' => 0,
                                'label' => $this->l('Disabled')
                            )
                        ),
                    ),
                    array(
                        'type' => 'text',
                        'label' => $this->l('Facebook App ID'),
                        'name' => 'hi_sc_fb_id',
                    ),
                    array(
                        'label' => $this->l('Positions to display'),
                        'name' => 'hi_sc_fb_position',
                        'type' => 'checkbox',
                        'desc' => $this->l('Add {hook h="HiFacebookConnect" button_size="big/small"} to your page tpl file where you want to display.'),
                        'values' => array(
                            'query' => array(
                                array(
                                    'id' => 'displayTop',
                                    'name' => $this->l('Top'),
                                    'val' => 'displayTop',
                                ),
                                array(
                                    'id' => 'displayLeftColumn',
                                    'name' => $this->l('Left'),
                                    'val' => 'displayLeftColumn',
                                ),
                                array(
                                    'id' => 'displayRightColumn',
                                    'name' => $this->l('Right'),
                                    'val' => 'displayRightColumn',
                                ),
                                array(
                                    'id' => 'custom',
                                    'name' => $this->l('Custom'),
                                    'val' => 1,
                                ),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        )
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Button size in top position'),
                        'name' => 'hi_sc_fb_button_size_top',
                        'options' => array(
                            'query' => array(
                                array('id' => 'small', 'name' => $this->l('Small')),
                                array('id' => 'big', 'name' => $this->l('Big')),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Button size in left position'),
                        'name' => 'hi_sc_fb_button_size_left',
                        'options' => array(
                            'query' => array(
                                array('id' => 'small', 'name' => $this->l('Small')),
                                array('id' => 'big', 'name' => $this->l('Big')),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        ),
                    ),
                    array(
                        'type' => 'select',
                        'label' => $this->l('Button size in right position'),
                        'name' => 'hi_sc_fb_button_size_right',
                        'options' => array(
                            'query' => array(
                                array('id' => 'small', 'name' => $this->l('Small')),
                                array('id' => 'big', 'name' => $this->l('Big')),
                            ),
                            'id' => 'id',
                            'name' => 'name'
                        ),
                    ),
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'name' => 'connect_sett_submit',
                    'class' => $this->psv >= 1.6 ? 'btn btn-default pull-right':'button',
                )
            ),
        );

        $helper = new HelperForm();
        $helper->show_toolbar = false;
        $helper->default_form_language = (int)Configuration::get('PS_LANG_DEFAULT');
        $this->fields_form = array();
        $helper->submit_action = 'submitBlockSettings';
        $helper->token = Tools::getAdminTokenLite('AdminModules');
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false).'&configure='.$this->name.'&tab_module='.$this->tab.'&module_name='.$this->name.'&hiscfacebook=connect_sett';
        $helper->tpl_vars = array(
            'fields_value' => $this->getFacebookConnectFieldsValues()
        );
        return $helper->generateForm(array($fields_form));
    }

    public function getFacebookConnectFieldsValues()
    {
        $return = array(
            'hi_sc_fb_on' => $this->hi_sc_fb_on,
            'hi_sc_fb_id' => $this->hi_sc_fb_id,
            'hi_sc_fb_position_displayTop' => $this->hi_sc_fb_position_top,
            'hi_sc_fb_position_displayLeftColumn' => $this->hi_sc_fb_position_left,
            'hi_sc_fb_position_displayRightColumn' => $this->hi_sc_fb_position_right,
            'hi_sc_fb_position_custom' => $this->hi_sc_fb_position_custom,
            'hi_sc_fb_button_size_top' => $this->hi_sc_fb_button_size_top,
            'hi_sc_fb_button_size_left' => $this->hi_sc_fb_button_size_left,
            'hi_sc_fb_button_size_right' => $this->hi_sc_fb_button_size_right,
            
        );
        return $return;
    }

    public function renderFacebookConnectUsersList()
    {
        $fields_list = array(
            'id' => array(
                'title' => $this->l('ID'),
                'search' => false,
            ),
            'id_user' => array(
                'title' => $this->l('ID user'),
                'search' => false,
            ),
            'first_name' => array(
                'title' => $this->l('First name'),
                'search' => false,
            ),
            'last_name' => array(
                'title' => $this->l('Last name'),
                'search' => false,
            ),
            'email' => array(
                'title' => $this->l('Email'),
                'search' => false,
            ),
            'date_add' => array(
                'title' => $this->l('Date add'),
            ),
        );
        if (!Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE')) {
            unset($fields_list['shop_name']);
        }
        $helper_list = new HelperList();
        $helper_list->module = $this;
        $helper_list->title = $this->l('Users');
        $helper_list->shopLinkType = '';
        $helper_list->no_link = true;
        $helper_list->show_toolbar = false;
        $helper_list->simple_header = true;
        $helper_list->identifier = 'id';
        $helper_list->table = 'hifacebookusers';
        $helper_list->actions = array('delete');
        $helper_list->currentIndex = $this->context->link->getAdminLink(
            'AdminModules',
            false
        ).'&configure='.$this->name.'&hiscfacebook=users';
        $helper_list->token = Tools::getAdminTokenLite('AdminModules');
        $this->_helperlist = $helper_list;
        $subscribers = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'hifacebookusers');
        $helper_list->listTotal = count($subscribers);
        $page = ($page = Tools::getValue('submitFilter'.$helper_list->table)) ? $page : 1;
        $pagination = ($pagination = Tools::getValue($helper_list->table.'_pagination')) ? $pagination : 50;
        $subscribers = $this->pagination($subscribers, $page, $pagination);
        return $helper_list->generateList($subscribers, $fields_list);
    }

    public function pagination($subscribers, $page = 1, $pagination = 50)
    {
        if (count($subscribers) > $pagination) {
            $subscribers = array_slice($subscribers, $pagination * ($page - 1), $pagination);
        }
        return $subscribers;
    }


    public function displayForm()
    {
        $html = '';
        $content = '';
        if (!$this->isSelectedShopGroup()) {
            $html .= $this->renderMenuTabs();
            switch (Tools::getValue('hiscfacebook')) {
                case 'generel_sett':
                    $content .= $this->renderSettingsForm();
                    break;
                case 'connect_sett':
                    $content .= $this->renderFacebookConnectForm();
                    break;
                case 'users':
                    $content .= $this->renderFacebookConnectUsersList();
                    break;
                case 'documentation':
                    $content .= $this->renderDocumentationForm();
                    break;
                case 'news':
                    $content .= $this->renderNewsForm();
                    break;
                default:
                    $content .= $this->renderSettingsForm();
                    break;
            }
            $html .= $this->renderAdminStructure($content);
            $html .= $this->renderModuleAdminVariables();
            $html .= $this->renderModuleAdvertisingForm();
        } else {
            $html .= $this->renderShopGroupError();
        }
        $this->context->controller->addCSS($this->_path.'views/css/facebook-connect-admin.css', 'all');
        $this->context->controller->addJS($this->_path.'views/js/facebook-connect-admin.js');
        return $html;
    }

    public function renderNewsForm()
    {
        $cookie = new Cookie('psAdmin');
        $employee = new Employee($cookie->id_employee);
        $this->context->smarty->assign(
            array(
                'employee_fname' => $cookie->id_employee ? $employee->firstname : '',
                'employee_lname' => $cookie->id_employee ? $employee->lastname : '',
                'employee_email' => $cookie->id_employee ? $employee->email : '',
            )
        );
        return $this->display(__FILE__, 'views/templates/admin/news.tpl');
    }

    public function postProcess()
    {
        if (Tools::isSubmit('settings_submit')) {
            Configuration::updateValue('HI_SC_FB_LOGIN_PAGE', Tools::getValue('login_page'));
            Configuration::updateValue('HI_SC_FB_CLEAN_DB', Tools::getValue('clean_db'));
            Configuration::updateValue('HI_SC_FB_SDK', Tools::getValue('sdk_on'));
            $this->success[] = $this->l('Successful Save');
        }
        if (Tools::isSubmit('connect_sett_submit')) {
            Configuration::updateValue('HI_SC_FB_ON', (bool)Tools::getValue('hi_sc_fb_on'));
            Configuration::updateValue('HI_SC_FB_POSITION_TOP', Tools::getValue('hi_sc_fb_position_displayTop'));
            Configuration::updateValue('HI_SC_FB_ID', trim(Tools::getValue('hi_sc_fb_id')));
            Configuration::updateValue('HI_SC_FB_POSITION_RIGHT', Tools::getValue('hi_sc_fb_position_displayRightColumn'));
            Configuration::updateValue('HI_SC_FB_POSITION_LEFT', Tools::getValue('hi_sc_fb_position_displayLeftColumn'));
            Configuration::updateValue('HI_SC_FB_POSITION_CUSTOM', (bool)Tools::getValue('hi_sc_fb_position_custom'));
            Configuration::updateValue('HI_SC_FB_ID', trim(Tools::getValue('hi_sc_fb_id')));
            Configuration::updateValue('HI_SC_FB_BUTTON_SIZE_TOP', Tools::getValue('hi_sc_fb_button_size_top'));
            Configuration::updateValue('HI_SC_FB_BUTTON_SIZE_RIGHT', Tools::getValue('hi_sc_fb_button_size_right'));
            Configuration::updateValue('HI_SC_FB_BUTTON_SIZE_LEFT', Tools::getValue('hi_sc_fb_button_size_left'));
            $this->success[] = $this->l('Successful Save');
        }
    }

    public function getContent()
    {
        if (Tools::isSubmit('settings_submit') || Tools::isSubmit('connect_sett_submit')) {
            $this->postProcess();
        }
        $this->globalVars();
        if ($this->psv >= 1.7) {
            $this->createEmailLangFiles();
        }
        $this->autoRegisterHook($this->id, array($this->hi_sc_fb_position_top, $this->hi_sc_fb_position_right, $this->hi_sc_fb_position_left));
        return $this->displayForm();
    }

    public function prepareHooks($hook, $btn_size = null)
    {
        if ($this->hi_sc_fb_on && $this->hi_sc_fb_id && $this->{'hi_sc_fb_position_'.$hook} && !$this->context->customer->isLogged()) {
            if ($hook == 'custom') {
                $button_size = $btn_size;
            } else {
                $button_size = $this->{'hi_sc_fb_button_size_'.$hook};
            }
            $this->context->smarty->assign(array(
                'hi_sc_fb_on' => true,
                'hi_sc_fb_button_size' => $button_size,
            ));
            return $this->display(__FILE__, 'facebookconnect.tpl');
        }
    }

    public function hookDisplayHeader()
    {
        $this->context->smarty->assign(array(
            'secure_key' => $this->secure_key,
            'sdk_on' => $this->sdk_on,
            'sc_fb_loader' => Tools::getHttpHost(true).__PS_BASE_URI__.'modules/'
                .$this->name.'/views/img/loaders/spinner.gif',
            'facebook_id' => $this->hi_sc_fb_id,
            'login_page' => $this->hi_sc_fb_login_page,
            'authentication_page' => $this->context->link->getPageLink('my-account', true),
            'hi_sc_fb_front_controller_dir' => $this->context->link->getModuleLink('hifacebookconnect', 'facebookconnect').(Configuration::get('PS_REWRITING_SETTINGS') ? '?' : '&' ).'content_only=1',
            'hi_sc_fb_module_dir' => Tools::getHttpHost(true)._MODULE_DIR_.$this->name,
            'hi_sc_fb_base_url' => Tools::getHttpHost(true).__PS_BASE_URI__,
            'hi_sc_fb_on' => $this->hi_sc_fb_on,
        ));
        $this->context->controller->addCSS($this->_path.'views/css/facebook-connect-front.css', 'all');
        $this->context->controller->addCSS($this->_path.'views/css/facebook-connect-front'.$this->psv.'.css', 'all');
        $this->context->controller->addJs($this->_path.'views/js/facebook-connect-front.js');
        return $this->display(__FILE__, 'header.tpl');
    }

    public function hookTop($params)
    {
        if ($this->psv < 1.6) {
            return $this->hookDisplayNav($params);
        }
    }

    public function hookDisplayNav($params)
    {
        return $this->prepareHooks('top');
    }

    public function hookDisplayNav2($params)
    {
        return $this->prepareHooks('top');
    }

    public function hookLeftColumn()
    {
        return $this->prepareHooks('left');
    }

    public function hookRightColumn()
    {
        return $this->prepareHooks('right');
    }

    public function hookHiFacebookConnect($params)
    {
        $button_size = isset($params['button_size'])?$params['button_size']:'';
        return $this->prepareHooks('custom', $button_size);
    }

    public function sendConfirmationMail(Customer $customer, $password)
    {
        if (!Configuration::get('PS_CUSTOMER_CREATION_EMAIL')) {
            return true;
        }
        if ($this->psv >= 1.7) {
            $html = 'account17';
            $mail_path = _PS_MODULE_DIR_.$this->name.'/mails/';
        } else {
            $html = 'account';
            $mail_path = _PS_MAIL_DIR_;
        }
        return Mail::Send(
            $this->context->language->id,
            $html,
            Mail::l('Welcome!'),
            array(
                '{firstname}' => $customer->firstname,
                '{lastname}' => $customer->lastname,
                '{email}' => $customer->email,
                '{passwd}' => $password),
            $customer->email,
            $customer->firstname.' '.$customer->lastname,
            null,
            null,
            null,
            null,
            $mail_path
        );
    }
}
