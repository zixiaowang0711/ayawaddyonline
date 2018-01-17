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
class PosModuleFrontController extends ModuleFrontController
{
    /**
     * Static actions are ones those don't require session timeout.
     *
     * @var array
     * <pre>
     * array(
     *   string,
     *   string
     * )
     */
    protected $static_actions = array(
        'getAllInOne',
        'login',
        'forgotPasswd',
    );
    /**
     *
     * @see parent::postProcess();
     */
    public function postProcess()
    {
         // Check session timeout
        if (!Context::getContext()->cookie->pos_id_employee) {
            if ((int) Tools::getValue('ajax') === 1 && in_array(Tools::getValue('action'), $this->static_actions)) {
                $this->ajax_json['message'] = $this->module->i18n['oops_your_session_just_expired'];
                $this->ajax_json['data']['timeout'] = true;
                die(Tools::jsonEncode($this->ajax_json));
            }
        }
        parent::postProcess();
        $action = Tools::toCamelCase(Tools::getValue('action'), true);
        if ($this->ajax) {
            if (!empty($action) && method_exists($this, 'ajaxProcess'.$action)) {
                $this->{'ajaxProcess'.$action}();
            } elseif (method_exists($this, 'ajaxProcess')) {
                return $this->ajaxProcess();
            }
        }
        if (!empty($action) && method_exists($this, 'process'.Tools::ucfirst(Tools::toCamelCase($action)))) {
            // Call process
            return $this->{'process'.Tools::toCamelCase($action)}();
        }
    }

    /**
     * Copy datas from $_POST to object
     *
     * @param object &$object Object
     * @param string $table Object table
     */
    protected function copyFromPost(&$object, $table)
    {
        /* Classical fields */
        foreach ($_POST as $key => $value) {
            if (array_key_exists($key, $object) && $key != 'id_'.$table) {
                /* Do not take care of password field if empty */
                if ($key == 'passwd' && Tools::getValue('id_'.$table) && empty($value)) {
                    continue;
                }
                /* Automatically encrypt password in MD5 */
                if ($key == 'passwd' && !empty($value)) {
                    $value = Tools::encrypt($value);
                }
                $object->{$key} = $value;
            }
        }

        /* Multilingual fields */
        $rules = call_user_func(array(get_class($object), 'getValidationRules'), get_class($object));
        if (count($rules['validateLang'])) {
            $language_ids = Language::getIDs(false);
            foreach ($language_ids as $id_lang) {
                foreach (array_keys($rules['validateLang']) as $field) {
                    if (Tools::isSubmit($field.'_'.(int)$id_lang)) {
                        $object->{$field}[(int)$id_lang] = Tools::getValue($field.'_'.(int)$id_lang);
                    }
                }
            }
        }
    }
}
