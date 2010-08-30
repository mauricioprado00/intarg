<?
class Admin_TipoAudiencia_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarTipoAudiencia($tipo_audiencia){
		if(!is_a($tipo_audiencia,'Granguia_Model_TipoAudiencia')){
			$tipo_audiencia = new Granguia_Model_TipoAudiencia($tipo_audiencia->getData());
		}
		if(!$tipo_audiencia->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $tipo_audiencia->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('TipoAudiencia añadido correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar el TipoAudiencia, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $tipo_audiencia->update()?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('TipoAudiencia actualizada correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar el TipoAudiencia, error en el operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarTipoAudiencia($id_tipo_audiencia){
		if(self::eliminarTipoAudiencia($id_tipo_audiencia)){
			Admin_App::getInstance()->addSuccessMessage('TipoAudiencia Eliminado Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar el TipoAudiencia');
		}
	}
	public static function eliminarTipoAudiencia($id_tipo_audiencia){
		$tipo_audiencia = new Granguia_Model_TipoAudiencia();
		$tipo_audiencia->setId($id_tipo_audiencia);
		if(!$tipo_audiencia->load())
			return false;
		if($tipo_audiencia->hasIdNodo())
			if(!Admin_Nodo_Helper::eliminarNodo($tipo_audiencia->getIdNodo()))
				return false;
		return $tipo_audiencia->delete(array('id'=>$id_tipo_audiencia));
	}
}
?>