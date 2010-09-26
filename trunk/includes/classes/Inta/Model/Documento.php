<?php

/**
 * Description of Documento
 *
 * @author mati
 */
class Inta_Model_Documento extends Core_Model_Abstract{
    public function init(){
        parent::init();
        $datafields = array(
            'id',
            'id_entidad',
            'tipo_entidad',
            'path',
        );
        foreach($datafields as $datafield)
            $this->setData($datafield);
    }
    private static function getBaseDir($file=null){
    	if(!isset($file))
    		return realpath(CFG_PATH_ROOT.CONF_PATH_UPLOADS);
    	else
		return realpath(CFG_PATH_ROOT.(isset($file)?'/'.$file:''));
	}
    public static function checkTipoRecurso($tipo_recurso,$sub_directorio, $allowedExtensions='rar,ppt,xls,xlsx,doc,docx,txt,pdf,xls,bmp,png,gif,tga,jpg,jpeg,zip,tar,gz,tgz,bz2,tbz,7z,avi,mpg,mpeg,flv', $deniedExtensions='', $maxSize=0){
		if(is_array($allowedExtensions))
			$allowedExtensions = implode(',', $allowedExtensions);
		if(is_array($deniedExtensions))
			$deniedExtensions = implode(',', $deniedExtensions);

		$baseUrl = trim(Core_App::getUrlModel()->getUrl(CONF_SUBPATH_UPLOADS),'/');
		$baseDir = self::getBaseDir();

		$tipos_recursos = Core_Session::getVar('RESOURCETYPES', 'CKFINDER');
		if(!file_exists($baseDir.$sub_directorio.'/')){
			mkdir($baseDir.$sub_directorio.'/',0777,true);;
		}
		$tipos_recursos[$tipo_recurso] = Array(
				'name' => $tipo_recurso,
				'url' => $baseUrl.'/'.$sub_directorio,// . 'image',
				'directory' => $baseDir.$sub_directorio.'/',
				'maxSize' => 0,
				'allowedExtensions' => $allowedExtensions,
				'deniedExtensions' => $deniedExtensions
		);
		Core_Session::setVar('RESOURCETYPES',$tipos_recursos, 'CKFINDER');
//		$tipos_recursos = Core_Session::getVar('RESOURCETYPES', 'CKFINDER');
//		var_dump(array_keys($tipos_recursos));
	}
	public function getFullPath(){
		return $this->getBaseDir($this->getPath());
	}
	public function getMimeType(){
		$filename = $this->getFullPath();
		return $this->get_mime_type($filename);
//		$finfo = finfo_open(FILEINFO_MIME_TYPE); // devuelve el tipo mime de su extensión
//		$mime = finfo_file($finfo, $filename);
//		finfo_close($finfo);
//		return $mime;
	}
	function get_mime_type($filename, $mimePath = '/etc') {
		$fileext = substr(strrchr($filename, '.'), 1);
		if (empty($fileext)) return (false);
		$regex = "/^([\w\+\-\.\/]+)\s+(\w+\s)*($fileext\s)/i";
		$lines = file("$mimePath/mime.types");
		foreach($lines as $line) {
			if (substr($line, 0, 1) == '#') continue; // skip comments
				$line = rtrim($line) . " ";
			if (!preg_match($regex, $line, $matches)) continue; // no match to the extension
				return ($matches[1]);
		}
		return (false); // no match at all
	}
	public function download(){
		header('content-type:' . $this->getMimeType());
		Core_Http_Header::ContentDisposition(basename($this->getFullPath()));
		readfile($this->getFullPath());
		die();
	}
    public function getDbTableName()
    {
        return 'inta_documento';
    }
}
?>