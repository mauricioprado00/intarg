<?
class Admin_Audiencia_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarAudiencia($audiencia){
		if(!is_a($audiencia,'Inta_Model_Audiencia')){
			$audiencia = new Inta_Model_Audiencia($audiencia->getData());
		}
		if(!$audiencia->getIdNodo()){
			$audiencia->setIdNodo(null);
		}
		if(!$audiencia->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $audiencia->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Audiencia añadida correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Audiencia, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $audiencia->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Audiencia actualizada correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Audiencia, error en la operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarAudiencia($id_audiencia){
		if(self::eliminarAudiencia($id_audiencia)){
			Admin_App::getInstance()->addSuccessMessage('Audiencia Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la Audiencia');
		}
	}
	public static function eliminarAudiencia($id_audiencia){
		$audiencia = new Inta_Model_Audiencia();
		return($audiencia->setId($id_audiencia)->delete());
	}
}
?>