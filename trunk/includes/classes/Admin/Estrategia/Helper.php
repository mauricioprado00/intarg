<?
class Admin_Estrategia_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarEstrategia($estrategia){
		if(!is_a($estrategia,'Inta_Model_Estrategia')){
			$estrategia = new Inta_Model_Estrategia($estrategia->getData());
		}
		if(!$estrategia->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $estrategia->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Estrategia a�adida correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Estrategia, error en la operaci�n");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $estrategia->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Estrategia actualizada correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Estrategia, error en la operaci�n");
			}
		}
		return($resultado);
	}
	public static function actionEliminarEstrategia($id_estrategia){
		if(self::eliminarEstrategia($id_estrategia)){
			Admin_App::getInstance()->addSuccessMessage('Estrategia Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la Estrategia');
		}
	}
	public static function eliminarEstrategia($id_estrategia){
		$estrategia = new Inta_Model_Estrategia();
		return($estrategia->setId($id_estrategia)->delete());
	}
}
?>