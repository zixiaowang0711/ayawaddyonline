<?php
/**
 * Oct8ne Module
 *
 * @category  Prestashop
 * @category  Module
 * @author    Prestaquality.com
 * @copyright 2014 - 2015 Prestaquality
 * @license   Commercial license see license.txt
 * Support by mail  : info@prestaquality.com
 */

$sql = array();

$sql[] = 'CREATE TABLE IF NOT EXISTS `'._DB_PREFIX_.'oct8nehistory` (
`id_oct8nehistory` int(10) unsigned NOT NULL AUTO_INCREMENT,
`cart_id` int(10) unsigned NOT NULL,
`session_id` text NOT NULL,
PRIMARY KEY  (`id_oct8nehistory`),
INDEX `cart_id` (`cart_id`)
) ENGINE='._MYSQL_ENGINE_.' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
