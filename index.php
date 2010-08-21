<?
if(file_exists(dirname(__FILE__).'/.htaccess')){
//        die("aca esta, existe el muy verga");
	include_once(dirname(__FILE__).'/app_index.php');
	return;
}
include_once(dirname(__FILE__).'/includes/configuraciones.php');
header('content-type: text/plain;');
var_dump($windows);
$windows = (isset($_ENV["OS"])&&preg_match('(windows)',strtolower($_ENV["OS"])));

echo str_repeat('-',200)."\n";
echo "            contenido de ".realpath(dirname(__FILE__))."/includes/configuraciones.php\n";
echo str_repeat('-',200)."\n";
?>
	/* BASE DE DATOS */
	define("DB_HOST", "<?=DB_HOST?>");
	define("DB_USER", "<?=DB_USER?>");
	define("DB_PASS", "****");
	define('DB_DATABASE', '<?=DB_DATABASE?>');
	
	define('CFG_PATH_ROOT', '<?=realpath(dirname(__FILE__))?>');
	
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
	define('CONF_SUBURL_APP','<?=$suburl = (substr($_SERVER['REQUEST_URI'], 0))?>');
	define("CONF_URL_APP", 'http://'.$_SERVER['HTTP_HOST'].CONF_SUBURL_APP);
	define("CONF_DEBUG_ENVIRONMENT", 1);
	if (!getenv('PHP_PEAR_INSTALL_DIR')) {
		putenv('PHP_PEAR_INSTALL_DIR=' . CFG_PATH_ROOT.CONF_PATH_INCLUDES.'pearlib');
	}
<?
echo "\n".str_repeat('-',200)."\n";
echo "\n\n";

echo "\n".str_repeat('-',200)."\n";
echo "            contenido de ".realpath(dirname(__FILE__))."/.htaccess\n";
echo str_repeat('-',200);
?> 
Options +FollowSymlinks

<IfModule mod_rewrite.c>
	RewriteEngine on
	RewriteRule (^(?!app_index[.]php)(?!index[.]php)(?!skin[/].*)(?!foro[/].*)(?!img[/].*).*)$ <?=$windows?'':$suburl?>app_index.php
</IfModule>
ErrorDocument 404 /this_will_never_happen_if_mod_rewrite_is_on_check_mod_rewrite_module_for_apache_on_http_conf_file.php
<?
echo "\n".str_repeat('-',200)."\n";
?>
