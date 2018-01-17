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
class PosAddressFormat extends AddressFormat
{

    /**
     * Reformat POS full address text.
     *
     * @param Address $address
     * @param array   $pattern_rules A defined rules array to avoid some pattern
     * @param string  $new_line      A string containing the newLine format
     * @param string  $separator     A string containing the separator format
     * @param array   $style
     *
     * @return string
     */
    public static function generateAddress(Address $address, $pattern_rules = array(), $new_line = "\r\n", $separator = ' ', $style = array())
    {
        $default_address_instance = PosAddress::generateDefaultInstance($address->id_country);
        foreach (get_object_vars($address) as $field => $value) {
            if (isset($default_address_instance->$field) && $default_address_instance->$field == $value) {
                $address->$field = null; // unset dummy value
            }
        }
        return AddressFormat::generateAddress($address, $pattern_rules, $new_line, $separator, $style);
    }
}
