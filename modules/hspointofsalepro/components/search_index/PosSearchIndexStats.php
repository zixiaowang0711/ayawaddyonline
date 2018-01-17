<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Search index stats for Point of Sale.
 */
class PosSearchIndexStats extends PosSearchIndex
{
    /**
     * @param array $id_shops
     *                        array(<pre>
     *                        int,
     *                        int,
     *                        ...
     *                        )</pre>
     *
     * @return int
     */
    public static function getTotalIndexedProducts()
    {
        $sub_query = new DbQuery(); // Get id_words based on shops
        $sub_query->select('DISTINCT `id_word`');
        $sub_query->from('pos_search_word');
        $sub_query->where('`id_shop` IN (' . implode(',', Shop::getContextListShopID()) . ')');

        $query = new DbQuery(); // Count id_products based on id_words
        $query->select('COUNT(DISTINCT `id_product`)');
        $query->from('pos_search_index');
        $query->where('`id_word` IN (' . $sub_query->build() . ')');

        return (int) Db::getInstance()->getValue($query);
    }

    /**
     * @return int
     */
    public static function getTotalProducts()
    {
        $query = new DbQuery();
        $query->select('COUNT(p.`id_product`)');
        $query->from('product', 'p');
        $query->join(Shop::addSqlAssociation('product', 'p'));
        $query->where(!PosConfiguration::get('POS_PRODUCT_INACTIVE') ? 'p.`active` = 1' : null);

        $visibilities = PosConfiguration::getProductVisibilities();
        $visibility_values = array();
        foreach ($visibilities as $key => $value) {
            if (PosConfiguration::get($key)) {
                $visibility_values[] = '\'' . $value . '\'';
            }
        }
        if (!empty($visibility_values)) {
            $query->where('p.`visibility` IN (' . implode(',', $visibility_values) . ')');
        }

        return (int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query, false);
    }
}
