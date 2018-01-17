<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Extended Tools for RockPOS.
 */
class PosTaxRulesGroup extends TaxRulesGroup
{
   
    /**
    * @return array
    */
    public static function getAssociatedTaxRates($id_country, $id_sate = 0)
    {
        $query = new DbQuery();
        $query->select('rg.`id_tax_rules_group`');
        $query->select('rg.`name`');
        $query->from('tax_rules_group', 'rg');
        $query->leftJoin('tax_rule', 'tr', 'tr.`id_tax_rules_group` = rg.`id_tax_rules_group`');
        $query->leftJoin('tax', 't', 't.`id_tax` = tr.`id_tax`');
        $query->where('tr.`id_country` = '. (int) $id_country);
        $query->where('tr.`id_state` = '. (int) $id_sate.' || tr.`id_state` = 0');
        return Db::getInstance()->executeS($query);
    }
}
