<?
class Admin_Reporte_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarReporte($reporte){
		if(!is_a($reporte,'Inta_Model_Reporte')){
			$reporte = new Inta_Model_Reporte($reporte->getData());
		}
		if(!$reporte->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $reporte->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Reporte a�adida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Reporte, error en la operaci�n");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $reporte->update(null)?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Reporte actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Reporte, error en la operaci�n");
			}
		}
		return($resultado);
	}
	public static function actionEliminarReporte($id_reporte){
		if(self::eliminarReporte($id_reporte)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Reporte Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la Reporte'));
		}
	}
	public static function eliminarReporte($id_reporte){
		$reporte = new Inta_Model_Reporte();
		return($reporte->setId($id_reporte)->delete());
	}
}
?>