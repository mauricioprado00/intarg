<?
class Core_PathHelper extends Base_Singleton{
	
/*
	define('CONF_PATH_DESIGN', CONF_PATH_APP.'design/');
	define('CONF_PATH_INCLUDES', CONF_PATH_APP.'includes/');
	define('CONF_PATH_CLASSES', CONF_PATH_INCLUDES.'classes/');
	define('CONF_PATH_CORE', CONF_PATH_CLASSES.'Core/');
	define('CONF_SUBPATH_LAYOUT', 'layout/');
	define('CONF_SUBPATH_DESIGN', 'design/');
	define('CONF_SUBPATH_SKIN', 'skin/');
	define('CONF_PATH_SKIN', CONF_PATH_APP.CONF_SUBPATH_SKIN);
	define('CONF_SUBPATH_TEMPLATE', 'template/');
	define('CONF_SUBURL_APP','/bigmotorcicle/');
	define("CONF_URL_APP", 'http://'.$_SERVER['HTTP_HOST'].CONF_SUBURL_APP);

*/
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function getPath($rel_path){
		return(CFG_PATH_ROOT.CONF_PATH_APP.$rel_path);
	}
	public static function getDesignPath($rel_path){
		return(self::getPath(CONF_PATH_DESIGN.$rel_path));
	}
	public static function getIncludesPath($rel_path){
		return(self::getPath(CONF_PATH_INCLUDES.$rel_path));
	}
	public static function getClassesPath($rel_path){
		return(self::getPath(CONF_PATH_CLASSES.$rel_path));
	}
	public static function getCoreClassesPath($rel_path){
		return(self::getPath(CONF_PATH_CORE.$rel_path));
	}
	public static function getSkinPath($rel_path){
		return(self::getPath(CONF_SUBPATH_SKIN.$rel_path));
	}
}
?>