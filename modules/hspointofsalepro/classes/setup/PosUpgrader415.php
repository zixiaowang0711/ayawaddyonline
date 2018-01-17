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
class PosUpgrader415 extends PosUpgrader
{
    /**
     * @see parent::$configurations_to_install
     */
    protected $configurations_to_install = array(
        'POS_SHARE_YOUR_DATA' => 0,
        'POS_TIME_RUN_MIX_PANEL' => 0,
        'POS_DEFAULT_RETURN_ORDER_STATE' => PosConstants::DEFAULT_RETURN_ORDER_STATE,
        'POS_ADDRESS_ALIAS' => PosConstants::ADDRESS_ALIAS
    );
    
    protected $configuration_keys_to_rename = array(
        'POS_FILTER_PRODUCT_NAME_LENGTH' => 'POS_PRODUCT_NAME_LENGTH'
    );
    
    /**
     * @return bool
     */
    protected function installOthers()
    {
        $success = array();
        $current_pos_default_id_customer = (int) Configuration::get('POS_DEFAULT_ID_CUSTOMERS');
        if ($current_pos_default_id_customer > 0) {
            $success[] = Configuration::updateValue('POS_DEFAULT_ID_CUSTOMERS', '{"-1":"' . $current_pos_default_id_customer . '"}');
        }
        return array_sum($success) >= count($success);
    }
}
