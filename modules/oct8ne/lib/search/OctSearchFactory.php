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
class OctSearchFactory
{

    /**
     * Devuelve el motor de busqueda especifico
     * @param $param motor de busqueda.
     * @return null|OctDoofinderSearch|OctInternalSearch
     */
    public function getInstance($param)
    {
        //engine
        $searchEngine = NULL;
        switch ($param) {

            //internal
            case 1:
                require_once dirname(__FILE__) . '/OctInternalSearch.php';
                $searchEngine = new OctInternalSearch();
                break;

            //doofinder
            case 2:

                if ($this->checkSearchEngine("doofinder")) {

                    require_once dirname(__FILE__) . '/OctDoofinderSearch.php';
                    $searchEngine = new OctDoofinderSearch();

                } else {

                    require_once dirname(__FILE__) . '/OctInternalSearch.php';
                    $searchEngine = new OctInternalSearch();
                }

                break;

            //internal
            default:
                require_once dirname(__FILE__) . '/OctInternalSearch.php';
                $searchEngine = new OctInternalSearch();
                break;
        }
        return $searchEngine;
    }


    /**
     * Comprueba si el modulo esta activo o no
     * @param $name
     */
    private function checkSearchEngine($name)
    {
       return  (Module::isEnabled($name));
    }
}