<?
class Admin_ResultadoEsperado_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarResultadoEsperado($resultado_esperado, $indicadores, $eliminar_indicadores){
		if(!is_a($resultado_esperado,'Inta_Model_ResultadoEsperado')){
			$resultado_esperado = new Inta_Model_ResultadoEsperado($resultado_esperado->getData());
		}
		if(!$resultado_esperado->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $resultado_esperado->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('ResultadoEsperado añadida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la ResultadoEsperado, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $resultado_esperado->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('ResultadoEsperado actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la ResultadoEsperado, error en la operación");
			}
			$indicador_resultado = new Inta_Model_IndicadorResultado();
			$cantidad = count($indicadores['descripcion']);
			$cantidad_guardada = 0;
			for($i=0; $i<$cantidad; $i++){
				$arr_data = array();
				foreach($indicadores as $field=>$xxx){
					$arr_data[$field] = $indicadores[$field][$i];
				}
				$arr_data['id_resultado_esperado'] = $resultado_esperado->getId();
				$indicador_resultado = new Inta_Model_IndicadorResultado();
				$indicador_resultado->loadFromArray($arr_data);
				if(!$indicador_resultado->getId()){
					if($guardado = $indicador_resultado->replace())
						$cantidad_guardada++;
				}
				else{
					if($guardado = $indicador_resultado->update())
						$cantidad_guardada++;
				}
				//echo Core_Helper::DebugVars($indicador_resultado->getData());
			}
			if($eliminar_indicadores&&is_array($eliminar_indicadores)&&count($eliminar_indicadores)){
				foreach($eliminar_indicadores as $id_indicador_resultado){
					$indicador_resultado = new Inta_Model_IndicadorResultado();
					$indicador_resultado->delete(array('id'=>$id_indicador_resultado));
				}
			}
			//echo Core_Helper::DebugVars($indicadores);
		}
		//echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
		return($resultado);
	}
	public static function actionEliminarResultadoEsperado($id_resultado_esperado){
		if(self::eliminarResultadoEsperado($id_resultado_esperado)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('ResultadoEsperado Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la ResultadoEsperado'));
		}
	}
	//hay 3 aca
	public static function eliminarResultadoEsperado($id_resultado_esperado){
		$resultado_esperado = new Inta_Model_ResultadoEsperado();
//		return($resultado_esperado->setId($id_resultado_esperado)->delete());
		$ret = $resultado_esperado->setId($id_resultado_esperado)->delete();
		foreach($resultado_esperado->getTranslatedErrors() as $error)
			Admin_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
		return($ret);
	}
	
	
	/**
	*	IndicadorResultado
	*/
	public static function actionAgregarEditarIndicadorResultado($indicador_resultado){
		if(!is_a($indicador_resultado,'Inta_Model_IndicadorResultado')){
			$indicador_resultado = new Inta_Model_IndicadorResultado($indicador_resultado->getData());
		}
		if(!$indicador_resultado->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $indicador_resultado->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('IndicadorResultado añadida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la IndicadorResultado, error en la operación");
				foreach($indicador_resultado->getTranslatedErrors() as $error)
					Admin_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
				echo Core_Helper::DebugVars(Inta_Db::getInstance()->getLastQuery());
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $indicador_resultado->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('IndicadorResultado actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la IndicadorResultado, error en la operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarIndicadorResultado($id_indicador_resultado){
		if(self::eliminarIndicadorResultado($id_indicador_resultado)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('IndicadorResultado Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la IndicadorResultado'));
		}
	}
	public static function eliminarIndicadorResultado($id_indicador_resultado){
		$indicador_resultado = new Inta_Model_IndicadorResultado();
//		return($indicador_resultado->setId($id_indicador_resultado)->delete());
		$ret = $indicador_resultado->setId($id_indicador_resultado)->delete();
		foreach($indicador_resultado->getTranslatedErrors() as $error)
			Admin_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
		return($ret);
	}

	/**
	*	MedioVerificacionIndicadorResultado
	*/
	public static function actionAgregarEditarMedioVerificacionIndicadorResultado($medio_verificacion_indicador_resultado){
		if(!is_a($medio_verificacion_indicador_resultado,'Inta_Model_MedioVerificacionIndicadorResultado')){
			$medio_verificacion_indicador_resultado = new Inta_Model_MedioVerificacionIndicadorResultado($medio_verificacion_indicador_resultado->getData());
		}
		if(!$medio_verificacion_indicador_resultado->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $medio_verificacion_indicador_resultado->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('MedioVerificacionIndicadorResultado añadida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la MedioVerificacionIndicadorResultado, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $medio_verificacion_indicador_resultado->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('MedioVerificacionIndicadorResultado actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la MedioVerificacionIndicadorResultado, error en la operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarMedioVerificacionIndicadorResultado($id_medio_verificacion_indicador_resultado){
		if(self::eliminarMedioVerificacionIndicadorResultado($id_medio_verificacion_indicador_resultado)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('MedioVerificacionIndicadorResultado Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la MedioVerificacionIndicadorResultado'));
		}
	}
	public static function eliminarMedioVerificacionIndicadorResultado($id_medio_verificacion_indicador_resultado){
		$medio_verificacion_indicador_resultado = new Inta_Model_MedioVerificacionIndicadorResultado();
//		return($medio_verificacion_indicador_resultado->setId($id_medio_verificacion_indicador_resultado)->delete());
		$ret = $medio_verificacion_indicador_resultado->setId($id_medio_verificacion_indicador_resultado)->delete();
		foreach($medio_verificacion_indicador_resultado->getTranslatedErrors() as $error)
			Admin_App::getInstance()->addErrorMessage($error->getTranslatedDescription());
		return($ret);
	}

}
?>