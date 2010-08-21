<?
class Admin_Archivo_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarArchivo($archivo){
		if(!is_a($archivo,'Granguia_Model_Archivo')){
			$archivo = new Granguia_Model_Archivo($archivo->getData());
		}
		if(!$archivo->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $archivo->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Archivo añadida correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Archivo, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $archivo->update()?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Archivo actualizada correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Archivo, error en la operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarArchivo($id_archivo){
		if(self::eliminarArchivo($id_archivo)){
			Admin_App::getInstance()->addSuccessMessage('Archivo Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la Archivo');
		}
	}
	public static function eliminarArchivo($id_archivo){
		$archivo = new Granguia_Model_Archivo();
		$archivo->setId($id_archivo);
		if(!$archivo->load())
			return false;
		if($archivo->hasIdNodo())
			if(!Admin_Nodo_Helper::eliminarNodo($archivo->getIdNodo()))
				return false;
		return($archivo->delete(array('id'=>$id_archivo)));
	}
}
?>