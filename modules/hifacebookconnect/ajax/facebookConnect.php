<?php

	include(dirname(__FILE__).'/../../../config/config.inc.php');
	include(dirname(__FILE__).'/../../../init.php');
	include(dirname(__FILE__).'/../hifacebookconnect.php');
	include(dirname(__FILE__).'/../classes/HIFacbookUsers.php');
	
	$customer = new Customer();
	$customer->getByEmail(Tools::getValue('email'), null, true);
	if ($customer->id){
		$context = Context::getContext();
		Hook::exec('actionBeforeAuthentication');
		
		$context->cookie->id_compare = isset($context->cookie->id_compare) ? $context->cookie->id_compare: CompareProduct::getIdCompareByIdCustomer($customer->id);
		$context->cookie->id_customer = (int)($customer->id);
		$context->cookie->customer_lastname = $customer->lastname;
		$context->cookie->customer_firstname = $customer->firstname;
		$context->cookie->logged = 1;
		$customer->logged = 1;
		$context->cookie->is_guest = $customer->isGuest();
		$context->cookie->passwd = $customer->passwd;
		$context->cookie->email = $customer->email;
		$context->customer = $customer;
				
		if (Configuration::get('PS_CART_FOLLOWING') && (empty($context->cookie->id_cart) || Cart::getNbProducts($context->cookie->id_cart) == 0) && $id_cart = (int)Cart::lastNoneOrderedCart($context->customer->id))
			$context->cart = new Cart($id_cart);
		else{
			$id_carrier = (int)$context->cart->id_carrier;
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
		CartRule::autoRemoveFromCart($context);
		CartRule::autoAddToCart($context);

	}else{
		$db_user_id = strval(Tools::getValue('id'));
		$result = Db::getInstance()->ExecuteS('SELECT * FROM '._DB_PREFIX_.'hifacebookusers WHERE id_user ="'.$db_user_id.'"'.Shop::addSqlRestriction(Shop::SHARE_CUSTOMER) );
		if (empty($result)){
			$facebook_users = new HIFacebookUsers();
			$facebook_users->id_user = Tools::getValue('id');
			$facebook_users->id_shop_group = Shop::getContextShopGroupID();
			$facebook_users->id_shop = Shop::getContextShopID();
			$facebook_users->first_name = Tools::getValue('firstname');
			$facebook_users->last_name = Tools::getValue('lastname');
			$facebook_users->email = Tools::getValue('email');
			$facebook_users->gender = Tools::getValue('gender');
			$originalDate = Tools::getValue('birthday');
			$newDate =  date('Y-m-d', strtotime($originalDate));
			$facebook_users->birthday = $newDate;
			$facebook_users->add();
		
			Hook::exec('actionBeforeSubmitAccount');
		
			$customer->firstname = Tools::getValue('firstname');
			$customer->lastname = Tools::getValue('lastname');
			$customer->email = Tools::getValue('email');
			$password = Tools::passwdGen();
			$customer->passwd = md5(pSQL(_COOKIE_KEY_.$password));
			
			if (Tools::getValue('gender') == 'male')
				$id_gender = 1;
			elseif (Tools::getValue('gender') == 'female')
				$id_gender = 2;
			else
				$id_gender = null;
			
			$customer->id_gender = $id_gender;
			$customer->birthday = $newDate;
			$customer->is_guest = 0;
			$customer->active = 1;
			$customer->add();
			
			$send_email = new HIfacebookConnect();
			$send_email->sendConfirmationMail($customer, $password);
			
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
	}
