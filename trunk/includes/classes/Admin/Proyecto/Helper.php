<?
class Admin_Proyecto_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarProyecto($proyecto){
		if(!is_a($proyecto,'Inta_Model_Proyecto')){
			$proyecto = new Inta_Model_Proyecto($proyecto->getData());
		}
		if(!$proyecto->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $proyecto->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Proyecto añadido correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar el Proyecto, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $proyecto->update()?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Proyecto actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar el Proyecto, error en el operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarProyecto($id_proyecto){
		if(self::eliminarProyecto($id_proyecto)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Proyecto Eliminado Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar el Proyecto'));
		}
	}
	public static function eliminarProyecto($id_proyecto){
		$proyecto = new Inta_Model_Proyecto();
		$proyecto->setId($id_proyecto);
		if(!$proyecto->load())
			return false;
		return $proyecto->delete(array('id'=>$id_proyecto));
	}
}
?>