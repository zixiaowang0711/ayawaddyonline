<?php
/**
 * Prestashop module for Oct8ne
 *
 * @category  Prestashop
 * @category  Module
 * @author    Prestaquality.com
 * @copyright 2016 Prestaquality
 * @license   Commercial license see license.txt
 * Support by mail  : info@prestaquality.com
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

$pq_core_path = dirname(__FILE__) . '/lib/Oct8neCore.php';
if (!file_exists($pq_core_path)) {
    exit;
}
require_once $pq_core_path;

class Oct8ne extends Oct8neCore
{
    private static $EMAIL_CK = "OCT_EMAIL";
    private static $PASSWORD_CK = "OCT_PASS";
    private static $API_TOKEN_CK = "OCT_API_TOKEN";
    private static $LICENSE_ID_CK = "OCT_LICID";
    public static $SEARCH_ENGINE = "OCT_SEARCH_ENGINE";
    public static $POSITION_LOAD = "OCT_POSITION_LOAD";
    public static $URL_IMG_TYPE = "OCT_URL_IMG_TYPE";

    /**
     * @return string
     */
    public function getSEARCHENGINENAME()
    {
        return self::$SEARCH_ENGINE;
    }

    /**
     * @return string
     */
    public function getPOSITIONLOADNAME()
    {
        return self::$POSITION_LOAD;
    }

    /**
     * @return string
     */
    public function getURLIMGTYPENAME()
    {
        return self::$URL_IMG_TYPE;
    }

    /**
     * Oct8ne constructor.
     * Variables de configuracion del modulo
     */
    public function __construct()
    {
        $this->name = 'oct8ne';
        $this->tab = 'front_office_features';
        $this->version = '1.0.19';
        $this->author = 'Oct8ne';
        $this->need_instance = 0;
        $this->module_key = '6e5b62a07d77d917645c5c401d9a9e01';

        /**
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->l('Oct8ne');
        $this->description = $this->l('Oct8ne module connector');

        $this->confirmUninstall = $this->l('All data will be deleted ¿continue?');
    }

    /**
     * Instalacion
     * @param bool $full : completa o parcial
     * @return bool
     */
    public function install($full = true)
    {
        try {
            $correct = parent::install();
            if ($full) {
                //Base de datos
                include(dirname(__FILE__) . '/sql/install.php');
                $tryhtac = $this->setHtaccessRules();
                if (!$tryhtac) {
                    throw new Exception("htaccess not found");
                }
                //Configuración
                Configuration::updateValue(Oct8ne::$POSITION_LOAD, 1);
                Configuration::updateValue(Oct8ne::$URL_IMG_TYPE, 1);

            }
            //Ganchos
            $correct = $correct &&
                $this->registerHook('actionCartSave') &&
                $this->registerHook('displayFooter') &&
                $this->registerHook('displayHeader');

            return $correct;
        } catch (Exception $ex) {
            $this->logException($ex);
            return false;
        }
    }


    /**
     * Desinstalacion
     * @param bool $full : completa o parcial
     * @return bool
     */
    public function uninstall($full = true)
    {
        if ($full) {
            include(dirname(__FILE__) . '/sql/uninstall.php');
            $this->removeHtaccessRules();
            Configuration::deleteByName(self::$EMAIL_CK);
            Configuration::deleteByName(self::$API_TOKEN_CK);
            Configuration::deleteByName(self::$LICENSE_ID_CK);
            Configuration::deleteByName(self::$SEARCH_ENGINE);
            Configuration::deleteByName(self::$POSITION_LOAD);
            Configuration::deleteByName(self::$URL_IMG_TYPE);

        }

        return parent::uninstall();
    }

    /**
     * Resetear modulo
     * @return bool
     */
    public function reset()
    {
        if (!$this->uninstall(false)) {
            return false;
        }
        if (!$this->install(false)) {
            return false;
        }

        return true;
    }


    /**
     * Vista configuracion del modulo
     * @return mixed
     */
    public function getContent()
    {
        //Ejecutamos el postprocess
        $this->postProcess();

        $api_key = Configuration::get(self::$API_TOKEN_CK);

        //si no hay api key es que no estamos registrados por lo tanto llamamos a iniciar sesion
        if (empty($api_key)) {
            return $this->renderSettingsForm();

            //si hay api key es que estamos iniciados, mostramos una vista para cerrar sesión
        } else {
            return $this->renderLoggedForm() . $this->renderSearchEngineForm() . $this->renderPositionLoadForm();
        }
    }

    /**
     * Submit Llegada de formularios
     */
    public function postProcess()
    {
        try {
            //si el submit es de tipo login, hacemos las comprobaciones pertinentes
            if (((bool)Tools::isSubmit('login')) == true) {
                $email = trim(strip_tags(Tools::getValue(self::$EMAIL_CK)));
                $password = trim(strip_tags(Tools::getValue(self::$PASSWORD_CK)));
                $check = $this->checkOct8neLinkUp($email, $password);

                if (!empty($email) && !empty($password) && $check != false) {

                    //guardamos las variables de configuracion si to.do está correcto
                    Configuration::updateValue(self::$LICENSE_ID_CK, $check["license"]);
                    Configuration::updateValue(self::$API_TOKEN_CK, $check["token"]);
                    Configuration::updateValue(self::$EMAIL_CK, $email);
                    Configuration::updateValue(self::$SEARCH_ENGINE, 1);
                    Configuration::updateValue(self::$POSITION_LOAD, 1);
                    Configuration::updateValue(self::$URL_IMG_TYPE, 1);


                } else {
                    $error_msg = $this->l('Cannot login, please check your credentials');
                    $this->context->controller->errors[] = $error_msg;
                }

                //si el submit es de tipo logout, borramos la configuracion
            } elseif (((bool)Tools::isSubmit('logout')) == true) {

                Configuration::deleteByName(self::$EMAIL_CK);
                Configuration::deleteByName(self::$API_TOKEN_CK);
                Configuration::deleteByName(self::$LICENSE_ID_CK);
                Configuration::deleteByName(self::$SEARCH_ENGINE);
                Configuration::deleteByName(self::$POSITION_LOAD);
                Configuration::deleteByName(self::$URL_IMG_TYPE);

            } elseif (((bool)Tools::isSubmit('oct_search_engine_changed')) == true) {

                $engine = trim(strip_tags(Tools::getValue(self::$SEARCH_ENGINE)));
                Configuration::updateValue(self::$SEARCH_ENGINE, $engine);

            } elseif (((bool)Tools::isSubmit('oct_options_changed')) == true) {

                $position = trim(strip_tags(Tools::getValue(self::$POSITION_LOAD))); //posicion donde se cargara el js de oct8ne
                $url_img_type = trim(strip_tags(Tools::getValue(self::$URL_IMG_TYPE))); //image url type

                //update values
                Configuration::updateValue(self::$POSITION_LOAD, $position);
                Configuration::updateValue(self::$URL_IMG_TYPE, $url_img_type);
            }
        } catch (Exception $ex) {
            $this->context->controller->errors[] = $ex->getMessage();
        }
    }

    /**
     * Muestra el formulario de inicio de sesion
     * inicio sesion
     * @return mixed
     */
    private function renderSettingsForm()
    {
        //Esquleto del formulario
        $form_schema = array();
        //Configuración General
        $form_schema[] = $this->getSettingsFormSchema();

        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'login';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => array(self::$EMAIL_CK => Tools::getValue(self::$EMAIL_CK), self::$PASSWORD_CK => Tools::getValue(self::$PASSWORD_CK)), /* Add values for your inputs */
        );

        return $helper->generateForm($form_schema);
    }

    /**
     * Esquema del formulario de inicio de sesion
     * @return array
     */
    private function getSettingsFormSchema()
    {

        $id_lang = (int)$this->context->employee->id_lang;
        $lang = new Language($id_lang);
        $iso = $lang->locale;

        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Oct8ne Settings'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(


                    array(
                        'type' => 'text',
                        'name' => self::$EMAIL_CK,
                        'required' => true,
                        'label' => $this->l('Email'),
                        'class' => 'col-lg-4',
                        'placeholder' => $this->l('Your email...'),
                        'prefix' => "<i class='icon-envelope'></i>"

                    ),
                    array(
                        'type' => 'password',
                        'name' => self::$PASSWORD_CK,
                        'required' => true,
                        'label' => $this->l('Password'),
                        'placeholder' => $this->l('Your password'),
                        'class' => 'col-lg-6',
                    ),
                    array(
                        'type' => 'html',
                        'name' => 'help',
                        'html_content' => $this->l('You must fill out the fields with your user information from the admin panel on Oct8ne. If you still do not have an Oct8ne user name, you can create one') . " <a href='https://secure.oct8ne.com/SignUp/StepOne?lang={$iso}' target='_blank' >" . $this->l('here') . '</a>',
                    )
                ),
                'submit' => array(
                    'title' => $this->l('Login'),
                ),

            ),
        );
    }

    /**
     * Muestra el formulario de cerrar sesion
     * @return mixed
     */
    private function renderLoggedForm()
    {

        //Esquleto del formulario
        $form_schema = array();
        //Configuración General
        $form_schema[] = $this->getLoggedFormSchema();

        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'logout';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => array('email' => Configuration::get(self::$EMAIL_CK)), /* Add values for your inputs */
        );

        return $helper->generateForm($form_schema);
    }

    /**
     * Muestra el formulario de metodo de busqueda
     * @return mixed
     */
    private function renderSearchEngineForm()
    {
        //Obtenemos los motores de búsqueda
        $search_engines = $this->getDetectedSearchEngines();
        if (count($search_engines) <= 1) {
            return;
        }
        //Esquleto del formulario
        $form_schema = array();
        //Configuración General
        $form_schema[] = $this->getSearchEngineFormSchema($search_engines);

        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'oct_search_engine_changed';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => array(self::$SEARCH_ENGINE => Configuration::get(self::$SEARCH_ENGINE)), /* Add values for your inputs */
        );

        return $helper->generateForm($form_schema);
    }

    /**
     * Devuelve una lista de motores de búsqueda detectados
     * @return array
     */
    public function getDetectedSearchEngines()
    {
        //Cargamos la libreria para determina los tipos soportados
        $this->loadLibrary('OctSearchEngineType', 'search');
        $search_engines = array();
        //Por defecto
        $search_engines[OctSearchEngineType::Internal] = $this->l('Default prestashop search engine');
        //Doofinder
        if (Module::isEnabled('doofinder')) {

            $search_engines[OctSearchEngineType::Doofinder] = $this->l('Doofinder search engine');
        }

        return $search_engines;
    }

    /**
     * Esquema del formulario para cambiar el metodo de busqueda
     * @return array
     */
    private function getSearchEngineFormSchema($search_engines)
    {
        $options = array();
        //Añadimos cada motor detectado
        foreach ($search_engines as $id => $name) {
            $options[] = array(
                'id_option' => $id,
                'name' => $name
            );
        }

        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Oct8ne search engine'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'select',
                        'required' => true,
                        'label' => $this->l('Search engine'),
                        'name' => self::$SEARCH_ENGINE,
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        ))
                ),
                'submit' => array(
                    'title' => $this->l('Save changes'),
                    'icon' => 'process-icon-save'
                ),
            ),
        );
    }

    /**
     * Esquema del formulario cerrar sesion
     * @return array
     */
    private function getLoggedFormSchema()
    {
        $this->context->smarty->assign('oct_mail', Configuration::get(self::$EMAIL_CK));

        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Oct8ne info'),
                    'icon' => 'icon-cogs',
                ),
                'input' => array(
                    array(
                        'type' => 'html',
                        'required' => true,
                        'label' => $this->l('Active User: '),
                        'name' => 'email',
                        'html_content' => $this->display(__FILE__, 'views/templates/admin/mail.tpl')
                    )
                ),

                'submit' => array(
                    'title' => $this->l('Logout'),
                    'icon' => 'process-icon-close'
                ),
            ),
        );
    }

    /**
     * Muestra el formulario para cambiar la posicion donde se carga el codigo de Oct8ne
     * @return mixed
     */
    public function renderPositionLoadForm()
    {

        //Esquleto del formulario
        $form_schema = array();
        //Configuración General
        $form_schema[] = $this->getPositionLoadFormSchema();

        $helper = new HelperForm();

        $helper->show_toolbar = false;
        $helper->table = $this->table;
        $helper->module = $this;

        $helper->identifier = $this->identifier;
        $helper->submit_action = 'oct_options_changed';
        $helper->currentIndex = $this->context->link->getAdminLink('AdminModules', false) . '&configure=' . $this->name . '&tab_module=' . $this->tab . '&module_name=' . $this->name;
        $helper->token = Tools::getAdminTokenLite('AdminModules');

        $helper->tpl_vars = array(
            'fields_value' => array(self::$POSITION_LOAD => Configuration::get(self::$POSITION_LOAD), self::$URL_IMG_TYPE => Configuration::get(self::$URL_IMG_TYPE)), /* Add values for your inputs */
        );

        return $helper->generateForm($form_schema);
    }

    /**
     * Esquema del formulario para cambiar la posicion donde se carga el codigo de Oct8ne
     * @return array
     */
    private function getPositionLoadFormSchema()
    {

        $this->context->smarty->assign(self::$POSITION_LOAD, Configuration::get(self::$POSITION_LOAD));
        $this->context->smarty->assign(self::$URL_IMG_TYPE, Configuration::get(self::$URL_IMG_TYPE));

        $options = array();
        //Añadimos cada motor detectado
        $options[] = array(
            'id_option' => 1,
            'name' => $this->l("On Header")
        );

        $options[] = array(
            'id_option' => 2,
            'name' => $this->l("On Footer")
        );

        $options2 = array();
        //Añadimos cada motor detectado
        $options2[] = array(
            'id_option' => 1,
            'name' => $this->l("Standard")
        );

        $options2[] = array(
            'id_option' => 2,
            'name' => "Type 1"
        );

        return array(
            'form' => array(
                'legend' => array(
                    'title' => $this->l('Oct8ne options'),
                    'icon' => 'icon-cogs',
                ),

                'input' => array(
                    array(
                        'type' => 'select',
                        'required' => true,
                        'desc' => $this->l('You can choose the position to load Oct8ne scripts (On Footer or On Header) of the page'),
                        'label' => $this->l('Position'),
                        'name' => self::$POSITION_LOAD,
                        'options' => array(
                            'query' => $options,
                            'id' => 'id_option',
                            'name' => 'name'
                        )),
                    array(
                        'type' => 'select',
                        'required' => true,
                        'label' => $this->l('Url image type'),
                        'desc' => $this->l('Type 1 adds product id before image id. Useful in specific instances. Be careful, change this option only when necessary'),
                        'name' => self::$URL_IMG_TYPE,
                        'options' => array(
                            'query' => $options2,
                            'id' => 'id_option',
                            'name' => 'name'
                        ))
                ),
                'submit' => array(
                    'title' => $this->l('Save'),
                    'icon' => 'process-icon-save'
                ),
            ),
        );
    }

    /**
     * Conecta con la API REST de Oct8ne y comprueba los datos de usuario
     * @param $user
     * @param $pass
     * @return array|bool
     */
    private function checkOct8neLinkUp($user, $pass)
    {
        try {


            //peticion
            $url = 'https://backoffice.oct8ne.com/platformConnection/linkup';
            $data = array('email' => $user,
                'pass' => $pass,
                'platform' => 'prestashop',
                'urlDomain' => Context::getContext()->shop->domain_ssl,
                'statusPlatform' => $this->active == 1);

            $options = array(
                'http' => array(
                    'header' => "Content-Type: application/json;charset=UTF-8\r\n",
                    'method' => 'POST',
                    'content' => Tools::jsonEncode($data)
                )
            );
            $context = stream_context_create($options);
            $result = self::OctFileGetContents($url, false, $context);
            $result = Tools::jsonDecode($result);


            if (isset($result)) {
                //si se devuelve una licencia y token correctos se devuelve
                $license = $result->LicenseId;
                $token = $result->ApiToken;
                //$msg = $result->Message;

                if ($license != null && $token != null) {
                    return array("license" => $license, "token" => $token);
                } else {
                    return false;
                }
            } else {
                return false;
            }
        } catch (Exception $ex) {
            return false;
        }
    }

    /**
     * File get contents personalizado
     * @param $url
     * @param bool $use_include_path
     * @param null $stream_context
     * @param int $curl_timeout
     * @return bool|mixed|string
     */
    private static function octFileGetContents($url, $use_include_path = false, $stream_context = null, $curl_timeout = 5)
    {
        if ($stream_context == null && preg_match('/^https?:\/\//', $url)) {
            $stream_context = @stream_context_create(array('http' => array('timeout' => $curl_timeout)));
        }
		$return = false;
        if (in_array(ini_get('allow_url_fopen'), array('On', 'on', '1')) || !preg_match('/^https?:\/\//', $url)) {
            $meth = 'file_get_contents';
            $return = @$meth($url, $use_include_path, $stream_context);
        } 
		if ($return === false && function_exists('curl_init')) {
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 5);
            curl_setopt($curl, CURLOPT_TIMEOUT, $curl_timeout);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl, CURLOPT_HTTPHEADER, array('Content-Type: application/json;charset=UTF-8\r\n'));
            if ($stream_context != null) {
                $opts = stream_context_get_options($stream_context);
                if (isset($opts['http']['method']) && Tools::strtolower($opts['http']['method']) == 'post') {
                    curl_setopt($curl, CURLOPT_POST, true);
                    if (isset($opts['http']['content'])) {
                        curl_setopt($curl, CURLOPT_POSTFIELDS, $opts['http']['content']);
                    }
                }
            }
            $content = curl_exec($curl);
            curl_close($curl);
            $return = $content;
		}
		return $return;
    }


    /**
     * Hook footer
     */
    public function hookDisplayFooter($params)
    {

        $position = Configuration::get(self::$POSITION_LOAD);

        if ($position == 2) {
            return $this->octDisplayCode();
        }

        return '';

    }

    /**
     * Hook header
     */
    public function hookDisplayHeader($params)
    {

        $position = Configuration::get(self::$POSITION_LOAD);

        if ($position == 1) {
            return $this->octDisplayCode();
        }

        return '';
    }

    /**
     * Codigo JS para el footer, solo disponible si existe licencia de oct8ne
     * @param $params
     * @return bool
     */
    public function octDisplayCode()
    {
        $license = Configuration::get(self::$LICENSE_ID_CK);
        if (!empty($license)) {

            $baseUrl = rtrim($this->context->shop->getBaseURL(), '/');

            $loginUrl = $this->context->link->getPageLink('authentication', true);

            $checkoutSuccessUrl = "";
            $aux = Configuration::get('PS_ORDER_PROCESS_TYPE');

            if ($aux == 0) {
                $checkoutSuccessUrl = $this->context->link->getPageLink('order', true);
            } elseif ($aux == 1) {
                $checkoutSuccessUrl = $this->context->link->getPageLink('order-opc', true);
            }

            $orderConfirmationUrl = $this->context->link->getPageLink('order-confirmation', true);

            $locale = $this->context->language->language_code;

            if (strpos($locale, '-') == true) {
                $aux = explode('-', $locale);
                $aux[1] = Tools::strtoupper($aux[1]);
                $locale = implode('-', $aux);
            }
            $currencyCode = $this->context->currency->iso_code;

            ///Comprobar si estamos es una pagina de producto
            $controller = $this->context->controller;
            if (isset($controller->php_self)) {
                if ($controller->php_self == 'product') {
                    $product = new Product(Tools::getValue("id_product"), false, Context::getContext()->language->id);

                    if (Validate::isLoadedObject($product)) {
                        $id = $product->id;
                        $image = Product::getCover($id)['id_image'];


                        $thumbnail = Context::getContext()->link->getImageLink($product->link_rewrite, $image);


                        $this->context->smarty->assign(array(
                            "oct8ne_product_id" => $id,
                            "oct8ne_product_thumb" => $thumbnail,

                        ));
                    }
                }
            }

            $baseUrl = Oct8ne::removeHttProtocol($baseUrl);
            $loginUrl = Oct8ne::removeHttProtocol($loginUrl);
            $checkoutSuccessUrl = Oct8ne::removeHttProtocol($checkoutSuccessUrl);
            $orderConfirmationUrl = Oct8ne::removeHttProtocol($orderConfirmationUrl);


            $this->context->smarty->assign(array(
                "oct8neLicense" => $license,
                "oct8neBaseUrl" => $baseUrl,
                "oct8neLoginUrl" => $loginUrl,
                "oct8neCheckOutSuccessUrl" => $checkoutSuccessUrl,
                "oct8neLocale" => $locale,
                "oct8neCurrencyCode" => $currencyCode,
                "oct8neorderConfirmationUrl" => $orderConfirmationUrl

            ));

            return $this->display(__FILE__, 'hookDisplayOctCode.tpl');

        } else {
            return false;
        }
    }

    /**
     * Comprueba si hay cookies de octone para el carrito
     * @param $params
     *
     */
    public function hookActionCartSave($params)
    {
        try {
            //Comprobamos la existencia de la cookie
            $cookie = filter_input(INPUT_COOKIE, 'oct8ne-session');

            if (!empty($cookie)) {
                //Comprobamos que prestashop nos haya enviado el carro
                if (isset($params['cart'])) {
                    $this->loadClass('Oct8neHistory');
                    $history = new Oct8neHistory();
                    //Comprobamos que no exista ya este carro
                    $exist = $history->existsIdCart($params['cart']->id);
                    if (!$exist) {

                        if (Validate::isLoadedObject($params['cart'])) {

                            $history->cart_id = $params['cart']->id;
                            $history->session_id = $cookie;
                            $history->save();
                        }
                    }
                }
            }
        } catch (Exception $ex) {
            $this->logException($ex);
        }
    }

    /**
     * Regla que permite a Oct8ne Conectar con nuestro controlador
     */
    public function setHtaccessRules()
    {
        $path = _PS_ROOT_DIR_ . '/.htaccess';

        $touch = touch($path);

        if ($touch) {
            if (is_writable($path)) {
                $rule = $this->getHtaccessRule();
                $rule .= Tools::file_get_contents($path);
                file_put_contents($path, $rule, LOCK_EX);
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    /**
     * Eliminar regla Oct8ne
     */
    public function removeHtaccessRules()
    {
        $path = _PS_ROOT_DIR_ . '/.htaccess';
        $fp = Tools::file_get_contents($path);
        $start = strpos($fp, '#Oct8ne');
        $end = strripos($fp, '#End_Oct8ne');
        if ($start !== false && $end !== false && $end > $start) {
            $to_delete = Tools::substr($fp, $start, ($end + Tools::strlen('#End_Oct8ne') - $start));
			$fp = trim(str_replace($to_delete, '', $fp));
            file_put_contents($path, $fp, LOCK_EX);
        }
    }

    /**
     * @return string
     * Regla Htaccess para Oct8ne
     */
    private function getHtaccessRule()
    {
        return '#Oct8ne
                <IfModule mod_rewrite.c>
                RewriteEngine on
                RewriteRule ^oct8ne/frame/([a-zA-Z]+)$ index.php?fc=module&module=oct8ne&controller=oct8neconnector&octmethod=$1&%{QUERY_STRING} [QSA,L]
                </IfModule>
                #End_Oct8ne
                ';
    }
}
