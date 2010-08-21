<?php
class Base_FileFilterIncluder extends Base_FileFilter{
	private $filtered_files;
	private $include_once;
	private $save_filtered_files;
	public function __construct($extensiones = null){
		$this->save_filtered_files = false;
		$this->include_once = true;
		parent::__construct($extensiones);
	}
	protected function onBeginFilter(){
		$this->filtered_files = array();
	}
	protected function onFinalizeFilter(){
	}
	public function setIncludeOnce($include_once){
		$this->include_once = (boolean)$include_once;
	}
	public function _include_once($path,$max_deep_level=null){
		$this->setIncludeOnce(true);
		return($this->Start($path,$max_deep_level));
	}
	public function _include($path,$max_deep_level=null){
		$this->setIncludeOnce(false);
		return($this->Start($path,$max_deep_level));
	}
	public function setSaveFilteredFiles($save){
		$this->save_filtered_files = (boolean)$save;
	}
	public function getFilteredFiles(){
		return($this->filtered_files);
	}
	public function onFilteredFile($fullpath, $relativepath){
		if($this->save_filtered_files)
			$this->filtered_files[] = $fullpath;
		if($this->include_once)
			$r = include_once($fullpath);
		else $r = include($fullpath);
		return($r);
	}
}
return;
/**/
// 		TESTEO		
$x = new Base_FileFilterIncluder(array('txt'));
//if(!$x->setExtensiones('class.php','php')){
//	echo "hay un error";
//	var_dump(Base_Errors::getLastError());
//}
$x->setSaveFilteredFiles(true);
$x->_include_once(dirname(__FILE__).'/../..',1);
var_dump($x->getFilteredFiles());

/**/