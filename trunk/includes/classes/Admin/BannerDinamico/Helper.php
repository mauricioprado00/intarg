<?
class Admin_BannerDinamico_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarBannerDinamico($banner_dinamico){
		if(!is_a($banner_dinamico,'Granguia_Model_BannerDinamico')){
			$banner_dinamico = new Granguia_Model_BannerDinamico($banner_dinamico->getData());
		}
		if(!$banner_dinamico->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $banner_dinamico->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('BannerDinamico añadida correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la BannerDinamico, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $banner_dinamico->update()?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('BannerDinamico actualizada correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la BannerDinamico, error en la operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarBannerDinamico($id_banner_dinamico){
		if(self::eliminarBannerDinamico($id_banner_dinamico)){
			Admin_App::getInstance()->addSuccessMessage('BannerDinamico Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la BannerDinamico');
		}
	}
	public static function eliminarBannerDinamico($id_banner_dinamico){
		$banner_dinamico = new Granguia_Model_BannerDinamico();
		return($banner_dinamico->setId($id_banner_dinamico)->delete());
	}
}
?>