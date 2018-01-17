<?php
/*
* 2007-2016 PrestaShop
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
*  @author PrestaShop SA <contact@prestashop.com>
*  @copyright  2007-2016 PrestaShop SA
*  @license    http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
*  International Registered Trademark & Property of PrestaShop SA
*/

class ChequeOverride extends Cheque
{
    public function hookPaymentReturn($params)
    {
        if (!$this->active)
            return;
        
        $status = array(Configuration::get('PS_OS_CHEQUE'), Configuration::get('PS_OS_OUTOFSTOCK'), Configuration::get('PS_OS_OUTOFSTOCK_UNPAID'), Configuration::get('WIC_STATE_ERP_COMPLETE'));
		
        if (Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS')) {
            $status = array_merge($status, explode(',', Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS')));
        }
    	 
        $state = $params['objOrder']->getCurrentState();

        if (in_array($state, $status))
        {
            $this->smarty->assign(array(
                'total_to_pay' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
                'chequeName' => $this->chequeName,
                'chequeAddress' => Tools::nl2br($this->address),
                'status' => 'ok',
                'id_order' => $params['objOrder']->id
            ));
            if (isset($params['objOrder']->reference) && !empty($params['objOrder']->reference))
                $this->smarty->assign('reference', $params['objOrder']->reference);
        }
        else
            $this->smarty->assign('status', 'failed');
        return $this->display(__FILE__, 'payment_return.tpl');
    }
}
