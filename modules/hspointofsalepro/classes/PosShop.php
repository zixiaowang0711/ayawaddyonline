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
class PosShop extends Shop
{
    /**
     * Get default shop.
     *
     * @return int
     */
    public static function getDefaultShopId()
    {
        return (int) Configuration::get('PS_SHOP_DEFAULT');
    }

    /**
     * Check active single shop or multi shop.
     *
     * @return bool
     */
    public static function isInSingleShop()
    {
        return !(Shop::isFeatureActive() && (Shop::getContext() == Shop::CONTEXT_ALL || Shop::getContext() == Shop::CONTEXT_GROUP));
    }

    /**
     * Returns the shop address.
     *
     * @return string
     */
    public function getAddress()
    {
        $address = parent::getAddress();
        $shop_address = PosAddressFormat::generateAddress($address, array(), ' - ', ' ');

        return $shop_address;
    }
}
