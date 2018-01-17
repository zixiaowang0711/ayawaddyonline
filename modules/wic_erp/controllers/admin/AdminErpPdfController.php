<?php
/**
* Module My Easy ERP Web In Color 
* 
*  @author    Web In Color - addons@webincolor.fr
*  @version 2.6
*  @uses Prestashop modules
*  @since 1.0 - mai 2014
*  @package Wic ERP
*  @copyright Copyright &copy; 2014, Web In Color
*  @license   http://www.webincolor.fr
*/

class AdminErpPdfController extends ModuleAdminController
{
    public function postProcess()
    {
        parent::postProcess();

        // We want to be sure that displaying PDF is the last thing this controller will do
        exit;
    }

    public function initProcess()
    {
        parent::initProcess();
        $access = Profile::getProfileAccess($this->context->employee->id_profile, (int)Tab::getIdFromClassName('AdminOrders'));
        if ($access['view'] === '1' && ($action = Tools::getValue('submitAction'))) {
            $this->action = $action;
        } else {
            $this->errors[] = Tools::displayError('You do not have permission to view here.');
        }
    }

    public function processGenerateErpOrderFormPDF()
    {
        if (!Tools::isSubmit('id_erp_order')) {
            die(Tools::displayError('Missing supply order ID'));
        }

        $id_erp_order = (int)Tools::getValue('id_erp_order');
        $erp_order = new ErpOrder($id_erp_order);

        if (!Validate::isLoadedObject($erp_order)) {
            die(Tools::displayError('Cannot find this just in time order in the database'));
        }

        $this->generatePDF($erp_order, PDF::TEMPLATE_ERP_ORDER_FORM);
    }

    public function generatePDF($object, $template)
    {
        $pdf = new PDF($object, $template, Context::getContext()->smarty);
        $pdf->render();
    }
}
