<?php
/**
* Module My Easy ERP Web In Color 
* 
*  @author    Web In Color - addons@webincolor.fr
*  @version 2.6
*  @uses Prestashop modules
*  @since 1.0 - mai 2014
*  @package Wic ERP
*  @copyright Copyright &copy; 2014, Web In Color
*  @license   http://www.webincolor.fr
*/

@ini_set('max_execution_time', 0);
/** No max line limit since the lines can be more than 4096. Performance impact is not significant. */
define('MAX_LINE_SIZE_ERP', 0);

/** Used for validatefields diying without user friendly error or not */
define('UNFRIENDLY_ERROR_ERP', false);

/** this value set the number of columns visible on each page */
define('MAX_COLUMNS_ERP', 6);

/** correct Mac error on eof */
@ini_set('auto_detect_line_endings', '1');

require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpOrder.php';
require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpOrderDetail.php';
require_once _PS_MODULE_DIR_.'wic_erp/classes/ErpOrderState.php';

class AdminErpImportController extends ModuleAdminController
{
    public static $column_mask;

    public $entities = array();

    public $available_fields = array();

    public $required_fields = array();

    public $cache_image_deleted = array();

    public static $default_values = array();

    public static $validators = array(
        'wholesale_price' => array('AdminErpImportController', 'getPrice'),
    );

    public $separator;
    public $multiple_value_separator;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->context = Context::getContext();
        // @since 1.5.
        $this->entities = array_merge(
            $this->entities,
            array(
                $this->l('Supply Order Details'),
                $this->l('Update stock'),
            )
        );
        
        $this->entities = array_flip($this->entities);

        // @since 1.5.0
        switch ((int)Tools::getValue('entity')) {
            case $this->entities[$this->l('Supply Order Details')]:
                // required fields
                $this->required_fields = array(
                    'product_reference',
                    'quantity_expected',
                );
                // available fields
                $this->available_fields = array(
                    'no' => array('label' => $this->l('Ignore this column')),
                    'product_reference' => array('label' => $this->l('Product reference or EAN or UPC *')),
                    'unit_price_te' => array('label' => $this->l('Unit Price (tax excl.)')),
                    'quantity_expected' => array('label' => $this->l('Quantity Expected *')),
                );
                break;
            
            case $this->entities[$this->l('Update stock')]:
                // required fields
                $this->required_fields = array(
                    'product_reference',
                    'quantity',
                );
                // available fields
                $this->available_fields = array(
                    'no' => array('label' => $this->l('Ignore this column')),
                    'product_reference' => array('label' => $this->l('Product reference or EAN or UPC *')),
                    'quantity' => array('label' => $this->l('Quantity *')),
                );
                break;
        }

        $this->separator = ($separator = Tools::substr(trim(Tools::getValue('separator')), 0, 1)) ? $separator :  ';';
        $this->multiple_value_separator = ($separator = Tools::substr(trim(Tools::getValue('multiple_value_separator')), 0, 1)) ? $separator :  ',';
        parent::__construct();
    }

    public function setMedia()
    {
        $bo_theme = ((Validate::isLoadedObject($this->context->employee)
            && $this->context->employee->bo_theme) ? $this->context->employee->bo_theme : 'default');

        if (!file_exists(_PS_BO_ALL_THEMES_DIR_.$bo_theme.DIRECTORY_SEPARATOR.'template')) {
            $bo_theme = 'default';
        }

        // We need to set parent media first, so that jQuery is loaded before the dependant plugins
        parent::setMedia();

        $this->addJs(_PS_ADMIN_DIR_.'/themes/'.$bo_theme.'/js/jquery.iframe-transport.js');
        $this->addJs(_PS_ADMIN_DIR_.'/themes/'.$bo_theme.'/js/jquery.fileupload.js');
        $this->addJs(_PS_ADMIN_DIR_.'/themes/'.$bo_theme.'/js/jquery.fileupload-process.js');
        $this->addJs(_PS_ADMIN_DIR_.'/themes/'.$bo_theme.'/js/jquery.fileupload-validate.js');
        $this->addJs(__PS_BASE_URI__.'js/vendor/spin.js');
        $this->addJs(__PS_BASE_URI__.'js/vendor/ladda.js');
    }

    public function renderForm()
    {
        if (!is_dir(AdminErpImportController::getPath())) {
            return !($this->errors[] = Tools::displayError('The import directory does not exist.'));
        }

        if (!is_writable(AdminErpImportController::getPath())) {
            $this->displayWarning($this->l('The import directory must be writable (CHMOD 755 / 777).'));
        }

        if (isset($this->warnings) && count($this->warnings)) {
            $warnings = array();
            foreach ($this->warnings as $warning) {
                $warnings[] = $warning;
            }
        }

        $files_to_import = scandir(AdminErpImportController::getPath());
        uasort($files_to_import, array('AdminErpImportController', 'usortFiles'));
        foreach ($files_to_import as $k => &$filename) {
            //exclude .  ..  .svn and index.php and all hidden files
            if (preg_match('/^\..*|index\.php/i', $filename)) {
                unset($files_to_import[$k]);
            }
        }
        unset($filename);

        $this->fields_form = array('');

        $this->toolbar_scroll = false;
        $this->toolbar_btn = array();

        // adds fancybox
        $this->addJqueryPlugin(array('fancybox'));

        $entity_selected = 0;
        if (isset($this->entities[$this->l(Tools::ucfirst(Tools::getValue('import_type')))])) {
            $entity_selected = $this->entities[$this->l(Tools::ucfirst(Tools::getValue('import_type')))];
            $this->context->cookie->entity_selected = (int)$entity_selected;
        } elseif (isset($this->context->cookie->entity_selected)) {
            $entity_selected = (int)$this->context->cookie->entity_selected;
        }

        $csv_selected = '';
        if (isset($this->context->cookie->csv_selected) && @filemtime(AdminErpImportController::getPath(
            urldecode($this->context->cookie->csv_selected)))) {
            $csv_selected = urldecode($this->context->cookie->csv_selected);
        } else {
            $this->context->cookie->csv_selected = $csv_selected;
        }

        $id_lang_selected = '';
        if (isset($this->context->cookie->iso_lang_selected) && $this->context->cookie->iso_lang_selected) {
            $id_lang_selected = (int)Language::getIdByIso(urldecode($this->context->cookie->iso_lang_selected));
        }

        $separator_selected = $this->separator;
        if (isset($this->context->cookie->separator_selected) && $this->context->cookie->separator_selected) {
            $separator_selected = urldecode($this->context->cookie->separator_selected);
        }

        $multiple_value_separator_selected = $this->multiple_value_separator;
        if (isset($this->context->cookie->multiple_value_separator_selected) && $this->context->cookie->multiple_value_separator_selected) {
            $multiple_value_separator_selected = urldecode($this->context->cookie->multiple_value_separator_selected);
        }

        //get post max size
        $post_max_size = ini_get('post_max_size');
        $bytes         = (int) trim($post_max_size);
        $last          = Tools::strtolower($post_max_size[Tools::strlen($post_max_size) - 1]);

        switch ($last) {
            case 'g': 
                $bytes *= 1024;
                // No break
            case 'm': 
                $bytes *= 1024;
                // No break
            case 'k': 
                $bytes *= 1024;
                // No break
        }

        if (!isset($bytes) || $bytes == '') {
            $bytes = 20971520;
        } // 20Mb

        //Get Erp orders in create states
        $erp_orders = ErpOrder::supplierHasCreateOrders();
        $erp_list_orders = array();
        
        if ($erp_orders) {
            foreach ($erp_orders as $erp_order) {
                $erp_order_obj = new ErpOrder($erp_order['id_erp_order']);
                if (Validate::isLoadedObject($erp_order_obj)) {
                    $erp_order_state = new ErpOrderState((int)$erp_order_obj->id_erp_order_state, Context::getContext()->language->id);
                    if (Validate::isLoadedObject($erp_order_state)) {
                        $erp_order_obj->state_name = $erp_order_state->name;
                        $erp_order_obj->state_color = $erp_order_state->color;
                    }

                    $erp_list_orders[] = $erp_order_obj;
                }
            }
        }
        
        if (Tools::getValue('wic_order_import')) {
            $wic_order_import = true;
        } else {
            $wic_order_import = false;
        }
        
        $this->tpl_form_vars = array(
            'post_max_size' => (int)$bytes,
            'module_confirmation' => Tools::isSubmit('import') && (isset($this->warnings) && !count($this->warnings)),
            'path_import' => AdminErpImportController::getPath(),
            'entities' => $this->entities,
            'entity_selected' => $entity_selected,
            'csv_selected' => $csv_selected,
            'separator_selected' => $separator_selected,
            'multiple_value_separator_selected' => $multiple_value_separator_selected,
            'files_to_import' => $files_to_import,
            'languages' => Language::getLanguages(false),
            'id_language' => ($id_lang_selected) ? $id_lang_selected : $this->context->language->id,
            'available_fields' => $this->getAvailableFields(),
            'truncateAuthorized' => (Shop::isFeatureActive() && $this->context->employee->isSuperAdmin()) || !Shop::isFeatureActive(),
            'erp_list_orders' => $erp_list_orders,
            'wic_order_import' => $wic_order_import,
        );

        return parent::renderForm();
    }

    public function ajaxProcessuploadCsv()
    {
        $filename_prefix = date('YmdHis').'-';

        if (isset($_FILES['file']) && !empty($_FILES['file']['error'])) {
            switch ($_FILES['file']['error']) {
                case UPLOAD_ERR_INI_SIZE:
                    $_FILES['file']['error'] = Tools::displayError('The uploaded file exceeds the upload_max_filesize directive in php.ini. If your server configuration allows it, you may add a directive in your .htaccess.');
                    break;
                case UPLOAD_ERR_FORM_SIZE:
                    $_FILES['file']['error'] = Tools::displayError('The uploaded file exceeds the post_max_size directive in php.ini.
						If your server configuration allows it, you may add a directive in your .htaccess, for example:')
                    .'<br/><a href="'.$this->context->link->getAdminLink('AdminMeta').'" >
					<code>php_value post_max_size 20M</code> '.
                    Tools::displayError('(click to open "Generators" page)').'</a>';
                    break;
                break;
                case UPLOAD_ERR_PARTIAL:
                    $_FILES['file']['error'] = Tools::displayError('The uploaded file was only partially uploaded.');
                    break;
                break;
                case UPLOAD_ERR_NO_FILE:
                    $_FILES['file']['error'] = Tools::displayError('No file was uploaded.');
                    break;
                break;
            }
        } elseif (!preg_match('/.*\.csv$/i', $_FILES['file']['name'])) {
            $_FILES['file']['error'] = Tools::displayError('The extension of your file should be .csv.');
        } elseif (!@filemtime($_FILES['file']['tmp_name']) ||
            !@move_uploaded_file($_FILES['file']['tmp_name'], AdminErpImportController::getPath().$filename_prefix.str_replace("\0", '', $_FILES['file']['name']))) {
            $_FILES['file']['error'] = $this->l('An error occurred while uploading / copying the file.');
        } else {
            @chmod(AdminErpImportController::getPath().$filename_prefix.$_FILES['file']['name'], 0664);
            $_FILES['file']['filename'] = $filename_prefix.str_replace('\0', '', $_FILES['file']['name']);
        }

        die(Tools::jsonEncode($_FILES));
    }

    public function renderView()
    {
        $this->addJS(_PS_MODULE_DIR_.'wic_erp/views/js/adminImport.js');

        $handle = $this->openCsvFile();
        $nb_column = $this->getNbrColumn($handle, $this->separator);
        $nb_table = ceil($nb_column / MAX_COLUMNS_ERP);

        $res = array();
        foreach ($this->required_fields as $elem) {
            $res[] = '\''.$elem.'\'';
        }

        $data = array();
        for ($i = 0; $i < $nb_table; $i++) {
            $data[$i] = $this->generateContentTable($i, $nb_column, $handle, $this->separator);
        }

        $this->context->cookie->entity_selected = (int)Tools::getValue('entity');
        $this->context->cookie->iso_lang_selected = urlencode(Tools::getValue('iso_lang'));
        $this->context->cookie->separator_selected = urlencode($this->separator);
        $this->context->cookie->multiple_value_separator_selected = urlencode($this->multiple_value_separator);
        $this->context->cookie->csv_selected = urlencode(Tools::getValue('csv'));

        $this->tpl_view_vars = array(
            'import_matchs' => Db::getInstance()->executeS('SELECT * FROM '._DB_PREFIX_.'import_match'),
            'fields_value' => array(
                'csv' => Tools::getValue('csv'),
                'convert' => Tools::getValue('convert'),
                'entity' => (int)Tools::getValue('entity'),
                'iso_lang' => Tools::getValue('iso_lang'),
                'truncate' => Tools::getValue('truncate'),
                'forceIDs' => Tools::getValue('forceIDs'),
                'regenerate' => Tools::getValue('regenerate'),
                'match_ref' => Tools::getValue('match_ref'),
                'separator' => $this->separator,
                'multiple_value_separator' => $this->multiple_value_separator,
                'id_erp_order' => Tools::getValue('id_erp_order'),
            ),
            'nb_table' => $nb_table,
            'nb_column' => $nb_column,
            'res' => implode(',', $res),
            'max_columns' => MAX_COLUMNS_ERP,
            'no_pre_select' => array('price_tin', 'feature'),
            'available_fields' => $this->available_fields,
            'data' => $data
        );

        return parent::renderView();
    }

    public function initToolbar()
    {
        switch ($this->display) {
            case 'import':
                // Default cancel button - like old back link
                $back = Tools::safeOutput(Tools::getValue('back', ''));
                if (empty($back)) {
                    $back = self::$currentIndex.'&token='.$this->token;
                }

                $this->toolbar_btn['cancel'] = array(
                    'href' => $back,
                    'desc' => $this->l('Cancel')
                );
                // Default save button - action dynamically handled in javascript
                $this->toolbar_btn['save-import'] = array(
                    'href' => '#',
                    'desc' => $this->l('Import .CSV data')
                );
                break;
        }
    }

    protected function generateContentTable($current_table, $nb_column, $handle, $glue)
    {
        $html = '<table id="table'.$current_table.'" style="display: none;" class="table table-bordered"><thead><tr>';
        // Header
        for ($i = 0; $i < $nb_column; $i++) {
            if (MAX_COLUMNS_ERP * (int)$current_table <= $i && (int)$i < MAX_COLUMNS_ERP * ((int)$current_table + 1)) {
                $html .= '<th>
							<select id="type_value['.$i.']"
								name="type_value['.$i.']"
								class="type_value">
								'.$this->getTypeValuesOptions($i).'
							</select>
						</th>';
            }
        }
        $html .= '</tr></thead><tbody>';

        AdminErpImportController::setLocale();
        for ($current_line = 0; $current_line < 10 && $line = fgetcsv($handle, MAX_LINE_SIZE_ERP, $glue); $current_line++) {
            /* UTF-8 conversion */
            if (Tools::getValue('convert')) {
                $line = $this->utf8EncodeArray($line);
            }
            $html .= '<tr id="table_'.$current_table.'_line_'.$current_line.'">';
            foreach ($line as $nb_c => $column) {
                if ((MAX_COLUMNS_ERP * (int)$current_table <= $nb_c) && ((int)$nb_c < MAX_COLUMNS_ERP * ((int)$current_table + 1))) {
                    $html .= '<td>'.htmlentities(Tools::substr($column, 0, 200), ENT_QUOTES, 'UTF-8').'</td>';
                }
            }
            $html .= '</tr>';
        }
        $html .= '</tbody></table>';
        AdminErpImportController::rewindBomAware($handle);
        return $html;
    }

    public function init()
    {
        parent::init();
        if (Tools::isSubmit('submitImportFile')) {
            $this->display = 'import';
        }
    }

    public function initContent()
    {
        $this->initTabModuleList();
        // toolbar (save, cancel, new, ..)
        $this->initToolbar();
        
        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->initPageHeaderToolbar();
        }

        if ($this->display == 'import') {
            if (Tools::isSubmit('submitImportFile')) {
                if (!Tools::getValue('id_erp_order') && !Tools::getValue('entity')) {
                    $this->errors[] = $this->l('You must choose an Erp order supplier.');
                    $this->content .= $this->renderForm();
                } elseif (Tools::getValue('csv')) {
                    $this->content .= $this->renderView();
                }
            } else {
                $this->errors[] = $this->l('You must upload a file in order to proceed to the next step');
                $this->content .= $this->renderForm();
            }
        } else {
            $this->content .= $this->renderForm();
        }

        if (version_compare(_PS_VERSION_, '1.6', '>=')) {
            $this->context->smarty->assign(array(
                'content' => $this->content,
                'url_post' => self::$currentIndex.'&token='.$this->token,
                'show_page_header_toolbar' => $this->show_page_header_toolbar,
                'page_header_toolbar_title' => $this->page_header_toolbar_title,
                'page_header_toolbar_btn' => $this->page_header_toolbar_btn
            ));
        } else {
            $this->context->smarty->assign(array(
                'content' => $this->content,
                'url_post' => self::$currentIndex.'&token='.$this->token
            ));
        }
    }

    protected static function rewindBomAware($handle)
    {
        // A rewind wrapper that skips BOM signature wrongly
        if (!is_resource($handle)) {
            return false;
        }
        rewind($handle);
        if (($bom = fread($handle, 3)) != "\xEF\xBB\xBF") {
            rewind($handle);
        }
    }

    protected static function getBoolean($field)
    {
        return (boolean)$field;
    }

    protected static function getPrice($field)
    {
        $field = ((float)str_replace(',', '.', $field));
        $field = ((float)str_replace('%', '', $field));
        return $field;
    }

    protected static function split($field)
    {
        if (empty($field)) {
            return array();
        }

        $separator = Tools::getValue('multiple_value_separator');
        if (is_null($separator) || trim($separator) == '') {
            $separator = ',';
        }

        do {
            $uniqid_path = _PS_UPLOAD_DIR_.uniqid();
        } while (file_exists($uniqid_path));
        file_put_contents($uniqid_path, $field);
        $tab = '';
        if (!empty($uniqid_path)) {
            $fd = fopen($uniqid_path, 'r');
            $tab = fgetcsv($fd, MAX_LINE_SIZE_ERP, $separator);
            fclose($fd);
            if (file_exists($uniqid_path)) {
                @unlink($uniqid_path);
            }
        }

        if (empty($tab) || (!is_array($tab))) {
            return array();
        }
        return $tab;
    }

    protected function getTypeValuesOptions($nb_c)
    {
        $i = 0;
        $no_pre_select = array('price_tin', 'feature');

        $options = '';
        foreach ($this->available_fields as $k => $field) {
            $options .= '<option value="'.$k.'"';
            if ($k === 'price_tin') {
                ++$nb_c;
            }
            if ($i === ($nb_c + 1) && (!in_array($k, $no_pre_select))) {
                $options .= ' selected="selected"';
            }
            $options .= '>'.$field['label'].'</option>';
            ++$i;
        }
        return $options;
    }

    /*
    * Return fields to be display AS piece of advise
    *
    * @param $in_array boolean
    * @return string or return array
    */
    public function getAvailableFields($in_array = false)
    {
        $i = 0;
        $fields = array();
        $keys = array_keys($this->available_fields);
        array_shift($keys);
        foreach ($this->available_fields as $k => $field) {
            if ($k === 'no') {
                continue;
            }
            if ($k === 'price_tin') {
                $fields[$i - 1] = '<div>'.$this->available_fields[$keys[$i - 1]]['label'].' '.$this->l('or').' '.$field['label'].'</div>';
            } else {
                if (isset($field['help'])) {
                    $html = '&nbsp;<a href="#" class="help-tooltip" data-toggle="tooltip" title="'.$field['help'].'"><i class="icon-info-sign"></i></a>';
                } else {
                    $html = '';
                }
                $fields[] = '<div>'.$field['label'].$html.'</div>';
            }
            ++$i;
        }
        if ($in_array) {
            return $fields;
        } else {
            return implode("\n\r", $fields);
        }
    }

    protected function receiveTab()
    {
        $type_value = Tools::getValue('type_value') ? Tools::getValue('type_value') : array();
        foreach ($type_value as $nb => $type) {
            if ($type != 'no') {
                self::$column_mask[$type] = $nb;
            }
        }
    }

    public static function getMaskedRow($row)
    {
        $res = array();
        if (is_array(self::$column_mask)) {
            foreach (self::$column_mask as $type => $nb) {
                $res[$type] = isset($row[$nb]) ? $row[$nb] : null;
            }
        }

        return $res;
    }

    protected static function setDefaultValues(&$info)
    {
        foreach (self::$default_values as $k => $v) {
            if (!isset($info[$k]) || $info[$k] == '') {
                $info[$k] = $v;
            }
        }
    }

    protected static function setEntityDefaultValues(&$entity)
    {
        $members = get_object_vars($entity);
        foreach (self::$default_values as $k => $v) {
            if ((array_key_exists($k, $members) && $entity->$k === null) || !array_key_exists($k, $members)) {
                $entity->$k = $v;
            }
        }
    }

    protected static function fillInfo($infos, $key, &$entity)
    {
        $infos = trim($infos);
        if (isset(self::$validators[$key][1]) && self::$validators[$key][1] == 'createMultiLangField' && Tools::getValue('iso_lang')) {
            $id_lang = Language::getIdByIso(Tools::getValue('iso_lang'));
            $tmp = call_user_func(self::$validators[$key], $infos);
            foreach ($tmp as $id_lang_tmp => $value) {
                if (empty($entity->{$key}[$id_lang_tmp]) || $id_lang_tmp == $id_lang) {
                    $entity->{$key}[$id_lang_tmp] = $value;
                }
            }
        } elseif (!empty($infos) || $infos == '0') { // ($infos == '0') => if you want to disable a product by using "0" in active because empty('0') return true
                $entity->{$key} = isset(self::$validators[$key]) ? call_user_func(self::$validators[$key], $infos) : $infos;
        }

        return true;
    }

    /**
     * @param $array
     * @param $funcname
     * @param mixed $user_data
     * @return bool
     */
    public static function arrayWalk(&$array, $funcname, &$user_data = false)
    {
        if (!is_callable($funcname)) {
            return false;
        }

        foreach ($array as $k => $row) {
            if (!call_user_func_array($funcname, array($row, $k, &$user_data))) {
                return false;
            }
        }
        return true;
    }
    
    public function stockUpdateImport()
    {
        // opens CSV & sets locale
        $this->receiveTab();
        $handle = $this->openCsvFile();
        AdminErpImportController::setLocale();

        $products = array();
        $reset = true;
        // main loop, for each supply orders details to import
        for ($current_line = 0; $line = fgetcsv($handle, MAX_LINE_SIZE_ERP, $this->separator); ++$current_line) {
            // if convert requested
            if (Tools::getValue('convert')) {
                $line = $this->utf8EncodeArray($line);
            }
            $info = AdminErpImportController::getMaskedRow($line);

            // sets default values if needed
            AdminErpImportController::setDefaultValues($info);
            
            $simple_product = false;
            
            //We verify if Product exists with attribute
            $sql = 'SELECT
						pa.`id_product`,
						pa.`id_product_attribute`,
						IF(pa.reference = \'\', IF(p.reference = \'\' , \'\', p.reference), pa.reference) as reference,
						IF(pa.supplier_reference = \'\', IF(p.supplier_reference = \'\' , \'\', p.supplier_reference), pa.supplier_reference) as supplier_reference,
						IF(pa.ean13 = \'\', IF(p.ean13 = \'\', \'\', p.ean13), pa.ean13) as ean13,
						IF(pa.upc = \'\', IF(p.upc = \'\', \'\', p.upc), pa.upc) as upc,
						IF(ps.product_supplier_price_te = 0 OR ps.product_supplier_price_te = \'\' OR ps.product_supplier_price_te IS NULL, IF(pa.wholesale_price = 0 OR pa.wholesale_price = \'\' OR pa.wholesale_price IS NULL, IF(psh.wholesale_price = 0 OR psh.wholesale_price = \'\' OR psh.wholesale_price IS NULL, p.wholesale_price, psh.wholesale_price), pa.wholesale_price), ps.product_supplier_price_te) as unit_price_te
					FROM 
						`'._DB_PREFIX_.'product_attribute` pa
					LEFT JOIN
						`'._DB_PREFIX_.'product` p ON (p.`id_product` = pa.`id_product`)
					LEFT JOIN
						`'._DB_PREFIX_.'product_shop` psh ON (psh.`id_product` = p.`id_product` AND psh.`id_shop` = '.Context::getContext()->shop->id.')				
					LEFT JOIN
						`'._DB_PREFIX_.'product_supplier` ps ON (ps.`id_product` = pa.`id_product` AND ps.`id_product_attribute` = pa.`id_product_attribute`)
					WHERE
						pa.`reference` = \''.pSQL($info['product_reference']).'\'
						OR pa.`supplier_reference` = \''.pSQL($info['product_reference']).'\'
						OR pa.`ean13` = \''.pSQL($info['product_reference']).'\'
						OR pa.`upc` = \''.pSQL($info['product_reference']).'\'
						OR ps.`product_supplier_reference` = \''.pSQL($info['product_reference']).'\'';
            $product_exists = Db::getInstance()->executeS($sql);
            
            if ($product_exists && count($product_exists) > 1) {
                $this->errors[] = sprintf($this->l('Product reference (%s) found on multiple product (Please verify you haven\'t product with the same reference).'), $info['product_reference'], $current_line + 1);
            } elseif (!$product_exists) {
                $simple_product = true;
                // We verify simple product
                $sql = 'SELECT
							DISTINCT(p.`id_product`),
							p.`ean13`,
							p.`reference`,
							p.`supplier_reference`,
							p.`upc`,
							p.id_tax_rules_group,
							IF(ps.product_supplier_price_te = 0 OR ps.product_supplier_price_te = \'\' OR ps.product_supplier_price_te IS NULL,IF(psh.wholesale_price = 0 OR psh.wholesale_price = \'\' OR psh.wholesale_price IS NULL, p.wholesale_price, psh.wholesale_price), ps.product_supplier_price_te) as unit_price_te
						FROM 
							`'._DB_PREFIX_.'product`p
						LEFT JOIN
							`'._DB_PREFIX_.'product_shop` psh ON (psh.`id_product` = p.`id_product` AND psh.`id_shop` = '.Context::getContext()->shop->id.')
						LEFT JOIN
						`'._DB_PREFIX_.'product_supplier` ps ON (ps.`id_product` = p.`id_product`)
						WHERE 
							p.`reference` = \''.pSQL($info['product_reference']).'\'
							OR p.`supplier_reference` = \''.pSQL($info['product_reference']).'\'
							OR p.`ean13` = \''.pSQL($info['product_reference']).'\'
							OR p.`upc` = \''.pSQL($info['product_reference']).'\'
							OR ps.`product_supplier_reference` = \''.pSQL($info['product_reference']).'\'
						GROUP BY
							p.`id_product`';

                $product_exists = Db::getInstance()->executeS($sql);
                
                if ($product_exists && count($product_exists) > 1) {
                    $this->errors[] = sprintf($this->l('Product reference (%s) found on multiple product (Please verify you haven\'t product with the same reference) at line %d.'), $info['product_reference'], $current_line + 1);
                }
            }
            
            if (empty($this->errors)) {
                foreach ($product_exists as $product_info) {
                    $id_product = (int)$product_info['id_product'];
                    
                    if (!isset($product_info['id_product_attribute'])) {
                        $id_product_attribute = 0;
                    } else {
                        $id_product_attribute = (int)$product_info['id_product_attribute'];
                    }
                        
                    $quantity = (int)$info['quantity'];
                    
                    $query = new DbQuery();
                    $query->select('COUNT(*)');
                    $query->from('stock_available');
                    $query->where('id_product = '.(int) $id_product.' AND id_product_attribute = '.(int) $id_product_attribute.
                        StockAvailable::addSqlShopRestriction(null, Context::getContext()->shop->id));

                    if ((int) Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue($query)) {
                        $query = array(
                            'table' => 'stock_available',
                            'data' => array('quantity' => $quantity),
                            'where' => 'id_product = '.(int) $id_product.' AND id_product_attribute = '.(int) $id_product_attribute.
                            StockAvailable::addSqlShopRestriction(null, Context::getContext()->shop->id),
                        );
                        Db::getInstance()->update($query['table'], $query['data'], $query['where']);
                    } else {
                        $query = array(
                            'table' => 'stock_available',
                            'data' => array(
                                'quantity' => $quantity,
                                'depends_on_stock' => 0,
                                'out_of_stock' => 1,
                                'id_product' => (int) $id_product,
                                'id_product_attribute' => (int) $id_product_attribute,
                            ),
                        );
                        StockAvailable::addSqlShopParams($query['data'], Context::getContext()->shop->id);
                        Db::getInstance()->insert($query['table'], $query['data']);
                    }
                    
                    //We select sum of quantity attribute
                    $sum = Db::getInstance()->getValue('SELECT SUM(`quantity`) as sum FROM '._DB_PREFIX_.'stock_available WHERE id_product = \''.(int)$id_product.'\' AND `id_product_attribute` != 0');

                    $id_stock_available = (int)StockAvailable::getStockAvailableIdByProductId($id_product, 0, 1);
                    if ($id_stock_available) {
                        Db::getInstance()->Execute('UPDATE `'._DB_PREFIX_.'stock_available` SET `quantity` = '.(int)$sum.' WHERE `id_stock_available` = '.(int)$id_stock_available.';');
                    } else {
                        Db::getInstance()->Execute('INSERT INTO `'._DB_PREFIX_.'stock_available` (`id_stock_available`, `id_product`, `id_product_attribute`, `id_shop`, `id_shop_group`, `quantity`, `depends_on_stock`, `out_of_stock`) VALUES (NULL, '.(int)$id_product.', 0, '.Context::getContext()->shop->id.', 0, '.(int)$sum.', 0, 1)');
                    }
                }
            }
        }
        
        // closes
        $this->closeCsvFile($handle);
    }
    
    public function supplyOrdersDetailsImport()
    {
        // opens CSV & sets locale
        $this->receiveTab();
        $handle = $this->openCsvFile();
        AdminErpImportController::setLocale();

        $products = array();
        $reset = true;
        // main loop, for each supply orders details to import
        for ($current_line = 0; $line = fgetcsv($handle, MAX_LINE_SIZE_ERP, $this->separator); ++$current_line) {
            // if convert requested
            if (Tools::getValue('convert')) {
                $line = $this->utf8EncodeArray($line);
            }
            $info = AdminErpImportController::getMaskedRow($line);

            // sets default values if needed
            AdminErpImportController::setDefaultValues($info);
                
            $simple_product = false;
            
            //We verify if Product exists with attribute
            $sql = 'SELECT
						pa.`id_product`,
						pa.`id_product_attribute`,
						IF(pa.reference = \'\', IF(p.reference = \'\' , \'\', p.reference), pa.reference) as reference,
						IF(pa.supplier_reference = \'\', IF(p.supplier_reference = \'\' , \'\', p.supplier_reference), pa.supplier_reference) as supplier_reference,
						IF(pa.ean13 = \'\', IF(p.ean13 = \'\', \'\', p.ean13), pa.ean13) as ean13,
						IF(pa.upc = \'\', IF(p.upc = \'\', \'\', p.upc), pa.upc) as upc,
						IF(ps.product_supplier_price_te = 0 OR ps.product_supplier_price_te = \'\' OR ps.product_supplier_price_te IS NULL, IF(pa.wholesale_price = 0 OR pa.wholesale_price = \'\' OR pa.wholesale_price IS NULL, IF(psh.wholesale_price = 0 OR psh.wholesale_price = \'\' OR psh.wholesale_price IS NULL, p.wholesale_price, psh.wholesale_price), pa.wholesale_price), ps.product_supplier_price_te) as unit_price_te
					FROM 
						`'._DB_PREFIX_.'product_attribute` pa
					LEFT JOIN
						`'._DB_PREFIX_.'product` p ON (p.`id_product` = pa.`id_product`)
					LEFT JOIN
						`'._DB_PREFIX_.'product_shop` psh ON (psh.`id_product` = p.`id_product` AND psh.`id_shop` = '.Context::getContext()->shop->id.')				
					LEFT JOIN
						`'._DB_PREFIX_.'product_supplier` ps ON (ps.`id_product` = pa.`id_product` AND ps.`id_product_attribute` = pa.`id_product_attribute`)
					WHERE
						pa.`reference` = \''.pSQL($info['product_reference']).'\'
						OR pa.`supplier_reference` = \''.pSQL($info['product_reference']).'\'
						OR pa.`ean13` = \''.pSQL($info['product_reference']).'\'
						OR pa.`upc` = \''.pSQL($info['product_reference']).'\'
						OR ps.`product_supplier_reference` = \''.pSQL($info['product_reference']).'\'';
            $product_exists = Db::getInstance()->executeS($sql);
            
            if ($product_exists && count($product_exists) > 1) {
                $this->errors[] = sprintf($this->l('Product reference (%s) found on multiple product (Please verify you haven\'t product with the same reference).'), $info['product_reference'], $current_line + 1);
            } elseif (!$product_exists) {
                $simple_product = true;
                // We verify simple product
                $sql = 'SELECT
							DISTINCT(p.`id_product`),
							p.`ean13`,
							p.`reference`,
							p.`supplier_reference`,
							p.`upc`,
							p.id_tax_rules_group,
							IF(ps.product_supplier_price_te = 0 OR ps.product_supplier_price_te = \'\' OR ps.product_supplier_price_te IS NULL,IF(psh.wholesale_price = 0 OR psh.wholesale_price = \'\' OR psh.wholesale_price IS NULL, p.wholesale_price, psh.wholesale_price), ps.product_supplier_price_te) as unit_price_te
						FROM 
							`'._DB_PREFIX_.'product`p
						LEFT JOIN
							`'._DB_PREFIX_.'product_shop` psh ON (psh.`id_product` = p.`id_product` AND psh.`id_shop` = '.Context::getContext()->shop->id.')
						LEFT JOIN
						`'._DB_PREFIX_.'product_supplier` ps ON (ps.`id_product` = p.`id_product`)
						WHERE 
							p.`reference` = \''.pSQL($info['product_reference']).'\'
							OR p.`supplier_reference` = \''.pSQL($info['product_reference']).'\'
							OR p.`ean13` = \''.pSQL($info['product_reference']).'\'
							OR p.`upc` = \''.pSQL($info['product_reference']).'\'
							OR ps.`product_supplier_reference` = \''.pSQL($info['product_reference']).'\'
						GROUP BY
							p.`id_product`';

                $product_exists = Db::getInstance()->executeS($sql);
                
                //die(count($product_exists));
                if ($product_exists && count($product_exists) > 1) {
                    $this->errors[] = sprintf($this->l('Product reference (%s) found on multiple product (Please verify you haven\'t product with the same reference) at line %d.'), $info['product_reference'], $current_line + 1);
                }
            }
            
            // gets the supply order
            if (array_key_exists('product_reference', $info) && pSQL($info['product_reference']) && ErpOrder::exists((int)Tools::getValue('id_erp_order'))  && count($product_exists)) {
                $erp_order = new ErpOrder((int)Tools::getValue('id_erp_order'));
            } else {
                $this->errors[] = sprintf($this->l('Product reference (%s) could not be loaded (at line %d).'), $info['product_reference'], $current_line + 1);
            }

            if (!count($this->errors)) {
                foreach ($product_exists as $product_info) {
                    $id_product = (int)$product_info['id_product'];
                    
                    if (!isset($product_info['id_product_attribute'])) {
                        $id_product_attribute = 0;
                    } else {
                        $id_product_attribute = (int)$product_info['id_product_attribute'];
                    }
                    
                    $info['unit_price_te'] = str_replace(',', '.', $info['unit_price_te']);
                    
                    if (!(float)$info['unit_price_te']) {
                        $unit_price_te = (float)$product_info['unit_price_te'];
                    } else {
                        $unit_price_te = (float)str_replace(',', '.', $info['unit_price_te']);
                    }
                    
                    $ean13 = ($product_info['ean13']) ? $product_info['ean13'] : '';
                    $upc = ($product_info['upc']) ? $product_info['upc'] : '';
                    $reference = $product_info['reference'];
                    $supplier_reference = $product_info['supplier_reference'];
                    $tax_rate = $this->getTaxRate($product_info['id_tax_rules_group']);
                            
                    $id_erp_products = ErpProducts::getProductById($id_product, $id_product_attribute);

                    if ($id_erp_products) {
                        $erp_products = new ErpProducts($id_erp_products);
                        if ($erp_products->bundling) {
                            $bundling = $erp_products->bundling;
                        } else {
                            $bundling = 1;
                        }
                    } else {
                        $bundling = 1;
                    }
                }
                
                $quantity_expected = (int)$info['quantity_expected'];

                // checks if one product/attribute is there only once
                if (isset($products[$id_product][$id_product_attribute])) {
                    $this->errors[] = sprintf(
                        $this->l('Product/Attribute (%d/%d) cannot be added twice (at line %d).'),
                        $id_product,
                        $id_product_attribute,
                        $current_line + 1
                    );
                } else {
                    $products[$id_product][$id_product_attribute] = $quantity_expected;
                }

                // checks parameters
                if (isset($id_product) && isset($id_product_attribute) && false === ($supplier_reference = ProductSupplier::getProductSupplierReference($id_product, $id_product_attribute, $erp_order->id_supplier))) {
                    $this->errors[] = sprintf(
                        $this->l('Product (%d/%d) is not available for this order (at line %d).'),
                        $id_product,
                        $id_product_attribute, 
                        $current_line + 1
                    );
                }
                if (isset($unit_price_te) && $unit_price_te < 0) {
                    $this->errors[] = sprintf(
                        $this->l('Unit Price (tax excl.) (%d) is not valid (at line %d).'),
                        $unit_price_te,
                        $current_line + 1
                    );
                }
                if (isset($quantity_expected) && $quantity_expected < 0) {
                    $this->errors[] = sprintf(
                        $this->l('Quantity Expected (%d) is not valid (at line %d).'),
                        $quantity_expected,
                        $current_line + 1
                    );
                }
                
                // if no errors, sets supply order details
                if (empty($this->errors)) {
                    // resets order if needed
                    if ($reset) {
                        $erp_order->resetProducts();
                        $reset = false;
                    }
                    
                    if ($simple_product) {
                        $product_obj = new Product((int)$id_product);
                        
                        if (Validate::isLoadedObject($product_obj)) {
                            if ($product_obj->hasAttributes()) {
                                $sql = 'SELECT
                                            pa.`id_product`,
                                            pa.`id_product_attribute`,
                                            p.id_tax_rules_group,
                                            IF(pa.reference = \'\', IF(p.reference = \'\' , \'\', p.reference), pa.reference) as reference,
                                            IF(pa.supplier_reference = \'\', IF(p.supplier_reference = \'\' , \'\', p.supplier_reference), pa.supplier_reference) as supplier_reference,
                                            IF(pa.ean13 = \'\', IF(p.ean13 = \'\', \'\', p.ean13), pa.ean13) as ean13,
                                            IF(pa.upc = \'\', IF(p.upc = \'\', \'\', p.upc), pa.upc) as upc,
                                            IF(ps.product_supplier_price_te = 0 OR ps.product_supplier_price_te = \'\' OR ps.product_supplier_price_te IS NULL, IF(pa.wholesale_price = 0 OR pa.wholesale_price = \'\' OR pa.wholesale_price IS NULL, IF(psh.wholesale_price = 0 OR psh.wholesale_price = \'\' OR psh.wholesale_price IS NULL, p.wholesale_price, psh.wholesale_price), pa.wholesale_price), ps.product_supplier_price_te) as unit_price_te
                                        FROM 
                                            `'._DB_PREFIX_.'product_attribute` pa
                                        LEFT JOIN
                                            `'._DB_PREFIX_.'product` p ON (p.`id_product` = pa.`id_product`)
                                        LEFT JOIN
                                            `'._DB_PREFIX_.'product_shop` psh ON (psh.`id_product` = p.`id_product` AND psh.`id_shop` = '.(int)Context::getContext()->shop->id.')
                                        LEFT JOIN
                                            `'._DB_PREFIX_.'product_supplier` ps ON (ps.`id_product` = pa.`id_product` AND ps.`id_product_attribute` = pa.`id_product_attribute`)
                                        WHERE
                                            pa.`id_product` = '.(int)$product_obj->id;
                                
                                //we retrieve all attributes
                                $attributes = Db::getInstance()->ExecuteS($sql);

                                if ($attributes) {
                                    foreach ($attributes as $attribute) {
                                        $id_product_attribute = (int)$attribute['id_product_attribute'];
                                        
                                        $id_erp_products = ErpProducts::getProductById((int)$attribute['id_product'], $id_product_attribute);

                                        if ($id_erp_products) {
                                            $erp_products = new ErpProducts($id_erp_products);
                                            if ($erp_products->bundling) {
                                                $bundling = $erp_products->bundling;
                                            } else {
                                                $bundling = 1;
                                            }
                                        } else {
                                            $bundling = 1;
                                        }
                                        
                                        $info['unit_price_te'] = str_replace(',', '.', $info['unit_price_te']);
                                        
                                        if (!(float)$info['unit_price_te']) {
                                            $unit_price_te = (float)$attribute['unit_price_te'];
                                        } else {
                                            $unit_price_te = (float)str_replace(',', '.', $info['unit_price_te']);
                                        }
                                            
                                        $ean13 = ($attribute['ean13']) ? $attribute['ean13'] : '';
                                        $upc = ($attribute['upc']) ? $attribute['upc'] : '';
                                        $reference = $attribute['reference'];
                                        $supplier_reference = $attribute['supplier_reference'];
                                        // creates new product
                                        $erp_order_detail = new ErpOrderDetail();
                                        AdminErpImportController::arrayWalk($info, array('AdminErpImportController', 'fillInfo'), $erp_order_detail);

                                        // sets parameters
                                        if ($tax_rate = $this->getTaxRate($attribute['id_tax_rules_group'])) {
                                            $erp_order_detail->tax_rate = $tax_rate;
                                        }
                                        
                                        $erp_order_detail->id_erp_order = (int)$erp_order->id;
                                        $currency = new Currency($erp_order->id_currency);
                                        $erp_order_detail->id_currency = (int)$currency->id;
                                        $erp_order_detail->id_product = (int)$id_product;
                                        $erp_order_detail->id_product_attribute = (int)$id_product_attribute;
                                        $erp_order_detail->exchange_rate = $currency->conversion_rate;
                                        $erp_order_detail->supplier_reference = $supplier_reference;
                                        $erp_order_detail->name = Product::getProductName($id_product, $id_product_attribute, Context::getContext()->language->id);
                                        $erp_order_detail->unit_price_te = (float)$unit_price_te;
                                        $erp_order_detail->reference = $reference;
                                        $erp_order_detail->ean13 = $ean13;
                                        $erp_order_detail->upc = $upc;
                                        $erp_order_detail->quantity_ordered = $quantity_expected * $bundling;
                                        $erp_order_detail->add();
                                        $erp_order->update();
                                    }
                                }
                            } else {
                                // creates new product
                                $erp_order_detail = new ErpOrderDetail();
                                AdminErpImportController::arrayWalk($info, array('AdminErpImportController', 'fillInfo'), $erp_order_detail);

                                // sets parameters
                                $erp_order_detail->tax_rate = $tax_rate;
                        
                                $erp_order_detail->id_erp_order = (int)$erp_order->id;
                                $currency = new Currency($erp_order->id_currency);
                                $erp_order_detail->id_currency = (int)$currency->id;
                                $erp_order_detail->id_product = (int)$id_product;
                                $erp_order_detail->id_product_attribute = (int)$id_product_attribute;
                                $erp_order_detail->exchange_rate = $currency->conversion_rate;
                                $erp_order_detail->supplier_reference = $supplier_reference;
                                $erp_order_detail->name = Product::getProductName($id_product, $id_product_attribute, Context::getContext()->language->id);
                                $erp_order_detail->unit_price_te = (float)$unit_price_te;
                                $erp_order_detail->reference = $reference;
                                $erp_order_detail->ean13 = $ean13;
                                $erp_order_detail->upc = $upc;
                                $erp_order_detail->quantity_ordered = $quantity_expected * $bundling;
                                $erp_order_detail->add();
                                $erp_order->update();
                            }
                        }
                    } else {
                        // creates new product
                        $erp_order_detail = new ErpOrderDetail();
                        AdminErpImportController::arrayWalk($info, array('AdminErpImportController', 'fillInfo'), $erp_order_detail);

                        // sets parameters
                        $erp_order_detail->tax_rate = $tax_rate;
                        
                        $erp_order_detail->id_erp_order = (int)$erp_order->id;
                        $currency = new Currency($erp_order->id_currency);
                        $erp_order_detail->id_currency = (int)$currency->id;
                        $erp_order_detail->id_product = (int)$id_product;
                        $erp_order_detail->id_product_attribute = (int)$id_product_attribute;
                        $erp_order_detail->exchange_rate = $currency->conversion_rate;
                        $erp_order_detail->supplier_reference = $supplier_reference;
                        $erp_order_detail->name = Product::getProductName($id_product, $id_product_attribute, Context::getContext()->language->id);
                        $erp_order_detail->unit_price_te = (float)$unit_price_te;
                        $erp_order_detail->reference = $reference;
                        $erp_order_detail->ean13 = $ean13;
                        $erp_order_detail->upc = $upc;
                        $erp_order_detail->quantity_ordered = $quantity_expected * $bundling;
                        $erp_order_detail->add();
                        $erp_order->update();
                    }
                    unset($erp_order_detail, $reference, $supplier_reference, $ean13, $upc, $product_exists, $bundling);
                }
            } else {
                
            }
        }

        // closes
        $this->closeCsvFile($handle);
        
        if (empty($this->errors)) {
            $action = '&id_erp_order='.(int)$erp_order->id.'&updateerp_order';
            Tools::redirectAdmin($this->context->link->getAdminLink('AdminErpSupplierOrders').$action);
        }
    }

    public function utf8EncodeArray($array)
    {
        return (is_array($array) ? array_map('utf8_encode', $array) : utf8_encode($array));
    }

    protected function getNbrColumn($handle, $glue)
    {
        if (!is_resource($handle)) {
            return false;
        }
        $tmp = fgetcsv($handle, MAX_LINE_SIZE_ERP, $glue);
        AdminErpImportController::rewindBomAware($handle);
        return count($tmp);
    }

    protected static function usortFiles($a, $b)
    {
        if ($a == $b) {
            return 0;
        }
        return ($b < $a) ? 1 : - 1;
    }

    protected function openCsvFile()
    {
        $file = AdminErpImportController::getPath(preg_replace('/\.{2,}/', '.', Tools::getValue('csv')));
        $handle = false;
        if (is_file($file) && is_readable($file)) {
            $handle = fopen($file, 'r');
        }

        if (!$handle) {
            $this->errors[] = Tools::displayError('Cannot read the .CSV file');
        }

        AdminErpImportController::rewindBomAware($handle);

        for ($i = 0; $i < (int)Tools::getValue('skip'); ++$i) {
            $line = fgetcsv($handle, MAX_LINE_SIZE_ERP, $this->separator);
        }
        return $handle;
    }

    protected function closeCsvFile($handle)
    {
        fclose($handle);
    }

    public function clearSmartyCache()
    {
        Tools::enableCache();
        Tools::clearCache($this->context->smarty);
        Tools::restoreCacheSettings();
    }

    public function postProcess()
    {
        if (Tools::isSubmit('import')) {
            // Check if the CSV file exist
            if (Tools::getValue('csv')) {
                switch ((int)Tools::getValue('entity')) {
                    case $this->entities[$import_type = $this->l('Supply Order Details')]:
                        $this->supplyOrdersDetailsImport();
                        break;
                    
                    case $this->entities[$import_type = $this->l('Update stock')]:
                        $this->stockUpdateImport();
                        break;
                }

                if ($import_type !== false) {
                    $log_message = sprintf($this->l('%s import', 'AdminTab', false, false), $import_type);
                    if (Tools::getValue('truncate')) {
                        $log_message .= ' '.$this->l('with truncate', 'AdminTab', false, false);
                    }
                    //PrestaShopLogger::addLog($log_message, 1, null, $import_type, null, true, (int)$this->context->employee->id);
                }
            } else {
                $this->errors[] = $this->l('You must upload a file in order to proceed to the next step');
            }
        } elseif ($filename = Tools::getValue('csvfilename')) {
            $filename = urldecode($filename);
            $file = AdminErpImportController::getPath(basename($filename));
            if (realpath(dirname($file)) != realpath(AdminErpImportController::getPath())) {
                exit();
            }
            if (!empty($filename)) {
                $b_name = basename($filename);
                if (Tools::getValue('delete') && file_exists($file)) {
                    @unlink($file);
                } elseif (file_exists($file)) {
                    $b_name = explode('.', $b_name);
                    $b_name = Tools::strtolower($b_name[count($b_name) - 1]);
                    $mime_types = array('csv' => 'text/csv');

                    if (isset($mime_types[$b_name])) {
                        $mime_type = $mime_types[$b_name];
                    } else {
                        $mime_type = 'application/octet-stream';
                    }

                    if (ob_get_level() && ob_get_length() > 0) {
                        ob_end_clean();
                    }

                    header('Content-Transfer-Encoding: binary');
                    header('Content-Type: '.$mime_type);
                    header('Content-Length: '.filesize($file));
                    header('Content-Disposition: attachment; filename="'.$filename.'"');
                    $fp = fopen($file, 'rb');
                    while (is_resource($fp) && !feof($fp)) {
                        echo fgets($fp, 16384);
                    }
                    exit;
                }
            }
        }
        return parent::postProcess();
    }

    public static function setLocale()
    {
        $iso_lang  = trim(Tools::getValue('iso_lang'));
        setlocale(LC_COLLATE, Tools::strtolower($iso_lang).'_'.Tools::strtoupper($iso_lang).'.UTF-8');
        setlocale(LC_CTYPE, Tools::strtolower($iso_lang).'_'.Tools::strtoupper($iso_lang).'.UTF-8');
    }

    protected function addProductWarning($product_name, $product_id = null, $message = '')
    {
        $this->warnings[] = $product_name.(isset($product_id) ? ' (ID '.$product_id.')' : '').' '
            .Tools::displayError($message);
    }

    public function ajaxProcessSaveImportMatchs()
    {
        if ($this->tabAccess['edit'] === '1') {
            $match = implode('|', Tools::getValue('type_value'));
            Db::getInstance()->execute('INSERT IGNORE INTO  `'._DB_PREFIX_.'import_match` (
										`id_import_match` ,
										`name` ,
										`match`,
										`skip`
										)
										VALUES (
										NULL ,
										\''.pSQL(Tools::getValue('newImportMatchs')).'\',
										\''.pSQL($match).'\',
										\''.pSQL(Tools::getValue('skip')).'\'
										)');

            die('{"id" : "'.Db::getInstance()->Insert_ID().'"}');
        }
    }

    public function ajaxProcessLoadImportMatchs()
    {
        if ($this->tabAccess['edit'] === '1') {
            $return = Db::getInstance()->executeS('SELECT * FROM `'._DB_PREFIX_.'import_match` WHERE `id_import_match` = '.(int)Tools::getValue('idImportMatchs'));
            die('{"id" : "'.$return[0]['id_import_match'].'", "matchs" : "'.$return[0]['match'].'", "skip" : "'.$return[0]['skip'].'"}');
        }
    }
    
    public function ajaxProcessDisplayEntity()
    {
        $jsonArray = array();

        $fields = $this->getAvailableFields(true);
        foreach ($fields as $field) {
            $jsonArray[] = '{"field":"'.addslashes($field).'"}';
        }
        die('['.implode(',', $jsonArray).']');
    }

    public function ajaxProcessDeleteImportMatchs()
    {
        if ($this->tabAccess['edit'] === '1') {
            Db::getInstance()->execute('DELETE FROM `'._DB_PREFIX_.'import_match` WHERE `id_import_match` = '.(int)Tools::getValue('idImportMatchs'));
            die;
        }
    }
    
    public function getTaxRate($id_tax_rules_group)
    {
        $id_tax = Db::getInstance(_PS_USE_SQL_SLAVE_)->getValue('SELECT DISTINCT(`id_tax`) FROM `'._DB_PREFIX_.'tax_rule` WHERE `id_tax_rules_group` = '.(int)$id_tax_rules_group);

        if ($id_tax) {
            $tax = new Tax($id_tax);
            if (Validate::isLoadedObject($tax)) {
                return $tax->rate;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }
    
    public static function getPath($file = '')
    {
        if ($file) {
            return _PS_MODULE_DIR_.'wic_erp/import/'.$file;
        }
        
        return _PS_MODULE_DIR_.'wic_erp/import/';
    }
    
    protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
    	if (version_compare(_PS_VERSION_, '1.7', '<')) {
            return parent::l($string, $class, $addslashes, $htmlentities);
    	} else {
            return Translate::getModuleTranslation('wic_erp', $string, 'AdminErpImportController');
    	}
    }
}
