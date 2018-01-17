<?php
/**
 * RockPOS Multiple Carts
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}
/**
 *
 */
class PosActiveCart
{

    /**
     * Limit sercords of active cart.
     *
     * @var int
     */
    public static $limit = 10;

    /**
     *
     * @var string
     */
    protected static $datetime_format = 'Y-m-d H:i:00';

    /**
     * Remove cart.
     *
     * @param ing $id_cart
     *
     * @return boolean
     */
    public static function removeByIdCart($id_cart)
    {
        return Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ . 'pos_cart` WHERE `id_cart`=' . (int) $id_cart);
    }

    /**
     * Get all active carts.
     *
     * @param int $exclude_id_cart
     * @param int $id_shop
     *
     * @return array
     *               array<pre>
     *               (
     *               [0] => int,
     *               [1] => int,
     *               ...
     *               )</pre>
     */
    public static function getActiveCarts($exclude_id_cart, $id_shop)
    {
        $db_query = new DbQuery();
        $db_query->select('`id_cart`');
        $db_query->from('pos_cart');
        $db_query->orderBy('`id_cart` DESC');
        $db_query->where('`id_shop` = ' . (int) $id_shop);
        $db_query->where('`order_reference` = \'\'');
        $db_query->where($exclude_id_cart ? ('`id_cart` != ' . (int) $exclude_id_cart) : null);

        if ((int) Configuration::get('POS_DELAY_TIME') > 0) {
            $db_query->where("`date_upd` > '" . pSQL(date(self::$datetime_format, time() - (int) Configuration::get('POS_DELAY_TIME') * 3600)) . "'");
        }
        if (Configuration::get('POS_ACTIVE_CART_LIMIT') > 0) {
            $db_query->limit(Configuration::get('POS_ACTIVE_CART_LIMIT'));
        }
        $results = Db::getInstance()->executeS($db_query);
        $id_carts = array();
        if ($results) {
            foreach ($results as $result) {
                $id_carts[] = $result['id_cart'];
            }
        }
        return $id_carts;
    }

    /**
     * Push (if not exist) a new active cart to this storage
     * @param int $id_cart
     * @param int $id_employee
     * @param int $id_shop
     * @param float $shipping
     * @return boolean
     */
    public static function push($id_cart, $id_employee, $id_shop, $shipping)
    {
        $values = func_get_args();
        $values[] = '"' . date(self::$datetime_format, time()) . '"';
        return Db::getInstance()->execute('REPLACE INTO `' . _DB_PREFIX_ . 'pos_cart` (`id_cart`, `id_employee`, `id_shop`, `shipping`, `date_upd`) VALUES (' . implode(', ', $values) . ')');
    }

    /**
     *
     * @param int $id_cart
     * @return float
     */
    public static function getShippingCost($id_cart)
    {
        $db_query = new DbQuery();
        $db_query->select('`shipping`');
        $db_query->from('pos_cart');
        $db_query->where('`id_cart` = ' . (int) $id_cart);
        return (float) Db::getInstance()->getValue($db_query);
    }

    /**
     *
     * @param int $id_cart
     * @param float $shipping_cost
     * @return boolean
     */
    public static function setShippingCost($id_cart, $shipping_cost)
    {
        return Db::getInstance()->execute(
            'UPDATE `' . _DB_PREFIX_ . 'pos_cart`
            SET `shipping` = ' . (float) $shipping_cost . '
            WHERE `id_cart` =' . (int) $id_cart
        );
    }

    /**
     *
     * @param int $id_cart
     * @param int $id_shop
     * @return int
     */
    public static function doesExistPosCart($id_cart, $id_shop)
    {
        $db_query = new DbQuery();
        $db_query->select('`id_cart`');
        $db_query->from('pos_cart');
        $db_query->where('`id_cart` = ' . (int) $id_cart);
        $db_query->where('`id_shop` = ' . (int) $id_shop);
        return (int) Db::getInstance()->getValue($db_query);
    }

    /**
     *
     * @param int $id_cart
     * @param int $id_shop
     * @param string $note
     * @param int $public
     * @return boolean
     */
    public static function updateNote($id_cart, $id_shop, $note, $public)
    {
        return Db::getInstance()->execute(
            'UPDATE `' . _DB_PREFIX_ . 'pos_cart`
            SET `note` = \'' . pSQL($note) . '\', `public` = ' . (int) $public . '
            WHERE `id_cart` = ' . (int) $id_cart . ' AND id_shop = ' . (int) $id_shop
        );
    }

    /**
     *
     * @param int $id_cart
     * @param int $id_shop
     * @param string $order_reference
     * @return array
     * <pre>
     * array(
     *  note => string
     *  public => int
     * )
     */
    public static function getNote($id_cart, $id_shop, $order_reference = null)
    {
        $db_query = new DbQuery();
        $db_query->select('`note`');
        $db_query->select('`public`');
        $db_query->from('pos_cart');
        $db_query->where('`id_cart` = ' . (int) $id_cart);
        $db_query->where('`id_shop` = ' . (int) $id_shop);
        if (!is_null($order_reference)) {
            $db_query->where('`order_reference` = \'' . pSQL($order_reference) . '\'');
        }
        $note = Db::getInstance()->getRow($db_query);
        return empty($note) ? array('note' => null, 'public' => 0) : $note;
    }

    /**
     *
     * @param int $id_cart
     * @param int $id_shop
     * @param string $order_reference
     * @return boolean
     */
    public static function updateOrderReference($id_cart, $id_shop, $order_reference)
    {
        return Db::getInstance()->execute(
            'UPDATE `' . _DB_PREFIX_ . 'pos_cart`
            SET `order_reference` = \'' . pSQL($order_reference) . '\'
            WHERE `id_cart` = ' . (int) $id_cart . ' AND id_shop = ' . (int) $id_shop
        );
    }
}
