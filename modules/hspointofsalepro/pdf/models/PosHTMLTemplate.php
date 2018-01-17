<?php
/**
 * RockPOS - Point of Sale for PrestaShop
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Generate ticket template, mostly for receipts and invoices
 */
abstract class PosHTMLTemplate extends HTMLTemplate
{

    /**
     * Returns main ticket template associated to the country iso_code.
     *
     * @param string $iso_country
     *
     * @return string
     */
    protected function getTemplateByCountry($iso_country)
    {
        $file = 'content';
        $template = $this->getTemplate($file . '-' . $iso_country);
        if (!$template) {
            $template = $this->getTemplate($file);
        }
        return $template;
    }

    /**
     * @see parent::getTemplate()
     * Overrides:<br/>
     * - Allow to override PDF templates at fron office
     * - Allow to override PDF templates at back office
     */
    protected function getTemplate($template_name)
    {
        $template = false;

        $overridden_templates = array();
        if (isset($this->context->controller->module)) {
            $module_name = Context::getContext()->controller->module->name;
            $overridden_templates[] = _PS_THEME_DIR_ . "pdf/$module_name/" . $template_name . '.tpl'; // At front-end's theme
            //$overridden_templates[] = _PS_BO_ALL_THEMES_DIR_ . $this->context->employee->bo_theme . "/pdf/$module_name/" . $template_name . '.tpl'; // At back-end's theme
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
}
