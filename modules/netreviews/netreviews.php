<?php
/**
 * 2012-2017 NetReviews
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
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * avisverifiesApi.php file used to execute query from AvisVerifies plateform
 *
 *  @author    NetReviews SAS <contact@avis-verifies.com>
 *  @copyright 2017 NetReviews SAS
 *  @version   Release: $Revision: 7.4.2
 *  @license   NetReviews
 *  @date      16/10/2017
 *  @category  api
 *  International Registered Trademark & Property of NetReviews SAS
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

$specifiqueModel = file_exists(_PS_MODULE_DIR_."netreviews/models/NetReviewsModel-specifique.php");
if ($specifiqueModel) {
    require_once _PS_MODULE_DIR_.'netreviews/models/NetReviewsModel-specifique.php';
} else {
    require_once _PS_MODULE_DIR_.'netreviews/models/NetReviewsModel.php';
}

class NetReviews extends Module
{
    public $_html = null;
    public $iso_lang = null;
    public $id_lang = null;
    public $group_name = null;
    public $stats_product;

    public function __construct()
    {
        $this->name = 'netreviews';
        $this->tab = 'advertising_marketing';
        $this->version = '7.4.2';
        $this->author = 'NetReviews';
        $this->need_instance = 0;
        $this->bootstrap = true; 
        parent::__construct();
        $this->displayName = $this->l('Verified Reviews');
        $this->description = $this->l('Collect service and product reviews with Verified Reviews. Display reviews on your shop and win the trust of your visitors, to increase your revenue.');
        $this->module_key = 'd63d28acbac0a249ec17b6394ac5a841';
        if (self::isInstalled($this->name)) {
            $this->id_lang = (int)Configuration::get('PS_LANG_DEFAULT');
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
        }
        $this->confirmUninstall = sprintf($this->l('Are you sure you want to uninstall %s module?'), $this->displayName);
        $this->initContext();
    }

    /**
     * Don't forget to create update methods if needed:
     * http://doc.prestashop.com/display/PS16/Enabling+the+Auto-Update
     */
    public function install()
    {
        // Create PS configuration variable
        Configuration::updateValue('AV_IDWEBSITE', '');
        Configuration::updateValue('AV_CLESECRETE', '');
        Configuration::updateValue('AV_LIGHTWIDGET', '1'); //afficher étoiles simple
        Configuration::updateValue('AV_MULTILINGUE', '0');
        Configuration::updateValue('AV_MULTISITE', '');
        Configuration::updateValue('AV_PROCESSINIT', '');
        Configuration::updateValue('AV_ORDERSTATESCHOOSEN', '');
        Configuration::updateValue('AV_DELAY', '');
        Configuration::updateValue('AV_DELAY_PRODUIT', '0');
        Configuration::updateValue('AV_GETPRODREVIEWS', '');
        Configuration::updateValue('AV_DISPLAYPRODREVIEWS', '');
        Configuration::updateValue('AV_CSVFILENAME', 'Export_NetReviews_01-01-1970-default.csv');
        Configuration::updateValue('AV_SCRIPTFLOAT', '');
        Configuration::updateValue('AV_SCRIPTFLOAT_ALLOWED', '');
        Configuration::updateValue('AV_SCRIPTFIXE', '');
        Configuration::updateValue('AV_SCRIPTFIXE_ALLOWED', '');
        Configuration::updateValue('AV_URLCERTIFICAT', '');
        Configuration::updateValue('AV_FORBIDDEN_EMAIL', '');
        Configuration::updateValue('AV_CODE_LANG', '');
        Configuration::updateValue('AV_DISPLAYGOOGLESNIPPETPRODUIT', '2'); //product rich snippet position by defaut
        Configuration::updateValue('AV_DISPLAYGOOGLESNIPPETPRODUITLI', '0');
        Configuration::updateValue('AV_DISPLAYGOOGLESNIPPETSITE', '0');
        Configuration::updateValue('AV_NBOFREVIEWS', '10');
        Configuration::updateValue('AV_NBOPRODUCTS', '');
         if (version_compare(_PS_VERSION_, '1.7', '>')) {
            Configuration::updateValue('AV_EXTRA_OPTION', '2'); //hookDisplayProductButtons
        }else{
            Configuration::updateValue('AV_EXTRA_OPTION', '0');  //hookExtraright     
        }
        Configuration::updateValue('AV_ORDER_UPDATE', ''); 
        Configuration::updateValue('AV_DISPLAYSTARPLIST', '0');

        if (!($query = include dirname(__FILE__).'/sql/install.php')) {
            $this->context->controller->errors[] = sprintf($this->l('SQL ERROR : %s | Query can\'t be executed. Maybe, check SQL user permissions.'), $query);
        }


        return parent::install()
            && $this->registerHook('displayProductTabContent')
            && $this->registerHook('displayProductTab')
            && $this->registerHook('displayRightColumnProduct')
            && $this->registerHook('displayLeftColumnProduct')
            && $this->registerHook('displayProductPriceBlock')
            && $this->registerHook('displayProductButtons')
            && $this->registerHook('displayBeforeBodyClosingTag')
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayFooter')
            && $this->registerHook('displayFooterProduct')
            && $this->registerHook('displayRightColumn')
            && $this->registerHook('displayLeftColumn')
            && $this->registerHook('displayProductListReviews')
            && $this->registerHook('displayProductExtraContent')
            && $this->registerHook('Extra_netreviews')
            && $this->registerHook('actionOrderStatusPostUpdate') //double check lost orders
            && $this->registerHook('actionValidateOrder');
    }

    public function uninstall()
    {
        //Uninstall NetReviews configurations variable
        $sql = 'SELECT name FROM '._DB_PREFIX_."configuration where name like 'AV_%'";
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                Configuration::deleteByName($row['name']);
            }
        }

        //Uninstall NetReviews Database
        if (!($query = include dirname(__FILE__).'/sql/uninstall.php')) {
            $this->context->controller->errors[] = sprintf($this->l('SQL ERROR : %s | Query can\'t be executed. Maybe, check SQL user permissions.'), $query);
        }

        return parent::uninstall()
            && $this->unregisterHook('displayProductTabContent')
            && $this->unregisterHook('displayProductTab')
            && $this->unregisterHook('displayRightColumnProduct')
            && $this->unregisterHook('displayLeftColumnProduct')
            && $this->unregisterHook('displayProductPriceBlock')
            && $this->unregisterHook('displayProductButtons')
            && $this->unregisterHook('displayFooterBefore')
            && $this->unregisterHook('displayHeader')
            && $this->unregisterHook('displayFooter')
            && $this->unregisterHook('displayFooterProduct')
            && $this->unregisterHook('displayRightColumn')
            && $this->unregisterHook('displayLeftColumn')
            && $this->unregisterHook('displayProductListReviews')
            && $this->unregisterHook('displayProductExtraContent')
            && $this->unregisterHook('Extra_netreviews')
            && $this->unregisterHook('actionOrderStatusPostUpdate')
            && $this->unregisterHook('actionValidateOrder');
    }

    /**
     * Load the configuration form
     */
    public function getContent()
    {
        if (version_compare(_PS_VERSION_, '1.5', '<')) {
            $oldversion = true;
            Tools::addCSS(($this->_path).'views/css/avisverifies-style-back.css', 'all');
            Tools::addCSS(($this->_path).'views/css/avisverifies-style-back-old.css', 'all');
        } elseif  (version_compare(_PS_VERSION_, '1.6', '>')){
            $oldversion = false;
            $this->context->controller->addCSS(($this->_path).'views/css/avisverifies-style-back.css', 'all');
        }else{
            $oldversion = true;
            $this->context->controller->addCSS(($this->_path).'views/css/avisverifies-style-back-old.css', 'all');
            $this->context->controller->addCSS(($this->_path).'views/css/avisverifies-style-back.css', 'all');
        }

        if (!empty($_POST)) {
            $this->postProcess();
        }

        if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1 &&
            (Shop::getContext() == Shop::CONTEXT_ALL || Shop::getContext() == Shop::CONTEXT_GROUP)) {
            $this->_html .= $this->displayError($this->l('Multistore feature is enabled. Please choose above the store to configure.'));
            return $this->_html;
        }

        $o_av = new NetReviewsModel();
        $nb_reviews = $o_av->getTotalReviews();
        $nb_reviews_average = $o_av->getTotalReviewsAverage();
        $nb_orders = $o_av->getTotalOrders();
        $current_avisverifies_idwebsite = array();
        $current_avisverifies_clesecrete = array();
        $order_statut_list = OrderState::getOrderStates((int)Configuration::get('PS_LANG_DEFAULT'));
        $multisite = Configuration::get('AV_MULTISITE');
        if (!empty($multisite)) {
            $idshop = $this->context->shop->getContextShopID();
            $idshop_conf = true;
        }else{
            $idshop = null;  // if multishop but only one shop enabled or non multishop 
            $idshop_conf = false;
        }
        $current_avisverifies_idwebsite['root'] = Configuration::get('AV_IDWEBSITE', null, null, $idshop);
        $current_avisverifies_clesecrete['root'] = Configuration::get('AV_CLESECRETE', null, null, $idshop);
        $languages = Language::getLanguages(true);

        foreach ($languages as $lang) {
            $current_avisverifies_idwebsite[$lang['iso_code']] = "";
            $current_avisverifies_clesecrete[$lang['iso_code']] = "";
            $language_group_name = $this->getIdConfigurationGroup($lang['iso_code']);           

            if (!Configuration::get('AV_IDWEBSITE'.$language_group_name, null, null, $idshop)) {
                Configuration::updateValue('AV_IDWEBSITE'.$language_group_name, '', false, null, $idshop);
            }elseif($language_group_name){
                $current_avisverifies_idwebsite[$lang['iso_code']] = Configuration::get('AV_IDWEBSITE'.$language_group_name, null, null, $idshop);
            }

            if (!Configuration::get('AV_CLESECRETE'.$language_group_name, null, null, $idshop)) {
                Configuration::updateValue('AV_CLESECRETE'.$language_group_name, '', false, null, $idshop);
            }elseif($language_group_name){
                $current_avisverifies_clesecrete[$lang['iso_code']] =  Configuration::get('AV_CLESECRETE'.$language_group_name, null, null, $idshop);
            }
        }
        $this->context->smarty->assign(array(
            'base_url' => __PS_BASE_URI__ ,
            'current_avisverifies_urlapi' => Configuration::get('AV_URLAPI', null, null, $idshop),
            'current_lightwidget_checked' => Configuration::get('AV_LIGHTWIDGET', null, null, $idshop),
            'current_multilingue_checked' => Configuration::get('AV_MULTILINGUE', null, null, $idshop),
            'current_starproductlist_checked' => Configuration::get('AV_DISPLAYSTARPLIST', null, null, $idshop),
            'current_snippets_produit_checked' => Configuration::get('AV_DISPLAYGOOGLESNIPPETPRODUIT', null, null, $idshop),
            'current_snippets_liste_produit_checked' => Configuration::get('AV_DISPLAYGOOGLESNIPPETPRODUITLI', null, null, $idshop),
            'current_snippets_site_checked' => Configuration::get('AV_DISPLAYGOOGLESNIPPETSITE', null, null, $idshop),
            'avisverifies_nb_reviews' => Configuration::get('AV_NBOFREVIEWS', null, null, $idshop),
            'avisverifies_nb_products' => Configuration::get('AV_NBOPRODUCTS', null, null, $idshop),
            'avisverifies_extra_option' => Configuration::get('AV_EXTRA_OPTION', null, null, $idshop),
            'avisverifies_orders_doublecheck' => Configuration::get('AV_ORDER_UPDATE', null, null, $idshop),
            'current_avisverifies_idwebsite' => $current_avisverifies_idwebsite,
            'current_avisverifies_clesecrete' => $current_avisverifies_clesecrete,
            'version' => $this->version,
            'order_statut_list' => $order_statut_list,
            'languages' => $languages,
            'debug_nb_reviews' => $nb_reviews['nb_reviews'],
            'debug_nb_reviews_average' => $nb_reviews_average['nb_reviews_average'],
            'debug_nb_orders_flagged' => $nb_orders['flagged']['nb'],
            'debug_nb_orders_not_flagged' => $nb_orders['not_flagged']['nb'],
            'debug_nb_orders_all' => $nb_orders['all']['nb'],
            'av_path' => $this->_path,
            'oldversion' => $oldversion,
            'shop_name' => $this->context->shop->name,
            'url_back' => (($this->context->link->getAdminLink('AdminModules').'&configure='.$this->name.'&tab_module='.
                $this->tab.'&conf=4&module_name='.$this->name))
        ));
        $this->_html .= $this->display(__FILE__, '/views/templates/hook/avisverifies-backoffice.tpl');
        return $this->_html;
    }

    /**
     * Save configuration form.
     */
    private function postProcess()
    {
        $av_idshop =  null;
         if (version_compare(_PS_VERSION_, '1.5', '>=') && Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1) {
             $av_idshop =  ($this->context->shop->getContextShopID())?$this->context->shop->getContextShopID():null;
         }
        if (Tools::isSubmit('submit_export')) {
            try {
                $o_av = new NetReviewsModel;
                    $header_colums = 'id_order;order_amount;email;firstname;lastname;date_order;delay;id_product;category;description;ean13;upc;mpn;brand;product_url;image_product_url;order_state_id;order_state;iso_lang;id_shop'."\r\n";
                    $return_export = $o_av->export($header_colums, $av_idshop);
                if (file_exists($return_export[2])) {
                    $this->_html .= $this->displayConfirmation(sprintf($this->l('%s orders have been exported.'), $return_export[1]).'<a href="../modules/netreviews/Export_NetReviews_'.$return_export[0].'"> '.$this->l('Click here to download the file').'</a>');
                } else {
                    $this->_html .= $this->displayError($this->l('Writing on the server is not allowed. Please assign write permissions to the folder netreviews').$return_export[2]);
                }
            } catch (Exception $e) {
                $this->_html .= $this->displayError($e->getMessage());
            }
        }

        if (Tools::isSubmit('submit_configuration')) {
                 Configuration::updateValue('AV_MULTILINGUE', Tools::getValue('avisverifies_multilingue'), false, null, $av_idshop);
                    Configuration::updateValue('AV_IDWEBSITE', Tools::getValue('avisverifies_idwebsite'), false, null, $av_idshop);
                    Configuration::updateValue('AV_CLESECRETE', Tools::getValue('avisverifies_clesecrete'), false, null, $av_idshop);
                    Configuration::updateValue('AV_MULTISITE',$av_idshop); 
                if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
                    $sql = '
                    SELECT name FROM '._DB_PREFIX_."configuration
                    where (name like 'AV_GROUP_CONF_%'
                    OR name like 'AV_IDWEBSITE_%'
                    OR name like 'AV_CLESECRETE_%')
                    AND id_shop = '".$av_idshop."'
                    ";
                       $idshop_conf = true; 
                    if ($results = Db::getInstance()->ExecuteS($sql)) {
                        foreach ($results as $row) {
                            Configuration::deleteFromContext($row['name']);
                        }
                    }else{
                        $idshop_conf = false; // if multishop but only one shop enabled or non multishop
                         $sql_without_idshop = '
                            SELECT name FROM '._DB_PREFIX_."configuration
                            where (name like 'AV_GROUP_CONF_%'
                            OR name like 'AV_IDWEBSITE_%'
                            OR name like 'AV_CLESECRETE_%')";  
                            if ($results = Db::getInstance()->ExecuteS($sql_without_idshop)) {
                                foreach ($results as $row) {
                                    Configuration::deleteFromContext($row['name']);
                                }
                            }
                    }
                    Configuration::updateValue('AV_MULTISITE',$idshop_conf); // in case that it's not multishop while configurated as multishop
                    $languages = Language::getLanguages(true);
                    $this->setIdConfigurationGroup($languages,$idshop_conf);
                }
        }
        
        if (Tools::isSubmit('submit_advanced')) {
                Configuration::updateValue('AV_LIGHTWIDGET', Tools::getValue('avisverifies_lightwidget'), false, null, $av_idshop);
                Configuration::updateValue('AV_DISPLAYGOOGLESNIPPETPRODUIT', Tools::getValue('netreviews_snippets_produit'), false, null, $av_idshop);
                Configuration::updateValue('AV_DISPLAYGOOGLESNIPPETSITE', Tools::getValue('netreviews_snippets_site'), false, null, $av_idshop);
                Configuration::updateValue('AV_NBOFREVIEWS', Tools::getValue('avisverifies_nb_reviews'), false, null, $av_idshop);
                Configuration::updateValue('AV_NBOPRODUCTS', Tools::getValue('avisverifies_nb_products'), false, null, $av_idshop);
                Configuration::updateValue('AV_EXTRA_OPTION', Tools::getValue('avisverifies_extra_option'), false, null, $av_idshop);
                Configuration::updateValue('AV_ORDER_UPDATE', Tools::getValue('avisverifies_orders_doublecheck'), false, null, $av_idshop);
                Configuration::updateValue('AV_DISPLAYSTARPLIST', Tools::getValue('avisverifies_star_productlist'), false, null, $av_idshop);
              
                Configuration::updateValue('AV_DISPLAYGOOGLESNIPPETPRODUITLI', Tools::getValue('netreviews_snippets_liste_produit'), false, null, $av_idshop);
        }

   
        if (Tools::isSubmit('submit_purge')) {
            $query_id_shop = "";
            if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1) {
                $query_id_shop = ' AND oav.id_shop = '.(int)$av_idshop;
            }

            $query = '  SELECT oav.id_order, o.date_add as date_order,o.id_customer
                        FROM '._DB_PREFIX_.'av_orders oav
                        LEFT JOIN '._DB_PREFIX_.'orders o
                        ON oav.id_order = o.id_order
                        LEFT JOIN '._DB_PREFIX_.'order_history oh
                        ON oh.id_order = o.id_order
                        WHERE (oav.flag_get IS NULL OR oav.flag_get = 0)'
                .$query_id_shop;

            $orders_list = Db::getInstance()->ExecuteS($query);
            if (!empty($orders_list)) {
                foreach ($orders_list as $order) { /* Set orders as getted */
                    Db::getInstance()->Execute('UPDATE '._DB_PREFIX_.'av_orders
                                                SET horodate_get = "'.time().'", flag_get = 1
                                                WHERE id_order = '.(int)$order['id_order']);
                }
                $this->_html .= $this->displayConfirmation(sprintf($this->l('The orders has been purged for %s'), $this->context->shop->name));
            } else {
                $this->_html .= $this->displayError(sprintf($this->l('No orders to purged for %s'), $this->context->shop->name));
            }
        }
    }

    /**
     * Return the widget flottant code to the hook header in front office if configurated
     *
     * Case 1: Return widget flottant code if configurated
     * Case 2: Return '' if not configurated
     *
     * @return javascript string in hook header
     */
    public function hookDisplayHeader()
    {
       $multisite = Configuration::get('AV_MULTISITE');
       $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
       if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }
        $widget_flottant_code = '';

        $this->context->controller->addCSS(($this->_path).'views/css/avisverifies-style-front.css', 'all');

        $specifiqueCss = file_exists(_PS_MODULE_DIR_."netreviews/views/css/avisverifies-style-front-specifique.css");
        if ($specifiqueCss) {
            $this->context->controller->addCSS(($this->_path).'views/css/avisverifies-style-front-specifique.css', 'all');
        }
        $this->context->controller->addJS(($this->_path).'views/js/avisverifies.js', 'all');

        $specifiqueJs = file_exists(_PS_MODULE_DIR_."views/js/avisverifies-specifique.js");
        if ($specifiqueJs) {
            $this->context->controller->addJS(($this->_path).'views/js/avisverifies-specifique.js', 'all');
        }

        $avisverifies_scriptfloat_allowed = Configuration::get('AV_SCRIPTFLOAT_ALLOWED'.$this->group_name, null, null, $av_idshop);
        if (Configuration::get('AV_SCRIPTFLOAT'.$this->group_name, null, null, $av_idshop)) {
            $widget_flottant_code .= "\n".Tools::stripslashes(html_entity_decode(Configuration::get('AV_SCRIPTFLOAT'.$this->group_name, null, null, $av_idshop)));
        }

        if ((strpos( strtolower($widget_flottant_code),'null') != true || strlen($widget_flottant_code) > 10) && $avisverifies_scriptfloat_allowed === "yes") {
            // display when it dosen't contain null and lenth > 10 and enabled
            return $widget_flottant_code;
        } 

    }

    /**
     * @param $params
     * @return string
     */
    

    public function hookDisplayProductListReviews($params)
    {
        if('index' != $this->context->controller->php_self){ //category stars won't display on homepage to avoid homepage caches
           $multisite = Configuration::get('AV_MULTISITE');
           $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
           if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
                $this->id_lang = $this->context->language->id;
                $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
                $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
            }

            $id_product = (int)$params['product']['id_product'];

            $avisverifies_display_stars = Configuration::get('AV_DISPLAYSTARPLIST', null, null, $av_idshop);

            if (!isset($id_product) || empty($id_product) || !isset($avisverifies_display_stars) || empty($avisverifies_display_stars)) {
                return '';
            }
            $o_av = new NetReviewsModel();

            $stats_product = (!isset($this->stats_product) || empty($this->stats_product)) ? $o_av->getStatsProduct($id_product, $this->group_name, $av_idshop) : $this->stats_product;

            if ($stats_product['nb_reviews'] == 0) {
                return '';
            }
            $percent = round($stats_product['rate'] * 20) ;
            $lang_id = (int)$this->context->language->id;
            if (empty($lang_id)) {
                $lang_id = 1;
            }
            $widgetlight = Configuration::get('AV_LIGHTWIDGET', null, null, $av_idshop);
            $product = new Product((int)$id_product);
            $this->context->smarty->assign(array(
                'av_nb_reviews' => $stats_product['nb_reviews'],
                'av_rate' =>  $stats_product['rate'],
                'av_rate_percent' =>  ($percent) ? $percent : 100,
                'link_product' => $params['product']['link'],
                'average_rate' => round($stats_product['rate'], 1),
                'widgetlight' =>  !empty($widgetlight)? $widgetlight: false,
                'product_name' => $this->getProductName($id_product, $lang_id),
                'product_description' => $product->description_short[$lang_id],
            ));

            $tpl = 'avisverifies-categorystars';
            return $this->displayTemplate($tpl);
       }
    }

  
    /**
     * Integration stars on category page
     *
     * @param $params
     * @return string
     */
    public function hookDisplayProductPriceBlock($params)
    {
        if (version_compare(_PS_VERSION_, '1.7', '>')) {
            if ($params['type'] == "before_price") {
                return $this->hookDisplayProductListReviews($params);
            }
        }
    }

    public function hookDisplayFooterProduct($params)
    {
       $multisite = Configuration::get('AV_MULTISITE');
       $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
       if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }
        if(Configuration::get('AV_DISPLAYGOOGLESNIPPETSITE', null, null, $av_idshop) == '1' && Configuration::get('AV_DISPLAYGOOGLESNIPPETPRODUIT', null, null, $av_idshop) == '1'){ //rich snippets actived
                /* Position 2 : Extraright - AggregateRating (Default)
                   Position 3 : Extraright - Product
                   Position 1 : Footer - Product
                   Position 4 : Tabcontent - AggregateRating
                   Position 5 : Tabcontent - Product
                   */
            $rs_choice = Configuration::get('AV_DISPLAYGOOGLESNIPPETPRODUITLI', null, null, $av_idshop); 
               /* AV_DISPLAYGOOGLESNIPPETPRODUITLI 
            * 0 disabled
            * 1 Microdata
            * 2 JSON-LD (product footer + category + site)
             */
            $id_product = (int)Tools::getValue('id_product');
            $o_av = new NetReviewsModel();
            $stats_product = (!isset($this->stats_product) || empty($this->stats_product)) ?
                $o_av->getStatsProduct($id_product, $this->group_name, $av_idshop) :
                $this->stats_product;
           
            if ($stats_product['nb_reviews'] != 0) {
            $lang_id = (int)$this->context->language->id;
            if (empty($lang_id)) {
                $lang_id = 1;
            }
            $product = new Product((int)$id_product);
            $link = new LinkCore();
            $a_image = Image::getCover($id_product);
            //rich snippets information
            $product_description = strip_tags($product->description_short[$lang_id]);
            $array_url = NetReviewsModel::getUrlsProduct($product->id);       
            $name_product =  $product->name;
            $url_page = $array_url['url_product'];
            $url_image = $array_url['url_image_product'];
            $sku = $product->reference;
            $mpn = $product->supplier_reference;
            $gtin_upc = $product->upc;
            $gtin_ean = $product->ean13;
            $this->context->smarty->assign(array(
                'count_reviews' => $stats_product['nb_reviews'],
                'average_rate' => round($stats_product['rate'], 1),
                'average_rate_percent' => $stats_product['rate'] * 20,
                'product_id' =>  $id_product,
                'product_name' => $this->getProductName($id_product, $lang_id),
                'product_description' => strip_tags($product->description_short[$lang_id]),
                'product_price' => $product->getPrice(true, null, 2),
                'product_quantity' => $product->quantity,
                'product_url' =>  !empty($url_page)? $url_page: false,
                'url_image' =>  !empty($url_image)? $url_image: false,
                'sku' =>  !empty($sku)? $sku: false,
                'mpn' =>  !empty($mpn)? $mpn: false,
                'gtin_upc' =>  !empty($gtin_upc)? $gtin_upc: false,
                'gtin_ean' =>  !empty($gtin_ean)? $gtin_ean: false,
                'rs_choice'=>$rs_choice 
            ));
            $tpl = 'footer_av';
            return $this->displayTemplate($tpl);
            }
        }
    }

    /**
     * Return the rich snippet code to the hook footer in front office if configurated
     * netreviews_snippets_produit
     * Case 1: footer
     * Case 2: extra
     * Case 2: tab content
     *
     * @return tpl string in hook footer
     */
    public function hookDisplayFooter()
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }

        $id_product = (int)Tools::getValue('id_product');

        if (Configuration::get('AV_DISPLAYGOOGLESNIPPETSITE', null, null, $av_idshop) == "1") { //if rich snippets are enabled
            /*
            AV_DISPLAYGOOGLESNIPPETPRODUITLI 
            * 0 disabled
            * 1 Microdata
            * 2 JSON-LD (product footer + category + site)
             */
            $rs_choice = Configuration::get('AV_DISPLAYGOOGLESNIPPETPRODUITLI', null, null, $av_idshop); 
            if ($this->context->controller->php_self == 'category') {
                // find the list of the id in a category
                $id_category=(int)Tools::getValue('id_category');
                $cat = new Category($id_category, $this->context->language->id);
                $nom_category=$cat->name;

                $sql = 'SELECT * FROM '._DB_PREFIX_.'category_product where id_category="'.$id_category.'"';
                $results = Db::getInstance()->ExecuteS($sql);
                // predefine the stats of the reviews, contains the number and the total of the rates
                $stats_product = array('nb_reviews'=>0,'somme'=>0);
                foreach ($results as $row) {

                    $id_product=(int)$row['id_product'];
                    $o_av = new NetReviewsModel();

                    $reviews = $o_av->getProductReviews($id_product, $this->group_name, $av_idshop, false, 0);

                    foreach ($reviews as $review) {
                        // calculate the number of review and the total of the rates
                        $stats_product['nb_reviews']++;
                        $stats_product['somme'] = $stats_product['somme'] + $review['rate'];
                    }
                }
                // calcul de la moyen
                if ( $stats_product['nb_reviews'] > 0) {
                    $stats_product['rate'] = $stats_product['somme'] / $stats_product['nb_reviews'];

                    $this->context->smarty->assign(array(
                        'count_reviews' => $stats_product['nb_reviews'],
                        'average_rate' => round($stats_product['rate'], 1),
                        'av_rate_percent' => ($stats_product['rate']*20),
                        'nom_category'=>$nom_category,
                        'page_name'=>'category',
                        'rs_choice'=>$rs_choice
                    ));

                    $tpl = 'avisverifies-category-snippets';
                    return $this->displayTemplate($tpl);
                 }
            } else if (empty($id_product)) { //the other pages other than category page nor product page
                    $rate_site = Configuration::get('AV_RATE_SITE', null, null, $av_idshop);
                    $nb_site = Configuration::get('AV_AVIS_SITE', null, null, $av_idshop);
                    $horodate = Configuration::get('AV_HORODATE_LASTGET', null, null, $av_idshop);
                    $av_idwebsite = Configuration::get('AV_IDWEBSITE'.$this->group_name, null, null, $av_idshop);
                    $av_urlcertificat = Configuration::get('AV_URLCERTIFICAT'.$this->group_name, null, null, $av_idshop);
                    $name_site = Configuration::get('PS_SHOP_DOMAIN');
                    $url_platform = explode('/', $av_urlcertificat);
                    $ex_datas = array();
                    if (empty($rate_site) or empty($nb_site) or empty($horodate) or (($horodate + 86400) < time())) {
                        $nb_site = null;
                        $rate_site = null;
                        $platform = Tools::substr($url_platform[2], 4);
                       $url = "http://cl.avis-verifies.com/".$this->context->language->iso_code."/cache/".Tools::substr($av_idwebsite, 0, 1)."/".Tools::substr($av_idwebsite, 1, 1)."/".Tools::substr($av_idwebsite, 2, 1)."/".$av_idwebsite."/AWS/".$av_idwebsite."_infosite.txt";
                        $file_headers = @get_headers($url);
                        if(strpos($file_headers[0],"200"))
                        {
                            $datas = Tools::file_get_contents($url);
                            $ex_datas = explode(";", $datas);

                            if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1) {
                                Configuration::updateValue('AV_HORODATE_LASTGET', time(), false, null, $av_idshop);
                                Configuration::updateValue('AV_AVIS_SITE', $ex_datas[0], false, null, $av_idshop);
                                Configuration::updateValue('AV_RATE_SITE', $ex_datas[1], false, null, $av_idshop);
                            } else {
                                Configuration::updateValue('AV_HORODATE_LASTGET', time());
                                Configuration::updateValue('AV_AVIS_SITE', $ex_datas[0]);
                                Configuration::updateValue('AV_RATE_SITE', $ex_datas[1]);
                            }
                            $nb_site = $ex_datas[0];
                            $rate_site = $ex_datas[1];
                        }
                    }else{
                            $this->context->smarty->assign(array(
                                'av_site_rating_avis' => $nb_site,
                                'av_site_rating_rate' => $rate_site,
                                'name_site' => $name_site,
                                'rs_choice'=>$rs_choice
                            ));
                            $tpl = 'footer_av_site';
                            return $this->displayTemplate($tpl);
                        }
              }  
            } // RS enabled
        
    }

    /* WARNING : Modifications below need to be copy in ajax-load.php*/
    /**
     * Display reviews on the product page
     *
     * @param $params
     * @return array
     */
    public function hookDisplayProductExtraContent($params)
    {
        if (version_compare(_PS_VERSION_, '1.7', '>')) {
            $multisite = Configuration::get('AV_MULTISITE');
            $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
            if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
                $this->id_lang = $this->context->language->id;
                $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
                $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
            }

            $display_prod_reviews = configuration::get('AV_DISPLAYPRODREVIEWS'.$this->group_name, null, null, $av_idshop);
            $url_certificat = Configuration::get('AV_URLCERTIFICAT'.$this->group_name, null, null, $av_idshop);
            $avisverifies_nb_reviews = (int)Configuration::get('AV_NBOFREVIEWS', null, null, $av_idshop);

            $shop_name = Configuration::get('PS_SHOP_NAME');
            $id_product = (int)Tools::getValue('id_product');
            $o_av = new NetReviewsModel();
            $stats_product = (!isset($this->stats_product) || empty($this->stats_product)) ?
                $o_av->getStatsProduct($id_product, $this->group_name, $av_idshop)
                : $this->stats_product;

            /* If reviews existed & display allowed */
            if (! empty($stats_product['nb_reviews']) && $display_prod_reviews == 'yes') {
                $reviews = $o_av->getProductReviews($id_product, $this->group_name, $av_idshop, false, 0);
                $reviews_list = array(); //Create array with all reviews data
                $my_review = array(); //Create array with each reviews data
                foreach ($reviews as $review) {
                    //Create variable for template engine
                    $my_review['ref_produit'] = $review['ref_product'];
                    $my_review['id_product_av'] = $review['id_product_av'];
                    $my_review['rate'] = $review['rate'];
                    $my_review['rate_percent'] = $review['rate']*20;
                    $my_review['avis'] = html_entity_decode(urldecode($review['review']));
                    if (Tools::strlen($review['horodate'])=='10') {
                        $date = new DateTime();
                        $date->setTimestamp($review['horodate']);
                        $my_review['horodate'] = $date->format('d/m/Y') ;
                    } else {
                        $my_review['horodate'] = date('d/m/Y', strtotime($review['horodate']));
                    }
                    $my_review['customer_name'] = urldecode($review['customer_name']);    

                    $my_review['discussion'] = '';

                    $unserialized_discussion = Tools::jsonDecode(NetReviewsModel::acDecodeBase64($review['discussion']), true);
                    if ($unserialized_discussion) {
                        foreach ($unserialized_discussion as $k_discussion => $each_discussion) {
                            if (Tools::strlen($each_discussion['horodate'])=='10') {
                                $date = new DateTime();
                                $date->setTimestamp($each_discussion['horodate']);
                                $my_review['discussion'][$k_discussion]['horodate'] = $date->format('d/m/Y') ;
                            } else {
                                $my_review['discussion'][$k_discussion]['horodate'] = date('d/m/Y', strtotime($each_discussion['horodate']));
                            }
                            $my_review['discussion'][$k_discussion]['commentaire'] = $each_discussion['commentaire'];
                            if ($each_discussion['origine'] == 'ecommercant') {
                                $my_review['discussion'][$k_discussion]['origine'] = $shop_name;
                            } else {
                                if ($each_discussion['origine'] == 'internaute') {
                                    $my_review['discussion'][$k_discussion]['origine'] = $my_review['customer_name'];
                                } else {
                                    $my_review['discussion'][$k_discussion]['origine'] = $this->l('Moderator');
                                }
                            }
                        }
                    }
                    array_push($reviews_list, $my_review);
                }
                    $widgetlight = Configuration::get('AV_LIGHTWIDGET', null, null, $av_idshop);
                // $this->context->controller->pagination((int)$stats_product['nb_reviews']);
                if(Configuration::get('AV_DISPLAYGOOGLESNIPPETSITE', null, null, $av_idshop) == '1' && (Configuration::get('AV_DISPLAYGOOGLESNIPPETPRODUIT', null, null, $av_idshop) != '1') ){
                    $snippets_active = true;
                }
                if(Configuration::get('AV_DISPLAYGOOGLESNIPPETPRODUIT', null, null, $av_idshop) == '3'){
                    $snippets_complete = 1;
                }elseif(Configuration::get('AV_DISPLAYGOOGLESNIPPETPRODUIT', null, null, $av_idshop) == '2'){
                    $snippets_complete = 0;
                }
                   /* Position 2 : Extraright - AggregateRating (Default)
                   Position 3 : Extraright - Product
                   Position 1 : Footer - Product
                   Position 4 : Tabcontent - AggregateRating
                   Position 5 : Tabcontent - Product
                   */
                $this->context->smarty->assign(array(
                    'modules_dir' => _MODULE_DIR_,
                    'base_uri' => _PS_BASE_URL_ ,
                    'current_url' =>  $_SERVER['REQUEST_URI'],
                    'id_shop' => $av_idshop,
                    'nom_group' => (!empty($this->group_name))?"'"."'":null,
                    'reviews' => $reviews_list,
                    'count_reviews' => $stats_product['nb_reviews'],
                    'average_rate' => round($stats_product['rate'], 1),
                    'widgetlight' =>  !empty($widgetlight)? $widgetlight: false,
                    'average_rate_percent' => $stats_product['rate'] * 20,
                    'is_https' => (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on' ? 1 : 0),
                    'url_certificat' => $url_certificat,
                    'avisverifies_nb_reviews' => ($avisverifies_nb_reviews)? $avisverifies_nb_reviews : "",
                     'snippets_complete' =>  !empty($snippets_complete)? $snippets_complete: false,
                     'snippets_active' =>  !empty($snippets_active)? $snippets_active: false
                ));

                $content= ($this->display(__FILE__, '/views/templates/hook/av-tabcontent.tpl'));

                $title= $this->l('verified reviews')."(".$stats_product['nb_reviews'].")";
                $array = array();
                $extraContent = (new PrestaShop\PrestaShop\Core\Product\ProductExtraContent());
                $extraContent->setTitle($title) ;
                $extraContent->setContent($content);
                $array[] = $extraContent;
            } else {
                $extraContent = (new PrestaShop\PrestaShop\Core\Product\ProductExtraContent());
                $extraContent->setContent("");
                $array[] =$extraContent;
            }
            return $array;
        }
    }

    /**
     *
     * @param $params
     * @return string|void
     */

    public function hookActionValidateOrder($params)
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }

        //$process_init = Configuration::get('AV_PROCESSINIT');
        $id_website = configuration::get('AV_IDWEBSITE'.$this->group_name, null, null, $av_idshop);
        $secret_key = configuration::get('AV_CLESECRETE'.$this->group_name, null, null, $av_idshop);
        $code_lang = configuration::get('AV_CODE_LANG'.$this->group_name, null, null, $av_idshop);

        if (empty($id_website) || empty($secret_key)) {
            return;
        }
        $code_lang = (!empty($code_lang)) ? $code_lang : 'undef';
        $o_order = $params['order'];
        $id_order = $o_order->id;
        if (!empty($o_order) && !empty($id_order)) {
            $o_av = new NetReviewsModel();
            $o_av->id_order = (int)$id_order;
            if (!empty($o_order->id_shop)) {
                $o_av->id_shop = $o_order->id_shop;
            }
            $o_av->iso_lang = pSQL(Language::getIsoById($o_order->id_lang));
            $o_av->saveOrderToRequest();
            $order_total = ($o_order->total_paid) ? (100 * $o_order->total_paid) : 0;
            return "<img height='1' hspace='0'
            src='//www.netreviews.eu/index.php?action=act_order&idWebsite=$id_website&langue=$code_lang&refCommande=$id_order&montant=$order_total' />";
        }
    }

     /**
     * This code is added for having possiblities of double check 
     * if not all orders are registered 
     */
    public function hookActionOrderStatusPostUpdate($params)
{
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
    if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
        $this->id_lang = $this->context->language->id;
        $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
        $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
    }
        $id_website = configuration::get('AV_IDWEBSITE'.$this->group_name, null, null, $av_idshop);
        $secret_key = configuration::get('AV_CLESECRETE'.$this->group_name, null, null, $av_idshop);
        $code_lang = configuration::get('AV_CODE_LANG'.$this->group_name, null, null, $av_idshop);

        if (empty($id_website) || empty($secret_key)) {
            return;
        }
    $double_check_orders = Configuration::get('AV_ORDER_UPDATE', null, null, $av_idshop);
    if (isset($double_check_orders) && ($double_check_orders == "1")){
       if (Configuration::get('AV_MULTILINGUE') == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }
         //$process_init = Configuration::get('AV_PROCESSINIT');
        $code_lang = (!empty($code_lang)) ? $code_lang : 'undef';
        $id_order =$params['id_order'];
        if (!empty($id_order)) {
            $o_av = new NetReviewsModel();
            $o_av->id_order = (int)$id_order;
            if (!empty($params['cart']->id_shop)) {
                $o_av->id_shop = $params['cart']->id_shop;
            }
            $o_av->iso_lang = pSQL(Language::getIsoById($params['cart']->id_lang));
            $o_av->saveOrderToRequest();
            return "<img height='1' hspace='0'
            src='//www.netreviews.eu/index.php?action=act_order&idWebsite=$id_website&langue=$code_lang&refCommande=$id_order' />";
        }
    }
}
    /**
     * Integration of widget site
     *
     * @return string|void
     */
    public function hookDisplayRightColumn()
    {
         $multisite = Configuration::get('AV_MULTISITE');
         $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }

        $av_scriptfixe_allowed = Configuration::get('AV_SCRIPTFIXE_ALLOWED'.$this->group_name, null, null, $av_idshop);
        $av_scriptfixe_position = Configuration::get('AV_SCRIPTFIXE_POSITION'.$this->group_name, null, null, $av_idshop);
        $av_scriptfixe = Configuration::get('AV_SCRIPTFIXE'.$this->group_name, null, null, $av_idshop);

        if ($av_scriptfixe_allowed != 'yes' || $av_scriptfixe_position != 'right') {
            return;
        }

        if ((strpos( strtolower($av_scriptfixe),'null') != true || strlen($av_scriptfixe) > 10) && $av_scriptfixe_allowed  === "yes" ) {
            return "\n\n<div align='center'>".Tools::stripslashes(html_entity_decode($av_scriptfixe)).
                "</div><br clear='left'/><br />";
        }
    }

    public function hookProductTab()
    {
         $multisite = Configuration::get('AV_MULTISITE');
         $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }

        $display_prod_reviews = Configuration::get('AV_DISPLAYPRODREVIEWS'.$this->group_name, null, null, $av_idshop);

        $o_av = new NetReviewsModel();
        $this->stats_product = $o_av->getStatsProduct((int)Tools::getValue('id_product'), $this->group_name, $av_idshop);
        if ($this->stats_product['nb_reviews'] < 1 || $display_prod_reviews != 'yes') {
            return ''; //Si Aucun avis, on retourne vide
        }
        $this->context->smarty->assign(
            array(
                'count_reviews' => $this->stats_product['nb_reviews']
            )
        );

        $tpl = "avisverifies-tab";

        return $this->displayTemplate($tpl);
    }

    /* WARNING : Modifications below need to be copy in ajax-load.php*/
    public function hookProductTabContent()
    {
         $multisite = Configuration::get('AV_MULTISITE');
         $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }

        $display_prod_reviews = configuration::get('AV_DISPLAYPRODREVIEWS'.$this->group_name, null, null, $av_idshop);
        $url_certificat = Configuration::get('AV_URLCERTIFICAT'.$this->group_name, null, null, $av_idshop);
        $avisverifies_nb_reviews = (int)Configuration::get('AV_NBOFREVIEWS', null, null, $av_idshop);

        $shop_name = Configuration::get('PS_SHOP_NAME');
        $id_product = (int)Tools::getValue('id_product');
        $o_av = new NetReviewsModel();
        $stats_product = (!isset($this->stats_product) || empty($this->stats_product)) ?
            $o_av->getStatsProduct($id_product, $this->group_name, $av_idshop)
            : $this->stats_product;
        if ($stats_product['nb_reviews'] < 1 || $display_prod_reviews != 'yes') {
            return ''; /* if no reviews, return empty */
        }
        $reviews = $o_av->getProductReviews($id_product, $this->group_name, $av_idshop, false, 0);
        $reviews_list = array(); //Create array with all reviews data
        $my_review = array(); //Create array with each reviews data
        foreach ($reviews as $review) {
            //Create variable for template engine
            $my_review['ref_produit'] = $review['ref_product'];
            $my_review['id_product_av'] = $review['id_product_av'];
            $my_review['rate'] = $review['rate'];
            $my_review['rate_percent'] = $review['rate']*20;
            $my_review['avis'] = html_entity_decode(urldecode($review['review']));
            if (Tools::strlen($review['horodate'])=='10') {
                $date = new DateTime();
                $date->setTimestamp($review['horodate']);
                $my_review['horodate'] = $date->format('d/m/Y') ;
            } else {
                $my_review['horodate'] = date('d/m/Y', strtotime($review['horodate']));
            }
            $my_review['customer_name'] = urldecode($review['customer_name']);
            $my_review['discussion'] = '';

            // renverser le nom et le prénom
            $customer_name= urldecode($review['customer_name']);
            $customer_name=(explode(' ',$customer_name));
            $prenom = $customer_name[1];
            $nom = $customer_name[0];
            $my_review['customer_name'] = $prenom." ".$nom;

            $unserialized_discussion = Tools::jsonDecode(NetReviewsModel::acDecodeBase64($review['discussion']), true);
            if ($unserialized_discussion) {
                foreach ($unserialized_discussion as $k_discussion => $each_discussion) {
                    if (Tools::strlen($each_discussion['horodate'])=='10') {
                        $date = new DateTime();
                        $date->setTimestamp($each_discussion['horodate']);
                        $my_review['discussion'][$k_discussion]['horodate'] = $date->format('d/m/Y') ;
                    } else {
                        $my_review['discussion'][$k_discussion]['horodate'] = date('d/m/Y', strtotime($each_discussion['horodate']));
                    }
                    $my_review['discussion'][$k_discussion]['commentaire'] = $each_discussion['commentaire'];
                    if ($each_discussion['origine'] == 'ecommercant') {
                        $my_review['discussion'][$k_discussion]['origine'] = $shop_name;
                    } else if ($each_discussion['origine'] == 'internaute') {
                        $my_review['discussion'][$k_discussion]['origine'] = $my_review['customer_name'];
                    } else {
                        $my_review['discussion'][$k_discussion]['origine'] = $this->l('Moderator');
                    }
                }
            }
            array_push($reviews_list, $my_review);
        }

        //rich snippets informations:
        $lang_id = (int)$this->context->language->id;
        if (empty($lang_id)) {
            $lang_id = 1;
        }
        $product = new Product((int)$id_product);
        $product_description = strip_tags($product->description_short[$lang_id]);
        $array_url = NetReviewsModel::getUrlsProduct($product->id);       
        $name_product =  $product->name;
        $url_page = $array_url['url_product'];
        $url_image = $array_url['url_image_product'];
        $sku = $product->reference;
        $mpn = $product->supplier_reference;
        $gtin_upc = $product->upc;
        $gtin_ean = $product->ean13;
        $brand_name ='';
        if (isset($this->context->smarty->tpl_vars['product']->value->manufacturer_name)){
            $brand_name = $this->context->smarty->tpl_vars['product']->value->manufacturer_name;
        }
        $av_sp_active = Configuration::get('AV_DISPLAYGOOGLESNIPPETSITE', null, null, $av_idshop);
        $av_sp_p = Configuration::get('AV_DISPLAYGOOGLESNIPPETPRODUIT', null, null, $av_idshop);
        if($av_sp_active == '1' &&  ($av_sp_p== '4' || $av_sp_p== '5')  ){
            $snippets_active = true;
        }
        if($av_sp_p == '5'){
            $snippets_complete = 1;
        }elseif($av_sp_p == '4'){
            $snippets_complete = 0;
        }
           /* Position 2 : Extraright - AggregateRating (Default)
           Position 3 : Extraright - Product
           Position 1 : Footer - Product
           Position 4 : Tabcontent - AggregateRating
           Position 5 : Tabcontent - Product
           */
          
        $this->context->controller->pagination((int)$stats_product['nb_reviews']);
        $widgetlight = Configuration::get('AV_LIGHTWIDGET', null, null, $av_idshop);
        $this->context->smarty->assign(array(
            'current_url' =>  $_SERVER['REQUEST_URI'],
            'id_shop' => $av_idshop,
            'nom_group' => (!empty($this->group_name))?'"'.'"':null,
            'reviews' => $reviews_list,
            'count_reviews' => $stats_product['nb_reviews'],
            'average_rate' => round($stats_product['rate'], 1),
            'average_rate_percent' => $stats_product['rate'] * 20,
            'widgetlight' =>  !empty($widgetlight)? $widgetlight: false,
            'is_https' => (array_key_exists('HTTPS', $_SERVER) && $_SERVER['HTTPS'] == 'on' ? 1 : 0),
            'url_certificat' => $url_certificat,
            'avisverifies_nb_reviews' => ($avisverifies_nb_reviews)? (int)$avisverifies_nb_reviews : "",
            'product_id' =>  $id_product,
            'product_name' => $this->getProductName($id_product, $lang_id),
            'product_description' => !empty($product_description)? $product_description: false,
            'product_url' =>  !empty($url_page)? $url_page: false,
            'url_image' =>  !empty($url_image)? $url_image: false,
            'product_price' => $product->getPrice(true, null, 2),
            'sku' =>  !empty($sku)? $sku: false,
            'mpn' =>  !empty($mpn)? $mpn: false,
            'gtin_upc' =>  !empty($gtin_upc)? $gtin_upc: false,
            'gtin_ean' =>  !empty($gtin_ean)? $gtin_ean: false,
            'brand_name' =>  !empty($brand_name)? $brand_name: false,
            'snippets_complete' =>  !empty($snippets_complete)? $snippets_complete: false,
            'snippets_active' =>  !empty($snippets_active)? $snippets_active: false
        ));

        $tpl = "avisverifies-tab-content";
        return $this->displayTemplate($tpl);
    }

/**
     * Integration of widget product
     * hookExtraRight
     * hookExtraLeft
     * hookDisplayProductButtons 
     * hookExtra_netreviews
     * @return string template of widget
     */
    public function hookExtra_netreviews()
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }else{
            $this->group_name = "";  
        }
        $snippets_complete = 0 ; 
        $display_prod_reviews = configuration::get('AV_DISPLAYPRODREVIEWS'.$this->group_name, null, null, $av_idshop);
        $id_product = (int)Tools::getValue('id_product');
        $o = new NetReviewsModel();
        $reviews = $o->getStatsProduct($id_product, $this->group_name, $av_idshop);

        if ($reviews['nb_reviews'] < 1 || $display_prod_reviews != 'yes') {
            return ''; //Si Aucun avis, on retourne vide
        }
        
       //rich snippets informations:
        $lang_id = (int)$this->context->language->id;
        if (empty($lang_id)) {
            $lang_id = 1;
        }

        $percent = round($reviews['rate'] * 20) ;
        $product = new Product((int)$id_product);
        $product_description = strip_tags($product->description_short[$lang_id]);
        $array_url = NetReviewsModel::getUrlsProduct($product->id);       
        $name_product =  $product->name;
        $url_page = $array_url['url_product'];
        $url_image = $array_url['url_image_product'];
        $sku = $product->reference;
        $mpn = $product->supplier_reference;
        $gtin_upc = $product->upc;
        $gtin_ean = $product->ean13;
        $brand_name ='';
        if (isset($this->context->smarty->tpl_vars['product']->value->manufacturer_name)){
            $brand_name = $this->context->smarty->tpl_vars['product']->value->manufacturer_name;
        }
    
        $widgetlight = Configuration::get('AV_LIGHTWIDGET', null, null, $av_idshop);
        /*    AV_LIGHTWIDGET :
                1 : étoiles simples
                2 : widget par défaut
                3 : widget badge
          */
        
        $av_sp_active = Configuration::get('AV_DISPLAYGOOGLESNIPPETSITE', null, null, $av_idshop);
        $av_sp_p = Configuration::get('AV_DISPLAYGOOGLESNIPPETPRODUIT', null, null, $av_idshop);
        if($av_sp_active == '1' &&  ($av_sp_p== '2' || $av_sp_p== '3')  ){
            $snippets_active = true;
        }
        if($av_sp_p == '3'){
            $snippets_complete = 1;
        }elseif($av_sp_p == '2'){
            $snippets_complete = 0;
        }

           /* Position 2 : Extraright - AggregateRating (Default)
           Position 3 : Extraright - Product
           Position 1 : Footer - Product
           Position 4 : Tabcontent - AggregateRating
           Position 5 : Tabcontent - Product
           */
        $this->context->smarty->assign(array(
            'av_nb_reviews' => $reviews['nb_reviews'],
            'av_rate' =>   round($reviews['rate'], 1),
            'av_rate_percent' =>  ($percent) ? $percent : 100,
            'average_rate' => round($reviews['rate'], 1),
            'product_id' =>  $id_product,
            'product_name' => $this->getProductName($id_product, $lang_id),
            'product_description' => !empty($product_description)? $product_description: false,
            'product_url' =>  !empty($url_page)? $url_page: false,
            'url_image' =>  !empty($url_image)? $url_image: false,
            'product_price' => $product->getPrice(true, null, 2),
            'sku' =>  !empty($sku)? $sku: false,
            'mpn' =>  !empty($mpn)? $mpn: false,
            'gtin_upc' =>  !empty($gtin_upc)? $gtin_upc: false,
            'gtin_ean' =>  !empty($gtin_ean)? $gtin_ean: false,
            'brand_name' =>  !empty($brand_name)? $brand_name: false,
            'widgetlight' =>  !empty($widgetlight)? $widgetlight: false,
            'snippets_complete' =>  !empty($snippets_complete)? $snippets_complete: false,
            'snippets_active' =>  !empty($snippets_active)? $snippets_active: false
        ));

        $tpl = 'avisverifies-extraright';
        return $this->displayTemplate($tpl);
    }

     public function hookExtraRight()
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if(Configuration::get('AV_EXTRA_OPTION', null, null, $av_idshop) == '0')
        return $this->hookExtra_netreviews();
    }

     public function hookExtraLeft()
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if(Configuration::get('AV_EXTRA_OPTION', null, null, $av_idshop) == '1')
        return $this->hookExtra_netreviews();
    }
    
    public function hookDisplayProductButtons()
    {
        $multisite = Configuration::get('AV_MULTISITE');
        $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if(Configuration::get('AV_EXTRA_OPTION', null, null, $av_idshop) == '2')
        return $this->hookExtra_netreviews();
    }

    /**
     * Integration of widget site on the left column
     *
     * @return string|void
     */
    public function hookDisplayLeftColumn()
    {
       $multisite = Configuration::get('AV_MULTISITE');
       $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }

        $av_scriptfixe_allowed = Configuration::get('AV_SCRIPTFIXE_ALLOWED', null, null, $av_idshop);
        $av_scriptfixe_position = Configuration::get('AV_SCRIPTFIXE_POSITION', null, null, $av_idshop);
        $av_scriptfixe = Configuration::get('AV_SCRIPTFIXE', null, null, $av_idshop);

        if ($av_scriptfixe_allowed != 'yes' || $av_scriptfixe_position != 'left') {
            return;
        }
        if ((strpos( strtolower($av_scriptfixe),'null') != true || strlen($av_scriptfixe) > 10) && $av_scriptfixe_allowed  === "yes") {
            return "\n\n<div align='center'>".Tools::stripslashes(html_entity_decode($av_scriptfixe)).
                "</div><br clear='left'/><br />";
        }
    }


    /**
     * Integration of widget site on the footer
     *
     * @param $params
     * @return string|void
     */
    public function hookDisplayFooterBefore($params)
    {
       $multisite = Configuration::get('AV_MULTISITE');
       $av_idshop = (!empty($multisite))? $this->context->shop->getContextShopID():null;
        if (Configuration::get('AV_MULTILINGUE', null, null, $av_idshop) == 'checked') {
            $this->id_lang = $this->context->language->id;
            $this->iso_lang = pSQL(Language::getIsoById($this->id_lang));
            $this->group_name = $this->getIdConfigurationGroup($this->iso_lang);
        }

        $av_scriptfixe_allowed = Configuration::get('AV_SCRIPTFIXE_ALLOWED', null, null, $av_idshop);
        $av_scriptfixe_position = Configuration::get('AV_SCRIPTFIXE_POSITION', null, null, $av_idshop);
        $av_scriptfixe = Configuration::get('AV_SCRIPTFIXE', null, null, $av_idshop);

        if ($av_scriptfixe_allowed != 'yes' || $av_scriptfixe_position != 'footer') {
            return;
        }
        if ((strpos( strtolower($av_scriptfixe),'null') != true || strlen($av_scriptfixe) > 10) && $av_scriptfixe_allowed  === "yes") {
            return "\n\n<div align='center'>".Tools::stripslashes(html_entity_decode($av_scriptfixe)).
                "</div><br clear='left'/><br />";
        }
    }


    private function displayTemplate($tpl)
    {
        $specifique = file_exists(_PS_MODULE_DIR_."netreviews/views/templates/hook/$tpl-specifique.tpl");
        if ($specifique) {
            return  ($this->display(__FILE__, "/views/templates/hook/$tpl-specifique.tpl"));
        } else {
            return  ($this->display(__FILE__, "/views/templates/hook/$tpl.tpl"));
        }
    }


    /**
     * initContext for the retrocompatibility from previous versions of PS
     */
    private function initContext()
    {
        if (class_exists('Context')) {
            $this->context = Context::getContext();
        } else {
            global $smarty, $cookie;
            $this->context = new StdClass();
            $this->context->smarty = $smarty;
            $this->context->cookie = $cookie;
        }
    }

    private function getProductName($id_product, $id_lang)
    {
        // creates the query
        $query = 'SELECT DISTINCT pl.name as name
                    FROM '._DB_PREFIX_.'product_lang pl
                    WHERE pl.id_product = '.(int)$id_product.'
                    AND pl.id_lang = '.(int)$id_lang.'
                    And id_shop = '.$this->context->shop->getContextShopID();

        return Db::getInstance()->getValue($query);
    }

    private function getIdConfigurationGroup($lang_iso = null)
    {
        $multisite = Configuration::get('AV_MULTISITE');
        if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1 && !empty($multisite)) {
            $sql = 'SELECT name FROM '._DB_PREFIX_."configuration where name like 'AV_GROUP_CONF_%' And id_shop = '"
                .$this->context->shop->getContextShopID()."'";
        } else {
            $sql = 'SELECT name FROM '._DB_PREFIX_."configuration where name like 'AV_GROUP_CONF_%'";
        }
        if ($results = Db::getInstance()->ExecuteS($sql)) {
            foreach ($results as $row) {
                if (Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1 && !empty($multisite)) {
                    $vconf = unserialize(Configuration::get($row['name'], null, null, $this->context->shop->getContextShopID()));
                } else {
                    $vconf = unserialize(Configuration::get($row['name']));
                }
                if ($vconf && in_array($lang_iso, $vconf)) {
                    return '_'.Tools::substr($row['name'], 14);
                }
            }
        }
    }

    private function setIdConfigurationGroup($languages = null, $idshop_conf = true ,$i = 0)
    {
        if (empty($languages)) {
            return;
        }
        reset($languages);
        $id_langue_curent = key($languages);
        $lang = $languages[$id_langue_curent];
        $id_website_current = Tools::getValue('avisverifies_idwebsite_'.$lang['iso_code']);
        $cle_secrete_current = Tools::getValue('avisverifies_clesecrete_'.$lang['iso_code']);

        if (empty($id_website_current) || empty($cle_secrete_current)) {
            unset($languages[$id_langue_curent]);
            return $this->setIdConfigurationGroup($languages,$idshop_conf, $i);
        } else {
                if(Configuration::get('PS_MULTISHOP_FEATURE_ACTIVE') == 1 && $idshop_conf){
                    $added_condition ="And id_shop = ".$this->context->shop->getContextShopID();
                }else{
                     $added_condition ="";
                }
                $sql = 'SELECT name
                FROM '._DB_PREFIX_."configuration
                WHERE value = '".pSql($id_website_current)."'
                AND name like 'AV_IDWEBSITE_%' ".$added_condition;
                if ($row = Db::getInstance()->getRow($sql)) {
                    if (Configuration::get('AV_CLESECRETE_'.Tools::substr($row['name'], 13), null, null, $this->context->shop->getContextShopID()) != $cle_secrete_current) {
                        $this->context->controller->errors[] = sprintf($this->l('PARAM ERROR: please check your multilingual configuration for the id_website "%s" at language "%s"'), $id_website_current, $lang['name']);
                        unset($languages[$id_langue_curent]);
                        return $this->setIdConfigurationGroup($languages,$idshop_conf, $i);
                    }
                }

            $group = array();
            array_push($group, $lang['iso_code']);
            unset($languages[$id_langue_curent]);
            foreach ($languages as $id1 => $lang1) {
                if ($id_website_current == Tools::getValue('avisverifies_idwebsite_'.$lang1['iso_code'])
                    && $cle_secrete_current == Tools::getValue('avisverifies_clesecrete_'.$lang1['iso_code'])) {
                    array_push($group, $lang1['iso_code']);
                    unset($languages[$id1]);
                }
            }
            // Create PS configuration variable
            if($idshop_conf){
                $idshop = $this->context->shop->getContextShopID();
            }else{
                $idshop = null; 
            }
                if (!Configuration::get('AV_IDWEBSITE_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_IDWEBSITE_'.$i, Tools::getValue('avisverifies_idwebsite_'.$lang['iso_code']), false, null, $idshop);
                }

                if (!Configuration::get('AV_CLESECRETE_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_CLESECRETE_'.$i, Tools::getValue('avisverifies_clesecrete_'.$lang['iso_code']), false, null, $idshop);
                }

                if (!Configuration::get('AV_GROUP_CONF_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_GROUP_CONF_'.$i, serialize($group), false, null, $idshop);
                }

                // if (!Configuration::get('AV_LIGHTWIDGET_'.$i, null, null, $idshop)) {
                //     Configuration::updateValue('AV_LIGHTWIDGET_'.$i, '0', false, null, $idshop);
                // }

                if (!Configuration::get('AV_PROCESSINIT_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_PROCESSINIT_'.$i, '', false, null, $idshop);
                }

                if (!Configuration::get('AV_ORDERSTATESCHOOSEN_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_ORDERSTATESCHOOSEN_'.$i, '', false, null, $idshop);
                }

                if (!Configuration::get('AV_DELAY_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_DELAY_'.$i, '', false, null, $idshop);
                }

                if (!Configuration::get('AV_DELAY_PRODUIT_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_DELAY_PRODUIT_'.$i, '', false, null, $idshop);
                }

                if (!Configuration::get('AV_GETPRODREVIEWS_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_GETPRODREVIEWS_'.$i, '', false, null, $idshop);
                }

                if (!Configuration::get('AV_DISPLAYPRODREVIEWS_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_DISPLAYPRODREVIEWS_'.$i, '', false, null, $idshop);
                }

                if (!Configuration::get('AV_SCRIPTFLOAT_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_SCRIPTFLOAT_'.$i, '', false, null, $idshop);
                }

                if (!Configuration::get('AV_SCRIPTFLOAT_ALLOWED_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_SCRIPTFLOAT_ALLOWED_'.$i, '', false, null, $idshop);
                }

                if (!Configuration::get('AV_SCRIPTFIXE_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_SCRIPTFIXE_'.$i, '', false, null, $idshop);
                }

                if (!Configuration::get('AV_SCRIPTFIXE_ALLOWED_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_SCRIPTFIXE_ALLOWED_'.$i, '', false, null, $idshop);
                }

                if (!Configuration::get('AV_URLCERTIFICAT_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_URLCERTIFICAT_'.$i, '', false, null, $idshop);
                }

                if (!Configuration::get('AV_FORBIDDEN_EMAIL_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_FORBIDDEN_EMAIL_'.$i, '', false, null, $idshop);
                }

                if (!Configuration::get('AV_CODE_LANG_'.$i, null, null, $idshop)) {
                    Configuration::updateValue('AV_CODE_LANG_'.$i, '', false, null, $idshop);
                }

            $i++;
            return $this->setIdConfigurationGroup($languages,$idshop_conf, $i);
        }
    }
}
