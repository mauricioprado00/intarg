<?
class Admin_Translate_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarTranslate($translate){
		if(!is_a($translate,'Inta_Model_Traduccion')){
			$translate = new Inta_Model_Traduccion($translate->getData());
		}
		if(!$translate->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $translate->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Translate añadido correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar el Translate, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $translate->update()?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Translate actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar el Translate, error en el operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarTranslate($id_translate){
		if(self::eliminarTranslate($id_translate)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Translate Eliminado Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar el Translate'));
		}
	}
	public static function eliminarTranslate($id_translate){
		$translate = new Inta_Model_Traduccion();
		$translate->setId($id_translate);
		if(!$translate->load())
			return false;
		return $translate->delete(array('id'=>$id_translate));
	}
}
?>