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
class SearchByManufacture extends SearchAbstract
{

    /**
     * @return array
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
     *               [1] =>Array()
     *               ...
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
        $product_query->innerJoin('product_lang', 'pl', 'p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' .(int) $this->id_lang. ' AND pl.`id_shop` = '. (int) $this->shop->id);
        $product_query->innerJoin('manufacturer', 'm', 'p.`id_manufacturer` = m.`id_manufacturer`');
        $product_query->innerJoin('manufacturer_lang', 'ml', 'ml.`id_manufacturer` = m.`id_manufacturer` AND ml.`id_lang` = ' . (int) $this->id_lang);
        $product_query->innerJoin('manufacturer_shop', 'ms', 'ms.`id_manufacturer` = m.`id_manufacturer` AND ms.`id_shop` = ' . (int) $this->shop->id);
        $product_query->join(Product::sqlStock('p', 0, true, $this->shop));
        $where = array();
        $where[] = 'm.`name` LIKE \'%'.pSQL($this->keyword).'%\'';
        $where_fields = array('description', 'short_description', 'meta_keywords', 'meta_description');
        foreach ($where_fields as $where_field) {
            $where[] = 'ml.`'.$where_field.'` LIKE \'%'.pSQL($this->keyword).'%\'';
        }
        $product_visibilities = PosProduct::getProductVisibilities();
        $product_query->where(!empty($product_visibilities) ? 'p.`visibility` IN ("' . implode('","', $product_visibilities) . '")' : null);
        $product_query->where(!Configuration::get('POS_PRODUCT_INACTIVE') ? 'product_shop.`active` = 1' : null);
        if (Configuration::get('PS_STOCK_MANAGEMENT')) {
            $product_query->where(Configuration::get('POS_PRODUCT_OUT_OF_STOCK') ? null : 'stock.`quantity` > 0');
        }
        $product_query->where(implode(' OR ', $where));
        $product_query->groupBy('p.`id_product`');
        return Db::getInstance()->executeS($product_query);
    }
}
