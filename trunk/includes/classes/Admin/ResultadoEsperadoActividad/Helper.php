<?
class Admin_ResultadoEsperadoActividad_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarResultadoEsperadoActividad($resultado_esperado_actividad){
		if(!is_a($resultado_esperado_actividad,'Inta_Model_ResultadoEsperadoActividad')){
			$resultado_esperado_actividad = new Inta_Model_ResultadoEsperadoActividad($resultado_esperado_actividad->getData());
		}
		$existente = $resultado_esperado_actividad->setWhere(Db_Helper::equal('id_actividad'),Db_Helper::equal('id_resultado_esperado'))->search();
		if(!$resultado_esperado_actividad->hasId()){/** aca hay que agregar a la base de datos*/
			if($existente){
				Admin_App::getInstance()->addInfoMessage("La ResultadoEsperadoActividad ya estaba agregada");
				$resultado = true;
			}
			else{
				$resultado = $resultado_esperado_actividad->replace()?true:false;
				//$insertada = true;// insertarEnLaBase()
				if($resultado){
					Admin_App::getInstance()->addSuccessMessage('ResultadoEsperadoActividad añadida correctamente');
				}
				else{
					Admin_App::getInstance()->addErrorMessage("No se pudo agregar la ResultadoEsperadoActividad, error en la operación");
					echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
					
				}
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			if($existente&&$existente[0]->getId()!=$resultado_esperado_actividad->getId()){
				$resultado_esperado_actividad->delete(array('id'=>$resultado_esperado_actividad->getId()));
				Admin_App::getInstance()->addInfoMessage("La ResultadoEsperadoActividad ya estaba agregada");
				$resultado = true;
			}
			else{
				$resultado = $resultado_esperado_actividad->update(null)?true:false;
				if($resultado){
					Admin_App::getInstance()->addSuccessMessage('ResultadoEsperadoActividad actualizada correctamente');
				}
				else{
					Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la ResultadoEsperadoActividad, error en la operación");
				}
			}
		}
		return($resultado);
	}
	public static function actionEliminarResultadoEsperadoActividad($id_resultado_esperado_actividad){
		if(self::eliminarResultadoEsperadoActividad($id_resultado_esperado_actividad)){
			Admin_App::getInstance()->addSuccessMessage('ResultadoEsperadoActividad Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la ResultadoEsperadoActividad');
		}
	}
	public static function eliminarResultadoEsperadoActividad($id_resultado_esperado_actividad){
		$resultado_esperado_actividad = new Inta_Model_ResultadoEsperadoActividad();
		return($resultado_esperado_actividad->setId($id_resultado_esperado_actividad)->delete());
	}
}
?>