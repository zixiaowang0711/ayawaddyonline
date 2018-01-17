<?php

/**
 * Created by PhpStorm.
 * User: migue
 * Date: 07/10/2016
 * Time: 9:23
 */
class Oct8neCore extends Module
{

    private static $error_file_name = 'error_log.log';
    private static $class_folder    = 'classes/';
    private static $lib_folder      = 'lib/';

    /**
     * añade al log la información sobre una excepcion
     * @param Exception $ex
     */
    public function logException($ex)
    {
        $this->logError($ex->getFile().':'.$ex->getLine().' -> '.$ex->getMessage());
    }

    /**
     * Añade la información indicada al log de excepciones
     * @param type $msg
     */
    public function logError($msg)
    {
        //Recogemos el nombre del archivo local y general
        $files_log = array($this->getLocalPath().self::$error_file_name, _PS_ROOT_DIR_.'/log/'.date('Ymd').'_exception.log');
        $logger    = new FileLogger();
        //Guardamos el log
        foreach ($files_log as $file_log) {
            $logger->setFilename($file_log);
            $logger->logError($msg);
        }
    }

    /**
     * Carga la clase solicitada
     * @param type $class_name Nombre de la clase
     * @throws Exception Si el archivo no existe
     */
    public function loadClass($class_name)
    {
        //Componemos el path
        $path = $this->getLocalPath().self::$class_folder.$class_name.'.php';
        //Comprobamos que exista
        if (!file_exists($path))
            throw new Exception('This class can not be loaded: '.$class_name);
        //La incluimos
        require_once $path;
    }

    /**
     * Carga la libreria solicitada
     * @param type $class_name Nombre de la clase
     * @throws Exception Si el archivo no existe
     */
    public function loadLibrary($class_name,$internal_path = '')
    {
        if(!empty($internal_path))
            $internal_path.='/';
        //Componemos el path
        $path = $this->getLocalPath().self::$lib_folder.$internal_path.$class_name.'.php';
        //Comprobamos que exista
        if (!file_exists($path))
            throw new Exception('This library can not be loaded: '.$class_name);
        //La incluimos
        require_once $path;
    }


    /**
     * Remove hthp: or https:
     * @param $string
     * @return mixed
     */
    public static function removeHttProtocol($string){

        $string = str_replace("https:", "", $string);
        $string = str_replace("http:", "", $string);

        return $string;
    }


}