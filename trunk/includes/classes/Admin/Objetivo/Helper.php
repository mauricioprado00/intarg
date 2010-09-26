<?
class Admin_Objetivo_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarObjetivo($objetivo, $ids_problemas){
		if(!is_a($objetivo,'Inta_Model_Objetivo')){
			$objetivo = new Inta_Model_Objetivo($objetivo->getData());
		}
		if(!$objetivo->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $objetivo->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Objetivo añadida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Objetivo, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $objetivo->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Objetivo actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Objetivo, error en la operación");
			}
		}
		if($resultado){
			//Inta_Model_Problema::asignarAObjetivo($objetivo->getId(), $ids_problemas);
			$objetivo->asignarProblemas($ids_problemas);
			//echo Core_Helper::DebugVars('ok');
		}
		return($resultado);
	}
	public static function actionEliminarObjetivo($id_objetivo){
		if(self::eliminarObjetivo($id_objetivo)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Objetivo Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la Objetivo'));
		}
	}
	public static function eliminarObjetivo($id_objetivo){
		$objetivo = new Inta_Model_Objetivo();
		return($objetivo->setId($id_objetivo)->delete());
	}
}
?>