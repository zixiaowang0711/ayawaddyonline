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
class PosImageType extends ImageType
{

    public static function getFormattedName($name)
    {
        return method_exists('ImageType', 'getFormattedName') ? parent::getFormattedName($name) : parent::getFormatedName($name);
    }
}
