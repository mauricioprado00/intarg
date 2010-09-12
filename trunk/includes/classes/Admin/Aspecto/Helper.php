<?
class Admin_Aspecto_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarAspecto($aspecto){
		if(!is_a($aspecto,'Inta_Model_Aspecto')){
			$aspecto = new Inta_Model_Aspecto($aspecto->getData());
		}
		if(!$aspecto->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $aspecto->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Aspecto a�adida correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Aspecto, error en la operaci�n");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $aspecto->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Aspecto actualizada correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Aspecto, error en la operaci�n");
			}
		}
		return($resultado);
	}
	public static function actionEliminarAspecto($id_aspecto){
		if(self::eliminarAspecto($id_aspecto)){
			Admin_App::getInstance()->addSuccessMessage('Aspecto Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la Aspecto');
		}
	}
	public static function eliminarAspecto($id_aspecto){
		$aspecto = new Inta_Model_Aspecto();
		return($aspecto->setId($id_aspecto)->delete());
	}
}
?>