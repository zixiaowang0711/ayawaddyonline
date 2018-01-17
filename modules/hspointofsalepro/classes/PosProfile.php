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
class PosProfile extends Profile
{

    /**
     * Get the current profile name
     *
     * @return string Profile
     */
    public static function getProfiles($id_profile, $id_lang = null)
    {
        $profiles = parent::getProfiles($id_profile, $id_lang);
        $select_id_profile = explode(',', Configuration::get('POS_ID_PROFILES'));
        if (!empty($profiles)) {
            foreach ($profiles as &$profile) {
                $profile['checked'] = in_array($profile['id_profile'], $select_id_profile) ? 1 : 0;
            }
        }
        return $profiles;
    }
}
