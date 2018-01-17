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
class SearchByReference extends SearchAbstract
{

    /**
     *  @return array
     *               array = (<pre>
     *               [0] => Array
     *               (
     *                  [id_product] => int
     *                  [reference] => string
     *                  [id_shop] => int
     *                  [name] => string
     *                  [id_product_attribute] => int
     *                  [position] => int
     *                  [quantity] => int
     *                  [combination] => string
     *                  [stock] => int/string
     *                  [item] => string (items / item),
     *                  [has_combinations] => boolean
     *               )
     *               )</pre>
     */
    public function search()
    {
        $product_query = new DbQuery();
        $product_query->select('p.`id_product`');
        $product_query->select('IF(p.`cache_default_attribute` > 0, 1, 0) AS `has_combinations`');
        $product_query->select('p.`reference` AS `reference`');
        $product_query->select('pl.`name` AS `name`');
        $product_query->select('stock.`quantity`');
        $product_query->from('product', 'p');
        $product_query->join(Shop::addSqlAssociation('product', 'p'));
        $product_query->innerJoin('product_lang', 'pl', 'p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int) $this->id_lang. ' AND pl.`id_shop` = '. (int) $this->shop->id);
        $product_query->join(Product::sqlStock('p', 0, true, $this->shop));
        $where = array();
        $where[] = 'p.`reference` LIKE \'%'.pSQL($this->keyword).'%\'';
        if (Combination::isFeatureActive()) {
            $where[] = 'EXISTS(SELECT * FROM `'._DB_PREFIX_.'product_attribute` `pa` WHERE pa.`id_product` = p.`id_product` AND (pa.`reference` LIKE \'%'.pSQL($this->keyword).'%\'))';
        }
        $product_visibilities = PosProduct::getProductVisibilities();
        $product_query->where(!empty($product_visibilities) ? 'p.`visibility` IN ("' . implode('","', $product_visibilities) . '")' : null);
        $product_query->where(implode(' OR ', $where));
        $product_query->where(!Configuration::get('POS_PRODUCT_INACTIVE') ? 'product_shop.`active` = 1' : null);
        if (Configuration::get('PS_STOCK_MANAGEMENT')) {
            $product_query->where(Configuration::get('POS_PRODUCT_OUT_OF_STOCK') ? null : 'stock.`quantity` > 0');
        }
        return Db::getInstance()->executeS($product_query);
    }
}
