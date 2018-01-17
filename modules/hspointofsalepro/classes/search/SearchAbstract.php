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
abstract class SearchAbstract extends Search
{

    /**
     *
     * @var string
     */
    public $keyword;

    /**
     *
     * @var int
     */
    public $id_lang;

    /**
     *
     * @var Shop
     */
    public $shop;

    /**
     *
     * @var int
     */
    public $limit = 12;

    /**
     *
     * @var int
     */
    public $offest = 0;

    /**
     *
     * @param string $keyword
     * @param int $id_lang
     * @param Shop $shop
     */
    public function __construct($keyword = null, $id_lang = null, Shop $shop = null)
    {

        $context = Context::getcontext();
        if (!is_null($keyword)) {
            $this->keyword = $keyword;
        }
        if (is_null($id_lang)) {
            $this->id_lang = $context->language->id;
        }
        if (is_null($shop)) {
            $this->shop = $context->shop;
        }
    }
    abstract public function search();
}
