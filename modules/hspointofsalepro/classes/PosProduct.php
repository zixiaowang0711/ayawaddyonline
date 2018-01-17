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
class PosProduct extends Product
{

    /**
     *
     * @var boolean
     */
    public $tax_included;

    /**
     *
     * @var Address
     */
    public $vat_address;

    /**
     * Deny orders = 0, Allow orders = 1, Deny orders as set in the Products Preferences page = 2.
     */
    const PRODUCT_OUT_OF_STOCK = 2;

    protected static $pos_combination = array();

    /**
     * @see parent::add()
     * @param boolean $autodate
     * @param boolean $null_values
     * @return boolean
     */
    public function add($autodate = true, $null_values = false)
    {
        $default_id_lang = (int) Configuration::get('PS_LANG_DEFAULT');
        if (empty($this->name[$default_id_lang])) {
            // Get the list of not-empty-names
            $valid_names = array_diff(array_map('trim', array_values($this->name)), array(''));
            $first_valid_name = reset($valid_names);
            $this->name[$default_id_lang] = $first_valid_name;
            $this->link_rewrite[$default_id_lang] = Tools::link_rewrite($first_valid_name);
        }
        $this->calculatePrice();
        return parent::add($autodate, $null_values);
    }

    /**
     * Re-calculate price if tax is applied
     */
    protected function calculatePrice()
    {
        if (!$this->vat_address instanceof Address || !Validate::isLoadedObject($this->vat_address)) {
            return;
        }
        if (Configuration::get('PS_TAX') && $this->id_tax_rules_group && $this->tax_included) {
            $tax_manager = TaxManagerFactory::getManager($this->vat_address, $this->id_tax_rules_group);
            $tax_calculator = $tax_manager->getTaxCalculator();
            // Price in this case is always considered as "Tax excluded"
            // Therefore, we have to remove tax from what's passed outside in
            $this->price = Tools::ps_round($tax_calculator->removeTaxes($this->price), _PS_PRICE_COMPUTE_PRECISION_);
        }
    }

    /**
     * Get all combinations of product.
     *
     * @param int  $id_product
     * @param int  $id_shop
     * @param int  $id_lang
     * @param bool $hiden_attribute_name
     *
     * @return array
     *               <pre/>
     *               array(
     *               int => array(// id_product_attribute
     *               'quantity' => int,
     *               'id_image' => string,//usually in format [id_product-id_product_attribute]
     *               'attribute_groups' => array(
     *               array(// the 1st group of this combination
     *               'id_attribute' => int,
     *               'group_name' => string,
     *               'value' => string,
     *               'color' => string, // color code
     *               'image' => string, // attribute image
     *               'position' => int
     *               ),
     *               array(),// the 2nd group of this combination
     *               array(),// the 3rd group of this combination
     *               ...
     *               )
     *               )
     *               )
     */
    public static function getCombinations($id_product, $id_shop, $id_lang = null)
    {
        $context = Context::getcontext();
        if (is_null($id_lang)) {
            $id_lang = (int) $context->language->id;
        }
        $shop = !empty($id_shop) ? new Shop($id_shop) : Context::getcontext()->shop;
        $id_cache = $id_product . '-' . $id_shop . '-' . $id_lang;

        if (isset(self::$pos_combination[$id_cache]) && self::$pos_combination[$id_cache] !== null) {
            return self::$pos_combination[$id_cache];
        }

        $query = new DbQuery();
        $query->select('pa.`id_product_attribute`');
        $query->select('agl.`name` AS `group_name`');
        $query->select('pa.`id_product`');
        $query->select('a.`id_attribute`');
        $query->select('al.`name`');
        $query->select('pai.`id_image`');
        $query->select('ag.`id_attribute_group`');
        $query->select('ag.`position`');
        $query->select('IF(ag.`group_type` = "color", a.`color`, "") AS `color`');
        $query->select('stock.`quantity`');
        $query->from('product_attribute', 'pa');
        $query->innerJoin('product_attribute_shop', 'pas', 'pa.`id_product_attribute` = pas.`id_product_attribute`');
        // Left join, not all of combinations have their own images
        $query->leftJoin('product_attribute_image', 'pai', 'pai.`id_product_attribute` = pas.`id_product_attribute`');
        $query->innerJoin('product_attribute_combination', 'pac', 'pac.`id_product_attribute` = pas.`id_product_attribute`');
        $query->innerJoin('attribute', 'a', 'a.`id_attribute` = pac.`id_attribute`');
        $query->innerJoin('attribute_shop', 'ats', '(ats.`id_attribute` = a.`id_attribute` AND ats.`id_shop` = ' . (int) $id_shop . ')');
        $query->innerJoin('attribute_lang', 'al', '(a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = ' . (int) $id_lang . ')');
        $query->innerJoin('attribute_group', 'ag', 'ag.`id_attribute_group` = a.`id_attribute_group`');
        $query->innerJoin('attribute_group_lang', 'agl', '(ag.`id_attribute_group` = agl.`id_attribute_group` AND agl.`id_lang` = ' . (int) $id_lang . ')');
        $query->join(Product::sqlStock('pa', 'pa', false, $shop));
        $query->where('pa.`id_product` = ' . (int) $id_product);
        $query->where('pas.`id_shop` = ' . (int) $id_shop);
        $query->orderBy('ag.`position`');
        $query->orderBy('`group_name`');
        $results = Db::getInstance()->executeS($query);
        $combinations = array();
        foreach ($results as $result) {
            $combinations[$result['id_product_attribute']]['attributes'][$result['id_attribute_group']] = array(
                'id_attribute' => (int) $result['id_attribute'],
                'group_name' => $result['group_name'],
                'value' => $result['name'],
                'color' => $result['color'],
                'image' => @filemtime(_PS_COL_IMG_DIR_ . $result['id_attribute'] . '.jpg') ? _THEME_COL_DIR_ . (int) $result['id_attribute'] . '.jpg' : '',
                'position' => $result['position'],
            );
            $combinations[$result['id_product_attribute']]['quantity'] = (int) $result['quantity'];
            $combinations[$result['id_product_attribute']]['id_image'] = $result['id_image'];
            $combinations[$result['id_product_attribute']]['id_product'] = $result['id_product'];
        }
        // Get product image if there is no image associated to a combinatioin
        foreach ($combinations as &$combination) {
            if (empty($combination['id_image'])) {
                $cover_image = Product::getCover($id_product);
                $combination['id_image'] = null; // default value
                $combination = empty($cover_image) ? $combination : array_merge($combination, $cover_image);
            }
            $combination['id_image'] = Product::defineProductImage($combination, $id_lang);
            unset($combination['id_product']);
        }
        self::$pos_combination[$id_cache] = $combinations;

        return self::$pos_combination[$id_cache];
    }

    /**
     * @param int $id_product_attribute
     *
     * @return int
     */
    public function getMinimalQuantity($id_product_attribute = 0)
    {
        $minimum_quantity = $this->minimal_quantity;
        if ($id_product_attribute) {
            $shop = Context::getContext()->shop;
            // Don't use Attribute::getAttributeMinimalQty() as it checks "$minimum_quantity>1" while we expect "$minimum_quantity>=1"
            $combination = new Combination($id_product_attribute, null, $shop->id);
            $minimum_quantity = (int) $combination->minimal_quantity;
        }

        return $minimum_quantity ? $minimum_quantity : 1;
    }

    /**
     * @param int $out_of_stock it depend on setting in product page (Deny orders = 0, Allow orders = 1, Deny orders as set in the Products Preferences page = 2)
     *
     * @return int
     *
     * @deprecated Use self::allowOrderingOutOfStock() instead
     */
    public static function isEnabledOrderOutOfStock($out_of_stock)
    {
        $is_enabled_order_out_of_stock = 1;
        if ((int) Configuration::get('PS_STOCK_MANAGEMENT')) {
            $is_enabled_order_out_of_stock = (int) $out_of_stock == self::PRODUCT_OUT_OF_STOCK ? (int) Configuration::get('POS_PRODUCT_OUT_OF_STOCK') : (int) $out_of_stock;
        }

        return $is_enabled_order_out_of_stock;
    }

    /**
     * @param int $id_product
     * @param int $id_shop    optional; get from context if null @see Context::getContext()
     *
     * @return bool
     *
     * @todo
     * - StockAvailable::dependsOnStock() which cases being taken effect?
     * - Product setting (in a specific product) overrides RockPOS's setting?
     * Or RockPOS's setting overrides product setting?
     * The current approach is: Product setting overrides RockPOS's setting
     */
    public static function allowOrderingOutOfStock($id_product, $id_shop = null)
    {
        //$allow_ordering_out_of_stock = true;
        //if (StockAvailable::dependsOnStock($id_product, $id_shop)) {
        // StockAvailable::outOfStock return flag "out_of_stock" of a product
        // Domains of "out_of_stock":
        // - 1: Denied
        // - 2: Allow
        // - 3: As set in the Products Preferences page
        // If "out_of_stock", most likely, it depends on that setting of PrestaShop;
        // but in RockPOS, let's redirect to our own setting under RockPOS > Preferences page
        $allow_ordering_out_of_stock = StockAvailable::outOfStock($id_product, $id_shop);
        if ($allow_ordering_out_of_stock == self::PRODUCT_OUT_OF_STOCK) {
            $allow_ordering_out_of_stock = Configuration::get('POS_PRODUCT_OUT_OF_STOCK');
        }
        //}
        return (bool) $allow_ordering_out_of_stock;
    }

    /**
     *
     * @param int $id_product
     * @param int $id_lang
     * @param int $id_shop
     * @return array
     * <pre>
     * array (
     *   image_fields => array(
     *      int => array (
     *              id_customization_field => int,
     *              name => string,
     *              required => boolean
     *      )
     * ...
     *   )
     *   text_fields => array(
     *      int => array(
     *              id_customization_field => int,
     *              name => string,
     * *            required => boolean
     *
     *      )
     * ...
     *   )
     */
    public static function getCustomizedFields($id_product, $id_lang, $id_shop = null)
    {
        $customization_fields = array(
            'image_fields' => array(),
            'text_fields' => array()
        );
        if (!Customization::isFeatureActive()) {
            return $customization_fields;
        }

        if (Shop::isFeatureActive() && !$id_shop) {
            $id_shop = (int) Context::getContext()->shop->id;
        }

        $query = new DbQuery();
        $query->select('cf.`id_customization_field`');
        $query->select('cfl.`name`');
        $query->select('cf.`required`');
        $query->select('cf.`type`');
        $query->from('customization_field', 'cf');
        $query->naturalJoin('customization_field_lang', 'cfl');
        $query->where('cf.`id_product` = ' . (int) $id_product . ' AND cfl.`id_lang` = ' . (int) $id_lang . ' AND  cfl.`id_shop` = ' . (int) $id_shop);
        $query->orderBy('cf.`id_customization_field`');
        $result = Db::getInstance()->executeS($query);
        
        if (!empty($result)) {
            foreach ($result as $field) {
                if ($field['type'] == PosProduct::CUSTOMIZE_FILE) {
                    array_push($customization_fields['image_fields'], array(
                        'id_customization_field' => $field['id_customization_field'],
                        'name' => $field['name'],
                        'required' => $field['required'],
                    ));
                } elseif ($field['type'] == PosProduct::CUSTOMIZE_TEXTFIELD) {
                    array_push($customization_fields['text_fields'], array(
                        'id_customization_field' => $field['id_customization_field'],
                        'name' => $field['name'],
                        'required' => $field['required'],
                    ));
                } else {
                    // nothing todo
                }
            }
        }
        return $customization_fields;
    }

    /**
     *
     * @param int $id_cart
     * @param int $id_product
     * @param int $id_product_attribute
     * @param int $id_address_delivery
     * @return array
     * <pre>
     * array (
     *   int => array(
     *          quantity => int,
     *          id_customization_field => int,
     *          value => string
     * )
     * ...
     */
    public static function getCustomizedProduct($id_cart, $id_product, $id_product_attribute, $id_address_delivery)
    {
        $customization_datas = array();
        $customized_datas = PosProduct::getAllCustomizedDatas($id_cart);
        if (!isset($customized_datas[$id_product][$id_product_attribute][$id_address_delivery])) {
            return $customization_datas;
        }
        $product_customized = $customized_datas[$id_product][$id_product_attribute][$id_address_delivery];
        foreach ($product_customized as $id_customization => $customization) {
            if (isset($customization['datas'])) {
                $customization_datas[$id_customization]['quantity'] = $customization['quantity'];
                foreach ($customization['datas'] as $custom_type => $custom_data) {
                    foreach ($custom_data as $value) {
                        if ($custom_type == PosProduct::CUSTOMIZE_FILE) {
                            $customization_datas[$id_customization]['image_fields'][] = array(
                                'id_customization_field' => $value['id_customization_field'],
                                'value' => _THEME_PROD_PIC_DIR_ . $value['value']
                            );
                        } elseif ($custom_type == PosProduct::CUSTOMIZE_TEXTFIELD) {
                            $customization_datas[$id_customization]['text_fields'][] = array(
                                'id_customization_field' => $value['id_customization_field'],
                                'value' => $value['value']
                            );
                        } else {
                            // nothing todo
                        }
                    }
                }
            }
        }
        return $customization_datas;
    }

    /**
     *
     * @param array $product_ids
     * <pre>
     * array(
     *  int => int,
     * ...
     * )
     * @param int $number_customization
     * @return boolean
     */
    public static function updateCustomizedProduct($product_ids, $number_customization)
    {
        $success = array();
        if (empty($product_ids)) {
            return false;
        }

        $success[] = Db::getInstance()->execute(
            'UPDATE `' . _DB_PREFIX_ . 'product`
            SET `customizable` = 1,
            `uploadable_files` = ' . (int) $number_customization[Product::CUSTOMIZE_FILE] . ',
            `text_fields` = ' . (int) $number_customization[Product::CUSTOMIZE_TEXTFIELD] . '
            WHERE `id_product` IN (' . implode(', ', $product_ids) . ')'
        );

        $success[] = Db::getInstance()->execute(
            'UPDATE `' . _DB_PREFIX_ . 'product_shop`
            SET `customizable` = 1,
            `uploadable_files` = ' . (int) $number_customization[Product::CUSTOMIZE_FILE] . ',
            `text_fields` = ' . (int) $number_customization[Product::CUSTOMIZE_TEXTFIELD] . '
            WHERE `id_product` IN (' . implode(', ', $product_ids) . ')'
        );
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @param int $id_product
     * @return boolean
     */
    public static function isAllowOrderingDisableProduct($id_product)
    {
        $query = new DbQuery();
        $query->select('p.`active`');
        $query->from('product', 'p');
        $query->where('p.`id_product` = ' . (int) $id_product);
        $active = Db::getInstance()->getValue($query);

        return Configuration::get('POS_PRODUCT_INACTIVE') ?  true : (bool) $active;
    }

   /**
    *
    * @return array
    * <pre>
    * array(
    *   int => array (
    *       id_product => int,
    *       id_product_attribute => int,
    *       quantity => int,
    *       name => string,
    *       reference => string,
    *       ean13 => string,
    *       upc => string,
    *       combination => string,
    *       man_name => string,
    *       unit_price => float
    *       original_price => float
    *       product_unit_price => string,
    *       product_original_price => string,
    *       product_total_price => string,
    *   )
    * ...
    * )
    */
    public static function getSampleProducts()
    {
        $context = Context::getContext();
        $query = new DbQuery();
        $query->select('p.`id_product`');
        $query->select('pa.`id_product_attribute`');
        $query->select('IF (pa.`reference` <> "", pa.`reference`, p.`reference`) AS `reference`');
        $query->select('IF (pa.`ean13` <> "", pa.`ean13`, p.`ean13`) AS `ean13`');
        $query->select('IF (pa.`upc` <> "", pa.`upc`, p.`upc`) AS `upc`');
        $query->select('GROUP_CONCAT( DISTINCT CONCAT(agl.`public_name`, " : " , al.`name`) ORDER BY agl.`public_name` SEPARATOR ", ") AS combination');
        $query->select('m.`name` man_name');
        $query->from('product', 'p');
        $query->join(Shop::addSqlAssociation('product', 'p'));
        $query->leftJoin('product_attribute', 'pa', 'pa.`id_product` = p.`id_product`');
        $query->join(Shop::addSqlAssociation('product_attribute', 'pa', false));
        $query->leftJoin('product_attribute_combination', 'pac', 'pac.`id_product_attribute` = pa.`id_product_attribute`');
        $query->leftJoin('attribute', 'a', 'a.`id_attribute` = pac.`id_attribute`');
        $query->leftJoin('attribute_shop', 'ats', 'ats.`id_attribute` = a.`id_attribute`');
        $query->leftJoin('attribute_lang', 'al', 'a.`id_attribute` = al.`id_attribute` AND al.`id_lang` = '. (int) $context->language->id);
        $query->leftJoin('attribute_group', 'ag', 'ag.`id_attribute_group` = a.`id_attribute_group`');
        $query->leftJoin('attribute_group_lang', 'agl', 'agl.`id_attribute_group` = ag.`id_attribute_group` AND agl.`id_lang` = '. (int) $context->language->id);
        $query->leftJoin('manufacturer', 'm', 'm.`id_manufacturer` = p.`id_manufacturer`');
        $query->where('p.`active` = 1');
        $query->groupBy('p.`id_product`, pa.`id_product_attribute`');
        $query->limit(3);
        $products = Db::getInstance()->executeS($query);
        $specific_price_output = null;
        foreach ($products as $index => &$product) {
            $specific_price_output = null;
            $unit_price = PosProduct::getPriceStatic((int) $product['id_product'], false, (int) $product['id_product_attribute'], 2, null, false, true, 1, false, null, null, null, $specific_price_output);
            $original_price = PosProduct::getPriceStatic((int) $product['id_product'], false, (int) $product['id_product_attribute'], 2, null, false, false, 1, false, null, null, null, $specific_price_output);
            $product['name'] = PosProduct::getProductNames($product['id_product'], $context->language->id);
            $product['unit_price'] = $unit_price;
            $product['return'] = false;
            $product['exchange'] = false;
            $product['original_price'] = $original_price;
            $product['product_unit_price'] = Tools::displayPrice($unit_price, $context->currency, false);
            $product['product_original_price'] = Tools::displayPrice($original_price, $context->currency, false);
            $product['product_total_price'] = Tools::displayPrice($unit_price * ($index + 1), $context->currency, false);
            $product['quantity'] = $index + 1;
        }
        return $products;
    }

    /**
     *
     * @param int $id_product
     * @param int $id_current_lang
     * @return array
     * <pre>
     * array(
     *  int => string
     * ...
     * )
     */
    public static function getProductNames($id_product, $id_current_lang)
    {
        $available_languages = array();
        $selected_languages = Tools::jsonDecode(Configuration::get('POS_MULTIPLE_LANGGUAGES'), true);
        if (empty($selected_languages)) {
            $available_languages = array( $id_current_lang);
        } else {
            $active_languages = Language::getLanguages(true);
            foreach ($active_languages as $language) {
                if (in_array($language['id_lang'], array_column($selected_languages, 'idLang'))) {
                    foreach ($selected_languages as $selected_language) {
                        if ($selected_language['idLang'] == $language['id_lang']) {
                            $available_languages[$selected_language['position']] = $language['id_lang'];
                        }
                    }
                }
            }
        }
        ksort($available_languages);
        $product = new self($id_product, true);
        $product_name = array();
        if (Validate::isLoadedObject($product)) {
            foreach ($available_languages as $available_language) {
                if (in_array($available_language, array_keys($product->name))) {
                    $product_name[] = $product->name[$available_language];
                }
            }
        } else {
            $product_name[] = PosOrderDetail::getCustomProductName($id_product);
        }
        return array_unique($product_name);
    }
    
    /**
     *
     * @return array
     * <pre >
     * array(
     *   int => int
     * ...
     */
    public static function getProductVisibilities()
    {
        $product_visibilities = PosConfiguration::getProductVisibilities();
        $product_visibility = array();
        foreach ($product_visibilities as $key => $value) {
            if ((int) Configuration::get($key)) {
                $product_visibility[] = $value;
            }
        }
        return $product_visibility;
    }
}
