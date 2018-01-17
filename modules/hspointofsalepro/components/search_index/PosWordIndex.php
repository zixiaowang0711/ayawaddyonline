<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Word index for Point of Sale.
 */
class PosWordIndex
{
    /**
     * @var int
     */
    public $id_word;

    /**
     * @var int
     */
    public $id_lang;

    /**
     * @var int
     */
    public $id_shop;

    /**
     * @var string
     */
    public $word;

    /**
     * @var int
     */
    protected $weight;

    /**
     * @var int
     */
    protected $id_product;

    /**
     * @param string $word
     * @param int    $id_lang
     * @param int    $id_shop
     * @param int    $id_word
     */
    public function __construct($word, $id_lang = 0, $id_shop = 0, $id_word = 0)
    {
        $this->word = trim($word);
        $this->id_lang = (int) $id_lang;
        $this->id_shop = (int) $id_shop;
        $this->id_word = (int) $id_word;
    }

    /**
     * @param int $weight
     */
    public function setWeight($weight)
    {
        if ($weight > 0) {
            $this->weight = (int) $weight;
        }
    }

    /**
     * @param int $weight
     */
    public function addWeight($weight)
    {
        if ($weight > 0) {
            $this->weight += (int) $weight;
        }
    }

    /**
     * @return int
     */
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * @param int $id_product
     */
    public function setIdProduct($id_product)
    {
        $this->id_product = (int) $id_product;
    }

    /**
     * @return int
     */
    public function getIdProduct()
    {
        return $this->id_product;
    }
}
