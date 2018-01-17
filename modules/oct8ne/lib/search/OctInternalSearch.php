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

class OctInternalSearch extends AbstractSearchEngine
{

    //realiza la busqueda a partir del motor de busqueda por defecto de prestashop
    public function doSearch($language, $search, $page, $pageSize, $orderBy, $dir)
    {
        //buscar
        //subir
        $aux = Search::find($language, $search, $page, $pageSize, $orderBy, $dir);


        if ($orderBy == 'price') {
            Tools::orderbyPrice($aux["result"], $dir);
        }

        return $aux;
    }
}