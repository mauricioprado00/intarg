<?
class Admin_Indicador_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarIndicador($indicador){
		if(!is_a($indicador,'Inta_Model_Indicador')){
			$indicador = new Inta_Model_Indicador($indicador->getData());
		}
		if(!$indicador->getIdNodo()){
			$indicador->setIdNodo(null);
		}
		if(!$indicador->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $indicador->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Indicador a�adida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Indicador, error en la operaci�n");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $indicador->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Indicador actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Indicador, error en la operaci�n");
			}
		}
		return($resultado);
	}
	public static function actionEliminarIndicador($id_indicador){
		if(self::eliminarIndicador($id_indicador)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Indicador Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la Indicador'));
		}
	}
	public static function eliminarIndicador($id_indicador){
		$indicador = new Inta_Model_Indicador();
		return($indicador->setId($id_indicador)->delete());
	}
}
?>