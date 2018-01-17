<?php
/**
* 2007-2017 PrestaShop
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

include_once(dirname(__FILE__).'/../../../config/config.inc.php');

class APIFAQ
{
    public function getData($m)
    {
        $context = Context::getContext();
        $iso_code = Language::getIsoById($context->language->id);
        $url = 'http://api.addons.prestashop.com/request/faq/'.$m->module_key.'/'.$m->version.'/'.$iso_code;

        $options = array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HEADER         => false
        );

        $CURL = curl_init();
        curl_setopt_array($CURL, $options);
        $content = curl_exec($CURL);
        curl_close($CURL);

        return $content;
    }
}
