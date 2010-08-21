<?
class Admin_Nodo_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function getValidationLinkUrl(){
		return 'administrator/nodo/validate';
	}
	public static function actionAgregarEditarNodo($nodo, $post_nodo=null){
		if(!is_a($nodo,'Granguia_Model_Nodo')){
			$nodo = new Granguia_Model_Nodo($nodo->getData());
		}
		if(!$nodo->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $nodo->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Nodo añadida correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Nodo, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $nodo->update(null, array('es_home', 'es_activa'))?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Nodo actualizada correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Nodo, error en la operación");
			}
		}
		if($resultado&&isset($post_nodo)&&$post_nodo->hasBannersDinamicos()){
			$banners_dinamicos = $post_nodo->GetBannersDinamicos(true);
//			self::actionAgregarEditarBannersDinamicos($nodo, $banners_dinamicos->GetNuevos());
			self::actionAgregarEditarBannersDinamicos($nodo, $banners_dinamicos->GetAddedit());
			self::actionEliminarBannersDinamicos($banners_dinamicos->GetEliminar());
//			echo Core_Helper::DebugVars($banners_dinamicos);
		}
		return($resultado);
	}
	public static function actionEliminarBannersDinamicos($post_banners_dinamicos){
		if(!isset($post_banners_dinamicos))
			return;
		$orden = array();
		foreach($post_banners_dinamicos as $info){
			$info = json_decode(stripslashes($info));
			if(!$info)
				continue;
			$info = new Core_Object((array)$info);
			$nodo_banner_dinamico = new Granguia_Model_NodoBannerDinamico();
			$nodo_banner_dinamico->setId($info->getId());
			$nodo_banner_dinamico->delete();
		}
	}
	public static function actionAgregarEditarBannersDinamicos($nodo, $post_banners_dinamicos){
		if(!isset($post_banners_dinamicos))
			return;
		$orden = array();
		foreach($post_banners_dinamicos as $info){
//			echo Core_Helper::DebugVars($info, stripslashes($info));
			$info = json_decode(stripslashes($info));
			if(!$info)
				continue;
			$info = new Core_Object((array)$info);
//			echo Core_Helper::DebugVars($info);
			$nodo_banner_dinamico = new Granguia_Model_NodoBannerDinamico();
			$posicion = $info->getPosicion();
			if(!isset($orden[$posicion]))
				$orden[$posicion] = 0;
			if(!$info->hasIdBannerDinamico()){// es un banner aleatorio
				$nodo_banner_dinamico
					->setCantidadEspaciosHorizontal($info->getWidth())
					->setCantidadEspaciosVertical($info->getHeight());
			}
			else{//es un banner normal
				$nodo_banner_dinamico
					->setIdBannerDinamico($info->getIdBannerDinamico());
			}
			$nodo_banner_dinamico
				->setIdNodo($nodo->getId())
				->setPosicion($posicion)
				->setOrden($orden[$posicion]++)
			;
			if(!$info->hasId()){
				$resultado = $nodo_banner_dinamico->replace();
			}
			else{
				$nodo_banner_dinamico->setId($info->getId());
				$resultado = $nodo_banner_dinamico->update();
			}
			//echo Core_Helper::DebugVars($nodo_banner_dinamico->getData());
		}
	}
	public static function actionEliminarNodo($id_nodo){
		if(self::eliminarNodo($id_nodo)){
			Admin_App::getInstance()->addSuccessMessage('Nodo Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la Nodo');
		}
	}
	public static function eliminarNodo($id_nodo){
		$nodo = new Granguia_Model_Nodo();
		return($nodo->setId($id_nodo)->delete());
	}
	public static function getValidationMessage($nodo){
		$mensaje = null;
		$ret = $nodo->validateUrl();
		if($ret){
			$mensaje = array(
				'mensaje'=>$ret,
				'addFieldName'=>false,
			);
		}
		return json_encode($mensaje);
	}
}
?>