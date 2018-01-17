<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * PosExchange for Point of Sale.
 */
class PosExchange extends ObjectModel
{

    /**
     *
     * @var int
     */
    public $id_order_old;

    /**
     *
     * @var int
     */
    public $id_order_new;

    /**
     *
     * @var int
     */
    public $id_cart_new = 0;

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'pos_exchange',
        'primary' => 'id_pos_exchange',
        'multilang' => false,
        'fields' => array(
            'id_order_old' => array('type' => self::TYPE_INT),
            'id_order_new' => array('type' => self::TYPE_INT),
            'id_cart_new' => array('type' => self::TYPE_INT),
        ),
    );

    /**
     *
     * @param int $id_order_old
     * @return \PosExchange
     */
    public static function getInstanceByOldOder($id_order_old)
    {
        $result = Db::getInstance(_PS_USE_SQL_SLAVE_)->getRow('
			SELECT *
			FROM `' . _DB_PREFIX_ . 'pos_exchange`
			WHERE `id_order_old` = ' . (int) $id_order_old);
        $exchange = new PosExchange();
        if (!empty($result)) {
            $exchange->hydrate($result, null);
        }
        return $exchange;
    }
    
    /**
     *
     * @param Prestashop Colection $order_collections
     * @return boolean
    */
    public static function isReturnExchangeOrder($order_collections)
    {
        $is_return_exchange = false;
        $id_orders = array();
        foreach ($order_collections as $order) {
            $id_orders[] = $order->id;
        }
        if (!empty($id_orders)) {
            $sql = new DbQuery();
            $sql->select('COUNT(*)');
            $sql->from('pos_exchange');
            $sql->where('`id_order_old` IN (' . implode(',', $id_orders) . ')');
            $is_return_exchange = (bool) Db::getInstance()->getValue($sql);
        }
        return $is_return_exchange;
    }
}
