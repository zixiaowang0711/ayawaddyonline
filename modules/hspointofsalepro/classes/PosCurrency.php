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
class PosCurrency extends Currency
{

    protected static $cache = array();

    /**
     * Similar to Currency::getCurrenciesByIdShop()
     * Use with care because we return (decimals * PS_PRICE_DISPLAY_PRECISION) rather than (decimals).
     *
     * @param int $id_shop
     *
     * @return array
     * <pre>
     * array(
     *    int => array(
     *               'id_currency' => int,
     *               'name' => string,
     *               'iso_code' => string,
     *               'iso_code_num' => string,
     *               'sign' => string,
     *               'blank' => int,
     *               'format' => int,
     *               'decimals' => int,
     *               'round_mode' => int
     *               // 0: Round up to the nearest value
     *               // 1: Round down to the nearest value
     *               // 2: Round up away from zero, when it is half way there (recommended)
     *               // 3: Round down towards zero, when it is half way there
     *               // 4: Round towards the next even value
     *               // 5: Round towards the next odd value
     *     ),
     * ...
     * )
     */
    public static function getAll($id_shop = null)
    {
        if (!isset(self::$cache['currencies'][(int) $id_shop])) {
            $round_mode = (int) Configuration::get('PS_PRICE_ROUND_MODE');
            $currences = parent::getCurrencies();
            foreach ($currences as &$currence) {
                $currence['decimals'] = isset($currence['decimals']) ? $currence['decimals'] * _PS_PRICE_DISPLAY_PRECISION_ : _PS_PRICE_DISPLAY_PRECISION_;
                $currence['round_mode'] = $round_mode;
            }
            self::$cache['currencies'][(int) $id_shop] = $currences;
        }
        return self::$cache['currencies'][(int) $id_shop];
    }

    /**
     * @param int $id_shop
     *
     * @return array // An item of self::getAll()
     */
    public static function getCurrentCurrency($id_shop = null)
    {
        $current_currency = array();
        $currencies = self::getAll($id_shop);
        foreach ($currencies as $currency) {
            if ($currency['id_currency'] == Context::getContext()->currency->id) {
                $current_currency = $currency;
            }
        }
        return $current_currency;
    }

    /**
     * Get Currency instance
     *
     * @param int $id Currency ID
     *
     * @return Currency
     */
    public static function getCurrencyInstance($id)
    {
        if (!isset(self::$currencies[$id])) {
            self::$currencies[(int) ($id)] = new PosCurrency($id);
        }
        $currency = self::$currencies[(int) ($id)];
        if ((int) $currency->decimals === 1) {
            $currency->decimals = $currency->decimals * _PS_PRICE_DISPLAY_PRECISION_;
            $currency->round_mode = (int) Configuration::get('PS_PRICE_ROUND_MODE');
            $currency->blank = 0;
        }
        return $currency;
    }
}
