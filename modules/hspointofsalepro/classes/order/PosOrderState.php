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
class PosOrderState extends OrderState
{
    /**
     * Store list of ids order state completed sale.
     *
     * @var array
     */
    protected static $cache_id_order_states = array();

    /**
     * Get first order state id of point of sale.
     *
     * @return int
     */
    public static function getFistOrderStateId()
    {
        return Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('
					SELECT
                                            `id_order_state`
                                        FROM
                                            `' . _DB_PREFIX_ . 'order_state`
                                        WHERE
                                            `deleted` = 0
                                            AND `logable` = 1
                                            AND `delivery` = 1
                                        ORDER BY `id_order_state` DESC');
    }

    /**
     * Get id order states are shipped.
     *
     * @return array
     *               array
     *               ( <pre>
     *               [0] => int
     *               [1] => int
     *               )</pre>
     */
    public static function getShippedIdOrderStates()
    {
        if (empty(self::$cache_id_order_states)) {
            $sql = 'SELECT `id_order_state`
                    FROM `' . _DB_PREFIX_ . 'order_state`
                    WHERE `deleted` = 0
                            AND `logable` = 1
                            AND `shipped` = 1
                    ORDER BY `id_order_state` DESC';
            $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->ExecuteS($sql);
            $id_order_states = array();
            foreach ($results as $result) {
                $id_order_states[] = $result['id_order_state'];
            }
            self::$cache_id_order_states = $id_order_states;
        }

        return self::$cache_id_order_states;
    }

    /**
     * @param int $id_lang
     *
     * @return array
     * <pre>
     * array(
     *  int => array(
     *     id_order_state => int,
     *     name => string,
     *     checked => boolean
     *  )
     * ...
     * )
     */
    public static function getOrderStates($id_lang, $stand_order = true)
    {
        $format_order_states = array();
        $order_states = parent::getOrderStates((int) $id_lang);
        $selected_order_states = explode(',', Configuration::get('POS_SELECTED_STANDARD_ORDER_STATES'));
        $default_order_state = $stand_order ? (int) Configuration::get('POS_DEFAULT_STANDARD_ORDER_STATE') : (int) Configuration::get('POS_DEFAULT_PRE_ORDER_STATE');
        if (!empty($order_states)) {
            foreach ($order_states as $index => $order_state) {
                $format_order_states[$index]['id_order_state'] = (int) $order_state['id_order_state'];
                $format_order_states[$index]['name'] = $order_state['name'];
                $format_order_states[$index]['checked'] = (int) in_array($order_state['id_order_state'], $selected_order_states);
                $format_order_states[$index]['is_default'] = (int) ($order_state['id_order_state']) === $default_order_state ? 1 : 0;
            }
        }
        return $format_order_states;
    }
}
