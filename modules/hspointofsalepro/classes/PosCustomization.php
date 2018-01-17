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
class PosCustomization extends Customization
{

    /**
     *
     * @param int $id_customization
     * @return array
     * <pre>
     * array(
     *      id_customization => int,
     *      id_product_attribute => int,
     *      id_address_delivery => int,
     *      id_cart => int,
     *      id_product => int,
     *      quantity => int,
     *      quantity_refunded => int,
     *      quantity_returned => int,
     *      in_cart => int
     * )
     */
    public static function getCustomizationById($id_customization)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('customization');
        $sql->where('`id_customization` =' . (int) $id_customization);
        return Db::getInstance()->getRow($sql);
    }

    /**
     *
     * @param int $id_cart
     * @param int $id_product
     * @param int $id_product_attribute
     * @return int
     */
    public static function getTotalQuantity($id_cart, $id_product, $id_product_attribute)
    {
        $sql = new DbQuery();
        $sql->select('SUM(`quantity`) AS \'quantity\'');
        $sql->from('customization');
        $sql->where('`id_cart` =' . (int) $id_cart);
        $sql->where('`id_product` =' . (int) $id_product);
        $sql->where('`id_product_attribute` =' . (int) $id_product_attribute);
        return Db::getInstance()->getRow($sql);
    }

    /**
     *
     * @param int $id_customization
     * @return boolean
     */
    public static function delele($id_customization)
    {
        return Db::getInstance()->execute(
            'DELETE FROM `' . _DB_PREFIX_ . 'customization`
            WHERE `id_customization` = ' . (int) $id_customization
        );
    }

    /**
     *
     * @param int $id_cart
     * @param int $id_product
     * @param int $index
     * @return array
     * <pre>
     * array(
     *      id_customization => int,
     *      index => int,
     *      value => string,
     *      type => int,
     * )
     */
    public static function getCustomization($id_cart, $id_product, $index)
    {
        $sql = new DbQuery();
        $sql->select('cu.`id_customization`');
        $sql->select('cd.`index`');
        $sql->select('cd.`value`');
        $sql->select('cd.`type`');
        $sql->from('customization', 'cu');
        $sql->leftJoin('customized_data', 'cd', 'cu.`id_customization` = cd.`id_customization`');
        $sql->where('cu.`id_cart` = ' . (int) $id_cart);
        $sql->where('cu.`id_product` = ' . (int) $id_product);
        $sql->where('cd.`index` = ' . (int) $index);
        $sql->where('cu.`in_cart` = 1');
        return Db::getInstance()->getRow($sql);
    }

    /**
     *
     * @param type $id_cart
     * @return array
     * <pre />
     * array(
     *  int => PosCustomization
     * ...
     * )
     */
    public static function getCustomizationCollection($id_cart)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('customization');
        $sql->where('`id_cart` = ' . (int) $id_cart);
        $sql->where('`in_cart` = 1');
        $results = Db::getInstance()->executeS($sql);

        return self::hydrateCollection('PosCustomization', $results);
    }
}
