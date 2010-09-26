<?
class Admin_AudienciaActividad_Helper extends Core_Singleton{
	public function canEditAsistencia($audiencia_actividad){
		if(!is_a($audiencia_actividad,'Inta_Model_AudienciaActividad')){
			$audiencia_actividad = new Inta_Model_AudienciaActividad($audiencia_actividad->getData());
		}
		return $audiencia_actividad->canEditAsistencia();
	}
	public function canEditCantidadEsperada($audiencia_actividad){
		if(!is_a($audiencia_actividad,'Inta_Model_AudienciaActividad')){
			$audiencia_actividad = new Inta_Model_AudienciaActividad($audiencia_actividad->getData());
		}
		return $audiencia_actividad->canEditCantidadEsperada();
	}
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarAudienciaActividad($audiencia_actividad){
		if(!is_a($audiencia_actividad,'Inta_Model_AudienciaActividad')){
			$audiencia_actividad = new Inta_Model_AudienciaActividad($audiencia_actividad->getData());
		}
		if(!$audiencia_actividad->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $audiencia_actividad->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('AudienciaActividad añadida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la AudienciaActividad, error en la operación");
				echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
				
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $audiencia_actividad->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('AudienciaActividad actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la AudienciaActividad, error en la operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarAudienciaActividad($id_audiencia_actividad){
		if(self::eliminarAudienciaActividad($id_audiencia_actividad)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('AudienciaActividad Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la AudienciaActividad'));
		}
	}
	public static function eliminarAudienciaActividad($id_audiencia_actividad){
		$audiencia_actividad = new Inta_Model_AudienciaActividad();
		return($audiencia_actividad->setId($id_audiencia_actividad)->delete());
	}
}
?>