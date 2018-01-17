<?php
/**
 * RockPOS - Point of Sale for PrestaShop.
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Abstract class for all upgraders.
 */
abstract class PosUpgrader extends PosSetup
{

    /**
     * @var string // x.y.z
     */
    protected $version;

    /**
     * @var array
     *            <pre>
     *            array(
     *            string, // configuration key
     *            string
     *            ...
     *            )
     */
    protected $configuration_keys_to_uninstall = array();

    /**
     * @var array
     *            <pre>
     *            array(
     *            string, // configuration key
     *            string
     *            ...
     *            )
     */
    protected $configuration_keys_to_rename = array();

    /**
     * @var array
     *            <pre>
     *            array(
     *            string, // configuration key
     *            string
     *            ...
     *            )
     */
    protected $configuration_keys_to_convert_to_multiple_language = array();

    /**
     * @var array
     *            <pre>
     *            array(
     *            string => string,// from_tab => to_tab where tab is a key of HsPointOfSaleAbstract::pos_tabs
     *            string => string,
     *            ...
     *            )
     */
    protected $tabs_to_copy_access = array();

    /**
     * Relative path to files which will be deleted<br/>
     * Base directory: the current module's directory.
     *
     * @var array
     *            <pre>
     *            array(
     *            string,
     *            string,
     *            ...
     *            )
     */
    protected $files_to_clean_up = array();

    /**
     * Relative path to directories which will be deleted<br/>
     * Base directory: the current module's directory.
     *
     * @var array
     *            <pre>
     *            array(
     *            string,
     *            string,
     *            ...
     *            )
     */
    protected $directories_to_clean_up = array();

    /**
     * @param Module $module
     * @param string $version // x.y.z
     */
    public function __construct(Module $module, $version)
    {
        parent::__construct($module);
        $this->version = $version;
    }

    public function run()
    {
        $success = array();
        $success[] = !empty($this->version);
        $success[] = array_sum($success) >= count($success) && parent::run();
        $success[] = array_sum($success) >= count($success) && $this->unregisterHooks();
        $success[] = array_sum($success) >= count($success) && $this->uninstallConfigs();
        $success[] = array_sum($success) >= count($success) && $this->renameConfigs();
        $success[] = array_sum($success) >= count($success) && $this->convertConfigsToMultipleLanguage();
        $success[] = array_sum($success) >= count($success) && $this->cleanUpFiles();
        $success[] = array_sum($success) >= count($success) && $this->cleanUpDirectories();
        $success[] = array_sum($success) >= count($success) && $this->clearCache();

        return array_sum($success) >= count($success);
    }

    /**
     * @return bool
     */
    protected function installTabs()
    {
        $success = array(parent::installTabs());
        // copy accesses
        if (!empty($this->tabs_to_copy_access)) {
            foreach ($this->tabs_to_copy_access as $from_tab => $to_tab) {
                if (!empty($this->module->pos_tabs[$from_tab]) && !empty($this->module->pos_tabs[$to_tab])) {
                    $success[] = PosTab::copyAccesses($this->module->pos_tabs[$from_tab]['class_name'], $this->module->pos_tabs[$to_tab]['tab_class']);
                }
            }
        }

        return array_sum($success) >= count($success);
    }

    /**
     * @return bool
     */
    protected function uninstallConfigs()
    {
        $success = array();
        if (!empty($this->configuration_keys_to_uninstall)) {
            foreach ($this->configuration_keys_to_uninstall as $key) {
                $success[] = PosConfiguration::deleteByName($key);
            }
        }

        return array_sum($success) >= count($success);
    }

    /**
     * @return bool
     */
    protected function renameConfigs()
    {
        $success = array();
        if (!empty($this->configuration_keys_to_rename)) {
            $success[] = PosConfiguration::renameMultiple(array_keys($this->configuration_keys_to_rename), array_values($this->configuration_keys_to_rename));
        }

        return array_sum($success) >= count($success);
    }

    /**
     * @return bool
     */
    protected function convertConfigsToMultipleLanguage()
    {
        $success = array();
        if (empty($this->configuration_keys_to_convert_to_multiple_language)) {
            return array_sum($success) >= count($success);
        }
        foreach ($this->configuration_keys_to_convert_to_multiple_language as $key) {
            $old_value = PosConfiguration::get($key);
            $languages = Language::getLanguages(false);
            $new_values = array();
            foreach ($languages as $lang) {
                $new_values[$lang['id_lang']] = $old_value;
            }
            $success[] = PosConfiguration::deleteByName($key) && PosConfiguration::updateValue($key, $new_values, true);
        }

        return array_sum($success) >= count($success);
    }

    /**
     * @return bool
     */
    protected function unregisterHooks()
    {
        $success = array();
        if (!empty($this->hooks_to_unregister)) {
            foreach ($this->hooks_to_unregister as $hook_name) {
                if (Validate::isHookName($hook_name)) {
                    $success[] = array_sum($success) >= count($success) && $this->module->unregisterHook($hook_name);
                } else {
                    $success[] = false;
                }
            }
        }

        return array_sum($success) >= count($success);
    }

    /**
     * @return bool
     */
    protected function installTables()
    {
        return true;
    }

    /**
     * @return bool
     */
    protected function cleanUpFiles()
    {
        return PosFile::deleteFiles($this->module->getLocalPath(), $this->files_to_clean_up);
    }

    /**
     * @return bool
     */
    protected function cleanUpDirectories()
    {
        return PosFile::deleteDirectories($this->module->getLocalPath(), $this->directories_to_clean_up);
    }
}
