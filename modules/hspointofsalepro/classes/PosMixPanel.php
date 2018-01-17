<?php
/**
 * RockPOS MixPanel
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
class PosMixPanel
{

    /**
     *
     * @return boolean
     */
    public static function updateTime()
    {
        return self::readyToGo() ? Configuration::updateValue('POS_TIME_RUN_MIX_PANEL', strtotime(" + 30 days")) : true;
    }

    public static function readyToGo()
    {
        return Configuration::get('POS_TIME_RUN_MIX_PANEL') <= strtotime("TODAY") && Configuration::get('POS_SHARE_YOUR_DATA');
    }
}
