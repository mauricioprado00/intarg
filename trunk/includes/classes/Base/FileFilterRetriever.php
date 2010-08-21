<?php
class Base_FileFilterRetriever extends Base_FileFilter{
	private $filtered_files;
	private $relative_files;
	public function __construct($extensiones = null){
		$this->filtered_files = array();
		$this->relative_files = array();
		parent::__construct($extensiones);
	}
	public function getFilteredFiles($relative=false){
		if($relative)
			$ret = $this->relative_files;
		else $ret = $this->filtered_files;
		return($ret);
	}
	public function onFilteredFile($fullpath, $relativepath){
		$this->filtered_files[] = $fullpath;//$this->getCurrentFileName(true);
		$this->relative_files[] = $relativepath;//$this->getCurrentFileName(false);
	}
}
/** /
// 		TESTEO		
$x = new Base_FileFilterRetriever(array('txt'));
//if(!$x->setExtensiones('class.php','php')){
//	echo "hay un error";
//	var_dump(Base_Errors::getLastError());
//}
$x->Start(dirname(__FILE__).'/../..',0);
var_dump(array('estos son'=>$x->getFilteredFiles()));

/**/