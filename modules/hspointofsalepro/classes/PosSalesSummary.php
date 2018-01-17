<?php
/**
 * RockPOS Sales Summary
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 *
 */
class PosSalesSummary
{

    /**
     *
     * @var string // refer to Validate::isModuleName
     */
    public $module_name;

    /**
     *
     * @var string
     */
    public $date_from;

    /**
     *
     * @var string
     */
    public $date_to;
    protected $date_format = 'Y-m-d';

    /**
     *
     * @param string $module_name
     * @param string $date_from
     * @param string $date_to
     */
    public function __construct($module_name, $date_from, $date_to)
    {
        $this->module_name = Validate::isModuleName($module_name) ? $module_name : null;
        $this->date_from = $this->isDate($date_from, $this->date_format) ? $date_from : null;
        $this->date_to = $this->isDate($date_to, $this->date_format) ? $date_to : null;
    }

    /**
     *
     * @return \DbQuery
     */
    protected function getOrderReferencesQuery()
    {
        $query = new DbQuery();
        $query->select('reference');
        $query->from('orders', 'o');
        $query->where('o.`module` = "' . pSQL($this->module_name) . '"');
        $query->where('o.`valid` = 1' . Shop::addSqlRestriction(false, 'o'));
        $query->where('o.`date_add` BETWEEN "' . pSQL($this->date_from) . ' 00:00:00" AND "' . pSQL($this->date_to) . ' 23:59:59"');
        return $query;
    }

    /**
     *
     * @return array
     * <pre>
     * array(
     *       total_tax_incl => float,
     *       total_tax_excl => float,
     *       total_real_paid => float,
     *       total_products => int,
     *       total_transaction => int,
     *       sold_items => int
     * )
     */
    public function getSalesSummary()
    {
        $query = new DbQuery();
        $query->select('o.`total_paid_tax_incl`');
        $query->select('o.`total_paid_tax_excl`');
        $query->select('o.`total_paid_real`');
        $query->select('o.`conversion_rate`');
        $query->select('o.`id_order`');
        $query->select('SUM(od.`total_price_tax_excl`) AS `total_price_tax_excl`');
        $query->select('COUNT(o.`id_order`) AS `total_transaction`');
        $query->select('SUM(od.`product_quantity`) AS `sold_items`');
        $query->from('orders', 'o');
        $query->leftJoin('order_detail', 'od', 'o.`id_order` = od.`id_order`');
        $query->where('o.`reference` IN (' . $this->getOrderReferencesQuery()->build() . ')');
        $query->where('o.`valid` = 1');
        $query->groupBy('o.`id_order`');
        $sql = 'SELECT
                  IFNULL(SUM(o.`total_paid_tax_incl` / o.`conversion_rate`), 0) AS `total_tax_incl`,
                  IFNULL(SUM(o.`total_paid_tax_excl` / o.`conversion_rate`), 0) AS `total_tax_excl`,
                  IFNULL(SUM(o.`total_paid_real` / o.`conversion_rate`), 0) AS `total_real_paid`,
                  IFNULL(SUM(o.`total_price_tax_excl` / o.`conversion_rate`), 0) AS `total_products`,
                  COUNT(o.`id_order`) AS `total_transaction`,
                  IFNULL(SUM(`sold_items`), 0) AS `sold_items`
                FROM (' . $query->build() . ') AS o';
        return Db::getInstance()->getRow($sql);
    }

    /**
     *
     * @return float
     */
    public function getShippingCostSummary()
    {
        $query = new DbQuery();
        $query->select('IFNULL(SUM(o.`total_shipping_tax_excl` / o.`conversion_rate`), 0) AS `shipping_cost`');
        $query->from('orders', 'o');
        $query->leftJoin('order_cart_rule', 'ocr', '(o.`id_order` = ocr.`id_order`)');
        $query->where('o.`reference` IN (' . $this->getOrderReferencesQuery()->build() . ')');
        $query->where('ocr.`free_shipping` IS NULL');
        $query->where('o.`valid` = 1');
        return (float) Db::getInstance()->getValue($query);
    }

    /**
     *
     * @return array
     * <pre>
     * array(
     *    array (
     *      amount => float,
     *      name => string
     *    )
     * .....
     * )
     */
    public function getPaymentSummary()
    {
        $query = new DbQuery();
        $query->select('SUM(op.`amount` / op.`conversion_rate`) as `amount`');
        $query->select('payment_method as `name`');
        $query->from('order_payment', 'op');
        $query->where('op.`order_reference` IN (' . $this->getOrderReferencesQuery()->build() . ')');
        $query->groupBy('op.`payment_method`');
        return Db::getInstance()->executeS($query);
    }

    /**
     *
     * @return array
     * <pre>
     * array(
     *  array(
     *      total => string,
     *      iso_code => string,
     *      sign => string
     *  ),
     *  ...................
     * )
     */
    public function getCurrencySummary()
    {
        $query = new DbQuery();
        $query->select('SUM(o.`total_paid_real`) AS `total`');
        $query->select('c.`id_currency`');
        $query->select('c.`iso_code`');
        $query->from('orders', 'o');
        $query->leftJoin('currency', 'c', 'o.`id_currency` = c.`id_currency`');
        $query->where('o.`reference` IN (' . $this->getOrderReferencesQuery()->build() . ')');
        $query->where('o.`valid` = 1');
        $query->groupBy('o.`id_currency`');
        $currency_summary = Db::getInstance()->executeS($query);
        foreach ($currency_summary as &$value) {
            $currency = new Currency($value['id_currency']);
            $value['sign'] = $currency->sign;
            $value['total'] = Tools::displayPrice($value['total'], $currency, false);
            unset($value['id_currency']);
        }
        return $currency_summary;
    }

    /**
     * @param int $id_lang
     * @return array
     * <pre>
     * array(
     *  0 => array(
     *      id_category_default => int,
     *      name => string,
     *      total_price => float
     *  ),
     *  ..........
     * )
     */
    public function getCategorySummary($id_lang)
    {
        $query = new DbQuery();
        $query->select('p.`id_category_default`');
        $query->select('SUM(od.`total_price_tax_excl` / o.`conversion_rate`) AS `total_price`');
        $query->from('product', 'p');
        $query->leftJoin('order_detail', 'od', 'od.`product_id` = p.`id_product`');
        $query->leftJoin('orders', 'o', 'o.`id_order` = od.`id_order`');
        $query->where('o.`reference` IN (' . $this->getOrderReferencesQuery()->build() . ')');
        $query->where('o.`valid`= 1');
        $query->groupBy('p.`id_category_default`');
        $sql = 'SELECT  DISTINCT p.`id_category_default`, cl.`name`, p.`total_price`
                    FROM `' . _DB_PREFIX_ . 'category_lang` cl
                INNER JOIN
                    (' . $query->build() . ') p
                    ON cl.`id_category` = p.`id_category_default`
                WHERE cl.`id_lang` = ' . (int) $id_lang . '
                ORDER BY p.`total_price` DESC';
        return Db::getInstance()->executeS($sql);
    }

    /**
     *
     * @return array
     * <pre>
     * array (
     *   array(
     *      total => float,
     *      rate => float
     *   ),
     *   ................
     * )
     */
    public function getTaxSummary()
    {
        $query = new DbQuery();
        $query->select('SUM((od.`total_price_tax_incl` - od.`total_price_tax_excl`)  /  o.`conversion_rate`) AS `total`');
        $query->select('t.`rate`', 'rate');
        $query->from('order_detail', 'od');
        $query->innerJoin('orders', 'o', 'o.`id_order` = od.`id_order`');
        $query->innerJoin('order_detail_tax', 'odt', 'odt.`id_order_detail` = od.`id_order_detail`');
        $query->innerJoin('tax', 't', 't.`id_tax` = odt.`id_tax`');
        $query->where('o.`reference` IN (' . $this->getOrderReferencesQuery()->build() . ')');
        $query->where('o.`valid` = 1');
        $query->groupBy('t.`rate`');
        return Db::getInstance()->executeS($query);
    }

    /**
     *
     * @param string $date
     * @param string $format
     * @return boolean
     */
    protected function isDate($date, $format = 'Y-m-d')
    {
        return date($format, strtotime($date)) == $date;
    }
}
