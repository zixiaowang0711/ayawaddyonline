<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Extended Context for RockPOS.
 */
class PosContext extends Context
{
    /**
     * Reset element(s) of Context to reflect RockPOS classes instead of PrestaShop classes<br/>
     * This method should be called right after "Cart::save()".
     */
    public static function resetContext()
    {
        if (!empty(self::getContext()->cart) && !self::getContext()->cart instanceof PosCart) {
            self::getContext()->cart = new PosCart(self::getContext()->cart->id);
        }
    }
}
