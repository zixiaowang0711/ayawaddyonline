<?php
/**
* Module My Easy ERP Web In Color 
* 
*  @author    Web In Color - addons@webincolor.fr
*  @version 2.6
*  @uses Prestashop modules
*  @since 1.0 - mai 2014
*  @package Wic ERP
*  @copyright Copyright &copy; 2014, Web In Color
*  @license   http://www.webincolor.fr
*/

class HTMLTemplateErpOrderFormMin extends HTMLTemplate
{
    public $erp_order;
    public $address_supplier;
    public $context;

    public function __construct(ErpOrder $erp_order, $smarty)
    {
        $this->erp_order = $erp_order;
        $this->smarty = $smarty;
        $this->context = Context::getContext();
        $this->address_supplier = new Address(Address::getAddressIdBySupplierId((int)$erp_order->id_supplier));

        // header informations
        $this->date = Tools::displayDate($erp_order->date_add, (int)$this->context->language->id);
        $this->title = HTMLTemplateErpOrderForm::l('Erp order form');
    }

    /**
     * @see HTMLTemplate::getContent()
     */
    public function getContent()
    {
        $erp_order_details = $this->erp_order->getEntriesCollectionMin((int)$this->context->language->id);
        $this->roundErpOrderDetails($erp_order_details);

        $this->roundErpOrder($this->erp_order);

        $tax_order_summary = $this->getTaxOrderSummary();
        $currency = new Currency((int)$this->erp_order->id_currency);

        foreach ($erp_order_details as $erp_order_detail) {
            $product = new Product((int)$erp_order_detail->id_product, false, (int)$this->context->language->id);

            if (Validate::isLoadedObject($product)) {
                $name = $product->name;
            } else {
                $name = '';
            }

            $sum = $erp_order_detail->price_with_order_discount_te;
            $qty = $erp_order_detail->quantity_ordered;
            $id_erp_product = ErpProducts::getProductById((int)$erp_order_detail->id_product, (int)$erp_order_detail->id_product_attribute);
            $erp_product = new ErpProducts((int)$id_erp_product);

            $bundle = $erp_order_detail->quantity_ordered / (!$erp_product->bundling ? 1 : $erp_product->bundling);

            //We select all combinations
            $sql = 'SELECT
								`id_product_attribute`,
								`price_with_order_discount_te`,
								`quantity_ordered`
							FROM 
								`'._DB_PREFIX_.'erp_order_detail`
							WHERE
								`id_product` = '.(int)$erp_order_detail->id_product.'
							AND
								`id_product_attribute` != 0
							AND
								`id_product_attribute` != '.(int)$erp_order_detail->id_product_attribute.'
							AND
								`id_erp_order` = '.(int)$erp_order_detail->id_erp_order.'
							';

            $attributes = Db::getInstance()->executeS($sql);

            if ($attributes && count($attributes)) {
                foreach ($attributes as $attribute) {
                    $combination = new Combination($attribute['id_product_attribute']);

                    $id_erp_product = ErpProducts::getProductById((int)$erp_order_detail->id_product, (int)$attribute['id_product_attribute']);
                    $erp_product = new ErpProducts((int)$id_erp_product);

                    if (Validate::isLoadedObject($combination)) {
                        $combination_name = '';
                        foreach ($combination->getAttributesName((int)$this->context->language->id) as $attribute_name) {
                            $combination_name .= $attribute_name['name'].'|';
                        }

                        $name .= ' '.Tools::substr($combination_name, 0, -1).',';
                    }

                    $sum += $attribute['price_with_order_discount_te'];
                    $qty += $attribute['quantity_ordered'];
                    $bundle += $attribute['quantity_ordered'] / (!$erp_product->bundling ? 1 : $erp_product->bundling);
                }
            }

            $erp_order_detail->reference = $product->reference;
            $erp_order_detail->name = $name;
            $erp_order_detail->price_with_order_discount_te = $sum;
            $erp_order_detail->quantity_ordered = $qty;
            $erp_order_detail->bundling = $bundle;
        }

        $this->smarty->assign(array(
            'supplier_name' => $this->erp_order->supplier_name,
            'address_supplier' => $this->address_supplier,
            'erp_order' => $this->erp_order,
            'erp_order_details' => $erp_order_details,
            'tax_order_summary' => $tax_order_summary,
            'currency' => $currency,
        ));

        return $this->smarty->fetch(_PS_MODULE_DIR_.'wic_erp/views/templates/admin/pdf-erp-order-min.tpl');
    }

    /**
     * @see HTMLTemplate::getBulkFilename()
     */
    public function getBulkFilename()
    {
        return 'erp_order.pdf';
    }

    /**
     * @see HTMLTemplate::getFileName()
     */
    public function getFilename()
    {
        return self::l('ErpOrderForm').sprintf('_%s', $this->erp_order->reference).'.pdf';
    }

    /**
     * @see HTMLTemplate::getHeader()
     */
    public function getHeader()
    {
        $shop_name = Configuration::get('PS_SHOP_NAME');
        $path_logo = $this->getLogo();
        $width = $height = 0;

        if (!empty($path_logo)) {
            list($width, $height) = getimagesize($path_logo);
        }

        $this->smarty->assign(array(
            'logo_path' => $path_logo,
            'img_ps_dir' => 'http://'.Tools::getMediaServer(_PS_IMG_)._PS_IMG_,
            'img_update_time' => Configuration::get('PS_IMG_UPDATE_TIME'),
            'title' => $this->title,
            'reference' => $this->erp_order->reference,
            'date' => $this->date,
            'shop_name' => $shop_name,
            'width_logo' => $width,
            'height_logo' => $height
        ));

        return $this->smarty->fetch(_PS_MODULE_DIR_.'wic_erp/views/templates/admin/pdf-erp-order-header.tpl');
    }

    /**
     * @see HTMLTemplate::getFooter()
     */
    public function getFooter()
    {
        $tax_order_summary = $this->getTaxOrderSummary();
        $currency = new Currency((int)$this->erp_order->id_currency);

        $this->smarty->assign(array(
            'shop_address' => $this->getShopAddress(),
            'shop_fax' => Configuration::get('PS_SHOP_FAX'),
            'shop_phone' => Configuration::get('PS_SHOP_PHONE'),
            'shop_details' => Configuration::get('PS_SHOP_DETAILS'),
            'tax_order_summary' => $tax_order_summary,
            'currency' => $currency,
        ));
        return $this->smarty->fetch(_PS_MODULE_DIR_.'wic_erp/views/templates/admin/pdf-erp-order-footer.tpl');
    }

    /**
     * Returns the shop address
     * @return string
     */
    protected function getShopAddress()
    {
        $shop_address = '';
        $shop = new Shop($this->context->shop->id);
        if (Validate::isLoadedObject($shop)) {
            $shop_address_obj = $shop->getAddress();
            if (isset($shop_address_obj) && $shop_address_obj instanceof Address) {
                $shop_address = AddressFormat::generateAddress($shop_address_obj, array(), ' - ', ' ');
            }
            return $shop_address;
        }

        return $shop_address;
    }

    protected function getTaxOrderSummary()
    {
        $query = new DbQuery();
        $query->select('
			SUM(price_with_order_discount_te) as base_te,
			tax_rate,
			SUM(tax_value_with_order_discount) as total_tax_value
		');
        $query->from('erp_order_detail');
        $query->where('id_erp_order = '.(int)$this->erp_order->id);
        $query->groupBy('tax_rate');

        $results = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);

        foreach ($results as &$result) {
            $result['base_te'] = Tools::ps_round($result['base_te'], 2);
            $result['tax_rate'] = Tools::ps_round($result['tax_rate'], 2);
            $result['total_tax_value'] = Tools::ps_round($result['total_tax_value'], 2);
        }
        unset($result); // remove reference

        return $results;
    }

    /**
     * Rounds values of a SupplyOrderDetail object
     * @param array $collection
     */
    protected function roundErpOrderDetails(&$collection)
    {
        foreach ($collection as $erp_order_detail) {
            $erp_order_detail->unit_price_te = Tools::ps_round($erp_order_detail->unit_price_te, 2);
            $erp_order_detail->price_te = Tools::ps_round($erp_order_detail->price_te, 2);
            $erp_order_detail->discount_rate = Tools::ps_round($erp_order_detail->discount_rate, 2);
            $erp_order_detail->price_with_discount_te = Tools::ps_round($erp_order_detail->price_with_discount_te, 2);
            $erp_order_detail->tax_rate = Tools::ps_round($erp_order_detail->tax_rate, 2);
            $erp_order_detail->price_ti = Tools::ps_round($erp_order_detail->price_ti, 2);
        }
    }

    /**
     * Rounds values of a SupplyOrder object
     * @param SupplyOrder $supply_order
     */
    protected function roundErpOrder(ErpOrder &$erp_order)
    {
        $erp_order->total_te = Tools::ps_round($erp_order->total_te, 2);
        $erp_order->discount_value_te = Tools::ps_round($erp_order->discount_value_te, 2);
        $erp_order->total_with_discount_te = Tools::ps_round($erp_order->total_with_discount_te, 2);
        $erp_order->total_tax = Tools::ps_round($erp_order->total_tax, 2);
        $erp_order->total_ti = Tools::ps_round($erp_order->total_ti, 2);
    }
}
