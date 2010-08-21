<?
class Admin_Config_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public function getImgLinkUrl(){
		return CONF_SUBPATH_UPLOADS.'conf_img/';
	}
	public function getFileLinkUrl(){
		return CONF_SUBPATH_UPLOADS.'conf_files/';
	}
	public function getImgPath(){
		return realpath(CFG_PATH_ROOT.'/'.self::getImgLinkUrl()).'/';
	}
	public function getFilePath(){
		return realpath(CFG_PATH_ROOT.'/'.self::getFileLinkUrl()).'/';
	}
	public static function actionAgregarEditarConfig($post){
		$x = new Granguia_Model_Config();
		$x->loadFromArray($post->getData());
		$x->setValor(stripslashes($x->hasValor()?$x->getValor():0));
		if(!$post->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $x->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Configuración añadida correctamente');
			}
			else{
				
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Configuración, error en la operación");
				foreach($x->getTranslatedErrors() as $error){
					Admin_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $x->update()?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Configuración actualizada correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Configuración, error en la operación");
				foreach($x->getTranslatedErrors() as $error){
					Admin_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				}
			}
		}
		return($resultado);
	}
	public static function actionEliminarConfig($id_config){
		if(self::eliminarConfig($id_config)){
			Admin_App::getInstance()->addSuccessMessage('Configuración Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la Configuración');
		}
	}
	public static function eliminarConfig($id_config){
		$config = new Granguia_Model_Config();
		return($config->setId($id_config)->delete());
	}
	private static function checkSupportedImageFormat($archivo_imagen){
		$filename = $archivo_imagen->getName();
		if(!$filename)
			return(false);
		return(Core_Image_Cache::IsSupportedFormat($filename));
	}
	private static function getSupportedImageFormatsExtensions(){
		return(Core_Image_Cache::getSupportedFormatsExtensions());
	}
	private static function newFileName($filepath, $type='img'){
		$filepath = $filepath?$filepath:'undefined';
		$i = 0;
		switch($type){
			case 'file':{
				$path = self::getFilePath();
				break;
			}
			case 'img':{
				$path = self::getImgPath();
				break;
			}
		}
		do{
			$new_filepath = 
			$path.//CFG_PATH_ROOT."/img/".
			(
				$filepath?
				array_shift(explode('.', basename($filepath))).($i?'_'.$i:'').".".(array_pop(explode('.', basename($filepath)))):
				''
			);
			if(!file_exists($new_filepath))
				break;
			$i++;
		}while(true);
		return($new_filepath);
	}
	private static function createFilePath($new_file){
		$dir = dirname($new_file);
		if(!file_exists($dir))
			mkdir($dir,0777,true);
	}
	private static function moveNewFile($file, $type='img'){
		if(!$file->hasTmpName()||!$file->getTmpName()){
			echo "no se puede utilizar no tiene nombre ".get_class($file);
			return(false);
		}
		//$new_file = CFG_PATH_ROOT."/files/importados/".array_shift(explode('.', basename($file->getName()))).date('Ymd_His_u').".xls";
		$newFileName = self::newFileName($file->getName(), $type);
		self::createFilePath($newFileName);
		if(file_exists($newFileName))
			unlink($newFileName);
		$r = move_uploaded_file($file->getTmpName(),$newFileName);
		if(!$r){
			$err_msg = 'No se pudo subir '.$file->getTmpName().' a '.$newFileName;
			Admin_App::getInstance()->addWarningMessage($err_msg);
		}
		return($newFileName);
	}
	public static function moveNewFile2($name, $tmpname, $type='img', $complete_path=true){
		$newFileName = self::newFileName($name, $type);
		self::createFilePath($newFileName);
		if(file_exists($newFileName))
			unlink($newFileName);
		$r = move_uploaded_file($tmpname,$newFileName);
		if(!$r){
			$err_msg = 'No se pudo subir '.$tmpname.' a '.$newFileName;
			Admin_App::getInstance()->addWarningMessage($err_msg);
		}
		if($complete_path)
			return($newFileName);
		return(str_replace(CFG_PATH_ROOT.'/', '', $newFileName));
	}
	public static function actionSubirNuevaImagen(){
		if(!Core_Http_Files::hasParameters())
			return(false);
		$archivos_subidos = Core_Http_Files::getParameters('core_object');
		if($archivos_subidos && is_array($archivos_subidos) && count($archivos_subidos)>0 && isset($archivos_subidos['nueva_imagen'])){
			$archivo_imagen = $archivos_subidos['nueva_imagen'];
			$supported = self::checkSupportedImageFormat($archivo_imagen);
			if(!$supported){
				$err_msg = 'Formato de imagen no soportado. utilize archivos con extensiones '.implode(', ', self::getSupportedImageFormatsExtensions());
				Admin_App::getInstance()->addWarningMessage($err_msg);
				return(false);
			}
			$newFileName = self::moveNewFile($archivo_imagen, 'img');
			return(str_replace(CFG_PATH_ROOT, '/', $newFileName));
			//return(basename($newFileName));
		}
		return(false);
	
	}
	public static function checkSupportedFileFormat($archivo){
		return !in_array(array_pop(explode('.', strtolower($archivo))), array('exe','php','php3','php2','php4','php5','php6','php1','php7','pl'));
	}
	public static function actionSubirNuevoArchivo(){
		if(!Core_Http_Files::hasParameters())
			return(false);
		$archivos_subidos = Core_Http_Files::getParameters('core_object');
		if($archivos_subidos && is_array($archivos_subidos) && count($archivos_subidos)>0 && isset($archivos_subidos['nuevo_archivo'])){
			$archivo = $archivos_subidos['nuevo_archivo'];
			$supported = self::checkSupportedFileFormat($archivo);
			if(!$supported){
				$err_msg = 'Formato de archivo no soportado.';
				Admin_App::getInstance()->addWarningMessage($err_msg);
				return(false);
			}
			$newFileName = self::moveNewFile($archivo, 'file');
			return(str_replace(CFG_PATH_ROOT.'/', '', $newFileName));
		}
		return(false);
	}
}
?>