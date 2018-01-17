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
class PosState extends State
{

    /**
     * @param int $id_country
     *
     * @return int
     */
    public static function getDefaultState($id_country)
    {
        $default_state = 0;
        $states = self::getStatesByIdCountry($id_country);
        if (!empty($states)) {
            $state = current($states);
            $default_state = (int) $state['id_state'];
        }
        return $default_state;
    }
}
