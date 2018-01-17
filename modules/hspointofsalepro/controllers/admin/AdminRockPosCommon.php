<?php
/**
 * RockPOS - Point of Sale for PrestaShop
 *
 * @author    Hamsa Technologies
 * @copyright Hamsa Technologies
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

/**
 * Controller of admin page - Point Of Sale
 */
class AdminRockPosCommon extends ModuleAdminController
{

    /**
     * An actual action which DOES something.
     *
     * @var string
     */
    protected $sub_action = null;

    /**
     * A group of actions which will do some related jobs.
     *
     * @var string
     */
    protected $action = null;

    /**
     * Show result to view.
     *
     * @var type json
     */
    protected $ajax_json = array(
        'success' => false,
        'message' => null,
        'data' => array(),
    );

    /**
     * @var array
     *            <pre>
     *            array(
     *            string => string,// uri => media_type. Fx: 'path/to/css/file' => 'all'
     *            string => string,
     *            ...
     *            )
     */
    protected $module_media_css = array();

    /**
     * @var array
     *            <pre>
     *            array(
     *            string,// path/to/js/file
     *            string
     *            ...
     *            )
     */
    protected $module_media_js = array(
        'rsvp-3.1.0.min.js',
        'sha-256.min.js',
        'qz-tray.js',
    );

    /**
     * @see parent::setMedia()
     */
    public function setMedia()
    {
        parent::setMedia();
        $css_files = array();
        $js_files = array();
        if (!empty($this->module_media_css) && is_array($this->module_media_css)) {
            foreach ($this->module_media_css as $css_file => $media_type) {
                $css_files[(Validate::isAbsoluteUrl($css_file) ? '' : $this->module->getCssPath()) . $css_file] = $media_type;
            }
            $this->addCSS($css_files);
        }
        // Js files
        if (!empty($this->module_media_js) && is_array($this->module_media_js)) {
            foreach ($this->module_media_js as $js_file) {
                $js_files[] = (Validate::isAbsoluteUrl($js_file) ? '' : $this->module->getJsPath()) . $js_file;
            }
            $this->addJS($js_files);
        }
    }

    public function init()
    {
        parent::init();

        // For all actions, by default, force to false, and set message to this one
        $this->ajax_json['message'] = $this->module->i18n['oops_something_goes_wrong'];

        $this->action = Tools::getValue('action');
        $this->sub_action = Tools::getValue('sub_action', null);
        if ($this->sub_action) {
            $this->sub_action = Tools::strtolower($this->sub_action);
        }
        $pos_installer = new PosInstaller($this->module);
        $pos_installer->installCarrier();
        // install payment for current shop if the current shop don't have any payment methods
        $payments_shop = PosPayment::getPosPayments(null, $this->context->shop->id);
        if (empty($payments_shop)) {
            // duplicate all the current pos payment to current shop
            PosPayment::syncPaymentsShop($this->context->shop->id);
        }
    }

    /**
     * initHeader() is not required in ajax requests
     * It could lead to errors, raised from third party modules.
     */
    public function initHeader()
    {
        if (!$this->ajax) {
            parent::initHeader();
        }
    }

    /**
     * @see parent::initContent
     */
    public function initContent()
    {
        $this->context->smarty->assign(array(
            'js_path' => $this->module->getJsPath(),
            'file_version' => $this->getFileVersion(),
        ));
        parent::initContent();
    }

    /**
     * @return string
     */
    protected function getFileVersion()
    {
        return defined('_PS_MODE_DEV_') && _PS_MODE_DEV_ ? time() : $this->module->version;
    }

    /**
     * Show content to view.
     */
    public function displayAjax()
    {
        $this->context->cookie->write(); // in PrestaShop, it's done in displayAjax() -> display() ->smartyOutputcontent()

        if ($this->ajax_json) {
            if (Tools::getValue('debug')) {
                p($this->ajax_json);
            } else {
                echo Tools::jsonEncode($this->ajax_json);
            }
        } elseif ($this->ajax_html) {
            echo $this->ajax_html;
        } elseif ($this->ajax_pdf) {
            $this->ajax_pdf->Output();
        } else {
            echo Tools::jsonEncode(array());
        }
    }
}
