<?
class Admin_Menu_Helper extends Core_Singleton{
	public function getInstance(){
		return(self::getInstanceOf(__CLASS__));
	}
	public static function actionAgregarEditarMenu($menu){
		if(!is_a($menu,'Granguia_Model_Menu')){
			$menu = new Granguia_Model_Menu($menu->getData());
		}
		if(!$menu->getIdNodo()){
			$menu->setIdNodo(null);
		}
		if(!$menu->hasId()){/** aca hay que agregar a la base de datos*/
			if(!$menu->hasOrden()||!$menu->getOrden())
				$menu->setOrden(2000);
			$resultado = $menu->replace()?true:false;
			//$insertada = true;// insertarEnLaBase()
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Menu añadida correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo agregar la Menu, error en la operación");
			}
		}
		else{/** aca hay que actualizar el registro*/
			//$actualizada = true;// actualizarEnLaBase()
			$resultado = $menu->update(null, array('id_nodo'))?true:false;
			if($resultado){
				Admin_App::getInstance()->addSuccessMessage('Menu actualizada correctamente');
			}
			else{
				Admin_App::getInstance()->addErrorMessage("No se pudo actualizar la Menu, error en la operación");
			}
		}
		return($resultado);
	}
	public static function actionEliminarMenu($id_menu){
		if(self::eliminarMenu($id_menu)){
			Admin_App::getInstance()->addSuccessMessage('Menu Eliminada Correctamente');
		}
		else{
			Admin_App::getInstance()->addErrorMessage('No se pudo eliminar la Menu');
		}
	}
	public static function eliminarMenu($id_menu){
		$menu = new Granguia_Model_Menu();
		return($menu->setId($id_menu)->delete());
	}
}
?>