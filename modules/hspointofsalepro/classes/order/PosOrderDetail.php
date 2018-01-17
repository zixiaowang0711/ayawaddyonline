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
class PosOrderDetail extends OrderDetail
{

    /**
     *
     * @param type $quantity
     * @param type $delete
     * @return boolean
     */
    public function reinjectQuantity($quantity, $delete = false)
    {
        // Reinject product
        $reinjectable_quantity = (int) $this->product_quantity - (int) $this->product_quantity_reinjected;
        $quantity_to_reinject = min(array($quantity, $reinjectable_quantity));
        $product = new Product($this->product_id, false, (int) $this->context->language->id, (int) $this->id_shop);

        if (Configuration::get('PS_ADVANCED_STOCK_MANAGEMENT') && $product->advanced_stock_management && $this->id_warehouse != 0) {
            $manager = StockManagerFactory::getManager();
            $movements = StockMvt::getNegativeStockMvts(
                $this->id_order,
                $this->product_id,
                $this->product_attribute_id,
                $quantity_to_reinject
            );
            $left_to_reinject = $quantity_to_reinject;
            foreach ($movements as $movement) {
                if ($left_to_reinject > $movement['physical_quantity']) {
                    $quantity_to_reinject = $movement['physical_quantity'];
                }

                $left_to_reinject -= $quantity_to_reinject;
                if (Pack::isPack((int) $product->id)) {
                    if ($product->pack_stock_type == 1 ||
                        $product->pack_stock_type == 2 ||
                        ($product->pack_stock_type == 3 && Configuration::get('PS_PACK_STOCK_TYPE') > 0)
                    ) {
                        $products_pack = Pack::getItems((int) $product->id, (int) Configuration::get('PS_LANG_DEFAULT'));
                        foreach ($products_pack as $product_pack) {
                            if ($product_pack->advanced_stock_management == 1) {
                                $manager->addProduct(
                                    $product_pack->id,
                                    $product_pack->id_pack_product_attribute,
                                    new Warehouse($movement['id_warehouse']),
                                    $product_pack->pack_quantity * $quantity_to_reinject,
                                    null,
                                    $movement['price_te'],
                                    true
                                );
                            }
                        }
                    }
                    if ($product->pack_stock_type == 0 ||
                        $product->pack_stock_type == 2 ||
                        ($product->pack_stock_type == 3 && (Configuration::get('PS_PACK_STOCK_TYPE') == 0 ||
                        Configuration::get('PS_PACK_STOCK_TYPE') == 2))
                    ) {
                        $manager->addProduct(
                            $this->product_id,
                            $this->product_attribute_id,
                            new Warehouse($movement['id_warehouse']),
                            $quantity_to_reinject,
                            null,
                            $movement['price_te'],
                            true
                        );
                    }
                } else {
                    $manager->addProduct(
                        $this->product_id,
                        $this->product_attribute_id,
                        new Warehouse($movement['id_warehouse']),
                        $quantity_to_reinject,
                        null,
                        $movement['price_te'],
                        true
                    );
                }
            }

            $id_product = $this->product_id;
            if ($delete) {
                $this->delete();
            }
            StockAvailable::synchronize($id_product);
        } elseif ($this->id_warehouse == 0) {
            StockAvailable::updateQuantity(
                $this->product_id,
                $this->product_attribute_id,
                $quantity_to_reinject,
                $this->id_shop
            );

            if ($delete) {
                $this->delete();
            }
        } else {
            return false;
        }
        return true;
    }

    /**
     *
     * @return int
     */
    public function getIdTax()
    {
        $sql = new DbQuery();
        $sql->select('id_tax');
        $sql->from('order_detail_tax');
        $sql->where('`id_order_detail` =' . (int) $this->id);
        return Db::getInstance()->getValue($sql);
    }

    /**
     *
     * @param int $id_tax
     * @param float $unit_amount
     * @param float $total_amount
     * @return boolean
     */
    public function addOrderDetailTax($id_tax, $unit_amount, $total_amount)
    {
        return Db::getInstance()->execute(
            'INSERT INTO `'._DB_PREFIX_.'order_detail_tax` (id_order_detail, id_tax, unit_amount, total_amount) VALUES ('.(int) $this->id.', '.(int) $id_tax.', '.(float) $unit_amount.', '.(float) $total_amount.')'
        );
    }

    /**
     *
     * @param int $id_product
     * @return string
     */
    public static function getCustomProductName($id_product)
    {
        $query = new DbQuery();
        $query->select('`product_name`');
        $query->from('order_detail');
        $query->where('`product_id`=' .(int) $id_product);
        return Db::getInstance()->getValue($query);
    }
}
