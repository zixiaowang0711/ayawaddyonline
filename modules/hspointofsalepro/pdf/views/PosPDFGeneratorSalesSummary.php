<?php
/**
 * RockPOS - Point of Sale for PrestaShop
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Rendering invoices in PDF
 */
class PosPDFGeneratorSalesSummary extends PosPDFGenerator
{

    protected function setPdfMargins()
    {
        $this->SetHeaderMargin(15);
        $this->SetFooterMargin(15);
        $this->setMargins(40, 30);
    }
}
