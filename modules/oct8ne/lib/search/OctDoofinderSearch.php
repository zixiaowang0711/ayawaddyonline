<?php

/**
 * Prestashop module for Oct8ne
 *
 * @category  Prestashop
 * @category  Module
 * @author    Prestaquality.com
 * @copyright 2016 Prestaquality
 * @license   Commercial license see license.txt
 * Support by mail  : info@prestaquality.com
 */

require_once dirname(__FILE__) . '/AbstractSearchEngine.php';

/**
 * Implementacion especifica de doofinder
 * Class OctDoofinderSearch
 */
class OctDoofinderSearch extends AbstractSearchEngine
{

    function doSearch($language, $search, $page, $pageSize, $orderBy, $dir)
    {
        //Cargar doofinder.
        $doofinder = Module::getInstanceByName("doofinder");
        $aux = $doofinder->searchOnApi($search, $page, $pageSize);
        return $aux;
    }
}