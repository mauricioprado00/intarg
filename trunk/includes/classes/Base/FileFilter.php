<?
abstract class Base_FileFilter extends Base_FileSystemScanner{
	private $extensiones;
	private $precede;
	public function precede($arr){
		$this->precede = $arr;
	}
	public function __construct($extensiones = null){
		$this->precede = array();
		$this->extensiones = array();
		if($extensiones!==null)
			$this->setExtensiones($extensiones);
	}
	abstract function onFilteredFile($fullpath, $relativepath);
	protected function onBeginFilter(){;}
	protected function onFinalizeFilter(){;}
	/*heredados de Base_FileSystemScanner*/
	public final function onBegin(){
	}
	public final function onFinalize($valor){
	}
	public final function onOpenDirectory(){
		foreach($this->precede as $precede){
			$fullfilename = $this->getCurrentFileName(true).'/'.$precede;
			if(file_exists($fullfilename)){
				if($this->extensionMatchs($fullfilename)){
					$relativefilename = $this->getCurrentFileName(false).'/'.$precede;
//					echo $fullfilename."\n";
//					echo $relativefilename."\n";
					$this->onFilteredFile($fullfilename, $relativefilename);
				}
			}
		}
		return(true);//continuar escaneando o abortar
	}
	public final function onCloseDirectory(){;}
	public final function onScanner(){
		if($this->isDirectory()==false){
			//var_dump(array('extensiones actuales'=>$this->extensiones));
			if(!in_array($this->getCurrentFileName(),$this->precede)){
				if($this->extensionMatchs()){
					//var_dump(array('archivo filtrado'=>$this->getCurrentFileName()));
					$this->onFilteredFile($this->getCurrentFileName(true), $this->getCurrentFileName(false));
				}
			}
		}
		return(true);//continuar escaneando o abortar
	}
	private function extensionMatchs($filename=null){
		if($this->extensiones===null||!is_array($this->extensiones)||count($this->extensiones)==0){
			//var_dump(array($this->extensiones===null, !is_array($this->extensiones), count($this->extensiones)==0));
			return(true);
		}
		if($filename===null){
			$filename = $this->getCurrentFileName();
		}
		$ext = explode('.', $filename);
		array_shift($ext);
		//$ext = implode('.', $ext);
		//var_dump($ext);
		//var_dump(in_array($ext, $this->extensiones));
		if(in_array($ext, $this->extensiones))
			return(true);
		return(false);
	}
	private function validarExtension($ext){
		return(true);
	}
	public function setExtensiones(){
		$args = func_get_args();
		$retv = true;
		if(($count = count($args))<=0){
			Base_Errors::Add('Error en '.__METHOD__.'()  no se pasaron parametros ',debug_backtrace(),1);
			$retv = false;
		}
		elseif($count>1){
			foreach($args as $arg){
				$retv = $retv && self::setExtensiones($arg);
			}
		}
		elseif(is_array($args[0])){
			foreach($args[0] as $arg){
				$retv = $retv && self::setExtensiones($arg);
			}
		}
		else{// un solo argumento, no es array
			if(gettype($args[0])=='string'){
				if($args[0]==''){
					Base_Errors::Add('Error en '.__METHOD__.'()  se paso como parametro una extension vacia',debug_backtrace(),2);
					$retv = false;
				}
				else{
					if($this->validarExtension($args[0])===false){
						Base_Errors::Add('Error en '.__METHOD__.'()  se paso un valor no valido como extension ',debug_backtrace(),2);
						$retv = false;
					}
					else{
						$this->extensiones[] = explode('.',$args[0]);
					}
				}	
			}
			else{
				Base_Errors::Add('Error en '.__METHOD__.'()  el parametro no es un string',debug_backtrace(),2);
				$retv = false;
			}
		}
		return($retv);
	}
}

/** /
// 		TESTEO		
$x = new Base_FileFilter();
if(!$x->setExtensiones('class.php','php')){
	echo "hay un error";
	var_dump(Base_Errors::getLastError());
}
$x->Start(dirname(__FILE__).'/../..',1);
var_dump($x->getFilteredFiles());

/**/