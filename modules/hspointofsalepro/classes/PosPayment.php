<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * PosPayment for Point of Sale.
 */
class PosPayment extends ObjectModel
{

    /**
     * Name of PosPayment.
     *
     * @var string
     */
    public $payment_name;

    /**
     * Integer id_module which PosPayment belongs to.
     *
     * @var int
     */
    public $id_module;

    /**
     * Reference of module.
     *
     * @var int
     */
    public $reference = 0;

    /**
     * Label name show in view.
     *
     * @var string
     */
    public $label;

    /**
     * Rule of each PosPayment.
     *
     * @var string
     */
    public $rule;

    /**
     * Boolean Status.
     *
     * @var int
     */
    public $active = true;

    /**
     *
     * @var int
     */
    public $is_default;

    /**
     * @var int
     */
    public $position;
    public static $default_payments = array(
        array(
            'name' => 'Cash',
            'label' => 'Cash',
            'reference' => 0,
            'position' => 0,
            'rule' => '',
            'is_default' => 1,
        ),
        array(
            'name' => 'Cheque',
            'label' => 'Cheque number',
            'reference' => 1,
            'position' => 1,
            'rule' => 'Cheque',
            'is_default' => 0,
        ),
        array(
            'name' => 'Credit Card',
            'label' => 'Credit Card',
            'reference' => 1,
            'position' => 2,
            'rule' => 'cc',
            'is_default' => 0,
        ),
        array(
            'name' => 'Installment',
            'label' => 'Installment',
            'reference' => 0,
            'position' => 3,
            'rule' => '',
            'is_default' => 0,
        ),
    );

    /**
     * @see ObjectModel::$definition
     */
    public static $definition = array(
        'table' => 'pos_payment',
        'primary' => 'id_pos_payment',
        'multilang' => true,
        'fields' => array(
            'id_module' => array('type' => self::TYPE_INT),
            'reference' => array('type' => self::TYPE_STRING),
            'rule' => array('type' => self::TYPE_STRING, 'validate' => 'isCatalogName'),
            'active' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool', 'required' => true),
            'is_default' => array('type' => self::TYPE_BOOL, 'validate' => 'isBool'),
            'position' => array('type' => self::TYPE_INT, 'validate' => 'isInt'),
            'payment_name' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'size' => 32),
            'label' => array('type' => self::TYPE_STRING, 'lang' => true, 'validate' => 'isCatalogName', 'size' => 32),
        ),
    );

    /**
     * @param boolean $associated_shop
     * @param boolean $autodate
     * @param boolean $null_values
     *
     * @return boolean
     */
    public function add($associated_shop = true, $autodate = true, $null_values = false)
    {
        $success = array();
        $success[] = parent::add($autodate, $null_values);
        if ($associated_shop) {
            $shops = Shop::getShops();
            $shop_ids = array();
            foreach ($shops as $shop) {
                $shop_ids[] = (int) $shop['id_shop'];
            }
            $success[] = $this->addAssociatedShops($shop_ids);
        }
        return array_sum($success) >= count($success);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        $success = array();
        $success[] = $this->deleteAssociatedShops();
        $success[] = parent::delete();

        return array_sum($success) >= count($success);
    }

    /**
     * Check relationship of entries.
     *
     * @return bool
     */
    public function hasMultishopEntries()
    {
        if (!Shop::isFeatureActive()) {
            return false;
        }

        return (bool) Db::getInstance()->getValue('SELECT COUNT(*) FROM `' . _DB_PREFIX_ . $this->def['table'] . '_shop` WHERE `' . $this->def['primary'] . '` = ' . (int) $this->id);
    }

    /**
     * Get all available pos_payments.
     *
     * @deprecated See getAll()
     *
     * @param int  $id_lang id of language
     * @param int  $id_shop id of curret shop
     * @param bool $active  enable or disable payments only
     *
     * @return array
     * <pre>
     * array(
     *  int => array (
     *    id_pos_payment => int
     *    active => boolean
     *    is_default => boolean
     *    payment_name => string
     *    )
     * ...
     * )
     */
    public static function getPosPayments($id_lang = null, $id_shop = null, $active = true)
    {
        if (empty($id_lang)) {
            $context = Context::getContext();
            $id_lang = (int) $context->language->id;
        }

        $sql_join = array('`' . _DB_PREFIX_ . 'pos_payment_lang` pl ON p.`id_pos_payment` = pl.`id_pos_payment`');
        $sql_where = array(
            'pl.`id_lang` = ' . (int) $id_lang,
            'p.`active` = ' . (int) $active,
        );
        if (!empty($id_shop)) {
            $sql_join[] = '`' . _DB_PREFIX_ . 'pos_payment_shop` ps ON p.`id_pos_payment` = ps.`id_pos_payment`';
            $sql_where[] = 'ps.`id_shop` = ' . (int) $id_shop;
        }

        $sql = 'SELECT
                        p.`id_pos_payment`,
                        p.`active`,
                        p.`is_default`,
                        pl.`payment_name`
                FROM `' . _DB_PREFIX_ . 'pos_payment` p
                JOIN ' . implode(' JOIN ', $sql_join) . '
                WHERE
                ' . implode(' AND ', $sql_where) . '
                GROUP BY p.`id_pos_payment`
                ORDER BY p.`position` ASC';
        return Db::getInstance()->executeS($sql, true);
    }

    /**
     * @param int  $id_lang
     * @param int  $id_shop
     * @param boolean $active
     *
     * @return array
     * <pre>
     * array(
     *  int => array(
     *     id_payment => int,
     *     is_default => int,
     *     name => string
     *  )
     * )
     */
    public static function getAll($id_lang = null, $id_shop = null, $active = true)
    {
        if (empty($id_lang)) {
            $context = Context::getContext();
            $id_lang = (int) $context->language->id;
        }
        $query = new DbQuery();
        $query->select('p.`id_pos_payment` AS `id_payment`');
        $query->select('p.`is_default` AS is_default');
        $query->select('pl.`payment_name` AS name');
        $query->from('pos_payment', 'p');
        $query->innerjoin('pos_payment_lang', 'pl', 'p.`id_pos_payment` = pl.`id_pos_payment` AND pl.`id_lang` = ' . (int) $id_lang);
        if (!empty($id_shop)) {
            $query->innerjoin('pos_payment_shop', 'ps', 'p.`id_pos_payment` = ps.`id_pos_payment`');
            $query->where('ps.`id_shop` = ' . (int) $id_shop);
        }
        if ($active) {
            $query->where('p.`active` = 1');
        }
        $query->orderBy('p.`position` ASC');
        $query->groupBy('p.`id_pos_payment`');

        return Db::getInstance()->executeS($query);
    }

    /**
     * Get first payment id of point of sale.
     *
     * @return int
     */
    public static function getFirstPaymentId()
    {
        $sql = 'SELECT `id_pos_payment`
                FROM `' . _DB_PREFIX_ . 'pos_payment`
                ORDER BY `id_pos_payment` ASC';

        return Db::getInstance()->getValue($sql);
    }

    /**
     * Get name of pos payments.
     *
     * @param int $id_lang
     * @param int $id_shop
     *
     * @return array
     *               <pre/>
     *               Array
     *               (
     *               [0] => string
     *               [1] => string
     *               )
     */
    public static function getPosPaymentNames($id_lang, $id_shop)
    {
        $names = array();
        $pos_payments = self::getPosPayments($id_lang, $id_shop);
        if (!empty($pos_payments)) {
            foreach ($pos_payments as $pos_payment) {
                $names[] = $pos_payment['payment_name'];
            }
        }

        return $names;
    }

    /**
     * @param bool $way      Up (1)  or Down (0)
     * @param int  $position
     *                       return boolean
     */
    public function updatePosition($way, $position)
    {
        $success = array();
        $success[] = Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'pos_payment`
                    SET `position`= `position` ' . ($way ? '+ 1' : '- 1') . '
                    WHERE `position` ' . ($way ? '>= ' . (int) $position . ' AND `position` <= ' . (int) $this->position : '<= ' . (int) $position . ' AND `position` >= ' . (int) $this->position));
        $success[] = Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'pos_payment`
                    SET `position` = ' . (int) $position . '
                    WHERE `id_pos_payment`=' . (int) $this->id);

        return array_sum($success) >= count($success);
    }

    /**
     * @deprecated since version 2.4.1 pospayment change to pos_payment
     *
     * @return bool
     */
    public static function resetPositions()
    {
        $success = array();
        if (Db::getInstance()->execute('SET @i = -1', false)) {
            $success[] = Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'pospayment` SET `position` = @i:=@i+1');
        }

        return array_sum($success) >= count($success);
    }

    /**
     * @return int
     */
    public static function getHighestPosition()
    {
        $query = new DbQuery();
        $query->select('MAX(`position`) AS `max`');
        $query->from('pos_payment');

        return (int) Db::getInstance()->getValue($query);
    }

    /**
     * @return bool
     */
    public static function installDefaultPayments()
    {
        if (self::getFirstPaymentId()) {
            return true;
        }
        $success = array();
        $languages = Language::getLanguages(false);
        foreach (self::$default_payments as $payment) {
            $pos_payment = new self();
            foreach ($languages as $language) {
                $pos_payment->payment_name[$language['id_lang']] = $payment['name'];
                $pos_payment->label[$language['id_lang']] = $payment['label'];
            }
            foreach ($payment as $key => $value) {
                if (!in_array($key, array('label', 'name'))) {
                    $pos_payment->{$key} = $value;
                }
            }
            $success[] = $pos_payment->add();
            $id_default_payment = Configuration::get('POS_DEFAULT_PAYMENT_ID');
            if (empty($id_default_payment)) {
                $success[] = Configuration::updateValue('POS_DEFAULT_PAYMENT_ID', $pos_payment->id);
            }
        }

        return array_sum($success) >= count($success);
    }

    /**
     *
     * @return array
     * <pre>
     * array(
     *  int => array(
     *      id_pos_payment => int,
     *      position => int,
     *      active => int,
     *      names => array(
     *          int => string // id_lang => name
     *      ),
     *      id_shops => array(
     *          int,
     *          int,
     *          ...
     *      )
     *  )
     * ...
     * )
     */
    public static function getPaymentTypes()
    {
        $query = new DbQuery();
        $query->select('p.`id_pos_payment`');
        $query->select('p.`position`');
        $query->select('p.`active`');
        $query->select('p.`is_default`');
        $query->select('p.`position`');
        $query->select('GROUP_CONCAT( DISTINCT CONCAT(pl.`id_lang`, ":" , pl.`payment_name`) SEPARATOR "," )  AS `names`');
        $query->select('GROUP_CONCAT( DISTINCT payment_shop.`id_shop` SEPARATOR "," ) AS `id_shops`');
        $query->from('pos_payment', 'p');
        $query->innerjoin('pos_payment_lang', 'pl', 'p.`id_pos_payment` = pl.`id_pos_payment`');
        $query->innerjoin('pos_payment_shop', 'payment_shop', 'p.`id_pos_payment` = payment_shop.`id_pos_payment`');
        $query->groupBy('p.`id_pos_payment`');
        $query->orderBy('p.`position` ASC');
        $query->where('pl.`payment_name` <> ""');
        $results = Db::getInstance()->executeS($query);
        if (!empty($results)) {
            foreach ($results as &$result) {
                $names = array();
                $payment_names = explode(',', $result['names']);
                foreach ($payment_names as $payment) {
                    $tmp = explode(':', $payment);
                    $names[$tmp[0]] = $tmp[1];
                }
                $result['names'] = $names;
                $result['id_shops'] = explode(',', $result['id_shops']);
            }
        }
        return $results;
    }

    /**
     *
     * @return boolean
     */
    public static function unsetDefaultPayment()
    {
        return Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'pos_payment` SET `is_default` = 0 WHERE `is_default`= 1');
    }

    /**
     *
     * @return boolean
     */
    public function deleteAssociatedShops()
    {
        $id_shop_list = Shop::getContextListShopID();
        return Db::getInstance()->delete($this->def['table'] . '_shop', '`' . $this->def['primary'] . '`=' . (int) $this->id . ' AND id_shop IN (' . implode(',', array_values($id_shop_list)) . ')');
    }

    /**
     * @param array $shop_ids
     * <pre>
     * array(
     *      int => int,
     * ...
     * )
     * @return boolean
     */
    public function addAssociatedShops(array $shop_ids)
    {
        $success = array();
        if (!empty($shop_ids)) {
            $data = array();
            foreach ($shop_ids as $shop_id) {
                $data[] = '(' . (int) $this->id . ',' . (int) $shop_id . ')';
            }
            $success[] = Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'pos_payment_shop` (`id_pos_payment`, `id_shop`) VALUES ' . implode(',', $data));
        }
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @param int $id_lang
     * @return PosPayment
     */
    public static function getDefaultPayment($id_lang = null)
    {
        if (empty($id_lang)) {
            $context = Context::getContext();
            $id_lang = (int) $context->language->id;
        }
        $query = new DbQuery();
        $query->select('p.`id_pos_payment` AS `id_pos_payment`');
        $query->select('p.`active` AS `active`');
        $query->select('p.`is_default` AS is_default');
        $query->select('pl.`payment_name` AS payment_name');
        $query->from('pos_payment', 'p');
        $query->innerjoin('pos_payment_lang', 'pl', 'p.`id_pos_payment` = pl.`id_pos_payment` AND pl.`id_lang` = ' . (int) $id_lang);
        $query->where('p.`active` = 1');
        $query->where('p.`is_default` = 1');
        $result = Db::getInstance()->getRow($query);
        $pos_payment = new self();
        if (!empty($result)) {
            $pos_payment->hydrate($result, null);
        }
        return $pos_payment;
    }

    /**
     * @param int $id_shop
     * @return boolean
     */
    public static function syncPaymentsShop($id_shop)
    {
        $sql = 'INSERT INTO `' . _DB_PREFIX_ . 'pos_payment_shop` (`id_pos_payment`, `id_shop`)
                SELECT id_pos_payment, ' . (int) $id_shop . ' FROM `' . _DB_PREFIX_ . 'pos_payment`';
        return Db::getInstance()->execute($sql);
    }
}
