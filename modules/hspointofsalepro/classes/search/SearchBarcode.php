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
class SearchBarcode extends SearchAbstract
{

    /**
     * @return array
     * <pre>
     * array(
     *  'id_product' => int,
     *  'id_product_attribute' => int
     * )
     */
    public function search()
    {
        $product = array();
        $id_product = $this->findIdProduct();
        if ((int) $id_product > 0) {
            $product['id_product'] = $id_product;
            $query = new DbQuery();
            $query->select('pa.`id_product_attribute`');
            $query->from('product_attribute', 'pa');
            $query->join(Shop::addSqlAssociation('product_attribute', 'pa', true));
            $sanitized_barcode = pSQL($this->keyword);
            $where = array();
            // If barcode contains letters (non-digits), this is not a standard barcode (EAN, UPC).
            // In this case, barcode is usually stored in reference fields.
            // Some shop owners also store valid barcodes (EAN, UPC) in reference fields even though, it's not highly recommended.
            $where_fields = array('upc', 'ean13', 'reference');
            if (Tools::version_compare(_PS_VERSION_, '1.7', '>=')) {
                array_push($where_fields, 'isbn');
            }
            foreach ($where_fields as $where_field) {
                $where[] = "pa.`$where_field` = '$sanitized_barcode'"; // Combination's field
            }
            $query->where(implode(' OR ', $where));
            $query->where('pa.`id_product` = ' . (int) $id_product . '');
            $id_product_attribute = Db::getInstance()->getValue($query);
            $product['id_product_attribute'] = (int) $id_product_attribute > 0 ? $id_product_attribute : 0;
        }
        return $product;
    }

    /**
     *
     * @return int
     */
    protected function findIdProduct()
    {
        $query = new DbQuery();
        $query->select('psi.`id_product`');
        $query->from('pos_search_word', 'psw');
        $query->leftJoin('pos_search_index', 'psi', 'psw.`id_word` = psi.`id_word`');
        $query->where('psw.`id_lang` = ' . (int) $this->id_lang);
        $query->where('psw.`id_shop` = ' . (int) $this->shop->id);
        $query->where('psw.`word` = \'' . pSQL(trim($this->keyword)) . '\'');
        return Db::getInstance()->getValue($query);
    }
}
