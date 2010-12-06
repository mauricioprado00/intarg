<?php
class Core_File_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public function copyAll($dirfrom, $dirto){
		$files = array_diff(scandir($dirfrom), array('.','..'));
		if($files){
			foreach($files as $file){
				if(!is_dir($dirfrom.'/'.$file)){
					copy($dirfrom.'/'.$file, $dirto.'/'.$file);
				}
				else{
					$destdir = $dirto.'/'.$file;
					if(!file_exists($destdir))
						mkdir($destdir);
					self::copyAll($dirfrom.'/'.$file, $destdir);
				}
			}
		}
	}
	public function listDir($dir, &$arr_files){
		$files = array_diff(scandir($dir), array('.','..'));
		if($files){
			foreach($files as $file){
				if(!is_dir($dir.'/'.$file)){
					$arr_files[] = $dir.'/'.$file;
				}
				else{
					self::listDir($dir.'/'.$file, $arr_files);
				}
			}
		}
	}
	public function remDir($dir){
		$files = array_diff(scandir($dir), array('.','..'));
		if($files){
			foreach($files as $file){
				if(is_dir($dir.'/'.$file)){
					self::remDir($dir.'/'.$file);
				}
			}
			foreach($files as $file){
				if(!is_dir($dir.'/'.$file)){
					@unlink($dir.'/'.$file);
				}
			}
		}
		rmdir($dir);
	}
}
?>