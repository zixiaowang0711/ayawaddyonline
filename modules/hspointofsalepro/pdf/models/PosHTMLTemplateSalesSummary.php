<?php
/**
 * RockPOS - Point of Sale for PrestaShop
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 *
 */
class PosHTMLTemplateSalesSummary extends PosHTMLTemplate
{

    public $sales_summary;

    /**
     *
     * @param PosSalesSummary $pos_sales_summary
     * @param Smarty $smarty
     */
    public function __construct(PosSalesSummary $pos_sales_summary, Smarty $smarty)
    {
        $this->sales_summary = $pos_sales_summary;
        $this->smarty = $smarty;
        $this->context = Context::getContext();
    }

    public function getFilename()
    {
        return 'sales_summary_' . $this->sales_summary->date_from . '_' . $this->sales_summary->date_to . '.pdf';
    }

    public function getBulkFilename()
    {
    }

    /**
     * @see parent::getTemplate()
     * Overrides:<br/>
     * - Use specific folder for invoice
     */
    protected function getTemplate($template_name)
    {
        $template_name = 'sales_summary/' . $template_name;
        $overridden_templates = array();
        if (isset($this->context->controller->module)) {
            $module_name = Context::getContext()->controller->module->name;
            $overridden_templates[] = _PS_THEME_DIR_ . "pdf/$module_name/" . $template_name . '.tpl'; // At front-end's theme
        }
        $overridden_templates[] = _ROCKPOS_PDF_TPL_DIR_ . '/' . $template_name . '.tpl'; // In RockPOS itself
        foreach ($overridden_templates as $overridden_template) {
            if (file_exists($overridden_template)) {
                $template = $overridden_template;
                break;
            }
        }
        return $template;
    }

    public function getContent()
    {
        $this->context->smarty->assign(array(
            'pos_sales_summary' => $this->sales_summary,
            'generate_time' => date('M jS Y h:i'),
            'tax_enabled' => Configuration::get('PS_TAX'),
            'employee' => $this->context->employee
        ));
        return $this->smarty->fetch($this->getTemplate('content'));
    }

    /**
     *
     * @return string
     */
    public function getHeader()
    {
        $this->assignCommonHeaderData();
        $shop_address = $this->getShopAddress();
        $this->context->smarty->assign(array(
            'shop_address' => trim(str_replace(array(Configuration::get('PS_SHOP_NAME', null, null, $this->context->shop->id)), '', $shop_address)),
        ));

        return $this->smarty->fetch($this->getTemplate('header'));
    }

    /**
     *
     * @return string
     */
    protected function getShopAddress()
    {
        $shop_address = '';
        // Due to some unknown reasons, on some stores, $this->shop cannot be loaded
        if (empty($this->shop)) {
            $this->setShopId();
        }
        $shop_address_obj = $this->shop->getAddress();
        if (isset($shop_address_obj) && $shop_address_obj instanceof Address) {
            $shop_address = PosAddressFormat::generateAddress($shop_address_obj, array(), ' ');
        }
        return $shop_address;
    }
}
