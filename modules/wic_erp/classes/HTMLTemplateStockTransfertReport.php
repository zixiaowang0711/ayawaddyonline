<?php
/**
 * Module Supply | Web in Color
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade this module
 *
 *   @author    Web in Color <addons@webincolor.fr>
 *   @copyright Copyright &copy; 2015, Web In Color
 *   @license   http://www.webincolor.fr
 */

/**
 * @since 1.0.2
 */
class HTMLTemplateStockTransfertReport extends HTMLTemplate
{
    public $products;
    public $context;

    public function __construct($products_list, $smarty)
    {
        $this->products = $products_list;
        $this->smarty = $smarty;
        $this->context = Context::getContext();
        $this->available_in_your_account = false;

        // header informations
        $this->date = Tools::displayDate(date('Y-m-d'));
        $this->title = self::l('Stock transfert Report');
    }

    /**
     * @see HTMLTemplate::getContent()
     */
    public function getContent()
    {
        $employee = new Employee((int)$this->context->employee->id);
        $this->smarty->assign(array(
            'products' => $this->products,
            'date' => $this->date,
            'employee' => $employee->lastname.' '.$employee->firstname,
        ));

        return $this->smarty->fetch($this->getTemplate('stock-transfert-report'));
    }

    /**
     * @see HTMLTemplate::getBulkFilename()
     */
    public function getBulkFilename()
    {
        return 'stock-transfert.pdf';
    }

    /**
     * @see HTMLTemplate::getFileName()
     */
    public function getFilename()
    {
        return self::l('stock-transert-report').date('Y-m-d').'.pdf';
    }

    protected function getTemplate($template_name)
    {
        $template = false;
        $default_template = _PS_PDF_DIR_.$template_name.'.tpl';
        $overriden_template = _PS_MODULE_DIR_.'wic_erp/views/templates/admin/pdf/'.$template_name.'.tpl';

        if (file_exists($overriden_template)) {
            $template = $overriden_template;
        } elseif (file_exists($default_template)) {
            $template = $default_template;
        }

        return $template;
    }
}
