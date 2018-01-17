<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Extended Translate for RockPOS.
 */
class PosTranslate extends Translate
{

    /**
     * We will only translate for the first sentence in a translation string
     * Flow:
     * + Split string by ". or ? " to get only first sentence
     * + Replace special character in that string like "%s,!,..." by "-"
     * + Use Tools::str2url to make the string friendly as an URL.
     *
     * @param string $string
     *
     * @return string
     */
    public static function getTranslationKey($string)
    {
        // Get the first sentence, refer to this link: http://stackoverflow.com/questions/8313430/regex-to-get-rid-of-everything-past-the-first-sentence-in-a-string-in-php
        $first_sentence = preg_replace('/(\.|\?)\s[A-Z](.+)/', '', $string);
        // Remove all words which store in string start with "[" and end with "]" and it is a variable
        $new_string = preg_replace('/\\[(.*?)_(.*?)\\]/is', '', $first_sentence);
        // Replace some special characters in translate string
        $search_array = array(
            '&quot;',
            '%1$s',
            '%s',
            '!',
            '.',
            '$s',
        );
        $replace_array = array(
            '', '', '-', '-', '-', '-',
        );
        // replace "-" by "_" to correct the translation key format
        $translation_key = str_replace(array('-'), array('_'), Tools::str2url(str_replace($search_array, $replace_array, strip_tags($new_string))));

        return rtrim($translation_key, '_');
    }

    /**
     * @param array $translation_keys
     *
     * @return array
     *               <pre>
     *               array(
     *               'correct key 1' => wrong key 1,
     *               'correct key 2' => wrong key 2,
     *               ..............................
     *               )
     */
    public static function getWrongTranslationKeys(array $translation_keys)
    {
        $wrong_translation_keys = array();
        foreach ($translation_keys as $key => $value) {
            $translation_key = self::getTranslationKey($value);
            if ($translation_key != $key && !empty($translation_key)) {
                $wrong_translation_keys[$translation_key] = $key;
            }
        }

        return $wrong_translation_keys;
    }
}
