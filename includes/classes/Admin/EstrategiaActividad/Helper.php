<?
class Admin_EstrategiaActividad_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarEstrategiaActividad($estrategia_actividad){
		if(!is_a($estrategia_actividad,'Inta_Model_EstrategiaActividad')){
			$estrategia_actividad = new Inta_Model_EstrategiaActividad($estrategia_actividad->getData());
		}
		$existente = $estrategia_actividad->setWhere(Db_Helper::equal('id_actividad'),Db_Helper::equal('id_estrategia'))->search();
		if(!$estrategia_actividad->hasId()){/** aca hay que agregar a la base de datos*/
			if($existente){
				Admin_App::getInstance()->addInfoMessage("La EstrategiaActividad ya estaba agregada");
				$resultado = true;
			}
			else{
				$resultado = $estrategia_actividad->replace()?true:false;
				//$insertada = true;// insertarEnLaBase()
				if($resultado){
					Admin_App::getInstance()->addSuccessMessage('EstrategiaActividad añadida correctamente');
				}
				else{
					Admin_App::getInstance()->addErrorMessage("No se pudo agregar la EstrategiaActividad, error en la operación");
					echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
					
				}
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			if($existente&&$existente[0]->getId()!=$estrategia_actividad->getId()){
				$estrategia_actividad->delete(array('id'=>$estrategia_actividad->getId()));
				Admin_App::getInstance()->addInfoMessage("La EstrategiaActividad ya estaba agregada");
				$resultado = true;
			}
			else{
				$resultado = $estrategia_actividad->update(null)?true:false;
				if($resultado){
					Admin_App::getInstance()->addSuccessMessage('EstrategiaActividad actualizada correctamente');
				}
				else{
					Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la EstrategiaActividad, error en la operación");
				}
			}
		}
		return($resultado);
	}
	public static function actionEliminarEstrategiaActividad($id_estrategia_actividad){
		if(self::eliminarEstrategiaActividad($id_estrategia_actividad)){
			Admin_App::getInstance()->addSuccessMessage('EstrategiaActividad Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la EstrategiaActividad');
		}
	}
	public static function eliminarEstrategiaActividad($id_estrategia_actividad){
		$estrategia_actividad = new Inta_Model_EstrategiaActividad();
		return($estrategia_actividad->setId($id_estrategia_actividad)->delete());
	}
}
?>