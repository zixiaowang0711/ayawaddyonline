<?php

class BankWireOverride extends BankWire
{
    public function hookPaymentReturn($params)
    {
        if (!$this->active)
            return;

        $status = array(Configuration::get('PS_OS_BANKWIRE'), Configuration::get('PS_OS_OUTOFSTOCK'), Configuration::get('PS_OS_OUTOFSTOCK_UNPAID'), Configuration::get('WIC_STATE_ERP_COMPLETE'));
		
        if (Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS')) {
            $status = array_merge($status, explode(',', Configuration::get('WIC_ERP_COMPLETE_LIST_STATUS')));
        }
    	 
        $state = $params['objOrder']->getCurrentState();

        if (in_array($state, $status))
        {
            $this->smarty->assign(array(
                    'total_to_pay' => Tools::displayPrice($params['total_to_pay'], $params['currencyObj'], false),
                    'bankwireDetails' => Tools::nl2br($this->details),
                    'bankwireAddress' => Tools::nl2br($this->address),
                    'bankwireOwner' => $this->owner,
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