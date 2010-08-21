<?
abstract class Base_FileSystemScanner{
	private $path;//esto es igual que scanning_directory
	private $scanning_directory;//esto es igual que path
	private $current_file;
	private $current_file_name;
	private $current_path;
	private $current_is_directory;
	private $current_dir_name;
	private $deep_level;
	private $max_deep_level;
  /**
   * Base_FileSystemScanner::onBegin()
   * Se ejecuta cuando comienza el escaneo
   * @return void
   */
	abstract function onBegin();
  /**
   * Base_FileSystemScanner::onFinalize()
   * Se ejecuta cuando termina de escanear, para por parametro el resultado
   * true si escaneo todos los archivos, false si se aborto el escaneo en algun momento
   * @return
   */
	abstract function onFinalize($return);
  /**
   * Base_FileSystemScanner::onCloseDirectory()
   * Se ejecuta cuando termina de escanear un directorio. 
   * @return
   */
	abstract function onCloseDirectory();
  /**
   * Base_FileSystemScanner::onOpenDirectory()
   * Se ejecuta cuando comienza a escanear un directorio
   * @return: si el retorno es falso se aborta el escaneo
   */
	abstract function onOpenDirectory();
	/*{
		if($dir_name==='magicpath')
			return(false);
		echo "<hr>escaneando directorio <b>$directory</b><br><br>" ;
		return(true);//abrirr directrio o seguri con la rox entrada
	}*/
  /**
   * Base_FileSystemScanner::onScanner()
   * Se ejecuta cuando comienza a escanear un archivo
   * @return: si el retorno es falso se aborta el escaneo
   */
	abstract function onScanner();
	/*{
		if(!$is_directory)
			echo $file_name."<br>";
		return(true);//continuar escaneando o abortar
	}*/

  /**
   * Base_FileSystemScanner::isDirectory()
   * @return true si el archivo escaneandose actualmente es un directorio 
   */
	public function isDirectory(){
		return($this->current_is_directory?true:false);
	}
  /**
   * Base_FileSystemScanner::getCurrentFileName()
   * Devuelve el nombre del archivo escaneandose actualmente.
   * si $fullpath es false devuelve solo el nombre, en caso contrario devuelve la ruta completa
   * @param bool $fullpath
   * @return
   */
	public function getCurrentFileName($fullpath=false){//archivo que se esta escaneando en este momento
		if($fullpath==false)
			return($this->current_file_name);
		return($this->current_file);
	}
  /**
   * Base_FileSystemScanner::getCurrentDirectory()
   * Devuelv el nombre del directorio en el que se listan los archivos actualmente. 
   * si fullpath es true devuelve la ruta completa, caso contrario solo devuelve el nombre
   * @param bool $fullpath
   * @return
   */
	public function getCurrentDirectory($fullpath=false){//directorio que se esta escaneando en este momento
		if($fullpath==true)
			return($this->current_path);
		return($this->current_dir_name);
	}
  /**
   * Base_FileSystemScanner::getScannedDirectory()
   * Devuelve el directorio que se paso por parametro para escanear a el metodo Base_FileSystemScanner::Start()
   * @return
   */
	public function getScannedDirectory(){//directorio que se pasa por parametro para escanear (el raiz)
		return($this->scanning_directory);
	}
  /**
   * Base_FileSystemScanner::getDeepLevel()
   * Devuelve la profundidad de directorios en la que se encuentra escaneando
   * @return
   */
	public function getDeepLevel(){
		return($this->deep_level);
	}
	private $contexto = array();
  /**
   * Base_FileSystemScanner::storeContext()
   *
   * @return
   */
	private function storeContext(){
		$store = new StdClass();
		$a = get_class_vars(__CLASS__);
		$k = array_keys($a);
		foreach($k as $attr)
			$store->$attr = $this->$attr;
		array_push($this->contexto, $store);
	}
  /**
   * Base_FileSystemScanner::restoreContext()
   *
   * @return
   */
	private function restoreContext(){
		$restore = array_pop($this->contexto);
		foreach($restore as $attr=>$val)
			$this->$attr = $val;
	}

  /**
   * Base_FileSystemScanner::ScanDirectory()
   *
   * @param mixed $path
   * @return
   */
	private final function ScanDirectory($path){
		$child_dirs = array();
		$retv = true;
		if(
			($this->max_deep_level!==null) && ($this->max_deep_level<$this->deep_level))
			return($retv);
		if($this->onScanner()===false)
			return(false);
		if($this->onOpenDirectory()==false)
			return(true);
		if(($archivos = opendir($path))){
			$abort = false;
				
			$current_dir_name = explode("/",$path);
			$this->current_dir_name = $current_dir_name[count($current_dir_name)-1];
			
			//$preserve = o2a($this,get_class(new self()));
			$this->storeContext();
			/*
			$preserver_current_file_name = $this->current_file_name;
			$preserve_path = $this->current_path;
			*/
			$this->current_path = $path."/";
			while (false !== ($archivo = readdir($archivos)))
			{
				if($archivo=="."||$archivo=="..")
					continue;
				if($is_dir = is_dir($dir = $path."/".$archivo)){
					$child_dirs[] = $archivo;
					continue;
				}
				
				$c = $path."/".$archivo;
				$this->current_file = $c;
				$this->current_file_name = $archivo;
				$this->current_is_directory = $is_dir;
				if($is_dir)
				{
					$this->deep_level++;
					//$this->current_file_name = $this->current_dir_name;
					$ret = $this->ScanDirectory($c);
					$this->deep_level--;
					if($ret===false){
						$abort = true;
						break;	
					}
				}
				if($this->onScanner()===false){
					$abort = true;
					break;
				}
			}
			foreach($child_dirs as $archivo){
				$c = $path."/".$archivo;
				$this->current_file = $c;
				$this->current_file_name = $archivo;
				$this->current_is_directory = $is_dir = is_dir($dir = $path."/".$archivo);
				if($is_dir)
				{
					$this->deep_level++;
					//$this->current_file_name = $this->current_dir_name;
					$ret = $this->ScanDirectory($c);
					$this->deep_level--;
					if($ret===false){
						$abort = true;
						break;	
					}
				}
				if($this->onScanner()===false){
					$abort = true;
					break;
				}
			}
			if($abort){
				$retv = false;
			}
			closedir($archivos);
			//$this->current_path=$preserve_path;
			$this->restoreContext();
			//var_dump(get_class(new self()));
			//a2o($preserve,$this,get_class(new self()));
		}
		$this->onCloseDirectory();
		return($retv);
	}
  /**
   * Base_FileSystemScanner::Start()
   *
   * @param mixed $path
   * @return
   */
	public final function Start($path,$max_deep_level=null){
		$this->max_deep_level = $max_deep_level;
		if (file_exists($path)&&is_dir($path)){
			$path = Base_MagicHelper::PathResolve($path);
			if(substr($path,-1)=='/')
				$path = substr($path,0,-1);
			$this->scanning_directory = $this->path = $path;
			$this->current_file_name = $path;
			$this->current_dir_name = "";
			$this->current_file = $path;
			$this->current_is_directory = true;
			$this->onBegin();
			$this->current_path = "";
			$this->deep_level = 0;
			$ret = $this->ScanDirectory($this->path);
			$this->onFinalize($ret);
			return($ret);
		}
		return(false);
	}
}