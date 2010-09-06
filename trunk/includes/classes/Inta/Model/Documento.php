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
    public static function checkTipoRecurso($tipo_recurso,$sub_directorio, $allowedExtensions='rar,ppt,xls,doc,txt,pdf,xls,bmp,png,gif,tga,jpg,jpeg,zip,tar,gz,tgz,bz2,tbz,7z,avi,mpg,mpeg,flv', $deniedExtensions='', $maxSize=0){
		if(is_array($allowedExtensions))
			$allowedExtensions = implode(',', $allowedExtensions);
		if(is_array($deniedExtensions))
			$deniedExtensions = implode(',', $deniedExtensions);

		$baseUrl = trim(Core_App::getUrlModel()->getUrl(CONF_SUBPATH_UPLOADS),'/');
		$baseDir = CFG_PATH_ROOT.CONF_PATH_UPLOADS;

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
    public function getDbTableName()
    {
        return 'inta_documento';
    }
}
?>