<?php
/**
 * RockPOS - Point of Sale for PrestaShop
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * A part of PDF generator. This supports our own PDF Generator class and PDF templates.
 */
class PosPDF extends PDF
{

    /**
     * Construct
     * Implement:
     * + add js print.
     *
     * @param mixed $objects ObjectModel or array of ObjectModels
     * @param string $template
     * @param Smarty $smarty
     */
    public function __construct($objects, $template, Smarty $smarty)
    {
        $this->template = $template;
        $this->smarty = $smarty;
        $this->objects = $objects;
        if (!($objects instanceof Iterator) && !is_array($objects)) {
            $this->objects = array($objects);
        }
    }

    /**
     * @see parent::render()
     * Overrides:<br/>
     * - This function is here for the same reason as PosPDFGenerator::render().<br/>
     * - Combine header+content into content and pass to PosPDFGenerator::createContent().<br/>
     * - Pass template object to generator for processing data easily at view level. <br/>
     */
    public function render($display = true)
    {
        $render = false;
        $this->pdf_renderer->setFontForLang(Context::getContext()->language->iso_code);
        foreach ($this->objects as $object) {
            $template = $this->getTemplateObject($object);
            if ($template) {
                $this->setFileName($template);
                $template->assignHookData($object);
                $this->pdf_renderer->setTemplateObject($template);
                $this->assignHtml($template);
                $this->pdf_renderer->writePage();
                $render = true;
                unset($template);
            }
        }
        if ($render) {
            // clean the output buffer
            if (ob_get_level() && ob_get_length() > 0) {
                ob_clean();
            }
            return $this->pdf_renderer->render($this->filename, $display);
        }
    }

    /**
     *
     * @param PosHTMLTemplate $template
     */
    protected function setFileName(PosHTMLTemplate $template)
    {
        if (empty($this->filename)) {
            $this->filename = $template->getFilename();
            if (count($this->objects) > 1) {
                $this->filename = $template->getBulkFilename();
            }
        }
    }

    /**
     *
     * @param PosHTMLTemplate $template
     */
    protected function assignHtml(PosHTMLTemplate $template)
    {
        $this->pdf_renderer->createHeader($template->getHeader());
        $this->pdf_renderer->createFooter($template->getFooter());
        $this->pdf_renderer->createContent($template->getContent());
    }

    /**
     * @see parent::getTemplateObject()
     * Overrides:<br/>
     * - Use own own template classes
     */
    public function getTemplateObject($object)
    {
        $class = false;
        $class_name = 'PosHTMLTemplate' . $this->template;
        if (!isset($this->send_bulk_flag)) {
            $this->send_bulk_flag = true;
        }

        if (class_exists($class_name)) {
            // Some HTMLTemplateXYZ implementations won't use the third param but this is not a problem (no warning in PHP),
            // the third param is then ignored if not added to the method signature.
            $class = new $class_name($object, $this->smarty, $this->send_bulk_flag);

            if (!($class instanceof HTMLTemplate)) {
                throw new PrestaShopException('Invalid class. It should be an instance of HTMLTemplate');
            }
        }

        return $class;
    }
}
