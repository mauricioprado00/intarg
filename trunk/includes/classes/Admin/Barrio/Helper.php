<?
class Admin_Barrio_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarBarrio($barrio){
		if(!is_a($barrio,'Granguia_Model_Barrio')){
			$barrio = new Granguia_Model_Barrio($barrio->getData());
		}
		if(!$barrio->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $barrio->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Barrio añadida correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Barrio, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $barrio->update()?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Barrio actualizada correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Barrio, error en la operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarBarrio($id_barrio){
		if(self::eliminarBarrio($id_barrio)){
			Admin_App::getInstance()->addSuccessMessage('Barrio Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la Barrio');
		}
	}
	public static function eliminarBarrio($id_barrio){
		$barrio = new Granguia_Model_Barrio();
		$barrio->setId($id_barrio);
		if(!$barrio->load())
			return false;
		if($barrio->hasIdNodo())
			if(!Admin_Nodo_Helper::eliminarNodo($barrio->getIdNodo()))
				return false;
		return $barrio->delete(array('id'=>$id_barrio));
	}
}
?>