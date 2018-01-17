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
class PosEmployee extends Employee
{

    /**
     * @param array $id_profiles
     * array(<pre>
     *      int,// id profile
     *      ...
     * )</pre>
     * @param boolean $active_only
     * @return array
     * array(<pre>
     *  0 => array(
     *          'id_employee' => int,
     *          'fullname' => string
     *      ),
     *  ...
     * )</pre>
     */
    public static function getEmployeesByIdProfiles(array $id_profiles, $active_only = true)
    {
        $employees = array();
        if (!empty($id_profiles)) {
            $query = new DbQuery();
            $query->select('DISTINCT e.`id_employee`');
            $query->select('CONCAT(e.`firstname`, \' \', e.`lastname`) AS `fullname`');
            $query->from('employee', 'e');
            $query->join(Shop::addSqlAssociation('employee', 'e'));
            $query->where($active_only ? 'e.`active` = 1' : null);
            $query->orderBy('e.`firstname`');
            $query->orderBy('e.`lastname`');
            $query->where('e.`id_profile` IN (' . implode(',', $id_profiles) . ')');
            $employees = Db::getInstance(_PS_USE_SQL_SLAVE_)->executeS($query);
        }
        return $employees;
    }

    /**
     *
     * @return string
     */
    public function getToken()
    {
        return md5(_COOKIE_KEY_ . (int) $this->id . $this->passwd);
    }

    /**
     * @param int $id_employee
     * @return array
     * array(<pre>
     *   int => array(
     *           selected => boolean,
     *           id_shop => int,
     *           name => string
     *           ),
     *           ..............
     * </pre>)
     */
    public function getListShops($id_employee)
    {
        $associated_shops = $this->getAssociatedShops();
        $shops_collection = new PrestaShopCollection('PosShop');
        $shops_collection = $shops_collection->where('id_shop', 'in', $associated_shops);
        $shops = array();
        $context = Context::getContext();
        $custom_pos_uri = Configuration::get('POS_CUSTOM_URI') ? Configuration::get('POS_CUSTOM_URI') : 'pos';
        foreach ($shops_collection as $key => $shop) {
            $shop->setURL();
            $shops[$key]['selected'] = $shop->id == $context->shop->id;
            $shops[$key]['id_shop'] = $shop->id;
            $shops[$key]['name'] = $shop->name;
            $shops[$key]['shop_url'] = $shop->getBaseURL();
            $shops[$key]['pos_url'] = Configuration::get('PS_REWRITING_SETTINGS') ?
                    $shop->getBaseURL() . $custom_pos_uri . '?token=' . $this->getToken() :
                    $context->link->getModuleLink(Configuration::get('POS_ROCKPOS_NAME'), 'sales', array('token' => $this->getToken()), null, null, $shop->id);
        }
        return $shops;
    }

    /**
     *
     * @return boolean
     */
    protected function isEnableMLM()
    {
        return property_exists($this, 'id_parent');
    }

    /**
     *
     * @return boolean
     */
    protected function isRootLevel()
    {
        return $this->isEnableMLM() && $this->id_parent <= 0 && $this->id_profile != Configuration::get('SCUSTOMER_GROUP1');
    }

    /**
     *
     * @param int $id_employee
     * @return array
     * <pre>
     * array(
     *  int => int,
     *  ...
     * )
     */
    protected function getSubEmployees($id_employee = null)
    {
        $id_employees = array();
        $query = new DbQuery();
        $query->select('id_employee');
        $query->from('employee');
        $query->where('id_parent = ' . (!empty($id_employee) ? (int) $id_employee : (int) $this->id));
        $employees = Db::getInstance()->executeS($query);
        if (!empty($employees)) {
            foreach ($employees as $employees) {
                $id_employees[] = $employees['id_employee'];
                $id_employees = array_merge($id_employees, $this->getSubEmployees($employees['id_employee']));
            }
        }
        return $id_employees;
    }

    /**
     *
     * @return array
     * <pre>
     * array(
     *  int => int,
     *  ...
     * )
     */
    public function getIdEmployees()
    {
        $id_employees = array();
        if ($this->isEnableMLM()) {
            if (!$this->isRootLevel()) {
                $id_employees = array_merge(array($this->id), $this->getSubEmployees());
            }
        }
        return $id_employees;
    }
}
