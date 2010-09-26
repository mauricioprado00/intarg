<?
class Admin_Anuncio_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarAnuncio($anuncio){
		if(!is_a($anuncio,'Granguia_Model_Anuncio')){
			$anuncio = new Granguia_Model_Anuncio($anuncio->getData());
		}
		if(!$anuncio->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $anuncio->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Anuncio añadida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Anuncio, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $anuncio->update()?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Anuncio actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Anuncio, error en la operación");
			}
		}
		return($resultado);
	}
	public static function actionAgregarEditarHorarioAnuncio($horario_anuncio){
		if(!is_a($horario_anuncio,'Granguia_Model_HorarioAnuncio')){
			$horario_anuncio = new Granguia_Model_HorarioAnuncio($horario_anuncio->getData());
		}
		foreach($horario_anuncio->getData() as $key=>$value){
			if(!$value)
				$horario_anuncio->setData($key, null);
		}
		if(!$horario_anuncio->hasId() || !$horario_anuncio->getId()){/** aca hay que agregar a la base de datos*/
			$resultado = $horario_anuncio->replace(null, true)?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('HorarioAnuncio añadido correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar el HorarioAnuncio, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $horario_anuncio->update(null, true)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('HorarioAnuncio actualizado correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar el HorarioAnuncio, error en la operación");
			}
		}
		//echo Core_Helper::DebugVars(Granguia_Db::getInstance()->getLastQuery());
		return($resultado);
	}
	public static function actionEliminarAnuncio($id_anuncio){
		if(self::eliminarAnuncio($id_anuncio)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Anuncio Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la Anuncio'));
		}
	}
	public static function eliminarAnuncio($id_anuncio){
		$anuncio = new Granguia_Model_Anuncio();
		$anuncio->setId($id_anuncio);
		if(!$anuncio->load())
			return false;
		if($anuncio->hasIdNodo())
			if(!Admin_Nodo_Helper::eliminarNodo($anuncio->getIdNodo()))
				return false;
		return($anuncio->delete(array('id'=>$id_anuncio)));
	}
}
?>