<?
class Admin_ProyectoActividad_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarProyectoActividad($proyecto_actividad){
		if(!is_a($proyecto_actividad,'Inta_Model_ProyectoActividad')){
			$proyecto_actividad = new Inta_Model_ProyectoActividad($proyecto_actividad->getData());
		}
		$existente = $proyecto_actividad->setWhere(Db_Helper::equal('id_actividad'),Db_Helper::equal('id_proyecto'))->search();
		if(!$proyecto_actividad->hasId()){/** aca hay que agregar a la base de datos*/
			if($existente){
				Admin_App::getInstance()->addInfoMessage("La ProyectoActividad ya estaba agregada");
				$resultado = true;
			}
			else{
				$resultado = $proyecto_actividad->replace()?true:false;
				//$insertada = true;// insertarEnLaBase()
				if($resultado){
					Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('ProyectoActividad añadida correctamente'));
				}
				else{
					Admin_App::getInstance()->addErrorMessage("No se pudo agregar la ProyectoActividad, error en la operación");
					echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
					
				}
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			if($existente&&$existente[0]->getId()!=$proyecto_actividad->getId()){
				$proyecto_actividad->delete(array('id'=>$proyecto_actividad->getId()));
				Admin_App::getInstance()->addInfoMessage("La ProyectoActividad ya estaba agregada");
				$resultado = true;
			}
			else{
				$resultado = $proyecto_actividad->update(null)?true:false;
				if($resultado){
					Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('ProyectoActividad actualizada correctamente'));
				}
				else{
					Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la ProyectoActividad, error en la operación");
				}
			}
		}
		return($resultado);
	}
	public static function actionEliminarProyectoActividad($id_proyecto_actividad){
		if(self::eliminarProyectoActividad($id_proyecto_actividad)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('ProyectoActividad Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la ProyectoActividad'));
		}
	}
	public static function eliminarProyectoActividad($id_proyecto_actividad){
		$proyecto_actividad = new Inta_Model_ProyectoActividad();
		return($proyecto_actividad->setId($id_proyecto_actividad)->delete());
	}
}
?>