<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Extended Tab for RockPOS.
 */
class PosTab extends Tab
{

    /**
     * Orverride
     *  1/ wrong update position tabs are greater than current position
     * Update position
     *
     * @param bool $way
     * @param int  $position
     *
     * @return boolean
     */
    public function updatePosition($way, $position)
    {
        $success = array();
        $tabs = Db::getInstance()->executeS(
            'SELECT t.`id_tab`, t.`position`, t.`id_parent`
            FROM `' . _DB_PREFIX_ . 'tab` t
            WHERE t.`id_parent` = ' . (int) $this->id_parent . '
            ORDER BY t.`position` ASC'
        );
        $move_tab = array();
        if (!empty($tabs)) {
            foreach ($tabs as $tab) {
                if ((int) $tab['id_tab'] == (int) $this->id) {
                    $move_tab = $tab;
                }
            }
            if (!empty($move_tab)) {
                $success[] = Db::getInstance()->execute(
                    'UPDATE `' . _DB_PREFIX_ . 'tab`
                    SET `position`= `position` + 1
                    WHERE `position` >= ' . (int) $position . '
                    AND `id_parent`=' . (int) $move_tab['id_parent']
                );

                $success[] = Db::getInstance()->execute(
                    'UPDATE `' . _DB_PREFIX_ . 'tab`
                    SET `position` = ' . (int) $position . '
                    WHERE `id_parent` = ' . (int) $move_tab['id_parent'] . '
                    AND `id_tab`=' . (int) $move_tab['id_tab']
                );
            }
        }
        return array_sum($success) >= count($success);
    }

    /**
     *
     * @return array of Tab
     */
    public static function getOutOfDateTabs()
    {
        $query = new DbQuery();
        $query->select('t.*');
        $query->from('tab', 't');
        $query->where('t.`module` = \'hspointofsalepro\'');
        $query->where('t.`class_name` NOT IN (\'AdminPos\', \'AdminRockPosSales\')');
        $result = Db::getInstance()->executeS($query, true, false);
        $tab = new self();
        return $tab->hydrateCollection('PosTab', $result);
    }

    /**
     * @param string $from_tab_class
     * @param string $to_tab_class
     *
     * @return bool
     */
    public static function copyAccesses($from_tab_class, $to_tab_class)
    {
        $from_id_tab = (int) Tab::getIdFromClassName($from_tab_class);
        $to_id_tab = (int) Tab::getIdFromClassName($to_tab_class);
        $query = 'REPLACE INTO `' . _DB_PREFIX_ . 'access` (`id_profile`, `id_tab`, `view`, `add`, `edit`, `delete`) '
                . 'SELECT `id_profile`, ' . (int) $to_id_tab . ', `view`, `add`, `edit`, `delete` FROM `' . _DB_PREFIX_ . 'access` WHERE `id_tab` = ' . (int) $from_id_tab;

        return Db::getInstance()->execute($query);
    }

    /**
     * @param string $parent_tab
     * @param string $suffix
     *
     * @return bool
     */
    public static function addSuffix($parent_tab, $suffix)
    {
        $success = array();
        $id_parent = (int) Tab::getIdFromClassName($parent_tab);
        $tabs = new PrestaShopCollection('Tab');
        $tabs->where('id_parent', '=', $id_parent);
        foreach ($tabs as $tab) {
            if (strpos($tab->class_name, $suffix) === false) {
                $tab->class_name .= $suffix;
                $success[] = $tab->update();
            }
        }

        return array_sum($success) >= count($success);
    }

    /**
     * @param string $module_name
     *
     * @return bool
     */
    public static function resetPositions($module_name)
    {
        $success = array();
        if (Db::getInstance()->execute('SET @i = 0', false)) {
            $success[] = Db::getInstance()->execute('UPDATE `' . _DB_PREFIX_ . 'tab` SET `position` = @i:=@i+1 WHERE `module` = "' . pSQL($module_name) . '" AND `id_parent` > 0 ORDER BY `position` ASC, `id_tab` DESC');
        }

        return array_sum($success) >= count($success);
    }

    /**
     * @param int $id_tab
     * @param int $position
     *
     * @return bool
     */
    public static function updateTabPosition($id_tab, $position)
    {
        $flag = true;
        $pos_tab = new self($id_tab);
        if (Validate::isLoadedObject($pos_tab)) {
            $pos_tab->position = (int) $position;
            $flag = $pos_tab->update();
        }

        return $flag;
    }
}
