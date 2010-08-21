<?
class Admin_BannerCarrousel_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarBannerCarrousel($banner_carrousel){
		if(!is_a($banner_carrousel,'Granguia_Model_BannerCarrousel')){
			$banner_carrousel = new Granguia_Model_BannerCarrousel($banner_carrousel->getData());
		}
		if(!$banner_carrousel->hasId()){/** aca hay que agregar a la base de datos*/
			if(!$banner_carrousel->hasOrden()||!$banner_carrousel->getOrden())
				$banner_carrousel->setOrden(2000);
			$resultado = $banner_carrousel->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('BannerCarrousel añadida correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la BannerCarrousel, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $banner_carrousel->update(null, array('es_activa'))?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('BannerCarrousel actualizada correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la BannerCarrousel, error en la operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarBannerCarrousel($id_banner_carrousel){
		if(self::eliminarBannerCarrousel($id_banner_carrousel)){
			Admin_App::getInstance()->addSuccessMessage('BannerCarrousel Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la BannerCarrousel');
		}
	}
	public static function eliminarBannerCarrousel($id_banner_carrousel){
		$banner_carrousel = new Granguia_Model_BannerCarrousel();
		return($banner_carrousel->setId($id_banner_carrousel)->delete());
	}
}
?>