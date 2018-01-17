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

require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpOrderState.php';

class AdminErpStatusOrderController extends ModuleAdminController
{
    /*
     * By default, we use StockMvtReason as the table / className
     */
    public function __construct()
    {
        $this->context = Context::getContext();
        $this->table = 'erp_order_state';
        $this->className = 'ErpOrderState';
        $this->identifier = 'id_erp_order_state';
        $this->_defaultOrderBy = 'id_erp_order_state';
        $this->lang = true;
        $this->delete = false;
        $this->list_no_link = true;
        $this->_orderBy = null;
        $this->context = Context::getContext();
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->bootstrap = true;
        }

        $this->addRowAction('edit');

        //$this->bulk_actions = array('delete' => array('text' => $this->l('Delete selected'), 'confirm' => $this->l('Delete selected items?')));

        $this->fields_list = array(
            'name' => array(
                'title' => $this->l('Name'),
                'color' => 'color',
            ),
            'editable' => array(
                'title' => $this->l('Editable?'),
                'align' => 'center',
                'active' => 'editable',
                'type' => 'bool',
                'orderby' => false,
                'class' => 'fixed-width-sm',
                'ajax' => true
            ),
            'delivery_note' => array(
                'title' => $this->l('Is there a delivery note available?'),
                'align' => 'center',
                'active' => 'deliveryNote',
                'type' => 'bool',
                'orderby' => false,
                'class' => 'fixed-width-sm',
                'ajax' => true
            ),
            'pending_receipt' => array(
                'title' => $this->l('Is there a pending receipt?'),
                'align' => 'center',
                'active' => 'pendingReceipt',
                'type' => 'bool',
                'orderby' => false,
                'class' => 'fixed-width-sm',
                'ajax' => true
            ),
            'receipt_state' => array(
                'title' => $this->l('Delivery state?'),
                'align' => 'center',
                'active' => 'receiptState',
                'type' => 'bool',
                'orderby' => false,
                'class' => 'fixed-width-sm',
                'ajax' => true
            ),
            'enclosed' => array(
                'title' => $this->l('Enclosed order state?'),
                'align' => 'center',
                'active' => 'enclosed',
                'type' => 'bool',
                'orderby' => false,
                'class' => 'fixed-width-sm',
                'ajax' => true
            ),
        );

        return parent::__construct();
    }

    /**
     * AdminController::renderForm() override
     * @see AdminController::renderForm()
     */
    public function renderForm()
    {
        if (!($obj = $this->loadObject(true))) {
            return;
        }

        if (Tools::isSubmit('adderp_order_state') ||
            Tools::isSubmit('updateerp_order_state') ||
            Tools::isSubmit('submitAdderp_order_state') ||
            Tools::isSubmit('submitUpdateerp_order_state')) {
            $this->fields_form = array(
                    'legend' => array(
                        'title' => $this->l('Erp Order Status'),
                        'image' => '../img/admin/edit.gif'
                    ),
                    'input' => array(
                        array(
                            'type' => 'text',
                            'lang' => true,
                            'label' => $this->l('Status:'),
                            'name' => 'name',
                            'size' => 50,
                            'required' => true
                        ),
                        array(
                            'type' => 'color',
                            'label' => $this->l('Color:'),
                            'name' => 'color',
                            'size' => 20,
                            'desc' => $this->l('Back Office background will be displayed in this color. HTML colors only.'),
                        ),
                        array(
                            'type' => (version_compare(_PS_VERSION_, '1.6', '<')) ? 'radio' : 'switch',
                            'label' => $this->l('Editable:'),
                            'name' => 'editable',
                            'required' => true,
                            'class' => 't',
                            'is_bool' => true,
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('Yes')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('No')
                                )
                            ),
                            'desc' => $this->l('Define if it is possible to edit the order. An editable order is not valid to send to the supplier.')
                        ),
                        array(
                            'type' => (version_compare(_PS_VERSION_, '1.6', '<')) ? 'radio' : 'switch',
                            'label' => $this->l('Delivery note:'),
                            'name' => 'delivery_note',
                            'required' => true,
                            'class' => 't',
                            'is_bool' => true,
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('Yes')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('No')
                                )
                            ),
                            'desc' => $this->l('Define if it is possible to generate a delivery note of the order.')
                        ),
                        array(
                            'type' => (version_compare(_PS_VERSION_, '1.6', '<')) ? 'radio' : 'switch',
                            'label' => $this->l('Delivery state:'),
                            'name' => 'receipt_state',
                            'required' => true,
                            'class' => 't',
                            'is_bool' => true,
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('Yes')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('No')
                                )
                            ),
                            'desc' => $this->l('Define if products have been partially/completely received. This allows you to know if the products ordered have to be added to the corresponding warehouse.'),
                        ),
                        array(
                            'type' => (version_compare(_PS_VERSION_, '1.6', '<')) ? 'radio' : 'switch',
                            'label' => $this->l('Pending receipt:'),
                            'name' => 'pending_receipt',
                            'required' => true,
                            'class' => 't',
                            'is_bool' => true,
                            'values' => array(
                                array(
                                    'id' => 'active_on',
                                    'value' => 1,
                                    'label' => $this->l('Yes')
                                ),
                                array(
                                    'id' => 'active_off',
                                    'value' => 0,
                                    'label' => $this->l('No')
                                )
                            ),
                            'desc' => $this->l('Customer is awaiting delivery')
                        ),
                    ),
                    'submit' => array(
                        'title' => $this->l('Save')
                    ));
        }
        return parent::renderForm();
    }
    
    protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
    	if (version_compare(_PS_VERSION_, '1.7', '<')) {
            return parent::l($string, $class, $addslashes, $htmlentities);
    	} else {
            return Translate::getModuleTranslation('wic_erp', $string, 'AdminErpStatusOrderController');
    	}
    }
}
