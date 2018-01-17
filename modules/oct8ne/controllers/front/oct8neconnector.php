<?php

/**
 * Prestashop module for Oct8ne
 *
 * @category  Prestashop
 * @category  Module
 * @author    Prestaquality.com
 * @copyright 2016 Prestaquality
 * @license   Commercial license see license.txt
 * Support by mail  : info@prestaquality.com
 */
class Oct8neOct8neConnectorModuleFrontController extends ModuleFrontController
{

    /*public $ssl = true;*/
    private $callback;
    private $octmethod;


    /**
     * Inicializacion
     */
    public function init()
    {

        parent::init();

        $this->callback = trim(strip_tags(Tools::getValue('callback')));
        $this->octmethod = trim(strip_tags(Tools::getValue('octmethod')));

    }

    /**
     * Metodo que se ejecuta despues de la inicializacion. Comprueba si existe el metodo solicitado
     * y lo ejecuta si es el caso.
     */
    public function postProcess()
    {

        $data = "";
        try {

            $meth = $this->octmethod . 'OctMeth';

            if (method_exists($this, $meth)) {
                $data = $this->$meth();

            } else {

                throw new Exception("Method not Exists");
            }

        } catch (Exception $ex) {

            $this->module->logException($ex);

        }
        $this->postProcessFinally($data);

    }

    /**
     * Acciones finales
     * @param $data
     */
    private function postProcessFinally($data)
    {

        ob_end_clean();
        $this->printJsonCode($data);
        die();
    }

    /**
     * Metodo que establece la moneda local
     */
    private function setContextCurrency()
    {

        $currency = trim(strip_tags(Tools::getValue('currency')));

        if (!empty($currency)) {

            $in_currency = Currency::getIdByIsoCode($currency);

            if (!empty($in_currency)) {

                //Cargamos el objeto, comprobamos que lo tenemos y que es válido
                $currency = new Currency($in_currency);

                if (Validate::isLoadedObject($currency) && $currency->active == '1') {
                    Context::getContext()->currency = $currency;
                }
            }
        }
    }

    /**
     * Metodo que establece la lengua local
     */
    private function setContextLanguage()
    {
        $language = trim(strip_tags(Tools::getValue('locale')));


        if (!empty($language)) {

            $global_local = explode('-', $language);

            $id_language = Language::getIdByIso($global_local[0]);

            if (!empty($id_language)) {

                //Cargamos el objeto, comprobamos que lo tenemos y que es válido
                $language = new Language($id_language);

                if (Validate::isLoadedObject($language) && $language->active == '1') {
                    Context::getContext()->language = $language;
                }
            }
        }

    }

    /**
     * @param $data
     * Imprime un json de respuesta
     */
    public function printJsonCode($data)
    {

        if (empty($this->callback)) {
            header('Content-Type: application/json; charset=UTF-8');
            $json = Tools::jsonEncode($data);
            echo $json;

        } else {
            header('Content-Type: application/javascript; charset=UTF-8');
            $json = $this->callback . '(' . Tools::jsonEncode($data) . ');';
            echo $json;
        }
    }

    /**
     * Obtiene unos datos concretos sobre un producto, a modo de resumen simple
     * @param $id_product
     * @return array
     */
    public function getProductSummary($id_product)
    {

        $product = new Product($id_product, false, Context::getContext()->language->id);

        $result = array();
        if (Validate::isLoadedObject($product)) {

            if ($product->active) {

                $image = Product::getCover($id_product)['id_image'];

                if (isset($image)) {

                    $thumbnail = Context::getContext()->link->getImageLink($product->link_rewrite, $image);

                    $url_image_type = Configuration::get('OCT_URL_IMG_TYPE');

                    if ($url_image_type == 2) {

                        $base_url = rtrim($this->context->shop->getBaseURL(), '/');
                        $base_url = str_replace("http://", "", $base_url);
                        $base_url = str_replace("https://", "", $base_url);
                        $base_url = $base_url . "/";

                        $thumbnail_exploded = explode($base_url, $thumbnail);

                        $thumbnail = $thumbnail_exploded[0] . $base_url . $id_product . "-" . $thumbnail_exploded[1];

                    }

                } else {
                    $thumbnail = "";
                }


                $result = array(

                    "internalId" => $product->id,
                    "title" => $product->name,
                    "formattedPrice" => Tools::displayPrice($product->getPrice(Product::$_taxCalculationMethod == PS_TAX_INC, null, 6, null, false, true)),
                    "formattedPrevPrice" => Tools::displayPrice($product->getPrice(Product::$_taxCalculationMethod == PS_TAX_INC, null, 6, null, false, false)),
                    "productUrl" => Context::getContext()->link->getProductLink($id_product),

                    "thumbnail" => $thumbnail,
                );

                //si tiene ipa los precios se devuelven como null

                $hasipa = ($product->cache_default_attribute > 0);
                if ($hasipa) {

                    $result['formattedPrice'] = null;
                    $result['formattedPrevPrice'] = null;
                }
            }
        }

        return $result;
    }


    /**
     * Contiene informacion sobre el producto
     * @return array
     */
    public function productInfoOctMeth()
    {

        $this->setContextLanguage();
        $this->setContextCurrency();

        $ids = trim(strip_tags(Tools::getValue('productIds')));

        $ids = explode(',', $ids);

        $result = array();
        foreach ($ids as $id) {

            $aux = $this->getProductSummary($id);
            if (!empty($aux)) {

                $product = new Product($aux["internalId"], false, Context::getContext()->language->id);
                $aux["description"] = $product->description;

                $attr = $product->hasAttributes();
                if ($attr > 0 || !$product->checkQty(1)) {
                    $aux["addToCartUrl"] = "";
                    $aux["useProductUrl"] = true;
                } else {

                    $auxurl = Context::getContext()->link->getPageLink('cart', true, Context::getContext()->language->id, array('add' => 1, 'id_product' => $aux["internalId"], 'token' => Tools::getToken(false), 'qty' => 1));
                    $auxurl = Oct8ne::removeHttProtocol($auxurl);
                    $aux["addToCartUrl"] = $auxurl;
                    $aux["useProductUrl"] = false;
                }

                $images = $product->getImages(Context::getContext()->language->id);

                $medias = array();

                foreach ($images as $image) {

                    $url_image_type = Configuration::get('OCT_URL_IMG_TYPE');

                    $thumbnail = Context::getContext()->link->getImageLink($product->link_rewrite, $image['id_image']);

                    if ($url_image_type == 2) {

                        if (!isset($base_url)) {

                            $base_url = $this->context->shop->getBaseURL();
                            $base_url = str_replace("http://", "", $base_url);
                            $base_url = str_replace("https://", "", $base_url);
                        }

                        $thumbnail_exploded = explode($base_url, $thumbnail);

                        $thumbnail = $thumbnail_exploded[0] . $base_url . $id . "-" . $thumbnail_exploded[1];

                    }

                    $medias[] = array("url" => $thumbnail);
                }

                $aux["medias"] = $medias;

                $result[] = $aux;
            }
        }

        return $result;
    }

    /**
     * Obtiene informacion simple sobre el producto. LLama a getSummary
     * @return array
     */
    public function productSummaryOctMeth()
    {

        $this->setContextLanguage();
        $this->setContextCurrency();

        $ids = trim(strip_tags(Tools::getValue('productIds')));


        $ids = explode(',', $ids);

        $result = array();
        foreach ($ids as $id) {

            $aux = $this->getProductSummary($id);
            if (!empty($aux)) {

                $result[] = $aux;
            }
        }

        return $result;

    }


    /**
     * Devuelve la busqueda de productos por los paremetros de entrada especificados
     * @return array
     */
    public function searchOctMeth()
    {

        $this->module->loadLibrary("OctSearchFactory", "search");

        $this->setContextLanguage();
        $this->setContextCurrency();

        $result = array();
        $result["total"] = 0;
        $result["results"] = array();
        $result["filters"] = array("applied" => array(), "available" => array());


        //parametro de busqueda
        $search = trim(strip_tags(Tools::getValue('search')));
        if (!isset($search)) {
            return $result;
        }

        //Page por defecto 1
        $page = (int)trim(strip_tags(Tools::getValue('page')));
        if (!isset($page)) {
            $page = 1;
        }

        //PageSize por defecto 10
        $pageSize = (int)trim(strip_tags(Tools::getValue('pageSize')));
        if (!isset($pageSize)) {
            $pageSize = 10;
        }

        //parametro de ordenacion
        $orderBy = trim(strip_tags(Tools::getValue('orderby')));
        if (!isset($orderBy)) {
            $orderBy = "position";
        } else {

            switch ($orderBy) {

                case "relevance":
                    $orderBy = "position";
                    break;

                case "price":
                    break;

                case "name":
                    break;

                default:
                    $orderBy = "position";
                    break;
            }
        }

        //ascendente o descendente
        $dir = trim(strip_tags(Tools::getValue('dir')));
        if (!isset($dir)) {
            $dir = "asc";
        } else {

            switch ($dir) {

                case "asc":
                    break;

                case "desc":
                    break;

                default:
                    $dir = "asc";
                    break;

            }
        }

        //Factoria de motores de busqueda
        $factory = new OctSearchFactory();

        //Motor de busqueda concreto
        $motor = $factory->getInstance(Configuration::get('OCT_SEARCH_ENGINE'));

        //Realizar la busqueda
        $aux = $motor->doSearch(Context::getContext()->language->id, $search, $page, $pageSize, $orderBy, $dir);

        if (isset($aux)) {

            if ($aux["total"] > 0) {

                $result["total"] = $aux["total"];

                foreach ($aux["result"] as $item) {

                    $item_aux = $this->getProductSummary($item['id_product']);
                    array_push($result["results"], $item_aux);
                }
            }

        }

        return $result;
    }

    /**
     * Obtiene informacion de productos relacionados con el indicado
     * @return array
     */
    public function productRelatedOctMeth()
    {

        $this->setContextLanguage();
        $this->setContextCurrency();

        $result = array();
        $result["total"] = 0;
        $result["results"] = array();

        $productId = trim(strip_tags(Tools::getValue('productId')));

        if (isset($productId)) {

            $product = new Product($productId, false, Context::getContext()->language->id);

            //si no se ha cargado devuelvo vacio
            if (!Validate::isLoadedObject($product)) {
                return $result;
            }

            $category = $product->id_category_default;

            //cargo el objeto categorias
            $category = new Category($category, Context::getContext()->language->id);

            //si está cargado
            if (Validate::isLoadedObject($category)) {

                //compruebo el total,
                $total = $category->getProducts(Context::getContext()->language->id, 1, 100, "position", "asc", true, true);

                if ($total > 1) {

                    $result["total"] = $total - 1;
                    $products = $category->getProducts(Context::getContext()->language->id, 1, 100, "position", "asc", false, true);
                    $products = array_filter($products, function ($v) use ($productId) {
                        return $v['id_product'] != $productId;
                    });

                    foreach ($products as $item) {

                        array_push($result["results"], $this->getProductSummary($item['id_product']));

                    }

                    return $result;


                } else {

                    return $result;
                }

            } else {
                return $result;
            }


        } else {

            return $result;
        }
    }

    /**
     * @return array
     * Obtiene informacion sobre el usuario
     */
    public function customerDataOctMeth()
    {

        $this->setContextLanguage();
        $this->setContextCurrency();

        $result = array();
        $result["id"] = "";
        $result["firstName"] = "";
        $result["lastName"] = "";
        $result["email"] = "";
        $result["wishlist"] = array();
        $result["cart"] = array();


        $customer = Context::getContext()->customer;

        if (!empty($customer) && Validate::isLoadedObject($customer) && $customer->isLogged() && !$customer->deleted) {

            $result["id"] = $customer->id;
            $result["firstName"] = $customer->firstname;
            $result["lastName"] = $customer->lastname;
            $result["email"] = $customer->email;


            //si está el modulo de lista blanca activo
            if (Module::isEnabled("blockwishlist")) {

                Module::getInstanceByName("blockwishlist");

                //obtiene toda la listas de deseis que tiene el usuario
                $wishlist = WishList::getByIdCustomer($customer->id);


                //si tiene una lista de deseos en cookies
                if (isset($this->context->cookie->id_wishlist) && $this->context->cookie->id_wishlist !== '' && !empty($wishlist)) {

                    //cojo solo la de la cookie que sera la ultima
                    $wishlist = array_filter($wishlist, function ($v) {
                        return $v['id_wishlist'] == Context::getContext()->cookie->id_wishlist;
                    });


                    if (!empty($wishlist)) {

                        //obtengo el primer elemento
                        $wishlist = array_shift($wishlist);

                        //recojo los productos
                        $products = WishList::getProductByIdCustomer((int)$wishlist['id_wishlist'], $customer->id, $this->context->language->id, null, true);

                        if (!empty($products)) {

                            foreach ($products as $product) {

                                //los meto en el resultado
                                array_push($result["wishlist"], $this->getProductSummary($product['id_product']));

                            }
                        }
                    }

                }

            }

        }

        $cart = Context::getContext()->cart;

        if (!empty($cart)) {

            $in_cart = $cart->getProducts();

            foreach ($in_cart as $in) {

                $aux = $this->getProductSummary($in["id_product"]);
                $aux["qty"] = $in["cart_quantity"];

                array_push($result["cart"], $aux);

            }
        }

        return $result;
    }


    /**
     * Añadir productos a la lista blanca
     * @return bool
     * @throws PrestaShopException
     */
    public function addToWishListOctMeth()
    {


        $result = false;

        try {
            if (Module::isEnabled("blockwishlist")) {

                $customer = Context::getContext()->customer;

                if (!empty($customer) && Validate::isLoadedObject($customer) && $customer->isLogged() && !$customer->deleted) {//Comprobamos que el usuario es accesible

                    $ids = trim(strip_tags(Tools::getValue('productIds')));
                    $ids = explode(',', $ids);
                    Module::getInstanceByName("blockwishlist");

                    $context = Context::getContext();
                    if (!isset($context->cookie->id_wishlist) || $context->cookie->id_wishlist == '') {

                        $wishlist = new WishList();
                        $wishlist->id_shop = $context->shop->id;
                        $wishlist->id_shop_group = $context->shop->id_shop_group;
                        $wishlist->default = 1;

                        $mod_wishlist = new BlockWishList();
                        $wishlist->name = $mod_wishlist->default_wishlist_name;
                        $wishlist->id_customer = (int)$context->customer->id;
                        list($us, $s) = explode(' ', microtime());
                        srand($s * $us);
                        $wishlist->token = Tools::strtoupper(Tools::substr(sha1(uniqid(rand(), true) . _COOKIE_KEY_ . $context->customer->id), 0, 16));
                        $wishlist->add();
                        $context->cookie->id_wishlist = (int)$wishlist->id;
                    }


                    foreach ($ids as $id_product) {

                        $id_product_attribute = (int)Product::getDefaultAttribute($id_product);
                        WishList::addProduct($context->cookie->id_wishlist, $context->customer->id, $id_product, $id_product_attribute, 1);

                    }

                    $result = true;
                }

            } else {

                $result = false;
            }
        } catch (Exception $e) {
            $result = false;
            $this->module->logException($e);
        }

        return $result;

    }

    /**
     * Obtiene el carro de compra
     * @return array
     */
    public function getCartOctMeth()
    {


        $result = array();
        $result["price"] = "0";
        $result["finalPrice"] = "0";
        $result["currency"] = "";
        $result["totalItems"] = "0";
        $result["cart"] = array();

        $cart = Context::getContext()->cart;

        if (!empty($cart)) {

            $result["price"] = $cart->getOrderTotal(false, Cart::ONLY_PRODUCTS);
            $result["finalPrice"] = $cart->getOrderTotal(true, Cart::BOTH);

            $currency = new Currency($cart->id_currency, Context::getContext()->language->id);

            if (Validate::isLoadedObject($currency)) {
                $result["currency"] = $currency->iso_code;
            }

            $in_cart = $cart->getProducts();

            $total = 0;
            foreach ($in_cart as $in) {

                $aux = array();
                $aux["internalId"] = $in["id_product"];
                $aux["title"] = $in["name"];
                $aux["qty"] = $in["cart_quantity"];
                $aux["price"] = $in["price_wt"];
                $total += $in["cart_quantity"];

                array_push($result["cart"], $aux);

            }

            $result["totalItems"] = $total;
        }

        return $result;
    }


    /**
     * Obtiene un informe de los productos vendidos a traves de oct8ne entre dos fechas
     * @return array
     */
    public function getSalesReportOctMeth()
    {

        $from = Tools::getValue("from");
        $to = Tools::getValue("to");
        $apiToken = Tools::getValue("apiToken");

        $in_token = Configuration::get("OCT_API_TOKEN");

        if ($in_token == $apiToken && isset($from) && isset($to)) {


            $result = array();

            $this->module->loadClass('Oct8neHistory');
            $history = new Oct8neHistory();

            $related_data = $history->getRelatedData($from, $to);

            foreach ($related_data as $rel) {

                $item = array();
                $item["quoteid"] = $rel["cart_id"];
                $item["orderId"] = $rel["id_order"];
                $item["sessionId"] = $rel["session_id"];
                $item["customerId"] = $rel["id_customer"];

                $cart = new Cart($rel["cart_id"], Context::getContext()->language->id);

                $order = new Order($rel["id_order"], Context::getContext()->language->id);


                //si hay order saco los datos de order
                if (Validate::isLoadedObject($order)) {

                    $item["price"] = $order->getTotalProductsWithoutTaxes();
                    $item["finalPrice"] = $order->getOrdersTotalPaid();


                    $currency = new Currency($order->id_currency, Context::getContext()->language->id);
                    if (Validate::isLoadedObject($currency)) {
                        $item["currency"] = $currency->iso_code;
                    } else {
                        $item["currency"] = "";
                    }

                    $products = $order->getProducts();
                    $item["productsCount"] = count($products);

                    $total = 0;
                    foreach ($products as $p) {

                        $total += $p["product_quantity"];
                    }
                    $item["itemsCount"] = $total;

                    $item["lastAction"] = ($order->date_add == $order->date_upd) ? 'C' : 'U';

                    $item["utcCreated"] = $order->date_add;
                    $item["utcLastUpdated"] = $order->date_upd;


                    //si no hay order los saco de cart
                } else {
                    $item["price"] = $cart->getOrderTotal(false, Cart::ONLY_PRODUCTS);
                    $item["finalPrice"] = $cart->getOrderTotal(true, Cart::BOTH);

                    $currency = new Currency($cart->id_currency, Context::getContext()->language->id);
                    if (Validate::isLoadedObject($currency)) {
                        $item["currency"] = $currency->iso_code;
                    } else {
                        $item["currency"] = "";
                    }

                    $products = $cart->getProducts();
                    $item["productsCount"] = count($products);

                    $total = 0;
                    foreach ($products as $p) {

                        $total += $p["cart_quantity"];
                    }
                    $item["itemsCount"] = $total;

                    $item["lastAction"] = ($cart->date_add == $order->date_upd) ? 'C' : 'U';
                    $item["utcCreated"] = $cart->date_add;
                    $item["utcLastUpdated"] = $cart->date_upd;

                }

                array_push($result, $item);
            }


            return $result;
        }

    }


    /**
     * Devuelve informacion sobre el modulo
     * @return array
     */
    public function getAdapterInfoOctMeth()
    {

        $result = array();
        $result["platform"] = "Prestashop";
        $result["adapterName"] = "Oct8ne official adapter for Prestashop";
        $result["adapterVersion"] = $this->module->version;
        $result["developedBy"] = "Oct8ne Inc";
        $result["supportUrl"] = "";
        $result["apiVersion"] = "2.3";
        $result["enabled"] = $this->module->active == 1;

        return $result;


    }


}
