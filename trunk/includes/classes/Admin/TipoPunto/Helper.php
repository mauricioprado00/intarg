<?
class Admin_TipoPunto_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarTipoPunto($tipo_punto){
		if(!is_a($tipo_punto,'Granguia_Model_TipoPunto')){
			$tipo_punto = new Granguia_Model_TipoPunto($tipo_punto->getData());
		}
		if(!$tipo_punto->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $tipo_punto->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('TipoPunto añadida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la TipoPunto, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $tipo_punto->update()?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('TipoPunto actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la TipoPunto, error en la operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarTipoPunto($id_tipo_punto){
		if(self::eliminarTipoPunto($id_tipo_punto)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('TipoPunto Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la TipoPunto'));
		}
	}
	public static function eliminarTipoPunto($id_tipo_punto){
		$tipo_punto = new Granguia_Model_TipoPunto();
		return($tipo_punto->setId($id_tipo_punto)->delete());
	}
}
?>