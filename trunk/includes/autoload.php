<?php
/*
 * Mauri, fijate, es una boludez el coso este. 
 * Primero se fija si no tiene definidas las constantes que va a usar y las define.
 * despues chequea en 2 directorios que es donde se supone que van a estar las clases que se van a incluir mas seguido, el clases del back y el del frontend
 * si no encuentra ahi, exploda el nombre de la clase, saca el ultimo elem que seria el nombre de la clase, y lo imploda cambiandole los _ por / y usa eso de path, le agrega al final el nombre de la clase y listo.
 * Si no encuentra nada, devuelve false y genera el fatal, el false lo devuelve al pedo, no es necesario.
 * lo que si hay que tener en cuenta, es que hay que armar las clases con el nombre correcto si las ponemos fuera del root de los directorios de clases.
 * Hay algunas clases del proy a las que vamos a tener uqe cambiar el nombre, ej: Core/Object.php se va a tener que llamar Core/Object.class.php,
 * tambien puedo hacer un fallback para eso, pero me parece que habria que cambiar los nombres
 * Ok el algoritmo lo movi a classes/Loader/Autoloader.class.php aunque lo modifique tanto que ya no es el mismo
 */

    if(!defined('CONF_PATH_FRONTEND_CLASSES'))
        define('CONF_PATH_FRONTEND_CLASSES', $_SERVER['DOCUMENT_ROOT'] . '/granguia/frontend/includes/classes/');
    if(!defined('CONF_PATH_CLASSES'))
        define('CONF_PATH_CLASSES', $_SERVER['DOCUMENT_ROOT'] . '/granguia/includes/classes/');

function __autoload($class_name) {
    $debug = 0;
    
    $aClassName = explode('_',$class_name);
    $classFileName = array_pop($aClassName);
    $classPath = implode('/',$aClassName);
    
    $classSufix = '.class';
    $ext = '.php';

    if($debug){
        echo "<pre>";
        var_dump($aClassName);
        echo "</pre>";
        echo "className: " . $class_name."<br>";
        echo "classFileName: " . $classFileName."<br>";
        echo "ClassPath: " . $classPath;
        echo "<br>trato con: " . CONF_PATH_FRONTEND_CLASSES . $class_name . $classSufix . $ext;
        echo "<br>trato con: " . CONF_PATH_CLASSES . $class_name . $classSufix . $ext;
        echo "<br>trato con: " . CONF_PATH_FRONTEND_CLASSES . $classPath . "/" .$classFileName . $classSufix . $ext;
        echo "<br>trato con: " . CONF_PATH_CLASSES . $classPath . "/" .$classFileName . $classSufix . $ext;
    }
    if(file_exists(CONF_PATH_FRONTEND_CLASSES . $class_name . $classSufix . $ext)){
        include CONF_PATH_FRONTEND_CLASSES . $class_name . $classSufix . $ext;
    }elseif(file_exists(CONF_PATH_CLASSES . $class_name . $classSufix . $ext)){
        include CONF_PATH_CLASSES . $class_name . $classSufix . $ext;
    }elseif(file_exists(CONF_PATH_FRONTEND_CLASSES . $classPath . "/" .$classFileName . $classSufix . $ext)){
        include CONF_PATH_FRONTEND_CLASSES . $classPath . "/" .$classFileName . $classSufix . $ext;
    }elseif(file_exists(CONF_PATH_CLASSES . $classPath . "/" .$classFileName . $classSufix . $ext)){
        include CONF_PATH_CLASSES . $classPath . "/" .$classFileName . $classSufix . $ext;
    }else
        return false;
}
?>