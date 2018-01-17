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
class SearchGeneral extends SearchAbstract
{

     /**
     * Search for one or more products.
     * @return array @see getProductProperties()
     */
    public function search()
    {
        $context = Context::getcontext();
        $scored_words = array();
        $words = explode(' ', Search::sanitize($this->keyword, $this->id_lang, false, $context->language->iso_code));
        if (!count($words)) {
            return array();
        }

        $eligible_id_products = array();
        foreach ($words as $word) {
            if ($this->isKeywordValid($word)) {
                $eligible_id_products_by_keyword = $this->findIdProducts($word);
                $eligible_id_products = !empty($eligible_id_products) ? array_intersect($eligible_id_products, $eligible_id_products_by_keyword) : $eligible_id_products_by_keyword;
                if ($word[0] != '-') {
                    $scored_words[] = $word;
                }
            }
        }
        $unique_eligible_id_products = array_unique($eligible_id_products);

        if (empty($unique_eligible_id_products)) {
            return array();
        }

        return $this->getProductProperties($unique_eligible_id_products, $scored_words);
    }

    /**
     * @param string $keyword
     *
     * @return bool
     */
    public static function isKeywordValid($keyword)
    {
        return !empty($keyword) && Tools::strlen($keyword) >= (int) Configuration::get('PS_SEARCH_MINWORDLEN');
    }

    /**
     * @param string $keyword
     *
     * @return array
     *               <pre>
     *               array(
     *               int,
     *               int,
     *               ...
     *               )
     */
    protected function findIdProducts($keyword)
    {
        $start_search = Configuration::get('PS_SEARCH_START') ? '%' : '';
        $end_search = Configuration::get('PS_SEARCH_END') ? '' : '%';
        $id_products = array();
        $refined_keyword = str_replace(array('%', '_'), array('\\%', '\\_'), $keyword);
        $searched_word = Tools::substr($refined_keyword, ($refined_keyword[0] == '-' ? 1 : 0), PS_SEARCH_MAX_WORD_LENGTH);
        $query = new DbQuery();
        $query->select('psi.`id_product`');
        $query->from('pos_search_word', 'psw');
        $query->leftJoin('pos_search_index', 'psi', 'psw.`id_word` = psi.`id_word`');
        $query->where('psw.`id_lang` = ' . (int) $this->id_lang);
        $query->where('psw.`id_shop` = ' . (int) $this->shop->id);
        $query->where('psw.`word` LIKE \'' . pSQL($start_search) . pSQL($searched_word) . pSQL($end_search) . '\'');
        foreach (Db::getInstance()->executeS($query) as $row) {
            if (!empty($row['id_product'])) {
                $id_products[] = (int) $row['id_product'];
            }
        }

        return $id_products;
    }

    /**
     * Get attributes of a list of products.
     *
     * @param array $id_products
     * @param array $scored_words list of words to sort result based on weighted scores
     *
     * @return array
     *               <pre>
     *               array(
     *               int => array(
     *               'id_product' => int,
     *               'name' => string,// Product name
     *               'name' => string,
     *               'reference' => string,
     *               'quantity' => int,
     *               'has_combinations' => tinyint
     *               )
     *               )
     */
    public function getProductProperties(array $id_products, array $scored_words = array())
    {
        $product_query = new DbQuery();
        if (count($scored_words)) {
            $start_search = Configuration::get('PS_SEARCH_START') ? '%' : '';
            $end_search = Configuration::get('PS_SEARCH_END') ? '' : '%';
            $score_query = new DbQuery();
            $score_query->select('SUM(`weight`)');
            $score_query->from('pos_search_word', 'psw');
            $score_query->leftJoin('pos_search_index', 'psi', 'psw.`id_word` = psi.`id_word`');
            $score_query->where('psw.`id_lang` = ' . (int) $this->id_lang);
            $score_query->where('psw.`id_shop` = ' . (int) $this->shop->id);
            $score_where = array();
            foreach ($scored_words as $scored_word) {
                $score_where[] = 'psw.`word` LIKE \'' . pSQL($start_search) . pSQL(Tools::substr($scored_word, 0, PS_SEARCH_MAX_WORD_LENGTH)) . pSQL($end_search) . '\'';
            }
            $score_query->where(implode(' OR ', $score_where));
            $product_query->select('(' . $score_query->build() . ') `position`');
        }
        $product_query->select('p.`id_product`');
        $product_query->select('IF(p.`cache_default_attribute` > 0, 1, 0) AS `has_combinations`');
        $product_query->select('p.`reference` AS `reference`');
        $product_query->select('pl.`name` AS `name`');
        $product_query->select('stock.`quantity`');
        $product_query->from('product', 'p');
        $product_query->join(Shop::addSqlAssociation('product', 'p'));
        $product_query->innerJoin('product_lang', 'pl', 'p.`id_product` = pl.`id_product` AND pl.`id_lang` = ' . (int) $this->id_lang. ' AND pl.`id_shop` = '. (int) $this->shop->id);
        $product_query->join(Product::sqlStock('p', 0, true, $this->shop));
        $product_query->where('p.`id_product` IN (' . implode(',', $id_products) . ')');
        $product_query->where(!Configuration::get('POS_PRODUCT_INACTIVE') ? 'product_shop.`active` = 1' : null);
        if (Configuration::get('PS_STOCK_MANAGEMENT')) {
            $product_query->where(Configuration::get('POS_PRODUCT_OUT_OF_STOCK') ? null : 'stock.`quantity` > 0');
        }
        $product_query->orderBy('`position` DESC');
        $products = Db::getInstance()->executeS($product_query);
        foreach ($products as &$product) {
            unset($product['position']);
        }
        return $products;
    }
}
