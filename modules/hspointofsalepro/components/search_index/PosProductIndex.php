<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Product index for Point of Sale.
 */
class PosProductIndex
{

    /**
     * @var int
     */
    public $id_product;

    /**
     * @var int
     */
    public $id_shop;

    /**
     * @var int
     */
    public $id_lang;

    /**
     * @var array
     *            <pre>
     *            array(
     *            string => PosWordIndex
     *            ...
     *            )</pre>
     */
    protected $word_indexes = array();

    /**
     * @param int $id_product
     * @param int $id_shop
     * @param int $id_lang
     */
    public function __construct($id_product, $id_shop, $id_lang)
    {
        $this->id_product = (int) $id_product;
        $this->id_shop = (int) $id_shop;
        $this->id_lang = (int) $id_lang;
    }

    /**
     * @param array $words
     *                      <pre>
     *                      array(
     *                      'string',
     *                      'string',
     *                      ...
     *                      )</pre>
     * @param int   $weight
     */
    public function addWordIndexes(array $words, $weight)
    {
        if (empty($words)) {
            return;
        }
        if ($weight <= 0) {
            return;
        }
        foreach ($words as $word) {
            $pos_word_index = new PosWordIndex($word, (int) $this->id_lang, (int) $this->id_shop);
            $pos_word_index->setWeight((int) $weight);
            $pos_word_index->setIdProduct((int) $this->id_product);
            $this->addWordIndex($pos_word_index);
        }
    }

    /**
     * @param PosWordIndex $word_index
     */
    public function addWordIndex(PosWordIndex $word_index)
    {
        if (isset($this->word_indexes[$word_index->word])) {
            $this->word_indexes[$word_index->word]->addWeight($word_index->getWeight());
        } else {
            $this->word_indexes[$word_index->word] = $word_index;
        }
    }

    public function indexing()
    {
        if (empty($this->word_indexes)) {
            return;
        }
        $word_indexes = array();
        foreach ($this->word_indexes as $word_index) {
            $word_indexes[] = implode(',', array(
                (int) $this->id_lang,
                (int) $this->id_shop,
                '\'' . pSQL($word_index->word) . '\'',
            ));
        }
        Db::getInstance()->execute('INSERT IGNORE INTO `' . _DB_PREFIX_ . 'pos_search_word` (`id_lang`, `id_shop`, `word`) VALUES (' . implode('),(', $word_indexes) . ')');

        $added_words = $this->getAddedWords();
        foreach ($added_words as $record) {
            if (isset($this->word_indexes[$record['word']])) {
                $this->word_indexes[$record['word']]->id_word = (int) $record['id_word'];
            }
        }

        $search_indexes = array();
        foreach ($this->word_indexes as $word_index) {
            $search_indexes[] = implode(',', array(
                $word_index->getIdProduct(),
                $word_index->id_word,
                $word_index->getWeight(),
            ));
        }
        Db::getInstance()->execute('INSERT INTO `' . _DB_PREFIX_ . 'pos_search_index` (`id_product`, `id_word`, `weight`) VALUES (' . implode('),(', $search_indexes) . ') ON DUPLICATE KEY UPDATE `weight` = `weight` + VALUES(weight)');
    }

    /**
     * @return array
     *               <pre>
     *               array(
     *               int => array(
     *               'id_word' => int,
     *               'word' => string
     *               ),
     *               ...
     *               )
     */
    protected function getAddedWords()
    {
        $query = new DbQuery();
        $query->select('`id_word`, `word`');
        $query->from('pos_search_word');
        $query->where('`word` IN ("' . implode('","', array_keys($this->word_indexes)) . '")');
        $query->where('`id_lang` = ' . (int) $this->id_lang);
        $query->where('`id_shop` = ' . (int) $this->id_shop);

        return Db::getInstance()->executeS($query);
    }
}
