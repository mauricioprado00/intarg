<?
if(strpos($_SERVER["REQUEST_URI"], "~bigmoto") !== false)
{/*configuracion para el sitio*/
	{
	/*
	Database:	bigmoto_app
	Host:	localhost
	Username:	bigmoto_app
	Password:	bigmoto_app
	*/
	}

	/* BASE DE DATOS */
	define("DB_HOST", "localhost");
	define("DB_USER", "bigmoto_app");
	define("DB_PASS", "bigmoto_app");
	define('DB_DATABASE', 'bigmoto_app');
	
	//define('CFG_PATH_ROOT', $_SERVER['DOCUMENT_ROOT']);
	define('CFG_PATH_ROOT', '/home/bigmoto/domains/bigmotorcycles.com.ar/public_html');
	//define('CFG_PATH_ROOT', dirname(dirname(__FILE__).'/../../'));
	define('CONF_PATH_APP', '/');
	define('CONF_PATH_DESIGN', CONF_PATH_APP.'design/');
	define('CONF_PATH_INCLUDES', CONF_PATH_APP.'includes/');
	define('CONF_PATH_CLASSES', CONF_PATH_INCLUDES.'classes/');
	define('CONF_PATH_CORE', CONF_PATH_CLASSES.'Core/');
	define('CONF_SUBPATH_LAYOUT', 'layout/');
	define('CONF_SUBPATH_DESIGN', 'design/');
	define('CONF_SUBPATH_SKIN', 'skin/');
	define('CONF_PATH_SKIN', CONF_PATH_APP.CONF_SUBPATH_SKIN);
	define('CONF_SUBPATH_TEMPLATE', 'template/');
	define('CONF_SUBURL_APP','/~bigmoto/');
	define("CONF_URL_APP", 'http://'.$_SERVER['HTTP_HOST'].CONF_SUBURL_APP);
	define("CONF_DEBUG_ENVIRONMENT", 0);
	if (!getenv('PHP_PEAR_INSTALL_DIR')) {
		putenv('PHP_PEAR_INSTALL_DIR=' . CFG_PATH_ROOT.CONF_PATH_INCLUDES.'pearlib');
	}

}
else
{
	/* BASE DE DATOS */
	define("DB_HOST", "192.168.1.215");
	define("DB_USER", "root");
	define("DB_PASS", "elNuev0");
//	define("DB_HOST", "192.168.1.100");
//	define("DB_USER", "root");
//	define("DB_PASS", "desbole");
	define('DB_DATABASE', 'app_inta');
	
	define('CFG_PATH_ROOT', '/var/www/inta');
	
	define('CONF_PATH_APP', '/');
	define('CONF_PATH_DESIGN', CONF_PATH_APP.'design/');
	define('CONF_PATH_INCLUDES', CONF_PATH_APP.'includes/');
	define('CONF_PATH_CLASSES', CONF_PATH_INCLUDES.'classes/');
	define('CONF_PATH_CORE', CONF_PATH_CLASSES.'Core/');
	define('CONF_SUBPATH_LAYOUT', 'layout/');
	define('CONF_SUBPATH_DESIGN', 'design/');
	define('CONF_SUBPATH_SKIN', 'skin/');
	define('CONF_SUBPATH_UPLOADS', CONF_SUBPATH_SKIN.'uploads/');
	define('CONF_PATH_SKIN', CONF_PATH_APP.CONF_SUBPATH_SKIN);
	define('CONF_PATH_UPLOADS', CONF_PATH_APP.CONF_SUBPATH_UPLOADS);
	define('CONF_SUBPATH_TEMPLATE', 'template/');
	define('CONF_SUBURL_APP','/inta/');
	define("CONF_URL_APP", 'http://'.$_SERVER['HTTP_HOST'].CONF_SUBURL_APP);
	define("CONF_DEBUG_ENVIRONMENT", 0);
	if (!getenv('PHP_PEAR_INSTALL_DIR')) {
		putenv('PHP_PEAR_INSTALL_DIR=' . CFG_PATH_ROOT.CONF_PATH_INCLUDES.'pearlib');
	}
	define('ZFLIBPATH',dirname(__FILE__).'/../../ZendFramework/library/');
}
?>