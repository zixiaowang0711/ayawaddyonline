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

class HIFacebookUsers extends ObjectModel{
	public $id;
	public $id_user;
	public $id_shop_group;
	public $id_shop;
	public $first_name;
	public $last_name;
	public $email;
	public $gender;
	public $date_add;
	public $date_upd;

	public static $definition = array(
		'table' => 'hifacebookusers',
		'primary' => 'id',
		'fields' => array(
			'id_user' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 100, 'required' => true),
			'id_shop_group' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'size' => 11, 'required' => true),
			'id_shop' => array('type' => self::TYPE_INT, 'validate' => 'isInt', 'size' => 11, 'required' => true),
		    'first_name' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 100),
		    'last_name' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 100),
		    'email' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 100),
		    'gender' => array('type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'size' => 100),
		    'date_add' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
		    'date_upd' => array('type' => self::TYPE_DATE, 'validate' => 'isDate'),
	    ),
    );

	public function add($autodate = true, $null_values = false){
		if (!parent::add($autodate, $null_values))
			return false;
		return true;
	}
}
