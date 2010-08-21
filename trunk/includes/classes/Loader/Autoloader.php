<?
class Loader_Autoloader implements Zend_Loader_Autoloader_Interface{
	public function autoload($class){
		$debug = 0;
		
//		$aClassName = explode('_',$class);
//		$classFileName = array_pop($aClassName);
//		$classPath = implode('/',$aClassName);
//		
//		$classSufix = '.class';
//		$ext = '.php';
//		
//		if($debug){
//		    echo "<pre>";
//		    var_dump($aClassName);
//		    echo "</pre>";
//		    echo "className: " . $class."<br>";
//		    echo "classFileName: " . $classFileName."<br>";
//		    echo "ClassPath: " . $classPath;
//		    echo "<br>trato con: " . CONF_PATH_FRONTEND_CLASSES . $class . $classSufix . $ext;
//		    echo "<br>trato con: " . CONF_PATH_CLASSES . $class . $classSufix . $ext;
//		    echo "<br>trato con: " . CONF_PATH_FRONTEND_CLASSES . $classPath . "/" .$classFileName . $classSufix . $ext;
//		    echo "<br>trato con: " . CONF_PATH_CLASSES . $classPath . "/" .$classFileName . $classSufix . $ext;
//		}
		static $paths = null;
		static $exts = null;
		if(!isset($paths)){
			$paths = array(
				CFG_PATH_ROOT . CONF_PATH_CLASSES,
				CFG_PATH_ROOT . CONF_PATH_FRONTEND_CLASSES,
			);
			$exts = array(
				'.php',
				'.class.php',
			);
		};
		$included = false;
		$class_file = explode('_', $class);
		foreach($class_file as &$f)$f = ucfirst($f);
			
		$class_file = implode('/', $class_file);
		foreach($paths as $path){
			foreach($exts as $ext){
				$file = $path.$class_file.$ext;
				//var_dump($file);
				if(file_exists($file)){
					include_once($file);
					$included = true;
					break;
				}
			}
			if($included)break;
		}
		if(!$included){
			return false;
			var_dump($args = func_get_args());
			echo ('no se puede incluir '.$class.', no existe archivo asociado a la clase');
			$bt = debug_backtrace();
			Zend_Debug::dump($bt);
			die();
		}
		return;
		
//
//		if(file_exists(CFG_PATH_ROOT . CONF_PATH_FRONTEND_CLASSES . $class . $classSufix . $ext)){
//		    include CFG_PATH_ROOT . CONF_PATH_FRONTEND_CLASSES . $class . $classSufix . $ext;
//		}elseif(file_exists(CFG_PATH_ROOT . CONF_PATH_CLASSES . $class . $classSufix . $ext)){
//		    include CFG_PATH_ROOT . CONF_PATH_CLASSES . $class . $classSufix . $ext;
//		}elseif(file_exists(CFG_PATH_ROOT . CONF_PATH_FRONTEND_CLASSES . $classPath . "/" .$classFileName . $classSufix . $ext)){
//		    include CFG_PATH_ROOT . CONF_PATH_FRONTEND_CLASSES . $classPath . "/" .$classFileName . $classSufix . $ext;
//		}elseif(file_exists(CFG_PATH_ROOT . CONF_PATH_CLASSES . $classPath . "/" .$classFileName . $classSufix . $ext)){
//		    include CFG_PATH_ROOT . CONF_PATH_CLASSES . $classPath . "/" .$classFileName . $classSufix . $ext;
//		}else
//		    return false;
	}
}
?>