<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * An "interface" for installer and upgraders.
 */
abstract class PosSetup
{
    /**
     * @var Module
     */
    protected $module;

    /**
     * @var array
     *            <pre>
     *            array(
     *            string, // hook name, validated against Validate::isHookName()
     *            string
     *            ...
     *            )
     */
    protected $hooks_to_register = array();

    /**
     * @var array
     *            <pre>
     *            array(
     *            string, // hook name
     *            string
     *            ...
     *            )
     */
    protected $hooks_to_unregister = array();

    /**
     * @var array
     *            <pre>
     *            array(
     *            string => mixed,// configuration key => value
     *            string => mixed,
     *            ...
     *            )
     */
    protected $configurations_to_install = array();

    /**
     * @var array
     *            <pre>
     *            array(
     *            string,
     *            string
     *            )
     */
    protected $tabs_to_install = array();

    /**
     * @param Module $module
     */
    public function __construct(Module $module)
    {
        $this->module = $module;
    }

    /**
     * @return bool
     */
    public function run()
    {
        $success = array();
        $success[] = $this->install();
        return array_sum($success) >= count($success);
    }

    /**
     * @return bool
     */
    public function install()
    {
        $success = array();
        $success[] = $this->installConfigs();
        $success[] = array_sum($success) >= count($success) && $this->installTabs();
        $success[] = array_sum($success) >= count($success) && $this->installTables();
        $success[] = array_sum($success) >= count($success) && $this->registerHooks();
        $success[] = array_sum($success) >= count($success) && $this->installOthers();
        $success[] = array_sum($success) >= count($success) && $this->clearCache();
        return array_sum($success) >= count($success);
    }

    /**
     * @return bool
     */
    protected function uninstall()
    {
        return true;
    }

    /**
     * @return bool
     */
    protected function clearCache()
    {
        // Copied from AdminPerformanceController::postProcess()
        Tools::clearSmartyCache();
        Media::clearCache();

        return true;
    }

    /**
     * @return bool
     */
    protected function registerHooks()
    {
        $success = array();
        if (!empty($this->hooks_to_register)) {
            foreach ($this->hooks_to_register as $hook_name) {
                if (Validate::isHookName($hook_name)) {
                    $success[] = array_sum($success) >= count($success) && $this->module->registerHook($hook_name);
                } else {
                    $success[] = false;
                }
            }
        }

        return array_sum($success) >= count($success);
    }

    /**
     * @param array $admin_tab
     *                         <pre>
     *                         array(
     *                         'active' => boolean,
     *                         'name' => string,
     *                         'position' => int,
     *                         'tab_class' => string
     *                         )
     *
     * @return bool
     */
    protected function installTab(array $admin_tab)
    {
        $success = array();
        if ($admin_tab['class_name'] == $this->module->parent_admin_tab) {
            $append_tab_id = (int) Tab::getIdFromClassName(PosConstants::APPEND_ADMIN_TAB);
            $append_tab = new Tab($append_tab_id);
            $parent_tab_id = $append_tab->id_parent;
        } else {
            $parent_tab_id = (int) Tab::getIdFromClassName($this->module->parent_admin_tab);
        }
        $tab = new PosTab();
        $names = array();
        foreach (Language::getLanguages(false) as $language) {
            $names[$language['id_lang']] = $admin_tab['name'];
        }
        $tab->name = $names;
        $tab->class_name = $admin_tab['class_name'];
        $tab->module = $this->module->name;
        $tab->active = (int) $admin_tab['active'];
        $tab->icon = !empty($admin_tab['icon']) ? $admin_tab['icon'] : '';
        $tab->position = (int) $admin_tab['position'];
        if ($parent_tab_id != null) {
            $tab->id_parent = $parent_tab_id;
        }

        $success[] = $tab->add(true);
        if ($admin_tab['class_name'] == $this->module->parent_admin_tab) {
            if (Tools::version_compare(_PS_VERSION_, '1.7', '>=')) {
                $success[] = $this->updateIconTab($tab->id);
            }
            $success[] = $tab->updatePosition(true, $this->getPosition());
        }
        return array_sum($success) >= count($success);
    }

    /**
     * Get position of tab AdminParentOrders.
     *
     * @return int
     */
    protected function getPosition()
    {
        $position = 0;
        $id_tab = (int) Tab::getIdFromClassName(PosConstants::APPEND_ADMIN_TAB);
        $tab = new Tab($id_tab);
        if (Validate::isLoadedObject($tab)) {
            $position = $tab->position;
        }
        return (int) $position;
    }

    /**
     * @return bool
     */
    protected function installConfigs()
    {
        $success = array();
        if (!empty($this->configurations_to_install)) {
            foreach ($this->configurations_to_install as $key => $value) {
                $success[] = Configuration::updateValue($key, $value);
            }
        }

        return array_sum($success) >= count($success);
    }

    /**
     * @return bool
     */
    protected function installTabs()
    {
        $success = array();
        if (!empty($this->tabs_to_install)) {
            foreach ($this->tabs_to_install as $tab) {
                $success[] = !empty($this->module->pos_tabs[$tab]) && $this->installTab($this->module->pos_tabs[$tab]);
            }
        }

        return array_sum($success) >= count($success);
    }

    /**
     *
     * @param int $id_tab
     * @return bool
     */
    protected function updateIconTab($id_tab)
    {
        return Db::getInstance()->execute(
            'UPDATE `' . _DB_PREFIX_ . 'tab` SET `icon` = \'shopping_cart\'
            WHERE `id_tab` = ' . (int) $id_tab
        );
    }

    /**
     * @return bool
     */
    protected function installOthers()
    {
        return true;
    }
    abstract protected function installTables();
}
