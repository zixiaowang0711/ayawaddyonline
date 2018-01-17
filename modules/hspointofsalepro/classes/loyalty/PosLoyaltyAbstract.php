<?php
/**
 * RockPOS PosLoyaltyAbstract
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 *
 */
abstract class PosLoyaltyAbstract
{
    public $voucher_code;
    public $module_name;

    abstract public function convertPoints($param);
    abstract public function getLoyaltyPoints($param);
    abstract public function registerDiscount($param);
    abstract public function getLatestLoyaltyDate($param);
    abstract public function getRestrictedIdCategories();
    abstract public function getLoyaltyTax();
    abstract public function getMinimumAmount();

    /**
     *
     * @param string $module_name
     * @return boolean
     */
    protected function isValidModule()
    {
        return Module::isEnabled($this->module_name) && Module::getInstanceByName($this->module_name);
    }

    /**
     *
     * @param string $default_name
     * @return boolean
     */
    protected function generateName($default_name)
    {
        $names_by_lang = array();
        if ($this->isValidModule()) {
            $languages = Language::getLanguages(true);
            $default_text = Configuration::get($default_name, (int) Configuration::get('PS_LANG_DEFAULT'));
            foreach ($languages as $language) {
                $text = Configuration::get($default_name, (int) $language['id_lang']);
                $names_by_lang[(int) $language['id_lang']] = $text ? $text : $default_text;
            }
        }
        return $names_by_lang;
    }
}
