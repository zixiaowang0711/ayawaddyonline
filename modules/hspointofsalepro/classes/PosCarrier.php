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
class PosCarrier extends Carrier
{

    /**
     *
     * @param array $id_carries
     * <pre>
     * array(
     *  int => int,
     * ...
     * )
     * @return \PrestaShopCollection
     */
    public static function getCarrierCollection(array $id_carries)
    {
        $carrier_collection = new PrestaShopCollection('PosCarrier');
        if (!empty($id_carries)) {
            $carrier_collection->where('id_carrier', 'IN', $id_carries);
        }
        return $carrier_collection;
    }

    /**
     *
     * @return PosCarrier
     */
    public static function getPosCarrier()
    {
        $query = new DbQuery();
        $query->select('id_carrier');
        $query->from('carrier');
        $query->where('`is_module` = 1');
        $query->where('`external_module_name` = \'' . pSQL(PosConfiguration::getModuleName()) . '\'');
        $id_carrier = (int) Db::getInstance()->getValue($query);
        $carrier = new self($id_carrier);
        return $carrier;
    }
}
