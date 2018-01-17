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
class PosCustomizationData
{

    /**
     *
     * @param int $id_customization
     * @return array
     * <pre>
     * array(
     *  int => array(
     *      id_customization => int,
     *      type => int,
     *      index => int,
     *      value => int,
     *  )
     * )
     */
    public static function getCustomizationDataById($id_customization)
    {
        $sql = new DbQuery();
        $sql->select('*');
        $sql->from('customized_data');
        $sql->where('`id_customization` =' . (int) $id_customization);
        return Db::getInstance()->executeS($sql);
    }

    /**
     *
     * @param int $id_customization
     * @return boolean
     */
    public static function delete($id_customization, $index = 0)
    {
        return Db::getInstance()->execute(
            'DELETE FROM `' . _DB_PREFIX_ . 'customized_data`
            WHERE `id_customization` = ' . (int) $id_customization .
            ((int) $index ? ' AND `index` = ' . (int) $index : '')
        );
    }

    /**
     *
     * @param int $index
     * @param int $type
     * @param string $value
     * @param int $id_customization
     * @return boolean
     */
    public static function add($index, $type, $value, $id_customization)
    {
        return Db::getInstance()->execute(
            'REPLACE INTO `' . _DB_PREFIX_ . 'customized_data` (`id_customization`, `type`, `index`, `value`)
            VALUES (' . (int) $id_customization . ', ' . (int) $type . ', ' . (int) $index . ', \'' . pSQL($value) . '\')'
        );
    }
}
