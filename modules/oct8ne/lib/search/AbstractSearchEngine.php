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

abstract class AbstractSearchEngine
{
    //Método abstracto a implementar que realiza la busqueda dependiendo del motor de búsqueda determinado
    //subir
    abstract function doSearch($language, $search, $page, $pageSize, $orderBy, $dir);
}