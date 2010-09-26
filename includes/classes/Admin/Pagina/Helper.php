<?
class Admin_Pagina_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarPagina($pagina){
		if(!is_a($pagina,'Granguia_Model_Pagina')){
			$pagina = new Granguia_Model_Pagina($pagina->getData());
		}
		if(!$pagina->hasId()){/** aca hay que agregar a la base de datos*/
			$resultado = $pagina->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Pagina añadida correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Pagina, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $pagina->update()?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Pagina actualizada correctamente'));
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Pagina, error en la operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarPagina($id_pagina){
		if(self::esPaginaInterna($id_pagina)){
			Admin_App::getInstance()->addShieldMessage('No se pueden eliminar las Páginas Internas.')
			->addInfoMessage('Las Paginas Internas son necesarias para el funcionamiento correcto del sistema');
		}
		elseif(self::eliminarPagina($id_pagina)){
			Admin_App::getInstance()->addSuccessMessage(self::getInstance()->__t('Pagina Eliminada Correctamente'));
		}
		else{
			Admin_App::getInstance()->addErrorMessage(self::getInstance()->__t('No se pudo eliminar la Pagina'));
		}
	}
	public static function esPaginaInterna($id_pagina){
		$pagina = new Granguia_Model_Pagina();
		$pagina->setWhere(Db_Helper::equal('id', $id_pagina), Db_Helper::equal('nombre_interno', '', false));
		return $pagina->searchCount();
	}
	public static function eliminarPagina($id_pagina){
		$pagina = new Granguia_Model_Pagina();
		$pagina->setId($id_pagina);
		if(!$pagina->load())
			return false;
		if($pagina->hasIdNodo())
			if(!Admin_Nodo_Helper::eliminarNodo($pagina->getIdNodo()))
				return false;
		return $pagina->delete(array('id'=>$id_pagina));
	}
}
?>