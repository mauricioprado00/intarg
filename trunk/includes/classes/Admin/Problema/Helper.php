<?
class Admin_Problema_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarProblema($problema){
		if(!is_a($problema,'Inta_Model_Problema')){
			$problema = new Inta_Model_Problema($problema->getData());
		}
		if(!$problema->getIdNodo()){
			$problema->setIdNodo(null);
		}
		if(!$problema->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $problema->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Problema añadida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Problema, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $problema->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Problema actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Problema, error en la operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarProblema($id_problema){
		if(self::eliminarProblema($id_problema)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Problema Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la Problema'));
		}
	}
	public static function eliminarProblema($id_problema){
		$problema = new Inta_Model_Problema();
		return($problema->setId($id_problema)->delete());
	}
}
?>