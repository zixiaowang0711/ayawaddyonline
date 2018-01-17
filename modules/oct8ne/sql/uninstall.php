<?php
/**
 * Pqproductbadges Module
 *
 * @category  Prestashop
 * @category  Module
 * @author    Prestaquality.com
 * @copyright 2014 - 2015 Prestaquality
 * @license   Commercial license see license.txt
 * Support by mail  : info@prestaquality.com
 */

$sql   = array();
$sql[] = 'DROP TABLE IF EXISTS `'._DB_PREFIX_.'oct8nehistory`';


foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
