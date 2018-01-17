<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 *
 */
class PosSpecificPrice extends SpecificPrice
{

    /**
     * Override empty cache specific price after delete.
     *
     * @param int $id_cart
     * @param int $id_product           Default = false
     * @param int $id_product_attribute Default = false
     *
     * @return bool
     */
    public static function deleteByIdCart($id_cart, $id_product = false, $id_product_attribute = false)
    {
        $result = false;
        if ((int) $id_cart) {
            SpecificPrice::$_specificPriceCache = array();
            Product::flushPriceCache();
            $result = parent::deleteByIdCart((int) $id_cart, (int) $id_product, (int) $id_product_attribute);
        }

        return $result;
    }

    /**
     * Check existing specific price of product.
     *
     * @param int $id_cart
     * @param int $id_product
     * @param int $id_product_attribute
     *
     * @return booelen
     */
    public static function doesSpecificPriceExist($id_cart, $id_product, $id_product_attribute)
    {
        $query = '
                SELECT
                    count(*)
                FROM
                    `' . _DB_PREFIX_ . 'specific_price`
                WHERE
                    `id_cart` = ' . (int) $id_cart . '
                    AND `id_product` = ' . (int) $id_product . '
                    AND `id_product_attribute` = ' . (int) $id_product_attribute;

        return (bool) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query);
    }

    /**
     * Update new combination of product in cart.
     *
     * @param int $id_cart
     * @param int $id_product
     * @param int $new_id_product_attribute
     * @param int $old_id_product_attribute
     *
     * @return bool
     */
    public static function updateProductCombination($id_cart, $id_product, $new_id_product_attribute, $old_id_product_attribute)
    {
        $query = '
                UPDATE
                    `' . _DB_PREFIX_ . 'specific_price`
                SET
                    `id_product_attribute` = ' . (int) $new_id_product_attribute . '

                WHERE
                    `id_cart` = ' . (int) $id_cart . '
                    AND `id_product` = ' . (int) $id_product . '
                    AND `id_product_attribute` = ' . (int) $old_id_product_attribute;

        return Db::getInstance()->execute($query);
    }

    /**
     * Calculate price based on tax setting<br/>
     * This is helpful when we set a price directly.<br/>
     * If we set a reduction, set "$this->reduction_tax = null" to take this into account; otherwise, it accepts what passed from outside and don't care about tax.<br/>.
     */
    protected function calculatePrice()
    {
        if ($this->reduction_type == PosConstants::DISCOUNT_TYPE_PERCENTAGE) {
            return; // all's good with percentage
        }

        if (!Configuration::get('PS_TAX')) {
            $this->reduction_tax = 0;

            return;
        }

        $price_display_method = Context::getContext()->customer->getPriceDisplayMethod();
        if ($this->price == -1) {
            // reduction with specific amount
            if ($this->reduction_tax === null) {
                $this->reduction_tax = (int) ($price_display_method == PS_TAX_INC);
            }
        } elseif ($this->price >= 0) {
            // set a new  price
            if ($price_display_method == PS_TAX_INC) {
                $vat_address = new Address((int) Context::getContext()->cart->{Configuration::get('PS_TAX_ADDRESS_TYPE')}); // In RockPOS, cart always comes with id_address_delivery and id_address_invoice
                $id_tax_rules = (int) Product::getIdTaxRulesGroupByIdProduct($this->id_product);
                $tax_manager = TaxManagerFactory::getManager($vat_address, $id_tax_rules);
                $tax_calculator = $tax_manager->getTaxCalculator();
                // Price in this case is always considered as "Tax excluded"
                // Therefore, we have to remove tax from what's passed outside in
                $this->price = Tools::ps_round($tax_calculator->removeTaxes($this->price), _PS_PRICE_COMPUTE_PRECISION_);
                $this->reduction_tax = 0;
            }
        }
    }

    /**
     * @see parent::add()
     */
    public function add($autodate = true, $nullValues = false)
    {
        $this->calculatePrice();
        if (property_exists('SpecificPrice', '_filterOutCache')) {
            SpecificPrice::$_filterOutCache = array();
        }

        return parent::add($autodate, $nullValues);
    }

    /**
     * @see parent::update()
     */
    public function update($null_values = false)
    {
        $this->calculatePrice();

        return parent::update($null_values);
    }
}
