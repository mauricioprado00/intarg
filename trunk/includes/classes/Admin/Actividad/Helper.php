<?
class Admin_Actividad_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarActividad($actividad){
		if(!is_a($actividad,'Inta_Model_Actividad')){
			$actividad = new Inta_Model_Actividad($actividad->getData());
		}
		if(!$actividad->getIdNodo()){
			$actividad->setIdNodo(null);
		}
		if(!$actividad->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $actividad->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Actividad a�adida correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Actividad, error en la operaci�n");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $actividad->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Actividad actualizada correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Actividad, error en la operaci�n");
			}
		}
		return($resultado);
	}
	public static function actionEliminarActividad($id_actividad){
		if(self::eliminarActividad($id_actividad)){
			Admin_App::getInstance()->addSuccessMessage('Actividad Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la Actividad');
		}
	}
	public static function eliminarActividad($id_actividad){
		$actividad = new Inta_Model_Actividad();
		return($actividad->setId($id_actividad)->delete());
	}
}
?>