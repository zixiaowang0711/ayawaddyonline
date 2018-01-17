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
class PosUpgrader412 extends PosUpgrader
{

    /**
     * @see parent::$configurations_to_install
     */
    protected $configurations_to_install = array(
        'POS_PRODUCT_ACTIVE' => 1,
        'POS_PRODUCT_INSTOCK' => 1
    );
}
