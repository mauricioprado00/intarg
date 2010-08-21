<?
setlocale(LC_ALL,"es_AR@euro","es_AR","esp");
//include_once(dirname(__FILE__).'/classes/FM.php');
//include_once($file = dirname(__FILE__).'/classes/Library.php');
include_once(dirname(__FILE__).'/configuraciones.php');
set_include_path(ZFLIBPATH);
require_once 'Zend/Loader/Autoloader.php';
false&&$loader=new Zend_Loader_Autoloader();
$loader = Zend_Loader_Autoloader::getInstance();

require_once CFG_PATH_ROOT.CONF_PATH_CLASSES.'/Loader/Autoloader.php';
$loader->pushAutoloader(array('Loader_Autoloader','autoload'),'');
function c($o){
	return Core::getInstance()->getObject($o);
}
////Library::_include_once('configuraciones.php');
//Library::setClassPath(dirname(__FILE__).'/classes');
//Library::_class(
//	'Base_Error',
//	'Errors',
//	'Configuration',
//	'Parameters',
//	//'DB',
//	'MagicHelper',
//	//'Singleton',
//	//'Layout',
//	'FileFilter',
//	'FileFilterIncluder',
//	'FileFilterRetriever'
//);
//Library::import('Layout, Core.*');
////Library::import('Layout');
?>