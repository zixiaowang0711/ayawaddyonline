<?php
/**
 * RockPOS - Point of Sale for PrestaShop
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Printing invoices
 */
class PosPDFSalesSummary extends PosPDF
{

    /**
     *
     * @param Object $objects
     * @param string $template
     * @param Smarty $smarty
     * @param string $page_size
     */
    public function __construct($objects, $template, Smarty $smarty, $page_size = 'A4')
    {
        parent::__construct($objects, $template, $smarty);
        $this->pdf_renderer = new PosPDFGeneratorSalesSummary($page_size, (bool) Configuration::get('PS_PDF_USE_CACHE'));
    }
}
