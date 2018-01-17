<?php
/**
 * Prestashop module for Oct8ne
 *
 * @category  Prestashop
 * @category  Module
 * @author    Prestaquality.com
 * @copyright 2016 Prestaquality
 * @license   Commercial license see license.txt
 * Support by mail  : info@prestaquality.com
 */

class Oct8neHistory extends ObjectModel
{

    public $id_oct8nehistory;
    public $cart_id;
    public $session_id;

    public static $definition = array(
        'table' => 'oct8nehistory',
        'primary' => 'id_oct8nehistory',
        'fields' => array(
            'cart_id' => array('type' => self::TYPE_INT, 'required' => true),
            'session_id' => array('type' => self::TYPE_STRING,'required' => true )

        )
    );

    public function existsIdCart($id_cart)
    {

        $query = new DbQuery();
        $query->select('*')
            ->from(self::$definition['table'])
            ->where('cart_id =' . (int)pSQL($id_cart));

        $result =  db::getInstance()->executeS($query);

        if (empty($result)) {
            return false;
        } else {
            return true;
        }
    }

    public function getRelatedData($from, $to)
    {

        $query = new DbQuery();
        $query->select('o.cart_id,o.session_id,r.id_order,c.id_customer, c.date_add, c.date_upd')
            ->from(self::$definition['table'], "o")
            ->leftJoin("orders", "r", "o.cart_id = r.id_cart")
            ->innerJoin("cart", "c", "o.cart_id = c.id_cart")
            ->where("c.date_add BETWEEN '" . pSQL($from) . "' AND '" . pSQL($to) . "'");

        $result =  db::getInstance()->executeS($query);

        return $result;

    }
}
