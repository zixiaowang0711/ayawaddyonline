<?php

/**
 * Created by PhpStorm.
 * User: migue
 * Date: 28/06/2017
 * Time: 10:48
 */
class Oct8neOct8neCustomerConfigModuleFrontController extends ModuleFrontController
{

    public function init(){

        parent::init();

        $valid =  $this->IsValidIp();
        if(!$valid){
            Tools::redirect(_PS_BASE_URL_);
            exit;
        }


    }


    public function initContent()
    {
        parent::initContent();


        $searchenginename = $this->module->getSEARCHENGINENAME();
        $urltypename = $this->module->getURLIMGTYPENAME();
        $positionloadName = $this->module->getPOSITIONLOADNAME();

        $searchengine = Configuration::get($searchenginename);


        $urltype = Configuration::get($urltypename);
        $positionload = Configuration::get($positionloadName);


        $submit = $this->context->link->getModuleLink('oct8ne','oct8necustomerconfig');



        $serchengines = $this->module->getDetectedSearchEngines();

        Context::getContext()->smarty->assign(array("POSITION_LOAD_NAME" => $positionloadName, "SEARCH_ENGINE_NAME" => $searchenginename,"URL_IMG_TYPE_NAME" => $urltypename,"SEARCH_ENGINES" => $serchengines,
                                                "POSITION_LOAD" => $positionload, "SEARCH_ENGINE" => $searchengine,"URL_IMG_TYPE" => $urltype, "SUBMIT" => $submit));


        if (version_compare(_PS_VERSION_, '1.7.0.0', '<')) {

            $this->setTemplate('customerconfig.tpl');

        } else{

            $this->setTemplate('module:oct8ne/views/templates/front/customerconfig7.tpl');
        }



        //http://localhost/prestashop6/es/index.php?fc=module&module=oct8ne&controller=oct8necustomerconfig
    }

    public function postProcess()
    {
        $this->module->postProcess();
    }


    private function IsValidIp() {

        if($this->CheckIpFromHeader('REMOTE_ADDR')) {
            return true;
        }
        if($this->CheckIpFromHeader('HTTP_X_FORWARDED_FOR')) {
            return true;
        }
        if($this->CheckIpFromHeader('HTTP_CLIENT_IP')) {
            return true;
        }
        if($this->CheckIpFromHeader('HTTP_X_FORWARDED')) {
            return true;
        }
        if($this->CheckIpFromHeader('HTTP_X_CLUSTER_CLIENT_IP')) {
            return true;
        }
        if($this->CheckIpFromHeader('HTTP_FORWARDED_FOR')) {
            return true;
        }
        if($this->CheckIpFromHeader('HTTP_FORWARDED')) {
            return true;
        }
        return false;
    }


    private function CheckIpFromHeader($header) {
        if(!isset($_SERVER[$header]))
            return FALSE;

        $ip = $_SERVER[$header];
        return $ip == '80.23.23.23' || $ip == '80.24.24.24' || $ip == '138.91.153.74' || $ip == '168.62.164.114' || $ip == '80.28.120.5' || $ip == '127.0.0.1';
    }

}