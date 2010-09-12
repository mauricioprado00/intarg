<?
class Admin_MedioVerificacion_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarMedioVerificacion($medio_verificacion){
		if(!is_a($medio_verificacion,'Inta_Model_MedioVerificacion')){
			$medio_verificacion = new Inta_Model_MedioVerificacion($medio_verificacion->getData());
		}
		if(!$medio_verificacion->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $medio_verificacion->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('MedioVerificacion a�adida correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la MedioVerificacion, error en la operaci�n");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $medio_verificacion->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('MedioVerificacion actualizada correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la MedioVerificacion, error en la operaci�n");
			}
		}
		return($resultado);
	}
	public static function actionEliminarMedioVerificacion($id_medio_verificacion){
		if(self::eliminarMedioVerificacion($id_medio_verificacion)){
			Admin_App::getInstance()->addSuccessMessage('MedioVerificacion Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la MedioVerificacion');
		}
	}
	public static function eliminarMedioVerificacion($id_medio_verificacion){
		$medio_verificacion = new Inta_Model_MedioVerificacion();
		return($medio_verificacion->setId($id_medio_verificacion)->delete());
	}
}
?>