<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Extend OrderInvoice for RockPOS.
 */
class PosOrderInvoice extends OrderInvoice
{
    /**
     * @var PosOrder
     */
    private $order;

    /**
     * @see parent::getOrder()
     * Overrides:<br/>
     * - Return an instance of PosOrder (instead of Order)
     *
     * @return PosOrder
     */
    public function getOrder()
    {
        if (!$this->order) {
            $this->order = new PosOrder($this->id_order);
        }

        return $this->order;
    }

    /**
     * @see parent::getProducts()
     * Overrides:<br/>
     * - Add extra information by utlizing PosOrder::getProducts()
     */
    public function getProducts($products = false, $selected_products = false, $selected_qty = false)
    {
        if (!$products) {
            $products = parent::getProducts($products, $selected_products, $selected_qty);
        }

        return $this->getOrder()->getProducts($products, $selected_products, $selected_qty);
    }
}
