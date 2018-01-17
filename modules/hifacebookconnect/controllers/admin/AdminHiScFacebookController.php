<?php
/**
* 2013 - 2017 HiPresta
*
* MODULE Facebook Connect
*
* @version   1.1.0
* @author    HiPresta <suren.mikaelyan@gmail.com>
* @link      http://www.hipresta.com
* @copyright HiPresta 2017
* @license   PrestaShop Addons license limitation
*
*/

class AdminHiScFacebookController extends ModuleAdminController
{
    public function __construct()
    {
        $this->ajax = Tools::getValue('ajax');
        parent::__construct();
    }

    public function init()
    {
        parent::init();
        if ($this->ajax) {
            if (Tools::getValue('action') == 'delete_table') {
                $table_id = (int)Tools::getValue('table_id');
                Db::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.'hifacebookusers WHERE id='.$table_id);
            }
            if (Tools::getValue('action') == 'delete_full') {
                $table_id = (int)Tools::getValue('table_id');
                $email = Db::getInstance()->ExecuteS('SELECT email FROM '._DB_PREFIX_.'hifacebookusers WHERE id='.$table_id);
                if (!empty($email)) {
                    $id_customer = Db::getInstance()->ExecuteS('SELECT id_customer FROM '._DB_PREFIX_.'customer WHERE email=\''.$email[0]['email'].'\'');
                    if (!empty($id_customer)) {
                        $customer = new Customer($id_customer[0]['id_customer']);
                        $customer->delete();
                    }
                }
                Db::getInstance()->Execute('DELETE FROM '._DB_PREFIX_.'hifacebookusers WHERE id='.$table_id);
            }
        } else {
            Tools::redirectAdmin($this->module->getModuleUrl());
        }
    }
}
