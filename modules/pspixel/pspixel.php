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
*  @copyright 2007-2016 PrestaShop SA
*  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

if (!defined('_PS_VERSION_')) {
    exit;
}

class Pspixel extends Module
{
    protected $js_path = null;
    protected $front_controller = null;

    public function __construct()
    {
        $this->name = 'pspixel';
        $this->author = 'Prestashop';
        $this->tab = 'analytics_stats';
        $this->module_key = '73fdb778d4cf7afd3bbfe96dc57dc8c5';
        $this->version = '1.0.3';
        $this->need_instance = 0;
        $this->bootstrap = true;

        $this->ps_versions_compliancy = array(
            'min' => '1.7.1.0',
            'max' => _PS_VERSION_,
        );

        parent::__construct();

        $this->displayName = $this->l('Official Facebook Pixel');
        $this->description = $this->l('This module allows you to implement an analysis tool into your website pages and track events');

        $this->js_path = 'modules/'.$this->name.'/views/js/';
        $this->front_controller = Context::getContext()->link->getModuleLink(
            $this->name,
            'FrontAjaxPixel',
            array(),
            true
        );
    }

    public function install()
    {
        $this->_clearCache('*');
        Configuration::updateValue('PS_PIXEL_ID', '');

        return parent::install()
          && $this->registerHook('header')
          && $this->registerHook('displayPaymentTop')
          && $this->registerHook('displayOrderConfirmation')
          && $this->registerHook('actionFrontControllerSetMedia')
          && $this->registerHook('actionAjaxDieProductControllerdisplayAjaxQuickviewBefore')
        ;
    }

    public function uninstall()
    {
        return parent::uninstall();
    }

    private function postProcess()
    {
        if (((bool)Tools::isSubmit('submitPixel')) === true) {
            $id_pixel = pSQL(trim(Tools::getValue('PS_PIXEL_ID')));
            if (empty($id_pixel)) {
                return  $this->displayError(
                    $this->l('Your ID Pixel can not be empty')
                );
            } elseif (Tools::strlen($id_pixel) < 15 || Tools::strlen($id_pixel) > 16) {
                return  $this->displayError(
                    $this->l('Your ID Pixel must be 16 characters long')
                );
            } else {
                Configuration::updateValue('PS_PIXEL_ID', $id_pixel);
                return $this->displayConfirmation(
                    $this->l('Your ID Pixel have been updated.')
                );
            }
        }
    }

    public function getContent()
    {
        // Set JS
        $this->context->controller->addJs(array(
            $this->_path.'views/js/conf.js',
            $this->_path.'views/js/faq.js'
        ));

        // Set CSS
        $this->context->controller->addCss(
            $this->_path.'views/css/faq.css'
        );

        $is_submit = $this->postProcess();

        include_once('classes/APIFAQClass.php');
        $api = new APIFAQ();
        $api_json = Tools::jsonDecode($api->getData($this));
        $apifaq_json_categories = '';
        if (!empty($api_json)) {
            $apifaq_json_categories = $api_json->categories;
        }

        $this->context->smarty->assign(array(
            'is_submit'          => $is_submit,
            'module_name'        => $this->name,
            'module_version'     => $this->version,
            'debug_mode'         => (int) _PS_MODE_DEV_,
            'module_display'     => $this->displayName,
            'multishop'          => (int) Shop::isFeatureActive(),
            'apifaq'             => $apifaq_json_categories,
            'version'            => _PS_VERSION_,
            'id_pixel'           => pSQL(Configuration::get('PS_PIXEL_ID')),
        ));

        return $is_submit.$this->display(__FILE__, 'views/templates/admin/configuration.tpl');
    }

    /*
    ** Hook's Managment
    */
    public function hookActionFrontControllerSetMedia()
    {
        if (empty(Configuration::get('PS_PIXEL_ID'))) {
            return;
        }

        // Asset Manager
        $this->context->controller->registerJavascript(
            'front_common',
            $this->js_path.'printpixel.js',
            array('position' => 'bottom', 'priority' => 150)
        );
    }

    // Handle Payment module (AddPaymentInfo)
    public function hookDisplayPaymentTop($params)
    {
      if (empty(Configuration::get('PS_PIXEL_ID'))) {
          return;
      }

      $items_id = array();
      $items = $params['cart']->getProducts();
      foreach ($items as &$item) {
          $items_id[] = (int)$item['id_product'];
      }
      unset($items, $item);

      $iso_code = pSQL($this->context->currency->iso_code);
      $content = array(
        'value' => Tools::ps_round($params['cart']->getOrderTotal(), 2),
        'currency' => $iso_code,
        'content_type' => 'product',
        'content_ids' => $items_id,
        'num_items' => $params['cart']->nbProducts(),
      );

      $content = $this->formatPixel($content);

      $this->context->smarty->assign(array(
        'type' => 'AddPaymentInfo',
        'content' => $content,
      ));

      return $this->display(__FILE__, 'views/templates/hook/displaypixel.tpl');
    }

    // Set Pixel (ViewContent / ViewCategory / ViewCMS / Search / InitiateCheckout)
    public function hookHeader($params)
    {
        if (empty(Configuration::get('PS_PIXEL_ID'))) {
            return;
        }

        $type = '';
        $content = array();

        $page = $this->context->controller->php_self;
        if (empty($page)) {
            $page = Tools::getValue('controller');
        }
        $page = pSQL($page);

        $id_lang = (int)$this->context->language->id;
        $locale = pSQL(Tools::strtoupper($this->context->language->iso_code));
        $iso_code = pSQL($this->context->currency->iso_code);
        $content_type = 'product';

        /**
        * Triggers ViewContent product pages
        */
        if ($page === 'product') {
            $type = 'ViewContent';
            $prods = $this->context->controller->getTemplateVarProduct();

            if (count($prods['attributes']) > 0) {
                $content_type = 'product_group';
            }

            $content = array(
              'content_name' => Tools::replaceAccentedChars($prods['name']) .' ('.$locale.')',
              'content_ids' => array($prods['id_product']),
              'content_type' => $content_type,
              'value' => (float)$prods['price_amount'],
              'currency' => $iso_code,
            );
        }
        /**
        * Triggers ViewContent for category pages
        */
        elseif ($page === 'category') {
            $type = 'ViewCategory';
            $category = $this->context->controller->getCategory();

            $breadcrumbs = $this->context->controller->getBreadcrumbLinks();
            $breadcrumb = implode(' > ', array_column($breadcrumbs['links'], 'title'));

            $prods = $category->getProducts($id_lang, 1, 10);

            $content = array(
              'content_name' => Tools::replaceAccentedChars($category->name) .' ('.$locale.')',
              'content_category' => Tools::replaceAccentedChars($breadcrumb),
              'content_ids' => array_column($prods, 'id_product'),
              'content_type' => $content_type,
            );
        }
        /**
        * Triggers ViewContent for cms pages
        */
        elseif ($page === 'cms') {
            // dump($this->context->controller);
            $type = 'ViewCMS';
            $cms = new Cms((int)Tools::getValue('id_cms'), $id_lang);

            $breadcrumbs = $this->context->controller->getBreadcrumbLinks();
            $breadcrumb = implode(' > ', array_column($breadcrumbs['links'], 'title'));

            $content = array(
              'content_category' => Tools::replaceAccentedChars($breadcrumb),
              'content_name' => Tools::replaceAccentedChars($cms->meta_title) .' ('.$locale.')',
            );
        }
        /**
        * Triggers Search for result pages
        */
        elseif ($page === 'search') {
            $type = Tools::ucfirst($page);
            $content = array(
              'search_string' => pSQL(Tools::getValue('s')),
            );
        }
        /**
        * Triggers InitiateCheckout for checkout page
        */
        elseif ($page === 'cart') {
            $type = 'InitiateCheckout';

            $content = array(
              'num_items' => $this->context->cart->nbProducts(),
              'content_ids' => array_column($this->context->cart->getProducts(), 'id_product'),
              'content_type' => $content_type,
              'value' => (float)$this->context->cart->getOrderTotal(),
              'currency' => $iso_code,
            );
        }

        // Format Pixel to display
        $content = $this->formatPixel($content);

        Media::addJsDef(array(
            'pixel_fc' => $this->front_controller
        ));

        $this->context->smarty->assign(array(
          'id_pixel' => pSQL(Configuration::get('PS_PIXEL_ID')),
          'type' => $type,
          'content' => $content,
        ));

        return $this->display(__FILE__, 'views/templates/hook/header.tpl');
    }

    // Handle QuickView (ViewContent)
    public function hookActionAjaxDieProductControllerdisplayAjaxQuickviewBefore($params)
    {
        if (empty(Configuration::get('PS_PIXEL_ID'))) {
            return;
        }

        // Decode Product Object
        $value = Tools::jsonDecode($params['value']);
        $locale = pSQL(Tools::strtoupper($this->context->language->iso_code));
        $iso_code = pSQL($this->context->currency->iso_code);

        $content = array(
          'content_name' => Tools::replaceAccentedChars($value->product->name) .' ('.$locale.')',
          'content_ids' => array($value->product->id_product),
          'content_type' => 'product',
          'value' => (float)$value->product->price_amount,
          'currency' => $iso_code,
        );
        $content = $this->formatPixel($content);

        $this->context->smarty->assign(array(
          'type' => 'ViewContent',
          'content' => $content,
        ));

        $value->quickview_html .= $this->context->smarty->fetch(
            $this->local_path.'views/templates/hook/displaypixel.tpl'
        );

        // Recode Product Object
        $params['value'] = Tools::jsonEncode($value);

        die($params['value']);
    }

    // Handle Display confirmation (Purchase)
    public function hookDisplayOrderConfirmation($params)
    {
        if (empty(Configuration::get('PS_PIXEL_ID'))) {
            return;
        }

        $order = $params['order'];

        $num_items = 0;
        $items_id = array();
        $items = $order->getProductsDetail();
        foreach ($items as $item) {
            $num_items += (int)$item['product_quantity'];
            $items_id[] = (int)$item['product_id'];
        }
        unset($items, $item);

        $iso_code = pSQL($this->context->currency->iso_code);

        $content = array(
          'value' => Tools::ps_round($order->total_paid, 2),
          'currency' => $iso_code,
          'content_type' => 'product',
          'content_ids' => $items_id,
          'order_id' => $params['order']->id,
          'num_items' => $num_items,
        );

        $content = $this->formatPixel($content);

        $this->context->smarty->assign(array(
          'type' => 'Purchase',
          'content' => $content,
        ));

        return $this->display(__FILE__, 'views/templates/hook/displaypixel.tpl');
    }

    // Format you pixel
    private function formatPixel($params)
    {
        if (!empty($params)) {
            $format = '{';
            foreach ($params as $key => &$val) {
                if (gettype($val) === 'string') {
                    $format .= $key.': \''.addslashes($val).'\', ';
                } elseif (gettype($val) === 'array') {
                    $format .= $key.': [\'';
                    foreach ($val as &$id) {
                        $format .= (int)$id."', '";
                    }
                    unset($id);
                    $format = Tools::substr($format, 0, -4);
                    $format .= '\'], ';
                } else {
                    $format .= $key.': '.addslashes($val).', ';
                }
            }
            unset($params, $key, $val);

            $format = Tools::substr($format, 0, -2);
            $format .= '}';

            return $format;
        }
        return false;
    }
}
