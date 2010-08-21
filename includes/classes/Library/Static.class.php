<?
class Library_Static{
	private static $classPath;
	private $files;
	private $dir;
	private $once = false;
	private $data = null;
	private static $use_cache_to_find_files = false;
	private static $existing_class_names = array();
	private static $directorios = array('core'=>0);
	private static $current_project_context = 'core';//es una referencia para saber de donde incluimos las clases
  /**
   * Library_Static::addClassDirectory()
   * Permite agregar el directorio de clases asociado a un proyecto
   * @param mixed $directory
   * @param mixed $projectname
   * @return void
   */
	public static function addClassDirectory($directory, $projectname){
		if(isset(self::$directorios[$projectname]))
			die('no se pudo agregar nuevo directorio de clases. Razon: nombre de proyecto repetido `'.$projectname.'`');
		$directory = realpath($directory);
		self::$directorios[$projectname] = $directory;
	}
	public static function init(){
		self::$existing_class_names = get_declared_classes();
		try{
			self::$existing_class_names = array_merge(self::$existing_class_names, get_declared_interfaces());
		}
		catch(Exception $e){;}
	}
	private static function getUseCacheToFindFiles(){
		return(self::$use_cache_to_find_files);
	}
  /**
   * Library_Static::setClassPath()
   * Este metodo setea el path principal de donde se extraen las clases
   * @param mixed $class_path
   * @return void
   */
	public static function setClassPath($class_path){
		self::$classPath = $class_path;
		self::$directorios['core'] = realpath($class_path);
	}
	public static function getClassPath(){
		return(self::$classPath);
	}
	public function __construct(){
		foreach($this as $var=>$value)
			$this->$var = null;
	} 
	public function setIncludeOnce(){
		$this->once = true;
	}
	public function setInclude(){
		$this->once = false;
	}
	public function setFiles($files){
		$this->files = $files;
		return($this);
	}
	public function setDir($dir){
		$this->dir = $dir;
		return($this);
	}
	public function Prepare($files,$dir){
		($this
			->setFiles($files)
			->setDir($dir)
		);
	}
	private function incluir(){
		if(!count($this->files))
			return(false);
		foreach($this->files as $idx=>$file){
			//$include_function = $this->include_function;
			$full_filename = ($this->dir!==null?$this->dir.'/':'').$file;
			if(
				class_exists('Base_MagicHelper')						//ya cargamos la libreria
				&&Base_Configuration::getconf_debug_environment() 	// y estamos en un entorno de pruebas 
				&&Base_MagicHelper::isAWindowsServer()				// y estamos en un windows
			){
				//var_dump($full_filename);
				//var_dump(Base_MagicHelper::PathResolve(realpath($full_filename)));
				$existe = Base_MagicHelper::doesReallyFileExists($full_filename);
			}
			else{
				$existe = file_exists($full_filename);
			}
			if(!$existe){
				echo "no existe archivo $full_filename, informacion de debug: <br />\n";
				var_dump($this->data);
				continue;
			}
			if($this->once){
				include_once($full_filename);
			}
			else{
				include($full_filename);
			}
		}
	}
	private function autoPrepare(){
		$data = debug_backtrace();
		$this->data = $data = $data[1];
		$this->Prepare($data['args'],dirname($data['file']));
	} 
	public static function _include(){
		$obj = new self();
		$obj->autoPrepare();
		$obj->setInclude();
		$obj->incluir();
	} 
	public static function _include_once(){
		$obj = new self();
		$obj->autoPrepare();
		$obj->setIncludeOnce();
		$obj->incluir();
	} 
	public static function _class(){
		static $clases_incluidas = array();
		$obj = new self();
		$obj->autoPrepare();
		$obj->setDir(self::getClassPath());
		$files = array();
		foreach($obj->files as $idx=>$file)
			if(!in_array($file, $clases_incluidas)){
				$clases_incluidas[] = $file;
				$files[$idx] = $file.'.class.php';
			}
		if(!count($files))
			return;
		$obj->files = $files;
		$obj->setIncludeOnce();
		$obj->incluir();
	}
	private static function _import(){
		$args = func_get_args();
		if(count($args)!=1)
			return(false);
		$str_import = $args[0];
		//echo "cadena esta bien $str_import\n";
		$arr_import = explode('.', $str_import);
		$last_component = array_pop($arr_import);
		$component_uid = implode('_', $arr_import);
		$path = implode('/', $arr_import);
		
		$path = Base_MagicHelper::PathResolve(dirname(__FILE__).'/'.$path);
		
		$obj = new self();
		$data = debug_backtrace();//desde donde llaman
		$obj->data =  $data = $data[1];
		$max_deep_level = null;
		switch($last_component){
			case '%':
				$max_deep_level = 0;
			case '*':{
				if(self::getUseCacheToFindFiles()&&file_exists($cache_file_name = CFG_PATH_ROOT.'/cache/import/'.$component_uid.$max_deep_level.'.serialized')){
					$arr_files = unserialize(file_get_contents($cache_file_name)); 
				}
				else{
					self::_class('Base_FileFilterRetriever');
					$x = new Base_FileFilterRetriever();
					//$x->precede(array('Abstract.php','Object.php','Abstract.class.php','Object.class.php'));
					//$x->setExtensiones('class.php','txt');
					$x->setExtensiones('class.php','php');
					$x->Start($path,$max_deep_level);
					//var_dump($x->getFilteredFiles());
					$arr_files = $obj->resolveDependencies($str_import, $x->getFilteredFiles());
					foreach($arr_files as $idx=>$file){
						if(in_array('fwtools', explode('/', $file))){
							unset($arr_files[$idx]);
						}
					}

					if(self::getUseCacheToFindFiles()){
						if(!file_exists(dirname($cache_file_name))){
							Base_FM::mkdir(dirname($cache_file_name), 0777, true);
							//chmod(dirname($cache_file_name), 0777);
						}
						Base_FM::file_put_contents($cache_file_name, serialize($arr_files));
						//chmod($cache_file_name, 0777);
					}
				}
//				$txt = var_export(array($arr_import, $max_deep_level, $component_uid, $arr_files), true);
//				file_put_contents(CFG_PATH_ROOT.'/cache/logs.txt', $txt, FILE_APPEND);
				$obj->Prepare(
					//$x->getFilteredFiles(),null
					$arr_files, null
				);
				break;
			}
			default:{
				if(self::getUseCacheToFindFiles()&&file_exists($cache_file_name = CFG_PATH_ROOT.'/cache/import/'.$component_uid.$last_component.'.class.serialized')){
					$arr_files = unserialize(file_get_contents($cache_file_name));
				}
				else{
					$file = $path.$last_component.'.class.php';
					$arr_files = $obj->resolveDependencies($str_import, array($file));
					if(self::getUseCacheToFindFiles()){
						if(!file_exists(dirname($cache_file_name))){
							Base_FM::mkdir(dirname($cache_file_name), 0777, true);
							//chmod(dirname($cache_file_name), 0777);
						}
						Base_FM::file_put_contents($cache_file_name, serialize($arr_files));
						//chmod($cache_file_name, 0777);
					}
				}
				$obj->Prepare(
					$arr_files, null
					//array($last_component.'.class.php'),$path
				);
				break;
			}
		}
		$obj->setIncludeOnce();
		$obj->incluir();
	}
	public static function import(){
		$args = func_get_args();
		if(is_array($args[0])){
			$r = true;
			foreach($args as $arg)
				$r = $r && self::import($arg);
			return($r);
		}
		self::_class('Base_RegExp');
		$imports_re = new Base_RegExp('/([a-zA-Z0-9.*%]+)[ ]*[,]?[ ]*/');
		$imports = $imports_re->executeOn($args[0]);
		$pathok_re = new Base_RegExp('/([a-zA-Z0-9]+)([.]([a-zA-Z0-9]+))*(?:([.][%])|([.][*]))?/');
		//var_export($imports);
		foreach($imports[1] as $str_import){
			$res = $pathok_re->executeOn($str_import);
			if(count($res)<0||count($res[0])<0||$res[0][0]!=$str_import){
				var_dump($res);
				echo "Error en la cadena: $str_import\n";
			}
			else self::_import($str_import);
			//var_dump($pathok_re->executeOn($str_import));
		}
		return(true);
	}
	public function resolveDependencies($str_import, $arr_files){
		//echo "resolviendo dependencias para $str_import\n";
		//var_dump($arr_files);
		$arr_dependency = $this->fileArrayToDependencyArray($arr_files);
		$arr_ordered_files = $this->dependencyArrayToOrderedFileArray($arr_dependency);
		
		//var_dump($arr_dependency);
		//var_dump($arr_ordered_files);
		return($arr_ordered_files);
	}
	public function dependencyArrayToOrderedFileArray($arr_dependency){
		$arr_ordered_files = array();
		$prev_count = null;
		//var_dump($arr_dependency);
		while($count = count($arr_dependency)){
			if($count === $prev_count){
				echo "error de inclusiones verifique las siguiente clases\n";
				var_dump($arr_dependency);
				die();
			}
			$prev_count= $count;
			$arr_dependency2 = array();
			foreach($arr_dependency as $filepath=>$arr_filedependecy){
				$no_dependency_left = false;
				if($arr_filedependecy===NULL){
					//$arr_dependency[$filepath] = true;
					$no_dependency_left = true;
				}
				else{
					$no_dependency_left = true;
					//var_dump($arr_filedependecy);
					foreach($arr_filedependecy as $filedependecy)
						if(!in_array($filedependecy,$arr_ordered_files)){
							$no_dependency_left = false;
							break;
						}
				}
				if($no_dependency_left)	
					$arr_ordered_files[] = $filepath;
				else $arr_dependency2[$filepath] = $arr_filedependecy;
			}
			$arr_dependency = $arr_dependency2;
		}
		return($arr_ordered_files);
	}
	public function fileArrayToDependencyArray($arr_files){
		//var_dump($arr_files);
		$arr_dependency = array();
		$arr_nuevos = array();
		$args = func_get_args();
		if(count($args)>1){
			//echo "includes desde parametro\n";
			$incluidos = $args[1];
		}
		else{
			//echo "includes calculando\n";
			$incluidos = array();
			if(!self::getUseCacheToFindFiles())//si estamos usando cache tenemos que meter todo en las dependencias
			foreach($x=get_included_files() as $filepath){
				$incluidos[] = implode('/', explode('\\', $filepath));
			}
		}
		foreach($arr_files as $filepath){
			$classnames = $this->getExtensionClassname($filepath);
			if($classnames!==null){
				foreach($classnames as $classname){
					$class_filepath = implode('/',explode('\\',realpath(dirname(__FILE__)))).'/'.implode('/', explode('_', $classname));
					if(file_exists($class_filepath.'.class.php'))
						$class_filepath .= '.class.php';
					elseif(file_exists($class_filepath.'.php'))
						$class_filepath .= '.php';
					else continue;
					//var_dump($class_filepath);
					if(in_array($class_filepath, $arr_files)){
						//echo 'existente'.$class_filepath."\n";
					}
					elseif(in_array($class_filepath, $incluidos)){
						$class_filepath = null;
					}
					else{
						//echo 'nuevo '.$class_filepath."\n";
						if(!in_array($class_filepath, $arr_nuevos))
							$arr_nuevos[] = $class_filepath;
//						$class_dependencty = $this->fileArrayToDependencyArray('',array($class_filepath));
//						$arr_dependency[] = $class_dependencty[0];
					}
					if($class_filepath!==null)
						$arr_dependency[$filepath][] = $class_filepath;
					else $arr_dependency[$filepath] = null;
				}
			}
			else{
				$arr_dependency[$filepath] = null;
			}
		}
		//var_dump($arr_nuevos);
		if(!$arr_dependency){
			foreach($arr_files as $filepath){
				$classnames = $this->getExtensionClassname($filepath);
				//var_dump($classnames);
				if($classnames!==null){
					foreach($classnames as $classname){
						$class_filepath = implode('/',explode('\\',realpath(dirname(__FILE__)))).'/'.implode('/', explode('_', $classname));
						if(file_exists($class_filepath.'.class.php'))
							$class_filepath .= '.class.php';
						elseif(file_exists($class_filepath.'.php'))
							$class_filepath .= '.php';
						else{
							echo "no existe ".($class_filepath.'.class.php');
							continue;
						};
						//var_dump($class_filepath);
						if(in_array($class_filepath, $arr_files)){
							//echo 'existente'.$class_filepath."\n";
						}
						elseif(in_array($class_filepath, $incluidos)){
							$class_filepath = null;
						}
						else{
							//echo 'nuevo '.$class_filepath."\n";
							if(!in_array($class_filepath, $arr_nuevos))
								$arr_nuevos[] = $class_filepath;
	//						$class_dependencty = $this->fileArrayToDependencyArray('',array($class_filepath));
	//						$arr_dependency[] = $class_dependencty[0];
						}
						if($class_filepath!==null)
							$arr_dependency[$filepath][] = $class_filepath;
						else $arr_dependency[$filepath] = null;
					}
				}
				else{
					$arr_dependency[$filepath] = null;
				}
			}
		}
		foreach($arr_nuevos as $filepath){
			//echo "recall\n";
			$arr_dependency2 = $this->fileArrayToDependencyArray(array($filepath),$incluidos);
			//var_dump(array($arr_dependency2,array($filepath),$incluidos));
			foreach($arr_dependency2 as $filepath=>$class_filepath){
				$arr_dependency[$filepath] = $class_filepath;
			}
		}
		return($arr_dependency);
	}
	public function getExtensionClassname($file){
		$default_classnames = self::$existing_class_names;
		$c = file_get_contents($file);
		$classnames = array();
		/*busco cadena especifica que declara dependencias*/
		$reg = 
		//'([ \t]*class\s+[a-zA-Z]+[a-zA-Z_0-9]*\s+extends\s+([a-zA-Z]+[a-zA-Z_0-9]*))';
		'([<]dependency[>]([a-z,A-Z0-9_]*)[<][/]dependency[>])';
		preg_match_all($reg, $c, $matches);
		if(count($matches)>1&&count($matches[1])>0){
			$dependecyes = $matches[1][0];
			$dependecyes = explode(',', $dependecyes);
			foreach($dependecyes as $dependecy){
				$classname = trim($dependecy);
				if(in_array($classname, $default_classnames))
					continue;
				$classnames[] = $classname;
			}
			//var_dump($file,$dependecyes);
		}

		
		/*elimino comentarios de los archivos de destino*/
//		$reg_comentarios_barra_doble = '([/][/].*)';
//		$reg_comentarios_multilinea = '([/][*]((?![*][/])((.)|\s))*[*][/])';
//		$c = preg_replace($reg_comentarios_barra_doble,'',$c);
//		$c = preg_replace($reg_comentarios_multilinea,'',$c);
		$c = php_strip_whitespace($file);
		$nombre_clase = '[a-zA-Z]+[a-zA-Z_0-9]*';
		$reg = 
		//'([ \t]*class\s+[a-zA-Z]+[a-zA-Z_0-9]*\s+extends\s+([a-zA-Z]+[a-zA-Z_0-9]*))';
//		'([ \t]*class\s+[a-zA-Z]+[a-zA-Z_0-9]*\s+(extends\s+([a-zA-Z]+[a-zA-Z_0-9]*))?(\s*implements\s+((([a-zA-Z]+[a-zA-Z_0-9]*)(\s*[,]\s*)*)*))?)';

		'([ \t]*class\s+'.$nombre_clase.'\s+(extends\s+('.$nombre_clase.'))?(\s*implements\s+((('.$nombre_clase.')(\s*[,]\s*)*)*))?)';
		preg_match_all($reg, $c, $matches);
		//$classnames = array();
		if(count($matches)>2&&count($matches[2])>0){
			//var_dump($matches[2]);
			//var_dump($matches[2]);
			//die();
			foreach($matches[2] as $classname){/*extends*/
				//var_dump(array(($classname&&!in_array($classname, $default_classnames)),$classname,$default_classnames));
				if($classname&&!in_array($classname, $default_classnames))
					$classnames[] = $classname;
			}
			if(count($matches)>4&&count($matches[4])){
				foreach($matches[4] as $classname){/*implements*/
					if($classname&&!in_array($classname, $default_classnames)){
						$re = '/('.$nombre_clase.')/';
						if(preg_match_all($re, $classname, $matches2)){
							foreach($matches2[1] as $classname){
								if($classname&&!in_array($classname, $default_classnames)){
									$classnames[] = $classname;
								}
							}
						}
					}
				}
			}
		}
		if(count($classnames)==0)
			return(null);
		return($classnames);
	}
}
Library_Static::init();
?>