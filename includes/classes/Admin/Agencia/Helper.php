<?
class Admin_Agencia_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarAgencia($agencia){
		if(!is_a($agencia,'Inta_Model_Agencia')){
			$agencia = new Inta_Model_Agencia($agencia->getData());
		}
		if(!$agencia->getIdNodo()){
			$agencia->setIdNodo(null);
		}
		if(!$agencia->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $agencia->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Agencia añadida correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Agencia, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $agencia->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Agencia actualizada correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Agencia, error en la operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarAgencia($id_agencia){
		if(self::eliminarAgencia($id_agencia)){
			Admin_App::getInstance()->addSuccessMessage('Agencia Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la Agencia');
		}
	}
	public static function eliminarAgencia($id_agencia){
		$agencia = new Inta_Model_Agencia();
		return($agencia->setId($id_agencia)->delete());
	}
}
?>