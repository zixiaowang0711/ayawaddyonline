<?php
/**
 * RockPOS Custom sale
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 *
 */
class PosCustomProduct extends ObjectModel
{

    /**
     *
     * @var int
     */
    public $id_cart;

    /**
     *
     * @var int
     */
    public $id_product;

    /**
     *
     * @var datetime
     */
    public $date_add;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'pos_custom_product',
        'primary' => 'id_pos_custom_product',
        'fields' => array(
            'id_cart' => array('type' => self::TYPE_INT),
            'id_product' => array('type' => self::TYPE_INT),
            'date_add' => array('type' => self::TYPE_DATE)
        ),
    );

    /**
     *
     * @param int $id_cart
     * @return PrestashopCollection
     */
    public static function getProductCollectionByCart($id_cart)
    {
        $query = new DbQuery();
        $query->select('GROUP_CONCAT(`id_product`)');
        $query->from('pos_custom_product');
        $query->where('id_cart=' . (int) $id_cart);
        $result = Db::getInstance()->getValue($query);
        $product_collection = array();
        if ($result) {
            $product_collection = new PrestaShopCollection('Product');
            $product_collection->where('id_product', 'IN', explode(',', $result));
        }
        return $product_collection;
    }

    /**
     *
     * @param int $id_cart
     * @return boolean
     */
    public static function deleteByCart($id_cart)
    {
        return Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'pos_custom_product` WHERE `id_cart`=' . (int) $id_cart);
    }

    /**
     *
     * @param array $ids_product
     * <pre>
     * array(
     *  int,
     *  int,
     *  ...
     * )
     */
    protected static function deleteByIdProducts(array $ids_product)
    {
        return Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'pos_custom_product` WHERE `id_product` IN (' . implode(',', $ids_product) . ')');
    }

    public static function deleteOutOfDateProducts()
    {
        $time_to_live = Configuration::get('POS_CUSTOM_PRODUCT_TIME_TO_LIVE') ? Configuration::get('POS_CUSTOM_PRODUCT_TIME_TO_LIVE') : PosConstants::CUSTOM_PRODUCT_TIME_TO_LIVE;
        $query = new DbQuery();
        $query->select('GROUP_CONCAT(`id_product`)');
        $query->from(self::$definition['table']);
        $query->where('`date_add` <= DATE_SUB(NOW(), INTERVAL ' . (int) $time_to_live . ' DAY)');
        $id_custom_products = Db::getInstance()->getValue($query);

        if ($id_custom_products) {
            $id_products = explode(',', $id_custom_products);
            $product_collection = new PrestashopCollection('Product');
            $product_collection->where('id_product', 'IN', $id_products);
            if (count($product_collection) > 0) {
                foreach ($product_collection as $product) {
                    $product->delete();
                }
                self::deleteByIdProducts($id_products);
            }
        }
    }
}
