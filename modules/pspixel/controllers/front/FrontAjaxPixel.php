<?php
/**
* 2007-2016 PrestaShop
*
* DISCLAIMER
*
* Do not edit or add to this file if you wish to upgrade PrestaShop to newer
* versions in the future. If you wish to customize PrestaShop for your
* needs please refer to http://www.prestashop.com for more information.
*
* @author    PrestaShop SA <contact@prestashop.com>
* @copyright 2007-2015 PrestaShop SA
* @license   http://addons.prestashop.com/en/content/12-terms-and-conditions-of-use
* International Registered Trademark & Property of PrestaShop SA
*/

use PrestaShop\PrestaShop\Adapter\ObjectPresenter;

class PsPixelFrontAjaxPixelModuleFrontcontroller extends ModuleFrontController
{

    //Redirect to home if trying to access to the front controller without ajax call
    public function initContent()
    {
        $this->module = Module::getInstanceByName('ps_pixel');
        if (Tools::getValue('ajax') != true) {
            parent::initContent();
            Tools::redirect('index.php?fc=PageNotFound');
        }
    }

    // Get Product Informations
    public function displayAjaxGetProduct()
    {
        if (empty(Configuration::get('PS_PIXEL_ID'))) {
            return;
        }
        $id_lang = (int)$this->context->language->id;
        $id_product = (int)Tools::getValue('id_product');
        $id_attribute = (int)Tools::getValue('id_attribute');

        $objectPresenter = new ObjectPresenter();

        $myproduct = new Product($id_product, true, $id_lang);
        $product = $objectPresenter->present($myproduct);
        $product['id_product'] = (int) $myproduct->id;
        $product['out_of_stock'] = (int) $myproduct->out_of_stock;
        $product['new'] = (int) $myproduct->new;
        $product['id_product_attribute'] = $id_attribute;
        $products = Product::getProductProperties($this->context->language->id, $product, $this->context);

        $productPresenter = new ProductPresenterFactory($this->context, new TaxConfiguration());
        $presenter = $productPresenter->getPresenter();
        $productSettings = $productPresenter->getPresentationSettings();

        $pixelProduct = $presenter->present(
            $productSettings,
            $products,
            $this->context->language
        );

        die(Tools::jsonEncode($pixelProduct));
    }
}
