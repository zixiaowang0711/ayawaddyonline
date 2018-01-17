<?php
/**
 * RockPOS - Point of Sale for PrestaShop
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Mainly for generating invoice or receipt
 */
abstract class PosPDFGenerator extends PDFGenerator
{

    /**
     *
     * @param string $page_size A4, A5, K57, K80, etc...
     * @param boolean $use_cache
     * @param $orientation (string) page orientation. Possible values are (case insensitive):<br/>
     * <ul>
     *  <li>P or Portrait (default)</li>
     *  <li>L or Landscape</li>
     *  <li>'' (empty string) for automatic orientation</li>
     * </ul>
     */
    public function __construct($page_size, $use_cache = false, $orientation = PosConstants::ORIENTATION_PORTRAIT)
    {
        parent::__construct($use_cache);
        $this->page_size = $page_size;
        $this->setPageFormat($page_size, $orientation);
    }

    /**
     * An instance of PosHTMLTemplateXxx class
     * @param PosHTMLTemplate $template_object
     */
    public function setTemplateObject(PosHTMLTemplate $template_object)
    {
        $this->template_object = $template_object;
    }

    /**
     * @see parent::writePage()
     * Overrides:<br/>
     * - Introduce $this->setPdfMargins() so that we can be easy to change margins of different papers
     */
    public function writePage()
    {
        $this->setPdfMargins();
        $this->AddPage();
        $this->writeHTML($this->content, true, false, true, false, '');
    }
}
