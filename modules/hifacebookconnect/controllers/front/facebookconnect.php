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

class HiFacebookconnectFacebookconnectModuleFrontController extends ModuleFrontController
{
    public function setMedia()
    {
        parent::setMedia();
        if ($this->module->psv < 1.6) {
            $this->addCSS(_MODULE_DIR_.'/hifacebookconnect/views/css/facebook-connect-controller1.5.css', 'all');
        }
        $this->addJS(_MODULE_DIR_.'/hifacebookconnect/views/js/facebook-connect-controller.js');
    }

    public function init()
    {
        parent::init();
        if (Tools::getValue('action') == 'get_facebook_info') {
            $link = new Link();
            $first_name = preg_replace('/\PL/u', '', Tools::getValue('user_fname'));
            $last_name = preg_replace('/\PL/u', '', Tools::getValue('user_lname'));
            $email = Tools::getValue('email');
            $gender = Tools::getValue('gender');
            $activate_url = $link->getModuleLink('hifacebookconnect', 'facebookconnect').(Configuration::get('PS_REWRITING_SETTINGS') ? '?' : '&' ).'content_only=1&email='.$email.'&user_data_id='.Tools::getValue('user_data_id').'&user_fname='.$first_name.'&user_lname='.$last_name.'&name_status=full&gender='.$gender;

            if ($first_name == '' || $last_name == '' || $email == '') {
                die(Tools::jsonEncode(array('activate_die_url' => $activate_url.'&full_info=false&popup=0')));
            } else {
                Tools::redirect($activate_url.'&full_info=true&popup=0');
            }
        } else {
            $customer = new Customer();
            $link = new Link();
            $base_dir = Tools::getHttpHost(true).__PS_BASE_URI__;
            $authentication_page = $link->getPageLink('my-account', true);
            $id_user = Tools::getValue('user_data_id');
            $first_name = Tools::getValue('user_fname');
            $last_name = Tools::getValue('user_lname');
            $mail = Tools::getValue('email');
            $gender = Tools::getValue('gender');
            $full_info = Tools::getValue('full_info');
            $popup = Tools::getValue('popup');
            $pass_erro = Tools::getValue('pass_erro');
            if ($mail != '' && $first_name != '' && $last_name != '' && $full_info) {
                $get_email = $customer->getByEmail($mail);
                if (!empty($get_email)) {
                    $this->customerLogin($customer);
                } else {
                    $result = Db::getInstance()->ExecuteS('
                        SELECT * FROM '._DB_PREFIX_.'hifacebookusers
                        WHERE id_user ="'.pSQL($id_user).'"'.Shop::addSqlRestriction(Shop::SHARE_CUSTOMER));
                    if (empty($result)) {
                        $this->addSCUsers($mail, $first_name, $last_name, $id_user, $gender);
                    }
                    $this->customerLoginAndRegister($customer, $first_name, $last_name, $mail);
                }
                if (!$popup) {
                    die(Tools::jsonEncode(array('error' => '', 'have_email' => false, 'activate_die_url' => '')));
                } else {
                    echo('
                    <script type="text/javascript">
                        var redirect = "'.$this->module->hi_sc_fb_login_page.'";
                        window.onunload = refreshParent();
                        function refreshParent() {
                            if(redirect == "no_redirect") {
                                window.opener.location.reload();
                            } else {
                                window.opener.location.href = "'.$authentication_page.'";
                            }
                        }
                        setTimeout(function(){
                            window.opener.loaderOpening();
                            self.close();
                        }, 500)
                    </script>');
                }
            } else {
                $email_result = Db::getInstance()->ExecuteS('
                    SELECT * FROM '._DB_PREFIX_.'hifacebookusers
                    WHERE id_user ="'.pSQL($id_user).'"'.Shop::addSqlRestriction(Shop::SHARE_CUSTOMER));
                if (empty($email_result)) {
                    if ($this->ajax) {
                        $error = array();
                        if ($mail == '' || !Validate::isEmail($mail)) {
                            $error[] = 'Email is invalid.';
                        }
                        if ($first_name == '' || !Validate::isName($first_name)) {
                            $error[] = 'First name is invalid.';
                        }
                        if ($last_name == '' || !Validate::isName($last_name)) {
                            $error[] = 'Last name is invalid.';
                        }
                        if (!empty($error)) {
                            die(Tools::jsonEncode(array('error' => $error, 'have_email' => false)));
                        } else {
                            $get_email = $customer->getByEmail($mail);
                            $password = Tools::encrypt(Tools::getValue('password'));
                            if (!empty($get_email) && $get_email->passwd != $password) {
                                if ($pass_erro == '0') {
                                    $error[] = $this->module->l('An account using this email address has already been registered.', 'facebookconnect');
                                } else {
                                    $error[] = $this->module->l('Password is invalid.', 'facebookconnect');
                                }
                                die(Tools::jsonEncode(array('error' => $error, 'have_email' => true)));
                            } elseif (!empty($get_email) && $get_email->passwd == $password) {
                                $this->customerLogin($customer);
                            } else {
                                $this->customerLoginAndRegister($customer, $first_name, $last_name, $mail);
                            }
                            $this->addSCUsers($mail, $first_name, $last_name, $id_user, $gender);
                            die(Tools::jsonEncode(array('error' => $error, 'have_email' => false, 'popup' => (bool)$popup)));
                        }
                    }
                } else {
                    $get_email = $customer->getByEmail($email_result[0]['email']);
                    if (!empty($get_email)) {
                        $this->customerLogin($customer);
                    } else {
                        $result = Db::getInstance()->ExecuteS('
                            SELECT * FROM '._DB_PREFIX_.'hifacebookusers
                            WHERE id_user ="'.pSQL($id_user).'"'.Shop::addSqlRestriction(Shop::SHARE_CUSTOMER));
                        if (!empty($result)) {
                            $this->customerLoginAndRegister($customer, $result[0]['first_name'], $result[0]['last_name'], $result[0]['email']);
                        }
                    }
                    echo('
                    <script type="text/javascript">
                        var redirect = "'.$this->module->hi_sc_fb_login_page.'";
                        var popup = '.$popup.';
                        window.onunload = refreshParent();
                        function refreshParent() {
                            if(redirect == "no_redirect") {
                                if (popup) {
                                    window.opener.location.reload();
                                } else {
                                    window.location.href = "'.$base_dir.'";
                                }
                            } else {
                                if (popup) {
                                    window.opener.location.href = "'.$authentication_page.'";
                                } else {
                                    window.location.href = "'.$authentication_page.'";
                                }
                            }
                        }
                        if (popup) {
                            setTimeout(function(){
                                window.opener.loaderOpening();
                                self.close();
                            }, 500)
                        }
                    </script>');
                }
            }
        }
    }

    public function addSCUsers($mail, $first_name, $last_name, $id_user, $gender)
    {
        include_once(dirname(__FILE__).'/../../classes/HIFacbookUsers.php');
        $sc_users = new HIFacebookUsers();
        $sc_users->first_name = $first_name;
        $sc_users->last_name = $last_name;
        $sc_users->id_user = $id_user;
        $sc_users->email = $mail;
        $sc_users->gender = $gender;
        $sc_users->active = 0;
        $sc_users->id_shop_group = Shop::getContextShopGroupID();
        $sc_users->id_shop = Shop::getContextShopID();
        $sc_users->add();
    }

    public function customerLoginAndRegister($customer, $fname, $lname, $email)
    {
        Hook::exec('actionBeforeSubmitAccount');
        $customer->firstname = $fname;
        $customer->lastname = $lname;
        $customer->email = $email;
        $password = Tools::passwdGen();
        $customer->passwd = md5(pSQL(_COOKIE_KEY_.$password));
        $customer->is_guest = 0;
        $customer->active = 1;
        $customer->add();
        $this->module->sendConfirmationMail($customer, $password);
        /*Customer login*/
        $context = Context::getContext();
        $context->customer = $customer;
        $context->cookie->id_customer = (int)$customer->id;
        $context->cookie->customer_lastname = $customer->lastname;
        $context->cookie->customer_firstname = $customer->firstname;
        $context->cookie->passwd = $customer->passwd;
        $context->cookie->logged = 1;
        $customer->logged = 1;
        $context->cookie->email = $customer->email;
        $context->cookie->is_guest = $customer->is_guest;
        $context->cart->secure_key = $customer->secure_key;
        $context->cookie->update();
        $context->cart->update();
    }

    public function customerLogin($customer)
    {
        /*Login when customer isset in customers table*/
        $context = Context::getContext();
        Hook::exec('actionBeforeAuthentication');
        
        if ($this->module->psv <= 1.6) {
            if (isset($context->cookie->id_compare)) {
                $id_compare = $context->cookie->id_compare;
            } else {
                $id_compare = CompareProduct::getIdCompareByIdCustomer($customer->id);
            }
            $context->cookie->id_compare = $id_compare;
        }
        $context->cookie->id_customer = (int)($customer->id);
        $context->cookie->customer_lastname = $customer->lastname;
        $context->cookie->customer_firstname = $customer->firstname;
        $context->cookie->logged = 1;
        $customer->logged = 1;
        $context->cookie->is_guest = $customer->isGuest();
        $context->cookie->passwd = $customer->passwd;
        $context->cookie->email = $customer->email;
        /* Add customer to the context */
        $context->customer = $customer;
        if (Configuration::get('PS_CART_FOLLOWING')
            && (empty($context->cookie->id_cart)
            || Cart::getNbProducts($context->cookie->id_cart) == 0)
            && $id_cart = (int)Cart::lastNoneOrderedCart($context->customer->id)) {
            $context->cart = new Cart($id_cart);
        } else {
            $context->cart->id_carrier = 0;
            $context->cart->setDeliveryOption(null);
            $context->cart->id_address_delivery = (int)Address::getFirstCustomerAddressId((int)($customer->id));
            $context->cart->id_address_invoice = (int)Address::getFirstCustomerAddressId((int)($customer->id));
        }
        $context->cart->id_customer = (int)$customer->id;
        $context->cart->secure_key = $customer->secure_key;
        $context->cart->save();
        $context->cookie->id_cart = (int)$context->cart->id;
        $context->cookie->write();
        $context->cart->autosetProductAddress();
        Hook::exec('actionAuthentication');
        /* Login information have changed, so we check if the cart rules still apply */
        CartRule::autoRemoveFromCart($context);
        CartRule::autoAddToCart($context);
    }

    public function initContent()
    {
        parent::initContent();
        if (Tools::getValue('email') == '' || Tools::getValue('user_fname') == '' || Tools::getValue('user_lname') == '') {
            $this->context->smarty->assign(array(
                'email' => Tools::getValue('email'),
                'user_data_id' => Tools::getValue('user_data_id'),
                'user_fname' => Tools::getValue('user_fname'),
                'user_lname' => Tools::getValue('user_lname'),
                'gender' => Tools::getValue('gender'),
                'name_status' => Tools::getValue('name_status'),
                'popup' => Tools::getValue('popup'),
                'psv' => $this->module->psv,
                'action' => $this->context->link->getModuleLink('hifacebookconnect', 'facebookconnect'),
            ));
            if ($this->module->psv >= 1.7) {
                $this->setTemplate('module:hifacebookconnect/views/templates/front/facebookconnect1.7.tpl');
            } else {
                $this->setTemplate('facebookconnect.tpl');
            }
        }
    }
}
